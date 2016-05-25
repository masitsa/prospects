<div class="page-header page-title-left page-title-pattern">
	<div class="image-bg content-in fixed" data-background="<?php echo base_url()?>assets/img/top_page2.jpg"><div class="overlay-dark"></div></div>
    <div class="container" id="breadcrum-modification" >
        <div class="row">
            <div class="col-md-12">
                <h1 class="title white"><?php echo $title?></h1>
                <h5></h5>
                <ul class="breadcrumb">
                    <?php echo $this->site_model->get_breadcrumbs();?>
                </ul>
            </div>
        </div>
    </div>
</div>
<!-- page-header -->
<section id="services" class="page-section service-section">
    <div class="container">
        <div class="row">
            <div class="col-sm-12 col-md-9 col-md-push-3">
                <div class="row">
                	<?php
                	if($services_item->num_rows() > 0)
                    {   
                        foreach($services_item->result() as $res_item)
                        {
                            $service_description = $res_item->service_description;
                            $service_id = $res_item->service_id;
                           
                        }
                    }

                    $services_gallery = $this->site_model->get_active_service_gallery($service_id);
                    $service_gallery_items = '';
                    if($services_gallery->num_rows() > 0)
                    {   
                        foreach($services_gallery->result() as $res_gallery)
                        {
                            $service_gallery_image_name = $res_gallery->service_gallery_image_name;
                                    
                            $service_gallery_items .='
                                                <a href="'.$service_location.''.$service_gallery_image_name.'" class="opacity" data-rel="prettyPhoto[portfolio]">
                                                    <img src="'.$service_location.''.$service_gallery_image_name.'" width="1000" height="500" alt="" />
                                                </a> ';
                        }
                    }
                	?>
                    <div class="col-md-12 content-block">
                        <div class="text-center">
                            <div class="owl-carousel navigation-4" data-pagination="false" data-items="1"
                            data-singleitem="true" data-autoplay="true" data-navigation="true">
                            
                                <?php echo $service_gallery_items;?>
                            </div>
                        </div>
                        <p><?php echo $service_description;?>.</p>
                    </div>
                </div>
                <!-- <div class="row">
                    <div class="col-md-6 content-block opacity">
                        <img src="img/sections/services/single/5.jpg" width="1000" height="500" alt="" />
                        <h4>THE INITIAL PLANNING</h4>
                        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec et magna lacinia, congue enim et,
                        dignissim ante. Nam gravida sit amet odio eu tincidunt.</p>
                    </div>
                    <div class="col-md-6 content-block opacity">
                        <img src="img/sections/services/single/6.jpg" width="1000" height="500" alt="" />
                        <h4>Project Process</h4>
                        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec et magna lacinia, congue enim et,
                        dignissim ante. Nam gravida sit amet odio eu tincidunt.</p>
                    </div>
                </div> -->
                <hr class="top-margin-0" />
                <div class="row">
                    <div class="col-md-6 service-list content-block">
                        <h4>why choose us</h4>
                        <ul>
                            <li>
                                <i class="icon-alarm3 text-color"></i>
                                <p>We available 24/7 feel free to contact us.</p>
                            </li>
                            <li>
                                <i class="icon-shield2 text-color"></i>
                                <p>We are genius because of experience.</p>
                            </li>
                            <li>
                                <i class="icon-price-tag text-color"></i>
                                <p>Offer low price compare with other builders</p>
                            </li>
                            <li>
                                <i class="icon-headphones text-color"></i>
                                <p>We provide free estimation for all projects</p>
                            </li>
                        </ul>
                    </div>
                    <div class="col-md-6 content-block">
                        <div role="tabpanel">
                            <!-- Nav tabs -->
                            <ul class="nav nav-tabs" role="tablist">
                                <li role="presentation" class="active">
                                    <a href="#brochure" aria-controls="brochure" role="tab" data-toggle="tab">Brochure</a>
                                </li>
                                <li role="presentation">
                                    <a href="#reporting" aria-controls="reporting" role="tab"
                                    data-toggle="tab">Reporting</a>
                                </li>
                                <li role="presentation">
                                    <a href="#inspection" aria-controls="inspection" role="tab"
                                    data-toggle="tab">Inspection</a>
                                </li>
                                <li role="presentation">
                                    <a href="#others" aria-controls="others" role="tab" data-toggle="tab">Others</a>
                                </li>
                            </ul>
                            <!-- Tab panes -->
                            <div class="tab-content">
                                <div role="tabpanel" class="tab-pane active top-margin-0" id="brochure">
                                    <a href="#">
                                        <img src="img/sections/services/broucher.jpg" width="450" height="270" alt="" />
                                    </a>
                                </div>
                                <div role="tabpanel" class="tab-pane" id="reporting">
                                    <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Repudiandae odit iste
                                    exercitationem praesentium deleniti.</p>
                                    <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit nostrum laborum rem id
                                    nihil tempora.</p>
                                </div>
                                <div role="tabpanel" class="tab-pane" id="inspection">
                                    <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Repudiandae odit iste
                                    exercitationem praesentium deleniti.</p>
                                    <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit nostrum laborum rem id
                                    nihil tempora.</p>
                                </div>
                                <div role="tabpanel" class="tab-pane" id="others">
                                    <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Repudiandae odit iste
                                    exercitationem praesentium deleniti.</p>
                                    <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit nostrum laborum rem id
                                    nihil tempora.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="sidebar col-sm-12 col-md-3 col-md-pull-9">
                <div class="widget list-border">
                    <div class="widget-title">
                        <h3 class="title">Services</h3>
                    </div>
                    <div id="MainMenu1">
                        <div class="list-group panel">
                            <div class="collapse in" id="demo">
                            <?php
                            $services = $this->site_model->get_active_services();
                            $checking_items = '';
                            if($services->num_rows() > 0)
                            {   $count = 0;
                                foreach($services->result() as $res)
                                {
                                    $service_name = $res->service_name;
                                    $service_description = $res->service_description;
                                     $mini_desc = implode(' ', array_slice(explode(' ', $service_description), 0, 10));
                                     $maxi_desc = implode(' ', array_slice(explode(' ', $service_description), 0, 30));
                                    $web_name = $this->site_model->create_web_name($service_name);
                                    if($title == $service_name)
                                    {
                                    	$item_active = 'active';
                                    }
                                    else
                                    {
                                    	$item_active = '';
                                    }
                                    $checking_items .='<a href="'.base_url().'services/'.$web_name.'" class="list-group-item '.$item_active.'">'.$service_name.'</a> ';
                                }
                            }
                            echo $checking_items;
                            ?>
                            
                        </div>
                    </div>
                    <!-- category-list -->
                </div>
            </div>
        </div>
    </div>
</section>
<!-- Services -->
<div id="get-quote" class="bg-color black text-center">
    <div class="container">
        <div class="row get-a-quote">
            <div class="col-md-12">Get A Free Quote / Need a Help ? 
            <a class="black" href="<?php echo site_url();?>contact">Contact Us</a></div>
        </div>
        <div class="move-top bg-color page-scroll">
            <a href="#page">
                <i class="glyphicon glyphicon-arrow-up"></i>
            </a>
        </div>
    </div>
</div>