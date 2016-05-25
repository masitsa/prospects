<?php

class Reports_model extends CI_Model 
{
	public function get_total_clicks($device = 'android', $date = NULL, $where = NULL)
	{
		if($date == NULL)
		{
			$date = date('Y-m-d');
		}
		if($where == NULL)
		{
			$where = 'click.os = \''.$device.'\' AND click.created = \''.$date.'\'';
		}
		
		else
		{
			$where .= ' AND click.os = \''.$device.'\' AND click.created = \''.$date.'\'';
		}
		
		$this->db->select('COUNT(click.click_id) AS total_clicks');
		$this->db->where($where);
		$query = $this->db->get('click');
		
		$result = $query->row();
		return $result->total_clicks;
		//$random = rand ( 100 , 10000 );
		//return $random;
	}
	
	public function get_user_websites()
	{
		$this->db->select('*');
		$this->db->where(array('customer_id' => $this->session->userdata('customer_id'), 'smart_banner_status' => 1));
		$this->db->order_by('title');
		$query = $this->db->get('smart_banner');
		
		return $query;
	}
	
	public function get_banner_total($website, $date = NULL)
	{
		
		$table = 'click';
		
		$where = 'click.website = \''.$website.'\'';
		
		if($date != NULL)
		{
			$where .= ' AND click.created LIKE \''.$date.'%\'';
		}
		
		/*$visit_search = $this->session->userdata('all_departments_search');
		if(!empty($visit_search))
		{
			$where = 'visit_charge.service_charge_id = service_charge.service_charge_id AND service_charge.service_id = '.$service_id.' AND visit.visit_id = visit_charge.visit_id'. $visit_search;
			$table .= ', visit';
		}*/
		
		$this->db->select('COUNT(click.click_id) AS banner_total');
		$this->db->where($where);
		$query = $this->db->get($table);
		
		$result = $query->row();
		$total = $result->banner_total;;
		
		if($total == NULL)
		{
			$total = 0;
		}
		
		//$total = rand ( 100 , 10000 );
		return $total;
	}
}
?>