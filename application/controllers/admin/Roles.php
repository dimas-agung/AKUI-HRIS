<?php
 /**
 * INFORMASI
 *
 * This source file is subject to the PortalHR License
 * that is bundled with this package in the file license.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.karyasoftware.com
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to hris@karyasoftware.com so we can send you a copy immediately.
 *
 * @author   Nizar Basyrewan, S.Si
 * @author-email  hris@karyasoftware.com
 * @copyright  Copyright © karyasoftware.com. All Rights Reserved
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Roles extends MY_Controller {
	
	 public function __construct() {
        parent::__construct();
		//load the model
		$this->load->model("Roles_model");
		$this->load->model("Company_model");
		$this->load->model("Core_model");
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
		$data['title']       = $this->lang->line('xin_role_urole').' | '.$this->Core_model->site_title();
		$data['icon']        = '<i class="fa fa-unlock-alt"></i>';
		$data['breadcrumbs'] = $this->lang->line('xin_role_urole');
		$data['path_url']    = 'roles';
		
		
		$user = $this->Core_model->read_employee_info($session['user_id']);
		if($user[0]->user_role_id==1) {
			if(!empty($session)){ 
				$data['subview'] = $this->load->view("admin/roles/role_list", $data, TRUE);
				$this->load->view('admin/layout/layout_main', $data); //page load
			} else {
				redirect('admin/');
			}
		} else {
			redirect('admin/dashboard');
		}
     }
 
    public function role_list()
     {

		$data['title'] = $this->Core_model->site_title();
		$session = $this->session->userdata('username');
		if(!empty($session)){ 
			$this->load->view("admin/roles/role_list", $data);
		} else {
			redirect('admin/');
		}

		$role_resources_ids = $this->Core_model->user_role_resource();

		// Datatables Variables
		$draw = intval($this->input->get("draw"));
		$start = intval($this->input->get("start"));
		$length = intval($this->input->get("length"));
		
		
		$role = $this->Roles_model->get_user_roles();
		
		$data = array();

        foreach($role->result() as $r) {
          	
          	$om = '';
          	/* get modul info*/
			if($r->role_resources == '') 
			{
				$om = '-';
			} 
			else 
			{
				$om = '<ol class="nl">';
				foreach(explode(',',$r->role_resources) as $mid) {
					$modul = $this->Core_model->read_modul_info($mid);
					if(!is_null($modul)){
						$om .= '<li>'.$modul[0]->modul_name.'</li>';
					} else {
						$om .= '';
					}
				 }
				 $om .= '</ol>';
			}

			$of = '';
          	/* get modul info*/
			if($r->role_resources == '') 
			{
				$of = '-';
			} 
			else 
			{
				$of = '<ol class="nl">';
				foreach(explode(',',$r->role_resources) as $fid) {
					$modul_fitur = $this->Core_model->read_fitur_info($fid);
					if(!is_null($modul_fitur)){
						$of .= '<li>'.$modul_fitur[0]->fitur_name.'</li>';
					} else {
						$of .= '';
					}
				 }
				 $of .= '</ol>';
			}

			if($r->role_id == '') {
				$ok = '<span class="blink blink-one"> 0 Karyawan </span>';
			} else {
				$ok = '<ol class="nl">';
				$employee = $this->Core_model->read_employee_info_role($r->role_id);
				foreach($employee->result() as $e) {
					

					if($e->is_active == 0){
						$status = '<span class="badge bg-red"> Resign </span>';
					} else {
						$status = '<span class="badge bg-green"> Aktif </span>';
					}


					if(!is_null($employee)){
						$ok .= '<li>'.$status.' '.$e->first_name.' '.$e->last_name.' ('.date("d-m-Y",strtotime($e->date_of_joining)).')</li>';
					} else {
						$ok .= '--';	
					}			
				}
				 $ok .= '</ol>';
			}


			
          
			/* get status*/
			if($r->role_access==1): $r_access = $this->lang->line('xin_role_all_menu'); 			
			elseif($r->role_access==2): $r_access = $this->lang->line('xin_role_cmenu'); endif;
			
			// 
			$created_at = $this->Core_model->set_date_format($r->created_at);
			//edit
			if($r->role_id==1)
			{
				$roleAccess = '<span data-toggle="tooltip" data-placement="top" title="'.$this->lang->line('xin_edit').'"><button type="button" class="btn icon-btn btn-xs btn-default waves-effect waves-light"  data-toggle="modal" data-target=".edit-modal-data"  data-role_id="'. $r->role_id . '"><span class="fa fa-pencil"></span> Edit </button></span>';
			} else {
				$roleAccess = '<span data-toggle="tooltip" data-placement="top" title="'.$this->lang->line('xin_edit').'"><button type="button" class="btn icon-btn btn-xs btn-default waves-effect waves-light"  data-toggle="modal" data-target=".edit-modal-data"  data-role_id="'. $r->role_id . '"><span class="fa fa-pencil"></span> Edit </button></span><span data-toggle="tooltip" data-placement="top" title="'.$this->lang->line('xin_delete').'"><button type="button" class="btn icon-btn btn-xs btn-danger waves-effect waves-light delete" data-toggle="modal" data-target=".delete-modal" data-record-id="'. $r->role_id . '"><span class="fa fa-trash"></span></button></span>';
			}

		    $data[] = array(
				$roleAccess,				
				$r->role_name,
				$om,
				$of,
				$ok,
				$r_access,
				$created_at
		    );
        }

        $output = array(
               "draw" => $draw,
                 "recordsTotal" => $role->num_rows(),
                 "recordsFiltered" => $role->num_rows(),
                 "data" => $data
        );
        echo json_encode($output);
        exit();
     }
	 
	 public function read()
	{
		$data['title'] = $this->Core_model->site_title();
		$id = $this->input->get('role_id');
		$result = $this->Roles_model->read_role_information($id);
		$data = array(
				'role_id' => $result[0]->role_id,
				'role_name' => $result[0]->role_name,
				'role_access' => $result[0]->role_access,
				'role_resources' => $result[0]->role_resources,
				'get_all_companies' => $this->Company_model->get_company(),
				);
		$session = $this->session->userdata('username');
		if(!empty($session)){ 
			$this->load->view('admin/roles/dialog_role', $data);
		} else {
			redirect('admin/');
		}
	}
	
	// Validate and add info in database
	public function add_role() {
	
		if($this->input->post('add_type')=='role') {		
		/* Define return | here result is used to return user data and error for error message */
		$Return = array('result'=>'', 'error'=>'', 'csrf_hash'=>'');
		$Return['csrf_hash'] = $this->security->get_csrf_hash();
			
		/* Server side PHP input validation */
		if($this->input->post('role_name')==='') {
        	$Return['error'] = $this->lang->line('xin_role_error_role_name');
		} else if($this->input->post('role_access')==='') {
			$Return['error'] = $this->lang->line('xin_role_error_access');
		}
				
						
		if($Return['error']!=''){
       		$this->output($Return);
    	}
	
		$data = array(
		'role_name' => $this->input->post('role_name'),
		'role_access' => $this->input->post('role_access'),		
		'created_at' => date('d-m-Y'),
		);
		
		$result = $this->Roles_model->add($data);
		if ($result == TRUE) {
			$Return['result'] = $this->lang->line('xin_role_success_added');
		} else {
			$Return['error'] = $this->lang->line('xin_error_msg');
		}
		$this->output($Return);
		exit;
		}
	}
	
	// Validate and update info in database
	public function update() {
	
		if($this->input->post('edit_type')=='role') {
			
		$id = $this->uri->segment(4);
		
		/* Define return | here result is used to return user data and error for error message */
		$Return = array('result'=>'', 'error'=>'', 'csrf_hash'=>'');
		$Return['csrf_hash'] = $this->security->get_csrf_hash();
			
		/* Server side PHP input validation */
		if($this->input->post('role_name')==='') {
        	$Return['error'] = $this->lang->line('xin_role_error_role_name');
		} else if($this->input->post('role_access')==='') {
			$Return['error'] = $this->lang->line('xin_role_error_access');
		}
		
		$role_resources = implode(',',$this->input->post('role_resources'));
						
		if($Return['error']!=''){
       		$this->output($Return);
    	}
	
		$data = array(
		'role_name' => $this->input->post('role_name'),
		'role_access' => $this->input->post('role_access'),
		'role_resources' => $role_resources,
		);	
		
		$result = $this->Roles_model->update_record($data,$id);		
		
		if ($result == TRUE) {
			$Return['result'] = $this->lang->line('xin_role_success_updated');
		} else {
			$Return['error'] = $this->lang->line('xin_error_msg');
		}
		$this->output($Return);
		exit;
		}
	}
	
	public function delete() {
		if($this->input->post('is_ajax')==2) {
			/* Define return | here result is used to return user data and error for error message */
			$Return = array('result'=>'', 'error'=>'', 'csrf_hash'=>'');
			$id = $this->uri->segment(4);
			$Return['csrf_hash'] = $this->security->get_csrf_hash();
			$result = $this->Roles_model->delete_record($id);
			if(isset($id)) {
				$Return['result'] = $this->lang->line('xin_role_success_deleted');
			} else {
				$Return['error'] = $this->lang->line('xin_error_msg');
			}
			$this->output($Return);
		}
	}
}
