<div class="container">
	<div class="admin_order_form">
		<h1><span class="glyphicon glyphicon-pencil"></span>&nbsp;&nbsp;<?php echo $heading; ?></h1>
		<!--
	<div class="alert alert-success alert-dismissable">
		<button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button><?php echo $message; ?>
	</div>
	/-->
		<?php echo form_open(uri_string(), 'role="form" class="form-horizontal" id="admin_order_form"'); ?>
		<div class="form-group">
			<label for="order_name" class="col-sm-2 control-label">Job Name</label>
			<div class="col-sm-2">
				<input type="text" name="order_name" class="form-control input-sm" id="order_name" placeholder="Job Name" value="<?php echo $order_name['value'] ?>" required>
			</div>
			<label for="lpo" class="col-sm-2 control-label">Purchase Order #</label>
			<div class="col-sm-2">
				<input type="text" name="lpo" class="form-control input-sm" id="lpo" placeholder="Purchase Order" value="<?php echo $lpo['value'] ?>">
			</div>
			<label for="contact_name" class="col-sm-2 control-label">Contact Name</label>
			<div class="col-sm-2">
				<input type="text" name="contact_name" class="form-control input-sm" id="contact_name" placeholder="Contact Name" value="<?php echo $contact_name['value'] ?>" required>
			</div>
		</div>
		<div class="form-group">
			<label for="contact_email" class="col-sm-2 control-label">Contact Email</label>
			<div class="col-sm-2">
				<input type="text" name="contact_email" class="form-control input-sm" id="contact_email" placeholder="Contact Email" value="<?php echo $contact_email['value'] ?>">
			</div>
			<label for="contact_mobile" class="col-sm-2 control-label">Contact Mobile</label>
			<div class="col-sm-2">
				<input type="text" name="contact_mobile" class="form-control input-sm" id="contact_mobile" placeholder="Contact Mobile" value="<?php echo $contact_mobile['value'] ?>">
			</div>
			<label for="date_delivery" class="col-sm-2 control-label">Req. Delivery Date</label>
			<div class="col-sm-2">
				<input type="text" name="date_delivery" class="form-control input-sm" id="date_delivery" placeholder="MM/DD/YYYY" value="<?php echo $date_delivery['value'] ?>" required>
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-2 control-label">Artwork Submitted By</label>
			<div class="col-sm-2">
				<?php
				$options_artwork = array(
					"" => "--",
					1 => "Mail",
					2 => "Drop Box",
					3 => "We Transfer",
					4 => "DVD",
					5 => "FTP",
					6 => "USB",
					7 => "Other"
				);

				echo form_dropdown('artwork_by', $options_artwork, $artwork_by, 'id="dd_artwork_by" class="form-control input-sm myselect" required="required"');
				?>
			</div>
		</div>

		<div class="form-group" style="padding-left: 55px;">

			<button type="button" id="show-qr" class="btn btn-default" style="width: 120px;">Show QR Code</button>
			<br><br>
			<div id="qr-code" style="display: none;padding-left: 0; padding-right: 0px;" class="col-sm-2">

				<?php
				$qrName = $order_id . '.png';
				if (!file_exists('qrcodes/' . $order_id . '.png')) {
					$data = 'Reference Number: ' . $order_id . "\n";
					$data .= 'Number of Items: ' . strval(count($filename)) . "\n";
					$data .= 'Job Name: ' . $order_name['value'] . "\n";
					$data .= 'Contact Name: ' . $contact_name['value'] . "\n";
					$data .= 'Contact Mobile: ' . $contact_mobile['value'] . "\n";
					$data .= 'Req. Delivery Date: ' . $date_delivery['value'] . "\n";
					$data .= 'Total Quantity: ' . $grand_qty . "\n";

					$size = '160x160';
					$ecc = 'H';
					$qrcode_url = 'https://api.qrserver.com/v1/create-qr-code/?data=' . urlencode($data) . '&size=' . $size . '&ecc=' . $ecc;

					$qrcode_image = file_get_contents($qrcode_url);
					$qrname = 'qrcodes/' . $order_id . '.png';
					file_put_contents($qrname, $qrcode_image);
				}
				echo '<img src="/heliopress/qrcodes/' . $qrName . '">';

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



		<div class="table-responsive">
			<table id="order_tbl" class="table-bordered table-condensed table-striped table-hover">
				<thead>
					<tr>
						<th class="text-center">No.</th>
						<th width="240" class="text-center">File Name</th>
						<th width="100" class="text-center">W</th>
						<th width="100" class="text-center">H</th>
						<th width="90" class="text-center">Qty</th>
						<th width="100" class="text-center">m<sup>2</sup></th>
						<th width="180" class="text-center">Material</th>
						<th width="150" class="text-center">Lamination</th>
						<th width="140" class="text-center">Quality</th>
						<th width="170" class="text-center">Finishing</th>
						<th width="100" class="text-center">U.P</th>
						<th width="100" class="text-center">Extra</th>
						<th width="100" class="text-center">Total</th>
						<th width="240" class="text-center">Notes</th>
						<th class="text-center">Delete</th>
					</tr>
				</thead>
				<tbody>
					<?php
					if (isset($filename)) {
						$rows = [];
						for ($i = 0; $i < count($filename); $i++) {
							$row = array(
								'index' => $i,
								'filename' => $filename[$i],
								'width' => $width[$i],
								'height' => $height[$i],
								'qty' => $qty[$i],
								'm2' => $m2[$i],
								'material' => $material[$i],
								'materialname' => $materialname[$i],
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
						array_multisort(array_column($rows, 'materialname'), SORT_ASC, array_column($rows, 'm2'), SORT_DESC, array_column($rows, 'qty'), SORT_DESC, $rows);
					?>

						<?php
						$x = 0;
						foreach ($rows as $row) {
							$x++;
						?>
							<tr class="order_row master">
								<td><?php echo $x; ?></td>
								<td><input type="text" name="filename[]" class="form-control input-sm min-pad" value="<?php echo $row['filename']; ?>" minlength="3" required="required"></td>
								<td><input type="text" name="width[]" class="form-control input-sm min-pad" value="<?php echo $row['width']; ?>" required="required"></td>
								<td><input type="text" name="height[]" class="form-control input-sm min-pad" value="<?php echo $row['height']; ?>" required="required"></td>
								<td><input type="text" name="qty[]" class="form-control input-sm min-pad" value="<?php echo $row['qty']; ?>" required="required"></td>
								<td><input type="text" name="m2[]" class="form-control input-sm min-pad" value="<?php echo $row['m2']; ?>" disabled="disabled"></td>
								<td> <?php echo form_dropdown('material[' . $row['index'] . ']', $options_material, $row['material'], 'class="form-control input-sm myselect" required="required"'); ?> </td>
								<td><?php echo form_dropdown('lamination[' . $row['index'] . ']', $options_lamination, $row['lamination'], 'class="form-control input-sm myselect" required="required"'); ?></td>
								<td><?php echo form_dropdown('quality[' . $row['index'] . ']', $options_quality, $row['quality'], 'class="form-control input-sm myselect" required="required"'); ?></td>
								<td><?php echo form_dropdown('finishing[' . $row['index'] . ']', $options_finishing, $row['finishing'], 'class="form-control input-sm myselect" required="required"'); ?></td>

								<td><input type="text" name="up[]" id="up_ID" class="form-control input-sm min-pad" value="<?php echo $row['up']; ?>" required></td>
								<td><input type="text" name="extra[]" class="form-control input-sm min-pad" value="<?php echo $row['extra']; ?>"></td>
								<td><input type="text" name="cost[]" class="form-control input-sm min-pad" disabled="disabled" value="<?php echo $row['total']; ?>"></td>
								<td><input type="text" name="notes[]" class="form-control input-sm min-pad" value="<?php echo $row['notes']; ?>"></td>
								<td>
									<?php
									if ($row['index'] == 0) {
										echo "<button class=\"btn btn-default btn-sm btn-danger btnDeleteRow hidden\"><span class=\"glyphicon glyphicon-remove\"></span></button>";
									} else {
										echo "<button class=\"btn btn-default btn-sm btn-danger btnDeleteRow\"><span class=\"glyphicon glyphicon-remove\"></span></button>";
									}
									?></td>
							</tr>
					<?php
						}
					}
					?>

				</tbody>
				<tfoot>
					<tr>
						<td colspan="15"><button id="add_row" name="add_row" class="btn btn-primary btn-sm add_row"><span class="glyphicon glyphicon-plus-sign"></span>&nbsp;&nbsp;Add row</button></td>
					</tr>

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

		</div>
		<br />
		<input type="hidden" name="client_id" value="<?php echo $client_id; ?>">
		<input type="hidden" name="order_date" value="<?php echo $order_date ?>" />
		<button type="submit" class="btn btn-default" name="save">Update Order</button>
		<button type="reset" class="btn btn-default" name="reset">Reset</button>

		<?php echo form_close(); ?>
	</div>

</div>