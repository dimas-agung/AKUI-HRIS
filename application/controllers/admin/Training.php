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
 * @property Finance_model $Finance_model
 * @property Training_model $Training_model
 * @property Trainers_model $Trainers_model
 * @property Employees_model $Employees_model
 * @property Designation_model $Designation_model
 */
class Training extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();
        //load the model
        $this->load->model("Training_model");
        $this->load->model("Core_model");
        $this->load->model("Trainers_model");
        $this->load->model("Designation_model");
        $this->load->model("Department_model");
        $this->load->model("Custom_fields_model");
        $this->load->model("Company_model");
        $this->load->model("Finance_model");
        $this->load->model("Employees_model");
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
        $data['title']          = 'Proses Pelatihan | ' . $this->Core_model->site_title();
        $data['icon']           = '<i class="fa fa-graduation-cap"></i>';
        $data['breadcrumbs']    = 'Proses Pelatihan';
        $data['desc']           = 'PROSES : Input Pelatihan';
        $data['path_url']       = 'training';

        $data['all_employees']  = $this->Core_model->all_employees();
        $data['all_trainers']   = $this->Trainers_model->all_trainers();
        $data['all_companies']  = $this->Company_model->get_company();

        $data['all_training_types'] = $this->Training_model->all_training_types();
        $role_resources_ids = $this->Core_model->user_role_resource();
        if (in_array('54', $role_resources_ids)) {
            if (!empty($session)) {
                $data['subview'] = $this->load->view("admin/training/training_list", $data, TRUE);
                $this->load->view('admin/layout/layout_main', $data); //page load
            } else {
                redirect('admin/');
            }
        } else {
            redirect('admin/dashboard');
        }
    }

    public function training_list()
    {
        $data['title'] = $this->Core_model->site_title();
        $session = $this->session->userdata('username');
        if (!empty($session)) {
            $this->load->view("admin/training/training_list", $data);
        } else {
            redirect('admin/');
        }
        // Datatables Variables
        $draw = intval($this->input->get("draw"));
        $start = intval($this->input->get("start"));
        $length = intval($this->input->get("length"));


        $role_resources_ids = $this->Core_model->user_role_resource();
        $user_info = $this->Core_model->read_user_info($session['user_id']);

        $training = $this->Training_model->get_training();

        $data = array();

        foreach ($training->result() as $r) {
            $aim = explode(',', $r->employee_id);
            // get training type
            $type = $this->Training_model->read_training_type_information($r->training_type_id);
            if (!is_null($type)) {
                $itype = $type[0]->type;
                $ktype = $type[0]->kategori;
            } else {
                $itype = '--';
                $ktype = $type[0]->type;
            }
            // get trainer
            if ($r->trainer_option == 2) {
                $trainer = $this->Trainers_model->read_trainer_information($r->trainer_id);
                // trainer full name
                if (!is_null($trainer)) {
                    $trainer_name = $trainer[0]->first_name . ' ' . $trainer[0]->last_name;
                    $trainer_company = $trainer[0]->company_id;
                } else {
                    $trainer_name = '--';
                    $trainer_company = '--';
                }
            } elseif ($r->trainer_option == 1) {
                // get user > employee_
                $trainer = $this->Core_model->read_user_info($r->trainer_id);
                // employee full name
                if (!is_null($trainer)) {
                    $trainer_name = $trainer[0]->first_name . ' ' . $trainer[0]->last_name;
                } else {
                    $trainer_name = '--';
                }
            } else {
                $trainer_name = '--';
            }

            $company = $this->Core_model->read_vendor_info($trainer_company);
            if (!is_null($company)) {
                $comp_name = $company[0]->name;
            } else {
                $comp_name = '--';
            }

            // get start date
            $start_date = $this->Core_model->set_date_format($r->start_date);
            // get end date
            $finish_date = $this->Core_model->set_date_format($r->finish_date);
            // training date
            $training_date = $start_date . ' ' . $this->lang->line('dashboard_to') . ' ' . $finish_date;
            // set currency
            $training_cost = $this->Core_model->currency_sign($r->training_cost);

            $cek_kategori = $this->Training_model->read_kategori_information($ktype);
            if (!is_null($cek_kategori)) {
                $kategori_name = $cek_kategori[0]->type;
            } else {
                $kategori_name = '<span class="badge bg-red"> ? </span>';
            }

            /* get Employee info*/
            if ($r->employee_id == '') {
                $ol = '--';
            } else {
                $ol = '<small class="text-muted"><ol class="nl">';
                foreach (explode(',', $r->employee_id) as $uid) {
                    $user = $this->Core_model->read_user_info($uid);
                    if (!is_null($user)) {

                        $cek_ikut = $this->Training_model->read_status_ikut($user[0]->user_id, $r->start_date, $r->finish_date, $r->training_type_id);
                        if (!is_null($cek_ikut)) {
                            $status_ikut = $cek_ikut[0]->jumlah;
                        } else {
                            $status_ikut = '';
                        }

                        if ($status_ikut == 0) {
                            $ikut = '<span class="badge bg-red"><i class="fa fa-minus-circle"></i></span>';
                        } else {
                            $ikut = '<span class="badge bg-green"><i class="fa fa-check-circle"></i></span>';
                        }

                        $designation = $this->Designation_model->read_designation_information($user[0]->designation_id);
                        if (!is_null($designation)) {
                            $designation_name = $designation[0]->designation_name;
                        } else {
                            $designation_name = '<span class="badge bg-red"> ? </span>';
                        }

                        $ol .= '<li> ' . $ikut . ' ' . $user[0]->first_name . ' ' . $user[0]->last_name . ' (' . $designation_name . ')</li>';
                    } else {
                        $ol .= '--';
                    }
                }
                $ol .= '</ol></small>';
            }
            // status
            //if($r->training_status==0): $status = $this->lang->line('xin_pending');
            //elseif($r->training_status==1): $status = $this->lang->line('xin_started'); elseif($r->training_status==2): $status = $this->lang->line('xin_completed');
            //else: $status = $this->lang->line('xin_terminated'); endif;
            if ($r->training_status == 0) : $status = '<span class="badge bg-orange">' . $this->lang->line('xin_pending') . '</span>';
            elseif ($r->training_status == 1) : $status = '<span class="badge bg-teal">' . $this->lang->line('xin_started') . '</span>';
            elseif ($r->training_status == 2) : $status = '<span class="badge bg-green">' . $this->lang->line('xin_completed') . '</span>';
            else : $status = '<span class="badge bg-red">' . $this->lang->line('xin_terminated') . '</span>';
            endif;
            // get company
            $company = $this->Core_model->read_company_info($r->company_id);
            if (!is_null($company)) {
                $comp_name = $company[0]->name;
            } else {
                $comp_name = '--';
            }

            if (in_array('342', $role_resources_ids)) { //edit
                $edit = '<span data-toggle="tooltip" data-placement="top" title="Aktifkan Pelatihan">
                                <button type="button" class="btn icon-btn btn-xs btn-default waves-effect waves-light"  data-toggle="modal" data-target=".edit-modal-data"  data-training_id="' . $r->training_id . '">
                                        <span class="fa fa-check-circle"></span> Aktifkan
                                </button>
                        </span>';
            } else {
                $edit = '';
            }

            if (in_array('344', $role_resources_ids)) { //view
                $view = '<span data-toggle="tooltip" data-placement="top" title="' . $this->lang->line('xin_view_details') . '">
                                <a href="' . site_url() . 'admin/training/details/' . $r->training_id . '">
                                    <button type="button" class="btn icon-btn btn-xs btn-default waves-effect waves-light">
                                        <span class="fa fa-gavel"></span> Status
                                    </button>
                                </a>
                        </span>';
            } else {
                $view = '';
            }

            if (in_array('343', $role_resources_ids)) { // delete
                $delete = '<span data-toggle="tooltip" data-placement="top" title="' . $this->lang->line('xin_delete') . '">
                                <button type="button" class="btn icon-btn btn-xs btn-danger waves-effect waves-light delete" data-toggle="modal" data-target=".delete-modal" data-record-id="' . $r->training_id . '">
                                        <span class="fa fa-times"></span>
                                </button>
                            </span>';
            } else {
                $delete = '';
            }

            $combhr = $edit . $view . $delete;
            $iitype = $itype;

            $data[] = array(
                $combhr,
                $status,
                $training_date,
                $iitype . '<br><i class="fa fa-user"></i> : ' . $trainer_name . '<br><i class="fa fa-building"></i> : ' . $comp_name,

                'Kategori : ' . $kategori_name . '<br> <i class="fa fa-users"></i> Peserta :<br>' . $ol,
                // $comp_name,



            );
        }

        $output = array(
            "draw" => $draw,
            "recordsTotal" => $training->num_rows(),
            "recordsFiltered" => $training->num_rows(),
            "data" => $data
        );
        echo json_encode($output);
        exit();
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
            $this->load->view("admin/training/get_employees", $data);
        } else {
            redirect('admin/');
        }
        // Datatables Variables
        $draw = intval($this->input->get("draw"));
        $start = intval($this->input->get("start"));
        $length = intval($this->input->get("length"));
    }

    public function read()
    {
        $data['title'] = $this->Core_model->site_title();
        $id = $this->input->get('training_id');
        $result = $this->Training_model->read_training_information($id);
        $data = array(
            'title' => $this->Core_model->site_title(),


            'company_id'       => $result[0]->company_id,
            'training_id'      => $result[0]->training_id,
            'employee_id'      => $result[0]->employee_id,
            'training_type_id' => $result[0]->training_type_id,
            'trainer_id'       => $result[0]->trainer_id,
            'trainer_option'   => $result[0]->trainer_option,
            'start_date'       => $result[0]->start_date,
            'finish_date'      => $result[0]->finish_date,
            'training_status'  => $result[0]->training_status,
            'description'      => $result[0]->description,
            'performance'      => $result[0]->performance,
            'remarks'          => $result[0]->remarks,

            'all_employees' => $this->Core_model->all_employees(),
            'all_training_types' => $this->Training_model->all_training_types(),
            'all_trainers' => $this->Trainers_model->all_trainers(),
            'all_companies' => $this->Company_model->get_company()
        );

        $session = $this->session->userdata('username');
        if (!empty($session)) {
            $this->load->view('admin/training/dialog_training', $data);
        } else {
            redirect('admin/');
        }
    }

    // Validate and add info in database
    public function add_training()
    {
        if ($this->input->post('add_type') == 'training') {
            /* Define return | here result is used to return user data and error for error message */
            $Return = array('result' => '', 'error' => '', 'csrf_hash' => '');
            $Return['csrf_hash'] = $this->security->get_csrf_hash();

            /* Server side PHP input validation */
            $start_date = $this->input->post('start_date');
            $end_date = $this->input->post('end_date');
            $description = $this->input->post('description');
            $st_date = strtotime($start_date);
            $ed_date = strtotime($end_date);
            $qt_description = htmlspecialchars(addslashes($description), ENT_QUOTES);

            if ($this->input->post('company') === '') {
                $Return['error'] = $this->lang->line('error_company_field');
            } else if ($this->input->post('trainer_option') === '') {
                $Return['error'] = $this->lang->line('xin_trainer_opt_error_field');
            } else if ($this->input->post('training_type') === '') {
                $Return['error'] = $this->lang->line('xin_error_training_type');
            } else if ($this->input->post('trainer') === '') {
                $Return['error'] = $this->lang->line('xin_error_trainer_field');
            } else if ($this->input->post('start_date') === '') {
                $Return['error'] = $this->lang->line('xin_error_start_date');
            } else if ($this->input->post('end_date') === '') {
                $Return['error'] = $this->lang->line('xin_error_end_date');
            } else if ($st_date > $ed_date) {
                $Return['error'] = $this->lang->line('xin_error_start_end_date');
            } else if ($this->input->post('employee_id') === '') {
                $Return['error'] = $this->lang->line('xin_error_employee_id');
            }

            if ($Return['error'] != '') {
                $this->output($Return);
            }


            $employee_ids = implode(',', $_POST['employee_id']);
            $employee_id = $employee_ids;

            if (isset($_POST['employee_id'])) {
                $employee_ids = implode(',', $_POST['employee_id']);
                $employee_id = $employee_ids;
            } else {
                $employee_id = '';
            }




            $data = array(
                'training_type_id' => $this->input->post('training_type'),
                'company_id'       => $this->input->post('company'),
                'trainer_id'       => $this->input->post('trainer'),
                'trainer_option'   => $this->input->post('trainer_option'),
                'start_date'       => $this->input->post('start_date'),
                'finish_date'      => $this->input->post('end_date'),
                'employee_id'      => $employee_id,
                'description'      => $qt_description,
                'created_at'       => date('d-m-Y h:i:s')
            );
            $iresult = $this->Training_model->add_training($data);
            if ($iresult) {
                $Return['result'] = 'Proses Pelatihan Baru Berhasil Ditambahkan, silahkan lakukan proses aktifasi pelatihan';
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
            $start_date = $this->input->post('start_date');
            $end_date = $this->input->post('end_date');
            $description = $this->input->post('description');
            $st_date = strtotime($start_date);
            $ed_date = strtotime($end_date);
            $qt_description = htmlspecialchars(addslashes($description), ENT_QUOTES);

            if ($this->input->post('company') === '') {
                $Return['error'] = $this->lang->line('error_company_field');
            } else if ($this->input->post('training_type') === '') {
                $Return['error'] = $this->lang->line('xin_error_training_type');
            } else if ($this->input->post('trainer') === '') {
                $Return['error'] = $this->lang->line('xin_error_trainer_field');
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

            if (isset($_POST['employee_id'])) {
                $employee_ids = implode(',', $_POST['employee_id']);
                $employee_id = $employee_ids;
            } else {
                $employee_id = '';
            }

            $start_date       = $this->input->post('start_date');
            $end_date         = $this->input->post('end_date');
            $training_type_id = $this->input->post('training_type');
            $trainer          = $this->input->post('trainer');

            $data = array(
                'training_type_id' => $this->input->post('training_type'),
                'company_id' => $this->input->post('company'),
                'trainer_id' => $this->input->post('trainer'),
                'start_date' => $this->input->post('start_date'),
                'finish_date' => $this->input->post('end_date'),
                'employee_id' => $employee_id,
                'description' => $qt_description
            );

            $result = $this->Training_model->update_record($data, $id);

            if ($result == TRUE) {

                $Return['result'] = 'Proses Pelatihan Berhasil Diaktifkan';

                foreach (explode(',', $employee_id) as $uid) {
                    $user = $this->Employees_model->read_employee_information($uid);
                    if (!is_null($user)) {

                        $result = $this->Training_model->delete_training_employee($uid, $start_date, $end_date, $training_type_id, $trainer);

                        $data_training   = array(

                            'employee_id'            => $uid,
                            'start_date'             => $this->input->post('start_date'),
                            'end_date'               => $this->input->post('end_date'),
                            'training_type_id'       => $this->input->post('training_type'),
                            'trainer_id'             => $this->input->post('trainer'),
                            'created_at'             => date('Y-m-d h:i:s')

                        );
                        $training_result = $this->Training_model->add_training_employee($data_training);
                    }
                }
            } else {
                $Return['error'] = $this->lang->line('xin_error_msg');
            }
            $this->output($Return);
            exit;
        }
    }

    // training details
    public function details()
    {
        $session = $this->session->userdata('username');
        if (empty($session)) {
            redirect('admin/');
        }
        $data['title'] = $this->Core_model->site_title();
        $id = $this->uri->segment(4);
        $result = $this->Training_model->read_training_information($id);
        if (is_null($result)) {
            redirect('admin/training');
        }
        // get training type
        $type = $this->Training_model->read_training_type_information($result[0]->training_type_id);
        if (!is_null($type)) {
            $itype = $type[0]->type;
        } else {
            $itype = '--';
        }

        if ($result[0]->trainer_option == 2) {
            // get trainer
            $trainer = $this->Trainers_model->read_trainer_information($result[0]->trainer_id);
            // trainer full name
            if (!is_null($trainer)) {
                $trainer_name = $trainer[0]->first_name . ' ' . $trainer[0]->last_name;
            } else {
                $trainer_name = '--';
            }
        } elseif ($result[0]->trainer_option == 1) {
            // get user > employee_
            $trainer = $this->Core_model->read_user_info($result[0]->trainer_id);
            // employee full name
            if (!is_null($trainer)) {
                $trainer_name = $trainer[0]->first_name . ' ' . $trainer[0]->last_name;
            } else {
                $trainer_name = '--';
            }
        } else {
            $trainer_name = '--';
        }


        $data = array(
            'title'       => 'Status Pelatiha',
            'training_id' => $result[0]->training_id,
            'company_id' => $result[0]->company_id,
            'type' => $itype,
            'trainer_name' => $trainer_name,
            'training_cost' => $result[0]->training_cost,
            'start_date' => $result[0]->start_date,
            'finish_date' => $result[0]->finish_date,
            'created_at' => $result[0]->created_at,
            'description' => $result[0]->description,
            'performance' => $result[0]->performance,
            'training_status' => $result[0]->training_status,
            'remarks' => $result[0]->remarks,
            'employee_id' => $result[0]->employee_id,
            'all_employees' => $this->Core_model->all_employees(),
            'all_companies' => $this->Company_model->get_company()
        );

        $data['icon']           = '<i class="fa fa-eye"></i>';
        $data['breadcrumbs']    = 'Status Pelatihan';
        $data['desc']           = 'PROSES : Status Pelatihan';

        $data['path_url'] = 'training_details';
        $role_resources_ids = $this->Core_model->user_role_resource();
        if (in_array('54', $role_resources_ids)) {
            if (!empty($session)) {
                $data['subview'] = $this->load->view("admin/training/training_details", $data, TRUE);
                $this->load->view('admin/layout/layout_main', $data); //page load
            } else {
                redirect('admin/');
            }
        } else {
            redirect('admin/dashboard');
        }
    }

    // Validate and update info in database
    public function update_status()
    {
        if ($this->input->post('edit_type') == 'update_status') {

            $id = $this->input->post('token_status');
            /* Define return | here result is used to return user data and error for error message */
            $Return = array('result' => '', 'error' => '', 'csrf_hash' => '');
            $Return['csrf_hash'] = $this->security->get_csrf_hash();

            $data = array(
                'performance' => $this->input->post('performance'),
                'training_status' => $this->input->post('status'),
                'remarks' => $this->input->post('remarks')
            );

            $result = $this->Training_model->update_status($data, $id);
            if ($this->input->post('status') == 2) {
                $system_settings = system_settings_info(1);
                if ($system_settings->online_payment_account == '') {
                    $online_payment_account = 0;
                } else {
                    $online_payment_account = $system_settings->online_payment_account;
                }
                $tr_info = $this->Training_model->read_training_information($id);

                $ivdata = array(

                    'account_id' => $online_payment_account,
                    'transaction_type' => 'expense',
                    'dr_cr' => 'cr',
                    'transaction_date' => date('Y-m-d'),
                    'payer_payee_id' => $tr_info[0]->employee_id,
                    'payment_method_id' => 3,
                    'description' => 'Training Cost',
                    'reference' => 'Training Cost',
                    'invoice_id' => $id,
                    'client_id' => $tr_info[0]->employee_id,
                    'created_at' => date('Y-m-d H:i:s')
                );
                $this->Finance_model->add_transactions($ivdata);
            }

            if ($result == TRUE) {
                $Return['result'] = $this->lang->line('xin_success_training_status_updated');
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
        $result = $this->Training_model->delete_record($id);
        if (isset($id)) {
            $Return['result'] = 'Proses Pelatihan Berhasil Dihapus';;
        } else {
            $Return['error'] = $this->lang->line('xin_error_msg');
        }
        $this->output($Return);
    }

    // get company > locations
    public function get_all_trainers()
    {
        $data['title'] = $this->Core_model->site_title();
        $id = 1;
        $data = array(
            'hris' => $id
        );
        $session = $this->session->userdata('username');
        if (!empty($session)) {
            $data = $this->security->xss_clean($data);
            $this->load->view("admin/training/get_all_trainers", $data);
        } else {
            redirect('admin/');
        }
        // Datatables Variables
        $draw = intval($this->input->get("draw"));
        $start = intval($this->input->get("start"));
        $length = intval($this->input->get("length"));
    }

    // get company > employees
    public function get_internal_employee()
    {
        $data['title'] = $this->Core_model->site_title();
        $id = $this->uri->segment(4);

        $data = array(
            'company_id' => $id
        );
        $session = $this->session->userdata('username');
        if (!empty($session)) {
            $this->load->view("admin/training/get_internal_employee", $data);
        } else {
            redirect('admin/');
        }
        // Datatables Variables
        $draw = intval($this->input->get("draw"));
        $start = intval($this->input->get("start"));
        $length = intval($this->input->get("length"));
    }
}
