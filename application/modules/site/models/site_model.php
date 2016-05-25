<?php

class Site_model extends CI_Model 
{
	public function get_slides()
	{
  		$table = "slideshow";
		$where = "slideshow_status = 1";
		
		$this->db->where($where);
		$query = $this->db->get($table);
		
		return $query;
	}
	
	public function display_page_title()
	{
		$page = explode("/",uri_string());
		$total = count($page);
		$last = $total - 1;
		$name = $this->site_model->decode_web_name($page[$last]);
		
		if(is_numeric($name))
		{
			$last = $last - 1;
			$name = $this->site_model->decode_web_name($page[$last]);
		}
		$page_url = ucwords(strtolower($name));
		
		return $page_url;
	}
	
	function generate_price_range()
	{
		$max_price = $this->products_model->get_max_product_price();
		//$min_price = $this->products_model->get_min_product_price();
		
		$interval = $max_price/5;
		
		$range = '';
		$start = 0;
		$end = 0;
		
		for($r = 0; $r < 5; $r++)
		{
			$end = $start + $interval;
			$value = 'KES '.number_format(($start+1), 0, '.', ',').' - KES '.number_format($end, 0, '.', ',');
			$range .= '
			<label class="radio-fancy">
				<input type="radio" name="agree" value="'.$start.'-'.$end.'">
				<span class="light-blue round-corners"><i class="dark-blue round-corners"></i></span>
				<b>'.$value.'</b>
			</label>';
			
			$start = $end;
		}
		
		return $range;
	}
	
	public function get_navigation()
	{
		$page = explode("/",uri_string());
		$total = count($page);
		
		$name = strtolower($page[0]);
		
		$home = '';
		$about = '';
		$services = '';
		$projects = '';
		$contact = '';
		$gallery = '';
		$membership = '';
		$blog = '';
		$events = '';
		$resources = '';
		
		if($name == 'home')
		{
			$home = 'active';
		}
		
		if($name == 'about')
		{
			$about = 'active';
		}
		if($name == 'services')
		{
			$services = 'active';
		}
		if($name == 'projects')
		{
			$projects = 'active';
		}
		if($name == 'contact')
		{
			$contact = 'active';
		}
		if($name == 'gallery')
		{
			$gallery = 'active';
		}
		if($name == 'blog')
		{
			$blog = 'active';
		}
		if($name == 'membership')
		{
			$membership = 'active';
		}
		if($name == 'events')
		{
			$events = 'active';
		}
		if($name == 'resources')
		{
			$resources = 'active';
		}
		
		//get departments
		$query = $this->get_active_services();
		$sub_menu_services = '';
		if($query->num_rows() > 0)
		{
			foreach($query->result() as $res)
			{
				$service_name = $res->service_name;
				$web_name = $this->create_web_name($service_name);
				$sub_menu_services .= '
										<li>
											<a href="'.site_url().'services/'.$web_name.'">'.$service_name.'</a>
										</li>';
			}
		}
		$navigation = 
		'
			<li><a class="'.$home.'" href="'.site_url().'home">Home</a></li>
			<li><a class="'.$about.'" href="'.site_url().'about">About</a></li>
			
			<!-- Service Menu -->
			<li><a class="'.$services.'"  href="'.site_url().'services">Services</a></li>
			<!-- Service Menu -->
			<!-- Portfolio Menu -->
			<li><a class="'.$blog.'" href="'.site_url().'blog">Blog</a></li>
			<li><a class="'.$contact.'" href="'.site_url().'contact">Contact</a></li>
			';
		
		return $navigation;
	}
	
	public function get_active_services()
	{
  		$table = "service";
		$where = "service.service_status = 1";
		
		$this->db->select('service.*');
		$this->db->where($where);
		$this->db->group_by('service_name');
		$this->db->order_by('position', 'ASC');
		$query = $this->db->get($table);
		
		return $query;
	}
	public function get_active_service_gallery($service_id)
	{
		$table = "service_gallery";
		$where = "service_id = ".$service_id;
		
		$this->db->select('service_gallery.*');
		$this->db->where($where);
		$query = $this->db->get($table);
		
		return $query;
	}
	public function get_active_gallery()
	{
		$table = "gallery";
		$where = "gallery_status > 0";
		
		$this->db->select('gallery.*');
		$this->db->where($where);
		
		$query = $this->db->get($table);
		
		return $query;
	}
	public function get_active_service_gallery_names()
	{
		$table = "gallery";
		$where = "gallery_status > 0";
		
		$this->db->select('gallery.*');
		$this->db->where($where);
		$this->db->group_by('gallery_name');
		$query = $this->db->get($table);
		
		return $query;
	}
	public function create_web_name($field_name)
	{
		$web_name = str_replace(" ", "-", $field_name);
		
		return $web_name;
	}
	public function get_services($table, $where, $limit = NULL)
	{
		$this->db->where($where);
		$this->db->select('service.*');
		$this->db->order_by('service_name', 'ASC');
		$query = $this->db->get($table);
		
		
		return $query;
	}
	
	public function decode_web_name($web_name)
	{
		$field_name = str_replace("-", " ", $web_name);
		
		return $field_name;
	}
	
	public function image_display($base_path, $location, $image_name = NULL)
	{
		$default_image = 'http://placehold.it/300x300&text=Dobi';
		$file_path = $base_path.'/'.$image_name;
		//echo $file_path.'<br/>';
		
		//Check if image was passed
		if($image_name != NULL)
		{
			if(!empty($image_name))
			{
				if((file_exists($file_path)) && ($file_path != $base_path.'\\'))
				{
					return $location.$image_name;
				}
				
				else
				{
					return $default_image;
				}
			}
			
			else
			{
				return $default_image;
			}
		}
		
		else
		{
			return $default_image;
		}
	}
	
	public function get_contacts()
	{
  		$table = "contacts";
		
		$query = $this->db->get($table);
		$contacts = array();
		if($query->num_rows() > 0)
		{
			$row = $query->row();
			$contacts['email'] = $row->email;
			$contacts['phone'] = $row->phone;
			$contacts['facebook'] = $row->facebook;
			$contacts['twitter'] = $row->twitter;
			$contacts['linkedin'] = $row->pintrest;
			$contacts['company_name'] = $row->company_name;
			$contacts['logo'] = $row->logo;
			$contacts['address'] = $row->address;
			$contacts['city'] = $row->city;
			$contacts['post_code'] = $row->post_code;
			$contacts['building'] = $row->building;
			$contacts['floor'] = $row->floor;
			$contacts['location'] = $row->location;
			$contacts['working_weekend'] = $row->working_weekend;
			$contacts['working_weekday'] = $row->working_weekday;
			$contacts['mission'] = $row->mission;
			$contacts['vision'] = $row->vision;
			$contacts['motto'] = $row->motto;
			$contacts['about'] = $row->about;
			$contacts['objectives'] = $row->objectives;
			$contacts['core_values'] = $row->core_values;
		}
		return $contacts;
	}
	
	public function get_breadcrumbs()
	{
		$page = explode("/",uri_string());
		$total = count($page);
		$last = $total - 1;
		$crumbs = '<li><a href="'.site_url().'home">HOME </a></li>';
		
		for($r = 0; $r < $total; $r++)
		{
			$name = $this->decode_web_name($page[$r]);
			if($r == $last)
			{
				$crumbs .= '<li class="active">'.strtoupper($name).'</li>';
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
	public function contact() 
	{
		$this->load->model('site/email_model');
		$this->load->library('Mandrill', $this->config->item('mandrill_key'));
		
		$date = date('jS M Y H:i a',strtotime(date('Y-m-d H:i:s')));
		$subject = $this->input->post('sender_name')." needs some help";
		$message = '
				<p>A help message was sent on '.$date.' saying:</p> 
				<p>'.$this->input->post('message').'</p>
				<p>Their contact details are:</p>
				<p>
					Name: '.$this->input->post('sender_name').'<br/>
					Email: '.$this->input->post('sender_email').'<br/>
					Phone: '.$this->input->post('sender_phone').'
				</p>
				';
		$sender_email = $this->input->post('sender_email');
		$shopping = "";
		$from = $this->input->post('sender_name');
		
		$button = '';
		$response = $this->email_model->send_mandrill_mail('info@instorelook.com.au', "Hi", $subject, $message, $sender_email, $shopping, $from, $button, $cc = NULL);
		
		//echo var_dump($response);
		
		return $response;
	}
	
	public function get_neighbourhoods()
	{
		$this->db->order_by('neighbourhood_name');
		return $this->db->get('neighbourhood');
	}
	public function get_testimonials()
	{
		$this->db->where('post.blog_category_id = blog_category.blog_category_id AND (blog_category.blog_category_name LIKE "%testimonials%") AND post.post_status = 1');
		$this->db->order_by('post.created','ASC');
		return $this->db->get('post,blog_category');
	}
	public function get_faqs()
	{
		$this->db->where('post.blog_category_id = blog_category.blog_category_id AND (blog_category.blog_category_name LIKE "%faqs%") AND post.post_status = 1');
		$this->db->order_by('post.created','ASC');
		return $this->db->get('post,blog_category');
	}
	public function get_front_end_items()
	{
		$this->db->where('post.blog_category_id = blog_category.blog_category_id AND (blog_category.blog_category_name LIKE "%front%") AND post.post_status = 1');
		$this->db->order_by('post.created','ASC');
		$this->db->limit(1);
		return $this->db->get('post,blog_category');
	}
	
	public function valid_url($url)
	{
		$pattern = "|^http(s)?://[a-z0-9-]+(.[a-z0-9-]+)*(:[0-9]+)?(/.*)?$|i";
		//$pattern = "/^((ht|f)tp(s?)\:\/\/|~/|/)?([w]{2}([\w\-]+\.)+([\w]{2,5}))(:[\d]{1,5})?/";
        if (!preg_match($pattern, $url))
		{
            return FALSE;
        }
 
        return TRUE;
	}
	
	public function get_days($date)
	{
		$now = time(); // or your date as well
		$your_date = strtotime($date);
		$datediff = $now - $your_date;
		return floor($datediff/(60*60*24));
	}
	
	public function limit_text($text, $limit) 
	{
		$pieces = explode(" ", $text);
		$total_words = count($pieces);
		
		if ($total_words > $limit) 
		{
			$return = "<i>";
			$count = 0;
			for($r = 0; $r < $total_words; $r++)
			{
				$count++;
				if(($count%$limit) == 0)
				{
					$return .= $pieces[$r]."</i><br/><i>";
				}
				else{
					$return .= $pieces[$r]." ";
				}
			}
		}
		
		else{
			$return = "<i>".$text;
		}
		return $return.'</i><br/>';
    }
}

?>