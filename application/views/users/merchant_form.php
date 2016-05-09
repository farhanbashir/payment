<?php
$first_name ="";
$last_name ="";
$email ="";
$password ="";

if(is_array($postedData) && count($postedData) > 0)
{
  $first_name = $postedData['first_name'];
  $last_name = $postedData['last_name'];
  $email = $postedData['email'];
  $password = $postedData['password'];
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
      Add New Merchant
      </h1>
	  <div class="col-xs-6">
		  <form role="form" method="post" action="">
			<div class="form-group">
			  <label>First Name</label>
			  <input type="text" name="first_name" class="form-control" value="<?php echo $first_name;?>">
			</div>
			<div class="form-group">
			  <label>Last Name</label>
			  <input type="text" name="last_name" class="form-control" value="<?php echo $last_name;?>">
			</div>
			<div class="form-group">
			  <label>Email</label>
			  <input type="text" name="email" class="form-control" value="<?php echo $email;?>">
			</div>
			<div class="form-group">
			  <label>Password</label>
			  <input type="text" name="password" class="form-control" value="<?php echo $password;?>">
			</div>

				<br /><br />
			
			<button value ="submit" name="btn-submit" class="btn btn-primary" type="submit">Submit</button>
		  </form>
	  </div>
    </div>
  </div>
</div>