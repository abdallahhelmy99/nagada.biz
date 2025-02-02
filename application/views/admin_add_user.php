<div class="container">
<div class="add_user_form">
	<h1><?php echo lang('create_user_heading');?></h1>
	<p><?php echo lang('create_user_subheading');?></p>

	<div id="infoMessage"><?php echo $message;?></div>
	
	<?php echo form_open(uri_string(), 'role="form" class="form-horizontal" id="client_order_form"');?>

	<div class="form-group">
		<label for="username" class="col-sm-2 control-label">Username</label>
		<div class="col-sm-2">
			<input type="text" name="username" class="form-control input-sm" id="username" placeholder="User Name" required>
		</div>
	</div>	
	
	<div class="form-group">
		<label for="password" class="col-sm-2 control-label">Password</label>
		<div class="col-sm-2">
			<input type="password" name="password" class="form-control input-sm" id="password" placeholder="Password" required>
		</div>
	</div>	
	
	<div class="form-group">
		<label for="password_confirm" class="col-sm-2 control-label">Confirm Password</label>
		<div class="col-sm-2">
			<input type="password" name="password_confirm" class="form-control input-sm" id="password_confirm" placeholder="Confirm Password" required>
		</div>
	</div>	

	<div class="form-group">
		<label for="email" class="col-sm-2 control-label">Email</label>
		<div class="col-sm-2">
			<input type="email" name="email" class="form-control input-sm" id="email" placeholder="Email" required>
		</div>
	</div>	
	
	<div class="form-group">
		<label class="col-sm-2 control-label">Group</label>
		<div class="col-sm-2">
		<?php echo form_dropdown('user_group', $ug_options, $ug_value, ' class="form-control input-sm myselect"');?>
		</div>
	</div>

	<br/ >
	<p class="center">
	<input type="submit" class="btn btn-default" name="save" value="Create User" />
	<input type="reset" class="btn btn-default" name="reset" value="Reset" />
	</p>
</div>
</div><!-- /.container -->