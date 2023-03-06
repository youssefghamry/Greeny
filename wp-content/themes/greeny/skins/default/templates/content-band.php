<?php
/**
 * 'Band' template to display the content
 *
 * Used for index/archive/search.
 *
 * @package GREENY
 * @since GREENY 1.71.0
 */

$greeny_template_args = get_query_var( 'greeny_template_args' );

$greeny_columns       = 1;

$greeny_expanded      = ! greeny_sidebar_present() && greeny_get_theme_option( 'expand_content' ) == 'expand';

$greeny_post_format   = get_post_format();
$greeny_post_format   = empty( $greeny_post_format ) ? 'standard' : str_replace( 'post-format-', '', $greeny_post_format );

if ( is_array( $greeny_template_args ) ) {
	$greeny_columns    = empty( $greeny_template_args['columns'] ) ? 1 : max( 1, $greeny_template_args['columns'] );
	$greeny_blog_style = array( $greeny_template_args['type'], $greeny_columns );
	if ( ! empty( $greeny_template_args['slider'] ) ) {
		?><div class="slider-slide swiper-slide">
		<?php
	} elseif ( $greeny_columns > 1 ) {
	    $greeny_columns_class = greeny_get_column_class( 1, $greeny_columns, ! empty( $greeny_template_args['columns_tablet']) ? $greeny_template_args['columns_tablet'] : '', ! empty($greeny_template_args['columns_mobile']) ? $greeny_template_args['columns_mobile'] : '' );
				?><div class="<?php echo esc_attr( $greeny_columns_class ); ?>"><?php
	}
}
?>
<article id="post-<?php the_ID(); ?>" data-post-id="<?php the_ID(); ?>"
	<?php
	post_class( 'post_item post_item_container post_layout_band post_format_' . esc_attr( $greeny_post_format ) );
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
								: array_map( 'trim', explode( ',', $greeny_template_args['meta_parts'] ) )
								)
							: greeny_array_get_keys_by_value( greeny_get_theme_option( 'meta_parts' ) );
	greeny_show_post_featured( apply_filters( 'greeny_filter_args_featured',
		array(
			'no_links'   => ! empty( $greeny_template_args['no_links'] ),
			'hover'      => $greeny_hover,
			'meta_parts' => $greeny_components,
			'thumb_bg'   => true,
			'thumb_ratio'   => '1:1',
			'thumb_size' => ! empty( $greeny_template_args['thumb_size'] )
								? $greeny_template_args['thumb_size']
								: greeny_get_thumb_size( 
								in_array( $greeny_post_format, array( 'gallery', 'audio', 'video' ) )
									? ( strpos( greeny_get_theme_option( 'body_style' ), 'full' ) !== false
										? 'full'
										: ( $greeny_expanded 
											? 'big' 
											: 'medium-square'
											)
										)
									: 'masonry-big'
								)
		),
		'content-band',
		$greeny_template_args
	) );

	?><div class="post_content_wrap"><?php

		// Title and post meta
		$greeny_show_title = get_the_title() != '';
		$greeny_show_meta  = count( $greeny_components ) > 0 && ! in_array( $greeny_hover, array( 'border', 'pull', 'slide', 'fade', 'info' ) );
		if ( $greeny_show_title ) {
			?>
			<div class="post_header entry-header">
				<?php
				// Categories
				if ( apply_filters( 'greeny_filter_show_blog_categories', $greeny_show_meta && in_array( 'categories', $greeny_components ), array( 'categories' ), 'band' ) ) {
					do_action( 'greeny_action_before_post_category' );
					?>
					<div class="post_category">
						<?php
						greeny_show_post_meta( apply_filters(
															'greeny_filter_post_meta_args',
															array(
																'components' => 'categories',
																'seo'        => false,
																'echo'       => true,
																'cat_sep'    => false,
																),
															'hover_' . $greeny_hover, 1
															)
											);
						?>
					</div>
					<?php
					$greeny_components = greeny_array_delete_by_value( $greeny_components, 'categories' );
					do_action( 'greeny_action_after_post_category' );
				}
				// Post title
				if ( apply_filters( 'greeny_filter_show_blog_title', true, 'band' ) ) {
					do_action( 'greeny_action_before_post_title' );
					if ( empty( $greeny_template_args['no_links'] ) ) {
						the_title( sprintf( '<h4 class="post_title entry-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h4>' );
					} else {
						the_title( '<h4 class="post_title entry-title">', '</h4>' );
					}
					do_action( 'greeny_action_after_post_title' );
				}
				?>
			</div><!-- .post_header -->
			<?php
		}

		// Post content
		if ( ! isset( $greeny_template_args['excerpt_length'] ) && ! in_array( $greeny_post_format, array( 'gallery', 'audio', 'video' ) ) ) {
			$greeny_template_args['excerpt_length'] = 13;
		}
		if ( apply_filters( 'greeny_filter_show_blog_excerpt', empty( $greeny_template_args['hide_excerpt'] ) && greeny_get_theme_option( 'excerpt_length' ) > 0, 'band' ) ) {
			?>
			<div class="post_content entry-content">
				<?php
				// Post content area
				greeny_show_post_content( $greeny_template_args, '<div class="post_content_inner">', '</div>' );
				?>
			</div><!-- .entry-content -->
			<?php
		}
		// Post meta
		if ( apply_filters( 'greeny_filter_show_blog_meta', $greeny_show_meta, $greeny_components, 'band' ) ) {
			if ( count( $greeny_components ) > 0 ) {
				do_action( 'greeny_action_before_post_meta' );
				greeny_show_post_meta(
					apply_filters(
						'greeny_filter_post_meta_args', array(
							'components' => join( ',', $greeny_components ),
							'seo'        => false,
							'echo'       => true,
						), 'band', 1
					)
				);
				do_action( 'greeny_action_after_post_meta' );
			}
		}
		// More button
		if ( apply_filters( 'greeny_filter_show_blog_readmore', ! $greeny_show_title || ! empty( $greeny_template_args['more_button'] ), 'band' ) ) {
			if ( empty( $greeny_template_args['no_links'] ) ) {
				do_action( 'greeny_action_before_post_readmore' );
				greeny_show_post_more_link( $greeny_template_args, '<div class="more-wrap">', '</div>' );
				do_action( 'greeny_action_after_post_readmore' );
			}
		}
		?>
	</div>
</article>
<?php

if ( is_array( $greeny_template_args ) ) {
	if ( ! empty( $greeny_template_args['slider'] ) || $greeny_columns > 1 ) {
		?>
		</div>
		<?php
	}
}
