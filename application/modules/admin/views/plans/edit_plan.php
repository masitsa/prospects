
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
			
			//the plan details
			$plan_id = $plan[0]->plan_id;
			$plan_name = $plan[0]->plan_name;
			$plan_description = $plan[0]->plan_description;
			$plan_status = $plan[0]->plan_status;
			$plan_amount = $plan[0]->plan_amount;
			$stripe_plan = $plan[0]->stripe_plan;
			$maximum_clicks = $plan[0]->maximum_clicks;
            
            $validation_errors = validation_errors();
            
            if(!empty($validation_errors))
            {
				$plan_name = set_value('plan_name');
				$plan_description = set_value('plan_description');
				$plan_status = set_value('plan_status');
				$plan_amount = set_value('plan_amount');
				$stripe_plan = set_value('stripe_plan');
				$maximum_clicks = set_value('maximum_clicks');
				
                echo '<div class="alert alert-danger"> Oh snap! '.$validation_errors.' </div>';
            }
			
            ?>
            
            <?php echo form_open($this->uri->uri_string(), array("class" => "form-horizontal", "role" => "form"));?>
            <!-- Plan Name -->
            <div class="form-group">
                <label class="col-lg-4 control-label">Plan Name</label>
                <div class="col-lg-4">
                	<input type="text" class="form-control" name="plan_name" placeholder="Plan Name" value="<?php echo $plan_name;?>" >
                </div>
            </div>
                    
            <!-- Plan Position -->
            <div class="form-group">
                <label class="col-lg-4 control-label">Amount</label>
                <div class="col-lg-6">
                    <input type="number" class="form-control" name="plan_amount" placeholder="Amount" value="<?php echo $plan_amount;?>" >
                </div>
            </div>
                    
            <!-- Plan clicks -->
            <div class="form-group">
                <label class="col-lg-4 control-label">Maximum clicks</label>
                <div class="col-lg-6">
                    <input type="number" class="form-control" name="maximum_clicks" placeholder="Maximum clicks" value="<?php echo $maximum_clicks;?>" >
                </div>
            </div>
            <!-- Plan Icon -->
            <div class="form-group">
                <label class="col-lg-4 control-label">Stripe ID</label>
                <div class="col-lg-6">
                    <input type="text" class="form-control" name="stripe_plan" placeholder="Stripe ID" value="<?php echo $stripe_plan;?>" >
                </div>
            </div>
            <!-- Activate checkbox -->
            <div class="form-group">
                <label class="col-lg-4 control-label">Activate Plan?</label>
                <div class="col-lg-4">
                    <div class="radio">
                        <label>
                        	<?php
                            if($plan_status == 1){echo '<input id="optionsRadios1" type="radio" checked value="1" name="plan_status">';}
							else{echo '<input id="optionsRadios1" type="radio" value="1" name="plan_status">';}
							?>
                            Yes
                        </label>
                    </div>
                    <div class="radio">
                        <label>
                        	<?php
                            if($plan_status == 0){echo '<input id="optionsRadios1" type="radio" checked value="0" name="plan_status">';}
							else{echo '<input id="optionsRadios1" type="radio" value="0" name="plan_status">';}
							?>
                            No
                        </label>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label class="col-lg-4 control-label">Plan Description</label>
                <div class="col-lg-8">
                    <textarea class="form-control" name="plan_description"><?php echo $plan_description;?></textarea>
                </div>
            </div>
            <div class="form-actions center-align">
                <button class="submit btn btn-primary" type="submit">
                    Edit plan
                </button>
            </div>
            <br />
            <?php echo form_close();?>
                </div>
            </section>