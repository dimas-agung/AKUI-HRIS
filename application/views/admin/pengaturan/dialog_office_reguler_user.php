<?php
defined('BASEPATH') OR exit('No direct script access allowed');
if(isset($_GET['jd']) && isset($_GET['office_shift_id']) && $_GET['data']=='reguler'){
?>

  <?php $session = $this->session->userdata('username');?>
  <?php $user_info = $this->Core_model->read_user_info($session['user_id']);?>

    <div class="modal-header">
      <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">×</span> </button>
      <h4 class="modal-title" id="add-modal-data"><i class="fa fa-users"></i> <?php echo $this->lang->line('xin_edit_office_reguler_user');?></h4>
    </div>

    <?php $attributes = array('name' => 'edit_office_reguler_user', 'id' => 'edit_office_reguler_user', 'autocomplete' => 'off', 'class'=>'m-b-1');?>
    <?php $hidden     = array('_method' => 'EDIT', '_token' => $office_shift_id, 'ext_name' => $office_shift_id);?>

    <?php echo form_open('admin/pengaturan/edit_office_reguler_user/'.$office_shift_id, $attributes, $hidden);?>

    <div class="modal-body">
      <div class="row">
        <div class="col-md-12">
         
          <div class="form-group row">
            <label for="time" class="col-md-3"><?php echo $this->lang->line('left_company');?></label>
            <div class="col-md-9">
              <select class="form-control input-sm" name="company_id" id="aj_company" data-plugin="select_hrm" data-placeholder="<?php echo $this->lang->line('left_company');?>">
              <option value=""></option>
              <?php foreach($get_all_companies as $company) {?>
              <option value="<?php echo $company->company_id?>" <?php if($company->company_id==$company_id):?> selected="selected"<?php endif;?>><?php echo $company->name?></option>
              <?php } ?>
            </select>
            </div>
          </div>
      

          <div class="form-group row">
            <label for="time" class="col-md-3"><?php echo $this->lang->line('xin_e_details_month_work');?></label>
            <div class="col-md-9">
              <select class="form-control input-sm" name="payroll_id" id="aj_company" data-plugin="select_hrm" data-placeholder="<?php echo $this->lang->line('xin_e_details_month_work');?>">
               <!-- <option value=""></option> -->
               <?php foreach($all_bulan_gaji as $bulan_gaji) {?>
                  <option value="<?php echo $bulan_gaji->payroll_id?>" <?php if($bulan_gaji->payroll_id==$payroll_id):?> selected="selected"<?php endif;?>><?php echo $bulan_gaji->desc?></option>
               <?php } ?>
              </select>
            </div>
          </div>            

          <div class=" row">
            <label for="time" class="col-md-3"><?php echo $this->lang->line('xin_e_details_timeperiod');?></label>
             <div class="col-md-4">
              <div class="form-group">
                <input class="form-control input-sm attendance_date" placeholder="<?php echo $this->lang->line('xin_select_date');?>" readonly="1"  name="start_date" type="text"  value="<?php echo $start_date;?>" >
              </div>
            </div>
            <div class="col-md-5">
              <div class="form-group">
                <input class="form-control input-sm attendance_date" placeholder="<?php echo $this->lang->line('xin_select_date');?>" readonly="1"  name="end_date" type="text" value="<?php echo $end_date;?>" >
              </div>
            </div>
          </div>         

          <div class="form-group row">
            <label for="time" class="col-md-3"><?php echo $this->lang->line('xin_shift_name');?></label>
            <div class="col-md-9">
              <input class="form-control input-sm" placeholder="<?php echo $this->lang->line('xin_shift_name');?>" name="shift_name" type="text" id="name" value="<?php echo $shift_name;?>">
            </div>
          </div>
          
         
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

        </div>
      </div>
    </div>

    <div class="modal-footer">
      <button type="button" class="btn btn-secondary" data-dismiss="modal"><?php echo $this->lang->line('xin_close');?></button>
      <button type="submit" class="btn btn-primary"><?php echo $this->lang->line('xin_update');?></button>
    </div>
    
  <?php echo form_close(); ?>

<script type="text/javascript">
 $(document).ready(function(){
								
	// Clock
	$('.clockpicker').clockpicker();
  	var input = $('.timepicker').clockpicker({
  		placement: 'bottom',
  		align: 'left',
  		autoclose: true,
  		'default': 'now'
	});
  
  // Month & Year
  $('.attendance_date').datepicker({
      changeMonth: true,
      changeYear: true,
      // maxDate: '0',
      dateFormat:'yy-mm-dd',
      altField: "#date_format",
      altFormat: js_date_format,
      yearRange: '1970:' + new Date().getFullYear(),
      beforeShow: function(input) {
        $(input).datepicker("widget").show();
      }
  });


	$('[data-plugin="select_hrm"]').select2($(this).attr('data-options'));
	$('[data-plugin="select_hrm"]').select2({ width:'100%' });
	
  /* Edit data */
	$("#edit_office_reguler_user").submit(function(e){
    
  		/*Form Submit*/
  		e.preventDefault();
  		var obj = $(this), action = obj.attr('name');
  		$('.save').prop('disabled', true);
  		
      $.ajax({
  			type: "POST",
  			url: e.target.action,
  			data: obj.serialize()+"&is_ajax=3&edit_type=reguler&form="+action,
  			cache: false,
  			success: function (JSON) {
  				
          if (JSON.error != '') {
  				
          	alert_fail('Gagal',JSON.error);
  					$('input[name="csrf_hris"]').val(JSON.csrf_hash);
  					$('.save').prop('disabled', false);
  				
          } else {  				    

          	  $('.add-modal-data').modal('toggle');
    						  var xin_table = $('#xin_table').dataTable({
        							"bDestroy": true,
        							"ajax": {
        								url : "<?php echo site_url("admin/pengaturan/office_reguler_list") ?>",
        								type : 'GET'
        							},
        							dom: 'lBfrtip',
        							// // "buttons": ['csv', 'excel', 'pdf', 'print'], // colvis > if needed
        							"fnDrawCallback": function(settings){
        							$('[data-toggle="tooltip"]').tooltip();          
    							}
  						});
    						
              
              xin_table.api().ajax.reload(function(){ 
                alert_success('Sukses',JSON.result);
              }, true);
  						
              $('input[name="csrf_hris"]').val(JSON.csrf_hash);
  					  $('.save').prop('disabled', false);
  				}

  			}
  		});
	});

	$(".clear-time").click(function(){
		var clear_id  = $(this).data('clear-id');
		$(".clear-"+clear_id).val('');
	});
});	
</script>
<?php } ?>
