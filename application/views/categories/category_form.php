<?php
$category_name = ''; 
$parent_category = '';
$child_category_id ='';

if(isset($edit_data) && is_array($edit_data))
{ 

  $category_name = $edit_data[0]['category'];
  $parent_category = $edit_data[0]['parent_category'];
  $child_category_id = $edit_data[0]['parent_category_id'];
}

?>
<div class="content ">
  <div class="panel panel-default">
    <div class="panel-body">
      <h1>
       <?php echo $form_title;?>
      </h1>
      <form role="form" method="post" action='<?php echo $form_url;?>'>
        <div class="form-group">
          <label>Category Name</label>
          <input type="text" name="category_name" class="form-control" required="" value="<?php echo $category_name;?>">
        </div>
        <div class="form-group">
          <label>Parent Category</label>
          <select name="parent_category" class="full-width select2-offscreen" data-init-plugin="select2" tabindex="-1" title="">
            <option value="0">No Parent Category</option>
            <?php foreach ($categories as $category)
            { 
              if($category['name']==$parent_category)
              {?>
                <option selected value="<?php echo $child_category_id?>"><?php echo $parent_category;?></option>
                <?php
              }
              if($category['name']!=$category_name)
              {?>
                <option value="<?php echo $category['category_id'];?>"><?php echo $category['name'];?></option>
                <?php
              }
            }?>
          </select>
        </div>
        <button class="btn btn-primary" type="submit"><?php echo $button_title;?></button>
      </form>
    </div>
  </div>
</div>