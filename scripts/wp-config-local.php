<?php

$hostname = 'dev.site.rp3.rp3';

$wp_url = 'http://' . $hostname;

/** Database Credentials */
define('DB_NAME',          'rp3_site_dev');
define('DB_USER',          'root');
define('DB_PASSWORD',      'password');
define('DB_HOST',          'localhost');
$table_prefix  = 'rp3wp_';

/** wp-content Location */
define( 'WP_CONTENT_DIR', realpath( $_SERVER['DOCUMENT_ROOT'] ) . '/wp-content' );
define( 'WP_CONTENT_URL', $wp_url . '/wp-content' );
define( 'WP_PLUGIN_DIR', WP_CONTENT_DIR . '/plugins' );

define( 'WP_HOME', $wp_url );
define( 'WP_SITEURL', WP_HOME . '/_wp' );

/** Debugging */
define( 'WP_DEBUG', true );
define( 'WP_DEBUG_DISPLAY', true );
define( 'WP_DEBUG_LOG', true );
define( 'JETPACK_DEV_DEBUG', true );

/** Caching */
define( 'WPCACHEHOME', WP_PLUGIN_DIR . '/wp-super-cache' );
define( 'WP_CACHE', false );

/** AWS Access Keys */
define( 'AWS_ACCESS_KEY_ID', 'AKIAJXBU4SAFVSTA4Z2Q' );
define( 'AWS_SECRET_ACCESS_KEY', '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' );

define( 'WP_SES_ACCESS_KEY', AWS_ACCESS_KEY_ID );
define( 'WP_SES_SECRET_KEY', AWS_SECRET_ACCESS_KEY );

/** Miscellaneous */
define( 'WP_MEMORY_LIMIT', '512M' );
