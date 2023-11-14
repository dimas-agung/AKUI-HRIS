	$(document).ready(function() {

		$(".info_report_gaji_harian").html('<i class="fa fa-warning"></i> Silahkan Klik Tombol "<span class="blink blink_one merah">Cari</span>" Guna menampilkan Gaji Harian.');
		// loadDataAttendance();

		$('[data-plugin="select_hrm"]').select2($(this).attr('data-options'));
		$('[data-plugin="select_hrm"]').select2({ width:'100%' }); 
		
		$('.attendance_date').datepicker({
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

		
});

$( document ).on( "click", ".delete", function() {
$('input[name=_token]').val($(this).data('record-id'));
$('#delete_record').attr('action',base_url+'/payslip_delete/'+$(this).data('record-id'))+'/';
});
