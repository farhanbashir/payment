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
		  <a href="<?php echo site_url('admin/products/add_product');?>" class="btn btn-primary btn-cons">Add New Product</a>
        </div>
        <div class="btn-group pull-right m-b-10">
          
        </div>
        <div class="pull-right">
          <div class="col-xs-12">
            <input type="text" id="search-table" class="form-control pull-right" placeholder="Search">
          </div>
        </div>
        <div class="clearfix"></div>
      </div>
       <div class="panel-body">
            <div id="tableWithSearch_wrapper" class="dataTables_wrapper form-inline no-footer">
                <div class="table-responsive_UJ">
                    <table class="table table-hover demo-table-search_UJ dataTable no-footer" id="tableWithSearch" role="grid" aria-describedby="tableWithSearch_info">
            <thead>
              <tr role="row">
                <th width="20%">Product</th>
                <th width="20%">Categories</th>
                <th width="10%">Price</th>
                <th width="50%">Actions</th>
              </tr>
            </thead>
            <tbody>
              <tr role="row" class="odd">
                <td class="v-align-middle sorting_1">
                  <p>Product 1</p>
                </td>
                <td class="v-align-middle">
                  <p>Category 1</p>
                </td>
                <td class="v-align-middle">
                  <p>$43</p>
                </td>
                <td class="v-align-middle">
                  <p>
                    <a href="<?php echo site_url('admin/products/add_product');?>">
                      <button class="btn btn-primary btn-cons">Edit</button>
                    </a>
                    <button class="btn btn-danger btn-cons">Delete</button>
                  </p>
                </td>
              </tr>
              <tr role="row" class="odd">
                <td class="v-align-middle sorting_1">
                  <p>Product 1</p>
                </td>
                <td class="v-align-middle">
                  <p>Category 2</p>
                </td>
                <td class="v-align-middle">
                  <p>$13</p>
                </td>
                <td class="v-align-middle">
                  <p>
                    <a href="<?php echo site_url('admin/products/add_product');?>">
                      <button class="btn btn-primary btn-cons">Edit</button>
                    </a>
                    <button class="btn btn-danger btn-cons">Delete</button>
                  </p>
                </td>
              </tr>
              <tr role="row" class="odd">
                <td class="v-align-middle sorting_1">
                  <p>Product 3</p>
                </td>
                <td class="v-align-middle">
                  <p>Category 1</p>
                </td>
                <td class="v-align-middle">
                  <p>$425</p>
                </td>
                <td class="v-align-middle">
                  <p>
                    <a href="<?php echo site_url('admin/products/add_product');?>">
                      <button class="btn btn-primary btn-cons">Edit</button>
                    </a>
                    <button class="btn btn-danger btn-cons">Delete</button>
                  </p>
                </td>
              </tr>
              <tr role="row" class="odd">
                <td class="v-align-middle sorting_1">
                  <p>Product 33</p>
                </td>
                <td class="v-align-middle">
                  <p>Category 12</p>
                </td>
                <td class="v-align-middle">
                  <p>$415</p>
                </td>
                <td class="v-align-middle">
                  <p>
                    <a href="<?php echo site_url('admin/products/add_product');?>">
                      <button class="btn btn-primary btn-cons">Edit</button>
                    </a>
                    <button class="btn btn-danger btn-cons">Delete</button>
                  </p>
                </td>
              </tr>
              <tr role="row" class="odd">
                <td class="v-align-middle sorting_1">
                  <p>Product 123</p>
                </td>
                <td class="v-align-middle">
                  <p>Category 13</p>
                </td>
                <td class="v-align-middle">
                  <p>$4235</p>
                </td>
                <td class="v-align-middle">
                  <p>
                    <a href="<?php echo site_url('admin/products/add_product');?>">
                      <button class="btn btn-primary btn-cons">Edit</button>
                    </a>
                    <button class="btn btn-danger btn-cons">Delete</button>
                  </p>
                </td>
              </tr>
              <tr role="row" class="odd">
                <td class="v-align-middle sorting_1">
                  <p>Product 123</p>
                </td>
                <td class="v-align-middle">
                  <p>Category 12</p>
                </td>
                <td class="v-align-middle">
                  <p>$452</p>
                </td>
                <td class="v-align-middle">
                  <p>
                    <a href="<?php echo site_url('admin/products/add_product');?>">
                      <button class="btn btn-primary btn-cons">Edit</button>
                    </a>
                    <button class="btn btn-danger btn-cons">Delete</button>
                  </p>
                </td>
              </tr>
            </tbody>
          </table></div>
        </div>
      </div>
    </div>
    <!-- END PANEL -->
  </div>

  <!-- END CONTAINER FLUID -->
</div>
