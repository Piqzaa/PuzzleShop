<?php
/**
 * Carte produit partagée entre la boucle shop et la section best-sellers.
 *
 * Variables via get_template_part($args) :
 *   $product              WC_Product
 *   $display_price        string
 *   $can_add_to_cart      bool
 *   $default_variation_id int|null
 *   $variations_data      array
 *   $is_variable          bool
 *   $badge_html           string (empty = pas de badge)
 *   $card_class_attr      string (attribut class complet, ex: class="card ...")
 */
defined('ABSPATH') || exit;

if (isset($args)) {
    $product              = $args['product'] ?? null;
    $display_price        = $args['display_price'] ?? '';
    $can_add_to_cart      = $args['can_add_to_cart'] ?? false;
    $default_variation_id = $args['default_variation_id'] ?? 0;
    $variations_data      = $args['variations_data'] ?? [];
    $is_variable          = $args['is_variable'] ?? false;
    $badge_html           = $args['badge_html'] ?? '';
    $card_class_attr      = $args['card_class_attr'] ?? '';
}
?>
<div <?php echo $card_class_attr; ?>>
    <?php if (!empty($badge_html)) : ?>
        <?php echo $badge_html; ?>
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
