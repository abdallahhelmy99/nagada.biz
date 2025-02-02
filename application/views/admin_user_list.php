<div class="container">
<div class="row">
<div class="col-xs-8 col-md-6">
<div class="admin_user_list">
	<h1><span class="glyphicon glyphicon-user"></span>&nbsp;&nbsp;<?php echo $heading ?></h1>
	<div id="infoMessage"><?php echo $message;?></div>
	<table id="user_tbl" class="table-stripped table-bordered table-condensed table-hover">
	<thead>
	<tr>
		<th>No.</th>
		<th width="200">User Name</th>
		<th>User Type</th>
		<th>Action</th>
	</tr>
	</thead>
	<tbody>
	<?php
	for ($i=0; $i< count($users_name); $i++)
	{
	?>
	<tr class="input_row">
		<td><?php echo $i+1; ?>
		</td>
		<td class="username">
			<span><?php echo $users_name[$i] ?></span>
		</td>
		<td class="password">
			<span><?php echo $groups_name[$i] ?></span>
		</td>
		<td>
		<?php echo anchor('/admin/edit_user/'. $users_id[$i], 'Edit', 'class="btn btn-default btn-sm lnkEdit"'); ?>
		<?php 
		if ($users_id[$i] != 1)
		{
			echo anchor('/admin/delete_user/'. $users_id[$i], 'Delete', 'class="btn btn-default btn-sm lnkDelete"');
		}
		else
		{
			echo "";
		}
		?>
		</td>
	</tr>
	<?php
	}
	?>
	</tbody>
	</table>
	<br>
	<button class="btn btn-primary btnAddUser"><span class="glyphicon glyphicon-plus-sign"></span>&nbsp;&nbsp;Add User</button>
</div>
</div>
</div><!-- /.row -->
</div><!-- /.container -->