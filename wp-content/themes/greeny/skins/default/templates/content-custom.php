<?php
/**
 * The custom template to display the content
 *
 * Used for index/archive/search.
 *
 * @package GREENY
 * @since GREENY 1.0.50
 */

$greeny_template_args = get_query_var( 'greeny_template_args' );
if ( is_array( $greeny_template_args ) ) {
	$greeny_columns    = empty( $greeny_template_args['columns'] ) ? 2 : max( 1, $greeny_template_args['columns'] );
	$greeny_blog_style = array( $greeny_template_args['type'], $greeny_columns );
} else {
	$greeny_blog_style = explode( '_', greeny_get_theme_option( 'blog_style' ) );
	$greeny_columns    = empty( $greeny_blog_style[1] ) ? 2 : max( 1, $greeny_blog_style[1] );
}
$greeny_blog_id       = greeny_get_custom_blog_id( join( '_', $greeny_blog_style ) );
$greeny_blog_style[0] = str_replace( 'blog-custom-', '', $greeny_blog_style[0] );
$greeny_expanded      = ! greeny_sidebar_present() && greeny_get_theme_option( 'expand_content' ) == 'expand';
$greeny_components    = ! empty( $greeny_template_args['meta_parts'] )
							? ( is_array( $greeny_template_args['meta_parts'] )
								? join( ',', $greeny_template_args['meta_parts'] )
								: $greeny_template_args['meta_parts']
								)
							: greeny_array_get_keys_by_value( greeny_get_theme_option( 'meta_parts' ) );
$greeny_post_format   = get_post_format();
$greeny_post_format   = empty( $greeny_post_format ) ? 'standard' : str_replace( 'post-format-', '', $greeny_post_format );

$greeny_blog_meta     = greeny_get_custom_layout_meta( $greeny_blog_id );
$greeny_custom_style  = ! empty( $greeny_blog_meta['scripts_required'] ) ? $greeny_blog_meta['scripts_required'] : 'none';

if ( ! empty( $greeny_template_args['slider'] ) || $greeny_columns > 1 || ! greeny_is_off( $greeny_custom_style ) ) {
	?><div class="
		<?php
		if ( ! empty( $greeny_template_args['slider'] ) ) {
			echo 'slider-slide swiper-slide';
		} else {
			echo esc_attr( ( greeny_is_off( $greeny_custom_style ) ? 'column' : sprintf( '%1$s_item %1$s_item', $greeny_custom_style ) ) . "-1_{$greeny_columns}" );
		}
		?>
	">
	<?php
}
?>
<article id="post-<?php the_ID(); ?>" data-post-id="<?php the_ID(); ?>"
	<?php
	post_class(
			'post_item post_item_container post_format_' . esc_attr( $greeny_post_format )
					. ' post_layout_custom post_layout_custom_' . esc_attr( $greeny_columns )
					. ' post_layout_' . esc_attr( $greeny_blog_style[0] )
					. ' post_layout_' . esc_attr( $greeny_blog_style[0] ) . '_' . esc_attr( $greeny_columns )
					. ( ! greeny_is_off( $greeny_custom_style )
						? ' post_layout_' . esc_attr( $greeny_custom_style )
							. ' post_layout_' . esc_attr( $greeny_custom_style ) . '_' . esc_attr( $greeny_columns )
						: ''
						)
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
	// Custom layout
	do_action( 'greeny_action_show_layout', $greeny_blog_id, get_the_ID() );
	?>
</article><?php
if ( ! empty( $greeny_template_args['slider'] ) || $greeny_columns > 1 || ! greeny_is_off( $greeny_custom_style ) ) {
	?></div><?php
	// Need opening PHP-tag above just after </div>, because <div> is a inline-block element (used as column)!
}
