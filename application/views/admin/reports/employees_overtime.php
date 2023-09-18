<?php
/* Monthly Timesheet view > hris
*/
?>
<?php $session = $this->session->userdata('username');?>
<?php $get_animate = $this->Core_model->get_content_animate();?>

<?php
$user_info          = $this->Core_model->read_user_info($session['user_id']);
$role_resources_ids = $this->Core_model->user_role_resource();

$jenis_company      = $this->input->post('company_id');
$jenis_gaji         = $this->input->post('jenis_id');
$month_year         = $this->input->post('month_year');

// $jenis_gaji     = $this->input->post('company_id');
// $month_year         = $this->input->post('month_year');
// $date               = strtotime(date("Y-m-d"));

 $gaji_name     = '';



  if(!isset($month_year)){

    $skrg     = date('Y-m-d');

    $xin_bulan   = $this->Timesheet_model->get_xin_employees_bulan($skrg);
    $month_year  = $xin_bulan[0]->month_payroll;

    $bulan       = $xin_bulan[0]->month_payroll;
    
    $jenis_company = 1;
    $jenis_gaji    = 1;
    
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
      $company_name     = 'PT AKUI BIRDNEST INDONESIA';
    } else if ($jenis_company == '2') {
      $company_name     = 'PT ORIGINAL BERKAH INDONESIA';
    } else if ($jenis_company == '3') {
      $company_name     = 'PT WALET ABDILLAH JABLI';
    }

    if ($jenis_gaji == 1) {
      $xin_employees = $this->Timesheet_model->get_xin_employees_bulanan_company($jenis_company);
      $gaji_name     = 'Karyawan Bulanan';
    } else if ($jenis_gaji == 2) {
      $xin_employees = $this->Timesheet_model->get_xin_employees_harian_company($jenis_company);
      $gaji_name     = 'Karyawan Harian';
    } else if ($jenis_gaji == 3){
      $xin_employees = $this->Timesheet_model->get_xin_employees_borongan_company($jenis_company);
      $gaji_name     = 'Karyawan Borongan';
    }

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
      $company_name     = 'PT AKUI BIRDNEST INDONESIA';
    } else if ($jenis_company == '2') {
      $company_name     = 'PT ORIGINAL BERKAH INDONESIA';
    } else if ($jenis_company == '3') {
      $company_name     = 'PT WALET ABDILLAH JABLI';
    }

    if ($jenis_gaji == 1) {
      $xin_employees = $this->Timesheet_model->get_xin_employees_bulanan_company($jenis_company);
      $gaji_name     = 'Karyawan Bulanan';
    } else if ($jenis_gaji == 2) {
      $xin_employees = $this->Timesheet_model->get_xin_employees_harian_company($jenis_company);
      $gaji_name     = 'Karyawan Harian';
    } else if ($jenis_gaji == 3){
      $xin_employees = $this->Timesheet_model->get_xin_employees_borongan_company($jenis_company);
      $gaji_name     = 'Karyawan Borongan';
    }
  }
?>

<div class="row <?php echo $get_animate;?>">
    
    <div class="col-md-12">
      <div class="box mb-4">
        <div class="box-header with-border">
          <h3 class="box-title"> Pencarian  </h3>
        </div>
        <div class="box-body">
          <div class="row">
            <div class="col-md-12">

              <?php $attributes = array('name' => 'employee_reports', 'id' => 'employee_reports', 'autocomplete' => 'off', 'class' => 'add form-hrm');?>
              <?php $hidden = array('euser_id' => $session['user_id']);?>
              <?php echo form_open('admin/reports/employees_overtime', $attributes, $hidden);?>                     
              
              <div class="row">

                <div class="col-md-3">
                  <div class="form-group">
                    <label for="first_name"><?php echo $this->lang->line('left_company');?></label>
                    <select class="form-control input-sm" name="company_id" id="company_id" data-plugin="select_hrm" data-placeholder="<?php echo $this->lang->line('left_company');?>">
                      <?php foreach($all_companies as $company) {?>
                      <!-- <option value="<?php echo $company->company_id?>"><?php echo $company->name?></option> -->
                      <option value="<?php echo $company->company_id?>" <?php if($company->company_id==$jenis_company): ?> selected="selected" <?php endif; ?>><?php echo strtoupper($company->name);?></option>
                      <?php } ?>
                    </select>
                  </div>
                </div>

                <div class="col-md-2">
                    <div class="form-group">
                      <label for="first_name">Jenis Karyawan</label>
                      <select class="form-control input-sm" name="jenis_id" id="jenis_id" data-plugin="select_hrm" data-placeholder="Jenis Karyawan" required>
                      
                        <?php foreach($all_jenis_gaji as $company) {?>
                          
                          <option value="<?php echo $company->jenis_gaji_id?>" <?php if($company->jenis_gaji_id==$jenis_gaji): ?> selected="selected" <?php endif; ?>><?php echo strtoupper($company->jenis_gaji_name);?></option>
                
                        <?php } ?>

                      </select>
                    </div>
                </div>                   
                
                <div class="col-md-2">
                    <div class="form-group">
                      <label for="first_name"><?php echo $this->lang->line('xin_e_details_month');?></label>
                      <select class="form-control input-sm" name="month_year" id="month_year" data-plugin="select_hrm" data-placeholder="<?php echo $this->lang->line('xin_e_details_month');?>" required>
                      
                        <?php foreach($all_bulan_gaji as $bulan_gaji) {?>
                          
                          <option value="<?php echo $bulan_gaji->month_payroll;?>" <?php if($bulan_gaji->month_payroll==$month_year): ?> selected="selected" <?php endif; ?>><?php echo $bulan_gaji->desc?></option>
                
                        <?php } ?>
                      </select>
                    </div>
                </div>
                
                <div class="col-md-3">
                  <div class="form-group">
                    <label >&nbsp;</label>
                    <br />
                    <?php echo form_button(array('name' => 'hris_form', 'type' => 'submit', 'class' => 'btn btn-primary', 'content' => '<i class="fa fa fa-check-square-o"></i> '.$this->lang->line('xin_get'))); ?> 
                  </div>
                </div>

              </div>             

              <?php echo form_close(); ?> 

            </div>
          </div>
        </div>
      </div>
    </div>
</div>

<div class="box <?php echo $get_animate;?>">
  <div class="box-body">
      <div class="row">
          <div class="col-md-12">
            <div class="box">
              <div class="box-header with-border">
                  <h3 class="box-title text-uppercase text-bold"> 
                    Lembur Bulan : <?php if(isset($month_year)): echo date('F Y', strtotime($month_year)); else: echo date('F Y',strtotime($bulan)); endif;?>  
                  </h3>
                  <h5>
                   Periode Kehadiran : <?php echo $start_att; ?> s/d <?php echo $end_att; ?> (<?php echo $interval_date->d; ?> hari) - Jenis Karyawan : <?php echo $gaji_name; ?> - di <?php echo $company_name ?>
                  </h5>
                  <div class="box-tools pull-right"> </div>
              </div>
        
              <div class="box-body">
                <div class="box-datatable table-responsive">
                  <table class="datatables-demo table table-striped table-bordered" id="xin_table_bulanan" width="180%">
                    <thead>
                        <tr>
                          <th rowspan="2" style="vertical-align: middle !important;"><?php echo $this->lang->line('xin_nomor');?></th>
                          <th width="400px" rowspan="2" style="vertical-align: middle !important;"><?php echo $this->lang->line('xin_employee');?></th>             
                          <th colspan="<?php echo $interval_date->d+1; ?>"> <?php if(isset($month_year)): echo date('F Y', strtotime($month_year)); else: echo date('F Y',strtotime($bulan)); endif;?></th>
                          
                          <th rowspan="2" width="90px" style="vertical-align: middle !important;">Total<br>Jam<br>Lembur</th>
                          <th colspan="2" style="vertical-align: middle !important;">Jam Lembur</th>                          
                          <th colspan="2" style="vertical-align: middle !important;">Biaya Lembur</th>
                          <th rowspan="2" width="90px" style="vertical-align: middle !important;">Total<br>Biaya<br>Lembur</th>
                      </tr>
                      <tr>
                          <?php foreach($xin_tanggal as $t):?>  
                            <?php $start = date("M d",strtotime($t->tanggal)); ?>
                            <?php $day   = $this->Timesheet_model->conHari(date("D",strtotime($t->tanggal))); ?>
                            <?php $warna = $this->Timesheet_model->conWarna($day); ?>

                            <th style="<?php echo $warna; ?>"><center><?php echo $start;?><br><?php echo $day;?> </center></th>      
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
                                  // user full name 
                               $company = $this->Core_model->read_company_info($r->company_id);
                              if(!is_null($company)){
                                $comp_name = $company[0]->name;
                              } else {
                                $comp_name = '<span class="badge bg-red"> ? </span>';
                              }

                              $wages_type = $this->Core_model->read_user_jenis_gaji($r->wages_type);
                              // user full name
                              if(!is_null($wages_type)){
                                $jenis_gaji       = $wages_type[0]->jenis_gaji_keterangan;                         
                              } else {
                                $jenis_gaji = '<span class="badge bg-red"> ? </span>';                          
                              }

                              // department
                              $department = $this->Department_model->read_department_information($r->department_id);
                              if(!is_null($department)){
                                $department_name = $department[0]->department_name;
                              } else {
                                $department_name = '<span class="badge bg-red"> ? </span>'; 
                              }

                              // get designation
                              $designation = $this->Designation_model->read_designation_information($r->designation_id);
                              if(!is_null($designation)){
                                $designation_name = $designation[0]->designation_name;
                              } else {
                                $designation_name = '<span class="badge bg-red"> ? </span>';  
                              }
            
                                $full_name = $r->first_name.' '.$r->last_name;
                                
                                $tanggal1 = date("Y-m-d",strtotime($start_att));
                                $tanggal2 = date("Y-m-d",strtotime($end_att));

                                $cek_lembur = $this->Timesheet_model->hitung_jumlah_jam_lembur($r->user_id,$tanggal1,$tanggal2);
                                $jumlah_lembur = $cek_lembur[0]->jumlah;

                                //$jumlah_lembur_total = $cek_lembur[0]->jumlah;

                                if ( $jumlah_lembur == 0) {

                                      $jumlah_jam_lembur_1   = 0;
                                      $jumlah_jam_lembur_2   = 0;

                                      $jumlah_biaya_lembur_1 = 0;
                                      $jumlah_biaya_lembur_2 = 0;

                                      $jumlah_jam_lembur     = 0;
                                      $jumlah_biaya_lembur   = 0;

                                     
                                } else if ( $jumlah_lembur > 0) {
                                  
                                   
                                        // jam

                                        $cek_lembur_jam_1      = $this->Timesheet_model->hitung_jumlah_jam_lembur_1($r->user_id,$tanggal1,$tanggal2);                            
                                        $jumlah_jam_lembur_1   = $cek_lembur_jam_1[0]->jumlah;
                                     
                                        
                                        $cek_lembur_jam_2      = $this->Timesheet_model->hitung_jumlah_jam_lembur_2($r->user_id,$tanggal1,$tanggal2);                            
                                        $jumlah_jam_lembur_2   = $cek_lembur_jam_2[0]->jumlah;

                                       
                                        $cek_lembur_jam_total     = $this->Timesheet_model->hitung_jumlah_jam_lembur($r->user_id,$tanggal1,$tanggal2);                            
                                        $jumlah_jam_lembur   = $cek_lembur_jam_total[0]->jumlah;

                                      

                                        // biaya

                                        $cek_lembur_biaya_1    = $this->Timesheet_model->hitung_jumlah_biaya_lembur_1($r->user_id,$tanggal1,$tanggal2);
                                        $biaya_lembur_1        = $cek_lembur_biaya_1[0]->jumlah;

                                        if($biaya_lembur_1 == 0){
                                           $jumlah_biaya_lembur_1 = 0;                            
                                        } else {                            
                                           $jumlah_biaya_lembur_1 = $biaya_lembur_1;                            
                                        }
                                        
                                        $cek_lembur_biaya_2    = $this->Timesheet_model->hitung_jumlah_biaya_lembur_2($r->user_id,$tanggal1,$tanggal2);
                                        $biaya_lembur_2        = $cek_lembur_biaya_2[0]->jumlah;

                                        if($biaya_lembur_2 == 0){
                                           $jumlah_biaya_lembur_2 = 0;                            
                                        } else {                            
                                           $jumlah_biaya_lembur_2 = $biaya_lembur_2;                            
                                        }                          

                                        $jumlah_biaya_lembur   = $jumlah_biaya_lembur_1+$jumlah_biaya_lembur_2;

                                   
                                }

                            ?>
                            <?php $employee_name = $full_name;?>
                      
                          <tr>
                              <td class="text-center"><?php echo $no;?></td>
                              
                              <td width="400px"><?php echo $full_name;?></td>

                              <?php foreach($xin_tanggal as $t):?>  
                              <?php $attendance_date = $t->tanggal; ?>
                               <?php $day   = $this->Timesheet_model->conHari(date("D",strtotime($t->tanggal))); ?>
                              <?php $warna   = $this->Timesheet_model->conWarna($day); ?>   
                           

                               <?php 
                             
                                $cek_status = $this->Timesheet_model->cek_status_kehadiran($r->user_id,$attendance_date); 

                                if(!is_null($cek_status)){
                                  
                                     $cek_lembur = $this->Timesheet_model->cek_jumlah_lembur($r->user_id,$attendance_date); 

                                        if(!is_null($cek_lembur)){

                                           $cek_status_name = $cek_lembur[0]->jumlah;
                                        
                                        } else {
                                            $cek_status_name = '';
                                        }      
                              
                                } else {
                                  
                                  $cek_status_name = ''; 
                                
                                }

                              ?>
                             
                              <td style="<?php echo $warna; ?>"><center><?php echo $cek_status_name; ?></center></td>

                              <?php endforeach;?>

                               <td style="text-align: center;"> <?php echo $jumlah_jam_lembur;?> </td>

                              <td style="text-align: center;"> <?php echo $jumlah_jam_lembur_1;?> </td>
                              <td style="text-align: center;"> <?php echo $jumlah_jam_lembur_2;?> </td>    

                              <td style="text-align: right;"> <?php echo $this->Core_model->currency_sign($jumlah_biaya_lembur_1);?> </td>
                              <td style="text-align: right;"> <?php echo $this->Core_model->currency_sign($jumlah_biaya_lembur_2);?> </td>                 
                              
                             
                              <td style="text-align: right;"> <?php echo $this->Core_model->currency_sign($jumlah_biaya_lembur);?> </td>                         
                             
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
