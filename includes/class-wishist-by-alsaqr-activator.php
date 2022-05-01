<?php

/**
 * Fired during plugin activation
 *
 * @link       https://shebinkp.co.in
 * @since      1.0.0
 *
 * @package    Wishist_By_Alsaqr
 * @subpackage Wishist_By_Alsaqr/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Wishist_By_Alsaqr
 * @subpackage Wishist_By_Alsaqr/includes
 * @author     Shebin KP <shebinkp7@gmail.com>
 */
class Wishist_By_Alsaqr_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {
 
			global $wpdb;
		 
			$table_name = $wpdb->prefix . "wishlist_alsaqr";
		 
			$charset_collate = $wpdb->get_charset_collate();
		 
			$sql = "CREATE TABLE IF NOT EXISTS $table_name (
			  id bigint(20) NOT NULL AUTO_INCREMENT,
			  user_id bigint(20) UNSIGNED NOT NULL,
			  product_id bigint(20) UNSIGNED NOT NULL,
			  PRIMARY KEY id (id)
			) $charset_collate;";
		 
			require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
			
			dbDelta($sql);
		}    
	

	}


