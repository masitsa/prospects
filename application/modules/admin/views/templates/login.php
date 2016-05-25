<?php 
	
	$contacts = $this->site_model->get_contacts();
	
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
	$data['contacts'] = $contacts;
?>
<!doctype html>
<html class="fixed">
	<head>
        <?php echo $this->load->view('includes/header', '', TRUE); ?>
    </head>

	<body>
    	<!--[if lt IE 7]>
            <p class="chromeframe">You are using an outdated browser. <a href="http://browsehappy.com/">Upgrade your browser today</a> or <a href="http://www.google.com/chromeframe/?redirect=true">install Google Chrome Frame</a> to better experience this site.</p>
        <![endif]-->
    	<section class="body-sign">
            <div class="center-sign">
				<a href="<?php echo site_url().'login-admin';?>" class="logo pull-left">
					<img src="<?php echo base_url().'assets/logo/'.$logo;?>" height="35" alt="<?php echo $company_name;?>" class="img-responsive" />
				</a>

				<div class="panel panel-sign">
					<div class="panel-title-sign mt-xl text-right">
						<h2 class="title text-uppercase text-weight-bold m-none"><i class="fa fa-user mr-xs"></i> Sign In</h2>
					</div>
					<div class="panel-body">
						<?php
							$login_error = $this->session->userdata('login_error');
							$this->session->unset_userdata('login_error');
							
							if(!empty($login_error))
							{
								echo '<div class="alert alert-danger">'.$login_error.'</div>';
							}
							
							$validation_errors = validation_errors();
							
							if(!empty($validation_errors ))
							{
								echo '<div class="alert alert-danger">'.$validation_errors .'</div>';
							}
						?>
							<form action="<?php echo site_url().'admin/auth/login_admin';?>" method="post">
                        	<?php
								//case of an input error
								if(!empty($user_email_error))
								{
									?>
                                    <div class="form-group mb-lg has-error">
                                        <label>Email</label>
                                        <div class="input-group input-group-icon">
                                            <input name="user_email" type="email" class="form-control input-lg" value="<?php echo $user_email;?>" />
                                            <label for="user_email" class="error"><?php echo $user_email_error;?></label>
                                            <span class="input-group-addon">
                                                <span class="icon icon-lg">
                                                    <i class="fa fa-envelope"></i>
                                                </span>
                                            </span>
                                        </div>
                                    </div>
									<?php
								}
								
								else
								{
									?>
                                    <div class="form-group mb-lg">
                                        <label>Email</label>
                                        <div class="input-group input-group-icon">
                                            <input name="user_email" type="email" class="form-control input-lg" value="<?php echo $user_email;?>"/>
                                            <span class="input-group-addon">
                                                <span class="icon icon-lg">
                                                    <i class="fa fa-envelope"></i>
                                                </span>
                                            </span>
                                        </div>
                                    </div>
									<?php
								}
							?>
                            
                            <?php
								//case of an input error
								if(!empty($user_password_error))
								{
									?>
                                    <div class="form-group mb-lg has-error">
                                        <div class="clearfix">
                                            <label class="pull-left">Password</label>
                                            <a href="#" class="pull-right"> Lost Password?</a>
                                        </div>
                                        <div class="input-group input-group-icon">
                                            <input name="user_password" type="password" class="form-control input-lg" value="<?php echo $user_password;?>" />
                                            <label for="user_email" class="error"><?php echo $user_email_error;?></label>
                                            <span class="input-group-addon">
                                                <span class="icon icon-lg">
                                                    <i class="fa fa-lock"></i>
                                                </span>
                                            </span>
                                        </div>
                                    </div>
									<?php
								}
								
								else
								{
									?>
									<div class="form-group mb-lg">
                                        <div class="input-group input-group-icon">
                                            <input name="user_password" type="password" class="form-control input-lg" value="<?php echo $user_password;?>" />
                                            <span class="input-group-addon">
                                                <span class="icon icon-lg">
                                                    <i class="fa fa-lock"></i>
                                                </span>
                                            </span>
                                        </div>
                                    </div>
									<?php
								}
							?>
							

							<div class="row">
								<div class="col-sm-8">
									<div class="checkbox-custom checkbox-default">
										<input id="RememberMe" name="rememberme" type="checkbox"/>
										<label for="RememberMe">Remember Me</label>
									</div>
								</div>
								<div class="col-sm-4 text-right">
									<button type="submit" class="btn btn-primary hidden-xs">Sign In</button>
									<button type="submit" class="btn btn-primary btn-block btn-lg visible-xs mt-lg">Sign In</button>
								</div>
							</div>

						</form>
					</div>
				</div>

				<p class="text-center text-muted mt-md mb-md">&copy; Copyright <?php echo date('Y');?>. All Rights Reserved.</p>
			</div>
		</section>
		<!-- end: page -->
        		
		<!-- Vendor -->
		<script src="<?php echo base_url()."assets/themes/porto-admin/1.4.1/";?>assets/vendor/jquery/jquery.js"></script>		
		<script src="<?php echo base_url()."assets/themes/porto-admin/1.4.1/";?>assets/vendor/jquery-browser-mobile/jquery.browser.mobile.js"></script>		
		<script src="<?php echo base_url()."assets/themes/porto-admin/1.4.1/";?>assets/vendor/jquery-cookie/jquery.cookie.js"></script>		
		<script src="<?php echo base_url()."assets/themes/porto-admin/1.4.1/";?>assets/vendor/bootstrap/js/bootstrap.js"></script>		
		<script src="<?php echo base_url()."assets/themes/porto-admin/1.4.1/";?>assets/vendor/nanoscroller/nanoscroller.js"></script>		
		<script src="<?php echo base_url()."assets/themes/porto-admin/1.4.1/";?>assets/vendor/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>		
		<script src="<?php echo base_url()."assets/themes/porto-admin/1.4.1/";?>assets/vendor/magnific-popup/magnific-popup.js"></script>		
		<script src="<?php echo base_url()."assets/themes/porto-admin/1.4.1/";?>assets/vendor/jquery-placeholder/jquery.placeholder.js"></script>
		
		<!-- Theme Base, Components and Settings -->
		<script src="<?php echo base_url()."assets/themes/porto-admin/1.4.1/";?>assets/javascripts/theme.js"></script>
		
		<!-- Theme Custom -->
		<script src="<?php echo base_url()."assets/themes/porto-admin/1.4.1/";?>assets/javascripts/theme.custom.js"></script>
		
		<!-- Theme Initialization Files -->
		<script src="<?php echo base_url()."assets/themes/porto-admin/1.4.1/";?>assets/javascripts/theme.init.js"></script>
	</body>
</html>