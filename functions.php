<?php
/**
 * publicinteresttown functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package publicinteresttown
 */

if ( ! defined( '_S_VERSION' ) ) {
	// Replace the version number of the theme on each release.
	define( '_S_VERSION', '1.0.0' );
}

/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 */
function publicinteresttown_setup() {
	/*
		* Make theme available for translation.
		* Translations can be filed in the /languages/ directory.
		* If you're building a theme based on publicinteresttown, use a find and replace
		* to change 'publicinteresttown' to the name of your theme in all the template files.
		*/
	load_theme_textdomain( 'publicinteresttown', get_template_directory() . '/languages' );

	// Add default posts and comments RSS feed links to head.
	add_theme_support( 'automatic-feed-links' );

	/*
		* Let WordPress manage the document title.
		* By adding theme support, we declare that this theme does not use a
		* hard-coded <title> tag in the document head, and expect WordPress to
		* provide it for us.
		*/
	add_theme_support( 'title-tag' );

	/*
		* Enable support for Post Thumbnails on posts and pages.
		*
		* @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
		*/
	add_theme_support( 'post-thumbnails' );

	// This theme uses wp_nav_menu() in one location.
	register_nav_menus(
		array(
			'menu-1' => esc_html__( 'Primary', 'publicinteresttown' ),
		)
	);

	/*
		* Switch default core markup for search form, comment form, and comments
		* to output valid HTML5.
		*/
	add_theme_support(
		'html5',
		array(
			'search-form',
			'comment-form',
			'comment-list',
			'gallery',
			'caption',
			'style',
			'script',
		)
	);

	// Set up the WordPress core custom background feature.
	add_theme_support(
		'custom-background',
		apply_filters(
			'publicinteresttown_custom_background_args',
			array(
				'default-color' => 'ffffff',
				'default-image' => '',
			)
		)
	);

	// Add theme support for selective refresh for widgets.
	add_theme_support( 'customize-selective-refresh-widgets' );

	/**
	 * Add support for core custom logo.
	 *
	 * @link https://codex.wordpress.org/Theme_Logo
	 */
	add_theme_support(
		'custom-logo',
		array(
			'height'      => 250,
			'width'       => 250,
			'flex-width'  => true,
			'flex-height' => true,
		)
	);

}
add_action( 'after_setup_theme', 'publicinteresttown_setup' );

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function publicinteresttown_content_width() {
	$GLOBALS['content_width'] = apply_filters( 'publicinteresttown_content_width', 640 );
}
add_action( 'after_setup_theme', 'publicinteresttown_content_width', 0 );

/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function publicinteresttown_widgets_init() {
	register_sidebar(
		array(
			'name'          => esc_html__( 'Sidebar', 'publicinteresttown' ),
			'id'            => 'sidebar-1',
			'description'   => esc_html__( 'Add widgets here.', 'publicinteresttown' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
		)
	);
}
add_action( 'widgets_init', 'publicinteresttown_widgets_init' );

/**
 * Enqueue scripts and styles.
 */
function publicinteresttown_scripts() {
	wp_enqueue_style( 'publicinteresttown-style', get_stylesheet_uri(), array(), _S_VERSION );

	wp_style_add_data( 'publicinteresttown-style', 'rtl', 'replace' );

	// wp_enqueue_script( 'publicinteresttown-navigation', get_template_directory_uri() . '/js/navigation.js', array(), _S_VERSION, true );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'publicinteresttown_scripts' );

/**
 * Implement the Custom Header feature.
 */
require get_template_directory() . '/inc/custom-header.php';

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Functions which enhance the theme by hooking into WordPress.
 */
require get_template_directory() . '/inc/template-functions.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';

/**
 * Load Jetpack compatibility file.
 */
if ( defined( 'JETPACK__VERSION' ) ) {
	require get_template_directory() . '/inc/jetpack.php';
}

/**
 * Fix RSS
 */
// function pit_turn_off_feed_caching($feed){
// 	$feed->enable_cache(false);
// }
// add_action('wp_feed_options','pit_turn_off_feed_caching');

function pit_feedcachetime( $seconds ) {
  return 3600;
}

function pit_jobs_feed() {
	$url = 'https://mastodon.publicinterest.town/@jobs.rss';

	add_filter('wp_feed_cache_transient_lifetime','pit_feedcachetime');
	$rss  = fetch_feed($url);
	remove_filter('wp_feed_cache_transient_lifetime','pit_feedcachetime');

	print '<div class="jobs-list">';
	foreach ( $rss->get_items( 0, 5 ) as $item ) {
		print '<article class="job">';
		$date = new DateTime($item->get_date(), new \DateTimeZone("UTC"));
		$date->setTimezone(new DateTimeZone('America/New_York'));

		print '<header class="job-header">
		<time class="job-date" datetime="' . $date->format(\DateTime::RFC850) . '">' .
			$date->format(get_option('date_format')) . '</time></header>';

		print '<div class="job-description"> ' . $item->get_description() . '</div>';

		print '</article>';
	}
	print '</div>';
}