<?php

class Plans_model extends CI_Model 
{	
	/*
	*	Retrieve all plans
	*
	*/
	public function all_plans()
	{
		$this->db->where('plan_status = 1');
		$query = $this->db->get('plan');
		
		return $query;
	}
	
	/*
	*	Retrieve all plans
	*	@param string $table
	* 	@param string $where
	*
	*/
	public function get_all_plans($table, $where, $per_page, $page, $order = 'plan_name', $order_method = 'ASC')
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
	*	Add a new plan
	*	@param string $image_name
	*
	*/
	public function add_plan()
	{
		$data = array(
				'plan_name'=>ucwords(strtolower($this->input->post('plan_name'))),
				'plan_description'=>$this->input->post('plan_description'),
				'plan_amount'=>$this->input->post('plan_amount'),
				'maximum_clicks'=>$this->input->post('maximum_clicks'),
				'plan_status'=>$this->input->post('plan_status'),
				'stripe_plan'=>$this->input->post('stripe_plan'),
				'created'=>date('Y-m-d H:i:s'),
				'created_by'=>$this->session->userdata('personnel_id'),
				'modified_by'=>$this->session->userdata('personnel_id')
			);
			
		if($this->db->insert('plan', $data))
		{
			//create plan in stripe
			$response = $this->stripe_model->create_plan($this->input->post('stripe_plan'), $this->input->post('plan_name'), $this->input->post('plan_amount'));
			return TRUE;
		}
		else{
			return FALSE;
		}
	}
	
	/*
	*	Update an existing plan
	*	@param string $image_name
	*	@param int $plan_id
	*
	*/
	public function update_plan($plan_id)
	{
		$data = array(
				'plan_name'=>ucwords(strtolower($this->input->post('plan_name'))),
				'plan_description'=>$this->input->post('plan_description'),
				'plan_amount'=>$this->input->post('plan_amount'),
				'maximum_clicks'=>$this->input->post('maximum_clicks'),
				'plan_status'=>$this->input->post('plan_status'),
				'stripe_plan'=>$this->input->post('stripe_plan'),
				'modified_by'=>$this->session->userdata('personnel_id')
			);
			
		$this->db->where('plan_id', $plan_id);
		if($this->db->update('plan', $data))
		{
			//update plan in stripe
			$response = $this->stripe_model->update_plan($this->input->post('stripe_plan'), $this->input->post('plan_name'), $this->input->post('plan_amount'));
			return TRUE;
		}
		else{
			return FALSE;
		}
	}
	
	/*
	*	get a single plan's details
	*	@param int $plan_id
	*
	*/
	public function get_plan($plan_id)
	{
		//retrieve all users
		$this->db->from('plan');
		$this->db->select('*');
		$this->db->where('plan_id = '.$plan_id);
		$query = $this->db->get();
		
		return $query;
	}
	
	/*
	*	Delete an existing plan
	*	@param int $plan_id
	*
	*/
	public function delete_plan($plan_id)
	{
		//delete parent
		if($this->db->delete('plan', array('plan_id' => $plan_id)))
		{
			//delete plan in stripe
			$response = $this->stripe_model->delete_plan($this->input->post('stripe_plan'));
			return TRUE;
		}
		else{
			return FALSE;
		}
	}
	
	/*
	*	Activate a deactivated plan
	*	@param int $plan_id
	*
	*/
	public function activate_plan($plan_id)
	{
		$data = array(
				'plan_status' => 1
			);
		$this->db->where('plan_id', $plan_id);
		

		if($this->db->update('plan', $data))
		{
			return TRUE;
		}
		else{
			return FALSE;
		}
	}
	
	/*
	*	Deactivate an activated plan
	*	@param int $plan_id
	*
	*/
	public function deactivate_plan($plan_id)
	{
		$data = array(
				'plan_status' => 0
			);
		$this->db->where('plan_id', $plan_id);
		
		if($this->db->update('plan', $data))
		{
			return TRUE;
		}
		else{
			return FALSE;
		}
	}
}
?>