<?php
/* Woocommerce extensions
------------------------------------------------------------------------------- */

// Theme init priorities:
// 9 - register other filters (for installer, etc.)
if ( ! function_exists( 'greeny_woocommerce_extensions_theme_setup9' ) ) {
	add_action( 'after_setup_theme', 'greeny_woocommerce_extensions_theme_setup9', 9 );
	function greeny_woocommerce_extensions_theme_setup9() {
		if ( greeny_exists_woocommerce() ) {
			// Frontend styles and scripts
			add_action( 'wp_enqueue_scripts', 'greeny_woocommerce_extensions_frontend_scripts', 1100 );
			add_action( 'trx_addons_action_load_scripts_front_woocommerce_extensions', 'greeny_woocommerce_extensions_frontend_scripts', 10, 1 );
			add_action( 'wp_enqueue_scripts', 'greeny_woocommerce_extensions_frontend_scripts_responsive', 2000 );
			add_action( 'trx_addons_action_load_scripts_front_woocommerce_extensions', 'greeny_woocommerce_extensions_frontend_scripts_responsive', 10, 1 );
			add_filter( 'greeny_filter_merge_styles', 'greeny_woocommerce_extensions_merge_styles' );
			add_filter( 'greeny_filter_merge_styles_responsive', 'greeny_woocommerce_extensions_merge_styles_responsive' );
			add_filter( 'greeny_filter_merge_scripts', 'greeny_woocommerce_extensions_merge_scripts' );
		}
	}
}

// Enqueue WooCommerce extensions styles
if ( ! function_exists( 'greeny_woocommerce_extensions_frontend_scripts' ) ) {
	//Handler of the add_action( 'wp_enqueue_scripts', 'greeny_woocommerce_extensions_frontend_scripts', 1100 );
	//Handler of the add_action( 'trx_addons_action_load_scripts_front_woocommerce_extensions', 'greeny_woocommerce_extensions_frontend_scripts', 10, 1 );
	function greeny_woocommerce_extensions_frontend_scripts( $force = false ) {
		static $loaded = false;
		if ( ! $loaded && (
			current_action() == 'wp_enqueue_scripts' && greeny_need_frontend_scripts( 'woocommerce' )
			||
			current_action() != 'wp_enqueue_scripts' && $force === true
			)
		) {
			$loaded = true;
			$greeny_url = greeny_get_file_url( 'plugins/woocommerce/woocommerce-extensions.css' );
			if ( '' != $greeny_url ) {
				wp_enqueue_style( 'greeny-woocommerce-extensions', $greeny_url, array(), null );
			}
			$greeny_url = greeny_get_file_url( 'plugins/woocommerce/woocommerce-extensions.js' );
			if ( '' != $greeny_url ) {
				wp_enqueue_script( 'greeny-woocommerce-extensions', $greeny_url, array( 'jquery' ), null, true );
			}
		}
	}
}

// Enqueue WooCommerce extensions responsive styles
if ( ! function_exists( 'greeny_woocommerce_extensions_frontend_scripts_responsive' ) ) {
	//Handler of the add_action( 'wp_enqueue_scripts', 'greeny_woocommerce_extensions_frontend_scripts_responsive', 2000 );
	//Handler of the add_action( 'trx_addons_action_load_scripts_front_woocommerce_extensions', 'greeny_woocommerce_extensions_frontend_scripts_responsive', 10, 1 );
	function greeny_woocommerce_extensions_frontend_scripts_responsive( $force = false ) {
		static $loaded = false;
		if ( ! $loaded && (
			current_action() == 'wp_enqueue_scripts' && greeny_need_frontend_scripts( 'woocommerce' )
			||
			current_action() != 'wp_enqueue_scripts' && $force === true
			)
		) {
			$loaded = true;
			$greeny_url = greeny_get_file_url( 'plugins/woocommerce/woocommerce-extensions-responsive.css' );
			if ( '' != $greeny_url ) {
				wp_enqueue_style( 'greeny-woocommerce-extensions-responsive', $greeny_url, array(), null, greeny_media_for_load_css_responsive( 'woocommerce-extensions' ) );
			}
		}
	}
}

// Merge custom styles
if ( ! function_exists( 'greeny_woocommerce_extensions_merge_styles' ) ) {
	//Handler of the add_filter('greeny_filter_merge_styles', 'greeny_woocommerce_extensions_merge_styles');
	function greeny_woocommerce_extensions_merge_styles( $list ) {
		$list[ 'plugins/woocommerce/woocommerce-extensions.css' ] = false;
		return $list;
	}
}

// Merge responsive styles
if ( ! function_exists( 'greeny_woocommerce_extensions_merge_styles_responsive' ) ) {
	//Handler of the add_filter('greeny_filter_merge_styles_responsive', 'greeny_woocommerce_extensions_merge_styles_responsive');
	function greeny_woocommerce_extensions_merge_styles_responsive( $list ) {
		$list[ 'plugins/woocommerce/woocommerce-extensions-responsive.css' ] = false;
		return $list;
	}
}

// Merge custom scripts
if ( ! function_exists( 'greeny_woocommerce_extensions_merge_scripts' ) ) {
	//Handler of the add_filter('greeny_filter_merge_scripts', 'greeny_woocommerce_extensions_merge_scripts');
	function greeny_woocommerce_extensions_merge_scripts( $list ) {
		$list[ 'plugins/woocommerce/woocommerce-extensions.js' ] = false;
		return $list;
	}
}



/* Common functions
------------------------------------------------------------------- */

// Check if taxomony is a Woocommerce product's attribute
if ( ! function_exists( 'greeny_woocommerce_extensions_attrib_get_type' ) ) {
	function greeny_woocommerce_extensions_attrib_get_type( $taxonomy ) {
		$type = '';
		if ( greeny_exists_woocommerce() ) {
			$attribute_taxonomies = wc_get_attribute_taxonomies();
			if ( ! empty( $attribute_taxonomies ) ) {
				foreach ( $attribute_taxonomies as $attribute ) {
					if ( wc_attribute_taxonomy_name( $attribute->attribute_name ) == $taxonomy ) {
						$type = $attribute->attribute_type;
						break;
					}
				}
			}
		}
		return $type;
	}
}



/* Move breadcrumbs above title
------------------------------------------------------------------- */
if ( ! function_exists( 'greeny_woocommerce_extensions_move_breadcrumbs_before_title' ) ) {
	add_action( 'init', 'greeny_woocommerce_extensions_move_breadcrumbs_before_title' );
	function greeny_woocommerce_extensions_move_breadcrumbs_before_title() {
		if ( greeny_exists_woocommerce()
			&& apply_filters( 'greeny_filter_woocommerce_extensions_allow_components', true, 'move_breadcrumbs' )
		) {
			remove_action( 'woocommerce_before_main_content', 'woocommerce_breadcrumb', 20 );
			add_action( 'woocommerce_single_product_summary', 'woocommerce_breadcrumb', 3 );	// Priority 5 used for the title
		}
	}
}



/* Add parameter 'Single Product Gallery Style'
------------------------------------------------------------------- */

// Theme init priorities:
// 3 - add/remove Theme Options elements
if ( ! function_exists( 'greeny_woocommerce_extensions_product_gallery_style_theme_setup3' ) ) {
	add_action( 'after_setup_theme', 'greeny_woocommerce_extensions_product_gallery_style_theme_setup3', 3 );
	function greeny_woocommerce_extensions_product_gallery_style_theme_setup3() {
		if ( greeny_exists_woocommerce()
			&& apply_filters( 'greeny_filter_woocommerce_extensions_allow_components', true, 'gallery_style' )
		) {
			// Add 'single_product_gallery_style' to the theme-specific options
			greeny_storage_set_array_before( 'options', 'single_product_gallery_thumbs', apply_filters( 'greeny_filter_woocommerce_extensions_gallery_style_args', array(
				'single_product_gallery_style'  => array(
					'title'      => esc_html__( 'Gallery style', 'greeny' ),
					'desc'       => wp_kses_data( __( 'Select the style of the gallery on the single product page.', 'greeny' ) ),
					'override'   => array(
						'mode'    => 'product',
						'section' => esc_html__( 'Content', 'greeny' ),
					),
					'options'    => apply_filters(
													'greeny_filter_woocommerce_single_product_gallery_style',
													array(
														'default'   => esc_html__( 'Default', 'greeny' ),
														'fullwidth' => esc_html__( 'Fullwidth', 'greeny' ),
														'cascade'   => esc_html__( 'Cascade', 'greeny' ),
														'grid'      => esc_html__( 'Grid', 'greeny' ),
													)
												),
					'std'        => 'default',
					'type'       => 'select',
				),
			) ) );

			// Add condition to the 'single_product_gallery_thumbs'
			greeny_storage_set_array2( 'options', 'single_product_gallery_thumbs', 'dependency', array(
				'single_product_gallery_style' => array( 'default', 'fullwidth' ),
			) );

		}
	}
}

// Disable slider and zoom for 'fullwidth' (only zoom), 'cascade' and 'grid'
if ( ! function_exists( 'greeny_woocommerce_extensions_product_gallery_style_theme_support' ) ) {
	add_action( 'wp_enqueue_scripts', 'greeny_woocommerce_extensions_product_gallery_style_theme_support', 9 );
	function greeny_woocommerce_extensions_product_gallery_style_theme_support() {
		if ( greeny_exists_woocommerce()
			&& apply_filters( 'greeny_filter_woocommerce_extensions_allow_components', true, 'gallery_style' )
		) {
			$style = greeny_get_theme_option( 'single_product_gallery_style' );
			// Disable 'zoom'
			if ( in_array( $style, array( 'fullwidth', 'cascade', 'grid' ) ) ) {
				remove_theme_support( 'wc-product-gallery-zoom' );
			}
			// Disable 'slider'
			if ( in_array( $style, array( 'cascade', 'grid' ) ) ) {
				remove_theme_support( 'wc-product-gallery-slider' );
			}
			// Load 'masonry' script
			if ( 'grid' == $style && is_product() ) {
				greeny_load_masonry_scripts();
			}
		}
	}
}

// Add plugin-specific classes to the product
if ( ! function_exists( 'greeny_woocommerce_extensions_product_gallery_style_add_wrapper_classes' ) ) {
	add_filter( 'greeny_filter_single_product_wrapper_class', 'greeny_woocommerce_extensions_product_gallery_style_add_wrapper_classes' );
	function greeny_woocommerce_extensions_product_gallery_style_add_wrapper_classes( $classes ) {
		if ( greeny_exists_woocommerce()
			&& apply_filters( 'greeny_filter_woocommerce_extensions_allow_components', true, 'gallery_style' )
			&& is_product()
		) {
			$classes[] = 'single_product_gallery_style_' . esc_attr( greeny_get_theme_option( 'single_product_gallery_style' ) );
		}
		return $classes;
	}
}

// Increase thumbnail size for some gallery's styles
if ( ! function_exists( 'greeny_woocommerce_extensions_product_gallery_style_thumbnail_size' ) ) {
	add_filter( 'woocommerce_gallery_thumbnail_size', 'greeny_woocommerce_extensions_product_gallery_style_thumbnail_size' );
	function greeny_woocommerce_extensions_product_gallery_style_thumbnail_size( $sizes = array() ) {
		if ( apply_filters( 'greeny_filter_woocommerce_extensions_allow_components', true, 'gallery_style' ) ) {
			$style = greeny_get_theme_option( 'single_product_gallery_style' );
			if ( in_array( $style, array( 'cascade', 'grid' ) ) ) {
				$gallery_thumbnail = wc_get_image_size( 'woocommerce_single' );
				$sizes = array( (int) $gallery_thumbnail['width'], (int) $gallery_thumbnail['height'] );
			}
		}
		return $sizes;
	}
}

// Increase main image size for some gallery's styles
if ( ! function_exists( 'greeny_woocommerce_extensions_product_gallery_style_image_size' ) ) {
	add_filter( 'woocommerce_gallery_image_size', 'greeny_woocommerce_extensions_product_gallery_style_image_size' );
	function greeny_woocommerce_extensions_product_gallery_style_image_size( $sizes = array() ) {
		if ( apply_filters( 'greeny_filter_woocommerce_extensions_allow_components', true, 'gallery_style' ) ) {
			$style = greeny_get_theme_option( 'single_product_gallery_style' );
			if ( $style == 'fullwidth' ) {
				$sizes = 'full';
			}
		}
		return $sizes;
	}
}


// Disable slider for some gallery's styles
if ( ! function_exists( 'greeny_woocommerce_extensions_product_gallery_style_disable_slider' ) ) {
	add_filter( 'woocommerce_single_product_flexslider_enabled', 'greeny_woocommerce_extensions_product_gallery_style_disable_slider' );
	function greeny_woocommerce_extensions_product_gallery_style_disable_slider( $enabled = true ) {
		return $enabled
					&& ( ! apply_filters( 'greeny_filter_woocommerce_extensions_allow_components', true, 'gallery_style' )
						||
						! in_array( greeny_get_theme_option( 'single_product_gallery_style' ), array( 'cascade', 'grid' ) )
						);
	}
}


// Remove 'single_product_gallery_thumbs_left' from the body 
if ( ! function_exists( 'greeny_woocommerce_extensions_product_gallery_style_remove_body_classes' ) ) {
	add_filter( 'body_class', 'greeny_woocommerce_extensions_product_gallery_style_remove_body_classes', 20 );
	function greeny_woocommerce_extensions_product_gallery_style_remove_body_classes( $classes ) {
		if ( apply_filters( 'greeny_filter_woocommerce_extensions_allow_components', true, 'gallery_style' ) ) {
			$style = greeny_get_theme_option( 'single_product_gallery_style' );
			if ( in_array( $style, array( 'cascade', 'grid' ) ) ) {
				if ( greeny_exists_woocommerce() && is_product() ) {
					$classes = greeny_array_delete_by_value( $classes, 'single_product_gallery_thumbs_left' );
				}
			}
		}
		return $classes;
	}
}



/* Add parameter 'Single product gallery lightbox on/off'
------------------------------------------------------------------- */

// Theme init priorities:
// 3 - add/remove Theme Options elements
if ( ! function_exists( 'greeny_woocommerce_extensions_product_gallery_lightbox_theme_setup3' ) ) {
	add_action( 'after_setup_theme', 'greeny_woocommerce_extensions_product_gallery_lightbox_theme_setup3', 3 );
	function greeny_woocommerce_extensions_product_gallery_lightbox_theme_setup3() {
		if ( greeny_exists_woocommerce()
			&& apply_filters( 'greeny_filter_woocommerce_extensions_allow_components', true, 'gallery_lightbox' )
		) {
			// Add 'single_product_additional_info' to the theme-specific options
			greeny_storage_set_array_after( 'options', 'single_product_gallery_thumbs', apply_filters( 'greeny_filter_woocommerce_extensions_gallery_lightbox_args', array(
				'single_product_gallery_lightbox' => array(
					'title'   => esc_html__( 'Allow lightbox with large image', 'greeny' ),
					'desc'    => wp_kses_data( __( "Allow the lightbox with a large image in the single product page.", 'greeny' ) ),
					'std'     => 1,
					'type'    => 'switch',
				),
			) ) );
		}
	}
}

// Disable image lightbox
if ( ! function_exists( 'greeny_woocommerce_extensions_product_gallery_lightbox_theme_support' ) ) {
	add_action( 'wp_enqueue_scripts', 'greeny_woocommerce_extensions_product_gallery_lightbox_theme_support', 9 );
	function greeny_woocommerce_extensions_product_gallery_lightbox_theme_support() {
		if ( greeny_exists_woocommerce()
			&& apply_filters( 'greeny_filter_woocommerce_extensions_allow_components', true, 'gallery_lightbox' )
			&& is_product()
		) {
			if ( (int) greeny_get_theme_option( 'single_product_gallery_lightbox' ) == 0 ) {
				remove_theme_support( 'wc-product-gallery-lightbox' );
			}
		}
	}
}

// Remove link from gallery image layout if lightbox is disabled
if ( ! function_exists( 'greeny_woocommerce_extensions_product_gallery_lightbox_remove_links' ) ) {
	add_filter( 'woocommerce_single_product_image_thumbnail_html', 'greeny_woocommerce_extensions_product_gallery_lightbox_remove_links', 9 );
	function greeny_woocommerce_extensions_product_gallery_lightbox_remove_links( $html = '', $image_id = 0 ) {
		if ( apply_filters( 'greeny_filter_woocommerce_extensions_allow_components', true, 'gallery_lightbox' ) ) {
			if ( (int) greeny_get_theme_option( 'single_product_gallery_lightbox' ) == 0 && strpos( $html, '<a ' ) !== false ) {
				$html = preg_replace( '/(.*)(<a [^>]*>)(.*)(<\/a>)(.*)/', '$1$3$5', $html );
			}
		}
		return $html;
	}
}



/* Add parameter 'Single product gallery zoom on/off'
------------------------------------------------------------------- */

// Theme init priorities:
// 3 - add/remove Theme Options elements
if ( ! function_exists( 'greeny_woocommerce_extensions_product_gallery_zoom_theme_setup3' ) ) {
	add_action( 'after_setup_theme', 'greeny_woocommerce_extensions_product_gallery_zoom_theme_setup3', 3 );
	function greeny_woocommerce_extensions_product_gallery_zoom_theme_setup3() {
		if ( greeny_exists_woocommerce()
			&& apply_filters( 'greeny_filter_woocommerce_extensions_allow_components', true, 'gallery_zoom' )
		) {
			// Add 'single_product_additional_info' to the theme-specific options
			greeny_storage_set_array_after( 'options', 'single_product_gallery_thumbs', apply_filters( 'greeny_filter_woocommerce_extensions_gallery_zoom_args', array(
				'single_product_gallery_zoom' => array(
					'title'   => esc_html__( 'Allow zoom on the large image', 'greeny' ),
					'desc'    => wp_kses_data( __( "Allow the zoom on the large image in the single product page.", 'greeny' ) ),
					'std'     => 1,
					'type'    => 'switch',
				),
			) ) );
		}
	}
}

// Disable image zoom
if ( ! function_exists( 'greeny_woocommerce_extensions_product_gallery_zoom_theme_support' ) ) {
	add_action( 'wp_enqueue_scripts', 'greeny_woocommerce_extensions_product_gallery_zoom_theme_support', 9 );
	function greeny_woocommerce_extensions_product_gallery_zoom_theme_support() {
		if ( greeny_exists_woocommerce()
			&& apply_filters( 'greeny_filter_woocommerce_extensions_allow_components', true, 'gallery_zoom' )
			&& is_product()
		) {
			if ( (int) greeny_get_theme_option( 'single_product_gallery_zoom' ) == 0 ) {
				remove_theme_support( 'wc-product-gallery-zoom' );
			}
		}
	}
}



/* Add parameter 'Single Product Details Style'
------------------------------------------------------------------- */

// Theme init priorities:
// 3 - add/remove Theme Options elements
if ( ! function_exists( 'greeny_woocommerce_extensions_product_details_style_theme_setup3' ) ) {
	add_action( 'after_setup_theme', 'greeny_woocommerce_extensions_product_details_style_theme_setup3', 3 );
	function greeny_woocommerce_extensions_product_details_style_theme_setup3() {
		if ( greeny_exists_woocommerce()
			&& apply_filters( 'greeny_filter_woocommerce_extensions_allow_components', true, 'details_style' )
		) {
			// Add 'single_product_details_style' to the theme-specific options
			greeny_storage_set_array_before( 'options', 'show_related_posts_shop', apply_filters( 'greeny_filter_woocommerce_extensions_details_style_args', array(
				'single_product_details_style'   => array(
					'title'      => esc_html__( 'Details style', 'greeny' ),
					'desc'       => wp_kses_data( __( 'Select the style of the section "Details" on the single product page.', 'greeny' ) ),
					'override'   => array(
						'mode'    => 'product',
						'section' => esc_html__( 'Content', 'greeny' ),
					),
					'options'    => apply_filters(
													'greeny_filter_woocommerce_single_product_details_styles',
													array(
														'default'   => esc_html__( 'Default (Tabs)', 'greeny' ),
														'stacked'   => esc_html__( 'Stacked', 'greeny' ),
														'accordion' => esc_html__( 'Accordion', 'greeny' ),
													)
												),
					'std'        => 'default',
					'type'       => 'select',
				),
			) ) );
		}
	}
}

// Add plugin-specific classes to the product
if ( ! function_exists( 'greeny_woocommerce_extensions_product_details_style_add_wrapper_classes' ) ) {
	add_filter( 'greeny_filter_single_product_wrapper_class', 'greeny_woocommerce_extensions_product_details_style_add_wrapper_classes' );
	function greeny_woocommerce_extensions_product_details_style_add_wrapper_classes( $classes ) {
		if ( greeny_exists_woocommerce()
			&& apply_filters( 'greeny_filter_woocommerce_extensions_allow_components', true, 'details_style' )
			&& is_product()
		) {
			$classes[] = 'single_product_details_style_' . esc_attr( greeny_get_theme_option( 'single_product_details_style' ) );
		}
		return $classes;
	}
}


// Enqueue accordion script if description style is equal to 'Accordion'
if ( ! function_exists( 'greeny_woocommerce_extensions_product_details_style_accordion_enqueue_script' ) ) {
	add_action( 'wp_enqueue_scripts', 'greeny_woocommerce_extensions_product_details_style_accordion_enqueue_script', 1100 );
	function greeny_woocommerce_extensions_product_details_style_accordion_enqueue_script() {
		if ( apply_filters( 'greeny_filter_woocommerce_extensions_allow_components', true, 'details_style' )
			&& greeny_get_theme_option( 'single_product_details_style' ) == 'accordion'
			&& greeny_exists_woocommerce()
			&& is_product()
		) {
			wp_enqueue_script( 'jquery-ui-accordion', false, array( 'jquery', 'jquery-ui-core' ), null, true );
		}
	}
}


// Remove tabs if description style is equal to 'Stacked'
if ( ! function_exists( 'greeny_woocommerce_extensions_product_details_style_stacked_remove_product_tabs' ) ) {
	add_filter( 'woocommerce_product_tabs', 'greeny_woocommerce_extensions_product_details_style_stacked_remove_product_tabs', 1000 );
	function greeny_woocommerce_extensions_product_details_style_stacked_remove_product_tabs( $tabs = array() ) {
		if ( apply_filters( 'greeny_filter_woocommerce_extensions_allow_components', true, 'details_style' )
			&& greeny_get_theme_option( 'single_product_details_style' ) == 'stacked'
			&& is_array( $tabs )
		) {
			// Move additional info to top
			if ( isset( $tabs['additional_information'] ) ) {
				$tabs_new = array( 'additional_information' => $tabs['additional_information'] );
				unset( $tabs['additional_information'] );
				$tabs = array_merge( $tabs_new, $tabs );
			}
			// Display blocks
			do_action( 'greeny_action_before_product_stacks', $tabs );
			?><div class="woocommerce-stacks"><?php
				foreach ( $tabs as $k => $tab ) {
					if ( isset( $tab['callback'] ) ) {
						do_action( 'greeny_action_before_product_stack', $k, $tab );
						?><div class="woocommerce-stack woocommerce-stack-<?php echo esc_attr( $k ); ?>"><?php
							call_user_func( $tab['callback'], $k, $tab );
						?></div><?php
						do_action( 'greeny_action_after_product_stack', $k, $tab );
					}
				}
			?></div><?php
			do_action( 'greeny_action_after_product_stacks', $tabs );
			// Remove all tabs to prevent display
			$tabs = array();
		}
		return $tabs;
	}
}


// Remove tabs if description style is equal to 'Accordion'
if ( ! function_exists( 'greeny_woocommerce_extensions_product_details_style_accordion_remove_product_tabs' ) ) {
	add_filter( 'woocommerce_product_tabs', 'greeny_woocommerce_extensions_product_details_style_accordion_remove_product_tabs', 1000 );
	function greeny_woocommerce_extensions_product_details_style_accordion_remove_product_tabs( $tabs = array() ) {
		if ( apply_filters( 'greeny_filter_woocommerce_extensions_allow_components', true, 'details_style' )
			&& greeny_get_theme_option( 'single_product_details_style' ) == 'accordion'
			&& is_array( $tabs )
		) {
			// Move additional info to top
			if ( isset( $tabs['additional_information'] ) ) {
				$tabs_new = array( 'additional_information' => $tabs['additional_information'] );
				unset( $tabs['additional_information'] );
				$tabs = array_merge( $tabs_new, $tabs );
			}
			// Display blocks
			do_action( 'greeny_action_before_product_accordion', $tabs );
			?><div class="greeny_accordion woocommerce-accordion"><?php
				foreach ( $tabs as $k => $tab ) {
					if ( isset( $tab['callback'] ) ) {
						do_action( 'greeny_action_before_product_accordion_title', $k, $tab );
						?><h3 class="greeny_accordion_title woocommerce-accordion-title woocommerce-accordion-title-<?php echo esc_attr( $k ); ?>"><?php
							echo esc_html( $tab['title'] );
						?></h3><?php
						do_action( 'greeny_action_after_product_accordion_title', $k, $tab );
						do_action( 'greeny_action_before_product_accordion_content', $k, $tab );
						?><div class="greeny_accordion_content woocommerce-accordion-content woocommerce-accordion-content-<?php echo esc_attr( $k ); ?>"><?php
							call_user_func( $tab['callback'], $k, $tab );
						?></div><?php
						do_action( 'greeny_action_after_product_accordion_content', $k, $tab );
					}
				}
			?></div><?php
			do_action( 'greeny_action_after_product_accordion', $tabs );
			// Remove all tabs to prevent display
			$tabs = array();
		}
		return $tabs;
	}
}


/* Add parameter 'Single Product Details position'
------------------------------------------------------------------- */

// Theme init priorities:
// 3 - add/remove Theme Options elements
if ( ! function_exists( 'greeny_woocommerce_extensions_product_details_position_theme_setup3' ) ) {
	add_action( 'after_setup_theme', 'greeny_woocommerce_extensions_product_details_position_theme_setup3', 3 );
	function greeny_woocommerce_extensions_product_details_position_theme_setup3() {
		if ( greeny_exists_woocommerce()
			&& apply_filters( 'greeny_filter_woocommerce_extensions_allow_components', true, 'details_position' )
		) {
			// Add 'single_product_details_position' to the theme-specific options
			greeny_storage_set_array_before( 'options', 'show_related_posts_shop', apply_filters( 'greeny_filter_woocommerce_extensions_details_position_args', array(
				'single_product_details_position' => array(
					'title'   => esc_html__( 'Details position', 'greeny' ),
					'desc'    => wp_kses_data( __( "Select a position of the single product details.", 'greeny' ) ),
					'override'   => array(
						'mode'    => 'product',
						'section' => esc_html__( 'Content', 'greeny' ),
					),
					'options' => apply_filters( 'greeny_filter_woocommerce_extensions_details_position', array(
						'default'       => esc_html__( 'Default', 'greeny' ),
						'under_gallery' => esc_html__( 'Under the gallery', 'greeny' ),
						'under_summary' => esc_html__( 'Under the summary', 'greeny' ),
					) ),
					'std'     => 'default',
					'type'    => 'select',
				),
			) ) );
		}
	}
}

// Add plugin-specific classes to the product
if ( ! function_exists( 'greeny_woocommerce_extensions_product_details_position_add_wrapper_classes' ) ) {
	add_filter( 'greeny_filter_single_product_wrapper_class', 'greeny_woocommerce_extensions_product_details_position_add_wrapper_classes' );
	function greeny_woocommerce_extensions_product_details_position_add_wrapper_classes( $classes ) {
		if ( greeny_exists_woocommerce()
			&& apply_filters( 'greeny_filter_woocommerce_extensions_allow_components', true, 'details_position' )
			&& is_product()
		) {
			$classes[] = 'single_product_details_position_' . esc_attr( greeny_get_theme_option( 'single_product_details_position' ) );
		}
		return $classes;
	}
}



/* Add parameter 'Single Product Summary Sticky'
------------------------------------------------------------------- */

// Theme init priorities:
// 3 - add/remove Theme Options elements
if ( ! function_exists( 'greeny_woocommerce_extensions_product_summary_sticky_theme_setup3' ) ) {
	add_action( 'after_setup_theme', 'greeny_woocommerce_extensions_product_summary_sticky_theme_setup3', 3 );
	function greeny_woocommerce_extensions_product_summary_sticky_theme_setup3() {
		if ( greeny_exists_woocommerce()
			&& apply_filters( 'greeny_filter_woocommerce_extensions_allow_components', true, 'product_summary_sticky' )
		) {
			// Add 'single_product_summary_sticky' to the theme-specific options
			greeny_storage_set_array_before( 'options', 'show_related_posts_shop', apply_filters( 'greeny_filter_woocommerce_extensions_summary_sticky_args', array(
				'single_product_summary_sticky' => array(
					'title'      => esc_html__( 'Summary sticky', 'greeny' ),
					'desc'       => wp_kses_data( __( 'Make "Summary" sticky on the single product page.', 'greeny' ) ),
					'override'   => array(
						'mode'    => 'product',
						'section' => esc_html__( 'Content', 'greeny' ),
					),
					'dependency' => array(
						'single_product_details_position' => array( 'under_gallery' ),
					),
					'std'        => 0,
					'type'       => 'switch',
				),
			) ) );
		}
	}
}

// Add plugin-specific classes to the product
if ( ! function_exists( 'greeny_woocommerce_extensions_product_summary_sticky_add_wrapper_classes' ) ) {
	add_filter( 'greeny_filter_single_product_wrapper_class', 'greeny_woocommerce_extensions_product_summary_sticky_add_wrapper_classes' );
	function greeny_woocommerce_extensions_product_summary_sticky_add_wrapper_classes( $classes ) {
		if ( greeny_exists_woocommerce()
			&& apply_filters( 'greeny_filter_woocommerce_extensions_allow_components', true, 'product_summary_sticky' )
			&& greeny_get_theme_option( 'single_product_summary_sticky' ) == 1
			&& apply_filters( 'greeny_filter_woocommerce_extensions_allow_components', true, 'details_position' )
			&& greeny_get_theme_option( 'single_product_details_position' ) == 'under_gallery'
			&& is_product()
		) {
			$classes[] = 'single_product_summary_sticky';
		}
		return $classes;
	}
}

// Move related products outside the article
if ( ! function_exists( 'greeny_woocommerce_extensions_product_summary_sticky_before_main_content' ) ) {
	add_action( 'woocommerce_before_main_content', 'greeny_woocommerce_extensions_product_summary_sticky_before_main_content' );
	function greeny_woocommerce_extensions_product_summary_sticky_before_main_content() {
		if ( greeny_exists_woocommerce()
			&& apply_filters( 'greeny_filter_woocommerce_extensions_allow_components', true, 'product_summary_sticky' )
			&& greeny_get_theme_option( 'single_product_summary_sticky' ) == 1
			&& apply_filters( 'greeny_filter_woocommerce_extensions_allow_components', true, 'details_position' )
			&& greeny_get_theme_option( 'single_product_details_position' ) == 'under_gallery'
			&& is_product()
		) {
			remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_related_products', 20 );
			add_action( 'woocommerce_after_main_content', 'woocommerce_output_related_products', 20 );
			add_action( 'woocommerce_after_single_product_summary', 'greeny_woocommerce_extensions_product_summary_sticky_add_holder', 20 );
		}
	}
}

// Add holder instead related products inside the article
if ( ! function_exists( 'greeny_woocommerce_extensions_product_summary_sticky_add_holder' ) ) {
	function greeny_woocommerce_extensions_product_summary_sticky_add_holder() {
		?><div class="single_product_summary_sticky_related_holder"></div><?php
	}
}



/* Move 'Additional information' under the product's description
------------------------------------------------------------------- */

// Theme init priorities:
// 3 - add/remove Theme Options elements
if ( ! function_exists( 'greeny_woocommerce_extensions_additional_info_theme_setup3' ) ) {
	add_action( 'after_setup_theme', 'greeny_woocommerce_extensions_additional_info_theme_setup3', 3 );
	function greeny_woocommerce_extensions_additional_info_theme_setup3() {
		if ( greeny_exists_woocommerce()
			&& apply_filters( 'greeny_filter_woocommerce_extensions_allow_components', true, 'additional_info' )
		) {
			// Add 'single_product_additional_info' to the theme-specific options
			greeny_storage_set_array_before( 'options', 'show_related_posts_shop', apply_filters( 'greeny_filter_woocommerce_extensions_additional_info_args', array(
				'single_product_additional_info' => array(
					'title'   => esc_html__( 'Show additional info under short description', 'greeny' ),
					'desc'    => wp_kses_data( __( "Make additional product information more prominent by moving it up under the short description.", 'greeny' ) ),
					'std'     => 0,
					'type'    => 'switch',
				),
			) ) );
		}
	}
}

// Remove tab 'Additional information' from the tabs list
if ( ! function_exists( 'greeny_woocommerce_extensions_additional_info_default_product_tabs' ) ) {
	add_filter( 'woocommerce_product_tabs', 'greeny_woocommerce_extensions_additional_info_default_product_tabs', 100 );
	function greeny_woocommerce_extensions_additional_info_default_product_tabs( $tabs = array() ) {
		if ( apply_filters( 'greeny_filter_woocommerce_extensions_allow_components', true, 'additional_info' )
			&& (int) greeny_get_theme_option( 'single_product_additional_info' ) == 1
			&& isset( $tabs['additional_information'] )
		) {
			unset( $tabs['additional_information'] );	//woocommerce_product_additional_information_tab
		}
		return $tabs;
	}
}

// Add 'Additional information' above the product's price
if ( ! function_exists( 'greeny_woocommerce_extensions_additional_info_template' ) ) {
	add_action( 'woocommerce_single_product_summary', 'greeny_woocommerce_extensions_additional_info_template', 25 );
	function greeny_woocommerce_extensions_additional_info_template() {
		if ( apply_filters( 'greeny_filter_woocommerce_extensions_allow_components', true, 'additional_info' )
			&& (int) greeny_get_theme_option( 'single_product_additional_info' ) == 1
			&& function_exists( 'woocommerce_product_additional_information_tab' )
		) {
			?><div class="woocommerce-product-details__additional-information"><?php
				woocommerce_product_additional_information_tab();
			?></div><?php
		}
	}
}



/* Custom text after price on the single product page
------------------------------------------------------------------- */

// Theme init priorities:
// 3 - add/remove Theme Options elements
if ( ! function_exists( 'greeny_woocommerce_extensions_product_text_after_price_theme_setup3' ) ) {
	add_action( 'after_setup_theme', 'greeny_woocommerce_extensions_product_text_after_price_theme_setup3', 3 );
	function greeny_woocommerce_extensions_product_text_after_price_theme_setup3() {
		if ( greeny_exists_woocommerce()
			&& apply_filters( 'greeny_filter_woocommerce_extensions_allow_components', true, 'text_after_price' )
		) {
			// Add 'single_product_text_after_price' to the theme-specific options
			greeny_storage_set_array_before( 'options', 'show_related_posts_shop', apply_filters( 'greeny_filter_woocommerce_extensions_text_after_price_args', array(
				'single_product_text_after_price' => array(
					'title'    => esc_html__( 'Text after price', 'greeny' ),
					'desc'     => wp_kses_data( __( 'Specify custom text to show it after the product price on the single product page.', 'greeny' ) ),
					'std'      => '',
					'type'     => 'text_editor',
				),
			) ) );
		}
	}
}

// Add custom text after "Add to cart" button
// @hooked woocommerce_template_single_price - 10
if ( ! function_exists( 'greeny_woocommerce_extensions_product_text_after_price' ) ) {
	add_action( 'woocommerce_single_product_summary', 'greeny_woocommerce_extensions_product_text_after_price', 10 );
	function greeny_woocommerce_extensions_product_text_after_price() {
		if ( apply_filters( 'greeny_filter_woocommerce_extensions_allow_components', true, 'text_after_price' ) ) {
			greeny_show_layout( greeny_get_theme_option( 'single_product_text_after_price' ), '<div class="single_product_custom_text_after_price">', '</div>' );
		}
	}
}



/* Custom text after "Add to cart" on the single product page
------------------------------------------------------------------- */

// Theme init priorities:
// 3 - add/remove Theme Options elements
if ( ! function_exists( 'greeny_woocommerce_extensions_product_text_after_add_to_cart_theme_setup3' ) ) {
	add_action( 'after_setup_theme', 'greeny_woocommerce_extensions_product_text_after_add_to_cart_theme_setup3', 3 );
	function greeny_woocommerce_extensions_product_text_after_add_to_cart_theme_setup3() {
		if ( greeny_exists_woocommerce()
			&& apply_filters( 'greeny_filter_woocommerce_extensions_allow_components', true, 'text_after_add_to_cart' )
		) {
			// Add 'single_product_text_after_add_to_cart' to the theme-specific options
			greeny_storage_set_array_before( 'options', 'show_related_posts_shop', apply_filters( 'greeny_filter_woocommerce_extensions_text_after_add_to_cart_args', array(
				'single_product_text_after_add_to_cart' => array(
					'title'    => esc_html__( 'Text after "Add to Cart"', 'greeny' ),
					'desc'     => wp_kses_data( __( 'Specify custom text to show it after "Add to Cart" button on the single product page.', 'greeny' ) ),
					'std'      => '',
					'type'     => 'text_editor',
				),
			) ) );
		}
	}
}

// Add custom text after "Add to cart" button
// @hooked woocommerce_template_single_add_to_cart - 30
if ( ! function_exists( 'greeny_woocommerce_extensions_product_text_after_add_to_cart' ) ) {
	add_action( 'woocommerce_single_product_summary', 'greeny_woocommerce_extensions_product_text_after_add_to_cart', 30 );
	function greeny_woocommerce_extensions_product_text_after_add_to_cart() {
		if ( apply_filters( 'greeny_filter_woocommerce_extensions_allow_components', true, 'text_after_add_to_cart' ) ) {
			greeny_show_layout( greeny_get_theme_option( 'single_product_text_after_add_to_cart' ), '<div class="single_product_custom_text_after_add_to_cart">', '</div>' );
		}
	}
}



/* Add parameter 'Single Product "Add to cart" Sticky'
------------------------------------------------------------------- */

// Theme init priorities:
// 3 - add/remove Theme Options elements
if ( ! function_exists( 'greeny_woocommerce_extensions_product_add_to_cart_sticky_theme_setup3' ) ) {
	add_action( 'after_setup_theme', 'greeny_woocommerce_extensions_product_add_to_cart_sticky_theme_setup3', 3 );
	function greeny_woocommerce_extensions_product_add_to_cart_sticky_theme_setup3() {
		if ( greeny_exists_woocommerce()
			&& apply_filters( 'greeny_filter_woocommerce_extensions_allow_components', true, 'add_to_cart_sticky' )
		) {
			// Add 'single_product_add_to_cart_sticky' to the theme-specific options
			greeny_storage_set_array_before( 'options', 'show_related_posts_shop', apply_filters( 'greeny_filter_woocommerce_extensions_add_to_cart_sticky_args', array(
				'single_product_add_to_cart_sticky' => array(
					'title'    => esc_html__( 'Sticky "Add to Cart" Bottom Bar', 'greeny' ),
					'desc'     => wp_kses_data( __( 'Add sticky "Add to Cart" bottom bar to the single product page.', 'greeny' ) ),
					'override' => array(
						'mode'    => 'product',
						'section' => esc_html__( 'Content', 'greeny' ),
					),
					'std'      => 0,
					'type'     => 'switch',
				),
			) ) );
		}
	}
}

// Add sticky bar to the footer
if ( ! function_exists( 'greeny_woocommerce_extensions_product_add_to_cart_sticky_bottom_bar' ) ) {
	add_action( 'wp_footer', 'greeny_woocommerce_extensions_product_add_to_cart_sticky_bottom_bar' );
	function greeny_woocommerce_extensions_product_add_to_cart_sticky_bottom_bar() {
		if ( greeny_exists_woocommerce()
			&& apply_filters( 'greeny_filter_woocommerce_extensions_allow_components', true, 'add_to_cart_sticky' )
			&& (int) greeny_get_theme_option( 'single_product_add_to_cart_sticky' ) == 1
			&& is_product()
		) {
			global $product;
			// Disable slider in bottom bar
			add_filter( 'woocommerce_single_product_flexslider_enabled', '__return_false' );
			?>
			<div class="single_product_bottom_bar_sticky">
				<div class="single_product_bottom_bar_title">
					<?php
					$post_thumbnail_id = $product->get_image_id();
					if ( $post_thumbnail_id > 0 ) {
						$html = apply_filters( 'woocommerce_single_product_image_thumbnail_html', wc_get_gallery_image_html( $post_thumbnail_id ), $post_thumbnail_id );
						?><div class="single_product_bottom_bar_product_image"><?php
							echo wp_kses( $html, 'greeny_kses_content' );
						?></div><?php
					}
					?>
					<div class="single_product_bottom_bar_product_title"><?php
						the_title( '<h6 class="product_title entry-title">', '</h6>' );
					?></div>
				</div>
				<div class="single_product_bottom_bar_info">
					<div class="single_product_bottom_bar_product_price"><?php
						echo wp_kses( $product->get_price_html(), 'greeny_kses_content' );
					?></div>
					<div class="single_product_bottom_bar_product_button"><?php
						ob_start();
						woocommerce_template_single_add_to_cart();
						$output = ob_get_contents();
						ob_end_clean();
						if ( strpos( $output, '<form class="variations_form ' ) !== false ) {
							$output = '<button type="button" class="button alt single_product_bottom_bar_button_select_options">' . esc_html__( 'Select options', 'greeny' ) . '</button>';
						}
						trx_addons_show_layout( $output );
					?></div>
				</div>
			</div>
			<div class="single_product_bottom_bar_sticky_holder"></div>
			<?php
			// Enable slider again
			remove_action( 'woocommerce_single_product_flexslider_enabled', '__return_false' );	// Is equal to rf
		}
	}
}

// Add var to js
if ( ! function_exists( 'greeny_woocommerce_extensions_product_add_to_cart_sticky_localize_script' ) ) {
	add_action( 'greeny_filter_localize_script', 'greeny_woocommerce_extensions_product_add_to_cart_sticky_localize_script' );
	function greeny_woocommerce_extensions_product_add_to_cart_sticky_localize_script( $vars = array() ) {
		if ( apply_filters( 'greeny_filter_woocommerce_extensions_allow_components', true, 'add_to_cart_sticky' ) ) {
			$vars['add_to_cart_sticky'] = greeny_exists_woocommerce()
											&& is_product()
											&& (int)greeny_get_theme_option( 'single_product_add_to_cart_sticky' ) == 1;
		}
		return $vars;
	}
}



/* Add parameter 'Show Product meta'
------------------------------------------------------------------- */

// Theme init priorities:
// 3 - add/remove Theme Options elements
if ( ! function_exists( 'greeny_woocommerce_extensions_product_meta_theme_setup3' ) ) {
	add_action( 'after_setup_theme', 'greeny_woocommerce_extensions_product_meta_theme_setup3', 3 );
	function greeny_woocommerce_extensions_product_meta_theme_setup3() {
		if ( greeny_exists_woocommerce()
			&& apply_filters( 'greeny_filter_woocommerce_extensions_allow_components', true, 'product_meta' )
		) {
			// Add 'single_product_meta' to the theme-specific options
			greeny_storage_set_array_before( 'options', 'show_related_posts_shop', apply_filters( 'greeny_filter_woocommerce_extensions_product_meta_args', array(
				'single_product_meta' => array(
					'title'   => esc_html__( 'Show product meta', 'greeny' ),
					'desc'    => wp_kses_data( __( "Show Categories, Tags, SKU, Product ID on the single product page.", 'greeny' ) ),
					'std'     => 1,
					'type'    => 'switch',
				),
			) ) );
		}
	}
}


// Disable product meta
if ( ! function_exists( 'greeny_woocommerce_extensions_product_meta_disable' ) ) {
	add_action( 'woocommerce_single_product_summary', 'greeny_woocommerce_extensions_product_meta_disable', 1 );
	function greeny_woocommerce_extensions_product_meta_disable() {
		if ( greeny_exists_woocommerce()
			&& apply_filters( 'greeny_filter_woocommerce_extensions_allow_components', true, 'product_meta' )
			&& is_product()
		) {
			if ( (int) greeny_get_theme_option( 'single_product_meta' ) == 0 ) {
				remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_meta', 40 );
			}
		}
	}
}



/* Add tabs to the single product details
------------------------------------------------------------------- */

// Theme init priorities:
// 3 - add/remove Theme Options elements
if ( ! function_exists( 'greeny_woocommerce_extensions_custom_tabs_theme_setup3' ) ) {
	add_action( 'after_setup_theme', 'greeny_woocommerce_extensions_custom_tabs_theme_setup3', 3 );
	function greeny_woocommerce_extensions_custom_tabs_theme_setup3() {
		if ( greeny_exists_woocommerce()
			&& apply_filters( 'greeny_filter_woocommerce_extensions_allow_components', true, 'custom_tabs' )
		) {
			// Add 'single_product_custom_tabs' to the theme-specific options
			greeny_storage_set_array_before( 'options', 'show_related_posts_shop', apply_filters( 'greeny_filter_woocommerce_extensions_custom_tabs_args', array(
				'single_product_custom_tabs' => array(
					"title" => esc_html__( "Tabs manager", 'greeny' ),
					"desc" => wp_kses_data( __( "Manage tabs in the Details section of the single product: hide any tab, reorder tabs, add an icon to the tab title, change a title, add new tabs with custom content", 'greeny' ) ),
					'override'   => array(
						'mode'    => 'product',
						'section' => esc_html__( 'Content', 'greeny' ),
					),
					"clone" => true,
					"std" => array(
						array( 'tab_title' => esc_html__( "Description", 'greeny' ), 'tab_slug' => 'description', 'tab_icon' => '', 'tab_show' => 1, 'tab_content' => '' ),
						array( 'tab_title' => esc_html__( "Reviews (%d)", 'greeny' ), 'tab_slug' => 'reviews', 'tab_icon' => '', 'tab_show' => 1, 'tab_content' => '' ),
						array( 'tab_title' => esc_html__( "Additional information", 'greeny' ), 'tab_slug' => 'additional_information', 'tab_icon' => '', 'tab_show' => 1, 'tab_content' => '' ),
					),
					"type" => "group",
					"fields" => array(
						"tab_show" => array(
							"title" => esc_html__( "Tab visible", 'greeny' ),
							"class" => "greeny_column-1_4",
							"std" => 1,
							"type" => "switch"
						),
						"tab_title" => array(
							"title" => esc_html__( "Tab title", 'greeny' ),
							"class" => "greeny_column-1_4",
							"std" => "",
							"type" => "text"
						),
						"tab_slug" => array(
							"title" => esc_html__( "Tab slug", 'greeny' ),
							"class" => "greeny_column-1_4",
							"std" => "",
							"type" => "text"
						),
						"tab_icon" => array(
							"title" => esc_html__( "Tab icon", 'greeny' ),
							"class" => "greeny_column-1_4",
							"std" => "",
							"type" => "icon"
						),
						"tab_content" => array(
							"title" => esc_html__( "Tab content", 'greeny' ),
							"desc" => esc_html__( "Only reordering, changing a title and visibility are allowed for standard WooCommerce tabs (Reviews, Description and Additional information). Don't fill a content for them - this is allowed only for custom tabs.", 'greeny')
									. '<br>'
									. esc_html__( "You can use a macros %%TITLE%% and %%PRICE%% to insert a title and a price of this product to the content.", 'greeny'),
							"class" => "greeny_column-1_1",
							"std" => "",
							"type" => "text_editor"
						),
					)
				),
			) ) );
		}
	}
}

// Add custom tabs (if defined for the current product)
if ( ! function_exists( 'greeny_woocommerce_extensions_custom_tabs_add_to_product_tabs' ) ) {
	add_filter( 'woocommerce_product_tabs', 'greeny_woocommerce_extensions_custom_tabs_add_to_product_tabs' );
	function greeny_woocommerce_extensions_custom_tabs_add_to_product_tabs( $tabs = array() ) {
		if ( apply_filters( 'greeny_filter_woocommerce_extensions_allow_components', true, 'custom_tabs' ) ) {
			global $product;
			$custom_tabs = greeny_get_theme_option( 'single_product_custom_tabs' );
			if ( is_array( $custom_tabs ) ) {
				$priority = 0;
				$new_tabs = array();
				foreach ( $custom_tabs as $tab ) {
					if ( ! empty( $tab['tab_title'] ) && (int) $tab['tab_show'] > 0 ) {
						$priority += 10;
						$slug = sanitize_title( ! empty( $tab['tab_slug'] ) ? $tab['tab_slug'] : $tab['tab_title'] );
						$new_tabs[ $slug ] = isset( $tabs[ $slug ] )
												? $tabs[ $slug ]
												: array( 'callback' => 'greeny_woocommerce_extensions_custom_tabs_template' );
						$new_tabs[ $slug ][ 'title' ] = ( ! greeny_is_off( $tab['tab_icon'] )
												 			? '<span class="woocommerce-tab-icon ' . esc_attr( $tab['tab_icon'] ) . '"></span>'
												 			: ''
												 			)
														. ( 'reviews' == $slug && is_object( $product )
															? str_replace( '%d', $product->get_review_count(), $tab['tab_title'] )
															: $tab['tab_title']
															);
						$new_tabs[ $slug ][ 'priority' ] = $priority;
					}
				}
				$tabs = $new_tabs;
			}
		}
		return $tabs;
	}
}

// Display custom tabs
if ( ! function_exists( 'greeny_woocommerce_extensions_custom_tabs_template' ) ) {
	function greeny_woocommerce_extensions_custom_tabs_template( $tab_slug, $tab_data ) {
		global $product;
		$custom_tabs = greeny_get_theme_option( 'single_product_custom_tabs' );
		if ( is_array( $custom_tabs ) && is_object( $product ) ) {
			foreach ( $custom_tabs as $tab ) {
				$slug = sanitize_title( $tab['tab_title'] );
				if ( $slug == $tab_slug ) {
					$title = get_the_title();
					$price = $product->get_price_html();
					greeny_show_layout(
						str_ireplace(
							array( '%%TITLE%%', '%%PRICE%%' ),
							array( $title,      $price ),
							$tab['tab_content']
						)
					);
				}
			}
		}
	}
}



/* Add section 'Rating details' to the single product
------------------------------------------------------------------- */

// Replace core Woocommerce tabs output
if ( ! function_exists( 'greeny_woocommerce_extensions_replace_woocommerce_core_tabs' ) ) {
	add_action( 'woocommerce_after_single_product_summary', 'greeny_woocommerce_extensions_replace_woocommerce_core_tabs', 1 );
	function greeny_woocommerce_extensions_replace_woocommerce_core_tabs() {
		if ( apply_filters( 'greeny_filter_woocommerce_extensions_allow_components', true, 'rating_details' ) ) {
			global $product;
			if ( is_object( $product ) && function_exists( 'woocommerce_output_product_data_tabs' ) ) {
				$rating_count = $product->get_rating_count();
				if ( $rating_count > 0 ) {
					remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_product_data_tabs', 10 );
					add_action( 'woocommerce_after_single_product_summary', 'greeny_woocommerce_extensions_add_rating_details', 10 );
				}
			}
		}
	}
}

// Add 'Rating Details' to the tab 'Reviews'
if ( ! function_exists( 'greeny_woocommerce_extensions_add_rating_details' ) ) {
	// Handler of the add_action( 'woocommerce_after_single_product_summary', 'greeny_woocommerce_extensions_add_rating_details', 10 );
	function greeny_woocommerce_extensions_add_rating_details() {
		global $product;
		// Capture a tabs output
		ob_start();
		woocommerce_output_product_data_tabs();
		$output = ob_get_contents();
		ob_end_clean();
		// Inject the section 'Rating Details'
		$rating_count  = $product->get_rating_count();
		$review_count  = $product->get_review_count();
		$average       = $product->get_average_rating();
		$html          = '<h5 class="rating_details_title">' . esc_html__( 'Average rating', 'greeny' ) . '</h5>'
						. '<div class="rating_details_avg">' . esc_html( $average ) . '</div>'
						. '<div class="rating_details_avg_stars">' . wc_get_rating_html( $average, $rating_count ) . '</div>'
						. '<div class="rating_details_avg_total">' . esc_html( sprintf( _n( '%d review', '%d reviews', $review_count, 'greeny' ), $review_count ) ) . '</div>';
		$rating_counts = $product->get_rating_counts();
		if ( is_array( $rating_counts ) ) {
			$rating_counts = greeny_array_merge( array( 5 => 0, 4 => 0, 3 => 0, 2 => 0, 1 => 0 ), $rating_counts );
			$html .= '<div class="rating_details_table">';
			foreach( $rating_counts as $mark => $total ) {
				$html .= '<div class="rating_details_table_row">'
							. '<div class="rating_details_table_cell rating_details_table_cell_mark">' . esc_html( sprintf( _n( '%d star', '%d stars', $mark, 'greeny' ), $mark ) ) . '</div>'
							. '<div class="rating_details_table_cell rating_details_table_cell_bar">'
									. '<div class="rating_details_table_cell_bar_fill_' . esc_attr( $total ) . '"></div>'
								. '</div>'
							. '<div class="rating_details_table_cell rating_details_table_cell_total">' . esc_html( apply_filters( 'greeny_filter_woocommerce_extensions_rating_details_as_percent', true ) ? round( $total / $rating_count * 100 ) . '%' : $total ) . '</div>'
							. '</div>';
			}
			$html .= '</div>';
		}
		$output = str_replace(
						'<div id="comments',
						'<div class="rating_details">' . $html . '</div>'
						. '<div id="comments',
						$output
						);
		// Display tabs
		greeny_show_layout( $output );
	}
}



/* Add parameter "Product's Video"
------------------------------------------------------------------- */
if ( ! function_exists( 'greeny_woocommerce_extensions_product_video_setup' ) ) {
	add_action( 'after_setup_theme', 'greeny_woocommerce_extensions_product_video_setup' );
	function greeny_woocommerce_extensions_product_video_setup() {
		if ( greeny_exists_trx_addons()
			&& greeny_exists_woocommerce()
			&& apply_filters( 'greeny_filter_woocommerce_extensions_allow_components', true, 'product_video' )
		) {
			if ( is_admin() ) {
				add_filter( 'greeny_filter_override_options', 'greeny_woocommerce_extensions_product_video_add_options' );
				add_filter( 'greeny_filter_update_post_options', 'greeny_woocommerce_extensions_product_video_update_options', 10, 3 );
			}
		}
	}
}

// After 'wp' options from the post meta are available
if ( ! function_exists( 'greeny_woocommerce_extensions_product_video_init' ) ) {
	add_action( 'wp', 'greeny_woocommerce_extensions_product_video_init', 20 );
	function greeny_woocommerce_extensions_product_video_init() {
		if ( greeny_exists_trx_addons()
			&& greeny_exists_woocommerce()
			&& apply_filters( 'greeny_filter_woocommerce_extensions_allow_components', true, 'product_video' )
		) {
			if ( ! is_admin() ) {
				// Add video to the gallery
				if ( greeny_get_theme_option( 'woocommerce_extensions_product_video_position' ) == 'first_thumb' ) {
					// Place video first (before main video)
					add_filter( 'woocommerce_single_product_image_thumbnail_html', 'greeny_woocommerce_extensions_product_video_add_thumbnails', 10, 2 );
				} else {
					// Place video last (after all thumbnails)
					add_action( 'woocommerce_product_thumbnails', 'greeny_woocommerce_extensions_product_video_add_thumbnails', 100 );
				}
			}
		}
	}
}

// Add options to the product
if ( ! function_exists( 'greeny_woocommerce_extensions_product_video_add_options' ) ) {
	//Handler of the add_filter('greeny_filter_override_options', 'greeny_woocommerce_extensions_product_video_add_options');
	function greeny_woocommerce_extensions_product_video_add_options( $list ) {
		if ( apply_filters( 'greeny_filter_woocommerce_extensions_allow_components', true, 'product_video' ) ) {
			global $post_type;
			if ( 'product' == $post_type && greeny_options_allow_override( $post_type ) ) {
				$list[] = array(
					'greeny_woocommerce_extensions_product_video_options',
					esc_html__( 'Product video', 'greeny' ),
					'greeny_woocommerce_extensions_product_video_options_show',
					$post_type,
					'side',
					'default',
				);
			}
		}
		return $list;
	}
}

// Return options to the product video
if ( ! function_exists( 'greeny_woocommerce_extensions_product_video_get_options' ) ) {
	function greeny_woocommerce_extensions_product_video_get_options() {
		return apply_filters( 'greeny_filter_woocommerce_extensions_product_video_options', array(
					'woocommerce_extensions_product_video_url'   => array(
						"title" => esc_html__( "Video URL", 'greeny' ),
						"desc" => wp_kses_data( __('Specify URL to show a videoplayer from Youtube, Vimeo or other compatible video hosting', 'greeny') ),
						"std" => "",
						"type" => "text"
					),
					'woocommerce_extensions_product_video_image' => array(
						"title" => esc_html__( "Cover image", 'greeny' ),
						"desc" => wp_kses_data( __( "Select an image to be used as a video cover", 'greeny' ) ),
						"std" => "",
						"type" => "image"
					),
					'woocommerce_extensions_product_video_position' => array(
						"title" => esc_html__( "Video position", 'greeny' ),
						"desc" => wp_kses_data( __( "Select a position of the video in the galery", 'greeny' ) ),
						"std" => "first_thumb",
						"options" => apply_filters( 'greeny_filter_woocommerce_extensions_product_video_positions', array(
							'first_thumb' => esc_html__( 'First thumb', 'greeny' ),
							'last_thumb' => esc_html__( 'Last thumb', 'greeny' ),
						) ),
						"type" => "select"
					),
					'woocommerce_extensions_product_video_in_popup' => array(
						"title" => esc_html__( "Open video in popup", 'greeny' ),
						"desc" => wp_kses_data( __( "Open video in a popup or embed to the gallery", 'greeny' ) ),
						"std" => 1,
						"type" => "switch"
					),
					'woocommerce_extensions_product_video_button_position' => array(
						"title" => esc_html__( "Button position", 'greeny' ),
						"desc" => wp_kses_data( __( "Select a position of the button to open popup", 'greeny' ) ),
						"std" => "lb",
						"dependency" => array(
							'compare' => 'or',
							'woocommerce_extensions_product_video_in_popup' => array( 1 ),
							'woocommerce_extensions_product_video_image' => array( 'not_empty' )
						),
						"options" => apply_filters( 'greeny_filter_woocommerce_extensions_product_video_button_positions', array(
							'lt' => esc_html__( 'Top Left', 'greeny' ),
							'rt' => esc_html__( 'Top Right', 'greeny' ),
							'cc' => esc_html__( 'Middle Center', 'greeny' ),
							'lb' => esc_html__( 'Bottom Left', 'greeny' ),
							'rb' => esc_html__( 'Bottom Right', 'greeny' )
						) ),
						"type" => "select"
					),
					'woocommerce_extensions_product_video_ratio' => array(
						"title" => esc_html__( "Video ratio", 'greeny' ),
						"desc" => wp_kses_data( __( "Select a ratio of the video", 'greeny' ) ),
						"std" => "16:9",
						"dependency" => array(
							'woocommerce_extensions_product_video_in_popup' => array( 0 ),
							'woocommerce_extensions_product_video_image' => array( 'is_empty' )
						),
						"options" => apply_filters( 'greeny_filter_woocommerce_extensions_product_video_ratio', array(
							'2:1'  => esc_html__( '2:1', 'greeny' ),
							'17:9' => esc_html__( '17:9', 'greeny' ),
							'16:9' => esc_html__( '16:9', 'greeny' ),
							'4:3'  => esc_html__( '4:3', 'greeny' ),
							'1:1'  => esc_html__( '1:1', 'greeny' ),
							'3:4'  => esc_html__( '3:4', 'greeny' ),
							'9:16' => esc_html__( '9:16', 'greeny' ),
							'9:17' => esc_html__( '9:17', 'greeny' ),
							'1:2'  => esc_html__( '1:2', 'greeny' ),
						)),
						"type" => "select"
					),
		) );
	}
}


// Add extension-specific dependencies to the general theme dependencies
if ( ! function_exists( 'greeny_woocommerce_extensions_product_video_dependencies' ) ) {
	add_filter( 'greeny_filter_get_theme_dependencies', 'greeny_woocommerce_extensions_product_video_dependencies' );
	function greeny_woocommerce_extensions_product_video_dependencies( $depends ) {
		if ( apply_filters( 'greeny_filter_woocommerce_extensions_allow_components', true, 'product_video' ) ) {
			$options = greeny_woocommerce_extensions_product_video_get_options();
			foreach ( $options as $k => $v ) {
				if ( isset( $v['dependency'] ) ) {
					$depends[ $k ] = $v['dependency'];
				}
			}
		}
		return $depends;
	}
}

// Callback function to show product options
if ( ! function_exists( 'greeny_woocommerce_extensions_product_video_options_show' ) ) {
	function greeny_woocommerce_extensions_product_video_options_show( $post = false, $args = false ) {
		if ( empty( $post ) || ! is_object( $post ) || empty( $post->ID ) ) {
			global $post, $post_type;
			$mb_post_id   = $post->ID;
			$mb_post_type = $post_type;
		} else {
			$mb_post_id   = $post->ID;
			$mb_post_type = $post->post_type;
		}
		if ( 'product' == $mb_post_type && greeny_options_allow_override( $mb_post_type ) ) {
			// Load saved options
			$meta = get_post_meta( $mb_post_id, 'greeny_options', true );
			$options = greeny_woocommerce_extensions_product_video_get_options();
			?>
			<div class="greeny_options greeny_product_options greeny_product_video_options">
				<div class="greeny_options_section">
					<?php
					foreach ( $options as $k => $v ) {
						$v['val'] = isset( $meta[ $k ] ) ? $meta[ $k ] : $v['std'];
						greeny_show_layout( greeny_options_show_field( $k, $v ) );
					}
					?>
				</div>
			</div>
			<?php
		}
	}
}


// Save product options
if ( ! function_exists( 'greeny_woocommerce_extensions_product_video_update_options' ) ) {
	//Handler of the add_filter( 'greeny_filter_update_post_options', 'greeny_woocommerce_extensions_product_video_update_options', 10, 3 );
	function greeny_woocommerce_extensions_product_video_update_options( $meta, $post_id, $post_type = '' ) {
		if ( apply_filters( 'greeny_filter_woocommerce_extensions_allow_components', true, 'product_video' )
			&& 'product' == $post_type
		) {
			$options = greeny_woocommerce_extensions_product_video_get_options();
			foreach ( $options as $k => $v ) {
				// Get option value from POST
				$meta[ $k ] = isset( $_POST[ "greeny_options_field_{$k}" ] )
								? greeny_get_value_gp( "greeny_options_field_{$k}" )
								: ( 'checkbox' == $v['type'] ? 0 : '' );
			}
		}
		return $meta;
	}
}



// Add thumb with video to the gallery
if ( ! function_exists( 'greeny_woocommerce_extensions_product_video_add_thumbnails' ) ) {
	//Handler of the add_action( 'woocommerce_product_thumbnails', 'greeny_woocommerce_extensions_product_video_add_thumbnails', 1 | 100 );
	//Handler of the add_filter( 'woocommerce_single_product_image_thumbnail_html', 'greeny_woocommerce_extensions_product_video_add_thumbnails', 10, 2 );
	function greeny_woocommerce_extensions_product_video_add_thumbnails( $image_html = '', $post_thumbnail_id = 0 ) {
		if ( apply_filters( 'greeny_filter_woocommerce_extensions_allow_components', true, 'product_video' ) ) {
			// Remove filter to prevent resursion
			if ( has_filter( 'woocommerce_single_product_image_thumbnail_html', 'greeny_woocommerce_extensions_product_video_add_thumbnails' ) ) {
				remove_action( 'woocommerce_single_product_image_thumbnail_html', 'greeny_woocommerce_extensions_product_video_add_thumbnails', 10 );	// Is equal to rf
			}
			// Add thumb with video (if specified)
			$video = greeny_get_theme_option( 'woocommerce_extensions_product_video_url' );
			if ( ! empty( $video ) ) {
				$popup      = greeny_get_theme_option( 'woocommerce_extensions_product_video_in_popup' );
				$cover      = greeny_get_theme_option( 'woocommerce_extensions_product_video_image' );
				$cover_id   = ! empty( $cover ) ? greeny_attachment_url_to_postid( $cover ) : 0;
				$button_pos = greeny_get_theme_option( 'woocommerce_extensions_product_video_button_position' );
				// Prepare layout with thumb
				if ( $cover_id ) {
					$html = wc_get_gallery_image_html( $cover_id );
				} else {
					$cover     = wc_placeholder_img_src( 'woocommerce_single' );
					$cover_id  = $cover;
					$cover_alt = esc_html__( 'Product video', 'greeny' );
					$html  = sprintf( '<div class="woocommerce-product-gallery__image woocommerce-product-gallery__image--placeholder" data-thumb="%1$s" data-thumb-alt="%2$s">',
										esc_url( $cover ),
										$cover_alt
									);
					$style = greeny_get_theme_option( 'single_product_gallery_style', 'default' );
					if ( $popup || ! in_array( $style, array( 'cascade', 'grid' ) ) ) {
						$html .= '<a href="' . esc_url( $cover ) . '">'
									. sprintf( '<img src="%1$s" alt="%2$s" class="wp-post-image" />',
												esc_url( $cover ),
												$cover_alt
											)
									. '</a>';
					} else {
						$cover    = '';
						$cover_id = '';
					}
					$html .= '</div>';
				}
				// Add class to the image wrap
				$html = str_replace(
							'"woocommerce-product-gallery__image',
							'"woocommerce-product-gallery__image'
								. ' woocommerce-product-gallery__image--with_video'
								. ( $cover
									? ' woocommerce-product-gallery__image--video_button_' . esc_attr( $button_pos )
									: ' woocommerce-product-gallery__image--video_present'
										. ' woocommerce-product-gallery__image--video_ratio_' . str_replace( ':', '_', greeny_get_theme_option( 'woocommerce_extensions_product_video_ratio' ) )
									),
							$html
						);
				// If video in the popup - add a 'play' button
				if ( $popup ) {
					$popup_layout = explode(
									'<!-- .sc_layouts_popup -->',
									greeny_get_video_layout( array(
																	'link'  => $video,
																	'embed' => '',
																	'cover' => $cover_id,
																	'cover_size' => 'full',
																	'show_cover' => false,
																	'popup' => true
																	)
																)
									);
					if ( ! empty( $popup_layout[0] ) && ! empty( $popup_layout[1] ) ) {
						if ( preg_match( '/<a .*<\/a>/', $popup_layout[0], $matches ) && ! empty( $matches[0] ) ) {
							// Remove link to the lightbox
							//$html = preg_replace( '/(.*)(<a[^>]*>)(.*)(<\/a>)(.*)/', '$1$3$5', $html );
							//$html = str_replace( 'data-large_image', 'data-dummy_image', $html );
							// Add popup and button
							$html = str_replace(
										'</div>',
										sprintf( '<div class="post_video_hover post_video_hover_popup">%1$s</div>%2$s',
													 $matches[0],
													 $popup_layout[1]
												)
										. '</div>',
										$html
									);
						}
					}
				} else {
					$video_layout = greeny_get_video_layout( array(
																	'link'  => $video,
																	'embed' => '',
																	'cover' => $cover_id,
																	'cover_size' => 'full',
																	'show_cover' => false,
																	'popup' => false
																	)
																);
					$html = str_replace(
								'</div>',
								sprintf( '<div class="woocommerce-product-gallery__image--video_wrap">%s</div>', $video_layout )
								. '</div>',
								$html
							);
				}
				$html = apply_filters( 'woocommerce_single_product_image_thumbnail_html', $html, $cover );
				if ( ! empty( $image_html ) ) {
					$image_html = trim( $html ) . trim( $image_html );
				} else {
					greeny_show_layout( $html );
				}
			}
		}
		return $image_html;
	}
}











/* Add parameter 'Add attributes to the products list'
------------------------------------------------------------------- */

// Theme init priorities:
// 3 - add/remove Theme Options elements
if ( ! function_exists( 'greeny_woocommerce_extensions_add_attributes_theme_setup3' ) ) {
	add_action( 'after_setup_theme', 'greeny_woocommerce_extensions_add_attributes_theme_setup3', 3 );
	function greeny_woocommerce_extensions_add_attributes_theme_setup3() {
		if ( greeny_exists_woocommerce()
			&& apply_filters( 'greeny_filter_woocommerce_extensions_allow_components', true, 'add_attributes_to_product_list' )
		) {
			// Add parameter to the theme-specific options
			greeny_storage_set_array_after( 'options', 'shop_pagination', apply_filters( 'greeny_filter_woocommerce_extensions_add_attributes_to_products_list_args', array(
				'add_attributes_to_products_list' => array(
					'title'   => esc_html__( 'Add attributes to the products list', 'greeny' ),
					'desc'    => wp_kses_data( __( "Display selected attributes in products on the shop page.", 'greeny' ) ),
					'std'     => '',
					'dir'     => 'vertical',
					'sortable'=> true,
					'options' => array(),
					'type'    => 'checklist',
				),
				'action_on_attribute_click' => array(
					'title'   => esc_html__( 'Action on attribute click', 'greeny' ),
					'desc'    => wp_kses_data( __( "Select an action on attribute click: open a single product page or apply a filter.", 'greeny' ) ),
					'std'     => 'none',
					'options' => array(
						'none'   => esc_html__( 'No action', 'greeny' ),
						'link'   => esc_html__( 'Open product', 'greeny' ),
						'filter' => esc_html__( 'Apply filter', 'greeny' ),
					),
					'type'    => 'radio',
				),
				'swap_on_attribute_hover' => array(
					'title' => esc_html__( 'Swap images on attribute hover', 'greeny' ),
					'desc'  => wp_kses_data( __( "Swap a product image (only for variable products) on attribute hover.", 'greeny' ) ),
					'std'   => 0,
					'type'  => 'switch',
				),
			) ) );
		}
	}
}


// Return lists with choises when its need in the admin mode
if ( ! function_exists( 'greeny_woocommerce_extensions_add_attributes_get_list_choises' ) ) {
	add_filter( 'greeny_filter_options_get_list_choises', 'greeny_woocommerce_extensions_add_attributes_get_list_choises', 10, 2 );
	function greeny_woocommerce_extensions_add_attributes_get_list_choises( $list, $id ) {
		if ( is_array( $list ) && count( $list ) == 0 ) {
			if ( strpos( $id, 'add_attributes_to_products_list' ) === 0 ) {
				$list = greeny_woocommerce_extensions_get_list_wc_attributes();
			}
		}
		return $list;
	}
}


// Return list of the WooCommerce attributes
if ( !function_exists( 'greeny_woocommerce_extensions_get_list_wc_attributes' ) ) {
	function greeny_woocommerce_extensions_get_list_wc_attributes() {
		static $list = false;
		if ( $list === false ) {
			$list = array();
			$attribute_taxonomies = wc_get_attribute_taxonomies();
			if ( ! empty( $attribute_taxonomies ) ) {
				foreach ( $attribute_taxonomies as $attribute ) {
					$list[ wc_attribute_taxonomy_name( $attribute->attribute_name ) ] = $attribute->attribute_label;
				}
			}
		}
		return apply_filters( 'greeny_filter_woocommerce_extensions_get_list_wc_attributes', $list );
	}
}


// Display selected attribute
if ( ! function_exists( 'greeny_woocommerce_extensions_show_attributes' ) ) {
	add_action( 'woocommerce_after_shop_loop_item', 'greeny_woocommerce_extensions_show_attributes', 18 );         // 20 is used to close item wrapper
	add_action( 'woocommerce_after_shop_loop_item_title', 'greeny_woocommerce_extensions_show_attributes', 8 );    //  5 is used to display rating
																													//  7 is used to display excerpt (in the list mode)
																													// 10 is used to display price
	function greeny_woocommerce_extensions_show_attributes() {
		if ( apply_filters( 'greeny_filter_woocommerce_extensions_allow_components', true, 'add_attributes_to_product_list' ) ) {
			$attributes = greeny_get_theme_option( 'add_attributes_to_products_list' );
			if ( ! empty( $attributes ) && ! greeny_is_off( $attributes ) && apply_filters( 'greeny_filter_woocommerce_extensions_show_attributes', is_shop(), $attributes ) ) {
				$mode = greeny_storage_get( 'shop_mode' );
				if ( ( 'woocommerce_after_shop_loop_item_title' == current_action() && 'list' == $mode )
					||
					( 'woocommerce_after_shop_loop_item' == current_action() && 'thumbs' == $mode )
				) {
					do_action( 'greeny_action_product_attributes', apply_filters( 'greeny_filter_woocommerce_extensions_show_attributes_args', array(
								'attributes' => $attributes,
								'action'     => greeny_get_theme_option( 'action_on_attribute_click' ),
								'swap'       => greeny_get_theme_option( 'swap_on_attribute_hover' )
								) ) );
				}
			}
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
		if ( greeny_exists_woocommerce()
			&& apply_filters( 'greeny_filter_woocommerce_extensions_allow_components', true, 'product_style' )
		) {
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
		if ( apply_filters( 'greeny_filter_woocommerce_extensions_allow_components', true, 'product_style' ) ) {
			if ( $slug == 'content' && $name == 'product'
				&& function_exists( 'trx_addons_woocommerce_extended_products_get_layouts' )
				&& ( ! function_exists( 'trx_addons_sc_stack_check' )
					 ||
					 ! trx_addons_sc_stack_check( 'trx_sc_extended_products' )
					)
				&& ! is_product()	// To prevent replace template of related products in the single product page
			) {
				$style = greeny_get_theme_option( 'product_style' );
				if ( 'default' != $style ) {
					$layouts = trx_addons_woocommerce_extended_products_get_layouts();
					if ( isset( $layouts[ $style ] ) && ! empty( $layouts[ $style ]['template'] ) ) {
						$template = $layouts[ $style ]['template'];
					}
				}
			}
		}
		return $template;
	}
}


// Add class with a "product style" to the wrap ul.products
// ( if we are not inside a shortcode 'trx_sc_extended_products' )
if ( ! function_exists( 'greeny_woocommerce_extensions_add_product_style_to_products_wrap' ) ) {
	add_filter( 'woocommerce_product_loop_start', 'greeny_woocommerce_extensions_add_product_style_to_products_wrap', 200, 1 );
	function greeny_woocommerce_extensions_add_product_style_to_products_wrap( $template ) {
		if ( apply_filters( 'greeny_filter_woocommerce_extensions_allow_components', true, 'product_style' ) ) {
			if ( function_exists( 'trx_addons_woocommerce_extended_products_get_layouts' )
				&& ( ! function_exists( 'trx_addons_sc_stack_check' )
					 ||
					 ! trx_addons_sc_stack_check( 'trx_sc_extended_products' )
					)
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
		}
		return $template;
	}
}


// Add class with a "product style" to each product item
if ( ! function_exists( 'greeny_woocommerce_extensions_add_product_style_to_product_items' ) ) {
	add_filter( 'woocommerce_post_class', 'greeny_woocommerce_extensions_add_product_style_to_product_items', 200, 2 );
	function greeny_woocommerce_extensions_add_product_style_to_product_items( $classes, $product ) {
		if ( apply_filters( 'greeny_filter_woocommerce_extensions_allow_components', true, 'product_style' ) ) {
			if ( function_exists( 'trx_addons_woocommerce_extended_products_get_layouts' )
				&& ( ! function_exists( 'trx_addons_sc_stack_check' )
					 ||
					 ! trx_addons_sc_stack_check( 'trx_sc_extended_products' )
					)
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
		}
		return $classes;
	}
}



/* Add parameter 'Brand attribute'
------------------------------------------------------------------- */

// Theme init priorities:
// 3 - add/remove Theme Options elements
if ( ! function_exists( 'greeny_woocommerce_extensions_add_brand_theme_setup3' ) ) {
	add_action( 'after_setup_theme', 'greeny_woocommerce_extensions_add_brand_theme_setup3', 3 );
	function greeny_woocommerce_extensions_add_brand_theme_setup3() {
		if ( greeny_exists_woocommerce()
			&& apply_filters( 'greeny_filter_woocommerce_extensions_allow_components', true, 'add_brand' )
		) {
			// Add parameter to the theme-specific options
			greeny_storage_set_array_before( 'options', 'fonts', apply_filters( 'greeny_filter_woocommerce_extensions_add_brand_args', array(
				'shop_brand_section'         => array(
					'title'      => esc_html__( 'Brands', 'greeny' ),
					'desc'       => wp_kses_data( __( 'Settings of the "Brand" attribute', 'greeny' ) ),
					'icon'       => 'icon-star',
					'type'       => 'section',
				),
				'shop_brand_info'            => array(
					'title'      => esc_html__( 'Brand settings', 'greeny' ),
					'desc'       => wp_kses_data( __( 'Settings of the "Brand" attribute', 'greeny' ) ),
					'type'       => 'info',
				),
				'brand_attribute'            => array(
					'title'   => esc_html__( "'Brand' attribute", 'greeny' ),
					'desc'    => wp_kses_data( __( "Use selected attribute as 'Brand' (display it after title).", 'greeny' ) ),
					'std'     => 'none',
					'options' => array(),
					'type'    => 'select',
				),
				'brand_attribute_on_shop'    => array(
					'title'  => esc_html__( "Show 'Brand' on the shop page", 'greeny' ),
					'desc'   => wp_kses_data( __( "Show attribute 'Brand' in the each product on the shop page", 'greeny' ) ),
					'std'    => 0,
					'type'   => 'switch',
				),
				'brand_attribute_on_product' => array(
					'title'  => esc_html__( "Show 'Brand' on the single product page", 'greeny' ),
					'desc'   => wp_kses_data( __( "Show 'Brand' on the single product page", 'greeny' ) ),
					'std'    => 1,
					'type'   => 'switch',
				),
				'brand_attribute_label'      => array(
					'title'  => esc_html__( "Label before 'Brand'", 'greeny' ),
					'desc'   => wp_kses_data( __( "Some text to display before the 'Brand' on the single product page", 'greeny' ) ),
					'std'    => '',
					'type'   => 'text',
				),
			) ) );
		}
	}
}


// Return lists with choises when its need in the admin mode
if ( ! function_exists( 'greeny_woocommerce_extensions_add_brand_get_list_choises' ) ) {
	add_filter( 'greeny_filter_options_get_list_choises', 'greeny_woocommerce_extensions_add_brand_get_list_choises', 10, 2 );
	function greeny_woocommerce_extensions_add_brand_get_list_choises( $list, $id ) {
		if ( is_array( $list ) && count( $list ) == 0 ) {
			if ( strpos( $id, 'brand_attribute' ) === 0 ) {
				$list = array_merge( array(
							'none' => greeny_get_not_selected_text( esc_html__( 'Select attribute', 'greeny' ) )
							), greeny_woocommerce_extensions_get_list_wc_attributes() );
			}
		}
		return $list;
	}
}


// Remove brand attribute from attributes list
if ( ! function_exists( 'greeny_woocommerce_extensions_remove_brand_from_attributes_list' ) ) {
	add_filter( 'woocommerce_display_product_attributes', 'greeny_woocommerce_extensions_remove_brand_from_attributes_list', 10, 2 );
	function greeny_woocommerce_extensions_remove_brand_from_attributes_list( $attributes, $product ) {
		if ( apply_filters( 'greeny_filter_woocommerce_extensions_allow_components', true, 'add_brand' ) ) {
			$brand = greeny_get_theme_option( 'brand_attribute' );
			if ( ! empty( $brand )
				&& ! greeny_is_off( $brand )
				&& (int) greeny_get_theme_option( 'brand_attribute_on_' . ( is_product() ? 'product' : 'shop' ) ) > 0
				&& taxonomy_exists( $brand )
				&& apply_filters( 'greeny_filter_woocommerce_extensions_show_brand', true )
			) {
				$key = "attribute_{$brand}";
				if ( isset( $attributes[ $key ] ) ) {
					unset( $attributes[ $key ] );
				}
			}
		}
		return $attributes;
	}
}


// Display selected attribute as brand (after title) on single product page and on the shop page
if ( ! function_exists( 'greeny_woocommerce_extensions_show_brand' ) ) {
	add_action( 'woocommerce_single_product_summary', 'greeny_woocommerce_extensions_show_brand', 6 );     // woocommerce_single_product_summary:5 is used to display a product title
	add_action( 'woocommerce_after_shop_loop_item_title', 'greeny_woocommerce_extensions_show_brand', 6 ); // woocommerce_after_shop_loop_item_title:5 is used to display a product rating
																											// woocommerce_shop_loop_item_title:10 is used to display a product title
	function greeny_woocommerce_extensions_show_brand() {
		if ( apply_filters( 'greeny_filter_woocommerce_extensions_allow_components', true, 'add_brand' ) ) {
			global $product;
			$brand = greeny_get_theme_option( 'brand_attribute' );
			$show  = (int) greeny_get_theme_option( 'brand_attribute_on_' . ( current_action() == 'woocommerce_single_product_summary' ? 'product' : 'shop' ) );
			if ( ! empty( $brand ) && ! greeny_is_off( $brand ) && taxonomy_exists( $brand ) && is_object( $product ) && apply_filters( 'greeny_filter_woocommerce_extensions_show_brand', $show ) ) {
				$type = greeny_woocommerce_extensions_attrib_get_type( $brand );
				$terms = wc_get_product_terms( $product->get_id(), $brand, array( 'fields' => 'all' ) );
				if ( is_array( $terms ) && count( $terms ) > 0 ) {
					?><div class="woocommerce_extensions_brand"><?php
					foreach ( $terms as $term ) {
						$term_val = $term->name;
						$term_image = '';
						$term_color = '';
						if ( $type == 'image' ) {
							$term_image = get_term_meta( $term->term_id, 'value', true );
							$term_image = ! empty( $term_image ) && greeny_is_url( $term_image )
											? greeny_add_thumb_size( $term_image, greeny_get_thumb_size( 'masonry' ) )
											: '';
						} else if ( $type == 'color' ) {
							$term_color = get_term_meta( $term->term_id, 'value', true );
							if ( empty( $term_color ) ) {
								$term_color = $term->slug;
							}
						}
						?><span class="woocommerce_extensions_brand_item"><?php
							if ( $type == 'image' && ! empty( $term_image ) ) {
								?><img src="<?php echo esc_url( $term_image ); ?>" class="woocommerce_extensions_brand_item_image" alt="<?php echo esc_attr( $term_val ); ?>"><?php
							}
							// Label
							$label = greeny_get_theme_option( 'brand_attribute_label' );
							if ( ! empty( $label ) && apply_filters( 'greeny_filter_woocommerce_extensions_show_brand_label', current_action() == 'woocommerce_single_product_summary' ) ) {
								?><span class="woocommerce_extensions_brand_item_label"><?php echo esc_html( $label ); ?></span><?php
							}
							// Brand
							?><span class="woocommerce_extensions_brand_item_caption<?php
								if ( ! empty( $term_color ) ) {
									echo ' ' . esc_attr( greeny_add_inline_css_class( 'color: ' . esc_attr( $term_color ) ) );
								}
							?>"><?php echo esc_html( $term_val ); ?></span><?php
						?></span><?php
					}
					?></div><?php
				}
			}
		}
	}
}



/* Add colors and fonts to the custom CSS
--------------------------------------------------------------- */
if ( greeny_exists_woocommerce() ) {
	require_once greeny_get_file_dir( 'plugins/woocommerce/woocommerce-extensions-style.php' );
}
