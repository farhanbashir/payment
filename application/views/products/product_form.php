
<?php


$file_name = "";
$product_name ="";
$price = "";
$description = ""; 
$productImages = "";

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
	$productImages = $postedData['productImages'];

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
          		{
		            echo getHTMLForErrorMessage($this->session->flashdata('showErrorMessage'));
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
					<div id="images">

						<?php
							if($file_name)						
							{
								?>
									<br /><br />
									<img src="<?php echo $file_name;?>" width="150" alt="." />
									<button name="btn-submit" value="<?php echo $file_name;?>" onclick="return deleteProductImage(this.value)" class="btn btn-danger" type="button">Delete</button>
									<br /><br />

								<?php
							}
						?>
					</div>	
					<div class="imageUploadError">

					</div>
					<div class="form-group" id="imageUpload">
						<label>Add Product Images</label>
						<input type="file" id="image" name="image" >
						<img src='<?php echo asset_url('img/loader.gif');?>' height='20'; id="loader">
						<input type="hidden" name="old_image" value="<?php echo $file_name;?>">
						<input type="hidden" id ="productImages" name="imges[]" value="<?php echo $productImages[0];?>">
					</div>
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
<script>
$( document ).ready(function()
{	
	console.log($("#productImages").val())
	$("loader").hide();

	$("#image").change(function()
	{	
		$("loader").show();
		$("image").hide();
		
		var file_data = $("#image").prop("files")[0]; 

    	var form_data = new FormData();   

	    form_data.append("file", file_data)

	    var allowFileTypes = ["image/png", "image/jpeg"];

	    var fileType = file_data['type'];
	  
	    var errorImageUpload = new Array();

	    allowFileTypes = allowFileTypes.indexOf(fileType);

	    if(allowFileTypes < 0)
	    {
	    	errorImageUpload[0]  = "invalid file type";
	    }
	  
	    if(allowFileTypes >= 0)
	    {	
	    	console.log('ASD')
	    	$.ajax(
	    	{
                url: "<?php echo site_url('admin/Products/test');?>",
                dataType: 'script',
                cache: false,
                contentType: false,
                processData: false,
                data: form_data,
                type: 'post',
                success: function(data)
                {	
                	data =  jQuery.parseJSON(data);
                	
                	if(data!='')
                	{	
                		console.log(data);
                		
	                    $( "#images").append('<img src="' + data + '" width="150" alt="." /><button name="btn-submit" value="' + data + '" class="btn btn-danger" type="button">Delete</button><br /><br />');
                		$.ajax(
					    {
					        type: "POST",
					        url: "<?php echo site_url('admin/Products/test');?>",
					        data:{
					          	'productId': '<?php echo $productId;?>',
					        	'image' :data,	
					        },
					    });
                	}
                	$("#image" ).val('');
                	$("loader").hide();
                	$("image" ).show();
                }
     		});
	    }
	});
});
function deleteProductImage(productImage)
{	

	console.log(productImage);
}

</script>
