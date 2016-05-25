<?php   if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once "./application/modules/auth/controllers/admin_auth.php";

class Admin extends admin_auth 
{
	function __construct()
	{
		parent:: __construct();
		
		if(!$this->login_model->check_admin_login())
		{
			redirect('login-admin');
		}
		$this->load->model('login_model');
		$this->load->model('admin_model');
		$this->load->model('site/site_model');
		$this->load->model('sections_model');
		$this->load->model('reports_model');
		$this->load->model('categories_model');
	}
	
	public function index()
	{
		redirect('dashboard');
	}
    
	/*
	*
	*	Dashboard
	*
	*/
	public function dashboard() 
	{
		$data['title'] = $this->site_model->display_page_title();
		$v_data['title'] = $data['title'];
		//$v_data['category_parents'] = $this->categories_model->all_parent_categories();
		
		$data['content'] = $this->load->view('dashboard', $v_data, true);
		
		$this->load->view('templates/general_page', $data);
	}
	
	public function dobis()
	{
		
	}
}
?>