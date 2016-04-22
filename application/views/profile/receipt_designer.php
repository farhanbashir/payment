<?php
$receipt_header_text ="";
$receipt_footer_text ="";
$receipt_bg_color ="";
$receipt_text_color ="";

if(!empty($business_info))
{
	$receipt_header_text = $business_info['receipt_header_text'];
	$receipt_footer_text = $business_info['receipt_footer_text'];
	$receipt_bg_color 	 = $business_info['receipt_bg_color'];
	$receipt_text_color  = $business_info['receipt_text_color'];
}

?>
<div class="panel-body">
	<h2>Receipt Designer</h2>
	<div class="col-md-6" style="padding-left: 0px;">
		<?php if($this->session->flashdata('MessageTab4')!='')
	    {?>   
	        <div class="alert alert-success">
	          <strong>Success!</strong>&nbsp;&nbsp;<?php echo $this->session->flashdata('MessageTab4');?>
	        </div>
	        <?php 
	    }?>
		<form role="form" method="post" action="<?php echo $receipt_designer_form_url;?>">
			
			<div class="form-group">
				<label>Header Text</label>
				<input name="header_text" value ="<?php echo $receipt_header_text;?>" type="text" class="form-control" required="">
			</div>
			
			<div class="form-group">
				<label>Footer Text</label>
				<input name="footer_text" value ="<?php echo $receipt_footer_text;?>" type="text" class="form-control" required="">
			</div>
			
			<div class="form-group">
				<label>Background Color</label>
				<input name="bg_color" value ="<?php echo $receipt_bg_color;?>" type="text" class="form-control" required="">
			</div>
			
			<div class="form-group">
				<label>Text Color</label>
				<input name="text_color" value ="<?php echo $receipt_text_color;?>" type="text" class="form-control" required="">
			</div>
			
			<br /><br />
			<button type="submit" class="btn btn-primary btn-cons">Save</button>
		</form>
	</div>

</div>