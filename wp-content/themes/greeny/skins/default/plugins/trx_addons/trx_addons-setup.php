<?php
/* Theme-specific action to configure ThemeREX Addons components
------------------------------------------------------------------------------- */


/* ThemeREX Addons components
------------------------------------------------------------------------------- */
if ( ! function_exists( 'greeny_trx_addons_theme_specific_components' ) ) {
	add_filter( 'trx_addons_filter_components_editor', 'greeny_trx_addons_theme_specific_components' );
	function greeny_trx_addons_theme_specific_components( $enable = false ) {
		return GREENY_THEME_FREE
					? false     // Free version
					: false;    // Pro version or Developer mode
	}
}

if ( ! function_exists( 'greeny_trx_addons_theme_specific_setup1' ) ) {
	add_action( 'after_setup_theme', 'greeny_trx_addons_theme_specific_setup1', 1 );
	function greeny_trx_addons_theme_specific_setup1() {
		if ( greeny_exists_trx_addons() ) {
			add_filter( 'trx_addons_addons_list', 'greeny_trx_addons_addons_list', 100 );
			add_filter( 'trx_addons_api_list', 'greeny_trx_addons_api_list' );
			add_filter( 'trx_addons_cpt_list', 'greeny_trx_addons_cpt_list' );
			add_filter( 'trx_addons_sc_list', 'greeny_trx_addons_sc_list' );
			add_filter( 'trx_addons_widgets_list', 'greeny_trx_addons_widgets_list' );
		}
	}
}

// Addons
if ( ! function_exists( 'greeny_trx_addons_addons_list' ) ) {
	//Handler of the add_filter( 'trx_addons_addons_list', 'greeny_trx_addons_addons_list', 100 );
	function greeny_trx_addons_addons_list( $list = array() ) {
		// To do: Enable/Disable theme-specific addons via add/remove it in the list
		if ( is_array( $list ) ) {
			// List of the theme/skin required addons:
			$required_addons = array(
				'audio-effects' => array( 'title' => esc_html__( 'Audio effects', 'greeny' ), ),
				'secondary-image' => array( 'title' => esc_html__( 'Secondary image', 'greeny' ), ),
                'bg-canvas'       => array( 'title' => esc_html__( 'Dynamic background', 'greeny' ) ),
				'mouse-helper' => array( 'title' => esc_html__( 'Mouse Helper', 'greeny' ), ),
                'image-effects' => array( 'title' => esc_html__( 'Image effects', 'greeny' ), ),
			);
			foreach( $required_addons as $k => $v ) {
				if ( ! isset( $list[ $k ] ) || ! is_array( $list[ $k ] ) ) {
					$list[ $k ] = $v;
				}
				$list[ $k ]['required'] = true;
			}
		}
		return $list;
	}
}

// API
if ( ! function_exists( 'greeny_trx_addons_api_list' ) ) {
	//Handler of the add_filter('trx_addons_api_list',	'greeny_trx_addons_api_list');
	function greeny_trx_addons_api_list( $list = array() ) {
		// To do: Enable/Disable Third-party plugins API via add/remove it in the list

		// If it's a free version - leave only basic set
		if ( GREENY_THEME_FREE ) {
			$free_api = array( 'gutenberg', 'elementor', 'contact-form-7', 'instagram_feed', 'woocommerce' );
			foreach ( $list as $k => $v ) {
				if ( ! in_array( $k, $free_api ) ) {
					unset( $list[ $k ] );
				}
			}
		}
		return $list;
	}
}


// CPT
if ( ! function_exists( 'greeny_trx_addons_cpt_list' ) ) {
	//Handler of the add_filter('trx_addons_cpt_list',	'greeny_trx_addons_cpt_list');
	function greeny_trx_addons_cpt_list( $list = array() ) {
		// To do: Enable/Disable CPT via add/remove it in the list

		// If it's a free version - leave only basic set
		if ( GREENY_THEME_FREE ) {
			$free_cpt = array( 'layouts', 'portfolio', 'post', 'services', 'team', 'testimonials' );
			foreach ( $list as $k => $v ) {
				if ( ! in_array( $k, $free_cpt ) ) {
					unset( $list[ $k ] );
				}
			}
		}
		return $list;
	}
}

// Shortcodes
if ( ! function_exists( 'greeny_trx_addons_sc_list' ) ) {
	//Handler of the add_filter('trx_addons_sc_list',	'greeny_trx_addons_sc_list');
	function greeny_trx_addons_sc_list( $list = array() ) {
		// To do: Add/Remove shortcodes into list
		// If you add new shortcode - in the theme's folder must exists /trx_addons/shortcodes/new_sc_name/new_sc_name.php

		// If it's a free version - leave only basic set
		if ( GREENY_THEME_FREE ) {
			$free_shortcodes = array( 'action', 'anchor', 'blogger', 'button', 'form', 'icons', 'price', 'promo', 'socials' );
			foreach ( $list as $k => $v ) {
				if ( ! in_array( $k, $free_shortcodes ) ) {
					unset( $list[ $k ] );
				}
			}
		}
		return $list;
	}
}

// Widgets
if ( ! function_exists( 'greeny_trx_addons_widgets_list' ) ) {
	//Handler of the add_filter('trx_addons_widgets_list',	'greeny_trx_addons_widgets_list');
	function greeny_trx_addons_widgets_list( $list = array() ) {
		// To do: Add/Remove widgets into list
		// If you add widget - in the theme's folder must exists /trx_addons/widgets/new_widget_name/new_widget_name.php

		// If it's a free version - leave only basic set
		if ( GREENY_THEME_FREE ) {
			$free_widgets = array( 'aboutme', 'banner', 'contacts', 'flickr', 'popular_posts', 'recent_posts', 'slider', 'socials' );
			foreach ( $list as $k => $v ) {
				if ( ! in_array( $k, $free_widgets ) ) {
					unset( $list[ $k ] );
				}
			}
		}
		return $list;
	}
}

// Add mobile menu to the plugin's cached menu list
if ( ! function_exists( 'greeny_trx_addons_menu_cache' ) ) {
	add_filter( 'trx_addons_filter_menu_cache', 'greeny_trx_addons_menu_cache' );
	function greeny_trx_addons_menu_cache( $list = array() ) {
		if ( in_array( '#menu_main', $list ) ) {
			$list[] = '#menu_mobile';
		}
		$list[] = '.menu_mobile_inner nav > ul';
		return $list;
	}
}

// Add theme-specific vars into localize array
if ( ! function_exists( 'greeny_trx_addons_localize_script' ) ) {
	add_filter( 'greeny_filter_localize_script', 'greeny_trx_addons_localize_script' );
	function greeny_trx_addons_localize_script( $arr ) {
		$arr['alter_link_color'] = greeny_get_scheme_color( 'alter_link' );

		$arr['mc4wp_msg_email_min'] = esc_html__('Email address is too short (or empty)', 'greeny');
		$arr['mc4wp_msg_email_max'] = esc_html__('Too long email address', 'greeny');

		return $arr;
	}
}

// Add theme-specific width where used min 2 columns
if ( ! function_exists( 'greeny_trx_addons_max_one_column_width' ) ) {
	add_filter( 'trx_addons_filter_max_one_column_width', 'greeny_trx_addons_max_one_column_width' );
	function greeny_trx_addons_max_one_column_width( $w ) {
		$media = greeny_storage_get_array( 'responsive', 'sm_wp' );
		if ( empty( $media['max'] ) ) {
			$media = array( 'max' => 600 );
		}
		return $media['max'];
	}
}


// Shortcodes support
//------------------------------------------------------------------------

// Add new output types (layouts) in the shortcodes
if ( ! function_exists( 'greeny_trx_addons_sc_type' ) ) {
	add_filter( 'trx_addons_sc_type', 'greeny_trx_addons_sc_type', 10, 2 );
	function greeny_trx_addons_sc_type( $list, $sc ) {
		// To do: check shortcode slug and if correct - add new 'key' => 'title' to the list
		if ( 'trx_sc_blogger' == $sc ) {
			$list = greeny_array_merge( $list, greeny_get_list_blog_styles( false, 'sc' ) );
		}
		return $list;
	}
}

// Add params values to the shortcode's atts
if ( ! function_exists( 'greeny_trx_addons_sc_prepare_atts' ) ) {
	add_filter( 'trx_addons_filter_sc_prepare_atts', 'greeny_trx_addons_sc_prepare_atts', 10, 2 );
	function greeny_trx_addons_sc_prepare_atts( $atts, $sc ) {
		if ( 'trx_sc_blogger' == $sc ) {
			$list = greeny_get_list_blog_styles( false, 'sc' );
			if ( isset( $list[ $atts['type'] ] ) ) {
			    $blog_id = 0;
			    $blog_meta = array( 'scripts_required' => '' );
				$custom_type = '';
				$use_masonry = false;
				if ( strpos( $atts['type'], 'blog-custom-' ) === 0 ) {
					$blog_id = greeny_get_custom_blog_id( $atts['type'] );
					$blog_meta = greeny_get_custom_layout_meta( $blog_id );
					$custom_type = ! empty( $blog_meta['scripts_required'] ) ? $blog_meta['scripts_required'] : 'custom';
					$use_masonry = strpos( $blog_meta['scripts_required'], 'masonry' ) !== false;
				} else {
					$use_masonry = greeny_is_blog_style_use_masonry( $atts['type'] );
				}
				// Classes for the container with posts
				$columns = $atts['columns'] > 0
								? $atts['columns']
								: ( 1 < $atts['count']
									? $atts['count']
									: ( -1 == $atts['count']
										? 3
										: 1
										)
									);
				$atts['posts_container'] = 'posts_container'
					. ' ' . esc_attr( $atts['type'] ) . '_wrap'
					. ( $columns > 1
							? ' ' . esc_attr( $atts['type'] ) . '_' . $columns 
							: '' )
					. ( $use_masonry
						?  sprintf( ' masonry_wrap masonry_%d', $columns )
						: ( $columns > 1
							? ' columns_wrap columns_padding_bottom'
							: ''
							)
						);
				// Scripts for masonry and portfolio
				if ( $use_masonry ) {
				    greeny_lazy_load_off();
					greeny_load_masonry_scripts();
				}
			}
		}
		return $atts;
	}
}


// Add new params to the default shortcode's atts
if ( ! function_exists( 'greeny_trx_addons_sc_atts' ) ) {
	add_filter( 'trx_addons_sc_atts', 'greeny_trx_addons_sc_atts', 10, 2 );
	function greeny_trx_addons_sc_atts( $atts, $sc ) {

		// Param 'scheme'
		if ( in_array(
			$sc, array(
				'trx_sc_action',
				'trx_sc_blogger',
				'trx_sc_cars',
				'trx_sc_courses',
				'trx_sc_content',
				'trx_sc_countdown',
				'trx_sc_dishes',
				'trx_sc_events',
				'trx_sc_form',
				'trx_sc_icons',
				'trx_sc_googlemap',
				'trx_sc_yandexmap',
				'trx_sc_osmap',
				'trx_sc_portfolio',
				'trx_sc_price',
				'trx_sc_promo',
				'trx_sc_properties',
				'trx_sc_services',
				'trx_sc_skills',
				'trx_sc_socials',
				'trx_sc_table',
				'trx_sc_team',
				'trx_sc_testimonials',
				'trx_sc_title',
				'trx_widget_audio',
				'trx_widget_twitter',
				'trx_sc_layouts',
				'trx_sc_layouts_container',
			)
		) ) {
			$atts['scheme'] = 'inherit';
		}
		// Param 'color_style'
		if ( in_array(
			$sc, array(
				'trx_sc_action',
				'trx_sc_blogger',
				'trx_sc_cars',
				'trx_sc_courses',
				'trx_sc_content',
				'trx_sc_countdown',
				'trx_sc_dishes',
				'trx_sc_events',
				'trx_sc_form',
				'trx_sc_icons',
				'trx_sc_googlemap',
				'trx_sc_yandexmap',
				'trx_sc_osmap',
				'trx_sc_portfolio',
				'trx_sc_price',
				'trx_sc_promo',
				'trx_sc_properties',
				'trx_sc_services',
				'trx_sc_skills',
				'trx_sc_socials',
				'trx_sc_table',
				'trx_sc_team',
				'trx_sc_testimonials',
				'trx_sc_title',
				'trx_widget_audio',
				'trx_widget_twitter'
			)
		) ) {
			$atts['color_style'] = 'default';
		}
		if ( in_array(
			$sc, array(
				'trx_sc_button',
			)
		) ) {
			if ( is_array( $atts['buttons'] ) ) {
				foreach( $atts['buttons'] as $k => $v ) {
					$atts['buttons'][ $k ]['color_style'] = 'default';
				}
			}
		}

		// Cursor
		$atts['typed_cursor_char'] = '_';

		return $atts;
	}
}


// Add classes to the shortcode's output from new params
if ( ! function_exists( 'greeny_trx_addons_sc_output' ) ) {
	add_filter( 'trx_addons_sc_output', 'greeny_trx_addons_sc_output', 10, 4 );
	function greeny_trx_addons_sc_output( $output, $sc, $atts, $content ) {
		$sc = str_replace( array( 'trx_widget', 'trx_' ), array( 'sc_widget', '' ), $sc );
		if ( substr( $sc, -3 ) == 'map' ) {
			$sc = str_replace( 'map', 'map_content', $sc );
		}
		if ( ! empty( $atts['scheme'] ) && ! greeny_is_inherit( $atts['scheme'] ) ) {
			$output = str_replace( 'class="' . esc_attr( $sc ) . ' ', 'class="' . esc_attr( $sc ) . ' scheme_' . esc_attr( $atts['scheme'] ) . ' ', $output );
		}
		if ( ! empty( $atts['color_style'] ) && ! greeny_is_inherit( $atts['color_style'] ) && 'default' != $atts['color_style'] ) {
			$output = str_replace( 'class="' . esc_attr( $sc ) . ' ', 'class="' . esc_attr( $sc ) . ' color_style_' . esc_attr( $atts['color_style'] ) . ' ', $output );
		}
		return $output;
	}
}


// Add color_style to the button items
if ( ! function_exists( 'greeny_trx_addons_sc_item_link_classes' ) ) {
	add_filter( 'trx_addons_filter_sc_item_link_classes', 'greeny_trx_addons_sc_item_link_classes', 10, 3 );
	function greeny_trx_addons_sc_item_link_classes( $class, $sc, $atts=array() ) {
		if ( 'sc_button' == $sc ) {
			if ( ! empty( $atts['color_style'] ) && ! greeny_is_inherit( $atts['color_style'] ) && 'default' != $atts['color_style'] ) {
				$class .= ' color_style_' . esc_attr( $atts['color_style'] );
			}
		}
		return $class;
	}
}



// Return tag for the item's title
if ( ! function_exists( 'greeny_trx_addons_sc_item_title_tag' ) ) {
	add_filter( 'trx_addons_filter_sc_item_title_tag', 'greeny_trx_addons_sc_item_title_tag' );
	function greeny_trx_addons_sc_item_title_tag( $tag = '' ) {
		return 'h1' == $tag ? 'h2' : $tag;
	}
}

// Return args for the item's button
if ( ! function_exists( 'greeny_trx_addons_sc_item_button_args' ) ) {
	add_filter( 'trx_addons_filter_sc_item_button_args', 'greeny_trx_addons_sc_item_button_args', 10, 3 );
	function greeny_trx_addons_sc_item_button_args( $args, $sc, $sc_args ) {
		if ( ! empty( $sc_args['color_style'] ) ) {
			$args['color_style'] = $sc_args['color_style'];
		}
		return $args;
	}
}

// Add new styles to the Google map
if ( ! function_exists( 'greeny_trx_addons_sc_googlemap_styles' ) ) {
	add_filter( 'trx_addons_filter_sc_googlemap_styles', 'greeny_trx_addons_sc_googlemap_styles' );
	function greeny_trx_addons_sc_googlemap_styles( $list ) {
		$list['dark'] = esc_html__( 'Dark', 'greeny' );
		$list['extra'] = esc_html__( 'Extra', 'greeny' );
		return $list;
	}
}

// Show post info from CPT Portfolio instead post meta
if ( ! function_exists( 'greeny_trx_addons_portfolio_info' ) ) {
	add_filter( 'greeny_filter_show_blog_meta', 'greeny_trx_addons_portfolio_info', 10, 2 );
	function greeny_trx_addons_portfolio_info( $show, $meta_parts ) {
		if ( greeny_exists_trx_addons() && defined( 'TRX_ADDONS_CPT_PORTFOLIO_PT' ) && get_post_type() == TRX_ADDONS_CPT_PORTFOLIO_PT && function_exists( 'trx_addons_cpt_portfolio_show_details' ) ) {
			trx_addons_cpt_portfolio_show_details( array( 'class' => 'post_meta', 'count' => 3 ) );
			$show = false;
		}
		return $show;
	}
}


// WP Editor addons
//------------------------------------------------------------------------

// Theme-specific configure of the WP Editor
if ( ! function_exists( 'greeny_trx_addons_tiny_mce_style_formats' ) ) {
	add_filter( 'trx_addons_filter_tiny_mce_style_formats', 'greeny_trx_addons_tiny_mce_style_formats' );
	function greeny_trx_addons_tiny_mce_style_formats( $style_formats ) {
		// Add style 'Arrow' to the 'List styles'
		// Remove 'false &&' from the condition below to add new style to the list
		if ( is_array( $style_formats ) && count( $style_formats ) > 0 ) {
			foreach ( $style_formats as $k => $v ) {
                if (esc_html__('Inline', 'greeny') == $v['title']) {
                    $style_formats[$k]['items'][] = array(
                        'title' => esc_html__('Alter Text', 'greeny'),
                        'inline' => 'span',
                        'classes' => 'trx_addons_alter_text',
                    );
                }
                if (esc_html__('Inline', 'greeny') == $v['title']) {
                    $style_formats[$k]['items'][] = array(
                        'title' => esc_html__('Alter Text 2', 'greeny'),
                        'inline' => 'span',
                        'classes' => 'trx_addons_alter_text_2',
                    );
                }
                if (esc_html__('Headers', 'greeny') == $v['title']) {
                    $style_formats[$k]['items'][] = array(
                        'title' => esc_html__('Title with Link', 'greeny'),
                        'selector' => 'h1,h2,h3,h4,h5,h6',
                        'classes' => 'trx_addons_title_with_link',
                    );
                }
                if (esc_html__('Headers', 'greeny') == $v['title']) {
                    $style_formats[$k]['items'][] = array(
                        'title' => esc_html__('Title with Link 2', 'greeny'),
                        'selector' => 'h1,h2,h3,h4,h5,h6',
                        'classes' => 'trx_addons_title_with_link_2',
                    );
                }
			}
		}
		return $style_formats;
	}
}


// Setup team and portflio pages
//------------------------------------------------------------------------

// Disable override header image on team and portfolio pages
if ( ! function_exists( 'greeny_trx_addons_allow_override_header_image' ) ) {
	add_filter( 'greeny_filter_allow_override_header_image', 'greeny_trx_addons_allow_override_header_image' );
	function greeny_trx_addons_allow_override_header_image( $allow ) {
		return is_single()
				&& (
					greeny_is_team_page()
					|| greeny_is_cars_page()
					|| greeny_is_cars_agents_page()
					|| greeny_is_properties_agents_page()
					)
				? false
				: $allow;
	}
}

// Add fields to the meta box for the team members
// All other CPT meta boxes may be modified in the same method
if ( ! function_exists( 'greeny_trx_addons_meta_box_fields' ) ) {
	add_filter( 'trx_addons_filter_meta_box_fields', 'greeny_trx_addons_meta_box_fields', 10, 2 );
	function greeny_trx_addons_meta_box_fields( $mb, $post_type ) {
		if ( defined( 'TRX_ADDONS_CPT_TEAM_PT' ) && TRX_ADDONS_CPT_TEAM_PT == $post_type ) {
			if ( ! isset( $mb['email'] ) ) {
				$mb['email'] = array(
					'title'   => esc_html__( 'E-mail', 'greeny' ),
					'desc'    => wp_kses_data( __( "Team member's email", 'greeny' ) ),
					'std'     => '',
					'details' => true,
					'type'    => 'text',
				);
			}
		}
		return $mb;
	}
}
// Add fields to the meta box for the portfolio
if ( ! function_exists( 'greeny_trx_addons_portfolio_meta_styles' ) ) {
    add_filter( 'trx_addons_filter_meta_box_fields', 'greeny_trx_addons_portfolio_meta_styles', 30, 2 );
    function greeny_trx_addons_portfolio_meta_styles( $meta_box, $post_type ) {

        if('cpt_portfolio' == $post_type){
            $meta_box = array_merge(
                array(
                    "style_section" => array(
                        "title" => esc_html__("Style", 'greeny'),
                        "desc" => wp_kses_data( __('Details styles for this post', 'greeny') ),
                        "type" => "section"
                    ),
                    "details_style" => array(
                        "title" => esc_html__("Detail block style", 'greeny'),
                        "desc" => wp_kses_data( __("Select project details style", 'greeny') ),
                        "std" => 'top',
                        "options" => array(
                            'default' => esc_html__('Default', 'greeny'),
                            'light' => esc_html__('Light', 'greeny'),
                        ),
                        "type" => "select"
                    )
                ),
                $meta_box
            );
        }
        return $meta_box;
    }
}



// Change thumb size for the team items
if ( ! function_exists( 'greeny_trx_addons_thumb_size' ) ) {
	add_filter( 'trx_addons_filter_thumb_size', 'greeny_trx_addons_thumb_size', 10, 3 );
	function greeny_trx_addons_thumb_size( $thumb_size = '', $type = '', $args=array() ) {
		// ToDo: Change team members image's size (default is 'avatar'):
		//---> if ($type == 'team-default') $thumb_size = greeny_get_thumb_size('big');
        if ( empty($args['thumb_size']) && ('blogger-lay_portfolio' == $type && 'style-1' == $args['template_lay_portfolio'] || 'blogger-lay_portfolio' == $type && 'style-8' == $args['template_lay_portfolio'] )) {
            $thumb_size = greeny_get_thumb_size(
                                $args['columns'] > 1
                                    ? 'medium-square'	// Use -big because when image is square 'masonry' is blur!
                                    : 'big'
                            );
        }
		return $thumb_size;
	}
}



// Modify layouts of some components
//------------------------------------------------------------------------

// Return theme specific title layout for the slider
if ( ! function_exists( 'greeny_trx_addons_slider_title' ) ) {
	add_filter( 'trx_addons_filter_slider_title', 'greeny_trx_addons_slider_title', 10, 3 );
	function greeny_trx_addons_slider_title( $title, $data, $args ) {
		$title = '';
		if ( ! empty( $data['title'] ) ) {
			$title .= '<h3 class="slide_title">'
						. ( ! empty( $data['link'] ) ? '<a href="' . esc_url( $data['link'] ) . '"'
							. ( ! empty( $data['link_atts'] ) ? $data['link_atts'] : ''	)
							. '>' : '' )
							. esc_html( $data['title'] )
						. ( ! empty( $data['link'] ) ? '</a>' : '' )
					. '</h3>';
		}
		if ( ! empty( $data['cats'] ) ) {
			$title .= sprintf( '<div class="slide_cats">%s</div>', $data['cats'] );
		}
		return $title;
	}
}


// Hide extended taxonomy attributes
if ( ! function_exists( 'greeny_skin_trx_addons_extended_taxonomy_attributes' ) ) {
    add_filter( 'trx_addons_filter_extended_taxonomy_attributes', 'greeny_skin_trx_addons_extended_taxonomy_attributes' );
    function greeny_skin_trx_addons_extended_taxonomy_attributes($array) {
        unset( $array['color_bg']);
        unset( $array['color_bg_hover']);
        return $array;
    }
}
