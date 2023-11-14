$(document).ready(function() {

	$('[data-plugin="select_hrm"]').select2($(this).attr('data-options'));
	$('[data-plugin="select_hrm"]').select2({ width:'100%' });

	 /* attendance daily report */
	 $(".info_rekap_harian").html('<h5><i class="fa fa-warning"></i> Silahkan Klik Tombol "<span class="blink blink-two merah">Tampilkan</span>" Jika Tidak ada data yang tersedia pada tabel, maka silahkan lakukan Proses "<span class="blink blink-one hijau">Rekap Absensi</span>".</h5>');

	 $('.date').datepicker({
	    changeMonth: true,
	    changeYear: true,
	    maxDate: '0',
	    dateFormat:'yy-mm-dd',
	    altField: "#date_format",
	    altFormat: js_date_format,
	    yearRange: '1970:' + new Date().getFullYear(),
	    beforeShow: function(input) {
	      $(input).datepicker("widget").show();
	    }
	  });

	  var xin_table = $('#xin_table_harian').DataTable( {
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
			leftColumns   : 2
		},
		dom: 'lBfrtip',
        buttons: [
          'print', {
            extend: 'pdf',
            orientation: 'landscape'
          },
          'excel'
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
	    // dom: 'lBfrtip',
	    // "buttons": ['excel']
	});


});
