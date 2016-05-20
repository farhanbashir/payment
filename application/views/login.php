<?php
	if($error)
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
<form id="form-login" class="p-t-15" role="form" action="<?php echo site_url('auth/login'); ?>"  method="post" autocomplete="off">
	<?php if($this->session->flashdata('Message')!='')
	{ 
		echo getHTMLForSuccessMessage($this->session->flashdata('Message'));
	}
	?>
	<div class="form-group ">
		<label>Email Address</label>
		<div class="controls">
			<input type="email" name="username" class="form-control" value="<?php echo $username; ?>" required>
		</div>
	</div>					
	
	<div class="form-group ">
		<label>Password</label>
		<div class="controls">
			<input type="password" class="form-control" name="password" required>
		</div>
	</div>
	
	<br />
	<button class="btn btn-primary btn-cons m-t-10" type="submit">Log In</button>
	
</form>
<!--END Login Form-->

<br /><br />
<div style="font-weight: bold;">
	Not a member? <a href="<?php echo site_url('auth/register');?>" class="">Join Now</a>
</div>

<!-- END Login Right Container-->