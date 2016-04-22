<?php 
$bank_name ="";
$bank_address ="";
$swift_code ="";
$account_number ="";
$account_title ="";
if(!empty($bank_info))
{
	$bank_name = $bank_info[0]['bank_name'];
	$bank_address = $bank_info[0]['bank_address'];
	$swift_code = $bank_info[0]['swift_code'];
	$account_number = $bank_info[0]['account_number'];
	$account_title = $bank_info[0]['account_title'];
}
?>
<div class="panel-body">
	<h2>Bank Information</h2>
	<div class="col-md-6" style="padding-left: 0px;">
		<?php if($this->session->flashdata('MessageTab3')!='')
	    {?>   
	        <div class="alert alert-success">
	          <strong>Success!</strong>&nbsp;&nbsp;<?php echo $this->session->flashdata('MessageTab3');?>
	        </div>
	        <?php 
	    }?>
		<form role="form" action="<?php echo $bank_info_form_url;?>" method="post">
			<div class="form-group">
				<label>Bank Name</label>
				<input name="bank_name" value="<?php echo $bank_name;?>" type="text" class="form-control" required="">
			</div>
			<div class="form-group">
				<label>Bank Address</label>
				<input name="bank_address" value="<?php echo $bank_address;?>"type="text" class="form-control" required="">
			</div>
			<div class="form-group">
				<label>Routing No / SWIFT Code</label>
				<input name="swift_code" value="<?php echo $swift_code;?>" type="text" class="form-control" required="">
			</div>
			<div class="form-group">
				<label>Account Title</label>
				<input name="account_title" value="<?php echo $account_title;?>"type="text" class="form-control" required="">
			</div>
			<div class="form-group">
				<label>Account Number / IBAN</label>
				<input  name="account_number" value="<?php echo $account_number;?>" type="text" class="form-control" required="">
			</div>
			<br /><br />
			<button type="submit" class="btn btn-primary btn-cons">Save</button>
		</form>
	</div>
</div>

