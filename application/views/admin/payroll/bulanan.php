<?php
/* Generate Payslip view
*/

$session            = $this->session->userdata('username');
$user_info          = $this->Core_model->read_user_info($session['user_id']);
$role_resources_ids = $this->Core_model->user_role_resource();
$get_animate        = $this->Core_model->get_content_animate();
$system             = $this->Core_model->read_setting_info(1);
// $month_year         = $this->input->post('month_year');
$bmonth_year        = $this->input->post('bmonth_year');

if (!isset($bmonth_year)) {
    $skrg       = date('Y-m-d');
    $xin_bulan  = $this->Timesheet_model->get_xin_employees_bulan($skrg);

    if ($xin_bulan) {
        $bmonth_year  = $xin_bulan[0]->month_payroll;
        $bulan       = $xin_bulan[0]->desc;
        $start       = date("d-m-Y", strtotime($xin_bulan[0]->start_date));
        $end         = date("d-m-Y", strtotime($xin_bulan[0]->end_date));
    }
}


$attributes = array('name' => 'bulk_payment', 'id' => 'bulk_payment', 'class' => 'm-b-1 add form-hrm');
$hidden     = array('user_id' => $session['user_id']);
echo form_open('admin/payroll/add_pay_to_all_bulanan', $attributes, $hidden);
?>

<div class="row <?php echo $get_animate; ?>">
    <div class="col-md-12">
        <div class="box mb-4">
            <div class="box-header with-border">
                <h3 class="box-title"> Proses Draft Gaji Bulanan </h3>
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
                                    <label for="first_name"> Bulan Gaji </label>
                                    <select class="form-control input-sm" name="bmonth_year" id="bmonth_year" data-plugin="select_hrm" data-placeholder="<?php echo $this->lang->line('xin_select_month'); ?>" required>
                                        <?php foreach ($all_bulan_gaji as $bulan_gaji) { ?>
                                            <option value="<?php echo $bulan_gaji->month_payroll; ?>" <?php if ($bulan_gaji->month_payroll == $bmonth_year) : ?> selected="selected" <?php endif; ?>>
                                                <?php echo strtoupper($bulan_gaji->desc); ?>
                                            </option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-5">
                                <div class="form-group" style="float: left; margin-top: 22px;">
                                    <div class="form-actions">
                                        <button type="button" class="btn  btn-warning" onclick="searchDataAttendance()" title="Proses Gaji">
                                            <i class="fa fa-money"></i>
                                            Proses Draft Gaji Bulanan
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

        <span class="info_proses_gaji_bulanan"></span>

        <div class="box-tools pull-right" id="myBtn" style="display:none;">
            <?php if (in_array('1016', $role_resources_ids)) { ?>
                <button type="submit" class="btn  btn-primary save" title="Simpan Kolektif Gaji Bulanan">
                    <i class="fa fa-save"></i> Simpan Kolektif Gaji Bulanan
                </button>
            <?php } ?>
        </div>

    </div>
    <div class="box-body" id="myDIV" style="display:none;">
        <div class="box-datatable table-responsive">
            <table class="datatables-demo table table-striped table-bordered" id="xin_table_bulanan" width="310%">
                <thead>
                    <tr>
                        <th width="300px" rowspan="2" style="text-align: center !important;">
                            <center><?php echo $this->lang->line('xin_action'); ?> </center>
                        </th>
                        <th width="60px" rowspan="2" style="text-align: center !important;">
                            <center>No</center>
                        </th>
                        <th width="100px" rowspan="2" style="text-align: center !important;">
                            <center>Status<br>Gaji</center>
                        </th>
                        <th width="150px" rowspan="2" style="text-align: center !important;">
                            <center>Bulan<br>Gaji</center>
                        </th>
                        <th width="150px" rowspan="2" style="text-align: center !important;">
                            <center>Pembayaran<br>Gaji</center>
                        </th>
                        <th width="170px" rowspan="2">
                            <center><?php echo $this->lang->line('xin_employees_id'); ?></center>
                        </th>
                        <th width="450px" rowspan="2">
                            <center><?php echo $this->lang->line('xin_employee_name'); ?></center>
                        </th>
                        <th width="350px" rowspan="2">
                            <center><?php echo $this->lang->line('left_department'); ?></center>
                        </th>
                        <th width="520px" rowspan="2">
                            <center><?php echo $this->lang->line('xin_employee_designation_txt'); ?></center>
                        </th>
                        <th width="220px" rowspan="2">
                            <center>Tanggal<br>Mulai Kerja</center>
                        </th>

                        <th width="120px" rowspan="2">
                            <center>Masa<br>Kerja</center>
                        </th>

                        <th width="100px" rowspan="2">
                            <center>Status<br>Karyawan</center>
                        </th>
                        <th width="100px" rowspan="2">
                            <center>Kontrak<br>Karyawan</center>
                        </th>
                        <th width="100px" rowspan="2">
                            <center>Grade<br>Karyawan</center>
                        </th>

                        <th width="120px" colspan="8" style="background-color: #4e7ccf;color: #fff;"><?php echo $this->lang->line('xin_penambah'); ?></th>
                        <th width="120px" colspan="12" style="background-color: #cd4ecf;color: #fff;"><?php echo $this->lang->line('xin_pengurang'); ?></th>

                        <th width="120px" rowspan="2" style="background-color: #2b8a38;color: #fff;">
                            <center><?php echo $this->lang->line('xin_payroll_total_salary'); ?></center>
                        </th>

                        <th width="120px" rowspan="2" style="background-color: #cfbe4e;color: #fff;">
                            <center> No. Rekening</center>
                        </th>
                        <th width="120px" rowspan="2" style="background-color: #cfbe4e;color: #fff;">
                            <center> Bank Transfer</center>
                        </th>
                        <th width="120px" rowspan="2" style="background-color: #cfbe4e;color: #fff;">
                            <center> Email</center>
                        </th>
                    </tr>

                    <tr>
                        <th width="170px" style="background-color: #4e7ccf;color: #fff;">
                            <center><?php echo $this->lang->line('xin_payroll_salary'); ?></center>
                        </th>
                        <th width="170px" style="background-color: #4e7ccf;color: #fff;">
                            <center><?php echo $this->lang->line('xin_payroll_overtime'); ?></center>
                        </th>
                        <th width="170px" style="background-color: #4e7ccf;color: #fff;">
                            <center><?php echo $this->lang->line('xin_salary_allowance_jabatan'); ?></center>
                        </th>
                        <th width="170px" style="background-color: #4e7ccf;color: #fff;">
                            <center><?php echo $this->lang->line('xin_salary_allowance_produktifitas'); ?></center>
                        </th>
                        <th width="170px" style="background-color: #4e7ccf;color: #fff;">
                            <center><?php echo $this->lang->line('xin_salary_allowance_transport'); ?></center>
                        </th>
                        <th width="170px" style="background-color: #4e7ccf;color: #fff;">
                            <center><?php echo $this->lang->line('xin_salary_allowance_komunikasi'); ?></center>
                        </th>
                        <th width="170px" style="background-color: #4e7ccf;color: #fff;">
                            <center><?php echo $this->lang->line('xin_payroll_comission'); ?></center>
                        </th>

                        <th width="170px" style="background-color: #294e93;color: #fff;">
                            <center>Jumlah Gaji <br>(penambahan) </center>
                        </th>

                        <th width="170px" style="background-color: #cd4ecf;color: #fff;">
                            <center><?php echo $this->lang->line('xin_payroll_tax'); ?></center>
                        </th>
                        <th width="170px" style="background-color: #cd4ecf;color: #fff;">
                            <center><?php echo $this->lang->line('xin_payroll_loan'); ?> </center>
                        </th>
                        <th width="170px" style="background-color: #cd4ecf;color: #fff;">
                            <center><?php echo $this->lang->line('xin_payroll_bpjs_kes'); ?> </center>
                        </th>
                        <th width="170px" style="background-color: #cd4ecf;color: #fff;">
                            <center><?php echo $this->lang->line('xin_payroll_bpjs_tk'); ?> </center>
                        </th>
                        <th width="170px" style="background-color: #cd4ecf;color: #fff;">
                            <center>Jumlah Alpa </center>
                        </th>
                        <th width="170px" style="background-color: #cd4ecf;color: #fff;">
                            <center>Potongan Alpa </center>
                        </th>
                        <th width="170px" style="background-color: #cd4ecf;color: #fff;">
                            <center>Jumlah Izin </center>
                        </th>
                        <th width="170px" style="background-color: #cd4ecf;color: #fff;">
                            <center>Potongan Izin </center>
                        </th>
                        <th width="170px" style="background-color: #cd4ecf;color: #fff;">
                            <center>Jumlah Libur </center>
                        </th>
                        <th width="170px" style="background-color: #cd4ecf;color: #fff;">
                            <center>Potongan Libur </center>
                        </th>
                        <th width="170px" style="background-color: #cd4ecf;color: #fff;">
                            <center>Potongan Lain </center>
                        </th>
                        <th width="170px" style="background-color: #ac28ae;color: #fff;">
                            <center>Jumlah Potongan <br>(pengurangan)</center>
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
        var month_year = jQuery('#bmonth_year').val();
        var company_id = jQuery('#aj_companyx').val();

        if (company_id == '') {
            alert("Nama Perusahaan Belum Diisi !");
            $("#company").focus();

        } else if (month_year == '') {
            alert("Bulan Gaji Belum Diisi !");
            $("#month_year").focus();

        } else {
            toastr.success('Proses Gaji Bulanan Berlangsung');

            $('.info_proses_gaji_bulanan').html('<i class="fa fa-warning"></i> Silahkan Tunggu ... ');

            var xin_table3 = $('#xin_table_bulanan').dataTable({

                "bDestroy": true,
                "bSort": false,
                "aLengthMenu": [
                    [10, 30, 50, 100, -1],
                    [10, 30, 50, 100, "All"]
                ],
                autoWidth: true,
                "fixedColumns": true,
                "fixedColumns": {
                    leftColumns: 7
                },
                "ajax": {
                    url: site_url + "payroll/payslip_list_bulanan/?company_id=" + company_id + "&month_year=" + month_year,
                    type: 'GET'
                },
                "columns": [{
                        "name": "kolom_1",
                        "className": "text-center"
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
                        "className": "text-center"
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
                        "className": "text-left"
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
                        "className": "text-center"
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
                        "className": "text-right"
                    },
                    {
                        "name": "kolom_23",
                        "className": "text-right"
                    },
                    {
                        "name": "kolom_24",
                        "className": "text-right"
                    },
                    {
                        "name": "kolom_25",
                        "className": "text-right"
                    },
                    {
                        "name": "kolom_26",
                        "className": "text-right"
                    },
                    {
                        "name": "kolom_27",
                        "className": "text-right"
                    },
                    {
                        "name": "kolom_28",
                        "className": "text-right"
                    },
                    {
                        "name": "kolom_29",
                        "className": "text-right"
                    },
                    {
                        "name": "kolom_30",
                        "className": "text-right"
                    },
                    {
                        "name": "kolom_31",
                        "className": "text-right"
                    },
                    {
                        "name": "kolom_32",
                        "className": "text-right"
                    },
                    {
                        "name": "kolom_33",
                        "className": "text-right"
                    },
                    {
                        "name": "kolom_34",
                        "className": "text-right"
                    },
                    {
                        "name": "kolom_35",
                        "className": "text-right"
                    },
                    {
                        "name": "kolom_36",
                        "className": "text-center"
                    },
                    {
                        "name": "kolom_37",
                        "className": "text-center"
                    },
                    {
                        "name": "kolom_38",
                        "className": "text-left"
                    }
                ],
                "language": {
                    "aria": {
                        "sortAscending": ": activate to sort column ascending",
                        "sortDescending": ": activate to sort column descending"
                    },
                    "emptyTable": "Tidak ada data yang tersedia pada tabel ini",
                    "info": "Menampilkan _START_ sampai _END_ dari _TOTAL_ entri",
                    "infoEmpty": "Proses Sedang Berlangsung ...",
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
                "buttons": ['excel'], // colvis > if needed

                "fnDrawCallback": function(settings) {
                    $('[data-toggle="tooltip"]').tooltip();
                },

                "rowCallback": function(row, data, index) {

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
                    $(row).find('td:eq(19)').css('background-color', '#eef7fa');
                    $(row).find('td:eq(19)').css('color', 'black');
                    $(row).find('td:eq(20)').css('background-color', '#eef7fa');
                    $(row).find('td:eq(20)').css('color', 'black');

                    $(row).find('td:eq(21)').css('background-color', '#b7d8e3');
                    $(row).find('td:eq(21)').css('color', 'black');

                    $(row).find('td:eq(22)').css('background-color', '#f3eefa');
                    $(row).find('td:eq(22)').css('color', 'black');
                    $(row).find('td:eq(23)').css('background-color', '#f3eefa');
                    $(row).find('td:eq(23)').css('color', 'black');
                    $(row).find('td:eq(24)').css('background-color', '#f3eefa');
                    $(row).find('td:eq(24)').css('color', 'black');
                    $(row).find('td:eq(25)').css('background-color', '#f3eefa');
                    $(row).find('td:eq(25)').css('color', 'black');
                    $(row).find('td:eq(26)').css('background-color', '#f3eefa');
                    $(row).find('td:eq(26)').css('color', 'black');
                    $(row).find('td:eq(27)').css('background-color', '#f3eefa');
                    $(row).find('td:eq(27)').css('color', 'black');
                    $(row).find('td:eq(28)').css('background-color', '#f3eefa');
                    $(row).find('td:eq(28)').css('color', 'black');
                    $(row).find('td:eq(29)').css('background-color', '#f3eefa');
                    $(row).find('td:eq(29)').css('color', 'black');

                    $(row).find('td:eq(30)').css('background-color', '#f3eefa');
                    $(row).find('td:eq(30)').css('color', 'black');

                    $(row).find('td:eq(31)').css('background-color', '#f3eefa');
                    $(row).find('td:eq(31)').css('color', 'black');

                    $(row).find('td:eq(32)').css('background-color', '#f3eefa');
                    $(row).find('td:eq(32)').css('color', 'black');

                    $(row).find('td:eq(33)').css('background-color', '#c2aedd');
                    $(row).find('td:eq(33)').css('color', 'black');

                    // Total Potongan
                    $(row).find('td:eq(34)').css('background-color', '#ddf4e2');
                    $(row).find('td:eq(34)').css('color', 'black');
                    // THP
                    $(row).find('td:eq(35)').css('background-color', '#faf9ee');
                    $(row).find('td:eq(35)').css('color', 'black'); // iNFO
                    $(row).find('td:eq(36)').css('background-color', '#faf9ee');
                    $(row).find('td:eq(36)').css('color', 'black');
                    $(row).find('td:eq(37)').css('background-color', '#faf9ee');
                    $(row).find('td:eq(37)').css('color', 'black');
                    $(row).find('td:eq(38)').css('background-color', '#faf9ee');
                    $(row).find('td:eq(38)').css('color', 'black');

                }
            });


            $('.icon-spinner3').hide();
            $('.save').prop('disabled', false);

            tampilkan_tabel();
            tampilkan_tombol();

            $.ajax({
                type: "GET",
                url: '<?php echo base_url(); ?>admin/payroll/gaji_bulanan_jumlah/',
                data: {
                    company_id: company_id,
                    month_year: month_year

                },
                dataType: "json",
                success: function(data) {

                    for (var i = 0; i < data.val.length; i++) {

                        $(".info_proses_gaji_bulanan").html('<h3 class="box-title text-bold"> DRAFT PERINCIAN GAJI BULANAN - <span class="text-danger text-uppercase"> BULAN : ' + data.val[i].bulan_gaji + ' </span></h3> <h5><i class="fa fa-info-circle"></i> Berikut ini Draft Gaji Bulanan Periode  <b >' + data.val[i].tanggal_gaji + '</b> di <b> ' + data.val[i].company_name + '</b>. Silahkan diperiksa, jika sudah benar, silahkan lakukan proses <span class="blink blink-one hijau"> Simpan </span>.</h5>');


                    }
                }
            });


        }
    }
</script>
