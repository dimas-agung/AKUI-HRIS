<?php $result = $this->Company_model->get_department_by_company($company_id);?>
<?php $system = $this->Core_model->read_setting_info(1);?>
<div class="form-group">
  <label for="name">Departemen</label>
  <select name="department_id" id="department_id" class="form-control input-sm" data-plugin="select_hrm" data-placeholder="Departement">
    <option value=""></option>
    <?php foreach($result as $department) {?>
    <option value="<?php echo $department->department_id?>"><?php echo strtoupper($department->department_name);?></option>
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
