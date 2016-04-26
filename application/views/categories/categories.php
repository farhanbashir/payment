<?php 



/*echo "<pre>";
print_r($categories);die;*/
?>
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
        <div class="table-responsive">
          <table class="table table-hover demo-table-search_UJ dataTable no-footer" id="" role="grid" aria-describedby="tableWithSearch_info">
            <thead>
              <tr role="row">
                <th width="20%">Categories</th>
                <th width="20%">No. of products</th>
                <th width="60%">Actions</th>
              </tr>
            </thead>
            <tbody>
              <?php
              foreach($categories as $category) 
              {
                  ?>
                  <tr role="row" class="odd">
                    <td class="v-align-middle">
                      <p><?php echo $category['name'];?></p>
                    </td>
                    <td class="v-align-middle">
                      <p><?php echo $category['total_products'];?></p>
                    </td>
                    <td class="v-align-middle">
                      <p>
                        <a href="<?php echo site_url('admin/categories/save/'.$category['category_id']);?>">
                          <button class="btn btn-primary btn-cons">Edit</button>
                        </a>
                        <a onclick="return confirm('Are you sure want to delete','<?php echo site_url('admin/categories/delete_category/'.$category['category_id']);?>')"href="<?php echo site_url('admin/categories/delete_category/'.$category['category_id']);?>">
                          <button class="btn btn-danger btn-cons">Delete</button>
                        </a>
                      </p>
                    </td>
                  </tr>
                  <?php
              }?>
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