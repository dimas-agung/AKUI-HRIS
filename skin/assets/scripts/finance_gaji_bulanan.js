$(document).ready(function() {

	
	$(".finance_gaji_bulanan").html('<i class="fa fa-warning"></i> Silahkan Klik Tombol "<span class="blink blink_one hijau">Tampilkan</span>" Guna menampilkan Gaji Bulanan.');
	
	function tampilkan_tabel() {
      var x = document.getElementById("myDIV");
      if (x.style.display === "none") {
        x.style.display = "block";
      } 
    } 

	$('[data-plugin="select_hrm"]').select2($(this).attr('data-options'));
	$('[data-plugin="select_hrm"]').select2({ width:'100%' }); 	
	
	
	$("#ihr_report").submit(function(e){
		/*Form Submit*/
		e.preventDefault();
		
		var aj_company   = $('#aj_company').val();
		var salary_month = $('#salary_month').val();
		
		if(aj_company == ''){
		
			toastr.error('Silahkan Pilih Perusahaan');

		} else if(salary_month == ''){
		
			toastr.error('Silahkan Pilih Bulan Gaji');
		
		} else {

			tampilkan_tabel();

			$bulan  ='';

			if (salary_month == '2022-1'){
              $bulan ="Januari 2022";
	        }
	        else if (salary_month == '2022-2'){
	            $bulan ="Februari 2022";
	        }
	        else if (salary_month == '2022-3'){
	            $bulan ="Maret 2022";
	        }
	        else if (salary_month == '2022-4'){
	            $bulan ="April 2022";
	        }
	        else if (salary_month == '2022-5'){
	            $bulan ="Mei 2022";
	        }
	        else if (salary_month == '2022-6'){
	            $bulan ="Juni 2022";
	        }
	        else if (salary_month == '2022-7'){
	            $bulan ="Juli 2022";
	        }
	        else if (salary_month == '2022-8'){
	            $bulan ="Agustus 2022";
	        }
	        else if (salary_month == '2022-9'){
	            $bulan ="September 2022";
	        }
	        else if (salary_month == '2022-10'){
	            $bulan ="Oktober 2022";
	        }
	        else if (salary_month == '2022-11'){
	            $bulan ="November 2022";
	        }
	        else if (salary_month == '2022-12'){
	            $bulan ="Desember 2022";
	        }

			$(".finance_gaji_bulanan").html('Berikut ini Gaji Karyawan Bulanan, Bulan : <b>'+ $bulan +'</b> ');


		 	var xin_table2 = $('#xin_table').dataTable({
				"bDestroy": true,
				"aLengthMenu": [[12, 50, 100, -1], [12, 50, 100, "All"]],
				"ajax": {
					url : site_url+"finance/gaji_bulanan_list/?ihr=true&company_id="+$('#aj_company').val()+"&salary_month="+$('#salary_month').val(),
					type : 'GET'
				},
				"columns": [
					{"name": "kolom_1", "className": "text-center"},
					{"name": "kolom_2", "className": "text-center"},
					{"name": "kolom_3", "className": "text-left"},
					{"name": "kolom_4", "className": "text-left"},
					{"name": "kolom_5", "className": "text-left"},
					{"name": "kolom_6", "className": "text-left"},
					{"name": "kolom_7", "className": "text-right"},
					{"name": "kolom_8", "className": "text-center"},
					{"name": "kolom_8", "className": "text-center"},
			    ],
			    
			    dom: 'lBfrtip',
				"buttons": ['excel'], // colvis > if needed
			    
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
			xin_table2.api().ajax.reload(function(){ }, true);
		}
	});


});