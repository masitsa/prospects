<!DOCTYPE html>
<html lang="en">
    	<!-- head -->
    	<?php echo $this->load->view('site/includes/header', '', TRUE); ?>
    	<!-- end of head -->
    <body>
    <div id="page">
        <!-- Page Loader -->
        <div id="pageloader">
            <div class="loader-item fa fa-spin text-color"></div>
        </div>
        
        <!-- Sticky Navbar -->
		<div class="">
			<!-- Sticky Navbar -->
			<?php echo $this->load->view('site/includes/navigation', '', TRUE); ?>
			<!-- end of sticker nav bar -->
		</div><!-- Transparent Header Ends -->	
	 	
	 	<!-- content -->
	 	<?php echo $content;?>
	 	<!-- end of content -->
		
        <!-- start of footer -->
        <?php echo $this->load->view('site/includes/footer', '', TRUE); ?>
        <!--end of footer -->
    </div>
    <!-- page -->
    <!-- Scripts -->
    <script type="text/javascript" src="<?php echo base_url()."assets/themes/metal/";?>js/jquery.min.js"></script> 
    <script type="text/javascript" src="<?php echo base_url()."assets/themes/metal/";?>js/bootstrap.min.js"></script> 
    <!-- Menu jQuery plugin -->
     
    <script type="text/javascript" src="<?php echo base_url()."assets/themes/metal/";?>js/hover-dropdown-menu.js"></script> 
    <!-- Menu jQuery Bootstrap Addon --> 
    <script type="text/javascript" src="<?php echo base_url()."assets/themes/metal/";?>js/jquery.hover-dropdown-menu-addon.js"></script> 
    <!-- Scroll Top Menu -->
     
    <script type="text/javascript" src="<?php echo base_url()."assets/themes/metal/";?>js/jquery.easing.1.3.js"></script> 
    <!-- Sticky Menu --> 
    <script type="text/javascript" src="<?php echo base_url()."assets/themes/metal/";?>js/jquery.sticky.js"></script> 
    <!-- Bootstrap Validation -->
     
    <script type="text/javascript" src="<?php echo base_url()."assets/themes/metal/";?>js/bootstrapValidator.min.js"></script> 
    <!-- Revolution Slider -->
     
    <script type="text/javascript" src="<?php echo base_url()."assets/themes/metal/";?>rs-plugin/js/jquery.themepunch.tools.min.js"></script> 
    <script type="text/javascript" src="<?php echo base_url()."assets/themes/metal/";?>rs-plugin/js/jquery.themepunch.revolution.min.js"></script> 
    <script type="text/javascript" src="<?php echo base_url()."assets/themes/metal/";?>js/revolution-custom.js"></script> 
    <!-- Portfolio Filter -->
     
    <script type="text/javascript" src="<?php echo base_url()."assets/themes/metal/";?>js/jquery.mixitup.min.js"></script> 
    <!-- Animations -->
     
    <script type="text/javascript" src="<?php echo base_url()."assets/themes/metal/";?>js/jquery.appear.js"></script> 
    <script type="text/javascript" src="<?php echo base_url()."assets/themes/metal/";?>js/effect.js"></script> 
    <!-- Owl Carousel Slider -->
     
    <script type="text/javascript" src="<?php echo base_url()."assets/themes/metal/";?>js/owl.carousel.min.js"></script> 
    <!-- Pretty Photo Popup -->
     
    <script type="text/javascript" src="<?php echo base_url()."assets/themes/metal/";?>js/jquery.prettyPhoto.js"></script> 
    <!-- Parallax BG -->
     
    <script type="text/javascript" src="<?php echo base_url()."assets/themes/metal/";?>js/jquery.parallax-1.1.3.js"></script> 
    <!-- Fun Factor / Counter -->
     
    <script type="text/javascript" src="<?php echo base_url()."assets/themes/metal/";?>js/jquery.countTo.js"></script> 
    <!-- Twitter Feed -->
     
    <script type="text/javascript" src="<?php echo base_url()."assets/themes/metal/";?>js/tweet/carousel.js"></script> 
    <script type="text/javascript" src="<?php echo base_url()."assets/themes/metal/";?>js/tweet/scripts.js"></script> 
    <script type="text/javascript" src="<?php echo base_url()."assets/themes/metal/";?>js/tweet/tweetie.min.js"></script> 
    <!-- Background Video -->
     
    <script type="text/javascript" src="<?php echo base_url()."assets/themes/metal/";?>js/jquery.mb.YTPlayer.js"></script> 
    <!-- Custom Js Code -->
     
    <script type="text/javascript" src="<?php echo base_url()."assets/themes/metal/";?>js/custom.js"></script> 
    <!-- Scripts --></body>
</html>
