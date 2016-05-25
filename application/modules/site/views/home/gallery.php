			<!--GALLERY SECTION START-->
			<section class="kode-gallery-section">
				<!-- HEADING 2 START-->
                <div class="col-md-12">
                    <div class="kf_edu2_heading2">
                        <h3>Our Gallery</h3>
						<p>Past events</p>
                    </div>
                </div>
                <!-- HEADING 2 END-->
                <!-- EDU2 GALLERY WRAP START-->
                <div class="edu2_gallery_wrap gallery">
                   
                    <!-- EDU2 GALLERY DES START-->
                    <div class="gallery3">
                    	<?php
                        if($gallery_images->num_rows() > 0)
                        {
                            foreach($gallery_images->result() as $res)
                            {
                                $gallery_name = $res->gallery_name;
                                $gallery_image_name = $res->gallery_image_name;
								$service_name = '';
                                ?>
                                <div class="filterable-item all 2 1 9 col-md-3 col-sm-4 col-xs-12 no_padding">
                                    <div class="edu2_gallery_des">
                                        <figure>
                                            <img src="<?php echo $gallery_location.$gallery_image_name;?>" alt="<?php echo $gallery_name;?>">
                                            <figcaption>
                                                <a data-rel="prettyPhoto[gallery2]" href="<?php echo $gallery_location.$gallery_image_name;?>"><i class="fa fa-eye"></i></a>
                                                <h5><?php echo $gallery_name;?></h5>
                                            </figcaption>
                                        </figure>
                                    </div>	
                                </div>
                                <?php
                            }
                        }
                    ?>
                    </div>
                	<!-- EDU2 GALLERY WRAP END-->
                </div>
                <div class="loadmore">
						<a href="<?php echo site_url().'gallery';?>" class="btn-3">Load More</a>
					</div>
			</section>
			<!--GALLERY SECTION END-->
