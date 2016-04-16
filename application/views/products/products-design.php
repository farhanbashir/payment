<div class="content ">
  <!-- START CONTAINER FLUID -->
  <div class="container-fluid container-fixed-lg bg-white">
    <!-- START PANEL -->
    <div class="panel panel-transparent">
      <div class="panel-heading">
        <div class="panel-title"><h1>Products</h1>
        </div>
        <div class="btn-group pull-right m-b-10">
          <a href="<?php echo site_url('admin/products/add_product');?>">
            <button class="btn btn-primary btn-cons">Add New</button>
          </a>
        </div>
        <div class="btn-group pull-right m-b-10">
          <a href="#">
            <button class="btn btn-primary btn-cons">Import / Export</button>
          </a>
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
                <div class="table-responsive">
                    <table class="table table-hover demo-table-search dataTable no-footer" id="tableWithSearch" role="grid" aria-describedby="tableWithSearch_info">
            <thead>
              <tr role="row">
                <th style="width:25%;" class="sorting" tabindex="0" aria-controls="basicTable" rowspan="1" colspan="1" aria-label="Activities: activate to sort column ascending">Products</th>
                <th style="width:25%;" class="sorting" tabindex="0" aria-controls="basicTable" rowspan="1" colspan="1" aria-label="Status: activate to sort column ascending">Categories</th>
                <th style="width:25%;" class="sorting" tabindex="0" aria-controls="basicTable" rowspan="1" colspan="1" aria-label="Last Update: activate to sort column ascending">Price</th>
                <th class="sorting" tabindex="0" aria-controls="basicTable" rowspan="1" colspan="1" aria-label="Last Update: activate to sort column ascending">Actions</th>
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