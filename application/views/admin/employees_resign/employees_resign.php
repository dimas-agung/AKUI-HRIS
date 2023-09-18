<?php
/* Employees view
*/
?>

<?php $session = $this->session->userdata('username');?>
<?php $get_animate = $this->Core_model->get_content_animate();?>
<?php $role_resources_ids = $this->Core_model->user_role_resource(); ?>
<?php $user_info = $this->Core_model->read_user_info($session['user_id']);?>
<?php $system = $this->Core_model->read_setting_info(1);?>

<?php

// reports to 
$reports_to = get_reports_team_data($session['user_id']); ?>

<div class="row <?php echo $get_animate;?>">
            <div class="col-sm-6 col-lg-6">
                <div class="card p-3">
                    <div class="d-flex align-items-center">
                        <span class="stamp-hris-4 stamp-hris-md bg-hris-success-4 mr-3">
                            <i class="fa fa-user"></i>
                        </span>
                        <div>
                            <h5 class="mb-1"><b><?php echo inactive_employees();?> <small>Resign </small></b></h5>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-sm-6 col-lg-3">
                <div class="card p-3">
                    <div class="d-flex align-items-center">
                        <span class="stamp-hris-4 stamp-hris-md bg-hris-secondary mr-3">
                            <i class="fa fa-male"></i>
                        </span>
                        <div>
                            <h5 class="mb-1"><b><?php echo $this->Core_model->male_employees_resign();?>% <small><?php echo $this->lang->line('xin_gender_male');?></small></b></h5>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-lg-3">
                <div class="card p-3">
                    <div class="d-flex align-items-center">
                        <span class="stamp-hris-4 stamp-hris-md bg-hris-warning-4 mr-3">
                            <i class="fa fa-female"></i>
                        </span>
                        <div>
                            <h5 class="mb-1"><b><?php echo $this->Core_model->female_employees_resign();?>% <small><?php echo $this->lang->line('xin_gender_female');?></small></b></h5>
                        </div>
                    </div>
                </div>
            </div>
    </div>

    <div class="box <?php echo $get_animate;?>">
      <div class="box-header with-border">
        <h3 class="box-title"> Daftar Karyawan Resign </h3>        
       
            <div class="box-tools pull-right"> 
              
            </div>
        
      </div>
      <div class="box-body">
        <div class="box-datatable table-responsive">
          <table class="datatables-demo table table-striped table-bordered" id="xin_table" width="100%">
            <thead>
                   <tr>
                       <th width="30px"><center> No </center></th>
                          <th width="30px"><center> Foto </center></th>
                          <th width="100px"><center> Tanggal<br>Rekrutmen </center></th>
                          <th width="100px"><center> Tanggal<br>Resign </center></th>
                          <th width="180px"><center> Nama<br>Karyawan </center></th>
                          <th width="180px"><center> Lokasi<br>Kerja </center></th>
                          <th width="170px"><center> Posisi<br>Karyawan </center></th>
                          <th width="80px"><center> Status<br>Karyawan </center></th>
                          <th width="80px"><center> Kontrak<br>Karyawan </center></th>               
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

