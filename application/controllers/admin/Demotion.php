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

defined('BASEPATH') OR exit('No direct script access allowed');

class Demotion extends MY_Controller {
	
	 public function __construct() {
        parent::__construct();
		//load the model
		$this->load->model("Demotion_model");
		$this->load->model("Core_model");
		$this->load->model("Department_model");
		$this->load->model("Designation_model");
		$this->load->model("Company_model");
		$this->load->model("Employees_model");
	}
	// =================================================================================================================
	// START
	// =================================================================================================================
		/*Function to set JSON output*/
		public function output($Return=array())
		{
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
			$data['title']       = 'Demosi | '.$this->Core_model->site_title();
			$data['icon']        = '<i class="fa fa-cloud-download"></i>';
			$data['breadcrumbs'] = 'Demosi';
			$data['path_url']    = 'demotion';

			$data['all_employees'] = $this->Core_model->all_employees();
			$data['get_all_companies'] = $this->Company_model->get_company();
			
			$role_resources_ids = $this->Core_model->user_role_resource();
			if(in_array('0671',$role_resources_ids)) {
				if(!empty($session)){ 
					$data['subview'] = $this->load->view("admin/demotion/demotion_list", $data, TRUE);
					$this->load->view('admin/layout/layout_main', $data); //page load
				} else {
					redirect('admin/');
				}
			} else {
				redirect('admin/dashboard');
			}
	    }
 
	    public function demotion_list()
	    {

			$data['title'] = $this->Core_model->site_title();
			$session = $this->session->userdata('username');
			if(!empty($session)){ 
				$this->load->view("admin/demotion/demotion_list", $data);
			} else {
				redirect('admin/');
			}
			// Datatables Variables
			$draw = intval($this->input->get("draw"));
			$start = intval($this->input->get("start"));
			$length = intval($this->input->get("length"));
			
			$role_resources_ids = $this->Core_model->user_role_resource();
			$user_info = $this->Core_model->read_user_info($session['user_id']);
			
			$demotion = $this->Demotion_model->get_demotions();

			$data = array();

	        foreach($demotion->result() as $r) {
				 			  		
				// get user > employee_
				$employee = $this->Core_model->read_employee_info_data($r->employee_id);
				// employee full name
				if(!is_null($employee)){
					$employee_name = $employee[0]->first_name.' '.$employee[0]->last_name;
				} else {
					$employee_name = '--';	
				}
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

			    $designation_name_old = $r->designation_id_old;
				

				// get demotion date
				$demotion_date = $this->Core_model->set_date_format($r->demotion_date);
				if(in_array('0673',$role_resources_ids)) { //edit
					$edit = '<span data-toggle="tooltip" data-placement="top" title="Aktifkan">
								<button type="button" class="btn icon-btn btn-xs btn-default waves-effect waves-light"  data-toggle="modal" data-target=".edit-modal-data"  data-demotion_id="'. $r->demotion_id . '">
									<span class="fa fa-check-circle"></span> Aktifkan
								</button>
							</span>';
				} else {
					$edit = '';
				}
				if(in_array('0674',$role_resources_ids)) { // delete
					$delete = '<span data-toggle="tooltip" data-placement="top" title="'.$this->lang->line('xin_delete').'">
									<button type="button" class="btn icon-btn btn-xs btn-danger waves-effect waves-light delete" data-toggle="modal" data-target=".delete-modal" data-record-id="'. $r->demotion_id . '">
										<span class="fa fa-trash"></span>
									</button>
								</span>';
				} else {
					$delete = '';
				}
				if(in_array('0675',$role_resources_ids)) { //view
					$view = '<span data-toggle="tooltip" data-placement="top" title="'.$this->lang->line('xin_view').'"><button type="button" class="btn icon-btn btn-xs btn-default waves-effect waves-light" data-toggle="modal" data-target=".view-modal-data" data-demotion_id="'. $r->demotion_id . '"><span class="fa fa-eye"></span></button></span>';
				} else {
					$view = '';
				}
				
				$combhr = $edit.$view.$delete;


				// =================================================================================================
			// get status
			// =================================================================================================
				if($r->status == 0): $status = '<span class="badge bg-orange">'.$this->lang->line('xin_pending').'</span>';
				elseif($r->status == 1): $status = '<span class="badge bg-green">'.$this->lang->line('xin_accepted').'</span>';
				else: $status = '<span class="badge bg-red">'.$this->lang->line('xin_rejected').'</span>'; 
				endif;
				
				$emp_name = $employee_name;
				
				$promoted_job = $designation_name.' <br> <small class="text-muted"><i>Dengan Posisi Sebelumnya Sebagai : <br>'.$designation_name_old.'</i></small>';

				if ($r->wages_type == '1'){

					$promoted_to = $this->lang->line('xin_demotion_title_by').' : Gaji Bulanan <br><small class="text-muted"><i>'.$this->lang->line('xin_description').': <br>'.$r->description.'<i></i></i></small>';

				} else if ($r->wages_type == '2') {

					$promoted_to = $this->lang->line('xin_demotion_title_by').' : Gaji Harian <br><small class="text-muted"><i>'.$this->lang->line('xin_description').': <br>'.$r->description.'<i></i></i></small>';

				} else if ($r->wages_type == '3'){

					$promoted_to = $this->lang->line('xin_demotion_title_by').' : Gaji Borongan <br><small class="text-muted"><i>'.$this->lang->line('xin_description').': <br>'.$r->description.'<i></i></i></small>';

				}
				
				$data[] = array(
					$combhr,
					$demotion_date,
					$emp_name,				
					$promoted_job,
					$promoted_to,
					$status,
				);
	      }

		  $output = array(
			   "draw" => $draw,
				 "recordsTotal" => $demotion->num_rows(),
				 "recordsFiltered" => $demotion->num_rows(),
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
			$id = $this->input->get('demotion_id');
			$result = $this->Demotion_model->read_demotion_information($id);
			// get designation
			
			$company = $this->Company_model->read_company_information($result[0]->company_id);
			if(!is_null($company)){
				$company_name = $company[0]->name;
			} else {
				$company_name = '--';	
			}
			$employee = $this->Core_model->read_employee_info_data($result[0]->employee_id);
			if(!is_null($employee)){
				$employee_name = $employee[0]->first_name.' '.$employee[0]->last_name;

			} else {
				$employee_name = '--';	
			}
			$designation = $this->Designation_model->read_designation_information($result[0]->designation_id);
			if(!is_null($designation)){
				$designation_name = $designation[0]->designation_name;
			} else {
				$designation_name = '--';	
			}
			$data = array(
					'demotion_id' => $result[0]->demotion_id,
					'company_name' => $company_name,
					'employee_name' => $employee_name,

					'designation_name' => $designation_name,
					'designation_name_old' => $result[0]->designation_id_old,

					'title'            => $result[0]->title,
					'demotion_date'   => $result[0]->demotion_date,
					'description' => $result[0]->description,
					'get_all_companies' => $this->Company_model->get_company(),
					'all_employees' => $this->Core_model->all_employees()
					);
				$session = $this->session->userdata('username');
			if(!empty($session)){ 
				$this->load->view('admin/demotion/dialog_demotion', $data);
			} else {
				redirect('admin/');
			}
		}

		public function view() 
	    {
			$data['title'] = $this->Core_model->site_title();
			$id = $this->input->get('demotion_id');
			$result = $this->Demotion_model->read_demotion_information($id);
			// get designation
			
			$company = $this->Company_model->read_company_information($result[0]->company_id);
			if(!is_null($company)){
				$company_name = $company[0]->name;
			} else {
				$company_name = '--';	
			}
			$employee = $this->Core_model->read_employee_info_data($result[0]->employee_id);
			if(!is_null($employee)){
				$employee_name = $employee[0]->first_name.' '.$employee[0]->last_name;

			} else {
				$employee_name = '--';	
			}
			$designation = $this->Designation_model->read_designation_information($result[0]->designation_id);
			if(!is_null($designation)){
				$designation_name = $designation[0]->designation_name;
			} else {
				$designation_name = '--';	
			}
			$data = array(
					'demotion_id' => $result[0]->demotion_id,
					'employee_id' => $result[0]->employee_id,
					'company_name' => $company_name,
					'company_id' => $result[0]->company_id,
					'employee_name' => $employee_name,

					'designation_name' => $designation_name,
					'designation_name_old' => $result[0]->designation_id_old,

					'title'            => $result[0]->title,
					'demotion_date'   => $result[0]->demotion_date,
					'description' => $result[0]->description,
					'get_all_companies' => $this->Company_model->get_company(),
					'all_employees' => $this->Core_model->all_employees()
					);
				$session = $this->session->userdata('username');
			if(!empty($session)){ 
				$this->load->view('admin/demotion/dialog_demotion_view', $data);
			} else {
				redirect('admin/');
			}
		}
		
		// Validate and add info in database
		public function add_demotion() 
		{
			if($this->input->post('add_type')=='demotion') {		
			/* Define return | here result is used to return user data and error for error message */
			$Return = array('result'=>'', 'error'=>'', 'csrf_hash'=>'');
			$Return['csrf_hash'] = $this->security->get_csrf_hash();
				
			/* Server side PHP input validation */
			$description = $this->input->post('description');
			$qt_description = htmlspecialchars(addslashes($description), ENT_QUOTES);
			
			if($this->input->post('company_id')==='') {
				$Return['error'] = $this->lang->line('error_company_field');
			} else if($this->input->post('employee_id')==='') {
	       		 $Return['error'] = $this->lang->line('xin_error_employee_id');
			} else if($this->input->post('designation_id')==='') {
	       		 $Return['error'] = $this->lang->line('xin_error_designation_field');
			} else if($this->input->post('title')==='') {
				$Return['error'] = $this->lang->line('xin_error_title');
			} else if($this->input->post('demotion_date')==='') {
				 $Return['error'] = $this->lang->line('xin_error_demotion_date');
			}
					
			if($Return['error']!=''){
	       		$this->output($Return);
	    	}
			// ======================================================================================================
			$employee = $this->Employees_model->read_employee_information_id( $this->input->post('employee_id') );
			if(!is_null($employee)){
				$designation_id_old = $employee[0]->designation_id;
				$department_id_old = $employee[0]->department_id;
				$wages_type_id_old = $employee[0]->wages_type;
				$user_id = $employee[0]->user_id;

			} else {
				$designation_id_old = '0';
				$department_id_old  ='0';	
				$wages_type_id_old = '';
				$user_id = '';
			}
			// ======================================================================================================
			$designation = $this->Designation_model->read_designation_information($designation_id_old);
			if(!is_null($designation)){
				$designation_name = $designation[0]->designation_name;
			} else {
				$designation_name = '--';	
			}
			// ======================================================================================================
			$department = $this->Department_model->read_department_information($department_id_old);
			if(!is_null($department)){
				$department_name = $department[0]->department_name;
			} else {
				$department_name = '--';	
			}
			// ======================================================================================================
			$designation_info = $this->Designation_model->read_designation_information($this->input->post('designation_id'));
			if(!is_null($designation_info)){
				$department_id = $designation_info[0]->department_id;
			} else {
				$department_id = '0';	
			}

			$data = array(
				
				'employee_id'        => $this->input->post('employee_id'),
				'company_id'         => $this->input->post('company_id'),
				
				'designation_id'     => $this->input->post('designation_id'),
				'designation_id_old' => $designation_name,

				'department_id'      => $department_id,
				'department_id_old'  => $department_name,
				
				'wages_type'         => $this->input->post('title'),
				'wages_type_old'     => $wages_type_id_old,
				'title'              => 'Demosi',
				'demotion_date'     => $this->input->post('demotion_date'),
				'status'             => 0,
				'description'        => $qt_description,
				'added_by'           => $this->input->post('user_id'),
				'created_at'         => date('d-m-Y'),
			
			);
			$result = $this->Demotion_model->add($data);
			if ($result == TRUE) {
				$Return['result'] = $this->lang->line('xin_demotion_added');
				
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
			if($this->input->post('edit_type')=='demotion') {
				
			$id = $this->uri->segment(4);
			
			/* Define return | here result is used to return user data and error for error message */
			$Return = array('result'=>'', 'error'=>'', 'csrf_hash'=>'');
			$Return['csrf_hash'] = $this->security->get_csrf_hash();		

			/* Server side PHP input validation */
			$description = $this->input->post('description');
			$qt_description = htmlspecialchars(addslashes($description), ENT_QUOTES);
			
			if($this->input->post('title')==='') {
				$Return['error'] = $this->lang->line('xin_error_title');
			} else if($this->input->post('demotion_date')==='') {
				 $Return['error'] = $this->lang->line('xin_error_demotion_date');
			}
					
			if($Return['error']!=''){
	       		$this->output($Return);
	    	}
			// =================================================================================================	

			$result = $this->Demotion_model->read_demotion_information($id);
			if(!is_null($result)){
				$employee_id    = $result[0]->employee_id;
				$designation_id = $result[0]->designation_id;
				$department_id  = $result[0]->department_id;
			} else {
				$employee_id    = '0';	
				$designation_id = '0';
				$department_id  = '0';

			}
			$employee = $this->Employees_model->read_employee_information_id( $employee_id );
			if(!is_null($employee)){
			
				$user_id = $employee[0]->user_id;

			} else {
			
				$user_id = '';
			}

			$user_data = array(			
				'department_id'  => $department_id,
				'designation_id' => $designation_id,	
				'wages_type'     => $this->input->post('title')
			);
			$user_info = $this->Employees_model->basic_info($user_data,$user_id);

			// ====================================================================================================

			$data = array(
				'title'          => $this->input->post('title'),
				'demotion_date' => $this->input->post('demotion_date'),	
				'status'         => 1,	
				'description'    => $qt_description,		
			);
			
			$result = $this->Demotion_model->update_record($data,$id);		
			
			if ($result == TRUE) {
				$Return['result'] = $this->lang->line('xin_demotion_updated');

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
			$Return = array('result'=>'', 'error'=>'', 'csrf_hash'=>'');
			$id = $this->uri->segment(4);
			$Return['csrf_hash'] = $this->security->get_csrf_hash();
			$result = $this->Demotion_model->delete_record($id);
			if(isset($id)) {
				$Return['result'] = $this->lang->line('xin_demotion_deleted');
			} else {
				$Return['error'] = $this->lang->line('xin_error_msg');
			}
			$this->output($Return);
		}

	// =================================================================================================================
	// TAMPILKAN
	// =================================================================================================================

		// get company > employees
		public function get_employees() 
		{
			$data['title'] = $this->Core_model->site_title();
			$id = $this->uri->segment(4);
			
			$data = array(
				'company_id' => $id
				);
			$session = $this->session->userdata('username');
			if(!empty($session)){ 
				$this->load->view("admin/demotion/get_employees", $data);
			} else {
				redirect('admin/');
			}
			// Datatables Variables
			$draw = intval($this->input->get("draw"));
			$start = intval($this->input->get("start"));
			$length = intval($this->input->get("length"));
		}
		// get company > employee > designations
		public function get_employee_designations() 
		{
			$data['title'] = $this->Core_model->site_title();
			$id = $this->uri->segment(4);
			// get user > employee_
			
			$data = array(
				'employee_id' => $id
				);
			$session = $this->session->userdata('username');
			if(!empty($session)){ 
				$this->load->view("admin/demotion/get_designations", $data);
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
