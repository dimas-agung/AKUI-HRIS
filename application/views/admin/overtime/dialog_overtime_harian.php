<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<?php if(isset($_GET['jd']) && isset($_GET['overtime_id']) && $_GET['data']=='overtime'){ ?>
	

      <?php $assigned_ids = explode(',',$employee_id);  ?>
      <?php $session = $this->session->userdata('username');?>
            
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">×</span> </button>
        <h4 class="modal-title" id="edit-modal-data"><i class="fa fa-pencil"></i> Edit Lembur Harian </h4>
      </div>

      <?php $attributes = array('name' => 'edit_overtime_harian', 'id' => 'edit_overtime_harian', 'autocomplete' => 'off', 'class'=>'m-b-1');?>
      <?php $hidden = array('_method' => 'EDIT', '_token' => $overtime_id, 'ext_name' => $overtime_id);?>
      
      <?php echo form_open('admin/overtime_harian/update_harian/'.$overtime_id, $attributes, $hidden);?>
          
          <div class="modal-body">
            <div class="row">

              <div class="col-md-6">
                
                <div class="row">
                  <div class="col-md-12">
                      <div class="form-group">
                        <label for="company_name"><?php echo $this->lang->line('module_company_title');?></label>
                        <select class="form-control" name="company" id="ajx_company" data-plugin="select_hrm" data-placeholder="<?php echo $this->lang->line('module_company_title');?>">
                          <option value=""><?php echo $this->lang->line('xin_select_one');?></option>
                          <?php foreach($all_companies as $company) {?>
                          <option value="<?php echo $company->company_id;?>" <?php if($company_id==$company->company_id):?> selected="selected" <?php endif;?>> <?php echo $company->name;?></option>
                          <?php } ?>
                        </select>
                      </div>         
                  </div>
                </div>
                
                <?php $result = $this->Department_model->ajax_company_employee_info($company_id);?>
                
                <div class="row">
                  <div class="col-md-12">
                    <div class="form-group" id="employee_ajx">
                      <label for="employee" class="control-label"><?php echo $this->lang->line('xin_employee');?></label>
                      <select multiple class="form-control" name="employee_id[]" data-plugin="select_hrm" data-placeholder="<?php echo $this->lang->line('xin_employee');?>">
                        <option value=""></option>
                        <?php foreach($result as $employee) {?>
                        <option value="<?php echo $employee->user_id;?>" <?php if(in_array($employee->user_id,$assigned_ids)):?> selected <?php endif; ?>><?php echo $employee->first_name.' '.$employee->last_name;?></option>
                        <?php } ?>
                      </select>
                    </div>
                  </div>
                </div>

                 <?php $result2 = $this->Department_model->ajax_company_employee_info($company_id);?>

                <div class="row"> 
                    <div class="col-md-12">
                      <div class="form-group">
                        <label for="reports_to"><?php echo $this->lang->line('xin_reports_to');?></label>
                        <select name="reports_to" class="form-control" data-plugin="select_hrm" data-placeholder="<?php echo $this->lang->line('xin_reports_to');?>">
                          <option value=""></option>
                          <?php foreach(get_reports_to() as $reports_to) {?>
                          <option value="<?php echo $reports_to->user_id?>" <?php if($reports_to->user_id==$ereports_to):?> selected="selected"<?php endif;?>><?php echo $reports_to->first_name.' '.$reports_to->last_name;?></option>
                          <?php } ?>
                        </select>
                      </div>
                   </div>                            
                </div>

                 <div class="row">
                  <div class="col-md-12">
                    <div class="form-group">
                      <label for="overtime_type"><?php echo $this->lang->line('left_overtime_type');?></label>
                      <select class="form-control" name="overtime_type" data-plugin="select_hrm" data-placeholder="<?php echo $this->lang->line('left_overtime_type');?>">
                        <option value=""></option>
                        <?php foreach($all_overtime_types as $overtime_type) {?>
                        <option value="<?php echo $overtime_type->overtime_type_id?>" <?php if($overtime_type_id==$overtime_type->overtime_type_id):?> selected="selected" <?php endif;?>><?php echo $overtime_type->type?></option>
                        <?php } ?>
                      </select>
                    </div>
                  </div>
                </div>

              </div>

              <div class="col-md-6">
                
                <div class="row">

                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="date"><?php echo $this->lang->line('xin_e_details_date');?></label>
                      <input class="form-control attendance_date_e" placeholder="<?php echo $this->lang->line('xin_e_details_date');?>" readonly="true" id="attendance_date_e" name="attendance_date_e" type="text" value="<?php echo $attendance_date_m;?>">
                    </div>
                  </div>

                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="xin_tipe_lembur"><?php echo $this->lang->line('xin_tipe_lembur');?><i class="hris-asterisk">*</i></label>
                      <select name="ov_status" id="ov_status" class="form-control" data-plugin="select_hrm">
                         
                        <option value="TS" <?php if($ov_status=='TS'):?> selected="selected" <?php endif;?>><?php echo $this->lang->line('xin_tipe_lembur_tetap');?></option>
                        <option value="TB" <?php if($ov_status=='TB'):?> selected="selected" <?php endif;?>><?php echo $this->lang->line('xin_tipe_lembur_beda');?></option>
                      
                      </select>
                    </div>
                  </div>
                </div>

                <div class="row">
                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="clock_in">L1 - Mulai </label>
                      <input class="form-control timepicker" placeholder="L1 - Mulai " readonly="true" name="clock_in_1" type="text" value="<?php echo $clock_in_m;?>">
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="clock_out">L1 - Sampai</label>
                      <input class="form-control timepicker" placeholder="L1 - Sampai" readonly="true" name="clock_out_1" type="text" value="<?php echo $clock_out_m;?>">
                    </div>
                  </div>
                </div>

                 <div class="row">
                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="clock_in">L2 - Mulai </label>
                      <input class="form-control timepicker" placeholder="L2 - Mulai " readonly="true" name="clock_in_2" type="text" value="<?php echo $clock_in_n;?>">
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="clock_out">L2 - Sampai</label>
                      <input class="form-control timepicker" placeholder="L2 - Sampai" readonly="true" name="clock_out_2" type="text" value="<?php echo $clock_out_n;?>">
                    </div>
                  </div>
                </div>
                
                <div class="row">
                  <div class="col-md-12">
                    <div class="form-group">
                      <label for="description"><?php echo $this->lang->line('xin_description');?></label>
                      <textarea class="form-control textarea" placeholder="<?php echo $this->lang->line('xin_description');?>" name="description" rows="5" id="description"><?php echo $description;?></textarea>
                    </div>
                  </div>
                </div>

              </div>
            </div>

          </div>

          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fa fa-check-square-o"></i>
                <?php echo $this->lang->line('xin_close');?>
            </button>
            <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Aktifkan </button>
          </div>

      <?php echo form_close(); ?> 

      <script type="text/javascript">

          $(document).ready(function(){
                    // Clock
                    var input = $('.timepicker').clockpicker({
                      placement: 'bottom',
                      align: 'left',
                      autoclose: true,
                      'default': 'now'
                    });

              

                  	$('[data-plugin="select_hrm"]').select2($(this).attr('data-options'));
                  	$('[data-plugin="select_hrm"]').select2({ width:'100%' });	 
                  	
                    jQuery("#ajx_company").change(function(){
                    		jQuery.get(base_url+"/get_employees/"+jQuery(this).val(), function(data, status){
                    			jQuery('#employee_ajx').html(data);
                    		});        	   
                  	});
                  	
                    // Month & Year
                    $('.attendance_date_e').datepicker({
                        changeMonth: true,
                        changeYear: true,
                        dateFormat:'yy-mm-dd',
                        altField: "#date_format",
                        altFormat: "d M, yy",
                        yearRange: '1970:' + new Date().getFullYear(),
                        beforeShow: function(input) {
                          $(input).datepicker("widget").show();
                        }
                    }); 

                  	/* Edit data */
                  	$("#edit_overtime_harian").submit(function(e){
                    		var fd = new FormData(this);
                    		var obj = $(this), action = obj.attr('name');
                    		fd.append("is_ajax", 1);
                    		fd.append("edit_type", 'overtime');
                    		fd.append("form", action);
                    		e.preventDefault();
                    		$('.icon-spinner3').show();
                    		$('.save').prop('disabled', true);
                    		$.ajax({
                      			url: e.target.action,
                      			type: "POST",
                      			data:  fd,
                      			contentType: false,
                      			cache: false,
                      			processData:false,
                      			success: function(JSON)
                      			{
                      				if (JSON.error != '') {
                      					alert_fail('Gagal',JSON.error);
                      					$('input[name="csrf_hris"]').val(JSON.csrf_hash);
                      						$('.save').prop('disabled', false);
                      						$('.icon-spinner3').hide();
                      				} else {
                      					// On page load: datatable
                      					var xin_table = $('#xin_table').dataTable({
                      						"bDestroy": true,
                      						"ajax": {
                      							url : "<?php echo site_url("admin/overtime_harian/overtime_list_harian") ?>",
                      							type : 'GET'
                      						},
                      						"fnDrawCallback": function(settings){
                      						$('[data-toggle="tooltip"]').tooltip();          
                      						}
                      					});
                      					xin_table.api().ajax.reload(function(){ 
                      						alert_success('Sukses',JSON.result);
                      					}, true);
                      					$('input[name="csrf_hris"]').val(JSON.csrf_hash);
                      					$('.icon-spinner3').hide();
                      					$('.edit-modal-data').modal('toggle');
                      					$('.save').prop('disabled', false);
                      				}
                      			},
                      			error: function() 
                      			{
                      				alert_fail('Gagal',JSON.error);
                      				$('input[name="csrf_hris"]').val(JSON.csrf_hash);
                      				$('.icon-spinner3').hide();
                      				$('.save').prop('disabled', false);
                      			} 	        
                  	     });
                  	});
          });	
      </script>
<?php } ?>