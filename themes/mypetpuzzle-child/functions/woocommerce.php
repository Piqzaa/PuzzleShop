<?php

declare(strict_types=1);

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
                        <?php
                        $thumb_url = $cart_item['cpz_image_thumb_url'] ?? '';
                        if ($thumb_url) :
                            echo '<img src="' . esc_url($thumb_url) . '" alt="Photo puzzle" width="50" height="50" class="header__cart-item-img">';
                        else :
                            echo $_product->get_image('thumbnail', ['width' => 50, 'height' => 50, 'class' => 'header__cart-item-img']);
                        endif;
                        ?>
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
    $query = new WP_Query(array(
        'post_type'      => 'page',
        'post_status'    => 'publish',
        'name'           => 'page-puzzle',
        'posts_per_page' => 1,
    ));
    return $query->have_posts() ? get_permalink($query->posts[0]->ID) : home_url('/');
}

function cpz_get_shipping_cost_html(string $free_label = 'Offert'): string {
    $subtotal = WC()->cart->get_subtotal();
    $free_threshold = 50;

    if ($subtotal >= $free_threshold) {
        return $free_label;
    }

    $customer = WC()->customer;
    if (!empty(WC()->session) && empty($customer->get_shipping_country())) {
        $base = wc_get_base_location();
        $customer->set_shipping_country($base['country']);
        if (!empty($base['state'])) {
            $customer->set_shipping_state($base['state']);
        }
        $customer->set_shipping_postcode('');
        $customer->set_shipping_city('');
    }

    $packages = WC()->shipping()->get_packages();
    if (!empty($packages)) {
        $package = reset($packages);
        $rates = $package['rates'];
        if (!empty($rates)) {
            $chosen = WC()->session->get('chosen_shipping_methods', array());
            $chosen = !empty($chosen) ? reset($chosen) : '';
            $rate = isset($rates[$chosen]) ? $rates[$chosen] : reset($rates);
            return wc_price($rate->cost + array_sum($rate->taxes));
        }
    }

    $remaining = wc_price($free_threshold - $subtotal);
    return sprintf('%s dès %s', $free_label, $remaining);
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

add_filter('woocommerce_product_is_visible', function (bool $visible, int $product_id): bool {
    if ($product_id === MYPETPUZZLE_CUSTOM_PRODUCT_ID) {
        return false;
    }
    return $visible;
}, 10, 2);

add_action('template_redirect', function () {
    if (is_singular('product') && get_queried_object_id() === MYPETPUZZLE_CUSTOM_PRODUCT_ID) {
        wp_safe_redirect(cpz_puzzle_page_url(), 301);
        exit;
    }
});

function cpz_get_product_variations_data(WC_Product $product): array {
    $data = [
        'variations'            => [],
        'default_variation_id'  => 0,
        'display_price'         => '',
        'can_add_to_cart'       => false,
        'is_variable'           => false,
    ];

    if ($product->get_type() === 'variable') {
        $data['is_variable'] = true;
        $available = $product->get_available_variations();
        $min_price = PHP_FLOAT_MAX;

        foreach ($available as $variation) {
            if (!$variation['is_purchasable'] || !$variation['is_in_stock']) {
                continue;
            }

            $var_id = (int) $variation['variation_id'];
            $price  = (float) $variation['display_price'];

            $size_label = '';
            foreach ($variation['attributes'] as $attr_key => $attr_value) {
                if ($attr_value) {
                    $taxonomy = str_replace('attribute_', '', $attr_key);
                    $term = get_term_by('slug', $attr_value, $taxonomy);
                    $size_label = $term ? $term->name : $attr_value;
                    break;
                }
            }

            $data['variations'][] = [
                'id'         => $var_id,
                'price'      => $price,
                'price_text' => wp_strip_all_tags(wc_price($price)),
                'size'       => $size_label,
            ];

            if ($price < $min_price) {
                $min_price = $price;
                $data['default_variation_id'] = $var_id;
            }
        }

        if (!empty($data['variations'])) {
            $data['display_price'] = wc_price($min_price);
            $data['can_add_to_cart'] = true;
        }
    } else {
        $data['display_price'] = wc_price($product->get_price());
        $data['can_add_to_cart'] = $product->is_purchasable() && $product->is_in_stock();
    }

    return $data;
}
