$(document).ready(function() {
	
	var xin_table_detail = $('#xin_table_detail').dataTable({
		
	});

	$('[data-plugin="select_hrm"]').select2($(this).attr('data-options'));
	$('[data-plugin="select_hrm"]').select2({ width:'100%' });

// Month & Year
	$('.d_month_year').datepicker({
		changeMonth: true,
		changeYear: true,
		showButtonPanel: true,
		dateFormat:'yy-mm',
		yearRange: '2019:' + new Date().getFullYear(),
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


	/* attendance daily report */
	$("#attendance_daily_report").submit(function(e){
		/*Form Submit*/
		e.preventDefault();
		var month_year = jQuery('#month_year').val();
				
		if(month_year == ''){
			toastr.error('Silahkan Pilih Bulan Kehadiran');
		} else {
					
			 var xin_table_detail = $('#xin_table_detail').dataTable({
				"bDestroy": true,
				"iDisplayLength": 30,
				"aLengthMenu": [[30, 50, 100, -1], [30, 50, 100, "All"]],
				"ajax": {
					url : site_url+"timesheet/attendance_list_month/?attendance_date="+$('#month_year').val()+"&location_id="+$('#location_id').val(),
					type : 'GET'
				},
				// dom: 'lBfrtip',
				// // "buttons": ['csv', 'excel', 'pdf', 'print'], // colvis > if needed
				"fnDrawCallback": function(settings){
				$('[data-toggle="tooltip"]').tooltip();          
				}
			});
			 

			xin_table_detail.api().ajax.reload(function(){ }, true);
		}
	});

});