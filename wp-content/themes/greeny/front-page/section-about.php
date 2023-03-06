<div class="front_page_section front_page_section_about<?php
	$greeny_scheme = greeny_get_theme_option( 'front_page_about_scheme' );
	if ( ! empty( $greeny_scheme ) && ! greeny_is_inherit( $greeny_scheme ) ) {
		echo ' scheme_' . esc_attr( $greeny_scheme );
	}
	echo ' front_page_section_paddings_' . esc_attr( greeny_get_theme_option( 'front_page_about_paddings' ) );
	if ( greeny_get_theme_option( 'front_page_about_stack' ) ) {
		echo ' sc_stack_section_on';
	}
?>"
		<?php
		$greeny_css      = '';
		$greeny_bg_image = greeny_get_theme_option( 'front_page_about_bg_image' );
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
	$greeny_anchor_icon = greeny_get_theme_option( 'front_page_about_anchor_icon' );
	$greeny_anchor_text = greeny_get_theme_option( 'front_page_about_anchor_text' );
if ( ( ! empty( $greeny_anchor_icon ) || ! empty( $greeny_anchor_text ) ) && shortcode_exists( 'trx_sc_anchor' ) ) {
	echo do_shortcode(
		'[trx_sc_anchor id="front_page_section_about"'
									. ( ! empty( $greeny_anchor_icon ) ? ' icon="' . esc_attr( $greeny_anchor_icon ) . '"' : '' )
									. ( ! empty( $greeny_anchor_text ) ? ' title="' . esc_attr( $greeny_anchor_text ) . '"' : '' )
									. ']'
	);
}
?>
	<div class="front_page_section_inner front_page_section_about_inner
	<?php
	if ( greeny_get_theme_option( 'front_page_about_fullheight' ) ) {
		echo ' greeny-full-height sc_layouts_flex sc_layouts_columns_middle';
	}
	?>
			"
			<?php
			$greeny_css           = '';
			$greeny_bg_mask       = greeny_get_theme_option( 'front_page_about_bg_mask' );
			$greeny_bg_color_type = greeny_get_theme_option( 'front_page_about_bg_color_type' );
			if ( 'custom' == $greeny_bg_color_type ) {
				$greeny_bg_color = greeny_get_theme_option( 'front_page_about_bg_color' );
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
		<div class="front_page_section_content_wrap front_page_section_about_content_wrap content_wrap">
			<?php
			// Caption
			$greeny_caption = greeny_get_theme_option( 'front_page_about_caption' );
			if ( ! empty( $greeny_caption ) || ( current_user_can( 'edit_theme_options' ) && is_customize_preview() ) ) {
				?>
				<h2 class="front_page_section_caption front_page_section_about_caption front_page_block_<?php echo ! empty( $greeny_caption ) ? 'filled' : 'empty'; ?>"><?php echo wp_kses( $greeny_caption, 'greeny_kses_content' ); ?></h2>
				<?php
			}

			// Description (text)
			$greeny_description = greeny_get_theme_option( 'front_page_about_description' );
			if ( ! empty( $greeny_description ) || ( current_user_can( 'edit_theme_options' ) && is_customize_preview() ) ) {
				?>
				<div class="front_page_section_description front_page_section_about_description front_page_block_<?php echo ! empty( $greeny_description ) ? 'filled' : 'empty'; ?>"><?php echo wp_kses( wpautop( $greeny_description ), 'greeny_kses_content' ); ?></div>
				<?php
			}

			// Content
			$greeny_content = greeny_get_theme_option( 'front_page_about_content' );
			if ( ! empty( $greeny_content ) || ( current_user_can( 'edit_theme_options' ) && is_customize_preview() ) ) {
				?>
				<div class="front_page_section_content front_page_section_about_content front_page_block_<?php echo ! empty( $greeny_content ) ? 'filled' : 'empty'; ?>">
					<?php
					$greeny_page_content_mask = '%%CONTENT%%';
					if ( strpos( $greeny_content, $greeny_page_content_mask ) !== false ) {
						$greeny_content = preg_replace(
							'/(\<p\>\s*)?' . $greeny_page_content_mask . '(\s*\<\/p\>)/i',
							sprintf(
								'<div class="front_page_section_about_source">%s</div>',
								apply_filters( 'the_content', get_the_content() )
							),
							$greeny_content
						);
					}
					greeny_show_layout( $greeny_content );
					?>
				</div>
				<?php
			}
			?>
		</div>
	</div>
</div>
