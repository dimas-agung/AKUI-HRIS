<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Employees_last_login extends MY_Controller {
	
	 public function __construct() {
        parent::__construct();
		//load the model
		$this->load->model("Employees_model");
		$this->load->model("Core_model");
		$this->load->model("Designation_model");
		$this->load->model("Department_model");
		$this->load->model("Company_model");
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
		$session = $this->session->userdata('username');
		if(empty($session)){ 
			redirect('admin/');
		}
		$data['title']       = 'Info Login | '.$this->Core_model->site_title();
		$data['icon']        = '<i class="fa fa-key"></i>';
		$data['breadcrumbs'] = 'Info Login';
		$data['path_url']    = 'employees_last_login';
		
		$data['get_all_companies'] = $this->Company_model->get_company();
		
		$role_resources_ids = $this->Core_model->user_role_resource();
		// reports to 
 		$reports_to = get_reports_team_data($session['user_id']);
		if(in_array('0540',$role_resources_ids) || $reports_to > 0) {
			if(!empty($session)){ 
				$data['subview'] = $this->load->view("admin/last_login/last_login_list", $data, TRUE);
				$this->load->view('admin/layout/layout_main', $data); //page load
			} else {
				redirect('admin/');
			}
		} else {
			redirect('admin/dashboard');
		}		  
     }
 
    public function last_login_list()
     {

		$data['title'] = $this->Core_model->site_title();
		$session = $this->session->userdata('username');
		if(!empty($session)){ 
			$this->load->view("admin/last_login/last_login_list", $data);
		} else {
			redirect('admin/');
		}
		// Datatables Variables
		$draw = intval($this->input->get("draw"));
		$start = intval($this->input->get("start"));
		$length = intval($this->input->get("length"));
		// reports to 
 		$reports_to = get_reports_team_data($session['user_id']);
		
		if($this->input->get("ihr")=='true'){
			if($this->input->get("company_id")==0 && $this->input->get("location_id")==0 && $this->input->get("department_id")==0 && $this->input->get("designation_id")==0){
				$employee = $this->Employees_model->get_employees_login();
				
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
			$user_info = $this->Core_model->read_user_info($session['user_id']);
			
			// if($user_info[0]->user_role_id==1 || $user_info[0]->user_role_id==4 ||$user_info[0]->user_role_id==5 || $user_info[0]->user_role_id==6){
			
			// 	$employee = $this->Employees_model->get_employees_login();
			
			// } else if($reports_to > 0) {
			
			// 	$employee = $this->Employees_model->get_employees_my_team($session['user_id']);
			
			// } else {
			
				$employee = $this->Employees_model->get_employees_login();
			
			// }
			
		}

		$role_resources_ids = $this->Core_model->user_role_resource();
		
		$data = array();
		$no = 1;
		foreach($employee->result() as $r) {
						  
		// login date and time
		if($r->last_login_date==''){
			$edate = '-';			
		} else {
			$edate = date('d-m-Y',strtotime($r->last_login_date));
			
		}

		if($r->last_login_date==''){
			$jam_masuk = '-';			
		} else {
			$jam_masuk = date('H:i:s',strtotime($r->last_login_date));
			
		}

		if(date('H:i:s',strtotime($r->last_logout_date)) =='07:00:00'){
			$jam_keluar = '-';			
		} else {
			$jam_keluar = date('H:i:s',strtotime($r->last_logout_date));
			
		}

		// employee link		
		if(in_array('202',$role_resources_ids)) {
			$emp_link = $r->employee_id;
		} else {
			$emp_link = $r->employee_id;
		}

		// user full name
		$full_name = $r->first_name.' '.$r->last_name;

		// userip
		$full_id = $r->last_login_ip;

		// get company
		$company = $this->Core_model->read_company_info($r->company_id);
		if(!is_null($company)){
			$comp_name = $company[0]->name;
		} else {
			$comp_name = '--';	
		}
		
		// get designation
		$designation = $this->Designation_model->read_designation_information($r->designation_id);
		if(!is_null($designation)){
			$designation_name = $designation[0]->designation_name;
		} else {
			$designation_name = '--';	
		}
		// department
		$department = $this->Department_model->read_department_information($r->department_id);
		if(!is_null($department)){
			$department_name = $department[0]->department_name;
		} else {
			$department_name = '--';	
		}
		$department_designation = $designation_name;
		$employee_nik = $emp_link;
		$employee_name = $full_name;
		// last login date and time
		
		$status ='Online';
		$data[] = array(
			$no,	
			$edate,
			$jam_masuk,
			$jam_keluar,
			$full_id,
			$employee_name,		
			$department_designation,		
			$comp_name,		
			$r->aplikasi_login,				
			
		);
		$no++;
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
}
