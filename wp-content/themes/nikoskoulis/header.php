<?php
/**
 * The header for our theme
 *
 * Displays all of the <head> section and everything up till <div id="content">
 *
 * @package Soul
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

$bootstrap_version = get_theme_mod( 'soul_bootstrap_version', 'bootstrap4' );
$navbar_type       = get_theme_mod( 'soul_navbar_type', 'collapse' );
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<link rel="profile" href="http://gmpg.org/xfn/11">
	<?php wp_head(); ?>
</head>

<body <?php body_class('bg-light'); ?> <?php soul_body_attributes(); ?>>
<?php do_action( 'wp_body_open' ); ?>
<?php get_template_part( 'global-templates/loader'); ?>
<div id="page" class="site">
	<header id="header" class="site__header">
		<a class="skip-link <?php echo soul_get_screen_reader_class( true ); ?>" href="#content">
			<?php esc_html_e( 'Skip to content', 'soul' ); ?>
		</a>
		<?php get_template_part( 'global-templates/navbar', 'branding'); ?>
		<?php get_template_part( 'global-templates/drawer'); ?>
	</header>
