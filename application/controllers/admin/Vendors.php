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
defined('BASEPATH') OR exit('No direct script access allowed');

class Vendors extends MY_Controller {
	
	 public function __construct() {
        parent::__construct();
		//load the model
		$this->load->model("Vendors_model");
		$this->load->model("Core_model");
		$this->load->model("Designation_model");
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
		$system = $this->Core_model->read_setting_info(1);
		if($system[0]->module_training!='true'){
			redirect('admin/dashboard');
		}
		$data['title']            = 'Vendor Pelatihan | '.$this->Core_model->site_title();
		$data['desc']             = 'INPUT : Vendor Pelatihan ';
		$data['icon']             = '<i class="fa fa-building"></i>';
		$data['breadcrumbs']      = 'Vendor Pelatihan';
		$data['path_url']         = 'vendors';
		
		$role_resources_ids = $this->Core_model->user_role_resource();
		if(in_array('57',$role_resources_ids)) {
			if(!empty($session)){ 
				$data['subview'] = $this->load->view("admin/vendors/vendor_list", $data, TRUE);
				$this->load->view('admin/layout/layout_main', $data); //page load
			} else {
				redirect('admin/');
			}
		} else {
			redirect('admin/dashboard');
		}	  
     }
 
    public function vendor_list()
     {

		$data['title'] = $this->Core_model->site_title();
		$session = $this->session->userdata('username');
		if(!empty($session)){ 
			$this->load->view("admin/vendors/vendor_list", $data);
		} else {
			redirect('admin/');
		}
		// Datatables Variables
		$draw = intval($this->input->get("draw"));
		$start = intval($this->input->get("start"));
		$length = intval($this->input->get("length"));
				
		$role_resources_ids = $this->Core_model->user_role_resource();
		$user_info = $this->Core_model->read_user_info($session['user_id']);
		
		$Vendors = $this->Vendors_model->get_vendors();
		
		$data = array();

        foreach($Vendors->result() as $r) {
			 			  
			// get name
			$full_name = $r->name;
			

			if(in_array('572',$role_resources_ids)) { //edit
				$edit = '<span data-toggle="tooltip" data-placement="top" title="'.$this->lang->line('xin_edit').'">
							<button type="button" class="btn icon-btn btn-xs btn-default waves-effect waves-light"  data-toggle="modal" data-target=".edit-modal-data-min"  data-vendor_id="'. $r->vendor_id . '">
								<span class="fa fa-pencil"></span> Edit 
							</button>
						</span>';
			} else {
				$edit = '';
			}
			if(in_array('573',$role_resources_ids)) { // delete
				$delete = '<span data-toggle="tooltip" data-placement="top" title="'.$this->lang->line('xin_delete').'">
								<button type="button" class="btn icon-btn btn-xs btn-danger waves-effect waves-light delete" data-toggle="modal" data-target=".delete-modal" data-record-id="'. $r->vendor_id . '">
									<span class="fa fa-trash"></span>
								</button>
							</span>';
			} else {
				$delete = '';
			}
			if(in_array('574',$role_resources_ids)) { //view
				$view = '<span data-toggle="tooltip" data-placement="top" title="'.$this->lang->line('xin_view').'">
								<button type="button" class="btn icon-btn btn-xs btn-default waves-effect waves-light" data-toggle="modal" data-target=".view-modal-data" data-vendor_id="'. $r->vendor_id . '">
									<span class="fa fa-eye"></span>
								</button>
							</span>';
			} else {
				$view = '';
			}
			$combhr = $edit.$delete;

			
			$data[] = array(
			$combhr,
			$r->name
			
		);
      }

	  $output = array(
		   "draw" => $draw,
			 "recordsTotal" => $Vendors->num_rows(),
			 "recordsFiltered" => $Vendors->num_rows(),
			 "data" => $data
		);
	  echo json_encode($output);
	  exit();
     }
	 
	 public function read()
	{
		$data['title'] = $this->Core_model->site_title();
		$id            = $this->input->get('vendor_id');
		$result        = $this->Vendors_model->read_vendor_information($id);
		$data = array(
				'vendor_id' => $result[0]->vendor_id,			
				'name'      => $result[0]->name
				
				);
		$session = $this->session->userdata('username');
		if(!empty($session)){ 
			$this->load->view('admin/vendors/dialog_vendor', $data);
		} else {
			redirect('admin/');
		}
	}
	
	// Validate and add info in database
	public function add_vendor() {
	
		if($this->input->post('add_type')=='vendor') {		
		/* Define return | here result is used to return user data and error for error message */
		$Return = array('result'=>'', 'error'=>'', 'csrf_hash'=>'');
		$Return['csrf_hash'] = $this->security->get_csrf_hash();
			
				
		if($this->input->post('name')==='') {
       		$Return['error'] = 'Nama Vendor Wajib Diisi';
		}
				
		if($Return['error']!=''){
       		$this->output($Return);
    	}				
	
		$data = array(
			'name' => $this->input->post('name'),			
			'created_at' => date('d-m-Y')		
		);
		$result = $this->Vendors_model->add($data);
		if ($result == TRUE) {
			$Return['result'] = 'Data Vendor Baru Berhasil Ditambahkan';
		} else {
			$Return['error'] = $this->lang->line('xin_error_msg');
		}
		$this->output($Return);
		exit;
		}
	}
	
	// Validate and update info in database
	public function update() {
	
		if($this->input->post('edit_type')=='vendor') {
			
		$id = $this->uri->segment(4);
		
		/* Define return | here result is used to return user data and error for error message */
		$Return = array('result'=>'', 'error'=>'', 'csrf_hash'=>'');
		$Return['csrf_hash'] = $this->security->get_csrf_hash();
			
		/* Server side PHP input validation */
		
		if($this->input->post('name')==='') {
       		$Return['error'] = 'Nama Vendor Wajib Diisi';
		} 
				
		if($Return['error']!=''){
       		$this->output($Return);
    	}				
	
		$data = array(
			'name' => $this->input->post('name')
		
		);
		
		$result = $this->Vendors_model->update_record($data,$id);		
		
		if ($result == TRUE) {
			$Return['result'] = 'Data Vendor Berhasil Diperbarui';
		} else {
			$Return['error'] = $this->lang->line('xin_error_msg');
		}
		$this->output($Return);
		exit;
		}
	}
	
	public function delete() {
		
		$session = $this->session->userdata('username');
		if(empty($session)){ 
			redirect('admin/');
		}
		/* Define return | here result is used to return user data and error for error message */
		$Return = array('result'=>'', 'error'=>'', 'csrf_hash'=>'');
		$id = $this->uri->segment(4);
		$Return['csrf_hash'] = $this->security->get_csrf_hash();
		$result              = $this->Vendors_model->delete_record($id);
		if(isset($id)) {
			$Return['result'] = 'Data Vendor Berhasil Dihapus';
		} else {
			$Return['error'] = $this->lang->line('xin_error_msg');
		}
		$this->output($Return);
	}
}
