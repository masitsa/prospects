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
	
	$minimal_order_charge = 0;
	if($order_total < 1000)
	{
		$minimal_order_charge = 1000 - $order_total;
		$order_total = 1000;
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
								<h1><?php echo $title;?></h1>
							</div>
						</div>
					</div>
				</section>

				<div class="container">

					<div class="row">
                        
                        <div class="col-md-12">
                        	<div class="heading heading-border heading-bottom-border">
                            	<h4>Pay using Pesapal</h4>
                            </div>
                        </div>
                        
						<div class="col-md-12">
                            
                            <?php
							$error = $this->session->userdata('error_message');
							$success = $this->session->userdata('success_message');
							
							if(!empty($error))
							{
								?>
								<div class="alert alert-danger">
									<p>
										<?php 
										echo $error;
										$this->session->unset_userdata('error_message');
										?>
									</p>
								</div>
								<?php
							}
							
							if(!empty($success))
							{
								?>
								<div class="alert alert-success">
									<p>
										<?php 
										echo $success;
										$this->session->unset_userdata('success_message');
										?>
									</p>
								</div>
								<?php
							}
						  ?>
						</div>
					</div>

					<div class="row">
						<div class="col-sm-9">
							<iframe src="<?php echo $iframe;?>" width="100%" height="700px"  scrolling="no" frameBorder="0">
                                <p>Browser unable to load Pesapal</p>
                            </iframe>

						</div>
						<div class="col-sm-3">
							
							<div class="actions-continue">
								<a href="<?php echo site_url().'hire-dobi/'.$dobi_id;?>" class="btn btn-sm btn-primary pull-left"><i class="fa fa-long-arrow-left"></i> Select Dobi</a>
							</div>
							<h4 class="heading-primary">Basket totals</h4>
							<table class="cart-totals">
                                <thead>
                                    <tr>
                                        <th></th>
                                        <th>Kes</th>
                                    </tr>
                                </thead>
								<tbody>
									<tr class="cart-subtotal">
										<th>
											<strong>Basket Subtotal</strong>
										</th>
										<td>
											<strong><span class="amount"><?php echo number_format($basket_total, 0);?></span></strong>
										</td>
									</tr>
									<tr class="shipping">
										<th>
											Options Subtotal
										</th>
										<td>
											<strong><span class="amount"><?php echo number_format($options_total, 0);?></span></strong>
										</td>
									</tr>
                                    <?php
									if($minimal_order_charge > 0)
									{
										?>
                                        <tr class="shipping">
                                            <th>
                                                Minimal order charge
                                            </th>
                                            <td>
                                                <strong><span class="amount"><?php echo number_format(($minimal_order_charge), 0);?></span></strong>
                                            </td>
                                        </tr>
										<?php
									}
									?>
									<tr class="total">
										<th>
											<strong>Order Total</strong>
										</th>
										<td>
											<strong><span class="amount"><?php echo number_format($order_total, 0);?></span></strong>
										</td>
									</tr>
								</tbody>
							</table>
						</div>
					</div>

				</div>

			</div>