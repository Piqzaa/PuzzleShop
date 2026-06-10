<?php get_header(); ?>

<section class="hero">
    <div class="hero__content">
        <h1 class="hero__title">Transformez la photo de votre animal en puzzle unique</h1>
        <p class="hero__subtitle">Un cadeau personnalisé, plein d'émotion, à offrir ou s'offrir.</p>
        <a class="hero__cta" href="<?php echo esc_url(get_permalink(wc_get_page_id('shop'))); ?>">
            Créer mon puzzle
        </a>
    </div>
</section>

<section class="features">
    <div class="features__grid">
        <div class="features__card">
            <div class="features__icon">&#127912;</div>
            <h3 class="features__title">Qualité premium</h3>
            <p class="features__text">Impression haute définition sur carton épais, découpe précise de chaque pièce.</p>
        </div>
        <div class="features__card">
            <div class="features__icon">&#128666;</div>
            <h3 class="features__title">Livraison rapide</h3>
            <p class="features__text">Expédié sous 48h en France métropolitaine et en Europe.</p>
        </div>
        <div class="features__card">
            <div class="features__icon">&#127470;&#127475;</div>
            <h3 class="features__title">Fait en France</h3>
            <p class="features__text">Conçu et fabriqué dans notre atelier en Provence.</p>
        </div>
    </div>
</section>

<?php
$featured_products = wc_get_products([
    'limit'  => 4,
    'status' => 'publish',
]);
?>

<?php if (!empty($featured_products)) : ?>
<section class="products-showcase">
    <h2 class="products-showcase__title">Nos puzzles animaux</h2>
    <div class="products-showcase__grid">
        <?php foreach ($featured_products as $product) : ?>
            <a class="products-showcase__card" href="<?php echo esc_url($product->get_permalink()); ?>">
                <?php echo $product->get_image('medium'); ?>
                <h3 class="products-showcase__name"><?php echo esc_html($product->get_name()); ?></h3>
                <span class="products-showcase__price"><?php echo $product->get_price_html(); ?></span>
            </a>
        <?php endforeach; ?>
    </div>
</section>
<?php endif; ?>

<section class="custom-cta">
    <div class="custom-cta__content">
        <h2 class="custom-cta__title">Votre propre photo, votre puzzle</h2>
        <p class="custom-cta__text">Importez une photo de votre animal et nous la transformons en puzzle personnalisé.</p>
        <a class="custom-cta__btn" href="<?php echo esc_url(home_url('/creer-mon-puzzle')); ?>">
            Télécharger ma photo
        </a>
    </div>
</section>

<section class="testimonials">
    <h2 class="testimonials__title">Ils nous ont fait confiance</h2>
    <div class="testimonials__grid">
        <div class="testimonials__card">
            <p class="testimonials__text">« Offert à ma mère pour Noël, elle a adoré reconstituer le portrait de son chat. Qualité incroyable ! »</p>
            <span class="testimonials__author">— Sophie M.</span>
        </div>
        <div class="testimonials__card">
            <p class="testimonials__text">« Le rendu photo est magnifique, les couleurs sont fidèles. Je recommande les yeux fermés. »</p>
            <span class="testimonials__author">— Thomas L.</span>
        </div>
        <div class="testimonials__card">
            <p class="testimonials__text">« Idée cadeau parfaite pour les propriétaires d'animaux. La livraison a été très rapide. »</p>
            <span class="testimonials__author">— Claire D.</span>
        </div>
    </div>
</section>

<?php get_footer(); ?>
