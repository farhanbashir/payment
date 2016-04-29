<?php
/*$Arry_products = array();
$product_id='';
$count = 0;
foreach ($products as $row) 
{ 
  
  if($product_id!=$row['product_id'])
  {
    $array = array(

      'product_id' => $row['product_id'],
      'product_name'=> $row['product_name'],
      'category_id' => $row['category_id'],
      'price' => $row['price'],
      'category_name'=>$row['category_name'],
      );
    $Arry_products[] = $array;
    $count = $count+1;
  }
  else
  {
    $Arry_products[$count-1]['category_name'] = $Arry_products[$count-1]['category_name'].", ".$row['category_name'];
  }
  
  $product_id=$row['product_id'];
  
}*/

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
        <?php if($this->session->flashdata('Message')!='')
        {?>   
        <div class="alert alert-success">
          <strong>Success!</strong>&nbsp;&nbsp;<?php echo $this->session->flashdata('Message');?>
        </div>
        <?php 
      }?>
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

          <thead>
            <tr>
              <th>ID</th>
              <th>Product</th>
              <th>Categories</th>
              <th>Price</th>
              <th>Action</th>
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
  $('#product-listing').DataTable( 
  {
    "processing":true,
    "serverSide": true,
    "ajax": "<?php echo site_url('admin/products/ajaxProductsListing');?>",
    "bLengthChange": false,
    "oLanguage": 
    {
      "sEmptyTable"   : "No Product Found",
      "sZeroRecords"  : "No Product Found"
    },

    "order": [[ 0, "desc" ]],
    "aoColumns": [

    { "sType": "html", "sName": "product_id" },
    { "sType": "html", "sName": "name" },
    { "sType": "html", "bSortable": false, "bSearchable": false },
    { "sType": "html", "sName": "price" },
    { "sType": "html", "bSortable": false, "bSearchable": false },
    ]
  } );
} );

</script>