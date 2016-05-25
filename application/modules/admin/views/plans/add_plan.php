          
          <section class="panel">
                <header class="panel-heading">
                    <h2 class="panel-title"><?php echo $title;?></h2>
                </header>
                <div class="panel-body">
                	<div class="row" style="margin-bottom:20px;">
                        <div class="col-lg-12">
                            <a href="<?php echo site_url();?>admin/plans" class="btn btn-info pull-right">Back to plans</a>
                        </div>
                    </div>
                        
                    <!-- Adding Errors -->
                    <?php
                    if(isset($error)){
                        echo '<div class="alert alert-danger"> Oh snap! Change a few things up and try submitting again. </div>';
                    }
                    
                    $validation_errors = validation_errors();
                    
                    if(!empty($validation_errors))
                    {
                        echo '<div class="alert alert-danger"> Oh snap! '.$validation_errors.' </div>';
                    }
                    ?>
                    
                    <?php echo form_open($this->uri->uri_string(), array("class" => "form-horizontal", "role" => "form"));?>
                    <!-- Plan Name -->
                    <div class="form-group">
                        <label class="col-lg-4 control-label">Plan Name</label>
                        <div class="col-lg-6">
                            <input type="text" class="form-control" name="plan_name" placeholder="Plan Name" value="<?php echo set_value('plan_name');?>" >
                        </div>
                    </div>
                    
                    <!-- Plan Position -->
                    <div class="form-group">
                        <label class="col-lg-4 control-label">Amount</label>
                        <div class="col-lg-6">
                            <input type="number" class="form-control" name="plan_amount" placeholder="Amount" value="<?php echo set_value('plan_amount');?>" >
                        </div>
                    </div>
                    
                    <!-- Plan clicks -->
                    <div class="form-group">
                        <label class="col-lg-4 control-label">Maximum clicks</label>
                        <div class="col-lg-6">
                            <input type="number" class="form-control" name="maximum_clicks" placeholder="Maximum clicks" value="<?php echo set_value('maximum_clicks');?>" >
                        </div>
                    </div>
                    <!-- Plan Icon -->
                    <div class="form-group">
                        <label class="col-lg-4 control-label">Stripe ID</label>
                        <div class="col-lg-6">
                            <input type="text" class="form-control" name="stripe_plan" placeholder="Stripe ID" value="<?php echo set_value('stripe_plan');?>" >
                        </div>
                    </div>
                    <!-- Activate checkbox -->
                    <div class="form-group">
                        <label class="col-lg-4 control-label">Activate Plan?</label>
                        <div class="col-lg-6">
                            <div class="radio">
                                <label>
                                    <input id="optionsRadios1" type="radio" checked value="1" name="plan_status">
                                    Yes
                                </label>
                            </div>
                            <div class="radio">
                                <label>
                                    <input id="optionsRadios2" type="radio" value="0" name="plan_status">
                                    No
                                </label>
                            </div>
                        </div>
                    </div>
                    <!-- Plan Icon -->
                    <div class="form-group">
                        <label class="col-lg-4 control-label">Plan Description</label>
                        <div class="col-lg-8">
                            <textarea class="form-control" name="plan_description"><?php echo set_value('plan_description');?></textarea>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-lg-6 col-lg-offset-4">
                            <div class="form-actions center-align">
                                <button class="submit btn btn-primary" type="submit">
                                    Add plan
                                </button>
                            </div>
                        </div>
                    </div>
                    
                    <br />
                    <?php echo form_close();?>
                </div>
            </section>