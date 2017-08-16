<?php

/**
 * CMB2 Theme Options
 * @version 0.1.0
 */
class Mai_AEC_Display {

	/**
	 * Holds an instance of the object
	 *
	 * @var Mai_AEC_Display
	 */
	protected static $instance = null;

	/**
	 * Returns the running object
	 *
	 * @return Mai_AEC_Display
	 */
	public static function get_instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
			self::$instance->hooks();
		}

		return self::$instance;
	}

	/**
	 * Initiate our hooks
	 * @since 0.1.0
	 */
	public function hooks() {
		add_action( 'wp_head',                              array( $this, 'header' ) );
		add_action( 'wp_footer',                            array( $this, 'footer' ) );
		add_action( 'mai_header_before',                    array( $this, 'before_header' ) );
		add_action( 'mai_header_left',                      array( $this, 'header_left' ) );
		add_action( 'mai_header_right',                     array( $this, 'header_right' ) );
		add_action( 'genesis_before_content_sidebar_wrap',  array( $this, 'header_after' ), 12 );
		add_action( 'genesis_before_footer',                array( $this, 'before_footer' ) );
		add_action( 'genesis_before_entry',                 array( $this, 'before_entry' ) );
		add_action( 'genesis_entry_content',                array( $this, 'before_entry_content' ) );
		add_action( 'genesis_entry_content',                array( $this, 'entry_content' ) );
		add_action( 'genesis_entry_content',                array( $this, 'after_entry_content' ), 20 );
		add_action( 'genesis_after_entry',                  array( $this, 'after_entry_a' ), 4 );
		add_action( 'genesis_after_entry',                  array( $this, 'after_entry_b' ), 12 );
		add_action( 'genesis_after_loop',                   array( $this, 'after_entry_c' ) );
	}

	function header() {
		echo $this->display_header_footer( 'mai_ad_header', 'header' );
	}

	function footer() {
		echo $this->display_header_footer( 'mai_ad_footer', 'footer' );
	}

	function before_header() {
		echo $this->get_display_global( 'mai_ad_header_before', 'header_before' );
	}

	function header_left() {
		echo $this->get_display_global( 'mai_ad_header_left', 'header_left' );
	}

	function header_right() {
		echo $this->get_display_global( 'mai_ad_header_right', 'header_right' );
	}

	function header_after() {
		echo $this->get_display_global( 'mai_ad_header_after', 'header_after' );
	}

	function before_footer() {
		echo $this->get_display_global( 'mai_ad_before_footer', 'before_footer' );
	}

	function before_entry() {
		echo $this->get_display_singular( 'mai_ad_before_entry', 'before_entry' );
	}

	function before_entry_content() {
		echo $this->get_display_singular( 'mai_ad_before_entry_content', 'before_entry_content' );
	}

	function entry_content() {
		echo $this->get_display_singular_in_content( 'mai_ad_entry_content', 'entry_content' );
	}

	function after_entry_content() {
		echo $this->get_display_singular( 'mai_ad_after_entry_content', 'after_entry_content' );
	}

	function after_entry_a() {
		echo $this->get_display_singular( 'mai_ad_after_entry_a', 'after_entry_a' );
	}

	function after_entry_b() {
		echo $this->get_display_singular( 'mai_ad_after_entry_b', 'after_entry_b' );
	}

	function after_entry_c() {
		echo $this->get_display_singular( 'mai_ad_after_entry_c', 'after_entry_c' );
	}

	function display_header_footer( $key, $location ) {

		// Get the data
		$data = $this->get_option( $key, $location );

		// Bail if no data
		if ( ! $data ) {
			return;
		}

		// Display it!
		echo $data;
	}

	function get_display_global( $key, $location, $wrap = true ) {

		// Get the data
		$data = $this->get_option( $key, $location );

		// Bail if no data
		if ( ! $data ) {
			return;
		}

		// Build the HTML class name from the location
		$class = str_replace( '_', '-', $location );

		$wrap_open  = $wrap ? '<div class="wrap">' : '';
		$wrap_close = $wrap ? '</div>' : '';

		// Display it!
		return sprintf( '<div class="mai-aec mai-aec-%s">%s%s%s</div>', $class, $wrap_open, mai_get_processed_content( $data ), $wrap_close );
	}

	function get_display_singular( $key, $location ) {

		// Bail if not a singular post
		if ( ! is_singular() ) {
			return;
		}

		// Get the data
		$data = $this->get_option( $key, $location );

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
		return sprintf( '<div class="mai-aec mai-aec-%s"><div class="wrap">%s</div></div>', $class, mai_get_processed_content( $data[0]['content'] ) );
	}

	function get_display_singular_in_content( $key, $location ) {

		// Bail if not a singular post
		if ( ! is_singular() ) {
			return;
		}

		// Get the data
		$data = $this->get_option( $key, $location );

		// Bail if no data
		if ( ! $data ) {
			return;
		}

		// Display ad after 'n' paragraph
		add_filter( 'the_content', function( $content ) use ( $data, $location ) {

			// Bail if not the main query
			if ( ! is_main_query() ) {
				return $content;
			}

			// Loop through the ad locations
			foreach ( $data as $ad ) {

				// Bail if no post types available
				if ( ! isset( $ad['post_types'] ) ) {
					continue;
				}

				// Bail if not on the correct post type
				if ( ! in_array( get_post_type(), $ad['post_types'] ) ) {
					continue;
				}

				// Bail if no paragraph or no content
				if ( ! isset( $ad['count'] ) || ! isset( $ad['content'] ) ) {
					continue;
				}

				// Add the paragraphs
				$content = $this->get_content_with_ad_after_p( mai_get_processed_content( $ad['content'] ), $content, $ad['count'], $location );
			}

			return $content;
		});
	}

	/**
	 * Insert content after a specific paragraph
	 * When running on the_content, use priority > 20 so it doesn't affect oEmbed
	 *
	 * @link    http://www.wpbeginner.com/wp-tutorials/how-to-insert-ads-within-your-post-content-in-wordpress/
	 * @link    http://www.billerickson.net/code/insert-after-paragraph/
	 *
	 * @param   string  $new_content
	 * @param   string  $existing_content
	 * @param   int     $paragraph_number
	 *
	 * @return  string  The modified content
	 */
	function get_content_with_ad_after_p( $new_content, $existing_content, $paragraph_number, $location ) {

		// Get the paragraphs array prior to adding and ads
		$paragraphs = explode( '</p>', wpautop( $existing_content ) );

		// Build the HTML class name from the location
		$class = str_replace( '_', '-', $location );

		foreach ( $paragraphs as $index => $paragraph ) {
			if ( trim( $paragraph ) ) {
				$paragraphs[$index] .= '</p>';
			}
			if ( (int) $paragraph_number == $index + 1 ) {
				$paragraphs[$index] .= sprintf( '<div class="mai-aec mai-aec-%s"><div class="wrap">%s</div></div>', $class, mai_get_processed_content( $new_content ) );
			}
		}
		return implode( '', $paragraphs );
	}

	/**
	 * Helper function to get the aec data.
	 * Runs through a filter to allow devs to disable the display
	 * of all or specific ad locations.
	 *
	 * @param   string  $key       The option key to check.
	 * @param   string  $location  The ad location.
	 *
	 * @return  false|array        The ad data
	 */
	function get_option( $key, $location ) {
		if ( ! $this->is_display( $location ) ) {
			return false;
		}
		return maiaec_get_option( $key );
	}

	function is_display( $location ) {
		$display = apply_filters( 'mai_aec_display', true, $location );
		if ( ! $display ) {
			return false;
		}
		return true;
	}

}

/**
 * Helper function to get/return the Mai_AEC_Display object
 * @since  0.1.0
 * @return Mai_AEC_Display object
 */
function maiaec_display() {
	return Mai_AEC_Display::get_instance();
}

// Get it started
maiaec_display();
