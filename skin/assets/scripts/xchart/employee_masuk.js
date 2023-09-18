$(window).on("load", function(){	
	var ctx = $("#employee_masuk");
	Chart.defaults.global.legend.display = false;
	$.ajax({
		url: site_url+'dashboard/employee_masuk/',
		contentType: "application/json; charset=utf-8",
		dataType: "json",
		success: function(response) {
		var bgcolor = [];
		var final = [];
		var final2 = [];
		for(i=0; i < response.c_name.length; i++) {
			final.push(response.chart_data[i].value);
			final2.push(response.chart_data[i].label);
			bgcolor.push(response.chart_data[i].bgcolor);
		} 
		
		// Chart Options
		var chartOptions = {
			events: false,
		    tooltips: {
		        enabled: false
		    },
		    hover: {
		        animationDuration: 0
		    },
		    animation: {
		        duration: 1,
		        onComplete: function () {
		            var chartInstance = this.chart,
		                ctx = chartInstance.ctx;
		            ctx.font = Chart.helpers.fontString(Chart.defaults.global.defaultFontSize, Chart.defaults.global.defaultFontStyle, Chart.defaults.global.defaultFontFamily);
		            ctx.textAlign = 'center';
		            ctx.textBaseline = 'bottom';

		            this.data.datasets.forEach(function (dataset, i) {
		                var meta = chartInstance.controller.getDatasetMeta(i);
		                meta.data.forEach(function (bar, index) {
		                    var data = dataset.data[index];
		                    ctx.fillText(data, bar._model.x, bar._model.y - 5);
		                });
		            });
		        }
		    }
		};
	
		// Chart Data
		var chartData = {
			labels: final2,
			datasets: [{
				label: "Jumlah ",
				data: final,
				backgroundColor: bgcolor,
			}]
		};
	
		var config = {
			type: 'bar',
			options : chartOptions,
			data : chartData
		};
	
		var doughnutSimpleChart = new Chart(ctx, config);
		},

		error: function(data) {
			console.log(data);
		}
	});
	
});
   