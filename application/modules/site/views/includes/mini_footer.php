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
					<div class="company-info">
						<img src="<?php echo base_url().'assets/logo/'.$logo;?>" alt="<?php echo $company_name;?>" class="logo">
						<div class="line-dec"></div>
						<ul class="contact-list">
							<li><span>Phone:</span> <?php echo $phone;?></li>
							<li><span>Address:</span> <?php echo $building;?> <?php echo $floor;?> <?php echo $location;?></li>
							<li><span>Email:</span> <a href="mailto:<?php echo $email;?>"><?php echo $email;?></a></li>
						</ul>
						<ul class="social-icons">
							<li><a href="<?php echo $facebook;?>"><i class="fa fa-facebook"></i></a></li>
                            <li><a href="<?php echo $linkedin;?>"><i class="fa fa-linkedin"></i></a></li>
						</ul>
					</div>
                    