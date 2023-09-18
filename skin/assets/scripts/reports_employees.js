$(document).ready(function() {
   var xin_table = $('#xin_emp_active').dataTable({
        "bDestroy": true,
				"iDisplayLength": 30,
				"aLengthMenu": [[30, 50, 100, -1], [30, 50, 100, "All"]],
		"ajax": {
            url : site_url+"reports/report_employees_active_list/0/0/0/",
            type : 'GET'
        },
		dom: 'lBfrtip',
		// // "buttons": ['csv', 'excel', 'pdf', 'print'], // colvis > if needed
		
		"fnDrawCallback": function(settings){
		$('[data-toggle="tooltip"]').tooltip();          
		}
    });

   var xin_table = $('#xin_emp_resign').dataTable({
        "bDestroy": true,
				"iDisplayLength": 30,
				"aLengthMenu": [[30, 50, 100, -1], [30, 50, 100, "All"]],
		"ajax": {
            url : site_url+"reports/report_employees_resign_list/0/0/0/",
            type : 'GET'
        },
		dom: 'lBfrtip',
		// // "buttons": ['csv', 'excel', 'pdf', 'print'], // colvis > if needed
		
		"fnDrawCallback": function(settings){
		$('[data-toggle="tooltip"]').tooltip();          
		}
    });

   var xin_table = $('#xin_emp_overtime').dataTable({
        "bDestroy": true,
		"iDisplayLength": 10,
		"aLengthMenu": [[10, 30, 50, 100, -1], [10, 30, 50, 100, "All"]],
		"ajax": {
            url : site_url+"reports/report_employees_overtime_list/0/0/0/",
            type : 'GET'
        },
		// dom: 'lBfrtip',
		// // // "buttons": ['csv', 'excel', 'pdf', 'print'], // colvis > if needed
		
		"fnDrawCallback": function(settings){
		$('[data-toggle="tooltip"]').tooltip();          
		}
    });
	
	$('[data-plugin="select_hrm"]').select2($(this).attr('data-options'));
	$('[data-plugin="select_hrm"]').select2({ width:'100%' });
	
	// get departments
	

	jQuery("#aj_company").change(function(){
		jQuery.get(base_url+"/get_employees/"+jQuery(this).val(), function(data, status){
			jQuery('#employee_ajax').html(data);
		});
	});

	
		// Month & Year
	$('.month_year').datepicker({
		changeMonth: true,
		changeYear: true,
		showButtonPanel: true,
		dateFormat:'yy-mm',
		yearRange: '1970:' + new Date().getFullYear(),
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

	/* projects report */
	$("#employees_active").submit(function(e){
		/*Form Submit*/
		e.preventDefault();
		var company_id = $('#aj_company').val();
		var department_id = $('#aj_department').val();
		var designation_id = $('#designation_id').val();
		var xin_table2 = $('#xin_emp_active').dataTable({
			 "bDestroy": true,
			"bFilter": true,
			"bAutoWidth": false,
			"bLengthChange": true,
			"iDisplayLength": 10,
			"aLengthMenu": [[30, 50, 100, -1], [30, 50, 100, "All"]],
			"ajax": {
				url : site_url+"reports/report_employees_active_list/"+company_id+"/"+department_id+"/"+designation_id+"/",
				type : 'GET'
			},
			dom: 'lBfrtip',
			// // "buttons": ['csv', 'excel', 'pdf', 'print'], // colvis > if needed
			"columns": [
				{"name": "no","orderable": false,"searchable": false,  "className": "text-center", "width": "5%"},
				{"name": "nip",  "className": "text-center", "width": "10%"},
				{"name": "nama",  "className": "text-left"},
				{"name": "nik",  "className": "text-center", "width": "10%"},
				{"name": "jk",  "className": "text-center", "width": "10%"},
				{"name": "dep",  "className": "text-left", "width": "20%"},
				{"name": "possis",  "className": "text-left", "width": "15%"},
				{"name": "ms",  "className": "text-center", "width": "10%"},
				{"name": "cp",  "className": "text-center", "width": "10%"}
		    ],
			"fnDrawCallback": function(settings){
			$('[data-toggle="tooltip"]').tooltip();          
			}
		});
		toastr.success('Data Berhasil Ditampilkan.');
		xin_table2.api().ajax.reload(function(){ }, true);
	});

	$("#employees_resign").submit(function(e){
		/*Form Submit*/
		e.preventDefault();
		var company_id = $('#aj_company').val();
		var department_id = $('#aj_department').val();
		var designation_id = $('#designation_id').val();
		var xin_table2 = $('#xin_emp_active').dataTable({
			 "bDestroy": true,
			"bFilter": true,
			"bAutoWidth": false,
			"bLengthChange": true,
			"iDisplayLength": 10,
			"aLengthMenu": [[30, 50, 100, -1], [30, 50, 100, "All"]],
			"ajax": {
				url : site_url+"reports/report_employees_resign_list/"+company_id+"/"+department_id+"/"+designation_id+"/",
				type : 'GET'
			},
			dom: 'lBfrtip',
			// // "buttons": ['csv', 'excel', 'pdf', 'print'], // colvis > if needed
			"columns": [
				{"name": "no","orderable": false,"searchable": false,  "className": "text-center", "width": "5%"},
				{"name": "nip",  "className": "text-center", "width": "10%"},
				{"name": "nama",  "className": "text-left"},
				{"name": "nik",  "className": "text-center", "width": "10%"},
				{"name": "jk",  "className": "text-center", "width": "10%"},
				{"name": "dep",  "className": "text-left", "width": "20%"},
				{"name": "possis",  "className": "text-left", "width": "15%"},
				{"name": "ms",  "className": "text-center", "width": "10%"},
				{"name": "cp",  "className": "text-center", "width": "10%"}
		    ],
			"fnDrawCallback": function(settings){
			$('[data-toggle="tooltip"]').tooltip();          
			}
		});
		toastr.success('Data Berhasil Ditampilkan.');
		xin_table2.api().ajax.reload(function(){ }, true);
	});

	/* projects report */
	$("#employees_overtime").submit(function(e){
		/*Form Submit*/
		e.preventDefault();
		var employee_id = jQuery('#employee_id').val();
		var bmonth_year = jQuery('#bmonth_year').val();
		var company_id  = jQuery('#aj_company').val();
		
		var xin_table2    = $('#xin_emp_overtime').dataTable({
			"bDestroy"       : true,
			"bFilter"        : true,
			"bAutoWidth"     : false,
			"bLengthChange"  : true,
			"iDisplayLength" : 10,
			"aLengthMenu": [[10, 30, 50, 100, -1], [10, 30, 50, 100, "All"]],
			"ajax": {
				url : site_url+"reports/report_employees_overtime_list/"+company_id+"/"+employee_id+"/"+bmonth_year+"/",								
				type : 'GET'
			},
			dom: 'lBfrtip',
			// // // "buttons": ['csv', 'excel', 'pdf', 'print'], // colvis > if needed
			"columns": [
				{"name": "no","orderable": false,"searchable": false,  "className": "text-center", "width": "5%"},
				{"name": "nip",  "className": "text-center", "width": "10%"},
				{"name": "nama",  "className": "text-left"},
				{"name": "nik",  "className": "text-center", "width": "10%"},
				{"name": "jk",  "className": "text-center", "width": "10%"},
				// {"name": "dep",  "className": "text-left", "width": "20%"},
				// {"name": "possis",  "className": "text-left", "width": "15%"},
				// {"name": "ms",  "className": "text-center", "width": "10%"},
				// {"name": "cp",  "className": "text-center", "width": "10%"}
		    ],
			"fnDrawCallback": function(settings){
			$('[data-toggle="tooltip"]').tooltip();          
			}
		});
		toastr.success('Data Berhasil Ditampilkan.');
		xin_table2.api().ajax.reload(function(){ }, true);
	});

});