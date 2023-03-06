<?php
/* Essential Grid support functions
------------------------------------------------------------------------------- */


// Theme init priorities:
// 9 - register other filters (for installer, etc.)
if ( ! function_exists( 'greeny_essential_grid_theme_setup9' ) ) {
	add_action( 'after_setup_theme', 'greeny_essential_grid_theme_setup9', 9 );
	function greeny_essential_grid_theme_setup9() {
		if ( greeny_exists_essential_grid() ) {
			add_action( 'wp_enqueue_scripts', 'greeny_essential_grid_frontend_scripts', 1100 );
			add_action( 'trx_addons_action_load_scripts_front_essential_grid', 'greeny_essential_grid_frontend_scripts', 10, 1 );
			add_filter( 'greeny_filter_merge_styles', 'greeny_essential_grid_merge_styles' );
		}
		if ( is_admin() ) {
			add_filter( 'greeny_filter_tgmpa_required_plugins', 'greeny_essential_grid_tgmpa_required_plugins' );
		}
	}
}

// Filter to add in the required plugins list
if ( ! function_exists( 'greeny_essential_grid_tgmpa_required_plugins' ) ) {
	//Handler of the add_filter('greeny_filter_tgmpa_required_plugins',	'greeny_essential_grid_tgmpa_required_plugins');
	function greeny_essential_grid_tgmpa_required_plugins( $list = array() ) {
		if ( greeny_storage_isset( 'required_plugins', 'essential-grid' ) && greeny_storage_get_array( 'required_plugins', 'essential-grid', 'install' ) !== false && greeny_is_theme_activated() ) {
			$path = greeny_get_plugin_source_path( 'plugins/essential-grid/essential-grid.zip' );
			if ( ! empty( $path ) || greeny_get_theme_setting( 'tgmpa_upload' ) ) {
				$list[] = array(
					'name'     => greeny_storage_get_array( 'required_plugins', 'essential-grid', 'title' ),
					'slug'     => 'essential-grid',
					'source'   => ! empty( $path ) ? $path : 'upload://essential-grid.zip',
					'version'  => '2.2.4.2',
					'required' => false,
				);
			}
		}
		return $list;
	}
}

// Check if plugin installed and activated
if ( ! function_exists( 'greeny_exists_essential_grid' ) ) {
	function greeny_exists_essential_grid() {
		return defined( 'EG_PLUGIN_PATH' ) || defined( 'ESG_PLUGIN_PATH' );
	}
}

// Enqueue styles for frontend
if ( ! function_exists( 'greeny_essential_grid_frontend_scripts' ) ) {
	//Handler of the add_action( 'wp_enqueue_scripts', 'greeny_essential_grid_frontend_scripts', 1100 );
	//Handler of the add_action( 'trx_addons_action_load_scripts_front_essential_grid', 'greeny_essential_grid_frontend_scripts', 10, 1 );
	function greeny_essential_grid_frontend_scripts( $force = false ) {
		static $loaded = false;
		if ( ! $loaded && (
			current_action() == 'wp_enqueue_scripts' && greeny_need_frontend_scripts( 'essential_grid' )
			||
			current_action() != 'wp_enqueue_scripts' && $force === true
			)
		) {
			$loaded = true;
			$greeny_url = greeny_get_file_url( 'plugins/essential-grid/essential-grid.css' );
			if ( '' != $greeny_url ) {
				wp_enqueue_style( 'greeny-essential-grid', $greeny_url, array(), null );
			}
		}
	}
}

// Merge custom styles
if ( ! function_exists( 'greeny_essential_grid_merge_styles' ) ) {
	//Handler of the add_filter('greeny_filter_merge_styles', 'greeny_essential_grid_merge_styles');
	function greeny_essential_grid_merge_styles( $list ) {
		$list[ 'plugins/essential-grid/essential-grid.css' ] = false;
		return $list;
	}
}
