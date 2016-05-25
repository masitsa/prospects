<?php
	if(count($contacts) > 0)
	{
		$email = $contacts['email'];
		$facebook = $contacts['facebook'];
		$linkedin = $contacts['linkedin'];
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
	}
	$result = '';
	$latest = '';
	
	//if users exist display them
	$total_posts = $latest_posts->num_rows();
	
	if ($total_posts > 0)
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
		
		//latest post
		$results = $latest_posts->result();
		$row = $results[0];
		$post_id = $row->post_id;
		$blog_category_name = '';//$row->blog_category_name;
		$blog_category_web_name = $this->site_model->create_web_name($blog_category_name);
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
		$mini_desc = implode(' ', array_slice(explode(' ', $description), 0, 30));
		$created = $row->created;
		$day = date('j',strtotime($created));
		$month = date('M',strtotime($created));
		$created_on = date('jS M Y',strtotime($created));
		
		$categories = '';
		$count = 0;
		
		if($categories_query->num_rows() > 0)
		{
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
		}
		
		$latest = '
			<div class="latest-post">
				<a href="'.site_url().'blog/'.$web_name.'"><img src="'.$image.'" alt="'.$post_title.'"></a>
				<a href="'.site_url().'blog/'.$web_name.'"><h3>'.$post_title.'</h3></a>
				<span>'.$categories.' '.$created_on.'</span>
				<p>'.$mini_desc.'</p>
				<a href="'.site_url().'blog/'.$web_name.'">Continue Reading</a>
			</div>
		';
		
		//foreach ($latest_posts->result() as $row)
		//other latest posts
		for($r = 1; $r < $total_posts; $r++)
		{
			$row = $results[$r];
			$post_id = $row->post_id;
			$blog_category_name = '';//$row->blog_category_name;
			$blog_category_web_name = $this->site_model->create_web_name($blog_category_name);
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
			$mini_desc = implode(' ', array_slice(explode(' ', $description), 0, 30));
			$created = $row->created;
			$day = date('j',strtotime($created));
			$month = date('M',strtotime($created));
			$created_on = date('jS M Y',strtotime($created));
			
			$categories = '';
			$count = 0;
			
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
							<div class="user_comment">
								<h5>'.$post_comment_user.' - '.$date.'</h5>
								<p>'.$post_comment_description.'</p>
							</div>
						';
					}
				}
			$result .= '
				<div class="col-md-12">
					<div class="blog-item">
						<a href="'.site_url().'blog/'.$web_name.'"><img src="'.$image.'" alt="'.$post_title.'"></a>
						<a href="'.site_url().'blog/'.$web_name.'"><h4>'.$post_title.'</h4></a>
						<span>'.$categories.' '.$created_on.'</span>
						<p>'.$mini_desc.'</p>
						<a href="'.site_url().'blog/'.$web_name.'">Continue Reading</a>
					</div>
				</div>
				';
			}
		}
		else
		{
			$result = "There are no posts :-(";
		}
	   
	  ?>
            
				<section class="our-blog" style="background-color: #f2f2f2;">
					<div class="container">
						<div class="row">
							<div class="col-md-12">
								<div class="section-heading-left text-left">
									<img src="<?php echo base_url().'assets/logo/'.$logo;?>" alt="<?php echo $company_name;?>" height="50">
									<h2>Our <em>Blog</em></h2>
									<p>Here are some of <span>our blog</span> posts.</p>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-6">
								<?php echo $latest;?>
							</div>
							<div class="col-md-6">
								<div class="latest-posts">
									<?php echo $result;?>
								</div>
							</div>
						</div>
					</div>
				</section>


