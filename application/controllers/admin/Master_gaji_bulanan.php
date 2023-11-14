<?php

defined('BASEPATH') or exit('No direct script access allowed');

/**
 * @property Timesheet_model $Timesheet_model
 * @property Employees_model $Employees_model
 * @property Skala_upah_model $Skala_upah_model
 * @property Designation_model $Designation_model
 */
class Master_gaji_bulanan extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();
        //load the model
        $this->load->model("Timesheet_model");
        $this->load->model("Employees_model");
        $this->load->model("Core_model");
        $this->load->library('email');
        $this->load->model("Department_model");
        $this->load->model("Designation_model");
        $this->load->model("Skala_upah_model");
        $this->load->model("Roles_model");
        $this->load->model("Location_model");
        $this->load->model("Company_model");
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

    // =============================================================================
    // 0810. ATUR JADWAL KERJA REGULER
    // =============================================================================

    // Gaji Pokok
    public function gajipokok()
    {

        $session = $this->session->userdata('username');
        if (empty($session)) {
            redirect('admin/');
        }
        $data['title']             = 'Atur Gaji Pokok | ' . $this->Core_model->site_title();
        $data['icon']              = '<i class="fa fa-share-alt"></i>';
        $data['breadcrumbs']       = 'Atur Gaji Pokok';
        $data['path_url']          = 'master_gaji_bulanan_gaji_pokok';

        $data['get_all_companies'] = $this->Company_model->get_company();
        $data['all_bulan_gaji']    = $this->Core_model->all_bulan_gaji();

        $role_resources_ids        = $this->Core_model->user_role_resource();
        if (in_array('0810', $role_resources_ids)) {
            if (!empty($session)) {
                $data['subview'] = $this->load->view("admin/master_gaji_bulanan/gajipokok", $data, TRUE);
                $this->load->view('admin/layout/layout_main', $data); //page load
            } else {
                redirect('admin/');
            }
        } else {
            redirect('admin/dashboard');
        }
    }
    public function gajipokok_add()
    {
        // if ($this->input->post('add_type') == 'office_shift') {
            /* Define return | here result is used to return user data and error for error message */
            $Return = array('result' => '', 'error' => '', 'csrf_hash' => '');
            $Return['csrf_hash'] = $this->security->get_csrf_hash();

            $data = array(

                'company_id'         => $this->input->post('company_id'),
                'nominal'         => $this->input->post('nominal'),
               
                'start_at'           => $this->input->post('start_date'),
                'end_at'           => $this->input->post('end_date'),
               
            );
            $result = $this->db->insert('employee_basic_salary', $data);
            // $result = $this->Timesheet_model->add_office_shift_record($data);
            // var_dump($result);return;
            // if ($result == TRUE) {
            // } else {
            //     $Return['error'] = $this->lang->line('xin_error_msg');
            // }
            redirect(base_url('admin/master_gaji_bulanan/gajipokok'));
            $Return['result'] = $this->lang->line('xin_success_reguler_added');
            $this->output($Return);
            exit;
        // }
    }


    // Jam Reguler > Daftar
    public function gajipokok_list()
    {
        $data['title'] = $this->Core_model->site_title();
        $session = $this->session->userdata('username');
        if (!empty($session)) {
            $this->load->view("admin/master_gaji_bulanan/gajipokok", $data);
        } else {
            redirect('admin/');
        }
        // Datatables Variables
        $draw = intval($this->input->get("draw"));
        $start = intval($this->input->get("start"));
        $length = intval($this->input->get("length"));


        $role_resources_ids = $this->Core_model->user_role_resource();
        $user_info = $this->Core_model->read_user_info($session['user_id']);

        $sql = "SELECT employee_basic_salary.*, xin_companies.name as company_name from employee_basic_salary LEFT JOIN xin_companies ON xin_companies.company_id = employee_basic_salary.company_id";
		// echo $sql;return;
        $result = $this->db->query($sql)->result();


        $data = array();

        foreach ($result as $r) {

            $data[] = array(
                $r->company_name,
                $r->nominal,
                $r->start_at,
                $r->end_at,
            );
        }

        $output = array(
            "draw" => $draw,
            "recordsTotal" => $this->db->query($sql)->num_rows(),
            "recordsFiltered" => $this->db->query($sql)->num_rows(),
            "data" => $data
        );
        echo json_encode($output);
        exit();
    }

    // Gaji Pokok
    public function master_grade()
    {

        $session = $this->session->userdata('username');
        if (empty($session)) {
            redirect('admin/');
        }
        $data['title']             = 'Master Grade | ' . $this->Core_model->site_title();
        $data['icon']              = '<i class="fa fa-share-alt"></i>';
        $data['breadcrumbs']       = 'Master Grade';
        $data['path_url']          = 'master_gaji_bulanan_master_grade';

        $data['get_all_companies'] = $this->Company_model->get_company();
        $data['all_bulan_gaji']    = $this->Core_model->all_bulan_gaji();

        $role_resources_ids        = $this->Core_model->user_role_resource();
        if (in_array('0810', $role_resources_ids)) {
            if (!empty($session)) {
                $data['subview'] = $this->load->view("admin/master_gaji_bulanan/master_grade", $data, TRUE);
                $this->load->view('admin/layout/layout_main', $data); //page load
            } else {
                redirect('admin/');
            }
        } else {
            redirect('admin/dashboard');
        }
    }
    public function master_grade_add()
    {
        // if ($this->input->post('add_type') == 'office_shift') {
            /* Define return | here result is used to return user data and error for error message */
            $Return = array('result' => '', 'error' => '', 'csrf_hash' => '');
            $Return['csrf_hash'] = $this->security->get_csrf_hash();

            $data = array(

                'name'         => 'Grade '.$this->input->post('level'). $this->input->post('grade'),
                'level'         => $this->input->post('level'),
                'grade'         => $this->input->post('grade'),
            );
            $result = $this->db->insert('employee_grade', $data);
            // $result = $this->Timesheet_model->add_office_shift_record($data);
            // var_dump($result);return;
            // if ($result == TRUE) {
            // } else {
            //     $Return['error'] = $this->lang->line('xin_error_msg');
            // }
            redirect(base_url('admin/master_gaji_bulanan/master_grade'));
            $Return['result'] = $this->lang->line('xin_success_reguler_added');
            $this->output($Return);
            exit;
        // }
    }
    // 
    public function tunjangan_karyawan()
    {

        $session = $this->session->userdata('username');
        if (empty($session)) {
            redirect('admin/');
        }
        $data['title']             = 'Master Grade | ' . $this->Core_model->site_title();
        $data['icon']              = '<i class="fa fa-share-alt"></i>';
        $data['breadcrumbs']       = 'Master Grade';
        $data['path_url']          = 'master_gaji_bulanan_master_grade';

        $data['get_all_companies'] = $this->Company_model->get_company();
        $data['all_bulan_gaji']    = $this->Core_model->all_bulan_gaji();
        
        $sql = "SELECT *from employee_grade";
		// echo $sql;return;
        $result = $this->db->query($sql)->result();
        $data['employee_grade']    = $result;
        // $sql = "SELECT employee_basic_salary.*, xin_companies.name as company_name from employee_grade LEFT JOIN xin_companies ON xin_companies.company_id = employee_basic_salary.company_id";

        $role_resources_ids        = $this->Core_model->user_role_resource();
        if (in_array('0810', $role_resources_ids)) {
            if (!empty($session)) {
                $data['subview'] = $this->load->view("admin/master_gaji_bulanan/tunjangan_karyawan", $data, TRUE);
                $this->load->view('admin/layout/layout_main', $data); //page load
            } else {
                redirect('admin/');
            }
        } else {
            redirect('admin/dashboard');
        }
    }
    public function tunjangan_karyawan_add()
    {
        // if ($this->input->post('add_type') == 'office_shift') {
            /* Define return | here result is used to return user data and error for error message */
            $Return = array('result' => '', 'error' => '', 'csrf_hash' => '');
            $Return['csrf_hash'] = $this->security->get_csrf_hash();

            $data = array(

                'employee_grade_id'         => $this->input->post('employee_grade_id'),
                'tunjangan_grade'           => $this->input->post('tunjangan_grade'),
                'tunjangan_komunikasi'      => $this->input->post('tunjangan_komunikasi'),
                'tunjangan_tempat_tinggal'  => $this->input->post('tunjangan_tempat_tinggal'),
                'tunjangan_transportasi'    => $this->input->post('tunjangan_transportasi'),
                'tunjangan_benefit'         => $this->input->post('tunjangan_benefit'),
                'start_at'                  => date('Y-m-d'),
                // 'end_at'         => $this->input->post('end_at'),
            );
            $result = $this->db->insert('employee_grade_allowance', $data);
            // $result = $this->Timesheet_model->add_office_shift_record($data);
            // var_dump($result);return;
            // if ($result == TRUE) {
            // } else {
            //     $Return['error'] = $this->lang->line('xin_error_msg');
            // }
            redirect(base_url('admin/master_gaji_bulanan/tunjangan_karyawan'));
            $Return['result'] = $this->lang->line('xin_success_reguler_added');
            $this->output($Return);
            exit;
        // }
    }
    public function tunjangan_karyawan_list()
    {
        $data['title'] = $this->Core_model->site_title();
        $session = $this->session->userdata('username');
        if (!empty($session)) {
            $this->load->view("admin/master_gaji_bulanan/tunjangan_karyawan", $data);
        } else {
            redirect('admin/');
        }
        // Datatables Variables
        $draw = intval($this->input->get("draw"));
        $start = intval($this->input->get("start"));
        $length = intval($this->input->get("length"));


        $role_resources_ids = $this->Core_model->user_role_resource();
        $user_info = $this->Core_model->read_user_info($session['user_id']);

        $sql = "SELECT tunjangan.*,employee_grade.name from employee_grade_allowance as tunjangan LEFT JOIN employee_grade ON employee_grade.id = tunjangan.employee_grade_id ORDER BY employee_grade.name";
		// echo $sql;return;
        $result = $this->db->query($sql)->result();


        $data = array();

        foreach ($result as $r) {

            $data[] = array(
                $r->name,
                $r->tunjangan_grade,
                $r->tunjangan_komunikasi,
                $r->tunjangan_tempat_tinggal,
                $r->tunjangan_transportasi,
                $r->tunjangan_benefit,
                '<div style="text-align: center;">
                    <button type="button" class="btn btn-danger" onclick="setEndAt('.$r->id.')">Nonaktif</button>
                </div>'
            );
        }

        $output = array(
            "draw" => $draw,
            "recordsTotal" => $this->db->query($sql)->num_rows(),
            "recordsFiltered" => $this->db->query($sql)->num_rows(),
            "data" => $data
        );
        echo json_encode($output);
        exit();
    }
    public function tunjangan_karyawan_add_end()
    {
        // if ($this->input->post('add_type') == 'office_shift') {
            /* Define return | here result is used to return user data and error for error message */
            $Return = array('result' => '', 'error' => '', 'csrf_hash' => '');
            $Return['csrf_hash'] = $this->security->get_csrf_hash();

            $id = $this->input->post('id');

            $data = array('end_at' => date('Y-m-d'));
            $this->db->where('id', $id);
            $this->db->update('employee_grade_allowance', $data);

            redirect(base_url('admin/master_gaji_bulanan/tunjangan_karyawan'));
            $Return['result'] = $this->lang->line('xin_success_reguler_added');
            $this->output($Return);
            exit;
        // }
    }

    // Validate and add info in database
    public function add_office_reguler()
    {
        if ($this->input->post('add_type') == 'office_shift') {
            /* Define return | here result is used to return user data and error for error message */
            $Return = array('result' => '', 'error' => '', 'csrf_hash' => '');
            $Return['csrf_hash'] = $this->security->get_csrf_hash();

            /* Server side PHP input validation */
            if ($this->input->post('company_id') === '') {
                $Return['error'] = $this->lang->line('error_company_field');
            } else if ($this->input->post('shift_name') === '') {
                $Return['error'] = $this->lang->line('xin_error_shift_name_field');
            } else if ($this->input->post('payroll_id') === '') {
                $Return['error'] = $this->lang->line('xin_error_bulan_name_field');
            } else if ($this->input->post('jenis') === '') {
                $Return['error'] = $this->lang->line('xin_error_jenis_field');
            } else if ($this->input->post('start_date') === '') {
                $Return['error'] = $this->lang->line('xin_error_bulan_awal');
            } else if ($this->input->post('end_date') === '') {
                $Return['error'] = $this->lang->line('xin_error_bulan_akhir');
            } else if ($this->input->post('monday_in_time') != '' && $this->input->post('monday_out_time') === '') {
                $Return['error'] = $this->lang->line('xin_error_shift_monday_timeout');
            } else if ($this->input->post('tuesday_in_time') != '' && $this->input->post('tuesday_out_time') === '') {
                $Return['error'] = $this->lang->line('xin_error_shift_tuesday_timeout');
            } else if ($this->input->post('wednesday_in_time') != '' && $this->input->post('wednesday_out_time') === '') {
                $Return['error'] = $this->lang->line('xin_error_shift_wednesday_timeout');
            } else if ($this->input->post('thursday_in_time') != '' && $this->input->post('thursday_out_time') === '') {
                $Return['error'] = $this->lang->line('xin_error_shift_thursday_timeout');
            } else if ($this->input->post('friday_in_time') != '' && $this->input->post('friday_out_time') === '') {
                $Return['error'] = $this->lang->line('xin_error_shift_friday_timeout');
            } else if ($this->input->post('saturday_in_time') != '' && $this->input->post('saturday_out_time') === '') {
                $Return['error'] = $this->lang->line('xin_error_shift_saturday_timeout');
            } else if ($this->input->post('sunday_in_time') != '' && $this->input->post('sunday_out_time') === '') {
                $Return['error'] = $this->lang->line('xin_error_shift_sunday_timeout');
            }

            if ($Return['error'] != '') {
                $this->output($Return);
            }

            // if(isset($_POST['employee_id'])) {
            // 	$employee_ids = implode(',',$_POST['employee_id']);
            // 	$employee_id = $employee_ids;
            // } else {
            // 	$employee_id = '';
            // }

            $data = array(

                'company_id'         => $this->input->post('company_id'),
                'payroll_id'         => $this->input->post('payroll_id'),
                'jenis'              => $this->input->post('jenis'),
                'shift_name'         => $this->input->post('shift_name'),
                'start_date'         => $this->input->post('start_date'),
                'end_date'           => $this->input->post('end_date'),
                'monday_in_time'     => $this->input->post('monday_in_time'),
                'monday_out_time'    => $this->input->post('monday_out_time'),
                'tuesday_in_time'    => $this->input->post('tuesday_in_time'),
                'tuesday_out_time'   => $this->input->post('tuesday_out_time'),
                'wednesday_in_time'  => $this->input->post('wednesday_in_time'),
                'wednesday_out_time' => $this->input->post('wednesday_out_time'),
                'thursday_in_time'   => $this->input->post('thursday_in_time'),
                'thursday_out_time'  => $this->input->post('thursday_out_time'),
                'friday_in_time'     => $this->input->post('friday_in_time'),
                'friday_out_time'    => $this->input->post('friday_out_time'),
                'saturday_in_time'   => $this->input->post('saturday_in_time'),
                'saturday_out_time'  => $this->input->post('saturday_out_time'),
                'sunday_in_time'     => $this->input->post('sunday_in_time'),
                'sunday_out_time'    => $this->input->post('sunday_out_time'),
                'created_at'         => date('Y-m-d')
            );
            $result = $this->Timesheet_model->add_office_shift_record($data);

            if ($result == TRUE) {
                $Return['result'] = $this->lang->line('xin_success_reguler_added');
            } else {
                $Return['error'] = $this->lang->line('xin_error_msg');
            }
            $this->output($Return);
            exit;
        }
    }

    // Validate and update info in database
    public function edit_office_reguler()
    {
        $id = $this->uri->segment(4);

        /* Define return | here result is used to return user data and error for error message */
        $Return = array('result' => '', 'error' => '', 'csrf_hash' => '');
        $Return['csrf_hash'] = $this->security->get_csrf_hash();

        /* Server side PHP input validation */
        if ($this->input->post('company_id') === '') {
            $Return['error'] = $this->lang->line('error_company_field');
        } else if ($this->input->post('shift_name') === '') {
            $Return['error'] = $this->lang->line('xin_error_shift_name_field');
        } else if ($this->input->post('payroll_id') === '') {
            $Return['error'] = $this->lang->line('xin_error_bulan_name_field');
        } else if ($this->input->post('jenis') === '') {
            $Return['error'] = $this->lang->line('xin_error_jenis_field');
        } else if ($this->input->post('start_date') === '') {
            $Return['error'] = $this->lang->line('xin_error_bulan_awal');
        } else if ($this->input->post('end_date') === '') {
            $Return['error'] = $this->lang->line('xin_error_bulan_akhir');
        } else if ($this->input->post('monday_in_time') != '' && $this->input->post('monday_out_time') === '') {
            $Return['error'] = $this->lang->line('xin_error_shift_monday_timeout');
        } else if ($this->input->post('tuesday_in_time') != '' && $this->input->post('tuesday_out_time') === '') {
            $Return['error'] = $this->lang->line('xin_error_shift_tuesday_timeout');
        } else if ($this->input->post('wednesday_in_time') != '' && $this->input->post('wednesday_out_time') === '') {
            $Return['error'] = $this->lang->line('xin_error_shift_wednesday_timeout');
        } else if ($this->input->post('thursday_in_time') != '' && $this->input->post('thursday_out_time') === '') {
            $Return['error'] = $this->lang->line('xin_error_shift_thursday_timeout');
        } else if ($this->input->post('friday_in_time') != '' && $this->input->post('friday_out_time') === '') {
            $Return['error'] = $this->lang->line('xin_error_shift_friday_timeout');
        } else if ($this->input->post('saturday_in_time') != '' && $this->input->post('saturday_out_time') === '') {
            $Return['error'] = $this->lang->line('xin_error_shift_saturday_timeout');
        } else if ($this->input->post('sunday_in_time') != '' && $this->input->post('sunday_out_time') === '') {
            $Return['error'] = $this->lang->line('xin_error_shift_sunday_timeout');
        }


        if ($Return['error'] != '') {
            $this->output($Return);
        }

        //   	if(isset($_POST['employee_id'])) {
        // 	$employee_ids = implode(',',$_POST['employee_id']);
        // 	$employee_id = $employee_ids;
        // } else {
        // 	$employee_id = '';
        // }

        $data = array(
            'shift_name'         => $this->input->post('shift_name'),
            'company_id'         => $this->input->post('company_id'),
            // 'employee_id'        => $employee_id,
            'payroll_id'         => $this->input->post('payroll_id'),
            'jenis'              => $this->input->post('jenis'),
            'start_date'         => $this->input->post('start_date'),
            'end_date'           => $this->input->post('end_date'),
            'monday_in_time'     => $this->input->post('monday_in_time'),
            'monday_out_time'    => $this->input->post('monday_out_time'),
            'tuesday_in_time'    => $this->input->post('tuesday_in_time'),
            'tuesday_out_time'   => $this->input->post('tuesday_out_time'),
            'wednesday_in_time'  => $this->input->post('wednesday_in_time'),
            'wednesday_out_time' => $this->input->post('wednesday_out_time'),
            'thursday_in_time'   => $this->input->post('thursday_in_time'),
            'thursday_out_time'  => $this->input->post('thursday_out_time'),
            'friday_in_time'     => $this->input->post('friday_in_time'),
            'friday_out_time'    => $this->input->post('friday_out_time'),
            'saturday_in_time'   => $this->input->post('saturday_in_time'),
            'saturday_out_time'  => $this->input->post('saturday_out_time'),
            'sunday_in_time'     => $this->input->post('sunday_in_time'),
            'sunday_out_time'    => $this->input->post('sunday_out_time'),
            'created_at'         => date('Y-m-d')
        );

        $result = $this->Timesheet_model->update_shift_record($data, $id);

        // echo "<pre>";
        // print_r($this->db->last_query());
        // echo "</pre>";
        // die();

        if ($result == TRUE) {
            $Return['result'] = $this->lang->line('xin_success_reguler_updated');
        } else {
            $Return['error'] = $this->lang->line('xin_error_msg');
        }
        $this->output($Return);
        exit;
    }

    // get record of office shift
    public function read_reguler_record()
    {
        $data['title']   = $this->Core_model->site_title();
        $office_shift_id = $this->input->get('office_shift_id');
        $result          = $this->Timesheet_model->read_office_shift_information($office_shift_id);

        $data = array(
            'office_shift_id'    => $result[0]->office_shift_id,
            'company_id'         => $result[0]->company_id,
            'jenis'              => $result[0]->jenis,
            'employee_id'          => $result[0]->employee_id,
            'payroll_id'         => $result[0]->payroll_id,
            'start_date'         => $result[0]->start_date,
            'end_date'           => $result[0]->end_date,
            'shift_name'         => $result[0]->shift_name,
            'monday_in_time'     => $result[0]->monday_in_time,
            'monday_out_time'    => $result[0]->monday_out_time,
            'tuesday_in_time'    => $result[0]->tuesday_in_time,
            'tuesday_out_time'   => $result[0]->tuesday_out_time,
            'wednesday_in_time'  => $result[0]->wednesday_in_time,
            'wednesday_out_time' => $result[0]->wednesday_out_time,
            'thursday_in_time'   => $result[0]->thursday_in_time,
            'thursday_out_time'  => $result[0]->thursday_out_time,
            'friday_in_time'     => $result[0]->friday_in_time,
            'friday_out_time'    => $result[0]->friday_out_time,
            'saturday_in_time'   => $result[0]->saturday_in_time,
            'saturday_out_time'  => $result[0]->saturday_out_time,
            'sunday_in_time'     => $result[0]->sunday_in_time,
            'sunday_out_time'    => $result[0]->sunday_out_time,
            'get_all_companies'  => $this->Company_model->get_company(),
            'all_bulan_gaji'     => $this->Core_model->all_bulan_gaji(),
            'all_employees'      => $this->Core_model->all_employees()
        );
        $session = $this->session->userdata('username');
        if (!empty($session)) {
            $this->load->view('admin/pengaturan/dialog_office_reguler', $data);
        } else {
            redirect('admin/');
        }
    }

    // =============================================================================
    // 0820. ATUR JADWAL KERJA SHIFT
    // =============================================================================

    // Jam Shift
    public function office_shift()
    {

        $session = $this->session->userdata('username');
        if (empty($session)) {
            redirect('admin/');
        }
        $data['title']             = 'Atur Jadwal Shift | ' . $this->Core_model->site_title();
        $data['icon']              = '<i class="fa fa-sliders"></i>';
        $data['breadcrumbs']       = 'Atur Jadwal Shift';
        $data['path_url']          = 'pengaturan_office_shift';

        $data['get_all_companies'] = $this->Company_model->get_company();
        $data['all_bulan_gaji']    = $this->Core_model->all_bulan_gaji();
        $data['all_jam_shift']     = $this->Core_model->all_jam_shift();

        $role_resources_ids        = $this->Core_model->user_role_resource();
        if (in_array('0821', $role_resources_ids)) {
            if (!empty($session)) {
                $data['subview'] = $this->load->view("admin/pengaturan/office_shift", $data, TRUE);
                $this->load->view('admin/layout/layout_main', $data); //page load
            } else {
                redirect('admin/');
            }
        } else {
            redirect('admin/dashboard');
        }
    }

    // Tabel
    public function office_shift_list()
    {

        $data['title'] = $this->Core_model->site_title();
        $session = $this->session->userdata('username');
        if (!empty($session)) {
            $this->load->view("admin/pengaturan/office_shift", $data);
        } else {
            redirect('admin/');
        }
        // Datatables Variables
        $draw   = intval($this->input->get("draw"));
        $start  = intval($this->input->get("start"));
        $length = intval($this->input->get("length"));

        $role_resources_ids = $this->Core_model->user_role_resource();
        $user_info          = $this->Core_model->read_user_info($session['user_id']);

        $office_shift = $this->Timesheet_model->get_office_shifts();

        $data = array();

        foreach ($office_shift->result() as $r) {

            /* get Office Shift info*/
            // ---------------------> minggu 1
            if ($r->T21 == 'K') {
                $T21 = '<span class="blink blink-one merah"> x </span>';
            } else {
                $T21 = $r->T21;
            }

            if ($r->T22 == 'K') {
                $T22 = '<span class="blink blink-one merah"> x </span>';
            } else {
                $T22 = $r->T22;
            }

            if ($r->T23 == 'K') {
                $T23 = '<span class="blink blink-one merah"> x </span>';
            } else {
                $T23 = $r->T23;
            }

            if ($r->T24 == 'K') {
                $T24 = '<span class="blink blink-one merah"> x </span>';
            } else {
                $T24 = $r->T24;
            }

            if ($r->T25 == 'K') {
                $T25 = '<span class="blink blink-one merah"> x </span>';
            } else {
                $T25 = $r->T25;
            }

            if ($r->T26 == 'K') {
                $T26 = '<span class="blink blink-one merah"> x </span>';
            } else {
                $T26 = $r->T26;
            }

            if ($r->T27 == 'K' || $r->T27 == '') {
                $T27 = '<span class="blink blink-one merah"> x </span>';
            } else {
                $T27 = $r->T27;
            }

            // ---------------------> minggu 2

            if ($r->T28 == 'K' || $r->T28 == '') {
                $T28 = '<span class="blink blink-one merah"> x </span>';
            } else {
                $T28 = $r->T28;
            }

            if ($r->T29 == 'K') {
                $T29 = '<span class="blink blink-one merah"> x </span>';
            } else {
                $T29 = $r->T29;
            }

            if ($r->T30 == 'K') {
                $T30 = '<span class="blink blink-one merah"> x </span>';
            } else {
                $T30 = $r->T30;
            }

            if ($r->T31 == 'K') {
                $T31 = '<span class="blink blink-one merah"> x </span>';
            } else {
                $T31 = $r->T31;
            }

            if ($r->T01 == 'K') {
                $T01 = '<span class="blink blink-one merah"> x </span>';
            } else {
                $T01 = $r->T01;
            }

            if ($r->T02 == 'K') {
                $T02 = '<span class="blink blink-one merah"> x </span>';
            } else {
                $T02 = $r->T02;
            }

            if ($r->T03 == 'K') {
                $T03 = '<span class="blink blink-one merah"> x </span>';
            } else {
                $T03 = $r->T03;
            }

            // ---------------------> minggu 3

            if ($r->T04 == 'K') {
                $T04 = '<span class="blink blink-one merah"> x </span>';
            } else {
                $T04 = $r->T04;
            }

            if ($r->T05 == 'K') {
                $T05 = '<span class="blink blink-one merah"> x </span>';
            } else {
                $T05 = $r->T05;
            }

            if ($r->T06 == 'K') {
                $T06 = '<span class="blink blink-one merah"> x </span>';
            } else {
                $T06 = $r->T06;
            }

            if ($r->T07 == 'K') {
                $T07 = '<span class="blink blink-one merah"> x </span>';
            } else {
                $T07 = $r->T07;
            }

            if ($r->T08 == 'K') {
                $T08 = '<span class="blink blink-one merah"> x </span>';
            } else {
                $T08 = $r->T08;
            }

            if ($r->T09 == 'K') {
                $T09 = '<span class="blink blink-one merah"> x </span>';
            } else {
                $T09 = $r->T09;
            }

            if ($r->T10 == 'K') {
                $T10 = '<span class="blink blink-one merah"> x </span>';
            } else {
                $T10 = $r->T10;
            }

            // ---------------------> minggu 4

            if ($r->T11 == 'K') {
                $T11 = '<span class="blink blink-one merah"> x </span>';
            } else {
                $T11 = $r->T11;
            }

            if ($r->T12 == 'K') {
                $T12 = '<span class="blink blink-one merah"> x </span>';
            } else {
                $T12 = $r->T12;
            }

            if ($r->T13 == 'K') {
                $T13 = '<span class="blink blink-one merah"> x </span>';
            } else {
                $T13 = $r->T13;
            }

            if ($r->T14 == 'K') {
                $T14 = '<span class="blink blink-one merah"> x </span>';
            } else {
                $T14 = $r->T14;
            }

            if ($r->T15 == 'K') {
                $T15 = '<span class="blink blink-one merah"> x </span>';
            } else {
                $T15 = $r->T15;
            }

            if ($r->T16 == 'K') {
                $T16 = '<span class="blink blink-one merah"> x </span>';
            } else {
                $T16 = $r->T16;
            }

            if ($r->T17 == 'K') {
                $T17 = '<span class="blink blink-one merah"> x </span>';
            } else {
                $T17 = $r->T17;
            }

            // ---------------------> minggu 5

            if ($r->T18 == 'K') {
                $T18 = '<span class="blink blink-one merah"> x </span>';
            } else {
                $T18 = $r->T18;
            }

            if ($r->T19 == 'K') {
                $T19 = '<span class="blink blink-one merah"> x </span>';
            } else {
                $T19 = $r->T19;
            }

            if ($r->T20 == 'K') {
                $T20 = '<span class="blink blink-one merah"> x </span>';
            } else {
                $T20 = $r->T20;
            }

            // ============

            if ($r->start_date == '' || $r->end_date == '') {
                $jam = '<span class="blink blink-tree merah"> Libur </span>';
            } else {
                $jam = date("d-m-Y", strtotime($r->start_date)) . ' ' . $this->lang->line('dashboard_to') . ' ' . date("d-m-Y", strtotime($r->end_date));
            }

            $cek_payroll_id = $this->Core_model->read_payroll_date_info($r->payroll_id);
            if (!is_null($cek_payroll_id)) {
                $payroll_month = '<b>' . $cek_payroll_id[0]->desc . '</b> <br><i class="fa fa-calendar"></i> <small> Periode Kehadiran : <b>' . date("d-m-Y", strtotime($cek_payroll_id[0]->start_date)) . ' s/d ' . date("d-m-Y", strtotime($cek_payroll_id[0]->end_date)) . '</b></small>';
            } else {
                $payroll_month = '--';
            }

            $cek_jenis = $this->Core_model->read_jenis_info($r->jenis);
            if (!is_null($cek_jenis)) {
                $jp = $cek_jenis[0]->id;
            } else {
                $jp = '--';
            }

            if ($jp == 1) : $jenis_pola = '<span class="badge bg-green">Reguler</span>';
            elseif ($jp == 2) : $jenis_pola = '<span class="badge bg-orange">Shift</span>';
            else : $jenis_pola = '<span class="badge bg-red"> ? </span>';
            endif;

            // get company
            $company = $this->Core_model->read_company_info($r->company_id);
            if (!is_null($company)) {
                $comp_name = $company[0]->name;
            } else {
                $comp_name = '--';
            }

            // =========================================

            if (in_array('0823', $role_resources_ids)) { //di
                $edit = '<span data-toggle="tooltip" data-placement="top" title="' . $this->lang->line('xin_edit') . '"><button type="button" class="btn icon-btn btn-xs btn-default waves-effect waves-light edit-data" data-toggle="modal" data-target=".edit-modal-data-shift" data-office_shift_id="' . $r->office_shift_id . '" ><span class="fa fa-users"></span></button></span>';
            } else {
                $edit = '';
            }

            if (in_array('0824', $role_resources_ids)) { // delete
                $delete = '<span data-toggle="tooltip" data-placement="top" title="' . $this->lang->line('xin_delete') . '">
                    <button type="button" class="btn icon-btn btn-xs btn-danger waves-effect waves-light delete" data-toggle="modal" data-target=".delete-modal" data-record-id="' . $r->office_shift_id . '">
                            <span class="fa fa-trash"></span>
                    </button>
                </span>';
            } else {
                $delete = '';
            }

            $combhr = $edit . $delete;

            if ($r->employee_id == '') {
                $ol = '<span class="blink blink-one merah"> 0 Karyawan </span>';
            } else {
                $ol = '<ol class="nl">';
                foreach (explode(',', $r->employee_id) as $uid) {
                    $user = $this->Core_model->read_user_info($uid);

                    if (!is_null($user)) {

                        $designation = $this->Designation_model->read_designation_information($user[0]->designation_id);
                        if (!is_null($designation)) {
                            $designation_name = $designation[0]->designation_name;
                        } else {
                            $designation_name = '<span class="badge bg-red"> ? </span>';
                        }

                        if ($user[0]->office_shift_id == $r->office_shift_id) {
                            $aktif = '<span class="badge bg-green"><i class="fa fa-check-circle"></i></span>';
                        } else {
                            $aktif = '<span class="badge bg-red"><i class="fa fa-minus-circle"></i></span>';
                        }

                        $ol .= '<li>' . $aktif . ' ' . $user[0]->first_name . ' ' . $user[0]->last_name . ' (' . $designation_name . ') </li>';
                    } else {
                        $ol .= '--';
                    }
                }
                $ol .= '</ol>';
            }

            $dates = array();
            foreach (range(1, 31) as $i) {
                $key = str_pad($i, 2, '0', STR_PAD_LEFT);
                $key = "T{$key}";
                $dates[] = $$key;
            }

            $data[] = array_merge(
                array(
                    $combhr,
                    $r->shift_name . '<br><i class="fa fa-calendar"></i> <small> Bulan Kerja : ' . $payroll_month . ' </small><br><i class="fa fa-users"></i> <small>Pola Kerja ini terdiri atas karyawan : <br>' . $ol . '</small>',
                ),
                $dates
            );
        }

        $output = array(
            "draw" => $draw,
            "recordsTotal"    => $office_shift->num_rows(),
            "recordsFiltered" => $office_shift->num_rows(),
            "data" => $data
        );
        echo json_encode($output);
        exit();
    }

    // Tambah
    public function add_office_shift()
    {
        if ($this->input->post('add_type') == 'office_shift') {

            /* Define return | here result is used to return user data and error for error message */
            $Return = array('result' => '', 'error' => '', 'csrf_hash' => '');
            $Return['csrf_hash'] = $this->security->get_csrf_hash();

            /* Server side PHP input validation */
            if ($this->input->post('company_id') === '') {
                $Return['error'] = $this->lang->line('error_company_field');
            } else if ($this->input->post('payroll_id') === '') {
                $Return['error'] = $this->lang->line('xin_error_bulan_name_field');
            } else if ($this->input->post('start_date') === '') {
                $Return['error'] = $this->lang->line('xin_error_bulan_awal');
            } else if ($this->input->post('end_date') === '') {
                $Return['error'] = $this->lang->line('xin_error_bulan_akhir');
            } else if ($this->input->post('shift_name') === '') {
                $Return['error'] = $this->lang->line('xin_error_shift_name_field');

                //   } else if($this->input->post('employee_id')==='') {
                // $Return['error'] = $this->lang->line('xin_error_employee_id');

            }

            if ($Return['error'] != '') {
                $this->output($Return);
            }

            // if(isset($_POST['employee_id'])) {
            // 	$employee_ids = implode(',',$_POST['employee_id']);
            // 	$employee_id = $employee_ids;
            // } else {
            // 	$employee_id = '';
            // }

            // foreach(explode(',',$employee_id) as $uid) {
            // 	$user = $this->Employees_model->read_employee_information($uid);
            // 	if(!is_null($user)){

            // 		$data_shift = array(
            // 			'office_shift_id'  => $id,
            // 			'office_id'        => 'S'
            // 		);

            // 		$result = $this->Employees_model->update_record($data_shift,$uid);
            // 	}
            // }

            $data = array(

                'shift_name'   => $this->input->post('shift_name'),
                'company_id'   => $this->input->post('company_id'),
                'payroll_id'   => $this->input->post('payroll_id'),
                'jenis'        => $this->input->post('jenis') ?: 2,
                'start_date'   => $this->input->post('start_date'),
                'end_date'     => $this->input->post('end_date'),
                'employee_id'  => '',

                'T21' => $this->input->post('T21'),
                'T22' => $this->input->post('T22'),
                'T23' => $this->input->post('T23'),
                'T24' => $this->input->post('T24'),
                'T25' => $this->input->post('T25'),
                'T26' => $this->input->post('T26'),
                'T27' => $this->input->post('T27'),
                'T28' => $this->input->post('T28'),
                'T29' => $this->input->post('T29'),
                'T30' => $this->input->post('T30'),
                'T31' => $this->input->post('T31'),
                'T01' => $this->input->post('T01'),
                'T02' => $this->input->post('T02'),
                'T03' => $this->input->post('T03'),
                'T04' => $this->input->post('T04'),
                'T05' => $this->input->post('T05'),
                'T06' => $this->input->post('T06'),
                'T07' => $this->input->post('T07'),
                'T08' => $this->input->post('T08'),
                'T09' => $this->input->post('T09'),
                'T10' => $this->input->post('T10'),
                'T11' => $this->input->post('T11'),
                'T12' => $this->input->post('T12'),
                'T13' => $this->input->post('T13'),
                'T14' => $this->input->post('T14'),
                'T15' => $this->input->post('T15'),
                'T16' => $this->input->post('T16'),
                'T17' => $this->input->post('T17'),
                'T18' => $this->input->post('T18'),
                'T19' => $this->input->post('T19'),
                'T20' => $this->input->post('T20'),

                'created_at' => date('Y-m-d H:i:s')
            );

            $result = $this->Timesheet_model->add_office_shift_record($data);

            // echo "<pre>";
            // print_r($this->db->last_query());
            // echo "</pre>";
            // die();

            if ($result == TRUE) {
                $Return['result'] = $this->lang->line('xin_success_shift_added');
            } else {
                $Return['error'] = $this->lang->line('xin_error_msg');
            }
            $this->output($Return);
            exit;
        }
    }

    // Edit
    public function edit_office_shift()
    {
        if ($this->input->post('edit_type') == 'shift') {

            $id = $this->uri->segment(4);

            /* Define return | here result is used to return user data and error for error message */
            $Return = array('result' => '', 'error' => '', 'csrf_hash' => '');
            $Return['csrf_hash'] = $this->security->get_csrf_hash();


            /* Server side PHP input validation */

            /* Server side PHP input validation */
            if ($this->input->post('company_id') === '') {
                $Return['error'] = $this->lang->line('error_company_field');
            } else if ($this->input->post('payroll_id') === '') {
                $Return['error'] = $this->lang->line('xin_error_bulan_name_field');
            } else if ($this->input->post('employee_id') === '') {
                $Return['error'] = $this->lang->line('xin_error_employee_id');
            } else if ($this->input->post('start_date') === '') {
                $Return['error'] = $this->lang->line('xin_error_bulan_awal');
            } else if ($this->input->post('end_date') === '') {
                $Return['error'] = $this->lang->line('xin_error_bulan_akhir');
            } else if ($this->input->post('shift_name') === '') {
                $Return['error'] = $this->lang->line('xin_error_shift_name_field');
            }

            if ($Return['error'] != '') {
                $this->output($Return);
            }


            if (isset($_POST['employee_id'])) {
                $employee_ids = implode(',', $_POST['employee_id']);
                $employee_id = $employee_ids;
            } else {
                $employee_id = '';
            }

            foreach (explode(',', $employee_id) as $uid) {
                $user = $this->Employees_model->read_employee_information($uid);
                if (!is_null($user)) {

                    $data_shift = array(
                        'office_shift_id'  => $id,
                        'office_id'        => 'S'
                    );

                    $result = $this->Employees_model->update_record($data_shift, $uid);
                }
            }

            $data = array(

                'shift_name'  => $this->input->post('shift_name'),
                'company_id'  => $this->input->post('company_id'),
                'employee_id' => $employee_id,
                'payroll_id'  => $this->input->post('payroll_id'),
                'jenis'       => 2,
                'start_date'  => $this->input->post('start_date'),
                'end_date'    => $this->input->post('end_date'),

                'T21' => $this->input->post('T21'),
                'T22' => $this->input->post('T22'),
                'T23' => $this->input->post('T23'),
                'T24' => $this->input->post('T24'),
                'T25' => $this->input->post('T25'),
                'T26' => $this->input->post('T26'),
                'T27' => $this->input->post('T27'),
                'T28' => $this->input->post('T28'),
                'T29' => $this->input->post('T29'),
                'T30' => $this->input->post('T30'),
                'T31' => $this->input->post('T31'),
                'T01' => $this->input->post('T01'),
                'T02' => $this->input->post('T02'),
                'T03' => $this->input->post('T03'),
                'T04' => $this->input->post('T04'),
                'T05' => $this->input->post('T05'),
                'T06' => $this->input->post('T06'),
                'T07' => $this->input->post('T07'),
                'T08' => $this->input->post('T08'),
                'T09' => $this->input->post('T09'),
                'T10' => $this->input->post('T10'),
                'T11' => $this->input->post('T11'),
                'T12' => $this->input->post('T12'),
                'T13' => $this->input->post('T13'),
                'T14' => $this->input->post('T14'),
                'T15' => $this->input->post('T15'),
                'T16' => $this->input->post('T16'),
                'T17' => $this->input->post('T17'),
                'T18' => $this->input->post('T18'),
                'T19' => $this->input->post('T19'),
                'T20' => $this->input->post('T20')


            );

            $result = $this->Timesheet_model->update_shift_record($data, $id);

            if ($result == TRUE) {
                $Return['result'] = $this->lang->line('xin_success_shift_updated');
            } else {
                $Return['error'] = $this->lang->line('xin_error_msg');
            }
            $this->output($Return);
            exit;
        }
    }

    // Read
    public function read_shift_record()
    {
        $data['title']   = $this->Core_model->site_title();
        $office_shift_id = $this->input->get('office_shift_id');
        $result          = $this->Timesheet_model->read_office_shift_information($office_shift_id);

        $data = array(
            'office_shift_id'   => $result[0]->office_shift_id,
            'company_id'        => $result[0]->company_id,
            'jenis'             => $result[0]->jenis,
            'employee_id'       => $result[0]->employee_id,
            'payroll_id'        => $result[0]->payroll_id,
            'start_date'        => $result[0]->start_date,
            'end_date'          => $result[0]->end_date,
            'shift_name'        => $result[0]->shift_name,
            'T21'               => $result[0]->T21,
            'T22'               => $result[0]->T22,
            'T23'               => $result[0]->T23,
            'T24'               => $result[0]->T24,
            'T25'               => $result[0]->T25,
            'T26'               => $result[0]->T26,
            'T27'               => $result[0]->T27,
            'T28'               => $result[0]->T28,
            'T29'               => $result[0]->T29,
            'T30'               => $result[0]->T30,
            'T31'               => $result[0]->T31,
            'T01'               => $result[0]->T01,
            'T02'               => $result[0]->T02,
            'T03'               => $result[0]->T03,
            'T04'               => $result[0]->T04,
            'T05'               => $result[0]->T05,
            'T06'               => $result[0]->T06,
            'T07'               => $result[0]->T07,
            'T08'               => $result[0]->T08,
            'T09'               => $result[0]->T09,
            'T10'               => $result[0]->T10,
            'T11'               => $result[0]->T11,
            'T12'               => $result[0]->T12,
            'T13'               => $result[0]->T13,
            'T14'               => $result[0]->T14,
            'T15'               => $result[0]->T15,
            'T16'               => $result[0]->T16,
            'T17'               => $result[0]->T17,
            'T18'               => $result[0]->T18,
            'T19'               => $result[0]->T19,
            'T20'               => $result[0]->T20,
            'all_jam_shift'     => $this->Core_model->all_jam_shift(),
            'get_all_companies' => $this->Company_model->get_company(),
            'all_bulan_gaji'    => $this->Core_model->all_bulan_gaji(),
            'all_employees'     => $this->Core_model->all_employees()
        );
        $session = $this->session->userdata('username');
        if (!empty($session)) {
            $this->load->view('admin/pengaturan/dialog_office_shift', $data);
        } else {
            redirect('admin/');
        }
    }

    // =============================================================================
    // 0830. ATUR JAM SHIFT
    // =============================================================================
    public function office_shift_jam()
    {
        $session = $this->session->userdata('username');
        if (empty($session)) {
            redirect('admin/');
        }
        $data['title']       = 'Atur Jam Shift | ' . $this->Core_model->site_title();
        $data['icon']        = '<i class="fa fa-history"></i>';
        $data['breadcrumbs'] = 'Atur Jam Shift';
        $data['path_url']    = 'pengaturan_office_shift_jam';

        $company_info = $this->Core_model->read_company_setting_info(1);

        $data['all_companies'] = $this->Company_model->get_company();

        $role_resources_ids = $this->Core_model->user_role_resource();
        if (in_array('0831', $role_resources_ids)) {
            if (!empty($session)) {
                $data['subview'] = $this->load->view("admin/pengaturan/office_shift_jam", $data, TRUE);
                $this->load->view('admin/layout/layout_main', $data); //page load
            } else {
                redirect('admin/');
            }
        } else {
            redirect('admin/dashboard');
        }
    }
    // Tabel
    public function shift_jam_list()
    {

        $data['title'] = $this->Core_model->site_title();
        $session = $this->session->userdata('username');
        if (!empty($session)) {
            $this->load->view("admin/pengaturan/office_shift_jam", $data);
        } else {
            redirect('admin/');
        }
        // Datatables Variables
        $draw   = intval($this->input->get("draw"));
        $start  = intval($this->input->get("start"));
        $length = intval($this->input->get("length"));

        $role_resources_ids = $this->Core_model->user_role_resource();

        $constant = $this->Core_model->get_shift_jam();

        $data = array();

        foreach ($constant->result() as $r) {

            if (in_array('0833', $role_resources_ids)) { //edit
                $edit = '<span data-toggle="tooltip" data-placement="top" title="' . $this->lang->line('xin_edit') . '">
                                    <button type="button" class="btn icon-btn btn-xs btn-default waves-effect waves-light" data-toggle="modal" data-target=".edit_setting_datail" data-field_id="' . $r->id . '" data-field_type="shift_jam">
                                        <span class="fa fa-pencil"></span>
                                    </button>
                                </span>';
            } else {
                $edit = '';
            }

            if (in_array('0834', $role_resources_ids)) { // delete
                $delete = '<span data-toggle="tooltip" data-placement="top" title="' . $this->lang->line('xin_delete') . '">
                            <button type="button" class="btn icon-btn btn-xs btn-danger waves-effect waves-light delete" data-toggle="modal" data-target=".delete-modal" data-record-id="' . $r->id . '" data-token_type="shift_jam">
                                <span class="fa fa-trash"></span>
                            </button>
                        </span>';
            } else {
                $delete = '';
            }

            $combhr = $edit . $delete;

            $data[] = array(
                $combhr,
                $r->kode,
                $r->start_date,
                $r->end_date,
                $r->keterangan,
            );
        }

        $output = array(
            "draw" => $draw,
            "recordsTotal" => $constant->num_rows(),
            "recordsFiltered" => $constant->num_rows(),
            "data" => $data
        );

        echo json_encode($output);
        exit();
    }
    // Edit
    public function shift_jam_info()
    {

        if ($this->input->post('type') == 'shift_jam_info') {

            /* Define return | here result is used to return user data and error for error message */
            $Return = array('result' => '', 'error' => '', 'csrf_hash' => '');
            $Return['csrf_hash'] = $this->security->get_csrf_hash();

            /* Server side PHP input validation */
            if ($this->input->post('kode') === '') {
                $Return['error'] = 'Kode Jam Shift';
            } else if ($this->input->post('start_date') === '') {
                $Return['error'] = 'Jam Shift Mulai';
            } else if ($this->input->post('end_date') === '') {
                $Return['error'] = 'Jam Shift Sampai';
            } else if ($this->input->post('keterangan') === '') {
                $Return['error'] = 'Keterangan Jam Shift';
            }

            if ($Return['error'] != '') {
                $this->output->set_output(json_encode($Return));
                // $this->output->set_output(json_encode($Return));
            }

            $data = array(
                'kode'        => $this->input->post('kode'),
                'start_date'  => $this->input->post('start_date'),
                'end_date'    => $this->input->post('end_date'),
                'keterangan'  => $this->input->post('keterangan'),
                'created_at'  => date('d-m-Y h:i:s')
            );

            $result = $this->Core_model->add_shift_jam($data);

            if ($result == TRUE) {
                $Return['result'] = $this->lang->line('xin_success_shift_jam_added');
            } else {
                $Return['error'] = $this->lang->line('xin_error_msg');
            }

            $this->output->set_output(json_encode($Return));

            // $this->output->set_output(json_encode($Return));
            // exit;
        }
    }
    // Tambah
    public function update_shift_jam()
    {

        if ($this->input->post('type') == 'edit_record') {

            $id = $this->uri->segment(4);

            /* Define return | here result is used to return user data and error for error message */
            $Return = array('result' => '', 'error' => '', 'csrf_hash' => '');
            $Return['csrf_hash'] = $this->security->get_csrf_hash();

            if ($this->input->post('kode') === '') {
                $Return['error'] = 'Kode Jam Shift';
            } else if ($this->input->post('start_date') === '') {
                $Return['error'] = 'Jam Shift Mulai';
            } else if ($this->input->post('end_date') === '') {
                $Return['error'] = 'Jam Shift Sampai';
            } else if ($this->input->post('keterangan') === '') {
                $Return['error'] = 'Keterangan Jam Shift';
            }

            if ($Return['error'] != '') {
                $this->output->set_output(json_encode($Return));
            }

            $data = array(
                'kode'       => $this->input->post('kode'),
                'start_date' => $this->input->post('start_date'),
                'end_date'   => $this->input->post('end_date'),
                'keterangan' => $this->input->post('keterangan')

            );

            $result = $this->Core_model->update_shift_jam_record($data, $id);

            if ($result == TRUE) {
                $Return['result'] = $this->lang->line('xin_success_shift_jam_updated');
            } else {
                $Return['error'] = $this->lang->line('xin_error_msg');
            }
            $this->output->set_output(json_encode($Return));
            // exit;
        }
    }
    // Hapus
    public function delete_shift_jam()
    {

        if ($this->input->post('type') == 'delete_record') {
            /* Define return | here result is used to return user data and error for error message */
            $Return = array('result' => '', 'error' => '', 'csrf_hash' => '');
            $id = $this->uri->segment(4);
            $Return['csrf_hash'] = $this->security->get_csrf_hash();
            $result = $this->Core_model->delete_shift_jam_record($id);
            if (isset($id)) {
                $Return['result'] = $this->lang->line('xin_success_shift_jam_deleted');
            } else {
                $Return['error'] = $this->lang->line('xin_error_msg');
            }
            $this->output->set_output(json_encode($Return));
        }
    }
    // Raed
    public function read_shift_jam()
    {
        $data['title'] = $this->Core_model->site_title();
        $session = $this->session->userdata('username');
        if (!empty($session)) {
            $this->load->view('admin/pengaturan/dialog_office_shift_jam', $data);
        } else {
            redirect('admin/');
        }
    }

    // =============================================================================
    // 0840. ATUR HARI LIBUR
    // =============================================================================

    // holidays > timesheet
    public function holidays()
    {

        $session = $this->session->userdata('username');
        if (empty($session)) {
            redirect('admin/');
        }

        $data['title']             = 'Atur Hari Libur | ' . $this->Core_model->site_title();
        $data['icon']              = '<i class="fa fa-child"></i>';
        $data['breadcrumbs']       = 'Atur Hari Libur';
        $data['path_url']          = 'pengaturan_holidays';

        $data['get_all_companies'] = $this->Company_model->get_company();

        $role_resources_ids = $this->Core_model->user_role_resource();
        if (in_array('0841', $role_resources_ids)) {
            if (!empty($session)) {
                $data['subview'] = $this->load->view("admin/pengaturan/holidays", $data, TRUE);
                $this->load->view('admin/layout/layout_main', $data); //page load
            } else {
                redirect('admin/');
            }
        } else {
            redirect('admin/dashboard');
        }
    }

    // holidays_list > timesheet
    public function holidays_list()
    {

        $data['title'] = $this->Core_model->site_title();
        $session = $this->session->userdata('username');
        if (!empty($session)) {
            $this->load->view("admin/pengaturan/holidays", $data);
        } else {
            redirect('admin/');
        }
        // Datatables Variables
        $draw   = intval($this->input->get("draw"));
        $start  = intval($this->input->get("start"));
        $length = intval($this->input->get("length"));

        $role_resources_ids = $this->Core_model->user_role_resource();

        $user_info = $this->Core_model->read_user_info($session['user_id']);

        if ($this->input->get("ihr") == 'true') {

            if ($this->input->get("company_id") == 0 && $this->input->get("status") == 'all') {

                $holidays = $this->Timesheet_model->get_holidays();
            } else if ($this->input->get("company_id") != 0 && $this->input->get("status") == 'all') {

                $holidays = $this->Timesheet_model->filter_company_holidays($this->input->get("company_id"));
            } else if ($this->input->get("company_id") != 0 && $this->input->get("status") != 'all') {

                $holidays = $this->Timesheet_model->filter_company_publish_holidays($this->input->get("company_id"), $this->input->get("status"));
            } else if ($this->input->get("company_id") == 0 && $this->input->get("status") != 'all') {

                $holidays = $this->Timesheet_model->filter_notcompany_publish_holidays($this->input->get("status"));
            }
        } else {

            if ($user_info[0]->user_role_id == 1) {

                $holidays = $this->Timesheet_model->get_holidays();
            } else {

                $holidays = $this->Timesheet_model->get_company_holidays($user_info[0]->company_id);
            }
        }

        $data = array();

        foreach ($holidays->result() as $r) {

            /* get publish/unpublish label*/
            if ($r->is_publish == 1) : $publish = '<span class="badge bg-green">' . $this->lang->line('xin_published') . '</span>';
            else : $publish = '<span class="badge bg-orange">' . $this->lang->line('xin_unpublished') . '</span>';
            endif;
            // get start date and end date
            $sdate = $this->Core_model->set_date_format($r->start_date);
            $edate = $this->Core_model->set_date_format($r->end_date);
            // get company

            /* get Employee info*/

            $ol = '<ol class="nl">';
            foreach (explode(',', $r->company_ids) as $uid) {
                $company = $this->Core_model->read_company_info($uid);
                if (!is_null($company)) {
                    $ol .= '<li>' . $company[0]->name . '</li>';
                } else {
                    $ol .= '--';
                }
            }
            $ol .= '</ol>';

            if (in_array('0843', $role_resources_ids)) { //edit
                $edit = '<span data-toggle="tooltip" data-placement="top" title="' . $this->lang->line('xin_edit') . '"><button type="button" class="btn icon-btn btn-xs btn-default waves-effect waves-light edit-data" data-toggle="modal" data-target=".edit-modal-data" data-holiday_id="' . $r->holiday_id . '"><span class="fa fa-pencil"></span></button></span>';
            } else {
                $edit = '';
            }
            if (in_array('0844', $role_resources_ids)) { // delete
                $delete = '<span data-toggle="tooltip" data-placement="top" title="' . $this->lang->line('xin_delete') . '"><button type="button" class="btn icon-btn btn-xs btn-danger waves-effect waves-light delete" data-toggle="modal" data-target=".delete-modal" data-record-id="' . $r->holiday_id . '"><span class="fa fa-trash"></span></button></span>';
            } else {
                $delete = '';
            }
            if (in_array('0845', $role_resources_ids)) { //view
                $view = '<span data-toggle="tooltip" data-placement="top" title="' . $this->lang->line('xin_view') . '"><button type="button" class="btn icon-btn btn-xs btn-default waves-effect waves-light" data-toggle="modal" data-target=".view-modal-data" data-holiday_id="' . $r->holiday_id . '"><span class="fa fa-eye"></span></button></span>';
            } else {
                $view = '';
            }
            $combhr = $edit . $view . $delete;
            $ievent_name = $r->event_name;
            $holiday_date = $sdate . ' s/d ' . $edate;
            $desc_name = $r->description;
            $data[] = array(
                $combhr,
                $holiday_date,
                $ol,
                $ievent_name,
                $desc_name,
                $publish
            );
        }

        $output = array(
            "draw" => $draw,
            "recordsTotal" => $holidays->num_rows(),
            "recordsFiltered" => $holidays->num_rows(),
            "data" => $data
        );
        echo json_encode($output);
        exit();
    }

    public function read_holiday_record()
    {
        $data['title'] = $this->Core_model->site_title();
        $holiday_id = $this->input->get('holiday_id');
        $result = $this->Timesheet_model->read_holiday_information($holiday_id);

        $data = array(
            'holiday_id' => $result[0]->holiday_id,
            'company_id' => $result[0]->company_ids,
            'event_name' => $result[0]->event_name,
            'start_date' => $result[0]->start_date,
            'end_date' => $result[0]->end_date,
            'is_publish'        => $result[0]->is_publish,
            'description'       => $result[0]->description,
            'get_all_companies' => $this->Company_model->get_company()
        );
        $session = $this->session->userdata('username');
        if (!empty($session)) {
            $this->load->view('admin/pengaturan/dialog_holiday', $data);
        } else {
            redirect('admin/');
        }
    }

    // Validate and add info in database
    public function add_holiday()
    {
        if ($this->input->post('add_type') == 'holiday') {
            /* Define return | here result is used to return user data and error for error message */
            $Return = array('result' => '', 'error' => '', 'csrf_hash' => '');
            $Return['csrf_hash'] = $this->security->get_csrf_hash();
            $start_date = $this->input->post('start_date');
            $end_date = $this->input->post('end_date');
            $description = $this->input->post('description');
            $st_date = strtotime($start_date);
            $ed_date = strtotime($end_date);
            $qt_description = htmlspecialchars(addslashes($description), ENT_QUOTES);

            /* Server side PHP input validation */
            if ($this->input->post('company_id') === '') {
                $Return['error'] = $this->lang->line('error_company_field');
            } else if ($this->input->post('event_name') === '') {
                $Return['error'] = $this->lang->line('xin_error_event_name');
            } else if ($this->input->post('start_date') === '') {
                $Return['error'] = $this->lang->line('xin_error_start_date');
            } else if ($this->input->post('end_date') === '') {
                $Return['error'] = $this->lang->line('xin_error_end_date');
            } else if ($st_date > $ed_date) {
                $Return['error'] = $this->lang->line('xin_error_start_end_date');
            }

            if ($Return['error'] != '') {
                $this->output($Return);
            }

            $company_ids = implode(',', $_POST['company_id']);
            $company_id = $company_ids;

            if (isset($_POST['company_id'])) {
                $company_ids = implode(',', $_POST['company_id']);
                $company_id = $company_ids;
            } else {
                $company_id = '';
            }

            $data = array(
                'event_name' => $this->input->post('event_name'),
                'company_ids' => $company_id,
                // 'company_id' => $this->input->post('company_id'),
                'description' => $qt_description,
                'start_date' => $this->input->post('start_date'),
                'end_date' => $this->input->post('end_date'),
                'is_publish' => $this->input->post('is_publish'),
                'created_at' => date('Y-m-d')
            );
            $result = $this->Timesheet_model->add_holiday_record($data);

            if ($result == TRUE) {
                $row = $this->db->select("*")->limit(1)->order_by('holiday_id', "DESC")->get("xin_holidays")->row();
                $Return['result'] = $this->lang->line('xin_holiday_added');
                $Return['re_last_id'] = $row->holiday_id;
            } else {
                $Return['error'] = $this->lang->line('xin_error_msg');
            }
            $this->output($Return);
            exit;
        }
    }

    // Validate and add info in database
    public function edit_holiday()
    {
        if ($this->input->post('edit_type') == 'holiday') {

            $id = $this->uri->segment(4);
            /* Define return | here result is used to return user data and error for error message */
            $Return = array('result' => '', 'error' => '', 'csrf_hash' => '');
            $Return['csrf_hash'] = $this->security->get_csrf_hash();
            $start_date = $this->input->post('start_date');
            $end_date = $this->input->post('end_date');
            $description = $this->input->post('description');
            $st_date = strtotime($start_date);
            $ed_date = strtotime($end_date);
            $qt_description = htmlspecialchars(addslashes($description), ENT_QUOTES);

            /* Server side PHP input validation */
            if ($this->input->post('company_id') === '') {
                $Return['error'] = $this->lang->line('error_company_field');
            } else if ($this->input->post('event_name') === '') {
                $Return['error'] = $this->lang->line('xin_error_event_name');
            } else if ($this->input->post('start_date') === '') {
                $Return['error'] = $this->lang->line('xin_error_start_date');
            } else if ($this->input->post('end_date') === '') {
                $Return['error'] = $this->lang->line('xin_error_end_date');
            } else if ($st_date > $ed_date) {
                $Return['error'] = $this->lang->line('xin_error_start_end_date');
            }

            if ($Return['error'] != '') {
                $this->output($Return);
            }

            $company_ids = implode(',', $_POST['company_id']);
            $company_id = $company_ids;

            if (isset($_POST['company_id'])) {
                $company_ids = implode(',', $_POST['company_id']);
                $company_id = $company_ids;
            } else {
                $company_id = '';
            }

            $data = array(
                'event_name'  => $this->input->post('event_name'),
                'company_ids' => $company_id,
                'description' => $qt_description,
                'start_date'  => $this->input->post('start_date'),
                'end_date'    => $this->input->post('end_date'),
                'is_publish'  => $this->input->post('is_publish')
            );

            $result = $this->Timesheet_model->update_holiday_record($data, $id);

            if ($result == TRUE) {
                $Return['result'] = $this->lang->line('xin_success_holiday_updated');
            } else {
                $Return['error'] = $this->lang->line('xin_error_msg');
            }
            $this->output($Return);
            exit;
        }
    }

    // delete holiday record
    public function delete_holiday()
    {
        if ($this->input->post('type') == 'delete') {
            // Define return | here result is used to return user data and error for error message
            $Return = array('result' => '', 'error' => '', 'csrf_hash' => '');
            $id = $this->uri->segment(4);
            $Return['csrf_hash'] = $this->security->get_csrf_hash();
            $result = $this->Timesheet_model->delete_holiday_record($id);
            if (isset($id)) {
                $Return['result'] = $this->lang->line('xin_success_holiday_deleted');
            } else {
                $Return['error'] = $this->lang->line('xin_error_msg');
            }
            $this->output($Return);
        }
    }

    // =============================================================================
    // 0850. ATUR BULAN KERJA
    // =============================================================================

    // holidays > timesheet
    public function works()
    {

        $session = $this->session->userdata('username');
        if (empty($session)) {
            redirect('admin/');
        }

        $data['title']             = 'Atur Bulan Kerja | ' . $this->Core_model->site_title();
        $data['icon']              = '<i class="fa fa-calendar"></i>';
        $data['breadcrumbs']       = 'Atur Bulan Kerja';
        $data['path_url']          = 'pengaturan_works';

        $data['all_bulan']    = $this->Core_model->all_bulan();
        $data['all_tahun']    = $this->Core_model->all_tahun();


        $role_resources_ids = $this->Core_model->user_role_resource();
        if (in_array('0851', $role_resources_ids)) {
            if (!empty($session)) {
                $data['subview'] = $this->load->view("admin/pengaturan/works", $data, TRUE);
                $this->load->view('admin/layout/layout_main', $data); //page load
            } else {
                redirect('admin/');
            }
        } else {
            redirect('admin/dashboard');
        }
    }

    // works_list > timesheet
    public function works_list()
    {

        $data['title'] = $this->Core_model->site_title();
        $session = $this->session->userdata('username');
        if (!empty($session)) {
            $this->load->view("admin/pengaturan/works", $data);
        } else {
            redirect('admin/');
        }
        // Datatables Variables
        $draw   = intval($this->input->get("draw"));
        $start  = intval($this->input->get("start"));
        $length = intval($this->input->get("length"));

        $role_resources_ids = $this->Core_model->user_role_resource();

        $user_info = $this->Core_model->read_user_info($session['user_id']);

        $works = $this->Timesheet_model->get_works();

        $data = array();

        foreach ($works->result() as $r) {


            // get start date and end date
            $sdate = $this->Core_model->set_date_format($r->start_date);
            $edate = $this->Core_model->set_date_format($r->end_date);
            // get company


            if (in_array('0853', $role_resources_ids)) { //edit
                $edit = '<span data-toggle="tooltip" data-placement="top" title="' . $this->lang->line('xin_edit') . '">
                                    <button type="button" class="btn icon-btn btn-xs btn-default waves-effect waves-light edit-data" data-toggle="modal" data-target=".edit-modal-data" data-payroll_id="' . $r->payroll_id . '">
                                             <span class="fa fa-gavel"></span> Aktifkan
                                     </button>
                             </span>';
            } else {
                $edit = '';
            }
            if (in_array('0854', $role_resources_ids)) { // delete
                $delete = '<span data-toggle="tooltip" data-placement="top" title="' . $this->lang->line('xin_delete') . '">
                                    <button type="button" class="btn icon-btn btn-xs btn-danger waves-effect waves-light delete" data-toggle="modal" data-target=".delete-modal" data-record-id="' . $r->payroll_id . '">
                                            <span class="fa fa-trash"></span>
                                    </button>
                                </span>';
            } else {
                $delete = '';
            }
            if (in_array('0855', $role_resources_ids)) { //view
                $view = '<span data-toggle="tooltip" data-placement="top" title="' . $this->lang->line('xin_view') . '">
                                    <button type="button" class="btn icon-btn btn-xs btn-default waves-effect waves-light" data-toggle="modal" data-target=".view-modal-data" data-payroll_id="' . $r->payroll_id . '">
                                            <span class="fa fa-eye"></span>
                                    </button>
                            </span>';
            } else {
                $view = '';
            }

            $combhr      = $edit . $view . $delete;

            $bulan_nama   = $r->desc;

            $month_payroll = $r->month_payroll;

            $cek_bulan = $this->Core_model->read_bulan_info($r->bulan);
            if (!is_null($cek_bulan)) {
                $bulan_date = $cek_bulan[0]->bulan_nama;
            } else {
                $bulan_date = '--';
            }

            $tahun_date   = $r->tahun;

            $work_date   = $sdate . ' s/d ' . $edate;

            if ($r->description == '') {
                $description   = '-';
            } else {
                $description   = $r->description;
            }

            // Publish
            if ($r->is_publish == 1) : $publish = '<span class="badge bg-green">' . $this->lang->line('xin_published') . '</span>';
            else : $publish = '<span class="badge bg-orange">' . $this->lang->line('xin_unpublished') . '</span>';
            endif;

            // Penggajian
            if ($r->is_payroll == 1) : $payroll = '<span class="badge bg-green"> Release </span>';
            else : $payroll = '<span class="badge bg-red"> Draft </span>';
            endif;

            // Laporan
            if ($r->is_recap == 1) : $recap = '<span class="badge bg-green">' . $this->lang->line('xin_published') . '</span>';
            else : $recap = '<span class="badge bg-orange">' . $this->lang->line('xin_unpublished') . '</span>';
            endif;

            $data[] = array(
                $combhr,
                $work_date,
                $bulan_date,
                $tahun_date,
                $month_payroll,
                $bulan_nama,
                $description,
                $publish,
                $payroll,
                $recap
            );
        }

        $output = array(
            "draw" => $draw,
            "recordsTotal" => $works->num_rows(),
            "recordsFiltered" => $works->num_rows(),
            "data" => $data
        );
        echo json_encode($output);
        exit();
    }

    public function read_work_record()
    {
        $data['title'] = $this->Core_model->site_title();

        $payroll_id = $this->input->get('payroll_id');

        $result = $this->Timesheet_model->read_work_information($payroll_id);


        if ($result[0]->description == '') {
            $description   = '-';
        } else {
            $description   = $result[0]->description;
        }


        $data = array(
            'payroll_id'    => $result[0]->payroll_id,

            'bulan'         => $result[0]->bulan,
            'tahun'         => $result[0]->tahun,

            'start_date'    => $result[0]->start_date,
            'end_date'      => $result[0]->end_date,

            'is_publish'    => $result[0]->is_publish,
            'is_payroll'    => $result[0]->is_payroll,
            'is_recap'      => $result[0]->is_recap,

            'desc'          => $result[0]->desc,
            'description'   => $description
        );
        $session = $this->session->userdata('username');
        if (!empty($session)) {
            $this->load->view('admin/pengaturan/dialog_work', $data);
        } else {
            redirect('admin/');
        }
    }

    public function view_work_record()
    {
        $data['title'] = $this->Core_model->site_title();

        $payroll_id = $this->input->get('payroll_id');

        $result = $this->Timesheet_model->read_work_information($payroll_id);

        $cek_bulan = $this->Core_model->read_bulan_info($result[0]->bulan);
        if (!is_null($cek_bulan)) {
            $bulan_nama = $cek_bulan[0]->bulan_nama;
        } else {
            $bulan_nama = '--';
        }

        if ($result[0]->description == '') {
            $description   = '-';
        } else {
            $description   = $result[0]->description;
        }


        $data = array(
            'payroll_id'    => $result[0]->payroll_id,

            'bulan'         => $bulan_nama,
            'tahun'         => $result[0]->tahun,

            'start_date'    => $result[0]->start_date,
            'end_date'      => $result[0]->end_date,

            'is_publish'    => $result[0]->is_publish,
            'is_payroll'    => $result[0]->is_payroll,
            'is_recap'      => $result[0]->is_recap,

            'desc'          => $result[0]->desc,
            'description'   => $description
        );
        $session = $this->session->userdata('username');
        if (!empty($session)) {
            $this->load->view('admin/pengaturan/dialog_work', $data);
        } else {
            redirect('admin/');
        }
    }

    // Validate and add info in database
    public function add_work()
    {
        if ($this->input->post('add_type') == 'work') {

            /* Define return | here result is used to return user data and error for error message */

            $Return              = array('result' => '', 'error' => '', 'csrf_hash' => '');
            $Return['csrf_hash'] = $this->security->get_csrf_hash();

            $start_date = $this->input->post('start_date');
            $end_date   = $this->input->post('end_date');

            $st_date = strtotime($start_date);
            $ed_date = strtotime($end_date);

            $description    = $this->input->post('description');
            $qt_description = htmlspecialchars(addslashes($description), ENT_QUOTES);

            /* Server side PHP input validation */
            if ($this->input->post('start_date') === '') {
                $Return['error'] = $this->lang->line('xin_error_start_date');
            } else if ($this->input->post('end_date') === '') {
                $Return['error'] = $this->lang->line('xin_error_end_date');
            } else if ($st_date > $ed_date) {
                $Return['error'] = $this->lang->line('xin_error_start_end_date');
            } else if ($this->input->post('bulan') === '') {
                $Return['error'] = 'Bulan wajib diisi';
            } else if ($this->input->post('tahun') === '') {
                $Return['error'] = 'Tahun wajib diisi';
            } else if ($this->input->post('is_publish') === '') {
                $Return['error'] = 'Status Aktif wajib diisi';
            } else if ($this->input->post('is_payroll') === '') {
                $Return['error'] = 'Status Gaji wajib diisi';
            } else if ($this->input->post('is_recap') === '') {
                $Return['error'] = 'Status Laporan wajib diisi';
            } else if ($this->input->post('desc') === '') {
                $Return['error'] = 'Nama Bulan wajib diisi';
            }

            if ($Return['error'] != '') {
                $this->output($Return);
            }


            $data = array(

                'start_date'     => $this->input->post('start_date'),
                'end_date'       => $this->input->post('end_date'),

                'bulan'          => $this->input->post('bulan'),
                'tahun'          => $this->input->post('tahun'),

                'month_payroll'  => $this->input->post('tahun') . '-' . $this->input->post('bulan'),

                'desc'           => $this->input->post('desc'),
                'description'    => $qt_description,

                'is_publish'     => $this->input->post('is_publish'),
                'is_payroll'     => $this->input->post('is_payroll'),
                'is_recap'       => $this->input->post('is_recap'),

                'created_at'     => date('Y-m-d')
            );
            $result = $this->Timesheet_model->add_work_record($data);

            if ($result == TRUE) {

                $Return['result'] = 'Bulan Kerja Berhasil Ditambahkan';
            } else {
                $Return['error'] = $this->lang->line('xin_error_msg');
            }
            $this->output($Return);
            exit;
        }
    }

    // Validate and add info in database
    public function edit_work()
    {
        if ($this->input->post('edit_type') == 'work') {

            $id = $this->uri->segment(4);

            /* Define return | here result is used to return user data and error for error message */
            $Return              = array('result' => '', 'error' => '', 'csrf_hash' => '');
            $Return['csrf_hash'] = $this->security->get_csrf_hash();

            $start_date     = $this->input->post('start_date');
            $end_date       = $this->input->post('end_date');

            $st_date        = strtotime($start_date);
            $ed_date        = strtotime($end_date);

            $description    = $this->input->post('description');
            $qt_description = htmlspecialchars(addslashes($description), ENT_QUOTES);


            /* Server side PHP input validation */
            if ($this->input->post('start_date') === '') {
                $Return['error'] = $this->lang->line('xin_error_start_date');
            } else if ($this->input->post('end_date') === '') {
                $Return['error'] = $this->lang->line('xin_error_end_date');
            } else if ($st_date > $ed_date) {
                $Return['error'] = $this->lang->line('xin_error_start_end_date');
            } else if ($this->input->post('bulan') === '') {
                $Return['error'] = 'Bulan wajib diisi';
            } else if ($this->input->post('tahun') === '') {
                $Return['error'] = 'Tahun wajib diisi';
            } else if ($this->input->post('is_publish') === '') {
                $Return['error'] = 'Status Aktif wajib diisi';
            } else if ($this->input->post('is_payroll') === '') {
                $Return['error'] = 'Status Gaji wajib diisi';
            } else if ($this->input->post('is_recap') === '') {
                $Return['error'] = 'Status Laporan wajib diisi';
            } else if ($this->input->post('desc') === '') {
                $Return['error'] = 'Nama Bulan wajib diisi';
            }

            if ($Return['error'] != '') {
                $this->output($Return);
            }


            $data = array(
                'start_date'     => $this->input->post('start_date'),
                'end_date'       => $this->input->post('end_date'),

                'bulan'          => $this->input->post('bulan'),
                'tahun'          => $this->input->post('tahun'),

                'month_payroll'  => $this->input->post('tahun') . '-' . $this->input->post('bulan'),

                'desc'           => $this->input->post('desc'),
                'description'    => $qt_description,

                'is_publish'     => $this->input->post('is_publish'),
                'is_payroll'     => $this->input->post('is_payroll'),
                'is_recap'       => $this->input->post('is_recap')
            );

            $result = $this->Timesheet_model->update_work_record($data, $id);

            if ($result == TRUE) {
                $Return['result'] = 'Bulan Kerja Berhasil Diperbarui';
            } else {
                $Return['error'] = $this->lang->line('xin_error_msg');
            }
            $this->output($Return);
            exit;
        }
    }

    // delete work record
    public function delete_work()
    {
        if ($this->input->post('type') == 'delete') {
            // Define return | here result is used to return user data and error for error message
            $Return = array('result' => '', 'error' => '', 'csrf_hash' => '');
            $id = $this->uri->segment(4);
            $Return['csrf_hash'] = $this->security->get_csrf_hash();
            $result = $this->Timesheet_model->delete_work_record($id);
            if (isset($id)) {
                $Return['result'] = 'Bulan Kerja Berhasil Dihapus';
            } else {
                $Return['error'] = $this->lang->line('xin_error_msg');
            }
            $this->output($Return);
        }
    }


    // =============================================================================
    // 0870. ATUR PERIODE KERJA
    // =============================================================================

    // holidays > timesheet
    public function periode()
    {

        $session = $this->session->userdata('username');
        if (empty($session)) {
            redirect('admin/');
        }

        $data['title']             = 'Atur Periode Kerja | ' . $this->Core_model->site_title();
        $data['icon']              = '<i class="fa fa-calendar"></i>';
        $data['breadcrumbs']       = 'Atur Periode Kerja';
        $data['path_url']          = 'pengaturan_periode';

        $data['all_bulan']    = $this->Core_model->all_bulan();
        $data['all_tahun']    = $this->Core_model->all_tahun();


        $role_resources_ids = $this->Core_model->user_role_resource();
        if (in_array('0871', $role_resources_ids)) {
            if (!empty($session)) {
                $data['subview'] = $this->load->view("admin/pengaturan/periode", $data, TRUE);
                $this->load->view('admin/layout/layout_main', $data); //page load
            } else {
                redirect('admin/');
            }
        } else {
            redirect('admin/dashboard');
        }
    }

    // works_list > timesheet
    public function periode_list()
    {

        $data['title'] = $this->Core_model->site_title();
        $session = $this->session->userdata('username');
        if (!empty($session)) {
            $this->load->view("admin/pengaturan/periode", $data);
        } else {
            redirect('admin/');
        }
        // Datatables Variables
        $draw   = intval($this->input->get("draw"));
        $start  = intval($this->input->get("start"));
        $length = intval($this->input->get("length"));

        $role_resources_ids = $this->Core_model->user_role_resource();

        $user_info = $this->Core_model->read_user_info($session['user_id']);

        $periode = $this->Timesheet_model->get_bulan_periode();

        $data = array();
        $no = 1;
        foreach ($periode->result() as $r) {

            $cek_bulan = $this->Core_model->read_bulan_info($r->bulan);
            if (!is_null($cek_bulan)) {
                $bulan_date = $cek_bulan[0]->bulan_nama;
            } else {
                $bulan_date = '--';
            }

            // get company

            $daftar_jenis = '';

            $sql_jenis = " SELECT *
                            FROM
                                 view_periode_jenis
                            WHERE
                                1 = 1
                            AND bulan  = '" . $r->bulan . "'
                            AND tahun  = '" . $r->tahun . "'
                            AND jenis != '1'
                            ORDER BY jenis ASC";

            // echo "<pre>";
            // print_r( $sql_jenis );
            // echo "</pre>";
            // die;



            $query_jenis = $this->db->query($sql_jenis);

            if ($query_jenis->num_rows() > 0) {
                $daftar_jenis = '<table class="datatables-demo table table-striped table-bordered" id="xin_table">
                                <thead>
                                  <tr>
                                    <th class="text-center" > Jenis </th>
                                    <th class="text-center" > Periode </th>

                                  </tr>
                                </thead>
                                <tbody>';
                $mo = 1;
                foreach ($query_jenis->result() as $row_jenis) :

                    $daftar_Periode = '';

                    $sql_Periode = " SELECT *
                                                FROM
                                                     xin_payroll_date_periode
                                                WHERE
                                                    1 = 1
                                                AND bulan  = '" . $r->bulan . "'
                                                AND tahun  = '" . $r->tahun . "'
                                                AND jenis  = '" . $row_jenis->jenis . "'
                                                ORDER BY start_date ASC";

                    // echo "<pre>";
                    // print_r( $sql_Periode );
                    // echo "</pre>";
                    // die;

                    $query_Periode = $this->db->query($sql_Periode);

                    if ($query_Periode->num_rows() > 0) {
                        $daftar_Periode = '<table class="datatables-demo table table-striped table-bordered" id="xin_table">
                                                    <thead>
                                                      <tr>
                                                        <th class="text-center" width="50px">No.</th>
                                                        <th class="text-center" > Start Date </th>
                                                        <th class="text-center" > End Date </th>
                                                        <th class="text-center" > Durasi </th>
                                                        <th class="text-center" > Keterangan </th>
                                                        <th class="text-center" > Status Aktif </th>
                                                        <th class="text-center" > Status Gaji</th>
                                                        <th class="text-center" > Status Laporan </th>
                                                        <th class="text-center" > Aksi </th>
                                                      </tr>
                                                    </thead>
                                                    <tbody>';
                        $mo = 1;
                        foreach ($query_Periode->result() as $row_Periode) :

                            if (in_array('0873', $role_resources_ids)) { //edit
                                $edit = '<span data-toggle="tooltip" data-placement="top" title="' . $this->lang->line('xin_edit') . '">
                                                                            <button type="button" class="btn icon-btn btn-xs btn-default waves-effect waves-light edit-data" data-toggle="modal" data-target=".edit-modal-data" data-payroll_id="' . $row_Periode->payroll_id . '">
                                                                                     <span class="fa fa-pencil"></span> Edit
                                                                             </button>
                                                                     </span>';
                            } else {
                                $edit = '';
                            }
                            if (in_array('0874', $role_resources_ids)) { // delete
                                $delete = '<span data-toggle="tooltip" data-placement="top" title="' . $this->lang->line('xin_delete') . '">
                                                                            <button type="button" class="btn icon-btn btn-xs btn-danger waves-effect waves-light delete" data-toggle="modal" data-target=".delete-modal" data-record-id="' . $row_Periode->payroll_id . '">
                                                                                    <span class="fa fa-trash"></span>
                                                                            </button>
                                                                        </span>';
                            } else {
                                $delete = '';
                            }
                            if (in_array('0875', $role_resources_ids)) { //view
                                $view = '<span data-toggle="tooltip" data-placement="top" title="' . $this->lang->line('xin_view') . '">
                                                                            <button type="button" class="btn icon-btn btn-xs btn-default waves-effect waves-light" data-toggle="modal" data-target=".view-modal-data" data-payroll_id="' . $row_Periode->payroll_id . '">
                                                                                    <span class="fa fa-eye"></span>
                                                                            </button>
                                                                    </span>';
                            } else {
                                $view = '';
                            }

                            $combhr      = $edit . $view . $delete;

                            // get start date and end date
                            $sdate = $this->Core_model->set_date_format($row_Periode->start_date);
                            $edate = $this->Core_model->set_date_format($row_Periode->end_date);

                            $d_start_date    = new DateTime($row_Periode->start_date);
                            $d_end_date      = new DateTime($row_Periode->end_date);
                            $interval_date   = $d_start_date->diff($d_end_date)->d + 1;


                            // Publish
                            if ($row_Periode->is_publish == 1) : $publish = '<span class="badge bg-green">' . $this->lang->line('xin_published') . '</span>';
                            else : $publish = '<span class="badge bg-orange">' . $this->lang->line('xin_unpublished') . '</span>';
                            endif;

                            // Penggajian
                            if ($row_Periode->is_payroll == 1) : $payroll = '<span class="badge bg-green"> Release </span>';
                            else : $payroll = '<span class="badge bg-red"> Draft </span>';
                            endif;

                            // Laporan
                            if ($row_Periode->is_recap == 1) : $recap = '<span class="badge bg-green">' . $this->lang->line('xin_published') . '</span>';
                            else : $recap = '<span class="badge bg-orange">' . $this->lang->line('xin_unpublished') . '</span>';
                            endif;


                            $daftar_Periode = $daftar_Periode . '
                                                                <tr >
                                                                    <td width="2%" align="center">' . $mo . '.</td>

                                                                    <td width="10%" align="center">
                                                                     ' . $sdate . '
                                                                    </td>

                                                                    <td width="10%" align="center">
                                                                     ' . $edate . '
                                                                    </td>

                                                                    <td width="10%" align="center">
                                                                     ' . $interval_date . ' hari
                                                                    </td>

                                                                    <td align="left">
                                                                     ' . $row_Periode->description . '
                                                                    </td>

                                                                    <td width="10%" align="center">
                                                                     ' . $publish . '
                                                                    </td>

                                                                    <td width="10%" align="center">
                                                                     ' . $payroll . '
                                                                    </td>

                                                                    <td width="10%" align="center">
                                                                     ' . $recap . '
                                                                    </td>

                                                                    <td width="15%" align="left">
                                                                      ' . $combhr . '
                                                                    </td>

                                                                </tr>';
                            $mo++;
                        endforeach;

                        $daftar_Periode = $daftar_Periode . '
                                                    </tbody>
                                                    </table>';
                    } else {
                        $daftar_Periode = '<div class="warning-msg" style="padding:5px;">
                                                        <i class ="fa fa-question-circle"></i> Tidak Ada Periode
                                                     </div>';
                    }

                    $daftar_jenis = $daftar_jenis . '
                                            <tr >
                                                <td width="10%" align="center">
                                                 ' . $row_jenis->jenis_gaji_keterangan . '
                                                </td>

                                                <td  align="center">' . $daftar_Periode . '</td>


                                            </tr>';
                    $mo++;
                endforeach;

                $daftar_jenis = $daftar_jenis . '
                                </tbody>
                                </table>';
            } else {
                $daftar_jenis = '<div class="warning-msg" style="padding:5px;">
                                    <i class ="fa fa-question-circle"></i> Tidak Ada Jenis
                                 </div>';
            }



            $data[] = array(
                $no,
                $bulan_date,
                $r->tahun,
                $daftar_jenis

            );
            $no++;
        }

        $output = array(
            "draw" => $draw,
            "recordsTotal" => $periode->num_rows(),
            "recordsFiltered" => $periode->num_rows(),
            "data" => $data
        );
        echo json_encode($output);
        exit();
    }

    public function read_periode_record()
    {
        $data['title'] = $this->Core_model->site_title();

        $payroll_id = $this->input->get('payroll_id');

        $result = $this->Timesheet_model->read_periode_information($payroll_id);


        if ($result[0]->description == '') {
            $description   = '-';
        } else {
            $description   = $result[0]->description;
        }


        $data = array(
            'payroll_id'    => $result[0]->payroll_id,

            'bulan'         => $result[0]->bulan,
            'tahun'         => $result[0]->tahun,

            'start_date'    => $result[0]->start_date,
            'end_date'      => $result[0]->end_date,

            'jenis'         => $result[0]->jenis,

            'is_publish'    => $result[0]->is_publish,
            'is_payroll'    => $result[0]->is_payroll,
            'is_recap'      => $result[0]->is_recap,

            'desc'          => $result[0]->desc,
            'description'   => $description
        );
        $session = $this->session->userdata('username');
        if (!empty($session)) {
            $this->load->view('admin/pengaturan/dialog_periode', $data);
        } else {
            redirect('admin/');
        }
    }

    public function view_periode_record()
    {
        $data['title'] = $this->Core_model->site_title();

        $payroll_id = $this->input->get('payroll_id');

        $result = $this->Timesheet_model->read_periode_information($payroll_id);

        $cek_bulan = $this->Core_model->read_bulan_info($result[0]->bulan);
        if (!is_null($cek_bulan)) {
            $bulan_nama = $cek_bulan[0]->bulan_nama;
        } else {
            $bulan_nama = '--';
        }

        if ($result[0]->description == '') {
            $description   = '-';
        } else {
            $description   = $result[0]->description;
        }


        $data = array(
            'payroll_id'    => $result[0]->payroll_id,

            'bulan'         => $bulan_nama,
            'tahun'         => $result[0]->tahun,

            'start_date'    => $result[0]->start_date,
            'end_date'      => $result[0]->end_date,

            'jenis'         => $result[0]->jenis,

            'is_publish'    => $result[0]->is_publish,
            'is_payroll'    => $result[0]->is_payroll,
            'is_recap'      => $result[0]->is_recap,

            'desc'          => $result[0]->desc,
            'description'   => $description
        );
        $session = $this->session->userdata('username');
        if (!empty($session)) {
            $this->load->view('admin/pengaturan/dialog_periode', $data);
        } else {
            redirect('admin/');
        }
    }

    // Validate and add info in database
    public function add_periode()
    {
        if ($this->input->post('add_type') == 'periode') {

            /* Define return | here result is used to return user data and error for error message */

            $Return              = array('result' => '', 'error' => '', 'csrf_hash' => '');
            $Return['csrf_hash'] = $this->security->get_csrf_hash();

            $start_date = $this->input->post('start_date');
            $end_date   = $this->input->post('end_date');

            $st_date = strtotime($start_date);
            $ed_date = strtotime($end_date);

            $description    = $this->input->post('description');
            $qt_description = htmlspecialchars(addslashes($description), ENT_QUOTES);

            /* Server side PHP input validation */
            if ($this->input->post('start_date') === '') {
                $Return['error'] = $this->lang->line('xin_error_start_date');
            } else if ($this->input->post('end_date') === '') {
                $Return['error'] = $this->lang->line('xin_error_end_date');
            } else if ($st_date > $ed_date) {
                $Return['error'] = $this->lang->line('xin_error_start_end_date');
            } else if ($this->input->post('bulan') === '') {
                $Return['error'] = 'Bulan wajib diisi';
            } else if ($this->input->post('tahun') === '') {
                $Return['error'] = 'Tahun wajib diisi';
            } else if ($this->input->post('jenis') === '') {
                $Return['error'] = 'Jenis wajib diisi';
            } else if ($this->input->post('is_publish') === '') {
                $Return['error'] = 'Status Aktif wajib diisi';
            } else if ($this->input->post('is_payroll') === '') {
                $Return['error'] = 'Status Gaji wajib diisi';
            } else if ($this->input->post('is_recap') === '') {
                $Return['error'] = 'Status Laporan wajib diisi';
            } else if ($this->input->post('desc') === '') {
                $Return['error'] = 'Nama Bulan wajib diisi';
            }

            if ($Return['error'] != '') {
                $this->output($Return);
            }


            $data = array(

                'start_date'     => $this->input->post('start_date'),
                'end_date'       => $this->input->post('end_date'),

                'bulan'          => $this->input->post('bulan'),
                'tahun'          => $this->input->post('tahun'),

                'jenis'          => $this->input->post('jenis'),

                'month_payroll'  => $this->input->post('tahun') . '-' . $this->input->post('bulan'),

                'desc'           => $this->input->post('desc'),
                'description'    => $qt_description,

                'is_publish'     => $this->input->post('is_publish'),
                'is_payroll'     => $this->input->post('is_payroll'),
                'is_recap'       => $this->input->post('is_recap'),

                'created_at'     => date('Y-m-d')
            );
            $result = $this->Timesheet_model->add_periode_record($data);

            if ($result == TRUE) {

                $Return['result'] = 'Periode Kerja Berhasil Ditambahkan';
            } else {
                $Return['error'] = $this->lang->line('xin_error_msg');
            }
            $this->output($Return);
            exit;
        }
    }

    // Validate and add info in database
    public function edit_periode()
    {
        if ($this->input->post('edit_type') == 'periode') {

            $id = $this->uri->segment(4);

            /* Define return | here result is used to return user data and error for error message */
            $Return              = array('result' => '', 'error' => '', 'csrf_hash' => '');
            $Return['csrf_hash'] = $this->security->get_csrf_hash();

            $start_date     = $this->input->post('start_date');
            $end_date       = $this->input->post('end_date');

            $st_date        = strtotime($start_date);
            $ed_date        = strtotime($end_date);

            $description    = $this->input->post('description');
            $qt_description = htmlspecialchars(addslashes($description), ENT_QUOTES);


            /* Server side PHP input validation */
            if ($this->input->post('start_date') === '') {
                $Return['error'] = $this->lang->line('xin_error_start_date');
            } else if ($this->input->post('end_date') === '') {
                $Return['error'] = $this->lang->line('xin_error_end_date');
            } else if ($st_date > $ed_date) {
                $Return['error'] = $this->lang->line('xin_error_start_end_date');
            } else if ($this->input->post('bulan') === '') {
                $Return['error'] = 'Bulan wajib diisi';
            } else if ($this->input->post('tahun') === '') {
                $Return['error'] = 'Tahun wajib diisi';
            } else if ($this->input->post('jenis') === '') {
                $Return['error'] = 'Jenis wajib diisi';
            } else if ($this->input->post('is_publish') === '') {
                $Return['error'] = 'Status Aktif wajib diisi';
            } else if ($this->input->post('is_payroll') === '') {
                $Return['error'] = 'Status Gaji wajib diisi';
            } else if ($this->input->post('is_recap') === '') {
                $Return['error'] = 'Status Laporan wajib diisi';
            } else if ($this->input->post('desc') === '') {
                $Return['error'] = 'Nama Bulan wajib diisi';
            }

            if ($Return['error'] != '') {
                $this->output($Return);
            }


            $data = array(
                'start_date'     => $this->input->post('start_date'),
                'end_date'       => $this->input->post('end_date'),

                'bulan'          => $this->input->post('bulan'),
                'tahun'          => $this->input->post('tahun'),

                'jenis'          => $this->input->post('jenis'),

                'month_payroll'  => $this->input->post('tahun') . '-' . $this->input->post('bulan'),

                'desc'           => $this->input->post('desc'),
                'description'    => $qt_description,

                'is_publish'     => $this->input->post('is_publish'),
                'is_payroll'     => $this->input->post('is_payroll'),
                'is_recap'       => $this->input->post('is_recap')
            );

            $result = $this->Timesheet_model->update_periode_record($data, $id);

            if ($result == TRUE) {
                $Return['result'] = 'Periode Kerja Berhasil Diperbarui';
            } else {
                $Return['error'] = $this->lang->line('xin_error_msg');
            }
            $this->output($Return);
            exit;
        }
    }

    // delete work record
    public function delete_periode()
    {
        if ($this->input->post('type') == 'delete') {
            // Define return | here result is used to return user data and error for error message
            $Return = array('result' => '', 'error' => '', 'csrf_hash' => '');
            $id = $this->uri->segment(4);
            $Return['csrf_hash'] = $this->security->get_csrf_hash();
            $result = $this->Timesheet_model->delete_periode_record($id);
            if (isset($id)) {
                $Return['result'] = 'Periode Kerja Berhasil Dihapus';
            } else {
                $Return['error'] = $this->lang->line('xin_error_msg');
            }
            $this->output($Return);
        }
    }

    // =============================================================================
    // 0860. ATUR SKALA UPAH
    // =============================================================================

    // holidays > timesheet
    public function skala_upah()
    {

        $session = $this->session->userdata('username');
        if (empty($session)) {
            redirect('admin/');
        }

        $data['title']             = 'Atur Skala Upah | ' . $this->Core_model->site_title();
        $data['icon']              = '<i class="fa fa-calendar"></i>';
        $data['breadcrumbs']       = 'Atur Skala Upah';
        $data['path_url']          = 'pengaturan_skala_upah';

        $data['get_all_companies'] = $this->Company_model->get_company();
        $data['get_all_workstation'] = $this->Company_model->get_workstation_skala_upah();


        $role_resources_ids = $this->Core_model->user_role_resource();
        if (in_array('0861', $role_resources_ids)) {
            if (!empty($session)) {
                $data['subview'] = $this->load->view("admin/pengaturan/skala_upah_list", $data, TRUE);
                $this->load->view('admin/layout/layout_main', $data); //page load
            } else {
                redirect('admin/');
            }
        } else {
            redirect('admin/dashboard');
        }
    }

    // works_list > timesheet
    public function skala_upah_list()
    {

        $session = $this->session->userdata('username');
        $data['title'] = $this->Core_model->site_title();
        if (!empty($session)) {
            $this->load->view("admin/pengaturan/skala_upah_list", $data);
        } else {
            redirect('admin/');
        }
        $system = $this->Core_model->read_setting_info(1);
        // Datatables Variables
        $draw = intval($this->input->get("draw"));
        $start = intval($this->input->get("start"));
        $length = intval($this->input->get("length"));


        $role_resources_ids = $this->Core_model->user_role_resource();
        $user_info = $this->Core_model->read_user_info($session['user_id']);

        $company = $this->Company_model->get_companies();

        $data = array();
        $no = 1;

        foreach ($company->result() as $r) {

            $icname = $r->name;

            $workstation = '';

            $sql_workstation = " SELECT *
                            FROM
                                 xin_workstation
                            WHERE
                                1 = 1
                            AND company_id  = '" . $r->company_id . "'
                            AND workstation_name !='-'
                            ORDER BY workstation_name ASC";

            // echo "<pre>";
            // print_r( $sql_workstation );
            // echo "</pre>";
            // die;

            $query_workstation = $this->db->query($sql_workstation);

            if ($query_workstation->num_rows() > 0) {




                $daftar_workstation = '<table class="datatables-demo table table-striped table-bordered" id="xin_table">
                                <thead>
                                  <tr>
                                    <th class="text-center" width="50px">No.</th>
                                    <th class="text-center" width="150px"> Workstation </th>
                                    <th class="text-center" > Daftar Tugas</th>
                                  </tr>
                                </thead>
                                <tbody>';
                $mo = 1;
                foreach ($query_workstation->result() as $row_workstation) :


                    $jum_workstation = $this->Employees_model->get_total_employees_workstation($r->company_id, $row_workstation->workstation_id);
                    if (!is_null($jum_workstation)) {
                        $jumlah_workstation = $jum_workstation[0]->jumlah;
                    } else {
                        $jumlah_workstation = '0';
                    }

                    $iworkstation_name = strtoupper($row_workstation->workstation_name);


                    $daftar_workstation = $daftar_workstation . '
                                            <tr">
                                                <td width="2%" align="center">' . $mo . '.</td>

                                                <td align="left">
                                                 ' . $iworkstation_name . ' <br> Karyawan : ' . $jumlah_workstation . '
                                                </td>

                                                <td  align="left">';

                    $tugas = '';

                    $sql_tugas = " SELECT *  FROM xin_workstation_skala_upah
                                                                    WHERE
                                                                        1 = 1
                                                                    AND workstation_id  = '" . $row_workstation->workstation_id . "'
                                                                    ORDER BY skala_upah_name ASC";

                    // echo "<pre>";
                    // print_r( $sql_tugas );
                    // echo "</pre>";
                    // die;

                    $query_tugas = $this->db->query($sql_tugas);

                    if ($query_tugas->num_rows() > 0) {


                        $tugas = '<table class="datatables-demo table table-striped table-bordered" id="xin_table">
                                                                    <thead>
                                                                      <tr>
                                                                        <th class="text-center" width="50px">No.</th>
                                                                        <th class="text-center" > NmBrg (dari XAI) </th>
                                                                        <th class="text-center" > Tugas Pekerjaan </th>
                                                                        <th class="text-center" colspan="2" width="150px"> Ongkos Kerja </th>
                                                                        <th class="text-center" width="100px"> Aksi</th>
                                                                      </tr>

                                                                    </thead>
                                                                    <tbody>';
                        $yo = 1;
                        foreach ($query_tugas->result() as $row_tugas) :


                            if (in_array('0863', $role_resources_ids)) {
                                // edit
                                $edit = '<span data-toggle="tooltip" data-placement="top" title="' . $this->lang->line('xin_edit') . '">
                                                                                        <button type="button" class="btn icon-btn btn-xs btn-default waves-effect waves-light"  data-toggle="modal" data-target="#edit-modal-data"  data-skala_upah_id="' . $row_tugas->skala_upah_id . '">
                                                                                            <span class="fa fa-pencil"></span> Edit
                                                                                        </button>
                                                                                    </span>';
                            } else {
                                $edit = '';
                            }

                            if (in_array('0864', $role_resources_ids)) {
                                // delete
                                $delete = '<span data-toggle="tooltip" data-placement="top" title="' . $this->lang->line('xin_delete') . '">
                                                                                            <button type="button" class="btn icon-btn btn-xs btn-danger waves-effect waves-light delete" data-toggle="modal" data-target=".delete-modal" data-record-id="' . $row_tugas->skala_upah_id . '">
                                                                                                <span class="fa fa-trash"></span>
                                                                                            </button>
                                                                                        </span>';
                            } else {
                                $delete = '';
                            }


                            $tugas_name = strtoupper($row_tugas->skala_upah_name);

                            if ($row_tugas->skala_upah_kode == '') {
                                $kode = '?';
                            } else {
                                $kode = $row_tugas->skala_upah_kode;
                            }

                            $tugas = $tugas . '
                                                                                <tr">
                                                                                    <td width="2%" align="center">' . $yo . '.</td>

                                                                                    <td width="20%"  align="center">
                                                                                     ' . $kode . '
                                                                                    </td>

                                                                                    <td align="left">
                                                                                     ' . $tugas_name . '
                                                                                    </td>

                                                                                    <td width="10%"  align="right">
                                                                                     ' . number_format($row_tugas->skala_upah_ongkos, 0, ',', '.') . '
                                                                                    </td>

                                                                                    <td width="5%"  align="center">
                                                                                     / Kg
                                                                                    </td>

                                                                                    <td width="10%" align="center">
                                                                                     ' . $edit . ' ' . $delete . '
                                                                                    </td>
                                                                                </tr>';
                            $yo++;
                        endforeach;

                        $tugas = $tugas . '
                                                                    </tbody>
                                                                    </table>';
                    } else {

                        $tugas = '<div class="warning-msg" style="padding:5px;">
                                                                        <i class ="fa fa-question-circle"></i> Tidak Ada Tugas
                                                                     </div>';
                    }

                    $daftar_workstation = $daftar_workstation . ' ' . $tugas . ' </td>

                                            </tr>';
                    $mo++;
                endforeach;

                $daftar_workstation = $daftar_workstation . '
                                </tbody>
                                </table>';
            } else {

                $daftar_workstation = '<div class="warning-msg" style="padding:5px;">
                                    <i class ="fa fa-question-circle"></i> Tidak Ada Workstation
                                 </div>';
            }

            $data[] = array(
                $no,
                $icname,
                $daftar_workstation

            );
            $no++;
        }

        $output = array(
            "draw" => $draw,
            "recordsTotal" => $company->num_rows(),
            "recordsFiltered" => $company->num_rows(),
            "data" => $data
        );
        echo json_encode($output);
        exit();
    }

    // ======================================================================================================
    // PROSES
    // ======================================================================================================

    public function read_skala_upah()
    {
        $session = $this->session->userdata('username');
        if (empty($session)) {
            redirect('admin/');
        }
        $data['title'] = $this->Core_model->site_title();
        $id            = $this->input->get('skala_upah_id');
        $result        = $this->Skala_upah_model->read_skala_upah_information($id);
        $data = array(
            'skala_upah_id'       => $result[0]->skala_upah_id,
            'company_id'          => $result[0]->company_id,
            'workstation_id'      => $result[0]->workstation_id,
            'skala_upah_kode'     => $result[0]->skala_upah_kode,
            'skala_upah_name'     => $result[0]->skala_upah_name,
            'skala_upah_ongkos'   => $result[0]->skala_upah_ongkos,
            'get_all_companies'   => $this->Company_model->get_company(),
            'get_all_workstation' => $this->Company_model->get_workstation_skala_upah()
        );
        if (!empty($session)) {
            $this->load->view('admin/pengaturan/dialog_skala_upah', $data);
        } else {
            redirect('admin/');
        }
    }

    // Validate and add info in database
    public function add_skala_upah()
    {
        if ($this->input->post('add_type') == 'skala_upah') {
            // Check validation for user input
            $this->form_validation->set_rules('workstation_id', 'Workstation', 'trim|required|xss_clean');
            $this->form_validation->set_rules('skala_upah_name', 'Tugas Pekerjaan', 'trim|required|xss_clean');

            /* Define return | here result is used to return user data and error for error message */
            $Return = array('result' => '', 'error' => '', 'csrf_hash' => '');
            $Return['csrf_hash'] = $this->security->get_csrf_hash();
            $system = $this->Core_model->read_setting_info(1);

            /* Server side PHP input validation */

            if ($this->input->post('company_id') === '') {
                $Return['error'] = $this->lang->line('error_company_field');
            } else if ($this->input->post('workstation_id') === '') {
                $Return['error'] = $this->lang->line('error_workstation_field');
            } else if ($this->input->post('skala_upah_name') === '') {
                $Return['error'] = $this->lang->line('error_skala_upah_name_field');
            } else if ($this->input->post('skala_upah_kode') === '') {
                $Return['error'] = $this->lang->line('error_skala_upah_kode_field');
            } else if ($this->input->post('skala_upah_ongkos') === '') {
                $Return['error'] = $this->lang->line('error_skala_upah_ongkos_field');
            }

            if ($Return['error'] != '') {
                $this->output($Return);
            }
            $data = array(

                'company_id'        => $this->input->post('company_id'),
                'workstation_id'    => $this->input->post('workstation_id'),
                'skala_upah_kode'   => $this->input->post('skala_upah_kode'),
                'skala_upah_name'   => $this->input->post('skala_upah_name'),
                'skala_upah_ongkos' => $this->input->post('skala_upah_ongkos'),
                'added_by'          => $this->input->post('user_id'),
                'created_at'        => date('d-m-Y'),
            );

            $result = $this->Skala_upah_model->add($data);

            if ($result == TRUE) {
                $Return['result'] =  "Skala Upah Pekerjaan " . $this->input->post('skala_upah_name') . " Berhasil Ditambahkan";
            } else {
                $Return['error'] = $this->lang->line('xin_error_msg');
            }

            $this->output($Return);
            exit;
        }
    }

    // Validate and update info in database
    public function update_skala_upah()
    {
        if ($this->input->post('edit_type') == 'skala_upah') {

            $id = $this->uri->segment(4);

            // Check validation for user input
            $this->form_validation->set_rules('workstation_id', 'Workstation', 'trim|required|xss_clean');
            $this->form_validation->set_rules('skala_upah_name', 'Tugas Pekerjaan', 'trim|required|xss_clean');

            /* Define return | here result is used to return user data and error for error message */
            $Return = array('result' => '', 'error' => '', 'csrf_hash' => '');
            $Return['csrf_hash'] = $this->security->get_csrf_hash();
            $system = $this->Core_model->read_setting_info(1);
            /* Server side PHP input validation */
            if ($this->input->post('company_id') === '') {
                $Return['error'] = $this->lang->line('error_company_field');
            } else if ($this->input->post('workstation_id') === '') {
                $Return['error'] = $this->lang->line('error_workstation_field');
            } else if ($this->input->post('skala_upah_name') === '') {
                $Return['error'] = $this->lang->line('error_skala_upah_name_field');
            } else if ($this->input->post('skala_upah_kode') === '') {
                $Return['error'] = $this->lang->line('error_skala_upah_kode_field');
            } else if ($this->input->post('skala_upah_ongkos') === '') {
                $Return['error'] = $this->lang->line('error_skala_upah_ongkos_field');
            }

            if ($Return['error'] != '') {
                $this->output($Return);
            }
            $data = array(
                'company_id'        => $this->input->post('company_id'),
                'workstation_id'    => $this->input->post('workstation_id'),
                'skala_upah_name'   => $this->input->post('skala_upah_name'),
                'skala_upah_kode'   => $this->input->post('skala_upah_kode'),
                'skala_upah_ongkos' => $this->input->post('skala_upah_ongkos')
            );
            $result = $this->Skala_upah_model->update_record($data, $id);

            if ($result == TRUE) {
                $Return['result'] = "Skala Upah Pekerjaan " . $this->input->post('skala_upah_name') . " Berhasil Diperbaharui";
            } else {
                $Return['error'] = $this->lang->line('xin_error_msg');
            }
            $this->output($Return);
            exit;
        }
    }

    public function delete_skala_upah()
    {
        if ($this->input->post('is_ajax') == 2) {
            $session = $this->session->userdata('username');
            if (empty($session)) {
                redirect('admin/');
            }
            /* Define return | here result is used to return user data and error for error message */
            $Return = array('result' => '', 'error' => '', 'csrf_hash' => '');
            $id = $this->uri->segment(4);
            $Return['csrf_hash'] = $this->security->get_csrf_hash();

            $result = $this->Skala_upah_model->delete_record($id);
            if (isset($id)) {
                $Return['result'] = "Skala Upah Berhasil Dihapus";
            } else {
                $Return['error'] = $this->lang->line('xin_error_msg');
            }

            $this->output($Return);
        }
    }


    // =============================================================================
    // TAMPILKAN
    // =============================================================================

    public function get_workstations()
    {

        $data['title'] = $this->Core_model->site_title();
        $id = $this->uri->segment(4);

        $data = array(
            'company_id' => $id
        );
        $session = $this->session->userdata('username');
        if (!empty($session)) {
            $this->load->view("admin/pengaturan/get_workstations", $data);
        } else {
            redirect('admin/');
        }
        // Datatables Variables
        $draw = intval($this->input->get("draw"));
        $start = intval($this->input->get("start"));
        $length = intval($this->input->get("length"));
    }


    public function get_model_workstations()
    {

        $data['title'] = $this->Core_model->site_title();
        $id = $this->uri->segment(4);

        $data = array(
            'company_id' => $id
        );
        $session = $this->session->userdata('username');
        if (!empty($session)) {
            $this->load->view("admin/pengaturan/get_model_workstations", $data);
        } else {
            redirect('admin/');
        }
        // Datatables Variables
        $draw = intval($this->input->get("draw"));
        $start = intval($this->input->get("start"));
        $length = intval($this->input->get("length"));
    }


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
            $this->load->view("admin/pengaturan/get_employees", $data);
        } else {
            redirect('admin/');
        }
        // Datatables Variables
        $draw = intval($this->input->get("draw"));
        $start = intval($this->input->get("start"));
        $length = intval($this->input->get("length"));
    }

    // get company > employees
    public function get_employees_office()
    {

        $data['title'] = $this->Core_model->site_title();
        $id = $this->uri->segment(4);

        $data = array(
            'company_id' => $id
        );
        $session = $this->session->userdata('username');
        if (!empty($session)) {
            $this->load->view("admin/pengaturan/get_employees_office", $data);
        } else {
            redirect('admin/');
        }
        // Datatables Variables
        $draw = intval($this->input->get("draw"));
        $start = intval($this->input->get("start"));
        $length = intval($this->input->get("length"));
    }


    // get company > projects
    public function get_company_project()
    {

        $data['title'] = $this->Core_model->site_title();
        $id = $this->uri->segment(4);

        $data = array(
            'company_id' => $id
        );
        $session = $this->session->userdata('username');
        if (!empty($session)) {
            $this->load->view("admin/pengaturan/tasks/get_company_project", $data);
        } else {
            redirect('admin/');
        }
        // Datatables Variables
        $draw = intval($this->input->get("draw"));
        $start = intval($this->input->get("start"));
        $length = intval($this->input->get("length"));
    }

    // get company > employees
    public function get_company_employees()
    {

        $data['title'] = $this->Core_model->site_title();
        $id = $this->uri->segment(4);

        $data = array(
            'company_id' => $id
        );
        $session = $this->session->userdata('username');
        if (!empty($session)) {
            $this->load->view("admin/pengaturan/tasks/get_employees", $data);
        } else {
            redirect('admin/');
        }
        // Datatables Variables
        $draw = intval($this->input->get("draw"));
        $start = intval($this->input->get("start"));
        $length = intval($this->input->get("length"));
    }

    // daily attendance list > timesheet
    public function dtwise_attendance_list()
    {

        $data['title'] = $this->Core_model->site_title();
        $session = $this->session->userdata('username');
        if (!empty($session)) {
            $this->load->view("admin/pengaturan/attendance_list", $data);
        } else {
            redirect('admin/');
        }
        // Datatables Variables
        $draw = intval($this->input->get("draw"));
        $start = intval($this->input->get("start"));
        $length = intval($this->input->get("length"));


        $employee = $this->Core_model->read_user_attendance_info();

        $data = array();

        foreach ($employee->result() as $r) {
            $data[] = array('', '', '', '', '', '', '', '', '', '', '');
        }

        $output = array(
            "draw" => $draw,
            "recordsTotal" => $employee->num_rows(),
            "recordsFiltered" => $employee->num_rows(),
            "data" => $data
        );
        echo json_encode($output);
        exit();
    }

    // date wise attendance list > timesheet
    public function date_wise_list()
    {

        $data['title'] = $this->Core_model->site_title();
        $session = $this->session->userdata('username');
        $user_info = $this->Core_model->read_user_info($session['user_id']);
        if (!empty($session)) {
            $this->load->view("admin/pengaturan/date_wise", $data);
        } else {
            redirect('admin/');
        }
        // Datatables Variables
        $draw = intval($this->input->get("draw"));
        $start = intval($this->input->get("start"));
        $length = intval($this->input->get("length"));

        $role_resources_ids = $this->Core_model->user_role_resource();

        $employee_id = $this->input->get("user_id");

        $system = $this->Core_model->read_setting_info(1);
        $employee = $this->Core_model->read_user_info($employee_id);

        $start_date = new DateTime($this->input->get("start_date"));
        $end_date = new DateTime($this->input->get("end_date"));
        $end_date = $end_date->modify('+1 day');

        $interval_re = new DateInterval('P1D');
        $date_range = new DatePeriod($start_date, $interval_re, $end_date);
        $attendance_arr = array();

        $data = array();
        foreach ($date_range as $date) {
            $attendance_date =  $date->format("Y-m-d");

            $get_day = strtotime($attendance_date);
            $day = date('l', $get_day);

            // office shift
            $office_shift = $this->Timesheet_model->read_office_shift_information($employee[0]->office_shift_id);

            // get clock in/clock out of each employee
            if ($day == 'Monday') {
                if ($monday_in_time == '') {
                    $in_time = '00:00:00';
                    $out_time = '00:00:00';
                } else {
                    $in_time = $office_shift[0]->monday_in_time;
                    $out_time = $office_shift[0]->monday_out_time;
                }
            } else if ($day == 'Tuesday') {
                if ($office_shift[0]->tuesday_in_time == '') {
                    $in_time = '00:00:00';
                    $out_time = '00:00:00';
                } else {
                    $in_time = $office_shift[0]->tuesday_in_time;
                    $out_time = $office_shift[0]->tuesday_out_time;
                }
            } else if ($day == 'Wednesday') {
                if ($office_shift[0]->wednesday_in_time == '') {
                    $in_time = '00:00:00';
                    $out_time = '00:00:00';
                } else {
                    $in_time = $office_shift[0]->wednesday_in_time;
                    $out_time = $office_shift[0]->wednesday_out_time;
                }
            } else if ($day == 'Thursday') {
                if ($office_shift[0]->thursday_in_time == '') {
                    $in_time = '00:00:00';
                    $out_time = '00:00:00';
                } else {
                    $in_time = $office_shift[0]->thursday_in_time;
                    $out_time = $office_shift[0]->thursday_out_time;
                }
            } else if ($day == 'Friday') {
                if ($office_shift[0]->friday_in_time == '') {
                    $in_time = '00:00:00';
                    $out_time = '00:00:00';
                } else {
                    $in_time = $office_shift[0]->friday_in_time;
                    $out_time = $office_shift[0]->friday_out_time;
                }
            } else if ($day == 'Saturday') {
                if ($office_shift[0]->saturday_in_time == '') {
                    $in_time = '00:00:00';
                    $out_time = '00:00:00';
                } else {
                    $in_time = $office_shift[0]->saturday_in_time;
                    $out_time = $office_shift[0]->saturday_out_time;
                }
            } else if ($day == 'Sunday') {
                if ($office_shift[0]->sunday_in_time == '') {
                    $in_time = '00:00:00';
                    $out_time = '00:00:00';
                } else {
                    $in_time = $office_shift[0]->sunday_in_time;
                    $out_time = $office_shift[0]->sunday_out_time;
                }
            }
            // check if clock-in for date
            $attendance_status = '';
            $check = $this->Timesheet_model->attendance_first_in_check($employee[0]->user_id, $attendance_date);
            if ($check->num_rows() > 0) {
                // check clock in time
                $attendance = $this->Timesheet_model->attendance_first_in($employee[0]->user_id, $attendance_date);

                // clock in
                $clock_in = new DateTime($attendance[0]->clock_in);
                $clock_in2 = $clock_in->format('H:i:s');

                $clkInIp = $clock_in2;

                $office_time =  new DateTime($in_time . ' ' . $attendance_date);
                //time diff > total time late
                $office_time_new = strtotime($in_time . ' ' . $attendance_date);
                $clock_in_time_new = strtotime($attendance[0]->clock_in);
                if ($clock_in_time_new <= $office_time_new) {
                    $total_time_l = '-';
                } else {
                    $interval_late = $clock_in->diff($office_time);
                    $hours_l   = $interval_late->format('%h');
                    $minutes_l = $interval_late->format('%i');
                    $total_time_l = $hours_l . "j " . $minutes_l . "m ";
                }

                // total hours work/ed
                $total_hrs = $this->Timesheet_model->total_hours_worked_attendance($employee[0]->user_id, $attendance_date);
                $hrs_old_int1 = 0;
                $Total = '';
                $Trest = '';
                $hrs_old_seconds = 0;
                $hrs_old_seconds_rs = 0;
                $total_time_rs = '';
                $hrs_old_int_res1 = 0;
                foreach ($total_hrs->result() as $hour_work) {
                    // total work
                    $timee = $hour_work->total_work . ':00';
                    $str_time = $timee;

                    $str_time = preg_replace("/^([\d]{1,2})\:([\d]{2})$/", "00:$1:$2", $str_time);

                    sscanf($str_time, "%d:%d:%d", $hours, $minutes, $seconds);

                    $hrs_old_seconds = $hours * 3600 + $minutes * 60 + $seconds;

                    $hrs_old_int1 += $hrs_old_seconds;

                    $Total = gmdate("H:i", $hrs_old_int1);
                }
                if ($Total == '') {
                    $total_work = '-';
                } else {
                    $total_work = $Total;
                }

                // total rest >
                $total_rest = $this->Timesheet_model->total_rest_attendance($employee[0]->user_id, $attendance_date);
                foreach ($total_rest->result() as $rest) {
                    // total rest
                    $str_time_rs = $rest->total_rest . ':00';
                    //$str_time_rs =$timee_rs;

                    $str_time_rs = preg_replace("/^([\d]{1,2})\:([\d]{2})$/", "00:$1:$2", $str_time_rs);

                    sscanf($str_time_rs, "%d:%d:%d", $hours_rs, $minutes_rs, $seconds_rs);

                    $hrs_old_seconds_rs = $hours_rs * 3600 + $minutes_rs * 60 + $seconds_rs;

                    $hrs_old_int_res1 += $hrs_old_seconds_rs;

                    $total_time_rs = gmdate("H:i", $hrs_old_int_res1);
                }

                // check attendance status
                $status = $attendance[0]->attendance_status;
                if ($total_time_rs == '') {
                    $Trest = '00:00:00';
                } else {
                    $Trest = $total_time_rs;
                }
            } else {
                $clock_in2 = '-';
                $total_time_l = '-';
                $total_work = '-';
                $Trest = '00:00:00';
                $clkInIp = $clock_in2;
                // get holiday/leave or absent
                /* attendance status */
                // get holiday
                $h_date_chck = $this->Timesheet_model->holiday_date_check($attendance_date);
                $holiday_arr = array();
                if ($h_date_chck->num_rows() == 1) {
                    $h_date = $this->Timesheet_model->holiday_date($attendance_date);
                    $begin = new DateTime($h_date[0]->start_date);
                    $end = new DateTime($h_date[0]->end_date);
                    $end = $end->modify('+1 day');

                    $interval = new DateInterval('P1D');
                    $daterange = new DatePeriod($begin, $interval, $end);

                    foreach ($daterange as $date) {
                        $holiday_arr[] =  $date->format("Y-m-d");
                    }
                } else {
                    $holiday_arr[] = '99-99-99';
                }


                // get leave/employee
                $leave_date_chck = $this->Timesheet_model->leave_date_check($employee[0]->user_id, $attendance_date);
                $leave_arr = array();
                if ($leave_date_chck->num_rows() == 1) {
                    $leave_date = $this->Timesheet_model->leave_date($employee[0]->user_id, $attendance_date);
                    $begin1 = new DateTime($leave_date[0]->from_date);
                    $end1 = new DateTime($leave_date[0]->to_date);
                    $end1 = $end1->modify('+1 day');

                    $interval1 = new DateInterval('P1D');
                    $daterange1 = new DatePeriod($begin1, $interval1, $end1);

                    foreach ($daterange1 as $date1) {
                        $leave_arr[] =  $date1->format("Y-m-d");
                    }
                } else {
                    $leave_arr[] = '99-99-99';
                }

                if ($office_shift[0]->monday_in_time == '' && $day == 'Monday') {
                    $status = $this->lang->line('xin_holiday');
                } else if ($office_shift[0]->tuesday_in_time == '' && $day == 'Tuesday') {
                    $status = $this->lang->line('xin_holiday');
                } else if ($office_shift[0]->wednesday_in_time == '' && $day == 'Wednesday') {
                    $status = $this->lang->line('xin_holiday');
                } else if ($office_shift[0]->thursday_in_time == '' && $day == 'Thursday') {
                    $status = $this->lang->line('xin_holiday');
                } else if ($office_shift[0]->friday_in_time == '' && $day == 'Friday') {
                    $status = $this->lang->line('xin_holiday');
                } else if ($office_shift[0]->saturday_in_time == '' && $day == 'Saturday') {
                    $status = $this->lang->line('xin_holiday');
                } else if ($office_shift[0]->sunday_in_time == '' && $day == 'Sunday') {
                    $status = $this->lang->line('xin_holiday');
                } else if (in_array($attendance_date, $holiday_arr)) { // holiday
                    $status = $this->lang->line('xin_holiday');
                } else if (in_array($attendance_date, $leave_arr)) { // on leave
                    $status = $this->lang->line('xin_on_leave');
                } else {
                    $status = $this->lang->line('xin_absent');
                }
            }



            // check if clock-out for date
            $check_out = $this->Timesheet_model->attendance_first_out_check($employee[0]->user_id, $attendance_date);
            if ($check_out->num_rows() == 1) {
                /* early time */
                $early_time =  new DateTime($out_time . ' ' . $attendance_date);
                // check clock in time
                $first_out = $this->Timesheet_model->attendance_first_out($employee[0]->user_id, $attendance_date);
                // clock out
                $clock_out = new DateTime($first_out[0]->clock_out);

                if ($first_out[0]->clock_out != '') {
                    $clock_out2 = $clock_out->format('H:i:s');

                    // early leaving
                    $early_new_time = strtotime($out_time . ' ' . $attendance_date);
                    $clock_out_time_new = strtotime($first_out[0]->clock_out);

                    if ($early_new_time <= $clock_out_time_new) {
                        $total_time_e = '-';
                    } else {
                        $interval_lateo = $clock_out->diff($early_time);
                        $hours_e   = $interval_lateo->format('%h');
                        $minutes_e = $interval_lateo->format('%i');
                        $total_time_e = $hours_e . "j " . $minutes_e . "m ";
                    }

                    /* over time */
                    $over_time =  new DateTime($out_time . ' ' . $attendance_date);
                    $overtime2 = $over_time->format('H:i:s');
                    // over time
                    $over_time_new = strtotime($out_time . ' ' . $attendance_date);
                    $clock_out_time_new1 = strtotime($first_out[0]->clock_out);

                    if ($clock_out_time_new1 <= $over_time_new) {
                        $overtime2 = '-';
                    } else {
                        $interval_lateov = $clock_out->diff($over_time);
                        $hours_ov   = $interval_lateov->format('%h');
                        $minutes_ov = $interval_lateov->format('%i');
                        $overtime2 = $hours_ov . "j " . $minutes_ov . "m ";
                    }
                } else {
                    $clock_out2 =  '-';
                    $total_time_e = '-';
                    $overtime2 = '-';
                }
            } else {
                $clock_out2 =  '-';
                $total_time_e = '-';
                $overtime2 = '-';
            }
            // user full name
            $full_name = $employee[0]->first_name . ' ' . $employee[0]->last_name;
            // get company
            $company = $this->Core_model->read_company_info($employee[0]->company_id);
            if (!is_null($company)) {
                $comp_name = $company[0]->name;
            } else {
                $comp_name = '--';
            }
            // attendance date
            $tdate = $this->Core_model->set_date_format($attendance_date);

            $fclckIn = $clock_in2;
            $fclckOut = $clock_out2;

            $data[] = array(
                $full_name,
                $employee[0]->employee_id,
                $comp_name,
                $status,
                $tdate,
                $fclckIn,
                $fclckOut,
                $total_time_l,
                $total_time_e,
                $overtime2,
                $total_work,
                $Trest
            );
        }

        $output = array(
            "draw" => $draw,
            //"recordsTotal" => count($date_range),
            //"recordsFiltered" => count($date_range),
            "data" => $data
        );
        echo json_encode($output);
        exit();
    }

    // get record of leave by id > modal
    public function read_variation_record()
    {
        $data['title'] = $this->Core_model->site_title();
        $variation_id = $this->input->get('variation_id');
        $result = $this->Timesheet_model->read_variation_information($variation_id);

        $data = array(
            'variation_id' => $result[0]->variation_id,
            'project_id' => $result[0]->project_id,
            'company_id' => $result[0]->company_id,
            'client_approval' => $result[0]->client_approval,
            'created_by' => $result[0]->created_by,
            'variation_name' => $result[0]->variation_name,
            'assigned_to' => $result[0]->assigned_to,
            'start_date' => $result[0]->start_date,
            'end_date' => $result[0]->end_date,
            'variation_hours' => $result[0]->variation_hours,
            'variation_status' => $result[0]->variation_status,
            'variation_no' => $result[0]->variation_no,
            'description' => $result[0]->description,
            'created_at' => $result[0]->created_at,
            'all_employees' => $this->Core_model->all_employees()
        );
        $session = $this->session->userdata('username');
        if (!empty($session)) {
            $this->load->view('admin/pengaturan/tasks/dialog_task', $data);
        } else {
            redirect('admin/');
        }
    }

    // get record of attendance
    public function read()
    {
        $data['title'] = $this->Core_model->site_title();
        $attendance_id = $this->input->get('attendance_id');
        $result = $this->Timesheet_model->read_attendance_information($attendance_id);
        $user = $this->Core_model->read_user_info($result[0]->employee_id);
        // user full name
        $full_name = $user[0]->first_name . ' ' . $user[0]->last_name;

        $in_time = new DateTime($result[0]->clock_in);
        $out_time = new DateTime($result[0]->clock_out);

        $clock_in = $in_time->format('H:i:s');
        if ($result[0]->clock_out == '') {
            $clock_out = '';
        } else {
            $clock_out = $out_time->format('H:i:s');
        }

        $data = array(
            'time_attendance_id' => $result[0]->time_attendance_id,
            'employee_id' => $result[0]->employee_id,
            'full_name' => $full_name,
            'attendance_date' => $result[0]->attendance_date,
            'clock_in' => $clock_in,
            'clock_out' => $clock_out
        );
        $session = $this->session->userdata('username');
        if (!empty($session)) {
            $this->load->view('admin/pengaturan/dialog_attendance', $data);
        } else {
            redirect('admin/');
        }
    }

    //read_map_info
    public function read_map_info()
    {
        $data['title'] = $this->Core_model->site_title();
        //$office_shift_id = $this->input->get('office_shift_id');
        //$result = $this->Timesheet_model->read_office_shift_information($office_shift_id);

        $data = array(
            //	'office_shift_id' => $result[0]->office_shift_id,
            //'company_id' => $result[0]->company_id
        );
        $session = $this->session->userdata('username');
        if (!empty($session)) {
            $this->load->view('admin/pengaturan/dialog_read_map', $data);
        } else {
            redirect('admin/');
        }
    }

    // Validate and update info in database
    public function default_shift()
    {

        if ($this->input->get('office_shift_id')) {

            $id = $this->input->get('office_shift_id');

            /* Define return | here result is used to return user data and error for error message */
            $Return = array('result' => '', 'error' => '', 'csrf_hash' => '');
            $Return['csrf_hash'] = $this->security->get_csrf_hash();

            $data = array(
                'default_shift' => '0'
            );

            $data2 = array(
                'default_shift' => '1'
            );

            $result = $this->Timesheet_model->update_default_shift_zero($data);
            $result = $this->Timesheet_model->update_default_shift_record($data2, $id);

            if ($result == TRUE) {
                $Return['result'] = $this->lang->line('xin_success_shift_default_made');
            } else {
                $Return['error'] = $this->lang->line('xin_error_msg');
            }
            $this->output($Return);
            exit;
        }
    }

    // delete attendance record
    public function delete_attendance()
    {
        if ($this->input->post('type') == 'delete') {
            // Define return | here result is used to return user data and error for error message
            $Return = array('result' => '', 'error' => '', 'csrf_hash' => '');
            $id = $this->uri->segment(4);
            $Return['csrf_hash'] = $this->security->get_csrf_hash();
            $result = $this->Timesheet_model->delete_attendance_record($id);
            if (isset($id)) {
                $Return['result'] = $this->lang->line('xin_success_employe_attendance_deleted');
            } else {
                $Return['error'] = $this->lang->line('xin_error_msg');
            }
            $this->output($Return);
        }
    }

    // delete shift record
    public function delete_shift()
    {
        if ($this->input->post('type') == 'delete') {
            // Define return | here result is used to return user data and error for error message
            $Return = array('result' => '', 'error' => '', 'csrf_hash' => '');
            $id = $this->uri->segment(4);
            $Return['csrf_hash'] = $this->security->get_csrf_hash();
            $result = $this->Timesheet_model->delete_shift_record($id);
            if (isset($id)) {
                $Return['result'] = $this->lang->line('xin_success_shift_deleted');
            } else {
                $Return['error'] = $this->lang->line('xin_error_msg');
            }
            $this->output($Return);
        }
    }
}
