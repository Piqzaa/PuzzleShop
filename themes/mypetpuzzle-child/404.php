<?php
defined('ABSPATH') || exit;

get_header(); ?>

<div class="error404-page">
	<div class="error404-page__inner">
		<h1 class="error404-page__code">404</h1>
		<p class="error404-page__message">Oups, cette page n'existe pas.</p>
		<p class="error404-page__text">Le lien que vous avez suivi est peut-être cassé, ou la page a été supprimée.</p>
		<div class="error404-page__actions">
			<a href="<?php echo esc_url(home_url('/')); ?>" class="btn btn--primary">Retour à l'accueil</a>
			<a href="<?php echo esc_url(get_permalink(wc_get_page_id('shop'))); ?>" class="btn btn--secondary">Voir la boutique</a>
		</div>
	</div>
</div>

<?php
get_footer();
