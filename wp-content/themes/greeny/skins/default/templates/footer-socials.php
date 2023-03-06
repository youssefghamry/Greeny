<?php
/**
 * The template to display the socials in the footer
 *
 * @package GREENY
 * @since GREENY 1.0.10
 */


// Socials
if ( greeny_is_on( greeny_get_theme_option( 'socials_in_footer' ) ) ) {
	$greeny_output = greeny_get_socials_links();
	if ( '' != $greeny_output ) {
		?>
		<div class="footer_socials_wrap socials_wrap">
			<div class="footer_socials_inner">
				<?php greeny_show_layout( $greeny_output ); ?>
			</div>
		</div>
		<?php
	}
}
