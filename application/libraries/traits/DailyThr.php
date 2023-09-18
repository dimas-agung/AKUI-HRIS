<?php defined('BASEPATH') or exit('No direct script access allowed');

trait DailyThr
{
    public function harian()
    {
        $session = $this->session->userdata('username');
        if (empty($session)) {
            redirect('admin/');
        }
        $data['title']       = 'Proses THR harian | ' . $this->Core_model->site_title();
        $data['icon']        = '<i class="fa fa-money"></i>';
        $data['breadcrumbs'] = 'Proses THR Harian ';

        $data['all_companies']     = $this->Company_model->get_company();
        $data['all_tahun_thr']    = $this->Core_model->all_tahun_gaji();


        $data['path_url'] = 'thr_harian';
        $role_resources_ids = $this->Core_model->user_role_resource();
        if (in_array('10111', $role_resources_ids)) {
            if (!empty($session)) {
                $data['subview'] = $this->load->view("admin/thr/harian", $data, TRUE);
                $this->load->view('admin/layout/layout_main', $data); //page load
            } else {
                redirect('admin/');
            }
        } else {
            redirect('admin/dashboard');
        }
    }

    public function thr_list_harian()
    {
        $result['title'] = $this->Core_model->site_title();
        $session = $this->session->userdata('username');

        if (!empty($session)) {
            $this->load->view("admin/thr/harian", $result);
        } else {
            redirect('admin/');
        }

        $draw = intval($this->input->get("draw"));
        $thr_date = $this->input->get("tanggal_thr");
        $thr_year = $this->input->get('tahun_thr');
        $role_resources_ids = $this->Core_model->user_role_resource();
        $company_id = $this->input->get("company_id");
        $pay_list = $this->THR_model->get_list_daily_payroll($thr_date, $company_id);
        // ChromePhp::log($pay_list);

        $thr_date_limit = new DateTimeImmutable($thr_date);
        $thr_date_start = $thr_date_limit->modify("-1 year");

        $fix_salary = [];
        foreach ($pay_list as $pay) {
            $start_date = new DateTime($pay->start_date);
            $end_date = new DateTime($pay->end_date);

            if ($start_date < $thr_date_start) {
                $fix_salary[] = [
                    'start_date' => $thr_date_start->format('Y-m-d'),
                    'end_date' => $end_date->format('Y-m-d'),
                    'user_id' => $pay->user_id,
                ];
            } elseif ($end_date > $thr_date_limit) {
                $fix_salary[] = [
                    'start_date' => $start_date->format('Y-m-d'),
                    'end_date' => $thr_date_limit->format('Y-m-d'),
                    'user_id' => $pay->user_id,
                ];
            }
        }

        $total_attendance = [];
        if ($fix_salary) {
            $fix_salary = $this->THR_model->get_total_attendance($fix_salary);

            foreach ($fix_salary as $att) {
                $start_date = new DateTime($att->start_date);
                $end_date = new DateTime($att->end_date);
                $key = $start_date < $thr_date_start ? $end_date->format('Ym') : $start_date->format('Ym');

                $total_attendance[$att->employee_id . '-' . $key] = $att->total;
            }
        }

        $data = $designation_ids = $department_ids = $employee_ids = [];
        foreach ($pay_list as $i => $pay) {
            /** Start fix salary difference date */
            $start_date = new DateTime($pay->start_date);
            $end_date = new DateTime($pay->end_date);

            $key = false;
            if ($start_date < $thr_date_start) {
                $key = $pay->user_id . '-' . $end_date->format('Ym');
            } elseif ($end_date > $thr_date_limit) {
                $key = $pay->user_id . '-' . $start_date->format('Ym');
            }

            if (isset($total_attendance[$key]) && $total_attendance[$key] < $pay->total_day) {
                $pay->salary = $pay->salary - ($pay->basic_salary * ($pay->total_day - $total_attendance[$key]));
            }
            /** End fix salary */

            $join_date = new DateTimeImmutable($pay->date_of_joining);
            $diff = $thr_date_limit->diff($join_date);

            if (isset($data[$pay->user_id])) {
                $data[$pay->user_id]['salary'] += $pay->salary;
            } else {
                $designation_ids[] = $pay->designation_id;
                $department_ids[] = $pay->department_id;
                $employee_ids[] = $pay->user_id;

                $data[$pay->user_id] = [
                    'full_name' => "{$pay->first_name} {$pay->last_name}",
                    'user_id' => $pay->user_id,
                    'employee_id' => $pay->employee_id,
                    'department_id' => $pay->department_id,
                    'designation_id' => $pay->designation_id,
                    'join_date' => $join_date->format('d-m-Y'),
                    'years_of_service' => str_replace("0 thn ", "", $diff->format('%y thn %m bln')),
                    'basic_salary' =>  $pay->basic_salary,
                    'salary' =>  $pay->salary,
                    'count_salary' => $diff->y ? 12 : $diff->m,
                    'bank_name' => '<span class="badge bg-red"> ? </span>',
                    'bank_account_number' => '<span class="badge bg-red"> ? </span>',
                    'bank_account_name' => '<span class="badge bg-red"> ? </span>',
                    'payslip_id' => $pay->payslip_id,
                ];
            }

            $count_salary = $data[$pay->user_id]['count_salary'];
            $data[$pay->user_id]['avg_salary'] = $count_salary ? $data[$pay->user_id]['salary'] / $count_salary : 0;
            $data[$pay->user_id]['total_thr'] = $data[$pay->user_id]['avg_salary'] / 12 * $count_salary;
        }

        // ChromePhp::log($data);

        $designation_data = [];
        if ($designation_ids) {
            $designations = $this->THR_model->get_designations($designation_ids);
            foreach ($designations as $designation) {
                $designation_data[$designation->designation_id] = $designation->designation_name;
            }
        }

        $department_data = [];
        if ($department_ids) {
            $departments = $this->THR_model->get_departments($department_ids);
            foreach ($departments as $department) {
                $department_data[$department->department_id] = $department->department_name;
            }
        }

        if ($employee_ids) {
            $bank_accounts = $this->THR_model->get_all_primary_bank_account($employee_ids);
            foreach ($bank_accounts as $key => $ba) {
                if (isset($data[$ba->employee_id])) {
                    $data[$ba->employee_id]['bank_name'] = $ba->bank_name;
                    $data[$ba->employee_id]['bank_account_number'] = $ba->account_number;
                    $data[$ba->employee_id]['bank_account_name'] = $ba->account_title;
                }
            }
        }

        $no = 1;
        $result = [];
        foreach ($data as $pay) {
            $user_id = $pay['user_id'];

            // Karyawan Departemen
            if (isset($department_data[$pay['department_id']])) {
                $department_name = $department_data[$pay['department_id']];
            } else {
                $department_name = '<span class="badge bg-red"> ? </span>';
            }

            // Karyawan Posisi
            if (isset($designation_data[$pay['designation_id']])) {
                $designation_name = $designation_data[$pay['designation_id']];
            } else {
                $designation_name = '<span class="badge bg-red"> ? </span>';
            }

            // ====================================================================================================================
            // PERIKSA PEMBAYARAN
            // ====================================================================================================================
            $act = "";
            if ($pay['payslip_id']) {
                $status = "<span class='label label-success'>{$this->lang->line('xin_payroll_paid')}</span>";

                if (in_array('101131', $role_resources_ids)) {
                    $act .= '<span data-toggle="tooltip" data-placement="top" title="' . $this->lang->line('xin_delete') . '">
                        <button type="button" class="btn icon-btn btn-xs btn-danger waves-effect waves-light" data-toggle="modal" data-target=".del_dayly_pay" data-payslip_id="' . $pay['payslip_id'] . '" data-tahun_thr="' . $thr_year . '" data-company_id="' . $company_id . '">
                            <span class="fa fa-trash"></span>
                        </button>
                    </span>';
                }

                if (in_array('10113', $role_resources_ids)) {
                    $act .= '<button style ="background-color: #ccc;border-color: #eee;" disabled type="button" class="btn icon-btn btn-xs btn-success waves-effect waves-light" title="Gaji Belum Diproses">
                        <span class="fa fa-pencil"></span>
                    </button> ';
                }

                if (in_array('10116', $role_resources_ids)) {
                    $act .= '<button style ="background-color: #ccc;border-color: #eee;" disabled type="button" class="btn icon-btn btn-xs btn-warning " title="Gaji Belum Diproses">
                        <span class="fa fa-save"></span>
                    </button>';
                }
            } else {
                $status = "<span class='label label-danger'>{$this->lang->line('xin_payroll_unpaid')}</span>";
                if (in_array('101131', $role_resources_ids)) {
                    $act .= '<button style ="background-color: #ccc;border-color: #eee;" disabled type="button" class="btn icon-btn btn-xs btn-warning " title="Gaji Belum Diproses">
                        <span class="fa fa-trash"></span>
                    </button>';
                }

                if (in_array('10113', $role_resources_ids)) {
                    $act .= '<span data-toggle="tooltip" data-placement="top" title="' . $this->lang->line('xin_edit_komponen_gaji') . '">
                            <a target="_blank" href="' . site_url() . 'admin/thr/harian_detail/' . $user_id . '">
                                <button type="button" class="btn icon-btn btn-xs btn-info waves-effect waves-light">
                                    <span class="fa fa-pencil"></span>
                                </button>
                            </a>
                    </span>';
                }

                if (in_array('10116', $role_resources_ids)) {
                    $act .= '<span data-toggle="tooltip" data-placement="top" title="' . $this->lang->line('xin_payroll_make_payment') . '">
                        <button type="button" class="btn icon-btn btn-xs btn-success waves-effect waves-light" data-toggle="modal" data-target=".emo_dayly_pay" data-employee_id="' . $user_id . '" data-tahun_thr="' . $thr_year . '" data-company_id="' . $this->input->get("company_id") . '">
                            <span class="fa fa-save"></span>
                        </button>
                    </span>';
                }
            }

            $result[] = array(
                $act,
                $no++,
                $status,
                $pay['employee_id'],
                strtoupper($pay['full_name']),
                $department_name,
                $designation_name,
                $pay['join_date'],
                $pay['years_of_service'],
                $this->Core_model->currency_sign($pay['salary']),
                $this->Core_model->currency_sign($pay['avg_salary']),
                $this->Core_model->currency_sign($pay['total_thr']),
                $pay['bank_account_number'],
                $pay['bank_name'],
            );
        }

        $output = array(
            "draw" => $draw,
            "recordsTotal" => count($result),
            "recordsFiltered" => count($result),
            "data" => $result
        );

        return json_response($output);
    }

    public function add_pay_to_all_harian()
    {

        /* Define return | here result is used to return user data and error for error message */
        $return = array('result' => '', 'error' => '', 'csrf_hash' => '');
        $return['csrf_hash'] = $this->security->get_csrf_hash();

        if ($this->input->post('add_type') == 'payroll') {
            $thr_date = $this->input->post("tanggal_thr");
            $company_id = $this->input->post("company_id");
            $session_id = $this->session->userdata('user_id');
            $user_create = $session_id['user_id'];

            $pay_list = $this->THR_model->get_list_daily_payroll($thr_date, $company_id);

            $thr_date = new DateTimeImmutable($thr_date);
            $thr_date_start = $thr_date->modify("-1 year");

            $fix_salary = [];
            foreach ($pay_list as $pay) {
                $start_date = new DateTime($pay->start_date);
                $end_date = new DateTime($pay->end_date);

                if ($start_date < $thr_date_start) {
                    $fix_salary[] = [
                        'start_date' => $thr_date_start->format('Y-m-d'),
                        'end_date' => $end_date->format('Y-m-d'),
                        'user_id' => $pay->user_id,
                    ];
                } elseif ($end_date > $thr_date) {
                    $fix_salary[] = [
                        'start_date' => $start_date->format('Y-m-d'),
                        'end_date' => $thr_date->format('Y-m-d'),
                        'user_id' => $pay->user_id,
                    ];
                }
            }

            $total_attendance = [];
            if ($fix_salary) {
                $fix_salary = $this->THR_model->get_total_attendance($fix_salary);

                foreach ($fix_salary as $att) {
                    $start_date = new DateTime($att->start_date);
                    $end_date = new DateTime($att->end_date);
                    $key = $start_date < $thr_date_start ? $end_date->format('Ym') : $start_date->format('Ym');

                    $total_attendance[$att->employee_id . '-' . $key] = $att->total;
                }
            }

            $fix_pay_list = $employee_ids = [];
            foreach ($pay_list as $i => $pay) {
                if (is_null($pay->payslip_id)) {
                    /** Start fix salary difference date */
                    $start_date = new DateTime($pay->start_date);
                    $end_date = new DateTime($pay->end_date);

                    $key = false;
                    if ($start_date < $thr_date_start) {
                        $key = $pay->user_id . '-' . $end_date->format('Ym');
                    } elseif ($end_date > $thr_date) {
                        $key = $pay->user_id . '-' . $start_date->format('Ym');
                    }

                    if (isset($total_attendance[$key]) && $total_attendance[$key] < $pay->total_day) {
                        $pay->salary = $pay->salary - ($pay->basic_salary * ($pay->total_day - $total_attendance[$key]));
                    }
                    /** End fix salary */

                    if (isset($fix_pay_list[$pay->user_id])) {
                        $fix_pay_list[$pay->user_id]->salary += $pay->salary;
                    } else {
                        $fix_pay_list[$pay->user_id] = $pay;
                    }
                }
            }

            $employee_ids = [];
            foreach ($fix_pay_list as $i => $pay) {
                $employee_ids[$pay->user_id] = $i;

                $fix_pay_list[$i]->bank_name = '';
                $fix_pay_list[$i]->bank_account_number = '';
                $fix_pay_list[$i]->bank_account_name = '';
            }

            if ($employee_ids) {
                $bank_accounts = $this->THR_model->get_all_primary_bank_account(array_keys($employee_ids));
                foreach ($bank_accounts as $ba) {
                    $fix_pay_list[$ba->employee_id]->bank_name = $ba->bank_name;
                    $fix_pay_list[$ba->employee_id]->bank_account_number = $ba->account_number;
                    $fix_pay_list[$ba->employee_id]->bank_account_name = $ba->account_title;
                }
            }

            $data = [];
            foreach ($fix_pay_list as $pay) {
                $join_date = new DateTimeImmutable($pay->date_of_joining);
                $year_of_service = $thr_date->diff($join_date);
                $count_salary = $year_of_service->y ? 12 : $year_of_service->m;
                $thr_type = $count_salary > 12 ? 'THR PENUH' : ($count_salary > 0 ? 'THR Prorate' : 'Tidak Dapat THR');

                $thr_type = '';
                $jurl = md5(uniqid($thr_date->format('Y') . $pay->user_id, true));

                $avg_salary = $count_salary ? floor($pay->salary / $count_salary) : 0;
                $total_thr = floor($avg_salary / 12 * $count_salary);

                $data[] = [
                    'employee_id' => $pay->user_id,
                    'department_id' => $pay->department_id,
                    'doj' => $pay->date_of_joining,
                    'company_id' => $pay->company_id,
                    'location_id' => $pay->location_id,
                    'designation_id' => $pay->designation_id,
                    'wages_type' => $pay->wages_type,
                    'tahun_thr' => $thr_date->format('Y'),
                    'tanggal_thr' => $thr_date->format('Y-m-d'),
                    'basic_salary' => $pay->basic_salary ?: 0,
                    'total_jumlah' => $avg_salary,
                    'note' => $thr_type,
                    'net_salary' => $total_thr,
                    'rekening_name' => $pay->bank_account_name,
                    'rekening_no' => $pay->bank_account_number,
                    'bank_name' => $pay->bank_name,
                    'is_payment' => '1',
                    'payslip_type' => 'full_daily',
                    'payslip_key' => $jurl,
                    'year_to_date' => date('Y-m-d'),
                    'created_at' => date('Y-m-d h:i:s'),
                    'created_by' => $user_create,
                    'pay_comments' => '',
                ];
            }

            $result = count($data) > 0 ? $this->THR_model->add_thr_daily($data, true) : true;

            if ($result) {
                $return['result'] = 'THR Kolektif Tahun ' . $thr_date->format('Y') . ' Berhasil Disimpan';
            } else {
                $return['error'] = $this->lang->line('xin_error_msg');
            }

            return json_response($return);
        }
    }

    public function pay_daily_thr_del()
    {
        $session = $this->session->userdata('username');
        if (empty($session)) {
            redirect('admin/');
        }
        $data['title'] = $this->Core_model->site_title();
        $payslip_id = $this->input->get('payslip_id');

        $thr = $this->THR_model->get_detail_daily_thr($payslip_id);

        $data = compact('thr');

        if (!empty($session)) {
            $this->load->view('admin/thr/dialog_make_payment_daily_delete', $data);
        } else {
            redirect('admin/');
        }
    }

    public function del_pay_daily()
    {
        if ($this->input->post('add_type') == 'del_daily_payment') {
            $Return = array('result' => '', 'error' => '', 'csrf_hash' => '');
            $Return['csrf_hash'] = $this->security->get_csrf_hash();


            $id  = $this->input->post('payslip_id');

            $result = $this->THR_model->delete_thr_daily($id, null, true);

            if ($result) {

                $Return['result'] = 'THR Harian Berhasil Dihapus';
            } else {
                $Return['error'] = $this->lang->line('xin_error_msg');
            }

            return json_response($Return);
        }
    }

    public function pay_salary_daily()
    {
        $session = $this->session->userdata('username');
        if (empty($session)) {
            redirect('admin/');
        }
        $data['title'] = $this->Core_model->site_title();

        $employee_id = $this->input->get('employee_id');
        $thr_date = $this->input->get('tanggal_thr');
        $company_id = $this->input->get('company_id');

        $thr_list = $this->THR_model->get_detail_daily_payroll($thr_date, $employee_id, $company_id);

        $thr_date_limit = new DateTimeImmutable($thr_date);
        $thr_date_start = $thr_date_limit->modify("-1 year");

        $fix_salary = [];
        foreach ($thr_list as $pay) {
            $start_date = new DateTime($pay->start_date);
            $end_date = new DateTime($pay->end_date);

            if ($start_date < $thr_date_start) {
                $fix_salary[] = [
                    'start_date' => $thr_date_start->format('Y-m-d'),
                    'end_date' => $end_date->format('Y-m-d'),
                    'user_id' => $pay->user_id,
                ];
            } elseif ($end_date > $thr_date_limit) {
                $fix_salary[] = [
                    'start_date' => $start_date->format('Y-m-d'),
                    'end_date' => $thr_date_limit->format('Y-m-d'),
                    'user_id' => $pay->user_id,
                ];
            }
        }

        $total_attendance = [];
        if ($fix_salary) {
            $fix_salary = $this->THR_model->get_total_attendance($fix_salary);

            foreach ($fix_salary as $att) {
                $start_date = new DateTime($att->start_date);
                $end_date = new DateTime($att->end_date);
                $key = $start_date < $thr_date_start ? $end_date->format('Ym') : $start_date->format('Ym');

                $total_attendance[$att->employee_id . '-' . $key] = $att->total;
            }
        }

        $thr = [];
        foreach ($thr_list as $i => $pay) {
            /** Start fix salary difference date */
            $start_date = new DateTime($pay->start_date);
            $end_date = new DateTime($pay->end_date);

            $key = false;
            if ($start_date < $thr_date_start) {
                $key = $pay->user_id . '-' . $end_date->format('Ym');
            } elseif ($end_date > $thr_date_limit) {
                $key = $pay->user_id . '-' . $start_date->format('Ym');
            }

            if (isset($total_attendance[$key]) && $total_attendance[$key] < $pay->total_day) {
                $pay->salary = $pay->salary - ($pay->basic_salary * ($pay->total_day - $total_attendance[$key]));
            }
            /** End fix salary */

            $join_date = new DateTimeImmutable($pay->date_of_joining);
            $diff = $thr_date_limit->diff($join_date);

            if (isset($thr['user_id'])) {
                $thr['salary'] += $pay->salary;
            } else {
                $designation_ids[] = $pay->designation_id;
                $department_ids[] = $pay->department_id;
                $employee_ids[] = $pay->user_id;

                $thr = [
                    'thr_date' => $thr_date_limit,
                    'full_name' => "{$pay->first_name} {$pay->last_name}",
                    'user_id' => $pay->user_id,
                    'company_id' => $pay->company_id,
                    'location_id' => $pay->location_id,
                    'wages_type' => $pay->wages_type,
                    'employee_id' => $pay->employee_id,
                    'department_id' => $pay->department_id,
                    'designation_id' => $pay->designation_id,
                    'join_date' => $join_date,
                    'years_of_service' => str_replace("0 thn ", "", $diff->format('%y thn %m bln')),
                    'basic_salary' =>  $pay->basic_salary,
                    'salary' =>  $pay->salary,
                    'count_salary' => $diff->y ? 12 : $diff->m,
                    'bank_name' => null,
                    'bank_account_number' => null,
                    'bank_account_name' => null,
                    'payslip_id' => $pay->payslip_id,
                ];
            }

            $count_salary = $thr['count_salary'];
            $thr['avg_salary'] = $count_salary ? $thr['salary'] / $count_salary : 0;
            $thr['total_thr'] = $thr['avg_salary'] / 12 * $count_salary;
        }

        $thr = (object) $thr;

        $designation = null;
        if ($thr->designation_id) {
            $designation = $this->THR_model->get_designations($thr->designation_id, true);
        }

        $department = null;
        if ($thr->department_id) {
            $department = $this->THR_model->get_departments($thr->department_id, true);
        }

        $bank_account = $this->THR_model->get_all_primary_bank_account($thr->user_id, true);
        if ($bank_account) {
            $thr->bank_name = $bank_account->bank_name;
            $thr->bank_account_number = $bank_account->account_number;
            $thr->bank_account_name = $bank_account->account_title;
        }

        $data = compact('thr', 'designation', 'department', 'bank_account');

        if (!empty($session)) {
            $this->load->view('admin/thr/dialog_make_payment_daily', $data);
        } else {
            redirect('admin/');
        }
    }

    public function add_pay_daily()
    {
        if ($this->input->post('add_type') == 'add_daily_payment') {

            /* Define return | here result is used to return user data and error for error message */

            $Return = array('result' => '', 'error' => '', 'csrf_hash' => '');
            $Return['csrf_hash'] = $this->security->get_csrf_hash();

            $session_id = $this->session->userdata('user_id');
            $user_create = $session_id['user_id'];

            $user_id    = $this->input->post('emp_id');
            $tahun_thr  = $this->input->post('tahun_thr');

            $pay_count = $this->THR_model->check_thr_daily($user_id, $tahun_thr);

            if ($pay_count) {
                $this->THR_model->delete_thr_daily($user_id, $tahun_thr);
            }

            $jurl = md5(uniqid($tahun_thr . $user_id, true));
            $data = array(
                'employee_id' => $this->input->post('emp_id'),
                'department_id' => $this->input->post('employee_department_id'),
                'doj' => $this->input->post('employee_date_of_joining'),
                'company_id' => $this->input->post('employee_company_id'),
                'location_id' => $this->input->post('employee_location_id'),
                'designation_id' => $this->input->post('employee_designation_id'),
                'wages_type' => $this->input->post('employee_wages_type'),
                'tahun_thr' => $this->input->post('thr_year'),
                'tanggal_thr' => $this->input->post('thr_date'),
                'basic_salary' => $this->input->post('basic_salary') ?: 0,
                'total_jumlah' => $this->input->post('total_gaji'),
                'note' => $this->input->post('thr_type'),
                'net_salary' => $this->input->post('total_net_salary'),
                'rekening_name' => $this->input->post('rekening_name'),
                'rekening_no' => $this->input->post('rekening_no'),
                'bank_name' => $this->input->post('bank_name'),
                'is_payment' => '1',
                'payslip_type' => 'full_daily',
                'payslip_key' => $jurl,
                'year_to_date' => date('Y-m-d'),
                'created_at' => date('Y-m-d h:i:s'),
                'created_by' => $user_create,
                'pay_comments' => '',
            );

            $result = $this->THR_model->add_thr_daily($data);

            if ($result) {
                $Return['result'] = 'THR Individu Tahun ' . $tahun_thr . ' Berhasil Disimpan';
            } else {
                $Return['error'] = $this->lang->line('xin_error_msg');
            }

            $this->output($Return);
            exit;
        }
    }

    public function harian_detail()
    {

        $session = $this->session->userdata('username');
        if (empty($session)) {
            redirect('admin/');
        }

        $role_resources_ids = $this->Core_model->user_role_resource();
        if (!in_array('1010', $role_resources_ids)) {
            redirect('admin/thr/harian');
        }

        $id = $this->uri->segment(4);
        $result = $this->Employees_model->read_employee_information($id);
        if (is_null($result)) {
            redirect('admin/thr/harian');
        }

        $data = array(
            'breadcrumbs'  => 'Edit Komponen THR',
            'icon'         => '<i class="fa fa-pencil"></i>',
            'path_url'     => 'employees_detail_payroll_harian',
            'title'        => 'Edit Komponen THR | ' . $this->Core_model->site_title(),

            'first_name' => $result[0]->first_name,
            'last_name' => $result[0]->last_name,
            'user_id' => $result[0]->user_id,


            'wages_type' => $result[0]->wages_type,
            'grade_type' => $result[0]->grade_type,
            'basic_salary' => $result[0]->basic_salary,

            'all_departments' => $this->Department_model->all_departments(),
            'all_designations' => $this->Designation_model->all_designations(),
            'all_user_roles' => $this->Roles_model->all_user_roles(),

        );

        $data['subview'] = $this->load->view("admin/thr/harian_detail", $data, TRUE);
        $this->load->view('admin/layout/layout_main', $data); //page load
    }
}
