<div class="form-group">
	<form role="form" method="post" action="">
		<div class="form-group"style="width: 200px;">
			<select name="select" class="full-width select2-offscreen" data-init-plugin="select2" tabindex="-1" title="">
				<option value="0">Summary</option>
				<option <?php echo $SelctedValue['Daily'];?> value="Daily">Daily</option>
				<option <?php echo $SelctedValue['Weekly'];?> value="Weekly">Weekly</option>
				<option <?php echo $SelctedValue['Monthly'];?> value="Monthly">Monthly</option>
			</select>
		</div>
		<div style="margin-top: -38px;margin-left: 209px;">
			<label>OR</label>
		</div>
		<div class="input-daterange input-group" id="datepicker-range" style="margin-top: -31px;margin-left: 236px;">
			<input value="<?php echo $SelctedValue['FromDate'];?>"  name="FromDate" type="text" class="input-sm form-control" name="start">
			<span class="input-group-addon">to</span>
			<input value="<?php echo $SelctedValue['ToDate'];?>" name="ToDate" type="text" class="input-sm form-control" name="end">

		</div>

		<button style="margin-top: -57px;margin-left: 646px;" class="btn btn-primary" type="submit">Submit</button>
	</form>
</div>
<?php

if(empty($item_sales))
{

	echo "No Records Founds";
}
else
{?>
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
	    					<th width="20%">Dates</th>
	    					<th width="20%">Products</th>
	    					<th width="20%">Category</th>
	    					<th width="10%">Quantity</th>
	    					<th width="10%">Gross Sales</th>

	    				</tr>
	    			</thead>
	    			<tbody>
	    				<?php
	    				foreach ($item_sales as $row) 
	    					{?>
	    				<tr role="row" class="odd">
	    					<td class="v-align-middle sorting_1">
	    						<p><?php echo date('F d, Y',strtotime($row['order_date']));?></p>
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
	    						<p><?php echo CONST_CURRENCY_DISPLAY.$row['total_price'];?></p>
	    					</td>
	    				</tr>
	    				<?php
	    			}
	    			?>

	    		</tbody>
	    	</table>
	    </div>
	</div>
	<?php
}?>