<?php
defined('BASEPATH') OR exit('No direct script access allowed');

if(isset($_GET['jd']) && isset($_GET['employee_id']) && $_GET['data']=='payment' && $_GET['type']=='monthly_payment'){ 

?>

		<?php

			$pay_date = $this->input->get('pay_date');
						
			// Tanggal Gaji
			$tanggal       = $this->Timesheet_model->read_tanggal_information($pay_date);
			if(!is_null($tanggal)){
				$start_date    = $tanggal[0]->start_date;
				$end_date      = $tanggal[0]->end_date;
				$month_date    = $tanggal[0]->desc;
			} else {					
				$start_date    = '';
				$end_date      = '';
				$month_date    = '';				
			}		

			$system = $this->Core_model->read_setting_info(1);
			$payment_month = strtotime($this->input->get('pay_date'));
			$p_month = date('F Y',$payment_month);
			if($wages_type==1){				
				$basic_salary = $basic_salary;
				
			} 

			$employee_user_id = $this->input->get('employee_id');
			$employee = $this->Core_model->read_user_info($this->input->get('employee_id'));
			if(!is_null($employee)){

				$employee_user_id           = $employee[0]->user_id;
				$employee_grade_type        = $employee[0]->grade_type;
				$employee_wages_type        = $employee[0]->wages_type;
				$employee_payment_type      = $employee[0]->payment_type;
				$employee_name              = $employee[0]->first_name.' '.$employee[0]->last_name;

				$employee_company_id        = $employee[0]->company_id;
				$employee_location_id       = $employee[0]->location_id;
				$employee_department_id     = $employee[0]->department_id;
				$employee_designation_id    = $employee[0]->designation_id;
				$employee_emp_status        = $employee[0]->emp_status;
				$employee_date_of_joining   = $employee[0]->date_of_joining;
				$employee_basic_salary      = $employee[0]->basic_salary;
				$employee_flag              = $employee[0]->flag;

			} else {
				$employee_user_id           = '';
				$employee_grade_type        = '';
				$employee_wages_type        = '';
				$employee_payment_type      = '';
				$employee_name              = '';

				$employee_company_id        = '';
				$employee_location_id       = '';
				$employee_department_id     = '';
				$employee_designation_id    = '';
				$employee_emp_status        = '';
				$employee_date_of_joining   = '';
				$employee_basic_salary      = '';
				$employee_flag              = '';
			}

			// Rekening
			$rekening = $this->Employees_model->get_employee_bank_account_last($employee_user_id);
      		if(!is_null($rekening)){
				$rekening_name = $rekening[0]->account_number;
				$bank_name = $rekening[0]->bank_name;
			} else {
				$rekening_name = '';	
				$bank_name      = '';
			}
		?>

		<?php

			// ====================================================================================================================
			// KOMPONEN GAJI - TAMBAH 
			// ====================================================================================================================
				// ****************************************************************************************************************
				// TETAP
				// ****************************************************************************************************************
					// ============================================================================================================	
					// 1: salary type				
					// ============================================================================================================	
						// $wages_type = $this->lang->line('xin_payroll_full_tTime');				
						$basic_salary = $employee_basic_salary;
				
				 	// ============================================================================================================		
					// 2: Tunjangan
					// ============================================================================================================	
						
						// 1 - Tunj. Jabatan
						$salary_tunj_jabatan = $this->Employees_model->read_salary_allowances_jabatan($employee_user_id,$start_date);
						$count_tunj_jabatan  = $this->Employees_model->count_employee_allowances_jabatan($employee_user_id,$start_date);
						$jumlah_tunj_jabatan = 0;
						if($count_tunj_jabatan > 0) {
							foreach($salary_tunj_jabatan as $tunj_jabatan){
							  	$jumlah_tunj_jabatan += $tunj_jabatan->tnj_jabatan;
							}
						} else {
							$jumlah_tunj_jabatan = 0;
						}
						
						// 2 - Tunj. Produktifitas
						$salary_tunj_produktifitas = $this->Employees_model->read_salary_allowances_produktifitas($employee_user_id,$start_date);
						$count_tunj_produktifitas  = $this->Employees_model->count_employee_allowances_produktifitas($employee_user_id,$start_date);
						$jumlah_tunj_produktifitas = 0;
						if($count_tunj_produktifitas > 0) {
							foreach($salary_tunj_produktifitas as $tunj_produktifitas){							
							  $jumlah_tunj_produktifitas += $tunj_produktifitas->tnj_produktifitas;
							}
						} else {
							$jumlah_tunj_produktifitas = 0;
						}
						
						// 3 - Tunj. Transportasi
						$salary_tunj_transportasi = $this->Employees_model->read_salary_allowances_transportasi($employee_user_id,$start_date);
						$count_tunj_transportasi  = $this->Employees_model->count_employee_allowances_transportasi($employee_user_id,$start_date);
						$jumlah_tunj_transportasi = 0;
						if($count_tunj_transportasi > 0) {
							foreach($salary_tunj_transportasi as $tunj_transportasi){							
							  $jumlah_tunj_transportasi += $tunj_transportasi->tnj_transportasi;
							}
						} else {
							$jumlah_tunj_transportasi = 0;
						}

						// 4 - Tunj. Komunikasi
						$salary_tunj_komunikasi = $this->Employees_model->read_salary_allowances_komunikasi($employee_user_id,$start_date);
						$count_tunj_komunikasi  = $this->Employees_model->count_employee_allowances_komunikasi($employee_user_id,$start_date);
						$jumlah_tunj_komunikasi = 0;
						if($count_tunj_komunikasi > 0) {
							foreach($salary_tunj_komunikasi as $tunj_komunikasi){	
							  $jumlah_tunj_komunikasi += $tunj_komunikasi->tnj_komunikasi;
							}
						} else {
							$jumlah_tunj_komunikasi = 0;
						}

				// ****************************************************************************************************************
				// TIDAK TETAP
				// ****************************************************************************************************************
					// ============================================================================================================		
					// 1: Insentif
					// ============================================================================================================	
					
						$commissions       = $this->Employees_model->read_payroll_salary_commissions($employee_user_id,$start_date,$end_date);
						$count_commissions = $this->Employees_model->count_employee_commissions($employee_user_id,$start_date,$end_date);				
						$commissions_amount = 0;
						if($count_commissions > 0) {
							foreach($commissions as $sl_salary_commissions){
							  $commissions_amount += $sl_salary_commissions->commission_amount;
							}
						} else {
							$commissions_amount = 0;
						}

					// ============================================================================================================		
					// 2: Lembur
					// ============================================================================================================
						
						$salary_overtime = $this->Employees_model->read_payroll_salary_overtime($employee_user_id,$start_date,$end_date);
						$count_overtime = $this->Employees_model->count_payroll_employee_overtime($employee_user_id,$start_date,$end_date);
						$overtime_amount = 0;
						if($count_overtime > 0) {
							foreach($salary_overtime as $sl_overtime){														
								$overtime_amount += $sl_overtime->overtime_total;
							}
						} else {
							$overtime_amount = 0;
						}
			
			// ====================================================================================================================
			// KOMPONEN GAJI - KURANG 
			// ====================================================================================================================
				
				// ****************************************************************************************************************
				// TETAP
				// ****************************************************************************************************************
					// ============================================================================================================		
					// 1: BPJS TK
					// ============================================================================================================
					
						$count_bpjs_tk = $this->Employees_model->count_employee_bpjs_tk($employee_user_id,$start_date);
						$bpjs_tk = $this->Employees_model->set_employee_bpjs_tk($employee_user_id,$start_date);
						$bpjs_tk_amount = 0;
						if($count_bpjs_tk > 0) {
							foreach($bpjs_tk->result() as $sl_salary_bpjs_tk){							
								$bpjs_tk_amount += $sl_salary_bpjs_tk->deduction_amount;							
							}
						} else {
							$bpjs_tk_amount = 0;
						}

					// ============================================================================================================		
					// 2: BPJS KES
					// ============================================================================================================
					
						$count_bpjs_kes = $this->Employees_model->count_employee_bpjs_kes($employee_user_id,$start_date);
						$bpjs_kes = $this->Employees_model->set_employee_bpjs_kes($employee_user_id,$start_date);
						$bpjs_kes_amount = 0;
						if($count_bpjs_kes > 0) {
							foreach($bpjs_kes->result() as $sl_salary_bpjs_kes){
								$bpjs_kes_amount += $sl_salary_bpjs_kes->deduction_amount;							
							}
						} else {
							$bpjs_kes_amount = 0;
						}						

					// ============================================================================================================		
					// 3: PPH
					// ============================================================================================================

						$count_other_payments  = $this->Employees_model->count_employee_other_payments($employee_user_id);
						$other_payments        = $this->Employees_model->set_employee_other_payments($employee_user_id);
						$other_payments_amount = 0;
						if($count_other_payments > 0) {
							foreach($other_payments->result() as $sl_other_payments) {							
							  $other_payments_amount += $sl_other_payments->payments_amount;
							}
						} else {
							$other_payments_amount = 0;
						}

				// ****************************************************************************************************************
				// TIDAK TETAP
				// ****************************************************************************************************************

					// ============================================================================================================		
					// 1: Minus
					// ============================================================================================================	
					
						$minus       = $this->Employees_model->read_payroll_salary_minus($employee_user_id,$start_date,$end_date);
						$count_minus = $this->Employees_model->count_employee_minus($employee_user_id,$start_date,$end_date);				
						$potongan_lain = 0;
						if($count_minus > 0) {
							foreach($minus as $sl_salary_minus){
							  $potongan_lain += $sl_salary_minus->minus_amount;
							}
						} else {
							$potongan_lain = 0;
						}

					// ============================================================================================================		
					// 1: Pinjaman
					// ============================================================================================================
					
						$salary_loan_deduction = $this->Employees_model->read_salary_loan_deductions($employee_user_id,$start_date,$end_date);
						// echo "<pre>";
						// print_r($this->db->last_query());
						// echo "</pre>";
						// die();

						$count_loan_deduction  = $this->Employees_model->count_employee_deductions($employee_user_id,$start_date,$end_date);
						$loan_de_amount = 0;
						if($count_loan_deduction > 0) {
							foreach($salary_loan_deduction as $sl_salary_loan_deduction){
							  $loan_de_amount +=  $sl_salary_loan_deduction->loan_deduction_amount;
							}
						} else {
							$loan_de_amount = 0;
						}	
				
					// ============================================================================================================		
					// 2: Absen
					// ============================================================================================================
						
						if ($employee_flag == '1') {
							
							$jumlah_alpa   = 0;
							$potongan_alpa = 0;

							$jumlah_izin   = 0;
							$potongan_izin = 0;


							$jumlah_libur   = 0;
							$potongan_libur = 0;

							$potongan_absen = $potongan_alpa + $potongan_izin + $potongan_libur;

						} else if ($employee_flag == '0'){

							$cek_hadir     = $this->Timesheet_model->hitung_jumlah_status_kehadiran($employee_user_id,$start_date,$end_date,'H');
							$jumlah_hadir  = $cek_hadir[0]->jumlah;

							$jumlah_upah  = $basic_salary+$jumlah_tunj_jabatan;

							// ==========================================================================================================
							// CEK BM
							// ==========================================================================================================

								
								$cek_bm      = $this->Timesheet_model->hitung_jumlah_status_kehadiran($employee_user_id,$start_date,$end_date,'BM');
								$jumlah_bm   = $cek_bm[0]->jumlah;

				          		$hitung_bm   = ($jumlah_upah/26)*$jumlah_bm*1;
				          		$potongan_bm = round($hitung_bm,2);

							// ==========================================================================================================
							// CEK ALPA
							// ==========================================================================================================

								$cek_alpa      = $this->Timesheet_model->hitung_jumlah_status_kehadiran($employee_user_id,$start_date,$end_date,'A');
								$jumlah_alpa   = $cek_alpa[0]->jumlah;

				          		$hitung_alpa   = ($jumlah_upah/26)*$jumlah_alpa*1;
				          		$potongan_alpa = round($hitung_alpa,2);

							// ==========================================================================================================
							// CEK IZIN
							// ==========================================================================================================
							
								$cek_izin     = $this->Timesheet_model->hitung_jumlah_status_kehadiran($employee_user_id,$start_date,$end_date,'I');
								$jumlah_izin  = $cek_izin[0]->jumlah;
			          			
			          			$hitung_izin   = ($jumlah_upah/26)*$jumlah_izin*0.5;
			          			$potongan_izin = round($hitung_izin,2);
			          		
			          		// ==========================================================================================================
							// CEK LIBUR
							// ==========================================================================================================
							
								$cek_libur_kantor     = $this->Timesheet_model->hitung_jumlah_status_kehadiran($employee_user_id,$start_date,$end_date,'LK');
								$jumlah_libur         = $cek_libur_kantor[0]->jumlah;
			          			
			          			$hitung_libur_kantor  = ($jumlah_upah/26)*$jumlah_libur*1;
			          			$potongan_libur       = round($hitung_libur_kantor,2);
			          		
			          		// ==========================================================================================================
							//  Potongan
							// ==========================================================================================================
							
			          			$potongan_absen = $potongan_alpa + $potongan_bm + $potongan_izin + $potongan_libur;

							

						}  

			// ====================================================================================================================
			// HITUNG
			// ====================================================================================================================	
          		
          		$total_tunjangan = $jumlah_tunj_jabatan+$jumlah_tunj_produktifitas+$jumlah_tunj_transportasi+$jumlah_tunj_komunikasi;
				
				$total_upah       = $basic_salary+$total_tunjangan;
				
				$total_tambahan   = $overtime_amount+$commissions_amount;

				$total_tambah     = $total_upah+$total_tambahan;

				$total_bpjs       = $bpjs_kes_amount+$bpjs_tk_amount;

				$total_deduction  = $total_bpjs+$other_payments_amount+$loan_de_amount+$potongan_absen+$potongan_lain;
				
				$total_net_salary = ($total_upah+$total_tambahan) - $total_deduction;
			
				$net_salary       = number_format((float)$total_net_salary, 2, '.', '');
			// check

		?>

		<div class="modal-header">
		  <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">×</span> </button>
		  <h4 class="modal-title" id="edit-modal-data"> <i class="fa fa-money"></i> 
		  		Bayar Gaji Bulanan - Per Karyawan !
			</h4>
		</div>

		<div class="modal-body" style="overflow:auto; height:570px; ">
			
			<?php $attributes = array('name' => 'pay_monthly', 'id' => 'pay_monthly', 'autocomplete' => 'off', 'class'=>'m-b-1');?>
			<?php $hidden = array('_method' => 'ADD');?>
			<?php echo form_open('admin/payroll/add_pay_monthly/', $attributes, $hidden);?>

			<!-- 'employee_id'                  => $this->input->post('emp_id'), -->
			<input type="hidden" value="<?php echo $user_id;?>"                               name="emp_id" id="emp_id" >
			<!-- 'employee_name'                => $this->input->post('employee_name'), -->
			<input type="hidden" value="<?php echo $employee_name;?>"                         name="employee_name"/>
			<!-- 'department_id'                => $this->input->post('department_id'), -->
			<input type="hidden" value="<?php echo $employee_department_id;?>"                name="employee_department_id"/>
			<!-- 'doj'     					   => $employee_date_of_joining, -->
			<input type="hidden" value="<?php echo $employee_date_of_joining;?>"              name="employee_date_of_joining"/>
					<!-- 'company_id'                   => $employee_company_id, -->
			<input type="hidden" value="<?php echo $employee_company_id;?>"                   name="employee_company_id"/>
					<!-- 'location_id'                  => $employee_location_id, --> 
			<input type="hidden" value="<?php echo $employee_location_id;?>"                  name="employee_location_id"/>
					<!-- 'designation_id'               => $employee_designation_id, -->
			<input type="hidden" value="<?php echo $employee_designation_id;?>"               name="employee_designation_id"/>
					<!-- 'wages_type'                   => $employee_wages_type, -->
			<input type="hidden" value="<?php echo $employee_wages_type;?>"                   name="employee_wages_type"/>

			<input type="hidden" value="<?php echo $employee_payment_type;?>"                 name="employee_payment_type"/>

			<input type="hidden" value="<?php echo $employee_flag;?>"          			      name="employee_flag"/>
		
					<!-- 'salary_month'                 => $this->input->post('bmonth_year'), -->
			<input type="hidden" value="<?php echo $this->input->get('pay_date');?>"          name="salary_month"/>
					<!-- 'basic_salary'      		   => $basic_salary,								 -->
			<input type="hidden" value="<?php echo $basic_salary;?>"                          name="basic_salary">
					<!-- 'jumlah_tunj_jabatan'          => $jumlah_tunj_jabatan, -->
			<input type="hidden" value="<?php echo $jumlah_tunj_jabatan;?>"                   name="jumlah_tunj_jabatan">
					<!-- 'jumlah_tunj_produktifitas'    => $jumlah_tunj_produktifitas, -->
			<input type="hidden" value="<?php echo $jumlah_tunj_produktifitas;?>"             name="jumlah_tunj_produktifitas">
					<!-- 'jumlah_tunj_transportasi'     => $jumlah_tunj_transportasi, -->
			<input type="hidden" value="<?php echo $jumlah_tunj_transportasi;?>"              name="jumlah_tunj_transportasi">
					<!-- 'jumlah_tunj_komunikasi'       => $jumlah_tunj_komunikasi, -->
			<input type="hidden" value="<?php echo $jumlah_tunj_komunikasi;?>"                name="jumlah_tunj_komunikasi">
					<!-- 'total_upah'                   => $total_upah, -->
			<input type="hidden" value="<?php echo $total_upah;?>"                            name="total_upah">
					<!-- 'overtime_amount'      		   => $overtime_amount,								 -->
			<input type="hidden" value="<?php echo $overtime_amount;?>"                       name="overtime_amount">			
					<!-- 'commissions_amount'           => $commissions_amount, -->
			<input type="hidden" value="<?php echo $commissions_amount;?>"                    name="commissions_amount">			
					<!-- 'total_tambahan'               => $total_tambahan, -->
			<input type="hidden" value="<?php echo $total_tambahan;?>"                        name="total_tambahan">
					<!-- 'bpjs_kes_amount'              => $bpjs_kes_amount, -->
			<input type="hidden" value="<?php echo $bpjs_kes_amount;?>"                       name="bpjs_kes_amount">
					<!-- 'bpjs_tk_amount'               => $bpjs_tk_amount, -->
			<input type="hidden" value="<?php echo $bpjs_tk_amount;?>"                        name="bpjs_tk_amount">			
					<!-- 'other_payments_amount'        => $other_payments_amount, -->
			<input type="hidden" value="<?php echo $other_payments_amount;?>"                 name="other_payments_amount">
					<!-- 'loan_de_amount'               => $loan_de_amount, -->
			<input type="hidden" value="<?php echo $loan_de_amount;?>"                        name="loan_de_amount">
					<!-- 'jumlah_alpa'                  => $jumlah_alpa, -->
			<input type="hidden" value="<?php echo $jumlah_alpa;?>"                           name="jumlah_alpa">
					<!-- 'potongan_alpa'                => $potongan_alpa, -->
			<input type="hidden" value="<?php echo $potongan_alpa;?>"                         name="potongan_alpa">
					<!-- 'jumlah_izin'                  => $jumlah_izin, -->
			<input type="hidden" value="<?php echo $jumlah_izin;?>"                           name="jumlah_izin">
					<!-- 'potongan_izin'                => $potongan_izin, -->
			<input type="hidden" value="<?php echo $potongan_izin;?>"                         name="potongan_izin">
					<!-- 'jumlah libur'                => $potongan_izin, -->
			<input type="hidden" value="<?php echo $jumlah_libur;?>"                          name="jumlah_libur">
					<!-- 'potongan_libur'                => $potongan_izin, -->
			<input type="hidden" value="<?php echo $potongan_libur;?>"                        name="potongan_libur">
					<!-- 'potongan_absen'               => $potongan_absen, -->
			<input type="hidden" value="<?php echo $potongan_absen;?>"                        name="potongan_absen">

			<input type="hidden" value="<?php echo $potongan_lain;?>"                        name="potongan_lain">
					<!-- 'total_deduction'              => $total_deduction, -->
			<input type="hidden" value="<?php echo $total_deduction;?>"                       name="total_deduction">			
					<!-- 'net_salary'                   => $total_net_salary, -->
			<input type="hidden" value="<?php echo $total_net_salary;?>"                      name="total_net_salary">			
					<!-- 'rekening_name'                => $rekening_name, -->
			<input type="hidden" value="<?php echo $rekening_name;?>"                         name="rekening_name">
					<!-- 'bank_name'                => $bank_name, -->
			<input type="hidden" value="<?php echo $bank_name;?>"                             name="bank_name">
			<input type="hidden" value="<?php echo $month_date;?>"                            name="month_date">
			

		    <div class="row">
		        <div class="col-md-12">
		          <div class="box">
		          	<div class="box-header with-border box-hijau" >
		              <h3 class="box-title" style="font-size: 16px; font-weight: bold;">
		              	 <?php echo $employee_name ;?>
		               </h3>
		               <br>
		               <h5 class="box-title-sub">
		              	 <?php echo $designation_name;?> - <?php echo $department_name;?>
		              </h5>
		               <br>
		              <h5 class="box-title-sub-sub">
		              	Bulan Gaji : <?php echo $p_month;?>
		              </h5>
		            </div>
		            <div class="box-body">
		            	<div class="row">
		       				<div class="col-md-6">
		       					 <div class="table-responsive" data-pattern="priority-columns">
		       					 	
					                <table class="datatables-demo table table-striped  dataTable no-footer">
					                  <tbody>
					                    
					                     <tr>
					                      <td colspan="2"><strong><i class="fa fa-plus-circle"></i> Penghasilan :</strong> 
					                        
					                      </td>
					                    </tr>
					                    <tr>
					                      	<td><strong> Gaji Pokok </strong></td> 
					                      	<td>: <span class="pull-right"><?php echo  number_format($basic_salary, 0, ',', '.'); ?></span></td>                      
					                    </tr>
					                    
					                    <!-- Tunjangan -->                   
					                    <tr>
					                        <td><strong> Tunj. Jabatan </strong></td> 
					                        <td>: <span class="pull-right"><?php echo number_format($jumlah_tunj_jabatan, 0, ',', '.'); ?></span></td>
					                    </tr>

										 <tr>
					                        <td><strong> Tunj Produktifitas </strong></td> 
					                        <td>: <span class="pull-right"><?php echo number_format($jumlah_tunj_produktifitas, 0, ',', '.'); ?></span></td>
					                    </tr>

					                     <tr>
					                        <td><strong> Tunj Transportasi </strong></td> 
					                        <td>: <span class="pull-right"><?php echo number_format($jumlah_tunj_transportasi, 0, ',', '.'); ?></span></td>
					                    </tr>

					                     <tr>
					                        <td><strong> Tunj Komunikasi </strong></td> 
					                        <td>: <span class="pull-right"><?php echo number_format($jumlah_tunj_komunikasi, 0, ',', '.'); ?></span></td>
					                    </tr>

					                    <!-- Insentif -->                   
					                    <tr>
					                      	<td><strong> Insentif </td>
					                      	<td>: <span class="pull-right"><?php echo number_format($commissions_amount, 0, ',', '.'); ?></span></td>
					                    </tr>                                       

					                    <!-- Overtime -->                   
					                    <tr>
					                      	<td><strong> Lembur </strong></td> 
					                        <td>: <span class="pull-right"><?php echo number_format($overtime_amount, 0, ',', '.');?></span></td>
					                    </tr>

					                    <tr>
					                      	<td align="right"><strong> Total </strong></td> 
					                        <td>: <span class="pull-right"><?php echo number_format($total_tambah, 0, ',', '.');?></span></td>
					                    </tr>
					                    
					                    <tr>
					                     	<td colspan="2"> </td>
					                       
					                    </tr>
					                 
					                  </tbody>
					                </table>
					            </div>
		       				</div>
		       				<div class="col-md-6">

		       					 <div class="table-responsive" data-pattern="priority-columns">
			                <table class="datatables-demo table table-striped  dataTable no-footer">
			                  <tbody>
			                    

			                    <tr>
			                      <td colspan="2"><strong><i class="fa fa-minus-circle"></i> Pemotong :</strong> 
			                        
			                      </td>
			                    </tr>

			                    <!-- BPJS -->                 
			                    <tr>
			                      	<td><strong> BPJS Kes </strong></td> 
			                        <td>: <span class="pull-right"><?php echo number_format($bpjs_kes_amount, 0, ',', '.') ;?></span></td>
			                    </tr>

			                    <tr>
			                      	<td><strong> BPJS TK </strong></td> 
			                        <td>:  <span class="pull-right"><?php echo number_format($bpjs_tk_amount, 0, ',', '.') ;?></span></td>
			                    </tr>
			                    
			                     <!-- Pinjaman -->                   
			                    <tr>
			                      	<td><strong> Pinjaman </strong></td> 
			                        <td>: <span class="pull-right"><?php echo number_format($loan_de_amount, 0, ',', '.');?></span></td>
			                    </tr>

			                    <!-- Pajak -->
			                    <tr>
			                      	<td><strong> Pajak (PPh 21) </strong></td> 
			                        <td>:  <span class="pull-right"><?php echo number_format($other_payments_amount, 0, ',', '.');?></span></td>
			                    </tr>

			                    <!-- Potongan -->
			                    <tr>
			                      	<td><strong> Potongan Alpa </strong></td>
			                        <td>:  <span class="pull-right"><?php echo number_format($potongan_alpa, 0, ',', '.');?></span></td>
			                    </tr>

			                    <tr>
			                      	<td><strong> Potongan Izin </strong></td>
			                        <td>:  <span class="pull-right"><?php echo number_format($potongan_izin, 0, ',', '.');?></span></td>
			                    </tr>

			                    <tr>
			                      	<td><strong> Potongan Libur Kantor </strong></td>
			                        <td>:  <span class="pull-right"><?php echo number_format($potongan_libur, 0, ',', '.');?></span></td>
			                    </tr>


			                    <tr>
			                      	<td><strong> Potongan Lain </strong></td>
			                        <td>:  <span class="pull-right"><?php echo number_format($potongan_lain, 0, ',', '.');?></span></td>
			                    </tr>

			                    <tr>
			                      	<td align="right"><strong> Total </strong></td> 
			                        <td>: <span class="pull-right"><?php echo number_format($total_deduction, 0, ',', '.');?></span></td>
			                    </tr>
			                    
			                     <tr>
			                     <td colspan="2"> </td>
			                    </tr>
			                    
			                    <tr>
			                      <td><strong> Total THP </strong></td>
			                        <td>: <span class="pull-right text-bold"> <?php echo number_format($net_salary, 0, ',', '.');?></span></td>
			                    </tr>
			                   

			                 
			                  </tbody>
			                </table>
			            </div>

		       				</div>
		       			</div>
		              
			           
		            </div>

		          </div>
		        </div>
		    </div>

		   
		     
		    <div class="form-actions box-footer"> 
		    	<?php echo form_button(array('data-dismiss' => 'modal', 'type' => 'button', 'class' => $this->Core_model->form_button_close_class(), 'content' => '<i class="fa fa fa-check-square-o"></i> '.$this->lang->line('xin_close'))); ?> 
		    	<?php echo form_button(array('name' => 'hris_form', 'type' => 'submit', 'class' => $this->Core_model->form_button_class(), 'content' => '<i class="fa fa-save"></i> '.$this->lang->line('xin_pay'))); ?> 
		    </div>
		  <?php echo form_close(); ?>
		</div>

	</div>
	<script type="text/javascript">
	$(document).ready(function(){
		$('[data-plugin="select_hrm"]').select2($(this).attr('data-options'));
		$('[data-plugin="select_hrm"]').select2({ width:'100%' });
		
		// On page load: datatable					
		$("#pay_monthly").submit(function(e){
		
		/*Form Submit*/
		e.preventDefault();
			var obj = $(this), action = obj.attr('name');
			// $('#hrload-img').show();
			$('.save').prop('disabled', true);
			$.ajax({
				type: "POST",
				url: e.target.action,
				data: obj.serialize()+"&is_ajax=11&data=monthly&add_type=add_monthly_payment&form="+action,
				cache: false,
				success: function (JSON) {
					
					if (JSON.error != '') {
						// $('#hrload-img').hide();
						alert_fail('Gagal',JSON.error);
						$('input[name="csrf_hris"]').val(JSON.csrf_hash);
						$('.save').prop('disabled', false);
						
					
					} else {

						// $('#hrload-img').hide();

						$('.emo_monthly_pay').modal('toggle');

						var xin_table3   = $('#xin_table_bulanan').dataTable({
									
							       
				            "bDestroy"        : true,
				            "bSort"           : false,
				            "aLengthMenu"     : [[10, 30, 50, 100, -1], [10, 30, 50, 100, "All"]],      
				            autoWidth         : true,  
				            "fixedColumns"    : true,
				            "fixedColumns"    : {
				              leftColumns   : 7
				            }, 
							"ajax": {
								url : "<?php echo site_url("admin/payroll/payslip_list_bulanan") ?>?company_id=<?php echo $employee_company_id;?>&month_year=<?php echo $this->input->get('pay_date');?>",
								type : 'GET'
							},
							"columns": [
				                   {"name": "kolom_1", "className": "text-center"},
				                  {"name": "kolom_2", "className": "text-center"},
				                  {"name": "kolom_3", "className": "text-center"},
				                  {"name": "kolom_4", "className": "text-center"},
				                  {"name": "kolom_5", "className": "text-center"},
				                  {"name": "kolom_6", "className": "text-center"},
				                  {"name": "kolom_7", "className": "text-left"},
				                  {"name": "kolom_8", "className": "text-left"},
				                  {"name": "kolom_9", "className": "text-left"},
				                  {"name": "kolom_10", "className": "text-center"},
				                  {"name": "kolom_11", "className": "text-center"},
				                  {"name": "kolom_12", "className": "text-center"},
				                  {"name": "kolom_13", "className": "text-center"},                  
				                  {"name": "kolom_14", "className": "text-center"},
				                  {"name": "kolom_15", "className": "text-right"},
				                  {"name": "kolom_16", "className": "text-right"},
				                  {"name": "kolom_17", "className": "text-right"},
				                  {"name": "kolom_18", "className": "text-right"},
				                  {"name": "kolom_19", "className": "text-right"},
				                  {"name": "kolom_20", "className": "text-right"},
				                  {"name": "kolom_21", "className": "text-right"},
				                  {"name": "kolom_22", "className": "text-right"},
				                  {"name": "kolom_23", "className": "text-right"},
				                  {"name": "kolom_24", "className": "text-right"},                  
				                  {"name": "kolom_25", "className": "text-right"},
				                  {"name": "kolom_26", "className": "text-right"},
				                  {"name": "kolom_27", "className": "text-right"},
				                  {"name": "kolom_28", "className": "text-right"},
				                  {"name": "kolom_29", "className": "text-right"},    
				                  {"name": "kolom_30", "className": "text-right"},
				                  {"name": "kolom_31", "className": "text-right"},
				                  {"name": "kolom_32", "className": "text-right"},    
				                  {"name": "kolom_33", "className": "text-right"},
				                  {"name": "kolom_34", "className": "text-right"},
				                  {"name": "kolom_35", "className": "text-right"},        
				                  {"name": "kolom_36", "className": "text-center"},
				                  {"name": "kolom_37", "className": "text-center"},
				                  {"name": "kolom_38", "className": "text-left"}        
				              ],
						   "language": {
					            "aria": {
					                "sortAscending" : ": activate to sort column ascending",
					                "sortDescending": ": activate to sort column descending"
					            },
					            "emptyTable": "Tidak ada data yang tersedia pada tabel ini",
							    "info": "Menampilkan _START_ sampai _END_ dari _TOTAL_ entri",
							    "infoEmpty": "Menampilkan 0 sampai 0 dari 0 entri",
							    "infoFiltered": "(disaring dari _MAX_ entri keseluruhan)",
							    "lengthMenu": "Tampilkan _MENU_ entri",
							    "loadingRecords": "Silahkan Tunggu...",
							    "processing": "Sedang memproses...",
							     "search"      : "Pencarian : ", "searchPlaceholder": "Masukan Kata Pencarian ...",
							    "zeroRecords": "Tidak ditemukan data yang sesuai",
							    "thousands": "'",
							    "paginate": {
							        "first": "Pertama",
							        "last": "Terakhir",
							        "next": "Selanjutnya",
							        "previous": "Sebelumnya"
							    },
						    },
						    dom: 'lBfrtip',
							"buttons": ['excel'], // colvis > if needed
							
							"fnDrawCallback": function(settings){
								$('[data-toggle="tooltip"]').tooltip();          
							},
							
							"rowCallback": function(row, data, index) {	
												
								  $(row).find('td:eq(14)').css('background-color', '#eef7fa');
				              $(row).find('td:eq(14)').css('color', 'black');
				              $(row).find('td:eq(15)').css('background-color', '#eef7fa');
				              $(row).find('td:eq(15)').css('color', 'black');       
				              $(row).find('td:eq(16)').css('background-color', '#eef7fa');
				              $(row).find('td:eq(16)').css('color', 'black');
				              $(row).find('td:eq(17)').css('background-color', '#eef7fa');
				              $(row).find('td:eq(17)').css('color', 'black');
				              $(row).find('td:eq(18)').css('background-color', '#eef7fa');
				              $(row).find('td:eq(18)').css('color', 'black'); 
				              $(row).find('td:eq(19)').css('background-color', '#eef7fa');
				              $(row).find('td:eq(19)').css('color', 'black');
				              $(row).find('td:eq(20)').css('background-color', '#eef7fa');
				              $(row).find('td:eq(20)').css('color', 'black');

				              $(row).find('td:eq(21)').css('background-color', '#b7d8e3');
				              $(row).find('td:eq(21)').css('color', 'black');

				              $(row).find('td:eq(22)').css('background-color', '#f3eefa');
				              $(row).find('td:eq(22)').css('color', 'black');
				              $(row).find('td:eq(23)').css('background-color', '#f3eefa');
				              $(row).find('td:eq(23)').css('color', 'black');
				              $(row).find('td:eq(24)').css('background-color', '#f3eefa');
				              $(row).find('td:eq(24)').css('color', 'black');
				              $(row).find('td:eq(25)').css('background-color', '#f3eefa');
				              $(row).find('td:eq(25)').css('color', 'black');
				              $(row).find('td:eq(26)').css('background-color', '#f3eefa');
				              $(row).find('td:eq(26)').css('color', 'black');
				              $(row).find('td:eq(27)').css('background-color', '#f3eefa');
				              $(row).find('td:eq(27)').css('color', 'black');
				              $(row).find('td:eq(28)').css('background-color', '#f3eefa');
				              $(row).find('td:eq(28)').css('color', 'black');
				              $(row).find('td:eq(29)').css('background-color', '#f3eefa');
				              $(row).find('td:eq(29)').css('color', 'black');

				              $(row).find('td:eq(30)').css('background-color', '#f3eefa');
				              $(row).find('td:eq(30)').css('color', 'black');

				              $(row).find('td:eq(31)').css('background-color', '#f3eefa');
				              $(row).find('td:eq(31)').css('color', 'black');

				              $(row).find('td:eq(32)').css('background-color', '#f3eefa');
				              $(row).find('td:eq(32)').css('color', 'black');

				              $(row).find('td:eq(33)').css('background-color', '#c2aedd');
				              $(row).find('td:eq(33)').css('color', 'black');

				              // Total Potongan
				              $(row).find('td:eq(34)').css('background-color', '#ddf4e2');
				              $(row).find('td:eq(34)').css('color', 'black');
				              // THP
				              $(row).find('td:eq(35)').css('background-color', '#faf9ee');
				              $(row).find('td:eq(35)').css('color', 'black');              // iNFO
				              $(row).find('td:eq(36)').css('background-color', '#faf9ee');
				              $(row).find('td:eq(36)').css('color', 'black');
				              $(row).find('td:eq(37)').css('background-color', '#faf9ee');
				              $(row).find('td:eq(37)').css('color', 'black');
				              $(row).find('td:eq(38)').css('background-color', '#faf9ee');
				              $(row).find('td:eq(38)').css('color', 'black');
             
								
							}
						});
												
						xin_table3.api().ajax.reload(function(){ 
							alert_success('Sukses',JSON.result);
						}, true);

						$('input[name="csrf_hris"]').val(JSON.csrf_hash);
						// $('.icon-spinner3').hide();												
						$('.save').prop('disabled', false);
					}
				}
			});
		});
	});	
	</script>

<?php }?>
