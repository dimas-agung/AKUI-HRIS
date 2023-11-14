$(document).ready(function () {
    var tahun_thr = jQuery("#tahun_thr").val();
    var company_id = jQuery("#aj_companyx").val();
    var tanggal_thr = jQuery("#tanggal_thr").val();

    $("#p_month").html(tahun_thr + ", Tanggal Batas THR : " + tanggal_thr);

    $('[data-plugin="select_hrm"]').select2($(this).attr("data-options"));
    $('[data-plugin="select_hrm"]').select2({ width: "100%" });

    $(".del_dayly_pay").on("show.bs.modal", function (event) {
      var button = $(event.relatedTarget);
      var payslip_id = button.data("payslip_id");
      var tahun_thr = $("#tahun_thr").val();
      var company_id = button.data("company_id");

      var modal = $(this);
      $.ajax({
        url: site_url + "thr/pay_daily_thr_del/",
        type: "GET",
        data: {
          jd: 1,
          is_ajax: 1,
          data: "payment",
          type: "daily_payment_delete",
          payslip_id: payslip_id,
          tahun_thr: tahun_thr,
          tanggal_thr: tanggal_thr,
          company_id: company_id,
        },
        success: function (response) {
          if (response) {
            $("#del_dayly_pay_aj").html(response);
          }
        },
      });
    });

    $(".thr_date").datepicker({
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

    $(".emo_dayly_pay").on("show.bs.modal", function (event) {
      var button = $(event.relatedTarget);
      var employee_id = button.data("employee_id");
      var payment_date = $("#tahun_thr").val();
      var company_id = button.data("company_id");
      var tanggal_thr = $("#tanggal_thr").val();
      var modal = $(this);

      $.ajax({
        url: site_url + "thr/pay_salary_daily/",
        type: "GET",
        data: {
          jd: 1,
          is_ajax: 1,
          data: "payment",
          type: "daily_payment",
          employee_id: employee_id,
          tahun_thr: tahun_thr,
          tanggal_thr: tanggal_thr,
          company_id: company_id,
        },
        success: function (response) {
          if (response) {
            $("#emo_dayly_pay_aj").html(response);
          }
        },
      });
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

    $(".payroll_template_modal").on("show.bs.modal", function (event) {
      var button = $(event.relatedTarget);
      var employee_id = button.data("employee_id");
      var modal = $(this);
      $.ajax({
        url: site_url + "thr/payroll_template_read/",
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

      swal(
        {
          title: "Simpan Gaji THR Harian",
          text: "",
          type: "warning",
          showCancelButton: true,
          cancelButtonClass: "btn-raised btn-warning",
          cancelButtonText: "Tidak!",
          confirmButtonClass: "btn-raised btn-danger",
          confirmButtonText: "Ya!",
          closeOnConfirm: false,
          showLoaderOnConfirm: true,
        },
        function () {
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
                $("#xin_table_harian").DataTable().ajax.reload();
                alert_success("Sukses", JSON.result);
                $(".icon-spinner3").hide();
                $(".save").prop("disabled", false);
              }
            },
          }).always(function () {
            swal.close();
          });
        }
      );

      return false;
    });
  });

  $(document).on("click", ".delete", function () {
    $("input[name=_token]").val($(this).data("record-id"));
    $("#delete_record").attr(
      "action",
      base_url + "/payslip_delete_bulanan/" + $(this).data("record-id")
    ) + "/";
  });
