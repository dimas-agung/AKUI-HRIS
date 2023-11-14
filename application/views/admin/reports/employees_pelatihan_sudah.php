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
            <?php $attributes = array('name'     => 'employees_pelatihan_sudah', 'id' => 'employees_pelatihan_sudah', 'autocomplete' => 'off', 'class' => 'add form-hrm');?>
            <?php $hidden     = array('euser_id' => $session['user_id']);?>
            <?php echo form_open('admin/reports/employees_pelatihan_sudah', $attributes, $hidden);?>
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
                 <div class="row">
                  <div class="col-md-12">
                    <div class="form-group">
                      <label for="training_type"> Jenis Pelatihan </label>
                      <select class="form-control" name="training_type" id="training_type" data-plugin="select_hrm" data-placeholder="Jenis Pelatihan ">
                        <option value=""></option>
                        <?php foreach($all_training_types as $training_type) {?>
                        <option value="<?php echo $training_type->training_type_id?>"><?php echo $training_type->type?></option>
                        <?php } ?>
                      </select>
                    </div>
                  </div>                  
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
        <h3 class="box-title"><i class="fa fa-info-circle"></i> Daftar Karyawan Sudah Pelatihan </div>
      <div class="box-body">
        <div class="box-datatable table-responsive">
          <table class="datatables-demo table table-striped table-bordered " id="xin_emp_active" width="100%">
            <thead>
              <tr>
                <th width="10px" text-align="center"><center>No.</center></th>
                <th width="100px"><center> Tanggal Mulai </center></th>
                <th width="100px"><center> Tanggal Sampai </center></th>
                <th width="120px"> NIP </center></th>                
                <th width="250px"><center> Nama Lengkap </center></th> 
                <th width="150px"><center> Posisi </center></th>
                <th width="120px"><center> Ketegori </center></th>
                <th width="290px"><center> Jenis Pelatihan </center></th>
                <th width="290px"><center> Nama Pelatih </center></th>

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
