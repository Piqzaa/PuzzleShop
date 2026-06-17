<?php
/**
 * Checkout Order Review Template
 *
 * @see     https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.8.0
 */

defined( 'ABSPATH' ) || exit;
?>
<div class="woocommerce-checkout-review-order-table">
<div class="checkout-summary__items">
	<?php do_action( 'woocommerce_review_order_before_cart_contents' ); ?>

	<?php
	foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) :
		$_product = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );

		if ( $_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters( 'woocommerce_checkout_cart_item_visible', true, $cart_item, $cart_item_key ) ) :
			$product_permalink = apply_filters( 'woocommerce_cart_item_permalink', $_product->is_visible() ? $_product->get_permalink( $cart_item ) : '', $cart_item, $cart_item_key );
			?>
			<div class="checkout-summary__item <?php echo esc_attr( apply_filters( 'woocommerce_cart_item_class', 'cart_item', $cart_item, $cart_item_key ) ); ?>">
				<div class="checkout-summary__item-image">
					<?php
					$thumbnail = apply_filters( 'woocommerce_cart_item_thumbnail', $_product->get_image( 'thumbnail' ), $cart_item, $cart_item_key );
					if ( ! $product_permalink ) {
						echo $thumbnail;
					} else {
						printf( '<a href="%s">%s</a>', esc_url( $product_permalink ), $thumbnail );
					}
					?>
				</div>
				<div class="checkout-summary__item-details">
					<span class="checkout-summary__item-name">
						<?php echo wp_kses_post( apply_filters( 'woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key ) ); ?>
					</span>
					<span class="checkout-summary__item-qty">&times; <?php echo esc_html( $cart_item['quantity'] ); ?></span>
					<?php echo wc_get_formatted_cart_item_data( $cart_item ); ?>
				</div>
				<span class="checkout-summary__item-total">
					<?php echo apply_filters( 'woocommerce_cart_item_subtotal', WC()->cart->get_product_subtotal( $_product, $cart_item['quantity'] ), $cart_item, $cart_item_key ); ?>
				</span>
			</div>
		<?php endif; ?>
	<?php endforeach; ?>

	<?php do_action( 'woocommerce_review_order_after_cart_contents' ); ?>
</div>

<div class="checkout-summary__totals">
	<div class="checkout-summary__row checkout-summary__row--subtotal">
		<span class="checkout-summary__row-label">Sous-total</span>
		<span class="checkout-summary__row-value"><?php wc_cart_totals_subtotal_html(); ?></span>
	</div>

	<?php foreach ( WC()->cart->get_coupons() as $code => $coupon ) : ?>
		<div class="checkout-summary__row checkout-summary__row--discount coupon-<?php echo esc_attr( sanitize_title( $code ) ); ?>">
			<span class="checkout-summary__row-label"><?php wc_cart_totals_coupon_label( $coupon ); ?></span>
			<span class="checkout-summary__row-value"><?php wc_cart_totals_coupon_html( $coupon ); ?></span>
		</div>
	<?php endforeach; ?>

	<?php if ( WC()->cart->needs_shipping() && WC()->cart->show_shipping() ) : ?>
		<?php do_action( 'woocommerce_review_order_before_shipping' ); ?>

		<div class="checkout-summary__row checkout-summary__row--shipping">
			<span class="checkout-summary__row-label">Livraison</span>
			<span class="checkout-summary__row-value">
				<?php
				$shipping_threshold = 50;
				$cart_subtotal = WC()->cart->get_subtotal();
				$applied_coupons = WC()->cart->get_applied_coupons();
				if ( ! empty( $applied_coupons ) ) {
					$cart_subtotal = WC()->cart->get_cart_contents_total();
				}
				if ( $cart_subtotal >= $shipping_threshold ) {
					echo 'Offert';
				} else {
					echo wc_price( 4.90 );
				}
				?>
			</span>
		</div>

		<?php do_action( 'woocommerce_review_order_after_shipping' ); ?>
	<?php endif; ?>

	<?php foreach ( WC()->cart->get_fees() as $fee ) : ?>
		<div class="checkout-summary__row checkout-summary__row--fee">
			<span class="checkout-summary__row-label"><?php echo esc_html( $fee->name ); ?></span>
			<span class="checkout-summary__row-value"><?php wc_cart_totals_fee_html( $fee ); ?></span>
		</div>
	<?php endforeach; ?>

	<?php if ( wc_tax_enabled() && ! WC()->cart->display_prices_including_tax() ) : ?>
		<?php if ( 'itemized' === get_option( 'woocommerce_tax_total_display' ) ) : ?>
			<?php foreach ( WC()->cart->get_tax_totals() as $code => $tax ) : ?>
				<div class="checkout-summary__row checkout-summary__row--tax tax-rate-<?php echo esc_attr( sanitize_title( $code ) ); ?>">
					<span class="checkout-summary__row-label"><?php echo esc_html( $tax->label ); ?></span>
					<span class="checkout-summary__row-value"><?php echo wp_kses_post( $tax->formatted_amount ); ?></span>
				</div>
			<?php endforeach; ?>
		<?php else : ?>
			<div class="checkout-summary__row checkout-summary__row--tax">
				<span class="checkout-summary__row-label"><?php echo esc_html( WC()->countries->tax_or_vat() ); ?></span>
				<span class="checkout-summary__row-value"><?php wc_cart_totals_taxes_total_html(); ?></span>
			</div>
		<?php endif; ?>
	<?php endif; ?>

	<?php do_action( 'woocommerce_review_order_before_order_total' ); ?>

	<div class="checkout-summary__row checkout-summary__row--total">
		<span class="checkout-summary__row-label">Total</span>
		<span class="checkout-summary__row-value"><?php wc_cart_totals_order_total_html(); ?></span>
	</div>

	<?php do_action( 'woocommerce_review_order_after_order_total' ); ?>
</div>
</div>
