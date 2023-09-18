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

class Training_posisi extends MY_Controller {
	
	 public function __construct() {
        parent::__construct();
		//load the model
		$this->load->model("Training_model");
		$this->load->model("Core_model");
		$this->load->model("Trainers_model");
		
		$this->load->model("Company_model");
		$this->load->model("Department_model");
		$this->load->model("Designation_model");

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
		$data['title']           = 'Posisi Dilatih | '.$this->Core_model->site_title();
		$data['desc']            = 'INPUT : Posisi Dilatih';
		$data['icon']            = '<i class="fa fa-tasks"></i>';
		$data['breadcrumbs']     = 'Posisi Dilatih ';
		$data['path_url']        = 'training_posisi';
		
		
		$data['get_all_companies'] = $this->Company_model->get_company();
		
		$data['all_departments']   = $this->Department_model->all_departments();
		
		$data['all_designations'] = $this->Designation_model->all_designations();

		$data['all_training_types'] = $this->Training_model->all_training_types();

		$role_resources_ids = $this->Core_model->user_role_resource();
		if(in_array('55',$role_resources_ids)) {
			if(!empty($session)){
				$data['subview'] = $this->load->view("admin/training/training_posisi", $data, TRUE);
				$this->load->view('admin/layout/layout_main', $data); //page load
			} else {
				redirect('admin/');
			}
		} else {
			redirect('admin/dashboard');
		}
     }
 	
	public function posisi_list()
    {

		$data['title'] = $this->Core_model->site_title();
		$session = $this->session->userdata('username');
		if(!empty($session)){ 
			$this->load->view("admin/training/training_posisi", $data);
		} else {
			redirect('admin/');
		}
		// Datatables Variables
		$draw = intval($this->input->get("draw"));
		$start = intval($this->input->get("start"));
		$length = intval($this->input->get("length"));
		
		$role_resources_ids = $this->Core_model->user_role_resource();
		$user_info = $this->Core_model->read_user_info($session['user_id']);			
		
		$company = $this->Company_model->get_training_jenis();			
		
		$data = array();
		$no = 1;
        
        foreach($company->result() as $r) {

        	$cek_kategori = $this->Training_model->read_kategori_information($r->kategori);
			if(!is_null($cek_kategori)){
				$kategori_name = $cek_kategori[0]->type;
			} else {
				$kategori_name = '<span class="badge bg-red"> ? </span>';	
			}
					
			$icname = $r->type.'<br>>> '.$kategori_name;

			$posisi = '';

			$sql_posisi = " SELECT *
						FROM
							 xin_training_posisi
						WHERE
							1 = 1
						AND type_id  = '".$r->training_type_id."'								
						ORDER BY designation_id ASC";                                    

			// echo "<pre>";
			// print_r( $sql_posisi );
			// echo "</pre>";
			// die;

			$query_posisi = $this->db->query($sql_posisi);

			if ($query_posisi->num_rows() > 0) {
				

				$daftar_posisi ='<table class="datatables-demo table table-striped table-bordered" id="xin_table">
				            <thead>
				              <tr>
				                <th class="text-center" width="50px">No.</th>			                
				                <th class="text-center"> Posisi </th>
				                <th class="text-center"> Departemen </th>
				                <th class="text-center"> Perusahaan </th>
				                <th class="text-center" width="100px"> Aksi</th>
				              </tr>
				            </thead>
				            <tbody>';
                        	$mo = 1;
                            foreach($query_posisi->result() as $row_posisi):									
									
                            	//edit
                            	if(in_array('552',$role_resources_ids)) { 
									$edit = '<span data-toggle="tooltip" data-placement="top" title="'.$this->lang->line('xin_edit').'">
												<button type="button" class="btn icon-btn btn-xs btn-default waves-effect waves-light"  data-toggle="modal" data-target="#edit-modal-data-min"  data-training_posisi_id="'. $row_posisi->training_posisi_id . '">
													<span class="fa fa-pencil"></span> Edit 
												</button>
											</span></span>';
								} else {
									$edit = '';
								}
								// delete
								if(in_array('553',$role_resources_ids)) { 
									$delete = '<span data-toggle="tooltip" data-placement="top" title="'.$this->lang->line('xin_delete').'">
													<button type="button" class="btn icon-btn btn-xs btn-danger waves-effect waves-light delete" data-toggle="modal" data-target=".delete-modal" data-record-id="'. $row_posisi->training_posisi_id . '">
														<span class="fa fa-times"></span> Hapus
													</button>
												</span>';
								} else {
									$delete = '';
								}

								// get designation
								$designation = $this->Designation_model->read_designation_information($row_posisi->designation_id);
								if(!is_null($designation)){
									$designation_name = $designation[0]->designation_name;
								} else {
									$designation_name = '<span class="badge bg-red"> ? </span>';	
								}

								// department
								$department = $this->Department_model->read_department_information($row_posisi->department_id);
								if(!is_null($department)){
									$department_name = $department[0]->department_name;
								} else {
									$department_name = '<span class="badge bg-red"> ? </span>';
								}
							
								$cek_company = $this->Core_model->read_company_info($row_posisi->company_id);
								if(!is_null($cek_company)){
									$comp_name = $cek_company[0]->name;
								} else {
									$comp_name = '?';	
								}
													
								$daftar_posisi = $daftar_posisi.' 
								        <tr">
		                                    <td width="2%" align="center">'.$mo.'.</td>
		                                    
		                                    <td width="12%"  align="left">
		                                     '.$designation_name.' 
		                                    </td>				                                    
											
											 <td width="12%"  align="left">
		                                     '.$department_name.' 
		                                    </td>

		                                     <td width="12%"  align="left">
		                                   	   '.$comp_name.'
		                                    </td>
											
											<td width="4%" align="center">
		                                     '.$delete.' 
		                                    </td>
				                        </tr>';
		                    $mo++;
                      		endforeach;
							
				$daftar_posisi = $daftar_posisi.'
							</tbody>
				            </table>';
			} else {

				$daftar_posisi ='<div class="warning-msg" style="padding:5px;">
				                <i class ="fa fa-question-circle"></i> Tidak Ada Posisi Dilatih		                      
				             </div>';
			}

		    $data[] = array(
				$no,
				$icname,
				$daftar_posisi
				
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

    
	 
	public function read()
	{
		$data['title'] = $this->Core_model->site_title();
		$id = $this->input->get('training_posisi_id');
		$result = $this->Training_model->read_training_posisi_information($id);
		$data = array(
				'training_posisi_id' => $result[0]->training_posisi_id,
				'company_id'         => $result[0]->company_id,
				'department_id'      => $result[0]->department_id,
				'designation_id'     => $result[0]->designation_id,
				'type_id'            => $result[0]->type_id,

				'all_companies'      => $this->Company_model->get_company(),		
				'all_departments'    => $this->Department_model->all_departments(),				
				'all_designations'   => $this->Designation_model->all_designations(),
				'all_training_types' => $this->Training_model->all_training_types()
		);
		$session = $this->session->userdata('username');
		if(!empty($session)){ 
			$this->load->view('admin/training/dialog_training_posisi', $data);
		} else {
			redirect('admin/');
		}
	}
	
	// =======================================================================================================
	// PROSES
	// ========================================================================================================
	
		public function add_type() {
		
			if($this->input->post('add_type')=='training') {		
				/* Define return | here result is used to return user data and error for error message */
				$Return = array('result'=>'', 'error'=>'', 'csrf_hash'=>'');
				$Return['csrf_hash'] = $this->security->get_csrf_hash();
					
				/* Server side PHP input validation */		
				if($this->input->post('company_id')==='') {
		        	$Return['error'] = 'Perusahaan Belum Diisi';
				
				} else if($this->input->post('department_id')==='') {
		        	$Return['error'] = 'Departemen wajib diisi';
				
				} else if($this->input->post('designation_id')==='') {
		        	$Return['error'] = 'Posisi wajib diisi';
				
				} else if($this->input->post('type_id')==='') {
		        	$Return['error'] = 'Jenis Pelatihan wajib diisi';
				}
						
				if($Return['error']!=''){
		       		$this->output($Return);
		    	}
			
				$data = array(
					'company_id'      => $this->input->post('company_id'),
					'department_id'   => $this->input->post('department_id'),
					'designation_id'  => $this->input->post('designation_id'),
					'type_id'         => $this->input->post('type_id'),
					'created_at'      => date('Y-m-d h:i:s')
				);
				$result = $this->Training_model->add_posisi($data);
				if ($result == TRUE) {
					$Return['result'] = 'Posisi Dilatih Berhasil Disimpan';
				} else {
					$Return['error'] = $this->lang->line('xin_error_msg');
				}
				$this->output($Return);
				exit;
			}
		}
			
		public function update() {
		
			if($this->input->post('edit_type')=='training') {
				
				$id = $this->uri->segment(4);
				
				/* Define return | here result is used to return user data and error for error message */
				$Return = array('result'=>'', 'error'=>'', 'csrf_hash'=>'');
				$Return['csrf_hash'] = $this->security->get_csrf_hash();
				
				/* Server side PHP input validation */		
				if($this->input->post('company_id')==='') {
		        	$Return['error'] = 'Perusahaan Belum Diisi';
				
				} else if($this->input->post('department_id')==='') {
		        	$Return['error'] = 'Departemen wajib diisi';
				
				} else if($this->input->post('designation_id')==='') {
		        	$Return['error'] = 'Posisi wajib diisi';
				
				} else if($this->input->post('type_id')==='') {
		        	$Return['error'] = 'Jenis Pelatihan wajib diisi';
				}
						
				if($Return['error']!=''){
		       		$this->output($Return);
		    	}
			
				$data = array(
					'company_id'      => $this->input->post('company_id'),
					'department_id'   => $this->input->post('department_id'),
					'designation_id'  => $this->input->post('designation_id'),
					'type_id'         => $this->input->post('type_id'),
				);
				
				$result = $this->Training_model->update_posisi_record($data,$id);		
				
				if ($result == TRUE) {
					$Return['result'] =  'Posisi Dilatih Berhasil Diperbarui';
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
			$result = $this->Training_model->delete_posisi_record($id);
			if(isset($id)) {
				$Return['result'] = 'Posisi Dilatih Berhasil Dihapus';
			} else {
				$Return['error'] = $this->lang->line('xin_error_msg');
			}
			$this->output($Return);
		}

	// =======================================================================================================
	// COMBO
	// ========================================================================================================

		public function get_departments() {

			$data['title'] = $this->Core_model->site_title();
			$id = $this->uri->segment(4);
			
			$data = array(
				'company_id' => $id
				);
			$session = $this->session->userdata('username');
			if(!empty($session)){ 
				$this->load->view("admin/training/get_departments", $data);
			} else {
				redirect('admin/');
			}
			// Datatables Variables
			$draw = intval($this->input->get("draw"));
			$start = intval($this->input->get("start"));
			$length = intval($this->input->get("length"));
		}
	    
	    public function get_designation() {

			$data['title'] = $this->Core_model->site_title();
			$id = $this->uri->segment(4);
			
			$data = array(
				'department_id' => $id
			);

			$session = $this->session->userdata('username');
			if(!empty($session)){ 
				$this->load->view("admin/training/get_designation", $data);
			} else {
				redirect('admin/');
			}
			// Datatables Variables
			$draw = intval($this->input->get("draw"));
			$start = intval($this->input->get("start"));
			$length = intval($this->input->get("length"));
		}
}
