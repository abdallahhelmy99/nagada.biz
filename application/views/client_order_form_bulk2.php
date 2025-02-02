<div class="container">

<!-- Tab panes -->
<div class="tab-content">
	<!-- #material -->
	<div class="tab-pane fade in active" id="material">
	<h1><span class="glyphicon glyphicon-list"></span>&nbsp;&nbsp;Template Order</h1>
	<div id="infoMessage"><?php echo $message;?></div>
	<table class="table-stripped table-bordered table-condensed table-hover">
	<thead>
	<tr>
		<th width="40">No.</th>
		<th width="180">Filename</th>
		<th width="120">W</th>
		<th width="120">H</th>
		<th width="120">Quantity</th>
		<th width="120">Material</th>
		<th width="120">Lamination</th>
		<th width="120">Quality</th>
		<th width="120">Finishing</th>
	</tr>
	</thead>
	<tbody>
	<?php
	//~ print_r($template_order);
	for ($i = 0; $i< count($template_order); $i++)
	{
		$row = $template_order[$i];
		if (empty($row->template_width) AND empty($row->template_height))
		{
	?>
	<tr class="title_row">
		<td><strong>#</strong></td>
		<td colspan="8"><span><strong><?php echo $row->template_filename; ?></strong></span>
		</td>
	</tr>

	<?php

		}
		else
		{

	?>

	<tr class="input_row">
		<td><?php echo $row->template_number; ?>
		</td>
		<td class="item">
			<span><?php echo $row->template_filename; ?></span>
		</td>
		<td><span><?php echo $row->template_width; ?></span></td>
		<td><span><?php echo $row->template_height; ?></span></td>
		<td><span><?php echo $row->template_quantity; ?></span></td>
		<td><span><?php echo $row->template_material; ?></span></td>
		<td><span><?php echo $row->template_lamination; ?></span></td>
		<td><span><?php echo $row->template_quality; ?></span></td>
		<td><span><?php echo $row->template_finishing; ?></span></td>
		<?php 
		/*
		echo anchor('/admin/edit_material/'. $key, 'Edit', 'class="btn btn-default btn-sm lnkEdit"');
		echo "&nbsp;";
		echo anchor('/admin/delete_material/'. $key, 'Delete', 'class="btn btn-default btn-sm lnkDelete"');
		*/
		?>
	</tr>

	<?php
		}
	}

	?>
	</tbody>
	</table>
	<br>
	<button class="btn btn-primary btnAddMaterial"><span class="glyphicon glyphicon-plus-sign"></span>&nbsp;&nbsp;Add Material</button>
	<br>
	<br>
	<br>
	</div>
	
	
</div><!-- /#tab-content -->
</div><!-- /.container -->
