$(document).ready(function() {
	
	// ===================================================================
	// TABEL
	// ===================================================================

		var xin_table = $('#xin_table').dataTable({
			"bDestroy": true,
			"aLengthMenu"     : [[12, 30, 50, 100, -1], [12, 30, 50, 100, "All"]],	
			"ajax": {
				url : base_url+"/perjanjian_list/",
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
			
		});

		$('[data-plugin="select_hrm"]').select2($(this).attr('data-options'));
		$('[data-plugin="select_hrm"]').select2({ width:'100%' }); 
		
		$('.date').datepicker({
		  changeMonth: true,
		  changeYear: true,
		  dateFormat:'yy-mm-dd',
		  yearRange: new Date().getFullYear() + ':' + (new Date().getFullYear() + 10),
		});
	// ===================================================================
	// PROSES
	// ===================================================================
		/* Delete data */
		$("#delete_record").submit(function(e){	
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
		$('.edit-modal-data-max').on('show.bs.modal', function (event) {
			var button = $(event.relatedTarget);
			var perjanjian_id = button.data('perjanjian_id');
			var modal = $(this);
			$.ajax({
				url : base_url+"/read/",
				type: "GET",
				data: 'jd=1&is_ajax=1&mode=modal&data=perjanjian&perjanjian_id='+perjanjian_id,
				success: function (response) {
					if(response) {
						$("#ajax_modal_max").html(response);
					}
				}
			});
		});		

		/* Add data */ /*Form Submit*/
		$("#xin-form").submit(function(e){
			e.preventDefault();
			var obj = $(this), action = obj.attr('name');
			$('.save').prop('disabled', true);
			$('.icon-spinner3').show();
			$.ajax({
				type: "POST",
				url: base_url+'/add_perjanjian/',
				data: obj.serialize()+"&is_ajax=1&add_type=perjanjian&form="+action,
				cache: false,
				success: function (JSON) {
					if (JSON.error != '') {
						alert_fail('Gagal',JSON.error);
						$('input[name="csrf_hris"]').val(JSON.csrf_hash);
						$('.save').prop('disabled', false);
						$('.icon-spinner3').hide();
					} else {
						xin_table.api().ajax.reload(function(){ 
							alert_success('Sukses',JSON.result);
						}, true);
						$('input[name="csrf_hris"]').val(JSON.csrf_hash);
						$('.icon-spinner3').hide();
						$('#xin-form')[0].reset(); // To reset form fields
						$('.add-form').removeClass('in');
						$('.select2-selection__rendered').html('-- Silakan Pilih --');
						$('.save').prop('disabled', false);
					}
				}
			});
		});
});

$( document ).on( "click", ".delete", function() {
	$('input[name=_token]').val($(this).data('record-id'));
	$('#delete_record').attr('action',base_url+'/delete/'+$(this).data('record-id'));
});
