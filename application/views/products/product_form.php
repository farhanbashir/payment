<?php
$file_name = "";
$product_name ="";
$price = "";
$description = ""; 

if(isset($postedData) && !empty($postedData))
{	
	if(isset($postedData['categories']) && !empty($postedData['categories']))
	{
		$ArrEditCategories = array();
		for ($i=0;$i<count($postedData['categories']);$i++) 
		{
			$ArrEditCategoriesId[] = $postedData['categories'][$i];
		}
	}
	$product_name = $postedData['product_name'];
	$description = $postedData['description'];
	$price = $postedData['price'];
	$file_name = $postedData['old_image'];
	if(!empty($ArrEditCategories))
	{
		for ($i=0; $i <count($ArrEditCategoriesId) ; $i++)
		{ 
			$ArrEditCategoriesId[$i]='"'.$ArrEditCategoriesId[$i].'"';
		}
	}
}
?>

<div class="content ">
	<div class="panel panel-default">
		<div class="panel-body">
			<?php 
		        if($this->session->flashdata('showErrorMessage')!='')
	          	{?>   
	        	    <div class="alert alert-danger">
		              <strong>Alert!</strong>&nbsp;&nbsp;<?php echo $this->session->flashdata('showErrorMessage');?>
		            </div>
	        	    <?php 
	        	}
		    ?>
			<h1>
				<?php echo $formHeading;?>
			</h1>
			<div class="col-xs-6">
				<form role="form" method ="post"action="" enctype="multipart/form-data" accept-charset="utf-8">
					<div class="form-group">
						<label>Product Name</label>
						<input type="text" value="<?php echo $product_name;?>" name="product_name" class="form-control" >
					</div>
					<div class="form-group">
						<label>Description</label>
						<textarea name="description" class="form-control no-resize" rows="8"><?php echo $description;?></textarea>
					</div>
					<div class="form-group">
						<label>Categories</label>
						<select  name="categories[]" id="multi" class="full-width select2-offscreen" multiple="" tabindex="-1">
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
						<input  value="<?php echo $price;?>" name="price" type="text" data-a-sign="$ " class="autonumeric form-control">
					</div>
					<div class="form-group">
						<label>Product Image</label>
						<input type="file" name="image" >
						<input type="hidden" name="old_image" value="<?php echo $file_name;?>">
					</div>
					<?php
					if($file_name)						
					{
						?>
							<br /><br />
							<img src="<?php echo $file_name;?>" width="150" alt="." />
						<?php
					}
					?>	
					<br /><br />
					<button name="btn-submit" value="submit" class="btn btn-primary" type="submit">Submit</button>
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


