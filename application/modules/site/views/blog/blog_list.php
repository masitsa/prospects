<?php

		$result = '';
		
		//if users exist display them
	
		if ($query->num_rows() > 0)
		{	
			//get all administrators
			$administrators = $this->users_model->get_all_administrators();
			if ($administrators->num_rows() > 0)
			{
				$admins = $administrators->result();
			}
			
			else
			{
				$admins = NULL;
			}
			
			foreach ($query->result() as $row)
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
				$comments = $this->users_model->count_items('post_comment', 'post_id = '.$post_id);
				$categories_query = $this->blog_model->get_all_post_categories($blog_category_id);
				$description = $row->post_content;
				$mini_desc = implode(' ', array_slice(explode(' ', $description), 0, 50));
				$created = $row->created;
				$day = date('j',strtotime($created));
				$month = date('M Y',strtotime($created));
				$created_on = date('jS M Y',strtotime($row->created));
				
				$categories = '';
				$count = 0;
				//get all administrators
					$administrators = $this->users_model->get_all_administrators();
					if ($administrators->num_rows() > 0)
					{
						$admins = $administrators->result();
						
						if($admins != NULL)
						{
							foreach($admins as $adm)
							{
								$user_id = $adm->user_id;
								
								if($user_id == $created_by)
								{
									$created_by = $adm->first_name;
								}
							}
						}
					}
					
					else
					{
						$admins = NULL;
					}
				
					foreach($categories_query->result() as $res)
					{
						$count++;
						$category_name = $res->blog_category_name;
						$category_id = $res->blog_category_id;
						$category_web_name = $this->site_model->create_web_name($category_name);
						
						if($count == $categories_query->num_rows())
						{
							$categories .= '<a href="'.site_url().'blog/category/'.$category_web_name.'" title="View all posts in '.$category_name.'" rel="category tag">'.$category_name.'</a>';
						}
						
						else
						{
							$categories .= '<a href="'.site_url().'blog/category/'.$category_web_name.'" title="View all posts in '.$category_name.'" rel="category tag">'.$category_name.'</a>, ';
						}
					}
					$comments_query = $this->blog_model->get_post_comments($post_id);
					//comments
					$comments = 'No Comments';
					$total_comments = $comments_query->num_rows();
					if($total_comments == 1)
					{
						$title = 'comment';
					}
					else
					{
						$title = 'comments';
					}
					
					if($comments_query->num_rows() > 0)
					{
						$comments = '';
						foreach ($comments_query->result() as $row)
						{
							$post_comment_user = $row->post_comment_user;
							$post_comment_description = $row->post_comment_description;
							$date = date('jS M Y',strtotime($row->comment_created));
							
							$comments .= 
							'
								<div class="user_comment">
									<h5>'.$post_comment_user.' - '.$date.'</h5>
									<p>'.$post_comment_description.'</p>
								</div>
							';
						}
					}
				$result .= '
						<div class="col-md-12">
                            <div class="featured-blog-post hidden-sm hidden-xs">
                                <a href="'.site_url().'blog/'.$web_name.'"><img src="'.$image.'" alt="'.$post_title.'"></a>
                                <div class="text-content">
                                    <a href="'.site_url().'blog/'.$web_name.'"><h4>'.$post_title.'</h4></a>
                                    <span>'.$categories.' '.$created_on.'</span>
                                    <p>'.$mini_desc.'</p>
                                    <a href="'.site_url().'blog/'.$web_name.'">Continue Reading</a>
                                </div>
                            </div>
                        </div>
					
		            ';
		        }
			}
			else
			{
				$result .= "There are no posts :-(";
			}
           
          ?> 
<section class="heading-page">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h1>Our Blog</h1>
                <span>Here are our latest blog posts</span>
            </div>
        </div>
    </div>
</section>

<section class="classic-blog-page">
    <div class="container">
        <div class="row">
            <div class="col-md-8">
                <div class="blog-posts">
                    <div class="row">
                        <?php echo $result;?>
                        <?php if(isset($links)){echo $links;}?>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
            	
            	<?php echo $this->load->view('blog/includes/side_bar', '', TRUE);?>
                
            </div>
        </div>
    </div>
</section>
