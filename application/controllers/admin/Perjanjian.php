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

class perjanjian extends MY_Controller {
	
	public function __construct() {
        parent::__construct();
		//load the model
		$this->load->model("Perjanjian_model");
		$this->load->model("Employees_model");
		$this->load->model("Perjanjian_model");
		$this->load->model("Instansi_model");
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
			$data['title']       = 'Perjanjian | '.$this->Core_model->site_title();
			$data['icon']        = '<i class="fa fa-files-o"></i>';
			$data['desc']        = 'Proses : Input Perjanjian';
			$data['breadcrumbs'] = 'Perjanjian';
			$data['path_url']    = 'perjanjian';

			
			$data['get_all_perjanjian_type'] = $this->Core_model->get_perjanjian_type_combo();
			
			
			$role_resources_ids = $this->Core_model->user_role_resource();
			if(in_array('0430',$role_resources_ids)) {
				if(!empty($session)){ 
				$data['subview'] = $this->load->view("admin/perjanjian/perjanjian_list", $data, TRUE);
				$this->load->view('admin/layout/layout_main', $data); //page load
				} else {
					redirect('admin/');
				}
			} else {
				redirect('admin/dashboard');
			}
	    }
	 	
	 	public function perjanjian_list()
	    {

			$data['title'] = $this->Core_model->site_title();
			$session = $this->session->userdata('username');
			if(!empty($session)){ 
				$this->load->view("admin/perjanjian/perjanjian_list", $data);
			} else {
				redirect('admin/');
			}
			// Datatables Variables
			$draw = intval($this->input->get("draw"));
			$start = intval($this->input->get("start"));
			$length = intval($this->input->get("length"));
			
			$role_resources_ids = $this->Core_model->user_role_resource();
			$user_info = $this->Core_model->read_user_info($session['user_id']);			
			
			$company = $this->Core_model->get_perjanjian_type();			
			
			$data = array();
			$no = 1;
	        
	        foreach($company->result() as $r) {
						
				$icname = strtoupper($r->perjanjian_type_name);

				$perjanjian = '';

				$sql_perjanjian = " SELECT *
							FROM
								 xin_perjanjian_applications
							WHERE
								1 = 1
							AND perjanjian_type_id  = '".$r->perjanjian_type_id."'								
							ORDER BY start_date DESC";                                    

				// echo "<pre>";
				// print_r( $sql_perjanjian );
				// echo "</pre>";
				// die;

				$query_perjanjian = $this->db->query($sql_perjanjian);

				if ($query_perjanjian->num_rows() > 0) {
					

					$daftar_perjanjian ='<table class="datatables-demo table table-striped table-bordered" id="xin_table">
					            <thead>
					              <tr>
					                <th class="text-center" width="50px">No.</th>
					                <th class="text-center" > Info Perjanjian </th>
					                 <th class="text-center" > Pihak 1 </th>
					                  <th class="text-center" > Pihak 2 </th>
					                <th class="text-center" > Tanggal Dimulai </th>
					                <th class="text-center" > Tanggal Sampai </th>	
					                <th class="text-center" > Status </th>					                 
					                <th class="text-center" width="100px"> Aksi</th>
					              </tr>
					            </thead>
					            <tbody>';
	                        	$mo = 1;
                                foreach($query_perjanjian->result() as $row_perjanjian):
  									
  									
                                	 if(in_array('0432',$role_resources_ids)) { //edit
										$edit = '<span data-toggle="tooltip" data-placement="top" title="'.$this->lang->line('xin_edit').'">
													<button type="button" class="btn icon-btn btn-xs btn-default waves-effect waves-light"  data-toggle="modal" data-target="#edit-modal-data-max"  data-perjanjian_id="'. $row_perjanjian->perjanjian_id . '">
														<span class="fa fa-pencil"></span> Edit 
													</button>
												</span></span>';
									} else {
										$edit = '';
									}

									if(in_array('0433',$role_resources_ids)) { // delete
										$delete = '<span data-toggle="tooltip" data-placement="top" title="'.$this->lang->line('xin_delete').'">
														<button type="button" class="btn icon-btn btn-xs btn-danger waves-effect waves-light delete" data-toggle="modal" data-target=".delete-modal" data-record-id="'. $row_perjanjian->perjanjian_id . '">
															<span class="fa fa-times"></span>
														</button>
													</span>';
									} else {
										$delete = '';
									}
								
									
									
									$perjanjian_nama          = strtoupper($row_perjanjian->perjanjian_nama);
									$perjanjian_item          = ucfirst($row_perjanjian->perjanjian_item);
									$perjanjian_pihak_1          = strtoupper($row_perjanjian->perjanjian_pihak_1);
									$perjanjian_pihak_2          = strtoupper($row_perjanjian->perjanjian_pihak_2);
									
									date_default_timezone_set("Asia/Jakarta");                            
               
                    				$now_date         = date("Y-m-d");

									$start_date       = date("d-m-Y", strtotime($row_perjanjian->start_date));
									$end_date         = date("d-m-Y", strtotime($row_perjanjian->end_date));

									if ($now_date > date("Y-m-d", strtotime($row_perjanjian->end_date)) ) {
				                    	$status ='<span class="badge btn-danger" style="padding: 5px;font-size: 11px;"><i class="fa fa-times"></i> <b>Sudah Berakhir</b> </span>';
				                    	$warna ='#e3cec1';
				                    } else {
				                    	$status ='<span class="badge btn-primary" style="padding: 5px;font-size: 11px;"><i class="fa fa-check"></i> <b>Berlaku</b> </span>';
				                    	$warna ='#c1e3cd';
				                    }

				                    if ($row_perjanjian->perjanjian_no != ''){
				                    	$perjanjian_no = ucfirst($row_perjanjian->perjanjian_no) ;
				                    } else {
				                    	$perjanjian_no = '<span class="badge bg-red"> ? </span>';
				                    }

									$daftar_perjanjian = $daftar_perjanjian.' 
									        <tr style ="background-color: '.$warna.' ">
			                                    <td width="2%" align="center">'.$mo.'.</td>
			                                    
			                                    <td align="left">
			                                     '.$perjanjian_item.'<br>
			                                     <small> <i class="fa fa-angle-double-right"></i> No: '.$perjanjian_no.' </small>
			                                    
			                                    </td>				                                    
												
												<td width="20%" align="left">
			                                     '.$perjanjian_pihak_1.' 
			                                    </td>

			                                    <td width="20%" align="left">
			                                     '.$perjanjian_pihak_2.' 
			                                    </td>

												<td width="8%" align="center">
			                                     '.$start_date.' 
			                                    </td>

			                                    <td width="8%" align="center">
			                                     '.$end_date.' 
			                                    </td>

			                                    <td width="8%" align="center">
			                                     '.$status.' 
			                                    </td>		                                   
												
												<td width="8%" align="center">
			                                     '.$edit.$delete.' 
			                                    </td>
					                        </tr>';
			                    $mo++;
                          		endforeach;
								
					$daftar_perjanjian = $daftar_perjanjian.'
								</tbody>
					            </table>';
				} else {

					$daftar_perjanjian ='<div class="warning-msg" style="padding:5px;">
					                <i class ="fa fa-question-circle"></i> Tidak Ada Perjanjian		                      
					             </div>';
				}

			    $data[] = array(
					$no,
					$icname,
					$daftar_perjanjian
					
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
			
			$id = $this->input->get('perjanjian_id');
	        
	        // $data['all_countries'] = $this->xin_model->get_countries();
			
			$result = $this->Perjanjian_model->read_perjanjian_information($id);
			
			$data = array(
				'perjanjian_type_id'            => $result[0]->perjanjian_type_id,				
				'perjanjian_id'                 => $result[0]->perjanjian_id,
				'perjanjian_no'                  => $result[0]->perjanjian_no,
				'perjanjian_nama'               => $result[0]->perjanjian_nama,
				'perjanjian_pihak_1'            => $result[0]->perjanjian_pihak_1,
				'perjanjian_pihak_2'            => $result[0]->perjanjian_pihak_2,
				'perjanjian_item'               => $result[0]->perjanjian_item,
				'perjanjian_nilai'              => $result[0]->perjanjian_nilai,
				'start_date'                    => $result[0]->start_date,
				'end_date' 						=> $result[0]->end_date,
				'get_all_perjanjian_type'       => $this->Core_model->get_perjanjian_type_combo()					
			);

			if(!empty($session)){ 
				$this->load->view('admin/perjanjian/dialog_perjanjian', $data);
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
			
			$id = $this->input->get('perjanjian_id');
	        
	        // $data['all_countries'] = $this->xin_model->get_countries();
			
			$result = $this->Perjanjian_model->read_perjanjian_information($id);
			
			$data = array(
				'perjanjian_type_id'            => $result[0]->perjanjian_type_id,				
				'perjanjian_id'                 => $result[0]->perjanjian_id,
				'perjanjian_no'                 => $result[0]->perjanjian_no,
				'perjanjian_nama'               => $result[0]->perjanjian_nama,
				'perjanjian_pihak_1'            => $result[0]->perjanjian_pihak_1,
				'perjanjian_pihak_2'            => $result[0]->perjanjian_pihak_2,
				'perjanjian_item'               => $result[0]->perjanjian_item,
				'perjanjian_nilai'              => $result[0]->perjanjian_nilai,
				'start_date'                    => $result[0]->start_date,
				'end_date' 						=> $result[0]->end_date,
				'get_all_perjanjian_type'       => $this->Core_model->get_perjanjian_type_combo()					
			);

			if(!empty($session)){ 
				$this->load->view('admin/perjanjian/view_perjanjian', $data);
			} else {
				redirect('admin/');
			}
		}
		// Tambah		
		public function add_perjanjian() 
		{
		
			if($this->input->post('add_type')=='perjanjian') {
				
				// Check validation for user input				
				
				$this->form_validation->set_rules('perjanjian_type_id', 'Jenis Perjanjian', 'trim|required|xss_clean');
				$this->form_validation->set_rules('perjanjian_no', 'No Perjanjian', 'trim|required|xss_clean');
				$this->form_validation->set_rules('perjanjian_nama', 'Nama Perjanjian', 'trim|required|xss_clean');

				$this->form_validation->set_rules('perjanjian_pihak_1', 'Nama Pihak 1', 'trim|required|xss_clean');
				$this->form_validation->set_rules('perjanjian_pihak_2', 'Nama Pihak 2', 'trim|required|xss_clean');

				$this->form_validation->set_rules('perjanjian_item', 'Item Perjanjian', 'trim|required|xss_clean');
				$this->form_validation->set_rules('perjanjian_Nilai', 'Nilai Perjanjian', 'trim|required|xss_clean');

				$this->form_validation->set_rules('start_date', 'Tanggal Diperoleh', 'trim|required|xss_clean');
				$this->form_validation->set_rules('end_date', 'Tanggal Berlaku Sampai', 'trim|required|xss_clean');
				
				/* Define return | here result is used to return user data and error for error message */
				$Return = array('result'=>'', 'error'=>'', 'csrf_hash'=>'');
				$Return['csrf_hash'] = $this->security->get_csrf_hash();
					
				/* Server side PHP input validation */					
				
				if($this->input->post('perjanjian_type_id')==='') {
					$Return['error'] = 'Jenis Perjanjian Belum Diisi';				
			
				} else if($this->input->post('perjanjian_no')==='') {
					$Return['error'] = 'No Perjanjian Belum Diisi';	

				} else if($this->input->post('perjanjian_nama')==='') {
					$Return['error'] = 'Nama Perjanjian Belum Diisi';	

				} else if($this->input->post('perjanjian_pihak_1')==='') {
					$Return['error'] = 'Nama Pihak 1 Belum Diisi';	

				} else if($this->input->post('perjanjian_pihak_2')==='') {
					$Return['error'] = 'Nama Pihak 2 Belum Diisi';	

				} else if($this->input->post('perjanjian_item')==='') {
					$Return['error'] = 'Item Perjanjian Belum Diisi';	

				} else if($this->input->post('perjanjian_Nilai')==='') {
					$Return['error'] = 'Nilai Perjanjian Belum Diisi';	

				} else if($this->input->post('start_date')==='') {
					$Return['error'] = 'Tanggal Mulai Belum Diisi';

				} else if($this->input->post('end_date')==='') {
					$Return['error'] = 'Tanggal Berakhir Belum Diisi';
				}

						
				if($Return['error']!=''){
		       		$this->output($Return);
		    	}
			
				$data = array(			
					'perjanjian_type_id'  => $this->input->post('perjanjian_type_id'),					
					'perjanjian_no'       => $this->input->post('perjanjian_no'),
					'perjanjian_nama'     => $this->input->post('perjanjian_nama'),

					'perjanjian_pihak_1'  => $this->input->post('perjanjian_pihak_1'),	
					'perjanjian_pihak_2'  => $this->input->post('perjanjian_pihak_2'),	

					'perjanjian_item'     => $this->input->post('perjanjian_item'),	
					'perjanjian_nilai'    => $this->input->post('perjanjian_nilai'),	

					'start_date'       	  => $this->input->post('start_date'),			
					'end_date' 		      => $this->input->post('end_date'),	

					'created_by'          => $this->input->post('user_id'),
					'created_at'          => date('Y-m-d'),			
				);
				$result = $this->Perjanjian_model->add($data);
				if ($result == TRUE) {
					$Return['result'] = 'Perjanjian Baru Berhasil Ditambahkan';
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

			if($this->input->post('edit_type')=='perjanjian') {
				
				$id = $this->uri->segment(4);
								
				$this->form_validation->set_rules('perjanjian_type_id', 'Jenis Perjanjian', 'trim|required|xss_clean');
				$this->form_validation->set_rules('perjanjian_no', 'No Perjanjian', 'trim|required|xss_clean');
				$this->form_validation->set_rules('perjanjian_nama', 'Nama Perjanjian', 'trim|required|xss_clean');

				$this->form_validation->set_rules('perjanjian_pihak_1', 'Nama Pihak 1', 'trim|required|xss_clean');
				$this->form_validation->set_rules('perjanjian_pihak_2', 'Nama Pihak 2', 'trim|required|xss_clean');

				$this->form_validation->set_rules('perjanjian_item', 'Item Perjanjian', 'trim|required|xss_clean');
				$this->form_validation->set_rules('perjanjian_Nilai', 'Nilai Perjanjian', 'trim|required|xss_clean');

				$this->form_validation->set_rules('start_date', 'Tanggal Diperoleh', 'trim|required|xss_clean');
				$this->form_validation->set_rules('end_date', 'Tanggal Berlaku Sampai', 'trim|required|xss_clean');
				
				/* Define return | here result is used to return user data and error for error message */
				$Return = array('result'=>'', 'error'=>'', 'csrf_hash'=>'');
				$Return['csrf_hash'] = $this->security->get_csrf_hash();
					
				/* Server side PHP input validation */					
				
				if($this->input->post('perjanjian_type_id')==='') {
					$Return['error'] = 'Jenis Perjanjian Belum Diisi';				
			
				} else if($this->input->post('perjanjian_no')==='') {
					$Return['error'] = 'No Perjanjian Belum Diisi';	

				} else if($this->input->post('perjanjian_nama')==='') {
					$Return['error'] = 'Nama Perjanjian Belum Diisi';	

				} else if($this->input->post('perjanjian_pihak_1')==='') {
					$Return['error'] = 'Nama Pihak 1 Belum Diisi';	

				} else if($this->input->post('perjanjian_pihak_2')==='') {
					$Return['error'] = 'Nama Pihak 2 Belum Diisi';	

				} else if($this->input->post('perjanjian_item')==='') {
					$Return['error'] = 'Item Perjanjian Belum Diisi';	

				} else if($this->input->post('perjanjian_Nilai')==='') {
					$Return['error'] = 'Nilai Perjanjian Belum Diisi';	

				} else if($this->input->post('start_date')==='') {
					$Return['error'] = 'Tanggal Mulai Belum Diisi';

				} else if($this->input->post('end_date')==='') {
					$Return['error'] = 'Tanggal Berakhir Belum Diisi';
				}

						
				if($Return['error']!=''){
		       		$this->output($Return);
		    	}
			
				$data = array(			
					'perjanjian_type_id'  => $this->input->post('perjanjian_type_id'),					
					'perjanjian_no'       => $this->input->post('perjanjian_no'),
					'perjanjian_nama'     => $this->input->post('perjanjian_nama'),

					'perjanjian_pihak_1'  => $this->input->post('perjanjian_pihak_1'),	
					'perjanjian_pihak_2'  => $this->input->post('perjanjian_pihak_2'),	

					'perjanjian_item'     => $this->input->post('perjanjian_item'),	
					'perjanjian_nilai'    => $this->input->post('perjanjian_nilai'),	

					'start_date'       	  => $this->input->post('start_date'),			
					'end_date' 		      => $this->input->post('end_date'),

				);	
				
				$result = $this->Perjanjian_model->update_record($data,$id);		
				
				if ($result == TRUE) {
					$Return['result'] = 'Perjanjian Berhasil Diperbaharui';
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
				$result = $this->Perjanjian_model->delete_record($id);
				if(isset($id)) {
					$Return['result'] = 'Perjanjian Berhasil Dihapus';
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
