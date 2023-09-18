$(document).ready(function() {
   var xin_table = $('#xin_table').dataTable({
        "bDestroy": true,
        "aLengthMenu"    : [[10, 30, 50, 100, -1], [10, 30, 50, 100, "All"]],
		"ajax": {
            url : base_url+'/award_list/',
            type : 'GET'
        },
        "columns": [
			{"name": "kolom_1", "className": "text-center"},
			{"name": "kolom_2", "className": "text-center"},
			{"name": "kolom_3", "className": "text-left"},
			{"name": "kolom_4", "className": "text-left"},
			
			{"name": "kolom_6", "className": "text-left"},
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
		/*dom: 'lBfrtip',
		// // "buttons": ['csv', 'excel', 'pdf', 'print'], // colvis > if needed*/
		"fnDrawCallback": function(settings){
		$('[data-toggle="tooltip"]').tooltip();          
		}
    });
	
	$('[data-plugin="select_hrm"]').select2($(this).attr('data-options'));
	$('[data-plugin="select_hrm"]').select2({ width:'100%' }); 
	
	jQuery("#aj_company").change(function(){
		jQuery.get(base_url+"/get_employees/"+jQuery(this).val(), function(data, status){
			jQuery('#employee_ajax').html(data);
		});
	});
	/* Delete data */
	$("#delete_record").submit(function(e){
	/*Form Submit*/
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
					}, true);
					$('input[name="csrf_hris"]').val(JSON.csrf_hash);
				}
			}
		});
	});
	
	// edit
	$('.edit-modal-data').on('show.bs.modal', function (event) {
		var button = $(event.relatedTarget);
		var award_id = button.data('award_id');
		var modal = $(this);
		$.ajax({
			url :  base_url+"/read/",
			type: "GET",
			data: 'jd=1&is_ajax=1&mode=modal&data=award&award_id='+award_id,
			success: function (response) {
				if(response) {
					$("#ajax_modal").html(response);
				}
			}
		});
	});
	
	$('.view-modal-data').on('show.bs.modal', function (event) {
		var button = $(event.relatedTarget);
		var award_id = button.data('award_id');
		var modal = $(this);
		$.ajax({
			url :  base_url+"/read/",
			type: "GET",
			data: 'jd=1&is_ajax=1&mode=modal&data=view_award&award_id='+award_id,
			success: function (response) {
				if(response) {
					$("#ajax_modal_view").html(response);
				}
			}
		});
	});
	
	// Award Month & Year
		$('.d_month_year').datepicker({
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
	
	/* Update logo */
	$("#xin-form").submit(function(e){
		var fd = new FormData(this);
		var obj = $(this), action = obj.attr('name');
		fd.append("is_ajax", 1);
		fd.append("add_type", 'award');
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
					$('input[name="csrf_hris"]').val(JSON.csrf_hash);
						$('.save').prop('disabled', false);
						$('.icon-spinner3').hide();
				} else {
					xin_table.api().ajax.reload(function(){ 
						alert_success('Sukses',JSON.result);
					}, true);
					$('.icon-spinner3').hide();
					$('input[name="csrf_hris"]').val(JSON.csrf_hash);
					$('#xin-form')[0].reset(); // To reset form fields
					$('.add-form').removeClass('in');
					$('.select2-selection__rendered').html('-- Silakan Pilih --');
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

/* Add data */ /*Form Submit*/
//	$("#xin-form").submit(function(e){});
});
$( document ).on( "click", ".delete", function() {
	$('input[name=_token]').val($(this).data('record-id'));
	$('#delete_record').attr('action',base_url+'/delete/'+$(this).data('record-id'));
});