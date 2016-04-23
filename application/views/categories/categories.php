<?php 
$Arr1 = array();
$parent_categories = array();
//var_dump($categories);die;
foreach ($categories as $row)
{
  if($row['parent_id']==0)
  { 

    $Arr1['name'] = $row['name'];
    $Arr1['category_id'] = $row['category_id'];
    $Arr1['total_products'] = $row['total_products'];
    $parent_categories[] = $Arr1;
  }

}
for ($i=0; $i <count($parent_categories)-1 ; $i++) 
{ 
  $parent_categories[$i]['child_categories'] = array();
  foreach ($categories as $row) 
  { 
    if($parent_categories[$i]['category_id']==$row['parent_id'])
    { 
      $parent_categories[$i]['child_categories'][$i][]=$row;
    }

  }
}
for ($i=0; $i <count($parent_categories)-1 ; $i++) 
{  
  if(is_array($parent_categories[$i]['child_categories']))
  { 

    for ($j=0; $j <count($parent_categories[$i]['child_categories']) ; $j++) 
    { 
      $parent_categories[$i]['child_categories'][$i][$i]['child_categories'] = array();
      foreach ($categories as $row) 
      { 
        if($parent_categories[$i]['child_categories'][$i][$i]['category_id']==$row['parent_id'])
        { 
          $parent_categories[$i]['child_categories'][$i][$j]['child_categories'][]=$row;
        }
      }
    }
  }
}


/*echo "<pre>";
print_r($parent_categories);die;*/
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
          <a class="btn btn-primary btn-cons" href="<?php echo site_url('admin/categories/create_category');?>">
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
              for ($i=0; $i <count($parent_categories) ; $i++) 
              {
                  ?>
                  <tr role="row" class="odd">
                    <td class="v-align-middle">
                      <p><?php echo $parent_categories[$i]['name'];?></p>
                    </td>
                    <td class="v-align-middle">
                      <p><?php echo $parent_categories[$i]['total_products'];?></p>
                    </td>
                    <td class="v-align-middle">
                      <p>
                        <a href="<?php echo site_url('admin/categories/edit_category/'.$parent_categories[$i]['category_id']);?>">
                          <button class="btn btn-primary btn-cons">Edit</button>
                        </a>
                        <a onclick="return confirm('Are you sure want to delete','<?php echo site_url('admin/categories/delete_category/'.$parent_categories[$i]['category_id']);?>')"href="<?php echo site_url('admin/categories/delete_category/'.$parent_categories[$i]['category_id']);?>">
                          <button class="btn btn-danger btn-cons">Delete</button>
                        </a>
                      </p>
                    </td>
                  </tr>
                  <?php
                  if(isset($parent_categories[$i]['child_categories'])==true)
                  {
                    $child_categories1 = $parent_categories[$i]['child_categories'];
                    for ($j=0; $j <count($child_categories1); $j++)
                    { 
                      ?>

                      <tr role="row" class="odd">
                        <td class="v-align-middle">
                          <p style='padding-left: 20px;'><?php echo "*".$child_categories1[$i][$j]['name'];?></p>
                        </td>
                        <td class="v-align-middle">
                          <p><?php echo $child_categories1[$i][$j]['total_products'];?></p>
                        </td>
                        <td class="v-align-middle">
                          <p>
                            <a href="<?php echo site_url('admin/categories/edit_category/'.$child_categories1[$i][$j]['category_id']);?>">
                              <button class="btn btn-primary btn-cons">Edit</button>
                            </a>
                            <a onclick="return confirm('Are you sure want to delete','<?php echo site_url('admin/categories/delete_category/'.$child_categories1[$i][$j]['category_id']);?>')"href="<?php echo site_url('admin/categories/delete_category/'.$child_categories1[$i][$j]['category_id']);?>">
                              <button class="btn btn-danger btn-cons">Delete</button>
                            </a>
                          </p>
                        </td>
                      </tr>
                     <?php
                     if (isset($child_categories1[$i][$j]['child_categories'])==true)
                     {  
                        $child_categories2 = $child_categories1[$i][$j]['child_categories'];
                        for ($k=0; $k < count($child_categories2); $k++) 
                        { ?>
                          
                          <tr role="row" class="odd">
                            <td class="v-align-middle">
                              <p style='padding-left: 40px;'><?php echo "**".$child_categories2[$k]['name'];?></p>
                            </td>
                            <td class="v-align-middle">
                              <p><?php echo $child_categories2[$k]['total_products'];?></p>
                            </td>
                            <td class="v-align-middle">
                              <p>
                                <a href="<?php echo site_url('admin/categories/edit_category/'.$child_categories2[$k]['category_id']);?>">
                                  <button class="btn btn-primary btn-cons">Edit</button>
                                </a>
                                <a onclick="return confirm('Are you sure want to delete','<?php echo site_url('admin/categories/delete_category/'.$child_categories2[$k]['category_id']);?>')"href="<?php echo site_url('admin/categories/delete_category/'.$child_categories2[$k]['category_id']);?>">
                                  <button class="btn btn-danger btn-cons">Delete</button>
                                </a>
                              </p>
                            </td>
                          </tr>

                          <?php
                        }
                      }
                    }
                  ?>
                   <?php
                  }  
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