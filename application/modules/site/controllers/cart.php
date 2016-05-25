<?php   if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once "./application/modules/site/controllers/site.php";

class Cart extends site {
	
	function __construct()
	{
		parent:: __construct();
	}
	
	public function add_item($category_id)
	{
		if($this->cart_model->add_item($category_id))
		{
			$cart_items = $this->cart_model->get_cart();
			
			$data['result'] = 'success';
			$data['cart_items'] = $cart_items;
			$data['total_items'] = $this->cart_model->total_items_in_cart();
			$data['mini_cart_footer'] = $this->load->view('cart/cart_footer', '', TRUE);
			$data['cart_total'] = $this->load->view('cart/cart_total', '', TRUE);
		}
		
		else
		{
			$data['result'] = 'failure';
		}
		
		echo json_encode($data);
	}
	
	public function delete_cart_item($row_id, $page = 1)
	{
		if($this->cart_model->delete_cart_item($row_id))
		{
			$cart_items = $this->cart_model->get_cart();
			
			$data['result'] = 'success';
			$data['cart_items'] = $cart_items;
			$data['total_items'] = $this->cart_model->total_items_in_cart();
			$data['mini_cart_footer'] = $this->load->view('cart/cart_footer', '', TRUE);
			$data['cart_total'] = $this->load->view('cart/cart_total', '', TRUE);
		}
		
		else
		{
			$data['result'] = 'failure';
		}
		
		if($page == 1)
		{
			echo json_encode($data);
		}
		
		else
		{
			redirect('basket');
		}
	}
	
	public function view_cart()
	{
		$v_data['categories_location'] = $this->categories_location;
		$v_data['categories_path'] = $this->categories_path;
		$contacts = $this->site_model->get_contacts();
		$v_data['contacts'] = $contacts;
		
		$data['title'] = $this->site_model->display_page_title();
		$v_data['title'] = $data['title'];
		$data['contacts'] = $contacts;
		
		$data['content'] = $this->load->view('cart/view_cart', $v_data, true);
		
		$data['title'] = $this->site_model->display_page_title();
		$this->load->view('templates/general_page', $data);
	}
	
	public function update_cart($row_id)
	{
		if($this->cart_model->update_cart($row_id))
		{
			$this->session->set_userdata('success_message', 'Your basket has been updated successfully');
		}
		
		else
		{
			$this->session->set_userdata('error_message', 'Unable to update. Please try again');
		}
		
		redirect('basket');
	}
	
	public function destroy_cart()
	{
		$this->cart->destroy();
	}
	
	public function update_cart_options($dobi_id)
	{
		$fold = $this->input->post('fold');
		$iron = $this->input->post('iron');
		$deliver = $this->input->post('deliver');
		
		$data = array();
		
		if(!empty($fold))
		{
			$data['fold_cost'] = $fold;
		}
		
		if(!empty($iron))
		{
			$data['iron_cost'] = $iron;
		}
		
		if(!empty($deliver))
		{
			$data['delivery_cost'] = $deliver;
		}
		
		$this->session->set_userdata('options', $data);
		
		redirect('hire-dobi/'.$dobi_id);
	}
}
?>