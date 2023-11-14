<?php $result = $this->Company_model->ajax_company_designation_info($department_id);?>
<?php $system = $this->Core_model->read_setting_info(1);?>
<div class="form-group" id="ajx_designation">
  <label for="designation"><?php echo $this->lang->line('xin_designations');?> (<span class="merah">*</span>)</label>
  <select class="select2" data-plugin="select_hrm" data-placeholder="<?php echo $this->lang->line('xin_select_designation');?>" name="designation_id" id="aj_designations">
    <option value=""></option>
    <?php foreach($result as $designation) {?>
    <option value="<?php echo $designation->designation_id?>"><?php echo $designation->designation_name?></option>
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
