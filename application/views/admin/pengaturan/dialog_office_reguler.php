<?php
defined('BASEPATH') OR exit('No direct script access allowed');
if(isset($_GET['jd']) && isset($_GET['office_shift_id']) && $_GET['data']=='reguler'){
?>

  <?php $assigned_ids = explode(',',$employee_id);  ?>

  <?php $session = $this->session->userdata('username');?>
  <?php $user_info = $this->Core_model->read_user_info($session['user_id']);?>

  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">×</span> </button>
    <h4 class="modal-title" id="edit-modal-data"><i class="fa fa-pencil"></i> Edit Pola Kerja Reguler</h4>
  </div>

  <?php $attributes = array('name' => 'edit_office_reguler', 'id' => 'edit_office_reguler', 'autocomplete' => 'off', 'class'=>'m-b-1');?>
  <?php $hidden     = array('_method' => 'EDIT', '_token' => $office_shift_id, 'ext_name' => $office_shift_id);?>

  <?php echo form_open('admin/pengaturan/edit_office_reguler/'.$office_shift_id, $attributes, $hidden);?>

  <div class="modal-body">
    <div class="row">
      <div class="col-md-6"> 

         <div class="row">
          <div class="col-md-12">
            <div class="form-group">
              <label for="date"><?php echo $this->lang->line('left_company');?></label>
                <select class="form-control input-sm" name="company_id" id="aj_company" data-plugin="select_hrm" data-placeholder="<?php echo $this->lang->line('left_company');?>">
                  <option value=""></option>
                  <?php foreach($get_all_companies as $company) {?>
                  <option value="<?php echo $company->company_id?>" <?php if($company->company_id==$company_id):?> selected="selected"<?php endif;?>><?php echo $company->name?></option>
                  <?php } ?>
                </select>
            </div>
          </div>
        </div>        
     
         <div class="row">
          <div class="col-md-12">
            <div class="form-group">
              <label for="date"><?php echo $this->lang->line('xin_e_details_month_work');?></label>
               <select class="form-control input-sm" name="payroll_id" id="aj_company" data-plugin="select_hrm" data-placeholder="<?php echo $this->lang->line('xin_e_details_month_work');?>">
                 <?php foreach($all_bulan_gaji as $bulan_gaji) {?>
                <option value="<?php echo $bulan_gaji->payroll_id?>" <?php if($bulan_gaji->payroll_id==$payroll_id):?> selected="selected"<?php endif;?>><?php echo $bulan_gaji->desc?></option>
             <?php } ?>
            </select>
            </div>
          </div>
        </div>        
        
        <div class="row">
          <div class="col-md-12">
            <div class="form-group">
              <label for="date"><?php echo $this->lang->line('xin_e_details_timeperiod');?></label>
                <div class="row">
                <div class="col-md-6">
                  <div class="form-group">
                    <input class="form-control input-sm attendance_date" placeholder="<?php echo $this->lang->line('xin_select_date');?>" readonly="1"  name="start_date" type="text"  value="<?php echo $start_date;?>" >
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <input class="form-control input-sm attendance_date" placeholder="<?php echo $this->lang->line('xin_select_date');?>" readonly="1"  name="end_date" type="text" value="<?php echo $end_date;?>" >
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>


        <div class="row">
          <div class="col-md-12">
            <div class="form-group">
              <label for="date"><?php echo $this->lang->line('xin_shift_name');?></label>
              <input class="form-control input-sm" placeholder="<?php echo $this->lang->line('xin_shift_name');?>" name="shift_name" type="text" id="name" value="<?php echo $shift_name;?>">
            </div>
          </div>
        </div>      

        <div class="row">
          <div class="col-md-12">
            <div class="form-group">
              <label for="date"><?php echo $this->lang->line('xin_employee_jenis_pola');?></label>
               <select class="form-control input-sm" name="jenis" data-plugin="select_hrm" data-placeholder="<?php echo $this->lang->line('xin_employee_status_txt');?>">
                  <option value="" <?php if($jenis==''):?> selected <?php endif; ?>><?php echo $this->lang->line('xin_select_one');?></option>
                  <option value="1" <?php if($jenis=='1'):?> selected <?php endif; ?>><?php echo $this->lang->line('xin_employee_reguler');?></option>
                  <!-- <option value="2" <?php if($jenis=='2'):?> selected <?php endif; ?>><?php echo $this->lang->line('xin_employee_shift');?></option>                                                          -->
                </select>

              
            </div>
          </div>
        </div>   

        <!-- <?php $result = $this->Department_model->ajax_company_employee_info($company_id);?> -->
        
       <!--  <div class="row">
          <div class="col-md-12">
            <div class="form-group" id="employee_ajx">
              <label for="employee" class="control-label"><?php echo $this->lang->line('xin_employee_list');?></label>
              <select multiple class="form-control input-sm" name="employee_id[]" data-plugin="select_hrm" data-placeholder="<?php echo $this->lang->line('xin_employee_list');?>">
                <option value=""></option>
                <?php foreach($result as $employee) {?>
                <option value="<?php echo $employee->user_id;?>" <?php if(in_array($employee->user_id,$assigned_ids)):?> selected <?php endif; ?>><?php echo $employee->first_name.' '.$employee->last_name;?></option>
                <?php } ?>
              </select>
            </div>
          </div>
        </div> -->
        
      </div>

      <div class="col-md-6">   

        <div style="background-color: #cbf3e6;padding: 10px;margin-bottom: 15px;">
          Jadwal Kerja Harian :
        </div>      

        <div class="form-group row">
          <label for="time" class="col-md-2"><?php echo $this->lang->line('xin_monday');?></label>
          <div class="col-md-4">
            <input class="form-control timepicker clear-1" placeholder="<?php echo $this->lang->line('xin_in_time');?>" readonly="1" name="monday_in_time" type="text" value="<?php echo $monday_in_time;?>">
          </div>
          <div class="col-md-4">
            <input class="form-control timepicker clear-1" placeholder="<?php echo $this->lang->line('xin_out_time');?>" readonly="1" name="monday_out_time" type="text" value="<?php echo $monday_out_time;?>">
          </div>
          <div class="col-md-1">
            <button type="button" class="btn btn-sm btn-danger clear-time" data-clear-id="1"><i class="fa fa-times"></i></button>
          </div>
        </div>
        
        <div class="form-group row">
          <label for="time" class="col-md-2"><?php echo $this->lang->line('xin_tuesday');?></label>
          <div class="col-md-4">
            <input class="form-control timepicker clear-2" placeholder="<?php echo $this->lang->line('xin_in_time');?>" readonly="1" name="tuesday_in_time" type="text" value="<?php echo $tuesday_in_time;?>">
          </div>
          <div class="col-md-4">
            <input class="form-control timepicker clear-2" placeholder="<?php echo $this->lang->line('xin_out_time');?>" readonly="1" name="tuesday_out_time" type="text" value="<?php echo $tuesday_out_time;?>">
          </div>
          <div class="col-md-1">
            <button type="button" class="btn btn-sm btn-danger clear-time" data-clear-id="2"><i class="fa fa-times"></i></button>
          </div>
        </div>
        <div class="form-group row">
          <label for="time" class="col-md-2"><?php echo $this->lang->line('xin_wednesday');?></label>
          <div class="col-md-4">
            <input class="form-control timepicker clear-3" placeholder="<?php echo $this->lang->line('xin_in_time');?>" readonly="1" name="wednesday_in_time" type="text" value="<?php echo $wednesday_in_time;?>">
          </div>
          <div class="col-md-4">
            <input class="form-control timepicker clear-3" placeholder="<?php echo $this->lang->line('xin_out_time');?>" readonly="1" name="wednesday_out_time" type="text" value="<?php echo $wednesday_out_time;?>">
          </div>
          <div class="col-md-1">
            <button type="button" class="btn btn-sm btn-danger clear-time" data-clear-id="3"><i class="fa fa-times"></i></button>
          </div>
        </div>
        <div class="form-group row">
          <label for="time" class="col-md-2"><?php echo $this->lang->line('xin_thursday');?></label>
          <div class="col-md-4">
            <input class="form-control timepicker clear-4" placeholder="<?php echo $this->lang->line('xin_in_time');?>" readonly="1" name="thursday_in_time" type="text" value="<?php echo $thursday_in_time;?>">
          </div>
          <div class="col-md-4">
            <input class="form-control timepicker clear-4" placeholder="<?php echo $this->lang->line('xin_out_time');?>" readonly="1" name="thursday_out_time" type="text" value="<?php echo $thursday_out_time;?>">
          </div>
          <div class="col-md-1">
            <button type="button" class="btn btn-sm btn-danger clear-time" data-clear-id="4"><i class="fa fa-times"></i></button>
          </div>
        </div>
        <div class="form-group row">
          <label for="time" class="col-md-2"><?php echo $this->lang->line('xin_friday');?></label>
          <div class="col-md-4">
            <input class="form-control timepicker clear-5" placeholder="<?php echo $this->lang->line('xin_in_time');?>" readonly="1" name="friday_in_time" type="text" value="<?php echo $friday_in_time;?>">
          </div>
          <div class="col-md-4">
            <input class="form-control timepicker clear-5" placeholder="<?php echo $this->lang->line('xin_out_time');?>" readonly="1" name="friday_out_time" type="text" value="<?php echo $friday_out_time;?>">
          </div>
          <div class="col-md-1">
            <button type="button" class="btn btn-sm btn-danger clear-time" data-clear-id="5"><i class="fa fa-times"></i></button>
          </div>
        </div>
        <div class="form-group row">
          <label for="time" class="col-md-2"><?php echo $this->lang->line('xin_saturday');?></label>
          <div class="col-md-4">
            <input class="form-control timepicker clear-6" placeholder="<?php echo $this->lang->line('xin_in_time');?>" readonly="1" name="saturday_in_time" type="text" value="<?php echo $saturday_in_time;?>">
          </div>
          <div class="col-md-4">
            <input class="form-control timepicker clear-6" placeholder="<?php echo $this->lang->line('xin_out_time');?>" readonly="1" name="saturday_out_time" type="text" value="<?php echo $saturday_out_time;?>">
          </div>
          <div class="col-md-1">
            <button type="button" class="btn btn-sm btn-danger clear-time" data-clear-id="6"><i class="fa fa-times"></i></button>
          </div>
        </div>
        <div class="form-group row">
          <label for="time" class="col-md-2"><?php echo $this->lang->line('xin_sunday');?></label>
          <div class="col-md-4">
            <input class="form-control timepicker clear-7" placeholder="<?php echo $this->lang->line('xin_in_time');?>" readonly="1" name="sunday_in_time" type="text" value="<?php echo $sunday_in_time;?>">
          </div>
          <div class="col-md-4">
            <input class="form-control timepicker clear-7" placeholder="<?php echo $this->lang->line('xin_out_time');?>" readonly="1" name="sunday_out_time" type="text" value="<?php echo $sunday_out_time;?>">
          </div>
          <div class="col-md-1">
            <button type="button" class="btn btn-sm btn-danger clear-time" data-clear-id="7"><i class="fa fa-times"></i></button>
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

        jQuery("#ajx_company").change(function(){
            jQuery.get(base_url+"/get_employees/"+jQuery(this).val(), function(data, status){
              jQuery('#employee_ajx').html(data);
            });            
        });
  								
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
        	$("#edit_office_reguler").submit(function(e){
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
          				    

                  	  $('.edit-modal-data').modal('toggle');
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
