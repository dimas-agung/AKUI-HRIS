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

    $skrg     = date('Y-m-d');

    $xin_bulan     = $this->Timesheet_model->get_xin_employees_bulan($skrg);
    $month_year    = $xin_bulan[0]->month_payroll;
    $bulan         = $xin_bulan[0]->month_payroll;

    $jenis_company = 1;
      
    
    $xin_tanggal   = $this->Timesheet_model->get_xin_employees_tanggal($bulan);
    $tanggal       = $this->Timesheet_model->read_tanggal_information($bulan);
    if(!is_null($tanggal)){
      $start_att = date("d-m-Y", strtotime($tanggal[0]->start_date));
      $end_att   = date("d-m-Y", strtotime($tanggal[0]->end_date));

      $start_date    = new DateTime($tanggal[0]->start_date);
      $end_date      = new DateTime($tanggal[0]->end_date);
      $interval_date = $end_date->diff($start_date);

    } else {
      $start_att = '';  
      $end_att = '';  

      $start_date    = '';
      $end_date      = '';
      $interval_date = '';
    }   

    if ($jenis_company == '1') {
      $company_name     = 'PT Akui Bird Nest Indonesia';
    } else if ($jenis_company == '2') {
      $company_name     = 'PT ORIGINAL BERKAH INDONESIA';
    } else if ($jenis_company == '3') {
      $company_name     = 'PT WALET ABDILLAH JABLI';
    }
    
    $xin_employees = $this->Timesheet_model->get_xin_employees_bulanan_rekap_lembur($jenis_company,$month_year);
    $gaji_name     = 'Karyawan Bulanan';
    
  } else {
    
    $xin_tanggal   = $this->Timesheet_model->get_xin_employees_tanggal($month_year);

    $tanggal = $this->Timesheet_model->read_tanggal_information($month_year);
    if(!is_null($tanggal)){
      $start_att = date("d-m-Y", strtotime($tanggal[0]->start_date));
      $end_att   = date("d-m-Y", strtotime($tanggal[0]->end_date));

      $start_date    = new DateTime($tanggal[0]->start_date);
      $end_date      = new DateTime($tanggal[0]->end_date);
      $interval_date = $end_date->diff($start_date);


    } else {
      $start_att = '';  
      $end_att = '';  

      $start_date    = '';
      $end_date      = '';
      $interval_date = '';
    }   

    if ($jenis_company == '1') {
      $company_name     = 'PT Akui Bird Nest Indonesia';
    } else if ($jenis_company == '2') {
      $company_name     = 'PT ORIGINAL BERKAH INDONESIA';
    } else if ($jenis_company == '3') {
      $company_name     = 'PT WALET ABDILLAH JABLI';
    }   
    
    $xin_employees = $this->Timesheet_model->get_xin_employees_bulanan_rekap_lembur($jenis_company,$month_year);
    $gaji_name     = 'Karyawan Bulanan';
  
  }
?>

<div class="box mb-4 <?php echo $get_animate;?>">
  <div class="box-body">
    <div class="row">
      <div class="col-md-12">
        
        <?php $attributes = array('name' => 'xin-form', 'id' => 'xin-form', 'autocomplete' => 'off');?>
        <?php $hidden     = array('_user' => $session['user_id']);?>
        <?php echo form_open('admin/reports/rekap_lembur/', $attributes, $hidden);?>
        
        <div class="box">
          <div class="box-body">
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
                    <label for="first_name"><?php echo $this->lang->line('xin_e_details_month');?></label>
                    <select class="form-control input-sm" name="month_year" id="month_year" data-plugin="select_hrm" data-placeholder="<?php echo $this->lang->line('xin_e_details_month');?>" required>                    
                      <?php foreach($all_bulan_gaji as $bulan_gaji) {?>                       
                        <option value="<?php echo $bulan_gaji->month_payroll;?>" <?php if($bulan_gaji->month_payroll==$month_year): ?> selected="selected" <?php endif; ?>><?php echo strtoupper($bulan_gaji->desc); ?></option>
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
        <?php echo form_close(); ?> </div>
    </div>
  </div>
</div>
<div class="box <?php echo $get_animate;?>">
  <div class="box-body">
      <div class="row">
          <div class="col-md-12">
            <div class="box">
        <div class="box-header with-border">
            <h3 class="box-title text-uppercase text-bold"> Rekap Lembur Bulan : <?php if(isset($month_year)): echo date('F Y', strtotime($month_year)); else: echo date('F Y',strtotime($bulan)); endif;?> </h3>
            <h5>
             Periode Kehadiran : <?php echo $start_att; ?> s/d <?php echo $end_att; ?> (<?php echo $interval_date->d; ?> hari) - Jenis : <?php echo $gaji_name ; ?> - 
             Pola Kerja : Reguler  Di <?php echo ucfirst($company_name); ?>
            </h5>
            <div class="box-tools pull-right"> </div>
        </div>
        
          <div class="box-body">
            <div class="box-datatable table-responsive">
              <table class="datatables-demo table table-striped table-bordered" id="xin_table_bulanan" width="160%">
                <thead>
                    <tr>
                      <th rowspan="2" style="vertical-align: middle !important;"><?php echo $this->lang->line('xin_nomor');?></th>
                      <th rowspan="2" style="vertical-align: middle !important;"><?php echo $this->lang->line('xin_employee');?></th>             
                      <th colspan="<?php echo $interval_date->d+1; ?>"> <?php if(isset($month_year)): echo date('F Y', strtotime($month_year)); else: echo date('F Y',strtotime($bulan)); endif;?></th>
                      
                      <th rowspan="2" width="90px" style="vertical-align: middle !important;"><center>Total<br>Jam<br>Lembur</center></th>
                      <th colspan="2" style="vertical-align: middle !important;"><center>Jam Lembur</center></th>
                      <th colspan="2" style="vertical-align: middle !important;"><center>Biaya Lembur</center></th>
                      <th rowspan="2" width="90px" style="vertical-align: middle !important;"><center>Total<br>Biaya<br>Lembur</center></th>
                  </tr>
                  <tr>
                      
                      <?php foreach($xin_tanggal as $t):?>

                        <?php $tgl    = date("d",strtotime($t->tanggal)); ?>
                        <?php $bln    = date("M",strtotime($t->tanggal)); ?>
                        <?php $day    = $this->Timesheet_model->conHari(date("D",strtotime($t->tanggal))); ?>
                        <?php $warna  = $this->Timesheet_model->conWarna($day); ?>
                        
                        <th style="<?php echo $warna; ?>">
                          <center><?php echo $bln;?><br><?php echo $tgl;?><br><?php echo $day;?> </center>
                        </th>

                    <?php endforeach;?>
                    
                    <th width="90px" style="vertical-align: middle !important;"><center>Jam 1</center></th>
                    <th width="90px" style="vertical-align: middle !important;"><center>Jam<br>Selanjutnya</center></th>
                    <th width="90px" style="vertical-align: middle !important;"><center>Jam 1</center></th>
                    <th width="90px" style="vertical-align: middle !important;"><center>Jam<br>Selanjutnya</center></th>

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

                          $total_jam_lembur        = $r->total_jam_lembur;
                          $jam_1                   = $r->jam_1;
                          $jam_1_selanjutnya       = $r->jam_1_selanjutnya;

                          $biaya_jam_1             = number_format($r->biaya_jam_1, 0, ',', '.');
                          $biaya_jam_1_selanjutnya = number_format($r->biaya_jam_1_selanjutnya, 0, ',', '.');
                          $total_biaya_lembur      = number_format($r->total_biaya_lembur, 0, ',', '.');
                                                    
                                              
                          $tanggal1 = date("Y-m-d",strtotime($start_att));
                          $tanggal2 = date("Y-m-d",strtotime($end_att));                    
                        
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

                          <?php foreach($xin_tanggal as $t):?>  
                              <?php $attendance_date = date("d",strtotime($t->tanggal)); ?>
                              <?php $day   = $this->Timesheet_model->conHari(date("D",strtotime($t->tanggal))); ?>
                              <?php $warna = $this->Timesheet_model->conWarnaSub($day); ?>
                              
                              <?php 

                                  $cari_date = 'tanggal_'.$attendance_date;

                                  $cari_isi = $this->Core_model->read_tanggal_info_lembur($r->employee_id,$month_year);
                                  // user full name
                                  if(!is_null($cari_isi)){
                                    $isi = $cari_isi[0]->$cari_date;
                                    if ($isi == '?') {
                                        $info   = '';
                                        $warnae = 'background-color : #ddd;';
                                    } else {

                                        date_default_timezone_set("Asia/Jakarta");
                                         $tanggal_sekrg = date("Y-m-d");

                                        if ($t->tanggal == $tanggal_sekrg){

                                           $info   = '<span class="blink blink-one"><i class="fa fa-star kuning" title="Hari ini"></i></span>';
                                          $warnae = 'background-color : #e6e6c0;';

                                        } else {

                                          $info   = $isi;
                                           $warnae = $warna;
                                        }
                                        
                                    }
                                  } else {
                                        $info   = '';
                                        $warnae = '';  
                                  }
                              ?>
                              <td width="50px" style="<?php echo $warnae; ?>"><center><?php echo $info; ?></center></td>


                          <?php endforeach;?>

                          <td width="70px" style="text-align: center; background-color : #ddc3c3 !important;"> <?php echo $total_jam_lembur;?> </td>
                          <td width="70px" style="text-align: center; background-color : #ecd5c2 !important;"> <?php echo $jam_1;?> </td>
                          <td width="70px" style="text-align: center; background-color : #c6edd1 !important;"> <?php echo $jam_1_selanjutnya;?> </td>
                          <td width="70px" style="text-align: center; background-color : #c6dbed !important;"> <?php echo $biaya_jam_1;?> </td>
                          <td width="70px" style="text-align: center; background-color : #e7debd !important;"> <?php echo $biaya_jam_1_selanjutnya;?>  </td>
                          <td width="70px" style="text-align: center; background-color : #bde7e7 !important;"> <?php echo $total_biaya_lembur;?>  </td>
                         
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
