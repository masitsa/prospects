
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
                                <h4>Edit clothes in your basket</h4>
                            </div>
                        
                            <p>
                                You can adjust quantities or remove clothes from your basket. Once you are content. Please select a Dobi (washer) to send the clothes to.
                            </p>
                        	<div class="alert alert-info">The minimum order amount is <strong>Kes 1,000.00</strong>. If your laundry adds up to less than that you the order price shall be raised to Kes 1,000.00</div>
                        </div>
                        
						<div class="col-md-12">
						<?php
						$success_message = $this->session->userdata('success_message');
						$this->session->unset_userdata('success_message');
						
						if(!empty($success_message))
						{
							echo '<div class="alert alert-success">'.$success_message.'</div>';
						}
						
						$error_message = $this->session->userdata('error_message');
						$this->session->unset_userdata('error_message');
						if(!empty($error_message))
						{
							echo '<div class="alert alert-danger">'.$error_message.'</div>';
						}
						?>

							<div class="featured-boxes">
								<div class="row">
									<div class="col-md-12">
										<div class="featured-box featured-box-primary align-left mt-sm">
											<div class="box-content">
												<?php $this->load->view('site/cart/cart_contents')?>
											</div>
										</div>
									</div>
								</div>
							</div>

							<div class="row">
								<div class="col-md-12">
									<div class="actions-continue">
										<a href="<?php echo site_url().'select-dobi';?>" class="btn btn-lg btn-primary">Select a dobi →</a>
									</div>
								</div>
							</div>

						</div>
					</div>

				</div>

			</div>

