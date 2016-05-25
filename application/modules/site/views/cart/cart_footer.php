<?php
	if(!isset($basket_total))
	{
		$options = $this->session->userdata('options');
		$selected_iron_cost = 0;
		$selected_fold_cost = 0;
		$selected_delivery_cost = 0;
		
		if(is_array($options))
		{
			if(isset($options['iron_cost']))
			{
				$selected_iron_cost = $options['iron_cost'];
			}
			if(isset($options['fold_cost']))
			{
				$selected_fold_cost = $options['fold_cost'];
			}
			if(isset($options['delivery_cost']))
			{
				$selected_delivery_cost = $options['delivery_cost'];
			}
		}
		$options_total = $selected_iron_cost + $selected_fold_cost + $selected_delivery_cost;
		$basket_total = $this->load->view('site/cart/cart_total', '', TRUE);
		$order_total = $basket_total + $options_total;
	}
?>
<div class="row">
    <div class="col-md-12">
    <h5 class="text-right subtotal" id="cart_sub_total"> 
        Laundry: Kes <?php echo number_format($basket_total, 0);?> <br/>
        Options: Kes <?php echo number_format($options_total, 0);?>  <br/>
        Subtotal: Kes <?php echo number_format($order_total, 0);?>  <br/>
    </h5>
    </div>
    
    <?php
	if($order_total > 0)
	{
	?>
    <div class="col-sm-6">
        <a class="btn btn-sm btn-default" href="<?php echo site_url().'basket';?>"> <i class="fa fa-shopping-cart"> </i> View Basket </a> 
    </div>
    
    <div class="col-sm-6">
        <a class="btn btn-sm btn-primary" href="<?php echo site_url().'select-dobi';?>" style="color:#fff;"> Select Dobi → </a> 
    </div>
    <?php
	}
	?>
</div>