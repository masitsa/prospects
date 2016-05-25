<div class="panel panel-default">
    <div class="panel-heading"><?php echo $title;?></div>
    <div class="panel-body">
<a href="<?php echo site_url().'administration/add-training';?>" class="btn btn-success pull-right">Add Training</a>
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
		
		//if users exist display them
		if ($query->num_rows() > 0)
		{
				?>
                <table class="table table-condensed table-striped table-hover">
                    <tr>
                    	<th>#</th>
                    	<!--<th>Image</th>
                    	<th>Poster</th>-->
                    	<th>Start Date</th>
                    	<th>End Date</th>
                    	<th>Registered Attendees</th>
                    	<th colspan="3">Actions</th>
                    </tr>
                <?php
				$count = $page;
				foreach($query->result() as $cat)
				{
					$training_id = $cat->training_id;
					$training_status = $cat->training_status;
					$training_date = $cat->training_date;
					$start_date = $cat->start_date;
					$end_date = $cat->end_date;
					$created = $cat->created;
					$training_image_name = 'thumbnail_'.$cat->training_image_name;
					$count++;
					$trainees = $this->training_model->get_attendees($training_id);
					$registered_attendees = $trainees->num_rows();
					
					$v_data['training_id'] = $training_id;
					$v_data['trainees'] = $trainees;
					
					?>
                    <tr>
                    	<td><?php echo $count?></td>
                    	<!--<td>
                        	<img src="<?php echo $training_location.$training_image_name;?>" width="100" class="img-responsive img-thumbnail">
                        </td>-->
                    	<td><?php echo date('jS M Y',strtotime($start_date));?></td>
                    	<td><?php echo date('jS M Y',strtotime($end_date));?></td>
                    	<td><?php echo $registered_attendees;?></td>
                        <td><a  class="btn btn-sm btn-primary" id="open_training<?php echo $training_id;?>" onclick="get_trainees(<?php echo $training_id;?>);">Trainees</a>
							<a  class="btn btn-sm btn-warning" id="close_training<?php echo $training_id;?>" style="display:none;" onclick="close_trainees(<?php echo $training_id;?>);">Close Trainees</a></td>
                    	<td>
                        	<a href="<?php echo site_url()."administration/edit-training/".$training_id.'/'.$page;?>"  class="btn btn-success btn-sm" title="Edit">
                            	<i class="fa fa-pencil-square-o"></i> Edit
                            </a>
                        </td>
                        <td>
                        	<a href="<?php echo site_url()."administration/delete-training/".$training_id.'/'.$page;?>"  class="btn btn-danger btn-sm" title="Delete" onclick="return confirm('Do you really want to delete this training?');">
                            	 <i class="fa fa-trash-o"></i> Delete
                            </a>
                        </td>
                        <!--<td>
                            <?php
								if($training_status == 1){
									?>
                                        <a href="<?php echo site_url()."administration/deactivate-training/".$training_id.'/'.$page;?>" class="i_size" title="Deactivate" onclick="return confirm('Do you really want to deactivate this training?');" class="btn btn-warning btn-sm">
                                        	<i class="fa fa-thumbs-o-down"></i> Deactivate
                                        </a>
                                    <?php
								}
								else{
									?>
                                        <a href="<?php echo site_url()."administration/activate-training/".$training_id.'/'.$page;?>" class="i_size" title="Activate" onclick="return confirm('Do you really want to activate this training?');" class="btn btn-info btn-sm">
                                        	<i class="fa fa-thumbs-o-up"></i> Activate
                                        </a>
                                    <?php
								}
							?>
                        </td>-->
                    </tr>
                    
                    <tr id="visit_trail<?php echo $training_id;?>" style="display:none;">
                        <td colspan="14"><?php echo $this->load->view("training/get_trainees", $v_data, TRUE);?></td>
                    </tr>
                    <?php
				}
				?>
                </table>
                <?php
			}
			
			else{
				echo "There are no trainings to display :-(";
			}
		?>
    </div>
    
</div>

  <script type="text/javascript">

	function get_trainees(training_id){

		var myTarget2 = document.getElementById("visit_trail"+training_id);
		var button = document.getElementById("open_training"+training_id);
		var button2 = document.getElementById("close_training"+training_id);

		myTarget2.style.display = '';
		button.style.display = 'none';
		button2.style.display = '';
	}
	function close_trainees(training_id){

		var myTarget2 = document.getElementById("visit_trail"+training_id);
		var button = document.getElementById("open_training"+training_id);
		var button2 = document.getElementById("close_training"+training_id);

		myTarget2.style.display = 'none';
		button.style.display = '';
		button2.style.display = 'none';
	}
  </script>