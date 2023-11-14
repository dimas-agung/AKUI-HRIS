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

class Awards extends MY_Controller {
	
	 public function __construct() {
        parent::__construct();
		//load the model
		$this->load->model("Awards_model");
		$this->load->model("Core_model");
		$this->load->library('email');
		$this->load->model("Department_model");
		$this->load->model("Custom_fields_model");
		$this->load->model("Company_model");
		$this->load->model("Finance_model");
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
			$system = $this->Core_model->read_setting_info(1);
			if($system[0]->module_awards!='true'){
				redirect('admin/dashboard');
			}
			$data['title']       = $this->lang->line('left_awards').' | '.$this->Core_model->site_title();
			$data['icon']        = '<i class="fa fa-trophy"></i>';
			$data['breadcrumbs'] = $this->lang->line('left_awards');
			$data['path_url']    = 'awards';

			$data['all_employees'] = $this->Core_model->all_employees();
			$data['all_award_types'] = $this->Awards_model->all_award_types();
			$data['get_all_companies'] = $this->Company_model->get_company();
			
			$role_resources_ids = $this->Core_model->user_role_resource();
			if(in_array('0621',$role_resources_ids)) {
				if(!empty($session)){ 
					$data['subview'] = $this->load->view("admin/awards/award_list", $data, TRUE);
					$this->load->view('admin/layout/layout_main', $data); //page load
				} else {
					redirect('admin/');
				}
			} else {
				redirect('admin/dashboard');
			}
	    }
 
	    public function award_list()
	    {

			$data['title'] = $this->Core_model->site_title();
			$session = $this->session->userdata('username');
			if(!empty($session)){ 
				$this->load->view("admin/awards/award_list", $data);
			} else {
				redirect('admin/');
			}
			// Datatables Variables
			$draw = intval($this->input->get("draw"));
			$start = intval($this->input->get("start"));
			$length = intval($this->input->get("length"));
			//get_company_awards
			$role_resources_ids = $this->Core_model->user_role_resource();
			$user_info = $this->Core_model->read_user_info($session['user_id']);
			// if($user_info[0]->user_role_id==1){
				$award = $this->Awards_model->get_awards();
			// } else {
			// 	if(in_array('232',$role_resources_ids)) {
			// 		$award = $this->Awards_model->get_company_awards($user_info[0]->company_id);
			// 	} else {
			// 		$award = $this->Awards_model->get_employee_awards($session['user_id']);
			// 	}
			// }			
			
			$data = array();

	        foreach($award->result() as $r) {
				 			  
				// get user > added by
				$user = $this->Core_model->read_user_info($r->employee_id);
				// user full name
				if(!is_null($user)){
					$full_name = $user[0]->first_name.' '.$user[0]->last_name;
				} else {
					$full_name = '--';	
				}
				// get award type
				$award_type = $this->Awards_model->read_award_type_information($r->award_type_id);
				if(!is_null($award_type)){
					$award_type = $award_type[0]->award_type;
				} else {
					$award_type = '--';	
				}
				
				$d = explode('-',$r->award_month_year);
				$get_month = date('F', mktime(0, 0, 0, $d[1], 10));
				$award_date = $get_month.', '.$d[0];
				// get currency
				if($r->cash_price == '') {
					$currency = $this->Core_model->currency_sign(0);
				} else {
					$currency = $this->Core_model->currency_sign($r->cash_price);
				}		
				// get company
				$company = $this->Core_model->read_company_info($r->company_id);
				if(!is_null($company)){
					$comp_name = $company[0]->name;
				} else {
					$comp_name = '--';	
				}
			
				if(in_array('0623',$role_resources_ids)) { //edit
					$edit = '<span data-toggle="tooltip" data-placement="top" title="'.$this->lang->line('xin_edit').'"><button type="button" class="btn icon-btn btn-xs btn-default waves-effect waves-light"  data-toggle="modal" data-target=".edit-modal-data"  data-award_id="'. $r->award_id . '"><span class="fa fa-pencil"></span></button></span>';
				} else {
					$edit = '';
				}
				if(in_array('0624',$role_resources_ids)) { // delete
					$delete = '<span data-toggle="tooltip" data-placement="top" title="'.$this->lang->line('xin_delete').'"><button type="button" class="btn icon-btn btn-xs btn-danger waves-effect waves-light delete" data-toggle="modal" data-target=".delete-modal" data-record-id="'. $r->award_id . '"><span class="fa fa-trash"></span></button></span>';
				} else {
					$delete = '';
				}
				if(in_array('0625',$role_resources_ids)) { //view
					$view = '<span data-toggle="tooltip" data-placement="top" title="'.$this->lang->line('xin_view').'"><button type="button" class="btn icon-btn btn-xs btn-default waves-effect waves-light" data-toggle="modal" data-target=".view-modal-data" data-award_id="'. $r->award_id . '"><span class="fa fa-eye"></span></button></span>';
				} else {
					$view = '';
				}
				$award_info = $award_type.'<br><small class="text-muted"><i>'.$r->description.'<i></i></i></small><br><small class="text-muted"><i>'.$this->lang->line('xin_cash_price').': '.$currency.'<i></i></i></small>';
				$combhr = $edit.$view.$delete;
						
				$data[] = array(
					$combhr,
					$r->award_date,
					$award_info,
					$full_name,					
					$r->gift_item,
					$award_date
				);
	      }

		  	$output = array(
			   "draw" => $draw,
				 "recordsTotal" => $award->num_rows(),
				 "recordsFiltered" => $award->num_rows(),
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
			$session = $this->session->userdata('username');
			if(empty($session)){ 
				redirect('admin/');
			}
			$data['title'] = $this->Core_model->site_title();
			$id = $this->input->get('award_id');
			$result = $this->Awards_model->read_award_information($id);
			$data = array(
					'award_id' => $result[0]->award_id,
					'company_id' => $result[0]->company_id,
					'employee_id' => $result[0]->employee_id,
					'award_type_id' => $result[0]->award_type_id,
					'gift_item' => $result[0]->gift_item,
					'award_photo' => $result[0]->award_photo,
					'cash_price' => $result[0]->cash_price,
					'award_month_year' => $result[0]->award_month_year,
					'award_information' => $result[0]->award_information,
					'description' => $result[0]->description,
					'award_date' => $result[0]->award_date,
					'all_employees' => $this->Core_model->all_employees(),
					'all_award_types' => $this->Awards_model->all_award_types(),
					'get_all_companies' => $this->Company_model->get_company()
					);
			if(!empty($session)){ 
				$this->load->view('admin/awards/dialog_award', $data);
			} else {
				redirect('admin/');
			}
		}
		
		// Validate and add info in database
		public function add_award() 
		{
			if($this->input->post('add_type')=='award') {		
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
			
			} else if($this->input->post('award_type_id')==='') {
				$Return['error'] = $this->lang->line('xin_award_error_award_type');
			
			} else if($this->input->post('award_date')==='') {
				$Return['error'] = $this->lang->line('xin_award_error_award_date');
			
			} else if($this->input->post('month_year')==='') {
				$Return['error'] = $this->lang->line('xin_award_error_award_month');
			
			// }  else if($this->input->post('cash')!='') {
			// 	$Return['error'] = $this->lang->line('xin_award_error_award_cash');

			}  else if($_FILES['award_picture']['size'] == 0) {
				$Return['error'] = $this->lang->line('xin_award_error_award_photo');

			} else {
				if(is_uploaded_file($_FILES['award_picture']['tmp_name'])) {
					//checking image type
					$allowed =  array('png','jpg','jpeg','pdf','gif');
					$filename = $_FILES['award_picture']['name'];
					$ext = pathinfo($filename, PATHINFO_EXTENSION);
					
					if(in_array($ext,$allowed)){
						$tmp_name = $_FILES["award_picture"]["tmp_name"];
						$profile = "uploads/award/";
						$set_img = base_url()."uploads/award/";
						// basename() may prevent filesystem traversal attacks;
						// further validation/sanitation of the filename may be appropriate
						$name = basename($_FILES["award_picture"]["name"]);
						$newfilename = 'award_'.round(microtime(true)).'.'.$ext;
						move_uploaded_file($tmp_name, $profile.$newfilename);
						$fname = $newfilename;			
					} else {
						$Return['error'] = $this->lang->line('xin_error_attatchment_type');
					}
				} else {
					$fname = '';
				}
			}	
			if($Return['error']!=''){
				$this->output($Return);
	    	}
			
			$module_attributes = $this->Custom_fields_model->awards_hris_module_attributes();
			$count_module_attributes = $this->Custom_fields_model->count_awards_module_attributes();	
			$i=1;
			if($count_module_attributes > 0){
				 foreach($module_attributes as $mattribute) {
					 if($mattribute->validation == 1){
						 if($i!=1) {
						 } else if($this->input->post($mattribute->attribute)=='') {
							$Return['error'] = $this->lang->line('xin_hris_custom_field_the').' '.$mattribute->attribute_label.' '.$this->lang->line('xin_hris_custom_field_is_required');
						}
					 }
				 }		
				 if($Return['error']!=''){
					$this->output($Return);
				}	
			}
			$system_settings = system_settings_info(1);	
			if($system_settings->online_payment_account == ''){
				$online_payment_account = 0;
			} else {
				$online_payment_account = $system_settings->online_payment_account;
			}		
			$data = array(
			'award_date' => $this->input->post('award_date'),
			'employee_id' => $this->input->post('employee_id'),
			'company_id' => $this->input->post('company_id'),
			'award_type_id' => $this->input->post('award_type_id'),
			'award_date' => $this->input->post('award_date'),
			'award_photo' => $fname,
			'award_month_year' => $this->input->post('month_year'),
			'gift_item' => $this->input->post('gift'),
			'cash_price' => $this->input->post('cash'),
			'description' => $qt_description,
			'award_information' => $this->input->post('award_information'),		
			);
			$iresult = $this->Awards_model->add($data);
			if ($iresult) {

				$Return['result'] = 'Penghargaan Berhasil Ditambahkan';		
			
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
			if($this->input->post('edit_type')=='award') {
				
			$id = $this->uri->segment(4);
			
			/* Define return | here result is used to return user data and error for error message */
			$Return = array('result'=>'', 'error'=>'', 'csrf_hash'=>'');
			$Return['csrf_hash'] = $this->security->get_csrf_hash();
				
			/* Server side PHP input validation */
			$description = $this->input->post('description');
			$qt_description = htmlspecialchars(addslashes($description), ENT_QUOTES);
			
			if($this->input->post('award_type_id')==='') {
				$Return['error'] = $this->lang->line('xin_award_error_award_type');
			} else if($this->input->post('award_date')==='') {
				$Return['error'] = $this->lang->line('xin_award_error_award_date');
			} else if($this->input->post('month_year')==='') {
				$Return['error'] = $this->lang->line('xin_award_error_award_month');
			}  		
			/* Check if file uploaded..*/
			else if($_FILES['award_picture']['size'] == 0) {
				$module_attributes = $this->Custom_fields_model->awards_hris_module_attributes();
				$count_module_attributes = $this->Custom_fields_model->count_awards_module_attributes();	
				$i=1;
				if($count_module_attributes > 0){
					 foreach($module_attributes as $mattribute) {
						 if($mattribute->validation == 1){
							 if($i!=1) {
							 } else if($this->input->post($mattribute->attribute)=='') {
								$Return['error'] = $this->lang->line('xin_hris_custom_field_the').' '.$mattribute->attribute_label.' '.$this->lang->line('xin_hris_custom_field_is_required');
							}
						 }
					 }		
					 if($Return['error']!=''){
						$this->output($Return);
					}	
				}
				$fname = '';
				 $data = array(
				'award_type_id' => $this->input->post('award_type_id'),
				'award_date' => $this->input->post('award_date'),
				'award_month_year' => $this->input->post('month_year'),
				'gift_item' => $this->input->post('gift'),
				'cash_price' => $this->input->post('cash'),
				'description' => $qt_description,
				'award_information' => $this->input->post('award_information'),		
				);
				 $result = $this->Awards_model->update_record($data,$id);
			} 
			
			if($Return['error']!=''){
	       		$this->output($Return);
	    	}
			
			if ($result == TRUE) {
				$Return['result'] = 'Penghargaan Berhasil Diperbarui';
				
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
			if(empty($session)){ 
				redirect('admin/');
			}
			/* Define return | here result is used to return user data and error for error message */
			$Return = array('result'=>'', 'error'=>'', 'csrf_hash'=>'');
			$id = $this->uri->segment(4);
			$Return['csrf_hash'] = $this->security->get_csrf_hash();
			$result = $this->Awards_model->delete_record($id);
			if(isset($id)) {
				$Return['result'] = 'Penghargaan Berhasil Dihapus';
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
				$this->load->view("admin/awards/get_employees", $data);
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
