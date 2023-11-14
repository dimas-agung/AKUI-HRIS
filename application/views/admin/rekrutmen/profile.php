<?php
/* Profile view
*/
?>
<?php $session = $this->session->userdata('username');?>
<?php $user = $this->Core_model->read_user_info($session['user_id']);?>
<?php $system = $this->Core_model->read_setting_info(1);?>
<?php if($profile_picture!='' && $profile_picture!='no file') {?>
<?php $de_file = base_url().'uploads/profile/'.$profile_picture;?>
<?php } else {?>
<?php if($gender=='Male') { ?>
<?php $de_file = base_url().'uploads/profile/default_male.jpg';?>
<?php } else { ?>
<?php $de_file = base_url().'uploads/profile/default_female.jpg';?>
<?php } ?>
<?php } ?>
<?php $full_name = $user[0]->first_name.' '.$user[0]->last_name;?>
<?php $designation = $this->Designation_model->read_designation_information($user[0]->designation_id);?>
<?php
  if(!is_null($designation)){
    $designation_name = $designation[0]->designation_name;
  } else {
    $designation_name = '--'; 
  }
  $leave_user = $this->Core_model->read_user_info($session['user_id']);
?>
<?php $role_resources_ids = $this->Core_model->user_role_resource(); ?>
<?php $get_animate = $this->Core_model->get_content_animate();?>
<div class="row">
  <div class="col-md-12">
    <div class="nav-tabs-custom mb-4">
      <ul class="nav nav-tabs">
        <li class="nav-item active"> <a class="nav-link active show" data-toggle="tab" href="#xin_general">
          <?php echo $this->lang->line('xin_general');?></a> </li>
       </ul>
     <div class="tab-content">
        <div class="tab-pane <?php echo $get_animate;?> active" id="xin_general">
          <div class="row">
              <div class="col-md-3 <?php echo $get_animate;?>"> 
                
               
                <div class="box box-primary">
                  <div class="box-body box-profile"> 

                    <a class="nav-tabs-link" href="#profile-picture" data-profile="2" data-profile-block="profile_picture" data-toggle="tab" aria-expanded="true" id="user_profile_2"> 
                      <img class="profile-user-img img-responsive img-circle" src="<?php echo $de_file;?>" alt="<?php echo $full_name;?>">
                    </a>
                   
                    <h3 class="profile-username text-center"><?php echo $full_name;?></h3>
                   
                    <p class="text-muted text-center"><?php echo $designation_name;?></p>
                    
                    <div class="list-group">
                                           
                      
                      <a class="list-group-item-profile list-group-item list-group-item-action nav-tabs-link" href="#change_password" data-profile="14" data-profile-block="change_password" data-toggle="tab" aria-expanded="true" id="user_profile_14"> 
                        <i class="fa fa-key"></i> <?php echo $this->lang->line('xin_e_details_cpassword');?> 
                      </a>

                    </div>
                  </div>
                
                </div>
              </div>

              <div class="col-md-9 current-tab <?php echo $get_animate;?>" id="change_password" >
                <div class="box">
                  <div class="box-header with-border">
                    <h3 class="box-title"> <?php echo $this->lang->line('xin_e_details_cpassword');?> </h3>
                  </div>
                  <div class="box-body">
                    <div class="card-block">
                      <?php $attributes = array('name' => 'e_change_password', 'id' => 'e_change_password', 'autocomplete' => 'off');?>
                      <?php $hidden = array('u_basic_info' => 'UPDATE');?>
                      <?php echo form_open('admin/employees/change_password/', $attributes, $hidden);?>
                      <?php
                      $data_usr11 = array(
                            'type'  => 'hidden',
                            'name'  => 'user_id',
                            'value' => $session['user_id'],
                     );
                    echo form_input($data_usr11);
                    ?>
                      <?php if($this->input->get('change_password')):?>
                      <input type="hidden" id="change_pass" value="<?php echo $this->input->get('change_password');?>" />
                      <?php endif;?>
                      <div class="row">
                        <div class="col-md-4">
                          <div class="form-group">
                            <label for="old_password"><?php echo $this->lang->line('xin_old_password');?></label>
                            <input class="form-control" placeholder="<?php echo $this->lang->line('xin_old_password');?>" name="old_password" type="password">
                          </div>
                        </div>
                        <div class="col-md-4">
                          <div class="form-group">
                            <label for="new_password"><?php echo $this->lang->line('xin_e_details_enpassword');?></label>
                            <input class="form-control" placeholder="<?php echo $this->lang->line('xin_e_details_enpassword');?>" name="new_password" type="password">
                          </div>
                        </div>
                        <div class="col-md-4">
                          <div class="form-group">
                            <label for="new_password_confirm" class="control-label"><?php echo $this->lang->line('xin_e_details_ecnpassword');?></label>
                            <input class="form-control" placeholder="<?php echo $this->lang->line('xin_e_details_ecnpassword');?>" name="new_password_confirm" type="password">
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
                </div>
              </div>

            </div>
        </div>
  
    </div>
  </div>
  </div>
</div>     
     

