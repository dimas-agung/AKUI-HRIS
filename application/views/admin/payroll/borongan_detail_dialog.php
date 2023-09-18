<?php
defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<?php if(isset($_GET['jd']) && isset($_GET['field_id']) && $_GET['data']=='emp_bank_account' && $_GET['type']=='emp_bank_account'){ ?>

    <div class="modal-header"> <?php echo form_button(array('aria-label' => 'Close', 'data-dismiss' => 'modal', 'type' => 'button', 'class' => 'close', 'content' => '<span aria-hidden="true">×</span>')); ?>
      <h4 class="modal-title" id="edit-modal-data"><i class="fa fa-pencil"></i> <?php echo $this->lang->line('xin_e_details_edit_baccount');?></h4>
    </div>
    <?php $attributes = array('name' => 'e_bank_account_info', 'id' => 'e_bank_account_info', 'autocomplete' => 'off');?>
    <?php $hidden = array('u_basic_info' => 'UPDATE');?>
    <?php echo form_open('admin/payroll/e_bank_account_info', $attributes, $hidden);?>
    <?php
    $edata_usr7 = array(
    	'type'  => 'hidden',
    	'id'  => 'user_id',
    	'name'  => 'user_id',
    	'value' => $employee_id,
    );
    echo form_input($edata_usr7);
    ?>
    <?php
    $edata_usr8 = array(
    	'type'  => 'hidden',
    	'id'  => 'e_field_id',
    	'name'  => 'e_field_id',
    	'value' => $bankaccount_id,
    );
    echo form_input($edata_usr8);
    ?>
    <div class="modal-body">
      <div class="row">
        <div class="col-sm-4">          
          <div class="form-group">
            <label for="account_number"><?php echo $this->lang->line('xin_e_details_acc_number');?><i class="hris-asterisk">*</i></label>
            <input class="form-control" placeholder="<?php echo $this->lang->line('xin_e_details_acc_number');?>" name="account_number" type="text" value="<?php echo $account_number;?>" id="account_number">
          </div>
        </div>
        <div class="col-sm-4">
          <div class="form-group">
            <label for="bank_name"><?php echo $this->lang->line('xin_e_details_bank_name');?><i class="hris-asterisk">*</i></label>
            <input class="form-control" placeholder="<?php echo $this->lang->line('xin_e_details_bank_name');?>" name="bank_name" type="text" value="<?php echo $bank_name;?>" id="bank_name">
          </div>          
        </div>
        <div class="col-sm-4">
          <div class="form-group">
            <label for="bank_branch"><?php echo $this->lang->line('xin_e_details_bank_branch');?></label>
            <input class="form-control" placeholder="<?php echo $this->lang->line('xin_e_details_bank_branch');?>" name="bank_branch" type="text" value="<?php echo $bank_branch;?>" id="bank_branch">
          </div>
        </div>
      </div>
      <div class="row">  
        <div class="col-md-5">
          <div class="form-group">
            <label>
              <input type="checkbox" class="custom-control-input" id="is_primary" value="1" name="is_primary" <?php if($is_primary=='1'){?> checked="checked" <?php }?>>
              <span>&nbsp;Rekening Utama</span> 
              </label>
          </div>
        </div>
      </div>
    </div>

    <div class="modal-footer"> 
        <?php echo form_button(array('data-dismiss' => 'modal', 'type' => 'button', 'class' => 'btn btn-default', 'content' => '<i class="fa fa-check-square-o"></i> '.$this->lang->line('xin_close'))); ?> 
        <?php echo form_button(array('name' => 'hris_form', 'type' => 'submit', 'class' => $this->Core_model->form_button_class(), 'content' => '<i class="fa fa-check-square-o"></i> '.$this->lang->line('xin_update'))); ?> 
      </div>
    <?php echo form_close(); ?> 
    
    <script type="text/javascript">
    
    $(document).ready(function(){			
    			
    	/* Update bank acount info */
    	$("#e_bank_account_info").submit(function(e){
        	/*Form Submit*/
        	  e.preventDefault();
        		var obj = $(this), action = obj.attr('name');
        		$('.save').prop('disabled', true);
        		$.ajax({
        			type: "POST",
        			url: e.target.action,
        			data: obj.serialize()+"&is_ajax=17&data=e_bank_account_info&type=e_bank_account_info&form="+action,
        			cache: false,
        			success: function (JSON) {
        				if (JSON.error != '') {
        					alert_fail('Gagal',JSON.error);
        					$('input[name="csrf_hris"]').val(JSON.csrf_hash);
        					$('.save').prop('disabled', false);
        				} else {
        					$('.edit-modal-data').modal('toggle');
        					// On page load:
        					var xin_table_bank_account = $('#xin_table_bank_account').dataTable({
        						"bDestroy": true,
        						"ajax": {
        							url : "<?php echo site_url("admin/payroll/bank_account") ?>/"+$('#user_id').val(),
        							type : 'GET'
        						},
        						"fnDrawCallback": function(settings){
        						$('[data-toggle="tooltip"]').tooltip();          
        						}
        					});
        					xin_table_bank_account.api().ajax.reload(function(){ 
        						alert_success('Sukses',JSON.result);
        						$('input[name="csrf_hris"]').val(JSON.csrf_hash);
        					}, true);
        					$('.save').prop('disabled', false);
        				}
        			}
        		});
    	});


    });	
    </script>

<?php } else if(isset($_GET['jd']) && isset($_GET['field_id']) && $_GET['data']=='salary_commissions_info' && $_GET['type']=='salary_commissions_info'){ ?>
    
    <div class="modal-header"> <?php echo form_button(array('aria-label' => 'Close', 'data-dismiss' => 'modal', 'type' => 'button', 'class' => 'close', 'content' => '<span aria-hidden="true">×</span>')); ?>
      <h4 class="modal-title" id="edit-modal-data"><i class="fa fa-pencil"></i> Edit Tambahan </h4>
    </div>
    
    <?php $attributes = array('name' => 'e_salary_commissions_info', 'id' => 'e_salary_commissions_info', 'autocomplete' => 'off');?>
    <?php $hidden = array('u_basic_info' => 'UPDATE');?>
    <?php echo form_open('admin/payroll/update_commissions_info', $attributes, $hidden);?>
    <?php
    $edata_usr7 = array(
      'type'  => 'hidden',
      'id'  => 'user_id',
      'name'  => 'user_id',
      'value' => $employee_id,
    );
    echo form_input($edata_usr7);
    ?>
    <?php
    $edata_usr8 = array(
      'type'  => 'hidden',
      'id'  => 'e_field_id',
      'name'  => 'e_field_id',
      'value' => $salary_commissions_id,
    );
    echo form_input($edata_usr8);
    ?>
    <div class="modal-body">
      <div class="row">

        <div class="col-md-4"> 
          <div class="form-group">
            <label for="commission_date" class="control-label"><?php echo $this->lang->line('dashboard_xin_date');?><i class="hris-asterisk">*</i></label>
            <input class="form-control date" readonly placeholder="<?php echo $this->lang->line('dashboard_xin_date');?>" name="date" type="text" value="<?php echo $commission_date;?>" >
          </div>
        </div>

        <div class="col-md-4">
          <div class="form-group">
            <label for="title"><?php echo $this->lang->line('dashboard_xin_title');?><i class="hris-asterisk">*</i></label>
            <input class="form-control" placeholder="<?php echo $this->lang->line('dashboard_xin_title');?>" name="title" type="text" value="<?php echo $commission_title;?>">
          </div>
        </div>
        
        <div class="col-md-4">
          <div class="form-group">
            <label for="amount" class="control-label"><?php echo $this->lang->line('xin_amount');?><i class="hris-asterisk">*</i></label>
            <input class="form-control" placeholder="<?php echo $this->lang->line('xin_amount');?>" name="amount" type="number" value="<?php echo $commission_amount;?>">
          </div>
        </div>
      </div>
    </div>
    <div class="modal-footer"> <?php echo form_button(array('data-dismiss' => 'modal', 'type' => 'button', 'class' => 'btn btn-default', 'content' => '<i class="fa fa-check-square-o"></i> '.$this->lang->line('xin_close'))); ?> <?php echo form_button(array('name' => 'hris_form', 'type' => 'submit', 'class' => $this->Core_model->form_button_class(), 'content' => '<i class="fa fa-check-square-o"></i> '.$this->lang->line('xin_update'))); ?> </div>
    <?php echo form_close(); ?> 
    
    <script type="text/javascript">
    $(document).ready(function(){     
          
          $('.date').datepicker({
            changeMonth: true,
            changeYear: true,
            dateFormat:'yy-mm-dd',
            yearRange: '1900:' + (new Date().getFullYear() + 10),
          });

          /* Update location info */
          $("#e_salary_commissions_info").submit(function(e){
          /*Form Submit*/
          e.preventDefault();
            var obj = $(this), action = obj.attr('name');
            $('.save').prop('disabled', true);
            $.ajax({
              type: "POST",
              url: e.target.action,
              data: obj.serialize()+"&is_ajax=29&data=e_salary_commissions_info&type=e_salary_commissions_info&form="+action,
              cache: false,
              success: function (JSON) {
                if (JSON.error != '') {
                  alert_fail('Gagal',JSON.error);
                  $('input[name="csrf_hris"]').val(JSON.csrf_hash);
                  $('.save').prop('disabled', false);
                } else {
                  $('.edit-modal-data').modal('toggle');
                  // On page load:
                  var xin_table_all_commissions = $('#xin_table_all_commissions_borongan').dataTable({
                    "bDestroy": true,
                    "ajax": {
                      url : "<?php echo site_url("admin/payroll/salary_all_commissions_borongan") ?>/"+$('#user_id').val(),
                      type : 'GET'
                    },
                    "fnDrawCallback": function(settings){
                    $('[data-toggle="tooltip"]').tooltip();          
                    }
                  });
                  xin_table_all_commissions.api().ajax.reload(function(){ 
                    alert_success('Sukses',JSON.result);
                    $('input[name="csrf_hris"]').val(JSON.csrf_hash);
                  }, true);
                  $('.save').prop('disabled', false);
                }
              }
            });
          });
    }); 
    </script>

<?php } else if(isset($_GET['jd']) && isset($_GET['field_id']) && $_GET['data']=='salary_commissions_help_info' && $_GET['type']=='salary_commissions_help_info'){ ?>
    
    <div class="modal-header"> <?php echo form_button(array('aria-label' => 'Close', 'data-dismiss' => 'modal', 'type' => 'button', 'class' => 'close', 'content' => '<span aria-hidden="true">×</span>')); ?>
      <h4 class="modal-title" id="edit-modal-data"><i class="fa fa-pencil"></i> Edit Diberbantukan </h4>
    </div>
    
    <?php $attributes = array('name' => 'e_salary_commissions_help_info', 'id' => 'e_salary_commissions_help_info', 'autocomplete' => 'off');?>
    <?php $hidden = array('u_basic_info' => 'UPDATE');?>
    <?php echo form_open('admin/payroll/update_commissions_help_info_borongan', $attributes, $hidden);?>
    <?php
    $edata_usr7 = array(
      'type'  => 'hidden',
      'id'  => 'user_id',
      'name'  => 'user_id',
      'value' => $employee_id,
    );
    echo form_input($edata_usr7);
    ?>
    <?php
    $edata_usr8 = array(
      'type'  => 'hidden',
      'id'  => 'e_field_id',
      'name'  => 'e_field_id',
      'value' => $salary_commissions_id,
    );
    echo form_input($edata_usr8);
    ?>
    <div class="modal-body">
      <div class="row">

        <div class="col-md-4"> 
          <div class="form-group">
            <label for="commission_date" class="control-label"><?php echo $this->lang->line('dashboard_xin_date');?><i class="hris-asterisk">*</i></label>
            <input class="form-control date" readonly placeholder="<?php echo $this->lang->line('dashboard_xin_date');?>" name="commission_date" type="text" value="<?php echo $commission_date;?>" >
          </div>
        </div>

        <div class="col-md-4">
          <div class="form-group">
            <label for="title"><?php echo $this->lang->line('dashboard_xin_title');?><i class="hris-asterisk">*</i></label>
            <input class="form-control" placeholder="<?php echo $this->lang->line('dashboard_xin_title');?>" name="commission_title" type="text" value="<?php echo $commission_title;?>">
          </div>
        </div>
        
        <div class="col-md-4">
          <div class="form-group">
            <label for="amount" class="control-label"><?php echo $this->lang->line('xin_amount');?><i class="hris-asterisk">*</i></label>
            <input class="form-control" placeholder="<?php echo $this->lang->line('xin_amount');?>" name="commission_amount" type="number" value="<?php echo $commission_amount;?>">
          </div>
        </div>
      </div>
    </div>
    <div class="modal-footer"> <?php echo form_button(array('data-dismiss' => 'modal', 'type' => 'button', 'class' => 'btn btn-default', 'content' => '<i class="fa fa-check-square-o"></i> '.$this->lang->line('xin_close'))); ?> <?php echo form_button(array('name' => 'hris_form', 'type' => 'submit', 'class' => $this->Core_model->form_button_class(), 'content' => '<i class="fa fa-check-square-o"></i> '.$this->lang->line('xin_update'))); ?> </div>
    <?php echo form_close(); ?> 
    
    <script type="text/javascript">
    $(document).ready(function(){     
          
          $('.date').datepicker({
            changeMonth: true,
            changeYear: true,
            dateFormat:'yy-mm-dd',
            yearRange: '1900:' + (new Date().getFullYear() + 10),
          });

          /* Update location info */
          $("#e_salary_commissions_help_info").submit(function(e){
          /*Form Submit*/
          e.preventDefault();
            var obj = $(this), action = obj.attr('name');
            $('.save').prop('disabled', true);
            $.ajax({
              type: "POST",
              url: e.target.action,
              data: obj.serialize()+"&is_ajax=29&data=e_salary_commissions_help_info&type=e_salary_commissions_help_info&form="+action,
              cache: false,
              success: function (JSON) {
                if (JSON.error != '') {
                  alert_fail('Gagal',JSON.error);
                  $('input[name="csrf_hris"]').val(JSON.csrf_hash);
                  $('.save').prop('disabled', false);
                } else {
                  $('.edit-modal-data').modal('toggle');
                  // On page load:
                  var xin_table_all_commissions_help = $('#xin_table_all_commissions_help_borongan').dataTable({
                    "bDestroy": true,
                    "ajax": {
                      url : "<?php echo site_url("admin/payroll/salary_all_commissions_help_borongan") ?>/"+$('#user_id').val(),
                      type : 'GET'
                    },
                    "fnDrawCallback": function(settings){
                    $('[data-toggle="tooltip"]').tooltip();          
                    }
                  });
                  xin_table_all_commissions_help.api().ajax.reload(function(){ 
                    alert_success('Sukses',JSON.result);
                    $('input[name="csrf_hris"]').val(JSON.csrf_hash);
                  }, true);
                  $('.save').prop('disabled', false);
                }
              }
            });
          });
    }); 
    </script>

<?php } else if(isset($_GET['jd']) && isset($_GET['field_id']) && $_GET['data']=='salary_statutory_deductions_info_borongan' && $_GET['type']=='salary_statutory_deductions_info'){ ?>
    
    <div class="modal-header"> <?php echo form_button(array('aria-label' => 'Close', 'data-dismiss' => 'modal', 'type' => 'button', 'class' => 'close', 'content' => '<span aria-hidden="true">×</span>')); ?>
      <h4 class="modal-title" id="edit-modal-data"><i class="fa fa-pencil"></i> <?php echo $this->lang->line('xin_employee_edit_allowance');?></h4>
    </div>
    <?php $attributes = array('name' => 'e_salary_statutory_deductions_info_borongan', 'id' => 'e_salary_statutory_deductions_info_borongan', 'autocomplete' => 'off');?>
    <?php $hidden = array('u_basic_info' => 'UPDATE');?>
    <?php echo form_open('admin/payroll/update_statutory_deductions_info_borongan', $attributes, $hidden);?>
    <?php
    $edata_usr7 = array(
      'type'  => 'hidden',
      'id'  => 'user_id',
      'name'  => 'user_id',
      'value' => $employee_id,
    );
    echo form_input($edata_usr7);
    ?>
    <?php
    $edata_usr8 = array(
      'type'  => 'hidden',
      'id'  => 'e_field_id',
      'name'  => 'e_field_id',
      'value' => $statutory_deductions_id,
    );
    echo form_input($edata_usr8);
    ?>
    <?php $system = $this->Core_model->read_setting_info(1);?>
    <div class="modal-body">
      <div class="row">
        <div class="col-md-3">
          <div class="form-group">
             <label for="month_year"><?php echo $this->lang->line('dashboard_xin_date');?><i class="hris-asterisk">*</i></label>
            <input class="form-control e_date" placeholder="<?php echo $this->lang->line('dashboard_xin_date');?>" readonly="readonly" name="deduction_date" type="text" value="<?php echo $deduction_date;?>">
          </div>
        </div>

        <div class="col-md-3">
          <div class="form-group">
            <label for="statutory_options"><?php echo $this->lang->line('xin_salary_sd_options');?><i class="hris-asterisk">*</i></label>
            <select name="statutory_options" id="statutory_options" class="form-control" data-plugin="select_hrm">
              <option value="1" <?php if($statutory_options==1):?> selected="selected"<?php endif;?>><?php echo $this->lang->line('xin_sd_ssc_title');?></option>
              <option value="2" <?php if($statutory_options==2):?> selected="selected"<?php endif;?>><?php echo $this->lang->line('xin_sd_phic_title');?></option>
            </select>
          </div>
        </div>
        <div class="col-md-3">
          <div class="form-group">
            <label for="title"><?php echo $this->lang->line('dashboard_xin_title_no');?><i class="hris-asterisk">*</i></label>
            <input class="form-control" placeholder="<?php echo $this->lang->line('dashboard_xin_title_no');?>" name="title" type="text" value="<?php echo $deduction_title;?>">
          </div>
        </div>
        <div class="col-md-3">
          <div class="form-group">
            <label for="amount" class="control-label"><?php echo $this->lang->line('xin_amount');?> <?php if($system[0]->statutory_fixed!='yes'):?> (%) <?php endif;?><i class="hris-asterisk">*</i></label>
            <input class="form-control" placeholder="<?php echo $this->lang->line('xin_amount');?>" name="amount" type="number" value="<?php echo $deduction_amount;?>">
          </div>
        </div>
      </div>
    </div>
    <div class="modal-footer"> <?php echo form_button(array('data-dismiss' => 'modal', 'type' => 'button', 'class' => 'btn btn-default', 'content' => '<i class="fa fa-check-square-o"></i> '.$this->lang->line('xin_close'))); ?> <?php echo form_button(array('name' => 'hris_form', 'type' => 'submit', 'class' => $this->Core_model->form_button_class(), 'content' => '<i class="fa fa-check-square-o"></i> '.$this->lang->line('xin_update'))); ?> </div>
    <?php echo form_close(); ?> 
    <script type="text/javascript">
    $(document).ready(function(){     
           
      $('.e_date').datepicker({
            changeMonth: true,
            changeYear: true,
            dateFormat:'yy-mm-dd',
            yearRange: '1900:' + (new Date().getFullYear() + 10),
       });

      /* Update location info */
      $("#e_salary_statutory_deductions_info_borongan").submit(function(e){
          /*Form Submit*/
          e.preventDefault();
          var obj = $(this), action = obj.attr('name');
          $('.save').prop('disabled', true);
          $.ajax({
            type: "POST",
            url: e.target.action,
            data: obj.serialize()+"&is_ajax=29&data=e_salary_statutory_deductions_info_borongan&type=e_salary_statutory_deductions_info_borongan&form="+action,
            cache: false,
            success: function (JSON) {
              if (JSON.error != '') {
                alert_fail('Gagal',JSON.error);
                $('input[name="csrf_hris"]').val(JSON.csrf_hash);
                $('.save').prop('disabled', false);
              } else {
                $('.edit-modal-data').modal('toggle');
                // On page load:
                var xin_table_all_statutory_deductions = $('#xin_table_all_statutory_deductions_borongan').dataTable({
                  "bDestroy": true,
                  "ajax": {
                    url : "<?php echo site_url("admin/payroll/salary_all_statutory_deductions_borongan") ?>/"+$('#user_id').val(),
                    type : 'GET'
                  },
                  "fnDrawCallback": function(settings){
                  $('[data-toggle="tooltip"]').tooltip();          
                  }
                });
                xin_table_all_statutory_deductions.api().ajax.reload(function(){ 
                  alert_success('Sukses',JSON.result);
                  $('input[name="csrf_hris"]').val(JSON.csrf_hash);
                }, true);
                $('.save').prop('disabled', false);
              }
            }
          });
      });

    }); 
    </script>

<?php } else if(isset($_GET['jd']) && isset($_GET['field_id']) && $_GET['data']=='salary_other_payments_info' && $_GET['type']=='salary_other_payments_info'){ ?>
    
    <div class="modal-header"> <?php echo form_button(array('aria-label' => 'Close', 'data-dismiss' => 'modal', 'type' => 'button', 'class' => 'close', 'content' => '<span aria-hidden="true">×</span>')); ?>
      <h4 class="modal-title" id="edit-modal-data"><i class="fa fa-pencil"></i> <?php echo $this->lang->line('xin_edit').' '.$this->lang->line('xin_employee_set_other_payment');?></h4>
    </div>
    <?php $attributes = array('name' => 'e_salary_other_payments_info', 'id' => 'e_salary_other_payments_info', 'autocomplete' => 'off');?>
    <?php $hidden = array('u_basic_info' => 'UPDATE');?>
    <?php echo form_open('admin/payroll/update_other_payment_info', $attributes, $hidden);?>
    <?php
    $edata_usr7 = array(
      'type'  => 'hidden',
      'id'  => 'user_id',
      'name'  => 'user_id',
      'value' => $employee_id,
    );
    echo form_input($edata_usr7);
    ?>
    <?php
    $edata_usr8 = array(
      'type'  => 'hidden',
      'id'  => 'e_field_id',
      'name'  => 'e_field_id',
      'value' => $other_payments_id,
    );
    echo form_input($edata_usr8);
    ?>
    <div class="modal-body">
      <div class="row">
        
        <div class="col-md-3">
          <div class="form-group">
             <label for="month_year"><?php echo $this->lang->line('dashboard_xin_date');?><i class="hris-asterisk">*</i></label>
            <input class="form-control e_date" placeholder="<?php echo $this->lang->line('dashboard_xin_date');?>" readonly="readonly" name="allowance_date" type="text" value="<?php echo $allowance_date;?>">
          </div>
        </div>     

        <div class="col-md-3">
          <div class="form-group">
            <label for="title"><?php echo $this->lang->line('dashboard_xin_title');?><i class="hris-asterisk">*</i></label>
            <input class="form-control" placeholder="<?php echo $this->lang->line('dashboard_xin_title');?>" name="title" type="text" value="<?php echo $payments_title;?>">
          </div>
        </div>

        <div class="col-md-3">
          <div class="form-group">
            <label for="amount" class="control-label"><?php echo $this->lang->line('xin_amount');?><i class="hris-asterisk">*</i></label>
            <input class="form-control" placeholder="<?php echo $this->lang->line('xin_amount');?>" name="amount" type="number" value="<?php echo $payments_amount;?>">
          </div>
        </div>

      </div>
    </div>

    <div class="modal-footer"> 
          <?php echo form_button(array('data-dismiss' => 'modal', 'type' => 'button', 'class' => 'btn btn-default', 'content' => '<i class="fa fa-check-square-o"></i> '.$this->lang->line('xin_close'))); ?> 
          <?php echo form_button(array('name' => 'hris_form', 'type' => 'submit', 'class' => $this->Core_model->form_button_class(), 'content' => '<i class="fa fa-check-square-o"></i> '.$this->lang->line('xin_update'))); ?> 
    </div>
   
    <?php echo form_close(); ?> 
    <script type="text/javascript">
    $(document).ready(function(){     
       $('.e_date').datepicker({
          changeMonth: true,
          changeYear: true,
          dateFormat:'yy-mm-dd',
          yearRange: '1900:' + (new Date().getFullYear() + 10),
        });
            
      /* Update location info */
      $("#e_salary_other_payments_info").submit(function(e){
      /*Form Submit*/
      e.preventDefault();
        var obj = $(this), action = obj.attr('name');
        $('.save').prop('disabled', true);
        $.ajax({
          type: "POST",
          url: e.target.action,
          data: obj.serialize()+"&is_ajax=29&data=e_salary_other_payments_info&type=e_salary_other_payments_info&form="+action,
          cache: false,
          success: function (JSON) {
            if (JSON.error != '') {
              alert_fail('Gagal',JSON.error);
              $('input[name="csrf_hris"]').val(JSON.csrf_hash);
              $('.save').prop('disabled', false);
            } else {
              $('.edit-modal-data').modal('toggle');
              // On page load:
              var xin_table_all_other_payments = $('#xin_table_all_other_payments').dataTable({
                "bDestroy": true,
                "ajax": {
                  url : "<?php echo site_url("admin/payroll/salary_all_other_payments") ?>/"+$('#user_id').val(),
                  type : 'GET'
                },
                "fnDrawCallback": function(settings){
                $('[data-toggle="tooltip"]').tooltip();          
                }
              });
              xin_table_all_other_payments.api().ajax.reload(function(){ 
                alert_success('Sukses',JSON.result);
                $('input[name="csrf_hris"]').val(JSON.csrf_hash);
              }, true);
              $('.save').prop('disabled', false);
            }
          }
        });
      });
    }); 
    </script>

<?php } ?>
