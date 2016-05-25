<section class="panel">
    <header class="panel-heading">
        <h2 class="panel-title">Add Gallery</h2>
         <a href="<?php echo site_url().'company-gallery';?>" class="btn btn-success btn-sm pull-right" style="margin-top:-25px;">Back to Gallery</a>
    </header>
    <div class="panel-body">
        <?php
        $success = $this->session->userdata('success_message');

        if(!empty($success))
        {
            echo '<div class="alert alert-success"> <strong>Success!</strong> '.$success.' </div>';
            $this->session->unset_userdata('success_message');
        }
        
        $error = $this->session->userdata('error_message');
        
        if(!empty($error))
        {
            echo '<div class="alert alert-danger"> <strong>Oh snap!</strong> '.$error.' </div>';
            $this->session->unset_userdata('error_message');
        }
        ?>   
        <!-- Jasny -->
        <link href="<?php echo base_url();?>assets/jasny/jasny-bootstrap.css" rel="stylesheet">		
        <script type="text/javascript" src="<?php echo base_url();?>assets/jasny/jasny-bootstrap.js"></script> 
          <div class="padd">
            <?php
				$error2 = validation_errors(); 
				if(!empty($error2)){?>
					<div class="row">
						<div class="col-md-6 col-md-offset-2">
							<div class="alert alert-danger">
								<strong>Error!</strong> <?php echo validation_errors(); ?>
							</div>
						</div>
					</div>
				<?php }
			
				if(isset($_SESSION['error'])){?>
					<div class="row">
						<div class="col-md-6 col-md-offset-2">
							<div class="alert alert-danger">
								<strong>Error!</strong> <?php echo $_SESSION['error']; $_SESSION['error'] = NULL;?>
							</div>
						</div>
					</div>
				<?php }?>
			
				<?php
				$attributes = array('role' => 'form', 'class'=>'form-horizontal');
		
				echo form_open_multipart($this->uri->uri_string(), $attributes);
				
				if(!empty($error))
				{
					?>
					<div class="alert alert-danger">
						<?php echo $error;?>
					</div>
					<?php
				}
				?>
                <div class="row">
                	<div class="col-md-4">
                        <div class="form-group">
                            <label class="col-lg-4 control-label" for="gallery_name">Title</label>
                            <div class="col-lg-8">
                            	<input type="text" class="form-control" name="gallery_name" placeholder="Title" value="<?php echo set_value("gallery_name");?>">
                            </div>
                        </div>
                        
					</div>
                	<div class="col-md-8">
                        <label class="col-lg-4 control-label" for="image">Gallery Image</label>
                        <div class="col-lg-8">
                        <?php echo form_upload(array( 'name'=>'gallery[]', 'multiple'=>true, 'class'=>'btn'));?>
                        </div>
                            <!--<div class="fileinput fileinput-new" data-provides="fileinput">
                                <div class="fileinput-preview thumbnail" data-trigger="fileinput" style="max-height: 400px;">
                                	<img src="<?php echo $gallery_location;?>" class="img-responsive"/>
                                </div>
                                    <div>
                                        <span class="btn btn-default btn-file"><span class="fileinput-new">Select image</span><span class="fileinput-exists">Change</span><input type="file" name="gallery_image"></span>
                                        <a href="#" class="btn btn-default fileinput-exists" data-dismiss="fileinput">Remove</a>
                                    </div>
                                </div>
                            </div>-->
                	</div>
                </div>
				
				<div class="form-group center-align">
					<input type="submit" value="Add Gallery Image" class="login_btn btn btn-success btn-sm">
				</div>
				<?php
					echo form_close();
				?>
		</div>
    </div>
</section>