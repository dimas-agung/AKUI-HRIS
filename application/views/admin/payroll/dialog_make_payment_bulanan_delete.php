<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<?php if(isset($_GET['jd']) && isset($_GET['payslip_id']) && $_GET['data']=='payment' && $_GET['type']=='monthly_payment_delete'){ ?>

		<?php

			$payslip_id = $this->input->get('payslip_id');
			$payment_month = strtotime($this->input->get('pay_date'));
			$p_month = date('F Y',$payment_month);		
			
		?>

		<div class="modal-header">
		  <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">×</span> </button>
		  <h4 class="modal-title" id="delete-modal-data"> <i class="fa fa-trash"></i> 
		  		Hapus Gaji Bulanan - Per Karyawan !
			</h4>
		</div>

		<div class="modal-body" style="overflow:auto; height:570px;">
			
			<?php $attributes = array('name' => 'del_pay_monthly', 'id' => 'del_pay_monthly', 'autocomplete' => 'off', 'class'=>'m-b-1');?>
			<?php $hidden = array('_method' => 'ADD');?>
			<?php echo form_open('admin/payroll/del_pay_monthly/', $attributes, $hidden);?>

			<input type="hidden"   value="<?php echo $payslip_id;?>"                     name="payslip_id"                id="payslip_id" >
			<input type="hidden"   value="<?php echo $this->input->get('pay_date');?>"   name="salary_month"/>
			<input type="hidden"   value="<?php echo $company_id;?>"                     name="company_id"                id="company_id" >			
			<input type="hidden"   value="<?php echo $employee_name;?>"                  name="employee_name"             id="employee_name"/>

			<input type="hidden"   value="<?php echo $basic_salary;?>"                   name="basic_salary"               id="basic_salary" >
			<input type="hidden"   value="<?php echo $jumlah_tunj_jabatan;?>"            name="jumlah_tunj_jabatan"        id="jumlah_tunj_jabatan" >
			<input type="hidden"   value="<?php echo $jumlah_tunj_produktifitas;?>"      name="jumlah_tunj_produktifitas"  id="jumlah_tunj_produktifitas" >
			<input type="hidden"   value="<?php echo $jumlah_tunj_transportasi;?>"       name="jumlah_tunj_transportasi"   id="jumlah_tunj_transportasi" >
			<input type="hidden"   value="<?php echo $jumlah_tunj_komunikasi;?>"         name="jumlah_tunj_komunikasi"     id="jumlah_tunj_komunikasi" >
			<input type="hidden"   value="<?php echo $commissions_amount;?>"             name="commissions_amount"         id="commissions_amount" >	
			<input type="hidden"   value="<?php echo $overtime_amount;?>"                name="overtime_amount"            id="overtime_amount" >		
			<input type="hidden"   value="<?php echo $total_tambah;?>"                   name="total_tambah"               id="total_tambah" >

			<input type="hidden"   value="<?php echo $bpjs_kes_amount;?>"                name="bpjs_kes_amount"            id="bpjs_kes_amount" >
			<input type="hidden"   value="<?php echo $bpjs_tk_amount;?>"                 name="bpjs_tk_amount"             id="bpjs_tk_amount" >
			<input type="hidden"   value="<?php echo $loan_de_amount;?>"                 name="loan_de_amount"             id="loan_de_amount" >
			<input type="hidden"   value="<?php echo $other_payments_amount;?>"          name="other_payments_amount"      id="other_payments_amount" >
			<input type="hidden"   value="<?php echo $potongan_absen;?>"                 name="potongan_absen"             id="potongan_absen" >
			<input type="hidden"   value="<?php echo $potongan_lain;?>"                  name="potongan_lain"              id="potongan_lain" >
			<input type="hidden"   value="<?php echo $total_deduction;?>"                name="total_deduction"            id="total_deduction" >	
			<input type="hidden"   value="<?php echo $net_salary;?>"                     name="net_salary"                 id="net_salary" >		
						

		    <div class="row">
		        <div class="col-md-12">
		          <div class="box">
		          	<div class="box-header with-border box-merah" >
		              <h3 class="box-title" style="font-size: 16px; font-weight: bold;">
		              	 <?php echo $employee_name ;?>
		               </h3>
		               <br>
		               <h5 class="box-title-sub">
		              	 <?php echo $designation_name;?> - <?php echo $department_name;?>
		              </h5>
		              <br>
		              <h5 class="box-title-sub-sub">
		              	Periode : <?php echo $p_month;?>
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
		    	<?php echo form_button(array('name' => 'hris_form', 'type' => 'submit', 'class' => $this->Core_model->form_button_delete_class(), 'content' => '<i class="fa fa-trash"></i> '.$this->lang->line('xin_delete'))); ?> 
		    </div>
		    
		  <?php echo form_close(); ?>
		</div>

	</div>
	<script type="text/javascript">
	$(document).ready(function(){
		$('[data-plugin="select_hrm"]').select2($(this).attr('data-options'));
		$('[data-plugin="select_hrm"]').select2({ width:'100%' });
		
		// On page load: datatable					
		$("#del_pay_monthly").submit(function(e){
		
		/*Form Submit*/
		e.preventDefault();
			var obj = $(this), action = obj.attr('name');
			$('.save').prop('disabled', true);
			$.ajax({
				type: "POST",
				url: e.target.action,
				data: obj.serialize()+"&is_ajax=11&data=monthly&add_type=del_monthly_payment&form="+action,
				cache: false,
				success: function (JSON) {
					
					if (JSON.error != '') {
					
						alert_fail('Gagal',JSON.error);
						$('input[name="csrf_hris"]').val(JSON.csrf_hash);
						$('.save').prop('disabled', false);
					
					} else {
						$('.del_monthly_pay').modal('toggle');

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
								url : "<?php echo site_url("admin/payroll/payslip_list_bulanan") ?>?company_id=<?php echo $company_id;?>&month_year=<?php echo $this->input->get('pay_date');?>",
								type : 'GET'
							},
							"columns": [
				                  {"name": "kolom_1", "className": "text-center"},
				                  {"name": "kolom_2", "className": "text-center"},
				                  {"name": "kolom_2", "className": "text-center"},
				                  {"name": "kolom_3", "className": "text-center"},
				                  {"name": "kolom_4", "className": "text-center"},
				                  {"name": "kolom_5", "className": "text-center"},
				                  {"name": "kolom_6", "className": "text-left"},
				                  {"name": "kolom_7", "className": "text-left"},
				                  {"name": "kolom_8", "className": "text-left"},
				                  {"name": "kolom_9", "className": "text-center"},
				                  {"name": "kolom_10", "className": "text-center"},
				                  {"name": "kolom_11", "className": "text-center"},
				                  {"name": "kolom_12", "className": "text-center"},                  
				                  {"name": "kolom_13", "className": "text-center"},
				                  {"name": "kolom_14", "className": "text-right"},
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
				                  {"name": "kolom_35", "className": "text-center"},
				                  {"name": "kolom_36", "className": "text-center"},
				                  {"name": "kolom_37", "className": "text-left"}      
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
						$('.save').prop('disabled', false);
					}
				}
			});
		});
	});	
	</script>

<?php }?>
