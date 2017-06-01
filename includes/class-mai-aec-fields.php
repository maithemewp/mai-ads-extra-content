<?php

/**
 * CMB2 Theme Options
 * @version 0.1.0
 */
class Mai_AEC_Fields {

	/**
 	 * Option key, and option page slug
 	 * @var string
 	 */
	protected $key = 'mai_aec';

	/**
 	 * Options page metabox id
 	 * @var string
 	 */
	protected $metabox_id = 'mai_aec_metabox';

	/**
	 * Options menu title
	 * @var string
	 */
	protected $menu_title = '';

	/**
	 * Options page title
	 * @var string
	 */
	protected $page_title = '';

	/**
	 * Options Page hook
	 * @var string
	 */
	protected $options_page = '';

	/**
	 * Holds an instance of the object
	 *
	 * @var Mai_AEC_Fields
	 */
	protected static $instance = null;

	/**
	 * Returns the running object
	 *
	 * @return Mai_AEC_Fields
	 */
	public static function get_instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
			self::$instance->hooks();
		}

		return self::$instance;
	}

	/**
	 * Constructor
	 * @since 0.1.0
	 */
	protected function __construct() {
		$this->page_title = __( 'Ads & Extra Content', 'mai-aec' );
		$this->menu_title = __( 'Ads & Extra Content', 'mai-aec' );
	}

	/**
	 * Initiate our hooks
	 * @since 0.1.0
	 */
	public function hooks() {
		add_action( 'admin_init', 		array( $this, 'init' ) );
		add_action( 'admin_menu', 		array( $this, 'add_options_page' ) );
		add_action( 'cmb2_admin_init',	array( $this, 'add_options_page_metabox' ) );
	}


	/**
	 * Register our setting to WP
	 * @since  0.1.0
	 */
	public function init() {
		register_setting( $this->key, $this->key );
	}

	/**
	 * Add menu options page
	 * @since 0.1.0
	 */
	public function add_options_page() {
		// $this->options_page = add_menu_page( $this->menu_title, $this->menu_title, 'manage_options', $this->key, array( $this, 'admin_page_display' ) );
		$this->options_page = add_submenu_page( 'genesis', $this->page_title, $this->menu_title, 'manage_options', $this->key, array( $this, 'admin_page_display' ) );

		// Include CMB CSS in the head to avoid FOUC
		add_action( "admin_print_styles-{$this->options_page}", array( 'CMB2_hookup', 'enqueue_cmb_css' ) );
	}

	/**
	 * Admin page markup. Mostly handled by CMB2
	 * @since  0.1.0
	 */
	public function admin_page_display() {
		?>
		<div class="wrap cmb2-options-page <?php echo $this->key; ?>">
			<h2><?php echo esc_html( get_admin_page_title() ); ?></h2>
			<?php cmb2_metabox_form( $this->metabox_id, $this->key ); ?>
		</div>
		<?php
	}

	/**
	 * Add the options metabox to the array of metaboxes
	 * @since  0.1.0
	 */
	function add_options_page_metabox() {

		// hook in our save notices
		add_action( "cmb2_save_options-page_fields_{$this->metabox_id}", array( $this, 'settings_notices' ), 10, 2 );

		$cmb = new_cmb2_box( array(
			'id'         => $this->metabox_id,
			'hookup'     => false,
			'cmb_styles' => false,
			'show_on'    => array(
				// These are important, don't remove
				'key'   => 'options-page',
				'value' => array( $this->key, )
			),
		) );

		// Set our CMB2 fields

		$cmb->add_field( array(
			'name'	=> __( 'Header/Footer Code', 'mai-aec' ),
			'desc'	=> __( 'This is the header and footer code, not a display area', 'mai-aec' ),
			'id'	=> 'mai_ad_code_title',
			'type'	=> 'title',
		) );

		$cmb->add_field( array(
			'name' => __( 'Header Code', 'mai-aec' ),
			'id'   => 'mai_ad_header',
			'type' => 'textarea_code',
		) );

		$cmb->add_field( array(
			'name' => __( 'Footer Code', 'mai-aec' ),
			'id'   => 'mai_ad_footer',
			'type' => 'textarea_code',
		) );

		$cmb->add_field( array(
			'name'	=> __( 'Global Ad & Content Areas', 'mai-aec' ),
			'desc'	=> __( 'These are the custom ad and content display areas displayed on every page of the site', 'mai-aec' ),
			'id'	=> 'mai_ad_global_content_title',
			'type'	=> 'title',
		) );

		$cmb->add_field( array(
			'name'		=> __( 'Before Header', 'mai-aec' ),
			'id'		=> 'mai_ad_header_before',
			'type'		=> 'wysiwyg',
			'options'	=> array(
				'wpautop'		=> true,
				'media_buttons'	=> true,
				'textarea_rows'	=> 6,
			),
		) );

		$cmb->add_field( array(
			'name'		=> __( 'Header Left', 'mai-aec' ),
			'id'		=> 'mai_ad_header_left',
			'type'		=> 'wysiwyg',
			'options'	=> array(
				'wpautop'		=> true,
				'media_buttons'	=> true,
				'textarea_rows'	=> 6,
			),
		) );

		$cmb->add_field( array(
			'name'		=> __( 'Header Right', 'mai-aec' ),
			'id'		=> 'mai_ad_header_right',
			'type'		=> 'wysiwyg',
			'options'	=> array(
				'wpautop'		=> true,
				'media_buttons'	=> true,
				'textarea_rows'	=> 6,
			),
		) );

		$cmb->add_field( array(
			'name'		=> __( 'After Header', 'mai-aec' ),
			'id'		=> 'mai_ad_header_after',
			'type'		=> 'wysiwyg',
			'options'	=> array(
				'wpautop'		=> true,
				'media_buttons'	=> true,
				'textarea_rows'	=> 6,
			),
		) );

		$cmb->add_field( array(
			'name'	=> __( 'Entry Ad & Content Areas', 'mai-aec' ),
			'desc'	=> __( 'These are the custom ad and content display areas displayed on single entries', 'mai-aec' ),
			'id'	=> 'mai_ad_entry_content_title',
			'type'	=> 'title',
		) );

		$before_entry = $cmb->add_field( array(
			'id'		 => 'mai_ad_before_entry',
			'type'		 => 'group',
			'repeatable' => false,
			'options'	 => array(
				'group_title' => __( 'Before Entry', 'mai-aec' ),
			),
		) );
		$cmb->add_group_field( $before_entry, $this->get_group_post_type_field_config() );
		$cmb->add_group_field( $before_entry, $this->get_group_ad_field_config() );

		$before_entry_content = $cmb->add_field( array(
			'id'		 => 'mai_ad_before_entry_content',
			'type'		 => 'group',
			'repeatable' => false,
			'options'	 => array(
				'group_title' => __( 'Before Entry Content', 'mai-aec' ),
			),
		) );
		$cmb->add_group_field( $before_entry_content, $this->get_group_post_type_field_config() );
		$cmb->add_group_field( $before_entry_content, $this->get_group_ad_field_config() );

		$entry_content = $cmb->add_field( array(
			'id'		 => 'mai_ad_entry_content',
			'type'		 => 'group',
			'repeatable' => true,
			'options'	 => array(
				'group_title'   => __( 'Entry Content Ad {#}', 'mai-aec' ),
				'add_button'    => __( 'Add New Entry Content Ad', 'mai-aec' ),
				'remove_button' => __( 'Remove Ad Location', 'mai-aec' ),
				'sortable'      => true,
			),
		) );
		$cmb->add_group_field( $entry_content, $this->get_group_post_type_field_config() );
		$cmb->add_group_field( $entry_content, array(
			'name'		 => __( 'Paragraphs', 'mai-aec' ),
			'desc'		 => __( 'Display ad after this many paragraphs', 'mai-aec' ),
			'id'		 => 'count',
			'type'		 => 'text_small',
			'attributes' => array(
				'type'			=> 'number',
				'pattern'		=> '\d*',
				'placeholder'	=> 2,
			),
		) );
		$cmb->add_group_field( $entry_content, $this->get_group_ad_field_config() );

		$after_entry_content = $cmb->add_field( array(
			'id'		 => 'mai_ad_after_entry_content',
			'type'		 => 'group',
			'repeatable' => false,
			'options'	 => array(
				'group_title' => __( 'After Entry Content', 'mai-aec' ),
			),
		) );
		$cmb->add_group_field( $after_entry_content, $this->get_group_post_type_field_config() );
		$cmb->add_group_field( $after_entry_content, $this->get_group_ad_field_config() );

		$after_entry_a = $cmb->add_field( array(
			'id'		 => 'mai_ad_after_entry_a',
			'type'		 => 'group',
			'repeatable' => false,
			'options'	 => array(
				'group_title' => __( 'After Entry (A)', 'mai-aec' ),
			),
		) );
		$cmb->add_group_field( $after_entry_a, $this->get_group_post_type_field_config() );
		$cmb->add_group_field( $after_entry_a, $this->get_group_ad_field_config() );

		$after_entry_b = $cmb->add_field( array(
			'id'		 => 'mai_ad_after_entry_b',
			'type'		 => 'group',
			'repeatable' => false,
			'options'	 => array(
				'group_title' => __( 'After Entry (B)', 'mai-aec' ),
			),
		) );
		$cmb->add_group_field( $after_entry_b, $this->get_group_post_type_field_config() );
		$cmb->add_group_field( $after_entry_b, $this->get_group_ad_field_config() );

		$after_entry_c = $cmb->add_field( array(
			'id'		 => 'mai_ad_after_entry_c',
			'type'		 => 'group',
			'repeatable' => false,
			'options'	 => array(
				'group_title' => __( 'After Entry (C)', 'mai-aec' ),
			),
		) );
		$cmb->add_group_field( $after_entry_c, $this->get_group_post_type_field_config() );
		$cmb->add_group_field( $after_entry_c, $this->get_group_ad_field_config() );

	}

	function get_group_post_type_field_config() {
		$post_types			= get_post_types( array('public' => true ), 'objects' );
		$post_type_options	= array();
	    foreach ( $post_types as $post_type ) {
			$post_type_options[$post_type->name] = $post_type->label;
	    }
	    // Remove Media as an option
	    unset( $post_type_options['attachment'] );
		return array(
			'name'				=> __( 'Post Types', 'mai-aec' ),
			'desc'				=> __( 'Post types to display this content area on', 'mai-aec' ),
			'id'				=> 'post_types',
			'type'				=> 'multicheck_inline',
			'select_all_button'	=> false,
			'options'			=> $post_type_options,
		);
	}

	function get_group_ad_field_config() {
		return array(
			'name'		=> __( 'Content', 'mai-aec' ),
			'id'		=> 'content',
			'type'		=> 'wysiwyg',
			'options'	=> array(
				'wpautop'		=> true,
				'media_buttons'	=> true,
				'textarea_rows'	=> 6,
			),
		);
	}

	/**
	 * Register settings notices for display
	 *
	 * @since  0.1.0
	 * @param  int   $object_id Option key
	 * @param  array $updated   Array of updated fields
	 * @return void
	 */
	public function settings_notices( $object_id, $updated ) {
		if ( $object_id !== $this->key || empty( $updated ) ) {
			return;
		}

		add_settings_error( $this->key . '-notices', '', __( 'Settings updated.', 'mai-aec' ), 'updated' );
		settings_errors( $this->key . '-notices' );
	}

	/**
	 * Public getter method for retrieving protected/private variables
	 * @since  0.1.0
	 * @param  string  $field Field to retrieve
	 * @return mixed          Field value or exception is thrown
	 */
	public function __get( $field ) {
		// Allowed fields to retrieve
		if ( in_array( $field, array( 'key', 'metabox_id', 'title', 'options_page' ), true ) ) {
			return $this->{$field};
		}

		throw new Exception( 'Invalid property: ' . $field );
	}

}

/**
 * Helper function to get/return the Mai_AEC_Fields object
 * @since  0.1.0
 * @return Mai_AEC_Fields object
 */
function maiaec_fields() {
	return Mai_AEC_Fields::get_instance();
}

/**
 * Wrapper function around cmb2_get_option
 * @since  0.1.0
 * @param  string $key     Options array key
 * @param  mixed  $default Optional default value
 * @return mixed           Option value
 */
function maiaec_get_option( $key = '', $default = null ) {
	if ( function_exists( 'cmb2_get_option' ) ) {
		// Use cmb2_get_option as it passes through some key filters.
		return cmb2_get_option( maiaec_fields()->key, $key, $default );
	}

	// Fallback to get_option if CMB2 is not loaded yet.
	$opts = get_option( maiaec_fields()->key, $key, $default );

	$val = $default;

	if ( 'all' == $key ) {
		$val = $opts;
	} elseif ( array_key_exists( $key, $opts ) && false !== $opts[ $key ] ) {
		$val = $opts[ $key ];
	}

	return $val;
}

// Get it started
maiaec_fields();
