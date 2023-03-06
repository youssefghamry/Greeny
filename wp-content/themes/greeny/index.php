<?php
/**
 * The main template file.
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 * Learn more: //codex.wordpress.org/Template_Hierarchy
 *
 * @package GREENY
 * @since GREENY 1.0
 */

$greeny_template = apply_filters( 'greeny_filter_get_template_part', greeny_blog_archive_get_template() );

if ( ! empty( $greeny_template ) && 'index' != $greeny_template ) {

	get_template_part( $greeny_template );

} else {

	greeny_storage_set( 'blog_archive', true );

	get_header();

	if ( have_posts() ) {

		// Query params
		$greeny_stickies   = is_home()
								|| ( in_array( greeny_get_theme_option( 'post_type' ), array( '', 'post' ) )
									&& (int) greeny_get_theme_option( 'parent_cat' ) == 0
									)
										? get_option( 'sticky_posts' )
										: false;
		$greeny_post_type  = greeny_get_theme_option( 'post_type' );
		$greeny_args       = array(
								'blog_style'     => greeny_get_theme_option( 'blog_style' ),
								'post_type'      => $greeny_post_type,
								'taxonomy'       => greeny_get_post_type_taxonomy( $greeny_post_type ),
								'parent_cat'     => greeny_get_theme_option( 'parent_cat' ),
								'posts_per_page' => greeny_get_theme_option( 'posts_per_page' ),
								'sticky'         => greeny_get_theme_option( 'sticky_style' ) == 'columns'
															&& is_array( $greeny_stickies )
															&& count( $greeny_stickies ) > 0
															&& get_query_var( 'paged' ) < 1
								);

		greeny_blog_archive_start();

		do_action( 'greeny_action_blog_archive_start' );

		if ( is_author() ) {
			do_action( 'greeny_action_before_page_author' );
			get_template_part( apply_filters( 'greeny_filter_get_template_part', 'templates/author-page' ) );
			do_action( 'greeny_action_after_page_author' );
		}

		if ( greeny_get_theme_option( 'show_filters' ) ) {
			do_action( 'greeny_action_before_page_filters' );
			greeny_show_filters( $greeny_args );
			do_action( 'greeny_action_after_page_filters' );
		} else {
			do_action( 'greeny_action_before_page_posts' );
			greeny_show_posts( array_merge( $greeny_args, array( 'cat' => $greeny_args['parent_cat'] ) ) );
			do_action( 'greeny_action_after_page_posts' );
		}

		do_action( 'greeny_action_blog_archive_end' );

		greeny_blog_archive_end();

	} else {

		if ( is_search() ) {
			get_template_part( apply_filters( 'greeny_filter_get_template_part', 'templates/content', 'none-search' ), 'none-search' );
		} else {
			get_template_part( apply_filters( 'greeny_filter_get_template_part', 'templates/content', 'none-archive' ), 'none-archive' );
		}
	}

	get_footer();
}
