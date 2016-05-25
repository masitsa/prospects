<?php
	$row = $customer->row();
	$customer_first_name = $row->customer_first_name;
	$customer_surname = $row->customer_surname;
	$customer_email = $row->customer_email;
	$customer_phone = $row->customer_phone;
	$neighbourhood_id = $row->neighbourhood_id;
	
	$this->db->where('neighbourhood_id', $neighbourhood_id);
	$query = $this->db->get('neighbourhood');
	
	if($query->num_rows() > 0)
	{
		$n_row = $query->row();
		$customer_neighbourhood = $n_row->neighbourhood_name;
	}
	
	else
	{
		$customer_neighbourhood = '--Not set--';
	}
	
	$row = $dobi->row();
	$dobi_id = $row->dobi_id;
	$dobi_first_name = $row->dobi_first_name;
	$neighbourhood_id = $row->neighbourhood_id;
	$fold = $row->fold;
	$iron = $row->iron;
	$deliver = $row->deliver;
	$fold_cost = $row->fold_cost;
	$iron_cost = $row->iron_cost;
	$delivery_cost = $row->delivery_cost;
	
	$this->db->where('neighbourhood_id', $neighbourhood_id);
	$query = $this->db->get('neighbourhood');
	
	if($query->num_rows() > 0)
	{
		$n_row = $query->row();
		$dobi_neighbourhood = $n_row->neighbourhood_name;
	}
	
	else
	{
		$dobi_neighbourhood = '--Not set--';
	}
	
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
							<h2 class="mb-none"><strong>Hire Dobi</strong></h2>
                            
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
						<div class="col-md-9">

							<div class="panel-group" id="accordion">
								<div class="panel panel-default">
									<div class="panel-heading">
										<h4 class="panel-title">
											<a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="shop-checkout.html#collapseOne">
												Customer details
											</a>
										</h4>
									</div>
									<div id="collapseOne" class="accordion-body collapse in">
										<div class="panel-body">
											<form action="#" id="frmBillingAddress" method="post">
												<div class="row">
													<div class="form-group">
														<div class="col-md-6">
															<label>First Name</label>
															<input type="text" value="<?php echo $customer_first_name;?>" class="form-control" readonly="readonly">
														</div>
														<div class="col-md-6">
															<label>Last Name</label>
															<input type="text" value="<?php echo $customer_surname;?>" class="form-control" readonly="readonly">
														</div>
													</div>
												</div>
												<div class="row">
													<div class="form-group">
														<div class="col-md-6">
															<label>Phone</label>
															<input type="text" value="<?php echo $customer_phone;?>" class="form-control" readonly="readonly">
														</div>
														<div class="col-md-6">
															<label>Email</label>
															<input type="text" value="<?php echo $customer_email;?>" class="form-control" readonly="readonly">
														</div>
													</div>
												</div>
												<div class="row">
													<div class="form-group">
														<div class="col-md-12">
															<label>Neighbourhood</label>
															<input type="text" value="<?php echo $customer_neighbourhood;?>" class="form-control" readonly="readonly">
														</div>
													</div>
												</div>
											</form>
										</div>
									</div>
								</div>
								<div class="panel panel-default">
									<div class="panel-heading">
										<h4 class="panel-title">
											<a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="shop-checkout.html#collapseTwo">
												Dobi details
											</a>
										</h4>
									</div>
									<div id="collapseTwo" class="accordion-body collapse">
										<div class="panel-body">
											<form action="#" id="frmShippingAddress" method="post">
												
												<div class="row">
													<div class="form-group">
														<div class="col-md-6">
															<label>Name</label>
															<input type="text" value="<?php echo $dobi_first_name;?>" class="form-control" readonly="readonly">
														</div>
														<div class="col-md-6">
															<label>Neighbourhood</label>
															<input type="text" value="<?php echo $dobi_neighbourhood;?>" class="form-control" readonly="readonly">
														</div>
													</div>
												</div>
											</form>
										</div>
									</div>
								</div>
								<div class="panel panel-default">
									<div class="panel-heading">
										<h4 class="panel-title">
											<a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="shop-checkout.html#collapseThree">
												Review & payment
											</a>
										</h4>
									</div>
									<div id="collapseThree" class="accordion-body collapse">
										<div class="panel-body">
											<?php $this->load->view('site/cart/cart_contents')?>
											<hr class="tall">
                                            
											<h4 class="heading-primary">Order instructions</h4>
                                            <p>You can add instructions to your order e.g. your washing preferrences or when you will pick up or drop off your laundry</p>
                                            <?php 
											echo form_open('update-order-instructions/'.$dobi_id, array('class' => 'form-horizontal'));
											$instructions = $this->session->userdata('order_instructions');
											?>
                                            <div class="row">
                                            	<div class="col-md-12">
                                                    <div class="form-group">
                                                        <label class="col-md-2">Instructions</label>
                                                        <div class="col-md-10">
                                                            <textarea class="form-conrol" rows="10" name="order_instructions" style="width:100%;"><?php echo $instructions;?></textarea>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-4 col-sm-offset-5">
                                                    <input type="submit" data-loading-text="Loading..." class="btn btn-primary pull-right mb-xl" value="Update instructions">
                                                </div>
                                            </div>
                                            <?php echo form_close();?>
                                            
											<h4 class="heading-primary">Options</h4>
											<div class="featured-boxes">
                                                <div class="row">
                                                    <div class="col-sm-6">
                                                        <div class="featured-box featured-box-primary align-left mt-xlg">
                                                            <div class="box-content">
                                                                <h4 class="heading-primary text-uppercase mb-md">Options</h4>
                                                                <form action="<?php echo site_url().'update-cart-options/'.$dobi_id;?>" id="frmCalculateShipping" method="post">
                                                                    <div class="row">
                                                                    	<?php
                                                                        if($fold == 1)
																		{
																		?>
                                                                        <div class="col-sm-6">
                                                                            <span class="remember-box checkbox">
                                                                                <label for="fold">
                                                                                <?php
																				if($selected_fold_cost >  0)
																				{
																				?>
                                                                                <input type="checkbox" id="fold" name="fold" value="<?php echo $fold_cost;?>" checked="checked">Fold clothes after wash
																				<?php
																				}
																				
																				else
																				{
																				?>
                                                                                <input type="checkbox" id="fold" name="fold" value="<?php echo $fold_cost;?>">Fold clothes after wash
																				<?php
																				}
																				?>  
                                                                                </label>
                                                                            </span>
                                                                        </div>
                                                                        <?php
																		}
																		?>
                                                                    	<?php
                                                                        if($iron == 1)
																		{
																		?>
                                                                        <div class="col-sm-6">
                                                                            <span class="remember-box checkbox">
                                                                                <label for="iron">
                                                                                <?php
																				if($selected_iron_cost >  0)
																				{
																				?>
                                                                                <input type="checkbox" id="iron" name="iron" value="<?php echo $iron_cost;?>" checked="checked">Iron clothes after wash
																				<?php
																				}
																				
																				else
																				{
																				?>
                                                                                <input type="checkbox" id="iron" name="iron" value="<?php echo $iron_cost;?>">Iron clothes after wash
																				<?php
																				}
																				?> 
                                                                                </label>
                                                                            </span>
                                                                        </div>
                                                                        <?php
																		}
																		?>
                                                                    	<?php
                                                                        if($deliver == 1)
																		{
																		?>
                                                                        <div class="col-sm-6">
                                                                            <span class="remember-box checkbox">
                                                                                <label for="deliver">
                                                                                 <?php
																				if($selected_delivery_cost >  0)
																				{
																				?>
                                                                                <input type="checkbox" id="deliver" name="deliver" value="<?php echo $delivery_cost;?>" checked="checked">Deliver clothes to me
																				<?php
																				}
																				
																				else
																				{
																				?>
                                                                                <input type="checkbox" id="deliver" name="deliver" value="<?php echo $delivery_cost;?>">Deliver clothes to me
																				<?php
																				}
																				?> 
                                                                                </label>
                                                                            </span>
                                                                        </div>
                                                                        <?php
																		}
																		?>
                                                                    </div>
                                                                    
                                                                    <div class="row">
                                                                        <div class="col-md-12">
                                                                            <input type="submit" data-loading-text="Loading..." class="btn btn-primary pull-right mb-xl" value="Update options">
                                                                        </div>
                                                                    </div>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <div class="featured-box featured-box-primary align-left mt-xlg">
                                                            <div class="box-content">
                                                                <h4 class="heading-primary text-uppercase mb-md">Cart Totals</h4>
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
                                                                                <strong>Cart Subtotal</strong>
                                                                            </th>
                                                                            <td>
                                                                                <strong><span class="amount"><?php echo number_format($basket_total, 0);?></span></strong>
                                                                            </td>
                                                                        </tr>
                                                                        <?php if($selected_fold_cost > 0){?>
                                                                        <tr class="shipping">
                                                                            <th>
                                                                                Fold clothes
                                                                            </th>
                                                                            <td>
                                                                                <?php echo number_format($selected_fold_cost, 0);?>
                                                                            </td>
                                                                        </tr>
                                                                        <?php }?>
                                                                        <?php if($selected_iron_cost > 0){?>
                                                                        <tr class="shipping">
                                                                            <th>
                                                                                Iron clothes
                                                                            </th>
                                                                            <td>
                                                                                <?php echo number_format($selected_iron_cost, 0);?>
                                                                            </td>
                                                                        </tr>
                                                                        <?php }?>
                                                                        <?php if($selected_delivery_cost > 0){?>
                                                                        <tr class="shipping">
                                                                            <th>
                                                                               Deliver clothes
                                                                            </th>
                                                                            <td>
                                                                                <?php echo number_format($selected_delivery_cost, 0);?>
                                                                            </td>
                                                                        </tr>
                                                                        <?php }?>
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
                
                                            </div>

											<hr class="tall">

											<h4 class="heading-primary">Payment</h4>

											<form action="<?php echo site_url().'make-payment/'.$dobi_id;?>" id="frmPayment" method="post">
												<div class="row">
													<div class="col-md-12">
														<span class="remember-box checkbox">
															<label>
																<input type="radio" checked="checked" name="payment_type" value="1">Mpesa
															</label>
														</span>
													</div>
												</div>
												<div class="row">
													<div class="col-md-12">
														<span class="remember-box checkbox">
															<label>
																<input type="radio" name="payment_type" value="2">Pesapal
															</label>
														</span>
													</div>
												</div>
												<div class="row">
													<div class="col-sm-12">
                                                        <div class="actions-continue pull-left">
                                                            <a href="<?php echo site_url().'save-order/'.$dobi_id;?>" class="btn btn-lg btn-default"><i class="fa fa-save"></i> Save order</a>
                                                        </div>
                                                        <div class="actions-continue">
														<button type="submit" class="btn btn-lg btn-primary">Proceed to payment</button>
                                                        </div>
													</div>
												</div>
                                                
											</form>
										</div>
									</div>
								</div>
							</div>

						</div>
						<div class="col-md-3">
							<h4 class="heading-primary">Basket Totals</h4>
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