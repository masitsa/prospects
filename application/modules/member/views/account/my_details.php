<?php
	if($customer_query->num_rows() > 0)
	{
		$customer = $customer_query->row();
		$first_name_error = '';
		$last_name_error = '';
		$phone_error = '';
		$email_error = '';
		$neighbourhood_id_error = '';
		$current_password_error = '';
		$new_password_error = '';
		$confirm_password_error = '';
		$child_error = '';
		$parent_error = '';
		$password = $customer->customer_password;
		
		$validation_errors = validation_errors();
		
		//repopulate form data if validation errors are present
		if(!empty($validation_errors))
		{
			//get errors
			$first_name_error = form_error('customer_first_name');
			$last_name_error = form_error('customer_surname');
			$phone_error = form_error('customer_phone');
			$email_error = form_error('customer_email');
			$neighbourhood_id_error = form_error('neighbourhood_id');
			$current_password_error = form_error('current_password');
			$new_password_error = form_error('new_password');
			$confirm_password_error = form_error('confirm_password');
			$child_error = form_error('child');
			$parent_error = form_error('parent');
			
			//repopulate prefilled values
			$first_name = set_value('customer_first_name');
			$last_name = set_value('customer_surname');
			$phone = set_value('customer_phone');
			$email = set_value('customer_email');
			$current_password = set_value('current_password');
			$new_password = set_value('new_password');
			$confirm_password = set_value('confirm_password');
			$neighbourhood_id = set_value('neighbourhood_id');
			$child = set_value('child');
			$parent = set_value('parent');
		}
		
		else
		{
			$first_name = $customer->customer_first_name;
			$last_name = $customer->customer_surname;
			$phone = $customer->customer_phone;
			$email = $customer->customer_email;
			$neighbourhood_id = $customer->neighbourhood_id;
			$current_password = "";
			$new_password = "";
			$confirm_password = "";
			$parent = $child = $neighbourhood_id;
		}
	}
	
	$children_array[''] = '--All neighbourhoods--';
	//case of an input error
	if(!empty($child_error))
	{
		$js = 'class="form-control alert-danger"';
	}
	
	else
	{
		$js = 'class="form-control"';
	}
	
	if($neighbourhood_children->num_rows() > 0)
	{
		$parents_array = array();
		$children_array[''] = '--All locations--';
		
		foreach($neighbourhood_children->result() as $res)
		{
			$neighbourhood_id = $res->neighbourhood_id;
			$neighbourhood_parent = $res->neighbourhood_parent;
			$neighbourhood_name = $res->neighbourhood_name;
			
			if($neighbourhood_id == $child)
			{
				$parent = $neighbourhood_parent;
			}
			
			if($neighbourhood_parent == $parent)
			{
				$children_array[$neighbourhood_id] = $neighbourhood_name;
			}
		}
	}
	
	//case of an input error
	if(!empty($parent_error))
	{
		$js = 'id="filter_neighbourhood2" class="form-control alert-danger"';
	}
	
	else
	{
		$js = 'id="filter_neighbourhood2" class="form-control"';
	}
	
	if($neighbourhood_parents->num_rows() > 0)
	{
		$parents_array = array();
		$parents_array[''] = '--You\'re from--';
		
		foreach($neighbourhood_parents->result() as $res)
		{
			$neighbourhood_id = $res->neighbourhood_id;
			$neighbourhood_name = $res->neighbourhood_name;
			$parents_array[$neighbourhood_id] = $neighbourhood_name;
		}
	}
?>

			<div role="main" class="main shop">

				<section class="page-header">
					<div class="container">
						<div class="row">
							<div class="col-md-12">
								<ul class="breadcrumb">
									<?php echo $this->site_model->get_breadcrumbs();?>
								</ul>
							</div>
						</div>
						<div class="row">
							<div class="col-md-12">
								<h1><?php echo $title; ?></h1>
							</div>
						</div>
					</div>
				</section>
                
                <div class="container">
                    <div class="heading heading-border heading-bottom-border">
                        <div class="row">
                            <div class="col-sm-10">
                                <h4><i class="fa fa-user"></i> About me</h4>
                            </div>
                            <div class="col-sm-2">
                                <a href="<?php echo site_url().'customer/sign-out';?>" class="pull-right"><i class="fa fa-sign-out"></i> Sign out</a>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-12">
                        	
						 <?php
                            $error_message = $this->session->userdata('error_message');
                            if(!empty($error_message))
                            {
                                echo '<div class="alert alert-danger center-align"> Oh snap! '.$error_message.' </div>';
                                $this->session->unset_userdata('error_message');
                            }
                            
                            $success_message = $this->session->userdata('success_message');
                            if(!empty($success_message))
                            {
                                echo '<div class="alert alert-success center-align"> '.$success_message.' </div>';
                                $this->session->unset_userdata('success_message');
                                
                            }
                        ?>
                        </div>
                        		
                        <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
        
                            <?php echo form_open('customer-account/update-customer-details', array('class' => 'form-horizontal', 'role' => 'form'));?>
                                <div class="form-group">
                                    <label for="customer_first_name" class="col-sm-4 control-label">First name <span class="required">*</span></label>
                                    <div class="col-sm-8">
                                        <?php
                                            //case of an input error
                                            if(!empty($first_name_error))
                                            {
                                                ?>
                                                <input type="text" class="form-control alert-danger" name="customer_first_name" placeholder="<?php echo $first_name_error;?>" onFocus="this.value = '<?php echo $first_name;?>';">
                                                <?php
                                            }
                                            
                                            else
                                            {
                                                ?>
                                                <input type="text" class="form-control" name="customer_first_name" placeholder="First name" value="<?php echo $first_name;?>">
                                                <?php
                                            }
                                        ?>
                                    </div>
                                </div>
                                
                                <div class="form-group">
                                    <label for="customer_surname" class="col-sm-4 control-label">Last name <span class="required">*</span></label>
                                    <div class="col-sm-8">
                                        <?php
                                            //case of an input error
                                            if(!empty($last_name_error))
                                            {
                                                ?>
                                                <input type="text" class="form-control alert-danger" name="customer_surname" placeholder="<?php echo $last_name_error;?>" onFocus="this.value = '<?php echo $last_name;?>';">
                                                <?php
                                            }
                                            
                                            else
                                            {
                                                ?>
                                                <input type="text" class="form-control" name="customer_surname" placeholder="Last name" value="<?php echo $last_name;?>">
                                                <?php
                                            }
                                        ?>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="customer_email" class="col-sm-4 control-label">Email</label>
                                    <div class="col-sm-8">
                                       <input type="text" class="form-control" name="customer_email" readonly placeholder="Email" value="<?php echo $email;?>">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="customer_phone" class="col-sm-4 control-label">Phone <span class="required">*</span></label>
                                    <div class="col-sm-8">
                                        <?php
                                            //case of an input error
                                            if(!empty($phone_error))
                                            {
                                                ?>
                                                <input type="text" class="form-control alert-danger" name="customer_phone" placeholder="<?php echo $phone_error;?>" onFocus="this.value = '<?php echo $phone;?>';">
                                                <?php
                                            }
                                            
                                            else
                                            {
                                                ?>
                                                <input type="text" class="form-control" name="customer_phone" placeholder="Phone" value="<?php echo $phone;?>">
                                                <?php
                                            }
                                        ?>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="parent" class="col-sm-4 control-label">Location <span class="required">*</span></label>
                                    <div class="col-sm-8">
                                        <?php
											echo form_dropdown('parent', $parents_array, $parent, $js);
										?>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="neighbourhood_id" class="col-sm-4 control-label">Neighbourhood <span class="required">*</span></label>
                                    <div class="col-sm-8">
                                        <?php
											echo '<div id="children_section2">';
											echo form_dropdown('child', $children_array, $child, $js);
											echo '</div>';
										?>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-8 col-md-offset-4">
                                        <div class="center-align">
                                        <button type="submit" class="btn   btn-primary"><i class="fa fa-save"></i> &nbsp; Update Account </button>
                                        </div>
                                    </div>
                                </div>
                             <?php echo form_close();?>
                        </div>
                        
                        <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                            <form action="<?php echo site_url().'customer-account/update-password';?>" method="POST" role="form" class="form-horizontal">
                            	<input type="hidden" name="slug" value="<?php echo $password;?>" />
                                <div class="form-group">
                                    <label for="current_password" class="col-sm-4 control-label">Current password <span class="required">*</span></label>
                                    <div class="col-sm-8">
                                        <?php
                                            //case of an input error
                                            if(!empty($current_password_error))
                                            {
                                                ?>
                                                <input type="password" class="form-control alert-danger" name="current_password" placeholder="<?php echo $current_password_error;?>" onFocus="this.value = '<?php echo $current_password;?>';">
                                                <?php
                                            }
                                            
                                            else
                                            {
                                                ?>
                                                <input type="password" class="form-control" name="current_password" placeholder="Current password" value="<?php echo $current_password;?>">
                                                <?php
                                            }
                                        ?>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="new_password" class="col-sm-4 control-label">New password <span class="required">*</span></label>
                                    <div class="col-sm-8">
                                        <?php
                                            //case of an input error
                                            if(!empty($new_password_error))
                                            {
                                                ?>
                                                <input type="password" class="form-control alert-danger" name="new_password" placeholder="<?php echo $new_password_error;?>" onFocus="this.value = '<?php echo $new_password;?>';">
                                                <?php
                                            }
                                            
                                            else
                                            {
                                                ?>
                                                <input type="password" class="form-control" name="new_password" placeholder="New password" value="<?php echo $new_password;?>">
                                                <?php
                                            }
                                        ?>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="confirm_password" class="col-sm-4 control-label">Confirm password <span class="required">*</span></label>
                                    <div class="col-sm-8">
                                        <?php
                                            //case of an input error
                                            if(!empty($confirm_password_error))
                                            {
                                                ?>
                                                <input type="password" class="form-control alert-danger" name="confirm_password" placeholder="<?php echo $confirm_password_error;?>" onFocus="this.value = '<?php echo $confirm_password;?>';">
                                                <?php
                                            }
                                            
                                            else
                                            {
                                                ?>
                                                <input type="password" class="form-control" name="confirm_password" placeholder="Confirm password" value="<?php echo $confirm_password;?>">
                                                <?php
                                            }
                                        ?>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-8 col-md-offset-4">
                                        <div class="center-align">
                                        <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> &nbsp; Update Password </button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
			
	        
	        <?php echo $this->load->view('account/navigation', '', TRUE);?>
	        
	      </div>
	      <!--/row end--> 
	      
	    </div>
    </div>

<script type="text/javascript">

$(document).on("change","select#filter_neighbourhood2",function()
{
	var category_parent = $(this).val();
	
	$.ajax({
		type:'POST',
		url: '<?php echo site_url();?>customer/auth/get_neighbourhood_children2/'+category_parent,
		cache:false,
		contentType: false,
		processData: false,
		dataType: 'json',
		success:function(data)
		{	
			$("#children_section2").html(data);
		},
		error: function(xhr, status, error) 
		{
			alert("XMLHttpRequest=" + xhr.responseText + "\ntextStatus=" + status + "\nerrorThrown=" + error);
		}
	});
	
	return false;
});
</script>