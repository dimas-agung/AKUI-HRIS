<?php
	if(isset($_GET['jd']) && isset($_GET['field_id']) && $_GET['data']=='ed_shift_jam' && $_GET['type']=='ed_shift_jam')
	{
		$row = $this->Core_model->read_shift_jam($_GET['field_id']);
		?>
		<div class="modal-header">
		  <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">×</span> </button>
		  <h4 class="modal-title" id="edit-modal-data"><i class="fa fa-pencil"></i> Edit Jam Shift</h4>
		</div>
		
		<?php $attributes = array('name' => 'ed_shift_jam_info', 'id' => 'ed_shift_jam_info', 'autocomplete' => 'off', 'class'=>'m-b-1');?>
		
		<?php $hidden = array('_method' => 'EDIT', '_token' => $row[0]->id, 'ext_name' => $row[0]->kode);?>
		
		<?php echo form_open('admin/timesheet/update_shift_jam/'.$row[0]->id, $attributes, $hidden);?>
		
		<div class="modal-body">
		  
		  <div class="form-group">
			<label for="name" class="form-control-label">Kode</label>
			<input type="text" class="form-control" name="kode" placeholder="Kode Jam Shift" value="<?php echo $row[0]->kode;?>">
		  </div>
		  
		   <div class="row">
	      <div class="col-md-6">
	        <div class="form-group">
	          <label for="clock_in">Jam Shift Mulai</label>
	          <input class="form-control timepicker" placeholder="Jam Shift Mulai" name="start_date" type="text" value="<?php echo $row[0]->start_date;?>">
	        </div>
	      </div>
	      <div class="col-md-6">
	        <div class="form-group">
	          <label for="clock_out">Jam Shift Sampai</label>
	          <input class="form-control timepicker" placeholder="Jam Shift Sampai" name="end_date" type="text" value="<?php echo $row[0]->end_date;?>">
	        </div>
	      </div>
	    </div>   

	    <div class="form-group">
	      <label for="name">Keterangan</label>
	      <input type="text" class="form-control" name="keterangan" placeholder="keterangan" value="<?php echo $row[0]->keterangan;?>">
	    </div>  

		</div>
		
		<div class="modal-footer">
		  <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fa fa-check-square-o"></i> <?php echo $this->lang->line('xin_close');?></button>
		  <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> <?php echo $this->lang->line('xin_update');?></button>
		</div>
		
		<?php echo form_close(); ?>

		<script type="text/javascript">

			$(document).ready(function(){

				$('[data-plugin="select_hrm"]').select2($(this).attr('data-options'));
				$('[data-plugin="select_hrm"]').select2({ width:'100%' });

				/* Edit data */
				
				$("#ed_shift_jam_info").submit(function(e){
				
				/* Form Submit */
				e.preventDefault();
					var obj = $(this), action = obj.attr('name');
					$('.save').prop('disabled', true);
					$.ajax({
						type: "POST",
						url: e.target.action,
						data: obj.serialize()+"&is_ajax=39&type=edit_record&data=ed_shift_jam_info&form="+action,
						cache: false,
						success: function (JSON) {
							if (JSON.error != '') {
								alert_fail('Gagal',JSON.error);
								$('input[name="csrf_hris"]').val(JSON.csrf_hash);
								$('.save').prop('disabled', false);
							} else {
								$('.edit_setting_datail').modal('toggle');
								// On page load: datatable
								var xin_table_shift_jam = $('#xin_table_shift_jam').dataTable({
									"bDestroy": true,
									"bFilter": true,
									"bAutoWidth": false,
									"bLengthChange": true,
									"iDisplayLength": 10,
									"aLengthMenu": [[10, 30, 50, 100, -1], [10, 30, 50, 100, "All"]],
									"ajax": {
							            url : site_url+"timesheet/shift_jam_list/",
							            type : 'GET'
							        },
									"columns": [
										{"name": "kolom_0","orderable": false,"searchable": false,  "className": "text-center", "width": "7%"},
										{"name": "kolom_1",  "className": "text-center", "width": "10%"},
										{"name": "kolom_2",  "className": "text-center", "width": "10%"},
										{"name": "kolom_3",  "className": "text-center", "width": "10%"},
										{"name": "kolom_4",  "className": "text-left"}
								    ],		
									"fnDrawCallback": function(settings){
										$('[data-toggle="tooltip"]').tooltip();          
									}			
								});
								xin_table_shift_jam.api().ajax.reload(function(){ 
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
		<?php 
	} 
