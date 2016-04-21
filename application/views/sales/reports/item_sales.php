<?php 
$ArrColumns = array();

foreach ($item_sales as $row)
{
	$ArrColumns[] = $row['product_name']; 
}

$ArrProducts = array_unique($ArrColumns);

$ArrProducts = array_values($ArrProducts);

$GraphData = array();

$ArrData = array();

foreach ($item_sales as $row) 
{	
	
	
	$ArrData['Day'] = $row['order_date'];
	
	for ($i=0; $i <count($ArrProducts) ; $i++) 
	{ 
		if($row['product_name']==$ArrProducts[$i])
		{
			$ArrData[$ArrProducts[$i]] = $row['total_price'];

		}
		else
		{
			$ArrData[$ArrProducts[$i]] = 0;
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
      for ($i=0; $i <count($ArrProducts) ; $i++) 
      	{?>
      		data.addColumn('number', '<?php echo $ArrProducts[$i];?>');
      		 
      		<?php
      	}?>
      	data.addRows
      	([
      		<?php
        	foreach ($GraphData as $row) 
        	{ 	
        		echo "['".date("m-d",strtotime($row['Day']))."',";
        		for ($i=0; $i <count($ArrProducts) ; $i++)
        		{ 
        			echo $row[$ArrProducts[$i]].",";
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
         3: {axis: 'Dollar'},
         
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
	          for ($i=0; $i <count($ArrProducts) ; $i++) 
		      {?>
		      		 '<?php echo $ArrProducts[$i];?>',
		      		 
		      		<?php
		      }?>
           	],
           
           		<?php
	        	foreach ($GraphData as $row) 
	        	{ 	
	        		echo "['".date("m-d",strtotime($row['Day']))."',";
	        		for ($i=0; $i <count($ArrProducts) ; $i++)
	        		{ 
	        			echo $row[$ArrProducts[$i]].",";
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
<div id="tableWithSearch_wrapper" class="dataTables_wrapper form-inline no-footer">
	<div class="table-responsive">
		<table class="table table-hover demo-table-search dataTable no-footer" id="" role="grid" aria-describedby="tableWithSearch_info">
			<thead>
				<tr role="row">
					<th width="20%" class="sorting_asc" tabindex="0" aria-controls="tableWithSearch" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Product: activate to sort column descending">Date</th>
					<th width="20%" class="sorting_asc" tabindex="0" aria-controls="tableWithSearch" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Product: activate to sort column descending">Product</th>
					<th width="20%" class="sorting" tabindex="0" aria-controls="tableWithSearch" rowspan="1" colspan="1" aria-label="Categories: activate to sort column ascending" style="width: 198px;">Category</th>
					<th width="10%" class="sorting" tabindex="0" aria-controls="tableWithSearch" rowspan="1" colspan="1" aria-label="Price: activate to sort column ascending" style="width: 79px;">Quantity</th>
					<th width="10%" class="sorting" tabindex="0" aria-controls="tableWithSearch" rowspan="1" colspan="1" aria-label="Price: activate to sort column ascending" style="width: 79px;">Gross Sale</th>

				</tr>
			</thead>
			<tbody>
				<?php
				foreach ($item_sales as $row) 
				{?>
					<tr role="row" class="odd">
						<td class="v-align-middle sorting_1">
							<p><?php echo date('F , m, Y',strtotime($row['order_date']));?></p>
						</td>
						<td class="v-align-middle">
							<p><?php echo $row['product_name']?></p>
						</td>
						<td class="v-align-middle">
							<p><?php echo $row['category_name'];?></p>
						</td>
						<td class="v-align-middle">
							<p><?php echo $row['total_quantity'];?></p>
						</td>
						<td class="v-align-middle">
							<p><?php echo $row['total_price'];?></p>
						</td>
					</tr>
					<?php
				}
				?>
				
			</tbody>
		</table>
	</div>
</div>