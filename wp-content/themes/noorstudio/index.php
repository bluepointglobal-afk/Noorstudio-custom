<?php
/**
 * Generic fallback template (posts, pages, archives).
 *
 * The homepage is handled by front-page.php; this keeps the theme valid and
 * usable for the supporting pages described in the SEO strategy (Features,
 * Pricing, Islamic Children's Books, blog, etc.) until dedicated templates
 * are added.
 *
 * @package NoorStudio
 */

get_header();
?>
<main id="main" class="content-page">
	<div class="content-in" style="max-width:780px;margin:0 auto;padding:80px 40px">
		<?php
		if ( have_posts() ) :
			while ( have_posts() ) :
				the_post();
				?>
				<article <?php post_class(); ?>>
					<header>
						<h1 class="section-title" style="margin-bottom:24px"><?php the_title(); ?></h1>
					</header>
					<div class="entry-content section-sub" style="color:var(--ink-soft)">
						<?php
						if ( is_singular() ) {
							the_content();
							wp_link_pages();
						} else {
							the_excerpt();
							printf( '<p><a class="btn-ghost" href="%s">Read more →</a></p>', esc_url( get_permalink() ) );
						}
						?>
					</div>
				</article>
				<?php
			endwhile;

			the_posts_pagination();
		else :
			?>
			<h1 class="section-title">Nothing found</h1>
			<p class="section-sub"><?php esc_html_e( 'Sorry, no content matched your request.', 'noorstudio' ); ?></p>
		<?php endif; ?>
	</div>
</main>
<?php
get_footer();
