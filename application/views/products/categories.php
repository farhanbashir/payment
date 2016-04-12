<div class="content ">
  <!-- START CONTAINER FLUID -->
  <div class="container-fluid container-fixed-lg bg-white">
    <!-- START PANEL -->
    <div class="panel panel-transparent">
      <div class="panel-heading">
        <div class="panel-title"><h1>Categories</h1>
        </div>
        <div class="btn-group pull-right m-b-10">
          <a href="<?php echo site_url('admin/products/add_category');?>">
            <button class="btn btn-primary btn-cons">New Category</button>
          </a>
        </div>
        <div class="clearfix"></div>
      </div>
      <div class="panel-body">
        <div id="tableWithSearch_wrapper" class="dataTables_wrapper form-inline no-footer">
          <div class="table-responsive">
            <table class="table table-hover demo-table-search dataTable no-footer" id="tableWithSearch" role="grid" aria-describedby="tableWithSearch_info">
              <thead>
                <tr role="row">
                  <th style="width:33.33%;" class="sorting" tabindex="0" aria-controls="basicTable" rowspan="1" colspan="1" aria-label="Status: activate to sort column ascending">Categories</th>
                  <th style="width:33.33%;" class="sorting" tabindex="0" aria-controls="basicTable" rowspan="1" colspan="1" aria-label="Last Update: activate to sort column ascending">No. of products</th>
                  <th class="sorting" tabindex="0" aria-controls="basicTable" rowspan="1" colspan="1" aria-label="Last Update: activate to sort column ascending">Actions</th>
                </tr>
              </thead>
              <tbody>
                <tr role="row" class="odd">
                  <td class="v-align-middle">
                    <p>Category 1</p>
                  </td>
                  <td class="v-align-middle">
                    <p>43</p>
                  </td>
                  <td class="v-align-middle">
                    <p>
                      <a href="<?php echo site_url('admin/products/add_category');?>">
                        <button class="btn btn-primary btn-cons">Edit</button>
                      </a>
                      <button class="btn btn-danger btn-cons">Delete</button>
                    </p>
                  </td>
                </tr>
                <tr role="row" class="odd">
                  <td class="v-align-middle">
                    <p>Category 2</p>
                  </td>
                  <td class="v-align-middle">
                    <p>13</p>
                  </td>
                  <td class="v-align-middle">
                    <p>
                      <a href="<?php echo site_url('admin/products/add_category');?>">
                        <button class="btn btn-primary btn-cons">Edit</button>
                      </a>
                      <button class="btn btn-danger btn-cons">Delete</button>
                    </p>
                  </td>
                </tr>
                <tr role="row" class="odd">
                  <td class="v-align-middle">
                    <p>Category 3</p>
                  </td>
                  <td class="v-align-middle">
                    <p>25</p>
                  </td>
                  <td class="v-align-middle">
                    <p>
                      <a href="<?php echo site_url('admin/products/add_category');?>">
                        <button class="btn btn-primary btn-cons">Edit</button>
                      </a>
                      <button class="btn btn-danger btn-cons">Delete</button>
                    </p>
                  </td>
                </tr>
                <tr role="row" class="odd">
                  <td class="v-align-middle">
                    <p>Category 12</p>
                  </td>
                  <td class="v-align-middle">
                    <p>15</p>
                  </td>
                  <td class="v-align-middle">
                  <p>
                    <a href="<?php echo site_url('admin/products/add_category');?>">
                      <button class="btn btn-primary btn-cons">Edit</button>
                    </a>
                    <button class="btn btn-danger btn-cons">Delete</button>
                  </p>
                </td>
                </tr>
                <tr role="row" class="odd">
                  <td class="v-align-middle">
                    <p>Category 13</p>
                  </td>
                  <td class="v-align-middle">
                    <p>235</p>
                  </td>
                  <td class="v-align-middle">
                  <p>
                    <a href="<?php echo site_url('admin/products/add_category');?>">
                      <button class="btn btn-primary btn-cons">Edit</button>
                    </a>
                    <button class="btn btn-danger btn-cons">Delete</button>
                  </p>
                </td>
                </tr>
                <tr role="row" class="odd">
                  <td class="v-align-middle">
                    <p>Category 12</p>
                  </td>
                  <td class="v-align-middle">
                    <p>52</p>
                  </td>
                  <td class="v-align-middle">
                  <p>
                    <a href="<?php echo site_url('admin/products/add_category');?>">
                      <button class="btn btn-primary btn-cons">Edit</button>
                    </a>
                    <button class="btn btn-danger btn-cons">Delete</button>
                  </p>
                </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
    <!-- END PANEL -->
  </div>

  <!-- END CONTAINER FLUID -->
</div>
