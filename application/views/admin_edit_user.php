<div class="container">
<div class="edit_user_form">
	<h1><span class="glyphicon glyphicon-user"></span>&nbsp;&nbsp;<?php echo $page_heading ?></h1>

	<div id="infoMessage"><?php echo $message;?></div>

	<?php echo form_open(uri_string(), 'role="form" class="form-horizontal" id="client_order_form"');?>

	<div class="form-group">
		<label for="username" class="col-sm-2 control-label">Username</label>
		<div class="col-sm-2">
			<input type="text" name="username" class="form-control input-sm" id="username" placeholder="User Name" value="<?php echo $username['value'] ?>" required>
		</div>
	</div>	
	<div class="form-group">
		<label for="email" class="col-sm-2 control-label">Email</label>
		<div class="col-sm-2">
			<input type="email" name="email" class="form-control input-sm" id="email" placeholder="Email" value="<?php echo $email['value'] ?>" required>
		</div>
	</div>	
	<div class="form-group">
		<label for="password" class="col-sm-2 control-label">Password</label>
		<div class="col-sm-2">
			<input type="password" name="password" class="form-control input-sm" id="password" placeholder="(if changing password)">
		</div>
	</div>	
		
	<div class="form-group">
		<label for="password_confirm" class="col-sm-2 control-label">Confirm Password</label>
		<div class="col-sm-2">
			<input type="password" name="password_confirm" class="form-control input-sm" id="password_confirm" placeholder="(if changing password)">
		</div>
	</div>	
		

	<h3>Member of group:</h3>
	<?php
	if ($user->id == 1)
	{
		$disabled = " disabled='disabled'";
	}
	else
	{
		$disabled = "";
	}
	foreach ($groups as $group):
	?>
		<div class="checkbox_left">
		<label for="cb_<?php echo $group['id']; ?>">
		<?php
			$gID=$group['id'];
			$checked = null;
			$item = null;
			foreach($currentGroups as $grp) {
				if ($gID == $grp->id) {
					$checked= ' checked="checked"';
				break;
				}
			}
		?>
		<?php echo $group['name'];?>
		</label>
		<input type="radio" name="groups[]" id="cb_<?php echo $group['id']; ?>" value="<?php echo $group['id'];?>"<?php echo $checked; echo $disabled; ?>>
		</div>
		<?php 
	endforeach
	?>

		<?php echo form_hidden('id', $user->id);?>
		<?php echo form_hidden($csrf); ?>

	<button type="submit" class="btn btn-default">Save User</button>
	<?php echo form_close();?>
</div>
</div><!-- /.container -->