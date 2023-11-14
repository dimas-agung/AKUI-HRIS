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

class perizinan extends MY_Controller {
	
	public function __construct() {
        parent::__construct();
		//load the model
		$this->load->model("Perizinan_model");
		$this->load->model("Employees_model");
		$this->load->model("Perizinan_model");
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
			$data['title']       = 'Perizinan | '.$this->Core_model->site_title();
			$data['icon']        = '<i class="fa fa-files-o"></i>';
			$data['desc']        = 'Proses : Input Perizinan';
			$data['breadcrumbs'] = 'Perizinan';
			$data['path_url']    = 'perizinan';

			
			$data['get_all_instansi']        = $this->Instansi_model->get_instansies();
			$data['get_all_perizinan_type'] = $this->Core_model->get_perizinan_type_combo();
			
			
			$role_resources_ids = $this->Core_model->user_role_resource();
			if(in_array('0420',$role_resources_ids)) {
				if(!empty($session)){ 
				$data['subview'] = $this->load->view("admin/perizinan/perizinan_list", $data, TRUE);
				$this->load->view('admin/layout/layout_main', $data); //page load
				} else {
					redirect('admin/');
				}
			} else {
				redirect('admin/dashboard');
			}
	    }
	 	
	 	public function perizinan_list()
	    {

			$data['title'] = $this->Core_model->site_title();
			$session = $this->session->userdata('username');
			if(!empty($session)){ 
				$this->load->view("admin/perizinan/perizinan_list", $data);
			} else {
				redirect('admin/');
			}
			// Datatables Variables
			$draw = intval($this->input->get("draw"));
			$start = intval($this->input->get("start"));
			$length = intval($this->input->get("length"));
			
			$role_resources_ids = $this->Core_model->user_role_resource();
			$user_info = $this->Core_model->read_user_info($session['user_id']);			
			
			$company = $this->Core_model->get_perizinan_type();			
			
			$data = array();
			$no = 1;
	        
	        foreach($company->result() as $r) {
						
				$icname = strtoupper($r->perizinan_type_name);

				$perizinan = '';

				$sql_perizinan = " SELECT *
							FROM
								 xin_instansi_perizinan
							WHERE
								1 = 1
							AND perizinan_type_id  = '".$r->perizinan_type_id."'								
							ORDER BY perizinan_id ASC";                                    

				// echo "<pre>";
				// print_r( $sql_perizinan );
				// echo "</pre>";
				// die;

				$query_perizinan = $this->db->query($sql_perizinan);

				if ($query_perizinan->num_rows() > 0) {
					

					$daftar_perizinan ='<table class="datatables-demo table table-striped table-bordered" id="xin_table">
					            <thead>
					              <tr>
					                <th class="text-center" width="50px">No.</th>			                
					                <th class="text-center"> No Izin </th>
					                <th class="text-center"> Nama Izin </th>
					                <th class="text-center"> Nama Instansi </th>
					                <th class="text-center"> Tanggal Diperoleh </th>
					                <th class="text-center"> Berlaku Sampai </th>	
					                <th class="text-center"> Status </th>					                 
					                <th class="text-center" width="100px"> Aksi</th>
					              </tr>
					            </thead>
					            <tbody>';
	                        	$mo = 1;
                                foreach($query_perizinan->result() as $row_perizinan):
  									
  									

									// $jum_karyawan = $this->Employees_model->get_total_employees_departemen($row_perizinan->perizinan_id);
									// if(!is_null($jum_karyawan)){
									// 	$jumlah_karyawan = $jum_karyawan[0]->jumlah;
									// } else {
									// 	$jumlah_karyawan = '--';	
									// }
                                	 if(in_array('0432',$role_resources_ids)) { //edit
										$edit = '<span data-toggle="tooltip" data-placement="top" title="'.$this->lang->line('xin_edit').'">
													<button type="button" class="btn icon-btn btn-xs btn-default waves-effect waves-light"  data-toggle="modal" data-target="#edit-modal-data-min"  data-perizinan_id="'. $row_perizinan->perizinan_id . '">
														<span class="fa fa-pencil"></span> Edit 
													</button>
												</span></span>';
									} else {
										$edit = '';
									}

									if(in_array('0433',$role_resources_ids)) { // delete
										$delete = '<span data-toggle="tooltip" data-placement="top" title="'.$this->lang->line('xin_delete').'">
														<button type="button" class="btn icon-btn btn-xs btn-danger waves-effect waves-light delete" data-toggle="modal" data-target=".delete-modal" data-record-id="'. $row_perizinan->perizinan_id . '">
															<span class="fa fa-times"></span>
														</button>
													</span>';
									} else {
										$delete = '';
									}
								
									$perizinan_no            = strtoupper($row_perizinan->perizinan_no);	
									$perizinan_nama          = strtoupper($row_perizinan->perizinan_nama);
									
									date_default_timezone_set("Asia/Jakarta");                            
               
                    				$now_date         = date("Y-m-d");

									$start_date       = date("d-m-Y", strtotime($row_perizinan->start_date));
									$end_date         = date("d-m-Y", strtotime($row_perizinan->end_date));

									if ($now_date > date("Y-m-d", strtotime($row_perizinan->end_date)) ) {
				                    	$status ='<span class="badge btn-danger" style="padding: 5px;font-size: 11px;"><i class="fa fa-times"></i> <b>Berakhir</b> </span>';
				                    	$warna ='#e3cec1';
				                    } else {
				                    	$status ='<span class="badge btn-primary" style="padding: 5px;font-size: 11px;"><i class="fa fa-check"></i> <b>Berlaku</b> </span>';
				                    	$warna ='#c1e3cd';
				                    }

				                    // instansi
									$instansi = $this->Instansi_model->read_instansi_information($row_perizinan->instansi_id);
									if(!is_null($instansi)){
										$instansi_name = strtoupper($instansi[0]->instansi_name);
									} else {
										$instansi_name = '<span class="badge bg-red"> ? </span>';	
									}


									$daftar_perizinan = $daftar_perizinan.' 
									        <tr style ="background-color: '.$warna.' ">
			                                    <td width="2%" align="center">'.$mo.'.</td>
			                                    
			                                    <td width="20%" align="left">
			                                     '.$perizinan_no.' 
			                                    </td>				                                    
												
												<td  align="left">
			                                     '.$perizinan_nama.' 
			                                    </td>

			                                    <td width="20%" align="left">
			                                     '.$instansi_name.' 
			                                    </td>

												<td width="12%" align="center">
			                                     '.$start_date.' 
			                                    </td>

			                                    <td width="10%" align="center">
			                                     '.$end_date.' 
			                                    </td>

			                                    <td width="7%" align="center">
			                                     '.$status.' 
			                                    </td>		                                   
												
												<td width="8%" align="center">
			                                     '.$edit.$delete.' 
			                                    </td>
					                        </tr>';
			                    $mo++;
                          		endforeach;
								
					$daftar_perizinan = $daftar_perizinan.'
								</tbody>
					            </table>';
				} else {

					$daftar_perizinan ='<div class="warning-msg" style="padding:5px;">
					                <i class ="fa fa-question-circle"></i> Tidak Ada Perizinan		                      
					             </div>';
				}

			    $data[] = array(
					$no,
					$icname,
					$daftar_perizinan
					
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
			
			$id = $this->input->get('perizinan_id');
	        
	        // $data['all_countries'] = $this->xin_model->get_countries();
			
			$result = $this->Perizinan_model->read_perizinan_information($id);
			
			$data = array(
				'instansi_id'                   => $result[0]->instansi_id,
				'perizinan_type_id'             => $result[0]->perizinan_type_id,
				'perizinan_id'                  => $result[0]->perizinan_id,					
				'perizinan_no'                  => $result[0]->perizinan_no,
				'perizinan_nama'                => $result[0]->perizinan_nama,
				'start_date'                    => $result[0]->start_date,
				'end_date'                      => $result[0]->end_date,

				'get_all_instansi'              => $this->Instansi_model->get_instansies(),
				'get_all_perizinan_type'        => $this->Core_model->get_perizinan_type_combo()					
			);

			if(!empty($session)){ 
				$this->load->view('admin/perizinan/dialog_perizinan', $data);
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
			
			$id = $this->input->get('perizinan_id');
	        
	        // $data['all_countries'] = $this->xin_model->get_countries();
			
			$result = $this->Perizinan_model->read_perizinan_information($id);
			
			$data = array(
				'instansi_id'                   => $result[0]->instansi_id,
				'perizinan_type_id'             => $result[0]->perizinan_type_id,				
				'perizinan_id'                  => $result[0]->perizinan_id,					
				'perizinan_no'                  => $result[0]->perizinan_no,
				'perizinan_nama'                => $result[0]->perizinan_nama,
				'start_date'   				    => $result[0]->start_date,
				'end_date'  					=> $result[0]->end_date,

				'get_all_instansi' 				=> $this->Instansi_model->get_instansies(),	
				'get_all_perizinan_type'        => $this->Core_model->get_perizinan_type_combo()			
			);

			if(!empty($session)){ 
				$this->load->view('admin/perizinan/view_perizinan', $data);
			} else {
				redirect('admin/');
			}
		}
		// Tambah		
		public function add_perizinan() 
		{
		
			if($this->input->post('add_type')=='perizinan') {
				
				// Check validation for user input				
				$this->form_validation->set_rules('instansi_id', 'Nama Perusahaan', 'trim|required|xss_clean');

				$this->form_validation->set_rules('perizinan_type_id', 'Jenis Perizinan', 'trim|required|xss_clean');
				$this->form_validation->set_rules('perizinan_no', 'No Perizinan', 'trim|required|xss_clean');
				$this->form_validation->set_rules('perizinan_nama', 'Nama Perizinan', 'trim|required|xss_clean');
				
				$this->form_validation->set_rules('start_date', 'Tanggal Diperoleh', 'trim|required|xss_clean');
				$this->form_validation->set_rules('end_date', 'Tanggal Berlaku Sampai', 'trim|required|xss_clean');
				
				/* Define return | here result is used to return user data and error for error message */
				$Return = array('result'=>'', 'error'=>'', 'csrf_hash'=>'');
				$Return['csrf_hash'] = $this->security->get_csrf_hash();
					
				/* Server side PHP input validation */					
				
				if($this->input->post('instansi_id')==='') {
					$Return['error'] ='Nama Instansi Belum Diisi';
				
				} else if($this->input->post('perizinan_type_id')==='') {
					$Return['error'] = 'Jenis Perizinan Belum Diisi';	

				} else if($this->input->post('perizinan_no')==='') {
					$Return['error'] = 'No Perizinan Belum Diisi';	

				} else if($this->input->post('perizinan_nama')==='') {
					$Return['error'] = 'Nama Perizinan Belum Diisi';	

				} else if($this->input->post('start_date')==='') {
					$Return['error'] = 'Tanggal Diperoleh Belum Diisi';

				} else if($this->input->post('end_date')==='') {
					$Return['error'] = 'Tanggal Berlaku Sampai Belum Diisi';
				}

						
				if($Return['error']!=''){
		       		$this->output($Return);
		    	}
			
				$data = array(			
					'instansi_id'       => $this->input->post('instansi_id'),
					'perizinan_type_id' => $this->input->post('perizinan_type_id'),
					'perizinan_no'      => $this->input->post('perizinan_no'),	
					'perizinan_nama'    => $this->input->post('perizinan_nama'),	
					'start_date'        => $this->input->post('start_date'),			
					'end_date' 		    => $this->input->post('end_date'),	

					'created_by'        => $this->input->post('user_id'),
					'created_at'        => date('Y-m-d'),			
				);
				$result = $this->Perizinan_model->add($data);
				if ($result == TRUE) {
					$Return['result'] = 'Perizinan Baru Berhasil Ditambahkan';
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

			if($this->input->post('edit_type')=='perizinan') {
				
				$id = $this->uri->segment(4);
								
				// Check validation for user input				
				$this->form_validation->set_rules('instansi_id', 'Nama Perusahaan', 'trim|required|xss_clean');

				$this->form_validation->set_rules('perizinan_type_id', 'Jenis Perizinan', 'trim|required|xss_clean');
				$this->form_validation->set_rules('perizinan_no', 'No Perizinan', 'trim|required|xss_clean');
				$this->form_validation->set_rules('perizinan_nama', 'Nama Perizinan', 'trim|required|xss_clean');
				
				$this->form_validation->set_rules('start_date', 'Tanggal Diperoleh', 'trim|required|xss_clean');
				$this->form_validation->set_rules('end_date', 'Tanggal Berlaku Sampai', 'trim|required|xss_clean');
				
				/* Define return | here result is used to return user data and error for error message */
				$Return = array('result'=>'', 'error'=>'', 'csrf_hash'=>'');
				$Return['csrf_hash'] = $this->security->get_csrf_hash();
					
				/* Server side PHP input validation */					
				
				if($this->input->post('instansi_id')==='') {
					$Return['error'] ='Nama Instansi Belum Diisi';
				
				} else if($this->input->post('perizinan_type_id')==='') {
					$Return['error'] = 'Jenis Perizinan Belum Diisi';	

				} else if($this->input->post('perizinan_no')==='') {
					$Return['error'] = 'No Perizinan Belum Diisi';	

				} else if($this->input->post('perizinan_nama')==='') {
					$Return['error'] = 'Nama Perizinan Belum Diisi';	

				} else if($this->input->post('start_date')==='') {
					$Return['error'] = 'Tanggal Diperoleh Belum Diisi';

				} else if($this->input->post('end_date')==='') {
					$Return['error'] = 'Tanggal Berlaku Sampai Belum Diisi';
				}

						
				if($Return['error']!=''){
		       		$this->output($Return);
		    	}
			
				$data = array(			
					'instansi_id'                  => $this->input->post('instansi_id'),
					'perizinan_type_id'            => $this->input->post('perizinan_type_id'),
					'perizinan_no'                 => $this->input->post('perizinan_no'),	
					'perizinan_nama'               => $this->input->post('perizinan_nama'),	
					'start_date'                   => $this->input->post('start_date'),			
					'end_date'                     => $this->input->post('end_date'),
				);	
				
				$result = $this->Perizinan_model->update_record($data,$id);		
				
				if ($result == TRUE) {
					$Return['result'] = 'Perizinan Berhasil Diperbaharui';
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
				$result = $this->Perizinan_model->delete_record($id);
				if(isset($id)) {
					$Return['result'] = 'Perizinan Berhasil Dihapus';
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
