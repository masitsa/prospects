<?php   if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once "./application/modules/admin/controllers/admin.php";
		
// include autoloader
require_once "./application/libraries/dompdf/autoload.inc.php";
	
// reference the Dompdf namespace
use Dompdf\Dompdf;

class Members extends admin 
{
	var $members_path;
	var $members_location;
	var $uploads_path;
	var $uploads_location;
	var $csv_path;
	
	function __construct()
	{
		parent:: __construct();
		$this->load->model('users_model');
		$this->load->model('companies_model');
		$this->load->model('members_model');
		$this->load->model('member/invoices_model');
		$this->load->model('member/uploads_model');

		$this->uploads_path = realpath(APPPATH . '../assets/uploads');
		$this->uploads_location = base_url().'assets/uploads/';
		$this->csv_path = realpath(APPPATH . '../assets/csv');
	}
    
	/*
	*
	*	Default action is to show all the members
	*
	*/
	public function index($order = 'member_first_name', $order_method = 'ASC') 
	{
		$where = 'member_status = 1';
		$table = 'member';
		
		$member_search = $this->session->userdata('member_search');
		if(!empty($member_search))
		{
			$where .= $member_search;
		}
		
		//pagination
		$segment = 5;
		$this->load->library('pagination');
		$config['base_url'] = site_url().'admin/members/'.$order.'/'.$order_method;
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
		$query = $this->members_model->get_all_members($table, $where, $config["per_page"], $page, $order, $order_method);
		
		//change of order method 
		if($order_method == 'DESC')
		{
			$order_method = 'ASC';
		}
		
		else
		{
			$order_method = 'DESC';
		}
		
		$data['title'] = 'Members';
		$search_title = $this->session->userdata('member_search_title');
			
		if(!empty($search_title))
		{
			$v_data['title'] = 'Members filtered by :'.$search_title;
		}
		
		else
		{
			$v_data['title'] = $data['title'];
		}
		
		$v_data['order'] = $order;
		$v_data['order_method'] = $order_method;
		$v_data['query'] = $query;
		$v_data['companies'] = $this->companies_model->all_companies();
		$v_data['page'] = $page;
		$v_data['uploads_path'] = $this->uploads_path;
		$v_data['uploads_location'] = $this->uploads_location;
		$data['content'] = $this->load->view('members/all_members', $v_data, true);
		
		$this->load->view('templates/general_page', $data);
	}
    
	/*
	*
	*	Add a new member
	*
	*/
	public function add_member() 
	{
		//form validation rules
		$this->form_validation->set_rules('member_name', 'member Name', 'required|xss_clean');
		$this->form_validation->set_rules('member_status', 'member Status', 'required|xss_clean');
		$this->form_validation->set_rules('member_preffix', 'member Preffix', 'required|is_unique[member.member_preffix]|xss_clean');
		$this->form_validation->set_rules('member_parent', 'member Parent', 'required|xss_clean');
		$this->form_validation->set_message("is_unique", "A unique preffix is requred.");
		
		//if form has been submitted
		if ($this->form_validation->run())
		{
			//upload product's gallery images
			$resize['width'] = 600;
			$resize['height'] = 800;
			
			if(is_uploaded_file($_FILES['member_image']['tmp_name']))
			{
				$this->load->library('image_lib');
				
				$members_path = $this->members_path;
				/*
					-----------------------------------------------------------------------------------------
					Upload image
					-----------------------------------------------------------------------------------------
				*/
				$response = $this->file_model->upload_file($members_path, 'member_image', $resize);
				if($response['check'])
				{
					$file_name = $response['file_name'];
					$thumb_name = $response['thumb_name'];
				}
			
				else
				{
					$this->session->set_userdata('error_message', $response['error']);
					
					$data['title'] = 'Add New member';
					$v_data['all_members'] = $this->members_model->all_members();
					$data['content'] = $this->load->view('members/add_member', $v_data, true);
					$this->load->view('templates/general_page', $data);
					break;
				}
			}
			
			else{
				$file_name = '';
				$thumb_name = '';
			}
			
			if($this->members_model->add_member($file_name))
			{
				$this->session->set_userdata('success_message', 'member added successfully');
				redirect('admin/members');
			}
			
			else
			{
				$this->session->set_userdata('error_message', 'Could not add member. Please try again');
			}
		}
		
		//open the add new member
		
		$data['title'] = 'Add member';
		$v_data['title'] = $data['title'];
		$v_data['all_members'] = $this->members_model->all_parent_members();
		$data['content'] = $this->load->view('members/add_member', $v_data, true);
		$this->load->view('templates/general_page', $data);
	}
    
	/*
	*
	*	Edit an existing member
	*	@param int $member_id
	*
	*/
	public function edit_member($member_id) 
	{
		//form validation rules
		$this->form_validation->set_rules('member_name', 'member Name', 'required|xss_clean');
		$this->form_validation->set_rules('member_status', 'member Status', 'required|xss_clean');
		$this->form_validation->set_rules('member_preffix', 'member Preffix', 'required|xss_clean');
		$this->form_validation->set_rules('member_parent', 'member Parent', 'required|xss_clean');
		$this->form_validation->set_message("is_unique", "A unique preffix is requred.");
		
		//if form has been submitted
		if ($this->form_validation->run())
		{
			//upload product's gallery images
			$resize['width'] = 600;
			$resize['height'] = 800;
			
			if(is_uploaded_file($_FILES['member_image']['tmp_name']))
			{
				$members_path = $this->members_path;
				$members_location = $this->members_location;
				
				//delete original image
				$this->file_model->delete_file($members_path."\\".$this->input->post('current_image'), $members_location);
				
				//delete original thumbnail
				$this->file_model->delete_file($members_path."\\thumbnail_".$this->input->post('current_image'), $members_location);
				/*
				/*
					-----------------------------------------------------------------------------------------
					Upload image
					-----------------------------------------------------------------------------------------
				*/
				$response = $this->file_model->upload_file($members_path, 'member_image', $resize);
				if($response['check'])
				{
					$file_name = $response['file_name'];
					$thumb_name = $response['thumb_name'];
				}
			
				else
				{
					$this->session->set_userdata('error_message', $response['error']);
					
					/*$data['title'] = 'Edit member';
					$query = $this->members_model->get_member($member_id);
					if ($query->num_rows() > 0)
					{
						$v_data['member'] = $query->result();
						$v_data['all_members'] = $this->members_model->all_members();
						$data['content'] = $this->load->view('members/edit_member', $v_data, true);
					}
					
					else
					{
						$data['content'] = 'member does not exist';
					}
					
					$this->load->view('templates/general_page', $data);
					break;*/
					$file_name = '';
				}
			}
			
			else{
				$file_name = $this->input->post('current_image');
			}
			//update member
			if($this->members_model->update_member($file_name, $member_id))
			{
				$this->session->set_userdata('success_message', 'member updated successfully');
				redirect('admin/members');
			}
			
			else
			{
				$this->session->set_userdata('error_message', 'Could not update member. Please try again');
			}
		}
		
		//open the add new member
		$data['title'] = 'Edit member';
		$v_data['title'] = $data['title'];
		
		//select the member from the database
		$query = $this->members_model->get_member($member_id);
		
		if ($query->num_rows() > 0)
		{
			$v_data['member'] = $query->result();
			$v_data['all_members'] = $this->members_model->all_parent_members();
			
			$data['content'] = $this->load->view('members/edit_member', $v_data, true);
		}
		
		else
		{
			$data['content'] = 'member does not exist';
		}
		
		$this->load->view('templates/general_page', $data);
	}
    
	/*
	*
	*	Delete an existing member
	*	@param int $member_id
	*
	*/
	public function delete_member($member_id)
	{
		//delete member image
		$query = $this->members_model->get_member($member_id);
		
		if ($query->num_rows() > 0)
		{
			$result = $query->result();
			$image = $result[0]->member_image_name;
			
			$this->load->model('file_model');
			//delete image
			$this->file_model->delete_file($this->members_path."/images/".$image);
			//delete thumbnail
			$this->file_model->delete_file($this->members_path."/thumbs/".$image);
		}
		$this->members_model->delete_member($member_id);
		$this->session->set_userdata('success_message', 'member has been deleted');
		redirect('admin/members');
	}
    
	/*
	*
	*	Activate an existing member
	*	@param int $member_id
	*
	*/
	public function activate_member($member_id)
	{
		$this->members_model->activate_member($member_id);
		$this->session->set_userdata('success_message', 'member activated successfully');
		redirect('admin/members');
	}
    
	/*
	*
	*	Deactivate an existing member
	*	@param int $member_id
	*
	*/
	public function deactivate_member($member_id)
	{
		$this->members_model->deactivate_member($member_id);
		$this->session->set_userdata('success_message', 'member disabled successfully');
		redirect('admin/members');
	}
	
	public function view_invoice($invoice_id)
	{
		$v_data['invoices'] = $this->invoices_model->get_invoice($invoice_id);
		$v_data['invoice_items'] = $this->invoices_model->get_invoice_items($invoice_id);
		$this->load->view('members/view_invoice', $v_data);
	}
    
	/*
	*
	*	import member
	*
	*/
	function import_members()
	{
		$v_data['title'] = $data['title'] = 'Import Members';
		
		$data['content'] = $this->load->view('members/import_members', $v_data, true);
		$this->load->view('admin/templates/general_page', $data);
	}
    
	/*
	*
	*	import member template
	*
	*/
	function import_members_template()
	{
		//export products template in excel 
		$this->members_model->import_members_template();
	}
    
	/*
	*
	*	Do the actual member import
	*
	*/
	function do_members_import()
	{
		if(isset($_FILES['import_csv']))
		{
			if(is_uploaded_file($_FILES['import_csv']['tmp_name']))
			{
				//import products from excel 
				$response = $this->members_model->import_csv_members($this->csv_path);
				
				if($response == FALSE)
				{
					$v_data['import_response_error'] = 'Something went wrong. Please try again.';
				}
				
				else
				{
					if($response['check'])
					{
						$v_data['import_response'] = $response['response'];
					}
					
					else
					{
						$v_data['import_response_error'] = $response['response'];
					}
				}
			}
			
			else
			{
				$v_data['import_response_error'] = 'Please select a file to import.';
			}
		}
		
		else
		{
			$v_data['import_response_error'] = 'Please select a file to import.';
		}
		
		$v_data['title'] = $data['title'] = $this->site_model->display_page_title();
		
		$data['content'] = $this->load->view('members/import_members', $v_data, true);
		$this->load->view('admin/templates/general_page', $data);
	}
	
	public function search_members()
	{
		$company_id = $this->input->post('company_id');
		$member_number = $this->input->post('member_number');
		$member_phone = $this->input->post('member_phone');
		$search_title = '';
		
		if(!empty($member_number))
		{
			$search_title .= ' member number <strong>'.$member_number.'</strong>';
			$member_number = ' AND member.member_number LIKE \'%'.$member_number.'%\'';
		}
		
		if(!empty($company_id))
		{
			$search_title .= ' Company name <strong>'.$this->input->post('company_name').'</strong>';
			$company_id = ' AND member.company_id = \''.$company_id.'\' ';
		}
		
		if(!empty($member_phone))
		{
			$search_title .= ' member phone <strong>'.$member_phone.'</strong>';
			$member_phone = ' AND member.member_phone = \''.$member_phone.'\' ';
		}
		
		//search surname
		if(!empty($_POST['member_first_name']))
		{
			$search_title .= ' first name <strong>'.$_POST['member_first_name'].'</strong>';
			$surnames = explode(" ",$_POST['member_first_name']);
			$total = count($surnames);
			
			$count = 1;
			$surname = ' AND (';
			for($r = 0; $r < $total; $r++)
			{
				if($count == $total)
				{
					$surname .= ' member.member_first_name LIKE \'%'.mysql_real_escape_string($surnames[$r]).'%\'';
				}
				
				else
				{
					$surname .= ' member.member_first_name LIKE \'%'.mysql_real_escape_string($surnames[$r]).'%\' AND ';
				}
				$count++;
			}
			$surname .= ') ';
		}
		
		else
		{
			$surname = '';
		}
		
		//search other_names
		if(!empty($_POST['member_surname']))
		{
			$search_title .= ' surname <strong>'.$_POST['member_surname'].'</strong>';
			$other_names = explode(" ",$_POST['member_surname']);
			$total = count($other_names);
			
			$count = 1;
			$other_name = ' AND (';
			for($r = 0; $r < $total; $r++)
			{
				if($count == $total)
				{
					$other_name .= ' member.member_surname LIKE \'%'.mysql_real_escape_string($other_names[$r]).'%\'';
				}
				
				else
				{
					$other_name .= ' member.member_surname LIKE \'%'.mysql_real_escape_string($other_names[$r]).'%\' AND ';
				}
				$count++;
			}
			$other_name .= ') ';
		}
		
		else
		{
			$other_name = '';
		}
		
		$search = $company_id.$member_number.$surname.$other_name.$member_phone;
		$this->session->set_userdata('member_search', $search);
		$this->session->set_userdata('member_search_title', $search_title);
		
		redirect('admin/members');
	}
	
	public function close_member_search()
	{
		$this->session->unset_userdata('member_search');
		
		redirect('admin/members');
	}
	
	public function download_invoice($invoice_id)
	{
		//$this->load->helper(array('dompdf', 'pdfFilePath'));
		$v_data['invoices'] = $this->invoices_model->get_invoice($invoice_id);
		$v_data['invoice_items'] = $this->invoices_model->get_invoice_items($invoice_id);
		$v_data['contacts'] = $this->site_model->get_contacts();
		
		$html=$this->load->view('members/view_invoice', $v_data, true);
		
		$row = $v_data['invoices']->row();

		$invoice_number = $row->invoice_number;
	
 
        //this the the PDF filename that user will get to download
        $pdfFilePath = 'Invoice '.$invoice_number.".pdf";
		
		// instantiate and use the dompdf class
		$dompdf = new Dompdf();
		$dompdf->loadHtml($html);
		
		// (Optional) Setup the paper size and orientation
		$dompdf->setPaper('A4', 'potrait');
		
		// Render the HTML as PDF
		$dompdf->render();
		
		// Output the generated PDF to Browser
		$dompdf->stream();
	}
}
?>