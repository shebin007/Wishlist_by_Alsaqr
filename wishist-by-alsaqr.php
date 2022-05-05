<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://shebinkp.co.in
 * @since             1.0.0
 * @package           Wishist_By_Alsaqr
 *
 * @wordpress-plugin
 * Plugin Name:       Wishlist by AlSaqr
 * Plugin URI:        https://shebinkp.co.in
 * Description:       This is a short description of what the plugin does. It's displayed in the WordPress admin area.
 * Version:           1.0.0
 * Author:            Shebin KP
 * Author URI:        https://shebinkp.co.in
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       wishist-by-alsaqr
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
define( 'WISHIST_BY_ALSAQR_VERSION', '1.0.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-wishist-by-alsaqr-activator.php
 */
function activate_wishist_by_alsaqr() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-wishist-by-alsaqr-activator.php';
	Wishist_By_Alsaqr_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-wishist-by-alsaqr-deactivator.php
 */
function deactivate_wishist_by_alsaqr() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-wishist-by-alsaqr-deactivator.php';
	Wishist_By_Alsaqr_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_wishist_by_alsaqr' );
register_deactivation_hook( __FILE__, 'deactivate_wishist_by_alsaqr' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-wishist-by-alsaqr.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_wishist_by_alsaqr() {

	$plugin = new Wishist_By_Alsaqr();
	$plugin->run();

}
run_wishist_by_alsaqr();

/**
 * 
 * Short code for saving wishlist into DB
 */


function favoriteProduct($attr){
	global $wpdb;
	$id = ($attr['id']!='' ? $attr['id'] : '0') ; 
	$activeClass = '';
	$table_name = $wpdb->prefix . "wishlist_alsaqr";
	if(is_user_logged_in()){
		$usid = get_current_user_id();
		$result = $wpdb->get_results (
			"
			SELECT * 
			FROM  $table_name 
			WHERE user_id =  $usid AND product_id = $id");
		if($result != null){
			$activeClass =  'fav-prodcut';

		}
		else{
			$activeClass = '';
		}

	}
	else{
		$wishlist = decode_arr($_COOKIE['alsaqr_wishlist'], true);
		if(in_array($id , $wishlist)){
			$activeClass = 'fav-prodcut';
		}
		else{
			$activeClass = '';
		}
	}
	// if($args != null){
			return '<form method="POST" class="favform " >
						<input type="hidden" class="prid" name="prid" value="'.$id.'" />
						<button type="submit" class="favourite-btn '.$activeClass.' prid-'.$id.' ">
							<svg xmlns="http://www.w3.org/2000/svg" width="24" height="20.524" viewBox="0 0 24 20.524">
								<path id="Path_2474" data-name="Path 2474" d="M-12-12.052v-.272a6.7,6.7,0,0,1,5.6-6.609A6.736,6.736,0,0,1-.562-17.062L0-16.5l.52-.562A6.819,6.819,0,0,1,6.4-18.933,6.7,6.7,0,0,1,12-12.323v.272A7.012,7.012,0,0,1,9.769-6.919L1.3.989A1.9,1.9,0,0,1,0,1.5,1.9,1.9,0,0,1-1.3.989L-9.769-6.919A7.019,7.019,0,0,1-12-12.052Z" transform="translate(12 19.024)" fill="#6ECDE5"/>
							</svg>
						</button>
					</form>
					
				';
	// }
 
}
 
add_shortcode( 'favproduct_btn' , 'favoriteProduct' );

add_action('wp_ajax_nopriv_add_to_wishlist', 'add_to_wishlist');
add_action('wp_ajax_add_to_wishlist', 'add_to_wishlist');

function add_to_wishlist(){
	global $wpdb;
	// check_ajax_referer('alsaqr_wishlist', 'security');
	$table_name = $wpdb->prefix . "wishlist_alsaqr";
	$data = array();

	parse_str($_POST['data'] , $data );
	$pid = $data['prid'];
	if(is_user_logged_in()){
		$usid = get_current_user_id();
		
		$result1 = $wpdb->get_results (
			"
			SELECT * 
			FROM  $table_name 
			WHERE user_id =  $usid AND product_id = $pid");
	

		if($result1 == null){
			$wpdb->insert($table_name,array(
				"user_id" => $usid,
				"product_id"=> $pid
				));
				
				return;
		}
		else{
			
			
			$wpdb->delete($table_name, array( 
				"user_id" => $usid,
				"product_id"=> $pid
				));
			

			return;
		}
		
	}
	else{
		
		
			$wishlist = decode_arr($_COOKIE['alsaqr_wishlist'], true);
			// echo 'wishlist :::' . var_dump($_COOKIE);;
			echo 'pid :::' . $pid;
			echo json_encode($wishlist );
			if(in_array($pid , $wishlist)){
				$wishlist = decode_arr($_COOKIE['alsaqr_wishlist'], true);
				$updatedwishlist = array_diff($wishlist, array($pid));
				setcookie('alsaqr_wishlist', encode_arr($updatedwishlist ), time()+31556926 );
				echo 'removed from favorite';
				return;
			}
			else{
				alsaqrCookie($pid );
			}
		
		
		
	}


}	



function alsaqrCookie($postID){
	$wishlist = decode_arr($_COOKIE['alsaqr_wishlist'], true);
	// echo 'before wishlistupdatd :::' .  json_encode( $wishlist);
	if($wishlist == null){
		$wishlist[] = $postID;

		
	}
	else{
		// echo 'reached second gate :::'. $wishlist;
		array_push($wishlist,$postID);
		// echo 'wihslistupdatd :::' . json_encode( $wishlist);
	}
	
	setcookie('alsaqr_wishlist', encode_arr($wishlist), time()+31556926 , '/' );
	return;
	// echo 'new wishlist :::' .  json_encode($wishlist);
}


function encode_arr($data) {
    return base64_encode(serialize($data));
}

function decode_arr($data) {
    return unserialize(base64_decode($data));
}




// short code for displaying products

function favoriteProductdisplay($attr){
	
	global $wpdb;
	$table_name = $wpdb->prefix . "wishlist_alsaqr";
	$usid = get_current_user_id();
	$wishlist_items = $wpdb->get_results (
		"SELECT * 
		FROM  $table_name 
		WHERE user_id =  $usid ");
	if(!is_admin()){
		foreach($wishlist_items as $item){
		
			$query = new WP_Query( array(
				'post_type'      => 'product',
				'p' => $item->product_id,
			) );
			if($query->have_posts()){
				while ( $query->have_posts() ) {
					$query->the_post();
					do_action( 'woocommerce_shop_loop' );
	
					wc_get_template_part( 'content', 'product' );
				}
			}
		}
	}

	

}
 
add_shortcode( 'alsaqr_favproducts' , 'favoriteProductdisplay' );



function favoriteRemoveProduct($attr){
	global $wpdb;
	$id = ($attr['id']!='' ? $attr['id'] : '0') ; 
	// if($args != null){
			return '<form method="POST" class="removeform" >
						<input type="hidden" class="prid" name="prid" value="'.$id.'" />
						<button type="submit" class="favourite-rm-btn">
							Remove
						</button>
					</form>
					
				';
	// }
 
}
 
add_shortcode( 'favproduct_remove_btn' , 'favoriteRemoveProduct' );