<?php
/**
 * Server render for the District Map block.
 *
 * Shows every `district` term as a heat-colored tile: the more upcoming
 * events happening at venues in that district, the warmer the color
 * (interpolated from the "sky" to the "sun" palette color).
 *
 * @package Evento
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! function_exists( 'evento_cbt_lerp_hex_color' ) ) {
	/**
	 * Interpolates between two hex colors.
	 *
	 * @param string $hex_a Start color.
	 * @param string $hex_b End color.
	 * @param float  $ratio Interpolation ratio between 0 and 1.
	 * @return string
	 */
	function evento_cbt_lerp_hex_color( string $hex_a, string $hex_b, float $ratio ): string {
		sscanf( $hex_a, '#%02x%02x%02x', $ra, $ga, $ba );
		sscanf( $hex_b, '#%02x%02x%02x', $rb, $gb, $bb );

		$r = (int) round( $ra + ( $rb - $ra ) * $ratio );
		$g = (int) round( $ga + ( $gb - $ga ) * $ratio );
		$b = (int) round( $ba + ( $bb - $ba ) * $ratio );

		return sprintf( '#%02x%02x%02x', $r, $g, $b );
	}
}

if ( ! function_exists( 'evento_cbt_upcoming_event_count_for_venues' ) ) {
	/**
	 * Counts upcoming published events whose venue is in the given list.
	 *
	 * @param array<int, int> $venue_ids Venue post IDs.
	 * @return int
	 */
	function evento_cbt_upcoming_event_count_for_venues( array $venue_ids ): int {
		if ( empty( $venue_ids ) ) {
			return 0;
		}

		$query = new WP_Query(
			array(
				'post_type'      => 'event',
				'post_status'    => 'publish',
				'posts_per_page' => 1,
				'fields'         => 'ids',
				'meta_query'     => array(
					array(
						'key'     => '_event_venue_id',
						'value'   => $venue_ids,
						'compare' => 'IN',
					),
					array(
						'key'     => '_event_start_datetime',
						'value'   => current_time( 'mysql' ),
						'compare' => '>=',
						'type'    => 'DATETIME',
					),
				),
			)
		);

		return (int) $query->found_posts;
	}
}

$terms = get_terms(
	array(
		'taxonomy'   => 'district',
		'hide_empty' => false,
		'orderby'    => 'name',
		'order'      => 'ASC',
	)
);

$wrapper_attributes = get_block_wrapper_attributes( array( 'class' => 'evento-district-map' ) );

if ( is_wp_error( $terms ) || empty( $terms ) ) : ?>
	<div <?php echo $wrapper_attributes; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
		<p class="evento-district-map__empty">
			<?php esc_html_e( 'Brak dzielnic do wyświetlenia.', 'evento-cbt' ); ?>
		</p>
	</div>
	<?php
	return;
endif;

$counts = array();
$max    = 0;

foreach ( $terms as $term ) {
	$venue_ids       = get_objects_in_term( $term->term_id, 'district' );
	$venue_ids       = is_wp_error( $venue_ids ) ? array() : array_map( 'intval', $venue_ids );
	$count           = evento_cbt_upcoming_event_count_for_venues( $venue_ids );
	$counts[ $term->term_id ] = $count;
	$max             = max( $max, $count );
}
?>
<div <?php echo $wrapper_attributes; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
	<?php foreach ( $terms as $term ) : ?>
		<?php
		$count = $counts[ $term->term_id ];
		$ratio = $max > 0 ? $count / $max : 0;
		$color = $count > 0 ? evento_cbt_lerp_hex_color( '#4A90D9', '#E8B33D', $ratio ) : '#E4E2D8';
		?>
		<a class="evento-district-tile" href="<?php echo esc_url( get_term_link( $term ) ); ?>" style="background-color: <?php echo esc_attr( $color ); ?>;">
			<span class="evento-district-tile__name"><?php echo esc_html( $term->name ); ?></span>
			<span class="evento-district-tile__count">
				<?php
				echo esc_html(
					sprintf(
						/* translators: %s: number of events */
						_n( '%s wydarzenie', '%s wydarzeń', $count, 'evento-cbt' ),
						number_format_i18n( $count )
					)
				);
				?>
			</span>
		</a>
	<?php endforeach; ?>
</div>
