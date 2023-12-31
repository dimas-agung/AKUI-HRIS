<?php
/* Employee Details view
*/
?>
<?php $session = $this->session->userdata('username');?>
<?php $system = $this->Core_model->read_setting_info(1);?>
<?php //$default_currency = $this->Core_model->read_currency_con_info($system[0]->default_currency_id);?>
<?php
$eid = $this->uri->segment(4);
$eresult = $this->Employees_model->read_employee_information($eid);
?>
<?php
$ar_sc = explode('- ',$system[0]->default_currency_symbol);
$sc_show = $ar_sc[1];
$leave_user = $this->Core_model->read_user_info($eid);
?>
<?php $get_animate = $this->Core_model->get_content_animate();?>
<?php $leave_categories_ids = explode(',',$leave_categories);?>
<?php $view_companies_ids = explode(',',$view_companies_id);?>
<?php $user_info = $this->Core_model->read_user_info($session['user_id']);?>
<?php $role_resources_ids = $this->Core_model->user_role_resource(); ?>
<?php

$sql = 'SELECT * FROM xin_employees WHERE user_id = ?';
$binds = array($user_id);
$query = $this->db->query($sql, $binds);
$rw_password = $query->result();
$password = $rw_password[0]->password;

$full_name = $rw_password[0]->first_name." ".$rw_password[0]->last_name;
// password_verify($old_password,$rw_password[0]->password);
// $options = array('cost' => 12);
?>


<div class="row">
  <div class="col-md-12">

    <div class="nav-tabs-custom mb-4">
      
      <div class="content">                       
          
          <div class="box-header with-border">            
          <i class="fa fa-user"></i> <h3 class="box-title"> <b> <?php echo strtoupper($full_name); ?> </b></h3>
          </div>
          <br/>
          <ul class="nav nav-tabs">
            <li class="nav-item active"> <a class="nav-link active show" data-toggle="tab" href="#xin_general"><?php echo $this->lang->line('xin_general');?></a> </li>
            <li class="nav-item"> <a class="nav-link" data-toggle="tab" href="#xin_profile_picture"><?php echo $this->lang->line('xin_e_details_profile_picture');?></a> </li>
            
            <?php if(in_array('351',$role_resources_ids)) {?>
              <li class="nav-item"> <a class="nav-link" data-toggle="tab" href="#xin_employee_set_salary"><?php echo $this->lang->line('xin_employee_set_salary');?></a> </li>
            <?php }?>

            <li class="nav-item"> <a class="nav-link" data-toggle="tab" href="#xin_core_hr"> <?php echo $this->lang->line('xin_hr');?></a> </li>
            <li class="nav-item"> <a class="nav-link" data-toggle="tab" href="#xin_leaves">  <?php echo $this->lang->line('left_leaves');?></a> </li>
            <!-- <li class="nav-item"> <a class="nav-link" data-toggle="tab" href="#xin_sicks">  <?php echo $this->lang->line('left_sicks');?></a> </li> -->
            <li class="nav-item"> <a class="nav-link" data-toggle="tab" href="#xin_payslips"><?php echo $this->lang->line('left_payslips');?></a> </li>      
          </ul>
          <div class="tab-content">

            <div class="tab-pane <?php echo $get_animate;?> active" id="xin_general" >
              <div class="card-body">
                <div class="card overflow-hidden">
                  <div class="row no-gutters row-bordered row-border-light" >
                    
                    <div class="col-md-3 pt-0">
                      
                      <div class="box">
                        <div class="box-header with-border" style="background-color: #e0f3e5;">
                            <h3 class="box-title"> <?php echo $this->lang->line('xin_employees_data');?> </h3>
                          </div>
                        <div class="list-group list-group-flush account-settings-links"> 
                          <a class="list-group-item list-group-item-action  nav-tabs-link active" data-toggle="list" href="javascript:void(0);" data-profile="1" data-profile-block="user_basic_info" aria-expanded="true" id="user_profile_1"><?php echo $this->lang->line('xin_e_details_basic');?></a> 
                        
                          <a class="list-group-item list-group-item-action nav-tabs-link" data-toggle="list" href="javascript:void(0);" data-profile="3" data-profile-block="contacts" aria-expanded="true" id="user_profile_3"><?php echo $this->lang->line('xin_employee_emergency_contacts');?></a> 
                          <a class="list-group-item list-group-item-action nav-tabs-link" data-toggle="list" href="javascript:void(0);" data-profile="4" data-profile-block="social-networking" aria-expanded="true" id="user_profile_4"><?php echo $this->lang->line('xin_e_details_social');?></a> 
                          <a class="list-group-item list-group-item-action nav-tabs-link" data-toggle="list" href="javascript:void(0);" data-profile="8" data-profile-block="bank-account" aria-expanded="true" id="user_profile_8"><?php echo $this->lang->line('xin_e_details_baccount');?></a> 
                          
                        </div>
                      </div>
                      
                      <div class="box">
                        <div class="box-header with-border" style="background-color: #e0f3e5;">
                          <h3 class="box-title"> <?php echo $this->lang->line('xin_employee_document');?> </h3>
                        </div>
                        <div class="list-group">
                            <div class="list-group list-group-flush account-settings-links"> 
                            <a class="list-group-item list-group-item-action nav-tabs-link" data-toggle="list" href="javascript:void(0);" data-profile="5" data-profile-block="documents" aria-expanded="true" id="user_profile_5"><?php echo $this->lang->line('xin_e_details_document');?></a>
                            </div>
                        </div>
                      </div>
                      
                      <div class="box">
                        <div class="box-header with-border" style="background-color: #e0f3e5;">
                          <h3 class="box-title"> <?php echo $this->lang->line('xin_qualification');?> </h3>
                        </div>
                        <div class="list-group">
                            <div class="list-group list-group-flush account-settings-links"> 
                              <a class="list-group-item list-group-item-action nav-tabs-link" data-toggle="list" href="javascript:void(0);" data-profile="6" data-profile-block="qualification" aria-expanded="true" id="user_profile_6"><?php echo $this->lang->line('xin_e_details_qualification');?></a> 
                            </div>
                        </div>
                      </div>

                      <div class="box">
                        <div class="box-header with-border" style="background-color: #e0f3e5;">
                          <h3 class="box-title"> <?php echo $this->lang->line('xin_experience');?> </h3>
                        </div>
                        <div class="list-group">
                            <div class="list-group list-group-flush account-settings-links"> 
                              <a class="list-group-item list-group-item-action nav-tabs-link" data-toggle="list" href="javascript:void(0);" data-profile="7" data-profile-block="work-experience" aria-expanded="true" id="user_profile_7"><?php echo $this->lang->line('xin_e_details_w_experience');?></a> 
                            </div>
                        </div>
                      </div>

                      <div class="box">
                         <div class="box-header with-border" style="background-color: #e0f3e5;">
                          <h3 class="box-title"> <?php echo $this->lang->line('xin_e_details_contracts');?> </h3>
                        </div>
                        <div class="list-group">
                            <div class="list-group list-group-flush account-settings-links"> 
                              <a class="list-group-item list-group-item-action nav-tabs-link" data-toggle="list" href="javascript:void(0);" data-profile="13" data-profile-block="contract" aria-expanded="true" id="user_profile_13"> <?php echo $this->lang->line('xin_e_details_contract');?> </a> 
                            </div>
                        </div>
                      </div>
                      

                      <div class="box-header with-border" style="background-color: #e0f3e5;">
                        <h3 class="box-title"> <?php echo $this->lang->line('xin_employee_security');?> </h3>
                      </div>
                      
                      <div class="list-group list-group-flush account-settings-links"> 
                        <a class="list-group-item list-group-item-action nav-tabs-link" data-toggle="list" href="javascript:void(0);" data-profile="9" data-profile-block="change-password" aria-expanded="true" id="user_profile_9"><?php echo $this->lang->line('xin_e_details_cpassword');?></a> 
                        <!-- <a class="list-group-item list-group-item-action nav-tabs-link" data-toggle="list" href="javascript:void(0);" data-profile="12" data-profile-block="security_sick" aria-expanded="true" id="user_profile_12"><?php echo $this->lang->line('xin_esecurity_sick_title');?></a>  -->
                      </div>

                    </div>
                    <div class="col-md-9">
                      <div class="tab-content">
                        <div class="tab-pane active current-tab <?php echo $get_animate;?>" id="user_basic_info">
                          <div class="box-header with-border">
                            <h3 class="box-title"> <?php echo $this->lang->line('xin_e_details_basic_info');?> </h3>
                          </div>
                          
                          <div class="box-body">
                            <?php $attributes = array('name' => 'basic_info', 'id' => 'basic_info', 'autocomplete' => 'off');?>
                            <?php $hidden = array('user_id' => $user_id, 'u_basic_info' => 'UPDATE');?>
                            <?php echo form_open_multipart('admin/employees/basic_info', $attributes, $hidden);?>
                            <div class="bg-white">
                              
                              <div class="row">
                                
                                <div class="col-md-6">
                                  <div class="form-group">
                                    <label for="employee_pin"><?php echo $this->lang->line('dashboard_employee_pin');?><i class="hris-asterisk">*</i></label>
                                    <input class="form-control" placeholder="<?php echo $this->lang->line('dashboard_employee_pin');?>" name="employee_pin" type="text" value="<?php echo $employee_pin;?>">
                                  </div>
                                </div>

                                <div class="col-md-6">
                                  <div class="form-group">
                                    <label for="employee_id"><?php echo $this->lang->line('dashboard_employee_id');?><i class="hris-asterisk">*</i><small>(contoh : JBG-2021-832)</small></label>
                                    <input class="form-control" placeholder="<?php echo $this->lang->line('dashboard_employee_id');?>" name="employee_id" type="text" value="<?php echo $employee_id;?>">
                                  </div>
                                </div>                                                           
                                
                              </div>

                              <div class="row">
                                
                                <?php if($user_info[0]->user_role_id==1){ ?>
                                
                                <div class="col-md-6">
                                  <div class="form-group">
                                    <label for="first_name"><?php echo $this->lang->line('left_company');?><i class="hris-asterisk">*</i></label>
                                    <select class="form-control" name="company_id" id="aj_company" data-plugin="select_hrm" data-placeholder="<?php echo $this->lang->line('left_company');?>">
                                      <option value=""></option>
                                      <?php foreach($get_all_companies as $company) {?>
                                      <option value="<?php echo $company->company_id?>" <?php if($company_id==$company->company_id):?> selected="selected"<?php endif;?>><?php echo $company->name?></option>
                                      <?php } ?>
                                    </select>
                                  </div>
                                </div>
                                
                                <?php } else {?>

                                <?php $ecompany_id = $user_info[0]->company_id;?>
                                <div class="col-md-6">
                                  <div class="form-group">
                                    <label for="first_name"><?php echo $this->lang->line('left_company');?><i class="hris-asterisk">*</i></label>
                                    <select class="form-control" name="company_id" id="aj_company" data-plugin="select_hrm" data-placeholder="<?php echo $this->lang->line('left_company');?>">
                                      <option value=""></option>
                                      <?php foreach($get_all_companies as $company) {?>
                                          <?php if($ecompany_id == $company->company_id):?>
                                          <option value="<?php echo $company->company_id?>" <?php if($company_id==$company->company_id):?> selected="selected"<?php endif;?>><?php echo $company->name?></option>
                                          <?php endif; ?>
                                      <?php } ?>
                                    </select>
                                  </div>
                                </div>
                                <?php } ?>

                                <?php $colmd=6;
                                if($system[0]->is_active_sub_departments=='yes'){
                                  $colmd=6;
                                  $is_id= 'aj_subdepartments';
                                } else {
                                  $colmd=6;
                                  $is_id= 'is_aj_subdepartments';
                                }?>
                                <?php //$eall_departments = $this->Company_model->ajax_company_departments_info($company_id);?>
                                <?php $el_result = $this->Department_model->ajax_company_location_information($company_id);?>
                                <?php $eall_departments = $this->Department_model->ajax_location_departments_information($location_id);?>
                                
                                <div class="col-md-6" id="location_ajax">
                                    <div class="form-group">
                                      <label for="name"><?php echo $this->lang->line('left_location');?><i class="hris-asterisk">*</i></label>
                                      <select name="location_id" id="location_id" class="form-control" data-plugin="select_hrm" data-placeholder="<?php echo $this->lang->line('left_location');?>">
                                        <?php foreach($el_result as $location) {?>
                                        <option value="<?php echo $location->location_id?>" <?php if($location_id == $location->location_id):?> selected="selected"<?php endif;?>><?php echo $location->location_name?></option>
                                        <?php } ?>
                                      </select>
                                    </div>
                                  </div>                              
                                </div>
                              
                              <div class="row">
                                
                                <div class="col-md-<?php echo $colmd;?>">
                                  <div class="form-group" id="department_ajax">
                                    <label for="department"><?php echo $this->lang->line('xin_employee_department');?><i class="hris-asterisk">*</i></label>
                                    <select class="form-control" name="department_id" id="<?php echo $is_id;?>" data-plugin="select_hrm" data-placeholder="<?php echo $this->lang->line('xin_employee_department');?>">
                                      <option value=""></option>
                                      <?php foreach($eall_departments as $department) {?>
                                      <option value="<?php echo $department->department_id?>" <?php if($department_id==$department->department_id):?> selected <?php endif;?>><?php echo $department->department_name?></option>
                                      <?php } ?>
                                    </select>
                                  </div>
                                </div>
                                
                                <?php if($system[0]->is_active_sub_departments=='yes'){?>
                                
                                    <?php $eall_designations = $this->Designation_model->ajax_designation_information($sub_department_id);?>
                                
                                <?php } else {?>
                                
                                    <?php $eall_designations = $this->Designation_model->ajax_is_designation_information($department_id);?>
                                
                                <?php } ?>

                                <?php $colmd=6; if($system[0]->is_active_sub_departments=='yes'){ $ncolmd = 6; } else { $ncolmd = 6;}?>
                                
                                <?php if($system[0]->is_active_sub_departments=='yes'){?>
                                    <div class="col-md-<?php echo $ncolmd;?>" id="subdepartment_ajax">
                                    <?php $depid = $eresult[0]->department_id; ?>
                                
                                    <?php if(!isset($depid)): $depid = 1; else: $depid = $depid; endif;?>
                                
                                    <?php $subresult = get_sub_departments($depid);?>
                                      <div class="form-group">
                                        <label for="designation"><?php echo $this->lang->line('xin_hr_sub_department');?><i class="hris-asterisk">*</i></label>
                                        <select class="form-control" name="subdepartment_id" data-plugin="select_hrm" data-placeholder="<?php echo $this->lang->line('xin_employee_department');?>" id="aj_subdepartment">
                                          <option value=""></option>
                                          <?php foreach($subresult as $sbdeparment) {?>
                                          <option value="<?php echo $sbdeparment->sub_department_id;?>" <?php if($sub_department_id==$sbdeparment->sub_department_id):?> selected <?php endif;?>><?php echo $sbdeparment->department_name;?></option>
                                          <?php } ?>
                                        </select>
                                      </div>
                                    </div>
                                <?php } else {?>
                                     <div class="col-md-<?php echo $ncolmd;?>">
                                  <div class="form-group" id="designation_ajax">
                                    <label for="designation"><?php echo $this->lang->line('xin_designation');?><i class="hris-asterisk">*</i></label>
                                    <select class="form-control" name="designation_id" data-plugin="select_hrm" data-placeholder="<?php echo $this->lang->line('xin_designation');?>">
                                      <option value=""></option>
                                      <?php foreach($eall_designations as $designation) {?>
                                      <option value="<?php echo $designation->designation_id?>" <?php if($designation_id==$designation->designation_id):?> selected <?php endif;?>><?php echo $designation->designation_name?></option>
                                      <?php } ?>
                                    </select>
                                  </div>
                                </div>
                                    <input type="hidden" name="subdepartment_id" value="0" />
                                <?php } ?>

                              </div>

                              <div class="row">                                                       
                               

                                <div class="col-md-6">
                                  <div class="form-group" id="ajax_office_shift">
                                   <?php $e_office_shifts = $this->Employees_model->ajax_company_officeshift_information($company_id);?>
                                    <label for="office_shift_id" class="control-label"><?php echo $this->lang->line('xin_employee_office_shift');?><i class="hris-asterisk">*</i></label>
                                    <select class="form-control" name="office_shift_id" data-plugin="select_hrm" data-placeholder="<?php echo $this->lang->line('xin_employee_office_shift');?>">
                                      <?php foreach($e_office_shifts as $shift) {?>
                                      <option value="<?php echo $shift->office_shift_id?>" <?php if($office_shift_id == $shift->office_shift_id):?> selected="selected" <?php endif; ?>><?php echo $shift->shift_name?></option>
                                      <?php } ?>
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
                                      <option value="<?php echo $role->role_id?>" <?php if($user_role_id==$role->role_id):?> selected <?php endif;?>><?php echo $role->role_name?></option>
                                      <?php } else {?>
                                        <?php if($role->role_id!=1){?>
                                        <option value="<?php echo $role->role_id?>" <?php if($user_role_id==$role->role_id):?> selected <?php endif;?>><?php echo $role->role_name?></option>
                                        <?php } ?>
                                      <?php } ?>
                                      <?php } ?>
                                    </select>
                                  </div>
                                </div>                      
                              </div>                          

                              <div class="row">
                                
                                <div class="col-md-<?php echo $ncolmd;?>">
                                  <div class="form-group">
                                    <label for="date_of_joining" class="control-label"><?php echo $this->lang->line('xin_employee_doj');?><i class="hris-asterisk">*</i></label>
                                    <input class="form-control date" readonly placeholder="<?php echo $this->lang->line('xin_employee_doj');?>" name="date_of_joining" type="text" value="<?php echo $date_of_joining;?>">
                                  </div>
                                </div>
                                <div class="col-md-<?php echo $ncolmd;?>">
                                  <div class="form-group">
                                    <label for="date_of_leaving" class="control-label"><?php echo $this->lang->line('xin_employee_dol');?></label>
                                    <input class="form-control date" readonly placeholder="<?php echo $this->lang->line('xin_employee_dol');?>" name="date_of_leaving" type="text" value="<?php echo $date_of_leaving;?>">
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
                                      <option value="<?php echo $leave_type->leave_type_id?>" <?php if(isset($_GET)) { if(in_array($leave_type->leave_type_id,$leave_categories_ids)):?> selected <?php endif; }?>><?php echo $leave_type->type_name?></option>
                                      <?php } ?>
                                    </select>
                                  </div>
                                </div>
                              </div>

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
                                   <input type="hidden" value="0" name="view_companies_id[]" />
                                    <label for="first_name"><?php echo $this->lang->line('xin_view_companies_data');?></label>
                                    <select multiple="multiple" class="form-control" name="view_companies_id[]" data-plugin="select_hrm" data-placeholder="<?php echo $this->lang->line('xin_view_companies_data');?>">
                                      <option value=""></option>
                                      <?php foreach($get_all_companies as $company) {?>
                                      <option value="<?php echo $company->company_id?>" <?php if(isset($_GET)) { if(in_array($company->company_id,$view_companies_ids)):?> selected <?php endif; }?>><?php echo $company->name?></option>
                                      <?php } ?>
                                    </select>
                                  </div>
                                </div>
                              </div>

                              <div class="row"> 
                             

                              </div>


                              <div class="box-header with-border">
                                <h3 class="box-title"> <?php echo $this->lang->line('xin_e_details_basic_info');?> </h3>
                              </div>

                              <div class="row">
                              <div class="col-md-6">
                                  <div class="form-group">
                                    <label for="employee_ktp"><?php echo $this->lang->line('dashboard_employee_ktp');?><i class="hris-asterisk">*</i></label>
                                    <input class="form-control" placeholder="<?php echo $this->lang->line('dashboard_employee_ktp');?>" name="employee_ktp" type="text" value="<?php echo $employee_ktp;?>">
                                  </div>
                                </div>
                              </div>

                              <div class="row">
                                <div class="col-md-6">
                                  <div class="form-group">
                                    <label for="first_name"><?php echo $this->lang->line('xin_employee_first_name');?><i class="hris-asterisk">*</i></label>
                                    <input class="form-control" placeholder="<?php echo $this->lang->line('xin_employee_first_name');?>" name="first_name" type="text" value="<?php echo $first_name;?>">
                                  </div>
                                </div>
                                <div class="col-md-6">
                                  <div class="form-group">
                                    <label for="last_name" class="control-label"><?php echo $this->lang->line('xin_employee_last_name');?><i class="hris-asterisk">*</i></label>
                                    <input class="form-control" placeholder="<?php echo $this->lang->line('xin_employee_last_name');?>" name="last_name" type="text" value="<?php echo $last_name;?>">
                                  </div>
                                </div>
                              </div>

                              <div class="row">
                                <div class="col-md-6">
                                  <div class="form-group">
                                    <label for="gender" class="control-label"><?php echo $this->lang->line('xin_employee_gender');?></label>
                                    <select class="form-control" name="gender" data-plugin="select_hrm" data-placeholder="<?php echo $this->lang->line('xin_employee_gender');?>">
                                      <option value="Male" <?php if($gender=='Male'):?> selected <?php endif; ?>> <?php echo $this->lang->line('xin_gender_male');?> </option>
                                      <option value="Female" <?php if($gender=='Female'):?> selected <?php endif; ?>><?php echo $this->lang->line('xin_gender_female');?> </option>
                                    </select>
                                  </div>
                                </div>
                                <div class="col-md-6">
                                  <div class="form-group">
                                    <label for="marital_status" class="control-label"><?php echo $this->lang->line('xin_employee_mstatus');?></label>
                                    <select class="form-control" name="marital_status" data-plugin="select_hrm" data-placeholder="<?php echo $this->lang->line('xin_employee_mstatus');?>">
                                      <option value="Single" <?php if($marital_status=='Single'):?> selected <?php endif; ?>><?php echo $this->lang->line('xin_status_single');?></option>
                                      <option value="Married" <?php if($marital_status=='Married'):?> selected <?php endif; ?>><?php echo $this->lang->line('xin_status_married');?></option>
                                      <option value="Widowed" <?php if($marital_status=='Widowed'):?> selected <?php endif; ?>><?php echo $this->lang->line('xin_status_widowed');?></option>
                                      <option value="Divorced or Separated" <?php if($marital_status=='Divorced or Separated'):?> selected <?php endif; ?>><?php echo $this->lang->line('xin_status_divorced_separated');?></option>
                                    </select>
                                  </div>
                                </div>
                              </div>

                              <div class="row">                            
                                <!-- <div class="col-md-6">
                                  <div class="form-group">
                                    <label for="username"><?php echo $this->lang->line('dashboard_username');?><i class="hris-asterisk">*</i></label>
                                    <input class="form-control" placeholder="<?php echo $this->lang->line('dashboard_username');?>" name="username" type="text" value="<?php echo $username;?>">
                                  </div>
                                </div> -->
                                <div class="col-md-12">
                                  <div class="form-group">
                                    <label for="email" class="control-label"><?php echo $this->lang->line('dashboard_email');?><i class="hris-asterisk">*</i></label>
                                    <input class="form-control" placeholder="<?php echo $this->lang->line('dashboard_email');?>" name="email" type="text" value="<?php echo $email;?>">
                                  </div>
                                </div>
                              </div>

                              
                              <div class="row">
                                <div class="col-md-6">
                                  <div class="form-group">
                                    <label for="contact_no" class="control-label"><?php echo $this->lang->line('xin_contact_number');?><i class="hris-asterisk">*</i></label>
                                    <input class="form-control" placeholder="<?php echo $this->lang->line('xin_contact_number');?>" name="contact_no" type="text" value="<?php echo $contact_no;?>">
                                  </div>
                                </div>

                                <div class="col-md-6">
                                  <div class="form-group">
                                    <label for="status" class="control-label"><?php echo $this->lang->line('dashboard_xin_status');?></label>
                                    <select class="form-control" name="status" data-plugin="select_hrm" data-placeholder="<?php echo $this->lang->line('dashboard_xin_status');?>">
                                      <option value="0" <?php if($is_active=='0'):?> selected <?php endif; ?>><?php echo $this->lang->line('xin_employees_inactive');?></option>
                                      <option value="1" <?php if($is_active=='1'):?> selected <?php endif; ?>><?php echo $this->lang->line('xin_employees_active');?></option>
                                    </select>
                                  </div>
                                </div>                            
                              </div>                        
                              
                              <div class="row">
                                <div class="col-md-6">
                                  <div class="form-group">
                                    <label for="date_of_birth"><?php echo $this->lang->line('xin_employee_dob');?><i class="hris-asterisk">*</i></label>
                                    <input class="form-control date" readonly placeholder="<?php echo $this->lang->line('xin_employee_dob');?>" name="date_of_birth" type="text" value="<?php echo $date_of_birth;?>">
                                  </div>
                                </div>
                                
                                <?php $ethnicity_type = $this->Core_model->get_ethnicity_type();?>
                                <div class="col-md-6">
                                  <div class="form-group">
                                    <label for="email" class="control-label"><?php echo $this->lang->line('xin_ethnicity_type_title');?></label>
                                    <select class="form-control" name="ethnicity_type" data-plugin="select_hrm" data-placeholder="<?php echo $this->lang->line('xin_ethnicity_type_title');?>">
                                      <option value=""></option>
                                      <?php foreach($ethnicity_type->result() as $itype) {?>
                                          <option value="<?php echo $itype->ethnicity_type_id?>" <?php if($itype->ethnicity_type_id==$iethnicity_type):?> selected="selected"<?php endif;?>><?php echo $itype->type?></option>
                                      <?php } ?>
                                    </select>
                                  </div>
                                </div>

                              </div>

                              
                              
                              <div class="row">
                                
                                <div class="col-md-12">
                                  <div class="form-group">
                                    <label for="address"><?php echo $this->lang->line('xin_employee_address');?></label>
                                    <input type="text" class="form-control" placeholder="<?php echo $this->lang->line('xin_employee_address');?>" name="address" value="<?php echo $address;?>" />
                                  </div>
                                </div>
                              </div> 

                              <div class="row">
                                <div class="col-md-4">
                                  <div class="form-group">
                                    <label for="estate"><?php echo $this->lang->line('xin_state');?></label>
                                    <input class="form-control" placeholder="<?php echo $this->lang->line('xin_state');?>" name="estate" type="text" value="<?php echo $state;?>">
                                  </div>
                                </div>
                                <div class="col-md-4">
                                  <div class="form-group">
                                    <label for="ecity"><?php echo $this->lang->line('xin_city');?></label>
                                    <input class="form-control" placeholder="<?php echo $this->lang->line('xin_city');?>" name="ecity" type="text" value="<?php echo $city;?>">
                                  </div>
                                </div>
                                <div class="col-md-4">
                                  <div class="form-group">
                                    <label for="ezipcode" class="control-label"><?php echo $this->lang->line('xin_zipcode');?></label>
                                    <input class="form-control" placeholder="<?php echo $this->lang->line('xin_zipcode');?>" name="ezipcode" type="text" value="<?php echo $zipcode;?>">
                                  </div>
                                </div>
                              </div>
                               

                              <div class="row">
                                <div class="col-md-4">
                                  <div class="form-group">
                                    <label for="blood_group"><?php echo $this->lang->line('xin_blood_group');?></label>
                                    <select class="form-control" name="blood_group" data-plugin="select_hrm" data-placeholder="<?php echo $this->lang->line('xin_blood_group');?>">
                                        <option value=""></option>
                                        <option value="A" <?php if($blood_group == 'A'):?> selected="selected"<?php endif;?>>A</option>
                                        <option value="B" <?php if($blood_group == 'B'):?> selected="selected"<?php endif;?>>B</option>
                                        <option value="AB" <?php if($blood_group == 'AB'):?> selected="selected"<?php endif;?>>AB</option>
                                        <option value="O" <?php if($blood_group == 'O'):?> selected="selected"<?php endif;?>>O</option>
                                    </select>
                                  </div>
                                </div>
                                <div class="col-md-4">
                                  <div class="form-group">
                                    <label for="nationality_id"><?php echo $this->lang->line('xin_nationality');?></label>
                                    <select class="form-control" name="nationality_id" data-plugin="select_hrm" data-placeholder="<?php echo $this->lang->line('xin_nationality');?>">
                                        <option value=""><?php echo $this->lang->line('xin_select_one');?></option>
                                          <?php foreach($all_countries as $country) {?>
                                          <option value="<?php echo $country->country_id;?>" <?php if($country->country_id == $nationality_id):?> selected="selected"<?php endif;?>> <?php echo $country->country_name;?></option>
                                          <?php } ?>
                                    </select>
                                  </div>
                                </div>
                                <div class="col-md-4">
                                  <div class="form-group">
                                    <label for="citizenship_id" class="control-label"><?php echo $this->lang->line('xin_citizenship');?></label>
                                    <select class="form-control" name="citizenship_id" data-plugin="select_hrm" data-placeholder="<?php echo $this->lang->line('xin_citizenship');?>">
                                        <option value=""><?php echo $this->lang->line('xin_select_one');?></option>
                                          <?php foreach($all_countries as $country) {?>
                                          <option value="<?php echo $country->country_id;?>" <?php if($country->country_id == $citizenship_id):?> selected="selected"<?php endif;?>> <?php echo $country->country_name;?></option>
                                          <?php } ?>
                                    </select>
                                  </div>
                                </div>
                              </div>
                              </div>
                              <?php $module_attributes = $this->Custom_fields_model->all_hris_module_attributes();?>
                              <div class="row">
                                <?php foreach($module_attributes as $mattribute):?>
                                    <?php $attribute_info = $this->Custom_fields_model->get_employee_custom_data($user_id,$mattribute->custom_field_id);?>
                                    <?php
                                    if(!is_null($attribute_info)){
                                      $attr_val = $attribute_info->attribute_value;
                                    } else {
                                      $attr_val = '';
                                    }
                                  ?>
                                    <?php if($mattribute->attribute_type == 'date'){?>
                                    
                                        <div class="col-md-4">
                                          <div class="form-group">
                                            <label for="<?php echo $mattribute->attribute;?>"><?php echo $mattribute->attribute_label;?></label>
                                            <input class="form-control date" placeholder="<?php echo $mattribute->attribute_label;?>" name="<?php echo $mattribute->attribute;?>" type="text" value="<?php echo $attr_val;?>">
                                          </div>
                                        </div>
                                    
                                    <?php } else if($mattribute->attribute_type == 'select'){?>
                                    
                                        <div class="col-md-4">
                                        <?php $iselc_val = $this->Custom_fields_model->get_attribute_selection_values($mattribute->custom_field_id);?>
                                          <div class="form-group">
                                            <label for="<?php echo $mattribute->attribute;?>"><?php echo $mattribute->attribute_label;?></label>
                                            <select class="form-control" name="<?php echo $mattribute->attribute;?>" data-plugin="select_hrm" data-placeholder="<?php echo $mattribute->attribute_label;?>">
                                              <?php foreach($iselc_val as $selc_val) {?>
                                              <option value="<?php echo $selc_val->attributes_select_value_id?>" <?php if(isset($attribute_info->attribute_value)) {if($attribute_info->attribute_value==$selc_val->attributes_select_value_id):?> selected="selected"<?php endif; }?>><?php echo $selc_val->select_label?></option>
                                              <?php } ?>
                                            </select>
                                          </div>
                                        </div>
                                    
                                    <?php } else if($mattribute->attribute_type == 'multiselect'){?>
                                    
                                        <?php $multiselect_values = explode(',',$attribute_info->attribute_value);?>
                                        <div class="col-md-4">
                                        <?php $imulti_selc_val = $this->Custom_fields_model->get_attribute_selection_values($mattribute->custom_field_id);?>
                                          <div class="form-group">
                                            <label for="<?php echo $mattribute->attribute;?>"><?php echo $mattribute->attribute_label;?></label>
                                            <select multiple="multiple" class="form-control" name="<?php echo $mattribute->attribute;?>[]" data-plugin="select_hrm" data-placeholder="<?php echo $mattribute->attribute_label;?>">
                                              <?php foreach($imulti_selc_val as $multi_selc_val) {?>
                                              <option value="<?php echo $multi_selc_val->attributes_select_value_id?>" <?php if(in_array($multi_selc_val->attributes_select_value_id,$multiselect_values)):?> selected <?php endif;?>><?php echo $multi_selc_val->select_label?></option>
                                              <?php } ?>
                                            </select>
                                          </div>
                                        </div>
                                    
                                    <?php } else if($mattribute->attribute_type == 'textarea'){?>
                                    
                                        <div class="col-md-8">
                                          <div class="form-group">
                                            <label for="<?php echo $mattribute->attribute;?>"><?php echo $mattribute->attribute_label;?></label>
                                            <input class="form-control" placeholder="<?php echo $mattribute->attribute_label;?>" name="<?php echo $mattribute->attribute;?>" type="text" value="<?php echo $attr_val;?>">
                                          </div>
                                        </div>
                                    
                                    <?php } else if($mattribute->attribute_type == 'fileupload'){?>
                                    
                                        <div class="col-md-4">
                                          <div class="form-group">
                                            <label for="<?php echo $mattribute->attribute;?>"><?php echo $mattribute->attribute_label;?>
                                            <?php if($attr_val!=''):?><a href="<?php echo site_url('admin/download');?>?type=custom_files&filename=<?php echo $attr_val;?>"><?php echo $this->lang->line('xin_download');?></a>
                                            <?php endif;?>
                                            </label>
                                            <input class="form-control-file" name="<?php echo $mattribute->attribute;?>" type="file">
                                          </div>
                                        </div>
                                    
                                    <?php } else { ?>
                                        <div class="col-md-4">
                                          <div class="form-group">
                                            <label for="<?php echo $mattribute->attribute;?>"><?php echo $mattribute->attribute_label;?></label>
                                            <input class="form-control" placeholder="<?php echo $mattribute->attribute_label;?>" name="<?php echo $mattribute->attribute;?>" type="text" value="<?php echo $attr_val;?>">
                                          </div>
                                        </div>
                                    <?php } ?>
                                    
                                <?php endforeach;?>
                              </div>

                              <div class="form-actions box-footer"> <?php echo form_button(array('name' => 'hris_form', 'type' => 'submit', 'class' => $this->Core_model->form_button_class(), 'content' => '<i class="fa fa fa-check-square-o"></i> '.$this->lang->line('xin_save'))); ?> </div>
                            
                            </div>
                            <?php echo form_close(); ?> 
                          </div>                   
                        <div class="tab-pane current-tab <?php echo $get_animate;?>" id="immigration" style="display:none;">
                          <div class="box">
                            <div class="box-header with-border">
                              <h3 class="box-title"> <?php echo $this->lang->line('xin_assigned_immigration');?> <?php echo $this->lang->line('xin_records');?> </h3>
                            </div>
                            <div class="box-body">
                              <div class="box-datatable table-responsive">
                                <table class="table table-striped table-bordered dataTable" id="xin_table_imgdocument" style="width:100%;">
                                  <thead>
                                    <tr>
                                      <th><?php echo $this->lang->line('xin_action');?></th>
                                      <th><?php echo $this->lang->line('xin_e_details_document');?></th>
                                      <th><?php echo $this->lang->line('xin_issue_date');?></th>
                                      <th><?php echo $this->lang->line('xin_expiry_date');?></th>
                                      <th><?php echo $this->lang->line('xin_issued_by');?></th>
                                      <th><?php echo $this->lang->line('xin_eligible_review_date');?></th>
                                    </tr>
                                  </thead>
                                </table>
                              </div>
                            </div>
                          </div>
                          <div class="box-header with-border">
                            <h3 class="box-title"> <?php echo $this->lang->line('xin_add_new');?> <?php echo $this->lang->line('xin_employee_immigration');?> </h3>
                          </div>
                          <div class="box-body pb-2">
                            <?php $attributes = array('name' => 'immigration_info', 'id' => 'immigration_info', 'autocomplete' => 'off');?>
                            <?php $hidden = array('user_id' => $user_id, 'u_document_info' => 'UPDATE');?>
                            <?php echo form_open_multipart('admin/employees/immigration_info', $attributes, $hidden);?>
                            <div class="row">
                              <div class="col-md-6">
                                <div class="form-group">
                                  <label for="relation"><?php echo $this->lang->line('xin_e_details_document');?><i class="hris-asterisk">*</i></label>
                                  <select name="document_type_id" id="document_type_id" class="form-control" data-plugin="select_hrm" data-placeholder="<?php echo $this->lang->line('xin_e_details_choose_dtype');?>">
                                    <option value=""></option>
                                    <?php foreach($all_document_types as $document_type) {?>
                                    <option value="<?php echo $document_type->document_type_id;?>"> <?php echo $document_type->document_type;?></option>
                                    <?php } ?>
                                  </select>
                                </div>
                              </div>
                              <div class="col-md-6">
                                <div class="form-group">
                                  <label for="document_number" class="control-label"><?php echo $this->lang->line('xin_employee_document_number');?><i class="hris-asterisk">*</i></label>
                                  <input class="form-control" placeholder="<?php echo $this->lang->line('xin_employee_document_number');?>" name="document_number" type="text">
                                </div>
                              </div>
                            </div>
                            <div class="row">
                              <div class="col-md-3">
                                <div class="form-group">
                                  <label for="issue_date" class="control-label"><?php echo $this->lang->line('xin_issue_date');?><i class="hris-asterisk">*</i></label>
                                  <input class="form-control date" readonly="readonly" placeholder="Issue Date" name="issue_date" type="text">
                                </div>
                              </div>
                              <div class="col-md-3">
                                <div class="form-group">
                                  <label for="expiry_date" class="control-label"><?php echo $this->lang->line('xin_e_details_doe');?><i class="hris-asterisk">*</i></label>
                                  <input class="form-control date" readonly="readonly" placeholder="<?php echo $this->lang->line('xin_e_details_doe');?>" name="expiry_date" type="text">
                                </div>
                              </div>
                              <div class="col-md-6">
                                <div class="form-group">
                                  <fieldset class="form-group">
                                    <label for="logo"><?php echo $this->lang->line('xin_e_details_document_file');?><i class="hris-asterisk">*</i></label>
                                    <input type="file" class="form-control-file" id="p_file2" name="document_file">
                                    <small><?php echo $this->lang->line('xin_e_details_d_type_file');?></small>
                                  </fieldset>
                                </div>
                              </div>
                            </div>
                            <div class="row">
                              <div class="col-md-6">
                                <div class="form-group">
                                  <label for="eligible_review_date" class="control-label"><?php echo $this->lang->line('xin_eligible_review_date');?></label>
                                  <input class="form-control date" readonly="readonly" placeholder="<?php echo $this->lang->line('xin_eligible_review_date');?>" name="eligible_review_date" type="text">
                                </div>
                              </div>
                              <div class="col-md-6">
                                <div class="form-group">
                                  <label for="send_mail"><?php echo $this->lang->line('xin_country');?></label>
                                  <select class="form-control" name="country" data-plugin="select_hrm" data-placeholder="<?php echo $this->lang->line('xin_country');?>">
                                    <option value=""><?php echo $this->lang->line('xin_select_one');?></option>
                                    <?php foreach($all_countries as $scountry) {?>
                                    <option value="<?php echo $scountry->country_id;?>"> <?php echo $scountry->country_name;?></option>
                                    <?php } ?>
                                  </select>
                                </div>
                              </div>
                            </div>
                            <div class="row">
                              <div class="col-md-12">
                                <div class="form-group">
                                  <div class="form-actions box-footer"> <?php echo form_button(array('name' => 'hris_form', 'type' => 'submit', 'class' => $this->Core_model->form_button_class(), 'content' => '<i class="fa fa fa-check-square-o"></i> '.$this->lang->line('xin_save'))); ?> </div>
                                </div>
                              </div>
                            </div>
                            <?php echo form_close(); ?> </div>
                          
                        </div>
                        <div class="tab-pane current-tab <?php echo $get_animate;?>" id="contacts" style="display:none;">
                          
                          <div class="box-header with-border">
                            <h3 class="box-title"> <?php echo $this->lang->line('xin_add_new');?> <?php echo $this->lang->line('xin_e_details_contact');?> </h3>
                          </div>
                          <div class="box-body pb-2">
                            <?php $attributes = array('name' => 'contact_info', 'id' => 'contact_info', 'autocomplete' => 'off');?>
                            <?php $hidden = array('u_basic_info' => 'ADD');?>
                            <?php echo form_open('admin/employees/contact_info', $attributes, $hidden);?>
                            <?php
                              $data_usr1 = array(
                                'type'  => 'hidden',
                                'name'  => 'user_id',
                                'id'    => 'user_id',
                                'value' => $user_id,
                             );
                            echo form_input($data_usr1);
                            ?>
                            <div class="row">
                              <div class="col-md-5">
                                <div class="form-group">
                                  <label for="relation"><?php echo $this->lang->line('xin_e_details_relation');?><i class="hris-asterisk">*</i></label>
                                  <select class="form-control" name="relation" data-plugin="select_hrm" data-placeholder="<?php echo $this->lang->line('xin_select_one');?>">
                                    <option value=""><?php echo $this->lang->line('xin_select_one');?></option>
                                    <option value="Self"><?php echo $this->lang->line('xin_self');?></option>
                                    <option value="Parent"><?php echo $this->lang->line('xin_parent');?></option>
                                    <option value="Spouse"><?php echo $this->lang->line('xin_spouse');?></option>
                                    <option value="Child"><?php echo $this->lang->line('xin_child');?></option>
                                    <option value="Sibling"><?php echo $this->lang->line('xin_sibling');?></option>
                                    <option value="In Laws"><?php echo $this->lang->line('xin_in_laws');?></option>
                                  </select>
                                </div>
                              </div>
                              <div class="col-md-7">
                                <div class="form-group">
                                  <label for="work_email" class="control-label"><?php echo $this->lang->line('dashboard_email');?><i class="hris-asterisk">*</i></label>
                                  <input class="form-control" placeholder="<?php echo $this->lang->line('xin_e_details_work');?>" name="work_email" type="text">
                                </div>
                              </div>
                            </div>
                            <div class="row">
                              <div class="col-md-5">
                                <div class="form-group">
                                  <label>
                                    <input type="checkbox" class="minimal" value="1" id="is_primary" name="is_primary">
                                    <?php echo $this->lang->line('xin_e_details_pcontact');?></span> </label>
                                  &nbsp;
                                  <label>
                                    <input type="checkbox" class="minimal" value="1" id="is_dependent" name="is_dependent">
                                    <?php echo $this->lang->line('xin_e_details_dependent');?></span> </label>
                                </div>
                              </div>
                              <div class="col-md-7">
                                <div class="form-group">
                                  <input class="form-control" placeholder="<?php echo $this->lang->line('xin_e_details_personal');?>" name="personal_email" type="text">
                                </div>
                              </div>
                            </div>
                            <div class="row">
                              <div class="col-md-5">
                                <div class="form-group">
                                  <label for="name" class="control-label"><?php echo $this->lang->line('xin_name');?><i class="hris-asterisk">*</i></label>
                                  <input class="form-control" placeholder="<?php echo $this->lang->line('xin_name');?>" name="contact_name" type="text">
                                </div>
                              </div>
                              <div class="col-md-7">
                                <div class="form-group" id="designation_ajax">
                                  <label for="address_1" class="control-label"><?php echo $this->lang->line('xin_address');?></label>
                                  <input class="form-control" placeholder="<?php echo $this->lang->line('xin_address_1');?>" name="address_1" type="text">
                                </div>
                              </div>
                            </div>
                            <div class="row">
                              <div class="col-md-5">
                                <div class="form-group">
                                  <label for="work_phone"><?php echo $this->lang->line('xin_phone');?><i class="hris-asterisk">*</i></label>
                                  <div class="row">
                                    <div class="col-md-8">
                                      <input class="form-control" placeholder="<?php echo $this->lang->line('xin_e_details_work');?>" name="work_phone" type="text">
                                    </div>
                                    <div class="col-md-4">
                                      <input class="form-control" placeholder="<?php echo $this->lang->line('xin_e_details_phone_ext');?>" name="work_phone_extension" type="text">
                                    </div>
                                  </div>
                                </div>
                              </div>
                              <div class="col-md-7">
                                <div class="form-group">
                                  <input class="form-control" placeholder="<?php echo $this->lang->line('xin_address_2');?>" name="address_2" type="text">
                                </div>
                              </div>
                            </div>
                            <div class="row">
                              <div class="col-md-5">
                                <div class="form-group">
                                  <input class="form-control" placeholder="<?php echo $this->lang->line('xin_e_details_mobile');?>" name="mobile_phone" type="text">
                                </div>
                              </div>
                              <div class="col-md-7">
                                <div class="form-group">
                                  <div class="row">
                                    <div class="col-md-5">
                                      <input class="form-control" placeholder="<?php echo $this->lang->line('xin_city');?>" name="city" type="text">
                                    </div>
                                    <div class="col-md-4">
                                      <input class="form-control" placeholder="<?php echo $this->lang->line('xin_state');?>" name="state" type="text">
                                    </div>
                                    <div class="col-md-3">
                                      <input class="form-control" placeholder="<?php echo $this->lang->line('xin_zipcode');?>" name="zipcode" type="text">
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div>
                            <div class="row">
                              <div class="col-md-5">
                                <div class="form-group">
                                  <input class="form-control" placeholder="<?php echo $this->lang->line('xin_e_details_home');?>" name="home_phone" type="text">
                                </div>
                              </div>
                              <div class="col-md-7">
                                <div class="form-group">
                                  <select name="country" id="select2-demo-6" class="form-control" data-plugin="select_hrm" data-placeholder="<?php echo $this->lang->line('xin_country');?>">
                                    <option value=""></option>
                                    <?php foreach($all_countries as $country) {?>
                                    <option value="<?php echo $country->country_id;?>"> <?php echo $country->country_name;?></option>
                                    <?php } ?>
                                  </select>
                                </div>
                              </div>
                            </div>
                            <div class="form-actions box-footer"> 
                              <?php echo form_button(array('name' => 'hris_form', 'type' => 'submit', 'class' => $this->Core_model->form_button_class(), 'content' => '<i class="fa fa fa-check-square-o"></i> '.$this->lang->line('xin_save'))); ?> 
                            </div>
                            <?php echo form_close(); ?> 
                          </div>
                          <div class="box">
                            <div class="box-header with-border">
                              <h3 class="box-title"> <?php echo $this->lang->line('xin_list_all');?> <?php echo $this->lang->line('xin_e_details_contacts');?> </h3>
                            </div>
                            <div class="box-body">
                              <div class="box-datatable table-responsive">
                                <table class="table table-striped table-bordered dataTable" id="xin_table_contact" style="width:100%;">
                                  <thead>
                                    <tr>
                                      <th><?php echo $this->lang->line('xin_action');?></th>
                                      <th><?php echo $this->lang->line('xin_employees_full_name');?></th>
                                      <th><?php echo $this->lang->line('xin_e_details_relation');?></th>
                                      <th><?php echo $this->lang->line('dashboard_email');?></th>
                                      <th><?php echo $this->lang->line('xin_e_details_mobile');?></th>
                                    </tr>
                                  </thead>
                                </table>
                              </div>
                            </div>
                          </div>
                        </div>
                        <div class="tab-pane current-tab <?php echo $get_animate;?>" id="documents" style="display:none;">
                          
                          <div class="box-header with-border">
                            <h3 class="box-title"> <?php echo $this->lang->line('xin_add_new');?> <?php echo $this->lang->line('xin_e_details_document');?> </h3>
                          </div>
                          <div class="box-body pb-2">
                            <?php $attributes = array('name' => 'document_info', 'id' => 'document_info', 'autocomplete' => 'off');?>
                            <?php $hidden = array('u_document_info' => 'UPDATE');?>
                            <?php echo form_open_multipart('admin/employees/document_info', $attributes, $hidden);?>
                            <?php
                              $data_usr2 = array(
                                'type'  => 'hidden',
                                'name'  => 'user_id',
                                'value' => $user_id,
                             );
                            echo form_input($data_usr2);
                            ?>
                            <div class="row">
                              <div class="col-md-6">
                                <div class="form-group">
                                  <label for="relation"><?php echo $this->lang->line('xin_e_details_dtype');?><i class="hris-asterisk">*</i></label>
                                  <select name="document_type_id" id="document_type_id" class="form-control" data-plugin="select_hrm" data-placeholder="<?php echo $this->lang->line('xin_e_details_choose_dtype');?>">
                                    <option value=""></option>
                                    <?php foreach($all_document_types as $document_type) {?>
                                    <option value="<?php echo $document_type->document_type_id;?>"> <?php echo $document_type->document_type;?></option>
                                    <?php } ?>
                                  </select>
                                </div>
                              </div>
                              <div class="col-md-6">
                                <div class="form-group">
                                  <label for="date_of_expiry" class="control-label"><?php echo $this->lang->line('xin_e_details_doe');?><i class="hris-asterisk">*</i></label>
                                  <input class="form-control date" readonly placeholder="<?php echo $this->lang->line('xin_e_details_doe');?>" name="date_of_expiry" type="text">
                                </div>
                              </div>
                            </div>
                            <div class="row">
                              <div class="col-md-12">
                                <div class="form-group">
                                  <label for="title" class="control-label"><?php echo $this->lang->line('xin_e_details_dtitle');?><i class="hris-asterisk">*</i></label>
                                  <input class="form-control" placeholder="<?php echo $this->lang->line('xin_e_details_dtitle');?>" name="title" type="text">
                                </div>
                              </div>
                            </div>
                            <div class="row">
                              <div class="col-md-6">
                                <div class="form-group">
                                  <label for="description" class="control-label"><?php echo $this->lang->line('xin_description');?></label>
                                  <textarea class="form-control" placeholder="<?php echo $this->lang->line('xin_description');?>" data-show-counter="1" data-limit="300" name="description" cols="30" rows="3" id="d_description"></textarea>
                                </div>
                              </div>
                              <div class="col-md-6">
                                <div class="form-group">
                                  <fieldset class="form-group">
                                    <label for="logo"><?php echo $this->lang->line('xin_e_details_document_file');?></label>
                                    <input type="file" class="form-control-file" id="document_file" name="document_file">
                                    <small><?php echo $this->lang->line('xin_e_details_d_type_file');?></small>
                                  </fieldset>
                                </div>
                              </div>
                            </div>
                            <div class="row">
                              <div class="col-md-12">
                                <div class="form-group">
                                  <div class="form-actions"> <?php echo form_button(array('name' => 'hris_form', 'type' => 'submit', 'class' => $this->Core_model->form_button_class(), 'content' => '<i class="fa fa fa-check-square-o"></i> '.$this->lang->line('xin_save'))); ?> </div>
                                </div>
                              </div>
                            </div>
                            <?php echo form_close(); ?> 
                          </div>
                          <div class="box">
                            <div class="box-header with-border">
                              <h3 class="box-title"> <?php echo $this->lang->line('xin_list_all');?> <?php echo $this->lang->line('xin_e_details_documents');?> </h3>
                            </div>
                            <div class="box-body">
                              <div class="box-datatable table-responsive">
                                <table class="table table-striped table-bordered dataTable" id="xin_table_document" style="width:100%;">
                                  <thead>
                                    <tr>
                                      <th><?php echo $this->lang->line('xin_action');?></th>
                                      <th><?php echo $this->lang->line('xin_e_details_dtype');?></th>
                                      <th><?php echo $this->lang->line('dashboard_xin_title');?></th>
                                      <th><?php echo $this->lang->line('xin_e_details_doe');?></th>
                                    </tr>
                                  </thead>
                                </table>
                              </div>
                            </div>
                          </div>
                        </div>

                        <!-- Pendidikan -->
                        <div class="tab-pane current-tab <?php echo $get_animate;?>" id="qualification" style="display:none;">
                         
                          <div class="box-header with-border">
                            <h3 class="box-title"> <?php echo $this->lang->line('xin_add_new');?> <?php echo $this->lang->line('xin_e_details_qualification');?> </h3>
                          </div>
                          <div class="box-body pb-2">
                            <?php $attributes = array('name' => 'qualification_info', 'id' => 'qualification_info', 'autocomplete' => 'off');?>
                            <?php $hidden = array('u_basic_info' => 'UPDATE');?>
                            <?php echo form_open('admin/employees/qualification_info', $attributes, $hidden);?>
                            <?php
                              $data_usr3 = array(
                                'type'  => 'hidden',
                                'name'  => 'user_id',
                                'value' => $user_id,
                             );
                            echo form_input($data_usr3);
                            ?>
                            <div class="row">
                              <div class="col-md-6">
                                <div class="form-group">
                                  <label for="name"><?php echo $this->lang->line('xin_e_details_inst_name');?><i class="hris-asterisk">*</i></label>
                                  <input class="form-control" placeholder="<?php echo $this->lang->line('xin_e_details_inst_name');?>" name="name" type="text">
                                </div>
                              </div>
                              <div class="col-md-6">
                                <div class="form-group">
                                  <label for="education_level" class="control-label"><?php echo $this->lang->line('xin_e_details_edu_level');?></label>
                                  <select class="form-control" name="education_level" data-plugin="select_hrm" data-placeholder="<?php echo $this->lang->line('xin_e_details_edu_level');?>">
                                    <?php foreach($all_education_level as $education_level) {?>
                                    <option value="<?php echo $education_level->education_level_id?>"><?php echo $education_level->name?></option>
                                    <?php } ?>
                                  </select>
                                </div>
                              </div>
                            </div>
                            <div class="row">
                              <div class="col-md-12">
                                <div class="form-group">
                                  <label for="from_year" class="control-label"><?php echo $this->lang->line('xin_e_details_timeperiod');?><i class="hris-asterisk">*</i></label>
                                  <div class="row">
                                    <div class="col-md-6">
                                      <input class="form-control date" readonly="readonly" placeholder="<?php echo $this->lang->line('xin_e_details_from');?>" name="from_year" type="text">
                                    </div>
                                    <div class="col-md-6">
                                      <input class="form-control date" readonly="readonly" placeholder="<?php echo $this->lang->line('dashboard_to');?>" name="to_year" type="text">
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div>
                            <div class="row">
                              <div class="col-md-6">
                                <div class="form-group">
                                  <label for="language" class="control-label"><?php echo $this->lang->line('xin_e_details_authority');?></label>
                                  <select class="form-control" name="language" data-plugin="select_hrm" data-placeholder="<?php echo $this->lang->line('xin_e_details_authority');?>">
                                    <?php foreach($all_qualification_language as $qualification_language) {?>
                                    <option value="<?php echo $qualification_language->language_id?>"><?php echo $qualification_language->name?></option>
                                    <?php } ?>
                                  </select>
                                </div>
                              </div>
                              <div class="col-md-6">
                                <div class="form-group">
                                  <label for="skill" class="control-label"><?php echo $this->lang->line('xin_e_details_skill');?></label>
                                  <select class="form-control" name="skill" data-plugin="select_hrm" data-placeholder="<?php echo $this->lang->line('xin_e_details_skill');?>">
                                    <option value=""></option>
                                    <?php foreach($all_qualification_skill as $qualification_skill) {?>
                                    <option value="<?php echo $qualification_skill->skill_id?>"><?php echo $qualification_skill->name?></option>
                                    <?php } ?>
                                  </select>
                                </div>
                              </div>
                            </div>
                            <div class="row">
                              <div class="col-md-12">
                                <div class="form-group">
                                  <label for="to_year" class="control-label"><?php echo $this->lang->line('xin_description');?></label>
                                  <textarea class="form-control" placeholder="<?php echo $this->lang->line('xin_description');?>" data-show-counter="1" data-limit="300" name="description" cols="30" rows="3" id="d_description"></textarea>
                                </div>
                              </div>
                            </div>
                            <div class="row">
                              <div class="col-md-12">
                                <div class="form-group">
                                  <div class="form-actions box-footer"> <?php echo form_button(array('name' => 'hris_form', 'type' => 'submit', 'class' => $this->Core_model->form_button_class(), 'content' => '<i class="fa fa fa-check-square-o"></i> '.$this->lang->line('xin_save'))); ?> </div>
                                </div>
                              </div>
                            </div>
                            <?php echo form_close(); ?> 
                          </div>
                          <div class="box">
                            <div class="box-header with-border">
                              <h3 class="box-title"> <?php echo $this->lang->line('xin_list_all');?> <?php echo $this->lang->line('xin_e_details_qualification');?> </h3>
                            </div>
                            <div class="box-body">
                              <div class="box-datatable table-responsive">
                                <table class="table table-striped table-bordered dataTable" id="xin_table_qualification" style="width:100%;">
                                  <thead>
                                    <tr>
                                      <th><?php echo $this->lang->line('xin_action');?></th>
                                      <th><?php echo $this->lang->line('xin_e_details_inst_name');?></th>
                                      <th><?php echo $this->lang->line('xin_e_details_timeperiod');?></th>
                                      <th><?php echo $this->lang->line('xin_e_details_edu_level');?></th>
                                    </tr>
                                  </thead>
                                </table>
                              </div>
                            </div>
                          </div>
                        </div>
                        <div class="tab-pane current-tab <?php echo $get_animate;?>" id="work-experience" style="display:none;">
                         
                          <div class="box-header with-border">
                            <h3 class="box-title"> <?php echo $this->lang->line('xin_add_new');?> <?php echo $this->lang->line('xin_e_details_w_experience');?> </h3>
                          </div>
                          <div class="box-body pb-2">
                            <?php $attributes = array('name' => 'work_experience_info', 'id' => 'work_experience_info', 'autocomplete' => 'off');?>
                            <?php $hidden = array('u_basic_info' => 'UPDATE');?>
                            <?php echo form_open('admin/employees/work_experience_info', $attributes, $hidden);?>
                            <?php
                              $data_usr4 = array(
                                'type'  => 'hidden',
                                'name'  => 'user_id',
                                'value' => $user_id,
                             );
                            echo form_input($data_usr4);
                            ?>
                            <div class="row">
                              <div class="col-md-6">
                                <div class="form-group">
                                  <label for="company_name"><?php echo $this->lang->line('xin_company_name');?><i class="hris-asterisk">*</i></label>
                                  <input class="form-control" placeholder="<?php echo $this->lang->line('xin_company_name');?>" name="company_name" type="text" value="" id="company_name">
                                </div>
                              </div>
                              <div class="col-md-6">
                                <div class="form-group">
                                  <label for="post"><?php echo $this->lang->line('xin_e_details_post');?><i class="hris-asterisk">*</i></label>
                                  <input class="form-control" placeholder="<?php echo $this->lang->line('xin_e_details_post');?>" name="post" type="text" value="" id="post">
                                </div>
                              </div>
                            </div>
                            <div class="row">
                              <div class="col-md-12">
                                <div class="form-group">
                                  <label for="from_year" class="control-label"><?php echo $this->lang->line('xin_e_details_timeperiod');?><i class="hris-asterisk">*</i></label>
                                  <div class="row">
                                    <div class="col-md-6">
                                      <input class="form-control date" readonly="readonly" placeholder="<?php echo $this->lang->line('xin_e_details_from');?>" name="from_date" type="text">
                                    </div>
                                    <div class="col-md-6">
                                      <input class="form-control date" readonly="readonly" placeholder="<?php echo $this->lang->line('dashboard_to');?>" name="to_date" type="text">
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div>
                            <div class="row">
                              <div class="col-md-12">
                                <div class="form-group">
                                  <label for="description"><?php echo $this->lang->line('xin_description');?></label>
                                  <textarea class="form-control" placeholder="<?php echo $this->lang->line('xin_description');?>" data-show-counter="1" data-limit="300" name="description" cols="30" rows="4" id="description"></textarea>
                                  <span class="countdown"></span> </div>
                              </div>
                            </div>
                            <div class="row">
                              <div class="col-md-12">
                                <div class="form-group">
                                  <div class="form-actions box-footer"> <?php echo form_button(array('name' => 'hris_form', 'type' => 'submit', 'class' => $this->Core_model->form_button_class(), 'content' => '<i class="fa fa fa-check-square-o"></i> '.$this->lang->line('xin_save'))); ?> </div>
                                </div>
                              </div>
                            </div>
                            <?php echo form_close(); ?> 
                          </div>
                           <div class="box">
                            <div class="box-header with-border">
                              <h3 class="box-title"> <?php echo $this->lang->line('xin_list_all');?> <?php echo $this->lang->line('xin_e_details_w_experience');?> </h3>
                            </div>
                            <div class="box-body">
                              <div class="box-datatable table-responsive">
                                <table class="table table-striped table-bordered dataTable" id="xin_table_work_experience" style="width:100%;">
                                  <thead>
                                    <tr>
                                      <th><?php echo $this->lang->line('xin_action');?></th>
                                      <th><?php echo $this->lang->line('xin_company_name');?></th>
                                      <th><?php echo $this->lang->line('xin_e_details_frm_date');?></th>
                                      <th><?php echo $this->lang->line('xin_e_details_to_date');?></th>
                                      <th><?php echo $this->lang->line('xin_e_details_post');?></th>
                                      <th><?php echo $this->lang->line('xin_description');?></th>
                                    </tr>
                                  </thead>
                                </table>
                              </div>
                            </div>
                          </div>
                        </div>
                        <div class="tab-pane current-tab <?php echo $get_animate;?>" id="bank-account" style="display:none;">
                          <div class="box">
                            <div class="box-header with-border">
                              <h3 class="box-title"> <?php echo $this->lang->line('xin_list_all');?> <?php echo $this->lang->line('xin_e_details_baccount');?> </h3>
                            </div>
                            <div class="box-body">
                              <div class="box-datatable table-responsive">
                                <table class="table table-striped table-bordered dataTable" id="xin_table_bank_account" style="width:100%;">
                                  <thead>
                                    <tr>
                                      <th><?php echo $this->lang->line('xin_action');?></th>
                                      <th><?php echo $this->lang->line('xin_e_details_acc_title');?></th>
                                      <th><?php echo $this->lang->line('xin_e_details_acc_number');?></th>
                                      <th><?php echo $this->lang->line('xin_e_details_bank_name');?></th>
                                      <th><?php echo $this->lang->line('xin_e_details_bank_code');?></th>
                                      <th><?php echo $this->lang->line('xin_e_details_bank_branch');?></th>
                                    </tr>
                                  </thead>
                                </table>
                              </div>
                            </div>
                          </div>
                          <div class="box-header with-border">
                            <h3 class="box-title"> <?php echo $this->lang->line('xin_add_new');?> <?php echo $this->lang->line('xin_e_details_baccount');?> </h3>
                          </div>
                          <div class="box-body pb-2">
                            <?php $attributes = array('name' => 'bank_account_info', 'id' => 'bank_account_info', 'autocomplete' => 'off');?>
                            <?php $hidden = array('u_basic_info' => 'UPDATE');?>
                            <?php echo form_open('admin/employees/bank_account_info', $attributes, $hidden);?>
                            <?php
                  $data_usr4 = array(
                    'type'  => 'hidden',
                    'name'  => 'user_id',
                    'value' => $user_id,
                 );
                echo form_input($data_usr4);
                ?>
                            <div class="row">
                              <div class="col-md-6">
                                <div class="form-group">
                                  <label for="account_title"><?php echo $this->lang->line('xin_e_details_acc_title');?><i class="hris-asterisk">*</i></label>
                                  <input class="form-control" placeholder="<?php echo $this->lang->line('xin_e_details_acc_title');?>" name="account_title" type="text" value="" id="account_name">
                                </div>
                              </div>
                              <div class="col-md-6">
                                <div class="form-group">
                                  <label for="account_number"><?php echo $this->lang->line('xin_e_details_acc_number');?><i class="hris-asterisk">*</i></label>
                                  <input class="form-control" placeholder="<?php echo $this->lang->line('xin_e_details_acc_number');?>" name="account_number" type="text" value="" id="account_number">
                                </div>
                              </div>
                            </div>
                            <div class="row">
                              <div class="col-md-6">
                                <div class="form-group">
                                  <label for="bank_name"><?php echo $this->lang->line('xin_e_details_bank_name');?><i class="hris-asterisk">*</i></label>
                                  <input class="form-control" placeholder="<?php echo $this->lang->line('xin_e_details_bank_name');?>" name="bank_name" type="text" value="" id="bank_name">
                                </div>
                              </div>
                              <div class="col-md-6">
                                <div class="form-group">
                                  <label for="bank_code"><?php echo $this->lang->line('xin_e_details_bank_code');?><i class="hris-asterisk">*</i></label>
                                  <input class="form-control" placeholder="<?php echo $this->lang->line('xin_e_details_bank_code');?>" name="bank_code" type="text" value="" id="bank_code">
                                </div>
                              </div>
                            </div>
                            <div class="row">
                              <div class="col-md-12">
                                <div class="form-group">
                                  <label for="bank_branch"><?php echo $this->lang->line('xin_e_details_bank_branch');?></label>
                                  <input class="form-control" placeholder="<?php echo $this->lang->line('xin_e_details_bank_branch');?>" name="bank_branch" type="text" value="" id="bank_branch">
                                </div>
                              </div>
                            </div>
                            <div class="row">
                              <div class="col-md-12">
                                <div class="form-group">
                                  <div class="form-actions box-footer"> <?php echo form_button(array('name' => 'hris_form', 'type' => 'submit', 'class' => $this->Core_model->form_button_class(), 'content' => '<i class="fa fa fa-check-square-o"></i> '.$this->lang->line('xin_save'))); ?> </div>
                                </div>
                              </div>
                            </div>
                            <?php echo form_close(); ?> </div>
                          
                        </div>
                        <div class="tab-pane current-tab <?php echo $get_animate;?>" id="social-networking" style="display:none;">
                          <div class="box-header with-border">
                            <h3 class="box-title"> <?php echo $this->lang->line('xin_e_details_social');?> </h3>
                          </div>
                          <div class="box-body pb-2">
                            <?php $attributes = array('name' => 'social_networking', 'id' => 'f_social_networking', 'autocomplete' => 'off');?>
                            <?php $hidden = array('user_id' => $user_id, 'u_basic_info' => 'UPDATE');?>
                            <?php echo form_open('admin/employees/social_info', $attributes, $hidden);?>
                            <div class="bg-white">
                              <div class="row">
                                <div class="col-md-6">
                                  <div class="form-group">
                                    <label for="facebook_profile"><?php echo $this->lang->line('xin_e_details_fb_profile');?></label>
                                    <input class="form-control" placeholder="<?php echo $this->lang->line('xin_e_details_fb_profile');?>" name="facebook_link" type="text" value="<?php echo $facebook_link;?>">
                                  </div>
                                </div>
                                <div class="col-md-6">
                                  <div class="form-group">
                                    <label for="facebook_profile"><?php echo $this->lang->line('xin_e_details_twit_profile');?></label>
                                    <input class="form-control" placeholder="<?php echo $this->lang->line('xin_e_details_twit_profile');?>" name="twitter_link" type="text" value="<?php echo $twitter_link;?>">
                                  </div>
                                </div>
                              </div>
                              <div class="row">
                                <div class="col-md-12">
                                  <div class="form-group">
                                    <label for="twitter_profile"><?php echo $this->lang->line('xin_e_details_blogr_profile');?></label>
                                    <input class="form-control" placeholder="<?php echo $this->lang->line('xin_e_details_blogr_profile');?>" name="blogger_link" type="text" value="<?php echo $blogger_link;?>">
                                  </div>
                                </div>
                              </div>
                              <div class="row">
                                <div class="col-md-6">
                                  <div class="form-group">
                                    <label for="blogger_profile"><?php echo $this->lang->line('xin_e_details_linkd_profile');?></label>
                                    <input class="form-control" placeholder="<?php echo $this->lang->line('xin_e_details_linkd_profile');?>" name="linkdedin_link" type="text" value="<?php echo $linkdedin_link;?>">
                                  </div>
                                </div>
                                <div class="col-md-6">
                                  <div class="form-group">
                                    <label for="blogger_profile"><?php echo $this->lang->line('xin_e_details_gplus_profile');?></label>
                                    <input class="form-control" placeholder="<?php echo $this->lang->line('xin_e_details_gplus_profile');?>" name="google_plus_link" type="text" value="<?php echo $google_plus_link;?>">
                                  </div>
                                </div>
                              </div>
                              <div class="row">
                                <div class="col-md-6">
                                  <div class="form-group">
                                    <label for="linkdedin_profile"><?php echo $this->lang->line('xin_e_details_insta_profile');?></label>
                                    <input class="form-control" placeholder="<?php echo $this->lang->line('xin_e_details_insta_profile');?>" name="instagram_link" type="text" value="<?php echo $instagram_link;?>">
                                  </div>
                                </div>
                                <div class="col-md-6">
                                  <div class="form-group">
                                    <label for="linkdedin_profile"><?php echo $this->lang->line('xin_e_details_pintrst_profile');?></label>
                                    <input class="form-control" placeholder="<?php echo $this->lang->line('xin_e_details_pintrst_profile');?>" name="pinterest_link" type="text" value="<?php echo $pinterest_link;?>">
                                  </div>
                                </div>
                              </div>
                              <div class="row">
                                <div class="col-md-12">
                                  <div class="form-group">
                                    <label for="linkdedin_profile"><?php echo $this->lang->line('xin_e_details_utube_profile');?></label>
                                    <input class="form-control" placeholder="<?php echo $this->lang->line('xin_e_details_utube_profile');?>" name="youtube_link" type="text" value="<?php echo $youtube_link;?>">
                                  </div>
                                </div>
                              </div>
                              <div class="form-actions box-footer"> <?php echo form_button(array('name' => 'hris_form', 'type' => 'submit', 'class' => $this->Core_model->form_button_class(), 'content' => '<i class="fa fa fa-check-square-o"></i> '.$this->lang->line('xin_save'))); ?> </div>
                            </div>
                            <?php echo form_close(); ?> </div>
                        </div>
                        <div class="tab-pane current-tab <?php echo $get_animate;?>" id="change-password" style="display:none;">
                          <div class="box-header with-border">
                            <h3 class="box-title"> <?php echo $this->lang->line('header_change_password');?> </h3>
                          </div>
                          <div class="box-body pb-2">
                            <?php $attributes = array('name' => 'e_change_password', 'id' => 'e_change_password', 'autocomplete' => 'off');?>
                            <?php $hidden = array('u_basic_info' => 'UPDATE');?>
                            <?php echo form_open('admin/employees/change_password', $attributes, $hidden);?>
                            <?php
                  $data_usr5 = array(
                    'type'  => 'hidden',
                    'name'  => 'user_id',
                    'value' => $user_id,
                 );
                echo form_input($data_usr5);
                ?>
                          <div class="row">
                            <div class="col-md-4">
                              <div class="form-group">
                                <label for="old_password"><?php echo $this->lang->line('xin_old_password');?></label>
                                <input class="form-control" placeholder="<?php echo $this->lang->line('xin_old_password');?>" name="old_password" type="password">
                              </div>
                              
                              <div class="form-group">
                                  <label for="new_password"><?php echo $this->lang->line('xin_e_details_enpassword');?><i class="hris-asterisk">*</i></label>
                                  <input class="form-control" placeholder="<?php echo $this->lang->line('xin_e_details_enpassword');?>" name="new_password" type="password">
                              </div>

                              <div class="form-group">
                                  <label for="new_password_confirm" class="control-label"><?php echo $this->lang->line('xin_e_details_ecnpassword');?><i class="hris-asterisk">*</i></label>
                                  <input class="form-control" placeholder="<?php echo $this->lang->line('xin_e_details_ecnpassword');?>" name="new_password_confirm" type="password">
                              </div>

                            </div>
                           
                          </div>
                            
                            <hr>

                            <div class="row">
                              <div class="col-md-12">
                                <!-- <div class=""> -->
                                <div class="form-group" style="float:left;">
                                  <div class="form-actions"> <?php echo form_button(array('name' => 'hris_form', 'type' => 'submit', 'class' => $this->Core_model->form_button_class(), 'content' => '<i class="fa fa fa-check-square-o"></i> '.$this->lang->line('xin_save'))); ?> </div>
                                </div>
                              <!-- </div> -->
                              </div>
                            </div>
                            <?php echo form_close(); ?> </div>
                        </div>
                        
                        <div class="tab-pane current-tab <?php echo $get_animate;?>" id="security_level" style="display:none;">
                          <div class="box">
                            <div class="box-header with-border">
                              <h3 class="box-title"> <?php echo $this->lang->line('xin_list_all');?> <?php echo $this->lang->line('xin_esecurity_level_title');?> </h3>
                            </div>
                            <div class="box-body">
                              <div class="box-datatable table-responsive">
                                <table class="table table-striped table-bordered dataTable" id="xin_table_security_level" style="width:100%;">
                                  <thead>
                                    <tr>
                                      <th><?php echo $this->lang->line('xin_action');?></th>
                                      <th><?php echo $this->lang->line('xin_esecurity_level_title');?></th>
                                      <th><?php echo $this->lang->line('xin_e_details_doe');?></th>
                                      <th><?php echo $this->lang->line('xin_e_details_do_clearance');?></th>
                                    </tr>
                                  </thead>
                                </table>
                              </div>
                            </div>
                          </div>
                          <div class="box-header with-border">
                            <h3 class="box-title"> <?php echo $this->lang->line('xin_add_new');?> <?php echo $this->lang->line('xin_esecurity_level_title');?> </h3>
                          </div>
                          <div class="box-body pb-2">
                            <?php $attributes = array('name' => 'security_level_info', 'id' => 'security_level_info', 'autocomplete' => 'off');?>
                            <?php $hidden = array('u_basic_info' => 'UPDATE');?>
                            <?php echo form_open('admin/employees/add_security_level', $attributes, $hidden);?>
                            <?php
                              $data_usr4 = array(
                              'type'  => 'hidden',
                              'name'  => 'user_id',
                              'value' => $user_id,
                             );
                            echo form_input($data_usr4);
                            ?>
                            <?php $security_level_list = $this->Core_model->get_security_level_type();?>
                            <div class="row">
                              <div class="col-md-4">
                                <div class="form-group">
                                  <label for="account_title"><?php echo $this->lang->line('xin_esecurity_level_title');?><i class="hris-asterisk">*</i></label>
                                  <select class="form-control" name="security_level" data-plugin="select_hrm" data-placeholder="<?php echo $this->lang->line('xin_esecurity_level_title');?>">
                                    <option value=""><?php echo $this->lang->line('xin_esecurity_level_title');?></option>
                                        <?php foreach($security_level_list->result() as $sc_level) {?>
                                            <option value="<?php echo $sc_level->type_id?>"><?php echo $sc_level->name?></option>
                                        <?php } ?>
                                  </select>
                                </div>
                              </div>
                              <div class="col-md-4">
                                <div class="form-group">
                                  <label for="account_number"><?php echo $this->lang->line('xin_e_details_doe');?></label>
                                  <input class="form-control date" placeholder="<?php echo $this->lang->line('xin_e_details_doe');?>" name="expiry_date" type="text" value="" id="expiry_date">
                                </div>
                              </div>
                              <div class="col-md-4">
                                <div class="form-group">
                                  <label for="account_number"><?php echo $this->lang->line('xin_e_details_do_clearance');?></label>
                                  <input class="form-control date" placeholder="<?php echo $this->lang->line('xin_e_details_do_clearance');?>" name="date_of_clearance" type="text" value="" id="date_of_clearance">
                                </div>
                              </div>
                            </div>
                            <div class="row">
                              <div class="col-md-12">
                                <div class="form-group">
                                  <div class="form-actions box-footer"> <?php echo form_button(array('name' => 'hris_form', 'type' => 'submit', 'class' => $this->Core_model->form_button_class(), 'content' => '<i class="fa fa fa-check-square-o"></i> '.$this->lang->line('xin_save'))); ?> </div>
                                </div>
                              </div>
                            </div>
                            <?php echo form_close(); ?> </div>
                          
                        </div>
                        <div class="tab-pane current-tab <?php echo $get_animate;?>" id="contract" style="display:none;">
                          
                          
                          <div class="box-header with-border">
                            <h3 class="box-title"> <?php echo $this->lang->line('xin_add_new');?> <?php echo $this->lang->line('xin_e_details_contract');?> </h3>
                          </div>

                          <div class="box-body pb-2">
                            <?php $attributes = array('name' => 'contract_info', 'id' => 'contract_info', 'autocomplete' => 'off');?>
                            <?php $hidden = array('u_basic_info' => 'UPDATE');?>
                            <?php echo form_open('admin/employees/contract_info', $attributes, $hidden);?>
                            <?php
                  $data_usr4 = array(
                  'type'  => 'hidden',
                  'name'  => 'user_id',
                  'value' => $user_id,
                 );
                echo form_input($data_usr4);
                ?>
                            <div class="col-md-6">
                              
                <div class="form-group">
                                <label for="company_id" class=""><?php echo $this->lang->line('left_company');?></label>
                                <select class="form-control" name="company_id" data-plugin="select_hrm" data-placeholder="<?php echo $this->lang->line('xin_select_one');?>">
                                  <option value=""><?php echo $this->lang->line('xin_select_one');?></option>
                                  <?php foreach($all_companies as $company) {?>
                                  <option value="<?php echo $company->company_id;?>"> <?php echo $company->name;?></option>
                                  <?php } ?>
                                </select>
                              </div>
                
                <div class="form-group">
                                <label class="" for="from_date"><?php echo $this->lang->line('xin_e_details_frm_date');?></label>
                                <input type="text" class="form-control cont_date" name="from_date" placeholder="<?php echo $this->lang->line('xin_e_details_frm_date');?>" readonly value="">
                              </div>
                
                              <div class="form-group">
                                <label for="contract_type_id" class=""><?php echo $this->lang->line('xin_e_details_contract_type');?></label>
                                <select class="form-control" name="contract_type_id" data-plugin="select_hrm" data-placeholder="<?php echo $this->lang->line('xin_select_one');?>">
                                  <option value=""><?php echo $this->lang->line('xin_select_one');?></option>
                                  <?php foreach($all_contract_types as $contract_type) {?>
                                  <option value="<?php echo $contract_type->contract_type_id;?>"> <?php echo $contract_type->name;?></option>
                                  <?php } ?>
                                </select>
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
                            </div>
              
                            <div class="col-md-6">
                              <div class="form-group">
                                <label for="title" class=""><?php echo $this->lang->line('xin_e_details_contract_title');?></label>
                                <input class="form-control" placeholder="<?php echo $this->lang->line('xin_e_details_contract_title');?>" name="title" type="text" value="" id="title">
                              </div>
                              <div class="form-group">
                                <label for="to_date"><?php echo $this->lang->line('xin_e_details_to_date');?></label>
                                <input type="text" class="form-control cont_date" name="to_date" placeholder="<?php echo $this->lang->line('xin_e_details_to_date');?>" readonly value="">
                              </div>
                              <div class="form-group">
                                <label for="description"><?php echo $this->lang->line('xin_description');?></label>
                                <textarea class="form-control" placeholder="<?php echo $this->lang->line('xin_description');?>" data-show-counter="1" data-limit="300" name="description" cols="30" rows="3" id="description"></textarea>
                                <span class="countdown"></span> </div>
                            </div>
                            <div class="row">
                              <div class="col-md-12">
                                <div class="form-group">
                                  <div class="form-actions box-footer"> <?php echo form_button(array('name' => 'hris_form', 'type' => 'submit', 'class' => $this->Core_model->form_button_class(), 'content' => '<i class="fa fa fa-check-square-o"></i> '.$this->lang->line('xin_save'))); ?> </div>
                                </div>
                              </div>
                            </div>
                            <?php echo form_close(); ?> 
                          </div>
                          
                          <div class="box">
                            <div class="box-header with-border">
                              <h3 class="box-title"> <?php echo $this->lang->line('xin_list_all');?> <?php echo $this->lang->line('xin_e_details_contracts');?> </h3>
                            </div>
                            <div class="box-body">
                              <div class="box-datatable table-responsive">
                                <table class="table table-striped table-bordered dataTable" id="xin_table_contract" style="width:100%;">
                                  <thead>
                                    <tr>
                                      <th><?php echo $this->lang->line('xin_action');?></th>
                                      <th><?php echo $this->lang->line('xin_e_details_duration');?></th>
                                      <th><?php echo $this->lang->line('dashboard_designation');?></th>
                                      <th><?php echo $this->lang->line('xin_e_details_contract_type');?></th>
                                      <th><?php echo $this->lang->line('dashboard_xin_title');?></th>
                                    </tr>
                                  </thead>
                                </table>
                              </div>
                            </div>
                          </div>
                        </div>
                        
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <div class="tab-pane" id="xin_profile_picture"  >
              <div class="box-body">
                <div class="row no-gutters row-bordered row-border-light">
                  <div class="col-md-12">
                    <div class="tab-content">
                      <div class="tab-pane  <?php echo $get_animate;?> active" id="profile-picture">
                        <div class="box-body pb-2">
                          <?php $attributes = array('name' => 'profile_picture', 'id' => 'f_profile_picture', 'autocomplete' => 'off');?>
                          <?php $hidden = array('u_profile_picture' => 'UPDATE');?>
                          <?php echo form_open_multipart('admin/employees/profile_picture', $attributes, $hidden);?>
                          <?php
                  $data_usr = array(
                    'type'  => 'hidden',
                    'name'  => 'user_id',
                    'id'    => 'user_id',
                    'value' => $user_id,
                 );
                echo form_input($data_usr);
                ?>
                          <?php
                  $data_usr = array(
                    'type'  => 'hidden',
                    'name'  => 'session_id',
                    'id'    => 'session_id',
                    'value' => $session['user_id'],
                 );
                echo form_input($data_usr);
                ?>
                          <div class="bg-white">
                            <div class="row">
                              <div class="col-md-12">
                                <div class='form-group'>
                                  <fieldset class="form-group">
                                    <label for="logo"><?php echo $this->lang->line('xin_browse');?><i class="hris-asterisk">*</i></label>
                                    <input type="file" class="form-control-file" id="p_file" name="p_file">
                                    <small><?php echo $this->lang->line('xin_e_details_picture_type');?></small>
                                  </fieldset>
                                  <?php if($profile_picture!='' && $profile_picture!='no file') {?>
                                  <img src="<?php echo base_url().'uploads/profile/'.$profile_picture;?>" width="50px" style="margin-left:20px;" id="u_file">
                                  <?php } else {?>
                                  <?php if($gender=='Male') { ?>
                                  <?php $de_file = base_url().'uploads/profile/default_male.jpg';?>
                                  <?php } else { ?>
                                  <?php $de_file = base_url().'uploads/profile/default_female.jpg';?>
                                  <?php } ?>
                                  <img src="<?php echo $de_file;?>" width="50px" style="margin-left:20px;" id="u_file">
                                  <?php } ?>
                                  <?php if($profile_picture!='' && $profile_picture!='no file') {?>
                                  <br />
                                  <label>
                                    <input type="checkbox" class="minimal" value="1" id="remove_profile_picture" name="remove_profile_picture">
                                    <?php echo $this->lang->line('xin_e_details_remove_pic');?></span> </label>
                                  <?php } else {?>
                                  <div id="remove_file" style="display:none;">
                                    <label>
                                      <input type="checkbox" class="minimal" value="1" id="remove_profile_picture" name="remove_profile_picture">
                                      <?php echo $this->lang->line('xin_e_details_remove_pic');?></span> </label>
                                  </div>
                                  <?php } ?>
                                </div>
                              </div>
                            </div>
                            <div class="form-action box-footer"> <?php echo form_button(array('name' => 'hris_form', 'type' => 'submit', 'class' => $this->Core_model->form_button_class(), 'content' => '<i class="fa fa fa-check-square-o"></i> '.$this->lang->line('xin_save'))); ?> </div>
                          </div>
                          <?php echo form_close(); ?> </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <?php if(in_array('351',$role_resources_ids)) {?>
              <div class="tab-pane <?php echo $get_animate;?>" id="xin_employee_set_salary"  >
                <div class="card-body">
                  <div class="card overflow-hidden">
                    <div class="row no-gutters row-bordered row-border-light">
                      <div class="col-md-3 pt-0">
                        <div class="list-group list-group-flush account-settings-links"> 
                            
                            <div class="box">
                              <div class="box-header with-border" style="background-color: #e0f3e5;">
                                <h3 class="box-title"> <?php echo $this->lang->line('xin_penambah');?> </h3>
                              </div>
                              <div class="list-group">
                                  <div class="list-group list-group-flush account-settings-links"> 
                                    <a class="salary-tab-list list-group-item list-group-item-action active salary-tab" data-toggle="list" href="javascript:void(0);" data-profile="1" data-profile-block="salary" aria-expanded="true" id="suser_profile_1"><?php echo $this->lang->line('xin_employee_type_wages');?></a> 
                                    <a class="salary-tab-list list-group-item list-group-item-action salary-tab" data-toggle="list" href="javascript:void(0);" data-profile="2" data-profile-block="set_allowances" aria-expanded="true" id="suser_profile_2"><?php echo $this->lang->line('xin_employee_set_allowances');?></a> 
                                    <a class="salary-tab-list list-group-item list-group-item-action salary-tab" data-toggle="list" href="javascript:void(0);" data-profile="3" data-profile-block="commissions" aria-expanded="true" id="suser_profile_3"><?php echo $this->lang->line('xin_hr_commissions');?></a> 
                                     <a class="salary-tab-list list-group-item list-group-item-action salary-tab" data-toggle="list" href="javascript:void(0);" data-profile="7" data-profile-block="overtime" aria-expanded="true" id="suser_profile_7"><?php echo $this->lang->line('dashboard_overtime');?></a>                           
                                  </div>
                              </div>
                            </div>

                            <div class="box">
                              <div class="box-header with-border" style="background-color: #e0f3e5;">
                                <h3 class="box-title"> <?php echo $this->lang->line('xin_pengurang');?> </h3>
                              </div>
                              <div class="list-group">
                                  <div class="list-group list-group-flush account-settings-links"> 
                                    <a class="salary-tab-list list-group-item list-group-item-action salary-tab" data-toggle="list" href="javascript:void(0);" data-profile="4" data-profile-block="loan_deductions" aria-expanded="true" id="suser_profile_4"><?php echo $this->lang->line('xin_employee_set_loan_deductions');?></a> 
                                    <a class="salary-tab-list list-group-item list-group-item-action salary-tab" data-toggle="list" href="javascript:void(0);" data-profile="5" data-profile-block="statutory_deductions" aria-expanded="true" id="suser_profile_5"><?php echo $this->lang->line('xin_employee_set_statutory_deductions');?></a> 
                                    <a class="salary-tab-list list-group-item list-group-item-action salary-tab" data-toggle="list" href="javascript:void(0);" data-profile="6" data-profile-block="other_payment" aria-expanded="true" id="suser_profile_6"><?php echo $this->lang->line('xin_employee_set_other_payment');?></a>                         
                                  </div>
                              </div>
                            </div> 
                          
                          </div>
                      </div>
                      <div class="col-md-9">
                        <div class="tab-content active">
                          

                          <!-- Gaji Pokok -->
                          <div class="tab-pane <?php echo $get_animate;?> salary-current-tab active" id="salary">
                            <div class="box-header with-border">
                              <h3 class="box-title"> <?php echo $this->lang->line('xin_employee_type_wages');?> </h3>
                            </div>
                            <div class="box-body pb-2">
                              <?php $attributes = array('name' => 'employee_update_salary', 'id' => 'employee_update_salary', 'autocomplete' => 'off');?>
                              <?php $hidden = array('user_id' => $user_id, 'u_basic_info' => 'UPDATE');?>
                              <?php echo form_open('admin/employees/update_salary_option', $attributes, $hidden);?>
                              <div class="bg-white">
                                <div class="row">
                                  <div class="col-md-4">
                                    <div class="form-group">
                                      <label for="wages_type"><?php echo $this->lang->line('xin_employee_type_wages');?><i class="hris-asterisk">*</i></label>
                                      <select name="wages_type" id="wages_type" class="form-control" data-plugin="select_hrm">
                                        <option value="1" <?php if($wages_type==1):?> selected="selected"<?php endif;?>><?php echo $this->lang->line('xin_payroll_full_tTime');?></option>
                                        <option value="2" <?php if($wages_type==2):?> selected="selected"<?php endif;?>><?php echo $this->lang->line('xin_payroll_part_tTime');?></option>
                                        <option value="3" <?php if($wages_type==2):?> selected="selected"<?php endif;?>><?php echo $this->lang->line('xin_payroll_free_lance');?></option>
                                      </select>
                                    </div>
                                  </div>
                                                           
                                </div>
                                <div class="row">
                                 
                                  <div class="col-md-3">
                                    <div class="form-group">
                                    <label for="basic_salary"><?php echo $this->lang->line('xin_salary_title');?><i class="hris-asterisk">*</i></label>
                                      <input class="form-control basic_salary" placeholder="<?php echo $this->lang->line('xin_salary_title');?>" name="basic_salary" type="text" value="<?php echo $basic_salary;?>">
                                    </div>
                                  </div>                          
                                </div>
                                <div class="row">
                                <div class="col-md-12">
                                  <div class="form-group">
                                    <div class="form-actions box-footer"> <?php echo form_button(array('name' => 'hris_form', 'type' => 'submit', 'class' => $this->Core_model->form_button_class(), 'content' => '<i class="fa fa fa-check-square-o"></i> '.$this->lang->line('xin_save'))); ?> </div>
                                  </div>
                                </div>
                              </div>
                              </div>
                              <?php echo form_close(); ?> </div>
                          </div>

                          <!-- Tunjangan -->
                          <div class="tab-pane <?php echo $get_animate;?> salary-current-tab" id="set_allowances">
                            
                            <div class="box-header with-border">
                              <h3 class="box-title"> <?php echo $this->lang->line('xin_employee_set_allowances');?> </h3>
                            </div>
                            <div class="box-body pb-2">
                              <?php $attributes = array('name' => 'employee_update_allowance', 'id' => 'employee_update_allowance', 'autocomplete' => 'off');?>
                              <?php $hidden = array('u_basic_info' => 'UPDATE');?>
                              <?php echo form_open('admin/employees/employee_allowance_option', $attributes, $hidden);?>
                              <?php
                                $data_usr4 = array(
                                'type'  => 'hidden',
                                'name'  => 'user_id',
                                'value' => $user_id,
                               );
                              echo form_input($data_usr4);
                              ?>
                              <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                      <label for="is_allowance_taxable"><?php echo $this->lang->line('xin_salary_allowance_options');?><i class="hris-asterisk">*</i></label>
                                      <select name="is_allowance_taxable" id="is_allowance_taxable" class="form-control" data-plugin="select_hrm">
                                        <option value="0"><?php echo $this->lang->line('xin_salary_allowance_non_taxable');?></option>
                                        <option value="1"><?php echo $this->lang->line('xin_salary_allowance_taxable');?></option>
                                      </select>
                                    </div>
                                  </div>
                                  <div class="col-md-4">
                                  <div class="form-group">
                                    <label for="account_title"><?php echo $this->lang->line('dashboard_xin_title');?><i class="hris-asterisk">*</i></label>
                                    <input class="form-control" placeholder="<?php echo $this->lang->line('dashboard_xin_title');?>" name="allowance_title" type="text" value="" id="allowance_title">
                                  </div>
                                </div>
                                <div class="col-md-3">
                                  <div class="form-group">
                                    <label for="account_number"><?php echo $this->lang->line('xin_amount');?><i class="hris-asterisk">*</i></label>
                                    <input class="form-control" placeholder="<?php echo $this->lang->line('xin_amount');?>" name="allowance_amount" type="text" value="" id="allowance_amount">
                                  </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                    <div class="box-footer hris-salary-button"> <?php echo form_button(array('name' => 'hris_form', 'type' => 'submit', 'class' => $this->Core_model->form_button_class(), 'content' => '<i class="fa fa fa-check-square-o"></i> '.$this->lang->line('xin_save'))); ?> </div>
                                    </div>
                                  </div>
                              </div>
                              <div class="row">
                                <div class="col-md-12">
                                  <div class="form-group">
                                    <div class="form-actions box-footer"> &nbsp;</div>
                                  </div>
                                </div>
                              </div>
                              <?php echo form_close(); ?> 
                            </div>
                            <div class="box">
                              <div class="box-header with-border">
                                <h3 class="box-title"> <?php echo $this->lang->line('xin_list_all');?> <?php echo $this->lang->line('xin_employee_set_allowances');?> </h3>
                              </div>
                              <div class="box-body">
                                <div class="box-datatable table-responsive">
                                  <table class="table table-striped table-bordered dataTable" id="xin_table_all_allowances" style="width:100%;">
                                    <thead>
                                      <tr>
                                        <th><?php echo $this->lang->line('xin_action');?></th>
                                        <th><?php echo $this->lang->line('xin_salary_allowance_options');?></th>
                                        <th><?php echo $this->lang->line('dashboard_xin_title');?></th>
                                        <th><?php echo $this->lang->line('xin_amount');?></th>
                                      </tr>
                                    </thead>
                                  </table>
                                </div>
                              </div>
                            </div>
                          </div>

                          <!-- komisi -->
                          <div class="tab-pane <?php echo $get_animate;?> salary-current-tab" id="commissions" style="display:none;">
                            
                            <div class="box-header with-border">
                              <h3 class="box-title"> <?php echo $this->lang->line('xin_hr_commissions');?> </h3>
                            </div>
                            <div class="box-body pb-2">
                              <?php $attributes = array('name' => 'employee_update_commissions', 'id' => 'employee_update_commissions', 'autocomplete' => 'off');?>
                              <?php $hidden = array('u_basic_info' => 'UPDATE');?>
                              <?php echo form_open('admin/employees/employee_commissions_option', $attributes, $hidden);?>
                              <?php
                                $data_usr4 = array(
                                'type'  => 'hidden',
                                'name'  => 'user_id',
                                'value' => $user_id,
                               );
                              echo form_input($data_usr4);
                              ?>
                              <div class="row">
                                <div class="col-md-4">
                                  <div class="form-group">
                                    <label for="title"><?php echo $this->lang->line('dashboard_xin_title');?><i class="hris-asterisk">*</i></label>
                                    <input class="form-control" placeholder="<?php echo $this->lang->line('dashboard_xin_title');?>" name="title" type="text" value="" id="title">
                                  </div>
                                </div>
                                <div class="col-md-4">
                                  <div class="form-group">
                                    <label for="amount"><?php echo $this->lang->line('xin_amount');?><i class="hris-asterisk">*</i></label>
                                    <input class="form-control" placeholder="<?php echo $this->lang->line('xin_amount');?>" name="amount" type="text" value="" id="amount">
                                  </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                    <div class="box-footer hris-salary-button"> <?php echo form_button(array('name' => 'hris_form', 'type' => 'submit', 'class' => $this->Core_model->form_button_class(), 'content' => '<i class="fa fa fa-check-square-o"></i> '.$this->lang->line('xin_save'))); ?> </div>
                                    </div>
                                  </div>
                              </div>
                              <div class="row">
                                <div class="col-md-12">
                                  <div class="form-group">
                                    <div class="form-actions box-footer"> &nbsp;</div>
                                  </div>
                                </div>
                              </div>
                              <?php echo form_close(); ?> 
                            </div>
                            <div class="box">
                              <div class="box-header with-border">
                                <h3 class="box-title"> <?php echo $this->lang->line('xin_list_all');?> <?php echo $this->lang->line('xin_hr_commissions');?> </h3>
                              </div>
                              <div class="box-body">
                                <div class="box-datatable table-responsive">
                                  <table class="table table-striped table-bordered dataTable" id="xin_table_all_commissions" style="width:100%;">
                                    <thead>
                                      <tr>
                                        <th><?php echo $this->lang->line('xin_action');?></th>
                                        <th><?php echo $this->lang->line('dashboard_xin_title');?></th>
                                        <th><?php echo $this->lang->line('xin_amount');?></th>
                                      </tr>
                                    </thead>
                                  </table>
                                </div>
                              </div>
                            </div>
                          </div>
                          
                          <!-- loan -->
                          <div class="tab-pane <?php echo $get_animate;?> salary-current-tab" id="loan_deductions" style="display:none;">
                            
                            <div class="box-header with-border">
                              <h3 class="box-title"> <?php echo $this->lang->line('xin_employee_set_loan_deductions');?> </h3>
                            </div>
                            <div class="box-body pb-2">
                              <?php $attributes = array('name' => 'add_loan_info', 'id' => 'add_loan_info', 'autocomplete' => 'off');?>
                              <?php $hidden = array('u_basic_info' => 'UPDATE');?>
                              <?php echo form_open('admin/employees/employee_loan_info', $attributes, $hidden);?>
                              <?php
                                $data_usr4 = array(
                                  'type'  => 'hidden',
                                  'name'  => 'user_id',
                                  'value' => $user_id,
                               );
                              echo form_input($data_usr4);
                              ?>
                              <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                      <label for="loan_options"><?php echo $this->lang->line('xin_salary_loan_options');?><i class="hris-asterisk">*</i></label>
                                      <select name="loan_options" id="loan_options" class="form-control" data-plugin="select_hrm">
                                        <option value="1"><?php echo $this->lang->line('xin_loan_ssc_title');?></option>
                                        <option value="2"><?php echo $this->lang->line('xin_loan_hdmf_title');?></option>
                                        <option value="0"><?php echo $this->lang->line('xin_loan_other_sd_title');?></option>
                                      </select>
                                    </div>
                                  </div>
                                  <div class="col-md-4">
                                  <div class="form-group">
                                    <label for="month_year"><?php echo $this->lang->line('dashboard_xin_title');?><i class="hris-asterisk">*</i></label>
                                    <input class="form-control" placeholder="<?php echo $this->lang->line('dashboard_xin_title');?>" name="loan_deduction_title" type="text">
                                  </div>
                                </div>
                                <div class="col-md-4">
                                  <div class="form-group">
                                    <label for="edu_role"><?php echo $this->lang->line('xin_employee_monthly_installment_title');?><i class="hris-asterisk">*</i></label>
                                    <input class="form-control" placeholder="<?php echo $this->lang->line('xin_employee_monthly_installment_title');?>" name="monthly_installment" type="text" id="m_monthly_installment">
                                  </div>
                                </div>
                              </div>
                              <div class="row">
                                <div class="col-md-6">
                                  <div class="form-group">
                                    <label for="month_year"><?php echo $this->lang->line('xin_start_date');?><i class="hris-asterisk">*</i></label>
                                    <input class="form-control cont_date" placeholder="<?php echo $this->lang->line('xin_start_date');?>" readonly="readonly" name="start_date" type="text">
                                  </div>
                                </div>
                                <div class="col-md-6">
                                  <div class="form-group">
                                    <label for="end_date"><?php echo $this->lang->line('xin_end_date');?><i class="hris-asterisk">*</i></label>
                                    <input class="form-control cont_date" readonly="readonly" placeholder="<?php echo $this->lang->line('xin_end_date');?>" name="end_date" type="text">
                                  </div>
                                </div>
                              </div>
                              <div class="row">
                                <div class="col-md-12">
                                  <div class="form-group">
                                    <label for="description"><?php echo $this->lang->line('xin_reason');?></label>
                                    <textarea class="form-control textarea" placeholder="<?php echo $this->lang->line('xin_reason');?>" name="reason" cols="30" rows="2" id="reason2"></textarea>
                                  </div>
                                </div>
                              </div>
                              <div class="row">
                                <div class="col-md-12">
                                  <div class="form-group">
                                    <div class="form-actions box-footer"> <?php echo form_button(array('name' => 'hris_form', 'type' => 'submit', 'class' => $this->Core_model->form_button_class(), 'content' => '<i class="fa fa fa-check-square-o"></i> '.$this->lang->line('xin_save'))); ?> </div>
                                  </div>
                                </div>
                              </div>
                              <?php echo form_close(); ?> 
                            </div>
                            <div class="box">
                              <div class="box-header with-border">
                                <h3 class="box-title"> <?php echo $this->lang->line('xin_list_all');?> <?php echo $this->lang->line('xin_employee_set_loan_deductions');?> </h3>
                              </div>
                              <div class="box-body">
                                <div class="box-datatable table-responsive">
                                  <table class="table table-striped table-bordered dataTable" id="xin_table_all_deductions" style="width:100%;">
                                    <thead>
                                      <tr>
                                        <th><?php echo $this->lang->line('xin_action');?></th>
                                        <th><?php echo $this->lang->line('xin_employee_set_loan_deductions');?></th>
                                        <th><?php echo $this->lang->line('xin_employee_monthly_installment_title');?></th>
                                        <th><?php echo $this->lang->line('xin_employee_loan_time');?></th>
                                      </tr>
                                    </thead>
                                  </table>
                                </div>
                              </div>
                            </div>
                          </div>

                          <!-- pengeluaran -->
                          <div class="tab-pane <?php echo $get_animate;?> salary-current-tab" id="statutory_deductions" style="display:none;">
                            
                            <div class="box-header with-border">
                              <h3 class="box-title"> <?php echo $this->lang->line('xin_employee_set_statutory_deductions');?> </h3>
                            </div>
                            <div class="box-body pb-2">
                              <?php $attributes = array('name' => 'statutory_deductions_info', 'id' => 'statutory_deductions_info', 'autocomplete' => 'off');?>
                              <?php $hidden = array('u_basic_info' => 'UPDATE');?>
                              <?php echo form_open('admin/employees/set_statutory_deductions', $attributes, $hidden);?>
                              <?php
                                $data_usr4 = array(
                                'type'  => 'hidden',
                                'name'  => 'user_id',
                                'value' => $user_id,
                               );
                              echo form_input($data_usr4);
                              ?>
                              <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                      <label for="statutory_options"><?php echo $this->lang->line('xin_salary_sd_options');?><i class="hris-asterisk">*</i></label>
                                      <select name="statutory_options" id="statutory_options" class="form-control" data-plugin="select_hrm">
                                        <option value="1"><?php echo $this->lang->line('xin_sd_ssc_title');?></option>
                                        <option value="2"><?php echo $this->lang->line('xin_sd_phic_title');?></option>                                      
                                                                             
                                      </select>
                                    </div>
                                  </div>
                                  <div class="col-md-3">
                                  <div class="form-group">
                                    <label for="title"><?php echo $this->lang->line('dashboard_xin_title');?><i class="hris-asterisk">*</i></label>
                                    <input class="form-control" placeholder="<?php echo $this->lang->line('dashboard_xin_title');?>" name="title" type="text" value="" id="title">
                                  </div>
                                </div>
                                <div class="col-md-3">
                                  <div class="form-group">
                                    <label for="amount"><?php echo $this->lang->line('xin_amount');?>
                                    <?php if($system[0]->statutory_fixed!='yes'):?> (%) <?php endif;?><i class="hris-asterisk">*</i>
                                    </label>
                                    <input class="form-control" placeholder="<?php echo $this->lang->line('xin_amount');?>" name="amount" type="text" value="" id="amount">
                                  </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                    <div class="box-footer hris-salary-button"> <?php echo form_button(array('name' => 'hris_form', 'type' => 'submit', 'class' => $this->Core_model->form_button_class(), 'content' => '<i class="fa fa fa-check-square-o"></i> '.$this->lang->line('xin_save'))); ?> </div>
                                    </div>
                                  </div>
                              </div>
                              <div class="row">
                                <div class="col-md-12">
                                  <div class="form-group">
                                    <div class="form-actions box-footer">&nbsp;</div>
                                  </div>
                                </div>
                              </div>
                              <?php echo form_close(); ?> 
                          </div>

                          <div class="box">
                              <div class="box-header with-border">
                                <h3 class="box-title"> <?php echo $this->lang->line('xin_list_all');?> <?php echo $this->lang->line('xin_employee_set_statutory_deductions');?> </h3>
                              </div>
                              <div class="box-body">
                                <div class="box-datatable table-responsive">
                                  <table class="table table-striped table-bordered dataTable" id="xin_table_all_statutory_deductions" style="width:100%;">
                                    <thead>
                                      <tr>
                                        <th><?php echo $this->lang->line('xin_action');?></th>
                                        <th><?php echo $this->lang->line('xin_salary_sd_options');?></th>
                                        <th><?php echo $this->lang->line('dashboard_xin_title');?></th>
                                        <th><?php echo $this->lang->line('xin_amount');?> <?php if($system[0]->statutory_fixed!='yes'):?> (%) <?php endif;?></th>
                                      </tr>
                                    </thead>
                                  </table>
                                </div>
                              </div>
                            </div>
                            
                          </div>

                          <!-- pembayaran lain -->
                          <div class="tab-pane <?php echo $get_animate;?> salary-current-tab" id="other_payment" style="display:none;">
                            
                            <div class="box-header with-border">
                              <h3 class="box-title"> <?php echo $this->lang->line('xin_employee_set_other_payment');?> </h3>
                            </div>
                            <div class="box-body pb-2">
                              <?php $attributes = array('name' => 'other_payments_info', 'id' => 'other_payments_info', 'autocomplete' => 'off');?>
                              <?php $hidden = array('u_basic_info' => 'UPDATE');?>
                              <?php echo form_open('admin/employees/set_other_payments', $attributes, $hidden);?>
                              <?php
                                $data_usr4 = array(
                                'type'  => 'hidden',
                                'name'  => 'user_id',
                                'value' => $user_id,
                               );
                              echo form_input($data_usr4);
                              ?>
                              <div class="row">
                                <div class="col-md-4">
                                  <div class="form-group">
                                    <label for="title"><?php echo $this->lang->line('dashboard_xin_title');?><i class="hris-asterisk">*</i></label>
                                    <input class="form-control" placeholder="<?php echo $this->lang->line('dashboard_xin_title');?>" name="title" type="text" value="" id="title">
                                  </div>
                                </div>
                                <div class="col-md-4">
                                  <div class="form-group">
                                    <label for="amount"><?php echo $this->lang->line('xin_amount');?><i class="hris-asterisk">*</i></label>
                                    <input class="form-control" placeholder="<?php echo $this->lang->line('xin_amount');?>" name="amount" type="text" value="" id="amount">
                                  </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                    <div class="box-footer hris-salary-button"> <?php echo form_button(array('name' => 'hris_form', 'type' => 'submit', 'class' => $this->Core_model->form_button_class(), 'content' => '<i class="fa fa fa-check-square-o"></i> '.$this->lang->line('xin_save'))); ?> </div>
                                    </div>
                                  </div>
                              </div>
                              <div class="row">
                                <div class="col-md-12">
                                  <div class="form-group">
                                    <div class="form-actions box-footer">&nbsp;</div>
                                  </div>
                                </div>
                              </div>
                              <?php echo form_close(); ?> 
                            </div>
                            <div class="box">
                              <div class="box-header with-border">
                                <h3 class="box-title"> <?php echo $this->lang->line('xin_list_all');?> <?php echo $this->lang->line('xin_employee_set_other_payment');?> </h3>
                              </div>
                              <div class="box-body">
                                <div class="box-datatable table-responsive">
                                  <table class="table table-striped table-bordered dataTable" id="xin_table_all_other_payments" style="width:100%;">
                                    <thead>
                                      <tr>
                                        <th><?php echo $this->lang->line('xin_action');?></th>
                                        <th><?php echo $this->lang->line('dashboard_xin_title');?></th>
                                        <th><?php echo $this->lang->line('xin_amount');?></th>
                                      </tr>
                                    </thead>
                                  </table>
                                </div>
                              </div>
                            </div>
                          </div>

                          <!-- lembur -->
                          <div class="tab-pane <?php echo $get_animate;?> salary-current-tab" id="overtime" style="display:none;">
                           
                            <div class="box-header with-border">
                              <h3 class="box-title"> <?php echo $this->lang->line('dashboard_overtime');?> </h3>
                            </div>
                            <div class="box-body pb-2">
                              <?php $attributes = array('name' => 'overtime_info', 'id' => 'overtime_info', 'autocomplete' => 'off');?>
                              <?php $hidden = array('u_basic_info' => 'UPDATE');?>
                              <?php echo form_open('admin/employees/set_overtime', $attributes, $hidden);?>
                              <?php
                                $data_usr4 = array(
                                  'type'  => 'hidden',
                                  'name'  => 'user_id',
                                  'value' => $user_id,
                               );
                              echo form_input($data_usr4);
                              ?>
                              <div class="row">
                                <div class="col-md-3">
                                  <div class="form-group">
                                    <label for="overtime_type"><?php echo $this->lang->line('xin_employee_overtime_title');?><i class="hris-asterisk">*</i></label>
                                    <input class="form-control" placeholder="<?php echo $this->lang->line('xin_employee_overtime_title');?>" name="overtime_type" type="text" value="" id="overtime_type">
                                  </div>
                                </div>
                                <div class="col-md-3">
                                  <div class="form-group">
                                    <label for="no_of_days"><?php echo $this->lang->line('xin_employee_overtime_no_of_days');?><i class="hris-asterisk">*</i></label>
                                    <input class="form-control" placeholder="<?php echo $this->lang->line('xin_employee_overtime_no_of_days');?>" name="no_of_days" type="text" value="" id="no_of_days">
                                  </div>
                                </div>
                                <div class="col-md-3">
                                  <div class="form-group">
                                    <label for="overtime_hours"><?php echo $this->lang->line('xin_employee_overtime_hour');?><i class="hris-asterisk">*</i></label>
                                    <input class="form-control" placeholder="<?php echo $this->lang->line('xin_employee_overtime_hour');?>" name="overtime_hours" type="text" value="" id="overtime_hours">
                                  </div>
                                </div>
                                <div class="col-md-3">
                                  <div class="form-group">
                                    <label for="overtime_rate"><?php echo $this->lang->line('xin_employee_overtime_rate');?><i class="hris-asterisk">*</i></label>
                                    <input class="form-control" placeholder="<?php echo $this->lang->line('xin_employee_overtime_rate');?>" name="overtime_rate" type="text" value="" id="overtime_rate">
                                  </div>
                                </div>
                              </div>
                              <div class="row">
                                <div class="col-md-12">
                                  <div class="form-group">
                                    <div class="form-actions box-footer"> <?php echo form_button(array('name' => 'hris_form', 'type' => 'submit', 'class' => $this->Core_model->form_button_class(), 'content' => '<i class="fa fa fa-check-square-o"></i> '.$this->lang->line('xin_save'))); ?> </div>
                                  </div>
                                </div>
                              </div>
                              <?php echo form_close(); ?> 
                            </div>
                             <div class="box">
                              <div class="box-header with-border">
                                <h3 class="box-title"> <?php echo $this->lang->line('xin_list_all');?> <?php echo $this->lang->line('dashboard_overtime');?> </h3>
                              </div>
                              <div class="box-body">
                                <div class="box-datatable table-responsive">
                                  <table class="table table-striped table-bordered dataTable" id="xin_table_emp_overtime" style="width:100%;">
                                    <thead>
                                      <tr>
                                        <th><?php echo $this->lang->line('xin_action');?></th>
                                        <th><?php echo $this->lang->line('xin_employee_overtime_title');?></th>
                                        <th><?php echo $this->lang->line('xin_employee_overtime_no_of_days');?></th>
                                        <th><?php echo $this->lang->line('xin_employee_overtime_hour');?></th>
                                        <th><?php echo $this->lang->line('xin_employee_overtime_rate');?></th>
                                      </tr>
                                    </thead>
                                  </table>
                                </div>
                              </div>
                            </div>
                          </div>

                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            <?php } ?>

            <div class="tab-pane <?php echo $get_animate;?>" id="xin_leaves">
              <div class="box-body">
                <div class="row no-gutters row-bordered row-border-light">
                  <div class="col-md-12">
                    <div class="tab-content">
                          <div class="box-header with-border">
                            <h3 class="box-title"> <?php echo $this->lang->line('xin_e_details_leave');?> </h3>
                          </div>
                          <div class="box-body pb-2">
                            <div class="row">
                              <?php $leave_categories_ids = explode(',',$leave_user[0]->leave_categories); ?>
                               <?php foreach($all_leave_types as $type) {
                                    if(in_array($type->leave_type_id,$leave_categories_ids)){?>
                                <?php
                                    $hlfcount =0;
                                    //$count_l =0;
                                    $leave_halfday_cal = employee_leave_halfday_cal($type->leave_type_id,$this->uri->segment(4));
                                    foreach($leave_halfday_cal as $lhalfday):
                                        $hlfcount += 0.5;
                                    endforeach;
                                    
                                    $count_l = count_leaves_info($type->leave_type_id,$this->uri->segment(4));
                                    $count_l = $count_l - $hlfcount;
                                ?>
                                <?php
                                    $edays_per_year = $type->days_per_year;
                                    
                                    if($count_l == 0){
                                        $progress_class = '';
                                        $count_data = 0;
                                    } else {
                                        if($edays_per_year > 0){
                                            $count_data = $count_l / $edays_per_year * 100;
                                        } else {
                                            $count_data = 0;
                                        }
                                        // progress
                                        if($count_data <= 20) {
                                            $progress_class = 'progress-success';
                                        } else if($count_data > 20 && $count_data <= 50){
                                            $progress_class = 'progress-info';
                                        } else if($count_data > 50 && $count_data <= 75){
                                            $progress_class = 'progress-warning';
                                        } else {
                                            $progress_class = 'progress-danger';
                                        }
                                    }
                                ?>
                              <div class="col-md-3">
                                <div class="box mb-4">
                                  <div class="box-body">
                                    <div class="d-flex align-items-center">
                                      <div class="fa fa-calendar-check-o display-4 text-success"></div>
                                      <div class="ml-3">
                                        <div class="text-muted small"> <?php echo $type->type_name;?> (<?php echo $count_l;?>/<?php echo $edays_per_year;?>)</div>
                                        <div class="text-large">
                                          <div class="progress" style="height: 6px;">
                                            <div class="progress-bar" style="width: <?php echo $count_data;?>%;"></div>
                                          </div>
                                        </div>
                                      </div>
                                    </div>
                                  </div>
                                </div>
                              </div>
                              <?php
                          } }
                          ?>
                            </div>
                          </div>
                          <?php $leave = $this->Timesheet_model->get_employee_leaves($user_id); ?>
                          <div class="box <?php echo $get_animate;?>">
                          <div class="box-header with-border">
                            <h3 class="box-title"><?php echo $this->lang->line('xin_list_all');?> <?php echo $this->lang->line('left_leave');?></h3>
                          </div>
                          <div class="box-body">
                            <div class="box-datatable table-responsive">
                              <table class="datatables-demo table table-striped table-bordered xin_hris_table" id="xin_hr_table">
                                <thead>
                                  <tr>
                                    <th><?php echo $this->lang->line('xin_view');?></th>
                                    <th width="250"><?php echo $this->lang->line('xin_leave_type');?></th>
                                    <th><?php echo $this->lang->line('left_department');?></th>
                                    <th><i class="fa fa-calendar"></i> <?php echo $this->lang->line('xin_leave_duration');?></th>
                                    <th><i class="fa fa-calendar"></i> <?php echo $this->lang->line('xin_applied_on');?></th>
                                  </tr>
                                </thead>
                                <tbody>
                                <?php foreach($leave->result() as $r) { ?>
                                <?php
                  // get start date and end date
                  $user = $this->Core_model->read_user_info($r->employee_id);
                  if(!is_null($user)){
                    $full_name = $user[0]->first_name. ' '.$user[0]->last_name;
                    // department
                    $department = $this->Department_model->read_department_information($user[0]->department_id);
                    if(!is_null($department)){
                      $department_name = $department[0]->department_name;
                    } else {
                      $department_name = '--';  
                    }
                  } else {
                    $full_name = '--';  
                    $department_name = '--';
                  }
                   
                   // get leave type
                   $leave_type = $this->Timesheet_model->read_leave_type_information($r->leave_type_id);
                   if(!is_null($leave_type)){
                    $type_name = $leave_type[0]->type_name;
                  } else {
                    $type_name = '--';  
                  }
                  
                  // get company
                  $company = $this->Core_model->read_company_info($r->company_id);
                  if(!is_null($company)){
                    $comp_name = $company[0]->name;
                  } else {
                    $comp_name = '--';  
                  }
                   
                  $datetime1 = new DateTime($r->from_date);
                  $datetime2 = new DateTime($r->to_date);
                  $interval = $datetime1->diff($datetime2);
                  if(strtotime($r->from_date) == strtotime($r->to_date)){
                    $no_of_days =1;
                  } else {
                    $no_of_days = $interval->format('%a') + 1;
                  }
                  $applied_on = $this->Core_model->set_date_format($r->applied_on);
                  if($r->is_half_day == 1){
                  $duration = $this->Core_model->set_date_format($r->from_date).' '.$this->lang->line('dashboard_to').' '.$this->Core_model->set_date_format($r->to_date).'<br>'.$this->lang->line('xin_hris_total_days').': '.$this->lang->line('xin_hr_leave_half_day');
                  } else {
                    $duration = $this->Core_model->set_date_format($r->from_date).' '.$this->lang->line('dashboard_to').' '.$this->Core_model->set_date_format($r->to_date).'<br>'.$this->lang->line('xin_hris_total_days').': '.$no_of_days;
                  }
                  
                   
                  if($r->status==1): $status = '<span class="badge bg-orange">'.$this->lang->line('xin_pending').'</span>';
                  elseif($r->status==2): $status = '<span class="badge bg-green">'.$this->lang->line('xin_approved').'</span>';
                  elseif($r->status==4): $status = '<span class="badge bg-green">'.$this->lang->line('xin_role_first_level_approved').'</span>';
                  else: $status = '<span class="badge bg-red">'.$this->lang->line('xin_rejected').'</span>'; endif;
                  
                  if(in_array('290',$role_resources_ids)) { //view
                    $view = '<span data-toggle="tooltip" data-placement="top" title="'.$this->lang->line('xin_view_details').'"><a href="'.site_url().'admin/timesheet/leave_details/id/'.$r->leave_id.'/" target="_blank"><button type="button" class="btn icon-btn btn-xs btn-default waves-effect waves-light"><span class="fa fa-arrow-circle-right"></span></button></a></span>';
                  } else {
                    $view = '';
                  }
                  $combhr = $view;
                  $itype_name = $type_name.'<br><small class="text-muted"><i>'.$this->lang->line('xin_reason').': '.$r->reason.'<i></i></i></small><br><small class="text-muted"><i>'.$status.'<i></i></i></small><br><small class="text-muted"><i>'.$this->lang->line('left_company').': '.$comp_name.'<i></i></i></small>';
                  ?>
                                  <tr>
                                    <td><?php echo $combhr;?></td>
                                    <td><?php echo $itype_name;?></td>
                                    <td><?php echo $department_name;?></td>
                                    <td><i class="fa fa-calendar"></i> <?php echo $duration;?></td>
                                    <td><i class="fa fa-calendar"></i> <?php echo $applied_on;?></td>
                                  </tr>
                                  <?php } ?>
                                 </tbody> 
                              </table>
                            </div>
                          </div>
                        </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <div class="tab-pane <?php echo $get_animate;?>" id="xin_sicks">
              <div class="box-body">
                <div class="row no-gutters row-bordered row-border-light">
                  <div class="col-md-12">
                    <div class="tab-content">
                          <div class="box-header with-border">
                            <h3 class="box-title"> <?php echo $this->lang->line('xin_e_details_sick');?> </h3>
                          </div>
                          <div class="box-body pb-2">
                            <div class="row">
                              <?php $leave_categories_ids = explode(',',$leave_user[0]->leave_categories); ?>
                               <?php foreach($all_leave_types as $type) {
                                    if(in_array($type->leave_type_id,$leave_categories_ids)){?>
                                <?php
                                    $hlfcount =0;
                                    //$count_l =0;
                                    $leave_halfday_cal = employee_leave_halfday_cal($type->leave_type_id,$this->uri->segment(4));
                                    foreach($leave_halfday_cal as $lhalfday):
                                        $hlfcount += 0.5;
                                    endforeach;
                                    
                                    $count_l = count_leaves_info($type->leave_type_id,$this->uri->segment(4));
                                    $count_l = $count_l - $hlfcount;
                                ?>
                                <?php
                                    $edays_per_year = $type->days_per_year;
                                    
                                    if($count_l == 0){
                                        $progress_class = '';
                                        $count_data = 0;
                                    } else {
                                        if($edays_per_year > 0){
                                            $count_data = $count_l / $edays_per_year * 100;
                                        } else {
                                            $count_data = 0;
                                        }
                                        // progress
                                        if($count_data <= 20) {
                                            $progress_class = 'progress-success';
                                        } else if($count_data > 20 && $count_data <= 50){
                                            $progress_class = 'progress-info';
                                        } else if($count_data > 50 && $count_data <= 75){
                                            $progress_class = 'progress-warning';
                                        } else {
                                            $progress_class = 'progress-danger';
                                        }
                                    }
                                ?>
                              <div class="col-md-3">
                                <div class="box mb-4">
                                  <div class="box-body">
                                    <div class="d-flex align-items-center">
                                      <div class="fa fa-calendar-check-o display-4 text-success"></div>
                                      <div class="ml-3">
                                        <div class="text-muted small"> <?php echo $type->type_name;?> (<?php echo $count_l;?>/<?php echo $edays_per_year;?>)</div>
                                        <div class="text-large">
                                          <div class="progress" style="height: 6px;">
                                            <div class="progress-bar" style="width: <?php echo $count_data;?>%;"></div>
                                          </div>
                                        </div>
                                      </div>
                                    </div>
                                  </div>
                                </div>
                              </div>
                              <?php
                          } }
                          ?>
                            </div>
                          </div>
                          <?php $leave = $this->Timesheet_model->get_employee_leaves($user_id); ?>
                          <div class="box <?php echo $get_animate;?>">
                          <div class="box-header with-border">
                            <h3 class="box-title"><?php echo $this->lang->line('xin_list_all');?> <?php echo $this->lang->line('left_leave');?></h3>
                          </div>
                          <div class="box-body">
                            <div class="box-datatable table-responsive">
                              <table class="datatables-demo table table-striped table-bordered xin_hris_table" id="xin_hr_table">
                                <thead>
                                  <tr>
                                    <th><?php echo $this->lang->line('xin_view');?></th>
                                    <th width="250"><?php echo $this->lang->line('xin_leave_type');?></th>
                                    <th><?php echo $this->lang->line('left_department');?></th>
                                    <th><i class="fa fa-calendar"></i> <?php echo $this->lang->line('xin_leave_duration');?></th>
                                    <th><i class="fa fa-calendar"></i> <?php echo $this->lang->line('xin_applied_on');?></th>
                                  </tr>
                                </thead>
                                <tbody>
                                <?php foreach($leave->result() as $r) { ?>
                                <?php
                  // get start date and end date
                  $user = $this->Core_model->read_user_info($r->employee_id);
                  if(!is_null($user)){
                    $full_name = $user[0]->first_name. ' '.$user[0]->last_name;
                    // department
                    $department = $this->Department_model->read_department_information($user[0]->department_id);
                    if(!is_null($department)){
                      $department_name = $department[0]->department_name;
                    } else {
                      $department_name = '--';  
                    }
                  } else {
                    $full_name = '--';  
                    $department_name = '--';
                  }
                   
                   // get leave type
                   $leave_type = $this->Timesheet_model->read_leave_type_information($r->leave_type_id);
                   if(!is_null($leave_type)){
                    $type_name = $leave_type[0]->type_name;
                  } else {
                    $type_name = '--';  
                  }
                  
                  // get company
                  $company = $this->Core_model->read_company_info($r->company_id);
                  if(!is_null($company)){
                    $comp_name = $company[0]->name;
                  } else {
                    $comp_name = '--';  
                  }
                   
                  $datetime1 = new DateTime($r->from_date);
                  $datetime2 = new DateTime($r->to_date);
                  $interval = $datetime1->diff($datetime2);
                  if(strtotime($r->from_date) == strtotime($r->to_date)){
                    $no_of_days =1;
                  } else {
                    $no_of_days = $interval->format('%a') + 1;
                  }
                  $applied_on = $this->Core_model->set_date_format($r->applied_on);
                  if($r->is_half_day == 1){
                  $duration = $this->Core_model->set_date_format($r->from_date).' '.$this->lang->line('dashboard_to').' '.$this->Core_model->set_date_format($r->to_date).'<br>'.$this->lang->line('xin_hris_total_days').': '.$this->lang->line('xin_hr_leave_half_day');
                  } else {
                    $duration = $this->Core_model->set_date_format($r->from_date).' '.$this->lang->line('dashboard_to').' '.$this->Core_model->set_date_format($r->to_date).'<br>'.$this->lang->line('xin_hris_total_days').': '.$no_of_days;
                  }
                  
                   
                  if($r->status==1): $status = '<span class="badge bg-orange">'.$this->lang->line('xin_pending').'</span>';
                  elseif($r->status==2): $status = '<span class="badge bg-green">'.$this->lang->line('xin_approved').'</span>';
                  elseif($r->status==4): $status = '<span class="badge bg-green">'.$this->lang->line('xin_role_first_level_approved').'</span>';
                  else: $status = '<span class="badge bg-red">'.$this->lang->line('xin_rejected').'</span>'; endif;
                  
                  if(in_array('290',$role_resources_ids)) { //view
                    $view = '<span data-toggle="tooltip" data-placement="top" title="'.$this->lang->line('xin_view_details').'"><a href="'.site_url().'admin/timesheet/leave_details/id/'.$r->leave_id.'/" target="_blank"><button type="button" class="btn icon-btn btn-xs btn-default waves-effect waves-light"><span class="fa fa-arrow-circle-right"></span></button></a></span>';
                  } else {
                    $view = '';
                  }
                  $combhr = $view;
                  $itype_name = $type_name.'<br><small class="text-muted"><i>'.$this->lang->line('xin_reason').': '.$r->reason.'<i></i></i></small><br><small class="text-muted"><i>'.$status.'<i></i></i></small><br><small class="text-muted"><i>'.$this->lang->line('left_company').': '.$comp_name.'<i></i></i></small>';
                  ?>
                                  <tr>
                                    <td><?php echo $combhr;?></td>
                                    <td><?php echo $itype_name;?></td>
                                    <td><?php echo $department_name;?></td>
                                    <td><i class="fa fa-calendar"></i> <?php echo $duration;?></td>
                                    <td><i class="fa fa-calendar"></i> <?php echo $applied_on;?></td>
                                  </tr>
                                  <?php } ?>
                                 </tbody> 
                              </table>
                            </div>
                          </div>
                        </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
                
            
            

            <div class="tab-pane <?php echo $get_animate;?>" id="xin_core_hr">
              <div class="box-body">
                <div class="row no-gutters row-bordered row-border-light">
                  <div class="col-md-2 pt-0">
                      <div class="list-group list-group-flush account-settings-links"> 

                        <a class="list-group-item list-group-item-action xin-core-hr-opt  active xin-core-hr-tab" data-toggle="list" href="javascript:void(0);" data-core-hr-info="51" data-core-profile-block="left_awards" aria-expanded="true" id="core_hr_51"><?php echo $this->lang->line('left_awards');?></a> 
                        <a class="list-group-item list-group-item-action  xin-core-hr-opt xin-core-hr-tab" data-toggle="list" href="javascript:void(0);" data-core-hr-info="58" data-core-profile-block="left_warnings" aria-expanded="true"      id="core_hr_58"><?php echo $this->lang->line('left_warnings');?></a> 
                        <a class="list-group-item list-group-item-action  xin-core-hr-opt xin-core-hr-tab" data-toggle="list" href="javascript:void(0);" data-core-hr-info="55" data-core-profile-block="left_transfers" aria-expanded="true"     id="core_hr_55"><?php echo $this->lang->line('left_transfers');?></a> 
                        <a class="list-group-item list-group-item-action  xin-core-hr-opt xin-core-hr-tab" data-toggle="list" href="javascript:void(0);" data-core-hr-info="56" data-core-profile-block="left_promotions" aria-expanded="true"    id="core_hr_56"><?php echo $this->lang->line('left_promotions');?></a>
                        <a class="list-group-item list-group-item-action  xin-core-hr-opt xin-core-hr-tab" data-toggle="list" href="javascript:void(0);" data-core-hr-info="52" data-core-profile-block="left_travels" aria-expanded="true"       id="core_hr_52"><?php echo $this->lang->line('left_travels');?></a>  
            <a class="list-group-item list-group-item-action  xin-core-hr-opt xin-core-hr-tab" data-toggle="list" href="javascript:void(0);" data-core-hr-info="53" data-core-profile-block="left_training" aria-expanded="true"      id="core_hr_53"><?php echo $this->lang->line('left_training');?></a>

                      </div>
                    </div><!--style="display:none;"-->
                    <div class="col-md-10">
                    <div class="tab-content">
                        <div class="tab-pane active core-current-tab <?php echo $get_animate;?>" id="left_awards">
                         <div class="box <?php echo $get_animate;?>">
                          <div class="box-header with-border">
                            <h3 class="box-title"> <?php echo $this->lang->line('xin_list_all');?> <?php echo $this->lang->line('left_awards');?> </h3>
                          </div>
                          <?php $award = $this->Awards_model->get_employee_awards($user_id); ?>
                          <div class="box-body">
                            <div class="box-datatable table-responsive">
                              <table class="datatables-demo table table-striped table-bordered xin_hris_table" id="xin_hr_table">
                                <thead>
                                  <tr>
                                    <th style="width:100px;"><?php echo $this->lang->line('xin_view');?></th>
                                    <th width="300"><i class="fa fa-trophy"></i> <?php echo $this->lang->line('xin_award_name');?></th>
                                    <th><i class="fa fa-gift"></i> <?php echo $this->lang->line('xin_gift');?></th>
                                    <th><i class="fa fa-calendar"></i> <?php echo $this->lang->line('xin_award_month_year');?></th>
                                  </tr>
                                </thead>
                                <tbody>
                                <?php foreach($award->result() as $r) { ?>
                                <?php
                                // get user > added by
                                $user = $this->Core_model->read_user_info($r->employee_id);
                                // user full name
                                if(!is_null($user)){
                                  $full_name = $user[0]->first_name.' '.$user[0]->last_name;
                                } else {
                                  $full_name = '--';  
                                }
                                // get award type
                                $award_type = $this->Awards_model->read_award_type_information($r->award_type_id);
                                if(!is_null($award_type)){
                                  $award_type = $award_type[0]->award_type;
                                } else {
                                  $award_type = '--'; 
                                }
                                
                                $d = explode('-',$r->award_month_year);
                                $get_month = date('F', mktime(0, 0, 0, $d[1], 10));
                                $award_date = $get_month.', '.$d[0];
                                // get currency
                                if($r->cash_price == '') {
                                  $currency = $this->Core_model->currency_sign(0);
                                } else {
                                  $currency = $this->Core_model->currency_sign($r->cash_price);
                                }   
                                // get company
                                $company = $this->Core_model->read_company_info($r->company_id);
                                if(!is_null($company)){
                                  $comp_name = $company[0]->name;
                                } else {
                                  $comp_name = '--';  
                                }
                                
                                if(in_array('232',$role_resources_ids)) { //view
                                  $view = '<span data-toggle="tooltip" data-placement="top" title="'.$this->lang->line('xin_view').'"><button type="button" class="btn icon-btn btn-xs btn-default waves-effect waves-light" data-toggle="modal" data-target=".view-modal-data" data-xfield_id="'. $r->award_id . '" data-field_type="awards"><span class="fa fa-eye"></span></button></span>';
                                } else {
                                  $view = '';
                                }
                                $award_info = $award_type.'<br><small class="text-muted"><i>'.$r->description.'<i></i></i></small><br><small class="text-muted"><i>'.$this->lang->line('xin_cash_price').': '.$currency.'<i></i></i></small>';
                                $combhr = $view;
                                ?>
                                <tr>
                                    <td><?php echo $combhr;?></td>
                                    <td><?php echo $award_info;?></td>
                                    <td><?php echo $r->gift_item;?></td>
                                    <td><?php echo $award_date;?></td>
                                  </tr>
                                <?php } ?>  
                                </tbody>
                              </table>
                            </div>
                          </div>
                        </div>
                        </div>
                        <div class="tab-pane active core-current-tab <?php echo $get_animate;?>" id="left_travels" style="display:none;">
               <div class="box <?php echo $get_animate;?>">
                <div class="box-header with-border">
                <h3 class="box-title"> <?php echo $this->lang->line('xin_list_all');?> <?php echo $this->lang->line('xin_travel');?> </h3>
                </div>
                <?php $travel = $this->Travel_model->get_employee_travel($user_id); ?>
                <div class="box-body">
                <div class="box-datatable table-responsive">
                  <table class="datatables-demo table table-striped table-bordered xin_hris_table">
                  <thead>
                    <tr>
                    <th><?php echo $this->lang->line('xin_view');?></th>
                    <th><?php echo $this->lang->line('xin_summary');?></th>
                    <th><?php echo $this->lang->line('xin_visit_place');?></th>
                    <th><i class="fa fa-calendar"></i> <?php echo $this->lang->line('xin_start_date');?></th>
                    <th><i class="fa fa-calendar"></i> <?php echo $this->lang->line('xin_end_date');?></th>
                    </tr>
                  </thead>
                  <tbody>
                  <?php foreach($travel->result() as $r) { ?>
                  <?php
                  // get start date
                        $start_date = $this->Core_model->set_date_format($r->start_date);
                        // get end date
                        $end_date = $this->Core_model->set_date_format($r->end_date);
                        // get company
                        $company = $this->Core_model->read_company_info($r->company_id);
                        if(!is_null($company)){
                          $comp_name = $company[0]->name;
                        } else {
                          $comp_name = '--';  
                        }
                        // status
                        //if($r->status==0): $status = $this->lang->line('xin_pending');
                        //elseif($r->status==1): $status = $this->lang->line('xin_accepted'); else: $status = $this->lang->line('xin_rejected'); endif;
                        if($r->status==0): $status = '<span class="badge bg-orange">'.$this->lang->line('xin_pending').'</span>';
                          elseif($r->status==1): $status = '<span class="badge bg-green">'.$this->lang->line('xin_accepted').'</span>';else: $status = '<span class="badge bg-red">'.$this->lang->line('xin_rejected'); endif;
                        
                        if(in_array('235',$role_resources_ids)) { //view
                          $view = '<span data-toggle="tooltip" data-placement="top" title="'.$this->lang->line('xin_view').'"><button type="button" class="btn icon-btn btn-xs btn-default waves-effect waves-light" data-toggle="modal" data-target=".view-modal-data" data-xfield_id="'. $r->travel_id . '" data-field_type="travel"><span class="fa fa-eye"></span></button></span>';
                        } else {
                          $view = '';
                        }
                        $combhr = $view;
                        $expected_budget = $this->Core_model->currency_sign($r->expected_budget);
                        $actual_budget = $this->Core_model->currency_sign($r->actual_budget);
                        $iemployee_name = $r->visit_purpose.'<br><small class="text-muted"><i>'.$this->lang->line('xin_expected_travel_budget').': '.$expected_budget.'<i></i></i></small><br><small class="text-muted"><i>'.$this->lang->line('xin_actual_travel_budget').': '.$actual_budget.'<i></i></i></small><br><small class="text-muted"><i>'.$status.'<i></i></i></small>';
                        ?>
                  <tr>
                    <td><?php echo $combhr;?></td>
                    <td><?php echo $iemployee_name;?></td>
                    <td><?php echo $r->visit_place;?></td>
                    <td><?php echo $start_date;?></td>
                    <td><?php echo $end_date;?></td>
                    </tr>
                  <?php } ?>  
                  </tbody>
                  </table>
                </div>
                </div>
              </div>
                        </div>

                        <div class="tab-pane active core-current-tab <?php echo $get_animate;?>" id="left_training" style="display:none;">
               <div class="box <?php echo $get_animate;?>">
                <div class="box-header with-border">
                <h3 class="box-title"> <?php echo $this->lang->line('xin_list_all');?> <?php echo $this->lang->line('left_training');?> </h3>
                </div>
                <?php $training = $this->Training_model->get_employee_training($user_id); ?>
                <div class="box-body">
                <div class="box-datatable table-responsive">
                  <table class="datatables-demo table table-striped table-bordered xin_hris_table">
                  <thead>
                    <tr>
                    <th><?php echo $this->lang->line('xin_view');?></th>
                    <th><?php echo $this->lang->line('left_training_type');?></th>
                    <th><?php echo $this->lang->line('xin_trainer');?></th>
                    <th><i class="fa fa-calendar"></i> <?php echo $this->lang->line('xin_training_duration');?></th>
                    <th><i class="fa fa-dollar"></i> <?php echo $this->lang->line('xin_cost');?></th>
                    </tr>
                  </thead>
                  <tbody>
                  <?php foreach($training->result() as $r) { ?>
                  <?php
                        $aim = explode(',',$r->employee_id);
                        // get training type
                        $type = $this->Training_model->read_training_type_information($r->training_type_id);
                        if(!is_null($type)){
                          $itype = $type[0]->type;
                        } else {
                          $itype = '--';  
                        }
                        // get trainer
                        $trainer = $this->Trainers_model->read_trainer_information($r->trainer_id);
                        // trainer full name
                        if(!is_null($trainer)){
                          $trainer_name = $trainer[0]->first_name.' '.$trainer[0]->last_name;
                        } else {
                          $trainer_name = '--'; 
                        }
                        // get start date
                        $start_date = $this->Core_model->set_date_format($r->start_date);
                        // get end date
                        $finish_date = $this->Core_model->set_date_format($r->finish_date);
                        // training date
                        $training_date = $start_date.' '.$this->lang->line('dashboard_to').' '.$finish_date;
                        // set currency
                        $training_cost = $this->Core_model->currency_sign($r->training_cost);
                        /* get Employee info*/
                        if($r->employee_id == '') {
                          $ol = '--';
                        } else {
                          $ol = '<ol class="nl">';
                          foreach(explode(',',$r->employee_id) as $uid) {
                            $user = $this->Core_model->read_user_info($uid);
                            if(!is_null($user)){
                              $ol .= '<li>'.$user[0]->first_name.' '.$user[0]->last_name.'</li>';
                            } else {
                              $ol .= '--';
                            }
                           }
                           $ol .= '</ol>';
                        }
                        // status
                        //if($r->training_status==0): $status = $this->lang->line('xin_pending');
                        //elseif($r->training_status==1): $status = $this->lang->line('xin_started'); elseif($r->training_status==2): $status = $this->lang->line('xin_completed');
                        //else: $status = $this->lang->line('xin_terminated'); endif;
                        if($r->training_status==0): $status = '<span class="badge bg-orange">'.$this->lang->line('xin_pending').'</span>';
                        elseif($r->training_status==1): $status = '<span class="badge bg-teal">'.$this->lang->line('xin_started').'</span>'; elseif($r->training_status==2): $status = '<span class="badge bg-green">'.$this->lang->line('xin_completed').'</span>';
                        else: $status = '<span class="badge bg-red">'.$this->lang->line('xin_terminated').'</span>'; endif;
                        // get company
                        $company = $this->Core_model->read_company_info($r->company_id);
                        if(!is_null($company)){
                        $comp_name = $company[0]->name;
                        } else {
                          $comp_name = '--';  
                        }
                        if(in_array('344',$role_resources_ids)) { //view
                          $view = '<span data-toggle="tooltip" data-placement="top" title="'.$this->lang->line('xin_view_details').'"><a href="'.site_url().'admin/training/details/'.$r->training_id.'" target="_blank"><button type="button" class="btn icon-btn btn-xs btn-default waves-effect waves-light"><span class="fa fa-arrow-circle-right"></span></button></a></span>';
                        } else {
                          $view = '';
                        }
                        $combhr = $view;
                        $iitype = $itype.'<br><small class="text-muted"><i>'.$status.'<i></i></i></small>';
                        ?>
                  <tr>
                    <td><?php echo $combhr;?></td>
                    <td><?php echo $iitype;?></td>
                    <td><?php echo $trainer_name;?></td>
                    <td><?php echo $training_date;?></td>
                    <td><?php echo $training_cost;?></td>
                    </tr>
                  <?php } ?>  
                  </tbody>
                  </table>
                </div>
                </div>
              </div>
                        </div> 

                       <!--  <div class="tab-pane active core-current-tab <?php echo $get_animate;?>" id="left_tickets" style="display:none;">
                         <div class="box <?php echo $get_animate;?>">
                          <div class="box-header with-border">
                            <h3 class="box-title"> <?php echo $this->lang->line('xin_list_all');?> <?php echo $this->lang->line('left_tickets');?> </h3>
                          </div>
                          <?php $ticket = $this->Tickets_model->get_employees_tickets($user_id);?>
                          <div class="box-body">
                            <div class="box-datatable table-responsive">
                              <table class="datatables-demo table table-striped table-bordered xin_hris_table">
                                <thead>
                                 <tr class="xin-bg-dark">
                                    <th><?php echo $this->lang->line('xin_view');?></th>
                                    <th><?php echo $this->lang->line('xin_ticket_code');?></th>
                                    <th><?php echo $this->lang->line('xin_subject');?></th>
                                    <th><?php echo $this->lang->line('xin_p_priority');?></th>
                                    <th><i class="fa fa-calendar"></i> <?php echo $this->lang->line('xin_e_details_date');?></th>
                                  </tr>
                                </thead>
                                <tbody>
                                <?php foreach($ticket->result() as $r) { ?>
                                <?php   
                                // priority
                                if($r->ticket_priority==1): $priority = $this->lang->line('xin_low'); elseif($r->ticket_priority==2): $priority = $this->lang->line('xin_medium'); elseif($r->ticket_priority==3): $priority = $this->lang->line('xin_high'); elseif($r->ticket_priority==4): $priority = $this->lang->line('xin_critical');  endif;
                                 
                                 // status
                                 //if($r->ticket_status==1): $status = $this->lang->line('xin_open'); elseif($r->ticket_status==2): $status = $this->lang->line('xin_closed'); endif;
                                 if($r->ticket_status==1): $status = '<span class="badge bg-orange">'.$this->lang->line('xin_open').'</span>';
                                  else: $status = '<span class="badge bg-green">'.$this->lang->line('xin_closed').'</span>';endif;
                                 // ticket date and time
                                 $created_at = date('h:i A', strtotime($r->created_at));
                                 $_date = explode(' ',$r->created_at);
                                 $edate = $this->Core_model->set_date_format($_date[0]);
                                 $_created_at = $edate. ' '. $created_at;
                                
                                
                                  $view = '<span data-toggle="tooltip" data-placement="top" title="'.$this->lang->line('xin_view_details').'"><a href="'.site_url().'admin/tickets/details/'.$r->ticket_id.'" target="_blank"><button type="button" class="btn icon-btn btn-xs btn-default waves-effect waves-light"><span class="fa fa-arrow-circle-right"></span></button></a></span>';
                                
                                $combhr = $view;
                                $iticket_code = $r->ticket_code.'<br><small class="text-muted"><i>'.$status.'<i></i></i></small>';
                                ?>
                                <tr>
                                    <td><?php echo $combhr;?></td>
                                    <td><?php echo $iticket_code;?></td>
                                    <td><?php echo $r->subject;?></td>
                                    <td><?php echo $priority;?></td>
                                    <td><?php echo $_created_at;?></td>
                                  </tr>
                                <?php } ?>  
                                </tbody>
                              </table>
                            </div>
                          </div>
                        </div>
                        </div> -->

                        <div class="tab-pane active core-current-tab <?php echo $get_animate;?>" id="left_transfers" style="display:none;">
                         <div class="box <?php echo $get_animate;?>">
                          <div class="box-header with-border">
                            <h3 class="box-title"> <?php echo $this->lang->line('xin_list_all');?> <?php echo $this->lang->line('left_transfers');?> </h3>
                          </div>
                          <?php $transfer = $this->Transfers_model->get_employee_transfers($user_id); ?>
                          <div class="box-body">
                            <div class="box-datatable table-responsive">
                              <table class="datatables-demo table table-striped table-bordered xin_hris_table">
                                <thead>
                                  <tr>
                                    <th><?php echo $this->lang->line('xin_view');?></th>
                                    <th><?php echo $this->lang->line('xin_summary');?></th>
                                    <th><?php echo $this->lang->line('left_company');?></th>
                                    <th><i class="fa fa-calendar"></i> <?php echo $this->lang->line('xin_transfer_date');?></th>
                                    <th><?php echo $this->lang->line('dashboard_xin_status');?></th>
                                  </tr>
                                </thead>
                                <tbody>
                                <?php foreach($transfer->result() as $r) { ?>
                                <?php
                                // get date
                                $transfer_date = $this->Core_model->set_date_format($r->transfer_date);
                                // get department by id
                                $department = $this->Department_model->read_department_information($r->transfer_department);
                                if(!is_null($department)){
                                  $department_name = $department[0]->department_name;
                                } else {
                                  $department_name = '--';  
                                }
                                // get location by id
                                $location = $this->Location_model->read_location_information($r->transfer_location);
                                if(!is_null($location)){
                                  $location_name = $location[0]->location_name;
                                } else {
                                  $location_name = '--';  
                                }
                                // get status
                                if($r->status==0): $status = '<span class="badge bg-orange">'.$this->lang->line('xin_pending').'</span>';
                                elseif($r->status==1): $status = '<span class="badge bg-green">'.$this->lang->line('xin_accepted').'</span>';else: $status = '<span class="badge bg-red">'.$this->lang->line('xin_rejected').'</span>'; endif;
                                
                                // get company
                                $company = $this->Core_model->read_company_info($r->company_id);
                                if(!is_null($company)){
                                  $comp_name = $company[0]->name;
                                } else {
                                  $comp_name = '--';  
                                }
                                
                                if(in_array('233',$role_resources_ids)) { //view
                                  $view = '<span data-toggle="tooltip" data-placement="top" title="'.$this->lang->line('xin_view').'"><button type="button" class="btn icon-btn btn-xs btn-default waves-effect waves-light" data-toggle="modal" data-target=".view-modal-data" data-xfield_id="'. $r->transfer_id . '" data-field_type="transfers"><span class="fa fa-eye"></span></button></span>';
                                } else {
                                  $view = '';
                                }
                              $combhr = $view;
                              $xinfo = $this->lang->line('xin_transfer_to_department').': '.$department_name.'<i></i></i></small><br><small class="text-muted"><i>'.$this->lang->line('xin_transfer_to_location').': '.$location_name.'<i></i></i></small>';
                                ?>
                                <tr>
                                    <td><?php echo $combhr;?></td>
                                    <td><?php echo $xinfo;?></td>
                                    <td><?php echo $comp_name;?></td>
                                    <td><?php echo $transfer_date;?></td>
                                    <td><?php echo $status;?></td>
                                  </tr>
                                <?php } ?>  
                                </tbody>
                              </table>
                            </div>
                          </div>
                        </div>
                        </div>
                        <div class="tab-pane active core-current-tab <?php echo $get_animate;?>" id="left_promotions" style="display:none;">
                         <div class="box <?php echo $get_animate;?>">
                          <div class="box-header with-border">
                            <h3 class="box-title"> <?php echo $this->lang->line('xin_list_all');?> <?php echo $this->lang->line('left_promotions');?> </h3>
                          </div>
                          <?php $promotion = $this->Promotion_model->get_employee_promotions($user_id); ?>
                          <div class="box-body">
                            <div class="box-datatable table-responsive">
                              <table class="datatables-demo table table-striped table-bordered xin_hris_table">
                                <thead>
                                  <tr>
                                    <th><?php echo $this->lang->line('xin_view');?></th>
                                    <th><?php echo $this->lang->line('xin_promotion_title');?></th>
                                    <th><i class="fa fa-calendar"></i> <?php echo $this->lang->line('xin_e_details_date');?></th>
                                  </tr>
                                </thead>
                                <tbody>
                                <?php foreach($promotion->result() as $r) { ?>
                                <?php
                  // get company
                  $company = $this->Core_model->read_company_info($r->company_id);
                  if(!is_null($company)){
                    $comp_name = $company[0]->name;
                  } else {
                    $comp_name = '--';  
                  }
                  // get promotion date
                  $promotion_date = $this->Core_model->set_date_format($r->promotion_date);
                  if(in_array('236',$role_resources_ids)) { //view
                    $view = '<span data-toggle="tooltip" data-placement="top" title="'.$this->lang->line('xin_view').'"><button type="button" class="btn icon-btn btn-xs btn-default waves-effect waves-light" data-toggle="modal" data-target=".view-modal-data" data-xfield_id="'. $r->promotion_id . '" data-field_type="promotion"><span class="fa fa-eye"></span></button></span>';
                  } else {
                    $view = '';
                  }
                  $combhr = $view;
                  $pro_desc = $r->title.'<br><small class="text-muted"><i>'.$this->lang->line('xin_description').': '.$r->description.'<i></i></i></small>';
                  ?>
                                <tr>
                                    <td><?php echo $combhr;?></td>
                                    <td><?php echo $pro_desc;?></td>
                                    <td><?php echo $promotion_date;?></td>
                                  </tr>
                                <?php } ?>  
                                </tbody>
                              </table>
                            </div>
                          </div>
                        </div>
                        </div>
                        <div class="tab-pane active core-current-tab <?php echo $get_animate;?>" id="left_complaints" style="display:none;">
                         <div class="box <?php echo $get_animate;?>">
                          <div class="box-header with-border">
                            <h3 class="box-title"> <?php echo $this->lang->line('xin_list_all');?> <?php echo $this->lang->line('left_complaints');?> </h3>
                          </div>
                          <?php $complaint = $this->Complaints_model->get_employee_complaints($user_id); ?>
                          <div class="box-body">
                            <div class="box-datatable table-responsive">
                              <table class="datatables-demo table table-striped table-bordered xin_hris_table">
                                <thead>
                                  <tr>
                                    <th><?php echo $this->lang->line('xin_view');?></th>
                                    <th width="200"><i class="fa fa-user"></i> <?php echo $this->lang->line('xin_complaint_from');?></th>
                                    <th><i class="fa fa-users"></i> <?php echo $this->lang->line('xin_complaint_against');?></th>
                                    <th><?php echo $this->lang->line('xin_complaint_title');?></th>
                                    <th><i class="fa fa-calendar"></i> <?php echo $this->lang->line('xin_complaint_date');?></th>
                                  </tr>
                                </thead>
                                <tbody>
                                <?php foreach($complaint->result() as $r) { ?>
                                <?php
                  // get user > added by
                  $user = $this->Core_model->read_user_info($r->complaint_from);
                  // user full name
                  if(!is_null($user)){
                    $complaint_from = $user[0]->first_name.' '.$user[0]->last_name;
                  } else {
                    $complaint_from = '--'; 
                  }
                  
                  if($r->complaint_against == '') {
                    $ol = '--';
                  } else {
                    $ol = '<ol class="nl">';
                    foreach(explode(',',$r->complaint_against) as $desig_id) {
                      $_comp_name = $this->Core_model->read_user_info($desig_id);
                      if(!is_null($_comp_name)){
                        $ol .= '<li>'.$_comp_name[0]->first_name.' '.$_comp_name[0]->last_name.'</li>';
                      } else {
                        $ol .= '';
                      }
                      
                     }
                     $ol .= '</ol>';
                  }
                  // get complaint date
                  $complaint_date = $this->Core_model->set_date_format($r->complaint_date);
                
                  if(in_array('237',$role_resources_ids)) { //view
                    $view = '<span data-toggle="tooltip" data-placement="top" title="'.$this->lang->line('xin_view').'"><button type="button" class="btn icon-btn btn-xs btn-default waves-effect waves-light" data-toggle="modal" data-target=".view-modal-data" data-xfield_id="'. $r->complaint_id . '" data-field_type="complaints"><span class="fa fa-eye"></span></button></span>';
                  } else {
                    $view = '';
                  }
                  // get company
                  $company = $this->Core_model->read_company_info($r->company_id);
                  if(!is_null($company)){
                    $comp_name = $company[0]->name;
                  } else {
                    $comp_name = '--';  
                  }
                  // get status
                  if($r->status==0): $status = '<span class="badge bg-red">'.$this->lang->line('xin_pending').'</span>';
                  elseif($r->status==1): $status = '<span class="badge bg-green">'.$this->lang->line('xin_accepted').'</span>'; else: $status = '<span class="badge bg-red">'.$this->lang->line('xin_rejected').'</span>';endif;
                  // info
                  $icomplaint_from = $complaint_from.'<br><small class="text-muted"><i>'.$this->lang->line('xin_description').': '.$r->description.'<i></i></i></small><br><small class="text-muted"><i>'.$status.'<i></i></i></small>';
                  $combhr = $view;
                  ?>
                                <tr>
                                    <td><?php echo $combhr;?></td>
                                    <td><?php echo $icomplaint_from;?></td>
                                    <td><?php echo $ol;?></td>
                                    <td><?php echo $r->title;?></td>
                                    <td><?php echo $complaint_date;?></td>
                                  </tr>
                                <?php } ?>  
                                </tbody>
                              </table>
                            </div>
                          </div>
                        </div>
                        </div>
                        <div class="tab-pane active core-current-tab <?php echo $get_animate;?>" id="left_warnings" style="display:none;">
                         <div class="box <?php echo $get_animate;?>">
                          <div class="box-header with-border">
                            <h3 class="box-title"> <?php echo $this->lang->line('xin_list_all');?> <?php echo $this->lang->line('left_warnings');?> </h3>
                          </div>
                          <?php $warning = $this->Warning_model->get_employee_warning($user_id); ?>
                          <div class="box-body">
                            <div class="box-datatable table-responsive">
                              <table class="datatables-demo table table-striped table-bordered xin_hris_table">
                                <thead>
                                  <tr>
                                    <th><?php echo $this->lang->line('xin_view');?></th>
                                    <th><?php echo $this->lang->line('xin_subject');?></th>
                                    <th><i class="fa fa-calendar"></i> <?php echo $this->lang->line('xin_warning_date');?></th>
                                    <th><i class="fa fa-user"></i> <?php echo $this->lang->line('xin_warning_by');?></th>
                                  </tr>
                                </thead>
                                <tbody>
                                <?php foreach($warning->result() as $r) { ?>
                                <?php
                  // get user > warning to
                  $user = $this->Core_model->read_user_info($r->warning_to);
                  // user full name
                  if(!is_null($user)){
                    $warning_to = $user[0]->first_name.' '.$user[0]->last_name;
                  } else {
                    $warning_to = '--'; 
                  }
                  // get user > warning by
                  $user_by = $this->Core_model->read_user_info($r->warning_by);
                  // user full name
                  if(!is_null($user_by)){
                    $warning_by = $user_by[0]->first_name.' '.$user_by[0]->last_name;
                  } else {
                    $warning_by = '--'; 
                  }
                  // get warning date
                  $warning_date = $this->Core_model->set_date_format($r->warning_date);
                      
                  // get status
                  if($r->status==0): $status = $this->lang->line('xin_pending');
                  elseif($r->status==1): $status = $this->lang->line('xin_accepted'); else: $status = $this->lang->line('xin_rejected'); endif;
                  // get warning type
                  $warning_type = $this->Warning_model->read_warning_type_information($r->warning_type_id);
                  if(!is_null($warning_type)){
                    $wtype = $warning_type[0]->type;
                  } else {
                    $wtype = '--';  
                  }
                  // get company
                  $company = $this->Core_model->read_company_info($r->company_id);
                  if(!is_null($company)){
                    $comp_name = $company[0]->name;
                  } else {
                    $comp_name = '--';  
                  }
                  
                  if(in_array('238',$role_resources_ids)) { //view
                    $view = '<span data-toggle="tooltip" data-placement="top" title="'.$this->lang->line('xin_view').'"><button type="button" class="btn icon-btn btn-xs btn-default waves-effect waves-light" data-toggle="modal" data-target=".view-modal-data" data-xfield_id="'. $r->warning_id . '" data-field_type="warning"><span class="fa fa-eye"></span></button></span>';
                  } else {
                    $view = '';
                  }
                  if($r->status==0): $status = '<span class="badge bg-orange">'.$this->lang->line('xin_pending').'</span>';
                  elseif($r->status==1): $status = '<span class="badge bg-green">'.$this->lang->line('xin_accepted').'</span>';else: $status = '<span class="badge bg-red">'.$this->lang->line('xin_rejected').'</span>'; endif;
                  
                  $combhr = $view;
                  
                  $iwarning_to = $warning_to.'<br><small class="text-muted"><i>'.$wtype.'<i></i></i></small><br><small class="text-muted"><i>'.$status.'<i></i></i></small>';
                  ?>
                                <tr>
                                    <td><?php echo $combhr;?></td>
                                    <td><?php echo $r->subject;?></td>
                                    <td><?php echo $warning_date;?></td>
                                    <td><?php echo $warning_by;?></td>
                                  </tr>
                                <?php } ?>  
                                </tbody>
                              </table>
                            </div>
                          </div>
                        </div>
                        </div>
                        
                    </div>  
                    </div> 
                </div>
              </div>
            </div>           
            
            <div class="tab-pane <?php echo $get_animate;?>" id="xin_payslips">
              <div class="box-body">
                <div class="row no-gutters row-bordered row-border-light">
                    <div class="col-md-12">
                         <div class="box <?php echo $get_animate;?>">
                          <div class="box-header with-border">
                            <h3 class="box-title"> <?php echo $this->lang->line('xin_list_all');?> <?php echo $this->lang->line('left_payment_history');?> </h3>
                          </div>
                          <?php $history = $this->Payroll_model->get_payroll_slip($user_id); ?>
                          <div class="box-body">
                            <div class="box-datatable table-responsive">
                              <table class="datatables-demo table table-striped table-bordered xin_hris_table" id="xin_hr_table">
                                <thead>
                                  <tr>
                                    <th><?php echo $this->lang->line('xin_action');?></th>
                                    <th><?php echo $this->lang->line('xin_payroll_net_payable');?></th>
                                    <th><?php echo $this->lang->line('xin_salary_month');?></th>
                                    <th><i class="fa fa-calendar"></i> <?php echo $this->lang->line('xin_payroll_date_title');?></th>
                                    <th><?php echo $this->lang->line('dashboard_xin_status');?></th>
                                  </tr>
                                </thead>
                                <tbody>
                                <?php foreach($history->result() as $r) { ?>
                                <?php
                  // get addd by > template
                  $user = $this->Core_model->read_user_info($r->employee_id);
                  // user full name
                  if(!is_null($user)){
                  $full_name = $user[0]->first_name.' '.$user[0]->last_name;
                  $emp_link = $user[0]->employee_id;              
                  $month_payment = date("F, Y", strtotime($r->salary_month));
                  
                  $p_amount = $this->Core_model->currency_sign($r->net_salary);
                  
                  // get date > created at > and format
                  $created_at = $this->Core_model->set_date_format($r->created_at);
                  // get designation
                  $designation = $this->Designation_model->read_designation_information($user[0]->designation_id);
                  if(!is_null($designation)){
                    $designation_name = $designation[0]->designation_name;
                  } else {
                    $designation_name = '--'; 
                  }
                  // department
                  $department = $this->Department_model->read_department_information($user[0]->department_id);
                  if(!is_null($department)){
                  $department_name = $department[0]->department_name;
                  } else {
                  $department_name = '--';  
                  }
                  $department_designation = $designation_name.' ('.$department_name.')';
                  // get company
                  $company = $this->Core_model->read_company_info($user[0]->company_id);
                  if(!is_null($company)){
                    $comp_name = $company[0]->name;
                  } else {
                    $comp_name = '--';  
                  }
                  // bank account
                  $bank_account = $this->Employees_model->get_employee_bank_account_last($user[0]->user_id);
                  if(!is_null($bank_account)){
                    $account_number = $bank_account[0]->account_number;
                  } else {
                    $account_number = '--'; 
                  }
                  $payslip = '<span data-toggle="tooltip" data-placement="top" title="'.$this->lang->line('xin_view').'"><a href="'.site_url().'admin/payroll/payslip/id/'.$r->payslip_key.'"><button type="button" class="btn icon-btn btn-xs btn-default waves-effect waves-light"><span class="fa fa-arrow-circle-right"></span></button></a></span><span data-toggle="tooltip" data-placement="top" title="'.$this->lang->line('xin_download').'"><a href="'.site_url().'admin/payroll/pdf_create/p/'.$r->payslip_key.'"><button type="button" class="btn icon-btn btn-xs btn-default waves-effect waves-light"><span class="fa fa-download"></span></button></a></span>';
                  
                $ifull_name = nl2br ($full_name."\r\n <small class='text-muted'><i>".$this->lang->line('xin_employees_id').': '.$emp_link."<i></i></i></small>\r\n <small class='text-muted'><i>".$department_designation.'<i></i></i></small>');
                  ?>
                                <tr>
                                    <td><?php echo $payslip;?></td>
                                    <td><?php echo $p_amount;?></td>
                                    <td><?php echo $month_payment;?></td>
                                    <td><?php echo $created_at;?></td>
                                    <td><?php echo $this->lang->line('xin_payroll_paid');?></td>
                                  </tr>
                                <?php } } ?>  
                                </tbody>
                              </table>
                            </div>
                          </div>                                        
                    </div>  
                    </div> 
                </div>
              </div>
            </div>

          </div>

       </div>
    </div>
  </div>
</div>

