<?php
defined('BASEPATH') OR exit('No direct script access allowed');

if(isset($_GET['jd']) && isset($_GET['employee_id']) && $_GET['data']=='payment' && $_GET['type']=='dayly_payment'){ 

?>

		<?php

			$start_date = $this->input->get('start_date');
			$end_date   = $this->input->get('end_date');
			
			$p_month  =   date("d-m-Y",strtotime($start_date)).' s/d '.date("d-m-Y",strtotime($end_date));
			

			$employee_user_id = $this->input->get('employee_id');
			$employee = $this->Core_model->read_user_info($this->input->get('employee_id'));
			if(!is_null($employee)){

				$employee_user_id           = $employee[0]->user_id;
				$employee_grade_type        = $employee[0]->grade_type;
				$employee_wages_type        = $employee[0]->wages_type;
				$employee_name              = $employee[0]->first_name.' '.$employee[0]->last_name;

				$employee_email             = $employee[0]->email;

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
				$employee_name              = '';

				$employee_email             = '';

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
				$bank_name     = $rekening[0]->bank_name;
			} else {
				$rekening_name = '';	
				$bank_name     = '';
			}

			
			$designation = $this->Designation_model->read_designation_workstation_information($employee_designation_id);
			if(!is_null($designation)){
				$workstation_id = $designation[0]->workstation_id;
				$workstation_nm = $designation[0]->workstation_name;
			} else {
				$workstation_id = '';
				$workstation_nm = '';	
			}
		?>

		<?php

			// ====================================================================================================================
			// KOMPONEN GAJI - TAMBAH 
			// ====================================================================================================================
				// ****************************************************************************************************************
				// TETAP
				// ****************************************************************************************************************
					$gapok       = $this->Employees_model->read_payroll_salary_gapok($employee_user_id,$end_date);
					$count_gapok = $this->Employees_model->count_employee_gapok($employee_user_id,$end_date);				
					$gapok_amount = 0;
					if($count_gapok > 0) {
						foreach($gapok as $sl_salary_gapok){
						  $gapok_amount += $sl_salary_gapok->gapok_amount;
						}
					} else {
						$gapok_amount = 0;
					}


					// ============================================================================================================	
					// 1: salary type				
					// ============================================================================================================	
						// $wages_type = $this->lang->line('xin_payroll_full_tTime');				
						// $basic_salary = $employee_basic_salary;			

						// Karyawan Masa kerja														       	  
				        date_default_timezone_set("Asia/Jakarta");     
		                
		                $tanggal1 = new DateTime($employee_date_of_joining);
						$tanggal2 = new DateTime($end_date);

						$datetime1 = new DateTime($employee_date_of_joining);
		  				$datetime2 = new DateTime($end_date);
		 				$difference = $datetime1->diff($datetime2);

		 				if ($difference->days >= 0 and $difference->days <= 31){

							$basic_salary = $gapok_amount;

						} else if ($difference->days > 31){

							if ($gapok_amount == 50000) {

								$basic_salary      = $gapok_amount+15000;
								$basic_salary_naik = $gapok_amount+15000;
							
							} else {

								$basic_salary      = $gapok_amount;
								$basic_salary_naik = $gapok_amount;

							}
						}	
		          		
		          		// $basic_salary      = $gapok_amount;
												
				 	
				// ****************************************************************************************************************
				// TIDAK TETAP
				// ****************************************************************************************************************
					// ============================================================================================================		
					// 1: Insentif
					// ============================================================================================================	
						$commissions_amount = 0;

						$commissions        = $this->Employees_model->read_payroll_salary_commissions($employee_user_id,$start_date,$end_date);
						$count_commissions  = $this->Employees_model->count_employee_commissions($employee_user_id,$start_date,$end_date);				
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
						$overtime_amount = 0;
						$overtime_amount_jam = 0;

						$salary_overtime = $this->Employees_model->read_payroll_salary_overtime($employee_user_id,$start_date,$end_date);
						$count_overtime = $this->Employees_model->count_payroll_employee_overtime($employee_user_id,$start_date,$end_date);
						$overtime_amount = 0;
						if($count_overtime > 0) {
							foreach($salary_overtime as $sl_overtime){														
								$overtime_amount += $sl_overtime->overtime_total;
								$overtime_amount_jam += $sl_overtime->overtime_hours_total;	
							}
						} else {
							$overtime_amount = 0;
							$overtime_amount_jam = 0;
						}

					// ============================================================================================================		
					// 3: Minus
					// ============================================================================================================	
					
						$minus       = $this->Employees_model->read_payroll_salary_minus($employee_user_id,$start_date,$end_date);
						$count_minus = $this->Employees_model->count_employee_minus($employee_user_id,$start_date,$end_date);				
						$minus_amount = 0;
						if($count_minus > 0) {
							foreach($minus as $sl_salary_minus){
							  $minus_amount += $sl_salary_minus->minus_amount;
							}
						} else {
							$minus_amount = 0;
						}
			
			// ====================================================================================================================
			// KOMPONEN GAJI - KURANG 
			// ====================================================================================================================
					// ============================================================================================================		
					// 1: BPJS TK
					// ============================================================================================================
					
						$count_bpjs_tk = $this->Employees_model->count_employee_bpjs_tk($employee_user_id,$end_date);
						$bpjs_tk = $this->Employees_model->set_employee_bpjs_tk($employee_user_id,$end_date);
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
					
						$count_bpjs_kes = $this->Employees_model->count_employee_bpjs_kes($employee_user_id,$end_date);
						$bpjs_kes = $this->Employees_model->set_employee_bpjs_kes($employee_user_id,$end_date);
						$bpjs_kes_amount = 0;
						if($count_bpjs_kes > 0) {
							foreach($bpjs_kes->result() as $sl_salary_bpjs_kes){
								$bpjs_kes_amount += $sl_salary_bpjs_kes->deduction_amount;							
							}
						} else {
							$bpjs_kes_amount = 0;
						}	

						
				// ****************************************************************************************************************
				// TIDAK TETAP
				// ****************************************************************************************************************
					// ============================================================================================================		
					// 2: Absen
					// ============================================================================================================

							$cek_hadir      = $this->Timesheet_model->hitung_jumlah_status_kehadiran($employee_user_id,$start_date,$end_date,'H');

							$cek_lembur     = $this->Timesheet_model->hitung_jumlah_status_kehadiran($employee_user_id,$start_date,$end_date,'O');			

							$cek_lembur_libur   = $this->Timesheet_model->hitung_jumlah_status_kehadiran_libur($employee_user_id,$start_date,$end_date,'O');				
						
							$cek_izin       = $this->Core_model->read_user_izin_jumlah($employee_user_id,$start_date,$end_date);

							// user full name
							if(!is_null($cek_izin))
							{
								$cek_jum_izin   = $cek_izin[0]->jumlah;
								
							} else 
							{
								$cek_jum_izin   = '';										
							}

							if ($cek_jum_izin == '' || $cek_jum_izin == 0)
							{
								$jum_izin   = 0;
							} else {
								$jum_izin   = $cek_jum_izin;
							}
									
							
							// +$cek_lembur[0]->jumlah+$jum_izin
							$jumlah_hadir   = $cek_hadir[0]->jumlah+$cek_lembur[0]->jumlah+$jum_izin-($cek_lembur_libur[0]->jumlah);

							$jumlah_upah   = $basic_salary*$jumlah_hadir;


			// ====================================================================================================================
			// HITUNG
			// ====================================================================================================================	
          		
          		$total_gaji        = $jumlah_upah;

				$jumlah_overtime   = $overtime_amount_jam;
				$total_lembur      = $overtime_amount;

				$tanggal_awal       = date("Y-m-d",strtotime($start_date));
          		$tanggal_akhir      = date("Y-m-d",strtotime($end_date));
          		$tanggal_potong     = date("Y-m-20",strtotime($start_date));

          		if ($tanggal_potong >= $tanggal_awal and $tanggal_potong <= $tanggal_akhir ){

          			$pot_bpjs_kes_amount = $bpjs_kes_amount;

          		} else {

          			$pot_bpjs_kes_amount = 0;

          		}

				$total_bpjs       = $pot_bpjs_kes_amount+$bpjs_tk_amount;
				
				$total_deduction  = $total_bpjs+$minus_amount;

				$total_net_salary  = ($total_gaji+$total_lembur+$commissions_amount) - $total_deduction;
									
			
				$net_salary = number_format((float)$total_net_salary, 2, '.', '');
			// check

		?>

		<div class="modal-header">
		  <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">×</span> </button>
		  <h4 class="modal-title" id="edit-modal-data"> <i class="fa fa-money"></i> 
		  		Bayar Gaji Harian - Per Karyawan!
			</h4>
		</div>

		<div class="modal-body" style="overflow:auto; height:670px;">
			
			<?php $attributes = array('name' => 'pay_dayly', 'id' => 'pay_dayly', 'autocomplete' => 'off', 'class'=>'m-b-1');?>
			<?php $hidden = array('_method' => 'ADD');?>
			<?php echo form_open('admin/payroll/add_pay_dayly/', $attributes, $hidden);?>

			<input type="hidden" value="<?php echo $user_id;?>"                               name="emp_id" id="emp_id" >			
			<input type="hidden" value="<?php echo $employee_name;?>"                         name="employee_name"/>			
			<input type="hidden" value="<?php echo $employee_department_id;?>"                name="employee_department_id"/>			
			<input type="hidden" value="<?php echo $employee_date_of_joining;?>"              name="employee_date_of_joining"/>			
			<input type="hidden" value="<?php echo $employee_company_id;?>"                   name="employee_company_id"/>			
			<input type="hidden" value="<?php echo $employee_location_id;?>"                  name="employee_location_id"/>			
			<input type="hidden" value="<?php echo $employee_designation_id;?>"               name="employee_designation_id"/>
			<input type="hidden" value="<?php echo $workstation_id;?>"                        name="workstation_id"/>	
			<input type="hidden" value="<?php echo $employee_wages_type;?>"                   name="employee_wages_type"/>
			<input type="hidden" value="<?php echo $this->input->get('start_date');?>"        name="start_date"/>			
			<input type="hidden" value="<?php echo $this->input->get('end_date');?>"          name="end_date"/>
			
			<input type="hidden" value="<?php echo $employee_basic_salary;?>"                  name="employee_basic_salary">
			<input type="hidden" value="<?php echo $basic_salary;?>"                          name="basic_salary">
			
			<input type="hidden" value="<?php echo $jumlah_hadir;?>"                          name="jumlah_hadir">
			<input type="hidden" value="<?php echo $total_gaji;?>"                            name="total_gaji">
			<input type="hidden" value="<?php echo $jumlah_overtime;?>"                       name="jumlah_overtime">
			<input type="hidden" value="<?php echo $overtime_amount;?>"                       name="overtime_amount">
			<input type="hidden" value="<?php echo $commissions_amount;?>"                    name="commissions_amount">

			<input type="hidden" value="<?php echo $pot_bpjs_kes_amount;?>"                   name="bpjs_kes_amount">
			<input type="hidden" value="<?php echo $bpjs_tk_amount;?>"                        name="bpjs_tk_amount">

			<input type="hidden" value="<?php echo $minus_amount;?>"                          name="minus_amount">			
			<input type="hidden" value="<?php echo $total_net_salary;?>"                      name="total_net_salary">
			<input type="hidden" value="<?php echo $rekening_name;?>"                         name="rekening_name">
			<input type="hidden" value="<?php echo $bank_name;?>"                             name="bank_name">
			<input type="hidden" value="<?php echo $employee_email;?>"                        name="employee_email">
			

		    <div class="row">
		        <div class="col-md-12">
		          <div class="box">
		            <div class="box-header with-border box-kuning" >
		              <h3 class="box-title" style="font-size: 16px; font-weight: bold;">
		              	 <?php echo $employee_name ;?>
		               </h3>
		               <br>
		               <h5 class="box-title-sub">
		              	 <?php echo $designation_name;?> - <?php echo $workstation_nm;?> - <?php echo $department_name;?>
		              </h5>
		               <br>
		              <h5 class="box-title-sub-sub">
		              	Periode Tanggal Gaji : <?php echo $p_month;?>
		              </h5>
		            </div>
		            <div class="box-body">
		              <div class="table-responsive" data-pattern="priority-columns">
		                <table class="datatables-demo table table-striped  dataTable no-footer">
		                  <tbody>
		                    
		                    <tr>
		                      <td colspan="2"><strong><i class="fa fa-plus-circle"></i> Penghasilan :</strong></td>
		                    </tr>

		                    <tr>
		                      	<td><strong> Gaji Pokok </strong></td> 
		                      	<td>: <span class="pull-right"><?php echo number_format($basic_salary, 0, ',', '.') ;?></span></td>                      
		                    </tr>
		                     <tr>
		                      	<td><strong> Jumlah Hadir </strong></td> 
		                      	<td>: <span class="pull-right"><?php echo $jumlah_hadir. ' Hari';?></span></td>                      
		                    </tr>
		                     <tr>
		                      	<td><strong> Total Gaji </strong></td> 
		                      	<td>: <b><span class="pull-right"><?php echo number_format($total_gaji, 0, ',', '.') ;?></span></b></td>                      
		                    </tr>

		                    <tr>
		                      <td colspan="2"><strong><i class="fa fa-plus-circle"></i> Faktor Tambah :</strong></td>
		                    </tr>
		                                                          
		                    <tr>
		                      	<td><strong> Jumlah Jam Lembur </strong></td> 
		                        <td>: <span class="pull-right"><?php echo $jumlah_overtime. ' Jam' ;?></span></td>
		                    </tr>
		                    <!-- Overtime -->                   
		                    <tr>
		                      	<td><strong> Total Lembur </strong></td> 
		                        <td>: <span class="pull-right"><?php echo number_format($overtime_amount, 0, ',', '.') ;?></span></td>
		                    </tr>

		                    <tr>
		                      	<td><strong> Total Tambahan </strong></td> 
		                        <td>: <span class="pull-right"><?php echo number_format($commissions_amount, 0, ',', '.') ;?></span></td>
		                    </tr>

		                    <tr>
		                      <td colspan="2" class="blue"><strong><i class="fa fa-minus-circle"></i> Faktor Pengurang :</strong></td>
		                    </tr>

		                    <tr>
		                      	<td><strong> BPJS Kes </strong></td> 
		                        <td>: <span class="pull-right"><?php echo number_format($pot_bpjs_kes_amount, 0, ',', '.') ;?></span></td>
		                    </tr>

		                    <tr>
		                      	<td><strong> BPJS TK </strong></td> 
		                        <td>:  <span class="pull-right"><?php echo number_format($bpjs_tk_amount, 0, ',', '.') ;?></span></td>
		                    </tr>

		                    <tr>
		                      	<td><strong> Total Potongan Lain </strong></td> 
		                        <td>: <span class="pull-right"><?php echo number_format($minus_amount, 0, ',', '.') ;?></span></td>
		                    </tr>

		                    <tr style="height: 10px;">
		                      <td></td>
		                      <td></td>
		                    </tr>
		                    
		                    <tr>
		                      <td><strong> Jumlah yang diterima </strong></td>
		                        <td>: <span class="pull-right text-bold"> <?php echo number_format($net_salary, 0, ',', '.') ;?></span></td>
		                    </tr>

		                    <tr>
		                      <td><strong> Rekening </strong></td>
		                        <td>: <span class="pull-right text-bold"> <?php echo $rekening_name ;?> - <?php echo $bank_name ;?></span></td>
		                    </tr>

		                    <tr>
		                      <td><strong> Email </strong></td>
		                        <td>: <span class="pull-right text-bold"> <?php echo $employee_email ;?></span></td>
		                    </tr>

		                  </tbody>
		                </table>
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
		$("#pay_dayly").submit(function(e){
		
		/*Form Submit*/
		e.preventDefault();
			var obj = $(this), action = obj.attr('name');
			$('.save').prop('disabled', true);
			$.ajax({
				type: "POST",
				url: e.target.action,
				data: obj.serialize()+"&is_ajax=11&data=dayly&add_type=add_dayly_payment&form="+action,
				cache: false,
				success: function (JSON) {
					
					if (JSON.error != '') {
					
						alert_fail('Gagal',JSON.error);
						$('input[name="csrf_hris"]').val(JSON.csrf_hash);
						$('.save').prop('disabled', false);
					
					} else {

						$('.emo_dayly_pay').modal('toggle');

						var xin_table3   = $('#xin_table_harian').dataTable({
									
							"bDestroy"        : true,
				            "bSort"           : false,
				            "aLengthMenu"     : [[10, 30, 50, 100, -1], [10, 30, 50, 100, "All"]],      
				            autoWidth         : true,  
				            "fixedColumns"    : true,
				            "fixedColumns"    : {
				              leftColumns   : 6
				            },  		
							"ajax": {
								url : "<?php echo site_url("admin/payroll/payslip_list_harian") ?>?company_id=<?php echo $employee_company_id;?>&start_date=<?php echo $this->input->get('start_date');?>&end_date=<?php echo $this->input->get('end_date');?>",
								type : 'GET'
							},
							  "columns": [
		                           {"name": "kolom_1",  "className": "text-center","width": "5%"},
		                          {"name": "kolom_2",  "className": "text-center"},
		                          {"name": "kolom_3",  "className": "text-center"},
		                          {"name": "kolom_4",  "className": "text-center"},
		                          {"name": "kolom_5",  "className": "text-center"},
		                          {"name": "kolom_6",  "className": "text-left"},
		                          {"name": "kolom_7",  "className": "text-left"},
		                          {"name": "kolom_8",  "className": "text-left"},
		                          {"name": "kolom_9",  "className": "text-center"},
		                          {"name": "kolom_10", "className": "text-center"},
		                          {"name": "kolom_11", "className": "text-center"},
		                          {"name": "kolom_12", "className": "text-center"},             
		                          {"name": "kolom_13", "className": "text-center"},                
		                          {"name": "kolom_14", "className": "text-right"},
		                          {"name": "kolom_15", "className": "text-center"},
		                          {"name": "kolom_16", "className": "text-right"},
		                          {"name": "kolom_17", "className": "text-right"},
		                          {"name": "kolom_18", "className": "text-right"},
		                          {"name": "kolom_19", "className": "text-right"},
		                          {"name": "kolom_20", "className": "text-right"},
		                          {"name": "kolom_21", "className": "text-right"},
		                          {"name": "kolom_22", "className": "text-right"},
		                          {"name": "kolom_23", "className": "text-right"},                          
		                          {"name": "kolom_24", "className": "text-center"},
		                          {"name": "kolom_25", "className": "text-center"},
		                          {"name": "kolom_26", "className": "text-left"},
		                          {"name": "kolom_27", "className": "text-left"}            
		                      ],
						   "language": {
					            "aria": {
					                "sortAscending" : ": activate to sort column ascending",
					                "sortDescending": ": activate to sort column descending"
					            },
					            "emptyTable": "Tidak ada data yang tersedia pada tabel ini",
							    "info": "Menampilkan _START_ sampai _END_ dari _TOTAL_ entri",
							    "infoEmpty": "Silahkan Ditunggu, proses refresh berlangsung ...",
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
												
								 $(row).find('td:eq(13)').css('background-color', '#eef7fa');
				                  $(row).find('td:eq(13)').css('color', 'black');  
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

				                  $(row).find('td:eq(19)').css('background-color', '#faeef4');
				                  $(row).find('td:eq(19)').css('color', 'black');
				                  $(row).find('td:eq(20)').css('background-color', '#faeef4');
				                  $(row).find('td:eq(20)').css('color', 'black');
				                  $(row).find('td:eq(21)').css('background-color', '#faeef4');
				                  $(row).find('td:eq(21)').css('color', 'black');

				                  $(row).find('td:eq(22)').css('background-color', '#ddf4e2');
				                  $(row).find('td:eq(22)').css('color', 'black');

				                  $(row).find('td:eq(23)').css('background-color', '#faf9ee');
				                  $(row).find('td:eq(23)').css('color', 'black'); 
				                  $(row).find('td:eq(24)').css('background-color', '#faf9ee');
				                  $(row).find('td:eq(24)').css('color', 'black');   
				                  $(row).find('td:eq(25)').css('background-color', '#faf9ee');
				                  $(row).find('td:eq(25)').css('color', 'black'); 

				                  $(row).find('td:eq(26)').css('background-color', '#e2e0c9');
				                  $(row).find('td:eq(26)').css('color', 'black');  
                  
							}
						});
						
						xin_table3.api().ajax.reload(function(){ 
							alert_success('Sukses',JSON.result);
						}, true);

						$('input[name="csrf_hris"]').val(JSON.csrf_hash);
						$('.save').prop('disabled', false);
					}
				}
			});
		});
	});	
	</script>

<?php }?>
