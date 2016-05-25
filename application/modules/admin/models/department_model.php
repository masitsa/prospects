<?php

class Department_model extends CI_Model 
{	
	public function upload_department_image($department_path, $edit = NULL)
	{
		//upload product's gallery images
		$resize['width'] = 500;
		$resize['height'] = 500;
		
		if(!empty($_FILES['department_image']['tmp_name']))
		{
			$image = $this->session->userdata('department_file_name');
			
			if((!empty($image)) || ($edit != NULL))
			{
				if($edit != NULL)
				{
					$image = $edit;
				}
				//delete any other uploaded image
				$this->file_model->delete_file($department_path."\\".$image, $department_path);
				
				//delete any other uploaded thumbnail
				$this->file_model->delete_file($department_path."\\thumbnail_".$image, $department_path);
			}
			//Upload image
			$response = $this->file_model->upload_file($department_path, 'department_image', $resize, 'height');
			if($response['check'])
			{
				$file_name = $response['file_name'];
				$thumb_name = $response['thumb_name'];
				
				//crop file to 1920 by 1010
				$response_crop = $this->file_model->crop_file($department_path."\\".$file_name, $resize['width'], $resize['height']);
				
				if(!$response_crop)
				{
					$this->session->set_userdata('department_error_message', $response_crop);
				
					return FALSE;
				}
				
				else
				{
					//Set sessions for the image details
					$this->session->set_userdata('department_file_name', $file_name);
					$this->session->set_userdata('department_thumb_name', $thumb_name);
				
					return TRUE;
				}
			}
		
			else
			{
				$this->session->set_userdata('department_error_message', $response['error']);
				
				return FALSE;
			}
		}
		
		else
		{
			$this->session->set_userdata('department_error_message', '');
			return FALSE;
		}
	}
	
	public function get_all_departments($table, $where, $per_page, $page)
	{
		//retrieve all departments
		$this->db->from($table);
		$this->db->select('*');
		$this->db->where($where);
		$this->db->order_by('department_name');
		$query = $this->db->get('', $per_page, $page);
		
		return $query;
	}
	
	/*
	*	Delete an existing department
	*	@param int $department_id
	*
	*/
	public function delete_department($department_id)
	{
		if($this->db->delete('department', array('department_id' => $department_id)))
		{
			return TRUE;
		}
		else{
			return FALSE;
		}
	}
	
	/*
	*	Activate a deactivated department
	*	@param int $department_id
	*
	*/
	public function activate_department($department_id)
	{
		$data = array(
				'department_status' => 1
			);
		$this->db->where('department_id', $department_id);
		
		if($this->db->update('department', $data))
		{
			return TRUE;
		}
		else{
			return FALSE;
		}
	}
	
	/*
	*	Deactivate an activated department
	*	@param int $department_id
	*
	*/
	public function deactivate_department($department_id)
	{
		$data = array(
				'department_status' => 0
			);
		$this->db->where('department_id', $department_id);
		
		if($this->db->update('department', $data))
		{
			return TRUE;
		}
		else{
			return FALSE;
		}
	}
	
	public function get_active_departments()
	{
  		$table = "department";
		$where = "department_status = 1";
		
		$this->db->where($where);
		$query = $this->db->get($table);
		
		return $query;
	}
	
	public function get_department_id($department_name)
	{
  		$table = "department";
		$where = array('department_name' => $department_name);
		
		$this->db->where($where);
		$query = $this->db->get($table);
		
		if($query->num_rows() > 0)
		{
			$row = $query->row();
			$department_id = $row->department_id;
		}
		
		else
		{
			$department_id = FALSE;
		}
		
		return $department_id;
	}
}