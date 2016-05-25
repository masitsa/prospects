<?php
class Auth extends MX_Controller 
{
	function __construct()
	{
		parent:: __construct();
		$this->load->model('member_model');
		$this->load->model('site/site_model');
		$this->load->model('admin/companies_model');
		$this->load->model('member/invoices_model');
	}
	
	public function create_member_number()
	{
		$number = $this->member_model->create_member_number();
		
		echo $number;
	}
	
	public function register_member()
	{
		$v_data['member_first_name_error'] = '';
		$v_data['member_surname_error'] = '';
		$v_data['member_password_error'] = '';
		$v_data['member_email_error'] = '';
		$v_data['member_agree'] = '';
		
		$this->form_validation->set_rules('member_first_name', 'First name', 'trim|required|xss_clean');
		$this->form_validation->set_rules('member_surname', 'Surname', 'trim|required|xss_clean');
		$this->form_validation->set_rules('member_password', 'Password', 'trim|required|xss_clean');
		$this->form_validation->set_rules('member_phone', 'Phone', 'trim|required|xss_clean');
		$this->form_validation->set_rules('member_email', 'Email', 'trim|valid_email|is_unique[member.member_email]|required|xss_clean');
		$this->form_validation->set_rules('member_agree', 'Agree', 'trim|required|xss_clean');
		$this->form_validation->set_rules('date_of_birth', 'Date of Birth', 'required|xss_clean');
		$this->form_validation->set_rules('nationality', 'Nationality', 'required|xss_clean');
		$this->form_validation->set_rules('qualifications', 'Qualifications', 'required|xss_clean');
		$this->form_validation->set_rules('designation', 'Designation', 'required|xss_clean');
		$this->form_validation->set_rules('member_title', 'Title', 'required|xss_clean');
		$this->form_validation->set_rules('company_id', 'Company', 'required|xss_clean');
		
		//if form conatins invalid data
		if ($this->form_validation->run())
		{
			if($this->member_model->register_member_details())
			{
				$this->session->set_userdata('success_message', 'Your account has been created successfully :). Please check your email for further information on Dobi. You can now send clothes to a Dobi. Happy washing!');
					
				redirect('account');
			}
			
			else
			{
				$this->session->set_userdata('register_error', 'Unable to create account. Please try again');
			}
		}
		else
		{
			$validation_errors = validation_errors();
			//echo $validation_errors; die();
			//repopulate form data if validation errors are present
			if(!empty($validation_errors))
			{
				if(!empty($v_data['member_agree_error']))
				{
					$this->session->set_userdata('register_error', 'You must agree to the terms and conditions to continue');
				}
			}
		}
		$v_data['companies'] = $this->companies_model->all_companies();
		$data['title'] = 'Register';
		$v_data['title'] = $data['title'];
		
		$data['content'] = $this->load->view('member_signup', $v_data, true);
		
		$this->load->view('site/templates/general_page', $data);
	}
    
	/*
	*
	*	Login a user
	*
	*/
	public function login_member() 
	{
		$v_data['member_password_error'] = '';
		$v_data['member_email_error'] = '';
		
		//form validation rules
		$this->form_validation->set_rules('member_email', 'Email', 'required|xss_clean|exists[member.member_email]|valid_email');
		$this->form_validation->set_rules('member_password', 'Password', 'required|xss_clean');
		
		//if form has been submitted
		if ($this->form_validation->run())
		{
			//check if user has valid login credentials
			if($this->member_model->validate_member())
			{
				$member_id = $this->session->userdata('member_id');
				$result = $this->invoices_model->is_invoice_due($member_id);//var_dump($result);die();
				if($result)
				{
					$this->session->set_userdata('warning_message', 'Your membership is due renewal');
					if($this->invoices_model->create_member_invoice($member_id))
					{
					}
				}
				$this->session->set_userdata('success_message', 'Welcome back');
					
				redirect('account');
			}
			
			else
			{
				$this->session->set_userdata('login_error', 'The email or password provided is incorrect. Please try again');
				$v_data['member_email'] = set_value('member_email');
				$v_data['member_password'] = set_value('member_password');
			}
		}
		
		else
		{
			$validation_errors = validation_errors();
			//echo $validation_errors; die();
			//repopulate form data if validation errors are present
			if(!empty($validation_errors))
			{
				//create errors
				$v_data['member_password_error'] = form_error('member_password');
				$v_data['member_email_error'] = form_error('member_email');
				
				//repopulate fields
				$v_data['member_password'] = set_value('member_password');
				$v_data['member_email'] = set_value('member_email');
			}
			
			//populate form data on initial load of page
			else
			{
				$v_data['member_password'] = "";
				$v_data['member_email'] = "";
			}
		}
		$data['title'] = $this->site_model->display_page_title();
		$v_data['title'] = $data['title'];
		
		$data['content'] = $this->load->view('member_signin', $v_data, true);
		
		$this->load->view('site/templates/general_page', $data);
	}
	
	public function logout_admin()
	{
		$this->session->sess_destroy();
		redirect('login');
	}
}
?>