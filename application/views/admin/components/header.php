<?php
$session = $this->session->userdata('username');
$system = $this->Core_model->read_setting_info(1);
$company_info = $this->Core_model->read_company_setting_info(1);
$user = $this->Core_model->read_employee_info($session['user_id']);
$theme = $this->Core_model->read_theme_info(1);
?>
<?php $site_lang = $this->load->helper('language');?>
<?php $wz_lang = $site_lang->session->userdata('site_lang');?>
<?php
if(!empty($wz_lang)):
  $lang_code = $this->Core_model->get_language_info($wz_lang);
  $flg_icn = $lang_code[0]->language_flag;
  $flg_icn = '<img src="'.base_url().'uploads/languages_flag/'.$flg_icn.'">';
elseif($system[0]->default_language!=''):
  $lang_code = $this->Core_model->get_language_info($system[0]->default_language);
  $flg_icn = $lang_code[0]->language_flag;
  $flg_icn = '<img src="'.base_url().'uploads/languages_flag/'.$flg_icn.'">';
else:
  $flg_icn = '<img src="'.base_url().'uploads/languages_flag/gb.gif">'; 
endif;
?>
<?php
$role_user = $this->Core_model->read_user_role_info($user[0]->user_role_id);
if(!is_null($role_user)){
  $role_resources_ids = explode(',',$role_user[0]->role_resources);
} else {
  $role_resources_ids = explode(',',0); 
}
//$designation_info = $this->Core_model->read_designation_info($user_info[0]->designation_id);
// set color
if($theme[0]->is_semi_dark==1):
  $light_cls = 'navbar-semi-dark navbar-shadow';
  $ext_clr = '';
else:
  $light_cls = 'navbar-dark';
  $ext_clr = $theme[0]->top_nav_dark_color;
endif;
// set layout / fixed or static
if($theme[0]->boxed_layout=='true'){
  $lay_fixed = 'container boxed-layout';
} else {
  $lay_fixed = '';
}
if($theme[0]->animation_style == '') {
  $animated = 'animated flipInY';
} else {
  $animated = 'animated '.$theme[0]->animation_style;
}
?>
<header class="main-header">
  <!-- Logo -->
  <a href="<?php echo site_url('admin/dashboard/');?>" class="logo">
    <!-- mini logo for sidebar mini 50x50 pixels -->
    <span class="logo-mini"><b><img alt="HRIS" src="<?php echo base_url();?>uploads/logo/logo_mini.png" class="brand-logo" style="width:32px;"></b></span>
    <!-- logo for regular state and mobile devices -->
    <span class="logo-lg"><img alt="HRIS" src="<?php echo base_url();?>uploads/logo/logo_1608171391.png" class="brand-logo" style="width:160px;"> </span>
  </a>
  <!-- Header Navbar: style can be found in header.less -->
  <nav class="navbar navbar-static-top">
    
    <!-- Sidebar toggle button-->
    <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
      <span class="sr-only">Toggle navigation</span>&nbsp;
        <?php if($this->router->fetch_class() !='dashboard' && $this->router->fetch_class() !='chat' && $this->router->fetch_class() !='profile'){?>
      <span class="pull-right sidebar-jam" id="clock"></span>
       <?php } ?> 
    </a>

    <div class="navbar-custom-menu">
      <ul class="nav navbar-nav">
        <!-- Messages: style can be found in dropdown.less-->
       

    <?php  if(in_array('90',$role_resources_ids)) { ?>
         <?php 

            $leave_count = 0; $sick_count = 0; $izin_count = 0; 

            $leaveapp    = $this->Core_model->get_notify_leave_applications();                
            $sickapp     = $this->Core_model->get_notify_sick_applications();
            $izinapp     = $this->Core_model->get_notify_izin_applications();
            // $contractapp     = $this->Core_model->get_notify_contract_applications();
            
            // count
            $leave_count = $this->Core_model->count_notify_leave_applications();
            $sick_count  = $this->Core_model->count_notify_sick_applications();
            $izin_count  = $this->Core_model->count_notify_izin_applications();

             // $contract_count  = $this->Core_model->count_notify_contract_applications();
         ?>

        <li class="dropdown messages-menu">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="true">
            <i class="fa fa-flag"></i>
            <span class="label label-success"><?php echo $leave_count;?></span>
          </a>

          <?php if($leave_count > 0 ){?>
              <ul class="dropdown-menu menu <?php echo $animated;?>">
                <li>
                    <ul class="menu" style="max-height: 245px;">
                      <li>
                                
                          <?php if($leave_count > 0){ ?>
                              <ul class="menu">
                                  <li class="header"><a href="javascript:void(0);"><?php echo $leave_count;?> <?php echo $this->lang->line('xin_leave_notifications');?></a></li>
                                    <?php foreach($leaveapp as $leave_notify){?>
                                      <?php $employee_info = $this->Core_model->read_user_info($leave_notify->employee_id);?>
                                      <?php
                                          if(!is_null($employee_info)){
                                              $emp_name = $employee_info[0]->first_name. ' '.$employee_info[0]->last_name;
                                          } else {
                                              $emp_name = '--'; 
                                          }
                                      ?>
                                      <li><!-- start message -->
                                      <a href="<?php echo site_url('admin/permission/leave_details/id')?>/<?php echo $leave_notify->leave_id;?>/">
                                        <div class="pull-left">
                                          <?php  if($employee_info[0]->profile_picture!='' && $employee_info[0]->profile_picture!='no file') {?>
                                                  
                                                  <img src="<?php  echo base_url().'uploads/profile/'.$employee_info[0]->profile_picture;?>" alt="" id="user_avatar" class="img-circle user_profile_avatar">
                                         
                                          <?php } else {?>
                                              
                                              <?php  if($employee_info[0]->gender=='Male') { ?>
                                                   <?php   $de_file = base_url().'uploads/profile/default_male.jpg';?>
                                              <?php } else { ?>
                                                    <?php   $de_file = base_url().'uploads/profile/default_female.jpg';?>
                                              <?php } ?>

                                              <img src="<?php  echo $de_file;?>" alt="" id="user_avatar" class="img-circle user_profile_avatar">
                                          <?php  } ?>
                                        </div>
                                        <h4>
                                           <?php echo substr($emp_name,0,15);?>...
                                          <small><i class="fa fa-calendar"></i> <?php echo $this->Core_model->set_date_format($leave_notify->applied_on);?></small>
                                        </h4>
                                        <p><?php echo $this->lang->line('header_has_applied_for_leave');?></p>
                                      </a>
                                    </li>
                                    <?php } ?>
                              </ul>
                          <?php } ?>

                      </li>
                    </ul>
                </li>
              </ul>
          <?php } ?>
        </li>

        <li class="dropdown messages-menu">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="true">
            <i class="fa fa-medkit"></i>
            <span class="label label-success"><?php echo $sick_count;?></span>
          </a>

          <?php if($sick_count > 0 ){?>
          <ul class="dropdown-menu menu <?php echo $animated;?>">
            <li>
              <ul class="menu" style="max-height: 245px;">
                <li>                             
                   
                    <?php if($sick_count > 0){ ?>
                        <ul class="menu">
                            <li class="header"><a href="javascript:void(0);"><?php echo $sick_count;?> <?php echo $this->lang->line('xin_sick_notifications');?></a></li>
                              <?php foreach($sickapp as $sick_notify){?>
                                <?php $employee_info = $this->Core_model->read_user_info($sick_notify->employee_id);?>
                                <?php
                                    if(!is_null($employee_info)){
                                        $emp_name = $employee_info[0]->first_name. ' '.$employee_info[0]->last_name;
                                    } else {
                                        $emp_name = '--'; 
                                    }
                                ?>
                                <li><!-- start message -->
                                <a href="<?php echo site_url('admin/permission/sick_details/id')?>/<?php echo $sick_notify->sick_id;?>/">
                                  <div class="pull-left">
                                    <?php  if($employee_info[0]->profile_picture!='' && $employee_info[0]->profile_picture!='no file') {?>
                                    <img src="<?php  echo base_url().'uploads/profile/'.$employee_info[0]->profile_picture;?>" alt="" id="user_avatar" 
                                    class="img-circle user_profile_avatar">
                                    <?php } else {?>
                                    <?php  if($employee_info[0]->gender=='Male') { ?>
                                    <?php   $de_file = base_url().'uploads/profile/default_male.jpg';?>
                                    <?php } else { ?>
                                    <?php   $de_file = base_url().'uploads/profile/default_female.jpg';?>
                                    <?php } ?>
                                    <img src="<?php  echo $de_file;?>" alt="" id="user_avatar" class="img-circle user_profile_avatar">
                                    <?php  } ?>
                                  </div>
                                  <h4>
                                    <?php echo substr($emp_name,0,15);?>...
                                    <small><i class="fa fa-calendar"></i> <?php echo $this->Core_model->set_date_format($sick_notify->applied_on);?></small>
                                  </h4>
                                  <p><?php echo $this->lang->line('header_has_applied_for_sick');?></p>
                                </a>
                              </li>
                              <?php } ?>
                        </ul>
                    <?php } ?>                
                </li>
              </ul>
            </li>
          </ul>
          <?php } ?>
        </li> 

        <li class="dropdown messages-menu">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="true">
            <i class="fa fa-warning"></i>
            <span class="label label-success"><?php echo $izin_count;?></span>
          </a>

          <?php if($izin_count > 0 ){?>
              <ul class="dropdown-menu menu <?php echo $animated;?>">
                <li>
                  <ul class="menu" style="max-height: 245px;">
                    <li>                             
                       
                        <?php if($izin_count > 0){ ?>
                            <ul class="menu">
                                <li class="header">
                                  <a href="javascript:void(0);"><?php echo $izin_count;?> <?php echo $this->lang->line('xin_izin_notifications');?></a></li>
                                  <?php foreach($izinapp as $izin_notify){?>
                                    <?php $employee_info = $this->Core_model->read_user_info($izin_notify->employee_id);?>
                                    <?php
                                        if(!is_null($employee_info)){
                                            $emp_name = $employee_info[0]->first_name. ' '.$employee_info[0]->last_name;
                                        } else {
                                            $emp_name = '--'; 
                                        }
                                    ?>
                                    <li><!-- start message -->
                                    <a href="<?php echo site_url('admin/permission/izin_details/id')?>/<?php echo $izin_notify->izin_id;?>/">
                                      <div class="pull-left">
                                        <?php  if($employee_info[0]->profile_picture!='' && $employee_info[0]->profile_picture!='no file') {?>
                                        <img src="<?php  echo base_url().'uploads/profile/'.$employee_info[0]->profile_picture;?>" alt="" id="user_avatar" 
                                        class="img-circle user_profile_avatar">
                                        <?php } else {?>
                                        <?php  if($employee_info[0]->gender=='Male') { ?>
                                        <?php   $de_file = base_url().'uploads/profile/default_male.jpg';?>
                                        <?php } else { ?>
                                        <?php   $de_file = base_url().'uploads/profile/default_female.jpg';?>
                                        <?php } ?>
                                        <img src="<?php  echo $de_file;?>" alt="" id="user_avatar" class="img-circle user_profile_avatar">
                                        <?php  } ?>
                                      </div>
                                      <h4>
                                        <?php echo substr($emp_name,0,15);?>...
                                        <small><i class="fa fa-calendar"></i> <?php echo $this->Core_model->set_date_format($izin_notify->applied_on);?></small>
                                      </h4>
                                      <p><?php echo $this->lang->line('header_has_applied_for_izin');?></p>
                                    </a>
                                  </li>
                                  <?php } ?>
                            </ul>
                        <?php } ?>                
                    </li>
                  </ul>
                </li>
              </ul>
          <?php } ?>
        </li>

    <?php } ?>
       


          <?php if($system[0]->module_chat_box=='true'){?>
        <li class="dropdown messages-menu">
          <a href="<?php echo site_url('admin/chat');?>">
           <i class="fa fa-comments"></i></i>
            <?php $unread_msgs = $this->Core_model->get_single_unread_message($session['user_id']);?>
            <?php if($unread_msgs > 0) {?><span class="chat-badge label label-aqua" id="msgs_count"><?php echo $unread_msgs;?></span><?php } ?>
          </a>
        </li>
        <?php } ?>



         <li class="dropdown">
            <?php  if($user[0]->profile_picture!='' && $user[0]->profile_picture!='no file') {?>
                  <?php $cpimg = base_url().'uploads/profile/'.$user[0]->profile_picture;?>
                  <?php $cimg = '<img src="'.$cpimg.'" alt="" id="user_avatar" class="img-circle rounded-circle user_profile_avatar">';?>
            <?php } else {?>
                  <?php  if($user[0]->gender=='Male') { ?>
                    <?php   $de_file = base_url().'uploads/profile/default_male.jpg';?>
                  <?php } else { ?>
                     <?php   $de_file = base_url().'uploads/profile/default_female.jpg';?>
                  <?php } ?>
                  <?php $cpimg = $de_file;?>
                  <?php $cimg = '<img src="'.$de_file.'" alt="" id="user_avatar" class="img-circle rounded-circle user_profile_avatar">';?>
            <?php  } ?>
            
            <a class="dropdown-toggle" data-toggle="dropdown" href="#" aria-expanded="true">
              <img src="<?php echo $cpimg;?>" class="user-image-top" alt="<?php echo $user[0]->first_name.' '.$user[0]->last_name;?>"> &nbsp;
              <?php echo $user[0]->first_name.' '.$user[0]->last_name;?>
            </a>

            <ul class="dropdown-menu <?php echo $animated;?>">
                <!--  <li role="presentation">
                    <a role="menuitem" tabindex="-1" href="<?php echo site_url('admin/auth/lock');?>"> <i class="fa fa-lock"></i><?php echo $this->lang->line('xin_lock_user');?></a>
                </li> -->
                
                <li role="presentation">
                    <a role="menuitem" tabindex="-1" href="<?php echo site_url('admin/profile?change_password=true');?>"> <i class="fa fa-key"></i><?php echo $this->lang->line('header_change_password');?></a>
                </li>
                
                <li class="divider"></li>

                <li role="presentation">
                  <a role="menuitem" tabindex="-1" href="<?php echo site_url('admin/logout');?>"> <i class="fa fa-power-off text-red"></i><?php echo $this->lang->line('header_sign_out');?></a>
                </li>
              </ul>
        </li>
        <!-- Control Sidebar Toggle Button -->
        <li>
          <a href="#" data-toggle="control-sidebar"><i class="fa fa-gear fa-spin"></i></a>
        </li>
      </ul>
    </div>
  </nav>
</header>
