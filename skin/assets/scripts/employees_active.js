$(document).ready(function() {

// ******************************************************************************
// TABEL 
// ******************************************************************************
	var xin_table = $('#xin_table').dataTable({
		"bDestroy": true,
			"aLengthMenu": [[10, 30, 50, 100, -1], [10, 30, 50, 100, "All"]],
		"ajax": {
			url : base_url+"/employees_list_active/",
			type : 'GET'
		},
		
		// dom        : 'lBfrtip',
		// "buttons"  : ['csv', 'excel', 'pdf'], // colvis > if needed
		"columns": [
			{"name": "kolom_1", "className": "text-center"},
			{"name": "kolom_2", "className": "text-center"},
			{"name": "kolom_3", "className": "text-left"},
			{"name": "kolom_4", "className": "text-left"},
			{"name": "kolom_5", "className": "text-left"},
			{"name": "kolom_6", "className": "text-left"},
			{"name": "kolom_7", "className": "text-center"},					
			{"name": "kolom_10", "className": "text-left"}
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

	var xin_my_team_table = $('#xin_my_team_table').dataTable({
		"bDestroy": true,
			"aLengthMenu": [[10, 30, 50, 100, -1], [10, 30, 50, 100, "All"]],
		"ajax": {
			url : base_url+"/myteam_employees_list_active/",
			type : 'GET'
		},
		
		"fnDrawCallback": function(settings){
			$('[data-toggle="tooltip"]').tooltip();          
		}
	});

// ******************************************************************************
// PROSES 
// ******************************************************************************
	
	//  1. TAMBAH
		$("#xin-form").submit(function(e){
			var fd = new FormData(this);
			var obj = $(this), action = obj.attr('name');
			
			fd.append("is_ajax", 1);
			fd.append("add_type", 'employee');
			fd.append("form", action);
			e.preventDefault();

			$('.icon-spinner3').show();
			$('.save').prop('disabled', true);

			$.ajax({
				url: e.target.action,
				type: "POST",
				data:  fd,
				contentType: false,
				cache: false,
				processData:false,
				success: function(JSON)
				{
					if (JSON.error != '') {
						
						alert_fail('Gagal',JSON.error);
						$('.icon-spinner3').hide();
						$('input[name="csrf_hris"]').val(JSON.csrf_hash);
						$('.save').prop('disabled', false);
					} else {
						//alert_success('Sukses',JSON.result);
						$('.icon-spinner3').hide();
						xin_table.api().ajax.reload(function(){ 					
							alert_success('Sukses',JSON.result);
							$('input[name="csrf_hris"]').val(JSON.csrf_hash);
						}, true);
						$('.add-form').removeClass('in');
						$('.select2-selection__rendered').html('-- Silakan Pilih --');
						$('#xin-form')[0].reset(); // To reset form fields
						$('.save').prop('disabled', false);
					}
				},
				error: function() 
				{
					
					alert_fail('Gagal',JSON.error);
					$('input[name="csrf_hris"]').val(JSON.csrf_hash);
					$('.icon-spinner3').hide();
					$('.save').prop('disabled', false);
				} 	        
		   });
		 });
		});

	// ==============================================================
	//  2. EDIT		
		$('.edit-modal-data').on('show.bs.modal', function (event) {
			var button = $(event.relatedTarget);
			var warning_id = button.data('warning_id');
			var modal = $(this);
			$.ajax({
				url : base_url+"/read/",
				type: "GET",
				data: 'jd=1&is_ajax=1&mode=modal&data=warning&warning_id='+warning_id,
				success: function (response) {
					if(response) {
						$("#ajax_modal").html(response);
					}
				}
			});
		});
	
	// ==============================================================
	//  3. HAPUS	
		$("#delete_record").submit(function(e)
		{
			e.preventDefault();
			var obj = $(this), action = obj.attr('name');
			$.ajax({
				type: "POST",
				url: e.target.action,
				data: obj.serialize()+"&is_ajax=2&form="+action,
				cache: false,
				success: function (JSON) {
					if (JSON.error != '') {
						alert_fail('Gagal',JSON.error);
						$('input[name="csrf_hris"]').val(JSON.csrf_hash);
					} else {
						$('.delete-modal').modal('toggle');
						xin_table.api().ajax.reload(function(){ 
							alert_success('Sukses',JSON.result);
							$('input[name="csrf_hris"]').val(JSON.csrf_hash);
						}, true);							
					}
				}
			});
		});
	
	// ==============================================================
	//  4. REPORT  
		$("#ihr_report").submit(function(e)
		{
			e.preventDefault();
			
			 var xin_table2 = $('#xin_table').dataTable({
				"bDestroy": true,
					"aLengthMenu": [[10, 30, 50, 100, -1], [10, 30, 50, 100, "All"]],
				"ajax": {
					url : site_url+"employees_active/employees_list_active/?ihr=true&company_id="+$('#filter_company').val()+"&location_id="+$('#filter_location').val()+"&department_id="+$('#filter_department').val()+"&designation_id="+$('#filter_designation').val(),
					type : 'GET'
				},
				
				// dom: 'lBfrtip',
				// "buttons": ['csv', 'excel', 'pdf'], // colvis > if needed
				"columns": [
					{"name": "kolom_1", "className": "text-center"},
					{"name": "kolom_2", "className": "text-center"},
					{"name": "kolom_3", "className": "text-left"},
					{"name": "kolom_4", "className": "text-left"},
					{"name": "kolom_5", "className": "text-left"},
					{"name": "kolom_6", "className": "text-left"},
					{"name": "kolom_7", "className": "text-center"},
					{"name": "kolom_8", "className": "text-center"},
					
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
		jQuery.get(escapeHtmlSecure(site_url+"employees/filter_company_flocations/"+jQuery(this).val()), function(data, status){
		jQuery('#location_ajaxflt').html(data);
		});
	});

	$( document ).on( "click", ".delete", function() {
		$('input[name=_token]').val($(this).data('record-id'));
		$('#delete_record').attr('action',base_url+'/delete/'+$(this).data('record-id'));
	});