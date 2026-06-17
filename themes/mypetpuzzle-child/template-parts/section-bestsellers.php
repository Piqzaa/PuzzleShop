<?php
$best_sellers = wc_get_products([
    'limit'    => 4,
    'status'   => 'publish',
    'meta_key' => 'total_sales',
    'orderby'  => 'meta_value_num',
    'order'    => 'DESC',
]);
?>

<?php if (!empty($best_sellers)) : ?>
<section class="bestsellers section" data-animate>
    <div class="section__inner section__inner--full">
        <h2 class="bestsellers__title">Nos best-sellers animaux</h2>
        <p class="bestsellers__desc">Les coups de cœur de nos clients. Des races et des poses qui cartonnent.</p>
        <div class="bestsellers__grid grid grid--4">
            <?php foreach ($best_sellers as $index => $product) :
                $display_price = '';
                $cart_product_id = $product->get_id();
                $cart_variation_id = 0;
                $can_add_to_cart = false;

                if ($product->get_type() === 'variable') {
                    $display_price = wc_price($product->get_variation_price('min'));
                    $cheapest_price = PHP_FLOAT_MAX;
                    foreach ($product->get_visible_children() as $variation_id) {
                        $variation = wc_get_product($variation_id);
                        if ($variation && $variation->is_purchasable() && $variation->is_in_stock()) {
                            $v_price = (float) $variation->get_price();
                            if ($v_price < $cheapest_price) {
                                $cheapest_price = $v_price;
                                $cart_variation_id = $variation_id;
                                $can_add_to_cart = true;
                            }
                        }
                    }
                } else {
                    $display_price = wc_price($product->get_price());
                    $can_add_to_cart = $product->is_purchasable() && $product->is_in_stock();
                }
            ?>
                <div class="card card--hover">
                    <?php if ($index === 0) : ?>
                        <span class="badge">Best-seller</span>
                    <?php endif; ?>
                    <a href="<?php echo esc_url($product->get_permalink()); ?>" class="bestsellers__link">
                        <div class="bestsellers__image">
                            <?php echo $product->get_image('medium'); ?>
                        </div>
                        <h3 class="bestsellers__name"><?php echo esc_html($product->get_name()); ?></h3>
                    </a>
                    <div class="bestsellers__footer">
                        <span class="bestsellers__price"><?php echo $display_price; ?></span>
                        <?php if ($can_add_to_cart) : ?>
                        <button class="bestsellers__add-to-cart"
                                data-product-id="<?php echo esc_attr($cart_product_id); ?>"
                                data-variation-id="<?php echo esc_attr($cart_variation_id); ?>">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#c9a84c" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="9" cy="21" r="1"></circle>
                                <circle cx="20" cy="21" r="1"></circle>
                                <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path>
                            </svg>
                            Ajouter
                        </button>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>
