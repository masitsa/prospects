<?php
	$contacts = $this->site_model->get_contacts();
	$banners = $this->banner_model->get_banners($this->session->userdata('customer_id'));
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
		
		if(!empty($facebook))
		{
			$facebook = '<li class="facebook"><a href="'.$facebook.'" target="_blank" title="Facebook">Facebook</a></li>';
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
	}
	
	if(!isset($website))
	{
		$website = '';
	}
?>

<!DOCTYPE html>
<html lang="en">
    <head>
    	<?php echo $this->load->view('site/includes/header', '', TRUE); ?>
    </head>
    <body>
    	<input type="hidden" id="base_url" value="<?php echo base_url();?>"/>
    	<input type="hidden" id="stripe_publishable_key" value="<?php echo $this->config->item("stripe_publishable_key");?>"/>
        <?php //echo $this->load->view('site/includes/top_navigation', $data, TRUE); ?>
        
        <header class="blue account-nav navbar-fixed">
            <nav class="blue top-nav">
                <div class="container">
                	<div class="nav-wrapper">
                    	<ul class="right">
                            <li>
                                <a class='dropdown-button' href='#' data-activates='banner_select'><i class="fa fa-caret-down"></i> Banners</a>
                                <!-- Dropdown Structure -->
                                <ul id='banner_select' class='dropdown-content'>
                                    <?php 
                                    if($banners->num_rows() > 0)
                                    {
                                        foreach($banners->result() as $res)
                                        {
                                            $banner_id = $res->smart_banner_id;
                                            $website_name = $res->smart_banner_website;
                                            
                                            echo '<li><a href="banner/'.$website_name.'">'.$website_name.'</a></li>';
                                        }
                                    }
                                    ?>
                                    <li class="divider"></li>
                                    <li><a class="modal-trigger" href="#new_banner"><i class="fa fa-plus"></i> New banner</a></li>
                                </ul>
                            </li>
                            <li><a href="<?php echo site_url().'sign-out';?>"><i class="fa fa-sign-out"></i> Sign out</a></li>
                        </ul>
                    </div>
                </div>
            </nav>
            
            <div class="container">
            	<a class="button-collapse top-nav full hide-on-large-only" data-activates="nav-mobile" href="#">
                	<i class="mdi-navigation-menu"></i>
                </a>
            </div>
            
            <ul class="side-nav fixed" id="nav-mobile" style="width: 240px;">
            	<li class="logo">
                    <a class="brand-logo" href="<?php echo site_url();?>" id="logo-container">
                    	<img src="<?php echo base_url().'assets/logo/'.$logo;?>" class="responsive-img logo" alt="<?php echo $company_name;?>">
                    </a>
                </li>
                <li class="bold"><a href="<?php echo site_url().'my-account';?>"><i class="fa fa-area-chart"></i> Summary</a></li>
                <li class="bold"><a href="<?php echo site_url().'settings';?>"><i class="fa fa-cog"></i> Settings</a></li>
                <li class="bold"><a href="<?php echo site_url().'subscribe';?>"><i class="fa fa-money"></i> Subscribe</a></li>
                <li class="bold"><a href="<?php echo site_url().'clicks';?>"><i class="fa fa-mouse-pointer"></i> Clicks</a></li>
                <li class="bold"><a href="<?php echo site_url().'banners';?>"><i class="fa fa-mobile"></i> Banners</a></li>
            </ul>
        </header>
        
        <main>
        	<div class="container">
                <div class="row">
                    <div class="col m12">
                        <?php echo $content;?>
                    </div>
                </div>
			</div>
		</main>

        <!-- New banner modal -->
        <div id="new_banner" class="modal modal-fixed-footer">
            <form class="form-horizontal sidebar_form" id="add_new_banner" method="POST">
                <div class="modal-content">
                    <h4 class="header center-align">Add new banner</h4>
                    <div id="add_banner_response"></div>
                    <div class="row">
                        <div class="input-field col s12">
                            <input type="text"  name="website">
                            <label for="Website">Website <span class="required">*</span></label>
                        </div>
                        <div class="input-field col s12">
                            <input type="text"  name="title">
                            <label for="title">Title <span class="required">*</span></label>
                        </div>
                        <div class="input-field col s12">
                            <input type="text"  name="author">
                            <label for="Author">Author <span class="required">*</span></label>
                        </div>
                        <div class="input-field col s12">
                            <input type="text"  name="price">
                            <label for="Price">Price <span class="required">*</span></label>
                        </div>
                        <div class="input-field col s12">
                            <input type="text"  name="icon_url">
                            <label for="Icon url">Icon url</label>
                        </div>
                        <div class="input-field col s12">
                            <input type="text"  name="url">
                            <label for="URL">App Store URL <span class="required">*</span></label>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="row">
                        <div class="col m6">
                            <button type="button" class="modal-action modal-close waves-effect waves-green btn red" data-dismiss="modal">Close</button>
                        </div>
                        <div class="col m6">
                            <button type="submit" class="waves-effect waves-green btn blue" id="add_banner">Add banner</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <!-- End new banner modal -->
        <script type="text/javascript" src="<?php echo base_url()."assets/themes/materialize/";?>js/materialize.min.js"></script>
        <script type="text/javascript" src="<?php echo base_url()."assets/themes/jscolor/";?>jscolor.js"></script>
        <?php
		$error = $this->session->userdata('error_message');
		$success = $this->session->userdata('success_message');
		
		//display error
		if(!empty($error))
		{
			?>
			<script type="text/javascript">
				$( document ).ready(function() {
					var response = '<span><?php echo $error;?></span>';
					Materialize.toast(response, 5000);
				});
			</script>
			<?php
			$this->session->unset_userdata('error_message');
		}
		
		//display error
		if(!empty($success))
		{
			?>
			<script type="text/javascript">
				$( document ).ready(function() {
					var response = '<span><?php echo $success;?></span>';
					Materialize.toast(response, 5000);
				});
			</script>
			<?php
			$this->session->unset_userdata('success_message');
		}
		?>
    </body>
</html>