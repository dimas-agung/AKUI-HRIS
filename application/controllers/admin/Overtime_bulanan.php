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

class overtime_bulanan extends MY_Controller {
	
	 public function __construct() {
        parent::__construct();
		//load the model
		$this->load->model("Overtime_model");
		$this->load->model("Core_model");
		$this->load->model("Trainers_model");
		$this->load->model("Designation_model");
		$this->load->model("Department_model");
		$this->load->model("Custom_fields_model");
		$this->load->model("Employees_model");
		$this->load->model("Finance_model");
		$this->load->model("Company_model");
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
			
			$data['title']       = 'Lembur Bulanan | '.$this->Core_model->site_title();
			$data['icon']        = '<i class="fa fa-tasks"></i>';
			$data['desc']        = '<b>INFORMASI : </b> Proses Lembur Bulanan Karyawan';
			$data['breadcrumbs'] = 'Lembur Bulanan';
			$data['path_url']    = 'overtime_bulanan';

			$data['all_employees']      = $this->Core_model->all_employees();		
			$data['all_companies']      = $this->Company_model->get_company();			
			$data['all_overtime_types'] = $this->Overtime_model->all_overtime_types();
			
			$role_resources_ids = $this->Core_model->user_role_resource();
			if(in_array('0681',$role_resources_ids)) {
				if(!empty($session)){ 
					$data['subview'] = $this->load->view("admin/overtime/overtime_list_bulanan", $data, TRUE);
					$this->load->view('admin/layout/layout_main', $data); //page load
				} else {
					redirect('admin/');
				}
			} else {
				redirect('admin/dashboard');
			}
	    }
 
	    public function overtime_list_bulanan() {

			$data['title'] = $this->Core_model->site_title();
			$session = $this->session->userdata('username');
			if(!empty($session)){ 
				$this->load->view("admin/overtime/overtime_list_bulanan", $data);
			} else {
				redirect('admin/');
			}
			// Datatables Variables
			$draw   = intval($this->input->get("draw"));
			$start  = intval($this->input->get("start"));
			$length = intval($this->input->get("length"));
			
			$role_resources_ids = $this->Core_model->user_role_resource();
			$user_info          = $this->Core_model->read_user_info($session['user_id']);
					
			$overtime = $this->Overtime_model->get_overtime_bulanan();
			
			$data = array();

	        foreach($overtime->result() as $r) {
				
				$aim = explode(',',$r->employee_id);	
				
				$company = $this->Core_model->read_company_info($r->company_id);
				if(!is_null($company)){
					$comp_name = $company[0]->name;
				} else {
					$comp_name = '<span class="badge bg-red"> ? </span>';
				}
				
				// get overtime date
				$overtime_date = $r->attendance_date_m;

				$cek_ov_status    = $r->ov_status;
				if ($cek_ov_status == '') {
					$ov_status = '<span class="badge bg-red"><i class="fa fa-question"></i></span>';
				} else {
					if ($cek_ov_status == 'TS') {
						$ov_status = '<span class="blink blink-one">Lembur di Tanggal Sama </span>';
					} else {
						$ov_status = '<span class="blink blink-one">Lembur di Tanggal Berikutnya</span> ';
					}				
				}
				// ========================================================================

				// get start date
				$clock_in_m    = $r->clock_in_m;
				// get end date
				$clock_out_m   = $r->clock_out_m;

				$cek_lembur_1 = $clock_in_m.' '.$this->lang->line('dashboard_to').' '.$clock_out_m ;
				if ($cek_lembur_1 == '' || $cek_lembur_1 == '00:00:00 s/d 00:00:00'){
					$lembur_1 = '-- Tidak Ada --';
				} else {
					$lembur_1 = $cek_lembur_1;
				}

				// get start date
				$clock_in_n    = $r->clock_in_n;
				// get end date
				$clock_out_n   = $r->clock_out_n;
				

				$cek_lembur_2 = $clock_in_n.' '.$this->lang->line('dashboard_to').' '.$clock_out_n ;
				if ($cek_lembur_2 == '' || $cek_lembur_2 == '00:00:00 s/d 00:00:00'){
					$lembur_2 = '-- Tidak Ada --';
				} else {
					$lembur_2 = $cek_lembur_2;
				}

				// total work
				$total_time    = $r->total_menit.' Menit';

				$total_jam    = round($total_time/60,2).' Jam';

				// overtime date
				$overtime_time = 'L1 '.$lembur_1.'<br>'.
				                 'L2 '.$lembur_2.'<br>
				                 <small class="text-muted">
				                 <i class="fa fa-clock-o"></i> '.$total_time.' 
				                 <i class="fa fa-angle-double-right"></i> '.$total_jam.'<br>
				                 <i class="fa fa-check-circle"></i> '.$ov_status.'			                 
				                 </small>';

				
				// get report to
				$reports_to = $this->Core_model->read_user_info($r->reports_to);
				// user full name
				if(!is_null($reports_to)){

					// get designation
					$designation = $this->Designation_model->read_designation_information($reports_to[0]->designation_id);
					if(!is_null($designation)){
						$designation_name = $designation[0]->designation_name;
					} else {
						$designation_name = '<span class="badge bg-red"> ? </span>';	
					}

					$manager_name = $reports_to[0]->first_name.' '.$reports_to[0]->last_name .' <small>('.$designation_name.')</small>';
				} else {
					$manager_name = '?';	
				}



				/* get Employee info*/
				if($r->employee_id == '') {
					$ol = '--';
				} else {
					$ol = '<ol class="nl">';
					foreach(explode(',',$r->employee_id) as $uid) {
						$user = $this->Core_model->read_user_info($uid);
						if(!is_null($user)){
							$ol .= '<li>'.$user[0]->first_name.' '.$user[0]->last_name.'</li>';
						} else {
							$ol .= '--';
						}
					 }
					 $ol .= '</ol>';
				}
				

				if($r->overtime_status==1): $status = '<span class="badge bg-orange">'.$this->lang->line('xin_pending').'</span>';
				elseif($r->overtime_status==2): $status = '<span class="badge bg-green">'.$this->lang->line('xin_accepted').'</span>';				
				else: $status = '<span class="badge bg-red">'.$this->lang->line('xin_rejected').'</span>'; endif;

				
				if(in_array('0683',$role_resources_ids)) { //edit
					$edit = '<span data-toggle="tooltip" data-placement="top" title="'.$this->lang->line('xin_edit').'">
								<button type="button" class="btn icon-btn btn-xs btn-default waves-effect waves-light"  data-toggle="modal" data-target=".edit-modal-data"  data-overtime_id="'. $r->overtime_id . '">
									<span class="fa fa-gavel"></span> Aktifkan
								</button>
							</span>';
				} else {
					$edit = '';
				}
				if(in_array('0684',$role_resources_ids)) { // delete
					$delete = '<span data-toggle="tooltip" data-placement="top" title="'.$this->lang->line('xin_delete').'"><button type="button" class="btn icon-btn btn-xs btn-danger waves-effect waves-light delete" data-toggle="modal" data-target=".delete-modal" data-record-id="'. $r->overtime_id . '"><span class="fa fa-trash"></span></button></span>';
				} else {
					$delete = '';
				}
				if(in_array('0685',$role_resources_ids)) { //view
					$view = '<span data-toggle="tooltip" data-placement="top" title="'.$this->lang->line('xin_view_details').'"><a target="_blank" href="'.site_url().'admin/overtime_bulanan/details_bulanan/'.$r->overtime_id.'"><button type="button" class="btn icon-btn btn-xs btn-default waves-effect waves-light"><span class="fa fa-eye"></span></button></a></span> ';
				} else {
					$view = '';
				}
				
				$combhr = $edit.$view.$delete;

				// get overtime type
				$type = $this->Overtime_model->read_overtime_type_information($r->overtime_type_id);
				if(!is_null($type)){
					$itype = $type[0]->type;
				} else {
					$itype = '--';	
				}
				$iitype = $itype.'<br>
				        <small class="text-muted"><i class="fa fa-angle-double-right"></i> '.$r->description.'</small>';
				
				if($r->flag == ''){
					$flag = '<span class="badge bg-red"><i class="fa fa-question"></i></span>';
				} else {
					$flag = '<span class="badge bg-green"><i class="fa fa-check"></i></span>';
				}
				
				$user_input = $this->Core_model->read_user_info($r->created_by);
				if(!is_null($user_input)){

					$user_input_data = $user_input[0]->first_name.' '.$user_input[0]->last_name;
				} else {
					$user_input_data = '';
				}
				
				$data[] = array(
					$combhr,
					date("d-m-Y",strtotime($overtime_date)).' <br>'.$status.' '.$flag,
					$overtime_time,				
					$manager_name.'<br/><small class="text-muted">'.$ol.'</small>',
					$comp_name.'<br>'.$iitype,
					'Diinput : '.date("d-m-Y H:i:s",strtotime($r->created_at)).'<br> <small class="text-muted"> Oleh : '.ucfirst(strtolower($user_input_data)).'</small>'				
				);		
	      }

		  $output = array(
			   "draw" => $draw,
				 "recordsTotal" => $overtime->num_rows(),
				 "recordsFiltered" => $overtime->num_rows(),
				 "data" => $data
			);
		  echo json_encode($output);
		  exit();
	    }
		 
		 // get company > employees
		public function get_employees_bulanan() {

			$data['title'] = $this->Core_model->site_title();
			$id = $this->uri->segment(4);
			
			$data = array(
				'company_id' => $id
				);
			$session = $this->session->userdata('username');
			if(!empty($session)){ 
				$this->load->view("admin/overtime/get_employees_bulanan", $data);
			} else {
				redirect('admin/');
			}
			// Datatables Variables
			$draw = intval($this->input->get("draw"));
			$start = intval($this->input->get("start"));
			$length = intval($this->input->get("length"));
		}
		
		// Validate and add info in database
		public function add_overtime_bulanan() {
		
			if($this->input->post('add_type')=='overtime_bulanan') {	

				/* Define return | here result is used to return user data and error for error message */
				$Return = array('result'=>'', 'error'=>'', 'csrf_hash'=>'');
				$Return['csrf_hash'] = $this->security->get_csrf_hash();				
					
				$description = $this->input->post('description');
				$qt_description = htmlspecialchars(addslashes($description), ENT_QUOTES);
				
				if($this->input->post('company')==='') {
		        	$Return['error'] = $this->lang->line('error_company_field');

				} else if($this->input->post('employee_id')==='') {
					$Return['error'] = $this->lang->line('xin_error_employee_id');
				
				} else if($this->input->post('reports_to')==='') {
		        	$Return['error'] = $this->lang->line('xin_error_overtime_field');

		        } else if($this->input->post('overtime_type')==='') {
		        	$Return['error'] = $this->lang->line('xin_error_overtime_type');


		        } else if($this->input->post('attendance_date_m')==='') {
					$Return['error'] = $this->lang->line('xin_error_overtime_date');

				} else if($this->input->post('ov_status')==='') {
					$Return['error'] = $this->lang->line('xin_error_overtime_date_ov');

		        } else if($this->input->post('clock_in_1')==='') {
					$Return['error'] = $this->lang->line('xin_error_start_time');		
				
				} else if($this->input->post('clock_out_1')==='') {
					$Return['error'] = $this->lang->line('xin_error_end_time');

				} else if($this->input->post('description')==='') {
					$Return['error'] = $this->lang->line('xin_error_desc');			
							
				} 
						
				if($Return['error']!=''){
		       		$this->output($Return);
		    	}
				
				
				// $attendance_date = $this->input->post('attendance_date_m');

				$ov_status = $this->input->post('ov_status');

				// ====================================================================
				// jam 1
				// ====================================================================
					if ($ov_status == 'TS') {

						$attendance_date = $this->input->post('attendance_date_m');

						$clock_in_1         = $this->input->post('clock_in_1');
						$clock_out_1        = $this->input->post('clock_out_1');
						
						$clock_in2_1        = $attendance_date.' '.$clock_in_1.':00';
						$clock_out2_1       = $attendance_date.' '.$clock_out_1.':00';
						
						//total work
						$total_work_cin_1   =  new DateTime($clock_in2_1);
						$total_work_cout_1  =  new DateTime($clock_out2_1);
						
						$interval_cin_1     = $total_work_cout_1->diff($total_work_cin_1);
						$hours_in_1         = $interval_cin_1->format('%h');
						$minutes_in_1       = $interval_cin_1->format('%i');
						// $total_work_1       = $hours_in_1 .":".$minutes_in_1;

						$total_menit_1      = $hours_in_1*60+$minutes_in_1;

					} else if ($ov_status == 'TB') {

						$attendance_date = $this->input->post('attendance_date_m');

						$attendance_date_next = date('Y-m-d', strtotime ('+1 days', strtotime ( $this->input->post('attendance_date_m') )));

					
						$clock_in_1         = $this->input->post('clock_in_1');
						$clock_out_1        = $this->input->post('clock_out_1');
						
						$clock_in2_1        = $attendance_date.' '.$clock_in_1.':00';
						$clock_out2_1       = $attendance_date_next.' '.$clock_out_1.':00';
						
						//total work
						$total_work_cin_1   =  new DateTime($clock_in2_1);
						$total_work_cout_1  =  new DateTime($clock_out2_1);
						
						$interval_cin_1     = $total_work_cout_1->diff($total_work_cin_1);
						$hours_in_1         = $interval_cin_1->format('%h');
						$minutes_in_1       = $interval_cin_1->format('%i');
						// $total_work_1       = $hours_in_1 .":".$minutes_in_1;

						$total_menit_1      = $hours_in_1*60+$minutes_in_1;

					}


				// ====================================================================
				// jam 2
				// ====================================================================

					if ($ov_status == 'TS') {

						$attendance_date = $this->input->post('attendance_date_m');

						$clock_in_2         = $this->input->post('clock_in_2');
						$clock_out_2        = $this->input->post('clock_out_2');					
						
						$clock_in2_2        = $attendance_date.' '.$clock_in_2.':00';
						$clock_out2_2       = $attendance_date.' '.$clock_out_2.':00';
						
						//total work
						$total_work_cin_2   =  new DateTime($clock_in2_2);
						$total_work_cout_2  =  new DateTime($clock_out2_2);
						
						$interval_cin_2     = $total_work_cout_2->diff($total_work_cin_2);
						$hours_in_2         = $interval_cin_2->format('%h');
						$minutes_in_2       = $interval_cin_2->format('%i');
						$total_work_2       = $hours_in_2 .":".$minutes_in_2;

						$total_menit_2      = $hours_in_2*60+$minutes_in_2;

						
					} else if ($ov_status == 'TB') {

						$attendance_date = $this->input->post('attendance_date_m');

						$attendance_date_next = date('Y-m-d', strtotime ('+1 days', strtotime ( $this->input->post('attendance_date_m') )));

						$clock_in_2         = $this->input->post('clock_in_2');
						$clock_out_2        = $this->input->post('clock_out_2');					
						
						$clock_in2_2        = $attendance_date.' '.$clock_in_2.':00';
						$clock_out2_2       = $attendance_date_next.' '.$clock_out_2.':00';
						
						//total work
						$total_work_cin_2   =  new DateTime($clock_in2_2);
						$total_work_cout_2  =  new DateTime($clock_out2_2);
						
						$interval_cin_2     = $total_work_cout_2->diff($total_work_cin_2);
						$hours_in_2         = $interval_cin_2->format('%h');
						$minutes_in_2       = $interval_cin_2->format('%i');
						$total_work_2       = $hours_in_2 .":".$minutes_in_2;

						$total_menit_2      = $hours_in_2*60+$minutes_in_2;

					}

				// =====================================================
					$total_menit        = $total_menit_1+$total_menit_2;
				// =====================================================

			
				// =====================================================

					$employee_ids = implode(',',$_POST['employee_id']);
					$employee_id = $employee_ids;

					if(isset($_POST['employee_id'])) {
						$employee_ids = implode(',',$_POST['employee_id']);
						$employee_id = $employee_ids;
					} else {
						$employee_id = '';
					}
				
				
					$session_id = $this->session->userdata('user_id');
				    $user_create = $session_id['user_id'];
					
				foreach(explode(',',$employee_id) as $uid) {
					$user = $this->Employees_model->read_employee_information($uid);
					if(!is_null($user)){
						
						// - Gaji Pokok
							$jumlah_basic_salary = $user[0]->basic_salary;
						
						// - Tunj. Jabatan
							$tunj_jabatan = $this->Employees_model->read_salary_allowances_jabatan($user[0]->user_id,$attendance_date);
							$count_tunj_jabatan  = $this->Employees_model->count_employee_allowances_jabatan($user[0]->user_id,$attendance_date);
							$jumlah_tunj_jabatan  = 0;
							if($count_tunj_jabatan > 0) {
								foreach($tunj_jabatan as $sl_tunj_jabatan){
								  		$jumlah_tunj_jabatan += $sl_tunj_jabatan->tnj_jabatan;
								}
							} else {
								$jumlah_tunj_jabatan = 0;
							}

						
						// ====================================================================================
						// Hitung Upah
						// ===================================================================================

						$jumlah_upah    = $jumlah_basic_salary+$jumlah_tunj_jabatan;

						// ====================================================================================
						// Hitung Lemburan
						// ===================================================================================

						if ($total_menit < 60) {
							$jam_1          = round($total_menit/60,2) ;
						} else {
							$jam_1          = 1 ;
						}
						
						$biaya_lembur_jam_1   = round($jam_1*1.5*1/173*$jumlah_upah,2);

						$jam_2          = round($total_menit/60,2)-1 ; 
						$biaya_lembur_jam_2   = round($jam_2*2*1/173*$jumlah_upah,2);

						// ====================================================================================
						// Total Lemburan
						// ===================================================================================
						$overtime_total = $biaya_lembur_jam_1+$biaya_lembur_jam_2;

						$data_sallary   = array(
							'overtime_date'	         => $attendance_date,
							'ov_status'              => $ov_status,
							'overtime_jenis'  	     => 'Bulanan',
							'clock_in_m'        	 => $clock_in_1,
							'clock_out_m'       	 => $clock_out_1,	
							'clock_in_n'        	 => $clock_in_2,
							'clock_out_n'       	 => $clock_out_2,	
							'employee_id'            => $uid,
							'overtime_type'      	 => $this->input->post('overtime_type'),	
							'reports_to'			 => $this->input->post('reports_to'),	
							'description'       	 => $qt_description,					
							'basic_salary'	         => $jumlah_basic_salary,
							'tunj_jabatan'	         => $jumlah_tunj_jabatan,
						
							'upah_salary'	         => $jumlah_upah,
							'overtime_menit_total'	 => $total_menit,
							'overtime_hours_total'	 => round($total_menit/60,2),
							'overtime_hours'         => $jam_1,
							'overtime_rate'    	     => $biaya_lembur_jam_1,
							'overtime_hours_next'    => $jam_2,
							'overtime_rate_next'     => $biaya_lembur_jam_2,
							'overtime_total'         => $overtime_total
							
						);

						
						$sallary_result = $this->Overtime_model->add_salary_overtime($data_sallary);
						
					}
				}			

				$data = array(
					'company_id'       		 => $this->input->post('company'),
					'employee_id'       	 => $employee_id,
					'overtime_type_id'  	 => $this->input->post('overtime_type'),
					'overtime_jenis'  	     => 'Bulanan',	
					'reports_to'			 => $this->input->post('reports_to'),
					'attendance_date_m' 	 => $attendance_date,	
					'ov_status'              => $ov_status,			
					'clock_in_m'        	 => $clock_in_1,
					'clock_out_m'       	 => $clock_out_1,				
					'clock_in_n'        	 => $clock_in_2,
					'clock_out_n'       	 => $clock_out_2,
					'total_menit'	         => $total_menit,	
					'total_hours'       	 => round($total_menit/60,2),
					'description'       	 => $qt_description,
					'created_at'        	 => date('d-m-Y h:i:s'),
					'created_by'        	 => $user_create,
					'flag'					 => 'y'
				);
				$iresult = $this->Overtime_model->add($data);
				
				// echo "<pre>";
				// print_r($this->db->last_query());
				// echo "</pre>";
				// die();

				if ($iresult) {			
					$Return['result'] = $this->lang->line('xin_success_overtime_added');				
				} else {
					$Return['error'] = $this->lang->line('xin_error_msg');
				}

				$this->output($Return);
				exit;
			}
		}

		public function read_bulanan()
		{
			$data['title'] = $this->Core_model->site_title();
			$id = $this->input->get('overtime_id');
			$result = $this->Overtime_model->read_overtime_information($id);

			// jam 1
			$in_time_1   = new DateTime($result[0]->clock_in_m);
			$out_time_1  = new DateTime($result[0]->clock_out_m);
			
			$clock_in_1  = $in_time_1->format('H:i');
			if($result[0]->clock_out_m == '') {
				$clock_out_1 = '';
			} else {
				$clock_out_1 = $out_time_1->format('H:i');
			}

			// jam 2
			$in_time_2   = new DateTime($result[0]->clock_in_n);
			$out_time_2  = new DateTime($result[0]->clock_out_n);
			
			$clock_in_2  = $in_time_2->format('H:i');
			if($result[0]->clock_out_n == '') {
				$clock_out_2 = '';
			} else {
				$clock_out_2 = $out_time_2->format('H:i');
			}

			$data = array(
					'title' 			 => $this->Core_model->site_title(),				
					'company_id' 		 => $result[0]->company_id,
					'overtime_id' 		 => $result[0]->overtime_id,
					'employee_id' 		 => $result[0]->employee_id,
					'overtime_type_id' 	 => $result[0]->overtime_type_id,
					'ereports_to' 	     => $result[0]->reports_to,
					'attendance_date_m'  => $result[0]->attendance_date_m,
					'ov_status'          => $result[0]->ov_status,

					'clock_in_m' 		 => $clock_in_1,
					'clock_out_m' 		 => $clock_out_1,

					'clock_in_n' 		 => $clock_in_2,
					'clock_out_n' 		 => $clock_out_2,

					'description'        => $result[0]->description,			
					'all_employees'      => $this->Core_model->all_employees(),
					'all_overtime_types' => $this->Overtime_model->all_overtime_types(),				
					'all_companies'      => $this->Company_model->get_company()
			);
			$session = $this->session->userdata('username');
			if(!empty($session)){ 
				$this->load->view('admin/overtime/dialog_overtime_bulanan', $data);
			} else {
				redirect('admin/');
			}
		}	
	
		// Validate and update info in database
		public function update_bulanan() {
		
			if($this->input->post('edit_type')=='overtime') {
				
			$id = $this->uri->segment(4);
			
			/* Define return | here result is used to return user data and error for error message */
			$Return = array('result'=>'', 'error'=>'', 'csrf_hash'=>'');
			$Return['csrf_hash'] = $this->security->get_csrf_hash();
				
			/* Server side PHP input validation */
			$description = $this->input->post('description');
			$qt_description = htmlspecialchars(addslashes($description), ENT_QUOTES);
			
			if($this->input->post('company')==='') {
	        	$Return['error'] = $this->lang->line('error_company_field');

			} else if($this->input->post('employee_id')==='') {
				$Return['error'] = $this->lang->line('xin_error_employee_id');

			} else if($this->input->post('reports_to')==='') {
		        	$Return['error'] = $this->lang->line('xin_error_overtime_field');
			
			} else if($this->input->post('overtime_type')==='') {
	        	$Return['error'] = $this->lang->line('xin_error_overtime_type');

	        } else if($this->input->post('attendance_date_e')==='') {
				$Return['error'] = $this->lang->line('xin_error_overtime_date');

			 } else if($this->input->post('ov_status')==='') {
				$Return['error'] = $this->lang->line('xin_error_overtime_date_ov');

	        } else if($this->input->post('clock_in_m')==='') {
				$Return['error'] = $this->lang->line('xin_error_start_time');		
			
			} else if($this->input->post('clock_out_m')==='') {
				$Return['error'] = $this->lang->line('xin_error_end_time');		
			
			} else if($this->input->post('description')==='') {
				$Return['error'] = $this->lang->line('xin_error_desc');

			} 
					
			if($Return['error']!=''){
	       		$this->output($Return);
	    	}		
			
			// ========================
			// $attendance_date = $this->input->post('attendance_date_e');

			$ov_status = $this->input->post('ov_status');

			// ====================================================================
			// jam 1
			// ====================================================================

				if ($ov_status == 'TS') {

					$attendance_date = $this->input->post('attendance_date_e');

					$clock_in_1         = $this->input->post('clock_in_1');
					$clock_out_1        = $this->input->post('clock_out_1');
					
					$clock_in2_1        = $attendance_date.' '.$clock_in_1.':00';
					$clock_out2_1       = $attendance_date.' '.$clock_out_1.':00';
					
					//total work
					$total_work_cin_1   =  new DateTime($clock_in2_1);
					$total_work_cout_1  =  new DateTime($clock_out2_1);
					
					$interval_cin_1     = $total_work_cout_1->diff($total_work_cin_1);
					$hours_in_1         = $interval_cin_1->format('%h');
					$minutes_in_1       = $interval_cin_1->format('%i');
					// $total_work_1       = $hours_in_1 .":".$minutes_in_1;

					$total_menit_1      = $hours_in_1*60+$minutes_in_1;

				} else if ($ov_status == 'TB') {

					$attendance_date = $this->input->post('attendance_date_e');

					$attendance_date_next = date('Y-m-d', strtotime ('+1 days', strtotime ( $this->input->post('attendance_date_e') )));

				
					$clock_in_1         = $this->input->post('clock_in_1');
					$clock_out_1        = $this->input->post('clock_out_1');
					
					$clock_in2_1        = $attendance_date.' '.$clock_in_1.':00';
					$clock_out2_1       = $attendance_date_next.' '.$clock_out_1.':00';
					
					//total work
					$total_work_cin_1   =  new DateTime($clock_in2_1);
					$total_work_cout_1  =  new DateTime($clock_out2_1);
					
					$interval_cin_1     = $total_work_cout_1->diff($total_work_cin_1);
					$hours_in_1         = $interval_cin_1->format('%h');
					$minutes_in_1       = $interval_cin_1->format('%i');
					// $total_work_1       = $hours_in_1 .":".$minutes_in_1;

					$total_menit_1      = $hours_in_1*60+$minutes_in_1;

				}
				

			// ====================================================================
			// jam 2
			// ====================================================================
				if ($ov_status == 'TS') {

					$attendance_date = $this->input->post('attendance_date_e');

					$clock_in_2         = $this->input->post('clock_in_2');
					$clock_out_2        = $this->input->post('clock_out_2');
					
					$clock_in2_2        = $attendance_date.' '.$clock_in_2.':00';
					$clock_out2_2       = $attendance_date.' '.$clock_out_2.':00';
					
					//total work
					$total_work_cin_2   =  new DateTime($clock_in2_2);
					$total_work_cout_2  =  new DateTime($clock_out2_2);
					
					$interval_cin_2     = $total_work_cout_2->diff($total_work_cin_2);
					$hours_in_2         = $interval_cin_2->format('%h');
					$minutes_in_2       = $interval_cin_2->format('%i');
					$total_work_2       = $hours_in_2 .":".$minutes_in_2;

					$total_menit_2      = $hours_in_2*60+$minutes_in_2;

				} else if ($ov_status == 'TB') {

					$attendance_date = $this->input->post('attendance_date_e');

					$attendance_date_next = date('Y-m-d', strtotime ('+1 days', strtotime ( $this->input->post('attendance_date_e') )));

					$clock_in_2         = $this->input->post('clock_in_2');
					$clock_out_2        = $this->input->post('clock_out_2');
					
					$clock_in2_2        = $attendance_date.' '.$clock_in_2.':00';
					$clock_out2_2       = $attendance_date_next.' '.$clock_out_2.':00';
					
					//total work
					$total_work_cin_2   =  new DateTime($clock_in2_2);
					$total_work_cout_2  =  new DateTime($clock_out2_2);
					
					$interval_cin_2     = $total_work_cout_2->diff($total_work_cin_2);
					$hours_in_2         = $interval_cin_2->format('%h');
					$minutes_in_2       = $interval_cin_2->format('%i');
					$total_work_2       = $hours_in_2 .":".$minutes_in_2;

					$total_menit_2      = $hours_in_2*60+$minutes_in_2;

				}

			// =====================================================
				$total_menit        = $total_menit_1+$total_menit_2;
			// =====================================================

			if(isset($_POST['employee_id'])) {
				$employee_ids = implode(',',$_POST['employee_id']);
				$employee_id = $employee_ids;
			} else {
				$employee_id = '';
			}

			// ========================================================

			foreach(explode(',',$employee_id) as $uid) {
				$user = $this->Employees_model->read_employee_information($uid);
				if(!is_null($user)){
					$result = $this->Overtime_model->delete_record_salary_overtime($uid,$attendance_date);

				}
			}

			// ========================================================

			$data = array(
				'company_id'       		 => $this->input->post('company'),
				'employee_id'       	 => $employee_id,
				'overtime_type_id'  	 => $this->input->post('overtime_type'),	
				'reports_to'			 => $this->input->post('reports_to'),
				'attendance_date_m' 	 => $attendance_date,
				'ov_status'              => $ov_status,
				'clock_in_m'        	 => $clock_in_1,
				'clock_out_m'       	 => $clock_out_1,
				'clock_in_n'        	 => $clock_in_2,
				'clock_out_n'       	 => $clock_out_2,
				'total_menit'	         => $total_menit,	
				'total_hours'       	 => round($total_menit/60,2),
				'flag'					 => 'y',
				'description'       	 => $qt_description			
			);
			
			$result = $this->Overtime_model->update_record($data,$id);		
			
			if ($result == TRUE) {

				foreach(explode(',',$employee_id) as $uid) {
					$user = $this->Employees_model->read_employee_information($uid);
					if(!is_null($user)){
						
						// - Gaji Pokok
						$jumlah_basic_salary = $user[0]->basic_salary;
						
						// - Tunj. Jabatan
						$tunj_jabatan = $this->Employees_model->read_salary_allowances_jabatan($user[0]->user_id,$attendance_date);
						$count_tunj_jabatan  = $this->Employees_model->count_employee_allowances_jabatan($user[0]->user_id,$attendance_date);
						$jumlah_tunj_jabatan  = 0;
						if($count_tunj_jabatan > 0) {
							foreach($tunj_jabatan as $sl_tunj_jabatan){
							  		$jumlah_tunj_jabatan += $sl_tunj_jabatan->tnj_jabatan;
							}
						} else {
							$jumlah_tunj_jabatan = 0;
						}
					

						// ====================================================================================
						// Hitung Upah
						// ===================================================================================

						$jumlah_upah    = $jumlah_basic_salary+$jumlah_tunj_jabatan;

						// ====================================================================================
						// Hitung Lemburan
						// ===================================================================================

						if ($total_menit < 60) {
							$jam_1          = round($total_menit/60,2) ;
						} else {
							$jam_1          = 1 ;
						}
						
						$biaya_lembur_jam_1   = round($jam_1*1.5*1/173*$jumlah_upah,2);

						$jam_2          = round($total_menit/60,2)-1 ; 
						$biaya_lembur_jam_2   = round($jam_2*2*1/173*$jumlah_upah,2);

						// ====================================================================================
						// Total Lemburan
						// ===================================================================================
						$overtime_total = $biaya_lembur_jam_1+$biaya_lembur_jam_2;

						$data_sallary   = array(
							'overtime_date'	         => $attendance_date,
							'ov_status'              => $ov_status,
							'clock_in_m'        	 => $clock_in_1,
							'clock_out_m'       	 => $clock_out_1,	
							'clock_in_n'        	 => $clock_in_2,
							'clock_out_n'       	 => $clock_out_2,	
							'employee_id'            => $uid,
							'overtime_type'      	 => $this->input->post('overtime_type'),	
							'reports_to'			 => $this->input->post('reports_to'),	
							'description'       	 => $qt_description,					
							'basic_salary'	         => $jumlah_basic_salary,
							'tunj_jabatan'	         => $jumlah_tunj_jabatan,
							// 'tunj_productifitas'	 => $jumlah_tunj_productifitas,
							// 'tunj_transportasi'	     => $jumlah_tunj_transportasi,
							// 'tunj_komunikasi'	     => $jumlah_tunj_komunikasi,
							'upah_salary'	         => $jumlah_upah,
							'overtime_menit_total'	 => $total_menit,
							'overtime_hours_total'	 => round($total_menit/60,2),
							'overtime_hours'         => $jam_1,
							'overtime_rate'    	     => $biaya_lembur_jam_1,
							'overtime_hours_next'    => $jam_2,
							'overtime_rate_next'     => $biaya_lembur_jam_2,
							'overtime_total'         => $overtime_total
							
						);
						$sallary_result = $this->Overtime_model->add_salary_overtime($data_sallary);
						
					}
				}


				$Return['result'] = $this->lang->line('xin_success_overtime_updated');			
			} else {
				$Return['error'] = $this->lang->line('xin_error_msg');
			}

			$this->output($Return);
			exit;
			}
		}
		
		// overtime details
		public function details_bulanan()
	    {
			$session = $this->session->userdata('username');
			if(empty($session)){ 
				redirect('admin/');
			}
			$data['title'] = $this->Core_model->site_title();
			
			$id     = $this->uri->segment(4);
			$result = $this->Overtime_model->read_overtime_information($id);
			
			if(is_null($result)){
				redirect('admin/overtime');
			}
			// get overtime type
			$type = $this->Overtime_model->read_overtime_type_information($result[0]->overtime_type_id);
			
			if(!is_null($type)){
				$itype = $type[0]->type;
			} else {
				$itype = '--';	
			}
						
			// get report to
			$reports_to = $this->Core_model->read_user_info($result[0]->reports_to);
			// user full name
			if(!is_null($reports_to)){
				$manager_name = $reports_to[0]->first_name.' '.$reports_to[0]->last_name;
			} else {
				$manager_name = '?';	
			}

			$cek_ov_status    = $result[0]->ov_status;
				if ($cek_ov_status == '') {
					$ov_status = '<span class="badge bg-red"><i class="fa fa-question"></i></span>';
				} else {

					if ($cek_ov_status == 'TS') {

						$ov_status = '<span class="blink blink-one">Lembur di Tanggal Sama </span>';

					} else {

						$ov_status = '<span class="blink blink-one">Lembur di Tanggal Berikutnya</span> ';

					}

					
				}

			$data = array(
					'title' 			=> "Detail Lembur Bulanan | ". $this->Core_model->site_title(),
					'icon'      		=> "<i class='fa fa-eye'></i>",
					'breadcrumbs' 		=> "Detail Lembur Bulanan",
					'path_url' 			=> 'overtime_details_bulanan',
					
					'overtime_id' 		=> $result[0]->overtime_id,
					'company_id' 		=> $result[0]->company_id,
					'type'           	=> $itype,				
					'manager_name' 	    => $manager_name,
					'attendance_date_m' => $result[0]->attendance_date_m,	
					'ov_status'         => $ov_status,				
					'clock_in_m' 		=> $result[0]->clock_in_m,
					'clock_out_m' 		=> $result[0]->clock_out_m,
					'clock_in_n' 		=> $result[0]->clock_in_n,
					'clock_out_n' 		=> $result[0]->clock_out_n,
					'created_at' 		=> $result[0]->created_at,
					'description' 		=> $result[0]->description,	
					'remarks'			=> $result[0]->remarks,
					'overtime_status' 	=> $result[0]->overtime_status,				
					'employee_id' 		=> $result[0]->employee_id,
					'all_employees' 	=> $this->Core_model->all_employees(),
					'all_companies' 	=> $this->Company_model->get_company()
			);
			
						
			$role_resources_ids = $this->Core_model->user_role_resource();
			
			if(in_array('0685',$role_resources_ids)) {
				if(!empty($session)){ 
					$data['subview'] = $this->load->view("admin/overtime/overtime_details_bulanan", $data, TRUE);
					$this->load->view('admin/layout/layout_main', $data); //page load
				} else {
					redirect('admin/');
				}
			} else {
				redirect('admin/dashboard');
			}		  
	    }
		 
		 // Validate and update info in database
		public function update_status_bulanan() 
		{
		
			if($this->input->post('edit_type')=='update_status_bulanan') {
				
				$id = $this->input->post('token_status');
				/* Define return | here result is used to return user data and error for error message */
				$Return = array('result'=>'', 'error'=>'', 'csrf_hash'=>'');
				$Return['csrf_hash'] = $this->security->get_csrf_hash();
			
				$data = array(			
					'overtime_status' => $this->input->post('status'),
					'remarks' => $this->input->post('remarks')
				);
				
				$result = $this->Overtime_model->update_status($data,$id);		
							
				if ($result == TRUE) {
					$Return['result'] = $this->lang->line('xin_success_overtime_status_updated');
				} else {
					$Return['error'] = $this->lang->line('xin_error_msg');
				}
				$this->output($Return);
				exit;
			}
		}
		
		public function delete_bulanan() 
		{
			/* Define return | here result is used to return user data and error for error message */
			$Return              = array('result'=>'', 'error'=>'', 'csrf_hash'=>'');
			$id                  = $this->uri->segment(4);
			$Return['csrf_hash'] = $this->security->get_csrf_hash();		
			
				$karyawan = $this->Overtime_model->read_overtime_information($id);
			
			// $Return['result'] = $karyawan[0]->employee_id;

			if($karyawan[0]->employee_id != '') {
				foreach(explode(',',$karyawan[0]->employee_id) as $uid) {
					$user = $this->Employees_model->read_employee_information($uid);
					if(!is_null($user)){
						$eresult = $this->Overtime_model->delete_record_salary_overtime($uid,$karyawan[0]->attendance_date_m);

					}
				}
			}

			$result = $this->Overtime_model->delete_record($id);
			if(isset($id)) {
				$Return['result'] = $this->lang->line('xin_success_overtime_deleted');
			} else {
				$Return['error'] = $this->lang->line('xin_error_msg');
			}
			$this->output($Return);
		}
	
}
