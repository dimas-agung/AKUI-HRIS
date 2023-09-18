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


<style type="text/css">
.btn-group {
  margin-top:5px !important;
}
</style>
