<div class="container">

	<h1><span class="glyphicon glyphicon-list"></span>&nbsp;&nbsp;Template Order</h1>
	<div id="infoMessage"><?php echo $message; ?></div>
	<?php echo form_open(uri_string(), 'role="form" class="form-horizontal" id="admin_template_form"'); ?>
	<table id="template_order" class="table-stripped table-bordered table-condensed table-hover">
		<thead>
			<tr>
				<th width="40">No.</th>
				<th width="300">Filename</th>
				<th width="65">W</th>
				<th width="65">H</th>
				<th width="80">Quantity</th>
				<th width="150">Material</th>
				<th width="150">Lamination</th>
				<th width="150">Quality</th>
				<th width="150">Finishing</th>
				<th width="150">Assigned To</th>
			</tr>
		</thead>
		<tbody>
			<tr class="input_row master hidden">
				<td>1
				</td>
				<td>
					<span><input type="text" name="filename[]" class="form-control input-sm"></span>
				</td>
				<td><span><input type="text" name="width[]" class="form-control input-sm"></span></td>
				<td><span><input type="text" name="height[]" class="form-control input-sm"></span></td>
				<td><span><input type="text" name="quantity[]" class="form-control input-sm"></span></td>
				<td><span><?php echo form_dropdown('material[]', $options_material, '', 'class="form-control input-sm myselect"'); ?></span></td>
				<td><span><?php echo form_dropdown('lamination[]', $options_lamination, '', 'class="form-control input-sm myselect"'); ?></span></td>
				<td><span><?php echo form_dropdown('quality[]', $options_quality, '', 'class="form-control input-sm myselect"'); ?></span></td>
				<td><span><?php echo form_dropdown('finishing[]', $options_finishing, '', 'class="form-control input-sm myselect"'); ?></span></td>
				<td><span><?php echo form_dropdown('username[]', $username, 'class="form-control input-sm myselect"'); ?></span></td>

			</tr>
			<?php

			//~ print_r($_POST);
			//~ $filenames = $this->input->post("filename");
			//~ foreach ($filenames as $key => $value)
			//~ {
			//~ echo "<pre>";
			//~ echo htmlentities($value);
			//~ echo "</pre><br>";
			//~ echo "<br>";
			//~ }

			for ($i = 0; $i < count($template_order); $i++) {
				$row = $template_order[$i];

				$selected_key = null;
				foreach ($username as $key => $value) {
					if ($value == $row->username) {
						$selected_key = $key;
						break;
					}
				}

				for ($j = 0; $j < count($template_order); $j++) {
					if ($template_order[$j]->template_category == $row->template_category) {
						$template_order[$j]->username = $row->username;
					}
				}
			?>


				<tr class="input_row">
					<td><?php echo $row->template_id; ?>
					</td>
					<td>
						<span><input type="text" name="filename[]" class="form-control input-sm" value="<?php echo $row->template_filename; ?>"></span>
					</td>
					<td><span><input type="text" name="width[]" class="form-control input-sm" value="<?php echo $row->template_width; ?>"></span></td>
					<td><span><input type="text" name="height[]" class="form-control input-sm" value="<?php echo $row->template_height; ?>"></span></td>
					<td><span><input type="text" name="quantity[]" class="form-control input-sm" value="<?php echo $row->template_quantity; ?>"></span></td>
					<td><span><?php echo form_dropdown('material[]', $options_material, $row->template_material, 'class="form-control input-sm myselect"'); ?></span></td>
					<td><span><?php echo form_dropdown('lamination[]', $options_lamination, $row->template_lamination, 'class="form-control input-sm myselect"'); ?></span></td>
					<td><span><?php echo form_dropdown('quality[]', $options_quality, $row->template_quality, 'class="form-control input-sm myselect"'); ?></span></td>
					<td><span><?php echo form_dropdown('finishing[]', $options_finishing, $row->template_finishing, 'class="form-control input-sm myselect"'); ?></span></td>

					<?php



					if ($row->template_height == 0 && $row->template_width == 0 && $row->template_quantity == 0) {
						echo "<td><span>";
						echo form_dropdown('username[]', $username, $selected_key, 'class="form-control input-sm myselect" onchange="changeUserTemplate()"');
						echo "</span></td>";
					} else {
						echo "<td><span>";
						// echo form_dropdown('username[]', $username, $selected_key, 'class="form-control input-sm myselect" style="display:none;"');
						echo form_dropdown('username[]', $username, $selected_key, 'class="form-control input-sm myselect"');

						echo "</span></td>";
					}

					?>

				</tr>

				<script>

				</script>




			<?php
			}

			for ($i = 0; $i < count($template_order); $i++) {
				$row = $template_order[$i];

				for ($j = 0; $j < count($template_order); $j++) {
					if ($template_order[$j]->template_category == $row->template_category) {
						$template_order[$j]->username = $row->username;
					}
				}
			}

			?>
		</tbody>
	</table>
	<br>
	<input type="hidden" name="post_data" value="">
	<button type="button" class="btn btn-primary btnAddRow" id="AddRowTemplate"><span class="glyphicon glyphicon-plus-sign"></span>&nbsp;&nbsp;Add Row</button>
	<button type="submit" class="btn btn-success" id="SaveTemplate"><span class="glyphicon glyphicon-floppy-disk"></span>&nbsp;&nbsp;Save Template</button>
	<?php echo form_close(); ?>
	<br>
	<br>
	<br>


</div><!-- /.container -->