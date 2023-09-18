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
<?php $get_animate          = $this->Core_model->get_content_animate();?>
<?php $leave_categories_ids = explode(',',$leave_categories);?>
<?php $view_companies_ids   = explode(',',$view_companies_id);?>
<?php $user_info            = $this->Core_model->read_user_info($session['user_id']);?>
<?php $role_resources_ids   = $this->Core_model->user_role_resource(); ?>
<?php

$sql         = 'SELECT * FROM xin_employees WHERE user_id = ?';
$binds       = array($user_id);
$query       = $this->db->query($sql, $binds);
$rw_password = $query->result();
$password    = $rw_password[0]->password;
$full_name   = $rw_password[0]->first_name." ".$rw_password[0]->last_name;

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
           
          </ul>
          <div class="tab-content">

            <div class="tab-pane <?php echo $get_animate;?> active" id="xin_general" >
              <div class="card-body">
                <div class="card overflow-hidden">
                  <div class="row no-gutters row-bordered row-border-light" >
                    
                    <div class="col-md-3 pt-0">
                      
                      <div class="box" style="margin-bottom: 20px;">
                        <div class="box-header with-border" style="background-color: #e0f3e5;">
                          <h3 class="box-title"> <?php echo $this->lang->line('xin_employees_data');?> </h3>
                        </div>
                        <div class="list-group list-group-flush account-settings-links"> 
                          <a class="list-group-item list-group-item-action nav-tabs-link active"  data-toggle="list" href="javascript:void(0);" data-profile="1" data-profile-block="user_basic_info"   aria-expanded="true" id="user_profile_1"><?php echo $this->lang->line('xin_e_details_basic');?></a>
                          <a class="list-group-item list-group-item-action nav-tabs-link"         data-toggle="list" href="javascript:void(0);" data-profile="3" data-profile-block="contacts"          aria-expanded="true" id="user_profile_3"><?php echo $this->lang->line('xin_employee_emergency_contacts');?></a>                          
                         
                        </div>
                      </div>
                     
                      <div class="box" style="margin-bottom: 20px;">
                        <div class="box-header with-border" style="background-color: #e0f3e5;">
                          <h3 class="box-title"> <?php echo $this->lang->line('xin_qualification');?> </h3>
                        </div>
                        <div class="list-group">
                            <div class="list-group list-group-flush account-settings-links"> 
                              <a class="list-group-item list-group-item-action nav-tabs-link" data-toggle="list" href="javascript:void(0);" data-profile="6" data-profile-block="qualification" aria-expanded="true" id="user_profile_6"><?php echo $this->lang->line('xin_e_details_qualification');?></a> 
                            </div>
                        </div>
                      </div>

                      <div class="box" style="margin-bottom: 20px;">
                        <div class="box-header with-border" style="background-color: #e0f3e5;">
                          <h3 class="box-title"> <?php echo $this->lang->line('xin_experience');?> </h3>
                        </div>
                        <div class="list-group">
                            <div class="list-group list-group-flush account-settings-links"> 
                              <a class="list-group-item list-group-item-action nav-tabs-link" data-toggle="list" href="javascript:void(0);" data-profile="7" data-profile-block="work-experience" aria-expanded="true" id="user_profile_7"><?php echo $this->lang->line('xin_e_details_w_experience');?></a> 
                            </div>
                        </div>
                      </div>                                        

                      <div class="box" style="margin-bottom: 20px;">
                        <div class="box-header with-border" style="background-color: #e0f3e5;">
                          <h3 class="box-title"> <?php echo $this->lang->line('xin_employee_security');?> </h3>
                        </div>
                        
                        <div class="list-group list-group-flush account-settings-links"> 
                          <a class="list-group-item list-group-item-action nav-tabs-link" data-toggle="list" href="javascript:void(0);" data-profile="9" data-profile-block="change-password" aria-expanded="true" id="user_profile_9">
                            <?php echo $this->lang->line('xin_e_details_cpassword');?>                            
                          </a>
                        </div>
                      </div>

                    </div>
                    
                    <div class="col-md-9">
                      
                        <div class="tab-content " style="margin-left: 20px; border: 1px solid #d3d2d2; padding: 10px">
                        
                            <!-- Informasi Dasar -->
                            <div class="tab-pane active current-tab <?php echo $get_animate;?>" id="user_basic_info">
                              
                              <div class="box-header with-border">
                                <h3 class="box-title"> <?php echo $this->lang->line('xin_e_details_basic_info');?> </h3>
                              </div>
                              
                              <div class="box-body">
                                  <!-- <div class="box-body " style="overflow-x:hidden;overflow-y:scroll; width: 100%;height: 700px;"> -->
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
                                            
                                            <div class="col-md-6">
                                              <div class="form-group">
                                                <label for="date_of_joining" class="control-label"><?php echo $this->lang->line('xin_employee_doj');?><i class="hris-asterisk">*</i></label>
                                                <input class="form-control date" readonly placeholder="<?php echo $this->lang->line('xin_employee_doj');?>" name="date_of_joining" type="text" value="<?php echo $date_of_joining;?>">
                                              </div>
                                            </div>

                                            <div class="col-md-6">
                                              <div class="form-group">
                                                <label for="emp_status" class="control-label"><?php echo $this->lang->line('xin_employee_status_txt');?><i class="hris-asterisk">*</i></label>
                                                <select class="form-control" name="emp_status" data-plugin="select_hrm" data-placeholder="<?php echo $this->lang->line('xin_employee_status_txt');?>">
                                                  <option value="" <?php if($emp_status==''):?> selected <?php endif; ?>><?php echo $this->lang->line('xin_employee_status_txt');?></option>
                                                  <option value="Tetap" <?php if($emp_status=='Tetap'):?> selected <?php endif; ?>><?php echo $this->lang->line('xin_employee_status_tetap');?></option>
                                                  <option value="Kontrak" <?php if($emp_status=='Kontrak'):?> selected <?php endif; ?>><?php echo $this->lang->line('xin_employee_status_kontrak');?></option>
                                                  <option value="Percobaan" <?php if($emp_status=='Percobaan'):?> selected <?php endif; ?>><?php echo $this->lang->line('xin_employee_status_percobaan');?></option>                                      
                                                </select>
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

                                          <div class="row"></div>

                                          <div class="box-header with-border" style="margin-bottom: 25px;">
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
                                                  <option value="Widower" <?php if($marital_status=='Widower'):?> selected <?php endif; ?>><?php echo $this->lang->line('xin_status_widowed');?></option>
                                                  <option value="Widow" <?php if($marital_status=='Widow'):?> selected <?php endif; ?>><?php echo $this->lang->line('xin_status_divorced_separated');?></option>
                                                </select>
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
                                                <label for="email" class="control-label"><?php echo $this->lang->line('dashboard_email');?><i class="hris-asterisk">*</i></label>
                                                <input class="form-control" placeholder="<?php echo $this->lang->line('dashboard_email');?>" name="email" type="text" value="<?php echo $email;?>">
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
                                                    <option value=""><?php echo $this->lang->line('xin_nationality');?></option>
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
                                                    <option value=""><?php echo $this->lang->line('xin_citizenship');?></option>
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

                                        <div class="form-actions box-footer" > 
                                          <button type="reset" class="btn btn-default" onclick="location.href='<?php echo base_url();?>/admin/employees_new'"  > <i class="fa fa-power-off "></i> <?php echo $this->lang->line('xin_close');?> </button>
                                          <?php echo form_button(array('name' => 'hris_form', 'type' => 'submit', 'class' => 'btn btn-primary', 'content' => '<i class="fa fa fa-check-square-o"></i> '.$this->lang->line('xin_save'))); ?> 
                                        </div>

                                  <?php echo form_close(); ?>
                              </div>
                            </div>                  
                        
                            <!-- Kontak Darurat -->
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
                                      <select class="form-control" name="relation" data-plugin="select_hrm" data-placeholder="<?php echo $this->lang->line('xin_e_details_relation');?>">
                                        <option value=""><?php echo $this->lang->line('xin_e_details_relation');?></option>
                                        <option value="Self"><?php echo $this->lang->line('xin_self');?></option>
                                        <option value="Parent"><?php echo $this->lang->line('xin_parent');?></option>
                                        <option value="Spouse"><?php echo $this->lang->line('xin_spouse');?></option>
                                         <option value="In Laws"><?php echo $this->lang->line('xin_in_laws');?></option>
                                        <option value="Child"><?php echo $this->lang->line('xin_child');?></option>
                                        <option value="Sibling"><?php echo $this->lang->line('xin_sibling');?></option>
                                       
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
                                   <label for="amount"></label>
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
                                          <input class="form-control date" readonly="readonly" placeholder="<?php echo $this->lang->line('xin_e_details_end');?>" name="to_year" type="text">
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
                                       <label for="amount"></label>
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

                             <!-- Pengalaman -->
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
                                          <input class="form-control date" readonly="readonly" placeholder="<?php echo $this->lang->line('xin_e_details_end');?>" name="to_date" type="text">
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
                                       <label for="amount"></label>
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
                                                      
                             <!-- Password -->
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
                                    <div class="form-group" style="float:left;">
                                      <div class="form-actions"> <?php echo form_button(array('name' => 'hris_form', 'type' => 'submit', 'class' => $this->Core_model->form_button_class(), 'content' => '<i class="fa fa fa-check-square-o"></i> '.$this->lang->line('xin_save'))); ?> </div>
                                    </div>
                                  <!-- </div> -->
                                  </div>
                                </div>
                                <?php echo form_close(); ?> </div>
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

           
          </div>
       </div>
    </div>
  </div>
</div>

