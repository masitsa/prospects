<?php   if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once "./application/modules/site/controllers/site.php";

class Checkout extends site {
	
	function __construct()
	{
		parent:: __construct();
		$this->load->model('customer/customer_model');
		$this->load->model('dobi/dobi_model');
	}
    
	/*
	*
	*	Open the checkout page
	*
	*/
	public function checkout_user($dobi_id)
	{
		$this->session->unset_userdata('return_to_checkout');
		
		//customer has logged in
		if($this->customer_model->check_login())
		{
			$v_data['categories_location'] = $this->categories_location;
			$v_data['categories_path'] = $this->categories_path;
			$v_data['dobi_id'] = $dobi_id;
			$customer_id = $this->session->userdata('customer_id');
			$v_data['dobi'] = $this->dobi_model->get_dobi_details($dobi_id);
			$v_data['customer'] = $this->customer_model->get_customer_details($customer_id);
			$v_data['title'] = $data['title'] = $this->site_model->display_page_title();
			
			$data['content'] = $this->load->view('checkout/checkout', $v_data, true);
			
			$this->load->view('templates/general_page', $data);
		}
		
		else
		{
			$this->session->set_userdata('return_to_checkout', $dobi_id);
			$this->session->set_userdata('register_error', 'You must create an account or login to your account before you can continue.');
			redirect('customer-registration');
		}
	}
    
	/*
	*
	*	Action of a forgotten password
	*
	*/
	public function forgot_password()
	{
		//form validation rules
		$this->form_validation->set_rules('email', 'Email', 'required|xss_clean|exists[users.email]');
		$this->form_validation->set_message('exists', 'That email does not exist. Are you trying to sign up?');
		
		//if form has been submitted
		if ($this->form_validation->run())
		{
			$this->load->model('email_model');
			
			//reset password
			if($this->users_model->reset_password($this->input->post('email')))
			{
				$this->session->set_userdata('front_success_message', 'Your password has been reset and mailed to '.$this->input->post('email').'. Please use that password to sign in here');
				
				redirect('checkout');
			}
			
			else
			{
				$this->session->set_userdata('front_error_message', 'Could not add a new user. Please try again.');
			}
		}
		
		else
		{
			$this->session->set_userdata('front_error_message', validation_errors());
		}
		
		//Required general page data
		$v_data['all_children'] = $this->categories_model->all_child_categories();
		$v_data['parent_categories'] = $this->categories_model->all_parent_categories();
		$v_data['crumbs'] = $this->site_model->get_crumbs();
		
		//page datea
		$data['content'] = $this->load->view('checkout/reset_password', $v_data, true);
		
		$data['title'] = $this->site_model->display_page_title();
		$this->load->view('templates/general_page', $data);
	}
    
	/*
	*
	*	Checkout page delivery method
	*
	*/
	public function delivery()
	{
		//user has logged in
		if($this->login_model->check_login())
		{
			//Required general page data
			$v_data['all_children'] = $this->categories_model->all_child_categories();
			$v_data['parent_categories'] = $this->categories_model->all_parent_categories();
			$v_data['crumbs'] = $this->site_model->get_crumbs();
			
			$cart_items = $this->cart->total_items();
			
			//go to delivery page if there are items in cart
			if($cart_items > 0)
			{
				//page datea
				$v_data['step'] = 2;
				$v_data['user_details'] = $this->users_model->get_user($this->session->userdata('user_id'));
				$data['content'] = $this->load->view('checkout/delivery', $v_data, true);
			}
			
			//go to account if there are no items in cart
			else
			{
				//page datea
				$v_data['user_details'] = $this->users_model->get_user($this->session->userdata('user_id'));
				$data['content'] = $this->load->view('user/my_account', $v_data, true);
			}
			
			$data['title'] = $this->site_model->display_page_title();
			$this->load->view('templates/general_page', $data);
		}
		
		//user has not logged in
		else
		{
			$this->session->set_userdata('front_error_message', 'Please sign up/in to continue');
				
			redirect('checkout');
		}
	}
    
	/*
	*
	*	Checkout page payment options
	*
	*/
	public function payment_options()
	{
		//user has logged in
		if($this->login_model->check_login())
		{
			//Required general page data
			$v_data['all_children'] = $this->categories_model->all_child_categories();
			$v_data['parent_categories'] = $this->categories_model->all_parent_categories();
			$v_data['crumbs'] = $this->site_model->get_crumbs();
			
			$cart_items = $this->cart->total_items();
			
			//go to delivery page if there are items in cart
			if($cart_items > 0)
			{
				//page datea
				$v_data['step'] = 3;
				$data['content'] = $this->load->view('checkout/payment', $v_data, true);
			}
			
			//go to account if there are no items in cart
			else
			{
				//page datea
				$v_data['user_details'] = $this->users_model->get_user($this->session->userdata('user_id'));
				$data['content'] = $this->load->view('user/my_account', $v_data, true);
			}
			
			$data['title'] = $this->site_model->display_page_title();
			$this->load->view('templates/general_page', $data);
		}
		
		//user has not logged in
		else
		{
			$this->session->set_userdata('front_error_message', 'Please sign up/in to continue');
				
			redirect('checkout');
		}
	}
    
	/*
	*
	*	Checkout page prder details
	*
	*/
	public function order_details()
	{
		//user has logged in
		if($this->login_model->check_login())
		{
			//Required general page data
			$v_data['all_children'] = $this->categories_model->all_child_categories();
			$v_data['parent_categories'] = $this->categories_model->all_parent_categories();
			$v_data['crumbs'] = $this->site_model->get_crumbs();
			
			$cart_items = $this->cart->total_items();
			
			//go to delivery page if there are items in cart
			if($cart_items > 0)
			{
				//page datea
				$v_data['step'] = 4;
				$data['content'] = $this->load->view('checkout/order', $v_data, true);
			}
			
			//go to account if there are no items in cart
			else
			{
				//page datea
				$v_data['user_details'] = $this->users_model->get_user($this->session->userdata('user_id'));
				$data['content'] = $this->load->view('user/my_account', $v_data, true);
			}
			
			$data['title'] = $this->site_model->display_page_title();
			$this->load->view('templates/general_page', $data);
		}
		
		//user has not logged in
		else
		{
			$this->session->set_userdata('front_error_message', 'Please sign up/in to continue');
				
			redirect('checkout');
		}
	}
    
	/*
	*
	*	Confirm order
	*
	*/
	public function confirm_order()
	{
		//user has logged in
		if($this->login_model->check_login())
		{
			$this->load->model('admin/orders_model');
			
			//Required general page data
			$v_data['all_children'] = $this->categories_model->all_child_categories();
			$v_data['parent_categories'] = $this->categories_model->all_parent_categories();
			$v_data['crumbs'] = $this->site_model->get_crumbs();
			
			$data['title'] = $this->site_model->display_page_title();
			
			if($this->cart_model->save_order())
			{
				$data['content'] = $this->load->view('checkout/confirm_message', $v_data, true);
			}
			
			else
			{
				$data['content'] = $this->load->view('checkout/error_message', $v_data, true);
			}
			
			$this->load->view('templates/general_page', $data);
		}
		
		//user has not logged in
		else
		{
			$this->session->set_userdata('front_error_message', 'Please sign up/in to continue');
				
			redirect('checkout');
		}
	}
    
	/*
	*
	*	Add delivery instructions
	*
	*/
	public function add_delivery_instructions()
	{
		//form validation rules
		$this->form_validation->set_rules('delivery_instructions', 'Delivery Instructions', 'required|xss_clean');
		
		//if form has been submitted
		if ($this->form_validation->run() == FALSE)
		{
			$this->session->set_userdata('front_error_message', validation_errors());
		}
		
		else
		{
			$this->session->set_userdata('front_success_message', 'Instructions added successfully');
			$this->session->set_userdata('delivery_instructions', $this->input->post('delivery_instructions'));
		}
		redirect('checkout/delivery');
	}
    
	/*
	*
	*	Add payment options
	*
	*/
	public function add_payment_options()
	{
		//form validation rules
		$this->form_validation->set_rules('radios', 'Payment Method', 'required|xss_clean');
		
		//if form has been submitted
		if ($this->form_validation->run() == FALSE)
		{
			$this->session->set_userdata('front_error_message', validation_errors());
			redirect('checkout/payment-options');
		}
		
		else
		{
			$this->session->set_userdata('payment_option', $this->input->post('radios'));
			redirect('checkout/order');
		}
	}
	
	public function update_instructions($dobi_id)
	{
		$this->session->set_userdata('order_instructions', $this->input->post('order_instructions'));
		redirect('hire-dobi/'.$dobi_id);
	}
}
?>