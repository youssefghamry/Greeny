<?php
/**
 * The Portfolio template to display the content
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

$greeny_post_format = get_post_format();
$greeny_post_format = empty( $greeny_post_format ) ? 'standard' : str_replace( 'post-format-', '', $greeny_post_format );

?><div class="
<?php
if ( ! empty( $greeny_template_args['slider'] ) ) {
	echo ' slider-slide swiper-slide';
} else {
	echo ( greeny_is_blog_style_use_masonry( $greeny_blog_style[0] ) ? 'masonry_item masonry_item-1_' . esc_attr( $greeny_columns ) : esc_attr( $greeny_columns_class ));
}
?>
"><article id="post-<?php the_ID(); ?>" 
	<?php
	post_class(
		'post_item post_item_container post_format_' . esc_attr( $greeny_post_format )
		. ' post_layout_portfolio'
		. ' post_layout_portfolio_' . esc_attr( $greeny_columns )
		. ( 'portfolio' != $greeny_blog_style[0] ? ' ' . esc_attr( $greeny_blog_style[0] )  . '_' . esc_attr( $greeny_columns ) : '' )
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

	$greeny_hover   = ! empty( $greeny_template_args['hover'] ) && ! greeny_is_inherit( $greeny_template_args['hover'] )
								? $greeny_template_args['hover']
								: greeny_get_theme_option( 'image_hover' );

	if ( 'dots' == $greeny_hover ) {
		$greeny_post_link = empty( $greeny_template_args['no_links'] )
								? ( ! empty( $greeny_template_args['link'] )
									? $greeny_template_args['link']
									: get_permalink()
									)
								: '';
		$greeny_target    = ! empty( $greeny_post_link ) && false === strpos( $greeny_post_link, home_url() )
								? ' target="_blank" rel="nofollow"'
								: '';
	}
	
	// Meta parts
	$greeny_components = ! empty( $greeny_template_args['meta_parts'] )
							? ( is_array( $greeny_template_args['meta_parts'] )
								? $greeny_template_args['meta_parts']
								: explode( ',', $greeny_template_args['meta_parts'] )
								)
							: greeny_array_get_keys_by_value( greeny_get_theme_option( 'meta_parts' ) );

	// Featured image
	greeny_show_post_featured( apply_filters( 'greeny_filter_args_featured',
        array(
			'hover'         => $greeny_hover,
			'no_links'      => ! empty( $greeny_template_args['no_links'] ),
			'thumb_size'    => ! empty( $greeny_template_args['thumb_size'] )
								? $greeny_template_args['thumb_size']
								: greeny_get_thumb_size(
									greeny_is_blog_style_use_masonry( $greeny_blog_style[0] )
										? (	strpos( greeny_get_theme_option( 'body_style' ), 'full' ) !== false || $greeny_columns < 3
											? 'masonry-big'
											: 'masonry'
											)
										: (	strpos( greeny_get_theme_option( 'body_style' ), 'full' ) !== false || $greeny_columns < 3
											? 'square'
											: 'square'
											)
								),
			'thumb_bg' => greeny_is_blog_style_use_masonry( $greeny_blog_style[0] ) ? false : true,
			'show_no_image' => true,
			'meta_parts'    => $greeny_components,
			'class'         => 'dots' == $greeny_hover ? 'hover_with_info' : '',
			'post_info'     => 'dots' == $greeny_hover
										? '<div class="post_info"><h5 class="post_title">'
											. ( ! empty( $greeny_post_link )
												? '<a href="' . esc_url( $greeny_post_link ) . '"' . ( ! empty( $target ) ? $target : '' ) . '>'
												: ''
												)
												. esc_html( get_the_title() ) 
											. ( ! empty( $greeny_post_link )
												? '</a>'
												: ''
												)
											. '</h5></div>'
										: '',
            'thumb_ratio'   => 'info' == $greeny_hover ?  '100:102' : '',
        ),
        'content-portfolio',
        $greeny_template_args
    ) );
	?>
</article></div><?php
// Need opening PHP-tag above, because <article> is a inline-block element (used as column)!