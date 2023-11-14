<?php
defined('BASEPATH') OR exit('No direct script access allowed');

if(isset($_GET['jd']) && isset($_GET['payslip_id']) && $_GET['data']=='payment' && $_GET['type']=='borongan_payment_delete'){ 

?>

		<?php

			$payslip_id = $this->input->get('payslip_id');
			$start_date = $this->input->get('start_date');
			$end_date   = $this->input->get('end_date');
			
			$p_month  =   date("d-m-Y",strtotime($start_date)).' s/d '.date("d-m-Y",strtotime($end_date));			
		?>


		<div class="modal-header">
		  <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">×</span> </button>
		  <h4 class="modal-title" id="edit-modal-data"> <i class="fa fa-trash"></i> 
		  		Hapus Borongan borongan - Per Karyawan!
			</h4>
		</div>

		<div class="modal-body" style="overflow:auto; height:610px;">
			
			<?php $attributes = array('name' => 'del_pay_borongan', 'id' => 'del_pay_borongan', 'autocomplete' => 'off', 'class'=>'m-b-1');?>
			<?php $hidden     = array('_method' => 'ADD');?>
			<?php echo form_open('admin/payroll/del_pay_borongan/', $attributes, $hidden);?>

			<input type="hidden" value="<?php echo $payslip_id;?>"                            name="payslip_id"                id="payslip_id" />
			<input type="hidden" value="<?php echo $start_date;?>"                            name="start_date"                id="start_date"/>			
			<input type="hidden" value="<?php echo $end_date;?>"                              name="end_date"                  id="end_date"/>
		    <input type="hidden" value="<?php echo $company_id;?>"                            name="company_id"                id="company_id" />		
			
			<input type="hidden" value="<?php echo $workstation_id;?>"                        name="workstation_id"            id="workstation_id"/>
			<input type="hidden" value="<?php echo $jumlah_hadir;?>"                          name="jumlah_hadir"              id="jumlah_hadir">
			<input type="hidden" value="<?php echo $jumlah_gram;?>"                           name="jumlah_gram"               id="jumlah_gram">
			<input type="hidden" value="<?php echo $jumlah_biaya;?>"                          name="jumlah_biaya"              id="jumlah_biaya">
			<input type="hidden" value="<?php echo $commissions_amount;?>"                    name="commissions_amount"        id="commissions_amount">

			<input type="hidden" value="<?php echo $bpjs_kes_amount;?>"            		      name="bpjs_kes_amount"           id="bpjs_kes_amount">
			<input type="hidden" value="<?php echo $bpjs_tk_amount;?>"                        name="bpjs_tk_amount"            id="bpjs_tk_amount">
			<input type="hidden" value="<?php echo $minus_amount;?>"                          name="minus_amount"              id="minus_amount">	

			<input type="hidden" value="<?php echo $total_net_salary;?>"                      name="total_net_salary"          id="total_net_salary">
			<input type="hidden" value="<?php echo $rekening_name;?>"                         name="rekening_name"             id="rekening_name">
			<input type="hidden" value="<?php echo $bank_name;?>"                             name="bank_name"                 id="bank_name">
			<input type="hidden" value="<?php echo $employee_email;?>"                        name="employee_email"            id="employee_email">
			
		    <div class="row">
		        <div class="col-md-12">
		          <div class="box">
		            <div class="box-header with-border box-merah" >
		               <h3 class="box-title" style="font-size: 16px; font-weight: bold;">
		              	 <?php echo $employee_name ;?>
		               </h3>
		               <br>
		               <h5 class="box-title-sub">
		              	 <?php echo $designation_name;?> - <?php echo $workstation_name;?> - <?php echo $department_name;?>
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
		                      	<td><strong> Jumlah Hadir </strong></td> 
		                      	<td>: <span class="pull-right"><?php echo $jumlah_hadir. ' Hari' ;?></span></td>                      
		                    </tr>
		                     <tr>
		                      	<td><strong> Jumlah Gram </strong></td> 
		                      	<td>: <span class="pull-right"><?php echo number_format($jumlah_gram, 0, ',', '.') ;?></span></td>                      
		                    </tr>
		                     <tr>
		                      	<td><strong> Total Gaji </strong></td> 
		                      	<td>: <b><span class="pull-right"><?php echo number_format($jumlah_biaya, 0, ',', '.') ;?></span></b></td>                      
		                    </tr>

		                    <tr>
		                      <td colspan="2"><strong><i class="fa fa-plus-circle"></i> Faktor Tambah :</strong></td>
		                    </tr>
		                                                          
		                    <tr>
		                      	<td><strong> Total Tambah </strong></td> 
		                        <td>: <span class="pull-right"><?php echo number_format($commissions_amount, 0, ',', '.') ;?></span></td>
		                    </tr>

		                    <tr>
		                      <td colspan="2" class="blue"><strong><i class="fa fa-minus-circle"></i> Faktor Pengurang :</strong></td>
		                    </tr>

		                    <tr>
		                      	<td><strong> BPJS Kes </strong></td> 
		                        <td>: <span class="pull-right"><?php echo number_format($bpjs_kes_amount, 0, ',', '.') ;?></span></td>
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
		                        <td>: <span class="pull-right text-bold"> <?php echo number_format($total_net_salary, 0, ',', '.') ;?></span></td>
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
		$("#del_pay_borongan").submit(function(e){
		
			/*Form Submit*/
			e.preventDefault();
			var obj = $(this), action = obj.attr('name');
			//$('.save').prop('disabled', true);
			$.ajax({
				type: "POST",
				url: e.target.action,
				data: obj.serialize()+"&is_ajax=11&data=borongan&proses_type=del_borongan_payment&form="+action,
				cache: false,
				success: function (JSON) {
					
					if (JSON.error != '') {
					
						alert_fail('Gagal',JSON.error);
						$('input[name="csrf_hris"]').val(JSON.csrf_hash);
						$('.save').prop('disabled', false);
					
					} else {

						$('.del_borongan_pay').modal('toggle');

						var xin_table3   = $('#xin_table_borongan').dataTable({
									
							"bDestroy"        : true,
				            "bSort"           : false,
				            "aLengthMenu"     : [[10, 30, 50, 100, -1], [10, 30, 50, 100, "All"]],      
				            autoWidth         : true,  
				            "fixedColumns"    : true,
				            "fixedColumns"    : {
				              leftColumns   : 6
				            },  		
							"ajax": {
								url : "<?php echo site_url("admin/payroll/payslip_list_borongan") ?>?company_id=<?php echo $company_id;?>&workstation_id=<?php echo $workstation_id;?>&start_date=<?php echo $this->input->get('start_date');?>&end_date=<?php echo $this->input->get('end_date');?>",
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
			                          {"name": "kolom_14", "className": "text-right"},
			                          {"name": "kolom_15", "className": "text-right"},
			                          {"name": "kolom_16", "className": "text-right"},
			                          {"name": "kolom_17", "className": "text-right"},
			                          {"name": "kolom_18", "className": "text-right"},
			                          {"name": "kolom_19", "className": "text-right"},
			                          {"name": "kolom_20", "className": "text-right"},
			                          {"name": "kolom_21", "className": "text-right"},                          
			                          {"name": "kolom_22", "className": "text-center"},
			                          {"name": "kolom_23", "className": "text-center"},
			                          {"name": "kolom_24", "className": "text-left"},
			                          {"name": "kolom_25", "className": "text-left"}          

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

		                        $(row).find('td:eq(18)').css('background-color', '#faeef8');
		                        $(row).find('td:eq(18)').css('color', 'black');
		                        $(row).find('td:eq(19)').css('background-color', '#faeef8');
		                        $(row).find('td:eq(19)').css('color', 'black');
		                        $(row).find('td:eq(20)').css('background-color', '#faeef8');
		                        $(row).find('td:eq(20)').css('color', 'black');

		                        $(row).find('td:eq(21)').css('background-color', '#eefaf1');
		                        $(row).find('td:eq(21)').css('color', 'black');

		                        $(row).find('td:eq(22)').css('background-color', '#faf9ee');
		                        $(row).find('td:eq(22)').css('color', 'black'); 
		                        $(row).find('td:eq(23)').css('background-color', '#faf9ee');
		                        $(row).find('td:eq(23)').css('color', 'black');   
		                        $(row).find('td:eq(24)').css('background-color', '#faf9ee');
		                        $(row).find('td:eq(24)').css('color', 'black'); 

		                        $(row).find('td:eq(25)').css('background-color', '#faf9ee');
                        		$(row).find('td:eq(25)').css('color', 'black'); 
							}
						});
						alert_success('Sukses',JSON.result);
						
						// xin_table3.api().ajax.reload(function(){ 
						// }, true);

						$('input[name="csrf_hris"]').val(JSON.csrf_hash);
						$('.save').prop('disabled', false);
					}
				}
			});
		});


	});	
	</script>

<?php }?>
