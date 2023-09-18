$(document).ready(function() {		

	//=====================================================================================================
	// 12 Jam Shift
	// ====================================================================================================

		var input = $('.timepicker').clockpicker({
			placement: 'bottom',
			align: 'left',
			autoclose: true,
			'default': 'now'
		});
		var xin_table_shift_jam = $('#xin_table_shift_jam').dataTable({
			"bDestroy": true,
			"bFilter": true,
			"bAutoWidth": false,
			"bLengthChange": true,
			"iDisplayLength": 10,
			"aLengthMenu": [[10, 30, 50, 100, -1], [10, 30, 50, 100, "All"]],
			"ajax": {
	            url : site_url+"pengaturan/shift_jam_list/",
	            type : 'GET'
	        },
			"columns": [
				{"name": "kolom_0","orderable": false,"searchable": false,  "className": "text-center", "width": "7%"},
				{"name": "kolom_1",  "className": "text-center", "width": "10%"},
				{"name": "kolom_2",  "className": "text-center", "width": "10%"},
				{"name": "kolom_3",  "className": "text-center", "width": "10%"},
				{"name": "kolom_4",  "className": "text-left"}
		    ],		
			"fnDrawCallback": function(settings){
				$('[data-toggle="tooltip"]').tooltip();          
			}			
		});

		jQuery("#shift_jam_info").submit(function(e){
			/*Form Submit*/
			e.preventDefault();
				var obj = jQuery(this), action = obj.attr('name');
				jQuery('.save').prop('disabled', true);
				$('.icon-spinner3').show();
				jQuery.ajax({
					type: "POST",
					url: e.target.action,
					data: obj.serialize()+"&is_ajax=23&data=shift_jam_info&type=shift_jam_info&form="+action,
					cache: false,
					success: function (JSON) {
						if (JSON.error != '') {
							alert_fail('Gagal',JSON.error);
							$('input[name="csrf_hris"]').val(JSON.csrf_hash);
							jQuery('.save').prop('disabled', false);
							$('.icon-spinner3').hide();
						} else {
							var xin_table_shift_jam = $('#xin_table_shift_jam').dataTable({
								"bDestroy": true,
								"bFilter": true,
								"bAutoWidth": false,
								"bLengthChange": true,
								"iDisplayLength": 10,
								"aLengthMenu": [[10, 30, 50, 100, -1], [10, 30, 50, 100, "All"]],
								"ajax": {
						            url : site_url+"pengaturan/shift_jam_list/",
						            type : 'GET'
						        },
								"columns": [
									{"name": "kolom_0","orderable": false,"searchable": false,  "className": "text-center", "width": "7%"},
									{"name": "kolom_1",  "className": "text-center", "width": "10%"},
									{"name": "kolom_2",  "className": "text-center", "width": "10%"},
									{"name": "kolom_3",  "className": "text-center", "width": "10%"},
									{"name": "kolom_4",  "className": "text-left"}
							    ],		
								"fnDrawCallback": function(settings){
									$('[data-toggle="tooltip"]').tooltip();          
								}			
							});
							xin_table_shift_jam.api().ajax.reload(function(){ 
								alert_success('Sukses',JSON.result);
							}, true);

							
							$('input[name="csrf_hris"]').val(JSON.csrf_hash);
							$('.icon-spinner3').hide();
							jQuery('#shift_jam_info')[0].reset(); // To reset form fields
							jQuery('.save').prop('disabled', false);
						}
					}
				});
		});

	//=====================================================================================================
	// HAPUS
	// ====================================================================================================
	
		/* Delete data */
		$("#delete_record").submit(function(e){

			var tk_type = $('#token_type').val();

			$('.icon-spinner3').show();
			
			if(tk_type == 'shift_jam'){
				var field_add = '&is_ajax=321&data=delete_izin_type&type=delete_record&';
				var tb_name = 'xin_table_'+tk_type;

			} 
			
			/*Form Submit*/
			e.preventDefault();
			var obj = $(this), action = obj.attr('name');
			$.ajax({
				url: e.target.action,
				type: "post",
				data: '?'+obj.serialize()+field_add+"form="+action,
				success: function (JSON) {
					if (JSON.error != '') {
						alert_fail('Gagal',JSON.error);
						$('input[name="csrf_hris"]').val(JSON.csrf_hash);
						$('.icon-spinner3').hide();
					} else {
						$('.delete-modal').modal('toggle');
						$('.icon-spinner3').hide();
						$('#'+tb_name).dataTable().api().ajax.reload(function(){ 
							alert_success('Sukses',JSON.result);
						}, true);
						$('input[name="csrf_hris"]').val(JSON.csrf_hash);
					}
				}
			});
		});   
	
	//=====================================================================================================
	// EDIT
	// ====================================================================================================

		$('#edit_setting_datail').on('show.bs.modal', function (event) {
			var button = $(event.relatedTarget);
			var field_id = button.data('field_id');
			var field_type = button.data('field_type');
			$('.icon-spinner3').show();

			if(field_type == 'shift_jam'){
				var field_add = '&data=ed_shift_jam&type=ed_shift_jam&';

			} 			
			
			var modal = $(this);
			$.ajax({
				url: site_url+'pengaturan/read_shift_jam/',
				type: "GET",
				data: 'jd=1'+field_add+'field_id='+field_id,
				success: function (response) {
					if(response) {
						$('.icon-spinner3').hide();
						$("#ajax_setting_info").html(response);
					}
				}
			});
	    });

	//=====================================================================================================
	// LAIN
	// ====================================================================================================
		
		$('[data-plugin="select_hrm"]').select2($(this).attr('data-options'));
		$('[data-plugin="select_hrm"]').select2({ width:'100%' });

		$(".nav-tabs-link").click(function(){
			var profile_id = $(this).data('constant');
			var profile_block = $(this).data('constant-block');
			$('.list-group-item').removeClass('active');
			$('.current-tab').hide();
			$('#constant_'+profile_id).addClass('active');
			$('#'+profile_block).show();
		});
	
	//=====================================================================================================
	//=====================================================================================================
});
$( document ).on( "click", ".delete", function() {
	$('input[name=_token]').val($(this).data('record-id'));
	$('input[name=token_type]').val($(this).data('token_type'));
	$('#delete_record').attr('action',site_url+'pengaturan/delete_'+$(this).data('token_type')+'/'+$(this).data('record-id'))+'/';
});