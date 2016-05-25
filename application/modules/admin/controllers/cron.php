<?php   if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Cron extends MX_Controller 
{
	function __construct()
	{
		parent:: __construct();
		
		$this->load->model('site/site_model');
		$this->load->model('emails_model');
		$this->load->model('cron_model');
		$this->load->model('site/email_model');
	}
	
	public function create_account_without_banner()
	{
		/*
			1. Select all customers who have not created a banner
		*/
		$query = $this->cron_model->customers_without_banners();
		
		if($query->num_rows() > 0)
		{
			/*
				2. Select email message of customers without banners
			*/
			$emails = $this->emails_model->get_automated_email(2);
			if($emails->num_rows() > 0)
			{
				$row = $emails->row();
				$email_content = $row->email_content;
			}
			
			else
			{
				$email_content = 'Oops. Seems like you have not created a banner. Please sign into your account and create one today!';
			}
			$subject = 'You have not created any banners';
			
			/*
				3. Send email to customers
			*/
			foreach($query->result() as $res)
			{
				$customer_id = $res->customer_id;
				$customer_email = $res->customer_email;
				$customer_first_name = $res->customer_first_name;
				
				$response = $this->cron_model->send_email($customer_email, $customer_first_name, $email_content, $subject);
				if($response)
				{
					$data['cron_description'] = json_encode($response);
				}
				
				else
				{
					$data['cron_description'] = json_encode($response);
				}
				
				/*
					4. Save email sending response to the database
				*/
				$data['customer_id'] = $customer_id;
				$data['cron_status'] = 1;
				$data['cron_created'] = date('Y-m-d H:i:s');
			
				if($this->cron_model->save_message($data))
				{
					echo 'true';;
				}
				else
				{
					echo 'false';
				}
			}
		}
		
		else
		{
			/*
				2. Save all customers have banners
			*/
			$data['cron_description'] = 'All customers have banners created';
			$data['cron_status'] = 1;
			$data['cron_created'] = date('Y-m-d H:i:s');
			
			if($this->cron_model->save_message($data))
			{
				echo 'true';;
			}
			else
			{
				echo 'false';
			}
		}
	}
	
	public function banner_not_installed()
	{
		/*
			1. Select all customers who have not installed a banner
		*/
		$query = $this->cron_model->customers_without_banner_installed();
		
		if($query->num_rows() > 0)
		{
			/*
				2. Select email message of customers without banners installed
			*/
			$emails = $this->emails_model->get_automated_email(3);
			if($emails->num_rows() > 0)
			{
				$row = $emails->row();
				$email_content = $row->email_content;
			}
			
			else
			{
				$email_content = 'Seems like you have not installed your banner. Would you like some help doing so? You can find detailed instructions in your account under the details of a particular banner. Sign in today and learn how to install your banner!';
			}
			
			/*
				3. Send email to customers
			*/
			foreach($query->result() as $res)
			{
				$customer_email = $res->customer_email;
				$customer_id = $res->customer_id;
				$customer_first_name = $res->customer_first_name;
				$smart_banner_website = $res->smart_banner_website;
				$banner = $res->title;
				
				if(empty($banner))
				{
					$banner = $smart_banner_website;
				}
				$subject = $banner.' banner not installed';
				
				$response = $this->cron_model->send_email($customer_email, $customer_first_name, $email_content, $subject);
				//var_dump($response);
				if($response)
				{
					$data['cron_description'] = json_encode($response);
				}
				
				else
				{
					$data['cron_description'] = json_encode($response);
				}
				
				/*
					4. Save email sending response to the database
				*/
				$data['customer_id'] = $customer_id;
				$data['cron_status'] = 1;
				$data['cron_created'] = date('Y-m-d H:i:s');
			
				if($this->cron_model->save_message($data))
				{
					echo 'true';;
				}
				else
				{
					echo 'false';
				}
			}
		}
		
		else
		{
			/*
				2. Save all customers have banners
			*/
			$data['cron_description'] = 'All customers have banners installed';
			$data['cron_status'] = 1;
			$data['cron_created'] = date('Y-m-d H:i:s');
			
			if($this->cron_model->save_message($data))
			{
				echo 'true';;
			}
			else
			{
				echo 'false';
			}
		}
	}
	
	public function running_out_of_clicks()
	{
		/*
			1. Select all customers who have < 100 clicks remaining
		*/
		$query = $this->cron_model->banner_running_out_of_clicks();
		
		if($query->num_rows() > 0)
		{
			/*
				2. Select email message of customers without banners installed
			*/
			$emails = $this->emails_model->get_automated_email(4);
			if($emails->num_rows() > 0)
			{
				$row = $emails->row();
				$email_content = $row->email_content;
			}
			
			else
			{
				$email_content = 'Seems like you have running out of clicks. Sign into your account to upgrade your plan in order to keep your click activity alive!';
			}
			
			/*
				3. Send email to customers
			*/
			foreach($query->result() as $res)
			{
				$customer_email = $res->customer_email;
				$customer_id = $res->customer_id;
				$customer_first_name = $res->customer_first_name;
				$smart_banner_website = $res->smart_banner_website;
				$banner = $res->title;
				$total_clicks = $res->total_clicks;
				
				$email_content .= '<br/>Your have <strong>'.$total_clicks.'</strong> clicks left.';
				
				if(empty($banner))
				{
					$banner = $smart_banner_website;
				}
				$subject = $banner.' banner is running out of clicks';
				
				$response = $this->cron_model->send_email($customer_email, $customer_first_name, $email_content, $subject);
				if($response)
				{
					$data['cron_description'] = json_encode($response);
				}
				
				else
				{
					$data['cron_description'] = json_encode($response);
				}
				
				/*
					4. Save email sending response to the database
				*/
				$data['customer_id'] = $customer_id;
				$data['cron_status'] = 1;
				$data['cron_created'] = date('Y-m-d H:i:s');
			
				if($this->cron_model->save_message($data))
				{
					echo 'true';;
				}
				else
				{
					echo 'false';
				}
			}
		}
		
		else
		{
			/*
				2. Save all customers have banners
			*/
			$data['cron_description'] = 'All customers have clicks to spare';
			$data['cron_status'] = 1;
			$data['cron_created'] = date('Y-m-d H:i:s');
			
			if($this->cron_model->save_message($data))
			{
				echo 'true';;
			}
			else
			{
				echo 'false';
			}
		}
	}
	
	public function run_out_of_clicks()
	{
		/*
			1. Select all customers who have run out of
		*/
		$query = $this->cron_model->banner_run_out_of_clicks();
		
		if($query->num_rows() > 0)
		{
			/*
				2. Select email message of customers without banners installed
			*/
			$emails = $this->emails_model->get_automated_email(5);
			if($emails->num_rows() > 0)
			{
				$row = $emails->row();
				$email_content = $row->email_content;
			}
			
			else
			{
				$email_content = 'Oh no! Seems like you have run out of clicks. You can keep your banner alive by upgrading your plan. Sign in today to upgrade your plan.';
			}
			
			/*
				3. Send email to customers
			*/
			foreach($query->result() as $res)
			{
				$customer_email = $res->customer_email;
				$customer_id = $res->customer_id;
				$customer_first_name = $res->customer_first_name;
				$smart_banner_website = $res->smart_banner_website;
				$banner = $res->title;
				$total_clicks = $res->total_clicks;
				
				if(empty($banner))
				{
					$banner = $smart_banner_website;
				}
				$subject = $banner.' banner has run out of clicks';
				
				$response = $this->cron_model->send_email($customer_email, $customer_first_name, $email_content, $subject);
				if($response)
				{
					$data['cron_description'] = json_encode($response);
				}
				
				else
				{
					$data['cron_description'] = json_encode($response);
				}
				
				/*
					4. Save email sending response to the database
				*/
				$data['customer_id'] = $customer_id;
				$data['cron_status'] = 1;
				$data['cron_created'] = date('Y-m-d H:i:s');
			
				if($this->cron_model->save_message($data))
				{
					echo 'true';;
				}
				else
				{
					echo 'false';
				}
			}
		}
		
		else
		{
			/*
				2. Save all customers have banners
			*/
			$data['cron_description'] = 'All customers have clicks to spare';
			$data['cron_status'] = 1;
			$data['cron_created'] = date('Y-m-d H:i:s');
			
			if($this->cron_model->save_message($data))
			{
				echo 'true';;
			}
			else
			{
				echo 'false';
			}
		}
	}
}