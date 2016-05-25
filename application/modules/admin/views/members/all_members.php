<?php
		
		$result = '';
		
		//if users exist display them
		if ($query->num_rows() > 0)
		{
			$count = $page;
			
			$result .= 
			
			'
			<div class="row">
    		<div class="col-md-12">
			<section class="panel panel-featured panel-featured-info">
				<header class="panel-heading">
					<h2 class="panel-title pull-left"><?php echo $title;?></h2>
					 <div class="widget-icons pull-right">
					 <a href="import-members" class="btn btn-warning btn-sm fa fa-plus"> Import Members</a>
                      </div>
		    	</header>
		    </section>
			</div>
			</div>
			<table class="table table-bordered table-striped table-condensed">
				<thead>
					<tr>
						<th>#</th>
						<th><a href="'.site_url().'admin/members/company_name/'.$order_method.'/'.$page.'">Company</a></th>
						<th><a href="'.site_url().'admin/members/member_number/'.$order_method.'/'.$page.'">Number</a></th>
						<th><a href="'.site_url().'admin/members/member_first_name/'.$order_method.'/'.$page.'">First name</a></th>
						<th><a href="'.site_url().'admin/members/member_last_name/'.$order_method.'/'.$page.'">Last name</a></th>
						<th><a href="'.site_url().'admin/members/member_phone/'.$order_method.'/'.$page.'">Phone</th>
						<th><a href="'.site_url().'admin/members/member_email/'.$order_method.'/'.$page.'">Email</th>
						
						<th colspan="5">Actions</th>
					</tr>
				</thead>
				  <tbody>
				  
			';
			
			foreach ($query->result() as $row)
			{
				$company_name = $row->company;
				$member_id = $row->member_id;
				$member_first_name = $row->member_first_name;
				$last_name = $row->member_surname;
				$phone = $row->member_phone;
				$email = $row->member_email;
				$member_number = $row->member_number;
				$member_status = $row->member_status;
				$invoices = $this->invoices_model->get_user_invoices($member_id, 5);
				$uploads = $this->uploads_model->get_member_uploads($member_id);
				$identification_result = $display_invoices = '';
				//uploads
				if($uploads->num_rows() > 0)
		       {
		            $count3 = 0;
		                
		            $identification_result = 
		            '
		            <table class="table table-bordered table-striped table-condensed">
		                <thead>
		                    <tr>
		                        <th>#</th>
		                        <th>Document Name</th>
		                        <th>Download Link</th>
		                        <th colspan="3">Actions</th>
		                    </tr>
		                </thead>
		                  <tbody>
		                  
		            ';
		            
		            foreach ($uploads->result() as $row3)
		            {
		                $document_upload_id = $row3->document_upload_id;
		                $document_name = $row3->document_name;
		                $document_upload_name = $row3->document_upload_name;
		                $document_status = $row3->document_status;
		                
		                //create deactivated status display
		                if($document_status == 0)
		                {
		                    $status = '<span class="label label-default">Deactivated</span>';
		                    $button = '<a class="btn btn-info" href="'.site_url().'microfinance/activate-member-identification/'.$document_upload_id.'/'.$member_id.'" onclick="return confirm(\'Do you want to activate?\');" title="Activate "><i class="fa fa-thumbs-up"></i></a>';
		                }
		                //create activated status display
		                else if($document_status == 1)
		                {
		                    $status = '<span class="label label-success">Active</span>';
		                    $button = '<a class="btn btn-default" href="'.site_url().'microfinance/deactivate-member-identification/'.$document_upload_id.'/'.$member_id.'" onclick="return confirm(\'Do you want to deactivate ?\');" title="Deactivate "><i class="fa fa-thumbs-down"></i></a>';
		                }
		                
		                $count3++;
		                $identification_result .= 
		                '
		                    <tr>
		                        <td>'.$count3.'</td>
		                        <td>'.$document_name.'</td>
		                        <td><a href="'.$this->uploads_location.''.$document_upload_name.'" target="_blank" >Download Here</a></td>
		                        <td>'.$status.'</td>
		                        <!--<td>'.$button.'</td>-->
		                        <td><a href="'.site_url().'member/delete_document_scan/'.$document_upload_name.'/'.$document_upload_id.'/'.$member_id.'" class="btn btn-sm btn-danger" onclick="return confirm(\'Do you really want to delete ?\');" title="Delete"><i class="fa fa-trash"></i></a></td>
		                    </tr> 
		                ';
		            }
		            
		            $identification_result .= 
		            '
		                          </tbody>
		                        </table>
		            ';
		        }
		        
		        else
		        {
		            $identification_result = "<p>No documents have been uploaded</p>";
		        }
				
				//invoices
				if($invoices->num_rows() > 0)
				{
					$count2 = 0;
					foreach($invoices->result() as $res)
					{
						$invoice_id = $res->invoice_id;
						$invoice_date = date('jS M Y',strtotime($res->invoice_date));
						$invoice_status_name = $res->invoice_status_name;
						$invoice_status = $res->invoice_status;
						$invoice_number = $res->invoice_number;
						$invoice_items = $this->invoices_model->get_invoice_items($invoice_id);
						$count2++;
						$button2 = $display_invoice_items = '';
						if($invoice_status == 0)
						{
							$button2 = '<span class="label label-danger">'.$invoice_status_name.'</span>';
						}
						else
						{
							$button2 = '<span class="label label-success">'.$invoice_status_name.'</span>';
						}
						
						if($invoice_items->num_rows() > 0)
						{
							$counter = 0;
							$total = 0;
							foreach($invoice_items->result() as $row2)
							{
								$invoice_item_amount = $row2->invoice_item_amount;
								$invoice_item_description = $row2->invoice_item_description;
								$units = 1;
								$counter++;
								$total+=$invoice_item_amount;
								$display_invoice_items .= '
								<tr>
									<td>'.$counter.'</td>
									<td>'.$invoice_item_description.'</td>
									<td>'.number_format($invoice_item_amount).'</td>
									<td>'.$units.'</td>
									<td>'.number_format($invoice_item_amount).'</td>
								</tr>
								';
							}
							$display_invoice_items .= '
							<tr>
								<th colspan="4">Total</th>
								<td>'.number_format($total).'</td>
							</tr>
							';
						}
					$display_invoices .= '
					<tr>
						<td>'.$count2.'</td>
						<td>'.$invoice_number.'</td>
						<td>'.$invoice_date.'</td>
						<td>'.$button2.'</td>
						<td>
							<a href="'.site_url().'view-invoice/'.$invoice_id.'" class="btn btn-warning" target="_blank">View invoice</a>
						</td>
						<td>
							<a href="'.site_url().'admin/members/download_invoice/'.$invoice_id.'" class="btn btn-danger btn-sm" target="_blank">Print</a>
						</td>
					</tr>
				';
				}
			}
				
				//create deactivated status display
				if($member_status == 0)
				{
					$status = '<span class="label label-default">Deactivated</span>';
					$button = '<a class="btn btn-info" href="'.site_url().'admin/activate-member/'.$member_id.'" onclick="return confirm(\'Do you want to activate '.$member_first_name.'?\');" title="Activate '.$member_first_name.'"><i class="fa fa-thumbs-up"></i></a>';
				}
				//create activated status display
				else if($member_status == 1)
				{
					$status = '<span class="label label-success">Active</span>';
					$button = '<a class="btn btn-default" href="'.site_url().'admin/deactivate-member/'.$member_id.'" onclick="return confirm(\'Do you want to deactivate '.$member_first_name.'?\');" title="Deactivate '.$member_first_name.'"><i class="fa fa-thumbs-down"></i></a>';
				}
				
				$count++;
				$result .= 
				'
					<tr>
						<td>'.$count.'</td>
						<td>'.$company_name.'</td>
						<td>'.$member_number.'</td>
						<td>'.$member_first_name.'</td>
						<td>'.$last_name.'</td>
						<td>'.$phone.'</td>
						<td>'.$email.'</td>
						<td>'.$status.'</td>
						<td>
							<a title="View '.$member_first_name.'" class="btn btn-sm btn-primary" href="#" data-toggle="modal" data-target="#view_member'.$member_id.'"><i class="fa fa-plus"></i></a>
							<!-- Modal -->
							<div class="modal fade" id="view_member'.$member_id.'" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
								<div class="modal-dialog" role="document">
									<div class="modal-content">
										<div class="modal-header">
											<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
											<h4 class="modal-title" id="myModalLabel">'.$member_first_name.' '.$last_name.'</h4>
										</div>
										<div class="modal-body">
											<div class="col-md-12">
												<div class="btn-pref btn-group btn-group-justified btn-group-lg" role="group" aria-label="...">
													<div class="btn-group" role="group">
														<button type="button" id="stars" class="btn btn-primary" href="#about'.$member_id.'" data-toggle="tab">
															<span class="glyphicon glyphicon-star" aria-hidden="true"></span>
															<div class="hidden-xs">About</div>
														</button>
													</div>
													<div class="btn-group" role="group">
														<button type="button" id="favorites" class="btn btn-default" href="#company'.$member_id.'" data-toggle="tab">
															<span class="glyphicon glyphicon-heart" aria-hidden="true"></span>
															<div class="hidden-xs">Company</div>
														</button>
													</div>
													<div class="btn-group" role="group">
														<button type="button" id="following" class="btn btn-default" href="#uploads'.$member_id.'" data-toggle="tab">
															<span class="glyphicon glyphicon-user" aria-hidden="true"></span>
															<div class="hidden-xs">Uploads</div>
														</button>
													</div>
													<div class="btn-group" role="group">
														<button type="button" id="following" class="btn btn-default" href="#invoices'.$member_id.'" data-toggle="tab">
															<span class="glyphicon glyphicon-user" aria-hidden="true"></span>
															<div class="hidden-xs">Invoices</div>
														</button>
													</div>
												</div>
							
												<div class="well">
													<div class="tab-content">
														<div class="tab-pane fade in active" id="about'.$member_id.'">
															<h3>About</h3>
															
															<div class="row">
																<div class="col-md-12">
																	<table class="table table-striped table-bordered table-hover">
																		<tr>
																			<th>Title</th>
																			<td>'.$row->member_title.'</td>
																		</tr>
																		<tr>
																			<th>First name</th>
																			<td>'.$row->member_first_name.'</td>
																		</tr>
																		<tr>
																			<th>Last name</th>
																			<td>'.$row->member_surname.'</td>
																		</tr>
																		<tr>
																			<th>Email</th>
																			<td>'.$row->member_email.'</td>
																		</tr>
																		<tr>
																			<th>Phone</th>
																			<td>'.$row->member_phone.'</td>
																		</tr>
																		<tr>
																			<th>Postal address</th>
																			<td>'.$row->member_postal_address.'</td>
																		</tr>
																		<tr>
																			<th>Postal code</th>
																			<td>'.$row->member_postal_code.'</td>
																		</tr>
																		<tr>
																			<th>Last login</th>
																			<td>'.$row->last_login.'</td>
																		</tr>
																		<tr>
																			<th>Date of Birth</th>
																			<td>'.$row->date_of_birth.'</td>
																		</tr>
																		<tr>
																			<th>Nationality</th>
																			<td>'.$row->nationality.'</td>
																		</tr>
																		<tr>
																			<th>Qualifications</th>
																			<td>'.$row->qualifications.'</td>
																		</tr>
																	</table>
																</div>
															</div>
														</div>
														
														<div class="tab-pane fade in" id="company'.$member_id.'">
															<h3>Company</h3>
															
															<div class="row">
																<div class="col-md-12">
																	<table class="table table-striped table-bordered table-hover">
																		<tr>
																			<th>Company</th>
																			<td>'.$row->company.'</td>
																		</tr>
																		<tr>
																			<th>Designation</th>
																			<td>'.$row->designation.'</td>
																		</tr>
																		<tr>
																			<th>Physical address</th>
																			<td>'.$row->company_physical_address.'</td>
																		</tr>
																		<tr>
																			<th>Postal address</th>
																			<td>'.$row->company_postal_address.'</td>
																		</tr>
																		<tr>
																			<th>Postal code</th>
																			<td>'.$row->company_post_code.'</td>
																		</tr>
																		<tr>
																			<th>Town</th>
																			<td>'.$row->company_town.'</td>
																		</tr>
																		<tr>
																			<th>Email</th>
																			<td>'.$row->company_email.'</td>
																		</tr>
																		<tr>
																			<th>Phone</th>
																			<td>'.$row->company_phone.'</td>
																		</tr>
																		<tr>
																			<th>Cell Phone</th>
																			<td>'.$row->company_cell_phone.'</td>
																		</tr>
																		<tr>
																			<th>Facsimile</th>
																			<td>'.$row->company_facsimile.'</td>
																		</tr>
																		<tr>
																			<th>Activity</th>
																			<td>'.$row->company_activity.'</td>
																		</tr>
																	
																	</table>
																</div>
															</div>
														</div>
														
														<div class="tab-pane fade in" id="uploads'.$member_id.'">
															<h3>Uploads</h3>
															'.$identification_result.'
														</div>
														
														<div class="tab-pane fade in" id="invoices'.$member_id.'">
															<h3>Invoices</h3>
															<table class="table table-striped table-hover table-condensed">
																'.$display_invoices.'
															</table>
														</div>
													</div>
												</div>
												
											</div>	
					
										</div>
										<div class="modal-footer">
											<button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
										</div>
									</div>
								</div>
							</div>
						</td>
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
			$result .= "There are no members";
		}
?>

<div class="panel panel-default">
    <div class="panel-heading">Search members</div>
    <div class="panel-body">
        
        <?php echo form_open("admin/members/search_members/", array("class" => "form-horizontal"));?>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="col-md-4 control-label">Company: </label>
                        
                        <div class="col-md-8">
                        	<input type="hidden" name="company_name" id="company_name" value="" />
                            <select name="company_id" id="company_id" class="form-control" onchange="document.getElementById('company_name').value=this.options[this.selectedIndex].text">
                                <option value="">----Select a company----</option>
                                <?php
                                                        
                                    if($companies->num_rows() > 0){

                                        foreach($companies->result() as $row):
                                            $company_name = $row->company_name;
                                            $company_id = $row->company_id;

                                            
                                            if($company_id == set_value('company_id'))
                                            {
                                                echo "<option value='".$company_id."' selected='selected'>".$company_name."</option>";
                                            }
                                            
                                            else
                                            {
                                                echo "<option value='".$company_id."'>".$company_name."</option>";
                                            }
                                        endforeach;
                                    }
                                ?>
                            </select>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label class="col-md-4 control-label">Member number: </label>
                        
                        <div class="col-md-8">
                            <input type="text" class="form-control" name="member_number" placeholder="member number">
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label class="col-md-4 control-label">Phone No.: </label>
                        
                        <div class="col-md-8">
                            <input type="text" class="form-control" name="member_phone" placeholder="Phone No.">
                        </div>
                    </div>
                </div>
                
                <div class="col-md-6">
                    
                    <div class="form-group">
                        <label class="col-md-4 control-label">First name: </label>
                        
                        <div class="col-md-8">
                            <input type="text" class="form-control" name="member_first_name" placeholder="First name">
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label class="col-md-4 control-label">Last Name: </label>
                        
                        <div class="col-md-8">
                            <input type="text" class="form-control" name="member_surname" placeholder="Other Names">
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <div class="col-md-8 col-md-offset-4">
                            <div class="center-align">
                                <button type="submit" class="btn btn-info">Search</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
		<?php 
		echo form_close();
        	
		$search = $this->session->userdata('member_search');
		
		if(!empty($search))
		{
			echo '
			<a href="'.site_url().'admin/members/close_member_search" class="btn btn-warning btn-sm ">Close Search</a>
			';
		}
		?>
    </div>
    
</div>

<div class="panel panel-default">
    <div class="panel-heading"><?php echo $title;?></div>
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
    
    <div class="panel-footer">
        <?php if(isset($links)){echo $links;}?>
    </div>
    
</div>