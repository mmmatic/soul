<?php
/**
 * Custom hooks
 *
 * @package Soul
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

if ( ! function_exists( 'soul_site_info' ) ) {
	/**
	 * Add site info hook to WP hook library.
	 */
	function soul_site_info() {
		do_action( 'soul_site_info' );
	}
}

add_action( 'soul_site_info', 'soul_add_site_info' );
if ( ! function_exists( 'soul_add_site_info' ) ) {
	/**
	 * Add site info content.
	 */
	function soul_add_site_info() {
		$the_theme = wp_get_theme();

		$site_info = sprintf(
			'<a href="%1$s">%2$s</a><span class="sep"> | </span>%3$s(%4$s)',
			esc_url( __( 'https://wordpress.org/', 'soul' ) ),
			sprintf(
				/* translators: WordPress */
				esc_html__( 'Proudly powered by %s', 'soul' ),
				'WordPress'
			),
			sprintf(
				/* translators: 1: Theme name, 2: Theme author */
				esc_html__( 'Theme: %1$s by %2$s.', 'soul' ),
				$the_theme->get( 'Name' ),
				'<a href="' . esc_url( __( 'https://soul.com', 'soul' ) ) . '">soul.com</a>'
			),
			sprintf(
				/* translators: Theme version */
				esc_html__( 'Version: %s', 'soul' ),
				$the_theme->get( 'Version' )
			)
		);

		// Check if customizer site info has value.
		if ( get_theme_mod( 'soul_site_info_override' ) ) {
			$site_info = get_theme_mod( 'soul_site_info_override' );
		}

		echo apply_filters( 'soul_site_info_content', $site_info ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

	}
}
