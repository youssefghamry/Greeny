<?php
/**
 * The template 'Style 2' to displaying related posts
 *
 * @package GREENY
 * @since GREENY 1.0
 */

$greeny_link        = get_permalink();
$greeny_post_format = get_post_format();
$greeny_post_format = empty( $greeny_post_format ) ? 'standard' : str_replace( 'post-format-', '', $greeny_post_format );
?><div id="post-<?php the_ID(); ?>" <?php post_class( 'related_item post_format_' . esc_attr( $greeny_post_format ) ); ?> data-post-id="<?php the_ID(); ?>">
	<?php
	greeny_show_post_featured(
		array(
			'thumb_ratio'   => '300:223',
			'thumb_size'    => apply_filters( 'greeny_filter_related_thumb_size', greeny_get_thumb_size( (int) greeny_get_theme_option( 'related_posts' ) == 1 ? 'huge' : 'square' ) ),
		)
	);
	?>
	<div class="post_header entry-header">
		<?php
		if ( in_array( get_post_type(), array( 'post', 'attachment' ) ) ) {

			greeny_show_post_meta(
				array(
					'components' => 'categories',
					'class'      => 'post_meta_categories',
				)
			);

		}
		?>
		<h6 class="post_title entry-title"><a href="<?php echo esc_url( $greeny_link ); ?>"><?php
			if ( '' == get_the_title() ) {
				esc_html_e( '- No title -', 'greeny' );
			} else {
				the_title();
			}
		?></a></h6>
	</div>
</div>
