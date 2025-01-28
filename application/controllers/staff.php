<?php if (! defined('BASEPATH')) exit('No direct script access allowed');

class Staff extends CI_Controller
{
	function __construct()
	{
		parent::__construct();
		$this->load->library('ion_auth');
		$this->load->library('form_validation');
		$this->load->library('pagination');
		$this->load->helper('url');
		$this->load->helper('language');
		$this->lang->load('client');
		$this->load->model('order_model');

		$this->load->database();

		$this->form_validation->set_error_delimiters($this->config->item('error_start_delimiter', 'ion_auth'), $this->config->item('error_end_delimiter', 'ion_auth'));

		if (!$this->ion_auth->in_group(2)) {
			redirect('auth/login', 'refresh');
		}
	}

	function index()
	{

		$valid_sort_index 	= array("ref", "jobname", "lpo", "orderdate", "expdelivery", "clientname", "status");

		$pages['base_url'] 		= base_url("staff/index");
		$pages['per_page'] 		= $per_page	= 20;
		$pageExtra = $this->applyFilter($this->uri->segment_array(), "staff/index", $per_page, $valid_sort_index);

		$pages["base_url"] = $pageExtra["base_url"];
		$pages["total_rows"] = $this->order_model->count_all_order("", $pageExtra["keywords"]);
		$pages["pagenum"] = $pageExtra["page_num"];

		$page				= array($pageExtra["page_num"], $per_page, $pageExtra["sortmode"]);
		$result				= $this->order_model->get_all_order("", $page, $pageExtra["keywords"]);

		$data['current_page'][0]	= "class='active'";
		$data['current_page'][1]	= "";
		$data['current_page'][2]	= "";
		$data['current_page'][3]	= "";
		$data['page_title']	= "Job Order List";
		$data['heading']	= "Production List";
		$data["links"]		= FALSE;
		if (count($result)) {
			$data['empty_order']		= FALSE;
			$this->pagination->initialize($pages);
			//~ $data["links"]		= $this->pagination->create_links();
			$data["links"]		= $this->create_pagination($pages);
			for ($i = 0; $i < count($result); $i++) {
				$data_row					= $result[$i];
				$data['row_number'][]		= $pageExtra["page_num"] + $i + 1;
				$data['order_id'][]			= $data_row->id_order;
				$data['order_date'][]		= date("d/m/Y H:i:s", $data_row->order_date);
				$data['order_delivery'][]	= date("d/m/Y", $data_row->req_delivery_date);
				$data['order_username'][]	= $data_row->username;
				$data['order_contact'][]	= $data_row->contact_name;
				$data['order_name'][]		= $data_row->order_name;
				$data['lpo'][]				= $data_row->lpo;
				$data['order_filename'][]	= implode(", ", $this->order_model->get_order_filename($data_row->id_order));
				$data['order_quantity'][]	= implode(", ", $this->order_model->get_order_quantity($data_row->id_order));
				$order_status				= $this->order_model->get_order_status($data_row->id_order, "array");
				$data["stat"][]				= $order_status;
				$key_current				= array_search("0", $order_status);
				$tail_structure				= array_slice($order_status, $key_current);
				$head_structure				= array_slice($order_status, 0, $key_current);
				$insert_zero				= array(0);
				$new_structure				= array_merge($head_structure, $insert_zero, $tail_structure);
				$data["new_stuct"][]		= $new_structure;
				$key_current				= array_search("0", $new_structure);
				$data['order_hold'][]		= ($new_structure[0]) ? " disabled='disabled'" : "";
				$data['order_started'][]	= ($new_structure[1]) ? " disabled='disabled'" : "";
				$data['order_printed'][]	= ($new_structure[2]) ? " disabled='disabled'" : "";
				$data['order_laminated'][]	= ($new_structure[3]) ? " disabled='disabled'" : "";
				$data['order_processed'][]	= ($new_structure[4]) ? " disabled='disabled'" : "";
				$data['order_checked'][]	= ($new_structure[5]) ? " disabled='disabled'" : "";
				$data['order_ready'][]		= ($new_structure[6]) ? " disabled='disabled'" : "";
				$data['order_delivered'][]	= ($new_structure[7]) ? " disabled='disabled'" : "";
				if ($key_current) {
					for ($j = 0; $j < count($new_structure); $j++) {
						$data['checked'][$i][$j] = "";
					}
					$data['checked'][$i][$key_current - 1] = " selected='selected'";
					$data['checked_hold'][$i] = " selected='selected'";
					$data['button_update'][]	= " <button class=\"btn btn-default btn-sm btnUpdateStatus\">Update</button>";
					$data["display_option"][]	= TRUE;
				} else if ($order_status[0]) {
					$data['button_update'][]	= "";
					$data["display_option"][]	= FALSE;
				} else {
					$data['button_update'][]	= " <button class=\"btn btn-default btn-sm btnUpdateStatus\">Update</button>";
					$data["display_option"][]	= TRUE;
				}
			}
		} else {
			$data['empty_order']		= TRUE;
		}


		$data['user_id']	= $this->ion_auth->get_user_id();
		$data['page_title']	= "Job Order List";
		$data['heading']	= "Job Order List";
		$this->load->view('header', $data);
		$this->load->view('staff_nav');
		$data['message'] 	= ($this->session->flashdata('message') ? $this->session->flashdata('message') : '');
		$this->load->view('staff_order_list', $data);
		$this->load->view('footer');
	}

	function update_order_process()
	{
		$order_id 					= $this->input->post("order_id");
		$user_id 					= $this->input->post("user_id");
		$process[0]					= array("started_by" 	=> $this->input->post("start"));
		$process[1]					= array("printed_by" 	=> $this->input->post("print"));
		$process[2]					= array("laminated_by" 	=> $this->input->post("laminate"));
		$process[3]					= array("processed_by" 	=> $this->input->post("process"));
		$process[4]					= array("checked_by" 	=> $this->input->post("check"));
		$process[5]					= array("ready_by" 		=> $this->input->post("ready"));
		$process[6]					= array("delivered_by"	=> $this->input->post("deliver"));

		$order_status				= $this->order_model->get_order_status($order_id, "array");
		$key_current				= array_search("0", $order_status);

		foreach ($process[$key_current] as $key => $value) {

			if ($value == 1) {
				$new_input			= array($key => $user_id);
				$this->order_model->update_order($order_id, $new_input);
				$data['the_case']	= $key_current + 1;

				$this->load->view('ajax_update_order', $data);
			}
		}
	}

	function edit_order($order_id = "")
	{
		if ($order_id) {
			$result	= $this->order_model->get_order_list($order_id);
			if ($result) {
				if ($this->input->post("submitted") == "1" && $this->order_model->update_order_staff($order_id))
				// if ($this->form_validation->run() == TRUE)
				{
					$this->session->set_flashdata('message', 'Order successfully updated');
					redirect("staff", 'refresh');
				} else {
					$data_row				= $result[0];

					$data['order_id']		= $data_row->id_order;
					$data['order_date']		= date("d/m/Y", $data_row->order_date);
					$data['order_delivery']	= date("d/m/Y", $data_row->req_delivery_date);
					$data['order_name']		= $data_row->order_name;
					$data['lpo']			= $data_row->lpo;
					$data['order_contact']	= $data_row->contact_name;
					$data['order_email']	= $data_row->contact_email;
					$data['order_mobile']	= $data_row->contact_mobile;
					$data['order_status']	= $this->order_model->get_order_status($data_row->id_order);

					$material			= $this->order_model->get_material_list();
					$lamination			= $this->order_model->get_lamination_list();
					$quality			= $this->order_model->get_quality_list();
					$finishing			= $this->order_model->get_finishing_list();

					$result	= $this->order_model->get_order_detail($order_id);
					$data['grand_qty']		= 0;
					$data['grand_size']		= 0;
					$data['grand_cost']		= 0;
					if (count($result)) {
						$data['empty_order']		= FALSE;
						for ($i = 0; $i < count($result); $i++) {
							$data_row				= $result[$i];
							$data['filename'][]		= $data_row->detail_filename;
							$data['width'][]		= $data_row->detail_width;
							$data['height'][]		= $data_row->detail_height;
							$data['qty'][]			= $data_row->detail_quantity;
							$m2						= $data_row->detail_width *
								$data_row->detail_height *
								$data_row->detail_quantity / 10000;
							$data['m2'][]			= round($m2, 2);
							$data['material'][]		= $material[$data_row->detail_material];
							$data['lamination'][]	= $lamination[$data_row->detail_lamination];
							$data['quality'][]		= $quality[$data_row->detail_quality];
							$data['finishing'][]	= $finishing[$data_row->detail_finishing];
							$data['up'][]			= $data_row->detail_up;
							$data['extra'][]		= (float) $data_row->detail_extra;
							$data['notes'][]		= $data_row->detail_notes;
							$data['grand_qty']		= $data['grand_qty'] + $data_row->detail_quantity;
							$data['grand_size']		= $data['grand_size'] + $m2;
							$total					= ($data_row->detail_up * $m2) + $data_row->detail_extra;
							$data['total'][]		= round(($data_row->detail_up * $m2) + $data_row->detail_extra, 2);
							$data['grand_cost']		= $data['grand_cost'] + $total;
							$id_detail[]			= $data_row->detail_id;
						}
						$data['grand_size']			= round($data['grand_size'], 2);
						$data['grand_cost']			= round($data['grand_cost'], 2);
					}
					$data["detail_id"]				= implode("-", $id_detail);
				}
			} else {
				$data['empty_order']		= TRUE;
			}
		} else {
			$data['empty_order']		= TRUE;
		}

		$data['page_title']	= "Edit Job Order";
		$data['heading']	= "Edit Job Order";
		$data['current_page'][0]	= "class='active'";
		$data['current_page'][1]	= "";
		$data['current_page'][2]	= "";
		$data['current_page'][3]	= "";
		$this->load->view('header', $data);
		$this->load->view('staff_nav');
		$this->load->view('staff_order_detail', $data);
		$this->load->view('footer');
	}

	function order_form()
	{
		// Validate form input
		$this->form_validation->set_rules('contact_name', $this->lang->line('create_order_contact_name_error'), 'required|xss_clean');

		if ($this->form_validation->run() == TRUE && $this->order_model->create_order())
		//if ($this->form_validation->run() == TRUE)
		{
			$this->session->set_flashdata('message', 'Order successfully created');
			redirect("staff", 'refresh');
			//print_r($_POST);
		} else {
			$this->data['message'] = (validation_errors() ? validation_errors() : '');
			$this->data['order_name'] = array(
				'name'  => 'order_name',
				'id'    => 'order_name',
				'type'  => 'text',
				'value' => $this->form_validation->set_value('order_name'),
			);
			$this->data['contact_name'] = array(
				'name'  => 'contact_name',
				'id'    => 'contact_name',
				'type'  => 'text',
				'value' => $this->form_validation->set_value('contact_name'),
			);
			$this->data['contact_email'] = array(
				'name'  => 'contact_email',
				'id'    => 'contact_email',
				'type'  => 'text',
				'value' => '',
				// 'value' => $this->form_validation->set_value('contact_email'),
			);
			$this->data['contact_mobile'] = array(
				'name'  => 'contact_mobile',
				'id'    => 'contact_mobile',
				'type'  => 'text',
				'value' => '',
				// 'value' => $this->form_validation->set_value('contact_mobile'),
			);
			$this->data['date_delivery'] = array(
				'name'  => 'date_delivery',
				'id'    => 'date_delivery',
				'type'  => 'text',
				'value' => $this->form_validation->set_value('date_delivery'),
			);
			$data['options_material']	= $this->order_model->get_material_list();
			$data['options_lamination']	= $this->order_model->get_lamination_list();
			$data['options_quality']	= $this->order_model->get_quality_list();
			$data['options_finishing']	= $this->order_model->get_finishing_list();
			$data['current_page'][0]	= "";
			$data['current_page'][1]	= "class='active'";
			$data['current_page'][2]	= "";
			$data['current_page'][3]	= "";
			$data['page_title']	= "Create Common Job Order";
			$data['heading']	= "Create Common Job Order";
			$this->load->view('header', $data);
			$this->load->view('staff_nav');
			$this->_render_page('staff_order_form', $this->data);
			$this->load->view('footer');
		}
	}

	function ajax_find_client()
	{
		$input_query = $this->input->post('query');
		$query_result = $this->order_model->get_client_list($input_query);
		//print_r($query_result);

		foreach ($query_result as $row) {
			$options_array[] = array('key' => (int) $row->user_id, 'value' => $row->username);
		}
		$array_result = array('options' => $options_array);


		/*
		$array_result = array('options' => array(array('key' => 1, 'value'=> 'asdf'),
													array('key' => 2, 'value'=> 'asdq'),
													array('key' => 3, 'value'=> 'asde'),
													array('key' => 4, 'value'=> 'asdx')));

		*/

		$return_json = json_encode($array_result);
		//		print_r($array_result);
		echo $return_json;
	}

	function print_pdf($order_id)
	{
		$this->load->helper('pdf_helper');

		$order_id = (int) $order_id;

		$artwork[1]			= "Mail";
		$artwork[2]			= "Drop Box";
		$artwork[3]			= "We Transfer";
		$artwork[4]			= "DVD";
		$artwork[5]			= "FTP";
		$artwork[6]			= "USB";
		$artwork[7]			= "Other";

		if ($this->order_model->get_order_list($order_id)) {
			$result	= $this->order_model->get_order_list($order_id);
			$data_row				= $result[0];
			$data['order_id']		= $order_id;
			$data['order_date']		= date("d/m/Y", $data_row->order_date);
			$data['order_delivery']	= date("d/m/Y", $data_row->req_delivery_date);
			$data['order_username']	= $data_row->username;
			$data['order_name']		= $data_row->order_name;
			$data['lpo']			= $data_row->lpo;
			$data['order_contact']	= $data_row->contact_name;
			$data['order_email']	= $data_row->contact_email;
			$data['order_mobile']	= $data_row->contact_mobile;
			$data['artwork_by']		= $artwork[$data_row->artwork_by];

			$result	= $this->order_model->get_order_detail($order_id);
			$data['grand_qty']		= 0;
			$data['grand_size']		= 0;
			$data['grand_cost']		= 0;
			if (count($result)) {
				$material			= $this->order_model->get_material_list();
				$lamination			= $this->order_model->get_lamination_list();
				$quality			= $this->order_model->get_quality_list();
				$finishing			= $this->order_model->get_finishing_list();
				for ($i = 0; $i < count($result); $i++) {
					$data_row				= $result[$i];
					$data['filename'][]		= $data_row->detail_filename;
					$data['width'][]		= $data_row->detail_width;
					$data['height'][]		= $data_row->detail_height;
					$data['qty'][]			= $data_row->detail_quantity;
					$m2						= $data_row->detail_width *
						$data_row->detail_height *
						$data_row->detail_quantity / 10000;
					$data['m2'][]			= round($m2, 2);
					$data['material'][]		= $material[$data_row->detail_material];
					$data['lamination'][]	= $lamination[$data_row->detail_lamination];
					$data['quality'][]		= $quality[$data_row->detail_quality];
					$data['finishing'][]	= $finishing[$data_row->detail_finishing];
					$data['up'][]			= $data_row->detail_up;
					$data['extra'][]		= (float) $data_row->detail_extra;
					$data['total'][]		= ($m2 * $data_row->detail_up) + $data_row->detail_extra;
					$total					= ($m2 * $data_row->detail_up) + $data_row->detail_extra;
					$data['notes'][]		= $data_row->detail_notes;
					$data['grand_qty']		= $data['grand_qty'] + $data_row->detail_quantity;
					$data['grand_size']		= $data['grand_size'] + $m2;
					$data['grand_cost']		= $data['grand_cost'] + $total;
				}
				$data['grand_size']			= round($data['grand_size'], 2);

				// Sort by material
				// array_multisort($data['material'], SORT_DESC, $data['filename'], $data['width'], $data['height'], $data['qty'], $data['m2'], $data['lamination'], $data['quality'], $data['finishing'], $data['up'], $data['extra'], $data['total'], $data['notes']);
				array_multisort($data['material'], SORT_ASC, $data['m2'], SORT_DESC, $data['filename'], $data['width'], $data['height'], $data['qty'], $data['lamination'], $data['quality'], $data['finishing'], $data['up'], $data['extra'], $data['total'], $data['notes']);
			}



			$this->load->view('pdf_job_order', $data);
		}
	}

	function order_bulk()
	{
		// Validate form input
		$this->form_validation->set_rules('contact_name', $this->lang->line('create_order_contact_name_error'), 'required|xss_clean');

		if ($this->form_validation->run() == TRUE && $this->order_model->create_order())
		// if ($this->form_validation->run() == TRUE)
		{
			$this->session->set_flashdata('message', 'Order successfully created');
			redirect("staff", 'refresh');
		} else {
			$this->data['message'] = (validation_errors() ? validation_errors() : '');
			$this->data['order_name'] = array(
				'name'  => 'order_name',
				'id'    => 'order_name',
				'type'  => 'text',
				'value' => $this->form_validation->set_value('order_name'),
			);
			$this->data['contact_name'] = array(
				'name'  => 'contact_name',
				'id'    => 'contact_name',
				'type'  => 'text',
				'value' => $this->form_validation->set_value('contact_name'),
			);
			$this->data['contact_email'] = array(
				'name'  => 'contact_email',
				'id'    => 'contact_email',
				'type'  => 'text',
				'value' => $this->form_validation->set_value('contact_email'),
			);
			$this->data['contact_mobile'] = array(
				'name'  => 'contact_mobile',
				'id'    => 'contact_mobile',
				'type'  => 'text',
				'value' => $this->form_validation->set_value('contact_mobile'),
			);
			$this->data['date_delivery'] = array(
				'name'  => 'date_delivery',
				'id'    => 'date_delivery',
				'type'  => 'text',
				'value' => $this->form_validation->set_value('date_delivery'),
			);
			$data['options_material']	= $this->order_model->get_material_list();
			$data['options_lamination']	= $this->order_model->get_lamination_list();
			$data['options_quality']	= $this->order_model->get_quality_list();
			$data['options_finishing']	= $this->order_model->get_finishing_list();
			$data['template_order']		= $this->order_model->get_template_list();
			$data['current_page'][0]	= "";
			$data['current_page'][1]	= "";
			$data['current_page'][2]	= "class='active'";
			$data['current_page'][3]	= "";
			$data['page_title']	= "Create New Job Order from Template";
			$data['heading']	= "Create New Job Order from Template";
			$this->load->view('header', $data);
			$this->load->view('staff_nav');
			$this->_render_page('staff_order_form_bulk', $this->data);
			$this->load->view('footer');
		}
	}




	function search()
	{
		$page				= array(0, 40, 0);


		$keywords["ref"]	= $this->input->post("ref");
		$keywords["job"]	= $this->input->post("job");
		$keywords["lpo"]	= $this->input->post("lpo");
		$keywords["ord"]	= $this->input->post("ord");
		$keywords["exd"]	= $this->input->post("exd");
		$keywords["cnn"]	= $this->input->post("cnn");
		$keywords["sts"]	= $this->input->post("sts");
		$search_result 		= $this->order_model->get_all_order("", $page, $keywords);
		//~ $html = "<tr><td colspan='12'>".count($search_result)."</td></tr>";
		$html = "";

		for ($i = 0; $i < count($search_result); $i++) {
			$j 			= $i + 1;
			$data_row 	= $search_result[$i];
			$order_status	= $this->order_model->get_order_status($data_row->id_order);



			$html 		.= '
		<tr>
			<td>' . $j . '</td>
			<td>' . anchor('/staff/edit_order/' . $data_row->id_order, $data_row->id_order) . '</td>
			<td>' . $data_row->lpo . '</td>
			<td>' . date("d/m/Y H:i:s", $data_row->order_date) . '</td>
			<td>' . date("d/m/Y", $data_row->req_delivery_date) . '</td>
			<td>' . $data_row->username . '</td>
			<td>' . $data_row->order_name . '</td>
			<td>' . anchor('/staff/edit_order/' . $data_row->id_order, implode(", ", $this->order_model->get_order_filename($data_row->id_order))) . '</td>
			<td>' . anchor('/staff/edit_order/' . $data_row->id_order, implode(", ", $this->order_model->get_order_quantity($data_row->id_order))) . '</td>
			<td>' . $order_status . '</td>
			<td width="148">
			';

			if ($order_status == "Hold") {
				$html .= anchor('/staff/order_detail/' . $data_row->id_order, 'Edit', 'role="button" class="lnkEditOrder btn btn-default btn-sm"');
				$html .=  "&nbsp;";
				$html .=  anchor('/client/delete_order/' . $data_row->id_order, 'Delete', 'role="button" class="lnkDelete btn btn-default btn-sm"');
				$html .=  "&nbsp;";
				$html .=  anchor('/client/print_pdf/' . $data_row->id_order, 'Print', 'role="button" class="lnkPrint btn btn-default btn-sm" target="new"');
			} else {
				$html .=  anchor('/client/order_detail/' . $data_row->id_order, 'Detail', 'role="button" class="lnkDetail btn btn-default btn-sm"');
				$html .=  "&nbsp;";
				$html .=  anchor('/client/print_pdf/' . $data_row->id_order, 'Print', 'role="button" class="lnkPrint btn btn-default btn-sm" target="new"');
			}


			$html .= '
			</td>
		</tr>';
		}

		echo $html;
	}

	function create_pagination($pages)
	{
		$first_link = '&lsaquo; First';
		$next_link = '&gt;';
		$prev_link = '&lt;';
		$last_link = 'Last &rsaquo;';
		$num_links = 9;

		$page_num = $pages['pagenum'];
		$per_page = $pages['per_page'];
		$total_rows = $pages['total_rows'];
		$base_url = $pages['base_url'];

		$output = "";
		$current_page = (int) ($page_num + $per_page) / $per_page;
		$num_pages = ceil($total_rows / $per_page);

		// First link
		if ($current_page > ($num_links + 1)) {
			$output .= '<a href="' . $base_url . '">' . $first_link . '</a>&nbsp;';
		}

		$start = (($current_page - $num_links) > 0) ? $current_page - ($num_links - 1) : 1;
		$end   = (($current_page + $num_links) < $num_pages) ? $current_page + $num_links : $num_pages;

		if ($num_pages > 1) {
			for ($loop = $start - 1; $loop <= $end; $loop++) {
				$i = $loop;

				if ($i >= 1) {
					if ($current_page == $loop) {
						$output .= '<b>' . $i . '</b>&nbsp;';
					} else {
						$n = ($i == 1) ? '' : $i;

						if ($n == '') {
							$output .= '<a href="' . $base_url . '">' . $first_link . '</a>&nbsp;';
						} else {
							$n = ($n == '') ? '' : $n;
							$output .= '<a href="' . $base_url . '/p-' . $n . '">' . $n . '</a>&nbsp;';
						}
					}
				}
			}
		}

		if (($current_page + $num_links) < $num_pages) {
			$i = $num_pages;
			$output .= '<a href="' . $base_url . '/p-' . $i . '">' . $last_link . '</a>';
		}
		return $output;
	}


	function _render_page($view, $data = null, $render = false)
	{

		$this->viewdata = (empty($data)) ? $this->data : $data;

		$view_html = $this->load->view($view, $this->viewdata, $render);

		if (!$render) return $view_html;
	}


	function applyFilter($segs, $base_url, $per_page, $valid_sort_index)
	{
		$sortmode = 0;
		$pagenum = 0;
		$base_url = base_url($base_url);
		$keywords = [];
		foreach ($segs as $segment) {
			$parameter = explode("-", $segment);
			if (count($parameter) > 1) {
				switch ($parameter[0]) {
					case "p":
						$pagenum = ((int) $parameter[1] - 1) * $per_page;
						break;
					case "sort":
						if (in_array($parameter[1], $valid_sort_index)) {
							$sortindex = $parameter[1];
							$sorttype = (strtolower($parameter[2]) == "up") ? "asc" : "desc";
							$sortmode = array($sortindex, $sorttype);
							$base_url = base_url("admin/index") . "/sort-" . $parameter[1] . "-" . $parameter[2];
						}
						break;
						// Search keywords
					case "s":
						if ((! empty($parameter[2])) and strtolower($parameter[2]) != "undefined") {
							$keywords[$parameter[1]] = $parameter[2];
						}
						break;
				}
			}
		}
		return [
			"page_num" => (int) $pagenum,
			"base_url" => $base_url,
			"sortmode" => $sortmode,
			"keywords" => $keywords,
		];
	}
}

/* End of file staff.php */
/* Location: ./application/controllers/staff.php */
