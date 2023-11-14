<?php $result = $this->Assets_model->ajax_assets_info($assets_category_id);?>
<div class="form-group">
  <label for="assets_id"> Nama Aset <i class="hrsale-asterisk">*</i></label>
   <select name="assets_id" id="select2-demo-6" class="form-control" data-plugin="select_hrm" data-placeholder="Pilih Nama Aset">
    <option value=""></option>
    <?php foreach($result as $assets) {?>
    <option value="<?php echo $assets->assets_id;?>"> <?php echo $assets->name;?></option>
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