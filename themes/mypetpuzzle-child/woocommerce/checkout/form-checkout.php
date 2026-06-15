<?php
/**
 * Checkout Form
 *
 * @see https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.5.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

do_action( 'woocommerce_before_checkout_form', $checkout );

// If checkout registration is required and not logged in, show message.
if ( ! $checkout->is_registration_enabled() && $checkout->is_registration_required() && ! is_user_logged_in() ) {
	echo esc_html( apply_filters( 'woocommerce_checkout_must_be_logged_in_message', __( 'You must be logged in to checkout.', 'woocommerce' ) ) );
	return;
}
?>

<section class="checkout-page">
	<header class="checkout-page__header">
		<h1 class="checkout-page__title">Validation de commande</h1>
	</header>

	<form name="checkout" method="post" class="checkout checkout-page__form woocommerce-checkout" action="<?php echo esc_url( wc_get_checkout_url() ); ?>" enctype="multipart/form-data">

		<?php if ( $checkout->get_checkout_fields() ) : ?>

			<div class="checkout-page__layout">
				<div class="checkout-page__fields">

					<?php do_action( 'woocommerce_checkout_before_customer_details' ); ?>

					<div class="checkout-page__section" id="customer_details">
						<div class="checkout-page__section-billing">
							<?php do_action( 'woocommerce_checkout_billing' ); ?>
						</div>

						<div class="checkout-page__section-shipping">
							<?php do_action( 'woocommerce_checkout_shipping' ); ?>
						</div>

						<div class="checkout-page__payment-inner">
							<h2 class="checkout-page__section-title">Mode de paiement</h2>
							<?php
							$order_button_text = apply_filters( 'woocommerce_order_button_text', 'Passer la commande' );
							$available_gateways = WC()->payment_gateways()->get_available_payment_gateways();
							wc_get_template( 'checkout/payment.php', array(
								'checkout'           => $checkout,
								'available_gateways' => $available_gateways,
								'order_button_text'  => $order_button_text,
							) );
							?>
						</div>
					</div>

					<?php do_action( 'woocommerce_checkout_after_customer_details' ); ?>

				</div>

				<aside class="checkout-page__summary">
					<div class="checkout-summary">
						<h2 class="checkout-summary__title">Votre commande</h2>
						<?php wc_get_template( 'checkout/review-order.php', array( 'checkout' => $checkout ) ); ?>
					</div>
				</aside>
			</div>

		<?php endif; ?>

	</form>
</section>

<?php do_action( 'woocommerce_after_checkout_form', $checkout ); ?>

<script>
jQuery(function($) {
	function fixCheckoutLabels() {
		$('label[for="order_comments"]').html('Notes de commande');
		$('.optional').text('(optionnel)');
		$('.wc-stripe-save-source').remove();
	}
	fixCheckoutLabels();
	$(document.body).on('updated_checkout', fixCheckoutLabels);
});
</script>
