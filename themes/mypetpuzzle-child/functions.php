<?php

define('MYPETPUZZLE_FREE_SHIPPING_THRESHOLD', 50);

function mypetpuzzle_child_enqueue_assets() {
    $theme   = wp_get_theme('mypetpuzzle-child');
    $version = $theme->get('Version');
    $uri     = get_stylesheet_directory_uri();

    wp_enqueue_style(
        'mypetpuzzle-child-fonts',
        'https://fonts.googleapis.com/css2?family=DM+Sans:opsz,wght@9..40,400;9..40,500;9..40,600;9..40,700&family=Playfair+Display:wght@400;500;600;700&display=swap',
        [],
        null
    );

    wp_enqueue_style(
        'mypetpuzzle-child-main',
        $uri . '/assets/css/main.css',
        ['storefront-style', 'mypetpuzzle-child-fonts'],
        $version
    );

    $scripts = [
        'mypetpuzzle-child-header'          => ['file' => 'header', 'deps' => ['wc-cart-fragments']],
        'mypetpuzzle-child-faq'             => ['file' => 'faq'],
        'mypetpuzzle-child-animations'      => ['file' => 'animations'],
        'mypetpuzzle-child-cursor'          => ['file' => 'cursor'],
        'mypetpuzzle-child-puzzle-floating' => ['file' => 'puzzle-floating'],
        'mypetpuzzle-child-cookies'         => ['file' => 'cookies'],
        'mypetpuzzle-child-bestsellers'     => ['file' => 'bestsellers'],
    ];

    foreach ($scripts as $handle => $cfg) {
        wp_enqueue_script(
            $handle,
            $uri . '/assets/js/modules/' . $cfg['file'] . '.js',
            $cfg['deps'] ?? [],
            $version,
            true
        );
    }

    wp_localize_script('mypetpuzzle-child-bestsellers', 'mypetpuzzle_ajax', [
        'ajax_url' => admin_url('admin-ajax.php'),
        'nonce'    => wp_create_nonce('bestseller_add_to_cart'),
    ]);

    if (is_cart()) {
        wp_enqueue_script(
            'mypetpuzzle-child-cart',
            $uri . '/assets/js/modules/cart.js',
            ['jquery'],
            $version,
            true
        );
    }
}
add_action('wp_enqueue_scripts', 'mypetpuzzle_child_enqueue_assets');

function mypetpuzzle_child_theme_support() {
    add_theme_support('woocommerce');
    add_theme_support('wc-product-gallery-zoom');
    add_theme_support('wc-product-gallery-lightbox');
    add_theme_support('wc-product-gallery-slider');
}
add_action('after_setup_theme', 'mypetpuzzle_child_theme_support');

function mypetpuzzle_child_register_menus() {
    register_nav_menus(array(
        'mypetpuzzle-primary' => __('Navigation principale', 'mypetpuzzle-child'),
    ));
}
add_action('after_setup_theme', 'mypetpuzzle_child_register_menus');

function mypetpuzzle_flush_rewrites() {
    flush_rewrite_rules();
}
add_action('after_switch_theme', 'mypetpuzzle_flush_rewrites');

function mypetpuzzle_page_templates() {
    if (get_option('mypetpuzzle_pages_created')) {
        return;
    }
    $existing = get_posts(array('post_type' => 'page', 'post_status' => 'any', 'numberposts' => -1));
    $slugs    = wp_list_pluck($existing, 'post_name');
    $pages    = array(
        'mentions-legales' => array('Mentions légales', 'template-mentions-legales.php'),
        'cgv'              => array('Conditions Générales de Vente', 'template-cgv.php'),
        'confidentialite'  => array('Politique de confidentialité', 'template-confidentialite.php'),
        'retours'          => array('Retours et remboursements', 'template-retours.php'),
        'contact'          => array('Contact', 'template-contact.php'),
    );
    foreach ($pages as $slug => $page) {
        if (!in_array($slug, $slugs, true)) {
            wp_insert_post(array(
                'post_title'   => $page[0],
                'post_name'    => $slug,
                'post_type'    => 'page',
                'post_status'  => 'draft',
                'post_author'  => get_current_user_id(),
                'page_template' => $page[1],
            ));
        }
    }
    update_option('mypetpuzzle_pages_created', true);
}
add_action('init', 'mypetpuzzle_page_templates');

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

// Rendre la livraison réellement offerte dès 50€ de sous-total
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

// Traduire les libellés des méthodes de livraison en français
add_filter('woocommerce_shipping_rate_label', function ($label, $rate) {
    $translations = array(
        'Flat rate'       => 'Livraison standard',
        'Free shipping'   => 'Livraison offerte',
        'Local pickup'    => 'Retrait en magasin',
        'Shipment'        => 'Livraison',
        'Shipping'        => 'Livraison',
    );
    if (isset($translations[$label])) {
        return $translations[$label];
    }
    return $label;
}, 10, 2);

// Supprimer le message "Cart updated" sur la page panier
add_filter('woocommerce_get_notices', function ($notices) {
    if (is_cart()) {
        unset($notices['success']);
    }
    return $notices;
});

// AJAX add to cart pour les best-sellers
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

// Traduire les champs d'adresse WooCommerce par défaut
add_filter('woocommerce_default_address_fields', function ($fields) {
    $translations = array(
        'first_name' => array('label' => 'Prénom', 'placeholder' => 'Votre prénom'),
        'last_name'  => array('label' => 'Nom', 'placeholder' => 'Votre nom'),
        'company'    => array('label' => 'Entreprise (optionnel)', 'placeholder' => 'Nom de l\'entreprise'),
        'address_1'  => array('label' => 'Adresse', 'placeholder' => 'Numéro et nom de rue'),
        'address_2'  => array('label' => 'Complément d\'adresse (optionnel)', 'placeholder' => 'Appartement, étage, bureau, etc.'),
        'city'       => array('label' => 'Ville', 'placeholder' => 'Votre ville'),
        'postcode'   => array('label' => 'Code postal', 'placeholder' => 'Votre code postal'),
        'country'    => array('label' => 'Pays / Région'),
        'state'      => array('label' => 'Région / Département'),
        'phone'      => array('label' => 'Téléphone', 'placeholder' => 'Votre numéro de téléphone'),
    );

    foreach ($translations as $key => $trans) {
        if (isset($fields[$key])) {
            if (isset($trans['label'])) {
                $fields[$key]['label'] = $trans['label'];
            }
            if (isset($trans['placeholder'])) {
                $fields[$key]['placeholder'] = $trans['placeholder'];
            }
        }
    }
    return $fields;
});

// Traduire tous les champs checkout (priorité haute pour passer après tout)
add_filter('woocommerce_checkout_fields', function ($fields) {
    if (isset($fields['billing']['billing_phone'])) {
        $fields['billing']['billing_phone']['label'] = 'Téléphone';
        $fields['billing']['billing_phone']['placeholder'] = 'Votre numéro de téléphone';
    }
    if (isset($fields['billing']['billing_email'])) {
        $fields['billing']['billing_email']['label'] = 'Adresse e-mail';
        $fields['billing']['billing_email']['placeholder'] = 'votre@email.com';
    }
    if (isset($fields['shipping']['shipping_phone'])) {
        $fields['shipping']['shipping_phone']['label'] = 'Téléphone';
        $fields['shipping']['shipping_phone']['placeholder'] = 'Votre numéro de téléphone';
        $fields['shipping']['shipping_phone']['required'] = false;
    }
    if (isset($fields['order']['order_comments'])) {
        $fields['order']['order_comments']['label'] = 'Notes de commande';
        $fields['order']['order_comments']['placeholder'] = 'Commentaires concernant votre commande...';
        $fields['order']['order_comments']['required'] = false;
    }
    return $fields;
}, 100);

// Fallback : traduire billing_phone directement
add_filter('woocommerce_billing_fields', function ($fields) {
    if (isset($fields['billing_phone'])) {
        $fields['billing_phone']['label'] = 'Téléphone';
        $fields['billing_phone']['placeholder'] = 'Votre numéro de téléphone';
    }
    return $fields;
}, 100);

// Fallback : traduire shipping_phone (ajouté par WooCommerce 5.6+)
add_filter('woocommerce_shipping_fields', function ($fields) {
    if (isset($fields['shipping_phone'])) {
        $fields['shipping_phone']['label'] = 'Téléphone';
        $fields['shipping_phone']['placeholder'] = 'Votre numéro de téléphone';
    }
    return $fields;
}, 100);

// Forcer le texte du bouton commander en français
add_filter('woocommerce_order_button_text', function ($text) {
    return 'Passer la commande';
}, 100);

// Texte de confidentialité en français
remove_action('woocommerce_checkout_terms_and_conditions', 'wc_checkout_privacy_policy_text', 20);
add_action('woocommerce_checkout_terms_and_conditions', function () {
    $privacy_link = get_privacy_policy_url();
    if ($privacy_link) {
        echo '<p class="woocommerce-privacy-policy-text">Vos données personnelles seront utilisées pour traiter votre commande, améliorer votre expérience sur ce site et à d\'autres fins décrites dans notre <a href="' . esc_url($privacy_link) . '" target="_blank">politique de confidentialité</a>.</p>';
    }
}, 20);

// Traduire "(optional)" en "(optionnel)" via gettext (car WC 10+ ne filtre pas le label)
add_filter('gettext', function ($translation, $text, $domain) {
    if ($domain === 'woocommerce' && $text === 'optional') {
        return 'optionnel';
    }
    return $translation;
}, 10, 3);
