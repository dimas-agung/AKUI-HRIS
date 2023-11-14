$(document).ready(function(){	

	

	// ================================================================================================================
    // SHOW TIME
    // ================================================================================================================
        function showTime() 
        {
            var a_p = "";
            var today = new Date();
            var curr_hour = today.getHours();
            var curr_minute = today.getMinutes();
            var curr_second = today.getSeconds();

            var months = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];          
            var myDays = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
            
            var date = new Date();
            var day = date.getDate();
            var month = date.getMonth();
            var thisDay = date.getDay(),
                thisDay = myDays[thisDay];
            var yy = date.getYear();
            var year = (yy < 1000) ? yy + 1900 : yy;

              if (curr_hour < 12) {
                  a_p = "AM";
              } else {
                  a_p = "PM";
              }
              if (curr_hour == 0) {
                  curr_hour = 12;
              }
              if (curr_hour > 12) {
                  curr_hour = curr_hour - 12;
              }
              curr_hour = checkTime(curr_hour);
              curr_minute = checkTime(curr_minute);
              curr_second = checkTime(curr_second);
            document.getElementById('clock').innerHTML=" " + thisDay + ", " + day + " " + months[month] + " " + year + ", " + curr_hour + ":" + curr_minute + ":" + curr_second + " " + a_p;
        }
        function checkTime(i) 
        {
              if (i < 10) {
                  i = "0" + i;
              }
              return i;
        }  
        setInterval(showTime, 500); 
        
	$('.policy').on('show.bs.modal', function (event) {
	$.ajax({
		url: site_url+'settings/policy_read/',
		type: "GET",
		data: 'jd=1&is_ajax=1&mode=modal&data=policy&type=policy&p=1',
		success: function (response) {
			if(response) {
				$("#policy_modal").html(response);
			}
		}
		});
	});
	
	jQuery(".hris_layout").change(function(){
		if($('#fixed_layout_hris').is(':checked')){
			var fixed_layout_hris = $("#fixed_layout_hris").val();
			
		} else {
			var fixed_layout_hris = '';
		}
		if($('#boxed_layout_hris').is(':checked')){
			var boxed_layout_hris = $("#boxed_layout_hris").val();
		} else {
			var boxed_layout_hris = '';
		}
		if($('#sidebar_layout_hris').is(':checked')){
			var sidebar_layout_hris = $("#sidebar_layout_hris").val();
		} else {
			var sidebar_layout_hris = '';
		}
	
		$.ajax({
			type: "GET",  url: site_url+"settings/layout_skin_info/?is_ajax=2&type=hris_layout_info&form=2&fixed_layout_hris="+fixed_layout_hris+"&boxed_layout_hris="+boxed_layout_hris+"&sidebar_layout_hris="+sidebar_layout_hris+"&user_session_id="+user_session_id,
			//data: order,
			success: function(response) {
				if (response.error != '') {
					toastr.error(response.error);
				} else {
					toastr.success(response.result);	
				}
			}
		});
	});
	//
	jQuery("#fixed_layout_hris").click(function(){
		if($('#fixed_layout_hris').is(':checked')){
			//$('#boxed_layout_hris').prop('checked', false);
		}
	});
	jQuery("#boxed_layout_hris").click(function(){
		if($('#boxed_layout_hris').is(':checked')){
			$('.hris-layout').removeClass('fixed');
			$('#fixed_layout_hris').prop('checked', false);
		}
	});
});