<?php

/**
 * Plugin Name:     Mai Ads & Extra Content
 * Plugin URI:      https://maitheme.com
 * Description:     Enable flexible ad locations and extra content areas throughout Mai Theme & Genesis child themes.
 * Text Domain:     mai-ads-extra-content
 * Version:         0.9.0
 *
 * Author:          MaiTheme.com
 * Author URI:      https://maitheme.com
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Main Mai_AEC Class.
 *
 * @since 0.1.1
 */
final class Mai_AEC {

	/**
	 * @var Mai_AEC The one true Mai_AEC
	 * @since 0.1.0
	 */
	private static $instance;

	/**
	 * Main Mai_AEC Instance.
	 *
	 * Insures that only one instance of Mai_AEC exists in memory at any one
	 * time. Also prevents needing to define globals all over the place.
	 *
	 * @since   0.1.0
	 * @static  var array $instance
	 * @uses    Mai_AEC::setup_constants() Setup the constants needed.
	 * @uses    Mai_AEC::setup() Activate, deactivate, etc.
	 * @see     Mai_AEC()
	 * @return  object | Mai_AEC The one true Mai_AEC
	 */
	public static function instance() {
		if ( ! isset( self::$instance ) ) {
			// Setup the init
			self::$instance = new Mai_AEC;
			// Methods
			self::$instance->setup_constants();
			self::$instance->includes();
			self::$instance->hooks();
		}
		return self::$instance;
	}

	/**
	 * Throw error on object clone.
	 *
	 * The whole idea of the singleton design pattern is that there is a single
	 * object therefore, we don't want the object to be cloned.
	 *
	 * @since   0.1.0
	 * @access  protected
	 * @return  void
	 */
	public function __clone() {
		// Cloning instances of the class is forbidden.
		_doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?', 'mai-aec' ), '1.0' );
	}

	/**
	 * Disable unserializing of the class.
	 *
	 * @since   0.1.0
	 * @access  protected
	 * @return  void
	 */
	public function __wakeup() {
		// Unserializing instances of the class is forbidden.
		_doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?', 'mai-aec' ), '1.0' );
	}

	/**
	* Setup plugin constants.
	*
	* @access  private
	* @since   0.1.0
	* @return  void
	*/
	private function setup_constants() {

		// Plugin version.
		if ( ! defined( 'MAI_AEC_VERSION' ) ) {
			define( 'MAI_AEC_VERSION', '0.9.0' );
		}

		// Plugin Folder Path.
		if ( ! defined( 'MAI_AEC_PLUGIN_DIR' ) ) {
			define( 'MAI_AEC_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
		}

		// Plugin Includes Path
		if ( ! defined( 'MAI_AEC_INCLUDES_DIR' ) ) {
			define( 'MAI_AEC_INCLUDES_DIR', MAI_AEC_PLUGIN_DIR . 'includes/' );
		}

		// Plugin Folder URL.
		if ( ! defined( 'MAI_AEC_PLUGIN_URL' ) ) {
			define( 'MAI_AEC_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
		}

		// Plugin Root File.
		if ( ! defined( 'MAI_AEC_PLUGIN_FILE' ) ) {
			define( 'MAI_AEC_PLUGIN_FILE', __FILE__ );
		}

		// Plugin Base Name
		if ( ! defined( 'MAI_AEC_BASENAME' ) ) {
			define( 'MAI_AEC_BASENAME', dirname( plugin_basename( __FILE__ ) ) );
		}

	}

	/**
	 * Include required files.
	 *
	 * @access  private
	 * @since   0.1.0
	 * @return  void
	 */
	private function includes() {
		// Autoload.
		require_once __DIR__ . '/vendor/autoload.php';
		// Vendor.
		require_once MAI_AEC_INCLUDES_DIR . 'vendor/CMB2/init.php';
		// Internal.
		foreach ( glob( MAI_AEC_INCLUDES_DIR . '*.php' ) as $file ) { include $file; }
		foreach ( glob( MAI_AEC_INCLUDES_DIR . 'widgets/*.php' ) as $file ) { include $file; }
	}


	/**
	 * Setup the plugin.
	 *
	 * @return  void
	 */
	public function hooks() {
		add_action( 'admin_init', array( $this, 'updater' ) );
		add_action( 'admin_init', array( $this, 'requirements' ) );
	}

	/**
	 * Setup the updater.
	 *
	 * composer require yahnis-elsts/plugin-update-checker
	 *
	 * @uses    https://github.com/YahnisElsts/plugin-update-checker/
	 *
	 * @return  void
	 */
	public function updater() {

		// Bail if current user cannot manage plugins.
		if ( ! current_user_can( 'install_plugins' ) ) {
			return;
		}

		// Bail if plugin updater is not loaded.
		if ( ! class_exists( 'Puc_v4_Factory' ) ) {
			return;
		}

		// Setup the updater.
		$updater = Puc_v4_Factory::buildUpdateChecker( 'https://github.com/maithemewp/mai-ads-extra-content/', __FILE__, 'mai-ads-extra-content' );
	}

	/**
	 * Check if Genesis is running.
	 * This was originally taken from https://github.com/copyblogger/genesis-connect-woocommerce/blob/develop/genesis-connect-woocommerce.php.
	 *
	 * @return  void
	 */
	public function requirements() {
		/**
		 * If Genesis is not the parent theme, deactivate and show a notice.
		 *
		 * This was taken from https://github.com/copyblogger/genesis-connect-woocommerce/blob/develop/genesis-connect-woocommerce.php.
		 */
		if ( 'genesis' !== get_option( 'template' ) ) {
			deactivate_plugins( plugin_basename( __FILE__ ) );
			add_action( 'admin_notices', function() {
				$notice = sprintf( __( 'Sorry, Mai Ads & Extra Content cannot be activated unless you have installed <a href="%s">Genesis</a>', 'mai-ads-extra-content' ), 'http://my.studiopress.com/themes/genesis/' );
				printf( '<div id="message" class="error notice"><p>%s</p></div>', wp_kses_post( $notice ) );
			});
		}
	}

}

/**
 * The main function for that returns Mai_AEC
 *
 * The main function responsible for returning the one true Mai_AEC
 * Instance to functions everywhere.
 *
 * Use this function like you would a global variable, except without needing
 * to declare the global.
 *
 * Example: <?php $plugin = Mai_AEC(); ?>
 *
 * @since 0.1.0
 *
 * @return object|Mai_AEC The one true Mai_AEC Instance.
 */
function Mai_AEC() {
	return Mai_AEC::instance();
}

// Get Mai_AEC Running.
Mai_AEC();
