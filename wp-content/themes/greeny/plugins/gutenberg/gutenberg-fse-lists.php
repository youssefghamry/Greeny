<?php
/**
 * Gutenberg Full-Site Editor (FSE) lists with different templates: headers, footers, sidebars.
 */

if ( ! function_exists( 'greeny_gutenberg_fse_list_header_styles' ) ) {
	add_filter( 'greeny_filter_list_header_styles', 'greeny_gutenberg_fse_list_header_styles');
	/**
	 * Add a WordPress FSE templates to the headers list.
	 * 
	 * Hooks: add_filter( 'greeny_filter_list_header_styles', 'greeny_gutenberg_fse_list_header_styles');
	 * 
	 * @param array $list  Optional. An array with a list of headers from other available Page Builders.
	 */
	function greeny_gutenberg_fse_list_header_styles( $list = array() ) {
		$new_list = array();
		// Add a default templates
		$data = greeny_gutenberg_fse_theme_json_data();
		if ( ! empty( $data['templateParts'] ) && is_array( $data['templateParts'] ) ) {
			foreach ( $data['templateParts'] as $template ) {
				if ( ! empty( $template['area'] ) && 'header' == $template['area'] ) {
					$new_list[ 'header-fse-template-' . trim( $template['name'] ) ] = $template['title'];
				}
			}
		}
		// Add a changed templates and new templates (created by user)
		$layouts = greeny_get_list_posts(
			false, array(
				'post_type'    => GREENY_FSE_TEMPLATE_PART_PT,
				'orderby'      => 'ID',
				'order'        => 'asc',
				'not_selected' => false,
				//'return'       => 'post_name',
				'tax_query'    => array(
										'relation' => 'AND',
										array(
											'taxonomy' => 'wp_theme',
											'field'    => 'name',
											'terms'    => get_stylesheet(),
										),
										array(
											'taxonomy' => 'wp_template_part_area',
											'field'    => 'name',
											'terms'    => 'header',
										)
									)
			)
		);
		foreach ( $layouts as $id => $title ) {
			if ( 'none' != $id ) {
				$new_list[ 'header-fse-template-' . trim( $id ) ] = $title;
			}
		}
		$list = greeny_array_merge( $new_list, $list );
		return $list;
	}
}

if ( ! function_exists( 'greeny_gutenberg_fse_list_footer_styles' ) ) {
	add_filter( 'greeny_filter_list_footer_styles', 'greeny_gutenberg_fse_list_footer_styles');
	/**
	 * Add a WordPress FSE templates to the footers list.
	 * 
	 * Hooks: add_filter( 'greeny_filter_list_footer_styles', 'greeny_gutenberg_fse_list_footer_styles');
	 * 
	 * @param array $list  Optional. An array with a list of footers from other available Page Builders.
	 */
	function greeny_gutenberg_fse_list_footer_styles( $list = array() ) {
		$new_list = array();
		// Add a default templates
		$data = greeny_gutenberg_fse_theme_json_data();
		if ( ! empty( $data['templateParts'] ) && is_array( $data['templateParts'] ) ) {
			foreach ( $data['templateParts'] as $template ) {
				if ( ! empty( $template['area'] ) && 'footer' == $template['area'] ) {
					$new_list[ 'footer-fse-template-' . trim( $template['name'] ) ] = $template['title'];
				}
			}
		}
		// Add a changed templates and new templates (created by user)
		$layouts = greeny_get_list_posts(
			false, array(
				'post_type'    => GREENY_FSE_TEMPLATE_PART_PT,
				'orderby'      => 'ID',
				'order'        => 'asc',
				'not_selected' => false,
				//'return'       => 'post_name',
				'tax_query'    => array(
										'relation' => 'AND',
										array(
											'taxonomy' => 'wp_theme',
											'field'    => 'name',
											'terms'    => get_stylesheet(),
										),
										array(
											'taxonomy' => 'wp_template_part_area',
											'field'    => 'name',
											'terms'    => 'footer',
										)
									)
			)
		);
		foreach ( $layouts as $id => $title ) {
			if ( 'none' != $id ) {
				$new_list[ 'footer-fse-template-' . trim( $id ) ] = $title;
			}
		}
		$list = greeny_array_merge( $new_list, $list );
		return $list;
	}
}
