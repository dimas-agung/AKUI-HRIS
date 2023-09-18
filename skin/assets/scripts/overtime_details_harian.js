$(document).ready(function(){			
	/* Edit overtime data */
	$("#update_status_harian").submit(function(e){
	/*Form Submit*/
	e.preventDefault();
		var obj = $(this), action = obj.attr('name');
		$('.save').prop('disabled', true);
		$(".icon-spinner3").show();
		$.ajax({
			type  : "POST",
			url   : e.target.action,
			data  : obj.serialize()+"&is_ajax=3&edit_type=update_status_harian&update=1&view=overtime&form="+action,
			cache : false,
			success: function (JSON) {
				if (JSON.error != '') {
					alert_fail('Gagal',JSON.error);
					$('input[name="csrf_hris"]').val(JSON.csrf_hash);
					$(".icon-spinner3").hide();
					$('.save').prop('disabled', false);
				} else {
					alert_success('Sukses',JSON.result);
					$('input[name="csrf_hris"]').val(JSON.csrf_hash);
					$(".icon-spinner3").hide();
					$('.save').prop('disabled', false);				
				}
			}
		});
	});
	$('[data-plugin="select_hrm"]').select2($(this).attr('data-options'));
	$('[data-plugin="select_hrm"]').select2({ width:'100%' }); 
});