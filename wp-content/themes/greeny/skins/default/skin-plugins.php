<?php
/**
 * Required plugins
 *
 * @package GREENY
 * @since GREENY 1.76.0
 */

// THEME-SUPPORTED PLUGINS
// If plugin not need - remove its settings from next array
//----------------------------------------------------------
$greeny_theme_required_plugins_groups = array(
	'core'          => esc_html__( 'Core', 'greeny' ),
	'page_builders' => esc_html__( 'Page Builders', 'greeny' ),
	'ecommerce'     => esc_html__( 'E-Commerce & Donations', 'greeny' ),
	'socials'       => esc_html__( 'Socials and Communities', 'greeny' ),
	'events'        => esc_html__( 'Events and Appointments', 'greeny' ),
	'content'       => esc_html__( 'Content', 'greeny' ),
	'other'         => esc_html__( 'Other', 'greeny' ),
);
$greeny_theme_required_plugins        = array(
	'trx_addons'                 => array(
		'title'       => esc_html__( 'ThemeREX Addons', 'greeny' ),
		'description' => esc_html__( "Will allow you to install recommended plugins, demo content, and improve the theme's functionality overall with multiple theme options", 'greeny' ),
		'required'    => true,
		'logo'        => 'trx_addons.png',
		'group'       => $greeny_theme_required_plugins_groups['core'],
	),
	'elementor'                  => array(
		'title'       => esc_html__( 'Elementor', 'greeny' ),
		'description' => esc_html__( "Is a beautiful PageBuilder, even the free version of which allows you to create great pages using a variety of modules.", 'greeny' ),
		'required'    => false,
		'logo'        => 'elementor.png',
		'group'       => $greeny_theme_required_plugins_groups['page_builders'],
	),
	'gutenberg'                  => array(
		'title'       => esc_html__( 'Gutenberg', 'greeny' ),
		'description' => esc_html__( "It's a posts editor coming in place of the classic TinyMCE. Can be installed and used in parallel with Elementor", 'greeny' ),
		'required'    => false,
		'install'     => false,          // Do not offer installation of the plugin in the Theme Dashboard and TGMPA
		'logo'        => 'gutenberg.png',
		'group'       => $greeny_theme_required_plugins_groups['page_builders'],
	),
	'js_composer'                => array(
		'title'       => esc_html__( 'WPBakery PageBuilder', 'greeny' ),
		'description' => esc_html__( "Popular PageBuilder which allows you to create excellent pages", 'greeny' ),
		'required'    => false,
		'install'     => false,          // Do not offer installation of the plugin in the Theme Dashboard and TGMPA
		'logo'        => 'js_composer.jpg',
		'group'       => $greeny_theme_required_plugins_groups['page_builders'],
	),
	'woocommerce'                => array(
		'title'       => esc_html__( 'WooCommerce', 'greeny' ),
		'description' => esc_html__( "Connect the store to your website and start selling now", 'greeny' ),
		'required'    => false,
        'install'     => false,
		'logo'        => 'woocommerce.png',
		'group'       => $greeny_theme_required_plugins_groups['ecommerce'],
	),
	'elegro-payment'             => array(
		'title'       => esc_html__( 'Elegro Crypto Payment', 'greeny' ),
		'description' => esc_html__( "Extends WooCommerce Payment Gateways with an elegro Crypto Payment", 'greeny' ),
		'required'    => false,
        'install'     => false,
		'logo'        => 'elegro-payment.png',
		'group'       => $greeny_theme_required_plugins_groups['ecommerce'],
	),
	'instagram-feed'             => array(
		'title'       => esc_html__( 'Instagram Feed', 'greeny' ),
		'description' => esc_html__( "Displays the latest photos from your profile on Instagram", 'greeny' ),
		'required'    => false,
		'logo'        => 'instagram-feed.png',
		'group'       => $greeny_theme_required_plugins_groups['socials'],
	),
	'mailchimp-for-wp'           => array(
		'title'       => esc_html__( 'MailChimp for WP', 'greeny' ),
		'description' => esc_html__( "Allows visitors to subscribe to newsletters", 'greeny' ),
		'required'    => false,
		'logo'        => 'mailchimp-for-wp.png',
		'group'       => $greeny_theme_required_plugins_groups['socials'],
	),
	'booked'                     => array(
		'title'       => esc_html__( 'Booked Appointments', 'greeny' ),
		'description' => '',
		'required'    => false,
        'install'     => false,
		'logo'        => 'booked.png',
		'group'       => $greeny_theme_required_plugins_groups['events'],
	),
	'the-events-calendar'        => array(
		'title'       => esc_html__( 'The Events Calendar', 'greeny' ),
		'description' => '',
		'required'    => false,
        'install'     => false,
		'logo'        => 'the-events-calendar.png',
		'group'       => $greeny_theme_required_plugins_groups['events'],
	),
	'contact-form-7'             => array(
		'title'       => esc_html__( 'Contact Form 7', 'greeny' ),
		'description' => esc_html__( "CF7 allows you to create an unlimited number of contact forms", 'greeny' ),
		'required'    => false,
		'logo'        => 'contact-form-7.png',
		'group'       => $greeny_theme_required_plugins_groups['content'],
	),

	'latepoint'                  => array(
		'title'       => esc_html__( 'LatePoint', 'greeny' ),
		'description' => '',
		'required'    => false,
        'install'     => false,
		'logo'        => greeny_get_file_url( 'plugins/latepoint/latepoint.png' ),
		'group'       => $greeny_theme_required_plugins_groups['events'],
	),
	'advanced-popups'                  => array(
		'title'       => esc_html__( 'Advanced Popups', 'greeny' ),
		'description' => '',
		'required'    => false,
		'logo'        => greeny_get_file_url( 'plugins/advanced-popups/advanced-popups.jpg' ),
		'group'       => $greeny_theme_required_plugins_groups['content'],
	),
	'devvn-image-hotspot'                  => array(
		'title'       => esc_html__( 'Image Hotspot by DevVN', 'greeny' ),
		'description' => '',
		'required'    => false,
        'install'     => false,
		'logo'        => greeny_get_file_url( 'plugins/devvn-image-hotspot/devvn-image-hotspot.png' ),
		'group'       => $greeny_theme_required_plugins_groups['content'],
	),
	'ti-woocommerce-wishlist'                  => array(
		'title'       => esc_html__( 'TI WooCommerce Wishlist', 'greeny' ),
		'description' => '',
		'required'    => false,
        'install'     => false,
		'logo'        => greeny_get_file_url( 'plugins/ti-woocommerce-wishlist/ti-woocommerce-wishlist.png' ),
		'group'       => $greeny_theme_required_plugins_groups['ecommerce'],
	),
	'twenty20'                  => array(
		'title'       => esc_html__( 'Twenty20 Image Before-After', 'greeny' ),
		'description' => '',
		'required'    => false,
        'install'     => false,
		'logo'        => greeny_get_file_url( 'plugins/twenty20/twenty20.png' ),
		'group'       => $greeny_theme_required_plugins_groups['content'],
	),
	'essential-grid'             => array(
		'title'       => esc_html__( 'Essential Grid', 'greeny' ),
		'description' => '',
		'required'    => false,
		'install'     => false,
		'logo'        => 'essential-grid.png',
		'group'       => $greeny_theme_required_plugins_groups['content'],
	),
	'revslider'                  => array(
		'title'       => esc_html__( 'Revolution Slider', 'greeny' ),
		'description' => '',
		'required'    => false,
		'logo'        => 'revslider.png',
		'group'       => $greeny_theme_required_plugins_groups['content'],
	),
	'sitepress-multilingual-cms' => array(
		'title'       => esc_html__( 'WPML - Sitepress Multilingual CMS', 'greeny' ),
		'description' => esc_html__( "Allows you to make your website multilingual", 'greeny' ),
		'required'    => false,
		'install'     => false,      // Do not offer installation of the plugin in the Theme Dashboard and TGMPA
		'logo'        => 'sitepress-multilingual-cms.png',
		'group'       => $greeny_theme_required_plugins_groups['content'],
	),
	'wp-gdpr-compliance'         => array(
		'title'       => esc_html__( 'Cookie Information', 'greeny' ),
		'description' => esc_html__( "Allow visitors to decide for themselves what personal data they want to store on your site", 'greeny' ),
		'required'    => false,
		'logo'        => 'wp-gdpr-compliance.png',
		'group'       => $greeny_theme_required_plugins_groups['other'],
	),
	'trx_updater'                => array(
		'title'       => esc_html__( 'ThemeREX Updater', 'greeny' ),
		'description' => esc_html__( "Update theme and theme-specific plugins from developer's upgrade server.", 'greeny' ),
		'required'    => false,
		'logo'        => 'trx_updater.png',
		'group'       => $greeny_theme_required_plugins_groups['other'],
	),
);

if ( GREENY_THEME_FREE ) {
	unset( $greeny_theme_required_plugins['js_composer'] );
	unset( $greeny_theme_required_plugins['booked'] );
	unset( $greeny_theme_required_plugins['the-events-calendar'] );
	unset( $greeny_theme_required_plugins['calculated-fields-form'] );
	unset( $greeny_theme_required_plugins['essential-grid'] );
	unset( $greeny_theme_required_plugins['revslider'] );
	unset( $greeny_theme_required_plugins['sitepress-multilingual-cms'] );
	unset( $greeny_theme_required_plugins['trx_updater'] );
	unset( $greeny_theme_required_plugins['trx_popup'] );
}

// Add plugins list to the global storage
greeny_storage_set( 'required_plugins', $greeny_theme_required_plugins );
