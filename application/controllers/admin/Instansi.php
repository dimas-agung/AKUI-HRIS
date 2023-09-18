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

class Instansi extends MY_Controller {
	
	public function __construct() {
        parent::__construct();
		//load the model
		$this->load->model("Instansi_model");
		$this->load->model("Employees_model");
		$this->load->model("instansi_model");
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
			$data['title']       = 'Instansi Perusahaan | '.$this->Core_model->site_title();
			$data['icon']        = '<i class="fa fa-building"></i>';
			$data['breadcrumbs'] = 'Instansi Perusahaan';
			$data['path_url']    = 'Instansi';

			
			$data['get_all_companies'] = $this->Company_model->get_company();
			
			
			$role_resources_ids = $this->Core_model->user_role_resource();
			if(in_array('0420',$role_resources_ids)) {
				if(!empty($session)){ 
				$data['subview'] = $this->load->view("admin/instansi/instansi_list", $data, TRUE);
				$this->load->view('admin/layout/layout_main', $data); //page load
				} else {
					redirect('admin/');
				}
			} else {
				redirect('admin/dashboard');
			}
	    }
	 	
	 	public function instansi_list()
	    {

			$data['title'] = $this->Core_model->site_title();
			$session = $this->session->userdata('username');
			if(!empty($session)){ 
				$this->load->view("admin/instansi/instansi_list", $data);
			} else {
				redirect('admin/');
			}
			// Datatables Variables
			$draw = intval($this->input->get("draw"));
			$start = intval($this->input->get("start"));
			$length = intval($this->input->get("length"));
			
			$role_resources_ids = $this->Core_model->user_role_resource();
			$user_info = $this->Core_model->read_user_info($session['user_id']);			
			
			$company = $this->Instansi_model->get_instansi();			
			
			$data = array();
			$no = 1;
	        
	        foreach($company->result() as $r) {
				
				$Instansi_Name    = strtoupper($r->instansi_name);
				$instansi_address    = strtoupper($r->instansi_address);	
				$instansi_phone   = strtoupper($r->instansi_phone);
				$instansi_contact = strtoupper($r->instansi_contact);

				if(in_array('0253',$role_resources_ids)) { //edit
					$edit = '<span data-toggle="tooltip" data-placement="top" title="'.$this->lang->line('xin_edit').'">
								<button type="button" class="btn icon-btn btn-xs btn-default waves-effect waves-light"  data-toggle="modal" data-target="#edit-modal-data-min"  data-instansi_id="'. $r->instansi_id . '">
									<span class="fa fa-pencil"></span> Edit 
								</button>
							</span></span>';
				} else {
					$edit = '';
				}

				if(in_array('0254',$role_resources_ids)) { // delete
					$delete = '<span data-toggle="tooltip" data-placement="top" title="'.$this->lang->line('xin_delete').'">
									<button type="button" class="btn icon-btn btn-xs btn-danger waves-effect waves-light delete" data-toggle="modal" data-target=".delete-modal" data-record-id="'. $r->instansi_id . '">
										<span class="fa fa-times"></span>
									</button>
								</span>';
				} else {
					$delete = '';
				}
								


				$combhr = $edit.$delete;
			    $data[] = array(
					$combhr,
					$Instansi_Name,
					$instansi_address,
					$instansi_phone,
					$instansi_contact
					
			    );
			    $no++;
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
		// Read
		public function read()
		{
			$session = $this->session->userdata('username');
			if(empty($session)){ 
				redirect('admin/');
			}
			
			$data['title'] = $this->Core_model->site_title();
			
			$id = $this->input->get('instansi_id');
	        
	        // $data['all_countries'] = $this->xin_model->get_countries();
			
			$result = $this->Instansi_model->read_instansi_information($id);
			
			$data = array(
				'instansi_id'       => $result[0]->instansi_id,					
				'instansi_name'     => $result[0]->instansi_name,
				'instansi_address'  => $result[0]->instansi_address,
				'instansi_phone'    => $result[0]->instansi_phone,
				'instansi_contact'  => $result[0]->instansi_contact				
			);

			if(!empty($session)){ 
				$this->load->view('admin/instansi/dialog_instansi', $data);
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
			
			$id = $this->input->get('instansi_id');
	        
	        // $data['all_countries'] = $this->xin_model->get_countries();
			
			$result = $this->Instansi_model->read_instansi_information($id);
			
			$data = array(
								
				'instansi_id'       => $result[0]->instansi_id,					
				'instansi_name'     => $result[0]->instansi_name,
				'instansi_address'  => $result[0]->instansi_address,
				'instansi_phone'    => $result[0]->instansi_phone,
				'instansi_contact'  => $result[0]->instansi_contact
			);

			if(!empty($session)){ 
				$this->load->view('admin/instansi/view_instansi', $data);
			} else {
				redirect('admin/');
			}
		}
		// Tambah		
		public function add_instansi() 
		{
		
			if($this->input->post('add_type')=='instansi') {
				
				// Check validation for user input				
				$this->form_validation->set_rules('instansi_name', 'Nama Instansi', 'trim|required|xss_clean');
				$this->form_validation->set_rules('instansi_address', 'Alamat Instansi', 'trim|required|xss_clean');
				$this->form_validation->set_rules('instansi_phone', 'No.Telp Instansi', 'trim|required|xss_clean');
				$this->form_validation->set_rules('instansi_contact', 'Kontak Instansi', 'trim|required|xss_clean');
				
				/* Define return | here result is used to return user data and error for error message */
				$Return = array('result'=>'', 'error'=>'', 'csrf_hash'=>'');
				$Return['csrf_hash'] = $this->security->get_csrf_hash();
					
				/* Server side PHP input validation */					
				if($this->input->post('instansi_name')==='') {
					$Return['error'] = 'Nama Instansi Belum Diisi';	

				} else if($this->input->post('instansi_address')==='') {
					$Return['error'] = 'Alamat Instansi Belum Diisi';	

				} else if($this->input->post('instansi_phone')==='') {
					$Return['error'] = 'No.Telp Instansi Belum Diisi';

				} else if($this->input->post('instansi_contact')==='') {
					$Return['error'] = 'Kontak Instansi Belum Diisi';	
				}

						
				if($Return['error']!=''){
		       		$this->output($Return);
		    	}
			
				$data = array(			
					
					'instansi_name'    => $this->input->post('instansi_name'),	
					'instansi_address' => $this->input->post('instansi_address'),	
					'instansi_phone'   => $this->input->post('instansi_phone'),			
					'instansi_contact' => $this->input->post('instansi_contact'),	

					'created_by'       => $this->input->post('user_id'),
					'created_at'       => date('Y-m-d'),			
				);
				$result = $this->Instansi_model->add($data);
				if ($result == TRUE) {
					$Return['result'] = 'Instansi Baru Berhasil Ditambahkan';
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

			if($this->input->post('edit_type')=='instansi') {
				
				$id = $this->uri->segment(4);
				
				// Check validation for user input
				
				$this->form_validation->set_rules('instansi_name', 'Nama Instansi', 'trim|required|xss_clean');
				$this->form_validation->set_rules('instansi_address', 'Alamat Instansi', 'trim|required|xss_clean');
				$this->form_validation->set_rules('instansi_phone', 'No.Telp Instansi', 'trim|required|xss_clean');
				$this->form_validation->set_rules('instansi_contact', 'Kontak Instansi', 'trim|required|xss_clean');
				
				
				/* Define return | here result is used to return user data and error for error message */
				$Return = array('result'=>'', 'error'=>'', 'csrf_hash'=>'');
				$Return['csrf_hash'] = $this->security->get_csrf_hash();
					
				/* Server side PHP input validation */
				if($this->input->post('instansi_name')==='') {
					$Return['error'] = 'Nama Instansi Belum Diisi';	

				} else if($this->input->post('instansi_address')==='') {
					$Return['error'] = 'Alamat Instansi Belum Diisi';	

				} else if($this->input->post('instansi_phone')==='') {
					$Return['error'] = 'No.Telp Instansi Belum Diisi';

				} else if($this->input->post('instansi_contact')==='') {
					$Return['error'] = 'Kontak Instansi Belum Diisi';	
				}
						
				if($Return['error']!=''){
		       		$this->output($Return);
		    	}
			
				$data = array(			
				
					'instansi_name'    => $this->input->post('instansi_name'),	
					'instansi_address' => $this->input->post('instansi_address'),	
					'instansi_phone'   => $this->input->post('instansi_phone'),			
					'instansi_contact' => $this->input->post('instansi_contact')
				);	
				
				$result = $this->Instansi_model->update_record($data,$id);		
				
				if ($result == TRUE) {
					$Return['result'] = 'Instansi '.$this->input->post('instansi_name').' Berhasil Diperbaharui';
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
				$id     = $this->uri->segment(4);
				$Return['csrf_hash'] = $this->security->get_csrf_hash();
				$result = $this->Instansi_model->delete_record($id);
				if(isset($id)) {
					$Return['result'] = 'Instansi Berhasil Dihapus';
				} else {
					$Return['error'] = $this->lang->line('xin_error_msg');
				}
				$this->output($Return);
			}
		}

	
		 
	// ============================================================================================
	// END
	// ============================================================================================
}
