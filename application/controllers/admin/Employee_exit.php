<?php 

 /** 
 * ---------------------------------------------------------------------
 * INFORMASI
 * -----------------------------------------------------------------------
 * Nama Aplikasi :  HRIS
 * Email Support :  hris@karyasoftware.com
 * Developer     :  Nizar Basyrewan, S.Si - 0895 606460 731
 * Tahun         :  2021
 * Copyright     :  ©karyasoftware.com. All Rights Reserved
 * ----------------------------------------------------------------------
 */

defined('BASEPATH') OR exit('No direct script access allowed');

class Employee_exit extends MY_Controller {
	
	 public function __construct() {
        parent::__construct();
		//load the model
		$this->load->model("Employee_exit_model");
		$this->load->model("Core_model");
		$this->load->model("Department_model");
		$this->load->model("Designation_model");
		$this->load->model("Company_model");
	}
	// =================================================================================================================
	// START
	// =================================================================================================================
		/*Function to set JSON output*/
		public function output($Return=array()){
			/*Set response header*/
			header("Access-Control-Allow-Origin: *");
			header("Content-Type: application/json; charset=UTF-8");
			/*Final JSON response*/
			exit(json_encode($Return));
		}
	
	// =================================================================================================================
	// TABEL
	// =================================================================================================================

		public function index()
	    {
			$session = $this->session->userdata('username');
			if(empty($session)){ 
				redirect('admin/');
			}
			$data['title']             = $this->lang->line('left_employees_exit').' | '.$this->Core_model->site_title();
			$data['icon']              = '<i class="fa fa-sign-out"></i>';
			$data['breadcrumbs']       = $this->lang->line('left_employees_exit');
			$data['path_url']          = 'employee_exit';

			$data['get_all_companies'] = $this->Company_model->get_company();
			$data['all_employees']     = $this->Core_model->all_employees();
			$data['all_exit_types']    = $this->Employee_exit_model->all_exit_types();
			$data['all_exit_types_reason']    = $this->Employee_exit_model->all_exit_types_reason();
			
			
			$role_resources_ids = $this->Core_model->user_role_resource();
			if(in_array('0611',$role_resources_ids)) {
				if(!empty($session)){ 
					$data['subview'] = $this->load->view("admin/exit/exit_list", $data, TRUE);
					$this->load->view('admin/layout/layout_main', $data); //page load
				} else {
					redirect('admin/');
				}
			} else {
				redirect('admin/dashboard');
			}
	    }
 
	    public function exit_list()
	    {

			$data['title'] = $this->Core_model->site_title();
			$session = $this->session->userdata('username');
			if(!empty($session)){ 
				$this->load->view("admin/exit/exit_list", $data);
			} else {
				redirect('admin/');
			}
			// Datatables Variables
			$draw = intval($this->input->get("draw"));
			$start = intval($this->input->get("start"));
			$length = intval($this->input->get("length"));
									
			$exit = $this->Employee_exit_model->get_exit();
			
			$data = array();

	        $role_resources_ids = $this->Core_model->user_role_resource();
			
			foreach($exit->result() as $r) {
				 			  
				// get user > employee to exit
				$user = $this->Core_model->get_employee_view($r->employee_id);
				
				// user full name
				if(!is_null($user)){
					$full_name = $user[0]->first_name.' '.$user[0]->last_name;
				} else {
					$full_name = '--';	
				}
				
				// get user > added by
				$user_by = $this->Core_model->read_user_info($r->added_by);
				if(!is_null($user_by)){
					$added_by = $user_by[0]->first_name.' '.$user_by[0]->last_name;
				} else {
					$added_by = '--';	
				}

				// get exit date
				$exit_date = $this->Core_model->set_date_format($r->exit_date);
						
				// get exit type
				$exit_type = $this->Employee_exit_model->read_exit_type_information($r->exit_type_id);
				if(!is_null($exit_type)){
					$etype = $exit_type[0]->type;
				} else {
					$etype = '--';	
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

				if($r->exit_interview==0): $exit_interview = '<span class="badge bg-red"> Belum </span>'; else: $exit_interview = '<span class="badge bg-green"> Sudah </span>'; endif;
				
				if($r->is_inactivate_account==0): $account = '<span class="badge bg-green">'.$this->lang->line('xin_employees_active').'</span>'; else: $account = '<span class="badge bg-red">'.$this->lang->line('xin_employees_inactive').'</span>'; endif;
				
				if(in_array('0613',$role_resources_ids)) { //edit
					$edit = '<span data-toggle="tooltip" data-placement="top" title="'.$this->lang->line('xin_edit').'"><button type="button" class="btn icon-btn btn-xs btn-default waves-effect waves-light"  data-toggle="modal" data-target=".edit-modal-data"  data-exit_id="'. $r->exit_id . '"><span class="fa fa-pencil"></span> Edit </button></span> ';
				} else {
					$edit = '';
				}
				
				if(in_array('0614',$role_resources_ids)) { // delete
					$delete = '<span data-toggle="tooltip" data-placement="top" title="'.$this->lang->line('xin_delete').'"><button type="button" class="btn icon-btn btn-xs btn-danger waves-effect waves-light delete" data-toggle="modal" data-target=".delete-modal" data-record-id="'. $r->exit_id . '"><span class="fa fa-trash"></span></button></span>';
				} else {
					$delete = '';
				}
				
				if(in_array('0615',$role_resources_ids)) { //view
					$view = '<span data-toggle="tooltip" data-placement="top" title="'.$this->lang->line('xin_view').'"><button type="button" class="btn icon-btn btn-xs btn-default waves-effect waves-light" data-toggle="modal" data-target=".view-modal-data" data-exit_id="'. $r->exit_id . '"><span class="fa fa-eye"></span></button></span>';
				} else {
					$view = '';
				}

				$iful_name = $full_name;
				
				$combhr = $edit.$delete;
				
				$data[] = array(
					$combhr,
					$exit_date,
					$account,					
					$r->first_name.' '.$r->last_name.'<br><i class="fa fa-angle-double-right"></i> '.$designation_name,
					$department_name,					
					$etype,	
					$r->reason,			
					$exit_interview,
					
				);
	        }

		    $output = array(
			   "draw" => $draw,
				 "recordsTotal" => $exit->num_rows(),
				 "recordsFiltered" => $exit->num_rows(),
				 "data" => $data
			);
		  echo json_encode($output);
		  exit();
	    }
	
	// =================================================================================================================
	// PROSES
	// =================================================================================================================

	    public function read()
		{
			$data['title'] = $this->Core_model->site_title();
			$id = $this->input->get('exit_id');
			$result = $this->Employee_exit_model->read_exit_information($id);
			$data = array(
					'exit_id' => $result[0]->exit_id,
					'employee_id' => $result[0]->employee_id,
					'company_id' => $result[0]->company_id,
					'exit_date' => $result[0]->exit_date,
					'exit_type_id' => $result[0]->exit_type_id,
					'exit_type_reason_id' => $result[0]->exit_type_reason_id,
					'exit_interview' => $result[0]->exit_interview,
					'is_inactivate_account' => $result[0]->is_inactivate_account,
					'reason' => $result[0]->reason,
					'all_employees' => $this->Core_model->all_employees(),
					'all_exit_types' => $this->Employee_exit_model->all_exit_types(),
					'all_exit_types_reason' => $this->Employee_exit_model->all_exit_types_reason(),
					'get_all_companies' => $this->Company_model->get_company()
					);
			$session = $this->session->userdata('username');
			if(!empty($session)){ 
				$this->load->view('admin/exit/dialog_exit', $data);
			} else {
				redirect('admin/');
			}
		}
		
		// Validate and add info in database
		public function add_exit() {
		
			if($this->input->post('add_type')=='exit') {		
			/* Define return | here result is used to return user data and error for error message */
			$Return = array('result'=>'', 'error'=>'', 'csrf_hash'=>'');
			$Return['csrf_hash'] = $this->security->get_csrf_hash();
				
			/* Server side PHP input validation */
			$reason = $this->input->post('reason');
			$qt_reason = htmlspecialchars(addslashes($reason), ENT_QUOTES);
			
			if($this->input->post('company_id')==='') {
				$Return['error'] = $this->lang->line('error_company_field');
			
			} else if($this->input->post('employee_id')==='') {
	       		 $Return['error'] = $this->lang->line('xin_error_employee_id');
			
			} else if($this->input->post('exit_date')==='') {
				$Return['error'] = $this->lang->line('xin_error_exit_date');
			
			} else if($this->input->post('type')==='') {
				 $Return['error'] = $this->lang->line('xin_error_exit_type');
			
			} else if($this->input->post('type_reason')==='') {
				 $Return['error'] = $this->lang->line('xin_error_exit_type_reason');
			}
					
			if($Return['error']!=''){
	       		$this->output($Return);
	    	}
			
			
			if ($this->input->post('is_inactivate_account') == '1') {
				$status = 0;
			} else {
				$status = 1;
			}
			
			$e_id = $this->input->post('employee_id');
			
			$data1 = array(
				'date_of_leaving' => $this->input->post('exit_date'),
				'is_active' => 0	
			);
			
			$this->Employee_exit_model->update_record_status($data1,$e_id);
		
			$data = array(
				'employee_id' => $this->input->post('employee_id'),
				'company_id' => $this->input->post('company_id'),
				'exit_date' => $this->input->post('exit_date'),
				'reason' => $qt_reason,
				'exit_type_id' => $this->input->post('type'),
				'exit_type_reason_id' => $this->input->post('type_reason'),
				'exit_interview' => $this->input->post('exit_interview'),
				'is_inactivate_account' => $this->input->post('is_inactivate_account'),
				'added_by' => $this->input->post('user_id'),
				'created_at' => date('d-m-Y'),
			);
			$result = $this->Employee_exit_model->add($data);
			if ($result == TRUE) {
				$Return['result'] = $this->lang->line('xin_success_employee_exit_added');
			} else {
				$Return['error'] = $this->lang->line('xin_error_msg');
			}
			$this->output($Return);
			exit;
			}
		}
		
		// Validate and update info in database
		public function update() {
		
			if($this->input->post('edit_type')=='exit') {
				
			$id = $this->uri->segment(4);
			
			/* Define return | here result is used to return user data and error for error message */
			$Return = array('result'=>'', 'error'=>'', 'csrf_hash'=>'');
			$Return['csrf_hash'] = $this->security->get_csrf_hash();
				
			/* Server side PHP input validation */
			$reason = $this->input->post('reason');
			$qt_reason = htmlspecialchars(addslashes($reason), ENT_QUOTES);
			
			if($this->input->post('exit_date')==='') {
				$Return['error'] = $this->lang->line('xin_error_exit_date');
			
			} else if($this->input->post('type')==='') {
				 $Return['error'] = $this->lang->line('xin_error_exit_type');
			
			} else if($this->input->post('type_reason')==='') {
				 $Return['error'] = $this->lang->line('xin_error_exit_type_reason');
			}
					
			if($Return['error']!=''){
	       		$this->output($Return);
	    	}
			
			if ($this->input->post('is_inactivate_account') == '1') {
				$status = 0;
			} else {
				$status = 1;
			}
			
			$employee = $this->Employee_exit_model->read_exit_information($id);
			$e_id = $employee[0]->employee_id;
			
			$data1 = array(
				'date_of_leaving' => $this->input->post('exit_date'),
				'is_active' => 0		
			);
			
			$this->Employee_exit_model->update_record_status($data1,$e_id);
			
			$data = array(
				'exit_date' => $this->input->post('exit_date'),
				'reason' => $qt_reason,
				'exit_type_id' => $this->input->post('type'),
				'exit_type_reason_id' => $this->input->post('type_reason'),
				'exit_interview' => $this->input->post('exit_interview'),
				'is_inactivate_account' => $this->input->post('is_inactivate_account'),
			);
			
			$result = $this->Employee_exit_model->update_record($data,$id);		
			
			if ($result == TRUE) {
				$Return['result'] = $this->lang->line('xin_success_employee_exit_updated');
			} else {
				$Return['error'] = $this->lang->line('xin_error_msg');
			}
			$this->output($Return);
			exit;
			}
		}
		
		public function delete() {
			/* Define return | here result is used to return user data and error for error message */
			$Return = array('result'=>'', 'error'=>'', 'csrf_hash'=>'');
			$id = $this->uri->segment(4);
			$Return['csrf_hash'] = $this->security->get_csrf_hash();
			
			$employee = $this->Employee_exit_model->read_exit_information($id);
			$e_id = $employee[0]->user_id;
			
			$data1 = array(
				'date_of_leaving' => '0000-00-00',
				'is_active' => 1		
			);
			
			$this->Employee_exit_model->update_record_status($data1,$e_id);
			
			$result = $this->Employee_exit_model->delete_record($id);
			if(isset($id)) {
				$Return['result'] = $this->lang->line('xin_success_employee_exit_deleted');
			} else {
				$Return['error'] = $this->lang->line('xin_error_msg');
			}
			$this->output($Return);
		}

	// =================================================================================================================
	// TAMPILKAN
	// =================================================================================================================

		// get company > employees
		public function get_employees() {

			$data['title'] = $this->Core_model->site_title();
			$id = $this->uri->segment(4);
			
			$data = array(
				'company_id' => $id
				);
			$session = $this->session->userdata('username');
			if(!empty($session)){ 
				$this->load->view("admin/exit/get_employees", $data);
			} else {
				redirect('admin/');
			}
			// Datatables Variables
			$draw = intval($this->input->get("draw"));
			$start = intval($this->input->get("start"));
			$length = intval($this->input->get("length"));
		}

	// =================================================================================================================
	// END
	// =================================================================================================================		 
	
}