<script src="<?php echo asset_url('plugins/jquery/jquery-1.11.1.min.js');?>" type="text/javascript"></script>
<div class="content ">
  <!-- START CONTAINER FLUID -->
  <div class="container-fluid container-fixed-lg bg-white">
    <!-- START PANEL -->
    <div class="panel panel-transparent">
      <div class="panel-heading">
        <div class="panel-title"><h1>Web-Services Logs</h1>
        </div>
        <div class="clearfix"></div>
      </div>
      <div class="panel-body">
        <div id="" class="dataTables_wrapper form-inline no-footer">
          <table class="table table-hover demo-table-search_UJ dataTable no-footer" id="logs-listing" role="grid" aria-describedby="tableWithSearch_info">
            <thead>
              <tr>
                <th width="10%">ID</th>
                <th width="10%">UserID</th>
				<th width="10%">Service</th>
                <th width="35%">POST Params</th>
                <th width="25%">Response</th>
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
<script src="<?php echo asset_url('plugins/jquery-datatable/media/js/jquery.dataTables.min.js');?>" type="text/javascript"></script>
<script>
$(document).ready(function() 
{
  $('#logs-listing').DataTable( 
  {
    "processing":true,
    "serverSide": true,
    "ajax": {
				"url": "<?php echo site_url('admin/apilogs/ajaxWebServicesLogsListing');?>"
			},
    "bLengthChange": false,
    "oLanguage": 
    {
      "sEmptyTable"   : "No Web-Services Logs Found",
      "sZeroRecords"  : "No Web-Services Logs Found"
    },

    "order": [[ 0, "desc" ]],
    "aoColumns": [

    { "sType": "html", "sName": "id" },
    { "sType": "html", "bSortable": false, "bSearchable": false },
	{ "sType": "html", "bSortable": false, "bSearchable": false },
    { "sType": "html", "bSortable": false, "bSearchable": false },
    { "sType": "html", "bSortable": false, "bSearchable": false },
    ]
  } );

} );


</script>