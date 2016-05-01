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
            <div class="panel-title"><h1>Merchants Bank Status</h1></div>
            <div class="clearfix"></div>
        </div>
        <div class="panel-body">
            <div id="custom-datatable_wrapper" class="dataTables_wrapper form-inline no-footer">
                <table id="bankStatus-listing" class="table table-hover demo-table-search dataTable no-footer" role="grid" aria-describedby="custom-datatable_info" cellspacing="0" width="100%">
                 <div class="cs-wrapper">
                    <select name="sort-by" id="sort-by" class="cs-select cs-skin-slide">
                        <option value="">All</option>
                        <option value="1">Verified</option>
                        <option value="2">Not Verified</option>
                        <option value="no detail">No Detail</option>
                    </select>
                </div>
                <thead>
                    <tr>
                        <th style="width:10%">ID</th>
                        <th style="width:15%">Name</th>
                        <th style="width:25%">Email</th>
                        <th style="width:25%">Bank Info</th>
                        <th style="width:15%">Last Check</th>
                        <th style="width:10%">Status</th>
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

    $('#bankStatus-listing').DataTable( 
    {
        "processing":true,
        "serverSide": true,
        
        "ajax": {
            "url": "<?php echo site_url('admin/users/ajaxMerchantBankStatus');?>",
            "data": function ( d ) {
                d.where_status = $('#sort-by').val();
            }
        },
        "bLengthChange": false,
        "oLanguage": 
        {
            "sEmptyTable"   : "No Merchant Bank Status Found",
            "sZeroRecords"  : "No Merchant Bank Status Found"
        },

        "order": [[ 0, "desc" ]],
        "aoColumns": [

        { "sType": "html", "sName": "u.user_id" },
        { "sType": "html", "sName": "name" },
        { "sType": "html", "sName": "u.email" },
        { "sType": "html", "bSortable": false, "bSearchable": false },
        { "sType": "html", "bSortable": false, "bSearchable": false },
        { "sType": "html", "bSortable": false, "bSearchable": false },
        ]
    } );


    $('#sort-by').change(function()
    {   
        var loadTable = $('#bankStatus-listing').DataTable();
       
        loadTable.draw();  
    });

} ); 
</script>