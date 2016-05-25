<?php

class Accounts_model extends CI_Model 
{
	/*
	*	Retrieve all categories
	*	@param string $table
	* 	@param string $where
	*
	*/
	public function get_accounts_receivable($table, $where, $per_page, $page, $order = 'order_created', $order_method = 'DESC')
	{
		//retrieve all users
		$this->db->from($table);
		$this->db->select('*');
		$this->db->where($where);
		$this->db->order_by($order, $order_method);
		$query = $this->db->get('', $per_page, $page);
		
		return $query;
	}
	
	public function get_payment_methods()
	{
		return $this->db->get('payment_method');
	}
	
	public function get_order_statuses()
	{
		return $this->db->get('order_status');
	}
	
	public function confirm_payment($order_id)
	{
		$data = array(
				'payment_status' => 3
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
	
	public function unconfirm_payment($order_id)
	{
		$data = array(
				'payment_status' => 2
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
	
	public function receipt_payment($order_id)
	{
		$data = array(
				'payment_status' => 4
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
	
	public function get_total_amount($table, $where)
	{
		//retrieve all users
		$this->db->from($table);
		$this->db->select('*');
		$this->db->where($where);
		$query = $this->db->get();
		
		$total = 0;
		$items = $query->num_rows();
		if($query->num_rows() > 0)
		{
			foreach ($query->result() as $row)
			{
				//order details
				$order_id = $row->order_id;
				$fold_cost = $row->fold_cost;
				$iron_cost = $row->iron_cost;
				$delivery_cost = $row->delivery_cost;
				$total_additional_price = $fold_cost + $iron_cost + $delivery_cost;
				
				//order items
				$order_items = $this->orders_model->get_order_items($order_id);
				$order_items = $order_items->result();
				$total_price = 0;
				foreach($order_items as $res)
				{
					$order_item_id = $res->order_item_id;
					$category = $res->category_name;
					$quantity = $res->order_item_quantity;
					$price = $res->order_item_price;
					
					$total_price += ($quantity * $price);
				}
				$order_total = $total_price + $total_additional_price;
				$minimal_order_charge = 0;
				if($order_total < 1000)
				{
					$minimal_order_charge = 1000 - $order_total;
					$order_total = 1000;
				}
				
				$total += $order_total;
			}
		}
		
		$return['amount'] = $total;
		$return['items'] = $items;
		
		return $return;
	}
}
?>