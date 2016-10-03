<?php
/**
 * Plugin Name: RP3
 * Description: Custom post types and taxonomies and other custom functionality used by RP3.
 * Author: RP3 Agency
 * Author URI: http://rp3agency.com
 */

define( 'RP3_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'RP3_PLUGIN_INC_DIR', RP3_PLUGIN_DIR . '/inc' );

require_once( RP3_PLUGIN_INC_DIR . '/custom-post-types.php' );
require_once( RP3_PLUGIN_INC_DIR . '/home-page-featured.php' );
require_once( RP3_PLUGIN_INC_DIR . '/remove-dfi-meta-box.php' );
require_once( RP3_PLUGIN_INC_DIR . '/custom-relabel-post-to-blog.php' );
require_once( RP3_PLUGIN_INC_DIR . '/rest-api-extensions.php' );
require_once( RP3_PLUGIN_INC_DIR . '/chrome-admin-menu-fix.php' );
require_once( RP3_PLUGIN_INC_DIR . '/seo-coauthor-fix.php' );


function rp3_copy_permalink() {

	if ( class_exists( 'Sharing_Source' ) ) {

		class Share_Copy_Permalink extends Sharing_Source {
			
		}
	}
}

add_action( 'plugins_loaded', 'rp3_copy_permalink' );

/**
 * Filter the Yoast Google Analytics Plugin to suit our needs
 */

function rp3_yoast_ga_filter( $gaq_push ) {

	array_pop( $gaq_push );

	return $gaq_push;

}

add_filter( 'yoast-ga-push-array-universal', 'rp3_yoast_ga_filter', 1 );
