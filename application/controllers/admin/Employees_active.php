<?php

/**
 * INFORMASI
 *
 * Nama Aplikasi :  HRIS
 * Email Support :  hris@karyasoftware.com
 * Developer     :  Nizar Basyrewan, S.Si - 0895 606460 731
 * Tahun         :  2020
 * Copyright     :  ©karyasoftware.com. All Rights Reserved
 */

if (!defined('BASEPATH')) exit('No direct script access allowed');

class Employees_active extends MY_Controller
{

	public function __construct()
	{
		parent::__construct();
		//load the models
		$this->load->model("Employees_model");
		$this->load->model("Core_model");
		$this->load->model("Department_model");
		$this->load->model("Designation_model");
		$this->load->model("Roles_model");
		$this->load->model("Location_model");
		$this->load->model("Company_model");
		$this->load->model("Timesheet_model");
		$this->load->model("Custom_fields_model");
		$this->load->model("Assets_model");
		$this->load->model("Training_model");
		$this->load->model("Trainers_model");
		$this->load->model("Company_model");

		$this->load->model("Awards_model");
		$this->load->model("Travel_model");
		$this->load->model("Assets_model");
		$this->load->model("Tickets_model");
		$this->load->model("Transfers_model");
		$this->load->model("Promotion_model");
		$this->load->model("Complaints_model");
		$this->load->model("Warning_model");

		$this->load->model("Payroll_model");
		$this->load->model("Events_model");
		$this->load->model("Meetings_model");
		$this->load->model('Overtime_model');

		$this->load->library("pagination");
		$this->load->library('Pdf');
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

	public function index()
	{
		$session = $this->session->userdata('username');
		if (empty($session)) {
			redirect('admin/');
		}
		$role_resources_ids           = $this->Core_model->user_role_resource();
		$data['title']                = 'Karyawan Aktif | ' . $this->Core_model->site_title();
		$data['icon']                 = '<i class="fa fa-ticket"></i>';
		$data['desc']                 = 'INFORMASI : Daftar Karyawan Aktif Bekerja saat ini';
		$data['breadcrumbs']          = 'Karyawan Aktif';
		$data['path_url']             = 'employees_active';

		$data['all_departments']      = $this->Department_model->all_departments();
		$data['all_designations']     = $this->Designation_model->all_designations();
		$data['all_user_roles']       = $this->Roles_model->all_user_roles();
		$data['all_office_shifts']    = $this->Employees_model->all_office_shifts();
		$data['get_all_companies']    = $this->Company_model->get_company();
		$data['all_leave_types']      = $this->Timesheet_model->all_leave_types();

		// reports to 
		$reports_to = get_reports_team_data($session['user_id']);
		if (in_array('0511', $role_resources_ids) || $reports_to > 0) {
			if (!empty($session)) {
				$data['subview'] = $this->load->view("admin/employees_active/employees_active", $data, TRUE);
				$this->load->view('admin/layout/layout_main', $data); //page load
			} else {
				redirect('admin/');
			}
		} else {
			redirect('admin/dashboard');
		}
	}

	public function employees_list_active()
	{

		$data['title'] = $this->Core_model->site_title();
		$session = $this->session->userdata('username');
		if (!empty($session)) {
			$this->load->view("admin/employees_active/employees_active", $data);
		} else {
			redirect('admin/');
		}
		// Datatables Variables
		$draw   = intval($this->input->get("draw"));
		$start  = intval($this->input->get("start"));
		$length = intval($this->input->get("length"));

		$role_resources_ids = $this->Core_model->user_role_resource();
		$system             = $this->Core_model->read_setting_info(1);

		$user_info = $this->Core_model->read_user_info($session['user_id']);

		if ($this->input->get("ihr") == 'true') {
			if ($this->input->get("company_id") == 0 && $this->input->get("location_id") == 0 && $this->input->get("department_id") == 0 && $this->input->get("designation_id") == 0) {
				$employee = $this->Employees_model->get_employees_active();
			} else if ($this->input->get("company_id") != 0 && $this->input->get("location_id") == 0 && $this->input->get("department_id") == 0 && $this->input->get("designation_id") == 0) {
				$employee = $this->Employees_model->get_company_employees_flt($this->input->get("company_id"));
			} else if ($this->input->get("company_id") != 0 && $this->input->get("location_id") != 0 && $this->input->get("department_id") == 0 && $this->input->get("designation_id") == 0) {
				$employee = $this->Employees_model->get_company_location_employees_flt($this->input->get("company_id"), $this->input->get("location_id"));
			} else if ($this->input->get("company_id") != 0 && $this->input->get("location_id") != 0 && $this->input->get("department_id") != 0 && $this->input->get("designation_id") == 0) {
				$employee = $this->Employees_model->get_company_location_department_employees_flt($this->input->get("company_id"), $this->input->get("location_id"), $this->input->get("department_id"));
			} else if ($this->input->get("company_id") != 0 && $this->input->get("location_id") != 0 && $this->input->get("department_id") != 0 && $this->input->get("designation_id") != 0) {
				$employee = $this->Employees_model->get_company_location_department_designation_employees_flt($this->input->get("company_id"), $this->input->get("location_id"), $this->input->get("department_id"), $this->input->get("designation_id"));
			}
		} else {

			$employee = $this->Employees_model->get_employees_active();
		}
		$data = array();

		$no = 1;

		foreach ($employee->result() as $r) {

			// get company
			$company = $this->Core_model->read_company_info($r->company_id);
			if (!is_null($company)) {
				$comp_name = $company[0]->name;
			} else {
				$comp_name = '<span class="badge bg-red"> ? </span>';
			}
			/* get Employee info*/
			if ($r->view_company_id == '') {
				$vc = '--';
			} else {
				$vc = '<ol class="nl">';
				foreach (explode(',', $r->view_company_id) as $uid) {
					$user = $this->Core_model->read_view_company_info($uid);
					if (!is_null($user)) {
						$vc .= '<li>' . $user[0]->name . '</li>';
					} else {
						$vc .= '--';
					}
				}
				$vc .= '</ol>';
			}


			// user full name 
			$full_name = $r->first_name . ' ' . $r->last_name;

			// PIN		
			$employment_pin = $r->employee_pin;
			if ($employment_pin != '') {

				$emp_pin = $r->employee_pin;
			} else {
				$emp_pin = '<span class="badge bg-red"> ? </span>';
			}

			// jenis gaji
			$wages_type = $this->Core_model->read_user_jenis_gaji($r->wages_type);
			// user full name
			if (!is_null($wages_type)) {
				$jenis_gaji       = $wages_type[0]->jenis_gaji_keterangan;
				$jenis_gaji_warna = $wages_type[0]->warna;
			} else {
				$jenis_gaji = '<span class="badge bg-red"> ? </span>';
				$jenis_gaji_warna = '';
			}
			// grade
			$grade_type = $this->Core_model->read_user_jenis_grade($r->grade_type);
			// user full name
			if (!is_null($grade_type)) {
				$jenis_grade       = $grade_type[0]->jenis_grade_keterangan;
				$jenis_grade_warna = $grade_type[0]->warna;
			} else {
				$jenis_grade = '<span class="badge bg-red"> ? </span>';
				$jenis_grade_warna = '';
			}
			// get designation
			$designation = $this->Designation_model->read_designation_information($r->designation_id);
			if (!is_null($designation)) {
				$designation_name = $designation[0]->designation_name;
			} else {
				$designation_name = '<span class="badge bg-red"> ? </span>';
			}

			// department
			$department = $this->Department_model->read_department_information($r->department_id);
			if (!is_null($department)) {
				$department_name = $department[0]->department_name;
			} else {
				$department_name = '<span class="badge bg-red"> ? </span>';
			}

			// location
			$location = $this->Location_model->read_location_information($r->location_id);
			if (!is_null($location)) {
				$location_name = $location[0]->location_name;
			} else {
				$location_name = '<span class="badge bg-red"> ? </span>';
			}

			$department_designation = $designation_name . ' (' . $department_name . ')';

			$cek_emp_status =  $this->Employees_model->read_employee_contract_information($r->user_id);

			if (!is_null($cek_emp_status)) {
				$emp_status_name = '<span class="badge bg-green">' . $cek_emp_status[0]->name_type . '</span>';
			} else {

				if ($r->emp_status == 'Tetap') {
					$emp_status_name = '<span class="badge bg-green"> Tetap </span>';
				} else {
					$emp_status_name = '<span class="badge bg-red"> ? </span>';
				}
			}

			if ($r->emp_status == '') {
				$emp_status = '<span class="badge bg-red"> ? </span>';
			} elseif ($r->emp_status == 'Tetap') {
				$emp_status = '<span class="badge bg-green">' . $this->lang->line('xin_employee_status_tetap') . '</span>';
			} elseif ($r->emp_status == 'Kontrak') {
				$emp_status = '<span class="badge bg-green">' . $this->lang->line('xin_employee_status_kontrak') . '</span>';
			} elseif ($r->emp_status == 'Percobaan') {
				$emp_status = '<span class="badge bg-green">' . $this->lang->line('xin_employee_status_percobaan') . '</span>';
			}

			// get status
			if ($r->is_active == 0) : $status = '<span class="badge bg-red">' . $this->lang->line('xin_employees_inactive') . '</span>';
			elseif ($r->is_active == 1) : $status = '<span class="badge bg-green">' . $this->lang->line('xin_employees_active') . '</span>';
			endif;

			if (in_array('0513', $role_resources_ids)) {
				$edit_opt = ' <span data-toggle="tooltip" data-placement="top" title="Edit Karyawan">
								<a target="_blank" href="' . site_url() . 'admin/employees_active/detail/' . $r->user_id . '">
									<button type="button" class="btn icon-btn btn-xs btn-default waves-effect waves-light">
										<span class="fa fa-pencil"></span> Edit
									</button>
								</a>
								</span>';
			} else {
				$edit_opt = '';
			}

			if (in_array('0514', $role_resources_ids)) {
				$view_opt = ' <span data-toggle="tooltip" data-placement="top" title="Lihat Karyawan">
								<a target="_blank" href="' . site_url() . 'admin/employees_active/lihat/' . $r->user_id . '">
									<button type="button" class="btn icon-btn btn-xs btn-info waves-effect waves-light">
										<span class="fa fa-info-circle"></span> Info
									</button>
								</a>
								</span>';
			} else {
				$view_opt = '';
			}


			$function = $edit_opt . $view_opt;

			$bsalary = $this->Core_model->currency_sign($r->basic_salary);

			if ($r->profile_picture != '' && $r->profile_picture != 'no file') {
				$ol = '<a href="javascript:void(0);"><span class="avatar box-16"><img src="' . base_url() . 'uploads/profile/' . $r->profile_picture . '" class="user-image-hr46" alt=""></span></a>';
			} else {
				if ($r->gender == 'Male') {
					$de_file = base_url() . 'uploads/profile/default_male.jpg';
				} else {
					$de_file = base_url() . 'uploads/profile/default_female.jpg';
				}
				$ol = '<a href="javascript:void(0);"><span class="avatar box-16"><img src="' . $de_file . '" class="user-image-hr46" alt=""></span></a>';
			}
			//shift info
			$office_shift = $this->Timesheet_model->read_office_shift_information($r->office_shift_id);
			if (!is_null($office_shift)) {
				$shift = $office_shift[0]->shift_name;
			} else {
				$shift = 'Pola Kerja Belum dibuat';
			}

			// shift info
			if ($r->office_id == 'R') {
				$shift_id = 'Reguler';
			} else if ($r->office_id == 'S') {
				$shift_id = 'Shift';
			} else {
				$shift_id = 'Pola Kerja Belum dibuat';
			}

			// Karyawan Masa kerja														       	  
			date_default_timezone_set("Asia/Jakarta");

			$tanggal1 = new DateTime($r->date_of_birth);
			$tanggal2 = new DateTime();

			if ($tanggal2->diff($tanggal1)->y == 0) {
				$selisih   = $tanggal2->diff($tanggal1)->m . ' bln';
			} else {
				$selisih   = $tanggal2->diff($tanggal1)->y . ' thn' . ' ' . $tanggal2->diff($tanggal1)->m . ' bln';
			}


			$employee_foto = $ol;

			if ($r->ibu_name == '') {
				$ibu = '<span class="badge bg-red"> ? </span>';
			} else {
				$ibu = $r->ibu_name;
			}
			$employee_date = date("d-m-Y", strtotime($r->date_of_joining)) . ' <span class="badge bg-green"> Aktif </span><br> 
			                 <small class="text-muted">Tgl Lhr : ' . date("d-m-Y", strtotime($r->date_of_birth)) . ' 
			                 <br>Usia : ' . $selisih . '<br>Ibu : ' . $ibu . ' </small>';

			$employee_name = strtoupper($full_name) . '<br><small class="text-muted ">' . $this->lang->line('xin_employees_id') . ': ' . $r->employee_id . '</small><br><small class="text-muted ">PIN : ' . strtoupper($emp_pin) . '</small>';

			$comp_name     = $comp_name . '<br><small class="text-muted"> ' . $location_name . '</small><br><small class="text-muted"> ' . $department_name . '</small>';

			$posisi_name   = strtoupper($designation_name) . '<br><small class="text-muted"> ' . $shift_id . ' <br> ' . $shift . ' </small>';

			$contact_info  = '<i class="fa fa-envelope" data-toggle="tooltip" data-placement="top" title="' . $this->lang->line('dashboard_email') . '"></i> ' . $r->email . '<br><i class="fa fa-phone" data-toggle="tooltip" data-placement="top" title="' . $this->lang->line('xin_contact_number') . '"></i> ' . $r->contact_no;

			$rolemp_status = '<span class="' . $jenis_gaji_warna . '">' . $jenis_gaji . '</sapan>';

			$grade_status  = '<span class="' . $jenis_grade_warna . '">' . $jenis_grade . '</sapan>';

			$data[]        = array(
				$function,
				$employee_foto,
				$employee_date,
				$employee_name,
				$comp_name,
				$posisi_name,
				$emp_status . '<br>' . $emp_status_name . '<br>' . $rolemp_status,
				$vc
			);
			$no++;
		}
		$output = array(
			"draw" => $draw,
			"recordsTotal" => $employee->num_rows(),
			"recordsFiltered" => $employee->num_rows(),
			"data" => $data
		);
		$this->output->set_output(json_encode($output));
	}

	public function detail()
	{

		$session = $this->session->userdata('username');

		if (empty($session)) {
			redirect('admin/');
		}

		$id = $this->uri->segment(4);

		$result = $this->Employees_model->read_employee_information($id);

		if (is_null($result)) {
			redirect('admin/employees_active');
		}

		$role_resources_ids = $this->Core_model->user_role_resource();

		$check_role = $this->Employees_model->read_employee_information($session['user_id']);

		if (!in_array('0511', $role_resources_ids)) {
			redirect('admin/employees_active');
		}


		$data = array(
			'title'             => 'Edit Karyawan Aktif | ' . $this->Core_model->site_title(),
			'icon'               => '<i class="fa fa-pencil"></i>',
			'breadcrumbs'       => 'Edit Karyawan Aktif',
			'path_url'          => 'employees_detail',

			'first_name'        => $result[0]->first_name,
			'last_name'         => $result[0]->last_name,

			'ibu_name'          => $result[0]->ibu_name,

			'office_id'         => $result[0]->office_id,
			'office_shift_id'   => $result[0]->office_shift_id,
			'user_id'           => $result[0]->user_id,
			'employee_id'       => $result[0]->employee_id,
			'employee_pin'      => $result[0]->employee_pin,
			'employee_ktp'      => $result[0]->employee_ktp,
			'company_id'        => $result[0]->company_id,
			'emp_status'        => $result[0]->emp_status,
			'location_id'       => $result[0]->location_id,
			'ereports_to'       => $result[0]->reports_to,
			'email'             => $result[0]->email,
			'department_id'     => $result[0]->department_id,
			'sub_department_id' => $result[0]->sub_department_id,
			'designation_id'    => $result[0]->designation_id,
			'user_role_id'      => $result[0]->user_role_id,

			'date_of_birth'     => $result[0]->date_of_birth,
			'place_of_birth'    => $result[0]->place_of_birth,


			'date_of_leaving' => $result[0]->date_of_leaving,

			'gender' => $result[0]->gender,
			'marital_status' => $result[0]->marital_status,
			'contact_no' => $result[0]->contact_no,
			'state' => $result[0]->state,
			'city' => $result[0]->city,
			'zipcode' => $result[0]->zipcode,
			'blood_group' => $result[0]->blood_group,
			'citizenship_id' => $result[0]->citizenship_id,
			'nationality_id' => $result[0]->nationality_id,
			'iethnicity_type' => $result[0]->ethnicity_type,
			'address' => $result[0]->address,
			'address_ktp' => $result[0]->address_ktp,
			'wages_type' => $result[0]->wages_type,
			'grade_type' => $result[0]->grade_type,
			'basic_salary' => $result[0]->basic_salary,
			'date_of_joining' => $result[0]->date_of_joining,
			'all_departments' => $this->Department_model->all_departments(),
			'all_designations' => $this->Designation_model->all_designations(),
			'all_user_roles' => $this->Roles_model->all_user_roles(),
			'profile_picture' => $result[0]->profile_picture,
			'leave_categories' => $result[0]->leave_categories,
			// 'view_companies_id' => $result[0]->view_companies_id,

			'view_company_id' => $result[0]->view_company_id,

			'all_countries' => $this->Core_model->get_countries(),
			'all_document_types' => $this->Employees_model->all_document_types(),
			'all_education_level' => $this->Employees_model->all_education_level(),
			'all_qualification_language' => $this->Employees_model->all_qualification_language(),
			'all_qualification_skill' => $this->Employees_model->all_qualification_skill(),
			'all_contract_types' => $this->Employees_model->all_contract_types(),
			'all_contract_durasi' => $this->Employees_model->all_contract_durasi(),
			'all_contracts' => $this->Employees_model->all_contracts(),
			'all_companies' => $this->Company_model->get_company(),
			'all_office_shifts' => $this->Employees_model->all_office_shifts(),
			'get_all_companies' => $this->Company_model->get_company(),
			'all_office_locations' => $this->Location_model->all_office_locations(),
			'all_leave_types' => $this->Timesheet_model->all_leave_types(),
			'all_countries' => $this->Core_model->get_countries(),
			'user_auth' => $check_role[0],
		);

		$data['subview'] = $this->load->view("admin/employees_active/employee_detail", $data, TRUE);
		$this->load->view('admin/layout/layout_main', $data); //page load

		// Datatables Variables
		$draw = intval($this->input->get("draw"));
		$start = intval($this->input->get("start"));
		$length = intval($this->input->get("length"));
	}

	public function lihat()
	{

		$session = $this->session->userdata('username');

		if (empty($session)) {
			redirect('admin/');
		}

		$id = $this->uri->segment(4);

		$result = $this->Employees_model->read_employee_information($id);

		if (is_null($result)) {
			redirect('admin/employees_active');
		}

		$role_resources_ids = $this->Core_model->user_role_resource();

		$check_role = $this->Employees_model->read_employee_information($session['user_id']);

		if (!in_array('0511', $role_resources_ids)) {
			redirect('admin/employees_active');
		}


		$data = array(
			'title' => 'Lihat Karyawan Aktif | ' . $this->Core_model->site_title(),
			'icon' => '<i class="fa fa-info-circle"></i>',
			'breadcrumbs' => 'Lihat Karyawan Aktif',
			'path_url' => 'employees_lihat',

			'first_name' => $result[0]->first_name,
			'last_name' => $result[0]->last_name,
			'ibu_name'        => $result[0]->ibu_name,
			'office_shift_id' => $result[0]->office_shift_id,
			'user_id' => $result[0]->user_id,
			'employee_id' => $result[0]->employee_id,
			'employee_pin' => $result[0]->employee_pin,
			'employee_ktp' => $result[0]->employee_ktp,
			'company_id' => $result[0]->company_id,
			'emp_status' => $result[0]->emp_status,
			'location_id' => $result[0]->location_id,
			'ereports_to' => $result[0]->reports_to,
			'email' => $result[0]->email,
			'department_id' => $result[0]->department_id,
			'sub_department_id' => $result[0]->sub_department_id,
			'designation_id' => $result[0]->designation_id,
			'user_role_id' => $result[0]->user_role_id,
			'date_of_birth' => $result[0]->date_of_birth,
			'date_of_leaving' => $result[0]->date_of_leaving,
			'gender' => $result[0]->gender,
			'marital_status' => $result[0]->marital_status,
			'contact_no' => $result[0]->contact_no,
			'state' => $result[0]->state,
			'city' => $result[0]->city,
			'zipcode' => $result[0]->zipcode,
			'blood_group' => $result[0]->blood_group,
			'citizenship_id' => $result[0]->citizenship_id,
			'nationality_id' => $result[0]->nationality_id,
			'iethnicity_type' => $result[0]->ethnicity_type,
			'address' => $result[0]->address,
			'address_ktp' => $result[0]->address_ktp,
			'wages_type' => $result[0]->wages_type,
			'grade_type' => $result[0]->grade_type,
			'basic_salary' => $result[0]->basic_salary,
			'date_of_joining' => $result[0]->date_of_joining,
			'all_departments' => $this->Department_model->all_departments(),
			'all_designations' => $this->Designation_model->all_designations(),
			'all_user_roles' => $this->Roles_model->all_user_roles(),
			'profile_picture' => $result[0]->profile_picture,
			'leave_categories' => $result[0]->leave_categories,
			'view_companies_id' => $result[0]->view_companies_id,
			'all_countries' => $this->Core_model->get_countries(),
			'all_document_types' => $this->Employees_model->all_document_types(),
			'all_education_level' => $this->Employees_model->all_education_level(),
			'all_qualification_language' => $this->Employees_model->all_qualification_language(),
			'all_qualification_skill' => $this->Employees_model->all_qualification_skill(),
			'all_contract_types' => $this->Employees_model->all_contract_types(),
			'all_contract_durasi' => $this->Employees_model->all_contract_durasi(),
			'all_contracts' => $this->Employees_model->all_contracts(),
			'all_companies' => $this->Company_model->get_company(),
			'all_office_shifts' => $this->Employees_model->all_office_shifts(),
			'get_all_companies' => $this->Company_model->get_company(),
			'all_office_locations' => $this->Location_model->all_office_locations(),
			'all_leave_types' => $this->Timesheet_model->all_leave_types(),
			'all_countries' => $this->Core_model->get_countries()
		);

		$data['subview'] = $this->load->view("admin/employees_active/employee_lihat", $data, TRUE);
		$this->load->view('admin/layout/layout_main', $data); //page load

		// Datatables Variables
		$draw = intval($this->input->get("draw"));
		$start = intval($this->input->get("start"));
		$length = intval($this->input->get("length"));
	}


	public function basic_info()
	{

		if ($this->input->post('type') == 'basic_info') {

			/* Define return | here result is used to return user data and error for error message */
			$Return = array('result' => '', 'error' => '', 'csrf_hash' => '');
			$Return['csrf_hash'] = $this->security->get_csrf_hash();

			//$office_shift_id = $this->input->post('office_shift_id');
			$system = $this->Core_model->read_setting_info(1);

			/* Server side PHP input validation */
			if ($this->input->post('first_name') === '') {

				$Return['error'] = $this->lang->line('xin_employee_error_first_name');
			} else if ($this->input->post('last_name') === '') {
				$Return['error'] = $this->lang->line('xin_employee_error_last_name');
			} else if ($this->input->post('ibu_name') === '') {
				$Return['error'] = 'Nama Ibu Kandung Wajib Diisi';
			} else if ($this->input->post('employee_id') === '') {
				$Return['error'] = $this->lang->line('xin_employee_error_employee_id');
			} else if ($this->input->post('employee_pin') === '') {
				$Return['error'] = $this->lang->line('xin_employee_error_employee_pin');
			} else if ($this->input->post('wages_type') === '') {
				$Return['error'] = 'Jenis Gaji Tidak Boleh Kosong';
			} else if ($this->input->post('grade_type') === '') {
				$Return['error'] = 'Grade Gaji Tidak Boleh Kosong';
			} else if ($this->input->post('office_id') === '') {
				$Return['error'] = 'Jenis Pola Kerja Tidak Boleh Kosong';
			} else if ($this->input->post('address') === '') {
				$Return['error'] = 'Alamat Domisili Tidak Boleh Kosong';
			} else if ($this->input->post('address_ktp') === '') {
				$Return['error'] = 'Alamat KTP Tidak Boleh Kosong';
			} else if ($this->input->post('view_company_id') === '') {
				$Return['error'] = 'Kelola data perusahaan wajib diisi';
			} else if ($this->input->post('emp_status') === '') {
				$Return['error'] = $this->lang->line('xin_employee_error_status');
			} else if ($this->input->post('employee_ktp') === '') {
				$Return['error'] = $this->lang->line('xin_employee_error_employee_ktp');
			} else if ($this->input->post('office_shift_id') === '') {
				$Return['error'] = $this->lang->line('xin_employee_error_employee_ktp');
			} else if ($this->input->post('company_id') === '') {
				$Return['error'] = $this->lang->line('error_company_field');
			} else if ($this->input->post('location_id') === '') {
				$Return['error'] = $this->lang->line('xin_location_field_error');
			} else if ($this->input->post('department_id') === '') {
				$Return['error'] = $this->lang->line('xin_employee_error_department');
			} else if ($this->input->post('subdepartment_id') === '') {
				$Return['error'] = $this->lang->line('xin_hr_sub_department_field_error');
			} else if ($this->input->post('designation_id') === '') {
				$Return['error'] = $this->lang->line('xin_employee_error_designation');
			} else if ($this->input->post('date_of_birth') === '') {
				$Return['error'] = $this->lang->line('xin_employee_error_date_of_birth');
			} else if ($this->input->post('place_of_birth') === '') {
				$Return['error'] = 'Tempat Lahir Wajib Diisi';
			} else if ($this->Core_model->validate_date($this->input->post('date_of_birth'), 'Y-m-d') == false) {
				$Return['error'] = $this->lang->line('xin_hr_date_format_error');
			} else if ($this->input->post('date_of_joining') === '') {
				$Return['error'] = $this->lang->line('xin_employee_error_joining_date');
			} else if ($this->Core_model->validate_date($this->input->post('date_of_joining'), 'Y-m-d') == false) {
				$Return['error'] = $this->lang->line('xin_hr_date_format_error');
			} else if ($this->input->post('role') === '') {
				$Return['error'] = $this->lang->line('xin_employee_error_user_role');
			} else if ($this->input->post('contact_no') === '') {
				$Return['error'] = $this->lang->line('xin_employee_error_contact_number');
			} else if (!preg_match('/^([0-9]*)$/', $this->input->post('contact_no'))) {
				$Return['error'] = 'Nomor Kontak harus bilangan';
			}

			if ($Return['error'] != '') {
				$this->output($Return);
			}

			if ($Return['error'] != '') {
				$this->output($Return);
			}

			$first_name        = $this->Core_model->clean_post($this->input->post('first_name'));
			$last_name         = $this->Core_model->clean_post($this->input->post('last_name'));

			$ibu_name          = $this->input->post('ibu_name');

			$employee_id       = $this->input->post('employee_id');
			$employee_pin      = $this->input->post('employee_pin');
			$employee_ktp      = $this->input->post('employee_ktp');
			$date_of_joining   = $this->Core_model->clean_date_post($this->input->post('date_of_joining'));

			$date_of_birth     = $this->Core_model->clean_date_post($this->input->post('date_of_birth'));
			$place_of_birth     = $this->input->post('place_of_birth');

			$contact_no        = $this->Core_model->clean_post($this->input->post('contact_no'));

			$address           = $this->Core_model->clean_post($this->input->post('address'));
			$address_ktp       = $this->Core_model->clean_post($this->input->post('address_ktp'));

			$leave_categories  = array($this->input->post('leave_categories'));
			$cat_ids           = implode(',', $this->input->post('leave_categories'));

			$data = array(
				'employee_id'       => $employee_id,
				'employee_pin'      => $employee_pin,
				'employee_ktp'      => $employee_ktp,
				'office_id'         => $this->input->post('office_id'),
				'office_shift_id'   => $this->input->post('office_shift_id'),
				'reports_to'        => $this->input->post('reports_to'),
				'first_name'        => $first_name,
				'last_name'         => $last_name,
				'ibu_name'          => $ibu_name,
				'company_id'        => $this->input->post('company_id'),
				'location_id'       => $this->input->post('location_id'),
				'email'             => $this->input->post('email'),
				'date_of_birth'     => $date_of_birth,
				'place_of_birth'    => $place_of_birth,
				'gender'            => $this->input->post('gender'),
				'emp_status'        => $this->input->post('emp_status'),
				'user_role_id'      => $this->input->post('role'),
				'department_id'     => $this->input->post('department_id'),
				'sub_department_id' => $this->input->post('subdepartment_id'),
				'designation_id'    => $this->input->post('designation_id'),
				'wages_type'        => $this->input->post('wages_type'),
				'grade_type'        => $this->input->post('grade_type'),
				'date_of_joining'   => $date_of_joining,
				'contact_no'        => $contact_no,
				'address'           => $address,
				'address_ktp'       => $address_ktp,
				'state'             => $this->input->post('estate'),
				'city'              => $this->input->post('ecity'),
				'zipcode'           => $this->input->post('ezipcode'),
				'ethnicity_type'    => $this->input->post('ethnicity_type'),
				'leave_categories'  => $cat_ids,
				'view_company_id'   => $this->input->post('view_company_id'),
				'date_of_leaving'   => $this->input->post('date_of_leaving'),
				'marital_status'    => $this->input->post('marital_status'),
				'blood_group'       => $this->input->post('blood_group'),
				'citizenship_id'    => $this->input->post('citizenship_id'),
				'nationality_id'    => $this->input->post('nationality_id')
			);
			$id = $this->input->post('user_id');
			$result = $this->Employees_model->basic_info($data, $id);

			if ($result == TRUE) {
				$Return['result'] = $this->lang->line('xin_employee_basic_info_updated');
			} else {
				$Return['error'] = $this->lang->line('xin_error_msg');
			}
			$this->output($Return);
			exit;
		}
	}


	// =================================================================
	// LIST
	// =================================================================

	// employees directory/hr
	public function hr()
	{

		$session = $this->session->userdata('username');
		if (empty($session)) {
			redirect('admin/');
		}
		$role_resources_ids           = $this->Core_model->user_role_resource();
		$data['title']                = $this->lang->line('left_employees_directory') . ' | ' . $this->Core_model->site_title();
		$data['all_departments']      = $this->Department_model->all_departments();
		$data['all_designations']     = $this->Designation_model->all_designations();
		$data['all_user_roles']       = $this->Roles_model->all_user_roles();
		$data['all_office_shifts']    = $this->Employees_model->all_office_shifts();
		$data['get_all_companies']    = $this->Company_model->get_company();
		$data['all_leave_types']      = $this->Timesheet_model->all_leave_types();
		$data['breadcrumbs']          = $this->lang->line('left_employees_directory');
		if (!in_array('88', $role_resources_ids)) {
			$data['path_url'] = 'myteam_employees';
		} else {
			$data['path_url'] = 'employees';
		}

		$data['path_url'] = 'employees_directory';

		if (in_array('88', $role_resources_ids) || $reports_to > 0) {
			$data['subview'] = $this->load->view("admin/employees_active/directory", $data, TRUE);
			$this->load->view('admin/layout/layout_main', $data); //page load
		} else {
			redirect('admin/dashboard');
		}
	}

	public function employees_list_all()
	{

		$data['title'] = $this->Core_model->site_title();
		$session = $this->session->userdata('username');
		if (!empty($session)) {
			$this->load->view("admin/employees_active/directory", $data);
		} else {
			redirect('admin/');
		}
		// Datatables Variables
		$draw = intval($this->input->get("draw"));
		$start = intval($this->input->get("start"));
		$length = intval($this->input->get("length"));
		$role_resources_ids = $this->Core_model->user_role_resource();
		$system = $this->Core_model->read_setting_info(1);
		$user_info = $this->Core_model->read_user_info($session['user_id']);

		if ($this->input->get("ihr") == 'true') {

			if ($this->input->get("company_id") == 0 && $this->input->get("location_id") == 0 && $this->input->get("department_id") == 0 && $this->input->get("designation_id") == 0) {
				$employee = $this->Employees_model->get_employees_active();
			} else if ($this->input->get("company_id") != 0 && $this->input->get("location_id") == 0 && $this->input->get("department_id") == 0 && $this->input->get("designation_id") == 0) {
				$employee = $this->Employees_model->get_company_employees_flt($this->input->get("company_id"));
			} else if ($this->input->get("company_id") != 0 && $this->input->get("location_id") != 0 && $this->input->get("department_id") == 0 && $this->input->get("designation_id") == 0) {
				$employee = $this->Employees_model->get_company_location_employees_flt($this->input->get("company_id"), $this->input->get("location_id"));
			} else if ($this->input->get("company_id") != 0 && $this->input->get("location_id") != 0 && $this->input->get("department_id") != 0 && $this->input->get("designation_id") == 0) {
				$employee = $this->Employees_model->get_company_location_department_employees_flt($this->input->get("company_id"), $this->input->get("location_id"), $this->input->get("department_id"));
			} else if ($this->input->get("company_id") != 0 && $this->input->get("location_id") != 0 && $this->input->get("department_id") != 0 && $this->input->get("designation_id") != 0) {
				$employee = $this->Employees_model->get_company_location_department_designation_employees_flt($this->input->get("company_id"), $this->input->get("location_id"), $this->input->get("department_id"), $this->input->get("designation_id"));
			}
		} else {
			// if($user_info[0]->user_role_id==1) {
			if ($user_info[0]->user_role_id == 1 || $user_info[0]->user_role_id == 4 || $user_info[0]->user_role_id == 5 || $user_info[0]->user_role_id == 6) {

				$employee = $this->Employees_model->get_employees();
			} else {

				if (in_array('372', $role_resources_ids)) {
					$employee = $this->Employees_model->get_employees_for_other($user_info[0]->company_id);
				} else {
					$employee = $this->Employees_model->get_employees_for_location($user_info[0]->location_id);
				}
			}
		}

		$data = array();

		$no = 1;

		foreach ($employee->result() as $r) {

			// get company
			$company = $this->Core_model->read_company_info($r->company_id);
			if (!is_null($company)) {
				$comp_name = $company[0]->name;
			} else {
				$comp_name = '<span class="badge bg-red"> ? </span>';
			}

			// user full name 
			$full_name = $r->first_name . ' ' . $r->last_name;

			// PIN		
			$employment_pin = $r->employee_pin;
			if ($employment_pin != '') {

				$emp_pin = $r->employee_pin;
			} else {
				$emp_pin = '<span class="badge bg-red"> ? </span>';
			}


			// get report to
			$reports_to = $this->Core_model->read_user_info($r->reports_to);
			// user full name
			if (!is_null($reports_to)) {
				$manager_name = $reports_to[0]->first_name . ' ' . $reports_to[0]->last_name;
			} else {
				$manager_name = '?';
			}
			// get designation
			$designation = $this->Designation_model->read_designation_information($r->designation_id);
			if (!is_null($designation)) {
				$designation_name = $designation[0]->designation_name;
			} else {
				$designation_name = '?';
			}
			// department
			$department = $this->Department_model->read_department_information($r->department_id);
			if (!is_null($department)) {
				$department_name = $department[0]->department_name;
			} else {
				$department_name = '?';
			}
			// location
			$location = $this->Location_model->read_location_information($r->location_id);
			if (!is_null($location)) {
				$location_name = $location[0]->location_name;
			} else {
				$location_name = '?';
			}


			$department_designation = $designation_name . ' (' . $department_name . ')';


			if ($r->emp_status == '') : $emp_status = '<span class="badge bg-red"> ? </span>';
			elseif ($r->emp_status == 'Tetap') : $emp_status = '<span class="badge bg-green">' . $this->lang->line('xin_employee_status_tetap') . '</span>';
			elseif ($r->emp_status == 'Kontrak') : $emp_status = '<span class="badge bg-green">' . $this->lang->line('xin_employee_status_kontrak') . '</span>';
			elseif ($r->emp_status == 'Percobaan') : $emp_status = '<span class="badge bg-green">' . $this->lang->line('xin_employee_status_percobaan') . '</span>';
			endif;

			// get status
			if ($r->is_active == 0) : $status = '<span class="badge bg-red">' . $this->lang->line('xin_employees_inactive') . '</span>';
			elseif ($r->is_active == 1) : $status = '<span class="badge bg-green">' . $this->lang->line('xin_employees_active') . '</span>';
			endif;

			if ($r->user_id != '1') {
				if (in_array('203', $role_resources_ids)) {
					$del_opt = '<span data-toggle="tooltip" data-placement="top" title="' . $this->lang->line('xin_delete') . '"><button type="button" class="btn icon-btn btn-xs btn-danger delete" data-toggle="modal" data-target=".delete-modal" data-record-id="' . $r->user_id . '"><span class="fa fa-trash"></span></button></span>';
				} else {
					$del_opt = '';
				}
			} else {
				$del_opt = '';
			}
			if (in_array('0511', $role_resources_ids)) {
				$view_opt = ' <span data-toggle="tooltip" data-placement="top" title="' . $this->lang->line('xin_view_details') . '"><a href="' . site_url() . 'admin/employees_active/view/' . $r->user_id . '"><button type="button" class="btn icon-btn btn-xs btn-default waves-effect waves-light"><span class="fa fa-eye"></span> Lihat </button></a></span> ';
			} else {
				$view_opt = '';
			}

			if (in_array('421', $role_resources_ids)) {
				$down_opt = ' <span data-toggle="tooltip" data-placement="top" title="' . $this->lang->line('xin_download_profile_title') . '"><a target="_blank" href="' . site_url() . 'admin/employees_active/download_profile/' . $r->user_id . '"><button type="button" class="btn icon-btn btn-xs btn-default waves-effect waves-light"><span class="fa fa-download"></span></button></a></span>';
			} else {
				$down_opt = '';
			}
			// if(in_array('421',$role_resources_ids)) {
			// 	$employee_name .= '<br><small class="text-muted "><a target="_blank" href="'.site_url('admin/employees_active/download_profile/').$r->user_id.'">'.$this->lang->line('xin_download_profile_title').' <i class="fa fa-eye"></i></a></small>';
			// }
			$function = $view_opt;

			$bsalary = $this->Core_model->currency_sign($r->basic_salary);

			if ($r->profile_picture != '' && $r->profile_picture != 'no file') {
				$ol = '<a href="javascript:void(0);"><span class="avatar box-16"><img src="' . base_url() . 'uploads/profile/' . $r->profile_picture . '" class="user-image-hr46" alt=""></span></a>';
			} else {
				if ($r->gender == 'Male') {
					$de_file = base_url() . 'uploads/profile/default_male.jpg';
				} else {
					$de_file = base_url() . 'uploads/profile/default_female.jpg';
				}
				$ol = '<a href="javascript:void(0);"><span class="avatar box-16"><img src="' . $de_file . '" class="user-image-hr46" alt=""></span></a>';
			}
			//shift info
			// $office_shift = $this->Timesheet_model->read_office_shift_information($r->office_shift_id);
			// if(!is_null($office_shift)){
			// 	$shift = $office_shift[0]->shift_name;
			// } else {
			// 	$shift = '?';	
			// }

			$employee_foto = $ol;

			$employee_date = date("d M Y", strtotime($r->date_of_joining)) . '<br><small class="text-muted ">PIN Finger : ' . strtoupper($emp_pin) . '</small>';

			$employee_name = strtoupper($full_name) . '<br><small class="text-muted ">' . $this->lang->line('xin_employees_id') . ': ' . $r->employee_id . '</small>';

			$comp_name = $comp_name . '<br><small class="text-muted"> ' . $location_name . '</small>';

			$posisi_name = strtoupper($designation_name) . '<br><small class="text-muted"> ' . $department_name . '</small>';

			$contact_info = '<i class="fa fa-envelope" data-toggle="tooltip" data-placement="top" title="' . $this->lang->line('dashboard_email') . '"></i> ' . $r->email . '<br><i class="fa fa-phone" data-toggle="tooltip" data-placement="top" title="' . $this->lang->line('xin_contact_number') . '"></i> ' . $r->contact_no;

			$rolemp_status = $status;

			$data[] = array(
				$function,
				$employee_foto,
				$employee_date,
				$employee_name,
				$comp_name,
				$posisi_name,
				$rolemp_status,

			);
			$no++;
		}
		$output = array(
			"draw" => $draw,
			"recordsTotal" => $employee->num_rows(),
			"recordsFiltered" => $employee->num_rows(),
			"data" => $data
		);

		$this->output->set_output(json_encode($output));
		// echo json_encode($output);
		// exit();
	}

	public function myteam_employees_list()
	{

		$data['title'] = $this->Core_model->site_title();
		$session = $this->session->userdata('username');
		if (!empty($session)) {
			$this->load->view("admin/employees_active/employees_list", $data);
		} else {
			redirect('admin/');
		}
		// Datatables Variables
		$draw = intval($this->input->get("draw"));
		$start = intval($this->input->get("start"));
		$length = intval($this->input->get("length"));
		$role_resources_ids = $this->Core_model->user_role_resource();
		$system = $this->Core_model->read_setting_info(1);
		$user_info = $this->Core_model->read_user_info($session['user_id']);
		$employee = $this->Employees_model->get_my_team_employees($session['user_id']);

		$data = array();

		$no = 1;

		foreach ($employee->result() as $r) {

			// get company
			$company = $this->Core_model->read_company_info($r->company_id);
			if (!is_null($company)) {
				$comp_name = $company[0]->name;
			} else {
				$comp_name = '?';
			}

			// user full name 
			$full_name = $r->first_name . ' ' . $r->last_name;
			// user role
			$role = $this->Core_model->read_user_role_info($r->user_role_id);
			if (!is_null($role)) {
				$role_name = $role[0]->role_name;
			} else {
				$role_name = '?';
			}
			// get report to
			$reports_to = $this->Core_model->read_user_info($r->reports_to);
			// user full name
			if (!is_null($reports_to)) {
				$manager_name = $reports_to[0]->first_name . ' ' . $reports_to[0]->last_name;
			} else {
				$manager_name = '?';
			}
			// get designation
			$designation = $this->Designation_model->read_designation_information($r->designation_id);
			if (!is_null($designation)) {
				$designation_name = $designation[0]->designation_name;
			} else {
				$designation_name = '?';
			}
			// department
			$department = $this->Department_model->read_department_information($r->department_id);
			if (!is_null($department)) {
				$department_name = $department[0]->department_name;
			} else {
				$department_name = '?';
			}
			// location
			$location = $this->Location_model->read_location_information($r->location_id);
			if (!is_null($location)) {
				$location_name = $location[0]->location_name;
			} else {
				$location_name = '?';
			}


			$department_designation = $designation_name . ' (' . $department_name . ')';
			// get status
			if ($r->is_active == 0) : $status = '<span class="badge bg-red">' . $this->lang->line('xin_employees_inactive') . '</span>';
			elseif ($r->is_active == 1) : $status = '<span class="badge bg-green">' . $this->lang->line('xin_employees_active') . '</span>';
			endif;

			if ($r->user_id != '1') {
				if (in_array('203', $role_resources_ids)) {
					$del_opt = '<span data-toggle="tooltip" data-placement="top" title="' . $this->lang->line('xin_delete') . '"><button type="button" class="btn icon-btn btn-xs btn-danger delete" data-toggle="modal" data-target=".delete-modal" data-record-id="' . $r->user_id . '"><span class="fa fa-trash"></span></button></span>';
				} else {
					$del_opt = '';
				}
			} else {
				$del_opt = '';
			}
			if (in_array('0511', $role_resources_ids)) {
				$view_opt = ' <span data-toggle="tooltip" data-placement="top" title="' . $this->lang->line('xin_view_details') . '"><a href="' . site_url() . 'admin/employees_active/detail/' . $r->user_id . '"><button type="button" class="btn icon-btn btn-xs btn-default waves-effect waves-light"><span class="fa fa-eye"></span></button></a></span>';
			} else {
				$view_opt = '';
			}
			$function = $view_opt . $del_opt . '';

			$bsalary = $this->Core_model->currency_sign($r->basic_salary);

			if ($r->profile_picture != '' && $r->profile_picture != 'no file') {
				$ol = '<a href="javascript:void(0);"><span class="avatar box-16"><img src="' . base_url() . 'uploads/profile/' . $r->profile_picture . '" class="user-image-hr46" alt=""></span></a>';
			} else {
				if ($r->gender == 'Male') {
					$de_file = base_url() . 'uploads/profile/default_male.jpg';
				} else {
					$de_file = base_url() . 'uploads/profile/default_female.jpg';
				}
				$ol = '<a href="javascript:void(0);"><span class="avatar box-16"><img src="' . $de_file . '" class="user-image-hr46" alt=""></span></a>';
			}
			//shift info
			$office_shift = $this->Timesheet_model->read_office_shift_information($r->office_shift_id);
			if (!is_null($office_shift)) {
				$shift = $office_shift[0]->shift_name;
			} else {
				$shift = '?';
			}

			$employee_foto = $ol;

			$employee_name = strtoupper($full_name) . '<br><small class="text-muted ">' . $this->lang->line('xin_employees_id') . ': ' . $r->employee_id . '</small><br><small class="text-muted ">' . $this->lang->line('xin_e_details_shift') . ': ' . $shift . '</small>';

			if (in_array('421', $role_resources_ids)) {
				$employee_name .= $full_name . '<br><small class="text-muted "><a target="_blank" href="' . site_url('admin/employees_active/download_profile/') . $r->user_id . '">' . $this->lang->line('xin_download_profile_title') . ' <i class="fa fa-eye"></i></a></small>';
			}

			$comp_name = $comp_name . '<br><small class="text-muted"> ' . $location_name . '</small><br><small class="text-muted">' . $this->lang->line('left_department') . ': ' . $department_name . '</small><br><small class="text-muted">' . $this->lang->line('left_designation') . ': ' . $designation_name . '</small>';

			$contact_info = '<i class="fa fa-envelope" data-toggle="tooltip" data-placement="top" title="' . $this->lang->line('dashboard_email') . '"></i> ' . $r->email . '<br><i class="fa fa-phone" data-toggle="tooltip" data-placement="top" title="' . $this->lang->line('xin_contact_number') . '"></i> ' . $r->contact_no;

			$rolemp_status = $role_name . '<br>' . $status;


			$data[] = array(
				$no . ".",
				$employee_foto,
				$employee_name,
				$comp_name,
				$contact_info,
				$manager_name,
				$rolemp_status,
				$function,
			);
			$no++;
		}
		$output = array(
			"draw" => $draw,
			"recordsTotal" => $employee->num_rows(),
			"recordsFiltered" => $employee->num_rows(),
			"data" => $data
		);
		$this->output->set_output(json_encode($output));
	}

	public function download_profile()
	{
		$system = $this->Core_model->read_setting_info(1);
		// create new PDF document
		$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
		$role_resources_ids = $this->Core_model->user_role_resource();
		$key = $this->uri->segment(4);
		$user = $this->Core_model->read_user_info($key);
		if (is_null($user)) {
			redirect('admin/employees');
		}
		if (!in_array('421', $role_resources_ids)) {
			redirect('admin/employees');
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
		$fname = $user[0]->first_name . ' ' . $user[0]->last_name;
		// company info
		$company = $this->Core_model->read_company_info($user[0]->company_id);
		if (!is_null($company)) {
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
				$country_name = '?';
			}
			$c_info_email = $company[0]->email;
			$c_info_phone = $company[0]->contact_number;
		} else {
			$company_name = '?';
			$address_1 = '?';
			$address_2 = '?';
			$city = '?';
			$state = '?';
			$zipcode = '?';
			$country_name = '?';
			$c_info_email = '?';
			$c_info_phone = '?';
		}
		$location = $this->Location_model->read_location_information($user[0]->location_id);
		if (!is_null($location)) {
			$location_name = $location[0]->location_name;
		} else {
			$location_name = '?';
		}
		$user_role = $this->Roles_model->read_role_information($user[0]->user_role_id);
		if (!is_null($user_role)) {
			$iuser_role = $user_role[0]->role_name;
		} else {
			$iuser_role = '?';
		}
		// set default header data
		//$c_info_address = $address_1.' '.$address_2.', '.$city.' - '.$zipcode.', '.$country_name;
		$c_info_address = $address_1 . ' ' . $address_2 . ', ' . $city . ' - ' . $zipcode;
		//$email_phone_address = "$c_info_address \n".$this->lang->line('xin_phone')." : $c_info_phone | ".$this->lang->line('dashboard_email')." : $c_info_email ";

		$company_info = $this->lang->line('left_company') . ": $company_name | " . $this->lang->line('left_location') . ": $location_name \n";
		$designation_info = $this->lang->line('left_department') . ": $_department_name | " . $this->lang->line('left_designation') . ": $_designation_name \n";

		$header_string = "$company_info" . "$designation_info";
		// set document information
		$pdf->SetCreator('AKUI');
		$pdf->SetAuthor('AKUI');
		//$pdf->SetTitle('Workable-Zone - Payslip');
		//$pdf->SetSubject('TCPDF Tutorial');
		//$pdf->SetKeywords('TCPDF, PDF, example, test, guide');
		if ($user[0]->profile_picture != '' && $user[0]->profile_picture != 'no file') {
			$ol = 'uploads/profile/' . $user[0]->profile_picture;
		} else {
			if ($user[0]->gender == 'Male') {
				$de_file = 'uploads/profile/default_male.jpg';
			} else {
				$de_file = 'uploads/profile/default_female.jpg';
			}
			$ol = $de_file;
		}

		$header_namae = $fname . ' ' . $this->lang->line('xin_profile');
		$pdf->SetHeaderData('../../../' . $ol, 15, $header_namae, $header_string);

		$pdf->setFooterData(array(0, 64, 0), array(0, 64, 128));

		// set header and footer fonts
		$pdf->setHeaderFont(array('helvetica', '', 11.5));
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
		$pdf->SetAuthor('AKUI');
		$pdf->SetTitle($company_name . ' - ' . $this->lang->line('xin_download_profile_title'));
		$pdf->SetSubject($this->lang->line('xin_download_profile_title'));
		$pdf->SetKeywords($this->lang->line('xin_download_profile_title'));

		// set font
		$pdf->SetFont('helvetica', 'B', 10);

		// set header and footer fonts
		$pdf->setHeaderFont(array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
		$pdf->setFooterFont(array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

		// set default monospaced font
		$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

		// set margins
		$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
		$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
		$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

		// set auto page breaks
		$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

		// set image scale factor
		$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

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
		/*$tbl = '<br>
		<table cellpadding="1" cellspacing="1" border="0">
			<tr>
				<td align="center"><h1>'.$fname.'</h1></td>
			</tr>
		</table>
		';
		$pdf->writeHTML($tbl, true, false, false, false, '');*/
		// -----------------------------------------------------------------------------
		$date_of_joining = $this->Core_model->set_date_format($user[0]->date_of_joining);

		// set cell padding
		$pdf->setCellPaddings(1, 1, 1, 1);

		// set cell margins
		$pdf->setCellMargins(0, 0, 0, 0);

		// set color for background
		$pdf->SetFillColor(255, 255, 127);
		/////////////////////////////////////////////////////////////////////////////////
		if ($user[0]->marital_status == 'Single') {
			$mstatus = $this->lang->line('xin_status_single');
		} else if ($user[0]->marital_status == 'Married') {
			$mstatus = $this->lang->line('xin_status_married');
		} else if ($user[0]->marital_status == 'Widowed') {
			$mstatus = $this->lang->line('xin_status_widowed');
		} else if ($user[0]->marital_status == 'Divorced or Separated') {
			$mstatus = $this->lang->line('xin_status_divorced_separated');
		} else {
			$mstatus = $this->lang->line('xin_status_single');
		}
		if ($user[0]->is_active == '0') {
			$isactive = $this->lang->line('xin_employees_inactive');
		} else if ($user[0]->is_active == '1') {
			$isactive = $this->lang->line('xin_employees_active');
		} else {
			$isactive = $this->lang->line('xin_employees_inactive');
		}
		$tbl_2 = '
		<table cellpadding="2" cellspacing="0" border="1">
			<tr bgcolor="#e0e0e0" >
			<td colspan="6"><strong>' . $this->lang->line('xin_e_details_basic') . '</strong></td>
			</tr>
			<tr>
				<td>' . $this->lang->line('dashboard_username') . '</td>
				<td colspan="2">' . $user[0]->username . '</td>
				<td>' . $this->lang->line('dashboard_email') . '</td>
				<td colspan="2">' . $user[0]->email . '</td>
			</tr>
			<tr>
				<td>' . $this->lang->line('dashboard_employee_id') . '</td>
				<td colspan="2">' . $user[0]->employee_id . '</td>
				<td>' . $this->lang->line('xin_employee_role') . '</td>
				<td colspan="2">' . $iuser_role . '</td>
			</tr>
			<tr>
				<td>' . $this->lang->line('dashboard_xin_status') . '</td>
				<td>' . $isactive . '</td>
				<td>' . $this->lang->line('xin_employee_gender') . '</td>
				<td>' . $user[0]->gender . '</td>
				<td>' . $this->lang->line('xin_employee_mstatus') . '</td>
				<td>' . $mstatus . '</td>
			</tr>
			<tr>
				<td>' . $this->lang->line('xin_employee_doj') . '</td>
				<td colspan="2">' . $date_of_joining . '</td>
				<td>' . $this->lang->line('dashboard_contact') . '#</td>
				<td colspan="2">' . $user[0]->contact_no . '</td>
			</tr>
			<tr>
				<td>' . $this->lang->line('xin_state') . '</td>
				<td>' . $user[0]->state . '</td>
				<td>' . $this->lang->line('xin_city') . '</td>
				<td>' . $user[0]->city . '</td>
				<td>' . $this->lang->line('xin_zipcode') . '</td>
				<td>' . $user[0]->zipcode . '</td>
			</tr>
			<tr>
				<td>' . $this->lang->line('xin_employee_address') . '</td>
				<td colspan="5">' . $user[0]->address . '</td>
			</tr>
		</table>';
		$pdf->writeHTML($tbl_2, true, false, false, false, '');
		//salary
		$salary_opt = $this->lang->line('xin_payroll_basic_salary');

		$tbl_3 = '
		<table cellpadding="2" cellspacing="0" border="1">
			<tr bgcolor="#e0e0e0">
			<td colspan="4"><strong>' . $this->lang->line('xin_salary_title') . '</strong></td>
			</tr>
			<tr>
				<td>' . $this->lang->line('xin_salary_title') . '</td>
				<td>' . $this->Core_model->currency_sign($user[0]->basic_salary) . '</td>
				<td>' . $this->lang->line('xin_employee_type_wages') . '</td>
				<td>' . $salary_opt . '</td>
			</tr>
			</table>';
		$pdf->writeHTML($tbl_3, true, false, false, false, '');
		//CORE HR
		// awards
		$count_awards = $this->Core_model->get_employee_awards_count($user[0]->user_id);
		if ($count_awards > 0) {
			$tbl_4 = '
		<table cellpadding="2" cellspacing="0" border="1">
			<tr bgcolor="#e0e0e0">
			<td colspan="3"><strong>' . $this->lang->line('left_awards') . '</strong></td>
			</tr>
			<tr>
				<td>' . $this->lang->line('xin_award_name') . '</td>
				<td>' . $this->lang->line('xin_gift') . '</td>
				<td>' . $this->lang->line('xin_award_month_year') . '</td>
			</tr>';
			$award = $this->Awards_model->get_employee_awards($user[0]->user_id);
			foreach ($award->result() as $r) {
				// get award type
				$award_type = $this->Awards_model->read_award_type_information($r->award_type_id);
				if (!is_null($award_type)) {
					$award_type = $award_type[0]->award_type;
				} else {
					$award_type = '?';
				}
				$d = explode('-', $r->award_month_year);
				$get_month = date('F', mktime(0, 0, 0, $d[1], 10));
				$award_date = $get_month . ', ' . $d[0];
				// get currency
				if ($r->cash_price == '') {
					$currency = $this->Core_model->currency_sign(0);
				} else {
					$currency = $this->Core_model->currency_sign($r->cash_price);
				}
				$tbl_4 .= '
			<tr>
				<td>' . $award_type . '</td>
				<td>' . $r->gift_item . '</td>
				<td>' . $award_date . '</td>
			</tr>';
			}
			$tbl_4 .= '</table>';
			$pdf->writeHTML($tbl_4, true, false, false, false, '');
		}
		// TRAINING
		$count_training = $this->Core_model->get_employee_training_count($user[0]->user_id);
		if ($count_training > 0) {
			$tbl_5 = '
		<table cellpadding="2" cellspacing="0" border="1">
			<tr bgcolor="#e0e0e0">
			<td colspan="4"><strong>' . $this->lang->line('left_training') . '</strong></td>
			</tr>
			<tr>
				<td>' . $this->lang->line('left_training_type') . '</td>
				<td>' . $this->lang->line('xin_trainer') . '</td>
				<td>' . $this->lang->line('xin_training_duration') . '</td>
				<td>' . $this->lang->line('xin_cost') . '</td>
			</tr>';
			$training = $this->Training_model->get_employee_training($user[0]->user_id);
			foreach ($training->result() as $tr_in) {
				// get training type
				$type = $this->Training_model->read_training_type_information($tr_in->training_type_id);
				if (!is_null($type)) {
					$itype = $type[0]->type;
				} else {
					$itype = '?';
				}
				// get trainer
				$trainer = $this->Core_model->read_user_info($tr_in->trainer_id);
				// employee full name
				if (!is_null($trainer)) {
					$trainer_name = $trainer[0]->first_name . ' ' . $trainer[0]->last_name;
				} else {
					$trainer_name = '?';
				}
				// get end date
				$finish_date = $this->Core_model->set_date_format($tr_in->finish_date);
				if ($tr_in->training_status == 0) :
					$training_status = $this->lang->line('xin_pending');
				elseif ($tr_in->training_status == 1) :
					$training_status = $this->lang->line('xin_started');
				elseif ($tr_in->training_status == 2) :
					$training_status = $this->lang->line('xin_completed');
				else :
					$training_status = $this->lang->line('xin_terminated');
				endif;
				$tbl_5 .= '
			<tr>
				<td>' . $itype . '</td>
				<td>' . $trainer_name . '</td>
				<td>' . $finish_date . '</td>
				<td>' . $training_status . '</td>
			</tr>';
			}
			$tbl_5 .= '</table>';
			$pdf->writeHTML($tbl_5, true, false, false, false, '');
		}
		// warning
		$count_warning = $this->Core_model->get_employee_warning_count($user[0]->user_id);
		if ($count_warning > 0) {
			$tbl_5 = '
		<table cellpadding="2" cellspacing="0" border="1">
			<tr bgcolor="#e0e0e0">
			<td colspan="4"><strong>' . $this->lang->line('left_warnings') . '</strong></td>
			</tr>
			<tr>
				<td>' . $this->lang->line('xin_subject') . '</td>
				<td>' . $this->lang->line('xin_warning_type') . '</td>
				<td>' . $this->lang->line('xin_warning_date') . '</td>
				<td>' . $this->lang->line('xin_warning_by') . '</td>
			</tr>';
			$warning = $this->Warning_model->get_employee_warning($user[0]->user_id);
			foreach ($warning->result() as $wr) {
				// get warning date
				$warning_date = $this->Core_model->set_date_format($wr->warning_date);
				// get warning type
				$warning_type = $this->Warning_model->read_warning_type_information($wr->warning_type_id);
				if (!is_null($warning_type)) {
					$wtype = $warning_type[0]->type;
				} else {
					$wtype = '?';
				}
				// get user > warning by
				$user_by = $this->Core_model->read_user_info($wr->warning_by);
				// user full name
				if (!is_null($user_by)) {
					$warning_by = $user_by[0]->first_name . ' ' . $user_by[0]->last_name;
				} else {
					$warning_by = '?';
				}
				$tbl_5 .= '
			<tr>
				<td>' . $wr->subject . '</td>
				<td>' . $wtype . '</td>
				<td>' . $warning_date . '</td>
				<td>' . $warning_by . '</td>
			</tr>';
			}
			$tbl_5 .= '</table>';
			$pdf->writeHTML($tbl_5, true, false, false, false, '');
		}
		// travel
		$travel_count = $this->Core_model->get_employee_travel_count($user[0]->user_id);
		if ($travel_count > 0) {
			$tbl_6 = '
		<table cellpadding="2" cellspacing="0" border="1">
			<tr bgcolor="#e0e0e0">
			<td colspan="5"><strong>' . $this->lang->line('xin_travel') . '</strong></td>
			</tr>
			<tr>
				<td>' . $this->lang->line('xin_visit_place') . '</td>
				<td colspan="2">' . $this->lang->line('xin_budget_title') . '</td>
				<td>' . $this->lang->line('dashboard_xin_status') . '</td>
				<td>' . $this->lang->line('xin_end_date') . '</td>
			</tr>';
			$travel = $this->Travel_model->get_employee_travel($user[0]->user_id);
			foreach ($travel->result() as $trv) {
				// get warning date
				//$warning_date = $this->Core_model->set_date_format($trv->warning_date);
				if ($trv->status == 0) :
					$status = $this->lang->line('xin_pending');
				elseif ($trv->status == 1) :
					$status = $this->lang->line('xin_accepted');
				else :
					$status = $this->lang->line('xin_rejected');
				endif;
				$expected_budget = $this->Core_model->currency_sign($trv->expected_budget);
				$actual_budget = $this->Core_model->currency_sign($trv->actual_budget);
				$t_budget = $this->lang->line('xin_expected_travel_budget') . ': ' . $expected_budget . '<br>' . $this->lang->line('xin_actual_travel_budget') . ': ' . $expected_budget;
				// get end date
				$end_date = $this->Core_model->set_date_format($trv->end_date);
				$tbl_6 .= '
			<tr>
				<td>' . $trv->visit_place . '</td>
				<td colspan="2">' . $t_budget . '</td>
				<td>' . $status . '</td>
				<td>' . $end_date . '</td>
			</tr>';
			}
			$tbl_6 .= '</table>';
			$pdf->writeHTML($tbl_6, true, false, false, false, '');
		}

		// tickets
		$tickets_count = $this->Core_model->get_employee_tickets_count($user[0]->user_id);
		if ($tickets_count > 0) {
			$tbl_7 = '
		<table cellpadding="2" cellspacing="0" border="1">
			<tr bgcolor="#e0e0e0">
			<td colspan="5"><strong>' . $this->lang->line('left_tickets') . '</strong></td>
			</tr>
			<tr>
				<td>' . $this->lang->line('xin_ticket_code') . '</td>
				<td>' . $this->lang->line('xin_subject') . '</td>
				<td>' . $this->lang->line('xin_p_priority') . '</td>
				<td  colspan="2">' . $this->lang->line('xin_e_details_date') . '</td>
			</tr>';
			$ticket = $this->Tickets_model->get_employee_tickets($user[0]->user_id);
			foreach ($ticket->result() as $tkts) {

				if ($tkts->ticket_priority == 0) :
					$ticket_priority = $this->lang->line('xin_low');
				elseif ($tkts->ticket_priority == 2) :
					$ticket_priority = $this->lang->line('xin_medium');
				elseif ($tkts->ticket_priority == 3) :
					$ticket_priority = $this->lang->line('xin_high');
				elseif ($tkts->ticket_priority == 4) :
					$ticket_priority = $this->lang->line('xin_critical');
				else :
					$ticket_priority = $this->lang->line('xin_low');
				endif;
				if ($tkts->ticket_status == 1) :
					$status = $this->lang->line('xin_open');
				else :
					$status = $this->lang->line('xin_closed');
				endif;

				// ticket_code
				$iticket_code = $tkts->ticket_code . '<br>' . $status;
				$created_at = date('h:i A', strtotime($tkts->created_at));
				$_date = explode(' ', $tkts->created_at);
				$edate = $this->Core_model->set_date_format($_date[0]);
				$_created_at = $edate . ' ' . $created_at;

				$tbl_7 .= '
			<tr>
				<td>' . $iticket_code . '</td>
				<td>' . $tkts->subject . '</td>
				<td>' . $ticket_priority . '</td>
				<td colspan="2">' . $_created_at . '</td>
			</tr>';
			}
			$tbl_7 .= '</table>';
			$pdf->writeHTML($tbl_7, true, false, false, false, '');
		}


		// assets
		$assets_count = $this->Core_model->get_employee_assets_count($user[0]->user_id);
		if ($assets_count > 0) {
			$tbl_10 = '
		<table cellpadding="2" cellspacing="0" border="1">
			<tr bgcolor="#e0e0e0">
			<td colspan="5"><strong>' . $this->lang->line('xin_assets') . '</strong></td>
			</tr>
			<tr>
				<td>' . $this->lang->line('xin_asset_name') . '</td>
				<td>' . $this->lang->line('xin_acc_category') . '</td>
				<td colspan="2">' . $this->lang->line('xin_company_asset_code') . '</td>
				<td>' . $this->lang->line('xin_is_working') . '</td>
			</tr>';
			$assets = $this->Assets_model->get_employee_assets($user[0]->user_id);
			foreach ($assets->result() as $asts) {

				// get category
				$assets_category = $this->Assets_model->read_assets_category_info($asts->assets_category_id);
				if (!is_null($assets_category)) {
					$category = $assets_category[0]->category_name;
				} else {
					$category = '?';
				}
				//working?
				if ($asts->is_working == 1) {
					$working = $this->lang->line('xin_yes');
				} else {
					$working = $this->lang->line('xin_no');
				}

				$tbl_10 .= '
				<tr>
					<td>' . $asts->name . '</td>
					<td>' . $category . '</td>
					<td colspan="2">' . $asts->company_asset_code . '</td>
					<td>' . $working . '</td>
				</tr>';
			}
			$tbl_10 .= '</table>';
			$pdf->writeHTML($tbl_10, true, false, false, false, '');
		}
		// meetings
		$meetings_count = $this->Core_model->get_employee_meetings_count($user[0]->user_id);
		if ($meetings_count > 0) {
			$tbl_11 = '
		<table cellpadding="2" cellspacing="0" border="1">
			<tr bgcolor="#e0e0e0">
			<td colspan="3"><strong>' . $this->lang->line('xin_hr_meetings') . '</strong></td>
			</tr>
			<tr>
				<td>' . $this->lang->line('xin_hr_meeting_title') . '</td>
				<td>' . $this->lang->line('xin_hr_meeting_date') . '</td>
				<td>' . $this->lang->line('xin_hr_meeting_time') . '</td>
			</tr>';
			$meetings = $this->Meetings_model->get_employee_meetings($user[0]->user_id);
			foreach ($meetings->result() as $meetings_hr) {

				// get start date and end date
				$meeting_date = $this->Core_model->set_date_format($meetings_hr->meeting_date);
				$meeting_time = new DateTime($meetings_hr->meeting_time);
				$metime = $meeting_time->format('h:i a');

				$tbl_11 .= '
				<tr>
					<td>' . $meetings_hr->meeting_title . '</td>
					<td>' . $meeting_date . '</td>
					<td>' . $metime . '</td>
				</tr>';
			}
			$tbl_11 .= '</table>';
			$pdf->writeHTML($tbl_11, true, false, false, false, '');
		}
		// events
		$events_count = $this->Core_model->get_employee_events_count($user[0]->user_id);
		if ($events_count > 0) {
			$tbl_12 = '
		<table cellpadding="2" cellspacing="0" border="1">
			<tr bgcolor="#e0e0e0">
			<td colspan="3"><strong>' . $this->lang->line('xin_hr_events') . '</strong></td>
			</tr>
			<tr>
				<td>' . $this->lang->line('xin_hr_event_title') . '</td>
				<td>' . $this->lang->line('xin_hr_event_date') . '</td>
				<td>' . $this->lang->line('xin_hr_event_time') . '</td>
			</tr>';
			$events = $this->Events_model->get_employee_events($user[0]->user_id);
			foreach ($events->result() as $events_hr) {

				// get start date and end date
				$sdate = $this->Core_model->set_date_format($events_hr->event_date);
				// get time am/pm
				$event_time = new DateTime($events_hr->event_time);
				$etime = $event_time->format('h:i a');

				$tbl_12 .= '
				<tr>
					<td>' . $events_hr->event_title . '</td>
					<td>' . $sdate . '</td>
					<td>' . $etime . '</td>
				</tr>';
			}
			$tbl_12 .= '</table>';
			$pdf->writeHTML($tbl_12, true, false, false, false, '');
		}


		$fname = strtolower($fname);
		$pay_month = strtolower(date("F Y"));
		//Close and output PDF document
		ob_start();
		$pdf->Output('payslip_' . $fname . '_' . $pay_month . '.pdf', 'I');
		ob_end_flush();
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
			$this->load->view("admin/employees_active/get_departments", $data);
		} else {
			redirect('admin/');
		}
		// Datatables Variables
		$draw = intval($this->input->get("draw"));
		$start = intval($this->input->get("start"));
		$length = intval($this->input->get("length"));
	}

	public function dialog_contact()
	{

		$session = $this->session->userdata('username');
		if (empty($session)) {
			redirect('admin/');
		}
		$data['title'] = $this->Core_model->site_title();
		$id = $this->input->get('field_id');
		$result = $this->Employees_model->read_contact_information($id);
		$data = array(
			'contact_id' => $result[0]->contact_id,
			'employee_id' => $result[0]->employee_id,
			'employee_pin' => $result[0]->employee_pin,
			'employee_ktp' => $result[0]->employee_ktp,
			'relation' => $result[0]->relation,
			'is_primary' => $result[0]->is_primary,
			'is_dependent' => $result[0]->is_dependent,
			'contact_name' => $result[0]->contact_name,
			'work_phone' => $result[0]->work_phone,
			'work_phone_extension' => $result[0]->work_phone_extension,
			'mobile_phone' => $result[0]->mobile_phone,
			'home_phone' => $result[0]->home_phone,
			'work_email' => $result[0]->work_email,
			'personal_email' => $result[0]->personal_email,
			'address_1' => $result[0]->address_1,
			'address_2' => $result[0]->address_2,
			'city' => $result[0]->city,
			'state' => $result[0]->state,
			'zipcode' => $result[0]->zipcode,
			'icountry' => $result[0]->country,
			'all_countries' => $this->Core_model->get_countries()
		);
		if (!empty($session)) {
			$this->load->view('admin/employees_active/dialog_employee_details', $data);
		} else {
			redirect('admin/');
		}
	}

	// get company > locations
	public function get_company_elocations()
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
				$this->load->view("admin/employees_active/get_company_elocations", $data);
			} else {
				redirect('admin/');
			}
		}
		// Datatables Variables
		$draw = intval($this->input->get("draw"));
		$start = intval($this->input->get("start"));
		$length = intval($this->input->get("length"));
	}

	// get company > office shifts
	public function get_company_office_shifts()
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
				$this->load->view("admin/employees_active/get_company_office_shifts", $data);
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
	public function get_location_departments()
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
				$this->load->view("admin/employees_active/get_location_departments", $data);
			} else {
				redirect('admin/');
			}
		}
		// Datatables Variables
		$draw = intval($this->input->get("draw"));
		$start = intval($this->input->get("start"));
		$length = intval($this->input->get("length"));
	}



	public function dialog_qualification()
	{

		$session = $this->session->userdata('username');
		if (empty($session)) {
			redirect('admin/');
		}
		$data['title'] = $this->Core_model->site_title();
		$id = $this->input->get('field_id');
		$result = $this->Employees_model->read_qualification_information($id);
		$data = array(
			'qualification_id' => $result[0]->qualification_id,
			'employee_id' => $result[0]->employee_id,
			'name' => $result[0]->name,
			'education_level_id' => $result[0]->education_level_id,
			'from_year' => $result[0]->from_year,
			'language_id' => $result[0]->language_id,
			'to_year' => $result[0]->to_year,
			'skill_id' => $result[0]->skill_id,
			'description' => $result[0]->description,
			'all_education_level' => $this->Employees_model->all_education_level(),
			'all_qualification_language' => $this->Employees_model->all_qualification_language(),
			'all_qualification_skill' => $this->Employees_model->all_qualification_skill()
		);
		if (!empty($session)) {
			$this->load->view('admin/employees_active/dialog_employee_details', $data);
		} else {
			redirect('admin/');
		}
	}

	public function dialog_work_experience()
	{

		$session = $this->session->userdata('username');
		if (empty($session)) {
			redirect('admin/');
		}
		$data['title'] = $this->Core_model->site_title();
		$id = $this->input->get('field_id');
		$result = $this->Employees_model->read_work_experience_information($id);
		$data = array(
			'work_experience_id' => $result[0]->work_experience_id,
			'employee_id' => $result[0]->employee_id,
			'company_name' => $result[0]->company_name,
			'from_date' => $result[0]->from_date,
			'to_date' => $result[0]->to_date,
			'post' => $result[0]->post,
			'description' => $result[0]->description
		);
		if (!empty($session)) {
			$this->load->view('admin/employees_active/dialog_employee_details', $data);
		} else {
			redirect('admin/');
		}
	}


	public function dialog_shift()
	{

		$session = $this->session->userdata('username');
		if (empty($session)) {
			redirect('admin/');
		}
		$data['title'] = $this->Core_model->site_title();
		$id = $this->input->get('field_id');
		$result = $this->Employees_model->read_emp_shift_information($id);
		$data = array(
			'emp_shift_id' => $result[0]->emp_shift_id,
			'employee_id' => $result[0]->employee_id,
			'shift_id' => $result[0]->shift_id,
			'from_date' => $result[0]->from_date,
			'to_date' => $result[0]->to_date
		);
		if (!empty($session)) {
			$this->load->view('admin/employees_active/dialog_employee_details', $data);
		} else {
			redirect('admin/');
		}
	}

	public function dialog_location()
	{

		$session = $this->session->userdata('username');
		if (empty($session)) {
			redirect('admin/');
		}
		$data['title'] = $this->Core_model->site_title();
		$id = $this->input->get('field_id');
		$result = $this->Employees_model->read_location_information($id);
		$data = array(
			'office_location_id' => $result[0]->office_location_id,
			'employee_id' => $result[0]->employee_id,
			'location_id' => $result[0]->location_id,
			'from_date' => $result[0]->from_date,
			'to_date' => $result[0]->to_date
		);
		if (!empty($session)) {
			$this->load->view('admin/employees_active/dialog_employee_details', $data);
		} else {
			redirect('admin/');
		}
	}


	// get departmens > designations
	public function designation()
	{

		$data['title'] = $this->Core_model->site_title();
		$id = $this->uri->segment(4);

		$data = array(
			'subdepartment_id' => $id,
			'all_designations' => $this->Designation_model->all_designations(),
		);
		$session = $this->session->userdata('username');
		if (!empty($session)) {
			$this->load->view("admin/employees_active/get_designations", $data);
		} else {
			redirect('admin/');
		}
		// Datatables Variables
		$draw = intval($this->input->get("draw"));
		$start = intval($this->input->get("start"));
		$length = intval($this->input->get("length"));
	}

	public function is_designation()
	{

		$data['title'] = $this->Core_model->site_title();
		$id = $this->uri->segment(4);

		$data = array(
			'department_id' => $id,
			'all_designations' => $this->Designation_model->all_designations(),
		);
		$session = $this->session->userdata('username');
		if (!empty($session)) {
			$this->load->view("admin/employees_active/get_designations", $data);
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
			$this->load->view("admin/employees_active/get_sub_departments", $data);
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
		$session = $this->session->userdata('username');
		if (empty($session)) {
			redirect('admin/');
		}
		$data['title'] = $this->Core_model->site_title();
		$id = $this->input->get('warning_id');
		$result = $this->Warning_model->read_warning_information($id);
		$data = array(
			'warning_id' => $result[0]->warning_id,
			'warning_to' => $result[0]->warning_to,
			'warning_by' => $result[0]->warning_by,
			'warning_date' => $result[0]->warning_date,
			'warning_type_id' => $result[0]->warning_type_id,
			'subject' => $result[0]->subject,
			'description' => $result[0]->description,
			'status' => $result[0]->status,
			'all_employees' => $this->Core_model->all_employees(),
			'all_warning_types' => $this->Warning_model->all_warning_types(),
		);
		if (!empty($session)) {
			$this->load->view('admin/warning/dialog_warning', $data);
		} else {
			redirect('admin/');
		}
	}

	// Validate and update info in database // social info
	public function profile_picture()
	{

		if ($this->input->post('type') == 'profile_picture') {

			/* Define return | here result is used to return user data and error for error message */
			$Return = array('result' => '', 'error' => '', 'csrf_hash' => '');
			$Return['csrf_hash'] = $this->security->get_csrf_hash();

			$id = $this->input->post('user_id');

			/* Check if file uploaded..*/
			if ($_FILES['p_file']['size'] == 0 && null == $this->input->post('remove_profile_picture')) {
				$Return['error'] = $this->lang->line('xin_employee_select_picture');
			} else {
				if (is_uploaded_file($_FILES['p_file']['tmp_name'])) {
					//checking image type
					$allowed  =  array('png', 'jpg', 'jpeg', 'pdf', 'gif');
					$filename = $_FILES['p_file']['name'];
					$ext      = pathinfo($filename, PATHINFO_EXTENSION);

					if (in_array($ext, $allowed)) {
						$tmp_name = $_FILES["p_file"]["tmp_name"];
						$profile = "uploads/profile/";
						$set_img = base_url() . "uploads/profile/";
						// basename() may prevent filesystem traversal attacks;
						// further validation/sanitation of the filename may be appropriate
						$name = basename($_FILES["p_file"]["name"]);
						$newfilename = 'profile_' . round(microtime(true)) . '.' . $ext;
						move_uploaded_file($tmp_name, $profile . $newfilename);
						$fname = $newfilename;

						//UPDATE Employee info in DB
						$data = array('profile_picture' => $fname);
						$result = $this->Employees_model->profile_picture($data, $id);
						if ($result == TRUE) {
							$Return['result'] = $this->lang->line('xin_employee_picture_updated');
							$Return['img'] = $set_img . $fname;
						} else {
							$Return['error'] = $this->lang->line('xin_error_msg');
						}
						$this->output($Return);
						exit;
					} else {
						$Return['error'] = $this->lang->line('xin_employee_picture_type');
					}
				}
			}

			if (null != $this->input->post('remove_profile_picture')) {
				//UPDATE Employee info in DB
				$data = array('profile_picture' => 'no file');
				$row = $this->Employees_model->read_employee_information($id);
				$profile = base_url() . "uploads/profile/";
				$result = $this->Employees_model->profile_picture($data, $id);
				if ($result == TRUE) {
					$Return['result'] = $this->lang->line('xin_employee_picture_updated');
					if ($row[0]->gender == 'Male') {
						$Return['img'] = $profile . 'default_male.jpg';
					} else {
						$Return['img'] = $profile . 'default_female.jpg';
					}
				} else {
					$Return['error'] = $this->lang->line('xin_error_msg');
				}
				$this->output($Return);
				exit;
			}

			if ($Return['error'] != '') {
				$this->output($Return);
			}
		}
	}


	// Validate and update info in database // contact info
	public function update_contacts_info()
	{

		if ($this->input->post('type') == 'contact_info') {
			/* Define return | here result is used to return user data and error for error message */
			$Return = array('result' => '', 'error' => '', 'csrf_hash' => '');
			$Return['csrf_hash'] = $this->security->get_csrf_hash();

			/* Server side PHP input validation */
			/* Server side PHP input validation */
			if ($this->input->post('salutation') === '') {
				$Return['error'] = $this->lang->line('xin_employee_error_salutation');
			} else if ($this->input->post('contact_name') === '') {
				$Return['error'] = $this->lang->line('xin_employee_error_contact_name');
			} else if ($this->input->post('relation') === '') {
				$Return['error'] = $this->lang->line('xin_employee_error_grp');
			} else if ($this->input->post('primary_email') === '') {
				$Return['error'] = $this->lang->line('xin_employee_error_pemail');
			} else if ($this->input->post('mobile_phone') === '') {
				$Return['error'] = $this->lang->line('xin_employee_error_mobile');
			} else if ($this->input->post('city') === '') {
				$Return['error'] = $this->lang->line('xin_error_city_field');
			} else if ($this->input->post('country') === '') {
				$Return['error'] = $this->lang->line('xin_error_country_field');
			}

			if ($Return['error'] != '') {
				$this->output($Return);
			}

			$data = array(
				'salutation' => $this->input->post('salutation'),
				'contact_name' => $this->input->post('contact_name'),
				'relation' => $this->input->post('relation'),
				'company' => $this->input->post('company'),
				'job_title' => $this->input->post('job_title'),
				'primary_email' => $this->input->post('primary_email'),
				'mobile_phone' => $this->input->post('mobile_phone'),
				'address' => $this->input->post('address'),
				'city' => $this->input->post('city'),
				'state' => $this->input->post('state'),
				'zipcode' => $this->input->post('zipcode'),
				'country' => $this->input->post('country'),
				'employee_id' => $this->input->post('user_id'),
				'contact_type' => 'permanent'
			);

			$query = $this->Employees_model->check_employee_contact_permanent($this->input->post('user_id'));
			if ($query->num_rows() > 0) {
				$res = $query->result();
				$e_field_id = $res[0]->contact_id;
				$result = $this->Employees_model->contact_info_update($data, $e_field_id);
			} else {
				$result = $this->Employees_model->contact_info_add($data);
			}

			if ($result == TRUE) {
				$Return['result'] = $this->lang->line('xin_employee_contact_info_updated');
			} else {
				$Return['error'] = $this->lang->line('xin_error_msg');
			}
			$this->output($Return);
			exit;
		}
	}

	// Validate and update info in database //  econtact info
	public function update_contact_info()
	{

		if ($this->input->post('type') == 'contact_info') {
			/* Define return | here result is used to return user data and error for error message */
			$Return = array('result' => '', 'error' => '', 'csrf_hash' => '');
			$Return['csrf_hash'] = $this->security->get_csrf_hash();

			/* Server side PHP input validation */
			/* Server side PHP input validation */
			if ($this->input->post('salutation') === '') {
				$Return['error'] = $this->lang->line('xin_employee_error_salutation');
			} else if ($this->input->post('contact_name') === '') {
				$Return['error'] = $this->lang->line('xin_employee_error_contact_name');
			} else if ($this->input->post('relation') === '') {
				$Return['error'] = $this->lang->line('xin_employee_error_grp');
			} else if ($this->input->post('primary_email') === '') {
				$Return['error'] = $this->lang->line('xin_employee_error_pemail');
			} else if ($this->input->post('mobile_phone') === '') {
				$Return['error'] = $this->lang->line('xin_employee_error_mobile');
			} else if ($this->input->post('city') === '') {
				$Return['error'] = $this->lang->line('xin_error_city_field');
			} else if ($this->input->post('country') === '') {
				$Return['error'] = $this->lang->line('xin_error_country_field');
			}

			if ($Return['error'] != '') {
				$this->output($Return);
			}

			$data = array(
				'salutation' => $this->input->post('salutation'),
				'contact_name' => $this->input->post('contact_name'),
				'relation' => $this->input->post('relation'),
				'company' => $this->input->post('company'),
				'job_title' => $this->input->post('job_title'),
				'primary_email' => $this->input->post('primary_email'),
				'mobile_phone' => $this->input->post('mobile_phone'),
				'address' => $this->input->post('address'),
				'city' => $this->input->post('city'),
				'state' => $this->input->post('state'),
				'zipcode' => $this->input->post('zipcode'),
				'country' => $this->input->post('country'),
				'employee_id' => $this->input->post('user_id'),
				'contact_type' => 'current'
			);

			$query = $this->Employees_model->check_employee_contact_current($this->input->post('user_id'));
			if ($query->num_rows() > 0) {
				$res = $query->result();
				$e_field_id = $res[0]->contact_id;
				$result = $this->Employees_model->contact_info_update($data, $e_field_id);
			} else {
				$result = $this->Employees_model->contact_info_add($data);
			}
			//$e_field_id = 1;

			if ($result == TRUE) {
				$Return['result'] = $this->lang->line('xin_employee_contact_info_updated');
			} else {
				$Return['error'] = $this->lang->line('xin_error_msg');
			}
			$this->output($Return);
			exit;
		}
	}

	// Validate and update info in database // contact info
	public function contact_info()
	{

		if ($this->input->post('type') == 'contact_info') {
			/* Define return | here result is used to return user data and error for error message */
			$Return = array('result' => '', 'error' => '', 'csrf_hash' => '');
			$Return['csrf_hash'] = $this->security->get_csrf_hash();

			/* Server side PHP input validation */
			if ($this->input->post('relation') === '') {
				$Return['error'] = $this->lang->line('xin_employee_error_relation');
			} else if ($this->input->post('contact_name') === '') {
				$Return['error'] = $this->lang->line('xin_employee_error_contact_name');
			} else if (!preg_match("/^(\pL{1,}[ ]?)+$/u", $this->input->post('contact_name'))) {
				$Return['error'] = $this->lang->line('xin_hr_string_error');
			} else if ($this->input->post('contact_no') !== '' && !preg_match('/^([0-9]*)$/', $this->input->post('contact_no'))) {
				$Return['error'] = 'Nomor Kontak harus bilangan';
			} else if ($this->input->post('work_phone') !== '' && !preg_match('/^([0-9]*)$/', $this->input->post('work_phone'))) {
				$Return['error'] = 'Nomor Telpon harus bilangan';
			} else if ($this->input->post('work_phone_extension') !== '' && !preg_match('/^([0-9]*)$/', $this->input->post('work_phone_extension'))) {
				$Return['error'] = 'Nomor Telpon harus bilangan';
			} else if ($this->input->post('mobile_phone') === '') {
				$Return['error'] = $this->lang->line('xin_employee_error_mobile');
			} else if (!preg_match('/^([0-9]*)$/', $this->input->post('mobile_phone'))) {
				$Return['error'] = 'Nomor HP harus bilangan';
			} else if ($this->input->post('home_phone') !== '' && !preg_match('/^([0-9]*)$/', $this->input->post('home_phone'))) {
				$Return['error'] = 'Nomor Telpon harus bilangan';
			} else if ($this->input->post('work_email') === '') {
				$Return['error'] = $this->lang->line('xin_employee_error_email');
			} else if (!filter_var($this->input->post('work_email'), FILTER_VALIDATE_EMAIL)) {
				$Return['error'] = $this->lang->line('xin_employee_error_invalid_email');
			} else if ($this->input->post('personal_email') !== '' && !filter_var($this->input->post('personal_email'), FILTER_VALIDATE_EMAIL)) {
				$Return['error'] = $this->lang->line('xin_employee_error_invalid_email');
			} else if ($this->input->post('zipcode') !== '' && !preg_match('/^([0-9]*)$/', $this->input->post('zipcode'))) {
				$Return['error'] = 'Kode Pos harus bilangan';
			}

			if (null != $this->input->post('is_primary')) {
				$is_primary = $this->input->post('is_primary');
			} else {
				$is_primary = '';
			}
			if (null != $this->input->post('is_dependent')) {
				$is_dependent = $this->input->post('is_dependent');
			} else {
				$is_dependent = '';
			}

			if ($Return['error'] != '') {
				$this->output($Return);
			}
			$contact_name = $this->Core_model->clean_post($this->input->post('contact_name'));
			$address_1 = $this->Core_model->clean_post($this->input->post('address_1'));
			$address_2 = $this->Core_model->clean_post($this->input->post('address_2'));
			$city = $this->Core_model->clean_post($this->input->post('city'));
			$state = $this->Core_model->clean_post($this->input->post('state'));

			$data = array(
				'relation' => $this->input->post('relation'),
				'work_email' => $this->input->post('work_email'),
				'is_primary' => $is_primary,
				'is_dependent' => $is_dependent,
				'personal_email' => $this->input->post('personal_email'),
				'contact_name' => $contact_name,
				'address_1' => $address_1,
				'work_phone' => $this->input->post('work_phone'),
				'work_phone_extension' => $this->input->post('work_phone_extension'),
				'address_2' => $address_2,
				'mobile_phone' => $this->input->post('mobile_phone'),
				'city' => $city,
				'state' => $state,
				'zipcode' => $this->input->post('zipcode'),
				'home_phone' => $this->input->post('home_phone'),
				'country' => $this->input->post('country'),
				'employee_id' => $this->input->post('user_id'),
				'created_at' => date('d-m-Y'),
			);
			$result = $this->Employees_model->contact_info_add($data);
			if ($result == TRUE) {
				$Return['result'] = $this->lang->line('xin_employee_contact_info_added');
			} else {
				$Return['error'] = $this->lang->line('xin_error_msg');
			}
			$this->output($Return);
			exit;
		}
	}

	// Validate and update info in database //  econtact info
	public function e_contact_info()
	{

		if ($this->input->post('type') == 'e_contact_info') {
			/* Define return | here result is used to return user data and error for error message */
			$Return = array('result' => '', 'error' => '', 'csrf_hash' => '');
			$Return['csrf_hash'] = $this->security->get_csrf_hash();

			/* Server side PHP input validation */
			if ($this->input->post('relation') === '') {
				$Return['error'] = $this->lang->line('xin_employee_error_relation');
			} else if ($this->input->post('contact_name') === '') {
				$Return['error'] = $this->lang->line('xin_employee_error_contact_name');
			} else if ($this->input->post('mobile_phone') === '') {
				$Return['error'] = $this->lang->line('xin_employee_error_mobile');
			}

			if (null != $this->input->post('is_primary')) {
				$is_primary = $this->input->post('is_primary');
			} else {
				$is_primary = '';
			}
			if (null != $this->input->post('is_dependent')) {
				$is_dependent = $this->input->post('is_dependent');
			} else {
				$is_dependent = '';
			}

			if ($Return['error'] != '') {
				$this->output($Return);
			}

			$data = array(
				'relation' => $this->input->post('relation'),
				'work_email' => $this->input->post('work_email'),
				'is_primary' => $is_primary,
				'is_dependent' => $is_dependent,
				'personal_email' => $this->input->post('personal_email'),
				'contact_name' => $this->input->post('contact_name'),
				'address_1' => $this->input->post('address_1'),
				'work_phone' => $this->input->post('work_phone'),
				'work_phone_extension' => $this->input->post('work_phone_extension'),
				'address_2' => $this->input->post('address_2'),
				'mobile_phone' => $this->input->post('mobile_phone'),
				'city' => $this->input->post('city'),
				'state' => $this->input->post('state'),
				'zipcode' => $this->input->post('zipcode'),
				'home_phone' => $this->input->post('home_phone'),
				'country' => $this->input->post('country')
			);

			$e_field_id = $this->input->post('e_field_id');
			$result = $this->Employees_model->contact_info_update($data, $e_field_id);
			if ($result == TRUE) {
				$Return['result'] = $this->lang->line('xin_employee_contact_info_updated');
			} else {
				$Return['error'] = $this->lang->line('xin_error_msg');
			}
			$this->output($Return);
			exit;
		}
	}

	// ===================================================================
	// pendidikan
	// =======================================================================
	// Validate and add info in database // qualification info
	public function qualification_info()
	{

		if ($this->input->post('type') == 'qualification_info') {
			/* Define return | here result is used to return user data and error for error message */
			$Return = array('result' => '', 'error' => '', 'csrf_hash' => '');
			$Return['csrf_hash'] = $this->security->get_csrf_hash();

			/* Server side PHP input validation */
			$from_year = $this->input->post('from_year');
			$to_year = $this->input->post('to_year');
			$st_date = strtotime($from_year);
			$ed_date = strtotime($to_year);

			if ($this->input->post('name') === '') {
				$Return['error'] = $this->lang->line('xin_employee_error_sch_uni');
			} else if (preg_match("/^(\pL{1,}[ ]?)+$/u", $this->input->post('name')) != 1) {
				$Return['error'] = $this->lang->line('xin_hr_string_error');
			} else if ($this->input->post('from_year') === '') {
				$Return['error'] = $this->lang->line('xin_employee_error_frm_date');
			} else if ($this->Core_model->validate_date($this->input->post('from_year'), 'Y-m-d') == false) {
				$Return['error'] = $this->lang->line('xin_hr_date_format_error');
			} else if ($this->input->post('to_year') === '') {
				$Return['error'] = $this->lang->line('xin_employee_error_to_date');
			} else if ($this->Core_model->validate_date($this->input->post('to_year'), 'Y-m-d') == false) {
				$Return['error'] = $this->lang->line('xin_hr_date_format_error');
			} else if ($st_date > $ed_date) {
				$Return['error'] = $this->lang->line('xin_employee_error_date_shouldbe');
			}

			if ($Return['error'] != '') {
				$this->output($Return);
			}

			$name = $this->Core_model->clean_post($this->input->post('name'));
			$from_year = $this->Core_model->clean_date_post($this->input->post('from_year'));
			$to_year = $this->Core_model->clean_date_post($this->input->post('to_year'));
			$description = $this->Core_model->clean_post($this->input->post('description'));
			$data = array(
				'name' => $name,
				'education_level_id' => $this->input->post('education_level'),
				'from_year' => $from_year,
				'language_id' => $this->input->post('language'),
				'to_year' => $this->input->post('to_year'),
				'skill_id' => $this->input->post('skill'),
				'description' => $description,
				'employee_id' => $this->input->post('user_id'),
				'created_at' => date('d-m-Y'),
			);
			$result = $this->Employees_model->qualification_info_add($data);
			if ($result == TRUE) {
				$Return['result'] = $this->lang->line('xin_employee_error_q_info_added');
			} else {
				$Return['error'] = $this->lang->line('xin_error_msg');
			}
			$this->output($Return);
			exit;
		}
	}

	// Validate and add info in database // qualification info
	public function e_qualification_info()
	{

		if ($this->input->post('type') == 'e_qualification_info') {
			/* Define return | here result is used to return user data and error for error message */
			$Return = array('result' => '', 'error' => '', 'csrf_hash' => '');
			$Return['csrf_hash'] = $this->security->get_csrf_hash();

			/* Server side PHP input validation */
			$from_year = $this->input->post('from_year');
			$to_year = $this->input->post('to_year');
			$st_date = strtotime($from_year);
			$ed_date = strtotime($to_year);

			if ($this->input->post('name') === '') {
				$Return['error'] = $this->lang->line('xin_employee_error_sch_uni');
			} else if ($this->input->post('from_year') === '') {
				$Return['error'] = $this->lang->line('xin_employee_error_frm_date');
			} else if ($this->input->post('to_year') === '') {
				$Return['error'] = $this->lang->line('xin_employee_error_to_date');
			} else if ($st_date > $ed_date) {
				$Return['error'] = $this->lang->line('xin_employee_error_date_shouldbe');
			}

			if ($Return['error'] != '') {
				$this->output($Return);
			}

			$data = array(
				'name' => $this->input->post('name'),
				'education_level_id' => $this->input->post('education_level'),
				'from_year' => $this->input->post('from_year'),
				'language_id' => $this->input->post('language'),
				'to_year' => $this->input->post('to_year'),
				'skill_id' => $this->input->post('skill'),
				'description' => $this->input->post('description')
			);
			$e_field_id = $this->input->post('e_field_id');
			$result = $this->Employees_model->qualification_info_update($data, $e_field_id);
			if ($result == TRUE) {
				$Return['result'] = $this->lang->line('xin_employee_error_q_info_updated');
			} else {
				$Return['error'] = $this->lang->line('xin_error_msg');
			}
			$this->output($Return);
			exit;
		}
	}

	// Validate and add info in database // work experience info
	public function work_experience_info()
	{

		if ($this->input->post('type') == 'work_experience_info') {
			/* Define return | here result is used to return user data and error for error message */
			$Return = array('result' => '', 'error' => '', 'csrf_hash' => '');
			$Return['csrf_hash'] = $this->security->get_csrf_hash();

			$frm_date = strtotime($this->input->post('from_date'));
			$to_date = strtotime($this->input->post('to_date'));
			/* Server side PHP input validation */
			if ($this->input->post('company_name') === '') {
				$Return['error'] = $this->lang->line('xin_employee_error_company_name');
			} else if ($this->input->post('post') === '') {
				$Return['error'] = $this->lang->line('xin_employee_error_post');
			} else if ($this->input->post('from_date') === '') {
				$Return['error'] = $this->lang->line('xin_employee_error_frm_date');
			} else if ($this->input->post('to_date') === '') {
				$Return['error'] = $this->lang->line('xin_employee_error_to_date');
			} else if ($frm_date > $to_date) {
				$Return['error'] = $this->lang->line('xin_employee_error_date_shouldbe');
			}

			if ($Return['error'] != '') {
				$this->output($Return);
			}

			$data = array(
				'company_name' => $this->input->post('company_name'),
				'from_date' => $this->input->post('from_date'),
				'to_date' => $this->input->post('to_date'),
				'post' => $this->input->post('post'),
				'description' => $this->input->post('description'),
				'employee_id' => $this->input->post('user_id'),
				'created_at' => date('d-m-Y'),
			);
			$result = $this->Employees_model->work_experience_info_add($data);
			if ($result == TRUE) {
				$Return['result'] = $this->lang->line('xin_employee_error_w_exp_added');
			} else {
				$Return['error'] = $this->lang->line('xin_error_msg');
			}
			$this->output($Return);
			exit;
		}
	}

	public function e_work_experience_info()
	{

		if ($this->input->post('type') == 'e_work_experience_info') {
			/* Define return | here result is used to return user data and error for error message */
			$Return = array('result' => '', 'error' => '', 'csrf_hash' => '');
			$Return['csrf_hash'] = $this->security->get_csrf_hash();

			$frm_date = strtotime($this->input->post('from_date'));
			$to_date = strtotime($this->input->post('to_date'));
			/* Server side PHP input validation */
			if ($this->input->post('company_name') === '') {
				$Return['error'] = $this->lang->line('xin_employee_error_company_name');
			} else if ($this->input->post('from_date') === '') {
				$Return['error'] = $this->lang->line('xin_employee_error_frm_date');
			} else if ($this->input->post('to_date') === '') {
				$Return['error'] = $this->lang->line('xin_employee_error_to_date');
			} else if ($frm_date > $to_date) {
				$Return['error'] = $this->lang->line('xin_employee_error_date_shouldbe');
			} else if ($this->input->post('post') === '') {
				$Return['error'] = $this->lang->line('xin_employee_error_post');
			}

			if ($Return['error'] != '') {
				$this->output($Return);
			}

			$data = array(
				'company_name' => $this->input->post('company_name'),
				'from_date' => $this->input->post('from_date'),
				'to_date' => $this->input->post('to_date'),
				'post' => $this->input->post('post'),
				'description' => $this->input->post('description')
			);
			$e_field_id = $this->input->post('e_field_id');
			$result = $this->Employees_model->work_experience_info_update($data, $e_field_id);
			if ($result == TRUE) {
				$Return['result'] = $this->lang->line('xin_employee_error_w_exp_updated');
			} else {
				$Return['error'] = $this->lang->line('xin_error_msg');
			}
			$this->output($Return);
			exit;
		}
	}



	// Validate and add info in database // location info
	public function location_info()
	{

		if ($this->input->post('type') == 'location_info') {
			/* Define return | here result is used to return user data and error for error message */
			$Return = array('result' => '', 'error' => '', 'csrf_hash' => '');
			$Return['csrf_hash'] = $this->security->get_csrf_hash();

			/* Server side PHP input validation */
			if ($this->input->post('from_date') === '') {
				$Return['error'] = $this->lang->line('xin_employee_error_frm_date');
			} else if ($this->input->post('location_id') === '') {
				$Return['error'] = $this->lang->line('error_location_dept_field');
			}

			if ($Return['error'] != '') {
				$this->output($Return);
			}

			$data = array(
				'from_date' => $this->input->post('from_date'),
				'to_date' => $this->input->post('to_date'),
				'location_id' => $this->input->post('location_id'),
				'employee_id' => $this->input->post('user_id'),
				'created_at' => date('d-m-Y'),
			);
			$result = $this->Employees_model->location_info_add($data);
			if ($result == TRUE) {
				$Return['result'] = $this->lang->line('xin_employee_location_info_added');
			} else {
				$Return['error'] = $this->lang->line('xin_error_msg');
			}
			$this->output($Return);
			exit;
		}
	}

	// Validate and add info in database // elocation info
	public function e_location_info()
	{

		if ($this->input->post('type') == 'e_location_info') {
			/* Define return | here result is used to return user data and error for error message */
			$Return = array('result' => '', 'error' => '', 'csrf_hash' => '');
			$Return['csrf_hash'] = $this->security->get_csrf_hash();

			/* Server side PHP input validation */
			if ($this->input->post('from_date') === '') {
				$Return['error'] = $this->lang->line('xin_employee_error_frm_date');
			} else if ($this->input->post('location_id') === '') {
				$Return['error'] = $this->lang->line('error_location_dept_field');
			}

			if ($Return['error'] != '') {
				$this->output($Return);
			}

			$data = array(
				'from_date' => $this->input->post('from_date'),
				'to_date' => $this->input->post('to_date')
			);
			$e_field_id = $this->input->post('e_field_id');
			$result = $this->Employees_model->location_info_update($data, $e_field_id);
			if ($result == TRUE) {
				$Return['result'] = $this->lang->line('xin_employee_location_info_updated');
			} else {
				$Return['error'] = $this->lang->line('xin_error_msg');
			}
			$this->output($Return);
			exit;
		}
	}

	// Validate and update info in database // change password
	public function change_password()
	{

		if ($this->input->post('type') == 'change_password') {
			/* Define return | here result is used to return user data and error for error message */
			$Return = array('result' => '', 'error' => '', 'csrf_hash' => '');
			$Return['csrf_hash'] = $this->security->get_csrf_hash();

			/* Server side PHP input validation */
			if (trim($this->input->post('old_password')) === '') {
				$Return['error'] = $this->lang->line('xin_old_password_error_field');
			} else if ($this->Employees_model->check_old_password($this->input->post('old_password'), $this->input->post('user_id')) != 1) {
				$Return['error'] = $this->lang->line('xin_old_password_does_not_match');
			} else if (trim($this->input->post('new_password')) === '') {
				$Return['error'] = $this->lang->line('xin_employee_error_newpassword');
			} else if (strlen($this->input->post('new_password')) < 6) {
				$Return['error'] = $this->lang->line('xin_employee_error_password_least');
			} else if (trim($this->input->post('new_password_confirm')) === '') {
				$Return['error'] = $this->lang->line('xin_employee_error_new_cpassword');
			} else if ($this->input->post('new_password') != $this->input->post('new_password_confirm')) {
				$Return['error'] = $this->lang->line('xin_employee_error_old_new_cpassword');
			}

			if ($Return['error'] != '') {
				$this->output($Return);
			}
			$options = array('cost' => 12);
			$password_hash = password_hash($this->input->post('new_password'), PASSWORD_BCRYPT, $options);

			$data = array(
				'password' => $password_hash
			);
			$id = $this->input->post('user_id');
			$result = $this->Employees_model->change_password($data, $id);
			if ($result == TRUE) {
				$Return['result'] = $this->lang->line('xin_employee_password_update');
			} else {
				$Return['error'] = $this->lang->line('xin_error_msg');
			}
			$this->output($Return);
			exit;
		}
	}


	// employee contacts - listing
	public function contacts()
	{

		$data['title'] = $this->Core_model->site_title();
		$session = $this->session->userdata('username');
		if (!empty($session)) {
			$this->load->view("admin/employees_active/employee_detail", $data);
		} else {
			redirect('admin/');
		}
		// Datatables Variables
		$draw = intval($this->input->get("draw"));
		$start = intval($this->input->get("start"));
		$length = intval($this->input->get("length"));

		$id = $this->uri->segment(4);
		$contacts = $this->Employees_model->set_employee_contacts($id);

		$data = array();

		foreach ($contacts->result() as $r) {

			if ($r->is_primary == 1) {
				$primary = '<span class="tag tag-success">' . $this->lang->line('xin_employee_primary') . '</span>';
			} else {
				$primary = '';
			}
			if ($r->is_dependent == 2) {
				$dependent = '<span class="tag tag-danger">' . $this->lang->line('xin_employee_dependent') . '</span>';
			} else {
				$dependent = '';
			}

			$data[] = array(
				'<span data-toggle="tooltip" data-placement="top" title="' . $this->lang->line('xin_edit') . '"><button type="button" class="btn btn-secondary btn-sm m-b-0-0 waves-effect waves-light" data-toggle="modal" data-target=".edit-modal-data" data-field_id="' . $r->contact_id . '" data-field_type="contact"><i class="fa fa-pencil-square-o"></i></button></span><span data-toggle="tooltip" data-placement="top" title="' . $this->lang->line('xin_delete') . '"><button type="button" class="btn btn-danger btn-sm m-b-0-0 waves-effect waves-light delete" data-toggle="modal" data-target=".delete-modal" data-record-id="' . $r->contact_id . '" data-token_type="contact"><i class="fa fa-trash-o"></i></button></span>',
				$r->contact_name . ' ' . $primary . ' ' . $dependent,
				$r->relation,
				$r->work_email,
				$r->mobile_phone
			);
		}

		$output = array(
			"draw" => $draw,
			"recordsTotal" => $contacts->num_rows(),
			"recordsFiltered" => $contacts->num_rows(),
			"data" => $data
		);
		$this->output->set_output(json_encode($output));
	}


	// employee qualification - listing
	public function qualification()
	{
		//set data
		$data['title'] = $this->Core_model->site_title();
		$session = $this->session->userdata('username');
		if (!empty($session)) {
			$this->load->view("admin/employees_active/employee_detail", $data);
		} else {
			redirect('admin/');
		}
		// Datatables Variables
		$draw = intval($this->input->get("draw"));
		$start = intval($this->input->get("start"));
		$length = intval($this->input->get("length"));

		$id = $this->uri->segment(4);
		$qualification = $this->Employees_model->set_employee_qualification($id);

		$data = array();

		foreach ($qualification->result() as $r) {

			$education = $this->Employees_model->read_education_information($r->education_level_id);
			if (!is_null($education)) {
				$edu_name = $education[0]->name;
			} else {
				$edu_name = '?';
			}

			$sdate = $this->Core_model->set_date_format($r->from_year);
			$edate = $this->Core_model->set_date_format($r->to_year);

			$time_period = $sdate . ' - ' . $edate;
			// get date
			$pdate = $time_period;
			$data[] = array(
				'<span data-toggle="tooltip" data-placement="top" title="' . $this->lang->line('xin_edit') . '"><button type="button" class="btn btn-secondary btn-sm m-b-0-0 waves-effect waves-light" data-toggle="modal" data-target=".edit-modal-data" data-field_id="' . $r->qualification_id . '" data-field_type="qualification"><i class="fa fa-pencil-square-o"></i></button></span><span data-toggle="tooltip" data-placement="top" title="' . $this->lang->line('xin_delete') . '"><button type="button" class="btn btn-danger btn-sm m-b-0-0 waves-effect waves-light delete" data-toggle="modal" data-target=".delete-modal" data-record-id="' . $r->qualification_id . '" data-token_type="qualification"><i class="fa fa-trash-o"></i></button></span>',
				$r->name,
				$pdate,
				$edu_name
			);
		}

		$output = array(
			"draw" => $draw,
			"recordsTotal" => $qualification->num_rows(),
			"recordsFiltered" => $qualification->num_rows(),
			"data" => $data
		);
		$this->output->set_output(json_encode($output));
	}

	// employee work experience - listing
	public function experience()
	{
		//set data
		$data['title'] = $this->Core_model->site_title();
		$session = $this->session->userdata('username');
		if (!empty($session)) {
			$this->load->view("admin/employees_active/employee_detail", $data);
		} else {
			redirect('admin/');
		}
		// Datatables Variables
		$draw = intval($this->input->get("draw"));
		$start = intval($this->input->get("start"));
		$length = intval($this->input->get("length"));

		$id = $this->uri->segment(4);
		$experience = $this->Employees_model->set_employee_experience($id);

		$data = array();

		foreach ($experience->result() as $r) {

			$from_date = $this->Core_model->set_date_format($r->from_date);
			$to_date = $this->Core_model->set_date_format($r->to_date);


			$data[] = array(
				'<span data-toggle="tooltip" data-placement="top" title="' . $this->lang->line('xin_edit') . '"><button type="button" class="btn btn-secondary btn-sm m-b-0-0 waves-effect waves-light" data-toggle="modal" data-target=".edit-modal-data" data-field_id="' . $r->work_experience_id . '" data-field_type="work_experience"><i class="fa fa-pencil-square-o"></i></button></span><span data-toggle="tooltip" data-placement="top" title="' . $this->lang->line('xin_delete') . '"><button type="button" class="btn btn-danger btn-sm m-b-0-0 waves-effect waves-light delete" data-toggle="modal" data-target=".delete-modal" data-record-id="' . $r->work_experience_id . '" data-token_type="work_experience"><i class="fa fa-trash-o"></i></button></span>',
				$r->company_name,
				$from_date,
				$to_date,
				$r->post,
				$r->description
			);
		}

		$output = array(
			"draw" => $draw,
			"recordsTotal" => $experience->num_rows(),
			"recordsFiltered" => $experience->num_rows(),
			"data" => $data
		);
		$this->output->set_output(json_encode($output));
	}




	// =====================================================================================
	// PERSONLIA
	// =====================================================================================

	public function award()
	{
		//set data
		$data['title'] = $this->Core_model->site_title();
		$session = $this->session->userdata('username');
		if (!empty($session)) {
			$this->load->view("admin/employees_active/employee_lihat", $data);
		} else {
			redirect('admin/');
		}
		// Datatables Variables
		$draw = intval($this->input->get("draw"));
		$start = intval($this->input->get("start"));
		$length = intval($this->input->get("length"));

		$id = $this->uri->segment(4);
		$award = $this->Awards_model->get_employee_awards($id);

		$data = array();

		foreach ($award->result() as $r) {

			// get user > added by
			$user = $this->Core_model->read_user_info($r->employee_id);
			// user full name
			if (!is_null($user)) {
				$full_name = $user[0]->first_name . ' ' . $user[0]->last_name;
			} else {
				$full_name = '--';
			}
			// get award type
			$award_type = $this->Awards_model->read_award_type_information($r->award_type_id);
			if (!is_null($award_type)) {
				$award_type = $award_type[0]->award_type;
			} else {
				$award_type = '--';
			}

			$d = explode('-', $r->award_month_year);
			$get_month = date('F', mktime(0, 0, 0, $d[1], 10));
			$award_month = $get_month . ', ' . $d[0];
			// get currency
			if ($r->cash_price == '') {
				$currency = $this->Core_model->currency_sign(0);
			} else {
				$currency = $this->Core_model->currency_sign($r->cash_price);
			}
			// get company
			$company = $this->Core_model->read_company_info($r->company_id);
			if (!is_null($company)) {
				$comp_name = $company[0]->name;
			} else {
				$comp_name = '--';
			}


			// if(in_array('0514',$role_resources_ids)) { //view
			$view = '<span data-toggle="tooltip" data-placement="top" title="' . $this->lang->line('xin_view') . '"><button type="button" class="btn icon-btn btn-xs btn-default waves-effect waves-light" data-toggle="modal" data-target=".view-modal-data" data-xfield_id="' . $r->award_id . '" data-field_type="awards"><span class="fa fa-eye"></span> Lihat </button></span>';
			// } else {
			// 	$view = '';
			// }

			$award_info = $r->description;
			$combhr = $view;

			$data[] = array(
				$combhr,
				$r->award_date,
				$award_month,
				$award_type,
				$r->gift_item,
				$award_info
			);
		}

		$output = array(
			"draw" => $draw,
			"recordsTotal" => $award->num_rows(),
			"recordsFiltered" => $award->num_rows(),
			"data" => $data
		);
		$this->output->set_output(json_encode($output));
	}

	public function warning()
	{
		//set data
		$data['title'] = $this->Core_model->site_title();
		$session = $this->session->userdata('username');
		if (!empty($session)) {
			$this->load->view("admin/employees_active/employee_lihat", $data);
		} else {
			redirect('admin/');
		}
		// Datatables Variables
		$draw = intval($this->input->get("draw"));
		$start = intval($this->input->get("start"));
		$length = intval($this->input->get("length"));

		$id = $this->uri->segment(4);
		$warning = $this->Warning_model->get_employee_warning($id);

		$data = array();

		foreach ($warning->result() as $r) {


			// get user > warning by
			$user_by = $this->Core_model->read_user_info_detail($r->warning_by);
			// user full name
			if (!is_null($user_by)) {
				$warning_by = $user_by[0]->first_name . ' ' . $user_by[0]->last_name . '<br><small class="text-muted">' . $user_by[0]->designation_name . '</small>';
			} else {
				$warning_by = '--';
			}
			// get warning date
			$warning_date = $this->Core_model->set_date_format($r->warning_date);


			// get warning type
			$warning_type = $this->Warning_model->read_warning_type_information($r->warning_type_id);
			if (!is_null($warning_type)) {
				$wtype = $warning_type[0]->type;
			} else {
				$wtype = '--';
			}

			$view = '<span data-toggle="tooltip" data-placement="top" title="' . $this->lang->line('xin_view') . '"><button type="button" class="btn icon-btn btn-xs btn-default waves-effect waves-light" data-toggle="modal" data-target=".view-modal-data" data-xfield_id="' . $r->warning_id . '" data-field_type="warning"><span class="fa fa-eye"></span> Lihat </button></span>';


			$combhr = $view;

			$data[] = array(
				$combhr,
				$warning_date,
				$r->subject,
				$wtype,
				$warning_by,
				$r->description
			);
		}

		$output = array(
			"draw" => $draw,
			"recordsTotal" => $warning->num_rows(),
			"recordsFiltered" => $warning->num_rows(),
			"data" => $data
		);
		$this->output->set_output(json_encode($output));
	}

	public function travel()
	{
		//set data
		$data['title'] = $this->Core_model->site_title();
		$session = $this->session->userdata('username');
		if (!empty($session)) {
			$this->load->view("admin/employees_active/employee_lihat", $data);
		} else {
			redirect('admin/');
		}
		// Datatables Variables
		$draw = intval($this->input->get("draw"));
		$start = intval($this->input->get("start"));
		$length = intval($this->input->get("length"));

		$id = $this->uri->segment(4);
		$travel = $this->Travel_model->get_employee_travel($id);

		$data = array();

		foreach ($travel->result() as $r) {


			// get user > employee_
			$employee = $this->Core_model->read_user_info($r->employee_id);
			// employee full name
			if (!is_null($employee)) {
				$employee_name = $employee[0]->first_name . ' ' . $employee[0]->last_name;
			} else {
				$employee_name = '--';
			}
			// get start date
			$start_date = $this->Core_model->set_date_format($r->start_date);
			// get end date
			$end_date = $this->Core_model->set_date_format($r->end_date);
			// get company
			$company = $this->Core_model->read_company_info($r->company_id);
			if (!is_null($company)) {
				$comp_name = $company[0]->name;
			} else {
				$comp_name = '--';
			}
			// status
			//if($r->status==0): $status = $this->lang->line('xin_pending');
			//elseif($r->status==1): $status = $this->lang->line('xin_accepted'); else: $status = $this->lang->line('xin_rejected'); endif;
			if ($r->status == 0) : $status = '<span class="badge bg-orange">' . $this->lang->line('xin_pending') . '</span>';
			elseif ($r->status == 1) : $status = '<span class="badge bg-green">' . $this->lang->line('xin_accepted') . '</span>';
			else : $status = '<span class="badge bg-red">' . $this->lang->line('xin_rejected');
			endif;

			if ($r->travel_mode == 1) {
				$transport = '' . $this->lang->line('xin_by_bus') . '';
			} else if ($r->travel_mode == 2) {
				$transport = '' . $this->lang->line('xin_by_train') . '';
			} else if ($r->travel_mode == 3) {
				$transport = '' . $this->lang->line('xin_by_plane') . '';
			} else if ($r->travel_mode == 4) {
				$transport = '' . $this->lang->line('xin_by_taxi') . '';
			} else if ($r->travel_mode == 5) {
				$transport = '' . $this->lang->line('xin_by_rental_car') . '';
			} else if ($r->travel_mode == 6) {
				$transport = '' . $this->lang->line('xin_by_privat_car') . '';
			} else if ($r->travel_mode == 7) {
				$transport = '' . $this->lang->line('xin_by_pribadi_motor') . '';
			} else if ($r->travel_mode == 8) {
				$transport = '' . $this->lang->line('xin_by_pribadi_car') . '';
			}

			$view = '<span data-toggle="tooltip" data-placement="top" title="' . $this->lang->line('xin_view') . '"><button type="button" class="btn icon-btn btn-xs btn-default waves-effect waves-light" data-toggle="modal" data-target=".view-modal-data" data-xfield_id="' . $r->travel_id . '" data-field_type="travel"><span class="fa fa-eye"></span> Lihat </button></span>';


			$combhr = $view;

			$data[] = array(
				$combhr,
				$start_date,
				$end_date,
				$r->visit_place,
				$transport,
				$r->description,
			);
		}

		$output = array(
			"draw" => $draw,
			"recordsTotal" => $travel->num_rows(),
			"recordsFiltered" => $travel->num_rows(),
			"data" => $data
		);
		$this->output->set_output(json_encode($output));
	}

	public function aset()
	{
		//set data
		$data['title'] = $this->Core_model->site_title();
		$session = $this->session->userdata('username');
		if (!empty($session)) {
			$this->load->view("admin/employees_active/employee_lihat", $data);
		} else {
			redirect('admin/');
		}
		// Datatables Variables
		$draw = intval($this->input->get("draw"));
		$start = intval($this->input->get("start"));
		$length = intval($this->input->get("length"));

		$id = $this->uri->segment(4);
		$aset = $this->Assets_model->get_employee_aset($id);

		$data = array();

		foreach ($aset->result() as $r) {

			// get company
			$company = $this->Core_model->read_company_info($r->company_id);
			if (!is_null($company)) {
				$comp_name = $company[0]->name;
			} else {
				$comp_name = '--';
			}

			// get user > employee_
			$employee = $this->Core_model->read_user_info($r->employee_id);
			// employee full name
			if (!is_null($employee)) {
				$employee_name = $employee[0]->first_name . ' ' . $employee[0]->last_name;
			} else {
				$employee_name = '--';
			}
			// get start date
			$pinjam_date = $this->Core_model->set_date_format($r->pinjam_date);
			// get end date
			$kembali_date = $this->Core_model->set_date_format($r->kembali_date);


			if ($r->is_pinjam == 1) : $status = '<span class="badge bg-orange"> Dipinjam </span>';
			elseif ($r->is_pinjam == 2) : $status = '<span class="badge bg-green"> Dikembalikan </span>';
			else : $status = '<span class="badge bg-red"> Dihilangkan </span>';
			endif;

			$asset_category = $this->Core_model->read_asset_category_info($r->category_id);
			if (!is_null($asset_category)) {
				$asset_category_name = $asset_category[0]->category_name;
			} else {
				$asset_category_name = '--';
			}

			$asset_pinjam = $this->Core_model->read_asset_info($r->assets_id);
			if (!is_null($asset_pinjam)) {
				$asset_pinjam_name = $asset_pinjam[0]->name;
				$asset_pinjam_kode = $asset_pinjam[0]->company_asset_code;
			} else {
				$asset_pinjam_name = '--';
				$asset_pinjam_kode = '--';
			}



			$view = '<span data-toggle="tooltip" data-placement="top" title="' . $this->lang->line('xin_view') . '"><button type="button" class="btn icon-btn btn-xs btn-default waves-effect waves-light" data-toggle="modal" data-target=".view-modal-data" data-xfield_id="' . $r->pinjam_id . '" data-field_type="travel"><span class="fa fa-eye"></span> Lihat </button></span>';


			$combhr = $view;

			if ($r->kembali_date == '0000-00-00') {
				$kembali = 'Belum Dikembalikan';
			} else {
				$kembali =  date("d-m-Y", strtotime($r->kembali_date));
			}

			$data[] = array(
				$combhr,
				$pinjam_date,
				$kembali,
				$status,
				$asset_category_name,
				$asset_pinjam_name,
				$r->asset_note
			);
		}

		$output = array(
			"draw" => $draw,
			"recordsTotal" => $aset->num_rows(),
			"recordsFiltered" => $aset->num_rows(),
			"data" => $data
		);
		$this->output->set_output(json_encode($output));
	}

	public function transfers()
	{
		//set data
		$data['title'] = $this->Core_model->site_title();
		$session = $this->session->userdata('username');
		if (!empty($session)) {
			$this->load->view("admin/employees_active/employee_lihat", $data);
		} else {
			redirect('admin/');
		}
		// Datatables Variables
		$draw = intval($this->input->get("draw"));
		$start = intval($this->input->get("start"));
		$length = intval($this->input->get("length"));

		$id = $this->uri->segment(4);
		$transfers = $this->Transfers_model->get_employee_transfers($id);

		$data = array();

		foreach ($transfers->result() as $r) {

			// =================================================================================================
			// get date
			// =================================================================================================
			$transfer_date = $this->Core_model->set_date_format($r->transfer_date);

			// =================================================================================================
			// Data Lama
			// =================================================================================================
			// 01. Perusahaan
			$company_old = $this->Company_model->read_company_information($r->company_id_old);
			if (!is_null($company_old)) {
				$company_name_old = $company_old[0]->name;
			} else {
				$company_name_old = '--';
			}
			// 02. Location
			$location_old = $this->Location_model->read_location_information($r->location_id_old);
			if (!is_null($location_old)) {
				$location_name_old = $location_old[0]->location_name;
			} else {
				$location_name_old = '--';
			}
			// 03. Departemen
			$department_old = $this->Department_model->read_department_information($r->department_id_old);
			if (!is_null($department_old)) {
				$department_name_old = $department_old[0]->department_name;
			} else {
				$department_name_old = '--';
			}
			// 04. Posisi
			$designation_old = $this->Designation_model->read_designation_information($r->designation_id_old);
			if (!is_null($designation_old)) {
				$designation_name_old = $designation_old[0]->designation_name;
			} else {
				$designation_name_old = '--';
			}
			// 05 Jenis Gaji
			$wages_old = $this->Designation_model->read_wages_information($r->wages_type_id_old);
			if (!is_null($wages_old)) {
				$wages_name_old = $wages_old[0]->jenis_gaji_name;
			} else {
				$wages_name_old = '--';
			}

			$data_lama = $company_name_old . '<br>' . $location_name_old . '<br>' . $department_name_old . '<br>' . $designation_name_old . '<br>' . $wages_name_old;

			// =================================================================================================
			// Data Mutasi
			// =================================================================================================
			// 01. Perusahaan
			$company = $this->Company_model->read_company_information($r->transfer_company);
			if (!is_null($company)) {
				$company_name = $company[0]->name;
			} else {
				$company_name = '--';
			}
			// 02. Location
			$location = $this->Location_model->read_location_information($r->transfer_location);
			if (!is_null($location)) {
				$location_name = $location[0]->location_name;
			} else {
				$location_name = '--';
			}
			// 03. Departemen
			$department = $this->Department_model->read_department_information($r->transfer_department);
			if (!is_null($department)) {
				$department_name = $department[0]->department_name;
			} else {
				$department_name = '--';
			}
			// 04. Posisi
			$designation = $this->Designation_model->read_designation_information($r->transfer_designation);
			if (!is_null($designation)) {
				$designation_name = $designation[0]->designation_name;
			} else {
				$designation_name = '--';
			}
			// 05 Jenis Gaji
			$wages = $this->Designation_model->read_wages_information($r->transfer_wages_type);
			if (!is_null($wages)) {
				$wages_name = $wages[0]->jenis_gaji_name;
			} else {
				$wages_name = '--';
			}

			$data_baru = $company_name . '<br>' . $location_name . '<br>' . $department_name . '<br>' . $designation_name . '<br>' . $wages_name;


			$view = '<span data-toggle="tooltip" data-placement="top" title="' . $this->lang->line('xin_view') . '"><button type="button" class="btn icon-btn btn-xs btn-default waves-effect waves-light" data-toggle="modal" data-target=".view-modal-data" data-xfield_id="' . $r->transfer_id . '" data-field_type="transfers"><span class="fa fa-eye"></span> Lihat </button></span>';


			$combhr = $view;

			$data[] = array(
				$combhr,
				$transfer_date,
				$data_lama,
				$data_baru,
				$r->description,
			);
		}

		$output = array(
			"draw" => $draw,
			"recordsTotal" => $transfers->num_rows(),
			"recordsFiltered" => $transfers->num_rows(),
			"data" => $data
		);
		$this->output->set_output(json_encode($output));
	}


	public function promotion()
	{
		//set data
		$data['title'] = $this->Core_model->site_title();
		$session = $this->session->userdata('username');
		if (!empty($session)) {
			$this->load->view("admin/employees_active/employee_lihat", $data);
		} else {
			redirect('admin/');
		}
		// Datatables Variables
		$draw = intval($this->input->get("draw"));
		$start = intval($this->input->get("start"));
		$length = intval($this->input->get("length"));

		$id = $this->uri->segment(4);
		$promotion = $this->Promotion_model->get_employee_promotions($id);

		// echo "<pre>";
		// print_r($this->db->last_query());
		// echo "</pre>";
		// die();

		$data = array();

		foreach ($promotion->result() as $r) {



			// get company
			$company = $this->Core_model->read_company_info($r->company_id);
			if (!is_null($company)) {
				$comp_name = $company[0]->name;
			} else {
				$comp_name = '--';
			}
			// get designation
			$designation = $this->Designation_model->read_designation_information($r->designation_id);
			if (!is_null($designation)) {
				$designation_name = $designation[0]->designation_name;
			} else {
				$designation_name = '--';
			}

			$designation_name_old = $r->designation_id_old;

			$promotion_date = $this->Core_model->set_date_format($r->promotion_date);

			$promoted_job = $designation_name . ' <br> <small class="text-muted"><i>Dengan Posisi Sebelumnya Sebagai : <br>' . $designation_name_old . '</i></small>';

			if ($r->wages_type == '1') {

				$promoted_to = $this->lang->line('xin_promotion_title_by') . ' : Gaji Bulanan <br><small class="text-muted"><i>' . $this->lang->line('xin_description') . ': <br>' . $r->description . '<i></i></i></small>';
			} else if ($r->wages_type == '2') {

				$promoted_to = $this->lang->line('xin_promotion_title_by') . ' : Gaji Harian <br><small class="text-muted"><i>' . $this->lang->line('xin_description') . ': <br>' . $r->description . '<i></i></i></small>';
			} else if ($r->wages_type == '3') {

				$promoted_to = $this->lang->line('xin_promotion_title_by') . ' : Gaji Borongan <br><small class="text-muted"><i>' . $this->lang->line('xin_description') . ': <br>' . $r->description . '<i></i></i></small>';
			}

			$view = '<span data-toggle="tooltip" data-placement="top" title="' . $this->lang->line('xin_view') . '"><button type="button" class="btn icon-btn btn-xs btn-default waves-effect waves-light" data-toggle="modal" data-target=".view-modal-data" data-xfield_id="' . $r->promotion_id . '" data-field_type="promotion"><span class="fa fa-eye"></span> Lihat </button></span>';


			$combhr = $view;

			$data[] = array(
				$combhr,
				$promotion_date,
				$promoted_job,
				$promoted_to,
				$r->description,
			);
		}

		$output = array(
			"draw" => $draw,
			"recordsTotal" => $promotion->num_rows(),
			"recordsFiltered" => $promotion->num_rows(),
			"data" => $data
		);
		$this->output->set_output(json_encode($output));
	}

	// ====================================================================================
	// KONTRAK
	// ==================================================================================== 
	public function kontrak()
	{

		$session = $this->session->userdata('username');

		if (empty($session)) {
			redirect('admin/');
		}

		$id = $this->uri->segment(4);

		$result = $this->Employees_model->read_employee_information($id);

		if (is_null($result)) {
			redirect('admin/employees');
		}

		$role_resources_ids = $this->Core_model->user_role_resource();

		$check_role = $this->Employees_model->read_employee_information($session['user_id']);

		if (!in_array('0511', $role_resources_ids)) {
			redirect('admin/employees');
		}


		$data = array(

			'breadcrumbs'          => 'Perpanjang Kontrak',
			'title'                => 'Perpanjang Kontrak | ' . $this->Core_model->site_title(),
			'path_url'             => 'employees_detail_contract',
			'first_name'           => $result[0]->first_name,
			'last_name'            => $result[0]->last_name,
			'user_id'              => $result[0]->user_id,
			'all_departments'      => $this->Department_model->all_departments(),
			'all_designations'     => $this->Designation_model->all_designations(),
			'all_contract_types'   => $this->Employees_model->all_contract_types(),
			'all_contract_durasi'  => $this->Employees_model->all_contract_durasi(),
			'all_contracts'        => $this->Employees_model->all_contracts(),
			'all_companies'        => $this->Company_model->get_company(),
			'get_all_companies'    => $this->Company_model->get_company(),
			'all_office_locations' => $this->Location_model->all_office_locations()

		);

		$data['subview'] = $this->load->view("admin/employees_active/employee_kontrak", $data, TRUE);
		$this->load->view('admin/layout/layout_main', $data); //page load

		// Datatables Variables
		$draw   = intval($this->input->get("draw"));
		$start  = intval($this->input->get("start"));
		$length = intval($this->input->get("length"));
	}

	// employee contract - listing
	// public function contract() 
	// {
	// 		//set data
	// 		$data['title'] = $this->Core_model->site_title();
	// 		$session = $this->session->userdata('username');
	// 		if(!empty($session)){ 
	// 			$this->load->view("admin/employees_active/employee_detail", $data);
	// 		} else {
	// 			redirect('admin/');
	// 		}
	// 		// Datatables Variables
	// 		$draw = intval($this->input->get("draw"));
	// 		$start = intval($this->input->get("start"));
	// 		$length = intval($this->input->get("length"));

	// 		$id = $this->uri->segment(4);
	// 		$contract = $this->Employees_model->set_employee_contract($id);

	// 		$data = array();

	//         foreach($contract->result() as $r) {			
	// 			// designation
	// 			$designation = $this->Designation_model->read_designation_information($r->designation_id);
	// 			if(!is_null($designation)){
	// 				$designation_name = $designation[0]->designation_name;
	// 			} else {
	// 				$designation_name = '?';
	// 			}
	// 			//company name
	// 			$company_name = $this->Core_model->read_company_info($r->company_id);
	// 			if(!is_null($company_name)){
	// 				$company_nm = $company_name[0]->name;
	// 			} else {
	// 				$company_nm = '?';
	// 			}
	// 			//contract type
	// 			$contract_type = $this->Employees_model->read_contract_type_information($r->contract_type_id);
	// 			if(!is_null($contract_type)){
	// 				$ctype = $contract_type[0]->name;
	// 			} else {
	// 				$ctype = '?';
	// 			}

	// 			//contract durasi
	// 			$contract_durasi = $this->Employees_model->read_contract_durasi_information($r->contract_durasi_id);
	// 			if(!is_null($contract_durasi)){
	// 				$cdurasi = $contract_durasi[0]->name;
	// 			} else {
	// 				$cdurasi = '?';
	// 			}
	// 			// date
	// 			$duration = $this->Core_model->set_date_format($r->from_date).' '.$this->lang->line('dashboard_to').' '.$this->Core_model->set_date_format($r->to_date).' ('.$r->durasi.')';

	// 		$data[] = array(
	// 			'<span data-toggle="tooltip" data-placement="top" title="'.$this->lang->line('xin_edit').'"><button type="button" class="btn btn-secondary btn-sm m-b-0-0 waves-effect waves-light" data-toggle="modal" data-target=".edit-modal-data" data-field_id="'. $r->contract_id . '" data-field_type="contract"><i class="fa fa-pencil-square-o"></i></button></span><span data-toggle="tooltip" data-placement="top" title="'.$this->lang->line('xin_delete').'"><button type="button" class="btn btn-danger btn-sm m-b-0-0 waves-effect waves-light delete" data-toggle="modal" data-target=".delete-modal" data-record-id="'. $r->contract_id . '" data-token_type="contract"><i class="fa fa-trash-o"></i></button></span>',
	// 			$duration,
	// 			$cdurasi,
	// 			$designation_name,
	// 			$ctype,
	// 			$r->title
	// 		);
	//       }

	// 	  $output = array(
	// 		   "draw" => $draw,
	// 			 "recordsTotal" => $contract->num_rows(),
	// 			 "recordsFiltered" => $contract->num_rows(),
	// 			 "data" => $data
	// 		);
	// 	  echo json_encode($output);
	// 	  exit();
	//   	}
	public function contract_list()
	{
		//set data
		$data['title'] = $this->Core_model->site_title();
		$session = $this->session->userdata('username');
		if (!empty($session)) {
			$this->load->view("admin/employees_active/employee_detail", $data);
		} else {
			redirect('admin/');
		}
		// Datatables Variables
		$draw = intval($this->input->get("draw"));
		$start = intval($this->input->get("start"));
		$length = intval($this->input->get("length"));

		$id = $this->uri->segment(4);
		$contract = $this->Employees_model->set_employee_contract($id);

		$data = array();

		foreach ($contract->result() as $r) {
			// designation
			$designation = $this->Designation_model->read_designation_information($r->designation_id);
			if (!is_null($designation)) {
				$designation_name = $designation[0]->designation_name;
			} else {
				$designation_name = '?';
			}
			//company name
			$company_name = $this->Core_model->read_company_info($r->company_id);
			if (!is_null($company_name)) {
				$company_nm = $company_name[0]->name;
			} else {
				$company_nm = '?';
			}
			//contract type
			$contract_type = $this->Employees_model->read_contract_type_information($r->contract_type_id);
			if (!is_null($contract_type)) {
				$ctype = $contract_type[0]->name;
			} else {
				$ctype = '?';
			}

			//contract durasi
			$contract_durasi = $this->Employees_model->read_contract_durasi_information($r->contract_durasi_id);
			if (!is_null($contract_durasi)) {
				$cdurasi = $contract_durasi[0]->name;
			} else {
				$cdurasi = '?';
			}
			// date
			$duration = $this->Core_model->set_date_format($r->from_date) . ' ' . $this->lang->line('dashboard_to') . ' ' . $this->Core_model->set_date_format($r->to_date);

			date_default_timezone_set("Asia/Jakarta");
			$now_date  = date("Y-m-d");

			if ($r->from_date < $now_date) {
				$status = 'Berakhir';
			} else {
				$status = 'Berlangsung';
			}

			$data[] = array(
				'<span data-toggle="tooltip" data-placement="top" title="' . $this->lang->line('xin_edit') . '"><button type="button" class="btn btn-secondary btn-sm m-b-0-0 waves-effect waves-light" data-toggle="modal" data-target=".edit-modal-data" data-field_id="' . $r->contract_id . '" data-field_type="contract"><i class="fa fa-pencil-square-o"></i></button></span><span data-toggle="tooltip" data-placement="top" title="' . $this->lang->line('xin_delete') . '"><button type="button" class="btn btn-danger btn-sm m-b-0-0 waves-effect waves-light delete" data-toggle="modal" data-target=".delete-modal" data-record-id="' . $r->contract_id . '" data-token_type="contract"><i class="fa fa-trash-o"></i></button></span>',
				$r->title,
				$duration,
				$cdurasi,
				$company_nm,
				$designation_name,
				$ctype,
				$status

			);
		}

		$output = array(
			"draw" => $draw,
			"recordsTotal" => $contract->num_rows(),
			"recordsFiltered" => $contract->num_rows(),
			"data" => $data
		);
		echo json_encode($output);
		exit();
	}
	// Validate and add info in database //contract info
	public function contract_info()
	{

		if ($this->input->post('type') == 'contract_info') {

			/* Define return | here result is used to return user data and error for error message */
			$Return = array('result' => '', 'error' => '', 'csrf_hash' => '');
			$Return['csrf_hash'] = $this->security->get_csrf_hash();

			$frm_date = strtotime($this->input->post('from_date'));
			$to_date = strtotime($this->input->post('to_date'));
			/* Server side PHP input validation */

			if ($this->input->post('company_id') === '') {
				$Return['error'] = $this->lang->line('xin_employee_error_contract_company');
			} else if ($this->input->post('contract_type_id') === '') {
				$Return['error'] = $this->lang->line('xin_employee_error_contract_type');
			} else if ($this->input->post('contract_durasi_id') === '') {
				$Return['error'] = $this->lang->line('xin_employee_error_contract_durasi');
			} else if ($this->input->post('title') === '') {
				$Return['error'] = $this->lang->line('xin_employee_error_contract_title');
			} else if ($this->input->post('from_date') === '') {
				$Return['error'] = $this->lang->line('xin_employee_error_frm_date');
			} else if ($frm_date > $to_date) {
				$Return['error'] = $this->lang->line('xin_employee_error_frm_to_date');
			} else if ($this->input->post('designation_id') === '') {
				$Return['error'] = $this->lang->line('xin_employee_error_designation');
			}

			if ($this->input->post('contract_type_id') === '1') {
				if ($this->input->post('to_date') === '') {
					$Return['error'] = $this->lang->line('xin_employee_error_to_date');
				}
			}

			if ($Return['error'] != '') {
				$this->output($Return);
			}


			$data = array(
				'company_id'         => $this->input->post('company_id'),
				'contract_type_id'   => $this->input->post('contract_type_id'),
				'contract_durasi_id' => $this->input->post('contract_durasi_id'),
				'title'              => $this->input->post('title'),
				'from_date'          => $this->input->post('from_date'),
				'to_date'            => $this->input->post('to_date'),
				'designation_id'     => $this->input->post('designation_id'),
				'description'        => $this->input->post('description'),
				'employee_id'        => $this->input->post('user_id'),
				'created_at'         => date('d-m-Y')
			);
			$result = $this->Employees_model->contract_info_add($data);
			if ($result == TRUE) {
				$Return['result'] = $this->lang->line('xin_employee_contract_info_added');
			} else {
				$Return['error']  = $this->lang->line('xin_error_msg');
			}

			$this->output($Return);
			exit;
		}
	}
	// Validate and add info in database //e contract info
	public function e_contract_info()
	{

		if ($this->input->post('type') == 'e_contract_info') {
			/* Define return | here result is used to return user data and error for error message */
			$Return = array('result' => '', 'error' => '', 'csrf_hash' => '');
			$Return['csrf_hash'] = $this->security->get_csrf_hash();

			$frm_date = strtotime($this->input->post('from_date'));
			$to_date = strtotime($this->input->post('to_date'));

			/* Server side PHP input validation */
			if ($this->input->post('company_id') === '') {
				$Return['error'] = $this->lang->line('xin_employee_error_contract_company');
			} else if ($this->input->post('contract_type_id') === '') {
				$Return['error'] = $this->lang->line('xin_employee_error_contract_type');
			} else if ($this->input->post('contract_durasi_id') === '') {
				$Return['error'] = $this->lang->line('xin_employee_error_contract_durasi');
			} else if ($this->input->post('title') === '') {
				$Return['error'] = $this->lang->line('xin_employee_error_contract_title');
			} else if ($this->input->post('from_date') === '') {
				$Return['error'] = $this->lang->line('xin_employee_error_frm_date');
			} else if ($frm_date > $to_date) {
				$Return['error'] = $this->lang->line('xin_employee_error_frm_to_date');
			} else if ($this->input->post('designation_id') === '') {
				$Return['error'] = $this->lang->line('xin_employee_error_designation');
			}

			if ($this->input->post('contract_type_id') === '1') {
				if ($this->input->post('to_date') === '') {
					$Return['error'] = $this->lang->line('xin_employee_error_to_date');
				}
			}

			if ($Return['error'] != '') {
				$this->output($Return);
			}

			// $datetime1 = new DateTime($this->input->post('frm_date'));
			// $datetime2 = new DateTime($this->input->post('to_date'));
			// $interval = $datetime1->diff($datetime2);

			$data = array(
				'company_id' => $this->input->post('company_id'),
				'contract_type_id' => $this->input->post('contract_type_id'),
				'contract_durasi_id' => $this->input->post('contract_durasi_id'),
				'title' => $this->input->post('title'),
				'from_date' => $this->input->post('from_date'),
				'to_date' => $this->input->post('to_date'),
				// 'durasi' => $interval,
				'designation_id' => $this->input->post('designation_id'),
				'description' => $this->input->post('description')
			);
			$e_field_id = $this->input->post('e_field_id');
			$result = $this->Employees_model->contract_info_update($data, $e_field_id);
			if ($result == TRUE) {
				$Return['result'] = $this->lang->line('xin_employee_contract_info_updated');
			} else {
				$Return['error'] = $this->lang->line('xin_error_msg');
			}
			$this->output($Return);
			exit;
		}
	}
	// delete contract record
	public function delete_contract()
	{

		if ($this->input->post('data') == 'delete_record') {
			/* Define return | here result is used to return user data and error for error message */
			$Return = array('result' => '', 'error' => '', 'csrf_hash' => '');
			$Return['csrf_hash'] = $this->security->get_csrf_hash();
			$id = $this->uri->segment(4);
			$result = $this->Employees_model->delete_contract_record($id);
			if (isset($id)) {
				$Return['result'] = $this->lang->line('xin_employee_contract_deleted');
			} else {
				$Return['error'] = $this->lang->line('xin_error_msg');
			}
			$this->output($Return);
		}
	}
	public function dialog_contract()
	{

		$session = $this->session->userdata('username');
		if (empty($session)) {
			redirect('admin/');
		}
		$data['title'] = $this->Core_model->site_title();
		$id = $this->input->get('field_id');
		$result = $this->Employees_model->read_contract_information($id);
		$data = array(
			'contract_id' => $result[0]->contract_id,
			'employee_id' => $result[0]->employee_id,
			'company_id' => $result[0]->company_id,
			'contract_type_id' => $result[0]->contract_type_id,
			'contract_durasi_id' => $result[0]->contract_durasi_id,
			'from_date' => $result[0]->from_date,
			'designation_id' => $result[0]->designation_id,
			'title' => $result[0]->title,
			'to_date' => $result[0]->to_date,
			'description' => $result[0]->description,
			'all_contract_types' => $this->Employees_model->all_contract_types(),
			'all_contract_durasi' => $this->Employees_model->all_contract_durasi(),
			'all_companies' => $this->Company_model->get_company(),
			'all_designations' => $this->Designation_model->all_designations(),
		);
		if (!empty($session)) {
			$this->load->view('admin/employees_active/dialog_employee_details', $data);
		} else {
			redirect('admin/');
		}
	}
	// ====================================================================================
	// CUTI
	// ==================================================================================== 
	// employee leave - listing
	public function leave()
	{
		//set data
		$data['title'] = $this->Core_model->site_title();
		$session = $this->session->userdata('username');
		if (!empty($session)) {
			$this->load->view("admin/employees_active/employee_detail", $data);
		} else {
			redirect('admin/');
		}
		// Datatables Variables
		$draw = intval($this->input->get("draw"));
		$start = intval($this->input->get("start"));
		$length = intval($this->input->get("length"));

		$id = $this->uri->segment(4);
		$leave = $this->Employees_model->set_employee_leave($id);

		$data = array();

		foreach ($leave->result() as $r) {



			// contract
			$contract = $this->Employees_model->read_contract_information($r->contract_id);
			if (!is_null($contract)) {
				// contract duration
				$duration = $this->Core_model->set_date_format($contract[0]->from_date) . ' ' . $this->lang->line('dashboard_to') . ' ' . $this->Core_model->set_date_format($contract[0]->to_date);
				$ctitle = $contract[0]->title . ' ' . $duration;
			} else {
				$ctitle = '?';
			}

			$contracti = $ctitle;

			$data[] = array(
				'<span data-toggle="tooltip" data-placement="top" title="' . $this->lang->line('xin_edit') . '"><button type="button" class="btn btn-secondary btn-sm m-b-0-0 waves-effect waves-light" data-toggle="modal" data-target=".edit-modal-data" data-field_id="' . $r->leave_id . '" data-field_type="leave"><i class="fa fa-pencil-square-o"></i></button></span><span data-toggle="tooltip" data-placement="top" title="' . $this->lang->line('xin_delete') . '"><button type="button" class="btn btn-danger btn-sm m-b-0-0 waves-effect waves-light delete" data-toggle="modal" data-target=".delete-modal" data-record-id="' . $r->leave_id . '" data-token_type="leave"><i class="fa fa-trash-o"></i></button></span>',
				$contracti,
				$r->casual_leave,
				$r->medical_leave
			);
		}

		$output = array(
			"draw" => $draw,
			"recordsTotal" => $leave->num_rows(),
			"recordsFiltered" => $leave->num_rows(),
			"data" => $data
		);
		$this->output->set_output(json_encode($output));
	}

	// employee office shift - listing
	public function shift()
	{
		//set data
		$data['title'] = $this->Core_model->site_title();
		$session = $this->session->userdata('username');
		if (!empty($session)) {
			$this->load->view("admin/employees_active/employee_detail", $data);
		} else {
			redirect('admin/');
		}
		// Datatables Variables
		$draw = intval($this->input->get("draw"));
		$start = intval($this->input->get("start"));
		$length = intval($this->input->get("length"));

		$id = $this->uri->segment(4);
		$shift = $this->Employees_model->set_employee_shift($id);

		$data = array();

		foreach ($shift->result() as $r) {
			// contract
			$shift_info = $this->Employees_model->read_shift_information($r->shift_id);
			// contract duration
			$duration = $this->Core_model->set_date_format($r->from_date) . ' ' . $this->lang->line('dashboard_to') . ' ' . $this->Core_model->set_date_format($r->to_date);

			if (!is_null($shift_info)) {
				$shift_name = $shift_info[0]->shift_name;
			} else {
				$shift_name = '?';
			}

			$data[] = array(
				'<span data-toggle="tooltip" data-placement="top" title="' . $this->lang->line('xin_edit') . '"><button type="button" class="btn btn-secondary btn-sm m-b-0-0 waves-effect waves-light" data-toggle="modal" data-target=".edit-modal-data" data-field_id="' . $r->emp_shift_id . '" data-field_type="shift"><i class="fa fa-pencil-square-o"></i></button></span><span data-toggle="tooltip" data-placement="top" title="' . $this->lang->line('xin_delete') . '"><button type="button" class="btn btn-danger btn-sm m-b-0-0 waves-effect waves-light delete" data-toggle="modal" data-target=".delete-modal" data-record-id="' . $r->emp_shift_id . '" data-token_type="shift"><i class="fa fa-trash-o"></i></button></span>',
				$duration,
				$shift_name
			);
		}

		$output = array(
			"draw" => $draw,
			"recordsTotal" => $shift->num_rows(),
			"recordsFiltered" => $shift->num_rows(),
			"data" => $data
		);
		$this->output->set_output(json_encode($output));
	}

	// employee location - listing
	public function location()
	{
		//set data
		$data['title'] = $this->Core_model->site_title();
		$session = $this->session->userdata('username');
		if (!empty($session)) {
			$this->load->view("admin/employees_active/employee_detail", $data);
		} else {
			redirect('admin/');
		}
		// Datatables Variables
		$draw = intval($this->input->get("draw"));
		$start = intval($this->input->get("start"));
		$length = intval($this->input->get("length"));

		$id = $this->uri->segment(4);
		$location = $this->Employees_model->set_employee_location($id);

		$data = array();

		foreach ($location->result() as $r) {
			// contract
			$of_location = $this->Location_model->read_location_information($r->location_id);
			// contract duration
			$duration = $this->Core_model->set_date_format($r->from_date) . ' ' . $this->lang->line('dashboard_to') . ' ' . $this->Core_model->set_date_format($r->to_date);
			if (!is_null($of_location)) {
				$location_name = $of_location[0]->location_name;
			} else {
				$location_name = '?';
			}

			$data[] = array(
				'<span data-toggle="tooltip" data-placement="top" title="' . $this->lang->line('xin_edit') . '"><button type="button" class="btn btn-secondary btn-sm m-b-0-0 waves-effect waves-light" data-toggle="modal" data-target=".edit-modal-data" data-field_id="' . $r->office_location_id . '" data-field_type="location"><i class="fa fa-pencil-square-o"></i></button></span><span data-toggle="tooltip" data-placement="top" title="' . $this->lang->line('xin_delete') . '"><button type="button" class="btn btn-danger btn-sm m-b-0-0 waves-effect waves-light delete" data-toggle="modal" data-target=".delete-modal" data-record-id="' . $r->office_location_id . '" data-token_type="location"><i class="fa fa-trash-o"></i></button></span>',
				$duration,
				$location_name
			);
		}

		$output = array(
			"draw" => $draw,
			"recordsTotal" => $location->num_rows(),
			"recordsFiltered" => $location->num_rows(),
			"data" => $data
		);
		$this->output->set_output(json_encode($output));
	}

	// Validate and update info in database
	public function update()
	{

		if ($this->input->post('edit_type') == 'warning') {

			$id = $this->uri->segment(4);

			/* Define return | here result is used to return user data and error for error message */
			$Return = array('result' => '', 'error' => '', 'csrf_hash' => '');
			$Return['csrf_hash'] = $this->security->get_csrf_hash();

			/* Server side PHP input validation */
			$description = $this->input->post('description');
			$qt_description = htmlspecialchars(addslashes($description), ENT_QUOTES);

			if ($this->input->post('warning_to') === '') {
				$Return['error'] = $this->lang->line('xin_employee_error_warning');
			} else if ($this->input->post('type') === '') {
				$Return['error'] = $this->lang->line('xin_employee_error_warning_type');
			} else if ($this->input->post('subject') === '') {
				$Return['error'] = $this->lang->line('xin_employee_error_subject');
			} else if ($this->input->post('warning_by') === '') {
				$Return['error'] = $this->lang->line('xin_employee_error_warning_by');
			} else if ($this->input->post('warning_date') === '') {
				$Return['error'] = $this->lang->line('xin_employee_error_warning_date');
			}

			if ($Return['error'] != '') {
				$this->output($Return);
			}

			$data = array(
				'warning_to' => $this->input->post('warning_to'),
				'warning_type_id' => $this->input->post('type'),
				'description' => $qt_description,
				'subject' => $this->input->post('subject'),
				'warning_by' => $this->input->post('warning_by'),
				'warning_date' => $this->input->post('warning_date'),
				'status' => $this->input->post('status'),
			);

			$result = $this->Warning_model->update_record($data, $id);

			if ($result == TRUE) {
				$Return['result'] = $this->lang->line('xin_employee_warning_updated');
			} else {
				$Return['error'] = $this->lang->line('xin_error_msg');
			}
			$this->output($Return);
			exit;
		}
	}

	// import > employees
	public function import()
	{
		$session = $this->session->userdata('username');
		if (empty($session)) {
			redirect('admin/');
		}
		$data['title'] = $this->lang->line('xin_import_employees') . ' | ' . $this->Core_model->site_title();
		$data['breadcrumbs'] = $this->lang->line('xin_import_employees');
		$data['path_url'] = 'import_employees';
		$data['all_employees'] = $this->Core_model->all_employees();
		$data['all_departments'] = $this->Department_model->all_departments();
		$data['all_designations'] = $this->Designation_model->all_designations();
		$data['all_user_roles'] = $this->Roles_model->all_user_roles();
		$data['all_office_shifts'] = $this->Employees_model->all_office_shifts();
		$data['get_all_companies'] = $this->Company_model->get_company();
		$role_resources_ids = $this->Core_model->user_role_resource();
		if (in_array('92', $role_resources_ids)) {
			if (!empty($session)) {
				$data['subview'] = $this->load->view("admin/employees_active/employes_import", $data, TRUE);
				$this->load->view('admin/layout/layout_main', $data); //page load
			} else {
				redirect('admin/');
			}
		} else {
			redirect('admin/dashboard');
		}
	}



	// delete contact record
	public function delete_contact()
	{

		if ($this->input->post('data') == 'delete_record') {
			/* Define return | here result is used to return user data and error for error message */
			$Return = array('result' => '', 'error' => '', 'csrf_hash' => '');
			$id = $this->uri->segment(4);
			$result = $this->Employees_model->delete_contact_record($id);
			$Return['csrf_hash'] = $this->security->get_csrf_hash();
			if (isset($id)) {
				$Return['result'] = $this->lang->line('xin_employee_contact_deleted');
			} else {
				$Return['error'] = $this->lang->line('xin_error_msg');
			}
			$this->output($Return);
		}
	}

	// delete document record
	public function delete_document()
	{

		if ($this->input->post('data') == 'delete_record') {
			/* Define return | here result is used to return user data and error for error message */
			$Return = array('result' => '', 'error' => '', 'csrf_hash' => '');
			$id = $this->uri->segment(4);
			$Return['csrf_hash'] = $this->security->get_csrf_hash();
			$result = $this->Employees_model->delete_document_record($id);
			if (isset($id)) {
				$Return['result'] = $this->lang->line('xin_employee_document_deleted');
			} else {
				$Return['error'] = $this->lang->line('xin_error_msg');
			}
			$this->output($Return);
		}
	}

	// delete document record
	public function delete_imgdocument()
	{

		if ($this->input->post('data') == 'delete_record') {
			/* Define return | here result is used to return user data and error for error message */
			$Return = array('result' => '', 'error' => '', 'csrf_hash' => '');

			$id = $this->uri->segment(4);
			$result = $this->Employees_model->delete_imgdocument_record($id);
			$Return['csrf_hash'] = $this->security->get_csrf_hash();
			if (isset($id)) {
				$Return['result'] = $this->lang->line('xin_employee_img_document_deleted');
			} else {
				$Return['error'] = $this->lang->line('xin_error_msg');
			}
			$this->output($Return);
		}
	}

	// delete qualification record
	public function delete_qualification()
	{

		if ($this->input->post('data') == 'delete_record') {
			/* Define return | here result is used to return user data and error for error message */
			$Return = array('result' => '', 'error' => '', 'csrf_hash' => '');
			$Return['csrf_hash'] = $this->security->get_csrf_hash();
			$id = $this->uri->segment(4);
			$result = $this->Employees_model->delete_qualification_record($id);
			if (isset($id)) {
				$Return['result'] = $this->lang->line('xin_employee_qualification_deleted');
			} else {
				$Return['error'] = $this->lang->line('xin_error_msg');
			}
			$this->output($Return);
		}
	}

	// delete work_experience record
	public function delete_work_experience()
	{

		if ($this->input->post('data') == 'delete_record') {
			/* Define return | here result is used to return user data and error for error message */
			$Return = array('result' => '', 'error' => '', 'csrf_hash' => '');
			$Return['csrf_hash'] = $this->security->get_csrf_hash();
			$id = $this->uri->segment(4);
			$result = $this->Employees_model->delete_work_experience_record($id);
			if (isset($id)) {
				$Return['result'] = $this->lang->line('xin_employee_work_deleted');
			} else {
				$Return['error'] = $this->lang->line('xin_error_msg');
			}
			$this->output($Return);
		}
	}

	// delete bank_account record




	// delete leave record
	public function delete_leave()
	{

		if ($this->input->post('data') == 'delete_record') {
			/* Define return | here result is used to return user data and error for error message */
			$Return = array('result' => '', 'error' => '', 'csrf_hash' => '');
			$Return['csrf_hash'] = $this->security->get_csrf_hash();
			$id = $this->uri->segment(4);
			$result = $this->Employees_model->delete_leave_record($id);
			if (isset($id)) {
				$Return['result'] = $this->lang->line('xin_employee_leave_deleted');
			} else {
				$Return['error'] = $this->lang->line('xin_error_msg');
			}
			$this->output($Return);
		}
	}

	// delete shift record
	public function delete_shift()
	{

		if ($this->input->post('data') == 'delete_record') {
			/* Define return | here result is used to return user data and error for error message */
			$Return = array('result' => '', 'error' => '', 'csrf_hash' => '');
			$Return['csrf_hash'] = $this->security->get_csrf_hash();
			$id = $this->uri->segment(4);
			$result = $this->Employees_model->delete_shift_record($id);
			if (isset($id)) {
				$Return['result'] = $this->lang->line('xin_employee_shift_deleted');
			} else {
				$Return['error'] = $this->lang->line('xin_error_msg');
			}
			$this->output($Return);
		}
	}

	// delete location record
	public function delete_location()
	{

		if ($this->input->post('data') == 'delete_record') {
			/* Define return | here result is used to return user data and error for error message */
			$Return = array('result' => '', 'error' => '', 'csrf_hash' => '');
			$Return['csrf_hash'] = $this->security->get_csrf_hash();
			$id = $this->uri->segment(4);
			$result = $this->Employees_model->delete_location_record($id);
			if (isset($id)) {
				$Return['result'] = $this->lang->line('xin_employee_location_deleted');
			} else {
				$Return['error'] = $this->lang->line('xin_error_msg');
			}
			$this->output($Return);
		}
	}

	// delete employee record
	public function delete()
	{

		if ($this->input->post('is_ajax') == '2') {
			/* Define return | here result is used to return user data and error for error message */
			$Return = array('result' => '', 'error' => '', 'csrf_hash' => '');
			$Return['csrf_hash'] = $this->security->get_csrf_hash();
			$id = $this->uri->segment(4);
			$result = $this->Employees_model->delete_record($id);
			if (isset($id)) {
				$Return['result'] = $this->lang->line('xin_employee_current_deleted');
			} else {
				$Return['error'] = $this->lang->line('xin_error_msg');
			}
			$this->output($Return);
		}
	}

	// Validate and update info in database // basic info



	// Validate and update info in database // basic info
	public function set_overtime()
	{

		if ($this->input->post('type') == 'emp_overtime') {
			/* Define return | here result is used to return user data and error for error message */
			$Return = array('result' => '', 'error' => '', 'csrf_hash' => '');
			$Return['csrf_hash'] = $this->security->get_csrf_hash();
			if ($this->input->post('overtime_type') === '') {
				$Return['error'] = $this->lang->line('xin_employee_set_overtime_title_error');
			} else if ($this->input->post('no_of_days') === '') {
				$Return['error'] = $this->lang->line('xin_employee_set_overtime_no_of_days_error');
			} else if ($this->input->post('overtime_hours') === '') {
				$Return['error'] = $this->lang->line('xin_employee_set_overtime_hours_error');
			} else if ($this->input->post('overtime_rate') === '') {
				$Return['error'] = $this->lang->line('xin_employee_set_overtime_rate_error');
			}

			if ($Return['error'] != '') {
				$this->output($Return);
			}
			$data = array(
				'employee_id' => $this->input->post('user_id'),
				'overtime_type' => $this->input->post('overtime_type'),
				'no_of_days' => $this->input->post('no_of_days'),
				'overtime_hours' => $this->input->post('overtime_hours'),
				'overtime_rate' => $this->input->post('overtime_rate')
			);
			$id = $this->input->post('user_id');
			$result = $this->Employees_model->add_salary_overtime($data);
			if ($result == TRUE) {
				$Return['result'] = $this->lang->line('xin_employee_added_overtime_success');
			} else {
				$Return['error'] = $this->lang->line('xin_error_msg');
			}
			$this->output($Return);
			exit;
		}
	}

	// Validate and update info in database // basic info
	public function update_overtime_info()
	{

		if ($this->input->post('type') == 'e_overtime_info') {
			/* Define return | here result is used to return user data and error for error message */
			$Return = array('result' => '', 'error' => '', 'csrf_hash' => '');
			$Return['csrf_hash'] = $this->security->get_csrf_hash();
			if ($this->input->post('overtime_type') === '') {
				$Return['error'] = $this->lang->line('xin_employee_set_overtime_title_error');
			} else if ($this->input->post('no_of_days') === '') {
				$Return['error'] = $this->lang->line('xin_employee_set_overtime_no_of_days_error');
			} else if ($this->input->post('overtime_hours') === '') {
				$Return['error'] = $this->lang->line('xin_employee_set_overtime_hours_error');
			} else if ($this->input->post('overtime_rate') === '') {
				$Return['error'] = $this->lang->line('xin_employee_set_overtime_rate_error');
			}

			if ($Return['error'] != '') {
				$this->output($Return);
			}
			$id = $this->input->post('e_field_id');
			$data = array(
				'overtime_type' => $this->input->post('overtime_type'),
				'no_of_days' => $this->input->post('no_of_days'),
				'overtime_hours' => $this->input->post('overtime_hours'),
				'overtime_rate' => $this->input->post('overtime_rate')
			);
			//$id = $this->input->post('user_id');
			$result = $this->Employees_model->salary_overtime_update_record($data, $id);
			if ($result == TRUE) {
				$Return['result'] = $this->lang->line('xin_employee_updated_overtime_success');
			} else {
				$Return['error'] = $this->lang->line('xin_error_msg');
			}
			$this->output($Return);
			exit;
		}
	}

	// delete overtime record
	public function delete_emp_overtime()
	{

		if ($this->input->post('data') == 'delete_record') {
			/* Define return | here result is used to return user data and error for error message */
			$Return = array('result' => '', 'error' => '', 'csrf_hash' => '');
			$Return['csrf_hash'] = $this->security->get_csrf_hash();
			$id = $this->uri->segment(4);
			$result = $this->Employees_model->delete_overtime_record($id);
			if (isset($id)) {
				$Return['result'] = $this->lang->line('xin_employee_delete_overtime_success');
			} else {
				$Return['error'] = $this->lang->line('xin_error_msg');
			}
			$this->output($Return);
		}
	}


	// employee commissions

	// employee statutory_deductions

	// employee other payments


	// employee overtime
	public function salary_overtime()
	{
		//set data
		$data['title'] = $this->Core_model->site_title();
		$session = $this->session->userdata('username');
		if (!empty($session)) {
			$this->load->view("admin/employees_active/employee_detail", $data);
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


			$cek_ov_status    = $r->ov_status;
			if ($cek_ov_status == '') {
				$ov_status = '<span class="badge bg-red"><i class="fa fa-question"></i></span>';
			} else {
				if ($cek_ov_status == 'TS') {
					$ov_status = '<span class="blink blink-one">Lembur di Tanggal Sama </span>';
				} else {
					$ov_status = '<span class="blink blink-one">Lembur di Tanggal Berikutnya</span> ';
				}
			}
			// get start date
			$clock_in_m    = $r->clock_in_m;
			// get end date
			$clock_out_m   = $r->clock_out_m;

			$cek_lembur_1 = $clock_in_m . ' ' . $this->lang->line('dashboard_to') . ' ' . $clock_out_m;
			if ($cek_lembur_1 == '' || $cek_lembur_1 == '00:00:00 s/d 00:00:00') {
				$lembur_1 = '-- Tidak Ada --';
			} else {
				$lembur_1 = $cek_lembur_1;
			}

			// get start date
			$clock_in_n    = $r->clock_in_n;
			// get end date
			$clock_out_n   = $r->clock_out_n;


			$cek_lembur_2 = $clock_in_n . ' ' . $this->lang->line('dashboard_to') . ' ' . $clock_out_n;
			if ($cek_lembur_2 == '' || $cek_lembur_2 == '00:00:00 s/d 00:00:00') {
				$lembur_2 = '-- Tidak Ada --';
			} else {
				$lembur_2 = $cek_lembur_2;
			}

			// total work
			// $total_time    = $r->total_menit.' Menit';

			// $total_jam    = round($total_time/60,2).' Jam';

			// overtime date
			$overtime_time = 'LP ' . $lembur_1 . '<br>' .
				'LS ' . $lembur_2 . '<br>
			                 <small class="text-muted">
			                 			                 <i class="fa fa-check-circle"></i> ' . $ov_status . '			                 
			                 </small>';


			// overtime date
			// $overtime_time = $clock_in_m.' '.$this->lang->line('dashboard_to').' '.$clock_out_m;


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

				$manager_name = $reports_to[0]->first_name . ' ' . $reports_to[0]->last_name . ' <small>(' . $designation_name . ')</small>';
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
				$no,
				date("d-m-Y", strtotime($overtime_date)),
				$overtime_time,
				$manager_name . '<br>' . $iitype,

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
		$this->output->set_output(json_encode($output));
	}

	// employee salary_all_deductions




	// get company > locations
	public function filter_company_flocations()
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
				$this->load->view("admin/filter/filter_company_flocations", $data);
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
	public function filter_location_fdepartments()
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
				$this->load->view("admin/filter/filter_location_fdepartments", $data);
			} else {
				redirect('admin/');
			}
		}
		// Datatables Variables
		$draw = intval($this->input->get("draw"));
		$start = intval($this->input->get("start"));
		$length = intval($this->input->get("length"));
	}
	public function filter_location_fdesignation()
	{

		$data['title'] = $this->Core_model->site_title();
		$id = $this->uri->segment(4);

		$data = array(
			'department_id' => $id,
			'all_designations' => $this->Designation_model->all_designations(),
		);
		$session = $this->session->userdata('username');
		if (!empty($session)) {
			$this->load->view("admin/filter/filter_location_fdesignation", $data);
		} else {
			redirect('admin/');
		}
		// Datatables Variables
		$draw = intval($this->input->get("draw"));
		$start = intval($this->input->get("start"));
		$length = intval($this->input->get("length"));
	}

	public function expired_documents()
	{
		$session = $this->session->userdata('username');
		if (empty($session)) {
			redirect('admin/');
		}
		$data['title'] = $this->lang->line('xin_e_details_exp_documents') . ' | ' . $this->Core_model->site_title();
		$data['breadcrumbs'] = $this->lang->line('xin_e_details_exp_documents');
		$data['path_url'] = 'employees_expired_documents';
		$role_resources_ids = $this->Core_model->user_role_resource();
		if (in_array('400', $role_resources_ids)) {
			$data['subview'] = $this->load->view("admin/employees_active/expired_documents_list", $data, TRUE);
			$this->load->view('admin/layout/layout_main', $data); //page load
		} else {
			redirect('admin/dashboard');
		}
	}

	// employee documents - listing
	public function expired_documents_list()
	{
		//set data
		$data['title'] = $this->Core_model->site_title();
		$session = $this->session->userdata('username');
		if (!empty($session)) {
			$this->load->view("admin/employees_active/expired_documents_list", $data);
		} else {
			redirect('admin/');
		}
		// Datatables Variables
		$draw = intval($this->input->get("draw"));
		$start = intval($this->input->get("start"));
		$length = intval($this->input->get("length"));
		$user_info = $this->Core_model->read_user_info($session['user_id']);
		if ($user_info[0]->user_role_id == 1 || $user_info[0]->user_role_id == 4 || $user_info[0]->user_role_id == 5 || $user_info[0]->user_role_id == 6) {
			$documents = $this->Employees_model->get_documents_expired_all();
		} else {
			$documents = $this->Employees_model->get_user_documents_expired_all($session['user_id']);
		}


		$data = array();

		foreach ($documents->result() as $r) {

			$d_type = $this->Employees_model->read_document_type_information($r->document_type_id);
			if (!is_null($d_type)) {
				$document_d = $d_type[0]->document_type;
			} else {
				$document_d = '?';
			}
			$date_of_expiry = $this->Core_model->set_date_format($r->date_of_expiry);
			if ($r->document_file != '' && $r->document_file != 'no file') {
				$functions = '<span data-toggle="tooltip" data-placement="top" title="Download"><a href="' . site_url() . 'admin/download?type=document&filename=' . $r->document_file . '"><button type="button" class="btn btn-secondary btn-sm m-b-0-0 waves-effect waves-light" title="' . $this->lang->line('xin_download') . '"><i class="fa fa-download"></i></button></a></span>';
			} else {
				$functions = '';
			}
			//userinfo
			$xuser_info = $this->Core_model->read_user_info($r->employee_id);
			if (!is_null($xuser_info)) {
				if ($user_info[0]->user_role_id == 1 || $user_info[0]->user_role_id == 4 || $user_info[0]->user_role_id == 5 || $user_info[0]->user_role_id == 6) {
					$fc_name = '<a target="_blank" href="' . site_url('admin/employees_active/detail/') . $r->employee_id . '">' . $xuser_info[0]->first_name . ' ' . $xuser_info[0]->last_name . '</a>';
				} else {
					$fc_name = $xuser_info[0]->first_name . ' ' . $xuser_info[0]->last_name;
				}
			} else {
				$fc_name = '?';
			}
			$data[] = array(
				$functions . '<span data-toggle="tooltip" data-placement="top" title="' . $this->lang->line('xin_edit') . '"><button type="button" class="btn btn-secondary btn-sm m-b-0-0 waves-effect waves-light" data-toggle="modal" data-target=".edit-modal-data" data-field_id="' . $r->document_id . '" data-field_type="document"><i class="fa fa-pencil-square-o"></i></button></span>',
				$fc_name,
				$document_d,
				$r->title,
				$date_of_expiry
			);
		}

		$output = array(
			"draw" => $draw,
			"recordsTotal" => $documents->num_rows(),
			"recordsFiltered" => $documents->num_rows(),
			"data" => $data
		);
		$this->output->set_output(json_encode($output));
	}

	// employee immigration - listing
	public function expired_immigration_list()
	{
		//set data
		$data['title'] = $this->Core_model->site_title();
		$session = $this->session->userdata('username');
		if (!empty($session)) {
			$this->load->view("admin/employees_active/expired_documents_list", $data);
		} else {
			redirect('admin/');
		}
		// Datatables Variables
		$draw = intval($this->input->get("draw"));
		$start = intval($this->input->get("start"));
		$length = intval($this->input->get("length"));

		//	$id = $this->uri->segment(4);
		$user_info = $this->Core_model->read_user_info($session['user_id']);
		if ($user_info[0]->user_role_id == 1 || $user_info[0]->user_role_id == 4 || $user_info[0]->user_role_id == 5 || $user_info[0]->user_role_id == 6) {
			$immigration = $this->Employees_model->get_img_documents_expired_all();
		} else {
			$immigration = $this->Employees_model->get_user_img_documents_expired_all($session['user_id']);
		}


		$data = array();

		foreach ($immigration->result() as $r) {

			$issue_date = $this->Core_model->set_date_format($r->issue_date);
			$expiry_date = $this->Core_model->set_date_format($r->expiry_date);
			$eligible_review_date = $this->Core_model->set_date_format($r->eligible_review_date);
			$d_type = $this->Employees_model->read_document_type_information($r->document_type_id);
			if (!is_null($d_type)) {
				$document_d = $d_type[0]->document_type . '<br>' . $r->document_number;
			} else {
				$document_d = $r->document_number;
			}
			$country = $this->Core_model->read_country_info($r->country_id);
			if (!is_null($country)) {
				$c_name = $country[0]->country_name;
			} else {
				$c_name = '?';
			}
			//userinfo
			$xuser_info = $this->Core_model->read_user_info($r->employee_id);
			if (!is_null($xuser_info)) {
				if ($user_info[0]->user_role_id == 1 || $user_info[0]->user_role_id == 4 || $user_info[0]->user_role_id == 5 || $user_info[0]->user_role_id == 6) {
					$fc_name = '<a target="_blank" href="' . site_url('admin/employees_active/detail/') . $r->employee_id . '">' . $xuser_info[0]->first_name . ' ' . $xuser_info[0]->last_name . '</a>';
				} else {
					$fc_name = $xuser_info[0]->first_name . ' ' . $xuser_info[0]->last_name;
				}
			} else {
				$fc_name = '?';
			}
			if ($r->document_file != '' && $r->document_file != 'no file') {
				$functions = '<span data-toggle="tooltip" data-placement="top" title="Download"><a href="' . site_url() . 'admin/download?type=document/immigration&filename=' . $r->document_file . '"><button type="button" class="btn btn-secondary btn-sm m-b-0-0 waves-effect waves-light" title="' . $this->lang->line('xin_download') . '"><i class="fa fa-download"></i></button></a></span>';
			} else {
				$functions = '';
			}
			$data[] = array(
				$functions . '<span data-toggle="tooltip" data-placement="top" title="' . $this->lang->line('xin_edit') . '"><button type="button" class="btn btn-secondary btn-sm m-b-0-0 waves-effect waves-light" data-toggle="modal" data-target=".edit-modal-data" data-field_id="' . $r->immigration_id . '" data-field_type="imgdocument"><i class="fa fa-pencil-square-o"></i></button></span>',
				$fc_name,
				$document_d,
				$issue_date,
				$expiry_date,
				$c_name,
			);
		}

		$output = array(
			"draw" => $draw,
			"recordsTotal" => $immigration->num_rows(),
			"recordsFiltered" => $immigration->num_rows(),
			"data" => $data
		);
		$this->output->set_output(json_encode($output));
	}
	public function exp_company_license_list()
	{

		$data['title'] = $this->Core_model->site_title();
		$session = $this->session->userdata('username');
		if (!empty($session)) {
			$this->load->view("admin/employees_active/expired_documents_list", $data);
		} else {
			redirect('admin/');
		}
		// Datatables Variables
		$draw = intval($this->input->get("draw"));
		$start = intval($this->input->get("start"));
		$length = intval($this->input->get("length"));

		$role_resources_ids = $this->Core_model->user_role_resource();
		$user_info = $this->Core_model->read_user_info($session['user_id']);
		if ($user_info[0]->user_role_id == 1 || $user_info[0]->user_role_id == 4 || $user_info[0]->user_role_id == 5 || $user_info[0]->user_role_id == 6) {
			$company = $this->Employees_model->company_license_expired_all();
		} else {
			$company = $this->Employees_model->get_company_license_expired($user_info[0]->company_id);
		}
		$data = array();

		foreach ($company->result() as $r) {

			if (in_array('247', $role_resources_ids)) { //edit
				$edit = '<span data-toggle="tooltip" data-placement="top" title="' . $this->lang->line('xin_edit') . '"><button type="button" class="btn icon-btn btn-xs btn-default waves-effect waves-light"  data-toggle="modal" data-target=".edit-modal-data"  data-field_id="' . $r->document_id . '" data-field_type="company_license_expired"><i class="fa fa-pencil-square-o"></i></button></span>';
			} else {
				$edit = '';
			}
			$company_id = $this->Company_model->read_company_information($r->company_id);
			if (!is_null($company_id)) {
				$company_name = $company_id[0]->name;
			} else {
				$company_name = '?';
			}

			if ($r->document != '' && $r->document != 'no file') {
				$doc_view = '<span data-toggle="tooltip" data-placement="top" title="' . $this->lang->line('xin_download') . '"><a href="' . base_url() . 'admin/download?type=company/official_documents&filename=' . $r->document . '"><button type="button" class="btn btn-secondary btn-sm m-b-0-0 waves-effect waves-light" title="' . $this->lang->line('xin_download') . '"><i class="fa fa-download"></i></button></a></span>';
			} else {
				$doc_view = '';
			}
			$combhr = $doc_view . $edit;
			$ilicense_name = $r->license_name . '<br><small class="text-muted">' . $this->lang->line('xin_hr_official_license_number') . ': ' . $r->license_number . '</small>';
			$data[] = array(
				$combhr,
				$ilicense_name,
				$company_name,
				$r->expiry_date
			);
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
	// assets warranty list
	public function assets_warranty_list()
	{

		$session = $this->session->userdata('username');
		if (empty($session)) {
			redirect('admin/');
		}
		$data['title'] = $this->Core_model->site_title();
		if (!empty($session)) {
			$this->load->view("admin/employees_active/expired_documents_list", $data);
		} else {
			redirect('admin/');
		}
		// Datatables Variables
		$draw = intval($this->input->get("draw"));
		$start = intval($this->input->get("start"));
		$length = intval($this->input->get("length"));

		$role_resources_ids = $this->Core_model->user_role_resource();
		$user_info = $this->Core_model->read_user_info($session['user_id']);
		if ($user_info[0]->user_role_id == 1 || $user_info[0]->user_role_id == 4 || $user_info[0]->user_role_id == 5 || $user_info[0]->user_role_id == 6) {
			$assets = $this->Employees_model->warranty_assets_expired_all();
		} else {
			if (in_array('265', $role_resources_ids)) {
				$assets = $this->Employees_model->company_warranty_assets_expired_all($user_info[0]->company_id);
			} else {
				$assets = $this->Employees_model->user_warranty_assets_expired_all($session['user_id']);
			}
		}
		$data = array();

		foreach ($assets->result() as $r) {

			// get category
			$assets_category = $this->Assets_model->read_assets_category_info($r->assets_category_id);
			if (!is_null($assets_category)) {
				$category = $assets_category[0]->category_name;
			} else {
				$category = '?';
			}
			//working?
			if ($r->is_working == 1) {
				$working = $this->lang->line('xin_yes');
			} else {
				$working = $this->lang->line('xin_no');
			}
			// get user > added by
			$user = $this->Core_model->read_user_info($r->employee_id);
			// user full name
			if (!is_null($user)) {
				$full_name = $user[0]->first_name . ' ' . $user[0]->last_name;
			} else {
				$full_name = '?';
			}

			if (in_array('263', $role_resources_ids)) { //edit
				$edit = '<span data-toggle="tooltip" data-placement="top" title="' . $this->lang->line('xin_edit') . '"><button type="button" class="btn icon-btn btn-xs btn-default waves-effect waves-light" data-toggle="modal" data-target=".edit-modal-data" data-field_id="' . $r->assets_id . '" data-field_type="assets_warranty_expired"><i class="fa fa-pencil-square-o"></i></button></span>';
			} else {
				$edit = '';
			}

			if (in_array('265', $role_resources_ids)) { //view
				$view = '<span data-toggle="tooltip" data-placement="top" title="' . $this->lang->line('xin_view') . '"><button type="button" class="btn icon-btn btn-xs btn-default waves-effect waves-light" data-toggle="modal" data-target=".view-modal-data" data-asset_id="' . $r->assets_id . '"><span class="fa fa-eye"></span></button></span>';
			} else {
				$view = '';
			}
			$combhr = $edit;
			$created_at = $this->Core_model->set_date_format($r->created_at);
			$iname = $r->name . '<br><small class="text-muted">' . $this->lang->line('xin_created_at') . ': ' . $created_at . '</small>';
			$data[] = array(
				$combhr,
				$iname,
				$category,
				$r->company_asset_code,
				$working,
				$full_name
			);
		}
		$output = array(
			"draw" => $draw,
			"recordsTotal" => $assets->num_rows(),
			"recordsFiltered" => $assets->num_rows(),
			"data" => $data
		);
		echo json_encode($output);
		exit();
	}
	public function dialog_exp_document()
	{

		$session = $this->session->userdata('username');
		if (empty($session)) {
			redirect('admin/');
		}
		$data['title'] = $this->Core_model->site_title();
		$id = $this->input->get('field_id');
		$document = $this->Employees_model->read_document_information($id);
		$data = array(
			'document_id' => $document[0]->document_id,
			'document_type_id' => $document[0]->document_type_id,
			'd_employee_id' => $document[0]->employee_id,
			'all_document_types' => $this->Employees_model->all_document_types(),
			'date_of_expiry' => $document[0]->date_of_expiry,
			'title' => $document[0]->title,
			'is_alert' => $document[0]->is_alert,
			'description' => $document[0]->description,
			'notification_email' => $document[0]->notification_email,
			'document_file' => $document[0]->document_file
		);
		if (!empty($session)) {
			$this->load->view('admin/employees_active/dialog_employee_exp_details', $data);
		} else {
			redirect('admin/');
		}
	}

	public function dialog_exp_imgdocument()
	{

		$session = $this->session->userdata('username');
		if (empty($session)) {
			redirect('admin/');
		}
		$data['title'] = $this->Core_model->site_title();
		$id = $this->input->get('field_id');
		$document = $this->Employees_model->read_imgdocument_information($id);
		$data = array(
			'immigration_id' => $document[0]->immigration_id,
			'document_type_id' => $document[0]->document_type_id,
			'd_employee_id' => $document[0]->employee_id,
			'all_document_types' => $this->Employees_model->all_document_types(),
			'all_countries' => $this->Core_model->get_countries(),
			'document_number' => $document[0]->document_number,
			'document_file' => $document[0]->document_file,
			'issue_date' => $document[0]->issue_date,
			'expiry_date' => $document[0]->expiry_date,
			'country_id' => $document[0]->country_id,
			'eligible_review_date' => $document[0]->eligible_review_date,
		);
		if (!empty($session)) {
			$this->load->view('admin/employees_active/dialog_employee_exp_details', $data);
		} else {
			redirect('admin/');
		}
	}

	public function dialog_exp_company_license_expired()
	{
		$session = $this->session->userdata('username');
		if (empty($session)) {
			redirect('admin/');
		}
		$data['title'] = $this->Core_model->site_title();
		$id = $this->input->get('field_id');
		// $data['all_countries'] = $this->xin_model->get_countries();
		$result = $this->Company_model->read_company_document_info($id);
		$data = array(
			'document_id' => $result[0]->document_id,
			'license_name' => $result[0]->license_name,
			'company_id' => $result[0]->company_id,
			'expiry_date' => $result[0]->expiry_date,
			'license_number' => $result[0]->license_number,
			'license_notification' => $result[0]->license_notification,
			'document' => $result[0]->document,
			'all_countries' => $this->Core_model->get_countries(),
			'get_all_companies' => $this->Company_model->get_company(),
			'get_company_types' => $this->Company_model->get_company_types()
		);
		$this->load->view('admin/employees_active/dialog_employee_exp_details', $data);
	}
	public function dialog_exp_assets_warranty_expired()
	{
		$session = $this->session->userdata('username');
		if (empty($session)) {
			redirect('admin/');
		}
		$data['title'] = $this->Core_model->site_title();
		$id = $this->input->get('field_id');
		$result = $this->Assets_model->read_assets_info($id);
		$data = array(
			'assets_id' => $result[0]->assets_id,
			'assets_category_id' => $result[0]->assets_category_id,
			'company_id' => $result[0]->company_id,
			'employee_id' => $result[0]->employee_id,
			'company_asset_code' => $result[0]->company_asset_code,
			'name' => $result[0]->name,
			'purchase_date' => $result[0]->purchase_date,
			'invoice_number' => $result[0]->invoice_number,
			'manufacturer' => $result[0]->manufacturer,
			'serial_number' => $result[0]->serial_number,
			'warranty_end_date' => $result[0]->warranty_end_date,
			'asset_note' => $result[0]->asset_note,
			'asset_image' => $result[0]->asset_image,
			'is_working' => $result[0]->is_working,
			'created_at' => $result[0]->created_at,
			'all_employees' => $this->Core_model->all_employees(),
			'all_assets_categories' => $this->Assets_model->get_all_assets_categories(),
			'all_companies' => $this->Company_model->get_company()
		);
		$this->load->view('admin/employees_active/dialog_employee_exp_details', $data);
	}
	public function staff_dashboard()
	{
		$session = $this->session->userdata('username');
		if (empty($session)) {
			redirect('admin/');
		}
		$data['title'] = $this->lang->line('hr_staff_dashboard_title') . ' | ' . $this->Core_model->site_title();
		$data['all_departments'] = $this->Department_model->all_departments();
		$data['all_designations'] = $this->Designation_model->all_designations();
		$data['all_user_roles'] = $this->Roles_model->all_user_roles();
		$data['all_office_shifts'] = $this->Employees_model->all_office_shifts();
		$data['get_all_companies'] = $this->Company_model->get_company();
		$data['all_leave_types'] = $this->Timesheet_model->all_leave_types();
		$data['breadcrumbs'] = $this->lang->line('hr_staff_dashboard_title');
		$data['path_url'] = 'employees';
		$role_resources_ids = $this->Core_model->user_role_resource();
		if (in_array('422', $role_resources_ids)) {
			if (!empty($session)) {
				$data['subview'] = $this->load->view("admin/employees_active/staff_dashboard", $data, TRUE);
				$this->load->view('admin/layout/layout_main', $data); //page load
			} else {
				redirect('admin/');
			}
		} else {
			redirect('admin/dashboard');
		}
	}
	public function dialog_security_level()
	{

		$session = $this->session->userdata('username');
		if (empty($session)) {
			redirect('admin/');
		}
		$data['title'] = $this->Core_model->site_title();
		$id = $this->input->get('field_id');
		$result = $this->Employees_model->read_security_level_information($id);
		$data = array(
			'security_level_id' => $result[0]->security_level_id,
			'employee_id' => $result[0]->employee_id,
			'security_type' => $result[0]->security_type,
			'date_of_clearance' => $result[0]->date_of_clearance,
			'expiry_date' => $result[0]->expiry_date
		);
		if (!empty($session)) {
			$this->load->view('admin/employees_active/dialog_employee_details', $data);
		} else {
			redirect('admin/');
		}
	}

	public function reset_password()
	{
		$session = $this->session->userdata('username');
		if (empty($session)) {
			redirect('admin/');
		}

		$check_role = $this->Employees_model->read_employee_information($session['user_id']);
		if ($check_role[0]->user_role_id == 1) {
			$id = $this->uri->segment(4);

			$data = array(
				'password' => password_hash('123456', PASSWORD_BCRYPT, array('cost', 12)),
			);

			$result = $this->Employees_model->change_password($data, $id);
			if ($result == TRUE) {
				redirect("admin/employees_active/detail/{$id}?reset_password=1");
			}

			redirect("admin/employees_active/detail/{$id}?reset_password=0");
		}

		redirect("admin/employees_active");
	}
}
