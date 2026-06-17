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
