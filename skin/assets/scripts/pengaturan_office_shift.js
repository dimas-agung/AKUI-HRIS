$(document).ready(function() {
	var xin_table = $('#xin_table').dataTable({
		"bDestroy": true,
		"fixedColumns"    : true,
		"fixedColumns"    : {
			leftColumns   : 2
		},
		
		"iDisplayLength": 30,
		"aLengthMenu": [[30, 50, 100, -1], [30, 50, 100, "All"]],

		"ajax": {
			url : site_url+"pengaturan/office_shift_list/",
			type : 'GET'
		},
		"columns": [
				{"name": "no", "className": "text-left"},
				{"name": "no", "className": "text-left"},
				
				{"name": "no", "className": "text-center"},
				{"name": "no", "className": "text-center"},
				{"name": "no", "className": "text-center"},
				{"name": "no", "className": "text-center"},
				{"name": "no", "className": "text-center"},
				{"name": "no", "className": "text-center"},
				{"name": "no", "className": "text-center"},
				{"name": "no", "className": "text-center"},
				{"name": "no", "className": "text-center"},
				{"name": "no", "className": "text-center"},
				{"name": "no", "className": "text-center"},
				{"name": "no", "className": "text-center"},
				{"name": "no", "className": "text-center"},
				{"name": "no", "className": "text-center"},
				{"name": "no", "className": "text-center"},
				{"name": "no", "className": "text-center"},
				{"name": "no", "className": "text-center"},
				{"name": "no", "className": "text-center"},
				{"name": "no", "className": "text-center"},
				{"name": "no", "className": "text-center"},
				{"name": "no", "className": "text-center"},
				{"name": "no", "className": "text-center"},
				{"name": "no", "className": "text-center"},
				{"name": "no", "className": "text-center"},
				{"name": "no", "className": "text-center"},
				{"name": "no", "className": "text-center"},
				{"name": "no", "className": "text-center"},
				{"name": "no", "className": "text-center"},
				{"name": "no", "className": "text-center"},
				{"name": "no", "className": "text-center"},
				{"name": "no", "className": "text-center"}				
			
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
	    // dom        : 'lBfrtip',
		"buttons"  : ['excel', 'pdf'], // colvis > if needed
		"rowCallback": function(row, data, index){													
			if ( data[2] == 'L' ) {
				$(row).find('td:eq(2)').css('background-color', '#f3c3c3');
				$(row).find('td:eq(2)').css('color', 'black');	
			
			}
			if ( data[3] == 'L' ) {
				$(row).find('td:eq(3)').css('background-color', '#f3c3c3');
				$(row).find('td:eq(3)').css('color', 'black');	

			}
			if ( data[4] == 'L' ) {
				$(row).find('td:eq(4)').css('background-color', '#f3c3c3');
				$(row).find('td:eq(4)').css('color', 'black');	

			} 
			if ( data[5] == 'L' ) {
				$(row).find('td:eq(5)').css('background-color', '#f3c3c3');
				$(row).find('td:eq(5)').css('color', 'black');	

			} 
			if ( data[6] == 'L' ) {
				$(row).find('td:eq(6)').css('background-color', '#f3c3c3');
				$(row).find('td:eq(6)').css('color', 'black');	

			} 
			if ( data[7] == 'L' ) {
				$(row).find('td:eq(7)').css('background-color', '#f3c3c3');
				$(row).find('td:eq(7)').css('color', 'black');	

			} 
			if ( data[8] == 'L' ) {
				$(row).find('td:eq(8)').css('background-color', '#f3c3c3');
				$(row).find('td:eq(8)').css('color', 'black');	

			} 
			if ( data[9] == 'L' ) {
				$(row).find('td:eq(9)').css('background-color', '#f3c3c3');
				$(row).find('td:eq(9)').css('color', 'black');	

			} 
			if ( data[10] == 'L' ) {
				$(row).find('td:eq(10)').css('background-color', '#f3c3c3');
				$(row).find('td:eq(10)').css('color', 'black');	
			
			} 
			if ( data[11] == 'L' ) {
				$(row).find('td:eq(11)').css('background-color', '#f3c3c3');
				$(row).find('td:eq(11)').css('color', 'black');	
			
			//==
			} 
			if ( data[12] == 'L' ) {
				$(row).find('td:eq(12)').css('background-color', '#f3c3c3');
				$(row).find('td:eq(12)').css('color', 'black');	

			} 
			if ( data[13] == 'L' ) {
				$(row).find('td:eq(13)').css('background-color', '#f3c3c3');
				$(row).find('td:eq(13)').css('color', 'black');	

			} 
			if ( data[14] == 'L' ) {
				$(row).find('td:eq(14)').css('background-color', '#f3c3c3');
				$(row).find('td:eq(14)').css('color', 'black');	

			} 
			if ( data[15] == 'L' ) {
				$(row).find('td:eq(15)').css('background-color', '#f3c3c3');
				$(row).find('td:eq(15)').css('color', 'black');	

			} 
			if ( data[16] == 'L' ) {
				$(row).find('td:eq(16)').css('background-color', '#f3c3c3');
				$(row).find('td:eq(16)').css('color', 'black');	

			} 

			if ( data[17] == 'L' ) {
				$(row).find('td:eq(17)').css('background-color', '#f3c3c3');
				$(row).find('td:eq(17)').css('color', 'black');	

			}  

			if ( data[18] == 'L' ) {
				$(row).find('td:eq(18)').css('background-color', '#f3c3c3');
				$(row).find('td:eq(18)').css('color', 'black');	

			} 

			if ( data[19] == 'L' ) {
				$(row).find('td:eq(19)').css('background-color', '#f3c3c3');
				$(row).find('td:eq(19)').css('color', 'black');	
			
			} 
			if ( data[20] == 'L' ) {
				$(row).find('td:eq(20)').css('background-color', '#f3c3c3');
				$(row).find('td:eq(20)').css('color', 'black');	
			}
			if ( data[21] == 'L' ) {
				$(row).find('td:eq(21)').css('background-color', '#f3c3c3');
				$(row).find('td:eq(21)').css('color', 'black');	
			}
			if ( data[22] == 'L' ) {
				$(row).find('td:eq(22)').css('background-color', '#f3c3c3');
				$(row).find('td:eq(22)').css('color', 'black');	
			}					
			if ( data[23] == 'L' ) {
				$(row).find('td:eq(23)').css('background-color', '#f3c3c3');
				$(row).find('td:eq(23)').css('color', 'black');	
			}
			if ( data[24] == 'L' ) {
				$(row).find('td:eq(24)').css('background-color', '#f3c3c3');
				$(row).find('td:eq(24)').css('color', 'black');	
			}
			if ( data[25] == 'L' ) {
				$(row).find('td:eq(25)').css('background-color', '#f3c3c3');
				$(row).find('td:eq(25)').css('color', 'black');	
			}
			if ( data[26] == 'L' ) {
				$(row).find('td:eq(26)').css('background-color', '#f3c3c3');
				$(row).find('td:eq(26)').css('color', 'black');	
			}
			if ( data[27] == 'L' ) {
				$(row).find('td:eq(27)').css('background-color', '#f3c3c3');
				$(row).find('td:eq(27)').css('color', 'black');	
			}
			if ( data[28] == 'L' ) {
				$(row).find('td:eq(28)').css('background-color', '#f3c3c3');
				$(row).find('td:eq(28)').css('color', 'black');	
			}
			if ( data[29] == 'L' ) {
				$(row).find('td:eq(29)').css('background-color', '#f3c3c3');
				$(row).find('td:eq(29)').css('color', 'black');	
			}
			if ( data[30] == 'L' ) {
				$(row).find('td:eq(30)').css('background-color', '#f3c3c3');
				$(row).find('td:eq(30)').css('color', 'black');	
			}
			if ( data[31] == 'L' ) {
				$(row).find('td:eq(31)').css('background-color', '#f3c3c3');
				$(row).find('td:eq(31)').css('color', 'black');	
			}
			if ( data[32] == 'L' ) {
				$(row).find('td:eq(32)').css('background-color', '#f3c3c3');
				$(row).find('td:eq(32)').css('color', 'black');	
			}
			
		}

			
	});

	$('[data-plugin="select_hrm"]').select2($(this).attr('data-options'));
	$('[data-plugin="select_hrm"]').select2({ width:'100%' });


	jQuery("#aj_company").change(function(){			
		jQuery.get(base_url+"/get_employees_office/"+jQuery(this).val(), function(data, status){
			jQuery('#employee_ajax').html(data);
		});
	});

	// Month & Year
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


	/* Delete data */
	$("#delete_record").submit(function(e){
		/*Form Submit*/
		e.preventDefault();
		var obj = $(this), action = obj.attr('name');
		$.ajax({
			type: "POST",
			url: e.target.action,
			data: obj.serialize()+"&is_ajax=2&type=delete&form="+action,
			cache: false,
			success: function (JSON) {
				if (JSON.error != '') {
					alert_fail('Gagal',JSON.error);
					$('input[name="csrf_hris"]').val(JSON.csrf_hash);
				} else {
					$('.delete-modal').modal('toggle');
					xin_table.api().ajax.reload(function(){ 
						alert_success('Sukses',JSON.result);
					}, true);
					$('input[name="csrf_hris"]').val(JSON.csrf_hash);							
				}
			}
		});
	});

	// edit
	$('.edit-modal-data-shift').on('show.bs.modal', function (event) {
		var button = $(event.relatedTarget);
		var office_shift_id = button.data('office_shift_id');
		var modal = $(this);
		$.ajax({
			url : site_url+"pengaturan/read_shift_record/",
			type: "GET",
			data: 'jd=1&is_ajax=1&mode=modal&data=shift&office_shift_id='+office_shift_id,
			success: function (response) {
				if(response) {
					$("#ajax_modal_shift").html(response);
				}
			}
		});
	});

	/* Add data */ /*Form Submit*/
	$("#xin-form").submit(function(e){
		
		e.preventDefault();
		
		var obj = $(this), action = obj.attr('name');
		$('.save').prop('disabled', true);

		$.ajax({
			url: e.target.action,
			type: "POST",			
			data: obj.serialize()+"&is_ajax=1&add_type=office_shift&form="+action,			
			cache: false,			
			success: function (JSON) {
				
				if (JSON.error != '') {
				
					alert_fail('Gagal',JSON.error);
					$('input[name="csrf_hris"]').val(JSON.csrf_hash);
					$('.save').prop('disabled', false);
				
				} else {
					
					var xin_table = $('#xin_table').dataTable({
						"bDestroy": true,
						"fixedColumns"    : true,
						"fixedColumns"    : {
							leftColumns   : 2
						},
						
						"iDisplayLength": 30,
						"aLengthMenu": [[30, 50, 100, -1], [30, 50, 100, "All"]],

						"ajax": {
							url : site_url+"pengaturan/office_shift_list/",
							type : 'GET'
						},
						"columns": [
								{"name": "no", "className": "text-left"},
								{"name": "no", "className": "text-left"},
								
								{"name": "no", "className": "text-center"},
								{"name": "no", "className": "text-center"},
								{"name": "no", "className": "text-center"},
								{"name": "no", "className": "text-center"},
								{"name": "no", "className": "text-center"},
								{"name": "no", "className": "text-center"},
								{"name": "no", "className": "text-center"},
								{"name": "no", "className": "text-center"},
								{"name": "no", "className": "text-center"},
								{"name": "no", "className": "text-center"},
								{"name": "no", "className": "text-center"},
								{"name": "no", "className": "text-center"},
								{"name": "no", "className": "text-center"},
								{"name": "no", "className": "text-center"},
								{"name": "no", "className": "text-center"},
								{"name": "no", "className": "text-center"},
								{"name": "no", "className": "text-center"},
								{"name": "no", "className": "text-center"},
								{"name": "no", "className": "text-center"},
								{"name": "no", "className": "text-center"},
								{"name": "no", "className": "text-center"},
								{"name": "no", "className": "text-center"},
								{"name": "no", "className": "text-center"},
								{"name": "no", "className": "text-center"},
								{"name": "no", "className": "text-center"},
								{"name": "no", "className": "text-center"},
								{"name": "no", "className": "text-center"},
								{"name": "no", "className": "text-center"},
								{"name": "no", "className": "text-center"},
								{"name": "no", "className": "text-center"},
								{"name": "no", "className": "text-center"}				
							
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
					    // dom        : 'lBfrtip',
						"buttons"  : ['excel', 'pdf'], // colvis > if needed
						"rowCallback": function(row, data, index)
						{
							if ( data[2] == 'L' ) {
								$(row).find('td:eq(2)').css('background-color', '#f3c3c3');
								$(row).find('td:eq(2)').css('color', 'black');	
							
							}
							if ( data[3] == 'L' ) {
								$(row).find('td:eq(3)').css('background-color', '#f3c3c3');
								$(row).find('td:eq(3)').css('color', 'black');	

							}
							if ( data[4] == 'L' ) {
								$(row).find('td:eq(4)').css('background-color', '#f3c3c3');
								$(row).find('td:eq(4)').css('color', 'black');	

							} 
							if ( data[5] == 'L' ) {
								$(row).find('td:eq(5)').css('background-color', '#f3c3c3');
								$(row).find('td:eq(5)').css('color', 'black');	

							} 
							if ( data[6] == 'L' ) {
								$(row).find('td:eq(6)').css('background-color', '#f3c3c3');
								$(row).find('td:eq(6)').css('color', 'black');	

							} 
							if ( data[7] == 'L' ) {
								$(row).find('td:eq(7)').css('background-color', '#f3c3c3');
								$(row).find('td:eq(7)').css('color', 'black');	

							} 
							if ( data[8] == 'L' ) {
								$(row).find('td:eq(8)').css('background-color', '#f3c3c3');
								$(row).find('td:eq(8)').css('color', 'black');	

							} 
							if ( data[9] == 'L' ) {
								$(row).find('td:eq(9)').css('background-color', '#f3c3c3');
								$(row).find('td:eq(9)').css('color', 'black');	

							} 
							if ( data[10] == 'L' ) {
								$(row).find('td:eq(10)').css('background-color', '#f3c3c3');
								$(row).find('td:eq(10)').css('color', 'black');	
							
							} 
							if ( data[11] == 'L' ) {
								$(row).find('td:eq(11)').css('background-color', '#f3c3c3');
								$(row).find('td:eq(11)').css('color', 'black');	
							
							//==
							} 
							if ( data[12] == 'L' ) {
								$(row).find('td:eq(12)').css('background-color', '#f3c3c3');
								$(row).find('td:eq(12)').css('color', 'black');	

							} 
							if ( data[13] == 'L' ) {
								$(row).find('td:eq(13)').css('background-color', '#f3c3c3');
								$(row).find('td:eq(13)').css('color', 'black');	

							} 
							if ( data[14] == 'L' ) {
								$(row).find('td:eq(14)').css('background-color', '#f3c3c3');
								$(row).find('td:eq(14)').css('color', 'black');	

							} 
							if ( data[15] == 'L' ) {
								$(row).find('td:eq(15)').css('background-color', '#f3c3c3');
								$(row).find('td:eq(15)').css('color', 'black');	

							} 
							if ( data[16] == 'L' ) {
								$(row).find('td:eq(16)').css('background-color', '#f3c3c3');
								$(row).find('td:eq(16)').css('color', 'black');	

							} 

							if ( data[17] == 'L' ) {
								$(row).find('td:eq(17)').css('background-color', '#f3c3c3');
								$(row).find('td:eq(17)').css('color', 'black');	

							}  

							if ( data[18] == 'L' ) {
								$(row).find('td:eq(18)').css('background-color', '#f3c3c3');
								$(row).find('td:eq(18)').css('color', 'black');	

							} 

							if ( data[19] == 'L' ) {
								$(row).find('td:eq(19)').css('background-color', '#f3c3c3');
								$(row).find('td:eq(19)').css('color', 'black');	
							
							} 
							if ( data[20] == 'L' ) {
								$(row).find('td:eq(20)').css('background-color', '#f3c3c3');
								$(row).find('td:eq(20)').css('color', 'black');	
							}
							if ( data[21] == 'L' ) {
								$(row).find('td:eq(21)').css('background-color', '#f3c3c3');
								$(row).find('td:eq(21)').css('color', 'black');	
							}
							if ( data[22] == 'L' ) {
								$(row).find('td:eq(22)').css('background-color', '#f3c3c3');
								$(row).find('td:eq(22)').css('color', 'black');	
							}					
							if ( data[23] == 'L' ) {
								$(row).find('td:eq(23)').css('background-color', '#f3c3c3');
								$(row).find('td:eq(23)').css('color', 'black');	
							}
							if ( data[24] == 'L' ) {
								$(row).find('td:eq(24)').css('background-color', '#f3c3c3');
								$(row).find('td:eq(24)').css('color', 'black');	
							}
							if ( data[25] == 'L' ) {
								$(row).find('td:eq(25)').css('background-color', '#f3c3c3');
								$(row).find('td:eq(25)').css('color', 'black');	
							}
							if ( data[26] == 'L' ) {
								$(row).find('td:eq(26)').css('background-color', '#f3c3c3');
								$(row).find('td:eq(26)').css('color', 'black');	
							}
							if ( data[27] == 'L' ) {
								$(row).find('td:eq(27)').css('background-color', '#f3c3c3');
								$(row).find('td:eq(27)').css('color', 'black');	
							}
							if ( data[28] == 'L' ) {
								$(row).find('td:eq(28)').css('background-color', '#f3c3c3');
								$(row).find('td:eq(28)').css('color', 'black');	
							}
							if ( data[29] == 'L' ) {
								$(row).find('td:eq(29)').css('background-color', '#f3c3c3');
								$(row).find('td:eq(29)').css('color', 'black');	
							}
							if ( data[30] == 'L' ) {
								$(row).find('td:eq(30)').css('background-color', '#f3c3c3');
								$(row).find('td:eq(30)').css('color', 'black');	
							}
							if ( data[31] == 'L' ) {
								$(row).find('td:eq(31)').css('background-color', '#f3c3c3');
								$(row).find('td:eq(31)').css('color', 'black');	
							}
							if ( data[32] == 'L' ) {
								$(row).find('td:eq(32)').css('background-color', '#f3c3c3');
								$(row).find('td:eq(32)').css('color', 'black');	
							}
							
						}							
					});
					
					xin_table.api().ajax.reload(function(){ 
						alert_success('Sukses',JSON.result);
					}, true);

					$('input[name="csrf_hris"]').val(JSON.csrf_hash);
  					$('.add-form').removeClass('in');
  					$('.select2-selection__rendered').html('--Select--');
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

$( document ).on( "click", ".delete", function() {
	$('input[name=_token]').val($(this).data('record-id'));
	$('#delete_record').attr('action',site_url+'pengaturan/delete_shift/'+$(this).data('record-id'));
});

$( document ).on( "click", ".default-shift", function(e) {
	var officeshift_id = $(this).data('office_shift_id');

	e.preventDefault();
	var obj = jQuery(this), action = obj.attr('name');
	jQuery('.save').prop('disabled', true);
	$('.icon-spinner3').show();

	$.ajax({
	type: "GET",
	url: site_url+"pengaturan/default_shift/?office_shift_id="+officeshift_id,
		success: function (JSON) {
			if (JSON.error != '') {
						
				alert_fail('Gagal',JSON.error);
				$('input[name="csrf_hris"]').val(JSON.csrf_hash);
				jQuery('.save').prop('disabled', false);
			} else {
				xin_table2.api().ajax.reload(function(){ 
					
					alert_success('Sukses',JSON.result);
					$('input[name="csrf_hris"]').val(JSON.csrf_hash);
				}, true);
				$('.icon-spinner3').hide();
				// jQuery('#employee_update_commissions')[0].reset(); // To reset form fields
				jQuery('.save').prop('disabled', false);
			}

			// var xin_table2 = $('#xin_table').dataTable({
			// 	"bDestroy": true,
			// 	"fixedColumns"    : true,
			// 	"fixedColumns"    : {
			// 		leftColumns   : 2
			// 	},
			// 	"iDisplayLength": 30,
			// 	"aLengthMenu": [[30, 50, 100, -1], [30, 50, 100, "All"]],
			// 	"ajax": {
			// 		url : site_url+"pengaturan/office_shift_list/",
			// 		type : 'GET'
			// 	},
			// 	"columns": [
			// 		{"name": "no", "className": "text-left"},
			// 		{"name": "no", "className": "text-left"},
					
			// 		{"name": "no", "className": "text-center"},
			// 		{"name": "no", "className": "text-center"},
			// 		{"name": "no", "className": "text-center"},
			// 		{"name": "no", "className": "text-center"},
			// 		{"name": "no", "className": "text-center"},
			// 		{"name": "no", "className": "text-center"},
			// 		{"name": "no", "className": "text-center"},
			// 		{"name": "no", "className": "text-center"},
			// 		{"name": "no", "className": "text-center"},
			// 		{"name": "no", "className": "text-center"},
			// 		{"name": "no", "className": "text-center"},
			// 		{"name": "no", "className": "text-center"},
			// 		{"name": "no", "className": "text-center"},
			// 		{"name": "no", "className": "text-center"},
			// 		{"name": "no", "className": "text-center"},
			// 		{"name": "no", "className": "text-center"},
			// 		{"name": "no", "className": "text-center"},
			// 		{"name": "no", "className": "text-center"},
			// 		{"name": "no", "className": "text-center"},
			// 		{"name": "no", "className": "text-center"},
			// 		{"name": "no", "className": "text-center"},
			// 		{"name": "no", "className": "text-center"},
			// 		{"name": "no", "className": "text-center"},
			// 		{"name": "no", "className": "text-center"},
			// 		{"name": "no", "className": "text-center"},
			// 		{"name": "no", "className": "text-center"},
			// 		{"name": "no", "className": "text-center"},
			// 		{"name": "no", "className": "text-center"},
			// 		{"name": "no", "className": "text-center"},
			// 		{"name": "no", "className": "text-center"},
			// 		{"name": "no", "className": "text-center"}				
					
			//     ],
			//     "buttons"  : ['csv', 'excel', 'pdf'], // colvis > if needed
			// 	"fnDrawCallback": function(settings){
			// 		$('[data-toggle="tooltip"]').tooltip();          
			// 	}
			// });
			// xin_table2.api().ajax.reload(function(){ 
			// 	alert_success('Sukses',JSON.result);
			// }, true);
		}
	});
 });
