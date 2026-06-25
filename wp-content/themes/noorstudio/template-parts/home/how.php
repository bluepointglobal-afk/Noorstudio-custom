<?php
/**
 * How it works — three steps. SEO H2 + H3 per step.
 *
 * @package NoorStudio
 */
$steps = array(
	array(
		'num'   => '01',
		'title' => 'Design Your Character',
		'body'  => 'Describe your character\'s age, appearance, personality, and role. NoorStudio\'s AI illustrator renders them in your chosen style and saves them to your studio — reused consistently across every page.',
	),
	array(
		'num'   => '02',
		'title' => 'Choose Your Story Template',
		'body'  => 'Select from over 40 templates: universal adventure, Islamic narrative, bedtime story, educational, or gift book. The AI assists with structure and pacing — you keep full creative control of the voice.',
	),
	array(
		'num'   => '03',
		'title' => 'Publish Anywhere',
		'body'  => 'Export a KDP-ready PDF, standard PDF, or EPUB. Order a premium hardcover through our print partner, or download high-resolution files for Etsy. Every export is print-ready the moment you click download.',
	),
);
?>
<section class="how" id="how" aria-labelledby="how-h2">
	<div class="how-in">
		<div class="section-center">
			<div class="section-eyebrow">How it works</div>
			<h2 class="section-title" id="how-h2">Create a children's book with AI <em>in three steps.</em></h2>
			<p class="section-sub">From a character idea to a print-ready file you can publish to Amazon KDP. No design skills, no illustration commissions, no waiting weeks.</p>
		</div>

		<div class="how-grid">
			<?php foreach ( $steps as $step ) : ?>
				<div class="how-step">
					<span class="how-step-num" aria-hidden="true"><?php echo esc_html( $step['num'] ); ?></span>
					<h3>Step <?php echo esc_html( ltrim( $step['num'], '0' ) ); ?> — <?php echo esc_html( $step['title'] ); ?></h3>
					<p><?php echo esc_html( $step['body'] ); ?></p>
				</div>
			<?php endforeach; ?>
		</div>
	</div>
</section>
