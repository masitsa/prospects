<?php
		
		$result = '<a href="'.site_url().'admin/add-email" class="btn btn-success pull-right">Add email</a>';
		
		//if users exist display them
		if ($query->num_rows() > 0)
		{
			$count = $page;
			
			$result .= 
			'
			<table class="table table-bordered table-striped table-condensed">
				<thead>
					<tr>
						<th>#</th>
						<th><a href="'.site_url().'admin/emails/email_category_name/'.$order_method.'/'.$page.'">Category</a></th>
						<th><a href="'.site_url().'admin/emails/email_content/'.$order_method.'/'.$page.'">Summary</th>
						<th><a href="'.site_url().'admin/emails/created/'.$order_method.'/'.$page.'">Created</a></th>
						<th><a href="'.site_url().'admin/emails/email_status/'.$order_method.'/'.$page.'">Status</a></th>
						<th colspan="5">Actions</th>
					</tr>
				</thead>
				  <tbody>
				  
			';
			
			foreach ($query->result() as $row)
			{
				$email_id = $row->email_id;
				$email_category_name = $row->email_category_name;
				$email_content = $row->email_content;
				$email_status = $row->email_status;
				$created = $row->created;
				
				//create deactivated status display
				if($email_status == 0)
				{
					$status = '<span class="label label-default">Deactivated</span>';
					$button = '<a class="btn btn-info" href="'.site_url().'admin/activate-email/'.$email_id.'" onclick="return confirm(\'Do you want to activate '.$email_category_name.'?\');" title="Activate '.$email_category_name.'"><i class="fa fa-thumbs-up"></i></a>';
				}
				//create activated status display
				else if($email_status == 1)
				{
					$status = '<span class="label label-success">Active</span>';
					$button = '<a class="btn btn-default" href="'.site_url().'admin/deactivate-email/'.$email_id.'" onclick="return confirm(\'Do you want to deactivate '.$email_category_name.'?\');" title="Deactivate '.$email_category_name.'"><i class="fa fa-thumbs-down"></i></a>';
				}
				
				$count++;
				$result .= 
				'
					<tr>
						<td>'.$count.'</td>
						<td>'.$email_category_name.'</td>
						<td>'.implode(' ', array_slice(explode(' ', $email_content), 0, 10)).'</td>
						<td>'.date('jS M Y H:i a',strtotime($created)).'</td>
						<td>'.$status.'</td>
						<td><a class="btn btn-primary" href="'.site_url().'admin/edit-email/'.$email_id.'" title="Edit '.$email_category_name.'"><i class="fa fa-pencil"></i></a></td>
						<td>'.$button.'</td>
						<td><a class="btn btn-danger" href="'.site_url().'admin/delete-email/'.$email_id.'" onclick="return confirm(\'Do you want to delete '.$email_category_name.'?\');" title="Delete '.$email_category_name.'"><i class="fa fa-trash"></i></a></td>
					</tr> 
				';
			}
			
			$result .= 
			'
						  </tbody>
						</table>
			';
		}
		
		else
		{
			$result .= "There are no emails";
		}
?>

						<section class="panel">
							<header class="panel-heading">
								<div class="panel-actions">
									<a href="#" class="panel-action panel-action-toggle" data-panel-toggle></a>
								</div>
						
								<h2 class="panel-title"><?php echo $title;?></h2>
							</header>
							<div class="panel-body">
								<div class="table-responsive">
                                	
									<?php 
									$success = $this->session->userdata('success_message');
									$error = $this->session->userdata('error_message');
									
									if(!empty($success))
									{
										echo '<div class="alert alert-success">'.$success.'</div>';
										$this->session->unset_userdata('success_message');
									}
									
									if(!empty($error))
									{
										echo '<div class="alert alert-danger">'.$error.'</div>';
										$this->session->unset_userdata('error_message');
									}
									echo $result;
									?>
							
                                </div>
							</div>
                            
							<div class="panel-body">
                            	<?php if(isset($links)){echo $links;}?>
							</div>
                            
						</section>