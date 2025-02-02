<?php


$pagestring = "p-1";
$segs = $this->uri->segment_array();

$sort["ref"]		= "sort-ref-up";
$sort["jobname"]	= "sort-jobname-up";
$sort["lpo"]		= "sort-lpo-up";
$sort["orderdate"]	= "sort-orderdate-up";
$sort["expdelivery"]	= "sort-expdelivery-up";
$sort["contact"]	= "sort-contact-up";
$sort["artwork"]	= "sort-artwork-up";
$sort["status"]		= "sort-status-up";
$css_class["ref"]		= "glyphicon-sort";
$css_class["jobname"]	= "glyphicon-sort";
$css_class["lpo"]		= "glyphicon-sort";
$css_class["orderdate"]	= "glyphicon-sort";
$css_class["expdelivery"]	= "glyphicon-sort";
$css_class["contact"]	= "glyphicon-sort";
$css_class["artwork"]	= "glyphicon-sort";
$css_class["status"]		= "glyphicon-sort";

$keywords["ref"]	= "";
$keywords["job"]	= "";
$keywords["lpo"]	= "";
$keywords["ord"]	= "";
$keywords["exd"]	= "";
$keywords["ctt"]	= "";
$keywords["art"]	= "";
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
	<?php
	if (!empty($message))
	{
	?>
	<div class="alert alert-success alert-dismissable"><button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button><?php echo $message;?></div>
	<?php
	}
	?>
	<h1><span class="glyphicon glyphicon-list"></span>&nbsp;&nbsp;My Order List</h1>

	<div class="table-responsive">
	<?php echo form_open(uri_string()."/list_sort", 'role="form" class="form-horizontal" id="client_order_form"');?>
	<table width="100%" id="order_tbl" class="table-bordered table-condensed table-striped table-hover">
	<thead>
	<tr>
		<th class="text-center">No.&nbsp;</th>
		<th class="text-center">Ref #&nbsp;<small><a href="<?php echo base_url()."client/index/".$sort["ref"]."/".$pagestring ."/". $str_keywords ."";?>" class="sort-link"><span class="glyphicon <?php echo $css_class["ref"]; ?> text-primary"></span></a></small></th>
		<th class="text-center">Job Name&nbsp;<small><a href="<?php echo base_url()."client/index/".$sort["jobname"]."/".$pagestring ."/". $str_keywords ."";?>" class="sort-link"><span class="glyphicon <?php echo $css_class["jobname"]; ?>  text-primary"></span></a></small></th>
		<th class="text-center">LPO #&nbsp;<small><a href="<?php echo base_url()."client/index/".$sort["lpo"]."/".$pagestring ."/". $str_keywords ."";?>" class="sort-link"><span class="glyphicon <?php echo $css_class["lpo"]; ?>  text-primary"></span></a></small></th>
		<th class="text-center">Order Date&nbsp;<small><a href="<?php echo base_url()."client/index/".$sort["orderdate"]."/".$pagestring ."/". $str_keywords ."";?>" class="sort-link"><span class="glyphicon <?php echo $css_class["orderdate"]; ?>  text-primary"></span></a></small></th>
		<th class="text-center">Exp. Delivery Date&nbsp;<small><a href="<?php echo base_url()."client/index/".$sort["expdelivery"]."/".$pagestring ."/". $str_keywords ."";?>" class="sort-link"><span class="glyphicon <?php echo $css_class["expdelivery"]; ?>  text-primary"></span></a></small></th>
		<th class="text-center">Contact Name&nbsp;<small><a href="<?php echo base_url()."client/index/".$sort["contact"]."/".$pagestring ."/". $str_keywords ."";?>" class="sort-link"><span class="glyphicon <?php echo $css_class["contact"]; ?>  text-primary"></span></a></small></th>
		<th class="text-center">Artwork By&nbsp;<small><a href="<?php echo base_url()."client/index/".$sort["artwork"]."/".$pagestring ."/". $str_keywords ."";?>" class="sort-link"><span class="glyphicon <?php echo $css_class["artwork"]; ?>  text-primary"></span></a></small></th>
		<th class="text-center" width="15%">Filename&nbsp;</th>
		<th class="text-center">Quantity&nbsp;</th>
		<th class="text-center">Status&nbsp;<small><a href="<?php echo base_url()."client/index/".$sort["status"]."/".$pagestring ."/". $str_keywords ."";?>" class="sort-link"><span class="glyphicon <?php echo $css_class["status"]; ?>  text-primary"></span></a></small></th>
		<th class="text-center">Action</th>
	</tr>
	<tr>
		<th class="text-center"></th>
		<th class="text-center"><input type="text" class="form-control input-sm min-pad" name="search_ref" size="8" value="<?php echo $keywords["ref"] ?>"></th>
		<th class="text-center"><input type="text" class="form-control input-sm min-pad" name="search_jobname" size="8" value="<?php echo $keywords["job"] ?>"></th>
		<th class="text-center"><input type="text" class="form-control input-sm min-pad" name="search_lpo" size="8" value="<?php echo $keywords["lpo"] ?>"></th>
		<th class="text-center"><input type="text" class="form-control input-sm min-pad" name="search_orderdate" size="8" value="<?php echo $keywords["ord"] ?>"></th>
		<th class="text-center"><input type="text" class="form-control input-sm min-pad" name="search_expdelivery" size="8" value="<?php echo $keywords["exd"] ?>"></th>
		<th class="text-center"><input type="text" class="form-control input-sm min-pad" name="search_contact" size="8" value="<?php echo $keywords["ctt"] ?>"></th>
		<th class="text-center">

				<?php
				for ($i=0; $i< 8; $i++)
				{
					if ($keywords["art"] == $i)
					{
						$art_checked[$i]	= ' selected="selected"';
					}
					else
					{
						$art_checked[$i] 	= '';
					}
				}
				?>
			<select name="search_artwork" class="form-control input-sm myselect">
			<option value="">--</option>
			<option value="1" <?php echo $art_checked[1]; ?>>Mail</option>
			<option value="2" <?php echo $art_checked[2]; ?>>Drop Box</option>
			<option value="3" <?php echo $art_checked[3]; ?>>We Transfer</option>
			<option value="4" <?php echo $art_checked[4]; ?>>DVD</option>
			<option value="5" <?php echo $art_checked[5]; ?>>FTP</option>
			<option value="6" <?php echo $art_checked[6]; ?>>USB</option>
			<option value="7" <?php echo $art_checked[7]; ?>>Other</option>
			</select>
				
		</th>
		<th class="text-center" width="15%">
			<!--
			<input type="search_text" name="search_filename" size="18">
			/-->
			</th>
		<th class="text-center">
			<!--
			<input type="text" name="search_quantity" size="8">
			/-->
			</th>
		<th class="text-center">

				<?php
				for ($i=0; $i< 8; $i++)
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
			<option value="hold">Hold</option>
			<option value="1" <?php echo $status_checked[1]; ?>>Started</option>
			<option value="2" <?php echo $status_checked[2]; ?>>Printed</option>
			<option value="3" <?php echo $status_checked[3]; ?>>Laminated</option>
			<option value="4" <?php echo $status_checked[4]; ?>>Processed</option>
			<option value="5" <?php echo $status_checked[5]; ?>>Checked</option>
			<option value="6" <?php echo $status_checked[6]; ?>>Ready</option>
			<option value="7" <?php echo $status_checked[7]; ?>>Delivered</option>
			</select>
			</th>
		<th class="text-center"><button type="button" name="Search" id="btnSearch" data-url="<?php echo base_url()?>client/search" data-view-url="<?php echo base_url()?>client/index" class="btn btn-default btn-sm"><span class="glyphicon glyphicon-search"></span>&nbsp;&nbsp;Search</button></th>
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
		<td><?php echo $order_name[$i] ?></td>
		<td><?php echo $lpo[$i] ?></td>
		<td><?php echo $order_date[$i] ?></td>
		<td><?php echo $order_delivery[$i] ?></td>
		<td><?php echo $order_contact[$i] ?></td>
		<td><?php echo $artwork_by[$i] ?></td>
		<td><?php echo $order_filename[$i] ?></td>
		<td><?php echo $order_quantity[$i] ?></td>
		<td><?php echo $order_status[$i] ?></td>
		<td width="148">
		<?php 
		if ($order_status[$i] == "Hold")
		{
			echo anchor('/client/order_detail/'. $order_id[$i], 'Edit', 'role="button" class="lnkEditOrder btn btn-default btn-sm"'); 
			echo "&nbsp;";
			echo anchor('/client/delete_order/'. $order_id[$i], 'Delete', 'role="button" class="lnkDelete btn btn-default btn-sm"'); 
			echo "&nbsp;";
			echo anchor('/client/print_pdf/'. $order_id[$i], 'Print', 'role="button" class="lnkPrint btn btn-default btn-sm" target="new"');
		}
		else
		{
			echo anchor('/client/order_detail/'. $order_id[$i], 'Detail', 'role="button" class="lnkDetail btn btn-default btn-sm"');
			echo "&nbsp;";
			echo anchor('/client/print_pdf/'. $order_id[$i], 'Print', 'role="button" class="lnkPrint btn btn-default btn-sm" target="new"');
		}
		?>
		</td>
	</tr>
	<?php
		}
	}
	else
	{
	?>
	<tr>
		<td colspan="12" class="text-center">You have not created any order yet.<br>
		<?php echo anchor('/client/order_form/', 'Create Order', 'class="btn btn-sm btn-default"'); ?>
		</td>
	</tr>
	<?php
	}
	?>

	</tbody>
	</table>
	<?php echo form_close();?>
	</div>

	<br>
	<div class="row">
		<div class="col-xs-12 text-center page-links">
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
