$(document).ready(function(){
	
	/* Clock In */
	$("#set_clocking").submit(function(e){
		
		e.preventDefault();
		var clock_state = '';
		var obj = $(this), action = obj.attr('name');
		navigator.geolocation.getCurrentPosition(function(position) {

		  // Get the coordinates of the current possition.
		  var lat = position.coords.latitude;
		  var lng = position.coords.longitude;
	
		
		$.ajax({
			type: "POST",
			url: e.target.action,
			data: obj.serialize()+"&is_ajax=1&type=set_clocking&latitude="+lat+"&longitude="+lng+"&form="+action,
			cache: false,
			success: function (JSON) {
				if (JSON.error != '') {
					alert_fail('Gagal',JSON.error);
				} else {
					alert_success('Sukses',JSON.result);
					window.location = '';
				}
			}
		});
		}); // geolocation
	});
});