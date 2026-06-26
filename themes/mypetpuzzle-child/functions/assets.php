<?php

defined('ABSPATH') || exit;

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

    $dependencies = ['mypetpuzzle-child-fonts'];

    wp_enqueue_style(
        'mypetpuzzle-child-main',
        $uri . '/assets/css/main.css',
        $dependencies,
        $version
    );

    $scripts = [
        'mypetpuzzle-child-header'          => ['file' => 'header', 'deps' => ['wc-cart-fragments']],
        'mypetpuzzle-child-faq'             => ['file' => 'faq'],
        'mypetpuzzle-child-animations'      => ['file' => 'animations'],
        'mypetpuzzle-child-cursor'          => ['file' => 'cursor'],
        'mypetpuzzle-child-puzzle-floating' => ['file' => 'puzzle-floating'],
        'mypetpuzzle-child-cookies'         => ['file' => 'cookies'],
        'mypetpuzzle-child-product-card'    => ['file' => 'product-card', 'deps' => ['wc-cart-fragments']],
        'mypetpuzzle-child-notices-dismiss' => ['file' => 'notices-dismiss'],

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

    wp_localize_script('mypetpuzzle-child-product-card', 'mypetpuzzle_ajax', [
        'ajax_url' => admin_url('admin-ajax.php'),
        'nonce'    => wp_create_nonce('bestseller_add_to_cart'),
    ]);

    if (is_front_page()) {
        wp_enqueue_script(
            'mypetpuzzle-child-registration-notice',
            $uri . '/assets/js/modules/registration-notice.js',
            [],
            $version,
            true
        );
    }

    if (is_product()) {
        wp_enqueue_script(
            'mypetpuzzle-child-quantity-input',
            $uri . '/assets/js/modules/quantity-input.js',
            [],
            $version,
            true
        );

        wp_enqueue_script(
            'mypetpuzzle-child-product-reviews',
            $uri . '/assets/js/modules/product-reviews.js',
            ['wc-single-product'],
            $version,
            true
        );

    }

    if (is_cart()) {
        wp_enqueue_script(
            'mypetpuzzle-child-cart',
            $uri . '/assets/js/modules/cart.js',
            ['jquery'],
            $version,
            true
        );
    }

    if (is_checkout()) {
        wp_enqueue_script(
            'mypetpuzzle-child-checkout',
            $uri . '/assets/js/modules/checkout.js',
            [],
            $version,
            true
        );
    }

    if (is_page('page-puzzle')) {
        wp_enqueue_style(
            'cropperjs',
            'https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.2/cropper.min.css',
            [],
            '1.6.2'
        );

        wp_enqueue_script(
            'cropperjs',
            'https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.2/cropper.min.js',
            [],
            '1.6.2',
            true
        );

        wp_enqueue_script(
            'mypetpuzzle-custom-puzzle',
            content_url('plugins/mypetpuzzle-core/custome-puzzle.js'),
            ['cropperjs'],
            '1.0.0',
            true
        );

        wp_localize_script('mypetpuzzle-custom-puzzle', 'cpzData', [
            'ajaxUrl'   => admin_url('admin-ajax.php'),
            'nonce'     => wp_create_nonce('cpz_upload_nonce'),
            'productId' => MYPETPUZZLE_CUSTOM_PRODUCT_ID,
            'cartUrl'   => wc_get_cart_url(),
        ]);
    }
}
add_action('wp_enqueue_scripts', 'mypetpuzzle_child_enqueue_assets');

add_action('wp_enqueue_scripts', function () {
    wp_dequeue_style('storefront-style');
    wp_deregister_style('storefront-style');
    wp_dequeue_style('storefront-woocommerce-style');
    wp_deregister_style('storefront-woocommerce-style');
}, 25);
