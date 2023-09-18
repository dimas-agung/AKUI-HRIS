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
    
    <div class="col-xl-6 col-md-4 col-12 hr-mini-state">
     
        <div class="info-box hrsalle-mini-stat"> <span class="info-box-icon bg-green"><i class="fa fa-file-text-o"></i></span>
            <div class="info-box-content"> 
              <span class="info-box-number">
               Status Karyawan Aktif     
              </span> 
              <span class="info-box-text">                  
                  <span class="badge badge-primary"> 
                     Total <?php echo active_employees();?>
                  </span>                  
                  <span class="badge badge-info"> 
                      Tetap  <?php echo jum_status_tetap();?>
                  </span>                  
                  <span class="ml-2"> 
                    <span class="badge bg-red"> 
                      Kontrak  <?php echo jum_status_kontrak();?>
                    </span>
                  </span>
              </span> 
            </div>
        </div>     
    </div>

    <div class="col-xl-6 col-md-4 col-12 hr-mini-state">
      
        <div class="info-box hrsalle-mini-stat"> <span class="info-box-icon bg-green"><i class="fa fa-file-text-o"></i></span>
            <div class="info-box-content"> 
              <span class="info-box-number">
               Status Aktifasi Kontrak            
              </span>              
              <span class="info-box-text">                                    
                  <span class="badge badge-info"> 
                     Sudah Aktivasi <?php echo jum_status_kontrak_sudah();?>
                  </span>                  
                  <span class="ml-2"> 
                    <span class="badge bg-red"> 
                      <?php $jumlah = jum_status_kontrak()-(jum_status_kontrak_sudah()+jum_status_kontrak_belum_ada())  ?>
                      Belum Aktivasi <?php echo $jumlah; ?> 
                    </span>
                  </span>
                  <span class="ml-2"> 
                    <span class="badge bg-red"> 
                      Belum Dibuat <?php echo jum_status_kontrak_belum_ada();?> 
                    </span>
                  </span> 
              </span> 
            </div>
        </div>          
    </div>

    <div class="col-xl-6 col-md-4 col-12 hr-mini-state">
      
        <div class="info-box hrsalle-mini-stat"> <span class="info-box-icon bg-green"><i class="fa fa-file-text-o"></i></span>
            <div class="info-box-content"> 
              <span class="info-box-number">
               Status Masa Kontrak            
              </span> 
              <span class="info-box-text">
                  <span class="badge badge-primary"> 
                     Berlangsung  <?php echo jum_status_kontrak_berlangsung();?>
                  </span>
                  
                  <span class="badge badge-info"> 
                     Akan Habis <?php echo jum_status_kontrak_akan_habis();?>
                  </span>
                  
                  <span class="ml-2"> 
                    <span class="badge bg-red"> 
                      Sudah Habis <?php echo jum_status_kontrak_sudah_habis();?> 
                    </span>
                  </span> 
              </span> 
            </div>
        </div>
          
    </div>

</div>

<div class="box <?php echo $get_animate;?>">
  <div class="box-header with-border">
    <h3 class="box-title"> <?php echo $this->lang->line('xin_list_all');?> Karyawan </h3>        
    <div class="box-tools pull-right"> 
    </div>       
  </div>
  <div class="box-body">
    <div class="box-datatable table-responsive">
      <table class="datatables-demo table table-striped table-bordered" id="xin_table" width="100%">
        <thead>
               <tr>
                  <th width="70px"><center><?php echo $this->lang->line('xin_action');?> </center></th>
                  <th width="30px"><center> Foto </center></th>
                  <th width="100px"><center> Tanggal<br>Rekrutmen </center></th>
                  <th width="100px"><center> Tanggal<br>Resign </center></th>
                  <th width="180px"><center> Nama<br>Karyawan </center></th>
                  <th width="200px"><center> Lokasi Kerja<br>Karyawan </center></th>
                  <th width="170px"><center> Posisi<br>Karyawan </center></th>
                  <th width="170px"><center> Info Kontrak<br>Terakhir </center></th> 
                  <th width="70px"><center> Status<br>Kontrak </center></th>
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

