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
<?php
    $gallery = $this->site_model->get_active_service_gallery_names();
    $gallery_items_names = '';
    if($gallery->num_rows() > 0)
    {   
        foreach($gallery->result() as $res_gallery)
        {
            $gallery_name = $res_gallery->gallery_name;
                    
            $gallery_items_names .='<li class="filter" data-filter=".'.$gallery_name.'">'.$gallery_name.'</li>';
        }
    }

    $gallery_div = $this->site_model->get_active_gallery();
    $gallery_items = '';
    if($gallery_div->num_rows() > 0)
    {   
        foreach($gallery_div->result() as $res_gallery_div)
        {
            $gallery_name = $res_gallery_div->gallery_name;
            $gallery_image_name = $res_gallery_div->gallery_image_name;
            $gallery_image_thumb = $res_gallery_div->gallery_image_thumb;

             $gallery_items .=
                                    '
                                     <div class="grid-item all '.$gallery_name.'">
                                        <div class="grid">
                                            <img src="'.$gallery_location.''.$gallery_image_name.'" width="400" height="273" alt="Recent Work"
                                            class="img-responsive" />
                                            <div class="figcaption">
                                            <h4>'.$gallery_name.'</h4>
                                            <!-- Image Popup-->
                                            <a href="'.$gallery_location.''.$gallery_image_name.'" data-rel="prettyPhoto[portfolio]">
                                                <i class="fa fa-search"></i>
                                            </a> </div>
                                        </div>
                                    </div>
                                    ';      
        }
    }
?>

        <!-- page-header -->
        <section id="works" class="page-section">
            <div class="container">
                <div class="mixed-grid pad general-section">
                    <div class="filter-menu">
                        <ul class="nav black works-filters text-center" id="filters">
                            <li class="filter active" data-filter=".all">Show All</li>
                            <?php echo $gallery_items_names;?>
                        </ul>
                    </div>
                    <div class="clearfix"></div>
                    <div class="masonry-grid grid-col-3 black">
                        <div class="grid-sizer"></div>
                        <?php echo $gallery_items;?>
                    </div>
                </div>
            </div>
        </section>
        <div id="get-quote" class="bg-color black text-center">
            <div class="container">
                <div class="row get-a-quote">
                    <div class="col-md-12">Get A Free Quote / Need Help ? 
                    <a class="black" href="#">Contact Us</a></div>
                </div>
                <div class="move-top bg-color page-scroll">
                    <a href="#page">
                        <i class="glyphicon glyphicon-arrow-up"></i>
                    </a>
                </div>
            </div>
        </div>