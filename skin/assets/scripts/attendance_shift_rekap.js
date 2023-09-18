$(document).ready(function() {

	// var xin_table_recap = $('#xin_table_recap').dataTable({
	// 		"bDestroy": true,
	// 		"iDisplayLength": 30,
	// 		"aLengthMenu": [[30, 50, 100, -1], [30, 50, 100, "All"]],
	// 		"ajax": {
	// 			url : site_url+"timesheet/attendance_shift_list_load/?attendance_date="+$('#attendance_date').val()+"&location_id="+$('#location_id').val()+"&company_id="+$('#company_id').val(),
	// 			type : 'GET'
	// 		},
	// 		// dom: 'lBfrtip',
	// 		"buttons": ['excel', 'pdf'], // colvis > if needed
	// 		"fnDrawCallback": function(settings){
	// 			$('[data-toggle="tooltip"]').tooltip();          
	// 		},
	// 		"columns": [
	// 			{"name": "kolom_1", "className": "text-center"},
	// 			{"name": "kolom_2", "className": "text-left"},
	// 			{"name": "kolom_3", "className": "text-left"},
	// 			{"name": "kolom_2",  "className": "text-left"},
	// 			{"name": "kolom_4", "className": "text-center"},
	// 			{"name": "kolom_5", "className": "text-center"},
	// 			{"name": "kolom_6", "className": "text-center"},
	// 			{"name": "kolom_7", "className": "text-center"},
	// 			{"name": "kolom_8", "className": "text-center"},
	// 			{"name": "kolom_9", "className": "text-center"},
	// 			{"name": "kolom_10", "className": "text-center"},
	// 			{"name": "kolom_11", "className": "text-center"},
	// 			{"name": "kolom_12", "className": "text-center"},
	// 	    ],
	// 	});

	
	$('[data-plugin="select_hrm"]').select2($(this).attr('data-options'));
	$('[data-plugin="select_hrm"]').select2({ width:'100%' });

	
// Month & Year
	$('.attendance_date').datepicker({
		changeMonth: true,
		changeYear: true,
		maxDate: '0',
		dateFormat:'yy-mm-dd',
		altField: "#date_format",
		altFormat: js_date_format,
		yearRange: '1970:' + new Date().getFullYear(),
		beforeShow: function(input) {
			$(input).datepicker("widget").show();
		}
	});

	/* attendance daily report */
	$("#attendance_recap_report").submit(function(e){
		/*Form Submit*/
		e.preventDefault();
		var attendance_date = $('#attendance_date').val();
		var date_format = $('#date_format').val();
		if(attendance_date == ''){
			toastr.error('Silahkan Pilih Tanggal Kehadiran');
		} else {
			$('#att_date').html(date_format);

			var xin_table_recap = $('#xin_table_recap').dataTable({
				"bDestroy": true,
				"iDisplayLength": 30,
				"aLengthMenu": [[30, 50, 100, -1], [30, 50, 100, "All"]],
				"ajax": {
					url : site_url+"timesheet/attendance_shift_list/?attendance_date="+$('#attendance_date').val()+"&location_id="+$('#location_id').val()+"&company_id="+$('#company_id').val(),
					type : 'GET'
				},
				dom: 'lBfrtip',
				"buttons": ['csv', 'excel', 'pdf', 'print'], // colvis > if needed
				"fnDrawCallback": function(settings){
					$('[data-toggle="tooltip"]').tooltip();          
				},
				"columns": [
					{"name": "kolom_1", "className": "text-center"},
					{"name": "kolom_2", "className": "text-left"},
					{"name": "kolom_3", "className": "text-left"},
					{"name": "kolom_2",  "className": "text-left"},
					{"name": "kolom_4", "className": "text-center"},
					{"name": "kolom_5", "className": "text-center"},
					{"name": "kolom_6", "className": "text-center"},
					{"name": "kolom_7", "className": "text-center"},
					{"name": "kolom_8", "className": "text-center"},
					{"name": "kolom_9", "className": "text-center"},
					{"name": "kolom_10", "className": "text-center"},
					{"name": "kolom_11", "className": "text-center"},
					{"name": "kolom_12", "className": "text-center"},
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
			});
	
   			tampilkan_tabel();

			xin_table_recap.api().ajax.reload(function(){ toastr.success('Proses Tarik Absensi Shift Berhasil Dilakukan.'); }, true);
		}
	});

	

});