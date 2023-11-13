<?php
/* Office Shift view
*/
?>
<?php $session = $this->session->userdata('username');?>
<?php $get_animate = $this->Core_model->get_content_animate();?>
<?php $role_resources_ids = $this->Core_model->user_role_resource(); ?>
<?php if(in_array('0812',$role_resources_ids)) {?>
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
        <?php $attributes = array('name' => 'add_office_shift', 'id' => 'xin-form', 'autocomplete' => 'off');?>
        <?php $hidden = array('user_id' => $session['user_id']);?>
        <?php echo form_open('admin/pengaturan/add_office_shift', $attributes, $hidden);?>
        <div class="bg-white">
          <div class="box-block" style="padding: 15px;">
            
            <div class="row">

              <div class="col-md-6">

                <div class="form-group row">
                    <label for="time" class="col-md-3"><?php echo $this->lang->line('module_company_title');?></label>
                    <div class="col-md-9">
                       <select class="form-control" name="company_id" id="aj_company" data-plugin="select_hrm" data-placeholder="<?php echo $this->lang->line('module_company_title');?>">
                        <option value=""><?php echo $this->lang->line('xin_select_one');?></option>
                        <?php foreach($get_all_companies as $company) {?>
                        <option value="<?php echo $company->company_id;?>"> <?php echo $company->name;?></option>
                        <?php } ?>
                      </select>
                    </div>
                </div>

                <div class="form-group row">
                  <label for="time" class="col-md-3"><?php echo $this->lang->line('xin_e_details_month_work');?></label>
                  <div class="col-md-9">
                    <select class="form-control input-sm" name="payroll_id" id="payroll_id" data-plugin="select_hrm" data-placeholder="<?php echo $this->lang->line('xin_e_details_month_work');?>" required>
                     <option value=""></option>
                     <?php foreach($all_bulan_gaji as $bulan_gaji) {?>
                        <option value="<?php echo $bulan_gaji->payroll_id?>"><?php echo $bulan_gaji->desc?></option>
                     <?php } ?>
                    </select>
                  </div>
                </div>            

                <div class=" row">
                  <label for="time" class="col-md-3"><?php echo $this->lang->line('xin_e_details_timeperiod');?></label>
                   <div class="col-md-4">
                    <div class="form-group">
                      <input class="form-control attendance_date" placeholder="<?php echo $this->lang->line('xin_select_date');?>" readonly id="start_date" name="start_date" type="text" value="<?php echo date('Y-m-d');?>">
                    </div>
                  </div>
                  <div class="col-md-5">
                    <div class="form-group">
                      <input class="form-control attendance_date" placeholder="<?php echo $this->lang->line('xin_select_date');?>" readonly id="end_date" name="end_date" type="text" value="<?php echo date('Y-m-d');?>">
                    </div>
                  </div>
                </div>    

                <div class="form-group row">
                  <label for="time" class="col-md-3"><?php echo $this->lang->line('xin_employee_jenis_pola');?></label>
                  <div class="col-md-9">
                     <select class="form-control" name="jenis" data-plugin="select_hrm" data-placeholder="<?php echo $this->lang->line('xin_employee_jenis_pola');?>">
                      <option value=""><?php echo $this->lang->line('xin_select_one');?></option>
                      <option value="1" selected><?php echo $this->lang->line('xin_employee_reguler');?></option>
                  </select>
                  </div>
                </div>            
                  
                <div class="form-group row">
                  <label for="time" class="col-md-3"><?php echo $this->lang->line('xin_shift_name');?></label>
                  <div class="col-md-9">
                    <input class="form-control" placeholder="<?php echo $this->lang->line('xin_shift_name');?>" name="shift_name" type="text" value="" id="name">
                  </div>
                </div>      

                <!-- <div class="row">
                  <div class="col-md-12">
                    <div class="form-group" id="employee_ajax">
                      <label for="employee" class="control-label"><?php echo $this->lang->line('xin_employee_list');?></label>
                      <select multiple class="form-control" name="employee_id[]" data-plugin="select_hrm" data-placeholder="<?php echo $this->lang->line('xin_employee_list');?>">
                        <option value=""></option>
                      </select>
                    </div>
                  </div>
                </div> -->

              </div>

              <div class="col-md-6">
              
                 <div style="background-color: #cbf3e6;padding: 10px;margin-bottom: 15px;">
                    Jadwal Kerja Harian :
                  </div>

                  <div class="form-group row">
                    <label for="time" class="col-md-3"><?php echo $this->lang->line('xin_monday');?></label>
                    <div class="col-md-4">
                      <input class="form-control timepicker clear-1" placeholder="<?php echo $this->lang->line('xin_in_time');?>" readonly name="monday_in_time" type="text" value="00:00"/>
                    </div>
                    <div class="col-md-4">
                      <input class="form-control timepicker clear-1" placeholder="<?php echo $this->lang->line('xin_out_time');?>" readonly name="monday_out_time" type="text" value="00:00"/>
                    </div>
                    <div class="col-md-1">
                      <button type="button" class="btn btn-xs btn-danger clear-time" data-clear-id="1"><i class="fa fa-times"></i></button>
                    </div>
                  </div>

                  <div class="form-group row">
                    <label for="time" class="col-md-3"><?php echo $this->lang->line('xin_tuesday');?></label>
                    <div class="col-md-4">
                      <input class="form-control timepicker clear-2" placeholder="<?php echo $this->lang->line('xin_in_time');?>" readonly name="tuesday_in_time" type="text" value="00:00"/>
                    </div>
                    <div class="col-md-4">
                      <input class="form-control timepicker clear-2" placeholder="<?php echo $this->lang->line('xin_out_time');?>" readonly name="tuesday_out_time" type="text" value="00:00"/>
                    </div>
                    <div class="col-md-1">
                      <button type="button" class="btn btn-xs btn-danger clear-time" data-clear-id="2"><i class="fa fa-times"></i></button>
                    </div>
                  </div>
                  
                  <div class="form-group row">
                    <label for="time" class="col-md-3"><?php echo $this->lang->line('xin_wednesday');?></label>
                    <div class="col-md-4">
                      <input class="form-control timepicker clear-3" placeholder="<?php echo $this->lang->line('xin_in_time');?>" readonly name="wednesday_in_time" type="text" value="00:00"/>
                    </div>
                    <div class="col-md-4">
                      <input class="form-control timepicker clear-3" placeholder="<?php echo $this->lang->line('xin_out_time');?>" readonly name="wednesday_out_time" type="text" value="00:00"/>
                    </div>
                    <div class="col-md-1">
                      <button type="button" class="btn btn-xs btn-danger clear-time" data-clear-id="3"><i class="fa fa-times"></i></button>
                    </div>
                  </div>
                  
                  <div class="form-group row">
                    <label for="time" class="col-md-3"><?php echo $this->lang->line('xin_thursday');?></label>
                    <div class="col-md-4">
                      <input class="form-control timepicker clear-4" placeholder="<?php echo $this->lang->line('xin_in_time');?>" readonly name="thursday_in_time" type="text" value="00:00"/>
                    </div>
                    <div class="col-md-4">
                      <input class="form-control timepicker clear-4" placeholder="<?php echo $this->lang->line('xin_out_time');?>" readonly name="thursday_out_time" type="text" value="00:00"/>
                    </div>
                    <div class="col-md-1">
                      <button type="button" class="btn btn-xs btn-danger clear-time" data-clear-id="4"><i class="fa fa-times"></i></button>
                    </div>
                  </div>

                  <div class="form-group row">
                    <label for="time" class="col-md-3"><?php echo $this->lang->line('xin_friday');?></label>
                    <div class="col-md-4">
                      <input class="form-control timepicker clear-5" placeholder="<?php echo $this->lang->line('xin_in_time');?>" readonly name="friday_in_time" type="text" value="00:00"/>
                    </div>
                    <div class="col-md-4">
                      <input class="form-control timepicker clear-5" placeholder="<?php echo $this->lang->line('xin_out_time');?>" readonly name="friday_out_time" type="text" value="00:00"/>
                    </div>
                    <div class="col-md-1">
                      <button type="button" class="btn btn-xs btn-danger clear-time" data-clear-id="5"><i class="fa fa-times"></i></button>
                    </div>
                  </div>

                  <div class="form-group row">
                    <label for="time" class="col-md-3"><?php echo $this->lang->line('xin_saturday');?></label>
                    <div class="col-md-4">
                      <input class="form-control timepicker clear-6" placeholder="<?php echo $this->lang->line('xin_in_time');?>" readonly name="saturday_in_time" type="text" value="00:00"/>
                    </div>
                    <div class="col-md-4">
                      <input class="form-control timepicker clear-6" placeholder="<?php echo $this->lang->line('xin_out_time');?>" readonly name="saturday_out_time" type="text" value="00:00"/>
                    </div>
                    <div class="col-md-1">
                      <button type="button" class="btn btn-xs btn-danger clear-time" data-clear-id="6"><i class="fa fa-times"></i></button>
                    </div>
                  </div>

                  <div class="form-group row">
                    <label for="time" class="col-md-3"><?php echo $this->lang->line('xin_sunday');?></label>
                    <div class="col-md-4">
                      <input class="form-control timepicker clear-7" placeholder="<?php echo $this->lang->line('xin_in_time');?>" readonly name="sunday_in_time" type="text" value="00:00"/>
                    </div>
                    <div class="col-md-4">
                      <input class="form-control timepicker clear-7" placeholder="<?php echo $this->lang->line('xin_out_time');?>" readonly name="sunday_out_time" type="text" value="00:00"/>
                    </div>
                    <div class="col-md-1">
                      <button type="button" class="btn btn-xs btn-danger clear-time" data-clear-id="7"><i class="fa fa-times"></i></button>
                    </div>
                  </div>

              </div>

            </div>

            <div class="form-actions box-footer ">
              <span style="float: left;"><button type="submit" class="btn btn-primary"> <i class="fa fa-check-square-o"></i> <?php echo $this->lang->line('xin_save');?> </button></span>
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
   <h3 class="box-title"> <b>JADWAL KERJA REGULER </b> </h3>
  </div>
  <div class="box-body">
    <div class="box-datatable table-responsive">
      <table class="datatables-demo table table-striped table-bordered" id="xin_table" width="100%">
        <thead>
          <tr>
            <th rowspan="2" width ="80px" style="text-align: center;"><?php echo $this->lang->line('xin_option');?></th>
            <th rowspan="2" style="text-align: center;"><?php echo $this->lang->line('xin_day');?><br>(Daftar Karyawan)</th>
            <th colspan="7" style="text-align: center;"><?php echo $this->lang->line('xin_schedule');?></th>
          </tr>
          <tr>
            <th width ="90px" style="text-align: center !important;"><center><?php echo $this->lang->line('xin_monday');?></center></th>
            <th width ="90px" style="text-align: center !important;"><center><?php echo $this->lang->line('xin_tuesday');?></center></th>
            <th width ="90px" style="text-align: center !important;"><center><?php echo $this->lang->line('xin_wednesday');?></center></th>
            <th width ="90px" style="text-align: center !important;"><center><?php echo $this->lang->line('xin_thursday');?></center></th>
            <th width ="90px" style="text-align: center !important;"><center><?php echo $this->lang->line('xin_friday');?></center></th>
            <th width ="90px" style="text-align: center !important;"><center><?php echo $this->lang->line('xin_saturday');?></center></th>
            <th width ="90px" style="text-align: center !important;"><center><?php echo $this->lang->line('xin_sunday');?></center></th>
           
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
