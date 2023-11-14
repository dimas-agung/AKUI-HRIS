$(document).ready(function() {

	var xin_table = $('#xin_table').dataTable({
		"bDestroy": true,
		"ajax": {
			url : base_url+"/vendor_list/",
			type : 'GET'
		},
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

	$('[data-plugin="select_hrm"]').select2($(this).attr('data-options'));
	$('[data-plugin="select_hrm"]').select2({ width:'100%' }); 
	//$('#expertise').trumbowyg();
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
	$('.edit-modal-data-min').on('show.bs.modal', function (event) {
		var button = $(event.relatedTarget);
		var vendor_id = button.data('vendor_id');
		var modal = $(this);
		$.ajax({
			url : base_url+"/read/",
			type: "GET",
			data: 'jd=1&is_ajax=1&mode=modal&data=vendor&vendor_id='+vendor_id,
			success: function (response) {
				if(response) {
					$("#ajax_modal_min").html(response);
				}
			}
		});
	});

	$('.view-modal-data').on('show.bs.modal', function (event) {
		var button = $(event.relatedTarget);
		var vendor_id = button.data('vendor_id');
		var modal = $(this);
		$.ajax({
			url : base_url+"/read/",
			type: "GET",
			data: 'jd=1&is_ajax=1&mode=modal&data=view_vendor&vendor_id='+vendor_id,
			success: function (response) {
				if(response) {
					$("#ajax_modal_view").html(response);
				}
			}
		});
	});

	/* Add data */ /*Form Submit*/
	$("#xin-form").submit(function(e){
		e.preventDefault();
		var obj = $(this), action = obj.attr('name');
		$('.save').prop('disabled', true);
		$(".icon-spinner3").show();
		$.ajax({
			type: "POST",
			url: e.target.action,
			data: obj.serialize()+"&is_ajax=1&add_type=vendor&form="+action,
			cache: false,
			success: function (JSON) {
				if (JSON.error != '') {
					alert_fail('Gagal',JSON.error);
					$('input[name="csrf_hris"]').val(JSON.csrf_hash);
					$('.save').prop('disabled', false);
					$(".icon-spinner3").hide();
				} else {
					xin_table.api().ajax.reload(function(){ 
						alert_success('Sukses',JSON.result);
					}, true);
					$('input[name="csrf_hris"]').val(JSON.csrf_hash);
					$(".icon-spinner3").hide();
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
	$('#delete_record').attr('action',base_url+'/delete/'+$(this).data('record-id'))+'/';
});
