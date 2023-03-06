<?php
/**
 * Gutenberg Full-Site Editor (FSE) support functions.
 */

if ( ! defined( 'GREENY_FSE_TEMPLATE_PART_PT' ) ) define( 'GREENY_FSE_TEMPLATE_PART_PT', 'wp_template_part' );

// Load additional files with FSE support
require_once greeny_get_file_dir( 'plugins/gutenberg/gutenberg-fse-lists.php' );
require_once greeny_get_file_dir( 'plugins/gutenberg/gutenberg-fse-templates.php' );


if ( ! function_exists( 'greeny_gutenberg_fse_loader' ) ) {
	add_action( 'wp_loaded', 'greeny_gutenberg_fse_loader', 1 );
	/**
	 * Turn off a Gutenberg (plugin) templates on frontend
	 * if a theme-specific frontend editor is active.
	 */
	function greeny_gutenberg_fse_loader() {
		// 
		if ( greeny_exists_gutenberg_fse()
			&& get_option( 'show_on_front' ) == 'page'
			&& (int) get_option('page_on_front') > 0
			&& greeny_is_on( greeny_get_theme_option( 'front_page_enabled', false ) )
			&& greeny_get_current_url() == '/'
		) {
		 	remove_action( 'wp_loaded', 'gutenberg_add_template_loader_filters' );
		}
	}
}

if ( ! function_exists( 'greeny_gutenberg_is_fse_wp_support' ) ) {
	/**
	 * Check if WordPress with FSE support installed
	 * (WordPress 5.9+ Site Editor is present).
	 */
	function greeny_gutenberg_is_fse_wp_support() {
		return function_exists( 'wp_is_block_theme' );
	}
}

if ( ! function_exists( 'greeny_gutenberg_is_fse_gb_support' ) ) {
	/**
	 * Check if a plugin "Gutenberg" with FSE support is installed.
	 */
	function greeny_gutenberg_is_fse_gb_support() {
		return function_exists( 'gutenberg_is_fse_theme' );
	}
}

if ( ! function_exists( 'greeny_exists_gutenberg_fse' ) ) {
	/**
	 * Check if FSE is available
	 * (WordPress 5.9+ or the plugin "Gutenberg" are installed).
	 */
	function greeny_exists_gutenberg_fse() {
		return greeny_exists_gutenberg() && ( greeny_gutenberg_is_fse_wp_support() || greeny_gutenberg_is_fse_gb_support() );
	}
}

if ( ! function_exists( 'greeny_gutenberg_is_fse_theme' ) ) {
	/**
	 * Check if the current theme supports a FSE behaviour.
	 */
	function greeny_gutenberg_is_fse_theme() {
		static $fse = -1;
		if ( $fse == -1 ) {
			if ( greeny_gutenberg_is_fse_wp_support() ) {                                    // WordPress 5.9+ FSE
				$fse = (bool) wp_is_block_theme();
			} else if ( greeny_gutenberg_is_fse_gb_support() ) {                             // Plugin Gutenberg FSE
				$fse = (bool) gutenberg_is_fse_theme();
			} else {
				$fse = is_readable( GREENY_THEME_DIR . 'templates/index.html' )              // WordPress 5.9+ FSE
						|| ( GREENY_THEME_DIR != GREENY_CHILD_DIR && is_readable( GREENY_CHILD_DIR . 'templates/index.html' ) )
						|| is_readable( GREENY_THEME_DIR . 'block-templates/index.html' )    // Plugin Gutenberg FSE
						|| ( GREENY_THEME_DIR != GREENY_CHILD_DIR && is_readable( GREENY_CHILD_DIR . 'block-templates/index.html' ) );
			}
		}
		return $fse;
	}
}

if ( ! function_exists( 'greeny_gutenberg_is_fse_enabled' ) ) {
	/**
	 * Check if the current theme supports FSE behaviour and FSE is installed and activated
	 */
	function greeny_gutenberg_is_fse_enabled() {
		return greeny_exists_gutenberg_fse() && greeny_gutenberg_is_fse_theme();
	}
}

if ( ! function_exists( 'greeny_gutenberg_fse_copy_from_skin' ) ) {
	add_action( 'greeny_action_skin_switched', 'greeny_gutenberg_fse_copy_from_skin', 10, 2 );
	/**
	 * Copy FSE-required folders from a new skin subfolder '_fse' to the theme's root folder.
	 * 
	 * Hooks: add_action( 'greeny_action_skin_switched', 'greeny_gutenberg_fse_copy_from_skin', 10, 2 );
	 */
	function greeny_gutenberg_fse_copy_from_skin( $new_skin, $old_skin ) {
		$theme_templates_dir = greeny_prepare_path( GREENY_THEME_DIR . ( greeny_gutenberg_is_fse_wp_support() ? 'templates/' : 'block-templates/' ) );
		$theme_template_parts_dir = greeny_prepare_path( GREENY_THEME_DIR . ( greeny_gutenberg_is_fse_wp_support() ? 'parts/' : 'block-template-parts/' ) );
		$theme_json = greeny_prepare_path( GREENY_THEME_DIR . 'theme.json' );
		$skin_templates_dir = greeny_prepare_path( GREENY_THEME_DIR . 'skins/' . $new_skin . '/_fse' . ( greeny_gutenberg_is_fse_wp_support() ? '/templates/' : '/block-templates/' ) );
		$skin_template_parts_dir = greeny_prepare_path( GREENY_THEME_DIR . 'skins/' . $new_skin . '/_fse' . ( greeny_gutenberg_is_fse_wp_support() ? '/parts/' : '/block-template-parts/' ) );
		// Remove old templates from the stylesheet dir (if exists)
		if ( is_dir( $theme_templates_dir ) ) {
			greeny_unlink( $theme_templates_dir );
		}
		if ( is_dir( $theme_template_parts_dir ) ) {
			greeny_unlink( $theme_template_parts_dir );
		}
		if ( file_exists( $theme_json ) ) {
			greeny_unlink( $theme_json );
		}
		// If a new skin is a FSE compatible - copy two folders with block templates and a file 'theme.json'
		// from a new skin's subfolder '_fse' to the theme's root folder
		if ( is_dir( $skin_templates_dir ) ) {
			greeny_copy( $skin_templates_dir, $theme_templates_dir );
		}
		if ( is_dir( $skin_template_parts_dir ) ) {
			greeny_copy( $skin_template_parts_dir, $theme_template_parts_dir );
		}
	}
}

if ( ! function_exists( 'greeny_gutenberg_fse_update_theme_json' ) ) {
	add_action( 'greeny_action_skin_switched', 'greeny_gutenberg_fse_update_theme_json', 30, 2 );
	add_action( 'greeny_action_save_options', 'greeny_gutenberg_fse_update_theme_json', 30 );
	add_action( 'trx_addons_action_save_options', 'greeny_gutenberg_fse_update_theme_json', 30 );
	/**
	 * Update the file 'theme.json' after the current skin is switched and/or theme options are saved.
	 * 
	 * Hooks: add_action( 'greeny_action_skin_switched', 'greeny_gutenberg_fse_update_theme_json', 30, 2 );
	 *        add_action( 'greeny_action_save_options', 'greeny_gutenberg_fse_update_theme_json', 30 );
	 *        add_action( 'trx_addons_action_save_options', 'greeny_gutenberg_fse_update_theme_json', 30 );
	 * 
	 * Trigger filter 'greeny_filter_gutenberg_fse_theme_json_data' to allow other modules modify this data before saving.
	 */
	function greeny_gutenberg_fse_update_theme_json( $new_skin = '', $old_skin = '' ) {
		// If the current skin supports FSE
		if ( ! empty( $new_skin ) && is_dir( GREENY_THEME_DIR . greeny_skins_get_current_skin_dir( $new_skin ) . '_fse' ) ) {
			$theme_json_data = greeny_gutenberg_fse_theme_json_data();
			if ( ! empty( $theme_json_data ) && is_array( $theme_json_data ) ) {
				greeny_fpc( greeny_prepare_path( GREENY_THEME_DIR . 'theme.json' ), json_encode( $theme_json_data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES ) );
			}
		}
	}
}

if ( ! function_exists( 'greeny_gutenberg_fse_theme_json_data' ) ) {
	/**
	 * Return a default data to save to the file 'theme.json'
	 * 
	 * Trigger filter 'greeny_filter_gutenberg_fse_theme_json_data' to allow other modules change data.
	 * 
	 * @return array  Data for the 'theme.json'
	 */
	function greeny_gutenberg_fse_theme_json_data() {

		$data = array(

					"version" => 2,
					
					// Theme-specific templates for posts, pages, etc.
					"customTemplates" => array(
						array(
							"name"      => "blog",
							"title"     => esc_html__( "Blog archive", 'greeny' ),
							"postTypes" => array(
								"page"
							)
						)
					),

					// Theme-specific template parts
					"templateParts" => array(
						array(
							"name"  => "header",
							"title" => esc_html__( "Header for FSE", 'greeny' ),
							"area"  => "header",
						),
						array(
							"name"  => "sidebar",
							"title" => esc_html__( "Sidebar for FSE", 'greeny' ),
							"area"  => "uncategorized",
						),
						array(
							"name"  => "content-404",
							"title" => esc_html__( "Page 404 content", 'greeny' ),
							"area"  => "uncategorized",
						),
						array(
							"name"  => "blog-post-standard",
							"title" => esc_html__( "Blog post item: Standard", 'greeny' ),
							"area"  => "uncategorized",
						),
						array(
							"name"  => "blog-post-header",
							"title" => esc_html__( "Blog post header", 'greeny' ),
							"area"  => "uncategorized",
						),
						array(
							"name"  => "blog-pagination",
							"title" => esc_html__( "Blog pagination", 'greeny' ),
							"area"  => "uncategorized",
						),
						array(
							"name"  => "footer",
							"title" => esc_html__( "Footer for FSE", 'greeny' ),
							"area"  => "footer",
						),
					),

					// General settings
					"settings" => array(

						"appearanceTools" => true,

						"spacing" => array(
							"units" => array( "%", "px", "em", "rem", "vh", "vw" ),
							"blockGap" => null,
						),

						// Theme colors
						"color" => array(
							"palette"   => array(),		// Should be added later from a color scheme
							"duotone"   => array(),		// Should be added later from a color scheme
							"gradients" => array(),		// Should be added later from a color scheme
						),

						// Typography
						"typography" => array(
							"dropCap"      => false,
/*
							// Font family - can be used below as CSS-vars
							// For example: var(--wp--preset--font-family--xxx)
							// where 'xxx' - is a slug of theme-specific typography setting, like 'p-font', 'h-1-font', etc.
							// Should be added later from a theme fonts
							"fontFamilies"  => array(),
							// Font size - can be used below as CSS-vars
							// For example: var(--wp--preset--font-size--xxx)
							// where 'xxx' - is a slug of theme-specific typography setting, like 'p-font', 'h-1-font', etc.
							// Should be added later from a theme fonts
							"fontSizes"     => array(),
							// Font weight - can be used below as CSS-vars
							// For example: var(--wp--preset--font-weight--xxx)
							// where 'xxx' - is a slug of theme-specific typography setting, like 'p-font', 'h-1-font', etc.
							// Should be added later from a theme fonts
							"fontWeight"    => array(),
							// Line height - can be used below as CSS-vars
							// For example: var(--wp--preset--line-height--xxx)
							// where 'xxx' - is a slug of theme-specific typography setting, like 'p-font', 'h-1-font', etc.
							// Should be added later from a theme fonts
							"lineHeight"    => array(),
							// Letter spacing - can be used below as CSS-vars
							// For example: var(--wp--preset--letter-spacing--xxx)
							// where 'xxx' - is a slug of theme-specific typography setting, like 'p-font', 'h-1-font', etc.
							// Should be added later from a theme fonts
							"letterSpacing" => array(),
*/
						),

						// Layout dimensions
						// Should be added later from a theme vars
						"layout" => array(),

						// Custom styles - can be used below as CSS-vars.
						"custom" => array(
							// Spacing - can be used as a value for a padding or a margin
							// For example: var(--wp--custom--spacing--xxx), where 'xxx' is a key of the array 'spacing'
							"spacing" => array(
								"tiny"   => "var(--sc-space-tiny,   1rem)",      //"max(1rem, 3vw)",
								"small"  => "var(--sc-space-small,  2rem)",      //"max(1.25rem, 5vw)",
								"medium" => "var(--sc-space-medium, 3.3333rem)", //"clamp(2rem, 8vw, calc(4 * var(--wp--style--block-gap)))",
								"large"  => "var(--sc-space-large,  6.6667rem)", //"clamp(4rem, 10vw, 8rem)",
								"huge"   => "var(--sc-space-huge,   8.6667rem)",
							),
/*
							"typography" => array(
								// Custom font-size usage:
								// var(--wp--custom--typography--font-size--xxx), where 'xxx' is a key of the array 'font-size'
								"font-size" => array(
									"huge"     => "clamp(2.25rem, 4vw, 2.75rem)",
									"gigantic" => "clamp(2.75rem, 6vw, 3.25rem)",
									"colossal" => "clamp(3.25rem, 8vw, 6.25rem)",
								),
								// Custom line-height usage:
								// var(--wp--custom--typography--line-height--xxx), where 'xxx' is a key of the array 'line-height'
								"line-height" => array(
									"tiny"   => 1.15,
									"small"  => 1.2,
									"medium" => 1.4,
									"normal" => 1.6,
								),
							),
*/
						),
					),

					// General styles
					"styles" => array(
						// Gap between blocks
						"spacing" => array(
							"blockGap" => 0,
						),
/*
						// Main site colors
						"color" => array(
							"background" => "var(--theme-color--bg_color)",
							"text"       => "var(--theme-color--text)",
						),
						// Default typography
						"typography" => array(
							"fontFamily" => "var(--theme-font-p_font-family)",
							"lineHeight" => "var(--theme-font-p_line-height)",
							"fontSize"   => "var(--theme-font-p_font-size)",
						),
						// Styles for main theme elements
						"elements" => array(
							"h1" => array(
								"typography" => array(
									"fontFamily"    => "var(--wp--preset--font-family--h-1-font)",
									"fontWeight"    => "var(--wp--preset--font-weight--h-1-font)",
									"fontSize"      => "var(--wp--preset--font-size--h-1-font)",
									"lineHeight"    => "var(--wp--preset--line-height--h-1-font)",
									"letterSpacing" => "var(--wp--preset--letter-spacing--h-1-font)",
								),
							),
							"h2" => array(
								"typography" => array(
									"fontFamily"    => "var(--wp--preset--font-family--h-2-font)",
									"fontWeight"    => "var(--wp--preset--font-weight--h-2-font)",
									"fontSize"      => "var(--wp--preset--font-size--h-2-font)",
									"lineHeight"    => "var(--wp--preset--line-height--h-2-font)",
									"letterSpacing" => "var(--wp--preset--letter-spacing--h-2-font)",
								),
							),
							"h3" => array(
								"typography" => array(
									"fontFamily"    => "var(--wp--preset--font-family--h-3-font)",
									"fontWeight"    => "var(--wp--preset--font-weight--h-3-font)",
									"fontSize"      => "var(--wp--preset--font-size--h-3-font)",
									"lineHeight"    => "var(--wp--preset--line-height--h-3-font)",
									"letterSpacing" => "var(--wp--preset--letter-spacing--h-3-font)",
								),
							),
							"h4" => array(
								"typography" => array(
									"fontFamily"    => "var(--wp--preset--font-family--h-4-font)",
									"fontWeight"    => "var(--wp--preset--font-weight--h-4-font)",
									"fontSize"      => "var(--wp--preset--font-size--h-4-font)",
									"lineHeight"    => "var(--wp--preset--line-height--h-4-font)",
									"letterSpacing" => "var(--wp--preset--letter-spacing--h-4-font)",
								),
							),
							"h5" => array(
								"typography" => array(
									"fontFamily"    => "var(--wp--preset--font-family--h-5-font)",
									"fontWeight"    => "var(--wp--preset--font-weight--h-5-font)",
									"fontSize"      => "var(--wp--preset--font-size--h-5-font)",
									"lineHeight"    => "var(--wp--preset--line-height--h-5-font)",
									"letterSpacing" => "var(--wp--preset--letter-spacing--h-5-font)",
								),
							),
							"h6" => array(
								"typography" => array(
									"fontFamily"    => "var(--wp--preset--font-family--h-6-font)",
									"fontWeight"    => "var(--wp--preset--font-weight--h-6-font)",
									"fontSize"      => "var(--wp--preset--font-size--h-6-font)",
									"lineHeight"    => "var(--wp--preset--line-height--h-6-font)",
									"letterSpacing" => "var(--wp--preset--letter-spacing--h-6-font)",
								),
							),
							"link" => array(
								"color" => array(
									"text" => "var(--wp--preset--color--text_link)",
								),
							),
						),
*/
						// Core blocks decoration
						"blocks" => array(
							"core/button" => array(
								"border" => array(
									"radius" => "0",
								),
								"color" => array(
									"background" => "var(--theme-color--text_link)",
									"text"       => "var(--theme-color--inverse_link)",
								),
								"typography" => array(
									"fontFamily" => "var(--theme-font-button_font-family)",
									"fontWeight" => "var(--theme-font-button_font-weight)",
									"fontSize"   => "var(--theme-font-button_font-size)",
									"lineHeight" => "var(--theme-font-button_line-height)",
								),
							),
							"core/post-title" => array(
								"typography" => array(
									"fontFamily" => "var(--theme-font-h3_font-family)",
									"fontWeight" => "var(--theme-font-h3_font-weight)",
									"fontSize"   => "var(--theme-font-h3_font-size)",
									"lineHeight" => "var(--theme-font-h3_line-height)",
								),
							),
							"core/post-comments" => array(
								"spacing" => array(
									"padding" => array(
										"top" => "var(--wp--custom--spacing--small)",
									),
								),
							),
							"core/pullquote" => array(
								"border" => array(
									"width" => "1px 0",
								),
							),
							"core/query-title" => array(
								"typography" => array(
									"fontFamily" => "var(--theme-font-h3_font-family)",
									"fontWeight" => "var(--theme-font-h3_font-weight)",
									"fontSize"   => "var(--theme-font-h3_font-size)",
									"lineHeight" => "var(--theme-font-h3_line-height)",
								),
							),
							"core/quote" => array(
								"border" => array(
									"width" => "1px",
								),
							),
							"core/site-title" => array(
								"typography" => array(
									"fontFamily" => "var(--theme-font-h1_font-family)",
									"fontWeight" => "var(--theme-font-h1_font-weight)",
									"fontSize"   => "var(--theme-font-h1_font-size)",
									"lineHeight" => "var(--theme-font-h1_line-height)",
								),
							),
						),
					),
				);

		// Add palette: all colors from the scheme 'default'
		$scheme = greeny_get_scheme_colors();
		$groups = greeny_storage_get( 'scheme_color_groups' );
		$names  = greeny_storage_get( 'scheme_color_names' );
		foreach( $groups as $g => $group ) {
			foreach( $names as $n => $name ) {
				$c = 'main' == $g ? ( 'text' == $n ? 'text_color' : $n ) : $g . '_' . str_replace( 'text_', '', $n );
				if ( isset( $scheme[ $c ] ) ) {
					$data['settings']['color']['palette'][] = array(
						'slug'  => $c,
						'color' => $scheme[ $c ],
						'name'  => ( 'main' == $g ? '' : $group['title'] . ' ' ) . $name['title'],
					);
				}
			}
			// Add only one group of colors
			// Delete next condition (or add false && to them) to add all groups
			if ( 'main' == $g ) {
				break;
			}
		}

		// Add duotones (a two colors combination)
		$data['settings']['color']['duotone'][] = array(
			"colors" => array( $scheme['bg_color'], $scheme['text'] ),
			"slug"   => 'bg-and-text',
			"name"   => esc_html__( 'Background and text color', 'greeny' )
		);
		$data['settings']['color']['duotone'][] = array(
			"colors" => array( $scheme['bg_color'], $scheme['text_dark'] ),
			"slug"   => 'bg-and-dark',
			"name"   => esc_html__( 'Background and dark color', 'greeny' )
		);
		$data['settings']['color']['duotone'][] = array(
			"colors" => array( $scheme['bg_color'], $scheme['text_link'] ),
			"slug"   => 'bg-and-link',
			"name"   => esc_html__( 'Background and link color', 'greeny' )
		);

		// Add gradients
		$data['settings']['color']['gradients'][] = array(
			"slug"     => 'vertical-link-to-hover',
			"gradient" => 'linear-gradient(to bottom,var(--theme-color-text_link) 0%,var(--theme-color-text_hover) 100%)',
			"name"     => esc_html__( 'Vertical from link color to hover color', 'greeny' ),
		);
		$data['settings']['color']['gradients'][] = array(
			"slug"     => 'diagonal-link-to-hover',
			"gradient" => 'linear-gradient(to bottom right,var(--theme-color-text_link) 0%,var(--theme-color-text_hover) 100%)',
			"name"     => esc_html__( 'Diagonal from link color to hover color', 'greeny' ),
		);
/*
		// Add fonts
		$fonts = greeny_get_theme_fonts();
		foreach( $fonts as $tag => $font ) {
			if ( ! in_array( $tag, array( 'button', 'p', 'h1', 'h2', 'h3', 'h4', 'h5', 'h6' ) ) ) {
				continue;
			}
			$data['settings']['typography']['fontFamilies'][] = array(
				"fontFamily" => $font['font-family'],
				"name"       => $font['title'],
				"slug"       => "{$tag}-font",
			);
			$data['settings']['typography']['fontSizes'][] = array(
				"size"       => $font['font-size'],
				"slug"       => "{$tag}-font",
			);
			$data['settings']['typography']['fontWeight'][] = array(
				"size" => $font['font-weight'],
				"slug"       => "{$tag}-font",
			);
			$data['settings']['typography']['lineHeight'][] = array(
				"size" => $font['line-height'],
				"slug"       => "{$tag}-font",
			);
			$data['settings']['typography']['letterSpacing'][] = array(
				"size" => ! empty( $font['letter-spacing'] ) ? $font['letter-spacing'] : '0',
				"slug"          => "{$tag}-font",
			);
		}
*/
		// Layout dimensions
		$vars = greeny_get_theme_vars();
		$data['settings']['layout']['contentSize'] = ( $vars['page_width'] - $vars['sidebar_width'] - $vars['sidebar_gap'] ) . 'px';
		$data['settings']['layout']['wideSize']    = $vars['page_width'] . 'px';

		return apply_filters( 'greeny_filter_gutenberg_fse_theme_json_data', $data );
	}
}
