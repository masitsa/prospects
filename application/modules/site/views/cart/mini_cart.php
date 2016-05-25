<?php
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
	
	$totals['options_total'] = $options_total;
	$totals['basket_total'] = $basket_total;
	$totals['order_total'] = $order_total;
?>
<li class="dropdown mega-menu-item mega-menu-shop">
    <a href="<?php echo site_url().'basket';?>" class="dropdown-toggle mobile-redirect" id="">
        <i class="fa fa-shopping-cart"></i> 
        Basket (<span id="menu_cart_total"><?php echo $this->cart_model->total_items_in_cart();?></span>) 
        - Kes <span id="menu_cart_sub_total"><?php echo number_format($order_total, 0);?></span>
    </a>
    <ul class="dropdown-menu">
        <li>
            <div class="mega-menu-content">
                <div class="row">
                    <div class="col-md-12" id="mini_menu_cart">

                        <?php echo $this->cart_model->get_cart();?>

                    </div>
                    
                    <div class="miniCartFooter" id="mini-cart-footer">
						<?php
                            echo $this->load->view('site/cart/cart_footer', $totals, TRUE);
                        ?>
                    </div>
                </div>
            </div>
        </li>
    </ul>
</li>