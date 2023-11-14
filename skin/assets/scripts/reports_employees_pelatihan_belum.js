$(document).ready(function() {
   
   var xin_table = $('#xin_emp_active').dataTable({
        "bDestroy"        : true,
		"bSort"           : false,
		"aLengthMenu"     : [[10, 30, 50, 100, -1], [10, 30, 50, 100, "All"]],			
		autoWidth         : true,  
		"fixedColumns"    : true,
		"fixedColumns"    : {
			leftColumns   : 4
		},	
		"ajax": {
            url : site_url+"reports/report_employees_pelatihan_belum_list/0/",
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
			{"name": "kolom_1",  "className": "text-center"},
				{"name": "kolom_2",  "className": "text-center"},
				{"name": "kolom_3",  "className": "text-center"},
				{"name": "kolom_4",  "className": "text-center"},
				{"name": "kolom_5",  "className": "text-left"},
				{"name": "kolom_8",  "className": "text-left"},
				{"name": "kolom_6",  "className": "text-center"},
				{"name": "kolom_7",  "className": "text-left"},
				{"name": "kolom_8",  "className": "text-left"},
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

   	$('[data-plugin="select_hrm"]').select2($(this).attr('data-options'));
	$('[data-plugin="select_hrm"]').select2({ width:'100%' });
	
		
	/* projects report */
	$("#employees_pelatihan_sudah").submit(function(e){
		/*Form Submit*/
		e.preventDefault();
		var training_type     = $('#training_type').val();
		
		// ===================================================================
		// DATA TABEL
		// ===================================================================
		var xin_table2     = $('#xin_emp_active').dataTable({
			"bDestroy"        : true,
			"bSort"           : false,
			"aLengthMenu"     : [[10, 30, 50, 100, -1], [10, 30, 50, 100, "All"]],			
			autoWidth         : true,  
			"fixedColumns"    : true,
			"fixedColumns"    : {
				leftColumns   : 4
			},	
			"ajax": {
				url : site_url+"reports/report_employees_pelatihan_belum_list/"+training_type+"/",
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
				{"name": "kolom_1",  "className": "text-center"},
				{"name": "kolom_2",  "className": "text-center"},
				{"name": "kolom_3",  "className": "text-center"},
				{"name": "kolom_4",  "className": "text-center"},
				{"name": "kolom_5",  "className": "text-left"},
				{"name": "kolom_8",  "className": "text-left"},
				{"name": "kolom_6",  "className": "text-center"},
				{"name": "kolom_7",  "className": "text-left"},
				{"name": "kolom_8",  "className": "text-left"},
								
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