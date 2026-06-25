<?php
/**
 * Footer.
 *
 * @package NoorStudio
 */
?>
<footer class="footer">
	<div class="footer-in">
		<div class="footer-brand">
			<?php
			if ( has_custom_logo() ) {
				the_custom_logo();
			} else {
				echo noor_logo( 32 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			}
			?>
			<p><?php esc_html_e( 'The AI children\'s book studio. Consistent characters, ten illustration styles, Islamic and universal templates, KDP-ready exports.', 'noorstudio' ); ?></p>
		</div>

		<?php
		noor_footer_col(
			'footer_product',
			__( 'Product', 'noorstudio' ),
			array(
				array( 'label' => 'Features', 'url' => '/features/' ),
				array( 'label' => 'How it works', 'url' => '/how-it-works/' ),
				array( 'label' => 'Templates', 'url' => '/templates/' ),
				array( 'label' => 'Pricing', 'url' => '/pricing/' ),
			)
		);

		noor_footer_col(
			'footer_resources',
			__( 'Resources', 'noorstudio' ),
			array(
				array( 'label' => 'Islamic books', 'url' => '/islamic-childrens-books/' ),
				array( 'label' => 'Publish on KDP', 'url' => '/kdp-childrens-book-creator/' ),
				array( 'label' => 'Success stories', 'url' => '/success-stories/' ),
				array( 'label' => 'Blog', 'url' => '/blog/' ),
			)
		);

		noor_footer_col(
			'footer_company',
			__( 'Company', 'noorstudio' ),
			array(
				array( 'label' => 'About', 'url' => '/about/' ),
				array( 'label' => 'Contact', 'url' => '/contact/' ),
				array( 'label' => 'Affiliate', 'url' => '/affiliate/' ),
			)
		);

		noor_footer_col(
			'footer_legal',
			__( 'Legal', 'noorstudio' ),
			array(
				array( 'label' => 'Terms', 'url' => '/terms-of-service/' ),
				array( 'label' => 'Privacy', 'url' => '/privacy-policy/' ),
				array( 'label' => 'Cookies', 'url' => '/cookie-policy/' ),
			)
		);
		?>
	</div>

	<div class="footer-bot">
		<span>&copy; <?php echo esc_html( gmdate( 'Y' ) ); ?> <?php echo esc_html( get_bloginfo( 'name' ) ); ?></span>
		<span><?php esc_html_e( 'Built for storytellers', 'noorstudio' ); ?></span>
	</div>
</footer>

<?php wp_footer(); ?>
</body>
</html>
