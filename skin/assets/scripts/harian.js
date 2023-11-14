$(document).ready(function () {
  var start_date = jQuery("#start_date").val();
  var end_date = jQuery("#end_date").val();

  var company_id = jQuery("#aj_companyx").val();

  $(".info_proses_gaji_harian").html(
    '<h5><i class="fa fa-warning"></i> Silahkan klik tombol "<span class="blink blink_two kuning">ProsesDraft Gaji Harian</span>" Terlebih dahulu sebagai Draft Gaji Harian.</h5>'
  );

  $('[data-plugin="select_hrm"]').select2($(this).attr("data-options"));
  $('[data-plugin="select_hrm"]').select2({ width: "100%" });

  $(".del_dayly_pay").on("show.bs.modal", function (event) {
    var button = $(event.relatedTarget);

    var payslip_id = button.data("payslip_id");
    var start_date = $("#start_date").val();
    var end_date = $("#end_date").val();
    var company_id = button.data("company_id");

    var modal = $(this);
    $.ajax({
      url: site_url + "payroll/pay_salary_dayly_del/",
      type: "GET",
      data:
        "jd=1&is_ajax=11&data=payment&type=dayly_payment_delete&payslip_id=" +
        payslip_id +
        "&start_date=" +
        start_date +
        "&end_date=" +
        end_date +
        "&company_id=" +
        company_id,
      success: function (response) {
        if (response) {
          $("#del_dayly_pay_aj").html(response);
        }
      },
    });
  });

  $(".emo_dayly_pay").on("show.bs.modal", function (event) {
    var button = $(event.relatedTarget);
    var employee_id = button.data("employee_id");

    var start_date = $("#start_date").val();
    var end_date = $("#end_date").val();

    var company_id = button.data("company_id");
    var modal = $(this);
    $.ajax({
      url: site_url + "payroll/pay_salary_dayly/",
      type: "GET",
      data:
        "jd=1&is_ajax=11&data=payment&type=dayly_payment&employee_id=" +
        employee_id +
        "&start_date=" +
        start_date +
        "&end_date=" +
        end_date +
        "&company_id=" +
        company_id,
      success: function (response) {
        if (response) {
          $("#emo_dayly_pay_aj").html(response);
        }
      },
    });
  });

  $(".attendance_date").datepicker({
    changeMonth: true,
    changeYear: true,
    // maxDate: '0',
    dateFormat: "yy-mm-dd",
    altField: "#date_format",
    altFormat: js_date_format,
    yearRange: "1970:" + new Date().getFullYear(),
    beforeShow: function (input) {
      $(input).datepicker("widget").show();
    },
  });

  $("#delete_record").submit(function (e) {
    /*Form Submit*/
    e.preventDefault();
    var obj = $(this),
      action = obj.attr("name");

    $.ajax({
      type: "POST",
      url: e.target.action,
      data: obj.serialize() + "&is_ajax=2&form=" + action,
      cache: false,
      success: function (JSON) {
        if (JSON.error != "") {
          alert_fail("Gagal", JSON.error);
        } else {
          $(".delete-modal").modal("toggle");

          // toastr.success('Proses Simpan Gaji Bulanan Berlangsung');

          xin_table3.api().ajax.reload(function () {
            alert_success("Sukses", JSON.result);
          }, true);
        }
      },
    });
  });

  $(".payroll_template_modal_harian").on("show.bs.modal", function (event) {
    var button = $(event.relatedTarget);
    var employee_id = button.data("employee_id");
    var modal = $(this);
    $.ajax({
      url: site_url + "payroll/payroll_template_read_harian/",
      type: "GET",
      data:
        "jd=1&is_ajax=11&mode=not_paid&data=payroll_template&type=payroll_template&employee_id=" +
        employee_id,
      success: function (response) {
        if (response) {
          $("#ajax_modal_payroll").html(response);
        }
      },
    });
  });
  $("#employee_update_salary").submit(function (e) {
    e.preventDefault();
    var obj = $(this),
      action = obj.attr("name");
    $(".save").prop("disabled", true);
    $(".icon-spinner3").show();

    $.ajax({
      type: "POST",
      url: e.target.action,
      data:
        obj.serialize() +
        "&is_ajax=3&data=employee_update_salary&type=employee_update_salary&form=" +
        action,
      cache: false,
      success: function (JSON) {
        if (JSON.error != "") {
          alert_fail("Gagal", JSON.error);
          $('input[name="csrf_hris"]').val(JSON.csrf_hash);
          $(".icon-spinner3").hide();
          $(".save").prop("disabled", false);
        } else {
          alert_success("Sukses", JSON.result);
          $('input[name="csrf_hris"]').val(JSON.csrf_hash);
          $(".icon-spinner3").hide();
          $(".save").prop("disabled", false);
        }
      },
    });
  });

  // bulk payments
  $("#bulk_payment").submit(function (e) {
    e.preventDefault();
    var obj = $(this),
      action = obj.attr("name");
    $(".save").prop("disabled", true);
    $(".icon-spinner3").show();

    var start_date = jQuery("#start_date").val();
    var end_date = jQuery("#end_date").val();

    var company_id = jQuery("#aj_companyx").val();
    $.ajax({
      type: "POST",
      url: e.target.action,
      data: obj.serialize() + "&is_ajax=1&add_type=payroll&form=" + action,
      cache: false,
      success: function (JSON) {
        if (JSON.error != "") {
          alert_fail("Gagal", JSON.error);
          $(".save").prop("disabled", false);
          $(".icon-spinner3").hide();
        } else {
          var xin_table3 = $("#xin_table_harian").dataTable({
            bDestroy: true,
            bSort: false,
            aLengthMenu: [
              [7, 10, 30, 50, 100, -1],
              [7, 10, 30, 50, 100, "All"],
            ],
            autoWidth: true,
            fixedColumns: true,
            fixedColumns: {
              leftColumns: 6,
            },
            ajax: {
              url: `${site_url}payroll/payslip_list_harian`,
              data: {
                company_id,
                start_date,
                end_date,
              },
              type: "GET",
            },
            columns: [
              { name: "kolom_1", className: "text-center", width: "5%" },
              { name: "kolom_2", className: "text-center" },
              { name: "kolom_3", className: "text-center" },
              { name: "kolom_4", className: "text-center" },
              { name: "kolom_5", className: "text-center" },
              { name: "kolom_6", className: "text-left" },
              { name: "kolom_7", className: "text-left" },
              { name: "kolom_8", className: "text-left" },
              { name: "kolom_9", className: "text-center" },
              { name: "kolom_10", className: "text-center" },
              { name: "kolom_11", className: "text-center" },
              { name: "kolom_12", className: "text-center" },
              { name: "kolom_13", className: "text-center" },
              { name: "kolom_14", className: "text-right" },
              { name: "kolom_15", className: "text-center" },
              { name: "kolom_16", className: "text-right" },
              { name: "kolom_17", className: "text-right" },
              { name: "kolom_18", className: "text-right" },
              { name: "kolom_19", className: "text-right" },
              { name: "kolom_20", className: "text-right" },
              { name: "kolom_21", className: "text-right" },
              { name: "kolom_22", className: "text-right" },
              { name: "kolom_23", className: "text-right" },
              { name: "kolom_24", className: "text-center" },
              { name: "kolom_25", className: "text-center" },
              { name: "kolom_26", className: "text-left" },
              { name: "kolom_27", className: "text-left" },
            ],
            columnDefs: [
              {
                targets: [13, 15, 17, 18, 19, 20, 21, 22],
                render: function (data, type, row) {
                  return type === "export" ? data.replace(/[$.,]/g, "") : data;
                },
              },
            ],
            language: {
              aria: {
                sortAscending: ": activate to sort column ascending",
                sortDescending: ": activate to sort column descending",
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
                previous: "Sebelumnya",
              },
            },
            dom: "lBfrtip",
            buttons: [
              {
                extend: "excelHtml5",
                exportOptions: { orthogonal: "export" },
              },
            ], // colvis > if needed

            fnDrawCallback: function (settings) {
              $('[data-toggle="tooltip"]').tooltip();
            },

            rowCallback: function (row, data, index) {
              $(row).find("td:eq(13)").css("background-color", "#eef7fa");
              $(row).find("td:eq(13)").css("color", "black");
              $(row).find("td:eq(14)").css("background-color", "#eef7fa");
              $(row).find("td:eq(14)").css("color", "black");
              $(row).find("td:eq(15)").css("background-color", "#eef7fa");
              $(row).find("td:eq(15)").css("color", "black");

              $(row).find("td:eq(16)").css("background-color", "#eef7fa");
              $(row).find("td:eq(16)").css("color", "black");
              $(row).find("td:eq(17)").css("background-color", "#eef7fa");
              $(row).find("td:eq(17)").css("color", "black");

              $(row).find("td:eq(18)").css("background-color", "#eef7fa");
              $(row).find("td:eq(18)").css("color", "black");

              $(row).find("td:eq(19)").css("background-color", "#faeef4");
              $(row).find("td:eq(19)").css("color", "black");
              $(row).find("td:eq(20)").css("background-color", "#faeef4");
              $(row).find("td:eq(20)").css("color", "black");
              $(row).find("td:eq(21)").css("background-color", "#faeef4");
              $(row).find("td:eq(21)").css("color", "black");

              $(row).find("td:eq(22)").css("background-color", "#ddf4e2");
              $(row).find("td:eq(22)").css("color", "black");

              $(row).find("td:eq(23)").css("background-color", "#faf9ee");
              $(row).find("td:eq(23)").css("color", "black");
              $(row).find("td:eq(24)").css("background-color", "#faf9ee");
              $(row).find("td:eq(24)").css("color", "black");
              $(row).find("td:eq(25)").css("background-color", "#faf9ee");
              $(row).find("td:eq(25)").css("color", "black");

              $(row).find("td:eq(26)").css("background-color", "#e2e0c9");
              $(row).find("td:eq(26)").css("color", "black");
            },
          });

          // toastr.success('Proses Simpan Gaji harian Berlangsung');

          xin_table3.api().ajax.reload(function () {
            alert_success("Sukses", JSON.result);
          }, true);

          $(".icon-spinner3").hide();
          $(".save").prop("disabled", false);
        }
      },
    });
  });
});

$(document).on("click", ".delete", function () {
  $("input[name=_token]").val($(this).data("record-id"));
  $("#delete_record").attr(
    "action",
    base_url + "/payslip_delete_harian/" + $(this).data("record-id")
  ) + "/";
});
