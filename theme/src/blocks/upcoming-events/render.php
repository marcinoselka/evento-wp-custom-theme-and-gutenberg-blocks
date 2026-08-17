<?php
/**
 * Server render for the Upcoming Events block.
 *
 * @package Evento
 *
 * @var array $attributes Block attributes.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$count = isset( $attributes['count'] ) ? max( 1, (int) $attributes['count'] ) : 6;

$query = new WP_Query(
	array(
		'post_type'      => 'event',
		'post_status'    => 'publish',
		'posts_per_page' => $count,
		'meta_key'       => '_event_start_datetime',
		'orderby'        => 'meta_value',
		'order'          => 'ASC',
		'meta_query'     => array(
			array(
				'key'     => '_event_start_datetime',
				'value'   => current_time( 'mysql' ),
				'compare' => '>=',
				'type'    => 'DATETIME',
			),
		),
	)
);

$wrapper_attributes = get_block_wrapper_attributes( array( 'class' => 'evento-events-grid' ) );

if ( ! $query->have_posts() ) : ?>
	<div <?php echo $wrapper_attributes; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
		<p class="evento-events-grid__empty">
			<?php esc_html_e( 'Brak nadchodzących wydarzeń. Zajrzyj tu wkrótce.', 'evento-cbt' ); ?>
		</p>
	</div>
	<?php
	return;
endif;
?>
<div <?php echo $wrapper_attributes; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
	<?php
	while ( $query->have_posts() ) :
		$query->the_post();

		$event_id     = get_the_ID();
		$start_string = (string) get_post_meta( $event_id, '_event_start_datetime', true );
		$start_time   = $start_string ? strtotime( $start_string ) : false;
		$is_free      = (int) get_post_meta( $event_id, '_event_is_free', true );
		$price        = (string) get_post_meta( $event_id, '_event_price_from', true );
		$venue_id     = (int) get_post_meta( $event_id, '_event_venue_id', true );
		$venue_name   = $venue_id ? get_the_title( $venue_id ) : '';
		$district     = '';

		if ( $venue_id ) {
			$district_terms = get_the_terms( $venue_id, 'district' );
			if ( ! is_wp_error( $district_terms ) && ! empty( $district_terms ) ) {
				$district = $district_terms[0]->name;
			}
		}

		$categories = get_the_terms( $event_id, 'event_category' );
		$categories = is_wp_error( $categories ) ? array() : $categories;
		?>
		<article class="evento-card-surface evento-event-card">
			<a class="evento-event-card__media" href="<?php the_permalink(); ?>">
				<?php if ( has_post_thumbnail() ) : ?>
					<?php the_post_thumbnail( 'large' ); ?>
				<?php else : ?>
					<span class="evento-event-card__placeholder" aria-hidden="true"></span>
				<?php endif; ?>

				<?php if ( ! empty( $categories ) ) : ?>
					<span class="evento-event-card__categories">
						<?php foreach ( $categories as $category ) : ?>
							<span class="evento-badge-pill evento-event-card__category"><?php echo esc_html( $category->name ); ?></span>
						<?php endforeach; ?>
					</span>
				<?php endif; ?>

				<?php if ( 1 === $is_free ) : ?>
					<span class="evento-badge-pill evento-event-card__price evento-event-card__price--free"><?php esc_html_e( 'Bezpłatne', 'evento-cbt' ); ?></span>
				<?php elseif ( '' !== $price ) : ?>
					<span class="evento-badge-pill evento-event-card__price">
						<?php
						/* translators: %s: price */
						echo esc_html( sprintf( __( 'od %s zł', 'evento-cbt' ), number_format_i18n( (float) $price, 0 ) ) );
						?>
					</span>
				<?php endif; ?>
			</a>

			<div class="evento-event-card__body">
				<?php if ( $start_time ) : ?>
					<time class="evento-event-card__time" datetime="<?php echo esc_attr( wp_date( 'c', $start_time ) ); ?>">
						<?php echo esc_html( wp_date( 'D, j M', $start_time ) . ' · ' . wp_date( 'H:i', $start_time ) ); ?>
					</time>
				<?php endif; ?>

				<h3 class="evento-event-card__title">
					<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
				</h3>

				<?php if ( '' !== $venue_name ) : ?>
					<p class="evento-event-card__venue">
						<svg class="evento-event-card__pin" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="none" aria-hidden="true">
							<circle cx="10" cy="8" r="5.25" stroke="currentColor" stroke-width="1.5"/>
							<circle cx="10" cy="8" r="1.75" fill="currentColor"/>
							<path d="M10 18c2.5-3 4.5-5.8 4.5-9" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
							<path d="M10 18c-2.5-3-4.5-5.8-4.5-9" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
						</svg>
						<?php echo esc_html( implode( ' · ', array_filter( array( $venue_name, $district ) ) ) ); ?>
					</p>
				<?php endif; ?>
			</div>
		</article>
		<?php
	endwhile;
	wp_reset_postdata();
	?>
</div>
