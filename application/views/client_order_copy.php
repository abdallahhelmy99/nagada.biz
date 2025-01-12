
<div class="container">
<div class="client_order_form">
<h1><span class="glyphicon glyphicon-pencil"></span>&nbsp;&nbsp;<?php echo $heading; ?></h1>
<div id="infoMessage"><?php echo $message;?></div>
	<?php echo form_open(uri_string(), 'role="form" class="form-horizontal" id="client_order_form"');?>
	<div class="form-group">
		<label for="order_name" class="col-sm-2 control-label">Job Name</label>
		<div class="col-sm-2">
			<input type="text" name="order_name" class="form-control input-sm" id="order_name" placeholder="Job Name" value="<?php echo $order_name['value'] ?>" required>
		</div>
		<label for="lpo" class="col-sm-2 control-label">Purchase Order #</label>
		<div class="col-sm-2">
			<input type="text" name="lpo" class="form-control input-sm" id="lpo" placeholder="Local Purchase Order #" value="<?php echo $lpo['value'] ?>">
		</div>
		<label for="contact_name" class="col-sm-2 control-label">Contact Name</label>
		<div class="col-sm-2">
			<input type="text" name="contact_name" class="form-control input-sm" id="contact_name" placeholder="Contact Name" value="<?php echo $contact_name['value'] ?>" required>
		</div>
	</div>	
	<div class="form-group">
		<label for="contact_email" class="col-sm-2 control-label">Contact Email</label>
		<div class="col-sm-2">
			<input type="text" name="contact_email" class="form-control input-sm" id="contact_email" placeholder="Contact Email" value="<?php echo $contact_email['value'] ?>" required>
		</div>
		<label for="contact_mobile" class="col-sm-2 control-label">Contact Mobile</label>
		<div class="col-sm-2">
			<input type="text" name="contact_mobile" class="form-control input-sm" id="contact_mobile" placeholder="Contact Mobile" value="<?php echo $contact_mobile['value'] ?>" required>
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
					7 => "Other");

		echo form_dropdown('artwork_by', $options_artwork, $artwork_by, 'id="dd_artwork_by" class="form-control input-sm myselect" required="required"');					
		?>
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
	if (isset($filename))
	{
		for ($i= 0; $i< count($filename); $i++)
		{
	?>
	<tr class="order_row master">
		<td><?php echo $i+1; ?></td>
		<td><input type="text" name="filename[]" class="form-control input-sm min-pad" value="<?php echo $filename[$i] ?>" minlength="3" required="required"></td>
		<td><input type="text" name="width[]" class="form-control input-sm min-pad" value="<?php echo $width[$i] ?>" required="required"></td>
		<td><input type="text" name="height[]" class="form-control input-sm min-pad" value="<?php echo $height[$i] ?>" required="required"></td>
		<td><input type="text" name="qty[]" class="form-control input-sm min-pad" value="<?php echo $qty[$i] ?>" required="required"></td>
		<td><input type="text" name="m2[]" class="form-control input-sm min-pad" value="<?php echo $m2[$i] ?>" disabled="disabled"></td>
		<td>
		<?php
		echo form_dropdown('material['.$i.']', $options_material, $material[$i], 'class="form-control input-sm myselect" required="required"');
		?>
		</td>
		<td>
		<?php
		echo form_dropdown('lamination['.$i.']', $options_lamination, $lamination[$i], 'class="form-control input-sm myselect" required="required"');
		?>
		</td>
		<td>
		<?php
		echo form_dropdown('quality['.$i.']', $options_quality, $quality[$i], 'class="form-control input-sm myselect" required="required"');
		?>
		</td>
		<td>
		<?php
		echo form_dropdown('finishing['.$i.']', $options_finishing, $finishing[$i], 'class="form-control input-sm myselect" required="required"');
		?>
		
		</td>
		<td><input type="text" name="up[]" class="form-control input-sm min-pad" value="<?php echo $up[$i] ?>" required></td>
		<td><input type="text" name="extra[]" class="form-control input-sm min-pad" value="<?php echo $extra[$i] ?>"></td>
		<td><input type="text" name="cost[]" class="form-control input-sm min-pad" disabled="disabled" value="<?php echo $total[$i] ?>" /></td>
		<td><input type="text" name="notes[]" class="form-control input-sm min-pad" value="<?php echo $notes[$i] ?>" /></td>
		<td>
		<?php
		if ($i == 0)
		{
			echo "<button class=\"btn btn-default btn-sm btn-danger btnDeleteRow hidden\"><span class=\"glyphicon glyphicon-remove\"></span></button>";
		}
		else
		{
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
		<td><div id="grand_qty"><?php echo $grand_qty ?></div></td>
		<td><div id="grand_size"><?php echo $grand_size ?></div></td>
		<td colspan="6" align="right"><strong>Total Cost</strong></td>
		<td><div id="grand_cost"><?php echo $grand_cost ?></div></td>
		<td colspan="2"></td>
	</tr>
	</tfoot>
	</table>

	</div>
	<br />
	<input type="hidden" name="order_date" value="<?php echo $order_date ?>" />
	<button type="submit" class="btn btn-default" name="save">Create Order</button>
	<!--<button type="reset" class="btn btn-default" name="reset">Reset</button>//-->

	<?php echo form_close(); ?>
</div>

</div>
