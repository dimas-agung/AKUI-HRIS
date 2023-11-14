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

class Transfers extends MY_Controller {
	
	 public function __construct() {
        parent::__construct();
		//load the model
		$this->load->model("Transfers_model");
		$this->load->model("Core_model");
		$this->load->library('email');

		$this->load->model("Company_model");
		$this->load->model("Location_model");		
		$this->load->model("Department_model");
		$this->load->model("Designation_model");

		$this->load->model("Employees_model");
		
	}
	// =================================================================================================================
	// START
	// =================================================================================================================
		/*Function to set JSON output*/
		public function output($Return=array()){
			/*Set response header*/
			header("Access-Control-Allow-Origin: *");
			header("Content-Type: application/json; charset=UTF-8");
			/*Final JSON response*/
			exit(json_encode($Return));
		}
	
	// =================================================================================================================
	// TABEL
	// =================================================================================================================

		public function index()
	    {
			$session = $this->session->userdata('username');
			if(empty($session)){ 
				redirect('admin/');
			}
			$data['title']         = $this->lang->line('xin_transfers').' | '.$this->Core_model->site_title();
			$data['icon']          = '<i class="fa fa-send"></i>';
			$data['breadcrumbs']   = $this->lang->line('xin_transfers');
			$data['path_url']      = 'transfers';

			$data['all_employees'] = $this->Core_model->all_employees();
			$data['all_locations'] = $this->Core_model->all_locations();
			$data['get_all_companies'] = $this->Company_model->get_company();
			$data['all_departments'] = $this->Department_model->all_departments();
			
			$role_resources_ids = $this->Core_model->user_role_resource();
			if(in_array('0650',$role_resources_ids)) {
				if(!empty($session)){ 
					$data['subview'] = $this->load->view("admin/transfers/transfer_list", $data, TRUE);
					$this->load->view('admin/layout/layout_main', $data); //page load
				} else {
					redirect('admin/');
				}
			} else {
				redirect('admin/dashboard');
			}
	     }
	 
	    public function transfer_list()
	    {

			$data['title'] = $this->Core_model->site_title();
			$session = $this->session->userdata('username');
			if(!empty($session)){ 
				$this->load->view("admin/transfers/transfer_list", $data);
			} else {
				redirect('admin/');
			}
			// Datatables Variables
			$draw = intval($this->input->get("draw"));
			$start = intval($this->input->get("start"));
			$length = intval($this->input->get("length"));
			$role_resources_ids = $this->Core_model->user_role_resource();
			
			$user_info = $this->Core_model->read_user_info($session['user_id']);
			
			$transfer = $this->Transfers_model->get_transfers();
			
			
			$data = array();
			$no =1 ;
	        foreach($transfer->result() as $r) {
				
				// =================================================================================================
				// get user > employee_
	        	// =================================================================================================
					$employee = $this->Core_model->read_user_info($r->employee_id);
					// employee full name
					if(!is_null($employee)){
						$employee_name = $employee[0]->first_name.' '.$employee[0]->last_name;
					} else {
						$employee_name = '--';	
					}
				// =================================================================================================
				// get date
				// =================================================================================================
					$transfer_date = $this->Core_model->set_date_format($r->transfer_date);

				// =================================================================================================
				// Data Lama
				// =================================================================================================
					// 01. Perusahaan
					$company_old = $this->Company_model->read_company_information($r->company_id_old);
					if(!is_null($company_old)){
						$company_name_old = $company_old[0]->name;
					} else {
						$company_name_old = '--';	
					}
					// 02. Location
					$location_old = $this->Location_model->read_location_information($r->location_id_old);
					if(!is_null($location_old)){
						$location_name_old = $location_old[0]->location_name;
					} else {
						$location_name_old = '--';	
					}
					// 03. Departemen
					$department_old = $this->Department_model->read_department_information($r->department_id_old);
					if(!is_null($department_old)){
						$department_name_old = $department_old[0]->department_name;
					} else {
						$department_name_old = '--';	
					}			
					// 04. Posisi
					$designation_old = $this->Designation_model->read_designation_information($r->designation_id_old);
					if(!is_null($designation_old)){
						$designation_name_old = $designation_old[0]->designation_name;
					} else {
						$designation_name_old = '--';	
					}
					// 05 Jenis Gaji
					$wages_old = $this->Designation_model->read_wages_information($r->wages_type_id_old);
					if(!is_null($wages_old)){
						$wages_name_old = $wages_old[0]->jenis_gaji_keterangan;
					} else {
						$wages_name_old = '--';	
					}

					// 06 Jenis Pola
					$pola_old = $this->Designation_model->read_pola_information($r->pola_id_old);
					if(!is_null($pola_old)){
						$pola_name_old = $pola_old[0]->pola_keterangan;
					} else {
						$pola_name_old = '--';	
					}

					$data_lama = $company_name_old.'<br>'.$location_name_old.'<br>'.$department_name_old.'<br>'.$designation_name_old.'<br>'.$wages_name_old.'<br>'.$pola_name_old;

				// =================================================================================================
				// Data Mutasi
				// =================================================================================================
					// 01. Perusahaan
					$company = $this->Company_model->read_company_information($r->transfer_company);
					if(!is_null($company)){
						$company_name = $company[0]->name;
					} else {
						$company_name = '--';	
					}
					// 02. Location
					$location = $this->Location_model->read_location_information($r->transfer_location);
					if(!is_null($location)){
						$location_name = $location[0]->location_name;
					} else {
						$location_name = '--';	
					}
					// 03. Departemen
					$department = $this->Department_model->read_department_information($r->transfer_department);
					if(!is_null($department)){
						$department_name = $department[0]->department_name;
					} else {
						$department_name = '--';	
					}			
					// 04. Posisi
					$designation = $this->Designation_model->read_designation_information($r->transfer_designation);
					if(!is_null($designation)){
						$designation_name = $designation[0]->designation_name;
					} else {
						$designation_name = '--';	
					}
					// 05 Jenis Gaji
					$wages = $this->Designation_model->read_wages_information($r->transfer_wages_type);
					if(!is_null($wages)){
						$wages_name = $wages[0]->jenis_gaji_keterangan;
					} else {
						$wages_name = '--';	
					}

					// 06 Jenis Pola
					$pola = $this->Designation_model->read_pola_information($r->transfer_pola);
					if(!is_null($pola)){
						$pola_name = $pola[0]->pola_keterangan;
					} else {
						$pola_name = '--';	
					}

				// =================================================================================================
				// Periksa
				// =================================================================================================
					$employee = $this->Employees_model->read_employee_information($r->employee_id );
					if(!is_null($employee)){

						$company_id_check     = $employee[0]->company_id;
						$location_id_cek      = $employee[0]->location_id;
						$department_id_cek    = $employee[0]->department_id;
						$designation_id_cek   = $employee[0]->designation_id;
						$wages_type_id_cek    = $employee[0]->wages_type;
						$pola_id_cek          = $employee[0]->office_id;	
						$jadwal_id_cek        = $employee[0]->office_shift_id;				

					} else {
						$company_id_check     = '0';
						$location_id_cek      = '0';
						$department_id_cek    = '0';
						$designation_id_cek   = '0';
						$wages_type_id_cek    = '0';
						$pola_id_cek          = '0';
						$jadwal_id_cek        = '0';
					}

					if ($pola_name == 'Reguler') {

						// 06 Jenis Pola
						$jadwal_cek = $this->Designation_model->read_jadwal_reguler_information($jadwal_id_cek);
						if(!is_null($jadwal_cek)){
							$jadwal_name = $jadwal_cek[0]->shift_name;
						} else {
							$jadwal_name = "<i class='fa fa-question-circle merah'></i> jadwal kerja baru belum di setting";	
						}

					} else {

						// 06 Jenis Pola
						$jadwal_cek = $this->Designation_model->read_jadwal_shift_information($jadwal_id_cek);
						if(!is_null($jadwal_cek)){
							$jadwal_name = $jadwal_cek[0]->shift_name;
						} else {
							$jadwal_name = "<i class='fa fa-question-circle merah'></i> jadwal kerja baru belum di setting";	
						}

					}
					

					if ($company_id_check == $r->transfer_company) {
						$cek_company ="<i class='fa fa-check-circle hijau'></i>";
					} else {
						$cek_company ="<i class='fa fa-minus-circle merah'></i>";
					}
					if ($location_id_cek == $r->transfer_location) {
						$cek_location ="<i class='fa fa-check-circle hijau'></i>";
					} else {
						$cek_location ="<i class='fa fa-minus-circle merah'></i>";
					}
					if ($department_id_cek == $r->transfer_department) {
						$cek_department ="<i class='fa fa-check-circle hijau'></i>";
					} else {
						$cek_department ="<i class='fa fa-minus-circle merah'></i>";
					}
					if ($designation_id_cek == $r->transfer_designation) {
						$cek_designation ="<i class='fa fa-check-circle hijau'></i>";
					} else {
						$cek_designation ="<i class='fa fa-minus-circle merah'></i>";
					}
					if ($wages_type_id_cek == $r->transfer_wages_type) {
						$cek_wages_type ="<i class='fa fa-check-circle hijau'></i>";
					} else {
						$cek_wages_type ="<i class='fa fa-minus-circle merah'></i>";
					}
					if ($pola_id_cek == $r->transfer_pola) {
						$cek_pola ="<i class='fa fa-check-circle hijau'></i>";
					} else {
						$cek_pola ="<i class='fa fa-minus-circle merah'></i>";
					}


					$data_baru = $cek_company.' '.$company_name.'<br>'.$cek_location.' '.$location_name.'<br>'.$cek_department.' '.$department_name.'<br>'.$cek_designation.' '.$designation_name.'<br>'.$cek_wages_type.' '.$wages_name.'<br>'.$cek_pola.' '.$pola_name.' - '.$jadwal_name;
				
				// =================================================================================================
				// get status
				// =================================================================================================
					if($r->status == 0): $status = '<span class="badge bg-orange">'.$this->lang->line('xin_pending').'</span>';
					elseif($r->status == 1): $status = '<span class="badge bg-green">'.$this->lang->line('xin_accepted').'</span>';
					else: $status = '<span class="badge bg-red">'.$this->lang->line('xin_rejected').'</span>'; 
					endif;
				
				// =================================================================================================
				// Tombol
				// =================================================================================================
					if(in_array('0653',$role_resources_ids)) { //edit
						$edit = '<span data-toggle="tooltip" data-placement="top" title="Aktifkan">
									<button type="button" class="btn icon-btn btn-xs btn-default waves-effect waves-light"  data-toggle="modal" data-target=".edit-modal-data"  data-transfer_id="'. $r->transfer_id . '">
										<span class="fa fa-check-circle"></span> Aktifkan
									</button>
								</span>';
					} else {
						$edit = '';
					}

					if(in_array('0654',$role_resources_ids)) { //delete
						$delete = '<span data-toggle="tooltip" data-placement="top" title="'.$this->lang->line('xin_delete').'">
										<button type="button" class="btn icon-btn btn-xs btn-danger waves-effect waves-light delete" data-toggle="modal" data-target=".delete-modal" data-record-id="'. $r->transfer_id . '">
											<span class="fa fa-trash"></span>
										</button>
									</span>';
					} else {
						$delete = '';
					}

					if(in_array('0655',$role_resources_ids)) { //view
						$view = '<span data-toggle="tooltip" data-placement="top" title="'.$this->lang->line('xin_view').'">
										<button type="button" class="btn icon-btn btn-xs btn-default waves-effect waves-light" data-toggle="modal" data-target=".view-modal-data" data-transfer_id="'. $r->transfer_id . '">
											<span class="fa fa-eye"></span>
										</button>
								</span> ';
					} else {
						$view = '';
					}
				
			$combhr = $edit.$view.$delete;

			$data[] = array(
				$combhr,			
				$transfer_date,
				$employee_name,
				$data_lama,
				$data_baru,	
				$r->description,		
				$status			
			);

			$no++;
	      }

		  $output = array(
			   "draw" => $draw,
				 "recordsTotal" => $transfer->num_rows(),
				 "recordsFiltered" => $transfer->num_rows(),
				 "data" => $data
			);
		  echo json_encode($output);
		  exit();
	    }
	
	// =================================================================================================================
	// PROSES
	// =================================================================================================================

		public function read()
		{
			$session = $this->session->userdata('username');
			if(empty($session)){ 
				redirect('admin/');
			}
			$data['title'] = $this->Core_model->site_title();
			$id = $this->input->get('transfer_id');
			$result = $this->Transfers_model->read_transfer_information($id);
			$data = array(

					'transfer_id'          => $result[0]->transfer_id,
					'employee_id'          => $result[0]->employee_id,				
					'transfer_date'        => $result[0]->transfer_date,

					// old
					'company_id_old'       => $result[0]->company_id_old,				
					'department_id_old'    => $result[0]->department_id_old,
					'location_id_old'      => $result[0]->location_id_old,
					'designation_id_old'   => $result[0]->designation_id_old,
					'wages_type_id_old'    => $result[0]->wages_type_id_old,
					'pola_id_old'          => $result[0]->pola_id_old,

					// mutasi
					'transfer_company'     => $result[0]->transfer_company,					
					'transfer_department'  => $result[0]->transfer_department,
					'transfer_location'    => $result[0]->transfer_location,
					'transfer_designation' => $result[0]->transfer_designation,
					'transfer_wages_type'  => $result[0]->transfer_wages_type,
					'transfer_pola'        => $result[0]->transfer_pola,
					
					'description'          => $result[0]->description,
					'status'               => $result[0]->status,
					
					'all_employees'        => $this->Core_model->all_employees(),
					'all_locations'        => $this->Core_model->all_locations(),
					'get_all_companies'    => $this->Company_model->get_company(),
					'all_departments'      => $this->Department_model->all_departments(),
					'all_designations'     => $this->Designation_model->all_designations()
			
			);
			if(!empty($session)){ 
				$this->load->view('admin/transfers/dialog_transfer', $data);
			} else {
				redirect('admin/');
			}
		}
		public function view()
		{
			$session = $this->session->userdata('username');
			if(empty($session)){ 
				redirect('admin/');
			}
			$data['title'] = $this->Core_model->site_title();
			$id = $this->input->get('transfer_id');
			$result = $this->Transfers_model->read_transfer_information($id);
			$data = array(

					'transfer_id'          => $result[0]->transfer_id,
					'employee_id'          => $result[0]->employee_id,				
					'transfer_date'        => $result[0]->transfer_date,

					// old
					'company_id_old'       => $result[0]->company_id_old,				
					'department_id_old'    => $result[0]->department_id_old,
					'location_id_old'      => $result[0]->location_id_old,
					'designation_id_old'   => $result[0]->designation_id_old,
					'wages_type_id_old'    => $result[0]->wages_type_id_old,
					'pola_id_old'          => $result[0]->pola_id_old,

					// mutasi
					'transfer_company'     => $result[0]->transfer_company,					
					'transfer_department'  => $result[0]->transfer_department,
					'transfer_location'    => $result[0]->transfer_location,
					'transfer_designation' => $result[0]->transfer_designation,
					'transfer_wages_type'  => $result[0]->transfer_wages_type,
					'transfer_pola'        => $result[0]->transfer_pola,
					
					'description'          => $result[0]->description,
					'status'               => $result[0]->status,
					
					'all_employees'        => $this->Core_model->all_employees(),
					'all_locations'        => $this->Core_model->all_locations(),
					'get_all_companies'    => $this->Company_model->get_company(),
					'all_departments'      => $this->Department_model->all_departments(),
					'all_designations'     => $this->Designation_model->all_designations()
			
			);
			if(!empty($session)){ 
				$this->load->view('admin/transfers/dialog_transfer_view', $data);
			} else {
				redirect('admin/');
			}
		}
		// Validate and add info in database
		public function add_transfer() 
		{
		
			if($this->input->post('add_type')=='transfer') {	

				/* Define return | here result is used to return user data and error for error message */
				$Return = array('result'=>'', 'error'=>'', 'csrf_hash'=>'');
				$Return['csrf_hash'] = $this->security->get_csrf_hash();
					
				/* Server side PHP input validation */
				$description = $this->input->post('description');
				$qt_description = htmlspecialchars(addslashes($description), ENT_QUOTES);
				
							
				if($this->input->post('employee_id')==='') {
		       		 $Return['error'] = $this->lang->line('xin_error_employee_id');
				
				} else if($this->input->post('transfer_date')==='') {
					$Return['error'] = $this->lang->line('xin_transfers_error_date');

				} else if($this->input->post('transfer_company')==='') {
		        	$Return['error'] = $this->lang->line('error_company_field');
				
				} else if($this->input->post('transfer_department')==='') {
					 $Return['error'] = $this->lang->line('xin_employee_error_department');
				
				} else if($this->input->post('transfer_location')==='') {
		       		$Return['error'] = $this->lang->line('error_location_dept_field');
				
				} else if($this->input->post('transfer_designation')==='') {
		       		$Return['error'] = $this->lang->line('error_designation_dept_field');
				
				} else if($this->input->post('transfer_wages_type')==='') {
		       		$Return['error'] = $this->lang->line('error_wages_type_field');

		       	} else if($this->input->post('transfer_pola')==='') {
		       		$Return['error'] = $this->lang->line('error_pola_type_field');
				}
					
				if($Return['error']!=''){
		       		$this->output($Return);
		    	}

				$employee = $this->Employees_model->read_employee_information( $this->input->post('employee_id') );
				if(!is_null($employee)){

					$company_id_old     = $employee[0]->company_id;
					$location_id_old    = $employee[0]->location_id;
					$department_id_old  = $employee[0]->department_id;
					$designation_id_old = $employee[0]->designation_id;
					$wages_type_id_old  = $employee[0]->wages_type;	
					$office_id_old      = $employee[0]->office_id;				

				} else {
					$company_id_old     = '0';
					$location_id_old    = '0';
					$department_id_old  = '0';
					$designation_id_old = '0';
					$wages_type_id_old  = '0';
					$office_id_old      = '';
				}

				$data = array(
					'employee_id'              => $this->input->post('employee_id'),			
					'transfer_date'            => $this->input->post('transfer_date'),
					
					// old
					'company_id_old'           => $company_id_old,	
					'location_id_old'          => $location_id_old,
					'department_id_old'        => $department_id_old,
					'designation_id_old'       => $designation_id_old,
					'wages_type_id_old'        => $wages_type_id_old,
					'pola_id_old'              => $office_id_old,

					// mutasi
					'transfer_company'         => $this->input->post('transfer_company'),
					'transfer_location'        => $this->input->post('transfer_location'),
					'transfer_department'      => $this->input->post('transfer_department'),
					'transfer_designation'     => $this->input->post('transfer_designation'),				
					'transfer_wages_type'      => $this->input->post('transfer_wages_type'),
					'transfer_pola'            => $this->input->post('transfer_pola'),

					'description'              => $qt_description,

					'added_by'                 => $this->input->post('user_id'),
					'created_at'               => date('d-m-Y'),
				
				);
				$result = $this->Transfers_model->add($data);
				
				if ($result == TRUE) {
					$Return['result'] = $this->lang->line('xin_transfers_success_added');					
					
				} else {
					$Return['error'] = $this->lang->line('xin_error_msg');
				}
				
				$this->output($Return);
				exit;
			}
		}
		
		// Validate and update info in database
		public function update() 
		{
		
			if($this->input->post('edit_type')=='transfer') {
				
				$id = $this->uri->segment(4);
				
				/* Define return | here result is used to return user data and error for error message */
				$Return = array('result'=>'', 'error'=>'', 'csrf_hash'=>'');
				$Return['csrf_hash'] = $this->security->get_csrf_hash();
					
				/* Server side PHP input validation */
				$description = $this->input->post('description');
				$qt_description = htmlspecialchars(addslashes($description), ENT_QUOTES);			
				

				if($this->input->post('transfer_date')==='') {
					$Return['error'] = $this->lang->line('xin_transfers_error_date');

				} else if($this->input->post('transfer_company')==='') {
		        	$Return['error'] = $this->lang->line('error_company_field');
				
				} else if($this->input->post('transfer_department')==='') {
					 $Return['error'] = $this->lang->line('xin_employee_error_department');
				
				} else if($this->input->post('transfer_location')==='') {
		       		$Return['error'] = $this->lang->line('error_location_dept_field');
				
				} else if($this->input->post('transfer_designation')==='') {
		       		$Return['error'] = $this->lang->line('error_designation_dept_field');
				
				} else if($this->input->post('transfer_wages_type')==='') {
		       		$Return['error'] = $this->lang->line('error_wages_type_field');

		       	} else if($this->input->post('transfer_pola')==='') {
		       		$Return['error'] = $this->lang->line('error_pola_type_field');
				}
					
				if($Return['error']!=''){
		       		$this->output($Return);
		    	}

		    	// =========================================================================================
		    	
			    	$result = $this->Transfers_model->read_transfer_information($id);
					if(!is_null($result)){
						$employee_id = $result[0]->employee_id;
						
					} else {
						$employee_id = '0';		
					}
					
					$pola =  $this->input->post('transfer_pola');

					if ($pola == 'R') {
						$jadwal = '1';
					} else {
						$jadwal = '';
					}

					$user_data = array(
						'company_id'       => $this->input->post('transfer_company'),
						'location_id'      => $this->input->post('transfer_location'),
						'department_id'    => $this->input->post('transfer_department'),
						'designation_id'   => $this->input->post('transfer_designation'),
						'wages_type'       => $this->input->post('transfer_wages_type'),
						'office_id'        => $this->input->post('transfer_pola'),
						'office_shift_id'  => $jadwal
					);
					$user_info = $this->Employees_model->basic_info($user_data, $employee_id);

				//==============================================================================================
			
				$data = array(
					'transfer_date'            => $this->input->post('transfer_date'),
									
					// mutasi
					'transfer_company'         => $this->input->post('transfer_company'),
					'transfer_location'        => $this->input->post('transfer_location'),
					'transfer_department'      => $this->input->post('transfer_department'),
					'transfer_designation'     => $this->input->post('transfer_designation'),				
					'transfer_wages_type'      => $this->input->post('transfer_wages_type'),
					'transfer_pola'            => $this->input->post('transfer_pola'),

					'description'              => $qt_description,		
					'status'                   => $this->input->post('status'),
				);
				
				$result = $this->Transfers_model->update_record($data,$id);		
				
				if ($result == TRUE) {
					$Return['result'] = $this->lang->line('xin_transfers_success_updated');				

				} else {
					$Return['error'] = $this->lang->line('xin_error_msg');
				}
				$this->output($Return);
				exit;
			}
		}
		
		public function delete() 
		{
			/* Define return | here result is used to return user data and error for error message */
			$Return = array('result'=>'', 'error'=>'', 'csrf_hash'=>'');
			$id = $this->uri->segment(4);
			$Return['csrf_hash'] = $this->security->get_csrf_hash();
			$result = $this->Transfers_model->delete_record($id);
			if(isset($id)) {
				$Return['result'] = $this->lang->line('xin_transfers_success_deleted');
			} else {
				$Return['error'] = $this->lang->line('xin_error_msg');
			}
			$this->output($Return);
		}

	// =================================================================================================================
	// TAMPILKAN
	// =================================================================================================================

		// get company > departments
		public function get_departments() {

			$data['title'] = $this->Core_model->site_title();
			$id = $this->uri->segment(4);
			
			$data = array(
				'company_id' => $id
				);
			$session = $this->session->userdata('username');
			if(!empty($session)){ 
				$this->load->view("admin/transfers/get_departments", $data);
			} else {
				redirect('admin/');
			}
			// Datatables Variables
			$draw = intval($this->input->get("draw"));
			$start = intval($this->input->get("start"));
			$length = intval($this->input->get("length"));
		 }
		 
		 public function get_designations() {

			$data['title'] = $this->Core_model->site_title();
			$id = $this->uri->segment(4);
			
			$data = array(
				'department_id'    => $id,
				'all_designations' => $this->Designation_model->all_designations(),
			);
			$session = $this->session->userdata('username');
			if(!empty($session)){ 
				$this->load->view("admin/transfers/get_designations", $data);
			} else {
				redirect('admin/');
			}
			// Datatables Variables
			$draw   = intval($this->input->get("draw"));
			$start  = intval($this->input->get("start"));
			$length = intval($this->input->get("length"));
		}

		 // get company > location
		public function get_locations() {

			$data['title'] = $this->Core_model->site_title();
			$id = $this->uri->segment(4);
			
			$data = array(
				'location_id' => $id,
				'all_locations' => $this->Core_model->all_locations(),
				);
			$session = $this->session->userdata('username');
			if(!empty($session)){ 
				$this->load->view("admin/transfers/get_locations", $data);
			} else {
				redirect('admin/');
			}
			// Datatables Variables
			$draw = intval($this->input->get("draw"));
			$start = intval($this->input->get("start"));
			$length = intval($this->input->get("length"));
		}
		 
		// get company > employees
		public function get_employees() {

			$data['title'] = $this->Core_model->site_title();
			$id = $this->uri->segment(4);
			
			$data = array(
				'company_id' => $id
				);
			$session = $this->session->userdata('username');
			if(!empty($session)){ 
				$this->load->view("admin/transfers/get_employees", $data);
			} else {
				redirect('admin/');
			}
			// Datatables Variables
			$draw = intval($this->input->get("draw"));
			$start = intval($this->input->get("start"));
			$length = intval($this->input->get("length"));
		}
	
	// =================================================================================================================
	// END
	// =================================================================================================================

}
