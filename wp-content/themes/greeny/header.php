<?php
/**
 * The Header: Logo and main menu
 *
 * @package GREENY
 * @since GREENY 1.0
 */
?><!DOCTYPE html>
<html <?php language_attributes(); ?> class="no-js<?php
	// Class scheme_xxx need in the <html> as context for the <body>!
	echo ' scheme_' . esc_attr( greeny_get_theme_option( 'color_scheme' ) );
?>">

<head>
	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>

	<?php
	if ( function_exists( 'wp_body_open' ) ) {
		wp_body_open();
	} else {
		do_action( 'wp_body_open' );
	}
	do_action( 'greeny_action_before_body' );
	?>

	<div class="<?php echo esc_attr( apply_filters( 'greeny_filter_body_wrap_class', 'body_wrap' ) ); ?>" <?php do_action('greeny_action_body_wrap_attributes'); ?>>

		<?php do_action( 'greeny_action_before_page_wrap' ); ?>

		<div class="<?php echo esc_attr( apply_filters( 'greeny_filter_page_wrap_class', 'page_wrap' ) ); ?>" <?php do_action('greeny_action_page_wrap_attributes'); ?>>

			<?php do_action( 'greeny_action_page_wrap_start' ); ?>

			<?php
			$greeny_full_post_loading = ( greeny_is_singular( 'post' ) || greeny_is_singular( 'attachment' ) ) && greeny_get_value_gp( 'action' ) == 'full_post_loading';
			$greeny_prev_post_loading = ( greeny_is_singular( 'post' ) || greeny_is_singular( 'attachment' ) ) && greeny_get_value_gp( 'action' ) == 'prev_post_loading';

			// Don't display the header elements while actions 'full_post_loading' and 'prev_post_loading'
			if ( ! $greeny_full_post_loading && ! $greeny_prev_post_loading ) {

				// Short links to fast access to the content, sidebar and footer from the keyboard
				?>
				<a class="greeny_skip_link skip_to_content_link" href="#content_skip_link_anchor" tabindex="1"><?php esc_html_e( "Skip to content", 'greeny' ); ?></a>
				<?php if ( greeny_sidebar_present() ) { ?>
				<a class="greeny_skip_link skip_to_sidebar_link" href="#sidebar_skip_link_anchor" tabindex="1"><?php esc_html_e( "Skip to sidebar", 'greeny' ); ?></a>
				<?php } ?>
				<a class="greeny_skip_link skip_to_footer_link" href="#footer_skip_link_anchor" tabindex="1"><?php esc_html_e( "Skip to footer", 'greeny' ); ?></a>

				<?php
				do_action( 'greeny_action_before_header' );

				// Header
				$greeny_header_type = greeny_get_theme_option( 'header_type' );
				if ( 'custom' == $greeny_header_type && ! greeny_is_layouts_available() ) {
					$greeny_header_type = 'default';
				}
				get_template_part( apply_filters( 'greeny_filter_get_template_part', "templates/header-" . sanitize_file_name( $greeny_header_type ) ) );

				// Side menu
				if ( in_array( greeny_get_theme_option( 'menu_side' ), array( 'left', 'right' ) ) ) {
					get_template_part( apply_filters( 'greeny_filter_get_template_part', 'templates/header-navi-side' ) );
				}

				// Mobile menu
				get_template_part( apply_filters( 'greeny_filter_get_template_part', 'templates/header-navi-mobile' ) );

				do_action( 'greeny_action_after_header' );

			}
			?>

			<?php do_action( 'greeny_action_before_page_content_wrap' ); ?>

			<div class="page_content_wrap<?php
				if ( greeny_is_off( greeny_get_theme_option( 'remove_margins' ) ) ) {
					if ( empty( $greeny_header_type ) ) {
						$greeny_header_type = greeny_get_theme_option( 'header_type' );
					}
					if ( 'custom' == $greeny_header_type && greeny_is_layouts_available() ) {
						$greeny_header_id = greeny_get_custom_header_id();
						if ( $greeny_header_id > 0 ) {
							$greeny_header_meta = greeny_get_custom_layout_meta( $greeny_header_id );
							if ( ! empty( $greeny_header_meta['margin'] ) ) {
								?> page_content_wrap_custom_header_margin<?php
							}
						}
					}
					$greeny_footer_type = greeny_get_theme_option( 'footer_type' );
					if ( 'custom' == $greeny_footer_type && greeny_is_layouts_available() ) {
						$greeny_footer_id = greeny_get_custom_footer_id();
						if ( $greeny_footer_id ) {
							$greeny_footer_meta = greeny_get_custom_layout_meta( $greeny_footer_id );
							if ( ! empty( $greeny_footer_meta['margin'] ) ) {
								?> page_content_wrap_custom_footer_margin<?php
							}
						}
					}
				}
				do_action( 'greeny_action_page_content_wrap_class', $greeny_prev_post_loading );
				?>"<?php
				if ( apply_filters( 'greeny_filter_is_prev_post_loading', $greeny_prev_post_loading ) ) {
					?> data-single-style="<?php echo esc_attr( greeny_get_theme_option( 'single_style' ) ); ?>"<?php
				}
				do_action( 'greeny_action_page_content_wrap_data', $greeny_prev_post_loading );
			?>>
				<?php
				do_action( 'greeny_action_page_content_wrap', $greeny_full_post_loading || $greeny_prev_post_loading );

				// Single posts banner
				if ( apply_filters( 'greeny_filter_single_post_header', greeny_is_singular( 'post' ) || greeny_is_singular( 'attachment' ) ) ) {
					if ( $greeny_prev_post_loading ) {
						if ( greeny_get_theme_option( 'posts_navigation_scroll_which_block' ) != 'article' ) {
							do_action( 'greeny_action_between_posts' );
						}
					}
					// Single post thumbnail and title
					$greeny_path = apply_filters( 'greeny_filter_get_template_part', 'templates/single-styles/' . greeny_get_theme_option( 'single_style' ) );
					if ( greeny_get_file_dir( $greeny_path . '.php' ) != '' ) {
						get_template_part( $greeny_path );
					}
				}

				// Widgets area above page
				$greeny_body_style   = greeny_get_theme_option( 'body_style' );
				$greeny_widgets_name = greeny_get_theme_option( 'widgets_above_page' );
				$greeny_show_widgets = ! greeny_is_off( $greeny_widgets_name ) && is_active_sidebar( $greeny_widgets_name );
				if ( $greeny_show_widgets ) {
					if ( 'fullscreen' != $greeny_body_style ) {
						?>
						<div class="content_wrap">
							<?php
					}
					greeny_create_widgets_area( 'widgets_above_page' );
					if ( 'fullscreen' != $greeny_body_style ) {
						?>
						</div>
						<?php
					}
				}

				// Content area
				do_action( 'greeny_action_before_content_wrap' );
				?>
				<div class="content_wrap<?php echo 'fullscreen' == $greeny_body_style ? '_fullscreen' : ''; ?>">

					<div class="content">
						<?php
						do_action( 'greeny_action_page_content_start' );

						// Skip link anchor to fast access to the content from keyboard
						?>
						<a id="content_skip_link_anchor" class="greeny_skip_link_anchor" href="#"></a>
						<?php
						// Single posts banner between prev/next posts
						if ( ( greeny_is_singular( 'post' ) || greeny_is_singular( 'attachment' ) )
							&& $greeny_prev_post_loading 
							&& greeny_get_theme_option( 'posts_navigation_scroll_which_block' ) == 'article'
						) {
							do_action( 'greeny_action_between_posts' );
						}

						// Widgets area above content
						greeny_create_widgets_area( 'widgets_above_content' );

						do_action( 'greeny_action_page_content_start_text' );
