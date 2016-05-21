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

if(empty($order_summary))
{

	echo "No Records Founds";
}
else
{	
?>
	<script type="text/javascript">
	google.charts.load('current', {'packages':['corechart']});
	google.charts.setOnLoadCallback(drawChart);google.charts.setOnLoadCallback(drawChart);
	function drawChart() {
		var data = google.visualization.arrayToDataTable
		([
			

			['Dates','Order(s)','Refund(s)'],
			<?php 
				foreach ($order_summary as $row)
				{?>
					['<?php echo  date("m-d", strtotime($row["order_date"]));?>', <?php echo intval($row["total_order"]);?>,<?php echo intval($row["total_refund"]);?> ],
					<?php
				}
			?>
		
		]);

		var options = {
			
			curveType: 'function',
			legend: { position: 'top' },
  			bar: { groupWidth: '25%' },
			vAxis: {minValue: 0},
			hAxis: { minValue: 0, maxValue: 9 },
			pointSize:10,
		};

		var chart = new google.visualization.ColumnChart(document.getElementById('curve_chart'));

		chart.draw(data, options);
	}
	</script>

	<div id="curve_chart" style="width: 1200px; height: 500px"></div>
	<div id="tableWithSearch_wrapper" class="dataTables_wrapper form-inline no-footer">
		<div class="table-responsive">
			<table class="table table-hover demo-table-search dataTable no-footer" id="" role="grid" aria-describedby="tableWithSearch_info">
				<thead>
					<tr role="row">
						<th width="33.33%">Dates</th>
						<th width="33.33%">Total Orders</th>
						<th width="33.33%">Order Refunds</th>

					</tr>
				</thead>
				<tbody>
					<?php
					foreach ($order_summary as $row) 
					{?>
						<tr role="row" class="odd">
							<td class="v-align-middle sorting_1">
								<p><?php echo date('F d, Y',strtotime($row['order_date']));?></p>
							</td>
							<td class="v-align-middle">
								<p><?php echo $row['total_order']?></p>
							</td>
							<td class="v-align-middle">
								<p><?php echo $row['total_refund'];?></p>
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
}
?>