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

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Legal extends MY_Controller {
	
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
		$this->load->model("Company_model");

		$this->load->library("pagination");
		$this->load->library('Pdf');
		$this->load->helper('string');
	}
	
	/*Function to set JSON output*/
	public function output($Return=array()){
		/*Set response header*/
		header("Access-Control-Allow-Origin: *");
		header("Content-Type: application/json; charset=UTF-8");
		/*Final JSON response*/
		exit(json_encode($Return));
	}
	
	public function index()
    {
		
    }

    // employees directory/hr
	public function employees_contract() 
	{
		
		$session = $this->session->userdata('username');
		if(empty($session)){ 
			redirect('admin/');
		}
		$role_resources_ids           = $this->Core_model->user_role_resource();
		$data['title']                = 'Kontrak Karyawan | '.$this->Core_model->site_title();
		$data['icon']                 = '<i class="fa fa-files-o"></i> ';
		$data['breadcrumbs']          = 'Kontrak Karyawan';
		$data['path_url']             = 'employees_contract';


		$data['all_departments']      = $this->Department_model->all_departments();
		$data['all_designations']     = $this->Designation_model->all_designations();
		$data['all_user_roles']       = $this->Roles_model->all_user_roles();
		$data['all_office_shifts']    = $this->Employees_model->all_office_shifts();
		$data['get_all_companies']    = $this->Company_model->get_company();
		$data['all_leave_types']      = $this->Timesheet_model->all_leave_types();
		
		
		$data['last_five_kontrak_belum_dibuat']  = $this->Core_model->last_five_kontrak_belum_dibuat();
		$data['last_five_kontrak_belum_berakhir']  = $this->Core_model->last_five_kontrak_belum_berakhir();
		$data['last_five_kontrak_akan_berakhir']  = $this->Core_model->last_five_kontrak_akan_berakhir();
		$data['last_five_kontrak_sudah_berakhir']  = $this->Core_model->last_five_kontrak_sudah_berakhir();
		
		// reports to 
 		$reports_to = get_reports_team_data($session['user_id']);
		if(in_array('0410',$role_resources_ids) ) {
			if(!empty($session)){ 
				$data['subview'] = $this->load->view("admin/legal/employees_contract", $data, TRUE);
				$this->load->view('admin/layout/layout_main', $data); //page load
			} else {
				redirect('admin/');
			}
		} else {
			redirect('admin/dashboard');
		}
    }
	
	public function employees_contract_list()
    {

		$data['title'] = $this->Core_model->site_title();
		$session = $this->session->userdata('username');
		if(!empty($session)){ 
			$this->load->view("admin/legal/employees_contract", $data);
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
		
		if($this->input->get("ihr")=='true'){
			
			if($this->input->get("company_id")==0 && $this->input->get("location_id")==0 && $this->input->get("department_id")==0 && $this->input->get("designation_id")==0){
				$employee = $this->Employees_model->get_employees_active();
				
			} else if($this->input->get("company_id")!=0 && $this->input->get("location_id")==0 && $this->input->get("department_id")==0 && $this->input->get("designation_id")==0){
				$employee = $this->Employees_model->get_company_employees_flt($this->input->get("company_id"));
			
			} else if($this->input->get("company_id")!=0 && $this->input->get("location_id")!=0 && $this->input->get("department_id")==0 && $this->input->get("designation_id")==0){
				$employee = $this->Employees_model->get_company_location_employees_flt($this->input->get("company_id"),$this->input->get("location_id"));
				
			} else if($this->input->get("company_id")!=0 && $this->input->get("location_id")!=0 && $this->input->get("department_id")!=0 && $this->input->get("designation_id")==0){
				$employee = $this->Employees_model->get_company_location_department_employees_flt($this->input->get("company_id"),$this->input->get("location_id"),$this->input->get("department_id"));
				
			} else if($this->input->get("company_id")!=0 && $this->input->get("location_id")!=0 && $this->input->get("department_id")!=0 && $this->input->get("designation_id")!=0){
				$employee = $this->Employees_model->get_company_location_department_designation_employees_flt($this->input->get("company_id"),$this->input->get("location_id"),$this->input->get("department_id"),$this->input->get("designation_id"));
			}
		} else {

			// if($user_info[0]->user_role_id==1) {
			// if($user_info[0]->user_role_id==1 || $user_info[0]->user_role_id==4 ||$user_info[0]->user_role_id==5 || $user_info[0]->user_role_id==6){
			
				$employee = $this->Employees_model->get_employees_active_kontrak();
			
		}
		
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

				if ($r->emp_status =='Tetap') {
						
					$emp_status_name ='<span class="badge bg-green"> Tetap </span>';
				
				} else if ($r->emp_status =='Kontrak'){

					$emp_status_name ='<span class="badge bg-blue"> Kontrak </span>';

				} else if ($r->emp_status =='Percobaan'){

					$emp_status_name ='<span class="badge bg-yellow"> Percobaan </span>';
				
				} else {
					$emp_status_name ='<span class="badge bg-yellow"> Belum Ada </span>';
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
	
			
			if(in_array('202',$role_resources_ids)) {
				$kontrak_opt = ' <span data-toggle="tooltip" data-placement="top" title="Kontrak Karyawan">
									<a target="_blank" href="'.site_url().'admin/legal/kontrak/'.$r->user_id.'">
										<button type="button" class="btn icon-btn btn-xs btn-default waves-effect waves-light">
											<span class="fa fa-file"></span> Kontrak
									</button></a></span>';
			} else {
				$kontrak_opt = '';
			}
						
			$function = $kontrak_opt;

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
	
			
			$employee_foto = $ol;
			
			$employee_date = date("d-m-Y", strtotime($r->date_of_joining)).'<br><span class="badge bg-green"> Aktif </span>';
			
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
				$emp_status,
				$emp_status_name,
				
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

    public function employees_all()
    {
		$session = $this->session->userdata('username');
		if(empty($session)){ 
			redirect('admin/');
		}
		$role_resources_ids           = $this->Core_model->user_role_resource();
		$data['title']                = 'Kontrak Karyawan | '.$this->Core_model->site_title();
		$data['icon']                 = '<i class="fa fa-files-o"></i>';
		$data['breadcrumbs']          = 'Kontrak Karyawan';
		$data['path_url']             = 'employees_all';

		$data['all_departments']      = $this->Department_model->all_departments();
		$data['all_designations']     = $this->Designation_model->all_designations();
		$data['all_user_roles']       = $this->Roles_model->all_user_roles();
		$data['all_office_shifts']    = $this->Employees_model->all_office_shifts();
		$data['get_all_companies']    = $this->Company_model->get_company();
		$data['all_leave_types']      = $this->Timesheet_model->all_leave_types();	
		
		// reports to 
 		$reports_to = get_reports_team_data($session['user_id']);
		if(in_array('0410',$role_resources_ids) || $reports_to > 0) {
			if(!empty($session)){ 
				$data['subview'] = $this->load->view("admin/legal/employees_all", $data, TRUE);
				$this->load->view('admin/layout/layout_main', $data); //page load
			} else {
				redirect('admin/');
			}
		} else {
			redirect('admin/dashboard');
		}
    }
	
	public function employees_all_list()
    {

		$data['title'] = $this->Core_model->site_title();
		$session = $this->session->userdata('username');
		if(!empty($session)){ 
			$this->load->view("admin/legal/employees_all", $data);
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
		
		$employee = $this->Employees_model->get_employees_all();
		
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

			if ($r->kontrak_from_date == '0000-00-00'){
				$mulai = '-';
			}  else {
				$mulai = date("d-m-Y", strtotime($r->kontrak_from_date));
			}

			if ($r->kontrak_end_date == '0000-00-00'){
				$sampai = '-';
			}  else {
				$sampai = date("d-m-Y", strtotime($r->kontrak_end_date));
			}

			if ($r->kontrak_no == ''){
				$emp_status_name = '-';
			} else {
				$emp_status_name = 'No : '.$r->kontrak_no.'<br><small class="text-muted">'.$mulai.' s/d '.$sampai.'</small><br><small class="text-muted">'.$r->kontrak_status.'</small>';
			}

			$contract_ada = $this->Employees_model->read_contract_status_ada_information($r->user_id);
				if(!is_null($contract_ada)){
					$status_ada = $contract_ada[0]->employee_id;
				} else {
					$status_ada = '';
				}
			
			if ($r->kontrak_no == ''){

				if ($r->is_active == 0 ){
				
					$status ='<span class="badge bg-black"> Diakhiri </span>';
				
				} else if ($status_ada != '') {
					$status ='<span class="badge bg-yellow blink blink-one"> Belum Aktivasi </span>';
				
				} else {

					if ($r->emp_status =='Tetap') {
						
						$status ='<span class="badge bg-green"> Tetap </span>';
					
					} else if ($r->emp_status =='Kontrak'){

						$status ='<span class="badge bg-blue"> Kontrak </span>';

					} else if ($r->emp_status =='Percobaan'){

						$status ='<span class="badge bg-yellow"> Percobaan </span>';
					
					} else {
						$status ='<span class="badge bg-yellow"> Belum Ada </span>';
					}					
				}
			
			} else {

				if ($r->is_active == 0 ){

					$status ='<span class="badge bg-black"> Diakhiri </span>';
					
				} else {

					$contract_status = $this->Employees_model->read_contract_status_information($r->kontrak_id);
					if(!is_null($contract_status)){
						$cstatus = $contract_status[0]->notif;
					} else {
						$cstatus = '?';
					}

					date_default_timezone_set("Asia/Jakarta");      
					$now_date  = date("Y-m-d");

					if ($r->kontrak_end_date < $now_date){
						
						$status ='<span class="badge bg-red"> Berakhir </span>';

					} else if ($cstatus > $now_date){

						$status ='<span class="badge bg-green"> Berlangsung </span>';

					} else {
						$status ='<span class="badge bg-yellow"> Akan Berakhir </span>';
					}

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
			
			
			if(in_array('0411',$role_resources_ids)) {
				$kontrak_opt = ' <span data-toggle="tooltip" data-placement="top" title="Kontrak Karyawan">
									<a target="_blank" href="'.site_url().'admin/legal/kontrak/'.$r->user_id.'">
										<button type="button" class="btn icon-btn btn-xs btn-info waves-effect waves-light">
											<span class="fa fa-info-circle"></span> Kontrak
									</button></a></span>';
			} else {
				$kontrak_opt = '';
			}

			$function =$kontrak_opt;

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
	
			
			$employee_foto = $ol;

			// get company
			
			if($r->date_of_joining == '0000-00-00' || $r->date_of_joining == ''){
				$employee_join = '';
			} else {
				$employee_join = date("d-m-Y", strtotime($r->date_of_joining)).'<br> <span class="badge bg-green"> Masuk </span>';
			}

			if($r->date_of_leaving == '0000-00-00' || $r->date_of_leaving == ''){
				$employee_leave = 'Masih Bekerja';
			} else {
				$employee_leave = date("d-m-Y", strtotime($r->date_of_leaving)).'<br> <span class="badge bg-red"> Keluar </span>';
			}
			
			
			
			
			$employee_name = strtoupper($full_name).'<br><small class="text-muted ">'.$this->lang->line('xin_employees_id').': '.$r->employee_id.'</small><br><small class="text-muted ">PIN : '.strtoupper($emp_pin).'</small>';
			
			$comp_name     = $comp_name.'<br><small class="text-muted"> '.$location_name.'</small><br><small class="text-muted"> '.$department_name.'</small>';
			
			$posisi_name   = strtoupper($designation_name).'<br><small class="text-muted"> '.$shift_id.' <br> '.$shift.' </small>';
		
			$contact_info  = '<i class="fa fa-envelope" data-toggle="tooltip" data-placement="top" title="'.$this->lang->line('dashboard_email').'"></i> '.$r->email.'<br><i class="fa fa-phone" data-toggle="tooltip" data-placement="top" title="'.$this->lang->line('xin_contact_number').'"></i> '.$r->contact_no;
			
			$rolemp_status = '<span class="'.$jenis_gaji_warna.'">'.$jenis_gaji.'</sapan>';

			$grade_status  = '<span class="'.$jenis_grade_warna.'">'.$jenis_grade.'</sapan>';
			
			$data[]        = array(
				$function,
				$employee_foto,
				$employee_join,
				$employee_leave,
				$employee_name,
				$comp_name,
				$posisi_name,				
				$emp_status_name,
				$status,
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

    // ====================================================================================
	// KONTRAK
	// ==================================================================================== 
		public function kontrak() 
		{

			$session = $this->session->userdata('username');

			if(empty($session)){ 
				redirect('admin/');
			}
			
			$id = $this->uri->segment(4);
			
			$result = $this->Employees_model->read_employee_information($id);
			
			if(is_null($result)){
				redirect('admin/legal');
			}

			$role_resources_ids = $this->Core_model->user_role_resource();
			
			$check_role = $this->Employees_model->read_employee_information($session['user_id']);
			
			if(!in_array('0410',$role_resources_ids)) {
				redirect('admin/legal');
			}
				

			$data = array(

				'breadcrumbs'          => 'Detil Kontrak',
				'title'                => 'Detil Kontrak | '.$this->Core_model->site_title(),
				'path_url'             => 'employees_detail_contract',			
				'first_name'           => $result[0]->first_name,
				'last_name'            => $result[0]->last_name,			
				'user_id'              => $result[0]->user_id,		
				'company_id' 		   => $result[0]->company_id,	
				'designation_id'       => $result[0]->designation_id,
				'all_departments'      => $this->Department_model->all_departments(),
				'all_designations'     => $this->Designation_model->all_designations(),
				'all_contract_types'   => $this->Employees_model->all_contract_types(),	
				'all_contract_durasi'  => $this->Employees_model->all_contract_durasi(),
				'all_contracts'        => $this->Employees_model->all_contracts(),
				'all_companies'        => $this->Company_model->get_company(),			
				'get_all_companies'    => $this->Company_model->get_company(),
				'all_office_locations' => $this->Location_model->all_office_locations()
				
			);
			
			$data['subview'] = $this->load->view("admin/legal/employee_kontrak", $data, TRUE);
			$this->load->view('admin/layout/layout_main', $data); //page load
			
			// Datatables Variables
			$draw   = intval($this->input->get("draw"));
			$start  = intval($this->input->get("start"));
			$length = intval($this->input->get("length"));
		}
	
    	public function contract_list() 
		{
				//set data
				$data['title'] = $this->Core_model->site_title();
				$session = $this->session->userdata('username');
				if(!empty($session)){ 
					$this->load->view("admin/legal/employee_detail", $data);
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

		        foreach($contract->result() as $r) {			
					// designation
					$designation = $this->Designation_model->read_designation_information($r->designation_id);
					if(!is_null($designation)){
						$designation_name = $designation[0]->designation_name;
					} else {
						$designation_name = '?';
					}
					//company name
					$company_name = $this->Core_model->read_company_info($r->company_id);
					if(!is_null($company_name)){
						$company_nm = $company_name[0]->name;
					} else {
						$company_nm = '?';
					}
					//contract type
					$contract_type = $this->Employees_model->read_contract_type_information($r->contract_type_id);
					if(!is_null($contract_type)){
						$ctype = $contract_type[0]->name;
					} else {
						$ctype = '?';
					}

					//contract durasi
					$contract_durasi = $this->Employees_model->read_contract_durasi_information($r->contract_durasi_id);
					if(!is_null($contract_durasi)){
						$cdurasi = $contract_durasi[0]->name;
					} else {
						$cdurasi = '?';
					}
					// date
					$duration = $this->Core_model->set_date_format($r->from_date).' '.$this->lang->line('dashboard_to').' '.$this->Core_model->set_date_format($r->to_date);
					
					

					$contract_status = $this->Employees_model->read_contract_status_information($r->contract_id);
					if(!is_null($contract_status)){
						$cstatus = $contract_status[0]->notif;
					} else {
						$cstatus = '?';
					}

					date_default_timezone_set("Asia/Jakarta");      
	    			$now_date  = date("Y-m-d");

					if ($r->to_date < $now_date){
						
						$status ='Berakhir';

					} else if ($cstatus > $now_date){

						$status ='Berlangsung';

					} else {
						$status ='Akan Berakhir';
					}

				$tampilkan = $this->Employees_model->read_contract_emp_information($r->contract_id );
				if(!is_null($tampilkan)){
					$kontrak = $tampilkan[0]->kontrak_id;
					
				} else {
					$kontrak = '';					

				}

				if ($kontrak == $r->contract_id){
					$info_kontrak = '<i class="fa fa-check-circle hijau"></i> '.$r->title.'(<span class="blink blink_one hijau">aktif</span>)';
				} else {
					$info_kontrak = '<i class="fa fa-time-circle merah"></i> '.$r->title.'(<span class="blink blink_one merah">tidak aktif</span>)';
				}

				$data[] = array(
					'<span data-toggle="tooltip" data-placement="top" title="Edit & Aktifkan">
							<button type="button" class="btn btn-primary btn-sm m-b-0-0 waves-effect waves-light" data-toggle="modal" data-target=".edit-modal-data" data-field_id="'. $r->contract_id . '" data-field_type="contract">
								<i class="fa fa-gavel"></i> Aktifkan
							</button>
					</span>
					<span data-toggle="tooltip" data-placement="top" title="'.$this->lang->line('xin_delete').'">
							<button type="button" class="btn btn-danger btn-sm m-b-0-0 waves-effect waves-light delete" data-toggle="modal" data-target=".delete-modal" data-record-id="'. $r->contract_id . '" data-token_type="contract">
								<i class="fa fa-trash-o"></i>
							</button>
					</span>',
					
					$info_kontrak,
					date("d-m-Y", strtotime($r->from_date)),
					date("d-m-Y", strtotime($cstatus)),
					date("d-m-Y", strtotime($r->to_date)),
					$cdurasi,
					$company_nm.'<br>'.$designation_name,
					
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
		
			if($this->input->post('type')=='contract_info') {		
			
				/* Define return | here result is used to return user data and error for error message */
				$Return = array('result'=>'', 'error'=>'', 'csrf_hash'=>'');
				$Return['csrf_hash'] = $this->security->get_csrf_hash();
				
				$frm_date = strtotime($this->input->post('from_date'));	
				$to_date = strtotime($this->input->post('to_date'));
				/* Server side PHP input validation */		
				
				if($this->input->post('company_id')==='') {
		       		 $Return['error'] = $this->lang->line('xin_employee_error_contract_company');
				
				} else if($this->input->post('contract_type_id')==='') {
		       		 $Return['error'] = $this->lang->line('xin_employee_error_contract_type');

		       	} else if($this->input->post('contract_durasi_id')==='') {
		       		 $Return['error'] = $this->lang->line('xin_employee_error_contract_durasi');
				
				} else if($this->input->post('title')==='') {
		       		 $Return['error'] = $this->lang->line('xin_employee_error_contract_title');
				
				} else if($this->input->post('from_date')==='') {
					$Return['error'] = $this->lang->line('xin_employee_error_frm_date');
				
				} else if($frm_date > $to_date) {
					 $Return['error'] = $this->lang->line('xin_employee_error_frm_to_date');
				
				} else if($this->input->post('designation_id')==='') {
					 $Return['error'] = $this->lang->line('xin_employee_error_designation');
				}

				if ($this->input->post('contract_type_id')==='1') {
					if($this->input->post('to_date')==='') {
						$Return['error'] = $this->lang->line('xin_employee_error_to_date');
					}
				} 
						
				if($Return['error']!=''){
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
		
			if($this->input->post('type')=='e_contract_info') {		
			/* Define return | here result is used to return user data and error for error message */
			$Return = array('result'=>'', 'error'=>'', 'csrf_hash'=>'');
			$Return['csrf_hash'] = $this->security->get_csrf_hash();
			
			$frm_date = strtotime($this->input->post('from_date'));	
			$to_date = strtotime($this->input->post('to_date'));
			
			/* Server side PHP input validation */		
			if($this->input->post('company_id')==='') {
	       		 $Return['error'] = $this->lang->line('xin_employee_error_contract_company');
			
			} else if($this->input->post('contract_type_id')==='') {
	       		 $Return['error'] = $this->lang->line('xin_employee_error_contract_type');
	       	
	       	} else if($this->input->post('contract_durasi_id')==='') {
	       		 $Return['error'] = $this->lang->line('xin_employee_error_contract_durasi');
			
			} else if($this->input->post('title')==='') {
	       		 $Return['error'] = $this->lang->line('xin_employee_error_contract_title');
			
			} else if($this->input->post('from_date')==='') {
				$Return['error'] = $this->lang->line('xin_employee_error_frm_date');
			
			} else if($frm_date > $to_date) {
				 $Return['error'] = $this->lang->line('xin_employee_error_frm_to_date');
			
			} else if($this->input->post('designation_id')==='') {
				 $Return['error'] = $this->lang->line('xin_employee_error_designation');
			}
					
			if ($this->input->post('contract_type_id')==='1') {
				if($this->input->post('to_date')==='') {
					$Return['error'] = $this->lang->line('xin_employee_error_to_date');
				}
			} 

			if($Return['error']!=''){
	       		$this->output($Return);
	    	}
			
			// $datetime1 = new DateTime($this->input->post('frm_date'));
			// $datetime2 = new DateTime($this->input->post('to_date'));
			// $interval = $datetime1->diff($datetime2);
	    	$e_field_id = $this->input->post('e_field_id');

	    	$tampilkan = $this->Employees_model->read_contract_information($e_field_id);
				if(!is_null($tampilkan)){
					$employee_id = $tampilkan[0]->employee_id;
					
				} else {
					$employee_id = '0';					

				}

				$contract_type = $this->Employees_model->read_contract_type_information($this->input->post('contract_type_id'));
				if(!is_null($contract_type)){
					$ctype = $contract_type[0]->name;
				} else {
					$ctype = '?';
				}

				$user_data = array(
					'company_id'         => $this->input->post('company_id'),
					'kontrak_id'        => $e_field_id,
					'kontrak_from_date' => $this->input->post('from_date'),
					'kontrak_end_date'  => $this->input->post('to_date'),
					'kontrak_status'    => $ctype,
					'kontrak_no'        => $this->input->post('title'),
					'kontrak_update'    => date('Y-m-d H:i:s')
					
				);
				$user_info = $this->Employees_model->basic_info($user_data, $employee_id);

				// echo "<pre>";
				// print_r($this->db->last_query());
				// echo "</pre>";
				// die();

			$data = array(
				'company_id'         => $this->input->post('company_id'),
				'contract_type_id'   => $this->input->post('contract_type_id'),
				'contract_durasi_id' => $this->input->post('contract_durasi_id'),
				'title'              => $this->input->post('title'),
				'from_date'          => $this->input->post('from_date'),
				'to_date'            => $this->input->post('to_date'),				
				'designation_id'     => $this->input->post('designation_id'),
				'description'        => $this->input->post('description')
			);
			
			
			$result = $this->Employees_model->contract_info_update($data,$e_field_id);
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
			
			if($this->input->post('data')=='delete_record') {
				/* Define return | here result is used to return user data and error for error message */
				$Return = array('result'=>'', 'error'=>'', 'csrf_hash'=>'');
				$Return['csrf_hash'] = $this->security->get_csrf_hash();
				$id = $this->uri->segment(4);
				$result = $this->Employees_model->delete_contract_record($id);
				if(isset($id)) {
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
			if(empty($session)){ 
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
			if(!empty($session)){ 
				$this->load->view('admin/legal/dialog_employee_details', $data);
			} else {
				redirect('admin/');
			}
		}

	    public function detail() 
		{

			$session = $this->session->userdata('username');

			if(empty($session)){ 
				redirect('admin/');
			}
			
			$id = $this->uri->segment(4);
			
			$result = $this->Employees_model->read_employee_information($id);
			
			if(is_null($result)){
				redirect('admin/legal');
			}

			$role_resources_ids = $this->Core_model->user_role_resource();
			
			$check_role = $this->Employees_model->read_employee_information($session['user_id']);
			
			if(!in_array('202',$role_resources_ids)) {
				redirect('admin/legal');
			}
				

			$data = array(
				'breadcrumbs' => $this->lang->line('xin_employee_detail'),
				'path_url' => 'employees_detail',
				'first_name' => $result[0]->first_name,
				'last_name' => $result[0]->last_name,
				'office_shift_id' => $result[0]->office_shift_id,
				'user_id' => $result[0]->user_id,
				'employee_id' => $result[0]->employee_id,
				'employee_pin' => $result[0]->employee_pin,
				'employee_ktp' => $result[0]->employee_ktp,
				'company_id' => $result[0]->company_id,
				'emp_status'=> $result[0]->emp_status,
				'location_id' => $result[0]->location_id,
				
				'ereports_to' => $result[0]->reports_to,
				// 'username' => $result[0]->username,
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
				'wages_type' => $result[0]->wages_type,
				'grade_type' => $result[0]->grade_type,
				'basic_salary' => $result[0]->basic_salary,
				// 'is_active' => $result[0]->is_active,
				'date_of_joining' => $result[0]->date_of_joining,
				'all_departments' => $this->Department_model->all_departments(),
				'all_designations' => $this->Designation_model->all_designations(),
				'all_user_roles' => $this->Roles_model->all_user_roles(),
				'title' => $this->lang->line('xin_employee_detail').' | '.$this->Core_model->site_title(),
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
			
			$data['subview'] = $this->load->view("admin/legal/employee_detail", $data, TRUE);
			$this->load->view('admin/layout/layout_main', $data); //page load
			
			// Datatables Variables
			$draw = intval($this->input->get("draw"));
			$start = intval($this->input->get("start"));
			$length = intval($this->input->get("length"));
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
			if(!empty($session)){ 
				$this->load->view("admin/legal/get_departments", $data);
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
				$this->load->view('admin/legal/dialog_employee_details', $data);
			} else {
				redirect('admin/');
			}
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
					$this->load->view("admin/legal/get_company_elocations", $data);
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
					$this->load->view("admin/legal/get_company_office_shifts", $data);
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
		public function designation() {

			$data['title'] = $this->Core_model->site_title();
			$id = $this->uri->segment(4);
			
			$data = array(
				'subdepartment_id' => $id,
				'all_designations' => $this->Designation_model->all_designations(),
				);
			$session = $this->session->userdata('username');
			if(!empty($session)){ 
				$this->load->view("admin/legal/get_designations", $data);
			} else {
				redirect('admin/');
			}
			// Datatables Variables
			$draw = intval($this->input->get("draw"));
			$start = intval($this->input->get("start"));
			$length = intval($this->input->get("length"));
		}
		 
		public function is_designation() {

			$data['title'] = $this->Core_model->site_title();
			$id = $this->uri->segment(4);
			
			$data = array(
				'department_id' => $id,
				'all_designations' => $this->Designation_model->all_designations(),
				);
			$session = $this->session->userdata('username');
			if(!empty($session)){ 
				$this->load->view("admin/legal/get_designations", $data);
			} else {
				redirect('admin/');
			}
			// Datatables Variables
			$draw = intval($this->input->get("draw"));
			$start = intval($this->input->get("start"));
			$length = intval($this->input->get("length"));
		}
		
		// get main department > sub departments
		public function get_sub_departments() {

			$data['title'] = $this->Core_model->site_title();
			$id = $this->uri->segment(4);
			
			$data = array(
				'department_id' => $id
				);
			$session = $this->session->userdata('username');
			if(!empty($session)){ 
				$this->load->view("admin/legal/get_sub_departments", $data);
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
			if(empty($session)){ 
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
			if(!empty($session)){ 
				$this->load->view('admin/warning/dialog_warning', $data);
			} else {
				redirect('admin/');
			}
		}
	

    // ====================================================================================
	// CUTI
	// ==================================================================================== 
	// employee leave - listing
	public function leave() {
		//set data
		$data['title'] = $this->Core_model->site_title();
		$session = $this->session->userdata('username');
		if(!empty($session)){ 
			$this->load->view("admin/legal/employee_detail", $data);
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

        foreach($leave->result() as $r) {			
			
			
			
			// contract
			$contract = $this->Employees_model->read_contract_information($r->contract_id);
			if(!is_null($contract)){
				// contract duration
			$duration = $this->Core_model->set_date_format($contract[0]->from_date).' '.$this->lang->line('dashboard_to').' '.$this->Core_model->set_date_format($contract[0]->to_date);
				$ctitle = $contract[0]->title.' '.$duration;
			} else {
				$ctitle = '?';
			}
			
			$contracti = $ctitle;
		
		$data[] = array(
			'<span data-toggle="tooltip" data-placement="top" title="'.$this->lang->line('xin_edit').'"><button type="button" class="btn btn-secondary btn-sm m-b-0-0 waves-effect waves-light" data-toggle="modal" data-target=".edit-modal-data" data-field_id="'. $r->leave_id . '" data-field_type="leave"><i class="fa fa-pencil-square-o"></i></button></span><span data-toggle="tooltip" data-placement="top" title="'.$this->lang->line('xin_delete').'"><button type="button" class="btn btn-danger btn-sm m-b-0-0 waves-effect waves-light delete" data-toggle="modal" data-target=".delete-modal" data-record-id="'. $r->leave_id . '" data-token_type="leave"><i class="fa fa-trash-o"></i></button></span>',
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
	public function shift() {
		//set data
		$data['title'] = $this->Core_model->site_title();
		$session = $this->session->userdata('username');
		if(!empty($session)){ 
			$this->load->view("admin/legal/employee_detail", $data);
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

        foreach($shift->result() as $r) {			
			// contract
			$shift_info = $this->Employees_model->read_shift_information($r->shift_id);
			// contract duration
			$duration = $this->Core_model->set_date_format($r->from_date).' '.$this->lang->line('dashboard_to').' '.$this->Core_model->set_date_format($r->to_date);
			
			if(!is_null($shift_info)){
				$shift_name = $shift_info[0]->shift_name;
			} else {
				$shift_name = '?';
			}
		
		$data[] = array(
			'<span data-toggle="tooltip" data-placement="top" title="'.$this->lang->line('xin_edit').'"><button type="button" class="btn btn-secondary btn-sm m-b-0-0 waves-effect waves-light" data-toggle="modal" data-target=".edit-modal-data" data-field_id="'. $r->emp_shift_id . '" data-field_type="shift"><i class="fa fa-pencil-square-o"></i></button></span><span data-toggle="tooltip" data-placement="top" title="'.$this->lang->line('xin_delete').'"><button type="button" class="btn btn-danger btn-sm m-b-0-0 waves-effect waves-light delete" data-toggle="modal" data-target=".delete-modal" data-record-id="'. $r->emp_shift_id . '" data-token_type="shift"><i class="fa fa-trash-o"></i></button></span>',
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
	public function location() {
		//set data
		$data['title'] = $this->Core_model->site_title();
		$session = $this->session->userdata('username');
		if(!empty($session)){ 
			$this->load->view("admin/legal/employee_detail", $data);
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

        foreach($location->result() as $r) {			
			// contract
			$of_location = $this->Location_model->read_location_information($r->location_id);
			// contract duration
			$duration = $this->Core_model->set_date_format($r->from_date).' '.$this->lang->line('dashboard_to').' '.$this->Core_model->set_date_format($r->to_date);
			if(!is_null($of_location)){
				$location_name = $of_location[0]->location_name;
			} else {
				$location_name = '?';
			}
		
		$data[] = array(
			'<span data-toggle="tooltip" data-placement="top" title="'.$this->lang->line('xin_edit').'"><button type="button" class="btn btn-secondary btn-sm m-b-0-0 waves-effect waves-light" data-toggle="modal" data-target=".edit-modal-data" data-field_id="'. $r->office_location_id . '" data-field_type="location"><i class="fa fa-pencil-square-o"></i></button></span><span data-toggle="tooltip" data-placement="top" title="'.$this->lang->line('xin_delete').'"><button type="button" class="btn btn-danger btn-sm m-b-0-0 waves-effect waves-light delete" data-toggle="modal" data-target=".delete-modal" data-record-id="'. $r->office_location_id . '" data-token_type="location"><i class="fa fa-trash-o"></i></button></span>',
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
	public function update() {
	
		if($this->input->post('edit_type')=='warning') {
			
		$id = $this->uri->segment(4);
		
		/* Define return | here result is used to return user data and error for error message */
		$Return = array('result'=>'', 'error'=>'', 'csrf_hash'=>'');
		$Return['csrf_hash'] = $this->security->get_csrf_hash();
			
		/* Server side PHP input validation */
		$description = $this->input->post('description');
		$qt_description = htmlspecialchars(addslashes($description), ENT_QUOTES);
		
		if($this->input->post('warning_to')==='') {
       		 $Return['error'] = $this->lang->line('xin_employee_error_warning');
		} else if($this->input->post('type')==='') {
			$Return['error'] = $this->lang->line('xin_employee_error_warning_type');
		} else if($this->input->post('subject')==='') {
			 $Return['error'] = $this->lang->line('xin_employee_error_subject');
		} else if($this->input->post('warning_by')==='') {
			 $Return['error'] = $this->lang->line('xin_employee_error_warning_by');
		} else if($this->input->post('warning_date')==='') {
			 $Return['error'] = $this->lang->line('xin_employee_error_warning_date');
		}
				
		if($Return['error']!=''){
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
		
		$result = $this->Warning_model->update_record($data,$id);		
		
		if ($result == TRUE) {
			$Return['result'] = $this->lang->line('xin_employee_warning_updated');
		} else {
			$Return['error'] = $this->lang->line('xin_error_msg');
		}
		$this->output($Return);
		exit;
		}
	}
	
	// delete document record
	public function delete_document() {
		
		if($this->input->post('data')=='delete_record') {
			/* Define return | here result is used to return user data and error for error message */
			$Return = array('result'=>'', 'error'=>'', 'csrf_hash'=>'');
			$id = $this->uri->segment(4);
			$Return['csrf_hash'] = $this->security->get_csrf_hash();
			$result = $this->Employees_model->delete_document_record($id);
			if(isset($id)) {
				$Return['result'] = $this->lang->line('xin_employee_document_deleted');
			} else {
				$Return['error'] = $this->lang->line('xin_error_msg');
			}
			$this->output($Return);
		}
	}
	
	// delete document record
	public function delete_imgdocument() {
		
		if($this->input->post('data')=='delete_record') {
			/* Define return | here result is used to return user data and error for error message */
			$Return = array('result'=>'', 'error'=>'', 'csrf_hash'=>'');
			
			$id = $this->uri->segment(4);
			$result = $this->Employees_model->delete_imgdocument_record($id);
			$Return['csrf_hash'] = $this->security->get_csrf_hash();
			if(isset($id)) {
				$Return['result'] = $this->lang->line('xin_employee_img_document_deleted');
			} else {
				$Return['error'] = $this->lang->line('xin_error_msg');
			}
			$this->output($Return);
		}
	}
	
	// delete qualification record
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
	
	// delete work_experience record
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
		
	
	
	
	// delete leave record
	public function delete_leave() {
		
		if($this->input->post('data')=='delete_record') {
			/* Define return | here result is used to return user data and error for error message */
			$Return = array('result'=>'', 'error'=>'', 'csrf_hash'=>'');
			$Return['csrf_hash'] = $this->security->get_csrf_hash();
			$id = $this->uri->segment(4);
			$result = $this->Employees_model->delete_leave_record($id);
			if(isset($id)) {
				$Return['result'] = $this->lang->line('xin_employee_leave_deleted');
			} else {
				$Return['error'] = $this->lang->line('xin_error_msg');
			}
			$this->output($Return);
		}
	}
	
	// delete shift record
	public function delete_shift() {
		
		if($this->input->post('data')=='delete_record') {
			/* Define return | here result is used to return user data and error for error message */
			$Return = array('result'=>'', 'error'=>'', 'csrf_hash'=>'');
			$Return['csrf_hash'] = $this->security->get_csrf_hash();
			$id = $this->uri->segment(4);
			$result = $this->Employees_model->delete_shift_record($id);
			if(isset($id)) {
				$Return['result'] = $this->lang->line('xin_employee_shift_deleted');
			} else {
				$Return['error'] = $this->lang->line('xin_error_msg');
			}
			$this->output($Return);
		}
	}
	
	// delete location record
	public function delete_location() {
		
		if($this->input->post('data')=='delete_record') {
			/* Define return | here result is used to return user data and error for error message */
			$Return = array('result'=>'', 'error'=>'', 'csrf_hash'=>'');
			$Return['csrf_hash'] = $this->security->get_csrf_hash();
			$id = $this->uri->segment(4);
			$result = $this->Employees_model->delete_location_record($id);
			if(isset($id)) {
				$Return['result'] = $this->lang->line('xin_employee_location_deleted');
			} else {
				$Return['error'] = $this->lang->line('xin_error_msg');
			}
			$this->output($Return);
		}
	}
	
	// delete employee record
	public function delete() {
		
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
	
	// Validate and update info in database // basic info
	


	// Validate and update info in database // basic info
	public function set_overtime() {
	
		if($this->input->post('type')=='emp_overtime') {		
		/* Define return | here result is used to return user data and error for error message */
		$Return = array('result'=>'', 'error'=>'', 'csrf_hash'=>'');
		$Return['csrf_hash'] = $this->security->get_csrf_hash();	
		if($this->input->post('overtime_type')==='') {
			$Return['error'] = $this->lang->line('xin_employee_set_overtime_title_error');
		} else if($this->input->post('no_of_days')==='') {
			$Return['error'] = $this->lang->line('xin_employee_set_overtime_no_of_days_error');
		} else if($this->input->post('overtime_hours')==='') {
			$Return['error'] = $this->lang->line('xin_employee_set_overtime_hours_error');
		} else if($this->input->post('overtime_rate')==='') {
			$Return['error'] = $this->lang->line('xin_employee_set_overtime_rate_error');
		}
		
		if($Return['error']!=''){
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
	public function update_overtime_info() {
	
		if($this->input->post('type')=='e_overtime_info') {		
		/* Define return | here result is used to return user data and error for error message */
		$Return = array('result'=>'', 'error'=>'', 'csrf_hash'=>'');
		$Return['csrf_hash'] = $this->security->get_csrf_hash();	
		if($this->input->post('overtime_type')==='') {
			$Return['error'] = $this->lang->line('xin_employee_set_overtime_title_error');
		} else if($this->input->post('no_of_days')==='') {
			$Return['error'] = $this->lang->line('xin_employee_set_overtime_no_of_days_error');
		} else if($this->input->post('overtime_hours')==='') {
			$Return['error'] = $this->lang->line('xin_employee_set_overtime_hours_error');
		} else if($this->input->post('overtime_rate')==='') {
			$Return['error'] = $this->lang->line('xin_employee_set_overtime_rate_error');
		}
		
		if($Return['error']!=''){
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
		$result = $this->Employees_model->salary_overtime_update_record($data,$id);
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
	public function delete_emp_overtime() {
		
		if($this->input->post('data')=='delete_record') {
			/* Define return | here result is used to return user data and error for error message */
			$Return = array('result'=>'', 'error'=>'', 'csrf_hash'=>'');
			$Return['csrf_hash'] = $this->security->get_csrf_hash();
			$id = $this->uri->segment(4);
			$result = $this->Employees_model->delete_overtime_record($id);
			if(isset($id)) {
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
	public function salary_overtime() {
		//set data
		$data['title'] = $this->Core_model->site_title();
		$session = $this->session->userdata('username');
		if(!empty($session)){ 
			$this->load->view("admin/legal/employee_detail", $data);
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
        foreach($overtime->result() as $r) {

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

			$cek_lembur_1 = $clock_in_m.' '.$this->lang->line('dashboard_to').' '.$clock_out_m ;
			if ($cek_lembur_1 == '' || $cek_lembur_1 == '00:00:00 s/d 00:00:00'){
				$lembur_1 = '-- Tidak Ada --';
			} else {
				$lembur_1 = $cek_lembur_1;
			}

			// get start date
			$clock_in_n    = $r->clock_in_n;
			// get end date
			$clock_out_n   = $r->clock_out_n;
			

			$cek_lembur_2 = $clock_in_n.' '.$this->lang->line('dashboard_to').' '.$clock_out_n ;
			if ($cek_lembur_2 == '' || $cek_lembur_2 == '00:00:00 s/d 00:00:00'){
				$lembur_2 = '-- Tidak Ada --';
			} else {
				$lembur_2 = $cek_lembur_2;
			}

			// total work
			// $total_time    = $r->total_menit.' Menit';

			// $total_jam    = round($total_time/60,2).' Jam';

			// overtime date
			$overtime_time = 'LP '.$lembur_1.'<br>'.
			                 'LS '.$lembur_2.'<br>
			                 <small class="text-muted">
			                 			                 <i class="fa fa-check-circle"></i> '.$ov_status.'			                 
			                 </small>';


        	// overtime date
			// $overtime_time = $clock_in_m.' '.$this->lang->line('dashboard_to').' '.$clock_out_m;
			

			// get report to
			$reports_to = $this->Core_model->read_user_info($r->reports_to);
			// user full name
			if(!is_null($reports_to)){

				// get designation
				$designation = $this->Designation_model->read_designation_information($reports_to[0]->designation_id);
				if(!is_null($designation)){
					$designation_name = $designation[0]->designation_name;
				} else {
					$designation_name = '<span class="badge bg-red"> ? </span>';	
				}

				$manager_name = $reports_to[0]->first_name.' '.$reports_to[0]->last_name .' <small>('.$designation_name.')</small>';
			} else {
				$manager_name = '?';	
			}

			// get overtime type
			$type = $this->Overtime_model->read_overtime_type_information($r->overtime_type);
			if(!is_null($type)){
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
			date("d-m-Y",strtotime($overtime_date)),
			$overtime_time,				
			$manager_name.'<br>'.$iitype,
			
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
	 
	  public function expired_documents() {
		$session = $this->session->userdata('username');
		if(empty($session)){ 
			redirect('admin/');
		}
		$data['title'] = $this->lang->line('xin_e_details_exp_documents').' | '.$this->Core_model->site_title();
		$data['breadcrumbs'] = $this->lang->line('xin_e_details_exp_documents');
		$data['path_url'] = 'employees_expired_documents';
		$role_resources_ids = $this->Core_model->user_role_resource();
		if(in_array('400',$role_resources_ids)) {
			$data['subview'] = $this->load->view("admin/legal/expired_documents_list", $data, TRUE);
			$this->load->view('admin/layout/layout_main', $data); //page load
		} else {
			redirect('admin/dashboard');
		}
     }
	 
	 // employee documents - listing
	public function expired_documents_list() {
		//set data
		$data['title'] = $this->Core_model->site_title();
		$session = $this->session->userdata('username');
		if(!empty($session)){ 
			$this->load->view("admin/legal/expired_documents_list", $data);
		} else {
			redirect('admin/');
		}
		// Datatables Variables
		$draw = intval($this->input->get("draw"));
		$start = intval($this->input->get("start"));
		$length = intval($this->input->get("length"));
		$user_info = $this->Core_model->read_user_info($session['user_id']);
		if($user_info[0]->user_role_id==1 || $user_info[0]->user_role_id==4 ||$user_info[0]->user_role_id==5 || $user_info[0]->user_role_id==6){
			$documents = $this->Employees_model->get_documents_expired_all();
		} else {
			$documents = $this->Employees_model->get_user_documents_expired_all($session['user_id']);
		}
		
		
		$data = array();

        foreach($documents->result() as $r) {
			
			$d_type = $this->Employees_model->read_document_type_information($r->document_type_id);
			if(!is_null($d_type)){
				$document_d = $d_type[0]->document_type;
			} else {
				$document_d = '?';
			}
			$date_of_expiry = $this->Core_model->set_date_format($r->date_of_expiry);
			if($r->document_file!='' && $r->document_file!='no file') {
			 $functions = '<span data-toggle="tooltip" data-placement="top" title="Download"><a href="'.site_url().'admin/download?type=document&filename='.$r->document_file.'"><button type="button" class="btn btn-secondary btn-sm m-b-0-0 waves-effect waves-light" title="'.$this->lang->line('xin_download').'"><i class="fa fa-download"></i></button></a></span>';
			 } else {
				 $functions ='';
			 }
			 //userinfo
			$xuser_info = $this->Core_model->read_user_info($r->employee_id);	
			if(!is_null($xuser_info)){
				if($user_info[0]->user_role_id==1 || $user_info[0]->user_role_id==4 ||$user_info[0]->user_role_id==5 || $user_info[0]->user_role_id==6){
					$fc_name = '<a target="_blank" href="'.site_url('admin/legal/detail/').$r->employee_id.'">'.$xuser_info[0]->first_name.' '.$xuser_info[0]->last_name.'</a>';
				} else {
					$fc_name = $xuser_info[0]->first_name.' '.$xuser_info[0]->last_name;
				}
			} else {
				$fc_name = '?';	
			}
			$data[] = array(
				$functions.'<span data-toggle="tooltip" data-placement="top" title="'.$this->lang->line('xin_edit').'"><button type="button" class="btn btn-secondary btn-sm m-b-0-0 waves-effect waves-light" data-toggle="modal" data-target=".edit-modal-data" data-field_id="'. $r->document_id . '" data-field_type="document"><i class="fa fa-pencil-square-o"></i></button></span>',
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
	public function expired_immigration_list() {
		//set data
		$data['title'] = $this->Core_model->site_title();
		$session = $this->session->userdata('username');
		if(!empty($session)){ 
			$this->load->view("admin/legal/expired_documents_list", $data);
		} else {
			redirect('admin/');
		}
		// Datatables Variables
		$draw = intval($this->input->get("draw"));
		$start = intval($this->input->get("start"));
		$length = intval($this->input->get("length"));
		
	//	$id = $this->uri->segment(4);
		$user_info = $this->Core_model->read_user_info($session['user_id']);
		if($user_info[0]->user_role_id==1 || $user_info[0]->user_role_id==4 ||$user_info[0]->user_role_id==5 || $user_info[0]->user_role_id==6){
			$immigration = $this->Employees_model->get_img_documents_expired_all();
		} else {
			$immigration = $this->Employees_model->get_user_img_documents_expired_all($session['user_id']);
		}
		
		
		$data = array();

        foreach($immigration->result() as $r) {
			
		$issue_date = $this->Core_model->set_date_format($r->issue_date);
		$expiry_date = $this->Core_model->set_date_format($r->expiry_date);
		$eligible_review_date = $this->Core_model->set_date_format($r->eligible_review_date);
		$d_type = $this->Employees_model->read_document_type_information($r->document_type_id);
		if(!is_null($d_type)){
			$document_d = $d_type[0]->document_type.'<br>'.$r->document_number;
		} else {
			$document_d = $r->document_number;
		}
		$country = $this->Core_model->read_country_info($r->country_id);
		if(!is_null($country)){
			$c_name = $country[0]->country_name;
		} else {
			$c_name = '?';	
		}
		//userinfo
		$xuser_info = $this->Core_model->read_user_info($r->employee_id);	
		if(!is_null($xuser_info)){
			if($user_info[0]->user_role_id==1 || $user_info[0]->user_role_id==4 ||$user_info[0]->user_role_id==5 || $user_info[0]->user_role_id==6){
				$fc_name = '<a target="_blank" href="'.site_url('admin/legal/detail/').$r->employee_id.'">'.$xuser_info[0]->first_name.' '.$xuser_info[0]->last_name.'</a>';
			} else {
				$fc_name = $xuser_info[0]->first_name.' '.$xuser_info[0]->last_name;
			}
		} else {
			$fc_name = '?';	
		}
		if($r->document_file!='' && $r->document_file!='no file') {
		 	$functions = '<span data-toggle="tooltip" data-placement="top" title="Download"><a href="'.site_url().'admin/download?type=document/immigration&filename='.$r->document_file.'"><button type="button" class="btn btn-secondary btn-sm m-b-0-0 waves-effect waves-light" title="'.$this->lang->line('xin_download').'"><i class="fa fa-download"></i></button></a></span>';
		 } else {
			 $functions ='';
		 }
		$data[] = array(
			$functions.'<span data-toggle="tooltip" data-placement="top" title="'.$this->lang->line('xin_edit').'"><button type="button" class="btn btn-secondary btn-sm m-b-0-0 waves-effect waves-light" data-toggle="modal" data-target=".edit-modal-data" data-field_id="'. $r->immigration_id . '" data-field_type="imgdocument"><i class="fa fa-pencil-square-o"></i></button></span>',
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
		if(!empty($session)){ 
			$this->load->view("admin/legal/expired_documents_list", $data);
		} else {
			redirect('admin/');
		}
		// Datatables Variables
		$draw = intval($this->input->get("draw"));
		$start = intval($this->input->get("start"));
		$length = intval($this->input->get("length"));
				
		$role_resources_ids = $this->Core_model->user_role_resource();
		$user_info = $this->Core_model->read_user_info($session['user_id']);
		if($user_info[0]->user_role_id==1 || $user_info[0]->user_role_id==4 ||$user_info[0]->user_role_id==5 || $user_info[0]->user_role_id==6){
			$company = $this->Employees_model->company_license_expired_all();
		} else {
			$company = $this->Employees_model->get_company_license_expired($user_info[0]->company_id);
		}
		$data = array();

          foreach($company->result() as $r) {
			  			  
			  if(in_array('247',$role_resources_ids)) { //edit
				$edit = '<span data-toggle="tooltip" data-placement="top" title="'.$this->lang->line('xin_edit').'"><button type="button" class="btn icon-btn btn-xs btn-default waves-effect waves-light"  data-toggle="modal" data-target=".edit-modal-data"  data-field_id="'. $r->document_id . '" data-field_type="company_license_expired"><i class="fa fa-pencil-square-o"></i></button></span>';
			} else {
				$edit = '';
			}
			$company_id = $this->Company_model->read_company_information($r->company_id);
			if(!is_null($company_id)){
				$company_name = $company_id[0]->name;
			} else {
				$company_name = '?';	
			}
			
			if($r->document!='' && $r->document!='no file') {
				 $doc_view = '<span data-toggle="tooltip" data-placement="top" title="'.$this->lang->line('xin_download').'"><a href="'.base_url().'admin/download?type=company/official_documents&filename='.$r->document.'"><button type="button" class="btn btn-secondary btn-sm m-b-0-0 waves-effect waves-light" title="'.$this->lang->line('xin_download').'"><i class="fa fa-download"></i></button></a></span>';
			 } else {
				 $doc_view ='';
			 }
			$combhr = $doc_view.$edit;
			$ilicense_name = $r->license_name.'<br><small class="text-muted">'.$this->lang->line('xin_hr_official_license_number').': '.$r->license_number.'</small>';
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
		if(empty($session)){ 
			redirect('admin/');
		}
		$data['title'] = $this->Core_model->site_title();
		if(!empty($session)){ 
			$this->load->view("admin/legal/expired_documents_list", $data);
		} else {
			redirect('admin/');
		}
		// Datatables Variables
		$draw = intval($this->input->get("draw"));
		$start = intval($this->input->get("start"));
		$length = intval($this->input->get("length"));
		
		$role_resources_ids = $this->Core_model->user_role_resource();
		$user_info = $this->Core_model->read_user_info($session['user_id']);
		if($user_info[0]->user_role_id==1 || $user_info[0]->user_role_id==4 ||$user_info[0]->user_role_id==5 || $user_info[0]->user_role_id==6){
			$assets = $this->Employees_model->warranty_assets_expired_all();
		} else {
			if(in_array('265',$role_resources_ids)) {
				$assets = $this->Employees_model->company_warranty_assets_expired_all($user_info[0]->company_id);
			} else {
				$assets = $this->Employees_model->user_warranty_assets_expired_all($session['user_id']);
			}
		}
		$data = array();
		
          foreach($assets->result() as $r) {						
			
			// get category
			$assets_category = $this->Assets_model->read_assets_category_info($r->assets_category_id);
			if(!is_null($assets_category)){
				$category = $assets_category[0]->category_name;
			} else {
			 	$category = '?';	
			}
			//working?
			if($r->is_working==1){
				$working = $this->lang->line('xin_yes');
			} else {
				$working = $this->lang->line('xin_no');
			}
			// get user > added by
			$user = $this->Core_model->read_user_info($r->employee_id);
			// user full name
			if(!is_null($user)){
				$full_name = $user[0]->first_name.' '.$user[0]->last_name;
			} else {
				$full_name = '?';	
			}
			
			if(in_array('263',$role_resources_ids)) { //edit
				$edit = '<span data-toggle="tooltip" data-placement="top" title="'.$this->lang->line('xin_edit').'"><button type="button" class="btn icon-btn btn-xs btn-default waves-effect waves-light" data-toggle="modal" data-target=".edit-modal-data" data-field_id="'. $r->assets_id . '" data-field_type="assets_warranty_expired"><i class="fa fa-pencil-square-o"></i></button></span>';
			} else {
				$edit = '';
			}
			
			if(in_array('265',$role_resources_ids)) { //view
				$view = '<span data-toggle="tooltip" data-placement="top" title="'.$this->lang->line('xin_view').'"><button type="button" class="btn icon-btn btn-xs btn-default waves-effect waves-light" data-toggle="modal" data-target=".view-modal-data" data-asset_id="'. $r->assets_id . '"><span class="fa fa-eye"></span></button></span>';
			} else {
				$view = '';
			}
			$combhr = $edit;
			$created_at = $this->Core_model->set_date_format($r->created_at);
			$iname = $r->name.'<br><small class="text-muted">'.$this->lang->line('xin_created_at').': '.$created_at.'</small>';					 			  				
			$data[] = array($combhr,
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
	 public function dialog_exp_document() {
		
		$session = $this->session->userdata('username');
		if(empty($session)){ 
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
		if(!empty($session)){ 
			$this->load->view('admin/legal/dialog_employee_exp_details', $data);
		} else {
			redirect('admin/');
		}
	}
	
	public function dialog_exp_imgdocument() {
		
		$session = $this->session->userdata('username');
		if(empty($session)){ 
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
		if(!empty($session)){ 
			$this->load->view('admin/legal/dialog_employee_exp_details', $data);
		} else {
			redirect('admin/');
		}
	}
	
	public function dialog_exp_company_license_expired() {
		$session = $this->session->userdata('username');
		if(empty($session)){ 
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
		$this->load->view('admin/legal/dialog_employee_exp_details', $data);
	}
	public function dialog_exp_assets_warranty_expired() {
		$session = $this->session->userdata('username');
		if(empty($session)){ 
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
		$this->load->view('admin/legal/dialog_employee_exp_details', $data);
	}
	 public function staff_dashboard()
     {
		$session = $this->session->userdata('username');
		if(empty($session)){ 
			redirect('admin/');
		}
		$data['title'] = $this->lang->line('hr_staff_dashboard_title').' | '.$this->Core_model->site_title();
		$data['all_departments'] = $this->Department_model->all_departments();
		$data['all_designations'] = $this->Designation_model->all_designations();
		$data['all_user_roles'] = $this->Roles_model->all_user_roles();
		$data['all_office_shifts'] = $this->Employees_model->all_office_shifts();
		$data['get_all_companies'] = $this->Company_model->get_company();
		$data['all_leave_types'] = $this->Timesheet_model->all_leave_types();
		$data['breadcrumbs'] = $this->lang->line('hr_staff_dashboard_title');
		$data['path_url'] = 'employees';
		$role_resources_ids = $this->Core_model->user_role_resource();
		if(in_array('422',$role_resources_ids)) {
			if(!empty($session)){ 
				$data['subview'] = $this->load->view("admin/legal/staff_dashboard", $data, TRUE);
				$this->load->view('admin/layout/layout_main', $data); //page load
			} else {
				redirect('admin/');
			}
		} else {
			redirect('admin/dashboard');
		}
     }
	public function dialog_security_level() {
		
		$session = $this->session->userdata('username');
		if(empty($session)){ 
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
		if(!empty($session)){ 
			$this->load->view('admin/legal/dialog_employee_details', $data);
		} else {
			redirect('admin/');
		}
	}
}
