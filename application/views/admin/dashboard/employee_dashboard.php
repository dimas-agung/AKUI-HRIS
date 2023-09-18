<?php 
$session = $this->session->userdata('username');
$user_info = $this->Core_model->read_user_info_detail($session['user_id']);
$theme = $this->Core_model->read_theme_info(1);
if($user_info[0]->profile_picture!='' && $user_info[0]->profile_picture!='no file') {
  $lde_file = base_url().'uploads/profile/'.$user_info[0]->profile_picture;
} else { 
  if($user_info[0]->gender=='Male') {  
    $lde_file = base_url().'uploads/profile/default_male.jpg'; 
  } else {  
    $lde_file = base_url().'uploads/profile/default_female.jpg';
  }
}
$last_login =  new DateTime($user_info[0]->last_login_date);
// get designation
$designation = $this->Designation_model->read_designation_information($user_info[0]->designation_id);
if(!is_null($designation)){
  $designation_name = $designation[0]->designation_name;
} else {
  $designation_name = '--'; 
}
$role_user = $this->Core_model->read_user_role_info($user_info[0]->user_role_id);
if(!is_null($role_user)){
  $role_resources_ids = explode(',',$role_user[0]->role_resources);
} else {
  $role_resources_ids = explode(',',0); 
}
?>
<?php $get_animate = $this->Core_model->get_content_animate();?>
<?php $system = $this->Core_model->read_setting_info(1);?>

<div class="box-widget widget-user-2">
  <div class="widget-user-header">
    <h4 class="widget-user-username welcome-hris-user"><?php echo $this->lang->line('xin_title_wcb');?>, <?php echo $user_info[0]->first_name.' '.$user_info[0]->last_name;?>!</h4>
    <h5 class="widget-user-desc welcome-hris-user-text"> 
       <i class="fa fa-briefcase"></i>&nbsp;<?php echo $user_info[0]->department_name.', '.$user_info[0]->designation_name.' - '.$user_info[0]->company_name ;?>
     <div class="pull-right" >
      <i class="fa fa-clock-o"></i>&nbsp;
      <span class="pull-right" id="clock"></span>
    </div>
    </h5>
  </div>
</div>

<div class="row <?php echo $get_animate;?>">

 <?php if(in_array('14',$role_resources_ids)) { ?>

    <?php if($system[0]->module_awards=='true'){?>

      <div class="col-sm-6 col-lg-3">
          <div class="card p-3">
              <div class="d-flex align-items-center">
                  <span class="stamp-hris-4 stamp-hris-md bg-hris-secondary mr-3">
                      <i class="fa fa-trophy"></i>
                  </span>
                  <div>
                      <h5 class="mb-1">
                        <b>                        
                      
                        <small><?php echo $this->lang->line('left_awards');?> </small>                       
                        </b>
                      </h5>
                      <small class="text-muted">
                        <span class="info-box-text">
                          <span class=""> 
                            <a class="text-muted" href="<?php echo site_url('admin/awards/');?>">
                              <?php echo $this->lang->line('xin_view');?> (<?php echo $this->Core_model->total_employee_awards_dash();?>)
                             </a>
                          </span>
                        </span>
                      </small>
                  </div>
              </div>
          </div>
      </div>

    <?php } else { ?>
    
      <div class="col-xl-6 col-md-3 col-12 hr-mini-state"> <a class="text-muted" href="<?php echo site_url('admin/timesheet/attendance/');?>">
        <div class="info-box hrsalle-mini-stat"> <span class="info-box-icon bg-primary"><i class="fa fa-clock-o"></i></span>
          <div class="info-box-content"> 
            <span class="info-box-number"><?php echo $this->lang->line('dashboard_attendance');?></span> 
            <span class="info-box-text"><?php echo $this->lang->line('xin_view');?></span> </div>
          <!-- /.info-box-content --> 
        </div>
        </a> 
        <!-- /.info-box --> 
      </div>
    
    <?php } ?>

  <?php } ?>
  
  <?php if(in_array('37',$role_resources_ids)) { ?>
    <div class="col-sm-6 col-lg-3">
          <div class="card p-3">
              <div class="d-flex align-items-center">
                  <span class="stamp-hris-4 stamp-hris-md bg-primary mr-3">
                      <i class="fa fa-money"></i>
                  </span>
                  <div>
                      <h5 class="mb-1">
                        <b>                     
                        
                        <small><?php echo $this->lang->line('left_payslips');?> </small>                       
                        </b>
                      </h5>
                      <small class="text-muted">
                        <span class="info-box-text">
                          <span class=""> 
                            <a class="text-muted" href="<?php echo site_url('admin/payroll/payment_history/');?>">
                              <?php echo $this->lang->line('xin_view');?> 
                             </a>
                          </span>
                        </span>
                      </small>
                  </div>
              </div>
          </div>
    </div> 
  <?php } ?> 

  <!-- <?php if(in_array('46',$role_resources_ids)) { ?>
    <div class="col-sm-6 col-lg-3">
          <div class="card p-3">
              <div class="d-flex align-items-center">
                  <span class="stamp-hris-4 stamp-hris-md bg-purple mr-3">
                      <i class="fa fa-calendar"></i>
                  </span>
                  <div>
                      <h5 class="mb-1">
                        <b>                     
                        
                        <small><?php echo $this->lang->line('left_leave');?> <?php echo $this->lang->line('xin_performance_management');?> </small>                       
                        </b>
                      </h5>
                      <small class="text-muted">
                        <span class="info-box-text">
                          <span class=""> 
                            <a class="text-muted" href="<?php echo site_url('admin/permission/leave/');?>">
                              <?php echo $this->lang->line('xin_view');?> 
                             </a>
                          </span>
                        </span>
                      </small>
                  </div>
              </div>
          </div>
    </div>
  <?php } ?> -->

  <?php if(in_array('17',$role_resources_ids)) { ?>

      <div class="col-sm-6 col-lg-3">
          <div class="card p-3">
              <div class="d-flex align-items-center">
                  <span class="stamp-hris-4 stamp-hris-md bg-red mr-3">
                      <i class="fa fa-plane"></i>
                  </span>
                  <div>
                      <h5 class="mb-1">
                        <b>                        
                       
                        <small> <?php echo $this->lang->line('xin_travel');?>  </small>                       
                        </b>
                      </h5>
                      <small class="text-muted">
                        <span class="info-box-text">
                          <span class=""> 
                            <a class="text-muted" href="<?php echo site_url('admin/travel/');?>">
                              <?php echo $this->lang->line('xin_view');?> (<?php echo $this->Core_model->total_employee_travel_dash();?>)
                             </a>
                          </span>
                        </span>
                      </small>
                  </div>
              </div>
          </div>
      </div>         
  <?php } ?> 

  

</div>
<?php
$att_date =  date('d-M-Y');
$attendance_date = date('d-M-Y');
// get office shift for employee
$get_day = strtotime($att_date);
$day = date('l', $get_day);
$strtotime = strtotime($attendance_date);
$new_date = date('d-M-Y', $strtotime);
// office shift
$u_shift = $this->Timesheet_model->read_office_shift_information($user_info[0]->office_shift_id);

// get clock in/clock out of each employee
if($day == 'Monday') {
  if($u_shift[0]->monday_in_time==''){
    $office_shift = $this->lang->line('dashboard_today_monday_shift');
  } else {
    $in_time =  new DateTime($u_shift[0]->monday_in_time. ' ' .$attendance_date);
    $out_time =  new DateTime($u_shift[0]->monday_out_time. ' ' .$attendance_date);
    $clock_in = $in_time->format('h:i a');
    $clock_out = $out_time->format('h:i a');
    $office_shift = $this->lang->line('dashboard_office_shift').': <br/> '.$clock_in.' '.$this->lang->line('dashboard_to').' '.$clock_out;
  }
} else if($day == 'Tuesday') {
  if($u_shift[0]->tuesday_in_time==''){
    $office_shift = $this->lang->line('dashboard_today_tuesday_shift');
  } else {
    $in_time =  new DateTime($u_shift[0]->tuesday_in_time. ' ' .$attendance_date);
    $out_time =  new DateTime($u_shift[0]->tuesday_out_time. ' ' .$attendance_date);
    $clock_in = $in_time->format('h:i a');
    $clock_out = $out_time->format('h:i a');
    $office_shift = $this->lang->line('dashboard_office_shift').': <br/>'.$clock_in.' '.$this->lang->line('dashboard_to').' '.$clock_out;
  }
} else if($day == 'Wednesday') {
  if($u_shift[0]->wednesday_in_time==''){
    $office_shift = $this->lang->line('dashboard_today_wednesday_shift');
  } else {
    $in_time =  new DateTime($u_shift[0]->wednesday_in_time. ' ' .$attendance_date);
    $out_time =  new DateTime($u_shift[0]->wednesday_out_time. ' ' .$attendance_date);
    $clock_in = $in_time->format('h:i a');
    $clock_out = $out_time->format('h:i a');
    $office_shift = $this->lang->line('dashboard_office_shift').': <br/>'.$clock_in.' '.$this->lang->line('dashboard_to').' '.$clock_out;
  }
} else if($day == 'Thursday') {
  if($u_shift[0]->thursday_in_time==''){
    $office_shift = $this->lang->line('dashboard_today_thursday_shift');
  } else {
    $in_time =  new DateTime($u_shift[0]->thursday_in_time. ' ' .$attendance_date);
    $out_time =  new DateTime($u_shift[0]->thursday_out_time. ' ' .$attendance_date);
    $clock_in = $in_time->format('h:i a');
    $clock_out = $out_time->format('h:i a');
    $office_shift = $this->lang->line('dashboard_office_shift').': <br/>'.$clock_in.' '.$this->lang->line('dashboard_to').' '.$clock_out;
  }
} else if($day == 'Friday') {
  if($u_shift[0]->friday_in_time==''){
    $office_shift = $this->lang->line('dashboard_today_friday_shift');
  } else {
    $in_time =  new DateTime($u_shift[0]->friday_in_time. ' ' .$attendance_date);
    $out_time =  new DateTime($u_shift[0]->friday_out_time. ' ' .$attendance_date);
    $clock_in = $in_time->format('h:i a');
    $clock_out = $out_time->format('h:i a');
    $office_shift = $this->lang->line('dashboard_office_shift').': <br/>'.$clock_in.' '.$this->lang->line('dashboard_to').' '.$clock_out;
  }
} else if($day == 'Saturday') {
  if($u_shift[0]->saturday_in_time==''){
    $office_shift = $this->lang->line('dashboard_today_saturday_shift');
  } else {
    $in_time =  new DateTime($u_shift[0]->saturday_in_time. ' ' .$attendance_date);
    $out_time =  new DateTime($u_shift[0]->saturday_out_time. ' ' .$attendance_date);
    $clock_in = $in_time->format('h:i a');
    $clock_out = $out_time->format('h:i a');
    $office_shift = $this->lang->line('dashboard_office_shift').': <br/>'.$clock_in.' '.$this->lang->line('dashboard_to').' '.$clock_out;
  }
} else if($day == 'Sunday') {
  if($u_shift[0]->sunday_in_time==''){
    $office_shift = $this->lang->line('dashboard_today_sunday_shift');
  } else {
    $in_time =  new DateTime($u_shift[0]->sunday_in_time. ' ' .$attendance_date);
    $out_time =  new DateTime($u_shift[0]->sunday_out_time. ' ' .$attendance_date);
    $clock_in = $in_time->format('h:i a');
    $clock_out = $out_time->format('h:i a');
    $office_shift = $this->lang->line('dashboard_office_shift').': <br/>'.$clock_in.' '.$this->lang->line('dashboard_to').' '.$clock_out;
  }
}
?>
<?php $sys_arr = explode(',',$system[0]->system_ip_address); ?>
<?php $attendances = $this->Timesheet_model->attendance_time_checks($user_info[0]->user_id); $dat = $attendances->result();?>
<?php
$bgatt = 'bg-success';
if($attendances->num_rows() < 1) {
  $bgatt = 'bg-success';
} else {
  $bgatt = 'bg-danger';
}
?>



<div class="row <?php echo $get_animate;?>">
  <div class="col-md-4" >
    <div class="nav-tabs-custom">
      <div class="tab-content">
        <div class="box-widget widget-user"> 
            <!-- Add the bg color to the header using any of the bg-* classes -->
            <div class="widget-user-header <?php echo $bgatt;?> bg-darken-2">
              <h3 class="widget-user-username"><?php echo $user_info[0]->first_name. ' ' .$user_info[0]->last_name;?> </h3>
              <h5 class="widget-user-desc"><?php echo $designation_name;?></h5>
            </div>
            <div class="widget-user-image"> <img class="img-circle" src="<?php echo $lde_file;?>" alt="User Avatar"> </div>
            
            <br/><br/>

            <div class="box-footer-dashboard">
              <div class="row">
                <div class="col-sm-12">
                  <div class="description-block">
                    <p class="text-muted pb-0-6"><?php echo $this->lang->line('dashboard_last_login');?>:<br/> 
                      <?php echo $this->Core_model->set_date_format($user_info[0]->last_login_date).' '.$last_login->format('h:i a');?></p>
                    <p class="text-muted pb-0-6"><?php echo $office_shift;?></p>
                  </div>                 
                </div>
              </div>
            </div>           
        </div>

      </div>
    </div>
    <!-- Widget: user widget style 1 --> 
  </div>
  <!-- /.widget-user -->
  <?php if(in_array('45',$role_resources_ids)) { ?>
 
  <?php } else {?>
  
  <div class="col-xl-8 col-lg-8">
    <div class="box">
      <div class="box-header">
        <h3 class="box-title"><?php echo $this->lang->line('dashboard_personal_details');?></h3>
      </div>
      <div class="box-body px-1">
        <div id="recent-buyers" class="list-group scrollable-container height-350 position-relative">
          <div class="table-responsive" data-pattern="priority-columns">
            <table border="0" width="" class="table table-striped m-md-b-0">
              <tbody>
                <tr>
                  <th scope="row" class="kanan"><?php echo $this->lang->line('dashboard_employee_id');?></th>
                  <td>: &nbsp; <?php echo $employee_id;?></td>
                </tr>

                <tr>
                  <th width ="25%" scope="row" class="kanan"><?php echo $this->lang->line('dashboard_fullname');?></th>
                  <td>: &nbsp; <?php echo $first_name.' '.$last_name;?></td>
                </tr>
                <tr>
                  <th scope="row" class="kanan"><?php echo $this->lang->line('left_department');?></th>
                  <td>: &nbsp; <?php echo $department_name;?></td>
                </tr>

                
                <tr>
                  <th scope="row" class="kanan"><?php echo $this->lang->line('dashboard_designation');?></th>
                  <td>: &nbsp; <?php echo $designation_name;?></td>
                </tr>
                
                <tr>
                  <th scope="row" class="kanan"><?php echo $this->lang->line('xin_joining_date');?></th>
                  <td>: &nbsp; <?php echo $this->Core_model->set_date_format($date_of_joining);?></td>
                </tr>
                <tr>
                  <th scope="row" class="kanan"><?php echo $this->lang->line('xin_clcontact_person');?></th>
                  <td>: &nbsp; <?php echo $contact_no;?></td>
                </tr>
                <tr>
                  <th scope="row" class="kanan"><?php echo $this->lang->line('xin_employee_gender');?></th>
                  <td>: &nbsp; <?php echo $gender;?></td>
                </tr>
                
                <tr>
                  <th scope="row" class="kanan"><?php echo $this->lang->line('xin_employee_mstatus');?></th>
                  <td>: &nbsp; <?php echo $marital_status;?></td>
                </tr>
                <tr>
                  <th scope="row" class="kanan"><?php echo $this->lang->line('xin_address');?></th>
                  <td>: &nbsp; <?php echo $address;?></td>
                </tr>
                <tr>
                  <th scope="row" class="kanan"><?php echo $this->lang->line('xin_city');?></th>
                  <td>: &nbsp; <?php echo $city;?></td>
                </tr>

              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div> 

  <?php } ?>

</div>



<?php if($theme[0]->dashboard_calendar == 'true'):?>
<?php $this->load->view('admin/calendar/calendar_hr');?>
<?php endif; ?>

<style type="text/css">
.btn-group {
  margin-top:5px !important;
}
</style>