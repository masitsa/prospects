<?php   if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once "./application/modules/admin/controllers/admin.php";

class Plans extends admin 
{
	function __construct()
	{
		parent:: __construct();
		$this->load->model('users_model');
		$this->load->model('site/stripe_model');
		$this->load->model('plans_model');
	}
    
	/*
	*
	*	Default action is to show all the plans
	*
	*/
	public function index($order = 'plan_name', $order_method = 'ASC') 
	{
		$where = 'plan_id > 0';
		$table = 'plan';
		//pagination
		$segment = 5;
		$this->load->library('pagination');
		$config['base_url'] = site_url().'admin/plans/'.$order.'/'.$order_method;
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
		$query = $this->plans_model->get_all_plans($table, $where, $config["per_page"], $page, $order, $order_method);
		
		//change of order method 
		if($order_method == 'DESC')
		{
			$order_method = 'ASC';
		}
		
		else
		{
			$order_method = 'DESC';
		}
		
		$data['title'] = 'Plans';
		$v_data['title'] = $data['title'];
		
		$v_data['order'] = $order;
		$v_data['order_method'] = $order_method;
		$v_data['query'] = $query;
		$v_data['all_plans'] = $this->plans_model->all_plans();
		$v_data['page'] = $page;
		$data['content'] = $this->load->view('plans/all_plans', $v_data, true);
		
		$this->load->view('templates/general_page', $data);
	}
    
	/*
	*
	*	Add a new plan
	*
	*/
	public function add_plan() 
	{
		//form validation rules
		$this->form_validation->set_rules('plan_name', 'Plan Name', 'trim|required|xss_clean');
		$this->form_validation->set_rules('maximum_clicks', 'Maximum clicks', 'trim|xss_clean');
		$this->form_validation->set_rules('plan_status', 'Status', 'required|xss_clean');
		$this->form_validation->set_rules('plan_description', 'Description', 'required|xss_clean');
		$this->form_validation->set_rules('plan_amount', 'Amount', 'trim|numeric|xss_clean');
		$this->form_validation->set_rules('stripe_plan', 'Stripe plan', 'trim|xss_clean');
		
		//if form has been submitted
		if ($this->form_validation->run())
		{	
			if($this->plans_model->add_plan())
			{
				$this->session->set_userdata('success_message', 'Plan added successfully');
				redirect('admin/plans');
			}
			
			else
			{
				$this->session->set_userdata('error_message', 'Could not add plan. Please try again');
			}
		}
		
		//open the add new plan
		
		$data['title'] = 'Add plan';
		$v_data['title'] = $data['title'];
		$data['content'] = $this->load->view('plans/add_plan', $v_data, true);
		$this->load->view('templates/general_page', $data);
	}
    
	/*
	*
	*	Edit an existing plan
	*	@param int $plan_id
	*
	*/
	public function edit_plan($plan_id) 
	{
		//form validation rules
		$this->form_validation->set_rules('plan_name', 'Plan Name', 'trim|required|xss_clean');
		$this->form_validation->set_rules('maximum_clicks', 'Maximum clicks', 'trim|xss_clean');
		$this->form_validation->set_rules('plan_status', 'Status', 'required|xss_clean');
		$this->form_validation->set_rules('plan_description', 'Description', 'required|xss_clean');
		$this->form_validation->set_rules('plan_amount', 'Amount', 'trim|numeric|xss_clean');
		$this->form_validation->set_rules('stripe_plan', 'Stripe plan', 'trim|xss_clean');
		
		//if form has been submitted
		if ($this->form_validation->run())
		{
			//update plan
			if($this->plans_model->update_plan($plan_id))
			{
				$this->session->set_userdata('success_message', 'Plan updated successfully');
				redirect('admin/plans');
			}
			
			else
			{
				$this->session->set_userdata('error_message', 'Could not update plan. Please try again');
			}
		}
		
		//open the add new plan
		$data['title'] = 'Edit plan';
		$v_data['title'] = $data['title'];
		
		//select the plan from the database
		$query = $this->plans_model->get_plan($plan_id);
		
		if ($query->num_rows() > 0)
		{
			$v_data['plan'] = $query->result();
			
			$data['content'] = $this->load->view('plans/edit_plan', $v_data, true);
		}
		
		else
		{
			$data['content'] = 'Plan does not exist';
		}
		
		$this->load->view('templates/general_page', $data);
	}
    
	/*
	*
	*	Delete an existing plan
	*	@param int $plan_id
	*
	*/
	public function delete_plan($plan_id)
	{
		if($this->plans_model->delete_plan($plan_id))
		{
			$this->session->set_userdata('success_message', 'Plan has been deleted');
		}
		
		else
		{
			$this->session->set_userdata('error_message', 'Plan could not deleted');
		}
		redirect('admin/plans');
	}
    
	/*
	*
	*	Activate an existing plan
	*	@param int $plan_id
	*
	*/
	public function activate_plan($plan_id)
	{
		$this->plans_model->activate_plan($plan_id);
		$this->session->set_userdata('success_message', 'Plan activated successfully');
		redirect('admin/plans');
	}
    
	/*
	*
	*	Deactivate an existing plan
	*	@param int $plan_id
	*
	*/
	public function deactivate_plan($plan_id)
	{
		$this->plans_model->deactivate_plan($plan_id);
		$this->session->set_userdata('success_message', 'Plan disabled successfully');
		redirect('admin/plans');
	}
}
?>