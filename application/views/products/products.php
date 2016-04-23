<?php
$Arry_products = array();
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
  
}

?>
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
		      <a href="<?php echo site_url('admin/products/create_product');?>" class="btn btn-primary btn-cons">Add New Product</a>
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
            <div id="tableWithSearch_wrapper" class="dataTables_wrapper form-inline no-footer">
                <div class="table-responsive_UJ">
                    <table class="table table-hover demo-table-search_UJ dataTable no-footer" id="tableWithSearch_UJ123" role="grid" aria-describedby="tableWithSearch_info">
            <thead>
              <tr role="row">
                <th width="20%">Product</th>
                <th width="20%">Categories</th>
                <th width="10%">Price</th>
                <th width="50%">Actions</th>
              </tr>
            </thead>
            <tbody>
              <?php 
              foreach ($Arry_products as $product) 
              {?>
                <tr role="row" class="odd">
                  <td class="v-align-middle sorting_1">
                    <p><?php echo $product['product_name'];?></p>
                  </td>
                  <td class="v-align-middle">
                    <p><?php echo $product['category_name'];?></p>
                  </td>
                  <td class="v-align-middle">
                    <p>$<?php echo $product['price'];?></p>
                  </td>
                  <td class="v-align-middle">
                    <p>
                      <a href="<?php echo site_url('admin/products/edit_product/'.$product['product_id']);?>">
                        <button class="btn btn-primary btn-cons">Edit</button>
                      </a>
                      <a onclick="return confirm('Are you sure want to delete','<?php echo site_url('admin/products/delete_product/'.$product['product_id']);?>')"href="<?php echo site_url('admin/products/delete_product/'.$product['product_id']);?>">
                        <button class="btn btn-danger btn-cons">Delete</button>
                      </a>
                    </p>
                  </td>
                </tr>
                <?php
              }?>
            </tbody>
          </table></div>
        </div>
      </div>
    </div>
    <!-- END PANEL -->
  </div>

  <!-- END CONTAINER FLUID -->
</div>
