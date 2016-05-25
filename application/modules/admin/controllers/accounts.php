<?php   if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once "./application/modules/admin/controllers/admin.php";

class Accounts extends admin 
{
	function __construct()
	{
		parent:: __construct();
		$this->load->model('users_model');
		$this->load->model('accounts_model');
		$this->load->model('site/orders_model');
	}
    
	/*
	*
	*	Accounts receivable
	*
	*/
	public function accounts_receivable($order = 'order_created', $order_method = 'DESC') 
	{
		$where = 'orders.customer_id = customer.customer_id AND orders.dobi_id = dobi.dobi_id AND orders.payment_method_id = payment_method.payment_method_id AND orders.order_status_id = order_status.order_status_id AND orders.payment_status = 2';
		$table = 'orders, customer, dobi, order_status, payment_method';
		
		$search = $this->session->userdata('receivable_search');
		
		if(!empty($search))
		{
			$where .= $search;
		}
		
		//pagination
		$segment = 5;
		$this->load->library('pagination');
		$config['base_url'] = site_url().'admin/accounts-receivable/'.$order.'/'.$order_method;
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
		$query = $this->accounts_model->get_accounts_receivable($table, $where, $config["per_page"], $page, $order, $order_method);
		
		//calculate totals
		$totals = $this->accounts_model->get_total_amount($table, $where);
		$v_data["total_transactions"] = $totals['items'];
		$v_data["total_receivable"] =  $totals['amount'];
		
		//change of order method 
		if($order_method == 'DESC')
		{
			$order_method = 'ASC';
		}
		
		else
		{
			$order_method = 'DESC';
		}
		
		$data['title'] = 'Accounts receivable';
		$v_data['title'] = $data['title'];
		
		$v_data['order'] = $order;
		$v_data['order_method'] = $order_method;
		$v_data['query'] = $query;
		$v_data['payment_method'] = $this->accounts_model->get_payment_methods();
		$v_data['order_statuses'] = $this->accounts_model->get_order_statuses();
		$v_data['page'] = $page;
		$data['content'] = $this->load->view('accounts/accounts_receivable', $v_data, true);
		
		$this->load->view('templates/general_page', $data);
	}
	
	public function confirm_payment($order_id, $order_number, $order, $order_method, $page)
	{
		if($this->accounts_model->confirm_payment($order_id))
		{
			$this->session->set_userdata('success_message', $order_number.' payment has been confirmed successfully');
		}
		
		else
		{
			$this->session->set_userdata('success_message', $order_number.' payment has could not be confirmed');
		}
		
		redirect('admin/accounts-receivable/'.$order.'/'.$order_method.'/'.$page);
	}
	
	public function search_accounts_receivable()
	{
		$payment_method_id = $this->input->post('payment_method_id');
		$order_status_id = $this->input->post('order_status_id');
		$search = '';
		
		if(!empty($payment_method_id))
		{
			$search .= ' AND payment_method.payment_method_id = '.$payment_method_id;
		}
		
		if(!empty($order_status_id))
		{
			$search .= ' AND order_status.order_status_id = '.$order_status_id;
		}
		
		$this->session->set_userdata('receivable_search', $search);
		redirect('admin/accounts-receivable');
	}
	
	public function close_accounts_receivable_search()
	{
		$this->session->unset_userdata('receivable_search');
		redirect('admin/accounts-receivable');
	}
    
	/*
	*
	*	Accounts receivable
	*
	*/
	public function accounts_payable($order = 'order_created', $order_method = 'DESC') 
	{
		$where = 'orders.customer_id = customer.customer_id AND orders.dobi_id = dobi.dobi_id AND orders.payment_method_id = payment_method.payment_method_id AND orders.order_status_id = order_status.order_status_id AND orders.payment_status = 3';
		$table = 'orders, customer, dobi, order_status, payment_method';
		
		$search = $this->session->userdata('payable_search');
		
		if(!empty($search))
		{
			$where .= $search;
		}
		
		//pagination
		$segment = 5;
		$this->load->library('pagination');
		$config['base_url'] = site_url().'admin/accounts-payable/'.$order.'/'.$order_method;
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
		$query = $this->accounts_model->get_accounts_receivable($table, $where, $config["per_page"], $page, $order, $order_method);
		
		//calculate totals
		$totals = $this->accounts_model->get_total_amount($table, $where);
		$v_data["total_transactions"] = $totals['items'];
		$amount =  $totals['amount'];
		$v_data["total_dobi"] =  $amount * 0.9;
		$v_data["total_profit"] =  $amount - $v_data["total_dobi"];
		
		//change of order method 
		if($order_method == 'DESC')
		{
			$order_method = 'ASC';
		}
		
		else
		{
			$order_method = 'DESC';
		}
		
		$data['title'] = 'Accounts payable';
		$v_data['title'] = $data['title'];
		
		$v_data['order'] = $order;
		$v_data['order_method'] = $order_method;
		$v_data['query'] = $query;
		$v_data['payment_method'] = $this->accounts_model->get_payment_methods();
		$v_data['order_statuses'] = $this->accounts_model->get_order_statuses();
		$v_data['page'] = $page;
		$data['content'] = $this->load->view('accounts/accounts_payable', $v_data, true);
		
		$this->load->view('templates/general_page', $data);
	}
	
	public function unconfirm_payment($order_id, $order_number, $order, $order_method, $page)
	{
		if($this->accounts_model->unconfirm_payment($order_id))
		{
			$this->session->set_userdata('success_message', $order_number.' payment has been unconfirmed successfully');
		}
		
		else
		{
			$this->session->set_userdata('success_message', $order_number.' payment has could not be unconfirmed');
		}
		
		redirect('admin/accounts-payable/'.$order.'/'.$order_method.'/'.$page);
	}
	
	public function receipt_payment($order_id, $order_number, $order, $order_method, $page)
	{
		if($this->accounts_model->receipt_payment($order_id))
		{
			$this->session->set_userdata('success_message', $order_number.' payment has been receipted successfully');
		}
		
		else
		{
			$this->session->set_userdata('success_message', $order_number.' payment has could not be receipted');
		}
		
		redirect('admin/accounts-payable/'.$order.'/'.$order_method.'/'.$page);
	}
	
	public function search_accounts_payable()
	{
		$payment_method_id = $this->input->post('payment_method_id');
		$order_status_id = $this->input->post('order_status_id');
		$search = '';
		
		if(!empty($payment_method_id))
		{
			$search .= ' AND payment_method.payment_method_id = '.$payment_method_id;
		}
		
		if(!empty($order_status_id))
		{
			$search .= ' AND order_status.order_status_id = '.$order_status_id;
		}
		
		$this->session->set_userdata('payable_search', $search);
		redirect('admin/accounts-payable');
	}
	
	public function close_accounts_payable_search()
	{
		$this->session->unset_userdata('payable_search');
		redirect('admin/accounts-payable');
	}
    
	/*
	*
	*	Receipts
	*
	*/
	public function receipts($order = 'order_created', $order_method = 'DESC') 
	{
		$where = 'orders.customer_id = customer.customer_id AND orders.dobi_id = dobi.dobi_id AND orders.payment_method_id = payment_method.payment_method_id AND orders.order_status_id = order_status.order_status_id AND orders.payment_status = 4';
		$table = 'orders, customer, dobi, order_status, payment_method';
		
		$search = $this->session->userdata('receipts_search');
		
		if(!empty($search))
		{
			$where .= $search;
		}
		
		//pagination
		$segment = 5;
		$this->load->library('pagination');
		$config['base_url'] = site_url().'admin/receipts/'.$order.'/'.$order_method;
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
		$query = $this->accounts_model->get_accounts_receivable($table, $where, $config["per_page"], $page, $order, $order_method);
		
		//calculate totals
		$totals = $this->accounts_model->get_total_amount($table, $where);
		$v_data["total_transactions"] = $totals['items'];
		$amount =  $totals['amount'];
		$v_data["total_dobi"] =  $amount * 0.9;
		$v_data["total_profit"] =  $amount - $v_data["total_dobi"];
		
		//change of order method 
		if($order_method == 'DESC')
		{
			$order_method = 'ASC';
		}
		
		else
		{
			$order_method = 'DESC';
		}
		
		$data['title'] = 'Receipts';
		$v_data['title'] = $data['title'];
		
		$v_data['order'] = $order;
		$v_data['order_method'] = $order_method;
		$v_data['query'] = $query;
		$v_data['payment_method'] = $this->accounts_model->get_payment_methods();
		$v_data['order_statuses'] = $this->accounts_model->get_order_statuses();
		$v_data['page'] = $page;
		$data['content'] = $this->load->view('accounts/receipts', $v_data, true);
		
		$this->load->view('templates/general_page', $data);
	}
	
	public function search_receipts()
	{
		$payment_method_id = $this->input->post('payment_method_id');
		$order_status_id = $this->input->post('order_status_id');
		$search = '';
		
		if(!empty($payment_method_id))
		{
			$search .= ' AND payment_method.payment_method_id = '.$payment_method_id;
		}
		
		if(!empty($order_status_id))
		{
			$search .= ' AND order_status.order_status_id = '.$order_status_id;
		}
		
		$this->session->set_userdata('receipts_search', $search);
		redirect('admin/receipts');
	}
	
	public function close_receipts_search()
	{
		$this->session->unset_userdata('receipts_search');
		redirect('admin/receipts');
	}
}
?>