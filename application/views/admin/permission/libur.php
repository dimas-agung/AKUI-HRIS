<?php
/* libur Application view
*/
?>
<?php $session            = $this->session->userdata('username');?>
<?php $user               = $this->Core_model->read_employee_info($session['user_id']);?>
<?php $get_animate        = $this->Core_model->get_content_animate();?>
<?php $xuser_info         = $this->Core_model->read_user_info($session['user_id']);?>
<?php $role_resources_ids = $this->Core_model->user_role_resource();?>
<?php

// reports to 
$reports_to = get_reports_team_data($session['user_id']); ?>

<?php if(in_array('0732',$role_resources_ids)){ ?>
    <div id="filter_hris" class="collapse add-formd <?php echo $get_animate;?>" data-parent="#accordion" style="">
        <div class="row">
          <div class="col-md-12">
            <div class="box mb-4">
            <div class="box-header  with-border">
              <h3 class="box-title"><?php echo $this->lang->line('xin_filter');?></h3>
                  <div class="box-tools pull-right"> <a class="text-dark collapsed" data-toggle="collapse" href="#filter_hris" aria-expanded="false">
                    <button type="button" class="btn btn-xs btn-primary"> <span class="fa fa-minus"></span> <?php echo $this->lang->line('xin_hide');?></button>
                    </a> </div>
                </div>
              <div class="box-body">
                <div class="row">
                  <div class="col-md-12">
                    <?php $attributes = array('name' => 'ihr_report', 'id' => 'ihr_report', 'class' => 'm-b-1 add form-hrm');?>
                    <?php $hidden = array('user_id' => $session['user_id']);?>
                    <?php echo form_open('admin/permission/libur_list', $attributes, $hidden);?>
                    <div class="row">
                      <div class="col-md-3">
                        <div class="form-group">
                          <label for="department"><?php echo $this->lang->line('module_company_title');?></label>
                          <select class="form-control" name="company" id="aj_companyf" data-plugin="select_hrm" data-placeholder="<?php echo $this->lang->line('module_company_title');?>" required>
                            <option value="0"><?php echo $this->lang->line('xin_all_companies');?></option>
                            <?php foreach($get_all_companies as $company) {?>
                            <option value="<?php echo $company->company_id;?>"> <?php echo $company->name;?></option>
                            <?php } ?>
                          </select>
                        </div>
                      </div>
                      <div class="col-md-3">
                        <div class="form-group" id="employee_ajaxf">
                          <label for="department"><?php echo $this->lang->line('dashboard_single_employee');?></label>
                          <select id="employee_id" name="employee_id" class="form-control" data-plugin="select_hrm" data-placeholder="<?php echo $this->lang->line('xin_choose_an_employee');?>">
                            <option value="0"><?php echo $this->lang->line('xin_all_employees');?></option>
                          </select>
                        </div>
                      </div>
                      <div class="col-md-3">
                        <div class="form-group">
                            <label for="status"><?php echo $this->lang->line('dashboard_xin_status');?></label>
                            <select class="form-control" name="status" id="status" data-plugin="select_hrm" data-placeholder="<?php echo $this->lang->line('dashboard_xin_status');?>">
                              <option value="0" ><?php echo $this->lang->line('xin_acc_all');?></option>
                              <option value="1" ><?php echo $this->lang->line('xin_pending');?></option>
                              <option value="2" ><?php echo $this->lang->line('xin_approved');?></option>
                              <option value="3" ><?php echo $this->lang->line('xin_rejected');?></option>
                            </select>
                          </div>
                      </div>
                      <div class="col-md-1"><label for="xin_get">&nbsp;</label><button name="hris_form" type="submit" class="btn btn-primary"><i class="fa fa fa-check-square-o"></i> <?php echo $this->lang->line('xin_get');?></button>
                    </div>
                    </div>
                    
                    <?php echo form_close(); ?> </div>
                </div>
              </div>
            </div>
          </div>
        </div>
    </div>
<?php } ?>

<?php if(in_array('0732',$role_resources_ids)) {?>

    <?php $user_info = $this->Core_model->read_user_info($session['user_id']);?>
    <?php $libur_cat  = get_employee_libur_category();?>
    <div class="box mb-4 <?php echo $get_animate;?>">
      <div id="accordion">
        <div class="box-header with-border">
          <h3 class="box-title"><i class="fa fa-plus-circle"></i> <?php echo $this->lang->line('xin_add_libur');?></h3>
          <div class="box-tools pull-right"> <a class="text-dark collapsed" data-toggle="collapse" href="#add_form" aria-expanded="false">
            <button type="button" class="btn btn-xs btn-primary"> <span class="fa fa-plus-circle"></span> <?php echo $this->lang->line('xin_add_new');?></button>
            </a> </div>
        </div>
        <div id="add_form" class="collapse add-form <?php echo $get_animate;?>" data-parent="#accordion" style="">
          <div class="box-body">
            <?php $attributes = array('name' => 'add_libur', 'id' => 'xin-form', 'autocomplete' => 'off');?>
            <?php $hidden = array('_user' => $session['user_id']);?>
            <?php echo form_open('admin/permission/add_libur', $attributes, $hidden);?>
            <?php $libur_cat = get_employee_libur_category();?>
            <div class="bg-white">
              <div class="box-block">
                <div class="row">
                  <div class="col-md-6">
                  
                    <div class="row">
                      <div class="col-md-6">
                        
                        <div class="form-group">
                          <label for="first_name"><?php echo $this->lang->line('left_company');?></label>
                          <select class="form-control" name="company_id" id="aj_company" data-plugin="select_hrm" data-placeholder="<?php echo $this->lang->line('left_company');?>">
                            <option value=""></option>
                            <?php foreach($get_all_companies as $company) {?>
                            <option value="<?php echo $company->company_id?>"><?php echo $company->name?></option>
                            <?php } ?>
                          </select>
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="form-group" id="employee_ajax">
                          <label for="employees" class="control-label"><?php echo $this->lang->line('xin_employee');?></label>
                          <select disabled="disabled" class="form-control" name="employee_id" id="employee_id" data-plugin="select_hrm" data-placeholder="<?php echo $this->lang->line('xin_choose_an_employee');?>">
                            <option value=""></option>
                          </select>
                        </div>
                      </div>
                    </div>
                   
                    <div class="form-group" >
                      <label for="libur_type" class="control-label"><?php echo $this->lang->line('xin_libur_type');?></label>                      
                      <select class="form-control" name="libur_type" id="libur_type" data-plugin="select_hrm" data-placeholder="<?php echo $this->lang->line('xin_libur_type');?>">
                        <option value=""></option>
                        <?php foreach($libur_cat as $type) {?>
                        <option value="<?php echo $type->libur_type_id?>"><?php echo $type->type_name?></option>
                        <?php } ?>
                      </select>                 
                    </div>

                    <div class="row">
                      <div class="col-md-6">
                        <div class="form-group">
                          <label for="start_date"><?php echo $this->lang->line('xin_start_date');?></label>
                          <input class="form-control date" placeholder="<?php echo $this->lang->line('xin_start_date');?>" readonly name="start_date" type="text" value="">
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="form-group">
                          <label for="end_date"><?php echo $this->lang->line('xin_end_date');?></label>
                          <input class="form-control date" placeholder="<?php echo $this->lang->line('xin_end_date');?>" readonly name="end_date" type="text" value="">
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="description"><?php echo $this->lang->line('xin_remarks');?></label>
                      <textarea class="form-control textarea" placeholder="<?php echo $this->lang->line('xin_remarks');?>" name="remarks" rows="5"></textarea>
                    </div>
                    <div class="form-group" hidden="true">
                      <label>
                      <input type="checkbox" class="minimal" value="1" id="libur_half_day" name="libur_half_day">
    				             <?php echo $this->lang->line('xin_hr_libur_half_day');?></span> 
                      </label>
                    </div>
                  </div>
                </div>
                <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                      <fieldset class="form-group">
                        <label for="attachment"><?php echo $this->lang->line('xin_attachment');?></label>
                        <input type="file" class="form-control-file" id="attachment" name="attachment">
                        <small><?php echo $this->lang->line('xin_libur_file_type');?></small>
                      </fieldset>
                    </div>
                  </div>
                </div>
                <div class="form-group">
                  <label for="summary"><?php echo $this->lang->line('xin_libur_reason');?></label>
                  <textarea class="form-control" placeholder="<?php echo $this->lang->line('xin_libur_reason');?>" name="reason" cols="30" rows="3" id="reason"></textarea>
                </div>
                <div class="form-actions box-footer">
                  <button type="submit" class="btn btn-primary"> <i class="fa fa-check-square-o"></i> <?php echo $this->lang->line('xin_save');?> </button>
                </div>
              </div>
            </div>
            <?php echo form_close(); ?> </div>
        </div>
      </div>
    </div>
<?php } ?>


<div class="box <?php echo $get_animate;?>">
  <div class="box-header with-border">
    <h3 class="box-title"><?php echo $this->lang->line('xin_list_all');?> <?php echo $this->lang->line('left_libur');?></h3>
    <?php if($xuser_info[0]->user_role_id==1){ ?><div class="box-tools pull-right"> <a class="text-dark collapsed" data-toggle="collapse" href="#filter_hris" aria-expanded="false">
        <button type="button" class="btn btn-xs btn-primary"> <span class="fa fa-filter"></span> <?php echo $this->lang->line('xin_filter');?></button>
       </a> </div><?php } ?>
  </div>
  <div class="box-body">
    <div class="box-datatable table-responsive">
      <table class="datatables-demo table table-striped table-bordered" id="xin_table">
        <thead>
            <tr>
                <th width="160px"><center><?php echo $this->lang->line('xin_action');?></center></th>
                <th width="10px"><center>No.</center></th>
                <th width="80px"><center><?php echo $this->lang->line('dashboard_xin_status');?></center></th>
                <th width="100px"><center>Tanggal<br>Pengajuan</center></th>
                <th width="200px"><center><i class="fa fa-calendar"></i> <?php echo $this->lang->line('xin_libur_duration');?></center></th>
                <th width="550px"><center><?php echo $this->lang->line('xin_employee');?></center></th>
                <th ><center><?php echo $this->lang->line('xin_libur_desc');?></center></th>                               
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