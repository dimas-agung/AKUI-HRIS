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

class Location extends MY_Controller {
	
	public function __construct() {
        parent::__construct();
		//load the model
		$this->load->model("Location_model");
		$this->load->model("Employees_model");
		$this->load->model("Department_model");
		$this->load->model("Company_model");
		$this->load->model("Core_model");
	}

	// ======================================================================================================
	// START
	// ======================================================================================================
		
		/*Function to set JSON output*/
		public function output($Return=array()){
			/*Set response header*/
			header("Access-Control-Allow-Origin: *");
			header("Content-Type: application/json; charset=UTF-8");
			/*Final JSON response*/
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
			$data['title']       = $this->lang->line('xin_locations').' | '.$this->Core_model->site_title();
			$data['icon']        = '<i class="fa fa-globe"></i>';
			$data['breadcrumbs'] = $this->lang->line('xin_locations');
			$data['path_url']    = 'location';

			$data['all_countries'] = $this->Core_model->get_countries();
			$data['all_companies'] = $this->Company_model->get_company();
			$data['all_employees'] = $this->Core_model->all_employees();
			
			$role_resources_ids = $this->Core_model->user_role_resource();
			if(in_array('0221',$role_resources_ids)) {
				if(!empty($session)){ 
				$data['subview'] = $this->load->view("admin/location/location_list", $data, TRUE);
				$this->load->view('admin/layout/layout_main', $data); //page load
				} else {
					redirect('admin/');
				}
			} else {
				redirect('admin/dashboard');
			}
	    }
	 
	    public function location_list()
	    {

			$data['title'] = $this->Core_model->site_title();
			$session = $this->session->userdata('username');
			if(!empty($session)){ 
				$this->load->view("admin/location/location_list", $data);
			} else {
				redirect('admin/');
			}
			// Datatables Variables
			$draw = intval($this->input->get("draw"));
			$start = intval($this->input->get("start"));
			$length = intval($this->input->get("length"));
			
			$role_resources_ids = $this->Core_model->user_role_resource();
			$user_info = $this->Core_model->read_user_info($session['user_id']);
						
			$location = $this->Location_model->get_locations();
						
			$data = array();
			$no=1;
	          foreach($location->result() as $r) {
				  
				  // get country
				  $country = $this->Core_model->read_country_info($r->country);
				  if(!is_null($country)){
				  	$c_name = $country[0]->country_name;
				  } else {
					  $c_name = '{belum diinput}';	
				  }
				  // get company
				  $company = $this->Core_model->read_company_info($r->company_id);
				  if(!is_null($company)){
				  	$comp_name = $company[0]->name;
				  } else {
					  $comp_name = '{belum diinput}';	
				  }
				  // get user
				  $user = $this->Core_model->read_user_info($r->added_by);
				  // user full name
				  if(!is_null($user)){
				  	$full_name = $user[0]->first_name.' '.$user[0]->last_name."<br/>".$r->created_at;
				  } else {
					$full_name = '{belum diinput}';	
				  }
				  
				  // get karyawan
				$jum_karyawan = $this->Employees_model->get_total_employees_location($r->location_id);
				if(!is_null($jum_karyawan)){
					$jumlah_karyawan = $jum_karyawan[0]->jumlah;
				} else {
					$jumlah_karyawan = '--';	
				}

				   // get location head
				  $location_head = $this->Core_model->read_user_info($r->location_head);
				  // user full name
				  if(!is_null($location_head)){
				  	 $head_name = $location_head[0]->first_name.' '.$location_head[0]->last_name;
				  } else {
					  $head_name = '{belum diinput}';	
				  }
				  if(in_array('0223',$role_resources_ids)) { //edit
					$edit = '<span data-toggle="tooltip" data-placement="top" title="'.$this->lang->line('xin_edit').'"><button type="button" class="btn icon-btn btn-xs btn-default waves-effect waves-light"  data-toggle="modal" data-target="#edit-modal-data"  data-location_id="'. $r->location_id . '"><span class="fa fa-pencil"></span> Edit </button></span></span>';
				} else {
					$edit = '';
				}
				if(in_array('0224',$role_resources_ids)) { // delete
					$delete = '<span data-toggle="tooltip" data-placement="top" title="'.$this->lang->line('xin_delete').'"><button type="button" class="btn icon-btn btn-xs btn-danger waves-effect waves-light delete" data-toggle="modal" data-target=".delete-modal" data-record-id="'. $r->location_id . '"><span class="fa fa-times"></span></button></span>';
				} else {
					$delete = '';
				}
				
				$ilocation_name = strtoupper($r->location_name);
				
				if(!is_null($r->address_1)){
				    $location_address = $r->address_1;
				 } else {
				    $location_address = '{belum diinput}';	
				 }

				$ilocation_address = strtoupper($location_address);
				

				$combhr = $edit.$delete;
				
	               $data[] = array(
				   		$combhr,			   		
	                    $ilocation_name.'<br> <small><i class="fa fa-home"></i> '.$location_address.'</small>',
	                    $head_name,
	                    $comp_name,  	
	                    $jumlah_karyawan					
	               );
	                $no++;
	          }
	         
	          $output = array(
	               "draw" => $draw,
	                 "recordsTotal" => $location->num_rows(),
	                 "recordsFiltered" => $location->num_rows(),
	                 "data" => $data
	            );
	          echo json_encode($output);
	          exit();
	    }

	// ============================================================================================
	// PROSES
	// ============================================================================================ 
		// Read
		public function read()
		{
			$session = $this->session->userdata('username');
			if(empty($session)){ 
				redirect('admin/');
			}
			$data['title'] = $this->Core_model->site_title();
			$id = $this->input->get('location_id');
	       // $data['all_countries'] = $this->xin_model->get_countries();
			$result = $this->Location_model->read_location_information($id);
			$data = array(
					'location_id' => $result[0]->location_id,
					'company_id' => $result[0]->company_id,
					'location_head' => $result[0]->location_head,
					'location_name' => $result[0]->location_name,
					'email' => $result[0]->email,
					'phone' => $result[0]->phone,
					'fax' => $result[0]->fax,
					'address_1' => $result[0]->address_1,
					'address_2' => $result[0]->address_2,
					'city' => $result[0]->city,
					'state' => $result[0]->state,
					'zipcode' => $result[0]->zipcode,
					'countryid' => $result[0]->country,
					'all_countries' => $this->Core_model->get_countries(),
					'all_companies' => $this->Company_model->get_company(),
					'all_employees' => $this->Core_model->all_employees()
					);
			if(!empty($session)){ 
				$this->load->view('admin/location/dialog_location', $data);
			} else {
				redirect('admin/');
			}
		}
		// Lihat
		public function read_info()
		{
			$session = $this->session->userdata('username');
			if(empty($session)){ 
				redirect('admin/');
			}
			$data['title'] = $this->Core_model->site_title();
			$id = $this->input->get('location_id');
	       // $data['all_countries'] = $this->xin_model->get_countries();
			$result = $this->Location_model->read_location_information($id);
			$data = array(
					'location_id' => $result[0]->location_id,
					'company_id' => $result[0]->company_id,
					'location_head' => $result[0]->location_head,
					'location_name' => $result[0]->location_name,
					'email' => $result[0]->email,
					'phone' => $result[0]->phone,
					'fax' => $result[0]->fax,
					'address_1' => $result[0]->address_1,
					'address_2' => $result[0]->address_2,
					'city' => $result[0]->city,
					'state' => $result[0]->state,
					'zipcode' => $result[0]->zipcode,
					'countryid' => $result[0]->country,
					'all_countries' => $this->Core_model->get_countries(),
					'all_companies' => $this->Company_model->get_company(),
					'all_employees' => $this->Core_model->all_employees()
					);
			if(!empty($session)){ 
				$this->load->view('admin/location/view_location', $data);
			} else {
				redirect('admin/');
			}
		}
		// Tambah		
		public function add_location() 
		{
		
			if($this->input->post('add_type')=='location') {
			// Check validation for user input
			$this->form_validation->set_rules('company', 'Company', 'trim|required|xss_clean');
			$this->form_validation->set_rules('name', 'Name', 'trim|required|xss_clean');
			$this->form_validation->set_rules('email', 'Email', 'trim|required|xss_clean');
			
			/* Define return | here result is used to return user data and error for error message */
			$Return = array('result'=>'', 'error'=>'', 'csrf_hash'=>'');
			$Return['csrf_hash'] = $this->security->get_csrf_hash();
				
			/* Server side PHP input validation */
			if($this->input->post('company')==='') {
	        	$Return['error'] = $this->lang->line('error_company_field');
			} else if($this->input->post('name')==='') {
				$Return['error'] = $this->lang->line('xin_error_name_field');
			} else if($this->input->post('city')==='') {
				$Return['error'] = $this->lang->line('xin_error_city_field');
			} else if($this->input->post('country')==='') {
				$Return['error'] = $this->lang->line('xin_error_country_field');
			}
					
			if($Return['error']!=''){
	       		$this->output($Return);
	    	}
		
			$data = array(
			'company_id' => $this->input->post('company'),
			'location_name' => $this->input->post('name'),
			'location_head' => $this->input->post('location_head'),
			'email' => $this->input->post('email'),
			'phone' => $this->input->post('phone'),
			'fax' => $this->input->post('fax'),
			'address_1' => $this->input->post('address_1'),
			'address_2' => $this->input->post('address_2'),
			'city' => $this->input->post('city'),
			'state' => $this->input->post('state'),
			'zipcode' => $this->input->post('zipcode'),
			'country' => $this->input->post('country'),
			'added_by' => $this->input->post('user_id'),
			'created_at' => date('d-m-Y'),
			
			);
			$result = $this->Location_model->add($data);
			if ($result == TRUE) {
				$Return['result'] = $this->lang->line('xin_success_add_location');
			} else {
				$Return['error'] = $this->lang->line('xin_error_msg');
			}
			$this->output($Return);
			exit;
			}
		}		
		// Edit
		public function update() 
		{
		
			$session = $this->session->userdata('username');
			if(empty($session)){ 
				redirect('admin/');
			}
			if($this->input->post('edit_type')=='location') {
				
			$id = $this->uri->segment(4);
			
			// Check validation for user input
			$this->form_validation->set_rules('company', 'Company', 'trim|required|xss_clean');
			$this->form_validation->set_rules('name', 'Name', 'trim|required|xss_clean');
			$this->form_validation->set_rules('email', 'Email', 'trim|required|xss_clean');
			
			/* Define return | here result is used to return user data and error for error message */
			$Return = array('result'=>'', 'error'=>'', 'csrf_hash'=>'');
			$Return['csrf_hash'] = $this->security->get_csrf_hash();
				
			/* Server side PHP input validation */
			if($this->input->post('company')==='') {
	        	$Return['error'] = $this->lang->line('error_company_field');
			} else if($this->input->post('name')==='') {
				$Return['error'] = $this->lang->line('xin_error_name_field');
			} else if($this->input->post('city')==='') {
				$Return['error'] = $this->lang->line('xin_error_city_field');
			} else if($this->input->post('country')==='') {
				$Return['error'] = $this->lang->line('xin_error_country_field');
			}
					
			if($Return['error']!=''){
	       		$this->output($Return);
	    	}
		
			$data = array(
			'company_id' => $this->input->post('company'),
			'location_name' => $this->input->post('name'),
			'location_head' => $this->input->post('location_head'),
			'email' => $this->input->post('email'),
			'phone' => $this->input->post('phone'),
			'fax' => $this->input->post('fax'),
			'address_1' => $this->input->post('address_1'),
			'address_2' => $this->input->post('address_2'),
			'city' => $this->input->post('city'),
			'state' => $this->input->post('state'),
			'zipcode' => $this->input->post('zipcode'),
			'country' => $this->input->post('country'),		
			);	
			
			$result = $this->Location_model->update_record($data,$id);		
			
			if ($result == TRUE) {
				$Return['result'] = $this->lang->line('xin_success_update_location');
			} else {
				$Return['error'] = $this->lang->line('xin_error_msg');
			}
			$this->output($Return);
			exit;
			}
		}
		// Hapus
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
				$result = $this->Location_model->delete_record($id);
				if(isset($id)) {
					$Return['result'] = $this->lang->line('xin_success_delete_location');
				} else {
					$Return['error'] = $this->lang->line('xin_error_msg');
				}
				$this->output($Return);
			}
		}

	// ============================================================================================
	// TAMPILKAN
	// ============================================================================================ 
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
				$this->load->view("admin/location/get_employees", $data);
			} else {
				redirect('admin/');
			}
			// Datatables Variables
			$draw = intval($this->input->get("draw"));
			$start = intval($this->input->get("start"));
			$length = intval($this->input->get("length"));
		}
		 
	// ============================================================================================
	// END
	// ============================================================================================
}
