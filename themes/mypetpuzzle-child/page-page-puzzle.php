<?php
/**
 * Template Name: Page Puzzle Personnalisé
 * Slug: page-puzzle
 *
 * @package mypetpuzzle-child
 */

defined('ABSPATH') || exit;

// ID du produit puzzle personnalisé
define('MYPETPUZZLE_CUSTOM_PRODUCT_ID', 151);

$product = wc_get_product(MYPETPUZZLE_CUSTOM_PRODUCT_ID);

if (!$product || !$product->is_type('variable')) {
    wp_die(__('Produit introuvable.', 'mypetpuzzle-child'));
}

// Récupère les variations disponibles avec prix
$variations = $product->get_available_variations();

get_header();
?>
</div><!-- .col-full -->
</div><!-- #content -->

<main id="custom-puzzle-page" class="cpz-page">

    <!-- ========================
         HERO
    ========================= -->
    <section class="cpz-hero">
        <div class="cpz-hero__inner">
            <span class="cpz-hero__eyebrow">Puzzle personnalisé</span>
            <h1 class="cpz-hero__title">Votre photo,<br>pièce par pièce</h1>
            <p class="cpz-hero__sub">Transformez votre moment préféré en puzzle artisanal. Livré sous 5–7 jours ouvrés.</p>
        </div>
    </section>

    <!-- ========================
         CONTENU PRINCIPAL
    ========================= -->
    <div id="content" class="site-content" tabindex="-1">
        <div class="col-full">

            <div class="cpz-container">

                <!-- ========================
                     STEPPER
                ========================= -->
                <div class="cpz-stepper" aria-label="Étapes">
                    <div class="cpz-stepper__item is-active" data-step="1">
                        <span class="cpz-stepper__num">1</span>
                        <span class="cpz-stepper__label">Votre photo</span>
                    </div>
                    <div class="cpz-stepper__divider" aria-hidden="true"></div>
                    <div class="cpz-stepper__item" data-step="2">
                        <span class="cpz-stepper__num">2</span>
                        <span class="cpz-stepper__label">Aperçu</span>
                    </div>
                    <div class="cpz-stepper__divider" aria-hidden="true"></div>
                    <div class="cpz-stepper__item" data-step="3">
                        <span class="cpz-stepper__num">3</span>
                        <span class="cpz-stepper__label">Format</span>
                    </div>
                    <div class="cpz-stepper__divider" aria-hidden="true"></div>
                    <div class="cpz-stepper__item" data-step="4">
                        <span class="cpz-stepper__num">4</span>
                        <span class="cpz-stepper__label">Panier</span>
                    </div>
                </div>

                <!-- ÉTAPE 1 : Upload -->
                <section class="cpz-step cpz-step--upload is-current" data-step="1" aria-labelledby="step1-title">
                    <h2 id="step1-title" class="cpz-step__title">Choisissez votre photo</h2>
                    <p class="cpz-step__hint">Format JPG ou PNG recommandé · Taille min. 800×600 px pour un rendu optimal</p>

                    <div class="cpz-dropzone" id="cpz-dropzone" role="button" tabindex="0" aria-label="Zone de dépôt de photo">
                        <div class="cpz-dropzone__inner">
                            <div class="cpz-dropzone__icon" aria-hidden="true">
                                <svg width="48" height="48" viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <rect x="6" y="10" width="36" height="28" rx="3" stroke="currentColor" stroke-width="2" fill="none"/>
                                    <circle cx="18" cy="20" r="3.5" stroke="currentColor" stroke-width="1.5" fill="none"/>
                                    <path d="M6 32 L16 22 L24 30 L30 24 L42 34" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                    <path d="M24 6 L24 20 M20 10 L24 6 L28 10" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                            </div>
                            <p class="cpz-dropzone__text">Glissez votre photo ici</p>
                            <span class="cpz-dropzone__or">ou</span>
                            <label class="btn btn--primary" for="cpz-file-input">
                                Choisir une photo
                            </label>
                            <input
                                type="file"
                                id="cpz-file-input"
                                name="puzzle_image"
                                accept="image/jpeg,image/png,image/webp"
                                class="cpz-dropzone__input"
                                aria-label="Choisir une photo depuis votre appareil"
                            >
                        </div>
                        <div class="cpz-dropzone__overlay" aria-hidden="true">
                            <span>Déposez ici</span>
                        </div>
                    </div>

                    <p class="cpz-upload-error" id="cpz-upload-error" role="alert" aria-live="assertive"></p>
                </section>

                <!-- ÉTAPE 2 : Preview -->
                <section class="cpz-step cpz-step--preview" data-step="2" aria-labelledby="step2-title" hidden>
                    <h2 id="step2-title" class="cpz-step__title">Ajustez votre photo</h2>
                    <p class="cpz-step__hint">Déplacez et zoomez pour recadrer l'image au format du puzzle</p>

                    <div class="cpz-preview">
                        <div class="cpz-preview__frame">
                            <img id="cpz-cropper-image" src="" alt="Aperçu avec recadrage">
                        </div>

                        <div class="cpz-preview__actions">
                            <button type="button" class="btn btn--secondary btn--sm" id="cpz-btn-reupload">
                                ← Changer la photo
                            </button>
                            <button type="button" class="btn btn--primary" id="cpz-btn-to-options">
                                Choisir le format →
                            </button>
                        </div>
                    </div>
                </section>

                <!-- ÉTAPE 3 : Format + variations -->
                <section class="cpz-step cpz-step--options" data-step="3" aria-labelledby="step3-title" hidden>
                    <h2 id="step3-title" class="cpz-step__title">Choisissez votre format</h2>
                    <p class="cpz-step__hint">Plus il y a de pièces, plus le défi est grand — et le souvenir précieux</p>

                    <div class="cpz-variants" id="cpz-variants" role="radiogroup" aria-labelledby="step3-title">
                        <?php foreach ($variations as $variation) :
                            $variation_obj = wc_get_product($variation['variation_id']);
                            if (!$variation_obj) continue;

                            $attrs_raw = $variation['attributes'];
                            $attrs_normalized = [];
                            foreach ($attrs_raw as $k => $v) {
                                $attrs_normalized[preg_replace('/^attribute_/', '', $k)] = $v;
                            }

                            $size_label   = '';
                            $pieces_label = '';

                            if (isset($attrs_normalized['size'])) {
                                // Attribut custom combiné : ex. '10" × 8" (120 pcs)'
                                $combined = $attrs_normalized['size'];
                                if (preg_match('/^(.+?)\s*\((\d+)\s*pcs\)$/', $combined, $m)) {
                                    $size_label   = trim($m[1]);
                                    $pieces_label = $m[2] . ' pièces';
                                } else {
                                    $size_label = $combined;
                                }
                            } else {
                                // Attributs taxonomies séparés : pa_format / pa_pieces
                                $size_attr   = $attrs_normalized['pa_format'] ?? '';
                                $pieces_attr = $attrs_normalized['pa_pieces'] ?? '';
                                $size_term   = $size_attr   ? get_term_by('slug', $size_attr,   'pa_format')  : null;
                                $pieces_term = $pieces_attr ? get_term_by('slug', $pieces_attr, 'pa_pieces')  : null;
                                $size_label   = $size_term   ? $size_term->name   : $size_attr;
                                $pieces_label = $pieces_term ? $pieces_term->name : $pieces_attr;
                            }

                            $price      = wc_price($variation_obj->get_price());
                            $var_id     = $variation['variation_id'];
                            $is_first   = $variation === $variations[0];

                            $descs = [
                                '120' => 'Idéal pour commencer · Format compact',
                                '252' => 'Le plus offert · Équilibre parfait',
                                '500' => 'Pour les passionnés · Grand format',
                            ];
                            $pieces_num  = preg_replace('/[^0-9]/', '', $pieces_label);
                            $var_desc    = isset($descs[$pieces_num]) ? $descs[$pieces_num] : '';
                        ?>
                        <label
                            class="cpz-variant<?php echo $is_first ? ' is-selected' : ''; ?>"
                            for="cpz-var-<?php echo esc_attr($var_id); ?>"
                        >
                            <input
                                type="radio"
                                id="cpz-var-<?php echo esc_attr($var_id); ?>"
                                name="variation_id"
                                value="<?php echo esc_attr($var_id); ?>"
                                class="cpz-variant__radio"
                                <?php checked($is_first); ?>
                                data-price="<?php echo esc_attr($variation_obj->get_price()); ?>"
                                data-pieces="<?php echo esc_attr($pieces_label); ?>"
                                data-size="<?php echo esc_attr($size_label); ?>"
                            >
                            <span class="cpz-variant__inner">
                                <span class="cpz-variant__size"><?php echo esc_html($size_label); ?></span>
                                <span class="cpz-variant__pieces"><?php echo esc_html($pieces_label); ?></span>
                                <?php if ($var_desc) : ?>
                                    <span class="cpz-variant__desc"><?php echo esc_html($var_desc); ?></span>
                                <?php endif; ?>
                                <span class="cpz-variant__price"><?php echo wp_kses_post($price); ?></span>
                            </span>
                            <span class="cpz-variant__check" aria-hidden="true">✓</span>
                        </label>
                        <?php endforeach; ?>
                    </div>

                    <div class="cpz-options__actions">
                        <button type="button" class="btn btn--secondary btn--sm" id="cpz-btn-back-preview">
                            ← Aperçu
                        </button>
                        <button type="button" class="btn btn--primary" id="cpz-btn-to-cart">
                            Ajouter au panier
                        </button>
                    </div>

                    <p class="cpz-add-error" id="cpz-add-error" role="alert" aria-live="assertive"></p>
                </section>

                <!-- ÉTAPE 4 : Confirmation -->
                <section class="cpz-step cpz-step--confirm" data-step="4" aria-labelledby="step4-title" hidden>
                    <div class="cpz-confirm">
                        <div class="cpz-confirm__icon" aria-hidden="true">✓</div>
                        <h2 id="step4-title" class="cpz-confirm__title">Ajouté au panier !</h2>
                        <p class="cpz-confirm__text">Votre puzzle personnalisé vous attend.</p>
                        <div class="cpz-confirm__actions">
                            <a href="<?php echo esc_url(wc_get_cart_url()); ?>" class="btn btn--primary">
                                Voir mon panier
                            </a>
                            <button type="button" class="btn btn--secondary" id="cpz-btn-restart">
                                Créer un autre puzzle
                            </button>
                        </div>
                    </div>
                </section>

            </div><!-- .cpz-container -->

        </div><!-- .col-full -->
    </div><!-- #content -->

    <!-- ========================
         REASSURANCE
    ========================= -->
    <section class="cpz-reassurance">
        <div class="cpz-reassurance__inner">
            <div class="cpz-reassurance__item">
                <span class="cpz-reassurance__icon" aria-hidden="true">🔒</span>
                <span>Photo supprimée après livraison</span>
            </div>
            <div class="cpz-reassurance__item">
                <span class="cpz-reassurance__icon" aria-hidden="true">🚚</span>
                <span>Livraison 5–7 jours ouvrés</span>
            </div>
            <div class="cpz-reassurance__item">
                <span class="cpz-reassurance__icon" aria-hidden="true">🧩</span>
                <span>Impression haute définition</span>
            </div>
        </div>
    </section>

</main>

<div id="content" class="site-content" tabindex="-1">
    <div class="col-full">

<?php
wp_enqueue_style(
    'cropperjs',
    'https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.2/cropper.min.css',
    [],
    '1.6.2'
);

wp_enqueue_script(
    'cropperjs',
    'https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.2/cropper.min.js',
    [],
    '1.6.2',
    true
);

wp_enqueue_script(
    'mypetpuzzle-custom-puzzle',
    content_url('plugins/mypetpuzzle-core/custome-puzzle.js'),
    ['cropperjs'],
    '1.0.0',
    true
);

wp_localize_script('mypetpuzzle-custom-puzzle', 'cpzData', [
    'ajaxUrl'   => admin_url('admin-ajax.php'),
    'nonce'     => wp_create_nonce('cpz_upload_nonce'),
    'productId' => MYPETPUZZLE_CUSTOM_PRODUCT_ID,
    'cartUrl'   => wc_get_cart_url(),
]);

get_footer();