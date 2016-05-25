<?php   if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once "./application/modules/admin/controllers/admin.php";

class Trainings extends admin {
	var $training_path;
	var $training_location;
	
	function __construct()
	{
		parent:: __construct();
		$this->load->model('users_model');
		$this->load->model('training_model');
		$this->load->model('department_model');
		$this->load->model('file_model');
		
		$this->load->library('image_lib');
		
		//path to image directory
		$this->training_path = realpath(APPPATH . '../assets/training');
		$this->training_location = base_url().'assets/training/';
	}
    
	/*
	*
	*	Default action is to show all the registered training
	*
	*/
	public function index() 
	{
		$where = 'training_id > 0';
		$table = 'training';
		$segment = 3;
		//pagination
		$this->load->library('pagination');
		$config['base_url'] = base_url().'trainings';
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
		$query = $this->training_model->get_all_trainings($table, $where, $config["per_page"], $page);
		
		$data['title'] = $v_data['title'] = 'All Trainings';
		if ($query->num_rows() > 0)
		{
			$v_data['query'] = $query;
			$v_data['page'] = $page;
			$v_data['training_location'] = $this->training_location;
			$v_data['active_departments'] = $this->department_model->get_active_departments();
			$data['content'] = $this->load->view('training/all_trainings', $v_data, true);
		}
		
		else
		{
			$data['content'] = '<a href="'.site_url().'administration/add-training" class="btn btn-success pull-right">Add Training</a>There are no trainings';
		}
		
		$this->load->view('templates/general_page', $data);
	}
	
	function add_training()
	{
		$v_data['training_location'] = 'http://placehold.it/500x500';
		
		$this->session->unset_userdata('training_error_message');
		
		//upload image if it has been selected
		$response = $this->training_model->upload_training_image($this->training_path);
		if($response)
		{
			$v_data['training_location'] = $this->training_location.$this->session->userdata('training_file_name');
		}
		
		//case of upload error
		else
		{
			$v_data['training_error'] = $this->session->userdata('training_error_message');
		}
		
		$training_error = $this->session->userdata('training_error_message');
		
		$this->form_validation->set_rules('training_name', 'Training name', 'trim|xss_clean');
		$this->form_validation->set_rules('start_date', 'Training start date', 'trim|xss_clean');
		$this->form_validation->set_rules('end_date', 'Training end date', 'trim|xss_clean');
		$this->form_validation->set_rules('training_description', 'Description', 'trim|xss_clean');
		$this->form_validation->set_rules('department_id', 'Department', 'numeric|xss_clean');

		if ($this->form_validation->run())
		{	
			if(empty($training_error))
			{
				$data2 = array(
					'training_name'=>$this->input->post("training_name"),
					'start_date'=>$this->input->post("start_date"),
					'end_date'=>$this->input->post("end_date"),
					'training_description'=>$this->input->post("training_description"),
					'training_status'=>1,
					'training_image_name'=>$this->session->userdata('training_file_name'),
					'department_id'=>$this->input->post("department_id")
				);
				
				$table = "training";
				$this->db->insert($table, $data2);
				$this->session->unset_userdata('training_file_name');
				$this->session->unset_userdata('training_thumb_name');
				$this->session->unset_userdata('training_error_message');
				$this->session->set_userdata('success_message', 'Training has been added');
				
				redirect('trainings');
			}
		}
		
		$training = $this->session->userdata('training_file_name');
		
		if(!empty($training))
		{
			$v_data['training_location'] = $this->training_location.$this->session->userdata('training_file_name');
		}
		$v_data['error'] = $training_error;
		$v_data['active_departments'] = $this->department_model->get_active_departments();
		
		$data['content'] = $this->load->view("training/add_training", $v_data, TRUE);
		$data['title'] = 'Add Training';
		
		$this->load->view('templates/general_page', $data);
	}
	
	function edit_training($training_id, $page)
	{
		//get training data
		$table = "training";
		$where = "training_id = ".$training_id;
		
		$this->db->where($where);
		$trainings_query = $this->db->get($table);
		$training_row = $trainings_query->row();
		$v_data['training_row'] = $training_row;
		$v_data['training_location'] = $this->training_location.$training_row->training_image_name;
		
		$this->session->unset_userdata('training_error_message');
		
		//upload image if it has been selected
		$response = $this->training_model->upload_training_image($this->training_path, $edit = $training_row->training_image_name);
		if($response)
		{
			$v_data['training_location'] = $this->training_location.$this->session->userdata('training_file_name');
		}
		
		//case of upload error
		else
		{
			$v_data['training_error'] = $this->session->userdata('training_error_message');
		}
		
		$training_error = $this->session->userdata('training_error_message');
		
		$this->form_validation->set_rules('training_name', 'Training name', 'trim|xss_clean');
		$this->form_validation->set_rules('start_date', 'Training start date', 'trim|xss_clean');
		$this->form_validation->set_rules('end_date', 'Training end date', 'trim|xss_clean');
		$this->form_validation->set_rules('training_description', 'Description', 'trim|xss_clean');
		$this->form_validation->set_rules('department_id', 'Department', 'numeric|xss_clean');

		if ($this->form_validation->run())
		{	
			if(empty($training_error))
			{
		
				$training = $this->session->userdata('training_file_name');
				
				if($training == FALSE)
				{
					$training = $training_row->training_image_name;
				}
				$data2 = array(
					'training_name'=>$this->input->post("training_name"),
					'start_date'=>$this->input->post("start_date"),
					'end_date'=>$this->input->post("end_date"),
					'training_description'=>$this->input->post("training_description"),
					'department_id'=>$this->input->post("department_id"),
					'training_image_name'=>$training
				);
				
				$table = "training";
				$this->db->where('training_id', $training_id);
				$this->db->update($table, $data2);
				$this->session->unset_userdata('training_file_name');
				$this->session->unset_userdata('training_thumb_name');
				$this->session->unset_userdata('training_error_message');
				$this->session->set_userdata('success_message', 'Training has been edited');
				
				redirect('trainings/'.$page);
			}
		}
		
		$training = $this->session->userdata('training_file_name');
		
		if(!empty($training))
		{
			$v_data['training_location'] = $this->training_location.$this->session->userdata('training_file_name');
		}
		$v_data['error'] = $training_error;
		$v_data['active_departments'] = $this->department_model->get_active_departments();
		
		$data['content'] = $this->load->view("training/edit_training", $v_data, TRUE);
		$data['title'] = 'Edit Training';
		
		$this->load->view('templates/general_page', $data);
	}
    
	/*
	*
	*	Delete an existing training
	*	@param int $training_id
	*
	*/
	function delete_training($training_id, $page)
	{
		//get training data
		$table = "training";
		$where = "training_id = ".$training_id;
		
		$this->db->where($where);
		$trainings_query = $this->db->get($table);
		$training_row = $trainings_query->row();
		$training_path = $this->training_path;
		
		$image_name = $training_row->training_image_name;
		
		//delete any other uploaded image
		$this->file_model->delete_file($training_path."\\".$image_name);
		
		//delete any other uploaded thumbnail
		$this->file_model->delete_file($training_path."\\thumbnail_".$image_name);
		
		if($this->training_model->delete_training($training_id))
		{
			$this->session->set_userdata('success_message', 'Training has been deleted');
		}
		
		else
		{
			$this->session->set_userdata('error_message', 'Training could not be deleted');
		}
		redirect('trainings/'.$page);
	}
    
	/*
	*
	*	Activate an existing training
	*	@param int $training_id
	*
	*/
	public function activate_training($training_id, $page)
	{
		if($this->training_model->activate_training($training_id))
		{
			$this->session->set_userdata('success_message', 'Training has been activated');
		}
		
		else
		{
			$this->session->set_userdata('error_message', 'Training could not be activated');
		}
		redirect('trainings/'.$page);
	}
    
	/*
	*
	*	Deactivate an existing training
	*	@param int $training_id
	*
	*/
	public function deactivate_training($training_id, $page)
	{
		if($this->training_model->deactivate_training($training_id))
		{
			$this->session->set_userdata('success_message', 'Training has been disabled');
		}
		
		else
		{
			$this->session->set_userdata('error_message', 'Training could not be disabled');
		}
		redirect('trainings/'.$page);
	}
	
	public function send_send_sms()
	{
		//select all trainees
		$trainees = $this->training_model->get_attendees(1);
		$this->load->model('site/site_model');
		echo '
		<table border="1">
			<tr>
				<th>#</th>
				<th>Trainee</th>
				<th>Number</th>
				<th>Status</th>
				<th>Cost</th>
			</tr>
		';
		
		if($trainees->num_rows() > 0)
		{
			$count = 0;
			foreach($trainees->result() as $res)
			{
				$trainee_id = $res->trainee_id;
				$trainee_fname = $res->trainee_fname;
				$trainee_mname = $res->trainee_mname;
				$trainee_lname = $res->trainee_lname;
				$trainee_company = $res->trainee_company;
				$trainee_role = $res->trainee_role;
				$trainee_email = $res->trainee_email;
				$trainee_phone = $res->trainee_phone;
				$created = $res->created;
				$last_modified = $res->last_modified;
				$count++;
		
				//Send confirmation SMS
				$phone = $trainee_phone;
				$response = $this->site_model->sms( 'Kindly remember to come with your laptops as we intend to have a practical session. Free wifi will be provided. Quadrant Africa Ltd', '+254'.$phone);
				
				$number = '';
				$status = '';
				$cost = '';
				
				foreach($response as $result) {
					// status is either "Success" or "error message"
					$number = $result->number;
					$status = $result->status;
					$cost = $result->cost;
				}
				
				echo '
				<tr>
					<td>'.$count.'</td>
					<td>'.$trainee_fname.' '.$trainee_mname.' '.$trainee_lname.'</td>
					<td>'.$number.'</td>
					<td>'.$status.'</td>
					<td>'.$cost.'</td>
				</tr>
				';
			}
		}
		
		echo '</table>';
	}
}
?>