<?php $result = $this->Company_model->ajax_company_workstations_skala_upah_info($company_id);?>
<?php $system = $this->Core_model->read_setting_info(1);?>
<div class="form-group" id="ajx_workstation">
  <label for="workstation"><?php echo $this->lang->line('xin_workstation_name');?></label>
  <select class="select2" data-plugin="select_hrm" data-placeholder="<?php echo $this->lang->line('xin_select_workstation');?>" name="workstation_id" id="aj_subworkstations">
    <option value=""></option>
    <?php foreach($result as $workstation) {?>
    <option value="<?php echo $workstation->workstation_id?>"><?php echo $workstation->workstation_name?></option>
    <?php } ?>
  </select>
</div>
<?php
//}
?>

<script type="text/javascript">
$(document).ready(function(){
$('[data-plugin="select_hrm"]').select2($(this).attr('data-options'));
	$('[data-plugin="select_hrm"]').select2({ width:'100%' });
});
</script>
