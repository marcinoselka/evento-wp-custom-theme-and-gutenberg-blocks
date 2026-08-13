<?php

/**
 * Custom post type registration for EVENTO.
 *
 * @package Evento
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_action( 'init', 'evento_cbt_register_post_types' );

/**
 * Registers custom post types.
 */
function evento_cbt_register_post_types(): void {
	evento_cbt_register_event_post_type();
	evento_cbt_register_venue_post_type();
}

/**
 * Registers the event custom post type.
 */
function evento_cbt_register_event_post_type(): void {
	$labels = array(
		'name'                  => __( 'Events', 'evento-cbt' ),
		'singular_name'         => __( 'Event', 'evento-cbt' ),
		'menu_name'             => __( 'Events', 'evento-cbt' ),
		'name_admin_bar'        => __( 'Event', 'evento-cbt' ),
		'add_new'               => __( 'Add New', 'evento-cbt' ),
		'add_new_item'          => __( 'Add New Event', 'evento-cbt' ),
		'new_item'              => __( 'New Event', 'evento-cbt' ),
		'edit_item'             => __( 'Edit Event', 'evento-cbt' ),
		'view_item'             => __( 'View Event', 'evento-cbt' ),
		'all_items'             => __( 'All Events', 'evento-cbt' ),
		'search_items'          => __( 'Search Events', 'evento-cbt' ),
		'not_found'             => __( 'No events found.', 'evento-cbt' ),
		'not_found_in_trash'    => __( 'No events found in Trash.', 'evento-cbt' ),
		'featured_image'        => __( 'Event image', 'evento-cbt' ),
		'set_featured_image'    => __( 'Set event image', 'evento-cbt' ),
		'remove_featured_image' => __( 'Remove event image', 'evento-cbt' ),
		'use_featured_image'    => __( 'Use as event image', 'evento-cbt' ),
	);

	$args = array(
		'labels'       => $labels,
		'public'       => true,
		'show_in_rest' => true,
		'rest_base'    => 'events',
		'has_archive'  => true,
		'menu_icon'    => 'dashicons-calendar-alt',
		'rewrite'      => array(
			'slug' => 'events',
		),
		'supports'     => array(
			'title',
			'editor',
			'thumbnail',
			'custom-fields',
		),
	);

	register_post_type( 'event', $args );
}

/**
 * Registers the venue custom post type.
 */
function evento_cbt_register_venue_post_type(): void {
	$labels = array(
		'name'                  => __( 'Venues', 'evento-cbt' ),
		'singular_name'         => __( 'Venue', 'evento-cbt' ),
		'menu_name'             => __( 'Venues', 'evento-cbt' ),
		'name_admin_bar'        => __( 'Venue', 'evento-cbt' ),
		'add_new'               => __( 'Add New', 'evento-cbt' ),
		'add_new_item'          => __( 'Add New Venue', 'evento-cbt' ),
		'new_item'              => __( 'New Venue', 'evento-cbt' ),
		'edit_item'             => __( 'Edit Venue', 'evento-cbt' ),
		'view_item'             => __( 'View Venue', 'evento-cbt' ),
		'all_items'             => __( 'All Venues', 'evento-cbt' ),
		'search_items'          => __( 'Search Venues', 'evento-cbt' ),
		'not_found'             => __( 'No venues found.', 'evento-cbt' ),
		'not_found_in_trash'    => __( 'No venues found in Trash.', 'evento-cbt' ),
		'featured_image'        => __( 'Venue image', 'evento-cbt' ),
		'set_featured_image'    => __( 'Set venue image', 'evento-cbt' ),
		'remove_featured_image' => __( 'Remove venue image', 'evento-cbt' ),
		'use_featured_image'    => __( 'Use as venue image', 'evento-cbt' ),
	);

	$args = array(
		'labels'       => $labels,
		'public'       => true,
		'show_in_rest' => true,
		'rest_base'    => 'venues',
		'has_archive'  => true,
		'menu_icon'    => 'dashicons-location',
		'rewrite'      => array(
			'slug' => 'venues',
		),
		'supports'     => array(
			'title',
			'editor',
			'thumbnail',
		),
	);

	register_post_type( 'venue', $args );
}
