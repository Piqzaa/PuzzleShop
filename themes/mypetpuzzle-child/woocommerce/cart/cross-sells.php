<?php
defined('ABSPATH') || exit;

if ($cross_sells) :
    $heading = apply_filters('woocommerce_product_cross_sells_products_heading', __('Vous aimerez aussi&hellip;', 'mypetpuzzle-child'));
?>
    <section class="cross-sells">
        <?php if ($heading) : ?>
            <h2 class="cross-sells__title"><?php echo esc_html($heading); ?></h2>
        <?php endif; ?>

        <div class="cross-sells__grid">
            <?php foreach ($cross_sells as $cross_sell) :
                $post_object = get_post($cross_sell->get_id());
                setup_postdata($GLOBALS['post'] = $post_object);
                wc_get_template_part('content', 'product');
            endforeach; ?>
        </div>
    </section>
<?php
endif;
wp_reset_postdata();
