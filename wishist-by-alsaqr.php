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
		// checking whether cookie already exist in borwser
		if(isset($_COOKIE['wishlist_alsaqr_data'])){
			$cookiedata = unserialize(base64_decode($_COOKIE['wishlist_alsaqr_data']));
			/**
			 * check product exisit in cooke arrays
			 */
			if(in_array($id , $cookiedata)){
				$activeClass =  'fav-prodcut';
			}
			else{
				$activeClass = '';
			}
			
			
		}
		// creating cookie and assigning the value
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

/**
 * Adding to wish list item to db if user logged in or adding wish list to cookie 
 */

add_action('wp_ajax_nopriv_add_to_wishlist', 'add_to_wishlist');
add_action('wp_ajax_add_to_wishlist', 'add_to_wishlist');


function add_to_wishlist(){
	global $wpdb;
	// check_ajax_referer('alsaqr_wishlist', 'security');
	$cookiedata = [];
	$table_name = $wpdb->prefix . "wishlist_alsaqr";
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
		// checking whether cookie already exist in borwser
		if(isset($_COOKIE['wishlist_alsaqr_data'])){
			$cookiedata = unserialize(base64_decode($_COOKIE['wishlist_alsaqr_data']));


				
			/**
			 * check product exisit in cooke arrays
			 */
			if(in_array($pid , $cookiedata)){
				$cookiedata = array_diff($cookiedata, array($pid));
			}
			else{
				array_push($cookiedata , $pid);
			}
			
			
			$cookieserialized = base64_encode(serialize($cookiedata));
			echo serialize($cookiedata);
			
			setcookie('wishlist_alsaqr_data', json_encode($cookieserialized) , time()+31556926 , '/' );
			// setcookie('wishlist_alsaqr_user', $cookieserialized , time()+31556926 , '/' );
		}
		// creating cookie and assigning the value
		else{
			array_push($cookiedata , $pid);
			$cookieserialized = base64_encode(serialize($cookiedata));
			setcookie('wishlist_alsaqr_data', $cookieserialized , time()+31556926 , '/' );
			echo $cookieserialized;
		}
		
		
	}


}	

/**
 * short code for displaying products
 */

function favoriteProductdisplay($attr){
	
	global $wpdb;
	$table_name = $wpdb->prefix . "wishlist_alsaqr";
	$wishlist_items = null;
	/**
	 * Bug , echoing the result in admin dashboard temporary fix for that by adding conditional check is_admin before executing it
	 */
	if(!is_admin()){
		if(is_user_logged_in(  )){
			$usid = get_current_user_id();
			$wishlist_items = $wpdb->get_results (
				"SELECT * 
				FROM  $table_name 
				WHERE user_id =  $usid ");

				
		}
		else{
			// checking whether cookie already exist in borwser
			if(isset($_COOKIE['wishlist_alsaqr_data'])){
				$cookiedata = unserialize(base64_decode($_COOKIE['wishlist_alsaqr_data']));


					
				/**
				 * check product exisit in cooke arrays
				 */
				if( !empty($cookiedata)){
					$wishlist_items = $cookiedata;
				}
				else{
					$wishlist_items = null;
				}
			}

		}

		if($wishlist_items  != null){
			?>
			<section class="wishlist-sec">   
				<div class="shop-title-banner">
					<h1 class="shop-title sml-flwr blu-flwer">Wishlist</h1>
				</div>
						
				<div class="wishlist-containr ">

			<?php

			foreach($wishlist_items as $item){
			
				$query = new WP_Query( array(
					'post_type'      => 'product',
					'p' => is_object($item)  ? $item->product_id  : $item ,
				));
				
				if($query->have_posts()){
					while ( $query->have_posts() ) {
						$query->the_post();
						do_action( 'woocommerce_shop_loop' );

						wc_get_template_part( 'content', 'product' );
					}
				}
				
			}
			?>
			
					</div>
				</section>
				<?php
				
		}
		else{
			echo '<section class="error-404 empty-wishlist  not-found d-flex align-items-center justify-content-center flex-column">
						<div class="page-content m-auto">
								<div class="d-flex justify-content-center flex-column align-items-center">
									<svg xmlns="http://www.w3.org/2000/svg" width="186" height="138" viewBox="0 0 186 138">
										<g id="Group_711" data-name="Group 711" transform="translate(-872 -435)">
										<rect id="Rectangle_309" data-name="Rectangle 309" width="186" height="138" transform="translate(872 435)" fill="#fff"/>
										<g id="Group_710" data-name="Group 710" transform="translate(99 -13.611)">
											<path id="Path_168" data-name="Path 168" d="M51.157,0H46.888A46.9,46.9,0,0,0,0,46.828V67.671a7.364,7.364,0,0,0,14.7,0V47.911a4.676,4.676,0,0,1,9.328,0V80.889a5.438,5.438,0,0,0,4.517,5.346H93.438a5.416,5.416,0,0,0,4.517-5.346V46.828A46.9,46.9,0,0,0,51.157,0" transform="translate(805 473)" fill="#27aae1"/>
											<path id="Path_169" data-name="Path 169" d="M51.157,0H46.888A46.9,46.9,0,0,0,0,46.828V67.671a7.364,7.364,0,0,0,14.7,0V47.911a4.676,4.676,0,0,1,9.328,0V80.889a5.4,5.4,0,0,0,5.331,5.391H92.6A5.4,5.4,0,0,0,98,80.889V46.828A46.9,46.9,0,0,0,51.157,0" transform="translate(805 473)" fill="#6ecde5"/>
											<path id="Path_170" data-name="Path 170" d="M39.949,44.88a12.914,12.914,0,1,0,0,.068" transform="translate(822.646 513.182)" fill="#ffea45"/>
											<path id="Path_171" data-name="Path 171" d="M55.029,44.88a12.914,12.914,0,1,0,0,.068" transform="translate(841.625 513.182)" fill="#ffea45"/>
											<path id="Path_172" data-name="Path 172" d="M12.79,17.738a1.241,1.241,0,0,0,1.22-1.241,4.337,4.337,0,0,1,8.65,0,1.242,1.242,0,1,0,2.462,0,6.776,6.776,0,0,0-13.552,0,1.241,1.241,0,0,0,1.22,1.241" transform="translate(819.562 485.218)" fill="#0f1d4a"/>
											<path id="Path_173" data-name="Path 173" d="M36.219,39.316a16.038,16.038,0,1,0,0-32.076H21.38a1.219,1.219,0,0,0-1.22,1.218v14.82A16.071,16.071,0,0,0,36.219,39.316M22.667,9.676H36.219A13.534,13.534,0,1,1,22.667,23.21Z" transform="translate(830.373 482.091)" fill="#0f1d4a"/>
										</g>
										</g>
									</svg>
							
									<h1 class="shop-title">Your wishlist is empty</h1>
									<a href="'. site_url() .'" class="primary-btn bg-blue">Return to homepage</a>
								</div>
			
								
			
						</div><!-- .page-content -->
					</section><!-- .error-404 -->';
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
					</form>';
	// }
 
}
 
add_shortcode( 'favproduct_remove_btn' , 'favoriteRemoveProduct' );

/**
 * Favourite button in single product page
 */

function favroutieCartButton($attr){
	global $wpdb;
	$id = ($attr['id']!='' ? $attr['id'] : '0') ; 

	$table_name = $wpdb->prefix . "wishlist_alsaqr";



	if(is_user_logged_in()){
		
		$usid = get_current_user_id();
		$result = $wpdb->get_results (
			"
			SELECT * 
			FROM  $table_name 
			WHERE user_id =  $usid AND product_id = $id");
	}
	else{
		// checking whether cookie already exist in borwser
		if(isset($_COOKIE['wishlist_alsaqr_data'])){
			$cookiedata = unserialize(base64_decode($_COOKIE['wishlist_alsaqr_data']));
			/**
			 * check product exisit in cooke arrays
			 */
			if(in_array($id , $cookiedata)){
				$result = 'fav-prodcut';
			}
			else{
				$result = null ;
			
			}
			
			
		}
		// creating cookie and assigning the value
		else{
			$result = null;
		}
	}
	
		if($result == null){
			$btnContent =  '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="20.524" viewBox="0 0 24 20.524">
								<path id="Path_2497" data-name="Path 2497" d="M-.562-17.062l.52.563.563-.562A6.819,6.819,0,0,1,6.4-18.933,6.7,6.7,0,0,1,12-12.323v.272A7.012,7.012,0,0,1,9.769-6.919L1.3.989A1.9,1.9,0,0,1,0,1.5,1.9,1.9,0,0,1-1.3.989L-9.769-6.919A7.019,7.019,0,0,1-12-12.052v-.272a6.7,6.7,0,0,1,5.6-6.609,6.738,6.738,0,0,1,5.841,1.87c-.042,0,0,0,0,0Zm.52,3.745-2.109-2.194a4.549,4.549,0,0,0-3.881-1.2,4.451,4.451,0,0,0-3.717,4.39v.272A4.767,4.767,0,0,0-8.234-8.564L0-.877,8.236-8.564A4.77,4.77,0,0,0,9.75-12.052v-.272a4.453,4.453,0,0,0-3.717-4.39,4.549,4.549,0,0,0-3.881,1.2L-.042-13.317Z" transform="translate(12 19.024)" fill="#6ecde5"/>
							</svg>
							add to wishlist';

		}
		else{
			$btnContent = '<svg xmlns="http://www.w3.org/2000/svg" width="21" height="24" viewBox="0 0 21 24">
								<path id="Path_2492" data-name="Path 2492" d="M-4.163-20.171A1.5,1.5,0,0,1-2.822-21H2.822a1.5,1.5,0,0,1,1.341.829L4.5-19.5H9A1.5,1.5,0,0,1,10.5-18,1.5,1.5,0,0,1,9-16.5H-9A1.5,1.5,0,0,1-10.5-18,1.5,1.5,0,0,1-9-19.5h4.5ZM8.006.848A2.254,2.254,0,0,1,5.761,3H-5.761A2.256,2.256,0,0,1-8.007.848L-9.042-15H9Z" transform="translate(10.5 21)" fill="#6ecde5"/>
							</svg>
							Remove from wishlist';
		}
	// if($args != null){
			return '<form method="POST" class="cartFavform" >
						<input type="hidden" class="prid" name="prid" value="'.$id.'" />
						
						<button type="submit" class="primary-btn brdr-blue fav-btn full-width">
							'.$btnContent.'
						</button>
					</form>';
	// }
 
}

 
add_shortcode( 'favproduct_single_add_btn' , 'favroutieCartButton' );




add_filter( 'woocommerce_add_to_cart_redirect', 'al_saqr_redirect_to_checkout_add_cart' );
 
function al_saqr_redirect_to_checkout_add_cart( $url ) {
	

	global $wpdb;
	$table_name = $wpdb->prefix . "wishlist_alsaqr";
	

	
	if(isset($_GET['add-to-cart'])){
		$pid = $_GET['add-to-cart'];
		if(is_user_logged_in()){
			$usid = get_current_user_id();
			$result1 = $wpdb->get_results (
				"
				SELECT * 
				FROM  $table_name 
				WHERE user_id =  $usid AND product_id = $pid");


			if($result1 != null){
				$wpdb->delete($table_name, array( 
					"user_id" => $usid,
					"product_id"=> $pid
					));
					
				return;
			}
		}
		else{
			// checking whether cookie already exist in borwser
			if(isset($_COOKIE['wishlist_alsaqr_data'])){
				$cookiedata = unserialize(base64_decode($_COOKIE['wishlist_alsaqr_data']));


					
				/**
				 * check product exisit in cooke arrays
				 */
				if(in_array($pid , $cookiedata)){
					$cookiedata = array_diff($cookiedata, array($pid));
				}
				
				
				
				$cookieserialized = base64_encode(serialize($cookiedata));
				
				
				setcookie('wishlist_alsaqr_data', json_encode($cookieserialized) , time()+31556926 , '/' );
				// setcookie('wishlist_alsaqr_user', $cookieserialized , time()+31556926 , '/' );
			

		}

		}
	
	}

 

    return wc_get_cart_url();       
}


/**
 * Upload cookie wishlist to db after user register
 */
add_action( 'user_register', 'uploadalsaqrcookietodb' );

function uploadalsaqrcookietodb($user_id){
	global $wpdb;
	$table_name = $wpdb->prefix . "wishlist_alsaqr";
	// checking whether cookie already exist in borwser
	if(isset($_COOKIE['wishlist_alsaqr_data'])){
		$cookiedata = unserialize(base64_decode($_COOKIE['wishlist_alsaqr_data']));

		if( !empty($cookiedata)){

			

			$wishlist_items = $cookiedata;
			foreach($wishlist_items as $item){
			
						$wpdb->insert($table_name,array(
							"user_id" => $user_id,
							"product_id"=>$item
							));
						
					
			
				
				
			}
			setcookie('wishlist_alsaqr_data', '' , time()-31556926 , '/' );
		
		}
		
	}
}


/**
 * Upload cookie wishlist to db after user logged in
 */
function uploadalsaqrcookietodbafterlogin($user_login, WP_User $user) {
	global $wpdb;
	$table_name = $wpdb->prefix . "wishlist_alsaqr";
	// checking whether cookie already exist in borwser
	if(isset($_COOKIE['wishlist_alsaqr_data'])){
		$cookiedata = unserialize(base64_decode($_COOKIE['wishlist_alsaqr_data']));

		if( !empty($cookiedata)){

			

			$wishlist_items = $cookiedata;
			foreach($wishlist_items as $item){
				$wpdb->get_results ("SELECT * FROM  $table_name 
					WHERE user_id =  $user->ID AND product_id = $item");

					if($wpdb->num_rows <= 0){
						$wpdb->insert($table_name,array(
							"user_id" => $user->ID,
							"product_id"=>$item
							));
						
					}
			
				
				
			}
			setcookie('wishlist_alsaqr_data', '' , time()-31556926 , '/' );
		
		}
		
	}

}
add_action('wp_login', 'uploadalsaqrcookietodbafterlogin', 10, 2);