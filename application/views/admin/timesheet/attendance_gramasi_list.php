<?php
/* Attendance view
*/
?>
<?php $session = $this->session->userdata('username');?>
<?php $get_animate = $this->Core_model->get_content_animate();?>
<?php $user_info = $this->Core_model->read_user_info($session['user_id']);?>

<?php $attributes = array('name' => 'attendance_recap_report', 'id' => 'attendance_recap_report', 'autocomplete' => 'off', 'class' => 'add form-hrm');?>
        <?php $hidden = array('user_id' => $session['user_id']);?>
        <?php echo form_open('admin/timesheet/attendance_gramasi_list', $attributes, $hidden);?>
        <?php
      $data = array(
        'type'        => 'hidden',
        'name'        => 'date_format',
        'id'          => 'date_format',
        'value'       => $this->Core_model->set_date_format(date('Y-m-d')),
        'class'       => 'form-control',
      );
      echo form_input($data);
      ?>

<div class="row <?php echo $get_animate;?>">
  <div class="col-md-12">
      <div class="box mb-4">
          <div class="box-header with-border">
            <h3 class="box-title"> Proses Tarik Gramasi - Harian </h3>
          </div>
          <div class="box-body">
            <div class="row">
              
              <div class="col-md-3">
                <div class="form-group">
                  <label for="first_name"><?php echo $this->lang->line('left_company');?></label>
                  <select class="form-control input-sm" name="company_id" id="company_id" data-plugin="select_hrm" data-placeholder="<?php echo $this->lang->line('left_company');?>">
                    <?php foreach($get_all_companies as $company) {?>
                    <option value="<?php echo $company->company_id?>"><?php echo $company->name?></option>
                    <?php } ?>
                  </select>
                </div>
              </div>

              <div class="col-md-2">
                  <div class="form-group">
                      <label for="name">Jenis Karyawan</label>
                      <select name="location_id" id="location_id" class="form-control input-sm" data-plugin="select_hrm" data-placeholder="<?php echo $this->lang->line('left_location');?>">
                          <?php foreach($all_office_shifts as $payroll_jenis) {?>
                          <option value="<?php echo $payroll_jenis->jenis_gaji_id?>"><?php echo strtoupper($payroll_jenis->jenis_gaji_name);?></option>
                          <?php } ?>
                      </select>
                  </div>
              </div>            

              <div class="col-md-2">
                  <div class="form-group">
                    <label for="first_name">Tanggal Kehadiran</label>
                    <input class="form-control attendance_date" placeholder="<?php echo $this->lang->line('xin_select_date');?>" readonly id="attendance_date" name="attendance_date" type="text" value="<?php echo date('Y-m-d');?>">
                  </div>
              </div>
                
              <div class="col-md-4">
                <div class="form-group"> &nbsp;
                  <label for="first_name">&nbsp;</label><br />
                   <button type="submit" class="btn btn-warning save"><i class="fa fa-cloud-upload"></i> Proses Tarik Gramasi </button>
                </div>
              </div>

            </div>
          </div>
    
       </div>
  </div>
</div>

 <?php echo form_close(); ?>

<div class="box <?php echo $get_animate;?>">
  
  <div class="box-body">
    <div class="row">
        <div class="col-md-12"> 
          
          <div class="box" style="margin-bottom: 0px;">   
            <div class="box-header with-border">
              <h3 class="box-title text-uppercase text-bold"> Tarik Gramasi Harian   </h3>
              <h5>
                Silahkan Klik Tombol "<span class="blink blink-two merah">Proses Tarik Gramasi</span>" terlebih dahulu guna menampilkan hasil tarikan gramasi dari Impor Gramasi.
              </h5>
              <div class="box-tools pull-right"> </div>
            </div>   
             <div class="box-body" id ="myDIV" style="display:none;">
              <div class="box-datatable table-responsive">
                <table class="datatables-demo table table-striped table-bordered" id="xin_table_recap" style="width:160%;">
                  <thead>                           
                      <tr>
                        <th style="width:50px; vertical-align: middle !important;"><center>No.</center></th>          
                        <th style="width:400px;vertical-align: middle !important;"><center>Nama Karyawan</center></th>
                        <th style="width:450px;vertical-align: middle !important;"><center>Posisi</center></th>
                        <th style="width:320px;vertical-align: middle !important;"><center>Perusahaan</center></th>       
                        <th style="width:180px;vertical-align: middle !important;"><center>Jam Kerja</center></th> 
                        <th style="width:120px;vertical-align: middle !important;"><center>Tanggal</center></th> 
                        <th style="width:120px;vertical-align: middle !important;"><center>Status</center></th> 
                        <th style="width:120px;vertical-align: middle !important;"><center><?php echo $this->lang->line('dashboard_clock_in');?></center></th> 
                        <th style="width:120px;vertical-align: middle !important;"><center><?php echo $this->lang->line('dashboard_clock_out');?></center></th> 
                        <th style="width:120px;vertical-align: middle !important;"><center><?php echo $this->lang->line('dashboard_late');?><br>(Menit)</center></th> 
                        <th style="width:120px;vertical-align: middle !important;"><center><?php echo $this->lang->line('dashboard_early_leaving');?><br>(Menit)</center></th>  
                        <th style="width:120px;vertical-align: middle !important;"><center><?php echo $this->lang->line('dashboard_overtime');?><br>(Menit)</center></th>            
                        <th style="width:120px;vertical-align: middle !important;"><center><?php echo $this->lang->line('dashboard_total_work');?><br>(Menit)</center></th>
                        <th style="width:500px;vertical-align: middle !important;"><center>Keterangan</center></th>                          
                      </tr>
                    </thead>                  
                  </table>
              </div>
            </div>
          </div>


          
        </div>
        <!-- /.col --> 
    </div>

    
  </div>
</div>

<script type="text/javascript"> 


  function tampilkan_tabel() {
    var x = document.getElementById("myDIV");
    if (x.style.display === "none") {
      x.style.display = "block";
    } 
  } 

  function tampilkan_tombol() {
    var x = document.getElementById("myBtn");
    if (x.style.display === "none") {
      x.style.display = "block";
    } 
  } 

  </script>