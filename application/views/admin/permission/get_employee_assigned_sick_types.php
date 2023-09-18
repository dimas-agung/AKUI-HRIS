<?php $session = $this->session->userdata('username');?>
<?php $user = $this->Core_model->read_user_info($employee_id); ?>
<?php $sick_categories = explode(',',$user[0]->sick_categories);?>
<div class="form-group">
   <label for="employee"><?php echo $this->lang->line('xin_sick_type');?></label>
   <select class="form-control" id="sick_type" name="sick_type" data-plugin="select_hrm" data-placeholder="<?php echo $this->lang->line('xin_sick_type');?>">
    <option value=""></option>
    <?php foreach($sick_categories as $sick_cat) {?>
    <?php if($sick_cat!=0):?>
    <?php
		$remaining_sick = $this->Permission_model->employee_count_total_sicks($sick_cat,$employee_id);
		$type = $this->Permission_model->read_sick_type_information($sick_cat);
		if(!is_null($type)){
			$type_name = $type[0]->type_name;
			$total = $type[0]->days_per_year;
			$sick_remaining_total = $total - $remaining_sick;	
	?>
    <option value="<?php echo $sick_cat;?>"> <?php echo $type_name.' ('.$sick_remaining_total.' '.$this->lang->line('xin_remaining').')';?></option>
    <?php }  endif;?>
    <?php } ?>
  </select>  
  <span id="remaining_sick" style="display:none; font-weight:600; color:#F00;">&nbsp;</span>           
</div>
<?php
//}
?>
<script type="text/javascript">
jQuery(document).ready(function(){
	jQuery('[data-plugin="select_hrm"]').select2($(this).attr('data-options'));
	jQuery('[data-plugin="select_hrm"]').select2({ width:'100%' });
	
	/*jQuery("#sick_type").change(function(){
		var employee_id = jQuery('#employee_id').val();
		var sick_type_id = jQuery(this).val();
		if(sick_type_id == '' || sick_type_id == 0) {
			jQuery('#remaining_sick').show();
			jQuery('#remaining_sick').html('<?php echo $this->lang->line('xin_error_sick_type_field');?>');
		} else {
			jQuery.get(base_url+"/get_employees_sick/"+sick_type_id+"/"+employee_id, function(data, status){
				jQuery('#remaining_sick').show();
				jQuery('#remaining_sick').html(data);
			});
		}
		alert(employee_id + ' - - '+sick_type_id);
		
	});*/
});
</script>