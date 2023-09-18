$(document).ready(function() {
   
   var xin_table = $('#xin_emp_active').dataTable({
        "bDestroy"       : true,
		"bFilter"        : true,
		"bAutoWidth"     : false,
		"bLengthChange"  : true,
		"iDisplayLength" : 10,
		"aLengthMenu"    : [[10, 30, 50, 100, -1], [10, 30, 50, 100, "All"]],
		"ajax": {
            url : site_url+"reports/report_karyawan_borongan_list/0/0/0/",
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
			
			{"name": "kolom_5", "className": "text-left"},
			{"name": "kolom_6", "className": "text-left"},
			{"name": "kolom_7", "className": "text-left"},
			{"name": "kolom_8", "className": "text-center"},
			{"name": "kolom_9", "className": "text-center"},
			{"name": "kolom_10", "className": "text-center"},			
			{"name": "kolom_11", "className": "text-center"},
			{"name": "kolom_12", "className": "text-center"},
			{"name": "kolom_13", "className": "text-left"},					
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
	
	// get departments
	jQuery("#aj_company").change(function(){
		var c_id = jQuery(this).val();
		
		jQuery.get(base_url+"/get_workstations_designations/"+c_id, function(data, status){
			jQuery('#workstation_ajax').html(data);			
		});
		
		if(c_id == 0){
			jQuery.get(base_url+"/get_designations_workstations/"+jQuery(this).val(), function(data, status){
				jQuery('#designation_ajax').html(data);
			});
		}
	});
		
	/* projects report */
	$("#employees_active").submit(function(e){
		/*Form Submit*/
		e.preventDefault();
		var company_id     = $('#aj_company').val();
		var workstation_id = $('#ajx_workstation').val();
		var designation_id = $('#designation_id').val();
		// ===================================================================
		// DATA TABEL
		// ===================================================================
		var xin_table2     = $('#xin_emp_active').dataTable({
			"bDestroy"       : true,
			"bFilter"        : true,
			"bAutoWidth"     : false,
			"bLengthChange"  : true,
			"iDisplayLength" : 10,
			"aLengthMenu"    : [[10, 30, 50, 100, -1], [10, 30, 50, 100, "All"]],
			"ajax": {
				url : site_url+"reports/report_karyawan_borongan_list/"+company_id+"/"+workstation_id+"/"+designation_id+"/",
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
			
			{"name": "kolom_5", "className": "text-left"},
			{"name": "kolom_6", "className": "text-left"},
			{"name": "kolom_7", "className": "text-left"},
			{"name": "kolom_8", "className": "text-center"},
			{"name": "kolom_9", "className": "text-center"},
			{"name": "kolom_10", "className": "text-center"},			
			{"name": "kolom_11", "className": "text-center"},
			{"name": "kolom_12", "className": "text-center"},
			{"name": "kolom_13", "className": "text-left"},						
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