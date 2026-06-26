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

                $badge_html = ($index === 0) ? '<span class="badge">Best-seller</span>' : '';
                $card_class_attr = 'class="card card--hover' . esc_attr($card_variation_class) . '"';

                get_template_part('template-parts/product-card', null, compact(
                    'product', 'display_price', 'can_add_to_cart', 'default_variation_id',
                    'variations_data', 'is_variable', 'badge_html', 'card_class_attr'
                ));
            endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>
