<?php $session = $this->session->userdata('username');?>
<?php $user = $this->Core_model->read_user_info($employee_id); ?>
<?php $izin_categories = explode(',',$user[0]->izin_categories);?>
<div class="form-group">
   <label for="employee"><?php echo $this->lang->line('xin_izin_type');?></label>
   <select class="form-control" id="izin_type" name="izin_type" data-plugin="select_hrm" data-placeholder="<?php echo $this->lang->line('xin_izin_type');?>">
    <option value=""></option>
    <?php foreach($izin_categories as $izin_cat) {?>
    <?php if($izin_cat!=0):?>
    <?php
		$remaining_izin = $this->Permission_model->employee_count_total_izins($izin_cat,$employee_id);
		$type = $this->Permission_model->read_izin_type_information($izin_cat);
		if(!is_null($type)){
			$type_name = $type[0]->type_name;
			$total = $type[0]->days_per_year;
			$izin_remaining_total = $total - $remaining_izin;	
	?>
    <option value="<?php echo $izin_cat;?>"> <?php echo $type_name.' ('.$izin_remaining_total.' '.$this->lang->line('xin_remaining').')';?></option>
    <?php }  endif;?>
    <?php } ?>
  </select>  
  <span id="remaining_izin" style="display:none; font-weight:600; color:#F00;">&nbsp;</span>           
</div>
<?php
//}
?>
<script type="text/javascript">
jQuery(document).ready(function(){
	jQuery('[data-plugin="select_hrm"]').select2($(this).attr('data-options'));
	jQuery('[data-plugin="select_hrm"]').select2({ width:'100%' });
	
	/*jQuery("#izin_type").change(function(){
		var employee_id = jQuery('#employee_id').val();
		var izin_type_id = jQuery(this).val();
		if(izin_type_id == '' || izin_type_id == 0) {
			jQuery('#remaining_izin').show();
			jQuery('#remaining_izin').html('<?php echo $this->lang->line('xin_error_izin_type_field');?>');
		} else {
			jQuery.get(base_url+"/get_employees_izin/"+izin_type_id+"/"+employee_id, function(data, status){
				jQuery('#remaining_izin').show();
				jQuery('#remaining_izin').html(data);
			});
		}
		alert(employee_id + ' - - '+izin_type_id);
		
	});*/
});
</script>