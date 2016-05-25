<?php

class Cron_model extends CI_Model 
{
	public function customers_without_banners()
	{
		$this->db->where('customer.customer_id NOT IN (SELECT customer_id FROM smart_banner where banner_delete = 0)');
		$query = $this->db->get('customer');
		
		return $query;
	}
	
	public function customers_without_banner_installed()
	{
		$this->db->where('customer.customer_id = smart_banner.customer_id AND customer.customer_id IN (SELECT customer_id FROM smart_banner where banner_delete = 0 AND banner_installed = 0)');
		$query = $this->db->get('customer, smart_banner');
		
		return $query;
	}
	
	public function banner_running_out_of_clicks()
	{
		$this->db->select('customer.*, plan.maximum_clicks, count(click_id) AS total_clicks, smart_banner.smart_banner_website, smart_banner.title');
		$this->db->where('customer.customer_id = smart_banner.customer_id AND customer.customer_id = subscription.customer_id AND subscription.plan_id = plan.plan_id AND smart_banner.smart_banner_website = click.website');
		$query = $this->db->get('customer, smart_banner, subscription, plan, click');
		
		return $query;
	}
	
	public function banner_run_out_of_clicks()
	{
		$this->db->select('customer.*, plan.maximum_clicks, count(click_id) AS total_clicks, smart_banner.smart_banner_website, smart_banner.title');
		$this->db->where('customer.customer_id = smart_banner.customer_id AND customer.customer_id = subscription.customer_id AND subscription.plan_id = plan.plan_id AND smart_banner.smart_banner_website = click.website');
		$this->db->having('(plan.maximum_clicks - count(click_id)) <= 0');
		$query = $this->db->get('customer, smart_banner, subscription, plan, click');
		
		return $query;
	}
	
	public function save_message($data)
	{
		if($this->db->insert('cron', $data))
		{
			return TRUE;
		}
		else
		{
			return FALSE;
		}
	}
	
	public function send_email($customer_email, $customer_first_name, $email_content, $subject)
	{
		//send account registration email
		$this->load->library('Mandrill', $this->config->item('mandrill_key'));
		
		//get contacts
		$contacts = $this->site_model->get_contacts();
		$email = '';
		$facebook = '';
		$twitter = '';
		$linkedin = '';
		$logo = '';
		$company_name = '';
		$google = '';
		
		if(count($contacts) > 0)
		{
			$email = $contacts['email'];
			$facebook = $contacts['facebook'];
			$twitter = $contacts['twitter'];
			$linkedin = $contacts['linkedin'];
			$logo = $contacts['logo'];
			$company_name = $contacts['company_name'];
			$phone = $contacts['phone'];
		}
		
		$client_email = $customer_email;
		$client_username = $customer_first_name;
		$sender_email = $email;
		$message = $email_content;
		$shopping = "<p>Happy installing<br/>The ".$company_name." Team</p>";
		$from = $company_name;
		
		$button = '<a class="mcnButton " title="Manage banners" href="'.site_url().'sign-in" target="_blank" style="font-weight: bold;letter-spacing: normal;line-height: 100%;text-align: center;text-decoration: none;color: #FFFFFF;">Manage banners</a>';
		//echo $client_email.'<br/>'."Hi ".$client_username.'<br/>'.$subject.'<br/>'.$message.'<br/>'.$sender_email.'<br/>'.$shopping.'<br/>'.$from.'<br/>'.$button;
		$response = $this->email_model->send_mandrill_mail($client_email, "Hi ".$client_username, $subject, $message, $sender_email, $shopping, $from, $button, $cc = NULL);
		
		return $response;
	}
}
?>