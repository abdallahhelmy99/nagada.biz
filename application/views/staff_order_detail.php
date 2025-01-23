<div class="container" id="client_order_form">
	<div class="client_order_form">
		<h1><?php echo $heading; ?></h1>
		<?php
		if (!$empty_order) {
		?>
			<div class="row">
				<div class="col-xs-4 col-md-2">
					<strong>Ref #</strong>
				</div>
				<div class="col-xs-6 col-md-4">
					<?php echo $order_id ?>
				</div>
			</div>
			<div class="row">
				<div class="col-xs-4 col-md-2">
					<strong>Job Name</strong>
				</div>
				<div class="col-xs-6 col-md-4">
					<?php echo $order_name ?>
				</div>
			</div>
			<div class="row">
				<div class="col-xs-4 col-md-2">
					<strong>LPO #</strong>
				</div>
				<div class="col-xs-6 col-md-4">
					<?php echo $lpo ?>
				</div>
			</div>
			<div class="row">
				<div class="col-xs-4 col-md-2">
					<strong>Contact Name</strong>
				</div>
				<div class="col-xs-6 col-md-4">
					<?php echo $order_contact ?>
				</div>
			</div>
			<div class="row">
				<div class="col-xs-4 col-md-2">
					<strong>Contact Email</strong>
				</div>
				<div class="col-xs-6 col-md-4">
					<?php echo $order_email ?>
				</div>
			</div>
			<div class="row">
				<div class="col-xs-4 col-md-2">
					<strong>Contact Mobile</strong>
				</div>
				<div class="col-xs-6 col-md-4">
					<?php echo $order_mobile ?>
				</div>
			</div>
			<div class="row">
				<div class="col-xs-4 col-md-2">
					<strong>Order Date</strong>
				</div>
				<div class="col-xs-6 col-md-4">
					<?php echo $order_date ?>
				</div>
			</div>
			<div class="row">
				<div class="col-xs-4 col-md-2">
					<strong>Req. Delivery Date</strong>
				</div>
				<div class="col-xs-6 col-md-4">
					<?php echo $order_delivery ?>
				</div>
			</div>
			<div class="row">
				<div class="col-xs-4 col-md-2">
					<strong>Order Status</strong>
				</div>
				<div class="col-xs-6 col-md-4">
					<?php echo $order_status ?>
				</div>
			</div>

			<!-- ABDELRADY - QR Code API -->
			<br>
			<div class="row" style="padding-left: 15px;">
				<button type="button" id="show-qr" class="btn btn-default">Show QR Code</button>
				<br><br>
				<div id="qr-code" style="display: none; margin-left:20;">

					<?php

					$qrName = $order_id . '.png';
					if (!file_exists('qrcodes/' . $order_id . '.png')) {
						$data = 'Reference Number: ' . $order_id . "\n";
						$data .= 'Number of Items: ' . strval(count($filename)) . "\n";
						$data .= 'Job Name: ' . $order_name . "\n";
						$data .= 'Contact Name: ' . $order_contact . "\n";
						$data .= 'Contact Mobile: ' . $order_mobile . "\n";
						$data .= 'Req. Delivery Date: ' . $order_delivery . "\n";
						$data .= 'Total Quantity: ' . $grand_qty . "\n";
						$size = '160x160';
						$ecc = 'H';
						$qrcode_url = 'https://api.qrserver.com/v1/create-qr-code/?data=' . urlencode($data) . '&size=' . $size . '&ecc=' . $ecc;
						$qrcode_image = file_get_contents($qrcode_url);
						$qrname = 'qrcodes/' . $order_id . '.png';
						file_put_contents($qrname, $qrcode_image);
					}
					echo '<img src="/qrcodes/' . $qrName . '">';

					?>

					<script>
						const showQrBtn = document.getElementById('show-qr');
						const qrCodeDiv = document.getElementById('qr-code');

						showQrBtn.addEventListener('click', () => {
							if (qrCodeDiv.style.display === 'none') {
								qrCodeDiv.style.display = 'block';
							} else {
								qrCodeDiv.style.display = 'none';
							}
						});
					</script>
				</div>
			</div>
			<!-- ABDELRADY - QR Code API -->

			<br />

			<div class="table-responsive">
				<?php echo form_open(uri_string(), 'role="form" class="form-horizontal" id="client_order_form"'); ?>

				<table id="order_tbl" class="table-bordered table-condensed table-striped table-hover">
					<thead>
						<tr>
							<th>No.</th>
							<th width="140">File Name</th>
							<th width="60">Width</th>
							<th width="60">Height</th>
							<!-- ABDELRADY - W/H Table Header -->
							<th width="60">Ratio</th>
							<!-- ABDELRADY - W/H Table Header -->
							<th width="50">Qty</th>
							<th width="80">m<sup>2</sup></th>
							<th>Material</th>
							<th>Lamination</th>
							<th>Quality</th>
							<th>Finishing</th>
							<th width="60">U.P</th>
							<th width="80">Extra</th>
							<th width="100">Total</th>
							<th width="260">Notes</th>
						</tr>
					</thead>
					<tbody>
						<?php
						// ABDELRADY - Sorting
						$rows = [];
						for ($i = 0; $i < count($filename); $i++) {
							$row = array(
								'index' => $i,
								'filename' => $filename[$i],
								'width' => $width[$i],
								'height' => $height[$i],
								'wh_ratio' => ($height[$i] != 0) ? round($width[$i] / $height[$i], 2) : 0,
								'qty' => $qty[$i],
								'm2' => $m2[$i],
								'material' => $material[$i],
								'lamination' => $lamination[$i],
								'quality' => $quality[$i],
								'finishing' => $finishing[$i],
								'up' => $up[$i],
								'extra' => $extra[$i],
								'total' => $total[$i],
								'notes' => $notes[$i],
							);
							$rows[] = $row;
						}
						array_multisort(array_column($rows, 'material'), SORT_ASC, array_column($rows, 'm2'), SORT_DESC, array_column($rows, 'qty'), SORT_DESC, $rows);
						?>

						<?php
						$x = 0;
						foreach ($rows as $row) {
							$x++;
						?>
							<tr class="order_row">
								<td><?php echo $x ?></td>
								<td><?php echo $row['filename']; ?></td>
								<td><input type="text" class="form-control input-sm min-pad" name="width[<?php echo $row['index']; ?>]" value="<?php echo $row['width']; ?>"></td>
								<td><input type="text" class="form-control input-sm min-pad" name="height[<?php echo $row['index']; ?>]" value="<?php echo $row['height']; ?>"></td>
								<td><input type="text" class="form-control input-sm min-pad" name="height[<?php echo $row['index']; ?>]" value="<?php echo $row['wh_ratio']; ?>"></td>
								<td><input type="text" class="form-control input-sm min-pad" name="qty[<?php echo $row['index']; ?>]" value="<?php echo $row['qty']; ?>"></td>
								<td><input type="text" class="form-control input-sm min-pad" name="m2[<?php echo $row['index']; ?>]" value="<?php echo $row['m2']; ?>" disabled="disabled"></td>
								<td><?php echo $row['material']; ?></td>
								<td><?php echo $row['lamination']; ?></td>
								<td><?php echo $row['quality']; ?></td>
								<td><?php echo $row['finishing']; ?></td>
								<td><input type="text" class="form-control input-sm min-pad" name="up[<?php echo $row['index']; ?>]" value="<?php echo $row['up']; ?>"></td>
								<td><input type="text" class="form-control input-sm min-pad" name="extra[<?php echo $row['index']; ?>]" value="<?php echo $row['extra']; ?>"></td>
								<td><input type="text" class="form-control input-sm min-pad" name="cost[<?php echo $row['index']; ?>]" value="<?php echo $row['total']; ?>" disabled="disabled"></td>
								<td><input type="text" class="form-control input-sm min-pad" name="notes[<?php echo $row['index']; ?>]" value="<?php echo $row['notes']; ?>"></td>
							</tr>
						<?php }
						// ABDELRADY - Sorting
						?>

					</tbody>
					<tfoot>

						<tr>
							<td colspan="4" align="right"><strong>Total</strong></td>
							<td>
								<div id="grand_qty"><?php echo $grand_qty ?></div>
							</td>
							<td>
								<div id="grand_size"><?php echo $grand_size ?></div>
							</td>
							<td colspan="6" align="right"><strong>Total Cost</strong></td>
							<td>
								<div id="grand_cost"><?php echo $grand_cost ?></div>
							</td>
							<td colspan="2"></td>
						</tr>
					</tfoot>
				</table>
				<br />
				<input type="hidden" name="detail_id" value="<?php echo $detail_id ?>">
				<input type="hidden" name="order_id" value="<?php echo $order_id ?>">
				<input type="hidden" name="submitted" value="1">
				<button type="submit" class="btn btn-default">Update Order</button>
				<?php echo form_close(); ?>
			</div><!-- /.table-responsive -->
		<?php
		}
		?>
	</div>

</div><!-- /.container -->