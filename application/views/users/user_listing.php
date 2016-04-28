<!-- <section class="content-header">
    <h1>
        Mechunds
    </h1>

</section> -->
<script src="<?php echo asset_url('plugins/jquery/jquery-1.11.1.min.js');?>" type="text/javascript"></script>
<div class="content ">
  <!-- START CONTAINER FLUID -->
  <div class="container-fluid container-fixed-lg bg-white">

    <div class="panel panel-transparent">
        <div class="panel-heading">
            <div class="panel-title"><h1>Merchants</h1></div>
            <div class="clearfix"></div>
        </div>
        <div class="panel-body">

            <div id="custom-datatable_wrapper" class="dataTables_wrapper form-inline no-footer">
                <table id="merchants-listing" class="table table-hover demo-table-search dataTable no-footer" role="grid" aria-describedby="custom-datatable_info" cellspacing="0" width="100%">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>First name</th>
                            <th>Last name</th>
                            <th>Email</th>
                            <th>Created</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
</div>
<!-- END CONTAINER FLUID -->
</div>

<!-- Main content -->

<script src="<?php echo asset_url('plugins/jquery-datatable/media/js/jquery.dataTables.min.js');?>" type="text/javascript"></script>
<script>
$(document).ready(function() 
{
    $('#merchants-listing').DataTable( 
    {
        "processing":true,
        "serverSide": true,
        "ajax": "<?php echo site_url('admin/users/merchuntsListing');?>",
        "bLengthChange": false,
        "oLanguage": 
        {
            "sEmptyTable":     "No merchant found"
        },

        "order": [[ 0, "desc" ]],
        "aoColumns": [

            { "sType": "html", "sName": "user_id" },
            { "sType": "html", "sName": "first_name" },
            { "sType": "html", "sName": "last_name" },
            { "sType": "html", "sName": "email" },
            { "sType": "html", "sName": "created" },
            { "sType": "html", "bSortable": false, "bSearchable": false },
            { "sType": "html", "bSortable": false, "bSearchable": false },
        ]
    } );
} );

</script>