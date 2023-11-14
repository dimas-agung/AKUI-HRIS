$(document).ready(function() {
		
	$('[data-plugin="select_hrm"]').select2($(this).attr('data-options'));
	$('[data-plugin="select_hrm"]').select2({ width:'100%' });

	 /* attendance daily report */
	 $(".info_rekap").html('<h3 class="box-title text-uppercase text-bold"> Rekap Lembur</h3><h5><i class="fa fa-warning"></i> Silahkan Klik Tombol "<span class="blink blink-two merah">Tampilkan</span>" Jika Tidak ada data yang tersedia pada tabel, maka silahkan lakukan Proses "<span class="blink blink-one hijau">Rekap Lembur</span>".</h5>');

	 // bulk payments
	// $("#lembur_rekap_proses").submit(function(e){
		
	// 	e.preventDefault();
		
	// 	var obj = $(this), action = obj.attr('name');
		
	// 	$('.save').prop('disabled', true);
	// 	$('.icon-spinner3').show();
		
	// 	var month_year  = jQuery('#month_year').val();
	// 	var company_id  = jQuery('#company_id').val();
	// 	var jenis_gaji  = jQuery('#jenis_gaji').val();
	
		
	// 	$.ajax({
	// 		type: "POST",
	// 		url: e.target.action,
	// 		data: obj.serialize()+"&is_ajax=1&add_type=rekap&form="+action,
	// 		cache: false,
	// 		success: function (JSON) {
				
	// 			if (JSON.error != '') {
				
	// 				alert_fail('Gagal',JSON.error);
	// 				$('.save').prop('disabled', false);
	// 				$('.icon-spinner3').hide();
				
	// 			} else {							

	// 				alert_success('Sukses',JSON.result);
	// 				$('.icon-spinner3').hide();
	// 				$('.save').prop('disabled', false);
	// 			}
	// 		}
	// 	});
	// });

	
	  var xin_table = $('#xin_table_bulanan').DataTable( {       
		// 	scrollX       : true,
		// scrollY		  : true,
		// scrollCollapse : false,			
		// paging         : true,
		
		"bDestroy"        : true,
		"bSort"           : false,
		"aLengthMenu"     : [[10, 30, 50, 100, -1], [10, 30, 50, 100, "All"]],			
		autoWidth         : true,  
		"fixedColumns"    : true,
		"fixedColumns"    : {
			leftColumns   : 1
		},	
		columnDefs: [
			{ "width": "5px", "targets": [0] },
			{ "width": "240px", "targets": [1] },
	    ],
	    "language": {
                    "aria": {
                        "sortAscending" : ": activate to sort column ascending",
                        "sortDescending": ": activate to sort column descending"
                    },
                    "emptyTable": "Tidak ada data yang tersedia pada tabel ini",
                "info": "Menampilkan _START_ sampai _END_ dari _TOTAL_ entri",
                "infoEmpty": "Menampilkan 0 sampai 0 dari 0 entri",
                "infoFiltered": "(disaring dari _MAX_ entri keseluruhan)",
                "lengthMenu": "Tampilkan _MENU_ entri",
                "loadingRecords": "Silahkan Tunggu...",
                "processing": "Sedang memproses...",
                 "search"      : "Pencarian : ", "searchPlaceholder": "Masukan Kata Pencarian ...",
                "zeroRecords": "Tidak ditemukan data yang sesuai",
                "thousands": "'",
                "paginate": {
                    "first": "Pertama",
                    "last": "Terakhir",
                    "next": "Selanjutnya",
                    "previous": "Sebelumnya"
                },
              },
	    dom: 'lBfrtip',
	    "buttons": ['excel'],


	});	
   
	

});