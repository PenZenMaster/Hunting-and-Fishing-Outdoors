<?php
/**
 * WPRentals Core Loader Class
 *
 * This class handles the loading of all WPRentals core files, dependencies, and integrations.
 * It manages core file loading, third-party integrations, and component initialization.
 *
 * @package    WPRentals
 * @subpackage Core
 * @since      4.0
 *
 * @uses       WPESTATE_PLUGIN_PATH Defined in wprentals-core.php
 * @uses       wprentals_get_option() Function from helper-functions.php
 * @uses       Wpestate_Social_Login Class from classes/tweet_login.php
 * @uses       Wpestate_Global_Payments Class from classes/wpestate_global_payments.php
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Core loader class for WPRentals
 * 
 * @since 4.0
 */
if (!class_exists('WpRentals_Loader')):

class WpRentals_Loader {

    /**
     * Instance of the loader
     *
     * @since 4.0
     * @var WpRentals_Loader
     */
    private static $instance = null;

    /**
     * Array of required core files to load
     *
     * @since 4.0
     * @var array
     */
    private $required_files = array(
        'misc/metaboxes.php',
        'misc/plugin_help_functions.php',
        'misc/emailfunctions.php',
        'misc/sms_functions.php', 
        'misc/3rd_party_code.php',
        'misc/update_functions.php',
        'resources/rcapi_functions.php',
        'widgets.php',
        'shortcodes/shortcodes_install.php',
        'shortcodes/shortcodes.php',
        'post-types/post-type-loaded.php',
        'admin/post-type-control.php',
        'admin/white-label.php',
        'admin/import-locations.php',
        'admin/real-estate-menu.php',
       // 'resources/src/Google_Client.php',
        //'resources/src/contrib/Google_Oauth2Service.php',
        'classes/wpestate_email.php',
        'classes/wpestate_func.php',
        'api/main.php'
        
    );


    /**
     * Get singleton instance
     *
     * @since  4.0
     * @return WpRentals_Loader The singleton instance
     */
    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

     /**
     * Constructor - Initialize the loader
     *
     * @since 4.0
     */
    private function __construct() {
       
        // Add initialization to plugins_loaded hook to ensure WordPress is fully loaded
        $this->init();
     
        add_action('init', array($this, 'wprentals_init_redux'), 1);
        add_action('init', array($this, 'initialize_components'), 30);
  
    }

      /**
     * Initialize all components
     * Hooked to plugins_loaded to ensure WordPress core functions are available
     *
     * @since 4.0
     */
    public function init() {
        $this->load_required_files();
    }

    
    /**
     * Load all required core files
     *
     * @since 4.0
     */
    private function load_required_files() {
        foreach ($this->required_files as $file) {
            $file_path = WPESTATE_PLUGIN_PATH . $file;
            
            if (file_exists($file_path)) {
                require_once $file_path;
            } else {
                // Log missing file error
                error_log(
                    sprintf(
                        /* translators: %s: The file path that is missing */
                        esc_html__('WPRentals: Required file %s not found', 'wprentals-core'),
                        $file_path
                    )
                );
            }
        }
    }

  

    /**
     * Initialize core components
     *
     * @since 4.0
     */
    public function initialize_components() {
        $this->maybe_initialize_social_login();
        $this->initialize_global_payments();
        $this->initialize_yelp();


    }



    public function wprentals_init_redux() {
        
        
        if (!class_exists('ReduxFramework') && file_exists(WPESTATE_PLUGIN_PATH . 'redux-framework/redux-core/framework.php')) {
            require_once( WPESTATE_PLUGIN_PATH. '/redux-framework/redux-core/framework.php' );
        }
           
       if ( !isset( $redux_demo ) && file_exists( WPESTATE_PLUGIN_PATH. '/redux-framework/admin-config.php' ) ) {
           require_once( WPESTATE_PLUGIN_PATH . '/redux-framework/admin-config.php' );
           Redux::init("wprentals_admin");
          
       }
            
    }



    private function initialize_yelp() {
        $yelp_client_id         =   wprentals_get_option('wp_estate_yelp_client_id','');
        $yelp_client_secret     =   wprentals_get_option('wp_estate_yelp_client_secret','');
        if($yelp_client_id!=='' || $yelp_client_secret!=='' ){
            require_once(WPESTATE_PLUGIN_PATH.'resources/yelp_fusion.php');
        }
    }

    /**
     * Initialize social login if enabled
     *
     * @since 4.0
     */
    private function maybe_initialize_social_login() {

        
        $facebook_status    =   esc_html( wprentals_get_option('wp_estate_facebook_login','') );
        if($facebook_status=='yes'){
            require_once WPESTATE_PLUGIN_PATH.'resources/facebook_sdk5/Facebook/autoload.php';
        }

        $twiter_status       =   esc_html( wprentals_get_option('wp_estate_twiter_login','') );
        if($twiter_status=='yes'){
            require_once WPESTATE_PLUGIN_PATH.'resources/twitteroauth/vendor/autoload.php';
        }
    
        $google_status              = esc_html( wprentals_get_option('wp_estate_google_login','') );

        if ($facebook_status === 'yes' || $twiter_status === 'yes' || $google_status === 'yes') {
            require_once WPESTATE_PLUGIN_PATH . 'classes/tweet_login.php';
            global $wpestate_social_login;
            $wpestate_social_login = new Wpestate_Social_Login();
        }
    }

    /**
     * Initialize global payments system
     *
     * @since 4.0
     */
    private function initialize_global_payments() {

        $enable_stripe_status   =    ( wprentals_get_option('wp_estate_enable_stripe') );
        if($enable_stripe_status==='yes'  && !class_exists('\Stripe\Stripe')  ){
            require_once(WPESTATE_PLUGIN_PATH.'resources/stripe-php-master/init.php');
        }

        
        require_once WPESTATE_PLUGIN_PATH . 'classes/wpestate_global_payments.php';
        global $wpestate_global_payments;
        $wpestate_global_payments = new Wpestate_Global_Payments();
    }
}

endif; // End class_exists check

