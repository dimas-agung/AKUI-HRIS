<?php
defined('BASEPATH') OR exit('No direct script access allowed');
if(isset($_GET['jd']) && isset($_GET['department_id']) && $_GET['data']=='department'){
?>

<div class="modal-header"> <?php echo form_button(array('aria-label' => 'Close', 'data-dismiss' => 'modal', 'type' => 'button', 'class' => 'close', 'content' => '<span aria-hidden="true">×</span>')); ?>
  <h4 class="modal-title" id="edit-modal-data"><i class="fa fa-pencil"></i> <?php echo $this->lang->line('xin_department_edit');?></h4>
</div>
<?php $attributes = array('name' => 'edit_department', 'id' => 'edit_department', 'autocomplete' => 'off', 'class'=>'m-b-1');?>
<?php $hidden = array('_method' => 'EDIT', '_token' => $department_id, 'ext_name' => $department_name);?>
<?php echo form_open('admin/department/update/'.$department_id, $attributes, $hidden);?>

<div class="modal-body">
  
  	

  	<div class="form-group">
	    <label for="first_name"><?php echo $this->lang->line('left_company');?></label>
	    <select class="form-control" name="company_id" id="ajx_company" data-plugin="select_hrm" data-placeholder="<?php echo $this->lang->line('left_company');?>">
	      <option value=""></option>
	      <?php foreach($get_all_companies as $company) {?>
	      <option value="<?php echo $company->company_id?>"<?php if($company_id==$company->company_id):?> selected="selected"<?php endif;?>><?php echo $company->name?></option>
	      <?php } ?>
	    </select>
  	</div>
  
  	<?php $result2 = $this->Department_model->ajax_location_information($company_id);?>
  	<div class="form-group" id="location_ajaxx">
      <label for="name"><?php echo $this->lang->line('left_location');?></label>
      <select name="location_id" id="location_id" class="form-control" data-plugin="select_hrm" data-placeholder="<?php echo $this->lang->line('left_location');?>">
        <?php foreach($result2 as $location) {?>
        <option value="<?php echo $location->location_id?>" <?php if($location_id==$location->location_id):?> selected="selected"<?php endif;?>><?php echo $location->location_name?></option>
        <?php } ?>
      </select>
    </div>

    <div class="form-group" id="employee_ajx">
	    <label for="name"><?php echo $this->lang->line('xin_department_head');?></label>
	    <select name="employee_id" id="select2-demo-6" class="form-control" data-plugin="select_hrm" data-placeholder="<?php echo $this->lang->line('xin_department_head');?>">
	      <option value=""></option>
	      <?php $result = $this->Department_model->ajax_company_employee_info($company_id);?>
	      <?php foreach($result as $employee) {?>
	      <option value="<?php echo $employee->user_id;?>" <?php if($employee_id==$employee->user_id):?> selected="selected"<?php endif;?>> <?php echo $employee->first_name.' '.$employee->last_name;?></option>
	      <?php } ?>
	    </select>
	  </div>
	
	<div class="form-group">
    	<label for="department-name" class="form-control-label"><?php echo $this->lang->line('xin_name');?> <?php echo $this->lang->line('xin_departments');?>  :</label>
    	<input type="text" class="form-control" name="department_name" value="<?php echo $department_name?>">
  	</div>

	
	</div>

	<div class="modal-footer"> 
		<?php echo form_button(array('data-dismiss' => 'modal', 'type' => 'button', 'class' => $this->Core_model->form_button_class(), 'content' => '<i class="fa fa fa-check-square-o"></i> '.$this->lang->line('xin_close'))); ?> 
		<?php echo form_button(array('name' => 'hris_form', 'type' => 'submit', 'class' => $this->Core_model->form_button_class(), 'content' => '<i class="fa fa-save"></i> '.$this->lang->line('xin_update'))); ?> 
	</div>

<?php echo form_close(); ?> 

<script type="text/javascript">
 $(document).ready(function(){
							
		$('[data-plugin="select_hrm"]').select2($(this).attr('data-options'));
		$('[data-plugin="select_hrm"]').select2({ width:'100%' });	 
		
		jQuery("#ajx_company").change(function(){
			jQuery.get(base_url+"/get_employees/"+jQuery(this).val(), function(data, status){
				jQuery('#employee_ajx').html(data);
			});
			jQuery.get(escapeHtmlSecure(base_url+"/get_company_locations/"+jQuery(this).val()), function(data, status){
				jQuery('#location_ajaxx').html(data);
			});
		});
		/*jQuery("#aj_company").change(function(){
		jQuery.get(escapeHtmlSecure(base_url+"/get_company_locations/"+jQuery(this).val()), function(data, status){
			jQuery('#location_ajax').html(data);
		});
	});*/
		/* Edit data */
		$("#edit_department").submit(function(e){
		e.preventDefault();
			var obj = $(this), action = obj.attr('name');
			$('.save').prop('disabled', true);
			
			$.ajax({
				type: "POST",
				url: e.target.action,
				data: obj.serialize()+"&is_ajax=1&edit_type=department&form="+action,
				cache: false,
				success: function (JSON) {
					if (JSON.error != '') {
						alert_fail('Gagal',JSON.error);
						$('input[name="csrf_hris"]').val(JSON.csrf_hash);
						$('.save').prop('disabled', false);
					} else {
						// On page load: datatable
						var xin_table = $('#xin_table').dataTable({
							"bDestroy": true,
							"ajax": {
								url : "<?php echo htmlspecialchars(site_url("admin/department/department_list")); ?>",
								type : 'GET'
							},
							// dom: 'lBfrtip',
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
							// // // "buttons": ['csv', 'excel', 'pdf', 'print'], // colvis > if needed
							"fnDrawCallback": function(settings){
							$('[data-toggle="tooltip"]').tooltip();          
							}
						});
						xin_table.api().ajax.reload(function(){ 
							alert_success('Sukses',JSON.result);
							$('input[name="csrf_hris"]').val(JSON.csrf_hash);
						}, true);
						$('.edit-modal-data').modal('toggle');
						$('.save').prop('disabled', false);
					}
				}
			});
		});
	});	
  </script>
<?php }
?>
