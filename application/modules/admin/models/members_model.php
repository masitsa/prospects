<?php

class Members_model extends CI_Model 
{	
	/*
	*	Retrieve all members
	*
	*/
	public function all_members()
	{
		$this->db->where('member_status = 1');
		$query = $this->db->get('member');
		
		return $query;
	}
	
	/*
	*	Retrieve all members
	*	@param string $table
	* 	@param string $where
	*
	*/
	public function get_all_members($table, $where, $per_page, $page, $order = 'member_name', $order_method = 'ASC')
	{
		//retrieve all users
		$this->db->from($table);
		$this->db->select('*');
		$this->db->where($where);
		$this->db->order_by($order, $order_method);
		$query = $this->db->get('', $per_page, $page);
		
		return $query;
	}
	
	/*
	*	get a single member's details
	*	@param int $member_id
	*
	*/
	public function get_member($member_id)
	{
		//retrieve all users
		$this->db->from('member');
		$this->db->select('*');
		$this->db->where('member_id = '.$member_id);
		$query = $this->db->get();
		
		return $query;
	}
	
	/*
	*	Delete an existing member
	*	@param int $member_id
	*
	*/
	public function delete_member($member_id)
	{
		if($this->db->delete('member', array('member_id' => $member_id)))
		{
			return TRUE;
		}
		else{
			return FALSE;
		}
	}
	
	/*
	*	Activate a deactivated member
	*	@param int $member_id
	*
	*/
	public function activate_member($member_id)
	{
		$data = array(
				'member_status' => 1
			);
		$this->db->where('member_id', $member_id);
		
		if($this->db->update('member', $data))
		{
			return TRUE;
		}
		else{
			return FALSE;
		}
	}
	
	/*
	*	Deactivate an activated member
	*	@param int $member_id
	*
	*/
	public function deactivate_member($member_id)
	{
		$data = array(
				'member_status' => 0
			);
		$this->db->where('member_id', $member_id);
		
		if($this->db->update('member', $data))
		{
			return TRUE;
		}
		else{
			return FALSE;
		}
	}
	/*
	*	Import members template
	*
	*/
	function import_members_template()
	{
		$this->load->library('Excel');
		
		$title = 'member Members Import Template';
		$count=1;
		$row_count=0;
		
		$report[$row_count][0] = 'Member Number';
		$report[$row_count][1] = 'Member first name';
		$report[$row_count][2] = 'Member last name';
		$report[$row_count][3] = 'Title (i.e. Mr. Mrs. Miss Doc)';
		$report[$row_count][4] = 'Date of Birth (i.e. YYYY-MM-DD)';
		$report[$row_count][5] = 'Email';
		$report[$row_count][6] = 'Phone';
		$report[$row_count][7] = 'Nationality';
		$report[$row_count][8] = 'Qualifications';
		$report[$row_count][9] = 'Designation';
		$report[$row_count][10] = 'Company';
		$report[$row_count][11] = 'Company Physical Address';
		$report[$row_count][12] = 'Company Postal Address';
		$report[$row_count][13] = 'Company Post code';
		$report[$row_count][14] = 'Company Town';
		$report[$row_count][15] = 'Company Phone';
		$report[$row_count][16] = 'Company Facsimile';
		$report[$row_count][17] = 'Company Cell Phone';
		$report[$row_count][18] = 'Company Email';
		$report[$row_count][19] = 'Company Activity';
		$report[$row_count][20] = 'Position';
		
		$row_count++;
		
		//create the excel document
		$this->excel->addArray ( $report );
		$this->excel->generateXML ($title);
	}
	
	public function import_csv_members($upload_path)
	{
		//load the file model
		$this->load->model('admin/file_model');
		/*
			-----------------------------------------------------------------------------------------
			Upload csv
			-----------------------------------------------------------------------------------------
		*/
		$response = $this->file_model->upload_csv($upload_path, 'import_csv');
		
		if($response['check'])
		{
			$file_name = $response['file_name'];
			
			$array = $this->file_model->get_array_from_csv($upload_path.'/'.$file_name);
			//var_dump($array); die();
			$response2 = $this->sort_member_data($array);
		
			if($this->file_model->delete_file($upload_path."\\".$file_name, $upload_path))
			{
			}
			
			return $response2;
		}
		
		else
		{
			$this->session->set_userdata('error_message', $response['error']);
			return FALSE;
		}
	}
	public function sort_member_data($array)
	{
		//count total rows
		$total_rows = count($array);
		$total_columns = count($array[0]);//var_dump($array);die();
		
		//if products exist in array
		if(($total_rows > 0) && ($total_columns == 21))
		{
			$items['modified_by'] = $this->session->userdata('personnel_id');
			$response = '
				<table class="table table-hover table-bordered ">
					  <thead>
						<tr>
						  <th>#</th>
						  <th>Member Number</th>
						  <th>First Name</th>
						  <th>Last Names</th>
						  <th>Comment</th>
						</tr>
					  </thead>
					  <tbody>
			';
			
			//retrieve the data from array
			for($r = 1; $r < $total_rows; $r++)
			{
		
				$items = array(
					   'member_number'				=> $array[$r][0],
					   'member_first_name'			=> mysql_real_escape_string(ucwords(strtolower($array[$r][1]))),
					   'member_surname'				=> mysql_real_escape_string(ucwords(strtolower($array[$r][2]))),
					   'member_title'				=> $array[$r][3],
					   'date_of_birth'				=> $array[$r][4],
					   'member_email'				=> strtolower($array[$r][5]),
					   'member_phone'				=> $array[$r][6],
					   'member_password'			=> md5(123456),
					   'nationality'				=> $array[$r][7],
					   'qualifications'				=> $array[$r][8],
					   'designation'				=> $array[$r][9],
					   'company'					=> $array[$r][10],
					   'company_physical_address'	=> $array[$r][11],
					   'company_postal_address'		=> $array[$r][12],
					   'company_post_code'			=> $array[$r][13],
					   'company_town'				=> $array[$r][14],
					   'company_phone'				=> $array[$r][15],
					   'company_facsimile'			=> $array[$r][16],
					   'company_cell_phone'			=> $array[$r][17],
					   'company_email'				=> $array[$r][18],
					   'company_activity'			=> $array[$r][19],
					   'member_title'				=> $array[$r][20],
					   'created'     				=> date('Y-m-d H:i:s'),
					   'member_status'     			=> 1
				   );
				$comment = '';
				
				if(!empty($items['member_number']))
				{
					// check if the number already exists
					if($this->check_current_number_exisits($items['member_number']))
					{
						//number exists
						$comment .= '<br/>Duplicate member number entered';
						$class = 'danger';
					}
					else
					{
						// number does not exisit
						//save product in the db
						if($this->db->insert('member', $items))
						{
							$comment .= '<br/>Member successfully added to the database';
							$class = 'success';
						}
						
						else
						{
							$comment .= '<br/>Internal error. Could not add member to the database. Please contact the site administrator';
							$class = 'warning';
						}
					}
				}
				
				else
				{
					$comment .= '<br/>Not saved ensure you have a member number entered';
					$class = 'danger';
				}
				
				
				$response .= '
					
						<tr class="'.$class.'">
							<td>'.$r.'</td>
							<td>'.$items['member_number'].'</td>
							<td>'.$items['member_first_name'].'</td>
							<td>'.$items['member_surname'].'</td>
							<td>'.$comment.'</td>
						</tr> 
				';
			}
			
			$response .= '</table>';
			
			$return['response'] = $response;
			$return['check'] = TRUE;
		}
		
		//if no products exist
		else
		{
			$return['response'] = 'Member data not found ';
			$return['check'] = FALSE;
		}
		
		return $return;
	}
	
	public function check_current_number_exisits($member_number)
	{
		$this->db->where('member_number', $member_number);
		
		$query = $this->db->get('member');
		
		if($query->num_rows() > 0)
		{
			return TRUE;
		}
		
		else
		{
			return FALSE;
		}
	}
		
	public function all_parent_members()
	{
	}
}
?>