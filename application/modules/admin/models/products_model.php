<?php

class Products_model extends CI_Model 
{	
	/*
	*	Retrieve all products
	*
	*/
	public function all_products()
	{
		$this->db->where('product_status = 1');
		$query = $this->db->get('product');
		
		return $query;
	}
	/*
	*	Retrieve all product reviews
	*	@param string $table
	* 	@param string $where
	*
	*/
	public function get_all_product_review($table, $where, $per_page, $page)
	{
		//retrieve all orders
		$this->db->from($table);
		$this->db->select('*');
		$this->db->where($where);
		$this->db->order_by('product_review.product_review_id','desc');
		$query = $this->db->get('', $per_page, $page);
		
		return $query;
	}
	/*
	*	Retrieve all products ratings
	*
	*/
	public function product_ratings($product_id)
	{
		$this->db->from('product_review');
		$this->db->where('product_review_status = 1 AND product_id = '.$product_id);
		$query = $this->db->get();
		
		return $query;
	}
	/*
	*	Retrieve all products for export
	*	@param string $table
	* 	@param string $where
	*
	*/
	public function get_all_products_export($table, $where,  $limit = NULL, $order_by = 'created', $order_method = 'DESC')
	{
		$this->db->from($table);
		$this->db->select('product.sale_price, product.featured, product.product_id, product.product_name, product.product_buying_price, product.product_selling_price, product.product_status, product.product_description, product.product_code, product.product_balance, product.brand_id, product.category_id, product.created, product.created_by, product.last_modified, product.modified_by, product.product_thumb_name, product.product_image_name, category.category_name, brand.brand_name');
		$this->db->where($where);
		$this->db->order_by($order_by, $order_method);
		
				
		return $query;
	}
	
	/*
	*	Retrieve all products
	*	@param string $table
	* 	@param string $where
	*
	*/
	public function get_all_products($table, $where, $per_page, $page, $limit = NULL, $order_by = 'created', $order_method = 'DESC')
	{
		$this->db->from($table);
		$this->db->select('product.sale_price, product.featured, product.product_id, product.product_name, product.product_buying_price, product.product_selling_price, product.product_status, product.product_description, product.product_code, product.product_balance, product.brand_id, product.category_id, product.created, product.created_by, product.last_modified, product.modified_by, product.product_thumb_name, product.product_image_name, category.category_name, brand.brand_name, product.sale_price_type');
		$this->db->where($where);
		$this->db->order_by($order_by, $order_method);
		
		if(isset($limit))
		{
			$query = $this->db->get('', $limit);
		}
		
		else
		{
			$query = $this->db->get('', $per_page, $page);
		}
		
		return $query;
	}

	/*
	*	Retrieve all products
	*	@param string $table
	* 	@param string $where
	*
	*/
	public function get_all_product_bundle($table, $where, $per_page, $page, $limit = NULL, $order_by = 'created_on', $order_method = 'DESC')
	{
		$this->db->from($table);
		$this->db->select('*');
		$this->db->where($where);
		$this->db->order_by($order_by, $order_method);
		
		if(isset($limit))
		{
			$query = $this->db->get('', $limit);
		}
		
		else
		{
			$query = $this->db->get('', $per_page, $page);
		}
		
		return $query;
	}
	
	public function add_images($product_id, $file_name, $thumb_name)
	{
		$data = array(
			'modified_by'=>$this->session->userdata('vendor_id'),
			'product_image_name'=>$file_name,
			'product_thumb_name'=>$thumb_name
		);
		
		$this->db->where('product_id', $product_id);
		if($this->db->update('product', $data))
		{
			return TRUE;
		}
		else{
			return FALSE;
		}
	}
	
	/*
	*	Add a new product
	*	@param string $image_name
	*
	*/
	public function add_product($product_id)
	{
		if($product_id > 0)
		{
			$data = array(
				'product_name'=>ucwords(strtolower($this->input->post('product_name'))),
				'featured'=>$this->input->post('featured'),
				'sale_price'=>$this->input->post('product_sale_price'),
				'product_buying_price'=>$this->input->post('product_buying_price'),
				'product_selling_price'=>$this->input->post('product_selling_price'),
				'product_status'=>$this->input->post('product_status'),
				'product_description'=>$this->input->post('product_description'),
				'product_balance'=>$this->input->post('product_balance'),
				'brand_id'=>$this->input->post('brand_id'),
				'category_id'=>$this->input->post('category_id'),
				'minimum_order_quantity'=>$this->input->post('minimum_order_quantity'),
				'maximum_purchase_quantity'=>$this->input->post('maximum_purchase_quantity'),
				'sale_price_type'=>$this->input->post('sale_price_type_id'),
				'modified_by'=>$this->session->userdata('vendor_id'),
				'product_shipping'=>3
			);
			
			$this->db->where('product_id', $product_id);
			if($this->db->update('product', $data))
			{
				//save locations
				$this->save_product_locations($product_id);
				
				return $product_id;
			}
			else{
				return FALSE;
			}
		}
		
		else
		{
			$code = $this->create_product_code($this->input->post('category_id'));
			$tiny_url = $this->products_model->get_tiny_url(site_url().'products/view-product/'.$code);
			
			$data = array(
					'product_name'=>ucwords(strtolower($this->input->post('product_name'))),
					'featured'=>$this->input->post('featured'),
					'sale_price'=>$this->input->post('product_sale_price'),
					'product_buying_price'=>$this->input->post('product_buying_price'),
					'product_selling_price'=>$this->input->post('product_selling_price'),
					'product_status'=>$this->input->post('product_status'),
					'product_description'=>$this->input->post('product_description'),
					'product_code'=>$code,
					'product_balance'=>$this->input->post('product_balance'),
					'brand_id'=>$this->input->post('brand_id'),
					'category_id'=>$this->input->post('category_id'),
					'minimum_order_quantity'=>$this->input->post('minimum_order_quantity'),
					'maximum_purchase_quantity'=>$this->input->post('maximum_purchase_quantity'),
					'sale_price_type'=>$this->input->post('sale_price_type_id'),
					'created'=>date('Y-m-d H:i:s'),
					'created_by'=>$this->session->userdata('vendor_id'),
					'modified_by'=>$this->session->userdata('vendor_id'),
					'tiny_url'=>$tiny_url
				);
				
			if($this->db->insert('product', $data))
			{
				$product_id = $this->db->insert_id();
				
				//save locations
				$this->save_product_locations($product_id);
				
				return $product_id;
			}
			else{
				return FALSE;
			}
		}
	}
	
	public function save_product_locations($product_id)
	{
		//delete previous locations
		$this->db->where('product_id', $product_id);
		$this->db->delete('product_location');
		
		//save locations
		$product_locations = $this->input->post('product_locations');
		// var_dump($product_locations) or die();
		$total_locations = count($product_locations);
		$data['product_id'] = $product_id;
		
		for($r = 0; $r < $total_locations; $r++)
		{
			$post_code = $product_locations[$r];
			
			if(!empty($post_code))
			{
				$this->db->where('post_code', $post_code);
				$query = $this->db->get('surburb');
				
				if($query->num_rows() > 0)
				{
					$row = $query->row();
					
					$data['surburb_id'] = $row->surburb_id;
					$data['product_location_status'] = 1;
					
					$this->db->insert('product_location', $data);
				}
			}
		}
		
		return TRUE;
	}
	
	/*
	*	Update an existing product
	*	@param string $image_name
	*	@param int $product_id
	*
	*/
	public function update_product($file_name, $thumb_name, $product_id)
	{
		$data = array(
				'product_name'=>ucwords(strtolower($this->input->post('product_name'))),
				'featured'=>$this->input->post('featured'),
				'sale_price'=>$this->input->post('product_sale_price'),
				'product_buying_price'=>$this->input->post('product_buying_price'),
				'product_selling_price'=>$this->input->post('product_selling_price'),
				'product_status'=>$this->input->post('product_status'),
				'product_description'=>$this->input->post('product_description'),
				'product_balance'=>$this->input->post('product_balance'),
				'brand_id'=>$this->input->post('brand_id'),
				'category_id'=>$this->input->post('category_id'),
				'minimum_order_quantity'=>$this->input->post('minimum_order_quantity'),
				'maximum_purchase_quantity'=>$this->input->post('maximum_purchase_quantity'),
				'sale_price_type'=>$this->input->post('sale_price_type_id'),
				'modified_by'=>$this->session->userdata('vendor_id'),
				'product_image_name'=>$file_name,
				'product_thumb_name'=>$thumb_name
			);
			
		$this->db->where('product_id', $product_id);
		if($this->db->update('product', $data))
		{
			//save locations
			$this->save_product_locations($product_id);
			
			return TRUE;
		}
		else{
			return FALSE;
		}
	}
	
	/*
	*	Update an existing product
	*	@param string $image_name
	*	@param int $product_id
	*
	*/
	public function add_product_shipping($product_id)
	{
		$data = array(
				'product_shipping'=>1,
				'product_width'=>$this->input->post('product_width'),
				'product_height'=>$this->input->post('product_height'),
				'product_length'=>$this->input->post('product_length'),
				'product_weight'=>$this->input->post('product_weight')
			);
			
		$this->db->where('product_id', $product_id);
		if($this->db->update('product', $data))
		{
			return TRUE;
		}
		else{
			return FALSE;
		}
	}
	
	/*
	*	get a single product's details
	*	@param int $product_id
	*
	*/
	public function get_product($product_id, $vendor_id = NULL)
	{
		//retrieve all users
		$this->db->from('product, category, brand');
		$this->db->select('product.*, category.category_name, brand.brand_name');
		
		if($vendor_id == NULL)
		{
			$this->db->where('product.category_id = category.category_id AND product.brand_id = brand.brand_id AND product_id = '.$product_id);
		}
		
		else
		{
			$this->db->where('product.category_id = category.category_id AND product.brand_id = brand.brand_id AND product_id = '.$product_id.' AND product.created_by = '.$vendor_id);
		}
		$query = $this->db->get();
		
		return $query;
	}
	
	/*
	*	get a single product's details
	*	@param int $product_id
	*
	*/
	public function get_product_shipping($product_id, $vendor_id = NULL)
	{
		//retrieve all users
		$this->db->from('product');
		
		$this->db->where('product_id = '.$product_id.' AND product.created_by = '.$vendor_id);
		$query = $this->db->get();
		
		return $query;
	}
	public function recently_viewed_products()
	{
		//retrieve all users
		$this->db->from('product, category, brand');
		$this->db->select('product.*, category.category_name, brand.brand_name');
		$this->db->where('product.category_id = category.category_id AND product.brand_id = brand.brand_id AND product.product_status = 1');
		$this->db->order_by('product.last_viewed_date','desc');
		$query = $this->db->get('', 10);
		 
		return $query;
	}
	/*
	*	get a related product
	*	@param int $product_id
	*
	*/
	public function related_products($product_id)
	{
		//retrieve all users
		$this->db->from('product, category, brand');
		$this->db->select('product.*, category.category_name, brand.brand_name');
		$this->db->where('product.category_id = category.category_id AND product.brand_id = brand.brand_id AND product.category_id = (SELECT category_id FROM product WHERE product_id = '.$product_id.') AND product.product_id != '.$product_id);
		$query = $this->db->get('', 10);
		
		return $query;
	}
	
	/*
	*	Delete an existing product
	*	@param int $product_id
	*
	*/
	public function delete_product($product_id)
	{
		if($this->db->delete('product', array('product_id' => $product_id)))
		{
			return TRUE;
		}
		else{
			return FALSE;
		}
	}
	
	/*
	*	Activate a deactivated product
	*	@param int $product_id
	*
	*/
	public function activate_product($product_id)
	{
		$data = array(
				'product_status' => 1
			);
		$this->db->where('product_id', $product_id);
		
		if($this->db->update('product', $data))
		{
			return TRUE;
		}
		else{
			return FALSE;
		}
	}
	
	/*
	*	Deactivate an activated product
	*	@param int $product_id
	*
	*/
	public function deactivate_product($product_id)
	{
		$data = array(
				'product_status' => 0
			);
		$this->db->where('product_id', $product_id);
		
		if($this->db->update('product', $data))
		{
			return TRUE;
		}
		else{
			return FALSE;
		}
	}
	
	public function create_product_code($category_id)
	{
		//get category_details
		$query = $this->categories_model->get_category($category_id);
		
		if($query->num_rows() > 0)
		{
			$result = $query->result();
			$category_preffix =  $result[0]->category_preffix;
			
			//select product code
			$this->db->from('product');
			$this->db->select('MAX(product_code) AS number');
			$this->db->where("product_code LIKE '".$category_preffix."%'");
			$query = $this->db->get();
			
			if($query->num_rows() > 0)
			{
				$result = $query->result();
				$number =  $result[0]->number;
				$number++;//go to the next number
				
				if($number == 1){
					$number = $category_preffix."001";
				}
			}
			else{//start generating receipt numbers
				$number = $category_preffix."001";
			}
		}
		
		else
		{
			$number = '001';
		}
		
		return $number;
	}
	
	/*
	*	Save a product's gallery image
	*	@param int $product_id
	*	@param char $image
	*	@param char $thumb
	*
	*/
	public function save_gallery_file($product_id, $image, $thumb)
	{
		//save the image data to the database
		$data = array(
			'product_id' => $product_id,
			'product_image_name' => $image,
			'product_image_thumb' => $thumb
		);
		
		if($this->db->insert('product_image', $data))
		{
			return $this->db->insert_id();
		}
		else
		{
			return FALSE;
		}
	}
	
	/*
	*	get a single product's gallery images
	*	@param int $product_id
	*
	*/
	public function get_gallery_images($product_id)
	{
		//retrieve all users
		$this->db->from('product_image');
		$this->db->select('*');
		$this->db->where('product_id = '.$product_id);
		$query = $this->db->get();
		
		return $query;
	}
	
	/*
	*	delete a product's gallery images
	*	@param int $product_id
	*
	*/
	public function delete_gallery_images($product_id)
	{
		if($this->db->delete('product_image', array('product_id' => $product_id)))
		{
			return TRUE;
		}
		else{
			return FALSE;
		}
	}
	
	public function delete_gallery_image($product_image_id, $image_name, $thumb_name, $gallery_path)
	{
		//delete any other uploaded image
		if($this->file_model->delete_file($gallery_path."\\".$image_name, $gallery_path))
		{	
		}
		//delete any other uploaded thumbnail
		if($this->file_model->delete_file($gallery_path."\\".$thumb_name, $gallery_path))
		{
		}
		if($this->db->delete('product_image', array('product_image_id' => $product_image_id)))
		{
			return TRUE;
		}
		else{
			return FALSE;
		}
	}
	
	/**
	 * Get all the feature valuess of a feature
	 * Called when adding a new product
	 *
	 * @param int category_feature_id
	 *
	 * @return object
	 *
	 */
	function fetch_new_category_features($category_feature_id)
	{
		if(isset($_SESSION['name'.$category_feature_id]))
		{
			$total_features = count($_SESSION['name'.$category_feature_id]);
			
			if($total_features > 0)
			{
				$features = '';
				//var_dump($_SESSION['name'.$category_feature_id]);
				for($r = 0; $r < $total_features; $r++)
				{
					if(isset($_SESSION['name'.$category_feature_id][$r]))
					{
						$name = mysql_real_escape_string($_SESSION['name'.$category_feature_id][$r]);
						$quantity = $_SESSION['quantity'.$category_feature_id][$r];
						$price = $_SESSION['price'.$category_feature_id][$r];
						$image = '<img src="'. base_url().'assets/images/products/features/'.$_SESSION['thumb'.$category_feature_id][$r].'" alt="'.$name.'"/>';
						
						$features .= '
							<tr>
								<td>
									<a href="'.$r.'" class="delete_feature" id="'.$category_feature_id.'" onclick="return confirm(\'Do you want to delete '.$name.'?\');"><i class="icon-trash butn butn-danger"></i></a>
								</td>
								<td>'.$name.'</td>
								<td>'.$quantity.'</td>
								<td>'.$price.'</td>
								<td>'.$image.'</td>
							</tr>
						';
					}
				}
				
				return $features;
			}
		
			else{
				return NULL;
			}
		}
		
		else{
			return NULL;
		}
	}
	
	function add_new_features($category_feature_id, $product_id, $feature_name, $feature_quantity, $feature_price, $image_name = 'None', $thumb_name = 'None')
	{
		$data = array(
			"product_id" => $product_id,
			"feature_id" => $category_feature_id,
			"feature_value" => $feature_name,
			"image" => $image_name,
			"thumb" => $thumb_name,
			"price" => $feature_price,
			"quantity" => $feature_quantity,
		);
		
		if($this->db->insert('product_feature', $data))
		{
			return TRUE;
		}
		
		else
		{
			return FALSE;
		}
	}
	
	function update_features($product_feature_id, $product_id, $feature_name, $feature_quantity, $feature_price, $image_name = 'None', $thumb_name = 'None')
	{
		$data = array(
			"product_id" => $product_id,
			"feature_value" => $feature_name,
			"image" => $image_name,
			"thumb" => $thumb_name,
			"price" => $feature_price,
			"quantity" => $feature_quantity,
		);
		
		$this->db->where('product_feature_id', $product_feature_id);
		if($this->db->update('product_feature', $data))
		{
			return TRUE;
		}
		
		else
		{
			return FALSE;
		}
	}
	
	/**
	 * Get all the feature valuess of a feature
	 * Called when adding a new product
	 *
	 * @param int category_feature_id
	 *
	 * @return object
	 *
	 */
	function save_features($product_id)
	{
		$features = $this->features_model->all_features();
		
		if($features->num_rows() > 0)
		{
			$feature = $features->result();
			
			foreach($feature as $feat)
			{
				$feature_id = $feat->feature_id;
				
				if(isset($_SESSION['name'.$feature_id]))
				{
					$total_features = count($_SESSION['name'.$feature_id]);
					
					if($total_features > 0)
					{	
						for($r = 0; $r < $total_features; $r++)
						{
							if(isset($_SESSION['name'.$feature_id][$r]))
							{
								$name = $_SESSION['name'.$feature_id][$r];
								$quantity = $_SESSION['quantity'.$feature_id][$r];
								$price = $_SESSION['price'.$feature_id][$r];
								$image = $_SESSION['image'.$feature_id][$r];
								$thumb = $_SESSION['thumb'.$feature_id][$r];
								
								$data = array(
										'feature_id'=>$feature_id,
										'product_id'=>$product_id,
										'feature_value'=>$name,
										'quantity'=>$quantity,
										'price'=>$price,
										'image'=>$image,
										'thumb'=>$thumb
									);
									
								$this->db->insert('product_feature', $data);
							}
						}
					}
				}
			}
		}
		session_unset();
		return TRUE;
	}
	
	/*
	*	get a single product's features
	*	@param int $product_id
	*
	*/
	public function get_features($product_id)
	{
		if(isset($product_id))
		{
			//retrieve all users
			$this->db->from('product_feature');
			$this->db->select('*');
			$this->db->where('product_id = '.$product_id);
			$query = $this->db->get();
			
			return $query;
		}
		
		else
		{
			return FALSE;
		}
	}
	
	/*
	*	delete a product's features
	*	@param int $product_id
	*
	*/
	public function delete_features($product_id)
	{
		
		if($this->db->delete('product_feature', array('product_id' => $product_id)))
		{
			return TRUE;
		}
		else{
			return FALSE;
		}
	}
	
	/*
	*	delete a product feature
	*	@param int $feature_id
	*
	*/
	public function delete_product_features($product_feature_id)
	{
		
		if($this->db->delete('product_feature', array('product_feature_id' => $product_feature_id)))
		{
			return TRUE;
		}
		else{
			return FALSE;
		}
	}
	
	/*
	*	get a single product's features
	*	@param int $feature_id
	*
	*/
	public function get_product_feature($product_feature_id)
	{
		//retrieve all users
		$this->db->from('product_feature');
		$this->db->select('*');
		$this->db->where('product_feature_id = '.$product_feature_id);
		$query = $this->db->get();
		
		return $query;
	}
	
	/*
	*	delete a product feature
	*	@param int $product_feature_id
	*
	*/
	public function delete_product_feature($product_feature_id)
	{
		
		if($this->db->delete('product_feature', array('product_feature_id' => $product_feature_id)))
		{
			return TRUE;
		}
		else{
			return FALSE;
		}
	}
	
	/*
	*	Retrieve latest products
	*
	*/
	public function get_latest_products()
	{
		$this->db->select('product.*, category.category_name, brand.brand_name')->from('product, category, brand')->where("product.product_status = 1 AND product.category_id = category.category_id AND product.brand_id = brand.brand_id AND product.product_balance > 0")->order_by("created", 'DESC');
		$query = $this->db->get('',8);
		
		return $query;
	}
	

	/*
	*	Retrieve latest products
	*
	*/
	public function get_discount_types()
	{
		$this->db->select('*')->from('discount_type')->where("discount_type_status = 1")->order_by("discount_type_id", 'ACS');
		$query = $this->db->get('',12);
		
		return $query;
	}
	/*
	*	Retrieve featured products
	*
	*/
	public function get_featured_products()
	{
		$this->db->select('product.*, category.category_name, brand.brand_name')->from('product, category, brand')->where("product.product_status = 1 AND product.category_id = category.category_id AND product.brand_id = brand.brand_id AND product.featured = 1 AND product.product_balance > 0")->order_by("created", 'DESC');
		$query = $this->db->get('',8);
		
		return $query;
	}
	/*
	*	Retrieve popular products
	*
	*/
	public function get_popular_products()
	{
		$this->db->select('product.*, category.category_name, brand.brand_name')->from('product, category, brand')->where("product.product_status = 1 AND product.category_id = category.category_id AND product.brand_id = brand.brand_id AND product.featured = 1")->order_by("clicks", 'DESC');
		$query = $this->db->get('',4);
		
		return $query;
	}
	
	/*
	*	Retrieve max product price
	*
	*/
	public function get_max_product_price()
	{
		$this->db->select('MAX(product_selling_price) AS price')->from('product')->where("product_status = 1");
		$query = $this->db->get();
		$result = $query->row();
		
		return $result->price;
	}
	
	/*
	*	Retrieve min product price
	*
	*/
	public function get_min_product_price()
	{
		$this->db->select('MIN(product_selling_price) AS price')->from('product')->where("product_status = 1");
		$query = $this->db->get();
		$result = $query->row();
		
		return $result->price;
	}
	
	/*
	*	get a similar products
	*	@param int $product_id
	*
	*/
	public function get_similar_products($product_id)
	{
		//retrieve all users
		$this->db->from('product');
		$this->db->select('*');
		$this->db->where('(category_id = (SELECT category_id FROM product WHERE product_id = '.$product_id.')) AND (product_id <> '.$product_id.')');
		$query = $this->db->get('', 10);
		
		return $query;
	}
	
	public function update_clicks($product_id)
	{
		//get clicks);
		$this->db->select('clicks');
		$this->db->where('product_id', $product_id);
		$query = $this->db->get('product');
		
		$row = $query->row();
		$clicks = $row->clicks;
		
		//increment clicks
		$clicks++;
		
		//save clicks
		$data = array(
				'clicks'=>$clicks,
				'last_viewed_date'=>date('Y-m-d')
			);
			
		$this->db->where('product_id', $product_id);
		if($this->db->update('product', $data))
		{
			return TRUE;
		}
		else{
			return FALSE;
		}
	}
	
	/*
	*	Export Transactions
	*
	*/
	function export_products()
	{
		$this->load->library('excel');
		
		$where = 'product.category_id = category.category_id AND product.brand_id = brand.brand_id AND product.created_by = '.$this->session->userdata('vendor_id');
		$table = 'product, category, brand';
		$this->db->select('product.clicks, product.minimum_order_quantity, product.maximum_purchase_quantity, product.sale_price, product.featured, product.product_id, product.product_name, product.product_buying_price, product.product_selling_price, product.product_status, product.product_description, product.product_code, product.product_balance, product.brand_id, product.category_id, product.created, product.created_by, product.last_modified, product.modified_by, product.product_thumb_name, product.product_image_name, category.category_name, brand.brand_name');
		
		$this->db->where($where);		
		$visits_query = $this->db->get($table);
		//var_dump($visits_query->result());die();
		$title = 'Export Products '.date('jS M Y H:i a');
		$count=1;
		$row_count=0;
		
		/*
			-----------------------------------------------------------------------------------------
			Document Header
			-----------------------------------------------------------------------------------------
		*/
		$report[$row_count][0] = '#';
		$report[$row_count][1] = 'Code';
		$report[$row_count][2] = 'Category';
		$report[$row_count][3] = 'Brand';
		$report[$row_count][4] = 'Product Name';
		$report[$row_count][5] = 'Product Description';
		$report[$row_count][6] = 'Buying Price';
		$report[$row_count][7] = 'Selling Price';
		$report[$row_count][8] = 'Sale Price (% off)';
		$report[$row_count][9] = 'Balance';
		$report[$row_count][10] = 'Featured';
		$report[$row_count][11] = 'Views';
		$report[$row_count][12] = 'Maximum Purchase Quantity';
		$report[$row_count][13] = 'Minimum Order Quantity';
		$report[$row_count][14] = 'Status';
		$row_count++;
		
		foreach ($visits_query->result() as $row)
		{
			$sale_price = $row->sale_price;
			$featured = $row->featured;
			$product_id = $row->product_id;
			$product_name = $row->product_name;
			$product_buying_price = $row->product_buying_price;
			$product_selling_price = $row->product_selling_price;
			$product_status = $row->product_status;
			$product_description = $row->product_description;
			$product_code = $row->product_code;
			$product_balance = $row->product_balance;
			$brand_id = $row->brand_id;
			$category_id = $row->category_id;
			$created = $row->created;
			$created_by = $row->created_by;
			$last_modified = $row->last_modified;
			$modified_by = $row->modified_by;
			$image = $row->product_image_name;
			$thumb = $row->product_thumb_name;
			$category_name = $row->category_name;
			$brand_name = $row->brand_name;
			$clicks = $row->clicks;
			$minimum_order_quantity = $row->minimum_order_quantity;
			$maximum_purchase_quantity = $row->maximum_purchase_quantity;
			//$query = $this->products_model->get_gallery_images($product_id);
			
			if($product_status == 1)
			{
				$status = 'Active';
			}
			else
			{
				$status = 'Disabled';
			}
			
			if($featured == 1)
			{
				$featured = 'Yes';
			}
			else
			{
				$featured = 'No';
			}
			
			$report[$row_count][0] = $count;
			$report[$row_count][1] = $product_code;
			$report[$row_count][2] = $category_name;
			$report[$row_count][3] = $brand_name;
			$report[$row_count][4] = $product_name;
			$report[$row_count][5] = $product_description;
			$report[$row_count][6] = $product_buying_price;
			$report[$row_count][7] = $product_selling_price;
			$report[$row_count][8] = $sale_price;
			$report[$row_count][9] = $product_balance;
			$report[$row_count][10] = $featured;
			$report[$row_count][11] = $clicks;
			$report[$row_count][12] = $maximum_purchase_quantity;
			$report[$row_count][13] = $minimum_order_quantity;
			$report[$row_count][14] = $status;
			
			$row_count++;
			$count++;	
		}
		
		//create the excel document
		$this->excel->addArray ( $report );
		$this->excel->generateXML ($title);
	}
	
	/*
	*	Import Template
	*
	*/
	function import_template()
	{
		$this->load->library('excel');
		
		$title = 'In Store Look Import Template V1';
		$count=1;
		$row_count=0;
		
		$report[$row_count][0] = 'Category (Is required & Must be from the list of your categories)';
		$report[$row_count][1] = 'Brand';
		$report[$row_count][2] = 'Product Name';
		$report[$row_count][3] = 'Product Description';
		$report[$row_count][4] = 'Buying Price (Must be numeric, $)';
		$report[$row_count][5] = 'Selling Price (Must be numeric, $)';
		$report[$row_count][6] = 'Sale Price (% off. Must be numeric)';
		$report[$row_count][7] = 'Balance (Must be numeric)';
		$report[$row_count][8] = 'Featured (Yes or No)';
		$report[$row_count][9] = 'Maximum Purchase Quantity (Must be numeric)';
		$report[$row_count][10] = 'Minimum Order Quantity (Must be numeric)';
		$report[$row_count][11] = 'Status (Active or Inactive)';
		$row_count++;
		
		//create the excel document
		$this->excel->addArray ( $report );
		$this->excel->generateXML ($title);
	}
	
	public function vendor_categories()
	{
		$where = 'category_status = 1';
		$table = 'category';
		
		$this->db->where($where);
		$this->db->order_by('category_name');
		$visits_query = $this->db->get($table);
		
		return $visits_query;
	}
	
	/*
	*	Import Categories
	*
	*/
	function import_categories()
	{
		//get vendors categories
		$visits_query = $this->vendor_categories();
		
		$this->load->library('excel');
		//var_dump($visits_query->result());die();
		$title = $this->session->userdata('vendor_name').' Product Categories';
		$count=1;
		$row_count=0;
		
		
		/*
			-----------------------------------------------------------------------------------------
			Document Header
			-----------------------------------------------------------------------------------------
		*/
		
		$report[$row_count][0] = '#';
		$report[$row_count][2] = 'Category';
		$row_count++;
		
		foreach ($visits_query->result() as $row)
		{
			$category_name = $row->category_name;
			
			$report[$row_count][0] = $count;
			$report[$row_count][2] = $category_name;
			
			$row_count++;
			$count++;	
		}
		
		//create the excel document
		$this->excel->addArray ( $report );
		$this->excel->generateXML ($title);
	}
	
	public function import_csv_products($upload_path)
	{
		//load the file model
		$this->load->model('admin/file_model');
		/*
			-----------------------------------------------------------------------------------------
			Upload csv
			-----------------------------------------------------------------------------------------
		*/
		$response = $this->file_model->upload_csv($upload_path, 'import_csv');
		
		if($response['check'])
		{
			$file_name = $response['file_name'];
			
			$array = $this->file_model->get_array_from_csv($upload_path.'/'.$file_name);
			//var_dump($array); die();
			$response2 = $this->sort_csv_data($array);
		
			if($this->file_model->delete_file($upload_path."\\".$file_name, $upload_path))
			{
			}
			
			return $response2;
		}
		
		else
		{
			$this->session->set_userdata('error_message', $response['error']);
			return FALSE;
		}
	}
	
	public function sort_csv_data($array)
	{
		//get vendors categories
		$visits_query = $this->vendor_categories();
		
		foreach ($visits_query->result() as $row)
		{
			$category_name = $row->category_name;
		}
		
		//count total rows
		$total_rows = count($array);
		$total_columns = count($array[0]);//var_dump($array);die();
		
		//if products exist in array
		if(($total_rows > 0) && ($total_columns == 12))
		{
			$items['modified_by'] = $this->session->userdata('vendor_id');
			$items['created_by'] = $this->session->userdata('vendor_id');
			$response = '
				<table class="table table-condensed table-striped table-hover">
					<tr>
						<th>#</th>
						<th>Category</th>
						<th>Brand</th>
						<th>Product name</th>
						<th>Product description</th>
						<th>Buying price</th>
						<th>Selling price</th>
						<th>Sale price</th>
						<th>Balance</th>
						<th>Featured</th>
						<th>Maximum purchase quantity</th>
						<th>Minimum order quantity</th>
						<th>Status</th>
						<th>Comment</th>
					</tr>
			';
			
			//retrieve the data from array
			for($r = 1; $r < $total_rows; $r++)
			{
				$category_name = $array[$r][0];
				$brand_name = $array[$r][1];
				$items['product_name'] = ucwords(strtolower($array[$r][2]));
				$items['product_description'] = $array[$r][3];
				$items['product_buying_price'] = $array[$r][4];
				$items['product_selling_price'] = $array[$r][5];
				$items['sale_price'] = $array[$r][6];
				$items['product_balance'] = $array[$r][7];
				$featured = $array[$r][8];
				$items['maximum_purchase_quantity'] = $array[$r][9];
				$items['minimum_order_quantity'] = $array[$r][10];
				$product_status = $array[$r][11];
				$items['created'] = date('Y-m-d H:i:s');
				$comment = '';
				
				//get category_id
				$category_id = $this->get_category_id($category_name);
				
				//only continue if category_id exists
				if(!empty($category_id))
				{
					$class = 'success';
					
					$items['category_id'] = $category_id;
					
					//generate product code
					$items['product_code'] = $this->create_product_code($category_id);
					
					//get brand_id
					$brand_id = $this->get_brand_id($brand_name);
					
					//validate buying price
					if((!is_numeric($items['product_buying_price'])) && (!empty($items['product_buying_price'])))
					{
						$class = 'warning';
						$comment .= '<br/>The buying price is not numeric. Product added with \'0\' as the buying price';
						$items['product_buying_price'] = 0;
					}
					
					else if(empty($items['product_buying_price']))
					{
						$items['product_buying_price'] = 0;
					}
					
					//validate selling price
					if((!is_numeric($items['product_selling_price'])) && (!empty($items['product_selling_price'])))
					{
						$class = 'warning';
						$comment .= '<br/>The selling price is not numeric. Product added with \'0\' as the selling price';
						$items['product_selling_price'] = 0;
					}
					
					else if(empty($items['product_selling_price']))
					{
						$items['product_selling_price'] = 0;
					}
					
					//validate sale price
					if((!is_numeric($items['sale_price'])) && (!empty($items['sale_price'])))
					{
						$class = 'warning';
						$comment .= '<br/>The sale price is not numeric. Product added with \'0\' as the sale price';
						$items['sale_price'] = 0;
					}
					
					else if(empty($items['sale_price']))
					{
						$items['sale_price'] = 0;
					}
					
					//validate product balance
					if(!is_numeric($items['product_balance']))
					{
						$class = 'warning';
						$comment .= '<br/>The product balance is not numeric. Product added with \'0\' as the balance';
						$items['product_balance'] = 0;
					}
					
					//validate featured
					if(empty($featured))
					{
						$items['featured'] = 0;
					}
					else
					{
						if($featured == 'Yes')
						{
							$items['featured'] = 1;
						}
						else
						{
							$items['featured'] = 0;
						}
					}
					
					//validate maximum purchase quantity
					if((!is_numeric($items['maximum_purchase_quantity'])) && (!empty($items['maximum_purchase_quantity'])))
					{
						$class = 'warning';
						$comment .= '<br/>The maximum purchase quantity is not numeric. Product added with \'0\' as the maximum purchase quantity';
						$items['maximum_purchase_quantity'] = 0;
					}
					
					else if(empty($items['maximum_purchase_quantity']))
					{
						$items['maximum_purchase_quantity'] = 0;
					}
					
					//validate maximum purchase quantity
					if((!is_numeric($items['minimum_order_quantity'])) && (!empty($items['minimum_order_quantity'])))
					{
						$class = 'warning';
						$comment .= '<br/>The minimum order quantity is not numeric. Product added with \'0\' as the minimum order quantity';
						$items['minimum_order_quantity'] = 0;
					}
					
					else if(empty($items['minimum_order_quantity']))
					{
						$items['minimum_order_quantity'] = 0;
					}
					
					//validate product status
					if(empty($product_status))
					{
						$items['product_status'] = 0;
					}
					else
					{
						if($product_status == 'Active')
						{
							$items['product_status'] = 1;
						}
						else
						{
							$items['product_status'] = 0;
						}
					}
					
					//save product in the db
					if($this->db->insert('product', $items))
					{
						//add product location
						$data['product_id'] = $this->db->insert_id();
						$data['product_location_status'] = 1;
						$data['surburb_id'] = $this->vendor_model->get_vendor_surburb($this->session->userdata('vendor_id'));
						if($this->db->insert('product_location', $data))
						{
							$comment .= '<br/>Product successfully added to the database';
						}
						else
						{
							$comment .= '<br/>Product successfully added to the database but the product\'s location was not added';
						}
					}
					
					else
					{
						$comment .= '<br/>Internal error. Could not add product to the database. Please contact the site administrator. Product code '.$items['product_code'];
					}
				}
				
				else
				{
					$class = 'danger';
					$comment = 'Unable to save product. Category not available. Please download the list of available categories <a href="'.site_url().'vendor/import-categories">here.</a>';
				}
				
				$response .= '
					<tr class="'.$class.'">
						<td>'.$r.'</td>
						<td>'.$category_name.'</td>
						<td>'.$brand_name.'</td>
						<td>'.$items['product_name'].'</td>
						<td>'.implode(' ', array_slice(explode(' ', $items['product_description']), 0, 10)).'...</td>
						<td>'.$items['product_buying_price'].'</td>
						<td>'.$items['product_selling_price'].'</td>
						<td>'.$items['sale_price'].'</td>
						<td>'.$items['product_balance'].'</td>
						<td>'.$featured.'</td>
						<td>'.$items['maximum_purchase_quantity'].'</td>
						<td>'.$items['minimum_order_quantity'].'</td>
						<td>'.$product_status.'</td>
						<td>'.$comment.'</td>
					</tr>
				';
			}
			
			$response .= '</table>';
			
			$return['response'] = $response;
			$return['check'] = TRUE;
		}
		
		//if no products exist
		else
		{
			$return['response'] = 'Product data not found';
			$return['check'] = FALSE;
		}
		
		return $return;
	}
	
	public function get_category_id($category_name)
	{
		$this->db->where('category_name = \''.$category_name.'\'');
		$query = $this->db->get('category');
		
		if($query->num_rows() > 0)
		{
			$row = $query->row();
			$category_id = $row->category_id;
		}
		
		else
		{
			$category_id = '';
		}
		
		return $category_id;
	}
	
	public function get_brand_id($brand_name)
	{
		//if brand was added
		if(!empty($brand_name))
		{
			$this->db->where('brand_name = \''.$brand_name.'\'');
			$query = $this->db->get('brand');
			
			//if brand exists
			if($query->num_rows() > 0)
			{
				$row = $query->row();
				$brand_id = $row->brand_id;
			}
			
			//if brand doesn't exist add a new brand
			else
			{
				$data['brand_name'] = ucwords(strtolower($brand_name));
				$data['brand_status'] = 1;
				
				$this->db->insert('brand', $data);
				$brand_id = $this->db->insert_id();
			}
		}
		
		//if brand wasn't added
		else
		{
			$brand_id = 0;
		}
		
		return $brand_id;
	}
	/*
	*	Add a new product
	*	@param string $image_name
	*
	*/
	//public function add_product($image_name, $thumb_name)
	public function add_product_bundle($image_name, $thumb_name)
	{
		
		
		$data = array(
				'product_bundle_name'=>ucwords(strtolower($this->input->post('product_bundle_name'))),
				'product_bundle_status'=>$this->input->post('product_bundle_status'),
				'product_bundle_price'=>$this->input->post('product_bundle_price'),
				'product_bundle_description'=>$this->input->post('product_bundle_description'),
				'created_on'=>date('Y-m-d H:i:s'),
				'created_by'=>$this->session->userdata('vendor_id'),
				'last_modified_by'=>$this->session->userdata('vendor_id'),
				'product_bundle_thumb_name'=>$thumb_name,
				'product_bundle_image_name'=>$image_name
			);
			
		if($this->db->insert('product_bundle', $data))
		{
			return $this->db->insert_id();
		}
		else{
			return FALSE;
		}
	}
	/*
	*	Retrieve all products bundle items
	*	@param string $table
	* 	@param string $where
	*
	*/
	public function get_all_product_bundle_items($table, $where, $per_page, $page, $limit = NULL, $order_by = 'created', $order_method = 'DESC')
	{
		$this->db->from($table);
		$this->db->select('product_bundle_item.product_bundle_item_id,product.sale_price,product.product_thumb_name,product_bundle_item.product_bundle_item_status, product.product_image_name, product.featured, product.product_buying_price, product.product_selling_price, product.product_status, product.product_description, product.product_balance, product_bundle.product_bundle_id, product_bundle.product_bundle_name, product.product_id, product.product_name, product.product_code, product.brand_id, product.category_id, product_bundle_item.created, product_bundle_item.created_by, product_bundle_item.last_modified, product_bundle_item.modified_by, product_bundle.product_bundle_thumb_name, product_bundle.product_bundle_image_name, category.category_name, brand.brand_name');
		$this->db->where($where);
		$this->db->order_by($order_by, $order_method);
		
		if(isset($limit))
		{
			$query = $this->db->get('');
		}
		
		else
		{
			$query = $this->db->get('');
		}
		
		return $query;
	}

	public function get_product_bundle_details($bundle_id)
	{
		
		$this->db->select('*');
		$this->db->where('product_bundle.product_bundle_id ='.$bundle_id);
		$this->db->order_by('product_bundle_id', 'DESC');
		$query = $this->db->get('product_bundle');
				
		return $query;
	}
	public function add_product_to_bundle($product_id,$bundle_id)
	{
		$data = array(
				'product_bundle_id'=>$bundle_id,
				'product_id'=>$product_id,
				'created'=>date('Y-m-d H:i:s'),
				'created_by'=>$this->session->userdata('vendor_id'),
				'modified_by'=>$this->session->userdata('vendor_id'),
				'product_bundle_item_status'=>1
			);
			
		if($this->db->insert('product_bundle_item', $data))
		{
			return $this->db->insert_id();
		}
		else{
			return FALSE;
		}
	}
	public function check_product_if_exists_in_bundle($product_id,$bundle_id)
	{
		$this->db->where('product_id = '.$product_id.' AND product_bundle_id = '.$bundle_id );
		$query = $this->db->get('product_bundle_item');
		
		if($query->num_rows() > 0)
		{
			return TRUE;
		}
		
		else
		{
			return FALSE;
		}
		
	}
	/*
	*	Activate a deactivated product
	*	@param int $product_id
	*
	*/
	public function activate_product_from_bundle($product_bundle_item_id)
	{
		$data = array(
				'product_bundle_item_status' => 1
			);
		$this->db->where('product_bundle_item_id', $product_bundle_item_id);
		
		if($this->db->update('product_bundle_item', $data))
		{
			return TRUE;
		}
		else{
			return FALSE;
		}
	}
	
	/*
	*	Deactivate an activated product
	*	@param int $product_id
	*
	*/
	public function deactivate_product_from_bundle($product_bundle_item_id)
	{
		$data = array(
				'product_bundle_item_status' => 0
			);
		$this->db->where('product_bundle_item_id', $product_bundle_item_id);
		
		if($this->db->update('product_bundle_item', $data))
		{
			return TRUE;
		}
		else{
			return FALSE;
		}
	}
	
	/*
	*	Retrieve top sellers
	*
	*/
	public function get_top_sellers()
	{
		$this->db->select('vendor.vendor_id, vendor.vendor_store_name, count(vendor.vendor_id) AS total_occurences')->from('product, vendor, order_item')->where("product.product_id = order_item.product_id AND product.created_by = vendor.vendor_id")->order_by("total_occurences", 'DESC');
		$query = $this->db->get('',10);
		
		return $query;
	}
	
	/*
	*	Retrieve top sellers
	*
	*/
	public function get_top_sellers2()
	{
		$this->db->select('vendor.vendor_id, vendor.vendor_store_name')->from('vendor')->where("vendor.vendor_status = 1")->order_by("vendor_store_name", 'ASC');
		$query = $this->db->get('',10);
		
		return $query;
	}
	
	public function get_product_locations($product_id)
	{
		$where = 'product_location.product_location_status = 1 AND product_location.surburb_id = surburb.surburb_id AND product_location.product_id = '.$product_id;
		$this->db->where($where);
		$this->db->select('product_location.*, surburb.post_code');
		$query = $this->db->get('product_location, surburb');
		
		return $query;
	}
	
	public function duplicate_features($old_product_id, $product_id)
	{
		$features = $this->products_model->get_features($old_product_id);
		if($features->num_rows() > 0)
		{
			$feat = $features->result();
			
			foreach($feat as $f)
			{
				$feature_id = $f->feature_id;
				$name = $f->feature_value;
				$price = $f->price;
				$quantity = $f->quantity;
				$image = $f->image;
				$thumb = $f->thumb;
				
				$data = array(
						'feature_id'=>$feature_id,
						'product_id'=>$product_id,
						'feature_value'=>$name,
						'quantity'=>$quantity,
						'price'=>$price,
						'image'=>$image,
						'thumb'=>$thumb
					);
					
				$this->db->insert('product_feature', $data);
			}
		}	
	}
	
	public function duplicate_gallery_images($old_product_id, $product_id)
	{
		$gallery_images = $this->products_model->get_gallery_images($old_product_id);
		if($gallery_images->num_rows() > 0)
		{
			$feat = $gallery_images->result();
			
			foreach($feat as $f)
			{
				$image = $f->product_image_name;
				$thumb = $f->product_image_thumb;
				
				$data = array(
						'product_image_name'=>$image,
						'product_image_thumb'=>$thumb
					);
					
				$this->db->insert('product_image', $data);
			}
		}	
	}
	
	public function duplicate_locations($old_product_id, $product_id)
	{
		$locations = $this->products_model->get_product_locations($old_product_id);
		if($locations->num_rows() > 0)
		{
			$feat = $locations->result();
			
			foreach($feat as $f)
			{
				$surburb_id = $f->surburb_id;
				
				$data = array(
						'surburb_id'=>$surburb_id,
						'product_location_status'=>1
					);
					
				$this->db->insert('product_location', $data);
			}
		}	
	}
	
	public function image_display($base_path, $location, $image_name = NULL)
	{
		$default_image = 'http://placehold.it/300x300&text=ISL';
		$file_path = $base_path.'/'.$image_name;
		//echo $file_path.'<br/>';
		
		//Check if image was passed
		if($image_name != NULL)
		{
			if(!empty($image_name))
			{
				if((file_exists($file_path)) && ($file_path != $base_path.'\\'))
				{
					return $location.$image_name;
				}
				
				else
				{
					return $default_image;
				}
			}
			
			else
			{
				return $default_image;
			}
		}
		
		else
		{
			return $default_image;
		}
	}
	
	public function get_product_discount_price($product_price, $sale_price, $sale_price_type)
	{
		if($sale_price_type == 2)
		{
			$product_price = ((100 - $sale_price)/100) * $product_price;
		}
		else
		{
			$product_price = $product_price - $sale_price;
		}
		
		return $product_price;
	}
	
	public function get_product_features2($product_feature_id)
	{
		$this->db->select('product_feature.*, feature.feature_name');
		$this->db->where('product_feature.product_feature_id = '.$product_feature_id.' AND product_feature.feature_id = feature.feature_id');
		$query = $this->db->get('product_feature, feature');
		return $query;
	}
	
	public function get_product_features($product_id)
	{
		$this->db->select('product_feature.*, feature.feature_name');
		$this->db->where('product_feature.product_id = '.$product_id.' AND product_feature.feature_id = feature.feature_id');
		$this->db->order_by('feature.feature_name ASC, product_feature.image');
		$query = $this->db->get('product_feature, feature');
		return $query;
	}
	
	public function get_feature_names($product_id)
	{
		$this->db->select('feature.feature_id, feature.feature_name');
		$this->db->where('product_feature.product_id = '.$product_id.' AND product_feature.feature_id = feature.feature_id');
		$this->db->group_by("feature.feature_name");
		$query = $this->db->get('product_feature, feature');
		return $query;
	}
	
	function get_tiny_url($url) {
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, "http://tinyurl.com/api-create.php?url=".$url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$tinyurl = curl_exec($ch);
		curl_close($ch);
		//$tinyurl = file_get_contents("http://tinyurl.com/api-create.php?url=".$url);
		return $tinyurl;
	}
	
	public function update_shipping_method($product_id, $product_shipping)
	{
		$data['product_shipping'] = $product_shipping;
		
		$this->db->where('product_id', $product_id);
		if($this->db->update('product', $data))
		{
			return TRUE;
		}
		
		else
		{
			return FALSE;
		}
	}
	public function add_fixed_rate($product_id)
	{
		$data = array(
				'product_shipping'=>2,
				'product_rate'=>$this->input->post('product_fixed_rate')
			);
			
		$this->db->where('product_id', $product_id);
		if($this->db->update('product', $data))
		{
			return TRUE;
		}
		else{
			return FALSE;
		}
	}
}
?>