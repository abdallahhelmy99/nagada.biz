<?php if (! defined('BASEPATH')) exit('No direct script access allowed');

class Client extends CI_Controller
{
	function __construct()
	{
		parent::__construct();
		$this->load->library('ion_auth');
		$this->load->library('form_validation');
		$this->load->library('pagination');
		$this->load->helper('url');
		$this->load->helper('language');
		$this->lang->load('auth');
		$this->lang->load('client');
		$this->load->model('order_model');

		$this->load->database();
		$this->form_validation->set_error_delimiters($this->config->item('error_start_delimiter', 'ion_auth'), $this->config->item('error_end_delimiter', 'ion_auth'));

		// If user is client or big client
		if (!(($this->ion_auth->in_group(3)) or ($this->ion_auth->in_group(4)))) {
			redirect('auth/login', 'refresh');
		}
	}

	function index()
	{
		$client_id			= $this->ion_auth->get_user_id();
		$valid_sort_index 	= array("ref", "jobname", "lpo", "orderdate", "expdelivery", "contact", "artwork", "status");

		$pages['per_page'] 		= $per_page	= 20;
		$pageExtra = $this->applyFilter($this->uri->segment_array(), "client/index", $per_page, $valid_sort_index);

		$pages["base_url"] = $pageExtra["base_url"];
		$pages["total_rows"] = $this->order_model->count_all_order($client_id, $pageExtra["keywords"]);
		$pages["pagenum"] = $pageExtra["page_num"];

		$page				= array($pageExtra["page_num"], $per_page, $pageExtra["sortmode"]);
		$result				= $this->order_model->get_all_order($client_id, $page, $pageExtra["keywords"]);

		$artwork[0]			= "";
		$artwork[1]			= "Mail";
		$artwork[2]			= "Drop Box";
		$artwork[3]			= "We Transfer";
		$artwork[4]			= "DVD";
		$artwork[5]			= "FTP";
		$artwork[6]			= "USB";
		$artwork[7]			= "Other";
		$data["links"]		= FALSE;
		if (count($result)) {

			$data['empty_order']		= FALSE;
			//			$this->pagination->initialize($pages);
			//			$data["links"]		= $this->pagination->create_links();
			$data["links"]		= $this->create_pagination($pages);

			for ($i = 0; $i < count($result); $i++) {
				$data_row					= $result[$i];
				$data['row_number'][]		= $pageExtra["page_num"] + $i + 1;
				$data['order_id'][]			= $data_row->id_order;
				$data['order_name'][]		= $data_row->order_name;
				$data['lpo'][]				= $data_row->lpo;
				$data['artwork_by'][]		= $artwork[$data_row->artwork_by];
				$data['order_date'][]		= date("d/m/Y", $data_row->order_date);
				$data['order_delivery'][]	= date("d/m/Y", $data_row->req_delivery_date);
				$data['order_contact'][]	= $data_row->contact_name;
				$data['order_filename'][]	= implode(", ", $this->order_model->get_order_filename($data_row->id_order));
				$data['order_quantity'][]	= implode(", ", $this->order_model->get_order_quantity($data_row->id_order));
				$data['order_status'][]		= $this->order_model->get_order_status($data_row->id_order);
			}
		} else {
			$data['empty_order']		= TRUE;
		}


		$data['page_title']	= "Job Order List";
		$data['heading']	= "Job Order List";
		$data['current_page'][0]	= "class='active'";
		$data['current_page'][1]	= "";
		$data['current_page'][2]	= ($this->ion_auth->in_group(4)) ? "" : "class='hidden'";
		$this->load->view('header', $data);
		$this->load->view('client_nav');
		$data['message'] 	= ($this->session->flashdata('message') ? $this->session->flashdata('message') : '');
		$this->load->view('client_order_list', $data);
		$this->load->view('footer');
	}

	function order_bulk()
	{
		// Validate form input
		$this->form_validation->set_rules('contact_name', $this->lang->line('create_order_contact_name_error'), 'required|xss_clean');


		if ($this->form_validation->run() == TRUE && $this->order_model->create_order())
		// if ($this->form_validation->run() == TRUE)
		{
			$this->session->set_flashdata('message', 'Order successfully created');
			redirect("client", 'refresh');
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
				// ABDELRADY - Email is not required
				// 'value' => $this->form_validation->set_value('contact_email'),
				// ABDELRADY - Email is not required
			);
			$this->data['contact_mobile'] = array(
				'name'  => 'contact_mobile',
				'id'    => 'contact_mobile',
				'type'  => 'text',
				'value' => '',
				// ABDELRADY - Phone Number is not required
				// 'value' => $this->form_validation->set_value('contact_mobile'),
				// ABDELRADY - Phone Number is not required
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
			$data['page_title']	= "Create New Job Order from Template";
			$data['heading']	= "Create New Job Order from Template";
			$this->load->view('header', $data);
			$this->load->view('client_nav');
			$this->_render_page('client_order_form_bulk', $this->data);
			$this->load->view('footer');
		}
	}

	function search()
	{
		$client_id			= $this->ion_auth->get_user_id();
		$page				= array(0, 40, 0);

		$artwork[0]			= "";
		$artwork[1]			= "Mail";
		$artwork[2]			= "Drop Box";
		$artwork[3]			= "We Transfer";
		$artwork[4]			= "DVD";
		$artwork[5]			= "FTP";
		$artwork[6]			= "USB";
		$artwork[7]			= "Other";

		$keywords["ref"]	= $this->input->post("ref");
		$keywords["job"]	= $this->input->post("job");
		$keywords["lpo"]	= $this->input->post("lpo");
		$keywords["ord"]	= $this->input->post("ord");
		$keywords["exd"]	= $this->input->post("exd");
		$keywords["ctt"]	= $this->input->post("ctt");
		$keywords["art"]	= $this->input->post("art");
		$keywords["fnm"]	= $this->input->post("fnm");
		$keywords["qty"]	= $this->input->post("qty");
		$keywords["sts"]	= $this->input->post("sts");
		$search_result 		= $this->order_model->get_all_order($client_id, $page, $keywords);
		//~ $html = "<tr><td colspan='12'>".count($search_result)."</td></tr>";
		$html = "";

		for ($i = 0; $i < count($search_result); $i++) {
			$j 			= $i + 1;
			$data_row 	= $search_result[$i];
			$order_status	= $this->order_model->get_order_status($data_row->id_order);
			$html 		.= '
		<tr>
			<td>' . $j . '</td>
			<td>' . anchor('/client/order_detail/' . $data_row->id_order, $data_row->id_order) . '</td>
			<td>' . $data_row->id_order . '</td>
			<td>' . $data_row->order_name . '</td>
			<td>' . $data_row->lpo . '</td>
			<td>' . date("d/m/Y", $data_row->order_date) . '</td>
			<td>' . date("d/m/Y", $data_row->req_delivery_date) . '</td>
			<td>' . $data_row->contact_name . '</td>
			<td>' . $artwork[$data_row->artwork_by] . '</td>
			<td>' . implode(", ", $this->order_model->get_order_filename($data_row->id_order)) . '</td>
			<td>' . implode(", ", $this->order_model->get_order_quantity($data_row->id_order)) . '</td>
			<td>' . $order_status . '</td>
			<td width="148">
			';

			if ($order_status == "Hold") {
				$html .= anchor('/client/order_detail/' . $data_row->id_order, 'Edit', 'role="button" class="lnkEditOrder btn btn-default btn-sm"');
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

	function order_form()
	{
		// Validate form input
		$this->form_validation->set_rules('contact_name', $this->lang->line('create_order_contact_name_error'), 'required|xss_clean');
		// $this->form_validation->set_rules('contact_email', 'Contact Email', 'trim');


		if ($this->form_validation->run() == TRUE && $this->order_model->create_order())
		// if ($this->form_validation->run() == TRUE)
		{
			$this->session->set_flashdata('message', 'Order successfully created');
			redirect("client", 'refresh');
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
			$data['current_page'][0]	= "";
			$data['current_page'][1]	= "class='active'";
			$data['current_page'][2]	= ($this->ion_auth->in_group(4)) ? "" : "class='hidden'";
			$data['page_title']	= "Create New Job Order";
			$data['heading']	= "Create New Job Order";
			$this->load->view('header', $data);
			$this->load->view('client_nav');
			$this->_render_page('client_order_form', $this->data);
			$this->load->view('footer');
		}
	}

	function order_detail($order_id = "")
	{
		$order_id = (int) $order_id;
		if ($this->order_model->get_order_list($order_id)) {
			$result	= $this->order_model->get_order_list($order_id);
			$data_row				= $result[0];

			$data['order_id']		= $data_row->id_order;
			$data['order_date']		= date("d/m/Y", $data_row->order_date);
			$data['order_delivery']	= date("d/m/Y", $data_row->req_delivery_date);
			$data['order_contact']	= $data_row->contact_name;
			$data['order_email']	= $data_row->contact_email;
			$data['order_mobile']	= $data_row->contact_mobile;
			$data['artwork_by']		= $data_row->artwork_by;
			$data['order_status']	= $this->order_model->get_order_status($data_row->id_order);
			if ($data['order_status'] == "Hold") {

				// Validate form input
				$this->form_validation->set_rules('contact_name', $this->lang->line('create_order_contact_name_error'), 'required|xss_clean');

				if ($this->form_validation->run() == TRUE && $this->order_model->update_order_client($order_id))
				// if ($this->form_validation->run() == TRUE)
				{
					$this->session->set_flashdata('message', 'Order successfully updated');
					redirect("client", 'refresh');
				} else {

					$order_date				= date("m/d/Y", $data_row->order_date);
					$order_delivery			= date("m/d/Y", $data_row->req_delivery_date);
					$order_contact			= $data_row->contact_name;
					$order_email			= $data_row->contact_email;
					$order_mobile			= $data_row->contact_mobile;
					$this->data['message'] = (validation_errors() ? validation_errors() : '');
					$this->data['order_name'] = array(
						'name'  => 'order_name',
						'id'    => 'order_name',
						'type'  => 'text',
						'value' => $data_row->order_name,
					);
					$this->data['lpo'] = array(
						'name'  => 'lpo',
						'id'    => 'lpo',
						'type'  => 'text',
						'value' => $data_row->lpo,
					);
					$this->data['contact_name'] = array(
						'name'  => 'contact_name',
						'id'    => 'contact_name',
						'type'  => 'text',
						'value' => $order_contact,
					);
					$this->data['contact_email'] = array(
						'name'  => 'contact_email',
						'id'    => 'contact_email',
						'type'  => 'text',
						'value' => $order_email,
					);
					$this->data['contact_mobile'] = array(
						'name'  => 'contact_mobile',
						'id'    => 'contact_mobile',
						'type'  => 'text',
						'value' => $order_mobile,
					);
					$this->data['date_delivery'] = array(
						'name'  => 'date_delivery',
						'id'    => 'date_delivery',
						'type'  => 'text',
						'value' => $order_delivery,
					);


					$result	= $this->order_model->get_order_detail($order_id);
					$data['grand_qty']		= 0;
					$data['grand_size']		= 0;
					$data['grand_cost']		= 0;
					if (count($result)) {
						$data['options_material']	= $this->order_model->get_material_list();
						$data['options_lamination']	= $this->order_model->get_lamination_list();
						$data['options_quality']	= $this->order_model->get_quality_list();
						$data['options_finishing']	= $this->order_model->get_finishing_list();
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
							$data['material'][]		= $data_row->detail_material;
							$data['lamination'][]	= $data_row->detail_lamination;
							$data['quality'][]		= $data_row->detail_quality;
							$data['finishing'][]	= $data_row->detail_finishing;
							$data['up'][]			= $data_row->detail_up;
							$data['extra'][]		= (float) $data_row->detail_extra;
							$data['total'][]		= round(($m2 * $data_row->detail_up) + $data_row->detail_extra, 2);
							$total					= ($m2 * $data_row->detail_up) + $data_row->detail_extra;
							$data['notes'][]		= $data_row->detail_notes;
							$data['grand_qty']		= $data['grand_qty'] + $data_row->detail_quantity;
							$data['grand_size']		= $data['grand_size'] + $m2;
							$data['grand_cost']		= $data['grand_cost'] + $total;
						}
						$data['grand_size']			= round($data['grand_size'], 2);
						$data['grand_cost']			= round($data['grand_cost'], 2);
					}



					$data['current_page'][0]	= "";
					$data['current_page'][1]	= "class='active'";
					$data['current_page'][2]	= ($this->ion_auth->in_group(4)) ? "" : "class='hidden'";
					$data['page_title']	= "Edit Job Order";
					$data['heading']	= "Edit Job Order";
					$this->load->view('header', $data);
					$this->load->view('client_nav');
					$this->_render_page('client_edit_order_form', $this->data);
					$this->load->view('footer');
				}
			} else {

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
						$data['total'][]		= round(($m2 * $data_row->detail_up) + $data_row->detail_extra, 2);
						$total					= ($m2 * $data_row->detail_up) + $data_row->detail_extra;
						$data['notes'][]		= $data_row->detail_notes;
						$data['grand_qty']		= $data['grand_qty'] + $data_row->detail_quantity;
						$data['grand_size']		= $data['grand_size'] + $m2;
						$data['grand_cost']		= $data['grand_cost'] + $total;
					}
					$data['grand_size']			= round($data['grand_size'], 2);
					$data['grand_cost']			= round($data['grand_cost'], 2);
				} else {
					$data['empty_order']		= TRUE;
				}
				$data['current_page'][0]	= "";
				$data['current_page'][1]	= "class='active'";
				$data['current_page'][2]	= ($this->ion_auth->in_group(4)) ? "" : "class='hidden'";
				$data['page_title']	= "Job Order Detail";
				$data['heading']	= "Job Order Detail";
				$this->load->view('header', $data);
				$this->load->view('client_nav');
				$this->load->view('client_order_detail', $data);
				$this->load->view('footer');
			}
		} else {
			$data['empty_order']		= TRUE;
		}
	}

	function order_copy($order_id = "")
	{
		$order_id = (int) $order_id;
		if ($this->order_model->get_order_list($order_id)) {
			$result	= $this->order_model->get_order_list($order_id);
			$data_row				= $result[0];

			$data['order_id']		= $data_row->id_order;
			$data['order_date']		= date("d/m/Y", $data_row->order_date);
			$data['order_delivery']	= date("d/m/Y", $data_row->req_delivery_date);
			$data['order_contact']	= $data_row->contact_name;
			$data['order_email']	= $data_row->contact_email;
			$data['order_mobile']	= $data_row->contact_mobile;
			$data['artwork_by']		= $data_row->artwork_by;
			$data['order_status']	= $this->order_model->get_order_status($data_row->id_order);



			// Validate form input
			$this->form_validation->set_rules('contact_name', $this->lang->line('create_order_contact_name_error'), 'required|xss_clean');

			if ($this->form_validation->run() == TRUE && $this->order_model->create_order())
			// if ($this->form_validation->run() == TRUE)
			{
				$this->session->set_flashdata('message', 'Order successfully created');
				redirect("client", 'refresh');
			} else {

				$order_date				= date("m/d/Y", $data_row->order_date);
				$order_delivery			= date("m/d/Y", $data_row->req_delivery_date);
				$order_contact			= $data_row->contact_name;
				$order_email			= $data_row->contact_email;
				$order_mobile			= $data_row->contact_mobile;
				$this->data['message'] = (validation_errors() ? validation_errors() : '');
				$this->data['order_name'] = array(
					'name'  => 'order_name',
					'id'    => 'order_name',
					'type'  => 'text',
					'value' => $data_row->order_name,
				);
				$this->data['lpo'] = array(
					'name'  => 'lpo',
					'id'    => 'lpo',
					'type'  => 'text',
					'value' => $data_row->lpo,
				);
				$this->data['contact_name'] = array(
					'name'  => 'contact_name',
					'id'    => 'contact_name',
					'type'  => 'text',
					'value' => $order_contact,
				);
				$this->data['contact_email'] = array(
					'name'  => 'contact_email',
					'id'    => 'contact_email',
					'type'  => 'text',
					'value' => $order_email,
				);
				$this->data['contact_mobile'] = array(
					'name'  => 'contact_mobile',
					'id'    => 'contact_mobile',
					'type'  => 'text',
					'value' => $order_mobile,
				);
				$this->data['date_delivery'] = array(
					'name'  => 'date_delivery',
					'id'    => 'date_delivery',
					'type'  => 'text',
					'value' => $order_delivery,
				);


				$result	= $this->order_model->get_order_detail($order_id);
				$data['grand_qty']		= 0;
				$data['grand_size']		= 0;
				$data['grand_cost']		= 0;
				if (count($result)) {
					$data['options_material']	= $this->order_model->get_material_list();
					$data['options_lamination']	= $this->order_model->get_lamination_list();
					$data['options_quality']	= $this->order_model->get_quality_list();
					$data['options_finishing']	= $this->order_model->get_finishing_list();
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
						$data['material'][]		= $data_row->detail_material;
						$data['lamination'][]	= $data_row->detail_lamination;
						$data['quality'][]		= $data_row->detail_quality;
						$data['finishing'][]	= $data_row->detail_finishing;
						// ABDELRADY - Clear prices after creating a copy from an old order.
						$data['up'][]			= 0;
						$data['extra'][]		= 0;
						$data['total'][]		= 0;
						// ABDELRADY - Clear prices after creating a copy from an old order.
						$total					= ($m2 * $data_row->detail_up) + $data_row->detail_extra;
						$data['notes'][]		= $data_row->detail_notes;
						$data['grand_qty']		= $data['grand_qty'] + $data_row->detail_quantity;
						$data['grand_size']		= $data['grand_size'] + $m2;
						// ABDELRADY - Clear prices after creating a copy from an old order.
						$data['grand_cost']		= 0;
						// ABDELRADY - Clear prices after creating a copy from an old order.
					}
					$data['grand_size']			= round($data['grand_size'], 2);
					// ABDELRADY - Clear prices after creating a copy from an old order.
					$data['grand_cost']			= 0;
					// ABDELRADY - Clear prices after creating a copy from an old order.
				}



				$data['current_page'][0]	= "";
				$data['current_page'][1]	= "class='active'";
				$data['current_page'][2]	= ($this->ion_auth->in_group(4)) ? "" : "class='hidden'";
				$data['page_title']	= "Create New Job Order";
				$data['heading']	= "Create New Job Order";
				$this->load->view('header', $data);
				$this->load->view('client_nav');
				$this->_render_page('client_order_copy', $this->data);
				$this->load->view('footer');
			}
		} else {
			$data['empty_order']		= TRUE;
		}
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
					$data['m2'][]			= $m2;
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

	function delete_order($id)
	{
		if ($this->order_model->delete_order($id)) {
			$this->session->set_flashdata('message', "Order Deleted");
		}
		redirect("client", 'refresh');
	}

	function _render_page($view, $data = null, $render = false)
	{

		$this->viewdata = (empty($data)) ? $this->data : $data;

		$view_html = $this->load->view($view, $this->viewdata, $render);

		if (!$render) return $view_html;
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

/* End of file client.php */
/* Location: ./application/controllers/client.php */
