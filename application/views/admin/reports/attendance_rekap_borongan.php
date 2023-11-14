<?php
/* Monthly Timesheet view > hris
*/
$session      = $this->session->userdata('username');
$get_animate  = $this->Core_model->get_content_animate();

$user_info          = $this->Core_model->read_user_info($session['user_id']);
$role_resources_ids = $this->Core_model->user_role_resource();

$jenis_company  = $this->input->post('company_id');
$start_date     = $this->input->post('start_date');
$end_date       = $this->input->post('end_date');

if (!isset($start_date)) {
  $start_date   = date('Y-m-01');
  $end_date     = date('Y-m-01');

  $jenis_company = 1;

  $xin_tanggal   = $this->Timesheet_model->get_xin_employees_tanggal_periode($start_date, $end_date);

  $start_att = date("d-m-Y", strtotime($start_date));
  $end_att   = date("d-m-Y", strtotime($end_date));

  $hit_start_date    = new DateTime($start_date);
  $hit_end_date      = new DateTime($end_date);
  $interval_date = $hit_end_date->diff($hit_start_date);

  if ($jenis_company == '1') {
    $company_name     = 'PT Akui Bird Nest Indonesia';
  } else if ($jenis_company == '2') {
    $company_name     = 'PT ORIGINAL BERKAH INDONESIA';
  } else if ($jenis_company == '3') {
    $company_name     = 'PT WALET ABDILLAH JABLI';
  }

  // $xin_employees = $this->Timesheet_model->get_employees_borongan_rekap($jenis_company, $start_date, $end_date);
  $gaji_name     = 'Karyawan Borongan';
} else {

  $xin_tanggal   = $this->Timesheet_model->get_xin_employees_tanggal_periode($start_date, $end_date);

  $start_att = date("d-m-Y", strtotime($start_date));
  $end_att   = date("d-m-Y", strtotime($end_date));


  $hit_start_date    = new DateTime($start_date);
  $hit_end_date      = new DateTime($end_date);
  $interval_date = $hit_end_date->diff($hit_start_date);

  if ($jenis_company == '1') {
    $company_name     = 'PT Akui Bird Nest Indonesia';
  } else if ($jenis_company == '2') {
    $company_name     = 'PT ORIGINAL BERKAH INDONESIA';
  } else if ($jenis_company == '3') {
    $company_name     = 'PT WALET ABDILLAH JABLI';
  }

  // $xin_employees = $this->Timesheet_model->get_employees_borongan_rekap($jenis_company, $start_date, $end_date);
  $gaji_name = 'Karyawan Borongan';

  $employee_ids = get_values($att_data, 0, 'employee_id');
  $dates        = get_values($xin_tanggal, 0, 'tanggal');

  $attendance_status  = $this->Timesheet_model->cek_multi_status_kehadiran($employee_ids, $dates);
  $attendance_data    = array();
  foreach ($attendance_status as $att) {
    $attendance_data["{$att->employee_id}-{$att->attendance_date}"] = $att;
  }
}

$attributes = array('name' => 'xin-form', 'id' => 'xin-form', 'autocomplete' => 'off');
$hidden = array('_user' => $session['user_id']);
echo form_open('admin/reports/attendance_rekap_borongan/', $attributes, $hidden);
?>

<div class="row <?php echo $get_animate; ?>">
  <div class="col-md-12">
    <div class="box mb-4">
      <div class="box-header with-border">
        <h3 class="box-title"> Tampilkan Kehadiran Rekap Borongan </h3>
      </div>
      <div class="box-body">
        <div class="row">
          <div class="col-md-12">

            <div class="row">

              <div class="col-md-3">
                <div class="form-group">
                  <label for="first_name"><?php echo $this->lang->line('left_company'); ?></label>
                  <select class="form-control input-sm" name="company_id" id="company_id" data-plugin="select_hrm" data-placeholder="<?php echo $this->lang->line('left_company'); ?>">
                    <?php foreach ($get_all_companies as $company) { ?>
                      <option value="<?php echo $company->company_id ?>" <?php if ($company->company_id == $jenis_company) : ?> selected="selected" <?php endif; ?>><?php echo strtoupper($company->name); ?></option>
                    <?php } ?>
                  </select>
                </div>
              </div>

              <div class="col-md-2">
                <div class="form-group">
                  <label for="first_name"> Tanggal Mulai </label>
                  <input class="form-control date" placeholder="<?php echo $this->lang->line('xin_select_date'); ?>" readonly id="start_date" name="start_date" type="text" value="<?php echo $start_date; ?>">
                </div>
              </div>
              <div class="col-md-2">
                <div class="form-group">
                  <label for="first_name"> Tanggal Sampai </label>
                  <input class="form-control date" placeholder="<?php echo $this->lang->line('xin_select_date'); ?>" readonly id="end_date" name="end_date" type="text" value="<?php echo $end_date; ?>">
                </div>
              </div>

              <div class="col-md-3">
                <div class="form-group">
                  <label>&nbsp;</label>
                  <br />
                  <?php echo form_button(array('name' => 'hris_form', 'type' => 'submit', 'class' => 'btn btn-primary', 'content' => '<i class="fa fa fa-check-square-o"></i> ' . $this->lang->line('xin_get_filter'))); ?>
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
        <h3 class="box-title text-uppercase text-bold"> Kehadiran Borongan : </h3>
        <h5>
          Periode Kehadiran : <?php echo $start_att; ?> s/d <?php echo $end_att; ?> (<?php echo $interval_date->d; ?> hari) - Jenis : <?php echo $gaji_name; ?> -
          Pola Kerja : Reguler Di <?php echo ucfirst($company_name); ?>
        </h5>
        <div class="box-tools pull-right"> </div>
      </div>

      <div class="box-body">
        <div class="box-datatable table-responsive">
          <table class="datatables-demo table table-striped table-bordered" id="xin_table_borongan" width="100%">
            <thead>
              <tr>
                <th rowspan="2" style="vertical-align: middle !important;"><?php echo $this->lang->line('xin_nomor'); ?></th>
                <th rowspan="2" style="vertical-align: middle !important;"><?php echo $this->lang->line('xin_employee'); ?></th>
                <th colspan="<?php echo $interval_date->d + 1; ?>"> Tanggal </th>
                <th colspan="8" style="vertical-align: middle !important;"><?php echo $this->lang->line('xin_timesheet_workdays_jumlah'); ?></th>
                <th colspan="2" style="vertical-align: middle !important;"><?php echo $this->lang->line('xin_absent_late'); ?></th>
                <th rowspan="2" style="vertical-align: middle !important;"><?php echo $this->lang->line('xin_timesheet_workdays_total'); ?></th </tr>
              <tr>
                <?php foreach ($xin_tanggal as $t) : ?>
                  <?php $start = date("M d", strtotime($t->tanggal)); ?>
                  <?php $day   = $this->Timesheet_model->conHari(date("D", strtotime($t->tanggal))); ?>
                  <?php $warna   = $this->Timesheet_model->conWarna($day); ?>

                  <th width="12px" style="<?php echo $warna; ?>">
                    <center><?php echo $start; ?><br><?php echo $day; ?> </center>
                  </th>
                <?php endforeach; ?>

                <th width="12px" style="vertical-align: middle !important;">
                  <center><?php echo $this->lang->line('xin_holiday_judul'); ?></center>
                </th>
                <th width="12px" style="vertical-align: middle !important;">
                  <center><?php echo $this->lang->line('xin_on_libur_simbol'); ?></center>
                </th>
                <th width="12px" style="vertical-align: middle !important;">
                  <center><?php echo $this->lang->line('xin_present_judul'); ?></center>
                </th>

                <th width="12px" style="vertical-align: middle !important;">
                  <center><?php echo $this->lang->line('xin_on_sick_simbol'); ?></center>
                </th>
                <th width="12px" style="vertical-align: middle !important;">
                  <center><?php echo $this->lang->line('xin_on_izin_simbol'); ?></center>
                </th>
                <th width="12px" style="vertical-align: middle !important;">
                  <center><?php echo $this->lang->line('xin_on_leave_simbol'); ?></center>
                </th>

                <th width="12px" style="vertical-align: middle !important;">
                  <center><?php echo $this->lang->line('xin_absent_simbol'); ?></center>
                </th>
                <th width="12px" style="vertical-align: middle !important;">
                  <center><?php echo $this->lang->line('xin_travels_simbol'); ?></center>
                </th>
                <th width="12px" style="vertical-align: middle !important;">
                  <center><?php echo $this->lang->line('xin_absent_menit'); ?></center>
                </th>
                <th width="12px" style="vertical-align: middle !important;">
                  <center><?php echo $this->lang->line('xin_absent_jam'); ?></center>
                </th>

              </tr>
            </thead>
            <tbody>
              <?php

              $no = 1;
              $j = 0;
              foreach ($att_data as $r) :


                $full_name = "{$r->first_name} {$r->last_name}";
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

                $tanggal1 = date("Y-m-d", strtotime($start_att));
                $tanggal2 = date("Y-m-d", strtotime($end_att));
                $tanggal_masuk = $r->date_of_joining;

              ?>

                <tr>

                  <td width="20px" class="text-center"><?php echo $no; ?></td>
                  <td width="400px">
                    <?php echo $full_name; ?><br>
                    <i class="fa fa-check-square-o"></i> Mulai Bekerja : <?php echo date("d-m-Y", strtotime($tanggal_masuk)); ?>
                  </td>

                  <?php foreach ($xin_tanggal as $t) :
                    $attendance_date = $t->tanggal;
                    $day        = $this->Timesheet_model->conHari(date("D", strtotime($t->tanggal)));
                    $warna      = $this->Timesheet_model->conWarna($day);
                    // $cari_isi   = $this->Timesheet_model->cek_status_kehadiran($r->employee_id, $attendance_date);

                    if (isset($attendance_data["{$r->employee_id}-{$t->tanggal}"])) {
                      $status = $attendance_data["{$r->employee_id}-{$t->tanggal}"];
                      $isi = $status->attendance_status_simbol;

                      if ($isi == '?' || $isi == '') {
                        $info   = '';
                        $warnae = 'background-color : #ddd;';
                      } else if ($isi == 'H') {
                        $info   = '.';
                        $warnae = '';
                      } else {
                        date_default_timezone_set("Asia/Jakarta");
                        $tanggal_sekrg = date("Y-m-d");

                        if ($t->tanggal == $tanggal_sekrg) {

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

                    <td width="50px" style="<?php echo $warnae; ?>">
                      <center><?php echo $info; ?></center>
                    </td>

                  <?php endforeach; ?>

                  <td width="70px" style="text-align: center; background-color : #ddc3c3 !important;"> <?php echo $jumlah_libur; ?> </td>
                  <td width="70px" style="text-align: center; background-color : #ecd5c2 !important;"> <?php echo $jumlah_libur_kantor; ?> </td>
                  <td width="70px" style="text-align: center; background-color : #c6edd1 !important;"> <?php echo $jumlah_hadir; ?> </td>

                  <td width="70px" style="text-align: center; background-color : #c6dbed !important;"> <?php echo $jumlah_sakit; ?> </td>
                  <td width="70px" style="text-align: center; background-color : #e7debd !important;"> <?php echo $jumlah_izin; ?> </td>
                  <td width="70px" style="text-align: center; background-color : #bde7e7 !important;"> <?php echo $jumlah_cuti; ?> </td>
                  <td width="70px" style="text-align: center; background-color : #edeac6 !important;"> <?php echo $jumlah_alpa; ?> </td>
                  <td width="70px" style="text-align: center; background-color : #c6e9ed !important;"> <?php echo $jumlah_dinas; ?> </td>

                  <td width="70px" style="text-align: center; background-color : #edc6eb !important;"> <?php echo $jumlah_terlambat_menit; ?> </td>
                  <td width="70px" style="text-align: center; background-color : #edc6eb !important;"> <?php echo $jumlah_terlambat_jam; ?> </td>

                  <td width="70px" style="text-align: center; background-color : #cfc6ed !important;"> <?php echo $total; ?> </td>

                </tr>
                <?php $no++;  ?>
              <?php endforeach; ?>
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
    padding-left: 0px !important;
    padding-right: 0px !important;
  }

  .dataTables_length {
    float: left;
  }

  .dt-buttons {
    position: relative;
    float: right;
    margin-left: 10px;
  }

  .hide-calendar .ui-datepicker-calendar {
    display: none !important;
  }

  .hide-calendar .ui-priority-secondary {
    display: none !important;
  }
</style>
