<!DOCTYPE html>
<html lang="en">
    <?php echo $this->load->view('site/includes/header', '', TRUE); ?>
    <body>
    <div id="page">
        <!-- Page Loader -->
        <div id="pageloader">
            <div class="loader-item fa fa-spin text-color"></div>
        </div>
        <div class="transparent-header">
        <?php echo $this->load->view('site/includes/navigation', '', TRUE); ?>
        </div>

        <?php echo $content;?>
        <!-- request -->
        <?php echo $this->load->view('site/includes/footer', '', TRUE); ?>
        <!-- footer -->
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
    <!-- Pretty Photo Popup -->
     
    <script type="text/javascript" src="<?php echo base_url()."assets/themes/metal/";?>js/jquery.prettyPhoto.js"></script> 
    <!-- Animations -->
     
    <script type="text/javascript" src="<?php echo base_url()."assets/themes/metal/";?>js/jquery.appear.js"></script> 
    <script type="text/javascript" src="<?php echo base_url()."assets/themes/metal/";?>js/effect.js"></script> 
    <!-- Parallax BG -->
     
    <script type="text/javascript" src="<?php echo base_url()."assets/themes/metal/";?>js/jquery.parallax-1.1.3.js"></script> 
    <!-- Fun Factor / Counter -->
     
    <script type="text/javascript" src="<?php echo base_url()."assets/themes/metal/";?>js/jquery.countTo.js"></script> 
    <!-- Isoptope -->
     
    <script type="text/javascript" src="<?php echo base_url()."assets/themes/metal/";?>js/isotope.min.js"></script> 
    <!-- Custom Js Code -->
     
    <script type="text/javascript" src="<?php echo base_url()."assets/themes/metal/";?>js/custom.js"></script> 
     
    <!-- Scripts --></body>
</html>
