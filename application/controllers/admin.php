<?php if (! defined('BASEPATH')) exit('No direct script access allowed');

class Admin extends CI_Controller
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
		$this->load->model('order_model');

		$this->load->database();

		$this->form_validation->set_error_delimiters($this->config->item('error_start_delimiter', 'ion_auth'), $this->config->item('error_end_delimiter', 'ion_auth'));

		// Check if current user admin or not
		if (!$this->ion_auth->in_group(1)) {
			redirect('auth/login', 'refresh');
		}

		// ABDELRADY - Disable Cache
		$this->output->set_header('Expires: Sat, 26 Jul 1997 05:00:00 GMT');
		$this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate');
		$this->output->set_header('Cache-Control: post-check=0, pre-check=0');
		$this->output->set_header('Pragma: no-cache');
		// ABDELRADY - Disable Cache

	}

	function index()
	{


		$valid_sort_index 	= array("ref", "orderdate", "expdelivery", "clientname", "contact", "status");
		$pages['per_page'] 		= $per_page	= 20;
		$pageExtra = $this->applyFilter($this->uri->segment_array(), "admin/index", $per_page, $valid_sort_index);

		$pages["base_url"] = $pageExtra["base_url"];
		$pages["total_rows"] = $this->order_model->count_all_order("", $pageExtra["keywords"]);
		$pages["pagenum"] = $pageExtra["page_num"];

		$page				= array($pageExtra["page_num"], $per_page, $pageExtra["sortmode"]);
		$result				= $this->order_model->get_all_order("", $page, $pageExtra["keywords"]);

		$data['current_page'][0]	= "class='active'";
		$data['current_page'][1]	= "";
		$data['current_page'][2]	= "";
		$data['current_page'][3]	= "";
		$data['current_page'][4]	= "";
		$data["links"]				= FALSE;
		if (count($result)) {
			$data['empty_order']		= FALSE;
			//~ $this->pagination->initialize($pages);
			//~ $data["links"]		= $this->pagination->create_links();
			$data["links"]		= $this->create_pagination($pages);
			for ($i = 0; $i < count($result); $i++) {
				$data_row					= $result[$i];
				$data['row_number'][]		= $pageExtra["page_num"] + $i + 1;
				// $data['row_number'][]		= $this->uri->segment(3) + $i + 1;
				$data['order_id'][]			= $data_row->id_order;
				$data['order_date'][]		= date("d/m/Y", $data_row->order_date);
				$data['order_delivery'][]	= date("d/m/Y", $data_row->req_delivery_date);
				$data['order_username'][]	= $data_row->username;
				$data['order_contact'][]	= $data_row->contact_name;
				$data['order_status'][]		= $this->order_model->get_order_status($data_row->id_order);
				$data['order_cost'][]		= $this->order_model->get_order_cost($data_row->id_order);
				$data['start'][]			= $this->order_model->get_user_start($data_row->id_order);
				$data['print'][]			= $this->order_model->get_user_print($data_row->id_order);
				$data['laminate'][]			= $this->order_model->get_user_laminate($data_row->id_order);
				$data['process'][]			= $this->order_model->get_user_process($data_row->id_order);
				$data['check'][]			= $this->order_model->get_user_check($data_row->id_order);
				$data['ready'][]			= $this->order_model->get_user_ready($data_row->id_order);
				$data['deliver'][]			= $this->order_model->get_user_deliver($data_row->id_order);
				$payment					= $this->order_model->get_payment_status($data_row->id_order);
				$data['payment_invoiced'][]	= $payment['invoiced'] == 1 ? "checked='checked'" : "";
				$data['payment_partial'][]	= $payment['partial'] == 1 ? "checked='checked'" : "";
				$data['payment_full'][]		= $payment['full'] == 1 ? "checked='checked'" : "";
			}
		} else {
			$data['empty_order']		= TRUE;
		}

		$data['page_title']	= "Job Order List";
		$data['heading']	= "Production List";
		$this->load->view('header', $data);
		$this->load->view('admin_nav');
		$this->load->view('admin_order_list', $data);
		$this->load->view('footer');
	}




	function search()
	{
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
		$keywords["ord"]	= $this->input->post("ord");
		$keywords["exd"]	= $this->input->post("exd");
		$keywords["cnn"]	= $this->input->post("cnn");
		$keywords["ctt"]	= $this->input->post("ctt");
		$keywords["art"]	= $this->input->post("art");
		$keywords["fnm"]	= $this->input->post("fnm");
		$keywords["qty"]	= $this->input->post("qty");
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
			<td>' . anchor('admin/order_detail/' . $data_row->id_order, $data_row->id_order) . '</td>
			<td>' . date("d/m/Y", $data_row->order_date) . '</td>
			<td>' . date("d/m/Y", $data_row->req_delivery_date) . '</td>
			<td>' . $data_row->username . '</td>
			<td>' . $data_row->contact_name . '</td>
			<td>' . $this->order_model->get_order_cost($data_row->id_order) . '</td>
			<td>' . anchor('admin/order_detail/' . $data_row->id_order, $order_status) . '</td>';

			$payment			= $this->order_model->get_payment_status($data_row->id_order);
			$payment_invoiced	= $payment['invoiced'] == 1 ? "checked='checked'" : "";
			$payment_partial	= $payment['partial'] == 1 ? "checked='checked'" : "";
			$payment_full		= $payment['full'] == 1 ? "checked='checked'" : "";

			$html 		.= '
			<td class="order_status">
			<div class="checkbox mycheckbox">
				<label><input type="checkbox" name="cb_invoiced" ' . $payment_invoiced . '>Invoiced</label>
			</div>
			</td>
			<td>
			<div class="checkbox mycheckbox">
				<label><input type="checkbox" name="cb_partial" ' . $payment_partial . '>P.P.</label>
			</div>
			</td>
			<td>
			<div class="checkbox mycheckbox">
				<label><input type="checkbox" name="cb_full" ' . $payment_full . '>F.P.</label>
			</div>
			</td>
			<td>
			<input type="hidden" name="order_id" value="' . $data_row->id_order . '">
			<input type="hidden" name="url" value="' . base_url() . 'admin/update_order_payment" />
			<input type="hidden" name="archive" value="' . base_url() . 'admin/archive_order" />
			<button class="btn btn-default btn-sm btnUpdatePayment">Update</button>
			' . anchor('/admin/print_pdf/' . $data_row->id_order, 'Print', 'role="button" class="lnkPrint btn btn-default btn-sm" target="new"') . '
			<button class="btn btn-default btn-sm btnArchive">Archive</button>
			</td>
		</tr>';
		}

		echo $html;
	}



	function archive()
	{

		$valid_sort_index 	= array("ref", "orderdate", "expdelivery", "clientname", "contact", "status");
		$pages['per_page'] 		= $per_page	= 20;
		$pageExtra = $this->applyFilter($this->uri->segment_array(), "admin/archive", $per_page, $valid_sort_index);

		$pages["base_url"] = $pageExtra["base_url"];
		$pages["total_rows"] = $this->order_model->count_all_order("", $pageExtra["keywords"], TRUE);
		$pages["pagenum"] = $pageExtra["page_num"];

		$page				= array($pageExtra["page_num"], $per_page, $pageExtra["sortmode"]);
		$result				= $this->order_model->get_all_order("", $page, $pageExtra["keywords"], TRUE);


		// $pages['base_url'] 		= base_url("admin/archive");
		// $pages['total_rows'] 	= $this->order_model->count_all_order("", "", TRUE);
		// $pages['per_page'] 		= $per_page	= 20;

		// $page				= array ($this->uri->segment(3), $per_page);
		// $result				= $this->order_model->get_all_order("", $page, "", TRUE);

		$data['current_page'][0]	= "";
		$data['current_page'][1]	= "";
		$data['current_page'][2]	= "";
		$data['current_page'][3]	= "class='active'";
		$data['current_page'][4]	= "";
		$data["links"]				= FALSE;
		if (count($result)) {
			$data['empty_order']		= FALSE;
			$data["links"]		= $this->create_pagination($pages);
			for ($i = 0; $i < count($result); $i++) {
				$data_row					= $result[$i];
				$data['row_number'][]		= $pageExtra["page_num"] + $i + 1;
				$data['order_id'][]			= $data_row->id_order;
				$data['order_date'][]		= date("d/m/Y", $data_row->order_date);
				$data['order_delivery'][]	= date("d/m/Y", $data_row->req_delivery_date);
				$data['order_username'][]	= $data_row->username;
				$data['order_contact'][]	= $data_row->contact_name;
				$data['order_status'][]		= $this->order_model->get_order_status($data_row->id_order);
				$data['order_cost'][]		= $this->order_model->get_order_cost($data_row->id_order);
				$data['start'][]			= $this->order_model->get_user_start($data_row->id_order);
				$data['print'][]			= $this->order_model->get_user_print($data_row->id_order);
				$data['laminate'][]			= $this->order_model->get_user_laminate($data_row->id_order);
				$data['process'][]			= $this->order_model->get_user_process($data_row->id_order);
				$data['check'][]			= $this->order_model->get_user_check($data_row->id_order);
				$data['ready'][]			= $this->order_model->get_user_ready($data_row->id_order);
				$data['deliver'][]			= $this->order_model->get_user_deliver($data_row->id_order);
				$payment					= $this->order_model->get_payment_status($data_row->id_order);
				$data['payment_invoiced'][]	= $payment['invoiced'] == 1 ? "checked='checked'" : "";
				$data['payment_partial'][]	= $payment['partial'] == 1 ? "checked='checked'" : "";
				$data['payment_full'][]		= $payment['full'] == 1 ? "checked='checked'" : "";
			}
		} else {
			$data['empty_order']		= TRUE;
		}

		$data['page_title']	= "Archived Job Order List";
		$data['heading']	= "Archived Production List";
		$data['current_page'][0]	= "";
		$data['current_page'][1]	= "";
		$data['current_page'][2]	= "";
		$data['current_page'][3]	= "class='active'";
		$data['current_page'][4]	= "";
		$this->load->view('header', $data);
		$this->load->view('admin_nav');
		$this->load->view('admin_archive_order_list', $data);
		$this->load->view('footer');
	}


	function archive_search()
	{
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
		$keywords["ord"]	= $this->input->post("ord");
		$keywords["exd"]	= $this->input->post("exd");
		$keywords["cnn"]	= $this->input->post("cnn");
		$keywords["ctt"]	= $this->input->post("ctt");
		$keywords["art"]	= $this->input->post("art");
		$keywords["fnm"]	= $this->input->post("fnm");
		$keywords["qty"]	= $this->input->post("qty");
		$keywords["sts"]	= $this->input->post("sts");
		$search_result 		= $this->order_model->get_all_order("", $page, $keywords, TRUE);
		//~ $html = "<tr><td colspan='12'>".count($search_result)."</td></tr>";
		$html = "";

		for ($i = 0; $i < count($search_result); $i++) {
			$j 			= $i + 1;
			$data_row 	= $search_result[$i];
			$order_status	= $this->order_model->get_order_status($data_row->id_order);
			$html 		.= '
		<tr>
			<td>' . $j . '</td>
			<td>' . anchor('admin/order_detail/' . $data_row->id_order, $data_row->id_order) . '</td>
			<td>' . date("d/m/Y", $data_row->order_date) . '</td>
			<td>' . date("d/m/Y", $data_row->req_delivery_date) . '</td>
			<td>' . $data_row->username . '</td>
			<td>' . $data_row->contact_name . '</td>
			<td>' . $this->order_model->get_order_cost($data_row->id_order) . '</td>
			<td>' . anchor('admin/order_detail/' . $data_row->id_order, $order_status) . '</td>';

			$payment			= $this->order_model->get_payment_status($data_row->id_order);
			$payment_invoiced	= $payment['invoiced'] == 1 ? "checked='checked'" : "";
			$payment_partial	= $payment['partial'] == 1 ? "checked='checked'" : "";
			$payment_full		= $payment['full'] == 1 ? "checked='checked'" : "";

			$html 		.= '
			<td class="order_status">
			<div class="checkbox mycheckbox">
				<label><input type="checkbox" name="cb_invoiced" ' . $payment_invoiced . '>Invoiced</label>
			</div>
			</td>
			<td>
			<div class="checkbox mycheckbox">
				<label><input type="checkbox" name="cb_partial" ' . $payment_partial . '>P.P.</label>
			</div>
			</td>
			<td>
			<div class="checkbox mycheckbox">
				<label><input type="checkbox" name="cb_full" ' . $payment_full . '>F.P.</label>
			</div>
			</td>
			<td>
			<input type="hidden" name="order_id" value="' . $data_row->id_order . '">
			<input type="hidden" name="url" value="' . base_url() . 'admin/update_order_payment" />
			<input type="hidden" name="archive" value="' . base_url() . 'admin/archive_order" />
			<button class="btn btn-default btn-sm btnUpdatePayment">Update</button>
			' . anchor('/admin/print_pdf/' . $data_row->id_order, 'Print', 'role="button" class="lnkPrint btn btn-default btn-sm" target="new"') . '
			<button class="btn btn-default btn-sm btnArchive">Archive</button>
			</td>
		</tr>';
		}

		echo $html;
	}



	function user_list()
	{
		// Get all groups
		$groups = $this->ion_auth->groups(1)->result();
		foreach ($groups as $row) {
			$group_description 		= $row->description;
			$users = $this->ion_auth->users($row->id)->result();
			foreach ($users as $row_2) {
				$data["users_id"][] = $row_2->id;
				$data["users_name"][] = $row_2->username;
				$data["groups_name"][] = $group_description;
			}
		}
		$data['page_title']	= "User List";
		$data['heading']	= "User List";
		$data['current_page'][0]	= "";
		$data['current_page'][1]	= "class='active'";
		$data['current_page'][2]	= "";
		$data['current_page'][3]	= "";
		$data['current_page'][4]	= "";
		$data['message']	= $this->session->flashdata('message') ? $this->session->flashdata('message') : '';
		$this->load->view('header', $data);
		$this->load->view('admin_nav');
		$this->load->view('admin_user_list', $data);
		$this->load->view('footer');
	}

	function add_user()
	{
		// Validate form input
		$this->form_validation->set_rules('username', $this->lang->line('create_user_validation_username_label'), 'required|xss_clean|is_unique[users.username]');
		$this->form_validation->set_rules('email', $this->lang->line('create_user_validation_email_label'), 'required|valid_email|is_unique[users.email]');
		$this->form_validation->set_rules('password', $this->lang->line('create_user_validation_password_label'), 'required|min_length[' . $this->config->item('min_password_length', 'ion_auth') . ']|max_length[' . $this->config->item('max_password_length', 'ion_auth') . ']|matches[password_confirm]');
		$this->form_validation->set_rules('password_confirm', $this->lang->line('create_user_validation_password_confirm_label'), 'required');

		if ($this->form_validation->run() == true) {
			$username 	= strtolower($this->input->post('username'));
			$email    	= strtolower($this->input->post('email'));
			$password 	= $this->input->post('password');
			$group[]	= (int) $this->input->post('user_group');

			$additional_data = array(
				'first_name' => '',
				'last_name'  => '',
				'company'    => '',
				'phone'      => '',
			);
		}

		if ($this->form_validation->run() == true && $this->ion_auth->register($username, $password, $email, $additional_data, $group)) {
			//check to see if we are creating the user
			//redirect them back to the admin page
			$this->session->set_flashdata('message', $this->ion_auth->messages());
			redirect("admin/user_list", 'refresh');
		} else {
			$this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));

			$this->data['username'] = array(
				'name'  => 'username',
				'id'    => 'username',
				'type'  => 'text',
				'value' => $this->form_validation->set_value('username'),
			);
			$this->data['email'] = array(
				'name'  => 'email',
				'id'    => 'email',
				'type'  => 'text',
				'value' => $this->form_validation->set_value('email'),
			);
			$this->data['ug_options'] = array(
				'2'  => 'Staff',
				'3' => 'Client',
				'4' => 'Major Client'
			);
			$this->data['ug_value'] =  $this->form_validation->set_value('user_group');

			$this->data['company'] = array(
				'name'  => 'company',
				'id'    => 'company',
				'type'  => 'text',
				'value' => $this->form_validation->set_value('company'),
			);
			$this->data['phone'] = array(
				'name'  => 'phone',
				'id'    => 'phone',
				'type'  => 'text',
				'value' => $this->form_validation->set_value('phone'),
			);
			$this->data['password'] = array(
				'name'  => 'password',
				'id'    => 'password',
				'type'  => 'password',
				'value' => $this->form_validation->set_value('password'),
			);
			$this->data['confirm_password'] = array(
				'name'  => 'password_confirm',
				'id'    => 'password_confirm',
				'type'  => 'password',
				'value' => $this->form_validation->set_value('password_confirm'),
			);

			$data['current_page'][0]	= "";
			$data['current_page'][1]	= "class='active'";
			$data['current_page'][2]	= "";
			$data['current_page'][3]	= "";
			$data['current_page'][4]	= "";
			$data['page_title']	= "Add User";
			$this->load->view('header', $data);
			$this->load->view('admin_nav');
			$this->_render_page('admin_add_user', $this->data);
			$this->load->view('footer');
		}
	}

	//edit a user
	function edit_user($id)
	{
		$user 			= $this->ion_auth->user($id)->row();
		$groups			= $this->ion_auth->groups()->result_array();
		$currentGroups 	= $this->ion_auth->get_users_groups($id)->result();

		//validate form input
		$this->form_validation->set_rules('username', $this->lang->line('create_user_validation_username_label'), 'required|xss_clean');
		$this->form_validation->set_rules('email', $this->lang->line('create_user_validation_email_label'), 'required|valid_email');

		if (isset($_POST) && !empty($_POST)) {
			// do we have a valid request?
			if ($this->_valid_csrf_nonce() === FALSE || $id != $this->input->post('id')) {
				show_error($this->lang->line('error_csrf'));
			}

			$data = array(
				'username'		=> $this->input->post('username'),
				'email'			=> $this->input->post('email'),
				'first_name' => '',
				'last_name'  => '',
				'company'    => '',
				'phone'      => '',
			);

			//Update the groups user belongs to
			$groupData = $this->input->post('groups');

			if (isset($groupData) && !empty($groupData)) {

				$this->ion_auth->remove_from_group('', $id);

				foreach ($groupData as $grp) {
					$this->ion_auth->add_to_group($grp, $id);
				}
			}

			//update the password if it was posted
			if ($this->input->post('password')) {
				$this->form_validation->set_rules('password', $this->lang->line('edit_user_validation_password_label'), 'required|min_length[' . $this->config->item('min_password_length', 'ion_auth') . ']|max_length[' . $this->config->item('max_password_length', 'ion_auth') . ']|matches[password_confirm]');
				$this->form_validation->set_rules('password_confirm', $this->lang->line('edit_user_validation_password_confirm_label'), 'required');

				$data['password'] = $this->input->post('password');
			}

			if ($this->form_validation->run() === TRUE) {
				$this->ion_auth->update($user->id, $data);

				//check to see if we are creating the user
				if ($this->ion_auth->errors()) {
					$this->session->set_flashdata('message', $this->ion_auth->errors());
				} else {
					$this->session->set_flashdata('message', "User Saved");
				}
				redirect("admin/user_list", 'refresh');
			}
		}

		//display the edit user form
		$this->data['csrf'] = $this->_get_csrf_nonce();

		//set the flash data error message if there is one
		$this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));

		//pass the user to the view
		$this->data['user'] = $user;
		$this->data['groups'] = $groups;
		$this->data['currentGroups'] = $currentGroups;

		$this->data['username'] = array(
			'name'  => 'username',
			'id'    => 'username',
			'type'  => 'text',
			'value' => $this->form_validation->set_value('username', $user->username),
		);
		$this->data['email'] = array(
			'name'  => 'email',
			'id'    => 'email',
			'type'  => 'text',
			'value' => $this->form_validation->set_value('email', $user->email),
		);
		$this->data['password'] = array(
			'name' => 'password',
			'id'   => 'password',
			'type' => 'password'
		);
		$this->data['password_confirm'] = array(
			'name' => 'password_confirm',
			'id'   => 'password_confirm',
			'type' => 'password'
		);

		$data['current_page'][0]	= "";
		$data['current_page'][1]	= "class='active'";
		$data['current_page'][2]	= "";
		$data['current_page'][3]	= "";
		$data['current_page'][4]	= "";
		$data['page_title']	= "Edit User";
		$data['page_heading']	= "Edit User";
		$this->load->view('header', $data);
		$this->load->view('admin_nav');
		$this->_render_page('admin_edit_user', $this->data);
		$this->load->view('footer');
	}

	function delete_user($id)
	{
		if ($this->ion_auth->delete_user($id)) {
			$this->session->set_flashdata('message', "User Deleted");
		} else {
			$this->session->set_flashdata('message', $this->ion_auth->errors());
		}
		redirect("admin/user_list", 'refresh');
	}

	function order_detail($order_id = "")
	{
		$order_id = (int) $order_id;
		if ($this->order_model->get_order_list($order_id)) {
			$result	= $this->order_model->get_order_list($order_id);
			$data_row				= $result[0];

			$data['client_id']		= $data_row->client_id;
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

			if ($this->form_validation->run() == TRUE && $this->order_model->update_order_admin($order_id))
			// if ($this->form_validation->run() == TRUE)
			{
				$this->session->set_flashdata('message', 'Order successfully updated');
				redirect("admin", 'refresh');
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
						$data['materialname'][] 	= $this->order_model->get_material_name($data_row->detail_material);
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
				$data['current_page'][0]	= "class='active'";
				$data['current_page'][1]	= "";
				$data['current_page'][2]	= "";
				$data['current_page'][3]	= "";
				$data['current_page'][4]	= "";
				$data['page_title']	= "Edit Job Order";
				$data['heading']	= "Edit Job Order";
				$this->load->view('header', $data);
				$this->load->view('admin_nav');
				$this->load->view('admin_order_detail', $this->data);
				$this->load->view('footer');
			}
		} else {
			$data['empty_order']		= TRUE;
		}
	}

	function app_setting()
	{
		$data['material']		= $this->order_model->get_material_list();
		$data['lamination']		= $this->order_model->get_lamination_list();
		$data['quality']		= $this->order_model->get_quality_list();
		$data['finishing']		= $this->order_model->get_finishing_list();
		$data['page_title']		= "App Setting";
		$data['current_page'][0]	= "";
		$data['current_page'][1]	= "";
		$data['current_page'][2]	= "class='active'";
		$data['current_page'][3]	= "";
		$data['current_page'][4]	= "";
		$data['message']	= $this->session->flashdata('message') ? $this->session->flashdata('message') : '';
		$this->load->view('header', $data);
		$this->load->view('admin_nav');
		$this->load->view('admin_app_setting', $data);
		$this->load->view('footer');
	}

	function edit_material($id)
	{
		if ($this->input->post('id')) {
			$item_id					= (int) $this->input->post('id');
			$value['material_name']		= trim($this->input->post('itemname'));
			$this->order_model->update_material($item_id, $value);
		}
		$data['message']	= $this->session->flashdata('message') ? $this->session->flashdata('message') : '';
		$data['current_page'][0]	= "";
		$data['current_page'][1]	= "";
		$data['current_page'][2]	= "class='active'";
		$data['current_page'][3]	= "";
		$data['current_page'][4]	= "";
		$material 				= $this->order_model->get_material_list($id);
		foreach ($material as $key => $value) {
			$data['item_id']	= $key;
			$data['item_name']	= $value;
		}
		$data['page_title']	= "Edit Material";
		$data['page_heading']	= "Edit Material";
		$this->load->view('header', $data);
		$this->load->view('admin_nav');
		$this->load->view('admin_edit_config', $data);
		$this->load->view('footer');
	}

	function add_material()
	{
		if ($this->input->post('itemname')) {
			$value['material_name']		= trim($this->input->post('itemname'));
			$this->order_model->add_material($value);
			$this->session->set_flashdata('message', 'Material successfully added');
			$this->app_setting();
		} else {
			$data['current_page'][0]	= "";
			$data['current_page'][1]	= "";
			$data['current_page'][2]	= "class='active'";
			$data['current_page'][3]	= "";
			$data['current_page'][4]	= "";
			$data['page_title']	= "Add Material";
			$data['page_heading']	= "Add Material";
			$this->load->view('header', $data);
			$this->load->view('admin_nav');
			$this->load->view('admin_add_item', $data);
			$this->load->view('footer');
		}
	}

	function edit_lamination($id)
	{
		if ($this->input->post('id')) {
			$item_id					= (int) $this->input->post('id');
			$value['lamination_name']	= trim($this->input->post('itemname'));
			$this->order_model->update_lamination($item_id, $value);
		}
		$data['message']	= $this->session->flashdata('message') ? $this->session->flashdata('message') : '';
		$data['current_page'][0]	= "";
		$data['current_page'][1]	= "";
		$data['current_page'][2]	= "class='active'";
		$data['current_page'][3]	= "";
		$data['current_page'][4]	= "";
		$lamination 				= $this->order_model->get_lamination_list($id);
		foreach ($lamination as $key => $value) {
			$data['item_id']	= $key;
			$data['item_name']	= $value;
		}
		$data['page_title']	= "Edit Lamination";
		$data['page_heading']	= "Edit Lamination";
		$this->load->view('header', $data);
		$this->load->view('admin_nav');
		$this->load->view('admin_edit_config', $data);
		$this->load->view('footer');
	}

	function add_lamination()
	{
		if ($this->input->post('itemname')) {
			$value['lamination_name']	= trim($this->input->post('itemname'));
			$this->order_model->add_lamination($value);
			$this->session->set_flashdata('message', 'Lamination successfully added');
			$this->app_setting();
		} else {
			$data['current_page'][0]	= "";
			$data['current_page'][1]	= "";
			$data['current_page'][2]	= "class='active'";
			$data['current_page'][3]	= "";
			$data['current_page'][4]	= "";
			$data['page_title']	= "Add Lamination";
			$data['page_heading']	= "Add Lamination";
			$this->load->view('header', $data);
			$this->load->view('admin_nav');
			$this->load->view('admin_add_item', $data);
			$this->load->view('footer');
		}
	}

	function edit_quality($id)
	{
		if ($this->input->post('id')) {
			$item_id					= (int) $this->input->post('id');
			$value['quality_name']		= trim($this->input->post('itemname'));
			$this->order_model->update_quality($item_id, $value);
		}
		$data['message']	= $this->session->flashdata('message') ? $this->session->flashdata('message') : '';
		$data['current_page'][0]	= "";
		$data['current_page'][1]	= "";
		$data['current_page'][2]	= "class='active'";
		$data['current_page'][3]	= "";
		$data['current_page'][4]	= "";
		$quality 					= $this->order_model->get_quality_list($id);
		foreach ($quality as $key => $value) {
			$data['item_id']	= $key;
			$data['item_name']	= $value;
		}
		$data['page_title']	= "Edit Quality";
		$data['page_heading']	= "Edit Quality";
		$this->load->view('header', $data);
		$this->load->view('admin_nav');
		$this->load->view('admin_edit_config', $data);
		$this->load->view('footer');
	}

	function add_quality()
	{
		if ($this->input->post('itemname')) {
			$value['quality_name']	= trim($this->input->post('itemname'));
			$this->order_model->add_quality($value);
			$this->session->set_flashdata('message', 'Lamination successfully added');
			$this->app_setting();
		} else {
			$data['current_page'][0]	= "";
			$data['current_page'][1]	= "";
			$data['current_page'][2]	= "class='active'";
			$data['current_page'][3]	= "";
			$data['current_page'][4]	= "";
			$data['page_title']	= "Add Quality";
			$data['page_heading']	= "Add Quality";
			$this->load->view('header', $data);
			$this->load->view('admin_nav');
			$this->load->view('admin_add_item', $data);
			$this->load->view('footer');
		}
	}

	function edit_finishing($id)
	{
		if ($this->input->post('id')) {
			$item_id					= (int) $this->input->post('id');
			$value['finishing_name']	= trim($this->input->post('itemname'));
			$this->order_model->update_finishing($item_id, $value);
		}
		$data['message']	= $this->session->flashdata('message') ? $this->session->flashdata('message') : '';
		$data['current_page'][0]	= "";
		$data['current_page'][1]	= "";
		$data['current_page'][2]	= "class='active'";
		$data['current_page'][3]	= "";
		$data['current_page'][4]	= "";
		$finishing 					= $this->order_model->get_finishing_list($id);
		foreach ($finishing as $key => $value) {
			$data['item_id']	= $key;
			$data['item_name']	= $value;
		}
		$data['page_title']	= "Edit Finishing";
		$data['page_heading']	= "Edit Finishing";
		$this->load->view('header', $data);
		$this->load->view('admin_nav');
		$this->load->view('admin_edit_config', $data);
		$this->load->view('footer');
	}

	function add_finishing()
	{
		if ($this->input->post('itemname')) {
			$value['finishing_name']	= trim($this->input->post('itemname'));
			$this->order_model->add_finishing($value);
			$this->session->set_flashdata('message', 'Lamination successfully added');
			$this->app_setting();
		} else {
			$data['current_page'][0]	= "";
			$data['current_page'][1]	= "";
			$data['current_page'][2]	= "class='active'";
			$data['current_page'][3]	= "";
			$data['current_page'][4]	= "";
			$data['page_title']	= "Add Finishing";
			$data['page_heading']	= "Add Finishing";
			$this->load->view('header', $data);
			$this->load->view('admin_nav');
			$this->load->view('admin_add_item', $data);
			$this->load->view('footer');
		}
	}

	function manage_template_order()
	{
		if ($this->input->post("post_data")) {
			//~ $json_input				= $this->input->post("post_data");
			//~ $decoded_json			= json_decode($json_input);
			//~ print_r($decoded_json);

			//~ $testsetset = $this->input->post("filename");
			//~ echo $testsetset[124];
			//~ echo count($this->input->post("filename"));
			$this->order_model->create_template();
		}
		$data['template_order']		= $this->order_model->get_template_list();
		$data['page_title']			= "Manage Template Order";
		$data['current_page'][0]	= "";
		$data['current_page'][1]	= "";
		$data['current_page'][2]	= "";
		$data['current_page'][3]	= "";
		$data['current_page'][4]	= "class='active'";
		$data['options_material']	= $this->order_model->get_material_list();
		$data['options_lamination']	= $this->order_model->get_lamination_list();
		$data['options_quality']	= $this->order_model->get_quality_list();
		$data['options_finishing']	= $this->order_model->get_finishing_list();
		$data['message']	= $this->session->flashdata('message') ? $this->session->flashdata('message') : '';
		$data['username']			= $this->order_model->get_clients();
		$this->load->view('header', $data);
		$this->load->view('admin_nav');
		$this->load->view('admin_manage_template_order', $data);
		$this->load->view('footer');
	}

	function update_order_payment()
	{
		$order_id 				= $this->input->post("order_id");
		$new_input['invoiced']	= $this->input->post("invoiced");
		$new_input['partial']	= $this->input->post("partial");
		$new_input['full']		= $this->input->post("full");
		$this->order_model->update_payment($order_id, $new_input);
		$data["response"]		= $new_input;
		$this->load->view('ajax_update_payment', $data);
	}

	function archive_order()
	{
		$order_id 				= $this->input->post("order_id");
		$input['is_archived']	= 1;
		$this->order_model->archive_order($order_id, $input);
		$data["response"]		= $order_id;
		$this->load->view('ajax_archive_order', $data);
	}

	function unarchive_order()
	{
		$order_id 				= $this->input->post("order_id");
		$input['is_archived']	= 0;
		$this->order_model->archive_order($order_id, $input);
		$data["response"]		= $order_id;
		$this->load->view('ajax_archive_order', $data);
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
			}



			$this->load->view('pdf_job_order', $data);
		}
	}

	function _get_csrf_nonce()
	{
		$this->load->helper('string');
		$key   = random_string('alnum', 8);
		$value = random_string('alnum', 20);
		$this->session->set_flashdata('csrfkey', $key);
		$this->session->set_flashdata('csrfvalue', $value);

		return array($key => $value);
	}

	function _valid_csrf_nonce()
	{
		if (
			$this->input->post($this->session->flashdata('csrfkey')) !== FALSE &&
			$this->input->post($this->session->flashdata('csrfkey')) == $this->session->flashdata('csrfvalue')
		) {
			return TRUE;
		} else {
			return FALSE;
		}
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
							$output .= '<a href="' . $bfase_url . '">' . $first_link . '</a>&nbsp;';
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

/* End of file admin.php */
/* Location: ./application/controllers/admin.php */
