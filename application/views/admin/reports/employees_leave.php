<?php
/* Monthly Timesheet view > hris
*/
?>
<?php $session = $this->session->userdata('username');?>
<?php $get_animate = $this->Core_model->get_content_animate();?>

<?php
$user_info          = $this->Core_model->read_user_info($session['user_id']);
$role_resources_ids = $this->Core_model->user_role_resource();


$jenis_company     = $this->input->post('company_id');
$year              = $this->input->post('year');
// $date               = strtotime(date("Y-m-d"));



  if(!isset($year)){

    $skrg     = date('Y');

    $xin_tahun = $this->Timesheet_model->get_xin_employees_tahun($skrg);

    $year           = $xin_tahun[0]->tahun;

    $tahun       = $xin_tahun[0]->tahun;
    

    $jenis_company = 1;


    $xin_bulan   = $this->Timesheet_model->get_xin_calendar_tahun($tahun);
    
    if ($jenis_company == 1) {
      $xin_employees = $this->Timesheet_model->get_xin_employees_cuti($jenis_company);
      $company_name     = 'PT AKUI BIRDNEST INDONESIA';
    } else if ($jenis_company == 2) {
      $xin_employees = $this->Timesheet_model->get_xin_employees_cuti($jenis_company);
      $company_name     = 'PT ORIGINAL BERKAH INDONESIA';
    } else if ($jenis_company == 3){
      $xin_employees = $this->Timesheet_model->get_xin_employees_cuti($jenis_company);
      $company_name     = 'PT WALET ABDILLAH JABLI';
    }

  } else {
    
    $xin_bulan   = $this->Timesheet_model->get_xin_calendar_tahun($year);
       
    if ($jenis_company == 1) {
      $xin_employees = $this->Timesheet_model->get_xin_employees_cuti($jenis_company);
      $company_name     = 'PT AKUI BIRDNEST INDONESIA';
    } else if ($jenis_company == 2) {
      $xin_employees = $this->Timesheet_model->get_xin_employees_cuti($jenis_company);
      $company_name     = 'PT ORIGINAL BERKAH INDONESIA';
    } else if ($jenis_company == 3){
      $xin_employees = $this->Timesheet_model->get_xin_employees_cuti($jenis_company);
      $company_name     = 'PT WALET ABDILLAH JABLI';
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

                <?php $attributes = array('name' => 'xin-form', 'id' => 'xin-form', 'autocomplete' => 'off');?>
                <?php $hidden = array('_user' => $session['user_id']);?>
                <?php echo form_open('admin/reports/employees_leave/', $attributes, $hidden);?>
                

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
                        <label for="first_name">Tahun</label>
                        <select class="form-control input-sm" name="year" id="year" data-plugin="select_hrm" data-placeholder="<?php echo $this->lang->line('xin_e_details_month');?>" required>
                          <?php foreach($all_tahun_gaji as $tahun_gaji) {?>
                            <option value="<?php echo $tahun_gaji->tahun;?>" <?php if($tahun_gaji->tahun==$year): ?> selected="selected" <?php endif; ?>><?php echo $tahun_gaji->tahun?></option>
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
            <h3 class="box-title text-uppercase text-bold"> Cuti Tahun : <?php if(isset($year)): echo date('Y', strtotime($year)); else: echo date('Y',strtotime($year)); endif;?>  </h3>
            
            <h5> 
                Perusahaan : <?php echo $company_name; ?> - Jenis : Karyawan Bulanan
            </h5>
            
            <div class="box-tools pull-right"> </div>
        </div>
        
          <div class="box-body">
            <div class="box-datatable table-responsive">
              <table class="datatables-demo table table-striped table-bordered" id="xin_table_bulanan" width="120%">
                <thead>
                  <tr>
                      <th rowspan="2" style="vertical-align: middle !important;"><?php echo $this->lang->line('xin_nomor');?></th>
                      <th rowspan="2" style="vertical-align: middle !important;"><?php echo $this->lang->line('xin_employee');?></th> 
                      <th rowspan="2" style="vertical-align: middle !important;">Tanggal<br>Rekrutmen</th> 
                      <th rowspan="2" style="vertical-align: middle !important;">Tanggal<br>Sekarang</th> 
                      <th rowspan="2" style="vertical-align: middle !important;">Masa<br>Kerja</th> 
                      <th rowspan="2" style="vertical-align: middle !important;">Tanggal<br>Hak Cuti</th> 
                      <th rowspan="2" style="vertical-align: middle !important;"><center>Hak<br>Cuti</center></th>
                      <th colspan="12"> Tahun <?php if(isset($year)): echo date('Y', strtotime($year)); else: echo date('Y',strtotime($tahun)); endif;?></th>
                      <th rowspan="2" style="vertical-align: middle !important;">Pakai<br>Cuti</th> 
                      <th rowspan="2" style="vertical-align: middle !important;">Sisa<br>Cuti</th> 
                      <th rowspan="2" style="vertical-align: middle !important;">Info Cuti</th>        
                  </tr>
                  <tr>
                      <?php foreach($xin_bulan as $t):?>  
                        <?php $bulan_no = $t->bulan; ?>
                        <?php $bulan_nm = $t->status; ?>
                        <th ><center><?php echo $bulan_no;?><br><?php echo $bulan_nm;?> </center></th>      
                      <?php endforeach;?>                   
                   </tr>
                </thead>

                <tbody>
                    <?php $no=1; ?>

                      <?php $j=0;foreach($xin_employees as $r):?>
                 
                        <?php                       
                       
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
      
                        $full_name = $r->first_name.' '.$r->last_name.'<br>
                                    <i class="fa fa-building"></i> '.$comp_name.'<br>
                                    <i class="fa fa-money"></i> '.$jenis_gaji.'<br>
                                    <i class="fa fa-briefcase"></i> '.$department_name.'<br>
                                    <i class="fa fa-tags"></i> '.$designation_name;
                        
                        $doj = date("d-m-Y", strtotime($r->date_of_joining));

                        date_default_timezone_set("Asia/Jakarta");     
                    
                        $tanggal1 = new DateTime($r->date_of_joining);
                        $tanggal2 = new DateTime();
                        
                        if ($tanggal2->diff($tanggal1)->y == 0) {
                          $selisih = $tanggal2->diff($tanggal1)->m.' bln';
                        } else {
                          $selisih = $tanggal2->diff($tanggal1)->y.' thn'.' '.$tanggal2->diff($tanggal1)->m.' bln';
                        }                       

                        if ($tanggal2->diff($tanggal1)->y == 0){
                          $bulan = $tanggal2->diff($tanggal1)->m;
                        } else {
                           $bulan = $tanggal2->diff($tanggal1)->y*12 + $tanggal2->diff($tanggal1)->m;
                        }

                         $don = date("d-m-Y", strtotime($r->date_of_now));

                        if ( $bulan < 12 ){

                            $info_cuti  = '<i class="fa fa-minus-circle"></i> Belum Punya Hak Cuti';
                            $warna      = 'text-align: center; background-color : #edc6c8 !important;"';
                            $warna_sisa = 'text-align: center; background-color : #edc6c8 !important;"';
                            $warna_info = 'text-align: left;   background-color : #edc6c8 !important;"';
                            $bulan_cuti = '';
                            $hak_cuti   = '0';
                        
                        } else {
                           
                            $tanggal_cuti = date("d", strtotime($r->date_of_leaving));

                            $bulan_cuti = date("m", strtotime($r->date_of_leaving));

                            $tahun_cuti = date("Y", strtotime($r->date_of_leaving));
                            $tahun_now = date("Y", strtotime($r->date_of_now));

                            if ( $bulan_cuti < 12 ){

                              if ($tahun_cuti < $tahun_now  ) {

                                  $info_cuti  = '<i class="fa fa-check-circle"></i> Hak Cuti Penuh';
                                  $warna      = 'text-align: center; background-color : #c6edca !important;"';
                                  $warna_sisa = 'text-align: center; background-color : #c6edca !important;"';
                                  $warna_info = 'text-align: left;   background-color : #c6edca !important;"';
                                  $hak_cuti   = 12;

                              } else {

                                if ($tanggal_cuti >= 1 and $tanggal_cuti <= 15) {

                                  $info_cuti  = '<i class="fa fa-check-circle"></i> Hak Cuti Prorate 1';
                                  $warna      = 'text-align: center; background-color : #edebc6 !important;"';
                                  $warna_sisa = 'text-align: center; background-color : #edebc6 !important;"';
                                  $warna_info = 'text-align: left;   background-color : #edebc6 !important;"';
                                  $hak_cuti   = 12-$bulan_cuti+1;

                                } else if ($tanggal_cuti >= 16 and $tanggal_cuti <= 31) {

                                  $info_cuti  = '<i class="fa fa-check-circle"></i> Hak Cuti Prorate 2';
                                  $warna      = 'text-align: center; background-color : #edebc6 !important;"';
                                  $warna_sisa = 'text-align: center; background-color : #edebc6 !important;"';
                                  $warna_info = 'text-align: left;   background-color : #edebc6 !important;"';
                                  $hak_cuti   = 12-$bulan_cuti;

                                }

                                 

                              }

                               // if ( $bulan > 12 ){
                               //    $info_cuti  = '<i class="fa fa-check-circle"></i> Hak Cuti Penuh';
                               //    $warna      = 'text-align: center; background-color : #c6edd1 !important;"';
                               //    $warna_sisa = 'text-align: center; background-color : #c6edd1 !important;"';
                               //    $warna_info = 'text-align: left    background-color : #c6edd1 !important;"';
                               //    $hak_cuti   = 12;
                               // } else {
                                
                               // }
                              
                            } else {

                              $info_cuti  = '<i class="fa fa-check-circle"></i> Hak Cuti Penuh';
                              $warna      = 'text-align: center; background-color : #c6edca !important;"';
                              $warna_sisa = 'text-align: center; background-color : #c6edca !important;"';
                              $warna_info = 'text-align: left;   background-color : #c6edca !important;"';
                              $hak_cuti   = 12;

                            }
                           
                        }

                        $dol = date("d-m-Y", strtotime($r->date_of_leaving)) ;
                                        
                     ?>                     
                  
                      <tr >
                          <td width="20px" class="text-center"><?php echo $no;?></td>
                          <td ><?php echo $full_name;?></td>
                          <td width="90px" class="text-center"><?php echo $doj;?></td>    
                          <td width="90px" class="text-center"><?php echo $don;?></td>                      
                          <td width="90px" class="text-center"><?php echo $selisih;?><br><?php echo $bulan.' bln'; ?><br><?php echo $r->selisih. 'hr'; ?></td>
                          <td width="90px" class="text-center"><?php echo $dol;?></td>
                          <td width="70px" style="<?php echo $warna; ?>"> <?php echo $hak_cuti; ?> </td>

                          <?php foreach($xin_bulan as $t):?>  
                              <?php $bulan_no = $t->bulan; ?>
                              <?php $bulan_nm = $t->status; ?>
                              <?php $employee_id = $r->user_id; ?>
                              
                              <?php $kode = $employee_id.'-'.$bulan_no.'-'.$r->year_of_now ; 

                               $cek_cuti_bulan = $this->Timesheet_model->cek_jumlah_cuti_bulan($r->user_id,$bulan_no,$r->year_of_now); 

                              if(!is_null($cek_cuti_bulan)){

                                
                                 if ($cek_cuti_bulan[0]->jumlah == ''){
                                     $jum_cuti_bulanan = '0';
                                    $warna     = 'text-align: center; background-color : #edc6c8 !important;"';
                                 } else {
                                    $jum_cuti_bulanan = $cek_cuti_bulan[0]->jumlah;
                                    $warna     = 'text-align: center; background-color : #eee !important;"';
                                 }
                                
                              
                              } else {
                                  $jum_cuti_bulanan = '0';
                                  $warna     = 'text-align: center; background-color : #edc6c8 !important;"';
                              } 

                               $cek_cuti_tahun = $this->Timesheet_model->cek_jumlah_cuti_tahun($r->user_id,$r->year_of_now); 

                               if(!is_null($cek_cuti_tahun)){

                                
                                  $jum_cuti_tahunan = $cek_cuti_tahun[0]->jumlah;
                                
                              
                              } else {
                                  $jum_cuti_tahunan = '0';
                                 
                              }

                              $sisa_cuti = $hak_cuti-$jum_cuti_tahunan;
                               
                              ?>

                          <td width="50px" style="<?php echo $warna; ?>"><center><?php echo $jum_cuti_bulanan; ?></center></td>

                          <?php endforeach;?>
                          <td width="90px" style="<?php echo $warna; ?>"> <?php echo $jum_cuti_tahunan;?> </td>
                          <td width="90px" style="<?php echo $warna_sisa; ?>"> <?php echo $sisa_cuti;?>  </td>
                          <td width="150px" style="<?php echo $warna_info; ?>"> <?php echo $info_cuti; ?>  </td>                          
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
