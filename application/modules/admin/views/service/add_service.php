<section class="panel">
    <header class="panel-heading">
        <h2 class="panel-title">Add Service</h2>
         <a href="<?php echo site_url().'administration/all-services';?>" class="btn btn-success btn-sm pull-right" style="margin-top:-25px;">Back to Service</a>
    </header>
	<div class="panel-body">
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
				$attributes = array('role' => 'form');
		
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
                	<div class="col-md-6">
                      
                        <div class="form-group">
                            <label for="service_name">Service name</label>
                            <input type="text" class="form-control" name="service_name" placeholder="Service Name" value="<?php echo set_value("service_name");?>">
                        </div>
					</div>
                	<div class="col-md-6">
                        <label class="control-label" for="image">Service image</label>
                        <div class="fileinput fileinput-new" data-provides="fileinput">
                            <div class="fileinput-preview thumbnail" data-trigger="fileinput" style="width: 200px; height: 150px;">
                                <img src="<?php echo $service_location;?>" class="img-responsive"/>
                            </div>
                            <div>
                                <span class="btn btn-default btn-file"><span class="fileinput-new">Select image</span><span class="fileinput-exists">Change</span><input type="file" name="service_image"></span>
                                <a href="#" class="btn btn-default fileinput-exists" data-dismiss="fileinput">Remove</a>
                            </div>
                        </div>
                	</div>
                </div>
                <div class="row">
                	<div class="col-md-12">
                	</div>
                </div>
                <div class="row">
                	<div class="col-md-12">
                        <div class="form-group">
                            <label class="col-md-2 control-label" for="service_description">Service description</label>
                            <div class="col-md-10" style=" margin-bottom:20px;">
                            	<textarea class="cleditor" name="service_description"><?php echo set_value("service_description");?></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                	<div class="col-md-12">
                        <div class="form-group">
		                	 <label class="col-lg-4 control-label" for="image">Gallery Image</label>
		                    <div class="col-lg-8">
		                    <?php echo form_upload(array( 'name'=>'gallery[]', 'multiple'=>true, 'class'=>'btn'));?>
		                    </div>
                		</div>
                	</div>
                </div>
				
				<div class="form-group center-align">
					<input type="submit" value="Add Service" class="login_btn btn btn-success btn-sm">
				</div>
				<?php
					echo form_close();
				?>
		</div>
	</div>
</section>