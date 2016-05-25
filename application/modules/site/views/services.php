<?php
    if(count($contacts) > 0)
    {
        $email = $contacts['email'];
        $facebook = $contacts['facebook'];
        $twitter = $contacts['twitter'];
        $logo = $contacts['logo'];
        $company_name = $contacts['company_name'];
        $phone = $contacts['phone'];
        $address = $contacts['address'];
        $post_code = $contacts['post_code'];
        $city = $contacts['city'];
        $building = $contacts['building'];
        $floor = $contacts['floor'];
        $location = $contacts['location'];
        $about = $contacts['about'];

        $working_weekday = $contacts['working_weekday'];
        $working_weekend = $contacts['working_weekend'];

        $mission = $contacts['mission'];
        $vision = $contacts['vision'];
    }
?>
<section class="heading-page">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h1>Our Services</h1>
                <span>Here are the services we offer</span>
            </div>
        </div>
    </div>
</section>

<section class="our-services">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="section-heading-left text-left">
                    <img src="<?php echo base_url().'assets/logo/'.$logo;?>" alt="<?php echo $company_name;?>" height="50">
                    <h2>Our <em>Services</em></h2>
                </div>
            </div>
        </div>
        <div class="row">
        	<div class="col-md-6">
                <div class="services-item">
                    <?php
                    $services = $this->site_model->get_active_services();
                    $checking_items = '';
                    if($services->num_rows() > 0)
                    {   $count = 0;
                        foreach($services->result() as $res)
                        {
                            $service_id = $res->service_id;
                            $service_name = $res->service_name;
                            $service_description = str_replace('<font face="Arial, Verdana">', '', $res->service_description);
                            $service_image_name = $res->service_image_name;
                            $mini_desc = implode(' ', array_slice(explode(' ', $service_description), 0, 10));
                            $maxi_desc = implode(' ', array_slice(explode(' ', $service_description), 0, 30));
                            $web_name = $this->site_model->create_web_name($service_name);
    
                            $count ++;
    						
							if($service_id <= 3)
							{
                            	$checking_items .= '
                                <div class="col-md-12">
                                    <div class="second-service-item">
                                        <img src="'.base_url().'assets/service/'.$service_image_name.'" alt="'.$service_name.'" height="150">
                                        <h4>'.$service_name.'</h4>
                                        <div class="line-dec"></div>
                                        <p>'.$service_description.'</p>
                                    </div>
                                </div>
                                ';
							}
                        }
                    }
                    echo $checking_items;
                ?>
                </div>
            </div>
            
        	<div class="col-md-6">
                <div class="services-item">
                    <?php
                    $services = $this->site_model->get_active_services();
                    $checking_items = '';
                    if($services->num_rows() > 0)
                    {   $count = 0;
                        foreach($services->result() as $res)
                        {
                            $service_name = $res->service_name;
                            $service_id = $res->service_id;
                            $service_description = str_replace('<font face="Arial, Verdana">', '', $res->service_description);
                            $service_image_name = $res->service_image_name;
                            $mini_desc = implode(' ', array_slice(explode(' ', $service_description), 0, 10));
                            $maxi_desc = implode(' ', array_slice(explode(' ', $service_description), 0, 30));
                            $web_name = $this->site_model->create_web_name($service_name);
    
                            $count ++;
    
							if($service_id > 3)
							{
                            	$checking_items .= '
                                <div class="col-md-12">
                                    <div class="second-service-item">
                                        <img src="'.base_url().'assets/service/'.$service_image_name.'" alt="'.$service_name.'" height="150">
                                        <h4>'.$service_name.'</h4>
                                        <div class="line-dec"></div>
                                        <p>'.$service_description.'</p>
                                    </div>
                                </div>
                                ';
							}
                        }
                    }
                    echo $checking_items;
                ?>
                </div>
            </div>
        </div>
    </div>
</section>
