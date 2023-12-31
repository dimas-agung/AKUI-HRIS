<?php
/* Travel view
*/
?>
<?php $session = $this->session->userdata('username');?>
<?php $get_animate = $this->Core_model->get_content_animate();?>
<?php $role_resources_ids = $this->Core_model->user_role_resource(); ?>
<?php $user_info = $this->Core_model->read_employee_info($session['user_id']);?>
<?php if(in_array('0642',$role_resources_ids)) {?>

<div class="box mb-4 <?php echo $get_animate;?>">
  <div id="accordion">
    <div class="box-header  with-border">
      <h3 class="box-title"><i class="fa fa-plus-circle"></i>i <?php echo $this->lang->line('xin_add_new');?> </h3>
      <div class="box-tools pull-right"> <a class="text-dark collapsed" data-toggle="collapse" href="#add_form" aria-expanded="false">
        <button type="button" class="btn btn-xs btn-primary"> <span class="fa fa-plus-circle"></span> <?php echo $this->lang->line('xin_add_new');?></button>
        </a> </div>
    </div>
    <div id="add_form" class="collapse add-form <?php echo $get_animate;?>" data-parent="#accordion" style="">
      <div class="box-body">
        <?php $attributes = array('name' => 'add_travel', 'id' => 'xin-form', 'autocomplete' => 'off');?>
        <!-- <?php if($user_info[0]->user_role_id==1){ ?> -->
        <?php $hidden = array('user_id' => $session['user_id']);?>
        <!-- <?php } else { ?> -->
        <!-- <?php $hidden = array('user_id' => $session['user_id'],'company_id' => $user_info[0]->company_id,'employee_id' => $session['user_id']);?> -->
        <!-- <?php } ?> -->
        <?php echo form_open('admin/travel/add_travel', $attributes, $hidden);?>
        <div class="bg-white">
          <div class="box-block">
            <div class="row">
              <div class="col-md-6">
               
                <div class="form-group">
                  <label for="first_name"><?php echo $this->lang->line('left_company');?></label>
                  <select class="form-control" name="company_id" id="aj_company" data-plugin="select_hrm" data-placeholder="<?php echo $this->lang->line('left_company');?>">
                    <option value=""></option>
                    <?php foreach($get_all_companies as $company) {?>
                    <option value="<?php echo $company->company_id?>"><?php echo $company->name?></option>
                    <?php } ?>
                  </select>
                </div>
                <div class="form-group" id="employee_ajax">
                  <label for="employee_id"><?php echo $this->lang->line('dashboard_single_employee');?></label>
                  <select name="employee_id" id="select2-demo-6" class="form-control" data-plugin="select_hrm" data-placeholder="<?php echo $this->lang->line('xin_choose_an_employee');?>">
                    <option value=""></option>
                  </select>
                </div>
               
                <div class="row">
                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="start_date"><?php echo $this->lang->line('xin_start_date');?></label>
                      <input class="form-control date" placeholder="<?php echo $this->lang->line('xin_start_date');?>" readonly name="start_date" type="text">
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="end_date"><?php echo $this->lang->line('xin_end_date');?></label>
                      <input class="form-control date" placeholder="<?php echo $this->lang->line('xin_end_date');?>" readonly name="end_date" type="text">
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="visit_purpose"><?php echo $this->lang->line('xin_visit_purpose');?></label>
                      <input class="form-control" placeholder="<?php echo $this->lang->line('xin_visit_purpose');?>" name="visit_purpose" type="text">
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="visit_place"><?php echo $this->lang->line('xin_visit_place');?></label>
                      <input class="form-control" placeholder="<?php echo $this->lang->line('xin_visit_place');?>" name="visit_place" type="text">
                    </div>
                  </div>
                </div>
                
                
              </div>
              <div class="col-md-6">
                
                <div class="row">
                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="expected_budget"><?php echo $this->lang->line('xin_expected_travel_budget');?></label>
                      <input class="form-control" placeholder="<?php echo $this->lang->line('xin_expected_travel_budget');?>" name="expected_budget" type="text">
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="actual_budget"><?php echo $this->lang->line('xin_actual_travel_budget');?></label>
                      <input class="form-control" placeholder="<?php echo $this->lang->line('xin_actual_travel_budget');?>" name="actual_budget" type="text">
                    </div>
                  </div>
                </div>
               
                <div class="row">
                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="travel_mode"><?php echo $this->lang->line('xin_travel_mode');?></label>
                      <select class="select2" data-plugin="select_hrm" data-placeholder="<?php echo $this->lang->line('xin_travel_mode');?>" name="travel_mode">
                         <option value=""></option>
                        <?php foreach($transport_arrangement_types as $transport_arr_type) {?>
                        <option value="<?php echo $transport_arr_type->arrangement_type_id;?>"> <?php echo $transport_arr_type->type;?></option>
                        <?php } ?>
                      </select>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="arrangement_type"><?php echo $this->lang->line('xin_arragement_type');?></label>
                      <select class="select2" data-plugin="select_hrm" data-placeholder="<?php echo $this->lang->line('xin_arragement_type');?>" name="arrangement_type">
                         <option value=""></option>
                        <?php foreach($travel_arrangement_types as $travel_arr_type) {?>
                        <option value="<?php echo $travel_arr_type->arrangement_type_id;?>"> <?php echo $travel_arr_type->type;?></option>
                        <?php } ?>
                      </select>
                    </div>
                  </div>
                </div>
                
                <div class="row">
                  <div class="col-md-12">
                    <div class="form-group">
                      <label for="description"><?php echo $this->lang->line('xin_description');?></label>
                      <textarea class="form-control textarea" placeholder="<?php echo $this->lang->line('xin_description');?>" name="description" cols="30" rows="5" id="description"></textarea>
                    </div>
                  </div>
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
    <h3 class="box-title"> <?php echo $this->lang->line('xin_list_all');?> <?php echo $this->lang->line('left_travels');?> </h3>
  </div>
  <div class="box-body">
    <div class="box-datatable table-responsive">
      <table class="datatables-demo table table-striped table-bordered" id="xin_table">
        <thead>
          <tr>
            <th width="150px"><center> <?php echo $this->lang->line('xin_action');?></center></th>
            <th width="100px"><center> Tanggal <br>Dinas Mulai </center></th>
            <th width="100px"><center> Tanggal <br>Dinas Sampai </center></th>
            <th width="250px;"><center>Nama Karyawan</center></th>
            <th width="220px;"><center>Tempat Kunjungan</center></th>
            <th width="220px"><center> Transportasi </center></th>
            <th ><center>Keterangan Dinas </center></th>            
            <th width="100px"><center> Status<br>Proses</center></th>           
          </tr>
        </thead>
      </table>
    </div>
  </div>
</div>
