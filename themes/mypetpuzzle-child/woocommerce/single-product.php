<?php get_header('shop'); ?>

<?php
/**
 * Hook: woocommerce_before_single_product.
 *
 * @hooked woocommerce_output_all_notices - 10
 */
do_action('woocommerce_before_single_product');
?>

<?php while (have_posts()) : the_post(); ?>

<?php global $product; ?>

<article id="product-<?php the_ID(); ?>" <?php wc_product_class('product-detail', $product); ?>>
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

<?php
/**
 * Hook: woocommerce_after_single_product.
 */
do_action('woocommerce_after_single_product');
?>

<?php get_footer('shop'); ?>
