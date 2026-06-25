<?php
/**
 * Header: <head>, sticky nav, and mobile drawer.
 *
 * @package NoorStudio
 */
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<link rel="profile" href="https://gmpg.org/xfn/11" />
<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<a class="skip-link" href="#main"><?php esc_html_e( 'Skip to content', 'noorstudio' ); ?></a>

<nav class="nav" aria-label="<?php esc_attr_e( 'Primary', 'noorstudio' ); ?>">
	<div class="nav-in">
		<?php
		if ( has_custom_logo() ) {
			// the_custom_logo() emits its own home link; avoid nesting anchors.
			echo '<span class="logo">';
			the_custom_logo();
			echo '</span>';
		} else {
			printf(
				'<a href="%1$s" class="logo" aria-label="%2$s — home">%3$s</a>',
				esc_url( home_url( '/' ) ),
				esc_attr( get_bloginfo( 'name' ) ),
				noor_logo( 52 ) // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- markup built in helper.
			);
		}
		?>

		<?php noor_primary_menu(); ?>

		<div class="nav-cta">
			<a href="<?php echo noor_link( '/login/' ); ?>" class="nav-login"><?php esc_html_e( 'Log in', 'noorstudio' ); ?></a>
			<a href="<?php echo noor_link( '/pricing/' ); ?>" class="btn-primary"><?php esc_html_e( 'Start creating', 'noorstudio' ); ?>
				<svg width="14" height="14" viewBox="0 0 14 14" fill="none" aria-hidden="true"><path d="M3 7h8m0 0L7.5 3.5M11 7l-3.5 3.5" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/></svg>
			</a>
			<button class="nav-toggle" type="button" aria-expanded="false" aria-controls="noor-drawer" aria-label="<?php esc_attr_e( 'Open menu', 'noorstudio' ); ?>">
				<svg viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M4 7h16M4 12h16M4 17h16" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></svg>
			</button>
		</div>
	</div>
</nav>

<div class="nav-overlay" hidden></div>
<aside class="nav-drawer" id="noor-drawer" aria-label="<?php esc_attr_e( 'Mobile navigation', 'noorstudio' ); ?>" aria-hidden="true">
	<button class="nav-drawer-close" type="button" aria-label="<?php esc_attr_e( 'Close menu', 'noorstudio' ); ?>">&times;</button>
	<?php
	foreach ( noor_nav_links() as $link ) {
		printf( '<a href="%s">%s</a>', noor_link( $link['url'] ), esc_html( $link['label'] ) );
	}
	?>
	<a href="<?php echo noor_link( '/login/' ); ?>"><?php esc_html_e( 'Log in', 'noorstudio' ); ?></a>
	<a href="<?php echo noor_link( '/pricing/' ); ?>" class="btn-primary lg"><?php esc_html_e( 'Start creating', 'noorstudio' ); ?></a>
</aside>
