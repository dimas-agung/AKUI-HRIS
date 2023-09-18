<?php
defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<?php if(isset($_GET['jd']) && isset($_GET['field_id']) && $_GET['data']=='emp_contract' && $_GET['type']=='emp_contract'){ ?>

    <div class="modal-header"> <?php echo form_button(array('aria-label' => 'Close', 'data-dismiss' => 'modal', 'type' => 'button', 'class' => 'close', 'content' => '<span aria-hidden="true">×</span>')); ?>
      <h4 class="modal-title" id="edit-modal-data"><i class="fa fa-gavel"></i> Edit & Aktifkan </h4>
    </div>

    <?php $attributes = array('name' => 'e_contract_info', 'id' => 'e_contract_info', 'autocomplete' => 'off');?>
    <?php $hidden = array('u_basic_info' => 'UPDATE');?>
    <?php echo form_open('admin/legal/e_contract_info', $attributes, $hidden);?>
    
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
    	'value' => $contract_id,
    );
    echo form_input($edata_usr8);
    ?>
    
    <div class="modal-body">
      <div class="row">
        <div class="col-md-6">
    	
    		 <div class="form-group">
            <label for="company_id" class=""><?php echo $this->lang->line('left_company');?></label>
            <select class="form-control" name="company_id" data-plugin="select_hrm" data-placeholder="<?php echo $this->lang->line('xin_select_one');?>">
              <option value=""><?php echo $this->lang->line('xin_select_one');?></option>
              <?php foreach($all_companies as $company) {?>
              <option value="<?php echo $company->company_id;?>" <?php if($company->company_id==$company_id) {?> selected="selected" <?php } ?>> <?php echo $company->name;?></option>
              <?php } ?>
            </select>
          </div>

          <div class="form-group">
            <label for="contract_type_id" class=""><?php echo $this->lang->line('xin_e_details_contract_type');?></label>
            <select class="form-control" name="contract_type_id" data-plugin="select_hrm" data-placeholder="<?php echo $this->lang->line('xin_e_details_contract_type');?>">
              <option value=""><?php echo $this->lang->line('xin_select_one');?></option>
              <?php foreach($all_contract_types as $contract_type) {?>
              <option value="<?php echo $contract_type->contract_type_id;?>" <?php if($contract_type->contract_type_id==$contract_type_id) {?> selected="selected" <?php } ?>> <?php echo $contract_type->name;?></option>
              <?php } ?>
            </select>
          </div>
    	  
    			<div class="form-group">
            <label class="" for="from_date"><?php echo $this->lang->line('xin_e_details_frm_date');?></label>
            <input type="text" class="form-control e_cont_date" name="from_date" placeholder="<?php echo $this->lang->line('xin_e_details_frm_date');?>" readonly value="<?php echo $from_date;?>">
          </div>

          <div class="form-group">
            <label for="contract_durasi_id" class=""><?php echo $this->lang->line('xin_e_details_contract_durasi');?></label>
            <select class="form-control" name="contract_durasi_id" data-plugin="select_hrm" data-placeholder="<?php echo $this->lang->line('xin_e_details_contract_durasi');?>">
              <option value=""><?php echo $this->lang->line('xin_e_details_contract_durasi');?></option>
              <?php foreach($all_contract_durasi as $contract_durasi) {?>
              <option value="<?php echo $contract_durasi->contract_durasi_id;?>" <?php if($contract_durasi->contract_durasi_id==$contract_durasi_id) {?> selected="selected" <?php } ?>> <?php echo $contract_durasi->name;?></option>
              <?php } ?>
            </select>
          </div>          
          
        </div>
        <div class="col-md-6">
          <div class="form-group">
            <label for="title" class=""><?php echo $this->lang->line('xin_e_details_contract_title');?></label>
            <input class="form-control" placeholder="<?php echo $this->lang->line('xin_e_details_contract_title');?>" name="title" type="text" value="<?php echo $title;?>" id="title">
          </div>
          <div class="form-group">
            <label for="designation_id" class=""><?php echo $this->lang->line('dashboard_designation');?></label>
            <select class="form-control" name="designation_id" data-plugin="select_hrm" data-placeholder="<?php echo $this->lang->line('xin_select_one');?>">
              <option value=""><?php echo $this->lang->line('xin_select_one');?></option>
              <?php foreach($all_designations as $designation) {?>
              <?php if($designation_id==$designation->designation_id):?>
              <option value="<?php echo $designation->designation_id?>" <?php if($designation_id==$designation->designation_id):?> selected <?php endif;?>><?php echo $designation->designation_name?></option>
              <?php endif;?>
              <?php } ?>
            </select>
          </div>
          <div class="form-group">
            <label for="to_date"><?php echo $this->lang->line('xin_e_details_to_date');?></label>
            <input type="text" class="form-control e_cont_date" name="to_date" placeholder="<?php echo $this->lang->line('xin_e_details_to_date');?>" readonly value="<?php echo $to_date;?>">
          </div>
          <div class="form-group">
            <label for="description"><?php echo $this->lang->line('xin_description');?></label>
            <textarea class="form-control" placeholder="<?php echo $this->lang->line('xin_description');?>" data-show-counter="1" data-limit="300" name="description" cols="30" rows="3" id="description"><?php echo $description;?></textarea>
            <span class="countdown"></span> </div>
        </div>
      </div>
    </div>

    <div class="modal-footer"> 
      <?php echo form_button(array('data-dismiss' => 'modal', 'type' => 'button', 'class' => 'btn btn-default', 'content' => '<i class="fa fa-check-square-o"></i> '.$this->lang->line('xin_close'))); ?> 
      <?php echo form_button(array('name' => 'hris_form', 'type' => 'submit', 'class' => $this->Core_model->form_button_class(), 'content' => '<i class="fa fa-save"></i> Edit & Aktifkan')); ?> 
    </div>

    <?php echo form_close(); ?> 
    <script type="text/javascript">
    $(document).ready(function(){			
    	
    	$('[data-plugin="select_hrm"]').select2($(this).attr('data-options'));
    	$('[data-plugin="select_hrm"]').select2({ width:'100%' });	
    	// Date
    	$('.e_cont_date').datepicker({
    	  changeMonth: true,
    	  changeYear: true,
    	  dateFormat:'yy-mm-dd',
    	  yearRange: '1950:' + new Date().getFullYear()
    	});
    			
    	/* Update bank acount info */
    	$("#e_contract_info").submit(function(e){
    	/*Form Submit*/
    	e.preventDefault();
    		var obj = $(this), action = obj.attr('name');
    		$('.save').prop('disabled', true);
    		$.ajax({
    			type: "POST",
    			url: e.target.action,
    			data: obj.serialize()+"&is_ajax=20&data=e_contract_info&type=e_contract_info&form="+action,
    			cache: false,
    			success: function (JSON) {
    				if (JSON.error != '') {
    					alert_fail('Gagal',JSON.error);
    					$('input[name="csrf_hris"]').val(JSON.csrf_hash);
    					$('.save').prop('disabled', false);
    				} else {
    					$('.edit-modal-data').modal('toggle');
    					// On page load:

              var xin_table_contract = $('#xin_table_contract').dataTable({
                  "bDestroy": true,
                  "ajax": {
                      url : site_url+"legal/contract_list/"+$('#user_id').val(),
                      type : 'GET'
                  },
                  "columns": [
                  {"name": "kolom_1", "className": "text-center"},
                  {"name": "kolom_2", "className": "text-center"},
                  {"name": "kolom_3", "className": "text-center"},
                  {"name": "kolom_4", "className": "text-center"},
                  {"name": "kolom_5", "className": "text-center"},
                  {"name": "kolom_6", "className": "text-center"},
                  {"name": "kolom_7", "className": "text-left"},                  
                  {"name": "kolom_9", "className": "text-center"},
                  {"name": "kolom_10", "className": "text-center"},
                  ],
                
              "fnDrawCallback": function(settings){
              $('[data-toggle="tooltip"]').tooltip();          
              }
              });
    					

    					xin_table_contract.api().ajax.reload(function(){ 
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
