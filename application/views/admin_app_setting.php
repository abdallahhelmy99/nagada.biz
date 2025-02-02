<div class="container">
<!-- Nav tabs -->
<br>
<ul class="nav nav-tabs">
  <li class="active"><a href="#material" data-toggle="tab">Material</a></li>
  <li><a href="#lamination" data-toggle="tab">Lamination</a></li>
  <li><a href="#quality" data-toggle="tab">Quality</a></li>
  <li><a href="#finishing" data-toggle="tab">Finishing</a></li>
</ul>

<!-- Tab panes -->
<div class="tab-content">
	<!-- #material -->
	<div class="tab-pane fade in active" id="material">
	<h1><span class="glyphicon glyphicon-list"></span>&nbsp;&nbsp;List Material</h1>
	<div id="infoMessage"><?php echo $message;?></div>
	<table class="table-stripped table-bordered table-condensed table-hover">
	<thead>
	<tr>
		<th width="40">No.</th>
		<th width="180">Material</th>
		<th width="120">Action</th>
	</tr>
	</thead>
	<tbody>
	<?php
	$i=1;
	foreach ($material as $key => $value)
	{
		if ($i!=1)
		{
	?>
	<tr class="input_row">
		<td><?php echo $i-1; ?>
		</td>
		<td class="item">
			<span><?php echo $value ?></span>
		</td>
		<td>
		<?php 
		echo anchor('/admin/edit_material/'. $key, 'Edit', 'class="btn btn-default btn-sm lnkEdit"');
		echo "&nbsp;";
		echo anchor('/admin/delete_material/'. $key, 'Delete', 'class="btn btn-default btn-sm lnkDelete"');
		?>
		</td>
	</tr>
	<?php
		}
		$i++;
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
	
	
	<!-- #lamination -->
	<div class="tab-pane fade" id="lamination">
	<h1><span class="glyphicon glyphicon-list"></span>&nbsp;&nbsp;List Lamination</h1>
	<div id="infoMessage"><?php echo $message;?></div>
	<table class="table-stripped table-bordered table-condensed table-hover">
	<thead>
	<tr>
		<th width="40">No.</th>
		<th width="180">Lamination</th>
		<th width="120">Action</th>
	</tr>
	</thead>
	<tbody>
	<?php
	$i=1;
	foreach ($lamination as $key => $value)
	{
		if ($i!=1)
		{
	?>
	<tr class="input_row">
		<td><?php echo $i-1; ?>
		</td>
		<td class="item">
			<span><?php echo $value ?></span>
		</td>
		<td>
		<?php 
		echo anchor('/admin/edit_lamination/'. $key, 'Edit', 'class="btn btn-default btn-sm lnkEdit"');
		echo "&nbsp;";
		echo anchor('/admin/delete_lamination/'. $key, 'Delete', 'class="btn btn-default btn-sm lnkDelete"');
		?>
		</td>
	</tr>
	<?php
		}
		$i++;
	}
	?>
	</tbody>
	</table>
	<br>
	<button class="btn btn-primary btnAddLamination"><span class="glyphicon glyphicon-plus-sign"></span>&nbsp;&nbsp;Add Lamination</button>
	</div>
	
	
	
	<!-- #quality -->
	<div class="tab-pane fade" id="quality">
	<h1><span class="glyphicon glyphicon-list"></span>&nbsp;&nbsp;List Quality</h1>
	<div id="infoMessage"><?php echo $message;?></div>
	<table class="table-stripped table-bordered table-condensed table-hover">
	<thead>
	<tr>
		<th width="40">No.</th>
		<th width="180">Quality</th>
		<th width="120">Action</th>
	</tr>
	</thead>
	<tbody>
	<?php
	$i=1;
	foreach ($quality as $key => $value)
	{
		if ($i!=1)
		{
	?>
	<tr class="input_row">
		<td><?php echo $i-1; ?>
		</td>
		<td class="item">
			<span><?php echo $value ?></span>
		</td>
		<td>
		<?php 
		echo anchor('/admin/edit_quality/'. $key, 'Edit', 'class="btn btn-default btn-sm lnkEdit"');
		echo "&nbsp;";
		echo anchor('/admin/delete_quality/'. $key, 'Delete', 'class="btn btn-default btn-sm lnkDelete"');
		?>
		</td>
	</tr>
	<?php
		}
		$i++;
	}
	?>
	</tbody>
	</table>
	<br>
	<button class="btn btn-primary btnAddQuality"><span class="glyphicon glyphicon-plus-sign"></span>&nbsp;&nbsp;Add Quality</button>
	</div>
	
	
	<!-- #finishing -->
	<div class="tab-pane fade" id="finishing">
	<h1><span class="glyphicon glyphicon-list"></span>&nbsp;&nbsp;List Finishing</h1>
	<div id="infoMessage"><?php echo $message;?></div>
	<table class="table-stripped table-bordered table-condensed table-hover">
	<thead>
	<tr>
		<th width="40">No.</th>
		<th width="180">Finishing</th>
		<th width="120">Action</th>
	</tr>
	</thead>
	<tbody>
	<?php
	$i=1;
	foreach ($finishing as $key => $value)
	{
		if ($i!=1)
		{
	?>
	<tr class="input_row">
		<td><?php echo $i-1; ?>
		</td>
		<td class="item">
			<span><?php echo $value ?></span>
		</td>
		<td>
		<?php 
		echo anchor('/admin/edit_finishing/'. $key, 'Edit', 'class="btn btn-default btn-sm lnkEdit"');
		echo "&nbsp;";
		echo anchor('/admin/delete_finishing/'. $key, 'Delete', 'class="btn btn-default btn-sm lnkDelete"');
		?>
		</td>
	</tr>
	<?php
		}
		$i++;
	}
	?>
	</tbody>
	</table>
	<br>
	<button class="btn btn-primary btnAddFinishing"><span class="glyphicon glyphicon-plus-sign"></span>&nbsp;&nbsp;Add Finishing</button>
	</div>
</div><!-- /#tab-content -->
</div><!-- /.container -->