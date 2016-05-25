<?php

class Login_model extends CI_Model 
{
	/*
	*	Check if user has logged in
	*
	*/
	public function check_login()
	{
		if($this->session->userdata('login_status'))
		{
			return TRUE;
		}
		
		else
		{
			return FALSE;
		}
	}
	/*
	*	Check if user has logged in
	*
	*/
	public function check_admin_login()
	{
		if($this->session->userdata('login_status'))
		{
			return TRUE;
		}
		
		else
		{
			return FALSE;
		}
	}
	/*
	*	Validate a user's login request
	*
	*/
	public function validate_user()
	{
		//select the user by email from the database
		$this->db->select('*');
		$this->db->where(array('personnel_username' => $this->input->post('username'), 'authorise' => 0, 'personnel_password' => md5($this->input->post('password'))));
		$query = $this->db->get('personnel');
		
		//if users exists
		if ($query->num_rows() > 0)
		{
			$result = $query->result();
			//create user's login session
			$newdata = array(
                   'login_status'     	=> TRUE,
                   'personnel_fname'  	=> $result[0]->personnel_fname,
                   'first_name'     	=> $result[0]->personnel_fname,
                   'personnel_email'	=> $result[0]->personnel_email,
                   'personnel_id'  		=> $result[0]->personnel_id
               );

			$this->session->set_userdata($newdata);
			
			//update user's last login date time
			$this->update_user_login($result[0]->personnel_id);
			return TRUE;
		}
		
		//if user doesn't exist
		else
		{
			return FALSE;
		}
	}
	
	/*
	*	Update user's last login date
	*
	*/
	private function update_user_login($personnel_id)
	{
		$session_log_insert = array(
			"personnel_id" => $personnel_id, 
			"session_name_id" => 1
		);
		$table = "session";
		$this->db->insert($table, $session_log_insert);
		
		return TRUE;
	}
}