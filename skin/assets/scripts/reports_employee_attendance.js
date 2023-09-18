$(document).ready(function() {
  
	$(".info_karyawan_bulanan").html('<i class="fa fa-warning"></i> Silahkan Klik Tombol "<span class="blink blink_one merah">Tampilkan</span>" Guna menampilkan Kehadiran Per Karyawan Bulanan (Reguler).');
	
	$('[data-plugin="select_hrm"]').select2($(this).attr('data-options'));
	$('[data-plugin="select_hrm"]').select2({ width:'100%' });
	
	function tampilkan_tabel() 
    {
      var x = document.getElementById("myDIV");
      if (x.style.display === "none") {
        x.style.display = "block";
      } 
    } 

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
	
	/* attendance datewise report */
	$("#attendance_datewise_report").submit(function(e){
		/*Form Submit*/
		e.preventDefault();

		$('.save').prop('disabled', true);

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
				url : site_url+"reports/employee_date_wise_list/?start_date="+start_date+"&end_date="+end_date+"&user_id="+user_id,
				type : 'GET'
			},
			"columns": [
				{"name": "kolom_1",  "className": "text-center"},
				{"name": "kolom_2",  "className": "text-center"},
				{"name": "kolom_3",  "className": "text-center"},
				{"name": "kolom_4",  "className": "text-center"},
				{"name": "kolom_5",  "className": "text-center"},
				{"name": "kolom_6",  "className": "text-center"},
				{"name": "kolom_7",  "className": "text-center"},
				{"name": "kolom_8",  "className": "text-center"},
				{"name": "kolom_9",  "className": "text-center"},
				{"name": "kolom_10", "className": "text-center"},
				{"name": "kolom_11", "className": "text-center"},
				{"name": "kolom_12", "className": "text-left"},
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
			"buttons": ['excel', 'pdf'], // colvis > if needed
			"fnDrawCallback": function(settings){
				$('[data-toggle="tooltip"]').tooltip();          
			},
			 "fnRowCallback": function( nRow, aData, iDisplayIndex, iDisplayIndexFull ) {
                            
                if ( aData[6] == 'Hadir' ) {
                    $('td', nRow).css('background-color', '#c6edd1' );               
                } else if ( aData[6] == 'Absen' ) {
                    $('td', nRow).css('background-color', '#edeac6' );
                } else if ( aData[6] == 'Sakit' ) {
                    $('td', nRow).css('background-color', '#c6dbed' );
                } else if ( aData[6] == 'Lembur' ) {
                    $('td', nRow).css('background-color', '#dec6ed' );
                } else if ( aData[6] == 'Dinas' ) {
                    $('td', nRow).css('background-color', '#c6e9ed' );
                } else if ( aData[6] == 'Izin' ) {
                    $('td', nRow).css('background-color', '#e7debd' );
                } else if ( aData[6] == 'Cuti' ) {
                    $('td', nRow).css('background-color', '#bde7e7' );
                } else if ( aData[6] == 'Libur' ) {
                    $('td', nRow).css('background-color', '#ddc3c3' );
                } 
             }
		});

		$(".info_karyawan_bulanan").html('Loading ...');
	
		tampilkan_tabel();

		toastr.success('Proses Pencarian Berlangsung');
		xin_table2.api().ajax.reload(function(){ 
			toastr.success('Data Kehadiran Berhasil Ditampilkan.'); 
			$(".info_karyawan_bulanan").html('<i class="fa fa-info-circle"></i> Berikut ini Kehadiran Per Karyawan Bulanan (Reguler).');
		}, true);

		$('.save').prop('disabled', false);	
	});
});