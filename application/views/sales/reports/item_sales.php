<?php 
$ArrColumns = array();

foreach ($item_sales as $row)
{
	$ArrColumns[] = $row['product_name']; 
}
$Columns = array_unique($ArrColumns);
$Columns = array_values($Columns);

$GraphData = array();

$ArrData = array();

foreach ($item_sales as $row) 
{	
	
	
	$ArrData['Day'] = $row['order_date'];
	
	for ($i=0; $i <count($Columns) ; $i++) 
	{ 
		if($row['product_name']==$Columns[$i])
		{
			$ArrData[$Columns[$i]] = $row['total_price'];
		}
		else
		{
			$ArrData[$Columns[$i]] = 0;
		}
	}

	$GraphData[]= $ArrData;
}

?>
<script type="text/javascript">
	//google.charts.load('current', {'packages':['line', 'corechart']});
    google.charts.load('current', {'packages':['line', 'corechart']});
    google.charts.setOnLoadCallback(drawChart);

    function drawChart() {

      var data = new google.visualization.DataTable();
      
      data.addColumn('string', 'Date');

      <?php 
      for ($i=0; $i <count($Columns) ; $i++) 
      {?>
      		data.addColumn('number', '<?php echo $Columns[$i];?>');
      		 
      		<?php
      }?>

     
    

      data.addRows([
      		<?php
        	foreach ($GraphData as $row) 
        	{ 	
        		echo "['".date("m-d",strtotime($row['Day']))."',";
        		for ($i=0; $i <count($Columns) ; $i++)
        		{ 
        			echo $row[$Columns[$i]].",";
        		}
        		echo "],";
        	}
        	?>
        
      ]);

      var options = {
        chart: {
          
        },
        chartArea:{left:100,top:20,width:"100%",height:"100%"},
        width: 1150,
        height: 500,
         series: {
          // Gives each series an axis name that matches the Y-axis below.
          0: {axis: 'Dollar'},
         
        },
        axes: {
          // Adds labels to each axis; they don't have to match the axis names.
          y: {
            Dollar: {label: 'USD ($)'},
           
          }
        },
        pointSize: 20,
      };

      var chart = new google.charts.Line(document.getElementById('curve_chart'));

      chart.draw(data, options);
  }
 /*google.charts.load('current', {'packages':['corechart']});
      google.charts.setOnLoadCallback(drawChart);

      function drawChart() {
        var data = google.visualization.arrayToDataTable([
          	['Date',
	          <?php
	          for ($i=0; $i <count($Columns) ; $i++) 
		      {?>
		      		 '<?php echo $Columns[$i];?>',
		      		 
		      		<?php
		      }?>
           	],
           
           		<?php
	        	foreach ($GraphData as $row) 
	        	{ 	
	        		echo "['".date("m-d",strtotime($row['Day']))."',";
	        		for ($i=0; $i <count($Columns) ; $i++)
	        		{ 
	        			echo $row[$Columns[$i]].",";
	        		}
	        		echo "],";
	        	}
	        	?>
           
        ]);

        var options = {
          title: 'Company Performance',
          curveType: 'function',
          legend: { position: 'bottom' }
        };

        var chart = new google.visualization.LineChart(document.getElementById('curve_chart'));

        chart.draw(data, options);
      }*/
</script>

<div id="curve_chart" style="width: 1100px; height: 500px"></div>