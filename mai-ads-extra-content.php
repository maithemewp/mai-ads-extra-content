<?php
/**
 * Plugin Name: 	Mai - Ads and Extra Content
 * Plugin URI: 		https://maitheme.com
 * Description: 	Enable ads and extra content areas throughout Mai Pro themes. Requires Mai Pro Engine plugin.
 * Version: 		1.0.0
 *
 * Author: 			Mike Hemberger, BizBudding Inc
 * Author URI: 		https://bizbudding.com
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists( 'Mai_AEC_Setup' ) ) :

/**
 * Main Mai_AEC_Setup Class.
 *
 * @since 1.0.0
 */
final class Mai_AEC_Setup {

    /**
     * @var Mai_AEC_Setup The one true Mai_AEC_Setup
     * @since 1.0.0
     */
    private static $instance;

    /**
     * Main Mai_AEC_Setup Instance.
     *
     * Insures that only one instance of Mai_AEC_Setup exists in memory at any one
     * time. Also prevents needing to define globals all over the place.
     *
     * @since   1.0.0
     * @static  var array $instance
     * @uses    Mai_AEC_Setup::setup_constants() Setup the constants needed.
     * @uses    Mai_AEC_Setup::includes() Include the required files.
     * @uses    Mai_AEC_Setup::setup() Activate, deactivate, etc.
     * @see     Mai_AEC()
     * @return  object | Mai_AEC_Setup The one true Mai_AEC_Setup
     */
    public static function instance() {
        if ( ! isset( self::$instance ) ) {
            // Setup the init
            self::$instance = new Mai_AEC_Setup;
            // Methods
            self::$instance->setup_constants();
            // self::$instance->includes();
            self::$instance->setup();
        }
        return self::$instance;
    }

    /**
     * Throw error on object clone.
     *
     * The whole idea of the singleton design pattern is that there is a single
     * object therefore, we don't want the object to be cloned.
     *
     * @since   1.0.0
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
     * @since   1.0.0
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
     * @since   1.0.0
     * @return  void
     */
    private function setup_constants() {

        // Plugin version.
        if ( ! defined( 'MAI_AEC_VERSION' ) ) {
            define( 'MAI_AEC_VERSION', '1.0.0' );
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

    public function setup() {

        /**
         * Setup the updater.
         * This class/code is in Mai Pro Engine.
         * Since this is a dependent plugin, we don't include that code twice.
         *
         * @uses  https://github.com/YahnisElsts/plugin-update-checker/
         */
        if ( class_exists( 'Puc_v4_Factory' ) ) {
            $updater = Puc_v4_Factory::buildUpdateChecker( 'https://github.com/bizbudding/mai-ads-extra-content/', __FILE__, 'mai-ads-extra-content' );
        }

        add_action( 'plugins_loaded', array( $this, 'init' ) );
    }

    function init() {
        // Bail if CMB2 is not running anywhere
        if ( ! class_exists( 'Mai_Pro_Engine' ) ) {
            add_action( 'admin_init', array( $this, 'deactivate_plugin' ) );
            add_action( 'admin_notices', array( $this, 'admin_notice' ) );
            return;
        }
        // Includes
        $this->includes();
    }

    function deactivate_plugin() {
        deactivate_plugins( plugin_basename( __FILE__ ) );
    }

    function admin_notice() {
        printf( '<div class="notice notice-warning is-dismissible"><p>%s</p></div>', __( 'Mai - Ads and Extra Content requires the Mai Pro Engine plugin. As a result, this plugin has been deactivated.', 'mai-ads-extra-content' ) );
        if ( isset( $_GET['activate'] ) ) {
            unset( $_GET['activate'] );
        }
    }

    /**
     * Include required files.
     *
     * @access  private
     * @since   1.0.0
     * @return  void
     */
    private function includes() {
        foreach ( glob( MAI_AEC_INCLUDES_DIR . '*.php' ) as $file ) { include $file; }
    }

}
endif; // End if class_exists check.

/**
 * The main function for that returns Mai_AEC_Setup
 *
 * The main function responsible for returning the one true Mai_AEC_Setup
 * Instance to functions everywhere.
 *
 * Use this function like you would a global variable, except without needing
 * to declare the global.
 *
 * Example: <?php $plugin = Mai_AEC(); ?>
 *
 * @since 1.0.0
 *
 * @return object|Mai_AEC_Setup The one true Mai_AEC_Setup Instance.
 */
function Mai_AEC() {
    return Mai_AEC_Setup::instance();
}

// Get Mai_AEC Running.
Mai_AEC();
