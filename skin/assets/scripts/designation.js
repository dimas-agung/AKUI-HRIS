$(document).ready(function() {
    
	// ===================================================================
	// TABEL
	// ===================================================================

	    var xin_table = $('#xin_table').dataTable({
	        "bDestroy": true,
			"ajax": {
	            url : base_url+"/designation_list/",
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
			 // "fnRowCallback": function( nRow, aData, iDisplayIndex, iDisplayIndexFull ) {
	                            
	   //          if ( aData[4] == 'PT AKUI BIRD NEST INDONESIA' ) {
	   //              $('td', nRow).css('background-color', '#e0f9e9' );
	   //          } else if ( aData[4] == 'PT ORIGINAL BERKAH INDONESIA' ) {
	   //              $('td', nRow).css('background-color', '#f9f7e0' );
	   //          } else if ( aData[4] == 'PT WALET ABDILLAH JABLI' ) {
	   //              $('td', nRow).css('background-color', '#f8e0f9' );
	   //          }             
	   //      }
	    });
		
		$('[data-plugin="select_hrm"]').select2($(this).attr('data-options'));
		$('[data-plugin="select_hrm"]').select2({ width:'100%' }); 
		
		jQuery("#aj_company").change(function(){
			jQuery.get(base_url+"/get_departments/"+jQuery(this).val(), function(data, status){
				jQuery('#department_ajax').html(data);
			});

			jQuery.get(base_url+"/get_workstations/"+jQuery(this).val(), function(data, status){
				jQuery('#workstation_ajax').html(data);
			});
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
		$('.edit-modal-data').on('show.bs.modal', function (event) {
			var button = $(event.relatedTarget);
			var designation_id = button.data('designation_id');
			var modal = $(this);
			$.ajax({
				url : base_url+"/read/",
				type: "GET",
				data: 'jd=1&is_ajax=1&mode=modal&data=designation&designation_id='+designation_id,
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
			$('.icon-spinner3').show();
			$.ajax({
				type: "POST",
				url: base_url+'/add_designation/',
				data: obj.serialize()+"&is_ajax=1&add_type=designation&form="+action,
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