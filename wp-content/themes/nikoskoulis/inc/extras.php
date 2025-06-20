<?php
/**
 * Custom functions that act independently of the theme templates
 *
 * Eventually, some of the functionality here could be replaced by core features.
 *
 * @package Soul
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

add_filter( 'body_class', 'soul_body_classes' );

if ( ! function_exists( 'soul_body_classes' ) ) {
	/**
	 * Adds custom classes to the array of body classes.
	 *
	 * @param array $classes Classes for the body element.
	 *
	 * @return array
	 */
	function soul_body_classes( $classes ) {
		// Adds a class of group-blog to blogs with more than 1 published author.
		if ( is_multi_author() ) {
			$classes[] = 'group-blog';
		}
		// Adds a class of hfeed to non-singular pages.
		if ( ! is_singular() ) {
			$classes[] = 'hfeed';
		}

		// Adds a body class based on the presence of a sidebar.
		$sidebar_pos = get_theme_mod( 'soul_sidebar_position' );
		if ( is_page_template(
			array(
				'page-templates/fullwidthpage.php',
				'page-templates/no-title.php',
			)
		) ) {
			$classes[] = 'soul-no-sidebar';
		} elseif (
			is_page_template(
				array(
					'page-templates/both-sidebarspage.php',
					'page-templates/left-sidebarpage.php',
					'page-templates/right-sidebarpage.php',
				)
			)
		) {
			$classes[] = 'soul-has-sidebar';
		} elseif ( 'none' !== $sidebar_pos ) {
			$classes[] = 'soul-has-sidebar';
		} else {
			$classes[] = 'soul-no-sidebar';
		}

		return $classes;
	}
}

if ( function_exists( 'soul_adjust_body_class' ) ) {
	/*
	 * soul_adjust_body_class() deprecated in v0.9.4. We keep adding the
	 * filter for child themes which use their own soul_adjust_body_class.
	 */
	add_filter( 'body_class', 'soul_adjust_body_class' );
}

// Filter custom logo with correct classes.
add_filter( 'get_custom_logo', 'soul_change_logo_class' );

if ( ! function_exists( 'soul_change_logo_class' ) ) {
	/**
	 * Replaces logo CSS class.
	 *
	 * @param string $html Markup.
	 *
	 * @return string
	 */
	function soul_change_logo_class( $html ) {

		$html = str_replace( 'class="custom-logo"', 'class="img-fluid"', $html );
		$html = str_replace( 'class="custom-logo-link"', 'class="navbar-brand custom-logo-link"', $html );
		$html = str_replace( 'alt=""', 'title="Home" alt="logo"', $html );

		return $html;
	}
}

if ( ! function_exists( 'soul_pingback' ) ) {
	/**
	 * Add a pingback url auto-discovery header for single posts of any post type.
	 */
	function soul_pingback() {
		if ( is_singular() && pings_open() ) {
			echo '<link rel="pingback" href="' . esc_url( get_bloginfo( 'pingback_url' ) ) . '">' . "\n";
		}
	}
}
add_action( 'wp_head', 'soul_pingback' );

if ( ! function_exists( 'soul_mobile_web_app_meta' ) ) {
	/**
	 * Add mobile-web-app meta.
	 */
	function soul_mobile_web_app_meta() {
		echo '<meta name="mobile-web-app-capable" content="yes">' . "\n";
		echo '<meta name="apple-mobile-web-app-capable" content="yes">' . "\n";
		echo '<meta name="apple-mobile-web-app-title" content="' . esc_attr( get_bloginfo( 'name' ) ) . ' - ' . esc_attr( get_bloginfo( 'description' ) ) . '">' . "\n";
	}
}
add_action( 'wp_head', 'soul_mobile_web_app_meta' );

if ( ! function_exists( 'soul_default_body_attributes' ) ) {
	/**
	 * Adds schema markup to the body element.
	 *
	 * @param array<string,string> $atts An associative array of attributes.
	 * @return array<string,string>
	 */
	function soul_default_body_attributes( $atts ) {
		$atts['itemscope'] = '';
		$atts['itemtype']  = 'http://schema.org/WebSite';
		return $atts;
	}
}
add_filter( 'soul_body_attributes', 'soul_default_body_attributes' );

// Escapes all occurrences of 'the_archive_description'.
add_filter( 'get_the_archive_description', 'soul_escape_the_archive_description' );

if ( ! function_exists( 'soul_escape_the_archive_description' ) ) {
	/**
	 * Escapes the description for an author or post type archive.
	 *
	 * @param string $description Archive description.
	 * @return string Maybe escaped $description.
	 */
	function soul_escape_the_archive_description( $description ) {
		if ( is_author() || is_post_type_archive() ) {
			return wp_kses_post( $description );
		}

		/*
		 * All other descriptions are retrieved via term_description() which returns
		 * a sanitized description.
		 */
		return $description;
	}
} // End of if function_exists( 'soul_escape_the_archive_description' ).

// Escapes all occurrences of 'the_title()' and 'get_the_title()'.
add_filter( 'the_title', 'soul_kses_title' );

// Escapes all occurrences of 'the_archive_title' and 'get_the_archive_title()'.
add_filter( 'get_the_archive_title', 'soul_kses_title' );

if ( ! function_exists( 'soul_kses_title' ) ) {
	/**
	 * Sanitizes data for allowed HTML tags for titles.
	 *
	 * @param string $data Title to filter.
	 * @return string Filtered title with allowed HTML tags and attributes intact.
	 */
	function soul_kses_title( $data ) {

		// Get allowed tags and protocols.
		$allowed_tags      = wp_kses_allowed_html( 'post' );
		$allowed_protocols = wp_allowed_protocols();
		if (
			in_array( 'polylang/polylang.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ), true )
			|| in_array( 'polylang-pro/polylang.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ), true )
		) {
			if ( ! in_array( 'data', $allowed_protocols, true ) ) {
				$allowed_protocols[] = 'data';
			}
		}

		if ( has_filter( 'soul_kses_title' ) ) {
			/**
			 * Filters the allowed HTML tags and attributes in titles.
			 *
			 * @param array<string,array<string,bool>> $allowed_tags Allowed HTML tags and attributes in titles.
			 */
			$allowed_tags = apply_filters_deprecated( 'soul_kses_title', array( $allowed_tags ), '1.2.0' );
		}

		return wp_kses( $data, $allowed_tags, $allowed_protocols );
	}
} // End of if function_exists( 'soul_kses_title' ).

if ( ! function_exists( 'soul_hide_posted_by' ) ) {
	/**
	 * Hides the posted by markup in `soul_posted_on()`.
	 *
	 * @since 1.0.0
	 *
	 * @param string $byline Posted by HTML markup.
	 * @return string Maybe filtered posted by HTML markup.
	 */
	function soul_hide_posted_by( $byline ) {
		if ( is_author() ) {
			return '';
		}
		return $byline;
	}
}
add_filter( 'soul_posted_by', 'soul_hide_posted_by' );


add_filter( 'excerpt_more', 'soul_custom_excerpt_more' );

if ( ! function_exists( 'soul_custom_excerpt_more' ) ) {
	/**
	 * Removes the ... from the excerpt read more link
	 *
	 * @param string $more The excerpt.
	 *
	 * @return string
	 */
	function soul_custom_excerpt_more( $more ) {
		if ( ! is_admin() ) {
			$more = '';
		}
		return $more;
	}
}

add_filter( 'wp_trim_excerpt', 'soul_all_excerpts_get_more_link' );

if ( ! function_exists( 'soul_all_excerpts_get_more_link' ) ) {
	/**
	 * Adds a custom read more link to all excerpts, manually or automatically generated
	 *
	 * @param string $post_excerpt Posts's excerpt.
	 *
	 * @return string
	 */
	function soul_all_excerpts_get_more_link( $post_excerpt ) {
		if ( is_admin() || ! get_the_ID() ) {
			return $post_excerpt;
		}

		$permalink = esc_url( get_permalink( (int) get_the_ID() ) ); // @phpstan-ignore-line -- post exists

		return $post_excerpt . ' [...]<p><a class="btn btn-secondary soul-read-more-link" href="' . $permalink . '">' . __(
			'Read More...',
			'soul'
		) . '<span class="screen-reader-text"> from ' . get_the_title( get_the_ID() ) . '</span></a></p>';

	}
}

function soul_allow_svg_uploads_for_admins( $mimes ) {
    if ( current_user_can( 'administrator' ) ) {
        $mimes['svg'] = 'image/svg+xml';
    }
    return $mimes;
}
add_filter( 'upload_mimes', 'soul_allow_svg_uploads_for_admins' );

// Remove unwanted default menu item classes
add_filter('nav_menu_css_class', function ($classes, $item, $args, $depth) {
    // Only allow custom walker to set classes
    return [];
}, 100, 4);

// Remove unwanted submenu classes
add_filter('nav_menu_submenu_css_class', function ($classes, $args, $depth) {
    return [];
}, 100, 3);

// Remove menu item ID
add_filter('nav_menu_item_id', '__return_empty_string');

// Custom logo output
add_filter('get_custom_logo', 'custom_logo_output');
function custom_logo_output($html) {
    // Get the custom logo ID
    $custom_logo_id = get_theme_mod('custom_logo');
    $logo = wp_get_attachment_image_src($custom_logo_id, 'full');

    if ($logo) {
        $logo_url = esc_url($logo[0]);

        return sprintf(
            '<a href="%1$s" class="logo" title="%2$s" rel="home">
                <img src="%3$s" class="logo__img" alt="%2$s" loading="lazy" />
            </a>',
            esc_url(home_url('/')),
            esc_attr(get_bloginfo('name')),
            $logo_url
        );
    }

    return $html; // fallback
}

function register_boutique_post_type() {
    $labels = array(
        'name'                  => _x('Boutiques', 'Post type general name', 'textdomain'),
        'singular_name'         => _x('Boutique', 'Post type singular name', 'textdomain'),
        'menu_name'             => _x('Boutiques', 'Admin Menu text', 'textdomain'),
        'name_admin_bar'        => _x('Boutique', 'Add New on Toolbar', 'textdomain'),
        'add_new'               => __('Add New', 'textdomain'),
        'add_new_item'          => __('Add New Boutique', 'textdomain'),
        'new_item'              => __('New Boutique', 'textdomain'),
        'edit_item'             => __('Edit Boutique', 'textdomain'),
        'view_item'             => __('View Boutique', 'textdomain'),
        'all_items'             => __('All Boutiques', 'textdomain'),
        'search_items'          => __('Search Boutiques', 'textdomain'),
        'parent_item_colon'     => __('Parent Boutiques:', 'textdomain'),
        'not_found'             => __('No boutiques found.', 'textdomain'),
        'not_found_in_trash'    => __('No boutiques found in Trash.', 'textdomain'),
        'featured_image'        => _x('Boutique Cover Image', 'Overrides the “Featured Image” phrase for this post type.', 'textdomain'),
        'set_featured_image'    => _x('Set cover image', 'textdomain'),
        'remove_featured_image' => _x('Remove cover image', 'textdomain'),
        'use_featured_image'    => _x('Use as cover image', 'textdomain'),
        'archives'              => _x('Boutique archives', 'textdomain'),
        'insert_into_item'      => _x('Insert into boutique', 'textdomain'),
        'uploaded_to_this_item' => _x('Uploaded to this boutique', 'textdomain'),
        'filter_items_list'     => _x('Filter boutiques list', 'textdomain'),
        'items_list_navigation' => _x('Boutiques list navigation', 'textdomain'),
        'items_list'            => _x('Boutiques list', 'textdomain'),
    );

    $args = array(
        'labels'             => $labels,
        'description'        => 'A custom post type for Boutiques.',
        'public'             => true,
        'publicly_queryable' => true,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'menu_icon'          => 'dashicons-store', // Optional: change to suit your design
        'query_var'          => true,
        'rewrite'            => array('slug' => 'boutique'),
        'capability_type'    => 'post',
        'has_archive'        => true,
        'hierarchical'       => false,
        'menu_position'      => null,
        'supports'           => array('title', 'editor', 'thumbnail', 'excerpt', 'custom-fields'),
        'show_in_rest'       => true, // Enables Gutenberg and REST API
    );

    register_post_type('boutique', $args);
}
add_action('init', 'register_boutique_post_type');
