<?php

/**
 * Custom meta field registration for EVENTO.
 *
 * @package Evento
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_action( 'init', 'evento_cbt_register_meta_fields' );

/**
 * Registers custom meta fields.
 */
function evento_cbt_register_meta_fields(): void {
	evento_cbt_register_event_meta_fields();
	evento_cbt_register_venue_meta_fields();
}

/**
 * Registers event meta fields.
 */
function evento_cbt_register_event_meta_fields(): void {
	$event_meta_fields = array(
		'_event_start_datetime' => array(
			'type'              => 'string',
			'description'       => __( 'Event start date and time in the WordPress local timezone.', 'evento-cbt' ),
			'sanitize_callback' => 'evento_cbt_sanitize_datetime',
			'schema'            => array(
				'pattern' => '^\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}$',
			),
		),
		'_event_end_datetime'   => array(
			'type'              => 'string',
			'description'       => __( 'Event end date and time in the WordPress local timezone.', 'evento-cbt' ),
			'sanitize_callback' => 'evento_cbt_sanitize_datetime',
			'schema'            => array(
				'pattern' => '^\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}$',
			),
		),
		'_event_venue_id'       => array(
			'type'              => 'integer',
			'description'       => __( 'Venue post ID assigned to the event.', 'evento-cbt' ),
			'sanitize_callback' => 'absint',
			'schema'            => array(
				'minimum' => 0,
			),
		),
		'_event_organizer'      => array(
			'type'              => 'string',
			'description'       => __( 'Event organizer name.', 'evento-cbt' ),
			'sanitize_callback' => 'sanitize_text_field',
		),
		'_event_price_from'     => array(
			'type'              => 'string',
			'description'       => __( 'Lowest available event price as a decimal string.', 'evento-cbt' ),
			'sanitize_callback' => 'evento_cbt_sanitize_decimal_string',
			'schema'            => array(
				'pattern' => '^\d+(\.\d{2})?$',
			),
		),
		'_event_is_free'        => array(
			'type'              => 'integer',
			'description'       => __( 'Whether the event is marked as free.', 'evento-cbt' ),
			'sanitize_callback' => 'evento_cbt_sanitize_boolean_integer',
			'schema'            => array(
				'enum' => array( 0, 1 ),
			),
		),
		'_event_ticket_url'     => array(
			'type'              => 'string',
			'description'       => __( 'Event ticket, registration, or external information URL.', 'evento-cbt' ),
			'sanitize_callback' => 'esc_url_raw',
			'schema'            => array(
				'format' => 'uri',
			),
		),
	);

	evento_cbt_register_post_meta_fields( 'event', $event_meta_fields );
}

/**
 * Registers venue meta fields.
 */
function evento_cbt_register_venue_meta_fields(): void {
	$venue_meta_fields = array(
		'_venue_address'   => array(
			'type'              => 'string',
			'description'       => __( 'Venue address.', 'evento-cbt' ),
			'sanitize_callback' => 'sanitize_text_field',
		),
		'_venue_latitude'  => array(
			'type'              => 'number',
			'description'       => __( 'Venue latitude.', 'evento-cbt' ),
			'sanitize_callback' => 'evento_cbt_sanitize_float',
			'schema'            => array(
				'minimum' => -90,
				'maximum' => 90,
			),
		),
		'_venue_longitude' => array(
			'type'              => 'number',
			'description'       => __( 'Venue longitude.', 'evento-cbt' ),
			'sanitize_callback' => 'evento_cbt_sanitize_float',
			'schema'            => array(
				'minimum' => -180,
				'maximum' => 180,
			),
		),
		'_venue_website'   => array(
			'type'              => 'string',
			'description'       => __( 'Venue website URL.', 'evento-cbt' ),
			'sanitize_callback' => 'esc_url_raw',
			'schema'            => array(
				'format' => 'uri',
			),
		),
	);

	evento_cbt_register_post_meta_fields( 'venue', $venue_meta_fields );
}

/**
 * Registers a set of post meta fields for one post type.
 *
 * @param string $post_type Post type name.
 * @param array  $meta_fields Meta field definitions.
 */
function evento_cbt_register_post_meta_fields( string $post_type, array $meta_fields ): void {
	foreach ( $meta_fields as $meta_key => $args ) {
		$schema = array_merge(
			array(
				'type' => $args['type'],
			),
			$args['schema'] ?? array()
		);

		register_post_meta(
			$post_type,
			$meta_key,
			array(
				'type'              => $args['type'],
				'description'       => $args['description'],
				'single'            => true,
				'show_in_rest'      => array(
					'schema' => $schema,
				),
				'sanitize_callback' => $args['sanitize_callback'],
				'auth_callback'     => '__return_true',
			)
		);
	}
}

/**
 * Sanitizes a local WordPress datetime string.
 *
 * @param mixed $value Raw meta value.
 * @return string
 */
function evento_cbt_sanitize_datetime( mixed $value ): string {
	$value = trim( (string) $value );

	if ( '' === $value ) {
		return '';
	}

	if ( ! preg_match( '/^(\d{4})-(\d{2})-(\d{2}) (\d{2}):(\d{2}):(\d{2})$/', $value, $matches ) ) {
		return '';
	}

	$year   = (int) $matches[1];
	$month  = (int) $matches[2];
	$day    = (int) $matches[3];
	$hour   = (int) $matches[4];
	$minute = (int) $matches[5];
	$second = (int) $matches[6];

	if ( ! checkdate( $month, $day, $year ) || $hour > 23 || $minute > 59 || $second > 59 ) {
		return '';
	}

	return $value;
}

/**
 * Sanitizes a decimal string with two decimal places.
 *
 * @param mixed $value Raw meta value.
 * @return string
 */
function evento_cbt_sanitize_decimal_string( mixed $value ): string {
	$value = trim( (string) $value );

	if ( '' === $value ) {
		return '';
	}

	if ( ! preg_match( '/^\d+(\.\d{1,2})?$/', $value ) ) {
		return '';
	}

	return number_format( (float) $value, 2, '.', '' );
}

/**
 * Sanitizes a boolean-like value to 0 or 1.
 *
 * @param mixed $value Raw meta value.
 * @return int
 */
function evento_cbt_sanitize_boolean_integer( mixed $value ): int {
	return in_array( $value, array( 1, '1', true, 'true' ), true ) ? 1 : 0;
}

/**
 * Sanitizes a numeric value to float.
 *
 * @param mixed $value Raw meta value.
 * @return float
 */
function evento_cbt_sanitize_float( mixed $value ): float {
	return (float) $value;
}
