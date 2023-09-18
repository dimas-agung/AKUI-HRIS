$(document).ready(function() {
var xin_table = $('#xin_table').dataTable({
	"bDestroy": true,
	"ajax": {
		url : site_url+"pengaturan/works_list/",
		type : 'GET'
	},
	"columns": [
		{"name": "kolom_1", "className": "text-center"},
		{"name": "kolom_2", "className": "text-center"},
		{"name": "kolom_3", "className": "text-center"},
		{"name": "kolom_4", "className": "text-center"},
		{"name": "kolom_5", "className": "text-center"},
		{"name": "kolom_6", "className": "text-left"},
		{"name": "kolom_7", "className": "text-left"},
		{"name": "kolom_8", "className": "text-center"},
		{"name": "kolom_9", "className": "text-center"},
		{"name": "kolom_10", "className": "text-center"},
		
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
// Date
$('.date').datepicker({
  changeMonth: true,
  changeYear: true,
  dateFormat:'yy-mm-dd',
  yearRange: new Date().getFullYear() + ':' + (new Date().getFullYear() + 10),
});


/* Delete data */
$("#delete_record").submit(function(e){
	/*Form Submit*/
	e.preventDefault();
	var obj = $(this), action = obj.attr('name');
	$.ajax({
		type: "POST",
		url: e.target.action,
		data: obj.serialize()+"&is_ajax=2&type=delete&form="+action,
		cache: false,
		success: function (JSON) {
			if (JSON.error != '') {
				$('input[name="csrf_hris"]').val(JSON.csrf_hash);
				alert_fail('Gagal',JSON.error);
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
	var payroll_id = button.data('payroll_id');
	var modal = $(this);
	$.ajax({
		url : site_url+"pengaturan/read_work_record/",
		type: "GET",
		data: 'jd=1&is_ajax=1&mode=modal&data=work&payroll_id='+payroll_id,
		success: function (response) {
			if(response) {
				$("#ajax_modal").html(response);
			}
		}
	});
});

$('.view-modal-data').on('show.bs.modal', function (event) {
	var button = $(event.relatedTarget);
	var payroll_id = button.data('payroll_id');
	var modal = $(this);
	$.ajax({
		url : site_url+"pengaturan/view_work_record/",
		type: "GET",
		data: 'jd=1&is_ajax=1&mode=modal&data=view_work&payroll_id='+payroll_id,
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
	$.ajax({
		type: "POST",
		url: e.target.action,
		data: obj.serialize()+"&is_ajax=1&add_type=work&form="+action,
		cache: false,
		success: function (JSON) {
			if (JSON.error != '') {
				alert_fail('Gagal',JSON.error);
				$('input[name="csrf_hris"]').val(JSON.csrf_hash);
				$('.save').prop('disabled', false);
			} else {
				xin_table.api().ajax.reload(function(){ 
					alert_success('Sukses',JSON.result);
				}, true);
				$('input[name="csrf_hris"]').val(JSON.csrf_hash);
				$('.add-form').fadeOut('slow');
				$('#xin-form')[0].reset(); // To reset form fields
				$('.save').prop('disabled', false);
			}
		}
	});
});
});
$( document ).on( "click", ".delete", function() {
	$('input[name=_token]').val($(this).data('record-id'));
	$('#delete_record').attr('action',site_url+'pengaturan/delete_work/'+$(this).data('record-id'));
});