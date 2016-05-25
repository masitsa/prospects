<?php

class Auth_model extends CI_Model 
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
	*	Update user's last login date
	*
	*/
	private function update_user_login($customer_id, $first_name, $email, $api_key = NULL)
	{
		$newdata = array(
			   'login_status'   => TRUE,
			   'first_name'     => $first_name,
			   'email'			=> $email,
			   'customer_id'  	=> $customer_id
		   );
		
		if($api_key == NULL)
		{
			$this->db->where('customer_id', $customer_id);
			$query = $this->db->get('customer');
			
			if($query->num_rows() > 0)
			{
				$row = $query->row();
				$api_key = $row->customer_api_key;
			}
			
			else
			{
				$api_key = '';
			}
		}
		$newdata['customer_api_key'] = $api_key;

		$this->session->set_userdata($newdata);
		
		$data['last_login'] = date('Y-m-d H:i:s');
		$data['customer_api_key'] = $api_key;
		$this->db->where('customer_id', $customer_id);
		if($this->db->update('customer', $data))
		{
			return TRUE;
		}
		else
		{
			return FALSE;
		}
	}
	
	/*
	*	Reset a user's password
	*
	*/
	public function reset_password($user_id)
	{
		$new_password = substr(md5(date('Y-m-d H:i:s')), 0, 6);
		
		$data['password'] = md5($new_password);
		$this->db->where('user_id', $user_id);
		$this->db->update('users', $data); 
		
		return $new_password;
	}
	
	/*
	*	Register a new user
	*
	*/
	public function register_user()
	{
		//customer details
		$name = $this->input->post('name');
		$image = $this->input->post('image');
		$email = $this->input->post('email');
		$website = $this->input->post('website');
		
		//separate first and lasst name
		$parts = explode(" ", $name);
		$total_names = count($parts);
		$first_name = '';
		$last_name = '';
		
		for($r = 0; $r < $total_names; $r++)
		{
			if($r == 0)
			{
				$first_name = $parts[$r];
			}
			else
			{
				if($r == ($total_names - 1))
				{
					$last_name .= $parts[$r];
				}
				else
				{
					$last_name .= $parts[$r].' ';
				}
			}
		}
		
		$data = array(
			'created' => date('Y-m-d H:i:s'),
			'customer_email' => $email,
			'customer_first_name' => $first_name,
			'customer_surname' => $last_name, 
			'customer_image' => $image, 
			'customer_website' => $website
		);
		
		//check if customer exists
		$this->db->where('customer_email', $email);
		$query = $this->db->get('customer');
		if($query->num_rows() > 0)
		{
			$row = $query->row();
			$customer_id = $row->customer_id;
			//Update last login
			if($this->update_user_login($customer_id, $first_name, $email))
			{
				//save new banner
				if($this->banner_model->save_new_banner($customer_id, $website))
				{
					$return['message'] = TRUE;
					$return['response'] = 'Banner created successfully';
					$return['first_name'] = $first_name;
				}
				
				else
				{
					$return['message'] = TRUE;
					$return['response'] = 'Unable to create banner';
					$return['first_name'] = $first_name;
				}
			}
		}
		
		else
		{
			//save new customer
			if($this->db->insert('customer', $data))
			{
				$customer_id = $this->db->insert_id();
				$api_key = md5(site_url().'-'.$customer_id);
				
				//Update last login
				if($this->update_user_login($customer_id, $first_name, $email, $api_key))
				{
					//save new banner
					$reply = $this->banner_model->save_new_banner($customer_id, $website);
					if($reply['message'] == TRUE)
					{
						$return['message'] = TRUE;
						$return['response'] = 'Banner created successfully';
						$return['first_name'] = $first_name;
					}
					
					else
					{
						$return['message'] = TRUE;
						$return['response'] = $reply['response'];
						$return['first_name'] = $first_name;
					}
				}
			}
			
			//in case of registration error
			else
			{
				$return['message'] = FALSE;
				$return['response'] = 'Unable to register. Please try again';
			}
		}
		
		return $return;
	}
	
	/*
	*	Register a new user
	*
	*/
	public function sign_in_customer()
	{
		//customer details
		$name = $this->input->post('name');
		$image = $this->input->post('image');
		$email = $this->input->post('email');
		
		//separate first and lasst name
		$parts = explode(" ", $name);
		$total_names = count($parts);
		$first_name = '';
		$last_name = '';
		
		for($r = 0; $r < $total_names; $r++)
		{
			if($r == 0)
			{
				$first_name = $parts[$r];
			}
			else
			{
				if($r == ($total_names - 1))
				{
					$last_name .= $parts[$r];
				}
				else
				{
					$last_name .= $parts[$r].' ';
				}
			}
		}
		
		$data = array(
			'customer_email' => $email,
			'customer_first_name' => $first_name,
			'customer_surname' => $last_name, 
			'customer_image' => $image
		);
		
		//check if customer exists
		$this->db->where('customer_email', $email);
		$query = $this->db->get('customer');
		if($query->num_rows() > 0)
		{
			$row = $query->row();
			$customer_id = $row->customer_id;
			
			//Update last login
			if($this->update_user_login($customer_id, $first_name, $email))
			{
				$return['message'] = TRUE;
				$return['response'] = 'Welcome back';
			}
		}
		
		else
		{
			//save new customer
			if($this->db->insert('customer', $data))
			{
				$customer_id = $this->db->insert_id();
				
				//Update last login
				if($this->update_user_login($customer_id, $first_name, $email))
				{
					$return['message'] = TRUE;
					$return['response'] = 'Welcome to Installify';
				}
			}
			
			//in case of registration error
			else
			{
				$return['message'] = FALSE;
				$return['response'] = 'Unable to register. Please try again';
			}
		}
		
		return $return;
	}
	
	public function send_registration_email($customer_email, $customer_first_name)
	{
		//send account registration email
		$this->load->library('Mandrill', $this->config->item('mandrill_key'));
		$this->load->model('site/email_model');
		
		//get contacts
		$contacts = $this->site_model->get_contacts();
		$email = '';
		$facebook = '';
		$twitter = '';
		$linkedin = '';
		$logo = '';
		$company_name = '';
		$google = '';
		
		if(count($contacts) > 0)
		{
			$email = $contacts['email'];
			$facebook = $contacts['facebook'];
			$twitter = $contacts['twitter'];
			$linkedin = $contacts['linkedin'];
			$logo = $contacts['logo'];
			$company_name = $contacts['company_name'];
			$phone = $contacts['phone'];
		}
		
		//get registration message
		$this->db->where(array('email_status' => 1, 'email_category_id' => 1));
		$query = $this->db->get('email');
		$email_content = '';
		
		if($query->num_rows() > 0)
		{
			$row = $query->row();
			$email_content = $row->email_content;
		}
		
		$client_email = $customer_email;
		$client_username = $customer_first_name;
		$sender_email = $email;
		$subject = 'Welcome to '.$company_name;
		$message = $email_content;
		$shopping = "<p>Happy installing<br/>The ".$company_name." Team</p>";
		$from = $company_name;
		
		$button = '<a class="mcnButton " title="Manage banners" href="'.site_url().'sign-in" target="_blank" style="font-weight: bold;letter-spacing: normal;line-height: 100%;text-align: center;text-decoration: none;color: #FFFFFF;">Manage banners</a>';
		//echo $client_email.'<br/>'."Hi ".$client_username.'<br/>'.$subject.'<br/>'.$message.'<br/>'.$sender_email.'<br/>'.$shopping.'<br/>'.$from.'<br/>'.$button;
		$response = $this->email_model->send_mandrill_mail($client_email, "Hi ".$client_username, $subject, $message, $sender_email, $shopping, $from, $button, $cc = NULL);
		
		return $response;
	}
}