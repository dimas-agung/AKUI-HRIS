<?php
/* Generate Payslip view
*/

$session            = $this->session->userdata('username');
$user_info          = $this->Core_model->read_user_info($session['user_id']);
$role_resources_ids = $this->Core_model->user_role_resource();
$get_animate        = $this->Core_model->get_content_animate();
$system             = $this->Core_model->read_setting_info(1);
$start_date         = $this->input->post('start_date');
$end_date           = $this->input->post('end_date');

if (!isset($start_date)) {
    $skrg     = date('Y-m-d');

    $xin_bulan   = $this->Timesheet_model->get_xin_employees_bulan($skrg);
    if ($xin_bulan) {
        $bulan       = $xin_bulan[0]->desc;
        $start       = date("d-m-Y", strtotime($xin_bulan[0]->start_date));
        $end         = date("d-m-Y", strtotime($xin_bulan[0]->end_date));
    }
}
?>

<?php $attributes = array('name' => 'bulk_payment', 'id' => 'bulk_payment', 'class' => 'm-b-1 add form-hrm'); ?>
<?php $hidden     = array('user_id' => $session['user_id']); ?>
<?php echo form_open('admin/thr/add_pay_to_all_harian', $attributes, $hidden); ?>

<div class="row <?php echo $get_animate; ?>">
  <div class="col-md-12">
    <div class="box mb-4">
      <div class="box-header with-border">
        <h3 class="box-title"> Proses Gaji Harian </h3>
      </div>
      <div class="box-body">
        <div class="row">
          <div class="col-md-12">
            <div class="row">
              <div class="col-md-3">
                <div class="form-group">
                  <label for="first_name"><?php echo $this->lang->line('left_company'); ?></label>
                  <select class="form-control" name="company_id" id="aj_companyx" data-plugin="select_hrm" data-placeholder="<?php echo $this->lang->line('left_company'); ?>">
                    <?php foreach ($all_companies as $company) { ?>
                      <option value="<?php echo $company->company_id; ?>"> <?php echo $company->name; ?></option>
                    <?php } ?>
                  </select>
                </div>
              </div>

              <div class="col-md-2">
                <div class="form-group">
                  <label for="tahun_thr"> Tahun THR </label>
                  <select class="form-control input-sm" name="tahun_thr" id="tahun_thr" data-plugin="select_hrm" data-placeholder="Pilih Tahun" required>
                    <?php foreach ($all_tahun_thr as $tahun_thr) { ?>
                      <option value="<?php echo $tahun_thr->tahun; ?>" <?php if ($tahun_thr->tahun == $tahun_thr) : ?> selected="selected" <?php endif; ?>>
                        <?php echo $tahun_thr->tahun ?>
                      </option>
                    <?php } ?>
                  </select>
                </div>
              </div>

              <div class="col-md-2">
                <div class="form-group">
                  <label for="tanggal_thr"> Tanggal Batas THR </label>
                  <input class="form-control thr_date" placeholder="<?php echo $this->lang->line('xin_select_date'); ?>" id="tanggal_thr" name="tanggal_thr" type="text" value="<?php echo date('Y-m-d'); ?>">
                </div>
              </div>

              <div class="col-md-5">
                <div class="form-group" style="float: left; margin-top: 22px;">
                  <div class="form-actions">
                    <button type="button" class="btn  btn-warning" onclick="searchDataTHR()" title="Proses Gaji Harian">
                      <i class="fa fa-money"></i>
                      Proses Gaji Harian
                    </button>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="box <?php echo $get_animate; ?>">
  <div class="box-header with-border">

    <h3 class="box-title text-uppercase text-bold">
      PERINCIAN GAJI HARIAN - <span class="text-danger"> Periode : </span>
      <span class="text-danger" id="p_month"><?php echo $start_date; ?> - <?php echo $end_date; ?></span>
    </h3>

    <h5>
      <i class="fa fa-warning"></i> Silahkan klik tombol "<span class="blink blink_two kuning">Proses Gaji Harian</span>"
      Terlebih dahulu sebagai Draft Gaji Harian, Jika sudah Benar, silahkan Klik Tombol "<span class="blink blink_two hijau">Simpan Gaji Harian</span>"
      guna Proses Kirim ke Bagian Finance.
    </h5>

    <div class="box-tools pull-right" id="myBtn" style="display:none;">
      <?php if (in_array('1022', $role_resources_ids)) { ?>
        <button type="submit" class="btn  btn-primary save" title="Simpan Gaji">
          <i class="fa fa-save"></i> Simpan Gaji Harian
        </button>
      <?php } ?>
    </div>

  </div>
  <div class="box-body" id="myDIV" style="display:none;">
    <div class="box-datatable table-responsive">
      <table class="datatables-demo table table-striped table-bordered" id="xin_table_harian" width="2500px">
        <thead>
          <tr>
            <th width="150px" style="text-align: center !important;">
              <center><?php echo $this->lang->line('xin_action'); ?></center>
            </th>
            <th width="50px" style="text-align: center !important;">
              <center>No</center>
            </th>
            <th width="80px" style="text-align: center !important;">
              <center>Status<br>THR</center>
            </th>
            <th width="150px">
              <center><?php echo $this->lang->line('xin_employees_id'); ?></center>
            </th>
            <th width="300px">
              <center><?php echo $this->lang->line('xin_employee_name'); ?></center>
            </th>
            <th width="200px">
              <center><?php echo $this->lang->line('left_department'); ?></center>
            </th>
            <th width="200px">
              <center><?php echo $this->lang->line('xin_employee_designation_txt'); ?></center>
            </th>
            <th>
              <center>Tanggal<br>Mulai Kerja</center>
            </th>
            <th>
              <center>Masa<br>Kerja</center>
            </th>
            <th style="background-color: #4e7ccf;color: #fff;">
              <center>Total Gaji</center>
            </th>
            <th style="background-color: #4e7ccf;color: #fff;">
              <center>Gaji Rata-rata</center>
            </th>
            <th style="background-color: #2b8a38;color: #fff;">
              <center>Total THR</center>
            </th>
            <th style="background-color: #cfbe4e;color: #fff;">
              <center>No. Rekening</center>
            </th>
            <th style="background-color: #cfbe4e;color: #fff;">
              <center>Bank Transfer</center>
            </th>
          </tr>
        </thead>
      </table>
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

  function searchDataTHR() {
    const companyId = $('#aj_companyx').val();
    const thrYear = $('#tahun_thr').val();
    const thrDate = $('#tanggal_thr').val();

    if (companyId == '') {
      alert("Nama Perusahaan Belum Diisi !");
      $("#aj_companyx").focus();
    } else if (thrYear == '') {
      alert("Tahun THR Belum Diisi !");
      $("#tahun_thr").focus();
    } else if (thrDate == '') {
      alert("Tanggal Batas THR Belum Diisi !");
      $("#tanggal_thr").focus();
    } else {
      toastr.success('Proses THR Harian Berlangsung');

      const params = $.param({
        company_id: companyId,
        tahun_thr: thrYear,
        tanggal_thr: thrDate
      });

      $('#p_month').html(`${thrYear}, Tanggal Batas THR : ${thrDate}`);
      $('#xin_table_harian').DataTable({
        bDestroy: true,
        bSort: false,
        aLengthMenu: [
          [10, 30, 50, 100, -1],
          [10, 30, 50, 100, "All"]
        ],
        autoWidth: true,
        fixedColumns: true,
        fixedColumns: {
          leftColumns: 5
        },
        ajax: {
          url: `${site_url}thr/thr_list_harian/?${params}`,
          type: 'GET'
        },
        columns: [{
            name: "kolom_1",
            className: "text-center",
            width: "5%"
          },
          {
            name: "kolom_2",
            className: "text-center"
          },
          {
            name: "kolom_3",
            className: "text-center"
          },
          {
            name: "kolom_4",
            className: "text-center"
          },
          {
            name: "kolom_5",
            className: "text-center"
          },
          {
            name: "kolom_6",
            className: "text-center"
          },
          {
            name: "kolom_7",
            className: "text-center"
          },
          {
            name: "kolom_8",
            className: "text-center"
          },
          {
            name: "kolom_9",
            className: "text-center"
          },
          {
            name: "kolom_10",
            className: "text-right"
          },
          {
            name: "kolom_11",
            className: "text-right"
          },
          {
            name: "kolom_12",
            className: "text-right"
          },
          {
            name: "kolom_13",
            className: "text-center"
          },
          {
            name: "kolom_14",
            className: "text-center"
          },
          // {
          //   name: "kolom_15",
          //   className: "text-center"
          // }
        ],
        language: {
          aria: {
            sortAscending: ": activate to sort column ascending",
            sortDescending: ": activate to sort column descending"
          },
          emptyTable: "Tidak ada data yang tersedia pada tabel ini",
          info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ entri",
          infoEmpty: "Menampilkan 0 sampai 0 dari 0 entri",
          infoFiltered: "(disaring dari _MAX_ entri keseluruhan)",
          lengthMenu: "Tampilkan _MENU_ entri",
          loadingRecords: "Silahkan Tunggu...",
          processing: "Sedang memproses...",
          search: "Pencarian : ",
          searchPlaceholder: "Masukan Kata Pencarian ...",
          zeroRecords: "Tidak ditemukan data yang sesuai",
          thousands: "'",
          paginate: {
            first: "Pertama",
            last: "Terakhir",
            next: "Selanjutnya",
            previous: "Sebelumnya"
          },
        },
      });

      tampilkan_tabel();
      tampilkan_tombol();
    }
  }
</script>
