<?php   if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Auth extends MX_Controller 
{	
	function __construct()
	{
		parent:: __construct();
		$this->load->model('auth/login_model');
		
		if(!$this->auth_model->check_login())
		{
			redirect('login');
		}
		
		else
		{
			redirect('dashboard');
		}
	}
	
	public function logout_admin()
	{
		$this->session->sess_destroy();
		redirect('login');
	}
}

?>