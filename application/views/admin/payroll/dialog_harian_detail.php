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

<?php } else if(isset($_GET['jd']) && isset($_GET['field_id']) && $_GET['data']=='e_salary_allowance' && $_GET['type']=='e_salary_allowance') { ?>
    

    <div class="modal-header"> 
      <?php echo form_button(array('aria-label' => 'Close', 'data-dismiss' => 'modal', 'type' => 'button', 'class' => 'close', 'content' => '<span aria-hidden="true">×</span>')); ?>
      <h4 class="modal-title" id="edit-modal-data"><i class="fa fa-pencil"></i> <?php echo $this->lang->line('xin_employee_edit_allowance');?></h4>
    </div>

        <?php $attributes = array('name' => 'e_allowance_info', 'id' => 'e_allowance_info', 'autocomplete' => 'off');?>
        <?php $hidden     = array('u_basic_info' => 'UPDATE');?>
        <?php echo form_open('admin/payroll/update_allowance_info', $attributes, $hidden);?>
        <?php
          $edata_usr7 = array(
          	'type'  => 'hidden',
          	'id'    => 'user_id',
          	'name'  => 'user_id',
          	'value' => $employee_id,
          );
          echo form_input($edata_usr7);
          ?>
          <?php
          $edata_usr8 = array(
          	'type'  => 'hidden',
          	'id'    => 'e_field_id',
          	'name'  => 'e_field_id',
          	'value' => $allowance_id,
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
      </div>

      <div class="row">
        <div class="col-md-3">
          <div class="form-group">
            <label for="tnj_jabatan" class="control-label">Tnj Jabatan <i class="hris-asterisk">*</i></label>
            <input class="form-control" placeholder="Tnj Jabatan" name="tnj_jabatan" type="number" value="<?php echo $tnj_jabatan;?>">
          </div>
        </div>       
        <div class="col-md-3">
          <div class="form-group">
            <label for="tnj_produktifitas" class="control-label">Tnj Produktifitas <i class="hris-asterisk">*</i></label>
            <input class="form-control" placeholder="Tnj Produktifitas" name="tnj_produktifitas" type="number" value="<?php echo $tnj_produktifitas;?>">
          </div>
        </div>
         <div class="col-md-3">
          <div class="form-group">
            <label for="tnj_komunikasi" class="control-label">Tnj Komunikasi <i class="hris-asterisk">*</i></label>
            <input class="form-control" placeholder="Tnj Komunikasi" name="tnj_komunikasi" type="number" value="<?php echo $tnj_komunikasi;?>">
          </div>
        </div>       
        <div class="col-md-3">
          <div class="form-group">
            <label for="tnj_transportasi" class="control-label">Tnj Transportasi <i class="hris-asterisk">*</i></label>
            <input class="form-control" placeholder="Tnj Transportasi" name="tnj_transportasi" type="number" value="<?php echo $tnj_transportasi;?>">
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
        	$("#e_allowance_info").submit(function(e){
          	/*Form Submit*/
          	  e.preventDefault();
          		var obj = $(this), action = obj.attr('name');
          		$('.save').prop('disabled', true);
          		$.ajax({
          			type: "POST",
          			url: e.target.action,
          			data: obj.serialize()+"&is_ajax=29&data=e_allowance_info&type=e_allowance_info&form="+action,
          			cache: false,
          			success: function (JSON) {
          				
                  if (JSON.error != '') {
          				
                  	alert_fail('Gagal',JSON.error);
          					$('input[name="csrf_hris"]').val(JSON.csrf_hash);
          					$('.save').prop('disabled', false);
          				
                  } else {
          					
                    $('.edit-modal-data').modal('toggle');
          				
                  	// On page load:
                   
          					var xin_table_all_allowances = $('#xin_table_all_allowances').dataTable({
          						"bDestroy": true,
          						"ajax": {
          							url : "<?php echo site_url("admin/payroll/salary_all_allowances") ?>/"+$('#user_id').val(),
          							type : 'GET'
          						},
                      "columns": [
                          {"name": "kolom_1","orderable": false,"searchable": false,  "className": "text-center", "width": "8%"},
                          {"name": "kolom_2", "className": "text-center"},        
                          {"name": "kolom_3", "className": "text-right"},
                          {"name": "kolom_4", "className": "text-right"},
                          {"name": "kolom_5", "className": "text-right"},
                          {"name": "kolom_6", "className": "text-right"},     
                      ],
          						"fnDrawCallback": function(settings){
            						$('[data-toggle="tooltip"]').tooltip();          
            						}
            					});
            				
                  	xin_table_all_allowances.api().ajax.reload(function(){ 
          				
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

<?php } else if(isset($_GET['jd']) && isset($_GET['field_id']) && $_GET['data']=='e_salary_loan' && $_GET['type']=='e_salary_loan'){ ?>
    
    <div class="modal-header"> <?php echo form_button(array('aria-label' => 'Close', 'data-dismiss' => 'modal', 'type' => 'button', 'class' => 'close', 'content' => '<span aria-hidden="true">×</span>')); ?>
      <h4 class="modal-title" id="edit-modal-data"><i class="fa fa-pencil"></i> <?php echo $this->lang->line('xin_employee_edit_loan_title');?></h4>
    </div>
    
    <?php $attributes = array('name' => 'e_salary_loan_info', 'id' => 'e_salary_loan_info', 'autocomplete' => 'off');?>
    <?php $hidden     = array('u_basic_info' => 'UPDATE');?>
    <?php echo form_open('admin/payroll/update_loan_info', $attributes, $hidden);?>
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
        	'value' => $loan_deduction_id,
        );
        echo form_input($edata_usr8);
    ?>
    <div class="modal-body">
      <div class="row">
          <div class="col-md-12">
            <div class="row">
              <div class="col-md-6">
                  <div class="form-group">
                    <label for="month_year">Tanggal Pinjam <i class="hris-asterisk">*</i></label>
                    <input class="form-control d_month_year" placeholder="Tanggal Pinjam" readonly="readonly" name="loan_date" type="text">
                  </div>
                </div>
              <div class="col-md-6">
                  <div class="form-group">
                    <label for="loan_options"><?php echo $this->lang->line('xin_salary_loan_options');?><i class="hris-asterisk">*</i></label>
                    <select name="loan_options" id="loan_options" class="form-control" data-plugin="select_hrm">
                      <option value="1"<?php if($loan_options==1):?> selected="selected" <?php endif;?>><?php echo $this->lang->line('xin_loan_ssc_title');?></option>
                      <option value="2"<?php if($loan_options==2):?> selected="selected" <?php endif;?>><?php echo $this->lang->line('xin_loan_hdmf_title');?></option>
                      <option value="0"<?php if($loan_options==0):?> selected="selected" <?php endif;?>><?php echo $this->lang->line('xin_loan_other_sd_title');?></option>
                    </select>
                  </div>
                </div>
                <div class="col-md-6">
                <div class="form-group">
                   <label for="month_year"><?php echo $this->lang->line('dashboard_xin_title');?><i class="hris-asterisk">*</i></label>
                  <input class="form-control" placeholder="<?php echo $this->lang->line('dashboard_xin_title');?>" name="loan_deduction_title" type="text" value="<?php echo $loan_deduction_title;?>">
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label for="edu_role"><?php echo $this->lang->line('xin_employee_monthly_installment_title');?><i class="hris-asterisk">*</i></label>
                  <input class="form-control" placeholder="<?php echo $this->lang->line('xin_employee_monthly_installment_title');?>" name="monthly_installment" type="text" id="m_monthly_installment" value="<?php echo $monthly_installment;?>">
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                   <label for="month_year"><?php echo $this->lang->line('xin_start_date');?><i class="hris-asterisk">*</i></label>
                  <input class="form-control d_month_year" placeholder="<?php echo $this->lang->line('xin_start_date');?>" readonly="readonly" name="start_date" type="text" value="<?php echo $start_date;?>">
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label for="end_date"><?php echo $this->lang->line('xin_end_date');?><i class="hris-asterisk">*</i></label>
                  <input class="form-control d_month_year" readonly="readonly" placeholder="<?php echo $this->lang->line('xin_end_date');?>" name="end_date" type="text" value="<?php echo $end_date;?>">
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-12">
                <div class="form-group">
                  <label for="description"><?php echo $this->lang->line('xin_reason');?></label>
                  <textarea class="form-control textarea" placeholder="<?php echo $this->lang->line('xin_reason');?>" name="reason" cols="30" rows="2" id="reason2"><?php echo $reason;?></textarea>
                </div>
              </div>
            </div>
          </div>
        </div>
    </div>

    <div class="modal-footer"> 
      <?php echo form_button(array('data-dismiss' => 'modal', 'type' => 'button', 'class' => 'btn btn-default', 'content' => '<i class="fa fa-check-square-o"></i> '.$this->lang->line('xin_close'))); ?> <?php echo form_button(array('name' => 'hris_form', 'type' => 'submit', 'class' => $this->Core_model->form_button_class(), 'content' => '<i class="fa fa-check-square-o"></i> '.$this->lang->line('xin_update'))); ?> 
    </div>
   

    <?php echo form_close(); ?> 
    
    <script type="text/javascript">
        $(document).ready(function(){		
        	$('[data-plugin="select_hrm"]').select2($(this).attr('data-options'));
        	$('[data-plugin="select_hrm"]').select2({ width:'100%' });

        	// Month & Year
        	$('.d_month_year').datepicker({
        	  changeMonth: true,
        	  changeYear: true,
        	  dateFormat:'yy-mm-dd',
        	  yearRange: '1990:' + (new Date().getFullYear() + 10),
        	});	
        				
        	/* Update location info */
        	$("#e_salary_loan_info").submit(function(e){
        	/*Form Submit*/
        	e.preventDefault();
        		var obj = $(this), action = obj.attr('name');
        		$('.save').prop('disabled', true);
        		$.ajax({
        			type: "POST",
        			url: e.target.action,
        			data: obj.serialize()+"&is_ajax=29&data=loan_info&type=loan_info&form="+action,
        			cache: false,
        			success: function (JSON) {
        				if (JSON.error != '') {
        					alert_fail('Gagal',JSON.error);
        					$('input[name="csrf_hris"]').val(JSON.csrf_hash);
        					$('.save').prop('disabled', false);
        				} else {
        					$('.edit-modal-data').modal('toggle');
        					// On page load:
        					var xin_table_all_deductions = $('#xin_table_all_deductions').dataTable({
        						"bDestroy": true,
        						"ajax": {
        							url : "<?php echo site_url("admin/payroll/salary_all_deductions").'/'.$employee_id; ?>/",
        							type : 'GET'
        						},
        						"fnDrawCallback": function(settings){
        						$('[data-toggle="tooltip"]').tooltip();          
        						}
        					});
        					xin_table_all_deductions.api().ajax.reload(function(){ 
        						alert_success('Sukses',JSON.result);
        					}, true);
        					$('input[name="csrf_hris"]').val(JSON.csrf_hash);
        					$('.save').prop('disabled', false);
        				}
        			}
        		});
        	});
        });	
    </script>

<?php } else if(isset($_GET['jd']) && isset($_GET['field_id']) && $_GET['data']=='salary_commissions_info' && $_GET['type']=='salary_commissions_info'){ ?>
    
    <div class="modal-header"> <?php echo form_button(array('aria-label' => 'Close', 'data-dismiss' => 'modal', 'type' => 'button', 'class' => 'close', 'content' => '<span aria-hidden="true">×</span>')); ?>
      <h4 class="modal-title" id="edit-modal-data"><i class="fa fa-pencil"></i> Edit Insentif </h4>
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
              var xin_table_all_commissions = $('#xin_table_all_commissions_harian').dataTable({
                "bDestroy": true,
                "ajax": {
                  url : "<?php echo site_url("admin/payroll/salary_all_commissions_harian") ?>/"+$('#user_id').val(),
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



<?php } else if(isset($_GET['jd']) && isset($_GET['field_id']) && $_GET['data']=='salary_gapok_info' && $_GET['type']=='salary_gapok_info'){ ?>
    
    <div class="modal-header"> <?php echo form_button(array('aria-label' => 'Close', 'data-dismiss' => 'modal', 'type' => 'button', 'class' => 'close', 'content' => '<span aria-hidden="true">×</span>')); ?>
      <h4 class="modal-title" id="edit-modal-data"><i class="fa fa-pencil"></i> Edit Gaji Pokok </h4>
    </div>
    
    <?php $attributes = array('name' => 'e_salary_gapok_info', 'id' => 'e_salary_gapok_info', 'autocomplete' => 'off');?>
    <?php $hidden = array('u_basic_info' => 'UPDATE');?>
    <?php echo form_open('admin/payroll/update_gapok_info_harian', $attributes, $hidden);?>
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
      'value' => $salary_gapok_id,
    );
    echo form_input($edata_usr8);
    ?>
    <div class="modal-body">
      <div class="row">

        <div class="col-md-4"> 
          <div class="form-group">
            <label for="commission_date" class="control-label"><?php echo $this->lang->line('dashboard_xin_date');?><i class="hris-asterisk">*</i></label>
            <input class="form-control date" readonly placeholder="<?php echo $this->lang->line('dashboard_xin_date');?>" name="date" type="text" value="<?php echo $gapok_date;?>" >
          </div>
        </div>

        <div class="col-md-4">
          <div class="form-group">
            <label for="title"><?php echo $this->lang->line('dashboard_xin_title');?><i class="hris-asterisk">*</i></label>
            <input class="form-control" placeholder="<?php echo $this->lang->line('dashboard_xin_title');?>" name="title" type="text" value="<?php echo $gapok_title;?>">
          </div>
        </div>
        
        <div class="col-md-4">
          <div class="form-group">
            <label for="amount" class="control-label"><?php echo $this->lang->line('xin_amount');?><i class="hris-asterisk">*</i></label>
            <input class="form-control" placeholder="<?php echo $this->lang->line('xin_amount');?>" name="amount" type="number" value="<?php echo $gapok_amount;?>">
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
      $("#e_salary_gapok_info").submit(function(e){
      /*Form Submit*/
      e.preventDefault();
        var obj = $(this), action = obj.attr('name');
        $('.save').prop('disabled', true);
        $.ajax({
          type: "POST",
          url: e.target.action,
          data: obj.serialize()+"&is_ajax=29&data=e_salary_gapok_info&type=e_salary_gapok_info&form="+action,
          cache: false,
          success: function (JSON) {
            if (JSON.error != '') {
              alert_fail('Gagal',JSON.error);
              $('input[name="csrf_hris"]').val(JSON.csrf_hash);
              $('.save').prop('disabled', false);
            } else {
              $('.edit-modal-data').modal('toggle');
              // On page load:
              var xin_table_all_gapok = $('#xin_table_all_gapok_harian').dataTable({
                "bDestroy": true,
                "ajax": {
                  url : "<?php echo site_url("admin/payroll/salary_all_gapok_harian") ?>/"+$('#user_id').val(),
                  type : 'GET'
                },
                "fnDrawCallback": function(settings){
                $('[data-toggle="tooltip"]').tooltip();          
                }
              });
              xin_table_all_gapok.api().ajax.reload(function(){ 
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

<?php } else if(isset($_GET['jd']) && isset($_GET['field_id']) && $_GET['data']=='emp_overtime_info' && $_GET['type']=='emp_overtime_info'){ ?>
    
    <div class="modal-header"> <?php echo form_button(array('aria-label' => 'Close', 'data-dismiss' => 'modal', 'type' => 'button', 'class' => 'close', 'content' => '<span aria-hidden="true">×</span>')); ?>
      <h4 class="modal-title" id="edit-modal-data"><i class="fa fa-pencil"></i> <?php echo $this->lang->line('xin_employee_edit_allowance');?></h4>
    </div>
    <?php $attributes = array('name' => 'e_overtime_info', 'id' => 'e_overtime_info', 'autocomplete' => 'off');?>
    <?php $hidden = array('u_basic_info' => 'UPDATE');?>
    <?php echo form_open('admin/payroll/update_overtime_info', $attributes, $hidden);?>
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
      'value' => $salary_overtime_id,
    );
    echo form_input($edata_usr8);
    ?>
    <div class="modal-body">
      <div class="row">   
          <div class="col-md-3">
            <div class="form-group">
              <label for="overtime_type"><?php echo $this->lang->line('xin_employee_overtime_title');?><i class="hris-asterisk">*</i></label>
              <input class="form-control" placeholder="<?php echo $this->lang->line('xin_employee_overtime_title');?>" name="overtime_type" type="text" value="<?php echo $overtime_type;?>" id="overtime_type">
            </div>
          </div>
          <div class="col-md-3">  
            <div class="form-group">
              <label for="no_of_days"><?php echo $this->lang->line('xin_employee_overtime_no_of_days');?><i class="hris-asterisk">*</i></label>
              <input class="form-control" placeholder="<?php echo $this->lang->line('xin_employee_overtime_no_of_days');?>" name="no_of_days" type="text" value="<?php echo $no_of_days;?>" id="no_of_days">
            </div>
          </div>
          <div class="col-md-3">  
            <div class="form-group">
              <label for="overtime_hours"><?php echo $this->lang->line('xin_employee_overtime_hour');?><i class="hris-asterisk">*</i></label>
              <input class="form-control" placeholder="<?php echo $this->lang->line('xin_employee_overtime_hour');?>" name="overtime_hours" type="text" value="<?php echo $overtime_hours;?>" id="overtime_hours">
            </div>
          </div>
          <div class="col-md-3">  
            <div class="form-group">
              <label for="overtime_rate"><?php echo $this->lang->line('xin_employee_overtime_rate');?><i class="hris-asterisk">*</i></label>
              <input class="form-control" placeholder="<?php echo $this->lang->line('xin_employee_overtime_rate');?>" name="overtime_rate" type="text" value="<?php echo $overtime_rate;?>" id="overtime_rate">
            </div>
          </div>
      </div>
    </div>
    <div class="modal-footer"> <?php echo form_button(array('data-dismiss' => 'modal', 'type' => 'button', 'class' => 'btn btn-default', 'content' => '<i class="fa fa-check-square-o"></i> '.$this->lang->line('xin_close'))); ?> <?php echo form_button(array('name' => 'hris_form', 'type' => 'submit', 'class' => $this->Core_model->form_button_class(), 'content' => '<i class="fa fa-check-square-o"></i> '.$this->lang->line('xin_update'))); ?> </div>
    <?php echo form_close(); ?> 
    <script type="text/javascript">
    $(document).ready(function(){     
          
      /* Update location info */
      $("#e_overtime_info").submit(function(e){
      /*Form Submit*/
      e.preventDefault();
        var obj = $(this), action = obj.attr('name');
        $('.save').prop('disabled', true);
        $.ajax({
          type: "POST",
          url: e.target.action,
          data: obj.serialize()+"&is_ajax=29&data=e_overtime_info&type=e_overtime_info&form="+action,
          cache: false,
          success: function (JSON) {
            if (JSON.error != '') {
              alert_fail('Gagal',JSON.error);
              $('input[name="csrf_hris"]').val(JSON.csrf_hash);
              $('.save').prop('disabled', false);
            } else {
              $('.edit-modal-data').modal('toggle');
              // On page load:
              var xin_table_emp_overtime = $('#xin_table_emp_overtime').dataTable({
                "bDestroy": true,
                "ajax": {
                  url : "<?php echo site_url("admin/payroll/salary_overtime") ?>/"+$('#user_id').val(),
                  type : 'GET'
                },
                "fnDrawCallback": function(settings){
                $('[data-toggle="tooltip"]').tooltip();          
                }
              });
              xin_table_emp_overtime.api().ajax.reload(function(){ 
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

<?php } else if(isset($_GET['jd']) && isset($_GET['field_id']) && $_GET['data']=='salary_statutory_deductions_info_harian' && $_GET['type']=='salary_statutory_deductions_info_harian'){ ?>
    
    <div class="modal-header"> <?php echo form_button(array('aria-label' => 'Close', 'data-dismiss' => 'modal', 'type' => 'button', 'class' => 'close', 'content' => '<span aria-hidden="true">×</span>')); ?>
      <h4 class="modal-title" id="edit-modal-data"><i class="fa fa-pencil"></i> <?php echo $this->lang->line('xin_employee_edit_allowance');?></h4>
    </div>
    <?php $attributes = array('name' => 'e_salary_statutory_deductions_info_harian', 'id' => 'e_salary_statutory_deductions_info_harian', 'autocomplete' => 'off');?>
    <?php $hidden = array('u_basic_info' => 'UPDATE');?>
    <?php echo form_open('admin/payroll/update_statutory_deductions_info_harian', $attributes, $hidden);?>
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
      $("#e_salary_statutory_deductions_info_harian").submit(function(e){
          /*Form Submit*/
          e.preventDefault();
          var obj = $(this), action = obj.attr('name');
          $('.save').prop('disabled', true);
          $.ajax({
            type: "POST",
            url: e.target.action,
            data: obj.serialize()+"&is_ajax=29&data=e_salary_statutory_deductions_info_harian&type=e_salary_statutory_deductions_info_harian&form="+action,
            cache: false,
            success: function (JSON) {
              if (JSON.error != '') {
                alert_fail('Gagal',JSON.error);
                $('input[name="csrf_hris"]').val(JSON.csrf_hash);
                $('.save').prop('disabled', false);
              } else {
                $('.edit-modal-data').modal('toggle');
                // On page load:
                var xin_table_all_statutory_deductions = $('#xin_table_all_statutory_deductions_harian').dataTable({
                  "bDestroy": true,
                  "ajax": {
                    url : "<?php echo site_url("admin/payroll/salary_all_statutory_deductions_harian") ?>/"+$('#user_id').val(),
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
