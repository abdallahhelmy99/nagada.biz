<?php
tcpdf();
// create new PDF document
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

$pdf->setPrintHeader(false);
$pdf->setPrintFooter(false);

// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

// set auto page breaks
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

$logo_data 	= base64_decode("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");

// set some language-dependent strings (optional)
if (@file_exists(dirname(__FILE__) . '/lang/eng.php')) {
	require_once(dirname(__FILE__) . '/lang/eng.php');
	$pdf->setLanguageArray($l);
}

// ---------------------------------------------------------

// Set font
$pdf->SetFont('helvetica', '', 10, '', true);

// Add a page
// This method has several options, check the source code documentation for more information.
$pdf->AddPage('L');

// create some HTML content
$pdf->Image(dirname(__FILE__) . '/../../assets/img/logo.jpg', '', 12, 50, '', '', '', '', '', '', 'R');

$lpo = (empty($lpo) ? "" : " / LPO #:" . $lpo);
$content = "";
$content .= "
<table border=\"0\" cellpadding=\"2\">
<tr>
<td colspan=\"8\" align=\"center\"><h1>Heliopress<br><small>Job Order Form (Ref #:" . $order_id . $lpo . ")</small></h1><br></td>
</tr>
<tr>
	<td align=\"right\" width=\"13%\"><strong>Job Name&nbsp;:</strong>
	</td>
	<td width=\"11%\">" . $order_name . "
	</td>
	<td align=\"right\" width=\"11%\"><strong>Client Name&nbsp;:</strong>
	</td>
	<td width=\"11%\">" . $order_username . "
	</td>
	<td align=\"right\" width=\"13%\"><strong>Contact Name&nbsp;:</strong>
	</td>
	<td width=\"11%\">" . $order_contact . "
	</td>
	<td align=\"right\" width=\"15%\"><strong>Contact Email&nbsp;:</strong>
	</td>
	<td width=\"15%\">" . $order_email . "
	</td>
</tr>
<tr>
	<td align=\"right\"><strong>Contact Mobile&nbsp;:</strong>
	</td>
	<td>" . $order_mobile . "
	</td>
	<td align=\"right\"><strong>Order Date&nbsp;:</strong>
	</td>
	<td>" . $order_date . "
	</td>
	<td align=\"right\"><strong>Req. Delivery Date&nbsp;:</strong>
	</td>
	<td>" . $order_delivery . "
	</td>
	<td align=\"right\"><strong>Artwork Submitted By&nbsp;:</strong>
	</td>
	<td>" . $artwork_by . "
	</td>
</tr>
</table>

<br>
<br>
<br>
<table bgcolor=\"#eeeeee\" border=\"1\" cellpadding=\"2\">
<tr align=\"center\" style=\"font-weight:bold\" bgcolor=\"#ffffff\">
	<td width=\"3%\">No.</td>
	<td width=\"11%\">File Name</td>
	<td width=\"5%\">Width</td>
	<td width=\"5%\">Height</td>
	<td width=\"5%\">Qty</td>
	<td width=\"5%\">m<sup>2</sup></td>
	<td width=\"10%\">Material</td>
	<td width=\"10%\">Lamination</td>
	<td width=\"7%\">Quality</td>
	<td width=\"9%\">Finishing</td>
	<td width=\"5%\">UP</td>
	<td width=\"5%\">Extra</td>
	<td>Total</td>
	<td width=\"13%\">Notes</td>
</tr>
";

array_multisort($material, SORT_ASC, $m2, SORT_DESC, $qty, SORT_DESC, $filename, $width, $height, $qty, $m2, $lamination, $quality, $finishing, $up, $extra, $total, $notes);

for ($i = 0; $i < count($filename); $i++) {
	$j = $i + 1;
	$content .= "
		<tr bgcolor=\"#ffffff\">
			<td>" . $j . "</td>
			<td>" . $filename[$i] . "</td>
			<td align=\"right\">" . $width[$i] . "</td>
			<td align=\"right\">" . $height[$i] . "</td>
			<td align=\"right\">" . $qty[$i] . "</td>
			<td align=\"right\">" . $m2[$i] . "</td>
			<td>" . $material[$i] . "</td>
			<td>" . $lamination[$i] . "</td>
			<td>" . $quality[$i] . "</td>
			<td>" . $finishing[$i] . "</td>
			<td align=\"right\">" . $up[$i] . "</td>
			<td align=\"right\">" . $extra[$i] . "</td>
			<td align=\"right\">" . $total[$i] . "</td>
			<td>" . $notes[$i] . "</td>
		</tr>";
}
$content .= "
	<tr>
		<td align=\"right\" colspan=\"4\"><strong>Total</strong></td>
		<td align=\"right\">" . $grand_qty . "</td>
		<td align=\"right\">" . $grand_size . "</td>
		<td align=\"right\" colspan=\"6\"><strong>Total Costs</strong></td>
		<td align=\"right\">" . $grand_cost . "</td>
		<td></td>
	</tr>
	</table>";

// output the HTML content
$pdf->writeHTML($content, true, false, true, false, '');

//Close and output PDF document
$pdf->Output('Heliopress_Job_Order_' . $order_id . '.pdf', 'I');
