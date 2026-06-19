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

    wp_add_inline_script('mypetpuzzle-child-header', '
        document.addEventListener("DOMContentLoaded",function(){
            var d=function(){document.querySelectorAll(".woocommerce-message,.woocommerce-info,.woocommerce-error").forEach(function(n){
                if(!n.dataset.dismissTimer){
                    n.dataset.dismissTimer="true";
                    n.style.transition="opacity .5s ease";
                    setTimeout(function(){n.style.opacity="0";setTimeout(function(){n.remove()},500)},4000)
                }
            })};
            d();
            new MutationObserver(d).observe(document.body,{childList:true,subtree:true})
        });
    ');

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
}
add_action('wp_enqueue_scripts', 'mypetpuzzle_child_enqueue_assets');

add_action('wp_enqueue_scripts', function () {
    wp_dequeue_style('storefront-style');
    wp_deregister_style('storefront-style');
    wp_dequeue_style('storefront-woocommerce-style');
    wp_deregister_style('storefront-woocommerce-style');
}, 25);
