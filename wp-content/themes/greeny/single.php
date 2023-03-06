<?php
/**
 * The template to display single post
 *
 * @package GREENY
 * @since GREENY 1.0
 */

// Full post loading
$full_post_loading          = greeny_get_value_gp( 'action' ) == 'full_post_loading';

// Prev post loading
$prev_post_loading          = greeny_get_value_gp( 'action' ) == 'prev_post_loading';
$prev_post_loading_type     = greeny_get_theme_option( 'posts_navigation_scroll_which_block' );

// Position of the related posts
$greeny_related_position   = greeny_get_theme_option( 'related_position' );

// Type of the prev/next post navigation
$greeny_posts_navigation   = greeny_get_theme_option( 'posts_navigation' );
$greeny_prev_post          = false;
$greeny_prev_post_same_cat = greeny_get_theme_option( 'posts_navigation_scroll_same_cat' );

// Rewrite style of the single post if current post loading via AJAX and featured image and title is not in the content
if ( ( $full_post_loading 
		|| 
		( $prev_post_loading && 'article' == $prev_post_loading_type )
	) 
	&& 
	! in_array( greeny_get_theme_option( 'single_style' ), array( 'style-6' ) )
) {
	greeny_storage_set_array( 'options_meta', 'single_style', 'style-6' );
}

do_action( 'greeny_action_prev_post_loading', $prev_post_loading, $prev_post_loading_type );

get_header();

while ( have_posts() ) {

	the_post();

	// Type of the prev/next post navigation
	if ( 'scroll' == $greeny_posts_navigation ) {
		$greeny_prev_post = get_previous_post( $greeny_prev_post_same_cat );  // Get post from same category
		if ( ! $greeny_prev_post && $greeny_prev_post_same_cat ) {
			$greeny_prev_post = get_previous_post( false );                    // Get post from any category
		}
		if ( ! $greeny_prev_post ) {
			$greeny_posts_navigation = 'links';
		}
	}

	// Override some theme options to display featured image, title and post meta in the dynamic loaded posts
	if ( $full_post_loading || ( $prev_post_loading && $greeny_prev_post ) ) {
		greeny_sc_layouts_showed( 'featured', false );
		greeny_sc_layouts_showed( 'title', false );
		greeny_sc_layouts_showed( 'postmeta', false );
	}

	// If related posts should be inside the content
	if ( strpos( $greeny_related_position, 'inside' ) === 0 ) {
		ob_start();
	}

	// Display post's content
	get_template_part( apply_filters( 'greeny_filter_get_template_part', 'templates/content', 'single-' . greeny_get_theme_option( 'single_style' ) ), 'single-' . greeny_get_theme_option( 'single_style' ) );

	// If related posts should be inside the content
	if ( strpos( $greeny_related_position, 'inside' ) === 0 ) {
		$greeny_content = ob_get_contents();
		ob_end_clean();

		ob_start();
		do_action( 'greeny_action_related_posts' );
		$greeny_related_content = ob_get_contents();
		ob_end_clean();

		if ( ! empty( $greeny_related_content ) ) {
			$greeny_related_position_inside = max( 0, min( 9, greeny_get_theme_option( 'related_position_inside' ) ) );
			if ( 0 == $greeny_related_position_inside ) {
				$greeny_related_position_inside = mt_rand( 1, 9 );
			}

			$greeny_p_number         = 0;
			$greeny_related_inserted = false;
			$greeny_in_block         = false;
			$greeny_content_start    = strpos( $greeny_content, '<div class="post_content' );
			$greeny_content_end      = strrpos( $greeny_content, '</div>' );

			for ( $i = max( 0, $greeny_content_start ); $i < min( strlen( $greeny_content ) - 3, $greeny_content_end ); $i++ ) {
				if ( $greeny_content[ $i ] != '<' ) {
					continue;
				}
				if ( $greeny_in_block ) {
					if ( strtolower( substr( $greeny_content, $i + 1, 12 ) ) == '/blockquote>' ) {
						$greeny_in_block = false;
						$i += 12;
					}
					continue;
				} else if ( strtolower( substr( $greeny_content, $i + 1, 10 ) ) == 'blockquote' && in_array( $greeny_content[ $i + 11 ], array( '>', ' ' ) ) ) {
					$greeny_in_block = true;
					$i += 11;
					continue;
				} else if ( 'p' == $greeny_content[ $i + 1 ] && in_array( $greeny_content[ $i + 2 ], array( '>', ' ' ) ) ) {
					$greeny_p_number++;
					if ( $greeny_related_position_inside == $greeny_p_number ) {
						$greeny_related_inserted = true;
						$greeny_content = ( $i > 0 ? substr( $greeny_content, 0, $i ) : '' )
											. $greeny_related_content
											. substr( $greeny_content, $i );
					}
				}
			}
			if ( ! $greeny_related_inserted ) {
				if ( $greeny_content_end > 0 ) {
					$greeny_content = substr( $greeny_content, 0, $greeny_content_end ) . $greeny_related_content . substr( $greeny_content, $greeny_content_end );
				} else {
					$greeny_content .= $greeny_related_content;
				}
			}
		}

		greeny_show_layout( $greeny_content );
	}

	// Comments
	do_action( 'greeny_action_before_comments' );
	comments_template();
	do_action( 'greeny_action_after_comments' );

	// Related posts
	if ( 'below_content' == $greeny_related_position
		&& ( 'scroll' != $greeny_posts_navigation || greeny_get_theme_option( 'posts_navigation_scroll_hide_related' ) == 0 )
		&& ( ! $full_post_loading || greeny_get_theme_option( 'open_full_post_hide_related' ) == 0 )
	) {
		do_action( 'greeny_action_related_posts' );
	}

	// Post navigation: type 'scroll'
	if ( 'scroll' == $greeny_posts_navigation && ! $full_post_loading ) {
		?>
		<div class="nav-links-single-scroll"
			data-post-id="<?php echo esc_attr( get_the_ID( $greeny_prev_post ) ); ?>"
			data-post-link="<?php echo esc_attr( get_permalink( $greeny_prev_post ) ); ?>"
			data-post-title="<?php the_title_attribute( array( 'post' => $greeny_prev_post ) ); ?>"
			<?php do_action( 'greeny_action_nav_links_single_scroll_data', $greeny_prev_post ); ?>
		></div>
		<?php
	}
}

get_footer();
