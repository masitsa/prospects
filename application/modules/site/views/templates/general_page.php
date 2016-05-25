<!DOCTYPE html>
<!--[if IE 9]>
<html class="ie ie9" lang="en-US">
<![endif]-->
<html lang="en-US">
    <!-- head -->
    <?php echo $this->load->view('site/includes/header', '', TRUE); ?>
    <!-- end of head -->
    
    <body>
    	<div class="sidebar-menu-container" id="sidebar-menu-container">
        
            <!-- Mini -->
            <div class="sidebar-menu-push">
    
                <div class="sidebar-menu-overlay"></div>
    
                <div class="sidebar-menu-inner">
                    <?php echo $this->load->view('site/includes/navigation', '', TRUE); ?>
                </div>
                
                <?php echo $content;?>
                
                <?php echo $this->load->view('site/includes/footer', '', TRUE); ?>
            </div>
            <!-- End Mini -->
        
            <!-- Maxi -->
            <nav class="sidebar-menu slide-from-left">
            	<div class="nano">
					<div class="content">
                    	<?php echo $this->load->view('site/includes/mini_navigation', '', TRUE); ?>
                        
                        <?php echo $this->load->view('site/includes/mini_footer', '', TRUE); ?>
                    </div>
                </div>
            </nav>
            <!-- End Maxi -->
		</div>
        
        <script type="text/javascript" src="<?php echo base_url()."assets/themes/anchro/";?>assets/js/bootstrap.min.js"></script>
        <!-- SLIDER REVOLUTION 4.x SCRIPTS  -->
        <script src="<?php echo base_url()."assets/themes/anchro/";?>assets/rs-plugin/js/jquery.themepunch.tools.min.js"></script>
        <script src="<?php echo base_url()."assets/themes/anchro/";?>assets/rs-plugin/js/jquery.themepunch.revolution.min.js"></script>
    
        <script type="text/javascript" src="<?php echo base_url()."assets/themes/anchro/";?>assets/js/plugins.js"></script>
        <script type="text/javascript" src="<?php echo base_url()."assets/themes/anchro/";?>assets/js/custom.js"></script>
	</body>
</html>
