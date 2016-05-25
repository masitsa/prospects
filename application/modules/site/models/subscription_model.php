<?php

class Subscription_model extends CI_Model 
{
	public function get_subscriptions($customer_id)
	{
		$this->db->select('subscription.*, plan.plan_name');
		$this->db->where('plan.plan_id = subscription.plan_id AND subscription.customer_id = '.$customer_id);
		$query = $this->db->get('plan, subscription');
		
		return $query;
	}
	public function get_active_subscription($customer_id)
	{
		$this->db->select('subscription.*, plan.plan_name');
		$this->db->where('subscription.subscription_status = 1 AND plan.plan_id = subscription.plan_id AND subscription.customer_id = '.$customer_id);
		$query = $this->db->get('plan, subscription');
		
		return $query;
	}
	
	public function get_plan($plan_id)
	{
		$this->db->where('plan.plan_id = '.$plan_id);
		$query = $this->db->get('plan');
		
		return $query;
	}
	
	public function get_subscription_payments($customer_id)
	{
		$this->db->select('subscription_payment.*');
		$this->db->where('subscription_payment.subscription_id = subscription.subscription_id AND subscription.customer_id = '.$customer_id);
		$query = $this->db->get('smart_banner, subscription, subscription_payment');
		
		return $query;
	}
	
	public function get_cards($customer_id)
	{
		$this->db->where('card.customer_id = '.$customer_id);
		$query = $this->db->get('card');
		
		return $query;
	}
	
	public function get_all_plans()
	{
		$this->db->where('plan.plan_status = 1');
		$query = $this->db->get('plan');
		
		return $query;
	}
	
	public function add_card($customer_id)
	{
		//update all other cards as not default
		$this->db->where('customer_id', $customer_id);
		if($this->db->update('card', array('card_default' => 0)))
		{
			$data = array(
				'created' => date('Y-m-d H:i:s'),
				'customer_id' => $customer_id,
				'card_number' => $this->input->post('card_number'),
				'card_expiry_year' => $this->input->post('card_expiry_year'),
				'card_expiry_month' => $this->input->post('card_expiry_month'),
				'card_cvc' => $this->input->post('card_cvc'),
				'stripe_token' => $this->input->post('stripe_token'),
				'card_default' => 1
			);
			
			if($this->db->insert('card', $data))
			{
				$return['message'] = 'true';
			}
			
			//in case of registration error
			else
			{
				$return['message'] = 'false';
				$return['response'] = 'Unable to create card';
			}
		}
		
		//in case of registration error
		else
		{
			$return['message'] = 'false';
			$return['response'] = 'Unable to update card';
		}
		
		return $return;
	}
	
	public function get_customer($customer_id)
	{
		$this->db->where('customer_id = '.$customer_id);
		$query = $this->db->get('customer');
		
		return $query;
	}
	
	public function subscribe_customer($customer_id, $plan_id, $stripe_subscription_id)
	{
		//update all other cards as not default
		$this->db->where('customer_id', $customer_id);
		if($this->db->update('subscription', array('subscription_status' => 0)))
		{
			$data = array(
				'subscription_date' => date('Y-m-d H:i:s'),
				'customer_id' => $customer_id,
				'plan_id' => $plan_id,
				'stripe_subscription_id' => $stripe_subscription_id,
				'subscription_status' => 1
			);
			
			if($this->db->insert('subscription', $data))
			{
				return TRUE;
			}
			
			//in case of registration error
			else
			{
				return FALSE;
			}
		}
		
		//in case of registration error
		else
		{
			return FALSE;
		}
	}
	
	public function get_customer_subscription($customer_id)
	{
		$where = array(
			'customer_id' => $customer_id,
			'subscription_status' => 1
		);
		
		$this->db->where($where);
		return $this->db->get('subscription');
	}
	
	public function cancel_subscription($subscription_id)
	{
		$where = array(
			'subscription_id' => $subscription_id
		);
		
		$this->db->where($where);
		return $this->db->update('subscription', array('subscription_status' => 0));
	}
	
	public function set_default_card($customer_id, $card_id)
	{
		//update all other cards as not default
		$this->db->where('customer_id', $customer_id);
		if($this->db->update('card', array('card_default' => 0)))
		{
			$data = array(
				'card_default' => 1
			);
			
			$this->db->where(array('card_id' => $card_id, 'customer_id' => $customer_id));
			if($this->db->update('card', $data))
			{
				return TRUE;
			}
			
			//in case of registration error
			else
			{
				return FALSE;
			}
		}
		
		//in case of registration error
		else
		{
			return FALSE;
		}
	}
}