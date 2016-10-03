/**
 * Establish our gulp/node plugins for this project.
 */
var gulp			= require( 'gulp' ),

	// Sass/Compass/related CSSy things
	sass			= require( 'gulp-sass' ),
	postcss			= require( 'gulp-postcss' ),
	autoprefixer	= require( 'autoprefixer' ),
	cssnano			= require( 'gulp-cssnano' ),
	sourcemaps		= require( 'gulp-sourcemaps' ),
	kss				= require( 'kss' ),
	csswring        = require( 'csswring' ),

	// JavaScript
	jshint			= require( 'gulp-jshint' ),
	uglify			= require( 'gulp-uglify' ),

	// File system
	concat			= require( 'gulp-concat' ),
	rename			= require( 'gulp-rename' ),
	del				= require( 'del' ),
	livereload		= require( 'gulp-livereload' ),
	shell			= require( 'gulp-shell' ),
	composer		= require( 'gulp-composer' ),

	// Utilities
	gutil			= require( 'gulp-util' ),
	plumber         = require( 'gulp-plumber' ),
	runSequence		= require( 'run-sequence' ),
	argv			= require( 'yargs' ).argv;

var environment = argv.env;

/**
 * Set our source and distribution variables
 */
var // Project
	project			= 'rp3',	// a short code for establishing things like
								// the resulting JavaScript file, etc.

	// Source files
	src				= __dirname + '/src',
	src_js			= src + '/js',
	src_js_vendor	= src_js + '/vendor',
	src_js_plugins	= src_js + '/plugins',
	src_js_admin	= src_js + '/admin',
	src_sass		= src + '/sass',
	src_theme		= src + '/theme',
	src_plugin		= src + '/plugin',

	// Destination files, WordPress
	dist			= __dirname + '/dist',
	dist_wpContent	= dist + '/wp-content',
	dist_theme		= dist_wpContent + '/themes/' + project,
	dist_theme_js	= dist_theme + '/js',
	dist_theme_css	= dist_theme + '/css',
	dist_plugin		= dist_wpContent + '/mu-plugins/' + project;

/**
 * Now, let's do things.
 */


/* ==========================================================================
   Clean
   ========================================================================== */

gulp.task( 'clean', function() {

	return del( [ dist ] ).then( paths => {

		if ( 0 < paths.length ) {
			console.log( 'Distribution directory (', paths, ') deleted.' );
		}
	});
});


/* ==========================================================================
   CSS
   ========================================================================== */

gulp.task( 'styles', function() {

	var supportedBrowsers = [
		'last 2 versions',
		'safari 5',
		'ie 9',
		'ie 10',
		'ie 11',
		'opera 12.1',
		'ios 6',
		'android 4'
	];

	var result = gulp.src( src_sass + '/**/*.scss' )
		.pipe( sourcemaps.init() )
		.pipe( plumber( function( err ) {
			gutil.beep();
			var errorText = err.message + "\n\n" + err.source;
			gutil.log( gutil.colors.red( errorText ) );
		}))
		.pipe( sass.sync() )
		.pipe( rename( function( path ) {
			path.extname = '.css'
		}))
		.pipe( gulp.dest( dist_theme_css ) )
		.pipe( postcss( [
			autoprefixer( { browsers: supportedBrowsers } ),
			csswring()
		]))
		.pipe( rename( function( path ) {
			path.extname = '.min.css'
		}))
		.pipe( sourcemaps.write( '.' ) )
		.pipe( gulp.dest( dist_theme_css ) );

	if ( 'dist' !== environment ) {
		result.pipe( livereload() );
	}

	return result;
});


/* ==========================================================================
   Styleguide
   ========================================================================== */

gulp.task( 'kss', function() {

	var kss_node = __dirname + '/node_modules/.bin/kss-node',
		command = kss_node + ' --source ' + src_sass + ' --destination ' + dist + '/styleguide --css /wp-content/themes/rp3/css/rp3.css --title "RP3 Agency Web Style Guide"';

	return gulp.src( '' )
		.pipe( shell( command ) )
		.pipe( livereload() );
});


/* ==========================================================================
   JavaScript
   ========================================================================== */

gulp.task( 'scripts:custom', function() {

	var scripts = [
			src_js + '/rp3.js',
			src_js + '/rp3.backbone.js',
			src_js + '/rp3.backbone.*.js',
			src_js + '/rp3.blog-search.js',
			src_js + '/rp3.google-maps.js',
			src_js + '/rp3.scroll-magic.js',
			src_js + '/rp3.skip-link-focus-fix.js',
			src_js + '/rp3.snapsketch.js',
			src_js + '/rp3.video.js'
		],
		result = gulp.src( scripts );

	if ( 'dist' !== environment ) {
		result
			.pipe( jshint( __dirname + '/config/jshint.conf' ) )
			.pipe( jshint.reporter( 'default' ) );
	}

	result
		.pipe( concat( project + '.js' ) )
		.pipe( gulp.dest( dist_theme_js ) )
		.pipe( rename( { suffix: '.min' } ) )
		.pipe( uglify() )
		.pipe( gulp.dest( dist_theme_js ) );

	if ( 'dist' !== environment ) {
		result.pipe( livereload() );
	}

	return result;
});

gulp.task( 'scripts:plugins', function() {

	return gulp.src( src_js_plugins + '/*.js' )
		.pipe( concat( project + '-plugins.js' ) )
		.pipe( gulp.dest( dist_theme_js ) )
		.pipe( rename( { suffix: '.min' } ) )
		.pipe( uglify() )
		.pipe( gulp.dest( dist_theme_js ) );
});

gulp.task( 'scripts:modernizr', function() {

	return gulp.src( '' )
		.pipe( shell(
			'modernizr -c ' + __dirname + '/config/modernizr.json -d ' + src_js_vendor + '/modernizr.js'
		));
});

gulp.task( 'scripts:vendor', [ 'scripts:modernizr' ], function() {

	return gulp.src( [
		src_js_vendor + '/modernizr.js',
		src_js_vendor + '/modernizr.clippath-polygon.js',
		src_js_vendor + '/picturefill.js'
	])
	.pipe( concat( project + '-vendor.js' ) )
	.pipe( gulp.dest( dist_theme_js ) )
	.pipe( rename( { suffix: '.min' } ) )
	.pipe( uglify() )
	.pipe( gulp.dest( dist_theme_js ) );
});

gulp.task( 'scripts:admin', function() {

	return gulp.src( src_js_admin + '/*.js' )
		.pipe( concat( project + '-admin.js' ) )
		.pipe( gulp.dest( dist_theme_js ) )
		.pipe( rename( { suffix: '.min' } ) )
		.pipe( uglify() )
		.pipe( gulp.dest( dist_theme_js ) );
});

gulp.task( 'scripts', function() {
	gulp.start( 'scripts:custom' );
	gulp.start( 'scripts:plugins' );
	gulp.start( 'scripts:vendor' );
	gulp.start( 'scripts:admin' );
});


/* ==========================================================================
   Build
   ========================================================================== */

gulp.task( 'build-theme', [ 'styles', 'scripts' ], function() {

	var result = gulp.src( [ src_theme + '/**/*.*' ] );

	result.pipe( gulp.dest( dist_theme ) );

	if ( 'dist' !== environment ) {
		result.pipe( livereload() );
	}

	return result;
});

gulp.task( 'build-plugin', function() {

	var result;

	result = gulp.src( [ src_plugin + '/**/*.*' ] );

	result.pipe( gulp.dest( dist_plugin ) );

	if ( 'dist' !== environment ) {
		result.pipe( livereload() );
	}

	return result;
});

gulp.task( 'build', [ 'composer', 'build-theme', 'build-plugin' ], function( callback ) {

	gulp.src([
		src + '/favicon.ico',
		src + '/index.php',
		src + '/wp-config.php',
		__dirname + '/scripts/wp-config-local.php'
	]).pipe( gulp.dest( dist ) );

	gulp.src( src + '/mu-plugin.php' )
		.pipe( rename( 'index.php' ) )
		.pipe( gulp.dest( dist_wpContent + '/mu-plugins' ) );

	callback();
});


/* ==========================================================================
   Composer
   ========================================================================== */

gulp.task( 'composer', function() {

	composer({
		'no-ansi':	true
	});
});


/* ==========================================================================
   Default
   ========================================================================== */

gulp.task( 'default', function() {
	gulp.start( 'build' );
	gulp.start( 'kss' );
});


/* ==========================================================================
   Watch
   ========================================================================== */

gulp.task( 'watch', function() {
	// Watch .scss files
	gulp.watch( src_sass + '/**/*.scss', [ 'styles', 'kss' ] );

	// Watch custom JavaScript files
	gulp.watch( src_js + '/*.js', [ 'scripts-custom' ] );

	// Watch admin JavaScript files
	gulp.watch( src_js_admin + '/*.js', [ 'scripts-admin' ] );

	// Watch theme template files
	gulp.watch( src_theme + '/**/*.*', [ 'build-theme' ] );

	// Watch plugin files
	gulp.watch( src_plugin + '/**/*.*', [ 'build-plugin' ] );

	livereload.listen();
	gutil.log( 'LiveReload server activated' );
});
