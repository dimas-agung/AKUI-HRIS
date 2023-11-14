<?php $session = $this->session->userdata('username');?>
<?php $user = $this->Core_model->read_user_info($employee_id); ?>
<?php $libur_categories = explode(',',$user[0]->libur_categories);?>
<div class="form-group">
   <label for="employee"><?php echo $this->lang->line('xin_libur_type');?></label>
   <select class="form-control" id="libur_type" name="libur_type" data-plugin="select_hrm" data-placeholder="<?php echo $this->lang->line('xin_libur_type');?>">
    <option value=""></option>
    <?php foreach($libur_categories as $libur_cat) {?>
    <?php if($libur_cat!=0):?>
    <?php
		$remaining_libur = $this->Permission_model->employee_count_total_liburs($libur_cat,$employee_id);
		$type = $this->Permission_model->read_libur_type_information($libur_cat);
		if(!is_null($type)){
			$type_name = $type[0]->type_name;
			$total = $type[0]->days_per_year;
			$libur_remaining_total = $total - $remaining_libur;	
	?>
    <option value="<?php echo $libur_cat;?>"> <?php echo $type_name.' ('.$libur_remaining_total.' '.$this->lang->line('xin_remaining').')';?></option>
    <?php }  endif;?>
    <?php } ?>
  </select>  
  <span id="remaining_libur" style="display:none; font-weight:600; color:#F00;">&nbsp;</span>           
</div>
<?php
//}
?>
<script type="text/javascript">
jQuery(document).ready(function(){
	jQuery('[data-plugin="select_hrm"]').select2($(this).attr('data-options'));
	jQuery('[data-plugin="select_hrm"]').select2({ width:'100%' });
	
	/*jQuery("#libur_type").change(function(){
		var employee_id = jQuery('#employee_id').val();
		var libur_type_id = jQuery(this).val();
		if(libur_type_id == '' || libur_type_id == 0) {
			jQuery('#remaining_libur').show();
			jQuery('#remaining_libur').html('<?php echo $this->lang->line('xin_error_libur_type_field');?>');
		} else {
			jQuery.get(base_url+"/get_employees_libur/"+libur_type_id+"/"+employee_id, function(data, status){
				jQuery('#remaining_libur').show();
				jQuery('#remaining_libur').html(data);
			});
		}
		alert(employee_id + ' - - '+libur_type_id);
		
	});*/
});
</script>