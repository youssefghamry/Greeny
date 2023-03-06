<?php
/**
 * The template to show mobile menu (used only header_style == 'default')
 *
 * @package GREENY
 * @since GREENY 1.0
 */

$greeny_show_widgets = greeny_get_theme_option( 'widgets_menu_mobile_fullscreen' );
$greeny_show_socials = greeny_get_theme_option( 'menu_mobile_socials' );

?>
<div class="menu_mobile_overlay scheme_dark"></div>
<div class="menu_mobile menu_mobile_<?php echo esc_attr( greeny_get_theme_option( 'menu_mobile_fullscreen' ) > 0 ? 'fullscreen' : 'narrow' ); ?> scheme_dark">
	<div class="menu_mobile_inner<?php echo esc_attr( $greeny_show_widgets == 1  ? ' with_widgets' : '' ); ?>">
        <div class="menu_mobile_header_wrap">
            <?php
            // Logo
            set_query_var( 'greeny_logo_args', array( 'type' => 'mobile' ) );
            get_template_part( apply_filters( 'greeny_filter_get_template_part', 'templates/header-logo' ) );
            set_query_var( 'greeny_logo_args', array() ); ?>

            <a class="menu_mobile_close menu_button_close" tabindex="0"><span class="menu_button_close_text"><?php esc_html_e('Close', 'greeny')?></span><span class="menu_button_close_icon"></span></a>
        </div>
        <div class="menu_mobile_content_wrap content_wrap">
            <div class="menu_mobile_content_wrap_inner<?php echo esc_attr($greeny_show_socials ? '' : ' without_socials'); ?>"><?php
            // Mobile menu
            $greeny_menu_mobile = greeny_get_nav_menu( 'menu_mobile' );
            if ( empty( $greeny_menu_mobile ) ) {
                $greeny_menu_mobile = apply_filters( 'greeny_filter_get_mobile_menu', '' );
                if ( empty( $greeny_menu_mobile ) ) {
                    $greeny_menu_mobile = greeny_get_nav_menu( 'menu_main' );
                    if ( empty( $greeny_menu_mobile ) ) {
                        $greeny_menu_mobile = greeny_get_nav_menu();
                    }
                }
            }
            if ( ! empty( $greeny_menu_mobile ) ) {
                $greeny_menu_mobile = str_replace(
                    array( 'menu_main',   'id="menu-',        'sc_layouts_menu_nav', 'sc_layouts_menu ', 'sc_layouts_hide_on_mobile', 'hide_on_mobile' ),
                    array( 'menu_mobile', 'id="menu_mobile-', '',                    ' ',                '',                          '' ),
                    $greeny_menu_mobile
                );
                if ( strpos( $greeny_menu_mobile, '<nav ' ) === false ) {
                    $greeny_menu_mobile = sprintf( '<nav class="menu_mobile_nav_area" itemscope="itemscope" itemtype="%1$s//schema.org/SiteNavigationElement">%2$s</nav>', esc_attr( greeny_get_protocol( true ) ), $greeny_menu_mobile );
                }
                greeny_show_layout( apply_filters( 'greeny_filter_menu_mobile_layout', $greeny_menu_mobile ) );
            }
            // Social icons
            if($greeny_show_socials) {
                greeny_show_layout( greeny_get_socials_links(), '<div class="socials_mobile">', '</div>' );
            }            
            ?>
            </div>
		</div><?php

        if ( $greeny_show_widgets == 1 )  {
            ?><div class="menu_mobile_widgets_area"><?php
            // Create Widgets Area
            greeny_create_widgets_area( 'widgets_additional_menu_mobile_fullscreen' );
            ?></div><?php
        } ?>

    </div>
</div>
