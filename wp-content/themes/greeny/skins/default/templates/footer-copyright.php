<?php
/**
 * The template to display the copyright info in the footer
 *
 * @package GREENY
 * @since GREENY 1.0.10
 */

// Copyright area
?> 
<div class="footer_copyright_wrap
<?php
$greeny_copyright_scheme = greeny_get_theme_option( 'copyright_scheme' );
if ( ! empty( $greeny_copyright_scheme ) && ! greeny_is_inherit( $greeny_copyright_scheme  ) ) {
	echo ' scheme_' . esc_attr( $greeny_copyright_scheme );
}
?>
				">
	<div class="footer_copyright_inner">
		<div class="content_wrap">
			<div class="copyright_text">
			<?php
				$greeny_copyright = greeny_get_theme_option( 'copyright' );
			if ( ! empty( $greeny_copyright ) ) {
				// Replace {{Y}} or {Y} with the current year
				$greeny_copyright = str_replace( array( '{{Y}}', '{Y}' ), date( 'Y' ), $greeny_copyright );
				// Replace {{...}} and ((...)) on the <i>...</i> and <b>...</b>
				$greeny_copyright = greeny_prepare_macros( $greeny_copyright );
				// Display copyright
				echo wp_kses( nl2br( $greeny_copyright ), 'greeny_kses_content' );
			}
			?>
			</div>
		</div>
	</div>
</div>
