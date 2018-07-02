<?php

// Hooks.
add_action( 'wp_enqueue_scripts',                   'maiaec_css', 1000 ); // Way late cause Engine changes stylesheet to 999.
add_action( 'wp_head',                              'maiaec_header' );
add_action( 'genesis_before',                       'maiaec_body' );
add_action( 'wp_footer',                            'maiaec_footer' );
add_action( 'genesis_header',                       'maiaec_before_header', 4 );
add_action( 'mai_header_left',                      'maiaec_header_left' );
add_action( 'genesis_header_right',                 'maiaec_genesis_header_right' );
add_action( 'mai_header_right',                     'maiaec_header_right' );
add_action( 'genesis_before_content_sidebar_wrap',  'maiaec_header_after', 12 );
add_action( 'genesis_before_footer',                'maiaec_before_footer' );
add_action( 'genesis_before_entry',                 'maiaec_before_entry' );
add_action( 'genesis_entry_content',                'maiaec_before_entry_content' );
add_action( 'genesis_entry_content',                'maiaec_entry_content' );
add_action( 'genesis_entry_content',                'maiaec_after_entry_content', 20 );
add_action( 'genesis_after_entry',                  'maiaec_after_entry_a', 4 );
add_action( 'genesis_after_entry',                  'maiaec_after_entry_b', 12 );
add_action( 'genesis_after_loop',                   'maiaec_after_entry_c' );

/**
 * Add inline CSS.
 *
 * @since 0.3.0
 *
 * @link  http://www.billerickson.net/code/enqueue-inline-styles/
 * @link  https://sridharkatakam.com/chevron-shaped-featured-parallax-section-in-genesis-using-clip-path/
 */
function maiaec_css() {
	$css = '
		.mai-aec > .wrap:empty {
			display: none;
		}
		.mai-aec > .wrap {
			margin-top: 16px;
			margin-bottom: 16px;
		}
		.mai-aec-header-before > .wrap {
			padding-left: 16px;
			padding-right: 16px;
		}
		.content .mai-aec > .wrap {
			padding-left: 0;
			padding-right: 0;
		}
		.mai-aec > .wrap > p:last-child {
			margin-bottom: 0;
		}
	';
	$handle = ( defined( 'CHILD_THEME_NAME' ) && CHILD_THEME_NAME ) ? sanitize_title_with_dashes( CHILD_THEME_NAME ) : 'child-theme';
	wp_add_inline_style( $handle, $css );
}

function maiaec_header() {
	echo maiaec_display_header_footer( 'mai_ad_header', 'header' );
}

function maiaec_body() {
	echo maiaec_display_header_footer( 'mai_ad_body', 'body' );
}

function maiaec_footer() {
	echo maiaec_display_header_footer( 'mai_ad_footer', 'footer' );
}

function maiaec_before_header() {
	echo maiaec_get_display_global( 'mai_ad_header_before', 'header_before' );
}

function maiaec_header_left() {
	echo maiaec_get_display_global( 'mai_ad_header_left', 'header_left' );
}

function maiaec_genesis_header_right() {
	if ( class_exists( 'Mai_Theme_Engine' ) ) {
		return;
	}
	echo maiaec_get_display_global( 'mai_ad_header_right', 'header_right' );
}

function maiaec_header_right() {
	echo maiaec_get_display_global( 'mai_ad_header_right', 'header_right' );
}

function maiaec_header_after() {
	echo maiaec_get_display_global( 'mai_ad_header_after', 'header_after' );
}

function maiaec_before_footer() {
	echo maiaec_get_display_global( 'mai_ad_before_footer', 'before_footer' );
}

function maiaec_before_entry() {
	echo maiaec_get_display_singular( 'mai_ad_before_entry', 'before_entry' );
}

function maiaec_before_entry_content() {
	echo maiaec_get_display_singular( 'mai_ad_before_entry_content', 'before_entry_content' );
}

function maiaec_entry_content() {
	echo maiaec_get_display_singular_in_content( 'mai_ad_entry_content', 'entry_content' );
}

function maiaec_after_entry_content() {
	echo maiaec_get_display_singular( 'mai_ad_after_entry_content', 'after_entry_content' );
}

function maiaec_after_entry_a() {
	echo maiaec_get_display_singular( 'mai_ad_after_entry_a', 'after_entry_a' );
}

function maiaec_after_entry_b() {
	echo maiaec_get_display_singular( 'mai_ad_after_entry_b', 'after_entry_b' );
}

function maiaec_after_entry_c() {
	echo maiaec_get_display_singular( 'mai_ad_after_entry_c', 'after_entry_c' );
}

/**
 * Get header/footer code.
 *
 * @access  private
 *
 * @return  string  mixed|HTML
 */
function maiaec_display_header_footer( $key, $location ) {

	// Get the data
	$data = maiaec_maybe_get_option( $key, $location );

	// Bail if no data
	if ( ! $data ) {
		return;
	}

	// Display it! Parse shortcodes so cookie notice shortcode can work ;)
	echo do_shortcode( $data );
}

/**
 * Get global ads.
 *
 * @access  private
 *
 * @return  mixed  string|HTML
 */
function maiaec_get_display_global( $key, $location, $wrap = true ) {

	// Get the data
	$data = maiaec_maybe_get_option( $key, $location );

	// Bail if no data
	if ( ! $data ) {
		return;
	}

	// Build the HTML class name from the location
	$class = str_replace( '_', '-', $location );

	$wrap_open  = $wrap ? '<div class="wrap">' : '';
	$wrap_close = $wrap ? '</div>' : '';

	// Display it!
	return sprintf( '<div class="mai-aec mai-aec-%s">%s%s%s</div>', $class, $wrap_open, maiaec_get_processed_content( $data ), $wrap_close );
}

/**
 * Get ads on singular content.
 *
 * @access  private
 *
 * @return  mixed  string|HTML
 */
function maiaec_get_display_singular( $key, $location ) {

	// Bail if not a singular post
	if ( ! is_singular() ) {
		return;
	}

	// Get the data
	$data = maiaec_maybe_get_option( $key, $location );

	// Bail if no data
	if ( ! $data ) {
		return;
	}

	// Bail if no content
	if ( empty( $data[0]['content'] ) ) {
		return;
	}

	// Bail if no post types available
	if ( ! isset( $data[0]['post_types'] ) ) {
		return;
	}

	// Bail if not on the correct post type
	if ( ! in_array( get_post_type(), $data[0]['post_types'] ) ) {
		return;
	}

	// Build the HTML class name from the location
	$class = str_replace( '_', '-', $location );

	// Display it
	return sprintf( '<div class="mai-aec mai-aec-%s"><div class="wrap">%s</div></div>', $class, maiaec_get_processed_content( $data[0]['content'] ) );
}

/**
 * Get the content with ads after paragraphs.
 *
 * @access  private
 *
 * @return  mixed  string|HTML
 */
function maiaec_get_display_singular_in_content( $key, $location ) {

	// Bail if not a singular post.
	if ( ! is_singular() ) {
		return;
	}

	// Get the data.
	$data = maiaec_maybe_get_option( $key, $location );

	// Bail if no data.
	if ( ! $data ) {
		return;
	}

	// Start the ads array.
	$ads = array();

	foreach ( $data as $ad ) {

		// Bail if no post types available.
		if ( ! isset( $ad['post_types'] ) || ! $ad['post_types'] ) {
			continue;
		}

		// Bail if not on the correct post type.
		if ( ! in_array( get_post_type(), $ad['post_types'] ) ) {
			continue;
		}

		// Bail if no paragraph or no content.
		if ( ! isset( $ad['count'] ) || ! isset( $ad['content'] ) ) {
			continue;
		}

		// Add count and content to the ads array.
		$ads[ (int) $ad['count'] ] = sprintf( '<div class="mai-aec mai-aec-entry-content"><div class="wrap">%s</div></div>', $ad['content']	);
	}

	// Bail if no ads.
	if ( empty( $ads ) ) {
		return;
	}

	// Add placeholder after 'n' paragraph.
	add_filter( 'the_content', function( $content ) use ( $ads, $location ) {

		// Bail if not the main query.
		if ( ! is_main_query() ) {
			return $content;
		}

		// Create the new document.
		$dom = new DOMDocument;

		// Not sure we need these.
		$dom->preserveWhiteSpace = false;
		$dom->formatOutput       = true;

		/**
		 * LIBXML_HTML_NOIMPLIED turns off the automatic adding of implied html/body elements.
		 * LIBXML_HTML_NODEFDTD prevents a default doctype being added when one is not found.
		 */
		$dom->loadHTML( $content, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD );

		$new_content = '';

		// Loop through the nodes.
		foreach( $dom->documentElement->childNodes as $index => $node ) {

			// The item number. The first content item is 1, not 0.
			$item = $index + 1;

			// If we have content after this item, add it.
			if ( isset( $ads[ $item ] ) && ! empty( $ads[ $item ] ) ) {
				$new_content .= $dom->saveHTML( $node ) . $ads[ $item ];
			}
			else {
				$new_content .= $dom->saveHTML( $node );
			}
		}

		return maiaec_get_processed_content( $new_content );

	}, 30, 1 );
}

/**
 * Helper function maiaec_to get the aec data.
 * Runs through a filter to allow devs to disable the display
 * of all or specific ad locations.
 *
 * @access  private
 *
 * @param   string  $key       The option key to check.
 * @param   string  $location  The ad location.
 *
 * @return  false|array        The ad data
 */
function maiaec_maybe_get_option( $key, $location ) {
	if ( ! maiaec_is_display( $location ) ) {
		return false;
	}
	return maiaec_get_option( $key );
}

/**
 * Check if a field is being displayed.
 *
 * @access  private
 *
 * @return  bool
 */
function maiaec_is_display( $location ) {
	$display = apply_filters( 'mai_aec_display', true, $location );
	if ( ! $display ) {
		return false;
	}
	return true;
}

/**
 * Get processed content.
 *
 * @access  private
 *
 * @return  mixed string|HTML
 */
function maiaec_get_processed_content( $content ) {
	if ( class_exists( 'Mai_Theme_Engine' ) ) {
		return mai_get_processed_content( $content );
	} else {
		global $wp_embed;
		$content = trim( $content );
		$content = wptexturize( $content );
		$content = wpautop( $content );
		$content = shortcode_unautop( $content );
		$content = do_shortcode( $content );
		$content = convert_smilies( $content );
		$content = $wp_embed->autoembed( $content );
		$content = $wp_embed->run_shortcode( $content );
		return $content;
	}
}
