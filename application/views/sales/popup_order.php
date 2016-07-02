<div class="modal fade slide-right disable-scroll_UJ" id="modal" tabindex="-1" role="dialog" aria-hidden="false">
	<div class="modal-dialog ">
		<div class="modal-content-wrapper">
			<div class="modal-content">
				<div class="modal-header clearfix text-left">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true" onclick="return closeModal();"><i class="pg-close fs-14"></i>
					</button>
					<h2><span class="semi-bold">Order #<?php echo $order_id; ?></span></h2>
					<?php
					if($orderInfo['receipt'])
					{
		              echo '<a target="_blank" href="'.$orderInfo['receipt'].'" class="btn btn-info">View Receipt</a>';
		            }
		            else
		            {
		              echo '<a target="_blank" href="'.site_url('admin/sales/generate_receipt/'.$order_id).'" class="btn btn-info">Generate Receipt</a>';
		            }
		   		 ?>	
				</div>
				<div class="modal-body">
					
					<div class="row">
						<div class="col-sm-12">
							<div><h4>Products</h4></div>
							<?php
								if(is_array($products) && count($products) > 0)
								{
									?>
										<table  class="table table-hover table-condensed">
											
											<thead>
												<tr>
													<th>Name</th>
													<th>Quantity</th>
													<th>Unit Price</th>
													<th>Sub Total</th>
												</tr>
											</thead>
											<tbody>
										
									<?php
									foreach($products as $_productInfo)
									{
										?>
											<tr>
												<td><?php echo $_productInfo['name']; ?></td>
												<td><?php echo $_productInfo['quantity']; ?></td>
												<td><?php echo $_productInfo['product_price']; ?></td>
												<td><?php echo ($_productInfo['quantity']*$_productInfo['product_price']); ?></td>
											</tr>
										<?php
									}
									
									?>
											</tbody>
										</table>
									<?php
								}
							?>
						</div>
					</div>
					
					<div class="row">
						<div class="col-sm-12">
							<div><h4>Payment Details</h4></div>
							
							<table  class="table table-hover table-condensed">								
								
								<tr>
									<td><strong>App:</strong></td>
									<td><?php echo getDeviceTypeNameById($paymentTransaction['app_type']); ?></td>
								</tr>
								
								<tr>
									<td><strong>Order Date:</strong></td>
									<td><?php echo date(CONST_DATE_TIME_DISPLAY, strtotime($orderInfo['created'])); ?></td>
								</tr>
								
								<tr>
									<td><strong>Total Amount:</strong></td>
									<td><?php echo $orderInfo['total_amount']; ?></td>
								</tr>
								
								<?php
								
									$total_refund = $orderInfo['total_refund'];
									
									if($total_refund > 0)
									{
										?>
											<tr>
												<td><strong>Total Refunded:</strong></td>
												<td><?php echo CONST_CURRENCY_DISPLAY. $total_refund; ?></td>
											</tr>
										<?php
									}
									
									$amount_cash = $paymentTransaction['amount_cash'];											
									if($amount_cash > 0)
									{
										?>
											<tr>
												<td><strong>Paid by Cash:</strong></td>
												<td><?php echo $amount_cash; ?></td>
											</tr>
										<?php
									}
									
									$amount_cc = $paymentTransaction['amount_cc'];	
									$is_cc_swipe = $paymentTransaction['is_cc_swipe'];									
									if($amount_cc > 0)
									{
										?>
											<tr>
												<td><strong>Paid by Credit Card:</strong></td>
												<td><?php echo $amount_cc; ?></td>
											</tr>
											
											<tr>
												<td><strong>Credit Card Swipe:</strong></td>
												<td>
													<?php
														$iconSwipe = '<i class="fs-14 fa fa-remove" style="color: red;"></i>';
														if($is_cc_swipe)
														{
															$iconSwipe = '<i class="fs-14 fa fa-check" style="color: green;"></i>';
														}
														
														echo $iconSwipe;
													?>
												</td>
											</tr>
											
											<tr>
												<td><strong>Name on Card:</strong></td>
												<td><?php echo $paymentTransaction['cc_name']; ?></td>
											</tr>
											
											<tr>
												<td><strong>Credit Card Number:</strong></td>
												<td><?php echo $paymentTransaction['cc_number']; ?></td>
											</tr>
											
											<tr>
												<td><strong>Expiry Year/Month:</strong></td>
												<td><?php echo $paymentTransaction['cc_expiry_year']; ?>/<?php echo $paymentTransaction['cc_expiry_month']; ?></td>
											</tr>
											
											<tr>
												<td><strong>CX Transaction ID:</strong></td>
												<td><?php echo $paymentTransaction['cx_transaction_id']; ?></td>
											</tr>
										<?php										
									}
								?>
								
							</table>
						</div>
					</div>
					
					<div class="row">
						<div class="col-sm-12">
							<div><h4>Customer Details</h4></div>
							
							<table  class="table table-hover table-condensed">
								<tr>
									<td><strong>Email:</strong></td>
									<td><?php echo $orderInfo['customer_email']; ?></td>
								</tr>
								<tr>
									<td><strong>Phone:</strong></td>
									<td><?php echo $orderInfo['customer_phone']; ?></td>
								</tr>
								<tr>
									<td><strong>State:</strong></td>
									<td><?php echo $orderInfo['customer_state']; ?></td>
								</tr>
								<tr>
									<td><strong>City:</strong></td>
									<td><?php echo $orderInfo['customer_city']; ?></td>
								</tr>
								<tr>
									<td><strong>Address:</strong></td>
									<td><?php echo trim($orderInfo['customer_address1'].' '.$orderInfo['customer_address2']); ?></td>
								</tr>
								<tr>
									<td><strong>Zipcode:</strong></td>
									<td><?php echo $orderInfo['customer_zipcode']; ?></td>
								</tr>
							</table>
						</div>
					</div>
					
					<div class="row">
						<div class="col-sm-12">
							<div><h4>Refund Details</h4></div>
							
							<?php
								if(is_array($refundTransaction) && count($refundTransaction) > 0)
								{
									?>
										<table  class="table table-hover table-condensed">
											<thead>
												<tr>
													<th>Date</th>
													<th>by Cash</th>
													<th>to Credit Card</th>
												</tr>
											</thead>
											<tbody>
												<?php
													foreach($refundTransaction as $_refundInfo)
													{
														?>
															<tr>
																<td><?php echo $_refundInfo['created']; ?></td>
																<td><?php echo $_refundInfo['amount_cash']; ?></td>
																<td><?php echo $_refundInfo['amount_cc']; ?></td>
															</tr>
														<?php
													}
												?>
											</tbody>
										</table>
										<?php
								}
								else									
								{
									echo 'None';
								}

							?>
						</div>

					</div>
				
				</div>

			</div>
		</div> <!-- /.modal-content -->
	</div>
</div> <!-- /.modal-dialog -->