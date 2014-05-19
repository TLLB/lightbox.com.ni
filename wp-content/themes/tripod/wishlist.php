<?php
/**
* Template Name: Wishlist
*
* @package WordPress
*/
 
 
 get_header(); 


 global $woocommerce, $product, $post;

?>


<section id="main">
    <div class="main-container woocommerce">    
        <div class="row">
        	<div class="twelve columns">
		        <?php if (function_exists('show_messages')) {
		        	$woocommerce->show_messages();
		        } ?>
				<h2 class="post-title"><?php _e('My wishlist', 'cosmotheme'); ?> </h2>
				<table class=" cart wishlist_table" >
					<thead>
						<tr>
							<th class="product-thumbnail"><span class="nobr"><?php _e('Product Name', 'cosmotheme'); ?></span></th>
							<th class="product-name">&nbsp;</th>
							<th class="product-price"><span class="nobr"><?php _e('Unit Price', 'cosmotheme'); ?></span></th>
							<th class="stock-status"><span class="nobr"><?php _e('Stock Status', 'cosmotheme'); ?></span></th>
							<th class="product-actions"><span class="nobr"><?php _e('Actions', 'cosmotheme'); ?></span></th>
							<th class="product-remove">&nbsp;</th>
						</tr>
					</thead>
					<tbody>
						<?php
						//global $wp_query;
						if (isset($_COOKIE['cookie_products']) && $_COOKIE['cookie_products'] != '') {
							$cookies = explode(",", $_COOKIE['cookie_products']);
							foreach ($cookies as $index => $value){
								$cookies[$index] = (int)$value; 
							}					    

						$wp_query = new WP_Query(array('post_status' => 'any', 'post_type' => 'product', 'post__in' => $cookies ));

						if ( $wp_query->have_posts()) {
							while ( $wp_query->have_posts() ) :
								$wp_query->the_post();
								$product = get_product($post->ID);

								$product_info = $product->post;	
								
									?>
									<tr id="rowid_<?php echo $product_info -> ID; ?>" >
										<td class="product-thumbnail">
											<a href="<?php echo esc_url( get_permalink(apply_filters('woocommerce_in_cart_product', $product->ID)) ); ?>">
											<?php 
												echo $product->get_image();
											?>
											</a>
										</td>
										<td class="product-name">
											<a href="<?php echo esc_url( get_permalink(apply_filters('woocommerce_in_cart_product', $product->ID)) ); ?>"><?php echo $product_info -> post_title; ?></a>

										</td>
										<td class="product-price">
				                                <?php
												
												if (get_option('woocommerce_display_cart_prices_excluding_tax')==true) :
													$price = apply_filters('woocommerce_cart_item_price_html', woocommerce_price( $proproductduct_obj->get_price_excluding_tax() ), $product, '' ); 
												else :
													$price = apply_filters('woocommerce_cart_item_price_html', woocommerce_price( $product->get_price() ), $product, '' ); 
												endif;

										        if($price){
													echo $price; 
												}
										?></td>
										<td class="stock-status">
											<?php
											$availability = $product->get_availability();
											$stock_status = $availability['class'];
											
											if($stock_status == 'out-of-stock' ) {
												$stock_status="Out";
												_e('Out Of Stock', 'cosmotheme');
											} else {
												$stock_status="In";
													_e('In Stock', 'cosmotheme');
											}
											
											?>
										</td>
										<td class="product-actions">	
											<?php get_template_part('/woocommerce/loop/add-to-cart'); ?>						
										</td>
										<td class="product-remove"><div class="ajax-loading-img"></div><a href="javascript:void(0)" onClick="remove_from_wishlist('<?php echo $product_info -> ID; ?>');" class="remove product_<?php echo $product_info -> ID; ?>" title="<?php _e('Remove this item', 'cosmotheme'); ?>">&times;</a></td>
									</tr>
				   						
					  		<?php endwhile; ?>
					  	<?php } else { 
							  		if (get_page_by_title( 'shop' )) {
					                    $shop_page = get_page_by_title( 'shop' );
					                    $url = __('Browse the ', 'cosmotheme') .'<a href="'. get_permalink( $shop_page -> ID) .'">'. __('shop page', 'cosmotheme').'</a>'; 
					                }else{
					                    $url = '';
					                }
					  		?>
								<tr>
									<td colspan="6"><center><?php _e('No products were added to wish list. ' , 'cosmotheme'); echo $url; ?></center></td>
								</tr>	
					  	<?php } 
					  	} else { 						
						  		if (get_page_by_title( 'shop' )) {
				                    $shop_page = get_page_by_title( 'shop' );
				                    $url = __('Browse the ', 'cosmotheme') .'<a href="'. get_permalink( $shop_page -> ID) .'">'. __('shop page', 'cosmotheme').'</a>'; 
				                }else{
				                    $url = '';
				                }
						?>
								<tr>
									<td colspan="6"><center><?php _e('No products were added to wish list. ' , 'cosmotheme'); echo $url; ?></center></td>
								</tr>						  	
					  	<?php } ?>
					</tbody>
				</table>
			</div>
		</div>
    </div>
</section>  

<?php get_footer(); ?>