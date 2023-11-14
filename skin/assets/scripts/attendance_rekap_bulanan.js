$(document).ready(function() {
		
	$('[data-plugin="select_hrm"]').select2($(this).attr('data-options'));
	$('[data-plugin="select_hrm"]').select2({ width:'100%' });

	 /* attendance daily report */
	 $(".info_rekap").html('<h5><i class="fa fa-warning"></i> Silahkan Klik Tombol "<span class="blink blink-two merah">Tampilkan</span>" Jika Tidak ada data yang tersedia pada tabel, maka silahkan lakukan Proses "<span class="blink blink-one hijau">Rekap Absensi</span>".</h5>');
	

});