$(document).ready(function() {

	$(".info_report_gaji_borongan").html('<i class="fa fa-warning"></i> Silahkan Klik Tombol "<span class="blink blink_one merah">Tampilkan</span>" Guna menampilkan Laporan <b>Detail Gaji Borongan Periode</b>.');
	// loadDataAttendance();

	$('[data-plugin="select_hrm"]').select2($(this).attr('data-options'));
	$('[data-plugin="select_hrm"]').select2({ width:'100%' }); 
	
	// get periode
	jQuery("#month_year").change(function(){
		jQuery.get(base_url+"/get_periode_borongan/"+jQuery(this).val(), function(data, status){
			jQuery('#periode_ajax').html(data);
		});
	});
		
});