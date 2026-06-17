<?php

defined('ABSPATH') || exit;

add_filter('woocommerce_add_to_cart_fragments', function ($fragments) {
    ob_start();
    ?>
    <span class="header__cart-count"><?php echo WC()->cart ? WC()->cart->get_cart_contents_count() : 0; ?></span>
    <?php
    $fragments['span.header__cart-count'] = ob_get_clean();
    return $fragments;
});

add_action('wp', function () {
    remove_action('storefront_before_content', 'woocommerce_breadcrumb', 10);
    remove_action('storefront_sidebar', 'storefront_get_sidebar', 10);

    if (is_cart()) {
        remove_action('storefront_page', 'storefront_page_header', 10);
    }
});

add_filter('woocommerce_package_rates', function ($rates, $package) {
    $subtotal = WC()->cart->get_subtotal();
    if ($subtotal >= MYPETPUZZLE_FREE_SHIPPING_THRESHOLD) {
        foreach ($rates as $rate_key => $rate) {
            $rates[$rate_key]->cost = 0;
            $rates[$rate_key]->taxes = array();
        }
    }
    return $rates;
}, 10, 2);

add_filter('woocommerce_get_notices', function ($notices) {
    if (is_cart()) {
        unset($notices['success']);
    }
    return $notices;
});

add_action('wp_ajax_bestseller_add_to_cart', 'bestseller_add_to_cart_callback');
add_action('wp_ajax_nopriv_bestseller_add_to_cart', 'bestseller_add_to_cart_callback');
function bestseller_add_to_cart_callback() {
    check_ajax_referer('bestseller_add_to_cart', 'security');

    $product_id   = intval($_POST['product_id'] ?? 0);
    $variation_id = intval($_POST['variation_id'] ?? 0);

    if ($variation_id > 0) {
        WC()->cart->add_to_cart($product_id, 1, $variation_id);
    } else {
        WC()->cart->add_to_cart($product_id, 1);
    }

    wp_send_json_success([
        'cart_count' => WC()->cart->get_cart_contents_count(),
    ]);
}

remove_action('woocommerce_checkout_terms_and_conditions', 'wc_checkout_privacy_policy_text', 20);
add_action('woocommerce_checkout_terms_and_conditions', function () {
    $privacy_link = get_privacy_policy_url();
    if ($privacy_link) {
        echo '<p class="woocommerce-privacy-policy-text">Vos données personnelles seront utilisées pour traiter votre commande, améliorer votre expérience sur ce site et à d\'autres fins décrites dans notre <a href="' . esc_url($privacy_link) . '" target="_blank">politique de confidentialité</a>.</p>';
    }
}, 20);

add_filter('gettext', function ($translation, $text, $domain) {
    if ($domain === 'woo-stripe-payment') {
        $translations = array(
            'New Card'     => 'Nouvelle carte',
            'Saved Cards'  => 'Cartes enregistrées',
        );
        if (isset($translations[$text])) {
            return $translations[$text];
        }
    }
    return $translation;
}, 10, 3);
