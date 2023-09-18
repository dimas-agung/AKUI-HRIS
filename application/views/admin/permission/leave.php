<?php
/* leave Application view
*/
?>
<?php $session            = $this->session->userdata('username');?>
<?php $user               = $this->Core_model->read_employee_info($session['user_id']);?>
<?php $get_animate        = $this->Core_model->get_content_animate();?>
<?php $xuser_info         = $this->Core_model->read_user_info($session['user_id']);?>
<?php $role_resources_ids = $this->Core_model->user_role_resource();?>
<?php

// reports to 
$reports_to = get_reports_team_data($session['user_id']); ?>

<?php if(in_array('0712',$role_resources_ids)){ ?>
    <div id="filter_hris" class="collapse add-formd <?php echo $get_animate;?>" data-parent="#accordion" style="">
        <div class="row">
          <div class="col-md-12">
            <div class="box mb-4">
            <div class="box-header  with-border">
              <h3 class="box-title"><?php echo $this->lang->line('xin_filter');?></h3>
                  <div class="box-tools pull-right"> <a class="text-dark collapsed" data-toggle="collapse" href="#filter_hris" aria-expanded="false">
                    <button type="button" class="btn btn-xs btn-primary"> <span class="fa fa-minus"></span> <?php echo $this->lang->line('xin_hide');?></button>
                    </a> </div>
                </div>
              <div class="box-body">
                <div class="row">
                  <div class="col-md-12">
                    <?php $attributes = array('name' => 'ihr_report', 'id' => 'ihr_report', 'class' => 'm-b-1 add form-hrm');?>
                    <?php $hidden = array('user_id' => $session['user_id']);?>
                    <?php echo form_open('admin/permission/leave_list', $attributes, $hidden);?>
                    <div class="row">
                      <div class="col-md-3">
                        <div class="form-group">
                          <label for="department"><?php echo $this->lang->line('module_company_title');?></label>
                          <select class="form-control" name="company" id="aj_companyf" data-plugin="select_hrm" data-placeholder="Pilih Perusahaan" required>
                            <option value="0"><?php echo $this->lang->line('xin_all_companies');?></option>
                            <?php foreach($get_all_companies as $company) {?>
                            <option value="<?php echo $company->company_id;?>"> <?php echo $company->name;?></option>
                            <?php } ?>
                          </select>
                        </div>
                      </div>
                      <div class="col-md-3">
                        <div class="form-group" id="employee_ajaxf">
                          <label for="department"><?php echo $this->lang->line('dashboard_single_employee');?></label>
                          <select id="employee_id" name="employee_id" class="form-control" data-plugin="select_hrm" data-placeholder="Pilih Nama Karyawan">
                            <option value="0"><?php echo $this->lang->line('xin_all_employees');?></option>
                          </select>
                        </div>
                      </div>
                      <div class="col-md-3">
                        <div class="form-group">
                            <label for="status"><?php echo $this->lang->line('dashboard_xin_status');?></label>
                            <select class="form-control" name="status" id="status" data-plugin="select_hrm" data-placeholder="Pilih Status">
                              <option value="0" ><?php echo $this->lang->line('xin_acc_all');?></option>
                              <option value="1" ><?php echo $this->lang->line('xin_pending');?></option>
                              <option value="2" ><?php echo $this->lang->line('xin_approved');?></option>
                              <option value="3" ><?php echo $this->lang->line('xin_rejected');?></option>
                            </select>
                          </div>
                      </div>
                      <div class="col-md-1"><label for="xin_get">&nbsp;</label><button name="hris_form" type="submit" class="btn btn-primary"><i class="fa fa fa-check-square-o"></i> <?php echo $this->lang->line('xin_get');?></button>
                    </div>
                    </div>
                    
                    <?php echo form_close(); ?> </div>
                </div>
              </div>
            </div>
          </div>
        </div>
    </div>
<?php } ?>

<?php if(in_array('0712',$role_resources_ids)) {?>
    
    <?php $user_info = $this->Core_model->read_user_info($session['user_id']);?>
    <?php $leave_cat = get_employee_leave_category();?>

    <div class="box mb-4 <?php echo $get_animate;?>">
      <div id="accordion">
        <div class="box-header with-border">
          <h3 class="box-title"><i class="fa fa-plus-circle"></i> <?php echo $this->lang->line('xin_add_leave');?></h3>
          <div class="box-tools pull-right"> <a class="text-dark collapsed" data-toggle="collapse" href="#add_form" aria-expanded="false">
            <button type="button" class="btn btn-xs btn-primary"> <span class="fa fa-plus-circle"></span> <?php echo $this->lang->line('xin_add_new');?></button>
            </a> </div>
        </div>
        <div id="add_form" class="collapse add-form <?php echo $get_animate;?>" data-parent="#accordion" style="">
          <div class="box-body">
            <?php $attributes = array('name' => 'add_leave', 'id' => 'xin-form', 'autocomplete' => 'off');?>
            <?php $hidden     = array('_user' => $session['user_id']);?>
            <?php echo form_open('admin/permission/add_leave', $attributes, $hidden);?>
           
            <div class="bg-white">
              <div class="box-block">
                <div class="row">
                  <div class="col-md-6">
                   
                    <div class="row">
                      <div class="col-md-12">
                        
                        <div class="form-group">
                          <label for="first_name"><?php echo $this->lang->line('left_company');?></label>
                          <select class="form-control" name="company_id" id="aj_company" data-plugin="select_hrm" data-placeholder="Pilih Perusahaan">
                            <option value=""></option>
                            <?php foreach($get_all_companies as $company) {?>
                            <option value="<?php echo $company->company_id?>"><?php echo $company->name?></option>
                            <?php } ?>
                          </select>
                        </div>
                      </div>
                      
                    </div>

                    <div class="row">
                      
                      <div class="col-md-12">
                        <div class="form-group" id="employee_ajax">
                          <label for="employees" class="control-label"><?php echo $this->lang->line('xin_employee');?></label>
                          <select disabled="disabled" class="form-control" name="employee_id" id="employee_id" data-plugin="select_hrm" data-placeholder="Pilih Nama Karyawan">
                            <option value=""></option>
                          </select>
                        </div>
                      </div>
                    </div>

                    <div class="form-group" id="get_leave_types" >
                      <label for="leave_type" class="control-label"><?php echo $this->lang->line('xin_leave_type');?></label>                  
                      <select disabled="disabled" class="form-control" name="leave_type" id="leave_type" data-plugin="select_hrm" data-placeholder="Pilih Jenis Cuti">
                            <option value=""></option>                      
                      </select>                 
                    </div>
                    
                    <div class="row">
                      <div class="col-md-6">
                        <div class="form-group">
                          <label for="start_date"><?php echo $this->lang->line('xin_start_date');?></label>
                          <input class="form-control date" placeholder="Pilih Tanggal Mulai Cuti" readonly name="start_date" type="text" value="">
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="form-group">
                          <label for="end_date"><?php echo $this->lang->line('xin_end_date');?></label>
                          <input class="form-control date" placeholder="Pilih Tanggal Sampai Cuti" readonly name="end_date" type="text" value="">
                        </div>
                      </div>
                    </div>

                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="description"><?php echo $this->lang->line('xin_leave_reason');?></label>
                      <textarea class="form-control textarea" placeholder="Tentukan Keterangan Cuti" name="reason" id="reason" rows="5"></textarea>
                    </div>
                    
                    <!-- <div class="form-group">
                      <label>
                      <input type="checkbox" class="minimal" value="1" id="leave_half_day" name="leave_half_day">
                         <?php echo $this->lang->line('xin_hr_leave_half_day');?></span> 
                      </label>
                    </div> -->

                    <div class="form-group">
                      <fieldset class="form-group">
                        <label for="attachment"><?php echo $this->lang->line('xin_attachment');?></label>
                        <input type="file" class="form-control-file" id="attachment" name="attachment">
                        <small><?php echo $this->lang->line('xin_leave_file_type');?></small>
                      </fieldset>
                    </div>
                  </div>
                </div>
                <div class="form-actions box-footer">
                  <button type="submit" class="btn btn-primary"> <i class="fa fa-check-square-o"></i> <?php echo $this->lang->line('xin_save');?> </button>
                </div>
              </div>
            </div>
            <?php echo form_close(); ?> </div>
        </div>
      </div>
    </div>

<?php } ?>

    <div class="box <?php echo $get_animate;?>">
      
      <div class="box-header with-border">
        <h3 class="box-title"><?php echo $this->lang->line('xin_list_all');?> <?php echo $this->lang->line('left_leave');?></h3>
        <?php if($xuser_info[0]->user_role_id==1){ ?>
          <div class="box-tools pull-right"> 
            <a class="text-dark collapsed" data-toggle="collapse" href="#filter_hris" aria-expanded="false">
                <button type="button" class="btn btn-xs btn-primary"> 
                  <span class="fa fa-filter"></span> <?php echo $this->lang->line('xin_filter');?>
                </button>
            </a> 
          </div>
        <?php } ?>
      </div>

      <div class="box-body">
        <div class="box-datatable table-responsive">
          <table class="datatables-demo table table-striped table-bordered" id="xin_table">
            <thead>
                  <tr>
                    <th width="160px"><center><?php echo $this->lang->line('xin_action');?></center></th>
                    <th width="10px"><center>No.</center></th>
                    <th width="80px"><center><?php echo $this->lang->line('dashboard_xin_status');?></center></th>       
                    <th width="100px"><center>Tanggal <br> Pengajuan</center></th>
                    <th width="200px"><center><i class="fa fa-calendar"></i> <?php echo $this->lang->line('xin_leave_duration');?></center></th>
                    <th width="450px"><center><?php echo $this->lang->line('xin_employee');?></center></th>
                    <th width="170px"><center>Tanggal Rekrutmen</center></th>
                    <th ><center><?php echo $this->lang->line('xin_leave_desc');?></center></th>                               
                  </tr>
            </thead>
          </table>
        </div>
      </div>

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