<?php   if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once "./application/modules/admin/controllers/admin.php";

class Departments extends admin {
	var $department_path;
	var $department_location;
	
	function __construct()
	{
		parent:: __construct();
		$this->load->model('users_model');
		$this->load->model('department_model');
		$this->load->model('file_model');
		
		$this->load->library('image_lib');
		
		//path to image directory
		$this->department_path = realpath(APPPATH . '../assets/department');
		$this->department_location = base_url().'assets/department/';
	}
    
	/*
	*
	*	Default action is to show all the registered department
	*
	*/
	public function index() 
	{
		$where = 'department_id > 0';
		$table = 'department';
		$segment = 3;
		//pagination
		$this->load->library('pagination');
		$config['base_url'] = base_url().'administration/all-departments';
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
        $data["links"] = $this->pagination->create_links();
		$query = $this->department_model->get_all_departments($table, $where, $config["per_page"], $page);
		
		if ($query->num_rows() > 0)
		{
			$v_data['query'] = $query;
			$v_data['page'] = $page;
			$v_data['department_location'] = $this->department_location;
			$data['content'] = $this->load->view('department/all_departments', $v_data, true);
		}
		
		else
		{
			$data['content'] = '<a href="'.site_url().'administration/add-department" class="btn btn-success pull-right">Add Department</a>There are no departments';
		}
		$data['title'] = 'All Departments';
		
		$this->load->view('templates/general_admin', $data);
	}
	
	function add_department()
	{
		$v_data['department_location'] = 'http://placehold.it/500x500';
		
		$this->session->unset_userdata('department_error_message');
		
		//upload image if it has been selected
		$response = $this->department_model->upload_department_image($this->department_path);
		if($response)
		{
			$v_data['department_location'] = $this->department_location.$this->session->userdata('department_file_name');
		}
		
		//case of upload error
		else
		{
			$v_data['department_error'] = $this->session->userdata('department_error_message');
		}
		
		$department_error = $this->session->userdata('department_error_message');
		
		$this->form_validation->set_rules('department_name', 'Title', 'trim|xss_clean');
		$this->form_validation->set_rules('department_description', 'Description', 'trim|xss_clean');

		if ($this->form_validation->run())
		{	
			if(empty($department_error))
			{
				$data2 = array(
					'department_name'=>$this->input->post("department_name"),
					'department_description'=>$this->input->post("department_description"),
					'department_status'=>1,
					'department_image_name'=>$this->session->userdata('department_file_name'),
					'department_web_name' => $this->users_model->create_web_name($this->input->post("department_name")),
					'created_by' => $this->session->userdata('user_id'),
					'created' => date('Y-m-d H:i:s'),
					'modified_by' => $this->session->userdata('user_id')
				);
				
				$table = "department";
				$this->db->insert($table, $data2);
				$this->session->unset_userdata('department_file_name');
				$this->session->unset_userdata('department_thumb_name');
				$this->session->unset_userdata('department_error_message');
				$this->session->set_userdata('success_message', 'Department has been added');
				
				redirect('administration/all-departments');
			}
		}
		
		$department = $this->session->userdata('department_file_name');
		
		if(!empty($department))
		{
			$v_data['department_location'] = $this->department_location.$this->session->userdata('department_file_name');
		}
		$v_data['error'] = $department_error;
		
		$data['content'] = $this->load->view("department/add_department", $v_data, TRUE);
		$data['title'] = 'Add Department';
		
		$this->load->view('templates/general_admin', $data);
	}
	
	function edit_department($department_id, $page)
	{
		//get department data
		$table = "department";
		$where = "department_id = ".$department_id;
		
		$this->db->where($where);
		$departments_query = $this->db->get($table);
		$department_row = $departments_query->row();
		$v_data['department_row'] = $department_row;
		$v_data['department_location'] = $this->department_location.$department_row->department_image_name;
		
		$this->session->unset_userdata('department_error_message');
		
		//upload image if it has been selected
		$response = $this->department_model->upload_department_image($this->department_path, $edit = $department_row->department_image_name);
		if($response)
		{
			$v_data['department_location'] = $this->department_location.$this->session->userdata('department_file_name');
		}
		
		//case of upload error
		else
		{
			$v_data['department_error'] = $this->session->userdata('department_error_message');
		}
		
		$department_error = $this->session->userdata('department_error_message');
		
		$this->form_validation->set_rules('check', 'check', 'trim|xss_clean');
		$this->form_validation->set_rules('department_name', 'Title', 'trim|xss_clean');
		$this->form_validation->set_rules('department_description', 'Description', 'trim|xss_clean');

		if ($this->form_validation->run())
		{	
			if(empty($department_error))
			{
				$department = $this->session->userdata('department_file_name');
				
				if($department == FALSE)
				{
					$department = $department_row->department_image_name;
				}
				$data2 = array(
					'department_name'=>$this->input->post("department_name"),
					'department_description'=>$this->input->post("department_description"),
					'department_image_name'=>$department,
					'department_web_name' => $this->users_model->create_web_name($this->input->post("department_name")),
					'modified_by' => $this->session->userdata('user_id')
				);
				
				$table = "department";
				$this->db->where('department_id', $department_id);
				$this->db->update($table, $data2);
				$this->session->unset_userdata('department_file_name');
				$this->session->unset_userdata('department_thumb_name');
				$this->session->unset_userdata('department_error_message');
				$this->session->set_userdata('success_message', 'Department has been edited');
				
				redirect('administration/all-departments/'.$page);
			}
		}
		
		$department = $this->session->userdata('department_file_name');
		
		if(!empty($department))
		{
			$v_data['department_location'] = $this->department_location.$this->session->userdata('department_file_name');
		}
		$v_data['error'] = $department_error;
		
		$data['content'] = $this->load->view("department/edit_department", $v_data, TRUE);
		$data['title'] = 'Edit Department';
		
		$this->load->view('templates/general_admin', $data);
	}
    
	/*
	*
	*	Delete an existing department
	*	@param int $department_id
	*
	*/
	function delete_department($department_id, $page)
	{
		//get department data
		$table = "department";
		$where = "department_id = ".$department_id;
		
		$this->db->where($where);
		$departments_query = $this->db->get($table);
		$department_row = $departments_query->row();
		$department_path = $this->department_path;
		
		$image_name = $department_row->department_image_name;
		
		//delete any other uploaded image
		$this->file_model->delete_file($department_path."\\".$image_name);
		
		//delete any other uploaded thumbnail
		$this->file_model->delete_file($department_path."\\thumbnail_".$image_name);
		
		if($this->department_model->delete_department($department_id))
		{
			$this->session->set_userdata('success_message', 'Department has been deleted');
		}
		
		else
		{
			$this->session->set_userdata('error_message', 'Department could not be deleted');
		}
		redirect('administration/all-departments/'.$page);
	}
    
	/*
	*
	*	Activate an existing department
	*	@param int $department_id
	*
	*/
	public function activate_department($department_id, $page)
	{
		if($this->department_model->activate_department($department_id))
		{
			$this->session->set_userdata('success_message', 'Department has been activated');
		}
		
		else
		{
			$this->session->set_userdata('error_message', 'Department could not be activated');
		}
		redirect('administration/all-departments/'.$page);
	}
    
	/*
	*
	*	Deactivate an existing department
	*	@param int $department_id
	*
	*/
	public function deactivate_department($department_id, $page)
	{
		if($this->department_model->deactivate_department($department_id))
		{
			$this->session->set_userdata('success_message', 'Department has been disabled');
		}
		
		else
		{
			$this->session->set_userdata('error_message', 'Department could not be disabled');
		}
		redirect('administration/all-departments/'.$page);
	}
}
?>