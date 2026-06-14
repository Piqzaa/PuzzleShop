<?php

function mypetpuzzle_child_enqueue_assets() {
    $theme   = wp_get_theme('mypetpuzzle-child');
    $version = $theme->get('Version');

    wp_enqueue_style(
        'mypetpuzzle-child-fonts',
        'https://fonts.googleapis.com/css2?family=DM+Sans:opsz,wght@9..40,400;9..40,500;9..40,600;9..40,700&family=Playfair+Display:wght@400;500;600;700&display=swap',
        [],
        null
    );

    wp_enqueue_style(
        'mypetpuzzle-child-main',
        get_stylesheet_directory_uri() . '/assets/css/main.css',
        ['storefront-style', 'mypetpuzzle-child-fonts'],
        $version
    );

    wp_enqueue_script(
        'mypetpuzzle-child-header',
        get_stylesheet_directory_uri() . '/assets/js/modules/header.js',
        ['wc-cart-fragments'],
        $version,
        true
    );

    wp_enqueue_script(
        'mypetpuzzle-child-faq',
        get_stylesheet_directory_uri() . '/assets/js/modules/faq.js',
        [],
        $version,
        true
    );

    wp_enqueue_script(
        'mypetpuzzle-child-animations',
        get_stylesheet_directory_uri() . '/assets/js/modules/animations.js',
        [],
        $version,
        true
    );

    wp_enqueue_script(
        'mypetpuzzle-child-cursor',
        get_stylesheet_directory_uri() . '/assets/js/modules/cursor.js',
        [],
        $version,
        true
    );

    wp_enqueue_script(
        'mypetpuzzle-child-puzzle-floating',
        get_stylesheet_directory_uri() . '/assets/js/modules/puzzle-floating.js',
        [],
        $version,
        true
    );

    wp_enqueue_script(
        'mypetpuzzle-child-cookies',
        get_stylesheet_directory_uri() . '/assets/js/modules/cookies.js',
        [],
        $version,
        true
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
                'post_author'  => 1,
                'page_template' => $page[1],
            ));
        }
    }
    update_option('mypetpuzzle_pages_created', true);
}
add_action('after_setup_theme', 'mypetpuzzle_page_templates');

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
});
