<?php
$receipt_header_text ="";
$receipt_footer_text ="";
$receipt_bg_color ="";
$receipt_text_color ="";

if(!empty($receiptInfoData))
{
	$receipt_header_text = $receiptInfoData['header_text'];
	$receipt_footer_text = $receiptInfoData['footer_text'];
	$receipt_bg_color 	 = $receiptInfoData['bg_color'];
	$receipt_text_color  = $receiptInfoData['text_color'];
}

?>

<div class="panel-body">
	<h2>Receipt Designer</h2>
	<div class="col-md-6" style="padding-left: 0px;">
		<?php if($this->session->flashdata('successMsgReceiptInfo')!='')
		{?>   
		<div class="alert alert-success">
			<strong>Success!</strong>&nbsp;&nbsp;<?php echo $this->session->flashdata('successMsgReceiptInfo');?>
		</div>
		<?php 
	}?>
	<form role="form" method="post" action="">

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
		<br /><br />
		<button name="btn-receipt-info" value="submit" type="submit" class="btn btn-primary btn-cons">Save</button>
	</form>
</div>

</div>
<?php
$this->session->set_flashdata('successMsgReceiptInfo','');
?>