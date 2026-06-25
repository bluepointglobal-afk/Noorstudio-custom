<?php
/**
 * NoorStudio theme functions.
 *
 * @package NoorStudio
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'NOOR_VERSION', '1.0.0' );

require_once get_theme_file_path( 'inc/helpers.php' );
require_once get_theme_file_path( 'inc/content.php' );
require_once get_theme_file_path( 'inc/seo.php' );

/**
 * Theme supports + menus.
 */
function noor_setup() {
	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'html5', array( 'search-form', 'gallery', 'caption', 'style', 'script', 'navigation-widgets' ) );
	add_theme_support( 'automatic-feed-links' );
	add_theme_support( 'responsive-embeds' );
	add_theme_support(
		'custom-logo',
		array(
			'height'      => 52,
			'width'       => 200,
			'flex-height' => true,
			'flex-width'  => true,
		)
	);

	register_nav_menus(
		array(
			'primary'        => __( 'Primary Navigation', 'noorstudio' ),
			'footer_product' => __( 'Footer — Product', 'noorstudio' ),
			'footer_resources' => __( 'Footer — Resources', 'noorstudio' ),
			'footer_company' => __( 'Footer — Company', 'noorstudio' ),
			'footer_legal'   => __( 'Footer — Legal', 'noorstudio' ),
		)
	);

	load_theme_textdomain( 'noorstudio', get_theme_file_path( 'languages' ) );
}
add_action( 'after_setup_theme', 'noor_setup' );

/**
 * Enqueue fonts, styles, and the small interaction script.
 *
 * Fonts are loaded with preconnect (added in header.php) + display=swap to
 * keep them off the critical render path (Core Web Vitals: CLS/LCP).
 */
function noor_assets() {
	// Google Fonts — exactly the three families used by the design.
	wp_enqueue_style(
		'noor-fonts',
		'https://fonts.googleapis.com/css2?family=Source+Serif+4:ital,opsz,wght@0,8..60,400;0,8..60,500;0,8..60,600;0,8..60,700;1,8..60,400;1,8..60,500&family=Inter:wght@400;500;600;700&family=IBM+Plex+Mono:wght@400;500&display=swap',
		array(),
		null
	);

	wp_enqueue_style( 'noorstudio', get_stylesheet_uri(), array( 'noor-fonts' ), NOOR_VERSION );

	wp_enqueue_script( 'noorstudio', noor_asset( 'assets/js/main.js' ), array(), NOOR_VERSION, true );
}
add_action( 'wp_enqueue_scripts', 'noor_assets' );

/**
 * Preconnect to the Google Fonts hosts (LCP).
 */
function noor_resource_hints( $hints, $relation_type ) {
	if ( 'preconnect' === $relation_type ) {
		$hints[] = array( 'href' => 'https://fonts.googleapis.com' );
		$hints[] = array(
			'href'        => 'https://fonts.gstatic.com',
			'crossorigin' => 'anonymous',
		);
	}
	return $hints;
}
add_filter( 'wp_resource_hints', 'noor_resource_hints', 10, 2 );

/**
 * Preload the hero portrait so it can be the LCP element (target < 2.5s).
 */
function noor_preload_hero() {
	if ( is_front_page() ) {
		$hero = get_theme_file_path( 'assets/images/img-02.jpg' );
		if ( file_exists( $hero ) ) {
			printf(
				'<link rel="preload" as="image" href="%s" fetchpriority="high" />' . "\n",
				esc_url( noor_asset( 'assets/images/img-02.jpg' ) )
			);
		}
	}
}
add_action( 'wp_head', 'noor_preload_hero', 2 );

/**
 * Output a primary menu if one is assigned, else a fallback built from the
 * internal-linking map in inc/content.php.
 */
function noor_primary_menu() {
	if ( has_nav_menu( 'primary' ) ) {
		wp_nav_menu(
			array(
				'theme_location' => 'primary',
				'container'      => false,
				'menu_class'     => 'nav-links',
				'depth'          => 1,
				'fallback_cb'    => false,
			)
		);
		return;
	}
	echo '<ul class="nav-links">';
	foreach ( noor_nav_links() as $link ) {
		printf( '<li><a href="%s">%s</a></li>', noor_link( $link['url'] ), esc_html( $link['label'] ) );
	}
	echo '</ul>';
}

/**
 * Footer column with a menu fallback to a provided link list.
 *
 * @param string $location Menu theme location.
 * @param string $heading  Column heading.
 * @param array  $fallback array of ['label'=>, 'url'=>].
 */
function noor_footer_col( $location, $heading, $fallback ) {
	echo '<div class="footer-col">';
	printf( '<h2>%s</h2>', esc_html( $heading ) );
	if ( has_nav_menu( $location ) ) {
		wp_nav_menu(
			array(
				'theme_location' => $location,
				'container'      => false,
				'items_wrap'     => '%3$s',
				'depth'          => 1,
				'fallback_cb'    => false,
			)
		);
	} else {
		foreach ( $fallback as $link ) {
			printf( '<a href="%s">%s</a>', noor_link( $link['url'] ), esc_html( $link['label'] ) );
		}
	}
	echo '</div>';
}
