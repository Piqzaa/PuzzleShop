<?php
/**
 * Template Name: Créer mon puzzle
 */

get_header(); ?>

<section class="puzzle-builder">
    <h1 class="puzzle-builder__title">Créez votre puzzle personnalisé</h1>

    <div class="puzzle-builder__stepper">
        <div class="puzzle-builder__step puzzle-builder__step--active">
            <span class="puzzle-builder__step-number">1</span>
            <span class="puzzle-builder__step-label">Photo</span>
        </div>
        <div class="puzzle-builder__step">
            <span class="puzzle-builder__step-number">2</span>
            <span class="puzzle-builder__step-label">Taille</span>
        </div>
        <div class="puzzle-builder__step">
            <span class="puzzle-builder__step-number">3</span>
            <span class="puzzle-builder__step-label">Commande</span>
        </div>
    </div>

    <div class="puzzle-builder__step-content puzzle-builder__step-content--active" data-step="1">
        <div class="upload upload--custom">
            <div class="upload__dropzone">
                <p class="upload__hint">Glissez-déposez votre photo ici ou cliquez pour parcourir</p>
                <p class="upload__formats">JPG, PNG ou WebP — 5 Mo maximum</p>
                <input type="file" accept="image/jpeg,image/png,image/webp" hidden>
                <img class="upload__preview" src="" alt="" style="display:none;">
                <p class="upload__error"></p>
            </div>
        </div>
    </div>

    <div class="puzzle-builder__step-content" data-step="2">
        <h2 class="puzzle-builder__size-title">Choisissez la taille de votre puzzle</h2>
        <div class="puzzle-builder__sizes">
            <label class="puzzle-builder__size-option">
                <input type="radio" name="puzzle_size" value="small" data-price="19.99">
                <span class="puzzle-builder__size-card">
                    <span class="puzzle-builder__size-name">Petit</span>
                    <span class="puzzle-builder__size-dims">20 x 20 cm</span>
                    <span class="puzzle-builder__size-pieces">100 pièces</span>
                    <span class="puzzle-builder__size-price">19,99 €</span>
                </span>
            </label>
            <label class="puzzle-builder__size-option">
                <input type="radio" name="puzzle_size" value="medium" data-price="29.99">
                <span class="puzzle-builder__size-card">
                    <span class="puzzle-builder__size-name">Moyen</span>
                    <span class="puzzle-builder__size-dims">30 x 40 cm</span>
                    <span class="puzzle-builder__size-pieces">300 pièces</span>
                    <span class="puzzle-builder__size-price">29,99 €</span>
                </span>
            </label>
            <label class="puzzle-builder__size-option">
                <input type="radio" name="puzzle_size" value="large" data-price="39.99">
                <span class="puzzle-builder__size-card">
                    <span class="puzzle-builder__size-name">Grand</span>
                    <span class="puzzle-builder__size-dims">50 x 40 cm</span>
                    <span class="puzzle-builder__size-pieces">500 pièces</span>
                    <span class="puzzle-builder__size-price">39,99 €</span>
                </span>
            </label>
            <label class="puzzle-builder__size-option">
                <input type="radio" name="puzzle_size" value="xl" data-price="59.99">
                <span class="puzzle-builder__size-card">
                    <span class="puzzle-builder__size-name">XL</span>
                    <span class="puzzle-builder__size-dims">60 x 80 cm</span>
                    <span class="puzzle-builder__size-pieces">1000 pièces</span>
                    <span class="puzzle-builder__size-price">59,99 €</span>
                </span>
            </label>
        </div>
        <p class="puzzle-builder__price-display">Total : <strong>—</strong></p>
    </div>

    <div class="puzzle-builder__step-content" data-step="3">
        <div class="puzzle-builder__preview">
            <h3>Récapitulatif de votre commande</h3>
            <img class="puzzle-builder__preview-img" src="" alt="Aperçu du puzzle">
            <p class="puzzle-builder__preview-size"></p>
            <p class="puzzle-builder__preview-price"></p>
            <a class="puzzle-builder__cta" href="#">Ajouter au panier</a>
        </div>
    </div>

    <div class="puzzle-builder__nav">
        <button class="puzzle-builder__nav-btn puzzle-builder__nav-btn--prev" disabled>Précédent</button>
        <button class="puzzle-builder__nav-btn puzzle-builder__nav-btn--next">Suivant</button>
    </div>
</section>

<?php get_footer(); ?>
