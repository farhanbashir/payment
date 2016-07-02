<script src="<?php echo asset_url('plugins/jquery/jquery-1.11.1.min.js');?>" type="text/javascript"></script>
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
				<div class="">
					<div  class="dataTables_wrapper form-inline no-footer">
						<table id="transaction-listing" class="table table-hover dataTable no-footer"  role="grid">
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
						</table>
					</div>
				</div>
			</div>
		</div>
		<!-- END PANEL -->
	</div>
	<!-- END CONTAINER FLUID -->
</div>

<div id="modal-wrapper"></div>
<script src="<?php echo asset_url('plugins/jquery-datatable/media/js/jquery.dataTables.min.js');?>" type="text/javascript"></script>
<script>
$(document).ready(function() 
{
  $('#transaction-listing').DataTable( 
  {
    "processing":true,
    "serverSide": true,
    "ajax": "<?php echo site_url('admin/sales/ajaxTransactionListing');?>",
    "bLengthChange": false,
    "oLanguage": 
    {
      "sEmptyTable"   : "No Transaction Found",
      "sZeroRecords"  : "No Transaction Found"
    },

    "order": [[ 0, "desc" ]],
    "aoColumns": [

    { "sType": "html", "sName": "o.order_id" },
    { "sType": "html", "sName": "o.total_amount" },
    { "sType": "html", "bSortable": false, "bSearchable": false },
    { "sType": "html", "bSortable": false, "bSearchable": false },
    { "sType": "html", "bSortable": false, "bSearchable": false },
    { "sType": "html", "bSortable": false, "bSearchable": false },
    ]
  } );
} );

/*
  $('#transaction-listing').on('click', 'tr', function () {
        $(this).find('.btn-primary').trigger('click');
    } );
*/
	

</script>