<?php   if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once "./application/modules/site/controllers/site.php";

class Charts extends site
{
	var $days;

	function __construct()
	{
		parent::__construct();
		
		$this->load->model('auth_model');
		$this->load->model('charts_model');
		$this->load->model('dates_model');
		$this->load->model('reports_model');
		$this->days = 7;
	}
	
	public function get_click_totals($timestamp)
	{
		$date = gmdate("Y-m-d", ($timestamp/1000));
		
		//initialize required variables
		$highest_bar = 0;
		
		//get outpatient total
		$total_android_clicks = $this->reports_model->get_total_clicks('android', $date);
		$total_ios_clicks = $this->reports_model->get_total_clicks('ios', $date);
		$total_windows_clicks = $this->reports_model->get_total_clicks('windows', $date);
		//mark the highest bar
		$highest_bar = $total_android_clicks;
		
		//prep data for the particular visit type
		$result[strtolower('android')] = $total_android_clicks;
		$result[strtolower('windows')] = $total_windows_clicks;
		$result[strtolower('ios')] = $total_ios_clicks;
		
		$result['highest_bar'] = $highest_bar;//var_dump($result['bars']);
		echo json_encode($result);
	}
	
	public function website_totals()
	{	
		//get all service types
		$banners_result = $this->reports_model->get_user_websites();
		
		//initialize required variables
		$totals = '';
		$names = '';
		$highest_bar = 0;
		$r = 1;
		
		if($banners_result->num_rows() > 0)
		{
			$result = $banners_result->result();
			
			foreach($result as $res)
			{
				$website = $res->smart_banner_website;
				$title = $res->title;
				
				//get service total
				$total = $this->reports_model->get_banner_total($website);
				
				//mark the highest bar
				if($total > $highest_bar)
				{
					$highest_bar = $total;
				}
				
				if(empty($title))
				{
					$service_name = $website;
				}
				
				else
				{
					$service_name = $title;
				}
				
				if($r == $banners_result->num_rows())
				{
					$totals .= $total;
					$names .= $service_name;
				}
				
				else
				{
					$totals .= $total.',';
					$names .= $service_name.',';
				}
				$r++;
			}
		}
		
		$result['total_services'] = $banners_result->num_rows();
		$result['names'] = $names;
		$result['bars'] = $totals;
		$result['highest_bar'] = $highest_bar;
		echo json_encode($result);
	}
}