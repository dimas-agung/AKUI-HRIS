$(document).ready(function() {
    var tahun_thr   = jQuery('#tahun_thr').val();
    var company_id  = jQuery('#aj_companyx').val();
    var tanggal_thr = jQuery('#tanggal_thr').val();

    $('#p_month').html(tahun_thr+', Tanggal Batas THR : '+tanggal_thr);
    $('[data-plugin="select_hrm"]').select2($(this).attr('data-options'));
    $('[data-plugin="select_hrm"]').select2({ width:'100%' });

    $('.del_monthly_pay').on('show.bs.modal', function (event) {
        var button       = $(event.relatedTarget);
        var payslip_id   = button.data('payslip_id');
        var tahun_thr    = $('#tahun_thr').val();
        var company_id   = button.data('company_id');
        var modal        = $(this);

        $.ajax({
            url: site_url+'thr/pay_thr_del/',
            type: "GET",
            data: 'jd=1&is_ajax=11&data=payment&type=monthly_payment_delete&payslip_id='+payslip_id+'&tahun_thr='+tahun_thr+'&tanggal_thr='+tanggal_thr+'&company_id='+company_id,
            success: function (response) {
                if(response) {
                    $("#del_monthly_pay_aj").html(response);
                }
            }
        });
    });

    $('.thr_date').datepicker({
        changeMonth: true,
        changeYear: true,
        // maxDate: '0',
        dateFormat:'yy-mm-dd',
        altField: "#date_format",
        altFormat: js_date_format,
        yearRange: '1970:' + new Date().getFullYear(),
        beforeShow: function(input) {
            $(input).datepicker("widget").show();
        }
    });

    $('.emo_monthly_pay').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget);
        var employee_id = button.data('employee_id');
        var payment_date = $('#tahun_thr').val();
        var company_id = button.data('company_id');
            var tanggal_thr = $('#tanggal_thr').val();
        var modal = $(this);
        $.ajax({
            url: site_url+'thr/pay_salary/',
            type: "GET",
            data: 'jd=1&is_ajax=11&data=payment&type=monthly_payment&employee_id='+employee_id+'&tahun_thr='+tahun_thr+'&tanggal_thr='+tanggal_thr+'&company_id='+company_id,
            success: function (response) {
                if(response) {
                    $("#emo_monthly_pay_aj").html(response);
                }
            }
        });
    });

    $("#delete_record").submit(function(e){
        /*Form Submit*/
        e.preventDefault();
        var obj = $(this), action = obj.attr('name');

        $.ajax({
            type: "POST",
            url: e.target.action,
            data: obj.serialize()+"&is_ajax=2&form="+action,
            cache: false,
            success: function (JSON) {
                if (JSON.error != '') {
                    alert_fail('Gagal',JSON.error);
                } else {
                    $('.delete-modal').modal('toggle');

                    // toastr.success('Proses Simpan Gaji Bulanan Berlangsung');

                    xin_table3.api().ajax.reload(function(){
                            alert_success('Sukses',JSON.result);
                    }, true);
                }
            }
        });
    });

    $('.payroll_template_modal').on('show.bs.modal', function (event) {
        var button      = $(event.relatedTarget);
        var employee_id = button.data('employee_id');
        var modal       = $(this);
        $.ajax({
            url: site_url+'thr/payroll_template_read/',
            type: "GET",
            data: 'jd=1&is_ajax=11&mode=not_paid&data=payroll_template&type=payroll_template&employee_id='+employee_id,
            success: function (response) {
                if(response) {
                    $("#ajax_modal_payroll").html(response);
                }
            }
        });
    });

    $("#employee_update_salary").submit(function(e){

        e.preventDefault();
        var obj = $(this), action = obj.attr('name');
        $('.save').prop('disabled', true);
        $('.icon-spinner3').show();

        $.ajax({
            type: "POST",
            url: e.target.action,
            data: obj.serialize()+"&is_ajax=3&data=employee_update_salary&type=employee_update_salary&form="+action,
            cache: false,
            success: function (JSON) {
                if (JSON.error != '') {

                    alert_fail('Gagal',JSON.error);
                    $('input[name="csrf_hris"]').val(JSON.csrf_hash);
                    $('.icon-spinner3').hide();
                    $('.save').prop('disabled', false);
                } else {

                    alert_success('Sukses',JSON.result);
                    $('input[name="csrf_hris"]').val(JSON.csrf_hash);
                    $('.icon-spinner3').hide();
                    $('.save').prop('disabled', false);
                }
            }
        });
    });

    // bulk payments
    $("#bulk_payment").submit(function(e){
        e.preventDefault();
        var obj = $(this), action = obj.attr('name');
        $('.save').prop('disabled', true);
        $('.icon-spinner3').show();

        var company_id  = jQuery('#aj_companyx').val();
        var tahun_thr   = jQuery('#tahun_thr').val();
        var tanggal_thr   = jQuery('#tanggal_thr').val();

        $.ajax({
            type: "POST",
            url: e.target.action,
            data: obj.serialize()+"&is_ajax=1&add_type=payroll&form="+action,
            cache: false,
            success: function (JSON) {
                if (JSON.error != '') {

                    alert_fail('Gagal',JSON.error);
                    $('.save').prop('disabled', false);
                    $('.icon-spinner3').hide();

                } else {

                    var xin_table3   = $('#xin_table_thr').dataTable({

                        "bDestroy"        : true,
                        "bSort"           : false,
                        "aLengthMenu"     : [[10, 30, 50, 100, -1], [10, 30, 50, 100, "All"]],
                        autoWidth         : true,
                        "fixedColumns"    : true,
                        "fixedColumns"    : {
                            leftColumns   : 7
                        },
                        "ajax": {
                            url : site_url+"thr/thr_list_bulanan/?company_id="+company_id+"&tahun_thr="+tahun_thr+"&tanggal_thr="+tanggal_thr,
                            type : 'GET'
                        },
                        "columns": [
                               {"name": "kolom_1",  "className": "text-center","width": "5%"},
                              {"name": "kolom_2",  "className": "text-center"},
                              {"name": "kolom_3",  "className": "text-center"},
                              {"name": "kolom_4",  "className": "text-center"},
                              {"name": "kolom_5",  "className": "text-center"},
                              {"name": "kolom_6",  "className": "text-center"},
                              {"name": "kolom_7",  "className": "text-left"},
                              {"name": "kolom_8",  "className": "text-left"},
                              {"name": "kolom_9",  "className": "text-left"},
                              {"name": "kolom_10",  "className": "text-center"},
                              {"name": "kolom_11", "className": "text-center"},
                              {"name": "kolom_12", "className": "text-center"},
                              {"name": "kolom_13", "className": "text-center"},
                              {"name": "kolom_14", "className": "text-center"},
                              {"name": "kolom_15", "className": "text-right"},
                              {"name": "kolom_16", "className": "text-right"},
                              {"name": "kolom_17", "className": "text-right"},
                              {"name": "kolom_18", "className": "text-right"},
                              {"name": "kolom_19", "className": "text-center"},
                              {"name": "kolom_20", "className": "text-center"},
                              {"name": "kolom_21", "className": "text-left"}
                          ],
                       "language": {
                            "aria": {
                                "sortAscending" : ": activate to sort column ascending",
                                "sortDescending": ": activate to sort column descending"
                            },
                            "emptyTable": "Tidak ada data yang tersedia pada tabel ini",
                            "info": "Menampilkan _START_ sampai _END_ dari _TOTAL_ entri",
                            "infoEmpty": "Menampilkan 0 sampai 0 dari 0 entri",
                            "infoFiltered": "(disaring dari _MAX_ entri keseluruhan)",
                            "lengthMenu": "Tampilkan _MENU_ entri",
                            "loadingRecords": "Silahkan Tunggu...",
                            "processing": "Sedang memproses...",
                             "search"      : "Pencarian : ", "searchPlaceholder": "Masukan Kata Pencarian ...",
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

                        "fnDrawCallback": function(settings){
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
                          $(row).find('td:eq(18)').css('background-color', '#faf9ee');
                          $(row).find('td:eq(18)').css('color', 'black');
                          $(row).find('td:eq(19)').css('background-color', '#faf9ee');
                          $(row).find('td:eq(19)').css('color', 'black');
                          $(row).find('td:eq(20)').css('background-color', '#faf9ee');
                          $(row).find('td:eq(20)').css('color', 'black');


                        }
                    });

                    // toastr.success('Proses Simpan Gaji Bulanan Berlangsung');

                    xin_table3.api().ajax.reload(function(){
                            alert_success('Sukses',JSON.result);
                    }, true);

                    $('.icon-spinner3').hide();
                    $('.save').prop('disabled', false);
                }
            }
        });
    });

});

$( document ).on( "click", ".delete", function() {
    $('input[name=_token]').val($(this).data('record-id'));
    $('#delete_record').attr('action',base_url+'/payslip_delete_bulanan/'+$(this).data('record-id'))+'/';
});
