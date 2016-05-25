
            <!--KF COURSES CATEGORIES WRAP START-->
			<section>
				<div class="container">
					<div class="row">
						<!-- HEADING 1 START-->
						<div class="col-md-4">
							<div class="kf_edu2_heading1">
								<h3>Our Trainings</h3>
							</div>
						</div>
						<!-- HEADING 1 END-->

						<!--EDU2 COURSES TAB WRAP START-->
						<div class="col-md-8">
							<div class="kf_edu2_tab_wrap">
								<!-- Nav tabs -->
									<ul class="nav nav-tabs" role="tablist">
									<li role="presentation" class="active"><a href="index.html#all" aria-controls="all" role="tab" data-toggle="tab">All</a></li>
								</ul>

							</div>
							
						</div>
						<div class="kf_edu2_tab_des">
							<div class="col-md-12">
								<!-- Tab panes -->
								<div class="tab-content">
									<div role="tabpanel" class="tab-pane fade in active" id="all">
										<!-- 1 Tab START  -->
										<div class="row margin-bottom">
                                            <?php if($trainings->num_rows() > 0){?>
												<?php foreach($trainings->result() as $res){?>
                                                    <?php 
                                                    $training_id = $res->training_id;
                                                    $training_name = $res->training_name;
                                                    $training_description = $res->training_description;
                                                    $start_date = $res->start_date;
													$training_image_name = $res->training_image_name;
                                                    $end_date = $res->end_date;
                                                    $start = date('jS F Y',strtotime($start_date));
                                                    $end = date('jS F Y',strtotime($end_date));
                                                    ?>
                                                    <!-- EDU COURSES WRAP START -->			
                                                    <div class="col-md-4">
                                                        <div class="edu2_cur_wrap">
                                                            <figure>
                                                                <img src="<?php echo base_url().'assets/training/'.$training_image_name;?>" alt=""/>
                                                                <figcaption><a href="courses-detail.html">See More</a></figcaption>
                                                            </figure>
                                                            <div class="edu2_cur_des">
                                                                <span>Kes 20</span>
                                                                <h5><a href="#"><?php echo $training_name;?></a></h5>
                                                                <strong>
                                                                    <span><?php echo $start;?></span>
                                                                    <small><?php echo $end;?></small>
                                                                </strong>
                                                                <p><?php echo $training_name;?></p>
                                                            </div>
                                                            <div class="edu2_cur_des_ft">
                                                                <?php echo $training_description;?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <!-- EDU COURSES WRAP END -->
                                                <?php }?>
                                            <?php }?>
                                           
										</div>
                                        <div class="view-all">
                                        	<a class="btn-3" href="<?php echo site_url().'trainings';?>">View All Trainings</a>
                                        </div>
									</div>
									
								</div>

							</div>
						</div>
						<!--EDU2 COURSES TAB WRAP END-->
					</div>
				</div>
			</section>
			<!--KF COURSES CATEGORIES WRAP END-->


