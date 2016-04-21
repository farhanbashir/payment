<?php 
$ArrColumns = array();

foreach ($category_sales as $row)
{
  $ArrColumns[] = $row['category_name']; 
}

$ArrCategories = array_unique($ArrColumns);

$ArrCategories = array_values($ArrCategories);

foreach ($category_sales as $row) 
{ 
  
  
  $ArrData['Day'] = $row['order_date'];
  
  for ($i=0; $i <count($ArrCategories) ; $i++) 
  { 
    if($row['category_name']==$ArrCategories[$i])
    {
      $ArrData[$ArrCategories[$i]] = $row['total_sale'];

    }
    else
    {
      $ArrData[$ArrCategories[$i]] = 0;
    }
  }

  $GraphData[]= $ArrData;
}
?>
<script type="text/javascript">
      google.charts.load('current', {'packages':['line']});
      google.charts.setOnLoadCallback(drawChart);

    function drawChart() {

      var data = new google.visualization.DataTable();
     data.addColumn('string', 'Date');

      <?php 
      for ($i=0; $i <count($ArrCategories) ; $i++) 
        {?>
          data.addColumn('number', '<?php echo $ArrCategories[$i];?>');
           
          <?php
        }?>
        data.addRows
        ([
          <?php
          foreach ($GraphData as $row) 
          {   
            echo "['".date("m-d",strtotime($row['Day']))."',";
            for ($i=0; $i <count($ArrCategories) ; $i++)
            { 
              echo $row[$ArrCategories[$i]].",";
            }
            echo "],";
          }
          ?>
      ]);

      var options = {
        chart: {
          /*title: 'Box Office Earnings in First Two Weeks of Opening',
          subtitle: 'in millions of dollars (USD)'*/
        },
        vAxis: {title: "# Price"},
        width: 900,
        height: 500,
        
      };

      var chart = new google.charts.Line(document.getElementById('curve_chart'));

      chart.draw(data, options);
    }
</script>

<div id="curve_chart" style="width: 1200px; height: 800px"></div>
<div id="tableWithSearch_wrapper" class="dataTables_wrapper form-inline no-footer">
  <div class="table-responsive">
    <table class="table table-hover demo-table-search dataTable no-footer" id="" role="grid" aria-describedby="tableWithSearch_info">
      <thead>
        <tr role="row">
          <th width="20%" class="sorting_asc" tabindex="0" aria-controls="tableWithSearch" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Product: activate to sort column descending">Date</th>
          <th width="20%" class="sorting" tabindex="0" aria-controls="tableWithSearch" rowspan="1" colspan="1" aria-label="Categories: activate to sort column ascending" style="width: 198px;">Category</th>
          <th width="10%" class="sorting" tabindex="0" aria-controls="tableWithSearch" rowspan="1" colspan="1" aria-label="Price: activate to sort column ascending" style="width: 79px;">Quantity</th>
          <th width="10%" class="sorting" tabindex="0" aria-controls="tableWithSearch" rowspan="1" colspan="1" aria-label="Price: activate to sort column ascending" style="width: 79px;">Gross Sale</th>

        </tr>
      </thead>
      <tbody>
        <?php
        foreach ($category_sales as $row) 
        {?>
          <tr role="row" class="odd">
            <td class="v-align-middle sorting_1">
              <p><?php echo date('F , m, Y',strtotime($row['order_date']));?></p>
            </td>
            <td class="v-align-middle">
              <p><?php echo $row['category_name'];?></p>
            </td>
            <td class="v-align-middle">
              <p><?php echo $row['total_quantity'];?></p>
            </td>
            <td class="v-align-middle">
              <p><?php echo $row['total_sale'];?></p>
            </td>
          </tr>
          <?php
        }
        ?>
        
      </tbody>
    </table>
  </div>
</div>
