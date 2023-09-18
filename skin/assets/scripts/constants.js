$(document).ready(function () {
  // Load
  var xin_table_contract_type = $("#xin_table_contract_type").dataTable({
    bDestroy: true,
    bFilter: true,
    bLengthChange: true,
    iDisplayLength: 10,
    aLengthMenu: [
      [10, 30, 50, 100, -1],
      [10, 30, 50, 100, "All"],
    ],
    ajax: {
      url: site_url + "settings/contract_type_list/",
      type: "GET",
    },

    fnDrawCallback: function (settings) {
      $('[data-toggle="tooltip"]').tooltip();
    },
  });

  var xin_table_education_level = $("#xin_table_education_level").dataTable({
    bDestroy: true,
    bFilter: true,
    bLengthChange: true,
    iDisplayLength: 10,
    aLengthMenu: [
      [10, 30, 50, 100, -1],
      [10, 30, 50, 100, "All"],
    ],
    ajax: {
      url: site_url + "settings/education_level_list/",
      type: "GET",
    },

    fnDrawCallback: function (settings) {
      $('[data-toggle="tooltip"]').tooltip();
    },
  });

  var xin_table_qualification_language = $(
    "#xin_table_qualification_language"
  ).dataTable({
    bDestroy: true,
    bFilter: true,
    bLengthChange: true,
    iDisplayLength: 10,
    aLengthMenu: [
      [10, 30, 50, 100, -1],
      [10, 30, 50, 100, "All"],
    ],
    ajax: {
      url: site_url + "settings/qualification_language_list/",
      type: "GET",
    },

    fnDrawCallback: function (settings) {
      $('[data-toggle="tooltip"]').tooltip();
    },
  });

  var xin_table_qualification_skill = $(
    "#xin_table_qualification_skill"
  ).dataTable({
    bDestroy: true,
    bFilter: true,
    bLengthChange: true,
    iDisplayLength: 10,
    aLengthMenu: [
      [10, 30, 50, 100, -1],
      [10, 30, 50, 100, "All"],
    ],
    ajax: {
      url: site_url + "settings/qualification_skill_list/",
      type: "GET",
    },

    fnDrawCallback: function (settings) {
      $('[data-toggle="tooltip"]').tooltip();
    },
  });

  var xin_table_award_type = $("#xin_table_award_type").dataTable({
    bDestroy: true,
    bFilter: true,
    bLengthChange: true,
    iDisplayLength: 10,
    aLengthMenu: [
      [10, 30, 50, 100, -1],
      [10, 30, 50, 100, "All"],
    ],
    ajax: {
      url: site_url + "settings/award_type_list/",
      type: "GET",
    },

    fnDrawCallback: function (settings) {
      $('[data-toggle="tooltip"]').tooltip();
    },
  });

  var xin_table_warning_type = $("#xin_table_warning_type").dataTable({
    bDestroy: true,
    bFilter: true,
    bLengthChange: true,
    iDisplayLength: 10,
    aLengthMenu: [
      [10, 30, 50, 100, -1],
      [10, 30, 50, 100, "All"],
    ],
    ajax: {
      url: site_url + "settings/warning_type_list/",
      type: "GET",
    },

    fnDrawCallback: function (settings) {
      $('[data-toggle="tooltip"]').tooltip();
    },
  });

  var xin_table_ethnicity_type = $("#xin_table_ethnicity_type").dataTable({
    bDestroy: true,
    bFilter: true,
    bLengthChange: true,
    iDisplayLength: 10,
    aLengthMenu: [
      [10, 30, 50, 100, -1],
      [10, 30, 50, 100, "All"],
    ],
    ajax: {
      url: site_url + "settings/ethnicity_type_list/",
      type: "GET",
    },

    fnDrawCallback: function (settings) {
      $('[data-toggle="tooltip"]').tooltip();
    },
  });

  var xin_table_leave_type = $("#xin_table_leave_type").dataTable({
    bDestroy: true,
    bFilter: true,
    bLengthChange: true,
    iDisplayLength: 10,
    aLengthMenu: [
      [10, 30, 50, 100, -1],
      [10, 30, 50, 100, "All"],
    ],
    ajax: {
      url: site_url + "settings/leave_type_list/",
      type: "GET",
    },

    fnDrawCallback: function (settings) {
      $('[data-toggle="tooltip"]').tooltip();
    },
  });

  var xin_table_sick_type = $("#xin_table_sick_type").dataTable({
    bDestroy: true,
    bFilter: true,
    bAutoWidth: false,
    bLengthChange: true,
    iDisplayLength: 10,
    aLengthMenu: [
      [10, 30, 50, 100, -1],
      [10, 30, 50, 100, "All"],
    ],
    ajax: {
      url: site_url + "settings/sick_type_list/",
      type: "GET",
    },

    fnDrawCallback: function (settings) {
      $('[data-toggle="tooltip"]').tooltip();
    },
  });

  var xin_table_izin_type = $("#xin_table_izin_type").dataTable({
    bDestroy: true,
    bFilter: true,
    bAutoWidth: false,
    bLengthChange: true,
    iDisplayLength: 10,
    aLengthMenu: [
      [10, 30, 50, 100, -1],
      [10, 30, 50, 100, "All"],
    ],
    ajax: {
      url: site_url + "settings/izin_type_list/",
      type: "GET",
    },

    fnDrawCallback: function (settings) {
      $('[data-toggle="tooltip"]').tooltip();
    },
  });

  var xin_table_exit_type = $("#xin_table_exit_type").dataTable({
    bSortable: false,
    bDestroy: true,
    bFilter: true,
    bLengthChange: true,
    iDisplayLength: 10,
    aLengthMenu: [
      [10, 30, 50, 100, -1],
      [10, 30, 50, 100, "All"],
    ],
    ajax: {
      url: site_url + "settings/exit_type_list/",
      type: "GET",
    },

    fnDrawCallback: function (settings) {
      $('[data-toggle="tooltip"]').tooltip();
    },
  });

  var xin_table_exit_type_reason = $("#xin_table_exit_type_reason").dataTable({
    bSortable: false,
    bDestroy: true,
    bFilter: true,
    bLengthChange: true,
    iDisplayLength: 10,
    aLengthMenu: [
      [10, 30, 50, 100, -1],
      [10, 30, 50, 100, "All"],
    ],
    ajax: {
      url: site_url + "settings/exit_type_reason_list/",
      type: "GET",
    },

    fnDrawCallback: function (settings) {
      $('[data-toggle="tooltip"]').tooltip();
    },
  });

  //=====================================================================================================
  // 01 Jenis Kontrak Kerja
  // ====================================================================================================
  jQuery("#contract_type_info").submit(function (e) {
    /*Form Submit*/
    e.preventDefault();
    var obj = jQuery(this),
      action = obj.attr("name");
    jQuery(".save").prop("disabled", true);
    $(".icon-spinner3").show();
    jQuery.ajax({
      type: "POST",
      url: e.target.action,
      data:
        obj.serialize() +
        "&is_ajax=16&data=contract_type_info&type=contract_type_info&form=" +
        action,
      cache: false,
      success: function (JSON) {
        if (JSON.error != "") {
          alert_fail("Gagal", JSON.error);
          $('input[name="csrf_hris"]').val(JSON.csrf_hash);
          $(".icon-spinner3").hide();
          jQuery(".save").prop("disabled", false);
        } else {
          xin_table_contract_type.api().ajax.reload(function () {
            alert_success("Sukses", JSON.result);
          }, true);
          $('input[name="csrf_hris"]').val(JSON.csrf_hash);
          $(".icon-spinner3").hide();
          jQuery("#contract_type_info")[0].reset(); // To reset form fields
          jQuery(".save").prop("disabled", false);
        }
      },
    });
  });

  //=====================================================================================================
  // 02 Jenjang Pendidikan
  // ====================================================================================================

  jQuery("#edu_level_info").submit(function (e) {
    /*Form Submit*/
    e.preventDefault();
    var obj = jQuery(this),
      action = obj.attr("name");
    jQuery(".save").prop("disabled", true);
    $(".icon-spinner3").show();
    jQuery.ajax({
      type: "POST",
      url: e.target.action,
      data:
        obj.serialize() +
        "&is_ajax=20&data=edu_level_info&type=edu_level_info&form=" +
        action,
      cache: false,
      success: function (JSON) {
        if (JSON.error != "") {
          alert_fail("Gagal", JSON.error);
          $('input[name="csrf_hris"]').val(JSON.csrf_hash);
          jQuery(".save").prop("disabled", false);
          $(".icon-spinner3").hide();
        } else {
          xin_table_education_level.api().ajax.reload(function () {
            alert_success("Sukses", JSON.result);
          }, true);
          $('input[name="csrf_hris"]').val(JSON.csrf_hash);
          $(".icon-spinner3").hide();
          jQuery("#edu_level_info")[0].reset(); // To reset form fields
          jQuery(".save").prop("disabled", false);
        }
      },
    });
  });

  jQuery("#edu_skill_info").submit(function (e) {
    /*Form Submit*/
    e.preventDefault();
    var obj = jQuery(this),
      action = obj.attr("name");
    jQuery(".save").prop("disabled", true);
    $(".icon-spinner3").show();
    jQuery.ajax({
      type: "POST",
      url: e.target.action,
      data:
        obj.serialize() +
        "&is_ajax=20&data=edu_skill_info&type=edu_skill_info&form=" +
        action,
      cache: false,
      success: function (JSON) {
        if (JSON.error != "") {
          alert_fail("Gagal", JSON.error);
          $('input[name="csrf_hris"]').val(JSON.csrf_hash);
          jQuery(".save").prop("disabled", false);
          $(".icon-spinner3").hide();
        } else {
          xin_table_qualification_skill.api().ajax.reload(function () {
            alert_success("Sukses", JSON.result);
          }, true);
          $('input[name="csrf_hris"]').val(JSON.csrf_hash);
          $(".icon-spinner3").hide();
          jQuery("#edu_skill_info")[0].reset(); // To reset form fields
          jQuery(".save").prop("disabled", false);
        }
      },
    });
  });

  jQuery("#edu_language_info").submit(function (e) {
    /*Form Submit*/
    e.preventDefault();
    var obj = jQuery(this),
      action = obj.attr("name");
    jQuery(".save").prop("disabled", true);
    $(".icon-spinner3").show();
    jQuery.ajax({
      type: "POST",
      url: e.target.action,
      data:
        obj.serialize() +
        "&is_ajax=19&data=edu_language_info&type=edu_language_info&form=" +
        action,
      cache: false,
      success: function (JSON) {
        if (JSON.error != "") {
          alert_fail("Gagal", JSON.error);
          $('input[name="csrf_hris"]').val(JSON.csrf_hash);
          jQuery(".save").prop("disabled", false);
          $(".icon-spinner3").hide();
        } else {
          xin_table_qualification_language.api().ajax.reload(function () {
            alert_success("Sukses", JSON.result);
          }, true);
          $('input[name="csrf_hris"]').val(JSON.csrf_hash);
          $(".icon-spinner3").hide();
          jQuery("#edu_language_info")[0].reset(); // To reset form fields
          jQuery(".save").prop("disabled", false);
        }
      },
    });
  });

  //=====================================================================================================
  // 03 Jenis Penghargaan
  // ====================================================================================================
  jQuery("#award_type_info").submit(function (e) {
    /*Form Submit*/
    e.preventDefault();
    var obj = jQuery(this),
      action = obj.attr("name");
    jQuery(".save").prop("disabled", true);
    $(".icon-spinner3").show();
    jQuery.ajax({
      type: "POST",
      url: e.target.action,
      data:
        obj.serialize() +
        "&is_ajax=22&data=award_type_info&type=award_type_info&form=" +
        action,
      cache: false,
      success: function (JSON) {
        if (JSON.error != "") {
          alert_fail("Gagal", JSON.error);
          $('input[name="csrf_hris"]').val(JSON.csrf_hash);
          $(".icon-spinner3").hide();
          jQuery(".save").prop("disabled", false);
        } else {
          xin_table_award_type.api().ajax.reload(function () {
            alert_success("Sukses", JSON.result);
          }, true);
          $('input[name="csrf_hris"]').val(JSON.csrf_hash);
          $(".icon-spinner3").hide();
          jQuery("#award_type_info")[0].reset(); // To reset form fields
          jQuery(".save").prop("disabled", false);
        }
      },
    });
  });

  //=====================================================================================================
  // 04 Jenis Peringatan
  // ====================================================================================================
  jQuery("#warning_type_info").submit(function (e) {
    /*Form Submit*/
    e.preventDefault();
    var obj = jQuery(this),
      action = obj.attr("name");
    jQuery(".save").prop("disabled", true);
    $(".icon-spinner3").show();
    jQuery.ajax({
      type: "POST",
      url: e.target.action,
      data:
        obj.serialize() +
        "&is_ajax=24&data=warning_type_info&type=warning_type_info&form=" +
        action,
      cache: false,
      success: function (JSON) {
        if (JSON.error != "") {
          alert_fail("Gagal", JSON.error);
          $('input[name="csrf_hris"]').val(JSON.csrf_hash);
          $(".icon-spinner3").hide();
          jQuery(".save").prop("disabled", false);
        } else {
          xin_table_warning_type.api().ajax.reload(function () {
            alert_success("Sukses", JSON.result);
          }, true);
          $('input[name="csrf_hris"]').val(JSON.csrf_hash);
          $(".icon-spinner3").hide();
          jQuery("#warning_type_info")[0].reset(); // To reset form fields
          jQuery(".save").prop("disabled", false);
        }
      },
    });
  });

  //=====================================================================================================
  // 05 Jenis Agama
  // ====================================================================================================

  jQuery("#ethnicity_type_info").submit(function (e) {
    /*Form Submit*/
    e.preventDefault();
    var obj = jQuery(this),
      action = obj.attr("name");
    jQuery(".save").prop("disabled", true);
    $(".icon-spinner3").show();
    jQuery.ajax({
      type: "POST",
      url: e.target.action,
      data:
        obj.serialize() +
        "&is_ajax=24&data=ethnicity_type_info&type=ethnicity_type_info&form=" +
        action,
      cache: false,
      success: function (JSON) {
        if (JSON.error != "") {
          alert_fail("Gagal", JSON.error);
          $('input[name="csrf_hris"]').val(JSON.csrf_hash);
          $(".icon-spinner3").hide();
          jQuery(".save").prop("disabled", false);
        } else {
          xin_table_ethnicity_type.api().ajax.reload(function () {
            alert_success("Sukses", JSON.result);
          }, true);
          $('input[name="csrf_hris"]').val(JSON.csrf_hash);
          $(".icon-spinner3").hide();
          jQuery("#ethnicity_type_info")[0].reset(); // To reset form fields
          jQuery(".save").prop("disabled", false);
        }
      },
    });
  });

  //=====================================================================================================
  // 06 Jenis Cuti
  // ====================================================================================================

  jQuery("#leave_type_info").submit(function (e) {
    /*Form Submit*/
    e.preventDefault();
    var obj = jQuery(this),
      action = obj.attr("name");
    jQuery(".save").prop("disabled", true);
    $(".icon-spinner3").show();
    jQuery.ajax({
      type: "POST",
      url: e.target.action,
      data:
        obj.serialize() +
        "&is_ajax=23&data=leave_type_info&type=leave_type_info&form=" +
        action,
      cache: false,
      success: function (JSON) {
        if (JSON.error != "") {
          alert_fail("Gagal", JSON.error);
          $('input[name="csrf_hris"]').val(JSON.csrf_hash);
          jQuery(".save").prop("disabled", false);
          $(".icon-spinner3").hide();
        } else {
          xin_table_leave_type.api().ajax.reload(function () {
            alert_success("Sukses", JSON.result);
          }, true);
          $('input[name="csrf_hris"]').val(JSON.csrf_hash);
          $(".icon-spinner3").hide();
          jQuery("#leave_type_info")[0].reset(); // To reset form fields
          jQuery(".save").prop("disabled", false);
        }
      },
    });
  });

  //=====================================================================================================
  // 07 Jenis Sakit
  // ====================================================================================================
  jQuery("#sick_type_info").submit(function (e) {
    /*Form Submit*/
    e.preventDefault();
    var obj = jQuery(this),
      action = obj.attr("name");
    jQuery(".save").prop("disabled", true);
    $(".icon-spinner3").show();
    jQuery.ajax({
      type: "POST",
      url: e.target.action,
      data:
        obj.serialize() +
        "&is_ajax=23&data=sick_type_info&type=sick_type_info&form=" +
        action,
      cache: false,
      success: function (JSON) {
        if (JSON.error != "") {
          alert_fail("Gagal", JSON.error);
          $('input[name="csrf_hris"]').val(JSON.csrf_hash);
          jQuery(".save").prop("disabled", false);
          $(".icon-spinner3").hide();
        } else {
          xin_table_sick_type.api().ajax.reload(function () {
            alert_success("Sukses", JSON.result);
          }, true);
          $('input[name="csrf_hris"]').val(JSON.csrf_hash);
          $(".icon-spinner3").hide();
          jQuery("#sick_type_info")[0].reset(); // To reset form fields
          jQuery(".save").prop("disabled", false);
        }
      },
    });
  });

  //=====================================================================================================
  // 08 Jenis Izin
  // ====================================================================================================
  jQuery("#izin_type_info").submit(function (e) {
    /*Form Submit*/
    e.preventDefault();
    var obj = jQuery(this),
      action = obj.attr("name");
    jQuery(".save").prop("disabled", true);
    $(".icon-spinner3").show();
    jQuery.ajax({
      type: "POST",
      url: e.target.action,
      data:
        obj.serialize() +
        "&is_ajax=23&data=izin_type_info&type=izin_type_info&form=" +
        action,
      cache: false,
      success: function (JSON) {
        if (JSON.error != "") {
          alert_fail("Gagal", JSON.error);
          $('input[name="csrf_hris"]').val(JSON.csrf_hash);
          jQuery(".save").prop("disabled", false);
          $(".icon-spinner3").hide();
        } else {
          xin_table_izin_type.api().ajax.reload(function () {
            alert_success("Sukses", JSON.result);
          }, true);
          $('input[name="csrf_hris"]').val(JSON.csrf_hash);
          $(".icon-spinner3").hide();
          jQuery("#izin_type_info")[0].reset(); // To reset form fields
          jQuery(".save").prop("disabled", false);
        }
      },
    });
  });

  //=====================================================================================================
  // 09 Jenis Resign
  // ====================================================================================================

  jQuery("#exit_type_info").submit(function (e) {
    /*Form Submit*/
    e.preventDefault();
    var obj = jQuery(this),
      action = obj.attr("name");
    jQuery(".save").prop("disabled", true);
    $(".icon-spinner3").show();
    jQuery.ajax({
      type: "POST",
      url: e.target.action,
      data:
        obj.serialize() +
        "&is_ajax=28&data=exit_type_info&type=exit_type_info&form=" +
        action,
      cache: false,
      success: function (JSON) {
        if (JSON.error != "") {
          alert_fail("Gagal", JSON.error);
          $('input[name="csrf_hris"]').val(JSON.csrf_hash);
          $(".icon-spinner3").hide();
          jQuery(".save").prop("disabled", false);
        } else {
          xin_table_exit_type.api().ajax.reload(function () {
            alert_success("Sukses", JSON.result);
          }, true);
          $('input[name="csrf_hris"]').val(JSON.csrf_hash);
          $(".icon-spinner3").hide();
          jQuery("#exit_type_info")[0].reset(); // To reset form fields
          jQuery(".save").prop("disabled", false);
        }
      },
    });
  });

  //=====================================================================================================
  // 09 Jenis Resign
  // ====================================================================================================

  jQuery("#exit_type_reason_info").submit(function (e) {
    /*Form Submit*/
    e.preventDefault();
    var obj = jQuery(this),
      action = obj.attr("name");
    jQuery(".save").prop("disabled", true);
    $(".icon-spinner3").show();
    jQuery.ajax({
      type: "POST",
      url: e.target.action,
      data:
        obj.serialize() +
        "&is_ajax=28&data=exit_type_reason_info&type=exit_type_reason_info&form=" +
        action,
      cache: false,
      success: function (JSON) {
        if (JSON.error != "") {
          alert_fail("Gagal", JSON.error);
          $('input[name="csrf_hris"]').val(JSON.csrf_hash);
          $(".icon-spinner3").hide();
          jQuery(".save").prop("disabled", false);
        } else {
          xin_table_exit_type_reason.api().ajax.reload(function () {
            alert_success("Sukses", JSON.result);
          }, true);
          $('input[name="csrf_hris"]').val(JSON.csrf_hash);
          $(".icon-spinner3").hide();
          jQuery("#exit_type_reason_info")[0].reset(); // To reset form fields
          jQuery(".save").prop("disabled", false);
        }
      },
    });
  });

  //=====================================================================================================
  // 10 Jenis Dinas
  // ====================================================================================================

  var xin_table_travel_arr_type = $("#xin_table_travel_arr_type").dataTable({
    bDestroy: true,
    bFilter: true,
    bLengthChange: true,
    iDisplayLength: 10,
    aLengthMenu: [
      [10, 30, 50, 100, -1],
      [10, 30, 50, 100, "All"],
    ],
    ajax: {
      url: site_url + "settings/travel_arr_type_list/",
      type: "GET",
    },

    fnDrawCallback: function (settings) {
      $('[data-toggle="tooltip"]').tooltip();
    },
  });

  jQuery("#travel_arr_type_info").submit(function (e) {
    /*Form Submit*/
    e.preventDefault();
    var obj = jQuery(this),
      action = obj.attr("name");
    jQuery(".save").prop("disabled", true);
    $(".icon-spinner3").show();
    jQuery.ajax({
      type: "POST",
      url: e.target.action,
      data:
        obj.serialize() +
        "&is_ajax=45&data=travel_arr_type_info&type=travel_arr_type_info&form=" +
        action,
      cache: false,
      success: function (JSON) {
        if (JSON.error != "") {
          alert_fail("Gagal", JSON.error);
          $('input[name="csrf_hris"]').val(JSON.csrf_hash);
          jQuery(".save").prop("disabled", false);
          $(".icon-spinner3").hide();
        } else {
          xin_table_travel_arr_type.api().ajax.reload(function () {
            alert_success("Sukses", JSON.result);
          }, true);
          $('input[name="csrf_hris"]').val(JSON.csrf_hash);
          $(".icon-spinner3").hide();
          jQuery("#travel_arr_type_info")[0].reset(); // To reset form fields
          jQuery(".save").prop("disabled", false);
        }
      },
    });
  });

  //=====================================================================================================
  // 11 Jenis Transport
  // ====================================================================================================

  var xin_table_transport_arr_type = $(
    "#xin_table_transport_arr_type"
  ).dataTable({
    bDestroy: true,
    bFilter: true,
    bLengthChange: true,
    iDisplayLength: 10,
    aLengthMenu: [
      [10, 30, 50, 100, -1],
      [10, 30, 50, 100, "All"],
    ],
    ajax: {
      url: site_url + "settings/transport_arr_type_list/",
      type: "GET",
    },

    fnDrawCallback: function (settings) {
      $('[data-toggle="tooltip"]').tooltip();
    },
  });

  jQuery("#transport_arr_type_info").submit(function (e) {
    /*Form Submit*/
    e.preventDefault();
    var obj = jQuery(this),
      action = obj.attr("name");
    jQuery(".save").prop("disabled", true);
    $(".icon-spinner3").show();
    jQuery.ajax({
      type: "POST",
      url: e.target.action,
      data:
        obj.serialize() +
        "&is_ajax=45&data=transport_arr_type_info&type=transport_arr_type_info&form=" +
        action,
      cache: false,
      success: function (JSON) {
        if (JSON.error != "") {
          alert_fail("Gagal", JSON.error);
          $('input[name="csrf_hris"]').val(JSON.csrf_hash);
          jQuery(".save").prop("disabled", false);
          $(".icon-spinner3").hide();
        } else {
          xin_table_transport_arr_type.api().ajax.reload(function () {
            alert_success("Sukses", JSON.result);
          }, true);
          $('input[name="csrf_hris"]').val(JSON.csrf_hash);
          $(".icon-spinner3").hide();
          jQuery("#transport_arr_type_info")[0].reset(); // To reset form fields
          jQuery(".save").prop("disabled", false);
        }
      },
    });
  });

  //=====================================================================================================
  // 12 Jenis Perjanjian
  // ====================================================================================================

  var xin_table_perjanjian_type = $("#xin_table_perjanjian_type").dataTable({
    bDestroy: true,
    bFilter: true,
    bLengthChange: true,
    iDisplayLength: 10,
    aLengthMenu: [
      [10, 30, 50, 100, -1],
      [10, 30, 50, 100, "All"],
    ],
    ajax: {
      url: site_url + "settings/perjanjian_type/",
      type: "GET",
    },

    fnDrawCallback: function (settings) {
      $('[data-toggle="tooltip"]').tooltip();
    },
  });

  jQuery("#perjanjian_type_info").submit(function (e) {
    /*Form Submit*/
    e.preventDefault();
    var obj = jQuery(this),
      action = obj.attr("name");
    jQuery(".save").prop("disabled", true);
    $(".icon-spinner3").show();
    jQuery.ajax({
      type: "POST",
      url: e.target.action,
      data:
        obj.serialize() +
        "&is_ajax=45&data=perjanjian_type_info&type=perjanjian_type_info&form=" +
        action,
      cache: false,
      success: function (JSON) {
        if (JSON.error != "") {
          alert_fail("Gagal", JSON.error);
          $('input[name="csrf_hris"]').val(JSON.csrf_hash);
          jQuery(".save").prop("disabled", false);
          $(".icon-spinner3").hide();
        } else {
          xin_table_perjanjian_type.api().ajax.reload(function () {
            alert_success("Sukses", JSON.result);
          }, true);
          $('input[name="csrf_hris"]').val(JSON.csrf_hash);
          $(".icon-spinner3").hide();
          jQuery("#perjanjian_type_info")[0].reset(); // To reset form fields
          jQuery(".save").prop("disabled", false);
        }
      },
    });
  });

  //=====================================================================================================
  // 13 Jenis Perizinan
  // ====================================================================================================

  var xin_table_perizinan_type = $("#xin_table_perizinan_type").dataTable({
    bDestroy: true,
    bFilter: true,
    bLengthChange: true,
    iDisplayLength: 10,
    aLengthMenu: [
      [10, 30, 50, 100, -1],
      [10, 30, 50, 100, "All"],
    ],
    ajax: {
      url: site_url + "settings/perizinan_type/",
      type: "GET",
    },
    fnDrawCallback: function (settings) {
      $('[data-toggle="tooltip"]').tooltip();
    },
  });

  jQuery("#perizinan_type_info").submit(function (e) {
    /*Form Submit*/
    e.preventDefault();
    var obj = jQuery(this),
      action = obj.attr("name");
    jQuery(".save").prop("disabled", true);
    $(".icon-spinner3").show();
    jQuery.ajax({
      type: "POST",
      url: e.target.action,
      data:
        obj.serialize() +
        "&is_ajax=45&data=perizinan_type_info&type=perizinan_type_info&form=" +
        action,
      cache: false,
      success: function (JSON) {
        if (JSON.error != "") {
          alert_fail("Gagal", JSON.error);
          $('input[name="csrf_hris"]').val(JSON.csrf_hash);
          jQuery(".save").prop("disabled", false);
          $(".icon-spinner3").hide();
        } else {
          xin_table_perizinan_type.api().ajax.reload(function () {
            alert_success("Sukses", JSON.result);
          }, true);
          $('input[name="csrf_hris"]').val(JSON.csrf_hash);
          $(".icon-spinner3").hide();
          jQuery("#perizinan_type_info")[0].reset(); // To reset form fields
          jQuery(".save").prop("disabled", false);
        }
      },
    });
  });

  //=====================================================================================================
  // HAPUS
  // ====================================================================================================

  /* Delete data */
  $("#delete_record").submit(function (e) {
    var tk_type = $("#token_type").val();

    $(".icon-spinner3").show();

    if (tk_type == "contract_type") {
      var field_add =
        "&is_ajax=10&data=delete_contract_type&type=delete_record&";
      var tb_name = "xin_table_" + tk_type;
    } else if (tk_type == "education_level") {
      var field_add =
        "&is_ajax=12&data=delete_education_level&type=delete_record&";
      var tb_name = "xin_table_" + tk_type;
    } else if (tk_type == "qualification_skill") {
      var field_add =
        "&is_ajax=14&data=delete_qualification_skill&type=delete_record&";
      var tb_name = "xin_table_" + tk_type;
    } else if (tk_type == "award_type") {
      var field_add = "&is_ajax=31&data=delete_award_type&type=delete_record&";
      var tb_name = "xin_table_" + tk_type;
    } else if (tk_type == "leave_type") {
      var field_add = "&is_ajax=32&data=delete_leave_type&type=delete_record&";
      var tb_name = "xin_table_" + tk_type;
    } else if (tk_type == "sick_type") {
      var field_add = "&is_ajax=32&data=delete_sick_type&type=delete_record&";
      var tb_name = "xin_table_" + tk_type;
    } else if (tk_type == "izin_type") {
      var field_add = "&is_ajax=32&data=delete_izin_type&type=delete_record&";
      var tb_name = "xin_table_" + tk_type;
    } else if (tk_type == "warning_type") {
      var field_add =
        "&is_ajax=33&data=delete_warning_type&type=delete_record&";
      var tb_name = "xin_table_" + tk_type;
    } else if (tk_type == "exit_type") {
      var field_add = "&is_ajax=37&data=delete_exit_type&type=delete_record&";
      var tb_name = "xin_table_" + tk_type;
    } else if (tk_type == "exit_type_reason") {
      var field_add =
        "&is_ajax=37&data=delete_exit_type_reason&type=delete_record_reason&";
      var tb_name = "xin_table_" + tk_type;
    } else if (tk_type == "travel_arr_type") {
      var field_add =
        "&is_ajax=47&data=delete_travel_arr_type&type=delete_record&";
      var tb_name = "xin_table_" + tk_type;
    } else if (tk_type == "transport_arr_type") {
      var field_add =
        "&is_ajax=47&data=delete_transport_arr_type&type=delete_record&";
      var tb_name = "xin_table_" + tk_type;
    } else if (tk_type == "ethnicity_type") {
      var field_add =
        "&is_ajax=47&data=delete_ethnicity_type&type=delete_record&";
      var tb_name = "xin_table_" + tk_type;
    } else if (tk_type == "perjanjian_type") {
      var field_add =
        "&is_ajax=47&data=delete_perjanjian_type&type=delete_record&";
      var tb_name = "xin_table_" + tk_type;
    } else if (tk_type == "perizinan_type") {
      var field_add =
        "&is_ajax=47&data=delete_perizinan_type&type=delete_record&";
      var tb_name = "xin_table_" + tk_type;
    }

    /*Form Submit*/
    e.preventDefault();
    var obj = $(this),
      action = obj.attr("name");
    $.ajax({
      url: e.target.action,
      type: "post",
      data: "?" + obj.serialize() + field_add + "form=" + action,
      success: function (JSON) {
        if (JSON.error != "") {
          alert_fail("Gagal", JSON.error);
          $('input[name="csrf_hris"]').val(JSON.csrf_hash);
          $(".icon-spinner3").hide();
        } else {
          $(".delete-modal").modal("toggle");
          $(".icon-spinner3").hide();
          $("#" + tb_name)
            .dataTable()
            .api()
            .ajax.reload(function () {
              alert_success("Sukses", JSON.result);
            }, true);
          $('input[name="csrf_hris"]').val(JSON.csrf_hash);
        }
      },
    });
  });

  //=====================================================================================================
  // EDIT
  // ====================================================================================================

  $("#edit_setting_datail").on("show.bs.modal", function (event) {
    var button = $(event.relatedTarget);
    var field_id = button.data("field_id");
    var field_type = button.data("field_type");
    $(".icon-spinner3").show();

    if (field_type == "contract_type") {
      var field_add = "&data=ed_contract_type&type=ed_contract_type&";
    } else if (field_type == "education_level") {
      var field_add = "&data=ed_education_level&type=ed_education_level&";
    } else if (field_type == "qualification_skill") {
      var field_add =
        "&data=ed_qualification_skill&type=ed_qualification_skill&";
    } else if (field_type == "award_type") {
      var field_add = "&data=ed_award_type&type=ed_award_type&";
    } else if (field_type == "leave_type") {
      var field_add = "&data=ed_leave_type&type=ed_leave_type&";
    } else if (field_type == "sick_type") {
      var field_add = "&data=ed_sick_type&type=ed_sick_type&";
    } else if (field_type == "izin_type") {
      var field_add = "&data=ed_izin_type&type=ed_izin_type&";
    } else if (field_type == "warning_type") {
      var field_add = "&data=ed_warning_type&type=ed_warning_type&";
    } else if (field_type == "exit_type") {
      var field_add = "&data=ed_exit_type&type=ed_exit_type&";
    } else if (field_type == "exit_type_reason") {
      var field_add = "&data=ed_exit_type_reason&type=ed_exit_type_reason&";
    } else if (field_type == "travel_arr_type") {
      var field_add = "&data=ed_travel_arr_type&type=ed_travel_arr_type&";
    } else if (field_type == "transport_arr_type") {
      var field_add = "&data=ed_transport_arr_type&type=ed_transport_arr_type&";
    } else if (field_type == "ethnicity_type") {
      var field_add = "&data=ed_ethnicity_type&type=ed_ethnicity_type&";
    } else if (field_type == "perjanjian_type") {
      var field_add = "&data=ed_perjanjian_type&type=ed_perjanjian_type&";
    } else if (field_type == "perizinan_type") {
      var field_add = "&data=ed_perizinan_type&type=ed_perizinan_type&";
    } else if (field_type == "payroll_year") {
      var field_add = "&data=ed_payroll_year&type=ed_payroll_year&";
    }

    var modal = $(this);
    $.ajax({
      url: site_url + "settings/constants_read/",
      type: "GET",
      data: "jd=1" + field_add + "field_id=" + field_id,
      success: function (response) {
        if (response) {
          $(".icon-spinner3").hide();
          $("#ajax_setting_info").html(response);
        }
      },
    });
  });

  //=====================================================================================================
  // LAIN
  // ====================================================================================================

  $('[data-plugin="select_hrm"]').select2($(this).attr("data-options"));
  $('[data-plugin="select_hrm"]').select2({ width: "100%" });

  $(".nav-tabs-link").click(function () {
    var profile_id = $(this).data("constant");
    var profile_block = $(this).data("constant-block");
    $(".list-group-item").removeClass("active");
    $(".current-tab").hide();
    $("#constant_" + profile_id).addClass("active");
    $("#" + profile_block).show();
  });

  //=====================================================================================================
  //=====================================================================================================

  //=====================================================================================================
  // 104 Payroll Year
  // ====================================================================================================

  var xin_table_payroll_year = $("#xin_table_payroll_year").dataTable({
    bDestroy: true,
    bFilter: true,
    bLengthChange: true,
    iDisplayLength: 10,
    aLengthMenu: [
      [10, 30, 50, 100, -1],
      [10, 30, 50, 100, "All"],
    ],
    ajax: {
      url: site_url + "settings/payroll_year/",
      type: "GET",
    },
    fnDrawCallback: function (settings) {
      $('[data-toggle="tooltip"]').tooltip();
    },
  });

  jQuery("#add_payroll_year").submit(function (e) {
    /*Form Submit*/
    e.preventDefault();
    var obj = jQuery(this),
      action = obj.attr("name");
    jQuery(".save").prop("disabled", true);
    $(".icon-spinner3").show();
    jQuery.ajax({
      type: "POST",
      url: e.target.action,
      data: obj.serialize() + "&type=add_payroll_year&form=" + action,
      cache: false,
      success: function (JSON) {
        if (JSON.error != "") {
          alert_fail("Gagal", JSON.error);
          $('input[name="csrf_hris"]').val(JSON.csrf_hash);
          jQuery(".save").prop("disabled", false);
          $(".icon-spinner3").hide();
        } else {
          xin_table_payroll_year.api().ajax.reload(function () {
            alert_success("Sukses", JSON.result);
          }, true);
          $('input[name="csrf_hris"]').val(JSON.csrf_hash);
          $(".icon-spinner3").hide();
          jQuery("#transport_arr_type_info")[0].reset(); // To reset form fields
          jQuery(".save").prop("disabled", false);
        }
      },
    });
  });
});

$(document).on("click", ".delete", function () {
  $("input[name=_token]").val($(this).data("record-id"));
  $("input[name=token_type]").val($(this).data("token_type"));
  $("#delete_record").attr(
    "action",
    site_url +
      "settings/delete_" +
      $(this).data("token_type") +
      "/" +
      $(this).data("record-id")
  ) + "/";
});
