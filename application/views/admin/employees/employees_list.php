<?php
/* Employees view
*/
?>

<?php $session = $this->session->userdata('username');?>
<?php $get_animate = $this->Core_model->get_content_animate();?>
<?php $role_resources_ids = $this->Core_model->user_role_resource(); ?>
<?php $user_info = $this->Core_model->read_user_info($session['user_id']);?>
<?php $system = $this->Core_model->read_setting_info(1);?>

<?php

// reports to 
$reports_to = get_reports_team_data($session['user_id']); ?>

<div class="row <?php echo $get_animate;?>">
    <div class="col-sm-6 col-lg-6">
        <div class="card p-3">
            <div class="d-flex align-items-center">
                <span class="stamp-hris-4 stamp-hris-md bg-hris-success-4 mr-3">
                    <i class="fa fa-user"></i>
                </span>
                <div>
                    <h5 class="mb-1"><b><?php echo active_employees();?> <small><?php echo $this->lang->line('xin_employees_active');?></small></b></h5>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-sm-6 col-lg-3">
        <div class="card p-3">
            <div class="d-flex align-items-center">
                <span class="stamp-hris-4 stamp-hris-md bg-hris-secondary mr-3">
                    <i class="fa fa-male"></i>
                </span>
                <div>
                    <h5 class="mb-1"><b><?php echo $this->Core_model->male_employees();?>% <small><?php echo $this->lang->line('xin_gender_male');?></small></b></h5>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-lg-3">
        <div class="card p-3">
            <div class="d-flex align-items-center">
                <span class="stamp-hris-4 stamp-hris-md bg-hris-warning-4 mr-3">
                    <i class="fa fa-female"></i>
                </span>
                <div>
                    <h5 class="mb-1"><b><?php echo $this->Core_model->female_employees();?>% <small><?php echo $this->lang->line('xin_gender_female');?></small></b></h5>
                </div>
            </div>
        </div>
    </div>
</div>

<?php if(in_array('201',$role_resources_ids)) {?>

    <div class="box mb-4 <?php echo $get_animate;?>">
      <div id="accordion">
        
        <div class="box-header  with-border">
          <h3 class="box-title"><i class="fa fa-plus-circle"></i> <?php echo $this->lang->line('xin_add_new');?> </h3>
          <div class="box-tools pull-right"> 
            <a class="text-dark collapsed" data-toggle="collapse" href="#add_form" aria-expanded="false">
            <button type="button" class="btn btn-xs btn-primary"> 
              <span class="fa fa-plus-circle"></span> <?php echo $this->lang->line('xin_add_new');?>
            </button>
            </a> 
          </div>
        </div>

        <div id="add_form" class="collapse add-form" data-parent="#accordion" style="">
          <div class="box-body">
            
            <?php $attributes = array('name' => 'add_employee', 'id' => 'xin-form', 'autocomplete' => 'off');?>
            <?php $hidden = array('_user' => $session['user_id']);?>
            <?php echo form_open_multipart('admin/employees/add_employee', $attributes, $hidden);?>
            
            <div class="form-body">
              <div class="row">               
              
                  <div class="col-md-6">
                  
                  <div class="row">
                    <div class="col-md-6">
                      <div class="form-group">
                        <label for="first_name"><?php echo $this->lang->line('xin_employee_first_name');?><i class="hris-asterisk">*</i></label>
                        <input class="form-control" placeholder="<?php echo $this->lang->line('xin_employee_first_name');?>" name="first_name" type="text" value="">
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-group">
                        <label for="last_name" class="control-label"><?php echo $this->lang->line('xin_employee_last_name');?><i class="hris-asterisk">*</i></label>
                        <input class="form-control" placeholder="<?php echo $this->lang->line('xin_employee_last_name');?>" name="last_name" type="text" value="">
                      </div>
                    </div>
                  </div>
                  
                  <div class="row">
                    
                    <div class="col-md-12">
                      <div class="form-group">
                        <label for="email" class="control-label"><?php echo $this->lang->line('dashboard_email');?><i class="hris-asterisk">*</i></label>
                        <input class="form-control" placeholder="<?php echo $this->lang->line('dashboard_email');?>" name="email" type="text" value="">
                      </div>
                    </div>
                  </div>

                  <div class="row">
                    <div class="col-md-6">
                      <div class="form-group">
                        <label for="date_of_birth"><?php echo $this->lang->line('xin_employee_dob');?><i class="hris-asterisk">*</i></label>
                        <input class="form-control date_of_birth" readonly placeholder="<?php echo $this->lang->line('xin_employee_dob');?>" name="date_of_birth" type="text" value="">
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-group">
                        <label for="contact_no" class="control-label"><?php echo $this->lang->line('xin_contact_number');?><i class="hris-asterisk">*</i></label>
                        <input class="form-control" placeholder="<?php echo $this->lang->line('xin_contact_number');?>" name="contact_no" type="text" value="">
                      </div>
                    </div>
                  </div>

                  <div class="row">
                    <div class="col-md-6">
                      <div class="form-group">
                        <label for="gender" class="control-label"><?php echo $this->lang->line('xin_employee_gender');?></label>
                        <select class="form-control" name="gender" data-plugin="select_hrm" data-placeholder="<?php echo $this->lang->line('xin_employee_gender');?>">
                          <option value="Male"><?php echo $this->lang->line('xin_gender_male');?></option>
                          <option value="Female"><?php echo $this->lang->line('xin_gender_female');?></option>
                        </select>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-group">
                        <label for="role"><?php echo $this->lang->line('xin_employee_role');?><i class="hris-asterisk">*</i></label>
                        <select class="form-control" name="role" data-plugin="select_hrm" data-placeholder="<?php echo $this->lang->line('xin_employee_role');?>">
                          <option value=""></option>
                          <?php foreach($all_user_roles as $role) {?>
                          <?php if($user_info[0]->user_role_id==1){?>
                          <option value="<?php echo $role->role_id?>"><?php echo $role->role_name?></option>
                          <?php } else {?>
                            <?php if($role->role_id!=1){?>
                            <option value="<?php echo $role->role_id?>"><?php echo $role->role_name?></option>
                            <?php } ?>
                          <?php } ?>
                          <?php } ?>
                        </select>
                      </div>
                    </div>

                  </div>

                  <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                          <label for="address"><?php echo $this->lang->line('xin_employee_address');?></label>
                          <input type="text" class="form-control" placeholder="<?php echo $this->lang->line('xin_employee_address');?>" name="address">
                        </div>
                      </div>
                  </div>

                    <div class="row">                
                      <div class="col-md-12">
                        <div class="form-group">
                          <label for="xin_hr_leave_cat"><?php echo $this->lang->line('xin_hr_leave_cat');?></label>
                          <input type="hidden" name="leave_categories[]" value="0" />
                          <select multiple="multiple" class="form-control" name="leave_categories[]" data-plugin="select_hrm" data-placeholder="<?php echo $this->lang->line('xin_hr_leave_cat');?>">
                            <?php foreach($all_leave_types as $leave_type) {?>
                            <option value="<?php echo $leave_type->leave_type_id?>"><?php echo $leave_type->type_name?></option>
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
                        <label for="employee_pin"><?php echo $this->lang->line('dashboard_employee_pin');?><i class="hris-asterisk">*</i></label>
                        <input class="form-control" placeholder="<?php echo $this->lang->line('dashboard_employee_pin');?>" name="employee_pin" type="text" value="">
                      </div>
                    </div> 

                    <div class="col-md-6">
                      <div class="form-group">
                        <label for="employee_id"><?php echo $this->lang->line('dashboard_employee_id');?><i class="hris-asterisk">*</i></label>
                        <input class="form-control" placeholder="<?php echo $this->lang->line('dashboard_employee_id');?>" name="employee_id" type="text" value="">
                      </div>
                    </div>             

                  </div>

                  <div class="row">
                    
                    <!-- <?php if($user_info[0]->user_role_id==1){ ?> -->

                        <div class="col-md-6">
                          <div class="form-group">
                            <label for="first_name"><?php echo $this->lang->line('left_company');?><i class="hris-asterisk">*</i></label>
                            <select class="form-control" name="company_id" id="aj_company" data-plugin="select_hrm" data-placeholder="<?php echo $this->lang->line('left_company');?>">
                              <option value=""></option>
                              <?php foreach($get_all_companies as $company) {?>
                              <option value="<?php echo $company->company_id?>"><?php echo $company->name?></option>
                              <?php } ?>
                            </select>
                          </div>
                        </div>
                    
                    <!-- <?php } else {?>
                    
                        <?php $ecompany_id = $user_info[0]->company_id;?>
                        <div class="col-md-6">
                          <div class="form-group">
                            <label for="first_name"><?php echo $this->lang->line('left_company');?><i class="hris-asterisk">*</i></label>
                            <select class="form-control" name="company_id" id="aj_company" data-plugin="select_hrm" data-placeholder="<?php echo $this->lang->line('left_company');?>">
                              <option value=""></option>
                              <?php foreach($get_all_companies as $company) {?>
                                  <?php if($ecompany_id == $company->company_id):?>
                                      <option value="<?php echo $company->company_id?>"><?php echo $company->name?></option>
                                  <?php endif;?>
                              <?php } ?>
                            </select>
                          </div>
                        </div>
                    
                    <?php } ?> -->

                    <div class="col-md-6" id="location_ajax">
                    <div class="form-group">
                      <label for="name"><?php echo $this->lang->line('left_location');?><i class="hris-asterisk">*</i></label>
                      <select disabled="disabled" name="location_id" id="location_id" class="form-control" data-plugin="select_hrm" data-placeholder="<?php echo $this->lang->line('left_location');?>">
                        <option value=""></option>
                      </select>
                    </div>
                    </div>
                  
                  </div>
                  
                  <div class="row">
                    <div class="col-md-6">
                      <div class="form-group" id="department_ajax">
                        <label for="department"><?php echo $this->lang->line('xin_hr_main_department');?><i class="hris-asterisk">*</i></label>
                        <select class="form-control" name="department_id" data-plugin="select_hrm" data-placeholder="<?php echo $this->lang->line('xin_employee_department');?>" disabled="disabled">
                          <option value=""></option>
                        </select>
                        <input type="hidden" name="subdepartment_id" value="YES" />
                      </div>
                    </div>
                    <div class="col-md-6" id="designation_ajax">
                      <div class="form-group">
                        <label for="designation"><?php echo $this->lang->line('xin_designation');?><i class="hris-asterisk">*</i></label>
                        <select class="form-control" name="designation_id" data-plugin="select_hrm" disabled="disabled" data-placeholder="<?php echo $this->lang->line('xin_designation');?>">
                          <option value=""></option>
                        </select>
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-6">
                      <div class="form-group" id="ajax_office_shift">
                        <label for="office_shift_id" class="control-label"><?php echo $this->lang->line('xin_employee_office_shift');?><i class="hris-asterisk">*</i></label>
                        <select class="form-control" name="office_shift_id" data-plugin="select_hrm" data-placeholder="<?php echo $this->lang->line('xin_employee_office_shift');?>">
                          <option value=""></option>
                        </select>
                      </div>
                    </div>
                     <div class="col-md-6">
                        <div class="form-group">
                          <label for="emp_status" class="control-label"><?php echo $this->lang->line('xin_employee_status_txt');?><i class="hris-asterisk">*</i></label>
                          <select class="form-control" name="emp_status" data-plugin="select_hrm" data-placeholder="<?php echo $this->lang->line('xin_employee_status_txt');?>">
                            <option value=""><?php echo $this->lang->line('xin_employee_status_txt');?></option>
                            <option value="Tetap" ><?php echo $this->lang->line('xin_employee_status_tetap');?></option>
                            <option value="Kontrak" ><?php echo $this->lang->line('xin_employee_status_kontrak');?></option>
                            <option value="Percobaan"><?php echo $this->lang->line('xin_employee_status_percobaan');?></option>                                      
                          </select>
                        </div>
                      </div>
                  </div>

                  <div class="row">
                      
                    <div class="col-md-6">
                      <div class="form-group">
                        <label for="date_of_joining" class="control-label"><?php echo $this->lang->line('xin_employee_doj');?><i class="hris-asterisk">*</i></label>
                        <input class="form-control date_of_joining" readonly placeholder="<?php echo $this->lang->line('xin_employee_doj');?>" name="date_of_joining" type="text" value="">
                      </div>
                    </div>

                      <?php if(!in_array('0312',$role_resources_ids)) { ?>
                          <div class="col-md-6">
                              <div class="form-group">
                                <label for="reports_to"><?php echo $this->lang->line('xin_reports_tos');?></label>
                                <select name="reports_to" class="form-control" data-plugin="select_hrm" data-placeholder="<?php echo $this->lang->line('xin_reports_tos');?>">
                                  <option value=""></option>
                                  <?php foreach(get_reports_to() as $reports_to) {?>
                                  <?php if($reports_to->user_id == $session['user_id']):?>
                                      <option value="<?php echo $reports_to->user_id?>" <?php if($reports_to->user_id == $session['user_id']):?> selected="selected"<?php endif;?>><?php echo $reports_to->first_name.' '.$reports_to->last_name;?></option>
                                      <?php endif;?>
                                  <?php } ?>
                                </select>
                              </div>
                           </div>
                      
                      <?php } else {?>
                      
                          <div class="col-md-6">
                              <div class="form-group">
                                <label for="reports_to"><?php echo $this->lang->line('xin_reports_tos');?></label>
                                <select name="reports_to" class="form-control" data-plugin="select_hrm" data-placeholder="<?php echo $this->lang->line('xin_reports_tos');?>">
                                  <option value=""></option>
                                  <?php foreach(get_reports_to() as $reports_to) {?>
                                  <option value="<?php echo $reports_to->user_id?>"><?php echo $reports_to->first_name.' '.$reports_to->last_name;?></option>
                                  <?php } ?>
                                </select>
                              </div>
                           </div>
                      
                      <?php } ?>                      
                    </div>

                  <div class="row">
                    <div class="col-md-6">
                      <div class="form-group">
                        <label for="xin_employee_password"><?php echo $this->lang->line('xin_employee_password');?><i class="hris-asterisk">*</i></label>
                        <input class="form-control" placeholder="<?php echo $this->lang->line('xin_employee_password');?>" name="password" type="text" value="">
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-group">
                        <label for="confirm_password" class="control-label"><?php echo $this->lang->line('xin_employee_cpassword');?><i class="hris-asterisk">*</i></label>
                        <input class="form-control" placeholder="<?php echo $this->lang->line('xin_employee_cpassword');?>" name="confirm_password" type="text" value="">
                      </div>
                    </div>
                  </div>

                </div>
              </div>
            </div>
           
              
            <div class="form-actions box-footer"> 
              <button type="reset" class="btn btn-default" onclick="location.href='<?php echo base_url();?>/admin/employees'"  > <i class="fa fa-power-off "></i> <?php echo $this->lang->line('xin_close');?> </button>
              <?php echo form_button(array('name' => 'hris_form', 'type' => 'submit', 'class' => 'btn btn-primary', 'content' => '<i class="fa fa fa-check-square-o"></i> '.$this->lang->line('xin_save'))); ?> </div>
            <?php echo form_close(); ?> </div>
        </div>

      </div>
    </div>

    <div class="box <?php echo $get_animate;?>">
      <div class="box-header with-border">
        <h3 class="box-title"> <?php echo $this->lang->line('xin_list_all');?> Karyawan </h3>
        
        <!-- <?php if($user_info[0]->user_role_id==1){ ?> -->
            <div class="box-tools pull-right"> 
              
            </div>
        <!-- <?php } ?> -->
      </div>
      <div class="box-body">
        <div class="box-datatable table-responsive">
          <table class="datatables-demo table table-striped table-bordered" id="xin_table" width="100%">
            <thead>
                   <tr>
                      <th width="70px"><center><?php echo $this->lang->line('xin_action');?> </center></th>
                      <th width="30px"><center> Foto </center></th>
                      <th width="100px"><center> Tanggal<br>Rekrutmen </center></th>
                      <th width="180px"><center> Nama<br>Karyawan </center></th>
                      <th width="180px"><center> Lokasi<br>Kerja </center></th>
                      <th width="170px"><center> Posisi<br>Karyawan </center></th>
                      <th width="80px"><center> Status<br>Karyawan </center></th>
                      <th width="80px"><center> Kontrak<br>Karyawan </center></th> 
                      <th width="80px"><center> Group<br>Karyawan </center></th>       
                      <th width="80px"><center> Grade<br>Karyawan </center></th>              
                    </tr>
            </thead>
          </table>
        </div>
      </div>
    </div>

<?php }?>

  
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