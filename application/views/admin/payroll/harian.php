<?php
/* Generate Payslip view
*/
$session            = $this->session->userdata('username');
$user_info          = $this->Core_model->read_user_info($session['user_id']);
$role_resources_ids = $this->Core_model->user_role_resource();
$get_animate        = $this->Core_model->get_content_animate();
$system             = $this->Core_model->read_setting_info(1);

$start_date = $this->input->post('start_date');
$end_date   = $this->input->post('end_date');

$attributes = array('name' => 'bulk_payment', 'id' => 'bulk_payment', 'class' => 'm-b-1 add form-hrm');
$hidden     = array('user_id' => $session['user_id']);
?>

<?php echo form_open('admin/payroll/add_pay_to_all_harian', $attributes, $hidden); ?>
<div class="row <?php echo $get_animate; ?>">
    <div class="col-md-12">
        <div class="box mb-4">
            <div class="box-header with-border">
                <h3 class="box-title"> Proses Draft Gaji Harian </h3>
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
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="first_name">Lokasi Kerja</label>
                                    <select class="form-control" name="location_id" id="aj_location" data-plugin="select_hrm" data-placeholder="<?php echo $this->lang->line('left_company'); ?>">
                                        <?php foreach ($all_location as $location) { ?>
                                            <option value="<?php echo $location->location_id; ?>"> <?php echo $location->location_name; ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="first_name"> Tanggal Mulai </label>
                                    <input class="form-control attendance_date" placeholder="<?php echo $this->lang->line('xin_select_date'); ?>" readonly id="start_date" name="start_date" type="text" value="<?=$start_date?>">
                                </div>
                            </div>

                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="first_name"> Tanggal Sampai </label>
                                    <input class="form-control attendance_date" placeholder="<?php echo $this->lang->line('xin_select_date'); ?>" readonly id="end_date" name="end_date" type="text" value="<?=$end_date?>">
                                </div>
                            </div>

                            <div class="col-md-5">
                                <div class="form-group" style="float: left; margin-top: 22px;">
                                    <div class="form-actions">
                                        <button type="button" class="btn  btn-warning" onclick="searchDataAttendance()" title="Proses Gaji Harian">
                                            <i class="fa fa-money"></i>
                                            Proses Draft Gaji Harian
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

        <span class="info_proses_gaji_harian"></span>

        <!-- <h3 class="box-title text-uppercase text-bold">
      PERINCIAN GAJI HARIAN - <span class="text-danger"> Periode : </span>
      <span class="text-danger" id="p_month"><?php echo $start_date; ?> - <?php echo $end_date; ?></span>
    </h3>

    <h5>
       <i class="fa fa-warning"></i> Silahkan klik tombol "<span class="blink blink_two kuning">Proses Draft Gaji Harian</span>"
       Terlebih dahulu sebagai Draft Gaji Harian, Jika sudah Benar, silahkan Klik Tombol "<span class="blink blink_two hijau">Simpan Kolektif Gaji Harian</span>"
       .
    </h5> -->

        <div class="box-tools pull-right" id="myBtn" style="display:none;">
            <?php if (in_array('1022', $role_resources_ids)) { ?>
                <button type="submit" class="btn  btn-primary save" title="Simpan Gaji Harian">
                    <i class="fa fa-save"></i> Simpan Kolektif Gaji Harian
                </button>
            <?php } ?>
        </div>

    </div>
    <div class="box-body" id="myDIV" style="display:none;">
        <div class="box-datatable table-responsive">
            <table class="datatables-demo table table-striped table-bordered" id="xin_table_harian" width="320%">
                <thead>
                    <tr>
                        <th width="100px" rowspan="2" style="text-align: center !important;">
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
                            <center><?php echo $this->lang->line('left_department'); ?></center>
                        </th>
                        <th width="320px" rowspan="2" style="text-align: center !important;">
                            <center><?php echo $this->lang->line('xin_employee_designation_txt'); ?></center>
                        </th>
                        <th width="120px" rowspan="2" style="text-align: center !important;">
                            <center>Tanggal<br>Mulai Kerja </center>
                        </th>
                        <th width="270px" rowspan="2" style="text-align: center !important;">
                            <center>Jumlah<br>Masa Kerja</center>
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
                        <th width="170px" colspan="6" style="background-color: #4e7ccf;color: #fff;">
                            <center> Komponen Penambah</center>
                        </th>
                        <th width="170px" colspan="3" style="background-color: #cd4ecf;color: #fff;">
                            <center> Komponen Pengurangan </center>
                        </th>

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
                        <th width="930px" rowspan="2" style="background-color: #cfbe4e;color: #fff;">
                            <center> Informasi </center>
                        </th>
                    </tr>
                    <tr>

                        <!-- 14 -->
                        <th width="170px" style="background-color: #4e7ccf;color: #fff;">
                            <center> Gaji Pokok </center>
                        </th>
                        <th width="170px" style="background-color: #4e7ccf;color: #fff;">
                            <center> Jumlah<br>Hari Kerja</center>
                        </th>
                        <th width="170px" style="background-color: #4e7ccf;color: #fff;">
                            <center> Total Gaji </center>
                        </th>
                        <th width="170px" style="background-color: #4e7ccf;color: #fff;">
                            <center> Jumlah<br>Jam Lembur</center>
                        </th>
                        <th width="170px" style="background-color: #4e7ccf;color: #fff;">
                            <center> Total Lembur </center>
                        </th> <!-- 19-->
                        <th width="170px" style="background-color: #4e7ccf;color: #fff;">
                            <center> Total Insentif</center>
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
        var location_id = jQuery('#aj_location').val();

        if (company_id == '') {
            alert("Nama Perusahaan Belum Diisi !");
            $("#company").focus();

        } else if (location_id == '') {
            alert("Location belum diisi !");
            $("#start_date").focus();
        } else if (start_date == '') {
            alert("Tanggal Start Belum Diisi !");
            $("#start_date").focus();

        } else if (end_date == '') {
            alert("Tanggal Finish Belum Diisi !");
            $("#end_date").focus();

        } else {
            // var s_date = $('#start_date').datepicker({ dateFormat: 'dd-mm-yyyy' }).val();
            // var e_date = $('#end_date').datepicker({ dateFormat: 'dd-mm-yyyy' }).val();

            // $periode = s_date+' s/d '+e_date;

            // $('#p_month').html($periode);

            $('.info_proses_gaji_harian').html('<i class="fa fa-warning"></i> Silahkan Tunggu ... ');

            toastr.success('Proses Gaji Harian Berlangsung');

            var xin_table3 = $('#xin_table_harian').dataTable({

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
                    url: `${site_url}payroll/payslip_list_harian`,
                    data: {
                        company_id,
                        location_id,
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
                        "name": "kolom_15",
                        "className": "text-center"
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
                        "className": "text-right"
                    },
                    {
                        "name": "kolom_23",
                        "className": "text-right"
                    },
                    {
                        "name": "kolom_24",
                        "className": "text-center"
                    },
                    {
                        "name": "kolom_25",
                        "className": "text-center"
                    },
                    {
                        "name": "kolom_26",
                        "className": "text-left"
                    },
                    {
                        "name": "kolom_27",
                        "className": "text-left"
                    }
                ],
                "columnDefs": [
                    {
                        "targets": [13, 15, 17, 18, 19, 20, 21, 22],
                        "render": function (data, type, row) {
                            return type === "export" ? data.replace(/[$.,]/g, "") : data;
                        }
                    }
                ],
                "language": {
                    "aria": {
                        "sortAscending": ": activate to sort column ascending",
                        "sortDescending": ": activate to sort column descending"
                    },
                    "emptyTable": "Tidak ada data yang tersedia pada tabel ini",
                    "info": "Menampilkan _START_ sampai _END_ dari _TOTAL_ entri",
                    "infoEmpty": "Proses Draft Gaji Harian Sedang Berlangsung ...",
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
                "buttons": [
                    {
                        extend: 'excelHtml5',
                        exportOptions: { orthogonal: 'export' }
                    },
                ], // colvis > if needed

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

                    $(row).find('td:eq(18)').css('background-color', '#eef7fa');
                    $(row).find('td:eq(18)').css('color', 'black');

                    $(row).find('td:eq(19)').css('background-color', '#faeef4');
                    $(row).find('td:eq(19)').css('color', 'black');
                    $(row).find('td:eq(20)').css('background-color', '#faeef4');
                    $(row).find('td:eq(20)').css('color', 'black');
                    $(row).find('td:eq(21)').css('background-color', '#faeef4');
                    $(row).find('td:eq(21)').css('color', 'black');

                    $(row).find('td:eq(22)').css('background-color', '#ddf4e2');
                    $(row).find('td:eq(22)').css('color', 'black');

                    $(row).find('td:eq(23)').css('background-color', '#faf9ee');
                    $(row).find('td:eq(23)').css('color', 'black');
                    $(row).find('td:eq(24)').css('background-color', '#faf9ee');
                    $(row).find('td:eq(24)').css('color', 'black');
                    $(row).find('td:eq(25)').css('background-color', '#faf9ee');
                    $(row).find('td:eq(25)').css('color', 'black');

                    $(row).find('td:eq(26)').css('background-color', '#e2e0c9');
                    $(row).find('td:eq(26)').css('color', 'black');
                }
            });

            $.ajax({
                type: "GET",
                url: '<?php echo base_url(); ?>admin/payroll/gaji_harian_jumlah/',
                data: {
                    company_id: company_id,
                    location_id : location_id,
                    start_date: start_date,
                    end_date: end_date
                },
                dataType: "json",
                success: function(data) {

                    for (var i = 0; i < data.val.length; i++) {
                        $(".info_proses_gaji_harian").html('<h3 class="box-title text-bold"> DRAFT PERINCIAN GAJI HARIAN - <span class="text-danger text-uppercase"> PERIODE : ' + data.val[i].periode_gaji + ' </span></h3> <h5><i class="fa fa-info-circle"></i> Berikut ini Draft Gaji Harian di <b> ' + data.val[i].company_name + '</b>. Silahkan diperiksa, jika sudah benar, silahkan lakukan proses <span class="blink blink-one hijau"> Simpan </span>.</h5>');
                    }
                }
            });

            tampilkan_tabel();
            tampilkan_tombol();

        }
    }
</script>
