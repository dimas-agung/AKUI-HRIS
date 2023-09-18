<?php $result = $this->Department_model->ajax_periode_borongan_info($month_year);?>
<div class="form-group">
	 <label for="first_name">Periode Kerja Borongan (<b>*</b>)</label>
   <select name="periode_id" id="periode_id" class="form-control" data-plugin="select_hrm" data-placeholder="Pilih Periode Kerja Borongan" required>
     <option value=""> Pilih Periode Kerja Borongan </option>
    <?php foreach($result as $periode) {?>
    <option value="<?php echo $periode->payroll_id;?>"> <?php echo date("d-m-Y",strtotime($periode->start_date)).' s/d '.date("d-m-Y",strtotime($periode->end_date));?></option>
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