$(document).ready(function() {

// ******************************************************************************
// TABEL 
// ******************************************************************************
	var xin_table = $('#xin_table').dataTable({
		"bDestroy": true,
			"aLengthMenu": [[10, 30, 50, 100, -1], [10, 30, 50, 100, "All"]],
		"ajax": {
			url : base_url+"/employees_all_list/",
			type : 'GET'
		},
		
		// dom        : 'lBfrtip',
		// "buttons"  : ['csv', 'excel', 'pdf'], // colvis > if needed

		"columns": [
			{"name": "kolom_1", "className": "text-center"},
			{"name": "kolom_1", "className": "text-center"},
			{"name": "kolom_2", "className": "text-left"},
			{"name": "kolom_3", "className": "text-left"},
			{"name": "kolom_4", "className": "text-left"},
			{"name": "kolom_5", "className": "text-left"},
			{"name": "kolom_6", "className": "text-left"},
			{"name": "kolom_8", "className": "text-left"},
			{"name": "kolom_7", "className": "text-center"},
			
			
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

});
// ******************************************************************************
// PROSES 
// ******************************************************************************
	
	// ==============================================================
	//  4. REPORT  
		$("#ihr_report").submit(function(e)
		{
			e.preventDefault();
			
			 var xin_table2 = $('#xin_table').dataTable({
				"bDestroy": true,
					"aLengthMenu": [[10, 30, 50, 100, -1], [10, 30, 50, 100, "All"]],
				"ajax": {
					url : site_url+"legal/employees_all_list/?ihr=true&company_id="+$('#filter_company').val()+"&location_id="+$('#filter_location').val()+"&department_id="+$('#filter_department').val()+"&designation_id="+$('#filter_designation').val(),
					type : 'GET'
				},
				
				// dom: 'lBfrtip',
				// "buttons": ['csv', 'excel', 'pdf'], // colvis > if needed
				"columns": [
					{"name": "kolom_1", "className": "text-center"},
					{"name": "kolom_1", "className": "text-center"},
					{"name": "kolom_2", "className": "text-left"},
					{"name": "kolom_3", "className": "text-left"},
					{"name": "kolom_4", "className": "text-left"},
					{"name": "kolom_5", "className": "text-left"},
					{"name": "kolom_6", "className": "text-left"},
					{"name": "kolom_8", "className": "text-left"},
					{"name": "kolom_7", "className": "text-center"},
					
					
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
			xin_table2.api().ajax.reload(function(){			
				toastr.success(request_submitted);
			}, true);
		});

// ******************************************************************************
// DATA 
// ******************************************************************************

	$('[data-plugin="select_hrm"]').select2($(this).attr('data-options'));
	$('[data-plugin="select_hrm"]').select2({ width:'100%' }); 

	// Date
	$('.date_of_birth').datepicker({
		changeMonth: true,
		changeYear: true,
		dateFormat:'yy-mm-dd',
		yearRange: '1940:' + new Date().getFullYear()
	});

	// Date
	$('.date_of_joining').datepicker({
	  	changeMonth: true,
	  	changeYear: true,
	  	dateFormat:'yy-mm-dd',
	  	yearRange: '1940:' + ':' + new Date().getFullYear()
	});

	jQuery("#aj_company").change(function(){
		jQuery.get(escapeHtmlSecure(base_url+"/get_company_elocations/"+jQuery(this).val()), function(data, status){
			jQuery('#location_ajax').html(data);
		});
		jQuery.get(escapeHtmlSecure(base_url+"/get_company_office_shifts/"+jQuery(this).val()), function(data, status){
			jQuery('#ajax_office_shift').html(data);
		});
	});

	jQuery("#filter_company").change(function(){
		if(jQuery(this).val() == 0){
			jQuery('#filter_location').prop('selectedIndex', 0);	
			jQuery('#filter_department').prop('selectedIndex', 0);
			jQuery('#filter_designation').prop('selectedIndex', 0);
		}	
		jQuery.get(escapeHtmlSecure(site_url+"legal/filter_company_flocations/"+jQuery(this).val()), function(data, status){
		jQuery('#location_ajaxflt').html(data);
		});
	});

	