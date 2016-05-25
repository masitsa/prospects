<?php   if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once "./application/modules/site/controllers/site.php";

class Subscription extends site 
{
	function __construct()
	{
		parent:: __construct();
		$this->load->model('site/orders_model');
		$this->load->model('site/payments_model');
	}
	
	public function save_order($dobi_id)
	{
		//create order
		$order_id = $this->orders_model->create_order($dobi_id);
		$customer_id = $this->session->userdata('customer_id');
		
		if($order_id)
		{
			$this->session->set_userdata('success_message', 'Your order has been saved successfully');
		}
		
		else
		{
			$this->session->set_userdata('success_message', 'Unable to save your order');
		}
		
		redirect('customer-account/order-history');
	}
	
	public function make_payment($dobi_id)
	{
		$v_data['title'] = $data['title'] = $this->site_model->display_page_title();
		$v_data['dobi_id'] = $dobi_id;
		
		//create order
		$order_id = $this->orders_model->create_order($dobi_id);
		$customer_id = $this->session->userdata('customer_id');
		
		if($order_id)
		{
			$payment_type = $this->input->post('payment_type');
			
			//pesapal
			if($payment_type == 2)
			{
				$v_data['iframe'] = '';
				
				$iframe = $this->payments_model->make_pesapal_payment($order_id, $customer_id);
				$v_data['iframe'] = $iframe;
				
				$data['content'] = $this->load->view('payment/pesapal', $v_data, true);
			}
			
			//mpesa
			else
			{
				$v_data['order'] = $this->orders_model->get_order($order_id);
				$v_data['order_id'] = $order_id;
				$data['content'] = $this->load->view('payment/mpesa', $v_data, true);
			}
		}
		
		else
		{
			$data['content'] = '<div class="alert alert-danger">Unable to create order</div>';
		}
		
		$this->load->view('templates/general_page', $data);
	}
	
	public function make_order_payment($order_id, $payment_type = NULL)
	{
		//get order details
		$query = $this->orders_model->get_order($order_id);
		$row = $query->row();
		$dobi_id = $row->dobi_id;
		
		$v_data['title'] = $data['title'] = $this->site_model->display_page_title();
		$v_data['dobi_id'] = $dobi_id;
		
		$customer_id = $this->session->userdata('customer_id');
		
		if($order_id)
		{
			if($payment_type == NULL)
			{
				$payment_type = $this->input->post('payment_type');
			}
			
			//mpesa
			if($payment_type == 1)
			{
				$v_data['order'] = $this->orders_model->get_order($order_id);
				$v_data['order_id'] = $order_id;
				$data['content'] = $this->load->view('payment/mpesa', $v_data, true);
			}
			
			//pesapal
			else
			{
				$v_data['iframe'] = '';
				
				$iframe = $this->payments_model->make_pesapal_payment($order_id, $customer_id);
				$v_data['iframe'] = $iframe;
				
				$data['content'] = $this->load->view('payment/pesapal', $v_data, true);
			}
		}
		
		else
		{
			$data['content'] = '<div class="alert alert-danger">Unable to create order</div>';
		}
		
		$this->load->view('templates/general_page', $data);
	}
    
	/*
	*
	*	Payment success Page
	*
	*/
	public function payment()
	{
		//unset data
		$this->session->unset_userdata('order_instructions');
		$this->session->unset_userdata('options');
		$this->cart->destroy();
		
		//mark order as paid in the database
		$payment_data = $this->input->get();
		$transaction_tracking_id = $payment_data['pesapal_transaction_tracking_id'];
		$order_id = $payment_data['pesapal_merchant_reference'];
		
		//check to see if the payment was successfully made
		$response = $this->payments_model->get_pesapal_payment($transaction_tracking_id, $order_id);
		
		if($response[1] == 'COMPLETED')
		{
			if($this->payments_model->activate_order($transaction_tracking_id, $order_id))
			{
				$this->session->set_userdata('success_message', 'Your payment has been received successfully');
			}
			
			else
			{
				$this->session->set_userdata('error_message', 'Unable to complete your payment. Please contact an administrator');
			}
			redirect('customer-account');
		}
		
		else
		{
			$this->payments_model->update_payment_response($transaction_tracking_id, $order_id);
			
			redirect('process-payment/'.$transaction_tracking_id.'/'.$order_id);
		}
	}
	
	public function process_payment($transaction_tracking_id, $order_id)
	{
		$v_data['transaction_tracking_id'] = $transaction_tracking_id;
		$v_data['order_id'] = $order_id;
		$v_data['title'] = $data['title'] = $this->site_model->display_page_title();
		
		$data['content'] = $this->load->view('payment/processing', $v_data, true);
		
		$this->load->view('templates/general_page', $data);
	}
	
	public function check_payment($count, $transaction_tracking_id, $order_id)
	{	
		$count++;
		
		$response = $this->payments_model->get_pesapal_payment($transaction_tracking_id, $order_id);
		$status = $response[1];
		
		if($response[1] == 'COMPLETED')
		{
			if($this->payments_model->activate_payment($transaction_tracking_id, $order_id))
			{
				$this->session->set_userdata('success_message', 'Your payment has been received successfully');
			}
			
			else
			{
				$this->session->set_userdata('error_message', 'Unable to complete your payment. Please contact an administrator');
			}
			echo 'true';
			
			//$this->session->unset_userdata('transaction_tracking_id', $transaction_tracking_id);
			//$this->session->unset_userdata('order_id', $order_id);
		}
		
		else if($count == 20)
		{
			$this->session->set_userdata('error_message', 'Unable to complete your payment. Kindly ensure that you followed the steps provided in Pesapal and entered the correct transaction number. If you did, please get in touch with us');
			echo 'true';
		}
		
		else
		{
			echo $count;
		}
	}
	
	public function check_payment2()
	{
		/*$this->session->set_userdata('transaction_tracking_id', '2e39abc3-267c-4f21-84a6-e6d3f0c778c');
		$this->session->set_userdata('order_id', '23');*/
		$status = '';
		$count = 0;
		$transaction_tracking_id = $this->session->userdata('pesapal_transaction_tracking_id');
		$order_id = $this->session->userdata('order_id');
		
		while($status != 'COMPLETED')
		{
			$count++;
			//echo $count.'<br/>';
			$response = $this->payments_model->get_pesapal_payment($transaction_tracking_id, $order_id);
			$status = $response[1];
			
			if($response[1] == 'COMPLETED')
			{
				if($this->payments_model->activate_payment($transaction_tracking_id, $order_id))
				{
					$this->session->set_userdata('success_message', 'Your payment has been received successfully');
				}
				
				else
				{
					$this->session->set_userdata('error_message', 'Unable to complete your payment. Please contact an administrator');
				}
				echo 'true';
			}
			
			if($count == 1000000)
			{
				$this->session->set_userdata('error_message', 'Unable to complete your payment. Kindly ensure that you followed the steps provided in Pesapal and entered the correct transaction number');
				echo 'false';
				break;
			}
		}
			
		$this->session->unset_userdata('transaction_tracking_id', $transaction_tracking_id);
		$this->session->unset_userdata('order_id', $order_id);
	}
	
	public function add_mpesa_code($order_id)
	{
		$this->form_validation->set_rules('mpesa_code', 'MPesa transaction code', 'required|xss_clean');
		
		//if form has been submitted
		if ($this->form_validation->run())
		{
			$code = strtoupper($this->input->post('mpesa_code'));
			
			//check if code exists in dobi db
			$this->db->where("transaction_reference = '".$code."' AND mpesa_code_status = 0");
			$query = $this->db->get('mpesa_code');
			
			//if code exists
			if($query->num_rows() > 0)
			{
				$row = $query->row();
				$mpesa_code_id = $row->mpesa_code_id;
				
				//update order
				$data['order_status_id'] = 0;
				$data['payment_status'] = 2;
				$data['mpesa_code'] = $code;
				
				$this->db->where('order_id', $order_id);
				if($this->db->update('orders', $data))
				{
					//update mpesa code as used
					$data2['mpesa_code_status'] = 1;
					$this->db->where("mpesa_code_id", $mpesa_code_id);
					if($this->db->update('mpesa_code', $data2))
					{
						//destroy cart
						$this->session->unset_userdata('order_instructions');
						$this->session->unset_userdata('options');
						$this->cart->destroy();
						
						//send transaction email
						$response = $this->orders_model->send_mpesa_receipt_email($order_id);
						$this->session->set_userdata('success_message', 'Your payment has been received successfully and your clothes queued for washing.');
						
						//redirect to customer account
						redirect('customer-account/order-history');
					}
				}
			}
			
			else
			{
				$data['order_status_id'] = 7;
				$data['mpesa_code'] = $code;
				
				$this->db->where('order_id', $order_id);
				if($this->db->update('orders', $data))
				{
					//destroy cart
					$this->session->unset_userdata('order_instructions');
					$this->session->unset_userdata('options');
					$this->cart->destroy();
					
					//redirect to customer account
					$this->session->set_userdata('error_message', 'Your payment is yet to be received. We shall notify you by email once it has come through. Thanks.');
					redirect('customer-account/order-history');
				}
			}
		}
		
		else
		{
			$this->session->set_userdata('error_message', validation_errors());
			$this->make_order_payment($order_id, $payment_type = NULL);
		}
		
	}
	
	public function process_mpesa_payment()
	{
		$data['transaction_timestamp'] = $_REQUEST['transaction_timestamp'];
		$data['transaction_type'] = $_REQUEST['transaction_type'];
		$data['transaction_reference'] = strtoupper($_REQUEST['transaction_reference']);
		$data['sender_phone'] = $_REQUEST['sender_phone'];
		$data['first_name'] = $_REQUEST['first_name'];
		$data['last_name'] = $_REQUEST['last_name'];
		$data['middle_name'] = $_REQUEST['middle_name'];
		$data['amount'] = $_REQUEST['amount'];
		$data['currency'] = $_REQUEST['currency'];
		
		if($this->db->insert('mpesa_code', $data))
		{
			
		}
		
		else
		{
		}
	}
	
	public function test_process_mpesa_payment()
	{
		$data['transaction_timestamp'] = 'aaa';
		$data['transaction_type'] = 'aaa';
		$transaction_reference = 'ORD15-000006';
		$data['sender_phone'] = 'aaa';
		$data['first_name'] = 'aaa';
		$data['last_name'] = 'aaa';
		$data['middle_name'] = 'aaa';
		$data['amount'] = 'aaa';
		$data['currency'] ='aaa';
		$data['order_status_id'] = 0;
		$data['payment_status'] = 2;
		
		$this->db->where('order_number', $transaction_reference);
		if($this->db->update('orders', $data))
		{
			$response = $this->orders_model->send_mpesa_receipt_email($transaction_reference);
		
			var_dump($response);
		}
		
		else
		{
		}
	}
}
?>