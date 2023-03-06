<?php
/**
 * The template to display the page title and breadcrumbs
 *
 * @package GREENY
 * @since GREENY 1.0
 */

// Page (category, tag, archive, author) title

if ( greeny_need_page_title() ) {
	greeny_sc_layouts_showed( 'title', true );
	greeny_sc_layouts_showed( 'postmeta', true );
	?>
	<div class="top_panel_title sc_layouts_row sc_layouts_row_type_normal">
		<div class="content_wrap">
			<div class="sc_layouts_column sc_layouts_column_align_center">
				<div class="sc_layouts_item">
					<div class="sc_layouts_title sc_align_center">
						<?php
						// Post meta on the single post
						if ( is_single() ) {
							?>
							<div class="sc_layouts_title_meta">
							<?php
								greeny_show_post_meta(
									apply_filters(
										'greeny_filter_post_meta_args', array(
											'components' => join( ',', greeny_array_get_keys_by_value( greeny_get_theme_option( 'meta_parts' ) ) ),
											'counters'   => join( ',', greeny_array_get_keys_by_value( greeny_get_theme_option( 'counters' ) ) ),
											'seo'        => greeny_is_on( greeny_get_theme_option( 'seo_snippets' ) ),
										), 'header', 1
									)
								);
							?>
							</div>
							<?php
						}

						// Blog/Post title
						?>
						<div class="sc_layouts_title_title">
							<?php
							$greeny_blog_title           = greeny_get_blog_title();
							$greeny_blog_title_text      = '';
							$greeny_blog_title_class     = '';
							$greeny_blog_title_link      = '';
							$greeny_blog_title_link_text = '';
							if ( is_array( $greeny_blog_title ) ) {
								$greeny_blog_title_text      = $greeny_blog_title['text'];
								$greeny_blog_title_class     = ! empty( $greeny_blog_title['class'] ) ? ' ' . $greeny_blog_title['class'] : '';
								$greeny_blog_title_link      = ! empty( $greeny_blog_title['link'] ) ? $greeny_blog_title['link'] : '';
								$greeny_blog_title_link_text = ! empty( $greeny_blog_title['link_text'] ) ? $greeny_blog_title['link_text'] : '';
							} else {
								$greeny_blog_title_text = $greeny_blog_title;
							}
							?>
							<h1 itemprop="headline" class="sc_layouts_title_caption<?php echo esc_attr( $greeny_blog_title_class ); ?>">
								<?php
								$greeny_top_icon = greeny_get_term_image_small();
								if ( ! empty( $greeny_top_icon ) ) {
									$greeny_attr = greeny_getimagesize( $greeny_top_icon );
									?>
									<img src="<?php echo esc_url( $greeny_top_icon ); ?>" alt="<?php esc_attr_e( 'Site icon', 'greeny' ); ?>"
										<?php
										if ( ! empty( $greeny_attr[3] ) ) {
											greeny_show_layout( $greeny_attr[3] );
										}
										?>
									>
									<?php
								}
								echo wp_kses_data( $greeny_blog_title_text );
								?>
							</h1>
							<?php
							if ( ! empty( $greeny_blog_title_link ) && ! empty( $greeny_blog_title_link_text ) ) {
								?>
								<a href="<?php echo esc_url( $greeny_blog_title_link ); ?>" class="theme_button theme_button_small sc_layouts_title_link"><?php echo esc_html( $greeny_blog_title_link_text ); ?></a>
								<?php
							}

							// Category/Tag description
							if ( ! is_paged() && ( is_category() || is_tag() || is_tax() ) ) {
								the_archive_description( '<div class="sc_layouts_title_description">', '</div>' );
							}

							?>
						</div>
						<?php

						// Breadcrumbs
						ob_start();
						do_action( 'greeny_action_breadcrumbs' );
						$greeny_breadcrumbs = ob_get_contents();
						ob_end_clean();
						greeny_show_layout( $greeny_breadcrumbs, '<div class="sc_layouts_title_breadcrumbs">', '</div>' );
						?>
					</div>
				</div>
			</div>
		</div>
	</div>
	<?php
}
