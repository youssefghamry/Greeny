<?php
/**
 * The template to display default site footer
 *
 * @package GREENY
 * @since GREENY 1.0.10
 */

$greeny_footer_id = greeny_get_custom_footer_id();
$greeny_footer_meta = get_post_meta( $greeny_footer_id, 'trx_addons_options', true );
if ( ! empty( $greeny_footer_meta['margin'] ) ) {
	greeny_add_inline_css( sprintf( '.page_content_wrap{padding-bottom:%s}', esc_attr( greeny_prepare_css_value( $greeny_footer_meta['margin'] ) ) ) );
}
?>
<footer class="footer_wrap footer_custom footer_custom_<?php echo esc_attr( $greeny_footer_id ); ?> footer_custom_<?php echo esc_attr( sanitize_title( get_the_title( $greeny_footer_id ) ) ); ?>
						<?php
						$greeny_footer_scheme = greeny_get_theme_option( 'footer_scheme' );
						if ( ! empty( $greeny_footer_scheme ) && ! greeny_is_inherit( $greeny_footer_scheme  ) ) {
							echo ' scheme_' . esc_attr( $greeny_footer_scheme );
						}
						?>
						">
	<?php
	// Custom footer's layout
	do_action( 'greeny_action_show_layout', $greeny_footer_id );
	?>
</footer><!-- /.footer_wrap -->
