<?php
defined('ABSPATH') || exit;

wc_print_notice('Un e-mail de réinitialisation vous a été envoyé.');
?>

<?php do_action('woocommerce_before_lost_password_confirmation_message'); ?>

<div class="myaccount-page myaccount-auth">
  <div class="myaccount-page__content">

    <h2 class="auth-title">E-mail envoyé</h2>

    <p class="auth-description">Un e-mail de réinitialisation de mot de passe a été envoyé à l'adresse associée à votre compte. Vérifiez votre boîte de réception (pensez aussi aux spams).</p>

    <p class="auth-back-link">
      <a href="<?php echo esc_url(get_permalink(get_option('woocommerce_myaccount_page_id'))); ?>">← Retour à la connexion</a>
    </p>

  </div>
</div>

<?php do_action('woocommerce_after_lost_password_confirmation_message'); ?>
