<?php   if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once "./application/modules/admin/controllers/admin.php";

class Customers extends admin 
{
	var $customers_path;
	var $customers_location;
	
	function __construct()
	{
		parent:: __construct();
		$this->load->model('users_model');
		$this->load->model('customers_model');
	}
    
	/*
	*
	*	Default action is to show all the customers
	*
	*/
	public function index($order = 'customer_first_name', $order_method = 'ASC') 
	{
		$where = 'customer_id > 0';
		$table = 'customer';
		//pagination
		$segment = 5;
		$this->load->library('pagination');
		$config['base_url'] = site_url().'admin/customers/'.$order.'/'.$order_method;
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
		$query = $this->customers_model->get_all_customers($table, $where, $config["per_page"], $page, $order, $order_method);
		
		//change of order method 
		if($order_method == 'DESC')
		{
			$order_method = 'ASC';
		}
		
		else
		{
			$order_method = 'DESC';
		}
		
		$data['title'] = 'Customers';
		$v_data['title'] = $data['title'];
		
		$v_data['order'] = $order;
		$v_data['order_method'] = $order_method;
		$v_data['query'] = $query;
		$v_data['all_customers'] = $this->customers_model->all_customers();
		$v_data['page'] = $page;
		$data['content'] = $this->load->view('customers/all_customers', $v_data, true);
		
		$this->load->view('templates/general_page', $data);
	}
    
	/*
	*
	*	Add a new customer
	*
	*/
	public function add_customer() 
	{
		//form validation rules
		$this->form_validation->set_rules('customer_name', 'Customer Name', 'required|xss_clean');
		$this->form_validation->set_rules('customer_status', 'Customer Status', 'required|xss_clean');
		$this->form_validation->set_rules('customer_preffix', 'Customer Preffix', 'required|is_unique[customer.customer_preffix]|xss_clean');
		$this->form_validation->set_rules('customer_parent', 'Customer Parent', 'required|xss_clean');
		$this->form_validation->set_message("is_unique", "A unique preffix is requred.");
		
		//if form has been submitted
		if ($this->form_validation->run())
		{
			//upload product's gallery images
			$resize['width'] = 600;
			$resize['height'] = 800;
			
			if(is_uploaded_file($_FILES['customer_image']['tmp_name']))
			{
				$this->load->library('image_lib');
				
				$customers_path = $this->customers_path;
				/*
					-----------------------------------------------------------------------------------------
					Upload image
					-----------------------------------------------------------------------------------------
				*/
				$response = $this->file_model->upload_file($customers_path, 'customer_image', $resize);
				if($response['check'])
				{
					$file_name = $response['file_name'];
					$thumb_name = $response['thumb_name'];
				}
			
				else
				{
					$this->session->set_userdata('error_message', $response['error']);
					
					$data['title'] = 'Add New Customer';
					$v_data['all_customers'] = $this->customers_model->all_customers();
					$data['content'] = $this->load->view('customers/add_customer', $v_data, true);
					$this->load->view('templates/general_page', $data);
					break;
				}
			}
			
			else{
				$file_name = '';
				$thumb_name = '';
			}
			
			if($this->customers_model->add_customer($file_name))
			{
				$this->session->set_userdata('success_message', 'Customer added successfully');
				redirect('admin/customers');
			}
			
			else
			{
				$this->session->set_userdata('error_message', 'Could not add customer. Please try again');
			}
		}
		
		//open the add new customer
		
		$data['title'] = 'Add customer';
		$v_data['title'] = $data['title'];
		$v_data['all_customers'] = $this->customers_model->all_parent_customers();
		$data['content'] = $this->load->view('customers/add_customer', $v_data, true);
		$this->load->view('templates/general_page', $data);
	}
    
	/*
	*
	*	Edit an existing customer
	*	@param int $customer_id
	*
	*/
	public function edit_customer($customer_id) 
	{
		//form validation rules
		$this->form_validation->set_rules('customer_name', 'Customer Name', 'required|xss_clean');
		$this->form_validation->set_rules('customer_status', 'Customer Status', 'required|xss_clean');
		$this->form_validation->set_rules('customer_preffix', 'Customer Preffix', 'required|xss_clean');
		$this->form_validation->set_rules('customer_parent', 'Customer Parent', 'required|xss_clean');
		$this->form_validation->set_message("is_unique", "A unique preffix is requred.");
		
		//if form has been submitted
		if ($this->form_validation->run())
		{
			//upload product's gallery images
			$resize['width'] = 600;
			$resize['height'] = 800;
			
			if(is_uploaded_file($_FILES['customer_image']['tmp_name']))
			{
				$customers_path = $this->customers_path;
				$customers_location = $this->customers_location;
				
				//delete original image
				$this->file_model->delete_file($customers_path."\\".$this->input->post('current_image'), $customers_location);
				
				//delete original thumbnail
				$this->file_model->delete_file($customers_path."\\thumbnail_".$this->input->post('current_image'), $customers_location);
				/*
				/*
					-----------------------------------------------------------------------------------------
					Upload image
					-----------------------------------------------------------------------------------------
				*/
				$response = $this->file_model->upload_file($customers_path, 'customer_image', $resize);
				if($response['check'])
				{
					$file_name = $response['file_name'];
					$thumb_name = $response['thumb_name'];
				}
			
				else
				{
					$this->session->set_userdata('error_message', $response['error']);
					
					/*$data['title'] = 'Edit Customer';
					$query = $this->customers_model->get_customer($customer_id);
					if ($query->num_rows() > 0)
					{
						$v_data['customer'] = $query->result();
						$v_data['all_customers'] = $this->customers_model->all_customers();
						$data['content'] = $this->load->view('customers/edit_customer', $v_data, true);
					}
					
					else
					{
						$data['content'] = 'customer does not exist';
					}
					
					$this->load->view('templates/general_page', $data);
					break;*/
					$file_name = '';
				}
			}
			
			else{
				$file_name = $this->input->post('current_image');
			}
			//update customer
			if($this->customers_model->update_customer($file_name, $customer_id))
			{
				$this->session->set_userdata('success_message', 'Customer updated successfully');
				redirect('admin/customers');
			}
			
			else
			{
				$this->session->set_userdata('error_message', 'Could not update customer. Please try again');
			}
		}
		
		//open the add new customer
		$data['title'] = 'Edit customer';
		$v_data['title'] = $data['title'];
		
		//select the customer from the database
		$query = $this->customers_model->get_customer($customer_id);
		
		if ($query->num_rows() > 0)
		{
			$v_data['customer'] = $query->result();
			$v_data['all_customers'] = $this->customers_model->all_parent_customers();
			
			$data['content'] = $this->load->view('customers/edit_customer', $v_data, true);
		}
		
		else
		{
			$data['content'] = 'Customer does not exist';
		}
		
		$this->load->view('templates/general_page', $data);
	}
    
	/*
	*
	*	Delete an existing customer
	*	@param int $customer_id
	*
	*/
	public function delete_customer($customer_id)
	{
		//delete customer image
		$query = $this->customers_model->get_customer($customer_id);
		
		if ($query->num_rows() > 0)
		{
			$result = $query->result();
			$image = $result[0]->customer_image_name;
			
			$this->load->model('file_model');
			//delete image
			$this->file_model->delete_file($this->customers_path."/images/".$image);
			//delete thumbnail
			$this->file_model->delete_file($this->customers_path."/thumbs/".$image);
		}
		$this->customers_model->delete_customer($customer_id);
		$this->session->set_userdata('success_message', 'Customer has been deleted');
		redirect('admin/customers');
	}
    
	/*
	*
	*	Activate an existing customer
	*	@param int $customer_id
	*
	*/
	public function activate_customer($customer_id)
	{
		$this->customers_model->activate_customer($customer_id);
		$this->session->set_userdata('success_message', 'Customer activated successfully');
		redirect('admin/customers');
	}
    
	/*
	*
	*	Deactivate an existing customer
	*	@param int $customer_id
	*
	*/
	public function deactivate_customer($customer_id)
	{
		$this->customers_model->deactivate_customer($customer_id);
		$this->session->set_userdata('success_message', 'Customer disabled successfully');
		redirect('admin/customers');
	}
}
?>