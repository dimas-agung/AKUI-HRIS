$(document).ready(function() {	
	$('[data-plugin="select_hrm"]').select2($(this).attr('data-options'));
	$('[data-plugin="select_hrm"]').select2({ width:'100%' }); 
});
$(document).ready(function() { 
  var xin_table = $('#xin_table_harian').DataTable( {       
		// 	scrollX       : true,
		// scrollY		  : true,
		// scrollCollapse : false,			
		// paging         : true,
		
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