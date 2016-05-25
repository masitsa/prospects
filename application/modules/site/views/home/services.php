<?php
	if(count($contacts) > 0)
	{
		$email = $contacts['email'];
		$facebook = $contacts['facebook'];
		$linkedin = $contacts['linkedin'];
		$logo = $contacts['logo'];
		$company_name = $contacts['company_name'];
		$phone = $contacts['phone'];
        $address = $contacts['address'];
        $post_code = $contacts['post_code'];
        $city = $contacts['city'];
        $building = $contacts['building'];
        $floor = $contacts['floor'];
        $location = $contacts['location'];
        $about = $contacts['about'];

        $working_weekday = $contacts['working_weekday'];
        $working_weekend = $contacts['working_weekend'];
	}
?>
				<section class="our-services">
					<div class="container">
						<div class="row">
							<div class="col-md-12">
								<div class="section-heading-left text-left">
									<img src="<?php echo base_url().'assets/logo/'.$logo;?>" alt="<?php echo $company_name;?>" height="50">
									<h2>Our <em>Services</em></h2>
									<p>Here are some of <span>the services</span> we offer.</p>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-4">
								<div class="left-text">
									<p>Flexitarian retro affogato listicle truffaut gluten-free and ready made. Kickstarter organic paleo roof party, crucifi craft beer tumblr bicycleseitan scenester.<br><br>Craft to be normcore man braid slow-carb cliche komb, lomo post-ironic typewriter limuzine portland.</p>
									<a href="<?php echo site_url().'services';?>">View all services now</a>
								</div>
							</div>
							<div class="col-md-8">
								<div class="services-item">
                                	<?php
									$services = $this->site_model->get_active_services();
									$checking_items = '';
									if($services->num_rows() > 0)
									{   $count = 0;
										foreach($services->result() as $res)
										{
											$service_name = $res->service_name;
											$service_description = $res->service_description;
											$service_image_name = $res->service_image_name;
											$mini_desc = implode(' ', array_slice(explode(' ', strip_tags($service_description)), 0, 10));
											 $maxi_desc = implode(' ', array_slice(explode(' ', $service_description), 0, 30));
											$web_name = $this->site_model->create_web_name($service_name);
					
											$count ++;
					
											$checking_items .= '
											<div class="col-md-6">
												<div class="service-item">
													<img src="'.base_url().'assets/service/'.$service_image_name.'" alt="" height="50">
													<h4>'.$service_name.'</h4>
													<div class="line-dec"></div>
													<p>'.$mini_desc.'</p>
												</div>
											</div>'; 
										}
									}
									echo $checking_items;
								?>
								</div>
							</div>
						</div>
					</div>
				</section>
