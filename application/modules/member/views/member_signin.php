<div class="container">
	
    <div class="row">
    
    	<div class="col-md-2 col-md-offset-5">
        	<img src="<?php echo base_url().'assets/logo/iod.jpg'?>" class="img-responsive" />
        </div>
    
        <div class="col-xs-12 col-sm-8 col-md-6 col-sm-offset-2 col-md-offset-3">
        	<?php
				$validation_errors = validation_errors();
				if(!empty($validation_errors))
				{
					echo '<div class="alert alert-danger">'.$validation_errors.'</div>';
				}
				
				$login_error = $this->session->userdata('login_error');
				if(!empty($login_error))
				{
					$this->session->unset_userdata('login_error');
					echo '<div class="alert alert-danger">'.$login_error.'</div>';
				}
			?>
            <form action="<?php echo $this->uri->uri_string();?>" method="post" role="form">
                <h2>Member <small>Login</small></h2>
                <hr class="colorgraph">
                
                <div class="form-group">
                    <input type="email" name="member_email" id="email" class="form-control input-lg" placeholder="Email Address" tabindex="4" value="<?php echo $member_email;?>">
                </div>
                <div class="form-group">
                    <input type="password" name="member_password" id="password" class="form-control input-lg" placeholder="Password" tabindex="5" value="<?php echo $member_password;?>">
                </div>
                
                <hr class="colorgraph">
                <div class="row">
                    <div class="col-xs-12 col-md-6"><a href="<?php echo site_url().'register';?>" class="btn btn-default btn-block btn-lg">Register</a></div>
                    <div class="col-xs-12 col-md-6"><input type="submit" value="Login" class="btn btn-primary btn-block btn-lg" tabindex="7"></div>
                </div>
            </form>
        </div>
    </div>
</div>
