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

<?php if(in_array('13',$role_resources_ids)) {?>

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
                    <div class="col-md-1">
                      <div class="form-group">
                        <label for="designation"><br></label>
                        <?php echo form_button(array('name' => 'hris_form', 'type' => 'submit', 'class' => $this->Core_model->form_button_class(),'style' => 'margin-top: 25px; margin-left: -20px;', 'content' => '<i class="fa fa-check-square-o"></i> '.$this->lang->line('xin_get'))); ?>
                      </div>
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
        <h3 class="box-title"> <?php echo $this->lang->line('xin_list_all');?> <?php echo $this->lang->line('xin_employees_active');?> </h3>
        
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

<!-- <?php } else {?> -->

  <div class="row">
    <div class="col-md-12 <?php echo $get_animate;?>">
        <div class="box">
            <div class="box-body">
                <div class="box-header with-border">
                    <h3 class="box-title"> <?php echo $this->lang->line('xin_list_all');?> <?php echo $this->lang->line('xin_employees');?> </h3>
                    <div class="box-tools pull-right"> 
                    </div>                    
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
        </div>           
    </div>
    <!-- /.col --> 
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
<!-- <?php } ?> -->
