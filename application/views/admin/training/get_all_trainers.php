<?php $all_trainers = $this->Trainers_model->all_trainers();?>
<div class="form-group">
  <label for="trainer">Nama Pelatih Eksternal (<span class="merah">*</span>)</label>
   <select name="trainer" id="trainer" class="form-control" data-plugin="select_hrm" data-placeholder="Pelatih dari Luar">
    <option value=""></option>
    <?php foreach($all_trainers as $trainer) {?>
    <option value="<?php echo $trainer->trainer_id?>"><?php echo $trainer->first_name.' '.$trainer->last_name;?></option>
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