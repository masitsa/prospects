<?php
$recent_query = $this->blog_model->get_recent_posts();
$recent_posts  ='';
if($recent_query->num_rows() > 0)
{
	$row = $recent_query->row();
	
	$post_id = $row->post_id;
	$post_title = $row->post_title;
	$web_name = $this->site_model->create_web_name($post_title);
	$image = base_url().'assets/images/posts/thumbnail_'.$row->post_image;
	$comments = $this->users_model->count_items('post_comment', 'post_id = '.$post_id);
	$description = $row->post_content;
	$mini_desc = implode(' ', array_slice(explode(' ', $description), 0, 50));
	$created = date('jS M Y',strtotime($row->created));
	$recent_posts .= '
	 		<li data-thumb="'.$image.'">
				<div class="text-content">
					<a href="'.site_url().'blog/'.$web_name.'"><h4>'.$post_title.'</h4></a>
				</div>
				<img src="'.$image.'" alt="'.$post_title.'" />
			</li>
	';

}

else
{
	$recent_posts = 'No posts yet';
}
$categories_query = $this->blog_model->get_all_active_category_parents();
$categories = '';
if($categories_query->num_rows() > 0)
{
	
	foreach($categories_query->result() as $res)
	{
		$category_id = $res->blog_category_id;
		$category_name = $res->blog_category_name;
		$web_name = $this->site_model->create_web_name($category_name);
		
		$children_query = $this->blog_model->get_all_active_category_children($category_id);
		
		//if there are children
		$categories = '<li><a href="'.site_url().'blog/category/'.$web_name.'">'.$category_name.'</a></li>';
	}
}

else
{
	$categories = 'No Categories';
}
$popular_query = $this->blog_model->get_popular_posts();

if($popular_query->num_rows() > 0)
{
	$popular_posts = '';
	
	foreach ($popular_query->result() as $row)
	{
		$post_id = $row->post_id;
		$post_title = $row->post_title;
		$web_name = $this->site_model->create_web_name($post_title);
		$image = base_url().'assets/images/posts/thumbnail_'.$row->post_image;
		$comments = $this->users_model->count_items('post_comment', 'post_id = '.$post_id);
		$description = $row->post_content;
		$mini_desc = implode(' ', array_slice(explode(' ', $description), 0, 10));
		$created = date('jS M Y',strtotime($row->created));
		
		$popular_posts .= '
	 		<li data-thumb="'.$image.'">
				<div class="text-content">
					<a href="'.site_url().'blog/'.$web_name.'"><h4>'.$post_title.'</h4></a>
				</div>
				<img src="'.$image.'" alt="'.$post_title.'" />
			</li>
		';
	}
}

else
{
	$popular_posts = 'No posts views yet';
}
?>

				<div class="blog-sidebar">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="sidebar-item welcome-text">
                                <h4>Search</h4>
                                <div class="line-dec"></div>
                                <?php echo form_open('site/blog/search');?>
                                <input type="text" class="blog-search-field" name="search_item" placeholder="Type to search" value="">
                                <?php echo form_close();?>
                            </div>
                            <div class="sidebar-item featured-posts">
                                <h4>Recent Posts</h4>
                                <div class="line-dec"></div>
                                <div class="slider">
                                    <div class="single-slider">
                                        <ul class="slides">
                                            <?php echo $recent_posts;?>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <!--<div class="sidebar-item connect-us">
                                <h4>Connect With Us</h4>
                                <div class="line-dec"></div>
                                <ul class="social-icons">
                                    <li><a href="#"><i class="fa fa-facebook"></i></a></li>
                                    <li><a href="#"><i class="fa fa-twitter"></i></a></li>
                                    <li><a href="#"><i class="fa fa-dribbble"></i></a></li>
                                    <li><a href="#"><i class="fa fa-instagram"></i></a></li>
                                    <li><a href="#"><i class="fa fa-linkedin"></i></a></li>
                                    <li><a href="#"><i class="fa fa-behance"></i></a></li>
                                </ul>
                            </div>-->
                            <div class="sidebar-item categories">
                                <h4>Categories</h4>
                                <div class="line-dec"></div>
                                <ul class="col-md-12">
                                    <?php echo $categories;?>
                                </ul>
                            </div>
                            <div class="sidebar-item featured-posts">
                                <h4>Popular Posts</h4>
                                <div class="line-dec"></div>
                                <div class="slider">
                                    <div class="single-slider">
                                        <ul class="slides">
                                            <?php echo $popular_posts;?>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            </div>
                        </div>
                    </div>
                </div>





