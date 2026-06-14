<?php
/**
 * Template Name: Contact
 */

get_header(); ?>

<div class="contact-page">
    <div class="contact-page__inner">
        <div class="contact-page__content">
            <h1 class="contact-page__title">Contactez-nous</h1>
            <p class="contact-page__intro">Une question, un problème, une idée ? Nous sommes là pour vous. Une vraie personne vous répond sous 24h ouvrées.</p>

            <div class="contact-page__info">
                <div class="contact-page__item">
                    <strong>Email</strong>
                    <a href="mailto:contact@mypetpuzzle.com">contact@mypetpuzzle.com</a>
                </div>

                <div class="contact-page__item">
                    <strong>Téléphone</strong>
                    <a href="tel:+33100000000">01 00 00 00 00</a>
                    <span class="contact-page__detail">Lun-Ven, 9h-18h</span>
                </div>

                <div class="contact-page__item">
                    <strong>Adresse</strong>
                    <address>
                        MyPetPuzzle SAS<br>
                        1 rue de l'Exemple<br>
                        75000 Paris, France
                    </address>
                </div>
            </div>
        </div>

        <form class="contact-page__form" action="#" method="POST">
            <div class="contact-page__field">
                <label for="contact-name">Nom *</label>
                <input type="text" id="contact-name" name="contact-name" required>
            </div>

            <div class="contact-page__field">
                <label for="contact-email">Email *</label>
                <input type="email" id="contact-email" name="contact-email" required>
            </div>

            <div class="contact-page__field">
                <label for="contact-command">Numéro de commande (optionnel)</label>
                <input type="text" id="contact-command" name="contact-command">
            </div>

            <div class="contact-page__field">
                <label for="contact-subject">Sujet *</label>
                <select id="contact-subject" name="contact-subject" required>
                    <option value="">Sélectionnez un sujet</option>
                    <option value="commande">Question sur ma commande</option>
                    <option value="produit">Question sur un produit</option>
                    <option value="retour">Retour ou remboursement</option>
                    <option value="livraison">Livraison</option>
                    <option value="autre">Autre</option>
                </select>
            </div>

            <div class="contact-page__field">
                <label for="contact-message">Message *</label>
                <textarea id="contact-message" name="contact-message" rows="6" required></textarea>
            </div>

            <button type="submit" class="btn btn--primary">Envoyer</button>
        </form>
    </div>
</div>

<?php get_footer(); ?>
