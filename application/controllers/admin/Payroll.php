<?php

defined('BASEPATH') or exit('No direct script access allowed');

/**
 * @property Roles_model $Roles_model
 * @property Payroll_model $Payroll_model
 * @property Overtime_model $Overtime_model
 * @property Employees_model $Employees_model
 * @property Timesheet_model $Timesheet_model
 * @property Department_model $Department_model
 * @property Designation_model $Designation_model
 * @property Workstation_model $Workstation_model
 */
class Payroll extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();

        $this->load->library(array('PHPExcel', 'PHPExcel/IOFactory'));
        $this->load->library('Tools');
        $this->load->library('Pdf');

        //load the model
        $this->load->model("Payroll_model");
        $this->load->model("Core_model");
        $this->load->model("Employees_model");
        $this->load->model("Designation_model");
        $this->load->model("Department_model");
        $this->load->model("Location_model");
        $this->load->model("Timesheet_model");
        $this->load->model("Overtime_model");
        $this->load->model("Company_model");
        $this->load->model("Finance_model");
        $this->load->model("Workstation_model");
        $this->load->model("Roles_model");

        $this->load->helper('string');
    }

    /*Function to set JSON output*/
    public function output($Return = array())
    {
        /*Set response header*/
        header("Access-Control-Allow-Origin: *");
        header("Content-Type: application/json; charset=UTF-8");
        /*Final JSON response*/
        exit(json_encode($Return));
    }


    // ===============================================================================================
    // 01. PROSES => GAJI BULANAN
    // ===============================================================================================

    // =======================================================================================
    // TABEL : GAJI BULANAN
    // =======================================================================================

    // generate payslips
    public function bulanan()
    {
        $session = $this->session->userdata('username');
        if (empty($session)) {
            redirect('admin/');
        }
        $data['title']       = 'Proses Gaji Bulanan | ' . $this->Core_model->site_title();
        $data['icon']        = '<i class="fa fa-money"></i>';
        $data['desc']        = 'PROSES : Gaji Bulanan ';
        $data['breadcrumbs'] = 'Proses Gaji Bulanan ';

        $data['all_companies']     = $this->Company_model->get_company();
        $data['all_bulan_gaji']    = $this->Core_model->all_bulan_status_payroll();


        $data['path_url'] = 'bulanan';
        $role_resources_ids = $this->Core_model->user_role_resource();
        if (in_array('1011', $role_resources_ids)) {
            if (!empty($session)) {
                $data['subview'] = $this->load->view("admin/payroll/bulanan", $data, TRUE);
                $this->load->view('admin/layout/layout_main', $data); //page load
            } else {
                redirect('admin/');
            }
        } else {
            redirect('admin/dashboard');
        }
    }

    public function payslip_list_bulanan()
    {

        $data['title'] = $this->Core_model->site_title();
        $session = $this->session->userdata('username');

        if (!empty($session)) {

            $this->load->view("admin/payroll/bulanan", $data);
        } else {

            redirect('admin/');
        }
        // Datatables Variables
        $draw   = intval($this->input->get("draw"));
        $start  = intval($this->input->get("start"));
        $length = intval($this->input->get("length"));

        // date and employee id/company id
        $p_date             = $this->input->get("month_year");
        $role_resources_ids = $this->Core_model->user_role_resource();

        // $user_info  = $this->Core_model->read_user_info($session['user_id']);
        // $system     = $this->Core_model->read_setting_info(1);

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
            $employee = $this->Core_model->read_user_info_data($r->user_id);
            if (!is_null($employee)) {

                $employee_user_id           = $employee[0]->user_id;
                $employee_grade_type        = $employee[0]->grade_type;
                $employee_wages_type        = $employee[0]->wages_type;
                $employee_payment_type      = $employee[0]->payment_type;
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
                $employee_payment_type      = '';
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

            $xin_bulan   = $this->Timesheet_model->get_xin_employees_bulan_gaji($this->input->get('month_year'));
            if (!is_null($xin_bulan)) {
                $bulan_gaji  = $xin_bulan[0]->desc;
            } else {
                $bulan_gaji  = '';
            }

            // jenis gaji
            $payment_type = $this->Core_model->read_user_pembayaran_gaji($employee_payment_type);
            // user full name
            if (!is_null($payment_type)) {
                $jenis_payment       = $payment_type[0]->jenis_gaji_keterangan;
                $jenis_payment_warna = $payment_type[0]->warna;
            } else {
                $jenis_payment = '<span class="badge bg-red"> ? </span>';
                $jenis_payment_warna = '';
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
            $tanggal2 = new DateTime();

            if ($tanggal2->diff($tanggal1)->y == 0) {
                $selisih = $tanggal2->diff($tanggal1)->m . ' bln';
            } else {
                $selisih = $tanggal2->diff($tanggal1)->y . ' thn' . ' ' . $tanggal2->diff($tanggal1)->m . ' bln';
            }

            // ====================================================================================================================
            // BULAN GAJI
            // ====================================================================================================================

            // Bulan
            $pay_date = $this->input->get('month_year');

            // Tanggal Gaji
            $tanggal       = $this->Timesheet_model->read_tanggal_information($pay_date);
            if (!is_null($tanggal)) {
                $start_date    = $tanggal[0]->start_date;
                $end_date      = $tanggal[0]->end_date;
            } else {
                $start_date    = '';
                $end_date      = '';
            }

            $p_class      = 'emo_monthly_pay';
            $p_del        = 'del_monthly_pay';
            $view_p_class = 'payroll_template_modal';

            // ====================================================================================================================
            // KOMPONEN GAJI - TAMBAH
            // ====================================================================================================================
            // ****************************************************************************************************************
            // TETAP
            // ****************************************************************************************************************
            // ============================================================================================================
            // 1: salary type
            // ============================================================================================================
            // $wages_type = $this->lang->line('xin_payroll_full_tTime');
            $basic_salary = $employee_basic_salary;

            // ============================================================================================================
            // 2: Tunjangan
            // ============================================================================================================

            // 1 - Tunj. Jabatan
            $salary_tunj_jabatan = $this->Employees_model->read_salary_allowances_jabatan($employee_user_id, $start_date);
            $count_tunj_jabatan  = $this->Employees_model->count_employee_allowances_jabatan($employee_user_id, $start_date);
            $jumlah_tunj_jabatan = 0;
            if ($count_tunj_jabatan > 0) {
                foreach ($salary_tunj_jabatan as $tunj_jabatan) {
                    $jumlah_tunj_jabatan += $tunj_jabatan->tnj_jabatan;
                }
            } else {
                $jumlah_tunj_jabatan = 0;
            }

            // 2 - Tunj. Produktifitas
            $salary_tunj_produktifitas = $this->Employees_model->read_salary_allowances_produktifitas($employee_user_id, $start_date);
            $count_tunj_produktifitas  = $this->Employees_model->count_employee_allowances_produktifitas($employee_user_id, $start_date);
            $jumlah_tunj_produktifitas = 0;
            if ($count_tunj_produktifitas > 0) {
                foreach ($salary_tunj_produktifitas as $tunj_produktifitas) {
                    $jumlah_tunj_produktifitas += $tunj_produktifitas->tnj_produktifitas;
                }
            } else {
                $jumlah_tunj_produktifitas = 0;
            }

            // 3 - Tunj. Transportasi
            $salary_tunj_transportasi = $this->Employees_model->read_salary_allowances_transportasi($employee_user_id, $start_date);
            $count_tunj_transportasi  = $this->Employees_model->count_employee_allowances_transportasi($employee_user_id, $start_date);
            $jumlah_tunj_transportasi = 0;
            if ($count_tunj_transportasi > 0) {
                foreach ($salary_tunj_transportasi as $tunj_transportasi) {
                    $jumlah_tunj_transportasi += $tunj_transportasi->tnj_transportasi;
                }
            } else {
                $jumlah_tunj_transportasi = 0;
            }

            // 4 - Tunj. Komunikasi
            $salary_tunj_komunikasi = $this->Employees_model->read_salary_allowances_komunikasi($employee_user_id, $start_date);
            $count_tunj_komunikasi  = $this->Employees_model->count_employee_allowances_komunikasi($employee_user_id, $start_date);
            $jumlah_tunj_komunikasi = 0;
            if ($count_tunj_komunikasi > 0) {
                foreach ($salary_tunj_komunikasi as $tunj_komunikasi) {
                    $jumlah_tunj_komunikasi += $tunj_komunikasi->tnj_komunikasi;
                }
            } else {
                $jumlah_tunj_komunikasi = 0;
            }

            // ****************************************************************************************************************
            // TIDAK TETAP
            // ****************************************************************************************************************
            // ============================================================================================================
            // 1: Insentif
            // ============================================================================================================

            $commissions       = $this->Employees_model->read_payroll_salary_commissions($employee_user_id, $start_date, $end_date);
            $count_commissions = $this->Employees_model->count_employee_commissions($employee_user_id, $start_date, $end_date);
            $commissions_amount = 0;
            if ($count_commissions > 0) {
                foreach ($commissions as $sl_salary_commissions) {
                    $commissions_amount += $sl_salary_commissions->commission_amount;
                }
            } else {
                $commissions_amount = 0;
            }

            // ============================================================================================================
            // 2: Lembur
            // ============================================================================================================

            $salary_overtime = $this->Employees_model->read_payroll_salary_overtime($employee_user_id, $start_date, $end_date);
            $count_overtime = $this->Employees_model->count_payroll_employee_overtime($employee_user_id, $start_date, $end_date);
            $overtime_amount = 0;
            if ($count_overtime > 0) {
                foreach ($salary_overtime as $sl_overtime) {
                    $overtime_amount += $sl_overtime->overtime_total;
                }
            } else {
                $overtime_amount = 0;
            }

            // ====================================================================================================================
            // KOMPONEN GAJI - KURANG
            // ====================================================================================================================

            // ****************************************************************************************************************
            // TETAP
            // ****************************************************************************************************************
            // ============================================================================================================
            // 1: BPJS TK
            // ============================================================================================================

            $count_bpjs_tk = $this->Employees_model->count_employee_bpjs_tk($employee_user_id,$start_date, $end_date);
            $bpjs_tk = $this->Employees_model->set_employee_bpjs_tk($employee_user_id,$start_date, $end_date);
            $bpjs_tk_amount = 0;
            if ($count_bpjs_tk > 0) {
                foreach ($bpjs_tk->result() as $sl_salary_bpjs_tk) {
                    $bpjs_tk_amount += $sl_salary_bpjs_tk->deduction_amount;
                }
            } else {
                $bpjs_tk_amount = 0;
            }

            // ============================================================================================================
            // 2: BPJS KES
            // ============================================================================================================

            $count_bpjs_kes = $this->Employees_model->count_employee_bpjs_kes($employee_user_id,$start_date, $end_date);
            $bpjs_kes = $this->Employees_model->set_employee_bpjs_kes($employee_user_id,$start_date, $end_date);
            $bpjs_kes_amount = 0;
            if ($count_bpjs_kes > 0) {
                foreach ($bpjs_kes->result() as $sl_salary_bpjs_kes) {
                    $bpjs_kes_amount += $sl_salary_bpjs_kes->deduction_amount;
                }
            } else {
                $bpjs_kes_amount = 0;
            }

            // ============================================================================================================
            // 3: PPH
            // ============================================================================================================

            $count_other_payments  = $this->Employees_model->count_employee_other_payments($employee_user_id);
            $other_payments        = $this->Employees_model->set_employee_other_payments($employee_user_id);
            $other_payments_amount = 0;
            if ($count_other_payments > 0) {
                foreach ($other_payments->result() as $sl_other_payments) {
                    $other_payments_amount += $sl_other_payments->payments_amount;
                }
            } else {
                $other_payments_amount = 0;
            }

            // ****************************************************************************************************************
            // TIDAK TETAP
            // ****************************************************************************************************************


            // ============================================================================================================
            // 1: Minus
            // ============================================================================================================
            $minus         = $this->Employees_model->read_payroll_salary_minus($employee_user_id, $start_date, $end_date);
            $count_minus   = $this->Employees_model->count_employee_minus($employee_user_id, $start_date, $end_date);
            $potongan_lain = 0;
            if ($count_minus > 0) {
                foreach ($minus as $sl_salary_minus) {
                    $potongan_lain += $sl_salary_minus->minus_amount;
                }
            } else {
                $potongan_lain = 0;
            }

            // ============================================================================================================
            // 1: Pinjaman
            // ============================================================================================================

            $salary_loan_deduction = $this->Employees_model->read_salary_loan_deductions($employee_user_id, $start_date, $end_date);
            // echo "<pre>";
            // print_r($this->db->last_query());
            // echo "</pre>";
            // die();

            $count_loan_deduction  = $this->Employees_model->count_employee_deductions($employee_user_id, $start_date, $end_date);
            $loan_de_amount = 0;
            if ($count_loan_deduction > 0) {
                foreach ($salary_loan_deduction as $sl_salary_loan_deduction) {
                    $loan_de_amount +=  $sl_salary_loan_deduction->loan_deduction_amount;
                }
            } else {
                $loan_de_amount = 0;
            }

            // ============================================================================================================
            // 2: Absen
            // ============================================================================================================

            if ($employee_flag == '1') {

                $jumlah_alpa   = 0;
                $potongan_alpa = 0;

                $jumlah_izin   = 0;
                $potongan_izin = 0;

                $jumlah_libur   = 0;
                $potongan_libur = 0;


                $potongan_absen = $potongan_alpa + $potongan_izin + $potongan_libur;

                $status_flag = '<i class="fa fa-bell merah" title="Faktor absensi diabaikan"></i>';
            } else if ($employee_flag == '0') {

                $status_flag = '<i class="fa fa-bell hijau" title="Faktor absensi tidak diabaikan"></i>';

                $cek_hadir     = $this->Timesheet_model->hitung_jumlah_status_kehadiran($employee_user_id, $start_date, $end_date, 'H');
                $jumlah_hadir  = $cek_hadir ? $cek_hadir[0]->jumlah : 0;

                $jumlah_upah  = $basic_salary + $jumlah_tunj_jabatan;

                // ==========================================================================================================
                // CEK BM
                // ==========================================================================================================


                $cek_bm      = $this->Timesheet_model->hitung_jumlah_status_kehadiran($employee_user_id, $start_date, $end_date, 'BM');
                $jumlah_bm   = $cek_bm ? $cek_bm[0]->jumlah : 0;

                $hitung_bm   = ($jumlah_upah / 26) * $jumlah_bm * 1;
                $potongan_bm = round($hitung_bm, 2);

                // ==========================================================================================================
                // CEK ALPA
                // ==========================================================================================================

                $cek_alpa      = $this->Timesheet_model->hitung_jumlah_status_kehadiran($employee_user_id, $start_date, $end_date, 'A');
                $jumlah_alpa   = $cek_alpa ? $cek_alpa[0]->jumlah : 0;

                $hitung_alpa   = ($jumlah_upah / 26) * $jumlah_alpa * 1;
                $potongan_alpa = round($hitung_alpa, 2);

                // ==========================================================================================================
                // CEK IZIN
                // ==========================================================================================================

                $cek_izin     = $this->Timesheet_model->hitung_jumlah_status_kehadiran($employee_user_id, $start_date, $end_date, 'I');
                $jumlah_izin  = $cek_izin ? $cek_izin[0]->jumlah : 0;

                $hitung_izin   = ($jumlah_upah / 26) * $jumlah_izin * 0.5;
                $potongan_izin = round($hitung_izin, 2);

                // ==========================================================================================================
                // CEK LIBUR
                // ==========================================================================================================

                $cek_libur     = $this->Timesheet_model->hitung_jumlah_status_kehadiran($employee_user_id, $start_date, $end_date, 'LK');
                $jumlah_libur  = $cek_libur ? $cek_libur[0]->jumlah : 0;

                $hitung_libur   = ($jumlah_upah / 26) * $jumlah_libur * 1;
                $potongan_libur = round($hitung_libur, 2);

                // ==========================================================================================================
                //  Jumlah Potongan
                // ==========================================================================================================

                $potongan_absen = $potongan_bm +  $potongan_alpa + $potongan_izin + $potongan_libur;
            }

            // ====================================================================================================================
            // HITUNG
            // ====================================================================================================================

            $total_upah       = $basic_salary + $jumlah_tunj_jabatan + $jumlah_tunj_produktifitas + $jumlah_tunj_transportasi + $jumlah_tunj_komunikasi;
            $total_tambahan   = $overtime_amount + $commissions_amount;
            $total_tambah     = $total_upah + $total_tambahan;
            $total_deduction  = $bpjs_kes_amount + $bpjs_tk_amount + $other_payments_amount + $loan_de_amount + $potongan_absen + $potongan_lain;
            $total_net_salary = ($total_upah + $total_tambahan) - $total_deduction;

            // ====================================================================================================================
            // PERIKSA PEMBAYARAN
            // ====================================================================================================================

            $payment_check = $this->Payroll_model->read_make_payment_payslip_check_bulanan($employee_user_id, $p_date);
            if ($payment_check->num_rows() > 0) {
                $make_payment   = $payment_check->result();
                $rekening_name  = $make_payment[0]->rekening_name;
                $bank_name      = $make_payment[0]->bank_name;
                $view_url       = site_url() . 'admin/payroll/payslip_bulanan/id/' . $make_payment[0]->payslip_key;
                $status         = '<span class="label label-success">' . $this->lang->line('xin_payroll_paid') . '</span>';

                if (in_array('1014', $role_resources_ids)) {
                    $mpay = '<span data-toggle="tooltip" data-placement="top" title="' . $this->lang->line('xin_payroll_view_payslip') . '">
                            <a target ="_blank" href="' . $view_url . '">
                                <button type="button" class="btn icon-btn btn-xs btn-warning waves-effect waves-light">
                                    <span class="fa fa-money"></span>
                                </button>
                            </a>
                        </span>';
                } else {
                    $mpay = '';
                }

                if (in_array('1015', $role_resources_ids)) {
                    $dpay = '<span data-toggle="tooltip" data-placement="top" title="' . $this->lang->line('xin_download') . '">
                            <a href="' . site_url() . 'admin/payroll/pdf_create/p/' . $make_payment[0]->payslip_key . '">
                                <button type="button" class="btn icon-btn btn-xs btn-success waves-effect waves-light">
                                    <span class="fa fa-download"></span>
                                </button>
                            </a>
                        </span>';
                } else {
                    $dpay = '';
                }

                if (in_array('10131', $role_resources_ids)) {
                    $delete = '<span data-toggle="tooltip" data-placement="top" title="' . $this->lang->line('xin_delete') . '">
                            <button type="button" class="btn icon-btn btn-xs btn-danger waves-effect waves-light" data-toggle="modal" data-target=".' . $p_del . '" data-payslip_id="' .  $make_payment[0]->payslip_id . '" data-payment_date="' . $p_date . '" data-company_id="' . $this->input->get("company_id") . '">
                                <span class="fa fa-trash"></span>
                            </button>
                        </span>';

                    // $delete = '<span data-toggle="tooltip" data-placement="top" title="' . $this->lang->line('xin_delete') . '">
                    //                 <button type="button" class="btn icon-btn btn-xs btn-danger waves-effect waves-light delete" data-toggle="modal" data-target=".delete-modal" data-record-id="' . $make_payment[0]->payslip_id . '">
                    //                         <span class="fa fa-trash"></span>
                    //                 </button>
                    //             </span>';
                } else {
                    $delete = '';
                }

                if (in_array('1013', $role_resources_ids)) {

                    $edit_opt = '<button style ="background-color: #ccc;border-color: #eee;" disabled type="button" class="btn icon-btn btn-xs btn-success waves-effect waves-light" title="Gaji Belum Diproses">
                                                        <span class="fa fa-pencil"></span>
                                                    </button> ';
                } else {
                    $edit_opt = '';
                }

                if (in_array('1016', $role_resources_ids)) {
                    $bpay = '<button style ="background-color: #ccc;border-color: #eee;" disabled type="button" class="btn icon-btn btn-xs btn-warning " title="Gaji Belum Diproses">
                                                <span class="fa fa-save"></span>
                                            </button>';
                } else {
                    $bpay = '';
                }
            } else {

                $status = '<span class="label label-danger">' . $this->lang->line('xin_payroll_unpaid') . '</span>';

                if (in_array('1014', $role_resources_ids)) {

                    $mpay = '<button style ="background-color: #ccc;border-color: #eee;" disabled type="button" class="btn icon-btn btn-xs btn-warning " title="Gaji Belum Diproses">
                                                        <span class="fa fa-money"></span>
                                                    </button> ';
                } else {
                    $mpay = '';
                }

                if (in_array('1015', $role_resources_ids)) {
                    $dpay         = '<button style ="background-color: #ccc;border-color: #eee;" disabled type="button" class="btn icon-btn btn-xs btn-success waves-effect waves-light" title="Gaji Belum Diproses">
                                                        <span class="fa fa-download"></span>
                                                    </button> ';
                } else {
                    $dpay = '';
                }

                if (in_array('10131', $role_resources_ids)) {
                    $delete = '<button style ="background-color: #ccc;border-color: #eee;" disabled type="button" class="btn icon-btn btn-xs btn-success waves-effect waves-light" title="Gaji Belum Diproses">
                                                        <span class="fa fa-trash"></span>
                                                    </button> ';
                } else {
                    $delete = '';
                }

                if (in_array('1013', $role_resources_ids)) {
                    $edit_opt = ' <span data-toggle="tooltip" data-placement="top" title="' . $this->lang->line('xin_edit_komponen_gaji') . '">
                                                        <a target="_blank" href="' . site_url() . 'admin/payroll/bulanan_detail/' . $employee_user_id . '">
                                                            <button type="button" class="btn icon-btn btn-xs btn-info waves-effect waves-light">
                                                                <span class="fa fa-pencil"></span>
                                                            </button>
                                                        </a>
                                                    </span>';
                } else {
                    $edit_opt = '';
                }

                if (in_array('1016', $role_resources_ids)) {
                    $bpay = '<span data-toggle="tooltip" data-placement="top" title="Simpan Gaji Per Karyawan">
                                                <button type="button" class="btn icon-btn btn-xs btn-success waves-effect waves-light" data-toggle="modal" data-target=".' . $p_class . '" data-employee_id="' .  $employee_user_id . '" data-payment_date="' . $p_date . '" data-company_id="' . $this->input->get("company_id") . '">
                                                    <span class="fa fa-save"></span>
                                                </button>
                                            </span>';
                } else {
                    $bpay = '';
                }
            }

            //detail link
            $detail = '<span data-toggle="tooltip" data-placement="top" title="' . $this->lang->line('xin_view') . '">
                                            <button type="button" class="btn icon-btn btn-xs btn-warning waves-effect waves-light" data-toggle="modal" data-target=".' . $view_p_class . '" data-employee_id="' . $employee_user_id . '">
                                                <span class="fa fa-eye"></span>
                                            </button>
                                        </span>';


            $basic_salary = $basic_salary;


            if ($basic_salary == 0 || $basic_salary == '') {
                $fmpay = '';
            } else {
                $fmpay = $mpay;
            }

            //action link
            $act = $mpay . $dpay . $delete . $edit_opt . $bpay;


            $data[] = array(
                $act,
                $no,
                $status,
                $bulan_gaji,
                $jenis_payment,
                $emp_id,
                $status_flag . ' ' . strtoupper($emp_name),
                strtoupper($department_name),
                strtoupper($designation_name),
                date("d-m-Y", strtotime($employee_date_of_joining)),
                $selisih,
                $karyawan_status,
                $emp_status_name,
                $jenis_grade,

                number_format((float)$basic_salary, 0, ',', '.'),
                number_format($overtime_amount, 0, ',', '.'),
                number_format($jumlah_tunj_jabatan, 0, ',', '.'),
                number_format($jumlah_tunj_produktifitas, 0, ',', '.'),
                number_format($jumlah_tunj_transportasi, 0, ',', '.'),
                number_format($jumlah_tunj_komunikasi, 0, ',', '.'),
                number_format($commissions_amount, 0, ',', '.'),

                number_format($total_tambah, 0, ',', '.'),

                number_format($other_payments_amount, 0, ',', '.'),
                number_format($loan_de_amount, 0, ',', '.'),
                number_format($bpjs_kes_amount, 0, ',', '.'),
                number_format($bpjs_tk_amount, 0, ',', '.'),
                $jumlah_alpa,
                number_format($potongan_alpa, 0, ',', '.'),

                $jumlah_izin,
                number_format($potongan_izin, 0, ',', '.'),

                $jumlah_libur,
                number_format($potongan_libur, 0, ',', '.'),

                number_format($potongan_lain, 0, ',', '.'),

                number_format($total_deduction, 0, ',', '.'),

                number_format($total_net_salary, 0, ',', '.'),
                $rekening_name,
                $bank_name,
                $employee_email
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

    public function gaji_bulanan_jumlah()
    {
        $company_id = $this->input->get('company_id');
        $bulan_gaji = $this->input->get('month_year');


        $sql = 'SELECT *
                        FROM xin_payroll_date WHERE month_payroll = "' . $bulan_gaji . '" ';

        // echo "<pre>";
        // print_r($sql);
        // echo "</pre>";
        // die();

        $query = $this->db->query($sql);


        $response['val'] = array();
        if ($query <> false) {
            foreach ($query->result() as $val) {

                // company
                $company = $this->Company_model->read_company_information($company_id);
                if (!is_null($company)) {
                    $company_name = $company[0]->name;
                } else {
                    $company_name = '--';
                }

                $response['val'][] = array(
                    'company_name'    => $company_name,
                    'bulan_gaji'      => $val->desc,
                    'tanggal_gaji'    => date("d-m-Y", strtotime($val->start_date)) . ' s/d ' . date("d-m-Y", strtotime($val->end_date))

                );
            }
            $response['status'] = '200';
        }


        echo json_encode($response);
    }

    // =======================================================================================
    // PROSES : SIMPAN
    // =======================================================================================

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

            $company_id = $this->input->post("company_id");
            $bulan_id   = $this->input->post("bmonth_year");

            // echo "<pre>";
            // print_r($this->db->last_query());
            // print_r( $company_id );
            // print_r( $bulan_id );
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


                // Rekening
                $rekening = $this->Employees_model->get_employee_bank_account_last($user_id);
                if (!is_null($rekening)) {
                    $rekening_name = $rekening[0]->account_number;
                    $bank_name     = $rekening[0]->bank_name;
                } else {
                    $rekening_name = '--';
                    $bank_name     = '--';
                }


                // ====================================================================================================================
                // JIKA ADA -> HAPUS
                // ====================================================================================================================

                $pay_count = $this->Payroll_model->read_make_payment_payslip_check_bulanan_company($user_id, $bulan_id);

                if ($pay_count->num_rows() > 0) {

                    $pay_val = $this->Payroll_model->read_make_payment_payslip_bulanan_company($user_id, $bulan_id);

                    $this->payslip_delete_all_bulanan($pay_val[0]->payslip_id);
                }

                // ====================================================================================================================
                // BULAN GAJI
                // ====================================================================================================================

                // Bulan
                // $pay_date = $this->input->post('bmonth_year');

                // Tanggal Gaji
                $tanggal       = $this->Timesheet_model->read_tanggal_information($bulan_id);
                if (!is_null($tanggal)) {
                    $start_date    = $tanggal[0]->start_date;
                    $end_date      = $tanggal[0]->end_date;
                    $month_date    = $tanggal[0]->desc;
                } else {
                    $start_date    = '';
                    $end_date      = '';
                    $month_date    = '';
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
                // $wages_type = $this->lang->line('xin_payroll_full_tTime');
                $basic_salary = $empid->basic_salary;

                // ============================================================================================================
                // 2: Tunjangan
                // ============================================================================================================

                // 1 - Tunj. Jabatan
                $salary_tunj_jabatan = $this->Employees_model->read_salary_allowances_jabatan($user_id, $start_date);
                $count_tunj_jabatan  = $this->Employees_model->count_employee_allowances_jabatan($user_id, $start_date);
                $jumlah_tunj_jabatan = 0;
                if ($count_tunj_jabatan > 0) {
                    foreach ($salary_tunj_jabatan as $tunj_jabatan) {
                        $jumlah_tunj_jabatan += $tunj_jabatan->tnj_jabatan;
                    }
                } else {
                    $jumlah_tunj_jabatan = 0;
                }

                // 2 - Tunj. Produktifitas
                $salary_tunj_produktifitas = $this->Employees_model->read_salary_allowances_produktifitas($user_id, $start_date);
                $count_tunj_produktifitas  = $this->Employees_model->count_employee_allowances_produktifitas($user_id, $start_date);
                $jumlah_tunj_produktifitas = 0;
                if ($count_tunj_produktifitas > 0) {
                    foreach ($salary_tunj_produktifitas as $tunj_produktifitas) {
                        $jumlah_tunj_produktifitas += $tunj_produktifitas->tnj_produktifitas;
                    }
                } else {
                    $jumlah_tunj_produktifitas = 0;
                }

                // 3 - Tunj. Transportasi
                $salary_tunj_transportasi = $this->Employees_model->read_salary_allowances_transportasi($user_id, $start_date);
                $count_tunj_transportasi  = $this->Employees_model->count_employee_allowances_transportasi($user_id, $start_date);
                $jumlah_tunj_transportasi = 0;
                if ($count_tunj_transportasi > 0) {
                    foreach ($salary_tunj_transportasi as $tunj_transportasi) {
                        $jumlah_tunj_transportasi += $tunj_transportasi->tnj_transportasi;
                    }
                } else {
                    $jumlah_tunj_transportasi = 0;
                }

                // 4 - Tunj. Komunikasi
                $salary_tunj_komunikasi = $this->Employees_model->read_salary_allowances_komunikasi($user_id, $start_date);
                $count_tunj_komunikasi  = $this->Employees_model->count_employee_allowances_komunikasi($user_id, $start_date);
                $jumlah_tunj_komunikasi = 0;
                if ($count_tunj_komunikasi > 0) {
                    foreach ($salary_tunj_komunikasi as $tunj_komunikasi) {
                        $jumlah_tunj_komunikasi += $tunj_komunikasi->tnj_komunikasi;
                    }
                } else {
                    $jumlah_tunj_komunikasi = 0;
                }

                // ****************************************************************************************************************
                // >> TIDAK TETAP
                // ****************************************************************************************************************
                // ============================================================================================================
                // 1: Insentif
                // ============================================================================================================
                $commissions       = $this->Employees_model->read_payroll_salary_commissions($user_id, $start_date, $end_date);
                $count_commissions = $this->Employees_model->count_employee_commissions($user_id, $start_date, $end_date);
                $commissions_amount = 0;
                if ($count_commissions > 0) {
                    foreach ($commissions as $sl_salary_commissions) {
                        $commissions_amount += $sl_salary_commissions->commission_amount;
                    }
                } else {
                    $commissions_amount = 0;
                }

                // ============================================================================================================
                // 2: Lembur
                // ============================================================================================================

                $salary_overtime = $this->Employees_model->read_payroll_salary_overtime($user_id, $start_date, $end_date);
                $count_overtime = $this->Employees_model->count_payroll_employee_overtime($user_id, $start_date, $end_date);
                $overtime_amount = 0;
                if ($count_overtime > 0) {
                    foreach ($salary_overtime as $sl_overtime) {
                        $overtime_amount += $sl_overtime->overtime_total;
                    }
                } else {
                    $overtime_amount = 0;
                }

                // ====================================================================================================================
                // (-) KOMPONEN GAJI - KURANG
                // ====================================================================================================================

                // ****************************************************************************************************************
                // >> TETAP
                // ****************************************************************************************************************
                // ============================================================================================================
                // 1: BPJS TK
                // ============================================================================================================

                $count_bpjs_tk = $this->Employees_model->count_employee_bpjs_tk($user_id, $end_date);
                $bpjs_tk = $this->Employees_model->set_employee_bpjs_tk($user_id, $end_date);
                $bpjs_tk_amount = 0;
                if ($count_bpjs_tk > 0) {
                    foreach ($bpjs_tk->result() as $sl_salary_bpjs_tk) {
                        $bpjs_tk_amount += $sl_salary_bpjs_tk->deduction_amount;
                    }
                } else {
                    $bpjs_tk_amount = 0;
                }

                // ============================================================================================================
                // 2: BPJS KES
                // ============================================================================================================

                $count_bpjs_kes = $this->Employees_model->count_employee_bpjs_kes($user_id, $end_date);
                $bpjs_kes       = $this->Employees_model->set_employee_bpjs_kes($user_id, $end_date);
                $bpjs_kes_amount = 0;
                if ($count_bpjs_kes > 0) {
                    foreach ($bpjs_kes->result() as $sl_salary_bpjs_kes) {
                        $bpjs_kes_amount += $sl_salary_bpjs_kes->deduction_amount;
                    }
                } else {
                    $bpjs_kes_amount = 0;
                }

                // ============================================================================================================
                // 3: PPH 21
                // ============================================================================================================

                $count_other_payments  = $this->Employees_model->count_employee_other_payments($user_id);
                $other_payments        = $this->Employees_model->set_employee_other_payments($user_id);
                $other_payments_amount = 0;
                if ($count_other_payments > 0) {
                    foreach ($other_payments->result() as $sl_other_payments) {
                        $other_payments_amount += $sl_other_payments->payments_amount;
                    }
                } else {
                    $other_payments_amount = 0;
                }

                // ****************************************************************************************************************
                // >> TIDAK TETAP
                // ****************************************************************************************************************

                // ============================================================================================================
                // 1: Minus
                // ============================================================================================================

                $minus       = $this->Employees_model->read_payroll_salary_minus($user_id, $start_date, $end_date);
                $count_minus = $this->Employees_model->count_employee_minus($user_id, $start_date, $end_date);
                $potongan_lain = 0;
                if ($count_minus > 0) {
                    foreach ($minus as $sl_salary_minus) {
                        $potongan_lain += $sl_salary_minus->minus_amount;
                    }
                } else {
                    $potongan_lain = 0;
                }
                // ============================================================================================================
                // 1: Pinjaman
                // ============================================================================================================

                $salary_loan_deduction = $this->Employees_model->read_salary_loan_deductions($user_id, $start_date, $end_date);
                $count_loan_deduction  = $this->Employees_model->count_employee_deductions($user_id, $start_date, $end_date);
                $loan_de_amount = 0;
                if ($count_loan_deduction > 0) {
                    foreach ($salary_loan_deduction as $sl_salary_loan_deduction) {
                        $loan_de_amount +=  $sl_salary_loan_deduction->loan_deduction_amount;
                    }
                } else {
                    $loan_de_amount = 0;
                }

                // ============================================================================================================
                // 2: Absen
                // ============================================================================================================

                if ($empid->flag == '1') {

                    $jumlah_alpa   = 0;
                    $potongan_alpa = 0;

                    $jumlah_izin   = 0;
                    $potongan_izin = 0;

                    $jumlah_libur   = 0;
                    $potongan_libur = 0;

                    $potongan_absen = $potongan_alpa + $potongan_izin + $jumlah_libur;
                } else if ($empid->flag == '0') {

                    $cek_hadir     = $this->Timesheet_model->hitung_jumlah_status_kehadiran($user_id, $start_date, $end_date, 'H');
                    $jumlah_hadir  = $cek_hadir[0]->jumlah;

                    $jumlah_upah  = $basic_salary + $jumlah_tunj_jabatan;

                    // ==========================================================================================================
                    // CEK BM
                    // ==========================================================================================================


                    $cek_bm      = $this->Timesheet_model->hitung_jumlah_status_kehadiran($user_id, $start_date, $end_date, 'BM');
                    $jumlah_bm   = $cek_bm[0]->jumlah;

                    $hitung_bm   = ($jumlah_upah / 26) * $jumlah_bm * 1;
                    $potongan_bm = round($hitung_bm, 2);

                    // ==========================================================================================================
                    // CEK ALPA
                    // ==========================================================================================================

                    $cek_alpa      = $this->Timesheet_model->hitung_jumlah_status_kehadiran($user_id, $start_date, $end_date, 'A');
                    $jumlah_alpa   = $cek_alpa[0]->jumlah;

                    $hitung_alpa   = ($jumlah_upah / 26) * $jumlah_alpa * 1;
                    $potongan_alpa = round($hitung_alpa, 2);

                    // ==========================================================================================================
                    // CEK IZIN
                    // ==========================================================================================================

                    $cek_izin     = $this->Timesheet_model->hitung_jumlah_status_kehadiran($user_id, $start_date, $end_date, 'I');
                    $jumlah_izin  = $cek_izin[0]->jumlah;

                    $hitung_izin   = ($jumlah_upah / 26) * $jumlah_izin * 0.5;
                    $potongan_izin = round($hitung_izin, 2);

                    // ==========================================================================================================
                    // CEK LIBUR
                    // ==========================================================================================================

                    $cek_libur     = $this->Timesheet_model->hitung_jumlah_status_kehadiran($user_id, $start_date, $end_date, 'LK');
                    $jumlah_libur  = $cek_libur[0]->jumlah;

                    $hitung_libur   = ($jumlah_upah / 26) * $jumlah_libur * 1;
                    $potongan_libur = round($hitung_libur, 2);

                    // ==========================================================================================================
                    //  Potongan
                    // ==========================================================================================================

                    $potongan_absen = $potongan_bm + $potongan_alpa + $potongan_izin + $potongan_libur;
                }

                // ====================================================================================================================
                // HITUNG
                // ====================================================================================================================

                $total_upah       = $basic_salary + $jumlah_tunj_jabatan + $jumlah_tunj_produktifitas + $jumlah_tunj_transportasi + $jumlah_tunj_komunikasi;

                $total_tambahan   = $overtime_amount + $commissions_amount;

                $total_deduction  = $bpjs_kes_amount + $bpjs_tk_amount + $other_payments_amount + $loan_de_amount + $potongan_absen + $potongan_lain;

                $total_net_salary = ($total_upah + $total_tambahan) - $total_deduction;

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
                    'payment_type'                 => $empid->payment_type,
                    'flag'                         => $empid->flag,
                    'salary_month'                 => $bulan_id,
                    'basic_salary'                 => $basic_salary,
                    'jumlah_tunj_jabatan'          => $jumlah_tunj_jabatan,
                    'jumlah_tunj_produktifitas'    => $jumlah_tunj_produktifitas,
                    'jumlah_tunj_transportasi'     => $jumlah_tunj_transportasi,
                    'jumlah_tunj_komunikasi'       => $jumlah_tunj_komunikasi,
                    'total_upah'                   => $total_upah,
                    'overtime_amount'                 => $overtime_amount,
                    'commissions_amount'           => $commissions_amount,
                    'total_tambahan'               => $total_tambahan,
                    'bpjs_kes_amount'              => $bpjs_kes_amount,
                    'bpjs_tk_amount'               => $bpjs_tk_amount,
                    'other_payments_amount'        => $other_payments_amount,
                    'loan_de_amount'               => $loan_de_amount,

                    'jumlah_alpa'                  => $jumlah_alpa,
                    'potongan_alpa'                => $potongan_alpa,

                    'jumlah_izin'                  => $jumlah_izin,
                    'potongan_izin'                => $potongan_izin,

                    'jumlah_libur'                 => $jumlah_libur,
                    'potongan_libur'               => $potongan_libur,

                    'potongan_lain'                   => $potongan_lain,

                    'potongan_absen'               => $potongan_absen,

                    'total_deduction'              => $total_deduction,
                    'net_salary'                   => $total_net_salary,
                    'rekening_name'                => $rekening_name,
                    'bank_name'                    => $bank_name,

                    'is_payment'                   => '1',
                    'payslip_type'                 => 'full_monthly',
                    'payslip_key'                  => $jurl,
                    'year_to_date'                 => date('Y-m-d'),
                    'created_at'                   => date('Y-m-d h:i:s'),
                    'created_by'                   => $user_create
                );
                $result = $this->Payroll_model->add_salary_payslip_month($data);

                // echo "<pre>";
                // print_r($this->db->last_query());
                // echo "</pre>";
                // die();

                if ($result) {

                    $Return['result'] = 'Gaji Bulanan Kolektif Berhasil Disimpan';
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


            $user_id         = $this->input->post('emp_id');
            $employee_name   = $this->input->post('employee_name');
            $bulan_id        = $this->input->post('salary_month');

            $pay_count = $this->Payroll_model->read_make_payment_payslip_check_bulanan_company($user_id, $bulan_id);

            if ($pay_count->num_rows() > 0) {
                $pay_val = $this->Payroll_model->read_make_payment_payslip_bulanan_company($user_id, $bulan_id);
                $this->payslip_delete_all_bulanan($pay_val[0]->payslip_id);
            }

            $jurl = random_string('alnum', 40);

            $session_id = $this->session->userdata('user_id');
            $user_create = $session_id['user_id'];


            $data = array(
                'employee_id'                  => $this->input->post('emp_id'),
                'department_id'                => $this->input->post('employee_department_id'),
                'doj'                            => $this->input->post('employee_date_of_joining'),
                'company_id'                   => $this->input->post('employee_company_id'),
                'location_id'                  => $this->input->post('employee_location_id'),
                'designation_id'               => $this->input->post('employee_designation_id'),
                'wages_type'                   => $this->input->post('employee_wages_type'),
                'payment_type'                 => $this->input->post('employee_payment_type'),
                'flag'                         => $this->input->post('employee_flag'),
                'salary_month'                 => $this->input->post('salary_month'),
                'basic_salary'                 => $this->input->post('basic_salary'),
                'jumlah_tunj_jabatan'          => $this->input->post('jumlah_tunj_jabatan'),
                'jumlah_tunj_produktifitas'    => $this->input->post('jumlah_tunj_produktifitas'),
                'jumlah_tunj_transportasi'     => $this->input->post('jumlah_tunj_transportasi'),
                'jumlah_tunj_komunikasi'       => $this->input->post('jumlah_tunj_komunikasi'),
                'total_upah'                   => $this->input->post('total_upah'),
                'overtime_amount'                 => $this->input->post('overtime_amount'),
                'commissions_amount'           => $this->input->post('commissions_amount'),
                'total_tambahan'               => $this->input->post('total_tambahan'),
                'bpjs_kes_amount'              => $this->input->post('bpjs_kes_amount'),
                'bpjs_tk_amount'               => $this->input->post('bpjs_tk_amount'),
                'other_payments_amount'        => $this->input->post('other_payments_amount'),
                'loan_de_amount'               => $this->input->post('loan_de_amount'),

                'jumlah_alpa'                  => $this->input->post('jumlah_alpa'),
                'potongan_alpa'                => $this->input->post('potongan_alpa'),

                'jumlah_izin'                  => $this->input->post('jumlah_izin'),
                'potongan_izin'                => $this->input->post('potongan_izin'),

                'jumlah_libur'                 => $this->input->post('jumlah_libur'),
                'potongan_libur'               => $this->input->post('potongan_libur'),

                'potongan_lain'                => $this->input->post('potongan_lain'),

                'potongan_absen'               => $this->input->post('potongan_absen'),

                'total_deduction'              => $this->input->post('total_deduction'),
                'net_salary'                   => $this->input->post('total_net_salary'),
                'rekening_name'                => $this->input->post('rekening_name'),
                'bank_name'                    => $this->input->post('bank_name'),

                'is_payment'                   => '1',
                'payslip_type'                 => 'full_monthly',
                'payslip_key'                  => $jurl,
                'year_to_date'                 => date('Y-m-d'),
                'created_at'                   => date('Y-m-d h:i:s'),
                'created_by'                   => $user_create
            );
            $result = $this->Payroll_model->add_salary_payslip_month($data);

            // echo "<pre>";
            // print_r($this->db->last_query());
            // echo "</pre>";
            // die();

            if ($result) {

                $Return['result'] = ' Gaji Bulanan ' . $employee_name . ' ' . "\n" . ' Berhasil Disimpan';
            } else {
                $Return['error'] = $this->lang->line('xin_error_msg');
            }

            $this->output($Return);
            exit;
        }
    }

    // =======================================================================================
    // PROSES : TAMPIL
    // =======================================================================================

    // Tampil : Form Edit
    public function pay_salary()
    {
        $session = $this->session->userdata('username');
        if (empty($session)) {
            redirect('admin/');
        }
        $data['title'] = $this->Core_model->site_title();
        $id = $this->input->get('employee_id');
        // get addd by > template
        $user = $this->Core_model->read_user_info($id);

        // $result = $this->Payroll_model->read_template_information($user[0]->monthly_grade_id);
        //$department = $this->Department_model->read_department_information($user[0]->department_id);
        // get designation
        $designation = $this->Designation_model->read_designation_information($user[0]->designation_id);
        if (!is_null($designation)) {
            $designation_name = $designation[0]->designation_name;
        } else {
            $designation_name = '';
        }
        // department
        $department = $this->Department_model->read_department_information($user[0]->department_id);
        if (!is_null($department)) {
            $department_name = $department[0]->department_name;
        } else {
            $department_name = '';
        }
        //$location = $this->Location_model->read_location_information($department[0]->location_id);
        $data = array(
            'department_name'  => $department_name,
            'designation_name' => $designation_name,
            'company_id'       => $user[0]->company_id,
            'location_id'      => $user[0]->location_id,
            'user_id'          => $user[0]->user_id,
            'wages_type'       => $user[0]->wages_type,
            'payment_type'     => $user[0]->payment_type,
            'flag'             => $user[0]->flag,
            'basic_salary'     => $user[0]->basic_salary
        );
        if (!empty($session)) {
            $this->load->view('admin/payroll/dialog_make_payment_bulanan', $data);
        } else {
            redirect('admin/');
        }
    }

    // Tampil : Form Hapus
    public function pay_salary_del()
    {
        $session = $this->session->userdata('username');
        if (empty($session)) {
            redirect('admin/');
        }
        $data['title'] = $this->Core_model->site_title();
        $payslip_id = $this->input->get('payslip_id');
        // get addd by > template

        $payslip = $this->Core_model->read_slip_info($payslip_id);

        $user = $this->Core_model->read_user_info($payslip[0]->employee_id);

        $designation = $this->Designation_model->read_designation_information($payslip[0]->designation_id);
        if (!is_null($designation)) {
            $designation_name = $designation[0]->designation_name;
        } else {
            $designation_name = '';
        }
        // department
        $department = $this->Department_model->read_department_information($payslip[0]->department_id);
        if (!is_null($department)) {
            $department_name = $department[0]->department_name;
        } else {
            $department_name = '';
        }

        //$location = $this->Location_model->read_location_information($department[0]->location_id);
        $data = array(
            'payslip_id'                     => $payslip_id,
            'department_name'                => $department_name,
            'designation_name'               => $designation_name,
            'employee_name'                  => $user[0]->first_name . ' ' . $user[0]->last_name,
            'company_id'                     => $payslip[0]->company_id,

            'basic_salary'                => $payslip[0]->basic_salary,
            'jumlah_tunj_jabatan'         => $payslip[0]->jumlah_tunj_jabatan,
            'jumlah_tunj_produktifitas'   => $payslip[0]->jumlah_tunj_produktifitas,
            'jumlah_tunj_transportasi'    => $payslip[0]->jumlah_tunj_transportasi,
            'jumlah_tunj_komunikasi'      => $payslip[0]->jumlah_tunj_komunikasi,
            'commissions_amount'            => $payslip[0]->commissions_amount,
            'overtime_amount'                => $payslip[0]->overtime_amount,
            'total_tambah'                => $payslip[0]->total_upah + $payslip[0]->total_tambahan,

            'bpjs_kes_amount'             => $payslip[0]->bpjs_kes_amount,
            'bpjs_tk_amount'              => $payslip[0]->bpjs_tk_amount,
            'loan_de_amount'              => $payslip[0]->loan_de_amount,
            'other_payments_amount'       => $payslip[0]->other_payments_amount,

            'potongan_alpa'              => $payslip[0]->potongan_alpa,
            'potongan_izin'              => $payslip[0]->potongan_izin,
            'potongan_libur'             => $payslip[0]->potongan_libur,

            'potongan_lain'             => $payslip[0]->potongan_lain,

            'potongan_absen'              => $payslip[0]->potongan_absen,
            'total_deduction'                => $payslip[0]->total_deduction,

            'net_salary'                    => $payslip[0]->net_salary

        );
        if (!empty($session)) {
            $this->load->view('admin/payroll/dialog_make_payment_bulanan_delete', $data);
        } else {
            redirect('admin/');
        }
    }

    // =======================================================================================
    // PROSES : HAPUS
    // =======================================================================================

    public function del_pay_monthly()
    {
        if ($this->input->post('add_type') == 'del_monthly_payment') {
            /* Define return | here result is used to return user data and error for error message */
            $Return = array('result' => '', 'error' => '', 'csrf_hash' => '');
            $Return['csrf_hash'] = $this->security->get_csrf_hash();


            $id  = $this->input->post('payslip_id');


            $result              = $this->Payroll_model->delete_record($id);

            if (isset($id)) {

                $Return['result'] = 'Gaji Bulanan Berhasil Dihapus';
            } else {
                $Return['error'] = $this->lang->line('xin_error_msg');
            }

            $this->output($Return);
            exit;
        }
    }

    public function payslip_delete_bulanan()
    {
        $session = $this->session->userdata('username');
        if (empty($session)) {
            redirect('admin/');
        }
        /* Define return | here result is used to return user data and error for error message */
        $Return              = array('result' => '', 'error' => '', 'csrf_hash' => '');
        $id                  = $this->uri->segment(4);
        $Return['csrf_hash'] = $this->security->get_csrf_hash();
        $result              = $this->Payroll_model->delete_record($id);

        // echo "<pre>";
        // print_r($this->db->last_query());
        // echo "</pre>";
        // die();

        if (isset($id)) {
            $Return['result'] = $this->lang->line('xin_hr_payslip_bulanan_deleted');
        } else {
            $Return['error'] = $this->lang->line('xin_error_msg');
        }
        $this->output($Return);
    }

    public function payslip_delete_all_bulanan($id)
    {
        $session = $this->session->userdata('username');
        if (empty($session)) {
            redirect('admin/');
        }
        /* Define return | here result is used to return user data and error for error message */
        $Return = array('result' => '', 'error' => '', 'csrf_hash' => '');
        $id = $id;
        $Return['csrf_hash'] = $this->security->get_csrf_hash();
        $this->Payroll_model->delete_record_bulanan($id);
    }

    // =======================================================================================
    // TAMPIL => SLIP GAJI
    // =======================================================================================

    public function payslip_bulanan()
    {
        $session = $this->session->userdata('username');
        if (empty($session)) {
            redirect('admin/');
        }
        //$data['title'] = $this->Core_model->site_title();
        $key = $this->uri->segment(5);

        $result = $this->Payroll_model->read_salary_payslip_info_key($key);
        if (is_null($result)) {
            redirect('admin/payroll/bulanan');
        }
        $p_method = '';

        // get addd by > template
        $user = $this->Core_model->read_user_info($result[0]->employee_id);
        // user full name
        if (!is_null($user)) {
            $first_name = $user[0]->first_name;
            $last_name = $user[0]->last_name;
        } else {
            $first_name = '--';
            $last_name = '--';
        }
        // get designation
        $designation = $this->Designation_model->read_designation_information($user[0]->designation_id);
        if (!is_null($designation)) {
            $designation_name = $designation[0]->designation_name;
        } else {
            $designation_name = '--';
        }

        // department
        $department = $this->Department_model->read_department_information($user[0]->department_id);
        if (!is_null($department)) {
            $department_name = $department[0]->department_name;
        } else {
            $department_name = '--';
        }

        // company
        $company = $this->Company_model->read_company_information($user[0]->company_id);
        if (!is_null($company)) {
            $company_name = $company[0]->name;
        } else {
            $company_name = '--';
        }
        //$department_designation = $designation[0]->designation_name.'('.$department[0]->department_name.')';
        $data['all_employees'] = $this->Core_model->all_employees();


        $data = array(
            'title'                      => 'Slip Gaji Karyawan Bulanan | ' . $this->Core_model->site_title(),
            'icon'                       => '<i class="fa fa-money"></i>',
            'breadcrumbs'                 => 'Slip Gaji Karyawan : Bulanan',
            'desc'                          => 'INFORMASI : Slip Gaji Karyawan - Bulanan',
            'path_url'                     => 'payslip',

            'first_name'                 => $first_name,
            'last_name'                  => $last_name,
            'employee_id'                => $user[0]->employee_id,
            'euser_id'                   => $user[0]->user_id,
            'contact_no'                 => $user[0]->contact_no,
            'email'                      => $user[0]->email,
            'date_of_joining'            => $user[0]->date_of_joining,
            'company_name'               => $company_name,
            'department_name'            => $department_name,
            'designation_name'           => $designation_name,
            'date_of_joining'            => $user[0]->date_of_joining,
            'profile_picture'            => $user[0]->profile_picture,
            'gender'                     => $user[0]->gender,
            'make_payment_id'            => $result[0]->payslip_id,
            'wages_type'                 => $result[0]->wages_type,
            'payment_date'               => $result[0]->salary_month,
            'year_to_date'               => $result[0]->year_to_date,
            'basic_salary'               => $result[0]->basic_salary,
            'daily_wages'                => $result[0]->daily_wages,
            'payment_method'             => $p_method,

            'total_allowances'           => $result[0]->jumlah_tunj_jabatan + $result[0]->jumlah_tunj_produktifitas + $result[0]->jumlah_tunj_transportasi + $result[0]->jumlah_tunj_komunikasi,
            'total_commissions'          => $result[0]->commissions_amount,

            'total_loan'                 => $result[0]->loan_de_amount,
            'total_overtime'             => $result[0]->overtime_amount,
            'total_statutory_deductions' => $result[0]->bpjs_kes_amount + $result[0]->bpjs_tk_amount,
            'total_other_payments'       => $result[0]->other_payments_amount,
            'total_attedance'            => $result[0]->potongan_absen,
            'total_potongan_lain'        => $result[0]->potongan_lain,
            'net_salary'                 => $result[0]->net_salary,

            // 'other_payment' => $result[0]->other_payment,
            'payslip_key'                => $result[0]->payslip_key,
            'payslip_type'               => $result[0]->payslip_type,
            'hours_worked'               => $result[0]->hours_worked,
            'pay_comments'               => $result[0]->pay_comments,
            'is_payment'                 => $result[0]->is_payment,
            'approval_status'            => $result[0]->status,
        );


        $role_resources_ids = $this->Core_model->user_role_resource();
        if (!empty($session)) {
            if ($result[0]->payslip_type == 'hourly') {
                $data['subview'] = $this->load->view("admin/payroll/hourly_payslip", $data, TRUE);
            } else {
                $data['subview'] = $this->load->view("admin/payroll/payslip_bulanan", $data, TRUE);
            }
            $this->load->view('admin/layout/layout_main', $data); //page load
        } else {
            redirect('admin/');
        }
    }

    public function pdf_create()
    {
        //$this->load->library('Pdf');
        $system = $this->Core_model->read_setting_info(1);
        // create new PDF document
        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

        $key = $this->uri->segment(5);
        $payment = $this->Payroll_model->read_salary_payslip_info_key($key);
        if (is_null($payment)) {
            redirect('admin/payroll/payslip_bulanan');
        }
        $user = $this->Core_model->read_user_info($payment[0]->employee_id);

        // if password generate option enable
        if ($system[0]->is_payslip_password_generate == 1) {
            /**
             * Protect PDF from being printed, copied or modified. In order to being viewed, the user needs
             * to provide password as selected format in settings module.
             */
            if ($system[0]->payslip_password_format == 'dateofbirth') {
                $password_val = date("dmY", strtotime($user[0]->date_of_birth));
            } else if ($system[0]->payslip_password_format == 'contact_no') {
                $password_val = $user[0]->contact_no;
            } else if ($system[0]->payslip_password_format == 'full_name') {
                $password_val = $user[0]->first_name . $user[0]->last_name;
            } else if ($system[0]->payslip_password_format == 'email') {
                $password_val = $user[0]->email;
            } else if ($system[0]->payslip_password_format == 'password') {
                $password_val = $user[0]->password;
            } else if ($system[0]->payslip_password_format == 'user_password') {
                $password_val = $user[0]->username . $user[0]->password;
            } else if ($system[0]->payslip_password_format == 'employee_id') {
                $password_val = $user[0]->employee_id;
            } else if ($system[0]->payslip_password_format == 'employee_id_password') {
                $password_val = $user[0]->employee_id . $user[0]->password;
            } else if ($system[0]->payslip_password_format == 'dateofbirth_name') {
                $dob = date("dmY", strtotime($user[0]->date_of_birth));
                $fname = $user[0]->first_name;
                $lname = $user[0]->last_name;
                $password_val = $dob . $fname[0] . $lname[0];
            }
            $pdf->SetProtection(array('print', 'copy', 'modify'), $password_val, $password_val, 0, null);
        }


        $_des_name = $this->Designation_model->read_designation_information($user[0]->designation_id);
        if (!is_null($_des_name)) {
            $_designation_name = $_des_name[0]->designation_name;
        } else {
            $_designation_name = '';
        }
        $department = $this->Department_model->read_department_information($user[0]->department_id);
        if (!is_null($department)) {
            $_department_name = $department[0]->department_name;
        } else {
            $_department_name = '';
        }
        //$location = $this->Core_model->read_location_info($department[0]->location_id);
        // company info
        $company = $this->Core_model->read_company_info($user[0]->company_id);


        $p_method = '';
        if (!is_null($company)) {
            $company_logo = $company[0]->logo;
            $company_name = $company[0]->name;
            $address_1 = $company[0]->address_1;
            $address_2 = $company[0]->address_2;
            $city = $company[0]->city;
            $state = $company[0]->state;
            $zipcode = $company[0]->zipcode;
            $country = $this->Core_model->read_country_info($company[0]->country);
            if (!is_null($country)) {
                $country_name = $country[0]->country_name;
            } else {
                $country_name = '--';
            }
            $c_info_email = $company[0]->email;
            $c_info_phone = $company[0]->contact_number;
        } else {
            $company_logo = $company[0]->logo;
            $company_name = '--';
            $address_1 = '--';
            $address_2 = '--';
            $city = '--';
            $state = '--';
            $zipcode = '--';
            $country_name = '--';
            $c_info_email = '--';
            $c_info_phone = '--';
        }
        $pdf = new Pdf('P', 'mm', 'A4', true, 'UTF-8', false);

        // set default header data
        $c_info_address = $address_1 . ', ' . $address_2;

        $c_info_city    = $city . ', Kode Pos : ' . $zipcode . ' Jawa Timur - Indonesia';

        $email_phone_address = "$c_info_address \n" . "$c_info_city \n" . $this->lang->line('xin_phone') . " : $c_info_phone | " . $this->lang->line('dashboard_email') . " : $c_info_email \n";

        $header_string = $email_phone_address;

        // set document information
        $pdf->SetCreator('hris');
        $pdf->SetAuthor('Nizar Basyrewan');
        $pdf->SetTitle($company_name . ' - ' . $this->lang->line('xin_print_payslip'));
        $pdf->SetSubject($this->lang->line('xin_payslip'));
        $pdf->SetKeywords($this->lang->line('xin_payslip'));

        $pdf->SetHeaderData('../../../../../uploads/logo/payroll/' . $company_logo, 40, $company_name, $header_string);

        $pdf->setFooterData(array(0, 64, 0), array(0, 64, 128));

        // set header and footer fonts
        $pdf->setHeaderFont(array('arial', '', 12));
        $pdf->setFooterFont(array('helvetica', '', 9));

        // set default monospaced font
        $pdf->SetDefaultMonospacedFont('courier');

        // set margins
        $pdf->SetMargins(15, 27, 15);
        $pdf->SetHeaderMargin(5);
        $pdf->SetFooterMargin(10);

        // set auto page breaks
        $pdf->SetAutoPageBreak(TRUE, 25);

        // set image scale factor
        $pdf->setImageScale(1.25);

        // set font
        $pdf->SetFont('helvetica', 'B', 10);

        // set header and footer fonts
        $pdf->setHeaderFont(array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
        $pdf->setFooterFont(array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

        // set default monospaced font
        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

        // set image scale factor
        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

        // ---------------------------------------------------------

        // set default font subsetting mode
        $pdf->setFontSubsetting(true);

        // Set font
        // dejavusans is a UTF-8 Unicode font, if you only need to
        // print standard ASCII chars, you can use core fonts like
        // helvetica or times to reduce file size.
        $pdf->SetFont('dejavusans', '', 10, '', true);

        // Add a page
        // This method has several options, check the source code documentation for more information.
        $pdf->AddPage();
        // -----------------------------------------------------------------------------
        $fname           = $user[0]->first_name . ' ' . $user[0]->last_name;

        $created_at      = $this->Core_model->set_date_format($payment[0]->created_at);
        $date_of_joining = $this->Core_model->set_date_format($user[0]->date_of_joining);
        $salary_month    = $this->Core_model->set_date_format($payment[0]->salary_month);

        $tanggal       = $this->Timesheet_model->read_tanggal_information($payment[0]->salary_month);
        if (!is_null($tanggal)) {
            $start_att = date("d-m-Y", strtotime($tanggal[0]->start_date));
            $end_att   = date("d-m-Y", strtotime($tanggal[0]->end_date));
        } else {
            $start_att = '';
            $end_att = '';
        }

        // check
        $half_title = '';

        // ===========================================================
        // Penambah
        //============================================================

        // basic salary
        $bs = 0;
        $bs = $payment[0]->basic_salary;

        // Tunjangan
        $count_allowances = $this->Employees_model->count_employee_allowances_payslip($payment[0]->payslip_id);
        $allowances       = $this->Employees_model->set_employee_allowances_payslip($payment[0]->payslip_id);

        $allowances_amount = 0;
        foreach ($allowances->result() as $sl_allowances) {
            $allowances_amount += $sl_allowances->jumlah_tunj_jabatan + $sl_allowances->jumlah_tunj_produktifitas + $sl_allowances->jumlah_tunj_transportasi + $sl_allowances->jumlah_tunj_komunikasi;
        }

        // Insentif
        $count_commissions = $this->Employees_model->count_employee_commissions_payslip($payment[0]->payslip_id);
        $commissions = $this->Employees_model->set_employee_commissions_payslip($payment[0]->payslip_id);

        $commissions_amount = 0;
        foreach ($commissions->result() as $sl_commissions) {
            $commissions_amount += $sl_commissions->commissions_amount;
        }

        // Lembur
        $count_overtime = $this->Employees_model->count_employee_overtime_payslip($payment[0]->payslip_id);
        $overtime = $this->Employees_model->set_employee_overtime_payslip($payment[0]->payslip_id);

        $overtime_amount = 0;
        foreach ($overtime->result() as $sl_overtime) {
            $overtime_amount += $sl_overtime->overtime_amount;
        }

        // ============================================================================================================================
        // Pengurangan
        // ============================================================================================================================

        // ========================================================================================================================
        // BPJS
        // ========================================================================================================================
        $count_statutory_deductions = $this->Employees_model->count_employee_statutory_deductions_payslip($payment[0]->payslip_id);
        $statutory_deductions = $this->Employees_model->set_employee_statutory_deductions_payslip($payment[0]->payslip_id);

        $bpjs_amount = 0;
        foreach ($statutory_deductions->result() as $sl_statutory_deductions) {
            $bpjs_amount += $sl_statutory_deductions->bpjs_kes_amount + $sl_statutory_deductions->bpjs_tk_amount;
        }

        // ========================================================================================================================
        // Pajak
        // ========================================================================================================================
        $count_other_payments = $this->Employees_model->count_employee_other_payments_payslip($payment[0]->payslip_id);
        $other_payments = $this->Employees_model->set_employee_other_payments_payslip($payment[0]->payslip_id);

        $other_payments_amount = 0;
        foreach ($other_payments->result() as $sl_other_payments) {
            $other_payments_amount += $sl_other_payments->other_payments_amount;
        }

        // ========================================================================================================================
        // Pinjaman
        // ========================================================================================================================
        $count_loan = $this->Employees_model->count_employee_deductions_payslip($payment[0]->payslip_id);
        $loan = $this->Employees_model->set_employee_deductions_payslip($payment[0]->payslip_id);

        $loan_amount = 0;
        foreach ($loan->result() as $sl_loan) {
            $loan_amount += $sl_loan->loan_de_amount;
        }

        // ========================================================================================================================
        // Potongan Kehadiran
        // ========================================================================================================================
        $count_attedance = $this->Employees_model->count_employee_deductions_payslip($payment[0]->payslip_id);
        $attedance  = $this->Employees_model->set_employee_deductions_payslip($payment[0]->payslip_id);

        $attedance_amount = 0;
        foreach ($attedance->result() as $sl_attedance) {
            $attedance_amount += $sl_attedance->potongan_absen;
        }

        // ========================================================================================================================
        // Potongan Lain
        // ========================================================================================================================
        $count_potongan_lain = $this->Employees_model->count_employee_potongan_lain_payslip($payment[0]->payslip_id);
        $potongan_lain       = $this->Employees_model->set_employee_potongan_lain_payslip($payment[0]->payslip_id);

        $potongan_lain_amount = 0;
        foreach ($potongan_lain->result() as $sl_potongan_lain) {
            $potongan_lain_amount += $sl_potongan_lain->potongan_lain;
        }
        // ========================================================================================================================

        $tbl = '<br><br>
                    <table cellpadding="1" cellspacing="1" border="0" >
                        <tr>
                            <td align="center"><h2> SLIP GAJI KARYAWAN </h2></td>
                        </tr>
                        <tr>
                            <td align="center">' . $this->lang->line('xin_payroll_year_date') . ': ' . $half_title . ' <strong>' . date("F Y", strtotime($payment[0]->salary_month)) . '</strong></td>
                        </tr>
                    </table>
                ';

        $pdf->writeHTML($tbl, true, false, false, false, '');
        // -----------------------------------------------------------------------------
        // set cell padding
        $pdf->setCellPaddings(1, 1, 1, 1);

        // set cell margins
        $pdf->setCellMargins(0, 0, 0, 0);

        // set color for background
        $pdf->SetFillColor(255, 255, 127);
        // set some text for example
        //$txt = 'Employee Details';
        // Multicell
        // $pdf->MultiCell(180, 6, $txt, 0, 'L', 11, 0, '', '', true);
        $pdf->Ln(7);
        $tbl1 = '
                    <table cellpadding="1" cellspacing="0" border="0" width="100%" style="font-size:11px;" >

                    <tr>
                        <td width="15%;"> <strong>NAMA</strong> </td>
                        <td width="35%;"> : ' . strtoupper($fname) . '</td>
                        <td width="15%;"> <strong>PERIODE</strong> </td>
                        <td width="35%;"> : ' . $start_att . ' s/d ' . $end_att . ' </td>
                    </tr>
                    <tr>
                        <td width="15%;"> <strong>JABATAN</strong> </td>
                        <td width="35%;"> : ' . strtoupper($_designation_name) . '</td>
                        <td width="15%;"> <strong>N.I.K</strong> </td>
                        <td width="35%;"> : ' . $user[0]->employee_id . '</td>
                    </tr>';


        $tbl1 .= '
                        </table>';

        $pdf->writeHTML($tbl1, true, false, true, false, '');


        //// break..
        // $pdf->Ln(0);


        $tbl_new = '
                    <table cellpadding="6" cellspacing="0" border="1" width="100%" style="border: 1px solid #ccc; font-size:11px;">

                    <tr>
                        <td width="31%;" align ="center" colspan="2"> <strong>RINCIAN</strong> </td>
                        <td width="19%;" align ="center" > <strong>NOMINAL</strong> </td>


                        <td width="31%;" align ="center" colspan="2"> <strong>RINCIAN</strong> </td>
                        <td width="19%;" align ="center" > <strong>NOMINAL</strong> </td>
                    </tr>
                    <tr>
                        <td width="5%;" align ="center" style="text-align: middle;" rowspan="7">P<br>E<br>N<br>G<br>H<br>A<br>S<br>I<br>L<br>A<br>N</td>
                        <td width="26%;;"> GAJI POKOK </td>

                        <td width="19%;" align="right"> ' . number_format($bs, 0, ',', '.') . ' </td>

                        <td width="5%;;" align ="center" rowspan="7">P<br>O<br>T<br>O<br>N<br>G<br>A<br>N</td>
                        <td width="26%;;"> CICILAN PINJAMAN </td>';

        if ($count_loan > 0) {
            foreach ($loan->result() as $sl_loan) {
                $tbl_new .= '

                                <td width="19%;" align="right">' . number_format($sl_loan->loan_de_amount, 0, ',', '.') . '</td>';
            }
        }

        $tbl_new .= '
                    </tr>

                    <tr>
                        <td width="26%;"> TJ. JABATAN </td>';

        if ($count_allowances > 0) {
            foreach ($allowances->result() as $sl_allowances) {
                $tbl_new .= '

                                <td width="19%;" align="right" >' . number_format($sl_allowances->jumlah_tunj_jabatan, 0, ',', '.') . '</td>';
            }
        }

        $tbl_new .= '
                        <td width="26%;"> PPH 21 </td>';

        if ($count_other_payments > 0) {
            foreach ($other_payments->result() as $sl_other_payments) {
                $tbl_new .= '

                                <td width="19%;" align="right">' . number_format($sl_other_payments->other_payments_amount, 0, ',', '.') . '</td>';
            }
        }

        $tbl_new .= '
                    </tr>
                    <tr>

                        <td width="26%;"> TJ. PRODUKTIFITAS </td>';

        if ($count_allowances > 0) {
            foreach ($allowances->result() as $sl_allowances) {
                $tbl_new .= '

                                <td width="19%;" align="right">' . number_format($sl_allowances->jumlah_tunj_produktifitas, 0, ',', '.') . '</td>';
            }
        }

        $tbl_new .= '

                        <td width="26%;"> BPJS TK </td>';

        if ($count_statutory_deductions > 0) {
            foreach ($statutory_deductions->result() as $sl_statutory_deductions) {
                $tbl_new .= '
                                <td width="19%;;" align="right">' . number_format($sl_statutory_deductions->bpjs_tk_amount, 0, ',', '.') . '</td>';
            }
        }

        $tbl_new .= '
                    </tr>
                    <tr>
                        <td width="26%;"> TJ. TRANSPORT </td>';

        if ($count_allowances > 0) {
            foreach ($allowances->result() as $sl_allowances) {
                $tbl_new .= '

                                <td width="19%;" align="right">' . number_format($sl_allowances->jumlah_tunj_transportasi, 0, ',', '.') . '</td>';
            }
        }

        $tbl_new .= '

                        <td width="26%;"> BPJS KESEHATAN </td>';

        if ($count_statutory_deductions > 0) {
            foreach ($statutory_deductions->result() as $sl_statutory_deductions) {
                $tbl_new .= '
                                <td width="19%;;" align="right">' . number_format($sl_statutory_deductions->bpjs_kes_amount, 0, ',', '.') . '</td>';
            }
        }

        $tbl_new .= '
                    </tr>
                    <tr>

                        <td width="26%;"> TJ. KOMUNIKASI </td>';

        if ($count_allowances > 0) {
            foreach ($allowances->result() as $sl_allowances) {
                $tbl_new .= '

                                <td width="19%;" align="right">' . number_format($sl_allowances->jumlah_tunj_komunikasi, 0, ',', '.') . '</td>';
            }
        }

        $tbl_new .= '
                        <td width="26%;"> TIDAK MASUK </td>';

        if ($count_attedance > 0) {
            foreach ($attedance->result() as $sl_attedance) {
                $tbl_new .= '


                                <td width="19%;" align="right">' . number_format($sl_attedance->potongan_absen, 0, ',', '.') . '</td>';
            }
        }

        $tbl_new .= '
                    </tr>

                    <tr>
                        <td width="26%;"> OVERTIME </td>';
        if ($count_overtime > 0) {
            foreach ($overtime->result() as $sl_overtime) {

                $tbl_new .= '

                                <td width="19%;" align="right">' . number_format($sl_overtime->overtime_amount, 0, ',', '.') . '</td>';
            }
        }
        $tbl_new .= '
                        <td width="26%;"> LAIN-LAIN </td>';

        if ($count_potongan_lain > 0) {

            foreach ($potongan_lain->result() as $sl_potongan_lain) {
                $tbl_new .= '<td width="19%;" align="right">' . number_format($sl_potongan_lain->potongan_lain, 0, ',', '.') . '</td>';
            }
        } else {

            $tbl_new .= '<td width="19%;" align="right"> 0 </td>';
        }

        $tbl_new .= '</tr>

                    <tr>
                        <td width="26%;"> INSENTIF / REKONSILIASI </td>';

        if ($count_commissions > 0) {
            foreach ($commissions->result() as $sl_commissions) {
                $tbl_new .= '
                                    <td width="19%;" align="right">' . number_format($sl_commissions->commissions_amount, 0, ',', '.') . '</td>';
            }
        }

        $tbl_new .= ' <td width="26%;"> </td>

                        <td width="19%;"> </td>
                    </tr>';

        $total_earning    = $bs + $allowances_amount + $overtime_amount + $commissions_amount;
        $total_deduction  = $loan_amount + $bpjs_amount + $other_payments_amount + $attedance_amount + $potongan_lain_amount;
        $total_net_salary = $total_earning - $total_deduction;

        $tbl_new .= ' <tr>
                        <td width="31%;" align="left" colspan="2"> <strong>TOTAL UPAH</strong></td>
                        <td width="19%;" align="right">' . number_format($total_earning, 0, ',', '.') . ' </td>

                        <td width="31%;" align="left" colspan="2"> <strong>TOTAL POTONGAN</strong></td>
                        <td width="19%;" align="right"> ' . number_format($total_deduction, 0, ',', '.') . ' </td>
                    </tr>
                    <tr>
                        <td width="81%;" align="left" colspan="5"> <strong>GAJI YANG DITERIMA</strong></td>
                        <td width="19%;" align="right"> <b>' . number_format($total_net_salary, 0, ',', '.') . '</b> </td>
                    </tr>';


        $tbl_new .= '
                        </table>';

        $pdf->writeHTML($tbl_new, true, false, true, false, '');

        //// break..
        $pdf->Ln(0);

        $tbl = '
                    <table cellpadding="5" cellspacing="0" border="1" width="100%">
                        <tr>
                            <td >
                                Keterangan :
                            </td>
                        </tr>
                        <tr>
                            <td >
                                <ol class="c" >
                                    <li>Slip gaji adalah bukti dan informasi resmi penerimaan gaji dari pemberi kerja kepada karyawan.
                                        Mengingat slip gaji merupakan dokumen yang bersifat pribadi & rahasia ,mohon dapat digunakan dengan bijaksana.
                                    </li>
                                    <li>Sesuaikan jumlah gaji yang anda terima dengan slip.</li>
                                    <li>Segera sampaikan kepada pihak HRD jika ada yang tidak sesuai / kesalahan.</li>
                                    <li>Batas klarifikasi gaji maksimal 2 hari setelah slip gaji diterima.</li>
                                    <li>HRD tidak melayani klarifikasi jika melebihi point 3.</li>
                                    <li>Semoga gaji anda Berkah untuk anda dan keluarga.</li>

                                </ol>
                            </td>
                        </tr>


                    </table>';

        $pdf->writeHTML($tbl, true, false, false, false, '');

        // Jombang, '.date('l jS \of F Y \a\t G:i:s A').'
        $tbl_ttd = '
                    <table cellpadding="5" cellspacing="0" border="0" width="50%">
                        <tr>
                            <td>
                                <strong>Jombang, ' . date('d F Y') . '</strong>
                            </td>
                        </tr>
                        <tr>
                            <td><br><br><br><br><br><br></td>
                        </tr>
                        <tr>
                            <td>
                                HRD <br>
                                <strong>PT. AKUI BIRD NEST INDONESIA</strong>
                            </td>
                        </tr>
                    </table>';

        $pdf->writeHTML($tbl_ttd, true, false, false, false, '');

        $tbl_note = '
                    <table cellpadding="0" cellspacing="0" border="0" width="100%">
                        <tr>
                            <td align="center">
                                <strong>“private and confidential”</strong>
                            </td>
                        </tr>

                    </table>';

        $pdf->writeHTML($tbl_note, true, false, false, false, '');


        // Close and output PDF document
        // This method has several options, check the source code documentation for more information.
        $fname = strtolower($fname);
        $pay_month = strtolower(date("F Y", strtotime($payment[0]->year_to_date)));

        //Close and output PDF document
        ob_start();



        $pdf->Output('slip_gaji_bulanan_' . $fname . '_' . $pay_month . '.pdf', 'I');
        ob_end_flush();
    }

    public function payroll_template_read()
    {
        $session = $this->session->userdata('username');
        if (empty($session)) {
            redirect('admin/');
        }
        $data['title'] = $this->Core_model->site_title();
        $id = $this->input->get('employee_id');
        // get addd by > template
        $user = $this->Core_model->read_user_info($id);
        // user full name
        $full_name = $user[0]->first_name . ' ' . $user[0]->last_name;
        // get designation
        $designation = $this->Designation_model->read_designation_information($user[0]->designation_id);
        if (!is_null($designation)) {
            $designation_name = $designation[0]->designation_name;
        } else {
            $designation_name = '--';
        }
        // department
        $department = $this->Department_model->read_department_information($user[0]->department_id);
        if (!is_null($department)) {
            $department_name = $department[0]->department_name;
        } else {
            $department_name = '--';
        }
        $data = array(
            'first_name' => $user[0]->first_name,
            'last_name' => $user[0]->last_name,
            'employee_id' => $user[0]->employee_id,
            'user_id' => $user[0]->user_id,
            'department_name' => $department_name,
            'designation_name' => $designation_name,
            'date_of_joining' => $user[0]->date_of_joining,
            'profile_picture' => $user[0]->profile_picture,
            'gender' => $user[0]->gender,
            'wages_type' => $user[0]->wages_type,
            'basic_salary' => $user[0]->basic_salary,
            'daily_wages' => '',
        );
        if (!empty($session)) {
            $this->load->view('admin/payroll/dialog_templates', $data);
        } else {
            redirect('admin/');
        }
    }

    // =======================================================================================
    // DETAIL KARYAWAN : GAJI BULANAN
    // =======================================================================================

    // ===================================================================================
    // DETAIL
    // ===================================================================================
    public function bulanan_detail()
    {

        $session = $this->session->userdata('username');
        if (empty($session)) {
            redirect('admin/');
        }

        $role_resources_ids = $this->Core_model->user_role_resource();
        $check_role = $this->Employees_model->read_employee_information($session['user_id']);
        if (!in_array('1010', $role_resources_ids)) {
            redirect('admin/payroll/bulanan');
        }

        $id = $this->uri->segment(4);
        $result = $this->Employees_model->read_employee_information($id);
        if (is_null($result)) {
            redirect('admin/payroll/bulanan');
        }

        $data = array(
            'breadcrumbs'  => 'Edit Komponen Gaji Bulanan',
            'desc'         => 'INFORMASI : Edit Komponen Gaji Bulanan',
            'icon'         => '<i class="fa fa-pencil"></i>',
            'path_url'     => 'employees_detail_payroll_bulanan',
            'title'        => 'Edit Komponen Gaji Bulanan | ' . $this->Core_model->site_title(),

            'first_name'   => $result[0]->first_name,
            'last_name'    => $result[0]->last_name,
            'user_id'      => $result[0]->user_id,

            'wages_type'       => $result[0]->wages_type,
            'payment_type'     => $result[0]->payment_type,
            'flag'             => $result[0]->flag,
            'grade_type'       => $result[0]->grade_type,
            'basic_salary'     => $result[0]->basic_salary,

            'all_departments'  => $this->Department_model->all_departments(),
            'all_designations' => $this->Designation_model->all_designations(),
            'all_user_roles'   => $this->Roles_model->all_user_roles(),

        );


        $data['subview'] = $this->load->view("admin/payroll/bulanan_detail", $data, TRUE);

        $this->load->view('admin/layout/layout_main', $data); //page load

        // Datatables Variables
        $draw   = intval($this->input->get("draw"));
        $start  = intval($this->input->get("start"));
        $length = intval($this->input->get("length"));
    }

    //========================================================================================
    // 00. JENIS GAJI
    // =======================================================================================

    public function update_salary_option()
    {
        if ($this->input->post('type') == 'employee_update_salary') {
            /* Define return | here result is used to return user data and error for error message */
            $Return = array('result' => '', 'error' => '', 'csrf_hash' => '');
            $Return['csrf_hash'] = $this->security->get_csrf_hash();

            if ($this->input->post('wages_type') === '') {
                $Return['error'] = 'Jenis Gaji Tidak Boleh Kosong';
            } else if ($this->input->post('payment_type') === '') {
                $Return['error'] = 'Jenis Pembayaran Gaji Tidak Boleh Kosong';
            } else if ($this->input->post('flag') === '') {
                $Return['error'] = 'Jenis Kehadiran Tidak Boleh Kosong';
            } else if ($this->input->post('grade_type') === '') {
                $Return['error'] = 'Grade Gaji Tidak Boleh Kosong';
            }

            if ($Return['error'] != '') {
                $this->output($Return);
            }

            $data = array(
                'wages_type'   => $this->input->post('wages_type'),
                'payment_type' => $this->input->post('payment_type'),
                'flag'         => $this->input->post('flag'),
                'grade_type'   => $this->input->post('grade_type')

            );

            $id = $this->input->post('user_id');

            $result = $this->Employees_model->basic_info($data, $id);

            if ($result == TRUE) {
                $Return['result'] = 'Setting Gaji Berhasil Diupdate';
            } else {
                $Return['error'] = $this->lang->line('xin_error_msg');
            }

            $this->output($Return);
            exit;
        }
    }

    //========================================================================================
    // 00. UPDATE GAPOK
    // =======================================================================================

    public function update_salary_gapok()
    {
        if ($this->input->post('type') == 'employee_update_salary_gapok') {
            /* Define return | here result is used to return user data and error for error message */
            $Return = array('result' => '', 'error' => '', 'csrf_hash' => '');
            $Return['csrf_hash'] = $this->security->get_csrf_hash();

            if ($this->input->post('basic_salary') === '') {
                $Return['error'] = $this->lang->line('xin_employee_salary_error_basic');
            }

            if ($Return['error'] != '') {
                $this->output($Return);
            }

            $data = array(
                'basic_salary' => $this->input->post('basic_salary')
            );

            $id = $this->input->post('user_id');

            $result = $this->Employees_model->basic_info($data, $id);

            if ($result == TRUE) {
                $Return['result'] = 'Data Gaji Pokok Berhasil Diupdate';
            } else {
                $Return['error'] = $this->lang->line('xin_error_msg');
            }

            $this->output($Return);
            exit;
        }
    }

    // =======================================================================================
    // 01. BANK
    // =======================================================================================

    public function bank_account()
    {
        //set data
        $data['title'] = $this->Core_model->site_title();
        $session = $this->session->userdata('username');
        if (!empty($session)) {
            $this->load->view("admin/payroll/bulanan_detail", $data);
        } else {
            redirect('admin/');
        }
        // Datatables Variables
        $draw = intval($this->input->get("draw"));
        $start = intval($this->input->get("start"));
        $length = intval($this->input->get("length"));

        $id = $this->uri->segment(4);
        $bank_account = $this->Employees_model->set_employee_bank_account($id);

        $data = array();

        foreach ($bank_account->result() as $r) {

            if ($r->is_primary == 1) {
                $primary = '<span class="tag tag-success"> Rekening Utama </span>';
            } else {
                $primary = '';
            }

            $data[] = array(
                '   <span data-toggle="tooltip" data-placement="top" title="' . $this->lang->line('xin_edit') . '">
                                <button type="button" class="btn btn-secondary btn-sm m-b-0-0 waves-effect waves-light" data-toggle="modal" data-target=".edit-modal-data" data-field_id="' . $r->bankaccount_id . '" data-field_type="bank_account">
                                    <i class="fa fa-pencil-square-o"></i>
                                </button>
                            </span><span data-toggle="tooltip" data-placement="top" title="' . $this->lang->line('xin_delete') . '">
                                <button type="button" class="btn btn-danger btn-sm m-b-0-0 waves-effect waves-light delete" data-toggle="modal" data-target=".delete-modal" data-record-id="' . $r->bankaccount_id . '" data-token_type="bank_account">
                                    <i class="fa fa-trash-o"></i>
                                </button>
                            </span>',
                $r->account_number . ' (' . $primary . ') ',
                $r->bank_code . '-' . $r->bank_name,
                $r->bank_branch
            );
        }

        $output = array(
            "draw" => $draw,
            "recordsTotal" => $bank_account->num_rows(),
            "recordsFiltered" => $bank_account->num_rows(),
            "data" => $data
        );
        $this->output->set_output(json_encode($output));
    }

    // Read
    public function dialog_bank_account()
    {
        $session = $this->session->userdata('username');
        if (empty($session)) {
            redirect('admin/');
        }
        $data['title'] = $this->Core_model->site_title();
        $id            = $this->input->get('field_id');
        $result = $this->Employees_model->read_bank_account_information($id);
        $data = array(
            'bankaccount_id' => $result[0]->bankaccount_id,
            'employee_id'    => $result[0]->employee_id,
            'is_primary'     => $result[0]->is_primary,
            'account_title'  => $result[0]->account_title,
            'account_number' => $result[0]->account_number,
            'bank_name'      => $result[0]->bank_name,
            'bank_branch'    => $result[0]->bank_branch
        );
        if (!empty($session)) {
            $this->load->view('admin/payroll/dialog_bulanan_detail', $data);
        } else {
            redirect('admin/');
        }
    }

    // Tambah
    public function bank_account_info()
    {

        if ($this->input->post('type') == 'bank_account_info') {
            /* Define return | here result is used to return user data and error for error message */
            $Return = array('result' => '', 'error' => '', 'csrf_hash' => '');
            $Return['csrf_hash'] = $this->security->get_csrf_hash();

            /* Server side PHP input validation */
            if ($this->input->post('account_number') === '') {
                $Return['error'] = $this->lang->line('xin_employee_error_acc_number');
            } else if ($this->input->post('bank_name') === '') {
                $Return['error'] = $this->lang->line('xin_employee_error_bank_name');
            }

            if ($Return['error'] != '') {
                $this->output($Return);
            }

            if ($this->input->post('bank_name') == 'Bank BNI') {
                $bank_code = '009';
            } else if ($this->input->post('bank_name') == 'Bank BCA') {
                $bank_code = '014';
            } else if ($this->input->post('bank_name') == 'Bank BRI') {
                $bank_code = '002';
            }
            if (null != $this->input->post('is_primary')) {
                $is_primary = $this->input->post('is_primary');
            } else {
                $is_primary = '';
            }

            $data = array(
                'account_title'  => 'Rekening Karyawan',
                'is_primary'     => $is_primary,
                'account_number' => $this->input->post('account_number'),
                'bank_name'      => $this->input->post('bank_name'),
                'bank_code'      => $bank_code,
                'bank_branch'    => $this->input->post('bank_branch'),
                'employee_id'    => $this->input->post('user_id'),
                'created_at'     => date('d-m-Y'),
            );
            $result = $this->Employees_model->bank_account_info_add($data);
            if ($result == TRUE) {
                $Return['result'] = 'Rekening Bank Berhasil Ditambahkan';
            } else {
                $Return['error'] = $this->lang->line('xin_error_msg');
            }
            $this->output($Return);
            exit;
        }
    }

    // Edit
    public function e_bank_account_info()
    {

        if ($this->input->post('type') == 'e_bank_account_info') {
            /* Define return | here result is used to return user data and error for error message */
            $Return = array('result' => '', 'error' => '', 'csrf_hash' => '');
            $Return['csrf_hash'] = $this->security->get_csrf_hash();
            /* Server side PHP input validation */

            if ($this->input->post('account_number') === '') {
                $Return['error'] = $this->lang->line('xin_employee_error_acc_number');
            } else if ($this->input->post('bank_name') === '') {
                $Return['error'] = $this->lang->line('xin_employee_error_bank_name');
            }

            if ($Return['error'] != '') {
                $this->output($Return);
            }

            if ($this->input->post('bank_name') == 'Bank BNI') {
                $bank_code = '009';
            } else if ($this->input->post('bank_name') == 'Bank BCA') {
                $bank_code = '014';
            } else if ($this->input->post('bank_name') == 'Bank BRI') {
                $bank_code = '002';
            }

            if (null != $this->input->post('is_primary')) {
                $is_primary = $this->input->post('is_primary');
            } else {
                $is_primary = '';
            }

            $data = array(
                'account_title'   => 'Rekening Karyawan',
                'is_primary'      => $is_primary,
                'account_number'  => $this->input->post('account_number'),
                'bank_name'       => $this->input->post('bank_name'),
                'bank_code'       => $bank_code,
                'bank_branch'     => $this->input->post('bank_branch')
            );
            $e_field_id = $this->input->post('e_field_id');
            $result = $this->Employees_model->bank_account_info_update($data, $e_field_id);
            if ($result == TRUE) {
                $Return['result'] = 'Rekening Bank Berhasil Diperbarui';
            } else {
                $Return['error'] = $this->lang->line('xin_error_msg');
            }
            $this->output($Return);
            exit;
        }
    }

    // Hapus
    public function delete_bank_account()
    {

        if ($this->input->post('data') == 'delete_record') {
            /* Define return | here result is used to return user data and error for error message */
            $Return = array('result' => '', 'error' => '', 'csrf_hash' => '');
            $Return['csrf_hash'] = $this->security->get_csrf_hash();

            $id     = $this->uri->segment(4);
            $result = $this->Employees_model->delete_bank_account_record($id);
            if (isset($id)) {
                $Return['result'] = 'Rekening Bank Berhasil Dihapus';
            } else {
                $Return['error'] = $this->lang->line('xin_error_msg');
            }
            $this->output($Return);
        }
    }

    // =======================================================================================
    // 02. TUNJANGAN
    // =======================================================================================

    public function salary_all_allowances()
    {
        //set data
        $data['title'] = $this->Core_model->site_title();
        $session = $this->session->userdata('username');
        if (!empty($session)) {
            $this->load->view("admin/payroll/bulanan_detail", $data);
        } else {
            redirect('admin/');
        }
        // Datatables Variables
        $draw = intval($this->input->get("draw"));
        $start = intval($this->input->get("start"));
        $length = intval($this->input->get("length"));

        $id = $this->uri->segment(4);


        $allowances = $this->Employees_model->set_employee_allowances($id);

        $data = array();


        foreach ($allowances->result() as $r) {



            $data[] = array(

                '<span data-toggle="tooltip" data-placement="top" title="' . $this->lang->line('xin_edit') . '">
                                <button type="button" class="btn icon-btn btn-xs btn-default waves-effect waves-light" data-toggle="modal" data-target=".edit-modal-data" data-field_id="' . $r->allowance_id . '" data-field_type="salary_allowance">
                                   <span class="fa fa-pencil"></span>
                                </button>
                            </span>
                            <span data-toggle="tooltip" data-placement="top" title="' . $this->lang->line('xin_delete') . '">
                                <button type="button" class="btn icon-btn btn-xs btn-danger waves-effect waves-light delete" data-toggle="modal" data-target=".delete-modal" data-record-id="' . $r->allowance_id . '" data-token_type="all_allowances">
                                <span class="fa fa-trash"></span>
                                </button>
                            </span>',

                $this->Core_model->set_date_format($r->allowance_date),

                $this->Core_model->currency_sign($r->tnj_jabatan),

                $this->Core_model->currency_sign($r->tnj_produktifitas),

                $this->Core_model->currency_sign($r->tnj_komunikasi),

                $this->Core_model->currency_sign($r->tnj_transportasi)

            );
        }

        $output = array(
            "draw" => $draw,
            "recordsTotal" => $allowances->num_rows(),
            "recordsFiltered" => $allowances->num_rows(),
            "data" => $data
        );
        $this->output->set_output(json_encode($output));
    }

    // Read
    public function dialog_salary_allowance()
    {
        $session = $this->session->userdata('username');
        if (empty($session)) {
            redirect('admin/');
        }
        $data['title'] = $this->Core_model->site_title();
        $id = $this->input->get('field_id');
        $result = $this->Employees_model->read_single_salary_allowance($id);
        $data = array(
            'allowance_id'         => $result[0]->allowance_id,
            'employee_id'          => $result[0]->employee_id,
            'allowance_date'        => $result[0]->allowance_date,
            'tnj_jabatan'            => $result[0]->tnj_jabatan,
            'tnj_produktifitas'    => $result[0]->tnj_produktifitas,
            'tnj_komunikasi'       => $result[0]->tnj_komunikasi,
            'tnj_transportasi'     => $result[0]->tnj_transportasi
        );
        if (!empty($session)) {
            $this->load->view('admin/payroll/dialog_bulanan_detail', $data);
        } else {
            redirect('admin/');
        }
    }

    // Tambah
    public function employee_allowance_option()
    {
        if ($this->input->post('type') == 'employee_update_allowance') {
            /* Define return | here result is used to return user data and error for error message */
            $Return = array('result' => '', 'error' => '', 'csrf_hash' => '');
            $Return['csrf_hash'] = $this->security->get_csrf_hash();

            if ($this->input->post('allowance_date') === '') {
                $Return['error'] = 'Tanggal Tunjangan Belum Ditentukan';
            } else if ($this->input->post('tnj_jabatan') === '') {
                $Return['error'] =  'Jumlah Tunjangan Jabatan Belum Ditentukan';
            } else if ($this->input->post('tnj_produktifitas') === '') {
                $Return['error'] =  'Jumlah Tunjangan Produktifitas Belum Ditentukan';
            } else if ($this->input->post('tnj_komunikasi') === '') {
                $Return['error'] =  'Jumlah Tunjangan Komunikasi Belum Ditentukan';
            } else if ($this->input->post('tnj_transportasi') === '') {
                $Return['error'] =  'Jumlah Tunjangan Transportasi Belum Ditentukan';
            }

            if ($Return['error'] != '') {
                $this->output($Return);
            }
            $data = array(
                'allowance_date'       => $this->input->post('allowance_date'),
                'employee_id'          => $this->input->post('user_id'),
                'tnj_jabatan'          => $this->input->post('tnj_jabatan'),
                'tnj_produktifitas'    => $this->input->post('tnj_produktifitas'),
                'tnj_komunikasi'       => $this->input->post('tnj_komunikasi'),
                'tnj_transportasi'     => $this->input->post('tnj_transportasi'),
                'created_at'           => date('Y-m-d h:i:s')
            );
            $result = $this->Employees_model->add_salary_allowances($data);

            if ($result == TRUE) {
                $Return['result'] = 'Tunjangan Berhasil Ditambahkan';
            } else {
                $Return['error'] = $this->lang->line('xin_error_msg');
            }

            $this->output($Return);
            exit;
        }
    }

    // Edit
    public function update_allowance_info()
    {

        if ($this->input->post('type') == 'e_allowance_info') {

            /* Define return | here result is used to return user data and error for error message */
            $Return = array('result' => '', 'error' => '', 'csrf_hash' => '');
            $Return['csrf_hash'] = $this->security->get_csrf_hash();

            /* Server side PHP input validation */

            if ($this->input->post('allowance_date') === '') {
                $Return['error'] = 'Tanggal Tunjangan Belum Ditentukan';
            } else if ($this->input->post('tnj_jabatan') === '') {
                $Return['error'] =  'Jumlah Tunjangan Jabatan Belum Ditentukan';
            } else if ($this->input->post('tnj_produktifitas') === '') {
                $Return['error'] =  'Jumlah Tunjangan Produktifitas Belum Ditentukan';
            } else if ($this->input->post('tnj_komunikasi') === '') {
                $Return['error'] =  'Jumlah Tunjangan Komunikasi Belum Ditentukan';
            } else if ($this->input->post('tnj_transportasi') === '') {
                $Return['error'] =  'Jumlah Tunjangan Transportasi Belum Ditentukan';
            }

            if ($Return['error'] != '') {
                $this->output($Return);
            }

            $data = array(
                'allowance_date'       => $this->input->post('allowance_date'),
                'tnj_jabatan'          => $this->input->post('tnj_jabatan'),
                'tnj_produktifitas'    => $this->input->post('tnj_produktifitas'),
                'tnj_komunikasi'       => $this->input->post('tnj_komunikasi'),
                'tnj_transportasi'     => $this->input->post('tnj_transportasi'),
                'edited_at'            => date('Y-m-d h:i:s')
            );
            $e_field_id = $this->input->post('e_field_id');
            $result     = $this->Employees_model->salary_allowance_update_record($data, $e_field_id);

            // echo "<pre>";
            // print_r($this->db->last_query());
            // echo "</pre>";
            // die();

            if ($result == TRUE) {
                $Return['result'] = 'Tunjangan Berhasil Diperbarui';
            } else {
                $Return['error'] = $this->lang->line('xin_error_msg');
            }
            $this->output($Return);
            exit;
        }
    }

    // Hapus
    public function delete_all_allowances()
    {

        if ($this->input->post('data') == 'delete_record') {
            /* Define return | here result is used to return user data and error for error message */
            $Return = array('result' => '', 'error' => '', 'csrf_hash' => '');
            $Return['csrf_hash'] = $this->security->get_csrf_hash();
            $id = $this->uri->segment(4);
            $result = $this->Employees_model->delete_allowance_record($id);
            if (isset($id)) {
                $Return['result'] = 'Tunjangan Berhasil Dihapus';
            } else {
                $Return['error'] = $this->lang->line('xin_error_msg');
            }
            $this->output($Return);
        }
    }

    // =======================================================================================
    // 03. PINJAMAN
    // =======================================================================================

    public function salary_all_deductions()
    {
        //set data
        $data['title'] = $this->Core_model->site_title();
        $session = $this->session->userdata('username');
        if (!empty($session)) {
            $this->load->view("admin/payroll/bulanan_detail", $data);
        } else {
            redirect('admin/');
        }
        // Datatables Variables
        $draw = intval($this->input->get("draw"));
        $start = intval($this->input->get("start"));
        $length = intval($this->input->get("length"));

        $id = $this->uri->segment(4);
        $deductions = $this->Employees_model->set_employee_deductions($id);

        $data = array();

        foreach ($deductions->result() as $r) {

            $sdate = $this->Core_model->set_date_format($r->start_date);
            $edate = $this->Core_model->set_date_format($r->end_date);

            // loan time
            if ($r->loan_time < 2) {
                $loan_time = $r->loan_time . ' ' . $this->lang->line('xin_employee_loan_time_single_month');
            } else {
                $loan_time = $r->loan_time . ' ' . $this->lang->line('xin_employee_loan_time_more_months');
            }

            if ($r->loan_options == 1) {
                $loan_options = $this->lang->line('xin_loan_ssc_title');
            } else if ($r->loan_options == 2) {
                $loan_options = $this->lang->line('xin_loan_hdmf_title');
            } else {
                $loan_options = $this->lang->line('xin_loan_other_sd_title');
            }

            $loan_durasi =  $sdate . ' s/d ' . $edate;

            $loan_details = $r->loan_deduction_title;

            //$eoption_removed = '<span data-toggle="tooltip" data-placement="top" title="'.$this->lang->line('xin_edit').'"><button type="button" class="btn icon-btn btn-xs btn-outline-info waves-effect waves-light" data-toggle="modal" data-target=".edit-modal-data" data-field_id="'. $r->loan_deduction_id . '" data-field_type="salary_loan"><span class="fas fa-pencil-alt"></span></button></span>';

            $data[] = array(
                '<span data-toggle="tooltip" data-placement="top" title="' . $this->lang->line('xin_edit') . '">
                            <button type="button" class="btn icon-btn btn-xs btn-default waves-effect waves-light"  data-toggle="modal" data-target=".edit-modal-data" data-field_id="' . $r->loan_deduction_id . '" data-field_type="salary_loan">
                                <span class="fa fa-pencil"></span>
                            </button>
                        </span>
                        <span data-toggle="tooltip" data-placement="top" title="' . $this->lang->line('xin_delete') . '">
                            <button type="button" class="btn icon-btn btn-xs btn-danger waves-effect waves-light delete" data-toggle="modal" data-target=".delete-modal" data-record-id="' . $r->loan_deduction_id . '" data-token_type="all_deductions">
                                <span class="fa fa-trash"></span>
                            </button>
                        </span>',
                date("d-m-Y", strtotime($r->loan_date)),
                $loan_durasi,

                $loan_details,
                $this->Core_model->currency_sign($r->monthly_installment),
                $this->Core_model->currency_sign($r->loan_deduction_amount),
                $loan_time,
            );
        }

        $output = array(
            "draw" => $draw,
            "recordsTotal" => $deductions->num_rows(),
            "recordsFiltered" => $deductions->num_rows(),
            "data" => $data
        );
        $this->output->set_output(json_encode($output));
    }

    // Read
    public function dialog_salary_loan()
    {
        $session = $this->session->userdata('username');
        if (empty($session)) {
            redirect('admin/');
        }
        $data['title'] = $this->Core_model->site_title();
        $id            = $this->input->get('field_id');
        $result        = $this->Employees_model->read_single_loan_deductions($id);
        $data          = array(
            'loan_deduction_id' => $result[0]->loan_deduction_id,
            'loan_date' => $result[0]->loan_date,
            'employee_id' => $result[0]->employee_id,
            'loan_deduction_title' => $result[0]->loan_deduction_title,
            'start_date' => $result[0]->start_date,
            'end_date' => $result[0]->end_date,
            'loan_options' => $result[0]->loan_options,
            'monthly_installment' => $result[0]->monthly_installment,
            'reason' => $result[0]->reason,
            'created_at' => $result[0]->created_at
        );
        if (!empty($session)) {
            $this->load->view('admin/payroll/dialog_bulanan_detail', $data);
        } else {
            redirect('admin/');
        }
    }

    // Tambah
    public function employee_loan_info()
    {

        if ($this->input->post('type') == 'loan_info') {

            /* Define return | here result is used to return user data and error for error message */
            $Return = array('result' => '', 'error' => '', 'csrf_hash' => '');
            $Return['csrf_hash'] = $this->security->get_csrf_hash();
            $reason = $this->input->post('reason');
            $qt_reason = htmlspecialchars(addslashes($reason), ENT_QUOTES);
            $start_date = $this->input->post('start_date');
            $end_date = $this->input->post('end_date');
            $st_date = strtotime($start_date);
            $ed_date = strtotime($end_date);

            $user_id = $this->input->post('user_id');

            /* Server side PHP input validation */
            if ($this->input->post('loan_deduction_title') === '') {
                $Return['error'] = $this->lang->line('xin_employee_set_loan_title_error');
            } else if ($this->input->post('loan_date') === '') {
                $Return['error'] = 'Tanggal Pinjaman Belum Diisi';
            } else if ($this->input->post('monthly_installment') === '') {
                $Return['error'] =  'Besar Pinjaman';
            } else if ($this->input->post('start_date') === '') {
                $Return['error'] = 'Tanggal Mulai Angsuran';
            } else if ($this->input->post('end_date') === '') {
                $Return['error'] = 'Tanggal Sampai Angsuran';
            } else if ($st_date > $ed_date) {
                $Return['error'] = $this->lang->line('xin_error_start_end_date');
            }

            if ($Return['error'] != '') {
                $this->output($Return);
            }

            $tm = $this->Employees_model->get_month_diff($this->input->post('start_date'), $this->input->post('end_date'));
            if ($tm < 1) {
                $m_ins = $this->input->post('monthly_installment');
            } else {
                $m_ins = $this->input->post('monthly_installment') / $tm;
            }

            $data = array(
                'loan_date'             => $this->input->post('loan_date'),
                'loan_deduction_title'  => $this->input->post('loan_deduction_title'),
                'reason'                => $qt_reason,
                'monthly_installment'   => $this->input->post('monthly_installment'),
                'start_date'            => $this->input->post('start_date'),
                'end_date'              => $this->input->post('end_date'),
                'loan_options'          => $this->input->post('loan_options'),
                'loan_time'             => $tm,
                'loan_deduction_amount' => $m_ins,
                'employee_id'           => $user_id
            );

            $result = $this->Employees_model->add_salary_loan($data);

            if ($result == TRUE) {
                $Return['result'] = 'Pinjaman Berhasil Ditambahkan';
            } else {
                $Return['error'] = $this->lang->line('xin_error_msg');
            }
            $this->output($Return);
            exit;
        }
    }

    // Edit
    public function update_loan_info()
    {
        if ($this->input->post('type') == 'loan_info') {
            /* Define return | here result is used to return user data and error for error message */
            $Return = array('result' => '', 'error' => '', 'csrf_hash' => '');
            $Return['csrf_hash'] = $this->security->get_csrf_hash();
            $reason = $this->input->post('reason');
            $qt_reason = htmlspecialchars(addslashes($reason), ENT_QUOTES);
            $start_date = $this->input->post('start_date');
            $end_date = $this->input->post('end_date');
            $st_date = strtotime($start_date);
            $ed_date = strtotime($end_date);

            $id = $this->input->post('e_field_id');

            /* Server side PHP input validation */
            if ($this->input->post('loan_deduction_title') === '') {
                $Return['error'] = $this->lang->line('xin_employee_set_loan_title_error');
            } else if ($this->input->post('loan_date') === '') {
                $Return['error'] = 'Tanggal Pinjaman Belum Diisi';
            } else if ($this->input->post('monthly_installment') === '') {
                $Return['error'] =  'Besar Pinjaman';
            } else if ($this->input->post('start_date') === '') {
                $Return['error'] = 'Tanggal Mulai Angsuran';
            } else if ($this->input->post('end_date') === '') {
                $Return['error'] = 'Tanggal Sampai Angsuran';
            } else if ($st_date > $ed_date) {
                $Return['error'] = $this->lang->line('xin_error_start_end_date');
            }

            if ($Return['error'] != '') {
                $this->output($Return);
            }

            $tm = $this->Employees_model->get_month_diff($this->input->post('start_date'), $this->input->post('end_date'));
            if ($tm < 1) {
                $m_ins = $this->input->post('monthly_installment');
            } else {
                $m_ins = $this->input->post('monthly_installment') / $tm;
            }

            $data = array(
                'loan_date'             => $this->input->post('loan_date'),
                'loan_deduction_title'  => $this->input->post('loan_deduction_title'),
                'reason'                => $qt_reason,
                'monthly_installment'   => $this->input->post('monthly_installment'),
                'start_date'            => $this->input->post('start_date'),
                'end_date'              => $this->input->post('end_date'),
                'loan_options'          => $this->input->post('loan_options'),
                'loan_time'             => $tm,
                'loan_deduction_amount' => $m_ins
            );


            $result = $this->Employees_model->salary_loan_update_record($data, $id);

            if ($result == TRUE) {
                $Return['result'] = 'Pinjaman Berhasil Diperbarui';
            } else {
                $Return['error'] = $this->lang->line('xin_error_msg');
            }

            $this->output($Return);
            exit;
        }
    }

    // Hapus
    public function delete_all_deductions()
    {
        if ($this->input->post('data') == 'delete_record') {
            /* Define return | here result is used to return user data and error for error message */
            $Return = array('result' => '', 'error' => '', 'csrf_hash' => '');
            $Return['csrf_hash'] = $this->security->get_csrf_hash();
            $id = $this->uri->segment(4);
            $result = $this->Employees_model->delete_loan_record($id);
            if (isset($id)) {
                $Return['result'] = 'Pinajaman Berhasil Dihapus';
            } else {
                $Return['error'] = $this->lang->line('xin_error_msg');
            }
            $this->output($Return);
        }
    }

    // =======================================================================================
    // 04. INSENTIF
    // =======================================================================================

    public function salary_all_commissions()
    {
        //set data
        $data['title'] = $this->Core_model->site_title();
        $session = $this->session->userdata('username');
        if (!empty($session)) {
            $this->load->view("admin/payroll/bulanan_detail", $data);
        } else {
            redirect('admin/');
        }
        // Datatables Variables
        $draw = intval($this->input->get("draw"));
        $start = intval($this->input->get("start"));
        $length = intval($this->input->get("length"));

        $id = $this->uri->segment(4);
        $commissions = $this->Employees_model->set_employee_commissions($id);

        $data = array();

        foreach ($commissions->result() as $r) {

            $data[] = array(
                '<span data-toggle="tooltip" data-placement="top" title="' . $this->lang->line('xin_edit') . '"><button type="button" class="btn icon-btn btn-xs btn-default waves-effect waves-light" data-toggle="modal" data-target=".edit-modal-data" data-field_id="' . $r->salary_commissions_id . '" data-field_type="salary_commissions"><span class="fa fa-pencil"></span></button></span><span data-toggle="tooltip" data-placement="top" title="' . $this->lang->line('xin_delete') . '"><button type="button" class="btn icon-btn btn-xs btn-danger waves-effect waves-light delete" data-toggle="modal" data-target=".delete-modal" data-record-id="' . $r->salary_commissions_id . '" data-token_type="all_commissions"><span class="fa fa-trash"></span></button></span>',
                date("d-m-Y", strtotime($r->commission_date)),
                $r->commission_title,
                $this->Core_model->currency_sign($r->commission_amount)
            );
        }

        $output = array(
            "draw" => $draw,
            "recordsTotal" => $commissions->num_rows(),
            "recordsFiltered" => $commissions->num_rows(),
            "data" => $data
        );
        $this->output->set_output(json_encode($output));
    }

    // Read
    public function dialog_salary_commissions()
    {
        $session = $this->session->userdata('username');
        if (empty($session)) {
            redirect('admin/');
        }
        $data['title'] = $this->Core_model->site_title();
        $id = $this->input->get('field_id');
        $result = $this->Employees_model->read_single_salary_commissions($id);
        $data = array(
            'salary_commissions_id' => $result[0]->salary_commissions_id,
            'employee_id'           => $result[0]->employee_id,
            'commission_title'      => $result[0]->commission_title,
            'commission_date'       => $result[0]->commission_date,
            'commission_amount'     => $result[0]->commission_amount
        );

        if (!empty($session)) {
            $this->load->view('admin/payroll/dialog_bulanan_detail', $data);
        } else {
            redirect('admin/');
        }
    }

    // Tambah
    public function employee_commissions_option()
    {
        if ($this->input->post('type') == 'employee_update_commissions') {
            /* Define return | here result is used to return user data and error for error message */
            $Return = array('result' => '', 'error' => '', 'csrf_hash' => '');
            $Return['csrf_hash'] = $this->security->get_csrf_hash();
            if ($this->input->post('title') === '') {
                $Return['error'] = $this->lang->line('xin_error_title');
            } else if ($this->input->post('amount') === '') {
                $Return['error'] = $this->lang->line('xin_error_amount_field');
            }

            if ($Return['error'] != '') {
                $this->output($Return);
            }
            $data = array(
                'commission_date' => $this->input->post('date'),
                'commission_title' => $this->input->post('title'),
                'commission_amount' => $this->input->post('amount'),
                'employee_id' => $this->input->post('user_id')
            );
            $result = $this->Employees_model->add_salary_commissions($data);
            if ($result == TRUE) {
                $Return['result'] = 'Insentif Berhasil Ditambahkan';
            } else {
                $Return['error'] = $this->lang->line('xin_error_msg');
            }
            $this->output($Return);
            exit;
        }
    }

    // Edit
    public function update_commissions_info()
    {

        if ($this->input->post('type') == 'e_salary_commissions_info') {
            /* Define return | here result is used to return user data and error for error message */
            $Return = array('result' => '', 'error' => '', 'csrf_hash' => '');
            $Return['csrf_hash'] = $this->security->get_csrf_hash();

            /* Server side PHP input validation */
            if ($this->input->post('title') === '') {
                $Return['error'] = $this->lang->line('xin_error_title');
            } else if ($this->input->post('amount') === '') {
                $Return['error'] = $this->lang->line('xin_error_amount_field');
            }

            if ($Return['error'] != '') {
                $this->output($Return);
            }

            $data = array(
                'commission_date' => $this->input->post('date'),
                'commission_title' => $this->input->post('title'),
                'commission_amount' => $this->input->post('amount')
            );
            $e_field_id = $this->input->post('e_field_id');
            $result = $this->Employees_model->salary_commissions_update_record($data, $e_field_id);
            if ($result == TRUE) {
                $Return['result'] = 'Insentif Berhasil Diperbarui';
            } else {
                $Return['error'] = $this->lang->line('xin_error_msg');
            }
            $this->output($Return);
            exit;
        }
    }

    // Hapus
    public function delete_all_commissions()
    {

        if ($this->input->post('data') == 'delete_record') {
            /* Define return | here result is used to return user data and error for error message */
            $Return = array('result' => '', 'error' => '', 'csrf_hash' => '');
            $Return['csrf_hash'] = $this->security->get_csrf_hash();
            $id = $this->uri->segment(4);
            $result = $this->Employees_model->delete_commission_record($id);
            if (isset($id)) {
                $Return['result'] = 'Insentif Berhasil Dihapus';
            } else {
                $Return['error'] = $this->lang->line('xin_error_msg');
            }
            $this->output($Return);
        }
    }

    // =======================================================================================
    // 04. LEMBUR
    // =======================================================================================

    public function salary_all_overtime()
    {
        //set data
        $data['title'] = $this->Core_model->site_title();
        $session = $this->session->userdata('username');
        if (!empty($session)) {
            $this->load->view("admin/payroll/bulanan_detail", $data);
        } else {
            redirect('admin/');
        }
        // Datatables Variables
        $draw = intval($this->input->get("draw"));
        $start = intval($this->input->get("start"));
        $length = intval($this->input->get("length"));

        $id = $this->uri->segment(4);
        $overtime = $this->Employees_model->set_employee_overtime($id);
        $system = $this->Core_model->read_setting_info(1);
        $data = array();
        $no = 1;

        foreach ($overtime->result() as $r) {

            // get overtime date
            $overtime_date    = $r->overtime_date;

            // get start date
            $clock_in_m    = $r->clock_in_m;
            // get end date
            $clock_out_m   = $r->clock_out_m;

            // overtime date
            $overtime_time = $clock_in_m . ' ' . $this->lang->line('dashboard_to') . ' ' . $clock_out_m;


            // get report to
            $reports_to = $this->Core_model->read_user_info($r->reports_to);
            // user full name
            if (!is_null($reports_to)) {

                // get designation
                $designation = $this->Designation_model->read_designation_information($reports_to[0]->designation_id);
                if (!is_null($designation)) {
                    $designation_name = $designation[0]->designation_name;
                } else {
                    $designation_name = '<span class="badge bg-red"> ? </span>';
                }

                $manager_name = $reports_to[0]->first_name . ' ' . $reports_to[0]->last_name . ' <br><small>(' . $designation_name . ')</small>';
            } else {
                $manager_name = '?';
            }

            // get overtime type
            $type = $this->Overtime_model->read_overtime_type_information($r->overtime_type);
            if (!is_null($type)) {
                $itype = $type[0]->type;
            } else {
                $itype = '--';
            }
            $iitype = $r->description;

            // Jam Lembur
            $overtime_hours = $r->overtime_hours_total;

            // Uang Lembur
            $current_amount = $r->overtime_total;

            $data[] = array(
                '
                        <span data-toggle="tooltip" data-placement="top" title="' . $this->lang->line('xin_delete') . '">
                            <button type="button" class="btn icon-btn btn-xs btn-danger waves-effect waves-light delete" data-toggle="modal" data-target=".delete-modal" data-record-id="' . $r->salary_overtime_id . '" data-token_type="all_overtime">
                                <span class="fa fa-trash"></span>
                            </button>
                        </span>',

                date("d-m-Y", strtotime($overtime_date)),
                $overtime_time,
                $manager_name,
                $iitype,
                $overtime_hours,
                $this->Core_model->currency_sign($current_amount)

            );
            $no++;
        }

        $output = array(
            "draw" => $draw,
            "recordsTotal" => $overtime->num_rows(),
            "recordsFiltered" => $overtime->num_rows(),
            "data" => $data
        );
        echo json_encode($output);
        exit();
    }

    // Hapus
    public function delete_all_overtime()
    {

        if ($this->input->post('data') == 'delete_record') {
            /* Define return | here result is used to return user data and error for error message */
            $Return = array('result' => '', 'error' => '', 'csrf_hash' => '');
            $Return['csrf_hash'] = $this->security->get_csrf_hash();
            $id = $this->uri->segment(4);
            $result = $this->Employees_model->delete_salary_overtime_record($id);
            if (isset($id)) {
                $Return['result'] = 'Lembur Berhasil Dihapus';
            } else {
                $Return['error'] = $this->lang->line('xin_error_msg');
            }
            $this->output($Return);
        }
    }

    // =======================================================================================
    // 05. BPJS KES & TK
    // =======================================================================================

    public function salary_all_statutory_deductions()
    {
        //set data
        $data['title'] = $this->Core_model->site_title();
        $session = $this->session->userdata('username');
        if (!empty($session)) {
            $this->load->view("admin/payroll/bulanan_detail", $data);
        } else {
            redirect('admin/');
        }
        // Datatables Variables
        $draw   = intval($this->input->get("draw"));
        $start  = intval($this->input->get("start"));
        $length = intval($this->input->get("length"));

        $id = $this->uri->segment(4);
        $statutory_deductions = $this->Employees_model->set_employee_statutory_deductions($id);

        $data = array();

        foreach ($statutory_deductions->result() as $r) {
            if ($r->statutory_options == 1) {
                $sd_opt = $this->lang->line('xin_sd_ssc_title');
            } else if ($r->statutory_options == 2) {
                $sd_opt = $this->lang->line('xin_sd_phic_title');
            } else if ($r->statutory_options == 3) {
                $sd_opt = $this->lang->line('xin_sd_hdmf_title');
            } else if ($r->statutory_options == 4) {
                $sd_opt = $this->lang->line('xin_sd_wht_title');
            } else {
                $sd_opt = $this->lang->line('xin_sd_other_sd_title');
            }
            $data[] = array(
                '<span data-toggle="tooltip" data-placement="top" title="' . $this->lang->line('xin_edit') . '">
                           <button type="button" class="btn icon-btn btn-xs btn-default waves-effect waves-light" data-toggle="modal" data-target=".edit-modal-data" data-field_id="' . $r->statutory_deductions_id . '" data-field_type="salary_statutory_deductions">
                              <span class="fa fa-pencil"></span>
                           </button>
                        </span>

                        <span data-toggle="tooltip" data-placement="top" title="' . $this->lang->line('xin_delete') . '">
                             <button type="button" class="btn icon-btn btn-xs btn-danger waves-effect waves-light delete" data-toggle="modal" data-target=".delete-modal" data-record-id="' . $r->statutory_deductions_id . '" data-token_type="all_statutory_deductions">
                                  <span class="fa fa-trash"></span>
                             </button>
                        </span>',

                $this->Core_model->set_date_format($r->deduction_date),
                $sd_opt,
                $r->deduction_title,
                $this->Core_model->currency_sign($r->deduction_amount)

            );
        }

        $output = array(
            "draw" => $draw,
            "recordsTotal" => $statutory_deductions->num_rows(),
            "recordsFiltered" => $statutory_deductions->num_rows(),
            "data" => $data
        );
        $this->output->set_output(json_encode($output));
    }

    // Read
    public function dialog_salary_statutory_deductions()
    {

        $session = $this->session->userdata('username');
        if (empty($session)) {
            redirect('admin/');
        }
        $data['title'] = $this->Core_model->site_title();
        $id            = $this->input->get('field_id');
        $result        = $this->Employees_model->read_single_salary_statutory_deduction($id);
        $data          = array(
            'statutory_deductions_id' => $result[0]->statutory_deductions_id,
            'deduction_date'          => $result[0]->deduction_date,
            'employee_id'             => $result[0]->employee_id,
            'deduction_title'         => $result[0]->deduction_title,
            'deduction_amount'        => $result[0]->deduction_amount,
            'statutory_options'       => $result[0]->statutory_options
        );

        if (!empty($session)) {
            $this->load->view('admin/payroll/dialog_bulanan_detail', $data);
        } else {
            redirect('admin/');
        }
    }

    // Tambah
    public function set_statutory_deductions()
    {
        if ($this->input->post('type') == 'statutory_deductions_info') {
            /* Define return | here result is used to return user data and error for error message */
            $Return = array('result' => '', 'error' => '', 'csrf_hash' => '');
            $Return['csrf_hash'] = $this->security->get_csrf_hash();
            if ($this->input->post('title') === '') {
                $Return['error'] = $this->lang->line('xin_error_title');
            } else if ($this->input->post('amount') === '') {
                $Return['error'] = $this->lang->line('xin_error_amount_field');
            } else if ($this->input->post('deduction_date') === '') {
                $Return['error'] = 'Tanggal BPJS Belum Diinput';
            }

            if ($Return['error'] != '') {
                $this->output($Return);
            }
            $data = array(
                'deduction_date' => $this->input->post('deduction_date'),
                'deduction_title' => $this->input->post('title'),
                'deduction_amount' => $this->input->post('amount'),
                'statutory_options' => $this->input->post('statutory_options'),
                'employee_id' => $this->input->post('user_id')
            );
            $result = $this->Employees_model->add_salary_statutory_deductions($data);
            if ($result == TRUE) {
                $Return['result'] = 'BPJS Berhasil Ditambahkan';
            } else {
                $Return['error'] = $this->lang->line('xin_error_msg');
            }
            $this->output($Return);
            exit;
        }
    }

    // Edit
    public function update_statutory_deductions_info()
    {

        if ($this->input->post('type') == 'e_salary_statutory_deductions_info') {

            /* Define return | here result is used to return user data and error for error message */
            $Return = array('result' => '', 'error' => '', 'csrf_hash' => '');
            $Return['csrf_hash'] = $this->security->get_csrf_hash();

            /* Server side PHP input validation */
            if ($this->input->post('title') === '') {
                $Return['error'] = $this->lang->line('xin_error_title');
            } else if ($this->input->post('amount') === '') {
                $Return['error'] = $this->lang->line('xin_error_amount_field');
            } else if ($this->input->post('deduction_date') === '') {
                $Return['error'] = 'Tanggal BPJS Belum Diinput';
            }

            if ($Return['error'] != '') {
                $this->output($Return);
            }

            $data = array(
                'deduction_date' => $this->input->post('deduction_date'),
                'deduction_title' => $this->input->post('title'),
                'deduction_amount' => $this->input->post('amount'),
                'statutory_options' => $this->input->post('statutory_options')
            );
            $e_field_id = $this->input->post('e_field_id');
            $result = $this->Employees_model->salary_statutory_deduction_update_record($data, $e_field_id);
            if ($result == TRUE) {
                $Return['result'] = 'BPJS Berhasil Diperbarui';
            } else {
                $Return['error'] = $this->lang->line('xin_error_msg');
            }
            $this->output($Return);
            exit;
        }
    }

    // Hapus
    public function delete_all_statutory_deductions()
    {

        if ($this->input->post('data') == 'delete_record') {
            /* Define return | here result is used to return user data and error for error message */
            $Return = array('result' => '', 'error' => '', 'csrf_hash' => '');
            $Return['csrf_hash'] = $this->security->get_csrf_hash();
            $id = $this->uri->segment(4);
            $result = $this->Employees_model->delete_statutory_deductions_record($id);
            if (isset($id)) {
                $Return['result'] = 'BPJS Berhasil Dihapus';
            } else {
                $Return['error'] = $this->lang->line('xin_error_msg');
            }
            $this->output($Return);
        }
    }

    /// ======================================================================================
    //  06. PPh 21
    //  ======================================================================================

    public function salary_all_other_payments()
    {
        //set data
        $data['title'] = $this->Core_model->site_title();
        $session = $this->session->userdata('username');
        if (!empty($session)) {
            $this->load->view("admin/payroll/bulanan_detail", $data);
        } else {
            redirect('admin/');
        }
        // Datatables Variables
        $draw = intval($this->input->get("draw"));
        $start = intval($this->input->get("start"));
        $length = intval($this->input->get("length"));

        $id = $this->uri->segment(4);
        $other_payment = $this->Employees_model->set_employee_other_payments($id);

        $data = array();

        foreach ($other_payment->result() as $r) {

            $data[] = array(
                '<span data-toggle="tooltip" data-placement="top" title="' . $this->lang->line('xin_edit') . '"><button type="button" class="btn icon-btn btn-xs btn-default waves-effect waves-light" data-toggle="modal" data-target=".edit-modal-data" data-field_id="' . $r->other_payments_id . '" data-field_type="salary_other_payments"><span class="fa fa-pencil"></span></button></span><span data-toggle="tooltip" data-placement="top" title="' . $this->lang->line('xin_delete') . '"><button type="button" class="btn icon-btn btn-xs btn-danger waves-effect waves-light delete" data-toggle="modal" data-target=".delete-modal" data-record-id="' . $r->other_payments_id . '" data-token_type="all_other_payments"><span class="fa fa-trash"></span></button></span>',
                date("d-m-Y", strtotime($r->payments_date)),
                $r->payments_title,
                $this->Core_model->currency_sign($r->payments_amount)
            );
        }

        $output = array(
            "draw" => $draw,
            "recordsTotal" => $other_payment->num_rows(),
            "recordsFiltered" => $other_payment->num_rows(),
            "data" => $data
        );
        $this->output->set_output(json_encode($output));
    }

    // Read
    public function dialog_salary_other_payments()
    {

        $session = $this->session->userdata('username');
        if (empty($session)) {
            redirect('admin/');
        }
        $data['title'] = $this->Core_model->site_title();
        $id            = $this->input->get('field_id');
        $result        = $this->Employees_model->read_single_salary_other_payment($id);
        $data          = array(
            'other_payments_id' => $result[0]->other_payments_id,
            'employee_id'       => $result[0]->employee_id,
            'payments_date'     => $result[0]->payments_date,
            'payments_title'    => $result[0]->payments_title,
            'payments_amount'   => $result[0]->payments_amount
        );

        if (!empty($session)) {
            $this->load->view('admin/payroll/dialog_bulanan_detail', $data);
        } else {
            redirect('admin/');
        }
    }

    // Tambah
    public function set_other_payments()
    {

        if ($this->input->post('type') == 'other_payments_info') {
            /* Define return | here result is used to return user data and error for error message */
            $Return = array('result' => '', 'error' => '', 'csrf_hash' => '');
            $Return['csrf_hash'] = $this->security->get_csrf_hash();
            if ($this->input->post('title') === '') {
                $Return['error'] = $this->lang->line('xin_error_title');
            } else if ($this->input->post('amount') === '') {
                $Return['error'] = $this->lang->line('xin_error_amount_field');
            } else if ($this->input->post('payments_date') === '') {
                $Return['error'] = 'Tanggal Pajak Belum Diisi';
            }

            if ($Return['error'] != '') {
                $this->output($Return);
            }
            $data = array(
                'payments_date'   => $this->input->post('payments_date'),
                'payments_title'  => $this->input->post('title'),
                'payments_amount' => $this->input->post('amount'),
                'employee_id'     => $this->input->post('user_id')
            );
            $result = $this->Employees_model->add_salary_other_payments($data);
            if ($result == TRUE) {
                $Return['result'] = 'Pajak Berhasil Ditambahkan';
            } else {
                $Return['error'] = $this->lang->line('xin_error_msg');
            }
            $this->output($Return);
            exit;
        }
    }

    // Edit
    public function update_other_payment_info()
    {

        if ($this->input->post('type') == 'e_salary_other_payments_info') {
            /* Define return | here result is used to return user data and error for error message */
            $Return = array('result' => '', 'error' => '', 'csrf_hash' => '');
            $Return['csrf_hash'] = $this->security->get_csrf_hash();

            /* Server side PHP input validation */
            if ($this->input->post('title') === '') {
                $Return['error'] = $this->lang->line('xin_error_title');
            } else if ($this->input->post('amount') === '') {
                $Return['error'] = $this->lang->line('xin_error_amount_field');
            } else if ($this->input->post('payments_date') === '') {
                $Return['error'] = 'Tanggal Pajak Belum Diisi';
            }

            if ($Return['error'] != '') {
                $this->output($Return);
            }

            $data = array(
                'payments_date'   => $this->input->post('payments_date'),
                'payments_title'  => $this->input->post('title'),
                'payments_amount' => $this->input->post('amount')
            );
            $e_field_id = $this->input->post('e_field_id');
            $result = $this->Employees_model->salary_other_payment_update_record($data, $e_field_id);
            if ($result == TRUE) {
                $Return['result'] = 'Pajak Berhasil Diperbarui';
            } else {
                $Return['error'] = $this->lang->line('xin_error_msg');
            }
            $this->output($Return);
            exit;
        }
    }

    // Hapus
    public function delete_all_other_payments()
    {

        if ($this->input->post('data') == 'delete_record') {
            /* Define return | here result is used to return user data and error for error message */
            $Return = array('result' => '', 'error' => '', 'csrf_hash' => '');
            $Return['csrf_hash'] = $this->security->get_csrf_hash();
            $id = $this->uri->segment(4);
            $result = $this->Employees_model->delete_other_payments_record($id);
            if (isset($id)) {
                $Return['result'] = 'Pajak Berhasil Diperbarui';
            } else {
                $Return['error'] = $this->lang->line('xin_error_msg');
            }
            $this->output($Return);
        }
    }

    // =======================================================================================
    // 07. POTONG LAINNYA
    // =======================================================================================

    public function salary_all_minus_bulanan()
    {
        //set data
        $data['title'] = $this->Core_model->site_title();
        $session = $this->session->userdata('username');
        if (!empty($session)) {
            $this->load->view("admin/payroll/bulanan_detail", $data);
        } else {
            redirect('admin/');
        }
        // Datatables Variables
        $draw   = intval($this->input->get("draw"));
        $start  = intval($this->input->get("start"));
        $length = intval($this->input->get("length"));

        $id = $this->uri->segment(4);
        $minus = $this->Employees_model->set_employee_minus($id);

        $data = array();

        foreach ($minus->result() as $r) {

            $data[] = array(
                '
                            <span data-toggle="tooltip" data-placement="top" title="' . $this->lang->line('xin_delete') . '">
                                <button type="button" class="btn icon-btn btn-xs btn-danger waves-effect waves-light delete" data-toggle="modal" data-target=".delete-modal" data-record-id="' . $r->salary_minus_id . '" data-token_type="all_minus_bulanan">
                                    <span class="fa fa-trash"></span> Hapus
                                </button>
                            </span>',

                date("d-m-Y", strtotime($r->minus_date)),
                $r->minus_title,
                $this->Core_model->currency_sign($r->minus_amount)
            );
        }

        $output = array(
            "draw" => $draw,
            "recordsTotal" => $minus->num_rows(),
            "recordsFiltered" => $minus->num_rows(),
            "data" => $data
        );
        $this->output->set_output(json_encode($output));
    }

    // Read
    public function dialog_salary_minus_bulanan()
    {
        $session = $this->session->userdata('username');
        if (empty($session)) {
            redirect('admin/');
        }
        $data['title'] = $this->Core_model->site_title();
        $id            = $this->input->get('field_id');
        $result        = $this->Employees_model->read_single_salary_minus($id);
        $data = array(
            'salary_minus_id'  => $result[0]->salary_minus_id,
            'employee_id'      => $result[0]->employee_id,
            'minus_title'      => $result[0]->minus_title,
            'minus_date'       => $result[0]->minus_date,
            'minus_amount'     => $result[0]->minus_amount
        );

        if (!empty($session)) {
            $this->load->view('admin/payroll/dialog_bulanan_detail', $data);
        } else {
            redirect('admin/');
        }
    }

    // Tambah
    public function employee_minus_option_bulanan()
    {
        if ($this->input->post('type') == 'employee_update_minus') {
            /* Define return | here result is used to return user data and error for error message */
            $Return = array('result' => '', 'error' => '', 'csrf_hash' => '');
            $Return['csrf_hash'] = $this->security->get_csrf_hash();

            if ($this->input->post('title') === '') {
                $Return['error'] = $this->lang->line('xin_error_title');
            } else if ($this->input->post('amount') === '') {
                $Return['error'] = $this->lang->line('xin_error_amount_field');
            }

            if ($Return['error'] != '') {
                $this->output($Return);
            }
            $data = array(
                'minus_date'   => $this->input->post('minus_date'),
                'minus_title'  => $this->input->post('minus_title'),
                'minus_amount' => $this->input->post('minus_amount'),
                'employee_id'  => $this->input->post('user_id')
            );
            $result = $this->Employees_model->add_salary_minus($data);
            if ($result == TRUE) {
                $Return['result'] = 'Pemotong Gaji Bulanan Berhasil Ditambahkan';
            } else {
                $Return['error'] = $this->lang->line('xin_error_msg');
            }
            $this->output($Return);
            exit;
        }
    }

    // Edit
    public function update_minus_info_bulanan()
    {

        if ($this->input->post('type') == 'e_salary_minus_info') {
            /* Define return | here result is used to return user data and error for error message */
            $Return = array('result' => '', 'error' => '', 'csrf_hash' => '');
            $Return['csrf_hash'] = $this->security->get_csrf_hash();

            /* Server side PHP input validation */
            if ($this->input->post('title') === '') {
                $Return['error'] = $this->lang->line('xin_error_title');
            } else if ($this->input->post('amount') === '') {
                $Return['error'] = $this->lang->line('xin_error_amount_field');
            }

            if ($Return['error'] != '') {
                $this->output($Return);
            }

            $data = array(
                'minus_date'   => $this->input->post('minus_date'),
                'minus_title'  => $this->input->post('minus_title'),
                'minus_amount' => $this->input->post('minus_amount')
            );
            $e_field_id = $this->input->post('e_field_id');
            $result     = $this->Employees_model->salary_minus_update_record($data, $e_field_id);
            if ($result == TRUE) {
                $Return['result'] = 'Pemotong Gaji Bulanan Berhasil Diperbarui';
            } else {
                $Return['error'] = $this->lang->line('xin_error_msg');
            }
            $this->output($Return);
            exit;
        }
    }

    // Hapus
    public function delete_all_minus_bulanan()
    {
        if ($this->input->post('data') == 'delete_record') {
            /* Define return | here result is used to return user data and error for error message */
            $Return = array('result' => '', 'error' => '', 'csrf_hash' => '');
            $Return['csrf_hash'] = $this->security->get_csrf_hash();
            $id = $this->uri->segment(4);
            $result = $this->Employees_model->delete_minus_record($id);
            if (isset($id)) {
                $Return['result'] = 'Pemotong Gaji Bulanan Berhasil Dihapus';
            } else {
                $Return['error'] = $this->lang->line('xin_error_msg');
            }
            $this->output($Return);
        }
    }

    // ===============================================================================================
    // 02. PROSES => GAJI HARIAN
    // ===============================================================================================

    // =======================================================================================
    // TABEL : GAJI HARIAN
    // =======================================================================================

    // generate payslips
    public function harian()
    {
        $session = $this->session->userdata('username');
        if (empty($session)) {
            redirect('admin/');
        }
        $data['title']       = 'Proses Gaji Harian | ' . $this->Core_model->site_title();
        $data['icon']        = '<i class="fa fa-money"></i>';
        $data['desc']        = 'PROSES : Gaji Harian ';
        $data['breadcrumbs'] = 'Proses Gaji Harian ';
        $data['path_url']    = 'harian';

        $data['all_companies']  = $this->Company_model->get_company();
        $data['all_location']  = $this->Location_model->get_locations();
        // var_dump($data['all_companies']);return;
        $data['all_bulan_gaji'] = $this->Core_model->all_bulan_status_payroll();

        $role_resources_ids = $this->Core_model->user_role_resource();
        if (in_array('1021', $role_resources_ids)) {
            if (!empty($session)) {
                $data['subview'] = $this->load->view("admin/payroll/harian", $data, TRUE);
                $this->load->view('admin/layout/layout_main', $data); //page load
            } else {
                redirect('admin/');
            }
        } else {
            redirect('admin/dashboard');
        }
    }

    public function payslip_list_harian()
    {

        $data['title'] = $this->Core_model->site_title();
        $session = $this->session->userdata('username');

        if (!empty($session)) {
            $this->load->view("admin/payroll/harian", $data);
        } else {
            redirect('admin/');
        }
        // Datatables Variables
        $draw   = intval($this->input->get("draw"));
        $start  = intval($this->input->get("start"));
        $length = intval($this->input->get("length"));

        // date and employee id/company id
        $start_date = $this->input->get("start_date");
        $end_date   = $this->input->get("end_date");

        $role_resources_ids = $this->Core_model->user_role_resource();

        // $payslip = $this->Payroll_model->get_comp_template_harian($this->input->get("company_id"));
        $payslip = $this->Payroll_model->get_comp_template_harian_new($this->input->get("company_id"),$this->input->get("location_id"));
        $result  = $payslip->result();

        $data   = array();
        $no     = 1;

        $user_ids = get_values($result, 0, 'user_id');
        $employee_grade_ids = get_values($result, 0, 'grade_type');
        $employee_department_ids = get_values($result, 0, 'department_id');
        $employee_designation_ids = get_values($result, 0, 'designation_id');

        $get_all_grades = $this->Core_model->read_user_jenis_grade(array_unique($employee_grade_ids));
        $all_grades = get_values($get_all_grades, 'jenis_grade_keterangan');

        $get_all_departments = $this->Department_model->read_department_information(array_unique($employee_department_ids));
        $all_departments = get_values($get_all_departments, 'department_id');

        $get_all_designations = $this->Designation_model->read_designation_information(array_unique($employee_designation_ids));
        $all_designations = get_values($get_all_designations, 'designation_id');

        $get_all_emp_status = $this->Employees_model->read_employee_contract_information2($user_ids);
        $all_emp_status = get_values($get_all_emp_status, 'employee_id');

        $get_all_emp_salary = $this->Employees_model->read_all_payroll_salary_gapok($user_ids, $end_date);
        $all_emp_salary = get_values($get_all_emp_salary, 'employe_id');

        $get_all_overtime = $this->Employees_model->sum_payroll_salary_overtime($user_ids, $start_date, $end_date);
        $all_overtime = get_values($get_all_overtime, 'employee_id');

        $get_all_commission = $this->Employees_model->sum_all_payroll_salary_commissions($user_ids, $start_date, $end_date);
        $all_commission = get_values($get_all_commission, 'employee_id');

        $get_all_bpjs = $this->Employees_model->sum_all_employee_bpjs($user_ids,$start_date, $end_date);
        $all_bpjs = get_values($get_all_bpjs, 'employee_id');

        $get_all_salary_minus = $this->Employees_model->sum_payroll_salary_minus($user_ids, $start_date, $end_date);
        $all_salary_minus = get_values($get_all_salary_minus, 'employee_id');

        // count attendance all employee
        $symbol = array('H', 'O');
        $get_all_total_attendance = $this->Timesheet_model->hitung_multi_jumlah_status_kehadiran($user_ids, $start_date, $end_date, $symbol);
        $all_total_attendance = array();
        foreach ($get_all_total_attendance as $gata) {
            if (!isset($all_total_attendance[$gata->employee_id])) {
                $all_total_attendance[$gata->employee_id] = array();
            }

            $all_total_attendance[$gata->employee_id][$gata->attendance_status_simbol] = $gata->jumlah;
        }

        $get_overtime_ooo = $this->Timesheet_model->hitung_multi_jumlah_lembur_libur($user_ids, $start_date, $end_date);
        $all_total_ooo = array();
        foreach ($get_overtime_ooo as $ooo) {
            $all_total_ooo[$ooo->employee_id] = $ooo->jumlah;
        }

        $get_all_permit = $this->Core_model->read_user_izin_jumlah($user_ids, $start_date, $end_date);
        $all_permit = get_values($get_all_permit, 'employee_id');

        $get_all_payment = $this->Payroll_model->read_make_payment_payslip_check_harian($user_ids, $start_date, $end_date);
        $all_payment = get_values($get_all_payment, 'employee_id');

        foreach ($result as $index => $r) {
            // ====================================================================================================================
            // DATA KARYAWAN
            // ====================================================================================================================

            // Karyawan NIP
            $emp_id = $r->employee_id;

            // grade
            $employee_user_id           = $r->user_id;
            $employee_grade_type        = $r->grade_type;
            $employee_wages_type        = $r->wages_type;
            $employee_name              = $r->first_name . ' ' . $r->last_name;
            $employee_department_id     = $r->department_id;
            $employee_designation_id    = $r->designation_id;
            $employee_emp_status        = $r->emp_status;
            $employee_date_of_joining   = $r->date_of_joining;
            $employee_basic_salary      = $r->basic_salary;
            $employee_flag              = $r->flag;
            $employee_email             = $r->email;

            // grade
            if (isset($all_grades[$employee_grade_type])) {
                $jenis_grade       = $all_grades[$employee_grade_type]->jenis_grade_keterangan;
                $jenis_grade_warna = $all_grades[$employee_grade_type]->warna;
            } else {
                $jenis_grade = '<span class="badge bg-red"> ? </span>';
                $jenis_grade_warna = '';
            }

            // Karyawan Nama
            $emp_name = $employee_name;

            // Karyawan Departemen
            if (isset($all_departments[$employee_department_id])) {
                $department_name = $all_departments[$employee_department_id]->department_name;
            } else {
                $department_name = '<span class="badge bg-red"> ? </span>';
            }

            // Karyawan Posisi
            if (isset($all_designations[$employee_designation_id])) {
                $designation_name = $all_designations[$employee_designation_id]->designation_name;
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
            if (isset($all_emp_status[$employee_user_id])) {
                $emp_status_name = $all_emp_status[$employee_user_id]->name_type;
            } else {
                $emp_status_name = '<span class="badge bg-red"> ? </span>';
            }

            // Karyawan Masa kerja
            date_default_timezone_set("Asia/Jakarta");

            $tanggal1 = new DateTime($employee_date_of_joining);
            $tanggal2 = new DateTime($end_date);

            $datetime1 = new DateTime($employee_date_of_joining);
            $datetime2 = new DateTime($end_date);
            $difference = $datetime1->diff($datetime2);

            if ($tanggal2->diff($tanggal1)->y == 0) {
                $selisih_tgl     = $tanggal2->diff($tanggal1)->m . ' bln ' . $tanggal2->diff($tanggal1)->d . ' hari';
                $selisih_tanggal = $tanggal2->diff($tanggal1)->d;
                $selisih         = $tanggal2->diff($tanggal1)->m . ' bln';
            } else {
                $selisih_tgl     =  $tanggal2->diff($tanggal1)->m . ' bln ' . $tanggal2->diff($tanggal1)->d . ' hari';
                $selisih_tanggal = $tanggal2->diff($tanggal1)->d;
                $selisih         = $tanggal2->diff($tanggal1)->y . ' thn' . ' ' . $tanggal2->diff($tanggal1)->m . ' bln';
            }

            // ====================================================================================================================
            // KOMPONEN GAJI - TAMBAH
            // ====================================================================================================================
            // ****************************************************************************************************************
            // TETAP
            // ****************************************************************************************************************
            // $gapok       = $this->Employees_model->read_payroll_salary_gapok($employee_user_id, $end_date);
            // $count_gapok = $this->Employees_model->count_employee_gapok($employee_user_id, $end_date);
            // $gapok_amount = 0;
            // if ($count_gapok > 0) {
            //     foreach ($gapok as $sl_salary_gapok) {
            //         $gapok_amount += $sl_salary_gapok->gapok_amount;
            //     }
            // } else {
            //     $gapok_amount = 0;
            // }

            $gapok_amount = 0;
            if (isset($all_emp_salary[$employee_user_id])) {
                $gapok_amount = $all_emp_salary[$employee_user_id]->gapok_amount;
            }

            // ============================================================================================================
            // 1: salary type
            // ============================================================================================================

            $jumlah_upah = 0;
            $basic_salary = $gapok_amount;
            if ($difference->days > 31 && $basic_salary == 50000) {
                $get_attendance = $this->db->select("attendance_date")
                    ->where_in('attendance_status_simbol', ['H', 'O'])
                    ->where('employee_id', $r->user_id)
                    ->where('attendance_date >=', $start_date)
                    ->where('attendance_date <=', $end_date)
                    ->get('xin_attendance_time')
                    ->result();

                foreach ($get_attendance as $ga) {
                    $at = new DateTime($ga->attendance_date);
                    $jumlah_upah += $tanggal1->diff($at)->days > 31 ? 65000 : $basic_salary;
                }

                $basic_salary = 65000;
            }

            // ****************************************************************************************************************
            // TIDAK TETAP
            // ****************************************************************************************************************

            // ============================================================================================================
            // 1: Lembur
            // ============================================================================================================

            // $salary_overtime = $this->Employees_model->read_payroll_salary_overtime($employee_user_id, $start_date, $end_date);
            // $count_overtime  = $this->Employees_model->count_payroll_employee_overtime($employee_user_id, $start_date, $end_date);
            // $overtime_amount = 0;
            // if ($count_overtime > 0) {
            //     foreach ($salary_overtime as $sl_overtime) {
            //         $overtime_amount += $sl_overtime->overtime_total;
            //     }
            // } else {
            //     $overtime_amount = 0;
            // }

            // $salary_overtime_jam = $this->Employees_model->read_payroll_salary_overtime($employee_user_id, $start_date, $end_date);
            // $count_overtime_jam  = $this->Employees_model->count_payroll_employee_overtime($employee_user_id, $start_date, $end_date);
            // $overtime_amount_jam = 0;
            // if ($count_overtime_jam > 0) {
            //     foreach ($salary_overtime_jam as $sl_overtime_jam) {

            //         $overtime_amount_jam += $sl_overtime_jam->overtime_hours_total;
            //     }
            // } else {

            //     $overtime_amount_jam = 0;
            // }

            $overtime_amount = isset($all_overtime[$employee_user_id]) ? $all_overtime[$employee_user_id]->total_overtime : 0;
            $overtime_amount_jam = isset($all_overtime[$employee_user_id]) ? $all_overtime[$employee_user_id]->total_overtime_hour : 0;

            // ============================================================================================================
            // 1: Insentif
            // ============================================================================================================

            // $commissions       = $this->Employees_model->read_payroll_salary_commissions($employee_user_id, $start_date, $end_date);
            // $count_commissions = $this->Employees_model->count_employee_commissions($employee_user_id, $start_date, $end_date);
            // $commissions_amount = 0;
            // if ($count_commissions > 0) {
            //     foreach ($commissions as $sl_salary_commissions) {
            //         $commissions_amount += $sl_salary_commissions->commission_amount;
            //     }
            // } else {
            //     $commissions_amount = 0;
            // }

            $commissions_amount = isset($all_commission[$employee_user_id]) ? $all_commission[$employee_user_id]->total_amount : 0;


            // ====================================================================================================================
            // KOMPONEN GAJI - KURANG
            // ====================================================================================================================

            // ****************************************************************************************************************
            // TETAP
            // ****************************************************************************************************************

            // ============================================================================================================
            // 1: BPJS TK
            // ============================================================================================================

            // $count_bpjs_tk = $this->Employees_model->count_employee_bpjs_tk($employee_user_id,$start_date, $end_date);
            // $bpjs_tk = $this->Employees_model->set_employee_bpjs_tk($employee_user_id,$start_date, $end_date);
            // $bpjs_tk_amount = 0;
            // if ($count_bpjs_tk > 0) {
            //     foreach ($bpjs_tk->result() as $sl_salary_bpjs_tk) {
            //         $bpjs_tk_amount += $sl_salary_bpjs_tk->deduction_amount;
            //     }
            // } else {
            //     $bpjs_tk_amount = 0;
            // }

            // ============================================================================================================
            // 2: BPJS KES
            // ============================================================================================================

            // $count_bpjs_kes = $this->Employees_model->count_employee_bpjs_kes($employee_user_id,$start_date, $end_date);
            // $bpjs_kes = $this->Employees_model->set_employee_bpjs_kes($employee_user_id,$start_date, $end_date);
            // $bpjs_kes_amount = 0;
            // if ($count_bpjs_kes > 0) {
            //     foreach ($bpjs_kes->result() as $sl_salary_bpjs_kes) {
            //         $bpjs_kes_amount += $sl_salary_bpjs_kes->deduction_amount;
            //     }
            // } else {
            //     $bpjs_kes_amount = 0;
            // }

            $bpjs_tk_amount = isset($all_bpjs[$employee_user_id]) ? $all_bpjs[$employee_user_id]->total_bpjstk : 0;
            $bpjs_kes_amount = isset($all_bpjs[$employee_user_id]) ? $all_bpjs[$employee_user_id]->total_bpjskes : 0;

            // ****************************************************************************************************************
            // TIDAK TETAP
            // ****************************************************************************************************************

            // ============================================================================================================
            // 1: Minus
            // ============================================================================================================

            // $minus       = $this->Employees_model->read_payroll_salary_minus($employee_user_id, $start_date, $end_date);
            // $count_minus = $this->Employees_model->count_employee_minus($employee_user_id, $start_date, $end_date);
            // $minus_amount = 0;
            // if ($count_minus > 0) {
            //     foreach ($minus as $sl_salary_minus) {
            //         $minus_amount += $sl_salary_minus->minus_amount;
            //     }
            // } else {
            //     $minus_amount = 0;
            // }

            $minus_amount = isset($all_salary_minus[$employee_user_id]) ? $all_salary_minus[$employee_user_id]->total_minus : 0;

            // ============================================================================================================
            // 2: Absen
            // ============================================================================================================

            // $cek_hadir      = $this->Timesheet_model->hitung_jumlah_status_kehadiran($employee_user_id, $start_date, $end_date, 'H');

            // $cek_lembur     = $this->Timesheet_model->hitung_jumlah_status_kehadiran($employee_user_id, $start_date, $end_date, 'O');

            // $cek_lembur_libur   = $this->Timesheet_model->hitung_jumlah_status_kehadiran_libur($employee_user_id, $start_date, $end_date, 'O');

            // $cek_izin       = $this->Core_model->read_user_izin_jumlah($employee_user_id, $start_date, $end_date);

            // user full name
            // if (!is_null($cek_izin)) {
            //     $cek_jum_izin   = $cek_izin[0]->jumlah;
            // } else {
            //     $cek_jum_izin   = '';
            // }

            // if ($cek_jum_izin == '' || $cek_jum_izin == 0) {
            //     $jum_izin   = 0;
            // } else {
            //     $jum_izin   = $cek_jum_izin;
            // }

            // $info_jum_hadir  = $cek_hadir[0]->jumlah;
            // $info_jum_lembur = $cek_lembur[0]->jumlah;

            // Lembur di hari libut
            $ooo = isset($all_total_ooo[$employee_user_id]) ? $all_total_ooo[$employee_user_id] : 0;

            $attendance = isset($all_total_attendance[$employee_user_id]) ? $all_total_attendance[$employee_user_id] : [];
            $info_jum_hadir  = isset($attendance['H']) ? $attendance['H'] : 0;
            $info_jum_lembur = isset($attendance['O']) ? $attendance['O'] : 0;

            // $info_jum_izin   = $jum_izin;
            $info_jum_izin   = isset($all_permit[$employee_user_id]) ? $all_permit[$employee_user_id]->jumlah : 0;
            // $info_jum_libur  = $cek_lembur_libur[0]->jumlah;

            $info_jum_masuk  = $info_jum_hadir + $info_jum_lembur - $ooo;

            $jumlah_hadir    = $info_jum_hadir + $info_jum_lembur - $ooo;

            $jumlah_upah    = $jumlah_upah == 0 ? $basic_salary * $jumlah_hadir : $jumlah_upah;

            // ====================================================================================================================
            // HITUNG
            // ====================================================================================================================

            $tanggal_awal       = date("Y-m-d", strtotime($start_date));
            $tanggal_akhir      = date("Y-m-d", strtotime($end_date));
            $tanggal_potong     = date("Y-m-20", strtotime($start_date));

            if ($tanggal_potong >= $tanggal_awal and $tanggal_potong <= $tanggal_akhir) {

                $pot_bpjs_kes_amount = $bpjs_kes_amount;

                $tes = 'Potong';
            } else {

                $pot_bpjs_kes_amount = 0;

                $tes = 'Tidak Potong';
            }

            if ($bpjs_kes_amount == 0) {
                $ada_bpjs_kes = '<span class="merah">(BPJS Kes Belum Diinput)</span>';
            } else {
                $ada_bpjs_kes = '';
            }

            $info_potong       = $tanggal_awal . ' s/d ' . $tanggal_akhir . ' => ' . $tanggal_potong . ' => ' . $tes . ' BPJS Kesehatan ' . $ada_bpjs_kes;

            $total_gaji        = $jumlah_upah;

            $jumlah_jam_lembur = $overtime_amount_jam;
            $total_lembur      = $overtime_amount;

            $total_net_salary = ($total_gaji + $total_lembur + $commissions_amount) - ($minus_amount + $bpjs_tk_amount + $pot_bpjs_kes_amount);

            // ====================================================================================================================
            // PERIKSA PEMBAYARAN
            // ====================================================================================================================

            // $payment_check = $this->Payroll_model->read_make_payment_payslip_check_harian($employee_user_id, $start_date, $end_date);

            // echo "<pre>";
            // 	print_r( $this->db->last_query() );
            // 	echo "</pre>";
            // 	die();
            if (isset($all_payment[$employee_user_id])) {

                // $make_payment = $this->Payroll_model->read_make_payment_payslip_harian($employee_user_id, $start_date, $end_date);
                $make_payment = $all_payment[$employee_user_id];

                // echo "<pre>";
                // print_r( $this->db->last_query() );
                // echo "</pre>";
                // die();

                $view_url     = site_url() . 'admin/payroll/payslip_harian/id/' . $make_payment->payslip_key;

                $status       = '<span class="label label-success">' . $this->lang->line('xin_payroll_paid') . '</span>';

                if (in_array('1024', $role_resources_ids)) {

                    $mpay = '<span data-toggle="tooltip" data-placement="top" title="' . $this->lang->line('xin_payroll_view_payslip') . '">
                            <a target ="_blank" href="' . $view_url . '">
                                <button type="button" class="btn icon-btn btn-xs btn-warning waves-effect waves-light">
                                    <span class="fa fa-money"></span>
                                </button>
                            </a>
                        </span>';
                } else {
                    $mpay = '';
                }

                if (in_array('1025', $role_resources_ids)) {
                    $dpay  = '<span data-toggle="tooltip" data-placement="top" title="' . $this->lang->line('xin_download') . '">
                            <a href="' . site_url() . 'admin/payroll/pdf_create_harian/p/' . $make_payment->payslip_key . '">
                                <button type="button" class="btn icon-btn btn-xs btn-success waves-effect waves-light">
                                    <span class="fa fa-download"></span>
                                </button>
                            </a>
                        </span>';
                } else {
                    $dpay = '';
                }

                if (in_array('10231', $role_resources_ids)) {
                    // $delete = '<span data-toggle="tooltip" data-placement="top" title="' . $this->lang->line('xin_delete') . '">
                    //         <button type="button" class="btn icon-btn btn-xs btn-danger waves-effect waves-light" data-toggle="modal" data-target=".del_dayly_pay" data-payslip_id="' .  $make_payment->payslip_id . '" data-start_date="' . $start_date . '" data-end_date="' . $end_date . '" data-company_id="' . $this->input->get("company_id") . '">
                    //             <span class="fa fa-trash"></span>
                    //         </button>
                    //     </span>';
                    $delete = '<span data-toggle="tooltip" data-placement="top" title="' . $this->lang->line('xin_delete') . '">
                            <button type="button" class="btn icon-btn btn-xs btn-danger waves-effect waves-light" data-toggle="modal" data-target=".del_dayly_pay" data-payslip_id="' .  $make_payment->payslip_id . '" data-start_date="' . $start_date . '" data-end_date="' . $end_date . '" data-company_id="' . $this->input->get("company_id") . '" data-location_id="' . $this->input->get("location_id") . '">
                                <span class="fa fa-trash"></span>
                            </button>
                        </span>';
                } else {
                    $delete = '';
                }

                if (in_array('1023', $role_resources_ids)) {
                    $edit_opt         = '<button style ="background-color: #ccc;border-color: #eee;" disabled type="button" class="btn icon-btn btn-xs btn-success waves-effect waves-light" title="Gaji Belum Diproses">
                            <span class="fa fa-pencil"></span>
                        </button> ';
                } else {
                    $edit_opt = '';
                }

                if (in_array('1026', $role_resources_ids)) {
                    $bpay = '<button style ="background-color: #ccc;border-color: #eee;" disabled type="button" class="btn icon-btn btn-xs btn-warning " title="Gaji Belum Diproses">
                            <span class="fa fa-save"></span>
                        </button>';
                } else {
                    $bpay = '';
                }

            } else {
                $status = '<span class="label label-danger">' . $this->lang->line('xin_payroll_unpaid') . '</span>';

                if (in_array('1024', $role_resources_ids)) {

                    $mpay         = '<button style ="background-color: #ccc;border-color: #eee;" disabled type="button" class="btn icon-btn btn-xs btn-warning " title="Gaji Belum Diproses">
                            <span class="fa fa-money"></span>
                        </button> ';
                } else {
                    $mpay = '';
                }

                if (in_array('1025', $role_resources_ids)) {
                    $dpay         = '<button style ="background-color: #ccc;border-color: #eee;" disabled type="button" class="btn icon-btn btn-xs btn-success waves-effect waves-light" title="Gaji Belum Diproses">
                            <span class="fa fa-download"></span>
                        </button> ';
                } else {
                    $dpay = '';
                }

                if (in_array('10231', $role_resources_ids)) {
                    $delete = '<button style ="background-color: #ccc;border-color: #eee;" disabled type="button" class="btn icon-btn btn-xs btn-danger waves-effect waves-light" title="Hapus Belum Diproses">
                            <span class="fa fa-trash"></span>
                        </button>';
                } else {
                    $delete = '';
                }

                if (in_array('1023', $role_resources_ids)) {
                    $edit_opt = ' <span data-toggle="tooltip" data-placement="top" title="' . $this->lang->line('xin_edit_komponen_gaji') . '">
                            <a target="_blank" href="' . site_url() . 'admin/payroll/harian_detail/' . $employee_user_id . '/' . $start_date . '/' . $end_date . '">
                                <button type="button" class="btn icon-btn btn-xs btn-info waves-effect waves-light">
                                    <span class="fa fa-pencil"></span>
                                </button>
                            </a>
                        </span>';
                } else {
                    $edit_opt = '';
                }

                if (in_array('1026', $role_resources_ids)) {
                    $bpay = '<span data-toggle="tooltip" data-placement="top" title="Simpan Gaji Per Karyawan">
                            <button type="button" class="btn icon-btn btn-xs btn-success waves-effect waves-light" data-toggle="modal" data-target=".emo_dayly_pay" data-employee_id="' .  $employee_user_id . '" data-start_date="' . $start_date . '" data-end_date="' . $end_date . '" data-company_id="' . $this->input->get("company_id") . '">
                                <span class="fa fa-save"></span>
                            </button>
                        </span>';
                } else {
                    $bpay = '';
                }
            }

            //detail link
            $detail = '<span data-toggle="tooltip" data-placement="top" title="' . $this->lang->line('xin_view') . '">
                    <button type="button" class="btn icon-btn btn-xs btn-warning waves-effect waves-light" data-toggle="modal" data-target=".payroll_template_modal_harian" data-employee_id="' . $employee_user_id . '">
                        <span class="fa fa-eye"></span>
                    </button>
                </span>';


            $basic_salary = $basic_salary;


            if ($basic_salary == 0 || $basic_salary == '') {
                $fmpay = '';
            } else {
                $fmpay = $mpay;
            }

            //action link
            $act = $mpay . $dpay . $delete . $edit_opt . $bpay;

            $data[] = array(
                $act,
                $no,
                $status,
                $start_date . ' s/d ' . $end_date,
                $emp_id,
                strtoupper($emp_name),
                strtoupper($department_name),
                strtoupper($designation_name),
                date("d-m-Y", strtotime($employee_date_of_joining)),
                $difference->days . ' hari => ' . $selisih_tgl,
                $karyawan_status,
                $emp_status_name,
                $jenis_grade,

                number_format((float)$basic_salary, 0, ',', '.'),
                'H=' . $info_jum_masuk . '/I=' . $info_jum_izin . '/Total=' . $jumlah_hadir,
                number_format($total_gaji, 0, ',', '.'),

                $jumlah_jam_lembur,
                number_format($total_lembur, 0, ',', '.'),

                number_format($commissions_amount, 0, ',', '.'),

                number_format($pot_bpjs_kes_amount, 0, ',', '.'),
                number_format($bpjs_tk_amount, 0, ',', '.'),

                number_format($minus_amount, 0, ',', '.'),

                number_format($total_net_salary, 0, ',', '.'),
                $rekening_name,
                $bank_name,
                $employee_email,
                $info_potong
            );
            $no++;

            if ($index == 9) {
                // break;
            }
        }

        $output = array(
            "draw" => $draw,
            "recordsTotal" => $payslip->num_rows(),
            "recordsFiltered" => $payslip->num_rows(),
            "data" => $data
        );
        $this->output->set_output(json_encode($output));
        // echo json_encode($output);
        // exit();
    }

    public function gaji_harian_jumlah()
    {
        $company_id = $this->input->get('company_id');
        $location_id = $this->input->get('location_id');
        $start_date = $this->input->get('start_date');
        $end_date = $this->input->get('end_date');


        $sql = 'SELECT *
                        FROM xin_companies WHERE company_id = "' . $company_id . '"  AND location_id = "' . $location_id . '" ';
        // echo "<pre>";
        // print_r($sql);
        // echo "</pre>";
        // die();

        $query = $this->db->query($sql);


        $response['val'] = array();
        if ($query <> false) {
            foreach ($query->result() as $val) {


                $response['val'][] = array(
                    'company_name'    => $val->name,
                    'periode_gaji'    => date("d-m-Y", strtotime($start_date)) . ' s/d ' . date("d-m-Y", strtotime($end_date))

                );
            }
            $response['status'] = '200';
        }


        echo json_encode($response);
    }

    // =======================================================================================
    // PROSES : SIMPAN
    // =======================================================================================

    public function add_pay_to_all_harian()
    {

        /* Define return | here result is used to return user data and error for error message */
        $Return = array('result' => '', 'error' => '', 'csrf_hash' => '');
        $Return['csrf_hash'] = $this->security->get_csrf_hash();

        if ($this->input->post('add_type') == 'payroll') {

            $session_id = $this->session->userdata('user_id');
            $user_create = $session_id['user_id'];

            // $system = $this->Core_model->read_setting_info(1);
            // $system_settings = system_settings_info(1);
            // if ($system_settings->online_payment_account == '') {
            //     $online_payment_account = 0;
            // } else {
            //     $online_payment_account = $system_settings->online_payment_account;
            // }

            $company_id = $this->input->post("company_id");
            $start_date = $this->input->post("start_date");
            $end_date   = $this->input->post("end_date");

            // $bulan_id   = $this->input->post("bmonth_year");

            // echo "<pre>";
            // print_r($this->db->last_query());
            // print_r( $company_id );
            // print_r( $bulan_id );
            // echo "</pre>";
            // die();

            if ($company_id != 0) {
                $eresult = $this->Payroll_model->get_company_payroll_employees_harian($company_id, $start_date, $end_date);
                $result = $eresult->result();

                // return json_response(c);
            } else {
                $Return['error'] = $this->lang->line('xin_record_not_found');
            }

            $user_ids = get_values($result, 0, 'user_id');
            $employee_designation_ids = get_values($result, 0, 'designation_id');

            $get_all_designations = $this->Designation_model->read_designation_information(array_unique($employee_designation_ids));
            $all_designations = get_values($get_all_designations, 'designation_id');

            $get_emp_bank_accounts = $this->Employees_model->get_all_employee_bank_account($user_ids);
            $all_bank_accounts = get_values($get_emp_bank_accounts, 'employee_id');

            $get_all_emp_salary = $this->Employees_model->read_all_payroll_salary_gapok($user_ids, $end_date);
            $all_emp_salary = get_values($get_all_emp_salary, 'employe_id');

            $get_all_overtime = $this->Employees_model->sum_payroll_salary_overtime($user_ids, $start_date, $end_date);
            $all_overtime = get_values($get_all_overtime, 'employee_id');

            $get_all_commission = $this->Employees_model->sum_all_payroll_salary_commissions($user_ids, $start_date, $end_date);
            $all_commission = get_values($get_all_commission, 'employee_id');

            $get_all_bpjs = $this->Employees_model->sum_all_employee_bpjs($user_ids, $end_date);
            $all_bpjs = get_values($get_all_bpjs, 'employee_id');

            $get_all_salary_minus = $this->Employees_model->sum_payroll_salary_minus($user_ids, $start_date, $end_date);
            $all_salary_minus = get_values($get_all_salary_minus, 'employee_id');

            $get_overtime_ooo = $this->Timesheet_model->hitung_multi_jumlah_lembur_libur($user_ids, $start_date, $end_date);
            $all_total_ooo = array();
            foreach ($get_overtime_ooo as $ooo) {
                $all_total_ooo[$ooo->employee_id] = $ooo->jumlah;
            }

            // count attendance all employee
            $symbol = array('H', 'O');
            $get_all_total_attendance = $this->Timesheet_model->hitung_multi_jumlah_status_kehadiran($user_ids, $start_date, $end_date, $symbol);
            $all_total_attendance = array();
            foreach ($get_all_total_attendance as $gata) {
                if (!isset($all_total_attendance[$gata->employee_id])) {
                    $all_total_attendance[$gata->employee_id] = array();
                }

                $all_total_attendance[$gata->employee_id][$gata->attendance_status_simbol] = $gata->jumlah;
            }

            $get_all_permit = $this->Core_model->read_user_izin_jumlah($user_ids, $start_date, $end_date);
            $all_permit = get_values($get_all_permit, 'employee_id');


            log_message('debug', "total data: " . count($result));
            log_message('debug', 'start payroll');

            $insert_data = [];
            foreach ($result as $index => $employee) {
                log_message('debug', "start user {$employee->user_id}");
                // ====================================================================================================================
                // DATA KARYAWAN
                // ====================================================================================================================

                $user_id = $employee->user_id;

                $employee_user_id           = $employee->user_id;
                $employee_email             = $employee->email;
                $employee_date_of_joining   = $employee->date_of_joining;
                $employee_designation_id    = $employee->designation_id;

                // Rekening
                // $rekening = $this->Employees_model->get_employee_bank_account_last($user_id);
                // if (!is_null($rekening)) {
                //     $rekening_name = $rekening[0]->account_number;
                //     $bank_name     = $rekening[0]->bank_name;
                // } else {
                //     $rekening_name = '--';
                //     $bank_name     = '--';
                // }

                $rekening_name = '--';
                $bank_name     = '--';
                if (isset($all_bank_accounts[$user_id])) {
                    $rekening_name = $all_bank_accounts[$user_id]->account_number;
                    $bank_name     = $all_bank_accounts[$user_id]->bank_name;
                }

                // ====================================================================================================================
                // JIKA ADA -> HAPUS
                // ====================================================================================================================

                // $pay_count = $this->Payroll_model->read_make_payment_payslip_check_harian_company($company_id, $start_date, $end_date);

                // if ($pay_count->num_rows() > 0) {

                //     $pay_val = $this->Payroll_model->read_make_payment_payslip_harian_company($company_id, $start_date, $end_date);

                //     $this->payslip_delete_all_harian($pay_val[0]->payslip_id);
                // }

                // ====================================================================================================================
                // (+) KOMPONEN GAJI - TAMBAH
                // ====================================================================================================================

                // ****************************************************************************************************************
                // >> TETAP
                // ****************************************************************************************************************
                // ============================================================================================================
                // 1: salary type
                // ============================================================================================================
                // $wages_type = $this->lang->line('xin_payroll_full_tTime');
                // $basic_salary = $empid->basic_salary;

                // Karyawan Masa kerja
                date_default_timezone_set("Asia/Jakarta");

                $tanggal1 = new DateTime($employee_date_of_joining);
                // $tanggal2 = new DateTime($end_date);

                $datetime1 = new DateTime($employee_date_of_joining);
                $datetime2 = new DateTime($end_date);
                $difference = $datetime1->diff($datetime2);

                // if ($tanggal2->diff($tanggal1)->y == 0) {
                //     $selisih_tanggal = $tanggal2->diff($tanggal1)->d;
                //     $selisih         = $tanggal2->diff($tanggal1)->m . ' bln';
                // } else {
                //     $selisih_tanggal = $tanggal2->diff($tanggal1)->d;
                //     $selisih         = $tanggal2->diff($tanggal1)->y . ' thn' . ' ' . $tanggal2->diff($tanggal1)->m . ' bln';
                // }

                // ****************************************************************************************************************
                // TETAP
                // ****************************************************************************************************************
                // $gapok       = $this->Employees_model->read_payroll_salary_gapok($employee_user_id, $end_date);
                // $count_gapok = $this->Employees_model->count_employee_gapok($employee_user_id, $end_date);
                // $gapok_amount = 0;
                // if ($count_gapok > 0) {
                //     foreach ($gapok as $sl_salary_gapok) {
                //         $gapok_amount += $sl_salary_gapok->gapok_amount;
                //     }
                // } else {
                //     $gapok_amount = 0;
                // }

                $gapok_amount = isset($all_emp_salary[$user_id]) ? $all_emp_salary[$user_id]->gapok_amount : 0;

                // ============================================================================================================
                // 1: salary type
                // ============================================================================================================

                $jumlah_upah = 0;
                $basic_salary = $gapok_amount;
                if ($difference->days > 31 && $basic_salary == 50000) {
                    $get_attendance = $this->db->select("attendance_date")
                        ->where_in('attendance_status_simbol', ['H', 'O'])
                        ->where('employee_id', $user_id)
                        ->where('attendance_date >=', $start_date)
                        ->where('attendance_date <=', $end_date)
                        ->get('xin_attendance_time')
                        ->result();

                    $saved = false;
                    foreach ($get_attendance as $ga) {
                        $at = new DateTime($ga->attendance_date);

                        if ($tanggal1->diff($at)->days > 31 && !$saved) {
                            $this->Employees_model->add_salary_gapok(array(
                                'employe_id'    => $user_id,
                                'gapok_date'    => $at->format('Y-m-d'),
                                'wages_type'    => 2,
                                'gapok_amount'  => 65000,
                                'gapok_title'   => 'Kenaikan Bulan Ke 2',
                                'created_by'    => $user_create,
                                'created_at'    => date('Y-m-d h:i:s'),
                            ));

                            $saved = true;
                        }

                        $jumlah_upah += $tanggal1->diff($at)->days > 31 ? 65000 : $basic_salary;
                    }

                    $basic_salary = 65000;
                }


                // ****************************************************************************************************************
                // >> TIDAK TETAP
                // ****************************************************************************************************************
                // ============================================================================================================
                // 1: Insentif
                // ============================================================================================================
                // $commissions_amount = 0;

                // $commissions        = $this->Employees_model->read_payroll_salary_commissions($user_id, $start_date, $end_date);
                // $count_commissions  = $this->Employees_model->count_employee_commissions($user_id, $start_date, $end_date);
                // $commissions_amount = 0;
                // if ($count_commissions > 0) {
                //     foreach ($commissions as $sl_salary_commissions) {
                //         $commissions_amount += $sl_salary_commissions->commission_amount;
                //     }
                // } else {
                //     $commissions_amount = 0;
                // }

                $commissions_amount = isset($all_commission[$user_id]) ? $all_commission[$user_id]->total_amount : 0;

                // ============================================================================================================
                // 2: Lembur
                // ============================================================================================================

                // $overtime_amount = 0;
                // $overtime_amount_jam = 0;

                // $salary_overtime = $this->Employees_model->read_payroll_salary_overtime($user_id, $start_date, $end_date);
                // $count_overtime = $this->Employees_model->count_payroll_employee_overtime($user_id, $start_date, $end_date);
                // $overtime_amount = 0;
                // if ($count_overtime > 0) {
                //     foreach ($salary_overtime as $sl_overtime) {
                //         $overtime_amount += $sl_overtime->overtime_total;
                //         $overtime_amount_jam += $sl_overtime->overtime_hours_total;
                //     }
                // } else {
                //     $overtime_amount = 0;
                //     $overtime_amount_jam = 0;
                // }

                $overtime_amount = isset($all_overtime[$user_id]) ? $all_overtime[$user_id]->total_overtime : 0;
                $overtime_amount_jam = isset($all_overtime[$user_id]) ? $all_overtime[$user_id]->total_overtime_hour : 0;

                // ====================================================================================================================
                // (-) KOMPONEN GAJI - KURANG
                // ====================================================================================================================

                // ****************************************************************************************************************
                // TETAP
                // ****************************************************************************************************************
                // ============================================================================================================
                // 1: BPJS TK
                // ============================================================================================================

                // $count_bpjs_tk = $this->Employees_model->count_employee_bpjs_tk($user_id, $end_date);
                // $bpjs_tk = $this->Employees_model->set_employee_bpjs_tk($user_id, $end_date);
                // $bpjs_tk_amount = 0;
                // if ($count_bpjs_tk > 0) {
                //     foreach ($bpjs_tk->result() as $sl_salary_bpjs_tk) {
                //         $bpjs_tk_amount += $sl_salary_bpjs_tk->deduction_amount;
                //     }
                // } else {
                //     $bpjs_tk_amount = 0;
                // }

                $bpjs_tk_amount = isset($all_bpjs[$user_id]) ? $all_bpjs[$user_id]->total_bpjstk : 0;

                // ============================================================================================================
                // 2: BPJS KES
                // ============================================================================================================

                // $count_bpjs_kes = $this->Employees_model->count_employee_bpjs_kes($user_id, $end_date);
                // $bpjs_kes = $this->Employees_model->set_employee_bpjs_kes($user_id, $end_date);
                // $bpjs_kes_amount = 0;
                // if ($count_bpjs_kes > 0) {
                //     foreach ($bpjs_kes->result() as $sl_salary_bpjs_kes) {
                //         $bpjs_kes_amount += $sl_salary_bpjs_kes->deduction_amount;
                //     }
                // } else {
                //     $bpjs_kes_amount = 0;
                // }

                $bpjs_kes_amount = isset($all_bpjs[$user_id]) ? $all_bpjs[$user_id]->total_bpjskes : 0;

                // ****************************************************************************************************************
                // >> TIDAK TETAP
                // ****************************************************************************************************************

                // ============================================================================================================
                // 1: Minus
                // ============================================================================================================
                // $minus       = $this->Employees_model->read_payroll_salary_minus($user_id, $start_date, $end_date);
                // $count_minus = $this->Employees_model->count_employee_minus($user_id, $start_date, $end_date);
                // $minus_amount = 0;
                // if ($count_minus > 0) {
                //     foreach ($minus as $sl_salary_minus) {
                //         $minus_amount += $sl_salary_minus->minus_amount;
                //     }
                // } else {
                //     $minus_amount = 0;
                // }

                $minus_amount = isset($all_salary_minus[$user_id]) ? $all_salary_minus[$user_id]->total_minus : 0;

                // ============================================================================================================
                // 2: Hadir
                // ============================================================================================================

                // $cek_hadir      = $this->Timesheet_model->hitung_jumlah_status_kehadiran($employee_user_id, $start_date, $end_date, 'H');

                // $cek_lembur     = $this->Timesheet_model->hitung_jumlah_status_kehadiran($employee_user_id, $start_date, $end_date, 'O');

                // $cek_izin       = $this->Core_model->read_user_izin_jumlah($employee_user_id, $start_date, $end_date);

                // // user full name
                // if (!is_null($cek_izin)) {
                //     $cek_jum_izin   = $cek_izin[0]->jumlah;
                // } else {
                //     $cek_jum_izin   = '';
                // }

                // if ($cek_jum_izin == '' || $cek_jum_izin == 0) {
                //     $jum_izin   = 0;
                // } else {
                //     $jum_izin   = $cek_jum_izin;
                // }

                $attendance = isset($all_total_attendance[$user_id]) ? $all_total_attendance[$user_id] : [];
                $info_jum_hadir  = isset($attendance['H']) ? $attendance['H'] : 0;
                $info_jum_lembur = isset($attendance['O']) ? $attendance['O'] : 0;

                $info_jum_izin   = isset($all_permit[$user_id]) ? $all_permit[$user_id]->jumlah : 0;

                // lembur di hari libur
                $ooo = isset($all_total_ooo[$employee_user_id]) ? $all_total_ooo[$employee_user_id] : 0;

                // $jumlah_hadir  = $cek_hadir[0]->jumlah + $cek_lembur[0]->jumlah + $jum_izin;
                $jumlah_hadir  = $info_jum_hadir + $info_jum_lembur + $info_jum_izin - $ooo;
                $jumlah_upah   = $jumlah_upah == 0 ? $basic_salary * $jumlah_hadir : $jumlah_upah;

                // ====================================================================================================================
                // HITUNG
                // ====================================================================================================================
                $tanggal_awal       = date("Y-m-d", strtotime($start_date));
                $tanggal_akhir      = date("Y-m-d", strtotime($end_date));
                $tanggal_potong     = date("Y-m-20", strtotime($start_date));

                if ($tanggal_potong >= $tanggal_awal and $tanggal_potong <= $tanggal_akhir) {

                    $pot_bpjs_kes_amount = $bpjs_kes_amount;
                } else {

                    $pot_bpjs_kes_amount = 0;
                }
                $total_gaji         = $jumlah_upah;
                $jumlah_jam_lembur  = $overtime_amount_jam;
                $total_lembur       = $overtime_amount;
                $total_net_salary   = ($total_gaji + $total_lembur + $commissions_amount) - ($minus_amount + $pot_bpjs_kes_amount + $bpjs_tk_amount);
                $jurl               = random_string('alnum', 40);

                // ====================================================================================================================
                // SIMPAN TABEL GAJI
                // ====================================================================================================================

                // Karyawan Posisi
                // $designation = $this->Designation_model->read_designation_information($empid->designation_id);
                // if (!is_null($designation)) {
                //     $workstation_id = $designation[0]->workstation_id;
                // } else {
                //     $workstation_id = '';
                // }

                $workstation_id = isset($all_designations[$employee_designation_id]) ? $all_designations[$employee_designation_id]->workstation_id : '';

                $data = array(
                    'employee_id'           => $user_id,
                    'department_id'         => $employee->department_id,
                    'doj'                   => $employee->date_of_joining,
                    'company_id'            => $employee->company_id,
                    'location_id'           => $employee->location_id,
                    'designation_id'        => $employee->designation_id,
                    'wages_type'            => $employee->wages_type,
                    'workstation_id'        => $workstation_id,
                    'start_date'            => $start_date,
                    'end_date'              => $end_date,
                    'basic_salary'          => $basic_salary,
                    'jumlah_hadir'          => $jumlah_hadir,
                    'total_upah'            => $total_gaji,
                    'jumlah_overtime'       => $overtime_amount_jam,
                    'overtime_amount'       => $overtime_amount,
                    'commissions_amount'    => $commissions_amount,
                    'minus_amount'          => $minus_amount,
                    'bpjs_kes_amount'       => $pot_bpjs_kes_amount,
                    'bpjs_tk_amount'        => $bpjs_tk_amount,
                    'net_salary'            => $total_net_salary,
                    'rekening_name'         => $rekening_name,
                    'bank_name'             => $bank_name,
                    'email'                 => $employee_email,
                    'is_payment'            => '1',
                    'payslip_type'          => 'full_periode',
                    'payslip_key'           => $jurl,
                    'year_to_date'          => date('Y-m-d'),
                    'created_at'            => date('Y-m-d h:i:s'),
                    'created_by'            => $user_create
                );

                // $result = $this->Payroll_model->add_salary_payslip_harian($data);
                $insert_data[] = $data;

                // if ($index == 9) {
                //     break;
                // }

                // echo "<pre>";
                // print_r($this->db->last_query());
                // echo "</pre>";
                // die();

                // if ($result) {
                //     $Return['result'] = 'Gaji Harian Kolektif ' . "\n" . ' Periode Tanggal ' . $start_date . ' s/d ' . $end_date . '' . "\n" . ' Berhasil Disimpan';
                // } else {
                //     $Return['error'] = $this->lang->line('xin_error_msg');
                // }

                log_message('debug', "finish user {$employee->user_id}");
            }

            log_message('debug', 'finish payroll');

            $result = $this->Payroll_model->add_salary_payslip_harian($insert_data, TRUE);
            if ($result) {
                $Return['result'] = 'Gaji Harian Kolektif ' . "\n" . ' Periode Tanggal ' . $start_date . ' s/d ' . $end_date . '' . "\n" . ' Berhasil Disimpan';
            } else {
                $Return['error'] = $this->lang->line('xin_error_msg');
            }

            $this->output($Return);
            exit;
        }
    }

    public function add_pay_dayly()
    {
        if ($this->input->post('add_type') == 'add_dayly_payment') {

            /* Define return | here result is used to return user data and error for error message */
            $Return = array('result' => '', 'error' => '', 'csrf_hash' => '');
            $Return['csrf_hash'] = $this->security->get_csrf_hash();

            $employee_id     = $this->input->post('emp_id');
            $employee_name   = $this->input->post('employee_name');
            $start_date      = $this->input->post('start_date');
            $end_date        = $this->input->post('end_date');

            $pay_count = $this->Payroll_model->read_make_payment_payslip_check_harian_company($employee_id, $start_date, $end_date);

            if ($pay_count->num_rows() > 0) {

                $pay_val = $this->Payroll_model->read_make_payment_payslip_harian_company($employee_id, $start_date, $end_date);

                $this->payslip_delete_all_harian($pay_val[0]->payslip_id);
            }

            $jurl = random_string('alnum', 40);

            date_default_timezone_set("Asia/Jakarta");

            $datetime1 = new DateTime($this->input->post('employee_date_of_joining'));
            $datetime2 = new DateTime($this->input->post('end_date'));
            $difference = $datetime1->diff($datetime2);



            $gapok       = $this->Employees_model->read_payroll_salary_gapok($employee_id, $end_date);
            $count_gapok = $this->Employees_model->count_employee_gapok($employee_id, $end_date);
            $gapok_amount = 0;
            if ($count_gapok > 0) {
                foreach ($gapok as $sl_salary_gapok) {
                    $gapok_amount += $sl_salary_gapok->gapok_amount;
                }
            } else {
                $gapok_amount = 0;
            }

            // ============================================================================================================
            // 1: salary type
            // ============================================================================================================

            if ($difference->days >= 0 and $difference->days <= 31) {

                $basic_salary = $gapok_amount;
            } else if ($difference->days > 31) {

                if ($gapok_amount == 50000) {

                    $basic_salary      = $gapok_amount + 15000;
                    $basic_salary_naik = $gapok_amount + 15000;

                    // ===================================================

                    $session_id = $this->session->userdata('user_id');
                    $user_create = $session_id['user_id'];


                    $data_rekap = array(
                        'gapok_date'                => $end_date,
                        'employe_id'                  => $employee_id,
                        'wages_type'                 => 2,
                        'gapok_amount'              => $basic_salary,
                        'gapok_title'               => 'Kenaikan Bulan Ke 2',
                        'created_at'                => date('Y-m-d h:i:s'),
                        'created_by'                => $user_create
                    );

                    $this->Payroll_model->add_naik_gapok($data_rekap);
                } else {

                    $basic_salary      =  $gapok_amount;
                    $basic_salary_naik =  $gapok_amount;
                }
            }


            $session_id = $this->session->userdata('user_id');
            $user_create = $session_id['user_id'];

            $data = array(
                'employee_id'                  => $this->input->post('emp_id'),
                'department_id'                => $this->input->post('employee_department_id'),
                'doj'                            => $this->input->post('employee_date_of_joining'),
                'company_id'                   => $this->input->post('employee_company_id'),
                'location_id'                  => $this->input->post('employee_location_id'),
                'designation_id'               => $this->input->post('employee_designation_id'),
                'wages_type'                   => $this->input->post('employee_wages_type'),

                'workstation_id'               => $this->input->post('workstation_id'),

                'start_date'                   => $this->input->post('start_date'),
                'end_date'                     => $this->input->post('end_date'),

                'basic_salary'                 => $basic_salary,
                'jumlah_hadir'                 => $this->input->post('jumlah_hadir'),
                'total_upah'                   => $this->input->post('total_gaji'),

                'jumlah_overtime'              => $this->input->post('jumlah_overtime'),
                'overtime_amount'              => $this->input->post('overtime_amount'),

                'commissions_amount'           => $this->input->post('commissions_amount'),

                'bpjs_kes_amount'              => $this->input->post('bpjs_kes_amount'),
                'bpjs_tk_amount'               => $this->input->post('bpjs_tk_amount'),
                'minus_amount'                 => $this->input->post('minus_amount'),

                'net_salary'                   => $this->input->post('total_net_salary'),
                'rekening_name'                => $this->input->post('rekening_name'),
                'bank_name'                    => $this->input->post('bank_name'),
                'email'                        => $this->input->post('employee_email'),

                'is_payment'                   => '1',
                'payslip_type'                 => 'full_periode',
                'payslip_key'                  => $jurl,
                'year_to_date'                 => date('Y-m-d'),
                'created_at'                   => date('Y-m-d h:i:s'),
                'created_by'                   => $user_create
            );
            $result = $this->Payroll_model->add_salary_payslip_harian($data);

            // echo "<pre>";
            // print_r($this->db->last_query());
            // echo "</pre>";
            // die();

            if ($result) {
                $Return['result'] = 'Gaji Harian ' . $employee_name . '' . "\n" . ' Periode Tanggal ' . $start_date . ' s/d ' . $end_date . '' . "\n" . ' Berhasil Disimpan';
            } else {
                $Return['error'] = $this->lang->line('xin_error_msg');
            }

            $this->output($Return);
            exit;
        }
    }

    // =======================================================================================
    // PROSES : TAMPIL
    // =======================================================================================

    // Tampil : Form Edit
    public function pay_salary_dayly()
    {
        $session = $this->session->userdata('username');
        if (empty($session)) {
            redirect('admin/');
        }
        $data['title'] = $this->Core_model->site_title();
        $id = $this->input->get('employee_id');
        // get addd by > template
        $user = $this->Core_model->read_user_info($id);

        // get designation
        $designation = $this->Designation_model->read_designation_information($user[0]->designation_id);
        if (!is_null($designation)) {
            $designation_name = $designation[0]->designation_name;
        } else {
            $designation_name = '';
        }
        // department
        $department = $this->Department_model->read_department_information($user[0]->department_id);
        if (!is_null($department)) {
            $department_name = $department[0]->department_name;
        } else {
            $department_name = '';
        }

        //$location = $this->Location_model->read_location_information($department[0]->location_id);
        $data = array(
            'department_name'  => $department_name,
            'designation_name' => $designation_name,
            'company_id'       => $user[0]->company_id,
            'location_id'      => $user[0]->location_id,
            'user_id'          => $user[0]->user_id,
            'wages_type'       => $user[0]->wages_type,
            'basic_salary'     => $user[0]->basic_salary
        );
        if (!empty($session)) {
            $this->load->view('admin/payroll/dialog_make_payment_harian', $data);
        } else {
            redirect('admin/');
        }
    }

    // Tampil : Form Hapu
    public function pay_salary_dayly_del()
    {
        $session = $this->session->userdata('username');
        if (empty($session)) {
            redirect('admin/');
        }
        $data['title'] = $this->Core_model->site_title();
        $payslip_id = $this->input->get('payslip_id');
        // get addd by > template

        $payslip = $this->Core_model->read_slip_info_harian($payslip_id);

        $user = $this->Core_model->read_user_info($payslip[0]->employee_id);

        $designation = $this->Designation_model->read_designation_information($payslip[0]->designation_id);
        if (!is_null($designation)) {
            $designation_name = $designation[0]->designation_name;
        } else {
            $designation_name = '';
        }

        $workstation = $this->Workstation_model->read_workstation_information($payslip[0]->workstation_id);
        if (!is_null($workstation)) {
            $workstation_name = $workstation[0]->workstation_name;
        } else {
            $workstation_name = '';
        }

        // department
        $department = $this->Department_model->read_department_information($payslip[0]->department_id);
        if (!is_null($department)) {
            $department_name = $department[0]->department_name;
        } else {
            $department_name = '';
        }

        $data = array(
            'payslip_id'                     => $payslip_id,
            'department_name'                => $department_name,
            'designation_name'               => $designation_name,
            'workstation_name'               => $workstation_name,

            'employee_name'                  => $user[0]->first_name . ' ' . $user[0]->last_name,
            'company_id'                     => $payslip[0]->company_id,

            'start_date'                  => $payslip[0]->start_date,
            'end_date'                    => $payslip[0]->end_date,

            'basic_salary'                => $payslip[0]->basic_salary,
            'jumlah_hadir'                => $payslip[0]->jumlah_hadir,
            'total_gaji'                  => $payslip[0]->total_upah,
            'jumlah_overtime'             => $payslip[0]->jumlah_overtime,
            'overtime_amount'             => $payslip[0]->overtime_amount,

            'commissions_amount'          => $payslip[0]->commissions_amount,

            'bpjs_kes_amount'             => $payslip[0]->bpjs_kes_amount,
            'bpjs_tk_amount'              => $payslip[0]->bpjs_tk_amount,

            'minus_amount'                => $payslip[0]->minus_amount,

            'total_net_salary'            => $payslip[0]->net_salary,
            'rekening_name'                => $payslip[0]->rekening_name,
            'bank_name'                      => $payslip[0]->bank_name,
            'employee_email'              => $payslip[0]->email,


        );
        if (!empty($session)) {
            $this->load->view('admin/payroll/dialog_make_payment_harian_delete', $data);
        } else {
            redirect('admin/');
        }
    }

    // =======================================================================================
    // PROSES : HAPUS
    // =======================================================================================

    public function del_pay_dayly()
    {
        if ($this->input->post('proses_type') == 'del_dayly_payment') {
            /* Define return | here result is used to return user data and error for error message */
            $Return = array('result' => '', 'error' => '', 'csrf_hash' => '');
            $Return['csrf_hash'] = $this->security->get_csrf_hash();


            $id     = $this->input->post('payslip_id');

            $result = $this->Payroll_model->delete_record_harian($id);

            // echo "<pre>";
            // print_r($this->db->last_query());
            // echo "</pre>";
            // die();

            if (isset($id)) {

                $Return['result'] = 'Gaji Harian Berhasil Dihapus';
            } else {
                $Return['error'] = $this->lang->line('xin_error_msg');
            }

            $this->output($Return);
            exit;
        }
    }

    public function payslip_delete_all_harian($id)
    {
        $session = $this->session->userdata('username');
        if (empty($session)) {
            redirect('admin/');
        }
        /* Define return | here result is used to return user data and error for error message */
        $Return = array('result' => '', 'error' => '', 'csrf_hash' => '');
        $id = $id;
        $Return['csrf_hash'] = $this->security->get_csrf_hash();
        $this->Payroll_model->delete_record_harian($id);
    }

    // =======================================================================================
    // TAMPIL => SLIP GAJI
    // =======================================================================================

    public function payslip_harian()
    {
        $session = $this->session->userdata('username');
        if (empty($session)) {
            redirect('admin/');
        }
        //$data['title'] = $this->Core_model->site_title();
        $key = $this->uri->segment(5);

        $result = $this->Payroll_model->read_salary_payslip_harian_info_key($key);
        if (is_null($result)) {
            redirect('admin/payroll/harian');
        }

        $p_method = '';

        // get addd by > template
        $user = $this->Core_model->read_user_info($result[0]->employee_id);
        // user full name
        if (!is_null($user)) {
            $first_name = $user[0]->first_name;
            $last_name = $user[0]->last_name;
        } else {
            $first_name = '--';
            $last_name = '--';
        }
        // get designation
        $designation = $this->Designation_model->read_designation_information($user[0]->designation_id);
        if (!is_null($designation)) {
            $designation_name = $designation[0]->designation_name;
        } else {
            $designation_name = '--';
        }

        // department
        $department = $this->Department_model->read_department_information($user[0]->department_id);
        if (!is_null($department)) {
            $department_name = $department[0]->department_name;
        } else {
            $department_name = '--';
        }

        // company
        $company = $this->Company_model->read_company_information($user[0]->company_id);
        if (!is_null($company)) {
            $company_name = $company[0]->name;
        } else {
            $company_name = '--';
        }


        //$department_designation = $designation[0]->designation_name.'('.$department[0]->department_name.')';
        $data['all_employees'] = $this->Core_model->all_employees();



        $data = array(
            'title'                      => 'Slip Gaji Harian Karyawan | ' . $this->Core_model->site_title(),
            'icon'                       => '<i class="fa fa-money"></i>',
            'desc'                       => 'CETAK : Slip Gaji Harian Karyawan',
            'breadcrumbs'                => 'Slip Gaji Harian Karyawan',
            'path_url'                   => 'payslip_harian',

            'first_name'                 => $first_name,
            'last_name'                  => $last_name,

            'employee_id'                => $user[0]->employee_id,
            'euser_id'                   => $user[0]->user_id,
            'contact_no'                 => $user[0]->contact_no,
            'email'                      => $user[0]->email,
            'date_of_joining'            => $user[0]->date_of_joining,

            'company_name'               => $company_name,
            'department_name'            => $department_name,
            'designation_name'           => $designation_name,

            'date_of_joining'            => $user[0]->date_of_joining,
            'profile_picture'            => $user[0]->profile_picture,
            'gender'                     => $user[0]->gender,
            'make_payment_id'            => $result[0]->payslip_id,
            'wages_type'                 => $result[0]->wages_type,
            'payment_date'               => $result[0]->start_date . ' s/d ' . $result[0]->end_date,
            'year_to_date'               => $result[0]->year_to_date,

            'basic_salary'               => $result[0]->basic_salary,
            'jumlah_hadir'                 => $result[0]->jumlah_hadir,
            'total_upah'                 => $result[0]->total_upah,

            'jumlah_overtime'             => $result[0]->jumlah_overtime,
            'total_overtime'             => $result[0]->overtime_amount,

            'total_statutory_deductions' => $result[0]->bpjs_kes_amount + $result[0]->bpjs_tk_amount,

            'total_commissions'             => $result[0]->commissions_amount,

            'total_minus'             => $result[0]->minus_amount,

            'net_salary'                 => $result[0]->net_salary,

            'payslip_key'                => $result[0]->payslip_key,
            'payslip_type'               => $result[0]->payslip_type,
            'hours_worked'               => $result[0]->hours_worked,
            'pay_comments'               => $result[0]->pay_comments,
            'is_payment'                 => $result[0]->is_payment,
            'approval_status'            => $result[0]->status,
        );

        $role_resources_ids = $this->Core_model->user_role_resource();
        if (!empty($session)) {
            $data['subview'] = $this->load->view("admin/payroll/payslip_harian", $data, TRUE);
            $this->load->view('admin/layout/layout_main', $data); //page load
        } else {
            redirect('admin/');
        }
    }

    public function pdf_create_harian()
    {
        //$this->load->library('Pdf');
        $system = $this->Core_model->read_setting_info(1);
        // create new PDF document
        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

        $key = $this->uri->segment(5);
        $payment = $this->Payroll_model->read_salary_payslip_harian_info_key($key);
        if (is_null($payment)) {
            redirect('admin/payroll/payslip_harian');
        }
        $user = $this->Core_model->read_user_info($payment[0]->employee_id);

        // if password generate option enable
        if ($system[0]->is_payslip_password_generate == 1) {
            /**
             * Protect PDF from being printed, copied or modified. In order to being viewed, the user needs
             * to provide password as selected format in settings module.
             */
            if ($system[0]->payslip_password_format == 'dateofbirth') {
                $password_val = date("dmY", strtotime($user[0]->date_of_birth));
            } else if ($system[0]->payslip_password_format == 'contact_no') {
                $password_val = $user[0]->contact_no;
            } else if ($system[0]->payslip_password_format == 'full_name') {
                $password_val = $user[0]->first_name . $user[0]->last_name;
            } else if ($system[0]->payslip_password_format == 'email') {
                $password_val = $user[0]->email;
            } else if ($system[0]->payslip_password_format == 'password') {
                $password_val = $user[0]->password;
            } else if ($system[0]->payslip_password_format == 'user_password') {
                $password_val = $user[0]->username . $user[0]->password;
            } else if ($system[0]->payslip_password_format == 'employee_id') {
                $password_val = $user[0]->employee_id;
            } else if ($system[0]->payslip_password_format == 'employee_id_password') {
                $password_val = $user[0]->employee_id . $user[0]->password;
            } else if ($system[0]->payslip_password_format == 'dateofbirth_name') {
                $dob = date("dmY", strtotime($user[0]->date_of_birth));
                $fname = $user[0]->first_name;
                $lname = $user[0]->last_name;
                $password_val = $dob . $fname[0] . $lname[0];
            }
            $pdf->SetProtection(array('print', 'copy', 'modify'), $password_val, $password_val, 0, null);
        }


        $_des_name = $this->Designation_model->read_designation_information($user[0]->designation_id);
        if (!is_null($_des_name)) {
            $_designation_name = $_des_name[0]->designation_name;
        } else {
            $_designation_name = '';
        }
        $department = $this->Department_model->read_department_information($user[0]->department_id);
        if (!is_null($department)) {
            $_department_name = $department[0]->department_name;
        } else {
            $_department_name = '';
        }
        //$location = $this->Core_model->read_location_info($department[0]->location_id);
        // company info
        $company = $this->Core_model->read_company_info($user[0]->company_id);


        $p_method = '';
        if (!is_null($company)) {
            $company_logo = $company[0]->logo;
            $company_name = $company[0]->name;
            $address_1 = $company[0]->address_1;
            $address_2 = $company[0]->address_2;
            $city = $company[0]->city;
            $state = $company[0]->state;
            $zipcode = $company[0]->zipcode;
            $country = $this->Core_model->read_country_info($company[0]->country);
            if (!is_null($country)) {
                $country_name = $country[0]->country_name;
            } else {
                $country_name = '--';
            }
            $c_info_email = $company[0]->email;
            $c_info_phone = $company[0]->contact_number;
        } else {
            $company_logo = $company[0]->logo;
            $company_name = '--';
            $address_1 = '--';
            $address_2 = '--';
            $city = '--';
            $state = '--';
            $zipcode = '--';
            $country_name = '--';
            $c_info_email = '--';
            $c_info_phone = '--';
        }
        $pdf = new Pdf('P', 'mm', 'A4', true, 'UTF-8', false);

        // set default header data
        $c_info_address = $address_1 . ', ' . $address_2;

        $c_info_city    = $city . ', Kode Pos : ' . $zipcode . ' Jawa Timur - Indonesia';

        $email_phone_address = "$c_info_address \n" . "$c_info_city \n" . $this->lang->line('xin_phone') . " : $c_info_phone | " . $this->lang->line('dashboard_email') . " : $c_info_email \n";

        $header_string = $email_phone_address;

        // set document information
        $pdf->SetCreator('hris');
        $pdf->SetAuthor('Nizar Basyrewan');
        $pdf->SetTitle($company_name . ' - ' . $this->lang->line('xin_print_payslip_harian'));
        $pdf->SetSubject($this->lang->line('xin_payslip'));
        $pdf->SetKeywords($this->lang->line('xin_payslip'));

        // $pdf->SetHeaderData('../../../../../uploads/logo/payroll/'.$company_logo, 40, $company_name, $header_string);

        $pdf->setFooterData(array(0, 64, 0), array(0, 64, 128));

        // set header and footer fonts
        $pdf->setHeaderFont(array('arial', '', 12));
        $pdf->setFooterFont(array('helvetica', '', 9));

        // set default monospaced font
        $pdf->SetDefaultMonospacedFont('courier');

        // set margins
        $pdf->SetMargins(15, 27, 15);
        $pdf->SetHeaderMargin(5);
        $pdf->SetFooterMargin(10);

        // set auto page breaks
        $pdf->SetAutoPageBreak(TRUE, 25);

        // set image scale factor
        $pdf->setImageScale(1.25);

        // set font
        $pdf->SetFont('helvetica', 'B', 10);

        // set header and footer fonts
        $pdf->setHeaderFont(array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
        $pdf->setFooterFont(array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

        // set default monospaced font
        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

        // set image scale factor
        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

        // ---------------------------------------------------------

        // set default font subsetting mode
        $pdf->setFontSubsetting(true);

        // Set font
        // dejavusans is a UTF-8 Unicode font, if you only need to
        // print standard ASCII chars, you can use core fonts like
        // helvetica or times to reduce file size.
        $pdf->SetFont('dejavusans', '', 10, '', true);

        // Add a page
        // This method has several options, check the source code documentation for more information.
        $pdf->AddPage();
        // -----------------------------------------------------------------------------
        $fname           = $user[0]->first_name . ' ' . $user[0]->last_name;

        $created_at      = $this->Core_model->set_date_format($payment[0]->created_at);
        $date_of_joining = $this->Core_model->set_date_format($user[0]->date_of_joining);


        $start_att = date("d-m-Y", strtotime($payment[0]->start_date));
        $end_att   = date("d-m-Y", strtotime($payment[0]->end_date));

        // check
        $half_title = '';

        // ===========================================================
        // Penambah
        //============================================================

        // basic salary
        $bs = 0;
        $bs = $payment[0]->basic_salary;

        $ns = $payment[0]->net_salary;

        // Lembur
        $count_overtime = $this->Employees_model->count_employee_overtime_payslip_harian($payment[0]->payslip_id);
        $overtime = $this->Employees_model->set_employee_overtime_payslip_harian($payment[0]->payslip_id);

        $overtime_amount = 0;
        foreach ($overtime->result() as $sl_overtime) {
            $overtime_amount += $sl_overtime->overtime_amount;
        }



        $tbl = '
                    <table cellpadding="1" cellspacing="1" border="0" style="font-size:10px;">
                        <tr>
                            <td align="center"><h2> SLIP GAJI KARYAWAN </h2></td>
                        </tr>
                        <tr>
                            <td align="center">  <strong>' . date("d-m-Y", strtotime($payment[0]->start_date)) . ' - ' . date("d-m-Y", strtotime($payment[0]->end_date)) . '</strong></td>
                        </tr>
                    </table>
                ';

        $pdf->writeHTML($tbl, true, false, false, false, '');
        // -----------------------------------------------------------------------------
        // set cell padding
        $pdf->setCellPaddings(1, 1, 1, 1);

        // set cell margins
        $pdf->setCellMargins(0, 0, 0, 0);

        // set color for background
        $pdf->SetFillColor(255, 255, 127);
        // set some text for example
        //$txt = 'Employee Details';
        // Multicell
        // $pdf->MultiCell(180, 6, $txt, 0, 'L', 11, 0, '', '', true);
        $pdf->Ln(7);
        $tbl1 = '
                    <table cellpadding="1" cellspacing="0" border="0" width="100%" style="font-size:10px;" >

                    <tr>
                        <td width="15%;"> <strong>NAMA</strong> </td>
                        <td width="35%;"> : ' . strtoupper($fname) . '</td>
                        <td width="15%;"> <strong>PERIODE</strong> </td>
                        <td width="35%;"> : ' . $start_att . ' s/d ' . $end_att . ' </td>
                    </tr>
                    <tr>
                        <td width="15%;"> <strong>JABATAN</strong> </td>
                        <td width="35%;"> : ' . strtoupper($_designation_name) . '</td>
                        <td width="15%;"> <strong>N.I.K</strong> </td>
                        <td width="35%;"> : ' . $user[0]->employee_id . '</td>
                    </tr>';


        $tbl1 .= '
                        </table>';

        $pdf->writeHTML($tbl1, true, false, true, false, '');


        //// break..
        // $pdf->Ln(0);





        $tbl_new = '
                    <table cellpadding="6" cellspacing="0" border="1" width="100%" style="border: 1px solid #ccc; font-size:10px;">

                        <tr>
                            <td width="15%;" align ="center" > <strong>TANGAL</strong> </td>
                            <td width="10%;" align ="center" > <strong>HARI</strong> </td>
                            <td width="14%;" align ="center" > <strong>KEHADIRAN</strong> </td>
                            <td width="15%;" align ="center" > <strong>GAJI POKOK</strong> </td>
                            <td width="12%;" align ="center" > <strong>LEMBUR</strong> </td>
                            <td width="12%;" align ="center" > <strong>INSENTIF</strong> </td>
                            <td width="12%;" align ="center" > <strong>POTONG</strong> </td>
                            <td width="14%;" align ="center" > <strong>TOTAL </strong> </td>
                        </tr>';
        $start_date = new DateTime($payment[0]->start_date);
        $end_date   = new DateTime($payment[0]->end_date);
        $end_date   = $end_date->modify('+1 day');

        $interval_re = new DateInterval('P1D');
        $date_range = new DatePeriod($start_date, $interval_re, $end_date);
        $attendance_arr = array();

        foreach ($date_range as $date) {

            $attendance_date =  $date->format("Y-m-d");

            $tday = $this->Timesheet_model->conHariNama(date("D", strtotime($attendance_date)));

            $attendance = $this->Core_model->read_attendance_info_detail($user[0]->user_id, $attendance_date);

            if (!is_null($attendance)) {

                $cek_attendance_status      = $attendance[0]->attendance_status;
                if ($cek_attendance_status == '0') {
                    $attendance_status      = '0';
                } else {
                    $attendance_status      = $cek_attendance_status;
                }
            } else {

                $attendance_status     = '-';
            }

            if ($attendance_status == '-') {

                $gapok  = '-';
                $lembur = '-';
                $total  = '-';
            } else {

                // =========================================================================================================
                // GAPOK
                // =========================================================================================================

                $gapok = number_format($bs, 0, ',', '.');

                // =========================================================================================================
                // LEMBUR
                // =========================================================================================================
                $sql_check_lembur = "SELECT * FROM xin_salary_overtime WHERE employee_id ='" . $user[0]->user_id . "' AND overtime_date = '" . $attendance_date . "' ";
                // echo "<pre>";
                // print_r($sql_check_izin);
                // echo "</pre>";
                // die;
                $query_check_lembur = $this->db->query($sql_check_lembur);
                if ($query_check_lembur->num_rows() > 0) {

                    foreach ($query_check_lembur->result() as $row_check_lembur) :


                        $overtime_total       =  $row_check_lembur->overtime_total;

                    endforeach;
                } else {

                    $overtime_total = '0';
                }

                if ($overtime_total == 0) {

                    $lembur = '-';
                } else {

                    $lembur =  number_format($overtime_total, 0, ',', '.');
                }

                // =========================================================================================================
                // INSENTIF
                // =========================================================================================================
                $sql_check_insentif = "SELECT * FROM xin_salary_commissions WHERE employee_id ='" . $user[0]->user_id . "' AND commission_date = '" . $attendance_date . "' ";
                // echo "<pre>";
                // print_r($sql_check_izin);
                // echo "</pre>";
                // die;
                $query_check_insentif = $this->db->query($sql_check_insentif);
                if ($query_check_insentif->num_rows() > 0) {

                    foreach ($query_check_insentif->result() as $row_check_insentif) :


                        $commission_total       =  $row_check_insentif->commission_amount;

                    endforeach;
                } else {

                    $commission_total = '0';
                }

                if ($commission_total == 0) {

                    $insentif = '-';
                } else {

                    $insentif =  number_format($commission_total, 0, ',', '.');
                }

                // =========================================================================================================
                // POTONG
                // =========================================================================================================
                $sql_check_potong = "SELECT * FROM xin_salary_minus WHERE employee_id ='" . $user[0]->user_id . "' AND minus_date = '" . $attendance_date . "' ";
                // echo "<pre>";
                // print_r($sql_check_izin);
                // echo "</pre>";
                // die;
                $query_check_potong = $this->db->query($sql_check_potong);
                if ($query_check_potong->num_rows() > 0) {

                    foreach ($query_check_potong->result() as $row_check_potong) :


                        $minus_total       =  $row_check_potong->minus_amount;

                    endforeach;
                } else {

                    $minus_total = '0';
                }

                if ($minus_total == 0) {

                    $potong = '-';
                } else {

                    $potong =  number_format($minus_total, 0, ',', '.');
                }

                if ($attendance_status == 'Libur' || $attendance_status == 'Absen') {

                    $total = '-';
                } else {

                    $total = number_format($bs + $overtime_total + $commission_total - $minus_total, 0, ',', '.');
                }
            }




            $tbl_new .= '
                        <tr>
                            <td width="15%;" align ="center" > ' . $attendance_date . ' </td>
                            <td width="10%;" align ="center" > ' . $tday . ' </td>
                            <td width="14%;" align ="center" > ' . $attendance_status . ' </td>
                            <td width="15%;" align ="right" > ' . $gapok . ' </td>
                            <td width="12%;" align ="right" > ' . $lembur . ' </td>
                            <td width="12%;" align ="right" > ' . $insentif . ' </td>
                            <td width="12%;" align ="right" > ' . $potong . ' </td>
                            <td width="14%;" align ="right" > ' . $total . ' </td>
                        </tr>';
        }



        $tbl_new .= '
                        <tr>
                            <td align ="right" colspan="7"> <strong> Jumlah yang diterima </strong> </td>
                            <td width="14%;" align ="right" > <strong>  ' . number_format($ns, 0, ',', '.') . ' </strong> </td>
                        </tr>

                        </table>';

        $pdf->writeHTML($tbl_new, true, false, true, false, '');

        //// break..
        $pdf->Ln(0);


        // Close and output PDF document
        // This method has several options, check the source code documentation for more information.
        $fname = strtolower($fname);
        $pay_month = strtolower(date("F Y", strtotime($payment[0]->year_to_date)));

        //Close and output PDF document
        ob_start();



        $pdf->Output('slip_gaji_harian_' . $fname . '_' . $pay_month . '.pdf', 'I');
        ob_end_flush();
    }

    public function payroll_template_read_harian()
    {
        $session = $this->session->userdata('username');
        if (empty($session)) {
            redirect('admin/');
        }
        $data['title'] = $this->Core_model->site_title();
        $id = $this->input->get('employee_id');
        // get addd by > template
        $user = $this->Core_model->read_user_info($id);
        // user full name
        $full_name = $user[0]->first_name . ' ' . $user[0]->last_name;
        // get designation
        $designation = $this->Designation_model->read_designation_information($user[0]->designation_id);
        if (!is_null($designation)) {
            $designation_name = $designation[0]->designation_name;
        } else {
            $designation_name = '--';
        }
        // department
        $department = $this->Department_model->read_department_information($user[0]->department_id);
        if (!is_null($department)) {
            $department_name = $department[0]->department_name;
        } else {
            $department_name = '--';
        }
        $data = array(
            'first_name' => $user[0]->first_name,
            'last_name' => $user[0]->last_name,
            'employee_id' => $user[0]->employee_id,
            'user_id' => $user[0]->user_id,
            'department_name' => $department_name,
            'designation_name' => $designation_name,
            'date_of_joining' => $user[0]->date_of_joining,
            'profile_picture' => $user[0]->profile_picture,
            'gender' => $user[0]->gender,
            'wages_type' => $user[0]->wages_type,
            'basic_salary' => $user[0]->basic_salary,
            'daily_wages' => '',
        );
        if (!empty($session)) {
            $this->load->view('admin/payroll/dialog_templates_harian', $data);
        } else {
            redirect('admin/');
        }
    }

    // =======================================================================================
    // DETAIL KARYAWAN : GAJI HARIAN
    // =======================================================================================

    // =======================================================================================
    // DETAIL
    // =======================================================================================
    public function harian_detail()
    {

        $session = $this->session->userdata('username');
        if (empty($session)) {
            redirect('admin/');
        }

        $role_resources_ids = $this->Core_model->user_role_resource();
        $check_role = $this->Employees_model->read_employee_information($session['user_id']);
        if (!in_array('1020', $role_resources_ids)) {
            redirect('admin/payroll/harian');
        }

        $id         = $this->uri->segment(4);

        $start_date = $this->uri->segment(5);
        $end_date   = $this->uri->segment(6);

        $result = $this->Employees_model->read_employee_information($id);
        if (is_null($result)) {
            redirect('admin/payroll/harian');
        }

        $data = array(
            'desc'         => 'PROSES : Edit Komponen Gaji Harian',
            'breadcrumbs'  => 'Edit Komponen Gaji Harian',
            'icon'         => '<i class="fa fa-pencil"></i>',
            'path_url'     => 'employees_detail_payroll_harian',
            'title'        => 'Edit Komponen Gaji Harian | ' . $this->Core_model->site_title(),

            'first_name'   => $result[0]->first_name,
            'last_name'    => $result[0]->last_name,
            'user_id'      => $result[0]->user_id,

            'start_date'   => $start_date,
            'end_date'     => $end_date,


            'wages_type'   => $result[0]->wages_type,
            'grade_type'   => $result[0]->grade_type,
            'basic_salary'     => $result[0]->basic_salary,

            'all_departments'  => $this->Department_model->all_departments(),
            'all_designations' => $this->Designation_model->all_designations(),
            'all_user_roles'   => $this->Roles_model->all_user_roles(),

        );


        $data['subview'] = $this->load->view("admin/payroll/harian_detail", $data, TRUE);

        $this->load->view('admin/layout/layout_main', $data); //page load

        // Datatables Variables
        $draw   = intval($this->input->get("draw"));
        $start  = intval($this->input->get("start"));
        $length = intval($this->input->get("length"));
    }

    // =======================================================================================
    // 04. LEMBUR HARIAN
    // =======================================================================================

    public function salary_all_overtime_harian()
    {
        //set data
        $data['title'] = $this->Core_model->site_title();
        $session = $this->session->userdata('username');
        if (!empty($session)) {
            $this->load->view("admin/payroll/harian_detail", $data);
        } else {
            redirect('admin/');
        }
        // Datatables Variables
        $draw = intval($this->input->get("draw"));
        $start = intval($this->input->get("start"));
        $length = intval($this->input->get("length"));


        $id    = $this->uri->segment(4);
        $start_date  = $this->uri->segment(5);
        $end_date    = $this->uri->segment(6);

        $overtime = $this->Employees_model->set_employee_overtime_harian($id, $start_date, $end_date);

        // echo "<pre>";
        // print_r($this->db->last_query());
        // echo "</pre>";
        // die();

        $system = $this->Core_model->read_setting_info(1);
        $data = array();
        $no = 1;

        foreach ($overtime->result() as $r) {

            // get overtime date
            $overtime_date    = $r->overtime_date;

            // get start date
            $clock_in_m    = $r->clock_in_m;
            // get end date
            $clock_out_m   = $r->clock_out_m;

            // overtime date
            $overtime_time = $clock_in_m . ' ' . $this->lang->line('dashboard_to') . ' ' . $clock_out_m;


            // get report to
            $reports_to = $this->Core_model->read_user_info($r->reports_to);
            // user full name
            if (!is_null($reports_to)) {

                // get designation
                $designation = $this->Designation_model->read_designation_information($reports_to[0]->designation_id);
                if (!is_null($designation)) {
                    $designation_name = $designation[0]->designation_name;
                } else {
                    $designation_name = '<span class="badge bg-red"> ? </span>';
                }

                $manager_name = $reports_to[0]->first_name . ' ' . $reports_to[0]->last_name . ' <br><small>(' . $designation_name . ')</small>';
            } else {
                $manager_name = '?';
            }

            // get overtime type
            $type = $this->Overtime_model->read_overtime_type_information($r->overtime_type);
            if (!is_null($type)) {
                $itype = $type[0]->type;
            } else {
                $itype = '--';
            }
            $iitype = $r->description;

            // Jam Lembur
            $overtime_hours = $r->overtime_hours_total;

            // Uang Lembur
            $current_amount = $r->overtime_total;

            $data[] = array(
                '
                        <span data-toggle="tooltip" data-placement="top" title="' . $this->lang->line('xin_delete') . '">
                            <button type="button" class="btn icon-btn btn-xs btn-danger waves-effect waves-light delete" data-toggle="modal" data-target=".delete-modal" data-record-id="' . $r->salary_overtime_id . '" data-token_type="all_overtime">
                                <span class="fa fa-trash"></span>
                            </button>
                        </span>',

                date("d-m-Y", strtotime($overtime_date)),
                $overtime_time,
                $manager_name,
                $iitype,
                $overtime_hours,
                $this->Core_model->currency_sign($current_amount)

            );
            $no++;
        }

        $output = array(
            "draw" => $draw,
            "recordsTotal" => $overtime->num_rows(),
            "recordsFiltered" => $overtime->num_rows(),
            "data" => $data
        );
        echo json_encode($output);
        exit();
    }

    // =======================================================================================
    // 04. GAPOK
    // =======================================================================================

    public function salary_all_gapok_harian()
    {
        //set data
        $data['title'] = $this->Core_model->site_title();
        $session = $this->session->userdata('username');
        if (!empty($session)) {
            $this->load->view("admin/payroll/harian_detail", $data);
        } else {
            redirect('admin/');
        }
        // Datatables Variables
        $draw   = intval($this->input->get("draw"));
        $start  = intval($this->input->get("start"));
        $length = intval($this->input->get("length"));

        $id = $this->uri->segment(4);
        $gapok = $this->Employees_model->set_employee_gapok($id);

        $data = array();

        foreach ($gapok->result() as $r) {

            $data[] = array(
                '<span data-toggle="tooltip" data-placement="top" title="' . $this->lang->line('xin_edit') . '">
                                <button type="button" class="btn icon-btn btn-xs btn-default waves-effect waves-light" data-toggle="modal" data-target=".edit-modal-data" data-field_id="' . $r->salary_gapok_id . '" data-field_type="salary_gapok_harian">
                                    <span class="fa fa-pencil"></span> Edit
                                </button>
                            </span>
                            <span data-toggle="tooltip" data-placement="top" title="' . $this->lang->line('xin_delete') . '">
                                <button type="button" class="btn icon-btn btn-xs btn-danger waves-effect waves-light delete" data-toggle="modal" data-target=".delete-modal" data-record-id="' . $r->salary_gapok_id . '" data-token_type="all_gapok_harian">
                                    <span class="fa fa-trash"></span>
                                </button>
                            </span>',
                date("d-m-Y", strtotime($r->gapok_date)),
                $r->gapok_title,
                $this->Core_model->currency_sign($r->gapok_amount)
            );
        }

        $output = array(
            "draw" => $draw,
            "recordsTotal" => $gapok->num_rows(),
            "recordsFiltered" => $gapok->num_rows(),
            "data" => $data
        );
        $this->output->set_output(json_encode($output));
    }

    // Read
    public function dialog_salary_gapok_harian()
    {
        $session = $this->session->userdata('username');
        if (empty($session)) {
            redirect('admin/');
        }
        $data['title'] = $this->Core_model->site_title();
        $id            = $this->input->get('field_id');
        $result        = $this->Employees_model->read_single_salary_gapok($id);
        $data = array(
            'salary_gapok_id'  => $result[0]->salary_gapok_id,
            'employee_id'      => $result[0]->employe_id,
            'gapok_title'      => $result[0]->gapok_title,
            'gapok_date'       => $result[0]->gapok_date,
            'gapok_amount'     => $result[0]->gapok_amount
        );

        if (!empty($session)) {
            $this->load->view('admin/payroll/dialog_harian_detail', $data);
        } else {
            redirect('admin/');
        }
    }

    // Tambah
    public function employee_gapok_option_harian()
    {
        if ($this->input->post('type') == 'employee_update_gapok') {
            /* Define return | here result is used to return user data and error for error message */
            $Return = array('result' => '', 'error' => '', 'csrf_hash' => '');
            $Return['csrf_hash'] = $this->security->get_csrf_hash();
            if ($this->input->post('title') === '') {
                $Return['error'] = $this->lang->line('xin_error_title');
            } else if ($this->input->post('amount') === '') {
                $Return['error'] = $this->lang->line('xin_error_amount_field');
            }

            if ($Return['error'] != '') {
                $this->output($Return);
            }
            $data = array(
                'gapok_date'   => $this->input->post('date'),
                'gapok_title'  => $this->input->post('title'),
                'gapok_amount' => $this->input->post('amount'),
                'employe_id'       => $this->input->post('user_id')
            );
            $result = $this->Employees_model->add_salary_gapok($data);
            if ($result == TRUE) {
                $Return['result'] = 'Gaji Pokok Berhasil Ditambahkan';
            } else {
                $Return['error'] = $this->lang->line('xin_error_msg');
            }
            $this->output($Return);
            exit;
        }
    }

    // Edit
    public function update_gapok_info_harian()
    {

        if ($this->input->post('type') == 'e_salary_gapok_info') {
            /* Define return | here result is used to return user data and error for error message */
            $Return = array('result' => '', 'error' => '', 'csrf_hash' => '');
            $Return['csrf_hash'] = $this->security->get_csrf_hash();

            /* Server side PHP input validation */
            if ($this->input->post('title') === '') {
                $Return['error'] = $this->lang->line('xin_error_title');
            } else if ($this->input->post('amount') === '') {
                $Return['error'] = $this->lang->line('xin_error_amount_field');
            }

            if ($Return['error'] != '') {
                $this->output($Return);
            }

            $data = array(
                'gapok_date' => $this->input->post('date'),
                'gapok_title' => $this->input->post('title'),
                'gapok_amount' => $this->input->post('amount')
            );
            $e_field_id = $this->input->post('e_field_id');
            $result = $this->Employees_model->salary_gapok_update_record($data, $e_field_id);
            if ($result == TRUE) {
                $Return['result'] = 'Gaji Pokok Berhasil Diperbarui';
            } else {
                $Return['error'] = $this->lang->line('xin_error_msg');
            }
            $this->output($Return);
            exit;
        }
    }

    // Hapus
    public function delete_all_gapok_harian()
    {

        if ($this->input->post('data') == 'delete_record') {
            /* Define return | here result is used to return user data and error for error message */
            $Return = array('result' => '', 'error' => '', 'csrf_hash' => '');
            $Return['csrf_hash'] = $this->security->get_csrf_hash();
            $id = $this->uri->segment(4);
            $result = $this->Employees_model->delete_gapok_record($id);
            if (isset($id)) {
                $Return['result'] = 'Gaji Pokok Berhasil Dihapus';
            } else {
                $Return['error'] = $this->lang->line('xin_error_msg');
            }
            $this->output($Return);
        }
    }

    // =======================================================================================
    // 04. INSENTIF HARIAN
    // =======================================================================================

    public function salary_all_commissions_harian()
    {
        //set data
        $data['title'] = $this->Core_model->site_title();
        $session = $this->session->userdata('username');
        if (!empty($session)) {
            $this->load->view("admin/payroll/harian_detail", $data);
        } else {
            redirect('admin/');
        }
        // Datatables Variables
        $draw   = intval($this->input->get("draw"));
        $start  = intval($this->input->get("start"));
        $length = intval($this->input->get("length"));

        $id = $this->uri->segment(4);
        $commissions = $this->Employees_model->set_employee_commissions($id);

        $data = array();

        foreach ($commissions->result() as $r) {

            $data[] = array(
                '<span data-toggle="tooltip" data-placement="top" title="' . $this->lang->line('xin_edit') . '">
                                <button type="button" class="btn icon-btn btn-xs btn-default waves-effect waves-light" data-toggle="modal" data-target=".edit-modal-data" data-field_id="' . $r->salary_commissions_id . '" data-field_type="salary_commissions_harian">
                                    <span class="fa fa-pencil"></span> Edit
                                </button>
                            </span>
                            <span data-toggle="tooltip" data-placement="top" title="' . $this->lang->line('xin_delete') . '">
                                <button type="button" class="btn icon-btn btn-xs btn-danger waves-effect waves-light delete" data-toggle="modal" data-target=".delete-modal" data-record-id="' . $r->salary_commissions_id . '" data-token_type="all_commissions_harian">
                                    <span class="fa fa-trash"></span>
                                </button>
                            </span>',
                date("d-m-Y", strtotime($r->commission_date)),
                $r->commission_title,
                $this->Core_model->currency_sign($r->commission_amount)
            );
        }

        $output = array(
            "draw" => $draw,
            "recordsTotal" => $commissions->num_rows(),
            "recordsFiltered" => $commissions->num_rows(),
            "data" => $data
        );
        $this->output->set_output(json_encode($output));
    }

    // Read
    public function dialog_salary_commissions_harian()
    {
        $session = $this->session->userdata('username');
        if (empty($session)) {
            redirect('admin/');
        }
        $data['title'] = $this->Core_model->site_title();
        $id            = $this->input->get('field_id');
        $result        = $this->Employees_model->read_single_salary_commissions($id);
        $data = array(
            'salary_commissions_id' => $result[0]->salary_commissions_id,
            'employee_id'           => $result[0]->employee_id,
            'commission_title'      => $result[0]->commission_title,
            'commission_date'       => $result[0]->commission_date,
            'commission_amount'     => $result[0]->commission_amount
        );

        if (!empty($session)) {
            $this->load->view('admin/payroll/dialog_harian_detail', $data);
        } else {
            redirect('admin/');
        }
    }

    // Tambah
    public function employee_commissions_option_harian()
    {
        if ($this->input->post('type') == 'employee_update_commissions') {
            /* Define return | here result is used to return user data and error for error message */
            $Return = array('result' => '', 'error' => '', 'csrf_hash' => '');
            $Return['csrf_hash'] = $this->security->get_csrf_hash();
            if ($this->input->post('title') === '') {
                $Return['error'] = $this->lang->line('xin_error_title');
            } else if ($this->input->post('amount') === '') {
                $Return['error'] = $this->lang->line('xin_error_amount_field');
            }

            if ($Return['error'] != '') {
                $this->output($Return);
            }
            $data = array(
                'commission_date'   => $this->input->post('date'),
                'commission_title'  => $this->input->post('title'),
                'commission_amount' => $this->input->post('amount'),
                'employee_id'       => $this->input->post('user_id')
            );
            $result = $this->Employees_model->add_salary_commissions($data);
            if ($result == TRUE) {
                $Return['result'] = 'Insentif Berhasil Ditambahkan';
            } else {
                $Return['error'] = $this->lang->line('xin_error_msg');
            }
            $this->output($Return);
            exit;
        }
    }

    // Edit
    public function update_commissions_info_harian()
    {

        if ($this->input->post('type') == 'e_salary_commissions_info') {
            /* Define return | here result is used to return user data and error for error message */
            $Return = array('result' => '', 'error' => '', 'csrf_hash' => '');
            $Return['csrf_hash'] = $this->security->get_csrf_hash();

            /* Server side PHP input validation */
            if ($this->input->post('title') === '') {
                $Return['error'] = $this->lang->line('xin_error_title');
            } else if ($this->input->post('amount') === '') {
                $Return['error'] = $this->lang->line('xin_error_amount_field');
            }

            if ($Return['error'] != '') {
                $this->output($Return);
            }

            $data = array(
                'commission_date' => $this->input->post('date'),
                'commission_title' => $this->input->post('title'),
                'commission_amount' => $this->input->post('amount')
            );
            $e_field_id = $this->input->post('e_field_id');
            $result = $this->Employees_model->salary_commissions_update_record($data, $e_field_id);
            if ($result == TRUE) {
                $Return['result'] = 'Insentif Berhasil Diperbarui';
            } else {
                $Return['error'] = $this->lang->line('xin_error_msg');
            }
            $this->output($Return);
            exit;
        }
    }

    // Hapus
    public function delete_all_commissions_harian()
    {

        if ($this->input->post('data') == 'delete_record') {
            /* Define return | here result is used to return user data and error for error message */
            $Return = array('result' => '', 'error' => '', 'csrf_hash' => '');
            $Return['csrf_hash'] = $this->security->get_csrf_hash();
            $id = $this->uri->segment(4);
            $result = $this->Employees_model->delete_commission_record($id);
            if (isset($id)) {
                $Return['result'] = 'Insentif Berhasil Dihapus';
            } else {
                $Return['error'] = $this->lang->line('xin_error_msg');
            }
            $this->output($Return);
        }
    }

    // =======================================================================================
    // 05. BPJS KES & TK
    // =======================================================================================

    public function salary_all_statutory_deductions_harian()
    {
        //set data
        $data['title'] = $this->Core_model->site_title();
        $session = $this->session->userdata('username');
        if (!empty($session)) {
            $this->load->view("admin/payroll/harian_detail", $data);
        } else {
            redirect('admin/');
        }
        // Datatables Variables
        $draw   = intval($this->input->get("draw"));
        $start  = intval($this->input->get("start"));
        $length = intval($this->input->get("length"));

        $id = $this->uri->segment(4);
        $statutory_deductions = $this->Employees_model->set_employee_statutory_deductions($id);

        $data = array();

        foreach ($statutory_deductions->result() as $r) {
            if ($r->statutory_options == 1) {
                $sd_opt = $this->lang->line('xin_sd_ssc_title');
            } else if ($r->statutory_options == 2) {
                $sd_opt = $this->lang->line('xin_sd_phic_title');
            } else if ($r->statutory_options == 3) {
                $sd_opt = $this->lang->line('xin_sd_hdmf_title');
            } else if ($r->statutory_options == 4) {
                $sd_opt = $this->lang->line('xin_sd_wht_title');
            } else {
                $sd_opt = $this->lang->line('xin_sd_other_sd_title');
            }
            $data[] = array(
                '<span data-toggle="tooltip" data-placement="top" title="' . $this->lang->line('xin_edit') . '">
                           <button type="button" class="btn icon-btn btn-xs btn-default waves-effect waves-light" data-toggle="modal" data-target=".edit-modal-data" data-field_id="' . $r->statutory_deductions_id . '" data-field_type="salary_statutory_deductions_harian">
                              <span class="fa fa-pencil"></span>
                           </button>
                        </span>

                        <span data-toggle="tooltip" data-placement="top" title="' . $this->lang->line('xin_delete') . '">
                             <button type="button" class="btn icon-btn btn-xs btn-danger waves-effect waves-light delete" data-toggle="modal" data-target=".delete-modal" data-record-id="' . $r->statutory_deductions_id . '" data-token_type="all_statutory_deductions_harian">
                                  <span class="fa fa-trash"></span>
                             </button>
                        </span>',

                $this->Core_model->set_date_format($r->deduction_date),
                $sd_opt,
                $r->deduction_title,
                $this->Core_model->currency_sign($r->deduction_amount)

            );
        }

        $output = array(
            "draw" => $draw,
            "recordsTotal" => $statutory_deductions->num_rows(),
            "recordsFiltered" => $statutory_deductions->num_rows(),
            "data" => $data
        );
        $this->output->set_output(json_encode($output));
    }

    // Read
    public function dialog_salary_statutory_deductions_harian()
    {

        $session = $this->session->userdata('username');
        if (empty($session)) {
            redirect('admin/');
        }
        $data['title'] = $this->Core_model->site_title();
        $id            = $this->input->get('field_id');
        $result        = $this->Employees_model->read_single_salary_statutory_deduction($id);
        $data          = array(
            'statutory_deductions_id' => $result[0]->statutory_deductions_id,
            'deduction_date'          => $result[0]->deduction_date,
            'employee_id'             => $result[0]->employee_id,
            'deduction_title'         => $result[0]->deduction_title,
            'deduction_amount'        => $result[0]->deduction_amount,
            'statutory_options'       => $result[0]->statutory_options
        );

        if (!empty($session)) {
            $this->load->view('admin/payroll/dialog_harian_detail', $data);
        } else {
            redirect('admin/');
        }
    }

    // Tambah
    public function set_statutory_deductions_harian()
    {
        if ($this->input->post('type') == 'statutory_deductions_info_harian') {
            /* Define return | here result is used to return user data and error for error message */
            $Return = array('result' => '', 'error' => '', 'csrf_hash' => '');
            $Return['csrf_hash'] = $this->security->get_csrf_hash();
            if ($this->input->post('title') === '') {
                $Return['error'] = $this->lang->line('xin_error_title');
            } else if ($this->input->post('amount') === '') {
                $Return['error'] = $this->lang->line('xin_error_amount_field');
            } else if ($this->input->post('deduction_date') === '') {
                $Return['error'] = 'Tanggal BPJS Belum Diinput';
            }

            if ($Return['error'] != '') {
                $this->output($Return);
            }
            $data = array(
                'deduction_date' => $this->input->post('deduction_date'),
                'deduction_title' => $this->input->post('title'),
                'deduction_amount' => $this->input->post('amount'),
                'statutory_options' => $this->input->post('statutory_options'),
                'employee_id' => $this->input->post('user_id')
            );
            $result = $this->Employees_model->add_salary_statutory_deductions($data);
            if ($result == TRUE) {
                $Return['result'] = 'BPJS Berhasil Ditambahkan';
            } else {
                $Return['error'] = $this->lang->line('xin_error_msg');
            }
            $this->output($Return);
            exit;
        }
    }

    // Edit
    public function update_statutory_deductions_info_harian()
    {

        if ($this->input->post('type') == 'e_salary_statutory_deductions_info_harian') {

            /* Define return | here result is used to return user data and error for error message */
            $Return = array('result' => '', 'error' => '', 'csrf_hash' => '');
            $Return['csrf_hash'] = $this->security->get_csrf_hash();

            /* Server side PHP input validation */
            if ($this->input->post('title') === '') {
                $Return['error'] = $this->lang->line('xin_error_title');
            } else if ($this->input->post('amount') === '') {
                $Return['error'] = $this->lang->line('xin_error_amount_field');
            } else if ($this->input->post('deduction_date') === '') {
                $Return['error'] = 'Tanggal BPJS Belum Diinput';
            }

            if ($Return['error'] != '') {
                $this->output($Return);
            }

            $data = array(
                'deduction_date' => $this->input->post('deduction_date'),
                'deduction_title' => $this->input->post('title'),
                'deduction_amount' => $this->input->post('amount'),
                'statutory_options' => $this->input->post('statutory_options')
            );
            $e_field_id = $this->input->post('e_field_id');
            $result = $this->Employees_model->salary_statutory_deduction_update_record($data, $e_field_id);
            if ($result == TRUE) {
                $Return['result'] = 'BPJS Berhasil Diperbarui';
            } else {
                $Return['error'] = $this->lang->line('xin_error_msg');
            }
            $this->output($Return);
            exit;
        }
    }

    // Hapus
    public function delete_all_statutory_deductions_harian()
    {

        if ($this->input->post('data') == 'delete_record') {
            /* Define return | here result is used to return user data and error for error message */
            $Return = array('result' => '', 'error' => '', 'csrf_hash' => '');
            $Return['csrf_hash'] = $this->security->get_csrf_hash();
            $id = $this->uri->segment(4);
            $result = $this->Employees_model->delete_statutory_deductions_record($id);
            if (isset($id)) {
                $Return['result'] = 'BPJS Berhasil Dihapus';
            } else {
                $Return['error'] = $this->lang->line('xin_error_msg');
            }
            $this->output($Return);
        }
    }

    // =======================================================================================
    // 05. POTONG HARIAN
    // =======================================================================================

    public function salary_all_minus_harian()
    {
        //set data
        $data['title'] = $this->Core_model->site_title();
        $session = $this->session->userdata('username');
        if (!empty($session)) {
            $this->load->view("admin/payroll/harian_detail", $data);
        } else {
            redirect('admin/');
        }
        // Datatables Variables
        $draw   = intval($this->input->get("draw"));
        $start  = intval($this->input->get("start"));
        $length = intval($this->input->get("length"));

        $id = $this->uri->segment(4);
        $minus = $this->Employees_model->set_employee_minus($id);

        $data = array();

        foreach ($minus->result() as $r) {

            $data[] = array(
                '
                            <span data-toggle="tooltip" data-placement="top" title="' . $this->lang->line('xin_delete') . '">
                                <button type="button" class="btn icon-btn btn-xs btn-danger waves-effect waves-light delete" data-toggle="modal" data-target=".delete-modal" data-record-id="' . $r->salary_minus_id . '" data-token_type="all_minus_harian">
                                    <span class="fa fa-trash"></span> Hapus
                                </button>
                            </span>',

                date("d-m-Y", strtotime($r->minus_date)),
                $r->minus_title,
                $this->Core_model->currency_sign($r->minus_amount)
            );
        }

        $output = array(
            "draw" => $draw,
            "recordsTotal" => $minus->num_rows(),
            "recordsFiltered" => $minus->num_rows(),
            "data" => $data
        );
        $this->output->set_output(json_encode($output));
    }

    // Read
    public function dialog_salary_minus_harian()
    {
        $session = $this->session->userdata('username');
        if (empty($session)) {
            redirect('admin/');
        }
        $data['title'] = $this->Core_model->site_title();
        $id            = $this->input->get('field_id');
        $result        = $this->Employees_model->read_single_salary_minus($id);
        $data = array(
            'salary_minus_id'  => $result[0]->salary_minus_id,
            'employee_id'      => $result[0]->employee_id,
            'minus_title'      => $result[0]->minus_title,
            'minus_date'       => $result[0]->minus_date,
            'minus_amount'     => $result[0]->minus_amount
        );

        if (!empty($session)) {
            $this->load->view('admin/payroll/dialog_harian_detail', $data);
        } else {
            redirect('admin/');
        }
    }

    // Tambah
    public function employee_minus_option_harian()
    {
        if ($this->input->post('type') == 'employee_update_minus') {
            /* Define return | here result is used to return user data and error for error message */
            $Return = array('result' => '', 'error' => '', 'csrf_hash' => '');
            $Return['csrf_hash'] = $this->security->get_csrf_hash();
            if ($this->input->post('title') === '') {
                $Return['error'] = $this->lang->line('xin_error_title');
            } else if ($this->input->post('amount') === '') {
                $Return['error'] = $this->lang->line('xin_error_amount_field');
            }

            if ($Return['error'] != '') {
                $this->output($Return);
            }
            $data = array(
                'minus_date'   => $this->input->post('minus_date'),
                'minus_title'  => $this->input->post('minus_title'),
                'minus_amount' => $this->input->post('minus_amount'),
                'employee_id'  => $this->input->post('user_id')
            );
            $result = $this->Employees_model->add_salary_minus($data);
            if ($result == TRUE) {
                $Return['result'] = 'Pemotong Gaji Harian Berhasil Ditambahkan';
            } else {
                $Return['error'] = $this->lang->line('xin_error_msg');
            }
            $this->output($Return);
            exit;
        }
    }

    // Edit
    public function update_minus_info_harian()
    {

        if ($this->input->post('type') == 'e_salary_minus_info') {
            /* Define return | here result is used to return user data and error for error message */
            $Return = array('result' => '', 'error' => '', 'csrf_hash' => '');
            $Return['csrf_hash'] = $this->security->get_csrf_hash();

            /* Server side PHP input validation */
            if ($this->input->post('title') === '') {
                $Return['error'] = $this->lang->line('xin_error_title');
            } else if ($this->input->post('amount') === '') {
                $Return['error'] = $this->lang->line('xin_error_amount_field');
            }

            if ($Return['error'] != '') {
                $this->output($Return);
            }

            $data = array(
                'minus_date'   => $this->input->post('minus_date'),
                'minus_title'  => $this->input->post('minus_title'),
                'minus_amount' => $this->input->post('minus_amount')
            );
            $e_field_id = $this->input->post('e_field_id');
            $result     = $this->Employees_model->salary_minus_update_record($data, $e_field_id);
            if ($result == TRUE) {
                $Return['result'] = 'Pemotong Gaji Harian Berhasil Diperbarui';
            } else {
                $Return['error'] = $this->lang->line('xin_error_msg');
            }
            $this->output($Return);
            exit;
        }
    }

    // Hapus
    public function delete_all_minus_harian()
    {
        if ($this->input->post('data') == 'delete_record') {
            /* Define return | here result is used to return user data and error for error message */
            $Return = array('result' => '', 'error' => '', 'csrf_hash' => '');
            $Return['csrf_hash'] = $this->security->get_csrf_hash();
            $id = $this->uri->segment(4);
            $result = $this->Employees_model->delete_minus_record($id);
            if (isset($id)) {
                $Return['result'] = 'Pemotong Gaji Harian Berhasil Dihapus';
            } else {
                $Return['error'] = $this->lang->line('xin_error_msg');
            }
            $this->output($Return);
        }
    }

    // ===============================================================================================
    // 03. PROSES => GAJI BORONGAN
    // ===============================================================================================

    // =======================================================================================
    // TABEL : GAJI BORONGAN
    // =======================================================================================

    // generate payslips
    public function borongan()
    {
        $session = $this->session->userdata('username');
        if (empty($session)) {
            redirect('admin/');
        }
        $data['title']       = 'Proses Gaji Borongan | ' . $this->Core_model->site_title();
        $data['desc']        = 'PROSES : Gaji Borongan';
        $data['icon']        = '<i class="fa fa-money"></i>';
        $data['breadcrumbs'] = 'Proses Gaji Borongan ';
        $data['path_url']    = 'borongan';

        $data['all_companies']     = $this->Company_model->get_company();
        $data['all_bulan_gaji']    = $this->Core_model->all_bulan_status_payroll();

        $role_resources_ids = $this->Core_model->user_role_resource();
        if (in_array('1031', $role_resources_ids)) {
            if (!empty($session)) {
                $data['subview'] = $this->load->view("admin/payroll/borongan", $data, TRUE);
                $this->load->view('admin/layout/layout_main', $data); //page load
            } else {
                redirect('admin/');
            }
        } else {
            redirect('admin/dashboard');
        }
    }

    public function payslip_list_borongan()
    {

        $data['title'] = $this->Core_model->site_title();
        $session = $this->session->userdata('username');

        if (!empty($session)) {
            $this->load->view("admin/payroll/borongan", $data);
        } else {
            redirect('admin/');
        }
        // Datatables Variables
        $draw   = intval($this->input->get("draw"));
        $start  = intval($this->input->get("start"));
        $length = intval($this->input->get("length"));

        // date and employee id/company id
        $start_date = $this->input->get("start_date");
        $end_date = $this->input->get("end_date");


        $role_resources_ids = $this->Core_model->user_role_resource();

        $user_info          = $this->Core_model->read_user_info($session['user_id']);
        $system = $this->Core_model->read_setting_info(1);

        $payslip = $this->Payroll_model->get_comp_template_borongan($this->input->get("company_id"), $this->input->get("workstation_id"));

        $data   = array();
        $no     = 1;

        foreach ($payslip->result() as $r) {

            // ====================================================================================================================
            // DATA KARYAWAN
            // ====================================================================================================================

            // Karyawan NIP
            $emp_id = $r->employee_id;

            // grade
            $employee = $this->Core_model->read_user_info_data($r->user_id);
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

            // Karyawan Workstation
            $workstation = $this->Core_model->read_designation_workstation_info($employee_designation_id);
            if (!is_null($workstation)) {
                $workstation_name = $workstation[0]->workstation_name;
                $workstation_id   = $workstation[0]->workstation_id;
            } else {
                $workstation_name = '<span class="badge bg-red"> ? </span>';
                $workstation_id   = '';
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
                $emp_status_name = '<span class="badge bg-red"> ? </span>';
            }

            // Karyawan Masa kerja
            date_default_timezone_set("Asia/Jakarta");

            $tanggal1 = new DateTime($employee_date_of_joining);
            $tanggal2 = new DateTime($end_date);

            if ($tanggal2->diff($tanggal1)->y == 0) {
                $selisih = $tanggal2->diff($tanggal1)->m . ' bln';
            } else {
                $selisih = $tanggal2->diff($tanggal1)->y . ' thn' . ' ' . $tanggal2->diff($tanggal1)->m . ' bln';
            }


            // ====================================================================================================================
            // KOMPONEN GAJI - TAMBAH
            // ====================================================================================================================
            // ****************************************************************************************************************
            // TETAP
            // ****************************************************************************************************************

            // ****************************************************************************************************************
            // TIDAK TETAP
            // ****************************************************************************************************************
            // ============================================================================================================
            // 1: Tambahan
            // ============================================================================================================

            $commissions       = $this->Employees_model->read_payroll_salary_commissions($employee_user_id, $start_date, $end_date);
            $count_commissions = $this->Employees_model->count_employee_commissions($employee_user_id, $start_date, $end_date);
            $commissions_amount = 0;
            if ($count_commissions > 0) {
                foreach ($commissions as $sl_salary_commissions) {
                    $commissions_amount += $sl_salary_commissions->commission_amount;
                }
            } else {
                $commissions_amount = 0;
            }

            // ============================================================================================================
            // 2: Diperbantukan
            // ============================================================================================================

            $commissions_help       = $this->Employees_model->read_payroll_salary_commissions_help($employee_user_id, $start_date, $end_date);
            $count_commissions_help = $this->Employees_model->count_employee_commissions_help($employee_user_id, $start_date, $end_date);
            $commissions_help_amount = 0;
            if ($count_commissions_help > 0) {
                foreach ($commissions_help as $sl_salary_commissions_help) {
                    $commissions_help_amount += $sl_salary_commissions_help->commission_amount;
                }
            } else {
                $commissions_help_amount = 0;
            }

            // ====================================================================================================================
            // KOMPONEN GAJI - KURANG
            // ====================================================================================================================

            // ****************************************************************************************************************
            // TETAP
            // ****************************************************************************************************************

            // ============================================================================================================
            // 1: BPJS TK
            // ============================================================================================================

            $count_bpjs_tk = $this->Employees_model->count_employee_bpjs_tk($employee_user_id,$start_date, $end_date);
            $bpjs_tk = $this->Employees_model->set_employee_bpjs_tk($employee_user_id,$start_date, $end_date);
            $bpjs_tk_amount = 0;
            if ($count_bpjs_tk > 0) {
                foreach ($bpjs_tk->result() as $sl_salary_bpjs_tk) {
                    $bpjs_tk_amount += $sl_salary_bpjs_tk->deduction_amount;
                }
            } else {
                $bpjs_tk_amount = 0;
            }

            // ============================================================================================================
            // 2: BPJS KES
            // ============================================================================================================

            $count_bpjs_kes = $this->Employees_model->count_employee_bpjs_kes($employee_user_id,$start_date, $end_date);
            $bpjs_kes = $this->Employees_model->set_employee_bpjs_kes($employee_user_id,$start_date, $end_date);
            $bpjs_kes_amount = 0;
            if ($count_bpjs_kes > 0) {
                foreach ($bpjs_kes->result() as $sl_salary_bpjs_kes) {
                    $bpjs_kes_amount += $sl_salary_bpjs_kes->deduction_amount;
                }
            } else {
                $bpjs_kes_amount = 0;
            }

            // ****************************************************************************************************************
            // TIDAK TETAP
            // ****************************************************************************************************************
            // ============================================================================================================
            // 1: Minus
            // ============================================================================================================

            $minus       = $this->Employees_model->read_payroll_salary_minus($employee_user_id, $start_date, $end_date);
            $count_minus = $this->Employees_model->count_employee_minus($employee_user_id, $start_date, $end_date);
            $minus_amount = 0;
            if ($count_minus > 0) {
                foreach ($minus as $sl_salary_minus) {
                    $minus_amount += $sl_salary_minus->minus_amount;
                }
            } else {
                $minus_amount = 0;
            }

            // ============================================================================================================
            // 3: biaya
            // ============================================================================================================

            $cek_biaya      = $this->Timesheet_model->get_produktifitas_rekap($emp_id, $start_date, $end_date);
            // var_dump($cek_biaya);return;
            if (!is_null($cek_biaya)) {
                $jum_biaya   = $cek_biaya[0]->rekap_amount;
                $jum_gram    = $cek_biaya[0]->rekap_gram;
                $jum_day     = $cek_biaya[0]->rekap_day;
                $jum_insentif     = $cek_biaya[0]->rekap_insentif;
            } else {
                $jum_biaya   = 0;
                $jum_gram    = 0;
                $jum_day    = 0;
                $jum_insentif    = 0;
            }
            // Bahan Baku, Cuci Kotor, Cuci Bersih, Dry, Wrapping, Barcode, Packing
            // if ($workstation_id == 4 || $workstation_id == 12 ) {

            // 	$jumlah_biaya = $jum_biaya;
            // }

            // else {

            // 	if ($jum_biaya == 0) {

            // 		$jumlah_biaya = 0;
            // 	}

            // 	else if ($jum_biaya > 0 && $jum_biaya <= 65000) {

            // 		$jumlah_biaya = $jumlah_hadir*65000;

            // 	}

            // 	else if ($jum_biaya > 65000) {

            // 		$jumlah_biaya = $jum_biaya;
            // 	}

            // }
            $jumlah_hadir = $jum_day;
            $jumlah_gram = $jum_gram;

            $jumlah_biaya = $jum_biaya;
            $jumlah_insentif = $jum_insentif;

            // ====================================================================================================================
            // HITUNG
            // ====================================================================================================================

            $tanggal_awal       = date("Y-m-d", strtotime($start_date));
            $tanggal_akhir      = date("Y-m-d", strtotime($end_date));
            $tanggal_potong     = date("Y-m-20", strtotime($start_date));

            if ($tanggal_potong >= $tanggal_awal and $tanggal_potong <= $tanggal_akhir) {

                $pot_bpjs_kes_amount = $bpjs_kes_amount;

                $tes = 'Potong';
            } else {

                $pot_bpjs_kes_amount = 0;

                $tes = 'Tidak Potong';
            }

            if ($bpjs_kes_amount == 0) {
                $ada_bpjs_kes = '<span class="merah">(BPJS Kes Belum Diinput)</span>';
            } else {
                $ada_bpjs_kes = '';
            }

            $info_potong       = $tanggal_awal . ' s/d ' . $tanggal_akhir . ' => ' . $tanggal_potong . ' => ' . $tes . ' BPJS Kesehatan ' . $ada_bpjs_kes;

            $total_net_salary = ($commissions_amount + $commissions_help_amount + $jumlah_biaya +$jumlah_insentif) - ($minus_amount + $bpjs_tk_amount + $pot_bpjs_kes_amount);

            // ====================================================================================================================
            // PERIKSA PEMBAYARAN
            // ====================================================================================================================

            $payment_check = $this->Payroll_model->read_make_payment_payslip_check_borongan($employee_user_id, $start_date, $end_date);

            // echo "<pre>";
            // 	print_r( $this->db->last_query() );
            // 	echo "</pre>";
            // 	die();
            if ($payment_check->num_rows() > 1) {

                $make_payment = $this->Payroll_model->read_make_payment_payslip_borongan($employee_user_id, $start_date, $end_date);


                // echo "<pre>";
                // print_r( $this->db->last_query() );
                // echo "</pre>";
                // die();

                $view_url     = site_url() . 'admin/payroll/payslip_harian/id/' . $make_payment[0]->payslip_key;

                $status       = '<span class="label label-success">' . $this->lang->line('xin_payroll_paid') . '</span>';

                if (in_array('1034', $role_resources_ids)) {

                    $mpay = '<span data-toggle="tooltip" data-placement="top" title="' . $this->lang->line('xin_payroll_view_payslip') . '">
                                                <a target ="_blank" href="' . $view_url . '">
                                                    <button type="button" class="btn icon-btn btn-xs btn-warning waves-effect waves-light">
                                                        <span class="fa fa-money"></span>
                                                    </button>
                                                </a>
                                            </span>';
                } else {
                    $mpay = '';
                }

                if (in_array('1035', $role_resources_ids)) {
                    $dpay  = '<span data-toggle="tooltip" data-placement="top" title="' . $this->lang->line('xin_download') . '">
                                                <a href="' . site_url() . 'admin/payroll/pdf_create_borongan/p/' . $make_payment[0]->payslip_key . '">
                                                    <button type="button" class="btn icon-btn btn-xs btn-success waves-effect waves-light">
                                                        <span class="fa fa-download"></span>
                                                    </button>
                                                </a>
                                            </span>';
                } else {
                    $dpay = '';
                }

                if (in_array('10331', $role_resources_ids)) {
                    $delete = '<span data-toggle="tooltip" data-placement="top" title="' . $this->lang->line('xin_delete') . '">
                                                <button type="button" class="btn icon-btn btn-xs btn-danger waves-effect waves-light" data-toggle="modal" data-target=".del_borongan_pay" data-payslip_id="' .  $make_payment[0]->payslip_id . '" data-start_date="' . $start_date . '" data-end_date="' . $end_date . '" data-company_id="' . $this->input->get("company_id") . '">
                                                    <span class="fa fa-trash"></span>
                                                </button>
                                            </span>';
                } else {
                    $delete = '';
                }

                if (in_array('1033', $role_resources_ids)) {
                    $edit_opt         = '<button style ="background-color: #ccc;border-color: #eee;" disabled type="button" class="btn icon-btn btn-xs btn-success waves-effect waves-light" title="Gaji Belum Diproses">
                                                        <span class="fa fa-pencil"></span>
                                                    </button> ';
                } else {
                    $edit_opt = '';
                }

                if (in_array('1036', $role_resources_ids)) {
                    $bpay = '<button style ="background-color: #ccc;border-color: #eee;" disabled type="button" class="btn icon-btn btn-xs btn-warning " title="Gaji Belum Diproses">
                                                        <span class="fa fa-save"></span>
                                                    </button>';
                } else {
                    $bpay = '';
                }
            } else if ($payment_check->num_rows() > 0) {

                $make_payment = $this->Payroll_model->read_make_payment_payslip_borongan_company($employee_user_id, $start_date, $end_date);


                // echo "<pre>";
                // print_r( $this->db->last_query() );
                // echo "</pre>";
                // die();

                $view_url     = site_url() . 'admin/payroll/payslip_borongan/id/' . $make_payment[0]->payslip_key;

                $status       = '<span class="label label-success">' . $this->lang->line('xin_payroll_paid') . '</span>';

                if (in_array('1034', $role_resources_ids)) {
                    $mpay = '<span data-toggle="tooltip" data-placement="top" title="' . $this->lang->line('xin_payroll_view_payslip') . '">
                                                <a target ="_blank" href="' . $view_url . '">
                                                    <button type="button" class="btn icon-btn btn-xs btn-warning waves-effect waves-light">
                                                        <span class="fa fa-money"></span>
                                                    </button>
                                                </a>
                                            </span>';
                } else {
                    $mpay = '';
                }

                if (in_array('1035', $role_resources_ids)) {
                    $dpay = '<span data-toggle="tooltip" data-placement="top" title="' . $this->lang->line('xin_download') . '">
                                                <a href="' . site_url() . 'admin/payroll/pdf_create_borongan/p/' . $make_payment[0]->payslip_key . '">
                                                    <button type="button" class="btn icon-btn btn-xs btn-success waves-effect waves-light">
                                                        <span class="fa fa-download"></span>
                                                    </button>
                                                </a>
                                            </span>';
                } else {
                    $dpay = '';
                }

                if (in_array('10331', $role_resources_ids)) {
                    $delete = '<span data-toggle="tooltip" data-placement="top" title="' . $this->lang->line('xin_delete') . '">
                                                <button type="button" class="btn icon-btn btn-xs btn-danger waves-effect waves-light" data-toggle="modal" data-target=".del_borongan_pay" data-payslip_id="' .  $make_payment[0]->payslip_id . '" data-start_date="' . $start_date . '" data-end_date="' . $end_date . '" data-company_id="' . $this->input->get("company_id") . '">
                                                    <span class="fa fa-trash"></span>
                                                </button>
                                            </span>';
                } else {
                    $delete = '';
                }

                if (in_array('1033', $role_resources_ids)) {

                    $edit_opt         = '<button style ="background-color: #ccc;border-color: #eee;" disabled type="button" class="btn icon-btn btn-xs btn-success waves-effect waves-light" title="Gaji Belum Diproses">
                                                        <span class="fa fa-pencil"></span>
                                                    </button> ';
                } else {
                    $edit_opt = '';
                }

                if (in_array('1036', $role_resources_ids)) {
                    $bpay = '<button style ="background-color: #ccc;border-color: #eee;" disabled type="button" class="btn icon-btn btn-xs btn-warning " title="Gaji Belum Diproses">
                                                <span class="fa fa-save"></span>
                                            </button>';
                } else {
                    $bpay = '';
                }
            } else {

                $make_payment = $this->Payroll_model->read_make_payment_payslip_borongan($employee_user_id, $start_date, $end_date);


                $status = '<span class="label label-danger">' . $this->lang->line('xin_payroll_unpaid') . '</span>';

                if (in_array('1034', $role_resources_ids)) {

                    $mpay         = '<button style ="background-color: #ccc;border-color: #eee;" disabled type="button" class="btn icon-btn btn-xs btn-warning " title="Gaji Belum Diproses">
                                                        <span class="fa fa-money"></span>
                                                    </button> ';
                } else {
                    $mpay = '';
                }

                if (in_array('1035', $role_resources_ids)) {
                    $dpay         = '<button style ="background-color: #ccc;border-color: #eee;" disabled type="button" class="btn icon-btn btn-xs btn-success waves-effect waves-light" title="Gaji Belum Diproses">
                                                        <span class="fa fa-download"></span>
                                                    </button> ';
                } else {
                    $dpay = '';
                }

                if (in_array('10331', $role_resources_ids)) {
                    $delete = '<button style ="background-color: #ccc;border-color: #eee;" disabled type="button" class="btn icon-btn btn-xs btn-danger waves-effect waves-light" title="Hapus Belum Diproses">
                                                    <span class="fa fa-trash"></span>
                                                </button>';
                } else {
                    $delete = '';
                }

                if (in_array('1033', $role_resources_ids)) {
                    $edit_opt = ' <span data-toggle="tooltip" data-placement="top" title="' . $this->lang->line('xin_edit_komponen_gaji') . '">
                                                        <a target="_blank" href="' . site_url() . 'admin/payroll/borongan_detail/' . $employee_user_id . '/' . $start_date . '/' . $end_date . '">
                                                            <button type="button" class="btn icon-btn btn-xs btn-info waves-effect waves-light">
                                                                <span class="fa fa-pencil"></span>
                                                            </button>
                                                        </a>
                                                    </span>';
                } else {
                    $edit_opt = '';
                }

                if (in_array('1036', $role_resources_ids)) {
                    $bpay = '<span data-toggle="tooltip" data-placement="top" title="Simpan Gaji Per Karyawan">
                                                <button type="button" class="btn icon-btn btn-xs btn-success waves-effect waves-light" data-toggle="modal" data-target=".emo_borongan_pay" data-employee_id="' .  $employee_user_id . '" data-start_date="' . $start_date . '" data-end_date="' . $end_date . '" data-company_id="' . $this->input->get("company_id") . '">
                                                    <span class="fa fa-save"></span>
                                                </button>
                                            </span>';
                } else {
                    $bpay = '';
                }
            }

            //detail link
            $detail = '<span data-toggle="tooltip" data-placement="top" title="' . $this->lang->line('xin_view') . '">
                                            <button type="button" class="btn icon-btn btn-xs btn-warning waves-effect waves-light" data-toggle="modal" data-target=".payroll_template_modal_harian" data-employee_id="' . $employee_user_id . '">
                                                <span class="fa fa-eye"></span>
                                            </button>
                                        </span>';




            //action link
            $act = $mpay . $dpay . $delete . $edit_opt . $bpay;

            $data[] = array(
                $act,
                $no,
                $status,
                $start_date . ' s/d ' . $end_date,
                $emp_id,
                strtoupper($emp_name),
                strtoupper($designation_name),
                strtoupper($designation_name),
                date("d-m-Y", strtotime($employee_date_of_joining)),
                $selisih,
                $karyawan_status,
                $emp_status_name,
                $jenis_grade,

                number_format($jumlah_hadir, 0, ',', '.'),
                number_format($jumlah_gram, 0, ',', '.'),
                number_format($jumlah_biaya, 0, ',', '.'),
                number_format($jumlah_insentif, 0, ',', '.'),
                number_format($commissions_amount, 0, ',', '.'),
                number_format($commissions_help_amount, 0, ',', '.'),

                number_format($pot_bpjs_kes_amount, 0, ',', '.'),
                number_format($bpjs_tk_amount, 0, ',', '.'),
                number_format($minus_amount, 0, ',', '.'),

                number_format($total_net_salary, 0, ',', '.'),

                $rekening_name,
                $bank_name,
                $employee_email,
                $info_potong
            );
            $no++;
        }

        $output = array(
            "draw" => $draw,
            "recordsTotal" => $payslip->num_rows(),
            "recordsFiltered" => $payslip->num_rows(),
            "data" => $data
        );
        $this->output->set_output(json_encode($output));
        // echo json_encode($output);
        // exit();
    }

    // =======================================================================================
    // PROSES : SIMPAN
    // =======================================================================================

    public function add_pay_to_all_borongan()
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

            $company_id     = $this->input->post("company_id");
            $workstation_id = $this->input->post("workstation_id");
            $start_date     = $this->input->post("start_date");
            $end_date       = $this->input->post("end_date");


            // $bulan_id   = $this->input->post("bmonth_year");

            // echo "<pre>";
            // print_r($this->db->last_query());
            // print_r( $company_id );
            // print_r( $bulan_id );
            // echo "</pre>";
            // die();

            if ($company_id != 0) {
                $eresult = $this->Payroll_model->get_comp_template_borongan($company_id, $workstation_id);
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
                    $employee_id                   = $employee[0]->employee_id;
                    $employee_grade_type        = $employee[0]->grade_type;
                    $employee_wages_type        = $employee[0]->wages_type;
                    $employee_name              = $employee[0]->first_name . ' ' . $employee[0]->last_name;

                    $employee_email             = $employee[0]->email;

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
                    $employee_id                   = '';
                    $employee_grade_type        = '';
                    $employee_wages_type        = '';
                    $employee_name              = '';

                    $employee_email             = '';

                    $employee_company_id        = '';
                    $employee_location_id       = '';
                    $employee_department_id     = '';
                    $employee_designation_id    = '';
                    $employee_emp_status        = '';
                    $employee_date_of_joining   = '';
                    $employee_basic_salary      = '';
                    $employee_flag              = '';
                }

                // Rekening
                $rekening = $this->Employees_model->get_employee_bank_account_last($user_id);
                if (!is_null($rekening)) {
                    $rekening_name = $rekening[0]->account_number;
                    $bank_name     = $rekening[0]->bank_name;
                } else {
                    $rekening_name = '';
                    $bank_name     = '';
                }


                // ====================================================================================================================
                // JIKA ADA -> HAPUS
                // ====================================================================================================================

                $pay_count = $this->Payroll_model->read_make_payment_payslip_check_borongan_company_workstation($employee_user_id, $workstation_id, $start_date, $end_date);
                if ($pay_count->num_rows() > 0) {

                    $pay_val = $this->Payroll_model->read_make_payment_payslip_borongan_company_workstation($employee_user_id, $workstation_id, $start_date, $end_date);

                    $this->payslip_delete_all_borongan($pay_val[0]->payslip_id);
                }

                // ====================================================================================================================
                // (+) KOMPONEN GAJI - TAMBAH
                // ====================================================================================================================
                // ****************************************************************************************************************
                // >> TETAP
                // ****************************************************************************************************************

                // ****************************************************************************************************************
                // >> TIDAK TETAP
                // ****************************************************************************************************************
                // ============================================================================================================
                // 1: Tambahan
                // ============================================================================================================
                $commissions       = $this->Employees_model->read_payroll_salary_commissions($employee_user_id, $start_date, $end_date);
                $count_commissions = $this->Employees_model->count_employee_commissions($employee_user_id, $start_date, $end_date);
                $commissions_amount = 0;
                if ($count_commissions > 0) {
                    foreach ($commissions as $sl_salary_commissions) {
                        $commissions_amount += $sl_salary_commissions->commission_amount;
                    }
                } else {
                    $commissions_amount = 0;
                }

                // ============================================================================================================
                // 2: Diperbantukan
                // ============================================================================================================

                $commissions_help       = $this->Employees_model->read_payroll_salary_commissions_help($employee_user_id, $start_date, $end_date);
                $count_commissions_help = $this->Employees_model->count_employee_commissions_help($employee_user_id, $start_date, $end_date);
                $commissions_help_amount = 0;
                if ($count_commissions_help > 0) {
                    foreach ($commissions_help as $sl_salary_commissions_help) {
                        $commissions_help_amount += $sl_salary_commissions_help->commission_amount;
                    }
                } else {
                    $commissions_help_amount = 0;
                }

                // ====================================================================================================================
                // (-) KOMPONEN GAJI - KURANG
                // ====================================================================================================================


                // ****************************************************************************************************************
                // TETAP
                // ****************************************************************************************************************

                // ============================================================================================================
                // 1: BPJS TK
                // ============================================================================================================

                $count_bpjs_tk = $this->Employees_model->count_employee_bpjs_tk($employee_user_id,$start_date, $end_date);
                $bpjs_tk = $this->Employees_model->set_employee_bpjs_tk($employee_user_id,$start_date, $end_date);
                $bpjs_tk_amount = 0;
                if ($count_bpjs_tk > 0) {
                    foreach ($bpjs_tk->result() as $sl_salary_bpjs_tk) {
                        $bpjs_tk_amount += $sl_salary_bpjs_tk->deduction_amount;
                    }
                } else {
                    $bpjs_tk_amount = 0;
                }

                // ============================================================================================================
                // 2: BPJS KES
                // ============================================================================================================

                $count_bpjs_kes = $this->Employees_model->count_employee_bpjs_kes($employee_user_id,$start_date, $end_date);
                $bpjs_kes = $this->Employees_model->set_employee_bpjs_kes($employee_user_id,$start_date, $end_date);
                $bpjs_kes_amount = 0;
                if ($count_bpjs_kes > 0) {
                    foreach ($bpjs_kes->result() as $sl_salary_bpjs_kes) {
                        $bpjs_kes_amount += $sl_salary_bpjs_kes->deduction_amount;
                    }
                } else {
                    $bpjs_kes_amount = 0;
                }

                // ****************************************************************************************************************
                // >> TIDAK TETAP
                // ****************************************************************************************************************

                // ============================================================================================================
                // 1: Minus
                // ============================================================================================================

                $minus       = $this->Employees_model->read_payroll_salary_minus($employee_user_id, $start_date, $end_date);
                $count_minus = $this->Employees_model->count_employee_minus($employee_user_id, $start_date, $end_date);
                $minus_amount = 0;
                if ($count_minus > 0) {
                    foreach ($minus as $sl_salary_minus) {
                        $minus_amount += $sl_salary_minus->minus_amount;
                    }
                } else {
                    $minus_amount = 0;
                }

                // ============================================================================================================
                // 2: Produktifitas
                // ============================================================================================================

                // $cek_hadir      = $this->Timesheet_model->hitung_jumlah_produktifitas_kehadiran($employee_id,$start_date,$end_date);
                // if(!is_null($cek_hadir)){

                // 	if ( $cek_hadir[0]->jumlah_hari != ''){
                // 		 $jumlah_hadir   = $cek_hadir[0]->jumlah_hari;
                // 	} else {
                // 		 $jumlah_hadir   = 0;
                // 	}
                //                      } else {
                //                         $jumlah_hadir   = 0;
                //                      }

                // ============================================================================================================
                // 2: gram
                // ============================================================================================================

                // $cek_gram      = $this->Timesheet_model->hitung_jumlah_produktifitas_gram($employee_id,$start_date,$end_date);
                // if(!is_null($cek_gram)){
                // 	$jumlah_gram   = $cek_gram[0]->jumlah_gram;
                // } else {
                // 	$jumlah_gram   = 0;
                // }

                // ============================================================================================================
                // 3: biaya
                // ============================================================================================================
                // echo $employee_id;
                $cek_biaya      = $this->Timesheet_model->get_produktifitas_rekap($employee_id, $start_date, $end_date);
                // var_dump($cek_biaya);return;
                if (!is_null($cek_biaya)) {
                    $jum_biaya   = $cek_biaya[0]->rekap_amount;
                    $jum_gram    = $cek_biaya[0]->rekap_gram;
                    $jum_day     = $cek_biaya[0]->rekap_day;
                    $jum_insentif     = $cek_biaya[0]->rekap_insentif;
                } else {
                    $jum_biaya   = 0;
                    $jum_gram    = 0;
                    $jum_day    = 0;
                    $jum_insentif    = 0;
                }


                // $cek_biaya      = $this->Timesheet_model->hitung_jumlah_produktifitas_biaya($employee_nip,$start_date,$end_date);
                // if(!is_null($cek_biaya)){
                // 	$jum_biaya   = $cek_biaya[0]->jumlah_biaya;
                // } else {
                // 	$jum_biaya   = 0;
                // }
                // // Bahan Baku, Cuci Kotor, Cuci Bersih, Dry, Wrapping, Barcode, Packing


                // if ($workstation_id == 4 || $workstation_id == 12 ) {

                // 	$jumlah_biaya = $jum_biaya;
                // }

                // else {

                // 	if ($jum_biaya == 0) {

                // 		$jumlah_biaya = 0;
                // 	}

                // 	else if ($jum_biaya > 0 && $jum_biaya <= 65000) {

                // 		$jumlah_biaya = $jumlah_hadir*65000;

                // 	}

                // 	else if ($jum_biaya > 65000) {

                // 		$jumlah_biaya = $jumlah_hadir*$jum_biaya;
                // 	}

                // }

                $jumlah_hadir = $jum_day;
                $jumlah_gram = $jum_gram;

                $jumlah_biaya = $jum_biaya;
                $jumlah_insentif = $jum_insentif;


                // ====================================================================================================================
                // HITUNG
                // ====================================================================================================================


                $tanggal_awal       = date("Y-m-d", strtotime($start_date));
                $tanggal_akhir      = date("Y-m-d", strtotime($end_date));
                $tanggal_potong     = date("Y-m-20", strtotime($start_date));

                if ($tanggal_potong >= $tanggal_awal and $tanggal_potong <= $tanggal_akhir) {

                    $pot_bpjs_kes_amount = $bpjs_kes_amount;
                } else {

                    $pot_bpjs_kes_amount = 0;
                }

                $total_net_salary = ($commissions_amount + $commissions_help_amount + $jumlah_biaya+$jumlah_insentif) - ($minus_amount + $bpjs_tk_amount + $pot_bpjs_kes_amount);

                $jurl               = random_string('alnum', 40);

                // ====================================================================================================================
                // SIMPAN TABEL GAJI
                // ====================================================================================================================

                $session_id  = $this->session->userdata('user_id');
                $user_create = $session_id['user_id'];

                $start_date  = $this->input->post('start_date');
                $end_date    = $this->input->post('end_date');

                $data = array(
                    'employee_id'                  => $user_id,

                    'department_id'                => $empid->department_id,
                    'doj'                            => $empid->date_of_joining,
                    'company_id'                   => $empid->company_id,
                    'location_id'                  => $empid->location_id,
                    'designation_id'               => $empid->designation_id,
                    'wages_type'                   => $empid->wages_type,

                    'workstation_id'               => $workstation_id,
                    'start_date'                   => $start_date,
                    'end_date'                     => $end_date,

                    'jumlah_hadir'                 => $jumlah_hadir,
                    'jumlah_gram'                  => $jumlah_gram,
                    'jumlah_biaya'                 => $jumlah_biaya,
                    'jumlah_insentif'                 => $jumlah_insentif,

                    'commissions_amount'            => $commissions_amount,
                    'commissions_help_amount'      => $commissions_help_amount,
                    'bpjs_kes_amount'                 => $pot_bpjs_kes_amount,
                    'bpjs_tk_amount'                 => $bpjs_tk_amount,
                    'minus_amount'                 => $minus_amount,

                    'net_salary'                   => $total_net_salary,
                    'rekening_name'                => $rekening_name,
                    'bank_name'                    => $bank_name,
                    'email'                          => $employee_email,

                    'is_payment'                   => '1',
                    'payslip_type'                 => 'full_periode',
                    'payslip_key'                  => $jurl,
                    'year_to_date'                 => date('Y-m-d'),
                    'created_at'                   => date('Y-m-d h:i:s'),
                    'created_by'                   => $user_create
                );
                $result = $this->Payroll_model->add_salary_payslip_borongan($data);

                // echo "<pre>";
                // print_r($this->db->last_query());
                // echo "</pre>";
                // die();

                if ($result) {

                    $Return['result'] = 'Gaji Borongan Kolektif Periode Berhasil Disimpan';
                } else {
                    $Return['error'] = $this->lang->line('xin_error_msg');
                }
            }

            $this->output($Return);
            exit;
        }
    }

    public function add_pay_borongan()
    {
        if ($this->input->post('add_type') == 'add_borongan_payment') {
            // var_dump( $this->input->post('commissions_amount'));
            // return;
            /* Define return | here result is used to return user data and error for error message */
            $Return = array('result' => '', 'error' => '', 'csrf_hash' => '');
            $Return['csrf_hash'] = $this->security->get_csrf_hash();

            $employee_id     = $this->input->post('emp_id');
            $employee_name   = $this->input->post('employee_name');
            $start_date      = $this->input->post('start_date');
            $end_date        = $this->input->post('end_date');

            $pay_count = $this->Payroll_model->read_make_payment_payslip_check_borongan_company($employee_id, $start_date, $end_date);

            if ($pay_count->num_rows() > 0) {

                $pay_val = $this->Payroll_model->read_make_payment_payslip_borongan_company($employee_id, $start_date, $end_date);

                $this->payslip_delete_all_harian($pay_val[0]->payslip_id);
            }

            $jurl = random_string('alnum', 40);

            $session_id = $this->session->userdata('user_id');
            $user_create = $session_id['user_id'];

            $data = array(
                'employee_id'                  => $this->input->post('emp_id'),
                'department_id'                => $this->input->post('employee_department_id'),
                'doj'                            => $this->input->post('employee_date_of_joining'),
                'company_id'                   => $this->input->post('employee_company_id'),
                'location_id'                  => $this->input->post('employee_location_id'),
                'designation_id'               => $this->input->post('employee_designation_id'),
                'workstation_id'               => $this->input->post('workstation_id'),
                'wages_type'                   => $this->input->post('employee_wages_type'),

                'start_date'                   => $this->input->post('start_date'),
                'end_date'                     => $this->input->post('end_date'),

                'jumlah_hadir'                 => $this->input->post('jumlah_hadir'),
                'jumlah_gram'                  => $this->input->post('jumlah_gram'),
                'jumlah_biaya'                 => $this->input->post('jumlah_biaya'),
                'jumlah_insentif'                 => $this->input->post('jumlah_insentif'),

                'commissions_amount'           => $this->input->post('commissions_amount'),
                'commissions_help_amount'      => $this->input->post('commissions_help_amount'),

                'bpjs_kes_amount'              => $this->input->post('bpjs_kes_amount'),
                'bpjs_tk_amount'               => $this->input->post('bpjs_tk_amount'),
                'minus_amount'                 => $this->input->post('minus_amount'),

                'net_salary'                   => $this->input->post('total_net_salary'),
                'rekening_name'                => $this->input->post('rekening_name'),
                'bank_name'                    => $this->input->post('bank_name'),
                'email'                        => $this->input->post('employee_email'),

                'is_payment'                   => '1',
                'payslip_type'                 => 'full_periode',
                'payslip_key'                  => $jurl,
                'year_to_date'                 => date('Y-m-d'),
                'created_at'                   => date('Y-m-d h:i:s'),
                'created_by'                   => $user_create
            );
            $result = $this->Payroll_model->add_salary_payslip_borongan($data);

            // echo "<pre>";
            // print_r($this->db->last_query());
            // echo "</pre>";
            // die();

            if ($result) {
                $Return['result'] = 'Gaji Borongan ' . $employee_name . '' . "\n" . ' Periode Tanggal ' . $start_date . ' s/d ' . $end_date . '' . "\n" . ' Berhasil Disimpan';
            } else {
                $Return['error'] = $this->lang->line('xin_error_msg');
            }

            $this->output($Return);
            exit;
        }
    }

    // =======================================================================================
    // PROSES : TAMPIL
    // =======================================================================================

    // Tampil : Form Edit
    public function pay_salary_borongan()
    {
        $session = $this->session->userdata('username');
        if (empty($session)) {
            redirect('admin/');
        }
        $data['title'] = $this->Core_model->site_title();
        $id = $this->input->get('employee_id');
        // get addd by > template
        $user = $this->Core_model->read_user_info($id);

        // get designation
        $designation = $this->Designation_model->read_designation_information($user[0]->designation_id);
        if (!is_null($designation)) {
            $designation_name = $designation[0]->designation_name;
        } else {
            $designation_name = '';
        }
        // department
        $department = $this->Department_model->read_department_information($user[0]->department_id);
        if (!is_null($department)) {
            $department_name = $department[0]->department_name;
        } else {
            $department_name = '';
        }

        //$location = $this->Location_model->read_location_information($department[0]->location_id);
        $data = array(
            'department_name'  => $department_name,
            'designation_name' => $designation_name,
            'company_id'       => $user[0]->company_id,
            'location_id'      => $user[0]->location_id,
            'user_id'          => $user[0]->user_id,
            'wages_type'       => $user[0]->wages_type
        );
        if (!empty($session)) {
            $this->load->view('admin/payroll/dialog_make_payment_borongan', $data);
        } else {
            redirect('admin/');
        }
    }

    // Tampil : Form Hapu
    public function pay_salary_borongan_del()
    {
        $session = $this->session->userdata('username');
        if (empty($session)) {
            redirect('admin/');
        }
        $data['title'] = $this->Core_model->site_title();
        $payslip_id = $this->input->get('payslip_id');
        // get addd by > template

        $payslip = $this->Core_model->read_slip_info_borongan($payslip_id);

        $user = $this->Core_model->read_user_info($payslip[0]->employee_id);

        $designation = $this->Designation_model->read_designation_information($payslip[0]->designation_id);
        if (!is_null($designation)) {
            $designation_name = $designation[0]->designation_name;
        } else {
            $designation_name = '';
        }

        $workstation = $this->Workstation_model->read_workstation_information($payslip[0]->workstation_id);
        if (!is_null($workstation)) {
            $workstation_name = $workstation[0]->workstation_name;
        } else {
            $workstation_name = '';
        }


        // department
        $department = $this->Department_model->read_department_information($payslip[0]->department_id);
        if (!is_null($department)) {
            $department_name = $department[0]->department_name;
        } else {
            $department_name = '';
        }



        $data = array(
            'payslip_id'                     => $payslip_id,
            'department_name'                => $department_name,

            'workstation_name'            => $workstation_name,
            'workstation_id'                => $payslip[0]->workstation_id,

            'designation_name'               => $designation_name,

            'employee_name'                  => $user[0]->first_name . ' ' . $user[0]->last_name,
            'company_id'                     => $payslip[0]->company_id,

            'start_date'                  => $payslip[0]->start_date,
            'end_date'                    => $payslip[0]->end_date,

            'jumlah_hadir'                => $payslip[0]->jumlah_hadir,
            'jumlah_gram'                 => $payslip[0]->jumlah_gram,
            'jumlah_biaya'                => $payslip[0]->jumlah_biaya,

            'commissions_amount'          => $payslip[0]->commissions_amount,
            'bpjs_kes_amount'             => $payslip[0]->bpjs_kes_amount,
            'bpjs_tk_amount'              => $payslip[0]->bpjs_tk_amount,
            'minus_amount'                => $payslip[0]->minus_amount,

            'total_net_salary'            => $payslip[0]->net_salary,
            'rekening_name'                => $payslip[0]->rekening_name,
            'bank_name'                      => $payslip[0]->bank_name,
            'employee_email'              => $payslip[0]->email,


        );
        if (!empty($session)) {
            $this->load->view('admin/payroll/dialog_make_payment_borongan_delete', $data);
        } else {
            redirect('admin/');
        }
    }

    // =======================================================================================
    // PROSES : HAPUS
    // =======================================================================================

    public function del_pay_borongan()
    {
        if ($this->input->post('proses_type') == 'del_borongan_payment') {
            /* Define return | here result is used to return user data and error for error message */
            $Return = array('result' => '', 'error' => '', 'csrf_hash' => '');
            $Return['csrf_hash'] = $this->security->get_csrf_hash();


            $id     = $this->input->post('payslip_id');

            $result = $this->Payroll_model->delete_record_borongan($id);

            // echo "<pre>";
            // print_r($this->db->last_query());
            // echo "</pre>";
            // die();

            if (isset($id)) {

                $Return['result'] = 'Gaji Borongan Berhasil Dihapus';
            } else {
                $Return['error'] = $this->lang->line('xin_error_msg');
            }

            $this->output($Return);
            exit;
        }
    }

    public function payslip_delete_all_borongan($id)
    {
        $session = $this->session->userdata('username');
        if (empty($session)) {
            redirect('admin/');
        }
        /* Define return | here result is used to return user data and error for error message */
        $Return = array('result' => '', 'error' => '', 'csrf_hash' => '');
        $id = $id;
        $Return['csrf_hash'] = $this->security->get_csrf_hash();
        $this->Payroll_model->delete_record_borongan($id);
    }

    // =======================================================================================
    // TAMPIL => SLIP GAJI BORONGAN
    // =======================================================================================

    public function payslip_borongan()
    {
        $session = $this->session->userdata('username');
        if (empty($session)) {
            redirect('admin/');
        }
        //$data['title'] = $this->Core_model->site_title();
        $key = $this->uri->segment(5);

        $result = $this->Payroll_model->read_salary_payslip_borongan_info_key($key);
        if (is_null($result)) {
            redirect('admin/payroll/borongan');
        }

        $p_method = '';

        // get addd by > template
        $user = $this->Core_model->read_user_info($result[0]->employee_id);
        // user full name
        if (!is_null($user)) {
            $first_name = $user[0]->first_name;
            $last_name = $user[0]->last_name;
        } else {
            $first_name = '--';
            $last_name = '--';
        }
        // get designation
        $designation = $this->Designation_model->read_designation_information($user[0]->designation_id);
        if (!is_null($designation)) {
            $designation_name = $designation[0]->designation_name;
        } else {
            $designation_name = '--';
        }

        // Karyawan Workstation
        $workstation = $this->Core_model->read_designation_workstation_info($user[0]->designation_id);
        if (!is_null($workstation)) {
            $workstation_name = $workstation[0]->workstation_name;
        } else {
            $workstation_name = '';
        }

        // department
        $department = $this->Department_model->read_department_information($user[0]->department_id);
        if (!is_null($department)) {
            $department_name = $department[0]->department_name;
        } else {
            $department_name = '--';
        }

        // company
        $company = $this->Company_model->read_company_information($user[0]->company_id);
        if (!is_null($company)) {
            $company_name = $company[0]->name;
        } else {
            $company_name = '--';
        }


        //$department_designation = $designation[0]->designation_name.'('.$department[0]->department_name.')';
        $data['all_employees'] = $this->Core_model->all_employees();



        $data = array(
            'title'                      => 'Slip Gaji Borongan Karyawan | ' . $this->Core_model->site_title(),
            'icon'                       => '<i class="fa fa-money"></i>',
            'breadcrumbs'                => 'Slip Gaji Borongan Karyawan',
            'path_url'                   => 'payslip_borongan',

            'first_name'                 => $first_name,
            'last_name'                  => $last_name,

            'employee_id'                => $user[0]->employee_id,
            'euser_id'                   => $user[0]->user_id,
            'contact_no'                 => $user[0]->contact_no,
            'email'                      => $user[0]->email,
            'date_of_joining'            => $user[0]->date_of_joining,

            'company_name'               => $company_name,
            'workstation_name'            => $workstation_name,
            'designation_name'           => $designation_name,

            'date_of_joining'            => $user[0]->date_of_joining,
            'profile_picture'            => $user[0]->profile_picture,
            'gender'                     => $user[0]->gender,

            'workstation_name '             => $workstation_name,
            'make_payment_id'            => $result[0]->payslip_id,
            'wages_type'                 => $result[0]->wages_type,
            'payment_date'               => $result[0]->start_date . ' s/d ' . $result[0]->end_date,
            'year_to_date'               => $result[0]->year_to_date,

            'jumlah_hadir'               => $result[0]->jumlah_hadir,
            'jumlah_gram'                 => $result[0]->jumlah_gram,
            'jumlah_biaya'                 => $result[0]->jumlah_biaya,

            'commissions_amount'         => $result[0]->commissions_amount,
            'bpjs_kes_amount'             => $result[0]->bpjs_kes_amount,
            'bpjs_tk_amount'             => $result[0]->bpjs_tk_amount,
            'total_bpjs'                 => $result[0]->bpjs_kes_amount + $result[0]->bpjs_tk_amount,
            'total_minus'                 => $result[0]->minus_amount,

            'net_salary'                 => $result[0]->net_salary,

            'payslip_key'                => $result[0]->payslip_key,
            'payslip_type'               => $result[0]->payslip_type,
            'hours_worked'               => $result[0]->hours_worked,
            'pay_comments'               => $result[0]->pay_comments,
            'is_payment'                 => $result[0]->is_payment,
            'approval_status'            => $result[0]->status,
        );

        $role_resources_ids = $this->Core_model->user_role_resource();
        if (!empty($session)) {
            $data['subview'] = $this->load->view("admin/payroll/payslip_borongan", $data, TRUE);
            $this->load->view('admin/layout/layout_main', $data); //page load
        } else {
            redirect('admin/');
        }
    }

    public function pdf_create_borongan()
    {
        //$this->load->library('Pdf');
        $system = $this->Core_model->read_setting_info(1);
        // create new PDF document
        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

        $key = $this->uri->segment(5);
        $payment = $this->Payroll_model->read_salary_payslip_borongan_info_key($key);
        if (is_null($payment)) {
            redirect('admin/payroll/payslip_borongan');
        }
        $user = $this->Core_model->read_user_info($payment[0]->employee_id);

        // if password generate option enable
        if ($system[0]->is_payslip_password_generate == 1) {
            /**
             * Protect PDF from being printed, copied or modified. In order to being viewed, the user needs
             * to provide password as selected format in settings module.
             */
            if ($system[0]->payslip_password_format == 'dateofbirth') {
                $password_val = date("dmY", strtotime($user[0]->date_of_birth));
            } else if ($system[0]->payslip_password_format == 'contact_no') {
                $password_val = $user[0]->contact_no;
            } else if ($system[0]->payslip_password_format == 'full_name') {
                $password_val = $user[0]->first_name . $user[0]->last_name;
            } else if ($system[0]->payslip_password_format == 'email') {
                $password_val = $user[0]->email;
            } else if ($system[0]->payslip_password_format == 'password') {
                $password_val = $user[0]->password;
            } else if ($system[0]->payslip_password_format == 'user_password') {
                $password_val = $user[0]->username . $user[0]->password;
            } else if ($system[0]->payslip_password_format == 'employee_id') {
                $password_val = $user[0]->employee_id;
            } else if ($system[0]->payslip_password_format == 'employee_id_password') {
                $password_val = $user[0]->employee_id . $user[0]->password;
            } else if ($system[0]->payslip_password_format == 'dateofbirth_name') {
                $dob = date("dmY", strtotime($user[0]->date_of_birth));
                $fname = $user[0]->first_name;
                $lname = $user[0]->last_name;
                $password_val = $dob . $fname[0] . $lname[0];
            }
            $pdf->SetProtection(array('print', 'copy', 'modify'), $password_val, $password_val, 0, null);
        }


        $_des_name = $this->Designation_model->read_designation_information($user[0]->designation_id);
        if (!is_null($_des_name)) {
            $_designation_name = $_des_name[0]->designation_name;
        } else {
            $_designation_name = '';
        }
        $department = $this->Department_model->read_department_information($user[0]->department_id);
        if (!is_null($department)) {
            $_department_name = $department[0]->department_name;
        } else {
            $_department_name = '';
        }
        //$location = $this->Core_model->read_location_info($department[0]->location_id);
        // company info
        $company = $this->Core_model->read_company_info($user[0]->company_id);


        $p_method = '';
        if (!is_null($company)) {
            $company_logo = $company[0]->logo;
            $company_name = $company[0]->name;
            $address_1 = $company[0]->address_1;
            $address_2 = $company[0]->address_2;
            $city = $company[0]->city;
            $state = $company[0]->state;
            $zipcode = $company[0]->zipcode;
            $country = $this->Core_model->read_country_info($company[0]->country);
            if (!is_null($country)) {
                $country_name = $country[0]->country_name;
            } else {
                $country_name = '--';
            }
            $c_info_email = $company[0]->email;
            $c_info_phone = $company[0]->contact_number;
        } else {
            $company_logo = $company[0]->logo;
            $company_name = '--';
            $address_1 = '--';
            $address_2 = '--';
            $city = '--';
            $state = '--';
            $zipcode = '--';
            $country_name = '--';
            $c_info_email = '--';
            $c_info_phone = '--';
        }
        $pdf = new Pdf('P', 'mm', 'A4', true, 'UTF-8', false);

        // set default header data
        $c_info_address = $address_1 . ', ' . $address_2;

        $c_info_city    = $city . ', Kode Pos : ' . $zipcode . ' Jawa Timur - Indonesia';

        $email_phone_address = "$c_info_address \n" . "$c_info_city \n" . $this->lang->line('xin_phone') . " : $c_info_phone | " . $this->lang->line('dashboard_email') . " : $c_info_email \n";

        $header_string = $email_phone_address;

        // set document information
        $pdf->SetCreator('hris');
        $pdf->SetAuthor('Nizar Basyrewan');
        $pdf->SetTitle($company_name . ' - ' . $this->lang->line('xin_print_payslip_borongan'));
        $pdf->SetSubject($this->lang->line('xin_payslip'));
        $pdf->SetKeywords($this->lang->line('xin_payslip'));

        // $pdf->SetHeaderData('../../../../../uploads/logo/payroll/'.$company_logo, 40, $company_name, $header_string);

        $pdf->setFooterData(array(0, 64, 0), array(0, 64, 128));

        // set header and footer fonts
        $pdf->setHeaderFont(array('arial', '', 12));
        $pdf->setFooterFont(array('helvetica', '', 9));

        // set default monospaced font
        $pdf->SetDefaultMonospacedFont('courier');

        // set margins
        $pdf->SetMargins(15, 27, 15);
        $pdf->SetHeaderMargin(5);
        $pdf->SetFooterMargin(10);

        // set auto page breaks
        $pdf->SetAutoPageBreak(TRUE, 25);

        // set image scale factor
        $pdf->setImageScale(1.25);

        // set font
        $pdf->SetFont('helvetica', 'B', 10);

        // set header and footer fonts
        $pdf->setHeaderFont(array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
        $pdf->setFooterFont(array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

        // set default monospaced font
        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

        // set image scale factor
        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

        // ---------------------------------------------------------

        // set default font subsetting mode
        $pdf->setFontSubsetting(true);

        // Set font
        // dejavusans is a UTF-8 Unicode font, if you only need to
        // print standard ASCII chars, you can use core fonts like
        // helvetica or times to reduce file size.
        $pdf->SetFont('dejavusans', '', 10, '', true);

        // Add a page
        // This method has several options, check the source code documentation for more information.
        $pdf->AddPage();
        // -----------------------------------------------------------------------------
        $fname           = $user[0]->first_name . ' ' . $user[0]->last_name;

        $created_at      = $this->Core_model->set_date_format($payment[0]->created_at);
        $date_of_joining = $this->Core_model->set_date_format($user[0]->date_of_joining);


        $start_att = date("d-m-Y", strtotime($payment[0]->start_date));
        $end_att   = date("d-m-Y", strtotime($payment[0]->end_date));

        // check
        $half_title = '';

        // ===========================================================
        // Penambah
        //============================================================

        // basic salary
        $ns = 0;

        $commissions_amount = $payment[0]->commissions_amount;
        $minus_amount = $payment[0]->minus_amount;

        $bpjs_kes_amount = $payment[0]->bpjs_kes_amount;
        $bpjs_tk_amount = $payment[0]->bpjs_tk_amount;

        $ns = $payment[0]->net_salary;




        $tbl = '<br><br>
                    <table cellpadding="1" cellspacing="1" border="0" >
                        <tr>
                            <td align="center"><h2> SLIP GAJI KARYAWAN </h2></td>
                        </tr>
                        <tr>
                            <td align="center">  <strong>' . date("d-m-Y", strtotime($payment[0]->start_date)) . ' - ' . date("d-m-Y", strtotime($payment[0]->end_date)) . '</strong></td>
                        </tr>
                    </table>
                ';

        $pdf->writeHTML($tbl, true, false, false, false, '');
        // -----------------------------------------------------------------------------
        // set cell padding
        $pdf->setCellPaddings(1, 1, 1, 1);

        // set cell margins
        $pdf->setCellMargins(0, 0, 0, 0);

        // set color for background
        $pdf->SetFillColor(255, 255, 127);
        // set some text for example
        //$txt = 'Employee Details';
        // Multicell
        // $pdf->MultiCell(180, 6, $txt, 0, 'L', 11, 0, '', '', true);
        $pdf->Ln(7);
        $tbl1 = '
                    <table cellpadding="1" cellspacing="0" border="0" width="100%" style="font-size:11px;" >

                    <tr>
                        <td width="15%;"> <strong>NAMA</strong> </td>
                        <td width="35%;"> : ' . strtoupper($fname) . '</td>
                        <td width="15%;"> <strong>PERIODE</strong> </td>
                        <td width="35%;"> : ' . $start_att . ' s/d ' . $end_att . ' </td>
                    </tr>
                    <tr>
                        <td width="15%;"> <strong>JABATAN</strong> </td>
                        <td width="35%;"> : ' . strtoupper($_designation_name) . '</td>
                        <td width="15%;"> <strong>N.I.K</strong> </td>
                        <td width="35%;"> : ' . $user[0]->employee_id . '</td>
                    </tr>';


        $tbl1 .= '
                        </table>';

        $pdf->writeHTML($tbl1, true, false, true, false, '');


        //// break..
        // $pdf->Ln(0);

        $tbl_new = '
                    <table cellpadding="6" cellspacing="0" border="1" width="100%" style="border: 1px solid #ccc; font-size:11px;">

                        <tr>
                            <td width="25%;" align ="center" > <strong>TANGAL</strong> </td>
                            <td width="25%;" align ="center" > <strong>HARI</strong> </td>
                            <td width="25%;" align ="center" > <strong>Jumlah gram</strong> </td>
                            <td width="25%;" align ="center" > <strong>Jumlah</strong> </td>

                        </tr>';
        $start_date = new DateTime($payment[0]->start_date);
        $end_date   = new DateTime($payment[0]->end_date);
        $end_date   = $end_date->modify('+1 day');

        $interval_re = new DateInterval('P1D');
        $date_range = new DatePeriod($start_date, $interval_re, $end_date);
        $attendance_arr = array();

        foreach ($date_range as $date) {

            $attendance_date =  $date->format("Y-m-d");

            $tday            = $this->Timesheet_model->conHariNama(date("D", strtotime($attendance_date)));

            // =========================================================================================================
            // TAMPILKAN
            // =========================================================================================================
            $gram_nilai = '0';
            $gram_biaya = '0';

            $sql_check_produktifitas = "SELECT * FROM xin_workstation_gram_terima WHERE employee_id ='" . $user[0]->employee_id . "' AND gram_tanggal = '" . $attendance_date . "' ";
            // echo "<pre>";
            // print_r($sql_check_izin);
            // echo "</pre>";
            // die;
            $query_check_produktifitas = $this->db->query($sql_check_produktifitas);
            if ($query_check_produktifitas->num_rows() > 0) {
                foreach ($query_check_produktifitas->result() as $row_check_produktifitas) :
                    $gram_nilai       =  $row_check_produktifitas->gram_nilai;
                    $gram_biaya       =  $row_check_produktifitas->gram_biaya;
                endforeach;
            } else {

                $gram_nilai = '0';
                $gram_biaya = '0';
            }

            // =========================================================================================================
            // TAMPILKAN
            // =========================================================================================================

            $commissions_help_amount = '0';

            $sql_check_diperbantukan = "SELECT * FROM xin_salary_commissions WHERE employee_id ='" . $user[0]->user_id . "' AND commission_date = '" . $attendance_date . "' AND flag = '1' ";
            // echo "<pre>";
            // print_r($sql_check_diperbantukan);
            // echo "</pre>";
            // die;
            $query_check_diperbantukan = $this->db->query($sql_check_diperbantukan);
            if ($query_check_diperbantukan->num_rows() > 0) {
                foreach ($query_check_diperbantukan->result() as $row_check_diperbantukan) :
                    $commissions_help_amount       =  $row_check_diperbantukan->commission_amount;
                endforeach;
            } else {

                $commissions_help_amount = '0';
            }

            if ($commissions_help_amount == '0') {

                $jumlah = number_format($gram_biaya, 0, ',', '.');
            } else {

                $jumlah = number_format($commissions_help_amount, 0, ',', '.') . ' *';
            }



            $tbl_new .= '

                        <tr>
                            <td width="25%;" align ="center" > ' . $attendance_date . ' </td>
                            <td width="25%;" align ="center" > ' . $tday . ' </td>

                            <td width="25%;" align ="center" > ' . number_format($gram_nilai, 0, ',', '.') . ' </td>
                            <td width="25%;" align ="right" > ' . $jumlah . ' </td>

                        </tr>';
        }



        $tbl_new .= '
                        <tr>
                            <td align ="left" colspan="2" rowspan="2"> <strong> Revisi Gaji </strong> </td>
                            <td width="25%;" align ="right" > <strong>  Tambahan </strong> </td>
                            <td width="25%;" align ="right" > <strong>  ' . number_format($commissions_amount, 0, ',', '.') . ' </strong> </td>
                        </tr>

                        <tr>

                            <td width="25%;" align ="right" > <strong>  Potongan </strong> </td>
                            <td width="25%;" align ="right" > <strong>  ' . number_format($minus_amount, 0, ',', '.') . ' </strong> </td>
                        </tr>

                        <tr>
                            <td align ="left" colspan="3"> <strong> BPJS TK </strong> </td>
                            <td width="25%;" align ="right" > <strong>  ' . number_format($bpjs_tk_amount, 0, ',', '.') . ' </strong> </td>
                        </tr>

                        <tr>
                            <td align ="left" colspan="3"> <strong> BPJS Kesehatan </strong> </td>
                            <td width="25%;" align ="right" > <strong>  ' . number_format($bpjs_kes_amount, 0, ',', '.') . ' </strong> </td>
                        </tr>

                        <tr>
                            <td align ="left" colspan="3"> <strong> Total Gaji </strong> </td>
                            <td width="25%;" align ="right" > <strong>  ' . number_format($ns, 0, ',', '.') . ' </strong> </td>
                        </tr>

                        </table>';

        $pdf->writeHTML($tbl_new, true, false, true, false, '');

        //// break..
        $pdf->Ln(0);


        // Close and output PDF document
        // This method has several options, check the source code documentation for more information.
        $fname = strtolower($fname);
        $pay_month = strtolower(date("F Y", strtotime($payment[0]->year_to_date)));

        //Close and output PDF document
        ob_start();



        $pdf->Output('slip_gaji_borongan_' . $fname . '_' . $pay_month . '.pdf', 'I');
        ob_end_flush();
    }

    public function payroll_template_read_borongan()
    {
        $session = $this->session->userdata('username');
        if (empty($session)) {
            redirect('admin/');
        }
        $data['title'] = $this->Core_model->site_title();
        $id = $this->input->get('employee_id');
        // get addd by > template
        $user = $this->Core_model->read_user_info($id);
        // user full name
        $full_name = $user[0]->first_name . ' ' . $user[0]->last_name;
        // get designation
        $designation = $this->Designation_model->read_designation_information($user[0]->designation_id);
        if (!is_null($designation)) {
            $designation_name = $designation[0]->designation_name;
        } else {
            $designation_name = '--';
        }
        // department
        $department = $this->Department_model->read_department_information($user[0]->department_id);
        if (!is_null($department)) {
            $department_name = $department[0]->department_name;
        } else {
            $department_name = '--';
        }
        $data = array(
            'first_name' => $user[0]->first_name,
            'last_name' => $user[0]->last_name,
            'employee_id' => $user[0]->employee_id,
            'user_id' => $user[0]->user_id,
            'department_name' => $department_name,
            'designation_name' => $designation_name,
            'date_of_joining' => $user[0]->date_of_joining,
            'profile_picture' => $user[0]->profile_picture,
            'gender' => $user[0]->gender,
            'wages_type' => $user[0]->wages_type,
            'basic_salary' => $user[0]->basic_salary,
            'daily_wages' => '',
        );
        if (!empty($session)) {
            $this->load->view('admin/payroll/dialog_templates_borongan', $data);
        } else {
            redirect('admin/');
        }
    }

    // =======================================================================================
    // DETAIL KARYAWAN : GAJI BORONGAN
    // =======================================================================================

    // =======================================================================================
    // DETAIL
    // =======================================================================================
    public function borongan_detail()
    {
        $session = $this->session->userdata('username');
        if (empty($session)) {
            redirect('admin/');
        }

        $role_resources_ids = $this->Core_model->user_role_resource();
        $check_role = $this->Employees_model->read_employee_information($session['user_id']);
        if (!in_array('1020', $role_resources_ids)) {
            redirect('admin/payroll/borongan');
        }

        $id         = $this->uri->segment(4);

        $start_date = $this->uri->segment(5);
        $end_date   = $this->uri->segment(6);

        $result = $this->Employees_model->read_employee_information($id);
        if (is_null($result)) {
            redirect('admin/payroll/borongan');
        }

        $data = array(
            'breadcrumbs'  => 'Edit Komponen Gaji Borongan',
            'icon'         => '<i class="fa fa-pencil"></i>',
            'path_url'     => 'employees_detail_payroll_borongan',
            'title'        => 'Edit Komponen Gaji Borongan | ' . $this->Core_model->site_title(),

            'first_name'   => $result[0]->first_name,
            'last_name'    => $result[0]->last_name,
            'user_id'      => $result[0]->user_id,

            'start_date'   => $start_date,
            'end_date'     => $end_date,


            'wages_type'   => $result[0]->wages_type,
            'grade_type'   => $result[0]->grade_type,
            'basic_salary'     => $result[0]->basic_salary,

            'all_departments'  => $this->Department_model->all_departments(),
            'all_designations' => $this->Designation_model->all_designations(),
            'all_user_roles'   => $this->Roles_model->all_user_roles(),

        );


        $data['subview'] = $this->load->view("admin/payroll/borongan_detail", $data, TRUE);

        $this->load->view('admin/layout/layout_main', $data); //page load

        // Datatables Variables
        $draw   = intval($this->input->get("draw"));
        $start  = intval($this->input->get("start"));
        $length = intval($this->input->get("length"));
    }

    // =======================================================================================
    // 04. TAMBAHAN BORONGAN
    // =======================================================================================

    public function salary_all_commissions_borongan()
    {
        //set data
        $data['title'] = $this->Core_model->site_title();
        $session = $this->session->userdata('username');
        if (!empty($session)) {
            $this->load->view("admin/payroll/borongan_detail", $data);
        } else {
            redirect('admin/');
        }
        // Datatables Variables
        $draw   = intval($this->input->get("draw"));
        $start  = intval($this->input->get("start"));
        $length = intval($this->input->get("length"));

        $id = $this->uri->segment(4);
        $commissions = $this->Employees_model->set_employee_commissions($id);

        $data = array();

        foreach ($commissions->result() as $r) {

            $data[] = array(
                '<span data-toggle="tooltip" data-placement="top" title="' . $this->lang->line('xin_edit') . '">
                                <button type="button" class="btn icon-btn btn-xs btn-default waves-effect waves-light" data-toggle="modal" data-target=".edit-modal-data" data-field_id="' . $r->salary_commissions_id . '" data-field_type="salary_commissions_borongan">
                                    <span class="fa fa-pencil"></span> Edit
                                </button>
                            </span>
                            <span data-toggle="tooltip" data-placement="top" title="' . $this->lang->line('xin_delete') . '">
                                <button type="button" class="btn icon-btn btn-xs btn-danger waves-effect waves-light delete" data-toggle="modal" data-target=".delete-modal" data-record-id="' . $r->salary_commissions_id . '" data-token_type="all_commissions_borongan">
                                    <span class="fa fa-trash"></span>
                                </button>
                            </span>',
                date("d-m-Y", strtotime($r->commission_date)),
                $r->commission_title,
                $this->Core_model->currency_sign($r->commission_amount)
            );
        }

        $output = array(
            "draw" => $draw,
            "recordsTotal" => $commissions->num_rows(),
            "recordsFiltered" => $commissions->num_rows(),
            "data" => $data
        );
        $this->output->set_output(json_encode($output));
    }

    // Read
    public function dialog_salary_commissions_borongan()
    {
        $session = $this->session->userdata('username');
        if (empty($session)) {
            redirect('admin/');
        }
        $data['title'] = $this->Core_model->site_title();
        $id            = $this->input->get('field_id');
        $result        = $this->Employees_model->read_single_salary_commissions($id);
        $data = array(
            'salary_commissions_id' => $result[0]->salary_commissions_id,
            'employee_id'           => $result[0]->employee_id,
            'commission_title'      => $result[0]->commission_title,
            'commission_date'       => $result[0]->commission_date,
            'commission_amount'     => $result[0]->commission_amount
        );

        if (!empty($session)) {
            $this->load->view('admin/payroll/borongan_detail_dialog', $data);
        } else {
            redirect('admin/');
        }
    }

    // Tambah
    public function employee_commissions_option_borongan()
    {
        if ($this->input->post('type') == 'employee_update_commissions') {
            /* Define return | here result is used to return user data and error for error message */
            $Return = array('result' => '', 'error' => '', 'csrf_hash' => '');
            $Return['csrf_hash'] = $this->security->get_csrf_hash();
            if ($this->input->post('title') === '') {
                $Return['error'] = $this->lang->line('xin_error_title');
            } else if ($this->input->post('amount') === '') {
                $Return['error'] = $this->lang->line('xin_error_amount_field');
            }

            if ($Return['error'] != '') {
                $this->output($Return);
            }
            $data = array(
                'commission_date'   => $this->input->post('date'),
                'commission_title'  => $this->input->post('title'),
                'commission_amount' => $this->input->post('amount'),
                'employee_id'       => $this->input->post('user_id')
            );
            $result = $this->Employees_model->add_salary_commissions($data);
            if ($result == TRUE) {
                $Return['result'] = 'Tambahan Berhasil Ditambahkan';
            } else {
                $Return['error'] = $this->lang->line('xin_error_msg');
            }
            $this->output($Return);
            exit;
        }
    }

    // Edit
    public function update_commissions_info_borongan()
    {
        if ($this->input->post('type') == 'e_salary_commissions_info') {
            /* Define return | here result is used to return user data and error for error message */
            $Return = array('result' => '', 'error' => '', 'csrf_hash' => '');
            $Return['csrf_hash'] = $this->security->get_csrf_hash();

            /* Server side PHP input validation */
            if ($this->input->post('title') === '') {
                $Return['error'] = $this->lang->line('xin_error_title');
            } else if ($this->input->post('amount') === '') {
                $Return['error'] = $this->lang->line('xin_error_amount_field');
            }

            if ($Return['error'] != '') {
                $this->output($Return);
            }

            $data = array(
                'commission_date' => $this->input->post('date'),
                'commission_title' => $this->input->post('title'),
                'commission_amount' => $this->input->post('amount')
            );
            $e_field_id = $this->input->post('e_field_id');
            $result = $this->Employees_model->salary_commissions_update_record($data, $e_field_id);
            if ($result == TRUE) {
                $Return['result'] = 'Tambahan Berhasil Diperbarui';
            } else {
                $Return['error'] = $this->lang->line('xin_error_msg');
            }
            $this->output($Return);
            exit;
        }
    }

    // Hapus
    public function delete_all_commissions_borongan()
    {
        if ($this->input->post('data') == 'delete_record') {
            /* Define return | here result is used to return user data and error for error message */
            $Return = array('result' => '', 'error' => '', 'csrf_hash' => '');
            $Return['csrf_hash'] = $this->security->get_csrf_hash();
            $id = $this->uri->segment(4);
            $result = $this->Employees_model->delete_commission_record($id);
            if (isset($id)) {
                $Return['result'] = 'Tambahan Berhasil Dihapus';
            } else {
                $Return['error'] = $this->lang->line('xin_error_msg');
            }
            $this->output($Return);
        }
    }

    // =======================================================================================
    // 04. DIPERBANTUKAN BORONGAN
    // =======================================================================================

    public function salary_all_commissions_help_borongan()
    {
        //set data
        $data['title'] = $this->Core_model->site_title();
        $session = $this->session->userdata('username');
        if (!empty($session)) {
            $this->load->view("admin/payroll/borongan_detail", $data);
        } else {
            redirect('admin/');
        }
        // Datatables Variables
        $draw   = intval($this->input->get("draw"));
        $start  = intval($this->input->get("start"));
        $length = intval($this->input->get("length"));

        $id = $this->uri->segment(4);
        $commissions = $this->Employees_model->set_employee_commissions_help($id);

        $data = array();

        foreach ($commissions->result() as $r) {

            $data[] = array(
                '<span data-toggle="tooltip" data-placement="top" title="' . $this->lang->line('xin_edit') . '">
                                <button type="button" class="btn icon-btn btn-xs btn-default waves-effect waves-light" data-toggle="modal" data-target=".edit-modal-data" data-field_id="' . $r->salary_commissions_id . '" data-field_type="salary_commissions_help_borongan">
                                    <span class="fa fa-pencil"></span> Edit
                                </button>
                            </span>
                            <span data-toggle="tooltip" data-placement="top" title="' . $this->lang->line('xin_delete') . '">
                                <button type="button" class="btn icon-btn btn-xs btn-danger waves-effect waves-light delete" data-toggle="modal" data-target=".delete-modal" data-record-id="' . $r->salary_commissions_id . '" data-token_type="all_commissions_help_borongan">
                                    <span class="fa fa-trash"></span>
                                </button>
                            </span>',
                date("d-m-Y", strtotime($r->commission_date)),
                $r->commission_title,
                $this->Core_model->currency_sign($r->commission_amount)
            );
        }

        $output = array(
            "draw" => $draw,
            "recordsTotal" => $commissions->num_rows(),
            "recordsFiltered" => $commissions->num_rows(),
            "data" => $data
        );
        $this->output->set_output(json_encode($output));
    }

    // Read
    public function dialog_salary_commissions_help_borongan()
    {
        $session = $this->session->userdata('username');
        if (empty($session)) {
            redirect('admin/');
        }
        $data['title'] = $this->Core_model->site_title();
        $id            = $this->input->get('field_id');
        $result        = $this->Employees_model->read_single_salary_commissions($id);
        $data = array(
            'salary_commissions_id' => $result[0]->salary_commissions_id,
            'employee_id'           => $result[0]->employee_id,
            'commission_title'      => $result[0]->commission_title,
            'commission_date'       => $result[0]->commission_date,
            'commission_amount'     => $result[0]->commission_amount
        );

        if (!empty($session)) {
            $this->load->view('admin/payroll/borongan_detail_dialog', $data);
        } else {
            redirect('admin/');
        }
    }

    // Tambah
    public function employee_commissions_help_option_borongan()
    {
        if ($this->input->post('type') == 'employee_update_commissions_help') {
            /* Define return | here result is used to return user data and error for error message */
            $Return = array('result' => '', 'error' => '', 'csrf_hash' => '');
            $Return['csrf_hash'] = $this->security->get_csrf_hash();
            if ($this->input->post('title') === '') {
                $Return['error'] = $this->lang->line('xin_error_title');
            } else if ($this->input->post('amount') === '') {
                $Return['error'] = $this->lang->line('xin_error_amount_field');
            }

            if ($Return['error'] != '') {
                $this->output($Return);
            }
            $data = array(
                'commission_date'   => $this->input->post('commission_date'),
                'commission_title'  => $this->input->post('commission_title'),
                'commission_amount' => $this->input->post('commission_amount'),
                'employee_id'       => $this->input->post('user_id'),
                'flag'              => 1
            );
            $result = $this->Employees_model->add_salary_commissions($data);
            if ($result == TRUE) {
                $Return['result'] = 'Diperbantukan Berhasil Ditambahkan';
            } else {
                $Return['error'] = $this->lang->line('xin_error_msg');
            }
            $this->output($Return);
            exit;
        }
    }

    // Edit
    public function update_commissions_help_info_borongan()
    {
        if ($this->input->post('type') == 'e_salary_commissions_help_info') {
            /* Define return | here result is used to return user data and error for error message */
            $Return = array('result' => '', 'error' => '', 'csrf_hash' => '');
            $Return['csrf_hash'] = $this->security->get_csrf_hash();

            /* Server side PHP input validation */
            if ($this->input->post('title') === '') {
                $Return['error'] = $this->lang->line('xin_error_title');
            } else if ($this->input->post('amount') === '') {
                $Return['error'] = $this->lang->line('xin_error_amount_field');
            }

            if ($Return['error'] != '') {
                $this->output($Return);
            }

            $data = array(
                'commission_date' => $this->input->post('commission_date'),
                'commission_title' => $this->input->post('commission_title'),
                'commission_amount' => $this->input->post('commission_amount')
            );
            $e_field_id = $this->input->post('e_field_id');
            $result = $this->Employees_model->salary_commissions_update_record($data, $e_field_id);
            if ($result == TRUE) {
                $Return['result'] = 'Diperbantukan Berhasil Diperbarui';
            } else {
                $Return['error'] = $this->lang->line('xin_error_msg');
            }
            $this->output($Return);
            exit;
        }
    }

    // Hapus
    public function delete_all_commissions_help_borongan()
    {
        if ($this->input->post('data') == 'delete_record') {
            /* Define return | here result is used to return user data and error for error message */
            $Return = array('result' => '', 'error' => '', 'csrf_hash' => '');
            $Return['csrf_hash'] = $this->security->get_csrf_hash();
            $id = $this->uri->segment(4);
            $result = $this->Employees_model->delete_commission_record($id);
            if (isset($id)) {
                $Return['result'] = 'Diperbantukan Berhasil Dihapus';
            } else {
                $Return['error'] = $this->lang->line('xin_error_msg');
            }
            $this->output($Return);
        }
    }

    // =======================================================================================
    // 05. BPJS KES & TK
    // =======================================================================================

    public function salary_all_statutory_deductions_borongan()
    {
        //set data
        $data['title'] = $this->Core_model->site_title();
        $session = $this->session->userdata('username');
        if (!empty($session)) {
            $this->load->view("admin/payroll/borongan_detail", $data);
        } else {
            redirect('admin/');
        }
        // Datatables Variables
        $draw   = intval($this->input->get("draw"));
        $start  = intval($this->input->get("start"));
        $length = intval($this->input->get("length"));

        $id = $this->uri->segment(4);
        $statutory_deductions = $this->Employees_model->set_employee_statutory_deductions($id);

        $data = array();

        foreach ($statutory_deductions->result() as $r) {
            if ($r->statutory_options == 1) {
                $sd_opt = $this->lang->line('xin_sd_ssc_title');
            } else if ($r->statutory_options == 2) {
                $sd_opt = $this->lang->line('xin_sd_phic_title');
            } else if ($r->statutory_options == 3) {
                $sd_opt = $this->lang->line('xin_sd_hdmf_title');
            } else if ($r->statutory_options == 4) {
                $sd_opt = $this->lang->line('xin_sd_wht_title');
            } else {
                $sd_opt = $this->lang->line('xin_sd_other_sd_title');
            }
            $data[] = array(
                '<span data-toggle="tooltip" data-placement="top" title="' . $this->lang->line('xin_edit') . '">
                    <button type="button" class="btn icon-btn btn-xs btn-default waves-effect waves-light" data-toggle="modal" data-target=".edit-modal-data" data-field_id="' . $r->statutory_deductions_id . '" data-field_type="salary_statutory_deductions_borongan">
                        <span class="fa fa-pencil"></span>
                    </button>
                </span>

                <span data-toggle="tooltip" data-placement="top" title="' . $this->lang->line('xin_delete') . '">
                        <button type="button" class="btn icon-btn btn-xs btn-danger waves-effect waves-light delete" data-toggle="modal" data-target=".delete-modal" data-record-id="' . $r->statutory_deductions_id . '" data-token_type="all_statutory_deductions_borongan">
                            <span class="fa fa-trash"></span>
                        </button>
                </span>',

                $this->Core_model->set_date_format($r->deduction_date),
                $sd_opt,
                $r->deduction_title,
                $this->Core_model->currency_sign($r->deduction_amount)

            );
        }

        $output = array(
            "draw" => $draw,
            "recordsTotal" => $statutory_deductions->num_rows(),
            "recordsFiltered" => $statutory_deductions->num_rows(),
            "data" => $data
        );
        $this->output->set_output(json_encode($output));
    }

    // Read
    public function dialog_salary_statutory_deductions_borongan()
    {

        $session = $this->session->userdata('username');
        if (empty($session)) {
            redirect('admin/');
        }
        $data['title'] = $this->Core_model->site_title();
        $id            = $this->input->get('field_id');
        $result        = $this->Employees_model->read_single_salary_statutory_deduction($id);
        $data          = array(
            'statutory_deductions_id' => $result[0]->statutory_deductions_id,
            'deduction_date'          => $result[0]->deduction_date,
            'employee_id'             => $result[0]->employee_id,
            'deduction_title'         => $result[0]->deduction_title,
            'deduction_amount'        => $result[0]->deduction_amount,
            'statutory_options'       => $result[0]->statutory_options
        );

        if (!empty($session)) {
            $this->load->view('admin/payroll/borongan_detail_dialog', $data);
        } else {
            redirect('admin/');
        }
    }

    // Tambah
    public function set_statutory_deductions_borongan()
    {
        if ($this->input->post('type') == 'statutory_deductions_info_borongan') {
            /* Define return | here result is used to return user data and error for error message */
            $Return = array('result' => '', 'error' => '', 'csrf_hash' => '');
            $Return['csrf_hash'] = $this->security->get_csrf_hash();
            if ($this->input->post('title') === '') {
                $Return['error'] = $this->lang->line('xin_error_title');
            } else if ($this->input->post('amount') === '') {
                $Return['error'] = $this->lang->line('xin_error_amount_field');
            } else if ($this->input->post('deduction_date') === '') {
                $Return['error'] = 'Tanggal BPJS Belum Diinput';
            }

            if ($Return['error'] != '') {
                $this->output($Return);
            }
            $data = array(
                'deduction_date' => $this->input->post('deduction_date'),
                'deduction_title' => $this->input->post('title'),
                'deduction_amount' => $this->input->post('amount'),
                'statutory_options' => $this->input->post('statutory_options'),
                'employee_id' => $this->input->post('user_id')
            );
            $result = $this->Employees_model->add_salary_statutory_deductions($data);
            if ($result == TRUE) {
                $Return['result'] = 'BPJS Berhasil Ditambahkan';
            } else {
                $Return['error'] = $this->lang->line('xin_error_msg');
            }
            $this->output($Return);
            exit;
        }
    }

    // Edit
    public function update_statutory_deductions_info_borongan()
    {

        if ($this->input->post('type') == 'e_salary_statutory_deductions_info_borongan') {

            /* Define return | here result is used to return user data and error for error message */
            $Return = array('result' => '', 'error' => '', 'csrf_hash' => '');
            $Return['csrf_hash'] = $this->security->get_csrf_hash();

            /* Server side PHP input validation */
            if ($this->input->post('title') === '') {
                $Return['error'] = $this->lang->line('xin_error_title');
            } else if ($this->input->post('amount') === '') {
                $Return['error'] = $this->lang->line('xin_error_amount_field');
            } else if ($this->input->post('deduction_date') === '') {
                $Return['error'] = 'Tanggal BPJS Belum Diinput';
            }

            if ($Return['error'] != '') {
                $this->output($Return);
            }

            $data = array(
                'deduction_date' => $this->input->post('deduction_date'),
                'deduction_title' => $this->input->post('title'),
                'deduction_amount' => $this->input->post('amount'),
                'statutory_options' => $this->input->post('statutory_options')
            );
            $e_field_id = $this->input->post('e_field_id');
            $result = $this->Employees_model->salary_statutory_deduction_update_record($data, $e_field_id);
            if ($result == TRUE) {
                $Return['result'] = 'BPJS Berhasil Diperbarui';
            } else {
                $Return['error'] = $this->lang->line('xin_error_msg');
            }
            $this->output($Return);
            exit;
        }
    }

    // Hapus
    public function delete_all_statutory_deductions_borongan()
    {

        if ($this->input->post('data') == 'delete_record') {
            /* Define return | here result is used to return user data and error for error message */
            $Return = array('result' => '', 'error' => '', 'csrf_hash' => '');
            $Return['csrf_hash'] = $this->security->get_csrf_hash();
            $id = $this->uri->segment(4);
            $result = $this->Employees_model->delete_statutory_deductions_record($id);
            if (isset($id)) {
                $Return['result'] = 'BPJS Berhasil Dihapus';
            } else {
                $Return['error'] = $this->lang->line('xin_error_msg');
            }
            $this->output($Return);
        }
    }

    // =======================================================================================
    // 05. POTONG BORONGAN
    // =======================================================================================

    public function salary_all_minus_borongan()
    {
        //set data
        $data['title'] = $this->Core_model->site_title();
        $session = $this->session->userdata('username');
        if (!empty($session)) {
            $this->load->view("admin/payroll/borongan_detail", $data);
        } else {
            redirect('admin/');
        }
        // Datatables Variables
        $draw   = intval($this->input->get("draw"));
        $start  = intval($this->input->get("start"));
        $length = intval($this->input->get("length"));

        $id = $this->uri->segment(4);
        $minus = $this->Employees_model->set_employee_minus($id);

        $data = array();

        foreach ($minus->result() as $r) {

            $data[] = array(
                '
                            <span data-toggle="tooltip" data-placement="top" title="' . $this->lang->line('xin_delete') . '">
                                <button type="button" class="btn icon-btn btn-xs btn-danger waves-effect waves-light delete" data-toggle="modal" data-target=".delete-modal" data-record-id="' . $r->salary_minus_id . '" data-token_type="all_minus_borongan">
                                    <span class="fa fa-trash"></span> Hapus
                                </button>
                            </span>',

                date("d-m-Y", strtotime($r->minus_date)),
                $r->minus_title,
                $this->Core_model->currency_sign($r->minus_amount)
            );
        }

        $output = array(
            "draw" => $draw,
            "recordsTotal" => $minus->num_rows(),
            "recordsFiltered" => $minus->num_rows(),
            "data" => $data
        );
        $this->output->set_output(json_encode($output));
    }

    // Read
    public function dialog_salary_minus_borongan()
    {
        $session = $this->session->userdata('username');
        if (empty($session)) {
            redirect('admin/');
        }
        $data['title'] = $this->Core_model->site_title();
        $id            = $this->input->get('field_id');
        $result        = $this->Employees_model->read_single_salary_minus($id);
        $data = array(
            'salary_minus_id'  => $result[0]->salary_minus_id,
            'employee_id'      => $result[0]->employee_id,
            'minus_title'      => $result[0]->minus_title,
            'minus_date'       => $result[0]->minus_date,
            'minus_amount'     => $result[0]->minus_amount
        );

        if (!empty($session)) {
            $this->load->view('admin/payroll/borongan_detail_dialog', $data);
        } else {
            redirect('admin/');
        }
    }

    // Tambah
    public function employee_minus_option_borongan()
    {
        if ($this->input->post('type') == 'employee_update_minus') {
            /* Define return | here result is used to return user data and error for error message */
            $Return = array('result' => '', 'error' => '', 'csrf_hash' => '');
            $Return['csrf_hash'] = $this->security->get_csrf_hash();
            if ($this->input->post('title') === '') {
                $Return['error'] = $this->lang->line('xin_error_title');
            } else if ($this->input->post('amount') === '') {
                $Return['error'] = $this->lang->line('xin_error_amount_field');
            }

            if ($Return['error'] != '') {
                $this->output($Return);
            }
            $data = array(
                'minus_date'   => $this->input->post('minus_date'),
                'minus_title'  => $this->input->post('minus_title'),
                'minus_amount' => $this->input->post('minus_amount'),
                'employee_id'  => $this->input->post('user_id')
            );
            $result = $this->Employees_model->add_salary_minus($data);
            if ($result == TRUE) {
                $Return['result'] = 'Pemotong Gaji Borongan Berhasil Ditambahkan';
            } else {
                $Return['error'] = $this->lang->line('xin_error_msg');
            }
            $this->output($Return);
            exit;
        }
    }

    // Edit
    public function update_minus_info_borongan()
    {

        if ($this->input->post('type') == 'e_salary_minus_info') {
            /* Define return | here result is used to return user data and error for error message */
            $Return = array('result' => '', 'error' => '', 'csrf_hash' => '');
            $Return['csrf_hash'] = $this->security->get_csrf_hash();

            /* Server side PHP input validation */
            if ($this->input->post('title') === '') {
                $Return['error'] = $this->lang->line('xin_error_title');
            } else if ($this->input->post('amount') === '') {
                $Return['error'] = $this->lang->line('xin_error_amount_field');
            }

            if ($Return['error'] != '') {
                $this->output($Return);
            }

            $data = array(
                'minus_date'   => $this->input->post('minus_date'),
                'minus_title'  => $this->input->post('minus_title'),
                'minus_amount' => $this->input->post('minus_amount')
            );
            $e_field_id = $this->input->post('e_field_id');
            $result     = $this->Employees_model->salary_minus_update_record($data, $e_field_id);
            if ($result == TRUE) {
                $Return['result'] = 'Pemotong Gaji Harian Berhasil Diperbarui';
            } else {
                $Return['error'] = $this->lang->line('xin_error_msg');
            }
            $this->output($Return);
            exit;
        }
    }

    // Hapus
    public function delete_all_minus_borongan()
    {
        if ($this->input->post('data') == 'delete_record') {
            /* Define return | here result is used to return user data and error for error message */
            $Return = array('result' => '', 'error' => '', 'csrf_hash' => '');
            $Return['csrf_hash'] = $this->security->get_csrf_hash();
            $id = $this->uri->segment(4);
            $result = $this->Employees_model->delete_minus_record($id);
            if (isset($id)) {
                $Return['result'] = 'Pemotong Gaji Harian Berhasil Dihapus';
            } else {
                $Return['error'] = $this->lang->line('xin_error_msg');
            }
            $this->output($Return);
        }
    }


    // ===============================================================================================
    // 04. LAIN
    // ===============================================================================================

    // get company > employees
    public function get_employees()
    {
        $data['title'] = $this->Core_model->site_title();
        $id = $this->uri->segment(4);

        $data = array(
            'company_id' => $id
        );
        $session = $this->session->userdata('username');
        if (!empty($session)) {
            $this->load->view("admin/payroll/get_employees", $data);
        } else {
            redirect('admin/');
        }
        // Datatables Variables
        $draw = intval($this->input->get("draw"));
        $start = intval($this->input->get("start"));
        $length = intval($this->input->get("length"));
    }

    // get company > locations
    public function get_company_plocations()
    {
        $data['title'] = $this->Core_model->site_title();
        $keywords = preg_split("/[\s,]+/", $this->uri->segment(4));
        if (is_numeric($keywords[0])) {
            $id = $keywords[0];

            $data = array(
                'company_id' => $id
            );
            $session = $this->session->userdata('username');
            if (!empty($session)) {
                $data = $this->security->xss_clean($data);
                $this->load->view("admin/payroll/get_company_plocations", $data);
            } else {
                redirect('admin/');
            }
        }
        // Datatables Variables
        $draw = intval($this->input->get("draw"));
        $start = intval($this->input->get("start"));
        $length = intval($this->input->get("length"));
    }

    // get location > departments
    public function get_location_pdepartments()
    {
        $data['title'] = $this->Core_model->site_title();
        $keywords = preg_split("/[\s,]+/", $this->uri->segment(4));
        if (is_numeric($keywords[0])) {
            $id = $keywords[0];

            $data = array(
                'location_id' => $id
            );
            $session = $this->session->userdata('username');
            if (!empty($session)) {
                $data = $this->security->xss_clean($data);
                $this->load->view("admin/payroll/get_location_pdepartments", $data);
            } else {
                redirect('admin/');
            }
        }
        // Datatables Variables
        $draw = intval($this->input->get("draw"));
        $start = intval($this->input->get("start"));
        $length = intval($this->input->get("length"));
    }

    public function get_department_pdesignations()
    {
        $data['title'] = $this->Core_model->site_title();
        $id = $this->uri->segment(4);

        $data = array(
            'department_id' => $id,
            'all_designations' => $this->Designation_model->all_designations(),
        );
        $session = $this->session->userdata('username');
        if (!empty($session)) {
            $this->load->view("admin/payroll/get_department_pdesignations", $data);
        } else {
            redirect('admin/');
        }
        // Datatables Variables
        $draw = intval($this->input->get("draw"));
        $start = intval($this->input->get("start"));
        $length = intval($this->input->get("length"));
    }

    // Validate and update info in database // update_status
    public function update_payroll_status()
    {
        if ($this->input->post('type') == 'update_status') {
            /* Define return | here result is used to return user data and error for error message */
            $Return = array('result' => '', 'error' => '', 'csrf_hash' => '');
            $Return['csrf_hash'] = $this->security->get_csrf_hash();
            if ($this->input->post('status') === '') {
                $Return['error'] = $this->lang->line('xin_error_template_status');
            }
            if ($Return['error'] != '') {
                $this->output($Return);
            }
            $data = array(
                'status' => $this->input->post('status'),
            );
            $id = $this->input->post('payroll_id');
            $result = $this->Payroll_model->update_payroll_status($data, $id);
            if ($result == TRUE) {
                if ($this->input->post('status') == 1) {
                    $Return['result'] = $this->lang->line('xin_role_first_level_approved');
                } else if ($this->input->post('status') == 2) {
                    $Return['result'] = $this->lang->line('xin_approved_final_payroll_title');
                } else {
                    $Return['result'] = $this->lang->line('xin_disabled_payroll_title');
                }
            } else {
                $Return['error'] = $this->lang->line('xin_error_msg');
            }
            $this->output($Return);
            exit;
        }
    }

    public function get_workstations()
    {

        $data['title'] = $this->Core_model->site_title();
        $id = $this->uri->segment(4);

        $data = array(
            'company_id' => $id
        );
        $session = $this->session->userdata('username');
        if (!empty($session)) {
            $this->load->view("admin/reports/get_workstations", $data);
        } else {
            redirect('admin/');
        }
        // Datatables Variables
        $draw = intval($this->input->get("draw"));
        $start = intval($this->input->get("start"));
        $length = intval($this->input->get("length"));
    }
}
