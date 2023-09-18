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

let _dt = null;

function searchDataAttendance() {
  $(".info_rekap").html("Loading ...");

  var month_year = jQuery("#month_year").val();
  var company_id = jQuery("#company_id").val();
  var jenis_gaji = jQuery("#jenis_gaji").val();
  var period_id = $("#periode_id").val();

  if (company_id == "") {
    alert("Nama Perusahaan Belum Diisi !");
    $("#company").focus();
  } else if (month_year == "") {
    alert("Bulan Kehadiran Belum Diisi !");
    $("#month_year").focus();
  } else if (jenis_gaji == "") {
    alert("Jenis Karyawan Belum Diisi !");
    $("#jenis_gaji").focus();
  } else {
    if (jenis_gaji != 1 && period_id == "") {
      alert("Periode Belum Diisi !");
      $("#periode_id").focus();
      return;
    }

    const monthYear = $("#month_year option:selected").text();

    $(".info_rekap").html(
      '<h3 class="box-title text-uppercase text-bold"> Rekap Lembur, Periode : ' +
        monthYear +
        '</h3><h5><i class="fa fa-warning"></i> Silahkan Klik Tombol "<span class="blink blink-two kuning">Rekap Lembur</span>" terlebih dahulu. Proses Rekap ini dilakukan sekali dan akan merekap semua lembur dari form pengajuan lembur, jadi dilakukan malam hari / setelah jam kerja.</h5>'
    );

    $("#p_month").html(monthYear);

    toastr.success(`Tampilkan Rekap Lembur Bulan ${monthYear}`);

    const _columns_start = [
      {
        name: "kolom_nomor",
        className: "text-center",
      },
      {
        name: "kolom_karyawan",
        className: "text-left",
      },
    ];

    const _columns_end = [
      {
        name: "kolom_total_jam_lembur",
        className: "text-center",
      },

      {
        name: "kolom_jam_1",
        className: "text-center",
      },
      {
        name: "kolom_jam_1_selanjutnya",
        className: "text-center",
      },

      {
        name: "kolom_biaya_jam_1",
        className: "text-right",
      },
      {
        name: "kolom_biaya_jam_selanjutnya",
        className: "text-right",
      },

      {
        name: "kolom_total",
        className: "text-right",
      },
    ];

    $.ajax({
      url: `${site_url}timesheet/lembur_rekap_list`,
      type: "GET",
      dataType: "json",
      data: {
        company_id,
        month_year,
        jenis_gaji,
        period_id,
      },
      success: function (response) {
        if (_dt) {
          _dt.clear().draw();
          _dt.destroy(); // Hancurkan instance DataTable saat ini
        }

        const tableHead = $("#xin_table_recap thead tr.row_dates");

        tableHead.find(".col-date").remove();
        $(".col-date-head").attr("colspan", response.dates_label.length);

        const _columnns_middle = response.dates_label.map(function (label) {
          var columnHtml = '<th width="50px">' + label + "</th>";
          $(columnHtml).insertBefore(tableHead.find("th.anchor"));

          return {
            name: `kolom_${label}`,
            className: `text-center col-date`,
            width: "50px",
          };
        });

        console.log({
          columns: [..._columns_start, ..._columnns_middle, ..._columns_end],
        });
        _dt = $("#xin_table_recap").DataTable({
          columns: [..._columns_start, ..._columnns_middle, ..._columns_end],
          autoWidth: false,
          bSort: false,
          aLengthMenu: [
            [10, 30, 50, 100, -1],
            [10, 30, 50, 100, "All"],
          ],
          fixedColumns: {
            leftColumns: 2,
          },
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
            "print",
            {
              extend: "pdf",
              orientation: "landscape",
            },
            "excel",
          ],
        });

        _dt.rows.add(response.data).draw();
        tampilkan_tabel();
        tampilkan_tombol();
      },
      error: function (xhr, status, error) {
        console.error("Terjadi kesalahan:", error);
      },
    });
  }
}

$(document).ready(function () {
  $('[data-plugin="select_hrm"]').select2($(this).attr("data-options"));
  $('[data-plugin="select_hrm"]').select2({ width: "100%" });

  /* attendance daily report */
  $(".info_rekap").html(
    '<h3 class="box-title text-uppercase text-bold"> Rekap Lembur</h3><h5><i class="fa fa-warning"></i> Silahkan Klik Tombol "<span class="blink blink-two hijau">Tampilkan</span>" Jika Tidak ada data yang tersedia pada tabel, maka silahkan lakukan Proses "<span class="blink blink-one kuning">Rekap Lembur</span>".</h5>'
  );

  // bulk payments
  $("#lembur_rekap_proses").submit(function (e) {
    e.preventDefault();

    var obj = $(this),
      action = obj.attr("name");

    $(".save").prop("disabled", true);
    $(".icon-spinner3").show();

    var month_year = jQuery("#month_year").val();
    var company_id = jQuery("#company_id").val();
    var jenis_gaji = jQuery("#jenis_gaji").val();

    $.ajax({
      type: "POST",
      url: e.target.action,
      data: obj.serialize() + "&is_ajax=1&add_type=rekap&form=" + action,
      cache: false,
      success: function (JSON) {
        if (JSON.error != "") {
          alert_fail("Gagal", JSON.error);
          $(".save").prop("disabled", false);
          $(".icon-spinner3").hide();
        } else {
          alert_success("Sukses", JSON.result);
          $(".icon-spinner3").hide();
          $(".save").prop("disabled", false);
        }
      },
    });
  });

  var salaryType = "";
  $("#jenis_gaji").on("change", function () {
    /**
     * 1 = bulanan
     * 2 = harian
     * 3 = borongan
     */
    const val = (salaryType = $(this).val());
    const _period = $(".box-period");

    _period.hide();
    _period.find(":input").prop("disabled", true);

    if (val == 2 || val == 3) {
      path = val == 2 ? "get_periode_harian" : "get_periode_borongan";

      _period.show();
      _period.find(":input").prop("disabled", false);
      $("#month_year").trigger("change");
    }
  });

  $("#month_year").on("change", _getPeriod);

  function _getPeriod(e) {
    if (salaryType == 2 || salaryType == 3) {
      const path =
        salaryType == 2 ? "get_periode_harian" : "get_periode_borongan";
      const val = $(this).val();
      jQuery.get(
        `${base_url}/${path}/${val}?order=desc`,
        function (data, status) {
          $("#periode_ajax").html(data);
        }
      );
    }
  }
});
