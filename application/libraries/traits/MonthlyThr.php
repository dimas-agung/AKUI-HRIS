<?php defined('BASEPATH') or exit('No direct script access allowed');

trait MonthlyThr
{
    public function test()
    {
        return json_response($this->Core_model->currency_sign(2650000));
    }

    public function bulanan()
    {
        $session = $this->session->userdata('username');
        if (empty($session)) {
            redirect('admin/');
        }
        $data['title']          = 'Proses THR Bulanan | ' . $this->Core_model->site_title();
        $data['icon']           = '<i class="fa fa-money"></i>';
        $data['breadcrumbs']    = 'Proses THR Bulanan ';

        $data['all_companies']  = $this->Company_model->get_company();
        $data['all_tahun_thr']  = $this->Core_model->all_tahun_gaji();

        $data['path_url'] = 'thr_bulanan';
        $role_resources_ids = $this->Core_model->user_role_resource();
        if (in_array('10111', $role_resources_ids)) {
            if (!empty($session)) {
                $data['subview'] = $this->load->view("admin/thr/bulanan", $data, TRUE);
                $this->load->view('admin/layout/layout_main', $data); //page load
            } else {
                redirect('admin/');
            }
        } else {
            redirect('admin/dashboard');
        }
    }

    public function thr_list_bulanan()
    {

        $data['title'] = $this->Core_model->site_title();
        $session = $this->session->userdata('username');

        if (!empty($session)) {

            $this->load->view("admin/thr/bulanan", $data);
        } else {

            redirect('admin/');
        }
        // Datatables Variables
        $draw   = intval($this->input->get("draw"));
        $start  = intval($this->input->get("start"));
        $length = intval($this->input->get("length"));

        // date and employee id/company id
        $p_date             = $this->input->get("tahun_thr");
        $tanggal_thr        = $this->input->get("tanggal_thr");

        $role_resources_ids = $this->Core_model->user_role_resource();

        $user_info          = $this->Core_model->read_user_info($session['user_id']);
        $system = $this->Core_model->read_setting_info(1);

        $payslip = $this->Payroll_model->get_comp_template_bulanan($this->input->get("company_id"));

        $data   = array();
        $no     = 1;

        foreach ($payslip->result() as $r) {

            // ====================================================================================================================
            // DATA KARYAWAN
            // ====================================================================================================================

            // Karyawan NIP
            $emp_id = $r->employee_id;

            // grade
            $employee = $this->Core_model->read_employee_info_data($r->employee_id);
            if (!is_null($employee)) {

                $employee_user_id           = $employee[0]->user_id;
                $employee_grade_type        = $employee[0]->grade_type;
                $employee_wages_type        = $employee[0]->wages_type;
                $employee_name              = $employee[0]->first_name . ' ' . $employee[0]->last_name;
                $employee_department_id     = $employee[0]->department_id;
                $employee_designation_id    = $employee[0]->designation_id;
                $employee_emp_status        = $employee[0]->emp_status;
                $employee_date_of_joining   = $employee[0]->date_of_joining;
                $employee_basic_salary      = $employee[0]->basic_salary;
                $employee_flag              = $employee[0]->flag;
                $employee_email             = $employee[0]->email;
            } else {
                $employee_user_id           = '';
                $employee_grade_type        = '';
                $employee_wages_type        = '';
                $employee_name              = '';
                $employee_department_id     = '';
                $employee_designation_id    = '';
                $employee_emp_status        = '';
                $employee_date_of_joining   = '';
                $employee_basic_salary      = '';
                $employee_flag              = '';
                $employee_email             = '';
            }

            // grade
            $grade_type = $this->Core_model->read_user_jenis_grade($employee_grade_type);
            if (!is_null($grade_type)) {
                $jenis_grade       = $grade_type[0]->jenis_grade_keterangan;
                $jenis_grade_warna = $grade_type[0]->warna;
            } else {
                $jenis_grade = '<span class="badge bg-red"> ? </span>';
                $jenis_grade_warna = '';
            }


            // jenis gaji
            $wages_type = $this->Core_model->read_user_jenis_gaji($employee_wages_type);
            // user full name
            if (!is_null($wages_type)) {
                $jenis_gaji       = $wages_type[0]->jenis_gaji_keterangan;
                $jenis_gaji_warna = $wages_type[0]->warna;
            } else {
                $jenis_gaji = '<span class="badge bg-red"> ? </span>';
                $jenis_gaji_warna = '';
            }

            // Karyawan Nama
            $emp_name = $employee_name;

            // Karyawan Departemen
            $department = $this->Department_model->read_department_information($employee_department_id);
            if (!is_null($department)) {
                $department_name = $department[0]->department_name;
            } else {
                $department_name = '<span class="badge bg-red"> ? </span>';
            }

            // Karyawan Posisi
            $designation = $this->Designation_model->read_designation_information($employee_designation_id);
            if (!is_null($designation)) {
                $designation_name = $designation[0]->designation_name;
            } else {
                $designation_name = '<span class="badge bg-red"> ? </span>';
            }

            // Karyawan Rekening No
            $rekening = $this->Employees_model->get_employee_bank_account_last($employee_user_id);
            if (!is_null($rekening)) {
                $rekening_name = $rekening[0]->account_number;
                $bank_name     = $rekening[0]->bank_name;
            } else {
                $rekening_name = '<span class="badge bg-red"> ? </span>';
                $bank_name     = '<span class="badge bg-red"> ? </span>';
            }

            $cek_karyawan_status = $employee_emp_status;

            if ($cek_karyawan_status != '') {
                $karyawan_status = $employee_emp_status;
            } else {
                $karyawan_status = '<span class="badge bg-red"> ? </span>';
            }

            // Karyawan Status
            $emp_status =  $this->Employees_model->read_employee_contract_information2($employee_user_id);
            if (!is_null($emp_status)) {
                $emp_status_name = $emp_status[0]->name_type;
            } else {

                if ($karyawan_status == 'Tetap') {
                    $emp_status_name = 'Tetap';
                } else {
                    $emp_status_name = '<span class="badge bg-red"> ? </span>';
                }
            }

            // Karyawan Masa kerja
            date_default_timezone_set("Asia/Jakarta");

            $tanggal1 = new DateTime($employee_date_of_joining);
            $tanggal2 = new DateTime($tanggal_thr);

            if ($tanggal2->diff($tanggal1)->y == 0) {

                // $jum_bulan = $tanggal2->diff($tanggal1)->m;

                if ($tanggal2->diff($tanggal1)->d >= 0 and $tanggal2->diff($tanggal1)->d <= 15) {

                    $jum_bulan   = $tanggal2->diff($tanggal1)->m . ' bln';
                } else if ($tanggal2->diff($tanggal1)->d >= 16 and $tanggal2->diff($tanggal1)->d <= 31) {

                    $jum_bulan   = 1 + $tanggal2->diff($tanggal1)->m . ' bln';
                }

                $selisih   = $tanggal2->diff($tanggal1)->m . ' bln' . ' ' . $tanggal2->diff($tanggal1)->d . ' hr' . '<br>' . $jum_bulan;
            } else {
                $selisih   = $tanggal2->diff($tanggal1)->y . ' thn' . ' ' . $tanggal2->diff($tanggal1)->m . ' bln';
                $jum_bulan = $tanggal2->diff($tanggal1)->y * 12 + ($tanggal2->diff($tanggal1)->m);
            }

            // ====================================================================================================================
            // Tahun THR
            // ====================================================================================================================

            // Bulan
            $p_year = $this->input->get('tahun_thr');

            $tahun_thr = $p_year;



            // ====================================================================================================================
            // KOMPONEN GAJI - TAMBAH
            // ====================================================================================================================
            // ====================================================================================================================
            // TETAP
            // ====================================================================================================================
            // ============================================================================================================
            // 1: salary type
            // ============================================================================================================
            // $wages_type = $this->lang->line('xin_payroll_full_tTime');
            $basic_salary = $employee_basic_salary;

            // ============================================================================================================
            // 2: Tunjangan
            // ============================================================================================================

            // 1 - Tunj. Jabatan
            $salary_tunj_jabatan = $this->Employees_model->read_salary_allowances_jabatan_tahun($employee_user_id, $tahun_thr);
            $count_tunj_jabatan  = $this->Employees_model->count_employee_allowances_jabatan_tahun($employee_user_id, $tahun_thr);
            $jumlah_tunj_jabatan = 0;
            if ($count_tunj_jabatan > 0) {
                foreach ($salary_tunj_jabatan as $tunj_jabatan) {
                    $jumlah_tunj_jabatan += $tunj_jabatan->tnj_jabatan;
                }
            } else {
                $jumlah_tunj_jabatan = 0;
            }


            // ====================================================================================================================
            // HITUNG
            // ====================================================================================================================

            $total_jumlah     = $basic_salary + $jumlah_tunj_jabatan;

            if ($jum_bulan > 12) {
                $faktor_kali = 12;
            } else {
                $faktor_kali = $jum_bulan;
            }


            $total_net_salary = ($total_jumlah / 12) * $faktor_kali;

            // ====================================================================================================================
            // PERIKSA PEMBAYARAN
            // ====================================================================================================================

            $payment_check = $this->Payroll_model->read_make_payment_thr_check_bulanan($employee_user_id, $tahun_thr);

            // echo "<pre>";
            // 	print_r( $this->db->last_query() );
            // 	echo "</pre>";
            // 	die();

            if ($payment_check->num_rows() > 1) {
                $make_payment = $this->Payroll_model->read_make_payment_thr_bulanan($employee_user_id, $tahun_thr);

                // echo "<pre>";
                // print_r( $this->db->last_query() );
                // echo "</pre>";
                // die();

                $status       = '<span class="label label-success">' . $this->lang->line('xin_payroll_paid') . '</span>';

                if (in_array('101131', $role_resources_ids)) {
                    $delete = '<span data-toggle="tooltip" data-placement="top" title="' . $this->lang->line('xin_delete') . '">
                                                <button type="button" class="btn icon-btn btn-xs btn-danger waves-effect waves-light" data-toggle="modal" data-target=".del_monthly_pay" data-payslip_id="' . $make_payment[0]->payslip_id . '" data-tahun_thr="' . $tahun_thr . '" data-company_id="' . $this->input->get("company_id") . '">
                                                    <span class="fa fa-trash"></span>
                                                </button>
                                            </span>';
                } else {
                    $delete = '';
                }

                if (in_array('10113', $role_resources_ids)) {
                    $edit_opt = '<button style ="background-color: #ccc;border-color: #eee;" disabled type="button" class="btn icon-btn btn-xs btn-success waves-effect waves-light" title="Gaji Belum Diproses">
                                                    <span class="fa fa-pencil"></span>
                                                </button> ';
                } else {
                    $edit_opt = '';
                }

                if (in_array('10116', $role_resources_ids)) {
                    $bpay = '<button style ="background-color: #ccc;border-color: #eee;" disabled type="button" class="btn icon-btn btn-xs btn-warning " title="Gaji Belum Diproses">
                                                    <span class="fa fa-save"></span>
                                            </button>';
                } else {
                    $bpay = '';
                }
            } else if ($payment_check->num_rows() > 0) {

                $make_payment = $this->Payroll_model->read_make_payment_thr_bulanan($employee_user_id, $tahun_thr);


                // echo "<pre>";
                // print_r( $this->db->last_query() );
                // echo "</pre>";
                // die();

                $status       = '<span class="label label-success">' . $this->lang->line('xin_payroll_paid') . '  </span>';


                if (in_array('101131', $role_resources_ids)) {
                    $delete = '<span data-toggle="tooltip" data-placement="top" title="' . $this->lang->line('xin_delete') . '">
                                                <button type="button" class="btn icon-btn btn-xs btn-danger waves-effect waves-light" data-toggle="modal" data-target=".del_monthly_pay" data-payslip_id="' . $make_payment[0]->payslip_id . '" data-tahun_thr="' . $tahun_thr . '" data-company_id="' . $this->input->get("company_id") . '">
                                                    <span class="fa fa-trash"></span>
                                                </button>
                                            </span>';
                } else {
                    $delete = '';
                }

                if (in_array('10113', $role_resources_ids)) {

                    $edit_opt = '<button style ="background-color: #ccc;border-color: #eee;" disabled type="button" class="btn icon-btn btn-xs btn-success waves-effect waves-light" title="Gaji Belum Diproses">
                                                    <span class="fa fa-pencil"></span>
                                                 </button> ';
                } else {
                    $edit_opt = '';
                }

                if (in_array('10116', $role_resources_ids)) {
                    $bpay = '<button style ="background-color: #ccc;border-color: #eee;" disabled type="button" class="btn icon-btn btn-xs btn-warning " title="Gaji Belum Diproses">
                                                <span class="fa fa-save"></span>
                                            </button>';
                } else {
                    $bpay = '';
                }
            } else {

                $make_payment = $this->Payroll_model->read_make_payment_thr_bulanan($employee_user_id, $tahun_thr);


                // echo "<pre>";
                // print_r( $this->db->last_query() );
                // echo "</pre>";
                // die();

                $status = '<span class="label label-danger">' . $this->lang->line('xin_payroll_unpaid') . '</span>';


                if (in_array('10113', $role_resources_ids)) {
                    $delete = '<button style ="background-color: #ccc;border-color: #eee;" disabled type="button" class="btn icon-btn btn-xs btn-warning " title="Gaji Belum Diproses">
                                                <span class="fa fa-trash"></span>
                                            </button>';
                } else {
                    $delete = '';
                }



                if (in_array('10113', $role_resources_ids)) {
                    $edit_opt = '<span data-toggle="tooltip" data-placement="top" title="' . $this->lang->line('xin_edit_komponen_gaji') . '">
                                                        <a target="_blank" href="' . site_url() . 'admin/thr/bulanan_detail/' . $employee_user_id . '">
                                                            <button type="button" class="btn icon-btn btn-xs btn-info waves-effect waves-light">
                                                                <span class="fa fa-pencil"></span>
                                                            </button>
                                                        </a>
                                                </span>';
                } else {
                    $edit_opt = '';
                }

                if (in_array('10116', $role_resources_ids)) {
                    $bpay = '<span data-toggle="tooltip" data-placement="top" title="' . $this->lang->line('xin_payroll_make_payment') . '">
                                                <button type="button" class="btn icon-btn btn-xs btn-success waves-effect waves-light" data-toggle="modal" data-target=".emo_monthly_pay" data-employee_id="' . $employee_user_id . '" data-tahun_thr="' . $tahun_thr . '" data-company_id="' . $this->input->get("company_id") . '">
                                                    <span class="fa fa-save"></span>
                                                </button>
                                            </span>';
                } else {
                    $bpay = '';
                }
            }

            //action link
            $act = $delete . $edit_opt . $bpay;


            $data[] = array(
                $act,
                $no,
                $status,
                $tahun_thr,
                $tanggal_thr,
                $emp_id,
                strtoupper($emp_name),
                strtoupper($department_name),
                strtoupper($designation_name),
                date("d-m-Y", strtotime($employee_date_of_joining)),
                $selisih,
                $karyawan_status,
                $emp_status_name,
                $jenis_grade,

                $this->Core_model->currency_sign($basic_salary),
                $this->Core_model->currency_sign($jumlah_tunj_jabatan),
                $this->Core_model->currency_sign($total_jumlah),

                $this->Core_model->currency_sign($total_net_salary),

                $rekening_name,
                $bank_name,
                $employee_email,

            );
            $no++;
        }

        $output = array(
            "draw" => $draw,
            "recordsTotal" => $payslip->num_rows(),
            "recordsFiltered" => $payslip->num_rows(),
            "data" => $data
        );

        return json_response($output);
    }

    public function add_pay_to_all_bulanan()
    {

        /* Define return | here result is used to return user data and error for error message */
        $Return = array('result' => '', 'error' => '', 'csrf_hash' => '');
        $Return['csrf_hash'] = $this->security->get_csrf_hash();

        if ($this->input->post('add_type') == 'payroll') {

            $system = $this->Core_model->read_setting_info(1);
            $system_settings = system_settings_info(1);
            if ($system_settings->online_payment_account == '') {
                $online_payment_account = 0;
            } else {
                $online_payment_account = $system_settings->online_payment_account;
            }

            $company_id   = $this->input->post("company_id");
            $tahun_thr    = $this->input->post("tahun_thr");
            $tanggal_thr  = $this->input->post('tanggal_thr');

            // echo "<pre>";
            // print_r($this->db->last_query());
            // print_r( $company_id );
            // print_r( $tahun_thr );
            // echo "</pre>";
            // die();

            if ($company_id != 0) {
                $eresult = $this->Payroll_model->get_company_payroll_employees_bulanan($company_id);
                $result = $eresult->result();
            } else {
                $Return['error'] = $this->lang->line('xin_record_not_found');
            }

            foreach ($result as $empid) {


                // ====================================================================================================================
                // DATA KARYAWAN
                // ====================================================================================================================

                $user_id = $empid->user_id;

                $employee = $this->Core_model->read_user_info($user_id);
                if (!is_null($employee)) {

                    $employee_user_id           = $employee[0]->user_id;
                    $employee_grade_type        = $employee[0]->grade_type;
                    $employee_wages_type        = $employee[0]->wages_type;
                    $employee_name              = $employee[0]->first_name . ' ' . $employee[0]->last_name;

                    $employee_company_id        = $employee[0]->company_id;
                    $employee_location_id       = $employee[0]->location_id;
                    $employee_department_id     = $employee[0]->department_id;
                    $employee_designation_id    = $employee[0]->designation_id;
                    $employee_emp_status        = $employee[0]->emp_status;
                    $employee_date_of_joining   = $employee[0]->date_of_joining;
                    $employee_basic_salary      = $employee[0]->basic_salary;
                    $employee_flag              = $employee[0]->flag;
                } else {
                    $employee_user_id           = '';
                    $employee_grade_type        = '';
                    $employee_wages_type        = '';
                    $employee_name              = '';

                    $employee_company_id        = '';
                    $employee_location_id       = '';
                    $employee_department_id     = '';
                    $employee_designation_id    = '';
                    $employee_emp_status        = '';
                    $employee_date_of_joining   = '';
                    $employee_basic_salary      = '';
                    $employee_flag              = '';
                }

                date_default_timezone_set("Asia/Jakarta");

                $tanggal1 = new DateTime($employee_date_of_joining);
                $tanggal2 = new DateTime($tanggal_thr);

                if ($tanggal2->diff($tanggal1)->y == 0) {

                    // $jum_bulan = $tanggal2->diff($tanggal1)->m;

                    if ($tanggal2->diff($tanggal1)->d >= 0 and $tanggal2->diff($tanggal1)->d <= 15) {

                        $jum_bulan   = $tanggal2->diff($tanggal1)->m . ' bln';
                    } else if ($tanggal2->diff($tanggal1)->d >= 16 and $tanggal2->diff($tanggal1)->d <= 31) {

                        $jum_bulan   = 1 + $tanggal2->diff($tanggal1)->m . ' bln';
                    }

                    $selisih   = $tanggal2->diff($tanggal1)->m . ' bln' . ' ' . $tanggal2->diff($tanggal1)->d . ' hr' . '<br>' . $jum_bulan;
                } else {
                    $selisih   = $tanggal2->diff($tanggal1)->y . ' thn' . ' ' . $tanggal2->diff($tanggal1)->m . ' bln';
                    $jum_bulan = $tanggal2->diff($tanggal1)->y * 12 + ($tanggal2->diff($tanggal1)->m);
                }



                // ====================================================================================================================
                // JIKA ADA -> HAPUS
                // ====================================================================================================================

                $pay_count = $this->Payroll_model->read_make_payment_thr_check_bulanan_company($user_id, $tahun_thr);

                if ($pay_count->num_rows() > 0) {

                    $pay_val = $this->Payroll_model->read_make_payment_thr_bulanan_company($user_id, $tahun_thr);

                    $this->payslip_delete_all_bulanan($pay_val[0]->payslip_id);
                }

                // ====================================================================================================================
                // (+) KOMPONEN GAJI - TAMBAH
                // ====================================================================================================================

                // ****************************************************************************************************************
                // >> TETAP
                // ****************************************************************************************************************

                // ============================================================================================================
                // 1: salary type
                // ============================================================================================================

                $basic_salary = $empid->basic_salary;

                // ============================================================================================================
                // 2: Tunjangan
                // ============================================================================================================

                // 1 - Tunj. Jabatan
                $salary_tunj_jabatan = $this->Employees_model->read_salary_allowances_jabatan_tahun($employee_user_id, $tahun_thr);
                $count_tunj_jabatan  = $this->Employees_model->count_employee_allowances_jabatan_tahun($employee_user_id, $tahun_thr);
                $jumlah_tunj_jabatan = 0;
                if ($count_tunj_jabatan > 0) {
                    foreach ($salary_tunj_jabatan as $tunj_jabatan) {
                        $jumlah_tunj_jabatan += $tunj_jabatan->tnj_jabatan;
                    }
                } else {
                    $jumlah_tunj_jabatan = 0;
                }


                // ====================================================================================================================
                // HITUNG
                // ====================================================================================================================
                $total_jumlah     = $basic_salary + $jumlah_tunj_jabatan;

                if ($jum_bulan > 12) {

                    $faktor_kali = 12;
                    $masa        = "THR Penuh";
                } else {

                    if ($jum_bulan == 0) {

                        $faktor_kali = $jum_bulan;
                        $masa = "Tidak Dapat THR";
                    } else {

                        $faktor_kali = $jum_bulan;
                        $masa = "THR Prorate";
                    }
                }

                $total_net_salary = ($total_jumlah / 12) * $faktor_kali;

                // Rekening
                $rekening = $this->Employees_model->get_employee_bank_account_last($user_id);
                if (!is_null($rekening)) {
                    $rekening_name = $rekening[0]->account_number;
                } else {
                    $rekening_name = '--';
                }

                $jurl = random_string('alnum', 40);

                // ====================================================================================================================
                // SIMPAN TABEL GAJI
                // ====================================================================================================================

                $session_id = $this->session->userdata('user_id');
                $user_create = $session_id['user_id'];


                $data = array(

                    'employee_id'                  => $user_id,
                    'company_id'                   => $empid->company_id,
                    'department_id'                => $empid->department_id,
                    'doj'                            => $empid->date_of_joining,
                    'location_id'                  => $empid->location_id,
                    'designation_id'               => $empid->designation_id,
                    'wages_type'                   => $empid->wages_type,

                    'tahun_thr'                    => $tahun_thr,
                    'tanggal_thr'                  => $tanggal_thr,

                    'basic_salary'                 => $basic_salary,
                    'jumlah_tunj_jabatan'          => $jumlah_tunj_jabatan,
                    'total_jumlah'                 => $total_jumlah,

                    'note'                           => $masa,

                    'net_salary'                   => $total_net_salary,
                    'rekening_name'                => $rekening_name,

                    'is_payment'                   => '1',
                    'payslip_type'                 => 'full_monthly',
                    'payslip_key'                  => $jurl,
                    'year_to_date'                 => date('Y-m-d'),
                    'created_at'                   => date('Y-m-d h:i:s'),
                    'created_by'                   => $user_create
                );
                $result = $this->Payroll_model->add_thr_payslip_month($data);

                // echo "<pre>";
                // print_r($this->db->last_query());
                // echo "</pre>";
                // die();

                if ($result) {

                    $Return['result'] = 'THR Kolektif Tahun ' . $tahun_thr . ' Berhasil Disimpan';
                } else {

                    $Return['error'] = $this->lang->line('xin_error_msg');
                }
            }

            $this->output($Return);
            exit;
        }
    }

    public function add_pay_monthly()
    {
        if ($this->input->post('add_type') == 'add_monthly_payment') {

            /* Define return | here result is used to return user data and error for error message */

            $Return = array('result' => '', 'error' => '', 'csrf_hash' => '');
            $Return['csrf_hash'] = $this->security->get_csrf_hash();


            $user_id    = $this->input->post('emp_id');
            $tahun_thr  = $this->input->post('tahun_thr');

            $pay_count = $this->Payroll_model->read_make_payment_thr_check_bulanan_company($user_id, $tahun_thr);

            if ($pay_count->num_rows() > 0) {
                $pay_val = $this->Payroll_model->read_make_payment_thr_bulanan_company($user_id, $tahun_thr);
                $this->payslip_delete_all_bulanan($pay_val[0]->payslip_id);
            }

            $jurl = random_string('alnum', 40);


            $data = array(
                'employee_id'                  => $this->input->post('emp_id'),
                'department_id'                => $this->input->post('employee_department_id'),
                'doj'                            => $this->input->post('employee_date_of_joining'),
                'company_id'                   => $this->input->post('employee_company_id'),
                'location_id'                  => $this->input->post('employee_location_id'),
                'designation_id'               => $this->input->post('employee_designation_id'),
                'wages_type'                   => $this->input->post('employee_wages_type'),

                'tahun_thr'                    => $this->input->post('tahun_thr'),
                'tanggal_thr'                  => $this->input->post('tanggal_thr'),

                'basic_salary'                 => $this->input->post('basic_salary'),
                'jumlah_tunj_jabatan'          => $this->input->post('jumlah_tunj_jabatan'),
                'total_jumlah'                 => $this->input->post('total_jumlah'),

                'note'                           => $this->input->post('masa'),

                'net_salary'                   => $this->input->post('total_net_salary'),
                'rekening_name'                => $this->input->post('rekening_name'),

                'is_payment'                   => '1',
                'payslip_type'                 => 'full_monthly',
                'payslip_key'                  => $jurl,
                'year_to_date'                 => date('Y-m-d'),
                'created_at'                   => date('Y-m-d h:i:s')
            );
            $result = $this->Payroll_model->add_thr_payslip_month($data);

            // echo "<pre>";
            // print_r($this->db->last_query());
            // echo "</pre>";
            // die();

            if ($result) {

                $Return['result'] = 'THR Individu Tahun ' . $tahun_thr . ' Berhasil Disimpan';
            } else {
                $Return['error'] = $this->lang->line('xin_error_msg');
            }

            $this->output($Return);
            exit;
        }
    }
}
