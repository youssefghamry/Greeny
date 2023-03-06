<?php
/**
 * The Classic template to display the content
 *
 * Used for index/archive/search.
 *
 * @package GREENY
 * @since GREENY 1.0
 */

$greeny_template_args = get_query_var( 'greeny_template_args' );

if ( is_array( $greeny_template_args ) ) {
	$greeny_columns    = empty( $greeny_template_args['columns'] ) ? 2 : max( 1, $greeny_template_args['columns'] );
	$greeny_blog_style = array( $greeny_template_args['type'], $greeny_columns );
    $greeny_columns_class = greeny_get_column_class( 1, $greeny_columns, ! empty( $greeny_template_args['columns_tablet']) ? $greeny_template_args['columns_tablet'] : '', ! empty($greeny_template_args['columns_mobile']) ? $greeny_template_args['columns_mobile'] : '' );
} else {
	$greeny_blog_style = explode( '_', greeny_get_theme_option( 'blog_style' ) );
	$greeny_columns    = empty( $greeny_blog_style[1] ) ? 2 : max( 1, $greeny_blog_style[1] );
    $greeny_columns_class = greeny_get_column_class( 1, $greeny_columns );
}
$greeny_expanded   = ! greeny_sidebar_present() && greeny_get_theme_option( 'expand_content' ) == 'expand';

$greeny_post_format = get_post_format();
$greeny_post_format = empty( $greeny_post_format ) ? 'standard' : str_replace( 'post-format-', '', $greeny_post_format );

?><div class="<?php
	if ( ! empty( $greeny_template_args['slider'] ) ) {
		echo ' slider-slide swiper-slide';
	} else {
		echo ( greeny_is_blog_style_use_masonry( $greeny_blog_style[0] ) ? 'masonry_item masonry_item-1_' . esc_attr( $greeny_columns ) : esc_attr( $greeny_columns_class ) );
	}
?>"><article id="post-<?php the_ID(); ?>" data-post-id="<?php the_ID(); ?>"
	<?php
	post_class(
		'post_item post_item_container post_format_' . esc_attr( $greeny_post_format )
				. ' post_layout_classic post_layout_classic_' . esc_attr( $greeny_columns )
				. ' post_layout_' . esc_attr( $greeny_blog_style[0] )
				. ' post_layout_' . esc_attr( $greeny_blog_style[0] ) . '_' . esc_attr( $greeny_columns )
	);
	greeny_add_blog_animation( $greeny_template_args );
	?>
>
	<?php

	// Sticky label
	if ( is_sticky() && ! is_paged() ) {
		?>
		<span class="post_label label_sticky"></span>
		<?php
	}

	// Featured image
	$greeny_hover      = ! empty( $greeny_template_args['hover'] ) && ! greeny_is_inherit( $greeny_template_args['hover'] )
							? $greeny_template_args['hover']
							: greeny_get_theme_option( 'image_hover' );

	$greeny_components = ! empty( $greeny_template_args['meta_parts'] )
							? ( is_array( $greeny_template_args['meta_parts'] )
								? $greeny_template_args['meta_parts']
								: explode( ',', $greeny_template_args['meta_parts'] )
								)
							: greeny_array_get_keys_by_value( greeny_get_theme_option( 'meta_parts' ) );

	greeny_show_post_featured( apply_filters( 'greeny_filter_args_featured',
		array(
			'thumb_size' => ! empty( $greeny_template_args['thumb_size'] )
				? $greeny_template_args['thumb_size']
				: greeny_get_thumb_size(
				'classic' == $greeny_blog_style[0]
						? ( strpos( greeny_get_theme_option( 'body_style' ), 'full' ) !== false
								? ( $greeny_columns > 2 ? 'big' : 'huge' )
								: ( $greeny_columns > 2
									? ( $greeny_expanded ? 'square' : 'square' )
									: ($greeny_columns > 1 ? 'square' : ( $greeny_expanded ? 'huge' : 'big' ))
									)
							)
						: ( strpos( greeny_get_theme_option( 'body_style' ), 'full' ) !== false
								? ( $greeny_columns > 2 ? 'masonry-big' : 'full' )
								: ($greeny_columns === 1 ? ( $greeny_expanded ? 'huge' : 'big' ) : ( $greeny_columns <= 2 && $greeny_expanded ? 'masonry-big' : 'masonry' ))
							)
			),
			'hover'      => $greeny_hover,
			'meta_parts' => $greeny_components,
			'no_links'   => ! empty( $greeny_template_args['no_links'] ),
        ),
        'content-classic',
        $greeny_template_args
    ) );

	// Title and post meta
	$greeny_show_title = get_the_title() != '';
	$greeny_show_meta  = count( $greeny_components ) > 0 && ! in_array( $greeny_hover, array( 'border', 'pull', 'slide', 'fade', 'info' ) );

	if ( $greeny_show_title ) {
		?>
		<div class="post_header entry-header">
			<?php

			// Post meta
			if ( apply_filters( 'greeny_filter_show_blog_meta', $greeny_show_meta, $greeny_components, 'classic' ) ) {
				if ( count( $greeny_components ) > 0 ) {
					do_action( 'greeny_action_before_post_meta' );
					greeny_show_post_meta(
						apply_filters(
							'greeny_filter_post_meta_args', array(
							'components' => join( ',', $greeny_components ),
							'seo'        => false,
							'echo'       => true,
						), $greeny_blog_style[0], $greeny_columns
						)
					);
					do_action( 'greeny_action_after_post_meta' );
				}
			}

			// Post title
			if ( apply_filters( 'greeny_filter_show_blog_title', true, 'classic' ) ) {
				do_action( 'greeny_action_before_post_title' );
				if ( empty( $greeny_template_args['no_links'] ) ) {
					the_title( sprintf( '<h4 class="post_title entry-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h4>' );
				} else {
					the_title( '<h4 class="post_title entry-title">', '</h4>' );
				}
				do_action( 'greeny_action_after_post_title' );
			}

			if( !in_array( $greeny_post_format, array( 'quote', 'aside', 'link', 'status' ) ) ) {
				// More button
				if ( apply_filters( 'greeny_filter_show_blog_readmore', ! $greeny_show_title || ! empty( $greeny_template_args['more_button'] ), 'classic' ) ) {
					if ( empty( $greeny_template_args['no_links'] ) ) {
						do_action( 'greeny_action_before_post_readmore' );
						greeny_show_post_more_link( $greeny_template_args, '<div class="more-wrap">', '</div>' );
						do_action( 'greeny_action_after_post_readmore' );
					}
				}
			}
			?>
		</div><!-- .entry-header -->
		<?php
	}

	// Post content
	if( in_array( $greeny_post_format, array( 'quote', 'aside', 'link', 'status' ) ) ) {
		ob_start();
		if (apply_filters('greeny_filter_show_blog_excerpt', empty($greeny_template_args['hide_excerpt']) && greeny_get_theme_option('excerpt_length') > 0, 'classic')) {
			greeny_show_post_content($greeny_template_args, '<div class="post_content_inner">', '</div>');
		}
		// More button
		if(! empty( $greeny_template_args['more_button'] )) {
			if ( empty( $greeny_template_args['no_links'] ) ) {
				do_action( 'greeny_action_before_post_readmore' );
				greeny_show_post_more_link( $greeny_template_args, '<div class="more-wrap">', '</div>' );
				do_action( 'greeny_action_after_post_readmore' );
			}
		}
		$greeny_content = ob_get_contents();
		ob_end_clean();
		greeny_show_layout($greeny_content, '<div class="post_content entry-content">', '</div><!-- .entry-content -->');
	}
	?>

</article></div><?php
// Need opening PHP-tag above, because <div> is a inline-block element (used as column)!
