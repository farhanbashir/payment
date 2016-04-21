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


<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<div class="content ">
	<!-- START CONTAINER FLUID -->
	<div class="container-fluid container-fixed-lg">

		<!-- START PANEL -->
		<div class="panel">
			<div class="panel-heading">
				<div class="panel-title"><h1>Reporting</h1></div>
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
								<li>
									<a href="#sales-trends"><span>Sales Trends</span></a>
								</li>
								<li>
									<a href="#payment-methods"><span>Payment Methods</span></a>
								</li>
								<li class="<?php echo $ActiveTab5;?>">
									<a href="<?php echo site_url('admin/reports/item_sales');?>"><span>Item Sales</span></a>
								</li>
								<li class="<?php echo $ActiveTab6;?>">
									<a href="<?php echo site_url('admin/reports/category_sales');?>"><span>Category Sales</span></a>
								</li>
							</ul>
							<!-- Tab panes -->
								  
						</div>
						<div class="form-group">
							<form role="form" method="post" action="<?php echo $form_url;?>">
								<div class="input-daterange input-group" id="datepicker-range">
									<input name="date1" type="text" class="input-sm form-control" name="start">
									<span class="input-group-addon">to</span>
									<input name="date2" type="text" class="input-sm form-control" name="end">
								
								</div>
								<div class="form-group"style="width: 200px;margin-top: -34px;margin-left: 410px;">
						          <select name="select" class="full-width select2-offscreen" data-init-plugin="select2" tabindex="-1" title="">
						            <option value="0">Please Select Type</option>
						            <option value="Daily">Daily</option>
						            <option value="Weekly">Weekly</option>
						            <option value="Monthly">Monthly</option>
						          </select>
	       						</div>
	       						<button style="margin-top: -76px;margin-left: 622px;" class="btn btn-primary" type="submit">Submit</button>
							</form>
						</div>
						<br>
						<?php $this->load->view($load_page); ?>	
					</div>
				</div>
			</div>
		</div>
		<!-- END PANEL -->

	</div>
	<!-- END CONTAINER FLUID -->
</div>