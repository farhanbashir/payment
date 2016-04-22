<?php
$this->load->library('Pdf');

$html = <<<EOT

<html>
<body style="">
<div style="">
	<table width="200px" border="0" cellpadding="5" cellspacing="0" style="border: 1px solid #000;">
		<tr>
			<td align="center">
				<img src="http://localhost/payment-process-app/test/images/company-logo.jpg" width="100" />
			</td>
		</tr>
		<tr>
			<td align="center" style="font-family: verdana; font-size: 14px;">
				{STORE_NAME}
			</td>
		</tr>
		<tr>
			<td align="center" style="font-family: verdana; font-size: 10px;">
				{STORE_ADDRESS}
			</td>
		</tr>
		<tr>
			<td align="center" style="font-family: verdana; font-size: 10px;">
				{HeaderText - Lorem Ipsum is simply dummy text of the printing and typesetting industry}
			</td>
		</tr>
		<tr>
			<td align="center" style="font-family: 'Courier New'; font-size: 10px;">
				<table width="100%" border="0" cellpadding="2" cellspacing="0" >
					<tr>
						<td align="left" style="font-family: 'Courier New'; font-size: 10px;">
							<strong>MERCHANT ID:</strong>
						</td>
						<td align="right" style="font-family: 'Courier New'; font-size: 10px;">
							<strong>921531150158293</strong>
						</td>
					</tr>
					<tr>
						<td align="left" style="font-family: 'Courier New'; font-size: 10px;">
							<strong>ORDER ID:</strong>
						</td>
						<td align="right" style="font-family: 'Courier New'; font-size: 10px;">
							<strong>12345678</strong>
						</td>
					</tr>
					<tr>
						<td align="left" style="font-family: 'Courier New'; font-size: 10px;">
							<strong>INVOICE ID:</strong>
						</td>
						<td align="right" style="font-family: 'Courier New'; font-size: 10px;">
							<strong>90461839</strong>
						</td>
					</tr>
					<tr>
						<td align="left" style="font-family: 'Courier New'; font-size: 8px;">
							DATE: 10/04/2016
						</td>
						<td align="right" style="font-family: 'Courier New'; font-size: 8px;">
							TIME: 16:25:53
						</td>
					</tr>					
					<tr>
						<td colspan="2" height="10" style="height: 10px;"></td>
					</tr>
					<tr>
						<td align="left" colspan="2" style="font-family: 'Courier New'; font-size: 13px;">
							<strong>VISA <br />421310******2009</strong>
							
							<br />
							<span style="font-size: 8px;">MUHAMMAD UMAIR</span>
						</td>
					</tr>
					<tr>
						<td colspan="2" height="10" style="height: 10px;"></td>
					</tr>
					<tr>
						<td align="left" style="font-family: 'Courier New'; font-size: 10px;">
							<strong>SALE AMOUNT</strong>
						</td>
						<td align="right" style="font-family: 'Courier New'; font-size: 10px;">
							<strong>USD 4,194.00</strong>
						</td>
					</tr>
				</table>
			</td>
		</tr>
		<tr>
			<td align="center" style="font-family: 'Courier New'; font-size: 11px;">
				<strong>VERIIED BY SIGNATURE</strong>
			</td>
		</tr>
		<tr>
			<td align="center">
				<img src="http://localhost/payment-process-app/test/images/signature.png" width="150" />
			</td>
		</tr>
		<tr>
			<td align="center" style="font-family: verdana; font-size: 10px;">
				{FooterText - Lorem Ipsum is simply dummy text of the printing and typesetting industry}
			</td>
		</tr>
		<tr>
			<td align="left" style="font-family: verdana; font-size: 10px;">
				<strong>IMPORTANT:</strong> This purchase will appear as <strong>"descriptor"</strong> on your credit card statement or online transaction detail. As with any international transaction of this nature, the final posted transaction on your statement or transaction detail may vary depending on your credit card issuer. It is not uncommon for some credit card issuers to impose a small currency conversion fee.
			</td>
		</tr>
	</table>
</div>

</body>
</html>

EOT;

//echo $html;
//exit;

$pdf = new Pdf(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
						
// remove default header/footer
$pdf->setPrintHeader(false);
$pdf->setPrintFooter(false);

/*
$pdf->SetHeaderData(PDF_HEADER_LOGO, 50);
$pdf->setFooterData();*/

// set margins
//$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);

/*$pdf->SetMargins(PDF_MARGIN_LEFT, 35, PDF_MARGIN_RIGHT);
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);*/

$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

$pdf->AddPage();
$pdf->writeHTML($html, true, false, false, false, '');

$pdfPath = 'test.pdf';

$pdfFilePath = $pdfPath;
$pdf->Output($pdfFilePath, 'F');