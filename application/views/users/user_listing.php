<script src="<?php echo asset_url('plugins/jquery/jquery-1.11.1.min.js');?>" type="text/javascript"></script>
<div class="content ">
  <!-- START CONTAINER FLUID -->
  <div class="container-fluid container-fixed-lg bg-white">
  <!-- START PANEL -->
    <div class="panel panel-transparent">
        <div class="panel-heading">
            <div class="panel-title"><h1>Merchants</h1></div>
			<div class="btn-group pull-right m-b-10">
              <a class="btn btn-primary btn-cons" href="<?php echo site_url('admin/users/save');?>">
                Add New Merchant
              </a>
            </div>
            <div class="clearfix"></div>
        </div>
        <?php if($this->session->flashdata('Message')!='')
          { 
            echo getHTMLForSuccessMessage($this->session->flashdata('Message'));
          }
        ?>
        <div class="panel-body">
            
            <div id="custom-datatable_wrapper" class="dataTables_wrapper form-inline no-footer">
                <table id="merchants-listing" class="table table-hover demo-table-search dataTable no-footer" role="grid" aria-describedby="custom-datatable_info" cellspacing="0" width="100%">
                    <thead>
                        <tr>
                            <th width="5%">ID</th>
                            <th width="15%">First Name</th>
                            <th width="15%">Last Name</th>
                            <th width="20%">Email</th>
                            <th width="15%">Created</th>
                            <th width="10%">Status</th>
                            <th width="20%">Action</th>
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
        "ajax": "<?php echo site_url('admin/users/ajaxMerchantsListing');?>",
        "bLengthChange": false,
        "oLanguage": 
        {
            "sEmptyTable"   : "No Merchant Found",
            "sZeroRecords"  : "No Merchant Found"
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