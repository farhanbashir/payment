
<script type="text/javascript">
google.charts.load('current', {'packages':['corechart']});
google.charts.setOnLoadCallback(drawChart);
function drawChart() {
	var data = google.visualization.arrayToDataTable
	([
		

		['Dates', 'Sales','Refund'],
		<?php 
			foreach ($sales_summary as $row)
			{?>
				['<?php echo  date("m-d", strtotime($row["order_date"]));?>', <?php echo intval($row["total_sale"]);?>,<?php echo intval($row["total_refund"]);?> ],
				<?php
			}
		?>
	
	]);

	var options = {
		
		curveType: 'function',
		legend: { position: 'bottom' },
		vAxis: {minValue: 0},
		hAxis: { minValue: 0, maxValue: 9 },
		pointSize:10,
	};

	var chart = new google.visualization.LineChart(document.getElementById('curve_chart'));

	chart.draw(data, options);
}
</script>

<div id="curve_chart" style="width: 1200px; height: 500px"></div>

<div id="tableWithSearch_wrapper" class="dataTables_wrapper form-inline no-footer">
                <div class="table-responsive_UJ">
                    <div id="tableWithSearch_wrapper" class="dataTables_wrapper form-inline no-footer"><div class="table-responsive"><table class="table table-hover demo-table-search_UJ dataTable no-footer" id="tableWithSearch" role="grid" aria-describedby="tableWithSearch_info">
            <thead>
              <tr role="row">
              	<th width="20%" class="sorting_asc" tabindex="0" aria-controls="tableWithSearch" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Product: activate to sort column descending">Date</th>
              	<th width="20%" class="sorting" tabindex="0" aria-controls="tableWithSearch" rowspan="1" colspan="1" aria-label="Categories: activate to sort column ascending" style="width: 198px;">Sales</th>
              	<th width="10%" class="sorting" tabindex="0" aria-controls="tableWithSearch" rowspan="1" colspan="1" aria-label="Price: activate to sort column ascending" style="width: 79px;">Refund</th>
              	
              </tr>
            </thead>
            <tbody>
                <tr role="row" class="odd">
                  <td class="v-align-middle sorting_1">
                    <p>04-06-2016</p>
                  </td>
                  <td class="v-align-middle">
                    <p>$1700.00</p>
                  </td>
                  <td class="v-align-middle">
                    <p>$22.00</p>
                  </td>
                  
                </tr></tbody>
          </table></div><div class="row"><div><div class="dataTables_paginate paging_simple_numbers" id="tableWithSearch_paginate"><ul class=""><li class="paginate_button previous disabled" id="tableWithSearch_previous"><a href="#" aria-controls="tableWithSearch" data-dt-idx="0" tabindex="0"><i class="pg-arrow_left"></i></a></li><li class="paginate_button active"><a href="#" aria-controls="tableWithSearch" data-dt-idx="1" tabindex="0">1</a></li><li class="paginate_button "><a href="#" aria-controls="tableWithSearch" data-dt-idx="2" tabindex="0">2</a></li><li class="paginate_button next" id="tableWithSearch_next"><a href="#" aria-controls="tableWithSearch" data-dt-idx="3" tabindex="0"><i class="pg-arrow_right"></i></a></li></ul></div><div class="dataTables_info" id="tableWithSearch_info" role="status" aria-live="polite">Showing <b>1 to 5</b> of 6 entries</div></div></div></div></div>
        </div>