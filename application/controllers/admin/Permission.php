<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Permission extends MY_Controller {
	
	 public function __construct() {
        parent::__construct();
		$this->load->library('email');
		//load the model
		$this->load->model("Permission_model");
		$this->load->model("Employees_model");
		$this->load->model("Core_model");		
		$this->load->model("Department_model");
		$this->load->model("Company_model");
		$this->load->model("Designation_model");
		$this->load->model("Resignation_model");
		
	}
	
	/*Function to set JSON output*/
	public function output($Return=array()){
		/*Set response header*/
		header("Access-Control-Allow-Origin: *");
		header("Content-Type: application/json; charset=UTF-8");
		/*Final JSON response*/
		exit(json_encode($Return));
	}
	
	// ================================================================================================================
	// 01. CUTI
	// ================================================================================================================

		// ================================================================================================================
		// TABEL
		// ================================================================================================================
			public function leave() 
			{
				
				$session = $this->session->userdata('username');
				if(empty($session)){ 
					redirect('admin/');
				}
				$data['title']             = $this->lang->line('left_leave').' | '.$this->Core_model->site_title();
				$data['icon']              = '<i class="fa fa-tags"></i> ';
				$data['desc']              = '<b>INFORMASI</b> : Pengajuan untuk Cuti Karyawan';
				$data['breadcrumbs']       = '<i class="fa fa-tags"></i> '.$this->lang->line('left_leave');

				$data['all_employees']     = $this->Core_model->all_employees();
				$data['get_all_companies'] = $this->Company_model->get_company();
				$data['all_leave_types']   = $this->Permission_model->all_leave_types();
				
				$role_resources_ids        = $this->Core_model->user_role_resource();
				$data['path_url']          = 'leave';
				
				
				// reports to 
		 		$reports_to = get_reports_team_data($session['user_id']);
				if(in_array('0711',$role_resources_ids) || $reports_to > 0) {
					if(!empty($session)){ 
						$data['subview'] = $this->load->view("admin/permission/leave", $data, TRUE);
						$this->load->view('admin/layout/layout_main', $data); //page load
					} else {
						redirect('admin/');
					}
				} else {
					redirect('admin/dashboard');
				}
		    }		 		
			
			
			public function leave_list() 
			{

				$data['title'] = $this->Core_model->site_title();
				$session = $this->session->userdata('username');
				if(!empty($session)){ 
					$this->load->view("admin/permission/leave", $data);
				} else {
					redirect('admin/');
				}
				// Datatables Variables
				$draw   = intval($this->input->get("draw"));
				$start  = intval($this->input->get("start"));
				$length = intval($this->input->get("length"));
				
				$data   = array();
				$role_resources_ids = $this->Core_model->user_role_resource();
				$user_info = $this->Core_model->read_user_info($session['user_id']);
				
				$leave = $this->Permission_model->get_leaves();
				
				// reports to 
		 		$reports_to = get_reports_team_data($session['user_id']);
				$no = 1;
				foreach($leave->result() as $r) {
					  
					// get start date and end date
					$user = $this->Core_model->read_user_info($r->employee_id);
					if(!is_null($user)){
						$full_name = $user[0]->first_name. ' '.$user[0]->last_name;
						// department
						$department = $this->Department_model->read_department_information($user[0]->department_id);
						if(!is_null($department)){
							$department_name = $department[0]->department_name;
						} else {
							$department_name = '--';	
						}

						$designation = $this->Designation_model->read_designation_information($user[0]->designation_id);
						if(!is_null($designation)){
							$designation_name = $designation[0]->designation_name;
						} else {
							$designation_name = '--';	
						}

						$date_of_joining =  $user[0]->date_of_joining ;

						//================================
						$tgl_skrg = date("Y-m-d");

						$tanggal_rektrumen = new DateTime( $user[0]->date_of_joining);
						$tanggal_sekarang = new DateTime($tgl_skrg);
						$selisih = $tanggal_rektrumen->diff($tanggal_sekarang);
						
						$jumlah_hari = $selisih->format('%a');

					

						$lama_kerja = round($jumlah_hari/365,1);


					} else {
						$full_name = '--';	
						$department_name = '--';
					}
					 
					// get leave type
					$leave_type = $this->Permission_model->read_leave_type_information($r->leave_type_id);
					if(!is_null($leave_type)){
						$type_name = $leave_type[0]->type_name;
					} else {
						$type_name = '--';	
					}
					
					// get company
					$company = $this->Core_model->read_company_info($r->company_id);
					if(!is_null($company)){
						$comp_name = $company[0]->name;
					} else {
						$comp_name = '--';	
					}
					 
					$datetime1 = new DateTime($r->from_date);
					$datetime2 = new DateTime($r->to_date);
					$interval = $datetime1->diff($datetime2);
					if(strtotime($r->from_date) == strtotime($r->to_date)){
						$no_of_days =1;
					} else {
						$no_of_days = $interval->format('%a') + 1;
					}
					$applied_on = $this->Core_model->set_date_format($r->applied_on);
					
					/*$duration = $this->Core_model->set_date_format($r->from_date).' '.$this->lang->line('dashboard_to').' '.$this->Core_model->set_date_format($r->to_date).'<br>'.$this->lang->line('xin_hris_total_days').': '.$no_of_days;*/
					
					if($r->is_half_day == 1){
					
						$duration = $this->Core_model->set_date_format($r->from_date).' '.$this->lang->line('dashboard_to').' '.$this->Core_model->set_date_format($r->to_date).'<br><small class="text-muted"><i class="fa fa-angle-double-right"></i> '.$this->lang->line('xin_hris_total_days').': '.$this->lang->line('xin_hr_leave_half_day').'</small>';
					
					} else {
					
						$duration = $this->Core_model->set_date_format($r->from_date).' '.$this->lang->line('dashboard_to').' '.$this->Core_model->set_date_format($r->to_date).'<br><small class="text-muted"><i class="fa fa-angle-double-right"></i> '.$this->lang->line('xin_hris_total_days').': '.$no_of_days.'</small>';
					
					}
					
					if($r->status==1): $status = '<span class="badge bg-orange">'.$this->lang->line('xin_pending').'</span>';
					elseif($r->status==2): $status = '<span class="badge bg-green">'.$this->lang->line('xin_approved').'</span>';
					elseif($r->status==4): $status = '<span class="badge bg-green">'.$this->lang->line('xin_role_first_level_approved').'</span>';
					else: $status = '<span class="badge bg-red">'.$this->lang->line('xin_rejected').'</span>'; endif;
					
					
					if(in_array('0713',$role_resources_ids)) { 
						// edit
						$edit = '<span data-toggle="tooltip" data-placement="top" title="'.$this->lang->line('xin_edit').'"><button type="button" class="btn icon-btn btn-xs btn-default waves-effect waves-light edit-data" data-toggle="modal" data-target=".edit-modal-data" data-leave_id="'. $r->leave_id.'" ><span class="fa fa-pencil"></span></button></span>';
					} else {
						$edit = '';
					}

					if(in_array('0714',$role_resources_ids)) { 
						// delete
						$delete = '<span data-toggle="tooltip" data-placement="top" title="'.$this->lang->line('xin_delete').'">
										<button type="button" class="btn icon-btn btn-xs btn-danger waves-effect waves-light delete" data-toggle="modal" data-target=".delete-modal" data-record-id="'. $r->leave_id . '">
											<span class="fa fa-trash"></span>
										</button>
									</span>';
					} else {
						$delete = '';
					}
					
					if(in_array('0715',$role_resources_ids)) { 
						// view
						$view = '<span data-toggle="tooltip" data-placement="top" title="Persetujuan">
									<a href="'.site_url().'admin/permission/leave_details/id/'.$r->leave_id.'/">
										<button type="button" class="btn icon-btn btn-xs btn-default waves-effect waves-light">
											<span class="fa fa-gavel"></span> Persetujuan
										</button>
									</a>
								</span>';
					} else {
						$view = '';
					}
					

					$combhr = $view.$edit.$delete;
					$itype_name = strtoupper($type_name).'<br>
					<small class="text-muted"><i class="fa fa-angle-double-right"></i> Reason :  <i> '.substr($r->remarks,0,30).'... </i></small> <br>
					<small class="text-muted"><i class="fa fa-angle-double-right"></i> Remark :  <i> '.substr($r->reason,0,30).'... </i></small>';
			
				   	$data[] = array(
						
						$combhr,
						$no,
						$status,
						$applied_on,
						$duration,
						$full_name.'<br><small class="text-muted"><i class="fa fa-angle-double-right"></i> '.$comp_name.' <i class="fa fa-angle-double-right"></i> '.$designation_name.' ('.$department_name.')</small>',
						$date_of_joining.'<br><small class="text-muted"><i class="fa fa-angle-double-right"></i> '.$lama_kerja.' Thn </small>',
						$itype_name						
						
				    );
				   	$no++;
			  }
			  	$output = array(
				   "draw" => $draw,
					"recordsTotal" => $leave->num_rows(),
					"recordsFiltered" => $leave->num_rows(),
					 "data" => $data
				);
				echo json_encode($output);
				exit();
		    }
		    
	    // ================================================================================================================
		// DETAIL
		// ================================================================================================================
		    public function leave_details() 
			{
				$session = $this->session->userdata('username');
				if(empty($session)){ 
					redirect('admin/');
				}
				
				$data['title'] = $this->Core_model->site_title();
				$leave_id = $this->uri->segment(5);
				// leave applications
				$result = $this->Permission_model->read_leave_information($leave_id);
				if(is_null($result)){
					redirect('admin/permission/leave');
				}
				$edata = array(
					'is_notify' => 0,
				);
				$this->Permission_model->update_leave_record($edata,$leave_id);
				// get leave types
				$type = $this->Permission_model->read_leave_type_information($result[0]->leave_type_id);
				if(!is_null($type)){
					$type_name = $type[0]->type_name;
				} else {
					$type_name = '--';	
				}
				// get employee
				$user = $this->Core_model->read_user_info($result[0]->employee_id);
				if(!is_null($user)){
					$full_name = $user[0]->first_name. ' '.$user[0]->last_name;
					$u_role_id = $user[0]->user_role_id;
					$department = $this->Department_model->read_department_information($user[0]->department_id);
					if(!is_null($department)){
						$department_name = $department[0]->department_name;
					} else {
						$department_name = '--';	
					}
					$designation = $this->Designation_model->read_designation_information($user[0]->designation_id);
					if(!is_null($designation)){
						$designation_name = $designation[0]->designation_name;
					} else {
						$designation_name = '--';	
					}
					
					$date_of_joining = $user[0]->date_of_joining ;

					//================================
					$tgl_skrg = date("Y-m-d");

					$datetime1 = new DateTime($user[0]->date_of_joining);
					$datetime2 = new DateTime($tgl_skrg);
					$interval = $datetime1->diff($datetime2);
					
					$no_of_days = $interval->format('%a');

					// $Cek_cuti_1 = date("Y-m",strtotime($date_of_joining));

					$lama_kerja = round($no_of_days/365,1);
					$tanggal_cuti  = $result[0]->from_date; 

					if ($lama_kerja >= 1) {
						

						$hak_cuti = 12;
					} else {

						$hak_cuti = 0;
					}
					

				} else {
					$full_name = '--';	
					$u_role_id = '--';
					$department_name = '--';
				}			 
				
				$data = array(
						'title' => 'Persetujuan Cuti | '.$this->Core_model->site_title(),
						'icon' => '<i class="fa fa-gavel"></i>',
						'type' => $type_name,
						'role_id' => $u_role_id,
						'full_name' => $full_name,
						'eemployee_id' => $result[0]->employee_id,
						'department_name' => $department_name,
						'designation_name' => $designation_name,
						'date_of_joining' => $date_of_joining,
						'lama_kerja' => $lama_kerja,
						'hak_cuti' => $hak_cuti,
						'leave_id' => $result[0]->leave_id,
						'employee_id' => $result[0]->employee_id,
						'company_id' => $result[0]->company_id,
						'leave_type_id' => $result[0]->leave_type_id,
						'from_date' => $result[0]->from_date,
						'to_date' => $result[0]->to_date,
						'applied_on' => $result[0]->applied_on,
						'reason' => $result[0]->reason,
						'remarks' => $result[0]->remarks,
						'status' => $result[0]->status,
						'leave_attachment' => $result[0]->leave_attachment,
						'is_half_day' => $result[0]->is_half_day,
						'created_at' => $result[0]->created_at,
						'all_employees' => $this->Core_model->all_employees(),
						'all_leave_types' => $this->Permission_model->all_leave_types(),
						);
				$data['breadcrumbs'] = 'Persetujuan Cuti';
				$data['path_url'] = 'leave_details';
				$role_resources_ids = $this->Core_model->user_role_resource();
				// reports to 
		 		$reports_to = get_reports_team_data($session['user_id']);
				if(in_array('0711',$role_resources_ids) || $reports_to > 0) {
					if(!empty($session)){ 
						$data['subview'] = $this->load->view("admin/permission/leave_details", $data, TRUE);
						$this->load->view('admin/layout/layout_main', $data); //page load
					} else {
						redirect('admin/');
					}
				} else {
					redirect('admin/dashboard');
				}			  
		    }
			public function read_leave_record()
			{
				$data['title'] = $this->Core_model->site_title();
				$leave_id = $this->input->get('leave_id');
				$result = $this->Permission_model->read_leave_information($leave_id);
				
				$data = array(
						'leave_id' => $result[0]->leave_id,
						'company_id' => $result[0]->company_id,
						'employee_id' => $result[0]->employee_id,
						'leave_type_id' => $result[0]->leave_type_id,
						'from_date' => $result[0]->from_date,
						'to_date' => $result[0]->to_date,
						'applied_on' => $result[0]->applied_on,
						'reason' => $result[0]->reason,
						'remarks' => $result[0]->remarks,
						'status' => $result[0]->status,
						'created_at' => $result[0]->created_at,
						'all_employees' => $this->Core_model->all_employees(),
						'get_all_companies' => $this->Company_model->get_company(),
						'all_leave_types' => $this->Permission_model->all_leave_types(),
						);
				$session = $this->session->userdata('username');
				if(!empty($session)){ 
					$this->load->view('admin/permission/dialog_leave', $data);
				} else {
					redirect('admin/');
				}
			}
		// ================================================================================================================
		// PROSES
		// ================================================================================================================
			public function add_leave() 
			{
			
				if($this->input->post('add_type')=='leave') {		
				/* Define return | here result is used to return user data and error for error message */
				$Return = array('result'=>'', 'error'=>'', 'csrf_hash'=>'');
				$Return['csrf_hash'] = $this->security->get_csrf_hash();
				
				$start_date = $this->input->post('start_date');
				$end_date   = $this->input->post('end_date');
				
			
				$st_date    = strtotime($start_date);
				$ed_date    = strtotime($end_date);

				$reason     = $this->input->post('remarks');
				$qt_reason  = htmlspecialchars(addslashes($reason), ENT_QUOTES);
				
				/* Server side PHP input validation */		
				if($this->input->post('leave_type')==='') {
		        	$Return['error'] = $this->lang->line('xin_error_leave_type_field');
				} else if($this->input->post('start_date')==='') {
		        	$Return['error'] = $this->lang->line('xin_error_start_date');
				} else if($this->input->post('end_date')==='') {
		        	$Return['error'] = $this->lang->line('xin_error_end_date');
				} else if($st_date > $ed_date) {
					$Return['error'] = $this->lang->line('xin_error_start_end_date');
				} else if($this->input->post('company_id')==='') {
					$Return['error'] = $this->lang->line('error_company_field');
				} else if($this->input->post('employee_id')==='') {
					$Return['error'] = $this->lang->line('xin_error_employee_id');
				} else if($this->input->post('reason')==='') {
					$Return['error'] = $this->lang->line('xin_error_leave_type_reason');
				}
				
				$datetime1  = new DateTime($this->input->post('start_date'));
				$datetime2  = new DateTime($this->input->post('end_date'));
				$interval   = $datetime1->diff($datetime2);
				$no_of_days = $interval->format('%a') + 1;
				
				// if($this->input->post('leave_half_day')==1 && $no_of_days>1) {
				// 	$Return['error'] = $this->lang->line('xin_hr_cant_appply_morethan').' 1 '.$this->lang->line('xin_day');
				// }

				if($Return['error']!=''){
		       		$this->output($Return);
		    	}
						
				if($this->input->post('start_date')!=''){	
					
					//$user_info_all = $this->Employees_model->read_employee_information($this->input->post('employee_id'));
					$eremaining_leave = 0;//$user_info_all[0]->leave_days;
					
					$tahun = date('Y', strtotime($this->input->post('start_date')));

					$count_l = 0;
					$leave_halfday_cal = employee_leave_halfday_cal($tahun, $this->input->post('leave_type'),$this->input->post('employee_id'));
					foreach($leave_halfday_cal as $lhalfday):
						$count_l += 0.5;
					endforeach;
					
					$remaining_leave = count_leaves_info($tahun,$this->input->post('leave_type'),$this->input->post('employee_id'));
					$remaining_leave = $remaining_leave - $count_l;
					
					$type = $this->Permission_model->read_leave_type_information($this->input->post('leave_type'));
					if(!is_null($type)){
						$type_name = $type[0]->type_name;
						$total = $type[0]->days_per_year;
						$leave_remaining_total = $total - $remaining_leave;
					} else {
						$type_name = '--';	
						$leave_remaining_total = 0;
					}
							
					if($this->input->post('leave_type')==3 || $this->input->post('leave_type')==5 || $this->input->post('leave_type')==7) {
						$leave_remaining_total = $leave_remaining_total + $eremaining_leave;
					} else {
						$leave_remaining_total = $leave_remaining_total;
					}

					
					if($leave_remaining_total < 0.4){
						$Return['error'] = $this->lang->line('xin_leave_limit_msg').' '.$leave_remaining_total.' '.$this->lang->line('xin_hrsale_leave_quota_completed') .$type_name;
					}
					
				}
				if($Return['error']!=''){
		       		$Return['csrf_hash'] = $this->security->get_csrf_hash();
					$this->output($Return);
		    	}	
				// if($this->input->post('leave_half_day')!=1){
				// 	$leave_half_day_opt = 0;
				// } else {
				// 	$leave_half_day_opt = $this->input->post('leave_half_day');
				// }
				if(is_uploaded_file($_FILES['attachment']['tmp_name'])) {
					//checking image type
					$allowed =  array('png','jpg','jpeg','pdf','gif');
					$filename = $_FILES['attachment']['name'];
					$ext = pathinfo($filename, PATHINFO_EXTENSION);
					
					if(in_array($ext,$allowed)){
						$tmp_name    = $_FILES["attachment"]["tmp_name"];
						$profile     = "uploads/leave/";
						$set_img     = base_url()."uploads/leave/";					
						$name        = basename($_FILES["attachment"]["name"]);
						$newfilename = 'leave_'.round(microtime(true)).'.'.$ext;
						move_uploaded_file($tmp_name, $profile.$newfilename);
						$fname = $newfilename;			
					} else {
						$Return['error'] = $this->lang->line('xin_error_attatchment_type');
					}
				} else {
					$fname = '';
				}
				
				$data = array(
					'employee_id'      => $this->input->post('employee_id'),
					'company_id'       => $this->input->post('company_id'),
					'leave_type_id'    => $this->input->post('leave_type'),
					'from_date'        => $this->input->post('start_date'),
					'to_date'          => $this->input->post('end_date'),
					'applied_on'       => date('Y-m-d h:i'),
					'reason'           => $qt_reason,		
					'leave_attachment' => $fname,
					'status'           => '1',
					'is_notify'        => '1',
					'is_half_day'      => '0',

					'created_at'       => date('Y-m-d h:i')
				);
				$result = $this->Permission_model->add_leave_record($data);
				
				if ($result == TRUE) {
					
					// ----------------------------------------------------------------
					// SIMPAN LOG USER
					// ----------------------------------------------------------------
						// Simpan : Log Tambah
						$this->Core_model->add_log_activity('Pengajuan','Cuti','Tambah Cuti Baru','Tambah','Sukses');
					// ----------------------------------------------------------------

					$Return['result'] = 'Proses Cuti Berhasil Ditambahkan';
					
				} else {
					$Return['error'] = $this->lang->line('xin_error_msg');
				}
				$this->output($Return);
				exit;
				}
			}
			
			public function edit_leave() 
			{
			
				if($this->input->post('edit_type')=='leave') {
					
				$id = $this->uri->segment(4);		
				/* Define return | here result is used to return user data and error for error message */
				$Return = array('result'=>'', 'error'=>'', 'csrf_hash'=>'');
				$Return['csrf_hash'] = $this->security->get_csrf_hash();
				
				$remarks = $this->input->post('remarks');
				$qt_remarks = htmlspecialchars(addslashes($remarks), ENT_QUOTES);
				
				/* Server side PHP input validation */		
				if($this->input->post('reason')==='') {
					$Return['error'] = $this->lang->line('xin_error_leave_type_reason');
				}
								
				if($Return['error']!=''){
		       		$this->output($Return);
		    	}
					
				$data = array(
				'reason' => $this->input->post('reason'),
				'remarks' => $qt_remarks
				);
				
				$result = $this->Permission_model->update_leave_record($data,$id);
				
				if ($result == TRUE) {

					// ----------------------------------------------------------------
					// SIMPAN LOG USER
					// ----------------------------------------------------------------
						// Simpan : Edit
						$this->Core_model->add_log_activity('Pengajuan','Cuti','Edit Cuti ','Edit','Sukses');
					// ----------------------------------------------------------------

					$Return['result'] = 'Proses Cuti Berhasil Diperbaharui';
				} else {
					$Return['error'] = $this->lang->line('xin_error_msg');
				}
				$this->output($Return);
				exit;
				}
			}

			// delete leave record
			public function delete_leave() 
			{
				if($this->input->post('type')=='delete') {
					// Define return | here result is used to return user data and error for error message 
					$Return = array('result'=>'', 'error'=>'', 'csrf_hash'=>'');
					$id = $this->uri->segment(4);
					$Return['csrf_hash'] = $this->security->get_csrf_hash();
					$result = $this->Permission_model->delete_leave_record($id);
					if(isset($id)) {

						// ----------------------------------------------------------------
						// SIMPAN LOG USER
						// ----------------------------------------------------------------
							// Simpan : Log Hapus
							$this->Core_model->add_log_activity('Pengajuan','Cuti','Hapus Cuti','Hapus','Sukses');
						// ----------------------------------------------------------------

						$Return['result'] = 'Proses Cuti Berhasil Dihapus';
					} else {
						$Return['error'] = $this->lang->line('xin_error_msg');
					}
					$this->output($Return);
				}
			}
			
			public function update_leave_status() 
			{
			
				if($this->input->post('update_type')=='leave') {
					
				$id = $this->uri->segment(4);		
				/* Define return | here result is used to return user data and error for error message */
				$Return = array('result'=>'', 'error'=>'', 'csrf_hash'=>'');
				$Return['csrf_hash'] = $this->security->get_csrf_hash();
				$remarks = $this->input->post('remarks');
				$qt_remarks = htmlspecialchars(addslashes($remarks), ENT_QUOTES);
					
				$data = array(
				'status' => $this->input->post('status'),
				'remarks' => $qt_remarks
				);
				
				$result = $this->Permission_model->update_leave_record($data,$id);
				
				if ($result == TRUE) {

					// ----------------------------------------------------------------
					// SIMPAN LOG USER
					// ----------------------------------------------------------------
						// Simpan : Log Persetujuan
						$this->Core_model->add_log_activity('Pengajuan','Cuti','Status Cuti ','Persetujuan','Sukses');
					// ----------------------------------------------------------------

					$Return['result'] = $this->lang->line('xin_success_leave__status_updated');
					
				} else {
					$Return['error'] = $this->lang->line('xin_error_msg');
				}
				$this->output($Return);
				exit;
				}
			}

	// ================================================================================================================
	// 02. SAKIT
	// ================================================================================================================

		// ================================================================================================================
		// TABEL
		// ================================================================================================================	 
			public function sick() 
			{
				
				$session = $this->session->userdata('username');
				if(empty($session)){ 
					redirect('admin/');
				}
				$data['title'] 			   = $this->lang->line('left_sick').' | '.$this->Core_model->site_title();
				$data['icon']              = '<i class="fa fa-medkit"></i> ';
				$data['desc']              = '<b>INFORMASI</b> : Pengajuan untuk Sakit Karyawan';
				$data['breadcrumbs']       = '<i class="fa fa-medkit"></i> '.$this->lang->line('left_sick');

				$data['all_employees'    ] = $this->Core_model->all_employees();
				$data['get_all_companies'] = $this->Company_model->get_company();
				$data['all_sick_types']    = $this->Permission_model->all_sick_types();
				
				$role_resources_ids = $this->Core_model->user_role_resource();
				$data['path_url'] = 'sick';
				
				
				// reports to 
		 		$reports_to = get_reports_team_data($session['user_id']);
				if(in_array('0721',$role_resources_ids) || $reports_to > 0) {
					if(!empty($session)){ 
						$data['subview'] = $this->load->view("admin/permission/sick", $data, TRUE);
						$this->load->view('admin/layout/layout_main', $data); //page load
					} else {
						redirect('admin/');
					}
				} else {
					redirect('admin/dashboard');
				}
		    }
		    
		    public function sick_list() 
			{

				$data['title'] = $this->Core_model->site_title();
				$session = $this->session->userdata('username');
				if(!empty($session)){ 
					$this->load->view("admin/permission/sick", $data);
				} else {
					redirect('admin/');
				}
				// Datatables Variables
				$draw   = intval($this->input->get("draw"));
				$start  = intval($this->input->get("start"));
				$length = intval($this->input->get("length"));
				
				$data = array();
				$role_resources_ids = $this->Core_model->user_role_resource();
				$user_info = $this->Core_model->read_user_info($session['user_id']);

														
				$sick = $this->Permission_model->get_sicks();
					
				
				// reports to 
		 		$reports_to = get_reports_team_data($session['user_id']);

		 		$no = 1;
				foreach($sick->result() as $r) {
					  
					// get start date and end date
					$user = $this->Core_model->read_user_info($r->employee_id);
					if(!is_null($user)){
						$full_name = $user[0]->first_name. ' '.$user[0]->last_name;
						// department
						$designation = $this->Designation_model->read_designation_information($user[0]->designation_id);
						if(!is_null($designation)){
							$designation_name = $designation[0]->designation_name;
						} else {
							$designation_name = '<span class="badge bg-red"> ? </span>';	
						}

						
					} else {
						$full_name = '--';	
						$designation_name = '--';
					}
					 
					// get sick type
					$sick_type = $this->Permission_model->read_sick_type_information($r->sick_type_id);
					if(!is_null($sick_type)){
						$type_name = $sick_type[0]->type_name;
					} else {
						$type_name = '--';	
					}
					
					// get company
					$company = $this->Core_model->read_company_info($r->company_id);
					if(!is_null($company)){
						$comp_name = $company[0]->name;
					} else {
						$comp_name = '--';	
					}
					 
					$datetime1 = new DateTime($r->from_date);
					$datetime2 = new DateTime($r->to_date);
					$interval = $datetime1->diff($datetime2);
					if(strtotime($r->from_date) == strtotime($r->to_date)){
						$no_of_days =1;
					} else {
						$no_of_days = $interval->format('%a') + 1;
					}
					$applied_on = $this->Core_model->set_date_format($r->applied_on);
					 /*$duration = $this->Core_model->set_date_format($r->from_date).' '.$this->lang->line('dashboard_to').' '.$this->Core_model->set_date_format($r->to_date).'<br>'.$this->lang->line('xin_hris_total_days').': '.$no_of_days;*/
					
					if($r->is_half_day == 1){
						$duration = $this->Core_model->set_date_format($r->from_date).' '.$this->lang->line('dashboard_to').' '.$this->Core_model->set_date_format($r->to_date).'<br><small class="text-muted"><i class="fa fa-angle-double-right"></i> '.$this->lang->line('xin_hris_total_days').': '.$this->lang->line('xin_hr_sick_half_day').'</small>';
					} else {
						$duration = $this->Core_model->set_date_format($r->from_date).' '.$this->lang->line('dashboard_to').' '.$this->Core_model->set_date_format($r->to_date).'<br><small class="text-muted"><i class="fa fa-angle-double-right"></i> '.$this->lang->line('xin_hris_total_days').': '.$no_of_days.'</small>';
					}
					
					if($r->status==1): $status = '<span class="badge bg-orange">'.$this->lang->line('xin_pending').'</span>';
					elseif($r->status==2): $status = '<span class="badge bg-green">'.$this->lang->line('xin_approved').'</span>';
					elseif($r->status==4): $status = '<span class="badge bg-green">'.$this->lang->line('xin_role_first_level_approved').'</span>';
					else: $status = '<span class="badge bg-red">'.$this->lang->line('xin_rejected').'</span>'; endif;
					
					
					if(in_array('0723',$role_resources_ids)) { //edit
						$edit = '<span data-toggle="tooltip" data-placement="top" title="'.$this->lang->line('xin_edit').'">
									<button type="button" class="btn icon-btn btn-xs btn-default waves-effect waves-light edit-data" data-toggle="modal" data-target=".edit-modal-data" data-sick_id="'. $r->sick_id.'" >
										<span class="fa fa-pencil"></span>
									</button>
								</span>';
					} else {
						$edit = '';
					}
					if(in_array('0724',$role_resources_ids)) { // delete
						$delete = '<span data-toggle="tooltip" data-placement="top" title="'.$this->lang->line('xin_delete').'">
										<button type="button" class="btn icon-btn btn-xs btn-danger waves-effect waves-light delete" data-toggle="modal" data-target=".delete-modal" data-record-id="'. $r->sick_id . '">
											<span class="fa fa-trash"></span>
										</button>
									</span>';
					} else {
						$delete = '';
					}
					if(in_array('0725',$role_resources_ids)) { //view
						$view = '<span data-toggle="tooltip" data-placement="top" title="Persetujuan">
									<a href="'.site_url().'admin/permission/sick_details/id/'.$r->sick_id.'/">
										<button type="button" class="btn icon-btn btn-xs btn-default waves-effect waves-light">
											<span class="fa fa-gavel"></span> Persetujuan
										</button>
									</a>
								</span>';
					} else {
						$view = '';
					}
					$combhr = $view.$edit.$delete;
					$itype_name = strtoupper($type_name).' <br><small class="text-muted"><i class="fa fa-angle-double-right"></i>  '.substr($r->reason,0,80).'... </i></small>';
			
				   $data[] = array(
						
						$combhr,
						$no,
						$status,
						$applied_on,
						$duration,								
						$full_name.'<br><small class="text-muted"><i class="fa fa-angle-double-right"></i> '.$comp_name.' <i class="fa fa-angle-double-right"></i> '.$designation_name.'</small>',
						$itype_name,
						
						
				   );
				   $no++;
			  }
			  $output = array(
				   "draw" => $draw,
					// "recordsTotal" => $sick->num_rows(),
					// "recordsFiltered" => $sick->num_rows(),
					 "data" => $data
				);
			  echo json_encode($output);
			  exit();
		    }			

		// ================================================================================================================
		// DETAIL
		// ================================================================================================================	
		    
		    public function sick_details() 
			{
				$session = $this->session->userdata('username');
				if(empty($session)){ 
					redirect('admin/');
				}
				
				$data['title'] = $this->Core_model->site_title();
				$sick_id = $this->uri->segment(5);
				// sick applications
				$result = $this->Permission_model->read_sick_information($sick_id);
				if(is_null($result)){
					redirect('admin/permission/sick');
				}
				$edata = array(
					'is_notify' => 0,
				);
				$this->Permission_model->update_sick_record($edata,$sick_id);
				// get sick types
				$type = $this->Permission_model->read_sick_type_information($result[0]->sick_type_id);
				if(!is_null($type)){
					$type_name = $type[0]->type_name;
				} else {
					$type_name = '--';	
				}
				// get employee
				$user = $this->Core_model->read_user_info($result[0]->employee_id);
				if(!is_null($user)){
					$full_name = $user[0]->first_name. ' '.$user[0]->last_name;
					$u_role_id = $user[0]->user_role_id;
					$department = $this->Department_model->read_department_information($user[0]->department_id);
					if(!is_null($department)){
						$department_name = $department[0]->department_name;
					} else {
						$department_name = '--';	
					}
				} else {
					$full_name = '--';	
					$u_role_id = '--';
					$department_name = '--';
				}			 
				
				$data = array(
						'title' => 'Persetujuan Sakit | '.$this->Core_model->site_title(),
						'icon' => '<i class="fa fa-gavel"></i>',
						'type' => $type_name,
						'role_id' => $u_role_id,
						'full_name' => $full_name,
						'eemployee_id' => $result[0]->employee_id,
						'department_name' => $department_name,
						'sick_id' => $result[0]->sick_id,
						'employee_id' => $result[0]->employee_id,
						'company_id' => $result[0]->company_id,
						'sick_type_id' => $result[0]->sick_type_id,
						'from_date' => $result[0]->from_date,
						'to_date' => $result[0]->to_date,
						'applied_on' => $result[0]->applied_on,
						'reason' => $result[0]->reason,
						'remarks' => $result[0]->remarks,
						'status' => $result[0]->status,
						'sick_attachment' => $result[0]->sick_attachment,
						'is_half_day' => $result[0]->is_half_day,
						'created_at' => $result[0]->created_at,
						'all_employees' => $this->Core_model->all_employees(),
						'all_sick_types' => $this->Permission_model->all_sick_types(),
						);
				$data['breadcrumbs'] = 'Persetujuan Sakit';
				$data['path_url'] = 'sick_details';
				$role_resources_ids = $this->Core_model->user_role_resource();
				// reports to 
		 		$reports_to = get_reports_team_data($session['user_id']);
				if(in_array('0721',$role_resources_ids) || $reports_to > 0) {
					if(!empty($session)){ 
						$data['subview'] = $this->load->view("admin/permission/sick_details", $data, TRUE);
						$this->load->view('admin/layout/layout_main', $data); //page load
					} else {
						redirect('admin/');
					}
				} else {
					redirect('admin/dashboard');
				}			  
		    }

		    public function read_sick_record()
			{
				$data['title'] = $this->Core_model->site_title();
				$sick_id = $this->input->get('sick_id');
				$result = $this->Permission_model->read_sick_information($sick_id);
				
				$data = array(
						'sick_id' => $result[0]->sick_id,
						'company_id' => $result[0]->company_id,
						'employee_id' => $result[0]->employee_id,
						'sick_type_id' => $result[0]->sick_type_id,
						'from_date' => $result[0]->from_date,
						'to_date' => $result[0]->to_date,
						'applied_on' => $result[0]->applied_on,
						'reason' => $result[0]->reason,
						'remarks' => $result[0]->remarks,
						'status' => $result[0]->status,
						'created_at' => $result[0]->created_at,
						'all_employees' => $this->Core_model->all_employees(),
						'get_all_companies' => $this->Company_model->get_company(),
						'all_sick_types' => $this->Permission_model->all_sick_types(),
						);
				$session = $this->session->userdata('username');
				if(!empty($session)){ 
					$this->load->view('admin/permission/dialog_sick', $data);
				} else {
					redirect('admin/');
				}
			}
	    
	    // ================================================================================================================
		// PROSES
		// ================================================================================================================
	    
			public function add_sick() 
			{
			
				if($this->input->post('add_type')=='sick') {		
				/* Define return | here result is used to return user data and error for error message */
				$Return = array('result'=>'', 'error'=>'', 'csrf_hash'=>'');
				$Return['csrf_hash'] = $this->security->get_csrf_hash();
				
				$start_date = $this->input->post('start_date');
				$end_date = $this->input->post('end_date');
				$remarks = $this->input->post('remarks');
			
				$st_date = strtotime($start_date);
				$ed_date = strtotime($end_date);
				$qt_remarks = htmlspecialchars(addslashes($remarks), ENT_QUOTES);
				
				/* Server side PHP input validation */		
				if($this->input->post('sick_type')==='') {
		        	$Return['error'] = $this->lang->line('xin_error_sick_type_field');
				} else if($this->input->post('start_date')==='') {
		        	$Return['error'] = $this->lang->line('xin_error_start_date');
				} else if($this->input->post('end_date')==='') {
		        	$Return['error'] = $this->lang->line('xin_error_end_date');
				} else if($st_date > $ed_date) {
					$Return['error'] = $this->lang->line('xin_error_start_end_date');
				} else if($this->input->post('company_id')==='') {
					$Return['error'] = $this->lang->line('error_company_field');
				} else if($this->input->post('employee_id')==='') {
					$Return['error'] = $this->lang->line('xin_error_employee_id');
				} else if($this->input->post('reason')==='') {
					$Return['error'] = $this->lang->line('xin_error_sick_type_reason');
				}
				$datetime1 = new DateTime($this->input->post('start_date'));
				$datetime2 = new DateTime($this->input->post('end_date'));
				$interval = $datetime1->diff($datetime2);
				$no_of_days = $interval->format('%a') + 1;			
				
				if($Return['error']!=''){
		       		$Return['csrf_hash'] = $this->security->get_csrf_hash();
					$this->output($Return);
		    	}	
				
				if(is_uploaded_file($_FILES['attachment']['tmp_name'])) {
					//checking image type
					$allowed =  array('png','jpg','jpeg','pdf','gif');
					$filename = $_FILES['attachment']['name'];
					$ext = pathinfo($filename, PATHINFO_EXTENSION);
					
					if(in_array($ext,$allowed)){
						$tmp_name = $_FILES["attachment"]["tmp_name"];
						$profile = "uploads/sick/";
						$set_img = base_url()."uploads/sick/";
						$name = basename($_FILES["attachment"]["name"]);
						$newfilename = 'sick_'.round(microtime(true)).'.'.$ext;
						move_uploaded_file($tmp_name, $profile.$newfilename);
						$fname = $newfilename;			
					} else {
						$Return['error'] = $this->lang->line('xin_error_attatchment_type');
					}
				} else {
					$fname = '';
				}

				if($this->input->post('sakit_half_day')!=1){
					$sakit_half_day_opt = 0;
				} else {
					$sakit_half_day_opt = $this->input->post('sakit_half_day');
				}
				
				$data = array(
					'employee_id' => $this->input->post('employee_id'),
					'company_id' => $this->input->post('company_id'),
					'sick_type_id' => $this->input->post('sick_type'),
					'from_date' => $this->input->post('start_date'),
					'to_date' => $this->input->post('end_date'),
					'applied_on' => date('Y-m-d h:i'),
					'reason' => $this->input->post('reason'),
					'is_half_day' => $sakit_half_day_opt,
					'remarks' => $qt_remarks,
					'sick_attachment' => $fname,
					'status' => '1',
					'is_notify' => '1',		
					'created_at' => date('Y-m-d h:i')
				);
				$result = $this->Permission_model->add_sick_record($data);
				
				if ($result == TRUE) {
					
					// ----------------------------------------------------------------
					// SIMPAN LOG USER
					// ----------------------------------------------------------------
						// Simpan : Log Tambah
						$this->Core_model->add_log_activity('Pengajuan','Sakit','Tambah Sakit Baru','Tambah','Sukses');
					// ----------------------------------------------------------------

					$Return['result'] = $this->lang->line('xin_success_sick_added');

				} else {
					$Return['error'] = $this->lang->line('xin_error_msg');
				}
				$this->output($Return);
				exit;
				}
			}

			public function edit_sick() 
			{
			
				if($this->input->post('edit_type')=='sick') {
					
				$id = $this->uri->segment(4);		
				/* Define return | here result is used to return user data and error for error message */
				$Return = array('result'=>'', 'error'=>'', 'csrf_hash'=>'');
				$Return['csrf_hash'] = $this->security->get_csrf_hash();
				
				$remarks = $this->input->post('remarks');
				$qt_remarks = htmlspecialchars(addslashes($remarks), ENT_QUOTES);
				
				/* Server side PHP input validation */		
				if($this->input->post('reason')==='') {
					$Return['error'] = $this->lang->line('xin_error_sick_type_reason');
				}
								
				if($Return['error']!=''){
		       		$this->output($Return);
		    	}
					
				$data = array(
					'reason' => $this->input->post('reason'),
					'remarks' => $qt_remarks
				);
				
				$result = $this->Permission_model->update_sick_record($data,$id);
				
				if ($result == TRUE) {

					// ----------------------------------------------------------------
					// SIMPAN LOG USER
					// ----------------------------------------------------------------
						// Simpan : Log Edit
						$this->Core_model->add_log_activity('Pengajuan','Sakit','Edit Sakit','Edit','Sukses');
					// ----------------------------------------------------------------

					$Return['result'] = $this->lang->line('xin_success_sick_updated');
				} else {
					$Return['error'] = $this->lang->line('xin_error_msg');
				}
				$this->output($Return);
				exit;
				}
			}

			// delete sick record
			public function delete_sick() 
			{
				if($this->input->post('type')=='delete') {
					// Define return | here result is used to return user data and error for error message 
					$Return = array('result'=>'', 'error'=>'', 'csrf_hash'=>'');
					$id = $this->uri->segment(4);
					$Return['csrf_hash'] = $this->security->get_csrf_hash();
					$result = $this->Permission_model->delete_sick_record($id);
					if(isset($id)) {

						// ----------------------------------------------------------------
						// SIMPAN LOG USER
						// ----------------------------------------------------------------
							// Simpan : Log Hapus
							$this->Core_model->add_log_activity('Pengajuan','Sakit','Hapus Sakit','Hapus','Sukses');
						// ----------------------------------------------------------------

						$Return['result'] = $this->lang->line('xin_success_sick_deleted');
					} else {
						$Return['error'] = $this->lang->line('xin_error_msg');
					}
					$this->output($Return);
				}
			}
			
			public function update_sick_status() 
			{
			
				if($this->input->post('update_type')=='sick') {
					
				$id = $this->uri->segment(4);		
				/* Define return | here result is used to return user data and error for error message */
				$Return = array('result'=>'', 'error'=>'', 'csrf_hash'=>'');
				$Return['csrf_hash'] = $this->security->get_csrf_hash();
				$remarks = $this->input->post('remarks');
				$qt_remarks = htmlspecialchars(addslashes($remarks), ENT_QUOTES);
					
				$data = array(
				'status' => $this->input->post('status'),
				'remarks' => $qt_remarks
				);
				
				$result = $this->Permission_model->update_sick_record($data,$id);
				
				if ($result == TRUE) {

					// ----------------------------------------------------------------
					// SIMPAN LOG USER
					// ----------------------------------------------------------------
						// Simpan : Log Persetujuan
						$this->Core_model->add_log_activity('Pengajuan','Sakit','Persetujuan Sakit','Persetujuan','Sukses');
					// ----------------------------------------------------------------

					$Return['result'] = $this->lang->line('xin_success_sick__status_updated');
					
				} else {
					$Return['error'] = $this->lang->line('xin_error_msg');
				}
				
				$this->output($Return);
				
				exit;
				}
			}	
	
	// ================================================================================================================
	// 03. IZIN
	// ================================================================================================================	

		// ============================================================================================================
		// TABEL
		// ============================================================================================================
			
			public function izin() 
			{
				
				$session = $this->session->userdata('username');
				if(empty($session)){ 
					redirect('admin/');
				}
				$data['title']             = $this->lang->line('left_izin').' | '.$this->Core_model->site_title();
				$data['icon']              = '<i class="fa fa-info"></i> ';
				$data['desc']              = '<b>INFORMASI</b> : Pengajuan untuk Izin Karyawan';
				$data['breadcrumbs']       = '<i class="fa fa-info"></i> '.$this->lang->line('left_izin');

				$data['all_employees']     = $this->Core_model->all_employees();
				$data['get_all_companies'] = $this->Company_model->get_company();
				$data['all_izin_types']    = $this->Permission_model->all_izin_types();
						
				$data['path_url']          = 'izin';
				
				$role_resources_ids        = $this->Core_model->user_role_resource();
				// reports to 
		 		$reports_to = get_reports_team_data($session['user_id']);
				if(in_array('0731',$role_resources_ids) || $reports_to > 0) {
					if(!empty($session)){ 
						$data['subview'] = $this->load->view("admin/permission/izin", $data, TRUE);
						$this->load->view('admin/layout/layout_main', $data); //page load
					} else {
						redirect('admin/');
					}
				} else {
					redirect('admin/dashboard');
				}
		    }
	 		 	
		    public function izin_list() 
			{

				$data['title'] = $this->Core_model->site_title();
				$session = $this->session->userdata('username');
				if(!empty($session)){ 
					$this->load->view("admin/permission/izin", $data);
				} else {
					redirect('admin/');
				}
				// Datatables Variables
				$draw = intval($this->input->get("draw"));
				$start = intval($this->input->get("start"));
				$length = intval($this->input->get("length"));
				
				$data = array();
				$role_resources_ids = $this->Core_model->user_role_resource();
				$user_info = $this->Core_model->read_user_info($session['user_id']);				
					
				$izin = $this->Permission_model->get_izins();					
				
				// reports to 
		 		$reports_to = get_reports_team_data($session['user_id']);
		 		$no = 1;
				foreach($izin->result() as $r) {
					  
					// get start date and end date
					$user = $this->Core_model->read_user_info($r->employee_id);
					if(!is_null($user)){
						$full_name = $user[0]->first_name. ' '.$user[0]->last_name;
						// department
						$designation = $this->Designation_model->read_designation_information($user[0]->designation_id);
						if(!is_null($designation)){
							$designation_name = $designation[0]->designation_name;
						} else {
							$designation_name = '<span class="badge bg-red"> ? </span>';	
						}

						
					} else {
						$full_name = '--';	
						$designation_name = '--';
					}
					 
					// get izin type
					$izin_type = $this->Permission_model->read_izin_type_information($r->izin_type_id);
					if(!is_null($izin_type)){
						$type_name = $izin_type[0]->type_name;
					} else {
						$type_name = '--';	
					}
					
					// get company
					$company = $this->Core_model->read_company_info($r->company_id);
					if(!is_null($company)){
						$comp_name = $company[0]->name;
					} else {
						$comp_name = '--';	
					}
					 
					$datetime1 = new DateTime($r->from_date);
					$datetime2 = new DateTime($r->to_date);
					$interval = $datetime1->diff($datetime2);
					if(strtotime($r->from_date) == strtotime($r->to_date)){
						$no_of_days =1;
					} else {
						$no_of_days = $interval->format('%a') + 1;
					}
					$applied_on = $this->Core_model->set_date_format($r->applied_on);
					 /*$duration = $this->Core_model->set_date_format($r->from_date).' '.$this->lang->line('dashboard_to').' '.$this->Core_model->set_date_format($r->to_date).'<br>'.$this->lang->line('xin_hris_total_days').': '.$no_of_days;*/
					
					if($r->is_half_day == 1){
						$duration = $this->Core_model->set_date_format($r->from_date).' '.$this->lang->line('dashboard_to').' '.$this->Core_model->set_date_format($r->to_date).'<br><small class="text-muted"><i class="fa fa-angle-double-right"></i> '.$this->lang->line('xin_hris_total_days').': '.$this->lang->line('xin_hr_izin_half_day').'</small>';
					} else {
						$duration = $this->Core_model->set_date_format($r->from_date).' '.$this->lang->line('dashboard_to').' '.$this->Core_model->set_date_format($r->to_date).'<br><small class="text-muted"><i class="fa fa-angle-double-right"></i> '.$this->lang->line('xin_hris_total_days').': '.$no_of_days.'</small>';
					}
					
					if($r->status==1): $status = '<span class="badge bg-orange">'.$this->lang->line('xin_pending').'</span>';
					elseif($r->status==2): $status = '<span class="badge bg-green">'.$this->lang->line('xin_approved').'</span>';
					elseif($r->status==4): $status = '<span class="badge bg-green">'.$this->lang->line('xin_role_first_level_approved').'</span>';
					else: $status = '<span class="badge bg-red">'.$this->lang->line('xin_rejected').'</span>'; endif;
					
					
					if(in_array('0733',$role_resources_ids)) { //edit
						$edit = '<span data-toggle="tooltip" data-placement="top" title="'.$this->lang->line('xin_edit').'">
									<button type="button" class="btn icon-btn btn-xs btn-default waves-effect waves-light edit-data" data-toggle="modal" data-target=".edit-modal-data" data-izin_id="'. $r->izin_id.'" >
										<span class="fa fa-pencil"></span>
									</button>
								</span>';
					} else {
						$edit = '';
					}
					if(in_array('0734',$role_resources_ids)) { // delete
						$delete = '<span data-toggle="tooltip" data-placement="top" title="'.$this->lang->line('xin_delete').'">
										<button type="button" class="btn icon-btn btn-xs btn-danger waves-effect waves-light delete" data-toggle="modal" data-target=".delete-modal" data-record-id="'. $r->izin_id . '">
											<span class="fa fa-trash"></span>
										</button>
									</span>';
					} else {
						$delete = '';
					}
					if(in_array('0735',$role_resources_ids)) { //view
						$view = '<span data-toggle="tooltip" data-placement="top" title="Persetujuan">
									<a href="'.site_url().'admin/permission/izin_details/id/'.$r->izin_id.'/">
										<button type="button" class="btn icon-btn btn-xs btn-default waves-effect waves-light">
											<span class="fa fa-gavel"></span> Persetujuan
										</button>
									</a>
								</span>';
					} else {
						$view = '';
					}
					$combhr = $view.$edit.$delete;
					$itype_name = strtoupper($type_name).' <br><small class="text-muted"><i class="fa fa-angle-double-right"></i>  '.substr($r->reason,0,80).'... </i></small>';
			
				   $data[] = array(					
						$combhr,
						$no,
						$status,
						$applied_on,
						$duration,								
						$full_name.'<br><small class="text-muted"><i class="fa fa-angle-double-right"></i> '.$comp_name.' <i class="fa fa-angle-double-right"></i> '.$designation_name.'</small>',
						$itype_name				
						
				   );
				   $no++;
			  }
			  $output = array(
				   "draw" => $draw,
					// "recordsTotal" => $izin->num_rows(),
					// "recordsFiltered" => $izin->num_rows(),
					 "data" => $data
				);
			  echo json_encode($output);
			  exit();
		    }

		// ============================================================================================================
		// DETAIL
		// ============================================================================================================

			public function izin_details() 
			{
				$session = $this->session->userdata('username');
				if(empty($session)){ 
					redirect('admin/');
				}
				
				$data['title'] = $this->Core_model->site_title();
				$izin_id = $this->uri->segment(5);
				// izin applications
				$result = $this->Permission_model->read_izin_information($izin_id);
				if(is_null($result)){
					redirect('admin/permission/izin');
				}
				$edata = array(
					'is_notify' => 0,
				);
				$this->Permission_model->update_izin_record($edata,$izin_id);
				// get izin types
				$type = $this->Permission_model->read_izin_type_information($result[0]->izin_type_id);
				if(!is_null($type)){
					$type_name = $type[0]->type_name;
				} else {
					$type_name = '--';	
				}
				// get employee
				$user = $this->Core_model->read_user_info($result[0]->employee_id);
				if(!is_null($user)){
					$full_name = $user[0]->first_name. ' '.$user[0]->last_name;
					$u_role_id = $user[0]->user_role_id;
					$department = $this->Department_model->read_department_information($user[0]->department_id);
					if(!is_null($department)){
						$department_name = $department[0]->department_name;
					} else {
						$department_name = '--';	
					}
				} else {
					$full_name = '--';	
					$u_role_id = '--';
					$department_name = '--';
				}			 
				
				$data = array(
						'title' => 'Persetujuan Izin | '.$this->Core_model->site_title(),
						'icon' => '<i class="fa fa-gavel"></i>',
						'type' => $type_name,
						'role_id' => $u_role_id,
						'full_name' => $full_name,
						'eemployee_id' => $result[0]->employee_id,
						'department_name' => $department_name,
						'izin_id' => $result[0]->izin_id,
						'employee_id' => $result[0]->employee_id,
						'company_id' => $result[0]->company_id,
						'izin_type_id' => $result[0]->izin_type_id,
						'from_date' => $result[0]->from_date,
						'to_date' => $result[0]->to_date,
						'applied_on' => $result[0]->applied_on,
						'reason' => $result[0]->reason,
						'remarks' => $result[0]->remarks,
						'status' => $result[0]->status,
						'izin_attachment' => $result[0]->izin_attachment,
						'is_half_day' => $result[0]->is_half_day,
						'created_at' => $result[0]->created_at,
						'all_employees' => $this->Core_model->all_employees(),
						'all_izin_types' => $this->Permission_model->all_izin_types(),
						);
				$data['breadcrumbs'] = 'Persetujuan Izin';
				$data['path_url'] = 'izin_details';
				$role_resources_ids = $this->Core_model->user_role_resource();
				// reports to 
		 		$reports_to = get_reports_team_data($session['user_id']);
				if(in_array('0731',$role_resources_ids) || $reports_to > 0) {
					if(!empty($session)){ 
						$data['subview'] = $this->load->view("admin/permission/izin_details", $data, TRUE);
						$this->load->view('admin/layout/layout_main', $data); //page load
					} else {
						redirect('admin/');
					}
				} else {
					redirect('admin/dashboard');
				}		  
		    }

		    public function read_izin_record()
			{
				$data['title'] = $this->Core_model->site_title();
				$izin_id = $this->input->get('izin_id');
				$result = $this->Permission_model->read_izin_information($izin_id);
				
				$data = array(
						'izin_id' => $result[0]->izin_id,
						'company_id' => $result[0]->company_id,
						'employee_id' => $result[0]->employee_id,
						'izin_type_id' => $result[0]->izin_type_id,
						'from_date' => $result[0]->from_date,
						'to_date' => $result[0]->to_date,
						'applied_on' => $result[0]->applied_on,
						'reason' => $result[0]->reason,
						'remarks' => $result[0]->remarks,
						'status' => $result[0]->status,
						'created_at' => $result[0]->created_at,
						'all_employees' => $this->Core_model->all_employees(),
						'get_all_companies' => $this->Company_model->get_company(),
						'all_izin_types' => $this->Permission_model->all_izin_types(),
						);
				$session = $this->session->userdata('username');
				if(!empty($session)){ 
					$this->load->view('admin/permission/dialog_izin', $data);
				} else {
					redirect('admin/');
				}
			}
		
		// ============================================================================================================
		// PROSESS
		// ============================================================================================================

		    public function add_izin() 
			{	
				if($this->input->post('add_type')=='izin') {		
				
					$Return = array('result'=>'', 'error'=>'', 'csrf_hash'=>'');
					$Return['csrf_hash'] = $this->security->get_csrf_hash();
					
					$start_date = $this->input->post('start_date');
					$end_date = $this->input->post('end_date');
					$remarks = $this->input->post('remarks');
				
					$st_date = strtotime($start_date);
					$ed_date = strtotime($end_date);
					$qt_remarks = htmlspecialchars(addslashes($remarks), ENT_QUOTES);
					
					/* Server side PHP input validation */		
					if($this->input->post('izin_type')==='') {
			        	$Return['error'] = $this->lang->line('xin_error_izin_type_field');
					} else if($this->input->post('start_date')==='') {
			        	$Return['error'] = $this->lang->line('xin_error_start_date');
					} else if($this->input->post('end_date')==='') {
			        	$Return['error'] = $this->lang->line('xin_error_end_date');
					} else if($st_date > $ed_date) {
						$Return['error'] = $this->lang->line('xin_error_start_end_date');
					} else if($this->input->post('company_id')==='') {
						$Return['error'] = $this->lang->line('error_company_field');
					} else if($this->input->post('employee_id')==='') {
						$Return['error'] = $this->lang->line('xin_error_employee_id');
					} else if($this->input->post('reason')==='') {
						$Return['error'] = $this->lang->line('xin_error_izin_type_reason');
					}
					$datetime1 = new DateTime($this->input->post('start_date'));
					$datetime2 = new DateTime($this->input->post('end_date'));
					$interval = $datetime1->diff($datetime2);
					$no_of_days = $interval->format('%a') + 1;				
				
					if($Return['error']!='')
					{
			       		$Return['csrf_hash'] = $this->security->get_csrf_hash();
						$this->output($Return);
			    	}	
					
					if(is_uploaded_file($_FILES['attachment']['tmp_name']))
					{
						//checking image type
						$allowed =  array('png','jpg','jpeg','pdf','gif');
						$filename = $_FILES['attachment']['name'];
						$ext = pathinfo($filename, PATHINFO_EXTENSION);
						
						if(in_array($ext,$allowed))
						{
							$tmp_name = $_FILES["attachment"]["tmp_name"];
							$profile = "uploads/izin/";
							$set_img = base_url()."uploads/izin/";
							$name = basename($_FILES["attachment"]["name"]);
							$newfilename = 'izin_'.round(microtime(true)).'.'.$ext;
							move_uploaded_file($tmp_name, $profile.$newfilename);
							$fname = $newfilename;			
						} 
						else 
						{
							$Return['error'] = $this->lang->line('xin_error_attatchment_type');
						}

					} 
					else 
					{
						$fname = '';
					}
				

				if($this->input->post('izin_half_day')!=1){
					$izin_half_day_opt = 0;
					$jum_day = 0;
				} else {
					$izin_half_day_opt = $this->input->post('izin_half_day');
					$jum_day = 0.5;
				}

				$data = array(
					'employee_id' => $this->input->post('employee_id'),
					'company_id' => $this->input->post('company_id'),
					'izin_type_id' => $this->input->post('izin_type'),
					'from_date' => $this->input->post('start_date'),
					'is_half_day' => $izin_half_day_opt,
					'day' => $jum_day,
					'to_date' => $this->input->post('end_date'),
					'applied_on' => date('Y-m-d h:i'),
					'reason' => $this->input->post('reason'),
					'remarks' => $qt_remarks,
					'izin_attachment' => $fname,
					'status' => '1',
					'is_notify' => '1',		
					'created_at' => date('Y-m-d h:i')
				);
				$result = $this->Permission_model->add_izin_record($data);
				
				if ($result == TRUE) {

					// ----------------------------------------------------------------
					// SIMPAN LOG USER
					// ----------------------------------------------------------------
						// Simpan : Log Tambah
						$this->Core_model->add_log_activity('Pengajuan','Izin','Tambah Izin Baru','Tambah','Sukses');
					// ----------------------------------------------------------------
					
					$Return['result'] = $this->lang->line('xin_success_izin_added');
					
				} else {
					$Return['error'] = $this->lang->line('xin_error_msg');
				}
				$this->output($Return);
				exit;
				}
			}

			public function edit_izin() 
			{
			
				if($this->input->post('edit_type')=='izin') {
					
				$id = $this->uri->segment(4);		
				/* Define return | here result is used to return user data and error for error message */
				$Return = array('result'=>'', 'error'=>'', 'csrf_hash'=>'');
				$Return['csrf_hash'] = $this->security->get_csrf_hash();
				
				$remarks = $this->input->post('remarks');
				$qt_remarks = htmlspecialchars(addslashes($remarks), ENT_QUOTES);
				
				/* Server side PHP input validation */		
				if($this->input->post('reason')==='') {
					$Return['error'] = $this->lang->line('xin_error_izin_type_reason');
				}
								
				if($Return['error']!=''){
		       		$this->output($Return);
		    	}
					
				$data = array(
				'reason' => $this->input->post('reason'),
				'remarks' => $qt_remarks
				);
				
				$result = $this->Permission_model->update_izin_record($data,$id);
				
				if ($result == TRUE) {

					// ----------------------------------------------------------------
					// SIMPAN LOG USER
					// ----------------------------------------------------------------
						// Simpan : Log Edit
						$this->Core_model->add_log_activity('Pengajuan','Izin','Edit Izin','Edit','Sukses');
					// ----------------------------------------------------------------

					$Return['result'] = $this->lang->line('xin_success_izin_updated');
				} else {
					$Return['error'] = $this->lang->line('xin_error_msg');
				}
				$this->output($Return);
				exit;
				}
			}

			// delete izin record
			public function delete_izin() 
			{
				if($this->input->post('type')=='delete') {
					// Define return | here result is used to return user data and error for error message 
					$Return = array('result'=>'', 'error'=>'', 'csrf_hash'=>'');
					$id = $this->uri->segment(4);
					$Return['csrf_hash'] = $this->security->get_csrf_hash();
					$result = $this->Permission_model->delete_izin_record($id);
					if(isset($id)) {
						// ----------------------------------------------------------------
						// SIMPAN LOG USER
						// ----------------------------------------------------------------
							// Simpan : Log Hapus
							$this->Core_model->add_log_activity('Pengajuan','Izin','Hapus Izin','Hapus','Sukses');
						// ----------------------------------------------------------------

						$Return['result'] = $this->lang->line('xin_success_izin_deleted');
					} else {
						$Return['error'] = $this->lang->line('xin_error_msg');
					}
					$this->output($Return);
				}
			}

			public function update_izin_status() 
			{
			
				if($this->input->post('update_type')=='izin') {
					
				$id = $this->uri->segment(4);		
				/* Define return | here result is used to return user data and error for error message */
				$Return = array('result'=>'', 'error'=>'', 'csrf_hash'=>'');
				$Return['csrf_hash'] = $this->security->get_csrf_hash();
				$remarks = $this->input->post('remarks');
				$qt_remarks = htmlspecialchars(addslashes($remarks), ENT_QUOTES);
					
				$data = array(
				'status' => $this->input->post('status'),
				'remarks' => $qt_remarks
				);
				
				$result = $this->Permission_model->update_izin_record($data,$id);
				
				if ($result == TRUE) {
					
					// ----------------------------------------------------------------
					// SIMPAN LOG USER
					// ----------------------------------------------------------------
						// $// Simpan : Log Persetujuan
						$this->Core_model->add_log_activity('Pengajuan','Izin','Persetujuan Izin','Persetujuan','Sukses');
					// ----------------------------------------------------------------

					$Return['result'] = $this->lang->line('xin_success_izin__status_updated');
					

				} else {
					$Return['error'] = $this->lang->line('xin_error_msg');
				}
				
				$this->output($Return);
				
				exit;
				}
			}
	
	// ================================================================================================================
	// 04. RESIGN
	// ================================================================================================================	 

		// ============================================================================================================
		// TABEL
		// ============================================================================================================	 
			
			public function resign()
		    {
				$session = $this->session->userdata('username');
				if(empty($session)){ 
					redirect('admin/');
				}

				$data['title'] 				= 'Resign | '.$this->Core_model->site_title();		
				$data['icon']       		= '<i class="fa fa-sign-out"></i>';
				$data['desc']              = '<b>INFORMASI</b> : Pengajuan untuk Resign Karyawan';
				$data['breadcrumbs']        = '<i class="fa fa-sign-out"></i> Resign';
				$data['path_url']           = 'resignation';
				
				$data['all_employees']      = $this->Core_model->all_employees();
				$data['get_all_companies']  = $this->Company_model->get_company();		
				
				$role_resources_ids = $this->Core_model->user_role_resource();
				if(in_array('0741',$role_resources_ids)) {
					if(!empty($session)){ 
						$data['subview'] = $this->load->view("admin/permission/resign", $data, TRUE);
						$this->load->view('admin/layout/layout_main', $data); //page load
					} else {
						redirect('admin/');
					}
				} else {
					redirect('admin/dashboard');
				}
		     }
		 
		    public function resign_list()
		    {

				$data['title'] = $this->Core_model->site_title();
				$session = $this->session->userdata('username');
				if(!empty($session)){ 
					$this->load->view("admin/permission/resign", $data);
				} else {
					redirect('admin/');
				}
				// Datatables Variables
				$draw = intval($this->input->get("draw"));
				$start = intval($this->input->get("start"));
				$length = intval($this->input->get("length"));
				
				$role_resources_ids = $this->Core_model->user_role_resource();
				$user_info = $this->Core_model->read_user_info($session['user_id']);
				
				if($user_info[0]->user_role_id==1){
					$resignation = $this->Resignation_model->get_resignations();
				
				} else {
					if(in_array('0741',$role_resources_ids)) {
						$resignation = $this->Resignation_model->get_company_resignations($user_info[0]->company_id);
					} else {
						$resignation = $this->Resignation_model->get_employee_resignation($session['user_id']);
					}
				}
				
				$role_resources_ids = $this->Core_model->user_role_resource();
				$data = array();

				$no = 1;
		        foreach($resignation->result() as $r) {
					 			  
				// get user > added by
				$user = $this->Core_model->read_user_info($r->added_by);
				// user full name
				if(!is_null($user)){
					$full_name = $user[0]->first_name.' '.$user[0]->last_name;
				} else {
					$full_name = '--';	
				}
				
				// get user > employee_
				$employee = $this->Core_model->read_user_info($r->employee_id);
				// employee full name
				if(!is_null($employee)){
					$employee_name = $employee[0]->first_name.' '.$employee[0]->last_name;
					$employee_nip = $employee[0]->employee_id;
				} else {
					$employee_name = '--';	
					$employee_nip  = '--';
				}
				if ($employee[0]->is_active == 0){
					$info_status = 'Sudah Keluar';
				} else {
					$info_status = 'Dalam Proses Keluar (belum Exit Clearence)';
				}
				// get notice date
				$notice_date = $this->Core_model->set_date_format($r->notice_date);
				// get resignation date
				$resignation_date = $this->Core_model->set_date_format($r->resignation_date);
				// get company
				$department = $this->Core_model->get_employee_view($r->employee_id);
				if(!is_null($department)){
					$department_name = $department[0]->department_name;
				} else {
					$department_name = '--';	
				}

				// get company
				$designation = $this->Core_model->get_employee_view($r->employee_id);
				if(!is_null($designation)){
					$designation_name = $designation[0]->designation_name;
				} else {
					$designation_name = '--';	
				}
				
				if(in_array('0743',$role_resources_ids)) { //edit
					$edit = '<span data-toggle="tooltip" data-placement="top" title="Persetujuan">
								<button type="button" class="btn icon-btn btn-xs btn-default waves-effect waves-light"  data-toggle="modal" data-target=".edit-modal-data"  data-resignation_id="'. $r->resignation_id . '">
										<span class="fa fa-gavel"></span> Persetujuan
								</button>
							</span>';
				} else {
					$edit = '';
				}
				if(in_array('0744',$role_resources_ids)) { // delete
					$delete = '<span data-toggle="tooltip" data-placement="top" title="'.$this->lang->line('xin_delete').'">
									<button type="button" class="btn icon-btn btn-xs btn-danger waves-effect waves-light delete" data-toggle="modal" data-target=".delete-modal" data-record-id="'. $r->resignation_id . '">
										<span class="fa fa-trash"></span>
									</button>
								</span>';
				} else {
					$delete = '';
				}
				if(in_array('0745',$role_resources_ids)) { //view
					$view = '<span data-toggle="tooltip" data-placement="top" title="'.$this->lang->line('xin_view').'">
									<button type="button" class="btn icon-btn btn-xs btn-default waves-effect waves-light" data-toggle="modal" data-target=".view-modal-data" data-resignation_id="'. $r->resignation_id . '">
										<span class="fa fa-eye"></span>
									</button>
							</span>';
				} else {
					$view = '';
				}
				$combhr = $edit.$view.$delete;				

				if($r->status==1): $status = '<span class="badge bg-orange">'.$this->lang->line('xin_pending').'</span>';
				elseif($r->status==2): $status = '<span class="badge bg-green">'.$this->lang->line('xin_approved').'</span>';
				elseif($r->status==4): $status = '<span class="badge bg-green">'.$this->lang->line('xin_role_first_level_approved').'</span>';
				else: $status = '<span class="badge bg-red">'.$this->lang->line('xin_rejected').'</span>'; endif;

				$iemployee_name = $employee_name;
				
				$data[] = array(
					$combhr,
					$no,
					$status,		
					$notice_date,
					$resignation_date,
					$employee_nip,
					$iemployee_name.'<br><small class="text-muted"><i class="fa fa-angle-double-right"></i> '.$designation_name.' ('.$department_name.')</small>',
					$r->reason.'<br><small class="text-muted"><i class="fa fa-angle-double-right"></i> '.$info_status.'</small>'					
				);
				$no++;
		      }

			  $output = array(
				   "draw" => $draw,
					 "recordsTotal" => $resignation->num_rows(),
					 "recordsFiltered" => $resignation->num_rows(),
					 "data" => $data
				);
			  echo json_encode($output);
			  exit();
		     }
		
		// =============================================================================================================
		// PROSES
		// =============================================================================================================
			
			public function read_resign()
			{
				$session = $this->session->userdata('username');
				if(empty($session)){ 
					redirect('admin/');
				}
				$data['title'] = $this->Core_model->site_title();
				$id = $this->input->get('resignation_id');
				$result = $this->Resignation_model->read_resignation_information($id);
				$data = array(
						'resignation_id' => $result[0]->resignation_id,
						'company_id' => $result[0]->company_id,
						'employee_id' => $result[0]->employee_id,
						'approval_status' => $result[0]->status,
						'notice_date' => $result[0]->notice_date,
						'resignation_date' => $result[0]->resignation_date,
						'reason' => $result[0]->reason,
						'all_employees' => $this->Core_model->all_employees(),
						'get_all_companies' => $this->Company_model->get_company()
				);
				if(!empty($session)){ 
					$this->load->view('admin/permission/dialog_resignation', $data);
				} else {
					redirect('admin/');
				}
			}

			public function view_resign()
			{
				$session = $this->session->userdata('username');
				if(empty($session)){ 
					redirect('admin/');
				}
				$data['title'] = $this->Core_model->site_title();
				$id = $this->input->get('resignation_id');
				$result = $this->Resignation_model->read_resignation_information($id);
				$data = array(
						'resignation_id' => $result[0]->resignation_id,
						'company_id' => $result[0]->company_id,
						'employee_id' => $result[0]->employee_id,
						'approval_status' => $result[0]->status,
						'notice_date' => $result[0]->notice_date,
						'resignation_date' => $result[0]->resignation_date,
						'reason' => $result[0]->reason,
						'all_employees' => $this->Core_model->all_employees(),
						'get_all_companies' => $this->Company_model->get_company()
				);
				if(!empty($session)){ 
					$this->load->view('admin/permission/dialog_resignation', $data);
				} else {
					redirect('admin/');
				}
			}
			
			public function add_resignation() 
			{
			
				if($this->input->post('add_type')=='resignation') {		
				
				/* Define return | here result is used to return user data and error for error message */
				$Return = array('result'=>'', 'error'=>'', 'csrf_hash'=>'');
				$Return['csrf_hash'] = $this->security->get_csrf_hash();
					
				/* Server side PHP input validation */
				$reason    = $this->input->post('reason');
				$qt_reason = htmlspecialchars(addslashes($reason), ENT_QUOTES);
				
				if($this->input->post('company_id')==='') {
					$Return['error'] = $this->lang->line('error_company_field');
				} else if($this->input->post('employee_id')==='') {
		       		 $Return['error'] = $this->lang->line('xin_error_employee_id');
				} else if($this->input->post('notice_date')==='') {
					$Return['error'] = $this->lang->line('xin_error_resignation_notice_date');
				} else if($this->input->post('resignation_date')==='') {
					 $Return['error'] = $this->lang->line('xin_error_resignation_date');
				}
						
				if($Return['error']!=''){
		       		$this->output($Return);
		    	}
			
				$data = array(
					'employee_id' => $this->input->post('employee_id'),
					'company_id' => $this->input->post('company_id'),
					'notice_date' => $this->input->post('notice_date'),
					'resignation_date' => $this->input->post('resignation_date'),
					'reason' => $qt_reason,
					'added_by' => $this->input->post('user_id'),
					'created_at' => date('d-m-Y'),				
				);
				$result = $this->Resignation_model->add($data);
				if ($result == TRUE) {
					// ----------------------------------------------------------------
					// SIMPAN LOG USER
					// ----------------------------------------------------------------
						// Simpan : Log Tambah
						$this->Core_model->add_log_activity('Pengajuan','Resign','Tambah Resign Baru','Tambah','Sukses');
					// ----------------------------------------------------------------
					$Return['result'] = $this->lang->line('xin_success_resignation_added');			
				} else {
					$Return['error'] = $this->lang->line('xin_error_msg');
				}
				$this->output($Return);
				exit;
				}
			}
			
			// Validate and update info in database
			public function update_resign() 
			{
			
				if($this->input->post('edit_type')=='resignation') {
					
				$id = $this->uri->segment(4);
				
				/* Define return | here result is used to return user data and error for error message */
				$Return = array('result'=>'', 'error'=>'', 'csrf_hash'=>'');
				$Return['csrf_hash'] = $this->security->get_csrf_hash();
					
				/* Server side PHP input validation */
				$reason = $this->input->post('reason');
				$qt_reason = htmlspecialchars(addslashes($reason), ENT_QUOTES);
				
				if($this->input->post('notice_date')==='') {
					$Return['error'] = $this->lang->line('xin_error_resignation_notice_date');
				} else if($this->input->post('resignation_date')==='') {
					 $Return['error'] = $this->lang->line('xin_error_resignation_date');
				} else if($this->input->post('status')==='') {
					 $Return['error'] = $this->lang->line('xin_error_template_status');
				}
						
				if($Return['error']!=''){
		       		$this->output($Return);
		    	}
			
				$data = array(
					'notice_date' => $this->input->post('notice_date'),
					'resignation_date' => $this->input->post('resignation_date'),
					'status' => $this->input->post('status'),
					'reason' => $qt_reason,
				);
				
				$result = $this->Resignation_model->update_record($data,$id);		
				
				if ($result == TRUE) {
					// ----------------------------------------------------------------
					// SIMPAN LOG USER
					// ----------------------------------------------------------------
						// Simpan : Log Edit
						$this->Core_model->add_log_activity('Pengajuan','Resign','Edit Resign','Edit','Sukses');
					// ----------------------------------------------------------------
					$Return['result'] = $this->lang->line('xin_success_resignation_updated');
				} else {
					$Return['error'] = $this->lang->line('xin_error_msg');
				}
				$this->output($Return);
				exit;
				}
			}
			
			public function delete_resign() 
			{
				$session = $this->session->userdata('username');
				if(empty($session)){ 
					redirect('admin/');
				}
				/* Define return | here result is used to return user data and error for error message */
				$Return              = array('result'=>'', 'error'=>'', 'csrf_hash'=>'');
				$id                  = $this->uri->segment(4);
				
				$Return['csrf_hash'] = $this->security->get_csrf_hash();
				
				$result              = $this->Resignation_model->delete_record($id);
				
				if(isset($id)) {
					// ----------------------------------------------------------------
					// SIMPAN LOG USER
					// ----------------------------------------------------------------
						// Simpan : Log Hapus
						$this->Core_model->add_log_activity('Pengajuan','Resign','Hapus Resign','Hapus','Sukses');
					// ----------------------------------------------------------------
					$Return['result'] = $this->lang->line('xin_success_resignation_deleted');
				} else {
					$Return['error'] = $this->lang->line('xin_error_msg');
				}
				$this->output($Return);
			}
	

	// ================================================================================================================
	// 05. LIBUR
	// ================================================================================================================	 
		
		// ================================================================================================================
		// TABEL
		// ================================================================================================================	 
			public function libur() 
			{
				
				$session = $this->session->userdata('username');
				if(empty($session)){ 
					redirect('admin/');
				}
				$data['title']             = $this->lang->line('left_libur').' | '.$this->Core_model->site_title();
				$data['icon']              = '<i class="fa fa-info"></i> ';
				$data['desc']              = '<b>INFORMASI</b> : Pengajuan untuk Libur Karyawan';
				$data['breadcrumbs']       = '<i class="fa fa-info"></i> '.$this->lang->line('left_libur');

				$data['all_employees']     = $this->Core_model->all_employees();
				$data['get_all_companies'] = $this->Company_model->get_company();
				$data['all_libur_types']    = $this->Permission_model->all_libur_types();
						
				$data['path_url']          = 'libur';
				
				$role_resources_ids        = $this->Core_model->user_role_resource();
				// reports to 
		 		$reports_to = get_reports_team_data($session['user_id']);
				if(in_array('0751',$role_resources_ids) || $reports_to > 0) {
					if(!empty($session)){ 
						$data['subview'] = $this->load->view("admin/permission/libur", $data, TRUE);
						$this->load->view('admin/layout/layout_main', $data); //page load
					} else {
						redirect('admin/');
					}
				} else {
					redirect('admin/dashboard');
				}
		    }
	 		 	
		    public function libur_list() 
			{

				$data['title'] = $this->Core_model->site_title();
				$session = $this->session->userdata('username');
				if(!empty($session)){ 
					$this->load->view("admin/permission/libur", $data);
				} else {
					redirect('admin/');
				}
				// Datatables Variables
				$draw = intval($this->input->get("draw"));
				$start = intval($this->input->get("start"));
				$length = intval($this->input->get("length"));
				
				$data = array();
				$role_resources_ids = $this->Core_model->user_role_resource();
				$user_info = $this->Core_model->read_user_info($session['user_id']);				
					
				$libur = $this->Permission_model->get_liburs();					
				
				// reports to 
		 		$reports_to = get_reports_team_data($session['user_id']);
		 		$no = 1;
				foreach($libur->result() as $r) {
					  
					// get start date and end date
					$user = $this->Core_model->read_user_info($r->employee_id);
					if(!is_null($user)){
						$full_name = $user[0]->first_name. ' '.$user[0]->last_name;
						// department
						$designation = $this->Designation_model->read_designation_information($user[0]->designation_id);
						if(!is_null($designation)){
							$designation_name = $designation[0]->designation_name;
						} else {
							$designation_name = '<span class="badge bg-red"> ? </span>';	
						}

						
					} else {
						$full_name = '--';	
						$designation_name = '--';
					}
					 
					// get libur type
					$libur_type = $this->Permission_model->read_libur_type_information($r->libur_type_id);
					if(!is_null($libur_type)){
						$type_name = $libur_type[0]->type_name;
					} else {
						$type_name = '--';	
					}
					
					// get company
					$company = $this->Core_model->read_company_info($r->company_id);
					if(!is_null($company)){
						$comp_name = $company[0]->name;
					} else {
						$comp_name = '--';	
					}
					 
					$datetime1 = new DateTime($r->from_date);
					$datetime2 = new DateTime($r->to_date);
					$interval = $datetime1->diff($datetime2);
					if(strtotime($r->from_date) == strtotime($r->to_date)){
						$no_of_days =1;
					} else {
						$no_of_days = $interval->format('%a') + 1;
					}
					$applied_on = $this->Core_model->set_date_format($r->applied_on);
					 /*$duration = $this->Core_model->set_date_format($r->from_date).' '.$this->lang->line('dashboard_to').' '.$this->Core_model->set_date_format($r->to_date).'<br>'.$this->lang->line('xin_hris_total_days').': '.$no_of_days;*/
					
					if($r->is_half_day == 1){
						$duration = $this->Core_model->set_date_format($r->from_date).' '.$this->lang->line('dashboard_to').' '.$this->Core_model->set_date_format($r->to_date).'<br><small class="text-muted"><i class="fa fa-angle-double-right"></i> '.$this->lang->line('xin_hris_total_days').': '.$this->lang->line('xin_hr_libur_half_day').'</small>';
					} else {
						$duration = $this->Core_model->set_date_format($r->from_date).' '.$this->lang->line('dashboard_to').' '.$this->Core_model->set_date_format($r->to_date).'<br><small class="text-muted"><i class="fa fa-angle-double-right"></i> '.$this->lang->line('xin_hris_total_days').': '.$no_of_days.'</small>';
					}
					
					if($r->status==1): $status = '<span class="badge bg-orange">'.$this->lang->line('xin_pending').'</span>';
					elseif($r->status==2): $status = '<span class="badge bg-green">'.$this->lang->line('xin_approved').'</span>';
					elseif($r->status==4): $status = '<span class="badge bg-green">'.$this->lang->line('xin_role_first_level_approved').'</span>';
					else: $status = '<span class="badge bg-red">'.$this->lang->line('xin_rejected').'</span>'; endif;
					
					
					if(in_array('0753',$role_resources_ids)) { //edit
						$edit = '<span data-toggle="tooltip" data-placement="top" title="'.$this->lang->line('xin_edit').'">
									<button type="button" class="btn icon-btn btn-xs btn-default waves-effect waves-light edit-data" data-toggle="modal" data-target=".edit-modal-data" data-libur_id="'. $r->libur_id.'" >
										<span class="fa fa-pencil"></span>
									</button>
								</span>';
					} else {
						$edit = '';
					}
					if(in_array('0754',$role_resources_ids)) { // delete
						$delete = '<span data-toggle="tooltip" data-placement="top" title="'.$this->lang->line('xin_delete').'">
										<button type="button" class="btn icon-btn btn-xs btn-danger waves-effect waves-light delete" data-toggle="modal" data-target=".delete-modal" data-record-id="'. $r->libur_id . '">
											<span class="fa fa-trash"></span>
										</button>
									</span>';
					} else {
						$delete = '';
					}
					if(in_array('0755',$role_resources_ids)) { //view
						$view = '<span data-toggle="tooltip" data-placement="top" title="Persetujuan">
									<a href="'.site_url().'admin/permission/libur_details/id/'.$r->libur_id.'/">
										<button type="button" class="btn icon-btn btn-xs btn-default waves-effect waves-light">
											<span class="fa fa-gavel"></span> Persetujuan
										</button>
									</a>
								</span>';
					} else {
						$view = '';
					}
					$combhr = $view.$edit.$delete;
					$itype_name = strtoupper($type_name).' <br><small class="text-muted"><i class="fa fa-angle-double-right"></i>  '.substr($r->reason,0,80).'... </i></small>';
			
				   $data[] = array(					
						$combhr,
						$no,
						$status,
						$applied_on,
						$duration,								
						$full_name.'<br><small class="text-muted"><i class="fa fa-angle-double-right"></i> '.$comp_name.' <i class="fa fa-angle-double-right"></i> '.$designation_name.'</small>',
						$itype_name				
						
				   );
				   $no++;
			  }
			  $output = array(
				   "draw" => $draw,
					// "recordsTotal" => $libur->num_rows(),
					// "recordsFiltered" => $libur->num_rows(),
					 "data" => $data
				);
			  echo json_encode($output);
			  exit();
		    }

		// ================================================================================================================
		// DETAIL
		// ================================================================================================================	 
		
			public function libur_details() 
			{
				$session = $this->session->userdata('username');
				if(empty($session)){ 
					redirect('admin/');
				}
				
				$data['title'] = $this->Core_model->site_title();
				$libur_id = $this->uri->segment(5);
				// libur applications
				$result = $this->Permission_model->read_libur_information($libur_id);
				if(is_null($result)){
					redirect('admin/permission/libur');
				}
				$edata = array(
					'is_notify' => 0,
				);
				$this->Permission_model->update_libur_record($edata,$libur_id);
				// get libur types
				$type = $this->Permission_model->read_libur_type_information($result[0]->libur_type_id);
				if(!is_null($type)){
					$type_name = $type[0]->type_name;
				} else {
					$type_name = '--';	
				}
				// get employee
				$user = $this->Core_model->read_user_info($result[0]->employee_id);
				if(!is_null($user)){
					$full_name = $user[0]->first_name. ' '.$user[0]->last_name;
					$u_role_id = $user[0]->user_role_id;
					$department = $this->Department_model->read_department_information($user[0]->department_id);
					if(!is_null($department)){
						$department_name = $department[0]->department_name;
					} else {
						$department_name = '--';	
					}
				} else {
					$full_name = '--';	
					$u_role_id = '--';
					$department_name = '--';
				}			 
				
				$data = array(
						'title' => 'Persetujuan Libur (Kantor) | '.$this->Core_model->site_title(),
						'icon' => '<i class="fa fa-gavel"></i>',
						'type' => $type_name,
						'role_id' => $u_role_id,
						'full_name' => $full_name,
						'eemployee_id' => $result[0]->employee_id,
						'department_name' => $department_name,
						'libur_id' => $result[0]->libur_id,
						'employee_id' => $result[0]->employee_id,
						'company_id' => $result[0]->company_id,
						'libur_type_id' => $result[0]->libur_type_id,
						'from_date' => $result[0]->from_date,
						'to_date' => $result[0]->to_date,
						'applied_on' => $result[0]->applied_on,
						'reason' => $result[0]->reason,
						'remarks' => $result[0]->remarks,
						'status' => $result[0]->status,
						'libur_attachment' => $result[0]->libur_attachment,
						'is_half_day' => $result[0]->is_half_day,
						'created_at' => $result[0]->created_at,
						'all_employees' => $this->Core_model->all_employees(),
						'all_libur_types' => $this->Permission_model->all_libur_types(),
						);
				$data['breadcrumbs'] = 'Persetujuan Libur';
				$data['path_url'] = 'libur_details';
				$role_resources_ids = $this->Core_model->user_role_resource();
				// reports to 
		 		$reports_to = get_reports_team_data($session['user_id']);
				if(in_array('0751',$role_resources_ids) || $reports_to > 0) {
					if(!empty($session)){ 
						$data['subview'] = $this->load->view("admin/permission/libur_details", $data, TRUE);
						$this->load->view('admin/layout/layout_main', $data); //page load
					} else {
						redirect('admin/');
					}
				} else {
					redirect('admin/dashboard');
				}		  
		    }

		    public function read_libur_record()
			{
				$data['title'] = $this->Core_model->site_title();
				$libur_id = $this->input->get('libur_id');
				$result = $this->Permission_model->read_libur_information($libur_id);
				
				$data = array(
						'libur_id' => $result[0]->libur_id,
						'company_id' => $result[0]->company_id,
						'employee_id' => $result[0]->employee_id,
						'libur_type_id' => $result[0]->libur_type_id,
						'from_date' => $result[0]->from_date,
						'to_date' => $result[0]->to_date,
						'applied_on' => $result[0]->applied_on,
						'reason' => $result[0]->reason,
						'remarks' => $result[0]->remarks,
						'status' => $result[0]->status,
						'created_at' => $result[0]->created_at,
						'all_employees' => $this->Core_model->all_employees(),
						'get_all_companies' => $this->Company_model->get_company(),
						'all_libur_types' => $this->Permission_model->all_libur_types(),
						);
				$session = $this->session->userdata('username');
				if(!empty($session)){ 
					$this->load->view('admin/permission/dialog_libur', $data);
				} else {
					redirect('admin/');
				}
			}
		
		// ================================================================================================================
		// PROSESS
		// ================================================================================================================

		    public function add_libur() 
			{	
				if($this->input->post('add_type')=='libur') {		
				
					$Return = array('result'=>'', 'error'=>'', 'csrf_hash'=>'');
					$Return['csrf_hash'] = $this->security->get_csrf_hash();
					
					$start_date = $this->input->post('start_date');
					$end_date = $this->input->post('end_date');
					$remarks = $this->input->post('remarks');
				
					$st_date = strtotime($start_date);
					$ed_date = strtotime($end_date);
					$qt_remarks = htmlspecialchars(addslashes($remarks), ENT_QUOTES);
					
					/* Server side PHP input validation */		
					if($this->input->post('libur_type')==='') {
			        	$Return['error'] = $this->lang->line('xin_error_libur_type_field');
					} else if($this->input->post('start_date')==='') {
			        	$Return['error'] = $this->lang->line('xin_error_start_date');
					} else if($this->input->post('end_date')==='') {
			        	$Return['error'] = $this->lang->line('xin_error_end_date');
					} else if($st_date > $ed_date) {
						$Return['error'] = $this->lang->line('xin_error_start_end_date');
					} else if($this->input->post('company_id')==='') {
						$Return['error'] = $this->lang->line('error_company_field');
					} else if($this->input->post('employee_id')==='') {
						$Return['error'] = $this->lang->line('xin_error_employee_id');
					} else if($this->input->post('reason')==='') {
						$Return['error'] = $this->lang->line('xin_error_libur_type_reason');
					}
					$datetime1 = new DateTime($this->input->post('start_date'));
					$datetime2 = new DateTime($this->input->post('end_date'));
					$interval = $datetime1->diff($datetime2);
					$no_of_days = $interval->format('%a') + 1;				
				
					if($Return['error']!='')
					{
			       		$Return['csrf_hash'] = $this->security->get_csrf_hash();
						$this->output($Return);
			    	}	
					
					if(is_uploaded_file($_FILES['attachment']['tmp_name']))
					{
						//checking image type
						$allowed =  array('png','jpg','jpeg','pdf','gif');
						$filename = $_FILES['attachment']['name'];
						$ext = pathinfo($filename, PATHINFO_EXTENSION);
						
						if(in_array($ext,$allowed))
						{
							$tmp_name = $_FILES["attachment"]["tmp_name"];
							$profile = "uploads/libur/";
							$set_img = base_url()."uploads/libur/";
							$name = basename($_FILES["attachment"]["name"]);
							$newfilename = 'libur_'.round(microtime(true)).'.'.$ext;
							move_uploaded_file($tmp_name, $profile.$newfilename);
							$fname = $newfilename;			
						} 
						else 
						{
							$Return['error'] = $this->lang->line('xin_error_attatchment_type');
						}

					} 
					else 
					{
						$fname = '';
					}
				

				if($this->input->post('libur_half_day')!=1){
					$libur_half_day_opt = 0;
					
				} else {
					$libur_half_day_opt = $this->input->post('libur_half_day');
					
				}

				$data = array(
					'employee_id' => $this->input->post('employee_id'),
					'company_id' => $this->input->post('company_id'),
					'libur_type_id' => $this->input->post('libur_type'),
					'from_date' => $this->input->post('start_date'),
					'is_half_day' => $libur_half_day_opt,
					
					'to_date' => $this->input->post('end_date'),
					'applied_on' => date('Y-m-d h:i'),
					'reason' => $this->input->post('reason'),
					'remarks' => $qt_remarks,
					'libur_attachment' => $fname,
					'status' => '1',
					'is_notify' => '1',		
					'created_at' => date('Y-m-d h:i')
				);
				$result = $this->Permission_model->add_libur_record($data);
				
				if ($result == TRUE) {

					// ----------------------------------------------------------------
					// SIMPAN LOG USER
					// ----------------------------------------------------------------
						// Simpan : Log Tambah
						$this->Core_model->add_log_activity('Pengajuan','Libur','Tambah Libur Baru','Tambah','Sukses');
					// ----------------------------------------------------------------
					
					$Return['result'] = $this->lang->line('xin_success_libur_added');
					
				} else {
					$Return['error'] = $this->lang->line('xin_error_msg');
				}
				$this->output($Return);
				exit;
				}
			}

			public function edit_libur() 
			{
			
				if($this->input->post('edit_type')=='libur') {
					
				$id = $this->uri->segment(4);		
				/* Define return | here result is used to return user data and error for error message */
				$Return = array('result'=>'', 'error'=>'', 'csrf_hash'=>'');
				$Return['csrf_hash'] = $this->security->get_csrf_hash();
				
				$remarks = $this->input->post('remarks');
				$qt_remarks = htmlspecialchars(addslashes($remarks), ENT_QUOTES);
				
				/* Server side PHP input validation */		
				if($this->input->post('reason')==='') {
					$Return['error'] = $this->lang->line('xin_error_libur_type_reason');
				}
								
				if($Return['error']!=''){
		       		$this->output($Return);
		    	}
					
				$data = array(
				'reason' => $this->input->post('reason'),
				'remarks' => $qt_remarks
				);
				
				$result = $this->Permission_model->update_libur_record($data,$id);
				
				if ($result == TRUE) {

					// ----------------------------------------------------------------
					// SIMPAN LOG USER
					// ----------------------------------------------------------------
						// Simpan : Log Edit
						$this->Core_model->add_log_activity('Pengajuan','Libur','Edit Libur','Edit','Sukses');
					// ----------------------------------------------------------------

					$Return['result'] = $this->lang->line('xin_success_libur_updated');
				} else {
					$Return['error'] = $this->lang->line('xin_error_msg');
				}
				$this->output($Return);
				exit;
				}
			}
			
			public function delete_libur() 
			{
				if($this->input->post('type')=='delete') {
					// Define return | here result is used to return user data and error for error message 
					$Return = array('result'=>'', 'error'=>'', 'csrf_hash'=>'');
					$id = $this->uri->segment(4);
					$Return['csrf_hash'] = $this->security->get_csrf_hash();
					$result = $this->Permission_model->delete_libur_record($id);
					if(isset($id)) {
						// ----------------------------------------------------------------
						// SIMPAN LOG USER
						// ----------------------------------------------------------------
							// Simpan : Log Hapus
							$this->Core_model->add_log_activity('Pengajuan','Libur','Hapus Libur','Hapus','Sukses');
						// ----------------------------------------------------------------

						$Return['result'] = $this->lang->line('xin_success_libur_deleted');
					} else {
						$Return['error'] = $this->lang->line('xin_error_msg');
					}
					$this->output($Return);
				}
			}

			public function update_libur_status() 
			{
			
				if($this->input->post('update_type')=='libur') {
					
				$id = $this->uri->segment(4);		
				/* Define return | here result is used to return user data and error for error message */
				$Return = array('result'=>'', 'error'=>'', 'csrf_hash'=>'');
				$Return['csrf_hash'] = $this->security->get_csrf_hash();
				$remarks = $this->input->post('remarks');
				$qt_remarks = htmlspecialchars(addslashes($remarks), ENT_QUOTES);
					
				$data = array(
				'status' => $this->input->post('status'),
				'remarks' => $qt_remarks
				);
				
				$result = $this->Permission_model->update_libur_record($data,$id);
				
				if ($result == TRUE) {
					
					// ----------------------------------------------------------------
					// SIMPAN LOG USER
					// ----------------------------------------------------------------
						// $// Simpan : Log Persetujuan
						$this->Core_model->add_log_activity('Pengajuan','Libur','Persetujuan Libur','Persetujuan','Sukses');
					// ----------------------------------------------------------------

					$Return['result'] = $this->lang->line('xin_success_libur_status_updated');
					

				} else {
					$Return['error'] = $this->lang->line('xin_error_msg');
				}
				
				$this->output($Return);
				
				exit;
				}
			}
	// ================================================================================================================
	// GET
	// ================================================================================================================	 
	
		// get company > employees
		public function get_employees() {

			$data['title'] = $this->Core_model->site_title();
			$id = $this->uri->segment(4);
			
			$data = array(
				'company_id' => $id
				);
			$session = $this->session->userdata('username');
			if(!empty($session)){ 
				$this->load->view("admin/permission/get_employees", $data);
			} else {
				redirect('admin/');
			}
			// Datatables Variables
			$draw = intval($this->input->get("draw"));
			$start = intval($this->input->get("start"));
			$length = intval($this->input->get("length"));
		} 
		
		// get company > employees
		public function get_leave_employees() {

			$data['title'] = $this->Core_model->site_title();
			$id = $this->uri->segment(4);
			
			$data = array(
				'company_id' => $id
				);
			$session = $this->session->userdata('username');
			if(!empty($session)){ 
				$this->load->view("admin/permission/get_leave_employees", $data);
			} else {
				redirect('admin/');
			}
			// Datatables Variables
			$draw   = intval($this->input->get("draw"));
			$start  = intval($this->input->get("start"));
			$length = intval($this->input->get("length"));
		} 

		public function get_remain_employees() {

			$data['title'] = $this->Core_model->site_title();
			$id = $this->uri->segment(4);
			
			$data = array(
				'company_id' => $id
				);
			$session = $this->session->userdata('username');
			if(!empty($session)){ 
				$this->load->view("admin/permission/get_leave_employees", $data);
			} else {
				redirect('admin/');
			}
			// Datatables Variables
			$draw   = intval($this->input->get("draw"));
			$start  = intval($this->input->get("start"));
			$length = intval($this->input->get("length"));
		} 		

		// get company > employees
		public function get_sick_employees() {

			$data['title'] = $this->Core_model->site_title();
			$id = $this->uri->segment(4);
			
			$data = array(
				'company_id' => $id
				);
			$session = $this->session->userdata('username');
			if(!empty($session)){ 
				$this->load->view("admin/permission/get_sick_employees", $data);
			} else {
				redirect('admin/');
			}
			// Datatables Variables
			$draw = intval($this->input->get("draw"));
			$start = intval($this->input->get("start"));
			$length = intval($this->input->get("length"));
		} 

		// get company > employees
		public function get_izin_employees() {

			$data['title'] = $this->Core_model->site_title();
			$id = $this->uri->segment(4);
			
			$data = array(
				'company_id' => $id
				);
			$session = $this->session->userdata('username');
			if(!empty($session)){ 
				$this->load->view("admin/permission/get_izin_employees", $data);
			} else {
				redirect('admin/');
			}
			// Datatables Variables
			$draw = intval($this->input->get("draw"));
			$start = intval($this->input->get("start"));
			$length = intval($this->input->get("length"));
		} 
		
		// get company > employees
		public function get_libur_employees() {

			$data['title'] = $this->Core_model->site_title();
			$id = $this->uri->segment(4);
			
			$data = array(
				'company_id' => $id
				);
			$session = $this->session->userdata('username');
			if(!empty($session)){ 
				$this->load->view("admin/permission/get_libur_employees", $data);
			} else {
				redirect('admin/');
			}
			// Datatables Variables
			$draw = intval($this->input->get("draw"));
			$start = intval($this->input->get("start"));
			$length = intval($this->input->get("length"));
		} 
	

		// get company > employees leave
		public function get_employees_leave() {

			$data['title'] = $this->Core_model->site_title();
			$session = $this->session->userdata('username');
			if(empty($session)){ 
				redirect('admin/');
			}
			
			$leave_type_id = $this->uri->segment(4);
			$employee_id = $this->uri->segment(5);
			
			$tahun = date("Y");

			$remaining_leave = $this->Permission_model->count_total_leaves($leave_type_id,$employee_id,$tahun);
			$type            = $this->Permission_model->read_leave_type_information($leave_type_id);
			if(!is_null($type)){
				$type_name = $type[0]->type_name;
				$total = $type[0]->days_per_year;
				$leave_remaining_total = $total - $remaining_leave;
			} else {
				$type_name = '--';	
				$leave_remaining_total = 0;
			}
			ob_start();
			echo $leave_remaining_total." ".$type_name. ' ' .$this->lang->line('xin_remaining');
			ob_end_flush();
		} 

		public function get_employees_sick() {

			$data['title'] = $this->Core_model->site_title();
			$session = $this->session->userdata('username');
			if(empty($session)){ 
				redirect('admin/');
			}
			
			$sick_type_id = $this->uri->segment(4);
			$employee_id = $this->uri->segment(5);
			
			$remaining_sick = $this->Permission_model->count_total_sicks($sick_type_id,$employee_id);
			$type = $this->Permission_model->read_sick_type_information($sick_type_id);
			if(!is_null($type)){
				$type_name = $type[0]->type_name;
				$total = $type[0]->days_per_year;
				$sick_remaining_total = $total - $remaining_sick;
			} else {
				$type_name = '--';	
				$sick_remaining_total = 0;
			}
			ob_start();
			echo $sick_remaining_total." ".$type_name. ' ' .$this->lang->line('xin_remaining');
			ob_end_flush();
		}

		public function get_employees_izin() {

			$data['title'] = $this->Core_model->site_title();
			$session = $this->session->userdata('username');
			if(empty($session)){ 
				redirect('admin/');
			}
			
			$izin_type_id = $this->uri->segment(4);
			$employee_id = $this->uri->segment(5);
			
			$remaining_izin = $this->Permission_model->count_total_izins($izin_type_id,$employee_id);
			$type = $this->Permission_model->read_izin_type_information($izin_type_id);
			if(!is_null($type)){
				$type_name = $type[0]->type_name;
				$total = $type[0]->days_per_year;
				$izin_remaining_total = $total - $remaining_izin;
			} else {
				$type_name = '--';	
				$izin_remaining_total = 0;
			}
			ob_start();
			echo $izin_remaining_total." ".$type_name. ' ' .$this->lang->line('xin_remaining');
			ob_end_flush();
		} 

		// get employee assigned leave types
		public function get_employee_assigned_leave_types() {

			$data['title'] = $this->Core_model->site_title();
			$employee_id = $this->uri->segment(4);
			
			$data = array(
				'employee_id' => $employee_id
				);
			$session = $this->session->userdata('username');
			if(!empty($session)){ 
				$this->load->view("admin/permission/get_employee_assigned_leave_types", $data);
			} else {
				redirect('admin/');
			}
			// Datatables Variables
			$draw = intval($this->input->get("draw"));
			$start = intval($this->input->get("start"));
			$length = intval($this->input->get("length"));
		}

		// get employee assigned sick types
		public function get_employee_assigned_sick_types() {

			$data['title'] = $this->Core_model->site_title();
			$employee_id = $this->uri->segment(4);
			
			$data = array(
				'employee_id' => $employee_id
				);
			$session = $this->session->userdata('username');
			if(!empty($session)){ 
				$this->load->view("admin/permission/get_employee_assigned_sick_types", $data);
			} else {
				redirect('admin/');
			}
			// Datatables Variables
			$draw = intval($this->input->get("draw"));
			$start = intval($this->input->get("start"));
			$length = intval($this->input->get("length"));
		}

		// get employee assigned izin types
		public function get_employee_assigned_izin_types() {

			$data['title'] = $this->Core_model->site_title();
			$employee_id = $this->uri->segment(4);
			
			$data = array(
				'employee_id' => $employee_id
				);
			$session = $this->session->userdata('username');
			if(!empty($session)){ 
				$this->load->view("admin/permission/get_employee_assigned_izin_types", $data);
			} else {
				redirect('admin/');
			}
			// Datatables Variables
			$draw = intval($this->input->get("draw"));
			$start = intval($this->input->get("start"));
			$length = intval($this->input->get("length"));
		}

		// get company > employees
		public function get_permission_employees() {

			$data['title'] = $this->Core_model->site_title();
			$id = $this->uri->segment(4);
			
			$data = array(
				'company_id' => $id
				);
			$session = $this->session->userdata('username');
			if(!empty($session)){ 
				$this->load->view("admin/permission/get_permission_employees", $data);
			} else {
				redirect('admin/');
			}
			// Datatables Variables
			$draw = intval($this->input->get("draw"));
			$start = intval($this->input->get("start"));
			$length = intval($this->input->get("length"));
		
		}   
	
		
}
