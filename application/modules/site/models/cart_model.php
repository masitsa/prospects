<?php

class Cart_model extends CI_Model 
{
    
	/*
	*
	*	Add an item to the cart
	*	@param int product_id
	*
	*/
	public function add_item($category_id)
	{
		//check if the item exists in the cart
		$in_cart = $this->item_in_cart($category_id);
		//If item is already in cart update its quantity
		if($in_cart['result'])
		{
			$row_id = $in_cart['row_id'];
			$quantity = $in_cart['quantity'] + 1;
			
			$data = array(
               'rowid' => $row_id,
               'qty'   => $quantity
            );

			$this->cart->update($data); 
		}
		
		//otherwise add a new product to cart
		else
		{
			//get product details
			$category_details = $this->categories_model->get_category($category_id);
			
			if($category_details->num_rows() > 0)
			{
				$category = $category_details->row();
				
				//calculate selling price
				$selling_price = $category->category_price;
				
				//add product to cart
				$data = array(
					   'id'      => $category_id,
					   'qty'     => 1,
					   'price'   => $selling_price,
					   'name'    => $this->clean($category->category_name)
					   //'options' => array('Size' => 'L', 'Color' => 'Red')
					);
				
				$this->cart->insert($data);
			}
		}
		
		return TRUE;
	}
	
	function clean($string)
	{
	   $string = str_replace(' ', '-', $string); // Replaces all spaces with hyphens.
	
	   return preg_replace('/[^A-Za-z0-9\-]/', '', $string); // Removes special chars.
	}
    
	/*
	*
	*	Check if cart contains a particular product
	*	@param int product_id
	*
	*/
	public function item_in_cart($category_id)
	{
		$data['result'] = FALSE;
		foreach ($this->cart->contents() as $items): 

			$cart_category_id = $items['id'];
			
			if($cart_category_id == $category_id)
			{
				$data['result'] = TRUE;
				$data['row_id'] = $items['rowid'];
				$data['quantity'] = $items['qty'];
				
				break;
			}
		
		endforeach; 
		
		return $data;
	}
    
	/*
	*
	*	Get the items in cart
	*
	*/
	public function get_cart()
	{
		//category image location & path
		$categories_path = realpath(APPPATH . '../assets/categories');
		$categories_location = base_url().'assets/categories/';
		
		$cart = '<table class="cart"><tbody>';
		
		foreach ($this->cart->contents() as $items): 
			
			$cart_category_id = $items['id'];
			
			//get category details
			$category_details = $this->categories_model->get_category($cart_category_id);
			
			if($category_details->num_rows() > 0)
			{
				$category = $category_details->row();
				
				$category_thumb = $category->category_image_name;
				$image = $this->site_model->image_display($categories_path, $categories_location, $category_thumb);
				$total = number_format($items['qty']*$items['price'], 0, '.', ',');
			
				$cart .= '
					<tr>
						<td class="product-thumbnail">
							<a href="'.site_url().'basket">
								<img width="100" height="100" src="'.$image.'" alt="'.$items['name'].'"> 
							</a>
						</td>
						<td class="product-name">
							<a href="'.site_url().'basket">'.$items['name'].'<br><span class="amount"><strong>Kes '.number_format($items['price'], 0).' x '.$items['qty'].' Kes '.$total.'</strong></span></a>
						</td>
						<td class="product-actions">
							<a href="'.$items['rowid'].'" class="remove delete_cart_item" title="Remove this item">
								<i class="fa fa-times"></i>
							</a>
						</td>
					</tr>
				';
			}
		
		endforeach; 
		
		$cart .= '</tbody></table>';
		
		return $cart;
	}
    
	/*
	*
	*	Delete an item from the cart
	*	@param int row_id
	*
	*/
	public function delete_cart_item($row_id)
	{
		$data = array(
		   'rowid' => $row_id,
		   'qty'   => 0
		);

		$this->cart->update($data); 
		
		return TRUE;
	}
    
	/*
	*
	*	Update the cart
	*	@param int product_id
	*
	*/
	public function update_cart($row_id)
	{
		$update_quantity = $this->input->post('quantity');
		
		$data = array(
			   'rowid' => $row_id,
			   'qty'   => $update_quantity
			);

		$this->cart->update($data);
		
		return TRUE;
	}
    
	/*
	*
	*	Save the cart items to the db
	*
	*/
	public function save_order()
	{
		//get order number
		$order_number = $this->orders_model->create_order_number();
		
		//create order
		$data = array(
					'user_id'=>$this->session->userdata('user_id'),
					'created'=>date('Y-m-d H:i:s'),
					'order_instructions'=>$this->session->userdata('delivery_instructions'),
					'payment_method'=>$this->session->userdata('payment_option'),
					'order_number'=>$order_number,
					'created_by'=>$this->session->userdata('user_id')
				);
				
		if($this->db->insert('orders', $data))
		{
			$order_id = $this->db->insert_id();
			
			//save order items
			foreach ($this->cart->contents() as $items): 
	
				$cart_product_id = $items['id'];
				$quantity = $items['qty'];
				$price = $items['price'];
				
				$data = array(
						'product_id'=>$cart_product_id,
						'order_id'=>$order_id,
						'quantity'=>$quantity,
						'price'=>$price
					);
					
				if($this->db->insert('order_item', $data))
				{
					
				}
			
			endforeach; 
			
			//remove session data
			$array_items = array('delivery_instructions' => '', 'payment_option' => '');
			$this->session->unset_userdata($array_items);
			
			//clear the shopping cart
			$this->cart->destroy();
		}
		
		return TRUE;
	}
	
	public function total_items_in_cart()
	{
		return $this->cart->total_items();
	}
}

