<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://shebinkp.co.in
 * @since      1.0.0
 *
 * @package    Wishist_By_Alsaqr
 * @subpackage Wishist_By_Alsaqr/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Wishist_By_Alsaqr
 * @subpackage Wishist_By_Alsaqr/includes
 * @author     Shebin KP <shebinkp7@gmail.com>
 */
class Wishist_By_Alsaqr_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'wishist-by-alsaqr',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
