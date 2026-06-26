<?php

declare(strict_types=1);

defined('ABSPATH') || exit;

/**
 * Remove default price range (e.g. "10€ - 20€") for variable products
 * The price is shown in the variation area when a variation is selected
 */
add_action('wp', function () {
    if (is_product()) {
        remove_action('woocommerce_after_single_product_summary', 'storefront_upsell_display', 15);
        remove_action('woocommerce_after_single_product_summary', 'storefront_single_product_pagination', 30);

        $post = get_post();
        if ($post) {
            $product = wc_get_product($post->ID);
            if ($product && $product->is_type('variable')) {
                remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_price', 10);
            }
        }
    }
});

/**
 * Limiter les produits apparentés à 3 maximum
 */
add_filter('woocommerce_output_related_products_args', function ($args) {
    $args['posts_per_page'] = 3;
    $args['columns']        = 3;
    return $args;
});
