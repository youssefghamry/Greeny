<?php
/**
 * The template to display default site footer
 *
 * @package GREENY
 * @since GREENY 1.0.10
 */

?>
<footer class="footer_wrap footer_default
<?php
$greeny_footer_scheme = greeny_get_theme_option( 'footer_scheme' );
if ( ! empty( $greeny_footer_scheme ) && ! greeny_is_inherit( $greeny_footer_scheme  ) ) {
	echo ' scheme_' . esc_attr( $greeny_footer_scheme );
}
?>
				">
	<?php

	// Footer widgets area
	get_template_part( apply_filters( 'greeny_filter_get_template_part', 'templates/footer-widgets' ) );

	// Logo
	get_template_part( apply_filters( 'greeny_filter_get_template_part', 'templates/footer-logo' ) );

	// Socials
	get_template_part( apply_filters( 'greeny_filter_get_template_part', 'templates/footer-socials' ) );

	// Copyright area
	get_template_part( apply_filters( 'greeny_filter_get_template_part', 'templates/footer-copyright' ) );

	?>
</footer><!-- /.footer_wrap -->
