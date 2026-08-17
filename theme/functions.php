<?php

/**
 * EVENTO theme functions.
 *
 * @package Evento
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

require_once get_template_directory() . '/inc/post-types.php';
require_once get_template_directory() . '/inc/taxonomies.php';
require_once get_template_directory() . '/inc/meta.php';
require_once get_template_directory() . '/inc/blocks.php';

add_action( 'after_setup_theme', 'evento_cbt_setup' );

/**
 * Sets up theme features used by the EVENTO data model.
 */
function evento_cbt_setup(): void {
	add_theme_support( 'post-thumbnails' );
}

add_action( 'wp_enqueue_scripts', 'evento_cbt_enqueue_styles' );

/**
 * Enqueues the theme stylesheet.
 */
function evento_cbt_enqueue_styles(): void {
	wp_enqueue_style(
		'evento-cbt-fonts',
		'https://fonts.googleapis.com/css2?family=Unbounded:wght@600;700;800;900&family=Manrope:wght@400;500;600;700;800&display=swap',
		array(),
		null
	);
	wp_enqueue_style( 'evento-cbt-style', get_stylesheet_uri(), array( 'evento-cbt-fonts' ), wp_get_theme()->get( 'Version' ) );
}
