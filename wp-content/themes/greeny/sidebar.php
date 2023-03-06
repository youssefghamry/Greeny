<?php
/**
 * The Sidebar containing the main widget areas.
 *
 * @package GREENY
 * @since GREENY 1.0
 */

if ( greeny_sidebar_present() ) {
	
	$greeny_sidebar_type = greeny_get_theme_option( 'sidebar_type' );
	if ( 'custom' == $greeny_sidebar_type && ! greeny_is_layouts_available() ) {
		$greeny_sidebar_type = 'default';
	}
	
	// Catch output to the buffer
	ob_start();
	if ( 'default' == $greeny_sidebar_type ) {
		// Default sidebar with widgets
		$greeny_sidebar_name = greeny_get_theme_option( 'sidebar_widgets' );
		greeny_storage_set( 'current_sidebar', 'sidebar' );
		if ( is_active_sidebar( $greeny_sidebar_name ) ) {
			dynamic_sidebar( $greeny_sidebar_name );
		}
	} else {
		// Custom sidebar from Layouts Builder
		$greeny_sidebar_id = greeny_get_custom_sidebar_id();
		do_action( 'greeny_action_show_layout', $greeny_sidebar_id );
	}
	$greeny_out = trim( ob_get_contents() );
	ob_end_clean();
	
	// If any html is present - display it
	if ( ! empty( $greeny_out ) ) {
		$greeny_sidebar_position    = greeny_get_theme_option( 'sidebar_position' );
		$greeny_sidebar_position_ss = greeny_get_theme_option( 'sidebar_position_ss' );
		?>
		<div class="sidebar widget_area
			<?php
			echo ' ' . esc_attr( $greeny_sidebar_position );
			echo ' sidebar_' . esc_attr( $greeny_sidebar_position_ss );
			echo ' sidebar_' . esc_attr( $greeny_sidebar_type );

			if ( 'float' == $greeny_sidebar_position_ss ) {
				echo ' sidebar_float';
			}
			$greeny_sidebar_scheme = greeny_get_theme_option( 'sidebar_scheme' );
			if ( ! empty( $greeny_sidebar_scheme ) && ! greeny_is_inherit( $greeny_sidebar_scheme ) ) {
				echo ' scheme_' . esc_attr( $greeny_sidebar_scheme );
			}
			?>
		" role="complementary">
			<?php

			// Skip link anchor to fast access to the sidebar from keyboard
			?>
			<a id="sidebar_skip_link_anchor" class="greeny_skip_link_anchor" href="#"></a>
			<?php

			do_action( 'greeny_action_before_sidebar_wrap', 'sidebar' );

			// Button to show/hide sidebar on mobile
			if ( in_array( $greeny_sidebar_position_ss, array( 'above', 'float' ) ) ) {
				$greeny_title = apply_filters( 'greeny_filter_sidebar_control_title', 'float' == $greeny_sidebar_position_ss ? esc_html__( 'Show Sidebar', 'greeny' ) : '' );
				$greeny_text  = apply_filters( 'greeny_filter_sidebar_control_text', 'above' == $greeny_sidebar_position_ss ? esc_html__( 'Show Sidebar', 'greeny' ) : '' );
				?>
				<a href="#" class="sidebar_control" title="<?php echo esc_attr( $greeny_title ); ?>"><?php echo esc_html( $greeny_text ); ?></a>
				<?php
			}
			?>
			<div class="sidebar_inner">
				<?php
				do_action( 'greeny_action_before_sidebar', 'sidebar' );
				greeny_show_layout( preg_replace( "/<\/aside>[\r\n\s]*<aside/", '</aside><aside', $greeny_out ) );
				do_action( 'greeny_action_after_sidebar', 'sidebar' );
				?>
			</div>
			<?php

			do_action( 'greeny_action_after_sidebar_wrap', 'sidebar' );

			?>
		</div>
		<div class="clearfix"></div>
		<?php
	}
}
