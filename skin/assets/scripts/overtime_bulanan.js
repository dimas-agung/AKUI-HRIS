$(document).ready(function() {
		var xin_table = $('#xin_table').dataTable({
			"bDestroy": true,
			"ajax": {
				url : base_url+"/overtime_list_bulanan/",
				type : 'GET'
			},
			"columns": [
				{"name": "kolom_1", "className": "text-center"},
				{"name": "kolom_2", "className": "text-center"},
				{"name": "kolom_3", "className": "text-left"},
				{"name": "kolom_4", "className": "text-left"},
				{"name": "kolom_5", "className": "text-left"},
				{"name": "kolom_6", "className": "text-left"},										
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

		$('[data-plugin="select_hrm"]').select2($(this).attr('data-options'));
		$('[data-plugin="select_hrm"]').select2({ width:'100%' }); 

		//$('#description').trumbowyg();
		jQuery("#aj_company").change(function(){
			
			jQuery.get(base_url+"/get_employees_bulanan/"+jQuery(this).val(), function(data, status){
				jQuery('#employee_ajax').html(data);
			});
		});

		// Clock
		var input = $('.timepicker_m').clockpicker({
			placement: 'bottom',
			align: 'left',
			autoclose: true,
			'default': 'now'
		});

		// Month & Year
		$('.attendance_date_m').datepicker({
			changeMonth: true,
			changeYear: true,
			dateFormat:'yy-mm-dd',
			altField: "#date_format",
			altFormat: "d M, yy",
			yearRange: '1970:' + new Date().getFullYear(),
			beforeShow: function(input) {
				$(input).datepicker("widget").show();
			}
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
			var overtime_id = button.data('overtime_id');
			var modal = $(this);
			$.ajax({
				url : base_url+"/read_bulanan/",
				type: "GET",
				data: 'jd=1&is_ajax=1&mode=modal&data=overtime&overtime_id='+overtime_id,
				success: function (response) {
					if(response) {
						$("#ajax_modal").html(response);
					}
				}
			});
		});

		/* Add data */ /*Form Submit*/
		$("#xin-form").submit(function(e){
			var fd = new FormData(this);
			var obj = $(this), action = obj.attr('name');
			fd.append("is_ajax", 1);
			fd.append("add_type", 'overtime_bulanan');
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
});
$( document ).on( "click", ".delete", function() {
	$('input[name=_token]').val($(this).data('record-id'));
	$('#delete_record').attr('action',base_url+'/delete_bulanan/'+$(this).data('record-id'))+'/';
});
