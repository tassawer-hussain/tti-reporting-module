<?php
/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://ministryinsights.com/
 * @since      1.0.0
 *
 * @package    Tti_Reporting_Module
 * @subpackage Tti_Reporting_Module/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Tti_Reporting_Module
 * @subpackage Tti_Reporting_Module/includes
 * @author     Ministry Insights <support@ministryinsights.com>
 */
class Tti_Reporting_Module_i18n {

	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'tti-reporting-module',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
