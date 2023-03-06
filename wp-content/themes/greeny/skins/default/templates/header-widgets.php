<?php
/**
 * The template to display the widgets area in the header
 *
 * @package GREENY
 * @since GREENY 1.0
 */

// Header sidebar
$greeny_header_name    = greeny_get_theme_option( 'header_widgets' );
$greeny_header_present = ! greeny_is_off( $greeny_header_name ) && is_active_sidebar( $greeny_header_name );
if ( $greeny_header_present ) {
	greeny_storage_set( 'current_sidebar', 'header' );
	$greeny_header_wide = greeny_get_theme_option( 'header_wide' );
	ob_start();
	if ( is_active_sidebar( $greeny_header_name ) ) {
		dynamic_sidebar( $greeny_header_name );
	}
	$greeny_widgets_output = ob_get_contents();
	ob_end_clean();
	if ( ! empty( $greeny_widgets_output ) ) {
		$greeny_widgets_output = preg_replace( "/<\/aside>[\r\n\s]*<aside/", '</aside><aside', $greeny_widgets_output );
		$greeny_need_columns   = strpos( $greeny_widgets_output, 'columns_wrap' ) === false;
		if ( $greeny_need_columns ) {
			$greeny_columns = max( 0, (int) greeny_get_theme_option( 'header_columns' ) );
			if ( 0 == $greeny_columns ) {
				$greeny_columns = min( 6, max( 1, greeny_tags_count( $greeny_widgets_output, 'aside' ) ) );
			}
			if ( $greeny_columns > 1 ) {
				$greeny_widgets_output = preg_replace( '/<aside([^>]*)class="widget/', '<aside$1class="column-1_' . esc_attr( $greeny_columns ) . ' widget', $greeny_widgets_output );
			} else {
				$greeny_need_columns = false;
			}
		}
		?>
		<div class="header_widgets_wrap widget_area<?php echo ! empty( $greeny_header_wide ) ? ' header_fullwidth' : ' header_boxed'; ?>">
			<?php do_action( 'greeny_action_before_sidebar_wrap', 'header' ); ?>
			<div class="header_widgets_inner widget_area_inner">
				<?php
				if ( ! $greeny_header_wide ) {
					?>
					<div class="content_wrap">
					<?php
				}
				if ( $greeny_need_columns ) {
					?>
					<div class="columns_wrap">
					<?php
				}
				do_action( 'greeny_action_before_sidebar', 'header' );
				greeny_show_layout( $greeny_widgets_output );
				do_action( 'greeny_action_after_sidebar', 'header' );
				if ( $greeny_need_columns ) {
					?>
					</div>	<!-- /.columns_wrap -->
					<?php
				}
				if ( ! $greeny_header_wide ) {
					?>
					</div>	<!-- /.content_wrap -->
					<?php
				}
				?>
			</div>	<!-- /.header_widgets_inner -->
			<?php do_action( 'greeny_action_after_sidebar_wrap', 'header' ); ?>
		</div>	<!-- /.header_widgets_wrap -->
		<?php
	}
}
