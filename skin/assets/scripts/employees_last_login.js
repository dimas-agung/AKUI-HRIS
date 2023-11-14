$(document).ready(function() {
   var xin_table = $('#xin_table').dataTable({
        "bDestroy": true,
		"ajax": {
            url : base_url+"/last_login_list/",
            type : 'GET'
        },
         "columns": [
			{"name": "kolom_1","orderable": false,"searchable": false,  "className": "text-center", "width": "5%"},
			{"name": "kolom_2", "className": "text-center"},
			{"name": "kolom_2", "className": "text-center"},
			{"name": "kolom_3", "className": "text-center"},
			{"name": "kolom_4", "className": "text-left"},
			{"name": "kolom_5", "className": "text-left"},
			{"name": "kolom_6", "className": "text-left"},				
			{"name": "kolom_7", "className": "text-left"},	
			{"name": "kolom_7", "className": "text-left"},			
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
	$('[data-plugin="select_hrm"]').select2($(this).attr('data-options'));
	$('[data-plugin="select_hrm"]').select2({ width:'100%' }); 

	jQuery("#filter_company").change(function(){
		if(jQuery(this).val() == 0){
			jQuery('#filter_location').prop('selectedIndex', 0);	
			jQuery('#filter_department').prop('selectedIndex', 0);
			jQuery('#filter_designation').prop('selectedIndex', 0);
		}
		jQuery.get(escapeHtmlSecure(site_url+"employees/filter_company_flocations/"+jQuery(this).val()), function(data, status){
		jQuery('#location_ajaxflt').html(data);
			
		});
	});
	
	$("#ihr_report").submit(function(e){
		/*Form Submit*/
		e.preventDefault();
		//$('#hrload-img').show();
		//toastr.info(processing_request);
			 var xin_table2 = $('#xin_table').dataTable({
				"bDestroy": true,
				"ajax": {
					url : site_url+"employees_last_login/last_login_list/?ihr=true&company_id="+$('#filter_company').val()+"&location_id="+$('#filter_location').val()+"&department_id="+$('#filter_department').val()+"&designation_id="+$('#filter_designation').val(),
					type : 'GET'
				},
				 "columns": [
					{"name": "kolom_1","orderable": false,"searchable": false,  "className": "text-center", "width": "5%"},
					{"name": "kolom_2", "className": "text-center"},
					{"name": "kolom_2", "className": "text-center"},
					{"name": "kolom_3", "className": "text-center"},
					{"name": "kolom_4", "className": "text-left"},
					{"name": "kolom_5", "className": "text-left"},
					{"name": "kolom_6", "className": "text-left"},					
					{"name": "kolom_8", "className": "text-left"},			
					{"name": "kolom_7", "className": "text-left"},		
			    ],
				"fnDrawCallback": function(settings){
					$('[data-toggle="tooltip"]').tooltip();          
				}
			});
			xin_table2.api().ajax.reload(function(){ toastr.success(request_submitted);}, true);
	});
});