<?php
/**
 * The template to display Admin notices
 *
 * @package GREENY
 * @since GREENY 1.0.64
 */

$greeny_skins_url  = get_admin_url( null, 'admin.php?page=trx_addons_theme_panel#trx_addons_theme_panel_section_skins' );
$greeny_skins_args = get_query_var( 'greeny_skins_notice_args' );
?>
<div class="greeny_admin_notice greeny_skins_notice notice notice-info is-dismissible" data-notice="skins">
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
		<?php esc_html_e( 'New skins available', 'greeny' ); ?>
	</h3>
	<?php

	// Description
	$greeny_total      = $greeny_skins_args['update'];	// Store value to the separate variable to avoid warnings from ThemeCheck plugin!
	$greeny_skins_msg  = $greeny_total > 0
							// Translators: Add new skins number
							? '<strong>' . sprintf( _n( '%d new version', '%d new versions', $greeny_total, 'greeny' ), $greeny_total ) . '</strong>'
							: '';
	$greeny_total      = $greeny_skins_args['free'];
	$greeny_skins_msg .= $greeny_total > 0
							? ( ! empty( $greeny_skins_msg ) ? ' ' . esc_html__( 'and', 'greeny' ) . ' ' : '' )
								// Translators: Add new skins number
								. '<strong>' . sprintf( _n( '%d free skin', '%d free skins', $greeny_total, 'greeny' ), $greeny_total ) . '</strong>'
							: '';
	$greeny_total      = $greeny_skins_args['pay'];
	$greeny_skins_msg .= $greeny_skins_args['pay'] > 0
							? ( ! empty( $greeny_skins_msg ) ? ' ' . esc_html__( 'and', 'greeny' ) . ' ' : '' )
								// Translators: Add new skins number
								. '<strong>' . sprintf( _n( '%d paid skin', '%d paid skins', $greeny_total, 'greeny' ), $greeny_total ) . '</strong>'
							: '';
	?>
	<div class="greeny_notice_text">
		<p>
			<?php
			// Translators: Add new skins info
			echo wp_kses_data( sprintf( __( "We are pleased to announce that %s are available for your theme", 'greeny' ), $greeny_skins_msg ) );
			?>
		</p>
	</div>
	<?php

	// Buttons
	?>
	<div class="greeny_notice_buttons">
		<?php
		// Link to the theme dashboard page
		?>
		<a href="<?php echo esc_url( $greeny_skins_url ); ?>" class="button button-primary"><i class="dashicons dashicons-update"></i> 
			<?php
			// Translators: Add theme name
			esc_html_e( 'Go to Skins manager', 'greeny' );
			?>
		</a>
	</div>
</div>
