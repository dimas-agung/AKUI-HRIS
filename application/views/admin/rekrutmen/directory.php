<?php
/* Employee Directory view
*/
?>
    <?php $session            = $this->session->userdata('username');?>
    <?php $countries          = $this->Core_model->get_countries();?>
    <?php $get_animate        = $this->Core_model->get_content_animate();?>
    <?php $role_resources_ids = $this->Core_model->user_role_resource();?>
    <?php $user_info          = $this->Core_model->read_user_info($session['user_id']);?>

    <?php if(in_array('88',$role_resources_ids)) {?>

    <div class="row <?php echo $get_animate;?>">
        <div class="col-sm-6 col-lg-3">
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
                    <span class="stamp-hris-4 stamp-hris-md bg-hris-danger-4 mr-3">
                        <i class="fa fa-user-times"></i>
                    </span>
                    <div>
                        <h5 class="mb-1"><b><?php echo inactive_employees();?> <small><?php echo $this->lang->line('xin_employees_inactive');?></small></b></h5>
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

    <?php } ?>

<!-- <?php if($user_info[0]->user_role_id==1){ ?> -->

    <div id="filter_hris" class="collapse add-formd <?php echo $get_animate;?>" data-parent="#accordion" style="">
        <div class="box mb-4 <?php echo $get_animate;?>">
            <div class="box-header  with-border">
              <h3 class="box-title"><?php echo $this->lang->line('xin_filter_employee');?></h3>
              <div class="box-tools pull-right"> 
                <a class="text-dark collapsed" data-toggle="collapse" href="#filter_hris" aria-expanded="false">
                <button type="button" class="btn btn-xs btn-primary"> 
                  <span class="fa fa-minus"></span> <?php echo $this->lang->line('xin_hide');?>
                </button>
                </a> 
              </div>
            </div>
          <div class="box-body">
            <div class="row">
              <div class="col-md-12">
                <?php $attributes = array('name' => 'ihr_report', 'id' => 'ihr_report', 'autocomplete' => 'off', 'class' => 'add form-hrm');?>
                <?php $hidden = array('user_id' => $session['user_id']);?>
                <?php echo form_open('admin/employees/employees_list', $attributes, $hidden);?>
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
                        <select class="form-control" name="company_id" id="filter_company" data-plugin="select_hrm" data-placeholder="<?php echo $this->lang->line('left_company');?>">
                          <option value="0"><?php echo $this->lang->line('xin_acc_all');?></option>
                          <?php foreach($get_all_companies as $company) {?>
                          <option value="<?php echo $company->company_id?>"><?php echo $company->name?></option>
                          <?php } ?>
                        </select>
                      </div>
                    </div>
                    <div class="col-md-3" id="location_ajaxflt">
                    <div class="form-group">
                      <label for="name"><?php echo $this->lang->line('left_location');?></label>
                      <select name="location_id" id="filter_location" class="form-control" data-plugin="select_hrm" data-placeholder="<?php echo $this->lang->line('left_location');?>">
                        <option value="0"><?php echo $this->lang->line('xin_acc_all');?></option>
                      </select>
                    </div>
                    </div>
                    <div class="col-md-3">
                      <div class="form-group" id="department_ajaxflt">
                        <label for="department"><?php echo $this->lang->line('left_department');?></label>
                        <select class="form-control" id="filter_department" name="department_id" data-plugin="select_hrm" data-placeholder="<?php echo $this->lang->line('left_department');?>" >
                          <option value="0"><?php echo $this->lang->line('xin_acc_all');?></option>
                        </select>
                      </div>
                    </div>
                    <div class="col-md-2" id="designation_ajaxflt">
                      <div class="form-group">
                        <label for="designation"><?php echo $this->lang->line('xin_designation');?></label>
                        <select class="form-control" name="designation_id" data-plugin="select_hrm"  id="filter_designation" data-placeholder="<?php echo $this->lang->line('xin_designation');?>">
                          <option value="0"><?php echo $this->lang->line('xin_acc_all');?></option>
                        </select>
                      </div>
                    </div>
                    <div class="col-md-1"><label for="designation">&nbsp;</label><?php echo form_button(array('name' => 'hris_form', 'type' => 'submit', 'class' => $this->Core_model->form_button_class(),'style' => 'margin-top: 25px; margin-left: -20px;', 'content' => '<i class="fa fa fa-check-square-o"></i> '.$this->lang->line('xin_get'))); ?>
                    </div>
                </div>
                <!--<div class="form-actions box-footer"> <?php echo form_button(array('name' => 'hris_form', 'type' => 'submit', 'class' => $this->Core_model->form_button_class(), 'content' => '<i class="fa fa fa-check-square-o"></i> '.$this->lang->line('xin_get'))); ?> </div>-->
                <?php echo form_close(); ?> </div>
            </div>
          </div>
        </div>
    </div>

<!-- <?php } ?> -->



<!-- <?php if($user_info[0]->user_role_id==1){ ?> -->

    <div class="box <?php echo $get_animate;?>">
      <div class="box-header with-border">
        <h3 class="box-title"> <?php echo $this->lang->line('xin_list_all');?> <?php echo $this->lang->line('xin_employees');?> </h3>
        
        <!-- <?php if($user_info[0]->user_role_id==1){ ?> -->
            <div class="box-tools pull-right"> 
              <a class="text-dark collapsed" data-toggle="collapse" href="#filter_hris" aria-expanded="false">
                <button type="button" class="btn btn-xs btn-primary"> 
                  <span class="fa fa-filter"></span> <?php echo $this->lang->line('xin_filter');?>
                </button>
              </a> 
            </div>
        <!-- <?php } ?> -->
      </div>
      <div class="box-body">
        <div class="box-datatable table-responsive">
          <table class="datatables-demo table table-striped table-bordered" id="xin_table">
            <thead>
                   <tr>
                      <th width="80px"><?php echo $this->lang->line('xin_action');?></th>
                      <th width="50px"><?php echo $this->lang->line('xin_employees_photo');?></th>
                      <th width="120px"><?php echo $this->lang->line('xin_employee_doj');?></th>
                      <th width="200px"><?php echo $this->lang->line('xin_employees_full_name');?></th>
                      <th width="180px"><?php echo $this->lang->line('xin_location');?></th>
                      <th width="200px"><?php echo $this->lang->line('xin_designations');?></th>                     
                      <th width="80px"><?php echo $this->lang->line('xin_e_details_shift');?></th>                      
                    </tr>
            </thead>
          </table>
        </div>
      </div>
    </div>

<!-- <?php } else {?> -->

  <div class="row">
    <div class="col-md-12 <?php echo $get_animate;?>"> 
      <!-- Custom Tabs (Pulled to the right) -->
      <div class="nav-tabs-custom">
        <ul class="nav nav-tabs">
          <?php if(in_array('88',$role_resources_ids)) {?>
          <li class="active"><a href="#tab_1-1" data-toggle="tab"><?php echo $this->lang->line('xin_list_all');?> <?php echo $this->lang->line('xin_employees');?></a></li>
          <?php } ?>
          <?php
            if(!in_array('88',$role_resources_ids)) {
          $act_cls = 'active';
        } else {
          $act_cls = '';
        }
      ?>
        <li class="<?php echo $act_cls;?>"><a href="#tab_2-2" data-toggle="tab"><?php echo $this->lang->line('xin_my_team');?></a></li>
        </ul>
        <div class="tab-content">

         <?php if(in_array('88',$role_resources_ids)) {?>

          <div class="tab-pane active" id="tab_1-1">
            <div class="box <?php echo $get_animate;?>">
              <div class="box-header with-border">
                  <h3 class="box-title"> <?php echo $this->lang->line('xin_list_all');?> <?php echo $this->lang->line('xin_employees');?> </h3>
                  <!-- <?php if($user_info[0]->user_role_id==1){ ?> -->
                  <div class="box-tools pull-right"> <a class="text-dark collapsed" data-toggle="collapse" href="#filter_hris" aria-expanded="false">
                      <button type="button" class="btn btn-xs btn-primary"> <span class="fa fa-filter"></span> <?php echo $this->lang->line('xin_filter');?></button>
                     </a> <button type="button" class="btn btn-xs btn-primary dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><span class="fa fa-bar-chart"></span> <?php echo $this->lang->line('xin_report');?> <span class="fa fa-caret-down"></span></button>
                     <ul class="dropdown-menu">
                      <li><a href="<?php echo site_url('admin/reports/employees/');?>" target="_blank"><?php echo $this->lang->line('xin_filter_employement_report');?></a></li>
                    </ul></div>
                    <!-- <?php } ?> -->
                </div>
              <div class="box-body">
                  <div class="box-datatable table-responsive">
                    <table class="datatables-demo table table-striped table-bordered" id="xin_table">
                      <thead>
                        <tr>
                            <th width="80px"><?php echo $this->lang->line('xin_action');?></th>
                            <th width="50px"><?php echo $this->lang->line('xin_employees_photo');?></th>
                            <th width="120px"><?php echo $this->lang->line('xin_employee_doj');?></th>
                            <th width="200px"><?php echo $this->lang->line('xin_employees_full_name');?></th>
                            <th width="180px"><?php echo $this->lang->line('xin_location');?></th>
                            <th width="200px"><?php echo $this->lang->line('xin_designations');?></th>                     
                            <th width="80px"><?php echo $this->lang->line('xin_e_details_shift');?></th>                      
                        </tr>
                      </thead>
                    </table>
                  </div>
                </div>
            </div>
          </div>

          <?php } ?>
          <!-- /.tab-pane -->
          <div class="tab-pane <?php echo $act_cls;?>" id="tab_2-2">
          <div class="box-header with-border">
                <h3 class="box-title"><?php echo $this->lang->line('xin_my_team');?></h3>
              </div>
            <div class="box-body">
              <div class="box-datatable table-responsive">
                <table class="datatables-demo table table-striped table-bordered" id="xin_my_team_table" style="width:120%;">
                  <thead>
                    <tr>
                      <th width="80px"><?php echo $this->lang->line('xin_action');?></th>
                      <th width="50px"><?php echo $this->lang->line('xin_employees_photo');?></th>
                      <th width="120px"><?php echo $this->lang->line('xin_employee_doj');?></th>
                      <th width="200px"><?php echo $this->lang->line('xin_employees_full_name');?></th>
                      <th width="180px"><?php echo $this->lang->line('xin_location');?></th>
                      <th width="200px"><?php echo $this->lang->line('xin_designations');?></th>                     
                      <th width="80px"><?php echo $this->lang->line('xin_e_details_shift');?></th>                      
                    </tr>
                  </thead>
                </table>
              </div>
            </div>
            </div>
          <!-- /.tab-pane --> 
        </div>
        <!-- /.tab-content --> 
      </div>
      <!-- nav-tabs-custom --> 
    </div>
    <!-- /.col --> 
</div>

<!-- <?php } ?> -->
