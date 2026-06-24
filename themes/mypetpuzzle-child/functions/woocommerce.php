<?php

defined('ABSPATH') || exit;

add_filter('woocommerce_add_to_cart_fragments', function ($fragments) {
    ob_start();
    ?>
    <span class="header__cart-count"><?php echo WC()->cart ? WC()->cart->get_cart_contents_count() : 0; ?></span>
    <?php
    $fragments['span.header__cart-count'] = ob_get_clean();

    $fragments['.header__cart-dropdown'] = cpz_render_mini_cart_dropdown();

    return $fragments;
});

function cpz_render_mini_cart_dropdown(): string {
    ob_start();
    ?>
    <div class="header__dropdown header__cart-dropdown">
        <?php if (WC()->cart && !WC()->cart->is_empty()) : ?>
            <div class="header__cart-items">
                <?php foreach (WC()->cart->get_cart() as $cart_item_key => $cart_item) :
                    $_product = $cart_item['data'];
                    if (!$_product || !$_product->exists()) continue;
                ?>
                    <div class="header__cart-item">
                        <?php echo $_product->get_image('thumbnail', ['width' => 50, 'height' => 50, 'class' => 'header__cart-item-img']); ?>
                        <div class="header__cart-item-info">
                            <span class="header__cart-item-name"><?php echo esc_html($_product->get_name()); ?></span>
                            <span class="header__cart-item-qty"><?php echo $cart_item['quantity']; ?> &times; <?php echo WC()->cart->get_product_price($_product); ?></span>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            <a href="<?php echo esc_url(wc_get_cart_url()); ?>" class="header__cart-btn">Voir mon panier</a>
        <?php else : ?>
            <p class="header__cart-empty">Votre panier est vide</p>
        <?php endif; ?>
    </div>
    <?php
    return ob_get_clean();
}

add_action('wp', function () {
    remove_action('storefront_before_content', 'woocommerce_breadcrumb', 10);
    remove_action('storefront_sidebar', 'storefront_get_sidebar', 10);
    remove_action('storefront_after_footer', 'storefront_sticky_single_add_to_cart', 999);

    if (is_cart()) {
        remove_action('storefront_page', 'storefront_page_header', 10);
    }
});


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

function cpz_puzzle_page_url(): string {
    $page = get_page_by_path('page-puzzle');
    return $page ? get_permalink($page->ID) : home_url('/');
}

add_filter('woocommerce_package_rates', function ($rates, $package) {
    $subtotal = WC()->cart ? WC()->cart->get_subtotal() : 0;
    if ($subtotal >= 50) {
        foreach ($rates as $rate) {
            $rate->cost = 0;
            $rate->taxes = array();
        }
    }
    return $rates;
}, 10, 2);
