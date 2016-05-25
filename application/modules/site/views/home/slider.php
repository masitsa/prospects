
				<div class="slider">
					<div class="fullwidthbanner-container">
						<div class="fullwidthbanner">
							<ul>
                            	 <?php
								if($slides->num_rows() > 0)
								{
									foreach($slides->result() as $slide)
									{
										$slide_name = $slide->slideshow_name;
										$description = $slide->slideshow_description;
										$slide_image = $slide->slideshow_image_name;
										$slideshow_link = $slide->slideshow_link;
										$slideshow_button_text = $slide->slideshow_button_text;
										$description = $this->site_model->limit_text($description, 8);
										
										?>
                                        <li class="first-slide" data-transition="fade" data-slotamount="10" data-masterspeed="300">
                                            <img src="<?php echo base_url();?>assets/slideshow/<?php echo $slide_image;?>" data-fullwidthcentering="on" alt="slide">
                                            <div class="tp-caption first-line lfr tp-resizeme start" data-x="center" data-hoffset="0" data-y="center" data-speed="2500" data-start="500" data-easing="Power4.easeOut" data-splitin="none" data-splitout="none" data-elementdelay="0" data-endelementdelay="0"><span>Prospects Communications</span><h1><?php echo $slide_name;?></h1><div class="slider-button"><a href="<?php echo site_url().$slideshow_link;?>"><?php echo $slideshow_button_text;?></a></div></div>
                                        </li>
									<?php
									}
								}
								?>
							</ul>
						</div>
					</div>
				</div>