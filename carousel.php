<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @since             1.0.0
 * @package           carousel
 *
 * @wordpress-plugin
 * Plugin Name:       Carousel
 * Description:       워드프레스 이미지 슬라이드 플러그인
 * Version:           1.0.0
 * Author:            seonzoo
 * Author URI:        http://seonzoo.com/
 * Text Domain:       carousel
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

define( 'CAROUSEL_DIR_PATH', plugin_dir_path( __FILE__ ) );
define( 'CAROUSEL_INCLUDES_PATH', CAROUSEL_DIR_PATH . 'includes/' );
define( 'CAROUSEL_ADMIN_PATH', CAROUSEL_DIR_PATH . 'admin/' );
define( 'CAROUSEL_PUBLIC_PATH', CAROUSEL_DIR_PATH . 'public/' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-carousel-activator.php
 */
function activate_carousel() {
	require_once CAROUSEL_INCLUDES_PATH . 'class-carousel-activator.php';
	Carousel_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-carousel-deactivator.php
 */
function deactivate_carousel() {
	require_once CAROUSEL_INCLUDES_PATH . 'class-carousel-deactivator.php';
	Carousel_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_carousel' );
register_deactivation_hook( __FILE__, 'deactivate_carousel' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require CAROUSEL_INCLUDES_PATH . 'class-carousel.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_carousel() {

	$plugin = new Carousel();
	$plugin->run();

}
run_carousel();
