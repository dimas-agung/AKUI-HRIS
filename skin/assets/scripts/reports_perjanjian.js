$(document).ready(function() {
   
   var xin_table = $('#xin_perjanjian').dataTable({
        "bDestroy"        : true,
		"bSort"           : false,
		"aLengthMenu"     : [[10, 30, 50, 100, -1], [10, 30, 50, 100, "All"]],			
		autoWidth         : true,  
		// "fixedColumns"    : true,
		// "fixedColumns"    : {
		// 	leftColumns   : 4
		// },	
		"ajax": {
            url : site_url+"reports/report_perjanjian_list/0/0/0/",
            type : 'GET'
        },
		dom: 'lBfrtip',
		buttons: [
          'print', {
            extend: 'pdf',
            orientation: 'landscape'
          },
          'excel'
        ],
		"columns": [
			{"name": "kolom_1", "className": "text-center"},
			{"name": "kolom_2", "className": "text-center"},
			{"name": "kolom_3", "className": "text-center"},
			{"name": "kolom_4", "className": "text-center"},
			{"name": "kolom_5", "className": "text-left"},
			{"name": "kolom_6", "className": "text-left"},
			{"name": "kolom_7", "className": "text-left"},
			{"name": "kolom_8", "className": "text-left"},
			{"name": "kolom_9", "className": "text-right"},
			{"name": "kolom_10", "className": "text-center"},
			
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
		"fnDrawCallback": function(settings){
		$('[data-toggle="tooltip"]').tooltip();          
		}
    });

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

   	$('[data-plugin="select_hrm"]').select2($(this).attr('data-options'));
	$('[data-plugin="select_hrm"]').select2({ width:'100%' });
	
	// get departments
	jQuery("#aj_company").change(function(){
		
	});
		
	/* projects report */
	$("#perjanjian").submit(function(e){
		/*Form Submit*/
		e.preventDefault();

		var jenis_id     = $('#aj_company').val();

		var start_date   = $('#start_date').val();
		var end_date     = $('#end_date').val();
		
		// ===================================================================
		// DATA TABEL
		// ===================================================================
		var xin_table2     = $('#xin_perjanjian').dataTable({
			"bDestroy"        : true,
			"bSort"           : false,
			"aLengthMenu"     : [[10, 30, 50, 100, -1], [10, 30, 50, 100, "All"]],			
			autoWidth         : true,  
			// "fixedColumns"    : true,
			// "fixedColumns"    : {
			// 	leftColumns   : 4
			// },	


			"ajax": {
				url : site_url+"reports/report_perjanjian_list/"+jenis_id+"/"+start_date+"/"+end_date+"/",
				type : 'GET'
			},
			dom: 'lBfrtip',
			buttons: [
	          'print', {
	            extend: 'pdf',
	            orientation: 'landscape'
	          },
	          'excel'
	        ],
			"columns": [
				{"name": "kolom_1", "className": "text-center"},
				{"name": "kolom_2", "className": "text-center"},
				{"name": "kolom_3", "className": "text-center"},
				{"name": "kolom_4", "className": "text-center"},
				{"name": "kolom_5", "className": "text-left"},
				{"name": "kolom_6", "className": "text-left"},
				{"name": "kolom_7", "className": "text-left"},
				{"name": "kolom_8", "className": "text-left"},
				{"name": "kolom_9", "className": "text-right"},
				{"name": "kolom_10", "className": "text-center"},
				
							
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
			"fnDrawCallback": function(settings){
			$('[data-toggle="tooltip"]').tooltip();          
			}
		});
		toastr.success('Data Berhasil Ditampilkan.');
		xin_table2.api().ajax.reload(function(){ }, true);
	});

});