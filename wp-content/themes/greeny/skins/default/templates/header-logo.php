<?php
/**
 * The template to display the logo or the site name and the slogan in the Header
 *
 * @package GREENY
 * @since GREENY 1.0
 */

$greeny_args = get_query_var( 'greeny_logo_args' );

// Site logo
$greeny_logo_type   = isset( $greeny_args['type'] ) ? $greeny_args['type'] : '';
$greeny_logo_image  = greeny_get_logo_image( $greeny_logo_type );
$greeny_logo_text   = greeny_is_on( greeny_get_theme_option( 'logo_text' ) ) ? get_bloginfo( 'name' ) : '';
$greeny_logo_slogan = get_bloginfo( 'description', 'display' );
if ( ! empty( $greeny_logo_image['logo'] ) || ! empty( $greeny_logo_text ) ) {
	?><a class="sc_layouts_logo" href="<?php echo esc_url( home_url( '/' ) ); ?>">
		<?php
		if ( ! empty( $greeny_logo_image['logo'] ) ) {
			if ( empty( $greeny_logo_type ) && function_exists( 'the_custom_logo' ) && is_numeric($greeny_logo_image['logo']) && (int) $greeny_logo_image['logo'] > 0 ) {
				the_custom_logo();
			} else {
				$greeny_attr = greeny_getimagesize( $greeny_logo_image['logo'] );
				echo '<img src="' . esc_url( $greeny_logo_image['logo'] ) . '"'
						. ( ! empty( $greeny_logo_image['logo_retina'] ) ? ' srcset="' . esc_url( $greeny_logo_image['logo_retina'] ) . ' 2x"' : '' )
						. ' alt="' . esc_attr( $greeny_logo_text ) . '"'
						. ( ! empty( $greeny_attr[3] ) ? ' ' . wp_kses_data( $greeny_attr[3] ) : '' )
						. '>';
			}
		} else {
			greeny_show_layout( greeny_prepare_macros( $greeny_logo_text ), '<span class="logo_text">', '</span>' );
			greeny_show_layout( greeny_prepare_macros( $greeny_logo_slogan ), '<span class="logo_slogan">', '</span>' );
		}
		?>
	</a>
	<?php
}
