<?php
defined('ABSPATH') || exit;

global $product;

if (!is_a($product, WC_Product::class) || !$product->is_visible()) {
    return;
}

$product_data = cpz_get_product_variations_data($product);
$display_price = $product_data['display_price'];
$can_add_to_cart = $product_data['can_add_to_cart'];
$card_variation_class = $product_data['is_variable'] && !empty($product_data['variations']) ? ' card--variable' : '';
$default_variation_id = $product_data['default_variation_id'];
$variations_data = $product_data['variations'];
$is_variable = $product_data['is_variable'];

$badge_html = $product->is_on_sale() ? '<span class="badge badge--sale">Promo</span>' : '';

ob_start();
wc_product_class('card card--hover' . $card_variation_class, $product);
$card_class_attr = ob_get_clean();

get_template_part('template-parts/product-card', null, compact(
    'product', 'display_price', 'can_add_to_cart', 'default_variation_id',
    'variations_data', 'is_variable', 'badge_html', 'card_class_attr'
));
