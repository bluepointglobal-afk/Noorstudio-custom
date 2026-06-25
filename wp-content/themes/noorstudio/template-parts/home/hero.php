<?php
/**
 * Hero — the only H1 on the page.
 *
 * H1 + first paragraph carry the primary keyword "AI children's book generator"
 * once each (SEO report PART 9 anti-stuffing rules).
 *
 * @package NoorStudio
 */
?>
<section class="hero" aria-labelledby="hero-h1">
	<svg class="hero-deco" viewBox="0 0 400 400" aria-hidden="true">
		<defs>
			<pattern id="geo" x="0" y="0" width="60" height="60" patternUnits="userSpaceOnUse">
				<circle cx="30" cy="30" r="22" fill="none" stroke="#0E4938" stroke-width=".8"/>
				<circle cx="30" cy="30" r="11" fill="none" stroke="#0E4938" stroke-width=".5"/>
				<path d="M30 8 L30 52 M8 30 L52 30" stroke="#0E4938" stroke-width=".4"/>
			</pattern>
		</defs>
		<rect width="400" height="400" fill="url(#geo)"/>
	</svg>

	<div class="hero-grid">
		<div>
			<h1 class="headline" id="hero-h1">
				AI Children's Book Generator with <em>Consistent Characters</em>
				<span class="headline-soft">Design the character once</span>
			</h1>

			<p class="sub">NoorStudio is the AI children's book creator built for parents, indie authors, Islamic educators, and KDP publishers. Our character consistency technology locks your illustrated hero — face, outfit, and personality — across every page of your personalized children's book.</p>

			<div class="cta-row">
				<a href="<?php echo noor_link( '/pricing/' ); ?>" class="btn-primary xl">Create Your First Book Free
					<svg width="16" height="16" viewBox="0 0 16 16" fill="none" aria-hidden="true"><path d="M3 8h10m0 0L8.5 3.5M13 8l-4.5 4.5" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/></svg>
				</a>
				<a href="<?php echo noor_link( '/how-it-works/' ); ?>" class="btn-ghost lg"><span class="play-dot" aria-hidden="true">▶</span>See How It Works</a>
			</div>

			<div class="proof">
				<div class="proof-item">
					<div class="stars-svg stars" aria-hidden="true">
						<?php for ( $s = 0; $s < 5; $s++ ) : ?>
							<svg width="13" height="13" viewBox="0 0 14 14" fill="currentColor"><path d="M7 1l1.8 3.9 4.2.5-3.1 2.9.8 4.2L7 10.4l-3.7 2.1.8-4.2L1 5.4l4.2-.5L7 1z"/></svg>
						<?php endfor; ?>
					</div>
					<span><strong>4.9</strong> from 3,200+ creators</span>
				</div>
				<div class="proof-divider" aria-hidden="true"></div>
				<div class="proof-item">
					<svg width="14" height="14" viewBox="0 0 14 14" fill="none" aria-hidden="true"><path d="M2 11V3a1 1 0 011-1h7l2 2v7a1 1 0 01-1 1H3a1 1 0 01-1-1z" stroke="currentColor" stroke-width="1.3" fill="none"/><path d="M4 6h6M4 8.5h4" stroke="currentColor" stroke-width="1.3" stroke-linecap="round"/></svg>
					KDP-ready PDF exports
				</div>
				<div class="proof-divider" aria-hidden="true"></div>
				<div class="proof-item">
					<svg width="14" height="14" viewBox="0 0 14 14" fill="none" aria-hidden="true"><path d="M7 1.5L8.5 5l3.5.4-2.7 2.5.8 3.6L7 9.7l-3.1 1.8.8-3.6L2 5.4 5.5 5z" stroke="currentColor" stroke-width="1.2" fill="none" stroke-linejoin="round"/></svg>
					Ten illustration styles
				</div>
			</div>
		</div>

		<div class="stage" aria-hidden="true">
			<div class="stage-bg"></div>
			<span class="sparkle sparkle-1">✦</span>
			<span class="sparkle sparkle-2">✧</span>
			<span class="sparkle sparkle-3">✦</span>
			<span class="sparkle sparkle-4">✧</span>

			<div class="portrait p-main">
				<?php
				echo noor_img(
					'img-02.jpg',
					array(
						'alt'           => 'Consistent AI-illustrated character shown across a children\'s book',
						'width'         => 440,
						'height'        => 440,
						'loading'       => 'eager',
						'fetchpriority' => 'high',
						'label'         => 'Featured character',
					)
				);
				?>
			</div>
			<div class="portrait p-sat p-tl"><?php echo noor_img( 'img-03.jpg', array( 'alt' => '', 'width' => 170, 'height' => 170, 'label' => 'Style' ) ); ?></div>
			<div class="portrait p-sat p-tr"><?php echo noor_img( 'img-04.jpg', array( 'alt' => '', 'width' => 160, 'height' => 160, 'label' => 'Style' ) ); ?></div>
			<div class="portrait p-sat p-ml"><?php echo noor_img( 'img-05.jpg', array( 'alt' => '', 'width' => 140, 'height' => 140, 'label' => 'Style' ) ); ?></div>
			<div class="portrait p-sat p-bl"><?php echo noor_img( 'img-06.jpg', array( 'alt' => '', 'width' => 180, 'height' => 180, 'label' => 'Style' ) ); ?></div>
			<div class="portrait p-sat p-br"><?php echo noor_img( 'img-07.jpg', array( 'alt' => '', 'width' => 165, 'height' => 165, 'label' => 'Style' ) ); ?></div>

			<div class="style-tag">
				<div class="style-tag-row">
					<span class="style-tag-dot"></span>
					<span class="style-tag-label">Style</span>
				</div>
				<div class="style-tag-name">3D Cinematic</div>
			</div>
		</div>
	</div>
</section>
