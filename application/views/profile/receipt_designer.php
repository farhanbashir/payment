<?php
$receipt_header_text ="";
$receipt_footer_text ="";
$receipt_bg_color ="";
$receipt_text_color ="";
$logo = "";
$tempLogo = "";
$test_email = "";
if(!empty($receiptInfoData))
{
	$receipt_header_text = $receiptInfoData['header_text'];
	$receipt_footer_text = $receiptInfoData['footer_text'];
	$receipt_bg_color 	 = $receiptInfoData['bg_color'];
	$receipt_text_color  = $receiptInfoData['text_color'];
	$receipt_text_color  = $receiptInfoData['text_color'];
	$logo 				 = $receiptInfoData['old_image'];
	$tempLogo 		     = $receiptInfoData['tempLogo'];
	$test_email 		 = $receiptInfoData['test_email'];

}

?>

<div class="panel-body">
	<h2>Receipt Designer</h2>
	<div class="col-md-6" style="padding-left: 0px;">
		<?php
		if($this->session->flashdata('successMsgReceiptInfo')!='')
		{   
			echo getHTMLForSuccessMessage($this->session->flashdata('successMsgReceiptInfo'));
		}
		
		if($this->session->flashdata('errMsgReceiptInfo')!='')
		{
			echo getHTMLForErrorMessage($this->session->flashdata('errMsgReceiptInfo'));
		}
		?>
		
		<form role="form" method ="post"action="" enctype="multipart/form-data" accept-charset="utf-8">
			<div class="form-group">
				<label>Logo</label>
				<input type="file" name="image" >
				<input type="hidden" name="old_image" value="<?php echo $logo;?>">
				<input type="hidden" name="tempLogo" value="<?php echo $tempLogo;?>">
				<?php
					if($logo && $tempLogo =='')						
					{
						?>
							<br /><br />
							<img src="<?php echo $logo;?>" width="150" alt="." />
						<?php
					}

					if($tempLogo!='')
					{
						?>
							<br /><br />
							<img src="<?php echo $tempLogo;?>" width="150" alt="." />
						<?php
					}
				?>				
			</div>	
			<div class="form-group">
				<label>Header Text</label>
				<input name="header_text" value ="<?php echo $receipt_header_text;?>" type="text" class="form-control" >
			</div>

			<div class="form-group">
				<label>Footer Text</label>
				<input name="footer_text" value ="<?php echo $receipt_footer_text;?>" type="text" class="form-control" >
			</div>

			<div class="form-group">
				<label>Background Color</label>
				<input id="saturation-demo" data-control="saturation" name="bg_color" value ="<?php echo $receipt_bg_color;?>" type="text" class="form-control demo" >
			</div>

			<div class="form-group">
				<label>Text Color</label>
				<input data-control="saturation" id="saturation-demo" name="text_color" value ="<?php echo $receipt_text_color;?>" type="text" class="form-control demo" >
			</div>
			
			<div class="form-group">
				<label>Email Address for Testing</label>
				<input name="test_email" value ="<?php echo $test_email;?>" type="text" class="form-control" >
			</div>
			
			<br /><br />			
			<button name="btn-receipt-info" value="submit" type="submit" class="btn btn-primary btn-cons">Save</button>
			&nbsp; &nbsp;
			<button name="btn-send-test-reciept" value="submit" type="submit" class="btn btn-warning btn-cons">Send Test Email</button>
			
		</form>
	</div>

</div>
<?php
$this->session->set_flashdata('successMsgReceiptInfo','');
$this->session->set_flashdata('errMsgReceiptInfo','');
?>