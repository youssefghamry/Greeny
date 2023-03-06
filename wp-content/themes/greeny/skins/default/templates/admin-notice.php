<?php
/**
 * The template to display Admin notices
 *
 * @package GREENY
 * @since GREENY 1.0.1
 */

$greeny_theme_slug = get_option( 'template' );
$greeny_theme_obj  = wp_get_theme( $greeny_theme_slug );
?>
<div class="greeny_admin_notice greeny_welcome_notice notice notice-info is-dismissible" data-notice="admin">
	<?php
	// Theme image
	$greeny_theme_img = greeny_get_file_url( 'screenshot.jpg' );
	if ( '' != $greeny_theme_img ) {
		?>
		<div class="greeny_notice_image"><img src="<?php echo esc_url( $greeny_theme_img ); ?>" alt="<?php esc_attr_e( 'Theme screenshot', 'greeny' ); ?>"></div>
		<?php
	}

	// Title
	?>
	<h3 class="greeny_notice_title">
		<?php
		echo esc_html(
			sprintf(
				// Translators: Add theme name and version to the 'Welcome' message
				__( 'Welcome to %1$s v.%2$s', 'greeny' ),
				$greeny_theme_obj->get( 'Name' ) . ( GREENY_THEME_FREE ? ' ' . __( 'Free', 'greeny' ) : '' ),
				$greeny_theme_obj->get( 'Version' )
			)
		);
		?>
	</h3>
	<?php

	// Description
	?>
	<div class="greeny_notice_text">
		<p class="greeny_notice_text_description">
			<?php
			echo str_replace( '. ', '.<br>', wp_kses_data( $greeny_theme_obj->description ) );
			?>
		</p>
		<p class="greeny_notice_text_info">
			<?php
			echo wp_kses_data( __( 'Attention! Plugin "ThemeREX Addons" is required! Please, install and activate it!', 'greeny' ) );
			?>
		</p>
	</div>
	<?php

	// Buttons
	?>
	<div class="greeny_notice_buttons">
		<?php
		// Link to the page 'About Theme'
		?>
		<a href="<?php echo esc_url( admin_url() . 'themes.php?page=greeny_about' ); ?>" class="button button-primary"><i class="dashicons dashicons-nametag"></i> 
			<?php
			echo esc_html__( 'Install plugin "ThemeREX Addons"', 'greeny' );
			?>
		</a>
	</div>
</div>
