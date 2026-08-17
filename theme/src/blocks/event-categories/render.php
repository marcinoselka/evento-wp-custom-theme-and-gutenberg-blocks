<?php
/**
 * Server render for the Event Categories block.
 *
 * @package Evento
 *
 * @var array $attributes Block attributes.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$count = isset( $attributes['count'] ) ? max( 1, (int) $attributes['count'] ) : 6;

$terms = get_terms(
	array(
		'taxonomy'   => 'event_category',
		'hide_empty' => true,
		'number'     => $count,
		'orderby'    => 'count',
		'order'      => 'DESC',
	)
);

$wrapper_attributes = get_block_wrapper_attributes( array( 'class' => 'evento-categories-pills' ) );

if ( is_wp_error( $terms ) || empty( $terms ) ) : ?>
	<div <?php echo $wrapper_attributes; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
		<p class="evento-categories-pills__empty">
			<?php esc_html_e( 'Brak kategorii wydarzeń.', 'evento-cbt' ); ?>
		</p>
	</div>
	<?php
	return;
endif;
?>
<div <?php echo $wrapper_attributes; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
	<?php foreach ( $terms as $term ) : ?>
		<a class="evento-badge-pill evento-category-pill" href="<?php echo esc_url( get_term_link( $term ) ); ?>">
			<?php echo esc_html( $term->name ); ?>
			<span class="evento-category-pill__count">(<?php echo esc_html( number_format_i18n( $term->count ) ); ?>)</span>
		</a>
	<?php endforeach; ?>
</div>
