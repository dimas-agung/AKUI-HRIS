$(document).ready(function() {
   var xin_table = $('#xin_table_bulanan').dataTable({
        
		// 	scrollX       : true,
		// scrollY		  : true,
		// scrollCollapse : false,			
		// paging         : true,
		
		"bDestroy"        : true,
		"bSort"           : false,
		"iDisplayLength"  : 31,
		"aLengthMenu"     : [[31, 50, 100, -1], [31, 50, 100, "All"]],				
		autoWidth         : true,  
		// "fixedColumns"    : true,
		// "fixedColumns"    : {
		// 	leftColumns   : 3
		// },	
		"ajax": {
            url : site_url+"reports/shift_attendance_list/",
            type : 'GET'
        },
		"columns": [
			{"name": "no", "className": "text-center"},
			{"name": "no", "className": "text-center"},
			{"name": "no", "className": "text-center"},
			{"name": "no", "className": "text-center"},
			{"name": "no", "className": "text-center"},
			{"name": "no", "className": "text-center"},
			{"name": "no", "className": "text-center"},
			{"name": "no", "className": "text-center"},
			{"name": "no", "className": "text-center"},
			{"name": "no", "className": "text-center"},
			{"name": "no", "className": "text-center"},
			{"name": "no", "className": "text-left"},
			{"name": "no", "className": "text-left"},				
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
		dom: 'lBfrtip',
		buttons: [
	          'print', {
	            extend: 'pdf',
	            orientation: 'landscape'
	          },
	          'excel'
	        ],
		"fnDrawCallback": function(settings){
			$('[data-toggle="tooltip"]').tooltip();          
		},
		 "fnRowCallback": function( nRow, aData, iDisplayIndex, iDisplayIndexFull ) {
                        
            if ( aData[10] == 'Hadir' ) {
                $('td', nRow).css('background-color', '#c6edd1' );               
            } else if ( aData[10] == 'Absen' ) {
                $('td', nRow).css('background-color', '#edeac6' );
            } else if ( aData[10] == 'Sakit' ) {
                $('td', nRow).css('background-color', '#c6dbed' );
            } else if ( aData[10] == 'Lembur' ) {
                $('td', nRow).css('background-color', '#dec6ed' );
            } else if ( aData[10] == 'Dinas' ) {
                $('td', nRow).css('background-color', '#c6e9ed' );
            } else if ( aData[10] == 'Izin' ) {
                $('td', nRow).css('background-color', '#e7debd' );
            } else if ( aData[10] == 'Cuti' ) {
                $('td', nRow).css('background-color', '#bde7e7' );
            } else if ( aData[10] == 'Libur' ) {
                $('td', nRow).css('background-color', '#ddc3c3' );
            } else {
                $('td', nRow).css('background-color', '#f1eaea' );
            } 
         }
    });
	
	$('[data-plugin="select_hrm"]').select2($(this).attr('data-options'));
	$('[data-plugin="select_hrm"]').select2({ width:'100%' });
	
	jQuery("#aj_company").change(function(){
		jQuery.get(base_url+"/get_employees_att_shift/"+jQuery(this).val(), function(data, status){
			jQuery('#employee_ajax').html(data);
		});
	});
	
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
	$('.view-modal-data').on('show.bs.modal', function (event) {
	var button = $(event.relatedTarget);
	var ipaddress = button.data('ipaddress');
	var modal = $(this);
	$.ajax({
		url :  site_url+"/timesheet/read_map_info/",
		type: "GET",
		data: 'jd=1&is_ajax=1&mode=modal&data=view_map&type=view_map&ipaddress='+ipaddress,
		success: function (response) {
			if(response) {
				$("#ajax_modal_view").html(response);
			}
		}
		});
	});
	
	/* attendance datewise report */
	$("#attendance_shift_report").submit(function(e){
		/*Form Submit*/
		e.preventDefault();
		var start_date = $('#start_date').val();
		var end_date   = $('#end_date').val();
		var user_id    = $('#employee_id').val();
		var xin_table2 = $('#xin_table_bulanan').dataTable({
			
			"bDestroy"        : true,
			"bSort"           : false,
			"iDisplayLength"  : 31,
			"aLengthMenu"     : [[31, 50, 100, -1], [31, 50, 100, "All"]],		
			autoWidth         : true,  
			// "fixedColumns"    : true,
			// "fixedColumns"    : {
			// 	leftColumns   : 3
			// },	
			"ajax": {
				url : site_url+"reports/employee_shift_list/?start_date="+start_date+"&end_date="+end_date+"&user_id="+user_id,
				type : 'GET'
			},
			"columns": [
				{"name": "no", "className": "text-center"},
				{"name": "no", "className": "text-center"},
				{"name": "no", "className": "text-center"},
				{"name": "no", "className": "text-center"},
				{"name": "no", "className": "text-center"},
				{"name": "no", "className": "text-center"},
				{"name": "no", "className": "text-center"},
				{"name": "no", "className": "text-center"},
				{"name": "no", "className": "text-center"},
				{"name": "no", "className": "text-center"},
				{"name": "no", "className": "text-center"},
				{"name": "no", "className": "text-left"},
				{"name": "no", "className": "text-left"},
				
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
			dom: 'lBfrtip',
			buttons: [
	          'print', {
	            extend: 'pdf',
	            orientation: 'landscape'
	          },
	          'excel'
	        ],
			"fnDrawCallback": function(settings){
				$('[data-toggle="tooltip"]').tooltip();          
			},
			 "fnRowCallback": function( nRow, aData, iDisplayIndex, iDisplayIndexFull ) {
                            
                if ( aData[10] == 'Hadir' ) {
                    $('td', nRow).css('background-color', '#c6edd1' );               
                } else if ( aData[10] == 'Absen' ) {
                    $('td', nRow).css('background-color', '#edeac6' );
                } else if ( aData[10] == 'Sakit' ) {
                    $('td', nRow).css('background-color', '#c6dbed' );
                } else if ( aData[10] == 'Lembur' ) {
                    $('td', nRow).css('background-color', '#dec6ed' );
                } else if ( aData[10] == 'Dinas' ) {
                    $('td', nRow).css('background-color', '#c6e9ed' );
                } else if ( aData[10] == 'Izin' ) {
                    $('td', nRow).css('background-color', '#e7debd' );
                } else if ( aData[10] == 'Cuti' ) {
                    $('td', nRow).css('background-color', '#bde7e7' );
                } else if ( aData[10] == 'Libur' ) {
                    $('td', nRow).css('background-color', '#ddc3c3' );
                } 
             }
		});
		toastr.success('Proses Pencarian Berlangsung');
		xin_table2.api().ajax.reload(function(){ toastr.success('Data Kehadiran Berhasil Ditampilkan.'); }, true);
	});
});