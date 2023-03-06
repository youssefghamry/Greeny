<?php
/**
 * Admin utilities
 *
 * @package GREENY
 * @since GREENY 1.0.1
 */

// Disable direct call
if ( ! defined( 'ABSPATH' ) ) {
	exit; }


//-------------------------------------------------------
//-- Theme init
//-------------------------------------------------------

// Theme init priorities:
// 1 - register filters to add/remove lists items in the Theme Options
// 2 - create Theme Options
// 3 - add/remove Theme Options elements
// 5 - load Theme Options
// 9 - register other filters (for installer, etc.)
//10 - standard Theme init procedures (not ordered)

if ( ! function_exists( 'greeny_admin_theme_setup' ) ) {
	add_action( 'after_setup_theme', 'greeny_admin_theme_setup' );
	function greeny_admin_theme_setup() {
		// Add theme icons
		add_action( 'admin_footer', 'greeny_admin_footer' );

		// Enqueue scripts and styles for admin
		add_action( 'admin_enqueue_scripts', 'greeny_admin_scripts' );
		add_action( 'admin_footer', 'greeny_admin_localize_scripts' );

		// Show admin notice with control panel
		add_action( 'admin_notices', 'greeny_admin_notice' );
		add_action( 'wp_ajax_greeny_hide_admin_notice', 'greeny_callback_hide_admin_notice' );

		// Show admin notice with "Rate Us" panel
		add_action( 'admin_notices', 'greeny_rate_notice' );
		add_action( 'wp_ajax_greeny_hide_rate_notice', 'greeny_callback_hide_rate_notice' );

		// After switch or update theme
		add_action( 'after_switch_theme', 'greeny_save_activation_date' );
		add_action( 'after_switch_theme', 'greeny_regenerate_merged_files' );
		add_action( 'admin_init', 'greeny_check_theme_version' );

		// TGM Activation plugin
		add_action( 'tgmpa_register', 'greeny_register_plugins' );

		// Init internal admin messages
		greeny_init_admin_messages();
	}
}


//-------------------------------------------------------
//-- After switch theme
//-------------------------------------------------------

// Save activation date
if ( ! function_exists( 'greeny_save_activation_date' ) ) {
	//Handler of the add_action( 'after_switch_theme', 'greeny_save_activation_date' );
	function greeny_save_activation_date() {
		$theme_time = (int) get_option( 'greeny_theme_activated' );
		if ( 0 == $theme_time ) {
			$theme_slug      = get_template();
			$stylesheet_slug = get_stylesheet();
			if ( $theme_slug == $stylesheet_slug ) {
				update_option( 'greeny_theme_activated', time() );
			}
		}
	}
}

// Regenerate merged files with styles and scripts after the current theme is switched
if ( ! function_exists( 'greeny_regenerate_merged_files' ) ) {
	//Handler of the add_action( 'after_switch_theme', 'greeny_regenerate_merged_files' );
	function greeny_regenerate_merged_files() {
		// Set a flag to regenerate styles and scripts on first run
		if ( apply_filters( 'greeny_filter_regenerate_merged_files_after_switch_theme', true ) ) {
			greeny_set_action_save_options();
		}
	}
}

// Regenerate merged files with styles and scripts after the current theme is updated
if ( ! function_exists( 'greeny_check_theme_version' ) ) {
	//Handler of the add_action( 'admin_init', 'greeny_check_theme_version' );
	function greeny_check_theme_version() {
		if ( ! wp_doing_ajax() ) {
			$theme_slug  = get_template();
			$theme       = wp_get_theme( $theme_slug );
			$version     = $theme->get( 'Version' );
			// If the theme was updated manually
			if ( get_option( 'greeny_theme_version' ) != $version ) {
				// Set a flag to regenerate styles and scripts on first run
				if ( apply_filters( 'greeny_filter_regenerate_merged_files_after_update_theme', true ) ) {
					greeny_set_action_save_options();
				}
				// Save current version
				update_option( 'greeny_theme_version', $version );
			}
		}
	}
}


//-------------------------------------------------------
//-- Welcome notice
//-------------------------------------------------------

// Show admin notice
if ( ! function_exists( 'greeny_admin_notice' ) ) {
	//Handler of the add_action( 'admin_notices', 'greeny_admin_notice' );
	function greeny_admin_notice() {
		if ( greeny_exists_trx_addons()
			|| in_array( greeny_get_value_gp( 'action' ), array( 'vc_load_template_preview' ) )
			|| greeny_get_value_gp( 'page' ) == 'greeny_about'
			|| ! current_user_can( 'edit_theme_options' ) ) {
			return;
		}
		$show = get_option( 'greeny_admin_notice' );
		if ( false !== $show && 0 == (int) $show ) {
			return;
		}
		get_template_part( apply_filters( 'greeny_filter_get_template_part', 'templates/admin-notice' ) );
	}
}

// Hide admin notice
if ( ! function_exists( 'greeny_callback_hide_admin_notice' ) ) {
	//Handler of the add_action( 'wp_ajax_greeny_hide_admin_notice', 'greeny_callback_hide_admin_notice' );
	function greeny_callback_hide_admin_notice() {
		if ( wp_verify_nonce( greeny_get_value_gp( 'nonce' ), admin_url( 'admin-ajax.php' ) ) ) {
			update_option( 'greeny_admin_notice', '0' );
		}
		greeny_exit();
	}
}


//-------------------------------------------------------
//-- "Rate Us" notice
//-------------------------------------------------------

// Show Rate Us notice
if ( ! function_exists( 'greeny_rate_notice' ) ) {
	//Handler of the add_action( 'admin_notices', 'greeny_rate_notice' );
	function greeny_rate_notice() {
		if ( in_array( greeny_get_value_gp( 'action' ), array( 'vc_load_template_preview' ) ) ) {
			return;
		}
		if ( ! current_user_can( 'edit_theme_options' ) ) {
			return;
		}
		// Display the message only on specified screens
		$allowed = array( 'dashboard', 'theme_options', 'trx_addons_options' );
		$screen  = function_exists( 'get_current_screen' ) ? get_current_screen() : false;
		if ( ( is_object( $screen ) && ! empty( $screen->id ) && in_array( $screen->id, $allowed ) ) || in_array( greeny_get_value_gp( 'page' ), $allowed ) ) {
			$show  = get_option( 'greeny_rate_notice' );
			$start = get_option( 'greeny_theme_activated' );
			if ( ( false !== $show && 0 == (int) $show ) || ( $start > 0 && ( time() - $start ) / ( 24 * 3600 ) < 14 ) ) {
				return;
			}
			get_template_part( apply_filters( 'greeny_filter_get_template_part', 'templates/admin-rate' ) );
		}
	}
}

// Hide rate notice
if ( ! function_exists( 'greeny_callback_hide_rate_notice' ) ) {
	//Handler of the add_action( 'wp_ajax_greeny_hide_rate_notice', 'greeny_callback_hide_rate_notice' );
	function greeny_callback_hide_rate_notice() {
		if ( wp_verify_nonce( greeny_get_value_gp( 'nonce' ), admin_url( 'admin-ajax.php' ) ) ) {
			update_option( 'greeny_rate_notice', '0' );
		}
		greeny_exit();
	}
}


//-------------------------------------------------------
//-- Internal messages
//-------------------------------------------------------

// Init internal admin messages
if ( ! function_exists( 'greeny_init_admin_messages' ) ) {
	function greeny_init_admin_messages() {
		$msg = get_transient( 'greeny_admin_messages' );
		if ( is_array( $msg ) ) {
			delete_transient( 'greeny_admin_messages' );
		} else {
			$msg = array();
		}
		greeny_storage_set( 'admin_messages', $msg );
	}
}

// Add internal admin message
if ( ! function_exists( 'greeny_add_admin_message' ) ) {
	function greeny_add_admin_message( $text, $type = 'success', $cur_session = false ) {
		if ( ! empty( $text ) ) {
			$new_msg = array(
				'message' => $text,
				'type'    => $type,
			);
			if ( $cur_session ) {
				greeny_storage_push_array( 'admin_messages', '', $new_msg );
			} else {
				$msg = get_transient( 'greeny_admin_messages' );
				if ( ! is_array( $msg ) ) {
					$msg = array();
				}
				$msg[] = $new_msg;
				set_transient( 'greeny_admin_messages', $msg, 60 * 60 );
			}
		}
	}
}

// Show internal admin messages
if ( ! function_exists( 'greeny_show_admin_messages' ) ) {
	function greeny_show_admin_messages() {
		$msg = greeny_storage_get( 'admin_messages' );
		if ( ! is_array( $msg ) || count( $msg ) == 0 ) {
			return;
		}
		?>
		<div class="greeny_admin_messages">
			<?php
			foreach ( $msg as $m ) {
				?>
				<div class="greeny_admin_message_item <?php echo esc_attr( str_replace( 'success', 'updated', $m['type'] ) ); ?>">
					<p><?php echo wp_kses_data( $m['message'] ); ?></p>
				</div>
				<?php
			}
			?>
		</div>
		<?php
	}
}


//-------------------------------------------------------
//-- Styles and scripts
//-------------------------------------------------------

// Load inline styles
if ( ! function_exists( 'greeny_admin_footer' ) ) {
	//Handler of the add_action('admin_footer', 'greeny_admin_footer');
	function greeny_admin_footer() {
		// Get current screen
		$screen = function_exists( 'get_current_screen' ) ? get_current_screen() : false;
		if ( is_object( $screen ) && 'nav-menus' == $screen->id ) {
			greeny_show_layout(
				greeny_show_custom_field(
					'greeny_icons_popup',
					array(
						'type'   => 'icons',
						'style'  => greeny_get_theme_setting( 'icons_type' ),
						'button' => false,
						'icons'  => true,
					),
					null
				)
			);
		}
	}
}

// Load required styles and scripts for admin mode
if ( ! function_exists( 'greeny_admin_scripts' ) ) {
	//Handler of the add_action("admin_enqueue_scripts", 'greeny_admin_scripts');
	function greeny_admin_scripts( $all = false ) {

		// Add theme admin styles
		wp_enqueue_style( 'greeny-admin', greeny_get_file_url( 'css/admin.css' ), array(), null );

		// Load RTL styles
		if ( is_rtl() ) {
			wp_enqueue_style( 'greeny-admin-rtl', greeny_get_file_url( 'css/admin-rtl.css' ), array(), null );
		}

		// Links to selected fonts
		$screen = function_exists( 'get_current_screen' ) ? get_current_screen() : false;
		if ( $all || is_object( $screen ) ) {
			if ( $all || greeny_options_allow_override( ! empty( $screen->post_type ) ? $screen->post_type : $screen->id ) ) {
				// Load font icons
				wp_enqueue_style( 'greeny-fontello', greeny_get_file_url( 'css/font-icons/css/fontello.css' ), array(), null );
				wp_enqueue_style( 'greeny-fontello-animation', greeny_get_file_url( 'css/font-icons/css/animation.css' ), array(), null );
				// Load theme fonts
				$links = greeny_theme_fonts_links();
				if ( count( $links ) > 0 ) {
					foreach ( $links as $slug => $link ) {
						wp_enqueue_style( sprintf( 'greeny-font-%s', $slug ), $link, array(), null );
					}
				}
			} elseif ( apply_filters( 'greeny_filter_allow_theme_icons', is_customize_preview() || in_array( $screen->id, array( 'nav-menus', 'update-core', 'update-core-network' ) ), ! empty( $screen->post_type ) ? $screen->post_type : $screen->id ) ) {
				// Load font icons
				wp_enqueue_style( 'greeny-fontello', greeny_get_file_url( 'css/font-icons/css/fontello.css' ), array(), null );
				wp_enqueue_style( 'greeny-fontello-animation', greeny_get_file_url( 'css/font-icons/css/animation.css' ), array(), null );
			}
		}

		// Add theme scripts
		wp_enqueue_script( 'greeny-utils', greeny_get_file_url( 'js/utils.js' ), array( 'jquery' ), null, true );
		wp_enqueue_script( 'greeny-admin', greeny_get_file_url( 'js/admin.js' ), array( 'jquery' ), null, true );
	}
}

// Add variables in the admin mode
if ( ! function_exists( 'greeny_admin_localize_scripts' ) ) {
	//Handler of the add_action("admin_footer", 'greeny_admin_localize_scripts');
	function greeny_admin_localize_scripts() {
		$screen = function_exists( 'get_current_screen' ) ? get_current_screen() : false;
		wp_localize_script(
			'greeny-admin', 'GREENY_STORAGE', apply_filters(
				'greeny_filter_localize_script_admin', array(
					'admin_mode'                 => true,
					'screen_id'                  => is_object( $screen ) ? esc_attr( $screen->id ) : '',
					'user_logged_in'             => true,
					'ajax_url'                   => esc_url( admin_url( 'admin-ajax.php' ) ),
					'ajax_nonce'                 => esc_attr( wp_create_nonce( admin_url( 'admin-ajax.php' ) ) ),
					'msg_ajax_error'             => esc_html__( 'Server response error', 'greeny' ),
					'msg_icon_selector'          => esc_html__( 'Select the icon for this menu item', 'greeny' ),
					'msg_scheme_reset'           => esc_html__( 'Reset all changes of the current color scheme?', 'greeny' ),
					'msg_scheme_copy'            => esc_html__( 'Enter the name for a new color scheme', 'greeny' ),
					'msg_scheme_delete'          => esc_html__( 'Do you really want to delete the current color scheme?', 'greeny' ),
					'msg_scheme_delete_last'     => esc_html__( 'You cannot delete the last color scheme!', 'greeny' ),
					'msg_scheme_delete_internal' => esc_html__( 'You cannot delete the built-in color scheme!', 'greeny' ),
					'msg_reset'                  => esc_html__( 'Reset', 'greeny' ),
					'msg_reset_confirm'          => esc_html__( 'Are you sure you want to reset all Theme Options?', 'greeny' ),
					'msg_export'                 => esc_html__( 'Export', 'greeny' ),
					'msg_export_options'         => esc_html__( 'Copy options and save to the text file.', 'greeny' ),
					'msg_import'                 => esc_html__( 'Import', 'greeny' ),
					'msg_import_options'         => esc_html__( 'Paste previously saved options from the text file.', 'greeny' ),
					'msg_import_error'           => esc_html__( 'Error occurs while import options!', 'greeny' ),
					'msg_presets'                => esc_html__( 'Options presets', 'greeny' ),
					'msg_presets_add'            => esc_html__( 'Specify the name of a new preset:', 'greeny' ),
					'msg_presets_apply'          => esc_html__( 'Apply the selected preset?', 'greeny' ),
					'msg_presets_delete'         => esc_html__( 'Delete the selected preset?', 'greeny' ),
				)
			)
		);
	}
}



//-------------------------------------------------------
//-- Third party plugins
//-------------------------------------------------------

// Register optional plugins
if ( ! function_exists( 'greeny_register_plugins' ) ) {
	//Handler of the add_action('tgmpa_register', 'greeny_register_plugins');
	function greeny_register_plugins() {
		tgmpa(
			apply_filters(
				'greeny_filter_tgmpa_required_plugins', array(
				// Plugins to include in the autoinstall queue.
				)
			),
			array(
				'id'           => 'tgmpa',                 // Unique ID for hashing notices for multiple instances of TGMPA.
				'default_path' => '',                      // Default absolute path to bundled plugins.
				'menu'         => 'tgmpa-install-plugins', // Menu slug.
				'parent_slug'  => 'themes.php',            // Parent menu slug.
				'capability'   => 'edit_theme_options',    // Capability needed to view plugin install page, should be a capability associated with the parent menu used.
				'has_notices'  => true,                    // Show admin notices or not.
				'dismissable'  => true,                    // If false, a user cannot dismiss the nag message.
				'dismiss_msg'  => '',                      // If 'dismissable' is false, this message will be output at top of nag.
				'is_automatic' => false,                   // Automatically activate plugins after installation or not.
				'message'      => '',                      // Message to output right before the plugins table.
			)
		);
	}
}


// Add group and logo from the parent plugin to the slave plugin
if ( ! function_exists( 'greeny_add_group_and_logo_to_slave' ) ) {
	function greeny_add_group_and_logo_to_slave( $list, $parent, $slave ) {
		$group = ! empty( $list[ $parent ]['group'] )
					? $list[ $parent ]['group']
					: greeny_storage_get_array( 'required_plugins', $parent, 'group' ); 
		if ( ! empty( $group ) ) {
			foreach ( $list as $k => $v ) {
				if ( substr( $k, 0, strlen( $slave ) ) == $slave ) {
					if ( empty( $v['group'] ) ) {
						$list[ $k ]['group'] = $group;
					}
					if ( empty( $v['logo'] ) ) {
						$logo = greeny_get_file_url( "plugins/{$parent}/{$k}.png" );
						$list[ $k ]['logo'] = empty( $logo )
												? ( ! empty( $list[ $parent ]['logo'] )
													? ( greeny_is_url( $list[ $parent ]['logo'] )
														? $list[ $parent ]['logo']
														: greeny_get_file_url( sprintf( 'plugins/%1$s/%2$s', $parent, $list[ $parent ]['logo'] ) )
														)
													: ''
													)
												: $logo;
					}
				}
			}
		}
		return $list;
	}
}


// Return path to the plugin source
if ( ! function_exists( 'greeny_get_plugin_source_path' ) ) {
	function greeny_get_plugin_source_path( $path ) {
		$local = greeny_get_file_dir( $path );
		$path  = empty( $local ) && ! greeny_get_theme_setting( 'tgmpa_upload' ) ? greeny_get_plugin_source_url( $path ) : $local;
		return $path;
	}
}


// Return URL to the plugin download
if ( ! function_exists( 'greeny_get_plugin_source_url' ) ) {
	function greeny_get_plugin_source_url( $path ) {
		$code = greeny_get_theme_activation_code();
		$url  = '';
		if ( ! empty( $code ) || greeny_is_theme_activated() || strpos($path, '/trx_addons/') !== false ) {   // Allow to install 'trx_addons' without theme activation
			$url = greeny_get_upgrade_url( array(
				'action' => 'install_plugin',
				'key'    => $code,
				'plugin' => str_replace( 'plugins/', '', $path )
			) );
		}
		return greeny_add_protocol( $url );
	}
}
