$(document).ready(function() {	
	$('[data-plugin="select_hrm"]').select2($(this).attr('data-options'));
	$('[data-plugin="select_hrm"]').select2({ width:'100%' }); 
});

$(document).ready(function() { 
	
    
    jQuery("#company_id").change(function(){
		var c_id = jQuery(this).val();
		jQuery.get(base_url+"/get_workstations/"+c_id, function(data, status){
			jQuery('#workstation_ajax').html(data);			
		});
		// if(c_id == 0){
		// 	jQuery.get(base_url+"/designation/"+jQuery(this).val(), function(data, status){
		// 		jQuery('#designation_ajax').html(data);
		// 	});
		// }
	});

	var xin_table = $('#xin_table_borongan').DataTable( {  		
		"bDestroy"        : true,
		"bSort"           : false,
		"aLengthMenu"     : [[10, 30, 50, 100, -1], [10, 30, 50, 100, "All"]],			
		autoWidth         : true,  
		"fixedColumns"    : true,
		"fixedColumns"    : {
			leftColumns   : 2
		},	
		columnDefs: [
			{ "width": "5px", "targets": [0] },
			{ "width": "240px", "targets": [1] },
	    ],
	    dom: 'lBfrtip',
	    "buttons": ['excel']		
	});	

  
	
});