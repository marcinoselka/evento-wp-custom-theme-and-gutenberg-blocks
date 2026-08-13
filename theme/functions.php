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

add_action( 'after_setup_theme', 'evento_cbt_setup' );

/**
 * Sets up theme features used by the EVENTO data model.
 */
function evento_cbt_setup(): void {
	add_theme_support( 'post-thumbnails' );
}
