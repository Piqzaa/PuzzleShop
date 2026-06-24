<?php
/**
 * Order Customer Details
 *
 * @see https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 8.7.0
 */

defined( 'ABSPATH' ) || exit;

$show_shipping = ! wc_ship_to_billing_address_only() && $order->needs_shipping_address();
?>

<?php if ( $show_shipping ) : ?>
<div class="checkout-page__addresses">
<?php endif; ?>

	<section class="checkout-page__section woocommerce-customer-details woocommerce-column--billing-address">
		<h2 class="checkout-page__section-title"><?php esc_html_e( 'Billing address', 'woocommerce' ); ?></h2>

		<address>
			<?php echo wp_kses_post( $order->get_formatted_billing_address( esc_html__( 'N/A', 'woocommerce' ) ) ); ?>

			<?php if ( $order->get_billing_phone() ) : ?>
				<p class="woocommerce-customer-details--phone"><?php echo esc_html( $order->get_billing_phone() ); ?></p>
			<?php endif; ?>

			<?php if ( $order->get_billing_email() ) : ?>
				<p class="woocommerce-customer-details--email"><?php echo esc_html( $order->get_billing_email() ); ?></p>
			<?php endif; ?>

			<?php
				do_action( 'woocommerce_order_details_after_customer_address', 'billing', $order );
			?>
		</address>
	</section>

<?php if ( $show_shipping ) : ?>

	<section class="checkout-page__section woocommerce-customer-details woocommerce-column--shipping-address">
		<h2 class="checkout-page__section-title"><?php esc_html_e( 'Shipping address', 'woocommerce' ); ?></h2>

		<address>
			<?php echo wp_kses_post( $order->get_formatted_shipping_address( esc_html__( 'N/A', 'woocommerce' ) ) ); ?>

			<?php if ( $order->get_shipping_phone() ) : ?>
				<p class="woocommerce-customer-details--phone"><?php echo esc_html( $order->get_shipping_phone() ); ?></p>
			<?php endif; ?>

			<?php
				do_action( 'woocommerce_order_details_after_customer_address', 'shipping', $order );
			?>
		</address>
	</section>

</div><!-- /.checkout-page__addresses -->

<?php endif; ?>

<?php do_action( 'woocommerce_order_details_after_customer_details', $order ); ?>
