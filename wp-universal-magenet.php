<?php
/*
Plugin Name: WP Universal Magenet
Plugin URI: http://google.com/
Description: WP Plugin based in Magenet Universal Plugin to replace the Monetize by magenet WP Plugin.
Version: 1.1
Author: Sniuk, etruel
Author URI: http://www.netmdp.com/
License: GPL2
*/
if (!class_exists('wp_universal_magenet') ) {
class wp_universal_magenet {
	private static $instance;

    public static function instance() {
        if( !self::$instance ) {
            self::$instance = new self();
			self::$instance->setup_constants();
            self::$instance->includes();
            self::$instance->hooks();
        }
        return self::$instance;
    }
	private function setup_constants() {
        define('WP_UNIVERSAL_MAGENET_DIR', plugin_dir_path( __FILE__ ));
        define('WP_UNIVERSAL_MAGENET_URL', plugin_dir_url( __FILE__ ));
		define('WP_UNIVERSAL_MAGENET_VERSION', '1.1');
		
    }
	private function includes() {
        require_once WP_UNIVERSAL_MAGENET_DIR . 'includes/functions.php'; 
		require_once WP_UNIVERSAL_MAGENET_DIR . 'includes/settings.php';
		require_once WP_UNIVERSAL_MAGENET_DIR . 'includes/magenet.php';
		require_once WP_UNIVERSAL_MAGENET_DIR . 'includes/widget.php';
    }
	private function hooks() {
		add_action( 'widgets_init', array('MagenetWidget', 'register_widget'));
	}
}
}
$wp_universal_magenet = wp_universal_magenet::instance();;

?>