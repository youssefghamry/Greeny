<?php
/**
 * Gutenberg Full-Site Editor (FSE) template manipulations.
 */

//------------------------------------------------------
//--  Replace a Front Page content
//------------------------------------------------------

if ( ! function_exists( 'greeny_gutenberg_fse_replace_frontpage_content' ) ) {
	add_filter( 'frontpage_template', 'greeny_gutenberg_fse_replace_frontpage_content', 9999, 3 );
	/**
	 * Substitute a content of the global variable $_wp_current_template_content
	 * with a content of the Front Page sections (if Front Page Builder is enabled in the Theme Options).
	 * 
	 * Hooks: add_filter( 'frontpage_template', 'greeny_gutenberg_fse_replace_frontpage_content', 9999, 3 );
	 *
	 * @param string   $template  Path to the template. See locate_template().
	 * @param string   $type      Sanitized filename without extension.
	 * @param string[] $templates A list of template candidates, in descending order of priority.
	 */
	function greeny_gutenberg_fse_replace_frontpage_content( $template, $type = '', $templates = array() ) {
		if ( substr( $template, -19 ) == 'template-canvas.php'
			&& get_option( 'show_on_front' ) == 'page'
			&& greeny_is_on( greeny_get_theme_option( 'front_page_enabled', false ) )
		) {
			if ( have_posts() ) {
				the_post();
			}
			global $_wp_current_template_content;
			ob_start();
			$greeny_sections = greeny_array_get_keys_by_value( greeny_get_theme_option( 'front_page_sections' ) );
			if ( is_array( $greeny_sections ) ) {
				foreach ( $greeny_sections as $greeny_section ) {
					get_template_part( apply_filters( 'greeny_filter_get_template_part', 'front-page/section', $greeny_section ), $greeny_section );
				}
			}
			$output = ob_get_contents();
			ob_end_clean();
			if ( ! empty( $output ) ) {
				$_wp_current_template_content = preg_replace( '#<!-- wp:query[\s\S]*<!-- /wp:query -->#', $output, $_wp_current_template_content );
			}
		}
		return $template;
	}
}



//--------------------------------------------------------------
//--  Replace a template parts according to the Theme Options
//--------------------------------------------------------------

if ( ! function_exists( 'greeny_gutenberg_fse_modify_template_with_current_options' ) ) {
	foreach( greeny_get_wp_template_hooks() as $hook ) {
		add_filter( $hook, 'greeny_gutenberg_fse_modify_template_with_current_options', 30, 3 );
	}
	/**
	 * Transforms the content of the global variable $_wp_current_template_content
	 * according to the settings of the current page:
	 * 
	 * - Remove a sidebar block from the content if a sidebar is not present on the current page
	 * 
	 * - Replace a template name of the header / footer / sidebar
	 * 
	 * Trigger the filter 'greeny_filter_wp_current_template_content' to allow other modules
	 * to modify the content.
	 *
	 * @param string $template   Path to the template. See locate_template().
	 * @param string $type       Sanitized filename without extension.
	 * @param array  $templates  A list of template candidates, in descending order of priority.
	 */
	function greeny_gutenberg_fse_modify_template_with_current_options( $template, $type = '', $templates = array() ) {
		global $_wp_current_template_content;
		if ( substr( $template, -19 ) == 'template-canvas.php' && is_array( $templates ) ) {
			$_wp_current_template_content = apply_filters( 'greeny_filter_wp_current_template_content', $_wp_current_template_content, $template, $type, $templates );
		}
		return $template;
	}
}

// Header
//----------------------------------------------
if ( ! function_exists( 'greeny_gutenberg_fse_modify_template_replace_header' ) ) {
	add_filter( 'greeny_filter_wp_current_template_content', 'greeny_gutenberg_fse_modify_template_replace_header', 10, 4 );
	/**
	 * Replace a header in the content of the current template
	 * according to the Theme Options of the current page.
	 * 
	 * Hooks: add_filter( 'greeny_filter_wp_current_template_content', 'greeny_gutenberg_fse_modify_template_replace_header', 10, 4 );
	 *
	 * @param string $content    A content of the current template.
	 * @param string $template   Path to the template. See locate_template().
	 * @param string $type       Sanitized filename without extension.
	 * @param array  $templates  A list of template candidates, in descending order of priority.
	 */
	function greeny_gutenberg_fse_modify_template_replace_header( $content, $template, $type, $templates ) {
		$header_type = greeny_get_theme_option( 'header_type' );
		if ( 'custom' == $header_type ) {
			$header_style = greeny_get_theme_option( "header_style" );
			$header_id = greeny_get_custom_header_id();

			// FSE template part is selected as a header style
			if ( strpos( $header_style, "header-fse-template-" ) !== false ) {
				$header_name = '';
				// Found a saved version
				if ( (int)$header_id > 0 ) {
					$post = get_post( $header_id );
					if ( ! empty( $post->post_name ) ) {
						$header_name = $post->post_name;
					}
				// Get a template from a folder 'parts'
				} else {
					$header_name = str_replace( "header-fse-template-", '', $header_style );
				}
				$content = preg_replace( '#(<!-- wp:template-part[\s]*{[^}]*"slug":[\s]*)("header[^"]*")#U', '${1}"' . esc_attr( $header_name ) . '"', $content );

			// Custom header's layout
			} else if ( greeny_is_layouts_available() ) {
				ob_start();
				// Trigger action before the custom header to allow other modules include an additional content to the custom header
				do_action( 'greeny_action_fse_before_custom_header', $content, $template, $type, $templates );
				// Custom header
				get_template_part( apply_filters( 'greeny_filter_get_template_part', "templates/header-custom" ) );
				// Trigger action after th custom header to allow other modules include an additional content to the custom header
				do_action( 'greeny_action_fse_after_custom_header', $content, $template, $type, $templates );
				// Get output
				$html = ob_get_contents();
				ob_end_clean();
				if ( ! empty( $html ) ) {
					$content = preg_replace( '#<!-- wp:template-part[\s]*{[^}]*"slug":[\s]*"header[^"]*"[^>]*/-->#U', $html, $content );
				}
			}
		}
		return $content;
	}
}

if ( ! function_exists( 'greeny_gutenberg_fse_custom_header_add_side_menu' ) ) {
	add_action( 'greeny_action_fse_after_custom_header', 'greeny_gutenberg_fse_custom_header_add_side_menu', 10, 4 );
	/**
	 * Add a side menu to the custom header layout.
	 * 
	 * Hooks: add_action( 'greeny_action_fse_after_custom_header', 'greeny_gutenberg_fse_custom_header_add_side_menu', 10, 4 );
	 *
	 * @param string $content    A content of the current template.
	 * @param string $template   Path to the template. See locate_template().
	 * @param string $type       Sanitized filename without extension.
	 * @param array  $templates  A list of template candidates, in descending order of priority.
	 */
	function greeny_gutenberg_fse_custom_header_add_side_menu( $content, $template, $type, $templates ) {
		// Side menu
		if ( in_array( greeny_get_theme_option( 'menu_side' ), array( 'left', 'right' ) ) ) {
			get_template_part( apply_filters( 'greeny_filter_get_template_part', 'templates/header-navi-side' ) );
		}
	}
}

if ( ! function_exists( 'greeny_gutenberg_fse_custom_header_add_mobile_menu' ) ) {
	add_action( 'greeny_action_fse_after_custom_header', 'greeny_gutenberg_fse_custom_header_add_mobile_menu', 10, 4 );
	/**
	 * Add a mobile menu to the custom header layout.
	 * 
	 * Hooks: add_action( 'greeny_action_fse_after_custom_header', 'greeny_gutenberg_fse_custom_header_add_mobile_menu', 10, 4 );
	 *
	 * @param string $content    A content of the current template.
	 * @param string $template   Path to the template. See locate_template().
	 * @param string $type       Sanitized filename without extension.
	 * @param array  $templates  A list of template candidates, in descending order of priority.
	 */
	function greeny_gutenberg_fse_custom_header_add_mobile_menu( $content, $template, $type, $templates ) {
		// Mobile menu
		get_template_part( apply_filters( 'greeny_filter_get_template_part', 'templates/header-navi-mobile' ) );
	}
}

// Sidebar
//----------------------------------------------
if ( ! function_exists( 'greeny_gutenberg_fse_modify_template_replace_sidebar' ) ) {
	add_filter( 'greeny_filter_wp_current_template_content', 'greeny_gutenberg_fse_modify_template_replace_sidebar', 10, 4 );
	/**
	 * Replace a sidebar in the content of the current template according to the Theme Options of the current page or
	 * Remove a sidebar block from the content of the current template if a sidebar is not present on the current page.
	 * 
	 * Hooks: add_filter( 'greeny_filter_wp_current_template_content', 'greeny_gutenberg_fse_modify_template_replace_sidebar', 10, 4 );
	 *
	 * @param string $content    A content of the current template.
	 * @param string $template   Path to the template. See locate_template().
	 * @param string $type       Sanitized filename without extension.
	 * @param array  $templates  A list of template candidates, in descending order of priority.
	 */
	function greeny_gutenberg_fse_modify_template_replace_sidebar( $content, $template, $type, $templates ) {
		// Replace sidebar
		if ( greeny_sidebar_present() ) {
			$sidebar_type = greeny_get_theme_option( 'sidebar_type' );
			if ( 'custom' == $sidebar_type && ! greeny_is_layouts_available() ) {
				$sidebar_type = 'default';
			}
			// Masks to search a sidebar block in the content
			$sidebar_start = apply_filters( 'greeny_filter_wp_block_with_sidebar_start',
											'(<!-- wp:group[\s]*{[^}]*"className":[\s]*"[^"]*sidebar[^"]*"[^>]*-->[\s]*'
											. '<div[^>]*class="[^"]*sidebar[^"]*"[^>]*>)'
											);
			$sidebar_end = apply_filters( 'greeny_filter_wp_block_with_sidebar_end',
											'(</div>[\s]*'
											. '<!-- /wp:group -->)'
											);
			// Default sidebar with widgets is selected
			if ( 'default' == $sidebar_type ) {
				$sidebar_name = greeny_get_theme_option( 'sidebar_widgets' );
				greeny_storage_set( 'current_sidebar', 'sidebar' );
				// Replace a group with class 'sidebar' in the content with a block displaying the specified set of widgets
				if ( is_active_sidebar( $sidebar_name ) ) {
					$content = preg_replace( "#{$sidebar_start}([\s\S]*){$sidebar_end}#U",
											'${1}'
												. '<!-- wp:trx-addons/layouts-widgets {"widgets":"' . esc_attr( $sidebar_name ) . '"} /-->'
											. '${3}',
											$content
										);
				}

			// A custom sidebar (built with FSE or with any other builder)
			} else {
				$sidebar_style = greeny_get_theme_option( "sidebar_style" );
				$sidebar_id = greeny_get_custom_sidebar_id();

				// FSE template part is selected as a sidebar style
				if ( strpos( $sidebar_style, "sidebar-fse-template-" ) !== false ) {
					$sidebar_name = '';
					// Found a saved version
					if ( (int)$sidebar_id > 0 ) {
						$post = get_post( $sidebar_id );
						if ( ! empty( $post->post_name ) ) {
							$sidebar_name = $post->post_name;
						}
					// Get a template from a folder 'parts'
					} else {
						$sidebar_name = str_replace( "sidebar-fse-template-", '', $sidebar_style );
					}
					$content = preg_replace( "#{$sidebar_start}([\s\S]*){$sidebar_end}#U",
											'${1}'
												. '<!-- wp:template-part {"slug":"' . esc_attr( $sidebar_name ) . '"} /-->'
											. '${3}',
											$content
										);

				// Custom sidebar's layout
				} else if ( greeny_is_layouts_available() ) {
					ob_start();
					// Trigger action before the custom sidebar to allow other modules include an additional content to the custom sidebar
					do_action( 'greeny_action_fse_before_custom_sidebar', $content, $template, $type, $templates );
					// Custom sidebar
					do_action( 'greeny_action_show_layout', $greeny_sidebar_id );
					// Trigger action after th custom sidebar to allow other modules include an additional content to the custom sidebar
					do_action( 'greeny_action_fse_after_custom_sidebar', $content, $template, $type, $templates );
					// Get output
					$html = ob_get_contents();
					ob_end_clean();
					if ( ! empty( $html ) ) {
						$html = preg_replace( "/<\/aside>[\r\n\s]*<aside/", '</aside><aside', $html );
						$content = preg_replace( "#{$sidebar_start}([\s\S]*){$sidebar_end}#U",
												'${1}' 
													. $html
												. '${3}',
												$content
											);
					}
				}

			}

		// Remove sidebar
		} else {
			$content = preg_replace( '#<!-- wp:group[\s]*{[^}]*"className":[\s]*"[^"]*sidebar[\s\S]*<!-- /wp:group -->#U', '', $content );
		}
		return $content;
	}
}

// Footer
//----------------------------------------------
if ( ! function_exists( 'greeny_gutenberg_fse_modify_template_replace_footer' ) ) {
	add_filter( 'greeny_filter_wp_current_template_content', 'greeny_gutenberg_fse_modify_template_replace_footer', 10, 4 );
	/**
	 * Replace a footer in the content of the current template
	 * according to the Theme Options of the current page.
	 * 
	 * Hooks: add_filter( 'greeny_filter_wp_current_template_content', 'greeny_gutenberg_fse_modify_template_replace_footer', 10, 4 );
	 *
	 * @param string $content    A content of the current template.
	 * @param string $template   Path to the template. See locate_template().
	 * @param string $type       Sanitized filename without extension.
	 * @param array  $templates  A list of template candidates, in descending order of priority.
	 */
	function greeny_gutenberg_fse_modify_template_replace_footer( $content, $template, $type, $templates ) {
		$footer_type = greeny_get_theme_option( 'footer_type' );
		if ( 'custom' == $footer_type ) {
			$footer_style = greeny_get_theme_option( "footer_style" );
			$footer_id = greeny_get_custom_footer_id();

			// FSE template part is selected as a footer style
			if ( strpos( $footer_style, "footer-fse-template-" ) !== false ) {
				$footer_name = '';
				// Found a saved version
				if ( (int)$footer_id > 0 ) {
					$post = get_post( $footer_id );
					if ( ! empty( $post->post_name ) ) {
						$footer_name = $post->post_name;
					}
				// Get a template from a folder 'parts'
				} else {
					$footer_name = str_replace( "footer-fse-template-", '', $footer_style );
				}
				$content = preg_replace( '#(<!-- wp:template-part[\s]*{[^}]*"slug":[\s]*)("footer[^"]*")#U', '${1}"' . esc_attr( $footer_name ) . '"', $content );

			// Custom footer's layout
			} else if ( greeny_is_layouts_available() ) {
				ob_start();
				// Trigger action before the custom footer to allow other modules include an additional content to the custom footer
				do_action( 'greeny_action_fse_before_custom_footer', $content, $template, $type, $templates );
				// Custom footer
				get_template_part( apply_filters( 'greeny_filter_get_template_part', "templates/footer-custom" ) );
				// Trigger action after th custom footer to allow other modules include an additional content to the custom footer
				do_action( 'greeny_action_fse_after_custom_footer', $content, $template, $type, $templates );
				// Get output
				$html = ob_get_contents();
				ob_end_clean();
				if ( ! empty( $html ) ) {
					$content = preg_replace( '#<!-- wp:template-part[\s]*{[^}]*"slug":[\s]*"footer[^"]*"[^>]*/-->#U', $html, $content );
				}
			}
		}
		return $content;
	}
}



//------------------------------------------------------
//--  Replace featured image with a largest size
//------------------------------------------------------
if ( ! function_exists( 'greeny_gutenberg_fse_replace_featured_image_renderer' ) ) {
	add_filter( 'block_type_metadata_settings', 'greeny_gutenberg_fse_replace_featured_image_renderer', 10, 2 );
	/**
	 * Replace a render_callback of the post featured image to increase its thumb size.
	 *
	 * @param array $settings Array of determined settings for registering a block type.
	 * @param array $metadata Metadata provided for registering a block type.
	 */
	function greeny_gutenberg_fse_replace_featured_image_renderer( $settings = array(), $metadata = array() ) {
		if ( ! empty( $settings['render_callback'] ) && $settings['render_callback'] == 'render_block_core_post_featured_image' ) {
			$settings['render_callback'] = 'greeny_gutenberg_fse_featured_image_renderer';
		}
		return $settings;
	}
}

if ( ! function_exists( 'greeny_gutenberg_fse_featured_image_renderer' ) ) {
	/**
	 * Renders the 'core/post-featured-image' block on the server with a theme-specific thumb size.
	 *
	 * @param array    $attributes Block attributes.
	 * @param string   $content    Block default content.
	 * @param WP_Block $block      Block instance.
	 * 
	 * @return string  Returns the featured image for the current post.
	 */
	function greeny_gutenberg_fse_featured_image_renderer( $attributes, $content, $block ) {
		if ( ! isset( $block->context['postId'] ) ) {
			return '';
		}
		$post_ID = $block->context['postId'];

		if ( empty( $post_ID ) ) {
			return '';
		}

		// Set a current post to allow using a post template functions
		$GLOBALS['post'] = get_post( $post_ID );
		setup_postdata( $GLOBALS['post'] );

		ob_start();

		$greeny_expanded   = ! greeny_sidebar_present() && greeny_get_theme_option( 'expand_content' ) == 'expand';
		$greeny_hover      = greeny_get_theme_option( 'image_hover' );
		$greeny_components = greeny_array_get_keys_by_value( greeny_get_theme_option( 'meta_parts' ) );

		$css = '';
		if ( ! empty( $attributes['width'] ) ) {
			$css .= "width:{$attributes['width']};";
		}
		if ( ! empty( $attributes['height'] ) ) {
			$css .= "height:{$attributes['height']};";
		}
		if ( ! empty( $attributes['scale'] ) ) {
			$css .= "object-fit:{$attributes['scale']};";
		}

		greeny_show_post_featured( apply_filters( 'greeny_filter_args_featured',
			array(
				'css'        => $css,
				'no_links'   => empty( $attributes['isLink'] ),
				'hover'      => $greeny_hover,
				'meta_parts' => $greeny_components,
				'singular'   => greeny_is_singular(),
				'thumb_size' => greeny_get_thumb_size( strpos( greeny_get_theme_option( 'body_style' ), 'full' ) !== false
									? 'full'
									: ( $greeny_expanded || greeny_is_singular()
										? 'huge' 
										: 'big' 
										)
									),
			),
			'wp-block-featured-image',
			$attributes
		) );

		$featured_image = ob_get_contents();

		ob_end_clean();

		// Restore current post data
		wp_reset_postdata();

		return $featured_image;
	}
}
