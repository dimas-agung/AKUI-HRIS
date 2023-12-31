<?php
/* Warning view
*/
?>
<?php $session = $this->session->userdata('username');?>
<?php $get_animate = $this->Core_model->get_content_animate();?>
<?php $role_resources_ids = $this->Core_model->user_role_resource(); ?>

<?php if(in_array('0632',$role_resources_ids)) {?>
<?php $user_info = $this->Core_model->read_user_info($session['user_id']);?>
<div class="box mb-4 <?php echo $get_animate;?>">
  <div id="accordion">
    <div class="box-header  with-border">
      <h3 class="box-title"><i class="fa fa-plus-circle"></i> <?php echo $this->lang->line('xin_add_new');?> </h3>
      <div class="box-tools pull-right"> <a class="text-dark collapsed" data-toggle="collapse" href="#add_form" aria-expanded="false">
        <button type="button" class="btn btn-xs btn-primary"> <span class="fa fa-plus-circle"></span> <?php echo $this->lang->line('xin_add_new');?></button>
        </a> </div>
    </div>
    <div id="add_form" class="collapse add-form <?php echo $get_animate;?>" data-parent="#accordion" style="">
      <div class="box-body">
        <?php $attributes = array('name' => 'add_warning', 'id' => 'xin-form', 'autocomplete' => 'off');?>
        <?php $hidden = array('user_id' => $session['user_id']);?>
        <?php echo form_open('admin/warning/add_warning', $attributes, $hidden);?>
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
                  <label for="warning_to"><?php echo $this->lang->line('xin_warning_to');?></label>
                  <select name="warning_to" id="select2-demo-6" class="form-control" data-plugin="select_hrm" data-placeholder="<?php echo $this->lang->line('xin_choose_an_employee');?>">
                    <option value=""></option>
                  </select>
                </div>
                <div class="row">
                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="type"><?php echo $this->lang->line('xin_warning_type');?></label>
                      <select class="select2" data-plugin="select_hrm" data-placeholder="<?php echo $this->lang->line('xin_warning_type');?>" name="type">
                        <option value=""></option>
                        <?php foreach($all_warning_types as $warning_type) {?>
                        <option value="<?php echo $warning_type->warning_type_id?>"><?php echo $warning_type->type;?></option>
                        <?php } ?>
                      </select>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="subject"><?php echo $this->lang->line('xin_subject');?></label>
                      <input class="form-control" placeholder="<?php echo $this->lang->line('xin_subject');?>" name="subject" type="text">
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-md-6">
                <div class="row">
                  <div class="col-md-12">
                    <div class="form-group">
                      <label for="description"><?php echo $this->lang->line('xin_description');?></label>
                      <textarea class="form-control textarea" placeholder="<?php echo $this->lang->line('xin_description');?>" name="description" cols="30" rows="5" id="description"></textarea>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-6">
                    <div class="form-group" id="warning_employee_ajax">
                      <label for="warning_by"><?php echo $this->lang->line('xin_warning_by');?></label>
                      <select name="warning_by" id="select2-demo-6" class="form-control" data-plugin="select_hrm" data-placeholder="<?php echo $this->lang->line('xin_choose_an_employee');?>">
                        <option value=""></option>
                      </select>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="warning_date"><?php echo $this->lang->line('xin_warning_date');?></label>
                      <input class="form-control date" placeholder="<?php echo $this->lang->line('xin_warning_date');?>" readonly name="warning_date" type="text">
                    </div>
                  </div>
                </div>
                
              </div>
            </div>
            <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                  <fieldset class="form-group">
                    <label for="attachment"><?php echo $this->lang->line('xin_attachment');?></label>
                    <input type="file" class="form-control-file" id="attachment" name="attachment">
                    <small><?php echo $this->lang->line('xin_company_file_type');?></small>
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
    <h3 class="box-title"> <?php echo $this->lang->line('xin_list_all');?> <?php echo $this->lang->line('left_warnings');?> </h3>
  </div>
  <div class="box-body">
    <div class="box-datatable table-responsive">
      <table class="datatables-demo table table-striped table-bordered" id="xin_table">
        <thead>
          <tr>
            <th width="80px"><center> <?php echo $this->lang->line('xin_action');?></center></th>
            <th width="100px"><center> Tanggal<br>Peringatan </center></th>
            <th width="100px"><center> Status<br>Karyawan </center></th>
            <th width="250px"><center> Nama Karyawan</center></th>
            <th width="220px"><center> No. Peringatan </center></th>                  
            <th width="220px"><center> <i class="fa fa-user"></i> <?php echo $this->lang->line('xin_warning_by');?></center></th>    
            <th ><center> Keterangan Peringatan</center></th>           
            <th width="100px"><center> Status<br>Proses</center></th>
          </tr>
        </thead>
      </table>
    </div>
  </div>
</div>
