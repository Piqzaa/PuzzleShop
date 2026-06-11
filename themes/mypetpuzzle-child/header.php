<?php
defined('ABSPATH') || exit;
?><!doctype html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo('charset'); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="profile" href="http://gmpg.org/xfn/11">
<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>">
<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<?php do_action('storefront_before_site'); ?>
<div id="page" class="hfeed site">
    <?php do_action('storefront_before_header'); ?>

    <header class="header" id="header">
        <div class="header__inner">
            <a href="<?php echo esc_url(home_url('/')); ?>" class="header__logo" rel="home">
                <span class="header__logo--accent">MyPet</span><span class="header__logo--base">Puzzle</span>
            </a>

            <button class="header__hamburger" id="header-hamburger" type="button" aria-label="Menu" aria-expanded="false">
                <span class="header__hamburger-line"></span>
                <span class="header__hamburger-line"></span>
                <span class="header__hamburger-line"></span>
            </button>

            <nav class="header__nav" id="header-nav" role="navigation" aria-label="<?php esc_attr_e('Navigation principale', 'mypetpuzzle-child'); ?>">
                <?php
                wp_nav_menu(array(
                    'theme_location' => 'mypetpuzzle-primary',
                    'container'      => 'ul',
                    'menu_class'     => 'header__nav-list',
                    'fallback_cb'    => '__return_false',
                ));
                ?>

                <ul class="header__nav-list header__nav-list--actions">
                    <?php if (is_user_logged_in()) : ?>
                        <li><a href="<?php echo esc_url(get_permalink(get_option('woocommerce_myaccount_page_id'))); ?>" class="header__nav-link">Mon compte</a></li>
                        <li><a href="<?php echo esc_url(wp_logout_url(home_url())); ?>" class="header__nav-link">Déconnexion</a></li>
                    <?php else : ?>
                        <li><a href="<?php echo esc_url(get_permalink(get_option('woocommerce_myaccount_page_id'))); ?>" class="header__nav-link">Connexion</a></li>
                    <?php endif; ?>
                </ul>
            </nav>

            <div class="header__actions">
                <?php if (is_user_logged_in()) : ?>
                    <a href="<?php echo esc_url(get_permalink(get_option('woocommerce_myaccount_page_id'))); ?>" class="header__account-link" aria-label="Mon compte">
                        <svg width="20" height="20" viewBox="0 0 20 20" fill="none" aria-hidden="true">
                            <path d="M10 10C12.7614 10 15 7.76142 15 5C15 2.23858 12.7614 0 10 0C7.23858 0 5 2.23858 5 5C5 7.76142 7.23858 10 10 10Z" fill="currentColor"/>
                            <path d="M10 12C4.47715 12 0 15.5817 0 20H20C20 15.5817 15.5228 12 10 12Z" fill="currentColor"/>
                        </svg>
                    </a>
                <?php else : ?>
                    <a href="<?php echo esc_url(get_permalink(get_option('woocommerce_myaccount_page_id'))); ?>" class="header__account-link" aria-label="Connexion">
                        <svg width="20" height="20" viewBox="0 0 20 20" fill="none" aria-hidden="true">
                            <path d="M10 10C12.7614 10 15 7.76142 15 5C15 2.23858 12.7614 0 10 0C7.23858 0 5 2.23858 5 5C5 7.76142 7.23858 10 10 10Z" fill="currentColor"/>
                            <path d="M10 12C4.47715 12 0 15.5817 0 20H20C20 15.5817 15.5228 12 10 12Z" fill="currentColor"/>
                        </svg>
                    </a>
                <?php endif; ?>

                <a href="<?php echo esc_url(wc_get_cart_url()); ?>" class="header__cart-link" aria-label="Panier">
                    <svg width="22" height="22" viewBox="0 0 22 22" fill="none" aria-hidden="true">
                        <path d="M6 6H22L20 16H8L6 6Z" fill="currentColor" opacity="0.3"/>
                        <path d="M6 6C6 6 5.5 2 3.5 2H1M6 6L8 16H20L22 6H6Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                        <circle cx="9" cy="20" r="1.5" fill="currentColor"/>
                        <circle cx="19" cy="20" r="1.5" fill="currentColor"/>
                    </svg>
                    <span class="header__cart-count"><?php echo WC()->cart ? WC()->cart->get_cart_contents_count() : 0; ?></span>
                </a>
            </div>
        </div>
    </header>

    <?php do_action('storefront_before_content'); ?>
    <div id="content" class="site-content" tabindex="-1">
        <div class="col-full">
        <?php do_action('storefront_content_top'); ?>
