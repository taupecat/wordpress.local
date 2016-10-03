<?php
/**
 * RP3 functions and definitions
 *
 * @package RP3
 */

/**
 * Set the content width based on the theme's design and stylesheet.
 */
if ( ! isset( $content_width ) ) {
	$content_width = 1200; /* pixels */
}

if ( ! function_exists( 'rp3_setup' ) ) :
/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 */
function rp3_setup() {

	/*
	 * Make theme available for translation.
	 * Translations can be filed in the /languages/ directory.
	 * If you're building a theme based on RP3, use a find and replace
	 * to change 'rp3' to the name of your theme in all the template files
	 */
	load_theme_textdomain( 'rp3', get_template_directory() . '/languages' );

	// Add default posts and comments RSS feed links to head.
	add_theme_support( 'automatic-feed-links' );

	/*
	 * Enable support for Post Thumbnails on posts and pages.
	 *
	 * @link http://codex.wordpress.org/Function_Reference/add_theme_support#Post_Thumbnails
	 */
	add_theme_support( 'post-thumbnails' );

	require get_template_directory() . '/inc/menus.php';

	// Enable support for Post Formats.
	// add_theme_support( 'post-formats', array( 'aside', 'image', 'video', 'quote', 'link' ) );

	// Setup the WordPress core custom background feature.
	add_theme_support( 'custom-background', apply_filters( 'rp3_custom_background_args', array(
		'default-color' => 'ffffff',
		'default-image' => '',
	) ) );

	// Enable support for HTML5 markup.
	add_theme_support( 'html5', array(
		'comment-list',
		'search-form',
		'comment-form',
		'gallery',
	) );

	// Enable title tag support (WordPress 4.1+)
	add_theme_support( 'title-tag' );
}
endif; // rp3_setup
add_action( 'after_setup_theme', 'rp3_setup' );

/**
 * Widget areas for this theme.
 */
require get_template_directory() . '/inc/widgets.php';

/**
 * Enqueue scripts and styles.
 */
function rp3_scripts() {

	$prerequisites = array(
		'jquery',
		'backbone',
		'underscore',
		'wp-api',
		'rp3-plugins'
	);

	if ( is_page( 'contact' ) ) {
		array_push( $prerequisites, 'rp3-google-maps' );
	}

	if ( ( is_front_page() ) || ( is_singular( 'rp3_cpt_work' ) ) ) {
		array_push( $prerequisites, 'froogaloop' );
	}

	/** Pinterest for the news and blog single pages, and the blog landing page (is_home()) */

	if ( ( is_singular( 'rp3_cpt_news' ) ) || ( is_singular( 'post' ) ) || ( is_home() ) ) {

		wp_register_script( 'pinterest', '//assets.pinterest.com/js/pinit.js', array(), rp3_cache_busting(), true );
		array_push( $prerequisites, 'pinterest' );
	}

	wp_register_style( 'rp3-style', get_stylesheet_directory_uri() . '/css/rp3.min.css', array(), rp3_cache_busting() );

	if ( WP_DEBUG ) {

		/** Uniminified for debugging */

		wp_register_script( 'rp3-vendor', get_template_directory_uri() . '/js/rp3-vendor.js', array(), rp3_cache_busting() );
		wp_register_script( 'rp3-plugins', get_template_directory_uri() . '/js/rp3-plugins.js', array( 'jquery' ), rp3_cache_busting(), true );
		wp_register_script( 'rp3-google-maps', '//maps.googleapis.com/maps/api/js?key=AIzaSyDbQMhVNXn8QRMNJoiWJeemZ63O4NN75kI');
		wp_register_script( 'rp3-javascript', get_template_directory_uri() . '/js/rp3.js', $prerequisites, rp3_cache_busting(), true );

	} else {

		/** Minified for production */

		wp_register_script( 'rp3-vendor', get_template_directory_uri() . '/js/rp3-vendor.min.js', array(), rp3_cache_busting() );
		wp_register_script( 'rp3-plugins', get_template_directory_uri() . '/js/rp3-plugins.min.js', array( 'jquery' ), rp3_cache_busting(), true );
		wp_register_script( 'rp3-google-maps', '//maps.googleapis.com/maps/api/js?key=AIzaSyDbQMhVNXn8QRMNJoiWJeemZ63O4NN75kI');
		wp_register_script( 'rp3-javascript', get_template_directory_uri() . '/js/rp3.min.js', $prerequisites, rp3_cache_busting(), true );
	}

	// wp_enqueue_style( 'wawf-fonts' );
	wp_enqueue_style( 'rp3-style' );
	wp_enqueue_script( 'rp3-vendor' );
	wp_enqueue_script( 'rp3-javascript' );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'rp3_scripts' );

/**
 * Add page slug into body class
 */
function rp3_add_slug_body_class( $classes ) {
	global $post;

	if ( isset ( $post ) ) {
		$classes[] = $post->post_type . '-' . $post->post_name;
	}

	if ( is_singular( 'post' ) ) {

		$classes[] = 'single-post-blog';

	} elseif ( is_singular( 'rp3_cpt_work' ) ) {

		$classes[] = 'single-post-rp3_cpt_work';
	}

	return $classes;
}
add_filter( 'body_class', 'rp3_add_slug_body_class' );

/**
 * Add filter for stripping out <a href=""> tags from content (for the blog archives).
 */
function rp3_strip_anchor_filter( $content ) {
	return preg_replace( '/<\/?a[^>]*>/', '', $content );
}

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Custom functions that act independently of the theme templates.
 */
require get_template_directory() . '/inc/extras.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';

/**
 * Load Jetpack compatibility file.
 */
require get_template_directory() . '/inc/jetpack.php';

/**
 * Custom filters for this theme.
 */
require get_template_directory() . '/inc/filters.php';

/**
 * Custom image sizes for this theme.
 */
require get_template_directory() . '/inc/image-sizes.php';

/**
 * Display people on the "About" page
 */
require get_template_directory() . '/inc/display-people.php';

/**
 * Pre-get-post filters
 */
require get_template_directory() . '/inc/pre-get-posts.php';

/**
 * Editor Styles
 */
require get_template_directory() . '/inc/editor-styles.php';

/**
 * Miscellaneous
 */

// Dequeue stylesheets that come with plugins
function rp3_dequeue_plugin_css() {
	wp_dequeue_style( 'main-style' );
	wp_dequeue_style( 'rpbcStyle' );
	wp_dequeue_style( 'yarppWidgetCss' );
}
add_action( 'wp_enqueue_scripts', 'rp3_dequeue_plugin_css' );

// https://wordpress.org/support/topic/prevent-loading-relatedcss-and-widgetcss
function rp3_dequeue_yarpp_related_css() {
	wp_dequeue_style( 'yarppRelatedCss' );
}
add_action( 'get_footer', 'rp3_dequeue_yarpp_related_css' );


// Add Favicon
function rp3_favicon() {
	echo '<link rel="apple-touch-icon" sizes="180x180" href="' . esc_url( get_stylesheet_directory_uri() . '/images/apple-touch-icon.png' ) . '">';
	echo '<link rel="icon" type="image/png" href="' . esc_url( get_stylesheet_directory_uri() . '/images/favicon-32x32.png' ) . '" sizes="32x32">';
	echo '<link rel="icon" type="image/png" href="' . esc_url( get_stylesheet_directory_uri() . '/images/favicon-16x16.png' ) . '" sizes="16x16">';
	echo '<link rel="mask-icon" href="' . esc_url( get_stylesheet_directory_uri() . '/images/safari-pinned-tab.svg' ) . '">';
	echo '<link rel="shortcut icon" href="' . esc_url( get_stylesheet_directory_uri() . '/images/favicon.ico' ) . '">';
}
add_action( 'wp_head', 'rp3_favicon' );


/**
 * Custom excerpts for News & Blog
 */
if ( ! function_exists( 'rp3_all_excerpts_get_more_link' ) ) {

	function rp3_all_excerpts_get_more_link( $post_excerpt ) {

		return '<p>' . $post_excerpt . ' <span class="link continue">Continue Reading</span></p>';
	}
}

add_filter( 'wp_trim_excerpt', 'rp3_all_excerpts_get_more_link' );

if ( ! function_exists( 'rp3_excerpt_length' ) ) {

	function rp3_excerpt_length( $length ) {
		return 20;
	}
}

add_filter( 'excerpt_length', 'rp3_excerpt_length' );

if ( ! function_exists( 'rp3_excerpt_more' ) ) {

	function rp3_excerpt_more( $more ) {
		return '…';
	}
}

add_filter( 'excerpt_more', 'rp3_excerpt_more' );


if ( ! function_exists( 'rp3_add_json_offset' ) ) {

	function rp3_add_json_offset( $valid_vars ) {
		$valid_vars[] = 'offset';
		return $valid_vars;
	}
}
add_filter( 'json_query_vars', 'rp3_add_json_offset' );

/**
 * Create a Limit Login Attempts whitelist for the Agency's IP address
 * https://wordpress.org/plugins/limit-login-attempts/faq/
 */
function rp3_limit_login_attempt_whitelist( $allow, $ip ) {
	return ( $ip == '198.207.29.34' ) ? true : $allow;
}

add_filter( 'limit_login_whitelist_ip', 'rp3_limit_login_attempt_whitelist', 10, 2 );



/** Determine whether to use the primary or alternate featured image */

function rp3_use_alternate_featured_image( $sub_field ) {
	global $dynamic_featured_image;

	$alt_featured_image = false;

	if ( get_sub_field( $sub_field ) ) {

		if ( class_exists( 'Dynamic_Featured_Image' ) ) {
			$alt_featured_image_array = $dynamic_featured_image->get_nth_featured_image( 2 );
			$alt_featured_image = $alt_featured_image_array['attachment_id'];
		}		
	}

	return $alt_featured_image;
}

/** Get comments to appear on blog "archive" pages */

$withcomments = 1;


/** Give us more parameters for our calls to Vimeo from the work items pages */
/** See: http://tinygod.pt/vimeo-embedding-on-wordpress/ */

add_filter( 'oembed_fetch_url','add_param_oembed_fetch_url', 10, 3);
add_filter( 'oembed_result', 'add_player_id_to_iframe', 10, 3);

/** add extra parameters to vimeo request api (oEmbed) */
function add_param_oembed_fetch_url( $provider, $url, $args) {
	// unset args that WP is already taking care
	$newargs = $args;
	unset( $newargs['discover'] );
	unset( $newargs['width'] );
	unset( $newargs['height'] );

	// build the query url
	$parameters = urlencode( http_build_query( $newargs ) );

	return $provider . '&'. $parameters;
}

function add_player_id_to_iframe( $html, $url, $args ) {

	/** add player id to iframe id on vimeo */
	if ( ( isset( $args['player_id'] ) ) && ( 'vimeo' == $args['platform'] ) ) {
		$html = str_replace( '<iframe', '<iframe id="'. $args['player_id'] .'"', $html );
	}

	/** add "enablejsapi" flag to youtube videos */
	if ( ( isset( $args['player_id'] ) ) && ( 'youtube' == $args['platform'] ) ) {
		$html = str_replace( '<iframe', '<iframe enablejsapi="true"', $html );
	}

	return $html;
}

add_filter( 'deprecated_constructor_trigger_error', '__return_false' );

function rp3_editor_style() {

	add_editor_style( get_stylesheet_directory_uri() . '/css/rp3-editor.min.css' );
}

add_action( 'admin_init', 'rp3_editor_style' );

function rp3_editor_remove_styles( $in ) {

	$in['block_formats'] = "Paragraph=p; Heading 1=h1; Heading 2=h2";

	return $in;
}

add_filter( 'tiny_mce_before_init', 'rp3_editor_remove_styles' );


// Set og:image tag for author archive pages
function rp3_author_og_image() {
	if ( is_author() && function_exists( 'get_coauthors' ) ) {

		$authors = get_coauthors();

		foreach ( $authors as $author ) {

			if ( ( is_author() ) && ( get_query_var( 'author_name' ) == $author->user_nicename ) ) {

				$author_image = wp_get_attachment_image_src( get_post_thumbnail_id( $author->ID ), 'four_three_small_2x' );
				$author_description = $author->display_name . ' ' . $author->description;

				echo "\n";
				echo '<meta property="og:image" content="' . $author_image[0] . '"/>' . "\n";
				echo '<meta property="og:description" content="' . $author_description . '"/>' . "\n";
				echo '<meta property="og:image:width" content="640"/>' . "\n";
				echo '<meta property="og:image:height" content="480"/>' . "\n";
			}
		}
	}
}
add_action( 'wp_head', 'rp3_author_og_image' );


/**
 * Add "async" and "defer" attributes to the Pinterest loading script.
 * We may need to modify this filter at a later date if we want to
 * async and defer other scripts (and I suspect we do). TFR
 */

add_filter('script_loader_tag', 'rp3_add_async_defer', 10, 2);

function rp3_add_async_defer( $tag, $handle ) {

	if ( 'pinterest' !== $handle ) {
		return $tag;
	}

	return str_replace( ' src', ' async defer src', $tag );
}

/**
 * Google tag manager code
 */

function rp3_google_tag_manager() {

	include_once( ABSPATH . 'wp-admin/includes/plugin.php' );

	if ( is_plugin_active( 'google-analytics-for-wordpress/googleanalytics.php' ) ) :

		get_template_part( 'template-parts/google-tag-manager' );
	
	endif;
}

add_action( 'wp_head', 'rp3_google_tag_manager' );
