<?php   if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once "./application/modules/admin/controllers/admin.php";

class Emails extends admin 
{
	function __construct()
	{
		parent:: __construct();
		$this->load->model('users_model');
		$this->load->model('emails_model');
	}
    
	/*
	*
	*	Default action is to show all the emails
	*
	*/
	public function index($order = 'created', $order_method = 'DESC') 
	{
		$where = 'email.email_category_id = email_category.email_category_id';
		$table = 'email, email_category';
		//pagination
		$segment = 5;
		$this->load->library('pagination');
		$config['base_url'] = site_url().'admin/emails/'.$order.'/'.$order_method;
		$config['total_rows'] = $this->users_model->count_items($table, $where);
		$config['uri_segment'] = $segment;
		$config['per_page'] = 20;
		$config['num_links'] = 5;
		
		$config['full_tag_open'] = '<ul class="pagination pull-right">';
		$config['full_tag_close'] = '</ul>';
		
		$config['first_tag_open'] = '<li>';
		$config['first_tag_close'] = '</li>';
		
		$config['last_tag_open'] = '<li>';
		$config['last_tag_close'] = '</li>';
		
		$config['next_tag_open'] = '<li>';
		$config['next_link'] = 'Next';
		$config['next_tag_close'] = '</span>';
		
		$config['prev_tag_open'] = '<li>';
		$config['prev_link'] = 'Prev';
		$config['prev_tag_close'] = '</li>';
		
		$config['cur_tag_open'] = '<li class="active"><a href="#">';
		$config['cur_tag_close'] = '</a></li>';
		
		$config['num_tag_open'] = '<li>';
		$config['num_tag_close'] = '</li>';
		$this->pagination->initialize($config);
		
		$page = ($this->uri->segment($segment)) ? $this->uri->segment($segment) : 0;
        $v_data["links"] = $this->pagination->create_links();
		$query = $this->emails_model->get_all_emails($table, $where, $config["per_page"], $page, $order, $order_method);
		
		//change of order method 
		if($order_method == 'DESC')
		{
			$order_method = 'ASC';
		}
		
		else
		{
			$order_method = 'DESC';
		}
		
		$data['title'] = 'Emails';
		$v_data['title'] = $data['title'];
		
		$v_data['order'] = $order;
		$v_data['order_method'] = $order_method;
		$v_data['query'] = $query;
		$v_data['all_emails'] = $this->emails_model->all_emails();
		$v_data['page'] = $page;
		$data['content'] = $this->load->view('emails/all_emails', $v_data, true);
		
		$this->load->view('templates/general_page', $data);
	}
    
	/*
	*
	*	Add a new email
	*
	*/
	public function add_email() 
	{
		//form validation rules
		$this->form_validation->set_rules('email_category_id', 'Email category', 'required|xss_clean');
		$this->form_validation->set_rules('email_status', 'Email Status', 'required|xss_clean');
		$this->form_validation->set_rules('email_content', 'Email Preffix', 'required|xss_clean');
		
		//if form has been submitted
		if ($this->form_validation->run())
		{
			if($this->emails_model->add_email())
			{
				$this->session->set_userdata('success_message', 'Email added successfully');
				redirect('admin/emails');
			}
			
			else
			{
				$this->session->set_userdata('error_message', 'Could not add email. Please try again');
			}
		}
		
		//open the add new email
		
		$data['title'] = 'Add email';
		$v_data['title'] = $data['title'];
		$v_data['email_categories'] = $this->emails_model->email_categories();
		$data['content'] = $this->load->view('emails/add_email', $v_data, true);
		$this->load->view('templates/general_page', $data);
	}
    
	/*
	*
	*	Edit an existing email
	*	@param int $email_id
	*
	*/
	public function edit_email($email_id) 
	{
		//form validation rules
		$this->form_validation->set_rules('email_category_id', 'Email category', 'required|xss_clean');
		$this->form_validation->set_rules('email_status', 'Email Status', 'required|xss_clean');
		$this->form_validation->set_rules('email_content', 'Email Preffix', 'required|xss_clean');
		
		//if form has been submitted
		if ($this->form_validation->run())
		{
			//update email
			if($this->emails_model->update_email($email_id))
			{
				$this->session->set_userdata('success_message', 'Email updated successfully');
				redirect('admin/emails');
			}
			
			else
			{
				$this->session->set_userdata('error_message', 'Could not update email. Please try again');
			}
		}
		
		//open the add new email
		$data['title'] = 'Edit email';
		$v_data['title'] = $data['title'];
		
		//select the email from the database
		$query = $this->emails_model->get_email($email_id);
		
		if ($query->num_rows() > 0)
		{
			$v_data['email'] = $query->result();
			$v_data['email_categories'] = $this->emails_model->email_categories();
			
			$data['content'] = $this->load->view('emails/edit_email', $v_data, true);
		}
		
		else
		{
			$data['content'] = 'Email does not exist';
		}
		
		$this->load->view('templates/general_page', $data);
	}
    
	/*
	*
	*	Delete an existing email
	*	@param int $email_id
	*
	*/
	public function delete_email($email_id)
	{
		//delete email image
		$query = $this->emails_model->get_email($email_id);
		
		if ($query->num_rows() > 0)
		{
			$result = $query->result();
			$image = $result[0]->email_image_name;
			
			$this->load->model('file_model');
			//delete image
			$this->file_model->delete_file($this->emails_path."/images/".$image);
			//delete thumbnail
			$this->file_model->delete_file($this->emails_path."/thumbs/".$image);
		}
		$this->emails_model->delete_email($email_id);
		$this->session->set_userdata('success_message', 'Email has been deleted');
		redirect('admin/emails');
	}
    
	/*
	*
	*	Activate an existing email
	*	@param int $email_id
	*
	*/
	public function activate_email($email_id)
	{
		if($this->emails_model->activate_email($email_id))
		{
			$this->session->set_userdata('success_message', 'Email activated successfully');
		}
		
		else
		{
			$this->session->set_userdata('error_message', 'Unable to activate email. Please try again');
		}
		redirect('admin/emails');
	}
    
	/*
	*
	*	Deactivate an existing email
	*	@param int $email_id
	*
	*/
	public function deactivate_email($email_id)
	{
		if($this->emails_model->deactivate_email($email_id))
		{
			$this->session->set_userdata('success_message', 'Email deactivated successfully');
		}
		
		else
		{
			$this->session->set_userdata('error_message', 'Unable to deactivate email. Please try again');
		}
		
		redirect('admin/emails');
	}
}
?>