<?php

/**
 * ---------------------------------------------------------------------
 * INFORMASI
 * -----------------------------------------------------------------------
 * Nama Aplikasi :  HRIS
 * Email Support :  hris@karyasoftware.com
 * Developer     :  Nizar Basyrewan, S.Si - 0895 606460 731
 * Tahun         :  2020
 * Copyright     :  ©karyasoftware.com. All Rights Reserved
 * ----------------------------------------------------------------------
 */

defined('BASEPATH') or exit('No direct script access allowed');

/**
 * @property Clients_model $Clients_model
 */
class Settings extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();
        //load the model
        $this->load->model("Employee_exit_model");
        $this->load->model("Clients_model");
        $this->load->model("Core_model");
        $this->load->model("Employees_model");
        $this->load->model("Finance_model");
        $this->load->model("Company_model");
        $this->load->helper('string');
    }

    /*Function to set JSON output*/
    public function output($Return = array())
    {

        header("Access-Control-Allow-Origin: *");
        header("Content-Type: application/json; charset=UTF-8");

        exit(json_encode($Return));
    }

    public function index()
    {
        redirect('admin/dashboard');
    }

    // ====================================================================================================
    // 0101. MASTER DATA
    // ====================================================================================================

    public function constants()
    {
        $session = $this->session->userdata('username');
        if (empty($session)) {
            redirect('admin/');
        }
        $data['title'] = $this->lang->line('left_constants') . ' | ' . $this->Core_model->site_title();
        $data['icon'] = '<i class="fa fa-cubes"></i>';
        $data['breadcrumbs'] = $this->lang->line('left_constants');
        $company_info = $this->Core_model->read_company_setting_info(1);

        $data['all_companies'] = $this->Company_model->get_company();
        $data['path_url'] = 'constants';
        $role_resources_ids = $this->Core_model->user_role_resource();
        if (in_array('0101', $role_resources_ids)) {
            if (!empty($session)) {
                $data['subview'] = $this->load->view("admin/settings/constants", $data, TRUE);
                $this->load->view('admin/layout/layout_main', $data); //page load
            } else {
                redirect('admin/');
            }
        } else {
            redirect('admin/dashboard');
        }
    }


    // ================================================================================================
    // JENIS KONTRAK
    // ================================================================================================
    public function contract_type_list()
    {

        $data['title'] = $this->Core_model->site_title();
        $session = $this->session->userdata('username');
        if (!empty($session)) {
            $this->load->view("admin/settings/constants", $data);
        } else {
            redirect('admin/');
        }
        // Datatables Variables
        $draw   = intval($this->input->get("draw"));
        $start  = intval($this->input->get("start"));
        $length = intval($this->input->get("length"));


        $contract_type = $this->Core_model->get_contract_types();

        $data = array();

        foreach ($contract_type->result() as $r) {

            $data[] = array(
                '
                    <span data-toggle="tooltip" data-placement="top" title="' . $this->lang->line('xin_edit') . '">
                        <button type="button" class="btn icon-btn btn-xs btn-default waves-effect waves-light" data-toggle="modal" data-target=".edit_setting_datail" data-field_id="' . $r->contract_type_id . '" data-field_type="contract_type">
                            <span class="fa fa-pencil"></span>
                        </button>
                    </span>
                    <span data-toggle="tooltip" data-placement="top" title="' . $this->lang->line('xin_delete') . '">
                        <button type="button" class="btn icon-btn btn-xs btn-danger waves-effect waves-light delete" data-toggle="modal" data-target=".delete-modal" data-record-id="' . $r->contract_type_id . '" data-token_type="contract_type">
                            <span class="fa fa-trash"></span>
                        </button>
                    </span>',
                $r->name,
            );
        }

        $output = array(
            "draw" => $draw,
            "recordsTotal" => $contract_type->num_rows(),
            "recordsFiltered" => $contract_type->num_rows(),
            "data" => $data
        );

        $this->output->set_output(json_encode($output));
    }
    // Update
    public function update_contract_type()
    {
        if ($this->input->post('type') == 'edit_record') {

            $id = $this->uri->segment(4);

            /* Define return | here result is used to return user data and error for error message */
            $Return = array('result' => '', 'error' => '', 'csrf_hash' => '');
            $Return['csrf_hash'] = $this->security->get_csrf_hash();

            /* Server side PHP input validation */
            if ($this->input->post('name') === '') {
                $Return['error'] = $this->lang->line('xin_employee_error_contract_type');
            }

            if ($Return['error'] != '') {
                //$this->output->set_output(json_encode($Return));
            }

            $data = array(

                'name' => $this->input->post('name')
            );

            $result = $this->Core_model->update_contract_type_record($data, $id);

            if ($result == TRUE) {
                $Return['result'] = $this->lang->line('xin_success_contract_type_updated');
            } else {
                $Return['error'] = $this->lang->line('xin_error_msg');
            }
            $this->output($Return);
            exit;
        }
    }
    // Add
    public function contract_type_info()
    {
        if ($this->input->post('type') == 'contract_type_info') {
            /* Define return | here result is used to return user data and error for error message */
            $Return = array('result' => '', 'error' => '', 'csrf_hash' => '');
            $Return['csrf_hash'] = $this->security->get_csrf_hash();

            /* Server side PHP input validation */
            if ($this->input->post('contract_type') === '') {
                $Return['error'] = $this->lang->line('xin_employee_error_contract_type');
            }

            if ($Return['error'] != '') {
                $this->output($Return);
                //$this->output->set_output(json_encode($Return));
            }

            $data = array(
                'name' => $this->input->post('contract_type'),
                'created_at' => date('d-m-Y h:i:s')
            );
            $result = $this->Core_model->add_contract_type($data);
            if ($result == TRUE) {
                $Return['result'] = $this->lang->line('xin_success_contract_type_added');
            } else {
                $Return['error'] = $this->lang->line('xin_error_msg');
            }
            $this->output($Return);
            exit;
            //$this->output->set_output(json_encode($Return));
            // exit;
        }
    }
    // Delete
    public function delete_contract_type()
    {
        if ($this->input->post('type') == 'delete_record') {
            /* Define return | here result is used to return user data and error for error message */
            $Return = array('result' => '', 'error' => '', 'csrf_hash' => '');
            $id = $this->uri->segment(4);
            $Return['csrf_hash'] = $this->security->get_csrf_hash();
            $result              = $this->Core_model->delete_contract_type_record($id);
            if (isset($id)) {
                $Return['result'] = $this->lang->line('xin_success_contract_type_deleted');
            } else {
                $Return['error']  = $this->lang->line('xin_error_msg');
            }
            $this->output($Return);
            exit;
        }
    }

    // ************************************************************************************************
    // JENIS JENJANG PENDIDIKAN
    // ************************************************************************************************

    // Education Level > list
    public function education_level_list()
    {

        $data['title'] = $this->Core_model->site_title();
        $session = $this->session->userdata('username');
        if (!empty($session)) {
            $this->load->view("admin/settings/constants", $data);
        } else {
            redirect('admin/');
        }
        // Datatables Variables
        $draw = intval($this->input->get("draw"));
        $start = intval($this->input->get("start"));
        $length = intval($this->input->get("length"));


        $constant = $this->Core_model->get_qualification_education();

        $data = array();

        foreach ($constant->result() as $r) {

            $data[] = array(
                '<span data-toggle="tooltip" data-placement="top" title="' . $this->lang->line('xin_edit') . '"><button type="button" class="btn icon-btn btn-xs btn-default waves-effect waves-light" data-toggle="modal" data-target=".edit_setting_datail" data-field_id="' . $r->education_level_id . '" data-field_type="education_level"><span class="fa fa-pencil"></span></button></span><span data-toggle="tooltip" data-placement="top" title="' . $this->lang->line('xin_delete') . '"><button type="button" class="btn icon-btn btn-xs btn-danger waves-effect waves-light delete" data-toggle="modal" data-target=".delete-modal" data-record-id="' . $r->education_level_id . '" data-token_type="education_level"><span class="fa fa-trash"></span></button></span>',
                $r->name,
            );
        }

        $output = array(
            "draw" => $draw,
            "recordsTotal" => $constant->num_rows(),
            "recordsFiltered" => $constant->num_rows(),
            "data" => $data
        );

        $this->output->set_output(json_encode($output));
    }


    // ************************************************************************************************
    // JENIS PENGHARGAAN
    // ************************************************************************************************

    // Daftar :
    public function award_type_list()
    {
        $data['title'] = $this->Core_model->site_title();
        $session = $this->session->userdata('username');
        if (!empty($session)) {
            $this->load->view("admin/settings/constants", $data);
        } else {
            redirect('admin/');
        }
        // Datatables Variables
        $draw = intval($this->input->get("draw"));
        $start = intval($this->input->get("start"));
        $length = intval($this->input->get("length"));


        $constant = $this->Core_model->get_award_type();

        $data = array();

        foreach ($constant->result() as $r) {

            $data[] = array(
                '<span data-toggle="tooltip" data-placement="top" title="' . $this->lang->line('xin_edit') . '"><button type="button" class="btn icon-btn btn-xs btn-default waves-effect waves-light" data-toggle="modal" data-target=".edit_setting_datail" data-field_id="' . $r->award_type_id . '" data-field_type="award_type"><span class="fa fa-pencil"></span></button></span><span data-toggle="tooltip" data-placement="top" title="' . $this->lang->line('xin_delete') . '"><button type="button" class="btn icon-btn btn-xs btn-danger waves-effect waves-light delete" data-toggle="modal" data-target=".delete-modal" data-record-id="' . $r->award_type_id . '" data-token_type="award_type"><span class="fa fa-trash"></span></button></span>',
                $r->award_type,
            );
        }

        $output = array(
            "draw" => $draw,
            "recordsTotal" => $constant->num_rows(),
            "recordsFiltered" => $constant->num_rows(),
            "data" => $data
        );

        $this->output->set_output(json_encode($output));
    }

    // Proses : Tambah
    public function award_type_info()
    {
        if ($this->input->post('type') == 'award_type_info') {
            /* Define return | here result is used to return user data and error for error message */
            $Return = array('result' => '', 'error' => '', 'csrf_hash' => '');
            $Return['csrf_hash'] = $this->security->get_csrf_hash();

            /* Server side PHP input validation */
            if ($this->input->post('award_type') === '') {
                $Return['error'] = $this->lang->line('xin_award_error_award_type');
            }

            if ($Return['error'] != '') {
                //$this->output->set_output(json_encode($Return));
                $this->output($Return);
            }

            $data = array(
                'award_type' => $this->input->post('award_type'),
                'created_at' => date('d-m-Y h:i:s')
            );

            $result = $this->Core_model->add_award_type($data);
            if ($result == TRUE) {
                $Return['result'] = $this->lang->line('xin_success_award_type_added');
            } else {
                $Return['error'] = $this->lang->line('xin_error_msg');
            }
            //$this->output->set_output(json_encode($Return));

            $this->output($Return);
            exit;
            // exit;
        }
    }

    // Proses : Hapus
    public function delete_award_type()
    {
        if ($this->input->post('type') == 'delete_record') {
            /* Define return | here result is used to return user data and error for error message */
            $Return = array('result' => '', 'error' => '', 'csrf_hash' => '');
            $id = $this->uri->segment(4);
            $Return['csrf_hash'] = $this->security->get_csrf_hash();
            $result = $this->Core_model->delete_award_type_record($id);
            if (isset($id)) {
                $Return['result'] = $this->lang->line('xin_success_award_type_deleted');
            } else {
                $Return['error'] = $this->lang->line('xin_error_msg');
            }
            //$this->output->set_output(json_encode($Return));
        }
    }

    // Proses : Update
    public function update_award_type()
    {
        if ($this->input->post('type') == 'edit_record') {

            $id = $this->uri->segment(4);

            /* Define return | here result is used to return user data and error for error message */
            $Return = array('result' => '', 'error' => '', 'csrf_hash' => '');
            $Return['csrf_hash'] = $this->security->get_csrf_hash();

            /* Server side PHP input validation */
            if ($this->input->post('name') === '') {
                $Return['error'] = $this->lang->line('xin_award_error_award_type');
            }

            if ($Return['error'] != '') {
                //$this->output->set_output(json_encode($Return));
            }

            $data = array(

                'award_type' => $this->input->post('name')
            );

            $result = $this->Core_model->update_award_type_record($data, $id);

            if ($result == TRUE) {
                $Return['result'] = $this->lang->line('xin_success_award_type_updated');
            } else {
                $Return['error'] = $this->lang->line('xin_error_msg');
            }
            //$this->output->set_output(json_encode($Return));
            // exit;
        }
    }

    // ************************************************************************************************
    // JENIS PERINGATAN
    // ************************************************************************************************

    // ************************************************************************************************
    // JENIS AGAMA
    // ************************************************************************************************

    // Daftar :
    public function ethnicity_type_list()
    {

        $data['title'] = $this->Core_model->site_title();
        $session = $this->session->userdata('username');
        if (!empty($session)) {
            $this->load->view("admin/settings/constants", $data);
        } else {
            redirect('admin/');
        }
        // Datatables Variables
        $draw = intval($this->input->get("draw"));
        $start = intval($this->input->get("start"));
        $length = intval($this->input->get("length"));


        $constant = $this->Core_model->get_ethnicity_type();

        $data = array();

        foreach ($constant->result() as $r) {

            $data[] = array(
                '<span data-toggle="tooltip" data-placement="top" title="' . $this->lang->line('xin_edit') . '"><button type="button" class="btn icon-btn btn-xs btn-default waves-effect waves-light" data-toggle="modal" data-target=".edit_setting_datail" data-field_id="' . $r->ethnicity_type_id . '" data-field_type="ethnicity_type"><span class="fa fa-pencil"></span></button></span><span data-toggle="tooltip" data-placement="top" title="' . $this->lang->line('xin_delete') . '"><button type="button" class="btn icon-btn btn-xs btn-danger waves-effect waves-light delete" data-toggle="modal" data-target=".delete-modal" data-record-id="' . $r->ethnicity_type_id . '" data-token_type="ethnicity_type"><span class="fa fa-trash"></span></button></span>',
                $r->type
            );
        }

        $output = array(
            "draw" => $draw,
            "recordsTotal" => $constant->num_rows(),
            "recordsFiltered" => $constant->num_rows(),
            "data" => $data
        );

        $this->output->set_output(json_encode($output));
    }

    // Proses : Tambah
    public function ethnicity_type_info()
    {
        if ($this->input->post('type') == 'ethnicity_type_info') {
            /* Define return | here result is used to return user data and error for error message */
            $Return = array('result' => '', 'error' => '', 'csrf_hash' => '');
            $Return['csrf_hash'] = $this->security->get_csrf_hash();

            /* Server side PHP input validation */
            if ($this->input->post('ethnicity_type') === '') {
                $Return['error'] = $this->lang->line('xin_ethnicity_type_error_field');
            }

            if ($Return['error'] != '') {
                //$this->output->set_output(json_encode($Return));
            }

            $data = array(
                'type' => $this->input->post('ethnicity_type'),
                'created_at' => date('d-m-Y h:i:s')
            );

            $result = $this->Core_model->add_ethnicity_type($data);
            if ($result == TRUE) {
                $Return['result'] = $this->lang->line('xin_ethnicity_type_success_added');
            } else {
                $Return['error'] = $this->lang->line('xin_error_msg');
            }
            //$this->output->set_output(json_encode($Return));
            // exit;
        }
    }

    // Proses : Hapus
    public function delete_ethnicity_type()
    {
        if ($this->input->post('type') == 'delete_record') {
            /* Define return | here result is used to return user data and error for error message */
            $Return = array('result' => '', 'error' => '', 'csrf_hash' => '');
            $id = $this->uri->segment(4);
            $Return['csrf_hash'] = $this->security->get_csrf_hash();
            $result = $this->Core_model->delete_ethnicity_type_record($id);
            if (isset($id)) {
                $Return['result'] = $this->lang->line('xin_ethnicity_type_success_deleted');
            } else {
                $Return['error'] = $this->lang->line('xin_error_msg');
            }
            //$this->output->set_output(json_encode($Return));
        }
    }

    // Proses : Update
    public function update_ethnicity_type()
    {
        if ($this->input->post('type') == 'edit_record') {

            $id = $this->uri->segment(4);

            /* Define return | here result is used to return user data and error for error message */
            $Return = array('result' => '', 'error' => '', 'csrf_hash' => '');
            $Return['csrf_hash'] = $this->security->get_csrf_hash();

            /* Server side PHP input validation */
            if ($this->input->post('ethnicity_type') === '') {
                $Return['error'] = $this->lang->line('xin_ethnicity_type_error_field');
            }

            if ($Return['error'] != '') {
                //$this->output->set_output(json_encode($Return));
            }

            $data = array(
                'type' => $this->input->post('ethnicity_type'),
            );

            $result = $this->Core_model->update_ethnicity_type_record($data, $id);

            if ($result == TRUE) {
                $Return['result'] = $this->lang->line('xin_ethnicity_type_success_updated');
            } else {
                $Return['error'] = $this->lang->line('xin_error_msg');
            }
            //$this->output->set_output(json_encode($Return));
            // exit;
        }
    }

    // ************************************************************************************************
    // JENIS CUTI
    // ************************************************************************************************

    // ************************************************************************************************
    // JENIS IZIN
    // ************************************************************************************************

    // ************************************************************************************************
    // JENIS SAKIT
    // ************************************************************************************************

    // ************************************************************************************************
    // JENIS RESIGN
    // ************************************************************************************************

    // ************************************************************************************************
    // JENIS DINAS
    // ************************************************************************************************


    // ************************************************************************************************
    // JENIS TRANSOPRT
    // ************************************************************************************************

    public function transport_arr_type_list()
    {

        $data['title'] = $this->Core_model->site_title();
        $session = $this->session->userdata('username');
        if (!empty($session)) {
            $this->load->view("admin/settings/constants", $data);
        } else {
            redirect('admin/');
        }
        // Datatables Variables
        $draw = intval($this->input->get("draw"));
        $start = intval($this->input->get("start"));
        $length = intval($this->input->get("length"));


        $constant = $this->Core_model->get_transport_type();

        $data = array();

        foreach ($constant->result() as $r) {

            $data[] = array(
                '<span data-toggle="tooltip" data-placement="top" title="' . $this->lang->line('xin_edit') . '"><button type="button" class="btn icon-btn btn-xs btn-default waves-effect waves-light" data-toggle="modal" data-target=".edit_setting_datail" data-field_id="' . $r->arrangement_type_id . '" data-field_type="transport_arr_type"><span class="fa fa-pencil"></span></button></span><span data-toggle="tooltip" data-placement="top" title="' . $this->lang->line('xin_delete') . '"><button type="button" class="btn icon-btn btn-xs btn-danger waves-effect waves-light delete" data-toggle="modal" data-target=".delete-modal" data-record-id="' . $r->arrangement_type_id . '" data-token_type="transport_arr_type"><span class="fa fa-trash"></span></button></span>',
                $r->type
            );
        }

        $output = array(
            "draw" => $draw,
            "recordsTotal" => $constant->num_rows(),
            "recordsFiltered" => $constant->num_rows(),
            "data" => $data
        );

        $this->output->set_output(json_encode($output));
    }

    // ************************************************************************************************
    // JENSI PERJANJIAN
    // ************************************************************************************************

    // Daftar :
    public function perjanjian_type()
    {

        $data['title'] = $this->Core_model->site_title();
        $session = $this->session->userdata('username');
        if (!empty($session)) {
            $this->load->view("admin/settings/constants", $data);
        } else {
            redirect('admin/');
        }
        // Datatables Variables
        $draw = intval($this->input->get("draw"));
        $start = intval($this->input->get("start"));
        $length = intval($this->input->get("length"));


        $constant = $this->Core_model->get_perjanjian_type();

        $data = array();

        foreach ($constant->result() as $r) {

            $data[] = array(

                '<span data-toggle="tooltip" data-placement="top" title="' . $this->lang->line('xin_edit') . '">
                                    <button type="button" class="btn icon-btn btn-xs btn-default waves-effect waves-light" data-toggle="modal" data-target=".edit_setting_datail" data-field_id="' . $r->perjanjian_type_id . '" data-field_type="perjanjian_type">
                                        <span class="fa fa-pencil"></span>
                                    </button>
                                </span>

                                <span data-toggle="tooltip" data-placement="top" title="' . $this->lang->line('xin_delete') . '">
                                    <button type="button" class="btn icon-btn btn-xs btn-danger waves-effect waves-light delete" data-toggle="modal" data-target=".delete-modal" data-record-id="' . $r->perjanjian_type_id . '" data-token_type="perjanjian_type">
                                        <span class="fa fa-trash"></span>
                                    </button>
                                </span>',
                $r->perjanjian_type_name

            );
        }

        $output = array(
            "draw"              => $draw,
            "recordsTotal"    => $constant->num_rows(),
            "recordsFiltered" => $constant->num_rows(),
            "data" => $data
        );

        $this->output->set_output(json_encode($output));
    }

    // Proses : Tambah
    public function perjanjian_type_info()
    {
        if ($this->input->post('type') == 'perjanjian_type_info') {
            /* Define return | here result is used to return user data and error for error message */
            $Return = array('result' => '', 'error' => '', 'csrf_hash' => '');
            $Return['csrf_hash'] = $this->security->get_csrf_hash();

            /* Server side PHP input validation */
            if ($this->input->post('perjanjian_type_name') === '') {
                $Return['error'] = 'Type Perjanjian Tidak Boleh Kosong';
            }

            if ($Return['error'] != '') {
                $this->output($Return);
            }

            $data = array(
                'perjanjian_type_name' => $this->input->post('perjanjian_type_name'),
                'created_at' => date('Y-m-d h:i:s')
            );

            $result = $this->Core_model->add_perjanjian_type($data);
            if ($result == TRUE) {
                $Return['result'] = 'Jenis Perjanjian Baru Berhasil Ditambahkan';
            } else {
                $Return['error'] = $this->lang->line('xin_error_msg');
            }
            $this->output($Return);
            exit;
        }
    }

    // Proses : Hapus
    public function delete_perjanjian_type()
    {
        if ($this->input->post('type') == 'delete_record') {
            /* Define return | here result is used to return user data and error for error message */
            $Return = array('result' => '', 'error' => '', 'csrf_hash' => '');
            $id = $this->uri->segment(4);
            $Return['csrf_hash'] = $this->security->get_csrf_hash();
            $result = $this->Core_model->delete_perjanjian_type_record($id);
            if (isset($id)) {
                $Return['result'] = 'Jenis Perjanjian Berhasil Dihapus';
            } else {
                $Return['error'] = $this->lang->line('xin_error_msg');
            }

            $this->output($Return);
            exit;
        }
    }

    // Proses : Update
    public function update_perjanjian_type()
    {
        if ($this->input->post('type') == 'edit_record') {

            $id = $this->uri->segment(4);

            /* Define return | here result is used to return user data and error for error message */
            $Return = array('result' => '', 'error' => '', 'csrf_hash' => '');
            $Return['csrf_hash'] = $this->security->get_csrf_hash();

            /* Server side PHP input validation */
            if ($this->input->post('perjanjian_type_name') === '') {
                $Return['error'] = 'Jenis Perjanjian Tidak Boleh Kosong';
            }

            if ($Return['error'] != '') {
                $this->output->set_output(json_encode($Return));
            }

            $data = array(
                'perjanjian_type_name' => $this->input->post('perjanjian_type_name')

            );

            $result = $this->Core_model->update_perjanjian_type_record($data, $id);

            if ($result == TRUE) {
                $Return['result'] = 'Jenis Perjanjian Berhasil Diperbarui';
            } else {
                $Return['error'] = $this->lang->line('xin_error_msg');
            }

            $this->output($Return);
            exit;
        }
    }

    // ************************************************************************************************
    // JENSI Perizinan
    // ************************************************************************************************

    // Daftar :
    public function perizinan_type()
    {

        $data['title'] = $this->Core_model->site_title();
        $session = $this->session->userdata('username');
        if (!empty($session)) {
            $this->load->view("admin/settings/constants", $data);
        } else {
            redirect('admin/');
        }
        // Datatables Variables
        $draw = intval($this->input->get("draw"));
        $start = intval($this->input->get("start"));
        $length = intval($this->input->get("length"));


        $constant = $this->Core_model->get_perizinan_type();

        $data = array();

        foreach ($constant->result() as $r) {

            $data[] = array(

                '<span data-toggle="tooltip" data-placement="top" title="' . $this->lang->line('xin_edit') . '">
                                    <button type="button" class="btn icon-btn btn-xs btn-default waves-effect waves-light" data-toggle="modal" data-target=".edit_setting_datail" data-field_id="' . $r->perizinan_type_id . '" data-field_type="perizinan_type">
                                        <span class="fa fa-pencil"></span>
                                    </button>
                                </span>

                                <span data-toggle="tooltip" data-placement="top" title="' . $this->lang->line('xin_delete') . '">
                                    <button type="button" class="btn icon-btn btn-xs btn-danger waves-effect waves-light delete" data-toggle="modal" data-target=".delete-modal" data-record-id="' . $r->perizinan_type_id . '" data-token_type="perizinan_type">
                                        <span class="fa fa-trash"></span>
                                    </button>
                                </span>',
                $r->perizinan_type_name

            );
        }

        $output = array(
            "draw"              => $draw,
            "recordsTotal"    => $constant->num_rows(),
            "recordsFiltered" => $constant->num_rows(),
            "data" => $data
        );

        $this->output->set_output(json_encode($output));
    }

    // Proses : Tambah
    public function perizinan_type_info()
    {
        if ($this->input->post('type') == 'perizinan_type_info') {

            /* Define return | here result is used to return user data and error for error message */

            $Return              = array('result' => '', 'error' => '', 'csrf_hash' => '');
            $Return['csrf_hash'] = $this->security->get_csrf_hash();

            /* Server side PHP input validation */
            if ($this->input->post('perizinan_type_name') === '') {
                $Return['error'] = 'Type Perizinan Tidak Boleh Kosong';
            }

            if ($Return['error'] != '') {
                $this->output($Return);
            }

            $data = array(
                'perizinan_type_name' => $this->input->post('perizinan_type_name'),
                'created_at'          => date('Y-m-d h:i:s')
            );

            $result = $this->Core_model->add_perizinan_type($data);
            if ($result == TRUE) {
                $Return['result'] = 'Jenis Perizinan Baru Berhasil Ditambahkan';
            } else {
                $Return['error'] = $this->lang->line('xin_error_msg');
            }
            $this->output($Return);
            exit;
        }
    }

    // Proses : Hapus
    public function delete_perizinan_type()
    {
        if ($this->input->post('type') == 'delete_record') {
            /* Define return | here result is used to return user data and error for error message */
            $Return = array('result' => '', 'error' => '', 'csrf_hash' => '');
            $id = $this->uri->segment(4);
            $Return['csrf_hash'] = $this->security->get_csrf_hash();
            $result = $this->Core_model->delete_perizinan_type_record($id);
            if (isset($id)) {
                $Return['result'] = 'Jenis Perizinan Berhasil Dihapus';
            } else {
                $Return['error'] = $this->lang->line('xin_error_msg');
            }

            $this->output($Return);
            exit;
        }
    }

    // Proses : Update
    public function update_perizinan_type()
    {
        if ($this->input->post('type') == 'edit_record') {

            $id = $this->uri->segment(4);

            /* Define return | here result is used to return user data and error for error message */
            $Return = array('result' => '', 'error' => '', 'csrf_hash' => '');
            $Return['csrf_hash'] = $this->security->get_csrf_hash();

            /* Server side PHP input validation */
            if ($this->input->post('perizinan_type_name') === '') {
                $Return['error'] = 'Jenis Perizinan Tidak Boleh Kosong';
            }

            if ($Return['error'] != '') {
                $this->output->set_output(json_encode($Return));
            }

            $data = array(
                'perizinan_type_name' => $this->input->post('perizinan_type_name')

            );

            $result = $this->Core_model->update_perizinan_type_record($data, $id);

            if ($result == TRUE) {
                $Return['result'] = 'Jenis Perizinan Berhasil Diperbarui';
            } else {
                $Return['error'] = $this->lang->line('xin_error_msg');
            }

            $this->output($Return);
            exit;
        }
    }

    // ====================================================================================================
    // 0102. BACKUP DATA
    // ====================================================================================================

    // database backup
    public function database_backup()
    {
        $session = $this->session->userdata('username');
        if (empty($session)) {
            redirect('admin/');
        }
        $data['title']       = $this->lang->line('left_db_backup') . ' | ' . $this->Core_model->site_title();
        $data['icon'] = '<i class="fa fa-database"></i>';
        $data['breadcrumbs'] = $this->lang->line('left_db_backup');
        $data['path_url']     = 'database_backup';

        $setting = $this->Core_model->read_setting_info(1);
        $company_info = $this->Core_model->read_company_setting_info(1);
        $role_resources_ids = $this->Core_model->user_role_resource();
        if (in_array('102', $role_resources_ids)) {
            if (!empty($session)) {
                $data['subview'] = $this->load->view("admin/settings/database_backup", $data, TRUE);
                $this->load->view('admin/layout/layout_main', $data); //page load
            } else {
                redirect('admin/');
            }
        } else {
            redirect('admin/dashboard');
        }
    }

    // backup list
    public function database_backup_list()
    {
        $data['title'] = $this->Core_model->site_title();
        $session = $this->session->userdata('username');
        if (!empty($session)) {
            $this->load->view("admin/settings/database_backup", $data);
        } else {
            redirect('admin/');
        }
        // Datatables Variables
        $draw = intval($this->input->get("draw"));
        $start = intval($this->input->get("start"));
        $length = intval($this->input->get("length"));

        $db_backup = $this->Core_model->all_db_backup();

        $data = array();

        foreach ($db_backup->result() as $r) {

            $created_at = $this->Core_model->set_date_format($r->created_at);

            $data[] = array(
                '
                <span data-toggle="tooltip" data-placement="top" title="' . $this->lang->line('xin_download') . '">
                    <a href="' . site_url() . 'admin/download?type=dbbackup&filename=' . $r->backup_file . '">
                        <button type="button" class="btn icon-btn btn-xs btn-default waves-effect waves-light">
                            <span class="fa fa-download"></span>
                        </button>
                    </a>
                </span>
                <span data-toggle="tooltip" data-placement="top" title="' . $this->lang->line('xin_delete') . '">
                    <button type="button" class="btn icon-btn btn-xs btn-danger waves-effect waves-light delete" data-toggle="modal" data-target=".delete-modal" data-record-id="' . $r->backup_id . '">
                        <span class="fa fa-trash"></span>
                    </button>
                </span>',
                $created_at,
                $r->backup_file

            );
        }

        $output = array(
            "draw" => $draw,
            "recordsTotal" => $db_backup->num_rows(),
            "recordsFiltered" => $db_backup->num_rows(),
            "data" => $data
        );

        $this->output->set_output(json_encode($output));
    }

    public function backup_database($directory, $outname, $dbhost, $dbuser, $dbpass, $dbname)
    {
        // check mysqli extension installed
        if (!function_exists('mysqli_connect')) {
            die(' This scripts need mysql extension to be running properly ! please resolve!!');
        }
        $mysqli = @new mysqli($dbhost, $dbuser, $dbpass, $dbname);

        if ($mysqli->connect_error) {
            print_r($mysqli->connect_error);
            return false;
        }
        $dir = $directory;
        $result = '<p> Could not create backup directory on :' . $dir . ' Please Please make sure you have set Directory on 755 or 777 for a while.</p>';
        $res = true;
        if (!is_dir($dir)) {
            if (!@mkdir($dir, 755)) {
                $res = false;
            }
        }
        $n = 1;
        if ($res) {
            $name     = $outname;
            # counts
            if (file_exists($dir . '/' . $name . '.sql.gz')) {
                for ($i = 1; @count(file($dir . '/' . $name . '_' . $i . '.sql.gz')); $i++) {
                    $name = $name;
                    if (!file_exists($dir . '/' . $name . '_' . $i . '.sql.gz')) {
                        $name = $name . '_' . $i;
                        break;
                    }
                }
            }
            $fullname = $dir . '/' . $name . '.sql.gz'; # full structures
            if (!$mysqli->error) {
                $sql = "SHOW TABLES";
                $show = $mysqli->query($sql);
                while ($r = $show->fetch_array()) {
                    $tables[] = $r[0];
                }
                if (!empty($tables)) {
                    //cycle through
                    $return = '';
                    foreach ($tables as $table) {
                        $result     = $mysqli->query('SELECT * FROM ' . $table);
                        $num_fields = $result->field_count;
                        $row2       = $mysqli->query('SHOW CREATE TABLE ' . $table);
                        $row2       = $row2->fetch_row();
                        $return    .=
                            "\n
            -- ---------------------------------------------------------
            --
            -- Table structure for table : `{$table}`
            --
            -- ---------------------------------------------------------
            " . $row2[1] . ";\n";
                        for ($i = 0; $i < $num_fields; $i++) {
                            $n = 1;
                            while ($row = $result->fetch_row()) {

                                if ($n++ == 1) { # set the first statements
                                    $return .=
                                        "
            --
            -- Dumping data for table `{$table}`
            --
            ";
                                    /**
                                     * Get structural of fields each tables
                                     */
                                    $array_field = array(); #reset ! important to resetting when loop
                                    while ($field = $result->fetch_field()) # get field
                                    {
                                        $array_field[] = '`' . $field->name . '`';
                                    }
                                    $array_f[$table] = $array_field;
                                    // $array_f = $array_f;
                                    # endwhile
                                    $array_field = implode(', ', $array_f[$table]); #implode arrays
                                    $return .= "INSERT INTO `{$table}` ({$array_field}) VALUES\n(";
                                } else {
                                    $return .= '(';
                                }
                                for ($j = 0; $j < $num_fields; $j++) {

                                    $row[$j] = str_replace('\'', '\'\'', preg_replace("/\n/", "\\n", $row[$j]));
                                    if (isset($row[$j])) {
                                        $return .= is_numeric($row[$j]) ? $row[$j] : '\'' . $row[$j] . '\'';
                                    } else {
                                        $return .= '\'\'';
                                    }
                                    if ($j < ($num_fields - 1)) {
                                        $return .= ', ';
                                    }
                                }
                                $return .= "),\n";
                            }
                            # check matching
                            @preg_match("/\),\n/", $return, $match, false, -3); # check match
                            if (isset($match[0])) {
                                $return = substr_replace($return, ";\n", -2);
                            }
                        }

                        $return .= "\n";
                    }
                    $return =
                        "-- ---------------------------------------------------------
            --
            -- SIMPLE SQL Dump
            --
            -- nawa (at) yahoo (dot) com
            --
            -- Host Connection Info: " . $mysqli->host_info . "
            -- Generation Time: " . date('F d, Y \a\t H:i A ( e )') . "
            -- PHP Version: " . PHP_VERSION . "
            --
            -- ---------------------------------------------------------\n\n
            SET SQL_MODE = \"NO_AUTO_VALUE_ON_ZERO\";
            SET time_zone = \"+00:00\";
            /*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
            /*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
            /*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
            /*!40101 SET NAMES utf8 */;
            " . $return . "
            /*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
            /*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
            /*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;";
                    # end values result
                    @ini_set('zlib.output_compression', 'Off');

                    $gzipoutput = gzencode($return, 9);
                    if (@file_put_contents($fullname, $gzipoutput)) { # 9 as compression levels

                        $result = $name . '.sql.gz'; # show the name

                    } else { # if could not put file , automaticly you will get the file as downloadable
                        $result = false;
                        // various headers, those with # are mandatory
                        header('Content-Type: application/x-gzip'); // change it to mimetype
                        header("Content-Description: File Transfer");
                        header('Content-Encoding: gzip'); #
                        header('Content-Length: ' . strlen($gzipoutput)); #
                        header('Content-Disposition: attachment; filename="' . $name . '.sql.gz' . '"');
                        header('Cache-Control: no-cache, no-store, max-age=0, must-revalidate');
                        header('Connection: Keep-Alive');
                        header("Content-Transfer-Encoding: binary");
                        header('Expires: 0');
                        header('Pragma: no-cache');

                        echo $gzipoutput;
                    }
                } else {
                    $result = '<p>Error when executing database query to export.</p>' . $mysqli->error;
                }
            }
        } else {
            $result = '<p>Wrong mysqli input</p>';
        }

        if ($mysqli && !$mysqli->error) {
            @$mysqli->close();
        }
        return $result;
    }

    public function create_database_backup()
    {
        $data['title'] = $this->Core_model->site_title();
        if ($this->input->post('type') === 'backup') {

            /* Define return | here result is used to return user data and error for error message */
            $Return = array('result' => '', 'error' => '', 'csrf_hash' => '');
            $Return['csrf_hash'] = $this->security->get_csrf_hash();

            $db = array('default' => array());
            // get db credentials
            require 'application/config/database.php';
            $hostname = $db['default']['hostname'];
            $username = $db['default']['username'];
            $password = $db['default']['password'];
            $database = $db['default']['database'];

            $dir  = 'uploads/dbbackup/'; // directory files
            $name = 'backup_' . date('d-m-Y_H_i_s'); // name sql backup
            $this->backup_database($dir, $name, $hostname, $username, $password, $database); // execute

            $fname = $name . '.sql.gz';

            $data = array(
                'backup_file' => $fname,
                'created_at' => date('d-m-Y H:i:s')
            );

            $result = $this->Core_model->add_backup($data);

            if ($result == TRUE) {
                $Return['result'] = $this->lang->line('xin_database_backup_generated');
            } else {
                $Return['error'] = $this->lang->line('xin_error_msg');
            }

            $this->output($Return);
            exit;

            //$this->output->set_output(json_encode($Return));
            // exit;
        }
    }

    public function delete_db_backup()
    {
        if ($this->input->post('type') === 'delete_old_backup') {

            /* Define return | here result is used to return user data and error for error message */
            $Return = array('result' => '', 'error' => '', 'csrf_hash' => '');
            $Return['csrf_hash'] = $this->security->get_csrf_hash();

            /*Delete backup*/
            $result = $this->Core_model->delete_all_backup_record();
            $baseurl = base_url();
            $files = glob('uploads/dbbackup/*'); //get all file names
            foreach ($files as $file) {
                if (is_file($file))
                    unlink($file); //delete file
            }

            $Return['result'] = $this->lang->line('xin_success_database_old_backup_deleted');

            $this->output($Return);
            exit;

            //$this->output->set_output(json_encode($Return));
            // exit;
        }
    }

    public function delete_single_backup()
    {
        /* Define return | here result is used to return user data and error for error message */
        $Return = array('result' => '', 'error' => '', 'csrf_hash' => '');
        $id = $this->uri->segment(4);
        $Return['csrf_hash'] = $this->security->get_csrf_hash();
        $result = $this->Core_model->delete_single_backup_record($id);
        if (isset($id)) {
            $Return['result'] = $this->lang->line('xin_success_database_backup_deleted');
        } else {
            $Return['error'] = $this->lang->line('xin_error_msg');
        }

        $this->output($Return);
        exit;
    }

    // ====================================================================================================
    // 103. UPLOAD DATA
    // ====================================================================================================

    // database backup
    public function imports()
    {
        $session = $this->session->userdata('username');
        if (empty($session)) {
            redirect('admin/');
        }
        $data['title']       = $this->lang->line('xin_hr_imports') . ' | ' . $this->Core_model->site_title();
        $data['breadcrumbs'] = $this->lang->line('xin_hr_imports');
        $data['desc']        = "PROSES : Import Data";
        $data['icon']        = '<i class="fa fa-flag"></i>';
        $data['path_url']    = 'hris_import';

        $data['all_companies'] = $this->Company_model->get_company();
        $setting               = $this->Core_model->read_setting_info(1);
        $company_info          = $this->Core_model->read_company_setting_info(1);
        $role_resources_ids    = $this->Core_model->user_role_resource();

        if (in_array('0103', $role_resources_ids)) {

            if (!empty($session)) {
                $data['subview'] = $this->load->view("admin/settings/hris_import", $data, TRUE);
                $this->load->view('admin/layout/layout_main', $data); //page load

            } else {

                redirect('admin/');
            }
        } else {
            redirect('admin/dashboard');
        }
    }

    public function import_gram()
    {

        if ($this->input->post('is_ajax') == '3') {

            /* Define return | here result is used to return user data and error for error message */

            $Return = array('result' => '', 'error' => '', 'csrf_hash' => '');

            $Return['csrf_hash'] = $this->security->get_csrf_hash();

            //validate whether uploaded file is a csv file
            $csvMimes = array('text/x-comma-separated-values', 'text/comma-separated-values', 'application/octet-stream', 'application/vnd.ms-excel', 'application/x-csv', 'text/x-csv', 'text/csv', 'application/csv', 'application/excel', 'application/vnd.msexcel', 'text/plain');

            if ($_FILES['file']['name'] === '') {
                $Return['error'] = $this->lang->line('xin_gram_imp_allowed_size');
            } else {

                if (in_array($_FILES['file']['type'], $csvMimes)) {

                    if (is_uploaded_file($_FILES['file']['tmp_name'])) {
                        // check file size
                        if (filesize($_FILES['file']['tmp_name']) > 2000000) {
                            $Return['error'] = $this->lang->line('xin_error_gram_import_size');
                        } else {
                            //open uploaded csv file with read only mode
                            $csvFile = fopen($_FILES['file']['tmp_name'], 'r');

                            //skip first line
                            fgetcsv($csvFile);

                            //parse data from csv file line by line
                            while (($line = fgetcsv($csvFile)) !== FALSE) {


                                $data = array(

                                    'gram_tanggal' => $line[0],
                                    'employee_id'  => $line[1],
                                    'gram_nilai'   => $line[2],
                                    'gram_grading' => $line[3],
                                    'gram_no_job'  => $line[4],

                                    'added_by'     => $this->input->post('user_id'),
                                    'created_at'   => date('Y-m-d H:i:s'),
                                    'status'       => '',
                                );
                                $this->Clients_model->add_gram($data);
                            }
                            //close opened csv file
                            fclose($csvFile);

                            $Return['result'] = $this->lang->line('xin_success_gram_import');
                        }
                    } else {
                        $Return['error'] = $this->lang->line('xin_error_not_gram_import');
                    }
                } else {
                    $Return['error'] = $this->lang->line('xin_error_invalid_file');
                }
            } // file empty

            if ($Return['error'] != '') {
                $this->output($Return);
            }

            $this->output($Return);
            exit;
        }
    }

    // ====================================================================================================
    // lain
    // ====================================================================================================



    // Validate and update info in database
    public function sidebar_setting_info()
    {

        if ($this->input->post('type') == 'other_settings') {

            /* Define return | here result is used to return user data and error for error message */
            $Return = array('result' => '', 'error' => '', 'csrf_hash' => '');
            $Return['csrf_hash'] = $this->security->get_csrf_hash();
            $id = 1;

            $data = array(
                'enable_attendance' => $this->input->post('enable_attendance'),
                'enable_job_application_candidates' => $this->input->post('enable_job'),
                'enable_profile_background' => $this->input->post('enable_profile_background'),
                'enable_email_notification' => $this->input->post('role_email_notification'),
                'notification_close_btn' => $this->input->post('close_btn'),
                'notification_bar' => $this->input->post('notification_bar'),
                'enable_policy_link' => $this->input->post('role_policy_link'),
                'enable_layout' => $this->input->post('enable_layout'),
            );

            $result = $this->Core_model->update_setting_info_record($data, $id);

            if ($result == TRUE) {
                $Return['result'] = $this->lang->line('xin_setting_config_updated');
            } else {
                $Return['error'] = $this->lang->line('xin_error_msg');
            }

            $this->output($Return);
            exit;

            //$this->output->set_output(json_encode($Return));
            // exit;
        }
    }

    // Validate and update info in database
    public function attendance_info()
    {

        if ($this->input->post('type') == 'attendance_info') {

            /* Define return | here result is used to return user data and error for error message */
            $Return = array('result' => '', 'error' => '', 'csrf_hash' => '');
            $Return['csrf_hash'] = $this->security->get_csrf_hash();
            $id = 1;

            $data = array(
                'enable_attendance' => $this->input->post('enable_attendance'),
                'enable_clock_in_btn' => $this->input->post('enable_clock_in_btn')
            );

            $result = $this->Core_model->update_setting_info_record($data, $id);

            if ($result == TRUE) {
                $Return['result'] = $this->lang->line('xin_attendance_config_updated');
            } else {
                $Return['error'] = $this->lang->line('xin_error_msg');
            }
            $this->output($Return);
            exit;

            //$this->output->set_output(json_encode($Return));
            // exit;
        }
    }

    // Validate and update info in database
    public function email_info()
    {

        if ($this->input->post('type') == 'email_info') {

            /* Define return | here result is used to return user data and error for error message */
            $Return = array('result' => '', 'error' => '', 'csrf_hash' => '');
            $Return['csrf_hash'] = $this->security->get_csrf_hash();
            $id = 1;

            $data = array(
                'enable_email_notification' => $this->input->post('enable_email_notification')
            );

            $result = $this->Core_model->update_setting_info_record($data, $id);
            $cdata = array(
                'email_type' => $this->input->post('email_type'),
                'smtp_host' => $this->input->post('smtp_host'),
                'smtp_username' => $this->input->post('smtp_username'),
                'smtp_password' => $this->input->post('smtp_password'),
                'smtp_port' => $this->input->post('smtp_port'),
                'smtp_secure' => $this->input->post('smtp_secure')
            );
            $this->Core_model->update_email_config_record($cdata, 1);

            if ($result == TRUE) {
                $Return['result'] = $this->lang->line('xin_success_email_notify_updated');
            } else {
                $Return['error'] = $this->lang->line('xin_error_msg');
            }
            $this->output($Return);
            exit;

            //$this->output->set_output(json_encode($Return));
            // exit;
        }
    }

    // Validate and update info in database
    public function job_info()
    {

        if ($this->input->post('type') == 'job_info') {

            /* Define return | here result is used to return user data and error for error message */
            $Return = array('result' => '', 'error' => '', 'csrf_hash' => '');
            $Return['csrf_hash'] = $this->security->get_csrf_hash();

            if ($this->input->post('job_application_format') === '') {
                $Return['error'] = $this->lang->line('xin_error_job_app_format');
            }

            if ($Return['error'] != '') {
                $this->output($Return);
            }
            $job_format = str_replace(array('php', '', 'js', '', 'html', ''), '', $this->input->post('job_application_format'));
            $id = 1;

            $data = array(
                'enable_job_application_candidates' => $this->input->post('enable_job'),
                'job_application_format' => $job_format
            );

            $result = $this->Core_model->update_setting_info_record($data, $id);

            if ($result == TRUE) {
                $Return['result'] = $this->lang->line('xin_success_job_config_updated');
            } else {
                $Return['error'] = $this->lang->line('xin_error_msg');
            }

            $this->output($Return);
            exit;

            //$this->output->set_output(json_encode($Return));
            // exit;
        }
    }

    // Validate and update info in database
    public function animation_effect_info()
    {

        if ($this->input->post('type') == 'animation_effect_info') {

            /* Define return | here result is used to return user data and error for error message */
            $Return = array('result' => '', 'error' => '', 'csrf_hash' => '');
            $Return['csrf_hash'] = $this->security->get_csrf_hash();
            $id = 1;

            $data = array(
                'animation_effect' => $this->input->post('animation_effect'),
                'animation_effect_topmenu' => $this->input->post('animation_effect_topmenu'),
                'animation_effect_modal' => $this->input->post('animation_effect_modal')
            );

            $result = $this->Core_model->update_setting_info_record($data, $id);

            if ($result == TRUE) {
                $Return['result'] = $this->lang->line('xin_success_animation_config_updated');
            } else {
                $Return['error'] = $this->lang->line('xin_error_msg');
            }

            $this->output($Return);
            exit;

            //$this->output->set_output(json_encode($Return));
            // exit;
        }
    }

    // Validate and update info in database
    public function notification_position_info()
    {

        if ($this->input->post('type') == 'notification_position_info') {

            /* Define return | here result is used to return user data and error for error message */
            $Return = array('result' => '', 'error' => '', 'csrf_hash' => '');
            $Return['csrf_hash'] = $this->security->get_csrf_hash();

            if ($this->input->post('notification_position') === '') {
                $Return['error'] = $this->lang->line('xin_error_notify_position');
            }

            if ($Return['error'] != '') {
                $this->output($Return);
            }
            $id = 1;

            $data = array(
                'notification_position' => $this->input->post('notification_position'),
                'notification_close_btn' => $this->input->post('notification_close_btn'),
                'notification_bar' => $this->input->post('notification_bar')
            );

            $result = $this->Core_model->update_setting_info_record($data, $id);

            if ($result == TRUE) {
                $Return['result'] = $this->lang->line('xin_success_notify_position_config_updated');
            } else {
                $Return['error'] = $this->lang->line('xin_error_msg');
            }

            $this->output($Return);
            exit;

            //$this->output->set_output(json_encode($Return));
            // exit;
        }
    }



    /*  ALL CONSTANTS */

    // Contract Type > list




    // Language > list
    public function qualification_language_list()
    {

        $data['title'] = $this->Core_model->site_title();
        $session = $this->session->userdata('username');
        if (!empty($session)) {
            $this->load->view("admin/settings/constants", $data);
        } else {
            redirect('admin/');
        }
        // Datatables Variables
        $draw = intval($this->input->get("draw"));
        $start = intval($this->input->get("start"));
        $length = intval($this->input->get("length"));


        $constant = $this->Core_model->get_qualification_language();

        $data = array();

        foreach ($constant->result() as $r) {

            $data[] = array(
                '<span data-toggle="tooltip" data-placement="top" title="' . $this->lang->line('xin_edit') . '"><button type="button" class="btn icon-btn btn-xs btn-default waves-effect waves-light" data-toggle="modal" data-target=".edit_setting_datail" data-field_id="' . $r->language_id . '" data-field_type="qualification_language"><span class="fa fa-pencil"></span></button></span><span data-toggle="tooltip" data-placement="top" title="' . $this->lang->line('xin_delete') . '"><button type="button" class="btn icon-btn btn-xs btn-danger waves-effect waves-light delete" data-toggle="modal" data-target=".delete-modal" data-record-id="' . $r->language_id . '"  data-token_type="qualification_language"><span class="fa fa-trash"></span></button></span>',
                $r->name,
            );
        }

        $output = array(
            "draw" => $draw,
            "recordsTotal" => $constant->num_rows(),
            "recordsFiltered" => $constant->num_rows(),
            "data" => $data
        );

        $this->output->set_output(json_encode($output));
    }

    // Skill > list
    public function qualification_skill_list()
    {

        $data['title'] = $this->Core_model->site_title();
        $session = $this->session->userdata('username');
        if (!empty($session)) {
            $this->load->view("admin/settings/constants", $data);
        } else {
            redirect('admin/');
        }
        // Datatables Variables
        $draw = intval($this->input->get("draw"));
        $start = intval($this->input->get("start"));
        $length = intval($this->input->get("length"));


        $constant = $this->Core_model->get_qualification_skill();

        $data = array();

        foreach ($constant->result() as $r) {

            $data[] = array(
                '<span data-toggle="tooltip" data-placement="top" title="' . $this->lang->line('xin_edit') . '"><button type="button" class="btn icon-btn btn-xs btn-default waves-effect waves-light" data-toggle="modal" data-target=".edit_setting_datail" data-field_id="' . $r->skill_id . '" data-field_type="qualification_skill"><span class="fa fa-pencil"></span></button></span><span data-toggle="tooltip" data-placement="top" title="' . $this->lang->line('xin_delete') . '"><button type="button" class="btn icon-btn btn-xs btn-danger waves-effect waves-light delete" data-toggle="modal" data-target=".delete-modal" data-record-id="' . $r->skill_id . '" data-token_type="qualification_skill"><span class="fa fa-trash"></span></button></span>',
                $r->name,
            );
        }

        $output = array(
            "draw" => $draw,
            "recordsTotal" => $constant->num_rows(),
            "recordsFiltered" => $constant->num_rows(),
            "data" => $data
        );

        $this->output->set_output(json_encode($output));
    }



    // Leave Type > list
    public function leave_type_list()
    {

        $data['title'] = $this->Core_model->site_title();
        $session = $this->session->userdata('username');
        if (!empty($session)) {
            $this->load->view("admin/settings/constants", $data);
        } else {
            redirect('admin/');
        }
        // Datatables Variables
        $draw = intval($this->input->get("draw"));
        $start = intval($this->input->get("start"));
        $length = intval($this->input->get("length"));


        $constant = $this->Core_model->get_leave_type();

        $data = array();

        foreach ($constant->result() as $r) {

            $data[] = array(
                '
            <span data-toggle="tooltip" data-placement="top" title="' . $this->lang->line('xin_edit') . '">
                <button type="button" class="btn icon-btn btn-xs btn-default waves-effect waves-light" data-toggle="modal" data-target=".edit_setting_datail" data-field_id="' . $r->leave_type_id . '" data-field_type="leave_type">
                    <span class="fa fa-pencil"></span>
                </button>
            </span>
            <span data-toggle="tooltip" data-placement="top" title="' . $this->lang->line('xin_delete') . '">
                <button type="button" class="btn icon-btn btn-xs btn-danger waves-effect waves-light delete" data-toggle="modal" data-target=".delete-modal" data-record-id="' . $r->leave_type_id . '" data-token_type="leave_type">
                    <span class="fa fa-trash"></span>
                </button>
            </span>',
                $r->type_name,
                $r->days_per_year
            );
        }

        $output = array(
            "draw" => $draw,
            "recordsTotal" => $constant->num_rows(),
            "recordsFiltered" => $constant->num_rows(),
            "data" => $data
        );

        $this->output->set_output(json_encode($output));
    }

    // Sick Type > list
    public function sick_type_list()
    {

        $data['title'] = $this->Core_model->site_title();
        $session = $this->session->userdata('username');
        if (!empty($session)) {
            $this->load->view("admin/settings/constants", $data);
        } else {
            redirect('admin/');
        }
        // Datatables Variables
        $draw   = intval($this->input->get("draw"));
        $start  = intval($this->input->get("start"));
        $length = intval($this->input->get("length"));


        $constant = $this->Core_model->get_sick_type();

        $data = array();

        foreach ($constant->result() as $r) {

            $data[] = array(
                '
                <span data-toggle="tooltip" data-placement="top" title="' . $this->lang->line('xin_edit') . '">
                    <button type="button" class="btn icon-btn btn-xs btn-default waves-effect waves-light" data-toggle="modal" data-target=".edit_setting_datail" data-field_id="' . $r->sick_type_id . '" data-field_type="sick_type">
                        <span class="fa fa-pencil"></span>
                    </button>
                </span>
                <span data-toggle="tooltip" data-placement="top" title="' . $this->lang->line('xin_delete') . '">
                    <button type="button" class="btn icon-btn btn-xs btn-danger waves-effect waves-light delete" data-toggle="modal" data-target=".delete-modal" data-record-id="' . $r->sick_type_id . '" data-token_type="sick_type">
                        <span class="fa fa-trash"></span>
                    </button>
                </span>',
                $r->type_name,

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

    // Izin Type > list
    public function izin_type_list()
    {

        $data['title'] = $this->Core_model->site_title();
        $session = $this->session->userdata('username');
        if (!empty($session)) {
            $this->load->view("admin/settings/constants", $data);
        } else {
            redirect('admin/');
        }
        // Datatables Variables
        $draw   = intval($this->input->get("draw"));
        $start  = intval($this->input->get("start"));
        $length = intval($this->input->get("length"));


        $constant = $this->Core_model->get_izin_type();

        $data = array();

        foreach ($constant->result() as $r) {

            $data[] = array(
                '
                <span data-toggle="tooltip" data-placement="top" title="' . $this->lang->line('xin_edit') . '">
                    <button type="button" class="btn icon-btn btn-xs btn-default waves-effect waves-light" data-toggle="modal" data-target=".edit_setting_datail" data-field_id="' . $r->izin_type_id . '" data-field_type="izin_type">
                        <span class="fa fa-pencil"></span>
                    </button>
                </span>
                <span data-toggle="tooltip" data-placement="top" title="' . $this->lang->line('xin_delete') . '">
                    <button type="button" class="btn icon-btn btn-xs btn-danger waves-effect waves-light delete" data-toggle="modal" data-target=".delete-modal" data-record-id="' . $r->izin_type_id . '" data-token_type="izin_type">
                        <span class="fa fa-trash"></span>
                    </button>
                </span>',
                $r->type_name,

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

    // Warning Type > list
    public function warning_type_list()
    {

        $data['title'] = $this->Core_model->site_title();
        $session = $this->session->userdata('username');
        if (!empty($session)) {
            $this->load->view("admin/settings/constants", $data);
        } else {
            redirect('admin/');
        }
        // Datatables Variables
        $draw = intval($this->input->get("draw"));
        $start = intval($this->input->get("start"));
        $length = intval($this->input->get("length"));


        $constant = $this->Core_model->get_warning_type();

        $data = array();

        foreach ($constant->result() as $r) {

            $data[] = array(
                '<span data-toggle="tooltip" data-placement="top" title="' . $this->lang->line('xin_edit') . '"><button type="button" class="btn icon-btn btn-xs btn-default waves-effect waves-light" data-toggle="modal" data-target=".edit_setting_datail" data-field_id="' . $r->warning_type_id . '" data-field_type="warning_type"><span class="fa fa-pencil"></span></button></span><span data-toggle="tooltip" data-placement="top" title="' . $this->lang->line('xin_delete') . '"><button type="button" class="btn icon-btn btn-xs btn-danger waves-effect waves-light delete" data-toggle="modal" data-target=".delete-modal" data-record-id="' . $r->warning_type_id . '" data-token_type="warning_type"><span class="fa fa-trash"></span></button></span>',
                $r->type
            );
        }

        $output = array(
            "draw" => $draw,
            "recordsTotal" => $constant->num_rows(),
            "recordsFiltered" => $constant->num_rows(),
            "data" => $data
        );

        $this->output->set_output(json_encode($output));
    }



    // Termination Type > list
    public function termination_type_list()
    {

        $data['title'] = $this->Core_model->site_title();
        $session = $this->session->userdata('username');
        if (!empty($session)) {
            $this->load->view("admin/settings/constants", $data);
        } else {
            redirect('admin/');
        }
        // Datatables Variables
        $draw = intval($this->input->get("draw"));
        $start = intval($this->input->get("start"));
        $length = intval($this->input->get("length"));


        $constant = $this->Core_model->get_termination_type();

        $data = array();

        foreach ($constant->result() as $r) {

            $data[] = array(
                '<span data-toggle="tooltip" data-placement="top" title="' . $this->lang->line('xin_edit') . '"><button type="button" class="btn icon-btn btn-xs btn-default waves-effect waves-light" data-toggle="modal" data-target=".edit_setting_datail" data-field_id="' . $r->termination_type_id . '" data-field_type="termination_type"><span class="fa fa-pencil"></span></button></span><span data-toggle="tooltip" data-placement="top" title="' . $this->lang->line('xin_delete') . '"><button type="button" class="btn icon-btn btn-xs btn-danger waves-effect waves-light delete" data-toggle="modal" data-target=".delete-modal" data-record-id="' . $r->termination_type_id . '" data-token_type="termination_type"><span class="fa fa-trash"></span></button></span>',
                $r->type
            );
        }

        $output = array(
            "draw" => $draw,
            "recordsTotal" => $constant->num_rows(),
            "recordsFiltered" => $constant->num_rows(),
            "data" => $data
        );

        $this->output->set_output(json_encode($output));
    }


    // Job Type > list
    public function job_type_list()
    {

        $data['title'] = $this->Core_model->site_title();
        $session = $this->session->userdata('username');
        if (!empty($session)) {
            $this->load->view("admin/settings/constants", $data);
        } else {
            redirect('admin/');
        }
        // Datatables Variables
        $draw = intval($this->input->get("draw"));
        $start = intval($this->input->get("start"));
        $length = intval($this->input->get("length"));


        $constant = $this->Core_model->get_job_type();

        $data = array();

        foreach ($constant->result() as $r) {

            $data[] = array(
                '<span data-toggle="tooltip" data-placement="top" title="' . $this->lang->line('xin_edit') . '"><button type="button" class="btn icon-btn btn-xs btn-default waves-effect waves-light" data-toggle="modal" data-target=".edit_setting_datail" data-field_id="' . $r->job_type_id . '" data-field_type="job_type"><span class="fa fa-pencil"></span></button></span><span data-toggle="tooltip" data-placement="top" title="' . $this->lang->line('xin_delete') . '"><button type="button" class="btn icon-btn btn-xs btn-danger waves-effect waves-light delete" data-toggle="modal" data-target=".delete-modal" data-record-id="' . $r->job_type_id . '" data-token_type="job_type"><span class="fa fa-trash"></span></button></span>',
                $r->type
            );
        }

        $output = array(
            "draw" => $draw,
            "recordsTotal" => $constant->num_rows(),
            "recordsFiltered" => $constant->num_rows(),
            "data" => $data
        );

        $this->output->set_output(json_encode($output));
    }

    // Job Categories > list
    public function job_category_list()
    {

        $data['title'] = $this->Core_model->site_title();
        $session = $this->session->userdata('username');
        if (!empty($session)) {
            $this->load->view("admin/settings/constants", $data);
        } else {
            redirect('admin/');
        }
        // Datatables Variables
        $draw = intval($this->input->get("draw"));
        $start = intval($this->input->get("start"));
        $length = intval($this->input->get("length"));


        $constant = $this->Core_model->get_job_categories();

        $data = array();

        foreach ($constant->result() as $r) {

            $data[] = array(
                '<span data-toggle="tooltip" data-placement="top" title="' . $this->lang->line('xin_edit') . '"><button type="button" class="btn icon-btn btn-xs btn-default waves-effect waves-light" data-toggle="modal" data-target=".edit_setting_datail" data-field_id="' . $r->category_id . '" data-field_type="job_category"><span class="fa fa-pencil"></span></button></span><span data-toggle="tooltip" data-placement="top" title="' . $this->lang->line('xin_delete') . '"><button type="button" class="btn icon-btn btn-xs btn-danger waves-effect waves-light delete" data-toggle="modal" data-target=".delete-modal" data-record-id="' . $r->category_id . '" data-token_type="job_category"><span class="fa fa-trash"></span></button></span>',
                $r->category_name
            );
        }

        $output = array(
            "draw" => $draw,
            "recordsTotal" => $constant->num_rows(),
            "recordsFiltered" => $constant->num_rows(),
            "data" => $data
        );

        $this->output->set_output(json_encode($output));
    }

    // Exit Type > list
    public function exit_type_list()
    {

        $data['title'] = $this->Core_model->site_title();
        $session = $this->session->userdata('username');
        if (!empty($session)) {
            $this->load->view("admin/settings/constants", $data);
        } else {
            redirect('admin/');
        }
        // Datatables Variables
        $draw = intval($this->input->get("draw"));
        $start = intval($this->input->get("start"));
        $length = intval($this->input->get("length"));


        $constant = $this->Core_model->get_exit_type();

        $data = array();

        foreach ($constant->result() as $r) {

            $data[] = array(
                '<span data-toggle="tooltip" data-placement="top" title="' . $this->lang->line('xin_edit') . '"><button type="button" class="btn icon-btn btn-xs btn-default waves-effect waves-light" data-toggle="modal" data-target=".edit_setting_datail" data-field_id="' . $r->exit_type_id . '" data-field_type="exit_type"><span class="fa fa-pencil"></span></button></span><span data-toggle="tooltip" data-placement="top" title="' . $this->lang->line('xin_delete') . '"><button type="button" class="btn icon-btn btn-xs btn-danger waves-effect waves-light delete" data-toggle="modal" data-target=".delete-modal" data-record-id="' . $r->exit_type_id . '" data-token_type="exit_type"><span class="fa fa-trash"></span></button></span>',
                $r->type
            );
        }

        $output = array(
            "draw" => $draw,
            "recordsTotal" => $constant->num_rows(),
            "recordsFiltered" => $constant->num_rows(),
            "data" => $data
        );

        $this->output->set_output(json_encode($output));
    }

    public function exit_type_reason_list()
    {

        $data['title'] = $this->Core_model->site_title();
        $session = $this->session->userdata('username');
        if (!empty($session)) {
            $this->load->view("admin/settings/constants", $data);
        } else {
            redirect('admin/');
        }
        // Datatables Variables
        $draw = intval($this->input->get("draw"));
        $start = intval($this->input->get("start"));
        $length = intval($this->input->get("length"));


        $constant = $this->Core_model->get_exit_type_reason();

        $data = array();

        foreach ($constant->result() as $r) {

            $data[] = array(
                '<span data-toggle="tooltip" data-placement="top" title="' . $this->lang->line('xin_edit') . '">
                                <button type="button" class="btn icon-btn btn-xs btn-default waves-effect waves-light" data-toggle="modal" data-target=".edit_setting_datail" data-field_id="' . $r->exit_type_id . '" data-field_type="exit_type_reason">
                                    <span class="fa fa-pencil"></span>
                                </button>
                         </span>

                        <span data-toggle="tooltip" data-placement="top" title="' . $this->lang->line('xin_delete') . '">
                                <button type="button" class="btn icon-btn btn-xs btn-danger waves-effect waves-light delete" data-toggle="modal" data-target=".delete-modal" data-record-id="' . $r->exit_type_id . '" data-token_type="exit_type_reason">
                                    <span class="fa fa-trash"></span>
                                </button>
                            </span>',
                $r->type
            );
        }

        $output = array(
            "draw" => $draw,
            "recordsTotal" => $constant->num_rows(),
            "recordsFiltered" => $constant->num_rows(),
            "data" => $data
        );

        $this->output->set_output(json_encode($output));
    }

    // Travel Arrangement Type > list
    public function travel_arr_type_list()
    {

        $data['title'] = $this->Core_model->site_title();
        $session = $this->session->userdata('username');
        if (!empty($session)) {
            $this->load->view("admin/settings/constants", $data);
        } else {
            redirect('admin/');
        }
        // Datatables Variables
        $draw = intval($this->input->get("draw"));
        $start = intval($this->input->get("start"));
        $length = intval($this->input->get("length"));


        $constant = $this->Core_model->get_travel_type();

        $data = array();

        foreach ($constant->result() as $r) {

            $data[] = array(
                '<span data-toggle="tooltip" data-placement="top" title="' . $this->lang->line('xin_edit') . '"><button type="button" class="btn icon-btn btn-xs btn-default waves-effect waves-light" data-toggle="modal" data-target=".edit_setting_datail" data-field_id="' . $r->arrangement_type_id . '" data-field_type="travel_arr_type"><span class="fa fa-pencil"></span></button></span><span data-toggle="tooltip" data-placement="top" title="' . $this->lang->line('xin_delete') . '"><button type="button" class="btn icon-btn btn-xs btn-danger waves-effect waves-light delete" data-toggle="modal" data-target=".delete-modal" data-record-id="' . $r->arrangement_type_id . '" data-token_type="travel_arr_type"><span class="fa fa-trash"></span></button></span>',
                $r->type
            );
        }

        $output = array(
            "draw" => $draw,
            "recordsTotal" => $constant->num_rows(),
            "recordsFiltered" => $constant->num_rows(),
            "data" => $data
        );

        $this->output->set_output(json_encode($output));
    }



    // Payment Method > list
    public function payment_method_list()
    {

        $data['title'] = $this->Core_model->site_title();
        $session = $this->session->userdata('username');
        if (!empty($session)) {
            $this->load->view("admin/settings/constants", $data);
        } else {
            redirect('admin/');
        }
        // Datatables Variables
        $draw = intval($this->input->get("draw"));
        $start = intval($this->input->get("start"));
        $length = intval($this->input->get("length"));


        $constant = $this->Core_model->get_payment_method();

        $data = array();

        foreach ($constant->result() as $r) {

            $data[] = array(
                '<span data-toggle="tooltip" data-placement="top" title="' . $this->lang->line('xin_edit') . '"><button type="button" class="btn icon-btn btn-xs btn-default waves-effect waves-light" data-toggle="modal" data-target=".edit_setting_datail" data-field_id="' . $r->payment_method_id . '" data-field_type="payment_method"><span class="fa fa-pencil"></span></button></span><span data-toggle="tooltip" data-placement="top" title="' . $this->lang->line('xin_delete') . '"><button type="button" class="btn icon-btn btn-xs btn-danger waves-effect waves-light delete" data-toggle="modal" data-target=".delete-modal" data-record-id="' . $r->payment_method_id . '" data-token_type="payment_method"><span class="fa fa-trash"></span></button></span>',
                $r->method_name,
                $r->payment_percentage . '%',
                $r->account_number
            );
        }

        $output = array(
            "draw" => $draw,
            "recordsTotal" => $constant->num_rows(),
            "recordsFiltered" => $constant->num_rows(),
            "data" => $data
        );

        $this->output->set_output(json_encode($output));
    }

    // Currency type > list
    public function currency_type_list()
    {

        $data['title'] = $this->Core_model->site_title();
        $session = $this->session->userdata('username');
        if (!empty($session)) {
            $this->load->view("admin/settings/constants", $data);
        } else {
            redirect('admin/');
        }
        // Datatables Variables
        $draw = intval($this->input->get("draw"));
        $start = intval($this->input->get("start"));
        $length = intval($this->input->get("length"));


        $constant = $this->Core_model->get_currency_types();

        $data = array();

        foreach ($constant->result() as $r) {

            $data[] = array(
                '<span data-toggle="tooltip" data-placement="top" title="' . $this->lang->line('xin_edit') . '"><button type="button" class="btn icon-btn btn-xs btn-default waves-effect waves-light" data-toggle="modal" data-target=".edit_setting_datail" data-field_id="' . $r->currency_id . '" data-field_type="currency_type"><span class="fa fa-pencil"></span></button></span><span data-toggle="tooltip" data-placement="top" title="' . $this->lang->line('xin_delete') . '"><button type="button" class="btn icon-btn btn-xs btn-danger waves-effect waves-light delete" data-toggle="modal" data-target=".delete-modal" data-record-id="' . $r->currency_id . '" data-token_type="currency_type"><span class="fa fa-trash"></span></button></span>',
                $r->name,
                $r->code,
                $r->symbol
            );
        }

        $output = array(
            "draw" => $draw,
            "recordsTotal" => $constant->num_rows(),
            "recordsFiltered" => $constant->num_rows(),
            "data" => $data
        );

        $this->output->set_output(json_encode($output));
    }

    // Company type > list
    public function company_type_list()
    {

        $data['title'] = $this->Core_model->site_title();
        $session = $this->session->userdata('username');
        if (!empty($session)) {
            $this->load->view("admin/settings/constants", $data);
        } else {
            redirect('admin/');
        }
        // Datatables Variables
        $draw = intval($this->input->get("draw"));
        $start = intval($this->input->get("start"));
        $length = intval($this->input->get("length"));


        $constant = $this->Core_model->get_company_type();

        $data = array();

        foreach ($constant->result() as $r) {

            $data[] = array(
                '<span data-toggle="tooltip" data-placement="top" title="' . $this->lang->line('xin_edit') . '"><button type="button" class="btn icon-btn btn-xs btn-default waves-effect waves-light" data-toggle="modal" data-target=".edit_setting_datail" data-field_id="' . $r->type_id . '" data-field_type="company_type"><span class="fa fa-pencil"></span></button></span><span data-toggle="tooltip" data-placement="top" title="' . $this->lang->line('xin_delete') . '"><button type="button" class="btn icon-btn btn-xs btn-danger waves-effect waves-light delete" data-toggle="modal" data-target=".delete-modal" data-record-id="' . $r->type_id . '" data-token_type="company_type"><span class="fa fa-trash"></span></button></span>',
                $r->name
            );
        }

        $output = array(
            "draw" => $draw,
            "recordsTotal" => $constant->num_rows(),
            "recordsFiltered" => $constant->num_rows(),
            "data" => $data
        );

        $this->output->set_output(json_encode($output));
    }

    /*  Add constant data */


    // Validate and add info in database
    public function document_type_info()
    {

        if ($this->input->post('type') == 'document_type_info') {
            /* Define return | here result is used to return user data and error for error message */
            $Return = array('result' => '', 'error' => '', 'csrf_hash' => '');
            $Return['csrf_hash'] = $this->security->get_csrf_hash();

            /* Server side PHP input validation */
            if ($this->input->post('document_type') === '') {
                $Return['error'] = $this->lang->line('xin_employee_error_d_type');
            }

            if ($Return['error'] != '') {
                $this->output($Return);
                //$this->output->set_output(json_encode($Return));
            }

            $data = array(
                'document_type' => $this->input->post('document_type'),
                'created_at' => date('d-m-Y h:i:s')
            );
            $result = $this->Core_model->add_document_type($data);
            if ($result == TRUE) {
                $Return['result'] = $this->lang->line('xin_success_document_type_added');
            } else {
                $Return['error'] = $this->lang->line('xin_error_msg');
            }
            $this->output($Return);
            exit;
            //$this->output->set_output(json_encode($Return));
            // exit;
        }
    }

    // Validate and add info in database
    public function edu_level_info()
    {

        if ($this->input->post('type') == 'edu_level_info') {
            /* Define return | here result is used to return user data and error for error message */
            $Return = array('result' => '', 'error' => '', 'csrf_hash' => '');
            $Return['csrf_hash'] = $this->security->get_csrf_hash();

            /* Server side PHP input validation */
            if ($this->input->post('name') === '') {
                $Return['error'] = $this->lang->line('xin_error_education_level');
            }

            if ($Return['error'] != '') {
                $this->output($Return);
                //$this->output->set_output(json_encode($Return));
            }

            $data = array(
                'name' => $this->input->post('name'),
                'created_at' => date('d-m-Y h:i:s')
            );

            $result = $this->Core_model->add_edu_level($data);
            if ($result == TRUE) {
                $Return['result'] = $this->lang->line('xin_success_education_level_added');
            } else {
                $Return['error'] = $this->lang->line('xin_error_msg');
            }
            $this->output($Return);
            exit;

            //$this->output->set_output(json_encode($Return));
            // exit;
        }
    }

    // Validate and add info in database
    public function edu_language_info()
    {

        if ($this->input->post('type') == 'edu_language_info') {
            /* Define return | here result is used to return user data and error for error message */
            $Return = array('result' => '', 'error' => '', 'csrf_hash' => '');
            $Return['csrf_hash'] = $this->security->get_csrf_hash();

            /* Server side PHP input validation */
            if ($this->input->post('name') === '') {
                $Return['error'] = $this->lang->line('xin_error_education_language');
            }

            if ($Return['error'] != '') {
                $this->output($Return);
                //$this->output->set_output(json_encode($Return));
            }

            $data = array(
                'name' => $this->input->post('name'),
                'created_at' => date('d-m-Y h:i:s')
            );

            $result = $this->Core_model->add_edu_language($data);
            if ($result == TRUE) {
                $Return['result'] = $this->lang->line('xin_success_education_language_added');
            } else {
                $Return['error'] = $this->lang->line('xin_error_msg');
            }
            $this->output($Return);
            exit;
            //$this->output->set_output(json_encode($Return));
            // exit;
        }
    }

    // Validate and add info in database
    public function edu_skill_info()
    {

        if ($this->input->post('type') == 'edu_skill_info') {
            /* Define return | here result is used to return user data and error for error message */
            $Return = array('result' => '', 'error' => '', 'csrf_hash' => '');
            $Return['csrf_hash'] = $this->security->get_csrf_hash();

            /* Server side PHP input validation */
            if ($this->input->post('name') === '') {
                $Return['error'] = $this->lang->line('xin_error_education_skill');
            }

            if ($Return['error'] != '') {
                $this->output($Return);
                //$this->output->set_output(json_encode($Return));
            }

            $data = array(
                'name' => $this->input->post('name'),
                'created_at' => date('d-m-Y h:i:s')
            );

            $result = $this->Core_model->add_edu_skill($data);
            if ($result == TRUE) {
                $Return['result'] = $this->lang->line('xin_success_education_skill_added');
            } else {
                $Return['error'] = $this->lang->line('xin_error_msg');
            }

            $this->output($Return);
            exit;

            //$this->output->set_output(json_encode($Return));
            // exit;
        }
    }

    // Validate and add info in database
    public function payment_method_info()
    {

        if ($this->input->post('type') == 'payment_method_info') {
            /* Define return | here result is used to return user data and error for error message */
            $Return = array('result' => '', 'error' => '', 'csrf_hash' => '');
            $Return['csrf_hash'] = $this->security->get_csrf_hash();

            /* Server side PHP input validation */
            if ($this->input->post('payment_method') === '') {
                $Return['error'] = $this->lang->line('xin_error_payment_method');
            }

            if ($Return['error'] != '') {
                $this->output($Return);

                //$this->output->set_output(json_encode($Return));
            }

            $data = array(
                'method_name' => $this->input->post('payment_method'),
                'payment_percentage' => $this->input->post('payment_percentage'),
                'account_number' => $this->input->post('account_number'),
                'created_at' => date('d-m-Y h:i:s')
            );

            $result = $this->Core_model->add_payment_method($data);
            if ($result == TRUE) {
                $Return['result'] = $this->lang->line('xin_success_payment_method_added');
            } else {
                $Return['error'] = $this->lang->line('xin_error_msg');
            }
            $this->output($Return);
            exit;
            //$this->output->set_output(json_encode($Return));
            // exit;
        }
    }


    // Validate and add info in database
    public function leave_type_info()
    {

        if ($this->input->post('type') == 'leave_type_info') {

            /* Define return | here result is used to return user data and error for error message */
            $Return = array('result' => '', 'error' => '', 'csrf_hash' => '');
            $Return['csrf_hash'] = $this->security->get_csrf_hash();

            /* Server side PHP input validation */
            if ($this->input->post('leave_type') === '') {
                $Return['error'] = $this->lang->line('xin_error_leave_type_field');
            } else if ($this->input->post('days_per_year') === '') {
                $Return['error'] = $this->lang->line('xin_error_days_per_year');
            }

            if ($Return['error'] != '') {
                //$this->output->set_output(json_encode($Return));
                $this->output($Return);
            }

            $data = array(
                'type_name' => $this->input->post('leave_type'),
                'days_per_year' => $this->input->post('days_per_year'),
                'created_at' => date('d-m-Y h:i:s')
            );

            $result = $this->Core_model->add_leave_type($data);
            if ($result == TRUE) {
                $Return['result'] = $this->lang->line('xin_success_leave_type_added');
            } else {
                $Return['error'] = $this->lang->line('xin_error_msg');
            }
            //$this->output->set_output(json_encode($Return));
            $this->output($Return);
            exit;
            // exit;
        }
    }

    // Validate and add info in database
    public function sick_type_info()
    {

        if ($this->input->post('type') == 'sick_type_info') {

            /* Define return | here result is used to return user data and error for error message */
            $Return = array('result' => '', 'error' => '', 'csrf_hash' => '');
            $Return['csrf_hash'] = $this->security->get_csrf_hash();

            /* Server side PHP input validation */
            if ($this->input->post('sick_type') === '') {
                $Return['error'] = $this->lang->line('xin_error_sick_type_field');
            }

            if ($Return['error'] != '') {
                //$this->output->set_output(json_encode($Return));
                $this->output($Return);
            }

            $data = array(
                'type_name' => $this->input->post('sick_type'),
                'created_at' => date('d-m-Y h:i:s')
            );

            $result = $this->Core_model->add_sick_type($data);
            if ($result == TRUE) {
                $Return['result'] = $this->lang->line('xin_success_sick_type_added');
            } else {
                $Return['error'] = $this->lang->line('xin_error_msg');
            }

            //$this->output->set_output(json_encode($Return));

            $this->output($Return);
            exit;
            // exit;
        }
    }

    // Validate and add info in database
    public function izin_type_info()
    {

        if ($this->input->post('type') == 'izin_type_info') {

            /* Define return | here result is used to return user data and error for error message */
            $Return = array('result' => '', 'error' => '', 'csrf_hash' => '');
            $Return['csrf_hash'] = $this->security->get_csrf_hash();

            /* Server side PHP input validation */
            if ($this->input->post('izin_type') === '') {
                $Return['error'] = $this->lang->line('xin_error_izin_type_field');
            }

            if ($Return['error'] != '') {
                //$this->output->set_output(json_encode($Return));
                $this->output($Return);
            }

            $data = array(
                'type_name' => $this->input->post('izin_type'),
                'created_at' => date('d-m-Y h:i:s')
            );

            $result = $this->Core_model->add_izin_type($data);
            if ($result == TRUE) {
                $Return['result'] = $this->lang->line('xin_success_izin_type_added');
            } else {
                $Return['error'] = $this->lang->line('xin_error_msg');
            }

            //$this->output->set_output(json_encode($Return));

            $this->output($Return);
            exit;
            // exit;
        }
    }

    // Validate and add info in database
    public function warning_type_info()
    {

        if ($this->input->post('type') == 'warning_type_info') {
            /* Define return | here result is used to return user data and error for error message */
            $Return = array('result' => '', 'error' => '', 'csrf_hash' => '');
            $Return['csrf_hash'] = $this->security->get_csrf_hash();

            /* Server side PHP input validation */
            if ($this->input->post('warning_type') === '') {
                $Return['error'] = $this->lang->line('xin_employee_error_warning_type');
            }

            if ($Return['error'] != '') {
                //$this->output->set_output(json_encode($Return));
                $this->output($Return);
                exit;
            }

            $data = array(
                'type' => $this->input->post('warning_type'),
                'created_at' => date('d-m-Y h:i:s')
            );

            $result = $this->Core_model->add_warning_type($data);
            if ($result == TRUE) {
                $Return['result'] = $this->lang->line('xin_success_warning_type_added');
            } else {
                $Return['error'] = $this->lang->line('xin_error_msg');
            }
            //$this->output->set_output(json_encode($Return));

            $this->output($Return);
            exit;
            // exit;
        }
    }

    // Validate and add info in database
    public function termination_type_info()
    {

        if ($this->input->post('type') == 'termination_type_info') {
            /* Define return | here result is used to return user data and error for error message */
            $Return = array('result' => '', 'error' => '', 'csrf_hash' => '');
            $Return['csrf_hash'] = $this->security->get_csrf_hash();

            /* Server side PHP input validation */
            if ($this->input->post('termination_type') === '') {
                $Return['error'] = $this->lang->line('xin_error_termination_type');
            }

            if ($Return['error'] != '') {
                //$this->output->set_output(json_encode($Return));
                $this->output($Return);
            }

            $data = array(
                'type' => $this->input->post('termination_type'),
                'created_at' => date('d-m-Y h:i:s')
            );

            $result = $this->Core_model->add_termination_type($data);
            if ($result == TRUE) {
                $Return['result'] = $this->lang->line('xin_success_termination_type_added');
            } else {
                $Return['error'] = $this->lang->line('xin_error_msg');
            }
            //$this->output->set_output(json_encode($Return));
            $this->output($Return);
            exit;
            // exit;
        }
    }

    // Validate and add info in database
    public function expense_type_info()
    {

        if ($this->input->post('type') == 'expense_type_info') {
            /* Define return | here result is used to return user data and error for error message */
            $Return = array('result' => '', 'error' => '', 'csrf_hash' => '');
            $Return['csrf_hash'] = $this->security->get_csrf_hash();

            /* Server side PHP input validation */
            if ($this->input->post('company') === '') {
                $Return['error'] = $this->lang->line('error_company_field');
            } else if ($this->input->post('expense_type') === '') {
                $Return['error'] = $this->lang->line('xin_error_expense_type');
            }

            if ($Return['error'] != '') {
                //$this->output->set_output(json_encode($Return));
                $this->output($Return);
                exit;
            }

            $data = array(
                'name' => $this->input->post('expense_type'),
                'company_id' => $this->input->post('company'),
                'created_at' => date('d-m-Y h:i:s')
            );

            $result = $this->Core_model->add_expense_type($data);
            if ($result == TRUE) {
                $Return['result'] = $this->lang->line('xin_success_expense_type_added');
            } else {
                $Return['error'] = $this->lang->line('xin_error_msg');
            }
            //$this->output->set_output(json_encode($Return));
            $this->output($Return);
            exit;
            // exit;
        }
    }

    // Validate and add info in database
    public function job_type_info()
    {

        if ($this->input->post('type') == 'job_type_info') {
            /* Define return | here result is used to return user data and error for error message */
            $Return = array('result' => '', 'error' => '', 'csrf_hash' => '');
            $Return['csrf_hash'] = $this->security->get_csrf_hash();

            /* Server side PHP input validation */
            if ($this->input->post('job_type') === '') {
                $Return['error'] = $this->lang->line('xin_error_jobpost_type');
            }

            if ($Return['error'] != '') {
                //$this->output->set_output(json_encode($Return));
                $this->output($Return);
                exit;
            }
            $jurl = random_string('alnum', 40);
            $data = array(
                'type' => $this->input->post('job_type'),
                'type_url' => $jurl,
                'company_id' => 1,
                'created_at' => date('d-m-Y h:i:s')
            );

            $result = $this->Core_model->add_job_type($data);
            if ($result == TRUE) {
                $Return['result'] = $this->lang->line('xin_success_job_type_added');
            } else {
                $Return['error'] = $this->lang->line('xin_error_msg');
            }
            //$this->output->set_output(json_encode($Return));

            $this->output($Return);
            exit;
            // exit;
        }
    }
    // Validate and add info in database
    public function job_category_info()
    {

        if ($this->input->post('type') == 'job_category_info') {
            /* Define return | here result is used to return user data and error for error message */
            $Return = array('result' => '', 'error' => '', 'csrf_hash' => '');
            $Return['csrf_hash'] = $this->security->get_csrf_hash();

            /* Server side PHP input validation */
            if ($this->input->post('job_category') === '') {
                $Return['error'] = $this->lang->line('xin_error_job_category');
            }

            if ($Return['error'] != '') {
                //$this->output->set_output(json_encode($Return));
                $this->output($Return);
                exit;
            }
            $jurl = random_string('alnum', 40);
            $data = array(
                'category_name' => $this->input->post('job_category'),
                'category_url' => $jurl,
                'created_at' => date('d-m-Y h:i:s')
            );

            $result = $this->Core_model->add_job_category($data);
            if ($result == TRUE) {
                $Return['result'] = $this->lang->line('xin_success_job_category_added');
            } else {
                $Return['error'] = $this->lang->line('xin_error_msg');
            }
            //$this->output->set_output(json_encode($Return));

            $this->output($Return);
            exit;
            // exit;
        }
    }

    // Validate and add info in database
    public function exit_type_info()
    {

        if ($this->input->post('type') == 'exit_type_info') {
            /* Define return | here result is used to return user data and error for error message */
            $Return = array('result' => '', 'error' => '', 'csrf_hash' => '');
            $Return['csrf_hash'] = $this->security->get_csrf_hash();

            /* Server side PHP input validation */
            if ($this->input->post('exit_type') === '') {
                $Return['error'] = $this->lang->line('xin_error_exit_type');
            }

            if ($Return['error'] != '') {
                //$this->output->set_output(json_encode($Return));
                $this->output($Return);
                exit;
            }

            $data = array(
                'type' => $this->input->post('exit_type'),
                'created_at' => date('d-m-Y h:i:s')
            );

            $result = $this->Core_model->add_exit_type($data);
            if ($result == TRUE) {
                $Return['result'] = 'Jenis Resign Berhasil Ditambahkan';
            } else {
                $Return['error'] = $this->lang->line('xin_error_msg');
            }
            //$this->output->set_output(json_encode($Return));
            $this->output($Return);
            exit;
            // exit;
        }
    }

    public function exit_type_reason_info()
    {

        if ($this->input->post('type') == 'exit_type_reason_info') {
            /* Define return | here result is used to return user data and error for error message */
            $Return = array('result' => '', 'error' => '', 'csrf_hash' => '');
            $Return['csrf_hash'] = $this->security->get_csrf_hash();

            /* Server side PHP input validation */
            if ($this->input->post('exit_type_reason') === '') {
                $Return['error'] = $this->lang->line('xin_error_exit_type_reason');
            }

            if ($Return['error'] != '') {
                //$this->output->set_output(json_encode($Return));
                $this->output($Return);
                exit;
            }

            $data = array(
                'type' => $this->input->post('exit_type_reason'),
                'created_at' => date('d-m-Y h:i:s')
            );

            $result = $this->Core_model->add_exit_type_reason($data);
            if ($result == TRUE) {
                $Return['result'] = 'Jenis Alasan Resign Berhasil Ditambahkan';
            } else {
                $Return['error'] = $this->lang->line('xin_error_msg');
            }
            //$this->output->set_output(json_encode($Return));
            $this->output($Return);
            exit;
            // exit;
        }
    }

    // Validate and add info in database
    public function travel_arr_type_info()
    {

        if ($this->input->post('type') == 'travel_arr_type_info') {
            /* Define return | here result is used to return user data and error for error message */
            $Return = array('result' => '', 'error' => '', 'csrf_hash' => '');
            $Return['csrf_hash'] = $this->security->get_csrf_hash();

            /* Server side PHP input validation */
            if ($this->input->post('travel_arr_type') === '') {
                $Return['error'] = $this->lang->line('xin_error_travel_arrangment_type');
            }

            if ($Return['error'] != '') {
                //$this->output->set_output(json_encode($Return));
                $this->output($Return);
                exit;
            }

            $data = array(
                'type' => $this->input->post('travel_arr_type'),
                'created_at' => date('d-m-Y h:i:s')
            );

            $result = $this->Core_model->add_travel_arr_type($data);
            if ($result == TRUE) {
                $Return['result'] = $this->lang->line('xin_success_travel_arrangment_type_added');
            } else {
                $Return['error'] = $this->lang->line('xin_error_msg');
            }
            $this->output($Return);
            exit;
            // exit;
        }
    }

    public function transport_arr_type_info()
    {

        if ($this->input->post('type') == 'transport_arr_type_info') {
            /* Define return | here result is used to return user data and error for error message */
            $Return = array('result' => '', 'error' => '', 'csrf_hash' => '');
            $Return['csrf_hash'] = $this->security->get_csrf_hash();

            /* Server side PHP input validation */
            if ($this->input->post('transport_arr_type') === '') {
                $Return['error'] = $this->lang->line('xin_error_travel_arrangment_type');
            }

            if ($Return['error'] != '') {
                //$this->output->set_output(json_encode($Return));
                $this->output($Return);
                exit;
            }

            $data = array(
                'type' => $this->input->post('transport_arr_type'),
                'created_at' => date('d-m-Y h:i:s')
            );

            $result = $this->Core_model->add_transport_arr_type($data);
            if ($result == TRUE) {
                $Return['result'] = $this->lang->line('xin_success_transport_arrangment_type_added');
            } else {
                $Return['error'] = $this->lang->line('xin_error_msg');
            }
            $this->output($Return);
            exit;
            // exit;
        }
    }

    // Validate and add info in database
    public function company_type_info()
    {

        if ($this->input->post('type') == 'company_type_info') {
            /* Define return | here result is used to return user data and error for error message */
            $Return = array('result' => '', 'error' => '', 'csrf_hash' => '');
            $Return['csrf_hash'] = $this->security->get_csrf_hash();

            /* Server side PHP input validation */
            if ($this->input->post('company_type') === '') {
                $Return['error'] = $this->lang->line('xin_error_ctype_field');
            }

            if ($Return['error'] != '') {
                //$this->output->set_output(json_encode($Return));
            }

            $data = array(
                'name' => $this->input->post('company_type'),
                'created_at' => date('d-m-Y h:i:s')
            );

            $result = $this->Core_model->add_company_type($data);
            if ($result == TRUE) {
                $Return['result'] = $this->lang->line('xin_company_type_added');
            } else {
                $Return['error'] = $this->lang->line('xin_error_msg');
            }
            //$this->output->set_output(json_encode($Return));
            // exit;
        }
    }


    // Validate and add info in database
    public function security_level_info()
    {

        if ($this->input->post('type') == 'security_level_info') {
            /* Define return | here result is used to return user data and error for error message */
            $Return = array('result' => '', 'error' => '', 'csrf_hash' => '');
            $Return['csrf_hash'] = $this->security->get_csrf_hash();

            /* Server side PHP input validation */
            if ($this->input->post('security_level') === '') {
                $Return['error'] = $this->lang->line('xin_error_security_level_field');
            }

            if ($Return['error'] != '') {
                //$this->output->set_output(json_encode($Return));
            }

            $data = array(
                'name' => $this->input->post('security_level'),
                'created_at' => date('d-m-Y h:i:s')
            );

            $result = $this->Core_model->add_security_level($data);
            if ($result == TRUE) {
                $Return['result'] = $this->lang->line('xin_security_level_added');
            } else {
                $Return['error'] = $this->lang->line('xin_error_msg');
            }
            //$this->output->set_output(json_encode($Return));
            // exit;
        }
    }
    // Validate and add info in database
    public function income_type_info()
    {

        if ($this->input->post('type') == 'income_type_info') {
            /* Define return | here result is used to return user data and error for error message */
            $Return = array('result' => '', 'error' => '', 'csrf_hash' => '');
            $Return['csrf_hash'] = $this->security->get_csrf_hash();

            /* Server side PHP input validation */
            if ($this->input->post('income_type') === '') {
                $Return['error'] = $this->lang->line('xin_income_type_error_field');
            }

            if ($Return['error'] != '') {
                //$this->output->set_output(json_encode($Return));
            }

            $data = array(
                'name' => $this->input->post('income_type'),
                'created_at' => date('d-m-Y h:i:s')
            );

            $result = $this->Core_model->add_income_type($data);
            if ($result == TRUE) {
                $Return['result'] = $this->lang->line('xin_income_type_success_added');
            } else {
                $Return['error'] = $this->lang->line('xin_error_msg');
            }
            //$this->output->set_output(json_encode($Return));
            // exit;
        }
    }

    // Validate and add info in database
    public function currency_type_info()
    {

        if ($this->input->post('type') == 'currency_type_info') {
            /* Define return | here result is used to return user data and error for error message */
            $Return = array('result' => '', 'error' => '', 'csrf_hash' => '');
            $Return['csrf_hash'] = $this->security->get_csrf_hash();

            /* Server side PHP input validation */
            if ($this->input->post('name') === '') {
                $Return['error'] = $this->lang->line('xin_error_currency_name_field');
            } else if ($this->input->post('code') === '') {
                $Return['error'] = $this->lang->line('xin_error_currency_code_field');
            } else if ($this->input->post('symbol') === '') {
                $Return['error'] = $this->lang->line('xin_error_currency_symbol_field');
            }

            if ($Return['error'] != '') {
                //$this->output->set_output(json_encode($Return));
            }

            $data = array(
                'name' => $this->input->post('name'),
                'code' => $this->input->post('code'),
                'symbol' => $this->input->post('symbol')
            );

            $result = $this->Core_model->add_currency_type($data);
            if ($result == TRUE) {
                $Return['result'] = $this->lang->line('xin_success_currency_type_added');
            } else {
                $Return['error'] = $this->lang->line('xin_error_msg');
            }
            //$this->output->set_output(json_encode($Return));
            // exit;
        }
    }

    /*  DELETE CONSTANTS */


    // delete constant record > table
    public function delete_document_type()
    {

        if ($this->input->post('type') == 'delete_record') {
            /* Define return | here result is used to return user data and error for error message */
            $Return = array('result' => '', 'error' => '', 'csrf_hash' => '');
            $id = $this->uri->segment(4);
            $Return['csrf_hash'] = $this->security->get_csrf_hash();
            $result = $this->Core_model->delete_document_type_record($id);
            if (isset($id)) {
                $Return['result'] = $this->lang->line('xin_success_document_type_deleted');
            } else {
                $Return['error'] = $this->lang->line('xin_error_msg');
            }
            //$this->output->set_output(json_encode($Return));
        }
    }

    // delete constant record > table
    public function delete_payment_method()
    {

        if ($this->input->post('type') == 'delete_record') {
            /* Define return | here result is used to return user data and error for error message */
            $Return = array('result' => '', 'error' => '', 'csrf_hash' => '');
            $id = $this->uri->segment(4);
            $Return['csrf_hash'] = $this->security->get_csrf_hash();
            $result = $this->Core_model->delete_payment_method_record($id);
            if (isset($id)) {
                $Return['result'] = $this->lang->line('xin_success_payment_method_deleted');
            } else {
                $Return['error'] = $this->lang->line('xin_error_msg');
            }
            //$this->output->set_output(json_encode($Return));
        }
    }

    // delete constant record > table
    public function delete_education_level()
    {

        if ($this->input->post('type') == 'delete_record') {
            /* Define return | here result is used to return user data and error for error message */
            $Return = array('result' => '', 'error' => '', 'csrf_hash' => '');
            $id = $this->uri->segment(4);
            $Return['csrf_hash'] = $this->security->get_csrf_hash();
            $result = $this->Core_model->delete_education_level_record($id);
            if (isset($id)) {
                $Return['result'] = $this->lang->line('xin_success_education_level_deleted');
            } else {
                $Return['error'] = $this->lang->line('xin_error_msg');
            }
            //$this->output->set_output(json_encode($Return));
        }
    }

    // delete constant record > table
    public function delete_qualification_language()
    {

        if ($this->input->post('type') == 'delete_record') {
            /* Define return | here result is used to return user data and error for error message */
            $Return = array('result' => '', 'error' => '', 'csrf_hash' => '');
            $id = $this->uri->segment(4);
            $Return['csrf_hash'] = $this->security->get_csrf_hash();
            $result = $this->Core_model->delete_qualification_language_record($id);
            if (isset($id)) {
                $Return['result'] = $this->lang->line('xin_success_qualification_lang_deleted');
            } else {
                $Return['error'] = $this->lang->line('xin_error_msg');
            }
            //$this->output->set_output(json_encode($Return));
        }
    }

    // delete constant record > table
    public function delete_qualification_skill()
    {

        if ($this->input->post('type') == 'delete_record') {
            /* Define return | here result is used to return user data and error for error message */
            $Return = array('result' => '', 'error' => '', 'csrf_hash' => '');
            $id = $this->uri->segment(4);
            $Return['csrf_hash'] = $this->security->get_csrf_hash();
            $result = $this->Core_model->delete_qualification_skill_record($id);
            if (isset($id)) {
                $Return['result'] = $this->lang->line('xin_success_qualification_skill_deleted');
            } else {
                $Return['error'] = $this->lang->line('xin_error_msg');
            }
            //$this->output->set_output(json_encode($Return));
        }
    }



    // delete constant record > table
    public function delete_leave_type()
    {

        if ($this->input->post('type') == 'delete_record') {
            /* Define return | here result is used to return user data and error for error message */
            $Return = array('result' => '', 'error' => '', 'csrf_hash' => '');
            $id = $this->uri->segment(4);
            $Return['csrf_hash'] = $this->security->get_csrf_hash();
            $result = $this->Core_model->delete_leave_type_record($id);
            if (isset($id)) {
                $Return['result'] = $this->lang->line('xin_success_leave_type_deleted');
            } else {
                $Return['error'] = $this->lang->line('xin_error_msg');
            }
            //$this->output->set_output(json_encode($Return));
        }
    }

    // delete constant record > table
    public function delete_sick_type()
    {

        if ($this->input->post('type') == 'delete_record') {
            /* Define return | here result is used to return user data and error for error message */
            $Return = array('result' => '', 'error' => '', 'csrf_hash' => '');
            $id = $this->uri->segment(4);
            $Return['csrf_hash'] = $this->security->get_csrf_hash();
            $result = $this->Core_model->delete_sick_type_record($id);
            if (isset($id)) {
                $Return['result'] = $this->lang->line('xin_success_sick_type_deleted');
            } else {
                $Return['error'] = $this->lang->line('xin_error_msg');
            }
            //$this->output->set_output(json_encode($Return));
        }
    }

    // delete constant record > table
    public function delete_izin_type()
    {

        if ($this->input->post('type') == 'delete_record') {
            /* Define return | here result is used to return user data and error for error message */
            $Return = array('result' => '', 'error' => '', 'csrf_hash' => '');
            $id = $this->uri->segment(4);
            $Return['csrf_hash'] = $this->security->get_csrf_hash();
            $result = $this->Core_model->delete_izin_type_record($id);
            if (isset($id)) {
                $Return['result'] = $this->lang->line('xin_success_izin_type_deleted');
            } else {
                $Return['error'] = $this->lang->line('xin_error_msg');
            }
            //$this->output->set_output(json_encode($Return));
        }
    }

    // public function delete_shift_jam() {

    // 	if($this->input->post('type')=='delete_record') {
    // 		/* Define return | here result is used to return user data and error for error message */
    // 		$Return = array('result'=>'', 'error'=>'', 'csrf_hash'=>'');
    // 		$id = $this->uri->segment(4);
    // 		$Return['csrf_hash'] = $this->security->get_csrf_hash();
    // 		$result = $this->Core_model->delete_shift_jam_record($id);
    // 		if(isset($id)) {
    // 			$Return['result'] = $this->lang->line('xin_success_shift_jam_deleted');
    // 		} else {
    // 			$Return['error'] = $this->lang->line('xin_error_msg');
    // 		}
    // 		//$this->output->set_output(json_encode($Return));
    // 	}
    // }

    // delete constant record > table
    public function delete_warning_type()
    {

        if ($this->input->post('type') == 'delete_record') {
            /* Define return | here result is used to return user data and error for error message */
            $Return = array('result' => '', 'error' => '', 'csrf_hash' => '');
            $id = $this->uri->segment(4);
            $Return['csrf_hash'] = $this->security->get_csrf_hash();
            $result = $this->Core_model->delete_warning_type_record($id);
            if (isset($id)) {
                $Return['result'] = $this->lang->line('xin_success_warning_type_deleted');
            } else {
                $Return['error'] = $this->lang->line('xin_error_msg');
            }
            //$this->output->set_output(json_encode($Return));
        }
    }

    // delete constant record > table
    public function delete_termination_type()
    {

        if ($this->input->post('type') == 'delete_record') {
            /* Define return | here result is used to return user data and error for error message */
            $Return = array('result' => '', 'error' => '', 'csrf_hash' => '');
            $id = $this->uri->segment(4);
            $Return['csrf_hash'] = $this->security->get_csrf_hash();
            $result = $this->Core_model->delete_termination_type_record($id);
            if (isset($id)) {
                $Return['result'] = $this->lang->line('xin_success_termination_type_deleted');
            } else {
                $Return['error'] = $this->lang->line('xin_error_msg');
            }
            //$this->output->set_output(json_encode($Return));
        }
    }

    // delete constant record > table
    public function delete_expense_type()
    {

        if ($this->input->post('type') == 'delete_record') {
            /* Define return | here result is used to return user data and error for error message */
            $Return = array('result' => '', 'error' => '', 'csrf_hash' => '');
            $id = $this->uri->segment(4);
            $Return['csrf_hash'] = $this->security->get_csrf_hash();
            $result = $this->Core_model->delete_expense_type_record($id);
            if (isset($id)) {
                $Return['result'] = $this->lang->line('xin_success_expense_type_deleted');
            } else {
                $Return['error'] = $this->lang->line('xin_error_msg');
            }
            //$this->output->set_output(json_encode($Return));
        }
    }

    // delete constant record > table
    public function delete_job_type()
    {

        if ($this->input->post('type') == 'delete_record') {
            /* Define return | here result is used to return user data and error for error message */
            $Return = array('result' => '', 'error' => '', 'csrf_hash' => '');
            $id = $this->uri->segment(4);
            $Return['csrf_hash'] = $this->security->get_csrf_hash();
            $result = $this->Core_model->delete_job_type_record($id);
            if (isset($id)) {
                $Return['result'] = $this->lang->line('xin_success_job_type_deleted');
            } else {
                $Return['error'] = $this->lang->line('xin_error_msg');
            }
            //$this->output->set_output(json_encode($Return));
        }
    }

    // delete constant record > table
    public function delete_job_category()
    {

        if ($this->input->post('type') == 'delete_record') {
            /* Define return | here result is used to return user data and error for error message */
            $Return = array('result' => '', 'error' => '', 'csrf_hash' => '');
            $id = $this->uri->segment(4);
            $Return['csrf_hash'] = $this->security->get_csrf_hash();
            $result = $this->Core_model->delete_job_category_record($id);
            if (isset($id)) {
                $Return['result'] = $this->lang->line('xin_success_job_category_deleted');
            } else {
                $Return['error'] = $this->lang->line('xin_error_msg');
            }
            //$this->output->set_output(json_encode($Return));
        }
    }

    // delete constant record > table
    public function delete_exit_type()
    {

        if ($this->input->post('type') == 'delete_record') {
            /* Define return | here result is used to return user data and error for error message */
            $Return = array('result' => '', 'error' => '', 'csrf_hash' => '');
            $id = $this->uri->segment(4);
            $Return['csrf_hash'] = $this->security->get_csrf_hash();
            $result = $this->Core_model->delete_exit_type_record($id);
            if (isset($id)) {
                $Return['result'] = 'Jenis Resign Berhasil Dihapus';
            } else {
                $Return['error'] = $this->lang->line('xin_error_msg');
            }
            //$this->output->set_output(json_encode($Return));
        }
    }

    // delete constant record > table
    public function delete_exit_type_reason()
    {

        if ($this->input->post('type') == 'delete_record_reason') {
            /* Define return | here result is used to return user data and error for error message */
            $Return = array('result' => '', 'error' => '', 'csrf_hash' => '');
            $id = $this->uri->segment(4);
            $Return['csrf_hash'] = $this->security->get_csrf_hash();
            $result = $this->Core_model->delete_exit_type_reason_record($id);
            if (isset($id)) {
                $Return['result'] = 'Jenis Alasan Resign Berhasil Dihapus';
            } else {
                $Return['error'] = $this->lang->line('xin_error_msg');
            }
            //$this->output->set_output(json_encode($Return));
        }
    }

    // delete constant record > table
    public function delete_travel_arr_type()
    {

        if ($this->input->post('type') == 'delete_record') {
            /* Define return | here result is used to return user data and error for error message */
            $Return = array('result' => '', 'error' => '', 'csrf_hash' => '');
            $id = $this->uri->segment(4);
            $Return['csrf_hash'] = $this->security->get_csrf_hash();
            $result = $this->Core_model->delete_travel_arr_type_record($id);
            if (isset($id)) {
                $Return['result'] = $this->lang->line('xin_success_travel_arrtype_deleted');
            } else {
                $Return['error'] = $this->lang->line('xin_error_msg');
            }
            //$this->output->set_output(json_encode($Return));
        }
    }

    public function delete_transport_arr_type()
    {

        if ($this->input->post('type') == 'delete_record') {
            /* Define return | here result is used to return user data and error for error message */
            $Return = array('result' => '', 'error' => '', 'csrf_hash' => '');
            $id = $this->uri->segment(4);
            $Return['csrf_hash'] = $this->security->get_csrf_hash();
            $result = $this->Core_model->delete_transport_arr_type_record($id);
            if (isset($id)) {
                $Return['result'] = $this->lang->line('xin_success_transport_arrtype_deleted');
            } else {
                $Return['error'] = $this->lang->line('xin_error_msg');
            }
            //$this->output->set_output(json_encode($Return));
        }
    }



    // delete constant record > table
    public function delete_income_type()
    {

        if ($this->input->post('type') == 'delete_record') {
            /* Define return | here result is used to return user data and error for error message */
            $Return = array('result' => '', 'error' => '', 'csrf_hash' => '');
            $id = $this->uri->segment(4);
            $Return['csrf_hash'] = $this->security->get_csrf_hash();
            $result = $this->Core_model->delete_income_type_record($id);
            if (isset($id)) {
                $Return['result'] = $this->lang->line('xin_income_type_success_deleted');
            } else {
                $Return['error'] = $this->lang->line('xin_error_msg');
            }
            //$this->output->set_output(json_encode($Return));
        }
    }

    // delete constant record > table
    public function delete_currency_type()
    {

        if ($this->input->post('type') == 'delete_record') {
            /* Define return | here result is used to return user data and error for error message */
            $Return = array('result' => '', 'error' => '', 'csrf_hash' => '');
            $id = $this->uri->segment(4);
            $Return['csrf_hash'] = $this->security->get_csrf_hash();
            $result = $this->Core_model->delete_currency_type_record($id);
            if (isset($id)) {
                $Return['result'] = $this->lang->line('xin_success_currency_type_deleted');
            } else {
                $Return['error'] = $this->lang->line('xin_error_msg');
            }
            //$this->output->set_output(json_encode($Return));
        }
    }

    // delete constant record > table
    public function delete_company_type()
    {

        if ($this->input->post('type') == 'delete_record') {
            /* Define return | here result is used to return user data and error for error message */
            $Return = array('result' => '', 'error' => '', 'csrf_hash' => '');
            $id = $this->uri->segment(4);
            $Return['csrf_hash'] = $this->security->get_csrf_hash();
            $result = $this->Core_model->delete_company_type_record($id);
            if (isset($id)) {
                $Return['result'] = $this->lang->line('xin_company_type_deleted');
            } else {
                $Return['error'] = $this->lang->line('xin_error_msg');
            }
            //$this->output->set_output(json_encode($Return));
        }
    }
    // delete constant record > table
    public function delete_security_level()
    {

        if ($this->input->post('type') == 'delete_record') {
            /* Define return | here result is used to return user data and error for error message */
            $Return = array('result' => '', 'error' => '', 'csrf_hash' => '');
            $id = $this->uri->segment(4);
            $Return['csrf_hash'] = $this->security->get_csrf_hash();
            $result = $this->Core_model->delete_security_level_record($id);
            if (isset($id)) {
                $Return['result'] = $this->lang->line('xin_security_level_deleted');
            } else {
                $Return['error'] = $this->lang->line('xin_error_msg');
            }
            //$this->output->set_output(json_encode($Return));
        }
    }
    // read and view all constants data > modal form
    public function constants_read()
    {
        $data['title'] = $this->Core_model->site_title();
        $session = $this->session->userdata('username');
        if (!empty($session)) {
            $this->load->view('admin/settings/dialog_constants', $data);
        } else {
            redirect('admin/');
        }
    }

    /*  UPDATE RECORD > CONSTANTS*/

    // Validate and update info in database
    public function update_document_type()
    {

        if ($this->input->post('type') == 'edit_record') {

            $id = $this->uri->segment(4);

            /* Define return | here result is used to return user data and error for error message */
            $Return = array('result' => '', 'error' => '', 'csrf_hash' => '');
            $Return['csrf_hash'] = $this->security->get_csrf_hash();

            /* Server side PHP input validation */
            if ($this->input->post('name') === '') {
                $Return['error'] = $this->lang->line('xin_employee_error_d_type');
            }

            if ($Return['error'] != '') {
                //$this->output->set_output(json_encode($Return));
            }

            $data = array(
                'document_type' => $this->input->post('name'),
                'company_id' => $this->input->post('company')
            );

            $result = $this->Core_model->update_document_type_record($data, $id);

            if ($result == TRUE) {
                $Return['result'] = $this->lang->line('xin_success_document_type_updated');
            } else {
                $Return['error'] = $this->lang->line('xin_error_msg');
            }
            //$this->output->set_output(json_encode($Return));
            // exit;
        }
    }



    // Validate and update info in database
    public function update_income_type()
    {

        if ($this->input->post('type') == 'edit_record') {

            $id = $this->uri->segment(4);

            /* Define return | here result is used to return user data and error for error message */
            $Return = array('result' => '', 'error' => '', 'csrf_hash' => '');
            $Return['csrf_hash'] = $this->security->get_csrf_hash();

            /* Server side PHP input validation */
            if ($this->input->post('income_type') === '') {
                $Return['error'] = $this->lang->line('xin_income_type_error_field');
            }

            if ($Return['error'] != '') {
                //$this->output->set_output(json_encode($Return));
            }

            $data = array(
                'name' => $this->input->post('income_type'),
            );

            $result = $this->Core_model->update_income_type_record($data, $id);

            if ($result == TRUE) {
                $Return['result'] = $this->lang->line('xin_income_type_success_updated');
            } else {
                $Return['error'] = $this->lang->line('xin_error_msg');
            }
            //$this->output->set_output(json_encode($Return));
            // exit;
        }
    }



    // Validate and update info in database
    public function update_payment_method()
    {

        if ($this->input->post('type') == 'edit_record') {

            $id = $this->uri->segment(4);

            /* Define return | here result is used to return user data and error for error message */
            $Return = array('result' => '', 'error' => '', 'csrf_hash' => '');
            $Return['csrf_hash'] = $this->security->get_csrf_hash();

            /* Server side PHP input validation */
            if ($this->input->post('name') === '') {
                $Return['error'] = $this->lang->line('xin_error_payment_method');
            }

            if ($Return['error'] != '') {
                //$this->output->set_output(json_encode($Return));
            }

            $data = array(

                'method_name' => $this->input->post('name'),
                'payment_percentage' => $this->input->post('payment_percentage'),
                'account_number' => $this->input->post('account_number')
            );

            $result = $this->Core_model->update_payment_method_record($data, $id);

            if ($result == TRUE) {
                $Return['result'] = $this->lang->line('xin_success_payment_method_updated');
            } else {
                $Return['error'] = $this->lang->line('xin_error_msg');
            }
            //$this->output->set_output(json_encode($Return));
            // exit;
        }
    }

    // Validate and update info in database
    public function update_education_level()
    {

        if ($this->input->post('type') == 'edit_record') {

            $id = $this->uri->segment(4);

            /* Define return | here result is used to return user data and error for error message */
            $Return = array('result' => '', 'error' => '', 'csrf_hash' => '');
            $Return['csrf_hash'] = $this->security->get_csrf_hash();

            /* Server side PHP input validation */
            if ($this->input->post('name') === '') {
                $Return['error'] = $this->lang->line('xin_error_education_level');
            }

            if ($Return['error'] != '') {
                //$this->output->set_output(json_encode($Return));
            }

            $data = array(

                'name' => $this->input->post('name')
            );

            $result = $this->Core_model->update_education_level_record($data, $id);

            if ($result == TRUE) {
                $Return['result'] = $this->lang->line('xin_success_education_level_updated');
            } else {
                $Return['error'] = $this->lang->line('xin_error_msg');
            }
            //$this->output->set_output(json_encode($Return));
            // exit;
        }
    }

    // Validate and update info in database
    public function update_qualification_language()
    {

        if ($this->input->post('type') == 'edit_record') {

            $id = $this->uri->segment(4);

            /* Define return | here result is used to return user data and error for error message */
            $Return = array('result' => '', 'error' => '', 'csrf_hash' => '');
            $Return['csrf_hash'] = $this->security->get_csrf_hash();

            /* Server side PHP input validation */
            if ($this->input->post('name') === '') {
                $Return['error'] = $this->lang->line('xin_error_education_language');
            }

            if ($Return['error'] != '') {
                //$this->output->set_output(json_encode($Return));
            }

            $data = array(

                'name' => $this->input->post('name')
            );

            $result = $this->Core_model->update_qualification_language_record($data, $id);

            if ($result == TRUE) {
                $Return['result'] = $this->lang->line('xin_error_education_level');
            } else {
                $Return['error'] = $this->lang->line('xin_error_msg');
            }
            //$this->output->set_output(json_encode($Return));
            // exit;
        }
    }

    // Validate and update info in database
    public function update_qualification_skill()
    {

        if ($this->input->post('type') == 'edit_record') {

            $id = $this->uri->segment(4);

            /* Define return | here result is used to return user data and error for error message */
            $Return = array('result' => '', 'error' => '', 'csrf_hash' => '');
            $Return['csrf_hash'] = $this->security->get_csrf_hash();

            /* Server side PHP input validation */
            if ($this->input->post('name') === '') {
                $Return['error'] = $this->lang->line('xin_error_education_skill');
            }

            if ($Return['error'] != '') {
                //$this->output->set_output(json_encode($Return));
            }

            $data = array(

                'name' => $this->input->post('name')
            );

            $result = $this->Core_model->update_qualification_skill_record($data, $id);

            if ($result == TRUE) {
                $Return['result'] = $this->lang->line('xin_success_qualification_skill_updated');
            } else {
                $Return['error'] = $this->lang->line('xin_error_msg');
            }
            //$this->output->set_output(json_encode($Return));
            // exit;
        }
    }



    // Validate and update info in database
    public function update_leave_type()
    {

        if ($this->input->post('type') == 'edit_record') {

            $id = $this->uri->segment(4);

            /* Define return | here result is used to return user data and error for error message */
            $Return = array('result' => '', 'error' => '', 'csrf_hash' => '');
            $Return['csrf_hash'] = $this->security->get_csrf_hash();

            /* Server side PHP input validation */
            if ($this->input->post('name') === '') {
                $Return['error'] = $this->lang->line('xin_error_leave_type_field');
            } else if ($this->input->post('days_per_year') === '') {
                $Return['error'] = $this->lang->line('xin_error_days_per_year');
            }

            if ($Return['error'] != '') {
                //$this->output->set_output(json_encode($Return));
            }

            $data = array(

                'type_name' => $this->input->post('name'),
                'days_per_year' => $this->input->post('days_per_year')
            );

            $result = $this->Core_model->update_leave_type_record($data, $id);

            if ($result == TRUE) {
                $Return['result'] = $this->lang->line('xin_success_leave_type_updated');
            } else {
                $Return['error'] = $this->lang->line('xin_error_msg');
            }
            //$this->output->set_output(json_encode($Return));
            // exit;
        }
    }

    // Validate and update info in database
    public function update_sick_type()
    {

        if ($this->input->post('type') == 'edit_record') {

            $id = $this->uri->segment(4);

            /* Define return | here result is used to return user data and error for error message */
            $Return = array('result' => '', 'error' => '', 'csrf_hash' => '');
            $Return['csrf_hash'] = $this->security->get_csrf_hash();

            /* Server side PHP input validation */
            if ($this->input->post('name') === '') {
                $Return['error'] = $this->lang->line('xin_error_sick_type_field');
            }

            if ($Return['error'] != '') {
                //$this->output->set_output(json_encode($Return));
            }

            $data = array(

                'type_name' => $this->input->post('name')

            );

            $result = $this->Core_model->update_sick_type_record($data, $id);

            if ($result == TRUE) {
                $Return['result'] = $this->lang->line('xin_success_sick_type_updated');
            } else {
                $Return['error'] = $this->lang->line('xin_error_msg');
            }
            //$this->output->set_output(json_encode($Return));
            // exit;
        }
    }

    // Validate and update info in database
    public function update_izin_type()
    {

        if ($this->input->post('type') == 'edit_record') {

            $id = $this->uri->segment(4);

            /* Define return | here result is used to return user data and error for error message */
            $Return = array('result' => '', 'error' => '', 'csrf_hash' => '');
            $Return['csrf_hash'] = $this->security->get_csrf_hash();

            /* Server side PHP input validation */
            if ($this->input->post('name') === '') {
                $Return['error'] = $this->lang->line('xin_error_izin_type_field');
            }

            if ($Return['error'] != '') {
                //$this->output->set_output(json_encode($Return));
            }

            $data = array(

                'type_name' => $this->input->post('name')

            );

            $result = $this->Core_model->update_izin_type_record($data, $id);

            if ($result == TRUE) {
                $Return['result'] = $this->lang->line('xin_success_izin_type_updated');
            } else {
                $Return['error'] = $this->lang->line('xin_error_msg');
            }
            //$this->output->set_output(json_encode($Return));
            // exit;
        }
    }

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
                //$this->output->set_output(json_encode($Return));
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
            //$this->output->set_output(json_encode($Return));
            // exit;
        }
    }
    // Validate and update info in database
    public function update_warning_type()
    {

        if ($this->input->post('type') == 'edit_record') {

            $id = $this->uri->segment(4);

            /* Define return | here result is used to return user data and error for error message */
            $Return = array('result' => '', 'error' => '', 'csrf_hash' => '');
            $Return['csrf_hash'] = $this->security->get_csrf_hash();

            /* Server side PHP input validation */
            if ($this->input->post('name') === '') {
                $Return['error'] = $this->lang->line('xin_employee_error_warning_type');
            }

            if ($Return['error'] != '') {
                //$this->output->set_output(json_encode($Return));
            }

            $data = array(

                'type' => $this->input->post('name')
            );

            $result = $this->Core_model->update_warning_type_record($data, $id);

            if ($result == TRUE) {
                $Return['result'] = $this->lang->line('xin_success_warning_type_updated');
            } else {
                $Return['error'] = $this->lang->line('xin_error_msg');
            }
            //$this->output->set_output(json_encode($Return));
            // exit;
        }
    }

    // Validate and update info in database
    public function update_termination_type()
    {

        if ($this->input->post('type') == 'edit_record') {

            $id = $this->uri->segment(4);

            /* Define return | here result is used to return user data and error for error message */
            $Return = array('result' => '', 'error' => '', 'csrf_hash' => '');
            $Return['csrf_hash'] = $this->security->get_csrf_hash();

            /* Server side PHP input validation */
            if ($this->input->post('name') === '') {
                $Return['error'] = $this->lang->line('xin_error_termination_type');
            }

            if ($Return['error'] != '') {
                //$this->output->set_output(json_encode($Return));
            }

            $data = array(

                'type' => $this->input->post('name')
            );

            $result = $this->Core_model->update_termination_type_record($data, $id);

            if ($result == TRUE) {
                $Return['result'] = $this->lang->line('xin_success_termination_type_updated');
            } else {
                $Return['error'] = $this->lang->line('xin_error_msg');
            }
            //$this->output->set_output(json_encode($Return));
            // exit;
        }
    }

    // Validate and update info in database
    public function update_expense_type()
    {

        if ($this->input->post('type') == 'edit_record') {

            $id = $this->uri->segment(4);

            /* Define return | here result is used to return user data and error for error message */
            $Return = array('result' => '', 'error' => '', 'csrf_hash' => '');
            $Return['csrf_hash'] = $this->security->get_csrf_hash();

            /* Server side PHP input validation */
            if ($this->input->post('company') === '') {
                $Return['error'] = $this->lang->line('error_company_field');
            } else if ($this->input->post('name') === '') {
                $Return['error'] = $this->lang->line('xin_error_expense_type');
            }

            if ($Return['error'] != '') {
                //$this->output->set_output(json_encode($Return));
            }

            $data = array(
                'company_id' => $this->input->post('company'),
                'name' => $this->input->post('name')
            );

            $result = $this->Core_model->update_expense_type_record($data, $id);

            if ($result == TRUE) {
                $Return['result'] = $this->lang->line('xin_success_expense_type_updated');
            } else {
                $Return['error'] = $this->lang->line('xin_error_msg');
            }
            //$this->output->set_output(json_encode($Return));
            // exit;
        }
    }

    // Validate and update info in database
    public function update_job_type()
    {

        if ($this->input->post('type') == 'edit_record') {

            $id = $this->uri->segment(4);

            /* Define return | here result is used to return user data and error for error message */
            $Return = array('result' => '', 'error' => '', 'csrf_hash' => '');
            $Return['csrf_hash'] = $this->security->get_csrf_hash();

            /* Server side PHP input validation */
            if ($this->input->post('name') === '') {
                $Return['error'] = $this->lang->line('xin_error_jobpost_type');
            }

            if ($Return['error'] != '') {
                //$this->output->set_output(json_encode($Return));
            }

            $data = array(

                'type' => $this->input->post('name')
            );

            $result = $this->Core_model->update_job_type_record($data, $id);

            if ($result == TRUE) {
                $Return['result'] = $this->lang->line('xin_success_job_type_updated');
            } else {
                $Return['error'] = $this->lang->line('xin_error_msg');
            }
            //$this->output->set_output(json_encode($Return));
            // exit;
        }
    }

    // Validate and update info in database
    public function update_job_category()
    {

        if ($this->input->post('type') == 'edit_record') {

            $id = $this->uri->segment(4);

            /* Define return | here result is used to return user data and error for error message */
            $Return = array('result' => '', 'error' => '', 'csrf_hash' => '');
            $Return['csrf_hash'] = $this->security->get_csrf_hash();

            /* Server side PHP input validation */
            if ($this->input->post('job_category') === '') {
                $Return['error'] = $this->lang->line('xin_error_job_category');
            }

            if ($Return['error'] != '') {
                //$this->output->set_output(json_encode($Return));
            }

            $data = array(

                'category_name' => $this->input->post('job_category')
            );

            $result = $this->Core_model->update_job_category_record($data, $id);

            if ($result == TRUE) {
                $Return['result'] = $this->lang->line('xin_success_job_category_updated');
            } else {
                $Return['error'] = $this->lang->line('xin_error_msg');
            }
            //$this->output->set_output(json_encode($Return));
            // exit;
        }
    }

    // Validate and update info in database
    public function update_exit_type()
    {

        if ($this->input->post('type') == 'edit_record') {

            $id = $this->uri->segment(4);

            /* Define return | here result is used to return user data and error for error message */
            $Return = array('result' => '', 'error' => '', 'csrf_hash' => '');
            $Return['csrf_hash'] = $this->security->get_csrf_hash();

            /* Server side PHP input validation */
            if ($this->input->post('name') === '') {
                $Return['error'] = $this->lang->line('xin_error_exit_type');
            }

            if ($Return['error'] != '') {
                //$this->output->set_output(json_encode($Return));
            }

            $data = array(

                'type' => $this->input->post('name')
            );

            $result = $this->Core_model->update_exit_type_record($data, $id);

            if ($result == TRUE) {
                $Return['result'] = 'Jenis Resign Berhasil Diperbarui';
            } else {
                $Return['error'] = $this->lang->line('xin_error_msg');
            }
            //$this->output->set_output(json_encode($Return));
            // exit;
        }
    }

    public function update_exit_type_reason()
    {

        if ($this->input->post('type') == 'edit_record_reason') {

            $id = $this->uri->segment(4);

            /* Define return | here result is used to return user data and error for error message */
            $Return = array('result' => '', 'error' => '', 'csrf_hash' => '');
            $Return['csrf_hash'] = $this->security->get_csrf_hash();

            /* Server side PHP input validation */
            if ($this->input->post('name') === '') {
                $Return['error'] = $this->lang->line('xin_error_exit_type_reason');
            }

            if ($Return['error'] != '') {
                //$this->output->set_output(json_encode($Return));
            }

            $data = array(

                'type' => $this->input->post('name')
            );

            $result = $this->Core_model->update_exit_type_reason_record($data, $id);

            if ($result == TRUE) {
                $Return['result'] = 'Jenis Alasan Resign Berhasil Diperbarui';
            } else {
                $Return['error'] = $this->lang->line('xin_error_msg');
            }
            //$this->output->set_output(json_encode($Return));
            // exit;
        }
    }

    // Validate and update info in database
    public function update_travel_arr_type()
    {

        if ($this->input->post('type') == 'edit_record') {

            $id = $this->uri->segment(4);

            /* Define return | here result is used to return user data and error for error message */
            $Return = array('result' => '', 'error' => '', 'csrf_hash' => '');
            $Return['csrf_hash'] = $this->security->get_csrf_hash();

            /* Server side PHP input validation */
            if ($this->input->post('name') === '') {
                $Return['error'] = $this->lang->line('xin_error_travel_arrangment_type');
            }

            if ($Return['error'] != '') {
                //$this->output->set_output(json_encode($Return));
            }

            $data = array(

                'type' => $this->input->post('name')
            );

            $result = $this->Core_model->update_travel_arr_record($data, $id);

            if ($result == TRUE) {
                $Return['result'] = $this->lang->line('xin_success_travel_arrtype_updated');
            } else {
                $Return['error'] = $this->lang->line('xin_error_msg');
            }
            //$this->output->set_output(json_encode($Return));
            // exit;
        }
    }

    // Validate and update info in database
    public function update_transport_arr_type()
    {

        if ($this->input->post('type') == 'edit_record') {

            $id = $this->uri->segment(4);

            /* Define return | here result is used to return user data and error for error message */
            $Return = array('result' => '', 'error' => '', 'csrf_hash' => '');
            $Return['csrf_hash'] = $this->security->get_csrf_hash();

            /* Server side PHP input validation */
            if ($this->input->post('name') === '') {
                $Return['error'] = $this->lang->line('xin_error_transport_arrangment_type');
            }

            if ($Return['error'] != '') {
                //$this->output->set_output(json_encode($Return));
            }

            $data = array(

                'type' => $this->input->post('name')
            );

            $result = $this->Core_model->update_transport_arr_record($data, $id);

            if ($result == TRUE) {
                $Return['result'] = $this->lang->line('xin_success_transport_arrtype_updated');
            } else {
                $Return['error'] = $this->lang->line('xin_error_msg');
            }
            //$this->output->set_output(json_encode($Return));
            // exit;
        }
    }

    // Validate and update info in database
    public function update_company_type()
    {

        if ($this->input->post('type') == 'edit_record') {

            $id = $this->uri->segment(4);

            /* Define return | here result is used to return user data and error for error message */
            $Return = array('result' => '', 'error' => '', 'csrf_hash' => '');
            $Return['csrf_hash'] = $this->security->get_csrf_hash();

            /* Server side PHP input validation */
            if ($this->input->post('name') === '') {
                $Return['error'] = $this->lang->line('xin_error_ctype_field');
            }

            if ($Return['error'] != '') {
                //$this->output->set_output(json_encode($Return));
            }

            $data = array(
                'name' => $this->input->post('name')
            );

            $result = $this->Core_model->update_company_type_record($data, $id);

            if ($result == TRUE) {
                $Return['result'] = $this->lang->line('xin_company_type_updated');
            } else {
                $Return['error'] = $this->lang->line('xin_error_msg');
            }
            //$this->output->set_output(json_encode($Return));
            // exit;
        }
    }

    // Validate and update info in database
    public function update_currency_type()
    {

        if ($this->input->post('type') == 'edit_record') {

            $id = $this->uri->segment(4);

            /* Define return | here result is used to return user data and error for error message */
            $Return = array('result' => '', 'error' => '', 'csrf_hash' => '');
            $Return['csrf_hash'] = $this->security->get_csrf_hash();

            /* Server side PHP input validation */
            if ($this->input->post('name') === '') {
                $Return['error'] = $this->lang->line('xin_error_currency_name_field');
            } else if ($this->input->post('code') === '') {
                $Return['error'] = $this->lang->line('xin_error_currency_code_field');
            } else if ($this->input->post('symbol') === '') {
                $Return['error'] = $this->lang->line('xin_error_currency_symbol_field');
            }

            if ($Return['error'] != '') {
                //$this->output->set_output(json_encode($Return));
            }

            $data = array(

                'name' => $this->input->post('name'),
                'code' => $this->input->post('code'),
                'symbol' => $this->input->post('symbol')
            );

            $result = $this->Core_model->update_currency_type_record($data, $id);

            if ($result == TRUE) {
                $Return['result'] = $this->lang->line('xin_success_currency_type_updated');
            } else {
                $Return['error'] = $this->lang->line('xin_error_msg');
            }
            //$this->output->set_output(json_encode($Return));
            // exit;
        }
    }
    // Validate and update info in database
    public function update_payment_gateway()
    {

        if ($this->input->post('type') == 'payment_gateway') {

            /* Define return | here result is used to return user data and error for error message */
            $Return = array('result' => '', 'error' => '', 'csrf_hash' => '');
            $Return['csrf_hash'] = $this->security->get_csrf_hash();
            $id = 1;

            $data = array(
                'paypal_email' => $this->input->post('paypal_email'),
                'paypal_sandbox' => $this->input->post('paypal_sandbox'),
                'paypal_active' => $this->input->post('paypal_active'),
                'stripe_secret_key' => $this->input->post('stripe_secret_key'),
                'stripe_publishable_key' => $this->input->post('stripe_publishable_key'),
                'stripe_active' => $this->input->post('stripe_active'),
                'online_payment_account' => $this->input->post('bank_cash_id'),
            );

            $result = $this->Core_model->update_setting_info_record($data, $id);

            if ($result == TRUE) {
                $Return['result'] = $this->lang->line('xin_acc_payment_gateway_info_updated');
            } else {
                $Return['error'] = $this->lang->line('xin_error_msg');
            }
            //$this->output->set_output(json_encode($Return));
            // exit;
        }
    }
    // Validate and update info in database
    public function update_security_level()
    {

        if ($this->input->post('type') == 'edit_record') {

            $id = $this->uri->segment(4);

            /* Define return | here result is used to return user data and error for error message */
            $Return = array('result' => '', 'error' => '', 'csrf_hash' => '');
            $Return['csrf_hash'] = $this->security->get_csrf_hash();

            /* Server side PHP input validation */
            if ($this->input->post('security_level') === '') {
                $Return['error'] = $this->lang->line('xin_error_security_level_field');
            }

            if ($Return['error'] != '') {
                //$this->output->set_output(json_encode($Return));
            }

            $data = array(
                'name' => $this->input->post('security_level')
            );

            $result = $this->Core_model->update_security_level_record($data, $id);

            if ($result == TRUE) {
                $Return['result'] = $this->lang->line('xin_security_level_updated');
            } else {
                $Return['error'] = $this->lang->line('xin_error_msg');
            }
            //$this->output->set_output(json_encode($Return));
            // exit;
        }
    }
    // Validate and update info in database
    public function performance_info()
    {

        if ($this->input->post('type') == 'performance_info') {

            /* Define return | here result is used to return user data and error for error message */
            $Return = array('result' => '', 'error' => '', 'csrf_hash' => '');
            $Return['csrf_hash'] = $this->security->get_csrf_hash();

            if ($this->input->post('technical_competencies') === '') {
                $Return['error'] = $this->lang->line('xin_performance_technical_error_field');
            } else if ($this->input->post('organizational_competencies') === '') {
                $Return['error'] = $this->lang->line('xin_performance_org_error_field');
            }

            if ($Return['error'] != '') {
                $this->output($Return);
            }
            $technical_competencies = str_replace(array('php', '', 'js', '', 'html', ''), '', $this->input->post('technical_competencies'));
            $organizational_competencies = str_replace(array('php', '', 'js', '', 'html', ''), '', $this->input->post('organizational_competencies'));
            $id = 1;

            $data = array(
                'technical_competencies' => $technical_competencies,
                'organizational_competencies' => $organizational_competencies
            );

            $result = $this->Core_model->update_setting_info_record($data, $id);

            if ($result == TRUE) {
                $Return['result'] = $this->lang->line('xin_success_performance_config_updated');
            } else {
                $Return['error'] = $this->lang->line('xin_error_msg');
            }
            //$this->output->set_output(json_encode($Return));
            // exit;
        }
    }

    public function payroll_year()
    {
        $data['title'] = $this->Core_model->site_title();
        $session = $this->session->userdata('username');
        if (!empty($session)) {
            $this->load->view("admin/settings/constants", $data);
        } else {
            redirect('admin/');
        }
        // Datatables Variables
        $draw = intval($this->input->get("draw"));
        $start = intval($this->input->get("start"));
        $length = intval($this->input->get("length"));


        $constant = $this->Core_model->get_payroll_year();

        $data = array();

        foreach ($constant->result() as $r) {
            $btn_edit = "<span data-toggle='tooltip' data-placement='top' title='{$this->lang->line('xin_edit')}'>
                <button type='button' class='btn icon-btn btn-xs btn-default waves-effect waves-light' data-toggle='modal' data-target='.edit_setting_datail' data-field_id='{$r->payroll_id}' data-field_type='payroll_year'>
                    <span class='fa fa-pencil'></span>
                </button>
            </span>";

            $btn_delete = "<span data-toggle='tooltip' data-placement='top' title='{$this->lang->line('xin_delete')}'>
                <button type='button' class='btn icon-btn btn-xs btn-danger waves-effect waves-light delete' data-toggle='modal' data-target='.delete-modal' data-record-id='{$r->payroll_id}' data-token_type='payroll_year'>
                    <span class='fa fa-trash'></span>
                </button>
            </span>";

            $data[] = [$btn_edit . $btn_delete, $r->tahun];
        }

        $output = array(
            "draw" => $draw,
            "recordsTotal" => $constant->num_rows(),
            "recordsFiltered" => $constant->num_rows(),
            "data" => $data,
        );

        $this->output->set_output(json_encode($output));
    }

    // Proses : Tambah
    public function add_payroll_year()
    {
        if ($this->input->post('type') == 'add_payroll_year') {

            /* Define return | here result is used to return user data and error for error message */

            $Return = array('result' => '', 'error' => '', 'csrf_hash' => '');
            $Return['csrf_hash'] = $this->security->get_csrf_hash();

            /* Server side PHP input validation */
            if ($this->input->post('year') === '') {
                $Return['error'] = 'Tahun Tidak Boleh Kosong';
            }

            if ($Return['error'] != '') {
                $this->output($Return);
            }

            $data = array(
                'tahun' => $this->input->post('year'),
                'tahun_status' => true,
            );

            $result = $this->Core_model->add_payroll_year($data);
            if ($result == TRUE) {
                $Return['result'] = 'Tahun Penggajian Baru Berhasil Ditambahkan';
            } else {
                $Return['error'] = $this->lang->line('xin_error_msg');
            }
            $this->output($Return);
            exit;
        }
    }

    // Proses : Hapus
    public function delete_payroll_year()
    {
        if ($this->input->post('type') == 'delete_record') {
            /* Define return | here result is used to return user data and error for error message */
            $Return = array('result' => '', 'error' => '', 'csrf_hash' => '');
            $id = $this->uri->segment(4);
            $Return['csrf_hash'] = $this->security->get_csrf_hash();
            $result = $this->Core_model->delete_payroll_year($id);
            if (isset($id)) {
                $Return['result'] = 'Tahun Penggajian Berhasil Dihapus';
            } else {
                $Return['error'] = $this->lang->line('xin_error_msg');
            }

            $this->output($Return);
            exit;
        }
    }

    // Proses : Update
    public function update_payroll_year()
    {
        if ($this->input->post('type') == 'edit_record') {

            $id = $this->uri->segment(4);

            /* Define return | here result is used to return user data and error for error message */
            $Return = array('result' => '', 'error' => '', 'csrf_hash' => '');
            $Return['csrf_hash'] = $this->security->get_csrf_hash();

            /* Server side PHP input validation */
            if ($this->input->post('year') === '') {
                $Return['error'] = 'Tahun Tidak Boleh Kosong';
            }

            if ($Return['error'] != '') {
                $this->output->set_output(json_encode($Return));
            }

            $data = array(
                'tahun' => $this->input->post('year'),
            );

            $result = $this->Core_model->update_payroll_year($data, $id);

            if ($result == TRUE) {
                $Return['result'] = 'Tahun Penggajian Berhasil Diperbarui';
            } else {
                $Return['error'] = $this->lang->line('xin_error_msg');
            }

            $this->output($Return);
            exit;
        }
    }
}
