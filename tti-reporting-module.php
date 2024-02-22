<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://ministryinsights.com/
 * @since             1.0.0
 * @package           Tti_Reporting_Module
 *
 * @wordpress-plugin
 * Plugin Name:       TTI Reporting Module
 * Plugin URI:        https://ministryinsights.com/
 * Description:       A simple plugin to export users enrolled in a specific duration.
 * Version:           1.0.0
 * Author:            Ministry Insights
 * Author URI:        https://ministryinsights.com//
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       tti-reporting-module
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'TTI_REPORTING_MODULE_VERSION', '1.0.0' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-tti-reporting-module.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_tti_reporting_module() {

	$plugin = new Tti_Reporting_Module();
	$plugin->run();

}
// run_tti_reporting_module();


function my_plugin_function() {
	$plugin = new Tti_Reporting_Module();
	$plugin->run();
}

add_action( 'plugins_loaded', 'my_plugin_function' );
