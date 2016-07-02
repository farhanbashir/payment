<?php 


?>
<script src="<?php echo asset_url('plugins/jquery/jquery-1.11.1.min.js');?>" type="text/javascript"></script>
<div class="content ">
  <!-- START CONTAINER FLUID -->
  <div class="container-fluid container-fixed-lg bg-white">
    <!-- START PANEL -->
    <div class="panel panel-transparent">
      <div class="panel-heading">
        <div class="panel-title"><h1>Products</h1>
        </div>
        <div class="btn-group pull-right m-b-10">
          <!--<a href="# class="btn btn-primary btn-cons">Import / Export</a>-->
          <a href="<?php echo site_url('admin/products/save');?>" class="btn btn-primary btn-cons">Add New Product</a>
        </div>
        <div class="btn-group pull-right m-b-10">

        </div>
        <?php 
        if($this->session->flashdata('Message')!='')
        { 
          echo getHTMLForSuccessMessage($this->session->flashdata('Message'));
        }
        ?>
        <div class="pull-right" style="display: none;">
          <div class="col-xs-12">
            <input type="text" id="search-table" class="form-control pull-right" placeholder="Search">
          </div>
        </div>
        <div class="clearfix"></div>
      </div>
      <div class="panel-body">
        <div id="" class="dataTables_wrapper form-inline no-footer">
          <table class="table table-hover demo-table-search_UJ dataTable no-footer" id="product-listing" role="grid" aria-describedby="tableWithSearch_info">
            <div class="cs-wrapper">
              <select name="filter-category" id="filter-category" class="cs-select cs-skin-slide">
                <option value="">All</option>
                <?php 
                  foreach ($categories as $row) 
                  {
                    ?>
                      <option value="<?php echo $row['category_id']; ?>"><?php echo $row['name']; ?></option>
                    <?php
                  }

                ?>
              </select>
            </div>
            <thead>
              <tr>
                <th width="5%">ID</th>
                <th width="25%">Product</th>
                <th width="20%">Categories</th>
                <th width="10%">Price</th>
                <th width="10%">Image</th>
                <th width="30%">Action</th>
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
  $('#product-listing').DataTable( 
  {
    "processing":true,
    "serverSide": true,
    "ajax": {
            "url": "<?php echo site_url('admin/products/ajaxProductsListing');?>",
            "data": function ( d ) {
                d.filter_category = $('#filter-category').val();
            }},
    "bLengthChange": false,
    "oLanguage": 
    {
      "sEmptyTable"   : "No Products Found",
      "sZeroRecords"  : "No Products Found"
    },

    "order": [[ 0, "desc" ]],
    "aoColumns": [

    { "sType": "html", "sName": "p.product_id" },
    { "sType": "html", "sName": "p.name" },
    { "sType": "html", "bSortable": false, "bSearchable": false },
    { "sType": "html", "sName": "price" },
    { "sType": "html", "bSortable": false, "bSearchable": false },
    { "sType": "html", "bSortable": false, "bSearchable": false },
    ]
  } );

  $('#product-listing').change(function()
  {   
      var loadTable = $('#product-listing').DataTable();
      
      loadTable.draw();  
  });
  

} );

/*
  $('#product-listing').on('click', 'tr', function () {
        $(this).find('.btn-primary').trigger('click');
    } );
*/

</script>