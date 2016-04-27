<!-- <section class="content-header">
    <h1>
        Mechunds
    </h1>

</section> -->
<script src="//code.jquery.com/jquery-1.12.0.min.js"></script>
<!--<link rel="stylesheet" href="https://cdn.datatables.net/1.10.11/css/jquery.dataTables.min.css">-->
<div class="content ">
  <!-- START CONTAINER FLUID -->
  <div class="container-fluid container-fixed-lg bg-white">

    <div class="panel panel-transparent">
        <div class="panel-heading">
            <div class="panel-title"><h1>Merchants</h1></div>
            <div class="clearfix"></div>
        </div>
        <div class="panel-body">
            <div id="tableWithSearch_wrapper" class="dataTables_wrapper form-inline no-footer">
                <div class="table-responsive_UJ">
                    <div id="custom-datatable_wrapper" class="dataTables_wrapper form-inline no-footer">
                      <div class="table-responsive">
                        <table id="tableWithSearch_UJ" class="table table-hover demo-table-search_UJ dataTable no-footer" role="grid" aria-describedby="custom-datatable_info" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <th>First name</th>
                                    <th>Last name</th>
                                    <th>Email</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- END CONTAINER FLUID -->
</div>

<!-- Main content -->

<script src="https://cdn.datatables.net/1.10.11/js/jquery.dataTables.min.js"></script>
<script>
$(document).ready(function() 
{
    $('#tableWithSearch_UJ').DataTable( 
    {
        "processing": true,
        "serverSide": true,
        "ajax": "<?php echo site_url('admin/users/AjaxDataTable');?>"
    } );
} );
</script>