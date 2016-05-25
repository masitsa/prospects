<?php

    $result = '';
    
    //if users exist display them

    if ($faqs->num_rows() > 0)
    {   
       
        foreach ($faqs->result() as $row)
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
                        <li>
                            <div class="collapsible-header"><i class="material-icons">info_outline</i>'.$post_title.'</div>
                            <div class="collapsible-body"><p>'.$description.'</p></div>
                        </li>
                   
                ';
            }
        }
        else
        {
            $result .= "There are no faqs :-(";
        }
       
      ?> 
<div class="grey lighten-4 section-content">
	<div class="container">
        
        <h3 class="header center-align grey-text darken-2 margin-height-60">Frequently asked questions</h3>
        
        <div class="row">
            
            <div class="col m12">
            	<ul class="collapsible popout" data-collapsible="accordion">
                    <?php echo $result;?>
                </ul>
            </div>
        </div>
        
        <div class="margin-height-60"></div>
        
    </div>
</div>