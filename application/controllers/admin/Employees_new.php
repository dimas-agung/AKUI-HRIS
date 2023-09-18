<?php
 
 /**
 * INFORMASI
 *
 * Nama Aplikasi :  HRIS
 * Email Support :  hris@karyasoftware.com
 * Developer     :  Nizar Basyrewan, S.Si - 0895 606460 731
 * Tahun         :  2022
 * Copyright     :  ©karyasoftware.com. All Rights Reserved
 */

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Employees_new extends MY_Controller {
	
	 public function __construct() {
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
		
		$this->load->model("Awards_model");
		$this->load->model("Travel_model");
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
	
	// ===========================================================================================
	// START
	// ===========================================================================================
		/*Function to set JSON output*/
		public function output($Return=array()){
			/*Set response header*/
			header("Access-Control-Allow-Origin: *");
			header("Content-Type: application/json; charset=UTF-8");
			/*Final JSON response*/
			exit(json_encode($Return));
		}

	// ===========================================================================================
	// TABEL
	// ===========================================================================================
	
		public function index()
	    {
			$session = $this->session->userdata('username');
			if(empty($session)){ 
				redirect('admin/');
			}
			$role_resources_ids           = $this->Core_model->user_role_resource();
			$data['title']                = 'Karyawan Baru | '.$this->Core_model->site_title();
			$data['icon']                 = '<i class="fa fa-plus"></i>';
			$data['breadcrumbs']          = 'Karyawan Baru';
			$data['path_url']             = 'rekrutmen';
			$data['all_departments']      = $this->Department_model->all_departments();
			$data['all_designations']     = $this->Designation_model->all_designations();
			$data['all_user_roles']       = $this->Roles_model->all_user_roles();
			$data['all_office_shifts']    = $this->Employees_model->all_office_shifts();
			$data['get_all_companies']    = $this->Company_model->get_all_companies();
			$data['all_leave_types']      = $this->Timesheet_model->all_leave_types();
			
			if(in_array('0311',$role_resources_ids) ) {
				if(!empty($session)){ 
					$data['subview'] = $this->load->view("admin/employees_new/employees_list", $data, TRUE);
					$this->load->view('admin/layout/layout_main', $data); //page load
				} else {
					redirect('admin/');
				}
			} else {
				redirect('admin/dashboard');
			}
	    }
		
		public function employees_list()
	    {

			$data['title'] = $this->Core_model->site_title();
			$session = $this->session->userdata('username');
			if(!empty($session)){ 
				$this->load->view("admin/employees_new/employees_list", $data);
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
			
			$employee = $this->Employees_model->get_employees_active();
			
			
			$data = array();

			$no = 1;

	        foreach($employee->result() as $r) {		  
			
				// get company
				$company = $this->Core_model->read_company_info($r->company_id);
				if(!is_null($company)){
					$comp_name = $company[0]->name;
				} else {
					$comp_name = '<span class="badge bg-red"> ? </span>';
				}

				if($r->view_company_id == '') {
					$vc = '--';
				} else {
					$vc = '<ol class="nl">';
					foreach(explode(',',$r->view_company_id) as $uid) {
						$user = $this->Core_model->read_view_company_info($uid);
						if(!is_null($user)){
							$vc .= '<li>'.$user[0]->name.'</li>';
						} else {
							$vc .= '--';
						}
					 }
					 $vc .= '</ol>';
				}
				
				// user full name 
				$full_name = $r->first_name.' '.$r->last_name;
				
				// PIN		
				$employment_pin = $r->employee_pin;
				if( $employment_pin != ''){
			
					$emp_pin = $r->employee_pin;
				} else{
					$emp_pin = '<span class="badge bg-red"> ? </span>';
				}			
				
				// jenis gaji
				$wages_type = $this->Core_model->read_user_jenis_gaji($r->wages_type);
				// user full name
				if(!is_null($wages_type)){
					$jenis_gaji       = $wages_type[0]->jenis_gaji_keterangan;
					$jenis_gaji_warna = $wages_type[0]->warna;
				} else {
					$jenis_gaji = '<span class="badge bg-red"> ? </span>';
					$jenis_gaji_warna = '';
				}
				// grade
				$grade_type = $this->Core_model->read_user_jenis_grade($r->grade_type);
				// user full name
				if(!is_null($grade_type)){
					$jenis_grade       = $grade_type[0]->jenis_grade_keterangan;
					$jenis_grade_warna = $grade_type[0]->warna;
				} else {
					$jenis_grade = '<span class="badge bg-red"> ? </span>';
					$jenis_grade_warna = '';
				}
				// get designation
				$designation = $this->Designation_model->read_designation_information($r->designation_id);
				if(!is_null($designation)){
					$designation_name = $designation[0]->designation_name;
				} else {
					$designation_name = '<span class="badge bg-red"> ? </span>';	
				}

				// department
				$department = $this->Department_model->read_department_information($r->department_id);
				if(!is_null($department)){
					$department_name = $department[0]->department_name;
				} else {
					$department_name = '<span class="badge bg-red"> ? </span>';	
				}


				
				// location
				$location = $this->Location_model->read_location_information($r->location_id);
				if(!is_null($location)){
					$location_name = $location[0]->location_name;
				} else {
					$location_name = '<span class="badge bg-red"> ? </span>';	
				}			
				
				$department_designation = $designation_name.' ('.$department_name.')';

				$cek_emp_status =  $this->Employees_model->read_employee_contract_information($r->user_id);
				
				if(!is_null($cek_emp_status)){
					$emp_status_name = '<span class="badge bg-green">'.$cek_emp_status[0]->name_type.'</span>';
				
				} else {

					if($r->emp_status =='Tetap'){
						$emp_status_name = '<span class="badge bg-green"> Tetap </span>';
					} else {
						$emp_status_name = '<span class="badge bg-red"> ? </span>';
					}					
				}

				if($r->emp_status =='') {
					$emp_status = '<span class="badge bg-red"> ? </span>';
				}
				elseif($r->emp_status =='Tetap'){
					 $emp_status = '<span class="badge bg-green">'.$this->lang->line('xin_employee_status_tetap').'</span>';
				}
				elseif($r->emp_status =='Kontrak') {
					 $emp_status = '<span class="badge bg-green">'.$this->lang->line('xin_employee_status_kontrak').'</span>';
				}
				elseif($r->emp_status =='Percobaan') {
					 $emp_status = '<span class="badge bg-green">'.$this->lang->line('xin_employee_status_percobaan').'</span>';
				}
				
				// get status
				if($r->is_active==0): $status = '<span class="badge bg-red">'.$this->lang->line('xin_employees_inactive').'</span>';
				elseif($r->is_active==1): $status = '<span class="badge bg-green">'.$this->lang->line('xin_employees_active').'</span>';endif;
				
				if(in_array('0313',$role_resources_ids)) {
					$edit_opt = ' <span data-toggle="tooltip" data-placement="top" title="Edit Karyawan">
										<a target="_blank" href="'.site_url().'admin/employees_new/detail/'.$r->user_id.'">
											<button type="button" class="btn icon-btn btn-xs btn-default waves-effect waves-light">
												<span class="fa fa-pencil"></span> Edit
											</button>
										</a>
									</span>';
				} else {
					$edit_opt = '';
				}

				if(in_array('0314',$role_resources_ids)) {
					$del_opt = '<span data-toggle="tooltip" data-placement="top" title="'.$this->lang->line('xin_delete').'">
									<button type="button" class="btn icon-btn btn-xs btn-danger delete" data-toggle="modal" data-target=".delete-modal" data-record-id="'. $r->user_id . '">
										<span class="fa fa-trash"></span>
									</button>
								</span>';
				} else {
					$del_opt = '';
				}				
				
				$function =$edit_opt.''.$del_opt;

				$bsalary = $this->Core_model->currency_sign($r->basic_salary);
							
				if($r->profile_picture!='' && $r->profile_picture!='no file') {
					$ol = '<a href="javascript:void(0);"><span class="avatar box-16"><img src="'.base_url().'uploads/profile/'.$r->profile_picture.'" class="user-image-hr46" alt=""></span></a>';
				} else {
					if($r->gender=='Male') { 
						$de_file = base_url().'uploads/profile/default_male.jpg';
					 } else {
						$de_file = base_url().'uploads/profile/default_female.jpg';
					 }
					$ol = '<a href="javascript:void(0);"><span class="avatar box-16"><img src="'.$de_file.'" class="user-image-hr46" alt=""></span></a>';
				}
				//shift info
				$office_shift = $this->Timesheet_model->read_office_shift_information($r->office_shift_id);
				if(!is_null($office_shift)){
					$shift = $office_shift[0]->shift_name;
				} else {
					$shift = 'Pola Kerja Belum dibuat';	
				}

				// shift info
				if ($r->office_id == 'R') {
					$shift_id ='Reguler';
				
				} else if ($r->office_id == 'S') {
					$shift_id ='Shift';
				
				} else {
					$shift_id ='Pola Kerja Belum dibuat';
				}
		
				date_default_timezone_set("Asia/Jakarta");     
            
	            $tanggal1 = new DateTime($r->date_of_birth);
				$tanggal2 = new DateTime();
	      		
	      		if ($tanggal2->diff($tanggal1)->y == 0) {
	      			$selisih   = $tanggal2->diff($tanggal1)->m.' bln';
	      			
	      		} else {
	      			$selisih   = $tanggal2->diff($tanggal1)->y.' thn'.' '.$tanggal2->diff($tanggal1)->m.' bln';
	      			
	      		}

				$employee_foto = $ol;

				if ($r->ibu_name == ''){
					$ibu = '<span class="badge bg-red"> ? </span>';
				} else {
					$ibu = $r->ibu_name;
				}
				
				$employee_date = date("d-m-Y", strtotime($r->date_of_joining)).' <span class="badge bg-green"> Aktif </span><br> 
			<small class="text-muted">Tgl Lhr : '.date("d-m-Y", strtotime($r->date_of_birth)).' <br>Usia : '.$selisih.'<br>Ibu : '.$ibu.' </small>';
				
				$employee_name = strtoupper($full_name).'<br><small class="text-muted ">'.$this->lang->line('xin_employees_id').': '.$r->employee_id.'</small><br><small class="text-muted ">PIN : '.strtoupper($emp_pin).'</small>';
				
				$comp_name     = $comp_name.'<br><small class="text-muted"> '.$location_name.'</small><br><small class="text-muted"> '.$department_name.'</small>';
				
				$posisi_name   = strtoupper($designation_name).'<br><small class="text-muted"> '.$shift_id.' <br> '.$shift.' </small>';
			
				$contact_info  = '<i class="fa fa-envelope" data-toggle="tooltip" data-placement="top" title="'.$this->lang->line('dashboard_email').'"></i> '.$r->email.'<br><i class="fa fa-phone" data-toggle="tooltip" data-placement="top" title="'.$this->lang->line('xin_contact_number').'"></i> '.$r->contact_no;
				
				$rolemp_status = '<span class="'.$jenis_gaji_warna.'">'.$jenis_gaji.'</sapan>';

				$grade_status  = '<span class="'.$jenis_grade_warna.'">'.$jenis_grade.'</sapan>';
				
				$data[]        = array(
					$function,
					$employee_foto,
					$employee_date,
					$employee_name,
					$comp_name,
					$posisi_name,
					$emp_status.'<br>'.$emp_status_name,
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

	// ===========================================================================================
	// PROSES
	// ===========================================================================================
	    public function detail() 
		{

			$session = $this->session->userdata('username');

			if(empty($session)){ 
				redirect('admin/');
			}
			
			$id = $this->uri->segment(4);
			
			$result = $this->Employees_model->read_employee_information($id);
			
			if(is_null($result)){
				redirect('admin/employees_new');
			}

			$role_resources_ids = $this->Core_model->user_role_resource();
			
			$check_role = $this->Employees_model->read_employee_information($session['user_id']);
			
			if(!in_array('0310',$role_resources_ids)) {
				redirect('admin/employees_new');
			}
				

			$data = array(
				'title'             => 'Edit Karyawan Baru | '.$this->Core_model->site_title(),
				'icon'              => '<i class="fa fa-pencil"></i>',
				'breadcrumbs'       => 'Edit Karyawan Baru',
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
				'user_role_id' => $result[0]->user_role_id,
				'date_of_birth' => $result[0]->date_of_birth,
				'place_of_birth' => $result[0]->place_of_birth,
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
				'all_countries' => $this->Core_model->get_countries()
				
			);
			
			$data['subview'] = $this->load->view("admin/employees_new/employee_detail", $data, TRUE);
			$this->load->view('admin/layout/layout_main', $data); //page load
			
			// Datatables Variables
			$draw = intval($this->input->get("draw"));
			$start = intval($this->input->get("start"));
			$length = intval($this->input->get("length"));
		}
				
		public function add_employee() 
		{		
			if($this->input->post('add_type')=='employee') 
			{
				/* Define return | here result is used to return user data and error for error message */
				$Return = array('result'=>'', 'error'=>'', 'csrf_hash'=>'');
				$Return['csrf_hash'] = $this->security->get_csrf_hash();		
		   	
				//$office_shift_id = $this->input->post('office_shift_id');
				$system = $this->Core_model->read_setting_info(1);
				
				/* Server side PHP input validation */		
			
				if($this->input->post('first_name')==='') {
		        	$Return['error'] = $this->lang->line('xin_employee_error_first_name');
				
				} else if($this->input->post('last_name')==='') {
					$Return['error'] = $this->lang->line('xin_employee_error_last_name');

				} else if($this->input->post('ibu_name')==='') {
					$Return['error'] = 'Ibu Kandung Wajib Diisi';
								
				} else if($this->input->post('employee_pin')==='') {
				    $Return['error'] = $this->lang->line('xin_employee_error_employee_pin');
				
				} else if($this->input->post('employee_id')==='') {
					$Return['error'] = $this->lang->line('xin_employee_error_employee_id');
				   
				} else if($this->Employees_model->check_employee_id($this->input->post('employee_id')) > 0) {
					 $Return['error'] = $this->lang->line('xin_employee_id_already_exist');
				
				} else if($this->Employees_model->check_employee_id($this->input->post('employee_pin')) > 0) {
					$Return['error'] = $this->lang->line('xin_employee_pin_already_exist');

				} else if($this->input->post('address')==='') {
					$Return['error'] = 'Alamat Domisili Tidak Boleh Kosong';

				} else if($this->input->post('address_ktp')==='') {
					$Return['error'] = 'Alamat KTP Tidak Boleh Kosong';
			
				} else if($this->input->post('emp_status')==='') {
					$Return['error'] = $this->lang->line('xin_employee_error_status');				
				
				} else if($this->Employees_model->check_employee_email($this->input->post('email')) > 0) {
					$Return['error'] = $this->lang->line('xin_employee_email_already_exist');
				
				} else if($this->input->post('company_id')==='') {
					$Return['error'] = $this->lang->line('error_company_field');

				} else if($this->input->post('view_company_id')==='') {
					$Return['error'] = 'Kelola data perusahaan wajib diisi';
			
				} else if($this->input->post('date_of_birth')==='') {
					$Return['error'] = $this->lang->line('xin_employee_error_date_of_birth');

				} else if($this->input->post('place_of_birth')==='') {
					$Return['error'] = 'Tempat Lahir Wajib Diisi';
				
				} else if($this->Core_model->validate_date($this->input->post('date_of_birth'),'Y-m-d') == false) {
					$Return['error'] = $this->lang->line('xin_hr_date_format_error');
				
				} else if($this->input->post('contact_no')==='') {
					$Return['error'] = $this->lang->line('xin_employee_error_contact_number');
				
				} else if(!preg_match('/^([0-9]*)$/', $this->input->post('contact_no'))) {
					$Return['error'] = 'Nomor Kontak harus bilangan';
					
				} else if($this->input->post('department_id')==='') {
					$Return['error'] = $this->lang->line('xin_employee_error_department');
				
				} else if($this->input->post('designation_id')==='') {
					$Return['error'] = $this->lang->line('xin_employee_error_designation');				
			
				} else if($this->input->post('date_of_joining')==='') {
					$Return['error'] = $this->lang->line('xin_employee_error_joining_date');

				} else if($this->Core_model->validate_date($this->input->post('date_of_joining'),'Y-m-d') == false) {
					$Return['error'] = $this->lang->line('xin_hr_date_format_error');		
				
				} else if($this->input->post('office_shift_id')==='') {
					$Return['error'] = $this->lang->line('xin_employee_error_shift_name');
				
				 } else if($this->input->post('wages_type')==='') {
					$Return['error'] = 'Jenis Gaji Tidak Boleh Kosong'; 

				 } else if($this->input->post('ethnicity_type')==='') {
					$Return['error'] = 'Jenis Agama Tidak Boleh Kosong'; 			
					
				} else if($this->input->post('password')==='') {
					$Return['error'] = $this->lang->line('xin_employee_error_password');
				
				} else if(strlen($this->input->post('password')) < 6) {
					$Return['error'] = $this->lang->line('xin_employee_error_password_least');
				
				} else if($this->input->post('password')!==$this->input->post('confirm_password')) {
					$Return['error'] = $this->lang->line('xin_employee_error_password_not_match');
				
				} else if($this->input->post('role')==='') {
					$Return['error'] = $this->lang->line('xin_employee_error_user_role');
				} 
				
				if($Return['error']!=''){
					$this->output($Return);
				}
							
				$first_name       = $this->Core_model->clean_post($this->input->post('first_name'));
				$last_name        = $this->Core_model->clean_post($this->input->post('last_name'));

				$ibu_name        = $this->input->post('ibu_name');

				$employee_id      = $this->Core_model->clean_post($this->input->post('employee_id'));
				$date_of_joining  = $this->Core_model->clean_date_post($this->input->post('date_of_joining'));
				// $username         = $this->Core_model->clean_post($this->input->post('username'));
				$date_of_birth    = $this->Core_model->clean_date_post($this->input->post('date_of_birth'));
				$place_of_birth    =$this->input->post('place_of_birth');

				$contact_no       = $this->Core_model->clean_post($this->input->post('contact_no'));
				
				$address          = $this->Core_model->clean_post($this->input->post('address'));
				$address_ktp      = $this->Core_model->clean_post($this->input->post('address_ktp'));

				$options          = array('cost' => 12);
				$password_hash    = password_hash($this->input->post('password'), PASSWORD_BCRYPT, $options);
				$leave_categories = array($this->input->post('leave_categories'));
				$cat_ids          = implode(',',$this->input->post('leave_categories'));

				$data = array(
					'employee_id'       => $employee_id,
					'employee_pin'      => $this->input->post('employee_pin'),
					'office_shift_id'   => $this->input->post('office_shift_id'),
					'reports_to'        => $this->input->post('reports_to'),
					'first_name'        => $first_name,
					'last_name'         => $last_name,
					'ibu_name'          => $ibu_name,						
					'ethnicity_type'    => $this->input->post('ethnicity_type'),
					'emp_status'        => $this->input->post('emp_status'),
					'company_id'        => $this->input->post('company_id'),
					'location_id'       => $this->input->post('location_id'),
					'email'             => $this->input->post('email'),
					'password'          => $password_hash,
					'date_of_birth'     => $date_of_birth,
					'place_of_birth'     => $place_of_birth,
					'gender'            => $this->input->post('gender'),
					'wages_type'        => $this->input->post('wages_type'),
					'user_role_id'      => $this->input->post('role'),
					'department_id'     => $this->input->post('department_id'),
					'view_company_id'   => $this->input->post('view_company_id'),
					'sub_department_id' => $this->input->post('subdepartment_id'),
					'designation_id'    => $this->input->post('designation_id'),
					'date_of_joining'   => $date_of_joining,
					'contact_no'        => $contact_no,
					'address'           => $address,
					'address_ktp'       => $address_ktp,
					'is_active'         => 1,
					'leave_categories'  => $cat_ids,
					'created_at'        => date('Y-m-d h:i:s')
				);
				$iresult = $this->Employees_model->add($data);

				// echo "<pre>";
				// print_r($this->db->last_query());
				// echo "</pre>";
				// die();
				
				if ($iresult) {				
					$Return['result'] = $this->lang->line('xin_success_add_employee');
				} else {
					$Return['error'] = $this->lang->line('xin_error_msg');
				}
				$this->output($Return);
				exit;
			}
		}

		public function basic_info() 
		{
		
			if($this->input->post('type')=='basic_info') {	

			/* Define return | here result is used to return user data and error for error message */
			$Return = array('result'=>'', 'error'=>'', 'csrf_hash'=>'');
			$Return['csrf_hash'] = $this->security->get_csrf_hash();
			
			//$office_shift_id = $this->input->post('office_shift_id');
			$system = $this->Core_model->read_setting_info(1);
				
			/* Server side PHP input validation */		
			if($this->input->post('first_name')==='') {

	        	$Return['error'] = $this->lang->line('xin_employee_error_first_name');

			} else if($this->input->post('last_name')==='') {
				$Return['error'] = $this->lang->line('xin_employee_error_last_name');

			} else if($this->input->post('ibu_name')==='') {
				$Return['error'] = 'Nama Ibu Kandung Wajib Diisi';

			} else if($this->input->post('employee_id')==='') {
				 $Return['error'] = $this->lang->line('xin_employee_error_employee_id');

			} else if($this->input->post('employee_pin')==='') {
				 $Return['error'] = $this->lang->line('xin_employee_error_employee_pin');
		    
		    } else if($this->input->post('wages_type')==='') {
				$Return['error'] = 'Jenis Gaji Tidak Boleh Kosong';
			
			} else if($this->input->post('grade_type')==='') {
				$Return['error'] = 'Grade Gaji Tidak Boleh Kosong';

			} else if($this->input->post('office_id')==='') {
				$Return['error'] = 'Jenis Pola Kerja Tidak Boleh Kosong';

			} else if($this->input->post('address')==='') {
				$Return['error'] = 'Alamat Domisili Tidak Boleh Kosong';

			} else if($this->input->post('address_ktp')==='') {
				$Return['error'] = 'Alamat KTP Tidak Boleh Kosong';

			} else if($this->input->post('view_company_id')==='') {
				$Return['error'] = 'Kelola data perusahaan wajib diisi';	
			
			} else if($this->input->post('emp_status')==='') {
				 $Return['error'] = $this->lang->line('xin_employee_error_status');

			} else if($this->input->post('employee_ktp')==='') {
				$Return['error'] = $this->lang->line('xin_employee_error_employee_ktp');

			} else if($this->input->post('office_shift_id')==='') {
				$Return['error'] = $this->lang->line('xin_employee_error_employee_ktp');

			} else if($this->input->post('company_id')==='') {
				 $Return['error'] = $this->lang->line('error_company_field');
			
			} else if($this->input->post('location_id')==='') {
				 $Return['error'] = $this->lang->line('xin_location_field_error');
			
			} else if($this->input->post('department_id')==='') {
				 $Return['error'] = $this->lang->line('xin_employee_error_department');
			
			} else if($this->input->post('subdepartment_id')==='') {
	        	$Return['error'] = $this->lang->line('xin_hr_sub_department_field_error');
			
			} else if($this->input->post('designation_id')==='') {
				 $Return['error'] = $this->lang->line('xin_employee_error_designation');
			
			} else if($this->input->post('date_of_birth')==='') {
				 $Return['error'] = $this->lang->line('xin_employee_error_date_of_birth');

			} else if($this->input->post('place_of_birth')==='') {
				 $Return['error'] = 'Tempat Lahir Wajib Diisi';
			
			} else if($this->Core_model->validate_date($this->input->post('date_of_birth'),'Y-m-d') == false) {
				 $Return['error'] = $this->lang->line('xin_hr_date_format_error');
			
			} else if($this->input->post('date_of_joining')==='') {
				 $Return['error'] = $this->lang->line('xin_employee_error_joining_date');
			
			} else if($this->Core_model->validate_date($this->input->post('date_of_joining'),'Y-m-d') == false) {
				 $Return['error'] = $this->lang->line('xin_hr_date_format_error');
			
			}  else if($this->input->post('role')==='') {
				 $Return['error'] = $this->lang->line('xin_employee_error_user_role');
			
			} else if($this->input->post('contact_no')==='') {
				 $Return['error'] = $this->lang->line('xin_employee_error_contact_number');
			
			} else if(!preg_match('/^([0-9]*)$/', $this->input->post('contact_no'))) {
				 $Return['error'] = 'Nomor Kontak harus bilangan';
			}
					
			if($Return['error']!=''){
	       		$this->output($Return);
	    	}
					
			if($Return['error']!=''){
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
			$place_of_birth    = $this->input->post('place_of_birth');

			$contact_no        = $this->Core_model->clean_post($this->input->post('contact_no'));
			
			$address           = $this->Core_model->clean_post($this->input->post('address'));
			$address_ktp       = $this->Core_model->clean_post($this->input->post('address_ktp'));

			$leave_categories  = array($this->input->post('leave_categories'));
			$cat_ids           = implode(',',$this->input->post('leave_categories'));						
					
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
			$result = $this->Employees_model->basic_info($data,$id);

			if ($result == TRUE) {
				$Return['result'] = $this->lang->line('xin_employee_basic_info_updated');
			} else {
				$Return['error'] = $this->lang->line('xin_error_msg');
			}
			$this->output($Return);
			exit;
			}
		}

		public function delete() 
		{
			if($this->input->post('is_ajax')=='2') {
				/* Define return | here result is used to return user data and error for error message */
				$Return = array('result'=>'', 'error'=>'', 'csrf_hash'=>'');
				$Return['csrf_hash'] = $this->security->get_csrf_hash();
				$id = $this->uri->segment(4);
				$result = $this->Employees_model->delete_record($id);
				if(isset($id)) {
					$Return['result'] = $this->lang->line('xin_employee_current_deleted');
				} else {
					$Return['error'] = $this->lang->line('xin_error_msg');
				}
				$this->output($Return);
			}
		}

	// ===========================================================================================
	// CONTACT
	// ===========================================================================================

		public function contacts()
	    {

			$data['title'] = $this->Core_model->site_title();
			$session = $this->session->userdata('username');
			if(!empty($session)){ 
				$this->load->view("admin/employees/employee_detail", $data);
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

	        foreach($contacts->result() as $r) {
				
				if($r->is_primary==1){
					$primary = '<span class="tag tag-success">'.$this->lang->line('xin_employee_primary').'</span>';
				 } else {
					 $primary = '';
				 }
				 if($r->is_dependent==2){
					$dependent = '<span class="tag tag-danger">'.$this->lang->line('xin_employee_dependent').'</span>';
				 } else {
					 $dependent = '';
				 }
			
			$data[] = array(
				'<span data-toggle="tooltip" data-placement="top" title="'.$this->lang->line('xin_edit').'"><button type="button" class="btn btn-secondary btn-sm m-b-0-0 waves-effect waves-light" data-toggle="modal" data-target=".edit-modal-data" data-field_id="'. $r->contact_id . '" data-field_type="contact"><i class="fa fa-pencil-square-o"></i></button></span><span data-toggle="tooltip" data-placement="top" title="'.$this->lang->line('xin_delete').'"><button type="button" class="btn btn-danger btn-sm m-b-0-0 waves-effect waves-light delete" data-toggle="modal" data-target=".delete-modal" data-record-id="'. $r->contact_id . '" data-token_type="contact"><i class="fa fa-trash-o"></i></button></span>',
				$r->contact_name . ' ' .$primary . ' '.$dependent,
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
	 
		// Validate and update info in database // contact info
		public function update_contacts_info() 
		{
			if($this->input->post('type')=='contact_info') {		
			
				/* Define return | here result is used to return user data and error for error message */
				$Return = array('result'=>'', 'error'=>'', 'csrf_hash'=>'');
				$Return['csrf_hash'] = $this->security->get_csrf_hash();
					
				/* Server side PHP input validation */		
				/* Server side PHP input validation */		
				if($this->input->post('salutation')==='') {
		       		 $Return['error'] = $this->lang->line('xin_employee_error_salutation');
				} else if($this->input->post('contact_name')==='') {
					$Return['error'] = $this->lang->line('xin_employee_error_contact_name');
				} else if($this->input->post('relation')==='') {
					 $Return['error'] = $this->lang->line('xin_employee_error_grp');
				} else if($this->input->post('primary_email')==='') {
					 $Return['error'] = $this->lang->line('xin_employee_error_pemail');
				} else if($this->input->post('mobile_phone')==='') {
					 $Return['error'] = $this->lang->line('xin_employee_error_mobile');
				} else if($this->input->post('city')==='') {
					 $Return['error'] = $this->lang->line('xin_error_city_field');
				} else if($this->input->post('country')==='') {
					 $Return['error'] = $this->lang->line('xin_error_country_field');
				}
						
				if($Return['error']!=''){
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
				if ($query->num_rows() > 0 ) {
					$res = $query->result();
					$e_field_id = $res[0]->contact_id;
					$result = $this->Employees_model->contact_info_update($data,$e_field_id);
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
			if($this->input->post('type')=='contact_info') {		
			
				/* Define return | here result is used to return user data and error for error message */
				$Return = array('result'=>'', 'error'=>'', 'csrf_hash'=>'');
				$Return['csrf_hash'] = $this->security->get_csrf_hash();
					
				/* Server side PHP input validation */		
				/* Server side PHP input validation */		
				if($this->input->post('salutation')==='') {
		       		 $Return['error'] = $this->lang->line('xin_employee_error_salutation');
				} else if($this->input->post('contact_name')==='') {
					$Return['error'] = $this->lang->line('xin_employee_error_contact_name');
				} else if($this->input->post('relation')==='') {
					 $Return['error'] = $this->lang->line('xin_employee_error_grp');
				} else if($this->input->post('primary_email')==='') {
					 $Return['error'] = $this->lang->line('xin_employee_error_pemail');
				} else if($this->input->post('mobile_phone')==='') {
					 $Return['error'] = $this->lang->line('xin_employee_error_mobile');
				} else if($this->input->post('city')==='') {
					 $Return['error'] = $this->lang->line('xin_error_city_field');
				} else if($this->input->post('country')==='') {
					 $Return['error'] = $this->lang->line('xin_error_country_field');
				}
					
			if($Return['error']!=''){
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
			if ($query->num_rows() > 0 ) {
				$res = $query->result();
				$e_field_id = $res[0]->contact_id;
				$result = $this->Employees_model->contact_info_update($data,$e_field_id);
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
		public function contact_info() {
		
			if($this->input->post('type')=='contact_info') {		
			/* Define return | here result is used to return user data and error for error message */
			$Return = array('result'=>'', 'error'=>'', 'csrf_hash'=>'');
			$Return['csrf_hash'] = $this->security->get_csrf_hash();
				
			/* Server side PHP input validation */		
			if($this->input->post('relation')==='') {
	       		 $Return['error'] = $this->lang->line('xin_employee_error_relation');
			} else if($this->input->post('contact_name')==='') {
				$Return['error'] = $this->lang->line('xin_employee_error_contact_name');
			} else if(!preg_match("/^(\pL{1,}[ ]?)+$/u",$this->input->post('contact_name'))) {
				$Return['error'] = $this->lang->line('xin_hr_string_error');
			} else if($this->input->post('contact_no')!=='' && !preg_match('/^([0-9]*)$/', $this->input->post('contact_no'))) {
				 $Return['error'] = 'Nomor Kontak harus bilangan';
			} else if($this->input->post('work_phone')!=='' && !preg_match('/^([0-9]*)$/', $this->input->post('work_phone'))) {
				 $Return['error'] = 'Nomor Telpon harus bilangan';
			} else if($this->input->post('work_phone_extension')!=='' && !preg_match('/^([0-9]*)$/', $this->input->post('work_phone_extension'))) {
				 $Return['error'] = 'Nomor Telpon harus bilangan';
			} else if($this->input->post('mobile_phone')==='') {
				 $Return['error'] = $this->lang->line('xin_employee_error_mobile');
			} else if(!preg_match('/^([0-9]*)$/', $this->input->post('mobile_phone'))) {
				 $Return['error'] = 'Nomor HP harus bilangan';
			} else if($this->input->post('home_phone')!=='' && !preg_match('/^([0-9]*)$/', $this->input->post('home_phone'))) {
				 $Return['error'] = 'Nomor Telpon harus bilangan';
			} else if($this->input->post('work_email')==='') {
				 $Return['error'] = $this->lang->line('xin_employee_error_email');
			} else if (!filter_var($this->input->post('work_email'), FILTER_VALIDATE_EMAIL)) {
				$Return['error'] = $this->lang->line('xin_employee_error_invalid_email');
			} else if ($this->input->post('personal_email')!=='' && !filter_var($this->input->post('personal_email'), FILTER_VALIDATE_EMAIL)) {
				$Return['error'] = $this->lang->line('xin_employee_error_invalid_email');
			} else if($this->input->post('zipcode')!=='' && !preg_match('/^([0-9]*)$/', $this->input->post('zipcode'))) {
				 $Return['error'] = 'Kode Pos harus bilangan';
			}
			
			if(null!=$this->input->post('is_primary')) {
				$is_primary = $this->input->post('is_primary');
			} else {
				$is_primary = '';
			}
			if(null!=$this->input->post('is_dependent')) {
				$is_dependent = $this->input->post('is_dependent');
			} else {
				$is_dependent = '';
			}
					
			if($Return['error']!=''){
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
		public function e_contact_info() {
		
			if($this->input->post('type')=='e_contact_info') {		
			/* Define return | here result is used to return user data and error for error message */
			$Return = array('result'=>'', 'error'=>'', 'csrf_hash'=>'');
			$Return['csrf_hash'] = $this->security->get_csrf_hash();
				
			/* Server side PHP input validation */		
			if($this->input->post('relation')==='') {
	       		 $Return['error'] = $this->lang->line('xin_employee_error_relation');
			} else if($this->input->post('contact_name')==='') {
				$Return['error'] = $this->lang->line('xin_employee_error_contact_name');
			} else if($this->input->post('mobile_phone')==='') {
				 $Return['error'] = $this->lang->line('xin_employee_error_mobile');
			}
			
			if(null!=$this->input->post('is_primary')) {
				$is_primary = $this->input->post('is_primary');
			} else {
				$is_primary = '';
			}
			if(null!=$this->input->post('is_dependent')) {
				$is_dependent = $this->input->post('is_dependent');
			} else {
				$is_dependent = '';
			}
					
			if($Return['error']!=''){
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
			$result = $this->Employees_model->contact_info_update($data,$e_field_id);
			if ($result == TRUE) {
				$Return['result'] = $this->lang->line('xin_employee_contact_info_updated');
			} else {
				$Return['error'] = $this->lang->line('xin_error_msg');
			}
			$this->output($Return);
			exit;
			}
		}

		public function dialog_contact() 
		{
			
			$session = $this->session->userdata('username');
			if(empty($session)){ 
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
			if(!empty($session)){ 
				$this->load->view('admin/employees/dialog_employee_details', $data);
			} else {
				redirect('admin/');
			}
		}

		public function delete_contact() {
		
			if($this->input->post('data')=='delete_record') {
				/* Define return | here result is used to return user data and error for error message */
				$Return = array('result'=>'', 'error'=>'', 'csrf_hash'=>'');
				$id = $this->uri->segment(4);
				$result = $this->Employees_model->delete_contact_record($id);
				$Return['csrf_hash'] = $this->security->get_csrf_hash();
				if(isset($id)) {
					$Return['result'] = $this->lang->line('xin_employee_contact_deleted');
				} else {
					$Return['error'] = $this->lang->line('xin_error_msg');
				}
				$this->output($Return);
			}
		}

	// ===========================================================================================
	// PENDIDIKAN
	// ===========================================================================================

		public function qualification() {
			//set data
			$data['title'] = $this->Core_model->site_title();
			$session = $this->session->userdata('username');
			if(!empty($session)){ 
				$this->load->view("admin/employees/employee_detail", $data);
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

	        foreach($qualification->result() as $r) {
				
				$education = $this->Employees_model->read_education_information($r->education_level_id);
				if(!is_null($education)){
					$edu_name = $education[0]->name;
				} else {
					$edu_name = '?';
				}
			
				$sdate = $this->Core_model->set_date_format($r->from_year);
				$edate = $this->Core_model->set_date_format($r->to_year);	
				
				$time_period = $sdate.' - '.$edate;
				// get date
				$pdate = $time_period;
				$data[] = array(
				'<span data-toggle="tooltip" data-placement="top" title="'.$this->lang->line('xin_edit').'"><button type="button" class="btn btn-secondary btn-sm m-b-0-0 waves-effect waves-light" data-toggle="modal" data-target=".edit-modal-data" data-field_id="'. $r->qualification_id . '" data-field_type="qualification"><i class="fa fa-pencil-square-o"></i></button></span><span data-toggle="tooltip" data-placement="top" title="'.$this->lang->line('xin_delete').'"><button type="button" class="btn btn-danger btn-sm m-b-0-0 waves-effect waves-light delete" data-toggle="modal" data-target=".delete-modal" data-record-id="'. $r->qualification_id . '" data-token_type="qualification"><i class="fa fa-trash-o"></i></button></span>',
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
	 
		// Validate and add info in database // qualification info
		public function qualification_info() {
		
			if($this->input->post('type')=='qualification_info') {		
			/* Define return | here result is used to return user data and error for error message */
			$Return = array('result'=>'', 'error'=>'', 'csrf_hash'=>'');
			$Return['csrf_hash'] = $this->security->get_csrf_hash();
				
			/* Server side PHP input validation */	
			$from_year = $this->input->post('from_year');
			$to_year = $this->input->post('to_year');
			$st_date = strtotime($from_year);
			$ed_date = strtotime($to_year);
				
			if($this->input->post('name')==='') {
	       		 $Return['error'] = $this->lang->line('xin_employee_error_sch_uni');
			} else if(preg_match("/^(\pL{1,}[ ]?)+$/u",$this->input->post('name'))!=1) {
				$Return['error'] = $this->lang->line('xin_hr_string_error');
			} else if($this->input->post('from_year')==='') {
				$Return['error'] = $this->lang->line('xin_employee_error_frm_date');
			} else if($this->Core_model->validate_date($this->input->post('from_year'),'Y-m-d') == false) {
				 $Return['error'] = $this->lang->line('xin_hr_date_format_error');
			} else if($this->input->post('to_year')==='') {
				 $Return['error'] = $this->lang->line('xin_employee_error_to_date');
			} else if($this->Core_model->validate_date($this->input->post('to_year'),'Y-m-d') == false) {
				 $Return['error'] = $this->lang->line('xin_hr_date_format_error');
			} else if($st_date > $ed_date) {
				$Return['error'] = $this->lang->line('xin_employee_error_date_shouldbe');
			}
					
			if($Return['error']!=''){
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
		public function e_qualification_info() {
		
			if($this->input->post('type')=='e_qualification_info') {		
			/* Define return | here result is used to return user data and error for error message */
			$Return = array('result'=>'', 'error'=>'', 'csrf_hash'=>'');
			$Return['csrf_hash'] = $this->security->get_csrf_hash();
				
			/* Server side PHP input validation */		
			$from_year = $this->input->post('from_year');
			$to_year = $this->input->post('to_year');
			$st_date = strtotime($from_year);
			$ed_date = strtotime($to_year);
				
			if($this->input->post('name')==='') {
	       		 $Return['error'] = $this->lang->line('xin_employee_error_sch_uni');
			} else if($this->input->post('from_year')==='') {
				$Return['error'] = $this->lang->line('xin_employee_error_frm_date');
			} else if($this->input->post('to_year')==='') {
				 $Return['error'] = $this->lang->line('xin_employee_error_to_date');
			} else if($st_date > $ed_date) {
				$Return['error'] = $this->lang->line('xin_employee_error_date_shouldbe');
			}
				
			if($Return['error']!=''){
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
			$result = $this->Employees_model->qualification_info_update($data,$e_field_id);
			if ($result == TRUE) {
				$Return['result'] = $this->lang->line('xin_employee_error_q_info_updated');
			} else {
				$Return['error'] = $this->lang->line('xin_error_msg');
			}
			$this->output($Return);
			exit;
			}
		}

		public function dialog_qualification() {
		
			$session = $this->session->userdata('username');
			if(empty($session)){ 
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
			if(!empty($session)){ 
				$this->load->view('admin/employees/dialog_employee_details', $data);
			} else {
				redirect('admin/');
			}
		}

		public function delete_qualification() {
		
			if($this->input->post('data')=='delete_record') {
				/* Define return | here result is used to return user data and error for error message */
				$Return = array('result'=>'', 'error'=>'', 'csrf_hash'=>'');
				$Return['csrf_hash'] = $this->security->get_csrf_hash();
				$id = $this->uri->segment(4);
				$result = $this->Employees_model->delete_qualification_record($id);
				if(isset($id)) {
					$Return['result'] = $this->lang->line('xin_employee_qualification_deleted');
				} else {
					$Return['error'] = $this->lang->line('xin_error_msg');
				}
				$this->output($Return);
			}
		}

	// ===========================================================================================
	// PENGALAMAN
	// ===========================================================================================

		public function experience() {
			//set data
			$data['title'] = $this->Core_model->site_title();
			$session = $this->session->userdata('username');
			if(!empty($session)){ 
				$this->load->view("admin/employees/employee_detail", $data);
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

	        foreach($experience->result() as $r) {
				
				$from_date = $this->Core_model->set_date_format($r->from_date);
				$to_date = $this->Core_model->set_date_format($r->to_date);
				
			
			$data[] = array(
				'<span data-toggle="tooltip" data-placement="top" title="'.$this->lang->line('xin_edit').'"><button type="button" class="btn btn-secondary btn-sm m-b-0-0 waves-effect waves-light" data-toggle="modal" data-target=".edit-modal-data" data-field_id="'. $r->work_experience_id . '" data-field_type="work_experience"><i class="fa fa-pencil-square-o"></i></button></span><span data-toggle="tooltip" data-placement="top" title="'.$this->lang->line('xin_delete').'"><button type="button" class="btn btn-danger btn-sm m-b-0-0 waves-effect waves-light delete" data-toggle="modal" data-target=".delete-modal" data-record-id="'. $r->work_experience_id . '" data-token_type="work_experience"><i class="fa fa-trash-o"></i></button></span>',
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
		// Validate and add info in database // work experience info
		public function work_experience_info() {
		
			if($this->input->post('type')=='work_experience_info') {		
			/* Define return | here result is used to return user data and error for error message */
			$Return = array('result'=>'', 'error'=>'', 'csrf_hash'=>'');
			$Return['csrf_hash'] = $this->security->get_csrf_hash();
			
			$frm_date = strtotime($this->input->post('from_date'));	
			$to_date = strtotime($this->input->post('to_date'));
			/* Server side PHP input validation */		
			if($this->input->post('company_name')==='') {
	       		 $Return['error'] = $this->lang->line('xin_employee_error_company_name');
			} else if($this->input->post('post')==='') {
				 $Return['error'] = $this->lang->line('xin_employee_error_post');
			} else if($this->input->post('from_date')==='') {
				$Return['error'] = $this->lang->line('xin_employee_error_frm_date');
			} else if($this->input->post('to_date')==='') {
				 $Return['error'] = $this->lang->line('xin_employee_error_to_date');
			} else if($frm_date > $to_date) {
				 $Return['error'] = $this->lang->line('xin_employee_error_date_shouldbe');
			} 
					
			if($Return['error']!=''){
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
		
		public function e_work_experience_info() {
		
			if($this->input->post('type')=='e_work_experience_info') {		
			/* Define return | here result is used to return user data and error for error message */
			$Return = array('result'=>'', 'error'=>'', 'csrf_hash'=>'');
			$Return['csrf_hash'] = $this->security->get_csrf_hash();
			
			$frm_date = strtotime($this->input->post('from_date'));	
			$to_date = strtotime($this->input->post('to_date'));
			/* Server side PHP input validation */		
			if($this->input->post('company_name')==='') {
	       		 $Return['error'] = $this->lang->line('xin_employee_error_company_name');
			} else if($this->input->post('from_date')==='') {
				$Return['error'] = $this->lang->line('xin_employee_error_frm_date');
			} else if($this->input->post('to_date')==='') {
				 $Return['error'] = $this->lang->line('xin_employee_error_to_date');
			} else if($frm_date > $to_date) {
				 $Return['error'] = $this->lang->line('xin_employee_error_date_shouldbe');
			} else if($this->input->post('post')==='') {
				 $Return['error'] = $this->lang->line('xin_employee_error_post');
			}
					
			if($Return['error']!=''){
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
			$result = $this->Employees_model->work_experience_info_update($data,$e_field_id);
			if ($result == TRUE) {
				$Return['result'] = $this->lang->line('xin_employee_error_w_exp_updated');
			} else {
				$Return['error'] = $this->lang->line('xin_error_msg');
			}
			$this->output($Return);
			exit;
			}
		}

		public function dialog_work_experience() {
		
			$session = $this->session->userdata('username');
			if(empty($session)){ 
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
			if(!empty($session)){ 
				$this->load->view('admin/employees/dialog_employee_details', $data);
			} else {
				redirect('admin/');
			}
		}
	
		public function delete_work_experience() {
		
			if($this->input->post('data')=='delete_record') {
				/* Define return | here result is used to return user data and error for error message */
				$Return = array('result'=>'', 'error'=>'', 'csrf_hash'=>'');
				$Return['csrf_hash'] = $this->security->get_csrf_hash();
				$id = $this->uri->segment(4);
				$result = $this->Employees_model->delete_work_experience_record($id);
				if(isset($id)) {
					$Return['result'] = $this->lang->line('xin_employee_work_deleted');
				} else {
					$Return['error'] = $this->lang->line('xin_error_msg');
				}
				$this->output($Return);
			}
		}
		
	// ===========================================================================================
	// TAMPIL
	// =========================================================================================== 		
	
		// get company > departments
		public function get_departments() 
		{

			$data['title'] = $this->Core_model->site_title();
			$id = $this->uri->segment(4);
			
			$data = array(
				'company_id' => $id
				);
			$session = $this->session->userdata('username');
			if(!empty($session)){ 
				$this->load->view("admin/employees/get_departments", $data);
			} else {
				redirect('admin/');
			}
			// Datatables Variables
			$draw = intval($this->input->get("draw"));
			$start = intval($this->input->get("start"));
			$length = intval($this->input->get("length"));
		} 
		
		// get company > locations
		public function get_company_elocations() 
		{

			$data['title'] = $this->Core_model->site_title();
			$keywords = preg_split("/[\s,]+/", $this->uri->segment(4));
			if(is_numeric($keywords[0])) {
				$id = $keywords[0];
			
				$data = array(
					'company_id' => $id
					);
				$session = $this->session->userdata('username');
				if(!empty($session)){ 
					$data = $this->security->xss_clean($data);
					$this->load->view("admin/employees/get_company_elocations", $data);
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
			if(is_numeric($keywords[0])) {
				$id = $keywords[0];
			
				$data = array(
					'company_id' => $id
					);
				$session = $this->session->userdata('username');
				if(!empty($session)){ 
					$data = $this->security->xss_clean($data);
					$this->load->view("admin/employees/get_company_office_shifts", $data);
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
			if(is_numeric($keywords[0])) {
				$id = $keywords[0];
			
				$data = array(
					'location_id' => $id
					);
				$session = $this->session->userdata('username');
				if(!empty($session)){ 
					$data = $this->security->xss_clean($data);
					$this->load->view("admin/employees/get_location_departments", $data);
				} else {
					redirect('admin/');
				}
			}
			// Datatables Variables
			$draw = intval($this->input->get("draw"));
			$start = intval($this->input->get("start"));
			$length = intval($this->input->get("length"));
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
			if(!empty($session)){ 
				$this->load->view("admin/employees/get_designations", $data);
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
			if(!empty($session)){ 
				$this->load->view("admin/employees/get_designations", $data);
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
			if(!empty($session)){ 
				$this->load->view("admin/employees/get_sub_departments", $data);
			} else {
				redirect('admin/');
			}
			// Datatables Variables
			$draw = intval($this->input->get("draw"));
			$start = intval($this->input->get("start"));
			$length = intval($this->input->get("length"));
		}
				
		// Validate and update info in database // social info
		public function profile_picture() 
		{
			if($this->input->post('type')=='profile_picture') {	

				/* Define return | here result is used to return user data and error for error message */
				$Return = array('result'=>'', 'error'=>'', 'csrf_hash'=>'');
				$Return['csrf_hash'] = $this->security->get_csrf_hash();
				
				$id = $this->input->post('user_id');
				
				/* Check if file uploaded..*/
				if($_FILES['p_file']['size'] == 0 && null ==$this->input->post('remove_profile_picture')) {
					$Return['error'] = $this->lang->line('xin_employee_select_picture');
				} else {
					if(is_uploaded_file($_FILES['p_file']['tmp_name'])) {
					//checking image type
					$allowed  =  array('png','jpg','jpeg','pdf','gif');
					$filename = $_FILES['p_file']['name'];
					$ext      = pathinfo($filename, PATHINFO_EXTENSION);
					
					if(in_array($ext,$allowed)){
						$tmp_name = $_FILES["p_file"]["tmp_name"];
						$profile = "uploads/profile/";
						$set_img = base_url()."uploads/profile/";
						// basename() may prevent filesystem traversal attacks;
						// further validation/sanitation of the filename may be appropriate
						$name = basename($_FILES["p_file"]["name"]);
						$newfilename = 'profile_'.round(microtime(true)).'.'.$ext;
						move_uploaded_file($tmp_name, $profile.$newfilename);
						$fname = $newfilename;
						
						//UPDATE Employee info in DB
						$data = array('profile_picture' => $fname);
						$result = $this->Employees_model->profile_picture($data,$id);
						if ($result == TRUE) {
							$Return['result'] = $this->lang->line('xin_employee_picture_updated');
							$Return['img'] = $set_img.$fname;
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
				
				if(null!=$this->input->post('remove_profile_picture')) {
					//UPDATE Employee info in DB
					$data = array('profile_picture' => 'no file');				
					$row = $this->Employees_model->read_employee_information($id);
					$profile = base_url()."uploads/profile/";
					$result = $this->Employees_model->profile_picture($data,$id);
					if ($result == TRUE) {
						$Return['result'] = $this->lang->line('xin_employee_picture_updated');
						if($row[0]->gender=='Male') {
							$Return['img'] = $profile.'default_male.jpg';
						} else {
							$Return['img'] = $profile.'default_female.jpg';
						}
					} else {
						$Return['error'] = $this->lang->line('xin_error_msg');
					}
					$this->output($Return);
					exit;
					
				}
					
				if($Return['error']!=''){
					$this->output($Return);
				}
			}
		}
	
		// Validate and update info in database // change password
		public function change_password() {
		
			if($this->input->post('type')=='change_password') {		
			/* Define return | here result is used to return user data and error for error message */
			$Return = array('result'=>'', 'error'=>'', 'csrf_hash'=>'');
			$Return['csrf_hash'] = $this->security->get_csrf_hash();
				
			/* Server side PHP input validation */						
			if(trim($this->input->post('old_password'))==='') {
	       		 $Return['error'] = $this->lang->line('xin_old_password_error_field');
			} else if($this->Employees_model->check_old_password($this->input->post('old_password'),$this->input->post('user_id'))!= 1) {
				 $Return['error'] = $this->lang->line('xin_old_password_does_not_match');
			} else if(trim($this->input->post('new_password'))==='') {
	       		 $Return['error'] = $this->lang->line('xin_employee_error_newpassword');
			} else if(strlen($this->input->post('new_password')) < 6) {
				$Return['error'] = $this->lang->line('xin_employee_error_password_least');
			} else if(trim($this->input->post('new_password_confirm'))==='') {
				 $Return['error'] = $this->lang->line('xin_employee_error_new_cpassword');
			} else if($this->input->post('new_password')!=$this->input->post('new_password_confirm')) {
				 $Return['error'] = $this->lang->line('xin_employee_error_old_new_cpassword');
			}
					
			if($Return['error']!=''){
	       		$this->output($Return);
	    	}
			$options = array('cost' => 12);
			$password_hash = password_hash($this->input->post('new_password'), PASSWORD_BCRYPT, $options);
		
			$data = array(
			'password' => $password_hash
			);
			$id = $this->input->post('user_id');
			$result = $this->Employees_model->change_password($data,$id);
			if ($result == TRUE) {
				$Return['result'] = $this->lang->line('xin_employee_password_update');
			} else {
				$Return['error'] = $this->lang->line('xin_error_msg');
			}
			$this->output($Return);
			exit;
			}
		}
		
	 
	
    	
    // ====================================================================================
	// CUTI
	// ==================================================================================== 
	
	
	// delete employee record
	
	

	// get company > locations
	public function filter_company_flocations() {

		$data['title'] = $this->Core_model->site_title();
		$keywords = preg_split("/[\s,]+/", $this->uri->segment(4));
		if(is_numeric($keywords[0])) {
			$id = $keywords[0];
		
			$data = array(
				'company_id' => $id
				);
			$session = $this->session->userdata('username');
			if(!empty($session)){ 
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
	public function filter_location_fdepartments() {

		$data['title'] = $this->Core_model->site_title();
		$keywords = preg_split("/[\s,]+/", $this->uri->segment(4));
		if(is_numeric($keywords[0])) {
			$id = $keywords[0];
		
			$data = array(
				'location_id' => $id
				);
			$session = $this->session->userdata('username');
			if(!empty($session)){ 
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
	
	public function filter_location_fdesignation() {

		$data['title'] = $this->Core_model->site_title();
		$id = $this->uri->segment(4);
		
		$data = array(
			'department_id' => $id,
			'all_designations' => $this->Designation_model->all_designations(),
			);
		$session = $this->session->userdata('username');
		if(!empty($session)){ 
			$this->load->view("admin/filter/filter_location_fdesignation", $data);
		} else {
			redirect('admin/');
		}
		// Datatables Variables
		$draw = intval($this->input->get("draw"));
		$start = intval($this->input->get("start"));
		$length = intval($this->input->get("length"));
	}
	 

}
