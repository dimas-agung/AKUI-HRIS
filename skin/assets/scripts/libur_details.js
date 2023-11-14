$(document).ready(function() {
	
$('[data-plugin="select_hrm"]').select2($(this).attr('data-options'));
$('[data-plugin="select_hrm"]').select2({ width:'100%' });
$('#remarks').trumbowyg({
	btns: [
        ['formatting'],
        'btnGrp-semantic',
        ['superscript', 'subscript'],
        ['removeformat'],
    ],
	autogrowOnEnter: true
});	
/* Add data */ /*Form Submit*/
	$("#update_status").submit(function(e){
		e.preventDefault();
		var obj = $(this), action = obj.attr('name');
		$.ajax({
			type: "POST",
			url: e.target.action,
			data: obj.serialize()+"&is_ajax=1&update_type=libur&form="+action,
			cache: false,
			success: function (JSON) {
				if (JSON.error != '') {
					alert_fail('Gagal',JSON.error);
					$('input[name="csrf_hris"]').val(JSON.csrf_hash);
				} else {
					swal({
		                title: "Proses Berhasil",
		                text: JSON.result,
		                type  : "success",
		                showConfirmButton: false,
		                confirmButtonClass: "btn-raised btn-success",
		                confirmButtonText: "OK",
		                timer: 2000
		            });
					// alert_success('Sukses',JSON.result);
					$('input[name="csrf_hris"]').val(JSON.csrf_hash);
					// setTimeout(function () {

						// window.location = window.location.href.replace('<?php echo base_url("admin/permission/libur");?>', 'documentation/');
						// window.location.href = JSON.redirect_url;
						// window.location.replace(JSON.redirect_url);
	                    // window.location.href = "<?php echo base_url('admin/permission/libur/');?>";

	                // }, 0)

					// window.location.assign("<?php echo base_url();?>/admin/permission/libur");
					// window.location.replace('admin/permission/libur');
					
				}
			}
		});
	});
});