<?php
$business_name="";
$description="";
$email="";
$phone="";
$address="";
$facebook="";
$twitter="";
$website="";

if(!empty($businessInfoData))
{
	$business_name = $businessInfoData['business'];
	$description = $businessInfoData['description'];
	$email = $businessInfoData['email'];
	$phone = $businessInfoData['phone'];
	$address = $businessInfoData['address'];
	$facebook = $businessInfoData['facebook'];
	$twitter = $businessInfoData['twitter'];
	$website = $businessInfoData['website'];
}

?>

<div class="panel-body">
	<h2>Business Information</h2>
	<div class="col-md-6" style="padding-left: 0px;">
		<?php
		if($this->session->flashdata('successMsgBusinessInfo')!='')
		{   
			echo getHTMLForSuccessMessage($this->session->flashdata('successMsgBusinessInfo'));
		}
		
		if($this->session->flashdata('errMsgBusinessInfo')!='')
		{
			echo getHTMLForErrorMessage($this->session->flashdata('errMsgBusinessInfo'));
		}
		?>
		<form role="form" method ="post"action="" enctype="multipart/form-data" accept-charset="utf-8">
			
			<div class="form-group">
				<label>Business Name</label>
				<input name="business" value="<?php echo $business_name;?>" type="text" class="form-control" >
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
				<input name="phone" value="<?php echo $phone;?>" type="text" class="form-control">
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
			<button name="btn-business-info" value="submit" type="submit" class="btn btn-primary btn-cons">Save</button>
		</form>
	</div>

</div>
<?php
$this->session->set_flashdata('successMsgBusinessInfo','');
$this->session->set_flashdata('errMsgBusinessInfo','');
?>