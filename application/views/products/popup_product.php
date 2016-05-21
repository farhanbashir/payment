
<div class="modal fade slide-right disable-scroll_UJ" id="modal" tabindex="-1" role="dialog" aria-hidden="false">
	<div class="modal-dialog ">
		<div class="modal-content-wrapper">
			<div class="modal-content">
				<div class="modal-header clearfix text-left">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="pg-close fs-14"></i>
					</button>
					<h2><span class="semi-bold">Product ID #<?php echo $productId; ?></span></h2>
					<br /><br />
					<a href="<?php echo site_url('admin/products/save/'.$productId);?>">
						<button class="btn btn-primary btn-cons">Edit</button>
					</a>
					<a onclick="return confirm('Are you sure want to delete?','<?php echo site_url('admin/products/delete_product/'.$productId);?>')" href="<?php echo site_url('admin/products/delete_product/'.$productId);?>">
						<button class="btn btn-danger btn-cons">Remove</button>
					</a>
				</div>
				<div class="modal-body">
					<div class="row">
						<div class="col-sm-12">
							<div><h4>Product Details</h4></div>
							<?php
							if(is_array($productInfo) && count($productInfo) > 0)
							{	
								?>
								<table class="table table-hover table-condensed">
									
									<tr>
										<td><strong>Name:</strong></td>
										<td><?php echo $productInfo['name']; ?></td>
									</tr>
									<tr>	
										<td><strong>Description:</strong></td>
										<td><?php echo $productInfo['description']; ?></td>
									</tr>
									<tr>	
										<td><strong>Categories</strong></td>
										<?php if (is_array($productCategories) && count($productCategories) > 0)
										{
											?>
											<td>
												<?php foreach ($productCategories as $row)
												{
													echo $row['name'].'<br /><br />';	
												}?>
											</td>
											<?php
										}?>
									</tr>
									<tr>
										<td><strong>Price:</strong></td>
										<td><?php echo $productInfo['price']; ?></td><br /><br />
									</tr>
									<tr>
										<td colspan="2">
												<strong>Product Images:</strong>
												
												<br /><br />
												<?php
													if(is_array($productImages) && count($productImages) > 0)
													for ($i=0; $i < count($productImages) ; $i++) 
													{ 
														echo   '<img src="'.$productImages[$i].'"  height="100" alt="." /><br /><br />'; 
													}
												?>
										</td>
									</tr>									
								</table>
								<?php
							}?>
						</div>
					</div>
				</div>
			</div>
		</div> <!-- /.modal-content -->
	</div>
</div> <!-- /.modal-dialog -->