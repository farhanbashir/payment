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

if(empty($category_sales))
{

  echo "No Records Founds";
}
else
{?>

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
  <div id="" class="dataTables_wrapper form-inline no-footer">
    <div class="">
      <table class="table table-hover demo-table-search dataTable no-footer" id="" role="grid" aria-describedby="tableWithSearch_info">
        <thead>
          <tr role="row">
            <th width="20%">Dates</th>
            <th width="20%">Category</th>
            <th width="20%">Quantity</th>
            <th width="20%">Gross Sales</th>
          </tr>
        </thead>
        <tbody>
          <?php
          foreach ($category_sales as $row) 
          {?>
            <tr role="row" class="odd">
              <td class="v-align-middle sorting_1">
                <p><?php echo date('F m, Y',strtotime($row['order_date']));?></p>
              </td>
              <td class="v-align-middle">
                <p><?php echo $row['category_name'];?></p>
              </td>
              <td class="v-align-middle">
                <p><?php echo $row['total_quantity'];?></p>
              </td>
              <td class="v-align-middle">
                <p><?php echo CONST_CURRENCY_DISPLAY.$row['total_sale'];?></p>
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