<?php
$greeny_woocommerce_sc = greeny_get_theme_option( 'front_page_woocommerce_products' );
if ( ! empty( $greeny_woocommerce_sc ) ) {
	?><div class="front_page_section front_page_section_woocommerce<?php
		$greeny_scheme = greeny_get_theme_option( 'front_page_woocommerce_scheme' );
		if ( ! empty( $greeny_scheme ) && ! greeny_is_inherit( $greeny_scheme ) ) {
			echo ' scheme_' . esc_attr( $greeny_scheme );
		}
		echo ' front_page_section_paddings_' . esc_attr( greeny_get_theme_option( 'front_page_woocommerce_paddings' ) );
		if ( greeny_get_theme_option( 'front_page_woocommerce_stack' ) ) {
			echo ' sc_stack_section_on';
		}
	?>"
			<?php
			$greeny_css      = '';
			$greeny_bg_image = greeny_get_theme_option( 'front_page_woocommerce_bg_image' );
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
		$greeny_anchor_icon = greeny_get_theme_option( 'front_page_woocommerce_anchor_icon' );
		$greeny_anchor_text = greeny_get_theme_option( 'front_page_woocommerce_anchor_text' );
		if ( ( ! empty( $greeny_anchor_icon ) || ! empty( $greeny_anchor_text ) ) && shortcode_exists( 'trx_sc_anchor' ) ) {
			echo do_shortcode(
				'[trx_sc_anchor id="front_page_section_woocommerce"'
											. ( ! empty( $greeny_anchor_icon ) ? ' icon="' . esc_attr( $greeny_anchor_icon ) . '"' : '' )
											. ( ! empty( $greeny_anchor_text ) ? ' title="' . esc_attr( $greeny_anchor_text ) . '"' : '' )
											. ']'
			);
		}
	?>
		<div class="front_page_section_inner front_page_section_woocommerce_inner
			<?php
			if ( greeny_get_theme_option( 'front_page_woocommerce_fullheight' ) ) {
				echo ' greeny-full-height sc_layouts_flex sc_layouts_columns_middle';
			}
			?>
				"
				<?php
				$greeny_css      = '';
				$greeny_bg_mask  = greeny_get_theme_option( 'front_page_woocommerce_bg_mask' );
				$greeny_bg_color_type = greeny_get_theme_option( 'front_page_woocommerce_bg_color_type' );
				if ( 'custom' == $greeny_bg_color_type ) {
					$greeny_bg_color = greeny_get_theme_option( 'front_page_woocommerce_bg_color' );
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
			<div class="front_page_section_content_wrap front_page_section_woocommerce_content_wrap content_wrap woocommerce">
				<?php
				// Content wrap with title and description
				$greeny_caption     = greeny_get_theme_option( 'front_page_woocommerce_caption' );
				$greeny_description = greeny_get_theme_option( 'front_page_woocommerce_description' );
				if ( ! empty( $greeny_caption ) || ! empty( $greeny_description ) || ( current_user_can( 'edit_theme_options' ) && is_customize_preview() ) ) {
					// Caption
					if ( ! empty( $greeny_caption ) || ( current_user_can( 'edit_theme_options' ) && is_customize_preview() ) ) {
						?>
						<h2 class="front_page_section_caption front_page_section_woocommerce_caption front_page_block_<?php echo ! empty( $greeny_caption ) ? 'filled' : 'empty'; ?>">
						<?php
							echo wp_kses( $greeny_caption, 'greeny_kses_content' );
						?>
						</h2>
						<?php
					}

					// Description (text)
					if ( ! empty( $greeny_description ) || ( current_user_can( 'edit_theme_options' ) && is_customize_preview() ) ) {
						?>
						<div class="front_page_section_description front_page_section_woocommerce_description front_page_block_<?php echo ! empty( $greeny_description ) ? 'filled' : 'empty'; ?>">
						<?php
							echo wp_kses( wpautop( $greeny_description ), 'greeny_kses_content' );
						?>
						</div>
						<?php
					}
				}

				// Content (widgets)
				?>
				<div class="front_page_section_output front_page_section_woocommerce_output list_products shop_mode_thumbs">
					<?php
					if ( 'products' == $greeny_woocommerce_sc ) {
						$greeny_woocommerce_sc_ids      = greeny_get_theme_option( 'front_page_woocommerce_products_per_page' );
						$greeny_woocommerce_sc_per_page = count( explode( ',', $greeny_woocommerce_sc_ids ) );
					} else {
						$greeny_woocommerce_sc_per_page = max( 1, (int) greeny_get_theme_option( 'front_page_woocommerce_products_per_page' ) );
					}
					$greeny_woocommerce_sc_columns = max( 1, min( $greeny_woocommerce_sc_per_page, (int) greeny_get_theme_option( 'front_page_woocommerce_products_columns' ) ) );
					echo do_shortcode(
						"[{$greeny_woocommerce_sc}"
										. ( 'products' == $greeny_woocommerce_sc
												? ' ids="' . esc_attr( $greeny_woocommerce_sc_ids ) . '"'
												: '' )
										. ( 'product_category' == $greeny_woocommerce_sc
												? ' category="' . esc_attr( greeny_get_theme_option( 'front_page_woocommerce_products_categories' ) ) . '"'
												: '' )
										. ( 'best_selling_products' != $greeny_woocommerce_sc
												? ' orderby="' . esc_attr( greeny_get_theme_option( 'front_page_woocommerce_products_orderby' ) ) . '"'
													. ' order="' . esc_attr( greeny_get_theme_option( 'front_page_woocommerce_products_order' ) ) . '"'
												: '' )
										. ' per_page="' . esc_attr( $greeny_woocommerce_sc_per_page ) . '"'
										. ' columns="' . esc_attr( $greeny_woocommerce_sc_columns ) . '"'
						. ']'
					);
					?>
				</div>
			</div>
		</div>
	</div>
	<?php
}
