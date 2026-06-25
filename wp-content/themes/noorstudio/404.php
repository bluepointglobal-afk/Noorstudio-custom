<?php
/**
 * 404 template.
 *
 * @package NoorStudio
 */

get_header();
?>
<main id="main">
	<section class="final" style="padding:140px 40px" aria-labelledby="e404-h1">
		<div class="final-in">
			<span class="final-spark" aria-hidden="true">✦</span>
			<h2 id="e404-h1">This page <em>doesn't exist yet.</em></h2>
			<p>But your child's favourite book still can. Head back home and start creating.</p>
			<div class="final-btns">
				<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="btn-primary xl btn-white">Back to home</a>
				<a href="<?php echo noor_link( '/templates/' ); ?>" class="btn-ghost lg btn-ghost-white">Browse templates</a>
			</div>
		</div>
	</section>
</main>
<?php
get_footer();
