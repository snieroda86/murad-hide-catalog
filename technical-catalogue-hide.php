<?php
/**
 * Plugin Name:       Hide Technical Catalogues
 * Plugin URI:        https://www.web4you.biz.pl
 * Description:       Wtyczka ukrywająca możliwość pobrania katalogu technicznego produktu dla niezalogowanych użytkownikó 
 * Version:           1.0
 * Requires at least: 5.6
 * Requires PHP:      7.2
 * Author:            Sebastian Nieroda
 * Author URI:        https://www.web4you.biz.pl
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       hide-calatogue
 * Domain Path:       /languages
 */

if(!defined('ABSPATH')){
	exit;
}


if( !class_exists( 'MoradHideCatalogue' )){

    class MoradHideCatalogue{

        public function __construct(){

            $this->define_constants(); 
            // Create custom post type
            require_once(MORAD_PATH.'post-types/profile-aluminiowe.php');
            $systemyAluminioweCPT = new SystemyAluminiowe_CPT();
            // Button to download catalogue shortcode
            require_once(MORAD_PATH.'shortcodes/catalogue-button.php');
            $catalogue_button_shortcode = new Morad_Catalogue_Button_Shortcode();
            // Registration form shortcode
            require_once(MORAD_PATH.'shortcodes/registration-form.php');
            $registration_form_shortcode = new Morad_Registration_Form_Shortcode();

            // Custom template
            require_once(MORAD_PATH.'includes/morad-custom-templates.php');
            $morad_custom_templates = new Morad_Custom_Templates();
            // Enqueue scripts
            // add_action('wp_enqueue_scripts' , array($this , 'register_scripts') , 999);
                        
        }

        public function define_constants(){
            // Path/URL to root of this plugin, with trailing slash.
            define ( 'MORAD_PATH', plugin_dir_path( __FILE__ ) );
            define ( 'MORAD_URL', plugin_dir_url( __FILE__ ) );
            define ( 'MORAD_VERSION', '1.0.0' );
        }

        /**
         * Activate the plugin
         */
        public static function activate(){
            update_option('rewrite_rules', '' );
           
        }

        /**
         * Deactivate the plugin
         */
        public static function deactivate(){
            flush_rewrite_rules();
            unregister_post_type( 'systemy-aluminiowe' );
        }        

        /**
         * Uninstall the plugin
         */
        public static function uninstall(){

        }

        // Register scripts
        // public function register_scripts(){
        //     wp_register_script('custom-sn-js' , SN_SONG_TRS_URL.'assets/jquery.custom.js' , array('jquery') , SN_SONG_TRS_VERSION , true  );
        //     wp_register_script('jquery-validate-sn-js' , SN_SONG_TRS_URL.'assets/jquery.validate.min.js' , array('jquery') , SN_SONG_TRS_VERSION , true  );
        // } 

    }
}

// Plugin Instantiation
if (class_exists( 'MoradHideCatalogue' )){

    // Installation and uninstallation hooks
    register_activation_hook( __FILE__, array( 'MoradHideCatalogue', 'activate'));
    register_deactivation_hook( __FILE__, array( 'MoradHideCatalogue', 'deactivate'));
    register_uninstall_hook( __FILE__, array( 'MoradHideCatalogue', 'uninstall' ) );

    // Instatiate the plugin class
    $morad_hide_catalogue = new MoradHideCatalogue(); 
}

