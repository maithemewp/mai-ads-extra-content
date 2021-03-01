<?php

/**
 * Hook in and register a metabox to handle a theme options page and adds a menu item.
 */
add_action( 'cmb2_admin_init', 'maiaec_register_metabox' );
function maiaec_register_metabox() {
	/**
	 * Registers options page menu item and form.
	 */
	$cmb = new_cmb2_box( array(
		'id'           => 'mai_aec_metabox',
		'title'        => __( 'Ads & Extra Content', 'mai-ads-extra-content' ),
		'object_types' => array( 'options-page' ),
		'option_key'   => 'mai_aec', // The option key and admin menu page slug.
		'parent_slug'  => current_theme_supports( 'mai-engine' ) ? 'mai-theme' : 'genesis', // Make options page a submenu item of the themes menu.
	) );

	$cmb->add_field( array(
		'name' => __( 'Header/Footer Code', 'mai-ads-extra-content' ),
		'desc' => __( 'This is the header and footer code, not a display area', 'mai-ads-extra-content' ),
		'id'   => 'mai_ad_code_title',
		'type' => 'title',
	) );

	$cmb->add_field( array(
		'name'       => __( 'Header Code', 'mai-ads-extra-content' ),
		'desc'       => sprintf( __( 'This code will output immediately before the closing %s tag in the document source.', 'mai-ads-extra-content' ), '<code>' . esc_html( '</head>' ) . '</code>' ),
		'id'         => 'mai_ad_header',
		'type'       => 'textarea_code',
		'attributes' => array(
			'style' => 'width:100%;',
		),
	) );

	$cmb->add_field( array(
		'name'       => __( 'Body Code', 'mai-ads-extra-content' ),
		'desc'       => sprintf( __( 'This code will output immediately after the opening %s tag in the document source.', 'mai-ads-extra-content' ), '<code>' . esc_html( '<body>' ) . '</code>' ),
		'id'         => 'mai_ad_body',
		'type'       => 'textarea_code',
		'attributes' => array(
			'style' => 'width:100%;',
		),
		) );

		$cmb->add_field( array(
		'name'       => __( 'Footer Code', 'mai-ads-extra-content' ),
		'desc'       => sprintf( __( 'This code will output immediately before the closing %s tag in the document source.', 'mai-ads-extra-content' ), '<code>' . esc_html( '</body>' ) . '</code>' ),
		'id'         => 'mai_ad_footer',
		'type'       => 'textarea_code',
		'attributes' => array(
			'style' => 'width:100%;',
		),
	) );

	$cmb->add_field( array(
		'name' => __( 'Global Ad & Content Areas', 'mai-ads-extra-content' ),
		'desc' => __( 'These are the custom ad and content display areas displayed on every page of the site', 'mai-ads-extra-content' ),
		'id'   => 'mai_ad_global_content_title',
		'type' => 'title',
	) );

	$cmb->add_field( array(
		'name'            => __( 'Before Header', 'mai-ads-extra-content' ),
		'id'              => 'mai_ad_header_before',
		'type'            => 'wysiwyg',
		'sanitization_cb' => 'maiaec_sanitize_wysiwyg',
		'escape_cb'       => 'maiaec_escape_wysiwyg',
		'options'         => array(
			'wpautop'       => true,
			'media_buttons' => true,
			'textarea_rows' => 6,
		),
	) );

	if ( class_exists( 'Mai_Theme_Engine' ) ) {

		$cmb->add_field( array(
			'name'            => __( 'Header Left', 'mai-ads-extra-content' ),
			'id'              => 'mai_ad_header_left',
			'type'            => 'wysiwyg',
			'sanitization_cb' => 'maiaec_sanitize_wysiwyg',
			'escape_cb'       => 'maiaec_escape_wysiwyg',
			'options'         => array(
				'wpautop'       => true,
				'media_buttons' => true,
				'textarea_rows' => 6,
			),
		) );

	}

	$cmb->add_field( array(
		'name'            => __( 'Header Right', 'mai-ads-extra-content' ),
		'id'              => 'mai_ad_header_right',
		'type'            => 'wysiwyg',
		'sanitization_cb' => 'maiaec_sanitize_wysiwyg',
		'escape_cb'       => 'maiaec_escape_wysiwyg',
		'options'         => array(
			'wpautop'       => true,
			'media_buttons' => true,
			'textarea_rows' => 6,
		),
	) );

	$cmb->add_field( array(
		'name'            => __( 'After Header', 'mai-ads-extra-content' ),
		'id'              => 'mai_ad_header_after',
		'type'            => 'wysiwyg',
		'sanitization_cb' => 'maiaec_sanitize_wysiwyg',
		'escape_cb'       => 'maiaec_escape_wysiwyg',
		'options'         => array(
			'wpautop'       => true,
			'media_buttons' => true,
			'textarea_rows' => 6,
		),
	) );

	$cmb->add_field( array(
		'name'            => __( 'Before Footer', 'mai-ads-extra-content' ),
		'id'              => 'mai_ad_before_footer',
		'type'            => 'wysiwyg',
		'sanitization_cb' => 'maiaec_sanitize_wysiwyg',
		'escape_cb'       => 'maiaec_escape_wysiwyg',
		'options'         => array(
			'wpautop'       => true,
			'media_buttons' => true,
			'textarea_rows' => 6,
		),
	) );

	$cmb->add_field( array(
		'name' => __( 'Entry Ad & Content Areas', 'mai-ads-extra-content' ),
		'desc' => __( 'These are the custom ad and content display areas displayed on single entries', 'mai-ads-extra-content' ),
		'id'   => 'mai_ad_entry_content_title',
		'type' => 'title',
	) );

	$before_entry = $cmb->add_field( array(
		'id'         => 'mai_ad_before_entry',
		'type'       => 'group',
		'repeatable' => false,
		'options'    => array(
			'group_title' => __( 'Before Entry', 'mai-ads-extra-content' ),
		),
	) );
	$cmb->add_group_field( $before_entry, maiaec_get_group_post_type_field_config() );
	$cmb->add_group_field( $before_entry, maiaec_get_group_ad_field_config() );

	$before_entry_content = $cmb->add_field( array(
		'id'         => 'mai_ad_before_entry_content',
		'type'       => 'group',
		'repeatable' => false,
		'options'    => array(
			'group_title' => __( 'Before Entry Content', 'mai-ads-extra-content' ),
		),
	) );
	$cmb->add_group_field( $before_entry_content, maiaec_get_group_post_type_field_config() );
	$cmb->add_group_field( $before_entry_content, maiaec_get_group_ad_field_config() );

	$entry_content = $cmb->add_field( array(
		'id'         => 'mai_ad_entry_content',
		'type'       => 'group',
		'repeatable' => true,
		'options'    => array(
			'group_title'   => __( 'Entry Content Ad {#}', 'mai-ads-extra-content' ),
			'add_button'    => __( 'Add New Entry Content Ad', 'mai-ads-extra-content' ),
			'remove_button' => __( 'Remove Ad Location', 'mai-ads-extra-content' ),
			'sortable'      => true,
		),
	) );
	$cmb->add_group_field( $entry_content, maiaec_get_group_post_type_field_config() );
	$cmb->add_group_field( $entry_content, array(
		'name'       => __( 'Paragraphs', 'mai-ads-extra-content' ),
		'desc'       => __( 'Display content after this many paragraphs', 'mai-ads-extra-content' ),
		'id'         => 'count',
		'type'       => 'text_small',
		'attributes' => array(
			'type'        => 'number',
			'pattern'     => '\d*',
			'placeholder' => 2,
		),
	) );
	$cmb->add_group_field( $entry_content, maiaec_get_group_ad_field_config() );

	$after_entry_content = $cmb->add_field( array(
		'id'         => 'mai_ad_after_entry_content',
		'type'       => 'group',
		'repeatable' => false,
		'options'    => array(
			'group_title' => __( 'After Entry Content', 'mai-ads-extra-content' ),
		),
	) );
	$cmb->add_group_field( $after_entry_content, maiaec_get_group_post_type_field_config() );
	$cmb->add_group_field( $after_entry_content, maiaec_get_group_ad_field_config() );

	$after_entry_a = $cmb->add_field( array(
		'id'         => 'mai_ad_after_entry_a',
		'type'       => 'group',
		'repeatable' => false,
		'options'    => array(
			'group_title' => __( 'After Entry (A)', 'mai-ads-extra-content' ),
		),
	) );
	$cmb->add_group_field( $after_entry_a, maiaec_get_group_post_type_field_config() );
	$cmb->add_group_field( $after_entry_a, maiaec_get_group_ad_field_config() );

	$after_entry_b = $cmb->add_field( array(
		'id'         => 'mai_ad_after_entry_b',
		'type'       => 'group',
		'repeatable' => false,
		'options'    => array(
			'group_title' => __( 'After Entry (B)', 'mai-ads-extra-content' ),
		),
	) );
	$cmb->add_group_field( $after_entry_b, maiaec_get_group_post_type_field_config() );
	$cmb->add_group_field( $after_entry_b, maiaec_get_group_ad_field_config() );

	$after_entry_c = $cmb->add_field( array(
		'id'         => 'mai_ad_after_entry_c',
		'type'       => 'group',
		'repeatable' => false,
		'options'    => array(
			'group_title' => __( 'After Entry (C)', 'mai-ads-extra-content' ),
		),
	) );
	$cmb->add_group_field( $after_entry_c, maiaec_get_group_post_type_field_config() );
	$cmb->add_group_field( $after_entry_c, maiaec_get_group_ad_field_config() );

	$cmb->add_field( array(
		'name' => __( 'Widgets', 'mai-ads-extra-content' ),
		'id'   => 'mai_ad_widgets_title',
		'type' => 'title',
	) );

	$cmb->add_field( array(
		'name'            => __( 'Widget A', 'mai-ads-extra-content' ),
		'id'              => 'mai_ad_widget_a',
		'type'            => 'wysiwyg',
		'sanitization_cb' => 'maiaec_sanitize_wysiwyg',
		'escape_cb'       => 'maiaec_escape_wysiwyg',
		'options'         => array(
			'wpautop'       => true,
			'media_buttons' => true,
			'textarea_rows' => 6,
		),
	) );

	$cmb->add_field( array(
		'name'            => __( 'Widget B', 'mai-ads-extra-content' ),
		'id'              => 'mai_ad_widget_b',
		'type'            => 'wysiwyg',
		'sanitization_cb' => 'maiaec_sanitize_wysiwyg',
		'escape_cb'       => 'maiaec_escape_wysiwyg',
		'options'         => array(
			'wpautop'       => true,
			'media_buttons' => true,
			'textarea_rows' => 6,
		),
	) );

	$cmb->add_field( array(
		'name'            => __( 'Widget C', 'mai-ads-extra-content' ),
		'id'              => 'mai_ad_widget_c',
		'type'            => 'wysiwyg',
		'sanitization_cb' => 'maiaec_sanitize_wysiwyg',
		'escape_cb'       => 'maiaec_escape_wysiwyg',
		'options'         => array(
			'wpautop'       => true,
			'media_buttons' => true,
			'textarea_rows' => 6,
		),
	) );

}

function maiaec_sanitize_wysiwyg( $content ) {
	return wp_unslash( apply_filters( 'content_save_pre', $content ) );
}

function maiaec_escape_wysiwyg( $content ) {
	// This escape taken from Genesis Simple Edits.
	return wp_kses_stripslashes( wp_kses_decode_entities( $content ) );
}

function maiaec_get_group_post_type_field_config() {
	$post_types        = get_post_types( array('public' => true ), 'objects' );
	$post_type_options = array();
	foreach ( $post_types as $post_type ) {
		$post_type_options[$post_type->name] = $post_type->label;
	}
	// Remove Media as an option
	unset( $post_type_options['attachment'] );
	return array(
		'name'              => __( 'Post Types', 'mai-ads-extra-content' ),
		'desc'              => __( 'Post types to display this content area on', 'mai-ads-extra-content' ),
		'id'                => 'post_types',
		'type'              => 'multicheck_inline',
		'select_all_button' => false,
		'options'           => $post_type_options,
	);
}

function maiaec_get_group_ad_field_config() {
	return array(
		'name'            => __( 'Content', 'mai-ads-extra-content' ),
		'id'              => 'content',
		'type'            => 'wysiwyg',
		'sanitization_cb' => 'maiaec_sanitize_wysiwyg',
		'escape_cb'       => 'maiaec_escape_wysiwyg',
		'options'         => array(
			'wpautop'       => true,
			'media_buttons' => true,
			'textarea_rows' => 6,
		),
	);
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
		return cmb2_get_option( 'mai_aec', $key, $default );
	}

	// Fallback to get_option if CMB2 is not loaded yet.
	$opts = get_option( 'mai_aec', $key, $default );

	$val = $default;

	if ( 'all' == $key ) {
		$val = $opts;
	} elseif ( array_key_exists( $key, $opts ) && false !== $opts[ $key ] ) {
		$val = $opts[ $key ];
	}

	return $val;
}
