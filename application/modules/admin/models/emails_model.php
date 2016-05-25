<?php

class Emails_model extends CI_Model 
{	
	/*
	*	Retrieve all emails
	*
	*/
	public function all_emails()
	{
		$this->db->where('email_status = 1');
		$query = $this->db->get('email');
		
		return $query;
	}
	/*
	*	Retrieve all child emails
	*
	*/
	public function all_child_emails()
	{
		$this->db->where('email_status = 1 AND email_parent != 0');
		$this->db->order_by('email_name');
		$query = $this->db->get('email');
		
		return $query;
	}
	/*
	*	Retrieve latest email
	*
	*/
	public function latest_email()
	{
		$this->db->limit(1);
		$this->db->order_by('created', 'DESC');
		$query = $this->db->get('email');
		
		return $query;
	}
	/*
	*	Retrieve all parent emails
	*
	*/
	public function email_categories()
	{
		$this->db->order_by('email_category_name', 'ASC');
		$query = $this->db->get('email_category');
		
		return $query;
	}
	
	/*
	*	Retrieve all emails
	*	@param string $table
	* 	@param string $where
	*
	*/
	public function get_all_emails($table, $where, $per_page, $page, $order = 'email_name', $order_method = 'ASC')
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
	*	Add a new email
	*	@param string $image_name
	*
	*/
	public function add_email()
	{
		$email_category_id = $this->input->post('email_category_id');
		
		if($email_category_id == 1)
		{
			//deactivate all other emails
			if($this->db->update('email', array('email_status' => 0)))
			{
			}
		}
		$data = array(
				'email_category_id'=>$this->input->post('email_category_id'),
				'email_content'=>$this->input->post('email_content'),
				'email_status'=>$this->input->post('email_status'),
				'created'=>date('Y-m-d H:i:s'),
				'created_by'=>$this->session->userdata('user_id'),
				'modified_by'=>$this->session->userdata('user_id')
			);
			
		if($this->db->insert('email', $data))
		{
			return TRUE;
		}
		else{
			return FALSE;
		}
	}
	
	/*
	*	Update an existing email
	*	@param string $image_name
	*	@param int $email_id
	*
	*/
	public function update_email($email_id)
	{
		$email_category_id = $this->input->post('email_category_id');
		
		if($email_category_id == 1)
		{
			//deactivate all other emails
			if($this->db->update('email', array('email_status' => 0)))
			{
			}
		}
		$data = array(
				'email_category_id'=>$this->input->post('email_category_id'),
				'email_content'=>$this->input->post('email_content'),
				'email_status'=>$this->input->post('email_status'),
				'modified_by'=>$this->session->userdata('user_id')
			);
			
		$this->db->where('email_id', $email_id);
		if($this->db->update('email', $data))
		{
			return TRUE;
		}
		else{
			return FALSE;
		}
	}
	
	/*
	*	get a single email's children
	*	@param int $email_id
	*
	*/
	public function get_sub_emails($email_id)
	{
		//retrieve all users
		$this->db->from('email');
		$this->db->select('*');
		$this->db->where('email_parent = '.$email_id);
		$query = $this->db->get();
		
		return $query;
	}
	
	/*
	*	get a single email's details
	*	@param int $email_id
	*
	*/
	public function get_email($email_id)
	{
		//retrieve all users
		$this->db->from('email');
		$this->db->select('*');
		$this->db->where('email_id = '.$email_id);
		$query = $this->db->get();
		
		return $query;
	}
	
	/*
	*	Delete an existing email
	*	@param int $email_id
	*
	*/
	public function delete_email($email_id)
	{
		if($this->db->delete('email', array('email_id' => $email_id)))
		{
			return TRUE;
		}
		else{
			return FALSE;
		}
	}
	
	/*
	*	Activate a deactivated email
	*	@param int $email_id
	*
	*/
	public function activate_email($email_id)
	{
		$email_category_id = 1;
		
		if($email_category_id == 1)
		{
			//deactivate all other emails
			if($this->db->update('email', array('email_status' => 0)))
			{
			}
		}
		$data = array(
				'email_status' => 1
			);
		$this->db->where('email_id', $email_id);
		
		if($this->db->update('email', $data))
		{
			return TRUE;
		}
		else{
			return FALSE;
		}
	}
	
	/*
	*	Deactivate an activated email
	*	@param int $email_id
	*
	*/
	public function deactivate_email($email_id)
	{
		$data = array(
				'email_status' => 0
			);
		$this->db->where('email_id', $email_id);
		
		if($this->db->update('email', $data))
		{
			return TRUE;
		}
		else{
			return FALSE;
		}
	}
	
	public function get_automated_email($email_category_id)
	{
		//retrieve all users
		$this->db->from('email');
		$this->db->select('*');
		$this->db->where('email_status = 1 AND email_category_id = '.$email_category_id);
		$query = $this->db->get();
		
		return $query;
	}
}
?>