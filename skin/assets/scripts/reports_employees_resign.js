$(document).ready(function() {  

 $(".info_resign").html('<i class="fa fa-warning"></i> Silahkan klik tombol <span class="blink blik-one hijau"><b>Tampilkan</b></span> terlebih dahulu ');
                  
   var xin_table = $('#xin_emp_resign').dataTable({
        "bDestroy"        : true,
		"bSort"           : false,
		"aLengthMenu"     : [[10, 30, 50, 100, -1], [10, 30, 50, 100, "All"]],			
		autoWidth         : true,  
		"fixedColumns"    : true,
		"fixedColumns"    : {
			leftColumns   : 6
		},	
		"ajax": {
            url : site_url+"reports/report_employees_resign_list/0/0/",
            type : 'GET'
        },
		dom: 'lBfrtip',
		buttons: [
          'print', {
            extend: 'pdf',
            orientation: 'landscape'
          },
          'excel'
        ],
		"columns": [
			{"name": "no", "className": "text-center"},
			{"name": "no", "className": "text-center"},
			{"name": "no", "className": "text-center"},
			{"name": "no", "className": "text-center"},
			{"name": "no", "className": "text-center"},
			{"name": "no", "className": "text-left"},
			{"name": "no", "className": "text-left"},
			{"name": "no", "className": "text-left"},
			{"name": "no", "className": "text-left"},
			{"name": "no", "className": "text-left"},
			{"name": "no", "className": "text-center"},
			
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
	
	

	$("#employees_resign").submit(function(e){
		/*Form Submit*/
		e.preventDefault();
		var company_id     = $('#aj_company').val();
		var month_year     = $('#month_year').val();

		 $(".info_resign").html('Loading ...');
                  
		
		// ===================================================================
		// DATA TABEL
		// ===================================================================
		var xin_table2     = $('#xin_emp_resign').dataTable({
			"bDestroy"        : true,
			"bSort"           : false,
			"aLengthMenu"     : [[10, 30, 50, 100, -1], [10, 30, 50, 100, "All"]],			
			autoWidth         : true,  
			"fixedColumns"    : true,
			"fixedColumns"    : {
				leftColumns   : 6
			},	
			"ajax": {
				url : site_url+"reports/report_employees_resign_list/"+company_id+"/"+month_year+"/",
				type : 'GET'
			},
			dom: 'lBfrtip',
			buttons: [
	          'print', {
	            extend: 'pdf',
	            orientation: 'landscape'
	          },
	          'excel'
	        ],
			"columns": [
				{"name": "no", "className": "text-center"},
				{"name": "no", "className": "text-center"},
				{"name": "no", "className": "text-center"},
				{"name": "no", "className": "text-center"},
				{"name": "no", "className": "text-center"},
				{"name": "no", "className": "text-left"},
				{"name": "no", "className": "text-left"},
				{"name": "no", "className": "text-left"},
				{"name": "no", "className": "text-left"},
				{"name": "no", "className": "text-left"},
				{"name": "no", "className": "text-center"},
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

		$.ajax({
            type : "GET",
            url : site_url+"reports/resume_employees_resign_jumlah/"+company_id+"/"+month_year+"/",
            // url  : '<?php echo base_url();?>admin/reports/resume_employees_resign_jumlah/',
            data : { 
                company_id : company_id,
                 month_year : month_year
                
            },
            dataType : "json",
            success:function(data){                        
                
                for(var i=0; i<data.val.length;i++){
                    
                   $(".info_resign").html('<i class="fa fa-info-circle"></i> Berikut ini Daftar Karyawan Resin <b>'+ data.val[i].company +'</b>, Bulan : <b>'+ data.val[i].bulan +'</b>, Jumlah Karyawan : <b> '+ data.val[i].jumlah_karyawan +' Orang </b> ');
                  
                  
                }
            }
	    });
		toastr.success('Data Berhasil Ditampilkan.');
		xin_table2.api().ajax.reload(function(){ }, true);
	});
});