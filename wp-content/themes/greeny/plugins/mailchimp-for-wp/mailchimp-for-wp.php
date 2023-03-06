<?php
/* Mail Chimp support functions
------------------------------------------------------------------------------- */

// Theme init priorities:
// 9 - register other filters (for installer, etc.)
if ( ! function_exists( 'greeny_mailchimp_theme_setup9' ) ) {
	add_action( 'after_setup_theme', 'greeny_mailchimp_theme_setup9', 9 );
	function greeny_mailchimp_theme_setup9() {
		if ( greeny_exists_mailchimp() ) {
			add_action( 'wp_enqueue_scripts', 'greeny_mailchimp_frontend_scripts', 1100 );
			add_action( 'trx_addons_action_load_scripts_front_mailchimp', 'greeny_mailchimp_frontend_scripts', 10, 1 );
			add_filter( 'greeny_filter_merge_styles', 'greeny_mailchimp_merge_styles' );
		}
		if ( is_admin() ) {
			add_filter( 'greeny_filter_tgmpa_required_plugins', 'greeny_mailchimp_tgmpa_required_plugins' );
		}
	}
}

// Filter to add in the required plugins list
if ( ! function_exists( 'greeny_mailchimp_tgmpa_required_plugins' ) ) {
	//Handler of the add_filter('greeny_filter_tgmpa_required_plugins',	'greeny_mailchimp_tgmpa_required_plugins');
	function greeny_mailchimp_tgmpa_required_plugins( $list = array() ) {
		if ( greeny_storage_isset( 'required_plugins', 'mailchimp-for-wp' ) && greeny_storage_get_array( 'required_plugins', 'mailchimp-for-wp', 'install' ) !== false ) {
			$list[] = array(
				'name'     => greeny_storage_get_array( 'required_plugins', 'mailchimp-for-wp', 'title' ),
				'slug'     => 'mailchimp-for-wp',
				'required' => false,
			);
		}
		return $list;
	}
}

// Check if plugin installed and activated
if ( ! function_exists( 'greeny_exists_mailchimp' ) ) {
	function greeny_exists_mailchimp() {
		return function_exists( '__mc4wp_load_plugin' ) || defined( 'MC4WP_VERSION' );
	}
}



// Custom styles and scripts
//------------------------------------------------------------------------

// Enqueue styles for frontend
if ( ! function_exists( 'greeny_mailchimp_frontend_scripts' ) ) {
	//Handler of the add_action( 'wp_enqueue_scripts', 'greeny_mailchimp_frontend_scripts', 1100 );
	//Handler of the add_action( 'trx_addons_action_load_scripts_front_mailchimp', 'greeny_mailchimp_frontend_scripts', 10, 1 );
	function greeny_mailchimp_frontend_scripts( $force = false ) {
		static $loaded = false;
		if ( ! $loaded && (
			current_action() == 'wp_enqueue_scripts' && greeny_need_frontend_scripts( 'mailchimp' )
			||
			current_action() != 'wp_enqueue_scripts' && $force === true
			)
		) {
			$loaded = true;
			$greeny_url = greeny_get_file_url( 'plugins/mailchimp-for-wp/mailchimp-for-wp.css' );
			if ( '' != $greeny_url ) {
				wp_enqueue_style( 'greeny-mailchimp-for-wp', $greeny_url, array(), null );
			}
		}
	}
}

// Merge custom styles
if ( ! function_exists( 'greeny_mailchimp_merge_styles' ) ) {
	//Handler of the add_filter( 'greeny_filter_merge_styles', 'greeny_mailchimp_merge_styles');
	function greeny_mailchimp_merge_styles( $list ) {
		$list[ 'plugins/mailchimp-for-wp/mailchimp-for-wp.css' ] = false;
		return $list;
	}
}


// Add plugin-specific colors and fonts to the custom CSS
if ( greeny_exists_mailchimp() ) {
	require_once greeny_get_file_dir( 'plugins/mailchimp-for-wp/mailchimp-for-wp-style.php' );
}

