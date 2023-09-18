<?php $session          = $this->session->userdata('username');?>
<?php $user             = $this->Core_model->read_user_info($employee_id); ?>

<?php $leave_categories = explode(',',$user[0]->leave_categories);?>
<?php $user = $this->Core_model->read_user_info($employee_id); ?>
<?php 
	$date_of_joining = $user[0]->date_of_joining ;			
	$tgl_skrg   = date("Y-m-d");
	$datetime1  = new DateTime($user[0]->date_of_joining);
	$datetime2  = new DateTime($tgl_skrg);
	$interval   = $datetime1->diff($datetime2);	
	$no_of_days = $interval->format('%a');
	$lama_kerja = round($no_of_days/365,1);

	// ====
	$tgl_2      = date('Y-m', strtotime ('+1 years', strtotime ($user[0]->date_of_joining)));
	
?>

<div class="form-group">
   <label for="employee"><?php echo $this->lang->line('xin_leave_type');?>(<span class="merah">*</span>)</label>
   <select class="form-control" id="leave_type" name="leave_type" data-plugin="select_hrm" data-placeholder="Pilih Jenis Cuti">
	    <option value=""></option>
	    <?php foreach($leave_categories as $leave_cat) {?>

	    	<?php if ($lama_kerja > 0 &&  $lama_kerja < 1 ) { ?>

	    		<?php if($leave_cat!=0):?>
				    <?php
				    	$tahun = date("Y");

						$remaining_leave = $this->Permission_model->employee_count_total_leaves($leave_cat,$employee_id,$tahun);
						$type = $this->Permission_model->read_leave_type_information($leave_cat);
						if(!is_null($type)){
							$type_name = $type[0]->type_name;

							if ($type[0]->leave_type_id == 3) {

								$bulan = date('m', strtotime ('+1 years', strtotime ($user[0]->date_of_joining)));
								$total = $type[0]->days_per_year-$bulan;
								$leave_remaining_total = $total - $remaining_leave;	
								$info = ', belum dapat hak cuti tahunan';

							} else {

								$total = $type[0]->days_per_year;
								$leave_remaining_total = $total - $remaining_leave;	
								$info = ' ('.$leave_remaining_total.' Tersisa)';

							}
							
					?>
				    <option value="<?php echo $leave_cat;?>"> <?php echo $type_name.''.$info; ?></option>
			    <?php }  endif;?>


	    	<?php } else if ($lama_kerja > 1 ) { ?>

	    		<?php if ($lama_kerja > 1 && $lama_kerja < 1.1 ) { ?>

				    <?php if($leave_cat!=0):?>
					    <?php
							$remaining_leave = $this->Permission_model->employee_count_total_leaves($leave_cat,$employee_id);
							$type = $this->Permission_model->read_leave_type_information($leave_cat);
							if(!is_null($type)){
								$type_name = $type[0]->type_name;

								if ($type[0]->leave_type_id == 3) {

									$bulan = date('m', strtotime ('+1 years', strtotime ($user[0]->date_of_joining)));
									$total = $type[0]->days_per_year-$bulan;
									$leave_remaining_total = $total - $remaining_leave;	
									$info = ', dapat hak cuti tahunan di bulan '.date("m-Y",strtotime($tgl_2)).' ('.$leave_remaining_total.' Tersisa)';

								} else {

									$total = $type[0]->days_per_year;
									$leave_remaining_total = $total - $remaining_leave;	
									$info = ' ('.$leave_remaining_total.' Tersisa)';

								}
								
						?>
					    <option value="<?php echo $leave_cat;?>"> <?php echo $type_name.''.$info; ?></option>
				    <?php }  endif;?>

				<?php } else if ($lama_kerja >= 1.1 ) { ?>


					<?php if($leave_cat!=0):?>
					    <?php
							
							$remaining_leave = $this->Permission_model->employee_count_total_leaves($leave_cat,$employee_id);
							$type = $this->Permission_model->read_leave_type_information($leave_cat);
							
							if(!is_null($type)){
								$type_name = $type[0]->type_name;

								if ($type[0]->leave_type_id == 3) {

									$bulan = date('m', strtotime ('+1 years', strtotime ($user[0]->date_of_joining)));

									$tanggal_cuti = date("d", strtotime($user[0]->date_of_leaving));

									if ($bulan > 0){

										if ($tanggal_cuti >= 1 and $tanggal_cuti <= 15) {											

											if ($lama_kerja >= 1.1 && $lama_kerja <= 2) {

												$total = $type[0]->days_per_year-$bulan+1;

												$leave_remaining_total = abs($total - $remaining_leave);

												$info = ', dapat hak cuti tahunan di bulan '.date("m-Y",strtotime($tgl_2)).' ('.$leave_remaining_total.' Tersisa)';


											} else if ( $lama_kerja > 2) {

												$total = $type[0]->days_per_year;

												$leave_remaining_total = abs($total - $remaining_leave);

												$info = ' ('.$leave_remaining_total.' Tersisa)';

											}
											

										} else {

											$total = $type[0]->days_per_year-$bulan;

											$leave_remaining_total = abs($total - $remaining_leave);

											$info = ', ('.$leave_remaining_total.' Tersisa)';

										}

										
									} else {
										$total = $type[0]->days_per_year;

										$leave_remaining_total = abs($total - $remaining_leave);

										$info = ', ('.$leave_remaining_total.' Tersisa)';

									}

								} else {

									$total = $type[0]->days_per_year;
									$leave_remaining_total = $total - $remaining_leave;	

									$info = ' ('.$leave_remaining_total.' Tersisa)';

								}
								
						?>
					    <option value="<?php echo $leave_cat;?>"> <?php echo $type_name.''.$info ;?></option>
				    <?php }  endif;?>


				<?php } ?>

		    <?php } else { ?>

		    		 
		    <?php } ?>

	    <?php } ?>
  </select>  
  <span id="remaining_leave" >&nbsp;</span>                      
</div>

<script type="text/javascript">
jQuery(document).ready(function(){
	jQuery('[data-plugin="select_hrm"]').select2($(this).attr('data-options'));
	jQuery('[data-plugin="select_hrm"]').select2({ width:'100%' });
	
	jQuery("#leave_type").change(function(){
		var employee_id = jQuery('#employee_id').val();
		var leave_type_id = jQuery(this).val();
		if(leave_type_id == '' || leave_type_id == 0) {
			jQuery('#remaining_leave').show();
			jQuery('#remaining_leave').html('<?php echo $this->lang->line('xin_error_leave_type_field');?>');
		} else {
			jQuery.get(base_url+"/get_employees_leave/"+leave_type_id+"/"+$employee_id, function(data, status){
				jQuery('#remaining_leave').show();
				jQuery('#remaining_leave').html(data);
			});
		}
		// alert(employee_id + ' - - '+leave_type_id);
		
	});
});
</script>