$(document).ready(function() {

	$('[data-plugin="select_hrm"]').select2($(this).attr('data-options'));
	$('[data-plugin="select_hrm"]').select2({ width:'100%' });

	 /* attendance daily report */
	 $(".info_rekap_Borongan").html('<h3 class="box-title text-uppercase text-bold"> Rekap Absensi Borongan</h3><h5><i class="fa fa-warning"></i> Silahkan Klik Tombol "<span class="blink blink-two kuning">Rekap Absensi Borongan</span>", Guna melakukan Rekap Absensi Borongan, Proses Rekap Borongan ini akan merekap semua form pengajuan sakit, izin, cuti, dinas, libur, dll.</h5>');

	  // get periode
	 jQuery("#month_year").change(function(){
		jQuery.get(base_url+"/get_periode_borongan/"+jQuery(this).val(), function(data, status){
			jQuery('#periode_ajax').html(data);
		});
	});

	 // bulk payments
	$("#attendance_rekap_borongan_proses").submit(function(e){

		e.preventDefault();

		var obj = $(this), action = obj.attr('name');

		$('.save').prop('disabled', true);
		$('.icon-spinner3').show();


		var company_id  = jQuery('#company_id').val();
		var pola_kerja  = jQuery('#pola_kerja').val();
		var month_year  = jQuery('#month_year').val();
		var periode_id  = jQuery('#periode_id').val();

		 $(".info_rekap_Borongan").html('<h5><i class="fa fa-warning"></i> Silahkan Tunggu ...</h5>');


		$.ajax({
			type: "POST",
			url: e.target.action,
			data: obj.serialize()+"&is_ajax=1&add_type=rekap&form="+action,
			cache: false,
			success: function (JSON) {

				if (JSON.error != '') {

					alert_fail('Gagal',JSON.error);
					$('.save').prop('disabled', false);
					$('.icon-spinner3').hide();

				} else {

					alert_success('Sukses',JSON.result);
					$(".info_rekap_Borongan").html('<h5><i class="fa fa-check"></i> Proses Rekap Absensi Borongan Berhasil Dilakukan </h5>');
					$('.icon-spinner3').hide();
					$('.save').prop('disabled', false);
				}
			}
		});
	});




});
