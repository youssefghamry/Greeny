<?php
/**
 * The template to display the site logo in the footer
 *
 * @package GREENY
 * @since GREENY 1.0.10
 */

// Logo
if ( greeny_is_on( greeny_get_theme_option( 'logo_in_footer' ) ) ) {
	$greeny_logo_image = greeny_get_logo_image( 'footer' );
	$greeny_logo_text  = get_bloginfo( 'name' );
	if ( ! empty( $greeny_logo_image['logo'] ) || ! empty( $greeny_logo_text ) ) {
		?>
		<div class="footer_logo_wrap">
			<div class="footer_logo_inner">
				<?php
				if ( ! empty( $greeny_logo_image['logo'] ) ) {
					$greeny_attr = greeny_getimagesize( $greeny_logo_image['logo'] );
					echo '<a href="' . esc_url( home_url( '/' ) ) . '">'
							. '<img src="' . esc_url( $greeny_logo_image['logo'] ) . '"'
								. ( ! empty( $greeny_logo_image['logo_retina'] ) ? ' srcset="' . esc_url( $greeny_logo_image['logo_retina'] ) . ' 2x"' : '' )
								. ' class="logo_footer_image"'
								. ' alt="' . esc_attr__( 'Site logo', 'greeny' ) . '"'
								. ( ! empty( $greeny_attr[3] ) ? ' ' . wp_kses_data( $greeny_attr[3] ) : '' )
							. '>'
						. '</a>';
				} elseif ( ! empty( $greeny_logo_text ) ) {
					echo '<h1 class="logo_footer_text">'
							. '<a href="' . esc_url( home_url( '/' ) ) . '">'
								. esc_html( $greeny_logo_text )
							. '</a>'
						. '</h1>';
				}
				?>
			</div>
		</div>
		<?php
	}
}
