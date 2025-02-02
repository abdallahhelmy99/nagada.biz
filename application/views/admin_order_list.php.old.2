<?php


$pagestring = "p-1";
$segs = $this->uri->segment_array();

$sort["ref"]		= "sort-ref-up";
$sort["jobname"]	= "sort-jobname-up";
$sort["lpo"]		= "sort-lpo-up";
$sort["orderdate"]	= "sort-orderdate-up";
$sort["expdelivery"]	= "sort-expdelivery-up";
$sort["contact"]	= "sort-contact-up";
$sort["clientname"]	= "sort-clientname-up";
$sort["artwork"]	= "sort-artwork-up";
$sort["status"]		= "sort-status-up";
$css_class["ref"]		= "glyphicon-sort";
$css_class["jobname"]	= "glyphicon-sort";
$css_class["lpo"]		= "glyphicon-sort";
$css_class["orderdate"]	= "glyphicon-sort";
$css_class["expdelivery"]	= "glyphicon-sort";
$css_class["contact"]	= "glyphicon-sort";
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
$keywords["stt"]	= "";
$keywords["sts"]	= "";


foreach ($segs as $segment)
{
	$parameter = explode("-",$segment);
	{
		if (count($parameter) > 1)
		{
			switch ($parameter[0])
			{
				case "p":
					$pagenum = (int) $parameter[1];
					$pagestring = "p-".$pagenum;
					break;
				case "sort":
					if (array_key_exists($parameter[1], $sort))
					{
						$sortindex = $parameter[1];
						$sorttype = (strtolower($parameter[2]) == "up") ? "down" : "up";
						$sort[$sortindex] 	= "sort-".$sortindex."-".$sorttype;
						$css_class[$sortindex] 	= "glyphicon glyphicon-chevron-".$sorttype;
					}
					break;
				// Search keywords	
				case "s":
					if (!empty($parameter[2]))
					{
						$keywords[$parameter[1]]				= $parameter[2];
					}
					break;
			}
		}
	}
}

if (isset($keywords))
{
	foreach ($keywords as $k => $v)
	{
		if (!empty($v))
		{
			$arr_keywords[] = "s-".$k."-".$v;
		}
	}
	if (isset($arr_keywords))
	{
		$str_keywords = implode("/",$arr_keywords);
	}
	else
	{
		$str_keywords = "";
	}
}
?>



<div class="container">
	<h1><span class="glyphicon glyphicon-list"></span>&nbsp;&nbsp;<?php echo $heading ?></h1>

	<div class="table-responsive">
	<table id="order_tbl" width="100%" class="table-striped table-bordered table-condensed table-hover">
	<thead>
	<tr>
		<th class="text-center">No.&nbsp;</th>
		<th class="text-center">Ref #&nbsp;<small><a href="<?php echo base_url()."admin/index/".$sort["ref"]."/".$pagestring ."/". $str_keywords ."";?>" class="sort-link"><span class="glyphicon <?php echo $css_class["ref"]; ?> text-primary"></span></a></small></th>
		<th class="text-center">Order Date&nbsp;<small><a href="<?php echo base_url()."admin/index/".$sort["orderdate"]."/".$pagestring ."/". $str_keywords ."";?>" class="sort-link"><span class="glyphicon <?php echo $css_class["orderdate"]; ?>  text-primary"></span></a></small></th>
		<th class="text-center">Req. Delivery Date&nbsp;<small><a href="<?php echo base_url()."admin/index/".$sort["expdelivery"]."/".$pagestring ."/". $str_keywords ."";?>" class="sort-link"><span class="glyphicon <?php echo $css_class["expdelivery"]; ?>  text-primary"></span></a></small></th>
		<th class="text-center">Client Name&nbsp;<small><a href="<?php echo base_url()."admin/index/".$sort["clientname"]."/".$pagestring ."/". $str_keywords ."";?>" class="sort-link"><span class="glyphicon <?php echo $css_class["clientname"]; ?>  text-primary"></span></a></small></th>
		<th class="text-center">Contact Name&nbsp;<small><a href="<?php echo base_url()."admin/index/".$sort["contact"]."/".$pagestring ."/". $str_keywords ."";?>" class="sort-link"><span class="glyphicon <?php echo $css_class["contact"]; ?>  text-primary"></span></a></small></th>
		<th class="text-center">Total Cost&nbsp;</th>
		<th class="text-center">Process Status&nbsp;<small><a href="<?php echo base_url()."admin/index/".$sort["status"]."/".$pagestring ."/". $str_keywords ."";?>" class="sort-link"><span class="glyphicon <?php echo $css_class["status"]; ?>  text-primary"></span></a></small></th>
		<th class="text-center" colspan="3">Payment Status&nbsp;</th>
		<th class="text-center">Action</th>
	</tr>
	<tr>
		<th class="text-center"></th>
		<th class="text-center"><input type="text" class="form-control input-sm min-pad" name="search_ref" size="8" value="<?php echo $keywords["ref"] ?>"></th>
		<th class="text-center"><input type="text" class="form-control input-sm min-pad" name="search_orderdate" size="8" value="<?php echo $keywords["ord"] ?>"></th>
		<th class="text-center"><input type="text" class="form-control input-sm min-pad" name="search_expdelivery" size="8" value="<?php echo $keywords["exd"] ?>"></th>
		<th class="text-center"><input type="text" class="form-control input-sm min-pad" name="search_client" size="8" value="<?php echo $keywords["cnn"] ?>"></th>
		<th class="text-center"><input type="text" class="form-control input-sm min-pad" name="search_contact" size="8" value="<?php echo $keywords["ctt"] ?>"></th>
		<th class="text-center"></th>
		<th class="text-center">
				<?php
				
				$status_checked[0] 	= ($keywords["sts"] == 'hold') ? ' selected="selected"' : '';
				for ($i=1; $i< 8; $i++)
				{
					if ($keywords["sts"] == $i)
					{
						$status_checked[$i]	= ' selected="selected"';
					}
					else
					{
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
		<th class="text-center" colspan="3">
		</th>
		<th class="text-center"><button type="button" name="Search" id="btnSearch" data-url="<?php echo base_url()?>admin/search" data-view-url="<?php echo base_url()?>admin/index" class="btn btn-default btn-sm"><span class="glyphicon glyphicon-search"></span>&nbsp;&nbsp;Search</button></th>
	</tr>
	</thead>
	<tbody>
	<?php
	if (!$empty_order)
	{
		for ($i= 0; $i< count($order_id); $i++)
		{
	?>
	<tr>
		<td><?php echo $row_number[$i]; ?></td>
		<td><?php echo $order_id[$i] ?></td>
		<td><?php echo $order_date[$i] ?></td>
		<td><?php echo $order_delivery[$i] ?></td>
		<td><?php echo $order_username[$i] ?></td>
		<td><?php echo $order_contact[$i] ?></td>
		<td><?php echo $order_cost[$i] ?></td>
		<td>
		<?php echo anchor('admin/order_detail/'.$order_id[$i], $order_status[$i]); ?>
		</a>
		</td>
		<td class="order_status">
		<div class="checkbox mycheckbox">
			<label><input type="checkbox" name="cb_invoiced" <?php echo $payment_invoiced[$i] ?>>Invoiced</label>
		</div>
		</td>
		<td>
		<div class="checkbox mycheckbox">
			<label><input type="checkbox" name="cb_partial" <?php echo $payment_partial[$i] ?>>P.P.</label>
		</div>
		</td>
		<td>
		<div class="checkbox mycheckbox">
			<label><input type="checkbox" name="cb_full" <?php echo $payment_full[$i] ?>>F.P.</label>
		</div>
		</td>
		<td>
		<input type="hidden" name="order_id" value="<?php echo $order_id[$i] ?>">
		<input type="hidden" name="url" value="<?php echo base_url(); ?>admin/update_order_payment" />
		<input type="hidden" name="archive" value="<?php echo base_url(); ?>admin/archive_order" />
		<button class="btn btn-default btn-sm btnUpdatePayment">Update</button>
		<?php echo anchor('/admin/print_pdf/'. $order_id[$i], 'Print', 'role="button" class="lnkPrint btn btn-default btn-sm" target="new"'); ?>
		<button class="btn btn-default btn-sm btnArchive">Archive</button>
		<!--
		/-->
		</td>
	</tr>
	<?php
		}
	}
	else
	{
	?>
	<tr>
		<td colspan="13" align="center">The order is currently empty.</td>
	</tr>
	<?php
	}
	?>

	</tbody>
	</table>
	</div>
	<br>
	<div class="row">
		<div class=" col-xs-12 text-center page-links">
		<?php
		if ($links)
		{
			echo "Pages: ";
			echo $links;
		}
		?>
		</div>
	</div>
</div><!-- ./container -->
