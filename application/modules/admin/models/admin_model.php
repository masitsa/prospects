<?php

class Admin_model extends CI_Model 
{
	/*
	*	Get admin section parents
	*
	*/
	public function get_admin_section_parents()
	{
		$this->db->where(array('section_status' => 1, 'section_parent' => 0));
		$this->db->order_by('section_position');
		return $this->db->get('section');
	}
	
	/*
	*	Get admin section children
	*
	*/
	public function get_admin_section_children()
	{
		$this->db->where(array('section_user' => 1, 'section_parent >' => 0));
		$this->db->order_by('section_parent');
		return $this->db->get('section');
	}
	
	/*
	*	Check if parent has children
	*
	*/
	public function check_children($children, $section_id, $web_name)
	{
		$section_children = array();
		
		if($children->num_rows() > 0)
		{
			foreach($children->result() as $res)
			{
				$parent = $res->section_parent;
				
				if($parent == $section_id)
				{
					$section_name = $res->section_name;
					
					$child_array = array
					(
						'section_name' => $section_name,
						'link' => site_url().$web_name.'/'.strtolower($this->site_model->create_web_name($section_name)),
					);
					
					array_push($section_children, $child_array);
				}
			}
		}
		
		return $section_children;
	}
	
	public function get_breadcrumbs()
	{
		$page = explode("/",uri_string());
		$total = count($page);
		$last = $total - 1;
		$crumbs = '<li><a href="'.site_url().'dashboard"><i class="fa fa-home"></i></a></li>';
		
		for($r = 0; $r < $total; $r++)
		{
			$name = $this->site_model->decode_web_name($page[$r]);
			if($r == $last)
			{
				$crumbs .= '<li><span>'.strtoupper($name).'</span></li>';
			}
			else
			{
				if($total == 3)
				{
					if($r == 1)
					{
						$crumbs .= '<li><a href="'.site_url().$page[$r-1].'/'.strtolower($name).'">'.strtoupper($name).'</a></li>';
					}
					else
					{
						$crumbs .= '<li><a href="'.site_url().strtolower($name).'">'.strtoupper($name).'</a></li>';
					}
				}
				else
				{
					$crumbs .= '<li><a href="'.site_url().strtolower($name).'">'.strtoupper($name).'</a></li>';
				}
			}
		}
		
		return $crumbs;
	}
	
	public function create_breadcrumbs($title)
	{
		$crumbs = '<li><a href="'.site_url().'dashboard"><i class="fa fa-home"></i></a></li>';
		$crumbs .= '<li><span>'.strtoupper($title).'</span></li>';
		
		return $crumbs;
	}
}
?>