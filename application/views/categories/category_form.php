<?php

$category_name = ''; 
$parent_category = '';
$child_category_id ='';

if(isset($postedData) && !empty($postedData))
{ 

  $category_name = $postedData['category_name'];
  $parent_category = $postedData['parent_category'];
}

?>

<div class="content ">
  <div class="panel panel-default">
    <div class="panel-body">
      <?php 
        if($this->session->flashdata('showErrorMessage')!='')
          {
             echo getHTMLForErrorMessage($this->session->flashdata('showErrorMessage'));
          }
      ?>
      <h1>
       <?php echo $formHeading;?>
      </h1>
      <form role="form" method="post" action="">
        <div class="form-group">
          <label>Category Name</label>
          <input type="text" name="category_name" class="form-control" value="<?php echo $category_name;?>">
        </div>
        <div class="form-group">
          <label>Parent Category</label>
          <select name="parent_category" class="full-width select2-offscreen" data-init-plugin="select2" tabindex="-1" title="">
            <option value="0">No Parent Category</option>
            <?php foreach ($categories as $category)
            { 
              if($category['category_id']==$parent_category)
              {?>
                <option selected value="<?php echo $category['category_id']?>"><?php echo $category['name'];?></option>
                <?php
              }
              else
              {?>
                <option value="<?php echo $category['category_id'];?>"><?php echo $category['name'];?></option>
                <?php
              }
            }?>
          </select>
        </div>
		<br /><br />
        <button value ="submit" name="btn-submit" class="btn btn-primary" type="submit">Submit</button>
      </form>
    </div>
  </div>
</div>