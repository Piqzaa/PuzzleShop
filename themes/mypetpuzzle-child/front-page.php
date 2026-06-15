<?php
get_header();
?>
</div><!-- .col-full -->
</div><!-- #content .site-content -->

<section class="hero section section--surface" data-animate>
    <canvas class="hero__canvas" aria-hidden="true"></canvas>
    <div class="hero__inner">
        <div class="hero__content">
            <p class="hero__pretitle">— Artisan Puzzle Premium</p>
            <h1 class="hero__title">L'art du puzzle, <span class="hero__title-highlight">à l'image</span> de votre animal</h1>
            <p class="hero__subtitle">Importez une photo, on la transforme en puzzle unique. Cadeau sincère, souvenir précieux.</p>
            <div class="hero__actions">
                <a class="btn btn--primary" href="<?php echo esc_url(home_url('/creer-mon-puzzle')); ?>">Créer mon puzzle</a>
                <a class="btn btn--secondary" href="<?php echo esc_url(get_permalink(wc_get_page_id('shop'))); ?>">Voir les modèles animaux</a>
            </div>
            <ul class="hero__trust">
                <li><span class="hero__trust-icon"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg></span>Impression qualité premium</li>
                <li><span class="hero__trust-icon"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="1" y="3" width="15" height="13"/><polygon points="16 8 20 8 23 11 23 16 16 16 16 8"/><circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/></svg></span>Livraison 5–7 jours ouvrés</li>
                <li><span class="hero__trust-icon"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/><polyline points="9 12 11 14 15 10"/></svg></span>Satisfait ou remboursé</li>
                <li><span class="hero__trust-icon"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg></span>Paiement sécurisé</li>
            </ul>
        </div>
        <div class="hero__image">
            <img src="<?php echo esc_url(get_stylesheet_directory_uri() . '/assets/images/hero-puzzle.png'); ?>" alt="Puzzle personnalisé avec photo d'animal">
            <div class="hero__badge">
                <div class="hero__badge-stars">★★★★★</div>
                <span class="hero__badge-text"><strong>Qualité premium</strong><br>Impression HD<br>Fabriqué avec soin<br><svg width="10" height="10" viewBox="0 0 24 24" fill="currentColor" stroke="none"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/></svg></span>
            </div>
        </div>
    </div>
</section>


<section class="gifts section" data-animate>
    <div class="gifts__inner">
        <h2 class="gifts__title">Une idée cadeau qui change des classiques</h2>
        <p class="gifts__intro">Offrir un puzzle personnalisé, c'est offrir un moment suspendu.</p>
        <div class="gifts__cards">
            <div class="gifts__card">
                <div class="gifts__card-image">
                    <img src="<?php echo esc_url(get_stylesheet_directory_uri() . '/assets/images/chien-puzzle.png'); ?>" alt="Puzzle personnalisé avec photo d'animal">
                </div>
                <div class="gifts__card-content">
                    <h3 class="gifts__card-title">Puzzle personnalisé</h3>
                    <p class="gifts__card-text">Sa photo transformée en puzzle. Un cadeau unique qui fait fondre le cœur.</p>
                    <a class="btn btn--primary" href="<?php echo esc_url(home_url('/creer-mon-puzzle')); ?>">Créer mon puzzle</a>
                </div>
            </div>
            <div class="gifts__card">
                <div class="gifts__card-image">
                    <img src="<?php echo esc_url(get_stylesheet_directory_uri() . '/assets/images/chien-chat-puzzle.png'); ?>" alt="Puzzles animaux">
                </div>
                <div class="gifts__card-content">
                    <h3 class="gifts__card-title">Puzzles animaux</h3>
                    <p class="gifts__card-text">Notre collection de races et espèces. Parfait pour un cadeau sans photo.</p>
                    <a class="btn btn--primary" href="<?php echo esc_url(get_permalink(wc_get_page_id('shop'))); ?>">Voir la collection</a>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="steps section section--surface" data-animate>
    <div class="steps__inner">
        <h2 class="steps__title">Comment ça marche</h2>
        <div class="steps__grid">
            <div class="steps__step">
                <div class="steps__circle">
                    <div class="steps__icon">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="3" y="3" width="7" height="7"/>
                            <rect x="14" y="3" width="7" height="7"/>
                            <rect x="3" y="14" width="7" height="7"/>
                            <rect x="14" y="14" width="7" height="7"/>
                        </svg>
                    </div>
                    <span class="steps__number">1</span>
                </div>
                <h3 class="steps__heading">Choisissez votre format</h3>
                <p class="steps__text">120, 252 ou 500 pièces. Le bon défi pour chaque âge.</p>
            </div>
            <div class="steps__step">
                <div class="steps__circle">
                    <div class="steps__icon">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z"/>
                            <circle cx="12" cy="13" r="4"/>
                        </svg>
                    </div>
                    <span class="steps__number">2</span>
                </div>
                <h3 class="steps__heading">Envoyez votre photo</h3>
                <p class="steps__text">Importez votre plus beau cliché, on s'occupe du reste.</p>
            </div>
            <div class="steps__step">
                <div class="steps__circle">
                    <div class="steps__icon">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="1" y="3" width="15" height="13"/>
                            <polygon points="16 8 20 8 23 11 23 16 16 16 16 8"/>
                            <circle cx="5.5" cy="18.5" r="2.5"/>
                            <circle cx="18.5" cy="18.5" r="2.5"/>
                        </svg>
                    </div>
                    <span class="steps__number">3</span>
                </div>
                <h3 class="steps__heading">Recevez votre puzzle</h3>
                <p class="steps__text">Livré chez vous en 48h, prêt à être assemblé avec amour.</p>
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
            <?php foreach ($best_sellers as $index => $product) :
                $display_price = '';
                $cart_product_id = $product->get_id();
                $cart_variation_id = 0;
                $can_add_to_cart = false;

                if ($product->get_type() === 'variable') {
                    $display_price = wc_price($product->get_variation_price('min'));
                    $cheapest_price = PHP_FLOAT_MAX;
                    foreach ($product->get_visible_children() as $variation_id) {
                        $variation = wc_get_product($variation_id);
                        if ($variation && $variation->is_purchasable() && $variation->is_in_stock()) {
                            $v_price = (float) $variation->get_price();
                            if ($v_price < $cheapest_price) {
                                $cheapest_price = $v_price;
                                $cart_variation_id = $variation_id;
                                $can_add_to_cart = true;
                            }
                        }
                    }
                } else {
                    $display_price = wc_price($product->get_price());
                    $can_add_to_cart = $product->is_purchasable() && $product->is_in_stock();
                }
            ?>
                <div class="card card--hover">
                    <?php if ($index === 0) : ?>
                        <span class="badge">Best-seller</span>
                    <?php endif; ?>
                    <a href="<?php echo esc_url($product->get_permalink()); ?>" class="bestsellers__link">
                        <div class="bestsellers__image">
                            <?php echo $product->get_image('medium'); ?>
                        </div>
                        <h3 class="bestsellers__name"><?php echo esc_html($product->get_name()); ?></h3>
                    </a>
                    <div class="bestsellers__footer">
                        <span class="bestsellers__price"><?php echo $display_price; ?></span>
                        <?php if ($can_add_to_cart) : ?>
                        <button class="bestsellers__add-to-cart"
                                data-product-id="<?php echo esc_attr($cart_product_id); ?>"
                                data-variation-id="<?php echo esc_attr($cart_variation_id); ?>">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#c9a84c" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
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

<section class="faq section" id="faq" data-animate>
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
