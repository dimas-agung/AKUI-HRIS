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

class Skala_upah extends MY_Controller {
	
	public function __construct() {
        parent::__construct();
		//load the model
		$this->load->model("skala_upah_model");
		$this->load->model("Employees_model");
		$this->load->model("Core_model");
		$this->load->model("Skala_upah_model");
		$this->load->model("Location_model");
		$this->load->model("Workstation_model");
		$this->load->model("Company_model");
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
			$data['title']       = $this->lang->line('left_skala_upah').' | '.$this->Core_model->site_title();
			$data['icon']        = '<i class="fa fa-money"></i>';
			$data['desc']        = 'INFORMASI : Data Master Skala Upah';
			$data['breadcrumbs'] = $this->lang->line('left_skala_upah');
			$data['path_url']    = 'skala_upah';

			
			$data['get_all_companies'] = $this->Company_model->get_company();
			$data['get_all_workstation'] = $this->Company_model->get_workstation_skala_upah();
						
			$role_resources_ids = $this->Core_model->user_role_resource();
			if(in_array('0261',$role_resources_ids)) {
				$data['subview'] = $this->load->view("admin/skala_upah/skala_upah_list", $data, TRUE);
				$this->load->view('admin/layout/layout_main', $data); //page load		  
			} else {
				redirect('admin/dashboard');
			}
	    }
	 
	    public function skala_upah_list()
	    {

			$session = $this->session->userdata('username');
			$data['title'] = $this->Core_model->site_title();
			if(!empty($session)){ 
				$this->load->view("admin/skala_upah/skala_upah_list", $data);
			} else {
				redirect('admin/');
			}
			$system = $this->Core_model->read_setting_info(1);
			// Datatables Variables
			$draw = intval($this->input->get("draw"));
			$start = intval($this->input->get("start"));
			$length = intval($this->input->get("length"));
			
			
			$role_resources_ids = $this->Core_model->user_role_resource();
			$user_info = $this->Core_model->read_user_info($session['user_id']);
			
			$company = $this->Company_model->get_companies();
			
			$data = array();
            $no= 1;
	        
	        foreach($company->result() as $r) {

	        	$icname = $r->name;

				$workstation = '';

				$sql_workstation = " SELECT *
							FROM
								 xin_workstation
							WHERE
								1 = 1
							AND company_id  = '".$r->company_id."'	
							AND workstation_name !='-'							
							ORDER BY workstation_name ASC";                                    

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
					                <th class="text-center" width="150px"> Workstation </th>
					                <th class="text-center" > Daftar Tugas</th>
					              </tr>
					            </thead>
					            <tbody>';
	                        	$mo = 1;
                                foreach($query_workstation->result() as $row_workstation):
  									
                                	
                                	$jum_workstation = $this->Employees_model->get_total_employees_workstation($r->company_id, $row_workstation->workstation_id);
									if(!is_null($jum_workstation)){
										$jumlah_workstation = $jum_workstation[0]->jumlah;
									} else {
										$jumlah_workstation = '0';	
									}

									$iworkstation_name = strtoupper($row_workstation->workstation_name);	

									
									$daftar_workstation = $daftar_workstation.' 
									        <tr">
			                                    <td width="2%" align="center">'.$mo.'.</td>
			                                    
			                                    <td align="left">
			                                     '.$iworkstation_name.' <br> Karyawan : '.$jumlah_workstation.'
			                                    </td>				                                    
												
												<td  align="left">';

			                                    	$tugas = '';

													$sql_tugas = " SELECT *  FROM xin_workstation_skala_upah
																	WHERE
																		1 = 1
																	AND workstation_id  = '".$row_workstation->workstation_id."'								
																	ORDER BY skala_upah_name ASC";                                    

													// echo "<pre>";
													// print_r( $sql_tugas );
													// echo "</pre>";
													// die;

													$query_tugas = $this->db->query($sql_tugas);

													if ($query_tugas->num_rows() > 0) {
														

														$tugas ='<table class="datatables-demo table table-striped table-bordered" id="xin_table">
														            <thead>
														              <tr>
														                <th class="text-center" width="50px">No.</th>
														                <th class="text-center" > Tugas Pekerjaan </th>
														                <th class="text-center" colspan="2" width="150px"> Ongkos Kerja </th>
														                <th class="text-center" width="100px"> Aksi</th>
														              </tr>
														              
														            </thead>
														            <tbody>';
										                        	$yo = 1;
									                                foreach($query_tugas->result() as $row_tugas):								  									
																		

																		if(in_array('0263',$role_resources_ids)) { 
																		   // edit
																			$edit = '<span data-toggle="tooltip" data-placement="top" title="'.$this->lang->line('xin_edit').'">
																						<button type="button" class="btn icon-btn btn-xs btn-default waves-effect waves-light"  data-toggle="modal" data-target="#edit-modal-data"  data-skala_upah_id="'. $row_tugas->skala_upah_id . '">
																							<span class="fa fa-pencil"></span> Edit 
																						</button>
																					</span>';
																		} else {
																			$edit = '';
																		}

																		if(in_array('0264',$role_resources_ids)) { 
																		   // delete
																			$delete = '<span data-toggle="tooltip" data-placement="top" title="'.$this->lang->line('xin_delete').'">
																							<button type="button" class="btn icon-btn btn-xs btn-danger waves-effect waves-light delete" data-toggle="modal" data-target=".delete-modal" data-record-id="'. $row_tugas->skala_upah_id . '">
																								<span class="fa fa-trash"></span>
																							</button>
																						</span>';
																		} else {
																			$delete = '';
																		}

																		
																		$tugas_name = strtoupper($row_tugas->skala_upah_name);

																																				
																		$tugas = $tugas.' 
																		        <tr">
												                                    <td width="2%" align="center">'.$yo.'.</td>
												                                  												                                    
												                                    <td align="left">
												                                     '.$tugas_name.' 
												                                    </td>

												                                    <td width="10%"  align="right">
												                                     '.number_format($row_tugas->skala_upah_ongkos, 0, ',', '.').' 
												                                    </td>

												                                    <td width="5%"  align="center">
												                                     / Kg
												                                    </td>
																						
																					<td width="10%" align="center">
												                                     '.$edit.' '.$delete.' 
												                                    </td>
														                        </tr>';
												                    $yo++;
									                          		endforeach;
																	
														$tugas = $tugas.'
																	</tbody>
														            </table>';
													} else {

														$tugas ='<div class="warning-msg" style="padding:5px;">
														                <i class ="fa fa-question-circle"></i> Tidak Ada Tugas		                      
														             </div>';
													}
			                                     
			                                    $daftar_workstation = $daftar_workstation.' '.$tugas .' </td>
												
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
	
	// ======================================================================================================
	// PROSES
	// ======================================================================================================

		public function read()
		{
			$session = $this->session->userdata('username');
			if(empty($session)){ 
				redirect('admin/');
			}
			$data['title'] = $this->Core_model->site_title();
			$id = $this->input->get('skala_upah_id');
			$result = $this->Skala_upah_model->read_skala_upah_information($id);
			$data = array(
					'skala_upah_id' => $result[0]->skala_upah_id,
					'company_id' => $result[0]->company_id,
					'workstation_id' => $result[0]->workstation_id,
					'skala_upah_name' => $result[0]->skala_upah_name,
					'skala_upah_ongkos' => $result[0]->skala_upah_ongkos,
					
					'get_all_companies' => $this->Company_model->get_company(),
					'get_all_workstation' => $this->Company_model->get_workstation_skala_upah()
			);
			if(!empty($session)){ 
				$this->load->view('admin/skala_upah/dialog_skala_upah', $data);
			} else {
				redirect('admin/');
			}
		}
	
		
		public function get_workstations() {

			$data['title'] = $this->Core_model->site_title();
			$id = $this->uri->segment(4);
			
			$data = array(
				'company_id' => $id
				);
			$session = $this->session->userdata('username');
			if(!empty($session)){ 
				$this->load->view("admin/skala_upah/get_workstations", $data);
			} else {
				redirect('admin/');
			}
			// Datatables Variables
			$draw = intval($this->input->get("draw"));
			$start = intval($this->input->get("start"));
			$length = intval($this->input->get("length"));
		}
	
			 	
	 	public function get_model_workstations() {

			$data['title'] = $this->Core_model->site_title();
			$id = $this->uri->segment(4);
			
			$data = array(
				'company_id' => $id
				);
			$session = $this->session->userdata('username');
			if(!empty($session)){ 
				$this->load->view("admin/skala_upah/get_model_workstations", $data);
			} else {
				redirect('admin/');
			}
			// Datatables Variables
			$draw = intval($this->input->get("draw"));
			$start = intval($this->input->get("start"));
			$length = intval($this->input->get("length"));
		}
	 
		
	
	// Validate and add info in database
	public function add_skala_upah() 
	{	
		if($this->input->post('add_type')=='skala_upah') {
			// Check validation for user input
			$this->form_validation->set_rules('workstation_id', 'Workstation', 'trim|required|xss_clean');
			$this->form_validation->set_rules('skala_upah_name', 'Tugas Pekerjaan', 'trim|required|xss_clean');
			
			/* Define return | here result is used to return user data and error for error message */
			$Return = array('result'=>'', 'error'=>'', 'csrf_hash'=>'');
			$Return['csrf_hash'] = $this->security->get_csrf_hash();
			$system = $this->Core_model->read_setting_info(1);
			
			/* Server side PHP input validation */
			
			if($this->input->post('company_id')==='') {
	        	$Return['error'] = $this->lang->line('error_company_field');
			
			} else if($this->input->post('workstation_id')==='') {
				$Return['error'] = $this->lang->line('error_workstation_field');
			
			} else if($this->input->post('skala_upah_name')==='') {
				$Return['error'] = $this->lang->line('error_skala_upah_name_field');
			
			} else if($this->input->post('skala_upah_ongkos')==='') {
				$Return['error'] = $this->lang->line('error_skala_upah_ongkos_field');			
			} 
					
			if($Return['error']!=''){
	       		$this->output($Return);
	    	}
			$data = array(
				
				'company_id'        => $this->input->post('company_id'),				
				'workstation_id'    => $this->input->post('workstation_id'),
				'skala_upah_name'   => $this->input->post('skala_upah_name'),
				'skala_upah_ongkos' => $this->input->post('skala_upah_ongkos'),
				'added_by'          => $this->input->post('user_id'),
				'created_at'        => date('d-m-Y'),
			);

			$result = $this->Skala_upah_model->add($data);
			
			if ($result == TRUE) {
				$Return['result'] =  "Skala Upah Pekerjaan ". $this->input->post('skala_upah_name') ." Berhasil Ditambahkan";
			} else {
				$Return['error'] = $this->lang->line('xin_error_msg');
			}

			$this->output($Return);
			exit;
		}
	}
	
	// Validate and update info in database
	public function update() {
	
		if($this->input->post('edit_type')=='skala_upah') {
			
			$id = $this->uri->segment(4);
			
			// Check validation for user input
			$this->form_validation->set_rules('workstation_id', 'Workstation', 'trim|required|xss_clean');
			$this->form_validation->set_rules('skala_upah_name', 'Tugas Pekerjaan', 'trim|required|xss_clean');
			
			/* Define return | here result is used to return user data and error for error message */
			$Return = array('result'=>'', 'error'=>'', 'csrf_hash'=>'');
			$Return['csrf_hash'] = $this->security->get_csrf_hash();
			$system = $this->Core_model->read_setting_info(1);
			/* Server side PHP input validation */
			if($this->input->post('company_id')==='') {
	        	$Return['error'] = $this->lang->line('error_company_field');
			
			} else if($this->input->post('workstation_id')==='') {
				$Return['error'] = $this->lang->line('error_workstation_field');
			
			} else if($this->input->post('skala_upah_name')==='') {
				$Return['error'] = $this->lang->line('error_skala_upah_name_field');
			
			} else if($this->input->post('skala_upah_ongkos')==='') {
				$Return['error'] = $this->lang->line('error_skala_upah_ongkos_field');			
			} 
					
			if($Return['error']!=''){
	       		$this->output($Return);
	    	}
			$data = array(				
				'company_id'        => $this->input->post('company_id'),				
				'workstation_id'    => $this->input->post('workstation_id'),
				'skala_upah_name'   => $this->input->post('skala_upah_name'),
				'skala_upah_ongkos' => $this->input->post('skala_upah_ongkos')		
			);
			$result = $this->Skala_upah_model->update_record($data,$id);		
			
			if ($result == TRUE) {
				$Return['result'] = "Skala Upah Pekerjaan ". $this->input->post('skala_upah_name') ." Berhasil Diperbaharui";
			} else {
				$Return['error'] = $this->lang->line('xin_error_msg');
			}
			$this->output($Return);
			exit;
		}
	}
	
	public function delete() {
		
		if($this->input->post('is_ajax')==2) {
			$session = $this->session->userdata('username');
			if(empty($session)){ 
				redirect('admin/');
			}
			/* Define return | here result is used to return user data and error for error message */
			$Return = array('result'=>'', 'error'=>'', 'csrf_hash'=>'');
			$id = $this->uri->segment(4);
			$Return['csrf_hash'] = $this->security->get_csrf_hash();					

			$result = $this->Skala_upah_model->delete_record($id);
			if(isset($id)) {
				$Return['result'] = "Skala Upah Berhasil Dihapus";
			} else {
				$Return['error'] = $this->lang->line('xin_error_msg');
			}
			
			$this->output($Return);
		}
	}
}
