<?php
/**
 * The template to display the background video in the header
 *
 * @package GREENY
 * @since GREENY 1.0.14
 */
$greeny_header_video = greeny_get_header_video();
$greeny_embed_video  = '';
if ( ! empty( $greeny_header_video ) && ! greeny_is_from_uploads( $greeny_header_video ) ) {
	if ( greeny_is_youtube_url( $greeny_header_video ) && preg_match( '/[=\/]([^=\/]*)$/', $greeny_header_video, $matches ) && ! empty( $matches[1] ) ) {
		?><div id="background_video" data-youtube-code="<?php echo esc_attr( $matches[1] ); ?>"></div>
		<?php
	} else {
		?>
		<div id="background_video"><?php greeny_show_layout( greeny_get_embed_video( $greeny_header_video ) ); ?></div>
		<?php
	}
}
