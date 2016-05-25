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

        $working_weekday = $contacts['working_weekday'];
        $working_weekend = $contacts['working_weekend'];
	}
?>
<footer>
					<div class="container">
						<div class="row">
							<div class="col-md-4">
								<div class="about-us">
									<img src="<?php echo base_url().'assets/logo/'.$logo;?>" alt="<?php echo $company_name;?>" class="logo">
									<ul class="social-icons">
										<li><a href="<?php echo $facebook;?>"><i class="fa fa-facebook"></i></a></li>
										<li><a href="<?php echo $linkedin;?>"><i class="fa fa-linkedin"></i></a></li>
									</ul>
								</div>
							</div>
							<div class="col-md-offset-5 col-md-3">
								<div class="contact-info">	
									<h6>Contact Info</h6>
									<ul class="contact-list">
										<li><span>Phone:</span> <?php echo $phone;?></li>
										<li><span>Address:</span> <?php echo $building;?> <?php echo $floor;?> <?php echo $location;?></li>
										<li><span>Email:</span> <a href="mailto:<?php echo $email;?>"><?php echo $email;?></a></li>
									</ul>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-12">	
								<div class="copyright-text">
									<p>@ <?php echo date('Y');?> <a href="https://www.omnis.co.ke" target="_blank">Omnis Limited</a>. All Rights Reserved.</p>
								</div>
							</div>
						</div>
					</div>
				</footer>

				<a href="#" class="go-top"><i class="fa fa-angle-up"></i></a>