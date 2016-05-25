<?php

class Orders_model extends CI_Model 
{
	/*
	*	Retrieve all orders
	*	@param string $table
	* 	@param string $where
	*
	*/
	public function get_all_orders($table, $where, $per_page, $page)
	{
		//retrieve all orders
		$this->db->from($table);
		$this->db->select('orders.*, orders.order_status_id AS status,customer.customer_first_name AS first_name, customer.customer_surname AS other_names, order_status.order_status_name');
		$this->db->where($where);
		$this->db->order_by('orders.order_created, orders.order_number');
		$query = $this->db->get('', $per_page, $page);
		
		return $query;
	}
	/*
	*	Retrieve latest orders
	*	@param string $table
	* 	@param string $where
	*
	*/
	public function get_latest_orders()
	{
		$where = 'orders.order_status_id != 4 AND orders.order_status_id = order_status.order_status_id AND customer.customer_id = orders.customer_id AND orders.dobi_id = '.$this->session->userdata('dobi_id');
		$table = 'orders, order_status, customer';
		
		//retrieve all orders
		$this->db->from($table);
		$this->db->select('orders.*, orders.order_status_id AS status,customer.customer_first_name AS first_name, customer.customer_surname AS other_names, order_status.order_status_name');
		$this->db->where($where);
		$this->db->order_by('orders.order_created', 'DESC');
		$query = $this->db->get('', 20);
		
		return $query;
	}
	
	/*
	*	Retrieve all orders of a user
	*
	*/
	public function get_user_orders($customer_id)
	{
		$this->db->select('dobi.*, payment_method.payment_method_name, orders.*, order_status.order_status_name');
		$this->db->where('orders.dobi_id = dobi.dobi_id AND orders.payment_method_id = payment_method.payment_method_id AND orders.order_status_id = order_status.order_status_id AND orders.customer_id = '.$customer_id);
		$this->db->order_by('order_created', 'DESC');
		$query = $this->db->get('orders, order_status, payment_method, dobi');
		
		return $query;
	}
	
	/*
	*	Retrieve all wishlist items of a user
	*
	*/
	public function get_user_wishlist($customer_id)
	{
		$this->db->select('brand.brand_name, product.*, wishlist.date_added, wishlist.wishlist_id');
		$this->db->where('product.brand_id = brand.brand_id AND product.product_id = wishlist.product_id AND wishlist.customer_id = '.$customer_id);
		$this->db->order_by('wishlist.date_added', 'DESC');
		$query = $this->db->get('product, wishlist, brand');
		
		return $query;
	}
	
	/*
	*	Retrieve an order
	*
	*/
	public function get_order($order_id)
	{
		$this->db->select('*');
		$this->db->where('orders.order_status_id = order_status.order_status_id AND orders.order_id = '.$order_id);
		$query = $this->db->get('orders, order_status');
		
		return $query;
	}
	
	/*
	*	Retrieve all order items of an order
	*
	*/
	public function get_order_items($order_id)
	{
		$this->db->select('category.category_name, category.category_image_name, order_item.*');
		$this->db->where('category.category_id = order_item.category_id AND order_item.order_id = '.$order_id);
		$query = $this->db->get('order_item, category');
		
		return $query;
	}
	
	/*
	*	Retrieve all order item featuress of an order item
	*
	*/
	public function get_order_item_features($order_item_id)
	{
		$this->db->select('order_item_feature.*, product_feature.feature_value, product_feature.thumb, feature.feature_name');
		$this->db->where('product_feature.feature_id = feature.feature_id AND order_item_feature.product_feature_id = product_feature.product_feature_id AND order_item_feature.order_item_id = '.$order_item_id);
		$query = $this->db->get('order_item_feature, product_feature, feature');
		
		return $query;
	}
	
	/*
	*	Create order number
	*
	*/
	public function create_order_number()
	{
		//select product code
		$this->db->from('orders');
		$this->db->where("order_number LIKE 'ORD".date('y')."-%'");
		$this->db->select('MAX(order_number) AS number');
		$query = $this->db->get();
		$preffix = "ORD".date('y').'-';
		
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
	
	/*
	*	Create the total cost of an order
	*	@param int order_id
	*
	*/
	public function calculate_order_cost($order_id)
	{
		//select product code
		$this->db->from('order_item');
		$this->db->where('order_id = '.$order_id);
		$this->db->select('SUM(order_item_price * order_item_quantity) AS total_cost');
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
	*	Add a new order
	*
	*/
	public function add_order()
	{
		$order_number = $this->create_order_number();
		
		$data = array(
				'order_number'=>$order_number,
				'user_id'=>$this->input->post('user_id'),
				'payment_method'=>$this->input->post('payment_method'),
				'order_status'=>1,
				'order_instructions'=>$this->input->post('order_instructions'),
				'created'=>date('Y-m-d H:i:s'),
				'created_by'=>$this->session->userdata('user_id'),
				'modified_by'=>$this->session->userdata('user_id')
			);
			
		if($this->db->insert('orders', $data))
		{
			return $this->db->insert_id();
		}
		else{
			return FALSE;
		}
	}
	
	/*
	*	Update an order
	*	@param int $order_id
	*
	*/
	public function _update_order($order_id)
	{
		$order_number = $this->create_order_number();
		
		$data = array(
				'user_id'=>$this->input->post('user_id'),
				'payment_method'=>$this->input->post('payment_method'),
				'order_status'=>1,
				'order_instructions'=>$this->input->post('order_instructions'),
				'modified_by'=>$this->session->userdata('user_id')
			);
		
		$this->db->where('order_id', $order_id);
		if($this->db->update('orders', $data))
		{
			return TRUE;
		}
		else{
			return FALSE;
		}
	}
	/*
	*	Retrieve all orders
	*	@param string $table
	* 	@param string $where
	*
	*/
	public function get_payment_methods()
	{
		//retrieve all orders
		$this->db->from('payment_method');
		$this->db->select('*');
		$this->db->order_by('payment_method_name');
		$query = $this->db->get();
		
		return $query;
	}

	/*
	*	Retrieve all orders
	*	@param string $table
	* 	@param string $where
	*
	*/
	public function get_order_status()
	{
		//retrieve all orders
		$this->db->from('order_status');
		$this->db->select('*');
		$this->db->order_by('order_status_name');
		$query = $this->db->get();
		
		return $query;
	}
	
	/*
	*	Add a order product
	*
	*/
	public function add_product($order_id, $product_id, $quantity, $price)
	{
		//Check if item exists
		$this->db->select('*');
		$this->db->where('product_id = '.$product_id.' AND order_id = '.$order_id);
		$query = $this->db->get('order_item');
		
		if($query->num_rows() > 0)
		{
			$result = $query->row();
			$qty = $result->quantity;
			
			$quantity += $qty;
			
			$data = array(
					'quantity'=>$quantity
				);
				
			$this->db->where('product_id = '.$product_id.' AND order_id = '.$order_id);
			if($this->db->update('order_item', $data))
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
					'order_id'=>$order_id,
					'product_id'=>$product_id,
					'quantity'=>$quantity,
					'price'=>$price
				);
				
			if($this->db->insert('order_item', $data))
			{
				return TRUE;
			}
			else{
				return FALSE;
			}
		}
	}
	
	/*
	*	Update an order item
	*
	*/
	public function update_cart($order_item_id, $quantity)
	{
		$data = array(
					'quantity'=>$quantity
				);
				
		$this->db->where('order_item_id = '.$order_item_id);
		if($this->db->update('order_item', $data))
		{
			return TRUE;
		}
		else{
			return FALSE;
		}
	}
	
	/*
	*	Delete an existing order item
	*	@param int $product_id
	*
	*/
	public function delete_order_item($order_item_id)
	{
		if($this->db->delete('order_item', array('order_item_id' => $order_item_id)))
		{
			return TRUE;
		}
		else{
			return FALSE;
		}
	}
	
	public function request_cancel($order_number, $customer_id)
	{
		$this->db->where(array(
				'order_number' => $order_number,
				'customer_id' => $customer_id
			)
		);
		$data['order_status_id'] = 6;
		if($this->db->update('orders', $data))
		{
			return TRUE;
		}
		else
		{
			return FALSE;
		}
	}
	
	public function get_customer($customer_id)
	{
		$this->db->where('customer_id', $customer_id);
		$query = $this->db->get('customer');
		
		return $query;
	}
	
	/*
	*
	*	Refund order
	*
	*/
	public function refund_order($order_number, $dobi_id)
	{
		$dobi_data = array();
		$invoice_items = array();
		$order_details = array();
		$created_orders = '';
		
		$this->db->where(array('order_number' => $order_number, 'dobi_id' => $dobi_id));
		$query = $this->db->get('orders');
		
		if($query->num_rows() > 0)
		{
			$row = $query->row();
			
			$customer_id = $row->customer_id;
			$order_id = $row->order_id;
			$customer_query = $this->get_customer($customer_id);
			$customer_email = '';
			$customer_total = 0;
			$total_price = 0;
			$total_additional_price = 0;
			
			if($customer_query->num_rows() > 0)
			{
				$row = $customer_query->row();
				$customer_email = $row->customer_email;
			}
			$created_orders .= $order_id.'-';
			
			//check number of order items
			$order_items = $this->orders_model->get_order_items($order_id);
			$total_order_items = $order_items->num_rows();
			
			if($total_order_items > 0)
			{
				foreach($order_items->result() as $res)
				{
					$order_item_id = $res->order_item_id;
					$product_id = $res->product_id;
					$order_item_quantity = $res->order_item_quantity;
					$order_item_price = $res->order_item_price;
					$product_name = $res->product_name;
					$total_price += ($order_item_quantity * $order_item_price);
					
					//features
					$this->db->select('order_item_feature.*, product_feature.feature_value, product_feature.thumb, feature.feature_name');
					$this->db->where('product_feature.feature_id = feature.feature_id AND order_item_feature.product_feature_id = product_feature.product_feature_id AND order_item_feature.order_item_id = '.$order_item_id);
					$order_item_features = $this->db->get('order_item_feature, product_feature, feature');
					
					if($order_item_features->num_rows() > 0)
					{
						foreach($order_item_features->result() as $feat)
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
							"identifier" => $order_item_id
						)
					);
				}
			}
			$total = $total_price + $total_additional_price;
			//add dobi data to the dobi_data array
			array_push($dobi_data, array(
					'email' => $customer_email, 
					'amount' => $total
				)
			);
			array_push($order_details, array(
					'receiver' => $customer_email, 
					'invoiceData' => array(
						'item' => $invoice_items
					)
				)
			);
		}
		
		//create return data
		$return['dobi_data'] = $dobi_data;
		$return['order_details'] = $order_details;
		$return['created_orders'] = $created_orders;
		
		return $return;
	}
	
	public function create_order($dobi_id, $status = 4)
	{
		$order_number = $this->orders_model->create_order_number();
		$data = array(
					'customer_id'		=>	$this->session->userdata('customer_id'),
					'order_created'		=>	date('Y-m-d H:i:s'),
					'order_status_id'	=>	$status,
					'order_number'		=>	$order_number,
					'dobi_id'			=>	$dobi_id,
					'order_instructions'=>	$this->session->userdata('order_instructions'),
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
		
		if($this->db->insert('orders', $data))
		{
			//get order id
			$order_id = $this->db->insert_id();
			
			//save order items
			foreach ($this->cart->contents() as $items): 

				//save order item
				$data2 = array(
					'category_id' => $items['id'],
					'order_id' => $order_id,
					'order_item_quantity' => $items['qty'],
					'order_item_price' => $items['price']
				);
				
				if($this->db->insert('order_item', $data2))
				{
					$order_item_id = $this->db->insert_id();
				}
	
			endforeach;
			
			return $order_id;
		}
		
		else
		{
			return FALSE;
		}
	}
	
	public function send_mpesa_receipt_email($order_id)
	{
		//send account registration email
		$this->load->library('Mandrill', $this->config->item('mandrill_key'));
		$this->load->model('site/email_model');
		
		//get customer details
		$where = "customer.customer_id = orders.customer_id AND orders.order_id = '".$order_id."'";
		$this->db->select('customer.*, orders.order_number');
		$this->db->where($where);
		$query = $this->db->get('customer, orders');
		
		//get dobi details
		$where = "dobi.dobi_id = orders.dobi_id AND orders.order_id = '".$order_id."'";
		$this->db->select('dobi.*, orders.order_number');
		$this->db->where($where);
		$query_dobi = $this->db->get('dobi, orders');
		
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
			
			$clothes = '<table border="1">';
			//get dobi details
			$where = "order_item.category_id = category.category_id AND order_item.order_id = '".$order_id."'";
			$this->db->select('order_item.*, category.category_name');
			$this->db->where($where);
			$query_clothes = $this->db->get('category, order_item');
			
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
					$order_item_quantity = $res->order_item_quantity;
					$order_item_price = $res->order_item_price;
					$total = $order_item_quantity * $order_item_price;
					$count++;
					$grand_total += $total;
					
					$clothes .= '
						<tr>
							<td>'.$count.'</td>
							<td>'.$category_name.'</td>
							<td>'.$order_item_price.'</td>
							<td>'.$order_item_quantity.'</td>
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
			$client_email = $row->customer_email;
			$client_username = $row->customer_first_name;
			$client_username = $row->customer_first_name;
			$order_number = $row->order_number;
			$sender_email = 'info@dobi.co.ke';
			$subject = 'Your payment has been received';
			$message = '
				<p>Your payment for order number '.$order_number.' has been received and your clothes queued for washing. Here is your order summary:</p>
				'.$clothes.'
				<p>Your dobi is '.$dobi_first_name.' , '.$location.' '.$street.' '.$estate.' '.$house.' phone number is '.$dobi_phone.'. Kindly be in touch with them to schedule laundry delivery and cleaning.</p>
								
				<p>To review your order please log into your account on '.site_url().'</p>
			';
			$shopping = "<p>Happy washing<br/>The Dobi Team</p>";
			$from = 'Dobi';
			
			$button = '<a class="mcnButton " title="Find a dobi" href="'.site_url().'customer-login" target="_blank" style="font-weight: bold;letter-spacing: normal;line-height: 100%;text-align: center;text-decoration: none;color: #FFFFFF;">Find a dobi</a>';
			$response = $this->email_model->send_mandrill_mail($client_email, "Hi ".$client_username, $subject, $message, $sender_email, $shopping, $from, $button, $cc = $dobi_email);
			
			return $response;
		}		
	}
}