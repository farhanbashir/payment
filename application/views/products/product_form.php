
<?php


$file_name = "";
$product_name ="";
$price = "";
$description = ""; 
$productMedia = array();
$productMediaHidden = false;
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

	if(isset($postedData['productMedia']))
	{
		$productMedia  = $postedData['productMedia'];
	}

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
						<br /><br />
						<?php
							if(is_array($productMedia) && count($productMedia) > 0)
							{	
								
								for ($i=0; $i <count($productMedia) ; $i++)
								{ 	
									if(isset($productMedia[$i]['mediaId']))
									{		

											?>
											<div>
												<img src="<?php echo $productMedia[$i]['mediaPath'];?>" width="150" alt="." />
												<button name="btn-submit" value="<?php echo $productMedia[$i]['mediaId'];?>" id="delete" class="btn btn-danger" type="button">Remove Image</button>
												<br /><br />
											</div>
											<?php
										
									}
									else
									{	
										$productMediaHidden = true;
										?>
										<div>
											<img src="<?php echo $productMedia[$i];?>" width="150" alt="." />
											<button name="btn-submit" value="<?php echo $productMedia[$i];?>" class="btn btn-danger" id="delete" type="button">Remove Image</button>
											<br /><br />
										</div>
										<?php
									}
							    }
							}
						?>
					</div>	
					<div id="imageUploadError">
						
					</div>
					<div class="form-group" id="imageUpload">
						<label>Add Product Images</label>
						<input type="file" id="image" name="image" >
						<img src='<?php echo asset_url('img/loader.gif');?>' height='20'; id="loader">
						<div id="productMedia">
						<?php
							if($productMediaHidden)
							{	
								for ($i=0; $i <count($productMedia) ; $i++) 
								{ 	
									?>
										<input type="hidden" name="productMedia[]" value="<?php echo $productMedia[$i];?>">
									<?php
								}
							}
						?>
						</div>

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
	    	setTimeout(function(){			
				$("#multi").val([<?php echo implode(', ', $ArrEditCategoriesId);?>]).select2();
			}, 1000);
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
	
	$("#loader").hide();
	$("#imageUploadError").hide();


	$("#image").on('change',function()
	{	
		$("#image").hide();
		$("#loader").show();
		
		var file_data = $("#image").prop("files")[0]; 

    	var form_data = new FormData();   

	    form_data.append("file", file_data)

	    var allowFileTypes = ["image/png", "image/jpeg"];

	    var fileType = file_data['type'];
	  
	    var errorImageUpload = new Array();

	    allowFileTypes = allowFileTypes.indexOf(fileType);

	    if(allowFileTypes < 0)
	    {
	    	$("#imageUploadError").empty();
	    	$("#imageUploadError").append("<div class='alert alert-danger'>You Did Not Select The Valid File Type</div>");
	    	$("#imageUploadError").show(1500).fadeOut("slow");
	    	
	    }
	  	
	    if(allowFileTypes >= 0)
	    {	
	    	
	    	$.ajax(
	    	{
                url: "<?php echo site_url('admin/Products/ajaxaUplaodImage');?>",
                dataType: 'script',
                cache: false,
                contentType: false,
                processData: false,
                data: form_data,
                type: 'post',
                success: function(imagePath)
                {	
                	imagePath =  jQuery.parseJSON(imagePath);
                	
                	if(imagePath!='')
                	{	
                		btnValue = imagePath;
                		imgSrc   = imagePath;

                		<?php if($productId <= 0)
                		{	
                			
                			?>
                			
                			$("#productMedia").append('<input type="hidden" name="productMedia[]" value="'+btnValue+'">');
                			
                			<?php
                		
                		}?>

                		div = '<div><img src="' + imgSrc + '" width="150" alt="." /> <button name="btn-submit" value="' + btnValue + '" class="btn btn-danger" type="button" id="delete">Delete</button>';
	                	div		= div +	
	                						'<br /><br />';
	                	div		= div +
	                			'</div>';

	                	$( "#images").append(div);
	                		
	                		<?php 
	                			if($productId > 0)
	                			{	
	                				?>
		                			$.ajax(
								    {	
								        type: "POST",
								        url: "<?php echo site_url('admin/Products/ajaxaUplaodImage');?>",
								        data:{
								          	'productId': '<?php echo $productId;?>',
								        	'image' :imgSrc,	
								        },
								        success: function(mediaId)
		               					{
											$('#images div:last-child button').attr('value', mediaId);
		               					}
								    });

								    <?php
								}
							?>
	                }
                	
                }
     		});

	    }

	    $("#image" ).val('');
    	$("#loader").hide(300);
    	$("#image" ).show();
	});
	
	// delete image
	$(document).on('click','#delete', function()
	{	
		console.log($(this).val())
		var isImageDelete = false; 
		
		value = $(this).val();

		if(isNaN(value)==true)
		{	
			var arrImagesLength = $("input[name^='productMedia']").length;
			var arrImages = $("input[name^='productMedia']");

			for(i=0;i<arrImagesLength;i++)
			{	
				if(arrImages.eq(i).val() == value)
				{	
					isImageDelete = true;

					$("input[name^='productMedia']").eq(i).remove();
					
				}
			}
		}

		if(isNaN(value)==false)
		{
			isImageDelete = true;
		}
		
		removeImage = $(this);
		
		if(isImageDelete)
		{
			$.ajax(
		    {
		        type: "POST",
		        url: "<?php echo site_url('admin/Products/ajaxDeleteImage');?>",
		        data:{
		        	'productId' :'<?php echo $productId;?>',
		        	'deleteImage' :value,	
		        },
		        success: function(data)
		        {
					$(removeImage).parent().remove();
		        }
		    });
		}
	});
});
 

</script>
