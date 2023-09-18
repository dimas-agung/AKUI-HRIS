<?php $result = $this->Company_model->ajax_company_workstations_info($company_id);?>

<div class="form-group" id="ajx_workstation_modal">
  <label for="designation"><?php echo $this->lang->line('xin_hr_main_workstation');?></label>
  <select class="select2" data-plugin="select_hrm" data-placeholder="<?php echo $this->lang->line('xin_select_workstation');?>" name="workstation_id" >
    <option value=""></option>
    <?php foreach($result as $workstations) {?>
    <option value="<?php echo $workstations->workstation_id?>"><?php echo $workstations->workstation_name?></option>
    <?php } ?>
  </select>
</div>
<?php
//}
?>
<script type="text/javascript">
$(document).ready(function(){
// get sub workstations

$('[data-plugin="select_hrm"]').select2($(this).attr('data-options'));
	$('[data-plugin="select_hrm"]').select2({ width:'100%' });
});
</script>