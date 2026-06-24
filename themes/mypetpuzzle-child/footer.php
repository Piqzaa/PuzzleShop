<?php
defined('ABSPATH') || exit;
?>
	</div><!-- .col-full -->
</div><!-- #content -->

<?php do_action('storefront_before_footer'); ?>

<footer class="footer" role="contentinfo">
	<div class="footer__inner">
		<div class="footer__brand">
			<a href="<?php echo esc_url(home_url('/')); ?>" class="footer__logo" rel="home">
				<span class="footer__logo--accent">MyPet</span><span class="footer__logo--base">Puzzle</span>
			</a>
			<p class="footer__tagline">Transformez la photo de votre animal en puzzle unique. Fabriqué en France avec amour.</p>
			<div class="footer__trust">
				<span class="footer__trust-badge">🇫🇷 Fabriqué en France</span>
				<span class="footer__trust-badge">🔒 Paiement sécurisé</span>
			</div>
		</div>

		<div class="footer__col">
			<h3 class="footer__col-title">Informations légales</h3>
			<ul class="footer__links">
				<li><a href="<?php echo esc_url(home_url('/mentions-legales')); ?>">Mentions légales</a></li>
				<li><a href="<?php echo esc_url(home_url('/cgv')); ?>">CGV</a></li>
				<li><a href="<?php echo esc_url(home_url('/confidentialite')); ?>">Politique de confidentialité</a></li>
				<li><a href="<?php echo esc_url(home_url('/retours')); ?>">Retours & remboursements</a></li>
				<li><a href="<?php echo esc_url(home_url('/confidentialite')); ?>#cookies">Gestion des cookies</a></li>
			</ul>
		</div>

		<div class="footer__col">
			<h3 class="footer__col-title">Aide & Contact</h3>
			<ul class="footer__links">
				<li><a href="<?php echo esc_url(home_url('/#faq')); ?>">FAQ</a></li>
				<li><a href="<?php echo esc_url(home_url('/contact')); ?>">Contact & support</a></li>
				<li><a href="<?php echo esc_url(wc_get_cart_url()); ?>">Suivi de commande</a></li>
			</ul>
			<div class="footer__delivery">
				<strong>Livraison estimée</strong>
				<span>5 à 7 jours ouvrés</span>
			</div>
		</div>

		<div class="footer__col footer__col--payments">
			<h3 class="footer__col-title">Paiements acceptés</h3>
			<div class="footer__payment-icons">
				<svg class="footer__payment-icon" viewBox="0 0 50 32" fill="none" aria-label="CB">
					<rect width="50" height="32" rx="4" fill="#fff" stroke="#ddd" stroke-width="1"/>
					<text x="25" y="20" text-anchor="middle" font-size="10" font-weight="700" fill="#1a1a1a" font-family="Arial">CB</text>
				</svg>
				<svg class="footer__payment-icon" viewBox="0 0 50 32" fill="none" aria-label="Visa">
					<rect width="50" height="32" rx="4" fill="#1a1f71"/>
					<text x="25" y="20" text-anchor="middle" font-size="14" font-weight="700" fill="#fff" font-family="Arial">V</text>
				</svg>
				<svg class="footer__payment-icon" viewBox="0 0 50 32" fill="none" aria-label="Mastercard">
					<rect width="50" height="32" rx="4" fill="#fff" stroke="#ddd" stroke-width="1"/>
					<circle cx="18" cy="16" r="8" fill="#eb001b" opacity="0.8"/>
					<circle cx="32" cy="16" r="8" fill="#f79e1b" opacity="0.8"/>
				</svg>
				<svg class="footer__payment-icon" viewBox="0 0 50 32" fill="none" aria-label="PayPal">
					<rect width="50" height="32" rx="4" fill="#003087"/>
					<text x="25" y="20" text-anchor="middle" font-size="8" font-weight="700" fill="#fff" font-family="Arial">PayPal</text>
				</svg>
			</div>
			<p class="footer__secure">Transactions 100% sécurisées (SSL)</p>
		</div>
	</div>

	<div class="footer__bottom">
		<div class="footer__bottom-inner">
			<p class="footer__copyright">&copy; <?php echo esc_html(date('Y')); ?> MyPetPuzzle. Tous droits réservés.</p>
			<p class="footer__legal-info">
				MyPetPuzzle SAS — SIRET 000 000 000 00000 — 1 rue de l'Exemple, 75000 Paris
			</p>
		</div>
	</div>
</footer>

<?php do_action('storefront_after_footer'); ?>

</div><!-- #page -->

<div class="cookie-banner" id="cookie-banner" role="dialog" aria-modal="true" aria-labelledby="cookie-title" hidden>
    <div class="cookie-banner__inner">
        <p class="cookie-banner__title" id="cookie-title">🍪 On respecte votre vie privée</p>
        <p class="cookie-banner__text">Ce site utilise des cookies pour améliorer votre expérience de navigation, analyser le trafic et vous proposer des offres personnalisées.</p>
        <div class="cookie-banner__actions">
            <button class="btn btn--primary btn--sm" id="cookie-accept" type="button">Tout accepter</button>
            <button class="btn btn--outline btn--sm" id="cookie-refuse" type="button">Tout refuser</button>
            <a href="<?php echo esc_url(home_url('/confidentialite')); ?>" class="cookie-banner__link" id="cookie-manage">En savoir plus</a>
        </div>
    </div>
</div>

<?php wp_footer(); ?>

</body>
</html>
