<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Timesheet extends MY_Controller {
	
	 public function __construct() {
        parent::__construct();
		//load the model
		$this->load->model("Timesheet_model");
		$this->load->model("Employees_model");
		$this->load->model("Core_model");
		$this->load->library('email');
		$this->load->model("Department_model");
		$this->load->model("Designation_model");
		$this->load->model("Roles_model");
		
		$this->load->model("Location_model");
		$this->load->model("Company_model");
	}
	
	/*Function to set JSON output*/
	public function output($Return=array()){
		/*Set response header*/
		header("Access-Control-Allow-Origin: *");
		header("Content-Type: application/json; charset=UTF-8");
		/*Final JSON response*/
		exit(json_encode($Return));
	}
	
	// index > timesheet
	
	
	// =============================================================================
	// 0910 TARIK ABSENSI REGULER
	// =============================================================================

		// daily attendance > timesheet
		public function attendance_reguler()
	    {
			$session = $this->session->userdata('username');
			if(empty($session)){ 
				redirect('admin/');
			}
			$data['title']       = 'Tarik Absensi Reguler | '.$this->Core_model->site_title();
			$data['icon']        = '<i class="fa fa-hand-o-up"></i>';
			$data['desc']        = '<span><b>INFORMASI : </b> Proses Tarik Absensi Setiap Hari ini dilakukan setelah Proses Pengajuan di Input semua </span>';
			$data['breadcrumbs'] = 'Tarik Absensi Reguler (Per Hari / Tanggal)';
			$data['path_url']    = 'attendance_reguler';
			
			$data['get_all_companies']    = $this->Company_model->get_company();
			$data['all_office_shifts'] = $this->Location_model->all_payroll_jenis();
			
			$role_resources_ids        = $this->Core_model->user_role_resource();
			
			if(in_array('0911',$role_resources_ids)) {
				if(!empty($session)){
				$data['subview'] = $this->load->view("admin/timesheet/attendance_reguler_list", $data, TRUE);
				$this->load->view('admin/layout/layout_main', $data); //page load
				} else {
					redirect('admin/dashboard/');
				}	
			} else {
				redirect('admin/dashboard');
			}	  
	    }

	    public function attendance_reguler_list_load()
	    {

			$data['title'] = $this->Core_model->site_title();
			$session       = $this->session->userdata('username');
			$user_info     = $this->Core_model->read_user_info($session['user_id']);

			if(!empty($session)){ 
				$this->load->view("admin/timesheet/attendance_reguler_list", $data);
			} else {
				redirect('admin/');
			}

			// Datatables Variables
			$draw               = intval($this->input->get("draw"));
			$start              = intval($this->input->get("start"));
			$length             = intval($this->input->get("length"));
			$role_resources_ids = $this->Core_model->user_role_resource();

			$attendance_date    = $this->input->get("attendance_date");
			$jenis_gaji         = $this->input->get("location_id");
			$company_id         = $this->input->get("company_id");

					
			$employee = $this->Employees_model->get_attendance_jenis_gaji_employees_reguler_load($jenis_gaji,$company_id);
			
			$system   = $this->Core_model->read_setting_info(1);
			
			$data = array();

			$no = 1;
			
	        foreach($employee->result() as $r) {
					  		
					// get company
					$company = $this->Core_model->read_company_info($r->company_id);
					if(!is_null($company)){
						$comp_name = $company[0]->name;
					} else {
						$comp_name = '';
					}
					// get posisi				
					$designation = $this->Designation_model->read_designation_information($r->designation_id);

					if(!is_null($designation)){
						$designation_name = $designation[0]->designation_name;
					} else {
						$designation_name = '';	
					}	

					// user full name
					$full_name = $r->first_name.' '.$r->last_name;	
					
					// get office shift for employee
					$get_day = strtotime($attendance_date);
					$day = date('l', $get_day);		

					$office_reguler = $this->Timesheet_model->read_office_jadwal_information_reguler($r->office_shift_id);					

					if(!is_null($office_reguler))
					{
						$monday_in_time      = $office_reguler[0]->monday_in_time;
						$monday_out_time     = $office_reguler[0]->monday_out_time;
						
						$tuesday_in_time     = $office_reguler[0]->tuesday_in_time;
						$tuesday_out_time    = $office_reguler[0]->tuesday_out_time;
						
						$wednesday_in_time   = $office_reguler[0]->wednesday_in_time;
						$wednesday_out_time  = $office_reguler[0]->wednesday_out_time;
						
						$thursday_in_time    = $office_reguler[0]->thursday_in_time;
						$thursday_out_time   = $office_reguler[0]->thursday_out_time;
						
						$friday_in_time      = $office_reguler[0]->friday_in_time;
						$friday_out_time     = $office_reguler[0]->friday_out_time;
						
						$saturday_in_time    = $office_reguler[0]->saturday_in_time;
						$saturday_out_time   = $office_reguler[0]->saturday_out_time;
						
						$sunday_in_time      = $office_reguler[0]->sunday_in_time;
						$sunday_out_time     = $office_reguler[0]->sunday_out_time;					
					} 
					else 
					{
						
						$monday_in_time      = '';	
						$tuesday_in_time     = '';	
						$wednesday_in_time   = '';	
						$thursday_in_time    = '';	
						$friday_in_time      = '';	
						$saturday_in_time    = '';	
						$sunday_in_time      = '';
						$monday_out_time     = '';	
						$tuesday_out_time    = '';	
						$wednesday_out_time  = '';	
						$thursday_out_time   = '';	
						$friday_out_time     = '';	
						$saturday_out_time   = '';	
						$sunday_out_time     = '';	
					}

					// echo "<pre>";
					// print_r($this->db->last_query());
					// echo "</pre>";
					// die();
					
					// get clock in/clock out of each employee
					if($day == 'Monday') {
						if( $monday_in_time ==''){
							$in_time = '00:00:00';
							$out_time = '00:00:00';
						} else {
							$in_time =  $monday_in_time ;
							$out_time = $monday_out_time;
						}
					} else if($day == 'Tuesday') {
						if( $tuesday_in_time ==''){
							$in_time = '00:00:00';
							$out_time = '00:00:00';
						} else {
							$in_time = $tuesday_in_time;
							$out_time = $tuesday_out_time;
						}
					} else if($day == 'Wednesday') {
						if( $wednesday_in_time ==''){
							$in_time = '00:00:00';
							$out_time = '00:00:00';
						} else {
							$in_time = $wednesday_in_time;
							$out_time = $wednesday_out_time;
						}
					} else if($day == 'Thursday') {
						if( $thursday_in_time ==''){
							$in_time = '00:00:00';
							$out_time = '00:00:00';
						} else {
							$in_time = $thursday_in_time;
							$out_time = $thursday_out_time;
						}
					} else if($day == 'Friday') {
						if( $friday_in_time ==''){
							$in_time = '00:00:00';
							$out_time = '00:00:00';
						} else {
							$in_time = $friday_in_time;
							$out_time = $friday_out_time;
						}
					} else if($day == 'Saturday') {
						if( $saturday_in_time == ''){
							$in_time = '00:00:00';
							$out_time = '00:00:00';
						} else {
							$in_time = $saturday_in_time;
							$out_time = $saturday_out_time;
						}
					} else if($day == 'Sunday') {
						if( $sunday_in_time ==''){
							$in_time = '00:00:00';
							$out_time = '00:00:00';
						} else {
							$in_time = $sunday_in_time;
							$out_time = $sunday_out_time;
						}
					}

					

					// check if clock-in for date
					$attendance_status = '';


					// ==============================================================================================================
					// CEK MASUK
					// =============================================================================================================				
					
					$check = $this->Timesheet_model->attendance_first_in_check_new($r->employee_id,$attendance_date);		
					
					if ($check->num_rows() > 0){
					
						// check clock in time
						$attendance = $this->Timesheet_model->attendance_first_in_new($r->employee_id,$attendance_date);
						
						// clock in
						$clock_in = new DateTime($attendance[0]->clock_in);
						$clock_in2 = $clock_in->format('H:i:s');

											
						$office_time =  new DateTime($in_time.' '.$attendance_date);

						// HITUNG TERLAMBAT				
						$office_time_new   = strtotime($in_time.' '.$attendance_date);
						$clock_in_time_new = strtotime($attendance[0]->clock_in);
						
						if($clock_in_time_new == '') {
							$total_time_l = '0';

						} else if($clock_in_time_new <= $office_time_new) {
							$total_time_l = '0';
						
						} else if($clock_in_time_new > $office_time_new) {
							$interval_late = $clock_in->diff($office_time);
							$hours_l   = $interval_late->format('%h');
							$minutes_l = $interval_late->format('%i');		
							$total_time_l = $hours_l*60+$minutes_l;
						} else {
							$total_time_l = '0';
						}
						
						// total hours work/ed
						$total_hrs        = $this->Timesheet_model->total_hours_worked_attendance($r->user_id,$attendance_date);
						$hrs_old_int1     = '';
						$Total            = '';
						$Trest            = '';
						$total_time_rs    = '';
						$hrs_old_int_res1 = '';
						foreach ($total_hrs->result() as $hour_work){
							// total work			
							$timee = $hour_work->total_work.':00';
							$str_time =$timee;
				
							$str_time = preg_replace("/^([\d]{1,2})\:([\d]{2})$/", "00:$1:$2", $str_time);
							
							sscanf($str_time, "%d:%d:%d", $hours, $minutes, $seconds);
							
							$hrs_old_seconds = $hours * 3600 + $minutes * 60 + $seconds;
							
							$hrs_old_int1 = $hrs_old_seconds;
							
							$Total = gmdate("H:i", $hrs_old_int1);	
						}
						if($Total=='') {
							$total_work = '0';
						} else {
							$total_work = $Total;
						}
						
						// =========================================================================================================
						// HARI LIBUR
						// =========================================================================================================
							$sql_check_libur = "SELECT * FROM xin_holidays WHERE company_ids like '%".$r->company_id."%' AND start_date >= '".$attendance_date."' AND end_date <= '".$attendance_date."' ";				
							// echo "<pre>";
							// print_r($sql_check_libur);
							// echo "</pre>";
							// die;			  
							$query_check_libur = $this->db->query($sql_check_libur);
							if ($query_check_libur->num_rows() > 0) {
								foreach ($query_check_libur->result() as $row_check_libur) :						    
								    $status_libur              = $this->lang->line('xin_on_holiday');								
									$status_libur_keterangan   = "Libur : ".$row_check_libur->event_name;						
								endforeach;

							} else {
								$status_libur              = '-';
								$status_libur_keterangan   ='-';
							}

						// =========================================================================================================
						// CUTI
						// =========================================================================================================
							$sql_check_cuti = "SELECT * FROM xin_leave_applications WHERE employee_id ='".$r->user_id."' AND from_date <= '".$attendance_date."' AND to_date >= '".$attendance_date."' ";				
							// echo "<pre>";
							// print_r($sql_check_cuti);
							// echo "</pre>";
							// die;			  
							$query_check_cuti = $this->db->query($sql_check_cuti);
							if ($query_check_cuti->num_rows() > 0) {
								foreach ($query_check_cuti->result() as $row_check_cuti) :						    
								    $status_cuti              = $this->lang->line('xin_on_leave');								
									$status_cuti_keterangan   = "Cuti : ".$row_check_cuti->reason;						
								endforeach;

							} else {
								$status_cuti              = '-';
								$status_cuti_keterangan   ='-';
							}
						
						// =========================================================================================================
						// SAKIT
						// =========================================================================================================
							$sql_check_sakit = "SELECT * FROM xin_sick_applications WHERE employee_id ='".$r->user_id."' AND from_date <= '".$attendance_date."' AND to_date >= '".$attendance_date."' ";				
							// echo "<pre>";
							// print_r($sql_check_sakit);
							// echo "</pre>";
							// die;			  
							$query_check_sakit = $this->db->query($sql_check_sakit);
							if ($query_check_sakit->num_rows() > 0) {
								foreach ($query_check_sakit->result() as $row_check_sakit) :						    
								    $status_sakit = $this->lang->line('xin_on_sick');	
								    $status_sakit_keterangan   = "Sakit : ".$row_check_sakit->reason;	
								endforeach;
							} else {
								$status_sakit     = '-';
								$status_sakit_keterangan   = '-';
							}
						
						// =========================================================================================================
						// IZIN
						// =========================================================================================================
							$sql_check_izin = "SELECT * FROM xin_izin_applications WHERE employee_id ='".$r->user_id."' AND from_date <= '".$attendance_date."' AND to_date >= '".$attendance_date."' ";				
							// echo "<pre>";
							// print_r($sql_check_izin);
							// echo "</pre>";
							// die;			  
							$query_check_izin = $this->db->query($sql_check_izin);
							if ($query_check_izin->num_rows() > 0) {
								foreach ($query_check_izin->result() as $row_check_izin) :						    
								    $status_izin = $this->lang->line('xin_on_izin');
								    $status_izin_jenis        =  $row_check_izin->is_half_day;
								    if($status_izin_jenis == 1){
								    	$izin_jenis ='Izin setengah hari';
								    } else {
								    	$izin_jenis ='Izin penuh hari';
								    }	
								    $status_izin_keterangan   = "Izin : ".$row_check_izin->reason.' - '.$izin_jenis;								
								endforeach;
							} else {
								$status_izin     = '-';
								 $status_izin_keterangan   = '-';
								 $status_izin_jenis        =  '';
							}
						
						// =========================================================================================================
						// DINAS
						// =========================================================================================================
							$sql_check_dinas = "SELECT * FROM xin_employee_travels WHERE employee_id ='".$r->user_id."' AND start_date <= '".$attendance_date."' AND end_date >= '".$attendance_date."' ";				
							// echo "<pre>";
							// print_r($sql_check_izin);
							// echo "</pre>";
							// die;			  
							$query_check_dinas = $this->db->query($sql_check_dinas);
							if ($query_check_dinas->num_rows() > 0) {

								foreach ($query_check_dinas->result() as $row_check_dinas) :
								    
								    $status_dinas = $this->lang->line('xin_travels_simbol');
								     $status_dinas_keterangan       = "Dinas : ".$row_check_dinas->description;										
													
								endforeach;

							} else {
								$status_dinas     = '-';
								$status_dinas_keterangan ='-';
							}

						// =========================================================================================================
						// LEMBUR
						// =========================================================================================================
							$sql_check_lembur = "SELECT * FROM xin_salary_overtime WHERE employee_id ='".$r->user_id."' AND overtime_date = '".$attendance_date."' ";				
							// echo "<pre>";
							// print_r($sql_check_izin);
							// echo "</pre>";
							// die;			  
							$query_check_lembur = $this->db->query($sql_check_lembur);
							if ($query_check_lembur->num_rows() > 0) {

								foreach ($query_check_lembur->result() as $row_check_lembur) :
								    
								     $status_lembur = $this->lang->line('xin_overtime_simbol');
								     $status_lembur_keterangan       = "Lembur : ".$row_check_lembur->description;										
													
								endforeach;

							} else {
								$status_lembur     = '-';
								$status_lembur_keterangan ='-';
							}

						// =========================================================================================================
						// PERIKSA
						// =========================================================================================================
						
							if ($status_libur !='-' )
							{  
								$status                = $this->lang->line('xin_on_holiday');
								$status_simbol         = $this->lang->line('xin_on_libur_simbol');
								$attendance_keterangan = $status_libur_keterangan;	
							} 

							else if ($status_cuti !='-' )
							{  
								$status                = $this->lang->line('xin_on_leave');
								$status_simbol         = $this->lang->line('xin_on_leave_simbol');
								$attendance_keterangan = $status_cuti_keterangan;	
							} 
							
							else if ($status_sakit !='-' )
							{
								 $status                = $this->lang->line('xin_on_sick');	
								 $status_simbol         = $this->lang->line('xin_on_sick_simbol');	
								 $attendance_keterangan = $status_sakit_keterangan;
							}

							else if ($status_izin !='-' )
							{
								 $status                = $this->lang->line('xin_on_izin');	
								 $status_simbol         = $this->lang->line('xin_on_izin_simbol');	
								 $attendance_keterangan = $status_izin_keterangan;
							}

							else if ($status_dinas !='-' )
							{
								 $status                = $this->lang->line('xin_travels');	
								 $status_simbol         = $this->lang->line('xin_travels_simbol');	
								 $attendance_keterangan = $status_dinas_keterangan;
							
							} 

							else if ($status_lembur !='-' )
							{
								 $status                = $this->lang->line('xin_overtime');	
								 $status_simbol         = $this->lang->line('xin_overtime_simbol');	
								 $attendance_keterangan = $status_lembur_keterangan;
							}

							else 
							{
								 $status                = $attendance[0]->attendance_status;	
								 $status_simbol         = 'H';	
								 $attendance_keterangan = 'Masuk';

							}

					
					} else {
						
						$clock_in2 = '00:00:00';
						$total_time_l = '0';
						$total_work = '0';

						
						// =========================================================================================================
						// HARI LIBUR
						// =========================================================================================================
							$sql_check_libur = "SELECT * FROM xin_holidays WHERE company_ids like '%".$r->company_id."%' AND start_date <= '".$attendance_date."' AND end_date >= '".$attendance_date."' ";				
							// echo "<pre>";
							// print_r($sql_check_libur);
							// echo "</pre>";
							// die;			  
							$query_check_libur = $this->db->query($sql_check_libur);
							if ($query_check_libur->num_rows() > 0) {
								foreach ($query_check_libur->result() as $row_check_libur) :						    
								    $status_libur              = $this->lang->line('xin_on_holiday');								
									$status_libur_keterangan   = "Libur : ".$row_check_libur->event_name;						
								endforeach;

							} else {
								$status_libur              = '-';
								$status_libur_keterangan   ='-';
							}

						// =========================================================================================================
						// CUTI
						// =========================================================================================================
							$sql_check_cuti = "SELECT * FROM xin_leave_applications WHERE employee_id ='".$r->user_id."' AND from_date <= '".$attendance_date."' AND to_date >= '".$attendance_date."' ";				
							// echo "<pre>";
							// print_r($sql_check_cuti);
							// echo "</pre>";
							// die;			  
							$query_check_cuti = $this->db->query($sql_check_cuti);
							if ($query_check_cuti->num_rows() > 0) {
								foreach ($query_check_cuti->result() as $row_check_cuti) :						    
								    $status_cuti              = $this->lang->line('xin_on_leave');								
									$status_cuti_keterangan   = "Cuti : ".$row_check_cuti->reason;						
								endforeach;

							} else {
								$status_cuti              = '-';
								$status_cuti_keterangan   ='-';
							}
						// =========================================================================================================
						// SAKIT
						// =========================================================================================================
							$sql_check_sakit = "SELECT * FROM xin_sick_applications WHERE employee_id ='".$r->user_id."' AND from_date <= '".$attendance_date."' AND to_date >= '".$attendance_date."' ";				
							// echo "<pre>";
							// print_r($sql_check_sakit);
							// echo "</pre>";
							// die;			  
							$query_check_sakit = $this->db->query($sql_check_sakit);
							if ($query_check_sakit->num_rows() > 0) {
								foreach ($query_check_sakit->result() as $row_check_sakit) :						    
								    $status_sakit = $this->lang->line('xin_on_sick');	
								    $status_sakit_keterangan   = "Sakit : ".$row_check_sakit->reason;	
								endforeach;
							} else {
								$status_sakit     = '-';
								$status_sakit_keterangan   = '-';
							}
						// =========================================================================================================
						// IZIN
						// =========================================================================================================
							$sql_check_izin = "SELECT * FROM xin_izin_applications WHERE employee_id ='".$r->user_id."' AND from_date <= '".$attendance_date."' AND to_date >= '".$attendance_date."' ";				
							// echo "<pre>";
							// print_r($sql_check_izin);
							// echo "</pre>";
							// die;			  
							$query_check_izin = $this->db->query($sql_check_izin);
							if ($query_check_izin->num_rows() > 0) {
								foreach ($query_check_izin->result() as $row_check_izin) :						    
								    $status_izin = $this->lang->line('xin_on_izin');
								    $status_izin_jenis        =  $row_check_izin->is_half_day;
								    if($status_izin_jenis == 1){
								    	$izin_jenis ='Izin setengah hari';
								    } else {
								    	$izin_jenis ='Izin penuh hari';
								    }	
								    $status_izin_keterangan   = "Izin : ".$row_check_izin->reason.' - '.$izin_jenis;							
								endforeach;
							} else {
								$status_izin     = '-';
								 $status_izin_keterangan   = '-';
								 $status_izin_jenis        =  '';
							}
						// =========================================================================================================
						// DINAS
						// =========================================================================================================
							$sql_check_dinas = "SELECT * FROM xin_employee_travels WHERE employee_id ='".$r->user_id."' AND start_date <= '".$attendance_date."' AND end_date >= '".$attendance_date."' ";				
							// echo "<pre>";
							// print_r($sql_check_izin);
							// echo "</pre>";
							// die;			  
							$query_check_dinas = $this->db->query($sql_check_dinas);
							if ($query_check_dinas->num_rows() > 0) {

								foreach ($query_check_dinas->result() as $row_check_dinas) :
								    
								    $status_dinas              = $this->lang->line('xin_travels_simbol');
								    $status_dinas_keterangan   = "Dinas : ".$row_check_dinas->description;										
													
								endforeach;

							} else {
								$status_dinas     = '-';
								$status_dinas_keterangan ='-';
							}
						// =========================================================================================================
						// LEMBUR
						// =========================================================================================================
							$sql_check_lembur = "SELECT * FROM xin_salary_overtime WHERE employee_id ='".$r->user_id."' AND overtime_date = '".$attendance_date."' ";				
							// echo "<pre>";
							// print_r($sql_check_izin);
							// echo "</pre>";
							// die;			  
							$query_check_lembur = $this->db->query($sql_check_lembur);
							if ($query_check_lembur->num_rows() > 0) {

								foreach ($query_check_lembur->result() as $row_check_lembur) :
								    
								     $status_lembur = $this->lang->line('xin_overtime_simbol');
								     $status_lembur_keterangan       = "Lembur : ".$row_check_lembur->description;										
													
								endforeach;

							} else {
								$status_lembur     = '-';
								$status_lembur_keterangan ='-';
							}
						// =========================================================================================================
						// PERIKSA
						// =========================================================================================================

							if($monday_in_time == '' && $day == 'Monday') 
							{
								$status                = $this->lang->line('xin_holiday');	
								$status_simbol         = $this->lang->line('xin_libur_simbol');	
								$attendance_keterangan = $this->lang->line('xin_holiday');
							} 
							
							else if($tuesday_in_time == '' && $day == 'Tuesday') 
							{
								$status                = $this->lang->line('xin_holiday');	
								$status_simbol         = $this->lang->line('xin_libur_simbol');	
								$attendance_keterangan = $this->lang->line('xin_holiday');
							} 
							
							else if($wednesday_in_time == '' && $day == 'Wednesday') 
							{
								$status                = $this->lang->line('xin_holiday');	
								$status_simbol         = $this->lang->line('xin_libur_simbol');	
								$attendance_keterangan = $this->lang->line('xin_holiday');
							} 
							
							else if($thursday_in_time == '' && $day == 'Thursday') 
							{
								$status                = $this->lang->line('xin_holiday');	
								$status_simbol         = $this->lang->line('xin_libur_simbol');	
								$attendance_keterangan = $this->lang->line('xin_holiday');
							} 
							
							else if($friday_in_time == '' && $day == 'Friday')
							{
								$status                = $this->lang->line('xin_holiday');	
								$status_simbol         = $this->lang->line('xin_libur_simbol');	
								$attendance_keterangan = $this->lang->line('xin_holiday');	
							} 
							
							else if($saturday_in_time == '' && $day == 'Saturday') 
							{
								$status                = $this->lang->line('xin_holiday');	
								$status_simbol         = $this->lang->line('xin_libur_simbol');	
								$attendance_keterangan = $this->lang->line('xin_holiday');
							} 
							
							else if($sunday_in_time == '' && $day == 'Sunday') 
							{
								$status                = $this->lang->line('xin_holiday');	
								$status_simbol         = $this->lang->line('xin_libur_simbol');	
								$attendance_keterangan = $this->lang->line('xin_holiday');
							} 

							else if ($status_libur !='-' )
							{ 						   
								$status                = $this->lang->line('xin_holiday');
								$status_simbol         = $this->lang->line('xin_libur_simbol');	
								$attendance_keterangan = $status_libur_keterangan;
							} 
							
							else if ($status_cuti !='-' )
							{   
							   // on leave
								$status                = $this->lang->line('xin_on_leave');
								$status_simbol         = $this->lang->line('xin_on_leave_simbol');
								$attendance_keterangan = $status_cuti_keterangan;	
							} 
							
							else if ($status_sakit !='-' )
							{
								 $status                = $this->lang->line('xin_on_sick');	
								 $status_simbol         = $this->lang->line('xin_on_sick_simbol');	
								 $attendance_keterangan = $status_sakit_keterangan;
							}

							else if ($status_izin !='-' )
							{
								 $status                = $this->lang->line('xin_on_izin');	
								 $status_simbol         = $this->lang->line('xin_on_izin_simbol');	
								 $attendance_keterangan = $status_izin_keterangan;
							}

							else if ($status_lembur !='-' )
							{
								 $status                = $this->lang->line('xin_overtime');	
								 $status_simbol         = $this->lang->line('xin_overtime_simbol');	
								 $attendance_keterangan = $status_lembur_keterangan;
							}

							else if ($status_dinas !='-' )
							{
								 $status                = $this->lang->line('xin_travels');	
								 $status_simbol         = $this->lang->line('xin_travels_simbol');	
								 $attendance_keterangan = $status_dinas_keterangan;
							}						
							
							else 
							{
								$status                = $this->lang->line('xin_absent');
								$status_simbol         = $this->lang->line('xin_absent_simbol');
								$attendance_keterangan = $this->lang->line('xin_absent_ket');	
							}
					}
					
					// ==============================================================================================================
					// CEK PULANG
					// =============================================================================================================
						// check if clock-out for date
						$check_out = $this->Timesheet_model->attendance_first_out_check_new($r->employee_id,$attendance_date);		
						
						if($check_out->num_rows() == 1){
							
							/* early time */
							$early_time =  new DateTime($out_time.' '.$attendance_date);
							
							// check clock in time
							$first_out = $this->Timesheet_model->attendance_first_out_new($r->employee_id,$attendance_date);
							
							// clock out
							$clock_out = new DateTime($first_out[0]->clock_out);
							
							if ($first_out[0]->clock_out!='') {
								
								$clock_out2 = $clock_out->format('H:i:s');
														
								// PULANG CEPAT
								$early_new_time     = strtotime($out_time.' '.$attendance_date);
								$clock_out_time_new = strtotime($first_out[0]->clock_out);
							
								if($early_new_time <= $clock_out_time_new) {
								
									$total_time_e = '0';
								
								} else {			
									$interval_lateo = $clock_out->diff($early_time);
									$hours_e        = $interval_lateo->format('%h');
									$minutes_e      = $interval_lateo->format('%i');
									$total_time_e   = $hours_e*60+$minutes_e;
								}
								
								// OVERTIME
								$over_time =  new DateTime($out_time.' '.$attendance_date);
								$overtime2 = $over_time->format('H:i:s');

								// over time
								$over_time_new = strtotime($out_time.' '.$attendance_date);
								$clock_out_time_new1 = strtotime($first_out[0]->clock_out);
								
								if($clock_out_time_new1 <= $over_time_new) {
									$overtime2 = '-';
								} else {			
									$interval_lateov = $clock_out->diff($over_time);
									$hours_ov   = $interval_lateov->format('%h');
									$minutes_ov = $interval_lateov->format('%i');

									$overtime2 = $hours_ov*60+$minutes_ov;
								}				
								
							} else {
								$clock_out2   =  '00:00:00';
								$total_time_e = '0';
								$overtime2    = '0';							
							}
									
						} else {
							$clock_out2   =  '00:00:00';
							$total_time_e = '0';
							$overtime2    = '0';
							
						}

						// attendance date
						$d_date = $this->Core_model->set_date_format($attendance_date);
						//
						$fclckIn = $clock_in2;
						$fclckOut = $clock_out2;
						
						$clock_in_a = $in_time.' s/d '.$out_time;

						if ($fclckIn == '-' || $fclckOut == '-'){

							$total_work = '0';

						} else {

							$total_work_cin  =  new DateTime($fclckIn);
							$total_work_cout =  new DateTime($fclckOut);
							
							$interval_cin = $total_work_cout->diff($total_work_cin);
							$hours_in   = $interval_cin->format('%h');
							$minutes_in = $interval_cin->format('%i');
							$total_work = $hours_in*60+$minutes_in;
						
						}	

							
						if ($clock_in_a == '00:00:00 s/d 00:00:00') {
							
							$info_jam = 'Libur';
						
						}	 else {
							 
							 $info_jam = $clock_in_a;
						}					

					$data[] = array(
						$no,					
						strtoupper($full_name),	
						substr(strtoupper($designation_name),0,30),
						$comp_name,				
						$info_jam,				
						$d_date,
						'',	
						'',	
						'',	
						'',	
						'',	
						'',	
						''						
					);
					$no++;
				// }
	      	}
	      
		    $output = array(
			   "draw" => $draw,
				 "recordsTotal" => $employee->num_rows(),
				 "recordsFiltered" => $employee->num_rows(),
				 "data" => $data
			);
		    echo json_encode($output);
		    exit();
	    }

	    // daily attendance list > timesheet
	    public function attendance_reguler_list()
	    {

			$data['title'] = $this->Core_model->site_title();
			$session       = $this->session->userdata('username');
			$user_info     = $this->Core_model->read_user_info($session['user_id']);

			if(!empty($session)){ 
				$this->load->view("admin/timesheet/attendance_reguler_list", $data);
			} else {
				redirect('admin/');
			}

			// Datatables Variables
			$draw               = intval($this->input->get("draw"));
			$start              = intval($this->input->get("start"));
			$length             = intval($this->input->get("length"));
			$role_resources_ids = $this->Core_model->user_role_resource();

			$attendance_date    = $this->input->get("attendance_date");
			$jenis_gaji         = $this->input->get("location_id");
			$company_id         = $this->input->get("company_id");

		
			
			$employee = $this->Employees_model->get_attendance_jenis_gaji_employees_reguler_load($jenis_gaji,$company_id);
			
			$system   = $this->Core_model->read_setting_info(1);
			
			$data = array();

			$no = 1;
			
	        foreach($employee->result() as $r) {

	        		$sql1 ="DELETE FROM xin_attendance_time WHERE 1=1
					        AND employee_id ='".$r->user_id."' AND  attendance_date = '".$attendance_date."'  ";
					// print_r($sql1);
					// exit();
					$query1   = $this->db->query($sql1);
					  		
					// get company
					$company = $this->Core_model->read_company_info($r->company_id);
					if(!is_null($company)){
						$comp_name = $company[0]->name;
					} else {
						$comp_name = '';
					}
					// get posisi				
					$designation = $this->Designation_model->read_designation_information($r->designation_id);

					if(!is_null($designation)){
						$designation_name = $designation[0]->designation_name;
					} else {
						$designation_name = '';	
					}	

					// user full name
					$full_name = $r->first_name.' '.$r->last_name;	
					
					// get office shift for employee
					$get_day = strtotime($attendance_date);
					$day = date('l', $get_day);		

					$office_reguler = $this->Timesheet_model->read_office_jadwal_information_reguler($r->office_shift_id);					

					if(!is_null($office_reguler))
					{
						$monday_in_time      = $office_reguler[0]->monday_in_time;
						$monday_out_time     = $office_reguler[0]->monday_out_time;
						
						$tuesday_in_time     = $office_reguler[0]->tuesday_in_time;
						$tuesday_out_time    = $office_reguler[0]->tuesday_out_time;
						
						$wednesday_in_time   = $office_reguler[0]->wednesday_in_time;
						$wednesday_out_time  = $office_reguler[0]->wednesday_out_time;
						
						$thursday_in_time    = $office_reguler[0]->thursday_in_time;
						$thursday_out_time   = $office_reguler[0]->thursday_out_time;
						
						$friday_in_time      = $office_reguler[0]->friday_in_time;
						$friday_out_time     = $office_reguler[0]->friday_out_time;
						
						$saturday_in_time    = $office_reguler[0]->saturday_in_time;
						$saturday_out_time   = $office_reguler[0]->saturday_out_time;
						
						$sunday_in_time      = $office_reguler[0]->sunday_in_time;
						$sunday_out_time     = $office_reguler[0]->sunday_out_time;					
					} 
					else 
					{
						
						$monday_in_time      = '';	
						$tuesday_in_time     = '';	
						$wednesday_in_time   = '';	
						$thursday_in_time    = '';	
						$friday_in_time      = '';	
						$saturday_in_time    = '';	
						$sunday_in_time      = '';
						$monday_out_time     = '';	
						$tuesday_out_time    = '';	
						$wednesday_out_time  = '';	
						$thursday_out_time   = '';	
						$friday_out_time     = '';	
						$saturday_out_time   = '';	
						$sunday_out_time     = '';	
					}

					// echo "<pre>";
					// print_r($this->db->last_query());
					// echo "</pre>";
					// die();
					
					// get clock in/clock out of each employee
					if($day == 'Monday') {
						if( $monday_in_time ==''){
							$in_time = '00:00:00';
							$out_time = '00:00:00';
						} else {
							$in_time =  $monday_in_time ;
							$out_time = $monday_out_time;
						}
					} else if($day == 'Tuesday') {
						if( $tuesday_in_time ==''){
							$in_time = '00:00:00';
							$out_time = '00:00:00';
						} else {
							$in_time = $tuesday_in_time;
							$out_time = $tuesday_out_time;
						}
					} else if($day == 'Wednesday') {
						if( $wednesday_in_time ==''){
							$in_time = '00:00:00';
							$out_time = '00:00:00';
						} else {
							$in_time = $wednesday_in_time;
							$out_time = $wednesday_out_time;
						}
					} else if($day == 'Thursday') {
						if( $thursday_in_time ==''){
							$in_time = '00:00:00';
							$out_time = '00:00:00';
						} else {
							$in_time = $thursday_in_time;
							$out_time = $thursday_out_time;
						}
					} else if($day == 'Friday') {
						if( $friday_in_time ==''){
							$in_time = '00:00:00';
							$out_time = '00:00:00';
						} else {
							$in_time = $friday_in_time;
							$out_time = $friday_out_time;
						}
					} else if($day == 'Saturday') {
						if( $saturday_in_time == ''){
							$in_time = '00:00:00';
							$out_time = '00:00:00';
						} else {
							$in_time = $saturday_in_time;
							$out_time = $saturday_out_time;
						}
					} else if($day == 'Sunday') {
						if( $sunday_in_time ==''){
							$in_time = '00:00:00';
							$out_time = '00:00:00';
						} else {
							$in_time = $sunday_in_time;
							$out_time = $sunday_out_time;
						}
					}

					// check if clock-in for date
					$attendance_status = '';


					// ==============================================================================================================
					// CEK MASUK
					// =============================================================================================================				
					
					$check_masuk = $this->Timesheet_model->attendance_first_in_check_new($r->employee_id,$attendance_date);		
					
					if ($check_masuk->num_rows() > 0){
					
						// check clock in time
						$attendance = $this->Timesheet_model->attendance_first_in_new($r->employee_id,$attendance_date);
						
						// clock in
						$clock_in = new DateTime($attendance[0]->clock_in);
						$clock_in2 = $clock_in->format('H:i:s');

											
						$office_time =  new DateTime($in_time.' '.$attendance_date);

						// HITUNG TERLAMBAT				
						$office_time_new   = strtotime($in_time.' '.$attendance_date);
						$clock_in_time_new = strtotime($attendance[0]->clock_in);
						
						if($clock_in_time_new == '') {
							$total_time_l = '0';

						} else if($clock_in_time_new <= $office_time_new) {
							$total_time_l = '0';
						
						} else if($clock_in_time_new > $office_time_new) {
							$interval_late = $clock_in->diff($office_time);
							$hours_l   = $interval_late->format('%h');
							$minutes_l = $interval_late->format('%i');		
							$total_time_l = $hours_l*60+$minutes_l;
						} else {
							$total_time_l = '0';
						}
						
						// total hours work/ed
						$total_hrs        = $this->Timesheet_model->total_hours_worked_attendance($r->user_id,$attendance_date);
						$hrs_old_int1     = '';
						$Total            = '';
						$Trest            = '';
						$total_time_rs    = '';
						$hrs_old_int_res1 = '';
						
						foreach ($total_hrs->result() as $hour_work){
							// total work			
							$timee = $hour_work->total_work.':00';
							$str_time =$timee;
				
							$str_time = preg_replace("/^([\d]{1,2})\:([\d]{2})$/", "00:$1:$2", $str_time);
							
							sscanf($str_time, "%d:%d:%d", $hours, $minutes, $seconds);
							
							$hrs_old_seconds = $hours * 3600 + $minutes * 60 + $seconds;
							
							$hrs_old_int1 = $hrs_old_seconds;
							
							$Total = gmdate("H:i", $hrs_old_int1);	
						}
						
						if($Total=='') {
							$total_work = '0';
						} else {
							$total_work = $Total;
						}
						
						// =========================================================================================================
						// HARI LIBUR
						// =========================================================================================================
							$sql_check_libur = "SELECT * FROM xin_holidays WHERE company_ids like '%".$r->company_id."%' AND start_date >= '".$attendance_date."' AND end_date <= '".$attendance_date."' ";				
							// echo "<pre>";
							// print_r($sql_check_libur);
							// echo "</pre>";
							// die;			  
							$query_check_libur = $this->db->query($sql_check_libur);
							if ($query_check_libur->num_rows() > 0) {
								foreach ($query_check_libur->result() as $row_check_libur) :						    
								    $status_libur              = $this->lang->line('xin_on_holiday');								
									$status_libur_keterangan   = "Libur : ".$row_check_libur->event_name;						
								endforeach;

							} else {
								$status_libur              = '-';
								$status_libur_keterangan   ='-';
							}

						// =========================================================================================================
						// CUTI
						// =========================================================================================================
							$sql_check_cuti = "SELECT * FROM xin_leave_applications WHERE employee_id ='".$r->user_id."' AND from_date <= '".$attendance_date."' AND to_date >= '".$attendance_date."' ";				
							// echo "<pre>";
							// print_r($sql_check_cuti);
							// echo "</pre>";
							// die;			  
							$query_check_cuti = $this->db->query($sql_check_cuti);
							if ($query_check_cuti->num_rows() > 0) {
								foreach ($query_check_cuti->result() as $row_check_cuti) :						    
								    $status_cuti              = $this->lang->line('xin_on_leave');								
									$status_cuti_keterangan   = "Cuti : ".$row_check_cuti->reason;						
								endforeach;

							} else {
								$status_cuti              = '-';
								$status_cuti_keterangan   ='-';
							}
						
						// =========================================================================================================
						// SAKIT
						// =========================================================================================================
							$sql_check_sakit = "SELECT * FROM xin_sick_applications WHERE employee_id ='".$r->user_id."' AND from_date <= '".$attendance_date."' AND to_date >= '".$attendance_date."' ";				
							// echo "<pre>";
							// print_r($sql_check_sakit);
							// echo "</pre>";
							// die;			  
							$query_check_sakit = $this->db->query($sql_check_sakit);
							if ($query_check_sakit->num_rows() > 0) {
								foreach ($query_check_sakit->result() as $row_check_sakit) :						    
								    $status_sakit = $this->lang->line('xin_on_sick');	
								    $status_sakit_keterangan   = "Sakit : ".$row_check_sakit->reason;	
								endforeach;
							} else {
								$status_sakit     = '-';
								$status_sakit_keterangan   = '-';
							}
						
						// =========================================================================================================
						// IZIN
						// =========================================================================================================
							$sql_check_izin = "SELECT * FROM xin_izin_applications WHERE employee_id ='".$r->user_id."' AND from_date <= '".$attendance_date."' AND to_date >= '".$attendance_date."' ";				
							// echo "<pre>";
							// print_r($sql_check_izin);
							// echo "</pre>";
							// die;			  
							$query_check_izin = $this->db->query($sql_check_izin);
							if ($query_check_izin->num_rows() > 0) {
								foreach ($query_check_izin->result() as $row_check_izin) :						    
								    $status_izin = $this->lang->line('xin_on_izin');
								    $status_izin_jenis        =  $row_check_izin->is_half_day;
								    if($status_izin_jenis == 1){
								    	$izin_jenis        ='Izin setengah hari';
								    	// $izin_jenis_jumlah = 0;
								    } else {
								    	$izin_jenis        ='Izin penuh hari';
								    	// $izin_jenis_jumlah = 1;
								    }	
								    $status_izin_keterangan   = "Izin : ".$row_check_izin->reason.' - '.$izin_jenis;									
								endforeach;
							} else {
								$status_izin              = '-';
								$status_izin_keterangan   = '-';
								$status_izin_jenis        = '';
								// $izin_jenis_jumlah        = 0;
							}
						
						// =========================================================================================================
						// DINAS
						// =========================================================================================================
							$sql_check_dinas = "SELECT * FROM xin_employee_travels WHERE employee_id ='".$r->user_id."' AND start_date <= '".$attendance_date."' AND end_date >= '".$attendance_date."' ";				
							// echo "<pre>";
							// print_r($sql_check_izin);
							// echo "</pre>";
							// die;			  
							$query_check_dinas = $this->db->query($sql_check_dinas);
							if ($query_check_dinas->num_rows() > 0) {

								foreach ($query_check_dinas->result() as $row_check_dinas) :
								    
								    $status_dinas = $this->lang->line('xin_travels_simbol');
								     $status_dinas_keterangan       = "Dinas : ".$row_check_dinas->description;										
													
								endforeach;

							} else {
								$status_dinas     = '-';
								$status_dinas_keterangan ='-';
							}

						// =========================================================================================================
						// LEMBUR
						// =========================================================================================================
							$sql_check_lembur = "SELECT * FROM xin_salary_overtime WHERE employee_id ='".$r->user_id."' AND overtime_date = '".$attendance_date."' ";				
							// echo "<pre>";
							// print_r($sql_check_izin);
							// echo "</pre>";
							// die;			  
							$query_check_lembur = $this->db->query($sql_check_lembur);
							if ($query_check_lembur->num_rows() > 0) {

								foreach ($query_check_lembur->result() as $row_check_lembur) :
								    
								     $status_lembur = $this->lang->line('xin_overtime_simbol');
								     $status_lembur_keterangan       = "Lembur : ".$row_check_lembur->description;										
													
								endforeach;

							} else {
								$status_lembur     = '-';
								$status_lembur_keterangan ='-';
							}

						// =========================================================================================================
						// PERIKSA
						// =========================================================================================================
						
							if ($status_libur !='-' )
							{  
								$status                = $this->lang->line('xin_on_holiday');
								$status_simbol         = $this->lang->line('xin_on_libur_simbol');
								$attendance_keterangan = $status_libur_keterangan;	
							} 

							else if ($status_cuti !='-' )
							{  
								$status                = $this->lang->line('xin_on_leave');
								$status_simbol         = $this->lang->line('xin_on_leave_simbol');
								$attendance_keterangan = $status_cuti_keterangan;	
							} 
							
							else if ($status_sakit !='-' )
							{
								 $status                = $this->lang->line('xin_on_sick');	
								 $status_simbol         = $this->lang->line('xin_on_sick_simbol');	
								 $attendance_keterangan = $status_sakit_keterangan;
							}

							else if ($status_izin !='-' )
							{
								 $status                = $this->lang->line('xin_on_izin');	
								 $status_simbol         = $this->lang->line('xin_on_izin_simbol');	
								 $attendance_keterangan = $status_izin_keterangan;
							}

							else if ($status_dinas !='-' )
							{
								 $status                = $this->lang->line('xin_travels');	
								 $status_simbol         = $this->lang->line('xin_travels_simbol');	
								 $attendance_keterangan = $status_dinas_keterangan;
							
							} 

							else if ($status_lembur !='-' )
							{
								 $status                = $this->lang->line('xin_overtime');	
								 $status_simbol         = $this->lang->line('xin_overtime_simbol');	
								 $attendance_keterangan = $status_lembur_keterangan;
							}

							else 
							{
								 $status                = $attendance[0]->attendance_status;	
								 $status_simbol         = 'H';	
								 $attendance_keterangan = 'Masuk';

							}

					
					} else {
						
						$clock_in2 = '00:00:00';
						$total_time_l = '0';
						$total_work = '0';

						
						// =========================================================================================================
						// HARI LIBUR
						// =========================================================================================================
							$sql_check_libur = "SELECT * FROM xin_holidays WHERE company_ids like '%".$r->company_id."%' AND start_date >= '".$attendance_date."' AND end_date <= '".$attendance_date."' ";				
							// echo "<pre>";
							// print_r($sql_check_libur);
							// echo "</pre>";
							// die;			  
							$query_check_libur = $this->db->query($sql_check_libur);
							if ($query_check_libur->num_rows() > 0) {
								foreach ($query_check_libur->result() as $row_check_libur) :						    
								    $status_libur              = $this->lang->line('xin_on_holiday');								
									$status_libur_keterangan   = "Libur : ".$row_check_libur->event_name;						
								endforeach;

							} else {
								$status_libur              = '-';
								$status_libur_keterangan   ='-';
							}

						// =========================================================================================================
						// CUTI
						// =========================================================================================================
							$sql_check_cuti = "SELECT * FROM xin_leave_applications WHERE employee_id ='".$r->user_id."' AND from_date <= '".$attendance_date."' AND to_date >= '".$attendance_date."' ";				
							// echo "<pre>";
							// print_r($sql_check_cuti);
							// echo "</pre>";
							// die;			  
							$query_check_cuti = $this->db->query($sql_check_cuti);
							if ($query_check_cuti->num_rows() > 0) {
								foreach ($query_check_cuti->result() as $row_check_cuti) :						    
								    $status_cuti              = $this->lang->line('xin_on_leave');								
									$status_cuti_keterangan   = "Cuti : ".$row_check_cuti->reason;						
								endforeach;

							} else {
								$status_cuti              = '-';
								$status_cuti_keterangan   ='-';
							}

						// =========================================================================================================
						// SAKIT
						// =========================================================================================================
							$sql_check_sakit = "SELECT * FROM xin_sick_applications WHERE employee_id ='".$r->user_id."' AND from_date <= '".$attendance_date."' AND to_date >= '".$attendance_date."' ";				
							// echo "<pre>";
							// print_r($sql_check_sakit);
							// echo "</pre>";
							// die;			  
							$query_check_sakit = $this->db->query($sql_check_sakit);
							if ($query_check_sakit->num_rows() > 0) {
								foreach ($query_check_sakit->result() as $row_check_sakit) :						    
								    $status_sakit = $this->lang->line('xin_on_sick');	
								    $status_sakit_keterangan   = "Sakit : ".$row_check_sakit->reason;	
								endforeach;
							} else {
								$status_sakit     = '-';
								$status_sakit_keterangan   = '-';
							}

						// =========================================================================================================
						// IZIN
						// =========================================================================================================
							$sql_check_izin = "SELECT * FROM xin_izin_applications WHERE employee_id ='".$r->user_id."' AND from_date <= '".$attendance_date."' AND to_date >= '".$attendance_date."' ";				
							// echo "<pre>";
							// print_r($sql_check_izin);
							// echo "</pre>";
							// die;			  
							$query_check_izin = $this->db->query($sql_check_izin);
							if ($query_check_izin->num_rows() > 0) {
								foreach ($query_check_izin->result() as $row_check_izin) :						    
								    $status_izin = $this->lang->line('xin_on_izin');
								    $status_izin_jenis        =  $row_check_izin->is_half_day;
								    if($status_izin_jenis == 1){
								    	$izin_jenis        ='Izin setengah hari';
								    	// $izin_jenis_jumlah = 0;
								    } else {
								    	$izin_jenis        ='Izin penuh hari';
								    	// $izin_jenis_jumlah = 1;
								    }	
								    $status_izin_keterangan   = "Izin : ".$row_check_izin->reason.' - '.$izin_jenis;							
								endforeach;
							} else {
								$status_izin              = '-';
								$status_izin_keterangan   = '-';
								$status_izin_jenis        = '';
								// $izin_jenis_jumlah        = 0;
							}

						// =========================================================================================================
						// DINAS
						// =========================================================================================================
							$sql_check_dinas = "SELECT * FROM xin_employee_travels WHERE employee_id ='".$r->user_id."' AND start_date <= '".$attendance_date."' AND end_date >= '".$attendance_date."' ";				
							// echo "<pre>";
							// print_r($sql_check_izin);
							// echo "</pre>";
							// die;			  
							$query_check_dinas = $this->db->query($sql_check_dinas);
							if ($query_check_dinas->num_rows() > 0) {

								foreach ($query_check_dinas->result() as $row_check_dinas) :
								    
								    $status_dinas              = $this->lang->line('xin_travels_simbol');
								    $status_dinas_keterangan   = "Dinas : ".$row_check_dinas->description;										
													
								endforeach;

							} else {
								$status_dinas     = '-';
								$status_dinas_keterangan ='-';
							}

						// =========================================================================================================
						// LEMBUR
						// =========================================================================================================
							$sql_check_lembur = "SELECT * FROM xin_salary_overtime WHERE employee_id ='".$r->user_id."' AND overtime_date = '".$attendance_date."' ";				
							// echo "<pre>";
							// print_r($sql_check_izin);
							// echo "</pre>";
							// die;			  
							$query_check_lembur = $this->db->query($sql_check_lembur);
							if ($query_check_lembur->num_rows() > 0) {

								foreach ($query_check_lembur->result() as $row_check_lembur) :
								    
								     $status_lembur = $this->lang->line('xin_overtime_simbol');
								     $status_lembur_keterangan       = "Lembur : ".$row_check_lembur->description;										
													
								endforeach;

							} else {
								$status_lembur     = '-';
								$status_lembur_keterangan ='-';
							}

						// =========================================================================================================
						// PERIKSA
						// =========================================================================================================

							if($monday_in_time == '' && $day == 'Monday') 
							{
								$status                = $this->lang->line('xin_holiday');	
								$status_simbol         = $this->lang->line('xin_libur_simbol');	
								$attendance_keterangan = $this->lang->line('xin_holiday');
							} 
							
							else if($tuesday_in_time == '' && $day == 'Tuesday') 
							{
								$status                = $this->lang->line('xin_holiday');	
								$status_simbol         = $this->lang->line('xin_libur_simbol');	
								$attendance_keterangan = $this->lang->line('xin_holiday');
							} 
							
							else if($wednesday_in_time == '' && $day == 'Wednesday') 
							{
								$status                = $this->lang->line('xin_holiday');	
								$status_simbol         = $this->lang->line('xin_libur_simbol');	
								$attendance_keterangan = $this->lang->line('xin_holiday');
							} 
							
							else if($thursday_in_time == '' && $day == 'Thursday') 
							{
								$status                = $this->lang->line('xin_holiday');	
								$status_simbol         = $this->lang->line('xin_libur_simbol');	
								$attendance_keterangan = $this->lang->line('xin_holiday');
							} 
							
							else if($friday_in_time == '' && $day == 'Friday')
							{
								$status                = $this->lang->line('xin_holiday');	
								$status_simbol         = $this->lang->line('xin_libur_simbol');	
								$attendance_keterangan = $this->lang->line('xin_holiday');	
							} 
							
							else if($saturday_in_time == '' && $day == 'Saturday') 
							{
								$status                = $this->lang->line('xin_holiday');	
								$status_simbol         = $this->lang->line('xin_libur_simbol');	
								$attendance_keterangan = $this->lang->line('xin_holiday');
							} 
							
							else if($sunday_in_time == '' && $day == 'Sunday') 
							{
								$status                = $this->lang->line('xin_holiday');	
								$status_simbol         = $this->lang->line('xin_libur_simbol');	
								$attendance_keterangan = $this->lang->line('xin_holiday');
							} 

							else if ($status_libur !='-' )
							{ 						   
								$status                = $this->lang->line('xin_holiday');
								$status_simbol         = $this->lang->line('xin_libur_simbol');	
								$attendance_keterangan = $status_libur_keterangan;
							} 
							
							else if ($status_cuti !='-' )
							{   
							   // on leave
								$status                = $this->lang->line('xin_on_leave');
								$status_simbol         = $this->lang->line('xin_on_leave_simbol');
								$attendance_keterangan = $status_cuti_keterangan;	
							} 
							
							else if ($status_sakit !='-' )
							{
								 $status                = $this->lang->line('xin_on_sick');	
								 $status_simbol         = $this->lang->line('xin_on_sick_simbol');	
								 $attendance_keterangan = $status_sakit_keterangan;
							}

							else if ($status_izin !='-' )
							{
								 $status                = $this->lang->line('xin_on_izin');	
								 $status_simbol         = $this->lang->line('xin_on_izin_simbol');	
								 $attendance_keterangan = $status_izin_keterangan;
							}

							else if ($status_lembur !='-' )
							{
								 $status                = $this->lang->line('xin_overtime');	
								 $status_simbol         = $this->lang->line('xin_overtime_simbol');	
								 $attendance_keterangan = $status_lembur_keterangan;
							}

							else if ($status_dinas !='-' )
							{
								 $status                = $this->lang->line('xin_travels');	
								 $status_simbol         = $this->lang->line('xin_travels_simbol');	
								 $attendance_keterangan = $status_dinas_keterangan;
							}						
							
							else 
							{
								$status                = $this->lang->line('xin_absent');
								$status_simbol         = $this->lang->line('xin_absent_simbol');
								$attendance_keterangan = $this->lang->line('xin_absent_ket');	
							}
					}
					
					// ==============================================================================================================
					// CEK PULANG
					// =============================================================================================================
						// check if clock-out for date
						$check_out = $this->Timesheet_model->attendance_first_out_check_new($r->employee_id,$attendance_date);		
						
						if($check_out->num_rows() == 1){
							
							/* early time */
							$early_time =  new DateTime($out_time.' '.$attendance_date);
							
							// check clock in time
							$first_out = $this->Timesheet_model->attendance_first_out_new($r->employee_id,$attendance_date);
							
							// clock out
							$clock_out = new DateTime($first_out[0]->clock_out);
							
							if ($first_out[0]->clock_out!='') {
								
								$clock_out2 = $clock_out->format('H:i:s');
														
								// PULANG CEPAT
								$early_new_time     = strtotime($out_time.' '.$attendance_date);
								$clock_out_time_new = strtotime($first_out[0]->clock_out);
							
								if($early_new_time <= $clock_out_time_new) {
								
									$total_time_e = '0';
								
								} else {			
									$interval_lateo = $clock_out->diff($early_time);
									$hours_e        = $interval_lateo->format('%h');
									$minutes_e      = $interval_lateo->format('%i');
									$total_time_e   = $hours_e*60+$minutes_e;
								}
								
								// OVERTIME
								$over_time =  new DateTime($out_time.' '.$attendance_date);
								$overtime2 = $over_time->format('H:i:s');

								// over time
								$over_time_new = strtotime($out_time.' '.$attendance_date);
								$clock_out_time_new1 = strtotime($first_out[0]->clock_out);
								
								if($clock_out_time_new1 <= $over_time_new) {
									$overtime2 = '-';
								} else {			
									$interval_lateov = $clock_out->diff($over_time);
									$hours_ov   = $interval_lateov->format('%h');
									$minutes_ov = $interval_lateov->format('%i');

									$overtime2 = $hours_ov*60+$minutes_ov;
								}				
								
							} else {
								$clock_out2   =  '00:00:00';
								$total_time_e = '0';
								$overtime2    = '0';							
							}
									
						} else {
							$clock_out2   =  '00:00:00';
							$total_time_e = '0';
							$overtime2    = '0';
							
						}

						// attendance date
						$d_date = $this->Core_model->set_date_format($attendance_date);
						//
						$fclckIn = $clock_in2;
						$fclckOut = $clock_out2;
						
						$clock_in_a = $in_time.' s/d '.$out_time;

						if ($fclckIn == '-' || $fclckOut == '-'){

							$total_work = '0';

						} else {

							$total_work_cin  =  new DateTime($fclckIn);
							$total_work_cout =  new DateTime($fclckOut);
							
							$interval_cin = $total_work_cout->diff($total_work_cin);
							$hours_in   = $interval_cin->format('%h');
							$minutes_in = $interval_cin->format('%i');
							$total_work = $hours_in*60+$minutes_in;
						
						}	

					// ==============================================================================================================
					// CEK PULANG
					// =============================================================================================================

					
					// =========================================================================================================
					// PERIKSA
					// =========================================================================================================
						
						$fclckIn  = $clock_in2;
						$fclckOut = $clock_out2;

						$clock_in_a = $in_time.' s/d '.$out_time;

						if ($clock_in_a == '00:00:00 s/d 00:00:00'){
							$jd ='Libur';
						} else {
							$jd = $clock_in_a;
						}							
				
						if ( $clock_in2 == '00:00:00' && $clock_out2 == '00:00:00' ) {

							if ( $jd == 'Libur' ) {

								$status                = 'Libur';
								$status_simbol         = 'L';	
								$attendance_keterangan = 'Libur';
							
							} else {

								if ($status_libur !='-' )							
								{ 						   
									$status                = $this->lang->line('xin_holiday');
									$status_simbol         = $this->lang->line('xin_libur_simbol');	
									$attendance_keterangan = $status_libur_keterangan;
								
								}

								else if ($status_cuti !='-' )
								{   
								   // on leave
									$status                = $this->lang->line('xin_on_leave');
									$status_simbol         = $this->lang->line('xin_on_leave_simbol');
									$attendance_keterangan = $status_cuti_keterangan;	
								} 
								
								else if ($status_sakit !='-' )
								{
									 $status                = $this->lang->line('xin_on_sick');	
									 $status_simbol         = $this->lang->line('xin_on_sick_simbol');	
									 $attendance_keterangan = $status_sakit_keterangan;
								}

								else if ($status_izin !='-' )
								{
									 $status                = $this->lang->line('xin_on_izin');	
									 $status_simbol         = $this->lang->line('xin_on_izin_simbol');	
									 $attendance_keterangan = $status_izin_keterangan;
								} 

								else if ($status_dinas !='-' )
								{
									 $status                = $this->lang->line('xin_travels');	
									 $status_simbol         = $this->lang->line('xin_travels_simbol');	
									 $attendance_keterangan = $status_dinas_keterangan;
								}	
								else {

									$status                = $this->lang->line('xin_absent');
									$status_simbol         = $this->lang->line('xin_absent_simbol');
									$attendance_keterangan = $this->lang->line('xin_absent_ket');

								}

							}
							

						} else {

							if ( $clock_in2 != '00:00:00' && $clock_out2 != '00:00:00' ) 
							{

								if ($status_lembur !='-' )
								{
									 $status                = $this->lang->line('xin_overtime');	
									 $status_simbol         = $this->lang->line('xin_overtime_simbol');	
									 $attendance_keterangan = $status_lembur_keterangan;
								}
								else if ($status_dinas !='-' )
								{
									 $status                = $this->lang->line('xin_travels');	
									 $status_simbol         = $this->lang->line('xin_travels_simbol');	
									 $attendance_keterangan = $status_dinas_keterangan;
								}
								else if ($status_izin !='-' )
								{
									 $status                = $this->lang->line('xin_on_izin');	
									 $status_simbol         = $this->lang->line('xin_on_izin_simbol');	
									 $attendance_keterangan = $status_izin_keterangan;
								} 
								else if ($status_cuti !='-' )
								{   
								   // on leave
									$status                = $this->lang->line('xin_on_leave');
									$status_simbol         = $this->lang->line('xin_on_leave_simbol');
									$attendance_keterangan = $status_cuti_keterangan;	
								} 
								else if ($status_sakit !='-' )
								{
									 $status                = $this->lang->line('xin_on_sick');	
									 $status_simbol         = $this->lang->line('xin_on_sick_simbol');	
									 $attendance_keterangan = $status_sakit_keterangan;
								}
								else {
									$status                = 'Hadir';	
									$status_simbol         = 'H';	
									$attendance_keterangan = 'Masuk '.$status_izin_keterangan;
								}
								
							} 

							else if ( $clock_in2 == '00:00:00' && $clock_out2 != '00:00:00' ) 
							{

								if ($status_dinas !='-' )
								{
									 $status                = $this->lang->line('xin_travels');	
									 $status_simbol         = $this->lang->line('xin_travels_simbol');	
									 $attendance_keterangan = $status_dinas_keterangan;
								
								} 
								else if ($status_izin !='-' )
								{
									 $status                = $this->lang->line('xin_on_izin');	
									 $status_simbol         = $this->lang->line('xin_on_izin_simbol');	
									 $attendance_keterangan = $status_izin_keterangan;
								} 
								else if ($status_cuti !='-' )
								{   
								   // on leave
									$status                = $this->lang->line('xin_on_leave');
									$status_simbol         = $this->lang->line('xin_on_leave_simbol');
									$attendance_keterangan = $status_cuti_keterangan;	
								} 

								else if ($status_sakit !='-' )
								{
									 $status                = $this->lang->line('xin_on_sick');	
									 $status_simbol         = $this->lang->line('xin_on_sick_simbol');	
									 $attendance_keterangan = $status_sakit_keterangan;
								}

								else 
								{	

									$status                = $this->lang->line('xin_absent');
									$status_simbol         = $this->lang->line('xin_absent_simbol');
									$attendance_keterangan = $this->lang->line('xin_absent_ket');
								}
								
							} 

							else if ( $clock_in2 != '00:00:00' && $clock_out2 == '00:00:00' ) 
							{

								if ($status_dinas !='-' )
								{
									 $status                = $this->lang->line('xin_travels');	
									 $status_simbol         = $this->lang->line('xin_travels_simbol');	
									 $attendance_keterangan = $status_dinas_keterangan;
								}
								else if ($status_izin !='-' )
								{
									 $status                = $this->lang->line('xin_on_izin');	
									 $status_simbol         = $this->lang->line('xin_on_izin_simbol');	
									 $attendance_keterangan = $status_izin_keterangan;
								} 
								else if ($status_sakit !='-' )
								{
									 $status                = $this->lang->line('xin_on_sick');	
									 $status_simbol         = $this->lang->line('xin_on_sick_simbol');	
									 $attendance_keterangan = $status_sakit_keterangan;
								}

								else if ($status_cuti !='-' )
								{   
								   // on leave
									$status                = $this->lang->line('xin_on_leave');
									$status_simbol         = $this->lang->line('xin_on_leave_simbol');
									$attendance_keterangan = $status_cuti_keterangan;	
								} 
								
								
								else 
								{	

									$status                = $this->lang->line('xin_absent');
									$status_simbol         = $this->lang->line('xin_absent_simbol');
									$attendance_keterangan = $this->lang->line('xin_absent_ket');
								}
								
							}							

						}

					

					$sql2 ="INSERT INTO xin_attendance_time
								(   
									employee_id,
									employee_pin,
									company_id,				 	
								 	location_id,
								 	date_of_joining,
								 	jenis_gaji,
								 	jenis_kerja,
								 	attendance_jadwal,
								 	attendance_date,
								 	clock_in,
								 	clock_out,								 	
								 	time_late,
								 	early_leaving,
								 	overtime,
								 	total_work,
								 	attendance_status,
								 	attendance_status_simbol,
								 	attendance_keterangan,
								 	rekap_date
								 
								) VALUES 
								(
									'$r->user_id',
									'$r->employee_pin',
									'$r->company_id',
									'$r->location_id',
									'$r->date_of_joining',
									'$jenis_gaji',
									'R',
									'$jd',
								 	'$attendance_date',
								 	'$fclckIn',							 	
								 	'$fclckOut',								 	
								 	'$total_time_l',							 	
								 	'$total_time_e',
								 	'$overtime2',
								 	'$total_work',
								 	'$status',
								 	'$status_simbol',
								 	'$attendance_keterangan',
								 	NOW()
								 	

								)";
						
						// print_r($sql2);
						// exit();

						$query2 = $this->db->query($sql2);

					if ($fclckIn =='00:00:00') {
						$jam_masuk = '-';
					} else {
						$jam_masuk = $fclckIn;
					}

					if ($fclckOut =='00:00:00') {
						$jam_pulang = '-';
					} else {
						$jam_pulang = $fclckOut;
					}

					$data[] = array(
						$no,					
						strtoupper($full_name),	
						substr(strtoupper($designation_name),0,30),
						$comp_name,				
						$jd,				
						$d_date,
						$status,
						$jam_masuk,
						$jam_pulang,
						$total_time_l,
						$total_time_e,
						$overtime2,
						$total_work,
						$attendance_keterangan					
					);
					$no++;
				// }
	      	}
	      
		    $output = array(
			   "draw" => $draw,
				 "recordsTotal" => $employee->num_rows(),
				 "recordsFiltered" => $employee->num_rows(),
				 "data" => $data
			);
		    echo json_encode($output);
		    exit();
	    }

	// =============================================================================
	// 0920 TARIK ABSENSI SHIFT
	// =============================================================================

		// daily attendance > timesheet
		public function attendance_shift()
	    {
			$session = $this->session->userdata('username');
			if(empty($session)){ 
				redirect('admin/');
			}
			$data['title']       = 'Tarik Absensi Shift | '.$this->Core_model->site_title();
			$data['icon']        = '<i class="fa fa-hand-o-up"></i>';
			$data['desc']        = '<span><b>INFORMASI : </b> Proses Tarik Absensi Setiap Hari ini dilakukan setelah Proses Pengajuan di Input semua </span>';
			$data['breadcrumbs'] = 'Tarik Absensi Shift (Per Hari / Tanggal)';			
			$data['path_url']    = 'attendance_shift';
			
			$data['get_all_companies']    = $this->Company_model->get_company();
			$data['all_office_shifts'] = $this->Location_model->all_payroll_jenis();

			$role_resources_ids = $this->Core_model->user_role_resource();
			if(in_array('0921',$role_resources_ids)) {
				if(!empty($session)){
				$data['subview'] = $this->load->view("admin/timesheet/attendance_shift_list", $data, TRUE);
				$this->load->view('admin/layout/layout_main', $data); //page load
				} else {
					redirect('admin/dashboard/');
				}	
			} else {
				redirect('admin/dashboard');
			}	  
	    }
	   
	    public function attendance_shift_list()
	    {

			$data['title'] = $this->Core_model->site_title();
			$session       = $this->session->userdata('username');
			$user_info     = $this->Core_model->read_user_info($session['user_id']);

			if(!empty($session)){ 
				$this->load->view("admin/timesheet/attendance_shift_list", $data);
			} else {
				redirect('admin/');
			}

			// Datatables Variables
			$draw               = intval($this->input->get("draw"));
			$start              = intval($this->input->get("start"));
			$length             = intval($this->input->get("length"));
			$role_resources_ids = $this->Core_model->user_role_resource();

			$attendance_date    = $this->input->get("attendance_date");
			$jenis_gaji         = $this->input->get("location_id");
			$company_id         = $this->input->get("company_id");
					
			
			$employee = $this->Employees_model->get_attendance_jenis_gaji_employees_shift_load($jenis_gaji,$company_id);
			
			$system   = $this->Core_model->read_setting_info(1);
			
			$data = array();

			$no = 1;
			
	        foreach($employee->result() as $r) {
					  		
					// get company
					$company = $this->Core_model->read_company_info($r->company_id);
					if(!is_null($company)){
						$comp_name = $company[0]->name;
					} else {
						$comp_name = '';
					}
					// get posisi				
					$designation = $this->Designation_model->read_designation_information($r->designation_id);

					if(!is_null($designation)){
						$designation_name = $designation[0]->designation_name;
					} else {
						$designation_name = '';	
					}	

					// user full name
					$full_name = $r->first_name.' '.$r->last_name;	
					
					// get office shift for employee
					$get_day = strtotime($attendance_date);
					
					$tgl = date('d', $get_day);	
					$day = date('l', $get_day);	

					
				    $tanggal = 'T'.date('d', $get_day);	

					$office_shift = $this->Timesheet_model->read_office_jadwal_information_shift($r->office_shift_id);					

					if(!is_null($office_shift))
					{
						$tanggal_shift = $this->Timesheet_model->read_office_jadwal_jam_shift($office_shift[0]->$tanggal);

						// echo "<pre>";
						// print_r( $tanggal );
						// print_r($office_shift[0]->$tanggal);
						// echo "</pre>";
						// die();

						if(!is_null($tanggal_shift)) {

							if($day == 'Monday') {
								if( $office_shift[0]->$tanggal ==''){
									$in_time = '00:00:00';
									$out_time = '00:00:00';
								} else {
									$in_time =  $tanggal_shift[0]->start_date ;
									$out_time = $tanggal_shift[0]->end_date ;
								}
							} else if($day == 'Tuesday') {
								if( $office_shift[0]->$tanggal ==''){
									$in_time = '00:00:00';
									$out_time = '00:00:00';
								} else {
									$in_time =  $tanggal_shift[0]->start_date ;
									$out_time = $tanggal_shift[0]->end_date ;
								}
							} else if($day == 'Wednesday') {
								if( $office_shift[0]->$tanggal ==''){
									$in_time = '00:00:00';
									$out_time = '00:00:00';
								} else {
									$in_time =  $tanggal_shift[0]->start_date ;
									$out_time = $tanggal_shift[0]->end_date ;
								}
							} else if($day == 'Thursday') {
								if( $office_shift[0]->$tanggal ==''){
									$in_time = '00:00:00';
									$out_time = '00:00:00';
								} else {
									$in_time =  $tanggal_shift[0]->start_date ;
									$out_time = $tanggal_shift[0]->end_date ;
								}
							} else if($day == 'Friday') {
								if( $office_shift[0]->$tanggal ==''){
									$in_time = '00:00:00';
									$out_time = '00:00:00';
								} else {
									$in_time =  $tanggal_shift[0]->start_date ;
									$out_time = $tanggal_shift[0]->end_date ;
								}
							} else if($day == 'Saturday') {
								if( $office_shift[0]->$tanggal == ''){
									$in_time = '00:00:00';
									$out_time = '00:00:00';
								} else {
									$in_time =  $tanggal_shift[0]->start_date ;
									$out_time = $tanggal_shift[0]->end_date ;
								}
							} else if($day == 'Sunday') {
								if( $office_shift[0]->$tanggal ==''){
									$in_time = '00:00:00';
									$out_time = '00:00:00';
								} else {
									$in_time =  $tanggal_shift[0]->start_date ;
									$out_time = $tanggal_shift[0]->end_date ;
								}
							} else {
								$in_time = '00:00:00';
								$out_time = '00:00:00';
							}

						} else {
							$in_time = '00:00:00';
							$out_time = '00:00:00';
						}					
					
					} 
					else 
					{
						$in_time  = '00:00:00';
						$out_time = '00:00:00';	
					}				

					// echo "<pre>";
					// print_r($this->db->last_query());
					// echo "</pre>";
					// die();
					
					// get clock in/clock out of each employee					

					// check if clock-in for date

					$sql1 ="DELETE FROM xin_attendance_time WHERE employee_id ='".$r->user_id."' AND attendance_date = '".$attendance_date."'  ";
					// print_r($sql1);
					// exit();
					$query1   = $this->db->query($sql1);	

					$attendance_status = '';


					// ==============================================================================================================
					// CEK MASUK
					// =============================================================================================================				
					
						$check = $this->Timesheet_model->attendance_first_in_check_new($r->employee_id,$attendance_date);		
						
						if ($check->num_rows() > 0){
						
							// check clock in time
							$attendance = $this->Timesheet_model->attendance_first_in_new($r->employee_id,$attendance_date);
							
							// clock in
							$clock_in = new DateTime($attendance[0]->clock_in);
							$clock_in2 = $clock_in->format('H:i:s');

												
							$office_time =  new DateTime($in_time.' '.$attendance_date);

							// HITUNG TERLAMBAT				
							$office_time_new   = strtotime($in_time.' '.$attendance_date);
							$clock_in_time_new = strtotime($attendance[0]->clock_in);
							
							if($clock_in_time_new == '') {
								$total_time_l = '0';

							} else if($clock_in_time_new <= $office_time_new) {
								$total_time_l = '0';
							
							} else if($clock_in_time_new > $office_time_new) {
								$interval_late = $clock_in->diff($office_time);
								$hours_l   = $interval_late->format('%h');
								$minutes_l = $interval_late->format('%i');		
								$total_time_l = $hours_l*60+$minutes_l;
							} else {
								$total_time_l = '0';
							}
							
							// total hours work/ed
							$total_hrs        = $this->Timesheet_model->total_hours_worked_attendance($r->user_id,$attendance_date);
							$hrs_old_int1     = '';
							$Total            = '';
							$Trest            = '';
							$total_time_rs    = '';
							$hrs_old_int_res1 = '';
							foreach ($total_hrs->result() as $hour_work){
								// total work			
								$timee = $hour_work->total_work.':00';
								$str_time =$timee;
					
								$str_time = preg_replace("/^([\d]{1,2})\:([\d]{2})$/", "00:$1:$2", $str_time);
								
								sscanf($str_time, "%d:%d:%d", $hours, $minutes, $seconds);
								
								$hrs_old_seconds = $hours * 3600 + $minutes * 60 + $seconds;
								
								$hrs_old_int1 = $hrs_old_seconds;
								
								$Total = gmdate("H:i", $hrs_old_int1);	
							}
							if($Total=='') {
								$total_work = '0';
							} else {
								$total_work = $Total;
							}
							
							// =========================================================================================================
							// HARI LIBUR
							// =========================================================================================================
								$sql_check_libur = "SELECT * FROM xin_holidays WHERE company_ids like '%".$r->company_id."%' AND start_date >= '".$attendance_date."' AND end_date <= '".$attendance_date."' ";				
								// echo "<pre>";
								// print_r($sql_check_libur);
								// echo "</pre>";
								// die;			  
								$query_check_libur = $this->db->query($sql_check_libur);
								if ($query_check_libur->num_rows() > 0) {
									foreach ($query_check_libur->result() as $row_check_libur) :						    
									    $status_libur              = $this->lang->line('xin_on_holiday');								
										$status_libur_keterangan   = "Libur : ".$row_check_libur->event_name;						
									endforeach;

								} else {
									$status_libur              = '-';
									$status_libur_keterangan   = '-';
								}

							// =========================================================================================================
							// CUTI
							// =========================================================================================================
								$sql_check_cuti = "SELECT * FROM xin_leave_applications WHERE employee_id ='".$r->user_id."' AND from_date <= '".$attendance_date."' AND to_date >= '".$attendance_date."' ";				
								// echo "<pre>";
								// print_r($sql_check_cuti);
								// echo "</pre>";
								// die;			  
								$query_check_cuti = $this->db->query($sql_check_cuti);
								if ($query_check_cuti->num_rows() > 0) {
									foreach ($query_check_cuti->result() as $row_check_cuti) :						    
									    $status_cuti              = $this->lang->line('xin_on_leave');								
										$status_cuti_keterangan   = "Cuti : ".$row_check_cuti->reason;						
									endforeach;

								} else {
									$status_cuti              = '-';
									$status_cuti_keterangan   ='-';
								}
							
							// =========================================================================================================
							// SAKIT
							// =========================================================================================================
								$sql_check_sakit = "SELECT * FROM xin_sick_applications WHERE employee_id ='".$r->user_id."' AND from_date <= '".$attendance_date."' AND to_date >= '".$attendance_date."' ";				
								// echo "<pre>";
								// print_r($sql_check_sakit);
								// echo "</pre>";
								// die;			  
								$query_check_sakit = $this->db->query($sql_check_sakit);
								if ($query_check_sakit->num_rows() > 0) {
									foreach ($query_check_sakit->result() as $row_check_sakit) :						    
									    $status_sakit = $this->lang->line('xin_on_sick');	
									    $status_sakit_keterangan   = "Sakit : ".$row_check_sakit->reason;	
									endforeach;
								} else {
									$status_sakit     = '-';
									$status_sakit_keterangan   = '-';
								}
							
							// =========================================================================================================
							// IZIN
							// =========================================================================================================
								$sql_check_izin = "SELECT * FROM xin_izin_applications WHERE employee_id ='".$r->user_id."' AND from_date <= '".$attendance_date."' AND to_date >= '".$attendance_date."' ";				
								// echo "<pre>";
								// print_r($sql_check_izin);
								// echo "</pre>";
								// die;			  
								$query_check_izin = $this->db->query($sql_check_izin);
								if ($query_check_izin->num_rows() > 0) {
									foreach ($query_check_izin->result() as $row_check_izin) :						    
									    $status_izin = $this->lang->line('xin_on_izin');
									    $status_izin_jenis        =  $row_check_izin->is_half_day;
									    if($status_izin_jenis == 1){
									    	$izin_jenis ='Izin setengah hari';
									    } else {
									    	$izin_jenis ='Izin penuh hari';
									    }	
									    $status_izin_keterangan   = "Izin : ".$row_check_izin->reason.' - '.$izin_jenis;
									endforeach;
								} else {
									$status_izin     = '-';
									 $status_izin_keterangan   = '-';
									 $status_izin_jenis        =  '';
								}
							
							// =========================================================================================================
							// DINAS
							// =========================================================================================================
								$sql_check_dinas = "SELECT * FROM xin_employee_travels WHERE employee_id ='".$r->user_id."' AND start_date <= '".$attendance_date."' AND end_date >= '".$attendance_date."' ";				
								// echo "<pre>";
								// print_r($sql_check_izin);
								// echo "</pre>";
								// die;			  
								$query_check_dinas = $this->db->query($sql_check_dinas);
								if ($query_check_dinas->num_rows() > 0) {

									foreach ($query_check_dinas->result() as $row_check_dinas) :
									    
									    $status_dinas = $this->lang->line('xin_travels_simbol');
									     $status_dinas_keterangan       = "Dinas : ".$row_check_dinas->description;										
														
									endforeach;

								} else {
									$status_dinas     = '-';
									$status_dinas_keterangan ='-';
								}

							// =========================================================================================================
							// LEMBUR
							// =========================================================================================================
								$sql_check_lembur = "SELECT * FROM xin_salary_overtime WHERE employee_id ='".$r->user_id."' AND overtime_date = '".$attendance_date."' ";				
								// echo "<pre>";
								// print_r($sql_check_izin);
								// echo "</pre>";
								// die;			  
								$query_check_lembur = $this->db->query($sql_check_lembur);
								if ($query_check_lembur->num_rows() > 0) {

									foreach ($query_check_lembur->result() as $row_check_lembur) :
									    
									     $status_lembur = $this->lang->line('xin_overtime_simbol');
									     $status_lembur_keterangan       = "Lembur : ".$row_check_lembur->description;										
														
									endforeach;

								} else {
									$status_lembur     = '-';
									$status_lembur_keterangan ='-';
								}

							
						} else {
							
							$clock_in2    = '00:00:00';
							$total_time_l = '0';
							$total_work   = '0';

							
							// =========================================================================================================
							// HARI LIBUR
							// =========================================================================================================
								$sql_check_libur = "SELECT * FROM xin_holidays WHERE company_ids like '%".$r->company_id."%' AND start_date >= '".$attendance_date."' AND end_date <= '".$attendance_date."' ";				
								// echo "<pre>";
								// print_r($sql_check_libur);
								// echo "</pre>";
								// die;			  
								$query_check_libur = $this->db->query($sql_check_libur);
								if ($query_check_libur->num_rows() > 0) {
									foreach ($query_check_libur->result() as $row_check_libur) :						    
									    $status_libur              = $this->lang->line('xin_on_holiday');								
										$status_libur_keterangan   = "Libur : ".$row_check_libur->event_name;						
									endforeach;

								} else {
									$status_libur              = '-';
									$status_libur_keterangan   ='-';
								}

							// =========================================================================================================
							// CUTI
							// =========================================================================================================
								$sql_check_cuti = "SELECT * FROM xin_leave_applications WHERE employee_id ='".$r->user_id."' AND from_date <= '".$attendance_date."' AND to_date >= '".$attendance_date."' ";				
								// echo "<pre>";
								// print_r($sql_check_cuti);
								// echo "</pre>";
								// die;			  
								$query_check_cuti = $this->db->query($sql_check_cuti);
								if ($query_check_cuti->num_rows() > 0) {
									foreach ($query_check_cuti->result() as $row_check_cuti) :						    
									    $status_cuti              = $this->lang->line('xin_on_leave');								
										$status_cuti_keterangan   = "Cuti : ".$row_check_cuti->reason;						
									endforeach;

								} else {
									$status_cuti              = '-';
									$status_cuti_keterangan   ='-';
								}
							
							// =========================================================================================================
							// SAKIT
							// =========================================================================================================
								$sql_check_sakit = "SELECT * FROM xin_sick_applications WHERE employee_id ='".$r->user_id."' AND from_date <= '".$attendance_date."' AND to_date >= '".$attendance_date."' ";				
								// echo "<pre>";
								// print_r($sql_check_sakit);
								// echo "</pre>";
								// die;			  
								$query_check_sakit = $this->db->query($sql_check_sakit);
								if ($query_check_sakit->num_rows() > 0) {
									foreach ($query_check_sakit->result() as $row_check_sakit) :						    
									    $status_sakit = $this->lang->line('xin_on_sick');	
									    $status_sakit_keterangan   = "Sakit : ".$row_check_sakit->reason;	
									endforeach;
								} else {
									$status_sakit     = '-';
									$status_sakit_keterangan   = '-';
								}
							
							// =========================================================================================================
							// IZIN
							// =========================================================================================================
								$sql_check_izin = "SELECT * FROM xin_izin_applications WHERE employee_id ='".$r->user_id."' AND from_date <= '".$attendance_date."' AND to_date >= '".$attendance_date."' ";				
								// echo "<pre>";
								// print_r($sql_check_izin);
								// echo "</pre>";
								// die;			  
								$query_check_izin = $this->db->query($sql_check_izin);
								if ($query_check_izin->num_rows() > 0) {
									foreach ($query_check_izin->result() as $row_check_izin) :						    
									    $status_izin = $this->lang->line('xin_on_izin');
									    $status_izin_jenis        =  $row_check_izin->is_half_day;
									    if($status_izin_jenis == 1){
									    	$izin_jenis ='Izin setengah hari';
									    } else {
									    	$izin_jenis ='Izin penuh hari';
									    }	
									    $status_izin_keterangan   = "Izin : ".$row_check_izin->reason.' - '.$izin_jenis;								
									endforeach;
								} else {
									$status_izin     = '-';
									 $status_izin_keterangan   = '-';
									 $status_izin_jenis        =  '';
								}
							
							// =========================================================================================================
							// DINAS
							// =========================================================================================================
								$sql_check_dinas = "SELECT * FROM xin_employee_travels WHERE employee_id ='".$r->user_id."' AND start_date <= '".$attendance_date."' AND end_date >= '".$attendance_date."' ";				
								// echo "<pre>";
								// print_r($sql_check_izin);
								// echo "</pre>";
								// die;			  
								$query_check_dinas = $this->db->query($sql_check_dinas);
								if ($query_check_dinas->num_rows() > 0) {

									foreach ($query_check_dinas->result() as $row_check_dinas) :
									    
									    $status_dinas              = $this->lang->line('xin_travels_simbol');
									    $status_dinas_keterangan   = "Dinas : ".$row_check_dinas->description;										
														
									endforeach;

								} else {
									$status_dinas     = '-';
									$status_dinas_keterangan ='-';
								}
							
							// =========================================================================================================
							// LEMBUR
							// =========================================================================================================
								$sql_check_lembur = "SELECT * FROM xin_salary_overtime WHERE employee_id ='".$r->user_id."' AND overtime_date = '".$attendance_date."' ";				
								// echo "<pre>";
								// print_r($sql_check_izin);
								// echo "</pre>";
								// die;			  
								$query_check_lembur = $this->db->query($sql_check_lembur);
								if ($query_check_lembur->num_rows() > 0) {

									foreach ($query_check_lembur->result() as $row_check_lembur) :
									    
									     $status_lembur = $this->lang->line('xin_overtime_simbol');
									     $status_lembur_keterangan       = "Lembur : ".$row_check_lembur->description;										
														
									endforeach;

								} else {
									$status_lembur     = '-';
									$status_lembur_keterangan ='-';
								}
							
						}
					
					// ==============================================================================================================
					// CEK PULANG
					// =============================================================================================================
						
						// echo "<pre>";
						// print_r($office_shift[0]->$tanggal);
						// echo "</pre>";
						// die();	
						
						$cek_tanggal = 'T'.date('d', strtotime ($attendance_date));	

						$cek_office_shift = $this->Timesheet_model->read_office_jadwal_information_shift($r->office_shift_id);					

						if(!is_null($cek_office_shift)) {

							$info_status = $cek_office_shift[0]->$cek_tanggal;

						} else {

							$info_status = '';
						}

						if ( $info_status == 'M' || $info_status == 'S' || $info_status == 'L'  ) {


							$check_out = $this->Timesheet_model->attendance_first_out_check_new_case($r->employee_id,$attendance_date,$clock_in2);

							if($check_out->num_rows() == 1)
							{
								
								/* early time */
								$early_time =  new DateTime($out_time.' '.$attendance_date);
								
								// check clock in time
								$first_out = $this->Timesheet_model->attendance_first_out_new_case($r->employee_id,$attendance_date,$clock_in2);
								
								// clock out
								$clock_out = new DateTime($first_out[0]->clock_out);
								
								if ($first_out[0]->clock_out!='') {

									if ($info_status == 'L'){
										$clock_out2   =  '00:00:00';
										$total_time_e = '0';
										$overtime2    = '0';
									
									} else {
										$clock_out2 = $clock_out->format('H:i:s');
															
										// PULANG CEPAT
										$early_new_time     = strtotime($out_time.' '.$attendance_date);
										$clock_out_time_new = strtotime($first_out[0]->clock_out);
									
										if($early_new_time <= $clock_out_time_new) {
										
											$total_time_e = '0';
										
										} else {			
											$interval_lateo = $clock_out->diff($early_time);
											$hours_e        = $interval_lateo->format('%h');
											$minutes_e      = $interval_lateo->format('%i');
											$total_time_e   = $hours_e*60+$minutes_e;
										}
										
										// OVERTIME
										$over_time =  new DateTime($out_time.' '.$attendance_date);
										$overtime2 = $over_time->format('H:i:s');

										// over time
										$over_time_new = strtotime($out_time.' '.$attendance_date);
										$clock_out_time_new1 = strtotime($first_out[0]->clock_out);
										
										if($clock_out_time_new1 <= $over_time_new) {
											$overtime2 = '-';
										} else {			
											$interval_lateov = $clock_out->diff($over_time);
											$hours_ov   = $interval_lateov->format('%h');
											$minutes_ov = $interval_lateov->format('%i');

											$overtime2 = $hours_ov*60+$minutes_ov;
										}
									}								

								} else {
									$clock_out2   =  '00:00:00';
									$total_time_e = '0';
									$overtime2    = '0';							
								}
										
							} else {
								
								$tanggal_berikutnya = date('Y-m-d', strtotime ('+1 days', strtotime ($attendance_date)));

								$check_out = $this->Timesheet_model->attendance_first_out_check_new_shift($r->employee_id,$tanggal_berikutnya);		
								
								$clock_in_a = $in_time.' s/d '.$out_time;
							    if ($clock_in_a == '00:00:00 s/d 00:00:00'){

							    	$clock_out2   =  '00:00:00';
									$total_time_e = '0';
									$overtime2    = '0';

								} else if($check_out->num_rows() == 1){
									
									/* early time */
									$early_time =  new DateTime($out_time.' '.$tanggal_berikutnya);
									
									// check clock in time
									$first_out = $this->Timesheet_model->attendance_first_out_new_shift($r->employee_id,$tanggal_berikutnya);
									
									// clock out
									$clock_out = new DateTime($first_out[0]->clock_out);
									
									if ($first_out[0]->clock_out!='') {
										
										$clock_out2 = $clock_out->format('H:i:s');
																
										// PULANG CEPAT
										$early_new_time     = strtotime($out_time.' '.$tanggal_berikutnya);
										$clock_out_time_new = strtotime($first_out[0]->clock_out);
									
										if($early_new_time <= $clock_out_time_new) {
										
											$total_time_e = '0';
										
										} else {			
											$interval_lateo = $clock_out->diff($early_time);
											$hours_e        = $interval_lateo->format('%h');
											$minutes_e      = $interval_lateo->format('%i');
											$total_time_e   = $hours_e*60+$minutes_e;
										}
										
										// OVERTIME
										$over_time =  new DateTime($out_time.' '.$tanggal_berikutnya);
										$overtime2 = $over_time->format('H:i:s');

										// over time
										$over_time_new = strtotime($out_time.' '.$tanggal_berikutnya);
										$clock_out_time_new1 = strtotime($first_out[0]->clock_out);
										
										if($clock_out_time_new1 <= $over_time_new) {
											$overtime2 = '-';
										} else {			
											$interval_lateov = $clock_out->diff($over_time);
											$hours_ov   = $interval_lateov->format('%h');
											$minutes_ov = $interval_lateov->format('%i');

											$overtime2 = $hours_ov*60+$minutes_ov;
										}				
										
									} else {
										$clock_out2   =  '00:00:00';
										$total_time_e = '0';
										$overtime2    = '0';							
									}
											
								} else {
									$clock_out2   =  '00:00:00';
									$total_time_e = '0';
									$overtime2    = '0';
									
								}
							}

						} else {

							$check_out = $this->Timesheet_model->attendance_first_out_check_new($r->employee_id,$attendance_date);		


							 if($check_out->num_rows() == 1)
							{
								
								/* early time */
								$early_time =  new DateTime($out_time.' '.$attendance_date);
								
								// check clock in time
								$first_out = $this->Timesheet_model->attendance_first_out_new($r->employee_id,$attendance_date);
								
								// clock out
								$clock_out = new DateTime($first_out[0]->clock_out);
								
								if ($first_out[0]->clock_out!='') {
									
									$clock_out2 = $clock_out->format('H:i:s');
															
									// PULANG CEPAT
									$early_new_time     = strtotime($out_time.' '.$attendance_date);
									$clock_out_time_new = strtotime($first_out[0]->clock_out);
								
									if($early_new_time <= $clock_out_time_new) {
									
										$total_time_e = '0';
									
									} else {			
										$interval_lateo = $clock_out->diff($early_time);
										$hours_e        = $interval_lateo->format('%h');
										$minutes_e      = $interval_lateo->format('%i');
										$total_time_e   = $hours_e*60+$minutes_e;
									}
									
									// OVERTIME
									$over_time =  new DateTime($out_time.' '.$attendance_date);
									$overtime2 = $over_time->format('H:i:s');

									// over time
									$over_time_new = strtotime($out_time.' '.$attendance_date);
									$clock_out_time_new1 = strtotime($first_out[0]->clock_out);
									
									if($clock_out_time_new1 <= $over_time_new) {
										$overtime2 = '-';
									} else {			
										$interval_lateov = $clock_out->diff($over_time);
										$hours_ov   = $interval_lateov->format('%h');
										$minutes_ov = $interval_lateov->format('%i');

										$overtime2 = $hours_ov*60+$minutes_ov;
									}				
									
								} else {
									$clock_out2   =  '00:00:00';
									$total_time_e = '0';
									$overtime2    = '0';							
								}
										
							} else {
								$clock_out2   =  '00:00:00';
								$total_time_e = '0';
								$overtime2    = '0';
								
							}

						}						

						// attendance date
						$d_date = $this->Core_model->set_date_format($attendance_date);
						//
						$fclckIn = $clock_in2;
						$fclckOut = $clock_out2;
						
						// $clock_in_a = $in_time.' s/d '.$out_time;

						if ($fclckIn == '00:00:00' || $fclckOut == '00:00:00'){

							$total_work = '0';

						} else {

							$total_work_cin  =  new DateTime($fclckIn);
							$total_work_cout =  new DateTime($fclckOut);
							
							$interval_cin = $total_work_cout->diff($total_work_cin);
							$hours_in   = $interval_cin->format('%h');
							$minutes_in = $interval_cin->format('%i');
							$total_work = 0; //$hours_in*60+$minutes_in;
						
						}	

					// =========================================================================================================
					// PERIKSA
					// =========================================================================================================
						

						$fclckIn  = $clock_in2;
						$fclckOut = $clock_out2;

						$clock_in_a = $in_time.' s/d '.$out_time;

						if ($clock_in_a == '00:00:00 s/d 00:00:00'){
							$jd ='Libur';

						} else {
							$jd =  $office_shift[0]->$tanggal.' - '.$clock_in_a;
						}							
				
						if ( $clock_in2 == '00:00:00' && $clock_out2 == '00:00:00' ) {

							if ( $jd == 'Libur' ) {

								$status                = 'Libur';
								$status_simbol         = 'L';	
								$attendance_keterangan = 'Libur';
							
							} else {

								// if ($status_libur !='' )							
								// { 						   
								// 	$status                = $this->lang->line('xin_holiday');
								// 	$status_simbol         = $this->lang->line('xin_libur_simbol');	
								// 	$attendance_keterangan = $status_libur_keterangan;
								
								// }

								if ($status_cuti !='-' )
								{   
								   // on leave
									$status                = $this->lang->line('xin_on_leave');
									$status_simbol         = $this->lang->line('xin_on_leave_simbol');
									$attendance_keterangan = $status_cuti_keterangan;	
								} 
								
								else if ($status_sakit !='-' )
								{
									 $status                = $this->lang->line('xin_on_sick');	
									 $status_simbol         = $this->lang->line('xin_on_sick_simbol');	
									 $attendance_keterangan = $status_sakit_keterangan;
								}

								else if ($status_izin !='-' )
								{
									 $status                = $this->lang->line('xin_on_izin');	
									 $status_simbol         = $this->lang->line('xin_on_izin_simbol');	
									 $attendance_keterangan = $status_izin_keterangan;
								} 

								else if ($status_dinas !='-' )
								{
									 $status                = $this->lang->line('xin_travels');	
									 $status_simbol         = $this->lang->line('xin_travels_simbol');	
									 $attendance_keterangan = $status_dinas_keterangan;
								}	
								else {

									$status                = $this->lang->line('xin_absent');
									$status_simbol         = $this->lang->line('xin_absent_simbol');
									$attendance_keterangan = $this->lang->line('xin_absent_ket');

								}

							}
							

						} else {

							if ( $clock_in2 != '00:00:00' && $clock_out2 != '00:00:00' ) 
							{

								if ($status_lembur !='-' )
								{
									 $status                = $this->lang->line('xin_overtime');	
									 $status_simbol         = $this->lang->line('xin_overtime_simbol');	
									 $attendance_keterangan = $status_lembur_keterangan;
								}
								else if ($status_dinas !='-' )
								{
									 $status                = $this->lang->line('xin_travels');	
									 $status_simbol         = $this->lang->line('xin_travels_simbol');	
									 $attendance_keterangan = $status_dinas_keterangan;
								}
								else if ($status_izin !='-' )
								{
									 $status                = $this->lang->line('xin_on_izin');	
									 $status_simbol         = $this->lang->line('xin_on_izin_simbol');	
									 $attendance_keterangan = $status_izin_keterangan;
								} 
								else if ($status_sakit !='-' )
								{
									 $status                = $this->lang->line('xin_on_sick');	
									 $status_simbol         = $this->lang->line('xin_on_sick_simbol');	
									 $attendance_keterangan = $status_sakit_keterangan;
								}
								else if ($status_cuti !='-' )
								{   
								   // on leave
									$status                = $this->lang->line('xin_on_leave');
									$status_simbol         = $this->lang->line('xin_on_leave_simbol');
									$attendance_keterangan = $status_cuti_keterangan;	
								}
								else {
									$status                = 'Hadir';	
									$status_simbol         = 'H';	
									$attendance_keterangan = 'Masuk';
								}
								
							} 

							else if ( $clock_in2 == '00:00:00' && $clock_out2 != '00:00:00' ) 
							{

								if ($status_dinas !='-' )
								{
									 $status                = $this->lang->line('xin_travels');	
									 $status_simbol         = $this->lang->line('xin_travels_simbol');	
									 $attendance_keterangan = $status_dinas_keterangan;
								
								}								
								else if ($status_izin !='-' )
								{
									 $status                = $this->lang->line('xin_on_izin');	
									 $status_simbol         = $this->lang->line('xin_on_izin_simbol');	
									 $attendance_keterangan = $status_izin_keterangan;
								} 
								else if ($status_cuti !='-' )
								{   
								   // on leave
									$status                = $this->lang->line('xin_on_leave');
									$status_simbol         = $this->lang->line('xin_on_leave_simbol');
									$attendance_keterangan = $status_cuti_keterangan;	
								} 

								else if ($status_sakit !='-' )
								{
									 $status                = $this->lang->line('xin_on_sick');	
									 $status_simbol         = $this->lang->line('xin_on_sick_simbol');	
									 $attendance_keterangan = $status_sakit_keterangan;
								}
								else if ( $jd == 'Libur' ) 
								{

									$status                = 'Libur';
									$status_simbol         = 'L';	
									$attendance_keterangan = 'Libur';
								
								} 
								else 
								{	

									$status                = $this->lang->line('xin_absent');
									$status_simbol         = $this->lang->line('xin_absent_simbol');
									$attendance_keterangan = $this->lang->line('xin_absent_ket');
								}
								
							} 

							else if ( $clock_in2 != '00:00:00' && $clock_out2 == '00:00:00' ) 
							{

								
								if ($status_sakit !='-' )
								{
									 $status                = $this->lang->line('xin_on_sick');	
									 $status_simbol         = $this->lang->line('xin_on_sick_simbol');	
									 $attendance_keterangan = $status_sakit_keterangan;
								}
								else if ($status_izin !='-' )
								{
									 $status                = $this->lang->line('xin_on_izin');	
									 $status_simbol         = $this->lang->line('xin_on_izin_simbol');	
									 $attendance_keterangan = $status_izin_keterangan;
								} 

								else if ($status_cuti !='-' )
								{   
								   // on leave
									$status                = $this->lang->line('xin_on_leave');
									$status_simbol         = $this->lang->line('xin_on_leave_simbol');
									$attendance_keterangan = $status_cuti_keterangan;	
								} 
								
								else if ($status_dinas !='-' )
								{
									 $status                = $this->lang->line('xin_travels');	
									 $status_simbol         = $this->lang->line('xin_travels_simbol');	
									 $attendance_keterangan = $status_dinas_keterangan;
								}

								else if ( $jd == 'Libur' ) 
								{

									$status                = 'Libur';
									$status_simbol         = 'L';	
									$attendance_keterangan = 'Libur';
								
								} 
								else 
								{	

									$status                = $this->lang->line('xin_absent');
									$status_simbol         = $this->lang->line('xin_absent_simbol');
									$attendance_keterangan = $this->lang->line('xin_absent_ket');
								}
								
							}							

						}


					$sql2 ="INSERT INTO xin_attendance_time
								(   
									employee_id,
									employee_pin,				 	
								 	company_id,
								 	location_id,
								 	date_of_joining,
								 	jenis_gaji,
								 	jenis_kerja,
								 	attendance_jadwal,
								 	attendance_date,
								 	clock_in,
								 	clock_out,
								 	time_late,
								 	early_leaving,
								 	overtime,
								 	total_work,
								 	attendance_status,
								 	attendance_status_simbol,
								 	attendance_keterangan,
								 	rekap_date									
											 
								) VALUES 
								(
									'$r->user_id',
									'$r->employee_pin',
									'$r->company_id',
									'$r->location_id',
									'$r->date_of_joining',
									'$jenis_gaji',
									'S',
									'$jd',
								 	'$attendance_date',
								 	'$fclckIn',							 	
								 	'$fclckOut',
								 	'$total_time_l',							 	
								 	'$total_time_e',
								 	'$overtime2',
								 	'$total_work',
								 	'$status',
								 	'$status_simbol',
								 	'$attendance_keterangan',
								 	NOW()

								)";
						
						// print_r($sql2);
						// exit();

						$query2 = $this->db->query($sql2);

					if ($fclckIn =='00:00:00') {
						$jam_masuk = '-';
					} else {
						$jam_masuk = $fclckIn;
					}

					if ($fclckOut =='00:00:00') {
						$jam_pulang = '-';
					} else {
						$jam_pulang = $fclckOut;
					}

					$data[] = array(
						$no,					
						strtoupper($full_name),	
						substr(strtoupper($designation_name),0,30),
						$comp_name,				
						$jd,				
						$d_date,
						$status,
						$jam_masuk,
						$jam_pulang,
						$total_time_l,
						$total_time_e,
						$overtime2,
						$total_work					
					);
					$no++;
				// }
	      	}
	      
		    $output = array(
			   "draw" => $draw,
				 "recordsTotal" => $employee->num_rows(),
				 "recordsFiltered" => $employee->num_rows(),
				 "data" => $data
			);
		    echo json_encode($output);
		    exit();
	    }	 	  
    	    
    // =============================================================================
	// 0930 REKAP ABSENSI REGULER
	// =============================================================================

		// daily attendance > timesheet
		public function attendance_rekap()
	    {
			$session = $this->session->userdata('username');
			if(empty($session)){ 
				redirect('admin/');
			}
			$data['title']       = 'Rekap Absensi | '.$this->Core_model->site_title();
			$data['icon']        = '<i class="fa fa-magic"></i>';
			$data['breadcrumbs'] = 'Rekap Absensi (<blink class ="blink blink-one merah">MASIH DALAM PROSES PEKERJAAN</blink>)';
			$data['path_url']    = 'attendance_rekap';
			
			$data['get_all_companies']    = $this->Company_model->get_company();
			$data['all_office_shifts']    = $this->Location_model->all_payroll_jenis();
			$data['all_office_pola']    = $this->Location_model->all_payroll_pola();
			$data['all_bulan_gaji']       = $this->Core_model->all_bulan_gaji();
			
			$role_resources_ids        = $this->Core_model->user_role_resource();
			
			if(in_array('0930',$role_resources_ids)) {
				if(!empty($session)){
				$data['subview'] = $this->load->view("admin/timesheet/attendance_rekap_list", $data, TRUE);
				$this->load->view('admin/layout/layout_main', $data); //page load
				} else {
					redirect('admin/dashboard/');
				}	
			} else {
				redirect('admin/dashboard');
			}	  
	    }	  

	    public function attendance_rekap_proses() 
	    {
		
			/* Define return | here result is used to return user data and error for error message */
			$Return = array('result'=>'', 'error'=>'', 'csrf_hash'=>'');
			$Return['csrf_hash'] = $this->security->get_csrf_hash();
				
			if($this->input->post('add_type')=='rekap') {			
				
				$company_id         = $this->input->post("company_id");
				$jenis_gaji         = $this->input->post("jenis_gaji");
				$pola_kerja         = $this->input->post("pola_kerja");
				$month_year   		= $this->input->post("month_year");

				// echo "<pre>";
				// print_r($this->db->last_query());
				// print_r( $company_id );
				// print_r( $bulan_id );
				// echo "</pre>";
				// die();			
							
				if($company_id!=0 ) {	
					$cek_karyawan = $this->Employees_model->get_attendance_jenis_gaji_employees_reguler_recap($jenis_gaji,$pola_kerja,$company_id);
					$tampilkan_karyawan = $cek_karyawan->result();					
				} else {
					$Return['error'] = $this->lang->line('xin_record_not_found');
				}
				
			    				
				foreach($tampilkan_karyawan as $r) {

			    	$sql1 ="DELETE FROM xin_attendance_time_rekap WHERE 1=1 AND employee_id ='".$r->user_id."' AND  month_year = '".$month_year."'  ";
					// print_r($sql1);
					// exit();
					$query1   = $this->db->query($sql1);
					  		
					// ==================================================================================================================
					// Tanggal
					// ==================================================================================================================
						$tanggal = $this->Timesheet_model->read_tanggal_information($month_year);
						if(!is_null($tanggal)){
							$start_att = date("d-m-Y", strtotime($tanggal[0]->start_date));
							$end_att   = date("d-m-Y", strtotime($tanggal[0]->end_date));

							$start_date    = new DateTime($tanggal[0]->start_date);
							$end_date      = new DateTime($tanggal[0]->end_date);
							$interval_date = $end_date->diff($start_date);

							$bulan   = $tanggal[0]->bulan;


						} else {
							$start_att = '';	
							$end_att = '';	

							$start_date    = '';
							$end_date      = '';
							$interval_date = '';

							$bulan   = '';
						}		

						$tanggal1 = date("Y-m-d",strtotime($start_att));
						$tanggal2 = date("Y-m-d",strtotime($end_att));
								
					// ==================================================================================================================
					// Rekap Kehadiran
					// ==================================================================================================================

						$xin_tanggal   = $this->Timesheet_model->get_xin_tanggal($month_year);
						foreach($xin_tanggal as $t):  
						    
						    $tgl = '$T'.$t->tgl;

						    $attendance_date = $t->tanggal;
						   
						    $cek_status      = $this->Timesheet_model->cek_status_kehadiran($r->user_id,$attendance_date);
					        
					        if(!is_null($cek_status)){	

					        	if ($cek_status[0]->attendance_status_simbol == 'H' ) {						        	
					        		$tgl = '.';						        	
					        	} else {
					        		if ($r->flag == 0) {
					        		
					        			$tgl = $cek_status[0]->attendance_status_simbol;						        			
					        		
					        		} else if ($r->flag == 1) {

					        			if ($cek_status[0]->attendance_status_simbol == 'L'){
					        				$tgl = 'L';
					        			} else {
					        				$tgl = '.';
					        			}

					        		}						        		
					        	}									
							
							} else {
								$tgl = 'x';	
							}

							if ($t->tgl == 27) {
								$T_27 = $tgl;

							} else if ($t->tgl == 28) {
								$T_28 = $tgl;
							
							} else if ($t->tgl == 29) {
								$T_29 = $tgl;

							} else if ($t->tgl == 30) {
								$T_30 = $tgl;
							
							} else if ($t->tgl == 31) {
								$T_31 = $tgl;

							} else if ($t->tgl == 1) {
								$T_1 = $tgl;
							
							} else if ($t->tgl == 2) {
								$T_2 = $tgl;

							} else if ($t->tgl == 3) {
								$T_3 = $tgl;
							
							} else if ($t->tgl == 4) {
								$T_4 = $tgl;

							} else if ($t->tgl == 5) {
								$T_5 = $tgl;
							
							} else if ($t->tgl == 6) {
								$T_6 = $tgl;

							} else if ($t->tgl == 7) {
								$T_7 = $tgl;
							
							} else if ($t->tgl == 8) {
								$T_8 = $tgl;

							} else if ($t->tgl == 9) {
								$T_9 = $tgl;
							
							} else if ($t->tgl == 10) {
								$T_10 = $tgl;
						
							} else if ($t->tgl == 11) {
								$T_11 = $tgl;
							
							} else if ($t->tgl == 12) {
								$T_12 = $tgl;

							} else if ($t->tgl == 13) {
								$T_13 = $tgl;
							
							} else if ($t->tgl == 14) {
								$T_14 = $tgl;

							} else if ($t->tgl == 15) {
								$T_15 = $tgl;
							
							} else if ($t->tgl == 16) {
								$T_16 = $tgl;

							} else if ($t->tgl == 17) {
								$T_17 = $tgl;
							
							} else if ($t->tgl == 18) {
								$T_18 = $tgl;

							} else if ($t->tgl == 19) {
								$T_19 = $tgl;
							
							} else if ($t->tgl == 20) {
								$T_20 = $tgl;

							} else if ($t->tgl == 21) {
								$T_21 = $tgl;
						
							} else if ($t->tgl == 22) {
								$T_22 = $tgl;

							} else if ($t->tgl == 23) {
								$T_23 = $tgl;
							
							} else if ($t->tgl == 24) {
								$T_24 = $tgl;

							} else if ($t->tgl == 25) {
								$T_25 = $tgl;
							
							} else if ($t->tgl == 26) {
								$T_26 = $tgl;
							}
							

						endforeach;						
						
						
						// echo "<pre>";
						// print_r( $T_27 );							
						// echo "</pre>";
						// die();

					// ==================================================================================================================
					// Rekap Pengajuan
					// ==================================================================================================================
						
						// -------------------------------------------------------------------------------------------------------------
						// LIBUR
						// -------------------------------------------------------------------------------------------------------------
							$cek_libur = $this->Timesheet_model->hitung_jumlah_status_kehadiran($r->user_id,$tanggal1,$tanggal2,'L');
							$jumlah_libur = $cek_libur[0]->jumlah;

						// -------------------------------------------------------------------------------------------------------------
						// HADIR
						// -------------------------------------------------------------------------------------------------------------
							$cek_hadir = $this->Timesheet_model->hitung_jumlah_status_kehadiran($r->user_id,$tanggal1,$tanggal2,'H');
							if ($r->flag == 0) {
								$jumlah_hadir = $cek_hadir[0]->jumlah;
							} else {
								$jumlah_hadir = $interval_date->d-$jumlah_libur;
							}

						// -------------------------------------------------------------------------------------------------------------
						// SAKIT
						// -------------------------------------------------------------------------------------------------------------
							$cek_sakit = $this->Timesheet_model->hitung_jumlah_status_kehadiran($r->user_id,$tanggal1,$tanggal2,'S');
							$jumlah_sakit = $cek_sakit[0]->jumlah;

						// -------------------------------------------------------------------------------------------------------------
						// IZIN
						// -------------------------------------------------------------------------------------------------------------
							$cek_izin = $this->Timesheet_model->hitung_jumlah_status_kehadiran($r->user_id,$tanggal1,$tanggal2,'I');
							$jumlah_izin = $cek_izin[0]->jumlah;
						
						// -------------------------------------------------------------------------------------------------------------
						// CUTI
						// -------------------------------------------------------------------------------------------------------------
							$cek_cuti = $this->Timesheet_model->hitung_jumlah_status_kehadiran($r->user_id,$tanggal1,$tanggal2,'C');
							$jumlah_cuti = $cek_cuti[0]->jumlah;

						// -------------------------------------------------------------------------------------------------------------
						// ALPA
						// -------------------------------------------------------------------------------------------------------------

							$cek_alpa = $this->Timesheet_model->hitung_jumlah_status_kehadiran($r->user_id,$tanggal1,$tanggal2,'A');
							
							if ($r->flag == 0) {
								$jumlah_alpa = $cek_alpa[0]->jumlah;
							} else {
								$jumlah_alpa = 0;
							}
						
						// -------------------------------------------------------------------------------------------------------------
						// DINAS
						// -------------------------------------------------------------------------------------------------------------
							$cek_dinas = $this->Timesheet_model->hitung_jumlah_status_kehadiran($r->user_id,$tanggal1,$tanggal2,'D');
							$jumlah_dinas = $cek_dinas[0]->jumlah;

						// -------------------------------------------------------------------------------------------------------------
						// LEMBUR
						// -------------------------------------------------------------------------------------------------------------
							$cek_lembur = $this->Timesheet_model->hitung_jumlah_status_kehadiran($r->user_id,$tanggal1,$tanggal2,'O');
							$jumlah_lembur = $cek_lembur[0]->jumlah;
							
						// -------------------------------------------------------------------------------------------------------------
						// TERLAMBAT
						// -------------------------------------------------------------------------------------------------------------
							$cek_terlambat = $this->Timesheet_model->hitung_jumlah_terlambat_kehadiran($r->user_id,$tanggal1,$tanggal2);
							$jumlah_terlambat= $cek_terlambat[0]->jumlah;

							// -------------------------------------------------------------------------------------------------------------
							// MENIT
							// -------------------------------------------------------------------------------------------------------------
							$jumlah_terlambat_menit = $jumlah_terlambat;
							// -------------------------------------------------------------------------------------------------------------
							// JAM
							// -------------------------------------------------------------------------------------------------------------
							$jumlah_terlambat_jam = round($jumlah_terlambat/60,2);

						// -------------------------------------------------------------------------------------------------------------
						// TOTAL
						// -------------------------------------------------------------------------------------------------------------
							$jumlah_total=0;

							$jumlah_total= $jumlah_hadir + $jumlah_libur + $jumlah_sakit + $jumlah_izin + $jumlah_cuti + $jumlah_alpa + $jumlah_dinas + $jumlah_lembur;
						
					// ==================================================================================================================
					// Simpan Rekap
					// ==================================================================================================================
						
						// $data_payroll = array(
						// 	'is_payroll' => 1									
						// );
						
						// $this->Timesheet_model->update_bulan_gaji($data_payroll,$month_year);	

						$session_id = $this->session->userdata('user_id');
			   			$user_create = $session_id['user_id'];

												
						$data = array(
								
								'employee_id'     => $r->user_id,									
								'wages_type'      => $r->wages_type,
								'company_id'      => $r->company_id,
								'office_id'	      => $r->office_id,
								'is_active'       => $r->is_active,
								'date_of_joining' => $r->date_of_joining,
								'department_id'   => $r->department_id,
								'designation_id'  => $r->designation_id,
								'month_year'      => $month_year,
								'bulan'           => $bulan,

								'tanggal_27'      => $T_27,
								'tanggal_28'      => $T_28,
								'tanggal_29'      => $T_29,
								'tanggal_30'      => $T_30,
								'tanggal_31'      => $T_31,

								'tanggal_1'       => $T_1,
								'tanggal_2'       => $T_2,
								'tanggal_3'       => $T_3,
								'tanggal_4'       => $T_4,
								'tanggal_5'       => $T_5,
								'tanggal_6'       => $T_6,
								'tanggal_7'       => $T_7,
								'tanggal_8'       => $T_8,
								'tanggal_9'       => $T_9,
								'tanggal_10'      => $T_10,

								'tanggal_11'      => $T_11,
								'tanggal_12'      => $T_12,
								'tanggal_13'      => $T_13,
								'tanggal_14'      => $T_14,
								'tanggal_15'      => $T_15,
								'tanggal_16'      => $T_16,
								'tanggal_17'      => $T_17,
								'tanggal_18'      => $T_18,
								'tanggal_19'      => $T_19,
								'tanggal_20'      => $T_20,

								'tanggal_21'      => $T_21,
								'tanggal_22'      => $T_22,
								'tanggal_23'      => $T_23,
								'tanggal_24'      => $T_24,
								'tanggal_25'      => $T_25,
								'tanggal_26'      => $T_26,								
								
								'libur'     	  => $jumlah_libur,
								'aktif'     	  => $jumlah_hadir,
								'sakit'           => $jumlah_sakit,
								'izin'            => $jumlah_izin,
								'cuti'            => $jumlah_cuti,
								'alpa'            => $jumlah_alpa,								
								'dinas'           => $jumlah_dinas,
								'terlambat_menit' => $jumlah_terlambat_menit,
								'terlambat_jam'   => $jumlah_terlambat_jam,
								'total'           => $jumlah_total,

								'create_date'     => date('Y-m-d h:i:s'),
								'create_by'       => $user_create,									 	
									 	
							);

						$result = $this->Timesheet_model->add_employee_attendance_rekap_reguler($data);

						// echo "<pre>";
						// print_r($result);
						// echo "</pre>";
						// die();	
								
						if ($result) {						 
							
							$Return['result'] = 'Rekap Kehadiran Berhasil Disimpan';

						} else {
							$Return['error'] = $this->lang->line('xin_error_msg');
						}	
					
				}

				$this->output($Return);
				exit;				
			} 
		}

	     // daily attendance list > timesheet
	    public function attendance_rekap_list()
	    {

			$data['title'] = $this->Core_model->site_title();
			$session       = $this->session->userdata('username');
			$user_info     = $this->Core_model->read_user_info($session['user_id']);

			if(!empty($session)){ 
				$this->load->view("admin/timesheet/attendance_rekap_list", $data);
			} else {
				redirect('admin/');
			}

			// Datatables Variables
			$draw               = intval($this->input->get("draw"));
			$start              = intval($this->input->get("start"));
			$length             = intval($this->input->get("length"));
			$role_resources_ids = $this->Core_model->user_role_resource();

			$company_id         = $this->input->get("company_id");
			$jenis_gaji         = $this->input->get("jenis_gaji");			
			$pola_kerja   		= $this->input->get("pola_kerja");
			$month_year   		= $this->input->get("month_year");
		
		    // ===============================================================================================================
		    // Tampilkan
		    // ===============================================================================================================
			$employee = $this->Employees_model->get_rekap_proses($company_id,$jenis_gaji,$month_year,$pola_kerja);	
			
			$data = array();

			$no = 1;
			
	        foreach($employee->result() as $r) {

	        	if ($r->bulan == 1 || $r->bulan == 2 ) { // 31
	        		
	        		$info_T29 = $r->T29;
	        		$info_T30 = $r->T30;
	        		$info_T31 = $r->T31;

	        	} else 	if ($r->bulan == 3) { // 28

	        		$info_T29 = '-';
	        		$info_T30 = '-';
	        		$info_T31 = '-';

	        	} else 	if ($r->bulan == 4) { // 31

	        		$info_T29 = $r->T29;
	        		$info_T30 = $r->T30;
	        		$info_T31 = $r->T31;

	        	} else 	if ($r->bulan == 5) {

	        		$info_T29 = '-';
	        		$info_T30 = '-';
	        		$info_T31 = '-';

	        	} else 	if ($r->bulan == 6) {

	        		$info_T29 = $r->T29;
	        		$info_T30 = $r->T30;
	        		$info_T31 = $r->T31;

	        	} else 	if ($r->bulan == 7) {

	        		$info_T29 = $r->T29;
	        		$info_T30 = $r->T30;
	        		$info_T31 = $r->T31;	        		

	        	} else {

	        		$info_T29 = '-';
	        		$info_T30 = '-';
	        		$info_T31 = '-';

	        	}

    	    	$data[] = array(
					$no,
					strtoupper($r->full_name),	
					
					$r->T27,
				 	$r->T28,

				 	$info_T29,
				 	$info_T30,
				 	$info_T31,						 	

				 	$r->T01,
				 	$r->T02,
				 	$r->T03,
				 	$r->T04,
				 	$r->T05,
				 	$r->T06,	
				 	$r->T07,
				 	$r->T08,
				 	$r->T09,
				 	$r->T10,

				 	$r->T11,
				 	$r->T12,
				 	$r->T13,
				 	$r->T14,
				 	$r->T15,
				 	$r->T16,
				 	$r->T17,
				 	$r->T18,
				 	$r->T19,
				 	$r->T20,

				 	$r->T21,
				 	$r->T22,
				 	$r->T23,
				 	$r->T24,
				 	$r->T25,
				 	$r->T26,	

				 	$r->libur,
				 	$r->aktif,
				 	$r->sakit,
				 	$r->izin,
				 	$r->cuti,
				 	$r->alpa,
				 	$r->dinas,
				 	$r->terlambat_menit,
				 	$r->terlambat_jam,
				 	$r->total
				);
				$no++;
				
	      	}
	      
		    $output = array(
			    "draw"            => $draw,
				"recordsTotal"    => $employee->num_rows(),
				"recordsFiltered" => $employee->num_rows(),
				"data" => $data
			);

		    echo json_encode($output);
		    exit();
	    }
    // =============================================================================
	// TAMPILKAN
	// =============================================================================
	
		// get company > employees
		public function get_employees() 
		{

			$data['title'] = $this->Core_model->site_title();
			$id = $this->uri->segment(4);
			
			$data = array(
				'company_id' => $id
				);
			$session = $this->session->userdata('username');
			if(!empty($session)){ 
				$this->load->view("admin/timesheet/get_employees", $data);
			} else {
				redirect('admin/');
			}
			// Datatables Variables
			$draw = intval($this->input->get("draw"));
			$start = intval($this->input->get("start"));
			$length = intval($this->input->get("length"));
		}

		// get company > employees
		public function get_employees_office() 
		{

			$data['title'] = $this->Core_model->site_title();
			$id = $this->uri->segment(4);
			
			$data = array(
				'company_id' => $id
				);
			$session = $this->session->userdata('username');
			if(!empty($session)){ 
				$this->load->view("admin/timesheet/get_employees_office", $data);
			} else {
				redirect('admin/');
			}
			// Datatables Variables
			$draw = intval($this->input->get("draw"));
			$start = intval($this->input->get("start"));
			$length = intval($this->input->get("length"));
		} 
		

		// get company > projects
		public function get_company_project() 
		{

			$data['title'] = $this->Core_model->site_title();
			$id = $this->uri->segment(4);
			
			$data = array(
				'company_id' => $id
				);
			$session = $this->session->userdata('username');
			if(!empty($session)){ 
				$this->load->view("admin/timesheet/tasks/get_company_project", $data);
			} else {
				redirect('admin/');
			}
			// Datatables Variables
			$draw = intval($this->input->get("draw"));
			$start = intval($this->input->get("start"));
			$length = intval($this->input->get("length"));
		}
		
		// get company > employees
		public function get_company_employees() 
		{

			$data['title'] = $this->Core_model->site_title();
			$id = $this->uri->segment(4);
			
			$data = array(
				'company_id' => $id
				);
			$session = $this->session->userdata('username');
			if(!empty($session)){ 
				$this->load->view("admin/timesheet/tasks/get_employees", $data);
			} else {
				redirect('admin/');
			}
			// Datatables Variables
			$draw = intval($this->input->get("draw"));
			$start = intval($this->input->get("start"));
			$length = intval($this->input->get("length"));
		}
				
		// daily attendance list > timesheet
	    public function dtwise_attendance_list()
	    {

			$data['title'] = $this->Core_model->site_title();
			$session = $this->session->userdata('username');
			if(!empty($session)){ 
				$this->load->view("admin/timesheet/attendance_list", $data);
			} else {
				redirect('admin/');
			}
			// Datatables Variables
			$draw = intval($this->input->get("draw"));
			$start = intval($this->input->get("start"));
			$length = intval($this->input->get("length"));
			
			
			$employee = $this->Core_model->read_user_attendance_info();
			
			$data = array();

	        foreach($employee->result() as $r) {
				$data[] = array('','','','','','','','','','','');
			}

		  	$output = array(
			   "draw" => $draw,
				 "recordsTotal" => $employee->num_rows(),
				 "recordsFiltered" => $employee->num_rows(),
				 "data" => $data
			);
			echo json_encode($output);
		  	exit();
	    }	
		
		// date wise attendance list > timesheet
	    public function date_wise_list()
	    {

			$data['title'] = $this->Core_model->site_title();
			$session = $this->session->userdata('username');
			$user_info = $this->Core_model->read_user_info($session['user_id']);
			if(!empty($session)){ 
				$this->load->view("admin/timesheet/date_wise", $data);
			} else {
				redirect('admin/');
			}
			// Datatables Variables
			$draw = intval($this->input->get("draw"));
			$start = intval($this->input->get("start"));
			$length = intval($this->input->get("length"));
			
			$role_resources_ids = $this->Core_model->user_role_resource(); 
			
			$employee_id = $this->input->get("user_id");		
			
			$system = $this->Core_model->read_setting_info(1);
			$employee = $this->Core_model->read_user_info($employee_id);
			
			$start_date = new DateTime( $this->input->get("start_date"));
			$end_date = new DateTime( $this->input->get("end_date") );
			$end_date = $end_date->modify( '+1 day' ); 
			
			$interval_re = new DateInterval('P1D');
			$date_range = new DatePeriod($start_date, $interval_re ,$end_date);
			$attendance_arr = array();
			
			$data = array();
			foreach($date_range as $date) {
				$attendance_date =  $date->format("Y-m-d");
		      
				$get_day = strtotime($attendance_date);
				$day = date('l', $get_day);
				
				// office shift
				$office_shift = $this->Timesheet_model->read_office_shift_information($employee[0]->office_shift_id);
			
				// get clock in/clock out of each employee
				if($day == 'Monday') {
					if( $monday_in_time ==''){
						$in_time = '00:00:00';
						$out_time = '00:00:00';
					} else {
						$in_time = $office_shift[0]->monday_in_time;
						$out_time = $office_shift[0]->monday_out_time;
					}
				} else if($day == 'Tuesday') {
					if($office_shift[0]->tuesday_in_time==''){
						$in_time = '00:00:00';
						$out_time = '00:00:00';
					} else {
						$in_time = $office_shift[0]->tuesday_in_time;
						$out_time = $office_shift[0]->tuesday_out_time;
					}
				} else if($day == 'Wednesday') {
					if($office_shift[0]->wednesday_in_time==''){
						$in_time = '00:00:00';
						$out_time = '00:00:00';
					} else {
						$in_time = $office_shift[0]->wednesday_in_time;
						$out_time = $office_shift[0]->wednesday_out_time;
					}
				} else if($day == 'Thursday') {
					if($office_shift[0]->thursday_in_time==''){
						$in_time = '00:00:00';
						$out_time = '00:00:00';
					} else {
						$in_time = $office_shift[0]->thursday_in_time;
						$out_time = $office_shift[0]->thursday_out_time;
					}
				} else if($day == 'Friday') {
					if($office_shift[0]->friday_in_time==''){
						$in_time = '00:00:00';
						$out_time = '00:00:00';
					} else {
						$in_time = $office_shift[0]->friday_in_time;
						$out_time = $office_shift[0]->friday_out_time;
					}
				} else if($day == 'Saturday') {
					if($office_shift[0]->saturday_in_time==''){
						$in_time = '00:00:00';
						$out_time = '00:00:00';
					} else {
						$in_time = $office_shift[0]->saturday_in_time;
						$out_time = $office_shift[0]->saturday_out_time;
					}
				} else if($day == 'Sunday') {
					if($office_shift[0]->sunday_in_time==''){
						$in_time = '00:00:00';
						$out_time = '00:00:00';
					} else {
						$in_time = $office_shift[0]->sunday_in_time;
						$out_time = $office_shift[0]->sunday_out_time;
					}
				}
			// check if clock-in for date
			$attendance_status = '';
			$check = $this->Timesheet_model->attendance_first_in_check($employee[0]->user_id,$attendance_date);		
			if($check->num_rows() > 0){
				// check clock in time
				$attendance = $this->Timesheet_model->attendance_first_in($employee[0]->user_id,$attendance_date);
				
				// clock in
				$clock_in = new DateTime($attendance[0]->clock_in);
				$clock_in2 = $clock_in->format('H:i:s');
				
				$clkInIp = $clock_in2;
						
				$office_time =  new DateTime($in_time.' '.$attendance_date);
				//time diff > total time late
				$office_time_new = strtotime($in_time.' '.$attendance_date);
				$clock_in_time_new = strtotime($attendance[0]->clock_in);
				if($clock_in_time_new <= $office_time_new) {
					$total_time_l = '-';
				} else {
					$interval_late = $clock_in->diff($office_time);
					$hours_l   = $interval_late->format('%h');
					$minutes_l = $interval_late->format('%i');			
					$total_time_l = $hours_l ."j ".$minutes_l ."m ";
				}
				
				// total hours work/ed
				$total_hrs = $this->Timesheet_model->total_hours_worked_attendance($employee[0]->user_id,$attendance_date);
				$hrs_old_int1 = 0;
				$Total = '';
				$Trest = '';
				$hrs_old_seconds = 0;
				$hrs_old_seconds_rs = 0;
				$total_time_rs = '';
				$hrs_old_int_res1 = 0;
				foreach ($total_hrs->result() as $hour_work){		
					// total work			
					$timee = $hour_work->total_work.':00';
					$str_time =$timee;
		
					$str_time = preg_replace("/^([\d]{1,2})\:([\d]{2})$/", "00:$1:$2", $str_time);
					
					sscanf($str_time, "%d:%d:%d", $hours, $minutes, $seconds);
					
					$hrs_old_seconds = $hours * 3600 + $minutes * 60 + $seconds;
					
					$hrs_old_int1 += $hrs_old_seconds;
					
					$Total = gmdate("H:i", $hrs_old_int1);	
				}
				if($Total=='') {
					$total_work = '-';
				} else {
					$total_work = $Total;
				}
				
				// total rest > 
				$total_rest = $this->Timesheet_model->total_rest_attendance($employee[0]->user_id,$attendance_date);
				foreach ($total_rest->result() as $rest){			
					// total rest
					$str_time_rs = $rest->total_rest.':00';
					//$str_time_rs =$timee_rs;
		
					$str_time_rs = preg_replace("/^([\d]{1,2})\:([\d]{2})$/", "00:$1:$2", $str_time_rs);
					
					sscanf($str_time_rs, "%d:%d:%d", $hours_rs, $minutes_rs, $seconds_rs);
					
					$hrs_old_seconds_rs = $hours_rs * 3600 + $minutes_rs * 60 + $seconds_rs;
					
					$hrs_old_int_res1 += $hrs_old_seconds_rs;
					
					$total_time_rs = gmdate("H:i", $hrs_old_int_res1);
				}
				
				// check attendance status
				$status = $attendance[0]->attendance_status;
				if($total_time_rs=='') {
					$Trest = '00:00:00';
				} else {
					$Trest = $total_time_rs;
				}
			
			} else {
				$clock_in2 = '00:00:00';
				$total_time_l = '-';
				$total_work = '-';
				$Trest = '00:00:00';
				$clkInIp = $clock_in2;
				// get holiday/leave or absent
				/* attendance status */
				// get holiday
				$h_date_chck = $this->Timesheet_model->holiday_date_check($attendance_date);
				$holiday_arr = array();
				if($h_date_chck->num_rows() == 1){
					$h_date = $this->Timesheet_model->holiday_date($attendance_date);
					$begin = new DateTime( $h_date[0]->start_date );
					$end = new DateTime( $h_date[0]->end_date);
					$end = $end->modify( '+1 day' ); 
					
					$interval = new DateInterval('P1D');
					$daterange = new DatePeriod($begin, $interval ,$end);
					
					foreach($daterange as $date){
						$holiday_arr[] =  $date->format("Y-m-d");
					}
				} else {
					$holiday_arr[] = '99-99-99';
				}
				
				
				// get leave/employee
				$leave_date_chck = $this->Timesheet_model->leave_date_check($employee[0]->user_id,$attendance_date);
				$leave_arr = array();
				if($leave_date_chck->num_rows() == 1){
					$leave_date = $this->Timesheet_model->leave_date($employee[0]->user_id,$attendance_date);
					$begin1 = new DateTime( $leave_date[0]->from_date );
					$end1 = new DateTime( $leave_date[0]->to_date);
					$end1 = $end1->modify( '+1 day' ); 
					
					$interval1 = new DateInterval('P1D');
					$daterange1 = new DatePeriod($begin1, $interval1 ,$end1);
					
					foreach($daterange1 as $date1){
						$leave_arr[] =  $date1->format("Y-m-d");
					}	
				} else {
					$leave_arr[] = '99-99-99';
				}
					
				if($office_shift[0]->monday_in_time == '' && $day == 'Monday') {
					$status = $this->lang->line('xin_holiday');	
				} else if($office_shift[0]->tuesday_in_time == '' && $day == 'Tuesday') {
					$status = $this->lang->line('xin_holiday');	
				} else if($office_shift[0]->wednesday_in_time == '' && $day == 'Wednesday') {
					$status = $this->lang->line('xin_holiday');	
				} else if($office_shift[0]->thursday_in_time == '' && $day == 'Thursday') {
					$status = $this->lang->line('xin_holiday');	
				} else if($office_shift[0]->friday_in_time == '' && $day == 'Friday') {
					$status = $this->lang->line('xin_holiday');	
				} else if($office_shift[0]->saturday_in_time == '' && $day == 'Saturday') {
					$status = $this->lang->line('xin_holiday');	
				} else if($office_shift[0]->sunday_in_time == '' && $day == 'Sunday') {
					$status = $this->lang->line('xin_holiday');	
				} else if(in_array($attendance_date,$holiday_arr)) { // holiday
					$status = $this->lang->line('xin_holiday');
				} else if(in_array($attendance_date,$leave_arr)) { // on leave
					$status = $this->lang->line('xin_on_leave');
				} 
				else {
					$status = $this->lang->line('xin_absent');
				}
			}
			
			
			
			// check if clock-out for date
			$check_out = $this->Timesheet_model->attendance_first_out_check($employee[0]->user_id,$attendance_date);		
			if($check_out->num_rows() == 1){
				/* early time */
				$early_time =  new DateTime($out_time.' '.$attendance_date);
				// check clock in time
				$first_out = $this->Timesheet_model->attendance_first_out($employee[0]->user_id,$attendance_date);
				// clock out
				$clock_out = new DateTime($first_out[0]->clock_out);
				
				if ($first_out[0]->clock_out!='') {
					$clock_out2 = $clock_out->format('H:i:s');
					
					// early leaving
					$early_new_time = strtotime($out_time.' '.$attendance_date);
					$clock_out_time_new = strtotime($first_out[0]->clock_out);
				
					if($early_new_time <= $clock_out_time_new) {
						$total_time_e = '-';
					} else {			
						$interval_lateo = $clock_out->diff($early_time);
						$hours_e   = $interval_lateo->format('%h');
						$minutes_e = $interval_lateo->format('%i');			
						$total_time_e = $hours_e ."j ".$minutes_e ."m ";
					}
					
					/* over time */
					$over_time =  new DateTime($out_time.' '.$attendance_date);
					$overtime2 = $over_time->format('H:i:s');
					// over time
					$over_time_new = strtotime($out_time.' '.$attendance_date);
					$clock_out_time_new1 = strtotime($first_out[0]->clock_out);
					
					if($clock_out_time_new1 <= $over_time_new) {
						$overtime2 = '-';
					} else {			
						$interval_lateov = $clock_out->diff($over_time);
						$hours_ov   = $interval_lateov->format('%h');
						$minutes_ov = $interval_lateov->format('%i');			
						$overtime2 = $hours_ov ."j ".$minutes_ov ."m ";
					}				
					
				} else {
					$clock_out2 =  '00:00:00';
					$total_time_e = '-';
					$overtime2 = '-';
				
				}
						
			} else {
				$clock_out2 =  '00:00:00';
				$total_time_e = '-';
				$overtime2 = '-';
				
			}		
			// user full name
				$full_name = $employee[0]->first_name.' '.$employee[0]->last_name;
				// get company
				$company = $this->Core_model->read_company_info($employee[0]->company_id);
				if(!is_null($company)){
					$comp_name = $company[0]->name;
				} else {
					$comp_name = '--';	
				}	
				// attendance date
				$tdate = $this->Core_model->set_date_format($attendance_date);
				
					$fclckIn = $clock_in2;
					$fclckOut = $clock_out2;
				
				$data[] = array(
					$full_name,
					$employee[0]->employee_id,
					$comp_name,
					$status,
					$tdate,
					$fclckIn,
					$fclckOut,
					$total_time_l,
					$total_time_e,
					$overtime2,
					$total_work,
					$Trest
				);
			
			
	      }

		  $output = array(
			   "draw" => $draw,
				 //"recordsTotal" => count($date_range),
				 //"recordsFiltered" => count($date_range),
				 "data" => $data
			);
		  echo json_encode($output);
		  exit();
	    }	

		// get record of leave by id > modal
		public function read_variation_record()
		{
			$data['title'] = $this->Core_model->site_title();
			$variation_id = $this->input->get('variation_id');
			$result = $this->Timesheet_model->read_variation_information($variation_id);
			
			$data = array(
					'variation_id' => $result[0]->variation_id,
					'project_id' => $result[0]->project_id,
					'company_id' => $result[0]->company_id,
					'client_approval' => $result[0]->client_approval,
					'created_by' => $result[0]->created_by,
					'variation_name' => $result[0]->variation_name,
					'assigned_to' => $result[0]->assigned_to,
					'start_date' => $result[0]->start_date,
					'end_date' => $result[0]->end_date,
					'variation_hours' => $result[0]->variation_hours,
					'variation_status' => $result[0]->variation_status,
					'variation_no' => $result[0]->variation_no,
					'description' => $result[0]->description,
					'created_at' => $result[0]->created_at,
					'all_employees' => $this->Core_model->all_employees()
					);
			$session = $this->session->userdata('username');
			if(!empty($session)){ 
				$this->load->view('admin/timesheet/tasks/dialog_task', $data);
			} else {
				redirect('admin/');
			}
		}
			
		
		// get record of attendance
		public function read()
		{
			$data['title'] = $this->Core_model->site_title();
			$attendance_id = $this->input->get('attendance_id');
			$result = $this->Timesheet_model->read_attendance_information($attendance_id);
			$user = $this->Core_model->read_user_info($result[0]->employee_id);
			// user full name
			$full_name = $user[0]->first_name.' '.$user[0]->last_name;
			
			$in_time = new DateTime($result[0]->clock_in);
			$out_time = new DateTime($result[0]->clock_out);
			
			$clock_in = $in_time->format('H:i:s');
			if($result[0]->clock_out == '') {
				$clock_out = '';
			} else {
				$clock_out = $out_time->format('H:i:s');
			}
			
			$data = array(
					'time_attendance_id' => $result[0]->time_attendance_id,
					'employee_id' => $result[0]->employee_id,
					'full_name' => $full_name,
					'attendance_date' => $result[0]->attendance_date,
					'clock_in' => $clock_in,
					'clock_out' => $clock_out
					);
			$session = $this->session->userdata('username');
			if(!empty($session)){ 
				$this->load->view('admin/timesheet/dialog_attendance', $data);
			} else {
				redirect('admin/');
			}
		}
		
	// get record of holiday

	
	
	//read_map_info
	public function read_map_info()
	{
		$data['title'] = $this->Core_model->site_title();
		//$office_shift_id = $this->input->get('office_shift_id');
		//$result = $this->Timesheet_model->read_office_shift_information($office_shift_id);
		
		$data = array(
			//	'office_shift_id' => $result[0]->office_shift_id,
				//'company_id' => $result[0]->company_id
				);
		$session = $this->session->userdata('username');
		if(!empty($session)){ 
			$this->load->view('admin/timesheet/dialog_read_map', $data);
		} else {
			redirect('admin/');
		}
	}
	
	
	// Validate and update info in database
	public function default_shift() {
	
		if($this->input->get('office_shift_id')) {
			
		$id = $this->input->get('office_shift_id');
		
		/* Define return | here result is used to return user data and error for error message */
		$Return = array('result'=>'', 'error'=>'', 'csrf_hash'=>'');
		$Return['csrf_hash'] = $this->security->get_csrf_hash();
			
		$data = array(
		'default_shift' => '0'
		);
		
		$data2 = array(
		'default_shift' => '1'
		);
		
		$result = $this->Timesheet_model->update_default_shift_zero($data);
		$result = $this->Timesheet_model->update_default_shift_record($data2,$id);		
		
		if ($result == TRUE) {
			$Return['result'] = $this->lang->line('xin_success_shift_default_made');
		} else {
			$Return['error'] = $this->lang->line('xin_error_msg');
		}
		$this->output($Return);
		exit;
		}
	}
	
	
	
	// delete attendance record
	public function delete_attendance() {
		if($this->input->post('type')=='delete') {
			// Define return | here result is used to return user data and error for error message 
			$Return = array('result'=>'', 'error'=>'', 'csrf_hash'=>'');
			$id = $this->uri->segment(4);
			$Return['csrf_hash'] = $this->security->get_csrf_hash();
			$result = $this->Timesheet_model->delete_attendance_record($id);
			if(isset($id)) {
				$Return['result'] = $this->lang->line('xin_success_employe_attendance_deleted');
			} else {
				$Return['error'] = $this->lang->line('xin_error_msg');
			}
			$this->output($Return);
		}
	}
	
	
	// delete shift record
	public function delete_shift() {
		if($this->input->post('type')=='delete') {
			// Define return | here result is used to return user data and error for error message 
			$Return = array('result'=>'', 'error'=>'', 'csrf_hash'=>'');
			$id = $this->uri->segment(4);
			$Return['csrf_hash'] = $this->security->get_csrf_hash();
			$result = $this->Timesheet_model->delete_shift_record($id);
			if(isset($id)) {
				$Return['result'] = $this->lang->line('xin_success_shift_deleted');
			} else {
				$Return['error'] = $this->lang->line('xin_error_msg');
			}
			$this->output($Return);
		}
	}
	
	
}
