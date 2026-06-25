<?php
/**
 * FAQ accordion. Rendered from noor_faqs() — the SAME array that feeds the
 * FAQPage JSON-LD in inc/seo.php, so the visible answers and the structured
 * data can never drift apart.
 *
 * A11y: each control has aria-expanded + aria-controls (a11y checklist).
 *
 * @package NoorStudio
 */
?>
<section class="faq" aria-labelledby="faq-h2">
	<div class="section-center">
		<div class="section-eyebrow">FAQ</div>
		<h2 class="section-title" id="faq-h2">Frequently asked <em>questions.</em></h2>
	</div>

	<div class="faq-list">
		<?php foreach ( noor_faqs() as $i => $faq ) : ?>
			<?php $panel_id = 'faq-panel-' . $i; ?>
			<div class="faq-item">
				<button class="faq-q" type="button" aria-expanded="false" aria-controls="<?php echo esc_attr( $panel_id ); ?>">
					<span><?php echo esc_html( $faq['q'] ); ?></span>
					<span class="faq-icon" aria-hidden="true">+</span>
				</button>
				<div class="faq-a" id="<?php echo esc_attr( $panel_id ); ?>" role="region">
					<p><?php echo esc_html( $faq['a'] ); ?></p>
				</div>
			</div>
		<?php endforeach; ?>
	</div>
</section>
