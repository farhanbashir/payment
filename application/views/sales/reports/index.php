<?php
$ActiveTab1 = "active";
$ActiveTab2 = "";
$ActiveTab3 = "";
$ActiveTab4 = "";
$ActiveTab5 = "";
$ActiveTab6 = "";

if($this->uri->segment(3) == 'order_summary')
{
	$ActiveTab1 = "";
	$ActiveTab2 = "active";
}
if($this->uri->segment(3) == 'sales_trends')
{
	$ActiveTab1 = "";
	$ActiveTab3 = "active";
}
if($this->uri->segment(3) == 'item_sales')
{
	$ActiveTab1 = "";
	$ActiveTab5 = "active";
}
if($this->uri->segment(3) == 'category_sales')
{
	$ActiveTab1 = "";
	$ActiveTab6 = "active";
}
?>


<script type="text/javascript" src="<?php echo asset_url('js/loader.js');?>"></script>
<div class="content ">
	<!-- START CONTAINER FLUID -->
	<div class="container-fluid container-fixed-lg">

		<!-- START PANEL -->
		<div class="panel">
			<div class="panel-heading">
				<div class="panel-title"><h1>Reports</h1></div>
			</div>
			<div class="panel-body">              
				<div class="row">
					<div class="col-sm-12">
						<div class="panel">
							<!-- Nav tabs -->
							<ul class="nav nav-tabs nav-tabs-linetriangle" data-init-reponsive-tabs="dropdownfx">
								<li class="<?php echo $ActiveTab1;?>">
									<a href="<?php echo site_url('admin/reports');?>"><span>Sales Summary</span></a>
								</li>
								<li class="<?php echo $ActiveTab2;?>">
									<a href="<?php echo site_url('admin/reports/order_summary');?>"><span>Order Summary</span></a>
								</li>
								<!-- <li>
									<a href="#sales-trends"><span>Sales Trends</span></a>
								</li>
								<li>
									<a href="#payment-methods"><span>Payment Methods</span></a>
								</li> -->
								<li class="<?php echo $ActiveTab5;?>">
									<a href="<?php echo site_url('admin/reports/item_sales');?>"><span>Item Sales</span></a>
								</li>
								<li class="<?php echo $ActiveTab6;?>">
									<a href="<?php echo site_url('admin/reports/category_sales');?>"><span>Category Sales</span></a>
								</li>
							</ul>
							<!-- Tab panes -->
								  
						</div>
						<br>
						<?php $this->load->view($load_page); ?>	
						<?php /*$this->load->view('sales/reports/under_construction');*/ ?>	
					</div>
				</div>
			</div>
		</div>
		<!-- END PANEL -->

	</div>
	<!-- END CONTAINER FLUID -->
</div>