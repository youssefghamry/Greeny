/* global jQuery:false */
/* global GREENY_STORAGE:false */

jQuery( window ).on( 'load', function() {

	"use strict";

 	greeny_gutenberg_first_init();

	if ( typeof window.MutationObserver !== 'undefined' ) {
		var $editor_wrapper = jQuery('#editor,#site-editor,#edit-site-editor,#widgets-editor').eq(0);
		if ( $editor_wrapper.length ) {
			// Create the observer to reinit visual editor after switch from code editor to visual editor
			greeny_create_observer( 'check_visual_editor_wrapper', $editor_wrapper, function( mutationsList ) {
				var $editor = greeny_gutenberg_editor_object();
				if ( $editor.length ) {
					greeny_gutenberg_first_init( $editor );
				}
			} );
			// Create the observer to add class 'scheme_xxx' to the each widgets area in the Widgets Block Editor
			if ( $editor_wrapper.attr( 'id' ) == 'widgets-editor' ) {
				greeny_create_observer( 'check_editor_styles_wrapper', $editor_wrapper, function( mutationsList ) {
					var $styles_wrapper = $editor_wrapper.find( '.editor-styles-wrapper:not([class*="scheme_"])' );
					if ( $styles_wrapper.length ) {
						$styles_wrapper.addClass( 'scheme_' + GREENY_STORAGE['color_scheme'] );
					} else {
						greeny_remove_observer( 'check_editor_styles_wrapper' );
					}
				} );
			}
		}
	}

	// Return Gutenberg editor object
	function greeny_gutenberg_editor_object() {
		// Get Post Editor
		var $editor = jQuery( '.edit-post-visual-editor:not(.greeny_inited)' ).eq( 0 );
		if ( ! $editor.length ) {
			// Check if Full Site Editor exists
			var $editor_frame = jQuery( 'iframe[name="editor-canvas"]' );
			if ( $editor_frame.length ) {
				$editor_frame = jQuery( $editor_frame.get(0).contentDocument.body );
				if ( $editor_frame.hasClass('editor-styles-wrapper') && ! $editor_frame.hasClass('greeny_inited') ) {
					$editor = $editor_frame;
				}
			} else {
				// Check if Widgets Editor exists
				$editor = jQuery( '.edit-widgets-block-editor:not(.greeny_inited)' ).eq( 0 );
			}
		}
		return $editor;
	}

	// Init on page load
	function greeny_gutenberg_first_init( $editor ) {

		// Get Gutenberg editor object
		if ( ! $editor ) {
			$editor = greeny_gutenberg_editor_object();
			if ( ! $editor.length ) {
				return;
			}
		}

		var old_GB = $editor.hasClass( 'editor-styles-wrapper' ) && $editor.hasClass( 'edit-post-visual-editor' ),
			widgets_GB = $editor.hasClass( 'edit-widgets-block-editor' ),
			fse_GB = $editor.is( 'body' ),
			styles_wrapper  = old_GB || $editor.hasClass( 'editor-styles-wrapper' )
								? $editor
								: $editor.find( '.editor-styles-wrapper' ),
			writing_flow    = $editor.find( '.block-editor-writing-flow' ),
			sidebar_wrapper = old_GB
								? $editor
								: writing_flow;

		// Add color scheme to the editor and to the wrapper '.block-editor-writing-flow' (instead '.block-editor-block-list__layout')
		styles_wrapper.addClass( 'scheme_' + GREENY_STORAGE['color_scheme'] );
		writing_flow.addClass( 'scheme_' + GREENY_STORAGE['color_scheme'] );

		if ( ! widgets_GB ) {
			if ( ! fse_GB ) {
				// Copy post-type to the styles_wrapper
				styles_wrapper.addClass( greeny_get_class_by_prefix( $editor.attr('class'), 'post-type-' ) );
				// Decorate sidebar placeholder
				styles_wrapper
					.addClass( 'sidebar_position_' + GREENY_STORAGE['sidebar_position'] )
					.addClass( GREENY_STORAGE['expand_content'] + '_content' );
				if ( GREENY_STORAGE['sidebar_position'] == 'left' && old_GB ) {
					sidebar_wrapper.prepend( '<div class="editor-post-sidebar-holder"></div>' );
				} else if ( GREENY_STORAGE['sidebar_position'] != 'hide' ) {
					sidebar_wrapper.append( '<div class="editor-post-sidebar-holder"></div>' );
				}
			} else {
				styles_wrapper.addClass( 'full_site_editor_present sidebar_right body_style_wide' );
			}
		}

		$editor.addClass('greeny_inited');
	}
} );
