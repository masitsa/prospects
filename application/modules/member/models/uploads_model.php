<?php
class Uploads_model extends CI_Model 
{
	public function upload_any_file($path, $location, $name, $upload, $edit = NULL)
	{
		if(!empty($_FILES[$upload]['tmp_name']))
		{
			$image = $this->session->userdata($name);
			
			if((!empty($image)) || ($edit != NULL))
			{
				if($edit != NULL)
				{
					$image = $edit;
				}
				
				//delete any other uploaded image
				if($this->file_model->delete_file($path."\\".$image, $location))
				{
					//delete any other uploaded thumbnail
					$this->file_model->delete_file($path."\\thumbnail_".$image, $location);
				}
				
				else
				{
					$this->file_model->delete_file($path."/".$image, $location);
					$this->file_model->delete_file($path."/thumbnail_".$image, $location);
				}
			}
			//Upload image
			$response = $this->file_model->upload_any_file($path, $upload);
			if($response['check'])
			{
				$file_name = $response['file_name'];
					
				//Set sessions for the image details
				$this->session->set_userdata($name, $file_name);
			
				return TRUE;
			}
		
			else
			{
				$this->session->set_userdata('upload_error_message', $response['error']);
				
				return FALSE;
			}
		}
		
		else
		{
			$this->session->set_userdata('upload_error_message', '');
			return FALSE;
		}
	}

	function upload_member_documents($member_id, $document)
	{
		$data = array(
			'document_name'=> $this->input->post('document_item_name'),
			'document_upload_name'=> $document,
			'created_by'=> $this->session->userdata('member_id'),
			'modified_by'=> $this->session->userdata('member_id'),
			'created'=> date('Y-m-d H:i:s'),
			'member_id'=>$member_id
		);
		
		if($this->db->insert('document_upload', $data))
		{
			return TRUE;
		}
		else{
			return FALSE;
		}
	}
	
	function get_member_uploads($member_id)
	{
		$this->db->from('document_upload');
		$this->db->select('*');
		$this->db->where('member_id = '.$member_id);
		$query = $this->db->get();
		
		return $query;
	}
	
	/*
	*	Delete an existing personnel
	*	@param int $personnel_id
	*
	*/
	public function delete_document_scan($document_upload_name, $document_upload_id, $location, $path)
	{
		//delete any other uploaded image
		if($this->file_model->delete_file($path."\\".$document_upload_name, $location))
		{
		}
		
		else
		{
			$this->file_model->delete_file($path."/".$document_upload_name, $location);
		}
		//delete parent
		if($this->db->delete('document_upload', array('document_upload_id' => $document_upload_id)))
		{
			return TRUE;
		}
		else{
			return FALSE;
		}
	}
	
	/*
	*	get a single member's details
	*	@param int $member_id
	*
	*/
	/*public function get_member_uploads($member_id)
	{
		//retrieve all users
		$this->db->from('document_upload');
		$this->db->select('*');
		$this->db->where('member_id = '.$member_id);
		$query = $this->db->get();
		
		return $query;
	}*/
}
?>