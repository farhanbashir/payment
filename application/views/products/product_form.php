<?php

$product_name ="";
$price = "";
$description = ""; 

if(isset($edit_data) && is_array($edit_data))
{	
	$ArrEditCategories = array();
	foreach ($edit_data as $row) 
	{
		$ArrEditCategoriesId[] = $row['category_id'];
	}
	
	$product_name = $edit_data[0]['product_name'];
	$description = $edit_data[0]['description'];
	$price = $edit_data[0]['price'];

	for ($i=0; $i <count($ArrEditCategoriesId) ; $i++)
	{ 
		$ArrEditCategoriesId[$i]='"'.$ArrEditCategoriesId[$i].'"';
	}
}
?>

<div class="content ">
	<div class="panel panel-default">
		<div class="panel-body">
			<h1>
				<?php echo $form_title;?>
			</h1>
			<div class="col-xs-6">
				<form role="form" action="<?php echo $form_url;?>" method="post">
					<div class="form-group">
						<label>Product Name</label>
						<input type="text" value="<?php echo $product_name;?>" name="product_name" class="form-control" required="">
					</div>
					<div class="form-group">
						<label>Description</label>
						<textarea name="description" class="form-control no-resize" rows="8"><?php echo $description;?></textarea>
					</div>
					<div class="form-group">
						<label>Categories</label>
						<select required="" name="categories[]" id="multi" class="full-width select2-offscreen" multiple="" tabindex="-1">
							<?php
							foreach ($categories as $category) 
							{?>
								<option value="<?php echo $category['category_id'];?>"><?php echo $category['name'];?></option>
								<?php
							}?>
						</select>
					</div>
					<div class="form-group">
						<label>Price</label>
						<input required="" value="<?php echo $price;?>" name="price" type="text" data-a-sign="$ " class="autonumeric form-control">
					</div>
					<br /><br />
					<button class="btn btn-primary" type="submit">Submit</button>
				</form>
			</div>     
		</div>
	</div>
</div>
 <script src="<?php echo asset_url('plugins/jquery/jquery-1.11.1.min.js');?>" type="text/javascript"></script>
<?php if (isset($ArrEditCategories) && is_array($ArrEditCategories))
{?>
	<script>
	 	$( document ).ready(function()
	    {
	    	$("#multi").val([<?php echo implode(', ', $ArrEditCategoriesId);?>]).select2();
	    });
	</script>
	<?php
}else
{?>
	<script>
	 	$( document ).ready(function()
	    {
	    	$("#multi").val([]).select2();
	    });
	</script>
	<?php
}
?>


