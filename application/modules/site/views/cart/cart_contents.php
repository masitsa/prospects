												<table class="shop_table cart">
														<thead>
															<tr>
																<th class="product-remove">&nbsp;
																	
																</th>
																<th class="product-thumbnail">&nbsp;
																	
																</th>
																<th class="product-name">
																	Item
																</th>
																<th class="product-price">
																	Price
																</th>
																<th class="product-quantity">
																	Quantity
																</th>
																<th class="product-subtotal">
																	Total
																</th>
															</tr>
														</thead>
														<tbody>
                <?php
				$grand_total = 0;
                foreach ($this->cart->contents() as $items): 

					$cart_category_id = $items['id'];
					
					//get product details
					$product_details = $this->categories_model->get_category($cart_category_id);
					
					if($product_details->num_rows() > 0)
					{
						$category = $product_details->row();
						
						$category_thumb = $category->category_image_name;
						$image = $this->site_model->image_display($categories_path, $categories_location, $category_thumb);
						
						$total = $items['qty']*$items['price'];
						$grand_total += $total;
						$total = number_format($total, 0);
						
						echo '
							<tr class="cart_table_item">
                                <td class="product-remove">
                                    <a title="Remove this item" class="remove" href="'.site_url().'site/cart/delete_cart_item/'.$items['rowid'].'/2">
                                        <i class="fa fa-times"></i>
                                    </a>
                                </td>
                                <td class="product-thumbnail">
                                    <a href="#">
                                        <img src="'.$image.'" class="img-responsive"/>
                                    </a>
                                </td>
                                <td class="product-name">
                                    <a href="#">'.$items['name'].'</a>
                                </td>
                                <td class="product-price">
                                    <span class="amount">Kes '.number_format($items['price'], 0).'</span>
                                </td>
                                <td class="product-quantity">
                                    <form enctype="multipart/form-data" method="post" class="cart" action="'.site_url().'basket/update-quantity/'.$items['rowid'].'">
                                        <div class="quantity">
											<input type="text" class="input-text qty text" title="Qty" value="'.$items['qty'].'" name="quantity" min="1" step="1">
                                            <button type="submit" class="plus"><i class="fa fa-refresh"></i></button>
">
                                        </div>
                                    </form>
                                </td>
                                <td class="product-subtotal">
                                    <span class="amount">Kes '.$total.'</span>
                                </td>
                            </tr>
						';
					}
		
				endforeach; 
				?>
                										</tbody>
                                                    </table>