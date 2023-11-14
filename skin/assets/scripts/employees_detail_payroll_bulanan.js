$(document).ready(function(){			
	
	// get data
	
   
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
	$(".basic_salary").keyup(function(e){
		var to_currency_rate = $('#to_currency_rate').val();
		var curr_val = $(this).val();
		var final_val = to_currency_rate * curr_val;
		var float_val = final_val.toFixed(2);
		$('#current_cur_val').html(float_val);
	});	

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

	$(".salary-tab").click(function(){
		var profile_id = $(this).data('profile');
		var profile_block = $(this).data('profile-block');
		$('.salary-tab-list').removeClass('active');
		$('.salary-current-tab').hide();
		$('#suser_profile_'+profile_id).addClass('active');
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
	
	// =====================================================================================================

		// Rekening Bank
		var xin_table_bank_account = $('#xin_table_bank_account').dataTable({
	        "bDestroy": true,
			"ajax": {
	            url : site_url+"payroll/bank_account/"+$('#user_id').val(),
	            type : 'GET'
	        },
	        "columns": [
				{"name": "kolom_0","orderable": false,"searchable": false,"className": "text-center", "width": "7%"},				
				{"name": "kolom_1"},
				{"name": "kolom_2","orderable": true,"searchable": true,"className": "text-center", "width": "15%"},
				{"name": "kolom_3","orderable": true,"searchable": true,"className": "text-center", "width": "15%"},
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
		
		// Tunjangan
		var xin_table_allowances_ad = $('#xin_table_all_allowances').dataTable({
	        "bDestroy": true,
			"ajax": {
	            url : site_url+"payroll/salary_all_allowances/"+$('#user_id').val(),
	            type : 'GET'
	        },
	        "columns": [
					{"name": "kolom_1","orderable": false,"searchable": false,  "className": "text-center", "width": "7%"},
					{"name": "kolom_2", "className": "text-center"},				
					{"name": "kolom_3", "className": "text-right", "width": "15%"},
					{"name": "kolom_4", "className": "text-right", "width": "15%"},
					{"name": "kolom_5", "className": "text-right", "width": "15%"},
					{"name": "kolom_6", "className": "text-right", "width": "15%"},			
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

	    // Insentif
		var xin_table_commissions_ad = $('#xin_table_all_commissions').dataTable({
	        "bDestroy": true,
			"ajax": {
	            url : site_url+"payroll/salary_all_commissions/"+$('#user_id').val(),
	            type : 'GET'
	        },
	         "columns": [
					{"name": "kolom_1","orderable": false,"searchable": false,  "className": "text-center", "width": "8%"},
					{"name": "kolom_2", "className": "text-center"},
					{"name": "kolom_3", "className": "text-left"},
					{"name": "kolom_4", "className": "text-right"},				
			    ],
			"fnDrawCallback": function(settings){
			$('[data-toggle="tooltip"]').tooltip();          
			}
	    });

	    // Lembur
	   	var xin_table_emp_overtime = $('#xin_table_all_overtime').dataTable({
	        "bDestroy": true,
			"ajax": {
	            url : site_url+"payroll/salary_all_overtime/"+$('#user_id').val(),
	            type : 'GET'
	        },
	        "columns": [
					{"name": "kolom_1","orderable": false,"searchable": false,  "className": "text-center", "width": "4%"},				
					{"name": "kolom_2", "className": "text-center"},
					{"name": "kolom_3", "className": "text-center"},
					{"name": "kolom_4", "className": "text-left"},
					{"name": "kolom_5", "className": "text-left"},
					{"name": "kolom_6", "className": "text-center"},				
					{"name": "kolom_7", "className": "text-right"},				
			    ],
			"fnDrawCallback": function(settings){
			$('[data-toggle="tooltip"]').tooltip();          
			}
	    });

	    // BPJS
		var xin_table_statutory_deductions_ad = $('#xin_table_all_statutory_deductions').dataTable({
	        "bDestroy": true,
			"ajax": {
	            url : site_url+"payroll/salary_all_statutory_deductions/"+$('#user_id').val(),
	            type : 'GET'
	        },
	        "columns": [
				{"name": "kolom_1","orderable": false,"searchable": false,  "className": "text-center", "width": "8%"},
				{"name": "kolom_2", "className": "text-center"},
				{"name": "kolom_3"},
				{"name": "kolom_4", "className": "text-left"},
				{"name": "kolom_5", "className": "text-right"},				
		    ],
			"fnDrawCallback": function(settings){
			$('[data-toggle="tooltip"]').tooltip();          
			}
	    });
		
		// Pajak
		var xin_table_other_payments_ad = $('#xin_table_all_other_payments').dataTable({
	        "bDestroy": true,
			"ajax": {
	            url : site_url+"payroll/salary_all_other_payments/"+$('#user_id').val(),
	            type : 'GET'
	        },
	        "columns": [
				{"name": "kolom_1","orderable": false,"searchable": false,  "className": "text-center", "width": "8%"},
				{"name": "kolom_2", "className": "text-center"},
				{"name": "kolom_3"},				
				{"name": "kolom_4", "className": "text-right"},				
		    ],
			"fnDrawCallback": function(settings){
			$('[data-toggle="tooltip"]').tooltip();          
			}
	    });

		// Pinjaman 
		var xin_table_all_deductions = $('#xin_table_all_deductions').dataTable({
	        "bDestroy": true,
			"ajax": {
	            url : site_url+"payroll/salary_all_deductions/"+$('#user_id').val(),
	            type : 'GET'
	        },
	        "columns": [
					{"name": "kolom_1","orderable": false,"searchable": false,  "className": "text-center", "width": "8%"},
					{"name": "kolom_2", "className": "text-center"},
					{"name": "kolom_3", "className": "text-center"},					
					{"name": "kolom_4"},
					{"name": "kolom_5", "className": "text-right"},
					{"name": "kolom_6", "className": "text-right"},	
					{"name": "kolom_7", "className": "text-center"},								
			    ],
			"fnDrawCallback": function(settings){
			$('[data-toggle="tooltip"]').tooltip();          
			}
	    });

	    // Pemotongan
		var xin_table_minus_ad = $('#xin_table_all_minus_bulanan').dataTable({
	        "bDestroy": true,
			"ajax": {
	            url : site_url+"payroll/salary_all_minus_bulanan/"+$('#user_id').val(),
	            type : 'GET'
	        },
	         "columns": [
					{"name": "kolom_1","orderable": false,"searchable": false,  "className": "text-center", "width": "8%"},
					{"name": "kolom_2", "className": "text-center"},
					{"name": "kolom_3", "className": "text-left"},
					{"name": "kolom_4", "className": "text-right"},				
			    ],
			"fnDrawCallback": function(settings){
			$('[data-toggle="tooltip"]').tooltip();          
			}
	    });

	// ======================================================================================================
		
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

		jQuery("#employee_update_allowance").submit(function(e){
		
			e.preventDefault();
			var obj = jQuery(this), action = obj.attr('name');
			jQuery('.save').prop('disabled', true);
			
			jQuery.ajax({
				type: "POST",
				url: e.target.action,
				data: obj.serialize()+"&is_ajax=4&data=employee_update_allowance&type=employee_update_allowance&form="+action,
				cache: false,
				success: function (JSON) {
					if (JSON.error != '') {
						
						alert_fail('Gagal',JSON.error);
						$('input[name="csrf_hris"]').val(JSON.csrf_hash);
						jQuery('.save').prop('disabled', false);
					} else {
						xin_table_allowances_ad.api().ajax.reload(function(){ 
							
							alert_success('Sukses',JSON.result);
							$('input[name="csrf_hris"]').val(JSON.csrf_hash);
						}, true);
						jQuery('#employee_update_allowance')[0].reset(); // To reset form fields
						jQuery('.save').prop('disabled', false);
					}
				}
			});
		});
		
		jQuery("#employee_update_commissions").submit(function(e){
		
			e.preventDefault();
			var obj = jQuery(this), action = obj.attr('name');
			jQuery('.save').prop('disabled', true);
			
			jQuery.ajax({
				type: "POST",
				url: e.target.action,
				data: obj.serialize()+"&is_ajax=4&data=employee_update_commissions&type=employee_update_commissions&form="+action,
				cache: false,
				success: function (JSON) {
					if (JSON.error != '') 
					{						
						alert_fail('Gagal',JSON.error);
						$('input[name="csrf_hris"]').val(JSON.csrf_hash);
						jQuery('.save').prop('disabled', false);

					} else 
					{
						xin_table_commissions_ad.api().ajax.reload(function()
						{	
							alert_success('Sukses',JSON.result);
							$('input[name="csrf_hris"]').val(JSON.csrf_hash);
						}, true);

						jQuery('#employee_update_commissions')[0].reset(); // To reset form fields
						jQuery('.save').prop('disabled', false);
					}
				}
			});
		});

		jQuery("#employee_update_minus").submit(function(e){
		
			e.preventDefault();
			var obj = jQuery(this), action = obj.attr('name');
			jQuery('.save').prop('disabled', true);
			
			jQuery.ajax({
				type: "POST",
				url: e.target.action,
				data: obj.serialize()+"&is_ajax=4&data=employee_update_minus&type=employee_update_minus&form="+action,
				cache: false,
				success: function (JSON) {
					if (JSON.error != '') {
						
						alert_fail('Gagal',JSON.error);
						$('input[name="csrf_hris"]').val(JSON.csrf_hash);
						jQuery('.save').prop('disabled', false);
					} else {
						xin_table_minus_ad.api().ajax.reload(function(){ 
							
							alert_success('Sukses',JSON.result);
							$('input[name="csrf_hris"]').val(JSON.csrf_hash);
						}, true);
						jQuery('#employee_update_minus')[0].reset(); // To reset form fields
						jQuery('.save').prop('disabled', false);
					}
				}
			});
		});


		jQuery("#statutory_deductions_info").submit(function(e){
		
			e.preventDefault();
			var obj = jQuery(this), action = obj.attr('name');
			jQuery('.save').prop('disabled', true);
			
			jQuery.ajax({
				type: "POST",
				url: e.target.action,
				data: obj.serialize()+"&is_ajax=4&data=statutory_deductions_info&type=statutory_deductions_info&form="+action,
				cache: false,
				success: function (JSON) {
					if (JSON.error != '') {
						
						alert_fail('Gagal',JSON.error);
						$('input[name="csrf_hris"]').val(JSON.csrf_hash);
						jQuery('.save').prop('disabled', false);
					} else {
						xin_table_statutory_deductions_ad.api().ajax.reload(function(){ 
							
							alert_success('Sukses',JSON.result);
							$('input[name="csrf_hris"]').val(JSON.csrf_hash);
						}, true);
						jQuery('#statutory_deductions_info')[0].reset(); // To reset form fields
						jQuery('.save').prop('disabled', false);
					}
				}
			});
		});

		jQuery("#other_payments_info").submit(function(e){
		
			e.preventDefault();
			var obj = jQuery(this), action = obj.attr('name');
			jQuery('.save').prop('disabled', true);
			
			jQuery.ajax({
				type: "POST",
				url: e.target.action,
				data: obj.serialize()+"&is_ajax=4&data=other_payments_info&type=other_payments_info&form="+action,
				cache: false,
				success: function (JSON) {
					if (JSON.error != '') {
						
						alert_fail('Gagal',JSON.error);
						$('input[name="csrf_hris"]').val(JSON.csrf_hash);
						jQuery('.save').prop('disabled', false);
					} else {
						xin_table_other_payments_ad.api().ajax.reload(function(){ 
							
							alert_success('Sukses',JSON.result);
							$('input[name="csrf_hris"]').val(JSON.csrf_hash);
						}, true);
						jQuery('#other_payments_info')[0].reset(); // To reset form fields
						jQuery('.save').prop('disabled', false);
					}
				}
			});
		});

		/* Add bank account info */
		jQuery("#bank_account_info").submit(function(e){
		
			e.preventDefault();
			var obj = jQuery(this), action = obj.attr('name');
			jQuery('.save').prop('disabled', true);
			$('.icon-spinner3').show();
			
			jQuery.ajax({
				type: "POST",
				url: e.target.action,
				data: obj.serialize()+"&is_ajax=16&data=bank_account_info&type=bank_account_info&form="+action,
				cache: false,
				success: function (JSON) {
					if (JSON.error != '') {
						
						alert_fail('Gagal',JSON.error);
						$('.icon-spinner3').hide();
						$('input[name="csrf_hris"]').val(JSON.csrf_hash);
						jQuery('.save').prop('disabled', false);
					} else {
						xin_table_bank_account.api().ajax.reload(function(){ 
							
							alert_success('Sukses',JSON.result);
							$('input[name="csrf_hris"]').val(JSON.csrf_hash);
						}, true);
						$('.icon-spinner3').hide();
						jQuery('#bank_account_info')[0].reset(); // To reset form fields
						jQuery('.save').prop('disabled', false);
					}
				}
			});
		});

	// ======================================================================================================

	$("#employee_update_salary").submit(function(e){
	
		e.preventDefault();
		var obj = $(this), action = obj.attr('name');
		$('.save').prop('disabled', true);
		$('.icon-spinner3').show();
		
		$.ajax({
			type: "POST",
			url: e.target.action,
			data: obj.serialize()+"&is_ajax=3&data=employee_update_salary&type=employee_update_salary&form="+action,
			cache: false,
			success: function (JSON) {
				if (JSON.error != '') {
					
					alert_fail('Gagal',JSON.error);
					$('input[name="csrf_hris"]').val(JSON.csrf_hash);
					$('.icon-spinner3').hide();
					$('.save').prop('disabled', false);
				} else {
					
					alert_success('Sukses',JSON.result);
					$('input[name="csrf_hris"]').val(JSON.csrf_hash);
					$('.icon-spinner3').hide();
					$('.save').prop('disabled', false);
				}
			}
		});
	});

	$("#employee_update_salary_gapok").submit(function(e){
	
		e.preventDefault();
		var obj = $(this), action = obj.attr('name');
		$('.save').prop('disabled', true);
		$('.icon-spinner3').show();
		
		$.ajax({
			type: "POST",
			url: e.target.action,
			data: obj.serialize()+"&is_ajax=3&data=employee_update_salary_gapok&type=employee_update_salary_gapok&form="+action,
			cache: false,
			success: function (JSON) {
				if (JSON.error != '') {
					
					alert_fail('Gagal',JSON.error);
					$('input[name="csrf_hris"]').val(JSON.csrf_hash);
					$('.icon-spinner3').hide();
					$('.save').prop('disabled', false);
				} else {
					
					alert_success('Sukses',JSON.result);
					$('input[name="csrf_hris"]').val(JSON.csrf_hash);
					$('.icon-spinner3').hide();
					$('.save').prop('disabled', false);
				}
			}
		});
	});

	// add loan
	$("#add_loan_info").submit(function(e){
	
		e.preventDefault();
		var obj = $(this), action = obj.attr('name');
		$('.save').prop('disabled', true);
		$('.icon-spinner3').show();
		
		$.ajax({
			type: "POST",
			url: e.target.action,
			data: obj.serialize()+"&is_ajax=3&data=loan_info&type=loan_info&form="+action,
			cache: false,
			success: function (JSON) {
				if (JSON.error != '') {
					
					alert_fail('Gagal',JSON.error);
					$('input[name="csrf_hris"]').val(JSON.csrf_hash);
					$('.icon-spinner3').hide();
					$('.save').prop('disabled', false);
				} else {
					xin_table_all_deductions.api().ajax.reload(function(){ 
						
						alert_success('Sukses',JSON.result);
						$('input[name="csrf_hris"]').val(JSON.csrf_hash);
					}, true);
					$('input[name="csrf_hris"]').val(JSON.csrf_hash);
					$('.icon-spinner3').hide();
					jQuery('#add_loan_info')[0].reset(); // To reset form fields
					$('.save').prop('disabled', false);
				}
			}
		});
	});
	
	$('.edit-modal-data').on('show.bs.modal', function (event) {
		
		var button    = $(event.relatedTarget);

		var field_id  = button.data('field_id');
		var field_tpe = button.data('field_type');
		
		if(field_tpe == 'salary_allowance'){
			var field_add = '&data=e_salary_allowance&type=e_salary_allowance&';
		
		} else if(field_tpe == 'salary_loan'){
			var field_add = '&data=e_salary_loan&type=e_salary_loan&';
		
		} else if(field_tpe == 'salary_commissions'){
			var field_add = '&data=salary_commissions_info&type=salary_commissions_info&';

		} else if(field_tpe == 'salary_minus_bulanan'){
			var field_add = '&data=salary_minus_info&type=salary_minus_info&';
				
		} else if(field_tpe == 'salary_statutory_deductions'){
			var field_add = '&data=salary_statutory_deductions_info&type=salary_statutory_deductions_info&';
		
		} else if(field_tpe == 'salary_other_payments'){
			var field_add = '&data=salary_other_payments_info&type=salary_other_payments_info&';

		} else if(field_tpe == 'bank_account'){
			var field_add = '&data=emp_bank_account&type=emp_bank_account&';
		
		}

		var modal = $(this);

		$.ajax({
			url: site_url+'payroll/dialog_'+field_tpe+'/',
			type: "GET",
			data: 'jd=1'+field_add+'field_id='+field_id,
			success: function (response) {
				if(response) {
					$("#ajax_modal").html(response);
				}
			}
		});
    });

   /* Delete data */
	$("#delete_record").submit(function(e){
		
		var tk_type = $('#token_type').val();
		
		if(tk_type == 'all_allowances'){
			var field_add = '&is_ajax=30&data=delete_record&type=delete_salary_allowance&';
			var tb_name = 'xin_table_'+tk_type;
		
		} else if(tk_type == 'all_deductions'){
			var field_add = '&is_ajax=30&data=delete_record&type=delete_salary_loan&';
			var tb_name = 'xin_table_'+tk_type;

		} else if(tk_type == 'all_overtime'){
			var field_add = '&is_ajax=30&data=delete_record&type=delete_all_overtime&';
			var tb_name = 'xin_table_'+tk_type;
		
		} else if(tk_type == 'all_commissions'){
			var field_add = '&is_ajax=30&data=delete_record&type=delete_all_commissions&';
			var tb_name = 'xin_table_'+tk_type;

		} else if(tk_type == 'all_minus_bulanan'){
			var field_add = '&is_ajax=30&data=delete_record&type=delete_all_minus_bulanan&';
			var tb_name = 'xin_table_'+tk_type;	
		
		} else if(tk_type == 'all_statutory_deductions'){
			var field_add = '&is_ajax=30&data=delete_record&type=delete_all_statutory_deductions&';
			var tb_name = 'xin_table_'+tk_type;
		
		} else if(tk_type == 'all_other_payments'){
			var field_add = '&is_ajax=30&data=delete_record&type=delete_all_other_payments&';
			var tb_name = 'xin_table_'+tk_type;		

		} else if(tk_type == 'bank_account'){
			var field_add = '&is_ajax=18&data=delete_record&type=delete_bank_account&';
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

   /// delete a record
	$( document ).on( "click", ".delete", function() {
		$('input[name=_token]').val($(this).data('record-id'));
		$('input[name=token_type]').val($(this).data('token_type'));
		$('#delete_record').attr('action',site_url+'payroll/delete_'+$(this).data('token_type')+'/'+$(this).data('record-id'));
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