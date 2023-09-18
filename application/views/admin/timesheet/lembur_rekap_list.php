<?php
/* Attendance view
*/
?>
<?php $session            = $this->session->userdata('username'); ?>
<?php $user_info          = $this->Core_model->read_user_info($session['user_id']); ?>
<?php $role_resources_ids = $this->Core_model->user_role_resource(); ?>
<?php $get_animate        = $this->Core_model->get_content_animate(); ?>
<?php $system             = $this->Core_model->read_setting_info(1); ?>


<?php

$company_id    = $this->input->post('company_id');
$month_year    = $this->input->post('month_year');

if (!isset($month_year)) {

  $skrg     = date('Y-m');

  $xin_bulan     = $this->Timesheet_model->get_xin_employees_bulan($skrg);

  $bulan         = $xin_bulan ? $xin_bulan[0]->month_payroll : '';
  $company_id    = 1;
  $xin_tanggal   = $this->Timesheet_model->get_xin_employees_tanggal($bulan);
  $tanggal       = $this->Timesheet_model->read_tanggal_information($bulan);

  if (!is_null($tanggal)) {
    $start_att = date("d-m-Y", strtotime($tanggal[0]->start_date));
    $end_att   = date("d-m-Y", strtotime($tanggal[0]->end_date));

    $start_date    = new DateTime($tanggal[0]->start_date);
    $end_date      = new DateTime($tanggal[0]->end_date);
    $interval_date = $end_date->diff($start_date);
  } else {
    $start_att     = '';
    $end_att       = '';

    $start_date    = '';
    $end_date      = '';
    $interval_date = '';
  }
} else {

  $xin_tanggal   = $this->Timesheet_model->get_xin_employees_tanggal($month_year);

  $bulan         = $month_year;

  $tanggal = $this->Timesheet_model->read_tanggal_information($month_year);
  if (!is_null($tanggal)) {
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
}

?>

<?php $attributes = array('name' => 'lembur_rekap_proses', 'id' => 'lembur_rekap_proses', 'autocomplete' => 'off', 'class' => 'add form-hrm'); ?>
<?php $hidden     = array('user_id' => $session['user_id']); ?>
<?php echo form_open('admin/timesheet/lembur_rekap_proses', $attributes, $hidden); ?>
<?php
$data = array(
  'type'     => 'hidden',
  'name'     => 'date_format',
  'id'       => 'date_format',
  'value'    => $this->Core_model->set_date_format(date('Y-m-d')),
  'class'    => 'form-control',
);
echo form_input($data);
?>

<div class="row <?php echo $get_animate; ?>">
  <div class="col-md-12">
    <div class="box mb-4">
      <div class="box-header with-border">
        <h3 class="box-title"> Proses Rekap Lembur </h3>
      </div>
      <div class="box-body">
        <div class="row">

          <div class="col-md-3">
            <div class="form-group">
              <label for="first_name"><?php echo $this->lang->line('left_company'); ?></label>
              <select class="form-control input-sm" name="company_id" id="company_id" data-plugin="select_hrm" data-placeholder="<?php echo $this->lang->line('left_company'); ?>">
                <?php foreach ($get_all_companies as $company) { ?>
                  <option value="<?php echo $company->company_id ?>"><?php echo $company->name ?></option>
                <?php } ?>
              </select>
            </div>
          </div>

          <div class="col-md-2">
            <div class="form-group">
              <label for="name">Jenis Karyawan</label>
              <select name="jenis_gaji" id="jenis_gaji" class="form-control input-sm" data-plugin="select_hrm" data-placeholder="<?php echo $this->lang->line('left_location'); ?>">
                <?php foreach ($all_office_shifts as $payroll_jenis) { ?>
                  <option value="<?php echo $payroll_jenis->jenis_gaji_id ?>"><?php echo strtoupper($payroll_jenis->jenis_gaji_name); ?></option>
                <?php } ?>
              </select>
            </div>
          </div>

          <div class="col-md-2">
            <div class="form-group">
              <label for="month_year">Bulan Lembur</label>
              <select class="form-control input-sm" name="month_year" id="month_year" data-plugin="select_hrm" data-placeholder="<?php echo $this->lang->line('xin_e_details_month'); ?>" required>
                <?php foreach ($all_bulan_gaji as $bulan_gaji) {
                  $title_dates = array();
                  $start_date = new DateTime($bulan_gaji->start_date);
                  $start = (int) $start_date->format('j');
                  foreach (range(1, 31) as $i) {
                    $title_dates[] = $start;

                    $start = $start == 31 ? $start = 1 : $start + 1;
                  }
                ?>
                  <option data-title='<?= json_encode($title_dates) ?>' value="<?= $bulan_gaji->month_payroll; ?>" <?php if ($bulan_gaji->month_payroll == $bulan) : ?> selected="selected" <?php endif; ?>>
                    <?= strtoupper($bulan_gaji->desc); ?>
                  </option>
                <?php } ?>
              </select>
            </div>
          </div>

          <div class="col-md-2 box-period" style="display: none;">
            <div class="form-group" id="periode_ajax">
              <label for="first_name">Periode Kehadiran</label>
              <select disabled="disabled" class="form-control input-sm" name="periode_id" id="periode_id" data-plugin="select_hrm" data-placeholder="<?php echo $this->lang->line('xin_e_details_month'); ?>" required disabled>
                <option value="0"> Pilih Periode Kehadiran </option>
              </select>
            </div>
          </div>

          <div class="col-md-3">
            <div class="form-group"> &nbsp;
              <label for="first_name">&nbsp;</label><br />



              <button type="button" class="btn  btn-success" onclick="searchDataAttendance()" title="Tampilkan">
                <i class="fa fa-filter"></i>
                Tampilkan
              </button>

            </div>
          </div>

        </div>
      </div>
    </div>
  </div>
</div>

<div class="box <?php echo $get_animate; ?>">

  <div class="box-body">
    <div class="row">
      <div class="col-md-12">

        <div class="box" style="margin-bottom: 0px;">
          <div class="box-header with-border">
            <span class="info_rekap"></span>
            <div class="box-tools pull-right" id="myBtn" style="display:none;">

              <?php if (in_array('0942', $role_resources_ids)) { ?>


                <button type="submit" class="btn  btn-warning save" title="Rekap Lembur">
                  <i class="fa fa-gears"></i> Rekap Lembur
                </button>
              <?php } ?>

            </div>
          </div>
          <div class="box-body" id="myDIV" style="display:none;">
            <div class="box-datatable table-responsive">
              <table class="datatables-demo table table-striped table-bordered" id="xin_table_recap" style="width:150%;">
                <thead>
                  <tr>
                    <th width="20px" rowspan="2" style="vertical-align: middle !important;"><?php echo $this->lang->line('xin_nomor'); ?></th>
                    <th width="300px" rowspan="2" style="vertical-align: middle !important;">
                      <center><?php echo $this->lang->line('xin_employee'); ?></center>
                    </th>
                    <th colspan="31" class="col-date-head"> Lembur Bulan <span id="p_month"></span></th>

                    <th rowspan="2" width="90px" style="vertical-align: middle !important;">
                      <center>Total<br>Jam<br>Lembur</center>
                    </th>
                    <th colspan="2" style="vertical-align: middle !important;">
                      <center>Jam Lembur</center>
                    </th>
                    <th colspan="2" style="vertical-align: middle !important;">
                      <center>Biaya Lembur</center>
                    </th>
                    <th rowspan="2" width="90px" style="vertical-align: middle !important;">
                      <center>Total<br>Biaya<br>Lembur</center>
                    </th>

                  </tr>
                  <tr class="row_dates">
                    <!-- Kolom-kolom dinamis akan ditambahkan di sini menggunakan JavaScript -->

                    <th width="90px" style="vertical-align: middle !important;" class="anchor">
                      <center>Jam 1</center>
                    </th>
                    <th width="90px" style="vertical-align: middle !important;">
                      <center>Jam<br>Selanjutnya</center>
                    </th>

                    <th width="90px" style="vertical-align: middle !important;">
                      <center>Jam 1</center>
                    </th>
                    <th width="90px" style="vertical-align: middle !important;">
                      <center>Jam<br>Selanjutnya</center>
                    </th>

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

<?php echo form_close(); ?>

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
