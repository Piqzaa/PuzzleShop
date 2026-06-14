<?php
get_header();
?>
</div><!-- .col-full -->
</div><!-- #content .site-content -->

<section class="hero section section--surface" data-animate>
    <canvas class="hero__canvas" aria-hidden="true"></canvas>
    <div class="hero__inner">
        <div class="hero__content">
            <h1 class="hero__title">Offrez la magie d'un puzzle personnalisé de votre animal</h1>
            <p class="hero__subtitle">Importez une photo, on la transforme en puzzle unique. Cadeau sincère, souvenir précieux.</p>
            <div class="hero__actions">
                <a class="btn btn--primary" href="<?php echo esc_url(home_url('/creer-mon-puzzle')); ?>">Créer mon puzzle</a>
                <a class="btn btn--secondary" href="<?php echo esc_url(get_permalink(wc_get_page_id('shop'))); ?>">Voir les modèles animaux</a>
            </div>
            <ul class="hero__trust">
                <li>Fabriqué en France</li>
                <li>Impression HD</li>
                <li>Livraison rapide</li>
            </ul>
        </div>
        <div class="hero__image">
            <img src="<?php echo esc_url(get_stylesheet_directory_uri() . '/assets/images/hero-puzzle.png'); ?>" alt="Puzzle personnalisé avec photo d'animal">
        </div>
    </div>
</section>

<section class="steps section" data-animate>
    <div class="steps__inner">
        <h2 class="steps__title">Comment ça marche</h2>
        <div class="steps__grid">
            <div class="steps__step">
                <span class="steps__number">1</span>
                <h3 class="steps__heading">Choisissez votre format</h3>
                <p class="steps__text">120, 252 ou 500 pièces. Le bon défi pour chaque âge.</p>
            </div>
            <div class="steps__step">
                <span class="steps__number">2</span>
                <h3 class="steps__heading">Envoyez votre photo</h3>
                <p class="steps__text">Importez votre plus beau cliché, on s'occupe du reste.</p>
            </div>
            <div class="steps__step">
                <span class="steps__number">3</span>
                <h3 class="steps__heading">Recevez votre puzzle</h3>
                <p class="steps__text">Livré chez vous en 48h, prêt à être assemblé avec amour.</p>
            </div>
        </div>
    </div>
</section>

<section class="gifts section section--surface" data-animate>
    <div class="gifts__inner">
        <h2 class="gifts__title">Une idée cadeau qui change des classiques</h2>
        <p class="gifts__intro">Offrir un puzzle personnalisé, c'est offrir un moment suspendu.</p>
        <div class="gifts__grid grid grid--2">
            <div class="gifts__item">
                <h3 class="gifts__item-title">Puzzle personnalisé</h3>
                <p class="gifts__item-text">Sa photo transformée en puzzle. Un cadeau unique qui fait fondre le cœur.</p>
                <a class="gifts__link" href="<?php echo esc_url(home_url('/creer-mon-puzzle')); ?>">Créer →</a>
            </div>
            <div class="gifts__item">
                <h3 class="gifts__item-title">Puzzles animaux</h3>
                <p class="gifts__item-text">Notre collection de races et espèces. Parfait pour un cadeau sans photo.</p>
                <a class="gifts__link" href="<?php echo esc_url(get_permalink(wc_get_page_id('shop'))); ?>">Voir la collection →</a>
            </div>
        </div>
    </div>
</section>

<?php
$best_sellers = wc_get_products([
    'limit'    => 4,
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
        <div class="bestsellers__grid grid grid--4">
            <?php foreach ($best_sellers as $index => $product) : ?>
                <a class="card card--hover" href="<?php echo esc_url($product->get_permalink()); ?>">
                    <?php if ($index === 0) : ?>
                        <span class="badge">Best-seller</span>
                    <?php endif; ?>
                    <div class="bestsellers__image">
                        <?php echo $product->get_image('medium'); ?>
                    </div>
                    <h3 class="bestsellers__name"><?php echo esc_html($product->get_name()); ?></h3>
                    <span class="bestsellers__price"><?php echo $product->get_price_html(); ?></span>
                </a>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<section class="cta-emotion section section--surface" data-animate>
    <div class="cta-emotion__grid">
        <div class="cta-emotion__content">
            <h2 class="cta-emotion__title">Votre photo. Votre puzzle. Vos souvenirs.</h2>
            <p class="cta-emotion__text">Chaque regard sur cette boîte racontera une histoire. La sienne, la vôtre. Offrez bien plus qu'un puzzle : un souvenir à reconstruire, pièce par pièce.</p>
            <a class="btn btn--primary" href="<?php echo esc_url(home_url('/creer-mon-puzzle')); ?>">Créer mon puzzle</a>
        </div>
        <div class="cta-emotion__image">
            <img src="<?php echo esc_url(get_stylesheet_directory_uri() . '/assets/images/puzzle-personnalisé.png'); ?>" alt="Puzzle personnalisé avec photo d'animal" />
        </div>
    </div>
</section>

<section class="comparison section" data-animate>
    <div class="section__inner section__inner--full">
        <h2 class="comparison__title">Avant / Après : la magie opère</h2>
        <p class="comparison__intro">Une photo devient un puzzle. Le résultat est bluffant.</p>
        <div class="comparison__grid grid grid--3">
            <div class="card card--lg card--hover">
                <div class="comparison__pair">
                    <figure class="comparison__figure comparison__figure--before">
                        <figcaption class="comparison__label">Photo originale</figcaption>
                        <div class="comparison__demo"><img src="<?php echo esc_url(get_stylesheet_directory_uri() . '/assets/images/chiwawa-avant.png'); ?>" alt="Chihuahua avant transformation" /></div>
                    </figure>
                    <figure class="comparison__figure comparison__figure--after">
                        <figcaption class="comparison__label">Puzzle assemblé</figcaption>
                        <div class="comparison__demo"><img src="<?php echo esc_url(get_stylesheet_directory_uri() . '/assets/images/chiwawa-apres.png'); ?>" alt="Chihuahua transformé en puzzle" /></div>
                    </figure>
                </div>
                <p class="comparison__caption">Chihuahua — photo transformée en puzzle personnalisé 252 pièces.</p>
            </div>
            <div class="card card--lg card--hover">
                <div class="comparison__pair">
                    <figure class="comparison__figure comparison__figure--before">
                        <figcaption class="comparison__label">Photo originale</figcaption>
                        <div class="comparison__demo"><img src="<?php echo esc_url(get_stylesheet_directory_uri() . '/assets/images/maine-coon-avant.png'); ?>" alt="Maine-coon avant transformation" /></div>
                    </figure>
                    <figure class="comparison__figure comparison__figure--after">
                        <figcaption class="comparison__label">Puzzle assemblé</figcaption>
                        <div class="comparison__demo"><img src="<?php echo esc_url(get_stylesheet_directory_uri() . '/assets/images/maine-coon-apres.png'); ?>" alt="Maine-coon transformé en puzzle" /></div>
                    </figure>
                </div>
                <p class="comparison__caption">Maine-coon reproduit en puzzle 500 pièces.</p>
            </div>
            <div class="card card--lg card--hover">
                <div class="comparison__pair">
                    <figure class="comparison__figure comparison__figure--before">
                        <figcaption class="comparison__label">Photo originale</figcaption>
                        <div class="comparison__demo"><img src="<?php echo esc_url(get_stylesheet_directory_uri() . '/assets/images/canari-avant.png'); ?>" alt="Canarie avant transformation" /></div>
                    </figure>
                    <figure class="comparison__figure comparison__figure--after">
                        <figcaption class="comparison__label">Puzzle assemblé</figcaption>
                        <div class="comparison__demo"><img src="<?php echo esc_url(get_stylesheet_directory_uri() . '/assets/images/canari-apres.png'); ?>" alt="Canarie transformée en puzzle" /></div>
                    </figure>
                </div>
                <p class="comparison__caption">Canari, rendu fidèle en puzzle 252 pièces.</p>
            </div>
        </div>
    </div>
</section>

<section class="savoir-faire section section--surface" data-animate>
    <div class="savoir-faire__inner">
        <h2 class="savoir-faire__title">Fabriqué en France avec amour</h2>
        <blockquote class="savoir-faire__story">
            Dans notre atelier au cœur de la Provence, chaque puzzle est imprimé, découpé et vérifié à la main. Pas de chaîne industrielle, pas de compromis. Juste du beau travail, fait avec passion.
        </blockquote>
        <div class="savoir-faire__pillars grid grid--3">
            <div class="savoir-faire__pillar">
                <h3 class="savoir-faire__pillar-title">Papier premium</h3>
                <p class="savoir-faire__pillar-text">Carton épais 2,2 mm, surface mate anti-reflet. Les pièces s'emboîtent parfaitement.</p>
            </div>
            <div class="savoir-faire__pillar">
                <h3 class="savoir-faire__pillar-title">Encres HD</h3>
                <p class="savoir-faire__pillar-text">Impression par sublimation pour des couleurs éclatantes et une fidélité photo exceptionnelle.</p>
            </div>
            <div class="savoir-faire__pillar">
                <h3 class="savoir-faire__pillar-title">Découpe laser</h3>
                <p class="savoir-faire__pillar-text">Lame calibrée au micron. Chaque pièce est unique, l'assemblage est fluide et précis.</p>
            </div>
        </div>
    </div>
</section>

<section class="reviews section" data-animate>
    <div class="section__inner section__inner--full">
        <h2 class="reviews__title">Ils nous ont fait confiance</h2>
        <div class="reviews__grid grid grid--3">
            <blockquote class="reviews__card">
                <div class="reviews__stars">★★★★★</div>
                <p class="reviews__text">« Offert à ma mère pour Noël, elle a adoré reconstituer le portrait de son chat. Qualité incroyable ! »</p>
                <footer class="reviews__author">
                    <span class="reviews__avatar">SM</span>
                    <cite>Sophie M.</cite>
                </footer>
            </blockquote>
            <blockquote class="reviews__card">
                <div class="reviews__stars">★★★★★</div>
                <p class="reviews__text">« Le rendu photo est magnifique, les couleurs sont parfaitement fidèles. Je recommande les yeux fermés. »</p>
                <footer class="reviews__author">
                    <span class="reviews__avatar">TL</span>
                    <cite>Thomas L.</cite>
                </footer>
            </blockquote>
            <blockquote class="reviews__card">
                <div class="reviews__stars">★★★★★</div>
                <p class="reviews__text">« Idée cadeau parfaite pour les propriétaires d'animaux. Livraison rapide, service client au top. »</p>
                <footer class="reviews__author">
                    <span class="reviews__avatar">CD</span>
                    <cite>Claire D.</cite>
                </footer>
            </blockquote>
        </div>
    </div>
</section>

<section class="whyus section section--surface" data-animate>
    <div class="whyus__inner">
        <h2 class="whyus__title">Pourquoi nous choisir</h2>
        <div class="whyus__grid grid grid--2">
            <div class="whyus__item">
                <span class="whyus__check">✓</span>
                <div class="whyus__content">
                    <strong>100% personnalisé</strong>
                    <span>Chaque puzzle est unique, comme votre animal.</span>
                </div>
            </div>
            <div class="whyus__item">
                <span class="whyus__check">✓</span>
                <div class="whyus__content">
                    <strong>Fabriqué en France</strong>
                    <span>Atelier artisanal en Provence, savoir-faire local.</span>
                </div>
            </div>
            <div class="whyus__item">
                <span class="whyus__check">✓</span>
                <div class="whyus__content">
                    <strong>Papier premium</strong>
                    <span>Carton épais, encres HD, découpe laser de précision.</span>
                </div>
            </div>
            <div class="whyus__item">
                <span class="whyus__check">✓</span>
                <div class="whyus__content">
                    <strong>Satisfait ou remboursé</strong>
                    <span>30 jours pour changer d'avis, sans question.</span>
                </div>
            </div>
            <div class="whyus__item">
                <span class="whyus__check">✓</span>
                <div class="whyus__content">
                    <strong>Livraison rapide</strong>
                    <span>Expédié sous 48h, reçu en 2-3 jours en France.</span>
                </div>
            </div>
            <div class="whyus__item">
                <span class="whyus__check">✓</span>
                <div class="whyus__content">
                    <strong>Service client JOYEUX</strong>
                    <span>Une vraie personne, pas de chatbot. Réponse sous 24h.</span>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="faq section" data-animate>
    <div class="faq__inner">
        <h2 class="faq__title">Questions fréquentes</h2>
        <div class="faq__list">
            <div class="faq__item">
                <button class="faq__question" aria-expanded="false" type="button">
                    <span>Quels formats de puzzle proposez-vous ?</span>
                    <span class="faq__icon" aria-hidden="true"></span>
                </button>
                <div class="faq__answer" hidden>
                    <p>3 tailles : 100 pièces (20×20 cm), 300 pièces (30×40 cm) et 500 pièces (40×50 cm). Le prix varie selon le format.</p>
                </div>
            </div>
            <div class="faq__item">
                <button class="faq__question" aria-expanded="false" type="button">
                    <span>Quelle qualité de photo dois-je envoyer ?</span>
                    <span class="faq__icon" aria-hidden="true"></span>
                </button>
                <div class="faq__answer" hidden>
                    <p>Plus la photo est nette, plus le rendu sera beau. On recommande une photo en plein jour, sans filtre, en JPEG ou PNG (min 1 Mo).</p>
                </div>
            </div>
            <div class="faq__item">
                <button class="faq__question" aria-expanded="false" type="button">
                    <span>Combien de temps pour recevoir mon puzzle ?</span>
                    <span class="faq__icon" aria-hidden="true"></span>
                </button>
                <div class="faq__answer" hidden>
                    <p>2 à 3 jours ouvrés pour la fabrication, puis 48 h de livraison en France métropolitaine.</p>
                </div>
            </div>
            <div class="faq__item">
                <button class="faq__question" aria-expanded="false" type="button">
                    <span>Puis-je offrir un puzzle personnalisé ?</span>
                    <span class="faq__icon" aria-hidden="true"></span>
                </button>
                <div class="faq__answer" hidden>
                    <p>Oui ! Commandez directement pour quelqu'un d'autre. Ajoutez un message dans le panier, on l'inclut dans le colis.</p>
                </div>
            </div>
            <div class="faq__item">
                <button class="faq__question" aria-expanded="false" type="button">
                    <span>Que faire si ma commande arrive abîmée ?</span>
                    <span class="faq__icon" aria-hidden="true"></span>
                </button>
                <div class="faq__answer" hidden>
                    <p>On remplace gratuitement tout article défectueux. Contactez-nous sous 48 h avec une photo, on s'occupe de tout.</p>
                </div>
            </div>
            <div class="faq__item">
                <button class="faq__question" aria-expanded="false" type="button">
                    <span>Le puzzle est-il livré avec l'image de référence ?</span>
                    <span class="faq__icon" aria-hidden="true"></span>
                </button>
                <div class="faq__answer" hidden>
                    <p>Oui, chaque puzzle est livré avec un poster A4 de l'image imprimée pour vous guider.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<div id="content" class="site-content" tabindex="-1">
    <div class="col-full">

<?php
get_footer();
