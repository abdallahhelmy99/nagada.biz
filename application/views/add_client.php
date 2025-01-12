<?php
$attributes = array('id' => 'add_user_form');
echo form_open(uri_string(),$attributes);
?>
&nbsp;
<div class="container">
	<div class="row">
		<div class="col-sm-3">
				<div class="list-group">
				<?php
				if (isset($id))
				{
					for ($i= 0; $i< count($id); $i++)
					{
						echo anchor(base_url()."checklist/client/".$id[$i], $name[$i], "class='list-group-item'");
					}
					echo anchor(base_url()."checklist/add_client/", "<strong>Add Client</strong>", "class='list-group-item active'");
				}
				else
				{
				?>
				<span class="list-group-item">Empty</span>
				<?php
				}
				?>
				</div>
		</div>
		<div class="col-sm-3">
				<div class="form-group">
				<label for="client_name">Client Name</label>
				<input type="text" name="client_name" class="form-control" id="client_name" placeholder="Enter client name">
				</div>
				<button type="submit" class="btn btn-default">Submit</button>
		</div>
	</div>
</div>
<?php
echo form_close();
?>