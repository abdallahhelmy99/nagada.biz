<div class="container">
<div class="client_order_form">
<h1><?php echo $heading; ?></h1>
	<?php
	if (!$empty_order)
	{
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
	<br />
	
	<div class="table-responsive">
	<?php echo form_open(uri_string(), 'role="form" class="form-horizontal" id="client_order_form"');?>

	<table id="order_tbl" class="table-bordered table-condensed table-striped table-hover">
	<thead>
	<tr>
		<th>No.</th>
		<th width="140">File Name</th>
		<th width="60">Width</th>
		<th width="60">Height</th>
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
	for ($i= 0; $i< count($filename); $i++)
	{
	?>
	<tr class="order_row">
		<td><?php echo $i+1 ?></td>
		<td><?php echo $filename[$i]; ?></td>
		<td><input type="text" class="form-control input-sm min-pad" name="width[<?php echo $i ?>]" value="<?php echo $width[$i]; ?>"></td>
		<td><input type="text" class="form-control input-sm min-pad" name="height[<?php echo $i ?>]" value="<?php echo $height[$i]; ?>"></td>
		<td><input type="text" class="form-control input-sm min-pad" name="qty[<?php echo $i ?>]" value="<?php echo $qty[$i]; ?>"></td>
		<td><input type="text" class="form-control input-sm min-pad" name="m2[<?php echo $i ?>]" value="<?php echo $m2[$i]; ?>" disabled="disabled"></td>
		<td><?php echo $material[$i]; ?></td>
		<td><?php echo $lamination[$i]; ?></td>
		<td><?php echo $quality[$i]; ?></td>
		<td><?php echo $finishing[$i]; ?></td>
		<td><input type="text" class="form-control input-sm min-pad" name="up[<?php echo $i ?>]" value="<?php echo $up[$i]; ?>"></td>
		<td><input type="text" class="form-control input-sm min-pad" name="extra[<?php echo $i ?>]" value="<?php echo $extra[$i]; ?>"></td>
		<td><input type="text" class="form-control input-sm min-pad" name="cost[<?php echo $i ?>]" value="<?php echo $total[$i]; ?>" disabled="disabled"></td>
		<td><input type="text" class="form-control input-sm min-pad" name="notes[<?php echo $i ?>]" value="<?php echo $notes[$i]; ?>"></td>
	</tr>
	<?php
	}
	?>
	</tbody>
	<tfoot>

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