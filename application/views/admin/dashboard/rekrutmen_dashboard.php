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

<div class="row" >
    <div class="col-xl-6 col-md-3 col-12 hr-mini-state">
     
        <div class="info-box hrsalle-mini-stat"> <span class="info-box-icon bg-green"><i class="fa fa-users"></i></span>
            <div class="info-box-content"> 
              <span class="info-box-number">
                TOTAL KARYAWAN AKTIF           
              </span> 
              <span class="info-box-text">
                  <span class="badge badge-primary"> 
                     Total  <?php echo $this->Employees_model->get_total_employees();?> 
                  </span>
                  
                  <span class="badge badge-info"> 
                     Pria <?php echo active_employees_male();?> 
                  </span>
                  
                  <span class="ml-2"> 
                    <span class="badge bg-red"> 
                      Perempuan <?php echo active_employees_female();?> 
                    </span>
                  </span> 
              </span> 
            </div>
        </div>
        
    </div>
    <div class="col-xl-6 col-md-3 col-12 hr-mini-state">
      
        <div class="info-box hrsalle-mini-stat"> <span class="info-box-icon bg-green"><i class="fa fa-users"></i></span>
            <div class="info-box-content"> 
              <span class="info-box-number">
                 PT AKUI BIRD NEST INDONESIA             
              </span> 
              <span class="info-box-text">
                  <span class="badge badge-primary"> 
                     Total <?php echo $this->Employees_model->get_total_employees_1();?>  
                  </span>
                  <span class="badge badge-info"> 
                     Pria <?php echo active_employees_male_1();?> 
                  </span>
                  <span class="ml-2"> 
                    <span class="badge bg-red"> 
                      Perempuan <?php echo active_employees_female_1();?> 
                    </span>
                  </span> 
              </span> 
            </div>
        </div>
         
    </div>
    <div class="col-xl-6 col-md-3 col-12 hr-mini-state">
     
        <div class="info-box hrsalle-mini-stat"> <span class="info-box-icon bg-green"><i class="fa fa-users"></i></span>
            <div class="info-box-content"> 
              <span class="info-box-number">
                PT ORIGINAL BERKAH INDONESIA              
              </span> 
              <span class="info-box-text">
                  <span class="badge badge-primary"> 
                     Total  <?php echo $this->Employees_model->get_total_employees_2();?>  
                  </span>
                  <span class="badge badge-info"> 
                     Pria <?php echo active_employees_male_2();?> 
                  </span>
                  <span class="ml-2"> 
                    <span class="badge bg-red"> 
                      Perempuan <?php echo active_employees_female_2();?> 
                    </span>
                  </span> 
              </span> 
            </div>
        </div>
          
    </div>
    <div class="col-xl-6 col-md-3 col-12 hr-mini-state">
      <a class="text-muted" href="<?php echo site_url('admin/overtime');?>">
        <div class="info-box hrsalle-mini-stat"> <span class="info-box-icon bg-green"><i class="fa fa-users"></i></span>
            <div class="info-box-content"> 
              <span class="info-box-number">
                PT WALET ABDILLAH JABLI             
              </span> 
              <span class="info-box-text">
                  <span class="badge badge-primary"> 
                     Total   <?php echo $this->Employees_model->get_total_employees_3();?> 
                  </span>
                  <span class="badge badge-info"> 
                     Pria <?php echo active_employees_male_3();?> 
                    </span>
                  <span class="ml-2"> 
                    <span class="badge bg-red"> 
                      Perempuan <?php echo active_employees_female_3();?> 
                    </span>
                  </span> 
              </span> 
            </div>
        </div>
      </a>    
    </div>
</div>

<div class="row">
  <div class="col-xs-12 col-md-6 col-sm-12">
    <div class="box">
      <div class="box-header with-border">
        <h3 class="box-title">
          <i class="fa fa-bar-chart-o"></i> PT AKUI BIRDNEST INDONESIA                       
        </h3>
        <div class="pull-right"> 
           Per Departemen <?php echo date('Y');?>
        </div>
      </div>
      <div class="box-body">
        <div class="box-block">
          <div class="col-xs-12 col-md-12 col-sm-12">
            <canvas id="employee_department_birdnest" height="390" width="" style="display: block;  height: 398px;"></canvas>
            <!-- <canvas id="employee_department_trading" height="390" width="" style="display: block;  height: 398px;"></canvas> -->
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="col-xs-12 col-md-6 col-sm-12">
    <div class="box">
      <div class="box-header with-border">
        <h3 class="box-title">
          <i class="fa fa-bar-chart-o"></i> PT ORIGINAL BERKAH INDONESIA                      
        </h3>
         <div class="pull-right"> 
           Per Departemen <?php echo date('Y');?>
        </div>
      </div>
      <div class="box-body">
        <div class="box-block">
          <div class="col-xs-12 col-md-12 col-sm-12">
            <canvas id="employee_department_trading" height="100" width="" style="display: block;  height: 100px;"></canvas>
            <!-- <canvas id="employee_department_trading" height="390" width="" style="display: block;  height: 398px;"></canvas> -->
          </div>
        </div>
      </div>
    </div>
    <div class="box">
      <div class="box-header with-border">
        <h3 class="box-title">
          <i class="fa fa-bar-chart-o"></i> PT WALET ABDILLAH JABLI                      
        </h3>
         <div class="pull-right"> 
           Per Departemen <?php echo date('Y');?>
        </div>
      </div>
      <div class="box-body">
        <div class="box-block">
          <div class="col-xs-12 col-md-12 col-sm-12">
            <canvas id="employee_department_asa" height="200" width="" style="display: block;  height: 205px;"></canvas>
            <!-- <canvas id="employee_department_trading" height="390" width="" style="display: block;  height: 398px;"></canvas> -->
          </div>
        </div>
      </div>
    </div>
  </div> 
 
</div> 



<div class="row" style="display: none;">
  <div class="col-md-6">
    <div class="box box-info">
      <div class="box-header with-border">
        <h3 class="box-title"><i class="fa fa-tags"></i> <?php echo $this->lang->line('xin_latest_leave');?></h3>
        <div class="box-tools pull-right"> <a href="<?php echo site_url('admin/permission/leave/');?>">
            <button type="button" class="btn btn-xs btn-primary"> <span class="fa fa-eye"></span> <?php echo $this->lang->line('xin_view_all');?></button>
            </a> </div>
      </div>
      <div class="box-body">
        <table class="table table-striped table-bordered">
          <tbody>
            <tr>
                <th><?php echo $this->lang->line('xin_leave_type');?></th>
                <th><?php echo $this->lang->line('xin_employee');?></th>
                <th><i class="fa fa-calendar"></i> <?php echo $this->lang->line('xin_leave_duration');?></th>
                <th>Tanggal<br>Pengajuan</th>
               
            </tr>
            <?php $role_resources_ids = $this->Core_model->user_role_resource(); foreach(total_last_leaves() as $ls_leaves):?>
                <?php
                         // get start date and end date
                $user = $this->Core_model->read_user_info_detail($ls_leaves->employee_id);
                if(!is_null($user)){
                  $full_name = $user[0]->first_name. ' '.$user[0]->last_name.' <br><i class="fa fa-briefcase"></i> '.$user[0]->designation_name;
                } else {
                  $full_name = '--';  
                }
                 
                // get leave type
                $leave_type = $this->Timesheet_model->read_leave_type_information($ls_leaves->leave_type_id);
                if(!is_null($leave_type)){
                  $type_name = $leave_type[0]->type_name;
                } else {
                  $type_name = '--';  
                }
                 
                $datetime1 = new DateTime($ls_leaves->from_date);
                $datetime2 = new DateTime($ls_leaves->to_date);
                $interval = $datetime1->diff($datetime2);
                if(strtotime($ls_leaves->from_date) == strtotime($ls_leaves->to_date)){
                  $no_of_days =1;
                } else {
                  $no_of_days = $interval->format('%a') + 1;
                }
                $applied_on = $this->Core_model->set_date_format($ls_leaves->applied_on);
                 /*$duration = $this->Core_model->set_date_format($ls_leaves->from_date).' '.$this->lang->line('dashboard_to').' '.$this->Core_model->set_date_format($ls_leaves->to_date).'<br>'.$this->lang->line('xin_hris_total_days').': '.$no_of_days;*/
                
                 if($ls_leaves->is_half_day == 1){
                $duration = $this->Core_model->set_date_format($ls_leaves->from_date).' '.$this->lang->line('dashboard_to').' '.$this->Core_model->set_date_format($ls_leaves->to_date).'<br><i class="fa fa-angle-double-right"></i>  '.$this->lang->line('xin_hris_total_days').': '.$this->lang->line('xin_hr_leave_half_day');
                } else {
                  $duration = $this->Core_model->set_date_format($ls_leaves->from_date).' '.$this->lang->line('dashboard_to').' '.$this->Core_model->set_date_format($ls_leaves->to_date).'<br><i class="fa fa-angle-double-right"></i> '.$this->lang->line('xin_hris_total_days').': '.$no_of_days;
                }
                
                if($ls_leaves->status==1): $status = '<span class="badge bg-orange">'.$this->lang->line('xin_pending').'</span>';
                elseif($ls_leaves->status==2): $status = '<span class="badge bg-green">'.$this->lang->line('xin_approved').'</span>';
                elseif($ls_leaves->status==4): $status = '<span class="badge bg-green">'.$this->lang->line('xin_role_first_level_approved').'</span>';
                else: $status = '<span class="badge bg-red">'.$this->lang->line('xin_rejected').'</span>'; endif;
                
                $itype_name = $type_name.'<br><small class="text-muted"><i>'.$this->lang->line('xin_reason').': '.$ls_leaves->reason.'<i></i></i></small>';
                ?>
            <tr>
                <td><a href="<?php echo site_url('admin/permission/leave_details/id/').$ls_leaves->leave_id.'/';?>"><?php echo $type_name;?></a></td>
                <td><?php echo $full_name;?></td>
                <td><?php echo $duration;?></td>
                <td><center><?php echo $applied_on;?></center></td>
                
            </tr>
            <?php endforeach;?>
            </tbody>
        </table>
      </div>
    </div>
  </div>

  <div class="col-md-6">
  
    <div class="row">
        <div class="col-md-12">
          <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title"><i class="fa fa-child"></i> Hari libur bulan ini </h3>
              <div class="box-tools pull-right"> <a href="<?php echo site_url('admin/pengaturan/holidays/');?>">
                <button type="button" class="btn btn-xs btn-primary"> <span class="fa fa-eye"></span> <?php echo $this->lang->line('xin_view_all');?></button>
                </a> </div>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
             <table class="table table-striped table-bordered">
              <tbody>
                <tr>
                    <th><?php echo $this->lang->line('xin_event_name');?></th>
                    <th>Tanggal<br>Mulai</th>
                    <th>Tanggal<br>Sampai</th>
                </tr>
                   <?php $role_resources_ids = $this->Core_model->user_role_resource();  ?>

                   <?php if (count(total_last_holidays()) > 0 ){ ?>
                          <?php    foreach(total_last_holidays() as $ls_holidays):
                          ?>
                          
                          <?php                           
                             $sdate = $this->Core_model->set_date_format($ls_holidays->start_date);
                             $edate = $this->Core_model->set_date_format($ls_holidays->end_date);
                          ?>
                      <tr>
                          <td><?php echo $ls_holidays->event_name;?></td>
                          <td width="17%"><center><?php echo $sdate;?></center></td>
                          <td width="17%"><center><?php echo $edate;?></center></td>
                      </tr>
                      <?php endforeach;?>
                  <?php } else{ ?>
                    <tr>
                          <td colspan="3"><div class="box-header bg-gray text-center">
                            <h3 class="box-title"> <i class="fa fa-info-circle"></i> Tidak ada hari libur bulan ini </h3>
                          </div></td>
                         
                      </tr>
                  <?php } ?>
                </tbody>
            </table>    
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
        </div>
        <div class="col-md-12">
          <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title"><i class="fa fa-tasks"></i> Lembur Terbaru</h3>
              <div class="box-tools pull-right"> <a href="<?php echo site_url('admin/overtime/');?>">
                <button type="button" class="btn btn-xs btn-primary"> <span class="fa fa-eye"></span> <?php echo $this->lang->line('xin_view_all');?></button>
                </a> </div>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <table class="table table-striped table-bordered">
              <tbody>
                <tr>
                    <th><?php echo $this->lang->line('xin_employee');?></th>
                    <th>Durasi<br>Lembur</th>
                    <th>Tanggal<br>Lembur</th>
                </tr>
                 <?php if (count(total_last_overtime_request()) > 0 ){ ?>

                      <?php foreach(total_last_overtime_request() as $ls_overtime):?>
                          <?php
                                  // total work
                          $in_time = new DateTime($ls_overtime->clock_in_m);
                          $out_time = new DateTime($ls_overtime->clock_out_m);
                          
                          $employee_id = $this->Core_model->read_user_info_detail($ls_overtime->employee_id);  
                          if(!is_null($employee_id)) {
                            $full_name = $employee_id[0]->first_name.' '.$employee_id[0]->last_name.' ('.$employee_id[0]->designation_name.')';
                          } else {
                            $full_name = '';
                          }
                          
                          
                          $clock_in = $in_time->format('h:i a');      
                          // attendance date
                          $att_date_in = explode(' ',$ls_overtime->clock_in_m);
                          $att_date_out = explode(' ',$ls_overtime->clock_out_m);
                          $request_date = $this->Core_model->set_date_format($ls_overtime->overtime_date);
                          $cin_date = $clock_in;
                          if($ls_overtime->clock_out_m=='') {
                            $cout_date = '-';
                            $total_time = '-';
                          } else {
                            $clock_out = $out_time->format('h:i a');
                            $interval = $in_time->diff($out_time);
                            $hours  = $interval->format('%h');
                            $minutes = $interval->format('%i');     
                            $total_time = $hours ."h ".$minutes."m";
                            $cout_date = $clock_out;
                          }
                          
                         
                            $status =$ls_overtime->description;
                         
                          
                          ?>
                      <tr>
                          <td><?php echo $full_name;?><br><i class="fa fa-calendar-plus-o"></i> <?php echo $ls_overtime->description;?></td>
                          <td width="17%"><center><?php echo $total_time;?></center></td>
                          <td width="17%"><center><?php echo $request_date;?></center></td>
                      </tr>
                      <?php endforeach;?>
                <?php } else{ ?>
                    <tr>
                          <td colspan="3"><div class="box-header bg-gray text-center">
                            <h3 class="box-title"> <i class="fa fa-info-circle"></i> Tidak ada lembur baru</h3>
                          </div></td>
                         
                      </tr>
                  <?php } ?>
                </tbody>
            </table>   
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
        </div>
    </div>
  </div>
</div>
<style type="text/css">
.btn-group {
  margin-top:5px !important;
}
</style>
