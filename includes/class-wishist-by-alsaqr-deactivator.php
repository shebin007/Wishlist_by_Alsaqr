<?php

/**
 * Fired during plugin deactivation
 *
 * @link       https://shebinkp.co.in
 * @since      1.0.0
 *
 * @package    Wishist_By_Alsaqr
 * @subpackage Wishist_By_Alsaqr/includes
 */

/**
 * Fired during plugin deactivation.
 *
 * This class defines all code necessary to run during the plugin's deactivation.
 *
 * @since      1.0.0
 * @package    Wishist_By_Alsaqr
 * @subpackage Wishist_By_Alsaqr/includes
 * @author     Shebin KP <shebinkp7@gmail.com>
 */
class Wishist_By_Alsaqr_Deactivator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function deactivate() {
		global $wpdb;
		 
		$table_name = $wpdb->prefix . "wishlist_alsaqr";
		
		$charset_collate = $wpdb->get_charset_collate();

		$wpdb->query( "DROP TABLE IF EXISTS $table_name" );
	}

}
