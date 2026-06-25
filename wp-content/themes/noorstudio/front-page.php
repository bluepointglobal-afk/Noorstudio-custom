<?php
/**
 * Front page — the NoorStudio marketing homepage.
 *
 * Section order and copy follow "NoorStudio — Complete SEO Content Strategy"
 * (PART 2, PAGE 1: Homepage). Heading hierarchy: a single H1 in the hero, then
 * H2 per section and H3 inside, per the report's recommended structure.
 *
 * @package NoorStudio
 */

get_header();
?>
<main id="main">
	<?php
	get_template_part( 'template-parts/home/hero' );
	get_template_part( 'template-parts/home/specstrip' );
	get_template_part( 'template-parts/home/styles' );
	get_template_part( 'template-parts/home/shelf' );
	get_template_part( 'template-parts/home/how' );
	get_template_part( 'template-parts/home/testimonials' );
	get_template_part( 'template-parts/home/pricing' );
	get_template_part( 'template-parts/home/faq' );
	get_template_part( 'template-parts/home/final' );
	?>
</main>
<?php
get_footer();
