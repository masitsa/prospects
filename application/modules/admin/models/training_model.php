<?php

class Training_model extends CI_Model 
{	
	public function upload_training_image($training_path, $edit = NULL)
	{
		//upload product's gallery images
		$resize['width'] = 500;
		$resize['height'] = 500;
		
		if(!empty($_FILES['training_image']['tmp_name']))
		{
			$image = $this->session->userdata('training_file_name');
			
			if((!empty($image)) || ($edit != NULL))
			{
				if($edit != NULL)
				{
					$image = $edit;
				}
				//delete any other uploaded image
				$this->file_model->delete_file($training_path."\\".$image, $training_path);
				
				//delete any other uploaded thumbnail
				$this->file_model->delete_file($training_path."\\thumbnail_".$image, $training_path);
			}
			//Upload image
			$response = $this->file_model->upload_file($training_path, 'training_image', $resize, 'height');
			if($response['check'])
			{
				$file_name = $response['file_name'];
				$thumb_name = $response['thumb_name'];
				
				//crop file to 1920 by 1010
				$response_crop = $this->file_model->crop_file($training_path."\\".$file_name, $resize['width'], $resize['height']);
				
				if(!$response_crop)
				{
					$this->session->set_userdata('training_error_message', $response_crop);
				
					return FALSE;
				}
				
				else
				{
					//Set sessions for the image details
					$this->session->set_userdata('training_file_name', $file_name);
					$this->session->set_userdata('training_thumb_name', $thumb_name);
				
					return TRUE;
				}
			}
		
			else
			{
				$this->session->set_userdata('training_error_message', $response['error']);
				
				return FALSE;
			}
		}
		
		else
		{
			$this->session->set_userdata('training_error_message', '');
			return FALSE;
		}
	}
	
	public function get_all_trainings($table, $where, $per_page, $page)
	{
		//retrieve all trainings
		$this->db->from($table);
		$this->db->select('*');
		$this->db->where($where);
		$this->db->order_by('training.training_date', 'DESC');
		$query = $this->db->get('', $per_page, $page);
		
		return $query;
	}
	
	public function get_training($training_id)
	{
		//retrieve all trainings
		$this->db->from('training');
		$this->db->where('training_id', $training_id);
		$query = $this->db->get();
		
		return $query;
	}
	
	/*
	*	Delete an existing training
	*	@param int $training_id
	*
	*/
	public function delete_training($training_id)
	{
		if($this->db->delete('training', array('training_id' => $training_id)))
		{
			return TRUE;
		}
		else{
			return FALSE;
		}
	}
	
	/*
	*	Activate a deactivated training
	*	@param int $training_id
	*
	*/
	public function activate_training($training_id)
	{
		$data = array(
				'training_status' => 1
			);
		$this->db->where('training_id', $training_id);
		
		if($this->db->update('training', $data))
		{
			return TRUE;
		}
		else{
			return FALSE;
		}
	}
	
	/*
	*	Deactivate an activated training
	*	@param int $training_id
	*
	*/
	public function deactivate_training($training_id)
	{
		$data = array(
				'training_status' => 0
			);
		$this->db->where('training_id', $training_id);
		
		if($this->db->update('training', $data))
		{
			return TRUE;
		}
		else{
			return FALSE;
		}
	}
	
	public function get_active_trainings()
	{
  		$table = "training";
		$where = "training_status = 1";
		
		$this->db->where($where);
		$query = $this->db->get($table);
		
		return $query;
	}
	
	public function get_attendees($training_id)
	{
  		$table = "member, attendee";
		$where = "member.member_id = attendee.member_id AND member_delete = 0 AND attendee.training_id = ".$training_id;
		
		$this->db->where($where);
		$query = $this->db->get($table);
		
		return $query;
	}
	
	public function get_tna_questions()
	{
  		$table = "tna";
		$where = "tna_id > 0";
		
		$this->db->where($where);
		$query = $this->db->get($table);
		
		return $query;
	}
	
	public function get_tna_results($training_id)
	{
  		$table = "tna_result, attendee, member";
		$where = "member.member_delete = 0 AND member.member_id = attendee.member_id AND attendee.attendee_id = tna_result.attendee_id AND attendee.training_id = ".$training_id;
		
		$this->db->select('tna_result.*, member.member_id');
		$this->db->where($where);
		$query = $this->db->get($table);
		
		return $query;
	}
	
	public function get_recent_trainings($limit = NULL)
	{
		if($limit != NULL)
		{
			$this->db->limit($limit);
		}
		
		$this->db->where('training_status = 1 AND start_date >= CURDATE()');
		$this->db->order_by('start_date', 'ASC');
		return $this->db->get('training');
	}
	
	public function get_department_trainings($department_id)
	{
		$this->db->where('training_status = 1 AND start_date >= CURDATE() AND department_id = '.$department_id);
		$this->db->order_by('start_date', 'ASC');
		return $this->db->get('training');
	}
}
