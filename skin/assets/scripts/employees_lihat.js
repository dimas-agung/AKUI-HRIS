$(document).ready(function(){		

	// ******************************************************************************
	// TABEL 
	// ******************************************************************************


		$('.view-modal-data').on('show.bs.modal', function (event) {
			var button = $(event.relatedTarget);
			var xfield_id = button.data('xfield_id');
			var field_type = button.data('field_type');
			var field_key = '';
			
			if(field_type == 'awards'){
				var view_info  = 'view_award';
				var field_key  = 'award_id';

			} else if(field_type == 'warning'){
				var view_info  = 'view_warning';
				var field_key  = 'warning_id';
			
			} else if(field_type == 'travel'){
				var view_info  = 'view_travel';
				var field_key  = 'travel_id';

			} else if(field_type == 'aset'){
				var view_info  = 'view_aset';
				var field_key  = 'aset_id';
			
			} else if(field_type == 'transfers'){
				var view_info  = 'view_transfer';
				var field_key  = 'transfer_id';
			
			} else if(field_type == 'promotion'){
				var view_info  = 'view_promotion';
				var field_key  = 'promotion_id';
			
			} else if(field_type == 'demotion'){
				var view_info  = 'view_demotion';
				var field_key  = 'demotion_id';		

			}
			
			var modal = $(this);
			$.ajax({
			url :  site_url+field_type+"/read/",
			type: "GET",
			data: 'jd=1&is_ajax=1&mode=view_modal&data='+view_info+'&'+field_key+'='+xfield_id,
			success: function (response) {
				if(response) {
					$("#ajax_modal_view").html(response);
				}
			}
			});
		});
		
		$('.xin_hris_table').DataTable();

		// 01. Penghargaan
			var xin_table_award = $('#xin_table_award').dataTable({
		        "bDestroy": true,
				"ajax": {
		            url : site_url+"employees_active/award/"+$('#user_id').val(),
		            type : 'GET'
		        },
		        "columns": [
					{"name": "kolom_1", "className": "text-center"},
					{"name": "kolom_2", "className": "text-center"},
					{"name": "kolom_3", "className": "text-center"},
					{"name": "kolom_4", "className": "text-left"},
					{"name": "kolom_5", "className": "text-left"},			
					{"name": "kolom_6", "className": "text-left"}							
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

		// 02. Peringatan
			var xin_table_warning = $('#xin_table_warning').dataTable({
		        "bDestroy": true,
				"ajax": {
		            url : site_url+"employees_active/warning/"+$('#user_id').val(),
		            type : 'GET'
		        },
		        "columns": [
					{"name": "kolom_1", "className": "text-center"},
					{"name": "kolom_2", "className": "text-center"},
					{"name": "kolom_3", "className": "text-center"},
					{"name": "kolom_4", "className": "text-left"},
					{"name": "kolom_5", "className": "text-left"},			
					{"name": "kolom_6", "className": "text-left"}							
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

		// 03. Dinas
			var xin_table_travel = $('#xin_table_travel').dataTable({
		        "bDestroy": true,
				"ajax": {
		            url : site_url+"employees_active/travel/"+$('#user_id').val(),
		            type : 'GET'
		        },
		        "columns": [
					{"name": "kolom_1", "className": "text-center"},
					{"name": "kolom_2", "className": "text-center"},
					{"name": "kolom_3", "className": "text-center"},
					{"name": "kolom_4", "className": "text-left"},
					{"name": "kolom_5", "className": "text-left"},			
					{"name": "kolom_6", "className": "text-left"}							
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

		// 04. Aset
			var xin_table_aset = $('#xin_table_aset').dataTable({
		        "bDestroy": true,
				"ajax": {
		            url : site_url+"employees_active/aset/"+$('#user_id').val(),
		            type : 'GET'
		        },
		        "columns": [
					{"name": "kolom_1", "className": "text-center"},
					{"name": "kolom_2", "className": "text-center"},
					{"name": "kolom_3", "className": "text-center"},
					{"name": "kolom_4", "className": "text-center"},
					{"name": "kolom_5", "className": "text-left"},			
					{"name": "kolom_6", "className": "text-left"},
					{"name": "kolom_7", "className": "text-left"},							
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

		// 05. Mutasi
			var xin_table_transfer = $('#xin_table_transfer').dataTable({
		        "bDestroy": true,
				"ajax": {
		            url : site_url+"employees_active/transfers/"+$('#user_id').val(),
		            type : 'GET'
		        },
		        "columns": [
					{"name": "kolom_1", "className": "text-center"},
					{"name": "kolom_2", "className": "text-center"},
					{"name": "kolom_3", "className": "text-left"},
					{"name": "kolom_4", "className": "text-left"},
					{"name": "kolom_5", "className": "text-left"},	
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

		// 06. Promosi
			var xin_table_promotion = $('#xin_table_promotion').dataTable({
		        "bDestroy": true,
				"ajax": {
		            url : site_url+"employees_active/promotion/"+$('#user_id').val(),
		            type : 'GET'
		        },
		        "columns": [
					{"name": "kolom_1", "className": "text-center"},
					{"name": "kolom_2", "className": "text-center"},
					{"name": "kolom_3", "className": "text-left"},
					{"name": "kolom_4", "className": "text-left"},
					{"name": "kolom_5", "className": "text-left"},	
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

		
	// ******************************************************************************
	// PROSES 
	// ******************************************************************************
	
		// 01. EDIT
			
			$('.edit-modal-data').on('show.bs.modal', function (event) 
			{
				var button    = $(event.relatedTarget);
				var field_id  = button.data('field_id');
				var field_tpe = button.data('field_type');
				
				if(field_tpe == 'contact'){
					var field_add = '&data=emp_contact&type=emp_contact&';	

				} else if(field_tpe == 'qualification'){
					var field_add = '&data=emp_qualification&type=emp_qualification&';
				
				} else if(field_tpe == 'work_experience'){
					var field_add = '&data=emp_work_experience&type=emp_work_experience&';
				
				}
				
				var modal = $(this);
				$.ajax({
					url: site_url+'employees/dialog_'+field_tpe+'/',
					type: "GET",
					data: 'jd=1'+field_add+'field_id='+field_id,
					success: function (response) {
						if(response) {
							$("#ajax_modal").html(response);
						}
					}
				});
		    });

			$("#basic_info").submit(function(e) 
			{
				var fd = new FormData(this);
				var obj = $(this), action = obj.attr('name');
				fd.append("is_ajax", 1);
				fd.append("type", 'basic_info');
				fd.append("data", 'basic_info');
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
							toastr.clear();
							$('#hrload-img').hide();
							alert_fail('Gagal',JSON.error);
							$('.icon-spinner3').hide();
							$('input[name="csrf_hris"]').val(JSON.csrf_hash);
							$('.save').prop('disabled', false);
						} else {
							toastr.clear();
							$('#hrload-img').hide();
							alert_success('Sukses',JSON.result);
							$('.icon-spinner3').hide();
							$('input[name="csrf_hris"]').val(JSON.csrf_hash);
							$('.save').prop('disabled', false);
						}
					},
					error: function() 
					{
						toastr.clear();
						$('#hrload-img').hide();
						alert_fail('Gagal',JSON.error);
						$('input[name="csrf_hris"]').val(JSON.csrf_hash);
						$('.icon-spinner3').hide();
						$('.save').prop('disabled', false);
					} 	        
			   });
			});  
	    
		// 02. ADD

			$("#basic_infoddd").submit(function(e)
			{
				
				e.preventDefault();
				var obj = $(this), action = obj.attr('name');
				$('.save').prop('disabled', true);
				$('.icon-spinner3').show();
				$.ajax({
					type: "POST",
					url: e.target.action,
					data: obj.serialize()+"&is_ajax=1&data=basic_info&type=basic_info&form="+action,
					cache: false,
					success: function (JSON) {
						if (JSON.error != '') {
							//toastr.clear();
							//$('#hrload-img').hide();
							alert_fail('Gagal',JSON.error);
							$('.icon-spinner3').hide();
							$('input[name="csrf_hris"]').val(JSON.csrf_hash);
							$('.save').prop('disabled', false);
						} else {
							//toastr.clear();
							//$('#hrload-img').hide();
							alert_success('Sukses',JSON.result);
							$('.icon-spinner3').hide();
							$('input[name="csrf_hris"]').val(JSON.csrf_hash);
							$('.save').prop('disabled', false);
						}
					}
				});
			});

			/* Update profile picture */
			$("#f_profile_picture").submit(function(e) 
			{
				var fd = new FormData(this);
				var user_id = $('#user_id').val();
				var session_id = $('#session_id').val();
				$('.icon-spinner3').show();
				var obj = $(this), action = obj.attr('name');
				fd.append("is_ajax", 2);
				fd.append("type", 'profile_picture');
				fd.append("data", 'profile_picture');
				fd.append("form", action);
				e.preventDefault();
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
							$('.save').prop('disabled', false);
							$('.icon-spinner3').hide();
							$('input[name="csrf_hris"]').val(JSON.csrf_hash);
						} else {
							
							alert_success('Sukses',JSON.result);
							$('.icon-spinner3').hide();
							$('#remove_file').show();
							$(".profile-photo-emp").remove('checked');
							$('#u_file').attr("src", JSON.img);
							$('input[name="csrf_hris"]').val(JSON.csrf_hash);
							if(user_id == session_id){
								$('.user_avatar').attr("src", JSON.img);
							}
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
			
						
		// 03. HAPUS
		
			$("#delete_record").submit(function(e){
				
				var tk_type = $('#token_type').val();
				
				if(tk_type == 'contact'){
					var field_add = '&is_ajax=6&data=delete_record&type=delete_contact&';
					var tb_name = 'xin_table_'+tk_type;				
								
				} else if(tk_type == 'qualification'){
					var field_add = '&is_ajax=12&data=delete_record&type=delete_qualification&';
					var tb_name = 'xin_table_'+tk_type;
				
				} else if(tk_type == 'work_experience'){
					var field_add = '&is_ajax=15&data=delete_record&type=delete_work_experience&';
					var tb_name = 'xin_table_'+tk_type;
									
				}
					
				e.preventDefault();
				var obj = $(this), action = obj.attr('name');
				$.ajax({
					url: e.target.action,
					type: "post",
					data: '?'+obj.serialize()+field_add+"form="+action,
					success: function (JSON) {
						if (JSON.error != '') {
							alert_fail('Gagal',JSON.error);
							$('input[name="csrf_hris"]').val(JSON.csrf_hash);
						} else {
							$('.delete-modal').modal('toggle');
							$('input[name="csrf_hris"]').val(JSON.csrf_hash);
							$('#'+tb_name).dataTable().api().ajax.reload(function(){ 
								alert_success('Sukses',JSON.result);
							}, true);
							
						}
					}
				});
			}); 

	    // ===========================================================================
   			

	// ******************************************************************************
	// LOAD 
	// ******************************************************************************

	   // Month & Year
		$('.ln_month_year').datepicker({
			changeMonth: true,
			changeYear: true,
			showButtonPanel: true,
			dateFormat:'yy-mm',
			yearRange: '1900:' + (new Date().getFullYear() + 15),
			beforeShow: function(input) {
				$(input).datepicker("widget").addClass('hide-calendar');
			},
			onClose: function(dateText, inst) {
				var month = $("#ui-datepicker-div .ui-datepicker-month :selected").val();
				var year = $("#ui-datepicker-div .ui-datepicker-year :selected").val();
				$(this).datepicker('setDate', new Date(year, month, 1));
				$(this).datepicker('widget').removeClass('hide-calendar');
				$(this).datepicker('widget').hide();
			}		
		});  

		// get current val
			
		$(".daily_wages").keyup(function(e){
			var to_currency_rate = $('#to_currency_rate').val();
			var curr_val = $(this).val();
			var final_val = to_currency_rate * curr_val;
			var float_val = final_val.toFixed(2);
			$('#current_cur_val2').html(float_val);
		});	

		$(".nav-tabs-link").click(function(){
			var profile_id = $(this).data('profile');
			var profile_block = $(this).data('profile-block');
			$('.nav-tabs-link').removeClass('active');
			$('.current-tab').hide();
			$('#user_profile_'+profile_id).addClass('active');
			$('#'+profile_block).show();
		});

		$(".xin-core-hr-opt").click(function(){
			var core_hr_info = $(this).data('core-hr-info');
			var core_profile_block = $(this).data('core-profile-block');
			$('.xin-core-hr-tab').removeClass('active');
			$('.core-current-tab').hide();
			$('#core_hr_'+core_hr_info).addClass('active');
			$('#'+core_profile_block).show();
		});
		
	// ******************************************************************************
	// jQuery 
	// ******************************************************************************

		jQuery("#aj_company").change(function(){
			jQuery.get(escapeHtmlSecure(base_url+"/get_company_elocations/"+jQuery(this).val()), function(data, status){
				jQuery('#location_ajax').html(data);
			});
			jQuery.get(escapeHtmlSecure(base_url+"/get_company_office_shifts/"+jQuery(this).val()), function(data, status){
				jQuery('#ajax_office_shift').html(data);
			});
		});

		jQuery("#location_id").change(function(){
			jQuery.get(base_url+"/get_location_departments/"+jQuery(this).val(), function(data, status){
				jQuery('#department_ajax').html(data);
			});
		});

		// get sub departments
		jQuery("#aj_subdepartments").change(function(){
			jQuery.get(base_url+"/get_sub_departments/"+jQuery(this).val(), function(data, status){
				jQuery('#subdepartment_ajax').html(data);
			});
		});

		// get designations
		jQuery("#aj_subdepartment").change(function(){
			jQuery.get(base_url+"/designation/"+jQuery(this).val(), function(data, status){
				jQuery('#designation_ajax').html(data);
			});
		});

		jQuery("#is_aj_subdepartments").change(function(){
			jQuery.get(base_url+"/is_designation/"+jQuery(this).val(), function(data, status){
				jQuery('#designation_ajax').html(data);
			});
		});
	
		jQuery("#contact_info").submit(function(e){

			e.preventDefault();
			var obj = jQuery(this), action = obj.attr('name');
			jQuery('.save').prop('disabled', true);

			jQuery.ajax({
				type: "POST",
				url: e.target.action,
				data: obj.serialize()+"&is_ajax=4&data=contact_info&type=contact_info&form="+action,
				cache: false,
				success: function (JSON) {
					if (JSON.error != '') {					
						alert_fail('Gagal',JSON.error);
						$('input[name="csrf_hris"]').val(JSON.csrf_hash);
						jQuery('.save').prop('disabled', false);
					} else {
						xin_table_contact.api().ajax.reload(function(){ 						
							alert_success('Sukses',JSON.result);
							$('input[name="csrf_hris"]').val(JSON.csrf_hash);
						}, true);
						jQuery('#contact_info')[0].reset(); // To reset form fields
						jQuery('.save').prop('disabled', false);
					}
				}
			});
		});

		jQuery("#contact_info2").submit(function(e){

			e.preventDefault();
			var obj = jQuery(this), action = obj.attr('name');
			jQuery('.save2').prop('disabled', true);
			$('.icon-spinner33').show();

			jQuery.ajax({
				type: "POST",
				url: e.target.action,
				data: obj.serialize()+"&is_ajax=4&data=contact_info&type=contact_info&form="+action,
				cache: false,
				success: function (JSON) {
					if (JSON.error != '') {
						
						alert_fail('Gagal',JSON.error);
						$('input[name="csrf_hris"]').val(JSON.csrf_hash);
						$('.icon-spinner33').hide();
						jQuery('.save2').prop('disabled', false);
					} else {
						
						alert_success('Sukses',JSON.result);
						$('input[name="csrf_hris"]').val(JSON.csrf_hash);
						$('.icon-spinner33').hide();
						jQuery('.save2').prop('disabled', false);
					}
				}
			});
		});	
	
		/* Add qualification info */
		jQuery("#qualification_info").submit(function(e){
		
			e.preventDefault();
			var obj = jQuery(this), action = obj.attr('name');
			jQuery('.save').prop('disabled', true);
			$('.icon-spinner3').show();
			
			jQuery.ajax({
				type: "POST",
				url: e.target.action,
				data: obj.serialize()+"&is_ajax=10&data=qualification_info&type=qualification_info&form="+action,
				cache: false,
				success: function (JSON) {
					if (JSON.error != '') {
						
						alert_fail('Gagal',JSON.error);
						$('input[name="csrf_hris"]').val(JSON.csrf_hash);
						jQuery('.save').prop('disabled', false);
						$('.icon-spinner3').hide();
					} else {
						xin_table_qualification.api().ajax.reload(function(){ 
							
							alert_success('Sukses',JSON.result);
							$('input[name="csrf_hris"]').val(JSON.csrf_hash);
						}, true);
						jQuery('#qualification_info')[0].reset(); // To reset form fields
						$('.icon-spinner3').hide();
						jQuery('.save').prop('disabled', false);
					}
				}
			});
		});
		
		/* Add work experience info */
		jQuery("#work_experience_info").submit(function(e){
		
			e.preventDefault();
			var obj = jQuery(this), action = obj.attr('name');
			jQuery('.save').prop('disabled', true);
			$('.icon-spinner3').show();
			
			jQuery.ajax({
				type: "POST",
				url: e.target.action,
				data: obj.serialize()+"&is_ajax=13&data=work_experience_info&type=work_experience_info&form="+action,
				cache: false,
				success: function (JSON) {
					if (JSON.error != '') {
						
						alert_fail('Gagal',JSON.error);
						$('input[name="csrf_hris"]').val(JSON.csrf_hash);
						jQuery('.save').prop('disabled', false);
						$('.icon-spinner3').hide();
					} else {
						xin_table_work_experience.api().ajax.reload(function(){ 
							
							alert_success('Sukses',JSON.result);
							$('input[name="csrf_hris"]').val(JSON.csrf_hash);
						}, true);
						$('.icon-spinner3').hide();
						jQuery('#work_experience_info')[0].reset(); // To reset form fields
						jQuery('.save').prop('disabled', false);
					}
				}
			});
		});
	
		/* Add location info */
		jQuery("#location_info").submit(function(e){
		
			e.preventDefault();
			var obj = jQuery(this), action = obj.attr('name');
			jQuery('.save').prop('disabled', true);
			
			jQuery.ajax({
				type: "POST",
				url: e.target.action,
				data: obj.serialize()+"&is_ajax=28&data=location_info&type=location_info&form="+action,
				cache: false,
				success: function (JSON) {
					if (JSON.error != '') {
						
						alert_fail('Gagal',JSON.error);
						$('input[name="csrf_hris"]').val(JSON.csrf_hash);
						jQuery('.save').prop('disabled', false);
					} else {
						xin_table_location.api().ajax.reload(function(){ 
							
							alert_success('Sukses',JSON.result);
							$('input[name="csrf_hris"]').val(JSON.csrf_hash);
						}, true);
						jQuery('#location_info')[0].reset(); // To reset form fields
						jQuery('.save').prop('disabled', false);
					}
				}
			});
		});
		
		/* Add change password */
		jQuery("#e_change_password").submit(function(e){
		
			e.preventDefault();
			var obj = jQuery(this), action = obj.attr('name');
			jQuery('.save').prop('disabled', true);
			$('.icon-spinner3').show();
			
			jQuery.ajax({
				type: "POST",
				url: e.target.action,
				data: obj.serialize()+"&is_ajax=31&data=e_change_password&type=change_password&form="+action,
				cache: false,
				success: function (JSON) {
					if (JSON.error != '') {
						
						alert_fail('Gagal',JSON.error);
						$('input[name="csrf_hris"]').val(JSON.csrf_hash);
						jQuery('.save').prop('disabled', false);
						$('.icon-spinner3').hide();
					} else {
						
						alert_success('Sukses',JSON.result);
						$('input[name="csrf_hris"]').val(JSON.csrf_hash);
						$('.icon-spinner3').hide();
						jQuery('#e_change_password')[0].reset(); // To reset form fields
						jQuery('.save').prop('disabled', false);
					}
				}
			});
		});     
    
    // delete a record
	$( document ).on( "click", ".delete", function() {
		$('input[name=_token]').val($(this).data('record-id'));
		$('input[name=token_type]').val($(this).data('token_type'));
		$('#delete_record').attr('action',site_url+'employees/delete_'+$(this).data('token_type')+'/'+$(this).data('record-id'));
	});
});	
$(document).ready(function(){
	
	$('[data-plugin="select_hrm"]').select2($(this).attr('data-options'));
	$('[data-plugin="select_hrm"]').select2({ width:'100%' });
		
	$('.cont_date').datepicker({
	  changeMonth: true,
	  changeYear: true,
	  dateFormat:'yy-mm-dd',
	  yearRange: '1990:' + (new Date().getFullYear() + 10),
	});	
	
});