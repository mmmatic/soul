<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after
 *
 * @package Soul
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

$container = get_theme_mod( 'soul_container_type' );
?>

</div><!-- #page -->

<?php wp_footer(); ?>

</body>

</html>

