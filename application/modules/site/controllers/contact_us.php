<?php   if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Contact_us extends MX_Controller {
	
	function __construct()
	{
		parent:: __construct();
		
		$this->load->library('Mandrill', $this->config->item('mandrill_key'));
		$this->load->model('site/email_model');
	}
    
	/*
	*
	*	Default action is to show all the posts
	*
	*/
	public function book_appointment() 
	{
		///form validation rules
		$this->form_validation->set_rules('email', 'Email', 'xss_clean|required|valid_email');
		$this->form_validation->set_rules('full_name', 'Full name', 'required|xss_clean');
		$this->form_validation->set_rules('phone', 'Phone number', 'required|xss_clean');
		$this->form_validation->set_rules('appointment_date', 'Appointment date', 'required|xss_clean');
		$this->form_validation->set_rules('appointment_time', 'Appointment time', 'required|xss_clean');
		
		//if form has been submitted
		if ($this->form_validation->run())
		{
			$full_name = $this->input->post('full_name');
			$email = $this->input->post('email');
			$phone = $this->input->post('phone');
			$date = $this->input->post('appointment_date');
			$time = $this->input->post('appointment_time');
			
			//save email_campaign
			$save_data = array(
				'full_name' => $full_name,
				'email' => $email,
				'phone' => $phone,
				'appointment_date' => $date,
				'appointment_time' => $time,
				'date_created' => date('Y-m-d H:i:s')
			);
			$this->db->insert('appointment', $save_data);
			
			//send mail to SUMC
			$subject = 'Appointment request';
			$message = '
			<p>I would like to request for an appointment. These are my details:</p>
			<ol>
				<li>
					<strong>Full name: </strong>'.$full_name.'
				</li>
				<li>
					<strong>Email: </strong>'.$email.'
				</li>
				<li>
					<strong>Phone: </strong>'.$phone.'
				</li>
				<li>
					<strong>Requested date: </strong>'.$date.'
				</li>
				<li>
					<strong>Requested time: </strong>'.$time.'
				</li>
			</ol>
			<p>Kindly respond to them asap</p>
			
			';
			$sender_email = 'sumedicalcentre@strathmore.edu';
			$shopping = "";
			$from = $full_name;
			
			$button = '';
			$response = $this->email_model->send_mandrill_mail($sender_email, "Hi ", $subject, $message, $email, $shopping, $from, $button, $cc = NULL);
			
			//send confirmation mail to client
			$subject = 'Appointment request';
			$message = '
			<p>Your appointment request has been received with the following details:</p>
			<ol>
				<li>
					<strong>Full name: </strong>'.$full_name.'
				</li>
				<li>
					<strong>Email: </strong>'.$email.'
				</li>
				<li>
					<strong>Phone: </strong>'.$phone.'
				</li>
				<li>
					<strong>Requested date: </strong>'.$date.'
				</li>
				<li>
					<strong>Requested time: </strong>'.$time.'
				</li>
			</ol>
			<p>We will get back to you as soon as possible</p>
			
			';
			$sender_email = 'sumedicalcentre@strathmore.edu';
			$shopping = "";
			$from = 'Strathmore University Medical Centre';
			
			$button = '';
			$response2 = $this->email_model->send_mandrill_mail($email, "Hi ", $subject, $message, $sender_email, $shopping, $from, $button, $cc = NULL);
			
			$return['status'] = 'success';
			$return['message'] = 'Your request has been received. We shall get back to you as soon as possible.';
		}
		
		else
		{
			$return['status'] = 'error';
			$return['message'] = validation_errors();
		}
		
		echo json_encode($return);
	}
    
	/*
	*
	*	Default action is to show all the posts
	*
	*/
	public function contact() 
	{
		///form validation rules
		$this->form_validation->set_rules('email', 'Email', 'xss_clean|required|valid_email');
		$this->form_validation->set_rules('first_name', 'First name', 'required|xss_clean');
		$this->form_validation->set_rules('last_name', 'Last name', 'required|xss_clean');
		$this->form_validation->set_rules('phone', 'Phone number', 'required|xss_clean');
		$this->form_validation->set_rules('message', 'Message', 'required|xss_clean');
		
		//if form has been submitted
		if ($this->form_validation->run())
		{
			$first_name = $this->input->post('first_name');
			$last_name = $this->input->post('last_name');
			$email = $this->input->post('email');
			$phone = $this->input->post('phone');
			$message = $this->input->post('message');
			
			//send mail to SUMC
			$subject = 'Inquiry on SUMC';
			$message = '
			<p>'.$message.'</p>
			<h5>Sender details</h5>
			<ol>
				<li>
					<strong>First name: </strong>'.$first_name.'
				</li>
				<li>
					<strong>Last name: </strong>'.$last_name.'
				</li>
				<li>
					<strong>Email: </strong>'.$email.'
				</li>
				<li>
					<strong>Phone: </strong>'.$phone.'
				</li>
			</ol>
			<p>Kindly respond to them asap</p>
			
			';
			$sender_email = $email;
			$shopping = "";
			$from = $first_name;
			
			$button = '';
			$response = $this->email_model->send_mandrill_mail('sumedicalcentre@strathmore.edu', "Hi ", $subject, $message, $sender_email, $shopping, $from, $button, $cc = NULL);
			
			//send confirmation mail to client
			$subject = 'We have received your message';
			$message = '
			<p>Your message has been received with the following details:</p>
			<ol>
				<li>
					<strong>First name: </strong>'.$first_name.'
				</li>
				<li>
					<strong>Last name: </strong>'.$last_name.'
				</li>
				<li>
					<strong>Email: </strong>'.$email.'
				</li>
				<li>
					<strong>Phone: </strong>'.$phone.'
				</li>
				<li>
					<strong>Message: </strong>'.$message.'
				</li>
			</ol>
			<p>We will get back to you as soon as possible</p>
			
			';
			
			$shopping = "";
			$from = 'Strathmore University Medical Centre';
			
			$button = '';
			$response2 = $this->email_model->send_mandrill_mail($email, "Hi ".$first_name, $subject, $message, 'sumedicalcentre@strathmore.edu', $shopping, $from, $button, $cc = NULL);
			
			$return['status'] = 'success';
			$return['message'] = 'Your message has been received. We shall get back to you as soon as possible.';
		}
		
		else
		{
			$return['status'] = 'error';
			$return['message'] = validation_errors();
		}
		
		echo json_encode($return);
	}
}