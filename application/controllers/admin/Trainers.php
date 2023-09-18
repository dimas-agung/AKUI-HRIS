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

class Trainers extends MY_Controller
{

	public function __construct()
	{
		parent::__construct();
		//load the model
		$this->load->model("Trainers_model");
		$this->load->model("Core_model");
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
		$data['title'] = 'Pelatih Pelatihan | ' . $this->Core_model->site_title();

		$data['desc']             = 'INPUT : Pelatih';
		$data['icon']             = '<i class="fa fa-user"></i>';
		$data['breadcrumbs']      = 'Pelatih Pelatihan';
		$data['path_url']         = 'trainers';

		$data['all_designations'] = $this->Designation_model->all_designations();
		$data['all_vendors']    = $this->Company_model->get_vendor();

		$role_resources_ids = $this->Core_model->user_role_resource();
		if (in_array('56', $role_resources_ids)) {
			if (!empty($session)) {
				$data['subview'] = $this->load->view("admin/trainers/trainer_list", $data, TRUE);
				$this->load->view('admin/layout/layout_main', $data); //page load
			} else {
				redirect('admin/');
			}
		} else {
			redirect('admin/dashboard');
		}
	}

	public function trainer_list()
	{

		$data['title'] = $this->Core_model->site_title();
		$session = $this->session->userdata('username');
		if (!empty($session)) {
			$this->load->view("admin/trainers/trainer_list", $data);
		} else {
			redirect('admin/');
		}
		// Datatables Variables
		$draw = intval($this->input->get("draw"));
		$start = intval($this->input->get("start"));
		$length = intval($this->input->get("length"));

		$role_resources_ids = $this->Core_model->user_role_resource();
		$user_info = $this->Core_model->read_user_info($session['user_id']);
		if ($user_info[0]->user_role_id == 1) {
			$trainers = $this->Trainers_model->get_trainers();
		} else {
			$trainers = $this->Trainers_model->get_company_trainers($user_info[0]->company_id);
		}
		$data = array();

		foreach ($trainers->result() as $r) {

			// get name
			$full_name = $r->first_name . ' ' . $r->last_name;
			// get company
			$company = $this->Core_model->read_company_info($r->company_id);
			if (!is_null($company)) {
				$comp_name = $company[0]->name;
			} else {
				$comp_name = '--';
			}
			if (in_array('562', $role_resources_ids)) { //edit
				$edit = '<span data-toggle="tooltip" data-placement="top" title="' . $this->lang->line('xin_edit') . '">
							<button type="button" class="btn icon-btn btn-xs btn-default waves-effect waves-light"  data-toggle="modal" data-target=".edit-modal-data"  data-trainer_id="' . $r->trainer_id . '">
								<span class="fa fa-pencil"></span> Edit
							</button>
						</span>';
			} else {
				$edit = '';
			}
			if (in_array('563', $role_resources_ids)) { // delete
				$delete = '<span data-toggle="tooltip" data-placement="top" title="' . $this->lang->line('xin_delete') . '">
								<button type="button" class="btn icon-btn btn-xs btn-danger waves-effect waves-light delete" data-toggle="modal" data-target=".delete-modal" data-record-id="' . $r->trainer_id . '">
									<span class="fa fa-trash"></span>
								</button>
							</span>';
			} else {
				$delete = '';
			}
			if (in_array('564', $role_resources_ids)) { //view
				$view = '<span data-toggle="tooltip" data-placement="top" title="' . $this->lang->line('xin_view') . '">
								<button type="button" class="btn icon-btn btn-xs btn-default waves-effect waves-light" data-toggle="modal" data-target=".view-modal-data" data-trainer_id="' . $r->trainer_id . '">
									<span class="fa fa-eye"></span>
								</button>
							</span>';
			} else {
				$view = '';
			}
			$combhr = $edit . $view . $delete;

			$ifull_name = $full_name;

			$data[] = array(
				$combhr,
				$ifull_name,
				$r->contact_number,
				$r->email,
				html_entity_decode($r->expertise),
				$comp_name
			);
		}

		$output = array(
			"draw" => $draw,
			"recordsTotal" => $trainers->num_rows(),
			"recordsFiltered" => $trainers->num_rows(),
			"data" => $data
		);
		echo json_encode($output);
		exit();
	}

	public function read()
	{
		$data['title'] = $this->Core_model->site_title();
		$id = $this->input->get('trainer_id');
		$result = $this->Trainers_model->read_trainer_information($id);
		$data = array(
			'trainer_id' => $result[0]->trainer_id,
			'company_id' => $result[0]->company_id,
			'first_name' => $result[0]->first_name,
			'last_name' => $result[0]->last_name,
			'contact_number' => $result[0]->contact_number,
			'email' => $result[0]->email,
			'expertise' => $result[0]->expertise,
			'address' => $result[0]->address,
			'all_vendors' => $this->Company_model->get_vendor(),
			'all_designations' => $this->Designation_model->all_designations()
		);
		$session = $this->session->userdata('username');
		if (!empty($session)) {
			$this->load->view('admin/trainers/dialog_trainer', $data);
		} else {
			redirect('admin/');
		}
	}

	// Validate and add info in database
	public function add_trainer()
	{

		if ($this->input->post('add_type') == 'trainer') {
			/* Define return | here result is used to return user data and error for error message */
			$Return = array('result' => '', 'error' => '', 'csrf_hash' => '');
			$Return['csrf_hash'] = $this->security->get_csrf_hash();

			/* Server side PHP input validation */
			$expertise = $this->input->post('expertise');
			$qt_expertise = htmlspecialchars(addslashes($expertise), ENT_QUOTES);
			$address = $this->input->post('address');
			$qt_address = htmlspecialchars(addslashes($address), ENT_QUOTES);

			if ($this->input->post('first_name') === '') {
				$Return['error'] = $this->lang->line('xin_employee_error_first_name');
			} else if ($this->input->post('last_name') === '') {
				$Return['error'] = $this->lang->line('xin_employee_error_last_name');
			} else if ($this->input->post('contact_number') === '') {
				$Return['error'] = $this->lang->line('xin_employee_error_contact_number');

				// } else if($this->input->post('email')==='') {
				//      		$Return['error'] = $this->lang->line('xin_error_cemail_field');

				// } else if (!filter_var($this->input->post('email'), FILTER_VALIDATE_EMAIL)) {
				//   $Return['error'] = $this->lang->line('xin_employee_error_invalid_email');

			} else if ($this->input->post('company') === '') {
				$Return['error'] = $this->lang->line('error_company_field');
			}

			if ($Return['error'] != '') {
				$this->output($Return);
			}

			$data = array(
				'first_name' => $this->input->post('first_name'),
				'last_name' => $this->input->post('last_name'),
				'company_id' => $this->input->post('company') ?: 1,
				'contact_number' => $this->input->post('contact_number'),
				'expertise' => $qt_expertise,
				'address' => $qt_address,
				'email' => $this->input->post('email'),
				'created_at' => date('d-m-Y'),

			);
			$result = $this->Trainers_model->add($data);
			if ($result == TRUE) {
				$Return['result'] = 'Daftar Pelatih Baru Berhasil Ditambahkan';
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

		if ($this->input->post('edit_type') == 'trainer') {

			$id = $this->uri->segment(4);

			/* Define return | here result is used to return user data and error for error message */
			$Return = array('result' => '', 'error' => '', 'csrf_hash' => '');
			$Return['csrf_hash'] = $this->security->get_csrf_hash();

			/* Server side PHP input validation */
			$expertise = $this->input->post('expertise');
			$qt_expertise = htmlspecialchars(addslashes($expertise), ENT_QUOTES);
			$address = $this->input->post('address');
			$qt_address = htmlspecialchars(addslashes($address), ENT_QUOTES);

			if ($this->input->post('first_name') === '') {
				$Return['error'] = $this->lang->line('xin_employee_error_first_name');
			} else if ($this->input->post('last_name') === '') {
				$Return['error'] = $this->lang->line('xin_employee_error_last_name');
			} else if ($this->input->post('contact_number') === '') {
				$Return['error'] = $this->lang->line('xin_employee_error_contact_number');

				// } else if($this->input->post('email')==='') {
				//      		$Return['error'] = $this->lang->line('xin_error_cemail_field');

				// } else if (!filter_var($this->input->post('email'), FILTER_VALIDATE_EMAIL)) {
				//   $Return['error'] = $this->lang->line('xin_employee_error_invalid_email');


			} else if ($this->input->post('company') === '') {
				$Return['error'] = $this->lang->line('error_company_field');
			}

			if ($Return['error'] != '') {
				$this->output($Return);
			}

			$data = array(
				'first_name' => $this->input->post('first_name'),
				'last_name' => $this->input->post('last_name'),
				'company_id' => $this->input->post('company'),
				'contact_number' => $this->input->post('contact_number'),
				'expertise' => $qt_expertise,
				'address' => $qt_address,
				'email' => $this->input->post('email')
			);

			$result = $this->Trainers_model->update_record($data, $id);

			if ($result == TRUE) {
				$Return['result'] = 'Daftar Pelatih Berhasil Diperbaharui';
			} else {
				$Return['error'] = $this->lang->line('xin_error_msg');
			}
			$this->output($Return);
			exit;
		}
	}

	public function delete()
	{

		$session = $this->session->userdata('username');
		if (empty($session)) {
			redirect('admin/');
		}
		/* Define return | here result is used to return user data and error for error message */
		$Return = array('result' => '', 'error' => '', 'csrf_hash' => '');
		$id = $this->uri->segment(4);
		$Return['csrf_hash'] = $this->security->get_csrf_hash();
		$result = $this->Trainers_model->delete_record($id);

		if (isset($id)) {
			$Return['result'] = 'Daftar Pelatih Berhasil Dihapus';
		} else {
			$Return['error'] = $this->lang->line('xin_error_msg');
		}
		$this->output($Return);
	}
}
