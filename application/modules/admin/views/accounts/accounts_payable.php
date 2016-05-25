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
						<th><a href="'.site_url().'admin/accounts-payable/order_created/'.$order_method.'/'.$page.'">Order date</a></th>
						<th><a href="'.site_url().'admin/accounts-payable/order_number/'.$order_method.'/'.$page.'">Order number</a></th>
						<th><a href="'.site_url().'admin/accounts-payable/dobi_first_name/'.$order_method.'/'.$page.'">Dobi</a></th>
						<th><a href="'.site_url().'admin/accounts-payable/orders.payment_method_id/'.$order_method.'/'.$page.'">Payment method</a></th>
						<th><a href="'.site_url().'admin/accounts-payable/orders.order_status_id/'.$order_method.'/'.$page.'">Status</a></th>
						<th>Total items</th>
						<th>Pay to Dobi</th>
						<th>Pay to Me</th>
						<th>Total</th>
						<th>Actions</th>
					</tr>
				</thead>
				  <tbody>
				  
			';
			
			foreach ($query->result() as $row)
			{
				//order details
				$order_id = $row->order_id;
				$customer_name = $row->customer_first_name.' '.$row->customer_surname;
				$dobi_name = $row->dobi_first_name.' '.$row->dobi_surname;
				$order_number = $row->order_number;
				$status = $row->order_status_id;
				$status_name = $row->order_status_name;
				$order_created = $row->order_created;
				$payment_method_name = $row->payment_method_name;
				$fold_cost = $row->fold_cost;
				$iron_cost = $row->iron_cost;
				$delivery_cost = $row->delivery_cost;
				$total_additional_price = $fold_cost + $iron_cost + $delivery_cost;
				
				//order items
				$order_items = $this->orders_model->get_order_items($order_id);
				$order_items = $order_items->result();
				$total_price = 0;
				$total_items = 0;
				foreach($order_items as $res)
				{
					$order_item_id = $res->order_item_id;
					$category = $res->category_name;
					$quantity = $res->order_item_quantity;
					$price = $res->order_item_price;
					
					$total_items += $quantity;
					
					$total_price += ($quantity * $price);
				}
				$order_total = $total_price + $total_additional_price;
				$minimal_order_charge = 0;
				if($order_total < 1000)
				{
					$minimal_order_charge = 1000 - $order_total;
					$order_total = 1000;
				}
				$pay_to_dobi = $order_total * 0.9;
				$pay_to_me = $order_total - $pay_to_dobi;
				
				//order status
				if($status == 0)
				{
					$order_status = '<span class="label label-primary">'.$status_name.'</span>';
				}
				
				else if($status == 1)
				{
					$order_status = '<span class="label label-success">'.$status_name.'</span>';
				}
				
				else if($status == 2)
				{
					$order_status = '<span class="label label-danger">'.$status_name.'</span>';
				}
				
				else if($status == 3)
				{
					$order_status = '<span class="label label-default">'.$status_name.'</span>';
				}
				
				else if($status == 4)
				{
					$order_status = '<span class="label label-warning">'.$status_name.'</span>';
				}
				
				else if($status == 6)
				{
					$order_status = '<span class="label label-danger">'.$status_name.'</span>';
				}
				
				$count++;
				$result .= 
				'
					<tr>
						<td>'.$count.'</td>
						<td>'.date('jS M Y H:i a',strtotime($order_created)).'</td>
						<td>'.$order_number.'</td>
						<td>'.$dobi_name.'</td>
						<td>'.$payment_method_name.'</td>
						<td>'.$order_status.'</td>
						<td>'.$total_items.'</td>
						<td>'.number_format($pay_to_dobi, 2).'</td>
						<td>'.number_format($pay_to_me, 2).'</td>
						<td>'.number_format(($order_total), 2).'</td>
						<!--<td><a href="'.site_url().'admin/unconfirm-payment/'.$order_id.'/'.$order_number.'/'.$order.'/'.$order_method.'/'.$page.'" class="btn btn-sm btn-success" title="Confirm payment"><i class="fa fa-pencil" onclick="return confirm(\'Do you really want to unconfirm the payment for order number '.$order_number.'?\');"></i></a></td>-->
						<td><a href="'.site_url().'admin/receipt-payment/'.$order_id.'/'.$order_number.'/'.$order.'/'.$order_method.'/'.$page.'" class="btn btn-sm btn-success" title="Receipt payment" onclick="return confirm(\'Do you really want to receipt the payment for order number '.$order_number.'?\');"><i class="fa fa-plus"></i></a></td>
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
			$result .= "There are no categories";
		}
?>
                        <div class="row">
                            <div class="col-sm-4">
                                <section class="panel panel-featured-left panel-featured-primary">
                                    <div class="panel-body">
                                        <div class="widget-summary">
                                            <div class="widget-summary-col widget-summary-col-icon">
                                                <div class="summary-icon bg-primary">
                                                    <i class="fa fa-shopping-cart"></i>
                                                </div>
                                            </div>
                                            <div class="widget-summary-col">
                                                <div class="summary">
                                                    <h4 class="title">Transactions</h4>
                                                    <div class="info">
                                                        <strong class="amount"><?php echo $total_transactions;?></strong>
                                                    </div>
                                                </div>
                                                <div class="summary-footer">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </section>
                            </div>
                            
                            <div class="col-sm-4">
                                <section class="panel panel-featured-left panel-featured-secondary">
                                    <div class="panel-body">
                                        <div class="widget-summary">
                                            <div class="widget-summary-col widget-summary-col-icon">
                                                <div class="summary-icon bg-secondary">
                                                    <i class="fa fa-usd"></i>
                                                </div>
                                            </div>
                                            <div class="widget-summary-col">
                                                <div class="summary">
                                                    <h4 class="title">Owe Dobi</h4>
                                                    <div class="info">
                                                        <strong class="amount">Kes <?php echo number_format($total_dobi, 2);?></strong>
                                                    </div>
                                                </div>
                                                <div class="summary-footer">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </section>
                            </div>
                            
                            <div class="col-sm-4">
                                <section class="panel panel-featured-left panel-featured-tertiary">
                                    <div class="panel-body">
                                        <div class="widget-summary">
                                            <div class="widget-summary-col widget-summary-col-icon">
                                                <div class="summary-icon bg-tertiary">
                                                    <i class="fa fa-usd"></i>
                                                </div>
                                            </div>
                                            <div class="widget-summary-col">
                                                <div class="summary">
                                                    <h4 class="title">Profit</h4>
                                                    <div class="info">
                                                        <strong class="amount">Kes <?php echo number_format($total_profit, 2);?></strong>
                                                    </div>
                                                </div>
                                                <div class="summary-footer">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </section>
                            </div>
                        </div>

						<section class="panel">
							<header class="panel-heading">
								<div class="panel-actions">
									<a href="#" class="panel-action panel-action-toggle" data-panel-toggle></a>
									<a href="#" class="panel-action panel-action-dismiss" data-panel-dismiss></a>
								</div>
						
								<h2 class="panel-title"><?php echo $title;?></h2>
							</header>
							<div class="panel-body">
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
                                ?>
                            	
                                <?php echo form_open('admin/search-accounts-payable');?>
                                <div class="row" style="margin-bottom:20px;">
                                	<div class="col-sm-6">
                                        <div class="form-group">
                                            <label class="col-lg-4 control-label">Status</label>
                                            <div class="col-lg-6">
                                                <select name="order_status_id" class="form-control">
                                                    <?php
                                                    echo '<option value="">--Select status--</option>';
                                                    if($order_statuses->num_rows() > 0)
                                                    {
                                                        $result2 = $order_statuses->result();
                                                        
                                                        foreach($result2 as $res)
                                                        {
                                                            echo '<option value="'.$res->order_status_id.'">'.$res->order_status_name.'</option>';
                                                        }
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    
                                	<div class="col-sm-6">
                                        <div class="form-group">
                                            <label class="col-lg-4 control-label">Method</label>
                                            <div class="col-lg-6">
                                                <select name="payment_method_id" class="form-control">
                                                    <?php
                                                    echo '<option value="">--Select method--</option>';
                                                    if($payment_method->num_rows() > 0)
                                                    {
                                                        $result2 = $payment_method->result();
                                                        
                                                        foreach($result2 as $res)
                                                        {
                                                            echo '<option value="'.$res->payment_method_id.'">'.$res->payment_method_name.'</option>';
                                                        }
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <?php
								$search = $this->session->userdata('payable_search');
								
								if(empty($search))
								{
									?>
                                    <div class="row">
                                        <div class="col-sm-4 col-sm-offset-4">
                                            <button type="submit" class="btn btn-primary">Filter</button>
                                        </div>
                                    </div>
                                    <?php
								}
								
								else
								{
									?>
                                    <div class="row">
                                        <div class="col-sm-4 col-sm-offset-2">
                                            <button type="submit" class="btn btn-primary">Filter</button>
                                        </div>
                                        <div class="col-sm-4">
                                            <a href="<?php echo site_url().'admin/close-payable-search';?>" class="btn btn-info">Close search</a>
                                        </div>
                                    </div>
                                    <?php
								}
								?>
                                <?php echo form_close();?>
                                
								<div class="table-responsive">
									<?php echo $result;?>
							
                                </div>
							</div>
                            
							<div class="panel-body">
                            	<?php if(isset($links)){echo $links;}?>
							</div>
                            
						</section>