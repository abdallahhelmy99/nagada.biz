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
$pdf->Image('@' . $logo_data, '', 12, 50, '', '', '', '', '', '', 'R');

$lpo = (empty($lpo) ? "" : " / LPO #:" . $lpo);
$content = "";
$content .= "
<table border=\"0\" cellpadding=\"2\">
<tr>
<td colspan=\"8\" align=\"center\"><h1>Nagada Production<br><small>Job Order Form (Ref #:" . $order_id . $lpo . ")</small></h1><br></td>
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
$pdf->Output('Nagada_Job_Order_' . $order_id . '.pdf', 'I');
