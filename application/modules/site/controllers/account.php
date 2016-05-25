<?php   if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once "./application/modules/site/controllers/site.php";

class Account extends site {
	
	function __construct()
	{
		parent:: __construct();
		
		$this->load->model('auth_model');
		$this->load->model('banner_model');
		$this->load->model('subscription_model');
		$this->load->model('stripe_model');
		$this->load->model('reports_model');
		
		//user has logged in
		if($this->auth_model->check_login())
		{
		}
		
		//user has not logged in
		else
		{
			$this->session->set_userdata('error_message', 'Please sign in to continue');
				
			redirect('sign-in');
		}
	}
    
	/*
	*
	*	Open the account page
	*
	*/
	public function my_account()
	{
		//Required general page data
		/*$v_data['all_children'] = $this->categories_model->all_child_categories();
		$v_data['parent_categories'] = $this->categories_model->all_parent_categories();
		$v_data['crumbs'] = $this->site_model->get_crumbs();
		
		//page data
		$v_data['user_details'] = $this->users_model->get_user($this->session->userdata('user_id'));
		$data['content'] = $this->load->view('user/my_account', $v_data, true);
		
		$data['title'] = $this->site_model->display_page_title();
		$this->load->view('templates/general_page', $data);*/
		
		$data['content'] = $this->load->view('user/my_account', '', true);
		
		$data['title'] = $this->site_model->display_page_title();
		$this->load->view('templates/account', $data);
	}
    
	/*
	*
	*	Open the account page
	*
	*/
	public function banner($website = NULL)
	{
		if($website == NULL)
		{
			$v_data['latest_banner'] = $this->banner_model->get_latest_banner($this->session->userdata('customer_id'));
		}
		else
		{
			$v_data['latest_banner'] = $this->banner_model->get_banner($this->session->userdata('customer_id'), $website);
		}
		
		$data['content'] = $this->load->view('user/smartbanner', $v_data, true);
		
		$data['website'] = $website;
		$data['title'] = 'Edit '.$this->site_model->display_page_title();
		$this->load->view('templates/account', $data);
	}
    
	/*
	*
	*	Open the account page
	*
	*/
	public function subscribe()
	{
		$v_data['plans'] = $this->subscription_model->get_all_plans();
		$v_data['subscriptions'] = $this->subscription_model->get_subscriptions($this->session->userdata('customer_id'));
		$v_data['active_subscription'] = $this->subscription_model->get_active_subscription($this->session->userdata('customer_id'));
		$v_data['subscription_payments'] = $this->subscription_model->get_subscription_payments($this->session->userdata('customer_id'));
		
		$data['content'] = $this->load->view('user/subscription', $v_data, true);
		
		$data['title'] = $this->site_model->display_page_title();
		$this->load->view('templates/account', $data);
	}
    
	/*
	*
	*	Open the orders list
	*
	*/
	public function orders_list()
	{
		//Required general page data
		$v_data['all_children'] = $this->categories_model->all_child_categories();
		$v_data['parent_categories'] = $this->categories_model->all_parent_categories();
		$v_data['crumbs'] = $this->site_model->get_crumbs();
		
		//page data
		$v_data['all_orders'] = $this->orders_model->get_user_orders($this->session->userdata('user_id'));
		$data['content'] = $this->load->view('user/orders_list', $v_data, true);
		
		$data['title'] = $this->site_model->display_page_title();
		$this->load->view('templates/general_page', $data);
	}
    
	/*
	*
	*	Open the user's details page
	*
	*/
	public function my_details()
	{
		//Required general page data
		$v_data['all_children'] = $this->categories_model->all_child_categories();
		$v_data['parent_categories'] = $this->categories_model->all_parent_categories();
		$v_data['crumbs'] = $this->site_model->get_crumbs();
		
		//page data
		$v_data['user_details'] = $this->users_model->get_user($this->session->userdata('user_id'));
		$data['content'] = $this->load->view('user/my_details', $v_data, true);
		
		$data['title'] = $this->site_model->display_page_title();
		$this->load->view('templates/general_page', $data);
	}
    
	/*
	*
	*	Open the user's wishlist
	*
	*/
	public function wishlist()
	{
		//Required general page data
		$v_data['all_children'] = $this->categories_model->all_child_categories();
		$v_data['parent_categories'] = $this->categories_model->all_parent_categories();
		$v_data['crumbs'] = $this->site_model->get_crumbs();
		
		//page data
		$v_data['all_orders'] = $this->orders_model->get_users_wishlist($this->session->userdata('user_id'));
		$data['content'] = $this->load->view('user/wishlist', $v_data, true);
		
		$data['title'] = $this->site_model->display_page_title();
		$this->load->view('templates/general_page', $data);
	}
    
	/*
	*
	*	Update a user's account
	*
	*/
	public function update_account()
	{
		//form validation rules
		$this->form_validation->set_rules('last_name', 'Last Names', 'required|xss_clean');
		$this->form_validation->set_rules('first_name', 'First Name', 'required|xss_clean');
		$this->form_validation->set_rules('phone', 'Phone', 'required|xss_clean');
		
		//if form has been submitted
		if ($this->form_validation->run() == FALSE)
		{
			$this->session->set_userdata('front_error_message', validation_errors());
		}
		
		else
		{
			//check if user has valid login credentials
			if($this->users_model->edit_frontend_user($this->session->userdata('user_id')))
			{
				$this->session->set_userdata('front_success_message', 'Your details have been successfully updated');
			}
			
			else
			{
				$this->session->set_userdata('front_error_message', 'Oops something went wrong and we were unable to update your details. Please try again');
			}
		}
		
		$this->my_details();
	}
    
	/*
	*
	*	Update a user's password
	*
	*/
	public function update_password()
	{
		//form validation rules
		$this->form_validation->set_rules('current_password', 'Current Password', 'required|xss_clean');
		$this->form_validation->set_rules('new_password', 'New Password', 'required|xss_clean');
		$this->form_validation->set_rules('confirm_password', 'Confirm Password', 'required|xss_clean');
		
		//if form has been submitted
		if ($this->form_validation->run() == FALSE)
		{
			$this->session->set_userdata('front_error_message', validation_errors());
		}
		
		else
		{
			//update password
			$update = $this->users_model->edit_password($this->session->userdata('user_id'));
			if($update['result'])
			{
				$this->session->set_userdata('front_success_message', 'Your password has been successfully updated');
			}
			
			else
			{
				$this->session->set_userdata('front_error_message', $update['message']);
			}
		}
		
		$this->my_details();
	}
	
	function add_banner()
	{
		//form validation rules
		$this->form_validation->set_rules('website', 'Website', 'required|xss_clean');
		$this->form_validation->set_rules('title', 'Title', 'required|xss_clean');
		$this->form_validation->set_rules('author', 'Author', 'required|xss_clean');
		$this->form_validation->set_rules('price', 'Price', 'required|xss_clean');
		$this->form_validation->set_rules('icon_url', 'Icon URL', 'xss_clean');
		$this->form_validation->set_rules('url', 'URL', 'required|xss_clean');
		
		//if form data is invalid
		if ($this->form_validation->run() == FALSE)
		{
			$return['message'] = FALSE;
			$return['response'] = validation_errors();
		}
		
		else
		{
			//Add banner
			$return = $this->banner_model->add_banner($this->session->userdata('customer_id'));
		}
		
		echo json_encode($return);
	}
	
	function update_banner($smart_banner_id)
	{
		//form validation rules
		$this->form_validation->set_rules('title', 'Title', 'required|xss_clean');
		$this->form_validation->set_rules('author', 'Author', 'required|xss_clean');
		$this->form_validation->set_rules('price', 'Price', 'required|xss_clean');
		$this->form_validation->set_rules('icon_url', 'Icon URL', 'required|xss_clean');
		$this->form_validation->set_rules('url', 'URL', 'required|xss_clean');
		$this->form_validation->set_rules('app_store_lang', 'App Store price text', 'xss_clean');
		$this->form_validation->set_rules('play_store_lang', 'Google Play Store price text', 'xss_clean');
		$this->form_validation->set_rules('windows_store_lang', 'Windows Store price text', 'xss_clean');
		$this->form_validation->set_rules('play_store_params', 'Google Play Store params', 'xss_clean');
		$this->form_validation->set_rules('ios_icon_gloss', 'IOS icon gloss', 'xss_clean');
		$this->form_validation->set_rules('speed_in', 'Speed in', 'xss_clean');
		$this->form_validation->set_rules('speed_out', 'Speed out', 'xss_clean');
		$this->form_validation->set_rules('days_hidden', 'Days hidden after close', 'xss_clean');
		$this->form_validation->set_rules('days_reminder', 'Days hidden after view', 'xss_clean');
		$this->form_validation->set_rules('button_text', 'Button text', 'xss_clean');
		$this->form_validation->set_rules('top_border_color', 'Top border color', 'xss_clean');
		$this->form_validation->set_rules('top_gradient_color', 'Top gradient color', 'xss_clean');
		$this->form_validation->set_rules('bottom_gradient_color', 'Bottom gradient color', 'xss_clean');
		$this->form_validation->set_rules('text_color', 'Text color', 'xss_clean');
		$this->form_validation->set_rules('button_color', 'Button color', 'xss_clean');
		$this->form_validation->set_rules('button_text_color', 'Button text color', 'xss_clean');
		
		//if form data is invalid
		if ($this->form_validation->run() == FALSE)
		{
			$return['message'] = 'false';
			$return['response'] = validation_errors();
		}
		
		else
		{
			//Add banner
			$return = $this->banner_model->update_banner($smart_banner_id);
		}
		
		echo json_encode($return);
	}
	
	function add_card()
	{
		//form validation rules
		$this->form_validation->set_rules('stripe_token', 'Stripe token', 'required|xss_clean');
		$this->form_validation->set_rules('plan_id', 'Subscription plan', 'required|xss_clean');
		
		//if form data is invalid
		if ($this->form_validation->run() == FALSE)
		{
			$return['message'] = 'false';
			$return['response'] = validation_errors();
		}
		
		else
		{
			//get customer details
			$query = $this->subscription_model->get_customer(($this->session->userdata('customer_id')));
			$stripe_token = $this->input->post('stripe_token');
			$plan_id = $this->input->post('plan_id');
			
			if($query->num_rows() > 0)
			{
				$row = $query->row();
				$customer_email = $row->customer_email;
				$stripe_customer_id = $row->stripe_customer_id;
				
				//if customer has no stripe ID create customer in stripe
				if(empty($stripe_customer_id))
				{
					$return = $this->stripe_model->create_customer($customer_email, $stripe_token);
					$stripe_customer_id = $return['response'];
				}
				
				//otherwise update the customers default card
				else
				{
					$return = $this->stripe_model->update_customer($stripe_customer_id, $stripe_token);
				}
			}
			
			else
			{
				$return['message'] = 'false';
				$return['response'] = 'Customer does not exist';
			}
			
			//subscribe customer
			if(($return['message'] == 'true') && (!empty($stripe_customer_id)))
			{
				//check if customer has subscription
				$query2 = $this->subscription_model->get_customer_subscription($this->session->userdata('customer_id'));
				
				//subscription exists
				if($query2->num_rows() > 0)
				{
					$row2 = $query2->row();
					$stripe_subscription_id = $row2->stripe_subscription_id;
					
					//cancel subscription
					$return = $this->stripe_model->cancel_subscription($stripe_customer_id, $stripe_subscription_id);
					
					//create a new subscription
					if($return['message'] == 'true')
					{
						$return = $this->stripe_model->create_subscription($stripe_customer_id, $plan_id);
					}
				}
				
				else
				{
					//create a new subscription
					$return = $this->stripe_model->create_subscription($stripe_customer_id, $plan_id);
				}
			}
		}
		
		echo json_encode($return);
	}
	
	public function check_stripe_customer($plan_id)
	{
		//get customer details
		$query = $this->subscription_model->get_customer(($this->session->userdata('customer_id')));
		
		//customer is signed in
		if($query->num_rows() > 0)
		{
			$row = $query->row();
			$customer_email = $row->customer_email;
			$stripe_customer_id = $row->stripe_customer_id;
			
			//Customer has no stripe ID
			if(empty($stripe_customer_id))
			{
				$return['message'] = 'false';
				$return['response'] = 'Customer does not exist';
			}
			
			//otherwise update the customers default card
			else
			{
				//check if customer has subscription
				$query2 = $this->subscription_model->get_customer_subscription($this->session->userdata('customer_id'));
				
				//subscription exists
				if($query2->num_rows() > 0)
				{
					$row2 = $query2->row();
					$stripe_subscription_id = $row2->stripe_subscription_id;
					$subscription_id = $row2->subscription_id;
					
					if(!empty($stripe_subscription_id))
					{
						//cancel subscription
						$return = $this->stripe_model->cancel_subscription($stripe_customer_id, $stripe_subscription_id);
						
						if($return)
						{
							//update subscription status
							$this->subscription_model->cancel_subscription($subscription_id);
						}
					}
					//create a new subscription
					else
					{
						$return = $this->stripe_model->create_subscription($stripe_customer_id, $plan_id);
					}
				}
				
				else
				{
					//create a new subscription
					$return = $this->stripe_model->create_subscription($stripe_customer_id, $plan_id);
				}
			}
		}
		
		else
		{
			$return['message'] = 'false';
			$return['response'] = 'Please sign in to continue';
		}
		
		echo json_encode($return);
	}
    
	/*
	*
	*	Delete an existing banner
	*	@param int $banner_id
	*
	*/
	public function delete_banner($banner_website)
	{
		if($this->banner_model->delete_banner($banner_website))
		{
			$this->session->set_userdata('success_message', 'Banner deleted successfully');
		}
		
		else
		{
			$this->session->set_userdata('error_message', 'Unable to delete banner. Please ensure you have the priviledges to do so then try again');
		}
		redirect('my-account');
	}
    
	/*
	*
	*	Activate an existing banner
	*	@param int $banner_website
	*
	*/
	public function activate_banner($banner_website)
	{
		if($this->banner_model->activate_banner($banner_website))
		{
			$this->session->set_userdata('success_message', 'Banner activated successfully');
		}
		
		else
		{
			$this->session->set_userdata('error_message', 'Unable to activate banner. Please ensure you have the priviledges to do so then try again');
		}
		
		redirect('banner/'.$banner_website);
	}
    
	/*
	*
	*	Deactivate an existing banner
	*	@param int $banner_website
	*
	*/
	public function deactivate_banner($banner_website)
	{
		if($this->banner_model->deactivate_banner($banner_website))
		{
			$this->session->set_userdata('success_message', 'Banner deactivated successfully');
		}
		
		else
		{
			$this->session->set_userdata('error_message', 'Unable to deactivate banner. Please ensure you have the priviledges to do so then try again');
		}
		
		redirect('banner/'.$banner_website);
	}
    
	/*
	*
	*	Open the user's banners page
	*
	*/
	public function banners()
	{
		$v_data['banners'] = $this->banner_model->get_banners($this->session->userdata('customer_id'));
		$data['content'] = $this->load->view('user/banners', $v_data, true);
		
		$data['title'] = $this->site_model->display_page_title();
		$this->load->view('templates/account', $data);
	}
	
	public function activate_banner2($smart_banner_id)
	{
		if($this->banner_model->activate_banner2($smart_banner_id))
		{
			$this->session->set_userdata('success_message', 'Banner activated successfully');
		}
		
		else
		{
			$this->session->set_userdata('error_message', 'Unable to activate banner. Please ensure you have the priviledges to do so then try again');
		}
		
		redirect('banner/'.$banner_website);
	}
    
	/*
	*
	*	Deactivate an existing banner
	*	@param int $banner_website
	*
	*/
	public function deactivate_banner2($smart_banner_id)
	{
		if($this->banner_model->deactivate_banner2($smart_banner_id))
		{
			$this->session->set_userdata('success_message', 'Banner deactivated successfully');
		}
		
		else
		{
			$this->session->set_userdata('error_message', 'Unable to deactivate banner. Please ensure you have the priviledges to do so then try again');
		}
		
		redirect('banner/'.$banner_website);
	}
}