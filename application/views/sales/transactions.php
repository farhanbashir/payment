<div class="content ">
  <!-- START CONTAINER FLUID -->
  <div class="container-fluid container-fixed-lg bg-white">
    <!-- START PANEL -->
    <div class="panel panel-transparent">
      <div class="panel-heading">
        <div class="panel-title"><h1>Transactions</h1></div>		
		
		<div class="row" style="display: none;">		
			<div class="col-md-4">
				<div id="datepicker-component" class="input-group date col-sm-8">
					<input type="text" class="form-control"><span class="input-group-addon"><i class="fa fa-calendar"></i></span>
				</div>
			</div>
			<div class="btn-group pull-right m-b-10">
				<button type="button" class="btn btn-primary">Export</button>
			</div>
		</div>
       
        <div class="clearfix"></div>
      </div>
      <div class="panel-body">
        <div class="table-responsive">
          <div id="basicTable_wrapper" class="dataTables_wrapper form-inline no-footer">
		  <table class="table table-hover dataTable no-footer" id="basicTable" role="grid">
            <thead>
				<tr role="row">
					<th >Order ID</th>
					
					<th>Amount Charged</th>
					
					<th>Payment Method</th>
					
					<th>Custom Info</th>
					
					<th>Date</th>
					
					<th>Actions</th>
				</tr>
            </thead>
            <tbody>
			
			<?php
			
				if(isset($orders))
				{
					if(is_array($orders) && count($orders) > 0)
					{
						foreach($orders as $orderInfo)
						{
							$order_id = $orderInfo['order_id'];
							$receipt  = $orderInfo['receipt'];
							
							?>
								<tr role="row" class="odd">

									<td class="v-align-middle sorting_1">
										<p><?php echo $order_id; ?></p>
									</td>
									<td class="v-align-middle">
										<p>$ <?php echo $orderInfo['total_amount']; ?></p>
									</td>
									<td class="v-align-middle">
										<?php
											$amount_cash = $orderInfo['amount_cash'];
											
											if($amount_cash > 0)
											{
												?><p><strong>Cash:</strong> $<?php echo $amount_cash; ?></p><?php
											}
											
											$amount_cc = $orderInfo['amount_cc'];
											
											if($amount_cc > 0)
											{
												?>
													<p>
														<strong>Credit Card:</strong> $<?php echo $amount_cc; ?> 
														<br /> 
														<span class="small" style="font-size: 10px;"><?php echo $orderInfo['cc_number']; ?></span>
													</p>
												<?php
											}
										?>
									</td>
									<td class="v-align-middle">
										<p>
											<strong>Email:</strong> <?php echo $orderInfo['customer_email']; ?><br />
											<strong>Phone:</strong> <?php echo $orderInfo['customer_phone']; ?><br />
											<strong>State:</strong> <?php echo $orderInfo['customer_state']; ?><br />
											<strong>City:</strong> <?php echo $orderInfo['customer_city']; ?><br />
											<strong>Address:</strong> <?php echo trim($orderInfo['customer_address1'].' '.$orderInfo['customer_address2']); ?><br />
											<strong>Zipcode:</strong> <?php echo trim($orderInfo['customer_zipcode']); ?><br />
										</p>
									</td>
									<td class="v-align-middle">
										<p><?php echo $orderInfo['created']; ?></p>
									</td>
									<!--<td class="v-align-middle">
										<span class="label label-info">SUCCESS</span>
									</td>-->
									<td class="v-align-middle">
										<p>
											<a href="#<?php echo $order_id; ?>" class="btn btn-primary" onclick="Javascript: return openPopupForOrderDetails('<?php echo $order_id; ?>');">View Details</a>
											
											<br /><br />
											<?php
												if($receipt)
												{
													?><a target="_blank" href="<?php echo $receipt; ?>" class="btn btn-info">View Receipt</a><?php
												}
												else
												{
													?><a target="_blank" href="<?php echo site_url('admin/sales/generate_receipt/'.$order_id) ?>" class="btn btn-info">Generate Receipt</a><?php
												}
											?>
											
										</p>
									</td>
								</tr>
								
							<?php
						}						
					}
				}
			?>
			</tr></tbody>
          </table></div>
        </div>
      </div>
    </div>
    <!-- END PANEL -->
  </div>
  <!-- END CONTAINER FLUID -->
</div>

<div id="modal-wrapper"></div>