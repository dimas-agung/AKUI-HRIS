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

class Company extends MY_Controller {
	
	public function __construct() {
        parent::__construct();
		//load the models
		$this->load->model("Company_model");
		$this->load->model("Core_model");
		$this->load->model("Custom_fields_model");
		$this->load->model("Employees_model");
	}
	
	// ======================================================================================================
	// START
	// ======================================================================================================
		
		/*Function to set JSON output*/
		public function output($Return=array()){
		
			header("Access-Control-Allow-Origin: *");
			header("Content-Type: application/json; charset=UTF-8");
			
			exit(json_encode($Return));
		}
	
	// ======================================================================================================
	// DAFTAR
	// ======================================================================================================

		public function index()
	    {
			$session = $this->session->userdata('username');
			if(empty($session)){ 
				redirect('admin/');
			}
			$data['title']         = $this->lang->line('module_company_title').' | '.$this->Core_model->site_title();
			$data['icon']          = '<i class="fa fa-building"></i>';
			$data['breadcrumbs']   = $this->lang->line('module_company_title');
			$data['path_url']      = 'company';

			$data['all_countries']     = $this->Core_model->get_countries();
			$data['get_company_types'] = $this->Company_model->get_company_types();
			
			$role_resources_ids = $this->Core_model->user_role_resource();
			if(in_array('0211',$role_resources_ids)) {
				$data['subview'] = $this->load->view("admin/company/company_list", $data, TRUE);
				$this->load->view('admin/layout/layout_main', $data); //page load
			} else {
				redirect('admin/dashboard');
			}
	    }
				
		public function company_list()
	    {

			$data['title'] = $this->Core_model->site_title();
			$session = $this->session->userdata('username');
			if(!empty($session)){ 
				$this->load->view("admin/company/company_list", $data);
			} else {
				redirect('admin/');
			}
			// Datatables Variables
			$draw = intval($this->input->get("draw"));
			$start = intval($this->input->get("start"));
			$length = intval($this->input->get("length"));
			
			$role_resources_ids = $this->Core_model->user_role_resource();
			$user_info = $this->Core_model->read_user_info($session['user_id']);
			
			$company = $this->Company_model->get_companies();
			
			$data = array();

	        foreach($company->result() as $r) {
				  
			  	// get country
			  	$country = $this->Core_model->read_country_info($r->country);
			  	if(!is_null($country)){
			  		$c_name = $country[0]->country_name;
			  	} else {
					$c_name = '--';	
			  	}
			
			  	// company type
			  	$ctype = $this->Company_model->read_company_type($r->type_id);
			  	if(!is_null($ctype)){
			  		$type_name = $ctype[0]->name;
			  	} else {
					$type_name = '--';	
			  	}
			  
			 	if(in_array('0212',$role_resources_ids)) { //edit
					$edit = '<span data-toggle="tooltip" data-placement="top" title="'.$this->lang->line('xin_edit').'">
								<button type="button" class="btn icon-btn btn-xs btn-default waves-effect waves-light"  data-toggle="modal" data-target=".edit-modal-data"  data-company_id="'. $r->company_id . '">
									<span class="fa fa-pencil"></span> Edit
								</button>
							</span>';
				} else {
					$edit = '';
				}
			
				$combhr = $edit;//
				$icname = $r->name;
			    $data[] = array(
					$combhr,
					$icname,
					$r->email,
					$r->city,
					$c_name,
					$r->default_currency,
					$r->default_timezone
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
		 
	// ============================================================================================
	// PROSES
	// ============================================================================================ 
		// 01. Read
		public function read() 
		{
			$session = $this->session->userdata('username');
			if(empty($session)){ 
				redirect('admin/');
			}
			$data['title'] = $this->Core_model->site_title();
			$id = $this->input->get('company_id');
	       // $data['all_countries'] = $this->xin_model->get_countries();
			$result = $this->Company_model->read_company_information($id);
			$data = array(
					'company_id' => $result[0]->company_id,
					'name' => $result[0]->name,
					'username' => $result[0]->username,
					'password' => $result[0]->password,
					'type_id' => $result[0]->type_id,
					'government_tax' => $result[0]->government_tax,
					'trading_name' => $result[0]->trading_name,
					'registration_no' => $result[0]->registration_no,
					'email' => $result[0]->email,
					'logo' => $result[0]->logo,
					'contact_number' => $result[0]->contact_number,
					'website_url' => $result[0]->website_url,
					'address_1' => $result[0]->address_1,
					'address_2' => $result[0]->address_2,
					'city' => $result[0]->city,
					'state' => $result[0]->state,
					'zipcode' => $result[0]->zipcode,
					'countryid' => $result[0]->country,
					'idefault_currency' => $result[0]->default_currency,
					'idefault_timezone' => $result[0]->default_timezone,
					'all_countries' => $this->Core_model->get_countries(),
					'get_company_types' => $this->Company_model->get_company_types()
					);
			$this->load->view('admin/company/dialog_company', $data);
		}
		// 02. Lihat
		public function read_info()
		{
			$session = $this->session->userdata('username');
			if(empty($session)){ 
				redirect('admin/');
			}
			$data['title'] = $this->Core_model->site_title();
			$id = $this->input->get('company_id');
	       // $data['all_countries'] = $this->xin_model->get_countries();
			$result = $this->Company_model->read_company_information($id);
			$data = array(
					'company_id' => $result[0]->company_id,
					'name' => $result[0]->name,
					'username' => $result[0]->username,
					'password' => $result[0]->password,
					'type_id' => $result[0]->type_id,
					'government_tax' => $result[0]->government_tax,
					'trading_name' => $result[0]->trading_name,
					'registration_no' => $result[0]->registration_no,
					'email' => $result[0]->email,
					'logo' => $result[0]->logo,
					'contact_number' => $result[0]->contact_number,
					'website_url' => $result[0]->website_url,
					'address_1' => $result[0]->address_1,
					'address_2' => $result[0]->address_2,
					'city' => $result[0]->city,
					'state' => $result[0]->state,
					'zipcode' => $result[0]->zipcode,
					'countryid' => $result[0]->country,
					'idefault_currency' => $result[0]->default_currency,
					'idefault_timezone' => $result[0]->default_timezone,
					'all_countries' => $this->Core_model->get_countries(),
					'get_company_types' => $this->Company_model->get_company_types()
					);
			$this->load->view('admin/company/view_company.php', $data);
		}
		// 03. Tambah
		public function add_company() 
		{
		
			if($this->input->post('add_type')=='company') {
			// Check validation for user input
			$this->form_validation->set_rules('name', 'Name', 'trim|required|xss_clean');
			$this->form_validation->set_rules('website', 'Website', 'trim|required|xss_clean');
			$this->form_validation->set_rules('city', 'City', 'trim|required|xss_clean');
			
			$name = $this->input->post('name');
			$trading_name = $this->input->post('trading_name');
			$registration_no = $this->input->post('registration_no');
			$email = $this->input->post('email');
			$contact_number = $this->input->post('contact_number');
			$website = $this->input->post('website');
			$address_1 = $this->input->post('address_1');
			$address_2 = $this->input->post('address_2');
			$city = $this->input->post('city');
			$state = $this->input->post('state');
			$zipcode = $this->input->post('zipcode');
			$country = $this->input->post('country');
			$user_id = $this->input->post('user_id');
			$file = $_FILES['logo']['tmp_name'];
			
			/* Define return | here result is used to return user data and error for error message */
			$Return = array('result'=>'', 'error'=>'', 'csrf_hash'=>'');
			$Return['csrf_hash'] = $this->security->get_csrf_hash();
				
			/* Server side PHP input validation */
			if($name==='') {
				$Return['error'] = $this->lang->line('xin_error_name_field');
			} else if( $this->input->post('company_type')==='') {
				$Return['error'] = $this->lang->line('xin_error_ctype_field');
			} else if($contact_number==='') {
				$Return['error'] = $this->lang->line('xin_error_contact_field');
			} else if($email==='') {
				$Return['error'] = $this->lang->line('xin_error_cemail_field');
			} else if($website==='') {
				$Return['error'] = $this->lang->line('xin_error_website_field');
			}  else if($city==='') {
				$Return['error'] = $this->lang->line('xin_error_city_field');
			} else if($zipcode==='') {
				$Return['error'] = $this->lang->line('xin_error_zipcode_field');
			} else if($country==='') {
				$Return['error'] = $this->lang->line('xin_error_country_field');
			} else if($this->input->post('username')==='') {
				$Return['error'] = $this->lang->line('xin_employee_error_username');
			} else if($this->input->post('password')==='') {
				$Return['error'] = $this->lang->line('xin_employee_error_password');
			} else if($this->input->post('default_currency')==='') {
				$Return['error'] = $this->lang->line('xin_default_currency_field_error');
			} else if($this->input->post('default_timezone')==='') {
				$Return['error'] = $this->lang->line('xin_default_timezone_field_error');
			}
			
			/* Check if file uploaded..*/
			else if($_FILES['logo']['size'] == 0) {
				$fname = 'no file';
				 $Return['error'] = $this->lang->line('xin_error_logo_field');
			} else {
				if(is_uploaded_file($_FILES['logo']['tmp_name'])) {
					//checking image type
					$allowed =  array('png','jpg','jpeg','gif');
					$filename = $_FILES['logo']['name'];
					$ext = pathinfo($filename, PATHINFO_EXTENSION);
					
					if(in_array($ext,$allowed)){
						$tmp_name = $_FILES["logo"]["tmp_name"];
						$bill_copy = "uploads/company/";
						// basename() may prevent filesystem traversal attacks;
						// further validation/sanitation of the filename may be appropriate
						$lname = basename($_FILES["logo"]["name"]);
						$newfilename = 'logo_'.round(microtime(true)).'.'.$ext;
						move_uploaded_file($tmp_name, $bill_copy.$newfilename);
						$fname = $newfilename;
					} else {
						$Return['error'] = $this->lang->line('xin_error_attatchment_type');
					}
				}
			}
			
			if($Return['error']!=''){
	       		$this->output($Return);
	    	}
			$module_attributes = $this->Custom_fields_model->company_hris_module_attributes();
			$count_module_attributes = $this->Custom_fields_model->count_company_module_attributes();	
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
			$data = array(
			'name' => $this->input->post('name'),
			'type_id' => $this->input->post('company_type'),
			'username' => $this->input->post('username'),
			'password' => $this->input->post('password'),
			'government_tax' => $this->input->post('xin_gtax'),
			'trading_name' => $this->input->post('trading_name'),
			'registration_no' => $this->input->post('registration_no'),
			'email' => $this->input->post('email'),
			'contact_number' => $this->input->post('contact_number'),
			'website_url' => $this->input->post('website'),
			'address_1' => $this->input->post('address_1'),
			'address_2' => $this->input->post('address_2'),
			'city' => $this->input->post('city'),
			'state' => $this->input->post('state'),
			'zipcode' => $this->input->post('zipcode'),
			'country' => $this->input->post('country'),
			'default_currency' => $this->input->post('default_currency'),
			'default_timezone' => $this->input->post('default_timezone'),
			'added_by' => $this->input->post('user_id'),
			'logo' => $fname,
			'created_at' => date('d-m-Y'),
			
			);
			$iresult = $this->Company_model->add($data);
			if ($iresult) {

				// ----------------------------------------------------------------
				// log activity
				// ----------------------------------------------------------------
					// $this->Core_model->add_log_activity($modul_name,$fitur_name,$isi,$proses,$status); - default
					$this->Core_model->add_log_activity('Organisasi','Perusahaan','Tambah Perusahaan Baru','Tambah','Sukses');
				// ----------------------------------------------------------------

				$Return['result'] = $this->lang->line('xin_success_add_company');
				$id = $iresult;
				if($count_module_attributes > 0){
					foreach($module_attributes as $mattribute) {
					 	
						if($mattribute->attribute_type == 'fileupload'){
							if($_FILES[$mattribute->attribute]['size'] != 0) {
								if(is_uploaded_file($_FILES[$mattribute->attribute]['tmp_name'])) {
								
									$allowed =  array('png','jpg','jpeg','pdf','gif','xls','doc','xlsx','docx');
									$filename = $_FILES[$mattribute->attribute]['name'];
									$ext = pathinfo($filename, PATHINFO_EXTENSION);
									
									if(in_array($ext,$allowed)){
										$tmp_name = $_FILES[$mattribute->attribute]["tmp_name"];
										$profile = "uploads/custom_files/";
										$set_img = base_url()."uploads/custom_files/";
										
										$name = basename($_FILES[$mattribute->attribute]["name"]);
										$newfilename = 'custom_file_'.round(microtime(true)).'.'.$ext;
										move_uploaded_file($tmp_name, $profile.$newfilename);
										$fname = $newfilename;	
									}
									$iattr_data = array(
										'user_id' => $id,
										'module_attributes_id' => $mattribute->custom_field_id,
										'attribute_value' => $fname,
										'created_at' => date('Y-m-d h:i:s')
									);
									$this->Custom_fields_model->add_values($iattr_data);
								}
							} else {
								$iattr_data = array(
										'user_id' => $id,
										'module_attributes_id' => $mattribute->custom_field_id,
										'attribute_value' => '',
										'created_at' => date('Y-m-d h:i:s')
									);
									$this->Custom_fields_model->add_values($iattr_data);
							}
						} else if($mattribute->attribute_type == 'multiselect') {
							$multisel_val = $this->input->post($mattribute->attribute);
							if(!empty($multisel_val)){
								$newdata = implode(',', $this->input->post($mattribute->attribute));
								$iattr_data = array(
									'user_id' => $id,
									'module_attributes_id' => $mattribute->custom_field_id,
									'attribute_value' => $newdata,
									'created_at' => date('Y-m-d h:i:s')
								);
								$this->Custom_fields_model->add_values($iattr_data);
							}
						} else {
								if($this->input->post($mattribute->attribute) == ''){
									$file_val = '';
								} else {
									$file_val = $this->input->post($mattribute->attribute);
								}
								$iattr_data = array(
									'user_id' => $id,
									'module_attributes_id' => $mattribute->custom_field_id,
									'attribute_value' => $file_val,
									'created_at' => date('Y-m-d h:i:s')
								);
							$this->Custom_fields_model->add_values($iattr_data);
						}
						
					 }
				}
			} else {
				$Return['error'] = $this->lang->line('xin_error_msg');
			}
			$this->output($Return);
			exit;
			}
		}
		// 04. Edit
		public function update() 
		{
			if($this->input->post('edit_type')=='company') 
			{
					$id = $this->uri->segment(4);
					// Check validation for user input
					$this->form_validation->set_rules('name', 'Name', 'trim|required|xss_clean');
					$this->form_validation->set_rules('website', 'Website', 'trim|required|xss_clean');
					$this->form_validation->set_rules('city', 'City', 'trim|required|xss_clean');
					$name = $this->input->post('name');
					$trading_name = $this->input->post('trading_name');
					$registration_no = $this->input->post('registration_no');
					$email = $this->input->post('email');
					$contact_number = $this->input->post('contact_number');
					$website = $this->input->post('website');
					$address_1 = $this->input->post('address_1');
					$address_2 = $this->input->post('address_2');
					$city = $this->input->post('city');
					$state = $this->input->post('state');
					$zipcode = $this->input->post('zipcode');
					$country = $this->input->post('country');
					$user_id = $this->input->post('user_id');
					$file = $_FILES['logo']['tmp_name'];
							
					/* Define return | here result is used to return user data and error for error message */
					$Return = array('result'=>'', 'error'=>'', 'csrf_hash'=>'');
					$Return['csrf_hash'] = $this->security->get_csrf_hash();
						
					/* Server side PHP input validation */
					if($name==='') {
						$Return['error'] = $this->lang->line('xin_error_name_field');
					} else if( $this->input->post('company_type')==='') {
						$Return['error'] = $this->lang->line('xin_error_ctype_field');
					} else if($contact_number==='') {
						$Return['error'] = $this->lang->line('xin_error_contact_field');
					} else if($email==='') {
						$Return['error'] = $this->lang->line('xin_error_cemail_field');
					} else if($website==='') {
						$Return['error'] = $this->lang->line('xin_error_website_field');
					} else if($city==='') {
						$Return['error'] = $this->lang->line('xin_error_city_field');
					} else if($zipcode==='') {
						$Return['error'] = $this->lang->line('xin_error_zipcode_field');
					} else if($country==='') {
						$Return['error'] = $this->lang->line('xin_error_country_field');
					} else if($this->input->post('username')==='') {
						$Return['error'] = $this->lang->line('xin_employee_error_username');
					} else if($this->input->post('default_currency')==='') {
						$Return['error'] = $this->lang->line('xin_default_currency_field_error');
					} else if($this->input->post('default_timezone')==='') {
						$Return['error'] = $this->lang->line('xin_default_timezone_field_error');
					}
					
					/* Check if file uploaded..*/
					else if($_FILES['logo']['size'] == 0) {
						$fname = 'no file';
						$module_attributes = $this->Custom_fields_model->company_hris_module_attributes();
						$count_module_attributes = $this->Custom_fields_model->count_company_module_attributes();	
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
						 $no_logo_data = array(
						'name' => $this->input->post('name'),
						'type_id' => $this->input->post('company_type'),
						'username' => $this->input->post('username'),
						'password' => $this->input->post('password'),
						'government_tax' => $this->input->post('xin_gtax'),
						'trading_name' => $this->input->post('trading_name'),
						'registration_no' => $this->input->post('registration_no'),
						'email' => $this->input->post('email'),
						'contact_number' => $this->input->post('contact_number'),
						'website_url' => $this->input->post('website'),
						'address_1' => $this->input->post('address_1'),
						'address_2' => $this->input->post('address_2'),
						'city' => $this->input->post('city'),
						'state' => $this->input->post('state'),
						'zipcode' => $this->input->post('zipcode'),
						'country' => $this->input->post('country'),
						'default_currency' => $this->input->post('default_currency'),
						'default_timezone' => $this->input->post('default_timezone'),
						);
						 $result = $this->Company_model->update_record_no_logo($no_logo_data,$id);
						 if($count_module_attributes > 0){
						foreach($module_attributes as $mattribute) {
							
							//
							$count_exist_values = $this->Custom_fields_model->count_module_attributes_values($id,$mattribute->custom_field_id);
							if($count_exist_values > 0){
								if($mattribute->attribute_type == 'fileupload'){
									if($_FILES[$mattribute->attribute]['size'] != 0) {
										if(is_uploaded_file($_FILES[$mattribute->attribute]['tmp_name'])) {
										//checking image type
											$allowed =  array('png','jpg','jpeg','pdf','gif','xls','doc','xlsx','docx');
											$filename = $_FILES[$mattribute->attribute]['name'];
											$ext = pathinfo($filename, PATHINFO_EXTENSION);
											
											if(in_array($ext,$allowed)){
												$tmp_name = $_FILES[$mattribute->attribute]["tmp_name"];
												$profile = "uploads/custom_files/";
												$set_img = base_url()."uploads/custom_files/";
												// basename() may prevent filesystem traversal attacks;
												// further validation/sanitation of the filename may be appropriate
												$name = basename($_FILES[$mattribute->attribute]["name"]);
												$newfilename = 'custom_file_'.round(microtime(true)).'.'.$ext;
												move_uploaded_file($tmp_name, $profile.$newfilename);
												$fname = $newfilename;	
											}
											$iattr_data = array(
												'attribute_value' => $fname
											);
											$this->Custom_fields_model->update_att_record($iattr_data, $id,$mattribute->custom_field_id);
										}
										
									} else {
									}
								} else if($mattribute->attribute_type == 'multiselect') {
									$multisel_val = $this->input->post($mattribute->attribute);
									if(!empty($multisel_val)){
										$newdata = implode(',', $this->input->post($mattribute->attribute));
										$iattr_data = array(
											'attribute_value' => $newdata,
										);
										$this->Custom_fields_model->update_att_record($iattr_data, $id,$mattribute->custom_field_id);
									}
								} else {
									$attr_data = array(
										'attribute_value' => $this->input->post($mattribute->attribute),
									);
									$this->Custom_fields_model->update_att_record($attr_data, $id,$mattribute->custom_field_id);
								}
								
							} else {
								if($mattribute->attribute_type == 'fileupload'){
									if($_FILES[$mattribute->attribute]['size'] != 0) {
										if(is_uploaded_file($_FILES[$mattribute->attribute]['tmp_name'])) {
										//checking image type
											$allowed =  array('png','jpg','jpeg','pdf','gif','xls','doc','xlsx','docx');
											$filename = $_FILES[$mattribute->attribute]['name'];
											$ext = pathinfo($filename, PATHINFO_EXTENSION);
											
											if(in_array($ext,$allowed)){
												$tmp_name = $_FILES[$mattribute->attribute]["tmp_name"];
												$profile = "uploads/custom_files/";
												$set_img = base_url()."uploads/custom_files/";
												// basename() may prevent filesystem traversal attacks;
												// further validation/sanitation of the filename may be appropriate
												$name = basename($_FILES[$mattribute->attribute]["name"]);
												$newfilename = 'custom_file_'.round(microtime(true)).'.'.$ext;
												move_uploaded_file($tmp_name, $profile.$newfilename);
												$fname = $newfilename;	
											}
											$iattr_data = array(
												'user_id' => $id,
												'module_attributes_id' => $mattribute->custom_field_id,
												'attribute_value' => $fname,
												'created_at' => date('Y-m-d h:i:s')
											);
											$this->Custom_fields_model->add_values($iattr_data);
										}
									} else {
										if($this->input->post($mattribute->attribute) == ''){
											$file_val = '';
										} else {
											$file_val = $this->input->post($mattribute->attribute);
										}
										$iattr_data = array(
											'user_id' => $id,
											'module_attributes_id' => $mattribute->custom_field_id,
											'created_at' => date('Y-m-d h:i:s')
										);
										$this->Custom_fields_model->add_values($iattr_data);
									}
								} else if($mattribute->attribute_type == 'multiselect') {
									$multisel_val = $this->input->post($mattribute->attribute);
									if(!empty($multisel_val)){
										$newdata = implode(',', $this->input->post($mattribute->attribute));
										$iattr_data = array(
											'user_id' => $id,
											'module_attributes_id' => $mattribute->custom_field_id,
											'attribute_value' => $newdata,
											'created_at' => date('Y-m-d h:i:s')
										);
										$this->Custom_fields_model->add_values($iattr_data);
									}
								} else {
										if($this->input->post($mattribute->attribute) == ''){
											$file_val = '';
										} else {
											$file_val = $this->input->post($mattribute->attribute);
										}
										$iattr_data = array(
											'user_id' => $id,
											'module_attributes_id' => $mattribute->custom_field_id,
											'attribute_value' => $file_val,
											'created_at' => date('Y-m-d h:i:s')
										);
									$this->Custom_fields_model->add_values($iattr_data);
								}
							}
						 }
					}
					} else {
						$module_attributes = $this->Custom_fields_model->company_hris_module_attributes();
						$count_module_attributes = $this->Custom_fields_model->count_company_module_attributes();	
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
						if(is_uploaded_file($_FILES['logo']['tmp_name'])) {
							//checking image type
							$allowed =  array('png','jpg','jpeg','gif');
							$filename = $_FILES['logo']['name'];
							$ext = pathinfo($filename, PATHINFO_EXTENSION);
							
							if(in_array($ext,$allowed)){
								$tmp_name = $_FILES["logo"]["tmp_name"];
								$bill_copy = "uploads/company/";
								// basename() may prevent filesystem traversal attacks;
								// further validation/sanitation of the filename may be appropriate
								$lname = basename($_FILES["logo"]["name"]);
								$newfilename = 'logo_'.round(microtime(true)).'.'.$ext;
								move_uploaded_file($tmp_name, $bill_copy.$newfilename);
								$fname = $newfilename;
								$data = array(
								'name' => $this->input->post('name'),
								'type_id' => $this->input->post('company_type'),
								'government_tax' => $this->input->post('xin_gtax'),
								'trading_name' => $this->input->post('trading_name'),
								'registration_no' => $this->input->post('registration_no'),
								'email' => $this->input->post('email'),
								'contact_number' => $this->input->post('contact_number'),
								'website_url' => $this->input->post('website'),
								'address_1' => $this->input->post('address_1'),
								'address_2' => $this->input->post('address_2'),
								'city' => $this->input->post('city'),
								'state' => $this->input->post('state'),
								'zipcode' => $this->input->post('zipcode'),
								'country' => $this->input->post('country'),
								'logo' => $fname,		
								);
								// update record > model
								$result = $this->Company_model->update_record($data,$id);
								if($count_module_attributes > 0){
									foreach($module_attributes as $mattribute) {
										
										//
										$count_exist_values = $this->Custom_fields_model->count_module_attributes_values($id,$mattribute->custom_field_id);
										if($count_exist_values > 0){
											if($mattribute->attribute_type == 'fileupload'){
												if($_FILES[$mattribute->attribute]['size'] != 0) {
													if(is_uploaded_file($_FILES[$mattribute->attribute]['tmp_name'])) {
													//checking image type
														$allowed =  array('png','jpg','jpeg','pdf','gif','xls','doc','xlsx','docx');
														$filename = $_FILES[$mattribute->attribute]['name'];
														$ext = pathinfo($filename, PATHINFO_EXTENSION);
														
														if(in_array($ext,$allowed)){
															$tmp_name = $_FILES[$mattribute->attribute]["tmp_name"];
															$profile = "uploads/custom_files/";
															$set_img = base_url()."uploads/custom_files/";
															// basename() may prevent filesystem traversal attacks;
															// further validation/sanitation of the filename may be appropriate
															$name = basename($_FILES[$mattribute->attribute]["name"]);
															$newfilename = 'custom_file_'.round(microtime(true)).'.'.$ext;
															move_uploaded_file($tmp_name, $profile.$newfilename);
															$fname = $newfilename;	
														}
														$iattr_data = array(
															'attribute_value' => $fname
														);
														$this->Custom_fields_model->update_att_record($iattr_data, $id,$mattribute->custom_field_id);
													}
													
												} else {
												}
											} else if($mattribute->attribute_type == 'multiselect') {
												$multisel_val = $this->input->post($mattribute->attribute);
												if(!empty($multisel_val)){
													$newdata = implode(',', $this->input->post($mattribute->attribute));
													$iattr_data = array(
														'attribute_value' => $newdata,
													);
													$this->Custom_fields_model->update_att_record($iattr_data, $id,$mattribute->custom_field_id);
												}
											} else {
												$attr_data = array(
													'attribute_value' => $this->input->post($mattribute->attribute),
												);
												$this->Custom_fields_model->update_att_record($attr_data, $id,$mattribute->custom_field_id);
											}
											
										} else {
											if($mattribute->attribute_type == 'fileupload'){
												if($_FILES[$mattribute->attribute]['size'] != 0) {
													if(is_uploaded_file($_FILES[$mattribute->attribute]['tmp_name'])) {
													//checking image type
														$allowed =  array('png','jpg','jpeg','pdf','gif','xls','doc','xlsx','docx');
														$filename = $_FILES[$mattribute->attribute]['name'];
														$ext = pathinfo($filename, PATHINFO_EXTENSION);
														
														if(in_array($ext,$allowed)){
															$tmp_name = $_FILES[$mattribute->attribute]["tmp_name"];
															$profile = "uploads/custom_files/";
															$set_img = base_url()."uploads/custom_files/";
															// basename() may prevent filesystem traversal attacks;
															// further validation/sanitation of the filename may be appropriate
															$name = basename($_FILES[$mattribute->attribute]["name"]);
															$newfilename = 'custom_file_'.round(microtime(true)).'.'.$ext;
															move_uploaded_file($tmp_name, $profile.$newfilename);
															$fname = $newfilename;	
														}
														$iattr_data = array(
															'user_id' => $id,
															'module_attributes_id' => $mattribute->custom_field_id,
															'attribute_value' => $fname,
															'created_at' => date('Y-m-d h:i:s')
														);
														$this->Custom_fields_model->add_values($iattr_data);
													}
												} else {
													if($this->input->post($mattribute->attribute) == ''){
														$file_val = '';
													} else {
														$file_val = $this->input->post($mattribute->attribute);
													}
													$iattr_data = array(
														'user_id' => $id,
														'module_attributes_id' => $mattribute->custom_field_id,
														'created_at' => date('Y-m-d h:i:s')
													);
													$this->Custom_fields_model->add_values($iattr_data);
												}
											} else if($mattribute->attribute_type == 'multiselect') {
												$multisel_val = $this->input->post($mattribute->attribute);
												if(!empty($multisel_val)){
													$newdata = implode(',', $this->input->post($mattribute->attribute));
													$iattr_data = array(
														'user_id' => $id,
														'module_attributes_id' => $mattribute->custom_field_id,
														'attribute_value' => $newdata,
														'created_at' => date('Y-m-d h:i:s')
													);
													$this->Custom_fields_model->add_values($iattr_data);
												}
											} else {
													if($this->input->post($mattribute->attribute) == ''){
														$file_val = '';
													} else {
														$file_val = $this->input->post($mattribute->attribute);
													}
													$iattr_data = array(
														'user_id' => $id,
														'module_attributes_id' => $mattribute->custom_field_id,
														'attribute_value' => $file_val,
														'created_at' => date('Y-m-d h:i:s')
													);
												$this->Custom_fields_model->add_values($iattr_data);
											}
										}
									 }
								}
							} else {
								$Return['error'] = $this->lang->line('xin_error_attatchment_type');
							}
						}
					}
					
					if($Return['error']!=''){
			       		$this->output($Return);
			    	}
					
					
					if ($result == TRUE) {

						// ----------------------------------------------------------------
						// log activity
						// ----------------------------------------------------------------
							// $this->Core_model->add_log_activity($modul_name,$fitur_name,$isi,$proses,$status); - default
							$this->Core_model->add_log_activity('Organisasi','Perusahaan','Perbarui Data Perusahaan ' .$this->input->post('name'),'Edit','Sukses');
						// ----------------------------------------------------------------

						$Return['result'] = 'Data Perusahaan '.$this->input->post('name').' Berhasil Diperbarui';
					} else {
						$Return['error'] = $Return['error'] = $this->lang->line('xin_error_msg');
					}
					$this->output($Return);
					exit;
			}
		}
		// 05. Hapus
		public function delete() 
		{
			if($this->input->post('is_ajax')==2) {
				$session = $this->session->userdata('username');
				if(empty($session)){ 
					redirect('admin/');
				}
				/* Define return | here result is used to return user data and error for error message */
				$Return = array('result'=>'', 'error'=>'', 'csrf_hash'=>'');
				$id = $this->uri->segment(4);
				$Return['csrf_hash'] = $this->security->get_csrf_hash();
				$result = $this->Company_model->delete_record($id);
				if(isset($id)) {
					$Return['result'] = $this->lang->line('xin_success_delete_company');
				} else {
					$Return['error'] = $Return['error'] = $this->lang->line('xin_error_msg');
				}
				$this->output($Return);
			}
		}
	// ======================================================================================================
	// END
	// =====================================================================================================

}
