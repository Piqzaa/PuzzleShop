<?php
$best_sellers = wc_get_products([
    'limit'    => 3,
    'status'   => 'publish',
    'exclude'  => [MYPETPUZZLE_CUSTOM_PRODUCT_ID],
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
        <div class="bestsellers__grid grid grid--3">
            <?php foreach ($best_sellers as $index => $product) :
                $product_data = cpz_get_product_variations_data($product);
                $display_price = $product_data['display_price'];
                $can_add_to_cart = $product_data['can_add_to_cart'];
                $card_variation_class = $product_data['is_variable'] && !empty($product_data['variations']) ? ' card--variable' : '';
                $default_variation_id = $product_data['default_variation_id'];
                $variations_data = $product_data['variations'];
                $is_variable = $product_data['is_variable'];
            ?>
                <div class="card card--hover<?php echo esc_attr($card_variation_class); ?>">
                    <?php if ($index === 0) : ?>
                        <span class="badge">Best-seller</span>
                    <?php endif; ?>

                    <a href="<?php echo esc_url($product->get_permalink()); ?>" class="card__link">
                        <div class="card__image">
                            <?php echo $product->get_image('medium'); ?>
                        </div>
                        <h3 class="card__title"><?php echo esc_html($product->get_name()); ?></h3>
                    </a>

                    <?php if ($is_variable && !empty($variations_data)) : ?>
                    <div class="card__size-wrap">
                        <select class="card__size-select">
                            <?php foreach ($variations_data as $v) : ?>
                                <option value="<?php echo esc_attr($v['id']); ?>"
                                        data-price="<?php echo esc_attr($v['price_text']); ?>"
                                        <?php selected($v['id'], $default_variation_id); ?>>
                                    <?php echo esc_html($v['size']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <?php endif; ?>

                    <div class="card__footer">
                        <span class="card__price">
                            <?php echo $display_price; ?>
                        </span>

                        <?php if ($can_add_to_cart) : ?>
                            <button class="card__add-to-cart"
                                    data-product-id="<?php echo esc_attr($product->get_id()); ?>"
                                    data-variation-id="<?php echo esc_attr($default_variation_id); ?>">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
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
