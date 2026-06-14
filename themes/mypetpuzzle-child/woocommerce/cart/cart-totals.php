<?php
defined( 'ABSPATH' ) || exit;
?>
<div class="cart-totals">
    <h2 class="cart-totals__title">Récapitulatif</h2>

    <?php do_action('woocommerce_before_cart_totals'); ?>

    <div class="cart-totals__rows">
        <div class="cart-totals__row cart-totals__row--subtotal">
            <span class="cart-totals__label">Sous-total</span>
            <span class="cart-totals__value"><?php wc_cart_totals_subtotal_html(); ?></span>
        </div>

        <?php foreach (WC()->cart->get_coupons() as $code => $coupon) : ?>
            <div class="cart-totals__row cart-totals__row--discount">
                <span class="cart-totals__label"><?php wc_cart_totals_coupon_label($coupon); ?></span>
                <span class="cart-totals__value"><?php wc_cart_totals_coupon_html($coupon); ?></span>
            </div>
        <?php endforeach; ?>

        <?php if (WC()->cart->needs_shipping() && WC()->cart->show_shipping()) : ?>
            <?php do_action('woocommerce_cart_totals_before_shipping'); ?>
            <div class="cart-totals__row cart-totals__row--shipping">
                <span class="cart-totals__label">Livraison</span>
                <span class="cart-totals__value"><?php wc_cart_totals_shipping_html(); ?></span>
            </div>
            <?php do_action('woocommerce_cart_totals_after_shipping'); ?>
        <?php endif; ?>

        <?php foreach (WC()->cart->get_fees() as $fee) : ?>
            <div class="cart-totals__row">
                <span class="cart-totals__label"><?php echo esc_html($fee->name); ?></span>
                <span class="cart-totals__value"><?php wc_cart_totals_fee_html($fee); ?></span>
            </div>
        <?php endforeach; ?>

        <?php if (wc_tax_enabled() && !WC()->cart->display_prices_including_tax()) : ?>
            <?php if ('itemized' === get_option('woocommerce_tax_total_display')) : ?>
                <?php foreach (WC()->cart->get_tax_totals() as $code => $tax) : ?>
                    <div class="cart-totals__row">
                        <span class="cart-totals__label"><?php echo esc_html($tax->label); ?></span>
                        <span class="cart-totals__value"><?php echo wp_kses_post($tax->formatted_amount); ?></span>
                    </div>
                <?php endforeach; ?>
            <?php else : ?>
                <div class="cart-totals__row">
                    <span class="cart-totals__label"><?php echo esc_html(WC()->countries->tax_or_vat()); ?></span>
                    <span class="cart-totals__value"><?php wc_cart_totals_taxes_total_html(); ?></span>
                </div>
            <?php endif; ?>
        <?php endif; ?>

        <?php do_action('woocommerce_cart_totals_before_order_total'); ?>

        <div class="cart-totals__row cart-totals__row--total">
            <span class="cart-totals__label">Total</span>
            <span class="cart-totals__value"><?php wc_cart_totals_order_total_html(); ?></span>
        </div>

        <?php do_action('woocommerce_cart_totals_after_order_total'); ?>
    </div>

    <div class="cart-totals__checkout">
        <?php do_action('woocommerce_proceed_to_checkout'); ?>
    </div>

    <?php do_action('woocommerce_after_cart_totals'); ?>
</div>
