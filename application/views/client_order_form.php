<div class="container">
	<div class="client_order_form">
		<h1><span class="glyphicon glyphicon-pencil"></span>&nbsp;&nbsp;<?php echo $heading; ?></h1>
		<?php
		if (!empty($message)) {
		?>
			<div class="alert alert-success alert-dismissable"><button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button><?php echo $message; ?></div>
		<?php
		}
		?>
		<?php echo form_open(uri_string(), 'role="form" class="form-horizontal" id="client_order_form"'); ?>
		<div class="form-group">
			<label for="order_name" class="col-sm-2 control-label">Job Name</label>
			<div class="col-sm-2">
				<input type="text" name="order_name" class="form-control input-sm" id="order_name" placeholder="Job Name" required="required">
			</div>
			<label for="lpo" class="col-sm-2 control-label">Purchase Order #</label>
			<div class="col-sm-2">
				<input type="text" name="lpo" class="form-control input-sm" id="lpo" placeholder="Local Purchase Order #">
			</div>
			<label for="contact_name" class="col-sm-2 control-label">Contact Name</label>
			<div class="col-sm-2">
				<input type="text" name="contact_name" class="form-control input-sm" id="contact_name" placeholder="Contact Name" required="required">
			</div>
		</div>
		<div class="form-group">
			<label for="contact_email" class="col-sm-2 control-label">Contact Email</label>
			<div class="col-sm-2">
				<input type="email" name="contact_email" class="form-control input-sm" id="contact_email" placeholder="Contact Email">
			</div>
			<label for="contact_mobile" class="col-sm-2 control-label">Contact Mobile</label>
			<div class="col-sm-2">
				<input type="text" name="contact_mobile" class="form-control input-sm" id="contact_mobile" placeholder="Contact Mobile">
			</div>
			<label for="date_delivery" class="col-sm-2 control-label">Req. Delivery Date</label>
			<div class="col-sm-2">
				<input type="text" name="date_delivery" class="form-control input-sm" id="date_delivery" placeholder="MM/DD/YYYY" required="required">
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-2 control-label">Artwork Submitted By</label>
			<div class="col-sm-2">
				<select name="artwork_by" class="form-control input-sm myselect" required="required">
					<option value="">--</option>
					<option value="1">Mail</option>
					<option value="2">Drop Box</option>
					<option value="3">We Transfer</option>
					<option value="4">DVD</option>
					<option value="5">FTP</option>
					<option value="6">USB</option>
					<option value="7">Other</option>
				</select>
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
						<th width="180" class="text-center">Lamination</th>
						<th width="140" class="text-center">Quality</th>
						<th width="170" class="text-center">Finishing</th>
						<th width="90" class="text-center">U.P</th>
						<th width="70" class="text-center">Extra</th>
						<th width="100" class="text-center">Total</th>
						<th width="240" class="text-center">Notes</th>
						<th class="text-center">Delete</th>
					</tr>
				</thead>
				<tbody>
					<tr class="order_row master">
						<td>1</td>
						<td><input type="text" name="filename[]" class="form-control input-sm min-pad" minlength="3" required="required"></td>
						<td><input type="text" name="width[]" class="form-control input-sm min-pad" required="required"></td>
						<td><input type="text" name="height[]" class="form-control input-sm min-pad" required="required"></td>
						<td><input type="text" name="qty[]" class="form-control input-sm min-pad" required="required"></td>
						<td><input type="text" name="m2[]" class="form-control input-sm min-pad" disabled="disabled"></td>
						<td>
							<?php
							echo form_dropdown('material[]', $options_material, '', 'class="form-control input-sm myselect" required="required"');
							?>
						</td>
						<td>
							<?php
							echo form_dropdown('lamination[]', $options_lamination, '', 'class="form-control input-sm myselect" required="required"');
							?>
						</td>
						<td>
							<?php
							echo form_dropdown('quality[]', $options_quality, '', 'class="form-control input-sm myselect" required="required"');
							?>
						</td>
						<td>
							<?php
							echo form_dropdown('finishing[]', $options_finishing, '', 'class="form-control input-sm myselect" required="required"');
							?>
						</td>
						<!-- ABDELRADY - Default value 0 when creating order -->
						<td><input type="text" name="up[]" class="form-control input-sm min-pad" value='0'></td>
						<td><input type="text" name="extra[]" class="form-control input-sm min-pad" value='0'></td>
						<!-- ABDELRADY - Default value 0 when creating order -->
						<td><input type="text" name="cost[]" class="form-control input-sm min-pad" disabled="disabled"></td>
						<td><input type="text" name="notes[]" class="form-control input-sm min-pad"></td>
						<td>
							<?php
							echo "<button class=\"btn btn-default btn-sm btn-danger btnDeleteRow hidden\"><span class=\"glyphicon glyphicon-remove\"></span></button>";
							?></td>

					</tr>
				</tbody>
				<tfoot>
					<tr>
						<td colspan="15"><button id="add_row" name="add_row" class="btn btn-primary btn-sm add_row"><span class="glyphicon glyphicon-plus-sign"></span>&nbsp;&nbsp;Add row</button></td>
					</tr>

					<tr>
						<td colspan="4" align="right"><strong>Total</strong></td>
						<td>
							<div id="grand_qty"></div>
						</td>
						<td>
							<div id="grand_size"></div>
						</td>
						<td colspan="6" align="right"><strong>Total Cost</strong></td>
						<td>
							<div id="grand_cost"></div>
						</td>
						<td colspan="2"></td>
					</tr>
				</tfoot>
			</table>
		</div>


		<br />
		<button type="submit" class="btn btn-default btnSubmit" name="save">Create Order</button>
		<button type="reset" class="btn btn-default" name="reset">Reset</button>

		<?php echo form_close(); ?>
	</div><!--/.client_order_form -->

</div><!--/.container -->