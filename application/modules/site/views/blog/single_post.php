<?php

$post_id = $row->post_id;
$blog_category_name = $row->blog_category_name;
$blog_category_id = $row->blog_category_id;
$post_title = $row->post_title;
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
$created_on = date('jS M Y H:i a',strtotime($row->created));

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
		
		if($count == $categories_query->num_rows())
		{
			$categories .= '<a href="'.site_url().'blog/category/'.$category_id.'" title="View all posts in '.$category_name.'" rel="category tag">'.$category_name.'</a>';
		}
		
		else
		{
			$categories .= '<a href="'.site_url().'blog/category/'.$category_id.'" title="View all posts in '.$category_name.'" rel="category tag">'.$category_name.'</a>, ';
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
			$date = date('jS M Y H:i a',strtotime($row->comment_created));
			
			$comments .= 
			'
				<li class="first-comment">
					<img src="'.base_url().'assets/img/avatar.jpg" alt="'.$post_comment_user.'">
					<h6>'.$post_comment_user.'</h6>
					<span>'.$date.'</span>
					<p>'.$post_comment_description.'</p>
				</li>
			';
		}
	}
	



?>
<section class="heading-page">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h1><?php echo $post_title;?></h1>
            </div>
        </div>
    </div>
</section>

<section class="single-blog-page">
    <div class="container">
        <div class="row">
            <div class="col-md-8">
                <div class="blog-post">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="blog-item">
                                <a href="#"><img src="<?php echo $image;?>" alt="<?php echo $post_title;?>"></a>
                                <div class="text-content">
                                    <a href="#"><h4><?php echo $post_title;?></h4></a>
                                    <span><?php echo $categories;?> <?php echo $created_on;?></span>
                                    <p><?php echo $description;?></p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="comments">
                                <h4><?php echo $comments_query->num_rows();?> <?php echo $title;?></h4>
                                <div class="comment-items">
                                    <ul>
                                        <?php echo $comments;?>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="leave-comment">
                                <h4>Leave a comment</h4>
                                <form method="post" action="<?php echo site_url().'site/blog/add_comment/'.$post_id.'/'.$web_name;?>" data-toggle="validator" novalidate="true">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <input type="text" class="blog-search-field" name="name" placeholder="Your name here..." value="">
                                        </div>
                                        <div class="col-md-6">
                                            <input type="text" class="blog-search-field" name="email" placeholder="Your email here..." value="">
                                        </div>
                                        <div class="col-md-12">
                                            <textarea name="post_comment_description" id="message" placeholder="Your message here..."></textarea>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="accent-button">
                                                <button type="submit" class="">Submit Comment</button>
                                            </div>
                                        </div>
                                    </div>		
                            	</form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <?php echo $this->load->view('blog/includes/side_bar', '', TRUE);?>
            </div>
        </div>
    </div>
</section>
