<?php
/**
 * Thankyou page
 *
 * @see 	https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.7.0
 */

defined( 'ABSPATH' ) || exit;
?>

<div class="checkout-page checkout-page--thankyou">
	<div class="checkout-success">

		<?php if ( $order ) :

			if ( $order->has_status( 'failed' ) ) : ?>

				<div class="checkout-page__notice checkout-page__notice--error">
					<p class="woocommerce-notice woocommerce-notice--error woocommerce-failed-order-cannot-be-paid"><?php esc_html_e( 'Unfortunately your order cannot be processed as the originating bank/merchant has declined your transaction. Please attempt your purchase again.', 'woocommerce' ); ?></p>
					
					<div class="checkout-page__notice-actions">
						<a href="<?php echo esc_url( $order->get_checkout_payment_url() ); ?>" class="btn btn--primary pay"><?php esc_html_e( 'Pay', 'woocommerce' ); ?></a>
						<?php if ( is_user_logged_in() ) : ?>
							<a href="<?php echo esc_url( wc_get_page_permalink( 'myaccount' ) ); ?>" class="btn btn--secondary pay"><?php esc_html_e( 'My account', 'woocommerce' ); ?></a>
						<?php endif; ?>
					</div>
				</div>

			<?php else : ?>

				<div class="checkout-success__icon">
					<svg width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="#417505" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
						<path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
						<polyline points="22 4 12 14.01 9 11.01"></polyline>
					</svg>
				</div>

				<h1 class="checkout-success__message">Merci pour votre commande !</h1>
				<p class="checkout-success__intro">Votre commande a bien été reçue et est en cours de traitement. Un e-mail de confirmation vous a été envoyé.</p>

				<ul class="checkout-success__details">
					<li class="woocommerce-order-overview__order order">
						<span class="checkout-success__detail-label">Numéro de commande :</span>
						<strong class="checkout-success__detail-value"><?php echo $order->get_order_number(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></strong>
					</li>

					<li class="woocommerce-order-overview__date date">
						<span class="checkout-success__detail-label">Date :</span>
						<strong class="checkout-success__detail-value"><?php echo wc_format_datetime( $order->get_date_created() ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></strong>
					</li>

					<?php if ( is_user_logged_in() && $order->get_user_id() === get_current_user_id() && $order->get_billing_email() ) : ?>
						<li class="woocommerce-order-overview__email email">
							<span class="checkout-success__detail-label">E-mail :</span>
							<strong class="checkout-success__detail-value"><?php echo $order->get_billing_email(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></strong>
						</li>
					<?php endif; ?>

					<li class="woocommerce-order-overview__total total">
						<span class="checkout-success__detail-label">Total :</span>
						<strong class="checkout-success__detail-value"><?php echo $order->get_formatted_order_total(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></strong>
					</li>

					<?php if ( $order->get_payment_method_title() ) : ?>
						<li class="woocommerce-order-overview__payment-method method">
							<span class="checkout-success__detail-label">Moyen de paiement :</span>
							<strong class="checkout-success__detail-value"><?php echo wp_kses_post( $order->get_payment_method_title() ); ?></strong>
						</li>
					<?php endif; ?>
				</ul>

			<?php endif; ?>

			<?php do_action( 'woocommerce_thankyou_' . $order->get_payment_method(), $order->get_id() ); ?>
			<?php do_action( 'woocommerce_thankyou', $order->get_id() ); ?>

		<?php else : ?>

			<div class="checkout-success__icon">
				<svg width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="#417505" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
					<path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
					<polyline points="22 4 12 14.01 9 11.01"></polyline>
				</svg>
			</div>

			<h1 class="checkout-success__message">Merci pour votre commande !</h1>
			<p class="checkout-success__intro">Votre commande a bien été reçue.</p>

		<?php endif; ?>

	</div>
</div>
