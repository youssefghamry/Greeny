<?php
/**
 * The default template to display the content
 *
 * Used for index/archive/search.
 *
 * @package GREENY
 * @since GREENY 1.0
 */

$greeny_template_args = get_query_var( 'greeny_template_args' );
$greeny_columns = 1;
if ( is_array( $greeny_template_args ) ) {
	$greeny_columns    = empty( $greeny_template_args['columns'] ) ? 1 : max( 1, $greeny_template_args['columns'] );
	$greeny_blog_style = array( $greeny_template_args['type'], $greeny_columns );
	if ( ! empty( $greeny_template_args['slider'] ) ) {
		?><div class="slider-slide swiper-slide">
		<?php
	} elseif ( $greeny_columns > 1 ) {
	    $greeny_columns_class = greeny_get_column_class( 1, $greeny_columns, ! empty( $greeny_template_args['columns_tablet']) ? $greeny_template_args['columns_tablet'] : '', ! empty($greeny_template_args['columns_mobile']) ? $greeny_template_args['columns_mobile'] : '' );
		?>
		<div class="<?php echo esc_attr( $greeny_columns_class ); ?>">
		<?php
	}
}
$greeny_expanded    = ! greeny_sidebar_present() && greeny_get_theme_option( 'expand_content' ) == 'expand';
$greeny_post_format = get_post_format();
$greeny_post_format = empty( $greeny_post_format ) ? 'standard' : str_replace( 'post-format-', '', $greeny_post_format );
?>
<article id="post-<?php the_ID(); ?>" data-post-id="<?php the_ID(); ?>"
	<?php
	post_class( 'post_item post_item_container post_layout_excerpt post_format_' . esc_attr( $greeny_post_format ) );
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
			'thumb_size' => ! empty( $greeny_template_args['thumb_size'] )
							? $greeny_template_args['thumb_size']
							: greeny_get_thumb_size( strpos( greeny_get_theme_option( 'body_style' ), 'full' ) !== false
								? 'full'
								: ( $greeny_expanded 
									? 'huge' 
									: 'big' 
									)
								),
		),
		'content-excerpt',
		$greeny_template_args
	) );

	// Title and post meta
	$greeny_show_title = get_the_title() != '';
	$greeny_show_meta  = count( $greeny_components ) > 0 && ! in_array( $greeny_hover, array( 'border', 'pull', 'slide', 'fade', 'info' ) );

	if ( $greeny_show_title ) {
		?>
		<div class="post_header entry-header">
			<?php
			// Post title
			if ( apply_filters( 'greeny_filter_show_blog_title', true, 'excerpt' ) ) {
				do_action( 'greeny_action_before_post_title' );
				if ( empty( $greeny_template_args['no_links'] ) ) {
					the_title( sprintf( '<h3 class="post_title entry-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h3>' );
				} else {
					the_title( '<h3 class="post_title entry-title">', '</h3>' );
				}
				do_action( 'greeny_action_after_post_title' );
			}
			?>
		</div><!-- .post_header -->
		<?php
	}

	// Post content
	if ( apply_filters( 'greeny_filter_show_blog_excerpt', empty( $greeny_template_args['hide_excerpt'] ) && greeny_get_theme_option( 'excerpt_length' ) > 0, 'excerpt' ) ) {
		?>
		<div class="post_content entry-content">
			<?php

			// Post meta
			if ( apply_filters( 'greeny_filter_show_blog_meta', $greeny_show_meta, $greeny_components, 'excerpt' ) ) {
				if ( count( $greeny_components ) > 0 ) {
					do_action( 'greeny_action_before_post_meta' );
					greeny_show_post_meta(
						apply_filters(
							'greeny_filter_post_meta_args', array(
								'components' => join( ',', $greeny_components ),
								'seo'        => false,
								'echo'       => true,
							), 'excerpt', 1
						)
					);
					do_action( 'greeny_action_after_post_meta' );
				}
			}

			if ( greeny_get_theme_option( 'blog_content' ) == 'fullpost' ) {
				// Post content area
				?>
				<div class="post_content_inner">
					<?php
					do_action( 'greeny_action_before_full_post_content' );
					the_content( '' );
					do_action( 'greeny_action_after_full_post_content' );
					?>
				</div>
				<?php
				// Inner pages
				wp_link_pages(
					array(
						'before'      => '<div class="page_links"><span class="page_links_title">' . esc_html__( 'Pages:', 'greeny' ) . '</span>',
						'after'       => '</div>',
						'link_before' => '<span>',
						'link_after'  => '</span>',
						'pagelink'    => '<span class="screen-reader-text">' . esc_html__( 'Page', 'greeny' ) . ' </span>%',
						'separator'   => '<span class="screen-reader-text">, </span>',
					)
				);
			} else {
				// Post content area
				greeny_show_post_content( $greeny_template_args, '<div class="post_content_inner">', '</div>' );
			}

			// More button
			if ( apply_filters( 'greeny_filter_show_blog_readmore',  ! isset( $greeny_template_args['more_button'] ) || ! empty( $greeny_template_args['more_button'] ), 'excerpt' ) ) {
				if ( empty( $greeny_template_args['no_links'] ) ) {
					do_action( 'greeny_action_before_post_readmore' );
					if ( greeny_get_theme_option( 'blog_content' ) != 'fullpost' ) {
						greeny_show_post_more_link( $greeny_template_args, '<p>', '</p>' );
					} else {
						greeny_show_post_comments_link( $greeny_template_args, '<p>', '</p>' );
					}
					do_action( 'greeny_action_after_post_readmore' );
				}
			}

			?>
		</div><!-- .entry-content -->
		<?php
	}
	?>
</article>
<?php

if ( is_array( $greeny_template_args ) ) {
	if ( ! empty( $greeny_template_args['slider'] ) || $greeny_columns > 1 ) {
		?>
		</div>
		<?php
	}
}
