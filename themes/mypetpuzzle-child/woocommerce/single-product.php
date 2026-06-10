<?php get_header('shop'); ?>

<?php while (have_posts()) : the_post(); ?>

<article class="product-detail">
    <div class="product-detail__gallery">
        <?php do_action('woocommerce_before_single_product_summary'); ?>
    </div>

    <div class="product-detail__summary">
        <?php do_action('woocommerce_single_product_summary'); ?>
    </div>

    <div class="product-detail__description">
        <?php do_action('woocommerce_after_single_product_summary'); ?>
    </div>
</article>

<?php endwhile; ?>

<?php get_footer('shop'); ?>
