
          <section class="panel">
                <header class="panel-heading">
            
                    <h2 class="panel-title"><?php echo $title;?></h2>
                </header>
                <div class="panel-body">
                	<div class="row" style="margin-bottom:20px;">
                        <div class="col-lg-12">
                            <a href="<?php echo site_url();?>admin/emails" class="btn btn-info pull-right">Back to emails</a>
                        </div>
                    </div>
                <!-- Adding Errors -->
            <?php
            if(isset($error)){
                echo '<div class="alert alert-danger"> Oh snap! Change a few things up and try submitting again. </div>';
            }
			
			//the email details
			$email_id = $email[0]->email_id;
			$email_category_id = $email[0]->email_category_id;
			$email_content = $email[0]->email_content;
			$email_status = $email[0]->email_status;
            
            $validation_errors = validation_errors();
            
            if(!empty($validation_errors))
            {
				$email_id = set_value('email_id');
				$email_category_id = set_value('email_category_id');
				$email_content = set_value('email_content');
				$email_status = set_value('email_status');
				
                echo '<div class="alert alert-danger"> Oh snap! '.$validation_errors.' </div>';
            }
			
            ?>
            
            <?php echo form_open($this->uri->uri_string(), array("class" => "form-horizontal", "role" => "form"));?>
            <div class="row">
                <div class="col-md-6">
                    <!-- Category Parent -->
                    <div class="form-group">
                        <label class="col-lg-4 control-label">Email category</label>
                        <div class="col-lg-6">
                            <select name="email_category_id" class="form-control">
                                <?php
                                echo '<option value="">-- Select email --</option>';
                                if($email_categories->num_rows() > 0)
                                {
                                    $result = $email_categories->result();
                                    
                                    foreach($result as $res)
                                    {
                                        if($res->email_category_id == $email_category_id)
                                        {
                                            echo '<option value="'.$res->email_category_id.'" selected>'.$res->email_category_name.'</option>';
                                        }
                                        else
                                        {
                                            echo '<option value="'.$res->email_category_id.'">'.$res->email_category_name.'</option>';
                                        }
                                    }
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-6">
                	<div class="form-group">
                        <label class="col-lg-4 control-label">Activate email?</label>
                        <div class="col-lg-4">
                            <div class="radio">
                                <label>
                                    <?php
                                    if($email_status == 1){echo '<input id="optionsRadios1" type="radio" checked value="1" name="email_status">';}
                                    else{echo '<input id="optionsRadios1" type="radio" value="1" name="email_status">';}
                                    ?>
                                    Yes
                                </label>
                            </div>
                            <div class="radio">
                                <label>
                                    <?php
                                    if($email_status == 0){echo '<input id="optionsRadios1" type="radio" checked value="0" name="email_status">';}
                                    else{echo '<input id="optionsRadios1" type="radio" value="0" name="email_status">';}
                                    ?>
                                    No
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
                    
            <div class="row">
                <div class="col-md-12">
                    <!-- Email content -->
                    <div class="form-group">
                        <label class="col-lg-2 control-label">Email content</label>
                        <div class="col-lg-10">
                            <textarea class="form-control cleditor" name="email_content" placeholder="Email content"><?php echo $email_content;?></textarea>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="form-actions center-align">
                <button class="submit btn btn-primary" type="submit">
                    Edit Email
                </button>
            </div>
            <br />
            <?php echo form_close();?>
                </div>
            </section>