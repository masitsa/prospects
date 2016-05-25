<?php   if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Site extends MX_Controller 
{	
	var $slideshow_location;
	var $service_location;
	var $gallery_location;
	
	function __construct()
	{
		parent:: __construct();
		$this->load->model('site/site_model');
		$this->load->model('auth_model');
		$this->load->model('admin/blog_model');
		$this->load->model('banner_model');
		$this->load->model('admin/training_model');
		$this->load->model('admin/users_model');

		$this->slideshow_location = base_url().'assets/slideshow/';
		$this->service_location = base_url().'assets/service/';
		$this->gallery_location = base_url().'assets/gallery/';
	}
	
	public function without_jquery()
	{
		$this->load->view('without_jquery');
	}
    
	/*
	*
	*	Default action is to go to the home page
	*
	*/
	public function index() 
	{
		redirect('home');
	}
    
	/*
	*
	*	Sign in
	*
	*/
	public function sign_in() 
	{
		$data['title'] = $this->site_model->display_page_title();
		$data['content'] = $this->load->view("sign_in", '', TRUE);
		
		$this->load->view("site/templates/general_page", $data);
	}
    
	/*
	*
	*	Sign in
	*
	*/
	public function sign_out() 
	{
		$this->session->sess_destroy();
		
		redirect('home');
	}
    
	/*
	*
	*	Home Page
	*
	*/
	public function home_page() 
	{
		$contacts = $this->site_model->get_contacts();
		$v_data['contacts'] = $contacts;
		$v_data['gallery_location'] = $this->gallery_location;
		$v_data['gallery_images'] = $this->site_model->get_active_gallery();
		$v_data['testimonials'] = $this->site_model->get_testimonials();
		$v_data['items'] = $this->site_model->get_front_end_items();
		$v_data['slides'] = $this->site_model->get_slides();
		$v_data['services'] = $this->site_model->get_active_services();
		$v_data['latest_posts'] = $this->blog_model->get_recent_posts(4);
		$v_data['trainings'] = $this->training_model->get_recent_trainings(3);
		$v_data['faqs'] = $this->site_model->get_faqs();
		$data['title'] = $this->site_model->display_page_title();
		$v_data['service_location'] = $this->service_location;
		$data['sign_up'] = 1;
		$data['content'] = $this->load->view("home", $v_data, TRUE);
		
		$this->load->view("site/templates/general_page", $data);
	}
    
	/*
	*
	*	Register customer
	*
	*/
	public function register_customer()
	{
		$this->form_validation->set_rules('website', 'Website url', 'required|is_unique[smart_banner.smart_banner_website]');
		$this->form_validation->set_rules('name', 'Name', 'required');
		$this->form_validation->set_rules('email', 'Email', 'required');
		$this->form_validation->set_rules('image', 'Image', 'trim');
		$this->form_validation->set_message('is_unique', 'That website already exists. Please enter another one');
		
		if ($this->form_validation->run() == FALSE)
		{
			$response['message'] = 'false';
			$validation_errors = validation_errors();
			$response['result'] = $validation_errors;
		}
		else
		{
			$url = $this->input->post('website');
			
			//check for valid url
			if($this->site_model->valid_url($url))
			{
				$reply = $this->auth_model->register_user();
				
				if($reply['message'] == TRUE)
				{
					//send registration email
					$email_reply = $this->auth_model->send_registration_email($this->input->post('email'), $reply['first_name']);
					//var_dump($response);
					if($email_reply)
					{
						//$data2['success'] = $response;
					}
					
					else
					{
						//$data2['error'] = $response;
					}
					$response['message'] = 'true';
					$this->session->set_userdata('success_message', $reply['response']);
				}
				
				else
				{
					$response['message'] = 'false';
					$response['result'] = $reply['response'];
				}
			}
			
			else
			{
				$response['message'] = 'false';
				$response['result'] = 'Please enter a valid website url. Ensure it starts with http(s)://';
			}
		}
		
		echo json_encode($response);
	}
    
	/*
	*
	*	Register customer
	*
	*/
	public function sign_in_customer()
	{
		$this->form_validation->set_rules('name', 'Name', 'required');
		$this->form_validation->set_rules('email', 'Email', 'required');
		$this->form_validation->set_rules('image', 'Image', 'trim');
		
		if ($this->form_validation->run() == FALSE)
		{
			$response['message'] = 'false';
			$validation_errors = validation_errors();
			$response['result'] = $validation_errors;
		}
		else
		{
			$reply = $this->auth_model->sign_in_customer();
			
			if($reply['message'] == TRUE)
			{
				$response['message'] = 'true';
				$this->session->set_userdata('success_message', $reply['response']);
			}
			
			else
			{
				$response['message'] = 'false';
				$response['result'] = $reply['response'];
			}
		}
		
		echo json_encode($response);
	}
    
	/*
	*
	*	Search for a product
	*
	*/
	public function search()
	{
		$search = $this->input->post('search_item');
		
		if(!empty($search))
		{
			redirect('products/search/'.$search);
		}
		
		else
		{
			redirect('products/all-products');
		}
	}
    
	/*
	*
	*	FAQs
	*
	*/
	public function faqs() 
	{	
		$contacts = $this->site_model->get_contacts();
		$v_data['contacts'] = $contacts;
		
		$data['title'] = $this->site_model->display_page_title();
		$v_data['title'] = $data['title'];
		$data['contacts'] = $contacts;
		$data['content'] = $this->load->view("faqs", $v_data, TRUE);
		
		$this->load->view("site/templates/general_page", $data);
	}
    
	/*
	*
	*	terms
	*
	*/
	public function terms() 
	{	
		$contacts = $this->site_model->get_contacts();
		$v_data['contacts'] = $contacts;
		
		$data['title'] = $this->site_model->display_page_title();
		$v_data['title'] = $data['title'];
		$data['contacts'] = $contacts;
		$data['content'] = $this->load->view("terms", $v_data, TRUE);
		
		$this->load->view("site/templates/general_page", $data);
	}
    
	/*
	*
	*	privacy
	*
	*/
	public function privacy() 
	{	
		$contacts = $this->site_model->get_contacts();
		$v_data['contacts'] = $contacts;
		
		$data['title'] = $this->site_model->display_page_title();
		$v_data['title'] = $data['title'];
		$data['contacts'] = $contacts;
		$data['content'] = $this->load->view("privacy", $v_data, TRUE);
		
		$this->load->view("site/templates/general_page", $data);
	}
    
	/*
	*
	*	about
	*
	*/
	public function about() 
	{	
		$contacts = $this->site_model->get_contacts();
		$v_data['contacts'] = $contacts;
		$data['title'] = $this->site_model->display_page_title();
		$v_data['title'] = $data['title'];
		$data['contacts'] = $contacts;
		$data['content'] = $this->load->view("about", $v_data, TRUE);
		
		$this->load->view("site/templates/general_page", $data);
	}

	/*
	*
	*	about
	*
	*/
	public function services() 
	{	
		$contacts = $this->site_model->get_contacts();
		$v_data['contacts'] = $contacts;
		
		$data['title'] = $this->site_model->display_page_title();
		$v_data['title'] = $data['title'];
		$data['contacts'] = $contacts;
		$data['content'] = $this->load->view("services", $v_data, TRUE);
		
		$this->load->view("site/templates/general_page", $data);
	}

/*
	*
	*	about
	*
	*/
	public function gallery() 
	{	
		$contacts = $this->site_model->get_contacts();
		$v_data['contacts'] = $contacts;
		
		$data['title'] = $this->site_model->display_page_title();
		$v_data['gallery_location'] = $this->gallery_location;
		$v_data['title'] = $data['title'];
		$data['contacts'] = $contacts;
		$data['content'] = $this->load->view("gallery", $v_data, TRUE);
		
		$this->load->view("site/templates/gallery_page", $data);
	}

	/*
	*
	*	about
	*
	*/
	public function projects() 
	{	
		$contacts = $this->site_model->get_contacts();
		$v_data['contacts'] = $contacts;
		
		$data['title'] = $this->site_model->display_page_title();
		$v_data['title'] = $data['title'];
		$data['contacts'] = $contacts;
		$data['content'] = $this->load->view("services", $v_data, TRUE);
		
		$this->load->view("site/templates/general_page", $data);
	}

	public function service_item($service_web_name) 
	{	

		$table = "service";
		$where = 'service.service_status = 1';
		$v_data['service_location'] = $this->service_location;
		
		if($service_web_name != NULL)
		{
			$service_name = $this->site_model->decode_web_name($service_web_name);
			$where .= ' AND service.service_name = \''.$service_name.'\'';
			$v_data['services_item'] = $this->site_model->get_services($table, $where, NULL);
			$data['title'] = $service_name;
			$v_data['title'] = $service_name;
		}
		
		else
		{
			$data['title'] = 'Our Services';
			$v_data['title'] = 'Our Services';
			$v_data['services_item'] = $this->site_model->get_services($table, $where, 12);
		}
		$v_data['service_location'] = $this->service_location;


		$contacts = $this->site_model->get_contacts();
		$v_data['contacts'] = $contacts;
		
		$data['title'] = $this->site_model->display_page_title();
		$v_data['title'] = $data['title'];
		$data['contacts'] = $contacts;
		$data['content'] = $this->load->view("single_service", $v_data, TRUE);
		
		$this->load->view("site/templates/general_page", $data);
	}
    
	/*
	*
	*	Contact
	*
	*/
	public function contact()
	{
		$v_data['sender_name_error'] = '';
		$v_data['sender_email_error'] = '';
		$v_data['sender_phone_error'] = '';
		$v_data['message_error'] = '';
		
		$v_data['sender_name'] = '';
		$v_data['sender_email'] = '';
		$v_data['sender_phone'] = '';
		$v_data['message'] = '';
		
		//form validation rules
		$this->form_validation->set_error_delimiters('', '');
		$this->form_validation->set_rules('sender_name', 'Your Name', 'required');
		$this->form_validation->set_rules('sender_email', 'Email', 'required|valid_email');
		$this->form_validation->set_rules('sender_phone', 'phone', 'xss_clean');
		$this->form_validation->set_rules('message', 'Message', 'required');
		
		//if form has been submitted
		if ($this->form_validation->run())
		{
			$response = $this->site_model->contact();
			$this->session->set_userdata('success_message', 'Your message has been sent successfully. We shall get back to you as soon as possible');
		}
		else
		{
			$validation_errors = validation_errors();
			
			//repopulate form data if validation errors are present
			if(!empty($validation_errors))
			{
				//create errors
				$v_data['sender_name_error'] = form_error('sender_name');
				$v_data['sender_email_error'] = form_error('sender_email');
				$v_data['sender_phone_error'] = form_error('sender_phone');
				$v_data['message_error'] = form_error('message');
				
				//repopulate fields
				$v_data['sender_name'] = set_value('sender_name');
				$v_data['sender_email'] = set_value('sender_email');
				$v_data['sender_phone'] = set_value('sender_phone');
				$v_data['message'] = set_value('message');
			}
		}
		
		$contacts = $this->site_model->get_contacts();
		$v_data['contacts'] = $contacts;
		$v_data['items'] = $this->site_model->get_front_end_items();
		
		$data['title'] = $v_data['title'] = $this->site_model->display_page_title();
		$data['contacts'] = $contacts;
		$data['content'] = $this->load->view('contact', $v_data, true);
		
		$this->load->view("site/templates/general_page", $data);
	}
	
	public function smartbanner()
	{
		$this->load->view('smartbanner');
	}
	
	public function get_banner_details($website = NULL)
	{
		if($website != NULL)
		{
			$latest_banner = $this->banner_model->get_website_banner($website);
			
			if($latest_banner->num_rows() > 0)
			{
				$banner = $latest_banner->row();
				
				$data['smart_banner_id'] = $banner->smart_banner_id;
				$data['smart_banner_website'] = $banner->smart_banner_website;
				$data['customer_id'] = $banner->customer_id;
				$data['smart_banner_status'] = $banner->smart_banner_status;
				$data['smart_banner_created'] = $banner->smart_banner_created;
				$data['smart_banner_last_modified'] = $banner->smart_banner_last_modified;
				$data['title'] = $banner->title;
				$data['author'] = $banner->author;
				$data['price'] = $banner->price;
				$data['language'] = $banner->language;
				$data['app_store_lang'] = $banner->app_store_lang;
				$data['play_store_lang'] = $banner->play_store_lang;
				$data['amazon_store_lang'] = $banner->amazon_store_lang;
				$data['windows_store_lang'] = $banner->windows_store_lang;
				$data['play_store_params'] = $banner->play_store_params;
				$data['icon_url'] = $banner->icon_url;
				$data['ios_icon_gloss'] = $banner->ios_icon_gloss;
				$data['url'] = $banner->url;
				$data['speed_in'] = $banner->speed_in;
				$data['speed_out'] = $banner->speed_out;
				$data['days_hidden'] = $banner->days_hidden;
				$data['days_reminder'] = $banner->days_reminder;
				$data['button_text'] = $banner->button_text;
				$data['auto_scale'] = $banner->auto_scale;
				$data['force_display'] = $banner->force_display;
				$data['hide_on_install'] = $banner->hide_on_install;
				$data['overlay_layer'] = $banner->overlay_layer;
				$data['ios_universall_app'] = $banner->ios_universall_app;
				$data['append_to_selector'] = $banner->append_to_selector;
				$data['install_message'] = $banner->install_message;
				$data['close_message'] = $banner->close_message;
				$data['top_border_color'] = $banner->top_border_color;
				$data['top_gradient_color'] = $banner->top_gradient_color;
				$data['bottom_gradient_color'] = $banner->bottom_gradient_color;
				$data['text_color'] = $banner->text_color;
				$data['button_color'] = $banner->button_color;
				$data['button_text_color'] = $banner->button_text_color;
				
				if(empty($data['top_border_color']))
				{
					$data['top_border_color'] = '88B131';
				}
				if(empty($data['top_gradient_color']))
				{
					$data['top_gradient_color'] = '555555';
				}
				if(empty($data['bottom_gradient_color']))
				{
					$data['bottom_gradient_color'] = '555555';
				}
				if(empty($data['text_color']))
				{
					$data['text_color'] = 'ffffff';
				}
				if(empty($data['button_color']))
				{
					$data['button_color'] = '2196F3';
				}
				if(empty($data['button_text_color']))
				{
					$data['button_text_color'] ='ffffff';
				}
				
				$return['response'] = 'true';
				$return['message'] = $data;
			}
		
			else
			{
				$return['response'] = 'false';
				$return['message'] = 'Website not found';
			}
		}
		
		else
		{
			$return['response'] = 'false';
			$return['message'] = 'Website not added';
		}
	}
	
	public function generate_api_key($customer_id)
	{
		$api_ley =  md5(site_url().'-'.$customer_id);
		
		$this->db->where('customer_id', $customer_id);
		if($this->db->update('customer', array('customer_api_key' => $api_ley)))
		{
			echo $api_ley;
		}
		
		else
		{
			echo 'Unable to update API Key';
		}
	}
	
	public function clicks($order = 'category_name', $order_method = 'ASC') 
	{
		$where = 'category_id > 0';
		$table = 'category';
		//pagination
		$segment = 5;
		$this->load->library('pagination');
		$config['base_url'] = site_url().'admin/categories/'.$order.'/'.$order_method;
		$config['total_rows'] = $this->users_model->count_items($table, $where);
		$config['uri_segment'] = $segment;
		$config['per_page'] = 20;
		$config['num_links'] = 5;
		
		$config['full_tag_open'] = '<ul class="pagination pull-right">';
		$config['full_tag_close'] = '</ul>';
		
		$config['first_tag_open'] = '<li>';
		$config['first_tag_close'] = '</li>';
		
		$config['last_tag_open'] = '<li>';
		$config['last_tag_close'] = '</li>';
		
		$config['next_tag_open'] = '<li>';
		$config['next_link'] = 'Next';
		$config['next_tag_close'] = '</span>';
		
		$config['prev_tag_open'] = '<li>';
		$config['prev_link'] = 'Prev';
		$config['prev_tag_close'] = '</li>';
		
		$config['cur_tag_open'] = '<li class="active"><a href="#">';
		$config['cur_tag_close'] = '</a></li>';
		
		$config['num_tag_open'] = '<li>';
		$config['num_tag_close'] = '</li>';
		$this->pagination->initialize($config);
		
		$page = ($this->uri->segment($segment)) ? $this->uri->segment($segment) : 0;
        $v_data["links"] = $this->pagination->create_links();
		$query = $this->categories_model->get_all_categories($table, $where, $config["per_page"], $page, $order, $order_method);
		
		//change of order method 
		if($order_method == 'DESC')
		{
			$order_method = 'ASC';
		}
		
		else
		{
			$order_method = 'DESC';
		}
		
		$data['title'] = 'Categories';
		$v_data['title'] = $data['title'];
		
		$v_data['order'] = $order;
		$v_data['order_method'] = $order_method;
		$v_data['query'] = $query;
		$v_data['all_categories'] = $this->categories_model->all_categories();
		$v_data['page'] = $page;
		$data['content'] = $this->load->view('categories/all_categories', $v_data, true);
		
		$this->load->view('templates/general_page', $data);
	}
	
	public function check_url($url)
	{
		if($this->site_model->valid_url($url))
		{
			echo 'true';
		}
		
		else
		{
			echo 'false';
		}
	}
}
?>