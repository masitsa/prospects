<?php	
	//get billing details
	if($customer_query->num_rows() > 0)
	{
		$customer = $customer_query->row();
		
		$first_name = $customer->customer_first_name;
		$last_name = $customer->customer_surname;
		$phone = $customer->customer_phone;
		$email = $customer->customer_email;
		$company = $customer->customer_company;
		$surburb_id = $customer->surburb_id;
		$address = $customer->customer_address;
	}
	
	else
	{
		$first_name = '';
		$last_name = '';
		$phone = '';
		$email = '';
		$company = '';
		$surburb_id = '';
		$address = '';
	}
	
	//get shipping details
	if($shipping_query->num_rows() > 0)
	{
		$customer = $shipping_query->row();
		
		$first_name2 = $customer->first_name;
		$last_name2 = $customer->last_name;
		$phone2 = $customer->phone;
		$email2 = $customer->email;
		$company2 = $customer->company;
		$surburb_id2 = $customer->surburb_id;
		$address2 = $customer->address;
	}
	else
	{
		$first_name2 = '';
		$last_name2 = '';
		$phone2 = '';
		$email2 = '';
		$company2 = '';
		$surburb_id2 = '';
		$address2 = '';
	}
	
	if($surburbs_query->num_rows() > 0)
	{
		foreach($surburbs_query->result() as $res)
		{
			$surburb_id3 = $res->surburb_id;
			$surburb_name = $res->surburb_name;
			$post_code = $res->post_code;
			$state_name = $res->state_name;
			
			if($surburb_id2 == $surburb_id3)
			{
				$surburb_id2 = $surburb_name.', '.$post_code.' '.$state_name;
			}
			
			if($surburb_id == $surburb_id3)
			{
				$surburb_id = $surburb_name.', '.$post_code.' '.$state_name;
			}
		}
	}
?>
<div class="container main-container headerOffset">
  <div class="row">
    <div class="breadcrumbDiv col-lg-12">
      <ul class="breadcrumb">
        <li><a href="<?php echo site_url();?>">Home</a> </li>
        <li><a href="<?php echo site_url().'customer/account';?>">My Account</a> </li>
        <li class="active"> My Address </li>
      </ul>
    </div>
  </div><!--/.row-->
  
  
  <div class="row">
  
    <div class="col-lg-12 col-md-12 col-sm-12">
      <h1 class="section-title-inner"><span><i class="fa fa-map-marker"></i> My addresses </span></h1>
      
      <p>Please configure your default billing and delivery addresses when placing an order.<!-- You may also add additional addresses, which can be useful for sending gifts or receiving an order at your office.--></p>
      
      <div class="row userInfo">
      
        <div class="col-lg-12">
          <h2 class="block-title-2"> Your addresses are listed below. </h2>
          <p> Be sure to update your personal information if it has changed.</p>
        </div>
        
        <div class="w100 clearfix">
        
          <div class="col-xs-12 col-sm-6 col-md-4">
            <div class="panel panel-default">
              <div class="panel-heading">
                <h3 class="panel-title"><strong>Billing Address</strong></h3>
              </div>
              <div class="panel-body">
                <ul >
                  <li> <span class="address-name"> <strong><?php echo $first_name;?> <?php echo $last_name;?></strong></span></li>
                  <li> <span class="address-company"> <?php echo $company;?> </span></li>
                  <li> <span class="address-line1"> <?php echo $surburb_id;?></span></li>
                  <li> <span> <strong>Mobile</strong> : <?php echo $phone;?> </span></li>
                  <li> <span> <strong>Email</strong> : <?php echo $email;?> </span></li>
                </ul>
              </div>
              <div class="panel-footer panel-footer-address"> <a href="<?php echo site_url().'account/personnal-information';?>" class="btn btn-sm btn-primary"> <i class="fa fa-edit"> </i> Edit </a> </div>
            </div>
          </div>
          
          <div class="col-xs-12 col-sm-6 col-md-4">
            <div class="panel panel-default">
              <div class="panel-heading">
                <h3 class="panel-title"><strong>Shipping Address</strong></h3>
              </div>
              <div class="panel-body">
                <ul >
                  <li> <span class="address-name"> <strong><?php echo $first_name2;?> <?php echo $last_name2;?></strong></span></li>
                  <li> <span class="address-company"> <?php echo $company2;?> </span></li>
                  <li> <span class="address-line1"> <?php echo $surburb_id2;?></span></li>
                  <li> <span> <strong>Mobile</strong> : <?php echo $phone2;?> </span></li>
                  <li> <span> <strong>Email</strong> : <?php echo $email2;?> </span></li>
                </ul>
              </div>
              <div class="panel-footer panel-footer-address"> <a  href="<?php echo site_url().'account/edit-shipping';?>" class="btn btn-sm btn-primary"> <i class="fa fa-edit"> </i> Edit </a> </div>
            </div>
          </div>
          
        </div><!--/.w100-->
        
        <?php echo $this->load->view('account/navigation', '', TRUE);?>
        
      </div> <!--/row end--> 
    </div>
    
    <div class="col-lg-3 col-md-3 col-sm-5"> </div>
    
  </div> <!--/row-->
  
  <div style="clear:both"></div>
</div> <!-- /.main-container -->
