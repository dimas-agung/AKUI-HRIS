$(document).ready(function(){			
	
	// ==============================================================================================
	// KONTRAK KARYAWAN
	// ==============================================================================================

		// get data
		$('.edit-modal-data').on('show.bs.modal', function (event) {
			var button = $(event.relatedTarget);
			var field_id = button.data('field_id');
			var field_tpe = button.data('field_type');
			
			if(field_tpe == 'contract'){
				var field_add = '&data=emp_contract&type=emp_contract&';
			
			} 
			var modal = $(this);
			$.ajax({
				url: site_url+'legal/dialog_'+field_tpe+'/',
				type: "GET",
				data: 'jd=1'+field_add+'field_id='+field_id,
				success: function (response) {
					if(response) {
						$("#ajax_modal").html(response);
					}
				}
			});
	    });

		/* Add contract info */
		jQuery("#contract_info").submit(function(e){
			
			/*Form Submit*/
			e.preventDefault();
			var obj = jQuery(this), action = obj.attr('name');
			jQuery('.save').prop('disabled', true);
		
			jQuery.ajax({
				type: "POST",
				url: e.target.action,
				data: obj.serialize()+"&is_ajax=19&data=contract_info&type=contract_info&form="+action,
				cache: false,
				success: function (JSON) {
					if (JSON.error != '') {
						
						alert_fail('Gagal',JSON.error);
						$('input[name="csrf_hris"]').val(JSON.csrf_hash);
						jQuery('.save').prop('disabled', false);

					} else {
						
						var xin_table_contract = $('#xin_table_contract').dataTable({
					        "bDestroy": true,
							"ajax": {
					            url : site_url+"legal/contract_list/"+$('#user_id').val(),
					            type : 'GET'
					        },
					        "columns": [
									{"name": "kolom_1", "className": "text-center"},
									{"name": "kolom_2", "className": "text-center"},
									{"name": "kolom_3", "className": "text-center"},
									{"name": "kolom_4", "className": "text-center"},
									{"name": "kolom_5", "className": "text-center"},
									{"name": "kolom_6", "className": "text-center"},
									{"name": "kolom_7", "className": "text-left"},
									
									{"name": "kolom_9", "className": "text-center"},
									{"name": "kolom_10", "className": "text-center"},
							    ],
								
							"fnDrawCallback": function(settings){
							$('[data-toggle="tooltip"]').tooltip();          
							}
					    });
						
						xin_table_contract.api().ajax.reload(function(){
							alert_success('Sukses',JSON.result);
							$('input[name="csrf_hris"]').val(JSON.csrf_hash);
						}, true);

						jQuery('#contract_info')[0].reset(); // To reset form fields
						jQuery('.save').prop('disabled', false);
					}
				}
			});
		});

		// On page load > contract
		var xin_table_contract = $('#xin_table_contract').dataTable({
	        "bDestroy": true,
			"ajax": {
	            url : site_url+"legal/contract_list/"+$('#user_id').val(),
	            type : 'GET'
	        },
	        "columns": [
					{"name": "kolom_1", "className": "text-center"},
					{"name": "kolom_2", "className": "text-center"},
					{"name": "kolom_3", "className": "text-center"},
					{"name": "kolom_4", "className": "text-center"},
					{"name": "kolom_5", "className": "text-center"},
					{"name": "kolom_6", "className": "text-center"},
					{"name": "kolom_7", "className": "text-left"},
					
					{"name": "kolom_9", "className": "text-center"},
					{"name": "kolom_10", "className": "text-center"},
			    ],
				
			"fnDrawCallback": function(settings){
			$('[data-toggle="tooltip"]').tooltip();          
			}
	    });

	     /* Delete data */
		$("#delete_record").submit(function(e){
			var tk_type = $('#token_type').val();
			
			if(tk_type == 'contract'){
				var field_add = '&is_ajax=21&data=delete_record&type=delete_contract&';
				var tb_name   = 'xin_table_'+tk_type;	
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
					} else {
						$('.delete-modal').modal('toggle');
						$('input[name="csrf_hris"]').val(JSON.csrf_hash);
						$('#'+tb_name).dataTable().api().ajax.reload(function(){ 
							alert_success('Sukses',JSON.result);
						}, true);
						
					}
				}
			});
		});  

		$('.xin_hris_table').DataTable();
	
	
	      

   /// delete a record
	$( document ).on( "click", ".delete", function() {
		$('input[name=_token]').val($(this).data('record-id'));
		$('input[name=token_type]').val($(this).data('token_type'));
		$('#delete_record').attr('action',site_url+'legal/delete_'+$(this).data('token_type')+'/'+$(this).data('record-id'));
	});
});	

$(document).ready(function(){
	
	$('[data-plugin="select_hrm"]').select2($(this).attr('data-options'));
	$('[data-plugin="select_hrm"]').select2({ width:'100%' });
		
	$('.cont_date').datepicker({
	  changeMonth: true,
	  changeYear: true,
	  dateFormat:'yy-mm-dd',
	  yearRange: '1990:' + (new Date().getFullYear() + 10),
	});	
	
});