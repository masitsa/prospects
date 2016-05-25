<?php
		
		$result = '';
		
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
						<th><a href="'.site_url().'admin/plans/plan_name/'.$order_method.'/'.$page.'">Plan name</a></th>
						<th><a href="'.site_url().'admin/plans/plan_amount/'.$order_method.'/'.$page.'">Amount ($)</a></th>
						<th><a href="'.site_url().'admin/plans/stripe_plan/'.$order_method.'/'.$page.'">Stripe ID</a></th>
						<th><a href="'.site_url().'admin/plans/plan_description/'.$order_method.'/'.$page.'">Description</a></th>
						<th><a href="'.site_url().'admin/plans/last_modified/'.$order_method.'/'.$page.'">Last modified</a></th>
						<th><a href="'.site_url().'admin/plans/plan_status/'.$order_method.'/'.$page.'">Status</a></th>
						<th colspan="5">Actions</th>
					</tr>
				</thead>
				  <tbody>
				  
			';
			
			//get all administrators
			$admins = NULL;
			
			foreach ($query->result() as $row)
			{
				$plan_id = $row->plan_id;
				$plan_name = $row->plan_name;
				$plan_amount = $row->plan_amount;
				$plan_status = $row->plan_status;
				$plan_description = $row->plan_description;
				$stripe_plan = $row->stripe_plan;
				$modified_by = $row->modified_by;
				
				//create deactivated status display
				if($plan_status == 0)
				{
					$status = '<span class="label label-important">Deactivated</span>';
					$button = '<a class="btn btn-info" href="'.site_url().'admin/activate-plan/'.$plan_id.'" onclick="return confirm(\'Do you want to activate '.$plan_name.'?\');" title="Activate '.$plan_name.'"><i class="fa fa-thumbs-up"></i></a>';
				}
				//create activated status display
				else if($plan_status == 1)
				{
					$status = '<span class="label label-success">Active</span>';
					$button = '<a class="btn btn-default" href="'.site_url().'admin/deactivate-plan/'.$plan_id.'" onclick="return confirm(\'Do you want to deactivate '.$plan_name.'?\');" title="Deactivate '.$plan_name.'"><i class="fa fa-thumbs-down"></i></a>';
				}
				
				//creators & editors
				if($admins != NULL)
				{
					foreach($admins as $adm)
					{
						$personnel_id = $adm->personnel_id;
						
						if($personnel_id == $created_by)
						{
							$created_by = $adm->personnel_fname;
						}
						
						if($personnel_id == $modified_by)
						{
							$modified_by = $adm->personnel_fname;
						}
					}
				}
				
				else
				{
				}
				$count++;
				$result .= 
				'
					<tr>
						<td>'.$count.'</td>
						<td>'.$plan_name.'</td>
						<td>'.$plan_amount.'</td>
						<td>'.$stripe_plan.'</td>
						<td>'.$plan_description.'</td>
						<td>'.date('jS M Y H:i a',strtotime($row->last_modified)).'</td>
						<td>'.$status.'</td>
						<td><a href="'.site_url().'admin/edit-plan/'.$plan_id.'" class="btn btn-sm btn-success" title="Edit '.$plan_name.'"><i class="fa fa-pencil"></i></a></td>
						<td>'.$button.'</td>
						<td><a href="'.site_url().'admin/delete-plan/'.$plan_id.'" class="btn btn-sm btn-danger" onclick="return confirm(\'Do you really want to delete '.$plan_name.'?\');" title="Delete '.$plan_name.'"><i class="fa fa-trash"></i></a></td>
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
			$result .= "There are no plans";
		}
?>

						<section class="panel">
							<header class="panel-heading">
								<h2 class="panel-title"><?php echo $title;?></h2>
							</header>
							<div class="panel-body">
                            	<?php
                                $success = $this->session->userdata('success_message');
		
								if(!empty($success))
								{
									echo '<div class="alert alert-success"> <strong>Success!</strong> '.$success.' </div>';
									$this->session->unset_userdata('success_message');
								}
								
								$error = $this->session->userdata('error_message');
								
								if(!empty($error))
								{
									echo '<div class="alert alert-danger"> <strong>Oh snap!</strong> '.$error.' </div>';
									$this->session->unset_userdata('error_message');
								}
								?>
                            	<div class="row" style="margin-bottom:20px;">
                                    <div class="col-lg-12">
                                    	<a href="<?php echo site_url();?>admin/add-plan" class="btn btn-success pull-right">Add Plan</a>
                                    </div>
                                </div>
								<div class="table-responsive">
                                	
									<?php echo $result;?>
							
                                </div>
							</div>
							<div class="panel-body">
                            	<?php if(isset($links)){echo $links;}?>
							</div>
						</section>