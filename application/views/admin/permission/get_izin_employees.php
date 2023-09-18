<?php $session = $this->session->userdata('username');?>
<?php $user    = $this->Core_model->read_employee_info($session['user_id']); ?>
<?php $result  = $this->Department_model->ajax_company_employee_info($company_id);?>

<div class="form-group">
   <label for="employee"><?php echo $this->lang->line('xin_employee');?></label>
   <select name="employee_id" id="employee_idx" class="form-control" data-plugin="select_hrm" data-placeholder="<?php echo $this->lang->line('xin_choose_an_employee');?>" required>
    <option value=""></option>
    <?php foreach($result as $employee) {?>
    <option value="<?php echo $employee->user_id;?>"> <?php echo $employee->first_name.' '.$employee->last_name;?></option>
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
	jQuery("#employee_idx").change(function(){
		var employee_id = jQuery(this).val();
		jQuery.get(base_url+"/get_employee_assigned_izin_types/"+employee_id, function(data, status){
			jQuery('#get_izin_types').html(data);
		});		
	});
	
});
</script>