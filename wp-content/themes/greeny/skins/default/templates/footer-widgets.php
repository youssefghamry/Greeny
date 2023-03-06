<?php
/**
 * The template to display the widgets area in the footer
 *
 * @package GREENY
 * @since GREENY 1.0.10
 */

// Footer sidebar
$greeny_footer_name    = greeny_get_theme_option( 'footer_widgets' );
$greeny_footer_present = ! greeny_is_off( $greeny_footer_name ) && is_active_sidebar( $greeny_footer_name );
if ( $greeny_footer_present ) {
	greeny_storage_set( 'current_sidebar', 'footer' );
	$greeny_footer_wide = greeny_get_theme_option( 'footer_wide' );
	ob_start();
	if ( is_active_sidebar( $greeny_footer_name ) ) {
		dynamic_sidebar( $greeny_footer_name );
	}
	$greeny_out = trim( ob_get_contents() );
	ob_end_clean();
	if ( ! empty( $greeny_out ) ) {
		$greeny_out          = preg_replace( "/<\\/aside>[\r\n\s]*<aside/", '</aside><aside', $greeny_out );
		$greeny_need_columns = true;   //or check: strpos($greeny_out, 'columns_wrap')===false;
		if ( $greeny_need_columns ) {
			$greeny_columns = max( 0, (int) greeny_get_theme_option( 'footer_columns' ) );			
			if ( 0 == $greeny_columns ) {
				$greeny_columns = min( 4, max( 1, greeny_tags_count( $greeny_out, 'aside' ) ) );
			}
			if ( $greeny_columns > 1 ) {
				$greeny_out = preg_replace( '/<aside([^>]*)class="widget/', '<aside$1class="column-1_' . esc_attr( $greeny_columns ) . ' widget', $greeny_out );
			} else {
				$greeny_need_columns = false;
			}
		}
		?>
		<div class="footer_widgets_wrap widget_area<?php echo ! empty( $greeny_footer_wide ) ? ' footer_fullwidth' : ''; ?> sc_layouts_row sc_layouts_row_type_normal">
			<?php do_action( 'greeny_action_before_sidebar_wrap', 'footer' ); ?>
			<div class="footer_widgets_inner widget_area_inner">
				<?php
				if ( ! $greeny_footer_wide ) {
					?>
					<div class="content_wrap">
					<?php
				}
				if ( $greeny_need_columns ) {
					?>
					<div class="columns_wrap">
					<?php
				}
				do_action( 'greeny_action_before_sidebar', 'footer' );
				greeny_show_layout( $greeny_out );
				do_action( 'greeny_action_after_sidebar', 'footer' );
				if ( $greeny_need_columns ) {
					?>
					</div><!-- /.columns_wrap -->
					<?php
				}
				if ( ! $greeny_footer_wide ) {
					?>
					</div><!-- /.content_wrap -->
					<?php
				}
				?>
			</div><!-- /.footer_widgets_inner -->
			<?php do_action( 'greeny_action_after_sidebar_wrap', 'footer' ); ?>
		</div><!-- /.footer_widgets_wrap -->
		<?php
	}
}
