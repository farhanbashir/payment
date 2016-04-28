<script src="<?php echo asset_url('plugins/jquery/jquery-1.11.1.min.js');?>" type="text/javascript"></script>
<div class="content ">
  <!-- START CONTAINER FLUID -->
  <div class="container-fluid container-fixed-lg bg-white">
    <!-- START PANEL -->
    <div class="panel panel-transparent">
      <div class="panel-heading">
        <div class="panel-title"><h1>Categories</h1>
        </div>
        <div class="btn-group pull-right m-b-10">
          <a class="btn btn-primary btn-cons" href="<?php echo site_url('admin/categories/save');?>">
            Add New Category
          </a>
        </div>
        <div class="clearfix"></div>
      </div>
      <?php if($this->session->flashdata('Message')!='')
      {?>   
      <div class="alert alert-success">
        <strong>Success!</strong>&nbsp;&nbsp;<?php echo $this->session->flashdata('Message');?>
      </div>
      <?php 
    }?>
    <div class="panel-body">
      <div id="tableWithSearch_wrapper" class="dataTables_wrapper form-inline no-footer">
        <table id="category-listing" class="table table-hover demo-table-search dataTable no-footer"  role="grid" aria-describedby="tableWithSearch_info">
          <thead>
            <tr role="row">
              <th>ID</th>
              <th>Categories</th>
              <th>No. of products</th>
              <th>Actions</th>
            </tr>
          </thead>
        </table>
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
  $('#category-listing').DataTable( 
  {
    "processing":true,
    "serverSide": true,
    "ajax": "<?php echo site_url('admin/categories/categoryListing');?>",
    "bLengthChange": false,
    "oLanguage": 
    {
      "sEmptyTable":     "No Category found"
    },
    "order": [[ 0, "desc" ]],
    "aoColumns": [
    { "sType": "html", "sName": "category_id" },
    { "sType": "html", "sName": "name" },
    { "sType": "html", "bSortable": false, "bSearchable": false },
    { "sType": "html", "bSortable": false, "bSearchable": false },
    ]
  } );
} );

</script>