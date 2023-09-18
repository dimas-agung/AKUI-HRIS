$(document).ready(function () {
  $(".info_thr").html(
    '<i class="fa fa-warning"></i> Silahkan Klik Tombol "<span class="blink blink_one merah">Tampilkan</span>" Guna menampilkan THR.'
  );
  // loadDataAttendance();

  $('[data-plugin="select_hrm"]').select2($(this).attr("data-options"));
  $('[data-plugin="select_hrm"]').select2({ width: "100%" });

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

  $('#thr_year').on('change', function() {
    const year = $(this).val();
    const dates = $('#tanggal_thr').data('options');

    $('#tanggal_thr').html('');
    dates.filter(d => d.tahun_thr == year).forEach(d => {
      $('#tanggal_thr').append(`<option value="${d.tanggal_thr}">${d.tanggal_thr}</option>`);
    });
  });
});
