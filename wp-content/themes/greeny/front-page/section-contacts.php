<div class="front_page_section front_page_section_contacts<?php
	$greeny_scheme = greeny_get_theme_option( 'front_page_contacts_scheme' );
	if ( ! empty( $greeny_scheme ) && ! greeny_is_inherit( $greeny_scheme ) ) {
		echo ' scheme_' . esc_attr( $greeny_scheme );
	}
	echo ' front_page_section_paddings_' . esc_attr( greeny_get_theme_option( 'front_page_contacts_paddings' ) );
	if ( greeny_get_theme_option( 'front_page_contacts_stack' ) ) {
		echo ' sc_stack_section_on';
	}
?>"
		<?php
		$greeny_css      = '';
		$greeny_bg_image = greeny_get_theme_option( 'front_page_contacts_bg_image' );
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
	$greeny_anchor_icon = greeny_get_theme_option( 'front_page_contacts_anchor_icon' );
	$greeny_anchor_text = greeny_get_theme_option( 'front_page_contacts_anchor_text' );
if ( ( ! empty( $greeny_anchor_icon ) || ! empty( $greeny_anchor_text ) ) && shortcode_exists( 'trx_sc_anchor' ) ) {
	echo do_shortcode(
		'[trx_sc_anchor id="front_page_section_contacts"'
									. ( ! empty( $greeny_anchor_icon ) ? ' icon="' . esc_attr( $greeny_anchor_icon ) . '"' : '' )
									. ( ! empty( $greeny_anchor_text ) ? ' title="' . esc_attr( $greeny_anchor_text ) . '"' : '' )
									. ']'
	);
}
?>
	<div class="front_page_section_inner front_page_section_contacts_inner
	<?php
	if ( greeny_get_theme_option( 'front_page_contacts_fullheight' ) ) {
		echo ' greeny-full-height sc_layouts_flex sc_layouts_columns_middle';
	}
	?>
			"
			<?php
			$greeny_css      = '';
			$greeny_bg_mask  = greeny_get_theme_option( 'front_page_contacts_bg_mask' );
			$greeny_bg_color_type = greeny_get_theme_option( 'front_page_contacts_bg_color_type' );
			if ( 'custom' == $greeny_bg_color_type ) {
				$greeny_bg_color = greeny_get_theme_option( 'front_page_contacts_bg_color' );
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
		<div class="front_page_section_content_wrap front_page_section_contacts_content_wrap content_wrap">
			<?php

			// Title and description
			$greeny_caption     = greeny_get_theme_option( 'front_page_contacts_caption' );
			$greeny_description = greeny_get_theme_option( 'front_page_contacts_description' );
			if ( ! empty( $greeny_caption ) || ! empty( $greeny_description ) || ( current_user_can( 'edit_theme_options' ) && is_customize_preview() ) ) {
				// Caption
				if ( ! empty( $greeny_caption ) || ( current_user_can( 'edit_theme_options' ) && is_customize_preview() ) ) {
					?>
					<h2 class="front_page_section_caption front_page_section_contacts_caption front_page_block_<?php echo ! empty( $greeny_caption ) ? 'filled' : 'empty'; ?>">
					<?php
						echo wp_kses( $greeny_caption, 'greeny_kses_content' );
					?>
					</h2>
					<?php
				}

				// Description
				if ( ! empty( $greeny_description ) || ( current_user_can( 'edit_theme_options' ) && is_customize_preview() ) ) {
					?>
					<div class="front_page_section_description front_page_section_contacts_description front_page_block_<?php echo ! empty( $greeny_description ) ? 'filled' : 'empty'; ?>">
					<?php
						echo wp_kses( wpautop( $greeny_description ), 'greeny_kses_content' );
					?>
					</div>
					<?php
				}
			}

			// Content (text)
			$greeny_content = greeny_get_theme_option( 'front_page_contacts_content' );
			$greeny_layout  = greeny_get_theme_option( 'front_page_contacts_layout' );
			if ( 'columns' == $greeny_layout && ( ! empty( $greeny_content ) || ( current_user_can( 'edit_theme_options' ) && is_customize_preview() ) ) ) {
				?>
				<div class="front_page_section_columns front_page_section_contacts_columns columns_wrap">
					<div class="column-1_3">
				<?php
			}

			if ( ( ! empty( $greeny_content ) || ( current_user_can( 'edit_theme_options' ) && is_customize_preview() ) ) ) {
				?>
				<div class="front_page_section_content front_page_section_contacts_content front_page_block_<?php echo ! empty( $greeny_content ) ? 'filled' : 'empty'; ?>">
					<?php
					echo wp_kses( $greeny_content, 'greeny_kses_content' );
					?>
				</div>
				<?php
			}

			if ( 'columns' == $greeny_layout && ( ! empty( $greeny_content ) || ( current_user_can( 'edit_theme_options' ) && is_customize_preview() ) ) ) {
				?>
				</div><div class="column-2_3">
				<?php
			}

			// Shortcode output
			$greeny_sc = greeny_get_theme_option( 'front_page_contacts_shortcode' );
			if ( ! empty( $greeny_sc ) || ( current_user_can( 'edit_theme_options' ) && is_customize_preview() ) ) {
				?>
				<div class="front_page_section_output front_page_section_contacts_output front_page_block_<?php echo ! empty( $greeny_sc ) ? 'filled' : 'empty'; ?>">
					<?php
					greeny_show_layout( do_shortcode( $greeny_sc ) );
					?>
				</div>
				<?php
			}

			if ( 'columns' == $greeny_layout && ( ! empty( $greeny_content ) || ( current_user_can( 'edit_theme_options' ) && is_customize_preview() ) ) ) {
				?>
				</div></div>
				<?php
			}
			?>

		</div>
	</div>
</div>
