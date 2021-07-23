
function barChart(data)
{
           // var data = results.CmdparClient;
            var CLname = [];
            var count = [];
       		var coloR = [];

			var dynamicColors = function() {
        		var r = Math.floor(Math.random() * 255);
				var g = Math.floor(Math.random() * 255);
				var b = Math.floor(Math.random() * 255);
				return "rgb(" + r + "," + g + "," + b + ")";
		    };
			for (var i in data) {
                CLname.push(data[i].CLname);
                count.push(data[i].count);
			    coloR.push(dynamicColors());
			}
            var chartdata = {
                labels: CLname,
                datasets: [
                    {
                     // label: ' Nb Cmd par Validation',
                        backgroundColor: '#49e2ff',
                        borderColor: '#46d5f1',
                        hoverBorderColor: '#666666',
                        data: count,
                        backgroundColor: coloR,
                        borderColor: [
                          'rgba(255,99,132,0.5)',
                          'rgba(54, 162, 235, 1)',
                          'rgba(255, 206, 86, 1)',
                          'rgba(75, 192, 192, 1)',
                          'rgba(153, 102, 255, 1)',
                          'rgba(255, 159, 64, 1)'
                        ],
                           borderWidth: 1
                    }
                ]
            };
            var graphTarget = $("#graph_clients_active");
            var barGraph = new Chart(graphTarget, {
                type: 'horizontalBar',
                data: chartdata,
           		borderWidth: 1
            });
}
