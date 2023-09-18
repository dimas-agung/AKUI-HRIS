<?php 

$result   = $this->Designation_model->ajax_company_designation_info( $company_id );

?>

<div class="form-group" id="ajx_designation">
  <label for="designation"> Posisi yang Dipromosikan</label>
  <select class="form-control" name="designation_id" data-plugin="select_hrm" data-placeholder="Pilih Posisi yang Dipromosikan">
    <option value=""></option>
    <?php foreach($result as $designation) {?>
		    <!-- <?php if($employee[0]->designation_id != $designation->designation_id):?> -->
        	<option value="<?php echo $designation->designation_id?>"><?php echo $designation->designation_name; ?></option>
        <!-- <?php endif;?> -->
    <?php } ?>
  </select>
</div>

<script type="text/javascript">
$(document).ready(function(){	
	$('[data-plugin="select_hrm"]').select2($(this).attr('data-options'));
	$('[data-plugin="select_hrm"]').select2({ width:'100%' });
});
</script>