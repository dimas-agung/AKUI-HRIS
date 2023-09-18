$(document).ready(function () {
  var start_date = jQuery("#start_date").val();
  var end_date = jQuery("#end_date").val();

  var company_id = jQuery("#aj_companyx").val();

  $periode = start_date + " s/d " + end_date;

  $("#p_month").html($periode);

  $('[data-plugin="select_hrm"]').select2($(this).attr("data-options"));
  $('[data-plugin="select_hrm"]').select2({ width: "100%" });

  $(".del_borongan_pay").on("show.bs.modal", function (event) {
    var button = $(event.relatedTarget);

    var payslip_id = button.data("payslip_id");
    var start_date = $("#start_date").val();
    var end_date = $("#end_date").val();
    var company_id = button.data("company_id");

    var modal = $(this);
    $.ajax({
      url: site_url + "payroll/pay_salary_borongan_del/",
      type: "GET",
      data:
        "jd=1&is_ajax=11&data=payment&type=borongan_payment_delete&payslip_id=" +
        payslip_id +
        "&start_date=" +
        start_date +
        "&end_date=" +
        end_date +
        "&company_id=" +
        company_id,
      success: function (response) {
        if (response) {
          $("#del_borongan_pay_aj").html(response);
        }
      },
    });
  });

  jQuery("#aj_companyx").change(function () {
    jQuery.get(
      base_url + "/get_workstations/" + jQuery(this).val(),
      function (data, status) {
        jQuery("#workstation_ajax").html(data);
      }
    );
  });

  $(".emo_borongan_pay").on("show.bs.modal", function (event) {
    var button = $(event.relatedTarget);
    var employee_id = button.data("employee_id");

    var start_date = $("#start_date").val();
    var end_date = $("#end_date").val();

    var company_id = button.data("company_id");
    var modal = $(this);
    $.ajax({
      url: site_url + "payroll/pay_salary_borongan/",
      type: "GET",
      data:
        "jd=1&is_ajax=11&data=payment&type=borongan_payment&employee_id=" +
        employee_id +
        "&start_date=" +
        start_date +
        "&end_date=" +
        end_date +
        "&company_id=" +
        company_id,
      success: function (response) {
        if (response) {
          $("#emo_borongan_pay_aj").html(response);
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

  $(".payroll_template_modal_borongan").on("show.bs.modal", function (event) {
    var button = $(event.relatedTarget);
    var employee_id = button.data("employee_id");
    var modal = $(this);
    $.ajax({
      url: site_url + "payroll/payroll_template_read_borongan/",
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

    toastr.success('Proses Simpan Gaji borongan Berlangsung');

    var start_date = jQuery("#start_date").val();
    var end_date = jQuery("#end_date").val();
    var workstation_id = jQuery("#workstation_id").val();
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
          var xin_table3 = $("#xin_table_borongan").dataTable();

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
    base_url + "/payslip_delete_borongan/" + $(this).data("record-id")
  ) + "/";
});
