<?php
/* Elementor Builder support functions
------------------------------------------------------------------------------- */

if ( ! defined( 'GREENY_ELEMENTOR_PADDINGS' ) ) {
	define( 'GREENY_ELEMENTOR_PADDINGS', 15 );
}

// Theme init priorities:
// 1 - register filters to add/remove lists items in the Theme Options
if ( ! function_exists( 'greeny_elm_theme_setup1' ) ) {
	add_action( 'after_setup_theme', 'greeny_elm_theme_setup1', 1 );
	function greeny_elm_theme_setup1() {
		if ( greeny_exists_elementor() ) {
			add_filter( 'greeny_filter_update_post_options', 'greeny_elm_update_post_options', 10, 3 );
			add_action( 'greeny_action_just_save_options', 'greeny_elm_just_save_options', 10, 1 );
		}
	}
}

// Theme init priorities:
// 9 - register other filters (for installer, etc.)
if ( ! function_exists( 'greeny_elm_theme_setup9' ) ) {
	add_action( 'after_setup_theme', 'greeny_elm_theme_setup9', 9 );
	function greeny_elm_theme_setup9() {

		add_filter( 'trx_addons_filter_force_load_elementor_styles', 'greeny_elm_force_load_elementor_styles' );

		if ( greeny_exists_elementor() ) {
			add_action( 'wp_enqueue_scripts', 'greeny_elm_frontend_scripts', 1100 );
			add_action( 'wp_enqueue_scripts', 'greeny_elm_responsive_styles', 2000 );
			add_filter( 'greeny_filter_merge_styles', 'greeny_elm_merge_styles' );
			add_filter( 'greeny_filter_merge_styles_responsive', 'greeny_elm_merge_styles_responsive' );

			add_action( 'init', 'greeny_elm_init_once', 3 );
			add_action( 'elementor/editor/before_enqueue_scripts', 'greeny_elm_editor_scripts' );

			// before Elementor 2.0.0
			add_filter( 'elementor/page/settings/success_response_data', 'greeny_elm_page_options_save', 10, 3 );
			add_filter( 'elementor/general/settings/success_response_data', 'greeny_elm_general_options_save', 10, 3 );
			// after Elementor 2.0.0
			add_filter( 'elementor/settings/page/success_response_data', 'greeny_elm_page_options_save', 10, 3 );
			add_filter( 'elementor/settings/post/success_response_data', 'greeny_elm_page_options_save', 10, 3 );
			add_filter( 'elementor/settings/general/success_response_data', 'greeny_elm_general_options_save', 10, 3 );
			add_filter( 'elementor/documents/ajax_save/return_data', 'greeny_elm_page_options_save_document', 10, 2 );
			// after Elementor 3.0
			add_action( 'elementor/document/before_save', 'greeny_elm_general_options_save2', 10, 2 );

			add_filter( 'greeny_filter_post_edit_link', 'greeny_elm_post_edit_link', 10, 2 );
			
			add_action( 'elementor/element/before_section_end', 'greeny_elm_add_color_scheme_control', 10, 3 );
			add_action( 'elementor/element/before_section_end', 'greeny_elm_move_paddings_to_column_wrap', 10, 3 );

			add_filter( 'trx_addons_sc_param_group_params', 'greeny_trx_addons_sc_param_group_params', 10, 2 );

			add_action( 'wp_ajax_elementor_ajax', 'greeny_elm_override_theme_options', 1 );

			// Add theme-specific page options.
			// Remove check is_admin() if have any problem with page-specific options!!!
			if ( is_admin() ) {
				add_action( 'elementor/element/after_section_end', 'greeny_elm_add_page_options', 10, 3 );
			}
		}
		if ( is_admin() ) {
			add_filter( 'greeny_filter_tgmpa_required_plugins', 'greeny_elm_tgmpa_required_plugins' );
		}
	}
}

// Filter to add in the required plugins list
if ( ! function_exists( 'greeny_elm_tgmpa_required_plugins' ) ) {
	//Handler of the add_filter('greeny_filter_tgmpa_required_plugins',	'greeny_elm_tgmpa_required_plugins');
	function greeny_elm_tgmpa_required_plugins( $list = array() ) {
		if ( greeny_storage_isset( 'required_plugins', 'elementor' ) && greeny_storage_get_array( 'required_plugins', 'elementor', 'install' ) !== false ) {
			$list[] = array(
				'name'     => greeny_storage_get_array( 'required_plugins', 'elementor', 'title' ),
				'slug'     => 'elementor',
				'required' => false,
			);
		}
		return $list;
	}
}

// Check if Elementor is installed and activated
if ( ! function_exists( 'greeny_exists_elementor' ) ) {
	function greeny_exists_elementor() {
		return class_exists( 'Elementor\Plugin' );
	}
}

// Return true if Elementor exists and current mode is preview
if ( ! function_exists( 'greeny_elementor_is_preview' ) ) {
	function greeny_elementor_is_preview() {
		static $is_preview = -1;
		if ( $is_preview === -1 ) {
			if ( greeny_exists_elementor() ) {
				$elementor = \Elementor\Plugin::instance();
				$is_preview = is_object( $elementor )
								&& ! empty( $elementor->preview )
								&& is_object( $elementor->preview )
								&& ( $elementor->preview->is_preview_mode()
									|| greeny_get_value_gp( 'elementor-preview' ) > 0
									|| ( greeny_get_value_gp( 'post' ) > 0
										&& greeny_get_value_gp( 'action' ) == 'elementor'
										)
									|| ( is_admin()
										&& in_array( greeny_get_value_gp( 'action' ), array( 'elementor', 'elementor_ajax', 'wp_ajax_elementor_ajax' ) )
										)
									);
			} else {
				$is_preview = false;
			}
		}
		return $is_preview;
	}
}

// Return true if specified post/page is built with Elementor
if ( !function_exists( 'greeny_is_built_with_elementor' ) ) {
	function greeny_is_built_with_elementor( $post_id ) {
		// Elementor\DB::is_built_with_elementor` is soft deprecated since 3.2.0
		// Use `Plugin::$instance->documents->get( $post_id )->is_built_with_elementor()` instead
		$rez = false;
		if ( greeny_exists_elementor() && ! empty( $post_id ) ) {
			$document = \Elementor\Plugin::instance()->documents->get( $post_id );
			if ( is_object( $document ) ) {
				$rez = $document->is_built_with_elementor();
			}
		}
		return $rez;
	}
}

// Override options with stored page/post meta inside ajax handlers
if ( ! function_exists( 'greeny_elm_override_theme_options' ) ) {
	//Handler of the add_action( 'wp_ajax_elementor_ajax', 'greeny_elm_override_theme_options', 1 );
	function greeny_elm_override_theme_options() {
		$post_id = greeny_get_value_gp( 'editor_post_id' );
		$actions = greeny_get_value_gp( 'actions' );
		if ( $post_id && strpos( $actions, '"action":"render_widget"' ) !== false ) {
			// Setup current post (disabled)
			if ( false ) {
				global $post;
				$post = get_post( $post_id );
				setup_postdata( $post );
			}
			greeny_storage_set( 'blog_mode', get_post_type( $post_id ) );
			greeny_storage_set( 'options_meta', get_post_meta( $post_id, 'greeny_options', true ) );
			do_action( 'greeny_action_override_theme_options' );
		}
	}
}

// Enqueue styles for frontend
if ( ! function_exists( 'greeny_elm_frontend_scripts' ) ) {
	//Handler of the add_action( 'wp_enqueue_scripts', 'greeny_elm_frontend_scripts', 1100 );
	function greeny_elm_frontend_scripts() {
		if ( greeny_is_on( greeny_get_theme_option( 'debug_mode' ) ) ) {
			$greeny_url = greeny_get_file_url( 'plugins/elementor/elementor.css' );
			if ( '' != $greeny_url ) {
				wp_enqueue_style( 'greeny-elementor', $greeny_url, array(), null );
			}
		}
	}
}

// Enqueue responsive styles for frontend
if ( ! function_exists( 'greeny_elm_responsive_styles' ) ) {
	//Handler of the add_action( 'wp_enqueue_scripts', 'greeny_elm_responsive_styles', 2000 );
	function greeny_elm_responsive_styles() {
		if ( greeny_is_on( greeny_get_theme_option( 'debug_mode' ) ) ) {
			$greeny_url = greeny_get_file_url( 'plugins/elementor/elementor-responsive.css' );
			if ( '' != $greeny_url ) {
				wp_enqueue_style( 'greeny-elementor-responsive', $greeny_url, array(), null, greeny_media_for_load_css_responsive( 'elementor' ) );
			}
		}
	}
}

// Merge custom styles
if ( ! function_exists( 'greeny_elm_merge_styles' ) ) {
	//Handler of the add_filter('greeny_filter_merge_styles', 'greeny_elm_merge_styles');
	function greeny_elm_merge_styles( $list ) {
		$list[ 'plugins/elementor/elementor.css' ] = true;
		return $list;
	}
}

// Merge responsive styles
if ( ! function_exists( 'greeny_elm_merge_styles_responsive' ) ) {
	//Handler of the add_filter('greeny_filter_merge_styles_responsive', 'greeny_elm_merge_styles_responsive');
	function greeny_elm_merge_styles_responsive( $list ) {
		$list[ 'plugins/elementor/elementor-responsive.css' ] = true;
		return $list;
	}
}


// Load required styles and scripts for Elementor Editor mode
if ( ! function_exists( 'greeny_elm_editor_scripts' ) ) {
	//Handler of the add_action( 'elementor/editor/before_enqueue_scripts',	'greeny_elm_editor_scripts');
	function greeny_elm_editor_scripts() {
		// Load font icons
		wp_enqueue_style( 'greeny-fontello', greeny_get_file_url( 'css/font-icons/css/fontello.css' ), array(), null );
		wp_enqueue_script( 'greeny-elementor-editor', greeny_get_file_url( 'plugins/elementor/elementor-editor.js' ), array( 'jquery' ), null, true );
		greeny_admin_scripts();
		greeny_admin_localize_scripts();
	}
}


// Return true if current page use header or footer from Elementor and need to load Elementor styles
if ( ! function_exists( 'greeny_elm_force_load_elementor_styles' ) ) {
	//Handler of the add_filter( 'trx_addons_filter_force_load_elementor_styles', 'greeny_elm_force_load_elementor_styles' );
	function greeny_elm_force_load_elementor_styles( $need ) {
		if ( ! $need ) {
			$need = ! greeny_is_singular() || ! greeny_is_built_with_elementor( get_the_ID() );
		}
		if ( $need ) {
			$need = false;
			$header_id = greeny_get_custom_header_id();
			$need = 0 < $header_id && greeny_is_built_with_elementor( $header_id );
			if ( ! $need ) {
				$footer_id = greeny_get_custom_footer_id();
				$need = 0 < $footer_id && greeny_is_built_with_elementor( $footer_id );
			}
		}
		return $need;
	}
}


// Set Elementor's options at once
if ( ! function_exists( 'greeny_elm_init_once' ) ) {
	//Handler of the add_action( 'init', 'greeny_elm_init_once', 3 );
	function greeny_elm_init_once() {
		if ( greeny_exists_elementor() && ! get_option( 'greeny_setup_elementor_options', false ) ) {
			// Set flag to prevent change Elementor's global options again
			update_option( 'greeny_setup_elementor_options', 1 );

			// Change global options of the Elementor 3.0+
			greeny_elm_update_elementor_options( true );
		}
	}
}


// Modify Elementor's options after the Theme Options saved
if ( ! function_exists( 'greeny_elm_just_save_options' ) ) {
	//Handler of the add_action( 'greeny_action_just_save_options', 'greeny_elm_just_save_options', 10, 1);
	function greeny_elm_just_save_options( $values ) {
		greeny_elm_update_elementor_options( false, $values );
	}
}


// Modify Elementor's kit options
if ( ! function_exists( 'greeny_elm_update_elementor_options' ) ) {
	function greeny_elm_update_elementor_options( $all, $values = false ) {
		$w = $values
				? ( ! empty( $values['page_width'] )
					? $values['page_width']
					: greeny_get_theme_option_std( 'page_width', greeny_storage_get_array( 'options', 'page_width', 'std' ) )
					)
				: greeny_get_theme_option( 'page_width' );

		// Change global options of the Elementor 2.9-
		if ( ! empty( $w ) ) {
			$w += 2 * GREENY_ELEMENTOR_PADDINGS;    // Theme-specific width + paddings of the columns
			update_option( 'elementor_container_width', $w );
		}
		if ( $all ) {
			update_option( 'elementor_disable_color_schemes', 'yes' );
			update_option( 'elementor_disable_typography_schemes', 'yes' );
			update_option( 'elementor_space_between_widgets', 0 );
			update_option( 'elementor_stretched_section_container', '.page_wrap' );
			update_option( 'elementor_page_title_selector', '.sc_layouts_title_caption' );
		}

		// Change global options of the Elementor 3.0+
		$kit = get_option( 'elementor_active_kit' );
		if ( ! empty( $kit ) ) {
			$options = get_post_meta( $kit, '_elementor_page_settings', true );
			if ( empty( $options ) || ! is_array( $options) ) {
				$options = array();
			}
			// Width of the page container
			if ( ! empty( $w ) ) {
				if ( ! isset( $options['container_width'] ) ) {
					$options['container_width'] = array(
														'unit' => 'px',
														'size' => $w,
														'sizes' => array(),
													);
				} else {
					$options['container_width']['size'] = $w;
				}
			}
			if ( $all ) {
				// Space between widgets
				if ( ! isset( $options['space_between_widgets'] ) ) {
					$options['space_between_widgets'] = array(
														'unit' => 'px',
														'size' => '0',
														'sizes' => array(),
													);
				} else {
					$options['space_between_widgets']['size'] = '';
				}
				// Selector of the page container to stretch sections
				$options['stretched_section_container'] = '.page_wrap';
				// Selector of the page title
				$options['page_title_selector'] = '.sc_layouts_title_caption';
			}
			// Update global options of the Elementor 3.0+
			if ( ! empty( $w ) || $all ) {
				update_post_meta( $kit, '_elementor_page_settings', $options );
			}
		}
	}
}


// Save General Options via AJAX from Elementor Editor 2.9-
// (called when any option is changed)
if ( ! function_exists( 'greeny_elm_general_options_save' ) ) {
	// Handler of the add_filter( 'elementor/general/settings/success_response_data', 'greeny_elm_general_options_save', 10, 3 );
	// Handler of the add_filter( 'elementor/settings/general/success_response_data', 'greeny_elm_general_options_save', 10, 3 );
	function greeny_elm_general_options_save( $response_data, $post_id, $data ) {
		$old_page_width = greeny_get_theme_option( 'page_width' ) + 2 * GREENY_ELEMENTOR_PADDINGS;
		$new_page_width = 0;
		if ( ! empty( $data['elementor_container_width'] ) && $old_page_width != $data['elementor_container_width'] ) {
			$new_page_width = $data['elementor_container_width'];
		}
		if ( ! empty( $new_page_width ) ) {
			set_theme_mod( 'page_width', $new_page_width - 2 * GREENY_ELEMENTOR_PADDINGS );   // Elementor width - paddings of the columns
		}
		return $response_data;
	}
}

// Save General Options via AJAX from Elementor Editor 3.0+
if ( ! function_exists( 'greeny_elm_general_options_save2' ) ) {
	// Handler of the add_action( 'elementor/document/before_save', 'greeny_elm_general_options_save2', 10, 2 );
	function greeny_elm_general_options_save2( $document, $data ) {
		if ( ! defined( 'DOING_AUTOSAVE' ) && ! empty( $data['settings'] ) ) {
			$old_page_width = greeny_get_theme_option( 'page_width' ) + 2 * GREENY_ELEMENTOR_PADDINGS;
			$new_page_width = 0;
			if ( ! empty( $data['settings']['container_width']['size'] ) && $old_page_width != $data['settings']['container_width']['size'] ) {
				$new_page_width = $data['settings']['container_width']['size'];
			}
			if ( ! empty( $new_page_width ) ) {
				set_theme_mod( 'page_width', $new_page_width - 2 * GREENY_ELEMENTOR_PADDINGS );   // Elementor width - paddings of the columns
			}
		}
	}
}

// Add theme-specific controls to sections and columns
if ( ! function_exists( 'greeny_elm_add_color_scheme_control' ) ) {
	//Handler of the add_action( 'elementor/element/before_section_end', 'greeny_elm_add_color_scheme_control', 10, 3 );
	function greeny_elm_add_color_scheme_control( $element, $section_id, $args ) {
		if ( is_object( $element ) ) {
			$el_name = $element->get_name();
			// Add color scheme selector
			if ( apply_filters(
				'greeny_filter_add_scheme_in_elements',
				( in_array( $el_name, array( 'section', 'column' ) ) && 'section_advanced' === $section_id )
							|| ( 'common' === $el_name && '_section_style' === $section_id ),
				$element, $section_id, $args
			) ) {
				$element->add_control(
					'scheme_heading',
					array(
						'label' => esc_html__( 'Theme-specific params', 'greeny' ),
						'type' => \Elementor\Controls_Manager::HEADING,
						'separator' => 'before',
					)
				);
				$element->add_control(
					'scheme', array(
						'type'         => \Elementor\Controls_Manager::SELECT,
						'label'        => esc_html__( 'Color scheme', 'greeny' ),
						'label_block'  => false,
						'options'      => greeny_array_merge( array( '' => esc_html__( 'Inherit', 'greeny' ) ), greeny_get_list_schemes() ),
						'render_type'  => 'template',	// ( none | ui | template ) - reload template after parameter is changed
						'default'      => '',
						'prefix_class' => 'scheme_',
					)
				);
			}
			// Add 'Override section width'
			if ( 'section' == $el_name && 'section_advanced' === $section_id ) {
				$element->add_control(
					'justify_columns', array(
						'type'         => \Elementor\Controls_Manager::SWITCHER,
						'label'        => esc_html__( 'Justify columns', 'greeny' ),
						'label_block'  => false,
						'description'  => wp_kses_data( __( 'Stretch columns to align the left and right edges to the site content area', 'greeny' ) ),
						'label_off'    => esc_html__( 'Off', 'greeny' ),
						'label_on'     => esc_html__( 'On', 'greeny' ),
						'return_value' => 'justified',
						'prefix_class' => 'elementor-section-',
					)
				);
			}
			// Add 'Color style'
			if ( in_array($el_name, array(
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
										'trx_widget_twitter'))
				&& in_array( $section_id, array( 'section_sc_button', 'section_sc_promo', 'section_sc_title', 'section_title_params' ) )
			) {
				$element->add_control(
					'color_style', array(
						'type'         => \Elementor\Controls_Manager::SELECT,
						'label'        => esc_html__( 'Color style', 'greeny' ),
						'label_block'  => false,
						'options'      => greeny_get_list_sc_color_styles(),
						'default'      => 'default',
					)
				);
			}
		}
	}
}

// Move paddings from the .elementor-element-wrap to the .elementor-column-wrap to compatibility with the theme
if ( ! function_exists( 'greeny_elm_move_paddings_to_column_wrap' ) ) {
	// Handler of add_action( 'elementor/element/before_section_end', 'greeny_elm_move_paddings_to_column_wrap', 10, 3 );
	function greeny_elm_move_paddings_to_column_wrap( $element, $section_id, $args ) {
		if ( is_object( $element ) ) {
			$el_name = $element->get_name();
			if ( 'column' == $el_name && 'section_advanced' == $section_id ) {
				$element->update_responsive_control( 'padding', array(
											'selectors' => array(
												'{{WRAPPER}} > .elementor-element-populated.elementor-column-wrap' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',	// Elm 2.9- (or DOM Optimization == Inactive)
												'{{WRAPPER}} > .elementor-element-populated.elementor-widget-wrap' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',	// Elm 3.0+
											)
										) );
			}
		}
	}
}

// Add param 'color_style' to the shortcode 'Button' in the Elementor
if ( ! function_exists( 'greeny_trx_addons_sc_param_group_params' ) ) {
	// Handler of add_filter( 'trx_addons_sc_param_group_params', 'greeny_trx_addons_sc_param_group_params', 10, 2 );
	function greeny_trx_addons_sc_param_group_params( $params, $sc ) {
		// Param 'color_style'
		if ( in_array( $sc, array( 'trx_sc_button' ) ) ) {
			// If it's Elementor's params
			if ( isset( $params[0]['name'] ) && isset( $params[0]['label'] ) ) {
				array_splice($params, 1, 0, array( array(
					'name'        => 'color_style',
					'type'        => \Elementor\Controls_Manager::SELECT,
					'label'       => esc_html__( 'Color style', 'greeny' ),
					'label_block' => false,
					'options'     => greeny_get_list_sc_color_styles(),
					'default'     => 'default',
				) ) );
			}
		}
		return $params;
	}
}


// Return url with post edit link
if ( ! function_exists( 'greeny_elm_post_edit_link' ) ) {
	//Handler of the add_filter( 'greeny_filter_post_edit_link', 'greeny_elm_post_edit_link', 10, 2 );
	function greeny_elm_post_edit_link( $link, $post_id ) {
		if ( greeny_is_built_with_elementor( $post_id ) ) {
			$link = str_replace( 'action=edit', 'action=elementor', $link );
		}
		return $link;
	}
}


// Add class "sidebar" to the wrapper of the Elementor's widget "Sidebar"
// Case 1: for frontend page
if ( ! function_exists( 'greeny_elm_add_class_sidebar' ) ) {
	add_action( 'elementor/frontend/widget/before_render', 'greeny_elm_add_class_sidebar', 10, 1 );
	function greeny_elm_add_class_sidebar( $element ) {
		if ( is_object( $element ) ) {
			$el_name = $element->get_name();
			if ( $el_name == 'sidebar' ) {
				$element->add_render_attribute( '_wrapper', 'class', 'sidebar' );
				$element->add_render_attribute( '_wrapper', 'class', 'widget_area' );
			} else if ( $el_name == 'trx_sc_layouts_widgets' ) {
				$element->add_render_attribute( '_wrapper', 'class', 'sidebar' );
			}
		}
	}
}


// Add class "sidebar" to the wrapper of the Elementor's widget "Sidebar"
// Case 2: for editor mode (when an editor get widget content via AJAX)
if ( ! function_exists( 'greeny_elm_add_class_sidebar_in_editor' ) ) {
	add_filter( 'elementor/widget/render_content', 'greeny_elm_add_class_sidebar_in_editor', 10, 2 );
	function greeny_elm_add_class_sidebar_in_editor( $widget_content, $element ) {
		if ( is_object( $element ) && ( wp_doing_ajax() || greeny_is_preview( 'elementor' ) ) ) {
			$el_name = $element->get_name();
			if ( $el_name == 'sidebar' ) {
				$widget_content = sprintf( '<div class="sidebar widget_area">%s</div>', $widget_content );
			} else if ( $el_name == 'trx_sc_layouts_widgets' ) {
				$widget_content = str_replace( 'widget_area', 'sidebar widget_area', $widget_content );
			}
		}
		return $widget_content;
	}
}



// Register custom controls for Elementor
//------------------------------------------------------------------------
if ( ! function_exists( 'greeny_elm_register_custom_controls' ) ) {
	add_action( 'elementor/controls/controls_registered', 'greeny_elm_register_custom_controls' );
	function greeny_elm_register_custom_controls($elementor) {
		$controls = array( 'choice' );
		foreach ( $controls as $control_id ) {
			$control_filename = str_replace( '_', '-', $control_id );
			require_once greeny_get_file_dir( "plugins/elementor/params/{$control_filename}/{$control_filename}.php" );
			$class_name = 'Greeny_Elementor_Control_' . ucwords( $control_id );
			$elementor->register_control( $control_id, new $class_name() );
		}
	}
}



// Add tab with theme-specific Page Options to the Page Settings
//---------------------------------------------------------------
if ( ! function_exists( 'greeny_elm_add_page_options' ) ) {
	//Handler of the add_action( 'elementor/element/after_section_end', 'greeny_elm_add_page_options', 10, 3 );
	function greeny_elm_add_page_options( $element, $section_id, $args ) {
		if ( is_object( $element ) && 'section_page_style' == $section_id ) {
			$el_name = $element->get_name();
			if ( in_array( $el_name, array( 'page-settings', 'post', 'wp-post', 'wp-page' ) ) ) {
				$post_id   = get_the_ID();
				$post_type = get_post_type( $post_id );
				if ( $post_id > 0 && greeny_options_allow_override( $post_type ) ) {
					// Load saved options
					$meta     = get_post_meta( $post_id, 'greeny_options', true );
					$sections = array();
					global $GREENY_STORAGE;
					// Refresh linked data if this field is controller for the another (linked) field
					// Do this before show fields to refresh data in the $GREENY_STORAGE
					foreach ( $GREENY_STORAGE['options'] as $k => $v ) {
						if ( ! isset( $v['override'] ) || strpos( $v['override']['mode'], $post_type ) === false ) {
							continue;
						}
						if ( ! empty( $v['linked'] ) ) {
							$v['val'] = isset( $meta[ $k ] ) ? $meta[ $k ] : 'inherit';
							if ( ! empty( $v['val'] ) && ! greeny_is_inherit( $v['val'] ) ) {
								greeny_refresh_linked_data( $v['val'], $v['linked'] );
							}
						}
					}
					// Collect fields to the tabs
					foreach ( $GREENY_STORAGE['options'] as $k => $v ) {
						if ( ! isset( $v['override'] ) || strpos( $v['override']['mode'], $post_type ) === false || 'hidden' == $v['type'] ) {
							continue;
						}
						$sec = empty( $v['override']['section'] ) ? esc_html__( 'General', 'greeny' ) : $v['override']['section'];
						if ( ! isset( $sections[ $sec ] ) ) {
							$sections[ $sec ] = array();
						}
						$v['val']               = isset( $meta[ $k ] ) ? $meta[ $k ] : 'inherit';
						$sections[ $sec ][ $k ] = $v;
					}
					if ( count( $sections ) > 0 ) {
						$cnt = 0;
						foreach ( $sections as $sec => $v ) {
							$cnt++;
							$element->start_controls_section(
								"section_theme_options_{$cnt}",
								array(
									'label' => $sec,
									'tab'   => \Elementor\Controls_Manager::TAB_ADVANCED,
								)
							);
							foreach ( $v as $field_id => $params ) {
								greeny_elm_add_page_options_field( $element, $field_id, $params );
							}
							$element->end_controls_section();
						}
					}
				}
			}
		}
	}
}


// Add control for the specified field
if ( ! function_exists( 'greeny_elm_add_page_options_field' ) ) {
	function greeny_elm_add_page_options_field( $element, $id, $field ) {
		$id_field    = "greeny_options_field_{$id}";
		$id_override = "greeny_options_override_{$id}";
		// If fields is inherit
		$inherit_state = isset( $field['val'] ) && greeny_is_inherit( $field['val'] );
		// Condition
		$condition = array();
		if ( ! empty( $field['dependency'] ) ) {
			foreach ( $field['dependency'] as $k => $v ) {
				$key = strpos( $k, '.editor-page-attributes__template' ) !== false || strpos( $k, '#page_template' ) !== false
							? 'template'
							: ( in_array( substr( $k, 0, 1 ), array( '.', '#' ) ) || strpos( $k, ' ' ) !== false
								? ''
								: "greeny_options_field_{$k}"
								);
				if ( empty( $key ) ) {
					continue;
				}
				if ( is_array( $v ) ) {
					if ( is_string( $v[0] ) && '^' == substr( $v[0], 0, 1 ) ) {
						$v[0] = substr( $v[0], 1 );
						$key .= '!';
					}					
				} else {
					if ( is_string( $v ) && '^' == substr( $v, 0, 1 ) ) {
						$v = substr( $v, 1 );
						$key .= '!';
					}
				}
				$condition[ $key ] = $v;
			}
		}
		// Inherit param
		$element->add_control(
			$id_override, array(
				'label'        => $field['title'],
				'label_block'  => in_array( $field['type'], array( 'media' ) ),
				'description'  => ! empty( $field['override']['desc'] ) ? $field['override']['desc'] : '', //( ! empty( $field['desc'] ) ? $field['desc'] : '' ),
				'separator'    => 'before',
				'type'         => \Elementor\Controls_Manager::SWITCHER,
				'label_off'    => esc_html__( 'Inherit', 'greeny' ),
				'label_on'     => esc_html__( 'Override', 'greeny' ),
				'return_value' => '1',
				'condition'    => $condition,
			)
		);

		// Field params
		$params = array(
			'label'       => esc_html__( 'New value', 'greeny' ),
			'label_block' => in_array( $field['type'], array( 'info' ) ),
			'description' => ! empty( $field['desc'] ) ? $field['desc'] : '',
		);

		// Add dependency to params
		$condition[ $id_override ] = '1';
		$params['condition']       = $condition;

		// Type 'checkbox' or 'switch'
		// Attention! In the section 'Page Options' Elementor don't send value, if field is not changed (user leave default value)
		// or if this field have type 'SWITCHER' (because use checkbox)
		// Use SELECT instead SWITCHER in the Page Options
		if ( 'checkbox' == $field['type'] || 'switch' == $field['type'] ) {
			$params = array_merge(
				$params, array(
					'type'         => \Elementor\Controls_Manager::SELECT,
					'options'      => array(
										'0' => esc_html__( 'Off', 'greeny' ),
										'1' => esc_html__( 'On', 'greeny' ),
									),
				)
			);
			$element->add_control( $id_field, $params );

			// Type 'choice' (pictogram selector)
		} elseif ( 'choice' == $field['type'] ) {
			$field['options'] = apply_filters( 'greeny_filter_options_get_list_choises', $field['options'], $id );
			$params = array_merge(
				$params, array(
					'type'        => 'choice',
					'label_block' => true,
					'options'     => $field['options'],
				)
			);
			$element->add_control( $id_field, $params );

			// Type 'radio' (2+ choises), 'select'
		} elseif ( in_array( $field['type'], array( 'radio', 'select' ) ) ) {
			$field['options'] = apply_filters( 'greeny_filter_options_get_list_choises', $field['options'], $id );
			if ( 'choice' == $field['type'] ) {
				foreach( $field['options'] as $k => $v ) {
					if ( isset( $v['title'] ) ) {
						$field['options'][ $k ] = $v['title'];
					}
				}
			}
			$params           = array_merge(
				$params, array(
					'type'    => \Elementor\Controls_Manager::SELECT,
					'options' => $field['options'],
				)
			);
			$element->add_control( $id_field, $params );

			// Type 'checklist', 'select2' and 'icon'
		} elseif ( in_array( $field['type'], array( 'checklist', 'select2', 'icon' ) ) ) {
			$field['options'] = apply_filters( 'greeny_filter_options_get_list_choises', $field['options'], $id );
			$params           = array_merge(
				$params, array(
					'type'     => \Elementor\Controls_Manager::SELECT2,
					'options'  => $field['options'],
					'multiple' => 'checklist' == $field['type'] || ! empty( $field['multiple'] ),
				)
			);
			$element->add_control( $id_field, $params );

			// Type 'text' or 'time'
		} elseif ( in_array( $field['type'], array( 'text', 'time' ) ) ) {
			$params = array_merge(
				$params, array(
					'type' => \Elementor\Controls_Manager::TEXT,
				)
			);
			$element->add_control( $id_field, $params );

			// Type 'date'
		} elseif ( 'date' == $field['type'] ) {
			$params = array_merge(
				$params, array(
					'type' => \Elementor\Controls_Manager::DATE_TIME,
				)
			);
			$element->add_control( $id_field, $params );

			// Type 'textarea'
		} elseif ( 'textarea' == $field['type'] ) {
			$params = array_merge(
				$params, array(
					'type' => \Elementor\Controls_Manager::TEXTAREA,
					'rows' => ! empty( $field['rows'] ) ? max( 1, $field['rows'] ) : 5,
				)
			);
			$element->add_control( $id_field, $params );

			// Type 'text_editor'
		} elseif ( 'text_editor' == $field['type'] ) {
			$params = array_merge(
				$params, array(
					'type' => \Elementor\Controls_Manager::WYSIWYG,
				)
			);
			$element->add_control( $id_field, $params );

			// Type 'media'
		} elseif ( in_array( $field['type'], array( 'image', 'media', 'video', 'audio' ) ) ) {
			$params = array_merge(
				$params, array(
					'type'        => \Elementor\Controls_Manager::MEDIA,
					'label_block' => true,
					'default'     => array(
						'id'  => ! empty( $field['val'] ) && ! greeny_is_inherit( $field['val'] ) ? greeny_attachment_url_to_postid( greeny_clear_thumb_size( $field['val'] ) ) : 0,
						'url' => ! empty( $field['val'] ) && ! greeny_is_inherit( $field['val'] ) ? $field['val'] : '',
					),
				)
			);
			$element->add_control( $id_field, $params );

			// Type 'color'
		} elseif ( 'color' == $field['type'] ) {
			$params = array_merge(
				$params, array(
					'type'   => \Elementor\Controls_Manager::COLOR,
					'global' => array(
						'active' => false,
					),
				)
			);
			$element->add_control( $id_field, $params );

			// Type 'slider' or 'range'
		} elseif ( in_array( $field['type'], array( 'slider', 'range' ) ) ) {
			$params = array_merge(
				$params, array(
					'type'    => \Elementor\Controls_Manager::SLIDER,
					'default' => array(
						'size' => ! empty( $field['val'] ) && ! greeny_is_inherit( $field['val'] ) ? $field['val'] : '',
						'unit' => 'px',
					),
					'range'   => array(
						'px' => array(
							'min' => ! empty( $field['min'] ) ? $field['min'] : 0,
							'max' => ! empty( $field['max'] ) ? $field['max'] : 1000,
						),
					),
				)
			);
			$element->add_control( $id_field, $params );

		}
	}
}


// Save Page Options via AJAX from Elementor Editor
// (called when any option is changed)
if ( ! function_exists( 'greeny_elm_page_options_save' ) ) {
	// Handler of the add_filter( 'elementor/page/settings/success_response_data', 'greeny_elm_page_options_save', 10, 3 );
	// Handler of the add_filter( 'elementor/settings/page/success_response_data', 'greeny_elm_page_options_save', 10, 3 );
	function greeny_elm_page_options_save( $response_data, $post_id, $data ) {
		if ( $post_id > 0 && is_array( $data ) ) {
			$meta    = get_post_meta( $post_id, 'greeny_options', true );
			if ( empty( $meta ) ) {
				$meta = array();
			}
			$options = greeny_storage_get( 'options' );
			foreach ( $options as $k => $v ) {
				$id_field    = "greeny_options_field_{$k}";
				$id_override = "greeny_options_override_{$k}";
				if ( isset( $data[ $id_override ] ) ) {
					$meta[ $k ] = isset( $data[ $id_field ] )
									? ( is_array( $data[ $id_field ] ) && isset( $data[ $id_field ]['url'] )
											? $data[ $id_field ]['url']
											: $data[ $id_field ]
											)
									: ( ! empty( $meta[ $k ] ) && ! greeny_is_inherit( $meta[ $k ] )
											? $meta[ $k ]
											: $v['std']
											);
				} elseif ( isset( $meta[ $k ] ) ) {
					unset( $meta[ $k ] );
				}
			}
			update_post_meta( $post_id, 'greeny_options', apply_filters( 'greeny_filter_update_post_options', $meta, $post_id ) );

			// Save separate meta options to search template pages
			if ( 'page' == get_post_type( $post_id ) && ! empty( $data['template'] ) && 'blog.php' == $data['template'] ) {
				update_post_meta( $post_id, 'greeny_options_post_type', isset( $meta['post_type'] ) ? $meta['post_type'] : 'post' );
				update_post_meta( $post_id, 'greeny_options_parent_cat', isset( $meta['parent_cat'] ) ? $meta['parent_cat'] : 0 );
			}
		}
		return $response_data;
	}
}


// Save Page Options via AJAX from Elementor Editor
// (called when any option is changed)
if ( ! function_exists( 'greeny_elm_page_options_save_document' ) ) {
	// Handler of the add_filter( 'elementor/documents/ajax_save/return_data', 'greeny_elm_page_options_save_document', 10, 2 );
	function greeny_elm_page_options_save_document( $response_data, $document ) {
		$post_id = $document->get_main_id();
		if ( $post_id > 0 ) {
			$actions = json_decode( greeny_get_value_gp( 'actions' ), true );
			if ( is_array( $actions ) && isset( $actions['save_builder']['data']['settings'] ) && is_array( $actions['save_builder']['data']['settings'] ) ) {
				$settings = $actions['save_builder']['data']['settings'];
				if ( is_array( $settings ) ) {
					greeny_elm_page_options_save( '', $post_id, $actions['save_builder']['data']['settings'] );
				}
			}
		}
		return $response_data;
	}
}


// Save Page Options when page is updated (saved) from WordPress Editor
if ( ! function_exists( 'greeny_elm_update_post_options' ) ) {
	// Handler of the add_filter( 'greeny_filter_update_post_options', 'greeny_elm_update_post_options', 10, 3 );
	function greeny_elm_update_post_options( $meta, $post_id, $post_type = '' ) {
		if ( doing_filter( 'save_post' ) ) {
			$elm_meta = get_post_meta( $post_id, '_elementor_page_settings', true );
			if ( is_array( $elm_meta ) ) {
				foreach ( $elm_meta as $k => $v ) {
					if ( strpos( $k, 'greeny_options_' ) !== false ) {
						unset( $elm_meta[ $k ] );
					}
				}
			} else {
				$elm_meta = array();
			}
			$options = greeny_storage_get( 'options' );
			foreach ( $meta as $k => $v ) {
				$elm_meta[ "greeny_options_field_{$k}" ]    = in_array( $options[ $k ]['type'], array( 'image', 'video', 'audio', 'media' ) )
																? array(
																	'id' => greeny_attachment_url_to_postid( greeny_clear_thumb_size( $v ) ),
																	'url' => $v,
																)
																: $v;
				$elm_meta[ "greeny_options_override_{$k}" ] = '1';
			}
			update_post_meta( $post_id, '_elementor_page_settings', apply_filters( 'greeny_filter_elementor_update_page_settings', $elm_meta, $post_id ) );
		}
		return $meta;
	}
}


// Add plugin-specific colors and fonts to the custom CSS
if ( greeny_exists_elementor() ) {
	require_once greeny_get_file_dir( 'plugins/elementor/elementor-style.php' );
}
