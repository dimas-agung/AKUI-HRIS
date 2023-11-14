<?php
/* Training view
*/
?>
<?php $session = $this->session->userdata('username');?>
<?php $get_animate = $this->Core_model->get_content_animate();?>
<?php $role_resources_ids = $this->Core_model->user_role_resource(); ?>
<?php if(in_array('54',$role_resources_ids)) {?>
<?php $user_info = $this->Core_model->read_user_info($session['user_id']);?>
<div class="box mb-4 <?php echo $get_animate;?>">
  <div id="accordion">
    <div class="box-header with-border">
      <h3 class="box-title"> <i class="fa fa-plus-circle"></i> Tambah Baru </h3>
      <div class="box-tools pull-right"> <a class="text-dark collapsed" data-toggle="collapse" href="#add_form" aria-expanded="false">
        <button type="button" class="btn btn-xs btn-primary"> <span class="fa fa-plus-circle"></span> <?php echo $this->lang->line('xin_add_new');?></button>
        </a> </div>
    </div>
    <div id="add_form" class="collapse add-form <?php echo $get_animate;?>" data-parent="#accordion" style="">
      <div class="box-body">
        <?php $attributes = array('name' => 'add_training', 'id' => 'xin-form', 'autocomplete' => 'off');?>
        <?php $hidden = array('_user' => $session['user_id']);?>
        <?php echo form_open('admin/training/add_training', $attributes, $hidden);?>
        <div class="bg-white">
          <div class="box-block">
            <div class="row">
              <div class="col-md-6">

                <div class="row">
                  <div class="col-md-12">                   
                    <div class="form-group">
                      <label for="company_name"><?php echo $this->lang->line('module_company_title');?></label>
                      <select class="form-control" name="company" id="aj_company" data-plugin="select_hrm" data-placeholder="<?php echo $this->lang->line('module_company_title');?>">
                        <option value=""><?php echo $this->lang->line('xin_select_one');?></option>
                        <?php foreach($all_companies as $company) {?>
                        <option value="<?php echo $company->company_id;?>"> <?php echo $company->name;?></option>
                        <?php } ?>
                      </select>
                    </div>                   
                  </div>
                </div>

                <div class="row">
                  <div class="col-md-12">
                    <div class="form-group" id="employee_ajax">
                      <label for="employee" class="control-label">Peserta Pelatihan </label>
                      <select multiple class="form-control" name="employee_id[]" data-plugin="select_hrm" data-placeholder="Peserta Pelatihan ">
                        <option value=""></option>
                      </select>
                    </div>
                  </div>
                </div>

                <div class="row">
                  <div class="col-md-12">
                    <div class="form-group">
                      <label for="trainer_option"> Jenis Pelatih </label>
                      <select disabled="disabled" class="form-control" name="trainer_option" id="trainer_option" data-plugin="select_hrm" data-placeholder="<?php echo $this->lang->line('xin_trainer_opt_title');?>">
                        <option value=""></option>
                        <option value="1"><?php echo $this->lang->line('xin_internal_title');?></option>
                        <option value="2"><?php echo $this->lang->line('xin_external_title');?></option>
                      </select>
                    </div>
                  </div>                  
                </div> 

                <div class="row">
                  <div class="col-md-12">
                    <div class="form-group" id="trainers_data">
                      <label for="trainer">Nama Pelatih</label>
                      <select disabled="disabled" class="form-control" name="trainer" data-plugin="select_hrm" data-placeholder="Nama Pelatih">
                        <option value=""></option>
                      </select>
                    </div>                    
                  </div>
                </div>               
               
              </div>
              <div class="col-md-6">

                <div class="row">
                  <div class="col-md-12">
                    <div class="form-group">
                      <label for="training_type"> Jenis Pelatihan </label>
                      <select class="form-control" name="training_type" data-plugin="select_hrm" data-placeholder="Jenis Pelatihan ">
                        <option value=""></option>
                        <?php foreach($all_training_types as $training_type) {?>
                        <option value="<?php echo $training_type->training_type_id?>"><?php echo $training_type->type?></option>
                        <?php } ?>
                      </select>
                    </div>
                  </div>                  
                </div>

                <div class="row">
                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="start_date"><?php echo $this->lang->line('xin_start_date');?></label>
                      <input class="form-control date" placeholder="<?php echo $this->lang->line('xin_start_date');?>" readonly name="start_date" type="text" value="">
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="end_date"><?php echo $this->lang->line('xin_end_date');?></label>
                      <input class="form-control date" placeholder="<?php echo $this->lang->line('xin_end_date');?>" readonly name="end_date" type="text" value="">
                    </div>
                  </div>
                </div>

                <div class="row">
                  <div class="col-md-12">
                  <div class="form-group">
                  <label for="description">Materi Pelatihan</label>
                  <textarea class="form-control textarea" placeholder="Materi Pelatihan" name="description" rows="8" id="description"></textarea>
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
    <h3 class="box-title"> <?php echo $this->lang->line('xin_list_all');?> <?php echo $this->lang->line('left_training');?> </h3>
  </div>
  <div class="box-body">
    <div class="box-datatable table-responsive">
      <table class="datatables-demo table table-striped table-bordered" id="xin_table">
        <thead>
          <tr>
            <th width="220"><?php echo $this->lang->line('xin_action');?></th>
            <th width="100">Status</th>
            <th width="220"><i class="fa fa-calendar"></i> <?php echo $this->lang->line('xin_training_duration');?></th>
            <th width="300"> Jenis Pelatihan </th>
            
            <th ><i class="fa fa-users"></i> Peserta Pelatihan </th>
          </tr>
        </thead>
      </table>
    </div>
  </div>
</div>
