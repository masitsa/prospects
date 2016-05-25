 <?php
	if(count($contacts) > 0)
	{
		$email = $contacts['email'];
		$email2 = $contacts['email'];
		$facebook = $contacts['facebook'];
		$twitter = $contacts['twitter'];
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
		
		if(!empty($facebook))
		{
			//$facebook = '<li class="facebook"><a href="'.$facebook.'" target="_blank" title="Facebook">Facebook</a></li>';
		}
		
	}
	else
	{
		$email = '';
		$facebook = '';
		$twitter = '';
		$linkedin = '';
		$logo = '';
		$company_name = '';
		$google = '';
		$address = '';
		$post_code = '';
		$city = '';
		$building = '';
		$floor = '';
		$location = '';
	}
?>


<section class="heading-page">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h1>Contact Us</h1>
                <span>Feel free to write us</span>
            </div>
        </div>
    </div>
</section>

<section class="contact-form">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
            	<form action="<?php echo site_url();?>site/contact_us/contact" method="post" id="contact-form">
                    <div class="row">
                        <div class="col-md-4 col-sm-12">
                            <input type="text" name="first_name" id="name-id" placeholder="First Name">
                        </div>
                        <div class="col-md-4 col-sm-12">
                            <input type="text" name="last_name" id="surname-id" placeholder="Last Name">
                        </div>
                        <div class="col-md-4 col-sm-12">
                            <input type="email" name="email" id="email-id" placeholder="Email Address">
                        </div>
                        <div class="col-md-4 col-sm-12">
                            <input type="text" name="phone" id="email-id" placeholder="Phone Number">
                        </div>
                        <div class="col-md-12 col-sm-12">
                            <textarea name="message" id="message-id" placeholder="Message"></textarea>
                            <button id="submit-contact" type="submit" class="btn">Send Message</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>

<section class="contact-info">
    <div class="container">
        <div class="row">
            <div class="col-md-4 col-ms-4 col-xs-12">
                <div class="contact-item">
                    <i class="fa fa-map-marker"></i>
                    <span><?php echo $location;?><br><?php echo $building;?> <?php echo $floor;?></span>
                </div>
            </div>
            <div class="col-md-4 col-ms-4 col-xs-12">
                <div class="contact-item">
                    <i class="fa fa-phone"></i>
                    <span><?php echo $phone;?></span>
                </div>
            </div>
            <div class="col-md-4 col-ms-4 col-xs-12">
                <div class="contact-item">
                    <i class="fa fa-envelope"></i>
                    <span><?php echo $email;?></span>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="map-section">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="content-map">
                    <div class="contact-map" style="height: 420px;"></div>
                </div>
            </div>
        </div>
    </div>
</section>

        <!-- Google Map -->
        <script type="text/javascript" src="//maps.googleapis.com/maps/api/js?key=AIzaSyCRL4A7M9ZGM7GIPaZqbfv67xtcPFLc2xc&libraries=places"></script>
    <script src="<?php echo base_url()."assets/themes/anchro/";?>assets/js/jquery.gmap3.min.js"></script>
    
    <!-- Google Map Init-->
    <script type="text/javascript">
        jQuery(function($){
            $('.contact-map').gmap3({
                marker:{
                    address: '-1.3121379,36.8076973' 
                },
                    map:{
                    options:{
                    zoom: 15,
                    scrollwheel: true,
                    streetViewControl : true
                    }
                }
            });
        });
    </script>