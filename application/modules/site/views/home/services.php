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
								
							</div>
						</div>
						<div class="row">
							<div class="col-md-4">
							 <div class="section-heading-middle text-center">
                    			<h2>About <em><?php echo $company_name;?></em></h2>
                    			<img src="<?php echo base_url().'assets/logo/'.$logo;?>" alt="<?php echo $company_name;?>" height="150">
                			 </div>
								<div class="left-text">
								<p>Prospects Communication is a multi-media communications and marketing company specializing <br>in behavioral change and development communication, and social marketing.</p>
									<a href="<?php echo site_url().'services';?>">View More About Us</a>
								</div>
							</div>
							<div class="col-md-8">
							<div class="section-heading-left text-left">
									<img src="<?php echo base_url().'assets/logo/'.$logo;?>" alt="<?php echo $company_name;?>" height="50">
									<h2>Our <em>Services</em></h2>
									<p>Here ia a brief description of some <span>services</span> we offer.<br>Read more about our service from our services link.</p>
								</div>
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
