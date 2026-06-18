<?php

defined('ABSPATH') || exit;

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

/**
 * Remove default price range from single product summary
 * (price is shown in the variation area when a variation is selected)
 */
add_action('wp', function () {
    if (is_product()) {
        remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_price', 10);
    }
});

/**
 * Premium Trust Badges below Add to Cart on Single Product Page
 */
add_action('woocommerce_single_product_summary', 'mypetpuzzle_single_product_trust_badges', 35);
function mypetpuzzle_single_product_trust_badges() {
    ?>
    <div class="product-trust-badges">
        <div class="product-trust-badges__title">
            <span>Garanties de notre atelier</span>
        </div>
        <div class="product-trust-badges__grid">
            <div class="product-trust-badge">
                <svg class="product-trust-badge__icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="10"></circle>
                    <path d="m9 12 2 2 4-4"></path>
                </svg>
                <span class="product-trust-badge__text">Fabriqué en France</span>
            </div>
            <div class="product-trust-badge">
                <svg class="product-trust-badge__icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                    <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                </svg>
                <span class="product-trust-badge__text">Paiement 100% sécurisé</span>
            </div>
            <div class="product-trust-badge">
                <svg class="product-trust-badge__icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <rect x="1" y="3" width="15" height="13"></rect>
                    <polygon points="16 8 20 8 23 11 23 16 16 16 16 8"></polygon>
                    <circle cx="5.5" cy="18.5" r="2.5"></circle>
                    <circle cx="18.5" cy="18.5" r="2.5"></circle>
                </svg>
                <span class="product-trust-badge__text">Livraison offerte dès 49€</span>
            </div>
            <div class="product-trust-badge">
                <svg class="product-trust-badge__icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"></path>
                </svg>
                <span class="product-trust-badge__text">Service client premium 7j/7</span>
            </div>
        </div>
    </div>
    <?php
}

/**
 * Sticky Add to Cart Mobile on Single Product Page
 */
add_action('wp_footer', 'mypetpuzzle_sticky_add_to_cart_mobile');
function mypetpuzzle_sticky_add_to_cart_mobile() {
    if (!is_product()) {
        return;
    }
    global $product;
    if (!$product) {
        return;
    }
    
    $image = $product->get_image('thumbnail');
    $title = $product->get_name();
    $price = $product->get_price_html();
    ?>
    <div class="sticky-cart-mobile">
        <div class="sticky-cart-mobile__container">
            <div class="sticky-cart-mobile__info">
                <div class="sticky-cart-mobile__thumb"><?php echo $image; ?></div>
                <div class="sticky-cart-mobile__meta">
                    <span class="sticky-cart-mobile__title"><?php echo esc_html($title); ?></span>
                    <span class="sticky-cart-mobile__price"><?php echo $price; ?></span>
                </div>
            </div>
            <button class="btn btn--primary btn--sm sticky-cart-mobile__btn">
                Ajouter
            </button>
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var stickyCart = document.querySelector('.sticky-cart-mobile');
            var addToCartBtn = document.querySelector('.single_add_to_cart_button');
            
            if (!stickyCart || !addToCartBtn) return;
            
            var observer = new IntersectionObserver(function(entries) {
                if (!entries[0].isIntersecting) {
                    stickyCart.classList.add('is-visible');
                } else {
                    stickyCart.classList.remove('is-visible');
                }
            }, { threshold: 0 });
            
            observer.observe(addToCartBtn);
            
            document.querySelector('.sticky-cart-mobile__btn').addEventListener('click', function(e) {
                e.preventDefault();
                addToCartBtn.scrollIntoView({ behavior: 'smooth', block: 'center' });
            });
        });
    </script>
    <?php
}

/**
 * Variation Image Gallery Filter - shows only images for selected variation
 */
add_action('wp_footer', 'mypetpuzzle_variation_gallery_filter');
function mypetpuzzle_variation_gallery_filter() {
    if (!is_product()) {
        return;
    }
    global $product;
    if (!$product || !$product->is_type('variable')) {
        return;
    }
    
    $variations = $product->get_available_variations();
    $variation_images = [];
    foreach ($variations as $variation) {
        if (!empty($variation['image_id'])) {
            $variation_images[$variation['variation_id']] = $variation['image_id'];
        }
    }
    if (empty($variation_images)) {
        return;
    }
    
    wp_enqueue_script('jquery');
    ?>
    <script>
    (function($) {
        var variationImages = <?php echo wp_json_encode($variation_images); ?>;
        var $gallery = $('.woocommerce-product-gallery');
        var $thumbs = $gallery.find('.flex-control-thumbs li');
        var $mainImage = $gallery.find('.flex-viewport img, .woocommerce-product-gallery__image img').first();
        
        // Store original thumb images for reset
        var originalThumbs = $thumbs.map(function() {
            return $(this).html();
        }).get();
        
        // Function to filter thumbnails for a variation
        function filterThumbnails(variationId) {
            var imageId = variationImages[variationId];
            if (!imageId) {
                // No specific image for this variation, show all
                $thumbs.each(function(i) {
                    $(this).html(originalThumbs[i]).show();
                });
                return;
            }
            
            // Find which thumb corresponds to this variation's image
            $thumbs.each(function(i) {
                var $img = $(this).find('img');
                var src = $img.attr('src') || '';
                var $a = $(this).find('a');
                var href = $a.attr('href') || '';
                
                // Check if this thumb matches the variation image
                // The image URL will contain the image ID
                var matches = src.indexOf(imageId) !== -1 || href.indexOf(imageId) !== -1;
                
                if (matches) {
                    $(this).show();
                } else {
                    $(this).hide();
                }
            });
        }
        
        // Listen for WooCommerce variation form events
        $('.variations_form').on('found_variation', function(event, variation) {
            if (variation.variation_id) {
                filterThumbnails(variation.variation_id);
            }
        }).on('reset_data', function() {
            // Show all thumbnails when reset
            $thumbs.each(function(i) {
                $(this).html(originalThumbs[i]).show();
            });
        });
        
    })(jQuery);
    </script>
    <?php
}
