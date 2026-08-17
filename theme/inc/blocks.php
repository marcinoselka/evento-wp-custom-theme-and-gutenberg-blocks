<?php

/**
 * Custom Gutenberg block registration for EVENTO.
 *
 * @package Evento
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_action( 'init', 'evento_cbt_register_blocks' );

/**
 * Registers custom blocks built from theme/src/blocks.
 */
function evento_cbt_register_blocks(): void {
	$blocks_dir = get_template_directory() . '/build/blocks';

	if ( ! is_dir( $blocks_dir ) ) {
		return;
	}

	foreach ( glob( $blocks_dir . '/*', GLOB_ONLYDIR ) as $block_dir ) {
		if ( file_exists( $block_dir . '/block.json' ) ) {
			register_block_type( $block_dir );
		}
	}
}
