<?php

    $result = '';
    
    //if users exist display them

    if ($testimonials->num_rows() > 0)
    {   
       
        foreach ($testimonials->result() as $row)
        {
            $post_id = $row->post_id;
            $blog_category_name = $row->blog_category_name;
            $blog_category_id = $row->blog_category_id;
            $post_title = $row->post_title;
            $web_name = $this->site_model->create_web_name($post_title);
            $post_status = $row->post_status;
            $post_views = $row->post_views;
            $image = base_url().'assets/images/posts/'.$row->post_image;
            $created_by = $row->created_by;
            $modified_by = $row->modified_by;
            $description = $row->post_content;
            $mini_desc = implode(' ', array_slice(explode(' ', $description), 0, 50));
            $created = $row->created;
            $day = date('j',strtotime($created));
            $month = date('M Y',strtotime($created));
            $created_on = date('jS M Y',strtotime($row->created));
            
            $categories = '';
            $count = 0;
           
              
            $result .= '
                         <div class="item">
                            <div class="card grey darken-2">
                                <div class="card-content white-text">
                                    <span class="card-title">'.$post_title.'</span>
                                    <img src="'.$image.'" class="responsive-img" alt="Testimonials">
                                    <p>'.$description.'</p>
                                </div>
                            </div>
                        </div>
                   
                ';
            }
        }
        else
        {
            $result .= "There are no testimonials :-(";
        }
       
      ?> 
<div class="white section-content">
	<div class="container">
        		
        <h3 class="header center-align grey-text darken-2 margin-height-60">Testimonials</h3>
        
        <div class="row">
        	
            <div class="m12">
                <div class="owl-carousel">
                    <?php echo $result;?>
                    
                </div>
                	
                <div class="owl-controls">
                    <!--<div class="owl-nav">
                        <div class="owl-prev">prev</div>
                        <div class="owl-next">next</div>
                    </div>-->
                    <div class="owl-dots">
                        <div class="owl-dot active"><span></span></div>
                        <div class="owl-dot"><span></span></div>
                        <div class="owl-dot"><span></span></div>
                    </div>
                </div>
                
            </div>
        </div>
        
        <div class="margin-height-60"></div>
    </div>
</div>

<div class="container white">
    <div class="divider"></div>
</div>