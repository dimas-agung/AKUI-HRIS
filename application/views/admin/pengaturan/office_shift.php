<?php

/**
 * Office Shift view
 */

$session = $this->session->userdata('username');
$get_animate = $this->Core_model->get_content_animate();
$role_resources_ids = $this->Core_model->user_role_resource();

if (in_array('0822', $role_resources_ids)) {
  $user_info = $this->Core_model->read_user_info($session['user_id']);
?>
  <div class="box mb-4 <?php echo $get_animate; ?>">
    <div id="accordion">
      <div class="box-header with-border">
        <h3 class="box-title"><i class="fa fa-plus-circle"></i> <?php echo $this->lang->line('xin_add_new'); ?></h3>
        <div class="box-tools pull-right"> <a class="text-dark collapsed" data-toggle="collapse" href="#add_form" aria-expanded="false">
            <button type="button" class="btn btn-xs btn-primary"> <span class="fa fa-plus-circle"></span> <?php echo $this->lang->line('xin_add_new'); ?></button>
          </a> </div>
      </div>
      <div id="add_form" class="collapse add-form <?php echo $get_animate; ?>" data-parent="#accordion" style="">
        <div class="box-body">
          <?php $attributes = array('name' => 'add_office_shift', 'id' => 'xin-form', 'autocomplete' => 'off'); ?>
          <?php $hidden = array('user_id' => $session['user_id']); ?>
          <?php echo form_open('admin/pengaturan/add_office_shift', $attributes, $hidden); ?>
          <div class="bg-white">
            <div class="box-block">

              <div class="row">

                <div class="col-md-6">

                  <div class="row">
                    <div class="col-md-12">
                      <div class="form-group">
                        <label for="employee" class="control-label">Perusahaan</label>
                        <select class="form-control input-sm" name="company_id" id="aj_company" data-plugin="select_hrm" data-placeholder="<?php echo $this->lang->line('module_company_title'); ?>">
                          <option value=""><?php echo $this->lang->line('xin_select_one'); ?></option>
                          <?php foreach ($get_all_companies as $company) { ?>
                            <option value="<?php echo $company->company_id; ?>"> <?php echo $company->name; ?></option>
                          <?php } ?>
                        </select>
                      </div>
                    </div>
                  </div>

                  <div class="row">
                    <div class="col-md-12">
                      <div class="form-group">
                        <label for="employee" class="control-label">Nama Pola Kerja</label>
                        <input class="form-control" placeholder="Nama Pola Kerja Shift" name="shift_name" type="text" value="" id="name">
                      </div>
                    </div>
                  </div>

                </div>

                <div class="col-md-6">

                  <div class="row">
                    <div class="col-md-12">
                      <div class="form-group">
                        <label for="employee" class="control-label">Bulan Kerja</label>
                        <select onchange="cekBulanKerja()" class="form-control input-sm" name="payroll_id" id="payroll_id" data-plugin="select_hrm" data-placeholder="<?php echo $this->lang->line('xin_e_details_month_work'); ?>" required>
                          <option value=""></option>
                          <?php foreach ($all_bulan_gaji as $bulan_gaji) { ?>
                            <option value="<?php echo $bulan_gaji->payroll_id ?>"><?php echo $bulan_gaji->desc ?></option>
                          <?php } ?>
                        </select>
                      </div>
                    </div>
                  </div>

                  <div class="row">
                    <div class="col-md-12">
                      <div class="form-group">
                        <label for="employee" class="control-label">Periode Tanggal</label>
                        <div class=" row">
                          <div class="col-md-6">
                            <div class="form-group">
                              <input class="form-control attendance_date" placeholder="<?php echo $this->lang->line('xin_select_date'); ?>" readonly id="start_date" name="start_date" type="text" value="<?php echo date('Y-m-d'); ?>">
                            </div>
                          </div>
                          <div class="col-md-6">
                            <div class="form-group">
                              <input class="form-control attendance_date" placeholder="<?php echo $this->lang->line('xin_select_date'); ?>" readonly id="end_date" name="end_date" type="text" value="<?php echo date('Y-m-d'); ?>">
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>

                </div>

              </div>

              <div class="row">

                <div class="col-md-12">

                  <div style="background-color: #cbf3e6;padding: 10px;margin-bottom: 15px;">
                    Jadwal Kerja Harian : <span class="info"></span>
                  </div>

                  <div class="form-group row">

                    <div class="col-md-12">

                      <div class="box-datatable table-responsive">
                        <table class="datatables-demo table table-striped table-bordered" width="100%">
                          <thead>
                            <tr>
                              <th width="5%">Minggu </th>
                              <th width="5%" colspan="7">Tanggal</th>
                            </tr>
                          </thead>

                          <tbody>

                            <tr>
                              <th width="5%" rowspan="2" style="text-align: center;">Minggu <br>1</th>
                              <?php foreach (range(1, 7) as $i) {
                                echo "<th width=\"5%\">{$i}</th>";
                              } ?>
                            </tr>

                            <tr>
                              <?php
                              foreach (range(1, 7) as $i) {
                                $key = str_pad($i, 2, '0', STR_PAD_LEFT);
                              ?>
                                <td width="5%" style="text-align: center;">
                                  <select class="form-control input-sm" name="T<?= $key ?>" id="T<?= $key ?>" data-plugin="select_hrm" data-placeholder="Tanggal <?= $key ?>">
                                    <option value="K"> K - Kosong </option>
                                    <?php foreach ($all_jam_shift as $jam_shift) { ?>
                                      <option value="<?php echo $jam_shift->kode ?>"><?php echo $jam_shift->kode; ?> - <?php echo $jam_shift->start_date; ?> s/d <?php echo $jam_shift->end_date; ?></option>
                                    <?php } ?>
                                  </select>
                                </td>
                              <?php } ?>
                            </tr>

                            <tr>
                              <th width="5%" rowspan="2" style="text-align: center;">Minggu <br>2</th>
                              <?php foreach (range(8, 14) as $i) {
                                echo "<th width=\"5%\">{$i}</th>";
                              } ?>
                            </tr>

                            <tr>
                              <?php
                              foreach (range(8, 14) as $i) {
                                $key = str_pad($i, 2, '0', STR_PAD_LEFT);
                              ?>
                                <td width="5%" style="text-align: center;">
                                  <select class="form-control input-sm" name="T<?= $key ?>" id="T<?= $key ?>" data-plugin="select_hrm" data-placeholder="Tanggal <?= $key ?>">
                                    <option value="K"> K - Kosong </option>
                                    <?php foreach ($all_jam_shift as $jam_shift) { ?>
                                      <option value="<?php echo $jam_shift->kode ?>"><?php echo $jam_shift->kode; ?> - <?php echo $jam_shift->start_date; ?> s/d <?php echo $jam_shift->end_date; ?></option>
                                    <?php } ?>
                                  </select>
                                </td>
                              <?php } ?>
                            </tr>

                            <tr>
                              <th width="5%" rowspan="2" style="text-align: center;">Minggu <br>3</th>
                              <?php foreach (range(15, 21) as $i) {
                                echo "<th width=\"5%\">{$i}</th>";
                              } ?>
                            </tr>

                            <tr>
                              <?php
                              foreach (range(15, 21) as $i) {
                                $key = str_pad($i, 2, '0', STR_PAD_LEFT);
                              ?>
                                <td width="5%" style="text-align: center;">
                                  <select class="form-control input-sm" name="T<?= $key ?>" id="T<?= $key ?>" data-plugin="select_hrm" data-placeholder="Tanggal <?= $key ?>">
                                    <option value="K"> K - Kosong </option>
                                    <?php foreach ($all_jam_shift as $jam_shift) { ?>
                                      <option value="<?php echo $jam_shift->kode ?>"><?php echo $jam_shift->kode; ?> - <?php echo $jam_shift->start_date; ?> s/d <?php echo $jam_shift->end_date; ?></option>
                                    <?php } ?>
                                  </select>
                                </td>
                              <?php } ?>
                            </tr>

                            <tr>
                              <th width="5%" rowspan="2" style="text-align: center;">Minggu <br>4</th>
                              <?php foreach (range(22, 28) as $i) {
                                echo "<th width=\"5%\">{$i}</th>";
                              } ?>
                            </tr>

                            <tr>
                              <?php
                              foreach (range(22, 28) as $i) {
                                $key = str_pad($i, 2, '0', STR_PAD_LEFT);
                              ?>
                                <td width="5%" style="text-align: center;">
                                  <select class="form-control input-sm" name="T<?= $key ?>" id="T<?= $key ?>" data-plugin="select_hrm" data-placeholder="Tanggal <?= $key ?>">
                                    <option value="K"> K - Kosong </option>
                                    <?php foreach ($all_jam_shift as $jam_shift) { ?>
                                      <option value="<?php echo $jam_shift->kode ?>"><?php echo $jam_shift->kode; ?> - <?php echo $jam_shift->start_date; ?> s/d <?php echo $jam_shift->end_date; ?></option>
                                    <?php } ?>
                                  </select>
                                </td>
                              <?php } ?>
                            </tr>

                            <tr>
                              <th width="5%" rowspan="2" style="text-align: center;">Minggu <br>5</th>
                              <?php foreach (range(29, 31) as $i) {
                                echo "<th width=\"5%\">{$i}</th>";
                              } ?>
                              <th colspan="4"></th>
                            </tr>

                            <tr>
                              <?php
                              foreach (range(29, 31) as $i) {
                                $key = str_pad($i, 2, '0', STR_PAD_LEFT);
                              ?>
                                <td width="5%" style="text-align: center;">
                                  <select class="form-control input-sm" name="T<?= $key ?>" id="T<?= $key ?>" data-plugin="select_hrm" data-placeholder="Tanggal <?= $key ?>">
                                    <option value="K"> K - Kosong </option>
                                    <?php foreach ($all_jam_shift as $jam_shift) { ?>
                                      <option value="<?php echo $jam_shift->kode ?>"><?php echo $jam_shift->kode; ?> - <?php echo $jam_shift->start_date; ?> s/d <?php echo $jam_shift->end_date; ?></option>
                                    <?php } ?>
                                  </select>
                                </td>
                              <?php } ?>


                              <th colspan="4"></th>
                            </tr>

                          </tbody>
                        </table>
                      </div>

                    </div>

                  </div>

                </div>

              </div>

              <div class="form-actions box-footer">
                <button type="submit" class="btn btn-primary"> <i class="fa fa-save"></i> <?php echo $this->lang->line('xin_save'); ?> </button>
              </div>
            </div>
          </div>
          <?php echo form_close(); ?>
        </div>
      </div>
    </div>
  </div>
<?php } ?>

<div class="box <?php echo $get_animate; ?>">
  <div class="box-header with-border">
    <h3 class="box-title"> <b>JADWAL KERJA SHIFT </b> </h3>
    <h5>
      <span style="float: left;">
        Jika anda ingin menambah. merubah, menghapus Jam Pola Kerja Shift ini, silahkan klik tombol "Jam Kerja Shift" </span>
      <span style="float: right;">
        <?php if (in_array('0831', $role_resources_ids)) { ?>
          <a href="<?php echo site_url('admin/pengaturan/office_shift_jam'); ?>" target="_blank" class="btn btn-xs btn-success"> <i class="fa fa-clock-o"></i> Jam Kerja Shift</a>
        <?php } ?>
      </span>
    </h5>
  </div>
  <div class="box-body">
    <div class="box-datatable table-responsive">
      <table class="datatables-demo table table-striped table-bordered" id="xin_table" width="180%">
        <thead>
          <tr>
            <th width="80px" rowspan="2" style="text-align: center;"><?php echo $this->lang->line('xin_option'); ?></th>
            <th width="600px" rowspan="2" style="text-align: center;"><?php echo $this->lang->line('xin_day'); ?><br>(Daftar Karyawan)</th>
            <th colspan="31" style="text-align: center;">Tanggal</th>
          </tr>

          <tr>
            <?php foreach (range(1, 31) as $i) { ?>
              <th width="90px" style="text-align: center !important;">
                <center><?= str_pad($i, 2, '0', STR_PAD_LEFT) ?></center>
              </th>
            <?php } ?>
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

<script type="text/javascript">
  // $(document).ready(function(){
  //      document.getElementById("T29").disabled = false;
  //       document.getElementById("T30").disabled = false;
  //       document.getElementById("T31").disabled = false;
  // });

  function cekBulanKerja() {
    var payroll_id = document.getElementsByName("payroll_id")[0].value;

    document.getElementById("T29").disabled = false;
    document.getElementById("T30").disabled = false;
    document.getElementById("T31").disabled = false;

    if (payroll_id == '1') {
      $(".info").html('Januari 2022');
    } else if (payroll_id == '2') {
      $(".info").html('Februari 2022');
    } else if (payroll_id == '3') {
      $(".info").html('Maret 2022');
    } else if (payroll_id == '4') {
      $(".info").html('April 2022');
    } else if (payroll_id == '5') {
      $(".info").html('Mei 2022');
    } else if (payroll_id == '6') {
      $(".info").html('Juni 2022');
    } else if (payroll_id == '7') {
      $(".info").html('Juli 2022');
    } else if (payroll_id == '8') {
      $(".info").html('' + payroll_id + '');
    }
  }
</script>
