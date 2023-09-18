<?php
/* overtime view
*/
?>
<?php $session = $this->session->userdata('username');?>
<?php $get_animate = $this->Core_model->get_content_animate();?>
<?php $role_resources_ids = $this->Core_model->user_role_resource(); ?>

<?php if(in_array('0682',$role_resources_ids)) {?>
<?php $user_info = $this->Core_model->read_user_info($session['user_id']);?>

<div class="box mb-4 <?php echo $get_animate;?>">
  <div id="accordion">
    <div class="box-header with-border">
      <h3 class="box-title"><i class="fa fa-plus-circle"></i> <?php echo $this->lang->line('xin_add_new');?> </h3>
      <div class="box-tools pull-right"> <a class="text-dark collapsed" data-toggle="collapse" href="#add_form" aria-expanded="false">
        <button type="button" class="btn btn-xs btn-primary"> <span class="fa fa-plus-circle"></span> <?php echo $this->lang->line('xin_add_new');?></button>
        </a> </div>
    </div>
    <div id="add_form" class="collapse add-form <?php echo $get_animate;?>" data-parent="#accordion" style="">
      <div class="box-body">
        <?php $attributes = array('name' => 'add_overtime_bulanan', 'id' => 'xin-form', 'autocomplete' => 'off');?>
        <?php $hidden = array('_user' => $session['user_id']);?>
        <?php echo form_open('admin/overtime_bulanan/add_overtime_bulanan', $attributes, $hidden);?>
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
                      <label for="employee" class="control-label"><?php echo $this->lang->line('xin_employee');?></label>
                      <select multiple class="form-control" name="employee_id[]" data-plugin="select_hrm" data-placeholder="Pilih Karyawan akan lembur">
                        <option value=""></option>
                      </select>
                    </div>
                  </div>
                </div>
              
                <div class="row">                 
                  <div class="col-md-12">
                    <div class="form-group">
                      <label for="overtime_type"><?php echo $this->lang->line('left_overtime_type');?></label>
                      <select class="form-control" name="overtime_type" data-plugin="select_hrm" data-placeholder="Pilih Jenis Lembur">
                        <option value=""></option>
                        <?php foreach($all_overtime_types as $overtime_type) {?>
                        <option value="<?php echo $overtime_type->overtime_type_id?>"><?php echo $overtime_type->type?></option>
                        <?php } ?>
                      </select>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-12">
                      <div class="form-group">
                        <label for="reports_to"><?php echo $this->lang->line('xin_reports_overtime');?></label>
                        <select name="reports_to" class="form-control" data-plugin="select_hrm" data-placeholder="Pilih Pemberi Lembur">
                          <option value=""></option>
                          <?php foreach(get_reports_to() as $reports_to) {?>
                              <option value="<?php echo $reports_to->user_id?>"><?php echo $reports_to->first_name.' '.$reports_to->last_name;?></option>
                          <?php } ?>
                        </select>
                      </div>
                   </div>
                 </div>          
              </div>

              <div class="col-md-6">
                <div class="row">
                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="date"><?php echo $this->lang->line('xin_e_details_date_overtime');?></label>
                      <input class="form-control attendance_date_m" placeholder="Pilih Tanggal Lembur" readonly="true" id="attendance_date_m" name="attendance_date_m" type="text">
                    </div>
                  </div>
                  <div class="col-md-6">
                        <div class="form-group">
                          <label for="ov_status" class="control-label"><?php echo $this->lang->line('xin_tipe_lembur');?><i class="hris-asterisk">*</i></label>
                          <select class="form-control" name="ov_status" data-plugin="select_hrm" data-placeholder="Pilih Tipe Lembur">
                            <option value=""><?php echo $this->lang->line('xin_tipe_lembur');?></option>
                            <option value="TS" ><?php echo $this->lang->line('xin_tipe_lembur_tetap');?></option>
                            <option value="TB" ><?php echo $this->lang->line('xin_tipe_lembur_beda');?></option>                                                            
                          </select>
                        </div>
                      </div>
                </div>
                <div class="row">
                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="clock_in">L1 - Mulai </label>
                      <input class="form-control timepicker_m" placeholder="L1 - Mulai" readonly="true" id="clock_in_1" value ="00:00" name="clock_in_1" type="text">
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="clock_out">L1 - Sampai</label>
                      <input class="form-control timepicker_m" placeholder="L1 - Sampai" readonly="true" id="clock_out_1" value ="00:00" name="clock_out_1" type="text">
                    </div>
                  </div>
                </div>

                <div class="row">
                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="clock_in">L2 - Mulai</label>
                      <input class="form-control timepicker_m" placeholder="L2 - Mulai" readonly="true" id="clock_in_2" value ="00:00" name="clock_in_2" type="text">
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="clock_out">L2 - Sampai</label>
                      <input class="form-control timepicker_m" placeholder="L2 - Sampai" readonly="true" id="clock_out_2" value ="00:00" name="clock_out_2" type="text">
                    </div>
                  </div>
                </div>

                <div class="row">
                  <div class="col-md-12">
                  <div class="form-group">
                  <label for="description"><?php echo $this->lang->line('xin_description');?></label>
                  <textarea class="form-control textarea" placeholder="<?php echo $this->lang->line('xin_description');?>" name="description" rows="5" id="description"></textarea>
                </div>
              </div>
              </div>
              
              </div>
            </div>
            <?php $count_module_attributes = $this->Custom_fields_model->count_overtime_module_attributes();?>
            <?php if($count_module_attributes > 0):?>
            <div class="row">
              <?php $module_attributes = $this->Custom_fields_model->overtime_hris_module_attributes();?>
              <?php foreach($module_attributes as $mattribute):?>
              <?php if($mattribute->attribute_type == 'date'){?>
              <div class="col-md-4">
                <div class="form-group">
                  <label for="<?php echo $mattribute->attribute;?>"><?php echo $mattribute->attribute_label;?></label>
                  <input class="form-control date" placeholder="<?php echo $mattribute->attribute_label;?>" name="<?php echo $mattribute->attribute;?>" type="text">
                </div>
              </div>
              <?php } else if($mattribute->attribute_type == 'select'){?>
              <div class="col-md-4">
                <?php $iselc_val = $this->Custom_fields_model->get_attribute_selection_values($mattribute->custom_field_id);?>
                <div class="form-group">
                  <label for="<?php echo $mattribute->attribute;?>"><?php echo $mattribute->attribute_label;?></label>
                  <select class="form-control" name="<?php echo $mattribute->attribute;?>" data-plugin="select_hrm" data-placeholder="<?php echo $mattribute->attribute_label;?>">
                    <?php foreach($iselc_val as $selc_val) {?>
                    <option value="<?php echo $selc_val->attributes_select_value_id?>"><?php echo $selc_val->select_label?></option>
                    <?php } ?>
                  </select>
                </div>
              </div>
              <?php } else if($mattribute->attribute_type == 'multiselect'){?>
              <div class="col-md-4">
                <?php $imulti_selc_val = $this->Custom_fields_model->get_attribute_selection_values($mattribute->custom_field_id);?>
                <div class="form-group">
                  <label for="<?php echo $mattribute->attribute;?>"><?php echo $mattribute->attribute_label;?></label>
                  <select multiple="multiple" class="form-control" name="<?php echo $mattribute->attribute;?>[]" data-plugin="select_hrm" data-placeholder="<?php echo $mattribute->attribute_label;?>">
                    <?php foreach($imulti_selc_val as $multi_selc_val) {?>
                    <option value="<?php echo $multi_selc_val->attributes_select_value_id?>"><?php echo $multi_selc_val->select_label?></option>
                    <?php } ?>
                  </select>
                </div>
              </div>
              <?php } else if($mattribute->attribute_type == 'textarea'){?>
              <div class="col-md-8">
                <div class="form-group">
                  <label for="<?php echo $mattribute->attribute;?>"><?php echo $mattribute->attribute_label;?></label>
                  <input class="form-control" placeholder="<?php echo $mattribute->attribute_label;?>" name="<?php echo $mattribute->attribute;?>" type="text">
                </div>
              </div>
              <?php } else if($mattribute->attribute_type == 'fileupload'){?>
              <div class="col-md-4">
                <div class="form-group">
                  <label for="<?php echo $mattribute->attribute;?>"><?php echo $mattribute->attribute_label;?></label>
                  <input class="form-control-file" name="<?php echo $mattribute->attribute;?>" type="file">
                </div>
              </div>
              <?php } else { ?>
              <div class="col-md-4">
                <div class="form-group">
                  <label for="<?php echo $mattribute->attribute;?>"><?php echo $mattribute->attribute_label;?></label>
                  <input class="form-control" placeholder="<?php echo $mattribute->attribute_label;?>" name="<?php echo $mattribute->attribute;?>" type="text">
                </div>
              </div>
              <?php }	?>
              <?php endforeach;?>
            </div>
            <?php endif;?>
            <div class="form-actions box-footer">
              <button type="submit" class="btn btn-primary"> <i class="fa fa-save"></i> <?php echo $this->lang->line('xin_save');?> </button>
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
    <h3 class="box-title"> <?php echo $this->lang->line('xin_list_all');?> <?php echo $this->lang->line('left_overtime');?> </h3>
  </div>
  <div class="box-body">
    <div class="box-datatable table-responsive">
      <table class="datatables-demo table table-striped table-bordered" id="xin_table">
        <thead>
          <tr>
            <th width="150px"><center><?php echo $this->lang->line('xin_action');?></center></th>
            <th width="120px"><center> Tanggal<br>Lembur </center></th>
            <th width="300px"><center><i class="fa fa-clock-o"></i> Durasi Lembur</center></th>
            <th width="25%"><center><i class="fa fa-users"></i> <?php echo $this->lang->line('xin_overtime_employees');?></center></th>
            <th><center><i class="fa fa-tasks"></i> <?php echo $this->lang->line('left_overtime_desc');?></center></th>           
            <th width="18%"><center><i class="fa fa-info-circle"></i> <?php echo $this->lang->line('dashboard_history');?></center></th>
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

