<?php
/**
 * Soul Theme Customizer
 *
 * @package Soul
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

if ( ! function_exists( 'soul_customize_register' ) ) {
	/**
	 * Register basic support (site title, header text color) for the Theme Customizer.
	 *
	 * @param WP_Customize_Manager $wp_customize Customizer reference.
	 */
	function soul_customize_register( $wp_customize ) {
		$settings = array( 'blogname', 'header_textcolor' );
		foreach ( $settings as $setting ) {
			$get_setting = $wp_customize->get_setting( $setting );
			if ( $get_setting instanceof WP_Customize_Setting ) {
				$get_setting->transport = 'postMessage';
			}
		}

		// Override default partial for custom logo.
		$wp_customize->selective_refresh->add_partial(
			'custom_logo',
			array(
				'settings'            => array( 'custom_logo' ),
				'selector'            => '.custom-logo-link',
				'render_callback'     => 'soul_customize_partial_custom_logo',
				'container_inclusive' => false,
			)
		);
	}
}
add_action( 'customize_register', 'soul_customize_register' );

if ( ! function_exists( 'soul_customize_partial_custom_logo' ) ) {
	/**
	 * Callback for rendering the custom logo, used in the custom_logo partial.
	 *
	 * @return string The custom logo markup or the site title.
	 */
	function soul_customize_partial_custom_logo() {
		if ( has_custom_logo() ) {
			return get_custom_logo();
		} else {
			return get_bloginfo( 'name' );
		}
	}
}

if ( ! function_exists( 'soul_theme_customize_register' ) ) {
	/**
	 * Register individual settings through customizer's API.
	 *
	 * @param WP_Customize_Manager $wp_customize Customizer reference.
	 */
	function soul_theme_customize_register( $wp_customize ) {

		// Theme layout settings.
		$wp_customize->add_section(
			'soul_theme_layout_options',
			array(
				'title'       => __( 'Theme Layout Settings', 'soul' ),
				'capability'  => 'edit_theme_options',
				'description' => __( 'Container width and sidebar defaults', 'soul' ),
				'priority'    => apply_filters( 'soul_theme_layout_options_priority', 160 ),
			)
		);

		$wp_customize->add_setting(
			'soul_bootstrap_version',
			array(
				'default'           => 'bootstrap4',
				'type'              => 'theme_mod',
				'sanitize_callback' => 'sanitize_text_field',
				'capability'        => 'edit_theme_options',
			)
		);

		$wp_customize->add_control(
			new WP_Customize_Control(
				$wp_customize,
				'soul_bootstrap_version',
				array(
					'label'       => __( 'Bootstrap Version', 'soul' ),
					'description' => __( 'Choose between Bootstrap 4 and Bootstrap 5', 'soul' ),
					'section'     => 'soul_theme_layout_options',
					'type'        => 'select',
					'choices'     => array(
						'bootstrap4' => __( 'Bootstrap 4', 'soul' ),
						'bootstrap5' => __( 'Bootstrap 5', 'soul' ),
					),
					'priority'    => apply_filters( 'soul_bootstrap_version_priority', 10 ),
				)
			)
		);

		$wp_customize->add_setting(
			'soul_container_type',
			array(
				'default'           => 'container',
				'type'              => 'theme_mod',
				'sanitize_callback' => 'soul_customize_sanitize_select',
				'capability'        => 'edit_theme_options',
			)
		);

		$wp_customize->add_control(
			new WP_Customize_Control(
				$wp_customize,
				'soul_container_type',
				array(
					'label'       => __( 'Container Width', 'soul' ),
					'description' => __( 'Choose between Bootstrap\'s container and container-fluid', 'soul' ),
					'section'     => 'soul_theme_layout_options',
					'type'        => 'select',
					'choices'     => array(
						'container'       => __( 'Fixed width container', 'soul' ),
						'container-fluid' => __( 'Full width container', 'soul' ),
					),
					'priority'    => apply_filters( 'soul_container_type_priority', 10 ),
				)
			)
		);

		$wp_customize->add_setting(
			'soul_navbar_type',
			array(
				'default'           => 'collapse',
				'type'              => 'theme_mod',
				'sanitize_callback' => 'soul_customize_sanitize_select',
				'capability'        => 'edit_theme_options',
			)
		);

		$wp_customize->add_control(
			new WP_Customize_Control(
				$wp_customize,
				'soul_navbar_type',
				array(
					'label'       => __( 'Responsive Navigation Type', 'soul' ),
					'description' => __(
						'Choose between an expanding and collapsing navbar or an offcanvas drawer.',
						'soul'
					),
					'section'     => 'soul_theme_layout_options',
					'type'        => 'select',
					'choices'     => array(
						'collapse'  => __( 'Collapse', 'soul' ),
						'offcanvas' => __( 'Offcanvas', 'soul' ),
					),
					'priority'    => apply_filters( 'soul_navbar_type_priority', 20 ),
				)
			)
		);

		$wp_customize->add_setting(
			'soul_sidebar_position',
			array(
				'default'           => 'right',
				'type'              => 'theme_mod',
				'sanitize_callback' => 'soul_customize_sanitize_select',
				'capability'        => 'edit_theme_options',
			)
		);

		$wp_customize->add_control(
			new WP_Customize_Control(
				$wp_customize,
				'soul_sidebar_position',
				array(
					'label'       => __( 'Sidebar Positioning', 'soul' ),
					'description' => __(
						'Set sidebar\'s default position. Can either be: right, left, both or none. Note: this can be overridden on individual pages.',
						'soul'
					),
					'section'     => 'soul_theme_layout_options',
					'type'        => 'select',
					'choices'     => array(
						'right' => __( 'Right sidebar', 'soul' ),
						'left'  => __( 'Left sidebar', 'soul' ),
						'both'  => __( 'Left & Right sidebars', 'soul' ),
						'none'  => __( 'No sidebar', 'soul' ),
					),
					'priority'    => apply_filters( 'soul_sidebar_position_priority', 20 ),
				)
			)
		);

		$wp_customize->add_setting(
			'soul_site_info_override',
			array(
				'default'           => '',
				'type'              => 'theme_mod',
				'sanitize_callback' => 'wp_kses_post',
				'capability'        => 'edit_theme_options',
			)
		);

		$wp_customize->add_control(
			new WP_Customize_Control(
				$wp_customize,
				'soul_site_info_override',
				array(
					'label'       => __( 'Footer Site Info', 'soul' ),
					'description' => __( 'Override Soul\'s site info located at the footer of the page.', 'soul' ),
					'section'     => 'soul_theme_layout_options',
					'type'        => 'textarea',
					'priority'    => 20,
				)
			)
		);

		$soul_site_info = $wp_customize->get_setting( 'soul_site_info_override' );
		if ( $soul_site_info instanceof WP_Customize_Setting ) {
			$soul_site_info->transport = 'postMessage';
		}
	}
} // End of if function_exists( 'soul_theme_customize_register' ).
add_action( 'customize_register', 'soul_theme_customize_register' );

if ( ! function_exists( 'soul_customize_sanitize_select' ) ) {
	/**
	 * Sanitize select.
	 *
	 * @since 1.2.0 Renamed from soul_theme_slug_sanitize_select()
	 *
	 * @param string               $input   Slug to sanitize.
	 * @param WP_Customize_Setting $setting Setting instance.
	 * @return string|bool Sanitized slug if it is a valid choice; the setting default for
	 *                     invalid choices and false in all other cases.
	 */
	function soul_customize_sanitize_select( $input, $setting ) {

		// Ensure input is a slug (lowercase alphanumeric characters, dashes and underscores are allowed only).
		$input = sanitize_key( $input );

		$control = $setting->manager->get_control( $setting->id );
		if ( ! $control instanceof WP_Customize_Control ) {
			return false;
		}

		// Get the list of possible select options.
		$choices = $control->choices;

		// If the input is a valid key, return it; otherwise, return the default.
		return ( array_key_exists( $input, $choices ) ? $input : $setting->default );

	}
}

/**
 * Binds JS handlers to make Theme Customizer preview reload changes asynchronously.
 */
if ( ! function_exists( 'soul_customize_preview_js' ) ) {
	/**
	 * Setup JS integration for live previewing.
	 */
	function soul_customize_preview_js() {
		$file    = '/js/customizer.js';
		$version = filemtime( get_template_directory() . $file );
		if ( false === $version ) {
			$version = time();
		}

		wp_enqueue_script(
			'soul_customizer',
			get_template_directory_uri() . $file,
			array( 'customize-preview' ),
			(string) $version,
			true
		);
	}
}
add_action( 'customize_preview_init', 'soul_customize_preview_js' );

/**
 * Loads javascript for conditionally showing customizer controls.
 */
if ( ! function_exists( 'soul_customize_controls_js' ) ) {
	/**
	 * Setup JS integration for live previewing.
	 *
	 * @since 1.1.0
	 */
	function soul_customize_controls_js() {
		$file    = '/js/customizer-controls.js';
		$version = filemtime( get_template_directory() . $file );
		if ( false === $version ) {
			$version = time();
		}

		wp_enqueue_script(
			'soul_customizer',
			get_template_directory_uri() . $file,
			array( 'customize-preview' ),
			(string) $version,
			true
		);
	}
}
add_action( 'customize_controls_enqueue_scripts', 'soul_customize_controls_js' );

if ( ! function_exists( 'soul_default_navbar_type' ) ) {
	/**
	 * Overrides the responsive navbar type for Bootstrap 4.
	 *
	 * @since 1.1.0
	 *
	 * @param string $current_mod Current navbar type.
	 * @return string Maybe filtered navbar type.
	 */
	function soul_default_navbar_type( $current_mod ) {

		if ( 'bootstrap5' !== get_theme_mod( 'soul_bootstrap_version' ) ) {
			$current_mod = 'collapse';
		}

		return $current_mod;
	}
}
add_filter( 'theme_mod_soul_navbar_type', 'soul_default_navbar_type', 20 );
