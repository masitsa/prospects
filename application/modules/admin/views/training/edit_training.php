   
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
                            <label for="training_name">Category</label>
                            <select class="form-control" name="department_id">
                            	<?php
                                	if($active_departments->num_rows() > 0)
									{
										$dept_id = $training_row->department_id;
										foreach($active_departments->result() as $res)
										{
											$department_id = $res->department_id;
											$department_name = $res->department_name;
											if($dept_id ==$department_id)
											{
												echo '<option value="'.$department_id.'" selected="selected">'.$department_name.'</option>';
											}
											else
											{
												echo '<option value="'.$department_id.'">'.$department_name.'</option>';
											}
										}
									}
								?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="training_name">Training name</label>
                            <input type="text" class="form-control" name="training_name" placeholder="Training Name" value="<?php echo $training_row->training_name;?>">
                        </div>
                        <div class="form-group">
                            <label class="col-lg-4 control-label">Training Date Start</label>
                            
                            <div class="col-lg-8">
                                <div id="datetimepicker1" class="input-append">
                                    <input data-format="yyyy-MM-dd" class="form-control" type="text" name="start_date" placeholder="Post Date" value="<?php echo $training_row->start_date;?>">
                                    <span class="add-on">
                                        &nbsp;<i data-time-icon="icon-time" data-date-icon="icon-calendar">
                                        </i>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-lg-4 control-label">Training Date End</label>
                            
                            <div class="col-lg-8">
                                <div id="datetimepicker3" class="input-append">
                                    <input data-format="yyyy-MM-dd" class="form-control" type="text" name="end_date" placeholder="Post Date" value="<?php echo $training_row->end_date;?>">
                                    <span class="add-on">
                                        &nbsp;<i data-time-icon="icon-time" data-date-icon="icon-calendar">
                                        </i>
                                    </span>
                                </div>
                            </div>
                        </div>
					</div>
                	<div class="col-md-6">
                        <label class="control-label" for="image">Training Image</label>
                        <div class="fileinput fileinput-new" data-provides="fileinput">
                            <div class="fileinput-preview thumbnail" data-trigger="fileinput" style="width: 200px; height: 150px;">
                                <img src="<?php echo $training_location;?>" class="img-responsive"/>
                            </div>
                            <div>
                                <span class="btn btn-default btn-file"><span class="fileinput-new">Select image</span><span class="fileinput-exists">Change</span><input type="file" name="training_image"></span>
                                <a href="#" class="btn btn-default fileinput-exists" data-dismiss="fileinput">Remove</a>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="row">
                	<div class="col-md-12">
                        <div class="form-group">
                            <label class="col-md-2 control-label" for="training_description">Training description</label>
                            <div class="col-md-10" style="height:500px; margin-bottom:20px;">
                            	<textarea class="cleditor" name="training_description"><?php echo $training_row->training_description;?></textarea>
                            </div>
                        </div>
                    </div>
                </div>
				
				<div class="form-group center-align">
					<input type="submit" value="Edit Training" class="login_btn btn btn-success btn-lg">
				</div>
				<?php
					form_close();
				?>
		</div>