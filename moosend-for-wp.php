<?php

/**
 *
 * @link              moosend.com
 * @since             1.0.0
 * @package           Moosend_For_Wp
 *
 * @wordpress-plugin
 * Plugin Name:       Moosend For WordPress
 * Plugin URI:        moosend.com
 * Description:       Easily create and manage subscription forms, linked to your Moosend mailing lists
 * Version:           1.0.115
 * Author:            Moosend
 * Author URI:        https://moosend.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       moosend-for-wp
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-moosend-for-wp-activator.php
 */
function activate_moosend_for_wp() {
	require_once plugin_dir_path( __FILE__ ) . 'src/includes/class-moosend-for-wp-activator.php';
	Moosend_For_Wp_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-moosend-for-wp-deactivator.php
 */
function deactivate_moosend_for_wp() {
	require_once plugin_dir_path( __FILE__ ) . 'src/includes/class-moosend-for-wp-deactivator.php';
	Moosend_For_Wp_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_moosend_for_wp' );
register_deactivation_hook( __FILE__, 'deactivate_moosend_for_wp' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'src/includes/class-moosend-for-wp.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_moosend_for_wp() {

	$plugin = new Moosend_For_Wp();
	$plugin->run();

}
run_moosend_for_wp();


