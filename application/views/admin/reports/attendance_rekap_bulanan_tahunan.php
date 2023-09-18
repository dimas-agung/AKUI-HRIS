<?php
/* Monthly Timesheet view > hris
*/
?>
<?php $session = $this->session->userdata('username');?>
<?php $get_animate = $this->Core_model->get_content_animate();?>

<?php
$user_info          = $this->Core_model->read_user_info($session['user_id']);
$role_resources_ids = $this->Core_model->user_role_resource();

$jenis_company    = $this->input->post('company_id');

$month_year         = $this->input->post('month_year');



  if(!isset($month_year)){

    $skrg     = date('Y');

    $xin_tahun     = $this->Timesheet_model->get_xin_employees_tahun($skrg);
    $month_year    = $xin_tahun[0]->tahun;
    $tahun         = $xin_tahun[0]->tahun;

    $jenis_company = 1;
      
    
    $xin_bulan     = $this->Timesheet_model->get_xin_calendar_tahun($tahun);

    $xin_status    = $this->Timesheet_model->get_xin_status_tahun();

    $bulan_daftar       = $this->Timesheet_model->read_bulan_information($tahun);
    if(!is_null($bulan_daftar)){
      $bulan_nama = $bulan_daftar[0]->status;
     
    } else {
      $bulan_nama = '';  
     
    }   

    if ($jenis_company == '1') {
      $company_name     = 'PT Akui Bird Nest Indonesia';
    } else if ($jenis_company == '2') {
      $company_name     = 'PT ORIGINAL BERKAH INDONESIA';
    } else if ($jenis_company == '3') {
      $company_name     = 'PT WALET ABDILLAH JABLI';
    }
    
    $xin_employees = $this->Timesheet_model->get_xin_employees_tahunan_rekap($jenis_company,$month_year);
    $gaji_name     = 'Karyawan Bulanan';
    
  } else {
    
    $xin_bulan   = $this->Timesheet_model->get_xin_calendar_tahun($month_year);

    $xin_status    = $this->Timesheet_model->get_xin_status_tahun();

    $bulan_daftar = $this->Timesheet_model->read_bulan_information($month_year);
    if(!is_null($bulan_daftar)){
      $bulan_nama = $bulan_daftar[0]->status;
    } else {
      $bulan_nama = ''; 
    }   

    if ($jenis_company == '1') {
      $company_name     = 'PT Akui Bird Nest Indonesia';
    } else if ($jenis_company == '2') {
      $company_name     = 'PT ORIGINAL BERKAH INDONESIA';
    } else if ($jenis_company == '3') {
      $company_name     = 'PT WALET ABDILLAH JABLI';
    }   
    
    $xin_employees = $this->Timesheet_model->get_xin_employees_bulanan_rekap($jenis_company,$month_year);
    $gaji_name     = 'Karyawan Bulanan';
  
  }
?>

<?php $attributes = array('name' => 'xin-form', 'id' => 'xin-form', 'autocomplete' => 'off');?>
<?php $hidden     = array('_user' => $session['user_id']);?>
<?php echo form_open('admin/reports/attendance_rekap_tahunan/', $attributes, $hidden);?>

<div class="row <?php echo $get_animate;?>">    
    <div class="col-md-12">
      <div class="box mb-4">
        <div class="box-header with-border">
          <h3 class="box-title"> Tampilkan Kehadiran Rekap Tahunan </h3>
        </div>
        <div class="box-body">
          <div class="row">
              <div class="col-md-12">                  
                 
                <div class="row">
                  
                   <div class="col-md-3">
                        <div class="form-group">
                          <label for="first_name"><?php echo $this->lang->line('left_company');?></label>
                          <select class="form-control input-sm" name="company_id" id="company_id" data-plugin="select_hrm" data-placeholder="<?php echo $this->lang->line('left_company');?>">
                            <?php foreach($get_all_companies as $company) {?>                     
                            <option value="<?php echo $company->company_id?>" <?php if($company->company_id==$jenis_company): ?> selected="selected" <?php endif; ?>><?php echo strtoupper($company->name);?></option>
                            <?php } ?>
                          </select>
                        </div>
                    </div> 
                       
                      <div class="col-md-2">
                        <div class="form-group">
                          <label for="first_name">Tahun</label>
                          <select class="form-control input-sm" name="month_year" id="month_year" data-plugin="select_hrm" data-placeholder="<?php echo $this->lang->line('xin_e_details_month');?>" required>                    
                            <?php foreach($all_tahun_gaji as $tahun_gaji) {?>                       
                              <option value="<?php echo $tahun_gaji->tahun;?>" <?php if($tahun_gaji->tahun==$month_year): ?> selected="selected" <?php endif; ?>>
                                <?php echo strtoupper($tahun_gaji->tahun); ?>                                  
                              </option>
                            <?php } ?>
                          </select>
                        </div>
                      </div>

                      <div class="col-md-3">
                        <div class="form-group">
                          <label >&nbsp;</label>
                          <br />
                          <?php echo form_button(array('name' => 'hris_form', 'type' => 'submit', 'class' => 'btn btn-primary', 'content' => '<i class="fa fa fa-check-square-o"></i> '.$this->lang->line('xin_get_filter'))); ?> 
                        </div>
                      </div>

                </div>
                  
              </div>
          </div>
        </div>
      </div>
    </div>
</div>

<?php echo form_close(); ?>


<div class="row">
    <div class="col-md-12">
      <div class="box">
        <div class="box-header with-border">
            <h3 class="box-title text-uppercase text-bold"> Kehadiran Tahunan : <?php if(isset($month_year)): echo date('Y', strtotime($month_year)); else: echo date('Y',strtotime($tahun)); endif;?> </h3>
            <h5>
             Jenis : <?php echo $gaji_name ; ?> - 
             Pola Kerja : Reguler  Di <?php echo ucfirst($company_name); ?>
            </h5>
            <div class="box-tools pull-right"> </div>
        </div>
  
        <div class="box-body">
        <div class="box-datatable table-responsive">
          <table class="datatables-demo table table-striped table-bordered" id="xin_table_tahunan" width="160%">
            <thead>
                <tr>
                  <th rowspan="3" style="vertical-align: middle !important;"><?php echo $this->lang->line('xin_nomor');?></th>
                  <th rowspan="3" style="vertical-align: middle !important;"><?php echo $this->lang->line('xin_employee');?></th>             
                  <th colspan="94"> <?php if(isset($month_year)): echo date('Y', strtotime($month_year)); else: echo date('Y',strtotime($tahun)); endif;?></th>
                </tr>
                <tr>                  
                    <?php foreach($xin_bulan as $t):?>
                      
                      <?php $bln    = $t->status; ?>                  
                      <?php $warna  = $this->Timesheet_model->conWarnaBulanNama($bln); ?>
                      
                      <th colspan="7" style="<?php echo $warna; ?>">
                        <center><?php echo $bln;?> </center>
                      </th>

                  <?php endforeach;?>
                </tr>
                <tr>
                  <?php foreach($xin_bulan as $t):?>
                     <?php $bln    = $t->status; ?>                  
                      <?php $warna  = $this->Timesheet_model->conWarnaBulanNama($bln); ?>

                   <?php foreach($xin_status as $s):?>
                      
                      <?php $status    = $s->nama; ?>                  
                                           
                      <th width="10px" style="<?php echo $warna; ?>">
                        <center><?php echo $status;?> </center>
                      </th>

                  <?php endforeach;?>
                   <?php endforeach;?>

                </tr>
            </thead>
            <tbody>
                <?php $no=1; ?>

                  <?php $j=0;foreach($xin_employees as $r):?>
             
                    <?php

                     $karyawan = $this->Core_model->read_user_info($r->employee_id);
                      // user full name
                      if(!is_null($karyawan)){
                        $full_name = $karyawan[0]->first_name.' '.$karyawan[0]->last_name;
                      } else {
                        $full_name = '?';  
                      }

                      $jumlah_libur            = $r->libur;
                      $jumlah_libur_kantor     = $r->libur_kantor;
                      $jumlah_hadir            = $r->aktif;
                      $jumlah_sakit            = $r->sakit;

                      $jumlah_izin             = $r->izin;
                      $jumlah_cuti             = $r->cuti;
                      $jumlah_alpa             = $r->alpa;
                      $jumlah_dinas            = $r->dinas;

                      $jumlah_terlambat_menit  = $r->terlambat_menit;
                      $jumlah_terlambat_jam    = $r->terlambat_jam;

                      $total                   = $r->total;
                      
                                          
                      $bulan_info = date("Y-m-d",strtotime($bulan_nama));
                                         
                    
                    ?>

                    <?php 

                    $tanggal_masuk = $r->date_of_joining; 

                   
                    ?>

              
                  <tr >

                      <td width="20px" class="text-center"><?php echo $no;?></td>
                      <td width="400px">
                        <?php echo $full_name;?><br>
                        <i class="fa fa-check-square-o"></i> Mulai Bekerja : <?php echo date("d-m-Y",strtotime($tanggal_masuk)) ; ?>
                      </td>

                      <?php foreach($xin_bulan as $t):?>  
                         <?php $bln    = $t->status; ?>    

                        <?php $warna  = $this->Timesheet_model->conWarnaBulan($bln); ?>
                       
                       <?php foreach($xin_status as $s):?>
                      
                          <?php $status    = $s->nama; ?>                  
                         
                          
                          <td width="20px" style="<?php echo $warna; ?>">
                            <center><?php echo $status;?> </center>
                          </td>

                      <?php endforeach;?>

                      <?php endforeach;?>

                   

                  </tr>
               <?php $no++;  ?>
              <?php endforeach;?>
            </tbody>
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
