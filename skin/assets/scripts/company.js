$(document).ready(function() {
    
    // ===================================================================
	// TABEL
	// ===================================================================

	    var xin_table = $('#xin_table').dataTable({
	        "bDestroy": true,
			"ajax": {
	            url : base_url+"/company_list/",
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
		
		$('[data-plugin="xin_select"]').select2($(this).attr('data-options'));
		$('[data-plugin="xin_select"]').select2({ width:'100%' }); 	
	
	// ===================================================================
	// PROSES
	// ===================================================================		
		// edit
		$('.edit-modal-data').on('show.bs.modal', function (event) {
			var button = $(event.relatedTarget);
			var company_id = button.data('company_id');
			var modal = $(this);
			$.ajax({
				url : base_url+"/read/",
				type: "GET",
				data: 'jd=1&is_ajax=1&mode=modal&data=company&company_id='+company_id,
				success: function (response) {
					if(response) {
						$("#ajax_modal").html(response);
					}
				}
			});
		});
});

//open the lateral panel
$( document ).on( "click", ".cd-btn", function() {
	event.preventDefault();
	var company_id = $(this).data('company_id');
	$.ajax({
	url : site_url+"company/read_info/",
	type: "GET",
	data: 'jd=1&is_ajax=1&mode=modal&data=view_company&company_id='+company_id,
	success: function (response) {
		if(response) {
			//alert(response);
			$('.cd-panel').addClass('is-visible');
			$("#cd-panel").html(response);
		}
	}
	});
	
});
//clode the lateral panel
$( document ).on( "click", ".cd-panel", function() {
	if( $(event.target).is('.cd-panel') || $(event.target).is('.cd-panel-close') ) { 
		$('.cd-panel').removeClass('is-visible');
		event.preventDefault();
	}
});
	
$( document ).on( "click", ".delete", function() {
	$('input[name=_token]').val($(this).data('record-id'));
	$('#delete_record').attr('action',base_url+'/delete/'+$(this).data('record-id'));
});