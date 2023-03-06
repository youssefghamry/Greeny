<?php
/**
 * The template to display custom header from the ThemeREX Addons Layouts
 *
 * @package GREENY
 * @since GREENY 1.0.06
 */

$greeny_header_css   = '';
$greeny_header_image = get_header_image();
$greeny_header_video = greeny_get_header_video();
if ( ! empty( $greeny_header_image ) && greeny_trx_addons_featured_image_override( is_singular() || greeny_storage_isset( 'blog_archive' ) || is_category() ) ) {
	$greeny_header_image = greeny_get_current_mode_image( $greeny_header_image );
}

$greeny_header_id = greeny_get_custom_header_id();
$greeny_header_meta = get_post_meta( $greeny_header_id, 'trx_addons_options', true );
if ( ! empty( $greeny_header_meta['margin'] ) ) {
	greeny_add_inline_css( sprintf( '.page_content_wrap{padding-top:%s}', esc_attr( greeny_prepare_css_value( $greeny_header_meta['margin'] ) ) ) );
}

?><header class="top_panel top_panel_custom top_panel_custom_<?php echo esc_attr( $greeny_header_id ); ?> top_panel_custom_<?php echo esc_attr( sanitize_title( get_the_title( $greeny_header_id ) ) ); ?>
				<?php
				echo ! empty( $greeny_header_image ) || ! empty( $greeny_header_video )
					? ' with_bg_image'
					: ' without_bg_image';
				if ( '' != $greeny_header_video ) {
					echo ' with_bg_video';
				}
				if ( '' != $greeny_header_image ) {
					echo ' ' . esc_attr( greeny_add_inline_css_class( 'background-image: url(' . esc_url( $greeny_header_image ) . ');' ) );
				}
				if ( is_single() && has_post_thumbnail() ) {
					echo ' with_featured_image';
				}
				if ( greeny_is_on( greeny_get_theme_option( 'header_fullheight' ) ) ) {
					echo ' header_fullheight greeny-full-height';
				}
				$greeny_header_scheme = greeny_get_theme_option( 'header_scheme' );
				if ( ! empty( $greeny_header_scheme ) && ! greeny_is_inherit( $greeny_header_scheme  ) ) {
					echo ' scheme_' . esc_attr( $greeny_header_scheme );
				}
				?>
">
	<?php

	// Background video
	if ( ! empty( $greeny_header_video ) ) {
		get_template_part( apply_filters( 'greeny_filter_get_template_part', 'templates/header-video' ) );
	}

	// Custom header's layout
	do_action( 'greeny_action_show_layout', $greeny_header_id );

	// Header widgets area
	get_template_part( apply_filters( 'greeny_filter_get_template_part', 'templates/header-widgets' ) );

	?>
</header>
