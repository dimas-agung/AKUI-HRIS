	$(document).ready(function() {

		
		var month_year  = jQuery('#month_year').val();
		var company_id  = jQuery('#aj_company').val();
		
		$('#p_month').html(month_year);
		
	
		$('[data-plugin="select_hrm"]').select2($(this).attr('data-options'));
		$('[data-plugin="select_hrm"]').select2({ width:'100%' }); 
		
		jQuery("#aj_company").change(function(){
			jQuery.get(base_url+"/get_employees/"+jQuery(this).val(), function(data, status){
				jQuery('#employee_ajax').html(data);
			});
		});
		
		jQuery("#aj_companyx").change(function(){
			jQuery.get(escapeHtmlSecure(base_url+"/get_company_plocations/"+jQuery(this).val()), function(data, status){
				jQuery('#location_ajax').html(data);
			});
		});

		// Month & Year
		$('.month_year').datepicker({
			changeMonth: true,
			changeYear: true,
			showButtonPanel: true,
			dateFormat:'yy-mm',
			yearRange: '1970:' + new Date().getFullYear(),
			beforeShow: function(input) {
				$(input).datepicker("widget").addClass('hide-calendar');
			},
			onClose: function(dateText, inst) {
				var month = $("#ui-datepicker-div .ui-datepicker-month :selected").val();
				var year = $("#ui-datepicker-div .ui-datepicker-year :selected").val();
				$(this).datepicker('setDate', new Date(year, month, 1));
				$(this).datepicker('widget').removeClass('hide-calendar');
				$(this).datepicker('widget').hide();
			}

		});

				
	

});

$( document ).on( "click", ".delete", function() {
$('input[name=_token]').val($(this).data('record-id'));
$('#delete_record').attr('action',base_url+'/payslip_delete/'+$(this).data('record-id'))+'/';
});
