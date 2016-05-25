<!-- styles needed by footable  -->
<link href="<?php echo base_url().'assets/footable';?>/css/footable-0.1.css" rel="stylesheet" type="text/css" />
<link href="<?php echo base_url().'assets/footable';?>/css/footable.sortable-0.1.css" rel="stylesheet" type="text/css" />

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
                    <div class="heading heading-border heading-bottom-border">
                        <div class="row">
                            <div class="col-sm-10">
                                <h4><i class="fa fa-calendar"></i> Order history</h4>
                            </div>
                            <div class="col-sm-2">
                                <a href="<?php echo site_url().'customer/sign-out';?>" class="pull-right"><i class="fa fa-sign-out"></i> Sign out</a>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-12 category-content">
                        	
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
	        	<?php
				
	            	if($all_orders->num_rows() > 0)
					{
						$orders = $all_orders->result();
				?>
              <div class="alert alert-warning"><strong>Note!</strong> Saved orders will be deleted automatically after 7 days</div>
	          <table class="footable">
	            <thead>
	              <tr>
	                <th data-class="expand" data-sort-initial="true"> <span title="table sorted by this column on load">Order ID</span> </th>
	                <th data-hide="phone,tablet" data-sort-ignore="false">Date</th>
	                <th data-hide="phone,tablet" data-sort-ignore="false">No. of items</th>
	                <th data-hide="phone,tablet" data-sort-ignore="false">Total Cost (Kes)</th>
	                <!--<th data-hide="phone,tablet" data-sort-ignore="true">Details</th>-->
	                <th data-hide="phone,tablet"><strong>Payment Method</strong></th>
	                <th data-hide="default"> Instructions </th>
	                <th data-hide="default" data-type="numeric"> Items ordered </th>
	                <th data-hide="phone" data-type="numeric"> Status </th>
	              </tr>
	            </thead>
	            <tbody>
	            <?php
	            	foreach($orders as $ord)
					{
						$instructions = $ord->order_instructions;
						$order_number = $ord->order_number;
						$method = $ord->payment_method_name;
						$created = date('jS M Y H:i a',strtotime($ord->order_created));
						$status = $ord->order_status_id;
						$status_name = $ord->order_status_name;
						$order_id = $ord->order_id;
						$dobi_id = $ord->dobi_id;
						$dobi_name = $ord->dobi_first_name;
						$order_items = $this->orders_model->get_order_items($order_id);
						$items = '';
						$cancel = '';
						$total_items = 0;
						$fold_cost = $ord->fold_cost;
						$iron_cost = $ord->iron_cost;
						$delivery_cost = $ord->delivery_cost;
						$total_additional_price = $fold_cost + $iron_cost + $delivery_cost;
						
						//allow cancel on status < 4
						if($status < 4)
						{
							$cancel = '<a class="btn btn-danger btn-sm" href="'.site_url().'account/request-cancel-order/'.$order_number.'" ><i class="glyphicon glyphicon-remove-circle"> </i> Request cancel</span></a>';
						}
						
						//order items
						if($order_items->num_rows() > 0)
						{
							$items = '
							<table class="table table-striped table-condensed">
							<tr>
								<th>Item</th>
								<th></th>
								<th>Quantity</th>
								<th>Price (Kes)</th>
								<th>Total (Kes)</th>
							</tr>';
							$order_items = $order_items->result();
							$total_price = 0;
							$total_items = 0;
							
							foreach($order_items as $res)
							{
								$order_item_id = $res->order_item_id;
								$category = $res->category_name;
								$quantity = $res->order_item_quantity;
								$price = $res->order_item_price;
								$category_id = $res->category_id;
								$web_name = $this->site_model->create_web_name($dobi_name);
								$dobi_link = site_url().'businesses/'.$web_name.'&'.$dobi_id;
								
								$total_items += $quantity;
								
								$total_price += ($quantity * $price);
								
								$items .= '
								<tr>
									<td>
									'.$category.'
									</td>
									<td><a class="btn btn-primary btn-sm add_to_cart" href="'.$category_id.'"><span class="add2cart"><i class="fa fa-shopping-cart"> </i> Wash again </span></a></td>
									<td>'.$quantity.'</td>
									<td>'.number_format($price, 2, '.', ',').'</td>
								</tr>
								';
							}
							$order_total = ($total_price + $total_additional_price);
							$minimal_order_charge = 0;
							if($order_total < 1000)
							{
								$minimal_order_charge = 1000 - $order_total;
								$order_total = 1000;
							}
								
							$items .= '
								<tr>
									<td>Fold</td>
									<td></td>
									<td></td>
									<td></td>
									<td>'.number_format($fold_cost, 2, '.', ',').'</td>
								</tr>
								<tr>
									<td>Iron</td>
									<td></td>
									<td></td>
									<td></td>
									<td>'.number_format($iron_cost, 2, '.', ',').'</td>
								</tr>
								<tr>
									<td>Delivery</td>
									<td></td>
									<td></td>
									<td></td>
									<td>'.number_format($delivery_cost, 2, '.', ',').'</td>
								</tr>
								<tr>
									<td>Minimal order charge</td>
									<td></td>
									<td></td>
									<td></td>
									<td>'.number_format($minimal_order_charge, 2, '.', ',').'</td>
								</tr>
								<tr>
									<td></td>
									<td></td>
									<td></td>
									<td></td>
									<td colspan="5">'.number_format($order_total, 2, '.', ',').'</td>
								</tr>
							</table>
							';
						}
						
						else
						{
							$items = 'This order has no items';
						}
						$button = '';
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
							$order_status = '<span class="label label-warning">'.$status_name.'</span> <a href="'.site_url().'order/make-payment/'.$order_id.'" class="btn btn-sm btn-info pull-right">Make payment</a>';
						}
						
						else if($status == 6)
						{
							$order_status = '<span class="label label-danger">'.$status_name.'</span>';
						}
						
						else
						{
							$order_status = '<span class="label label-info">'.$status_name.'</span>';
						}
						if($total_items == 1)
						{
							$display = 'item';
						}
						else
						{
							$display = 'items';
						}
						$instructions .= '<p>This order was washed by <a href="'.$dobi_link.'" target="_blank">'.$dobi_name.'</a></p>';
				?>
	              <tr>
	                <td><?php echo $order_number;?></td>
	                <td data-value="<?php echo strtotime($ord->order_created);?>"><?php echo $created;?></td>
	                <td><?php echo $total_items;?> <small><?php echo $display;?></small></td>
	                <td><?php echo number_format(($order_total), 2);?></td>
	                <!--<td><?php echo $items;?></td>-->
	                <td><?php echo $method;?></td>
	                <td><?php echo $instructions;?></td>
	                <td><?php echo $items;?></td>
	                <td data-value="3"><?php echo $order_status;?></td>
	              </tr>
	             <?php
					}
				 ?>
	            </tbody>
	          </table>
			  <?php
					}
					else
					{
						echo '<p>You have not placed any orders. Please <a href="'.site_url().'wash">wash here</a></p>';
					}
				?>
	        </div>
	        
	        <?php echo $this->load->view('account/navigation', '', TRUE);?>
	        
	      </div>
	      <!--/row end--> 
	      
	    </div>
    </div>
  </div>
  <!--/row-->
  
  <div style="clear:both"></div>
</div>
<!-- /main-container -->
<!-- include footable plugin --> 
<script src="<?php echo base_url().'assets/footable';?>/js/footable.js" type="text/javascript"></script> 
<script src="<?php echo base_url().'assets/footable';?>/js/footable.sortable.js" type="text/javascript"></script> 
<script type="text/javascript">
    $(function() {
      $('.footable').footable();
    });
  </script> 
