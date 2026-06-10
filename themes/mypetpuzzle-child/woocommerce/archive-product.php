<?php
get_header('shop');

$categories = get_terms([
    'taxonomy'   => 'product_cat',
    'hide_empty' => true,
]);
?>

<section class="shop">
    <header class="shop__header">
        <?php do_action('woocommerce_before_main_content'); ?>
        <?php woocommerce_breadcrumb(); ?>
        <h1 class="shop__title"><?php woocommerce_page_title(); ?></h1>
    </header>

    <?php if (!empty($categories)) : ?>
    <nav class="shop__filters">
        <span class="shop__filter-label">Filtrer par animal :</span>
        <ul class="shop__filter-list">
            <li class="shop__filter-item">
                <a class="shop__filter-link shop__filter-link--active" href="<?php echo esc_url(get_permalink(wc_get_page_id('shop'))); ?>">Tous</a>
            </li>
            <?php foreach ($categories as $category) : ?>
                <li class="shop__filter-item">
                    <a class="shop__filter-link" href="<?php echo esc_url(get_term_link($category)); ?>">
                        <?php echo esc_html($category->name); ?>
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>
    </nav>
    <?php endif; ?>

    <?php
    do_action('woocommerce_before_shop_loop');

    if (woocommerce_product_loop()) :
        woocommerce_product_loop_start();

        if (wc_get_loop_prop('total')) :
            while (have_posts()) :
                the_post();
                wc_get_template_part('content', 'product');
            endwhile;
        endif;

        woocommerce_product_loop_end();
        do_action('woocommerce_after_shop_loop');

    else :
        do_action('woocommerce_no_products_found');
    endif;

    do_action('woocommerce_after_main_content');
    ?>
</section>

<?php get_footer('shop'); ?>
