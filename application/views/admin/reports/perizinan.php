<?php

/* ===========================================================================
   LAPORAN : KARYAWAN AKTIF
   ===========================================================================
*/

?>
<?php $session     = $this->session->userdata('username');?>
<?php $get_animate = $this->Core_model->get_content_animate();?>
<?php $user_info   = $this->Core_model->read_user_info($session['user_id']);?>

<div class="row m-b-1 <?php echo $get_animate;?>">  
  <div class="col-md-12">
    <div class="box">
      <div class="box-header with-border">
        <h3 class="box-title"> <?php echo $this->lang->line('xin_hr_report_filters');?> </h3>
      </div>
      <div class="box-body">
        <div class="row">
          <div class="col-md-12">
            <?php $attributes = array('name'     => 'perizinan', 'id' => 'perizinan', 'autocomplete' => 'off', 'class' => 'add form-hrm');?>
            <?php $hidden     = array('euser_id' => $session['user_id']);?>
            <?php echo form_open('admin/reports/perizinan', $attributes, $hidden);?>
            <?php
                $data = array(
                  'name'        => 'user_id',
                  'id'          => 'user_id',
                  'type'        => 'hidden',
                  'value'   	  => $session['user_id'],
                  'class'       => 'form-control input-sm',
                );
            
            echo form_input($data);
            ?>
           
            <div class="row">

              <div class="col-md-3">
                <div class="form-group">
                    <label for="first_name"> Jenis Perizinan </label>
                    <select class=" form-control input-sm" name="perizinan_type_id" id="aj_company" data-plugin="select_hrm" data-placeholder="Piih Jenis Perizinan">
                     <option value="0"><?php echo $this->lang->line('xin_acc_all');?></option>
                      <?php foreach($get_all_perizinan_type as $perizinan_type) {?>
                          <option value="<?php echo $perizinan_type->perizinan_type_id?>"><?php echo strtoupper($perizinan_type->perizinan_type_name);?></option>
                      <?php } ?>
                    </select>
                  </div>
              </div>                        
              <div class="col-md-2">
                <div class="form-group">
                  <label for="first_name"> Tanggal Dimulai </label>
                  <input class="form-control attendance_date" placeholder="<?php echo $this->lang->line('xin_select_date');?>" readonly id="start_date" name="start_date" type="text" >
                </div>
              </div>
              <div class="col-md-2">
                <div class="form-group">
                  <label for="first_name"> Tanggal Berakhir </label>
                  <input class="form-control attendance_date" placeholder="<?php echo $this->lang->line('xin_select_date');?>" readonly id="end_date" name="end_date" type="text" >
                </div>
              </div>
              <div class="col-md-3">
                <div class="form-group">
                  <label for="submit">&nbsp;</label><br />
                  <button type="submit" class="btn btn-primary"> <i class="fa fa-check-square-o"></i> <?php echo $this->lang->line('xin_get');?> </button>
                </div>
              </div>
              
            </div>
            <?php echo form_close(); ?> </div>
        </div>
      </div>
    </div>
  </div>

  <div class="col-md-12 <?php echo $get_animate;?>">
    <div class="box">
      <div class="box-header with-border">
        <h3 class="box-title"> Daftar Perizinan </h3>
      </div>
      <div class="box-body">
        <div class="box-datatable table-responsive">
          <table class="datatables-demo table table-striped table-bordered " id="xin_perizinan" width="100%">
            <thead>
              <tr>
                <th width="10px" text-align="center"><center>No.</center></th>

                <th width="90px"><center> Tanggal<br>Dimulai</center></th>               
                <th width="90px"><center> Tanggal<br>Berakhir</center></th>
                <th width="100px"><center> Jenis </center></th>
                <th width="220px"><center> No Perizinan </center></th> 
                <th width="220px"><center> Nama Perizinan </center></th>                
                <th width="320px"><center> Nama Instansi </center></th>               
                <th width="100px"><center> Status </center></th>               
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
