<?php $session = $this->session->userdata('username');?>
<?php $user    = $this->Core_model->read_employee_info($session['user_id']); ?>
<?php $result  = $this->Department_model->ajax_company_employee_info($company_id);?>

<div class="form-group">
   <label for="employee"><?php echo $this->lang->line('xin_employee');?>(<span class="merah">*</span>)</label>
   <select name="employee_id" id="employee_idx" class="form-control" data-plugin="select_hrm" data-placeholder="Pilih Nama Karyawan" required>
    <option value=""></option>
    <?php foreach($result as $employee) {?>
    	<?php 
			$date_of_joining = $employee->date_of_joining ;			
			$tgl_skrg   = date("Y-m-d");
			$datetime1  = new DateTime($employee->date_of_joining);
			$datetime2  = new DateTime($tgl_skrg);
			$interval   = $datetime1->diff($datetime2);	
			$no_of_days = $interval->format('%a');
			$lama_kerja = round($no_of_days/365,1);
		?>

    <option value="<?php echo $employee->user_id;?>"> <?php echo $employee->first_name.' '.$employee->last_name.' (Mulai Bekerja '.date("d-m-Y",strtotime($employee->date_of_joining)).' Masa Kerja '.$lama_kerja.' thn)';?></option>
    <?php } ?>
  </select>  
  <span id="remaining_leave" style="display:none; font-weight:600; color:#F00;">&nbsp;</span>           
</div>


<script type="text/javascript">
jQuery(document).ready(function(){
	jQuery('[data-plugin="select_hrm"]').select2($(this).attr('data-options'));
	jQuery('[data-plugin="select_hrm"]').select2({ width:'100%' });
	
	jQuery("#employee_idx").change(function(){
		var employee_id = jQuery(this).val();
		jQuery.get(base_url+"/get_employee_assigned_leave_types/"+employee_id, function(data, status){
			jQuery('#get_leave_types').html(data);
		});		
	});
	
	jQuery("#leave_type").change(function(){
		var employee_id = jQuery('#employee_id').val();
		var leave_type_id = jQuery(this).val();
		if(leave_type_id == '' || leave_type_id == 0) {
			jQuery('#remaining_leave').show();
			jQuery('#remaining_leave').html('<?php echo $this->lang->line('xin_error_leave_type_field');?>');
		} else {
			jQuery.get(base_url+"/get_employees_leave/"+leave_type_id+"/"+employee_id, function(data, status){
				jQuery('#remaining_leave').show();
				jQuery('#remaining_leave').html(data);
			});
		}
		alert(employee_id + ' - - '+leave_type_id);
		
	});
});
</script>