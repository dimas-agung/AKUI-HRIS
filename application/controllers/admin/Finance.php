<?php
 
defined('BASEPATH') OR exit('No direct script access allowed');

class Finance extends MY_Controller {

	 public function __construct() {
        parent::__construct();
        
        $this->load->library(array('PHPExcel', 'PHPExcel/IOFactory'));
		$this->load->library('Tools');
		$this->load->library('Pdf');

		//load the model
		$this->load->model("Payroll_model");
		$this->load->model("Core_model");
		$this->load->model("Employees_model");
		$this->load->model("Designation_model");
		$this->load->model("Department_model");
		$this->load->model("Location_model");
		$this->load->model("Timesheet_model");
		// $this->load->model("Overtime_request_model");
		$this->load->model("Overtime_model");
		$this->load->model("Company_model");
		$this->load->model("Finance_model");
		$this->load->model("Roles_model");

		$this->load->model("Employees_model");		
	
		$this->load->model("Company_model");
		$this->load->model("Timesheet_model");
		$this->load->model("Custom_fields_model");
		$this->load->model("Assets_model");
		$this->load->model("Training_model");
		$this->load->model("Trainers_model");
		
		$this->load->model("Awards_model");
		$this->load->model("Travel_model");
		$this->load->model("Tickets_model");
		$this->load->model("Transfers_model");
		$this->load->model("Promotion_model");
		$this->load->model("Complaints_model");
		$this->load->model("Warning_model");
		
		$this->load->model("Payroll_model");
		$this->load->model("Events_model");
		$this->load->model("Meetings_model");
		$this->load->model('Overtime_model');


		$this->load->helper('string');
	}
	
	/*Function to set JSON output*/
	public function output($Return=array()){
		/*Set response header*/
		header("Access-Control-Allow-Origin: *");
		header("Content-Type: application/json; charset=UTF-8");
		/*Final JSON response*/
		exit(json_encode($Return));
	}
	

    // ================================================================================
    // GAJI BULANAN
    // ================================================================================
    	
		// payment history
		public function gaji_bulanan()
	    {
			$session = $this->session->userdata('username');
			if(empty($session)){ 
				redirect('admin/');
			}
			$data['title']         = 'Gaji Bulanan | '.$this->Core_model->site_title();
			$data['icon']          = '<i class="fa fa-money"></i>';
			$data['breadcrumbs']   = 'Gaji Bulanan';
			$data['path_url']      = 'finance_gaji_bulanan';
			
			$data['all_employees'] = $this->Core_model->all_employees();
			$data['get_all_companies'] = $this->Company_model->get_company();
			$data['all_bulan_gaji']    = $this->Core_model->all_bulan_gaji();

			$role_resources_ids = $this->Core_model->user_role_resource();
			if(in_array('1111',$role_resources_ids)) {
				if(!empty($session)){
					$data['subview'] = $this->load->view("admin/finance/gaji_bulanan", $data, TRUE);
					$this->load->view('admin/layout/layout_main', $data); //page load
				} else {
					redirect('admin/');
				}
			} else {
				redirect('admin/dashboard');
			}		  
	    }

	    // hourly_list > templates
		public function gaji_bulanan_list()
	    {

			$data['title'] = $this->Core_model->site_title();
			$session = $this->session->userdata('username');
			if(!empty($session)){ 
				$this->load->view("admin/finance/gaji_bulanan", $data);
			} else {
				redirect('admin/');
			}
			// Datatables Variables
			$draw = intval($this->input->get("draw"));
			$start = intval($this->input->get("start"));
			$length = intval($this->input->get("length"));
			
			$role_resources_ids = $this->Core_model->user_role_resource();
			$user_info = $this->Core_model->read_user_info($session['user_id']);
			
			$history = $this->Payroll_model->get_company_payslip_history_month($this->input->get("company_id"),$this->input->get("salary_month"));

			$data = array();

			$no = 1;

	        foreach($history->result() as $r) {
					
				$user = $this->Core_model->read_user_info($r->employee_id);				
				// user full name
				if(!is_null($user)){

					$full_name = $user[0]->first_name.' '.$user[0]->last_name;
					$emp_link = $user[0]->employee_id;			  		  
					$month_payment = date("F Y", strtotime($r->salary_month));
					
					$p_amount = $this->Core_model->currency_sign($r->net_salary);
					
					// get date > created at > and format
					$created_at = $this->Core_model->set_date_format($r->created_at);
					// get designation
					$designation = $this->Designation_model->read_designation_information($user[0]->designation_id);
					if(!is_null($designation)){
						$designation_name = $designation[0]->designation_name;
					} else {
						$designation_name = '--';	
					}
				
					// department
					$department = $this->Department_model->read_department_information($user[0]->department_id);
					if(!is_null($department)){
					$department_name = $department[0]->department_name;
					} else {
					$department_name = '--';	
					}

					$department_designation = $designation_name.' ('.$department_name.')';
					
					// get company
					$company = $this->Core_model->read_company_info($user[0]->company_id);
					if(!is_null($company)){
						$comp_name = $company[0]->name;
					} else {
						$comp_name = '--';	
					}
					// bank account
					$bank_account = $this->Employees_model->get_employee_bank_account_last($user[0]->user_id);
					if(!is_null($bank_account)){
						$account_number = $bank_account[0]->account_number;
						$bank_name = $bank_account[0]->bank_code.'-'.$bank_account[0]->bank_name;
					} else {
						$account_number = '--';
						$bank_name = '--';	
					}
				
				
					$ifull_name = nl2br ($full_name."\r\n <small class='text-muted'><i>".$this->lang->line('xin_employees_id').': '.$emp_link."<i></i></i></small>\r\n <small class='text-muted'><i>".$department_designation.'<i></i></i></small>');
	                
	                $data[] = array(
						$no,
						$month_payment,
	                    $full_name,
						$comp_name,
						$department_name,
						$designation_name,
						$p_amount,
	                    $account_number,
	                    $bank_name 
	                );
	                $no++;
		        }
			} 

        	$output = array(
	            "draw" => $draw,
	            "recordsTotal" => $history->num_rows(),
	            "recordsFiltered" => $history->num_rows(),
	            "data" => $data
	        );
	        $this->output->set_output(json_encode($output));
	    }

	// ================================================================================
	// REKAP THR BULANAN
	// ================================================================================
	
		public function thr_bulanan() 
		{
			$session = $this->session->userdata('username');
			if(empty($session)){ 
				redirect('admin/');
			}
			$data['title']         = 'THR Bulanan | '.$this->Core_model->site_title();
			$data['icon']          = '<i class="fa fa-money"></i>';
			$data['breadcrumbs']   = 'THR Bulanan';
			$data['path_url']      = 'finance_thr_bulanan';
			
			$data['all_companies'] = $this->Company_model->get_company();
			$data['all_tahun_thr']    = $this->Core_model->all_tahun_gaji();

			$role_resources_ids = $this->Core_model->user_role_resource();
			if(in_array('1381',$role_resources_ids)) {
				$data['subview'] = $this->load->view("admin/finance/thr_bulanan", $data, TRUE);
				$this->load->view('admin/layout/layout_main', $data); //page load
			} else {
				redirect('admin/dashboard');
			}
		}			
	
		// hourly_list > templates
		public function slip_thr_bulanan_list() 
		{
			$data['title'] = $this->Core_model->site_title();
			$session = $this->session->userdata('username');
			
			if(!empty($session)){ 			
				$this->load->view("admin/finance/thr_bulanan", $data);			
			} else {			
				redirect('admin/');			
			}
			// Datatables Variables
			$draw   = intval($this->input->get("draw"));
			$start  = intval($this->input->get("start"));
			$length = intval($this->input->get("length"));
			
			// date and employee id/company id
			$p_date = $this->input->get("thr_year");
			$tanggal_thr = $this->input->get("tanggal_thr");	

			$role_resources_ids = $this->Core_model->user_role_resource();
			
			$user_info          = $this->Core_model->read_user_info($session['user_id']);
			
			$history = $this->Payroll_model->get_company_thr($this->input->get("company_id"),$this->input->get("thr_year"),$this->input->get("tanggal_thr"));		
		   
			$system = $this->Core_model->read_setting_info(1);		
			$data   = array();
			$no     = 1;              

	        foreach($history->result() as $r) {
				$user = $this->Core_model->read_user_info($r->employee_id);	
				if(!is_null($user)){

					$full_name = $user[0]->first_name.' '.$user[0]->last_name;
					$emp_link = $user[0]->employee_id;	

					$tahun_thr = date("Y", strtotime($r->tahun_thr));
					
					$p_amount = $this->Core_model->currency_sign($r->net_salary);
					
					// get date > created at > and format
					$created_at = $this->Core_model->set_date_format($r->created_at);
					// get designation
					$designation = $this->Designation_model->read_designation_information($user[0]->designation_id);
					if(!is_null($designation)){
						$designation_name = $designation[0]->designation_name;
					} else {
						$designation_name = '--';	
					}
				
					// department
					$department = $this->Department_model->read_department_information($user[0]->department_id);
					if(!is_null($department)){
					$department_name = $department[0]->department_name;
					} else {
					$department_name = '--';	
					}

					$department_designation = $designation_name.' ('.$department_name.')';
					
					// get company
					$company = $this->Core_model->read_company_info($user[0]->company_id);
					if(!is_null($company)){
						$comp_name = $company[0]->name;
					} else {
						$comp_name = '--';	
					}
					// bank account
					$bank_account = $this->Employees_model->get_employee_bank_account_last($user[0]->user_id);
					if(!is_null($bank_account)){
						$account_number = $bank_account[0]->account_number;
						$bank_name = $bank_account[0]->bank_code.'-'.$bank_account[0]->bank_name;
					} else {
						$account_number = '--';
						$bank_name = '--';	
					}
				
				
					$ifull_name = nl2br ($full_name."\r\n <small class='text-muted'><i>".$this->lang->line('xin_employees_id').': '.$emp_link."<i></i></i></small>\r\n <small class='text-muted'><i>".$department_designation.'<i></i></i></small>');
	                
	                $data[] = array(
						$no,
						$tahun_thr,
						$tanggal_thr,
	                    $full_name,
						$comp_name,
						$department_name,
						$designation_name,
						$p_amount,
	                    $account_number,
	                    $bank_name 
	                );
	                $no++;
		        }
	        }

	        $output = array(
	            "draw" => $draw,
	            "recordsTotal" => $history->num_rows(),
	            "recordsFiltered" => $history->num_rows(),
	            "data" => $data
	        );
	        $this->output->set_output(json_encode($output));		         
	    }
		
		public function export_thr_bulanan()
		{
			$data['title'] = $this->Core_model->site_title();
			$session = $this->session->userdata('username');
			
			if(!empty($session)){ 			
				$this->load->view("admin/reports/thr_bulanan", $data);			
			} else {			
				redirect('admin/');			
			}

			$company_id   = $this->input->post('company');
			$thr_year     = $this->input->post('thr_year');
		    
		    $tanggal_thr     = $this->input->post('tanggal_thr');

			$cek_tahun_thr = $this->input->post('thr_year');
			

			$company = $this->Core_model->read_company_info($company_id);
			$company_name = $company[0]->name;			

			// echo "<pre>";
			// print_r($company);
			// print_r($month_year);
			
			// echo "</pre>";
			// die;
			
			$role_resources_ids = $this->Core_model->user_role_resource();			
			$user_info          = $this->Core_model->read_user_info($session['user_id']);			
							
			$departemen = $this->Payroll_model->get_comp_template_dept_bulanan_lihat_thr($company_id,$thr_year);

			// $ambildata = $this->mod->get_attendance_to_excel($tgl1, $tgl2, $location_id, $status_id, $user_id);

			if (count($departemen) > 0) {

				$objPHPExcel = new PHPExcel();

	            // Set properties
				
				$objPHPExcel->getProperties()->setCreator("Nizar Basyrewan")
							 ->setLastModifiedBy("Nizar Basyrewan")
							 ->setTitle("Office 2007 XLSX Test Document")
							 ->setSubject("Office 2007 XLSX Test Document")
							 ->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")
							 ->setKeywords("office 2007 openxml php")
							 ->setCategory("Test result file");

				$objPHPExcel->setActiveSheetIndex(0)
				            ->mergeCells("B1:N1")
				            ->getStyle("B1:B1")
				            ->applyFromArray(
					              array(
					              	'alignment' => array(
										'horizontal' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
									),
					              	"font" => array(
					              		"size" => 16,
					              		"bold" => true,
					              		"color" => array("rgb" => "363636")
					              	)
					              )
					        );

				$objPHPExcel->setActiveSheetIndex(0)
				            ->mergeCells("B2:N2")
				            ->getStyle("B2:B2")
				            ->applyFromArray(
					              array(
					              	'alignment' => array(
										'horizontal' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
									),
					              	"font" => array(
					              		"size" => 14,
					              		"bold" => true,
					              		"color" => array("rgb" => "363636")
					              	)
					              )
					        );

				$objPHPExcel->setActiveSheetIndex(0)
				            ->mergeCells("B3:N3")
				            ->getStyle("B3:B3")
				            ->applyFromArray(
					              array(
					              	'alignment' => array(
										'horizontal' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
									),
					              	"font" => array(
					              		"size" => 11,
					              		"bold" => true,
					              		"color" => array("rgb" => "363636")
					              	)
					              )
					        );

				$objPHPExcel->setActiveSheetIndex(0)->setCellValue("B1", $company_name);

				$objPHPExcel->setActiveSheetIndex(0)->setCellValue("B2", "THR BULANAN");

				$objPHPExcel->setActiveSheetIndex(0)->setCellValue("B3", "TAHUN ".$cek_tahun_thr." TANGGAL ".$tanggal_thr);

				$objPHPExcel->getActiveSheet()->freezePane('E7');

				$objset = $objPHPExcel->setActiveSheetIndex(0);     // inisiasi set object
				$objget = $objPHPExcel->getActiveSheet();           // inisiasi get object						

				$objPHPExcel->getActiveSheet()->getStyle("B5:N5")->applyFromArray(
					array(
						'fill' => array(
							'type' => PHPExcel_Style_Fill::FILL_SOLID,
							'color' => array('rgb' => 'CCC0DA')
						),
						'font' => array(
							'color' => array('rgb' => '363636')
						),
						'borders' => array(
					          'allborders' => array(
					              'style' => PHPExcel_Style_Border::BORDER_THIN
					          )
					    )
					)
				);

				$objPHPExcel->getActiveSheet()->getStyle("B6:N6")->applyFromArray(
					array(
						'fill' => array(
							'type' => PHPExcel_Style_Fill::FILL_SOLID,
							'color' => array('rgb' => 'CCC0DA')
						),
						'font' => array(
							'color' => array('rgb' => '363636')
						),
						'borders' => array(
					          'allborders' => array(
					              'style' => PHPExcel_Style_Border::BORDER_THIN
					        )
					    )
					)
				);		
				
				// setting kolom rata
				$objPHPExcel->getActiveSheet()->getStyle('B7:B2560')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
				$objPHPExcel->getActiveSheet()->getStyle('C7:C2560')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
				$objPHPExcel->getActiveSheet()->getStyle('D7:D2560')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
				
				$objPHPExcel->getActiveSheet()->getStyle('E7:E2560')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
				$objPHPExcel->getActiveSheet()->getStyle('F7:F2560')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);	
				$objPHPExcel->getActiveSheet()->getStyle('G7:G2560')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
				$objPHPExcel->getActiveSheet()->getStyle('H7:H2560')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

				$objPHPExcel->getActiveSheet()->getStyle('M7:M2560')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);					

				$objPHPExcel->getActiveSheet()->getStyle('B5:N5000')->getAlignment()->setWrapText(true);
				
				// Kanan
				$objPHPExcel->getActiveSheet()->getStyle("I7:I2560")->getNumberFormat()->setFormatCode("#,##0")->applyFromArray(array('alignment' => array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT)));			    
				$objPHPExcel->getActiveSheet()->getStyle("J7:J2560")->getNumberFormat()->setFormatCode("#,##0")->applyFromArray(array('alignment' => array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT)));			    
				$objPHPExcel->getActiveSheet()->getStyle("K7:K2560")->getNumberFormat()->setFormatCode("#,##0")->applyFromArray(array('alignment' => array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT)));			    
				$objPHPExcel->getActiveSheet()->getStyle("L7:L2560")->getNumberFormat()->setFormatCode("#,##0")->applyFromArray(array('alignment' => array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT)));			    
				
				$objPHPExcel->getActiveSheet()->getStyle("M7:M2560")->getNumberFormat()->setFormatCode()->applyFromArray(array('alignment' => array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT)));			    
				
				// $objPHPExcel->getActiveSheet()->getStyle("F7:F2560")->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);

				// Merge
				$objPHPExcel->getActiveSheet()->mergeCells('B5:B6');
				$objPHPExcel->getActiveSheet()->getStyle('B5')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

				$objPHPExcel->getActiveSheet()->mergeCells('C5:C6');
				$objPHPExcel->getActiveSheet()->getStyle('C5')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

				$objPHPExcel->getActiveSheet()->mergeCells('D5:D6');
				$objPHPExcel->getActiveSheet()->getStyle('D5')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

				$objPHPExcel->getActiveSheet()->mergeCells('E5:E6');
				$objPHPExcel->getActiveSheet()->getStyle('E5')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

				$objPHPExcel->getActiveSheet()->mergeCells('F5:F6');
				$objPHPExcel->getActiveSheet()->getStyle('F5')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

				$objPHPExcel->getActiveSheet()->mergeCells('G5:G6');
				$objPHPExcel->getActiveSheet()->getStyle('G5')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

				$objPHPExcel->getActiveSheet()->mergeCells('H5:H6');
				$objPHPExcel->getActiveSheet()->getStyle('H5')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

				$objPHPExcel->getActiveSheet()->mergeCells('I5:I6');
				$objPHPExcel->getActiveSheet()->getStyle('I5')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

				$objPHPExcel->getActiveSheet()->mergeCells('J5:J6');
				$objPHPExcel->getActiveSheet()->getStyle('J5')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

				$objPHPExcel->getActiveSheet()->mergeCells('K5:K6');
				$objPHPExcel->getActiveSheet()->getStyle('K5')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

				$objPHPExcel->getActiveSheet()->mergeCells('L5:L6');
				$objPHPExcel->getActiveSheet()->getStyle('L5')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

				$objPHPExcel->getActiveSheet()->mergeCells('M5:M6');
				$objPHPExcel->getActiveSheet()->getStyle('M5')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

				$objPHPExcel->getActiveSheet()->mergeCells('N5:N6');
				$objPHPExcel->getActiveSheet()->getStyle('N5')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);



	            //table header
				$cols = array("A","B", "C", "D", "E", "F", "G","H","I","J","K","L","M","N");

				$val = array("", "No", "Nama", 'Jabatan', "Status", "Grade", "Tanggal Kerja", "Masa Kerja", "Gaji Pokok","Tj.Jabatan", "Total T1&T2", "Total THR", "No Rekening", "Bank");

				$style = array(
					'alignment' => array(
						'horizontal' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
					)
				);					 
				

				for ($a = 0; $a < 14; $a++) {
					
					$objset->setCellValue($cols[$a].'5', $val[$a]);
	                
	                // Setting lebar cell
	                $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(5);  // 
					$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(5);  // No
					$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(45); // Nama			
					$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(35); // Jabatan	

					$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(10); // StatuS	
					$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(10); // Grade	
					$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(12); // Tanggal Kerja	
					$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(12); // Masa Kerja

					$objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(15); // Gaji Pokok
					$objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(15); // Tj. Jabatan
					$objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(15); // Tj. T1&T2							

					$objPHPExcel->getActiveSheet()->getColumnDimension('L')->setWidth(15); // Total THR							
					$objPHPExcel->getActiveSheet()->getColumnDimension('M')->setWidth(20); // No.Rekening
					$objPHPExcel->getActiveSheet()->getColumnDimension('N')->setWidth(15); // Bank
					
					$style = array(
						'alignment' => array(
							'horizontal' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
						)
					);


					$objPHPExcel->getActiveSheet()->getStyle($cols[$a].'5')->applyFromArray($style);

				}

				
				$dep = 6;
				$baris = $dep+1;
				
				

				// $da = $baris+1;
				//$mo = $no+$baris;
				foreach($departemen->result() as $d) {

					$grouping = $this->Employees_model->get_employee_by_department_company_bulanan_thr($d->company_id,$d->department_id,$thr_year,$tanggal_thr);
	          		if(!is_null($grouping)){
						$jumlah_karyawan = $grouping[0]->jumlah;
					} else {
						$jumlah_karyawan = '';	
					}	

					if ($jumlah_karyawan > 0 ){

						// Karyawan Departemen
						$department = $this->Department_model->read_department_information($d->department_id);
						if(!is_null($department)){
							$department_name = $department[0]->department_name;
						} else {
							$department_name = '';		
						}

						// $objset->setCellValue("B".$baris, ); 
						$objset->setCellValue("B".($baris+1)."", $department_name.', Jumlah  : '.$jumlah_karyawan.' Karyawan'); 

						$objset ->getStyle("B".($baris+1).":N".($baris+1)."")
					            ->applyFromArray(
						              array(
						              	'fill' => array(
											'type' => PHPExcel_Style_Fill::FILL_SOLID,
											'color' => array('rgb' => 'B8CCE4')
										),
						              	
						              	"font" => array(
						              		"size" => 11,
						              		"bold" => true,
						              		"color" => array("rgb" => "000000")
						              	),

						              	'borders' => array(
									          'allborders' => array(
									              'style' => PHPExcel_Style_Border::BORDER_THIN
									          )
									    )
						              )
						          );

					    $objset ->getStyle("B".($jumlah_karyawan+$baris+2).":N".($jumlah_karyawan+$baris+2)."")
					            ->applyFromArray(
						              array(
						              	'fill' => array(
											'type' => PHPExcel_Style_Fill::FILL_SOLID,
											'color' => array('rgb' => 'B8CCE4')
										),		
										'borders' => array(
									          'allborders' => array(
									              'style' => PHPExcel_Style_Border::BORDER_THIN
									        )
									    ),			              	
						              	"font" => array(
						              		"size" => 11,
						              		"bold" => true,
						              		"color" => array("rgb" => "000000")
						              	)
						              )
						          );
					

						$objset ->mergeCells("B".($baris+1).":D".($baris+1)."") 
								->getStyle("B".($baris+1).":D".($baris+1)."")
						        ->applyFromArray(
						              array(
						              	
						              	'alignment' => array(
											'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,
										),
										"font" => array(
						              		"size" => 11,
						              		"bold" => true,
						              		"color" => array("rgb" => "000000")
						              	)
						              	
						              )
						          );
						
						$objset ->mergeCells("B".($jumlah_karyawan+$baris+2).":D".($jumlah_karyawan+$baris+2)."") 
								->getStyle("B".($jumlah_karyawan+$baris+2).":D".($jumlah_karyawan+$baris+2)."")
						        ->applyFromArray(
						              array(
						              	
						              	'alignment' => array(
											'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,
										),
										"font" => array(
						              		"size" => 11,
						              		"bold" => true,
						              		"color" => array("rgb" => "000000")
						              	)
						              	
						              )
						          );

						$objset ->getStyle("I".($jumlah_karyawan+$baris+2).":L".($jumlah_karyawan+$baris+2)."")
						    ->getNumberFormat()
						    ->setFormatCode("#,##0")
				            ->applyFromArray(
					              array(
					              	
					              	'alignment' => array(
										'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT,
									),
					              	
					              )
					          );
						
						$objset->setCellValue("B".($jumlah_karyawan+$baris+2)."", 'Total ');

			            $objset->setCellValue("I".($jumlah_karyawan+$baris+2)."", "=SUM(I".($baris+2).":I".($jumlah_karyawan+$baris+1).")");
						$objset->setCellValue("J".($jumlah_karyawan+$baris+2)."", "=SUM(J".($baris+2).":J".($jumlah_karyawan+$baris+1).")");
						$objset->setCellValue("K".($jumlah_karyawan+$baris+2)."", "=SUM(K".($baris+2).":K".($jumlah_karyawan+$baris+1).")");
						$objset->setCellValue("L".($jumlah_karyawan+$baris+2)."", "=SUM(L".($baris+2).":L".($jumlah_karyawan+$baris+1).")");
						
						
						// $objset->setCellValue("B".($jumlah_karyawan+$baris+3)."", '');

				        $thr = $this->Payroll_model->get_comp_template_bulanan_dep_lihat_thr($d->company_id,1,$thr_year,$tanggal_thr,$d->department_id);

				        $no = 1;
				        $baris =  $baris+2;
				        foreach($thr->result() as $r) {

				                // ====================================================================================================================
								// DATA KARYAWAN 
								// ====================================================================================================================
									
									// Karyawan ID
									$emp_id = $r->employee_id;

									$user_info = $this->Core_model->read_user_info( $emp_id );			
									$emp_nik   = $user_info[0]->employee_id;
									$full_name = $user_info[0]->first_name.' '.$user_info[0]->last_name;

								// ====================================================================================================================
								// DATA KARYAWAN 
								// ====================================================================================================================
									
									$rekening = $this->Employees_model->get_employee_bank_account_last($r->employee_id);
					          		if(!is_null($rekening)){
										$rekening_name = $rekening[0]->account_number;
										$bank_name = $rekening[0]->bank_name;
									} else {
										$rekening_name = '';	
										$bank_name = '--';
									}					

									$cek_karyawan_status = $user_info[0]->emp_status;
									
									if($cek_karyawan_status !=''){
										$karyawan_status     = $cek_karyawan_status;
									} else {
										$karyawan_status     = '';	
									}

										

										// Karyawan Posisi
										$designation = $this->Designation_model->read_designation_information($r->designation_id);
										if(!is_null($designation)){
											$designation_name = $designation[0]->designation_name;
										} else {
											$designation_name = '';	
										}

										$jabatan = $designation_name;

										// Karyawan Masa kerja														       	  
								        date_default_timezone_set("Asia/Jakarta");     
						                
						                $doj = $r->doj;

						                $tanggal1 = new DateTime($r->doj);
										$tanggal2 = new DateTime($tanggal_thr);
						          		
						          		if ($tanggal2->diff($tanggal1)->y == 0) {
						          			$selisih = $tanggal2->diff($tanggal1)->m.' bln';
						          		} else {
						          			$selisih = $tanggal2->diff($tanggal1)->y.' thn'.' '.$tanggal2->diff($tanggal1)->m.' bln';
						          		}

						          		$cek_karyawan_status = $user_info[0]->emp_status;
						          		
						          		// Karyawan Status
										$emp_status =  $this->Employees_model->read_employee_contract_information2($r->employee_id);
										if(!is_null($emp_status)){
											$emp_status_name = $emp_status[0]->name_type;
										} else {
											if ($cek_karyawan_status == 'Tetap'){
												$emp_status_name = 'Tetap';
											} else {
												$emp_status_name = '';
											}
												
										}						

											// grade
										$grade_type = $this->Core_model->read_user_jenis_grade($user_info[0]->grade_type);
										
										if(!is_null($grade_type)){
											$jenis_grade       = $grade_type[0]->jenis_grade_keterangan;
											$jenis_grade_warna = $grade_type[0]->warna;
										} else {
											$jenis_grade       = '';
											$jenis_grade_warna = '';
										}											
							
				                
				                //pemanggilan sesuaikan dengan nama kolom tabel

								$objset->setCellValue("B" . $baris, $no); 				       // No
								$objset->setCellValue("C" . $baris, $full_name); 		       // Nama Karyawan				
								$objset->setCellValue("D" . $baris, $jabatan); 			       // Jabatan

								$objset->setCellValue("E" . $baris, $emp_status_name); 		   // Status
								$objset->setCellValue("F" . $baris, $jenis_grade); 			   // Grade
								$objset->setCellValue("G" . $baris, $doj); 					   // Tanggal Mulai Kerja
								$objset->setCellValue("H" . $baris, $selisih); 				   // Masa Kerja
									
								$objset->setCellValue("I" . $baris, $r->basic_salary); 			// Gaji Pokok
								$objset->setCellValue("J" . $baris, $r->jumlah_tunj_jabatan); 	// Tj. Jabatan	
								$objset->setCellValue("K" . $baris, $r->total_jumlah); 		    // Total
								
								$objset->setCellValue("L" . $baris, $r->net_salary);            // Total THR
				                $objset->setCellValue("M" . $baris, "'".$r->rekening_name);     // No.Rekening
				                $objset->setCellValue("N" . $baris, $bank_name);                // Bank
				               
								$no++;
								$baris++;
						}
						
						$dep++;
					}
				}

				$total_emp = $this->Employees_model->get_employee_by_company_bulanan_thr($d->company_id,$thr_year,$tanggal_thr);
          		if(!is_null($total_emp)){
					$total_karyawan = $total_emp[0]->jumlah;
				} else {
					$total_karyawan = '';	
				}	
				
				$objset->setCellValue("B".($jumlah_karyawan+$baris+1)."", $company_name);
				$objset->setCellValue("B".($jumlah_karyawan+$baris+2)."", 'Jumlah Karyawan THR Bulanan Tahun '.$cek_tahun_thr.' Tanggal Batas THR '.$tanggal_thr.' : ');
				

				$objset->getStyle("B".($jumlah_karyawan+$baris+1).":N".($jumlah_karyawan+$baris+1)."")
				            ->applyFromArray(
					              array(
					              	'fill' => array(
										'type' => PHPExcel_Style_Fill::FILL_SOLID,
										'color' => array('rgb' => 'E26B0A')
									),		
									'borders' => array(
								          'allborders' => array(
								              'style' => PHPExcel_Style_Border::BORDER_THIN
								        )
								    ),			              	
					              	"font" => array(
					              		"size" => 12,
					              		"bold" => true,
					              		"color" => array("rgb" => "000000")
					              	)
					              )
					          );

				$objset->getStyle("B".($jumlah_karyawan+$baris+2).":N".($jumlah_karyawan+$baris+2)."")
				            ->applyFromArray(
					              array(
					              	'fill' => array(
										'type' => PHPExcel_Style_Fill::FILL_SOLID,
										'color' => array('rgb' => 'F7F1A5')
									),		
									'borders' => array(
								          'allborders' => array(
								              'style' => PHPExcel_Style_Border::BORDER_THIN
								        )
								    ),			              	
					              	"font" => array(
					              		"size" => 11,
					              		"bold" => true,
					              		"color" => array("rgb" => "000000")
					              	)
					              )
					          );						

				$objset->mergeCells("B".($jumlah_karyawan+$baris+1).":D".($jumlah_karyawan+$baris+1)."") 
							->getStyle("B".($jumlah_karyawan+$baris+1).":D".($jumlah_karyawan+$baris+1)."")
					        ->applyFromArray(
					              array(
					              	
					              	'alignment' => array(
										'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,
									),						              	
					              )
					          );

				$objset->mergeCells("B".($jumlah_karyawan+$baris+2).":D".($jumlah_karyawan+$baris+2)."") 
							->getStyle("B".($jumlah_karyawan+$baris+2).":D".($jumlah_karyawan+$baris+2)."")
					        ->applyFromArray(
					              array(
					              	
					              	'alignment' => array(
										'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,
									),						              	
					              )
					          );

				$objset ->getStyle("L".($jumlah_karyawan+$baris+1).":L".($jumlah_karyawan+$baris+1)."")
					    ->getNumberFormat()
					    ->setFormatCode("#,##0")
			            ->applyFromArray(
				              array(
				              	
				              	'alignment' => array(
									'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT,
								),
				              )
				          );

				$grand_total = $this->Payroll_model->get_comp_template_bulanan_lihat_thr($d->company_id,1,$thr_year);

				foreach($grand_total->result() as $g) {
				   
					$objset->setCellValue("L".($jumlah_karyawan+$baris+1)."", $g->net_salary);
					$objset->setCellValue("L".($jumlah_karyawan+$baris+2)."", $total_karyawan);
				}
				// Rename worksheet
				$objPHPExcel->getActiveSheet()->setTitle('Rekap THR Bulanan');
			
				$objPHPExcel->getActiveSheet()->getSheetView()->setZoomScale(75);

				// Set active sheet index to the first sheet, so Excel opens this as the first sheet
				$objPHPExcel->setActiveSheetIndex(0);
				
				// $objWriter = IOFactory::createWriter($objPHPExcel, 'Excel5');
				$objWriter = IOFactory::createWriter($objPHPExcel, 'Excel2007');
				$filename = 'Rekap THR Bulanan '.$company_name.' - '.$cek_tahun_thr;

				 //sesuaikan headernya
				ob_end_clean();

				header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
				header("Content-Disposition: attachment; filename=".$filename.".xlsx");
				
			    header("Cache-Control: no-store, no-cache, must-revalidate");
	    		header("Cache-Control: post-check=0, pre-check=0", false);
				
			    // Date in the past
			    header('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT');
			   
			    // HTTP/1.1
			    header("Pragma: no-cache");
				
				$objWriter->save('php://output');
				exit;
			} else {
				redirect('Excel');
			}
		}
	
	// ================================================================================
	// GAJI HARIAN
	// ================================================================================

		public function gaji_harian() 
		{
			$session = $this->session->userdata('username');
			if(empty($session)){ 
				redirect('admin/');
			}
			$data['title']         = 'Gaji Harian | '.$this->Core_model->site_title();
			$data['icon']          = '<i class="fa fa-money"></i>';
			$data['breadcrumbs']   = 'Gaji Harian';
			$data['path_url']      = 'finance_gaji_harian';
			
			$data['all_companies'] = $this->Company_model->get_company();
			
			$role_resources_ids = $this->Core_model->user_role_resource();
			if(in_array('1131',$role_resources_ids)) {
				$data['subview'] = $this->load->view("admin/finance/gaji_harian", $data, TRUE);
				$this->load->view('admin/layout/layout_main', $data); //page load
			} else {
				redirect('admin/dashboard');
			}
		}			
			
		// hourly_list > templates
		public function gaji_harian_list() 
		{
			$data['title'] = $this->Core_model->site_title();
			$session = $this->session->userdata('username');
			
			if(!empty($session)){ 			
				$this->load->view("admin/finance/gaji_harian", $data);			
			} else {			
				redirect('admin/');			
			}
			// Datatables Variables
			$draw   = intval($this->input->get("draw"));
			$start  = intval($this->input->get("start"));
			$length = intval($this->input->get("length"));
			
			// date and employee id/company id
			$start_date = $this->input->get("start_date");	
			$end_date   = $this->input->get("end_date");	

			$role_resources_ids = $this->Core_model->user_role_resource();
			
			$user_info          = $this->Core_model->read_user_info($session['user_id']);
			
			// $history = $this->Payroll_model->cek_jumlah_gaji($this->input->get("company_id"),$start_date,$end_date);	

			$history = $this->Payroll_model->get_company_payslip($this->input->get("company_id"));

			// echo "<pre>";
			// print_r($this->db->last_query());
			// echo "</pre>";
			// die();	
		   
			$system = $this->Core_model->read_setting_info(1);		
			$data   = array();
			$no     = 1;              

	        foreach($history->result() as $r) {

	        	$month_payment = date("d-m-Y", strtotime($start_date)).' s/d '.date("d-m-Y", strtotime($end_date));
				
				$employee_name = strtoupper($r->full_name);	

				// get company
				$company = $this->Core_model->read_company_info($r->company_id);
				if(!is_null($company)){
					$comp_name = $company[0]->name;
				} else {
					$comp_name = '--';	
				}

				// department
				$department = $this->Department_model->read_department_information($r->department_id);
				if(!is_null($department)){
					$department_name = $department[0]->department_name;
				} else {
					$department_name = '--';	
				}

				// designation
				$designation = $this->Designation_model->read_designation_information($r->designation_id);
				if(!is_null($designation)){
					$designation_name = $designation[0]->designation_name;
				} else {
					$designation_name = '--';	
				}

				$cek_gaji_harian = $this->Payroll_model->cek_jumlah_gaji($r->user_id,$start_date,$end_date); 
                if(!is_null($cek_gaji_harian)){
                   $gaji_harian = $cek_gaji_harian[0]->jumlah;                        
                } else {
                   $gaji_harian = '';
                }                          


                $cek_gaji_kerja = $this->Payroll_model->cek_jumlah_kerja($r->user_id,$start_date,$end_date); 
                if(!is_null($cek_gaji_kerja)){
                   $tanggal_kerja = date("d-m-Y",strtotime($cek_gaji_kerja[0]->start_date)).' s/d '.date("d-m-Y",strtotime($cek_gaji_kerja[0]->end_date));                        
                } else {
                   $tanggal_kerja = '';
                }  

                // get company
				if($r->user_id == '') {
					$ol = '<span class="blink blink-one"> 0  </span>';
				} else {
					$ol = '<ol style="margin-bottom: 0px !important;">';
					$employee = $this->Payroll_model->read_employee_kerja($r->user_id,$start_date,$end_date);
					foreach($employee->result() as $e) {
						if(!is_null($employee)){
							$ol .= '<li>'.date("d-m-Y",strtotime($e->start_date)).' s/d '.date("d-m-Y",strtotime($e->end_date)).' </li>';
						} else {
							$ol .= '--';	
						}			
					}
					 $ol .= '</ol>';
				}


				$rekening = $this->Employees_model->get_employee_bank_account_last($r->user_id);
          		if(!is_null($rekening)){
					$rekening_name = $rekening[0]->account_number;
					$bank_name = $rekening[0]->bank_name;
				} else {
					$rekening_name = '';	
					$bank_name = '--';
				}
				

		         $data[] = array(
						$no,
						$month_payment,
	                    $employee_name,
						$comp_name,
						strtoupper($department_name),
						strtoupper($designation_name),
						$this->Core_model->currency_sign($gaji_harian),
	                    $rekening_name,
	                    $bank_name, 
	                    $ol
	                );
	                $no++;
	        }

	        $output = array(
	            "draw" => $draw,
	            "recordsTotal" => $history->num_rows(),
	            "recordsFiltered" => $history->num_rows(),
	            "data" => $data
	        );
	        $this->output->set_output(json_encode($output));		         
	    }
	
	    public function export_slip_harian()
		{
			$data['title'] = $this->Core_model->site_title();
			$session = $this->session->userdata('username');
			
			if(!empty($session)){ 			
				$this->load->view("admin/finance/gaji_harian", $data);			
			} else {			
				redirect('admin/');			
			}

			$company_id   = $this->input->post('company');
			$month_year   = $this->input->post('month_year');
		

			$bulan_gaji         = $this->Payroll_model->check_bulan_gaji($month_year);	
			$cek_bulan_gaji     = $bulan_gaji[0]->desc;
			$cek_tanggal_gaji   = date("d-m-Y",strtotime($bulan_gaji[0]->start_date)).' s/d '.date("d-m-Y",strtotime($bulan_gaji[0]->end_date));

			$company = $this->Core_model->read_company_info($company_id);
			$company_name = $company[0]->name;			

			// echo "<pre>";
			// print_r($company);
			// print_r($month_year);				
			// echo "</pre>";
			// die;
			
			$role_resources_ids = $this->Core_model->user_role_resource();			
			$user_info          = $this->Core_model->read_user_info($session['user_id']);			
							
			$departemen = $this->Payroll_model->get_comp_template_dept_harian_slip($company_id);

			// $ambildata = $this->mod->get_attendance_to_excel($tgl1, $tgl2, $location_id, $status_id, $user_id);

			if (count($departemen) > 0) {

				$objPHPExcel = new PHPExcel();

	            // Set properties
				
				$objPHPExcel->getProperties()->setCreator("Nizar Basyrewan")
							 ->setLastModifiedBy("Nizar Basyrewan")
							 ->setTitle("Office 2007 XLSX Test Document")
							 ->setSubject("Office 2007 XLSX Test Document")
							 ->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")
							 ->setKeywords("office 2007 openxml php")
							 ->setCategory("Test result file");

				$objPHPExcel->setActiveSheetIndex(0)
				            ->mergeCells("B1:Y1")
				            ->getStyle("B1:B1")
				            ->applyFromArray(
					              array(
					              	'alignment' => array(
										'horizontal' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
									),
					              	"font" => array(
					              		"size" => 16,
					              		"bold" => true,
					              		"color" => array("rgb" => "363636")
					              	)
					              )
					          );

				$objPHPExcel->setActiveSheetIndex(0)
				            ->mergeCells("B2:Y2")
				            ->getStyle("B2:B2")
				            ->applyFromArray(
					              array(
					              	'alignment' => array(
										'horizontal' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
									),
					              	"font" => array(
					              		"size" => 14,
					              		"bold" => true,
					              		"color" => array("rgb" => "363636")
					              	)
					              )
					          );

				$objPHPExcel->setActiveSheetIndex(0)
				            ->mergeCells("B3:Y3")
				            ->getStyle("B3:B3")
				            ->applyFromArray(
					              array(
					              	'alignment' => array(
										'horizontal' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
									),
					              	"font" => array(
					              		"size" => 11,
					              		"bold" => true,
					              		"color" => array("rgb" => "363636")
					              	)
					              )
					          );
				
				

				$objPHPExcel->setActiveSheetIndex(0)->setCellValue("B1", $company_name);

				$objPHPExcel->setActiveSheetIndex(0)->setCellValue("B2", "REKAP GAJI BULANAN");

				$objPHPExcel->setActiveSheetIndex(0)->setCellValue("B3", $cek_bulan_gaji.' ('.$cek_tanggal_gaji.')');

				$objPHPExcel->getActiveSheet()->freezePane('E7');

				$objset = $objPHPExcel->setActiveSheetIndex(0); //inisiasi set object
				$objget = $objPHPExcel->getActiveSheet();  //inisiasi get object

				

				$objPHPExcel->getActiveSheet()->getStyle("B5:Y5")->applyFromArray(
					array(
						'fill' => array(
							'type' => PHPExcel_Style_Fill::FILL_SOLID,
							'color' => array('rgb' => 'CCC0DA')
						),
						'font' => array(
							'color' => array('rgb' => '363636')
						),
						'borders' => array(
					          'allborders' => array(
					              'style' => PHPExcel_Style_Border::BORDER_THIN
					          )
					    )
					)
				);




				$objPHPExcel->getActiveSheet()->getStyle("B6:Y6")->applyFromArray(
					array(
						'fill' => array(
							'type' => PHPExcel_Style_Fill::FILL_SOLID,
							'color' => array('rgb' => 'CCC0DA')
						),
						'font' => array(
							'color' => array('rgb' => '363636')
						),
						'borders' => array(
					          'allborders' => array(
					              'style' => PHPExcel_Style_Border::BORDER_THIN
					        )
					    )
					)
				);		
				
				// setting kolom rata
				$objPHPExcel->getActiveSheet()->getStyle('B7:B2560')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
				$objPHPExcel->getActiveSheet()->getStyle('C7:C2560')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
				$objPHPExcel->getActiveSheet()->getStyle('D7:D2560')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
				$objPHPExcel->getActiveSheet()->getStyle('E7:E2560')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
				$objPHPExcel->getActiveSheet()->getStyle('F7:F2560')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);				
				$objPHPExcel->getActiveSheet()->getStyle('G7:G2560')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
				$objPHPExcel->getActiveSheet()->getStyle('H7:H2560')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

				$objPHPExcel->getActiveSheet()->getStyle('B5:Y5000')->getAlignment()->setWrapText(true);
				
				// Kanan
			    	
				$objPHPExcel->setActiveSheetIndex(0)->getStyle("I7:I2560")->getNumberFormat()->setFormatCode("#,##0")->applyFromArray(array('alignment' => array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT)));			    
				$objPHPExcel->setActiveSheetIndex(0)->getStyle("J7:J2560")->getNumberFormat()->setFormatCode("#,##0")->applyFromArray(array('alignment' => array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT)));			
				$objPHPExcel->setActiveSheetIndex(0)->getStyle("K7:K2560")->getNumberFormat()->setFormatCode("#,##0")->applyFromArray(array('alignment' => array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT)));			
				$objPHPExcel->setActiveSheetIndex(0)->getStyle("L7:L2560")->getNumberFormat()->setFormatCode("#,##0")->applyFromArray(array('alignment' => array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT)));			    
				$objPHPExcel->setActiveSheetIndex(0)->getStyle("M7:M2560")->getNumberFormat()->setFormatCode("#,##0")->applyFromArray(array('alignment' => array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT)));			
				$objPHPExcel->setActiveSheetIndex(0)->getStyle("N7:N2560")->getNumberFormat()->setFormatCode("#,##0")->applyFromArray(array('alignment' => array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT)));						   
				$objPHPExcel->setActiveSheetIndex(0)->getStyle("O7:O2560")->getNumberFormat()->setFormatCode("#,##0")->applyFromArray(array('alignment' => array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT)));			    
				$objPHPExcel->setActiveSheetIndex(0)->getStyle("P7:P2560")->getNumberFormat()->setFormatCode("#,##0")->applyFromArray(array('alignment' => array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT)));			
				$objPHPExcel->setActiveSheetIndex(0)->getStyle("Q7:Q2560")->getNumberFormat()->setFormatCode("#,##0")->applyFromArray(array('alignment' => array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT)));			
			    $objPHPExcel->setActiveSheetIndex(0)->getStyle("R7:R2560")->getNumberFormat()->setFormatCode("#,##0")->applyFromArray(array('alignment' => array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT)));			    
				$objPHPExcel->setActiveSheetIndex(0)->getStyle("S7:S2560")->getNumberFormat()->setFormatCode("#,##0")->applyFromArray(array('alignment' => array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT)));			
				$objPHPExcel->setActiveSheetIndex(0)->getStyle("T7:T2560")->getNumberFormat()->setFormatCode("#,##0")->applyFromArray(array('alignment' => array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT)));			
				$objPHPExcel->setActiveSheetIndex(0)->getStyle("U7:U2560")->getNumberFormat()->setFormatCode("#,##0")->applyFromArray(array('alignment' => array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT)));		
				$objPHPExcel->setActiveSheetIndex(0)->getStyle("V7:V2560")->getNumberFormat()->setFormatCode("#,##0")->applyFromArray(array('alignment' => array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT)));		
				$objPHPExcel->setActiveSheetIndex(0)->getStyle("W7:W2560")->getNumberFormat()->setFormatCode("#,##0")->applyFromArray(array('alignment' => array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT)));		
				$objPHPExcel->setActiveSheetIndex(0)->getStyle("X7:X2560")->getNumberFormat()->setFormatCode("#,##0")->applyFromArray(array('alignment' => array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT)));
				
				$objPHPExcel->getActiveSheet()->getStyle('Y7:Y2560')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

				// Merge
				$objPHPExcel->getActiveSheet()->mergeCells('B5:B6');
				$objPHPExcel->getActiveSheet()->getStyle('B5')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

				$objPHPExcel->getActiveSheet()->mergeCells('C5:C6');
				$objPHPExcel->getActiveSheet()->getStyle('C5')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

				$objPHPExcel->getActiveSheet()->mergeCells('D5:D6');
				$objPHPExcel->getActiveSheet()->getStyle('D5')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

				$objPHPExcel->getActiveSheet()->mergeCells('E5:E6');
				$objPHPExcel->getActiveSheet()->getStyle('E5')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

				$objPHPExcel->getActiveSheet()->mergeCells('F5:F6');
				$objPHPExcel->getActiveSheet()->getStyle('F5')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

				$objPHPExcel->getActiveSheet()->mergeCells('G5:G6');
				$objPHPExcel->getActiveSheet()->getStyle('G5')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

				$objPHPExcel->getActiveSheet()->mergeCells('H5:H6');
				$objPHPExcel->getActiveSheet()->getStyle('H5')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

				$objPHPExcel->getActiveSheet()->mergeCells('I5:I6');
				$objPHPExcel->getActiveSheet()->getStyle('I5')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

				
				$objPHPExcel->getActiveSheet()->mergeCells('J5:O5');

				$objPHPExcel->getActiveSheet()->mergeCells('P5:W5');

				$objPHPExcel->getActiveSheet()->mergeCells('X5:X6');
				$objPHPExcel->getActiveSheet()->getStyle('X5')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

				$objPHPExcel->getActiveSheet()->mergeCells('Y5:Y6');
				$objPHPExcel->getActiveSheet()->getStyle('Y5')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);


	            //table header
				$cols = array("B", "C", "D", "E", "F", "G", "H", "I", "J", "K", "L", "M", "N", "O", "P", "Q", "R", "S", "T", "U", "V", "W", "X", "Y");

				$val = array("No", "Nama", 'Jabatan', "Status", "Grade", "Tanggal Kerja", "Masa Kerja", "Gaji Pokok","Penambah", "K", "L", "M", "N","O","Pengurang", "Q", "R", "S", "T", "U", "V", "W", "Total Gaji", "No Rekening");

				$style = array(
					'alignment' => array(
						'horizontal' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
					)
				);

			 

				$objPHPExcel->setActiveSheetIndex(0)->setCellValue( "J6", "Lembur");
				$objPHPExcel->getActiveSheet()->getStyle('J6')->applyFromArray($style);

				$objPHPExcel->setActiveSheetIndex(0)->setCellValue( "K6", "Tj.Jabatan");
				$objPHPExcel->getActiveSheet()->getStyle('K6')->applyFromArray($style);

				$objPHPExcel->setActiveSheetIndex(0)->setCellValue( "L6", "Tj.Produktifitas");
				$objPHPExcel->getActiveSheet()->getStyle('L6')->applyFromArray($style);

				$objPHPExcel->setActiveSheetIndex(0)->setCellValue( "M6", "Tj.Transportasi");
				$objPHPExcel->getActiveSheet()->getStyle('M6')->applyFromArray($style);

				$objPHPExcel->setActiveSheetIndex(0)->setCellValue( "N6", "Tj.Komunikasi");
				$objPHPExcel->getActiveSheet()->getStyle('N6')->applyFromArray($style);

				$objPHPExcel->setActiveSheetIndex(0)->setCellValue( "O6", "Insentif");
				$objPHPExcel->getActiveSheet()->getStyle('O6')->applyFromArray($style);				

				$objPHPExcel->setActiveSheetIndex(0)->setCellValue( "P6", "Pajak (PPh)");
				$objPHPExcel->getActiveSheet()->getStyle('P6')->applyFromArray($style);

				$objPHPExcel->setActiveSheetIndex(0)->setCellValue( "Q6", "Pinjaman");
				$objPHPExcel->getActiveSheet()->getStyle('Q6')->applyFromArray($style);

				$objPHPExcel->setActiveSheetIndex(0)->setCellValue( "R6", "BPJS Kes");
				$objPHPExcel->getActiveSheet()->getStyle('R6')->applyFromArray($style);

				$objPHPExcel->setActiveSheetIndex(0)->setCellValue( "S6", "BPJS TK");
				$objPHPExcel->getActiveSheet()->getStyle('S6')->applyFromArray($style);

				$objPHPExcel->setActiveSheetIndex(0)->setCellValue( "T6", "Jumlah Alpa");
				$objPHPExcel->getActiveSheet()->getStyle('T6')->applyFromArray($style);

				$objPHPExcel->setActiveSheetIndex(0)->setCellValue( "U6", "Potongan Alpa");
				$objPHPExcel->getActiveSheet()->getStyle('U6')->applyFromArray($style);

				$objPHPExcel->setActiveSheetIndex(0)->setCellValue( "V6", "Jumlah Izin");
				$objPHPExcel->getActiveSheet()->getStyle('V6')->applyFromArray($style);

				$objPHPExcel->setActiveSheetIndex(0)->setCellValue( "W6", "Potongan Izin");
				$objPHPExcel->getActiveSheet()->getStyle('W6')->applyFromArray($style);
				

				for ($a = 0; $a < 24; $a++) {
					
					$objset->setCellValue($cols[$a].'5', $val[$a]);
	                
	                // Setting lebar cell
	                $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(5);  // No
					$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(5);  // No
					$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(45); // Nama			
					$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(35); // Jabatan
					$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(10); // Status
					$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(10); // Grade
					$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(12); // Tanggal
					$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(12); // Masa Kerja
					$objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(15); // Gaji Pokok
					$objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(15); // Lembur
					$objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(15); // Tj.Jabatan
					$objPHPExcel->getActiveSheet()->getColumnDimension('L')->setWidth(15); // Tj.Produktifitas
					$objPHPExcel->getActiveSheet()->getColumnDimension('M')->setWidth(15); // Tj.Transportasi
					$objPHPExcel->getActiveSheet()->getColumnDimension('N')->setWidth(15); // Tj.Komunikasi
					$objPHPExcel->getActiveSheet()->getColumnDimension('O')->setWidth(15); // Insentif
					$objPHPExcel->getActiveSheet()->getColumnDimension('P')->setWidth(15); // Insentif
					$objPHPExcel->getActiveSheet()->getColumnDimension('Q')->setWidth(15); // Insentif
					$objPHPExcel->getActiveSheet()->getColumnDimension('R')->setWidth(15); // Insentif
					$objPHPExcel->getActiveSheet()->getColumnDimension('S')->setWidth(15); // Insentif
					$objPHPExcel->getActiveSheet()->getColumnDimension('T')->setWidth(15); // Insentif
					$objPHPExcel->getActiveSheet()->getColumnDimension('U')->setWidth(15); // Insentif
					
					$objPHPExcel->getActiveSheet()->getColumnDimension('V')->setWidth(15); // Insentif
					
					$objPHPExcel->getActiveSheet()->getColumnDimension('W')->setWidth(15); // Insentif

					$objPHPExcel->getActiveSheet()->getColumnDimension('X')->setWidth(15); // Insentif
					
					$objPHPExcel->getActiveSheet()->getColumnDimension('Y')->setWidth(15); // Insentif

					
					$style = array(
						'alignment' => array(
							'horizontal' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
						)
					);


					$objPHPExcel->getActiveSheet()->getStyle($cols[$a].'5')->applyFromArray($style);



				}

				
				$dep = 6;
				$baris = $dep+1;
				
				

				// $da = $baris+1;
				//$mo = $no+$baris;
				foreach($departemen->result() as $d) {

					$grouping = $this->Employees_model->get_employee_by_department_company($d->company_id,$d->department_id);
	          		if(!is_null($grouping)){
						$jumlah_karyawan = $grouping[0]->jumlah;
					} else {
						$jumlah_karyawan = '';	
					}	

					// $objset->setCellValue("B".$baris, ); 
					$objset->setCellValue("B".($baris+1)."", $d->department_name.', Jumlah  : '.$jumlah_karyawan.' Karyawan'); 

					$objset ->getStyle("B".($baris+1).":Y".($baris+1)."")
				            ->applyFromArray(
					              array(
					              	'fill' => array(
										'type' => PHPExcel_Style_Fill::FILL_SOLID,
										'color' => array('rgb' => 'B8CCE4')
									),
					              	
					              	"font" => array(
					              		"size" => 11,
					              		"bold" => true,
					              		"color" => array("rgb" => "000000")
					              	),

					              	'borders' => array(
								          'allborders' => array(
								              'style' => PHPExcel_Style_Border::BORDER_THIN
								          )
								    )
					              )
					          );

				    $objset ->getStyle("B".($jumlah_karyawan+$baris+2).":Y".($jumlah_karyawan+$baris+2)."")
				            ->applyFromArray(
					              array(
					              	'fill' => array(
										'type' => PHPExcel_Style_Fill::FILL_SOLID,
										'color' => array('rgb' => 'B8CCE4')
									),		
									'borders' => array(
								          'allborders' => array(
								              'style' => PHPExcel_Style_Border::BORDER_THIN
								        )
								    ),			              	
					              	"font" => array(
					              		"size" => 11,
					              		"bold" => true,
					              		"color" => array("rgb" => "000000")
					              	)
					              )
					          );
				

					$objset ->mergeCells("B".($baris+1).":Y".($baris+1)."") 
							->getStyle("B".($baris+1).":Y".($baris+1)."")
					        ->applyFromArray(
					              array(
					              	
					              	'alignment' => array(
										'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,
									),
									"font" => array(
					              		"size" => 11,
					              		"bold" => true,
					              		"color" => array("rgb" => "000000")
					              	)
					              	
					              )
					          );
					
					$objset ->mergeCells("B".($jumlah_karyawan+$baris+2).":H".($jumlah_karyawan+$baris+2)."") 
							->getStyle("B".($jumlah_karyawan+$baris+2).":H".($jumlah_karyawan+$baris+2)."")
					        ->applyFromArray(
					              array(
					              	
					              	'alignment' => array(
										'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,
									),
									"font" => array(
					              		"size" => 11,
					              		"bold" => true,
					              		"color" => array("rgb" => "000000")
					              	)
					              	
					              )
					          );

					$objset ->getStyle("I".($jumlah_karyawan+$baris+2).":X".($jumlah_karyawan+$baris+2)."")
					    ->getNumberFormat()
					    ->setFormatCode("#,##0")
			            ->applyFromArray(
				              array(
				              	
				              	'alignment' => array(
									'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT,
								),
				              	
				              )
				          );
					
					$objset->setCellValue("B".($jumlah_karyawan+$baris+2)."", 'Total ');

		            $objset->setCellValue("I".($jumlah_karyawan+$baris+2)."", "=SUM(I".($baris+2).":I".($jumlah_karyawan+$baris+1).")");
					$objset->setCellValue("J".($jumlah_karyawan+$baris+2)."", "=SUM(J".($baris+2).":J".($jumlah_karyawan+$baris+1).")");
					$objset->setCellValue("K".($jumlah_karyawan+$baris+2)."", "=SUM(K".($baris+2).":K".($jumlah_karyawan+$baris+1).")");
					$objset->setCellValue("L".($jumlah_karyawan+$baris+2)."", "=SUM(L".($baris+2).":L".($jumlah_karyawan+$baris+1).")");
					$objset->setCellValue("M".($jumlah_karyawan+$baris+2)."", "=SUM(M".($baris+2).":M".($jumlah_karyawan+$baris+1).")");
					$objset->setCellValue("N".($jumlah_karyawan+$baris+2)."", "=SUM(N".($baris+2).":N".($jumlah_karyawan+$baris+1).")");
					$objset->setCellValue("O".($jumlah_karyawan+$baris+2)."", "=SUM(O".($baris+2).":O".($jumlah_karyawan+$baris+1).")");
					$objset->setCellValue("P".($jumlah_karyawan+$baris+2)."", "=SUM(P".($baris+2).":P".($jumlah_karyawan+$baris+1).")");
					$objset->setCellValue("Q".($jumlah_karyawan+$baris+2)."", "=SUM(Q".($baris+2).":Q".($jumlah_karyawan+$baris+1).")");
					$objset->setCellValue("R".($jumlah_karyawan+$baris+2)."", "=SUM(R".($baris+2).":R".($jumlah_karyawan+$baris+1).")");
					$objset->setCellValue("S".($jumlah_karyawan+$baris+2)."", "=SUM(S".($baris+2).":S".($jumlah_karyawan+$baris+1).")");
					$objset->setCellValue("T".($jumlah_karyawan+$baris+2)."", "=SUM(T".($baris+2).":T".($jumlah_karyawan+$baris+1).")");
					$objset->setCellValue("U".($jumlah_karyawan+$baris+2)."", "=SUM(U".($baris+2).":U".($jumlah_karyawan+$baris+1).")");
					$objset->setCellValue("V".($jumlah_karyawan+$baris+2)."", "=SUM(V".($baris+2).":V".($jumlah_karyawan+$baris+1).")");
					$objset->setCellValue("W".($jumlah_karyawan+$baris+2)."", "=SUM(W".($baris+2).":W".($jumlah_karyawan+$baris+1).")");
					$objset->setCellValue("X".($jumlah_karyawan+$baris+2)."", "=SUM(X".($baris+2).":X".($jumlah_karyawan+$baris+1).")");
					
					// $objset->setCellValue("B".($jumlah_karyawan+$baris+3)."", '');

			        $payslip = $this->Payroll_model->get_comp_template_bulanan_dep_lihat($d->company_id,1,$month_year,$d->department_id);

			        $no = 1;
			        $baris =  $baris+2;
			        foreach($payslip->result() as $r) {

			                // ====================================================================================================================
							// DATA KARYAWAN 
							// ====================================================================================================================
								
								// Karyawan ID
								$emp_id = $r->employee_id;

								$user_info = $this->Core_model->read_user_info( $emp_id );			
								$emp_nik   = $user_info[0]->employee_id;
								$full_name = $user_info[0]->first_name.' '.$user_info[0]->last_name;

							// ====================================================================================================================
							// DATA KARYAWAN 
							// ====================================================================================================================
								// $rekening = $this->Employees_model->get_employee_bank_account_last($r->employee_id);
				    //       		if(!is_null($rekening)){
								// 	$rekening_name = $rekening[0]->account_number;
								// } else {
								// 	$rekening_name = '';	
								// }			

								$rekening_name = $r->rekening_name;		

									$bank_name = $r->bank_name;			

								$cek_karyawan_status = $user_info[0]->emp_status;
								
								if($cek_karyawan_status !=''){
									$karyawan_status     = $cek_karyawan_status;
								} else {
									$karyawan_status     = '';	
								}

								// Karyawan Departemen
								$department = $this->Department_model->read_department_information($r->department_id);
								if(!is_null($department)){
									$department_name = $department[0]->department_name;
								} else {
									$department_name = '';		
								}

								// Karyawan Posisi
								$designation = $this->Designation_model->read_designation_information($r->designation_id);
								if(!is_null($designation)){
									$designation_name = $designation[0]->designation_name;
								} else {
									$designation_name = '';	
								}

								$jabatan = $designation_name;

								// Karyawan Masa kerja														       	  
						        date_default_timezone_set("Asia/Jakarta");     
				                
				                $doj = $r->doj;

				                $tanggal1 = new DateTime($r->doj);
								$tanggal2 = new DateTime();
				          		
				          		if ($tanggal2->diff($tanggal1)->y == 0) {
				          			$selisih = $tanggal2->diff($tanggal1)->m.' bln';
				          		} else {
				          			$selisih = $tanggal2->diff($tanggal1)->y.' thn'.' '.$tanggal2->diff($tanggal1)->m.' bln';
				          		}

				          		// Karyawan Status
								$emp_status =  $this->Employees_model->read_employee_contract_information2($r->employee_id);
								if(!is_null($emp_status)){
									$emp_status_name = $emp_status[0]->name_type;
								} else {
									$emp_status_name = '';	
								}						

									// grade
								$grade_type = $this->Core_model->read_user_jenis_grade($user_info[0]->grade_type);
								
								if(!is_null($grade_type)){
									$jenis_grade       = $grade_type[0]->jenis_grade_keterangan;
									$jenis_grade_warna = $grade_type[0]->warna;
								} else {
									$jenis_grade       = '';
									$jenis_grade_warna = '';
								}
						
			                
			                //pemanggilan sesuaikan dengan nama kolom tabel
							$objset->setCellValue("B" . $baris, $no); 																		// No
							$objset->setCellValue("C" . $baris, $full_name); 																// Nama Karyawan				
							$objset->setCellValue("D" . $baris, $jabatan); 														// Jabatan
							$objset->setCellValue("E" . $baris, $emp_status_name); 															// Status
							$objset->setCellValue("F" . $baris, $jenis_grade); 																// Grade
							$objset->setCellValue("G" . $baris, $doj); 																		// Tanggal Mulai Kerja
							$objset->setCellValue("H" . $baris, $selisih); 																	// Masa Kerja
							$objset->setCellValue("I" . $baris, $r->basic_salary); 						// Gaji Pokok
							$objset->setCellValue("J" . $baris, $r->overtime_amount); 																		        // Gaji Pokok
							$objset->setCellValue("K" . $baris, $r->jumlah_tunj_jabatan); 																		        // Gaji Poko
							$objset->setCellValue("L" . $baris, $r->jumlah_tunj_produktifitas); 																		        // Gaji Poko
							$objset->setCellValue("M" . $baris, $r->jumlah_tunj_transportasi); 																		        // Gaji Poko
							$objset->setCellValue("N" . $baris, $r->jumlah_tunj_komunikasi); 		
							$objset->setCellValue("O" . $baris, $r->commissions_amount); 
							$objset->setCellValue("P" . $baris, $r->other_payments_amount);
							$objset->setCellValue("Q" . $baris, $r->loan_de_amount); 
							$objset->setCellValue("R" . $baris, $r->bpjs_kes_amount); 																		        // Gaji Poko
			                $objset->setCellValue("S" . $baris, $r->bpjs_tk_amount);
			                $objset->setCellValue("T" . $baris, $r->jumlah_alpa); 
			                $objset->setCellValue("U" . $baris, $r->potongan_alpa); 
			                $objset->setCellValue("V" . $baris, $r->jumlah_izin); 
			                $objset->setCellValue("W" . $baris, $r->potongan_izin); 
			                $objset->setCellValue("X" . $baris, $r->net_salary); 

			                $objset->setCellValue("Y" . $baris, $r->rekening_name); 
			               
							$no++;
							$baris++;
					}
					
					$dep++;
				}

				$total_emp = $this->Employees_model->get_employee_by_company($d->company_id);
	          		if(!is_null($grouping)){
						$total_karyawan = $total_emp[0]->jumlah;
					} else {
						$total_karyawan = '';	
					}	
				
				$objset->setCellValue("B".($jumlah_karyawan+$baris)."", 'Grand Total '.$total_karyawan.' Karyawan');

				$objset ->getStyle("B".($jumlah_karyawan+$baris).":Y".($jumlah_karyawan+$baris)."")
				            ->applyFromArray(
					              array(
					              	'fill' => array(
										'type' => PHPExcel_Style_Fill::FILL_SOLID,
										'color' => array('rgb' => 'B8CCE4')
									),		
									'borders' => array(
								          'allborders' => array(
								              'style' => PHPExcel_Style_Border::BORDER_THIN
								        )
								    ),			              	
					              	"font" => array(
					              		"size" => 11,
					              		"bold" => true,
					              		"color" => array("rgb" => "000000")
					              	)
					              )
					          );
				

				$objset ->mergeCells("B".($jumlah_karyawan+$baris).":H".($jumlah_karyawan+$baris)."") 
							->getStyle("B".($jumlah_karyawan+$baris).":H".($jumlah_karyawan+$baris)."")
					        ->applyFromArray(
					              array(
					              	
					              	'alignment' => array(
										'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,
									),						              	
					              )
					          );

				$objset ->getStyle("I".($jumlah_karyawan+$baris).":X".($jumlah_karyawan+$baris)."")
					    ->getNumberFormat()
					    ->setFormatCode("#,##0")
			            ->applyFromArray(
				              array(
				              	
				              	'alignment' => array(
									'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT,
								),
				              )
				          );

				$grand_total = $this->Payroll_model->get_comp_template_bulanan_lihat($d->company_id,1,$month_year);

				foreach($grand_total->result() as $g) {
				    $objset->setCellValue("I".($jumlah_karyawan+$baris)."", $g->ga_pok);
					$objset->setCellValue("J".($jumlah_karyawan+$baris)."", $g->lembur);
					$objset->setCellValue("K".($jumlah_karyawan+$baris)."", $g->tj_jabatan);
					$objset->setCellValue("L".($jumlah_karyawan+$baris)."", $g->tj_produktifitas);
					$objset->setCellValue("M".($jumlah_karyawan+$baris)."", $g->tj_transportasi);
					$objset->setCellValue("N".($jumlah_karyawan+$baris)."", $g->tj_komunikasi);
					$objset->setCellValue("O".($jumlah_karyawan+$baris)."", $g->insentif);
					$objset->setCellValue("P".($jumlah_karyawan+$baris)."", $g->pph);
					$objset->setCellValue("Q".($jumlah_karyawan+$baris)."", $g->pinjaman);
					$objset->setCellValue("R".($jumlah_karyawan+$baris)."", $g->bpjs_kes);
					$objset->setCellValue("S".($jumlah_karyawan+$baris)."", $g->bpjs_tk);
					$objset->setCellValue("T".($jumlah_karyawan+$baris)."", $g->jum_alpa);
					$objset->setCellValue("U".($jumlah_karyawan+$baris)."", $g->potongan_alpa);
					$objset->setCellValue("V".($jumlah_karyawan+$baris)."", $g->jum_izin);
					$objset->setCellValue("W".($jumlah_karyawan+$baris)."", $g->potongan_izin);
					$objset->setCellValue("X".($jumlah_karyawan+$baris)."", $g->net_salary);
				}
				// Rename worksheet
				$objPHPExcel->getActiveSheet()->setTitle('Rekap Gaji Bulanan');
			
				$objPHPExcel->getActiveSheet()->getSheetView()->setZoomScale(75);

				// Set active sheet index to the first sheet, so Excel opens this as the first sheet
				$objPHPExcel->setActiveSheetIndex(0);
				
				// $objWriter = IOFactory::createWriter($objPHPExcel, 'Excel5');
				$objWriter = IOFactory::createWriter($objPHPExcel, 'Excel2007');
				$filename = 'Rekap Gaji Bulanan '.$company_name.' - '.$cek_bulan_gaji;

				 //sesuaikan headernya
				ob_end_clean();

				header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
				header("Content-Disposition: attachment; filename=".$filename.".xlsx");
				
			    header("Cache-Control: no-store, no-cache, must-revalidate");
	    		header("Cache-Control: post-check=0, pre-check=0", false);
				
			    // Date in the past
			    header('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT');
			   
			    // HTTP/1.1
			    header("Pragma: no-cache");
				
				$objWriter->save('php://output');
				exit;
			} else {
				redirect('Excel');
			}
		}			
   
}
