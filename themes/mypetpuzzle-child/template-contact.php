<?php
/**
 * Template Name: Contact
 */

$contact_message = '';
$contact_error   = '';

if (isset($_POST['submit_contact'])) {
    if (!isset($_POST['contact_nonce']) || !wp_verify_nonce($_POST['contact_nonce'], 'contact_submit')) {
        $contact_error = 'Erreur de sécurité. Veuillez réessayer.';
    } else {
        $name    = sanitize_text_field($_POST['contact-name'] ?? '');
        $email   = sanitize_email($_POST['contact-email'] ?? '');
        $command = sanitize_text_field($_POST['contact-command'] ?? '');
        $subject = sanitize_text_field($_POST['contact-subject'] ?? '');
        $message = sanitize_textarea_field($_POST['contact-message'] ?? '');

        if (empty($name) || empty($email) || empty($subject) || empty($message)) {
            $contact_error = 'Tous les champs obligatoires doivent être remplis.';
        } elseif (!is_email($email)) {
            $contact_error = 'Adresse email invalide.';
        } else {
            $to = get_option('admin_email');
            $email_subject = sprintf('[Contact MyPetPuzzle] %s', $subject);
            $email_body = "Nom : $name\n"
                        . "Email : $email\n"
                        . ($command ? "Commande : $command\n" : '')
                        . "Sujet : $subject\n\n"
                        . "Message :\n$message\n";
            $headers = ['From: ' . $name . ' <' . $email . '>', 'Reply-To: ' . $email];

            if (wp_mail($to, $email_subject, $email_body, $headers)) {
                $contact_message = 'Votre message a bien été envoyé. Nous vous répondrons sous 24h ouvrées.';
            } else {
                $contact_error = 'Erreur lors de l\'envoi. Veuillez réessayer ou nous écrire directement à contact@mypetpuzzle.com.';
            }
        }
    }
}

get_header(); ?>

<div class="contact-page">
    <div class="contact-page__inner">
        <div class="contact-page__content">
            <h1 class="contact-page__title">Contactez-nous</h1>
            <p class="contact-page__intro">Une question, un problème, une idée ? Nous sommes là pour vous. Une vraie personne vous répond sous 24h ouvrées.</p>

            <div class="contact-page__info">
                <div class="contact-page__item">
                    <div class="contact-page__item-icon">
                        <svg viewBox="0 0 24 24" fill="none">
                            <path d="M21 5a2 2 0 00-2-2H5a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2V5z"/>
                            <path d="M21 5l-9 6.5L3 5"/>
                        </svg>
                    </div>
                    <strong>Email</strong>
                    <a href="mailto:contact@mypetpuzzle.com">contact@mypetpuzzle.com</a>
                </div>

                <div class="contact-page__item">
                    <div class="contact-page__item-icon">
                        <svg viewBox="0 0 24 24" fill="none">
                            <path d="M22 16.92v3a2 2 0 01-2.18 2 19.79 19.79 0 01-8.63-3.07 19.5 19.5 0 01-6-6 19.79 19.79 0 01-3.07-8.67A2 2 0 014.11 2h3a2 2 0 012 1.72 12.84 12.84 0 00.7 2.81 2 2 0 01-.45 2.11L8.09 9.91a16 16 0 006 6l1.27-1.27a2 2 0 012.11-.45 12.84 12.84 0 002.81.7A2 2 0 0122 16.92z"/>
                        </svg>
                    </div>
                    <strong>Téléphone</strong>
                    <a href="tel:+33100000000">01 00 00 00 00</a><br>
                    <span class="contact-page__detail">Lun-Ven, 9h-18h</span>
                </div>

                <div class="contact-page__item">
                    <div class="contact-page__item-icon">
                        <svg viewBox="0 0 24 24" fill="none">
                            <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0118 0z"/>
                            <circle cx="12" cy="10" r="3"/>
                        </svg>
                    </div>
                    <strong>Adresse</strong>
                    <address>
                        MyPetPuzzle SAS<br>
                        1 rue de l'Exemple<br>
                        75000 Paris, France
                    </address>
                </div>
            </div>
        </div>

        <div class="contact-page__form-wrapper">
            <h2 class="contact-page__form-title">Envoyez-nous un message</h2>
            <form class="contact-page__form" action="" method="POST">
                <?php wp_nonce_field('contact_submit', 'contact_nonce'); ?>

                <?php if ($contact_message) : ?>
                    <div class="contact-page__success"><?php echo esc_html($contact_message); ?></div>
                <?php endif; ?>
                <?php if ($contact_error) : ?>
                    <div class="contact-page__error"><?php echo esc_html($contact_error); ?></div>
                <?php endif; ?>

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

            <button type="submit" name="submit_contact" class="btn btn--primary contact-page__submit">Envoyer</button>
        </form>
    </div>
    </div>
</div>

<?php get_footer(); ?>
