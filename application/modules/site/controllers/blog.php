<?php   if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Blog extends MX_Controller {
	
	function __construct()
	{
		parent:: __construct();
		$this->load->model('admin/users_model');
		$this->load->model('admin/blog_model');
		$this->load->model('site/site_model');
	}
    
	/*
	*
	*	Default action is to show all the posts
	*
	*/
	public function index($category = '__', $search = '__') 
	{
		$where = 'post.blog_category_id = blog_category.blog_category_id AND post.post_status = 1';
		$segment = 3;
		$base_url = base_url().'blog/'.$category;
		
		if($search != '__')
		{
			$segment = 4;
			$search_web = $this->site_model->decode_web_name($search);
			$base_url = base_url().'blog/search/'.$search;
			$where .= ' AND (post.post_title LIKE \'%'.$search_web.'%\' OR post.post_content LIKE \'%'.$search_web.'%\' OR blog_category.blog_category_name LIKE \'%'.$search_web.'%\')';
		}
		
		if($category != '__')
		{
			$category_web = $this->site_model->decode_web_name($category);
			$segment = 4;
			$base_url = base_url().'blog/category/'.$category;
			$where .= ' AND (blog_category.blog_category_name = '.$category_web.' OR blog_category.blog_category_parent = blog_category.blog_category_id)';
		}
		
		$table = 'post, blog_category';
		//pagination
		$this->load->library('pagination');
		$config['base_url'] = $base_url;
		$config['total_rows'] = $this->users_model->count_items($table, $where);
		$config['uri_segment'] = $segment;
		$config['per_page'] = 5;
		$config['num_links'] = 5;
		
		$config['full_tag_open'] = '<ul class="pagination">';
		$config['full_tag_close'] = '</ul>';
		
		$config['next_link'] = '<i class="material-icons">chevron_right</i>';
		
		$config['prev_link'] = '<i class="material-icons">chevron_left</i>';
		
		$config['cur_tag_open'] = '<li class="active"><a href="#">';
		$config['cur_tag_close'] = '</a></li>';
		
		$config['num_tag_open'] = '<li class="waves-effect">';
		$config['num_tag_close'] = '</li>';
		$this->pagination->initialize($config);
		
		$page = ($this->uri->segment($segment)) ? $this->uri->segment($segment) : 0;
        $v_data["links"] = $this->pagination->create_links();
		$query = $this->blog_model->get_all_posts($table, $where, $config["per_page"], $page);
		
		$contacts = $this->site_model->get_contacts();
		$v_data['contacts'] = $contacts;

		if ($query->num_rows() > 0)
		{
			$v_data['query'] = $query;
			$v_data['page'] = $page;
			$v_data["first"] = $page + 1;
			$v_data["total"] = $config['total_rows'];
			
			if($v_data["total"] < $config["per_page"])
			{
				$v_data["last"] = $page + $v_data["total"];
			}
			
			else
			{
				$v_data["last"] = $page + $config["per_page"];
			}
			$data['content'] = $this->load->view('blog/blog_list', $v_data, true);
		}
		
		else
		{
			$data['content'] = '<p>There are no posts</p>';
		}
		$data['title'] = 'Blog';
		$this->load->view("site/templates/general_page", $data);
	}
	
	public function view_post($web_name)
	{
		$post_title = $this->site_model->decode_web_name($web_name);
		$post_id = $this->blog_model->get_post_id($post_title);
		$contacts = $this->site_model->get_contacts();
		$v_data['contacts'] = $contacts;
		$v_data['web_name'] = $web_name;
		if($post_id)
		{
			$this->blog_model->update_views_count($post_id);
			$query = $this->blog_model->get_post($post_id);
			$v_data['comments_query'] = $this->blog_model->get_post_comments($post_id);
			
			if ($query->num_rows() > 0)
			{
				$administrators = $this->users_model->get_all_administrators();
				if ($administrators->num_rows() > 0)
				{
					$admins = $administrators->result();
				}
				
				else
				{
					$admins = NULL;
				}
				
				foreach ($query->result() as $row)
				{
					$post_id = $row->post_id;
					$blog_category_name = $row->blog_category_name;
					$blog_category_id = $row->blog_category_id;
					$post_title = $row->post_title;
					$created_by = $row->created_by;
					$description = $row->post_content;
					$ultra_mini_title = implode(' ', array_slice(explode(' ', $post_title), 0, 4));
					$administrators = $this->users_model->get_all_administrators();
					if ($administrators->num_rows() > 0)
					{
						$admins = $administrators->result();
						if($admins != NULL)
						{
							foreach($admins as $adm)
							{
								$user_id = $adm->user_id;
								
								if($user_id == $created_by)
								{
									$created_by_name = $adm->first_name;
								}
							}
						}
					}
					
					else
					{
						$admins = NULL;
					}
				}
				
				$data['post_id'] = $post_id;
				$data['post_title'] = $post_title;
				$data['created_by'] = $created_by;
				$data['category_id'] = $blog_category_id;
				$data['ultra_mini_title'] = $ultra_mini_title;
				$data['title'] = $post_title;
				$v_data['row'] = $query->row();
				
				// get previous post 
				$prev_web_name = $this->blog_model->get_pre_next_blog($post_id,'Previous');
				$next_web_name = $this->blog_model->get_pre_next_blog($post_id,'Next');

				if($prev_web_name == NULL)
				{
					$v_data['previous_post'] = '';
				}
				else
				{
					$v_data['previous_post'] = ' <li class="pull-left"><a href="'.site_url().'blog/'.$prev_web_name.'" title="">Prev Post</a></li>';
				}

				if($next_web_name == NULL)
				{
					$v_data['next_post'] = '';
				}
				else
				{
					$v_data['next_post'] = ' <li class="pull-left"><a href="'.site_url().'blog/'.$prev_web_name.'" title="">Prev Post</a></li>';
				}
				
				$data['content'] = $this->load->view('blog/single_post', $v_data, true);
			}
			
			else
			{
				$data['post_id'] = 0;
				$data['created_by'] = 0;
				$data['category_id'] = 0;
				$data['ultra_mini_title'] = '';
				$data['post_title'] = '';
				$data['content'] = 'Post not found';
				$data['title'] = 'No active posts are available';
			}
		}
			
		else
		{
			$data['post_id'] = 0;
			$data['created_by'] = 0;
			$data['category_id'] = 0;
			$data['ultra_mini_title'] = '';
			$data['post_title'] = '';
			$data['content'] = 'Post not found';
			$data['title'] = 'No active posts are available';
		}
		
		$this->load->view("site/templates/general_page", $data);
	}
    
	/*
	*
	*	Add a new comment
	*
	*/
	public function add_comment($post_id, $web_name) 
	{
		//form validation rules
		$this->form_validation->set_rules('post_comment_description', 'Comment', 'required|xss_clean');
		$this->form_validation->set_rules('name', 'Name', 'required|xss_clean');
		$this->form_validation->set_rules('email', 'Email', 'valid_email|xss_clean');
		
		//if form has been submitted
		if ($this->form_validation->run() == FALSE)
		{
			$this->session->set_userdata('error_message', validation_errors());
			redirect('blog/'.$web_name);
		}
		
		else
		{
			if($this->blog_model->add_comment_user($post_id))
			{
				$this->session->set_userdata('success_message', 'Comment added successfully. Pending approval by admin');
				redirect('blog/'.$web_name);
			}
			
			else
			{
				$this->session->set_userdata('error_message', 'Could not add comment. Please try again');
				redirect('blog/'.$web_name);
			}
		}
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
			$web_name = $this->site_model->create_web_name($search);
			redirect('blog/search/'.$web_name);
		}
		
		else
		{
			redirect('blog');
		}
	}
}