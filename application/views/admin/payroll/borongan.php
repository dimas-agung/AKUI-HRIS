<?php
/* Generate Payslip view
*/
?>
<?php $session            = $this->session->userdata('username'); ?>
<?php $user_info          = $this->Core_model->read_user_info($session['user_id']); ?>
<?php $role_resources_ids = $this->Core_model->user_role_resource(); ?>
<?php $get_animate        = $this->Core_model->get_content_animate(); ?>
<?php $system             = $this->Core_model->read_setting_info(1); ?>

<?php $start_date         = $this->input->post('start_date'); ?>
<?php $end_date           = $this->input->post('end_date'); ?>


<?php $attributes = array('name' => 'bulk_payment', 'id' => 'bulk_payment', 'class' => 'm-b-1 add form-hrm'); ?>
<?php $hidden     = array('user_id' => $session['user_id']); ?>
<?php echo form_open('admin/payroll/add_pay_to_all_borongan', $attributes, $hidden); ?>

<div class="row <?php echo $get_animate; ?>">
  <div class="col-md-12">
    <div class="box mb-4">
      <div class="box-header with-border">
        <h3 class="box-title"> Proses Gaji Borongan </h3>
      </div>
      <div class="box-body">
        <div class="row">
          <div class="col-md-12">
            <div class="row">
              <div class="col-md-3">
                <div class="form-group">
                  <label for="first_name"><?php echo $this->lang->line('left_company'); ?></label>
                  <select class="form-control" name="company_id" id="aj_companyx" data-plugin="select_hrm" data-placeholder="<?php echo $this->lang->line('left_company'); ?>">
                    <option value=""></option>
                    <?php foreach ($all_companies as $company) { ?>
                      <option value="<?php echo $company->company_id; ?>"> <?php echo $company->name; ?></option>
                    <?php } ?>
                  </select>
                </div>
              </div>

              <div class="col-md-2">
                <div class="form-group" id="workstation_ajax">
                  <label for="name"><?php echo $this->lang->line('xin_workstation_select'); ?></label>
                  <select disabled="disabled" class="select2" data-plugin="select_hrm" data-placeholder="<?php echo $this->lang->line('xin_select_workstation'); ?>" name="workstation_id">
                    <option value=""></option>
                  </select>
                </div>
              </div>

              <div class="col-md-2">
                <div class="form-group">
                  <label for="first_name"> Tanggal Mulai </label>
                  <input class="form-control attendance_date" placeholder="<?php echo $this->lang->line('xin_select_date'); ?>" readonly id="start_date" name="start_date" type="text" value="<?php echo date('Y-m-01'); ?>">
                </div>
              </div>

              <div class="col-md-2">
                <div class="form-group">
                  <label for="first_name"> Tanggal Sampai </label>
                  <input class="form-control attendance_date" placeholder="<?php echo $this->lang->line('xin_select_date'); ?>" readonly id="end_date" name="end_date" type="text" value="<?php echo date('Y-m-d'); ?>">
                </div>
              </div>

              <div class="col-md-2">
                <div class="form-group" style="float: left; margin-top: 22px;">
                  <div class="form-actions">
                    <button type="button" class="btn  btn-warning" onclick="searchDataAttendance()" title="Proses Gaji borongan">
                      <i class="fa fa-money"></i>
                      Proses Draft Gaji Borongan
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
      PERINCIAN GAJI borongan - <span class="text-danger"> Periode : </span>
      <span class="text-danger" id="p_month"><?php echo $start_date; ?> - <?php echo $end_date; ?></span>
    </h3>

    <h5>
      <i class="fa fa-warning"></i> Silahkan klik tombol "<span class="blink blink_two kuning">Proses Draft Gaji Borongan</span>"
      Terlebih dahulu sebagai Draft Gaji Borongan, Jika sudah Benar, silahkan Klik Tombol "<span class="blink blink_two hijau">Simpan Kolektif Gaji Borongan</span>"
      .
    </h5>

    <div class="box-tools pull-right" id="myBtn" style="display:none;">
      <?php if (in_array('1032', $role_resources_ids)) { ?>
        <button type="submit" class="btn  btn-primary save" title="Simpan Gaji">
          <i class="fa fa-save"></i> Simpan Kolektif Gaji Borongan
        </button>
      <?php } ?>
    </div>

  </div>
  <div class="box-body" id="myDIV" style="display:none;">
    <div class="box-datatable table-responsive">
      <table class="datatables-demo table table-striped table-bordered" id="xin_table_borongan" width="270%">
        <thead>
          <tr>
            <!-- 1 -->
            <th width="300px" rowspan="2" style="text-align: center !important;">
              <center><?php echo $this->lang->line('xin_action'); ?></center>
            </th>
            <th width="60px" rowspan="2" style="text-align: center !important;">
              <center>No</center>
            </th>
            <th width="100px" rowspan="2" style="text-align: center !important;">
              <center>Status<br>Gaji</center>
            </th>
            <th width="380px" rowspan="2" style="text-align: center !important;">
              <center>Periode<br>Tanggal Gaji</center>
            </th>
            <th width="200px" rowspan="2" style="text-align: center !important;">
              <center><?php echo $this->lang->line('xin_employees_id'); ?></center>
            </th>
            <th width="450px" rowspan="2" style="text-align: center !important;">
              <center><?php echo $this->lang->line('xin_employee_name'); ?></center>
            </th>
            <th width="350px" rowspan="2" style="text-align: center !important;">
              <center><?php echo $this->lang->line('left_workstation'); ?></center>
            </th>
            <th width="320px" rowspan="2" style="text-align: center !important;">
              <center><?php echo $this->lang->line('xin_employee_designation_txt'); ?></center>
            </th>
            <th width="120px" rowspan="2" style="text-align: center !important;">
              <center>Tanggal<br>Mulai Kerja </center>
            </th>
            <th width="120px" rowspan="2" style="text-align: center !important;">
              <center>Masa<br>Kerja</center>
            </th>
            <th width="100px" rowspan="2" style="text-align: center !important;">
              <center><?php echo $this->lang->line('xin_employee_status'); ?></center>
            </th>
            <th width="100px" rowspan="2" style="text-align: center !important;">
              <center><?php echo $this->lang->line('xin_employee_contrack'); ?></center>
            </th>
            <th width="100px" rowspan="2" style="text-align: center !important;">
              <center><?php echo $this->lang->line('xin_employee_grade'); ?></center>
            </th>
            <!-- 14 -->
            <th width="170px" colspan="5" style="background-color: #4e7ccf;color: #fff;">
              <center> Komponen Penambah</center>
            </th>
            <th width="170px" colspan="3" style="background-color: #cd4ecf;color: #fff;">
              <center> Komponen Pengurang</center>
            </th>
            <!-- 17 -->
            <th width="120px" rowspan="2" style="background-color: #2b8a38;color: #fff;">
              <center><?php echo $this->lang->line('xin_payroll_total_salary'); ?></center>
            </th>
            <th width="120px" rowspan="2" style="background-color: #cfbe4e;color: #fff;">
              <center> No. Rekening </center>
            </th>
            <th width="120px" rowspan="2" style="background-color: #cfbe4e;color: #fff;">
              <center> Bank Transfer </center>
            </th>
            <th width="120px" rowspan="2" style="background-color: #cfbe4e;color: #fff;">
              <center> Email </center>
            </th>
            <th width="520px" rowspan="2" style="background-color: #cfbe4e;color: #fff;">
              <center> Informasi </center>
            </th>
            <!--  -->
          </tr>
          <tr>

            <th width="170px" style="background-color: #4e7ccf;color: #fff;">
              <center> Jumlah<br>Hari Kerja</center>
            </th>
            <th width="170px" style="background-color: #4e7ccf;color: #fff;">
              <center> Total<br>Gram</center>
            </th>
            <th width="170px" style="background-color: #4e7ccf;color: #fff;">
              <center> Total<br>Gaji</center>
            </th>
            <th width="170px" style="background-color: #4e7ccf;color: #fff;">
              <center> Total<br>Tambahan </center>
            </th>
            <th width="170px" style="background-color: #4e7ccf;color: #fff;">
              <center> Total<br>Diperbantukan </center>
            </th>

            <th width="170px" style="background-color: #cd4ecf;color: #fff;">
              <center> BPJS Kes </center>
            </th>
            <th width="170px" style="background-color: #cd4ecf;color: #fff;">
              <center> BPJS TK</center>
            </th>
            <th width="170px" style="background-color: #cd4ecf;color: #fff;">
              <center> Potongan Lain </center>
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

  function searchDataAttendance() {

    var start_date = jQuery('#start_date').val();
    var end_date = jQuery('#end_date').val();

    var company_id = jQuery('#aj_companyx').val();
    var workstation_id = jQuery('#workstation_id').val();



    if (company_id == '') {
      alert("Nama Perusahaan Belum Diisi !");
      $("#company").focus();

    } else if (workstation_id == '') {
      alert("Workstation Belum Diisi !");
      $("#workstation_id").focus();

    } else if (start_date == '') {
      alert("Tanggal Start Belum Diisi !");
      $("#start_date").focus();

    } else if (end_date == '') {
      alert("Tanggal Finish Belum Diisi !");
      $("#end_date").focus();

    } else {
      var s_date = $('#start_date').datepicker({
        dateFormat: 'dd-mm-yyyy'
      }).val();
      var e_date = $('#end_date').datepicker({
        dateFormat: 'dd-mm-yyyy'
      }).val();

      $periode = s_date + ' s/d ' + e_date;

      $('#p_month').html($periode);

      toastr.success('Proses Gaji Borongan Berlangsung');

      var xin_table3 = $('#xin_table_borongan').dataTable({

        "bDestroy": true,
        "bSort": false,
        "aLengthMenu": [
          [10, 30, 50, 100, -1],
          [10, 30, 50, 100, "All"]
        ],
        autoWidth: true,
        "fixedColumns": true,
        "fixedColumns": {
          leftColumns: 6
        },
        "ajax": {
          url: `${site_url}payroll/payslip_list_borongan`,
          data: {
            company_id,
            workstation_id,
            start_date,
            end_date
          },
          type: 'GET'
        },
        "columns": [{
            "name": "kolom_1",
            "className": "text-center",
            "width": "5%"
          },
          {
            "name": "kolom_2",
            "className": "text-center"
          },
          {
            "name": "kolom_3",
            "className": "text-center"
          },
          {
            "name": "kolom_4",
            "className": "text-center"
          },
          {
            "name": "kolom_5",
            "className": "text-center"
          },
          {
            "name": "kolom_6",
            "className": "text-left"
          },
          {
            "name": "kolom_7",
            "className": "text-left"
          },
          {
            "name": "kolom_8",
            "className": "text-left"
          },
          {
            "name": "kolom_9",
            "className": "text-center"
          },
          {
            "name": "kolom_10",
            "className": "text-center"
          },
          {
            "name": "kolom_11",
            "className": "text-center"
          },
          {
            "name": "kolom_12",
            "className": "text-center"
          },
          {
            "name": "kolom_13",
            "className": "text-center"
          },
          {
            "name": "kolom_14",
            "className": "text-right"
          },
          {
            "name": "kolom_14",
            "className": "text-right"
          },
          {
            "name": "kolom_15",
            "className": "text-right"
          },
          {
            "name": "kolom_16",
            "className": "text-right"
          },
          {
            "name": "kolom_17",
            "className": "text-right"
          },
          {
            "name": "kolom_18",
            "className": "text-right"
          },
          {
            "name": "kolom_19",
            "className": "text-right"
          },
          {
            "name": "kolom_20",
            "className": "text-right"
          },
          {
            "name": "kolom_21",
            "className": "text-right"
          },
          {
            "name": "kolom_22",
            "className": "text-center"
          },
          {
            "name": "kolom_23",
            "className": "text-center"
          },
          {
            "name": "kolom_24",
            "className": "text-left"
          },
          {
            "name": "kolom_25",
            "className": "text-left"
          }

        ],
        "columnDefs": [{
          "targets": [13, 14, 15, 16, 17, 18, 19, 20, 21],
          "render": function(data, type, row) {
            return type === "export" ? data.replace(/[$.,]/g, "") : data;
          }
        }],
        "language": {
          "aria": {
            "sortAscending": ": activate to sort column ascending",
            "sortDescending": ": activate to sort column descending"
          },
          "emptyTable": "Tidak ada data yang tersedia pada tabel ini",
          "info": "Menampilkan _START_ sampai _END_ dari _TOTAL_ entri",
          "infoEmpty": "Proses Draft Gaji Borongan Sedang Berlangsung ...",
          "infoFiltered": "(disaring dari _MAX_ entri keseluruhan)",
          "lengthMenu": "Tampilkan _MENU_ entri",
          "loadingRecords": "Silahkan Tunggu...",
          "processing": "Sedang memproses...",
          "search": "Pencarian : ",
          "searchPlaceholder": "Masukan Kata Pencarian ...",
          "zeroRecords": "Tidak ditemukan data yang sesuai",
          "thousands": "'",
          "paginate": {
            "first": "Pertama",
            "last": "Terakhir",
            "next": "Selanjutnya",
            "previous": "Sebelumnya"
          },
        },
        dom: 'lBfrtip',
        "buttons": [{
          extend: 'excelHtml5',
          exportOptions: {
            orthogonal: 'export'
          }
        }, ], // colvis > if needed

        "fnDrawCallback": function(settings) {
          $('[data-toggle="tooltip"]').tooltip();
        },

        "rowCallback": function(row, data, index) {

          $(row).find('td:eq(13)').css('background-color', '#eef7fa');
          $(row).find('td:eq(13)').css('color', 'black');
          $(row).find('td:eq(14)').css('background-color', '#eef7fa');
          $(row).find('td:eq(14)').css('color', 'black');
          $(row).find('td:eq(15)').css('background-color', '#eef7fa');
          $(row).find('td:eq(15)').css('color', 'black');
          $(row).find('td:eq(16)').css('background-color', '#eef7fa');
          $(row).find('td:eq(16)').css('color', 'black');
          $(row).find('td:eq(17)').css('background-color', '#eef7fa');
          $(row).find('td:eq(17)').css('color', 'black');

          $(row).find('td:eq(18)').css('background-color', '#faeef8');
          $(row).find('td:eq(18)').css('color', 'black');
          $(row).find('td:eq(19)').css('background-color', '#faeef8');
          $(row).find('td:eq(19)').css('color', 'black');
          $(row).find('td:eq(20)').css('background-color', '#faeef8');
          $(row).find('td:eq(20)').css('color', 'black');

          $(row).find('td:eq(21)').css('background-color', '#eefaf1');
          $(row).find('td:eq(21)').css('color', 'black');

          $(row).find('td:eq(22)').css('background-color', '#faf9ee');
          $(row).find('td:eq(22)').css('color', 'black');
          $(row).find('td:eq(23)').css('background-color', '#faf9ee');
          $(row).find('td:eq(23)').css('color', 'black');
          $(row).find('td:eq(24)').css('background-color', '#faf9ee');
          $(row).find('td:eq(24)').css('color', 'black');

          $(row).find('td:eq(25)').css('background-color', '#faf9ee');
          $(row).find('td:eq(25)').css('color', 'black');
        }
      });

      tampilkan_tabel();
      tampilkan_tombol();

    }
  }
</script>
