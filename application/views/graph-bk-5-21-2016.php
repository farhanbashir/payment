<?php

?>

<script>

 // Load the Visualization API and the piechart package.
      google.load('visualization', '1.0', {'packages':['corechart','line']});

      // Set a callback to run when the Google Visualization API is loaded.
      google.setOnLoadCallback(drawChart);

      // Callback that creates and populates a data table,
      // instantiates the pie chart, passes in the data and
      // draws it.
      	function drawChart() 
      	{
      		<?php
      		if(!empty($item_sales))
      		{


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
		        // Create the data table.
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
				var options = 	{	
									titleTextStyle: {
							     	color: '333333',
							        fontName: 'Arial',
								    fontSize: 10
								    },
		                       		'width':1150,
		                       		'height':400
	                       		};
	            var chart = new google.visualization.LineChart(document.getElementById('chart_div'));
        		chart.draw(data, options);
	            <?php

	        }?>
        
        // SET CATEGORY TABLE DATA
        <?php 
        if(!empty($category_sales))
        {
			$ArrColumns = array();
			$GraphData = array();
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
			//SET CATEGORY TABLE DATA
			var data2 = new google.visualization.DataTable();
	        data2.addColumn('string', 'Date');

	        <?php 
	        for ($i=0; $i <count($ArrCategories) ; $i++) 
	        {?>
	            data2.addColumn('number', '<?php echo $ArrCategories[$i];?>');
	             
	            <?php
	        }?>
	        data2.addRows
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

	        var options2 = 	{
	                       'width':1150,
	                       'height':400};
            var chart2 = new google.visualization.LineChart(document.getElementById('chart_div2'));
        	chart2.draw(data2, options2);           
	    	<?php
	    }?>
        /*var data3 = new google.visualization.DataTable();
        data3.addColumn('string', 'Year');
        data3.addColumn('number', 'Sales');
        data3.addColumn('number', 'Expenses');
        data3.addRows([
          ['2004', 1000, 400],
          ['2005', 1170, 460],
          ['2006',  860, 580],
          ['2007', 1030, 540]
        ]);
       
        // Set chart options
        var options3 = {'title':'Line chart',
                       'width':400,
                       'height':300};

        // Instantiate and draw our chart, passing in some options.
       
      
        var chart3 = new google.visualization.LineChart(document.getElementById('chart_div3'));
        chart3.draw(data3, options3);*/

      }
    </script>
  </head>

  <body>
    <!--Divs that will hold the charts-->
    <h1>Monthly Item Sales</h1>
    
    <?php if(empty($category_sales))
    {
    	echo "No Records Found";
    }?>
    <div id="chart_div"></div>
    <br>
	<h1>Monthly Category Sales</h1>
	
    <?php if(empty($item_sales))
    {
    	echo "No Records Found";
    }?>
    <div id="chart_div2"></div>
    
  </body>
</html>