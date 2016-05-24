<?php

	$first_name = ''; 
	$last_name = '';
	$email ='';

	if(isset($postedData) && !empty($postedData))
	{
		$first_name = $postedData['first_name'];
		$last_name = $postedData['last_name'];
		$email = $postedData['email'];
	}
?>


<?php
	if($error!='')
	{
		?>
			<p>
				<div class="alert alert-danger" role="alert" style="margin-bottom: 0px;">
					<button class="close" data-dismiss="alert"></button>
					<?php echo $error; ?>
				</div>
			</p>
		<?php
	}
?>

<!-- START Login Form -->
<form id="form-register" class="p-t-15" role="form" action="<?php echo site_url('auth/register'); ?>"  method="post">
	
	<div class="form-group ">
		<label>First Name</label>
		<div class="controls">
			<input type="text" class="form-control" name="first_name" value="<?php echo $first_name; ?>" required>
		</div>
	</div>

	<div class="form-group ">
		<label>Last Name</label>
		<div class="controls">
			<input type="text" class="form-control" name="last_name" value="<?php echo $last_name; ?>" required="required">
		</div>
	</div>

	<div class="form-group ">
		<label>Email Address</label>
		<div class="controls">
			<input type="email" name="email" class="form-control" autocomplete="off" value="<?php echo $email; ?>" required>
		</div>
	</div>					
	
	<div class="form-group ">
		<label>Password</label>
		<div class="controls">
			<input type="password" class="form-control" name="password" autocomplete="off" value="" required>
		</div>
	</div>					
	
	<button class="btn btn-primary btn-cons m-t-10" name="btn-submit" value="submit" type="submit">Register</button>
</form>
<!--END Login Form-->

<br /><br />
<div style="font-weight: bold;">
	Already a member? <a href="<?php echo site_url();?>" class="">Login</a>
</div>
<br />

<a href="https://itunes.apple.com/bb/app/icannpay/id1101212235" target="_blank">
	<img src="<?php echo asset_url('img/apple-store.png');?>" height="30" alt="apple">
</a>
<a href="https://play.google.com/store/apps/details?id=com.dd.icannpay" target="_blank">
	<img src="<?php echo asset_url('img/google-play-store.png');?>" height="30" alt="apple">
</a>