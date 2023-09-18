<?php

/**
 * NOTICE OF LICENSE
 *
 * This source file is subject to the PortalHR License
 * that is bundled with this package in the file license.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.karyasoftware.com
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to hris@karyasoftware.com so we can send you a copy immediately.
 *
 * @author   Nizar Basyrewan
 * @author-email  hris@karyasoftware.com
 * @copyright  Copyright © karyasoftware.com. All Rights Reserved
 */
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * @property Training_model $Training_model
 */
class Training_type extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();
        //load the model
        $this->load->model("Training_model");
        $this->load->model("Core_model");
        $this->load->model("Trainers_model");
        $this->load->model("Designation_model");
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

    public function index()
    {
        $session = $this->session->userdata('username');
        if (empty($session)) {
            redirect('admin/');
        }
        $system = $this->Core_model->read_setting_info(1);
        if ($system[0]->module_training != 'true') {
            redirect('admin/dashboard');
        }
        $data['title']           = 'Jenis Pelatihan | ' . $this->Core_model->site_title();
        $data['desc']            = 'INPUT : Jenis Pelatihan';
        $data['icon']            = '<i class="fa fa-tasks"></i>';
        $data['breadcrumbs']     = 'Jenis Pelatihan';
        $data['path_url']        = 'training_type';

        $role_resources_ids = $this->Core_model->user_role_resource();
        if (in_array('55', $role_resources_ids)) {
            if (!empty($session)) {
                $data['subview'] = $this->load->view("admin/training/training_type", $data, TRUE);
                $this->load->view('admin/layout/layout_main', $data); //page load
            } else {
                redirect('admin/');
            }
        } else {
            redirect('admin/dashboard');
        }
    }

    public function type_list()
    {

        $data['title'] = $this->Core_model->site_title();
        $session = $this->session->userdata('username');
        if (!empty($session)) {
            $this->load->view("admin/training/training_type", $data);
        } else {
            redirect('admin/');
        }
        // Datatables Variables
        $draw = intval($this->input->get("draw"));
        $start = intval($this->input->get("start"));
        $length = intval($this->input->get("length"));

        $role_resources_ids = $this->Core_model->user_role_resource();
        $user_info = $this->Core_model->read_user_info($session['user_id']);

        $company = $this->Company_model->get_training_kategori();

        $data = array();
        $no = 1;

        foreach ($company->result() as $r) {

            $icname = $r->type;

            $jenis_pelatihan = '';

            $sql_jenis_pelatihan = " SELECT *
                        FROM
                             xin_training_types
                        WHERE
                            1 = 1
                        AND kategori  = '" . $r->training_type_id . "'								
                        ORDER BY type ASC";

            // echo "<pre>";
            // print_r( $sql_jenis_pelatihan );
            // echo "</pre>";
            // die;

            $query_jenis_pelatihan = $this->db->query($sql_jenis_pelatihan);

            if ($query_jenis_pelatihan->num_rows() > 0) {


                $daftar_jenis_pelatihan = '<table class="datatables-demo table table-striped table-bordered" id="xin_table">
                            <thead>
                              <tr>
                                <th class="text-center" width="50px">No.</th>			                
                                <th class="text-center"> Jenis </th>
                                <th class="text-center" width="100px"> Aksi</th>
                              </tr>
                            </thead>
                            <tbody>';
                $mo = 1;
                foreach ($query_jenis_pelatihan->result() as $row_jenis_pelatihan) :


                    if (in_array('552', $role_resources_ids)) { //edit
                        $edit = '<span data-toggle="tooltip" data-placement="top" title="' . $this->lang->line('xin_edit') . '">
                                                <button type="button" class="btn icon-btn btn-xs btn-default waves-effect waves-light"  data-toggle="modal" data-target="#edit-modal-data-min"  data-training_type_id="' . $row_jenis_pelatihan->training_type_id . '">
                                                    <span class="fa fa-pencil"></span> Edit 
                                                </button>
                                            </span></span>';
                    } else {
                        $edit = '';
                    }

                    if (in_array('553', $role_resources_ids)) { // delete
                        $delete = '<span data-toggle="tooltip" data-placement="top" title="' . $this->lang->line('xin_delete') . '">
                                                    <button type="button" class="btn icon-btn btn-xs btn-danger waves-effect waves-light delete" data-toggle="modal" data-target=".delete-modal" data-record-id="' . $row_jenis_pelatihan->training_type_id . '">
                                                        <span class="fa fa-times"></span>
                                                    </button>
                                                </span>';
                    } else {
                        $delete = '';
                    }

                    $ijenis_pelatihan_name = strtoupper($row_jenis_pelatihan->type);


                    $daftar_jenis_pelatihan = $daftar_jenis_pelatihan . ' 
                                        <tr">
                                            <td width="2%" align="center">' . $mo . '.</td>
                                            
                                            <td align="left">
                                             ' . $ijenis_pelatihan_name . ' 
                                            </td>				                                    
                                            
                                            
                                            <td width="8%" align="center">
                                             ' . $edit . $delete . ' 
                                            </td>
                                        </tr>';
                    $mo++;
                endforeach;

                $daftar_jenis_pelatihan = $daftar_jenis_pelatihan . '
                            </tbody>
                            </table>';
            } else {

                $daftar_jenis_pelatihan = '<div class="warning-msg" style="padding:5px;">
                                <i class ="fa fa-question-circle"></i> Tidak Ada Jenis Pelatihan		                      
                             </div>';
            }

            $data[] = array(
                $no,
                $icname,
                $daftar_jenis_pelatihan

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




    public function read()
    {
        $data['title'] = $this->Core_model->site_title();
        $id = $this->input->get('training_type_id');
        $result = $this->Training_model->read_training_type_information($id);
        $data = array(
            'training_type_id' => $result[0]->training_type_id,
            'type'             => $result[0]->type,
            'kategori'         => $result[0]->kategori,
            'all_kategori' => $this->Company_model->get_training_kategori_combo()
        );
        $session = $this->session->userdata('username');
        if (!empty($session)) {
            $this->load->view('admin/training/dialog_training_type', $data);
        } else {
            redirect('admin/');
        }
    }

    // Validate and add info in database
    public function add_type()
    {

        if ($this->input->post('add_type') == 'training') {
            /* Define return | here result is used to return user data and error for error message */
            $Return = array('result' => '', 'error' => '', 'csrf_hash' => '');
            $Return['csrf_hash'] = $this->security->get_csrf_hash();

            /* Server side PHP input validation */
            if ($this->input->post('type_name') == '') {
                $Return['error'] = $this->lang->line('xin_error_training_type_name');
            } else if ($this->input->post('type_kategori') == '') {
                $Return['error'] = 'kategori Pelatihan wajib diisi';
            }

            if ($Return['error'] != '') {
                $this->output($Return);
            }

            $data = array(
                'type' => $this->input->post('type_name'),
                'kategori' => $this->input->post('type_kategori'),
                'created_at' => date('d-m-Y h:i:s')
            );
            $result = $this->Training_model->add_type($data);
            if ($result == TRUE) {
                $Return['result'] = $this->lang->line('xin_success_training_type_added');
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

        if ($this->input->post('edit_type') == 'training') {

            $id = $this->uri->segment(4);

            /* Define return | here result is used to return user data and error for error message */
            $Return = array('result' => '', 'error' => '', 'csrf_hash' => '');
            $Return['csrf_hash'] = $this->security->get_csrf_hash();

            /* Server side PHP input validation */
            if ($this->input->post('type_name') === '') {
                $Return['error'] = $this->lang->line('xin_error_training_type_name');
            } else if ($this->input->post('kategori') === '') {
                $Return['error'] = 'kategori Pelatihan wajib diisi';
            }

            if ($Return['error'] != '') {
                $this->output($Return);
            }

            $data = array(
                'type' => $this->input->post('type_name'),
                'kategori' => $this->input->post('kategori')
            );

            $result = $this->Training_model->update_type_record($data, $id);

            if ($result == TRUE) {
                $Return['result'] = $this->lang->line('xin_success_training_type_updated');
            } else {
                $Return['error'] = $this->lang->line('xin_error_msg');
            }
            $this->output($Return);
            exit;
        }
    }

    public function delete()
    {
        /* Define return | here result is used to return user data and error for error message */
        $Return = array('result' => '', 'error' => '', 'csrf_hash' => '');
        $id = $this->uri->segment(4);
        $Return['csrf_hash'] = $this->security->get_csrf_hash();
        $result = $this->Training_model->delete_type_record($id);
        if (isset($id)) {
            $Return['result'] = $this->lang->line('xin_success_training_type_deleted');
        } else {
            $Return['error'] = $this->lang->line('xin_error_msg');
        }
        $this->output($Return);
    }
}
