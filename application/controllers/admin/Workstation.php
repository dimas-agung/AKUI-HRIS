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

class Workstation extends MY_Controller {
	
	public function __construct() {
        parent::__construct();
		//load the model
		$this->load->model("Workstation_model");
		$this->load->model("Employees_model");
		$this->load->model("workstation_model");
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
			$data['title']       = $this->lang->line('xin_workstations').' | '.$this->Core_model->site_title();
			$data['icon']        = '<i class="fa fa-filter"></i>';
			$data['breadcrumbs'] = $this->lang->line('xin_workstations');
			$data['path_url']    = 'Workstation';

			
			$data['get_all_companies'] = $this->Company_model->get_company();
			
			
			$role_resources_ids = $this->Core_model->user_role_resource();
			if(in_array('0251',$role_resources_ids)) {
				if(!empty($session)){ 
				$data['subview'] = $this->load->view("admin/workstation/workstation_list", $data, TRUE);
				$this->load->view('admin/layout/layout_main', $data); //page load
				} else {
					redirect('admin/');
				}
			} else {
				redirect('admin/dashboard');
			}
	    }
	 	
	 	public function workstation_list()
	    {

			$data['title'] = $this->Core_model->site_title();
			$session = $this->session->userdata('username');
			if(!empty($session)){ 
				$this->load->view("admin/workstation/workstation_list", $data);
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
			$no = 1;
	        
	        foreach($company->result() as $r) {
						
				$icname = $r->name;

				$workstation = '';

				$sql_workstation = " SELECT *
							FROM
								 xin_workstation
							WHERE
								1 = 1
							AND company_id  = '".$r->company_id."'								
							ORDER BY workstation_id ASC";                                    

				// echo "<pre>";
				// print_r( $sql_workstation );
				// echo "</pre>";
				// die;

				$query_workstation = $this->db->query($sql_workstation);

				if ($query_workstation->num_rows() > 0) {
					

					$daftar_workstation ='<table class="datatables-demo table table-striped table-bordered" id="xin_table">
					            <thead>
					              <tr>
					                <th class="text-center" width="50px">No.</th>			                
					                <th class="text-center" > Workstation </th>
					                <th class="text-center" width="100px"> Aksi</th>
					              </tr>
					            </thead>
					            <tbody>';
	                        	$mo = 1;
                                foreach($query_workstation->result() as $row_workstation):
  									
  									

									// $jum_karyawan = $this->Employees_model->get_total_employees_departemen($row_workstation->workstation_id);
									// if(!is_null($jum_karyawan)){
									// 	$jumlah_karyawan = $jum_karyawan[0]->jumlah;
									// } else {
									// 	$jumlah_karyawan = '--';	
									// }
                                	 if(in_array('0253',$role_resources_ids)) { //edit
										$edit = '<span data-toggle="tooltip" data-placement="top" title="'.$this->lang->line('xin_edit').'">
													<button type="button" class="btn icon-btn btn-xs btn-default waves-effect waves-light"  data-toggle="modal" data-target="#edit-modal-data-min"  data-workstation_id="'. $row_workstation->workstation_id . '">
														<span class="fa fa-pencil"></span> Edit 
													</button>
												</span></span>';
									} else {
										$edit = '';
									}

									if(in_array('0254',$role_resources_ids)) { // delete
										$delete = '<span data-toggle="tooltip" data-placement="top" title="'.$this->lang->line('xin_delete').'">
														<button type="button" class="btn icon-btn btn-xs btn-danger waves-effect waves-light delete" data-toggle="modal" data-target=".delete-modal" data-record-id="'. $row_workstation->workstation_id . '">
															<span class="fa fa-times"></span>
														</button>
													</span>';
									} else {
										$delete = '';
									}
								
									$iworkstation_name = strtoupper($row_workstation->workstation_name);	

									
									$daftar_workstation = $daftar_workstation.' 
									        <tr">
			                                    <td width="2%" align="center">'.$mo.'.</td>
			                                    
			                                    <td align="left">
			                                     '.$iworkstation_name.' 
			                                    </td>				                                    
												
												
												<td width="8%" align="center">
			                                     '.$edit.$delete.' 
			                                    </td>
					                        </tr>';
			                    $mo++;
                          		endforeach;
								
					$daftar_workstation = $daftar_workstation.'
								</tbody>
					            </table>';
				} else {

					$daftar_workstation ='<div class="warning-msg" style="padding:5px;">
					                <i class ="fa fa-question-circle"></i> Tidak Ada Workstation		                      
					             </div>';
				}

			    $data[] = array(
					$no,
					$icname,
					$daftar_workstation
					
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
			$id = $this->input->get('workstation_id');
	       // $data['all_countries'] = $this->xin_model->get_countries();
			$result = $this->Workstation_model->read_workstation_information($id);
			$data = array(
				'company_id' => $result[0]->company_id,
				'workstation_id' => $result[0]->workstation_id,					
				'workstation_name' => $result[0]->workstation_name,
				'get_all_companies' => $this->Company_model->get_company()					
			);
			if(!empty($session)){ 
				$this->load->view('admin/workstation/dialog_workstation', $data);
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
			$id = $this->input->get('workstation_id');
	       // $data['all_countries'] = $this->xin_model->get_countries();
			$result = $this->Workstation_model->read_workstation_information($id);
			$data = array(
				'company_id'   => $result[0]->company_id,
				'workstation_id'   => $result[0]->workstation_id,					
				'workstation_name' => $result[0]->workstation_name,
				'get_all_companies' => $this->Company_model->get_company()					
			);
			if(!empty($session)){ 
				$this->load->view('admin/workstation/view_workstation', $data);
			} else {
				redirect('admin/');
			}
		}
		// Tambah		
		public function add_workstation() 
		{
		
			if($this->input->post('add_type')=='workstation') {
				// Check validation for user input
				
				$this->form_validation->set_rules('name', 'Name', 'trim|required|xss_clean');
				
				/* Define return | here result is used to return user data and error for error message */
				$Return = array('result'=>'', 'error'=>'', 'csrf_hash'=>'');
				$Return['csrf_hash'] = $this->security->get_csrf_hash();
					
				/* Server side PHP input validation */
				if($this->input->post('name')==='') {
					$Return['error'] = $this->lang->line('xin_error_name_field');			
				} else if($this->input->post('company_id')==='') {
					$Return['error'] = $this->lang->line('error_company_field');
				}
						
				if($Return['error']!=''){
		       		$this->output($Return);
		    	}
			
				$data = array(			
					'company_id' => $this->input->post('company_id'),
					'workstation_name' => $this->input->post('name'),			
					'added_by' => $this->input->post('user_id'),
					'created_at' => date('Y-m-d'),			
				);
				$result = $this->Workstation_model->add($data);
				if ($result == TRUE) {
					$Return['result'] = $this->lang->line('xin_success_add_workstation');
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
			if($this->input->post('edit_type')=='workstation') {
				
				$id = $this->uri->segment(4);
				
				// Check validation for user input
				
				$this->form_validation->set_rules('name', 'Name', 'trim|required|xss_clean');
				
				/* Define return | here result is used to return user data and error for error message */
				$Return = array('result'=>'', 'error'=>'', 'csrf_hash'=>'');
				$Return['csrf_hash'] = $this->security->get_csrf_hash();
					
				/* Server side PHP input validation */
				if($this->input->post('name')==='') {
					$Return['error'] = $this->lang->line('xin_error_name_field');			
				} else if($this->input->post('company_id')==='') {
					$Return['error'] = $this->lang->line('error_company_field');
				}
						
				if($Return['error']!=''){
		       		$this->output($Return);
		    	}
			
				$data = array(
					'company_id' => $this->input->post('company_id'),
					'workstation_name' => $this->input->post('name')
						
				);	
				
				$result = $this->Workstation_model->update_record($data,$id);		
				
				if ($result == TRUE) {
					$Return['result'] = $this->lang->line('xin_success_update_workstation');
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
				$result = $this->Workstation_model->delete_record($id);
				if(isset($id)) {
					$Return['result'] = $this->lang->line('xin_success_delete_workstation');
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
