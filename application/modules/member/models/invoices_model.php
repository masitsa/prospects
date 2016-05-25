<?php

class Invoices_model extends CI_Model 
{
	
	public function create_new_member_invoice($member_id)
	{
		$invoice_number = $this->invoices_model->create_invoice_number();
		$newdata = array(
			   'member_id'				=> $member_id,
			   'invoice_number'				=> $invoice_number,
			   'invoice_date'				=> date('Y-m-d'),
			   'invoice_status'				=> 0,
			   'created'     				=> date('Y-m-d H:i:s')
		   );

		if($this->db->insert('invoice', $newdata))
		{
			$invoice_id = $this->db->insert_id();
			$items = array(
				   'invoice_id'					=> $invoice_id,
				   'invoice_item_amount'		=> 3000,
				   'invoice_item_description'	=> 'Entrance Fee'
			   );
	
			if($this->db->insert('invoice_item', $items))
			{
				$items = array(
					   'invoice_id'					=> $invoice_id,
					   'invoice_item_amount'		=> 12000,
					   'invoice_item_description'	=> 'Annual subscription'
				   );
		
				if($this->db->insert('invoice_item', $items))
				{
					return TRUE;
				}
				
				else
				{
					return FALSE;
				}
			}
			
			else
			{
				return FALSE;
			}
		}
		else
		{
			return FALSE;
		}
	}
	
	public function create_member_invoice($member_id)
	{
		$invoice_number = $this->invoices_model->create_invoice_number();
		$newdata = array(
			   'member_id'				=> $member_id,
			   'invoice_number'				=> $invoice_number,
			   'invoice_date'				=> date('Y-m-d'),
			   'invoice_status'				=> 0,
			   'created'     				=> date('Y-m-d H:i:s')
		   );

		if($this->db->insert('invoice', $newdata))
		{
			$invoice_id = $this->db->insert_id();
			$items = array(
				   'invoice_id'					=> $invoice_id,
				   'invoice_item_amount'		=> 12000,
					'invoice_item_description'	=> 'Annual subscription'
			   );
	
			if($this->db->insert('invoice_item', $items))
			{
				return TRUE;
			}
			
			else
			{
				return FALSE;
			}
		}
		else
		{
			return FALSE;
		}
	}
	
	public function is_invoice_due($member_id)
	{
		$this->db->where('member_id', $member_id);
		$this->db->order_by('invoice_date', 'DESC');
		$this->db->limit(1);
		$query = $this->db->get('invoice');
		
		if($query->num_rows() > 0)
		{
			$row = $query->row();
			
			$invoice_date = $row->invoice_date;
			$invoice_status = $row->invoice_status;
			
			$age = $this->calculate_age($invoice_date);//echo $age;
			
			//if(($age >= 11) && ($invoice_status == 0))
			if($age >= 11)
			{
				return TRUE;
			}
			
			else
			{
				return FALSE;
			}
		}
		
		else
		{
			return TRUE;
		}
	}
	public function calculate_age($invoice_date)
	{
		$value = $this->dateDiff(date('y-m-d  h:i'), $invoice_date." 00:00", 'month');
		
		return $value;
	}
	public function dateDiff($time1, $time2, $interval) 
	{
	    // If not numeric then convert texts to unix timestamps
	    if (!is_int($time1)) {
	      $time1 = strtotime($time1);
	    }
	    if (!is_int($time2)) {
	      $time2 = strtotime($time2);
	    }
	 
	    // If time1 is bigger than time2
	    // Then swap time1 and time2
	    if ($time1 > $time2) {
	      $ttime = $time1;
	      $time1 = $time2;
	      $time2 = $ttime;
	    }
	 
	    // Set up intervals and diffs arrays
	    $intervals = array('year','month','day','hour','minute','second');
	    if (!in_array($interval, $intervals)) {
	      return false;
	    }
	 
	    $diff = 0;
	    // Create temp time from time1 and interval
	    $ttime = strtotime("+1 " . $interval, $time1);
	    // Loop until temp time is smaller than time2
	    while ($time2 >= $ttime) {
	      $time1 = $ttime;
	      $diff++;
	      // Create new temp time from time1 and interval
	      $ttime = strtotime("+1 " . $interval, $time1);
	    }
	 
	    return $diff;
  	}
	/*
	*	Retrieve all invoice
	*	@param string $table
	* 	@param string $where
	*
	*/
	public function get_all_invoice($table, $where, $per_page, $page)
	{
		//retrieve all invoice
		$this->db->from($table);
		$this->db->select('invoice.*, invoice.invoice_status_id AS status,member.member_first_name AS first_name, member.member_surname AS other_names, invoice_status.invoice_status_name');
		$this->db->where($where);
		$this->db->order_by('invoice.created, invoice.invoice_number');
		$query = $this->db->get('', $per_page, $page);
		
		return $query;
	}
	/*
	*	Retrieve latest invoice
	*	@param string $table
	* 	@param string $where
	*
	*/
	public function get_latest_invoice()
	{
		$where = 'invoice.invoice_status_id != 4 AND invoice.invoice_status_id = invoice_status.invoice_status_id AND member.member_id = invoice.member_id AND invoice.dobi_id = '.$this->session->userdata('dobi_id');
		$table = 'invoice, invoice_status, member';
		
		//retrieve all invoice
		$this->db->from($table);
		$this->db->select('invoice.*, invoice.invoice_status_id AS status,member.member_first_name AS first_name, member.member_surname AS other_names, invoice_status.invoice_status_name');
		$this->db->where($where);
		$this->db->order_by('invoice.created', 'DESC');
		$query = $this->db->get('', 20);
		
		return $query;
	}
	
	/*
	*	Retrieve all invoice of a user
	*
	*/
	public function get_user_invoices($member_id, $limit = NULL)
	{
		$this->db->select('invoice.*, invoice_status.invoice_status_name');
		$this->db->where('invoice.invoice_status = invoice_status.invoice_status_id AND invoice.member_id = '.$member_id);
		$this->db->order_by('created', 'DESC');
		if($limit != NULL)
		{
			$this->db->limit($limit);
		}
		$query = $this->db->get('invoice, invoice_status');
		
		return $query;
	}
	
	/*
	*	Retrieve all wishlist items of a user
	*
	*/
	public function get_user_wishlist($member_id)
	{
		$this->db->select('brand.brand_name, product.*, wishlist.date_added, wishlist.wishlist_id');
		$this->db->where('product.brand_id = brand.brand_id AND product.product_id = wishlist.product_id AND wishlist.member_id = '.$member_id);
		$this->db->order_by('wishlist.date_added', 'DESC');
		$query = $this->db->get('product, wishlist, brand');
		
		return $query;
	}
	
	/*
	*	Retrieve an invoice
	*
	*/
	public function get_invoice($invoice_id)
	{
		$this->db->select('*');
		$this->db->where('invoice.member_id = member.member_id AND invoice.invoice_status = invoice_status.invoice_status_id AND invoice.invoice_id = '.$invoice_id);
		$query = $this->db->get('invoice, invoice_status, member');
		
		return $query;
	}
	
	/*
	*	Retrieve all invoice items of an invoice
	*
	*/
	public function get_invoice_items($invoice_id)
	{
		$this->db->select('invoice_item.*');
		$this->db->where('invoice_item.invoice_id = '.$invoice_id);
		$query = $this->db->get('invoice_item');
		
		return $query;
	}
	
	/*
	*	Retrieve all invoice item featuress of an invoice item
	*
	*/
	public function get_invoice_item_features($invoice_item_id)
	{
		$this->db->select('invoice_item_feature.*, product_feature.feature_value, product_feature.thumb, feature.feature_name');
		$this->db->where('product_feature.feature_id = feature.feature_id AND invoice_item_feature.product_feature_id = product_feature.product_feature_id AND invoice_item_feature.invoice_item_id = '.$invoice_item_id);
		$query = $this->db->get('invoice_item_feature, product_feature, feature');
		
		return $query;
	}
	
	/*
	*	Create the total cost of an invoice
	*	@param int invoice_id
	*
	*/
	public function calculate_invoice_cost($invoice_id)
	{
		//select product code
		$this->db->from('invoice_item');
		$this->db->where('invoice_id = '.$invoice_id);
		$this->db->select('SUM(invoice_item_price * invoice_item_quantity) AS total_cost');
		$query = $this->db->get();
		
		if($query->num_rows() > 0)
		{
			$result = $query->result();
			$total_cost =  $result[0]->total_cost;
		}
		
		else
		{
			$total_cost = 0;
		}
		
		return $total_cost;
	}
	
	/*
	*	Add a new invoice
	*
	*/
	public function add_invoice()
	{
		$invoice_number = $this->create_invoice_number();
		
		$data = array(
				'invoice_number'=>$invoice_number,
				'user_id'=>$this->input->post('user_id'),
				'payment_method'=>$this->input->post('payment_method'),
				'invoice_status'=>1,
				'invoice_instructions'=>$this->input->post('invoice_instructions'),
				'created'=>date('Y-m-d H:i:s'),
				'created_by'=>$this->session->userdata('user_id'),
				'modified_by'=>$this->session->userdata('user_id')
			);
			
		if($this->db->insert('invoice', $data))
		{
			return $this->db->insert_id();
		}
		else{
			return FALSE;
		}
	}
	
	/*
	*	Update an invoice
	*	@param int $invoice_id
	*
	*/
	public function _update_invoice($invoice_id)
	{
		$invoice_number = $this->create_invoice_number();
		
		$data = array(
				'user_id'=>$this->input->post('user_id'),
				'payment_method'=>$this->input->post('payment_method'),
				'invoice_status'=>1,
				'invoice_instructions'=>$this->input->post('invoice_instructions'),
				'modified_by'=>$this->session->userdata('user_id')
			);
		
		$this->db->where('invoice_id', $invoice_id);
		if($this->db->update('invoice', $data))
		{
			return TRUE;
		}
		else{
			return FALSE;
		}
	}
	/*
	*	Retrieve all invoice
	*	@param string $table
	* 	@param string $where
	*
	*/
	public function get_payment_methods()
	{
		//retrieve all invoice
		$this->db->from('payment_method');
		$this->db->select('*');
		$this->db->order_by('payment_method_name');
		$query = $this->db->get();
		
		return $query;
	}

	/*
	*	Retrieve all invoice
	*	@param string $table
	* 	@param string $where
	*
	*/
	public function get_invoice_status()
	{
		//retrieve all invoice
		$this->db->from('invoice_status');
		$this->db->select('*');
		$this->db->order_by('invoice_status_name');
		$query = $this->db->get();
		
		return $query;
	}
	
	/*
	*	Add a invoice product
	*
	*/
	public function add_product($invoice_id, $product_id, $quantity, $price)
	{
		//Check if item exists
		$this->db->select('*');
		$this->db->where('product_id = '.$product_id.' AND invoice_id = '.$invoice_id);
		$query = $this->db->get('invoice_item');
		
		if($query->num_rows() > 0)
		{
			$result = $query->row();
			$qty = $result->quantity;
			
			$quantity += $qty;
			
			$data = array(
					'quantity'=>$quantity
				);
				
			$this->db->where('product_id = '.$product_id.' AND invoice_id = '.$invoice_id);
			if($this->db->update('invoice_item', $data))
			{
				return TRUE;
			}
			else{
				return FALSE;
			}
		}
		
		else
		{
			$data = array(
					'invoice_id'=>$invoice_id,
					'product_id'=>$product_id,
					'quantity'=>$quantity,
					'price'=>$price
				);
				
			if($this->db->insert('invoice_item', $data))
			{
				return TRUE;
			}
			else{
				return FALSE;
			}
		}
	}
	
	/*
	*	Update an invoice item
	*
	*/
	public function update_cart($invoice_item_id, $quantity)
	{
		$data = array(
					'quantity'=>$quantity
				);
				
		$this->db->where('invoice_item_id = '.$invoice_item_id);
		if($this->db->update('invoice_item', $data))
		{
			return TRUE;
		}
		else{
			return FALSE;
		}
	}
	
	/*
	*	Delete an existing invoice item
	*	@param int $product_id
	*
	*/
	public function delete_invoice_item($invoice_item_id)
	{
		if($this->db->delete('invoice_item', array('invoice_item_id' => $invoice_item_id)))
		{
			return TRUE;
		}
		else{
			return FALSE;
		}
	}
	
	public function request_cancel($invoice_number, $member_id)
	{
		$this->db->where(array(
				'invoice_number' => $invoice_number,
				'member_id' => $member_id
			)
		);
		$data['invoice_status_id'] = 6;
		if($this->db->update('invoice', $data))
		{
			return TRUE;
		}
		else
		{
			return FALSE;
		}
	}
	
	public function get_member($member_id)
	{
		$this->db->where('member_id', $member_id);
		$query = $this->db->get('member');
		
		return $query;
	}
	
	/*
	*
	*	Refund invoice
	*
	*/
	public function refund_invoice($invoice_number, $dobi_id)
	{
		$dobi_data = array();
		$invoice_items = array();
		$invoice_details = array();
		$created_invoice = '';
		
		$this->db->where(array('invoice_number' => $invoice_number, 'dobi_id' => $dobi_id));
		$query = $this->db->get('invoice');
		
		if($query->num_rows() > 0)
		{
			$row = $query->row();
			
			$member_id = $row->member_id;
			$invoice_id = $row->invoice_id;
			$member_query = $this->get_member($member_id);
			$member_email = '';
			$member_total = 0;
			$total_price = 0;
			$total_additional_price = 0;
			
			if($member_query->num_rows() > 0)
			{
				$row = $member_query->row();
				$member_email = $row->member_email;
			}
			$created_invoice .= $invoice_id.'-';
			
			//check number of invoice items
			$invoice_items = $this->invoices_model->get_invoice_items($invoice_id);
			$total_invoice_items = $invoice_items->num_rows();
			
			if($total_invoice_items > 0)
			{
				foreach($invoice_items->result() as $res)
				{
					$invoice_item_id = $res->invoice_item_id;
					$product_id = $res->product_id;
					$invoice_item_quantity = $res->invoice_item_quantity;
					$invoice_item_price = $res->invoice_item_price;
					$product_name = $res->product_name;
					$total_price += ($invoice_item_quantity * $invoice_item_price);
					
					//features
					$this->db->select('invoice_item_feature.*, product_feature.feature_value, product_feature.thumb, feature.feature_name');
					$this->db->where('product_feature.feature_id = feature.feature_id AND invoice_item_feature.product_feature_id = product_feature.product_feature_id AND invoice_item_feature.invoice_item_id = '.$invoice_item_id);
					$invoice_item_features = $this->db->get('invoice_item_feature, product_feature, feature');
					
					if($invoice_item_features->num_rows() > 0)
					{
						foreach($invoice_item_features->result() as $feat)
						{
							$product_feature_id = $feat->product_feature_id;
							$added_price = $feat->additional_price;
							$feature_name = $feat->feature_name;
							$feature_value = $feat->feature_value;
							$feature_image = $feat->thumb;
							$total_additional_price += $added_price;
						}
					}
					
					//create invoice items
					array_push($invoice_items, array(
							"name" => $product_name,
							"price" => ($total_price + $total_additional_price),
							"identifier" => $invoice_item_id
						)
					);
				}
			}
			$total = $total_price + $total_additional_price;
			//add dobi data to the dobi_data array
			array_push($dobi_data, array(
					'email' => $member_email, 
					'amount' => $total
				)
			);
			array_push($invoice_details, array(
					'receiver' => $member_email, 
					'invoiceData' => array(
						'item' => $invoice_items
					)
				)
			);
		}
		
		//create return data
		$return['dobi_data'] = $dobi_data;
		$return['invoice_details'] = $invoice_details;
		$return['created_invoice'] = $created_invoice;
		
		return $return;
	}
	
	public function create_invoice($dobi_id, $status = 4)
	{
		$invoice_number = $this->invoices_model->create_invoice_number();
		$data = array(
					'member_id'		=>	$this->session->userdata('member_id'),
					'created'	=>	date('Y-m-d H:i:s'),
					'invoice_status_id'	=>	$status,
					'invoice_number'	=>	$invoice_number,
					'dobi_id'			=>	$dobi_id,
					'invoice_instructions'=>	$this->session->userdata('invoice_instructions'),
					'payment_method_id'	=>	$this->input->post('payment_type')
				);
		
		$options = $this->session->userdata('options');
		$selected_iron_cost = 0;
		$selected_fold_cost = 0;
		$selected_delivery_cost = 0;
		
		if(is_array($options))
		{
			if(isset($options['iron_cost']))
			{
				$data['iron_cost'] = $options['iron_cost'];
				$data['iron'] = 1;
			}
			if(isset($options['fold_cost']))
			{
				$data['fold_cost'] = $options['fold_cost'];
				$data['fold'] = 1;
			}
			if(isset($options['delivery_cost']))
			{
				$data['delivery_cost'] = $options['delivery_cost'];
				$data['delivery'] = 1;
			}
		}
		
		if($this->db->insert('invoice', $data))
		{
			//get invoice id
			$invoice_id = $this->db->insert_id();
			
			//save invoice items
			foreach ($this->cart->contents() as $items): 

				//save invoice item
				$data2 = array(
					'category_id' => $items['id'],
					'invoice_id' => $invoice_id,
					'invoice_item_quantity' => $items['qty'],
					'invoice_item_price' => $items['price']
				);
				
				if($this->db->insert('invoice_item', $data2))
				{
					$invoice_item_id = $this->db->insert_id();
				}
	
			endforeach;
			
			return $invoice_id;
		}
		
		else
		{
			return FALSE;
		}
	}
	
	public function send_mpesa_receipt_email($invoice_id)
	{
		//send account registration email
		$this->load->library('Mandrill', $this->config->item('mandrill_key'));
		$this->load->model('site/email_model');
		
		//get member details
		$where = "member.member_id = invoice.member_id AND invoice.invoice_id = '".$invoice_id."'";
		$this->db->select('member.*, invoice.invoice_number');
		$this->db->where($where);
		$query = $this->db->get('member, invoice');
		
		//get dobi details
		$where = "dobi.dobi_id = invoice.dobi_id AND invoice.invoice_id = '".$invoice_id."'";
		$this->db->select('dobi.*, invoice.invoice_number');
		$this->db->where($where);
		$query_dobi = $this->db->get('dobi, invoice');
		
		if($query->num_rows() > 0)
		{
			if($query_dobi->num_rows() > 0)
			{
				$row2 = $query_dobi->row();
				$dobi_email = $row2->dobi_email;
				$dobi_first_name = $row2->dobi_first_name;
				$dobi_phone = $row2->dobi_phone;
				$location = $row2->location;
				$street = $row2->street;
				$estate = $row2->estate;
				$house = $row2->house;
			}
			
			else
			{
				$dobi_email = '';
				$dobi_first_name = '';
				$dobi_phone = '';
				$location = '';
				$street = '';
				$estate = '';
				$house = '';
			}
			
			$clothes = '<table binvoice="1">';
			//get dobi details
			$where = "invoice_item.category_id = category.category_id AND invoice_item.invoice_id = '".$invoice_id."'";
			$this->db->select('invoice_item.*, category.category_name');
			$this->db->where($where);
			$query_clothes = $this->db->get('category, invoice_item');
			
			if($query_clothes->num_rows() > 0)
			{
				$clothes .= '
					<tr>
						<th>#</th>
						<th>Item</th>
						<th>Unit price</th>
						<th>Quantity</th>
						<th>Total</th>
					</tr>
				';
				$count = 0;
				$grand_total = 0;
				foreach($query_clothes->result() as $res)
				{
					$count++;
					$category_name = $res->category_name;
					$invoice_item_quantity = $res->invoice_item_quantity;
					$invoice_item_price = $res->invoice_item_price;
					$total = $invoice_item_quantity * $invoice_item_price;
					$count++;
					$grand_total += $total;
					
					$clothes .= '
						<tr>
							<td>'.$count.'</td>
							<td>'.$category_name.'</td>
							<td>'.$invoice_item_price.'</td>
							<td>'.$invoice_item_quantity.'</td>
							<td>'.$total.'</td>
						</tr>
					';
				}
					
				$clothes .= '
					<tr>
						<td colspan="5" align="right">'.$grand_total.'</td>
					</tr>
				</table>
				';
			}
			
			$row = $query->row();
			$client_email = $row->member_email;
			$client_username = $row->member_first_name;
			$client_username = $row->member_first_name;
			$invoice_number = $row->invoice_number;
			$sender_email = 'info@dobi.co.ke';
			$subject = 'Your payment has been received';
			$message = '
				<p>Your payment for invoice number '.$invoice_number.' has been received and your clothes queued for washing. Here is your invoice summary:</p>
				'.$clothes.'
				<p>Your dobi is '.$dobi_first_name.' , '.$location.' '.$street.' '.$estate.' '.$house.' phone number is '.$dobi_phone.'. Kindly be in touch with them to schedule laundry delivery and cleaning.</p>
								
				<p>To review your invoice please log into your account on '.site_url().'</p>
			';
			$shopping = "<p>Happy washing<br/>The Dobi Team</p>";
			$from = 'Dobi';
			
			$button = '<a class="mcnButton " title="Find a dobi" href="'.site_url().'member-login" target="_blank" style="font-weight: bold;letter-spacing: normal;line-height: 100%;text-align: center;text-decoration: none;color: #FFFFFF;">Find a dobi</a>';
			$response = $this->email_model->send_mandrill_mail($client_email, "Hi ".$client_username, $subject, $message, $sender_email, $shopping, $from, $button, $cc = $dobi_email);
			
			return $response;
		}		
	}
	
	/*
	*	Create invoice number
	*
	*/
	public function create_invoice_number()
	{
		//select product code
		$this->db->from('invoice');
		$this->db->where("invoice_number LIKE 'IOD/INV/".date('y')."-%'");
		$this->db->select('MAX(invoice_number) AS number');
		$query = $this->db->get();
		$preffix = "IOD/INV/".date('y').'-';
		
		if($query->num_rows() > 0)
		{
			$result = $query->result();
			$number =  $result[0]->number;
			$real_number = str_replace($preffix, "", $number);
			$real_number++;//go to the next number
			$number = $preffix.sprintf('%06d', $real_number);
		}
		else{//start generating receipt numbers
			$number = $preffix.sprintf('%06d', 1);
		}
		
		return $number;
	}
}