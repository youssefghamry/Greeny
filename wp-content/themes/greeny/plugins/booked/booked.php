<?php
/* Booked Appointments support functions
------------------------------------------------------------------------------- */

// Theme init priorities:
// 9 - register other filters (for installer, etc.)
if ( ! function_exists( 'greeny_booked_theme_setup9' ) ) {
	add_action( 'after_setup_theme', 'greeny_booked_theme_setup9', 9 );
	function greeny_booked_theme_setup9() {
		if ( greeny_exists_booked() ) {
			add_action( 'wp_enqueue_scripts', 'greeny_booked_frontend_scripts', 1100 );
			add_action( 'trx_addons_action_load_scripts_front_booked', 'greeny_booked_frontend_scripts', 10, 1 );
			add_action( 'wp_enqueue_scripts', 'greeny_booked_frontend_scripts_responsive', 2000 );
			add_action( 'trx_addons_action_load_scripts_front_booked', 'greeny_booked_frontend_scripts_responsive', 10, 1 );
			add_filter( 'greeny_filter_merge_styles', 'greeny_booked_merge_styles' );
			add_filter( 'greeny_filter_merge_styles_responsive', 'greeny_booked_merge_styles_responsive' );
		}
		if ( is_admin() ) {
			add_filter( 'greeny_filter_tgmpa_required_plugins', 'greeny_booked_tgmpa_required_plugins' );
			add_filter( 'greeny_filter_theme_plugins', 'greeny_booked_theme_plugins' );
		}
	}
}

// Filter to add in the required plugins list
if ( ! function_exists( 'greeny_booked_tgmpa_required_plugins' ) ) {
	//Handler of the add_filter('greeny_filter_tgmpa_required_plugins',	'greeny_booked_tgmpa_required_plugins');
	function greeny_booked_tgmpa_required_plugins( $list = array() ) {
		if ( greeny_storage_isset( 'required_plugins', 'booked' ) && greeny_storage_get_array( 'required_plugins', 'booked', 'install' ) !== false && greeny_is_theme_activated() ) {
			$path = greeny_get_plugin_source_path( 'plugins/booked/booked.zip' );
			if ( ! empty( $path ) || greeny_get_theme_setting( 'tgmpa_upload' ) ) {
				$list[] = array(
					'name'     => greeny_storage_get_array( 'required_plugins', 'booked', 'title' ),
					'slug'     => 'booked',
					'source'   => ! empty( $path ) ? $path : 'upload://booked.zip',
					'version'  => '2.3',
					'required' => false,
				);
			}
		}
		return $list;
	}
}

// Filter theme-supported plugins list
if ( ! function_exists( 'greeny_booked_theme_plugins' ) ) {
	//Handler of the add_filter( 'greeny_filter_theme_plugins', 'greeny_booked_theme_plugins' );
	function greeny_booked_theme_plugins( $list = array() ) {
		return greeny_add_group_and_logo_to_slave( $list, 'booked', 'booked-' );
	}
}



// Check if plugin installed and activated
if ( ! function_exists( 'greeny_exists_booked' ) ) {
	function greeny_exists_booked() {
		return class_exists( 'booked_plugin' );
	}
}


// Enqueue styles for frontend
if ( ! function_exists( 'greeny_booked_frontend_scripts' ) ) {
	//Handler of the add_action( 'wp_enqueue_scripts', 'greeny_booked_frontend_scripts', 1100 );
	//Handler of the add_action( 'trx_addons_action_load_scripts_front_booked', 'greeny_booked_frontend_scripts', 10, 1 );
	function greeny_booked_frontend_scripts( $force = false ) {
		static $loaded = false;
		if ( ! $loaded && (
			current_action() == 'wp_enqueue_scripts' && greeny_need_frontend_scripts( 'booked' )
			||
			current_action() != 'wp_enqueue_scripts' && $force === true
			)
		) {
			$loaded = true;
			$greeny_url = greeny_get_file_url( 'plugins/booked/booked.css' );
			if ( '' != $greeny_url ) {
				wp_enqueue_style( 'greeny-booked', $greeny_url, array(), null );
			}
		}
	}
}


// Enqueue responsive styles for frontend
if ( ! function_exists( 'greeny_booked_frontend_scripts_responsive' ) ) {
	//Handler of the add_action( 'wp_enqueue_scripts', 'greeny_booked_frontend_scripts_responsive', 2000 );
	//Handler of the add_action( 'trx_addons_action_load_scripts_front_booked', 'greeny_booked_frontend_scripts_responsive', 10, 1 );
	function greeny_booked_frontend_scripts_responsive( $force = false ) {
		static $loaded = false;
		if ( ! $loaded && (
			current_action() == 'wp_enqueue_scripts' && greeny_need_frontend_scripts( 'booked' )
			||
			current_action() != 'wp_enqueue_scripts' && $force === true
			)
		) {
			$loaded = true;
			$greeny_url = greeny_get_file_url( 'plugins/booked/booked-responsive.css' );
			if ( '' != $greeny_url ) {
				wp_enqueue_style( 'greeny-booked-responsive', $greeny_url, array(), null, greeny_media_for_load_css_responsive( 'booked' ) );
			}
		}
	}
}

// Merge custom styles
if ( ! function_exists( 'greeny_booked_merge_styles' ) ) {
	//Handler of the add_filter('greeny_filter_merge_styles', 'greeny_booked_merge_styles');
	function greeny_booked_merge_styles( $list ) {
		$list[ 'plugins/booked/booked.css' ] = false;
		return $list;
	}
}

// Merge responsive styles
if ( ! function_exists( 'greeny_booked_merge_styles_responsive' ) ) {
	//Handler of the add_filter('greeny_filter_merge_styles_responsive', 'greeny_booked_merge_styles_responsive');
	function greeny_booked_merge_styles_responsive( $list ) {
		$list[ 'plugins/booked/booked-responsive.css' ] = false;
		return $list;
	}
}


// Add plugin-specific colors and fonts to the custom CSS
if ( greeny_exists_booked() ) {
	require_once greeny_get_file_dir( 'plugins/booked/booked-style.php' );
}
