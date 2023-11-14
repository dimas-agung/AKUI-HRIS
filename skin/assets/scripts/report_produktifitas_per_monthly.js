$(document).ready(function() {	
	$('[data-plugin="select_hrm"]').select2($(this).attr('data-options'));
	$('[data-plugin="select_hrm"]').select2({ width:'100%' }); 

	
	 
  var xin_table = $('#xin_table_borongan').DataTable( {       
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
			leftColumns   : 3
		},	
		columnDefs: [
			{ "width": "5px", "targets": [0] },
			{ "width": "140px", "targets": [1] },
			{ "width": "440px", "targets": [2] },
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
	    "buttons": ['excel']		
	});	

  
	
});