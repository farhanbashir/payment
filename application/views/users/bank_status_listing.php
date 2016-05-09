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
                    <select name="filter-status" id="filter-status" class="cs-select cs-skin-slide">
                        <option value="">All</option>
                        <option value="<?php echo CONST_BANK_STATUS_VERIFIED; ?>">Verified</option>
                        <option value="<?php echo CONST_BANK_STATUS_NOT_VERIFIED; ?>">Not Verified</option>
                        <option value="<?php echo CONST_TXT_BANK_STATUS_NO_DETAIL; ?>">No Bank Details</option>
                    </select>
                </div>
                <thead>
                    <tr>
                        <th width="15%">Merchant ID</th>
                        <th width="20%">Name</th>
                        <th width="20%">Email</th>
                        <!--<th width="20%">Bank Info</th>-->
                        <th width="15%">Last Checked</th>
                        <th width="30%">Status</th>
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
                d.filter_status = $('#filter-status').val();
            }
        },
        "bLengthChange": false,
        "oLanguage": 
        {
            "sEmptyTable"   : "No Details Found",
            "sZeroRecords"  : "No Details Found"
        },

        "order": [[ 0, "desc" ]],
        "aoColumns": [

        { "sType": "html", "sName": "u.user_id" },
        { "sType": "html", "sName": "name" },
        { "sType": "html", "sName": "u.email" },
        { "sType": "html", "bSortable": false, "bSearchable": false },
        { "sType": "html", "bSortable": false, "bSearchable": false },
        ]
    } );


    $('#filter-status').change(function()
    {   
        var loadTable = $('#bankStatus-listing').DataTable();
       
        loadTable.draw();  
    });

    

} ); 
function checkBankStatus(Merchant) // no ';' here
{   
    $("#"+Merchant.value).empty();
    $( "#"+Merchant.value ).append( "<img src='<?php echo asset_url('img/loader.gif');?>'width=50;height=20;>" );

    $.ajax(
    {
        type: "POST",
        url: "<?php echo site_url('admin/users/check_bank_status');?>",
        data:{
          'userId': Merchant.value,
        },

        success: function(data)
        { 
            $("#"+Merchant.value).empty();  
            $("#"+Merchant.value).append(data);
        }
    });
}
</script>

<img src="">