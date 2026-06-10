<?php

function mypetpuzzle_child_enqueue_assets() {
    $theme = wp_get_theme('mypetpuzzle-child');

    wp_enqueue_style(
        'mypetpuzzle-child-style',
        get_stylesheet_uri(),
        ['storefront-style'],
        $theme->get('Version')
    );

    wp_enqueue_style(
        'mypetpuzzle-child-main',
        get_stylesheet_directory_uri() . '/assets/css/main.css',
        ['mypetpuzzle-child-style'],
        $theme->get('Version')
    );

}
add_action('wp_enqueue_scripts', 'mypetpuzzle_child_enqueue_assets');

function mypetpuzzle_child_theme_support() {
    add_theme_support('woocommerce');
    add_theme_support('wc-product-gallery-zoom');
    add_theme_support('wc-product-gallery-lightbox');
    add_theme_support('wc-product-gallery-slider');
}
add_action('after_setup_theme', 'mypetpuzzle_child_theme_support');
