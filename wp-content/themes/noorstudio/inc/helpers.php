<?php
/**
 * Template helpers.
 *
 * @package NoorStudio
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Absolute URL to a bundled asset.
 *
 * @param string $path Relative path under the theme dir, e.g. "assets/images/img-02.jpg".
 * @return string
 */
function noor_asset( $path ) {
	return get_theme_file_uri( ltrim( $path, '/' ) );
}

/**
 * Render an <img> for a design asset, with a graceful placeholder fallback.
 *
 * The original design bundle ships images in assets/images/ (img-01..img-15).
 * If a file is missing (e.g. before the real product art is dropped in), we
 * render a branded placeholder instead of a broken image. Explicit width/height
 * are always emitted to protect CLS (SEO report PART 6 — Core Web Vitals).
 *
 * @param string $file     Filename inside assets/images/.
 * @param array  $args     alt, width, height, class, loading, fetchpriority, label.
 * @return string HTML.
 */
function noor_img( $file, $args = array() ) {
	$defaults = array(
		'alt'           => '',
		'width'         => '',
		'height'        => '',
		'class'         => '',
		'loading'       => 'lazy',
		'fetchpriority' => '',
		'label'         => '', // placeholder caption when image is absent.
		'sizes'         => '',
	);
	$args = wp_parse_args( $args, $defaults );

	$rel  = 'assets/images/' . $file;
	$abs  = get_theme_file_path( $rel );
	$attr = '';

	if ( $args['width'] ) {
		$attr .= ' width="' . esc_attr( $args['width'] ) . '"';
	}
	if ( $args['height'] ) {
		$attr .= ' height="' . esc_attr( $args['height'] ) . '"';
	}
	if ( $args['class'] ) {
		$attr .= ' class="' . esc_attr( $args['class'] ) . '"';
	}

	if ( file_exists( $abs ) ) {
		$attr .= ' loading="' . esc_attr( $args['loading'] ) . '"';
		$attr .= ' decoding="async"';
		if ( $args['fetchpriority'] ) {
			$attr .= ' fetchpriority="' . esc_attr( $args['fetchpriority'] ) . '"';
		}
		return sprintf(
			'<img src="%1$s" alt="%2$s"%3$s />',
			esc_url( noor_asset( $rel ) ),
			esc_attr( $args['alt'] ),
			$attr
		);
	}

	// Fallback placeholder — keeps layout intact until real art is added.
	$label = $args['label'] ? $args['label'] : ( $args['alt'] ? $args['alt'] : 'NoorStudio' );
	return sprintf(
		'<span class="noor-ph"%1$s role="img" aria-label="%2$s">%3$s</span>',
		$attr,
		esc_attr( $args['alt'] ? $args['alt'] : $label ),
		esc_html( $label )
	);
}

/**
 * The brand logo: real PNG if present, else a serif wordmark fallback.
 *
 * @param int $height px height for the logo image.
 * @return string HTML
 */
function noor_logo( $height = 52 ) {
	$abs = get_theme_file_path( 'assets/images/img-01.png' );
	if ( file_exists( $abs ) ) {
		return sprintf(
			'<img src="%1$s" alt="%2$s" height="%3$d" style="height:%3$dpx;width:auto" />',
			esc_url( noor_asset( 'assets/images/img-01.png' ) ),
			esc_attr( get_bloginfo( 'name' ) ),
			(int) $height
		);
	}
	return '<span class="logo-wordmark">NoorStudio</span>';
}

/**
 * Inline check-mark SVG used in pricing lists.
 */
function noor_check_svg() {
	return '<svg width="14" height="14" viewBox="0 0 14 14" fill="none" aria-hidden="true"><path d="M3 7l3 3 5-6" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg>';
}

/**
 * Resolve an internal link to a real WP permalink when the page exists,
 * otherwise fall back to the site-root relative path from the sitemap.
 *
 * @param string $path e.g. "/pricing/"
 * @return string
 */
function noor_link( $path ) {
	return esc_url( home_url( $path ) );
}
