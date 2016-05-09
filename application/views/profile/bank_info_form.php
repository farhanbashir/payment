<?php 
$bank_name ="";
$bank_address ="";
$swift_code ="";
$account_number ="";
$account_title ="";
$bank_status = CONST_BANK_STATUS_NOT_VERIFIED;

if(!empty($bankInfoData))
{
	$bank_name = $bankInfoData['bank_name'];
	$bank_address = $bankInfoData['bank_address'];
	$swift_code = $bankInfoData['swift_code'];
	$account_number = $bankInfoData['account_number'];
	$account_title = $bankInfoData['account_title'];
	
	$bank_status = $bankInfoData['bank_status'];
}

$isBankAlreadyVerified = false;
if($bank_status == CONST_BANK_STATUS_VERIFIED)
{
	$isBankAlreadyVerified = true;
}

$bankStatusMessage = '';
if($isBankAlreadyVerified)
{
	$bankStatusMessage = "No need to change your bank details as its already verified!";
}
?>

<div class="panel-body">
	<h2>Bank Information</h2>
	<div class="col-md-6" style="padding-left: 0px;">
		
		<?php
		if($bankStatusMessage)
		{ 
			echo getHTMLForNotificationMessage($bankStatusMessage);
		}
		
		if($this->session->flashdata('successMsgBankInfo')!='')
		{   
			echo getHTMLForSuccessMessage($this->session->flashdata('successMsgBankInfo'));
		}

		if($this->session->flashdata('errMsgBankInfo')!='')
		{
			echo getHTMLForErrorMessage($this->session->flashdata('errMsgBankInfo'));
		}
		?>
		<form role="form" action="" method="post">
			<div class="form-group">
				<label>Bank Name</label>
				<input name="bank_name" value="<?php echo $bank_name;?>" type="text" class="form-control" >
			</div>
			<div class="form-group">
				<label>Bank Address</label>
				<input name="bank_address" value="<?php echo $bank_address;?>"type="text" class="form-control" >
			</div>
			<div class="form-group">
				<label>Routing No / SWIFT Code</label>
				<input name="swift_code" value="<?php echo $swift_code;?>" type="text" class="form-control" >
			</div>
			<div class="form-group">
				<label>Account Title</label>
				<input name="account_title" value="<?php echo $account_title;?>"type="text" class="form-control" >
			</div>
			<div class="form-group">
				<label>Account Number / IBAN</label>
				<input  name="account_number" value="<?php echo $account_number;?>" type="text" class="form-control" >
			</div>
			<?php
			if(!$isBankAlreadyVerified)
			{
				?>
				<br /><br />
				<button name="btn-bank-info" value="submit" type="submit" class="btn btn-primary btn-cons">Save</button>
				<?php
			}
			?>
			
		</form>
	</div>
</div>
<?php
$this->session->set_flashdata('successMsgBankInfo','');
$this->session->set_flashdata('errMsgBankInfo','');
?>