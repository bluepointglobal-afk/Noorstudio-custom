<?php
/**
 * Library shelf.
 *
 * Filter tabs use role="tablist" (a11y checklist). Actual filtering is a
 * backend wiring task — tabs only toggle the active state for now.
 *
 * @package NoorStudio
 */
$books = noor_books();
?>
<section class="shelf" id="library" aria-labelledby="shelf-h2">
	<div class="shelf-head">
		<div class="shelf-head-l">
			<div class="section-eyebrow">The library</div>
			<h2 class="section-title" id="shelf-h2">One platform. <em>Every story you need to tell.</em></h2>
			<p class="section-sub">Personalized books, Islamic Prophet and Sahabah stories, bedtime tales, and KDP series — every story finds its visual voice, and your characters stay consistent across every page.</p>
		</div>
		<div class="shelf-filter" role="tablist" aria-label="Filter library by book type">
			<button role="tab" aria-selected="true" class="active">All</button>
			<button role="tab" aria-selected="false">Picture book</button>
			<button role="tab" aria-selected="false">Chapter book</button>
			<button role="tab" aria-selected="false">Middle grade</button>
		</div>
	</div>

	<div class="shelf-layout">
		<?php foreach ( $books as $book ) : ?>
			<?php $is_hero = ! empty( $book['hero'] ); ?>
			<a class="book<?php echo $is_hero ? ' book-hero' : ''; ?>" href="<?php echo noor_link( '/templates/' ); ?>">
				<div class="book-cover">
					<?php
					echo noor_img(
						$book['img'],
						array(
							'alt'    => $book['alt'],
							'width'  => $is_hero ? 500 : 260,
							'height' => $is_hero ? 675 : 351,
							'label'  => $book['style'],
						)
					);
					?>
				</div>
				<div class="book-meta">
					<div class="book-style"><?php echo esc_html( $book['style'] ); ?></div>
					<div class="book-tag"><?php echo esc_html( $book['tag'] ); ?></div>
					<div class="book-meta-row">
						<span class="trim"><?php echo esc_html( $book['trim'] ); ?></span>
						<span><?php echo esc_html( $book['bisac'] ); ?></span>
					</div>
					<div class="book-swatches">
						<?php foreach ( $book['palette'] as $hex ) : ?>
							<span class="swatch" style="background:<?php echo esc_attr( $hex ); ?>"></span>
						<?php endforeach; ?>
					</div>
					<div class="book-formats">
						<?php
						foreach ( $book['formats'] as $cls => $names ) {
							foreach ( (array) $names as $name ) {
								printf(
									'<span class="fmt-chip%s">%s</span>',
									$cls ? ' ' . esc_attr( $cls ) : '',
									esc_html( $name )
								);
							}
						}
						?>
					</div>
				</div>
			</a>
		<?php endforeach; ?>
	</div>

	<div class="shelf-footer">
		<p class="shelf-footer-text">Also available: <strong>Vintage Ornate, Modern Minimal, Bold Typography</strong>. More on the way.</p>
		<a href="<?php echo noor_link( '/templates/' ); ?>" class="btn-ghost lg">Browse all templates →</a>
	</div>
</section>
