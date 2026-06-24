<?php
get_header('shop');

$exclude_cats = [];
$uncategorized = get_term_by('slug', 'uncategorized', 'product_cat');
if ($uncategorized) {
    $exclude_cats[] = $uncategorized->term_id;
}

$categories = get_terms([
    'taxonomy'   => 'product_cat',
    'hide_empty' => true,
    'exclude'    => $exclude_cats,
]);
?>
</div><!-- .col-full -->
</div><!-- #content -->

<section class="shop-hero section" data-animate>
    <div class="shop-hero__bg"></div>
    <div class="shop-hero__inner">
        <p class="shop-hero__pretitle">— Boutique</p>
        <h1 class="shop-hero__title">Trouvez le puzzle <span class="shop-hero__title-highlight">parfait</span> pour votre animal</h1>
        <p class="shop-hero__desc">Des modèles uniques pour chaque race et chaque caractère. Sélectionnez votre animal et créez un souvenir inoubliable.</p>
    </div>
</section>

<section class="shop section" data-animate>
    <div class="shop__inner">
        <?php do_action('woocommerce_before_main_content'); ?>

        <?php if (!empty($categories)) : ?>
        <nav class="shop__filters">
            <span class="shop__filter-label">Filtrer :</span>
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

        <div class="shop__toolbar">
            <?php do_action('woocommerce_before_shop_loop'); ?>
        </div>

        <?php if (woocommerce_product_loop()) :
        ?>
            <div class="shop__grid">
                <?php while (have_posts()) : the_post(); ?>
                    <?php wc_get_template_part('content', 'product'); ?>
                <?php endwhile; ?>
            </div>

            <?php woocommerce_pagination(); ?>
        <?php
        else :
            do_action('woocommerce_no_products_found');
        endif;
        ?>

        <?php do_action('woocommerce_after_main_content'); ?>
    </div>
</section>

<div id="content" class="site-content" tabindex="-1">
    <div class="col-full">

<?php
get_footer('shop');
