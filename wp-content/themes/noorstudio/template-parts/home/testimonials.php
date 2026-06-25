<?php
/**
 * Testimonials (E-E-A-T: experience signal).
 * Monogram avatars fall back gracefully when no photo is set.
 *
 * @package NoorStudio
 */
?>
<section class="testi" aria-labelledby="testi-h2">
	<div class="section-center">
		<div class="section-eyebrow">Who uses NoorStudio</div>
		<h2 class="section-title" id="testi-h2">Made by parents, <em>kept by children.</em></h2>
	</div>

	<div class="testi-grid">
		<?php foreach ( noor_testimonials() as $t ) : ?>
			<?php
			$has_photo = ! empty( $t['photo'] ) && file_exists( get_theme_file_path( 'assets/images/' . $t['photo'] ) );
			?>
			<figure class="testi-card">
				<span class="testi-quote-mark" aria-hidden="true">&ldquo;</span>
				<blockquote class="testi-text"><?php echo esc_html( $t['quote'] ); ?></blockquote>
				<figcaption class="testi-author">
					<div class="testi-avatar<?php echo $has_photo ? '' : ' no-photo'; ?>" data-initial="<?php echo esc_attr( $t['initial'] ); ?>">
						<?php if ( $has_photo ) : ?>
							<img src="<?php echo esc_url( noor_asset( 'assets/images/' . $t['photo'] ) ); ?>" alt="<?php echo esc_attr( $t['name'] ); ?>" width="48" height="48" loading="lazy" decoding="async" />
						<?php endif; ?>
					</div>
					<div>
						<div class="testi-name"><?php echo esc_html( $t['name'] ); ?></div>
						<div class="testi-meta"><?php echo esc_html( $t['meta'] ); ?></div>
					</div>
				</figcaption>
			</figure>
		<?php endforeach; ?>
	</div>
</section>
