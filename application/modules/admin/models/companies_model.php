<?php

class companies_model extends CI_Model 
{	
	/*
	*	Retrieve all companies
	*
	*/
	public function all_companies()
	{
		$this->db->where('company_status = 1');
		$this->db->order_by('company_name');
		$query = $this->db->get('company');
		
		return $query;
	}
	
	/*
	*	Retrieve all companies
	*	@param string $table
	* 	@param string $where
	*
	*/
	public function get_all_companies($table, $where, $per_page, $page, $order = 'company_name', $order_method = 'ASC')
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
	*	get a single company's details
	*	@param int $company_id
	*
	*/
	public function get_company($company_id)
	{
		//retrieve all users
		$this->db->from('company');
		$this->db->select('*');
		$this->db->where('company_id = '.$company_id);
		$query = $this->db->get();
		
		return $query;
	}
	
	/*
	*	Delete an existing company
	*	@param int $company_id
	*
	*/
	public function delete_company($company_id)
	{
		if($this->db->delete('company', array('company_id' => $company_id)))
		{
			return TRUE;
		}
		else{
			return FALSE;
		}
	}
	
	/*
	*	Activate a deactivated company
	*	@param int $company_id
	*
	*/
	public function activate_company($company_id)
	{
		$data = array(
				'company_status' => 1
			);
		$this->db->where('company_id', $company_id);
		
		if($this->db->update('company', $data))
		{
			return TRUE;
		}
		else{
			return FALSE;
		}
	}
	
	/*
	*	Deactivate an activated company
	*	@param int $company_id
	*
	*/
	public function deactivate_company($company_id)
	{
		$data = array(
				'company_status' => 0
			);
		$this->db->where('company_id', $company_id);
		
		if($this->db->update('company', $data))
		{
			return TRUE;
		}
		else{
			return FALSE;
		}
	}
	/*
	*	Import companies template
	*
	*/
	function import_companies_template()
	{
		$this->load->library('Excel');
		
		$title = 'company companies Import Template';
		$count=1;
		$row_count=0;
		
		$report[$row_count][0] = 'Company Name';
		$report[$row_count][1] = 'Company Physical Address';
		$report[$row_count][2] = 'Company Postal Address';
		$report[$row_count][3] = 'Company Post code';
		$report[$row_count][4] = 'Company Town';
		$report[$row_count][5] = 'Company Phone';
		$report[$row_count][6] = 'Company Facsimile';
		$report[$row_count][7] = 'Company Cell Phone';
		$report[$row_count][8] = 'Company Email';
		$report[$row_count][9] = 'Company Activity';
		
		$row_count++;
		
		//create the excel document
		$this->excel->addArray ( $report );
		$this->excel->generateXML ($title);
	}
	
	public function import_csv_companies($upload_path)
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
			$response2 = $this->sort_company_data($array);
		
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
	public function sort_company_data($array)
	{
		//count total rows
		$total_rows = count($array);
		$total_columns = count($array[0]);//var_dump($array);die();
		
		//if products exist in array
		if(($total_rows > 0) && ($total_columns == 10))
		{
			$items['modified_by'] = $this->session->userdata('personnel_id');
			$response = '
				<table class="table table-hover table-bordered ">
					  <thead>
						<tr>
						  <th>#</th>
						  <th>Company Name</th>
						  <th>Phone</th>
						  <th>Email</th>
						  <th>Comment</th>
						</tr>
					  </thead>
					  <tbody>
			';
			
			//retrieve the data from array
			for($r = 1; $r < $total_rows; $r++)
			{
		
				$items = array(
					   'company_name'				=> $array[$r][0],
					   'company_physical_address'	=> $array[$r][1],
					   'company_postal_address'		=> $array[$r][2],
					   'company_post_code'			=> $array[$r][3],
					   'company_town'				=> $array[$r][4],
					   'company_phone'				=> $array[$r][5],
					   'company_facsimile'			=> $array[$r][6],
					   'company_cell_phone'			=> $array[$r][7],
					   'company_email'				=> $array[$r][8],
					   'company_activity'			=> $array[$r][9],
					   'created'     				=> date('Y-m-d H:i:s'),
					   'company_status'     		=> 1
				   );
				$comment = '';
				
				if(!empty($items['company_name']))
				{
					// check if the number already exists
					if($this->check_current_name_exisits($items['company_name']))
					{
						//number exists
						$comment .= '<br/>Duplicate company name entered';
						$class = 'danger';
					}
					else
					{
						// number does not exisit
						//save product in the db
						if($this->db->insert('company', $items))
						{
							$comment .= '<br/>company successfully added to the database';
							$class = 'success';
						}
						
						else
						{
							$comment .= '<br/>Internal error. Could not add company to the database. Please contact the site administrator';
							$class = 'warning';
						}
					}
				}
				
				else
				{
					$comment .= '<br/>Not saved ensure you have a company number entered';
					$class = 'danger';
				}
				
				
				$response .= '
					
						<tr class="'.$class.'">
							<td>'.$r.'</td>
							<td>'.$items['company_name'].'</td>
							<td>'.$items['company_phone'].'</td>
							<td>'.$items['company_email'].'</td>
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
			$return['response'] = 'company data not found ';
			$return['check'] = FALSE;
		}
		
		return $return;
	}
	
	public function check_current_name_exisits($company_name)
	{
		$this->db->where('company_name', $company_name);
		
		$query = $this->db->get('company');
		
		if($query->num_rows() > 0)
		{
			return TRUE;
		}
		
		else
		{
			return FALSE;
		}
	}
	
	public function add_company()
	{
		$newdata = array(
			   'company_name'				=> $this->input->post('company_name'),
			   'company_physical_address'	=> $this->input->post('company_physical_address'),
			   'company_postal_address'		=> $this->input->post('company_postal_address'),
			   'company_post_code'			=> $this->input->post('company_post_code'),
			   'company_town'				=> $this->input->post('company_town'),
			   'company_phone'				=> $this->input->post('company_phone'),
			   'company_facsimile'			=> $this->input->post('company_facsimile'),
			   'company_cell_phone'			=> $this->input->post('company_cell_phone'),
			   'company_email'				=> $this->input->post('company_email'),
			   'company_activity'			=> $this->input->post('company_activity'),
			   'company_status'				=> $this->input->post('company_status'),
			   'created'     				=> date('Y-m-d H:i:s')
		   );

		if($this->db->insert('company', $newdata))
		{
			return TRUE;
		}
		else
		{
			return FALSE;
		}
	}
	
	public function update_company($company_id)
	{
		$newdata = array(
			   'company_name'				=> $this->input->post('company_name'),
			   'company_physical_address'	=> $this->input->post('company_physical_address'),
			   'company_postal_address'		=> $this->input->post('company_postal_address'),
			   'company_post_code'			=> $this->input->post('company_post_code'),
			   'company_town'				=> $this->input->post('company_town'),
			   'company_phone'				=> $this->input->post('company_phone'),
			   'company_facsimile'			=> $this->input->post('company_facsimile'),
			   'company_cell_phone'			=> $this->input->post('company_cell_phone'),
			   'company_email'				=> $this->input->post('company_email'),
			   'company_activity'			=> $this->input->post('company_activity'),
			   'company_status'				=> $this->input->post('company_status')
		   );
		
		$this->db->where('company_id', $company_id);
		if($this->db->update('company', $newdata))
		{
			return TRUE;
		}
		else
		{
			return FALSE;
		}
	}
}
?>