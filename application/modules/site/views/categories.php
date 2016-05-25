<?php
	$pics = '';
	
	if($categories->num_rows() > 0)
	{
		foreach($categories->result() as $res)
		{
			$category_id = $res->category_id;
			$name = $res->category_name;
			$image = $res->category_image_name;
			$price = $res->category_price;
			$image = $this->site_model->image_display($categories_path, $categories_location, $image);
			$pics .= '
							<li class="col-md-3 col-sm-6 col-xs-12 product">
								<span class="product-thumb-info">
									<a href="'.$category_id.'" class="add-to-cart-product add_to_cart">
										<span><i class="fa fa-shopping-cart"></i> Add to basket</span>
									</a>
									<a href="#">
										<span class="product-thumb-info-image">
											<img class="img-responsive" src="'.$image.'" alt="'.$name.'">
										</span>
									</a>
									<span class="product-thumb-info-content">
										<a href="#">
											<h4>'.$name.'</h4>
											<span class="price">
												<span class="amount">Kes '.$price.'</span>
											</span>
										</a>
									</span>
								<a href="'.$category_id.'" class="add_to_cart btn btn-success"><i class="fa fa-shopping-cart"></i> Add to basket</a>
								</span>
								
							</li>
			';
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
								<h1><?php echo $title;?></h1>
							</div>
						</div>
					</div>
				</section>

				<div class="container">

					<div class="row">
						<?php
						$success_message = $this->session->userdata('success_message');
						$this->session->unset_userdata('success_message');
						
						if(!empty($success_message))
						{
							echo '<div class="alert alert-success">'.$success_message.'</div>';
						}
						?>
                        
                        <div class="col-md-12">
                        	<div class="heading heading-border heading-bottom-border">
                            	<h4>Washing is as easy as 1 - 2 - 3</h4>
                            </div>
                        </div>
                        
                        <ol>
                        	<li>Select the items you want to wash</li>
                        	<li>Add quantities to the selected items</li>
                        	<li>Find a Dobi (washer)</li>
                        </ol>
                        
                        <p>Start now by seleting the itmes you would like washed! Add them to your basket <i class="fa fa-shopping-cart"></i>.</p>
                        <div class="alert alert-info">The minimum order amount is <strong>Kes 1,000.00</strong>. If your laundry adds up to less than that you the order price shall be raised to Kes 1,000.00</div>
						<ul class="products product-thumb-info-list" data-plugin-masonry>
							<?php echo $pics;?>
						</ul>

					</div>

				</div>

			</div>
