<div class="container">
<div class="client_order_form">
<h1><?php echo $heading; ?></h1>
	<?php
	if (!$empty_order)
	{
	?>
	<div class="display_row">
		<span>Ref #</span>:
		<?php echo $order_id ?>
	</div>
	<div class="display_row">
		<span>Contact Name</span>:
		<?php echo $order_contact ?>
	</div>
	<div class="display_row">
		<span>Contact Email</span>:
		<?php echo $order_email ?>
	</div>
	<div class="display_row">
		<span>Contact Mobile</span>:
		<?php echo $order_mobile ?>
	</div>
	<div class="display_row">
		<span>Order Date</span>:
		<?php echo $order_date ?>
	</div>
	<div class="display_row">
		<span>Req. Delivery Date</span>:
		<?php echo $order_delivery ?>
	</div>
	<div class="display_row">
		<span>Order Status</span>:
		<?php echo $order_status ?>
	</div>
	<br />


	<table id="order_tbl" class="table-bordered table-condensed table-striped table-hover">
	<thead>
	<tr>
		<th>No.</th>
		<th>File Name</th>
		<th>Width</th>
		<th>Height</th>
		<th>Qty</th>
		<th>m<sup>2</sup></th>
		<th>Material</th>
		<th>Lamination</th>
		<th>Quality</th>
		<th>Finishing</th>
		<th>UP</th>
		<th>Extra</th>
		<th>Cost</th>
		<th>Notes</th>
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
		<td><?php echo $width[$i]; ?></td>
		<td><?php echo $height[$i]; ?></td>
		<td><?php echo $qty[$i]; ?></td>
		<td><?php echo $m2[$i]; ?></td>
		<td><?php echo $material[$i]; ?></td>
		<td><?php echo $lamination[$i]; ?></td>
		<td><?php echo $quality[$i]; ?></td>
		<td><?php echo $finishing[$i]; ?></td>
		<td><?php echo $up[$i]; ?></td>
		<td><?php echo $extra[$i]; ?></td>
		<td><?php echo $total[$i]; ?></td>
		<td><?php echo $notes[$i]; ?></td>
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
		<td><?php echo $grand_cost ?></td>
	</tr>
	</tfoot>
	</table>
	<br>
	<?php
	echo anchor('/client/order_copy/'. $order_id, 'Copy to new order', 'role="button" class="btn btn-info"'); 
	}
	?>
</div>
</div><!-- ./container //-->
