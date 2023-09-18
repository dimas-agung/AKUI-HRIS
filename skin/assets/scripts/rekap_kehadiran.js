$(document).ready(function() {
		
	$('[data-plugin="select_hrm"]').select2($(this).attr('data-options'));
	$('[data-plugin="select_hrm"]').select2({ width:'100%' });

	 /* attendance daily report */
	 $(".info_rekap").html('<h3 class="box-title text-uppercase text-bold"> Rekap Absensi Bulanan</h3><h5><i class="fa fa-warning"></i> Silahkan Klik Tombol "<span class="blink blink-two hijau">Tampilkan</span>" Jika Tidak ada data yang tersedia pada tabel, maka silahkan lakukan Proses "<span class="blink blink-one kuning">Rekap Absensi Bulanan</span>".</h5>');

	 // bulk payments
	$("#attendance_rekap_bulanan_proses").submit(function(e){
		
		e.preventDefault();
		
		var obj = $(this), action = obj.attr('name');
		
		$('.save').prop('disabled', true);
		$('.icon-spinner3').show();
		
		var month_year  = jQuery('#month_year').val();
		var company_id  = jQuery('#company_id').val();		
		var pola_kerja  = jQuery('#pola_kerja').val();

		 $(".info_rekap").html('<h5><i class="fa fa-warning"></i> Silahkan Tunggu, Proses Rekap Absensi Bulanan sedang berlangsung ... </h5>');

		sembunyikan_tabel();

		$.ajax({
			type: "POST",
			url: e.target.action,
			data: obj.serialize()+"&is_ajax=1&add_type=rekap&form="+action,
			cache: false,
			success: function (JSON) {
				
				if (JSON.error != '') {
				
					alert_fail('Gagal',JSON.error);

					$(".info_rekap").html('<h5><i class="fa fa-check"></i> Proses Rekap Absensi Tidak Berhasil Dilakukan ...</h5>');

					$('.save').prop('disabled', false);
					$('.icon-spinner3').hide();
				
				} else {							

					alert_success('Sukses',JSON.result);

					$(".info_rekap").html('<h5><i class="fa fa-check"></i> Proses Rekap Absensi Berhasil Dilakukan ...</h5>');


					$('.icon-spinner3').hide();
					$('.save').prop('disabled', false);
				}
			}
		});
	});

   
	

});