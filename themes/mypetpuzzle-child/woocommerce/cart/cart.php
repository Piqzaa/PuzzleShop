<?php
defined('ABSPATH') || exit;

do_action('woocommerce_before_cart');

if (WC()->cart->is_empty()) :
    wc_get_template('cart/cart-empty.php');
    do_action('woocommerce_after_cart');
    return;
endif;
?>

<section class="cart-page">
    <header class="cart-page__header">
        <h1 class="cart-page__title">Mon Panier</h1>
        <p class="cart-page__count">
            <?php echo sprintf(_n('%s article', '%s articles', WC()->cart->get_cart_contents_count(), 'mypetpuzzle-child'), WC()->cart->get_cart_contents_count()); ?>
        </p>
    </header>

    <div class="cart-page__layout">
        <div class="cart-page__items">
            <form class="cart-page__form woocommerce-cart-form" action="<?php echo esc_url(wc_get_cart_url()); ?>" method="post">
                <?php do_action('woocommerce_before_cart_contents'); ?>

                <div class="woocommerce-cart-form__contents">
                <?php foreach (WC()->cart->get_cart() as $cart_item_key => $cart_item) :
                    $_product = apply_filters('woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key);
                    $product_id = apply_filters('woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key);

                    if (!$_product || !$_product->exists() || $cart_item['quantity'] < 1 || !apply_filters('woocommerce_cart_item_visible', true, $cart_item, $cart_item_key)) {
                        continue;
                    }

                    $product_permalink = apply_filters('woocommerce_cart_item_permalink', $_product->is_visible() ? $_product->get_permalink($cart_item) : '', $cart_item, $cart_item_key);
                ?>
                    <div class="cart-item cart_item woocommerce-cart-form__cart-item">
                        <div class="cart-item__image">
                            <?php
                            $thumbnail = apply_filters('woocommerce_cart_item_thumbnail', $_product->get_image('thumbnail'), $cart_item, $cart_item_key);
                            if (!$product_permalink) {
                                echo $thumbnail;
                            } else {
                                printf('<a href="%s">%s</a>', esc_url($product_permalink), $thumbnail);
                            }
                            ?>
                        </div>

                        <div class="cart-item__details">
                            <h3 class="cart-item__name">
                                <?php
                                if (!$product_permalink) {
                                    echo wp_kses_post(apply_filters('woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key) . '&nbsp;');
                                } else {
                                    echo wp_kses_post(apply_filters('woocommerce_cart_item_name', sprintf('<a href="%s">%s</a>', esc_url($product_permalink), $_product->get_name()), $cart_item, $cart_item_key));
                                }
                                ?>
                            </h3>

                            <div class="cart-item__meta">
                                <?php echo wc_get_formatted_cart_item_data($cart_item); ?>
                            </div>

                            <div class="cart-item__price">
                                <?php echo apply_filters('woocommerce_cart_item_price', WC()->cart->get_product_price($_product), $cart_item, $cart_item_key); ?>
                            </div>
                        </div>

                        <div class="cart-item__quantity" data-title="<?php esc_attr_e('Quantity', 'woocommerce'); ?>">
                            <label class="cart-item__qty-label" for="qty-<?php echo esc_attr($cart_item_key); ?>">Qté</label>
                            <?php
                            if ($_product->is_sold_individually()) {
                                $product_quantity = sprintf('1 <input type="hidden" name="cart[%s][qty]" value="1" />', $cart_item_key);
                            } else {
                                $product_quantity = woocommerce_quantity_input(
                                    array(
                                        'input_name'   => "cart[{$cart_item_key}][qty]",
                                        'input_value'  => $cart_item['quantity'],
                                        'max_value'    => $_product->get_max_purchase_quantity(),
                                        'min_value'    => 1,
                                        'product_name' => $_product->get_name(),
                                    ),
                                    $_product,
                                    false
                                );
                            }
                            echo apply_filters('woocommerce_cart_item_quantity', $product_quantity, $cart_item_key, $cart_item);
                            ?>
                        </div>

                        <div class="cart-item__subtotal" data-title="<?php esc_attr_e('Subtotal', 'woocommerce'); ?>">
                            <span class="cart-item__subtotal-label">Sous-total</span>
                            <span class="cart-item__subtotal-value">
                                <?php echo apply_filters('woocommerce_cart_item_subtotal', WC()->cart->get_product_subtotal($_product, $cart_item['quantity']), $cart_item, $cart_item_key); ?>
                            </span>
                        </div>

                        <div class="cart-item__remove product-remove">
                            <?php
                            echo apply_filters(
                                'woocommerce_cart_item_remove_link',
                                sprintf(
                                    '<a href="%s" class="remove cart-item__remove-btn" aria-label="%s" data-product_id="%s" data-product_sku="%s">
                                        <svg width="18" height="18" viewBox="0 0 18 18" fill="none" aria-hidden="true">
                                            <path d="M3 4.5h12M6.75 4.5V3a1.5 1.5 0 011.5-1.5h1.5a1.5 1.5 0 011.5 1.5v1.5M14.25 4.5v10.5a1.5 1.5 0 01-1.5 1.5H5.25a1.5 1.5 0 01-1.5-1.5V4.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                            <path d="M7.5 8.25v4.5M10.5 8.25v4.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                                        </svg>
                                        <span class="cart-item__remove-text">Supprimer</span>
                                    </a>',
                                    esc_url(wc_get_cart_remove_url($cart_item_key)),
                                    esc_attr(sprintf(__('Remove %s from cart', 'woocommerce'), wp_strip_all_tags($_product->get_name()))),
                                    esc_attr($product_id),
                                    esc_attr($_product->get_sku())
                                ),
                                $cart_item_key
                            );
                            ?>
                        </div>
                    </div>
                <?php endforeach; ?>

                <?php do_action('woocommerce_cart_contents'); ?>
                </div>

                <div class="cart-page__actions">
                    <?php if (wc_coupons_enabled()) : ?>
                        <div class="cart-page__coupon">
                            <label for="coupon_code" class="screen-reader-text">Code promo</label>
                            <input type="text" name="coupon_code" class="cart-page__coupon-input" id="coupon_code" value="" placeholder="Code promo" />
                            <button type="submit" class="btn btn--secondary" name="apply_coupon" value="Appliquer">Appliquer</button>
                            <?php do_action('woocommerce_cart_coupon'); ?>
                        </div>
                    <?php endif; ?>

                    <button type="submit" class="btn btn--secondary cart-page__update-btn" name="update_cart" value="Mettre à jour">
                        Mettre à jour
                    </button>

                    <?php do_action('woocommerce_cart_actions'); ?>
                    <?php wp_nonce_field('woocommerce-cart', 'woocommerce-cart-nonce'); ?>
                </div>

                <?php do_action('woocommerce_after_cart_contents'); ?>
            </form>

            <?php do_action('woocommerce_before_cart_collaterals'); ?>
        </div>

        <aside class="cart-page__totals">
            <?php woocommerce_cart_totals(); ?>
        </aside>
    </div>

    <?php do_action('woocommerce_after_cart'); ?>
</section>
