<?php

/**
 * Plugin Name:     Continuous Integrations
 * Plugin URI:      https://coderex.co
 * Description:     This plugin is for continuous integrations pipeline
 * Version:         1.0.0
 * Author:          Code Rex
 * Author URI:      https://coderex.co
 * Text Domain:     continuous-integrations
 * Domain Path:     /languages
 * Requires PHP:    7.1
 * Requires WP:     5.5.0
 * Namespace:       ContinuousIntegrations
 */

defined( 'ABSPATH' ) || exit;



final class ContinuousIntegrations {
    /**
     * Plugin version.
     *
     * @var string
     */
    const VERSION = '1.0.0';

    /**
     * Plugin slug.
     *
     * @var string
     *
     * @since 1.0.0
     */
    const SLUG = 'continuous-integrations';

    /**
     * Holds various class instances.
     *
     * @var array
     *
     * @since 1.0.0
     */
    private $container = [];

    /**
     * Constructor for the PluginName class.
     *
     * Sets up all the appropriate hooks and actions within our plugin.
     *
     * @since 1.0.0
     */
    private function __construct() {
        require_once __DIR__ . '/vendor/autoload.php';

        $this->define_constants();

        register_activation_hook( __FILE__, [ $this, 'activate' ] );
        register_deactivation_hook( __FILE__, [ $this, 'deactivate' ] );

        add_action( 'wp_loaded', [ $this, 'flush_rewrite_rules' ] );
        $this->init_plugin();
    }

    /**
     * Initializes the PluginBoilerplate() class.
     *
     * Checks for an existing PluginBoilerplate() instance
     * and if it doesn't find one, creates it.
     *
     * @since 1.0.0
     *
     * @return ContinuousIntegrations|bool
     */
    public static function init() {
        static $instance = false;

        if ( ! $instance ) {
            $instance = new ContinuousIntegrations();
        }

        return $instance;
    }

    /**
     * Magic getter to bypass referencing plugin.
     *
     * @since 1.0.0
     *
     * @param $prop
     *
     * @return mixed
     */
    public function __get( $prop ) {
        if ( array_key_exists( $prop, $this->container ) ) {
            return $this->container[ $prop ];
        }

        return $this->{$prop};
    }

    /**
     * Magic isset to bypass referencing plugin.
     *
     * @since 1.0.0
     *
     * @param $prop
     *
     * @return mixed
     */
    public function __isset( $prop ) {
        return isset( $this->{$prop} ) || isset( $this->container[ $prop ] );
    }

    /**
     * Define the constants.
     *
     * @since 1.0.0
     *
     * @return void
     */
    public function define_constants() {
        define( 'CONTINUOUS_INTEGRATIONS_VERSION', self::VERSION );
        define( 'CONTINUOUS_INTEGRATIONS_SLUG', self::SLUG );
        define( 'CONTINUOUS_INTEGRATIONS_FILE', __FILE__ );
        define( 'CONTINUOUS_INTEGRATIONS_DIR', __DIR__ );
        define( 'CONTINUOUS_INTEGRATIONS_PATH', dirname( CONTINUOUS_INTEGRATIONS_FILE ) );
        define( 'CONTINUOUS_INTEGRATIONS_INCLUDES', CONTINUOUS_INTEGRATIONS_PATH . '/includes' );
        define( 'CONTINUOUS_INTEGRATIONS_TEMPLATE_PATH', CONTINUOUS_INTEGRATIONS_PATH . '/views' );
        define( 'CONTINUOUS_INTEGRATIONS_URL', plugins_url( '', CONTINUOUS_INTEGRATIONS_FILE ) );
        define( 'CONTINUOUS_INTEGRATIONS_BUILD', CONTINUOUS_INTEGRATIONS_URL . '/build' );
        define( 'CONTINUOUS_INTEGRATIONS_ASSETS', CONTINUOUS_INTEGRATIONS_URL . '/assets' );
        define( 'CONTINUOUS_INTEGRATIONS_PRODUCTION', 'yes' );
    }

    /**
     * Load the plugin after all plugins are loaded.
     *
     * @since 1.0.0
     *
     * @return void
     */
    public function init_plugin() {
        $this->includes();
        $this->init_hooks();

        /**
         * Fires after the plugin is loaded.
         *
         * @since 1.0.0
         */
        do_action( 'continuous_integrations_loaded' );
    }

    /**
     * Activating the plugin.
     *
     * @since 1.0.0
     *
     * @return void
     */
    public function activate() {
        // Run the installer to create necessary migrations.
//        $this->install();
    }

    /**
     * Placeholder for deactivation function.
     *
     * @since 1.0.0
     *
     * @return void
     */
    public function deactivate() {
        //
    }

    /**
     * Flush rewrite rules after plugin is activated.
     *
     * Nothing being added here yet.
     *
     * @since 1.0.0
     */
    public function flush_rewrite_rules() {
        // fix rewrite rules
    }

    /**
     * Run the installer to create necessary migrations and seeders.
     *
     * @since 1.0.0
     *
     * @return void
     */
    private function install() {
        $installer = new RexTheme\ContinuousIntegrations\Setup\Installer();
        $installer->run();
    }

    /**
     * Include the required files.
     *
     * @since 0.2.0
     *
     * @return void
     */
    public function includes() {
        if ( $this->is_request( 'admin' ) ) {
            $this->container['admin_menu'] = new RexTheme\ContinuousIntegrations\Admin\Menu();
        }
		$this->container['assets']   = new RexTheme\ContinuousIntegrations\Assets\LoadAssets();
        $this->container['rest_api'] = new RexTheme\ContinuousIntegrations\REST\Api();
    }

    /**
     * Initialize the hooks.
     *
     * @since 0.2.0
     *
     * @return void
     */
    public function init_hooks() {
        // Init classes
        add_action( 'init', [ $this, 'init_classes' ] );

        // Localize our plugin
        add_action( 'init', [ $this, 'localization_setup' ] );

        // Add the plugin page links
        add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), [ $this, 'plugin_action_links' ] );
    }


    /**
     * Instantiate the required classes.
     *
     * @since 0.2.0
     *
     * @return void
     */
    public function init_classes() {
        // Init necessary hooks
        new RexTheme\ContinuousIntegrations\Hooks\Common();
    }

    /**
     * Initialize plugin for localization.
     *
     * @uses load_plugin_textdomain()
     *
     * @since 0.2.0
     *
     * @return void
     */
    public function localization_setup() {
        load_plugin_textdomain( 'continuous-integrations', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );

        // Load the React-pages translations.
        if ( is_admin() ) {
            // Load wp-script translation for plugin-name-app
            wp_set_script_translations( 'plugin-name-app', 'continuous-integrations', plugin_dir_path( __FILE__ ) . 'languages/' );
        }
    }

    /**
     * What type of request is this.
     *
     * @since 0.2.0
     *
     * @param string $type admin, ajax, cron or frontend
     *
     * @return bool
     */
    private function is_request( $type ) {
        switch ( $type ) {
            case 'admin':
                return is_admin();

            case 'ajax':
                return defined( 'DOING_AJAX' );

            case 'rest':
                return defined( 'REST_REQUEST' );

            case 'cron':
                return defined( 'DOING_CRON' );

            case 'frontend':
                return ( ! is_admin() || defined( 'DOING_AJAX' ) ) && ! defined( 'DOING_CRON' );
        }
    }

    /**
     * Plugin action links
     *
     * @param array $links
     *
     * @since 0.2.0
     *
     * @return array
     */
    public function plugin_action_links( $links ) {
        $links[] = '<a href="' . admin_url( 'admin.php?page=plugin_name#/settings' ) . '">' . __( 'Settings', 'continuous-integrations' ) . '</a>';
        $links[] = '<a href="#" target="_blank">' . __( 'Documentation', 'continuous-integrations' ) . '</a>';

        return $links;
    }
}



/**
 * Initialize the main plugin.
 *
 * @since 1.0.0
 *
 * @return \ContinuousIntegrations|bool
 */
function the_continuous_integrations_main_function() {
    return ContinuousIntegrations::init();
}

/*
 * Kick-off the plugin.
 *
 * @since 1.0.0
 */
the_continuous_integrations_main_function();

