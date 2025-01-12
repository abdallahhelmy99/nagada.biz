<div class="container">
<div class="edit_user_form">
	<h1><?php echo $page_heading ?></h1>

	<?php echo form_open(uri_string(), 'role="form" class="form-horizontal" id="item_edit"');?>

	<div class="form-group">
		<label for="itemname" class="col-sm-2 control-label">Item Name</label>
		<div class="col-sm-2">
			<input type="text" name="itemname" class="form-control input-sm" id="itemname" placeholder="Item Name" value="<?php echo $item_name ?>" required>
		</div>
	</div>	
	<?php echo form_hidden('id', $item_id);?>

	<button type="submit" class="btn btn-default">Save Item</button>
	<?php echo form_close();?>
</div>
</div><!-- /.container -->