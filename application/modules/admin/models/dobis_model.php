<?php

class Dobis_model extends CI_Model 
{	
	/*
	*	Retrieve all dobis
	*
	*/
	public function all_dobis()
	{
		$this->db->where('dobi_status = 1');
		$query = $this->db->get('dobi');
		
		return $query;
	}
	/*
	*	Retrieve all child dobis
	*
	*/
	public function all_child_dobis()
	{
		$this->db->where('dobi_status = 1 AND dobi_parent != 0');
		$this->db->order_by('dobi_name');
		$query = $this->db->get('dobi');
		
		return $query;
	}
	/*
	*	Retrieve latest dobi
	*
	*/
	public function latest_dobi()
	{
		$this->db->limit(1);
		$this->db->order_by('created', 'DESC');
		$query = $this->db->get('dobi');
		
		return $query;
	}
	/*
	*	Retrieve all parent dobis
	*
	*/
	public function all_parent_dobis()
	{
		$this->db->where('dobi_status = 1 AND dobi_parent = 0');
		$this->db->order_by('dobi_name', 'ASC');
		$query = $this->db->get('dobi');
		
		return $query;
	}
	
	/*
	*	Retrieve all dobis
	*	@param string $table
	* 	@param string $where
	*
	*/
	public function get_all_dobis($table, $where, $per_page, $page, $order = 'dobi_name', $order_method = 'ASC')
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
	*	Add a new dobi
	*	@param string $image_name
	*
	*/
	public function add_dobi($image_name)
	{
		$data = array(
				'dobi_name'=>ucwords(strtolower($this->input->post('dobi_name'))),
				'dobi_parent'=>$this->input->post('dobi_parent'),
				'dobi_preffix'=>strtoupper($this->input->post('dobi_preffix')),
				'dobi_status'=>$this->input->post('dobi_status'),
				'created'=>date('Y-m-d H:i:s'),
				'created_by'=>$this->session->userdata('user_id'),
				'modified_by'=>$this->session->userdata('user_id'),
				'dobi_image_name'=>$image_name
			);
			
		if($this->db->insert('dobi', $data))
		{
			return TRUE;
		}
		else{
			return FALSE;
		}
	}
	
	/*
	*	Update an existing dobi
	*	@param string $image_name
	*	@param int $dobi_id
	*
	*/
	public function update_dobi($image_name, $dobi_id)
	{
		$data = array(
				'dobi_name'=>ucwords(strtolower($this->input->post('dobi_name'))),
				'dobi_parent'=>$this->input->post('dobi_parent'),
				'dobi_preffix'=>strtoupper($this->input->post('dobi_preffix')),
				'dobi_status'=>$this->input->post('dobi_status'),
				'modified_by'=>$this->session->userdata('user_id'),
				'dobi_image_name'=>$image_name
			);
			
		$this->db->where('dobi_id', $dobi_id);
		if($this->db->update('dobi', $data))
		{
			return TRUE;
		}
		else{
			return FALSE;
		}
	}
	
	/*
	*	get a single dobi's children
	*	@param int $dobi_id
	*
	*/
	public function get_sub_dobis($dobi_id)
	{
		//retrieve all users
		$this->db->from('dobi');
		$this->db->select('*');
		$this->db->where('dobi_parent = '.$dobi_id);
		$query = $this->db->get();
		
		return $query;
	}
	
	/*
	*	get a single dobi's details
	*	@param int $dobi_id
	*
	*/
	public function get_dobi($dobi_id)
	{
		//retrieve all users
		$this->db->from('dobi');
		$this->db->select('*');
		$this->db->where('dobi_id = '.$dobi_id);
		$query = $this->db->get();
		
		return $query;
	}
	
	/*
	*	Delete an existing dobi
	*	@param int $dobi_id
	*
	*/
	public function delete_dobi($dobi_id)
	{
		if($this->db->delete('dobi', array('dobi_id' => $dobi_id)))
		{
			return TRUE;
		}
		else{
			return FALSE;
		}
	}
	
	/*
	*	Activate a deactivated dobi
	*	@param int $dobi_id
	*
	*/
	public function activate_dobi($dobi_id)
	{
		$data = array(
				'dobi_status' => 1
			);
		$this->db->where('dobi_id', $dobi_id);
		
		if($this->db->update('dobi', $data))
		{
			return TRUE;
		}
		else{
			return FALSE;
		}
	}
	
	/*
	*	Deactivate an activated dobi
	*	@param int $dobi_id
	*
	*/
	public function deactivate_dobi($dobi_id)
	{
		$data = array(
				'dobi_status' => 0
			);
		$this->db->where('dobi_id', $dobi_id);
		
		if($this->db->update('dobi', $data))
		{
			return TRUE;
		}
		else{
			return FALSE;
		}
	}
}
?>