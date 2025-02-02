
	<?php 
	$attributes = array('id' => 'checklist_form');
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
						if ($id[$i] == $this->uri->segment(3)){
							echo anchor(base_url()."checklist/client/".$id[$i], $name[$i]."<button type=\"button\" class=\"close btn-delete-client\">&times;</button>", "title='Delete &quot;".$name[$i]."&quot;' class='list-group-item active'");
						}
						else
						{
							echo anchor(base_url()."checklist/client/".$id[$i], $name[$i], "class='list-group-item'");
						}
					}
					echo anchor(base_url()."checklist/add_client/", "<span class=\"text-primary\"><strong>Add Client</strong></span>", "class='list-group-item'");
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
			<div class="col-sm-9 hidden-xs note-content">
				<div class="check-list">
				<?php
				if (isset($note_id))
				{
					for ($j=0; $j< count($note_id); $j++)
					{
						$is_checked		= ($status[$j] == 0) ? "" : "checked='checked'";
						$the_content	= ($status[$j] == 0) ? $content[$j] : "<strike>".$content[$j]."</strike>";
				?>
					<div class="checkbox">
					  <label>
						<input type="checkbox" name="cb_notes[]" value="<?php echo $note_id[$j]; ?>" <?php echo $is_checked; ?>>
						<?php echo $the_content; ?>
					  </label>
					</div>
				<?php
					}
					$extra_button 		= "<button type=\"button\" class=\"btn btn-default btn-mark-done\">Mark as Done</button>
				<button type=\"button\" class=\"btn btn-default btn-delete\">Delete</button>";
				}
				?>
				</div>
				<?php

				if (isset($client_id))
				{
				?>
				<input type="hidden" name="client_id" value="<?php echo $client_id ?>" />
				<button type="button" class="btn btn-default btn-add-new-note">Add New</button>
				<?php
				echo $extra_button;
				?>
				<button type="button" class="btn btn-default btn-save-notes">Save</button>
				<?php
				}
				?>
			</div>
		</div>

		
<!--		
        <div class="row">
			<div class="col-sm-3">
			  <div class="list-group">
				<?php
				if (isset($id))
				{
					for ($i= 0; $i< count($id); $i++)
					{
					if ($i== 0){
					?>
					<a href="<?php echo $id[$i]?>" class="list-group-item active"><?php echo $name[$i]; ?></a>
					<div class="col-sm-3 visible-xs">
						<div class="checkbox">
						  <label>
							<input type="checkbox" value="">
							Option one is this and that&mdash;be sure to include why it's great
						  </label>
						</div>
						<div class="checkbox">
						  <label>
							<input type="checkbox" value="">
							Option one is this and that&mdash;be sure to include why it's great
						  </label>
						</div>
						<div class="checkbox">
						  <label>
							<input type="checkbox" value="">
							Option one is this and that&mdash;be sure to include why it's great
						  </label>
						</div>
					</div>
					<?php
					}
					else
					{
					?>
					<a href="<?php echo $id[$i]?>" class="list-group-item"><?php echo $name[$i]; ?></a>
					<?php
					}
					}
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
					<div class="col-sm-9 hidden-xs">
						<div class="checkbox">
						  <label>
							<input type="checkbox" value="">
							Option one is this and that&mdash;be sure to include why it's great
						  </label>
						</div>
						<div class="checkbox">
						  <label>
							<input type="checkbox" value="">
							Option one is this and that&mdash;be sure to include why it's great
						  </label>
						</div>
						<div class="checkbox">
						  <label>
							<input type="checkbox" value="">
							Option one is this and that&mdash;be sure to include why it's great
						  </label>
						</div>
					</div>
			
		</div>
//-->

	</div><!-- /.container -->
	<?php echo form_close();?>
