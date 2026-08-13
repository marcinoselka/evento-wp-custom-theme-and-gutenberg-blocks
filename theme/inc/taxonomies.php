<?php

/**
 * Custom taxonomy registration for EVENTO.
 *
 * @package Evento
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_action( 'init', 'evento_cbt_register_taxonomies' );

/**
 * Registers custom taxonomies.
 */
function evento_cbt_register_taxonomies(): void {
	evento_cbt_register_event_category_taxonomy();
}

/**
 * Registers the event category taxonomy.
 */
function evento_cbt_register_event_category_taxonomy(): void {
	$labels = array(
		'name'              => __( 'Event Categories', 'evento-cbt' ),
		'singular_name'     => __( 'Event Category', 'evento-cbt' ),
		'search_items'      => __( 'Search Event Categories', 'evento-cbt' ),
		'all_items'         => __( 'All Event Categories', 'evento-cbt' ),
		'parent_item'       => __( 'Parent Event Category', 'evento-cbt' ),
		'parent_item_colon' => __( 'Parent Event Category:', 'evento-cbt' ),
		'edit_item'         => __( 'Edit Event Category', 'evento-cbt' ),
		'update_item'       => __( 'Update Event Category', 'evento-cbt' ),
		'add_new_item'      => __( 'Add New Event Category', 'evento-cbt' ),
		'new_item_name'     => __( 'New Event Category Name', 'evento-cbt' ),
		'menu_name'         => __( 'Event Categories', 'evento-cbt' ),
	);

	$args = array(
		'labels'       => $labels,
		'public'       => true,
		'hierarchical' => true,
		'show_in_rest' => true,
		'rest_base'    => 'event-categories',
		'rewrite'      => array(
			'slug' => 'event-category',
		),
	);

	register_taxonomy( 'event_category', array( 'event' ), $args );
}
