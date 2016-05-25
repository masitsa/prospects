<?php
    if(count($contacts) > 0)
    {
        $email = $contacts['email'];
        $facebook = $contacts['facebook'];
        $twitter = $contacts['twitter'];
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

        $mission = $contacts['mission'];
        $vision = $contacts['vision'];
    }
?>

        		<section class="heading-page">
					<div class="container">
						<div class="row">
							<div class="col-md-12">
								<h1>About Us</h1>
								<span>Here is some more information about our company</span>
							</div>
						</div>
					</div>
				</section>

				<section class="first-call-to-action">
					<div class="container">
						<div class="row">
							<div class="col-md-12">
								<div class="section-heading-middle text-center">
									<h2>Hello, We're <em><?php echo $company_name;?></em></h2>
									<img src="<?php echo base_url().'assets/logo/'.$logo;?>" alt="<?php echo $company_name;?>" height="50">
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-4">
								<h3>Mission</h3>
                                <p><?php echo $mission;?></p>
                                <h3>Vision</h3>
                                <p><?php echo $vision?></p>
							</div>
							<div class="col-md-8">
								<p><?php echo $about;?></p>
							</div>
						</div>
					</div>
				</section>
        