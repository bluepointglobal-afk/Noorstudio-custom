<?php
/**
 * Pricing — three plans (SEO report PAGE 5).
 *
 * @package NoorStudio
 */
?>
<section class="pricing" id="pricing" aria-labelledby="pricing-h2">
	<div class="pricing-in">
		<div class="section-center">
			<div class="section-eyebrow">Pricing</div>
			<h2 class="section-title" id="pricing-h2">Start free. <em>Publish when ready.</em></h2>
			<p class="section-sub">Every plan includes character consistency, all ten illustration styles, Islamic and universal templates, and full KDP export. You keep 100% of your royalties, always.</p>
		</div>

		<div class="pricing-grid">
			<?php foreach ( noor_plans() as $plan ) : ?>
				<div class="price-card<?php echo $plan['pop'] ? ' pop' : ''; ?>">
					<?php if ( $plan['pop'] ) : ?>
						<div class="price-badge">Most popular</div>
					<?php endif; ?>
					<div class="price-tier"><?php echo esc_html( $plan['tier'] ); ?></div>
					<div class="price-desc"><?php echo esc_html( $plan['desc'] ); ?></div>
					<div class="price-amt">$<?php echo esc_html( $plan['amount'] ); ?><span>/month</span></div>
					<ul class="price-features">
						<?php foreach ( $plan['features'] as $feature ) : ?>
							<li><?php echo noor_check_svg(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?><?php echo esc_html( $feature ); ?></li>
						<?php endforeach; ?>
					</ul>
					<a href="<?php echo noor_link( $plan['url'] ); ?>" class="<?php echo $plan['ghost'] ? 'btn-ghost' : 'btn-primary'; ?> btn-full lg"><?php echo esc_html( $plan['cta'] ); ?></a>
				</div>
			<?php endforeach; ?>
		</div>

		<p class="price-note">All plans include a 7-day free trial with no credit card required. You keep 100% of your royalties, always.</p>
	</div>
</section>
