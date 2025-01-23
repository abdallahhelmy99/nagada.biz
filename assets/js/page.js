$(function () {


	$(' .typeahead ').typeahead({
		hint: true,
		highlight: true,
		minLength: 2
	},
		{
			name: 'client_name',
			displayKey: 'value',
			source: function (query, process) {
				return $.ajax({
					url: $(' .typeahead ').attr('data-link'),
					type: 'post',
					data: { query: query },
					dataType: 'json',
					success: function (json) {
						return typeof json.options == 'undefined' ? false : process(json.options);
					}
				});
			}
		});


	$(' #btnSearch ')
		.click(function (event) {
			event.preventDefault();
			//			$(" tbody ").html('<tr><td colspan="12">Empty</td></tr>');
			var par = $(this).parent().parent();
			var p_url = $(this).attr("data-url");
			var l_url = $(this).attr("data-view-url");
			var s_ref = par.find(" input[name='search_ref'] ").val();
			var s_job = par.find(" input[name='search_jobname'] ").val();
			var s_lpo = par.find(" input[name='search_lpo'] ").val();
			var s_ord = par.find(" input[name='search_orderdate'] ").val();
			var s_exd = par.find(" input[name='search_expdelivery'] ").val();
			var s_ctt = par.find(" input[name='search_contact'] ").val();
			var s_cnn = par.find(" input[name='search_client'] ").val();
			var s_art = par.find(" select[name='search_artwork'] ").val();
			//~ var s_fnm = par.find(" input[name='search_filename'] ").val();
			//~ var s_qty = par.find(" input[name='search_quantity'] ").val();
			var s_sts = par.find(" select[name='search_status'] ").val();
			$.ajax({
				type: "post",
				url: p_url,
				data: {
					ref: s_ref,
					job: s_job,
					lpo: s_lpo,
					ord: s_ord,
					exd: s_exd,
					ctt: s_ctt,
					cnn: s_cnn,
					art: s_art,
					sts: s_sts,
				},
				dataType: "html"
			})
				.done(function (html) {
					//~ $(" tbody ").html('<tr><td colspan="12">'+ html +'</td></tr>');
					$(" tbody ").html(html);
					$(" input[type=checkbox], input[type=radio] ").iCheck({
						checkboxClass: 'icheckbox_minimal-green',
						radioClass: 'iradio_minimal-green'
					});

					$(" .page-links ").hide();

					//~ var s_query = "s-ref-"+s_ref+"/s-job-"+s_job+"/s-lpo-"+s_lpo+"/s-ord-"+s_ord+"/s-exd-"+s_exd+"/s-ctt-"+s_ctt+"/s-art-"+s_art+"/s-fnm-"+s_fnm+"/s-qty-"+s_qty+"/s-sts-"+s_sts;
					var s_query = "s-ref-" + s_ref + "/s-job-" + s_job + "/s-lpo-" + s_lpo + "/s-ord-" + s_ord + "/s-exd-" + s_exd + "/s-ctt-" + s_ctt + "/s-cnn-" + s_cnn + "/s-art-" + s_art + "/s-sts-" + s_sts;

					console.log(s_query);
					$(" .sort-link").each(function () {
						this.href = l_url + '/' + s_query;
					});
				});
		});

	$(' .loaded ').attr("title", iso8601(new Date())).timeago();
	$(" .btnRefresh ").bind("click", refreshPage).click(function (event) {
		event.preventDefault();
	});
	
	// ABDELRADY - Enter button fix
	$(" #add_row ").on("click", function(event) {
		AddRow();
		event.preventDefault();
	});
	$(" .btnDeleteRow ").bind("click", DeleteRow);
	$(" .admin_order_form ").on('keydown', function(e) {
		if(e.keyCode == 13) {
		  	e.preventDefault();
		}
	});
	$(" .client_order_form ").on('keydown', function(e) {
		if(e.keyCode == 13) {
		  	e.preventDefault();
		}
	});
	// ABDELRADY - Enter button fix

	$(" .btnArchive ").bind("click", ArchiveOrder).click(function (event) {
		event.preventDefault();
	});
	$(" .btnUnArchive ").bind("click", UnArchiveOrder).click(function (event) {
		event.preventDefault();
	});
	$(" .btnUpdateStatus ").bind("click", UpdateStatus).click(function (event) {
		event.preventDefault();
	});
	$(" .btnUpdatePayment ").bind("click", UpdatePayment).click(function (event) {
		event.preventDefault();
	});

	$(" #AddRowTemplate ").bind("click", AddRowTemplate).click(function (event) {
		event.preventDefault();
	});

	$(" #SaveTemplate ").bind("click", SaveTemplate).click(function (event) {
		event.preventDefault();
		SaveTemplate();
		console.log("SaveTemplate");
	});

	$(" #date_delivery ").datepicker();
	$(" #order_tbl ").on("change", 'input[name^="width"], input[name^="height"], input[name^="qty"]', function (event) {
		calculateSize($(this).closest("tr"));
		calculateGrandQty();
		calculateGrandSize();
		calculateCost($(this).closest("tr"));
		calculateGrandCost();
	});
	$("#order_tbl").on("change", 'input[name^="up"], input[name^="extra"]', function (event) {
		calculateCost($(this).closest("tr"));
		calculateGrandCost();
	});

	$(" input[type=checkbox], input[type=radio] ").iCheck({
		checkboxClass: 'icheckbox_minimal-green',
		radioClass: 'iradio_minimal-green'
	});

	$(" .btnAddUser")
		.click(function (event) {
			window.location.replace('add_user');
		});
	$(" .btnAddMaterial")
		.click(function (event) {
			window.location.replace('add_material');
		});
	$(" .btnAddLamination")
		.click(function (event) {
			window.location.replace('add_lamination');
		});
	$(" .btnAddQuality")
		.click(function (event) {
			window.location.replace('add_quality');
		});
	$(" .btnAddFinishing")
		.click(function (event) {
			window.location.replace('add_finishing');
		});



	$(" #client_order_form ").validate({
		highlight: function (element, errorClass, validClass) {
			$(element).parent().addClass('has-error');
		},
		unhighlight: function (element, errorClass, validClass) {
			$(element).parent().removeClass('has-error').addClass('has-success');
		}
	});

	// ABDELRADY - Admin form validation
	$(" #admin_order_form ").validate({
		highlight: function (element, errorClass, validClass) {
			$(element).parent().addClass('has-error');
		},
		unhighlight: function (element, errorClass, validClass) {
			$(element).parent().removeClass('has-error').addClass('has-success');
		}
	});
	// ABDELRADY - Admin form validation


});

function calculateSize(row) {
	var width = +row.find('input[name^="width"]').val();
	var height = +row.find('input[name^="height"]').val();
	var qty = +row.find('input[name^="qty"]').val();
	row.find('input[name^="m2"]').val((width * height * qty / 10000).toFixed(2));
}

function calculateGrandQty() {
	var grandQty = 0;
	$("#order_tbl").find('input[name^="qty"]').each(function () {
		grandQty += +$(this).val();
	});
	$("#grand_qty").text(grandQty);
}

function calculateGrandSize() {
	var grandSize = 0;
	$("#order_tbl").find('input[name^="m2"]').each(function () {
		grandSize += +$(this).val();
	});
	$("#grand_size").text(grandSize.toFixed(2));
}

function calculateCost(row) {
	var up = +row.find('input[name^="up"]').val();
	var extra = +row.find('input[name^="extra"]').val();
	var size = +row.find('input[name^="m2"]').val();
	row.find('input[name^="cost"]').val(((size * up) + extra));
}

function calculateGrandCost() {
	var grandCost = 0;
	$("#order_tbl").find('input[name^="cost"]').each(function () {
		grandCost += +$(this).val();
	});
	$("#grand_cost").text(grandCost.toFixed(2));
}


function refreshRowNumber() {
	$(' .order_row ').each(function (i) {
		$("td:first", this).html(i + 1);
		$(this).find("input[name^='filename']").attr('name', 'filename[' + i + ']');
		$(this).find("input[name^='width']").attr('name', 'width[' + i + ']');
		$(this).find("input[name^='height']").attr('name', 'height[' + i + ']');
		$(this).find("input[name^='qty']").attr('name', 'qty[' + i + ']');
		$(this).find("select[name^='material']").attr('name', 'material[' + i + ']');
		$(this).find("select[name^='lamination']").attr('name', 'lamination[' + i + ']');
		$(this).find("select[name^='quality']").attr('name', 'quality[' + i + ']');
		$(this).find("select[name^='finishing']").attr('name', 'finishing[' + i + ']');
		$(this).find("input[name^='up']").attr('name', 'up[' + i + ']');
		$(this).find("input[name^='extra']").attr('name', 'extra[' + i + ']');
		$(this).find("input[name^='notes']").attr('name', 'notes[' + i + ']');
	});
}


function AddRow() {
	var master_row = "<tr class='order_row'>" + $(" tr.master ").html() + "</tr>";
	var new_row = $.parseHTML(master_row);
	$(new_row).find(" input ").val("");
	// ABDELRADY - Default values for new row
	$(new_row).find("input[name^='up']").val("0");
	$(new_row).find("input[name^='extra']").val("0");
	// ABDELRADY - Default values for new row
	$(new_row).find(" option ").removeAttr("selected");
	$(new_row).find(" button ").removeClass("hidden");
	$("#order_tbl tbody").append(new_row);
	$(" .btnDeleteRow ").bind("click", DeleteRow);	
	calculateGrandQty();
	calculateGrandSize();
	calculateGrandCost();
	refreshRowNumber();
}


function DeleteRow() {
	var par = $(this).parent().parent(); //tr
	par.remove();
	calculateGrandQty();
	calculateGrandSize();
	calculateGrandCost();
	refreshRowNumber();
};

function UpdateStatus() {
	var par = $(this).parent().parent(); //tr
	var o_url = par.find(" input[name='url'] ").val();
	var o_id = par.find(" input[name='order_id'] ").val();
	var o_uid = par.find(" input[name='user_id'] ").val();
	var o_start = par.find(" option[name='opt_process_start'] ").is(":selected") ? '1' : '0';
	var o_print = par.find(" option[name='opt_process_print'] ").is(":selected") ? '1' : '0';
	var o_laminate = par.find(" option[name='opt_process_laminate'] ").is(":selected") ? '1' : '0';
	var o_process = par.find(" option[name='opt_process_process'] ").is(":selected") ? '1' : '0';
	var o_check = par.find(" option[name='opt_process_check'] ").is(":selected") ? '1' : '0';
	var o_ready = par.find(" option[name='opt_process_ready'] ").is(":selected") ? '1' : '0';
	var o_deliver = par.find(" option[name='opt_process_deliver'] ").is(":selected") ? '1' : '0';
	$.ajax({
		type: "post",
		url: o_url,
		data: {
			order_id: o_id,
			user_id: o_uid,
			start: o_start,
			print: o_print,
			laminate: o_laminate,
			process: o_process,
			check: o_check,
			ready: o_ready,
			deliver: o_deliver
		},
		dataType: "text"
	})
		.done(function (text) {

			switch (text) {
				case "1":
					par.find(" option[name='opt_process_hold'] ").prop("selected", false).prop("disabled", true);
					par.find(" option[name='opt_process_start'] ").prop("selected", true);
					par.find(" option[name='opt_process_print'] ").prop("disabled", false);
					break;
				case "2":
					par.find(" option[name='opt_process_start'] ").prop("selected", false).prop("disabled", true);
					par.find(" option[name='opt_process_print'] ").prop("selected", true);
					par.find(" option[name='opt_process_laminate'] ").prop("disabled", false);
					break;
				case "3":
					par.find(" option[name='opt_process_print'] ").prop("selected", false).prop("disabled", true);
					par.find(" option[name='opt_process_laminate'] ").prop("selected", true);
					par.find(" option[name='opt_process_process'] ").prop("disabled", false);
					break;
				case "4":
					par.find(" option[name='opt_process_laminate'] ").prop("selected", false).prop("disabled", true);
					par.find(" option[name='opt_process_process'] ").prop("selected", true);
					par.find(" option[name='opt_process_check'] ").prop("disabled", false);
					break;
				case "5":
					par.find(" option[name='opt_process_process'] ").prop("selected", false).prop("disabled", true);
					par.find(" option[name='opt_process_check'] ").prop("selected", true);
					par.find(" option[name='opt_process_ready'] ").prop("disabled", false);
					break;
				case "6":
					par.find(" option[name='opt_process_check'] ").prop("selected", false).prop("disabled", true);
					par.find(" option[name='opt_process_ready'] ").prop("selected", true);
					par.find(" option[name='opt_process_deliver'] ").prop("disabled", false);
					break;
				case "7":
					par.find(" option[name='opt_process_ready'] ").prop("selected", false).prop("disabled", true);
					par.find(" option[name='opt_process_deliver'] ").prop("selected", true).prop("disabled", true);
					par.find(" td[class='order_status'] ").empty();
					par.find(" .btnUpdateStatus ").hide();
					break;
			}

		});
};

function UpdatePayment() {
	var par = $(this).parent().parent(); //tr
	var o_url = par.find(" input[name='url'] ").val();
	var o_id = par.find(" input[name='order_id'] ").val();
	var o_invoiced = par.find(" input[name='cb_invoiced'] ").is(":checked") ? '1' : '0';
	var o_partial = par.find(" input[name='cb_partial'] ").is(":checked") ? '1' : '0';
	var o_full = par.find(" input[name='cb_full'] ").is(":checked") ? '1' : '0';
	$.ajax({
		type: "post",
		url: o_url,
		data: {
			order_id: o_id,
			invoiced: o_invoiced,
			partial: o_partial,
			full: o_full
		},
		dataType: "text"
	})
		.done(function (text) {
			alert("Payment updated!");
		});

};

function ArchiveOrder() {
	var par = $(this).parent().parent(); //tr
	var o_url = par.find(" input[name='archive'] ").val();
	var o_id = par.find(" input[name='order_id'] ").val();
	$.ajax({
		type: "post",
		url: o_url,
		data: {
			order_id: o_id
		},
		dataType: "text"
	})
		.done(function (text) {
			refreshPage();
			// alert("Order Archived!");
		});
};

function UnArchiveOrder() {
	var par = $(this).parent().parent(); //tr
	var o_url = par.find(" input[name='archive'] ").val();
	var o_id = par.find(" input[name='order_id'] ").val();
	$.ajax({
		type: "post",
		url: o_url,
		data: {
			order_id: o_id
		},
		dataType: "text"
	})
		.done(function (text) {
			refreshPage();
			// alert("Order Archived!");
		});
};

function refreshPage() {
	location.reload();
}


function AddRowTemplate() {
	var master_row = "<tr class='input_row'>" + $(" tr.master ").html() + "</tr>";
	var new_row = $.parseHTML(master_row);
	$(new_row).find(" input ").val("");
	$(new_row).find(" option ").removeAttr("selected");
	$(new_row).removeClass("hidden");
	//~ $(new_row).find(" button ").removeClass("hidden");
	$("#template_order tbody").append(new_row);
	//~ $(" .btnDeleteRow ").bind("click", DeleteRow);
	refreshRowNumberTemplate();
}

function refreshRowNumberTemplate() {
	$(' .input_row:not(:hidden) ').each(function (i) {
		$(" td:first ", this).html(i + 1);
		$(this).find("input[name^='filename']").attr('name', 'filename[' + i + ']');
		$(this).find("input[name^='width']").attr('name', 'width[' + i + ']');
		$(this).find("input[name^='height']").attr('name', 'height[' + i + ']');
		$(this).find("input[name^='quantity']").attr('name', 'quantity[' + i + ']');
		$(this).find("select[name^='material']").attr('name', 'material[' + i + ']');
		$(this).find("select[name^='lamination']").attr('name', 'lamination[' + i + ']');
		$(this).find("select[name^='quality']").attr('name', 'quality[' + i + ']');
		$(this).find("select[name^='finishing']").attr('name', 'finishing[' + i + ']');
		$(this).find("select[name^='username']").attr('name', 'username[' + i + ']');
		//~ $(this).find("input[name^='up']").attr('name', 'up['+i+']');
		//~ $(this).find("input[name^='extra']").attr('name', 'extra['+i+']');
		//~ $(this).find("input[name^='notes']").attr('name', 'notes['+i+']');
	});
}

function SaveTemplate() {
	var template = [];
	$(' .input_row:not(:hidden) ').each(function (i) {

		template.push({
			"filename": $(this).find("input[name^='filename']").val(),
			"width": $(this).find("input[name^='width']").val(),
			"height": $(this).find("input[name^='height']").val(),
			"quantity": $(this).find("input[name^='quantity']").val(),
			"material": $(this).find("select[name^='material']").val(),
			"lamination": $(this).find("select[name^='lamination']").val(),
			"quality": $(this).find("select[name^='quality']").val(),
			"finishing": $(this).find("select[name^='finishing']").val(),
			"username": $(this).find("select[name^='username'] option:selected").text(),
		});

		$(this).find("input").prop('disabled', true);
		$(this).find("select").prop('disabled', true);

	});
	$(' input[name="post_data"] ').val(JSON.stringify(template));
	console.log(template);
	$(' #admin_template_form ').submit();
}

function changeUserTemplate(){
	var template = [];
	$(' .input_row:not(:hidden) ').each(function (i) {

		template.push({
			"filename": $(this).find("input[name^='filename']").val(),
			"width": $(this).find("input[name^='width']").val(),
			"height": $(this).find("input[name^='height']").val(),
			"quantity": $(this).find("input[name^='quantity']").val(),
			"material": $(this).find("select[name^='material']").val(),
			"lamination": $(this).find("select[name^='lamination']").val(),
			"quality": $(this).find("select[name^='quality']").val(),
			"finishing": $(this).find("select[name^='finishing']").val(),
			"username": $(this).find("select[name^='username'] option:selected").text(),
		});

		$(this).find("input").prop('disabled', true);
		$(this).find("select").prop('disabled', true);

	});
	$(' input[name="post_data"] ').val(JSON.stringify(template));
	console.log(template);
	$(' #admin_template_form ').submit();
}
