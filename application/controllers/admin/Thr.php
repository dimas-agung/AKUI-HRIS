<?php defined('BASEPATH') or exit('No direct script access allowed');

$path = APPPATH . DIRECTORY_SEPARATOR . 'libraries' . DIRECTORY_SEPARATOR . 'traits' . DIRECTORY_SEPARATOR;
require_once($path . 'DailyThr.php');
require_once($path . 'MonthlyThr.php');

/**
 * @property THR_model $THR_model
 * @property Roles_model $Roles_model
 * @property Payroll_model $Payroll_model
 * @property Employees_model $Employees_model
 * @property Timesheet_model $Timesheet_model
 * @property Department_model $Department_model
 * @property Designation_model $Designation_model
 */
class Thr extends MY_Controller
{
    use DailyThr;
    use MonthlyThr;

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
        $this->load->model("Finance_model");
        $this->load->model("Roles_model");
        $this->load->model("Employees_model");
        $this->load->model("Company_model");
        $this->load->model("Timesheet_model");
        $this->load->model("Training_model");
        $this->load->model("Trainers_model");
        $this->load->model("Awards_model");
        $this->load->model("Travel_model");
        $this->load->model("Transfers_model");
        $this->load->model("Promotion_model");
        $this->load->model("Complaints_model");
        $this->load->model("Warning_model");
        $this->load->model('Overtime_model');
        $this->load->model('THR_model');

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
    // PROSES => THR BULANAN
    // ===============================================================================================

    // ===========================================================================================
    // TABEL
    // ===========================================================================================

    // generate payslips

    // =======================================================================================
    // PROSES : SIMPAN
    // =======================================================================================





    // pay monthly > create payslip
    public function pay_salary()
    {
        $session = $this->session->userdata('username');
        if (empty($session)) {
            redirect('admin/');
        }
        $data['title'] = $this->Core_model->site_title();

        $id            = $this->input->get('employee_id');

        // get addd by > template
        $user = $this->Core_model->read_user_info($id);

        // $result     = $this->Payroll_model->read_template_information($user[0]->monthly_grade_id);
        // $department = $this->Department_model->read_department_information($user[0]->department_id);

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
            $this->load->view('admin/thr/dialog_make_payment', $data);
        } else {
            redirect('admin/');
        }
    }

    public function pay_thr_del()
    {
        $session = $this->session->userdata('username');
        if (empty($session)) {
            redirect('admin/');
        }
        $data['title'] = $this->Core_model->site_title();

        $payslip_id    = $this->input->get('payslip_id');

        $payslip       = $this->Core_model->read_thr_info($payslip_id);

        $user          = $this->Core_model->read_user_info($payslip[0]->employee_id);

        $designation   = $this->Designation_model->read_designation_information($payslip[0]->designation_id);
        if (!is_null($designation)) {
            $designation_name = $designation[0]->designation_name;
        } else {
            $designation_name = '';
        }
        // department
        $department    = $this->Department_model->read_department_information($payslip[0]->department_id);
        if (!is_null($department)) {
            $department_name = $department[0]->department_name;
        } else {
            $department_name = '';
        }

        // Karyawan Masa kerja
        date_default_timezone_set("Asia/Jakarta");

        $tanggal1 = new DateTime($user[0]->date_of_joining);
        $tanggal2 = new DateTime();

        if ($tanggal2->diff($tanggal1)->y == 0) {
            $selisih   = $tanggal2->diff($tanggal1)->m . ' bln';
            $jum_bulan = $tanggal2->diff($tanggal1)->m;
        } else {
            $selisih   = $tanggal2->diff($tanggal1)->y . ' thn' . ' ' . $tanggal2->diff($tanggal1)->m . ' bln';
            $jum_bulan = $tanggal2->diff($tanggal1)->y * 12 + ($tanggal2->diff($tanggal1)->m);
        }

        if ($jum_bulan > 12) {
            $masa = "THR Penuh";
        } else {
            if ($jum_bulan == 0) {
                $masa = "Tidak Dapat THR";
            } else {
                $masa = "THR Prorate";
            }
        }

        //$location = $this->Location_model->read_location_information($department[0]->location_id);
        $data = array(
            'payslip_id'                     => $payslip_id,
            'department_name'                => $department_name,
            'designation_name'               => $designation_name,
            'selisih'                        => $selisih,
            'masa'                             => $masa,

            'employee_name'                  => $user[0]->first_name . ' ' . $user[0]->last_name,

            'company_id'                     => $payslip[0]->company_id,
            'employee_date_of_joining'    => $payslip[0]->doj,
            'tahun_thr'                   => $payslip[0]->tahun_thr,

            'basic_salary'                => $payslip[0]->basic_salary,
            'jumlah_tunj_jabatan'         => $payslip[0]->jumlah_tunj_jabatan,
            'total_jumlah'                => $payslip[0]->total_jumlah,


            'net_salary'                    => $payslip[0]->net_salary

        );
        if (!empty($session)) {
            $this->load->view('admin/thr/dialog_make_payment_bulanan_delete', $data);
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

            $result      = $this->Payroll_model->delete_thr_bulanan($id);

            // echo "<pre>";
            // print_r($this->db->last_query());
            // echo "</pre>";
            // die();

            if (isset($id)) {

                $Return['result'] = 'THR Bulanan Berhasil Dihapus';
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
        $this->Payroll_model->delete_thr_bulanan($id);
    }

    // =======================================================================================
    // TAMPIL => SLIP GAJI
    // =======================================================================================
    public function payslip()
    {
        $session = $this->session->userdata('username');
        if (empty($session)) {
            redirect('admin/');
        }
        //$data['title'] = $this->Core_model->site_title();
        $key = $this->uri->segment(5);

        $result = $this->Payroll_model->read_thr_payslip_info_key($key);
        if (is_null($result)) {
            redirect('admin/thr/bulanan');
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
            'title'                      => 'Slip Gaji Bulanan Karyawan | ' . $this->Core_model->site_title(),
            'icon'                       => '<i class="fa fa-money"></i>',
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
            'net_salary'                 => $result[0]->net_salary,

            // 'other_payment' => $result[0]->other_payment,
            'payslip_key'                => $result[0]->payslip_key,
            'payslip_type'               => $result[0]->payslip_type,
            'hours_worked'               => $result[0]->hours_worked,
            'pay_comments'               => $result[0]->pay_comments,
            'is_payment'                 => $result[0]->is_payment,
            'approval_status'            => $result[0]->status,
        );
        $data['breadcrumbs'] = $this->lang->line('xin_payroll_employee_payslip');
        $data['path_url'] = 'payslip';
        $role_resources_ids = $this->Core_model->user_role_resource();
        if (!empty($session)) {
            if ($result[0]->payslip_type == 'hourly') {
                $data['subview'] = $this->load->view("admin/thr/hourly_payslip", $data, TRUE);
            } else {
                $data['subview'] = $this->load->view("admin/thr/payslip", $data, TRUE);
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
            redirect('admin/thr/bulanan');
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

        // ===========================================================
        // Pengurangan
        //============================================================

        // BPJS
        $count_statutory_deductions = $this->Employees_model->count_employee_statutory_deductions_payslip($payment[0]->payslip_id);
        $statutory_deductions = $this->Employees_model->set_employee_statutory_deductions_payslip($payment[0]->payslip_id);

        $bpjs_amount = 0;
        foreach ($statutory_deductions->result() as $sl_statutory_deductions) {
            $bpjs_amount += $sl_statutory_deductions->bpjs_kes_amount + $sl_statutory_deductions->bpjs_tk_amount;
        }

        // Pajak
        $count_other_payments = $this->Employees_model->count_employee_other_payments_payslip($payment[0]->payslip_id);
        $other_payments = $this->Employees_model->set_employee_other_payments_payslip($payment[0]->payslip_id);

        $other_payments_amount = 0;
        foreach ($other_payments->result() as $sl_other_payments) {
            $other_payments_amount += $sl_other_payments->other_payments_amount;
        }

        // Pinjaman
        $count_loan = $this->Employees_model->count_employee_deductions_payslip($payment[0]->payslip_id);
        $loan = $this->Employees_model->set_employee_deductions_payslip($payment[0]->payslip_id);

        $loan_amount = 0;
        foreach ($loan->result() as $sl_loan) {
            $loan_amount += $sl_loan->loan_de_amount;
        }

        // Potongan
        $count_attedance = $this->Employees_model->count_employee_deductions_payslip($payment[0]->payslip_id);
        $attedance  = $this->Employees_model->set_employee_deductions_payslip($payment[0]->payslip_id);

        $attedance_amount = 0;
        foreach ($attedance->result() as $sl_attedance) {
            $attedance_amount += $sl_attedance->potongan_absen;
        }

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
                        <td width="26%;"> LAIN-LAIN </td>

                        <td width="19%;"> </td>
                    </tr>

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
        $total_deduction  = $loan_amount + $bpjs_amount + $other_payments_amount + $attedance_amount;
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
            $this->load->view('admin/thr/dialog_templates', $data);
        } else {
            redirect('admin/');
        }
    }

    // ===========================================================================================
    // DETAIL KARYAWAN : GAJI BULANAN
    // ===========================================================================================

    // =======================================================================================
    // DETAIL
    // =======================================================================================
    public function bulanan_detail()
    {

        $session = $this->session->userdata('username');
        if (empty($session)) {
            redirect('admin/');
        }

        $role_resources_ids = $this->Core_model->user_role_resource();
        $check_role = $this->Employees_model->read_employee_information($session['user_id']);
        if (!in_array('1010', $role_resources_ids)) {
            redirect('admin/thr/bulanan');
        }

        $id = $this->uri->segment(4);
        $result = $this->Employees_model->read_employee_information($id);
        if (is_null($result)) {
            redirect('admin/thr/bulanan');
        }

        $data = array(
            'breadcrumbs'  => 'Edit Komponen THR',
            'icon'         => '<i class="fa fa-pencil"></i>',
            'path_url'     => 'employees_detail_payroll_bulanan',
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


        $data['subview'] = $this->load->view("admin/thr/bulanan_detail", $data, TRUE);

        $this->load->view('admin/layout/layout_main', $data); //page load

        // Datatables Variables
        $draw   = intval($this->input->get("draw"));
        $start  = intval($this->input->get("start"));
        $length = intval($this->input->get("length"));
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
            $this->load->view("admin/thr/bulanan_detail", $data);
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
            $this->load->view('admin/thr/dialog_bulanan_detail', $data);
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
            $this->load->view("admin/thr/bulanan_detail", $data);
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
            $this->load->view('admin/thr/dialog_bulanan_detail', $data);
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


    // ===============================================================================================
    // PROSES => THR HARIAN
    // ===============================================================================================

    // ===========================================================================================
    // TABEL
    // ===========================================================================================

    // generate payslips


    // ================================================================================
    // LAIN
    // ================================================================================

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
            $this->load->view("admin/thr/get_employees", $data);
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
                $this->load->view("admin/thr/get_company_plocations", $data);
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
                $this->load->view("admin/thr/get_location_pdepartments", $data);
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
            $this->load->view("admin/thr/get_department_pdesignations", $data);
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
}
