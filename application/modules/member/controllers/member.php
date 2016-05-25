<?php   if ( ! defined('BASEPATH')) exit('No direct script access allowed');
		
// include autoloader
require_once "./application/libraries/dompdf/autoload.inc.php";
	
// reference the Dompdf namespace
use Dompdf\Dompdf;

class member extends MX_Controller 
{
	var $member_id;
	var $uploads_path;
	var $uploads_location;
		
	function __construct()
	{
		parent:: __construct();
		
		$this->load->model('site/site_model');
		$this->load->model('member/member_model');
		$this->load->model('member/invoices_model');
		$this->load->model('member/uploads_model');
		$this->load->model('admin/file_model');

		$this->uploads_path = realpath(APPPATH . '../assets/uploads');
		$this->uploads_location = base_url().'assets/uploads/';
		
		//user has logged in
		if($this->member_model->check_login())
		{
			$this->member_id = $this->session->userdata('member_id');
		}
		
		//user has not logged in
		else
		{
			$this->session->set_userdata('login_error', 'Please sign up/in to continue');
				
			redirect('login');
		}
	}
    
	/*
	*
	*	Open the account page
	*
	*/
	public function my_account()
	{
		$member_id = $this->member_id;
		$v_data['title'] = $data['title'] = $this->site_model->display_page_title();
		$v_data['member_id'] = $member_id;
		$v_data['invoices'] = $this->invoices_model->get_user_invoices($member_id, 5);
		$data['content'] = $this->load->view('account/dashboard', $v_data, true);
		
		$this->load->view('site/templates/account', $data);
	}
    
	/*
	*
	*	Open the orders list
	*
	*/
	public function orders_list()
	{	
		//page data
		$v_data['all_orders'] = $this->orders_model->get_user_orders($this->member_id);
		$v_data['title'] = $data['title'] = $this->site_model->display_page_title();
		$data['content'] = $this->load->view('account/orders', $v_data, true);
		
		$this->load->view('site/templates/general_page', $data);
	}
    
	/*
	*
	*	Open the user's details page
	*
	*/
	public function my_details()
	{
		//page data
		$v_data['title'] = $data['title'] = $this->site_model->display_page_title();
		$neighbourhoods_query = $this->member_model->get_neighbourhoods();
		$v_data['neighbourhood_parents'] = $neighbourhoods_query['neighbourhood_parents'];
		$v_data['neighbourhood_children'] = $neighbourhoods_query['neighbourhood_children'];
		$v_data['member_query'] = $this->member_model->get_member_details($this->member_id);
		$data['content'] = $this->load->view('account/my_details', $v_data, true);
		
		$this->load->view('site/templates/general_page', $data);
	}
    
	/*
	*
	*	Open the user's shipping page
	*
	*/
	public function edit_shipping()
	{
		//page data
		$v_data['surburbs_query'] = $this->vendor_model->get_all_surburbs();
		$v_data['shipping_query'] = $this->checkout_model->get_shipping_details($this->member_id);
		$data['content'] = $this->load->view('account/shipping_address', $v_data, true);
		
		$data['title'] = $this->site_model->display_page_title();
		$this->load->view('site/templates/general_page', $data);
	}
    
	/*
	*
	*	Open the user's wishlist
	*
	*/
	public function my_addresses()
	{
		$v_data['surburbs_query'] = $this->vendor_model->get_all_surburbs();
		$v_data['member_query'] = $this->checkout_model->get_member_details($this->member_id);
		$v_data['shipping_query'] = $this->checkout_model->get_shipping_details($this->member_id);
		$data['content'] = $this->load->view('account/my_addresses', $v_data, true);
		
		$data['title'] = $this->site_model->display_page_title();
		$this->load->view('site/templates/general_page', $data);
	}
    
	/*
	*
	*	Open the user's wishlist
	*
	*/
	public function wishlist()
	{
		$v_data['products_path'] = $this->products_path;
		$v_data['products_location'] = $this->products_location;
		$v_data['wishlist'] = $this->orders_model->get_user_wishlist($this->member_id);
		$data['content'] = $this->load->view('account/wishlist', $v_data, true);
		
		$data['title'] = $this->site_model->display_page_title();
		$this->load->view('site/templates/general_page', $data);
	}
	
	public function delete_wishlist_item($wishlist_id)
	{
		$this->db->where('wishlist_id', $wishlist_id);
		$this->db->delete('wishlist');
		echo 'true';
		redirect('account/wishlist');
	}
    
	/*
	*
	*	Update a user's account
	*
	*/
	public function update_account()
	{
		//form validation rules
		$this->form_validation->set_error_delimiters('', '');
		$this->form_validation->set_rules('member_first_name', 'First name', 'trim|required|xss_clean');
		$this->form_validation->set_rules('member_surname', 'Surname', 'trim|required|xss_clean');
		$this->form_validation->set_rules('member_phone', 'Phone', 'trim|required|xss_clean');
		$this->form_validation->set_rules('parent', 'Location', 'trim|required|xss_clean');
		$this->form_validation->set_rules('child', 'Neighbourhood', 'trim|xss_clean');
		
		//if form has been submitted
		if ($this->form_validation->run() == FALSE)
		{
			$this->my_details();
		}
		
		else
		{
			//check if user has valid login credentials
			if($this->member_model->update_member_details($this->member_id))
			{
				$this->session->set_userdata('success_message', 'Your details have been successfully updated');
			}
			
			else
			{
				$this->session->set_userdata('error_message', 'Oops something went wrong and we were unable to update your details. Please try again');
			}
		
			redirect('member-account/about-me');
		}
	}
    
	/*
	*
	*	Update a user's password
	*
	*/
	public function update_password()
	{
		//form validation rules
		$this->form_validation->set_error_delimiters('', '');
		$this->form_validation->set_rules('current_password', 'Current Password', 'required|xss_clean');
		$this->form_validation->set_rules('new_password', 'New Password', 'required|xss_clean');
		$this->form_validation->set_rules('confirm_password', 'Confirm Password', 'required|xss_clean');
		
		//if form has been submitted
		if ($this->form_validation->run() == FALSE)
		{
			$this->my_details();
		}
		
		else
		{
			//update password
			$update = $this->member_model->edit_password($this->member_id);
			if($update['result'])
			{
				$this->session->set_userdata('success_message', 'Your password has been successfully updated');
			}
			
			else
			{
				$this->session->set_userdata('error_message', $update['message']);
			}
		
			redirect('member-account/about-me');
		}
	}
	
	public function cancel_order($order_preffix, $order_number)
	{
		$number = $order_preffix.'/'.$order_number;
		//confirm order is for the member
		if($this->orders_model->request_cancel($number, $this->member_id))
		{
			$this->session->set_userdata('success_message', 'Your cancel request for order number '.$number.' has been received. You will be notified once the request is confirmed');
		}
		
		else
		{
			$this->session->set_userdata('error_message', 'Unable to cancel your order. Please try again');
		}
		
		redirect('account/orders-list');
	}
	
	public function make_payment($order_preffix, $order_number)
	{
		$number = $order_preffix.'/'.$order_number;
		//confirm order is for the member
		if($this->checkout_model->make_payment($number, $this->member_id))
		{
			$this->session->set_userdata('success_message', 'Your cancel request for order number '.$number.' has been received. You will be notified once the request is confirmed');
		}
		
		else
		{
			$this->session->set_userdata('error_message', 'Unable to initiate payment for your order. Please try again');
		}
		
		redirect('account/orders-list');
	}
	
	public function sign_out()
	{
		$this->session->sess_destroy();
		$this->session->set_userdata('login_error', 'Your have been signed out of your account');
		redirect('login');
	}
    
	/*
	*
	*	Open the user's uploads
	*
	*/
	public function uploads()
	{
		$v_data['uploads_path'] = $this->uploads_path;
		$v_data['uploads_location'] = $this->uploads_location;
		$v_data['member_id'] = $this->member_id;
		$v_data['uploads'] = $this->uploads_model->get_member_uploads($this->member_id);
		$data['content'] = $this->load->view('account/uploads', $v_data, true);
		
		$data['title'] = $this->site_model->display_page_title();
		$this->load->view('site/templates/account', $data);
	}

	/*
	*
	*	Add documents 
	*	@param int $member_id
	*
	*/
	public function upload_documents($member_id) 
	{
		$image_error = '';
		$this->session->unset_userdata('upload_error_message');
		$document_name = 'document_scan';
		
		//upload image if it has been selected
		$response = $this->uploads_model->upload_any_file($this->uploads_path, $this->uploads_location, $document_name, 'document_scan');
		if($response)
		{
			$uploads_location = $this->uploads_location.$this->session->userdata($document_name);
		}
		
		//case of upload error
		else
		{
			$image_error = $this->session->userdata('upload_error_message');
			$this->session->unset_userdata('upload_error_message');
		}

		$document = $this->session->userdata($document_name);
		$this->form_validation->set_rules('document_place', 'Place of issue', 'xss_clean');
		
		//if form has been submitted
		if ($this->form_validation->run())
		{

			if($this->uploads_model->upload_member_documents($member_id, $document))
			{
				$this->session->set_userdata('success_message', 'Document uploaded successfully');
				$this->session->unset_userdata($document_name);
			}
			
			else
			{
				$this->session->set_userdata('error_message', 'Could not upload document. Please try again');
			}
		}
		else
		{
			$this->session->set_userdata('error_message', 'Could not upload document. Please try again');
		}
		
		redirect('uploads');
	}
    
	/*
	*
	*	Delete an existing personnel
	*	@param int $personnel_id
	*
	*/
	public function delete_document_scan($document_upload_name, $document_upload_id, $personnel_id)
	{
		if($this->uploads_model->delete_document_scan($document_upload_name, $document_upload_id, $this->uploads_location, $this->uploads_path))
		{
			$this->session->set_userdata('success_message', 'Document has been deleted');
		}
		
		else
		{
			$this->session->set_userdata('error_message', 'Document could not deleted');
		}
		redirect('uploads');
	}
	
	public function download_invoice($invoice_id)
	{
		//$this->load->helper(array('dompdf', 'pdfFilePath'));
		$v_data['invoices'] = $this->invoices_model->get_invoice($invoice_id);
		$v_data['invoice_items'] = $this->invoices_model->get_invoice_items($invoice_id);
		$v_data['contacts'] = $this->site_model->get_contacts();
		
		$html=$this->load->view('admin/members/view_invoice', $v_data, true);
		
		$row = $v_data['invoices']->row();

		$invoice_number = $row->invoice_number;
	
 
        //this the the PDF filename that user will get to download
        $pdfFilePath = 'Invoice '.$invoice_number.".pdf";
		
		// instantiate and use the dompdf class
		$dompdf = new Dompdf();
		$dompdf->loadHtml($html);
		
		// (Optional) Setup the paper size and orientation
		$dompdf->setPaper('A4', 'potrait');
		
		// Render the HTML as PDF
		$dompdf->render();
		
		// Output the generated PDF to Browser
		$dompdf->stream();
	}
}