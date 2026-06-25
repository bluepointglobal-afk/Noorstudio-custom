<?php
/**
 * Homepage content model — single source of truth.
 *
 * Centralising this data lets the visible markup and the JSON-LD schema
 * (FAQPage, SoftwareApplication, etc.) stay in perfect sync, which is a
 * hard requirement for valid structured data. Edit copy here and it
 * updates both the page and the schema.
 *
 * All copy is drawn from "NoorStudio — Complete SEO Content Strategy"
 * (PART 2: Homepage). Keyword usage follows the anti-stuffing rules in
 * PART 9 (primary keyword in H1 + first paragraph + one H2 + meta only).
 *
 * @package NoorStudio
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Site-wide business / SEO constants pulled from the strategy doc.
 */
function noor_meta() {
	return array(
		'brand'        => 'NoorStudio',
		'rating_value' => '4.9',
		'review_count' => '3200',
		'price'        => '29',
		'currency'     => 'USD',
		'email'        => 'hello@noorstudio.io',
		'same_as'      => array(
			'https://www.instagram.com/noorstudio',
			'https://www.tiktok.com/@noorstudio',
			'https://www.facebook.com/noorstudio',
		),
	);
}

/**
 * Primary nav + footer internal-linking map.
 * Mirrors the sitemap & internal-linking architecture (SEO report PART 6).
 * These are used as fallbacks when no WordPress menu is assigned.
 */
function noor_nav_links() {
	return array(
		array( 'label' => 'Library',       'url' => '/templates/' ),
		array( 'label' => 'How it works',  'url' => '/how-it-works/' ),
		array( 'label' => 'Islamic books', 'url' => '/islamic-childrens-books/' ),
		array( 'label' => 'Pricing',       'url' => '/pricing/' ),
		array( 'label' => 'Publish',       'url' => '/kdp-childrens-book-creator/' ),
	);
}

/**
 * Ten illustration styles (SEO report: "Ten Illustration Styles" section).
 * Five surfaced on the homepage grid; the rest noted in the footer line.
 */
function noor_styles() {
	return array(
		array( 'img' => 'img-02.jpg', 'label' => '3D Cinematic',   'tag' => 'Pixar-grade rendering' ),
		array( 'img' => 'img-07.jpg', 'label' => 'Heroic 3D',      'tag' => 'Bold, action-ready' ),
		array( 'img' => 'img-05.jpg', 'label' => 'Professional 3D','tag' => 'Educational & school readers' ),
		array( 'img' => 'img-06.jpg', 'label' => 'Soft Storybook', 'tag' => 'Warm, hand-drawn feel' ),
		array( 'img' => 'img-04.jpg', 'label' => 'Cute Creature',  'tag' => 'For your sidekicks' ),
	);
}

/**
 * Library shelf — generated sample books. First entry is the hero feature.
 */
function noor_books() {
	return array(
		array(
			'hero'    => true,
			'img'     => 'img-08.png',
			'style'   => 'Islamic Heritage',
			'tag'     => 'Chapter book for ages 8 to 14',
			'trim'    => '6×9″ · 168 pp',
			'bisac'   => 'BISAC JNF013030',
			'palette' => array( '#0E4A3E', '#C9A24F', '#3A4858', '#8B5A2E' ),
			'formats' => array( 'kdp' => 'KDP-ready', '' => array( 'Hardcover', 'Paperback' ), 'kindle' => 'Kindle' ),
			'alt'     => 'Islamic Heritage — illustrated chapter book created with NoorStudio',
		),
		array(
			'img'     => 'img-09.png',
			'style'   => 'Classic Adventure',
			'tag'     => 'Picture book for ages 3 to 6',
			'trim'    => '8.5×8.5″ · 32 pp',
			'bisac'   => 'JNF051000',
			'palette' => array( '#F4A91C', '#3C9CD8', '#7BC04A', '#E84A4A' ),
			'formats' => array( '' => array( 'Paperback' ), 'kindle' => 'Kindle' ),
			'alt'     => 'Classic Adventure picture book sample',
		),
		array(
			'img'     => 'img-10.png',
			'style'   => 'Epic Cinematic',
			'tag'     => 'Middle grade for ages 8 to 14',
			'trim'    => '6×9″ · 224 pp',
			'bisac'   => 'JUV001000',
			'palette' => array( '#1A1830', '#4A2860', '#8B4A60', '#C9A24F' ),
			'formats' => array( '' => array( 'Hardcover', 'Paperback' ) ),
			'alt'     => 'Epic Cinematic middle-grade book sample',
		),
		array(
			'img'     => 'img-11.png',
			'style'   => 'Vintage Ornate',
			'tag'     => 'Chapter book for ages 6 to 10',
			'trim'    => '5.5×8.5″ · 96 pp',
			'bisac'   => 'JUV019000',
			'palette' => array( '#E8C547', '#1B1814', '#A87A2E', '#F4ECD8' ),
			'formats' => array( '' => array( 'Hardcover', 'Paperback' ) ),
			'alt'     => 'Vintage Ornate chapter book sample',
		),
		array(
			'img'     => 'img-12.png',
			'style'   => 'Watercolor Dream',
			'tag'     => 'Picture book for ages 3 to 7',
			'trim'    => '8×10″ · 32 pp',
			'bisac'   => 'JUV017000',
			'palette' => array( '#E8B5C8', '#C9D8A4', '#F4DCC8', '#A8B8D8' ),
			'formats' => array( '' => array( 'Paperback' ), 'kindle' => 'Kindle' ),
			'alt'     => 'Watercolor Dream picture book sample',
		),
		array(
			'img'     => 'img-13.png',
			'style'   => 'Night Sky',
			'tag'     => 'Picture book for ages 4 to 8',
			'trim'    => '8.5×8.5″ · 40 pp',
			'bisac'   => 'JUV029010',
			'palette' => array( '#1A2848', '#4A4070', '#C9A24F', '#F4ECD8' ),
			'formats' => array( '' => array( 'Hardcover' ), 'kindle' => 'Kindle' ),
			'alt'     => 'Night Sky bedtime picture book sample',
		),
		array(
			'img'     => 'img-14.png',
			'style'   => 'Storybook Warm',
			'tag'     => 'Chapter book for ages 6 to 10',
			'trim'    => '6×9″ · 80 pp',
			'bisac'   => 'JUV051000',
			'palette' => array( '#5C2818', '#C9A24F', '#E8B570', '#3A2418' ),
			'formats' => array( 'kdp' => 'KDP-ready', '' => array( 'Paperback' ) ),
			'alt'     => 'Storybook Warm chapter book sample',
		),
	);
}

/**
 * Testimonials. `photo` is optional — when absent the CSS renders a
 * serif monogram from `initial` (handoff note: avatars are placeholders).
 */
function noor_testimonials() {
	return array(
		array(
			'quote'   => 'My daughter has read her own personalised book every night for three weeks. She thinks I drew her, and I am not telling her otherwise.',
			'name'    => 'Sara A.',
			'meta'    => 'Mother of two · Dubai',
			'initial' => 'S',
			'photo'   => 'img-03.jpg',
		),
		array(
			'quote'   => 'I run a Sunday school. We created eight illustrated Islamic books across three age groups in a single weekend. The children asked for the Prophet stories for a month.',
			'name'    => 'Yusuf K.',
			'meta'    => 'Islamic educator · Birmingham',
			'initial' => 'Y',
			'photo'   => 'img-05.jpg',
		),
		array(
			'quote'   => 'I had a story in my head for years. With the KDP-ready export I published it on Amazon in nine days. First royalties came in the second week.',
			'name'    => 'Aisha M.',
			'meta'    => 'Indie author · Toronto',
			'initial' => 'A',
			'photo'   => 'img-15.jpg',
		),
	);
}

/**
 * Homepage FAQ — verbatim from the SEO strategy (Homepage FAQ section).
 * Rendered as an accordion AND emitted as FAQPage schema, from this one array.
 */
function noor_faqs() {
	return array(
		array(
			'q' => 'How does character consistency work in NoorStudio?',
			'a' => 'When you create a character in NoorStudio, our AI illustrator builds a consistent visual model — including facial features, skin tone, hair, body type, and clothing. This model is applied to every illustration in your book, so whether your character is running, sleeping, or speaking, they look recognisably the same on every page. This is the core technical difference between NoorStudio and basic AI story generators.',
		),
		array(
			'q' => 'Can I create Islamic children\'s books with NoorStudio?',
			'a' => 'Yes. NoorStudio is built specifically to support Islamic storytelling alongside universal children\'s content. We offer dedicated templates for Prophet stories, Sahabah narratives, Ramadan and Eid seasonal books, dua collections, and Islamic educational readers — all developed with input from Islamic educators and reviewed for cultural and religious accuracy.',
		),
		array(
			'q' => 'Is the content I create with NoorStudio commercially licensed?',
			'a' => 'Author and Studio plan subscribers receive a full commercial license for all books created on the platform. You can sell your illustrated children\'s books on Amazon KDP, Etsy, Gumroad, or any other marketplace, and you keep 100% of your royalties. The Creator plan is for personal use only.',
		),
		array(
			'q' => 'What export formats does NoorStudio produce?',
			'a' => 'NoorStudio exports KDP-ready PDF (PDF/X-1a, 300 DPI, CMYK, with correct trim and bleed for your chosen size), standard PDF for digital distribution, and EPUB 3 for Kindle and digital reading platforms. Hardcover print-on-demand is available through our print partner.',
		),
		array(
			'q' => 'What age groups and book formats does NoorStudio support?',
			'a' => 'NoorStudio supports picture books (ages 3–7, typically 32–40 pages), early readers (ages 5–8, 48–64 pages), chapter books (ages 7–10, 64–128 pages), and middle-grade novels (ages 8–14, 128–224 pages). Trim sizes include 8.5×8.5″ square, 6×9″, and A5.',
		),
		array(
			'q' => 'Do I need design or illustration skills to use NoorStudio?',
			'a' => 'No. NoorStudio is designed for writers, parents, educators, and entrepreneurs — not designers or illustrators. Describe what you want in plain language, select from our style options, and the platform handles all illustration, layout, and export formatting automatically.',
		),
	);
}

/**
 * Pricing plans (SEO report PAGE 5 — Pricing).
 */
function noor_plans() {
	return array(
		array(
			'tier'     => 'Creator',
			'desc'     => 'For families and first-time authors',
			'amount'   => '29',
			'features' => array( '5 books per month', 'All ten illustration styles', 'KDP-ready PDF export', 'Personal use license' ),
			'cta'      => 'Start free',
			'url'      => '/pricing/',
			'pop'      => false,
			'ghost'    => true,
		),
		array(
			'tier'     => 'Author',
			'desc'     => 'For serious indie publishers',
			'amount'   => '79',
			'features' => array( 'Unlimited books', 'Unlimited characters', 'KDP-ready exports', 'Full commercial license', 'Priority support' ),
			'cta'      => 'Start 7-day trial',
			'url'      => '/pricing/',
			'pop'      => true,
			'ghost'    => false,
		),
		array(
			'tier'     => 'Studio',
			'desc'     => 'For teams, schools, and publishers',
			'amount'   => '199',
			'features' => array( 'Everything in Author', 'Team collaboration (5 seats)', 'Bulk export tools', 'API access', 'Dedicated support' ),
			'cta'      => 'Contact sales',
			'url'      => '/contact/',
			'pop'      => false,
			'ghost'    => true,
		),
	);
}
