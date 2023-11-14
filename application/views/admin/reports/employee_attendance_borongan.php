<?php
/* Date Wise Attendance Report > EMployees view
*/
?>
<?php $session = $this->session->userdata('username');?>
<?php $get_animate = $this->Core_model->get_content_animate();?>
<?php $user_info = $this->Core_model->read_user_info($session['user_id']);?>


<div class="row">      
  <div class="col-md-12">
    
    <div class="box mb-4">
      <div class="box-header with-border">
        <h3 class="box-title"> Tampilkan Kehadiran Per Karyawan Borongan (Reguler) </h3>
      </div>
      <div class="box-body">
        <div class="row">
          <div class="col-md-12">
            <?php $attributes = array('name' => 'attendance_borongan_report', 'id' => 'attendance_borongan_report', 'autocomplete' => 'off', 'class' => 'add form-hrm');?>
            <?php $hidden = array('euser_id' => $session['user_id']);?>
            <?php echo form_open('admin/reports/attendance_xin', $attributes, $hidden);?>
            <?php
        				$data = array(
        				  'name'        => 'user_id',
        				  'id'          => 'user_id',
        				  'value'       => $session['user_id'],
        				  'type'   		=> 'hidden',
        				  'class'       => 'form-control',
        				);
                
                echo form_input($data);
                ?>
            <div class="row">
              <div class="col-md-2">
                <div class="form-group">
                  <input class="form-control attendance_date" placeholder="<?php echo $this->lang->line('xin_select_date');?>" readonly id="start_date" name="start_date" type="text" value="<?php echo date('Y-m-d');?>">
                </div>
              </div>
              <div class="col-md-2">
                <div class="form-group">
                  <input class="form-control attendance_date" placeholder="<?php echo $this->lang->line('xin_select_date');?>" readonly id="end_date" name="end_date" type="text" value="<?php echo date('Y-m-d');?>">
                </div>
              </div>
           
              <div class="col-md-3">
                <div class="form-group">
                  <select class="form-control" name="employee_id" id="employee_id" data-plugin="select_hrm" data-placeholder="Pilih Karyawan Borongan" required>
                    <option value=""></option>
                    
                    <?php foreach($all_karyawan_borongan as $karyawan_borongan) {?>
                       <option value="<?php echo $karyawan_borongan->user_id;?>"><?php echo $karyawan_borongan->first_name.' '.$karyawan_borongan->last_name;?></option>
                    <?php } ?>

                   
                  </select>
                </div>
              </div>

              <div class="col-md-2">
                <button type="submit" class="btn btn-primary"> <i class="fa fa-filter"></i> 
                  Tampilkan
                </button>
              </div>

            </div>
           
            <?php echo form_close(); ?> </div>
        </div>
      </div>
    </div>

  </div>
</div>

 
<div class="row">
  <div class="col-md-12 ">
    <div class="box">
     
      <div class="box-header with-border">
        <h3 class="box-title"><span class="info_karyawan_borongan"></span> </h3> 
      </div>
      
      <div class="box-body" id ="myDIV" style="display:none;">
        <div class="box-datatable table-responsive">
              <table class="datatables-demo table table-striped table-bordered" id="xin_table_borongan" width="100%">
            <thead>              
              <tr>
                <th style="vertical-align: middle !important; width:20px;text-align: center;">No</th>               
                <th style="vertical-align: middle !important; width:90px;"><?php echo $this->lang->line('xin_e_details_date');?></th>
                <th style="vertical-align: middle !important; width:50px;"><?php echo $this->lang->line('dashboard_employee_hari');?></th>
                <th style="vertical-align: middle !important; width:140px;"><?php echo $this->lang->line('dashboard_employee_jam');?></th>
                <th style="vertical-align: middle !important; width:140px;"><?php echo $this->lang->line('dashboard_clock_in');?></th>
                <th style="vertical-align: middle !important; width:140px;"><?php echo $this->lang->line('dashboard_clock_out');?></th>
                <th style="vertical-align: middle !important; width:90px;"><?php echo $this->lang->line('dashboard_xin_status');?></th>

                <th style="vertical-align: middle !important; width:120px;"><?php echo $this->lang->line('dashboard_late');?><br>(Menit)</th>
                <th style="vertical-align: middle !important; width:120px;"><?php echo $this->lang->line('dashboard_early_leaving');?><br>(Menit)</th>
                <th style="vertical-align: middle !important; width:120px;"><?php echo $this->lang->line('dashboard_overtime');?><br>(Menit)</th>
                
                <th style="vertical-align: middle !important; width:140px;"><?php echo $this->lang->line('dashboard_total_work');?><br>(Menit)</th>
                                
                <th style="vertical-align: middle !important; width:450px;"><?php echo $this->lang->line('left_desc');?></th>   
             
              </tr>
            </thead>
          </table>
        </div>
      </div>
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
