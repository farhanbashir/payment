<?php
$business_name="";
$description="";
$email="";
$phone="";
$logo="";
$address="";
$facebook="";
$twitter="";
$website="";

if(!empty($business_info))
{
	$business_name = $business_info['name'];
	$description = $business_info['description'];
	$email = $business_info['email'];
	$phone = $business_info['phone'];
	$logo = $business_info['logo'];
	$address = $business_info['address'];
	$facebook = $business_info['facebook'];
	$twitter = $business_info['twitter'];
	$website = $business_info['website'];
}

?>

<div class="panel-body">
	<h2>Business Information</h2>
	<div class="col-md-6" style="padding-left: 0px;">
		<?php if($this->session->flashdata('ErrorMessageTab2')!='')
	    {?>   
	        <div class="alert alert-danger">
	          <strong>Alert!</strong>&nbsp;&nbsp;<?php echo $this->session->flashdata('ErrorMessageTab2');?>
	        </div>
	        <?php 
	    }?>
	    <?php if($this->session->flashdata('MessageTab2')!='')
	    {?>   
	        <div class="alert alert-success">
	          <strong>Success!</strong>&nbsp;&nbsp;<?php echo $this->session->flashdata('MessageTab2');?>
	        </div>
	        <?php 
	    }?>
		<form role="form" method ="post"action="<?php echo $business_info_form_url;?>" enctype="multipart/form-data" accept-charset="utf-8">
			<div class="form-group">
				<label>Logo</label>
				<input type="file" name="image" >
				<input type="hidden" name="old-image" value="<?php echo $logo;?>">
				<?php
					if($logo)						
					{
						?>
							<br /><br />
							<img src="<?php echo $logo;?>" width="150" alt="." />
						<?php
					}
				?>				
			</div>
			<div class="form-group">
				<label>Business Name</label>
				<input name="business" value="<?php echo $business_name;?>" type="text" class="form-control" required="">
			</div>
			<div class="form-group">
				<label>Description</label>
				<textarea name="description" type="text" class="form-control no-resize" rows="8"><?php echo $description;?></textarea>
			</div>
			<div class="form-group">
				<label>Email</label>
				<input name="email" value="<?php echo $email;?>"type="email" class="form-control">
			</div>
			<div class="form-group">
				<label>Phone</label>
				<input name="phone" value="<?php echo $phone;?>" type="text" class="form-control" required="">
			</div>
			<div class="form-group">
				<label>Address</label>
				<input name="address" value="<?php echo $address;?>" type="text" class="form-control">
			</div>
			<div class="form-group">
				<label>Facebook</label>
				<input name="facebook" value="<?php echo $facebook;?>" type="text" class="form-control">
			</div>
			<div class="form-group">
				<label>Twitter</label>
				<input  name="twitter" value="<?php echo $twitter;?>" type="text" class="form-control">
			</div>
			<div class="form-group">
				<label>Website</label>
				<input name="website" value="<?php echo $website;?>" type="text" class="form-control">
			</div>
			<br /><br />
			<button type="submit" class="btn btn-primary btn-cons">Save</button>
		</form>
	</div>

</div>