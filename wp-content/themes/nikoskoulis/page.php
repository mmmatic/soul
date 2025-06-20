<?php
/**
 * The template for displaying all pages
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site will use a
 * different template.
 *
 * @package Soul
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

get_header();

$container = get_theme_mod( 'soul_container_type' );
$sections = get_field('sections');
?>

<main id="main" class="site__main">
<?php while ( have_posts() ) {
	the_post();
	// get_template_part( 'loop-templates/content', 'page' );
    if (!empty($sections)):
        if( have_rows('sections') ):
           while ( have_rows('sections') ) : the_row();
                $row_layout = get_row_layout();
                get_template_part( 'loop-templates/sections/' . $row_layout);
            endwhile;
        endif;
    endif;	
} ?>
</main>

<?php get_footer();
