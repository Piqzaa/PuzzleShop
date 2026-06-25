<section class="hero section section--surface" data-animate>
    <?php if ('complete' === get_query_var('registration', '')) : ?>
    <div id="registration-notice" style="position:absolute;top:0;left:0;right:0;z-index:100;background:#ede8dc;color:#2c1810;text-align:center;padding:14px 48px 14px 24px;font-size:0.9375rem;border-bottom:1px solid #c9a84c;cursor:pointer;transition:opacity 0.4s ease,transform 0.4s ease;">
        <span style="display:inline-flex;align-items:center;gap:8px;">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#c9a84c" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
            <?php esc_html_e('Un email de confirmation vous a été envoyé. Cliquez sur le lien pour activer votre compte.', 'mypetpuzzle-child'); ?>
        </span>
        <span style="position:absolute;right:12px;top:50%;transform:translateY(-50%);color:#8b6f47;font-size:18px;line-height:1;">&times;</span>
    </div>
    <script>
    (function(){
        var el = document.getElementById('registration-notice');
        if (!el) return;
        var close = function(){
            el.style.opacity = '0';
            el.style.transform = 'translateY(-100%)';
            setTimeout(function(){ el.style.display = 'none'; }, 400);
        };
        el.addEventListener('click', close);
        setTimeout(close, 8000);
    })();
    </script>
    <?php endif; ?>
    <canvas class="hero__canvas" aria-hidden="true"></canvas>
    <div class="hero__inner">
        <div class="hero__content">
            <p class="hero__pretitle">— Artisan Puzzle Premium</p>
            <h1 class="hero__title">L'art du puzzle, <span class="hero__title-highlight">à l'image</span> de votre animal</h1>
            <p class="hero__subtitle">Importez une photo, on la transforme en puzzle unique. Cadeau sincère, souvenir précieux.</p>
            <div class="hero__actions">
                <a class="btn btn--primary" href="<?php echo esc_url(cpz_puzzle_page_url()); ?>">Créer mon puzzle</a>
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
