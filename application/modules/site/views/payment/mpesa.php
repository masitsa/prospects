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
	
	$order_row = $order->row();
	$order_number = $order_row->order_number;
	
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
                            	<h4>Lipa na Mpesa</h4>
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
							<img src="<?php echo base_url().'assets/img/lipa-na-mpesa-buy-goods.png';?>" class="img-responsive">
                            
                            <p>To pay using Mpesa</p>
                            
                            <ol>
                                <li>Go to your Mpesa menu</li>
                                <li>Select <strong>Buy Goods /Services</strong></li>
                                <li>Enter Till Number <strong>564890</strong></li>
                                <li>Enter <strong><?php echo $order_total;?></strong> for amount</li>
                                <li>Enter your transaction code in the box provided below</li>
                                <li>You will be notified by email once your payment has been received</li>
                            </ol>
                            <hr />
                            <h3 style="margin:0 auto; text-align:center; margin-top:10px; margin-bottom:20px;">Enter your MPesa transaction code</h3>
                            <?php echo form_open('payment/record-mpesa-code/'.$order_id);?>
                            	<div class="form-group">
                                    <label class="col-lg-4 control-label">MPesa Transaction Code</label>
                                    <div class="col-lg-6">
                                        <input type="text" class="form-control" name="mpesa_code" placeholder="MPesa Transaction Code" value="<?php echo set_value('category_name');?>" required>
                                    </div>
                                </div>
                                
                                <div class="row">
                                	<div class="col-lg-6 col-lg-offset-4">
                                        <div class="form-actions center-align">
                                            <button class="submit btn btn-primary" type="submit">
                                                Add code
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            <?php echo form_close();?>
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