<?php
	//communication messages
	$success_message = $this->session->userdata('shipping_success_message');
	$error_message = $this->session->userdata('shipping_error_message');
	$first_name = '';
	$last_name = '';
	$phone = '';
	$email = '';
	$company = '';
	$surburb_id = '';
	$address = '';
	
	if($shipping_query->num_rows() > 0)
	{
		$customer = $shipping_query->row();
		$first_name_error = '';
		$last_name_error = '';
		$phone_error = '';
		$email_error = '';
		$company_error = '';
		$address_error = '';
		$surburb_id_error = '';
		$company_error = '';
		
		$validation_errors = validation_errors();
		
		//repopulate form data if validation errors are present
		if(!empty($validation_errors))
		{
			//get errors
			$first_name_error = form_error('first_name');
			$last_name_error = form_error('last_name');
			$phone_error = form_error('phone');
			$email_error = form_error('email');
			$company_error = form_error('company');
			$address_error = form_error('address');
			$surburb_id_error = form_error('surburb_id');
			$company_error = form_error('company');
			
			//repopulate prefilled values
			$first_name = set_value('first_name');
			$last_name = set_value('last_name');
			$phone = set_value('phone');
			$email = set_value('email');
			$company = set_value('company');
			$address = set_value('address');
			$surburb_id = set_value('surburb_id');
			$company = set_value('company');
		}
		
		else
		{
			$first_name = $customer->first_name;
			$last_name = $customer->last_name;
			$phone = $customer->phone;
			$email = $customer->email;
			$company = $customer->company;
			$address = $customer->address;
			$surburb_id = $customer->surburb_id;
			$company = $customer->company;
			$current_password = "";
			$new_password = "";
			$confirm_password = "";
		}
	}
?>
<div class="container main-container headerOffset">
  <div class="row">
    <div class="breadcrumbDiv col-lg-12">
      <ul class="breadcrumb">
        <li><a href="<?php echo site_url();?>">Home</a> </li>
        <li><a href="<?php echo site_url().'customer/account';?>">My Account</a> </li>
        <li class="active"> Shipping Information </li>
      </ul>
    </div>
  </div><!--/.row-->

	<?php
		if(!empty($success_message))
		{
			echo '<div class="alert alert-success">'.$success_message.'</div>';
			$this->session->unset_userdata('shipping_success_message');
		}
		if(!empty($error_message))
		{
			echo '<div class="alert alert-danger">'.$error_message.'</div>';
			$this->session->unset_userdata('shipping_error_message');
		}
	?>

    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12">
            <h1 class="section-title-inner"><span><i class="glyphicon glyphicon-user"></i> My shipping information </span></h1>
            <div class="row userInfo">
				<div class="col-lg-12">
					<h2 class="block-title-2"> Please be sure to update your shipping information if it has changed. </h2>
					<p class="required"><span class="required">*</span> Required field</p>
				</div>
				
                <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6 col-md-offset-3">
                	<?php echo form_open('account/update-shipping-details', array('class' => 'form-horizontal', 'role' => 'form'));?>
                        <div class="form-group">
                            <label for="first_name" class="col-sm-4 control-label">First name <span class="required">*</span></label>
                            <div class="col-sm-8">
                                <?php
                                    //case of an input error
                                    if(!empty($first_name_error))
                                    {
                                        ?>
                                        <input type="text" class="form-control alert-danger" name="first_name" placeholder="<?php echo $first_name_error;?>" onFocus="this.value = '<?php echo $first_name;?>';">
                                        <?php
                                    }
                                    
                                    else
                                    {
                                        ?>
                                        <input type="text" class="form-control" name="first_name" placeholder="First name" value="<?php echo $first_name;?>">
                                        <?php
                                    }
                                ?>
                            </div>
                        </div>
						
                        <div class="form-group">
                            <label for="last_name" class="col-sm-4 control-label">Last name <span class="required">*</span></label>
                            <div class="col-sm-8">
                                <?php
                                    //case of an input error
                                    if(!empty($last_name_error))
                                    {
                                        ?>
                                        <input type="text" class="form-control alert-danger" name="last_name" placeholder="<?php echo $last_name_error;?>" onFocus="this.value = '<?php echo $last_name;?>';">
                                        <?php
                                    }
                                    
                                    else
                                    {
                                        ?>
                                        <input type="text" class="form-control" name="last_name" placeholder="Last name" value="<?php echo $last_name;?>">
                                        <?php
                                    }
                                ?>
                            </div>
                        </div>
						
                        <div class="form-group">
                            <label for="company" class="col-sm-4 control-label">Company</label>
                            <div class="col-sm-8">
                                <?php
                                    //case of an input error
                                    if(!empty($company_error))
                                    {
                                        ?>
                                        <input type="text" class="form-control alert-danger" name="company" placeholder="<?php echo $company_error;?>" onFocus="this.value = '<?php echo $company;?>';">
                                        <?php
                                    }
                                    
                                    else
                                    {
                                        ?>
                                        <input type="text" class="form-control" name="company" placeholder="Company" value="<?php echo $company;?>">
                                        <?php
                                    }
                                ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="email" class="col-sm-4 control-label">Email</label>
                            <div class="col-sm-8">
                               <input type="text" class="form-control" name="email" readonly placeholder="Email" value="<?php echo $email;?>">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="phone" class="col-sm-4 control-label">Phone <span class="required">*</span></label>
                            <div class="col-sm-8">
                                <?php
                                    //case of an input error
                                    if(!empty($phone_error))
                                    {
                                        ?>
                                        <input type="text" class="form-control alert-danger" name="phone" placeholder="<?php echo $phone_error;?>" onFocus="this.value = '<?php echo $phone;?>';">
                                        <?php
                                    }
                                    
                                    else
                                    {
                                        ?>
                                        <input type="text" class="form-control" name="phone" placeholder="Phone" value="<?php echo $phone;?>">
                                        <?php
                                    }
                                ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="address" class="col-sm-4 control-label">Address <span class="required">*</span></label>
                            <div class="col-sm-8">
                                <?php
                                    //case of an input error
                                    if(!empty($address_error))
                                    {
                                        ?>
                                        <input type="text" class="form-control alert-danger" name="address" placeholder="<?php echo $address_error;?>" onFocus="this.value = '<?php echo $address;?>';">
                                        <?php
                                    }
                                    
                                    else
                                    {
                                        ?>
                                        <input type="text" class="form-control" name="address" placeholder="Address" value="<?php echo $address;?>">
                                        <?php
                                    }
                                ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="surburb_id" class="col-sm-4 control-label">Surburb <span class="required">*</span></label>
                            <div class="col-sm-8">
                                <?php
                                    //case of an input error
                                    if(!empty($surburb_id_error))
                                    {
                                        ?>
                                        <select name="surburb_id" class="form-control alert-danger">
                                        <?php
                                    }
                                    
                                    else
                                    {
                                        ?>
                                        <select name="surburb_id" class="form-control">
                                        <?php
                                    }
                                    if($surburbs_query->num_rows() > 0)
                                    {
                                        foreach($surburbs_query->result() as $res)
                                        {
                                            $surburb_id2 = $res->surburb_id;
                                            $surburb_name = $res->surburb_name;
                                            $post_code = $res->post_code;
                                            $state_name = $res->state_name;
                                            
                                            if($surburb_id2 == $surburb_id)
                                            {
                                                echo '<option value="'.$surburb_id2.'" selected="selected">'.$surburb_name.', '.$post_code.' '.$state_name.'</option>';
                                            }
                                            
                                            else
                                            {
                                                echo '<option value="'.$surburb_id2.'">'.$surburb_name.', '.$post_code.' '.$state_name.'</option>';
                                            }
                                        }
                                    }
                                ?>
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-8 col-md-offset-4">
                            	<div class="center-align">
                                <button type="submit" class="btn   btn-primary"><i class="fa fa-save"></i> &nbsp; Update shipping details </button>
                                </div>
                            </div>
                        </div>
					</form>
				</div>
                
			</div>
			<div class="col-lg-12 clearfix">
				<ul class="pager">
				<li class="previous pull-right"><a href="<?php echo site_url().'products/all-products';?>"> <i class="fa fa-home"></i> Go to Shop </a></li>
				<li class="next pull-left"><a href="<?php echo site_url().'account';?>"> &larr; Back to My Account</a></li>
				</ul>
			</div>
		</div>
		<!--/row end--> 
    </div>
    <!--/row-->
    
    <div style="clear:both"></div>
</div>
<!-- /main-container -->

<div class="gap"> </div>