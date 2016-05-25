<?php   if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once "./application/modules/admin/controllers/admin.php";

class Dobis extends admin 
{
	var $dobis_path;
	var $dobis_location;
	
	function __construct()
	{
		parent:: __construct();
		$this->load->model('users_model');
		$this->load->model('dobis_model');
	}
    
	/*
	*
	*	Default action is to show all the dobis
	*
	*/
	public function index($order = 'dobi_first_name', $order_method = 'ASC') 
	{
		$where = 'dobi.neighbourhood_id = neighbourhood.neighbourhood_id';
		$table = 'dobi, neighbourhood';
		//pagination
		$segment = 5;
		$this->load->library('pagination');
		$config['base_url'] = site_url().'admin/dobis/'.$order.'/'.$order_method;
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
		$query = $this->dobis_model->get_all_dobis($table, $where, $config["per_page"], $page, $order, $order_method);
		
		//change of order method 
		if($order_method == 'DESC')
		{
			$order_method = 'ASC';
		}
		
		else
		{
			$order_method = 'DESC';
		}
		
		$data['title'] = 'Dobis';
		$v_data['title'] = $data['title'];
		
		$v_data['order'] = $order;
		$v_data['order_method'] = $order_method;
		$v_data['query'] = $query;
		$v_data['all_dobis'] = $this->dobis_model->all_dobis();
		$v_data['page'] = $page;
		$data['content'] = $this->load->view('dobis/all_dobis', $v_data, true);
		
		$this->load->view('templates/general_page', $data);
	}
    
	/*
	*
	*	Add a new dobi
	*
	*/
	public function add_dobi() 
	{
		//form validation rules
		$this->form_validation->set_rules('dobi_name', 'Dobi Name', 'required|xss_clean');
		$this->form_validation->set_rules('dobi_status', 'Dobi Status', 'required|xss_clean');
		$this->form_validation->set_rules('dobi_preffix', 'Dobi Preffix', 'required|is_unique[dobi.dobi_preffix]|xss_clean');
		$this->form_validation->set_rules('dobi_parent', 'Dobi Parent', 'required|xss_clean');
		$this->form_validation->set_message("is_unique", "A unique preffix is requred.");
		
		//if form has been submitted
		if ($this->form_validation->run())
		{
			//upload product's gallery images
			$resize['width'] = 600;
			$resize['height'] = 800;
			
			if(is_uploaded_file($_FILES['dobi_image']['tmp_name']))
			{
				$this->load->library('image_lib');
				
				$dobis_path = $this->dobis_path;
				/*
					-----------------------------------------------------------------------------------------
					Upload image
					-----------------------------------------------------------------------------------------
				*/
				$response = $this->file_model->upload_file($dobis_path, 'dobi_image', $resize);
				if($response['check'])
				{
					$file_name = $response['file_name'];
					$thumb_name = $response['thumb_name'];
				}
			
				else
				{
					$this->session->set_userdata('error_message', $response['error']);
					
					$data['title'] = 'Add New Dobi';
					$v_data['all_dobis'] = $this->dobis_model->all_dobis();
					$data['content'] = $this->load->view('dobis/add_dobi', $v_data, true);
					$this->load->view('templates/general_page', $data);
					break;
				}
			}
			
			else{
				$file_name = '';
				$thumb_name = '';
			}
			
			if($this->dobis_model->add_dobi($file_name))
			{
				$this->session->set_userdata('success_message', 'Dobi added successfully');
				redirect('admin/dobis');
			}
			
			else
			{
				$this->session->set_userdata('error_message', 'Could not add dobi. Please try again');
			}
		}
		
		//open the add new dobi
		
		$data['title'] = 'Add dobi';
		$v_data['title'] = $data['title'];
		$v_data['all_dobis'] = $this->dobis_model->all_parent_dobis();
		$data['content'] = $this->load->view('dobis/add_dobi', $v_data, true);
		$this->load->view('templates/general_page', $data);
	}
    
	/*
	*
	*	Edit an existing dobi
	*	@param int $dobi_id
	*
	*/
	public function edit_dobi($dobi_id) 
	{
		//form validation rules
		$this->form_validation->set_rules('dobi_name', 'Dobi Name', 'required|xss_clean');
		$this->form_validation->set_rules('dobi_status', 'Dobi Status', 'required|xss_clean');
		$this->form_validation->set_rules('dobi_preffix', 'Dobi Preffix', 'required|xss_clean');
		$this->form_validation->set_rules('dobi_parent', 'Dobi Parent', 'required|xss_clean');
		$this->form_validation->set_message("is_unique", "A unique preffix is requred.");
		
		//if form has been submitted
		if ($this->form_validation->run())
		{
			//upload product's gallery images
			$resize['width'] = 600;
			$resize['height'] = 800;
			
			if(is_uploaded_file($_FILES['dobi_image']['tmp_name']))
			{
				$dobis_path = $this->dobis_path;
				$dobis_location = $this->dobis_location;
				
				//delete original image
				$this->file_model->delete_file($dobis_path."\\".$this->input->post('current_image'), $dobis_location);
				
				//delete original thumbnail
				$this->file_model->delete_file($dobis_path."\\thumbnail_".$this->input->post('current_image'), $dobis_location);
				/*
				/*
					-----------------------------------------------------------------------------------------
					Upload image
					-----------------------------------------------------------------------------------------
				*/
				$response = $this->file_model->upload_file($dobis_path, 'dobi_image', $resize);
				if($response['check'])
				{
					$file_name = $response['file_name'];
					$thumb_name = $response['thumb_name'];
				}
			
				else
				{
					$this->session->set_userdata('error_message', $response['error']);
					
					/*$data['title'] = 'Edit Dobi';
					$query = $this->dobis_model->get_dobi($dobi_id);
					if ($query->num_rows() > 0)
					{
						$v_data['dobi'] = $query->result();
						$v_data['all_dobis'] = $this->dobis_model->all_dobis();
						$data['content'] = $this->load->view('dobis/edit_dobi', $v_data, true);
					}
					
					else
					{
						$data['content'] = 'dobi does not exist';
					}
					
					$this->load->view('templates/general_page', $data);
					break;*/
					$file_name = '';
				}
			}
			
			else{
				$file_name = $this->input->post('current_image');
			}
			//update dobi
			if($this->dobis_model->update_dobi($file_name, $dobi_id))
			{
				$this->session->set_userdata('success_message', 'Dobi updated successfully');
				redirect('admin/dobis');
			}
			
			else
			{
				$this->session->set_userdata('error_message', 'Could not update dobi. Please try again');
			}
		}
		
		//open the add new dobi
		$data['title'] = 'Edit dobi';
		$v_data['title'] = $data['title'];
		
		//select the dobi from the database
		$query = $this->dobis_model->get_dobi($dobi_id);
		
		if ($query->num_rows() > 0)
		{
			$v_data['dobi'] = $query->result();
			$v_data['all_dobis'] = $this->dobis_model->all_parent_dobis();
			
			$data['content'] = $this->load->view('dobis/edit_dobi', $v_data, true);
		}
		
		else
		{
			$data['content'] = 'Dobi does not exist';
		}
		
		$this->load->view('templates/general_page', $data);
	}
    
	/*
	*
	*	Delete an existing dobi
	*	@param int $dobi_id
	*
	*/
	public function delete_dobi($dobi_id)
	{
		//delete dobi image
		$query = $this->dobis_model->get_dobi($dobi_id);
		
		if ($query->num_rows() > 0)
		{
			$result = $query->result();
			$image = $result[0]->dobi_image_name;
			
			$this->load->model('file_model');
			//delete image
			$this->file_model->delete_file($this->dobis_path."/images/".$image);
			//delete thumbnail
			$this->file_model->delete_file($this->dobis_path."/thumbs/".$image);
		}
		$this->dobis_model->delete_dobi($dobi_id);
		$this->session->set_userdata('success_message', 'Dobi has been deleted');
		redirect('admin/dobis');
	}
    
	/*
	*
	*	Activate an existing dobi
	*	@param int $dobi_id
	*
	*/
	public function activate_dobi($dobi_id)
	{
		$this->dobis_model->activate_dobi($dobi_id);
		$this->session->set_userdata('success_message', 'Dobi activated successfully');
		redirect('admin/dobis');
	}
    
	/*
	*
	*	Deactivate an existing dobi
	*	@param int $dobi_id
	*
	*/
	public function deactivate_dobi($dobi_id)
	{
		$this->dobis_model->deactivate_dobi($dobi_id);
		$this->session->set_userdata('success_message', 'Dobi disabled successfully');
		redirect('admin/dobis');
	}
}
?>