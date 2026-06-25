<?php
defined('ABSPATH') || exit;

do_action('woocommerce_before_lost_password_form');
?>

<div class="myaccount-page myaccount-auth">
  <div class="myaccount-page__content">

    <h2 class="auth-title">Mot de passe oublié</h2>

    <form method="post" class="woocommerce-ResetPassword lost_reset_password">

      <p class="auth-description">Saisissez votre e-mail ou identifiant pour recevoir un lien de réinitialisation.</p>

      <p class="woocommerce-form-row woocommerce-form-row--first form-row form-row-first">
        <label for="user_login">E-mail ou identifiant <span class="required" aria-hidden="true">*</span></label>
        <input class="woocommerce-Input woocommerce-Input--text input-text" type="text" name="user_login" id="user_login" autocomplete="username" required aria-required="true" />
      </p>

      <div class="clear"></div>

      <?php do_action('woocommerce_lostpassword_form'); ?>

      <p class="woocommerce-form-row form-row">
        <input type="hidden" name="wc_reset_password" value="true" />
        <button type="submit" class="woocommerce-Button button<?php echo esc_attr(wc_wp_theme_get_element_class_name('button') ? ' ' . wc_wp_theme_get_element_class_name('button') : ''); ?>" value="Réinitialiser">Réinitialiser</button>
      </p>

      <?php wp_nonce_field('lost_password', 'woocommerce-lost-password-nonce'); ?>

    </form>

    <p class="auth-back-link">
      <a href="<?php echo esc_url(get_permalink(get_option('woocommerce_myaccount_page_id'))); ?>">← Retour à la connexion</a>
    </p>

  </div>
</div>

<?php
do_action('woocommerce_after_lost_password_form');
