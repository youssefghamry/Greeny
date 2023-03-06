<?php
/**
 * The Sticky template to display the sticky posts
 *
 * Used for index/archive
 *
 * @package GREENY
 * @since GREENY 1.0
 */

$greeny_columns     = max( 1, min( 3, count( get_option( 'sticky_posts' ) ) ) );
$greeny_post_format = get_post_format();
$greeny_post_format = empty( $greeny_post_format ) ? 'standard' : str_replace( 'post-format-', '', $greeny_post_format );

?><div class="column-1_<?php echo esc_attr( $greeny_columns ); ?>"><article id="post-<?php the_ID(); ?>" 
	<?php
	post_class( 'post_item post_layout_sticky post_format_' . esc_attr( $greeny_post_format ) );
	greeny_add_blog_animation( $greeny_template_args );
	?>
>

	<?php
	if ( is_sticky() && is_home() && ! is_paged() ) {
		?>
		<span class="post_label label_sticky"></span>
		<?php
	}

	// Featured image
	greeny_show_post_featured(
		array(
			'thumb_size' => greeny_get_thumb_size( 1 == $greeny_columns ? 'big' : ( 2 == $greeny_columns ? 'med' : 'avatar' ) ),
		)
	);

	if ( ! in_array( $greeny_post_format, array( 'link', 'aside', 'status', 'quote' ) ) ) {
		?>
		<div class="post_header entry-header">
			<?php
			// Post title
			the_title( sprintf( '<h5 class="post_title entry-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h6>' );
			// Post meta
			greeny_show_post_meta( apply_filters( 'greeny_filter_post_meta_args', array(), 'sticky', $greeny_columns ) );
			?>
		</div><!-- .entry-header -->
		<?php
	}
	?>
</article></div><?php

// div.column-1_X is a inline-block and new lines and spaces after it are forbidden
