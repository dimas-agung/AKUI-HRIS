<?php
/* Employee Exit view
*/
?>
<?php $session = $this->session->userdata('username');?>
<?php $get_animate = $this->Core_model->get_content_animate();?>
<?php $role_resources_ids = $this->Core_model->user_role_resource(); ?>
<?php $xuser_info = $this->Core_model->read_user_info($session['user_id']);?>


<?php if(in_array('0612',$role_resources_ids)) {?>
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
            <?php $attributes = array('name' => 'add_exit', 'id' => 'xin-form', 'autocomplete' => 'off');?>
            <?php $hidden = array('user_id' => $session['user_id']);?>
            <?php echo form_open('admin/employee_exit/add_exit', $attributes, $hidden);?>
            <div class="bg-white">
              <div class="box-block">
                <div class="row">
                  <div class="col-md-6">
                 
                    <div class="form-group">
                      <label for="first_name"><?php echo $this->lang->line('left_company');?><i class="hris-asterisk">*</i></label>
                      <select class="form-control" name="company_id" id="aj_company" data-plugin="select_hrm" data-placeholder="<?php echo $this->lang->line('left_company');?>">
                        <option value=""></option>
                        <?php foreach($get_all_companies as $company) {?>
                        <option value="<?php echo $company->company_id?>"><?php echo $company->name?></option>
                        <?php } ?>
                      </select>
                    </div>
                   
                    <div class="form-group" id="employee_ajax">
                      <label for="employee"><?php echo $this->lang->line('xin_employee_to_exit');?><i class="hris-asterisk">*</i></label>
                      <select disabled="disabled" name="employee_id" id="select2-demo-6" class="form-control" data-plugin="select_hrm" data-placeholder="<?php echo $this->lang->line('xin_choose_an_employee');?>">
                        <option value=""></option>
                      </select>
                    </div>

                    <div class="row">
                      <div class="col-md-6">
                        <div class="form-group">
                          <label for="exit_date"><?php echo $this->lang->line('xin_exit_date');?><i class="hris-asterisk">*</i></label>
                          <input class="form-control date" placeholder="<?php echo $this->lang->line('xin_exit_date');?>"  name="exit_date" type="text">
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="form-group">
                          <label for="type"><?php echo $this->lang->line('xin_type_of_exit');?><i class="hris-asterisk">*</i></label>
                          <select class="select2" data-plugin="select_hrm" data-placeholder="<?php echo $this->lang->line('xin_type_of_exit');?>" name="type">
                            <option value=""></option>
                            <?php foreach($all_exit_types as $exit_type) {?>
                            <option value="<?php echo $exit_type->exit_type_id?>"><?php echo $exit_type->type;?></option>
                            <?php } ?>
                          </select>
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-md-6">
                        <div class="form-group">
                          <label for="exit_interview"><?php echo $this->lang->line('xin_exit_interview');?><i class="hris-asterisk">*</i></label>
                          <select class="select2" data-plugin="select_hrm" data-placeholder="<?php echo $this->lang->line('xin_exit_interview');?>" name="exit_interview">
                            <option value="1"><?php echo $this->lang->line('xin_yes');?></option>
                            <option value="0"><?php echo $this->lang->line('xin_no');?></option>
                          </select>
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="form-group">
                          <label for="is_inactivate_account"><?php echo $this->lang->line('xin_exit_inactive_employee_account');?><i class="hris-asterisk">*</i></label>
                          <select class="select2" data-plugin="select_hrm" data-placeholder="<?php echo $this->lang->line('xin_exit_inactive_employee_account');?>" name="is_inactivate_account">
                            <option value="1"><?php echo $this->lang->line('xin_yes');?></option>
                            <option value="0"><?php echo $this->lang->line('xin_no');?></option>
                          </select>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="row">
                      
                      <div class="col-md-12">
                        <div class="form-group">
                          <label for="type"> Jenis Alasan Resign <i class="hris-asterisk">*</i></label>
                          <select class="select2" data-plugin="select_hrm" data-placeholder=" Jenis Alasan Resign " name="type_reason">
                            <option value=""></option>
                            <?php foreach($all_exit_types_reason as $exit_type_reason) {?>
                            <option value="<?php echo $exit_type_reason->exit_type_id?>"><?php echo $exit_type_reason->type;?></option>
                            <?php } ?>
                          </select>
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-md-12">
                        <div class="form-group">
                          <label for="description"><?php echo $this->lang->line('xin_description');?></label>
                          <textarea class="form-control textarea" placeholder="<?php echo $this->lang->line('xin_description');?>" name="reason" rows="10" id="reason"></textarea>
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
    <h3 class="box-title">  <?php echo $this->lang->line('xin_list_all');?> <?php echo $this->lang->line('xin_employee_exit');?> </h3>
   
      <div class="box-tools pull-right"> 

     </div>
    
  </div>
  <div class="box-body">
    <div class="card-datatable table-responsive">
      <table class="datatables-demo table table-striped table-bordered" id="xin_table">
        <thead>
          <tr>
            <th width="80px"><center> <?php echo $this->lang->line('xin_action');?></center></th>
            <th width="100px"><center> Tanggal<br>Keluar </center></th>
            <th width="100px"><center>  Status<br>Karyawan </center></th>           
            <th width="250px"><center> Nama Karyawan</center></th>
            <th width="220px"><center> Departemen</center></th>
            <th width="220px"><center> Jenis Keluar</center></th>
            <th ><center> Keterangan Keluar </center></th>            
            <th width="100px"><center> Status<br>Proses </center></th>			      
          </tr>
        </thead>
      </table>
    </div>
  </div>
</div>