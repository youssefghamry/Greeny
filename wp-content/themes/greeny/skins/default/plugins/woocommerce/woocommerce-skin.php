<?php
/* WooCommerce skin-specific functions
------------------------------------------------------------------------------- */


/* Skin-specific WooCommerce utils
------------------------------------------------------------------- */

// Theme init priorities:
// 3 - add/remove Theme Options elements
if ( ! function_exists( 'greeny_woocommerce_skin_theme_setup3' ) ) {
	add_action( 'after_setup_theme', 'greeny_woocommerce_skin_theme_setup3', 3 );
	function greeny_woocommerce_skin_theme_setup3() {
		if ( greeny_exists_woocommerce() ) {
			// Panel 'Shop' with skin-specific options
			// Add color_sheme
			greeny_storage_set_array_after( 'options', 'shop_general', greeny_options_get_list_cpt_options_color( 'shop', esc_html__( 'Product', 'greeny' ) ) );
			// Hide 'shop_mode'
			greeny_storage_set_array2( 'options', 'shop_mode', 'type', 'hidden' );
			// Hide 'single_product_gallery_thumbs'
			greeny_storage_set_array2( 'options', 'single_product_gallery_thumbs', 'std', 'left' );
			greeny_storage_set_array2( 'options', 'single_product_gallery_thumbs', 'type', 'hidden' );
			// Remove hover 'shop_buttons'
			greeny_storage_set_array2( 'options', 'shop_hover', 'std', 'shop' );
			greeny_storage_set_array2( 'options', 'shop_hover', 'options', apply_filters( 'greeny_filter_shop_hover', array(
				'none' => esc_html__( 'None', 'greeny' ),
				'shop' => esc_html__( 'Icons', 'greeny' ),
				)
			) );
		}
	}
}


// Theme init priorities:
// Remove\Register Action\filters
if ( ! function_exists( 'greeny_woocommerce_skin_woocommerce_remove_action' ) ) {
	add_action( 'init', 'greeny_woocommerce_skin_woocommerce_remove_action', 11 );
	function greeny_woocommerce_skin_woocommerce_remove_action() {
		if ( greeny_exists_woocommerce() ) {

            remove_action( 'woocommerce_sale_flash', 'greeny_woocommerce_add_sale_percent', 10 );

            remove_action( 'woocommerce_get_price_html', 'greeny_woocommerce_get_price_html' );

            remove_action( 'woocommerce_loop_add_to_cart_link', 'greeny_woocommerce_add_to_cart_link', 10 );
			add_filter( 'woocommerce_loop_add_to_cart_link', 'greeny_woocommerce_skin_add_to_cart_link', 10, 2 );

			remove_action( 'woocommerce_before_shop_loop', 'greeny_woocommerce_before_shop_loop', 10 );

			remove_action('woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_rating', 5);
			add_action('woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_rating', 30);

			add_action( 'woocommerce_product_thumbnails', 'greeny_woocommerce_add_wishlist', 21 );

			remove_action('woocommerce_before_single_product_summary', 'woocommerce_show_product_sale_flash', 10);
			add_action('woocommerce_single_product_summary', 'woocommerce_show_product_sale_flash', 4);

			// Status Bar
			add_action('woocommerce_before_cart', 'woocommerce_show_product_status_bar');
			add_action('woocommerce_before_checkout_form', 'woocommerce_show_product_status_bar');
			add_action('woocommerce_before_thankyou', 'woocommerce_show_product_status_bar');
		}
	}
}


// Theme init priorities:
// Action 'wp'
// 1 - detect override mode. Attention! Only after this step you can use overriden options (separate values for the shop, courses, etc.)
if ( ! function_exists( 'greeny_woocommerce_skin_setup_wp' ) ) {
	add_action( 'wp', 'greeny_woocommerce_skin_setup_wp' );
	function greeny_woocommerce_skin_setup_wp() {
		if ( greeny_exists_woocommerce() ) {
			if ( is_product() && greeny_get_theme_option( 'single_product_layout' ) == 'stretched' ) {
				remove_action( 'woocommerce_before_main_content', 'woocommerce_breadcrumb', 20, 0 );
				add_action( 'woocommerce_single_product_summary', 'woocommerce_breadcrumb', 3 );
			}
		}
	}
}


if ( ! function_exists( 'woocommerce_show_product_status_bar' ) ) {
	function woocommerce_show_product_status_bar() {
		if (is_cart() || is_checkout()) { ?>
			<div class="woocommerce_status_bar">
				<div class="bar_cart active"><span class="num">1</span><?php esc_html_e('Shopping Cart', 'greeny'); ?></div>
				<div class="bar_payment<?php echo esc_attr(is_checkout() ? ' active': ''); ?>"><span class="num">2</span><?php esc_html_e('Payment & Delivery Options', 'greeny'); ?></div>
				<div class="bar_order"><span class="num">3</span><?php esc_html_e('Order Received', 'greeny'); ?></div>
			</div>
		<?php
		}
	}
}


// Add WooCommerce-specific classes to the body
if ( ! function_exists( 'greeny_woocommerce_skin_add_body_classes' ) ) {
	add_filter( 'body_class', 'greeny_woocommerce_skin_add_body_classes' );
	function greeny_woocommerce_skin_add_body_classes( $classes ) {
		if ( is_product() ) {
			$classes[] = 'single_product_layout_' . greeny_get_theme_option( 'single_product_layout' );
		}
		return $classes;
	}
}


// Show/Hide title
if ( ! function_exists( 'greeny_woocommerce_skin_show_title' ) ) {
	add_filter( 'greeny_filter_show_woocommerce_title', 'greeny_woocommerce_skin_show_title' );
	function greeny_woocommerce_skin_show_title( $show ) {
		$tpl = greeny_storage_get('extended_products_tpl');
		if ( !empty($tpl) && ('info' == $tpl || 'info_2' == $tpl) ) {
			$show = true;
		} else {
			$show = false;
		}
		return $show;
	}
}


// Wrap 'Add to cart' button
if ( ! function_exists( 'greeny_woocommerce_skin_add_to_cart_link' ) ) {
	//Handler of the add_filter( 'woocommerce_loop_add_to_cart_link', 'greeny_woocommerce_skin_add_to_cart_link', 10, 2 );
	function greeny_woocommerce_skin_add_to_cart_link( $html, $product = false, $args = array() ) {
		$tpl = greeny_storage_get('extended_products_tpl');
		if ( isset($tpl) && 'simple' == $tpl ) {
			return sprintf( '<div class="add_to_cart_wrap">%s</div>', $html );
		} else if (isset($tpl) && 'hovered' == $tpl)  {
			return false;
		} else {
			return greeny_is_off( greeny_get_theme_option( 'shop_hover' ) ) ? sprintf( '<div class="add_to_cart_wrap">%s</div>', $html ) : $html;
		}
	}
}


if ( ! function_exists( 'greeny_woocommerce_add_wishlist' ) ) {
	function greeny_woocommerce_add_wishlist() {
		if (function_exists('greeny_exists_wishlist') && greeny_exists_wishlist()) {
			greeny_show_layout(do_shortcode("[ti_wishlists_addtowishlist]"));
		}
	}
}


/* Add parameter 'Product style' to the shop page settings
------------------------------------------------------------------- */

// Theme init priorities:
// 3 - add/remove Theme Options elements
if ( ! function_exists( 'greeny_woocommerce_extensions_add_product_style_theme_setup3' ) ) {
	add_action( 'after_setup_theme', 'greeny_woocommerce_extensions_add_product_style_theme_setup3', 3 );
	function greeny_woocommerce_extensions_add_product_style_theme_setup3() {
		if ( greeny_exists_woocommerce() ) {
			// Add parameter to the theme-specific options
			greeny_storage_set_array_after( 'options', 'shop_mode', apply_filters( 'greeny_filter_woocommerce_extensions_add_product_style_args', array(
				'product_style' => array(
					'title'      => esc_html__( 'Product style', 'greeny' ),
					'desc'       => wp_kses_data( __( 'Style of product items on the shop page.', 'greeny' ) ),
					'std'     => 'default',
					'options' => array(),
					'type'    => 'select',
				),
			) ) );
		}
	}
}


// Return lists with choises when its need in the admin mode
if ( ! function_exists( 'greeny_woocommerce_extensions_add_product_style_get_list_choises' ) ) {
	add_filter( 'greeny_filter_options_get_list_choises', 'greeny_woocommerce_extensions_add_product_style_get_list_choises', 10, 2 );
	function greeny_woocommerce_extensions_add_product_style_get_list_choises( $list, $id ) {
		if ( is_array( $list ) && count( $list ) == 0 ) {
			if ( strpos( $id, 'product_style' ) === 0 && function_exists( 'trx_addons_woocommerce_extended_products_get_list_styles' ) ) {
				$list = trx_addons_woocommerce_extended_products_get_list_styles();
			}
		}
		return $list;
	}
}


// Substitute default template in the products loop with selected in Theme Options
if ( ! function_exists( 'greeny_woocommerce_extensions_add_product_style_wc_get_template_part' ) ) {
	add_filter( 'wc_get_template_part', 'greeny_woocommerce_extensions_add_product_style_wc_get_template_part', 200, 3 );
	function greeny_woocommerce_extensions_add_product_style_wc_get_template_part( $template, $slug, $name ) {
		if ( $slug == 'content' && $name == 'product'
			&& function_exists( 'trx_addons_woocommerce_extended_products_get_layouts' )
			&& ( ! function_exists( 'trx_addons_sc_stack_check' ) || ! trx_addons_sc_stack_check( 'trx_sc_extended_products' ) )
			&& ! is_product()
		) {
			$style = greeny_get_theme_option( 'product_style' );
			if ( 'default' != $style ) {
				$layouts = trx_addons_woocommerce_extended_products_get_layouts();
				if ( isset( $layouts[ $style ] ) && ! empty( $layouts[ $style ]['template'] ) ) {
					$template = $layouts[ $style ]['template'];
				}
			}
		}
		return $template;
	}
}


// add products layouts
if ( !function_exists( 'greeny_add_woocommerce_products_layouts' ) ) {
	add_filter('trx_addons_filter_woocommerce_products_layouts', 'greeny_add_woocommerce_products_layouts');
	function greeny_add_woocommerce_products_layouts() {
		$arr = array(
				'default' => array(
					'title' => esc_html__( 'Default', 'greeny' ),
					'template' => ''
				),
				'centered' => array(
					'title' => esc_html__( 'Centered', 'greeny' ),
					'template' => ''
				),
				'simple' => array(
					'title' => esc_html__( 'Simple', 'greeny' ),
					'template' => greeny_get_file_dir('woocommerce/content-product-simple.php')
				),
				'hovered' => array(
					'title' => esc_html__( 'Hovered', 'greeny' ),
					'template' => greeny_get_file_dir('woocommerce/content-product-hovered.php')
				),
				'info' => array(
					'title' => esc_html__( 'Info', 'greeny' ),
					'template' => greeny_get_file_dir('woocommerce/content-product-info.php')
				),
				'info_2' => array(
					'title' => esc_html__( 'Info 2', 'greeny' ),
					'template' => greeny_get_file_dir('woocommerce/content-product-info-2.php')
				),
		);
		return $arr;
	}
}


// Add class with a "product style" to the wrap ul.products
// ( if we are not inside a shortcode 'trx_sc_extended_products' )
if ( ! function_exists( 'greeny_woocommerce_extensions_add_product_style_to_products_wrap' ) ) {
	add_filter( 'woocommerce_product_loop_start', 'greeny_woocommerce_extensions_add_product_style_to_products_wrap', 200, 1 );
	function greeny_woocommerce_extensions_add_product_style_to_products_wrap( $template ) {
		if ( function_exists( 'trx_addons_woocommerce_extended_products_get_layouts' )
			&& ( ! function_exists( 'trx_addons_sc_stack_check' ) || ! trx_addons_sc_stack_check( 'trx_sc_extended_products' ) )
			&& ! is_product()	// To prevent add class for the wrap of related products in the single product page
		) {
			$style = greeny_get_theme_option( 'product_style' );
			$new_classes = array(
				sprintf( 'products_style_%s', $style )
			);
			$layouts = trx_addons_woocommerce_extended_products_get_layouts();
			if ( isset( $layouts[ $style ] ) && ! empty( $layouts[ $style ]['products_classes'] ) ) {
				$new_classes = array_merge(
									$new_classes, 
									is_array( $layouts[ $style ]['products_classes'] )
										? $layouts[ $style ]['products_classes']
										: explode( ' ', $layouts[ $style ]['products_classes'] )
									);
			}
			$template = preg_replace( 
									'/(<ul[^>]*class="products )/',
									'$1' . esc_attr( join( ' ', $new_classes ) ) . ' ',
									$template
									);
		}
		return $template;
	}
}


// Add class with a "product style" to each product item
if ( ! function_exists( 'greeny_woocommerce_extensions_add_product_style_to_product_items' ) ) {
	add_filter( 'woocommerce_post_class', 'greeny_woocommerce_extensions_add_product_style_to_product_items', 200, 2 );
	function greeny_woocommerce_extensions_add_product_style_to_product_items( $classes, $product ) {
		if ( function_exists( 'trx_addons_woocommerce_extended_products_get_layouts' )
			&& ( ! function_exists( 'trx_addons_sc_stack_check' ) || ! trx_addons_sc_stack_check( 'trx_sc_extended_products' ) )
			&& ! is_product()	// To prevent add class for the wrap of related products in the single product page
		) {
			if ( is_array( $classes ) ) {
				$style = greeny_get_theme_option( 'product_style' );
				$new_classes = array(
									sprintf( 'product_style_%s', esc_attr( $style ) )
								);
				$layouts = trx_addons_woocommerce_extended_products_get_layouts();
				if ( isset( $layouts[ $style ] ) && ! empty( $layouts[ $style ]['product_classes'] ) ) {
					$new_classes = array_merge(
										$new_classes, 
										is_array( $layouts[ $style ]['product_classes'] )
											? $layouts[ $style ]['product_classes']
											: explode( ' ', $layouts[ $style ]['product_classes'] )
										);
				}
				foreach( $new_classes as $c ) {
					$c = trim( $c );
					if ( ! empty( $c ) && ! in_array( $c, $classes ) ) {
						$classes[] = $c;
					}
				}
			}
		}
		return $classes;
	}
}