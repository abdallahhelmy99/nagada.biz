<?php


$pagestring = "p-1";
$segs = $this->uri->segment_array();

$sort["ref"]		= "sort-ref-up";
$sort["jobname"]	= "sort-jobname-up";
$sort["lpo"]		= "sort-lpo-up";
$sort["orderdate"]	= "sort-orderdate-up";
$sort["expdelivery"]	= "sort-expdelivery-up";
$sort["clientname"]	= "sort-clientname-up";
$sort["artwork"]	= "sort-artwork-up";
$sort["status"]		= "sort-status-up";
$css_class["ref"]		= "glyphicon-sort";
$css_class["jobname"]	= "glyphicon-sort";
$css_class["lpo"]		= "glyphicon-sort";
$css_class["orderdate"]	= "glyphicon-sort";
$css_class["expdelivery"]	= "glyphicon-sort";
$css_class["clientname"]	= "glyphicon-sort";
$css_class["artwork"]	= "glyphicon-sort";
$css_class["status"]		= "glyphicon-sort";

$keywords["ref"]	= "";
$keywords["job"]	= "";
$keywords["lpo"]	= "";
$keywords["ord"]	= "";
$keywords["exd"]	= "";
$keywords["ctt"]	= "";
$keywords["cnn"]	= "";
$keywords["art"]	= "";
$keywords["sts"]	= "";


foreach ($segs as $segment) {
	$parameter = explode("-", $segment); {
		if (count($parameter) > 1) {
			switch ($parameter[0]) {
				case "p":
					$pagenum = (int) $parameter[1];
					$pagestring = "p-" . $pagenum;
					break;
				case "sort":
					if (array_key_exists($parameter[1], $sort)) {
						$sortindex = $parameter[1];
						$sorttype = (strtolower($parameter[2]) == "up") ? "down" : "up";
						$sort[$sortindex] 	= "sort-" . $sortindex . "-" . $sorttype;
						$css_class[$sortindex] 	= "glyphicon glyphicon-chevron-" . $sorttype;
					}
					break;
					// Search keywords	
				case "s":
					if (!empty($parameter[2])) {
						$keywords[$parameter[1]]				= $parameter[2];
					}
					break;
			}
		}
	}
}

if (isset($keywords)) {
	foreach ($keywords as $k => $v) {
		if (!empty($v)) {
			$arr_keywords[] = "s-" . $k . "-" . $v;
		}
	}
	if (isset($arr_keywords)) {
		$str_keywords = implode("/", $arr_keywords);
	} else {
		$str_keywords = "";
	}
}

//~ print_r($keywords);

?>


<div class="container">
	<?php
	if (!empty($message)) {
	?>
		<div class="alert alert-success alert-dismissable"><button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button><?php echo $message; ?></div>
	<?php
	}
	?>
	<h1><span class="glyphicon glyphicon-list"></span>&nbsp;&nbsp;<?php echo $heading; ?></h1>
	<?php echo form_open(uri_string()); ?>

	<div class="table-responsive">
		<table id="order_tbl" width="100%" class="table-bordered table-condensed table-striped table-hover">
			<thead>
				<!--
	<tr>
		<th class="text-center">No.</th>
		<th class="text-center">Ref #</th>
		<th class="text-center">LPO #</th>
		<th class="text-center">Order Date</th>
		<th class="text-center">Req. Delivery Date</th>
		<th class="text-center">Client Name</th>
		<th class="text-center">Job Name</th>
		<th class="text-center">File Name</th>
		<th class="text-center">Quantity</th>
		<th class="text-center" width="130">Process</th>
		<th width="10%" class="text-center">Action</th>
	</tr>
	/-->
				<tr>
					<th class="text-center">No.&nbsp;</th>
					<th class="text-center">Ref #&nbsp;<small><a href="<?php echo base_url() . "staff/index/" . $sort["ref"] . "/" . $pagestring . "/" . $str_keywords . ""; ?>" class="sort-link"><span class="glyphicon <?php echo $css_class["ref"]; ?> text-primary"></span></a></small></th>
					<th class="text-center">LPO #&nbsp;<small><a href="<?php echo base_url() . "staff/index/" . $sort["lpo"] . "/" . $pagestring . "/" . $str_keywords . ""; ?>" class="sort-link"><span class="glyphicon <?php echo $css_class["lpo"]; ?>  text-primary"></span></a></small></th>
					<th class="text-center">Order Date&nbsp;<small><a href="<?php echo base_url() . "staff/index/" . $sort["orderdate"] . "/" . $pagestring . "/" . $str_keywords . ""; ?>" class="sort-link"><span class="glyphicon <?php echo $css_class["orderdate"]; ?>  text-primary"></span></a></small></th>
					<th class="text-center">Req. Delivery Date&nbsp;<small><a href="<?php echo base_url() . "staff/index/" . $sort["expdelivery"] . "/" . $pagestring . "/" . $str_keywords . ""; ?>" class="sort-link"><span class="glyphicon <?php echo $css_class["expdelivery"]; ?>  text-primary"></span></a></small></th>
					<th class="text-center">Client Name&nbsp;<small><a href="<?php echo base_url() . "staff/index/" . $sort["clientname"] . "/" . $pagestring . "/" . $str_keywords . ""; ?>" class="sort-link"><span class="glyphicon <?php echo $css_class["clientname"]; ?>  text-primary"></span></a></small></th>
					<th class="text-center">Job Name&nbsp;<small><a href="<?php echo base_url() . "staff/index/" . $sort["jobname"] . "/" . $pagestring . "/" . $str_keywords . ""; ?>" class="sort-link"><span class="glyphicon <?php echo $css_class["jobname"]; ?>  text-primary"></span></a></small></th>
					<th class="text-center" width="15%">Filename&nbsp;</th>
					<th class="text-center">Quantity&nbsp;</th>
					<th class="text-center">Process&nbsp;<small><a href="<?php echo base_url() . "staff/index/" . $sort["status"] . "/" . $pagestring . "/" . $str_keywords . ""; ?>" class="sort-link"><span class="glyphicon <?php echo $css_class["status"]; ?>  text-primary"></span></a></small></th>
					<th class="text-center">Action</th>
				</tr>
				<tr>
					<th class="text-center"></th>
					<th class="text-center"><input type="text" class="form-control input-sm min-pad" name="search_ref" size="8" value="<?php echo $keywords["ref"] ?>"></th>
					<th class="text-center"><input type="text" class="form-control input-sm min-pad" name="search_lpo" size="8" value="<?php echo $keywords["lpo"] ?>"></th>
					<th class="text-center"><input type="text" class="form-control input-sm min-pad" name="search_orderdate" size="8" value="<?php echo $keywords["ord"] ?>"></th>
					<th class="text-center"><input type="text" class="form-control input-sm min-pad" name="search_expdelivery" size="8" value="<?php echo $keywords["exd"] ?>"></th>
					<th class="text-center"><input type="text" class="form-control input-sm min-pad" name="search_client" size="8" value="<?php echo $keywords["cnn"] ?>"></th>
					<th class="text-center"><input type="text" class="form-control input-sm min-pad" name="search_jobname" size="8" value="<?php echo $keywords["job"] ?>"></th>
					<th class="text-center" width="15%">
					</th>
					<th class="text-center">
					</th>
					<th class="text-center">
						<?php

						$status_checked[0] 	= ($keywords["sts"] == 'hold') ? ' selected="selected"' : '';
						for ($i = 1; $i < 8; $i++) {
							if ($keywords["sts"] == $i) {
								$status_checked[$i]	= ' selected="selected"';
							} else {
								$status_checked[$i] 	= '';
							}
						}
						?>
						<select name="search_status" class="form-control input-sm myselect">
							<option value="">--</option>
							<option value="hold" <?php echo $status_checked[0]; ?>>Hold</option>
							<option value="1" <?php echo $status_checked[1]; ?>>Started</option>
							<option value="2" <?php echo $status_checked[2]; ?>>Printed</option>
							<option value="3" <?php echo $status_checked[3]; ?>>Laminated</option>
							<option value="4" <?php echo $status_checked[4]; ?>>Processed</option>
							<option value="5" <?php echo $status_checked[5]; ?>>Checked</option>
							<option value="6" <?php echo $status_checked[6]; ?>>Ready</option>
							<option value="7" <?php echo $status_checked[7]; ?>>Delivered</option>
						</select>
					</th>
					<th class="text-center"><button type="button" name="Search" id="btnSearch" data-url="<?php echo base_url() ?>staff/search" data-view-url="<?php echo base_url() ?>staff/index" class="btn btn-default btn-sm"><span class="glyphicon glyphicon-search"></span>&nbsp;&nbsp;Search</button></th>
				</tr>
			</thead>
			<tbody>
				<?php
				if (!$empty_order) {
					for ($i = 0; $i < count($order_id); $i++) {
				?>
						<tr>
							<td><?php echo $i + 1; ?></td>
							<td><?php echo anchor('staff/edit_order/' . $order_id[$i], $order_id[$i]) ?></td>
							<td><?php echo $lpo[$i] ?></td>
							<td><?php echo $order_date[$i] ?></td>
							<td><?php echo $order_delivery[$i] ?></td>
							<td><?php echo $order_username[$i] ?></td>
							<td><?php echo $order_name[$i] ?></td>
							<td>
								<?php echo anchor('/staff/edit_order/' . $order_id[$i], $order_filename[$i]); ?>
							</td>
							<td>
								<?php echo anchor('/staff/edit_order/' . $order_id[$i], $order_quantity[$i]); ?>
							</td>
							<td class="order_status">
								<div class="dropdown_process">
									<?php
									if ($display_option[$i]) {
									?>
										<select name="process" class="form-control myselect">
											<option name="opt_process_hold" <?php echo $order_hold[$i] ?> <?php echo isset($checked_hold[$i]) ? $checked_hold[$i] : ""; ?>>Hold</option>
											<option name="opt_process_start" <?php echo $order_started[$i] ?> <?php echo isset($checked[$i][0]) ? $checked[$i][0] : ""; ?>>Started</option>
											<option name="opt_process_print" <?php echo $order_printed[$i] ?> <?php echo isset($checked[$i][1]) ? $checked[$i][1] : ""; ?>>Printed</option>
											<option name="opt_process_laminate" <?php echo $order_laminated[$i] ?> <?php echo isset($checked[$i][2]) ? $checked[$i][2] : ""; ?>>Laminated</option>
											<option name="opt_process_process" <?php echo $order_processed[$i] ?> <?php echo isset($checked[$i][3]) ? $checked[$i][3] : ""; ?>>Processed</option>
											<option name="opt_process_check" <?php echo $order_checked[$i] ?> <?php echo isset($checked[$i][4]) ? $checked[$i][4] : ""; ?>>Checked</option>
											<option name="opt_process_ready" <?php echo $order_ready[$i] ?> <?php echo isset($checked[$i][5]) ? $checked[$i][5] : ""; ?>>Ready</option>
											<option name="opt_process_deliver" <?php echo $order_delivered[$i] ?> <?php echo isset($checked[$i][6]) ? $checked[$i][6] : ""; ?>>Delivered</option>
										</select>
									<?php
									}
									?>
								</div>
								<input type="hidden" name="url" value="<?php echo base_url(); ?>staff/update_order_process" />
								<input type="hidden" name="order_id" value="<?php echo $order_id[$i] ?>" />
								<input type="hidden" name="user_id" value="<?php echo $user_id ?>" />
							</td>
							<td>
								<?php echo $button_update[$i] ?>
								<?php echo anchor('/staff/print_pdf/' . $order_id[$i], "Print", ' class="btn btn-default btn-sm btnPrintOrder" target="new"'); ?>
							</td>
						</tr>
				<?php
					}
				}
				?>
			</tbody>
		</table>
	</div>
	<br>
	<?php echo form_close(); ?>
	<br>
	<div class="row">
		<div class=" col-xs-12 text-center page-links">
			<?php
			if ($links) {
				echo "Pages: ";
				echo $links;
			}
			?>
		</div>
	</div>
</div><!-- ./container //-->
<br>
<br>
<br>