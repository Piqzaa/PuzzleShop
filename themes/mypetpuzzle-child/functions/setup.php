<?php

defined('ABSPATH') || exit;

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
