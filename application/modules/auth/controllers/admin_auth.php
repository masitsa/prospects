<?php   if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Admin_auth extends MX_Controller 
{	
	function __construct()
	{
		parent:: __construct();
		$this->load->model('auth/login_model');
		
		if(!$this->login_model->check_admin_login())
		{
			redirect('login-admin');
		}
	}
}

?>