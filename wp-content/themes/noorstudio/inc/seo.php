<?php
/**
 * SEO layer — meta tags, Open Graph / Twitter cards, and schema.org JSON-LD.
 *
 * Implements the "NoorStudio — Complete SEO Content Strategy" technical spec:
 *   - PART 2: Homepage meta title / description / canonical / Open Graph
 *   - PART 6: Schema Markup Plan (SoftwareApplication + FAQPage + AggregateRating),
 *             Organization schema (global)
 *
 * Self-contained: works with no SEO plugin installed. If Yoast SEO or Rank Math
 * is active, we DEFER all head meta to them (to avoid duplicate tags) but still
 * emit the SoftwareApplication / FAQPage / Organization JSON-LD, which those
 * plugins do not generate for a custom homepage.
 *
 * @package NoorStudio
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Is a third-party SEO plugin handling <title> + meta description + OG?
 */
function noor_seo_plugin_active() {
	return defined( 'WPSEO_VERSION' ) || defined( 'RANK_MATH_VERSION' ) || class_exists( 'WPSEO_Frontend' );
}

/**
 * Per-page SEO values. Homepage values come straight from the strategy doc;
 * other pages fall back to sensible WordPress defaults.
 *
 * @return array{title:string,description:string,og_title:string,og_desc:string,og_image_alt:string}
 */
function noor_seo_values() {
	if ( is_front_page() ) {
		return array(
			'title'        => "AI Children's Book Generator | Islamic & Personalized Books",
			'description'  => 'Create personalized AI children\'s books with consistent characters. Islamic stories, Prophet narratives, KDP-ready exports. Trusted by 3,200+ authors. Start free.',
			'og_title'     => "AI Children's Book Generator — NoorStudio",
			'og_desc'      => 'Design the character once. They appear on every page, exactly as you made them. Islamic stories, bedtime tales, and KDP publishing — all from one AI studio.',
			'og_image_alt' => "NoorStudio AI children's book generator showing a consistent character across multiple illustrated pages",
		);
	}

	$title = wp_get_document_title();
	$desc  = get_bloginfo( 'description' );
	if ( is_singular() ) {
		$excerpt = wp_strip_all_tags( get_the_excerpt() );
		if ( $excerpt ) {
			$desc = wp_trim_words( $excerpt, 28, '…' );
		}
	}

	return array(
		'title'        => $title,
		'description'  => $desc,
		'og_title'     => $title,
		'og_desc'      => $desc,
		'og_image_alt' => get_bloginfo( 'name' ),
	);
}

/**
 * Canonical + meta description + Open Graph + Twitter card.
 * Hooked early so it sits high in <head>.
 */
function noor_head_meta() {
	$v   = noor_seo_values();
	$url = is_front_page() ? home_url( '/' ) : ( is_singular() ? get_permalink() : home_url( add_query_arg( null, null ) ) );

	// Featured image → OG image, else the bundled social card if present.
	$og_image = '';
	if ( is_singular() && has_post_thumbnail() ) {
		$og_image = get_the_post_thumbnail_url( null, 'full' );
	} elseif ( file_exists( get_theme_file_path( 'assets/images/og-image.jpg' ) ) ) {
		$og_image = noor_asset( 'assets/images/og-image.jpg' );
	} elseif ( file_exists( get_theme_file_path( 'assets/images/img-02.jpg' ) ) ) {
		$og_image = noor_asset( 'assets/images/img-02.jpg' );
	}

	echo "\n<!-- NoorStudio SEO -->\n";

	// Description + canonical are skipped if an SEO plugin owns them.
	if ( ! noor_seo_plugin_active() ) {
		printf( '<meta name="description" content="%s" />' . "\n", esc_attr( $v['description'] ) );
		printf( '<link rel="canonical" href="%s" />' . "\n", esc_url( $url ) );
	}

	// Open Graph + Twitter (only when no plugin already prints og:title).
	if ( ! noor_seo_plugin_active() ) {
		printf( '<meta property="og:type" content="%s" />' . "\n", is_singular( 'post' ) ? 'article' : 'website' );
		printf( '<meta property="og:site_name" content="%s" />' . "\n", esc_attr( get_bloginfo( 'name' ) ) );
		printf( '<meta property="og:title" content="%s" />' . "\n", esc_attr( $v['og_title'] ) );
		printf( '<meta property="og:description" content="%s" />' . "\n", esc_attr( $v['og_desc'] ) );
		printf( '<meta property="og:url" content="%s" />' . "\n", esc_url( $url ) );
		printf( '<meta property="og:locale" content="%s" />' . "\n", esc_attr( str_replace( '-', '_', get_bloginfo( 'language' ) ) ) );
		if ( $og_image ) {
			printf( '<meta property="og:image" content="%s" />' . "\n", esc_url( $og_image ) );
			printf( '<meta property="og:image:alt" content="%s" />' . "\n", esc_attr( $v['og_image_alt'] ) );
		}
		echo '<meta name="twitter:card" content="summary_large_image" />' . "\n";
		printf( '<meta name="twitter:title" content="%s" />' . "\n", esc_attr( $v['og_title'] ) );
		printf( '<meta name="twitter:description" content="%s" />' . "\n", esc_attr( $v['og_desc'] ) );
		if ( $og_image ) {
			printf( '<meta name="twitter:image" content="%s" />' . "\n", esc_url( $og_image ) );
		}
	}
}
add_action( 'wp_head', 'noor_head_meta', 1 );

/**
 * Filter the document <title> for the homepage to the strategy's exact meta title.
 */
function noor_document_title( $title ) {
	if ( is_front_page() && ! noor_seo_plugin_active() ) {
		$v = noor_seo_values();
		return $v['title'];
	}
	return $title;
}
add_filter( 'pre_get_document_title', 'noor_document_title', 20 );

/**
 * Emit JSON-LD structured data.
 *
 * Organization is emitted site-wide. On the homepage we add SoftwareApplication
 * (with Offer + AggregateRating) and FAQPage, both required by the schema plan.
 */
function noor_json_ld() {
	$m       = noor_meta();
	$home    = home_url( '/' );
	$logo    = file_exists( get_theme_file_path( 'assets/images/img-01.png' ) ) ? noor_asset( 'assets/images/img-01.png' ) : $home . 'logo.png';
	$graph   = array();

	// Organization (global) — SEO report PART 6.
	$graph[] = array(
		'@type'        => 'Organization',
		'@id'          => $home . '#organization',
		'name'         => $m['brand'],
		'url'          => $home,
		'logo'         => $logo,
		'description'  => 'AI-powered children\'s book creation platform with character consistency technology, Islamic storytelling templates, and KDP-ready exports.',
		'sameAs'       => $m['same_as'],
		'contactPoint' => array(
			'@type'       => 'ContactPoint',
			'contactType' => 'customer support',
			'email'       => $m['email'],
		),
	);

	if ( is_front_page() ) {
		// SoftwareApplication + Offer + AggregateRating.
		$graph[] = array(
			'@type'               => 'SoftwareApplication',
			'@id'                 => $home . '#software',
			'name'                => $m['brand'],
			'applicationCategory' => 'DesignApplication',
			'operatingSystem'     => 'Web',
			'url'                 => $home,
			'description'         => 'AI-powered children\'s book creation platform with consistent character technology, Islamic storytelling templates, and KDP-ready exports.',
			'offers'              => array(
				'@type'           => 'Offer',
				'price'           => $m['price'],
				'priceCurrency'   => $m['currency'],
				'priceValidUntil' => '2027-12-31',
			),
			'aggregateRating'     => array(
				'@type'       => 'AggregateRating',
				'ratingValue' => $m['rating_value'],
				'reviewCount' => $m['review_count'],
			),
			'publisher'           => array( '@id' => $home . '#organization' ),
		);

		// FAQPage — built from the SAME array that renders the visible accordion.
		$entities = array();
		foreach ( noor_faqs() as $faq ) {
			$entities[] = array(
				'@type'          => 'Question',
				'name'           => $faq['q'],
				'acceptedAnswer' => array(
					'@type' => 'Answer',
					'text'  => $faq['a'],
				),
			);
		}
		$graph[] = array(
			'@type'      => 'FAQPage',
			'@id'        => $home . '#faq',
			'mainEntity' => $entities,
		);
	}

	$data = array(
		'@context' => 'https://schema.org',
		'@graph'   => $graph,
	);

	echo "\n" . '<script type="application/ld+json">' . "\n";
	echo wp_json_encode( $data, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT );
	echo "\n" . '</script>' . "\n";
}
add_action( 'wp_head', 'noor_json_ld', 20 );

/**
 * Extend the virtual robots.txt with the strategy's crawl rules (PART 6).
 *
 * Only applies to WordPress's generated robots.txt (no static file present)
 * and only when the site is set to "discourage search engines" = off.
 */
function noor_robots_txt( $output, $public ) {
	if ( '1' !== (string) $public ) {
		return $output; // Site is non-public; leave WP's default block in place.
	}
	$rules  = "Disallow: /app/\n";
	$rules .= "Disallow: /dashboard/\n";
	$rules .= "Disallow: /api/\n";
	$rules .= "Disallow: /user/\n";
	$rules .= "Disallow: /*.json$\n";
	$output .= $rules;
	return $output;
}
add_filter( 'robots_txt', 'noor_robots_txt', 10, 2 );
