<?php

class Customers_model extends CI_Model 
{	
	/*
	*	Retrieve all customers
	*
	*/
	public function all_customers()
	{
		$this->db->where('customer_status = 1');
		$query = $this->db->get('customer');
		
		return $query;
	}
	/*
	*	Retrieve all child customers
	*
	*/
	public function all_child_customers()
	{
		$this->db->where('customer_status = 1 AND customer_parent != 0');
		$this->db->order_by('customer_name');
		$query = $this->db->get('customer');
		
		return $query;
	}
	/*
	*	Retrieve latest customer
	*
	*/
	public function latest_customer()
	{
		$this->db->limit(1);
		$this->db->order_by('created', 'DESC');
		$query = $this->db->get('customer');
		
		return $query;
	}
	/*
	*	Retrieve all parent customers
	*
	*/
	public function all_parent_customers()
	{
		$this->db->where('customer_status = 1 AND customer_parent = 0');
		$this->db->order_by('customer_name', 'ASC');
		$query = $this->db->get('customer');
		
		return $query;
	}
	
	/*
	*	Retrieve all customers
	*	@param string $table
	* 	@param string $where
	*
	*/
	public function get_all_customers($table, $where, $per_page, $page, $order = 'customer_name', $order_method = 'ASC')
	{
		//retrieve all users
		$this->db->from($table);
		$this->db->select('*');
		$this->db->where($where);
		$this->db->order_by($order, $order_method);
		$query = $this->db->get('', $per_page, $page);
		
		return $query;
	}
	
	/*
	*	Add a new customer
	*	@param string $image_name
	*
	*/
	public function add_customer($image_name)
	{
		$data = array(
				'customer_name'=>ucwords(strtolower($this->input->post('customer_name'))),
				'customer_parent'=>$this->input->post('customer_parent'),
				'customer_preffix'=>strtoupper($this->input->post('customer_preffix')),
				'customer_status'=>$this->input->post('customer_status'),
				'created'=>date('Y-m-d H:i:s'),
				'created_by'=>$this->session->userdata('user_id'),
				'modified_by'=>$this->session->userdata('user_id'),
				'customer_image_name'=>$image_name
			);
			
		if($this->db->insert('customer', $data))
		{
			return TRUE;
		}
		else{
			return FALSE;
		}
	}
	
	/*
	*	Update an existing customer
	*	@param string $image_name
	*	@param int $customer_id
	*
	*/
	public function update_customer($image_name, $customer_id)
	{
		$data = array(
				'customer_name'=>ucwords(strtolower($this->input->post('customer_name'))),
				'customer_parent'=>$this->input->post('customer_parent'),
				'customer_preffix'=>strtoupper($this->input->post('customer_preffix')),
				'customer_status'=>$this->input->post('customer_status'),
				'modified_by'=>$this->session->userdata('user_id'),
				'customer_image_name'=>$image_name
			);
			
		$this->db->where('customer_id', $customer_id);
		if($this->db->update('customer', $data))
		{
			return TRUE;
		}
		else{
			return FALSE;
		}
	}
	
	/*
	*	get a single customer's children
	*	@param int $customer_id
	*
	*/
	public function get_sub_customers($customer_id)
	{
		//retrieve all users
		$this->db->from('customer');
		$this->db->select('*');
		$this->db->where('customer_parent = '.$customer_id);
		$query = $this->db->get();
		
		return $query;
	}
	
	/*
	*	get a single customer's details
	*	@param int $customer_id
	*
	*/
	public function get_customer($customer_id)
	{
		//retrieve all users
		$this->db->from('customer');
		$this->db->select('*');
		$this->db->where('customer_id = '.$customer_id);
		$query = $this->db->get();
		
		return $query;
	}
	
	/*
	*	Delete an existing customer
	*	@param int $customer_id
	*
	*/
	public function delete_customer($customer_id)
	{
		if($this->db->delete('customer', array('customer_id' => $customer_id)))
		{
			return TRUE;
		}
		else{
			return FALSE;
		}
	}
	
	/*
	*	Activate a deactivated customer
	*	@param int $customer_id
	*
	*/
	public function activate_customer($customer_id)
	{
		$data = array(
				'customer_status' => 1
			);
		$this->db->where('customer_id', $customer_id);
		
		if($this->db->update('customer', $data))
		{
			return TRUE;
		}
		else{
			return FALSE;
		}
	}
	
	/*
	*	Deactivate an activated customer
	*	@param int $customer_id
	*
	*/
	public function deactivate_customer($customer_id)
	{
		$data = array(
				'customer_status' => 0
			);
		$this->db->where('customer_id', $customer_id);
		
		if($this->db->update('customer', $data))
		{
			return TRUE;
		}
		else{
			return FALSE;
		}
	}
}
?>