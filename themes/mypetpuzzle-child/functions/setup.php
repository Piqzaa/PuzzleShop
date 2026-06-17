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
