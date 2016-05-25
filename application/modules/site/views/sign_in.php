<div class="container">
	<!-- Show messages -->
    <div class="row">
    	<div class="col-md-12 center-align">
			<?php 
                $error = $this->session->userdata('error_message');
                $success = $this->session->userdata('success_message');
                
                if(!empty($error))
                {
                    echo '<div class="alert alert-danger">'.$error.'</div>';
                    $this->session->unset_userdata('error_message');
                }
                
                if(!empty($success))
                {
                    echo '<div class="alert alert-success">'.$success.'</div>';
                    $this->session->unset_userdata('success_message');
                }
            ?>
        </div>
    </div>
    <!-- End messages -->
    
	<div class="row">
    	<div class="col-md-4 col-md-offset-4">
        	<button type="button" class="btn red" id="google_signin">
                <span class="icon"><i class="fa fa-google"></i></span>
                Sign in
            </button>
        </div>
    </div>
</div>

<div class="row">
	<div class="col-md-12">
    	<div id="signin_response"></div>
    </div>
</div>

<script src="https://apis.google.com/js/api:client.js"></script>
<script src="<?php echo base_url()."assets/themes/custom/";?>js/google_signin.js"></script>