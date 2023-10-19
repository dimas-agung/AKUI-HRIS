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
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Import extends MY_Controller
{

   /*Function to set JSON output*/
	public function output($Return=array()){
		/*Set response header*/
		header("Access-Control-Allow-Origin: *");
		header("Content-Type: application/json; charset=UTF-8");
		/*Final JSON response*/
		exit(json_encode($Return));
	}
	
	public function __construct()
     {
		parent::__construct();
		
		//load the models
		$this->load->model("Clients_model");
		$this->load->model("Core_model");		
		$this->load->model("Company_model");
		$this->load->model("Payroll_model");
		$this->load->model("Department_model");
		$this->load->model("Designation_model");
		$this->load->model("Employees_model");

		
		$this->load->library("pagination");
		$this->load->library('Pdf');
		$this->load->helper('string');
     }
	 
	// import
	public function index() {
	
		$session = $this->session->userdata('username');
		if(empty($session)){ 
			redirect('admin/');
		}
		$data['title']         = 'Impor Produktifitas | '.$this->Core_model->site_title();
		$data['icon']          = '<i class="fa fa-cloud-download"></i>';
		$data['breadcrumbs']   = 'Impor Produktifitas';
		$data['desc']          = "PROSES : Impor Produktifitas";
		$data['path_url']      = 'hris_import';

		$data['all_companies'] = $this->Company_model->get_company();
		$role_resources_ids    = $this->Core_model->user_role_resource();
		
        // $data['all_companies']       = $this->Company_model->get_company();

        $data['get_all_workstation'] = $this->Company_model->get_workstation();
		if(in_array('01031',$role_resources_ids)) {
			$data['subview'] = $this->load->view("admin/layout/hris_import", $data, TRUE);
			$this->load->view('admin/layout/layout_main', $data); //page load
		} else {
			redirect('admin/dashboard');
		}
	}	 
	


	 // Validate and add info in database
	public function import_gram() {
		
		if($this->input->post('is_ajax')=='3') {	
			/* Define return | here result is used to return user data and error for error message */
			
			$Return = array('result'=>'', 'error'=>'', 'csrf_hash'=>'');

			$Return['csrf_hash'] = $this->security->get_csrf_hash();
			$company_id = $this->input->post('company_id');
			
			//validate whether uploaded file is a csv file
	   		$csvMimes = array('text/x-comma-separated-values', 'text/comma-separated-values', 'application/octet-stream', 'application/vnd.ms-excel', 'application/x-csv', 'text/x-csv', 'text/csv', 'application/csv', 'application/excel', 'application/vnd.msexcel', 'text/plain');
			 
			 // $csvMimes = array('application/vnd.ms-excel','text/plain','text/csv','text/tsv');

			if($_FILES['file']['name']==='') 
			{			
				$Return['error'] = 'Silahkan upload file CSV atau Excel Anda';		
			} 

			else 
			{
				
				if(!empty($_FILES['file']['name']) && in_array($_FILES['file']['type'],$csvMimes))
				{
					
					if(is_uploaded_file($_FILES['file']['tmp_name']))
					{					
						// check file size
						if(filesize($_FILES['file']['tmp_name']) > 2000000) 
						{
							$Return['error'] = $this->lang->line('xin_error_gram_import_size');
						
						} 
						else 
						{
							//open uploaded csv file with read only mode
							$csvFile = fopen($_FILES['file']['tmp_name'], 'r');
							
							//skip first line
							fgetcsv($csvFile);

							//parse data from csv file line by line
							while(($line = fgetcsv($csvFile)) !== FALSE){

								$gram_tanggal = $line[0];
								$employee_id = $line[1];
								$gram_nilai = $line[2];
								$gram_grading = $line[3];
								$gram_no_job = $line[4];
								$insentif = $line[5];
								// $old_date_timestamp = strtotime($gram_tanggal);
								// $gram_tanggal = date('Y-m-d', $old_date_timestamp);  
								// echo $gram_tanggal;return;

								$user_info      = $this->Employees_model->get_employees_by_id($employee_id);	
								if(!is_null($user_info)){
									$company_id = $user_info->company_id;
									$designation_id = $user_info->designation_id;
									$workstation_id = $user_info->workstation_id;
									
								} else {		
									$company_id = '';
									$designation_id = '';
									$workstation_id = '';
								}

								$sql1 ="DELETE FROM xin_workstation_gram WHERE 1=1
								        AND gram_tanggal = '".$gram_tanggal."' 
								        AND employee_id  = '".$line[1]."'								        
								        AND gram_no_job  = '".$line[4]."'
								        AND company_id  = '".$company_id."'
								";
								// print_r($sql1);
								// exit();
								$query1   = $this->db->query($sql1);
								
								$data = array(

									'gram_tanggal' => $gram_tanggal,
									'employee_id'  => $line[1],	
									'gram_nilai'   => $line[2],
									'gram_grading' => $line[3],
									'gram_no_job'  => $line[4],
									'insentif'  => $line[5],
									'company_id'  => $company_id,
									'workstation_id'  => $workstation_id,
																
									'added_by'     => $this->input->post('user_id'),
									'created_at'   => date('Y-m-d H:i:s'),
									'status'       => '',
								);
								$this->Clients_model->add_gram($data);

								// rekap
									$sql1 ="DELETE FROM xin_workstation_gram_terima WHERE 1=1
											AND  gram_tanggal = '".$gram_tanggal."' AND employee_id  = '".$line[1]."' AND gram_no_job  = '".$line[4]."'  AND company_id  = '".$company_id."'  ";
									$this->db->query($sql1);
									
									// var_dump($user_info);return;
									

									$ongkos = $this->Core_model->read_gramasi_workstation_info($gram_grading);
									if(!is_null($ongkos)){						
										$ongkos_biaya     = $ongkos[0]->skala_upah_ongkos;
									} else {
										$ongkos_biaya = 0;							
									}

									$jumlah = $ongkos_biaya*$gram_nilai/1000; 

									$session_id = $this->session->userdata('user_id');
									$user_create = $session_id['user_id'];


									$data_impor = array(
										'gram_tanggal'                 => $gram_tanggal,						
										'employee_id'                  => $employee_id,
										'workstation_id'               => $workstation_id,
										'company_id'  					=> $company_id,
										'gram_grading'     			   => $gram_grading,	
										'gram_nilai'                   => $gram_nilai,
										'gram_ongkos'                  => $ongkos_biaya,
										'gram_biaya'         	       => $jumlah,						
										'insentif'         	           => $insentif,						

										'gram_no_job'                  => $gram_no_job,
										'status'                   	   => '1',
										'created_at'                   => date('Y-m-d h:i:s'),
										'created_by'                   => $user_create
									);
									$this->Payroll_model->add_produktifitas_harian($data_impor);
							}					
							//close opened csv file
							fclose($csvFile);
				
							$Return['result'] = $this->lang->line('xin_success_gram_import');
						}
				
					} else{
						$Return['error'] = $this->lang->line('xin_error_not_gram_import');
					}

				} else {
					$Return['error'] = $this->lang->line('xin_error_invalid_file');
				}
			} // file empty
					
			if($Return['error']!='')
			{
	       		$this->output($Return);
	    	}	
			
			$this->output($Return);
			exit;
		}
		
	}

	public function gaji_borongan_gramasi_list() 
	{
		$data['title'] = $this->Core_model->site_title();
		$session = $this->session->userdata('username');
		
		if(empty($session)){ 
			redirect('admin/');
		}

		// Datatables Variables
		$draw   = intval($this->input->get("draw"));
		$start  = intval($this->input->get("start"));
		$length = intval($this->input->get("length"));
		
		// date and employee id/company id
		$start_date = $this->input->get("start_date");
		$company_id = $this->input->get("company_id");
		$workstation_id = $this->input->get("workstation_id");
		
		$role_resources_ids = $this->Core_model->user_role_resource();
													
		$payslip = $this->Payroll_model->get_comp_import_borongan_company2($company_id,$workstation_id,$start_date);
		// var_dump($payslip->result());
		// return;
		$system = $this->Core_model->read_setting_info(1);		
		$data   = array();
		$no     = 1;              

        foreach($payslip->result() as $r) {
				
				// ====================================================================================================================
				// DATA KARYAWAN 
				// ====================================================================================================================
					// Karyawan ID
					$emp_id         = $r->employee_id;
					$user_id        = $r->added_by;

					$create_info      = $this->Core_model->read_user_info_detail($user_id);	
					// var_dump($user_id);return;

					if(!is_null($r->fullname)){
						$emp_nik        = $r->employee_id;
						$full_name      = $r->fullname;					
						$designation_id = $r->designation_id;
						$status_hris    = '<i class="fa fa-check-circle text-success"></i> Ada ';				
						$create_info_name        = ucfirst($create_info[0]->first_name . ' ' .$create_info[0]->last_name);
					} else {
						$emp_nik        = '';
						$create_info_name        = ucfirst($create_info[0]->first_name . ' ' .$create_info[0]->last_name);
						$full_name      = '<span class="badge bg-red"> ? </span>';			
						$designation_id = '';
						$status_hris    = '<i class="fa fa-times-circle text-danger"></i> Tidak Ada';
					}
				
				// ====================================================================================================================
				// DATA LAIN 
				// ====================================================================================================================
					
					// get workstation
					if(!is_null($r->workstation_name)){
						$workstation_name = $r->workstation_name;
					} else {
						$workstation_name = '<span class="badge bg-red"> ? </span>';
					}

					// Karyawan Posisi
					if(!is_null($r->designation_name)){
						$designation_name = $r->designation_name;
					} else {
						$designation_name = '<span class="badge bg-red"> ? </span>';	
					}

					$gram_tanggal = $start_date; 
					$employee_id  = $emp_id;
					$gram_no_job  = $r->gram_no_job;
					$gram_grading = $r->gram_grading;
					$gram_nilai   = $r->gram_nilai;

					// Karyawan Posisi
					$simpan = $this->Core_model->read_gramasi_simpan_info($gram_tanggal,$employee_id,$gram_no_job,$gram_grading,$gram_nilai);
					if(!is_null($simpan)){
						$status_simpan = $simpan[0]->jumlah;
					} else {
						$status_simpan = '0';	
					}

					if ($status_simpan == 0){
						$info_simpan = '<i class="fa fa-times text-danger"></i> Belum ';
					} else {
						$info_simpan = '<i class="fa fa-check text-success"></i> Sudah ';
					}

					 

				$data[] = array(
					$no,	
					date("d-m-Y",strtotime($start_date)),
					$r->gram_no_job,
					$emp_id,
					$full_name,		
					$designation_name,
					$r->gram_grading,
					number_format($r->gram_nilai, 0, ',', '.'), 					
					number_format($r->insentif, 0, ',', '.'), 					
					
					$status_hris,
					$info_simpan,
					date("d-m-Y",strtotime($r->created_at)).' | '.date("H:i:s",strtotime($r->created_at)).' | '.$create_info_name,				
											
					
				);
				$no++;

        }

        $output = array(
            "draw" => $draw,
            "recordsTotal" => $payslip->num_rows(),
            "recordsFiltered" => $payslip->num_rows(),
            "data" => $data
        );
        $this->output->set_output(json_encode($output));		         
    }
	

    public function gaji_borongan_gramasi_list_jumlah()
	{
					        

        $start_date = $this->input->get('start_date');
		$company_id = $this->input->get("company_id");
		$workstation_id = $this->input->get("workstation_id");
		$sql = 'SELECT count(*) as jumlah, sum(gram_nilai) as jumlah_gram 
		        FROM xin_workstation_gram 
		        WHERE gram_tanggal = "'.$start_date.'" and company_id =   "'.$company_id.'" and workstation_id =  "'.$workstation_id.'"  ';

		
		// echo "<pre>";
		// print_r($sql);
		// echo "</pre>";
		// die();

		$query = $this->db->query($sql);

		
		$response['val'] = array();
		if ($query<>false) {
			foreach ($query->result() as $val) {

				$kg = $val->jumlah_gram/1000;

				$response['val'][] = array(
					'tanggal' => date("d-m-Y",strtotime( $start_date)),
					'jumlah_gram' => number_format($val->jumlah_gram, 2, ',', '.'),
					'jumlah_kg' => number_format($kg, 2, ',', '.'), 
					'jumlah' => $val->jumlah
											
				);
			}
			$response['status'] = '200';
		}	              

		
		echo json_encode($response);
	}

    public function gaji_borongan_gramasi_simpan_list() 
	{
		$data['title'] = $this->Core_model->site_title();
		$session = $this->session->userdata('username');
		
		if(empty($session)){ 
			redirect('admin/');
		}

		// Datatables Variables
		$draw   = intval($this->input->get("draw"));
		$start  = intval($this->input->get("start"));
		$length = intval($this->input->get("length"));
		
		// date and employee id/company id
		$start_date = $this->input->get("start_date");
		
		$role_resources_ids = $this->Core_model->user_role_resource();
													
		$payslip = $this->Payroll_model->get_comp_import_borongan_company($start_date);
		
		$system = $this->Core_model->read_setting_info(1);		
		$data   = array();
		$no     = 1;              

		$sql1 ="DELETE FROM xin_workstation_gram_terima WHERE 1=1
		        AND  gram_tanggal = '".$start_date."'  ";
		// print_r($sql1);
		// exit();
		$query1   = $this->db->query($sql1);

        foreach($payslip->result() as $r) {


				
				// ====================================================================================================================
				// DATA KARYAWAN 
				// ====================================================================================================================
					
					// Karyawan ID
					$emp_id         = $r->employee_id;
					$user_id        = $r->added_by;

					$user_info      = $this->Core_model->read_employee_info_data($emp_id);	

					if(!is_null($user_info)){
						$emp_nik        = $user_info[0]->employee_id;
						$full_name      = $user_info[0]->first_name.' '.$user_info[0]->last_name;					
						$designation_id = $user_info[0]->designation_id;
						$status	        = '<i class="fa fa-check-circle text-success"></i> Ada';
					} else {
						$emp_nik        = '';
						$full_name      = '<span class="badge bg-red"> ? </span>';			
						$designation_id = '';
						$status	        = '<i class="fa fa-times-circle text-danger"></i> Tidak Ada';
					}

					$create_info      = $this->Core_model->read_user_info_detail($user_id);	

					if(!is_null($create_info)){
						
						$create_info_name      = ucfirst($create_info[0]->first_name.' '.$create_info[0]->last_name);					
						
					} else {
						$create_info_name        = '';
						
					}
				
				// ====================================================================================================================
				// DATA KARYAWAN 
				// ====================================================================================================================
								
				

						
					// get workstation
					$workstation = $this->Core_model->read_designation_workstation_info($designation_id);
					if(!is_null($workstation)){
						$workstation_name = $workstation[0]->workstation_name;
						$workstation_id   = $workstation[0]->workstation_id;
					} else {
						$workstation_name = '<span class="badge bg-red"> ? </span>';
						$workstation_id   = '';
					}

					// Karyawan Posisi
					$designation = $this->Designation_model->read_designation_information($designation_id);
					if(!is_null($designation)){
						$designation_name = $designation[0]->designation_name;
					} else {
						$designation_name = '<span class="badge bg-red"> ? </span>';	
					}

					// Karyawan Posisi
					$ongkos = $this->Core_model->read_gramasi_workstation_info($r->gram_grading);
					if(!is_null($ongkos)){						
						$ongkos_biaya     = $ongkos[0]->skala_upah_ongkos;
					} else {
						$ongkos_biaya = 0;							
					}

					$jumlah = $ongkos_biaya*$r->gram_nilai/1000; 

					$session_id = $this->session->userdata('user_id');
					$user_create = $session_id['user_id'];


					$data_impor = array(
						'gram_tanggal'                 => $start_date,						
						'employee_id'                  => $emp_id,
						'workstation_id'               => $workstation_id,

						'gram_grading'     			   => $r->gram_grading,	
						'gram_nilai'                   => $r->gram_nilai,
						'gram_ongkos'                  => $ongkos_biaya,
						'gram_biaya'         	       => $jumlah,						

						'gram_no_job'                  => $r->gram_no_job,
						'status'                   	   => '1',
						'created_at'                   => date('Y-m-d h:i:s'),
						'created_by'                   => $user_create
					);
					
					$this->Payroll_model->add_produktifitas_harian($data_impor);


					 

				$data[] = array(
					$no,	
					date("d-m-Y",strtotime($start_date)),
					$r->gram_no_job,
					$emp_id,
					$full_name,				
					$designation_name,
					$r->gram_grading,					
					$r->gram_nilai,
					$status,
					'Sudah',
					'<span class="blink blink_one hijau"><i class="fa fa-check-circle text-success"></i> Sukses Tersimpan</span>'				
											
					
				);
				$no++;

        }

        $output = array(
            "draw" => $draw,
            "recordsTotal" => $payslip->num_rows(),
            "recordsFiltered" => $payslip->num_rows(),
            "data" => $data
        );
        $this->output->set_output(json_encode($output));		         
    }

    public function gaji_borongan_gramasi_hapus_list() 
	{
		$data['title'] = $this->Core_model->site_title();
		$session = $this->session->userdata('username');
		
		if(empty($session)){ 
			redirect('admin/');
		}

		// Datatables Variables
		$draw   = intval($this->input->get("draw"));
		$start  = intval($this->input->get("start"));
		$length = intval($this->input->get("length"));
		
		// date and employee id/company id
		$start_date = $this->input->get("start_date");
		$company_id = $this->input->get("company_id");
		$workstation_id = $this->input->get("workstation_id");
		
		$role_resources_ids = $this->Core_model->user_role_resource();
													
		$payslip = $this->Payroll_model->get_comp_import_borongan_company2($company_id,$workstation_id,$start_date);
		
		$system = $this->Core_model->read_setting_info(1);		
		$data   = array();
		$no     = 1;              

		$sql1 ="DELETE FROM xin_workstation_gram WHERE 1=1
		        AND  gram_tanggal = '".$start_date."' and company_id = '".$company_id."' and workstation_id = '".$workstation_id."'  ";
		// print_r($sql1);
		// exit();
		$query1   = $this->db->query($sql1);

		$sql2 ="DELETE FROM xin_workstation_gram_terima WHERE 1=1
		        AND  gram_tanggal = '".$start_date."' and company_id = '".$company_id."' and workstation_id = '".$workstation_id."' ";
		// print_r($sql2);
		// exit();
		$query2   = $this->db->query($sql2);

        foreach($payslip->result() as $r) {


				
				// ====================================================================================================================
				// DATA KARYAWAN 
				// ====================================================================================================================
					
					// Karyawan ID
					$emp_id         = $r->employee_id;
					$user_id        = $r->added_by;

					$user_info      = $this->Core_model->read_employee_info_data($emp_id);	

					if(!is_null($user_info)){
						$emp_nik        = $user_info[0]->employee_id;
						$full_name      = $user_info[0]->first_name.' '.$user_info[0]->last_name;					
						$designation_id = $user_info[0]->designation_id;
						$status	        = '<i class="fa fa-check-circle text-success"></i> Ada';
					} else {
						$emp_nik        = '';
						$full_name      = '<span class="badge bg-red"> ? </span>';			
						$designation_id = '';
						$status	        = '<i class="fa fa-times-circle text-danger"></i> Tidak Ada';
					}

					$create_info      = $this->Core_model->read_user_info_detail($user_id);	

					if(!is_null($create_info)){
						
						$create_info_name      = ucfirst($create_info[0]->first_name.' '.$create_info[0]->last_name);					
						
					} else {
						$create_info_name        = '';
						
					}
				
				// ====================================================================================================================
				// DATA KARYAWAN 
				// ====================================================================================================================
						
					// get workstation
					$workstation = $this->Core_model->read_designation_workstation_info($designation_id);
					if(!is_null($workstation)){
						$workstation_name = $workstation[0]->workstation_name;
						$workstation_id   = $workstation[0]->workstation_id;
					} else {
						$workstation_name = '<span class="badge bg-red"> ? </span>';
						$workstation_id   = '';
					}

					// Karyawan Posisi
					$designation = $this->Designation_model->read_designation_information($designation_id);
					if(!is_null($designation)){
						$designation_name = $designation[0]->designation_name;
					} else {
						$designation_name = '<span class="badge bg-red"> ? </span>';	
					}

					// Karyawan Posisi
					$ongkos = $this->Core_model->read_gramasi_workstation_info($r->gram_grading);
					if(!is_null($ongkos)){						
						$ongkos_biaya     = $ongkos[0]->skala_upah_ongkos;
					} else {
						$ongkos_biaya = 0;							
					}

					$jumlah = $ongkos_biaya*$r->gram_nilai/1000; 

					$session_id = $this->session->userdata('user_id');
					$user_create = $session_id['user_id'];

					if ($workstation_id == 1 || 
						$workstation_id == 2 || 
						$workstation_id == 3 || 
						$workstation_id == 5 || 
						$workstation_id == 8 ||
						$workstation_id == 9 ||
						$workstation_id == 27 ) {

						if ($jumlah > 0 && $jumlah <= 65000) {
							
							$jumlah_biaya = 65000;
						
						} else {
						
							$jumlah_biaya = $jumlah;
						}

					} else {

						$jumlah_biaya = $jumlah;

					}

										 

				$data[] = array(
					$no,	
					date("d-m-Y",strtotime($start_date)),
					$r->gram_no_job,
					$emp_id,
					$full_name,				
					$designation_name,
					$r->gram_grading,					
					$r->gram_nilai,
					$status,
					'Sudah',
					'<span class="blink blink_one hijau"><i class="fa fa-check-circle text-success"></i> Sukses Tersimpan</span>'				
											
					
				);
				$no++;

        }

        $output = array(
            "draw" => $draw,
            "recordsTotal" => $payslip->num_rows(),
            "recordsFiltered" => $payslip->num_rows(),
            "data" => $data
        );
        $this->output->set_output(json_encode($output));		         
    }

	public function get_workstations()
    {

        $data['title'] = $this->Core_model->site_title();
		$id = $this->uri->segment(4);

        $data = array(
            'company_id' => $id
        );
		// var_dump($data);
        $session = $this->session->userdata('username');
        if (!empty($session)) {
            $this->load->view("admin/layout/get_workstations", $data);
        } else {
            redirect('admin/');
        }
        // Datatables Variables
        $draw = intval($this->input->get("draw"));
        $start = intval($this->input->get("start"));
        $length = intval($this->input->get("length"));
    }



} 
?>