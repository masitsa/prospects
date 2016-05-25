<?php

    $result = '';
    
    //if users exist display them

    if ($items->num_rows() > 0)
    {   
       $counter = 0;
        foreach ($items->result() as $row)
        {
        	$counter++;
            $post_id = $row->post_id;
            $blog_category_name = $row->blog_category_name;
            $blog_category_id = $row->blog_category_id;
            $post_title = $row->post_title;
            $web_name = $this->site_model->create_web_name($post_title);
            $post_status = $row->post_status;
            $post_views = $row->post_views;
            $image = base_url().'assets/images/posts/'.$row->post_image;
            if($row->post_image == "" || $row->post_image == NULL)
            {
            	$image ="http://placehold.it/450x250?text=Comparison+graph";
            }
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
           
           		if(($counter%2) == 0)
				{
						$result .= '
	                        <div class="white section-content">
									<div class="container">
								        		
								        <!--<h3 class="header center-align grey-text darken-2 margin-height-60">Increase your organic mobile app installs</h3>-->
								        
								        <div class="row">
								        	<div class="col m6 s12">
								            	'.$description.'
								            </div>
								            <div class="col m6 s12">
								            	<img src="'.$image.'" class="responsive-img" alt="Graph">
								            </div>
								            
								            
								        </div>
										
								        <div class="margin-height-40"></div>
								    </div>
								</div>

								<div class="container white">
								    <div class="divider"></div>
								</div>
	                   
	                ';
				}
				else
				{
					$result .= '
	                        <div class="white section-content">
									<div class="container">
								        		
								        <!--<h3 class="header center-align grey-text darken-2 margin-height-60">Increase your organic mobile app installs</h3>-->
								        
								        <div class="row">
								             <div class="col m6 s12">
								            	<img src="'.$image.'" class="responsive-img" alt="Graph">
								            </div>
								            
								            <div class="col m6 s12">
								            	'.$description.'
								            </div>
								           
								        </div>
										
								        <div class="margin-height-40"></div>
								    </div>
								</div>

								<div class="container white">
								    <div class="divider"></div>
								</div>                  
	                ';
				}
      
            
            }
        }
        else
        {
            $result .= "There are no posts :-(";
        }
       echo $result;
      ?> 
