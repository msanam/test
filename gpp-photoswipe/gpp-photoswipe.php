<?php
/*
Plugin Name: GPP Photoswipe	
Description: A photoswipe plugin.
Version: 1.0
License: GPL
Author: Graph Paper Press
Author URI: http://graphpaperpress.com
*/



/**
 * Set plugin constants
 */

define ( 'GPP_PHOTOSWIPE_PLUGIN_URL', WP_PLUGIN_URL . '/' . dirname( plugin_basename( __FILE__ ) ) . '/' );

/*
* Load jquery cycle plugin after the jquery has loaded
* Load css for the front end 
*/

if ( !is_admin() ) add_action( 'wp_enqueue_scripts', 'load_gpp_category_slider_js' );
function  load_gpp_category_slider_js() {
		wp_enqueue_script('klass', GPP_PHOTOSWIPE_PLUGIN_URL.'/inc/photoswipe/klass.min.js', array( 'jquery' ) );	   
		wp_enqueue_script('photoswipe', GPP_PHOTOSWIPE_PLUGIN_URL.'/inc/photoswipe/code.photoswipe.jquery-3.0.4.js', array( 'jquery' ) );
		wp_enqueue_style( 'gpp-photoswipe-style', GPP_PHOTOSWIPE_PLUGIN_URL.'/inc/photoswipe/photoswipe.css');
}

	add_action( 'wp_head', 'gpp_import_export_js' );

function gpp_import_export_js() {
		echo '
		<script type="text/javascript">
			jQuery(document).ready(function(){
				var options = {swipeThreshold:50};
				jQuery(".gallery a").photoSwipe(options);
			});
		</script>';
	}
	
	/**
 * Include all plugin files
 */
require_once( 'plugin-updates/plugin-update-checker.php' );
/**
 * Include css to style the options 
 */
add_action( 'admin_menu', 'load_gpp_photoswipe_css' );
function  load_gpp_photoswipe_css() {
	// Create a custom option menu in Appearance section
	add_theme_page( 'GPP Photoswipe', 'GPP Photoswipe', 'manage_options', __FILE__, 'gpp_photoswipe_page' );
	// Loads css for the admin page	
	if ( is_admin() ) {
		wp_enqueue_style( 'gpp_photoswipe_css', GPP_PHOTOSWIPE_PLUGIN_URL . 'gpp-photoswipe.css' );
	}
}

/**
* gpp_photoswipe_page() displays the page content for the custom menu
*/
function gpp_photoswipe_page() {			
	$storedcats = explode( ",", get_option( 'gpp_category_slider_cats' ) );	
	
	// Get Wordpress Categories
	$cats_array = get_categories();
	$categories = array();
	$multicheckcats = array();
	foreach ( $cats_array as $cats ) {
		$categories[0] = "";
		$categories[$cats->cat_ID] = $cats->cat_name;
		$multicheckcats[$cats->cat_ID] = $cats->cat_name;	
	}

	?>

	<div class="wrap" id="gpp_photoswipe">
		<h2>Photoswipe</h2>		
		<div class="option option-textarea">
			<div class="option-inner">
				<label class="titledesc">Phowoswipe Settings</label>
				<div class="formcontainer">
					<div class="forminp">
						<form id="impexp" method="post" action="#">
							<?php foreach( $multicheckcats as $key => $value ) { ?>
								<input id="gpp_base_slider_cat_<?php echo $key; ?>" class="checkbox" type="checkbox" value="<?php echo $key; ?>" <?php if( in_array( $key, $storedcats ) ) { echo "checked"; } ?> name="gpp_base_slider_cat[<?php echo $key; ?>]">
								<label for="gpp_base_slider_cat_<?php echo $key; ?>"><?php echo $value; ?></label><br />
							<?php } ?>

						<input type="submit" value="Save" id="gppsave" name="gppsave" onClick="return confirm('Are you sure you want to save the settings?')"> 
						</form>
					</div>
					<div class="desc">Choose the categories to be shown in category slider.</div>
				</div>
			</div>
		</div>		
	 </div>
	<?php
}


/**
 * Lets update this plugin from our own downloads server.
 */
$ExampleUpdateChecker = new PluginUpdateChecker(
	'http://downloads.graphpaperpress.com/gpp-photoswipe-plugin/info.json', 
	__FILE__
);