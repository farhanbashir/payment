
<script type="text/javascript">
google.charts.load('current', {'packages':['corechart']});
google.charts.setOnLoadCallback(drawChart);google.charts.setOnLoadCallback(drawChart);
function drawChart() {
	var data = google.visualization.arrayToDataTable
	([
		

		['Dates','Order(s)'],
		<?php 
			foreach ($order_summary as $row)
			{?>
				['<?php echo  date("m-d", strtotime($row["Dates"]));?>', <?php echo intval($row["Total_Orders"]);?> ],
				<?php
			}
		?>
	
	]);

	var options = {
		
		curveType: 'function',
		legend: { position: 'bottom' },
		vAxis: {minValue: 0},
		hAxis: { minValue: 0, maxValue: 12 },
		pointSize:12,
	};

	var chart = new google.visualization.LineChart(document.getElementById('curve_chart'));

	chart.draw(data, options);
}
</script>

<div id="curve_chart" style="width: 1200px; height: 500px"></div>