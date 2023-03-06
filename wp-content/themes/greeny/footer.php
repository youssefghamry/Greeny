<?php
/**
 * The Footer: widgets area, logo, footer menu and socials
 *
 * @package GREENY
 * @since GREENY 1.0
 */

							do_action( 'greeny_action_page_content_end_text' );
							
							// Widgets area below the content
							greeny_create_widgets_area( 'widgets_below_content' );
						
							do_action( 'greeny_action_page_content_end' );
							?>
						</div>
						<?php

						// Show main sidebar
						get_sidebar();
						?>
					</div>
					<?php

					do_action( 'greeny_action_after_content_wrap' );

					// Widgets area below the page and related posts below the page
					$greeny_body_style = greeny_get_theme_option( 'body_style' );
					$greeny_widgets_name = greeny_get_theme_option( 'widgets_below_page' );
					$greeny_show_widgets = ! greeny_is_off( $greeny_widgets_name ) && is_active_sidebar( $greeny_widgets_name );
					$greeny_show_related = greeny_is_single() && greeny_get_theme_option( 'related_position' ) == 'below_page';
					if ( $greeny_show_widgets || $greeny_show_related ) {
						if ( 'fullscreen' != $greeny_body_style ) {
							?>
							<div class="content_wrap">
							<?php
						}
						// Show related posts before footer
						if ( $greeny_show_related ) {
							do_action( 'greeny_action_related_posts' );
						}

						// Widgets area below page content
						if ( $greeny_show_widgets ) {
							greeny_create_widgets_area( 'widgets_below_page' );
						}
						if ( 'fullscreen' != $greeny_body_style ) {
							?>
							</div>
							<?php
						}
					}
					do_action( 'greeny_action_page_content_wrap_end' );
					?>
			</div>
			<?php
			do_action( 'greeny_action_after_page_content_wrap' );

			// Don't display the footer elements while actions 'full_post_loading' and 'prev_post_loading'
			if ( ( ! greeny_is_singular( 'post' ) && ! greeny_is_singular( 'attachment' ) ) || ! in_array ( greeny_get_value_gp( 'action' ), array( 'full_post_loading', 'prev_post_loading' ) ) ) {
				
				// Skip link anchor to fast access to the footer from keyboard
				?>
				<a id="footer_skip_link_anchor" class="greeny_skip_link_anchor" href="#"></a>
				<?php

				do_action( 'greeny_action_before_footer' );

				// Footer
				$greeny_footer_type = greeny_get_theme_option( 'footer_type' );
				if ( 'custom' == $greeny_footer_type && ! greeny_is_layouts_available() ) {
					$greeny_footer_type = 'default';
				}
				get_template_part( apply_filters( 'greeny_filter_get_template_part', "templates/footer-" . sanitize_file_name( $greeny_footer_type ) ) );

				do_action( 'greeny_action_after_footer' );

			}
			?>

			<?php do_action( 'greeny_action_page_wrap_end' ); ?>

		</div>

		<?php do_action( 'greeny_action_after_page_wrap' ); ?>

	</div>

	<?php do_action( 'greeny_action_after_body' ); ?>

	<?php wp_footer(); ?>

</body>
</html>