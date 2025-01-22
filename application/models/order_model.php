<?php if (! defined('BASEPATH')) exit('No direct script access allowed');
class Order_model extends CI_Model
{
	function __construct()
	{
		// Call the Model constructor
		parent::__construct();
		$this->load->database();
		$this->load->library('ion_auth');
		$this->tables  		= array(
			'order' => 'order_order',
			'users' => 'users',
			'group' => 'users_groups',
			'order_detail' => 'order_detail',
			'order_contact' => 'order_contact',
			'material' => 'list_material',
			'lamination' => 'list_lamination',
			'quality' => 'list_quality',
			'finishing' => 'list_finishing',
			'payment' => 'order_payment',
			'template' => 'order_template'
		);
	}

	function client_name_exist($client_name)
	{
		//SELECT * FROM `users` WHERE username = 'client'
		$this->db->select('id');
		$this->db->like('username', $client_name, 'none');
		$query			= $this->db->get($this->tables['users']);
		$result 		= $query->row();
		if ($query->num_rows() != 1) {
			return FALSE;
		} else {
			return $result->id;
		}
	}

	function create_order()
	{
		if ($this->client_name_exist($this->input->post('client_name'))) {
			$client_id 		= $this->client_name_exist($this->input->post('client_name'));
		} else {
			$client_id		= $this->ion_auth->get_user_id();
		}
		$order_name			= $this->input->post('order_name');
		$lpo				= $this->input->post('lpo');
		$artwork_by			= $this->input->post('artwork_by');
		$contact_name		= $this->input->post('contact_name');
		$contact_email     	= $this->input->post('contact_email');
		$contact_mobile    	= $this->input->post('contact_mobile');
		$order_date			= time();
		$date_delivery		= explode('/', $this->input->post('date_delivery'));
		$req_delivery_date	= mktime(0, 0, 0, $date_delivery[0], $date_delivery[1], $date_delivery[2]);

		$data_order 		= array(
			'order_name'		=> $order_name,
			'lpo'				=> $lpo,
			'artwork_by'		=> $artwork_by,
			'client_id'			=> $client_id,
			'order_date'        => $order_date,
			'req_delivery_date' => $req_delivery_date
		);
		$filtered_data_order	= array_map('trim', $data_order);
		$this->db->insert($this->tables['order'], $data_order);
		$id_order			= $this->db->insert_id();

		$detail_filename	= $this->input->post('filename');
		$detail_width		= $this->input->post('width');
		$detail_height		= $this->input->post('height');
		$detail_quantity	= $this->input->post('qty');
		$detail_material	= $this->input->post('material');
		$detail_lamination	= $this->input->post('lamination');
		$detail_quality		= $this->input->post('quality');
		$detail_finishing	= $this->input->post('finishing');
		$detail_up			= $this->input->post('up');
		$detail_extra		= $this->input->post('extra');
		$detail_notes		= $this->input->post('notes');
		for ($i = 0; $i < count($this->input->post('filename')); $i++) {
			if ($detail_quantity[$i] > 0) {
				$data_order_detail 		= array(
					'id_order'      	=> $id_order,
					'detail_filename' 	=> $detail_filename[$i],
					'detail_width' 		=> $detail_width[$i],
					'detail_height'     => $detail_height[$i],
					'detail_quantity'   => $detail_quantity[$i],
					'detail_material'   => $detail_material[$i],
					'detail_lamination' => $detail_lamination[$i],
					'detail_quality'    => $detail_quality[$i],
					'detail_finishing'  => $detail_finishing[$i],
					'detail_notes'    	=> $detail_notes[$i],
					'detail_up'    		=> $detail_up[$i],
					'detail_extra'    	=> $detail_extra[$i]
				);
				$this->db->insert($this->tables['order_detail'], $data_order_detail);
			}
		}

		// Get contact ID
		if ($id_contact = $this->get_contact_detail($contact_name, $client_id, $contact_email, $contact_mobile)) {
			// Update order table
			$data_order_update 		= array(
				'contact_id'		=> $id_contact
			);
			$this->db->where('client_id', $client_id);
			$this->db->where('id_order', $id_order);
			$this->db->update($this->tables['order'], $data_order_update);
		} else {
			$data_order_contact 	= array(
				'client_id'			=> $client_id,
				'contact_name'      => $contact_name,
				'contact_email'     => $contact_email,
				'contact_mobile'    => $contact_mobile
			);
			$this->db->insert($this->tables['order_contact'], $data_order_contact);
			$id_contact			= $this->db->insert_id();

			// Update order table
			$data_order_update 		= array(
				'contact_id'		=> $id_contact
			);
			$this->db->where('client_id', $client_id);
			$this->db->where('id_order', $id_order);
			$this->db->update($this->tables['order'], $data_order_update);
		}
		return TRUE;
	}

	function count_all_order($client_id = "", $keywords = "", $is_archived = FALSE)
	{
		$this->db->join($this->tables['order_contact'], $this->tables['order_contact'] . ".id_contact = " . $this->tables['order'] . ".contact_id", 'LEFT');

		if ($client_id != "") {
			$this->db->where($this->tables['order'] . '.client_id', $client_id);
		} else {
			if ($is_archived == FALSE) {
				$this->db->where($this->tables['order'] . '.is_archived', 0);
			} else {
				$this->db->where($this->tables['order'] . '.is_archived', 1);
			}
			//~ $this->db->where($this->tables['order'].'.is_archived', 0);
			$this->db->join($this->tables['users'], $this->tables['users'] . ".id = " . $this->tables['order'] . ".client_id", 'LEFT');
		}


		// This part is for search function
		if ($keywords) {
			if (!empty($keywords['ref'])) {
				$this->db->like($this->tables['order'] . '.id_order', $keywords['ref']);
			}
			if (!empty($keywords['job'])) {
				$this->db->like($this->tables['order'] . '.order_name', $keywords['job']);
			}
			if (!empty($keywords['lpo'])) {
				$this->db->like($this->tables['order'] . '.lpo', $keywords['lpo']);
			}
			if (!empty($keywords['ord'])) {
				$o_date = explode('/', $keywords['ord']);
				if (count($o_date) == 3) {
					if ((is_numeric($o_date[0])) and (is_numeric($o_date[1])) and (is_numeric($o_date[2]))) {
						$k_ord0	= mktime(0, 0, 0, $o_date[1], $o_date[0], $o_date[2]);
						$k_ord1	= mktime(23, 59, 59, $o_date[1], $o_date[0], $o_date[2]);
						$where_clause = 'order_date BETWEEN "' . $k_ord0 . '" AND "' . $k_ord1 . '"';
						$this->db->where($where_clause);
					}
				}
			}
			if (!empty($keywords['exd'])) {
				$d_date = explode('/', $keywords['exd']);
				if (count($d_date) == 3) {
					if ((is_numeric($d_date[0])) and (is_numeric($d_date[1])) and (is_numeric($d_date[2]))) {
						$k_ded0	= mktime(0, 0, 0, $d_date[1], $d_date[0], $d_date[2]);
						$k_ded1	= mktime(23, 59, 59, $d_date[1], $d_date[0], $d_date[2]);
						$where_clause = 'req_delivery_date BETWEEN "' . $k_ded0 . '" AND "' . $k_ded1 . '"';
						$this->db->where($where_clause);
					}
				}
			}
			if (!empty($keywords['ctt'])) {
				if ($keywords['ctt'] != 'undefined') {
					$this->db->like($this->tables['order_contact'] . '.contact_name', $keywords['ctt']);
				}
			}
			if (!empty($keywords['cnn'])) {
				if ($keywords['cnn'] != 'undefined') {
					$this->db->like($this->tables['users'] . '.username', $keywords['cnn']);
				}
			}
			if (!empty($keywords['art'])) {
				if ($keywords['art'] != 'undefined') {
					$this->db->like($this->tables['order'] . '.artwork_by', $keywords['art']);
				}
			}
			if (!empty($keywords['sts'])) {
				switch ($keywords['sts']) {
					case '0':
						$this->db->where($this->tables['order'] . '.started_by', NULL);
						break;
					case '1':
						$this->db->where($this->tables['order'] . '.started_by >', '0');
						$this->db->where($this->tables['order'] . '.printed_by', NULL);
						break;
					case '2':
						$this->db->where($this->tables['order'] . '.printed_by >', '0');
						$this->db->where($this->tables['order'] . '.laminated_by', NULL);
						break;
					case '3':
						$this->db->where($this->tables['order'] . '.laminated_by >', '0');
						$this->db->where($this->tables['order'] . '.processed_by', NULL);
						break;
					case '4':
						$this->db->where($this->tables['order'] . '.processed_by >', '0');
						$this->db->where($this->tables['order'] . '.checked_by', NULL);
						break;
					case '5':
						$this->db->where($this->tables['order'] . '.checked_by >', '0');
						$this->db->where($this->tables['order'] . '.ready_by', NULL);
						break;
					case '6':
						$this->db->where($this->tables['order'] . '.ready_by >', '0');
						$this->db->where($this->tables['order'] . '.delivered_by', NULL);
						break;
					case '7':
					default:
						$this->db->where($this->tables['order'] . '.delivered_by >', '0');
						break;
				}
			}
		}

		$this->db->from($this->tables['order']);
		return $this->db->count_all_results();
	}

	/*
	function count_archive_order($client_id = "")
	{
		if ($client_id != "")
		{
			$this->db->where($this->tables['order'].'.client_id', $client_id);
		}
		else
		{
			$this->db->where($this->tables['order'].'.is_archived', 1);
		}
		$this->db->from($this->tables['order']);
		return $this->db->count_all_results();
	}
	*/

	function get_all_order($client_id = "", $page = "", $keywords = "", $is_archived = FALSE)
	{
		$this->db->join($this->tables['order_contact'], $this->tables['order_contact'] . ".id_contact = " . $this->tables['order'] . ".contact_id", 'LEFT');
		//~ $this->db->join($this->tables['order_detail'], $this->tables['order_detail'].".id_order = ".$this->tables['order'].".id_order" , 'LEFT');

		if ($client_id != "") {
			$this->db->where($this->tables['order'] . '.client_id', $client_id);
			$this->db->select($this->tables['order'] . '.id_order, artwork_by, order_name, lpo, order_date, req_delivery_date, contact_name, started_by IS NOT NULL as started, printed_by IS NOT NULL as printed, laminated_by IS NOT NULL as laminated, processed_by IS NOT NULL as processed, checked_by IS NOT NULL as checked, ready_by IS NOT NULL as ready, delivered_by IS NOT NULL as delivered');
		} else {
			if ($is_archived == FALSE) {
				$this->db->where($this->tables['order'] . '.is_archived', 0);
			} else {
				$this->db->where($this->tables['order'] . '.is_archived', 1);
			}
			$this->db->select('username, id_order, order_name, lpo, artwork_by, order_date, req_delivery_date, contact_name, started_by IS NOT NULL as started, printed_by IS NOT NULL as printed, laminated_by IS NOT NULL as laminated, processed_by IS NOT NULL as processed, checked_by IS NOT NULL as checked, ready_by IS NOT NULL as ready, delivered_by IS NOT NULL as delivered');
			$this->db->join($this->tables['users'], $this->tables['users'] . ".id = " . $this->tables['order'] . ".client_id", 'LEFT');
		}


		// $query			 	= $this->db->order_by("id_order", "DESC");
		// $query			 	= $this->db->order_by("order_date", "DESC");
		// $query			 	= $this->db->order_by("req_delivery_date", "ASC");

		$valid_sort_index = array(
			"id_order" => "ref",
			"order_name" => "jobname",
			"username" => "clientname",
			"lpo" 		=> "lpo",
			"order_date" => "orderdate",
			"req_delivery_date" => "expdelivery",
			"contact_name" => "contact",
			"artwork_by" => "artwork",
		);


		// This part is for search function
		if ($keywords) {
			if (!empty($keywords['ref'])) {
				if ($keywords['ref'] != 'undefined')
					$this->db->like($this->tables['order'] . '.id_order', $keywords['ref']);
			}
			if (!empty($keywords['job'])) {
				if ($keywords['job'] != 'undefined')
					$this->db->like($this->tables['order'] . '.order_name', $keywords['job']);
			}
			if (!empty($keywords['lpo'])) {
				if ($keywords['lpo'] != 'undefined')
					$this->db->like($this->tables['order'] . '.lpo', $keywords['lpo']);
			}
			if (!empty($keywords['ord'])) {
				$o_date = explode('/', $keywords['ord']);
				if (count($o_date) == 3) {
					if ((is_numeric($o_date[0])) and (is_numeric($o_date[1])) and (is_numeric($o_date[2]))) {
						$k_ord0	= mktime(0, 0, 0, $o_date[1], $o_date[0], $o_date[2]);
						$k_ord1	= mktime(23, 59, 59, $o_date[1], $o_date[0], $o_date[2]);
						$where_clause = 'order_date BETWEEN "' . $k_ord0 . '" AND "' . $k_ord1 . '"';
						$this->db->where($where_clause);
					}
				}
			}
			if (!empty($keywords['exd'])) {
				$d_date = explode('/', $keywords['exd']);
				if (count($d_date) == 3) {
					if ((is_numeric($d_date[0])) and (is_numeric($d_date[1])) and (is_numeric($d_date[2]))) {
						$k_ded0	= mktime(0, 0, 0, $d_date[1], $d_date[0], $d_date[2]);
						$k_ded1	= mktime(23, 59, 59, $d_date[1], $d_date[0], $d_date[2]);
						$where_clause = 'req_delivery_date BETWEEN "' . $k_ded0 . '" AND "' . $k_ded1 . '"';
						$this->db->where($where_clause);
					}
				}
			}
			if (!empty($keywords['ctt'])) {
				if ($keywords['ctt'] != 'undefined')
					$this->db->like($this->tables['order_contact'] . '.contact_name', $keywords['ctt']);
			}
			if (!empty($keywords['cnn'])) {
				if ($keywords['cnn'] != 'undefined')
					$this->db->like($this->tables['users'] . '.username', $keywords['cnn']);
			}
			if (!empty($keywords['art'])) {
				if ($keywords['art'] != 'undefined')
					$this->db->like($this->tables['order'] . '.artwork_by', $keywords['art']);
			}
			if (!empty($keywords['fnm'])) {
				//~ $this->db->like($this->tables['order_detail'].'.detail_filename', $keywords['fnm']);
			}
			if (!empty($keywords['qty'])) {
				//~ $this->db->like($this->tables['order'].'.quantity', $keywords['qty']);
			}
			if (!empty($keywords['sts'])) {

				switch ($keywords['sts']) {
					case 'hold':
						$this->db->where($this->tables['order'] . '.started_by', NULL);
						break;
					case '1':
						$this->db->where($this->tables['order'] . '.started_by >', '0');
						$this->db->where($this->tables['order'] . '.printed_by', NULL);
						break;
					case '2':
						$this->db->where($this->tables['order'] . '.printed_by >', '0');
						$this->db->where($this->tables['order'] . '.laminated_by', NULL);
						break;
					case '3':
						$this->db->where($this->tables['order'] . '.laminated_by >', '0');
						$this->db->where($this->tables['order'] . '.processed_by', NULL);
						break;
					case '4':
						$this->db->where($this->tables['order'] . '.processed_by >', '0');
						$this->db->where($this->tables['order'] . '.checked_by', NULL);
						break;
					case '5':
						$this->db->where($this->tables['order'] . '.checked_by >', '0');
						$this->db->where($this->tables['order'] . '.ready_by', NULL);
						break;
					case '6':
						$this->db->where($this->tables['order'] . '.ready_by >', '0');
						$this->db->where($this->tables['order'] . '.delivered_by', NULL);
						break;
					case '7':
					default:
						$this->db->where($this->tables['order'] . '.delivered_by >', '0');
						break;
				}
			}
		}












		// This part is for sorting function
		if (is_array($page)) {
			if ((isset($page[2])) and (is_array($page[2]))) {
				if (in_array($page[2][0], $valid_sort_index)) {
					$field_name		= array_keys($valid_sort_index, $page[2][0]);
					$query			= $this->db->order_by($field_name[0], $page[2][1]);
				} else if ($page[2][0] == "status") {
					$query = $this->db->order_by("started", $page[2][1]);
					$query = $this->db->order_by("printed", $page[2][1]);
					$query = $this->db->order_by("laminated", $page[2][1]);
					$query = $this->db->order_by("processed", $page[2][1]);
					$query = $this->db->order_by("checked", $page[2][1]);
					$query = $this->db->order_by("ready", $page[2][1]);
					$query = $this->db->order_by("delivered", $page[2][1]);
				} else {
					$query			 = $this->db->order_by("id_order", "DESC");
				}
			} else {
				$query			 	= $this->db->order_by($this->tables['order'] . ".id_order", "DESC");
			}
			$query			 	= $this->db->get($this->tables['order'], $page[1], $page[0]);
		}

		// Display default page
		else {
			$query			 	= $this->db->get($this->tables['order']);
		}




		$rows			 	= $query->result();
		return $rows;
	}

	function get_archive_order($client_id = "", $page = "")
	{
		$this->db->where($this->tables['order'] . '.is_archived', 1);
		$this->db->join($this->tables['order_contact'], $this->tables['order_contact'] . ".id_contact = " . $this->tables['order'] . ".contact_id", 'LEFT');
		if ($client_id != "") {
			$this->db->where($this->tables['order'] . '.client_id', $client_id);
			$this->db->select('id_order, artwork_by, order_name, lpo, order_date, req_delivery_date, contact_name');
		} else {
			$this->db->select('username, id_order, order_name, lpo, artwork_by, order_date, req_delivery_date, contact_name');
			$this->db->join($this->tables['users'], $this->tables['users'] . ".id = " . $this->tables['order'] . ".client_id", 'LEFT');
		}



		$query			 	= $this->db->order_by("id_order", "DESC");
		// $query			 	= $this->db->order_by("order_date", "DESC");
		// $query			 	= $this->db->order_by("req_delivery_date", "ASC");
		if (!empty($page)) {
			$query			 	= $this->db->get($this->tables['order'], $page[1], $page[0]);
		} else {
			$query			 	= $this->db->get($this->tables['order']);
		}
		$rows			 	= $query->result();
		return $rows;
	}


	function get_order_list($order_id)
	{
		$this->db->select('id_order, ' . $this->tables['order'] . '.client_id, username, order_name, lpo, artwork_by, order_date, req_delivery_date, contact_name, contact_email, contact_mobile');
		$this->db->join($this->tables['order_contact'], $this->tables['order_contact'] . ".id_contact = " . $this->tables['order'] . ".contact_id", 'LEFT');
		$this->db->join($this->tables['users'], $this->tables['users'] . ".id = " . $this->tables['order'] . ".client_id", 'LEFT');
		$this->db->where($this->tables['order'] . '.id_order', $order_id);
		$query			 	= $this->db->get($this->tables['order']);
		$rows			 	= $query->result();

		return $rows;
	}

	function get_contact_detail($contact_name, $client_id, $contact_email, $contact_mobile)
	{
		$this->db->select('id_contact');
		$this->db->like('contact_name', $contact_name);
		$this->db->like('client_id', $client_id);
		$this->db->like('contact_email', $contact_email);
		$this->db->like('contact_mobile', $contact_mobile);
		$query			= $this->db->get($this->tables['order_contact']);
		$result 		= $query->row();
		if ($query->num_rows() < 1) {
			return FALSE;
		} else {
			return $result->id_contact;
		}
	}

	function get_client_list($input_query)
	{
		//SELECT user_id, username FROM `users_groups` LEFT JOIN users ON users.id = users_groups.user_id WHERE group_id = '3'
		$this->db->select('user_id, username');
		$this->db->like('username', $input_query);
		$this->db->join($this->tables['users'], $this->tables['users'] . ".id = " . $this->tables['group'] . ".user_id", 'LEFT');
		$query			= $this->db->get($this->tables['group']);
		$this->db->where($this->tables['group'] . '.group_id', '3');
		$result 		= $query->result();
		if ($query->num_rows() < 1) {
			return FALSE;
		} else {
			return $result;
		}
	}

	function get_clients()
	{

		$this->db->select('users.username')
			->from('users')
			->join('users_groups', 'users.id = users_groups.user_id');

		$this->db->where('users_groups.group_id', 4);
		// $this->db->where_in($this->tables['group'].'.group_id', array('3', '4'));	// 3 = Client, 4 = Major Client

		$query = $this->db->get();

		$usernames = array_column($query->result_array(), 'username');

		return $usernames;
	}

	// function get_clients() {

	// 	$this->db->select('users.id, users.username')
	// 	  ->from('users')
	// 	  ->join('users_groups', 'users.id = users_groups.user_id');

	// 	$this->db->where('users_groups.group_id', 3);

	// 	$query = $this->db->get();

	// 	// Return array with id and username
	// 	return $query->result_array(); 

	//   }

	function get_order_status($order_id, $return = "")
	{
		$this->db->select('started_by, printed_by, laminated_by, processed_by, checked_by, ready_by, delivered_by');
		$this->db->where('id_order', $order_id);
		$query			 	= $this->db->get($this->tables['order']);
		$rows			 	= $query->result();

		if ($return == "") {
			$result			= $rows[0];
			$data			= (empty($result->started_by) ? "Hold" : "Started");
			$data			= (empty($result->printed_by) ? $data : "Printed");
			$data			= (empty($result->laminated_by) ? $data : "Laminated");
			$data			= (empty($result->processed_by) ? $data : "Processed");
			$data			= (empty($result->checked_by) ? $data : "Checked");
			$data			= (empty($result->ready_by) ? $data : "Ready");
			$data			= (empty($result->delivered_by) ? $data : "Delivered");
		} else if ($return == "array") {
			$result				= $rows[0];
			$data = array((empty($result->started_by) ? 0 : 1),
				((!empty($result->printed_by) or (empty($result->started_by))) ? 1 : 0),
				((!empty($result->laminated_by) or (empty($result->printed_by))) ? 1 : 0),
				((!empty($result->processed_by) or (empty($result->laminated_by))) ? 1 : 0),
				((!empty($result->checked_by) or (empty($result->processed_by))) ? 1 : 0),
				((!empty($result->ready_by) or (empty($result->checked_by))) ? 1 : 0),
				((!empty($result->delivered_by) or (empty($result->ready_by))) ? 1 : 0)
			);
		}
		return $data;
	}

	function get_order_filename($order_id)
	{
		$this->db->select('detail_filename');
		$this->db->where('id_order', $order_id);
		$query			 	= $this->db->get($this->tables['order_detail']);
		$rows			 	= $query->result();
		$filename			= array();
		for ($i = 0; $i < count($rows); $i++) {
			$result				= $rows[$i];
			$filename[]			= $result->detail_filename;
		}
		return $filename;
	}

	function get_order_quantity($order_id)
	{
		$this->db->select('detail_quantity');
		$this->db->where('id_order', $order_id);
		$query			 	= $this->db->get($this->tables['order_detail']);
		$rows			 	= $query->result();
		$quantity			= array();
		for ($i = 0; $i < count($rows); $i++) {
			$result				= $rows[$i];
			$quantity[]			= $result->detail_quantity;
		}
		return $quantity;
	}

	function get_order_cost($order_id)
	{
		$this->db->select('detail_width, detail_height, detail_quantity, detail_up, detail_extra');
		$this->db->where('id_order', $order_id);
		$query			 	= $this->db->get($this->tables['order_detail']);
		$rows			 	= $query->result();
		$total				= 0;
		for ($i = 0; $i < count($rows); $i++) {
			$result				= $rows[$i];
			$total				= (($result->detail_width * $result->detail_height * $result->detail_quantity / 10000) * $result->detail_up) + $total + $result->detail_extra;
		}
		return $total;
	}

	function get_order_detail($order_id)
	{
		$this->db->where('id_order', $order_id);
		$this->db->order_by("detail_id", "ASC");
		$query			 	= $this->db->get($this->tables['order_detail']);
		$rows			 	= $query->result();
		return $rows;
	}

	function get_user_start($order_id)
	{
		$this->db->select('started_by, username');
		$this->db->where('id_order', $order_id);
		$this->db->join($this->tables['users'], $this->tables['users'] . ".id = " . $this->tables['order'] . ".started_by", 'LEFT');
		$query			 	= $this->db->get($this->tables['order']);
		$rows			 	= $query->result();
		$result				= $rows[0];
		$status				= (empty($result->username) ? "<span class=\"text-warning\">Not started yet</span>" : "<span class=\"text-success\">Started by " . $result->username . "</span>");
		return $status;
	}

	function get_user_print($order_id)
	{
		$this->db->select('printed_by, username');
		$this->db->where('id_order', $order_id);
		$this->db->join($this->tables['users'], $this->tables['users'] . ".id = " . $this->tables['order'] . ".printed_by", 'LEFT');
		$query			 	= $this->db->get($this->tables['order']);
		$rows			 	= $query->result();
		$result				= $rows[0];
		$status				= (empty($result->username) ? "<span class=\"text-warning\">Not printed yet</span>" : "<span class=\"text-success\">Printed by " . $result->username . "</span>");
		return $status;
	}

	function get_user_laminate($order_id)
	{
		$this->db->select('laminated_by, username');
		$this->db->where('id_order', $order_id);
		$this->db->join($this->tables['users'], $this->tables['users'] . ".id = " . $this->tables['order'] . ".laminated_by", 'LEFT');
		$query			 	= $this->db->get($this->tables['order']);
		$rows			 	= $query->result();
		$result				= $rows[0];
		$status				= (empty($result->username) ? "<span class=\"text-warning\">Not laminated yet</span>" : "<span class=\"text-success\">Laminated by " . $result->username . "</span>");
		return $status;
	}

	function get_user_process($order_id)
	{
		$this->db->select('processed_by, username');
		$this->db->where('id_order', $order_id);
		$this->db->join($this->tables['users'], $this->tables['users'] . ".id = " . $this->tables['order'] . ".processed_by", 'LEFT');
		$query			 	= $this->db->get($this->tables['order']);
		$rows			 	= $query->result();
		$result				= $rows[0];
		$status				= (empty($result->username) ? "<span class=\"text-warning\">Not processed yet</span>" : "<span class=\"text-success\">Processed by " . $result->username . "</span>");
		return $status;
	}

	function get_user_check($order_id)
	{
		$this->db->select('checked_by, username');
		$this->db->where('id_order', $order_id);
		$this->db->join($this->tables['users'], $this->tables['users'] . ".id = " . $this->tables['order'] . ".checked_by", 'LEFT');
		$query			 	= $this->db->get($this->tables['order']);
		$rows			 	= $query->result();
		$result				= $rows[0];
		$status				= (empty($result->username) ? "<span class=\"text-warning\">Not checked yet</span>" : "<span class=\"text-success\">Checked by " . $result->username . "</span>");
		return $status;
	}

	function get_user_ready($order_id)
	{
		$this->db->select('ready_by, username');
		$this->db->where('id_order', $order_id);
		$this->db->join($this->tables['users'], $this->tables['users'] . ".id = " . $this->tables['order'] . ".ready_by", 'LEFT');
		$query			 	= $this->db->get($this->tables['order']);
		$rows			 	= $query->result();
		$result				= $rows[0];
		$status				= (empty($result->username) ? "<span class=\"text-warning\">Not ready yet</span>" : "<span class=\"text-success\">Ready by " . $result->username . "</span>");
		return $status;
	}

	function get_user_deliver($order_id)
	{
		$this->db->select('delivered_by, username');
		$this->db->where('id_order', $order_id);
		$this->db->join($this->tables['users'], $this->tables['users'] . ".id = " . $this->tables['order'] . ".delivered_by", 'LEFT');
		$query			 	= $this->db->get($this->tables['order']);
		$rows			 	= $query->result();
		$result				= $rows[0];
		$status				= (empty($result->username) ? "<span class=\"text-warning\">Not delivered yet</span>" : "<span class=\"text-success\">Delivered by " . $result->username . "</span>");
		return $status;
	}

	function delete_order($order_id)
	{

		$tables = array($this->tables['order'], $this->tables['order_detail']);
		$this->db->where('id_order', $order_id);
		$this->db->delete($tables);
		return TRUE;
	}

	function update_order($order_id, $input)
	{
		$this->db->where('id_order', $order_id);
		$this->db->update($this->tables['order'], $input);
		return TRUE;
	}

	function update_order_client($order_id)
	{

		// Have to delete rows in these tables: order_detail, order_order, order_contact
		$tables = array($this->tables['order'], $this->tables['order_detail']);
		$this->db->where('id_order', $order_id);
		$this->db->delete($tables);

		// Then place new order => insert new rows, but keep rows in order_order so that REF# doesn't change.
		// So we won't need to change lines below. :)

		$client_id			= $this->ion_auth->get_user_id();
		$order_name			= $this->input->post('order_name');
		$lpo				= $this->input->post('lpo');
		$artwork_by			= $this->input->post('artwork_by');
		$contact_name		= $this->input->post('contact_name');
		$contact_email     	= $this->input->post('contact_email');
		$contact_mobile    	= $this->input->post('contact_mobile');
		$date_order			= explode("/", $this->input->post('order_date'));
		$order_date			= mktime(0, 0, 0, $date_order[1], $date_order[0], $date_order[2]);
		$date_delivery		= explode('/', $this->input->post('date_delivery'));
		$req_delivery_date	= mktime(0, 0, 0, $date_delivery[0], $date_delivery[1], $date_delivery[2]);

		$data_order 		= array(
			'id_order'			=> $order_id,
			'order_name'		=> $order_name,
			'lpo'				=> $lpo,
			'artwork_by'		=> $artwork_by,
			'client_id'			=> $client_id,
			'order_date'        => $order_date,
			'req_delivery_date' => $req_delivery_date
		);


		$this->db->insert($this->tables['order'], $data_order);
		$id_order			= $order_id;

		$detail_filename	= $this->input->post('filename');
		$detail_width		= $this->input->post('width');
		$detail_height		= $this->input->post('height');
		$detail_quantity	= $this->input->post('qty');
		$detail_material	= $this->input->post('material');
		$detail_lamination	= $this->input->post('lamination');
		$detail_quality		= $this->input->post('quality');
		$detail_finishing	= $this->input->post('finishing');
		$detail_up			= $this->input->post('up');
		$detail_extra		= $this->input->post('extra');
		$detail_notes		= $this->input->post('notes');
		for ($i = 0; $i < count($this->input->post('filename')); $i++) {
			$data_order_detail 		= array(
				'id_order'      	=> $id_order,
				'detail_filename' 	=> $detail_filename[$i],
				'detail_width' 		=> $detail_width[$i],
				'detail_height'     => $detail_height[$i],
				'detail_quantity'   => $detail_quantity[$i],
				'detail_material'   => $detail_material[$i],
				'detail_lamination' => $detail_lamination[$i],
				'detail_quality'    => $detail_quality[$i],
				'detail_finishing'  => $detail_finishing[$i],
				'detail_notes'    	=> $detail_notes[$i],
				'detail_up'    		=> $detail_up[$i],
				'detail_extra'    	=> $detail_extra[$i]
			);
			$this->db->insert($this->tables['order_detail'], $data_order_detail);
		}

		// Get contact ID
		if ($id_contact = $this->get_contact_detail($contact_name, $client_id, $contact_email, $contact_mobile)) {
			// Update order table
			$data_order_update 		= array(
				'contact_id'		=> $id_contact
			);
			$this->db->where('client_id', $client_id);
			$this->db->where('id_order', $id_order);
			$this->db->update($this->tables['order'], $data_order_update);
		} else {
			$data_order_contact 	= array(
				'client_id'			=> $client_id,
				'contact_name'      => $contact_name,
				'contact_email'     => $contact_email,
				'contact_mobile'    => $contact_mobile
			);
			$this->db->insert($this->tables['order_contact'], $data_order_contact);
			$id_contact			= $this->db->insert_id();

			// Update order table
			$data_order_update 		= array(
				'contact_id'		=> $id_contact
			);
			$this->db->where('client_id', $client_id);
			$this->db->where('id_order', $id_order);
			$this->db->update($this->tables['order'], $data_order_update);
		}


		return TRUE;
	}

	function update_order_staff($order_id)
	{
		$id_to_update		= explode("-", $this->input->post("detail_id"));

		$detail_width		= $this->input->post('width');
		$detail_height		= $this->input->post('height');
		$detail_quantity	= $this->input->post('qty');
		$detail_up			= $this->input->post('up');
		$detail_extra		= $this->input->post('extra');
		$detail_notes		= $this->input->post('notes');
		for ($i = 0; $i < count($id_to_update); $i++) {
			$data_order_detail 		= array(
				'detail_width' 		=> $detail_width[$i],
				'detail_height'     => $detail_height[$i],
				'detail_quantity'   => $detail_quantity[$i],
				'detail_up'    		=> $detail_up[$i],
				'detail_extra'    	=> $detail_extra[$i],
				'detail_notes'    	=> $detail_notes[$i]
			);
			$this->db->where('detail_id', $id_to_update[$i]);
			$this->db->update($this->tables['order_detail'], $data_order_detail);
		}
		return TRUE;
	}

	function update_order_admin($order_id)
	{

		// Have to delete rows in these tables: order_detail, order_contact
		$tables = array($this->tables['order_detail']);
		$this->db->where('id_order', $order_id);
		$this->db->delete($tables);

		// Then update existing rows.
		// So we won't need to change lines below. :)

		$client_id			= $this->input->post('client_id');
		$order_name			= $this->input->post('order_name');
		$lpo				= $this->input->post('lpo');
		$artwork_by			= $this->input->post('artwork_by');
		$contact_name		= $this->input->post('contact_name');
		$contact_email     	= $this->input->post('contact_email');
		$contact_mobile    	= $this->input->post('contact_mobile');
		$date_order			= explode("/", $this->input->post('order_date'));
		$order_date			= mktime(0, 0, 0, $date_order[1], $date_order[0], $date_order[2]);
		$date_delivery		= explode('/', $this->input->post('date_delivery'));
		$req_delivery_date	= mktime(0, 0, 0, $date_delivery[0], $date_delivery[1], $date_delivery[2]);

		$data_order 		= array(
			'order_name'		=> $order_name,
			'client_id'			=> $client_id,
			'order_date'        => $order_date,
			'req_delivery_date' => $req_delivery_date,
			'artwork_by'		=> $artwork_by,
			'lpo'				=> $lpo,
		);


		$this->db->where('id_order', $order_id);
		$this->db->update($this->tables['order'], $data_order);
		$id_order			= $order_id;

		$detail_filename	= $this->input->post('filename');
		$detail_width		= $this->input->post('width');
		$detail_height		= $this->input->post('height');
		$detail_quantity	= $this->input->post('qty');
		$detail_material	= $this->input->post('material');
		$detail_lamination	= $this->input->post('lamination');
		$detail_quality		= $this->input->post('quality');
		$detail_finishing	= $this->input->post('finishing');
		$detail_up			= $this->input->post('up');
		$detail_extra		= $this->input->post('extra');
		$detail_notes		= $this->input->post('notes');
		for ($i = 0; $i < count($this->input->post('filename')); $i++) {
			$data_order_detail 		= array(
				'id_order'      	=> $id_order,
				'detail_filename' 	=> $detail_filename[$i],
				'detail_width' 		=> $detail_width[$i],
				'detail_height'     => $detail_height[$i],
				'detail_quantity'   => $detail_quantity[$i],
				'detail_material'   => $detail_material[$i],
				'detail_lamination' => $detail_lamination[$i],
				'detail_quality'    => $detail_quality[$i],
				'detail_finishing'  => $detail_finishing[$i],
				'detail_notes'    	=> $detail_notes[$i],
				'detail_up'    		=> $detail_up[$i],
				'detail_extra'    	=> $detail_extra[$i]
			);
			$this->db->insert($this->tables['order_detail'], $data_order_detail);
		}

		// Get contact ID
		if ($id_contact = $this->get_contact_detail($contact_name, $client_id, $contact_email, $contact_mobile)) {
			// Update order table
			$data_order_update 		= array(
				'contact_id'		=> $id_contact
			);
			$this->db->where('client_id', $client_id);
			$this->db->where('id_order', $id_order);
			$this->db->update($this->tables['order'], $data_order_update);
		} else {
			$data_order_contact 	= array(
				'client_id'			=> $client_id,
				'contact_name'      => $contact_name,
				'contact_email'     => $contact_email,
				'contact_mobile'    => $contact_mobile
			);
			$this->db->insert($this->tables['order_contact'], $data_order_contact);
			$id_contact			= $this->db->insert_id();

			// Update order table
			$data_order_update 		= array(
				'contact_id'		=> $id_contact
			);
			$this->db->where('client_id', $client_id);
			$this->db->where('id_order', $id_order);
			$this->db->update($this->tables['order'], $data_order_update);
		}


		return TRUE;
	}



	function get_material_list($id = "")
	{
		$this->db->where($this->tables['material'] . '.is_deleted', '0');
		if ($id != "") {
			$id = (int) $id;
			$this->db->where($this->tables['material'] . '.id_material', $id);
		} else {
			$material[""]	= "--";
		}
		$query			 	= $this->db->get($this->tables['material']);
		$rows			 	= $query->result();
		for ($i = 0; $i < count($rows); $i++) {
			$data_row				= $rows[$i];
			$material[$data_row->id_material] = $data_row->material_name;
		}
		return $material;
	}
	function get_lamination_list($id = "")
	{
		$this->db->where($this->tables['lamination'] . '.is_deleted', '0');
		if ($id != "") {
			$id = (int) $id;
			$this->db->where($this->tables['lamination'] . '.id_lamination', $id);
		} else {
			$lamination[""]	= "--";
		}
		$query			 	= $this->db->get($this->tables['lamination']);
		$rows			 	= $query->result();
		for ($i = 0; $i < count($rows); $i++) {
			$data_row				= $rows[$i];
			$lamination[$data_row->id_lamination] = $data_row->lamination_name;
		}
		return $lamination;
	}
	function get_quality_list($id = "")
	{
		$this->db->where($this->tables['quality'] . '.is_deleted', '0');
		if ($id != "") {
			$id = (int) $id;
			$this->db->where($this->tables['quality'] . '.id_quality', $id);
		} else {
			$quality[""]	= "--";
		}
		$query			 	= $this->db->get($this->tables['quality']);
		$rows			 	= $query->result();
		for ($i = 0; $i < count($rows); $i++) {
			$data_row				= $rows[$i];
			$quality[$data_row->id_quality] = $data_row->quality_name;
		}
		return $quality;
	}
	function get_finishing_list($id = "")
	{
		$this->db->where($this->tables['finishing'] . '.is_deleted', '0');
		if ($id != "") {
			$id = (int) $id;
			$this->db->where($this->tables['finishing'] . '.id_finishing', $id);
		} else {
			$finishing[""]	= "--";
		}
		$query			 	= $this->db->get($this->tables['finishing']);
		$rows			 	= $query->result();
		for ($i = 0; $i < count($rows); $i++) {
			$data_row				= $rows[$i];
			$finishing[$data_row->id_finishing] = $data_row->finishing_name;
		}
		return $finishing;
	}

	function update_material($id, $data)
	{
		$this->db->where('id_material', $id);
		$this->db->update($this->tables['material'], $data);
	}

	function add_material($data)
	{
		$this->db->insert($this->tables['material'], $data);
	}

	function update_lamination($id, $data)
	{
		$this->db->where('id_lamination', $id);
		$this->db->update($this->tables['lamination'], $data);
	}

	function add_lamination($data)
	{
		$this->db->insert($this->tables['lamination'], $data);
	}

	function update_quality($id, $data)
	{
		$this->db->where('id_quality', $id);
		$this->db->update($this->tables['quality'], $data);
	}

	function add_quality($data)
	{
		$this->db->insert($this->tables['quality'], $data);
	}

	function update_finishing($id, $data)
	{
		$this->db->where('id_finishing', $id);
		$this->db->update($this->tables['finishing'], $data);
	}

	function add_finishing($data)
	{
		$this->db->insert($this->tables['finishing'], $data);
	}

	function get_payment_status($order_id)
	{
		$this->db->where($this->tables['payment'] . '.order_id', $order_id);
		$query			 	= $this->db->get($this->tables['payment']);
		$rows			 	= $query->result();
		if (count($rows) == 0) {
			$data['order_id']	= $order_id;
			$data['invoiced']	= 0;
			$data['partial']	= 0;
			$data['full']		= 0;
			$this->db->insert($this->tables['payment'], $data);
		} else {
			$data_row			= $rows[0];
			$data['invoiced']	= $data_row->invoiced;
			$data['partial']	= $data_row->partial;
			$data['full']		= $data_row->full;
		}
		return $data;
	}

	function update_payment($order_id, $data)
	{
		$this->db->where('order_id', $order_id);
		$this->db->update($this->tables['payment'], $data);
	}

	function archive_order($order_id, $data)
	{
		$this->db->where('id_order', $order_id);
		$this->db->update($this->tables['order'], $data);
	}

	function get_template_list()
	{
		$query			 	= $this->db->get($this->tables['template']);
		return $query->result();
	}

	function create_template()
	{
		$json_input				= $this->input->post("post_data");
		$decoded_json			= json_decode($json_input);
		//~ $template_up 			= $this->input->post("up");
		//~ $template_extra 		= $this->input->post("extra");
		//~ $template_notes 		= $this->input->post("notes");
		$j = 0;

		if (count($decoded_json) > 0) {
			// Let's truncate the table first
			$this->db->truncate($this->tables['template']);

			$template_category = 0; // Category ID
			for ($i = 0; $i < count($decoded_json); $i++) {
				$the_row =	$decoded_json[$i];
				$template_filename		= $the_row->filename;
				// Check if the filename is not empty
				// If it's not empty, continue
				if (strlen($template_filename) > 0) {
					$template_width			= $the_row->width;
					$template_height		= $the_row->height;
					$template_quantity		= $the_row->quantity;
					$template_material		= $the_row->material;
					$template_lamination	= $the_row->lamination;
					$template_quality		= $the_row->quality;
					$template_finishing		= $the_row->finishing;
					$username				= $the_row->username;

					// This is category name
					for ($k = 0; $k < 2; $k++) {
						if (($template_width == 0) and ($template_height == 0)) {
							$template_category++;
							$j = 0;
							$template_number 	= $j;
							$data = array(
								"template_category" => $template_category,
								"template_number" => $j,
								"template_filename" => htmlentities($template_filename, ENT_QUOTES),
								"template_width" => 0,
								"template_height" => 0,
								"username" => $username,
							);
							// Make an insert query that sets username for all the templates with the same category
							$this->db->set('username', $username);
							$this->db->where('template_category', $template_category);
							$this->db->update($this->tables['template']);
						}
						// Else, it's the template content
						else {
							$data = array(
								"template_category" 	=> $template_category,
								"template_number" 	=> $j,
								"template_filename" => htmlentities($template_filename, ENT_QUOTES),
								"template_width" 	=> $template_width,
								"template_height" 	=> $template_height,
								"template_quantity" => $template_quantity,
								"template_material" => $template_material,
								"template_lamination" => $template_lamination,
								"template_quality" 	=> $template_quality,
								"template_finishing" => $template_finishing,
								"username" 			=> $username,
							);
							//~ "template_up" 		=> $template_up,
							//~ "template_extra" 	=> $template_extra,
							//~ "template_notes" 	=> $template_notes);
						}
					}
					$j++;



					$this->db->insert($this->tables['template'], $data);
				}
			}
		}
	}
	function get_material_name($id)
	{
		$this->db->where('id_material', $id);
		$query			 	= $this->db->get($this->tables['material']);
		$rows			 	= $query->result();
		$data_row			= $rows[0];
		return $data_row->material_name;
	}
}
