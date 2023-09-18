<?php
/* Payment History view
*/
?>
<?php $session = $this->session->userdata('username');?>
<?php $get_animate = $this->Core_model->get_content_animate();?>
<?php $user_info = $this->Core_model->read_user_info($session['user_id']);?>
<?php if($user_info[0]->user_role_id==1){ ?>
<div id="filter_hris" class="collapse add-formd <?php echo $get_animate;?>" data-parent="#accordion" style="">
<div class="box mb-4 <?php echo $get_animate;?>">
<div class="box-header  with-border">
  <h3 class="box-title"><?php echo $this->lang->line('xin_filter');?></h3>
      <div class="box-tools pull-right"> <a class="text-dark collapsed" data-toggle="collapse" href="#filter_hris" aria-expanded="false">
        <button type="button" class="btn btn-xs btn-primary"> <span class="fa fa-minus"></span> <?php echo $this->lang->line('xin_hide');?></button>
        </a> </div>
    </div>
  <div class="box-body">
    <div class="row">
      <div class="col-md-12">
        <?php $attributes = array('name' => 'payroll_report', 'id' => 'ihr_report', 'autocomplete' => 'off', 'class' => 'add form-hrm');?>
        <?php $hidden = array('user_id' => $session['user_id']);?>
        <?php echo form_open('admin/thr/payment_history_list', $attributes, $hidden);?>
        <?php
			$data = array(
			  'type'        => 'hidden',
			  'name'        => 'date_format',
			  'id'          => 'date_format',
			  'value'       => $this->Core_model->set_date_format(date('Y-m-d')),
			  'class'       => 'form-control',
			);
			echo form_input($data);
			?>
        <div class="row">
            <div class="col-md-3">
              <div class="form-group">
                <label for="first_name"><?php echo $this->lang->line('left_company');?></label>
                <select class="form-control" name="company_id" id="aj_company" data-plugin="select_hrm" data-placeholder="<?php echo $this->lang->line('left_company');?>">
                  <option value="0"><?php echo $this->lang->line('xin_acc_all');?></option>
                  <?php foreach($get_all_companies as $company) {?>
                  <option value="<?php echo $company->company_id?>"><?php echo $company->name?></option>
                  <?php } ?>
                </select>
              </div>
            </div>
            <div class="col-md-3" id="location_ajax">
            <div class="form-group">
              <label for="name"><?php echo $this->lang->line('left_location');?></label>
              <select disabled="disabled" name="location_id" id="aj_location_id" class="form-control" data-plugin="select_hrm" data-placeholder="<?php echo $this->lang->line('left_location');?>">
                <option value="0"><?php echo $this->lang->line('xin_acc_all');?></option>
              </select>
            </div>
            </div>
            <div class="col-md-3">
              <div class="form-group" id="department_ajax">
                <label for="department"><?php echo $this->lang->line('left_department');?></label>
                <select class="form-control" id="aj_subdepartments" name="department_id" data-plugin="select_hrm" data-placeholder="<?php echo $this->lang->line('left_department');?>" disabled="disabled">
                  <option value="0"><?php echo $this->lang->line('xin_acc_all');?></option>
                </select>
              </div>
            </div>
            <!--<div class="col-md-3" id="designation_ajax">-->
            <div class="col-md-3">
              <div class="form-group">
                <label for="designation"><?php echo $this->lang->line('xin_salary_month');?></label>
                <input type="text" class="form-control month_year" name="salary_month" id="salary_month" placeholder="<?php echo $this->lang->line('xin_salary_month');?>" />
              </div>
            </div>
        </div>
        <div class="form-actions box-footer"> <?php echo form_button(array('name' => 'hris_form', 'type' => 'submit', 'class' => $this->Core_model->form_button_class(), 'content' => '<i class="fa fa fa-check-square-o"></i> '.$this->lang->line('xin_get'))); ?> </div>
        <?php echo form_close(); ?> </div>
    </div>
  </div>
</div>
</div>
<?php } ?>
<div class="box <?php echo $get_animate;?>">
  <div class="box-header with-border">
    <h3 class="box-title"> <?php echo $this->lang->line('xin_list_all');?> <?php echo $this->lang->line('left_payment_history');?> </h3>
    <?php if($user_info[0]->user_role_id==1){ ?><div class="box-tools pull-right"> <a class="text-dark collapsed" data-toggle="collapse" href="#filter_hris" aria-expanded="false">
        <button type="button" class="btn btn-xs btn-primary"> <span class="fa fa-filter"></span> <?php echo $this->lang->line('xin_filter');?></button>
       </a> </div><?php } ?>
  </div>
  <div class="box-body">
    <div class="box-datatable table-responsive">
      <table class="datatables-demo table table-striped table-bordered" id="xin_table" width="100%">
        <thead>
          <tr>
            <th><?php echo $this->lang->line('xin_action');?></th>
            <th style="text-align: center !important;"><center><?php echo $this->lang->line('xin_salary_month');?></center></th>
            <th style="text-align: center !important;"><center><i class="fa fa-user"></i> <?php echo $this->lang->line('xin_employee_name');?></center></th>
            <th style="text-align: center !important;"><center><i class="fa fa-building"></i> <?php echo $this->lang->line('xin_employee_designation_txt');?></center></th>
            <th style="text-align: center !important;"><center><?php echo $this->lang->line('xin_payroll_total_salary');?></center></th>
            <th style="text-align: center !important;"><center><?php echo $this->lang->line('xin_acc_account_rekening');?>#</center></th>
          </tr>
        </thead>
      </table>
    </div>
  </div>
</div>
<style type="text/css">
.box-tools {
    margin-right: -5px !important;
}
.col-md-8 {
  padding-left:0px !important;
  padding-right: 0px !important;
}
.dataTables_length {
  float:left;
}
.dt-buttons {
    position: relative;
    float: right;
    margin-left: 10px;
}
.hide-calendar .ui-datepicker-calendar { display:none !important; }
.hide-calendar .ui-priority-secondary { display:none !important; }
</style>
