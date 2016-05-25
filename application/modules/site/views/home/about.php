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
	
	$about = implode(' ', array_slice(explode(' ', strip_tags($about)), 0, 18));
?>
<section class="first-call-to-action">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="section-heading-middle text-center">
                    <h2>About <em><?php echo $company_name;?></em></h2>
                    <img src="<?php echo base_url().'assets/logo/'.$logo;?>" alt="<?php echo $company_name;?>" height="150">
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <p><?php echo $about;?></p>
                <div class="accent-button">
                    <a href="<?php echo site_url().'about';?>">Learn More</a>
                </div>
            </div>
        </div>
    </div>
</section>