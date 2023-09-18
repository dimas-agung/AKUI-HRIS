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
 * @property Employees_model $Employees_model
 * @property Department_model $Department_model
 * @property Workstation_model $Workstation_model
 * @property Designation_model $Designation_model
 */
class Designation extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();
        //load the model
        $this->load->model("Designation_model");
        $this->load->model("Employees_model");
        $this->load->model("Core_model");
        $this->load->model("Department_model");
        $this->load->model("Location_model");
        $this->load->model("Workstation_model");
        $this->load->model("Company_model");
    }

    // ======================================================================================================
    // START
    // ======================================================================================================

    /*Function to set JSON output*/
    public function output($Return = array())
    {
        /*Set response header*/
        header("Access-Control-Allow-Origin: *");
        header("Content-Type: application/json; charset=UTF-8");
        /*Final JSON response*/
        exit(json_encode($Return));
    }

    // ======================================================================================================
    // DAFTAR
    // ======================================================================================================
    public function index()
    {
        $session = $this->session->userdata('username');
        if (empty($session)) {
            redirect('admin/');
        }
        $data['title']       = $this->lang->line('xin_designations') . ' | ' . $this->Core_model->site_title();
        $data['icon']        = '<i class="fa fa-tags"></i>';
        $data['desc']        = 'INFORMASI : Data Master Posisi';
        $data['breadcrumbs'] = $this->lang->line('xin_designations');
        $data['path_url']    = 'designation';

        $data['all_departments'] = $this->Department_model->all_departments();
        $data['get_all_companies'] = $this->Company_model->get_company();
        $data['get_all_workstation'] = $this->Company_model->get_workstation();
        //$data['all_designations'] = $this->Designation_model->all_designations();

        $role_resources_ids = $this->Core_model->user_role_resource();
        if (in_array('0241', $role_resources_ids)) {
            $data['subview'] = $this->load->view("admin/designation/designation_list", $data, TRUE);
            $this->load->view('admin/layout/layout_main', $data); //page load
        } else {
            redirect('admin/dashboard');
        }
    }

    public function designation_list()
    {

        $session = $this->session->userdata('username');
        $data['title'] = $this->Core_model->site_title();
        if (!empty($session)) {
            $this->load->view("admin/designation/designation_list", $data);
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

            $departemen = '';

            $sql_departemen = " SELECT *
                            FROM
                                 xin_departments
                            WHERE
                                1 = 1
                            AND company_id  = '" . $r->company_id . "'
                            ORDER BY department_id ASC";

            // echo "<pre>";
            // print_r( $sql_departemen );
            // echo "</pre>";
            // die;

            $query_departemen = $this->db->query($sql_departemen);

            if ($query_departemen->num_rows() > 0) {
                $departemen = '<table class="datatables-demo table table-striped table-bordered" id="xin_table">
                                <thead>
                                  <tr>
                                    <th class="text-center" width="50px">No.</th>
                                    <th class="text-center" width="100px"> Departemen </th>
                                    <th class="text-center"> Daftar Posisi</th>

                                  </tr>
                                </thead>
                                <tbody>';
                $mo = 1;
                foreach ($query_departemen->result() as $row_departemen) :

                    $jum_karyawan = $this->Employees_model->get_total_employees_departemen($row_departemen->department_id);
                    if (!is_null($jum_karyawan)) {
                        $jumlah_karyawan = $jum_karyawan[0]->jumlah;
                    } else {
                        $jumlah_karyawan = '0';
                    }

                    $jum_posisi = $this->Employees_model->get_total_employees_posisi($row_departemen->department_id);
                    if (!is_null($jum_posisi)) {
                        $jumlah_posisi = $jum_posisi[0]->jumlah;
                    } else {
                        $jumlah_posisi = '0';
                    }


                    $ititle = strtoupper($row_departemen->department_name);
                    $departemen = $departemen . '
                                            <tr">
                                                <td width="2%" align="center">' . $mo . '.</td>

                                                <td width="15%" align="left">
                                                 ' . $ititle . '<br> Karyawan : ' . $jumlah_karyawan . '<br> Posisi : ' . $jumlah_posisi . '
                                                </td>

                                                <td  align="left">';

                    $posisi = '';

                    $sql_posisi = " SELECT *
                                                                FROM
                                                                     xin_designations
                                                                WHERE
                                                                    1 = 1
                                                                AND department_id  = '" . $row_departemen->department_id . "'
                                                                ORDER BY designation_name ASC";

                    // echo "<pre>";
                    // print_r( $sql_posisi );
                    // echo "</pre>";
                    // die;

                    $query_posisi = $this->db->query($sql_posisi);

                    if ($query_posisi->num_rows() > 0) {


                        $posisi = '<table class="datatables-demo table table-striped table-bordered" id="xin_table">
                                                                    <thead>
                                                                      <tr>
                                                                        <th class="text-center" width="50px">No.</th>
                                                                        <th class="text-center" width="50px">Kode.</th>
                                                                        <th class="text-center" > Posisi </th>
                                                                        <th class="text-center"  width="120px"> Workstation </th>
                                                                        <th class="text-center" width="100px"> <i class="fa fa-user"></i> Karyawan</th>
                                                                        <th class="text-center" width="120px"> <i class="fa fa-user"></i> Gaji Harian</th>
                                                                        <th class="text-center" width="120px"> <i class="fa fa-user"></i> Gaji Bulanan</th>
                                                                        <th class="text-center" width="120px"> <i class="fa fa-user"></i> Gaji Borongan</th>
                                                                        <th class="text-center" width="100px"> Aksi</th>
                                                                      </tr>
                                                                    </thead>
                                                                    <tbody>';
                        $yo = 1;
                        foreach ($query_posisi->result() as $row_posisi) :

                            // get karyawan
                            $jum_karyawan_posisi = $this->Employees_model->get_total_employees_designation($row_posisi->designation_id);
                            if (!is_null($jum_karyawan_posisi)) {
                                $jumlah_karyawan_posisi = $jum_karyawan_posisi[0]->jumlah;
                            } else {
                                $jumlah_karyawan_posisi = '--';
                            }

                            // get karyawan harian
                            $jumlah_karyawan_harian = $this->Employees_model->get_total_employees_designation_gaji_harian($row_posisi->designation_id);
                            // if (!is_null($jum_karyawan_gaji_harian)) {
                            // 	$jumlah_karyawan_harian = $jum_karyawan_gaji_harian[0]->jumlah;
                            // } else {
                            // 	$jumlah_karyawan_harian = '--';
                            // }

                            // get karyawan bulanan
                            $jumlah_karyawan_bulanan = $this->Employees_model->get_total_employees_designation_gaji_month($row_posisi->designation_id);
                            // if (!is_null($jum_karyawan_gaji_bulanan)) {
                            // 	$jumlah_karyawan_bulanan = $jum_karyawan_gaji_bulanan[0]->jumlah;
                            // } else {
                            // 	$jumlah_karyawan_bulanan = '--';
                            // }

                            // get karyawan borongan
                            $jumlah_karyawan_borongan = $this->Employees_model->get_total_employees_designation_gaji_borongan($row_posisi->designation_id);


                            if (in_array('0243', $role_resources_ids)) { //edit
                                $edit = '<span data-toggle="tooltip" data-placement="top" title="' . $this->lang->line('xin_edit') . '">
                                                                                        <button type="button" class="btn icon-btn btn-xs btn-default waves-effect waves-light"  data-toggle="modal" data-target="#edit-modal-data"  data-designation_id="' . $row_posisi->designation_id . '">
                                                                                            <span class="fa fa-pencil"></span> Edit
                                                                                        </button>
                                                                                    </span>';
                            } else {
                                $edit = '';
                            }

                            if (in_array('0244', $role_resources_ids)) { // delete
                                $delete = '<span data-toggle="tooltip" data-placement="top" title="' . $this->lang->line('xin_delete') . '">
                                                                                            <button type="button" class="btn icon-btn btn-xs btn-danger waves-effect waves-light delete" data-toggle="modal" data-target=".delete-modal" data-record-id="' . $row_posisi->designation_id . '">
                                                                                                <span class="fa fa-trash"></span>
                                                                                            </button>
                                                                                        </span>';
                            } else {
                                $delete = '';
                            }


                            $idesignation_name = strtoupper($row_posisi->designation_name);


                            // get company
                            $workstation = $this->Workstation_model->read_workstation_information($row_posisi->workstation_id);
                            if (!is_null($workstation)) {
                                $iworkstation_name = $workstation[0]->workstation_name;
                            } else {
                                $iworkstation_name = '--';
                            }

                            $posisi = $posisi . '
                                                                                <tr">
                                                                                    <td width="2%" align="center">' . $yo . '.</td>

                                                                                    <td width="2%" align="center">
                                                                                        ' . $row_posisi->designation_id . '
                                                                                    </td>

                                                                                    <td align="left">
                                                                                     ' . $idesignation_name . '
                                                                                    </td>

                                                                                    <td width="12%" align="left">
                                                                                     ' . $iworkstation_name . '
                                                                                    </td>

                                                                                    <td width="12%" align="center">
                                                                                     ' . $jumlah_karyawan_posisi . '
                                                                                    </td>

                                                                                    <td width="12%" align="center">
                                                                                     ' . $jumlah_karyawan_harian . '
                                                                                    </td>

                                                                                     <td width="12%" align="center">
                                                                                     ' . $jumlah_karyawan_bulanan . '
                                                                                    </td>

                                                                                    <td width="12%" align="center">
                                                                                    ' . $jumlah_karyawan_borongan . '
                                                                                   </td>

                                                                                    <td width="15%" align="center">
                                                                                     ' . $edit . ' ' . $delete . '
                                                                                    </td>
                                                                                </tr>';
                            $yo++;
                        endforeach;

                        $posisi = $posisi . '
                                                                    </tbody>
                                                                    </table>';
                    } else {

                        $posisi = '<div class="warning-msg" style="padding:5px;">
                                                                        <i class ="fa fa-question-circle"></i> Tidak Ada Posisi
                                                                     </div>';
                    }

                    $departemen = $departemen . ' ' . $posisi . ' </td>


                                            </tr>';
                    $mo++;
                endforeach;

                $departemen = $departemen . '
                                </tbody>
                                </table>';
            } else {
                $departemen = '<div class="warning-msg" style="padding:5px;">
                                    <i class ="fa fa-question-circle"></i> Tidak Ada Departemen
                                 </div>';
            }


            $data[] = array(
                $no,
                $icname,
                $departemen
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

    public function read()
    {
        $session = $this->session->userdata('username');
        if (empty($session)) {
            redirect('admin/');
        }
        $data['title'] = $this->Core_model->site_title();
        $id = $this->input->get('designation_id');
        $result = $this->Designation_model->read_designation_information($id);
        $data = array(
            'designation_id' => $result[0]->designation_id,
            'company_id' => $result[0]->company_id,
            'department_id' => $result[0]->department_id,
            'sub_department_id' => $result[0]->sub_department_id,
            'designation_name' => $result[0]->designation_name,
            'workstation_id' => $result[0]->workstation_id,
            'get_all_companies' => $this->Company_model->get_company(),
            'all_departments' => $this->Department_model->all_departments(),
        );
        if (!empty($session)) {
            $this->load->view('admin/designation/dialog_designation', $data);
        } else {
            redirect('admin/');
        }
    }

    // get company > departments
    public function get_departments()
    {

        $data['title'] = $this->Core_model->site_title();
        $id = $this->uri->segment(4);

        $data = array(
            'company_id' => $id
        );
        $session = $this->session->userdata('username');
        if (!empty($session)) {
            $this->load->view("admin/designation/get_departments", $data);
        } else {
            redirect('admin/');
        }
        // Datatables Variables
        $draw = intval($this->input->get("draw"));
        $start = intval($this->input->get("start"));
        $length = intval($this->input->get("length"));
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
            $this->load->view("admin/designation/get_workstations", $data);
        } else {
            redirect('admin/');
        }
        // Datatables Variables
        $draw = intval($this->input->get("draw"));
        $start = intval($this->input->get("start"));
        $length = intval($this->input->get("length"));
    }

    // get company > departments
    public function get_model_departments()
    {

        $data['title'] = $this->Core_model->site_title();
        $id = $this->uri->segment(4);

        $data = array(
            'company_id' => $id
        );
        $session = $this->session->userdata('username');
        if (!empty($session)) {
            $this->load->view("admin/designation/get_model_departments", $data);
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
            $this->load->view("admin/designation/get_model_workstations", $data);
        } else {
            redirect('admin/');
        }
        // Datatables Variables
        $draw = intval($this->input->get("draw"));
        $start = intval($this->input->get("start"));
        $length = intval($this->input->get("length"));
    }

    // get main department > sub departments
    public function get_sub_departments()
    {

        $data['title'] = $this->Core_model->site_title();
        $id = $this->uri->segment(4);

        $data = array(
            'department_id' => $id
        );
        $session = $this->session->userdata('username');
        if (!empty($session)) {
            $this->load->view("admin/designation/get_subdepartments", $data);
        } else {
            redirect('admin/');
        }
        // Datatables Variables
        $draw = intval($this->input->get("draw"));
        $start = intval($this->input->get("start"));
        $length = intval($this->input->get("length"));
    }

    // get main department > sub departments
    public function get_sub_departments_modal()
    {

        $data['title'] = $this->Core_model->site_title();
        $id = $this->uri->segment(4);

        $data = array(
            'department_id' => $id
        );
        $session = $this->session->userdata('username');
        if (!empty($session)) {
            $this->load->view("admin/designation/get_subdepartments", $data);
        } else {
            redirect('admin/');
        }
        // Datatables Variables
        $draw = intval($this->input->get("draw"));
        $start = intval($this->input->get("start"));
        $length = intval($this->input->get("length"));
    }

    // get departmens > designations
    public function topdesignation()
    {

        $data['title'] = $this->Core_model->site_title();
        $id = $this->uri->segment(4);

        $data = array(
            'department_id' => $id,
            'all_designations' => $this->Designation_model->all_designations(),
        );
        $session = $this->session->userdata('username');
        if (!empty($session)) {
            $this->load->view("admin/designation/get_designations", $data);
        } else {
            redirect('admin/');
        }
        // Datatables Variables
        $draw = intval($this->input->get("draw"));
        $start = intval($this->input->get("start"));
        $length = intval($this->input->get("length"));
    }

    // Validate and add info in database
    public function add_designation()
    {

        if ($this->input->post('add_type') == 'designation') {
            // Check validation for user input
            $this->form_validation->set_rules('department_id', 'Department', 'trim|required|xss_clean');
            $this->form_validation->set_rules('designation_name', 'Designation', 'trim|required|xss_clean');

            /* Define return | here result is used to return user data and error for error message */
            $Return = array('result' => '', 'error' => '', 'csrf_hash' => '');
            $Return['csrf_hash'] = $this->security->get_csrf_hash();
            $system = $this->Core_model->read_setting_info(1);
            /* Server side PHP input validation */
            if ($this->input->post('company_id') === '') {
                $Return['error'] = $this->lang->line('error_company_field');
            } else if ($this->input->post('department_id') === '') {
                $Return['error'] = $this->lang->line('error_department_field');
            } else if ($this->input->post('subdepartment_id') === '') {
                $Return['error'] = $this->lang->line('xin_hr_sub_department_field_error');
            } else if ($this->input->post('designation_name') === '') {
                $Return['error'] = $this->lang->line('error_designation_field');
            } else if ($this->input->post('workstation_id') === '') {
                $Return['error'] = $this->lang->line('error_workstation_field');
            }

            if ($Return['error'] != '') {
                $this->output($Return);
            }
            $data = array(
                'department_id'     => $this->input->post('department_id'),
                'sub_department_id' => 0,
                'company_id'        => $this->input->post('company_id'),
                'designation_name'  => $this->input->post('designation_name'),
                'workstation_id'  => $this->input->post('workstation_id'),
                'added_by'          => $this->input->post('user_id'),
                'created_at'        => date('d-m-Y'),
            );
            $result = $this->Designation_model->add($data);
            if ($result == TRUE) {
                $Return['result'] = $this->lang->line('xin_success_add_designation');
            } else {
                $Return['error'] = $this->lang->line('xin_error_msg');
            }
            $this->output($Return);
            exit;
        }
    }

    // Validate and update info in database
    public function update()
    {

        if ($this->input->post('edit_type') == 'designation') {

            $id = $this->uri->segment(4);

            // Check validation for user input
            $this->form_validation->set_rules('department_id', 'Department', 'trim|required|xss_clean');
            $this->form_validation->set_rules('designation_name', 'Designation', 'trim|required|xss_clean');

            /* Define return | here result is used to return user data and error for error message */
            $Return = array('result' => '', 'error' => '', 'csrf_hash' => '');
            $Return['csrf_hash'] = $this->security->get_csrf_hash();
            $system = $this->Core_model->read_setting_info(1);
            /* Server side PHP input validation */
            if ($this->input->post('company_id') === '') {
                $Return['error'] = $this->lang->line('error_company_field');
            } else if ($this->input->post('department_id') === '') {
                $Return['error'] = $this->lang->line('error_department_field');
            } else if ($this->input->post('subdepartment_id') === '') {
                $Return['error'] = $this->lang->line('xin_hr_sub_department_field_error');
            } else if ($this->input->post('designation_name') === '') {
                $Return['error'] = $this->lang->line('error_designation_field');
            } else if ($this->input->post('workstation_id') === '') {
                $Return['error'] = $this->lang->line('error_workstation_field');
            }

            if ($Return['error'] != '') {
                $this->output($Return);
            }
            $data = array(
                'department_id'     => $this->input->post('department_id'),
                'sub_department_id' => 0,
                'company_id'        => $this->input->post('company_id'),
                'designation_name'  => $this->input->post('designation_name'),
                'workstation_id'  => $this->input->post('workstation_id'),
            );
            $result = $this->Designation_model->update_record($data, $id);

            if ($result == TRUE) {
                $Return['result'] = $this->lang->line('xin_success_update_designation');
            } else {
                $Return['error'] = $this->lang->line('xin_error_msg');
            }
            $this->output($Return);
            exit;
        }
    }

    public function delete()
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

            $jum_karyawan = $this->Employees_model->get_total_employees_designation($id);

            if ($jum_karyawan[0]->jumlah != 0) {

                $Return['result'] = 'Tidak Bisa Hapus karena Masih ada ' . $jum_karyawan[0]->jumlah . ' Karyawan yang terhubung dengan posisi ini';
            } else {

                $jum_karyawan_gaji_harian = $this->Employees_model->get_total_employees_designation_gaji_harian($id);

                if ($jum_karyawan_gaji_harian[0]->jumlah != 0) {

                    $Return['result'] = 'Tidak Bisa Hapus karena Masih ada ' . $jum_karyawan_gaji_harian[0]->jumlah . ' Gaji Harian yang terhubung dengan posisi ini';
                } else {

                    $jum_karyawan_gaji_month = $this->Employees_model->get_total_employees_designation_gaji_month($id);

                    if ($jum_karyawan_gaji_month[0]->jumlah != 0) {

                        $Return['result'] = 'Tidak Bisa Hapus karena Masih ada ' . $jum_karyawan_gaji_month[0]->jumlah . ' Gaji Bulanan yang terhubung dengan posisi ini';
                    } else {

                        $result = $this->Designation_model->delete_record($id);
                        if (isset($id)) {
                            $Return['result'] = $this->lang->line('xin_success_delete_designation');
                        } else {
                            $Return['error'] = $this->lang->line('xin_error_msg');
                        }
                    }
                }
            }


            $this->output($Return);
        }
    }
}
