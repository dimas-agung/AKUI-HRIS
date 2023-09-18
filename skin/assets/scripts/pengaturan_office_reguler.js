$(document).ready(function() {
	var xin_table = $('#xin_table').dataTable({
		"bDestroy": true,
		"ajax": {
			url : site_url+"pengaturan/office_reguler_list/",
			type : 'GET'
		},
		"columns": [
				{"name": "no", "className": "text-left"},
				// {"name": "no", "className": "text-left"},
				{"name": "no", "className": "text-left"},
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
		"fnDrawCallback": function(settings){
			$('[data-toggle="tooltip"]').tooltip();          
		}
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

	
	$('[data-plugin="select_hrm"]').select2($(this).attr('data-options'));
	$('[data-plugin="select_hrm"]').select2({ width:'100%' });
	$('.clockpicker').clockpicker();

	var input = $('.timepicker').clockpicker({
		placement: 'bottom',
		align: 'left',
		autoclose: true,
		'default': 'now'
	});

	$(".clear-time").click(function(){
		var clear_id  = $(this).data('clear-id');
		$(".clear-"+clear_id).val('');
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


	jQuery("#aj_company").change(function(){
			
			jQuery.get(base_url+"/get_employees_office/"+jQuery(this).val(), function(data, status){
				jQuery('#employee_ajax').html(data);
			});
		});

	// edit
	$('.edit-modal-data').on('show.bs.modal', function (event) {
		var button = $(event.relatedTarget);
		var office_shift_id = button.data('office_shift_id');
		var modal = $(this);
		$.ajax({
			url : site_url+"pengaturan/read_reguler_record/",
			type: "GET",
			data: 'jd=1&is_ajax=1&mode=modal&data=reguler&office_shift_id='+office_shift_id,
			success: function (response) {
				if(response) {
					$("#ajax_modal").html(response);
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
			type: "POST",
			url: e.target.action,
			data: obj.serialize()+"&is_ajax=1&add_type=office_shift&form="+action,
			cache: false,
			success: function (JSON) {
				if (JSON.error != '') {
					alert_fail('Gagal',JSON.error);
					$('input[name="csrf_hris"]').val(JSON.csrf_hash);
					$('.save').prop('disabled', false);
				} else {
					xin_table.api().ajax.reload(function(){ 
						alert_success('Sukses',JSON.result);
					}, true);
					$('input[name="csrf_hris"]').val(JSON.csrf_hash);
					$('.add-form').removeClass('in');
					$('.select2-selection__rendered').html('-- Silakan Pilih --');
					$('#xin-form')[0].reset(); // To reset form fields
					$('.save').prop('disabled', false);
				}
			}
		});
	});
});

$( document ).on( "click", ".delete", function() {
	$('input[name=_token]').val($(this).data('record-id'));
	$('#delete_record').attr('action',site_url+'pengaturan/delete_shift/'+$(this).data('record-id'));
});

$( document ).on( "click", ".default-shift", function() {
	var officeshift_id = $(this).data('office_shift_id');
	$.ajax({
		type: "GET",
		url: site_url+"pengaturan/default_shift/?office_shift_id="+officeshift_id,
		success: function (JSON) {
			var xin_table2 = $('#xin_table').dataTable({
				"bDestroy": true,
				"ajax": {
					url : site_url+"pengaturan/office_reguler_list/",
					type : 'GET'
				},
				"columns": [
					{"name": "no", "className": "text-left"},
					// {"name": "no", "className": "text-left"},
					{"name": "no", "className": "text-left"},
					{"name": "no", "className": "text-center"},
					{"name": "no", "className": "text-center"},
					{"name": "no", "className": "text-center"},
					{"name": "no", "className": "text-center"},
					{"name": "no", "className": "text-center"},
					{"name": "no", "className": "text-center"},
					{"name": "no", "className": "text-center"}
			    ],
				"fnDrawCallback": function(settings){
				$('[data-toggle="tooltip"]').tooltip();          
				}
			});
			xin_table2.api().ajax.reload(function(){ 
				alert_success('Sukses',JSON.result);
			}, true);
		}
	});
});
