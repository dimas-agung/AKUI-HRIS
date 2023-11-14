$(document).ready(function(){	
	/* Add data */ /*Form Submit*/
	$("#import_gramasi").submit(function(e){
		var fd = new FormData(this);
		var obj = $(this), action = obj.attr('name');
		fd.append("is_ajax", 3);
		fd.append("type", 'imp_employees');
		fd.append("form", action);
		e.preventDefault();
		$('.save').prop('disabled', true);
		// $('#hrload-img').show();
		// toastr.info(processing_request);
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
					
					// $('#hrload-img').hide();
					alert_fail('Gagal',JSON.error);

					$('input[name="csrf_hris"]').val(JSON.csrf_hash);
					$('.save').prop('disabled', false);
				
				} else {
					
					// $('#hrload-img').hide();
					alert_success('Sukses',JSON.result);

					$('input[name="csrf_hris"]').val(JSON.csrf_hash);
					$('#import_gramasi')[0].reset(); // To reset form fields
					
					$('.save').prop('disabled', false);
				}
			},
			error: function() 
			{
				
				// $('#hrload-img').hide();
				alert_success('Ada Kesalahan',JSON.error);
				$('input[name="csrf_hris"]').val(JSON.csrf_hash);
				$('.save').prop('disabled', false);
			} 	        
	   });
	});

	$(".nav-tabs-link").click(function(){
		var import_id = $(this).data('hris-import');
		var hris_import_block = $(this).data('hris-import-block');
		$('.list-group-item').removeClass('active');
		$('.current-tab').hide();
		$('#hris_import_'+import_id).addClass('active');
		$('#'+hris_import_block).show();
	});	

	$(".info_report_gaji_borongan").html('<span style="padding:5px;">Silahkan Klik Tombol "<span class="blink blink_one merah">Tampilkan</span>" Guna menampilkan Hasil Impor <b>Produktiftas per Harian</b>.</span>');
	// loadDataAttendance();

	$('[data-plugin="select_hrm"]').select2($(this).attr('data-options'));
	$('[data-plugin="select_hrm"]').select2({ width:'100%' }); 
	
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
});
