<?php
defined('ABSPATH') || exit;

do_action('woocommerce_cart_is_empty');

if (wc_get_page_id('shop') > 0) : ?>
    <div class="cart-page__empty">
        <div class="cart-page__empty-icon">
            <svg width="80" height="80" viewBox="0 0 80 80" fill="none" xmlns="http://www.w3.org/2000/svg">
                <rect x="10" y="25" width="60" height="45" rx="4" stroke="#c9a84c" stroke-width="2.5" fill="#efe5c9"/>
                <path d="M25 25V20C25 11.716 31.716 5 40 5C48.284 5 55 11.716 55 20V25" stroke="#c9a84c" stroke-width="2.5" fill="none"/>
                <circle cx="32" cy="43" r="3" fill="#c9a84c"/>
                <circle cx="48" cy="43" r="3" fill="#c9a84c"/>
                <path d="M34 54C36 56 44 56 46 54" stroke="#c9a84c" stroke-width="2" stroke-linecap="round"/>
            </svg>
        </div>
        <p class="cart-page__empty-text">Votre panier est vide.</p>
        <a href="<?php echo esc_url(apply_filters('woocommerce_return_to_shop_redirect', wc_get_page_permalink('shop'))); ?>" class="btn btn--primary">
            Découvrir nos puzzles
        </a>
    </div>
<?php endif; ?>
