<?php
$best_sellers = wc_get_products([
    'limit'    => 3,
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
        <div class="bestsellers__grid grid grid--3">
            <?php foreach ($best_sellers as $index => $product) :
                $product_type = $product->get_type();
                $is_variable  = $product_type === 'variable';

                $variations_data = [];
                $default_variation_id = 0;
                $display_price = '';
                $can_add_to_cart = false;
                $card_variation_class = '';

                if ($is_variable) {
                    $available = $product->get_available_variations();
                    $attrs     = $product->get_variation_attributes();
                    $size_attr = !empty($attrs) ? key($attrs) : '';

                    $min_price = PHP_FLOAT_MAX;

                    foreach ($available as $variation) {
                        if (!$variation['is_purchasable'] || !$variation['is_in_stock']) {
                            continue;
                        }

                        $var_id = (int) $variation['variation_id'];
                        $price  = (float) $variation['display_price'];

                        $size_label = '';
                        foreach ($variation['attributes'] as $attr_key => $attr_value) {
                            if ($attr_value) {
                                $taxonomy = str_replace('attribute_', '', $attr_key);
                                $term = get_term_by('slug', $attr_value, $taxonomy);
                                $size_label = $term ? $term->name : $attr_value;
                                break;
                            }
                        }

                        $variations_data[] = [
                            'id'         => $var_id,
                            'price'      => $price,
                            'price_text' => wp_strip_all_tags(wc_price($price)),
                            'size'       => $size_label,
                        ];

                        if ($price < $min_price) {
                            $min_price = $price;
                            $default_variation_id = $var_id;
                        }
                    }

                    if (!empty($variations_data)) {
                        $display_price   = wc_price($min_price);
                        $can_add_to_cart = true;
                        $card_variation_class = ' card--variable';
                    }
                } else {
                    $display_price   = wc_price($product->get_price());
                    $can_add_to_cart = $product->is_purchasable() && $product->is_in_stock();
                }
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
                            <?php if ($is_variable) : ?>
                                À partir de <?php echo $display_price; ?>
                            <?php else : ?>
                                <?php echo $display_price; ?>
                            <?php endif; ?>
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
