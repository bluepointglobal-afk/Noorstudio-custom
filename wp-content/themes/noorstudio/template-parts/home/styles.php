<?php
/**
 * Illustration styles grid.
 * SEO H2: "Ten Illustration Styles. One Consistent Character."
 *
 * @package NoorStudio
 */
?>
<section class="styles" id="styles" aria-labelledby="styles-h2">
	<div class="styles-in">
		<div class="styles-head">
			<div class="styles-head-l">
				<div class="section-eyebrow">The cast</div>
				<h2 class="section-title" id="styles-h2">Ten illustration styles. <em>One consistent character.</em></h2>
				<p class="section-sub">NoorStudio offers ten professionally designed illustration styles for AI-generated children's books — from Pixar-grade 3D Cinematic to Soft Storybook, Watercolour, and Manga. Build the character once, render them in the style your story needs.</p>
			</div>
			<a href="<?php echo noor_link( '/features/' ); ?>" class="btn-ghost lg">Browse the studio →</a>
		</div>

		<div class="styles-grid">
			<?php foreach ( noor_styles() as $style ) : ?>
				<a class="style-card" href="<?php echo noor_link( '/features/' ); ?>">
					<div class="style-card-frame">
						<?php
						echo noor_img(
							$style['img'],
							array(
								'alt'    => $style['label'] . ' illustration style — ' . $style['tag'],
								'width'  => 320,
								'height' => 320,
								'label'  => $style['label'],
							)
						);
						?>
					</div>
					<div class="style-card-label"><?php echo esc_html( $style['label'] ); ?></div>
					<div class="style-card-tag"><?php echo esc_html( $style['tag'] ); ?></div>
				</a>
			<?php endforeach; ?>
		</div>
	</div>
</section>
