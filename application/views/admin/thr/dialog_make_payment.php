<?php
defined('BASEPATH') OR exit('No direct script access allowed');

if(isset($_GET['jd']) && isset($_GET['employee_id']) && $_GET['data']=='payment' && $_GET['type']=='monthly_payment'){ 

?>

		<?php

			$tahun_thr = $this->input->get('tahun_thr');
			$tanggal_thr = $this->input->get('tanggal_thr');
				

			$system = $this->Core_model->read_setting_info(1);
			$payment_month = strtotime($this->input->get('tahun_thr'));
			$tahun_thr = date('Y',$payment_month);
			if($wages_type==1){				
				$basic_salary = $basic_salary;
				
			} 

			$employee_user_id = $this->input->get('employee_id');
			$employee = $this->Core_model->read_user_info($this->input->get('employee_id'));
			if(!is_null($employee)){

				$employee_user_id           = $employee[0]->user_id;
				$employee_grade_type        = $employee[0]->grade_type;
				$employee_wages_type        = $employee[0]->wages_type;
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

			// Karyawan Masa kerja														       	  
	        date_default_timezone_set("Asia/Jakarta");    

	        $tanggal1 = new DateTime($employee_date_of_joining);
			$tanggal2 = new DateTime($tanggal_thr);
      		
      		if ($tanggal2->diff($tanggal1)->y == 0) {
      			
      			// $jum_bulan = $tanggal2->diff($tanggal1)->m;
      			
      			if ($tanggal2->diff($tanggal1)->d >= 0 AND $tanggal2->diff($tanggal1)->d <= 15){
      				
      				$jum_bulan   = $tanggal2->diff($tanggal1)->m.' bln';

      			} else if ($tanggal2->diff($tanggal1)->d >= 16 AND $tanggal2->diff($tanggal1)->d <= 31){

      				$jum_bulan   = 1+$tanggal2->diff($tanggal1)->m.' bln';

      			}

      			$selisih   = $tanggal2->diff($tanggal1)->m.' bln'.' '.$tanggal2->diff($tanggal1)->d.' hr'.'<br>'.$jum_bulan;

      		} else {
      			$selisih   = $tanggal2->diff($tanggal1)->y.' thn'.' '.$tanggal2->diff($tanggal1)->m.' bln';
      			$jum_bulan = $tanggal2->diff($tanggal1)->y*12+($tanggal2->diff($tanggal1)->m);
      		}

                        

			// Rekening
			$rekening = $this->Employees_model->get_employee_bank_account_last($employee_user_id);
      		if(!is_null($rekening)){
				$rekening_name = $rekening[0]->account_number;
			} else {
				$rekening_name = '--';	
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
						$salary_tunj_jabatan = $this->Employees_model->read_salary_allowances_jabatan_tahun($employee_user_id,$tahun_thr);
						$count_tunj_jabatan  = $this->Employees_model->count_employee_allowances_jabatan_tahun($employee_user_id,$tahun_thr);
						$jumlah_tunj_jabatan = 0;
						if($count_tunj_jabatan > 0) {
							foreach($salary_tunj_jabatan as $tunj_jabatan){
							  	$jumlah_tunj_jabatan += $tunj_jabatan->tnj_jabatan;
							}
						} else {
							$jumlah_tunj_jabatan = 0;
						}
						
					
			// ====================================================================================================================
			// HITUNG
			// ====================================================================================================================	
          		
          		$total_jumlah     = $basic_salary+$jumlah_tunj_jabatan;

				if ($jum_bulan > 12 ) {
					$faktor_kali = 12;
					$masa = "THR Penuh";
				} else {

					if ($jum_bulan == 0) {

						$faktor_kali = $jum_bulan;
						$masa = "Tidak Dapat THR";

					} else {

						$faktor_kali = $jum_bulan;
						$masa = "THR Prorate";

					}
				}
				
				$total_net_salary = ($total_jumlah/12)*$faktor_kali;

		?>

		<div class="modal-header">
		  <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">×</span> </button>
		  <h4 class="modal-title" id="edit-modal-data"> <i class="fa fa-money"></i> 
		  		Bayar THR Bulanan - Per Karyawan !
			</h4>
		</div>

		<div class="modal-body" style="overflow:auto; height:450px;">
			
			<?php $attributes = array('name' => 'pay_monthly', 'id' => 'pay_monthly', 'autocomplete' => 'off', 'class'=>'m-b-1');?>
			<?php $hidden = array('_method' => 'ADD');?>
			<?php echo form_open('admin/thr/add_pay_monthly/', $attributes, $hidden);?>

			
			<input type="hidden" value="<?php echo $user_id;?>"                            name="emp_id" id="emp_id" >			
			<input type="hidden" value="<?php echo $employee_department_id;?>"             name="employee_department_id"/>			
			<input type="hidden" value="<?php echo $employee_date_of_joining;?>"           name="employee_date_of_joining"/>		
			<input type="hidden" value="<?php echo $employee_company_id;?>"                name="employee_company_id"/>		
			<input type="hidden" value="<?php echo $employee_location_id;?>"               name="employee_location_id"/>			
			<input type="hidden" value="<?php echo $employee_designation_id;?>"            name="employee_designation_id"/>			
			<input type="hidden" value="<?php echo $employee_wages_type;?>"                name="employee_wages_type"/>		
			<input type="hidden" value="<?php echo $this->input->get('tahun_thr');?>"      name="salary_month"/>		
			<input type="hidden" value="<?php echo $basic_salary;?>"                       name="basic_salary">			
			<input type="hidden" value="<?php echo $jumlah_tunj_jabatan;?>"                name="jumlah_tunj_jabatan">
			<input type="hidden" value="<?php echo $total_jumlah;?>"            		   name="total_jumlah">			
			<input type="hidden" value="<?php echo $total_net_salary;?>"                   name="total_net_salary">
			<input type="hidden" value="<?php echo $rekening_name;?>"                      name="rekening_name">
			<input type="hidden" value="<?php echo $tahun_thr;?>"                          name="tahun_thr">
			<input type="hidden" value="<?php echo $masa;?>"                               name="masa">
			<input type="hidden" value="<?php echo $tanggal_thr;?>"                        name="tanggal_thr">			

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
		              	THR Tahun : <?php echo $tahun_thr;?>, Tanggal Batas THR : <?php echo $tanggal_thr; ?>
		              </h5>
		            </div>
		            <div class="box-body">
		              <div class="table-responsive" data-pattern="priority-columns">
		                <table class="datatables-demo table table-striped  dataTable no-footer">
		                  <tbody>
		                    
		                    <tr>
		                      <td colspan="2" align="center"><strong><i class="fa fa-plus-circle"></i> Detail Tunjangan Hari Raya :</strong> 
		                        
		                      </td>
		                    </tr>
		                    <tr>
		                      	<td><strong> Gaji Pokok </strong></td> 
		                      	<td>: <span class="pull-right"><?php echo  number_format($basic_salary, 0, ',', '.'); ?></span></td>                      
		                    </tr>		                    
		                                  
		                    <tr>
		                        <td><strong> Tunj. Jabatan </strong></td> 
		                        <td>: <span class="pull-right"><?php echo number_format($jumlah_tunj_jabatan, 0, ',', '.'); ?></span></td>
		                    </tr>

							<tr>
		                        <td><strong> Tunj T1&T2 </strong></td> 
		                        <td>: <span class="pull-right"><?php echo number_format($total_jumlah, 0, ',', '.'); ?></span></td>
		                    </tr>

		                    <tr>
		                        <td><strong> Mulai Bekerja </strong></td> 
		                        <td>: <span class="pull-right"><?php echo date("d-m-Y",strtotime($employee_date_of_joining)); ?> - <?php echo $selisih; ?></span></td>
		                    </tr>

		                     <tr>
		                        <td><strong> Info THR </strong></td> 
		                        <td>: <span class="pull-right"><?php echo $masa; ?></span></td>
		                    </tr>                  
		                    
		                    <tr>
		                        <td><strong> Total THR </strong></td>
		                        <td>: <span class="pull-right text-bold"> <?php echo number_format($total_net_salary, 0, ',', '.');?></span></td>
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
		$("#pay_monthly").submit(function(e){
		
		/*Form Submit*/
		e.preventDefault();
			var obj = $(this), action = obj.attr('name');
			//$('.save').prop('disabled', true);
			$.ajax({
				type: "POST",
				url     : e.target.action,
				data    : obj.serialize()+"&is_ajax=11&data=monthly&add_type=add_monthly_payment&form="+action,
				cache   : false,
				success : function (JSON) {
					
					if (JSON.error != '') {
					
						alert_fail('Gagal',JSON.error);
						$('input[name="csrf_hris"]').val(JSON.csrf_hash);
						$('.save').prop('disabled', false);
					
					} else {
						
						$('.emo_monthly_pay').modal('toggle');

						var xin_table3   = $('#xin_table_thr').dataTable({									
							       
				            "bDestroy"        : true,
				            "bSort"           : false,
				            "aLengthMenu"     : [[10, 30, 50, 100, -1], [10, 30, 50, 100, "All"]],      
				            autoWidth         : true,  
				            "fixedColumns"    : true,
				            "fixedColumns"    : {
				                leftColumns   : 7
				            }, 
							"ajax": {
								url : "<?php echo site_url("admin/thr/thr_list_bulanan") ?>?company_id=<?php echo $employee_company_id;?>&tahun_thr=<?php echo $this->input->get('tahun_thr');?>&tanggal_thr=<?php echo $this->input->get('tanggal_thr');?>",
								type : 'GET'
							},
							"columns": [
				                   {"name": "kolom_1",  "className": "text-center","width": "5%"},
				                  {"name": "kolom_2",  "className": "text-center"},
				                  {"name": "kolom_3",  "className": "text-center"},
				                  {"name": "kolom_4",  "className": "text-center"},
				                  {"name": "kolom_5",  "className": "text-center"},
				                  {"name": "kolom_6",  "className": "text-center"},
				                  {"name": "kolom_7",  "className": "text-left"},
				                  {"name": "kolom_8",  "className": "text-left"},
				                  {"name": "kolom_9",  "className": "text-left"},
				                  {"name": "kolom_10",  "className": "text-center"},
				                  {"name": "kolom_11", "className": "text-center"},
				                  {"name": "kolom_12", "className": "text-center"},
				                  {"name": "kolom_13", "className": "text-center"},
				                  {"name": "kolom_14", "className": "text-center"},
				                  {"name": "kolom_15", "className": "text-right"},    
				                  {"name": "kolom_16", "className": "text-right"},
				                  {"name": "kolom_17", "className": "text-right"},
				                  {"name": "kolom_18", "className": "text-right"},        
				                  {"name": "kolom_19", "className": "text-center"},
				                  {"name": "kolom_20", "className": "text-center"},
				                  {"name": "kolom_21", "className": "text-left"}          
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
				              $(row).find('td:eq(18)').css('background-color', '#faf9ee');
				              $(row).find('td:eq(18)').css('color', 'black'); 
				              $(row).find('td:eq(19)').css('background-color', '#faf9ee');
				              $(row).find('td:eq(19)').css('color', 'black'); 
				              $(row).find('td:eq(20)').css('background-color', '#faf9ee');
				              $(row).find('td:eq(20)').css('color', 'black');
           
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
