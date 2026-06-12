<?php get_header(); ?>
</div><!-- fermeture .col-full -->
</div><!-- fermeture #content .site-content -->

<section class="hero">
    <div class="hero__inner">
        <div class="hero__content">
            <h1 class="hero__title">Transformez la photo de votre animal en puzzle unique</h1>
            <p class="hero__subtitle">Un cadeau personnalisé, plein d'émotion, à offrir ou à s'offrir.</p>
            <a class="hero__cta" href="<?php echo esc_url(get_permalink(wc_get_page_id('shop'))); ?>">
                Créer mon puzzle
            </a>
            <ul class="hero__trust">
                <li>Livraison sous 48h</li>
                <li>Satisfait ou remboursé</li>
                <li>Fabriqué en France</li>
            </ul>
        </div>
        <div class="hero__image">
            <img src="<?php echo esc_url(get_stylesheet_directory_uri() . '/assets/images/hero-puzzle.png'); ?>" alt="Puzzle personnalisé avec photo d'animal">
        </div>
    </div>
</section>

<section class="how-it-works">
    <h2 class="how-it-works__title">Comment ça marche</h2>
    <div class="how-it-works__grid">
        <div class="how-it-works__step">
            <span class="how-it-works__number">1</span>
            <h3 class="how-it-works__title-step">Choisissez votre format</h3>
            <p class="how-it-works__text">Sélectionnez la taille et le nombre de pièces de votre puzzle.</p>
        </div>
        <div class="how-it-works__step">
            <span class="how-it-works__number">2</span>
            <h3 class="how-it-works__title-step">Envoyez votre photo</h3>
            <p class="how-it-works__text">Importez la photo de votre animal, on s'occupe du reste.</p>
        </div>
        <div class="how-it-works__step">
            <span class="how-it-works__number">3</span>
            <h3 class="how-it-works__title-step">Recevez votre puzzle</h3>
            <p class="how-it-works__text">Imprimé et découpé avec soin, livré chez vous sous 48h.</p>
        </div>
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
            <div class="features__icon">&#127467;&#127479;</div>
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
        <?php foreach ($featured_products as $index => $product) : ?>
            <a class="products-showcase__card" href="<?php echo esc_url($product->get_permalink()); ?>">
                <?php if ($index === 0) : ?>
                    <span class="products-showcase__badge">Best-seller</span>
                <?php endif; ?>
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
            <div class="testimonials__stars">★★★★★</div>
            <p class="testimonials__text">« Offert à ma mère pour Noël, elle a adoré reconstituer le portrait de son chat. Qualité incroyable ! »</p>
            <span class="testimonials__author">— Sophie M.</span>
        </div>
        <div class="testimonials__card">
            <div class="testimonials__stars">★★★★★</div>
            <p class="testimonials__text">« Le rendu photo est magnifique, les couleurs sont fidèles. Je recommande les yeux fermés. »</p>
            <span class="testimonials__author">— Thomas L.</span>
        </div>
        <div class="testimonials__card">
            <div class="testimonials__stars">★★★★★</div>
            <p class="testimonials__text">« Idée cadeau parfaite pour les propriétaires d'animaux. La livraison a été très rapide. »</p>
            <span class="testimonials__author">— Claire D.</span>
        </div>
    </div>
</section>

<div id="content" class="site-content" tabindex="-1">
    <div class="col-full">

<?php get_footer(); ?>