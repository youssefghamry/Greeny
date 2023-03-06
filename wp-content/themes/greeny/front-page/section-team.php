<div class="front_page_section front_page_section_team<?php
	$greeny_scheme = greeny_get_theme_option( 'front_page_team_scheme' );
	if ( ! empty( $greeny_scheme ) && ! greeny_is_inherit( $greeny_scheme ) ) {
		echo ' scheme_' . esc_attr( $greeny_scheme );
	}
	echo ' front_page_section_paddings_' . esc_attr( greeny_get_theme_option( 'front_page_team_paddings' ) );
	if ( greeny_get_theme_option( 'front_page_team_stack' ) ) {
		echo ' sc_stack_section_on';
	}
?>"
		<?php
		$greeny_css      = '';
		$greeny_bg_image = greeny_get_theme_option( 'front_page_team_bg_image' );
		if ( ! empty( $greeny_bg_image ) ) {
			$greeny_css .= 'background-image: url(' . esc_url( greeny_get_attachment_url( $greeny_bg_image ) ) . ');';
		}
		if ( ! empty( $greeny_css ) ) {
			echo ' style="' . esc_attr( $greeny_css ) . '"';
		}
		?>
>
<?php
	// Add anchor
	$greeny_anchor_icon = greeny_get_theme_option( 'front_page_team_anchor_icon' );
	$greeny_anchor_text = greeny_get_theme_option( 'front_page_team_anchor_text' );
if ( ( ! empty( $greeny_anchor_icon ) || ! empty( $greeny_anchor_text ) ) && shortcode_exists( 'trx_sc_anchor' ) ) {
	echo do_shortcode(
		'[trx_sc_anchor id="front_page_section_team"'
									. ( ! empty( $greeny_anchor_icon ) ? ' icon="' . esc_attr( $greeny_anchor_icon ) . '"' : '' )
									. ( ! empty( $greeny_anchor_text ) ? ' title="' . esc_attr( $greeny_anchor_text ) . '"' : '' )
									. ']'
	);
}
?>
	<div class="front_page_section_inner front_page_section_team_inner
	<?php
	if ( greeny_get_theme_option( 'front_page_team_fullheight' ) ) {
		echo ' greeny-full-height sc_layouts_flex sc_layouts_columns_middle';
	}
	?>
			"
			<?php
			$greeny_css      = '';
			$greeny_bg_mask  = greeny_get_theme_option( 'front_page_team_bg_mask' );
			$greeny_bg_color_type = greeny_get_theme_option( 'front_page_team_bg_color_type' );
			if ( 'custom' == $greeny_bg_color_type ) {
				$greeny_bg_color = greeny_get_theme_option( 'front_page_team_bg_color' );
			} elseif ( 'scheme_bg_color' == $greeny_bg_color_type ) {
				$greeny_bg_color = greeny_get_scheme_color( 'bg_color', $greeny_scheme );
			} else {
				$greeny_bg_color = '';
			}
			if ( ! empty( $greeny_bg_color ) && $greeny_bg_mask > 0 ) {
				$greeny_css .= 'background-color: ' . esc_attr(
					1 == $greeny_bg_mask ? $greeny_bg_color : greeny_hex2rgba( $greeny_bg_color, $greeny_bg_mask )
				) . ';';
			}
			if ( ! empty( $greeny_css ) ) {
				echo ' style="' . esc_attr( $greeny_css ) . '"';
			}
			?>
	>
		<div class="front_page_section_content_wrap front_page_section_team_content_wrap content_wrap">
			<?php
			// Caption
			$greeny_caption = greeny_get_theme_option( 'front_page_team_caption' );
			if ( ! empty( $greeny_caption ) || ( current_user_can( 'edit_theme_options' ) && is_customize_preview() ) ) {
				?>
				<h2 class="front_page_section_caption front_page_section_team_caption front_page_block_<?php echo ! empty( $greeny_caption ) ? 'filled' : 'empty'; ?>"><?php echo wp_kses( $greeny_caption, 'greeny_kses_content' ); ?></h2>
				<?php
			}

			// Description (text)
			$greeny_description = greeny_get_theme_option( 'front_page_team_description' );
			if ( ! empty( $greeny_description ) || ( current_user_can( 'edit_theme_options' ) && is_customize_preview() ) ) {
				?>
				<div class="front_page_section_description front_page_section_team_description front_page_block_<?php echo ! empty( $greeny_description ) ? 'filled' : 'empty'; ?>"><?php echo wp_kses( wpautop( $greeny_description ), 'greeny_kses_content' ); ?></div>
				<?php
			}

			// Content (widgets)
			?>
			<div class="front_page_section_output front_page_section_team_output">
				<?php
				if ( is_active_sidebar( 'front_page_team_widgets' ) ) {
					dynamic_sidebar( 'front_page_team_widgets' );
				} elseif ( current_user_can( 'edit_theme_options' ) ) {
					if ( ! greeny_exists_trx_addons() ) {
						greeny_customizer_need_trx_addons_message();
					} else {
						greeny_customizer_need_widgets_message( 'front_page_team_caption', 'ThemeREX Addons - Team' );
					}
				}
				?>
			</div>
		</div>
	</div>
</div>
