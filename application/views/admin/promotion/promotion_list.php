<?php
/* Promotion view
*/
?>
<?php $session = $this->session->userdata('username');?>
<?php $get_animate = $this->Core_model->get_content_animate();?>
<?php $role_resources_ids = $this->Core_model->user_role_resource(); ?>
<?php if(in_array('0662',$role_resources_ids)) {?>
<?php $user_info = $this->Core_model->read_employee_info($session['user_id']);?>
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
        <?php $attributes = array('name' => 'add_promotion', 'id' => 'xin-form', 'autocomplete' => 'off');?>
        <?php $hidden = array('user_id' => $session['user_id']);?>
        <?php echo form_open('admin/promotion/add_promotion', $attributes, $hidden);?>
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
              
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group" id="employee_ajax">
                          <label for="employee"><?php echo $this->lang->line('xin_promotion_for');?></label>
                          <select disabled="disabled" name="employee_id" id="select2-demo-6" class="form-control" data-plugin="select_hrm" data-placeholder="<?php echo $this->lang->line('xin_choose_an_employee');?>">
                            <option value=""></option>
                          </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-group" id="ajx_designation">
                        <label for="designation">Posisi yang Dipromosikan</label>
                        <select disabled="disabled" class="form-control" name="designation_id" data-plugin="select_hrm"  id="filter_designation" data-placeholder="Pilih Posisi">
                          <option value=""></option>
                        </select>
                      </div>
                    </div>
                </div>
                <div class="row">
                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="title"><?php echo $this->lang->line('xin_promotion_title');?></label>
                      <select name="title" id="title" class="form-control" data-plugin="select_hrm" data-placeholder="<?php echo $this->lang->line('xin_promotion_title');?>">
                          <option value=""></option>
                          <option value="1"><?php echo $this->lang->line('xin_payroll_full_tTime');?></option>
                          <option value="2"><?php echo $this->lang->line('xin_payroll_part_tTime');?></option>
                          <option value="3"><?php echo $this->lang->line('xin_payroll_free_lance');?></option>                        
                      </select>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="promotion_date"><?php echo $this->lang->line('xin_promotion_date');?></label>
                      <input class="form-control date" placeholder="<?php echo $this->lang->line('xin_promotion_date');?>" readonly name="promotion_date" type="text">
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
    <h3 class="box-title"> <?php echo $this->lang->line('xin_list_all');?> <?php echo $this->lang->line('left_promotions');?> </h3>
  </div>
  <div class="box-body">
    <div class="box-datatable table-responsive">
      <table class="datatables-demo table table-striped table-bordered" id="xin_table">
        <thead>
          <tr>
            <th width="150px"><center> <?php echo $this->lang->line('xin_action');?></center></th>
            <th width="120px"><center> Tanggal<br>Promosi </center></th>
            <th width="300px"><center><i class="fa fa-user"></i> <?php echo $this->lang->line('xin_employee_name');?></center></th>
            <th width="300px"><center><i class="fa fa-tasks"></i> <?php echo $this->lang->line('xin_promotion');?></center></th>
            <th ><center><i class="fa fa-tasks"></i> <?php echo $this->lang->line('xin_promotion_title_desc');?></center></th>
            <th width="100px"><center>Status<br>Aktif</center></th>
          </tr>
        </thead>
      </table>
    </div>
  </div>
</div>
