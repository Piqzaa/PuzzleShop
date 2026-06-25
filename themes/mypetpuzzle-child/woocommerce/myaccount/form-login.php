<?php
defined('ABSPATH') || exit;

do_action('woocommerce_before_customer_login_form');

$has_registration = 'yes' === get_option('woocommerce_enable_myaccount_registration');
$register_requested = isset($_GET['inscription']) || isset($_POST['register']) || isset($_POST['register_section']);
$force_register = !empty($GLOBALS['mypetpuzzle_force_register']);

$show_register_section = $has_registration || $register_requested || $force_register;
$show_register = $show_register_section && ($register_requested || $force_register);

$login_url = wc_get_page_permalink('myaccount');
$register_url = add_query_arg('inscription', '1', $login_url);
?>

<div class="myaccount-page myaccount-auth">
  <div class="myaccount-page__content">

    <?php if ($show_register) : ?>

      <!-- ===== REGISTER ===== -->

      <h2 class="auth-title">Inscription</h2>
      <p class="auth-description">Créez votre compte pour finaliser vos achats plus rapidement.</p>

      <form method="post" class="woocommerce-form woocommerce-form-register register" <?php do_action('woocommerce_register_form_tag'); ?>>

        <?php do_action('woocommerce_register_form_start'); ?>

        <input type="hidden" name="register_section" value="1" />

        <p class="form-row form-row-first">
          <label for="reg_billing_first_name">Prénom <span class="required">*</span></label>
          <input type="text" class="woocommerce-Input woocommerce-Input--text input-text" name="billing_first_name" id="reg_billing_first_name" value="<?php echo (!empty($_POST['billing_first_name'])) ? esc_attr(wp_unslash($_POST['billing_first_name'])) : ''; ?>" required />
        </p>

        <p class="form-row form-row-last">
          <label for="reg_billing_last_name">Nom <span class="required">*</span></label>
          <input type="text" class="woocommerce-Input woocommerce-Input--text input-text" name="billing_last_name" id="reg_billing_last_name" value="<?php echo (!empty($_POST['billing_last_name'])) ? esc_attr(wp_unslash($_POST['billing_last_name'])) : ''; ?>" required />
        </p>

        <div class="clear"></div>

        <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
          <label for="reg_email">Adresse e-mail <span class="required">*</span></label>
          <input type="email" class="woocommerce-Input woocommerce-Input--text input-text" name="email" id="reg_email" value="<?php echo (!empty($_POST['email'])) ? esc_attr(wp_unslash($_POST['email'])) : ''; ?>" required />
        </p>

        <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
          <label for="reg_billing_phone">Téléphone</label>
          <input type="tel" class="woocommerce-Input woocommerce-Input--text input-text" name="billing_phone" id="reg_billing_phone" value="<?php echo (!empty($_POST['billing_phone'])) ? esc_attr(wp_unslash($_POST['billing_phone'])) : ''; ?>" />
        </p>

        <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
          <label for="reg_billing_address_1">Adresse</label>
          <input type="text" class="woocommerce-Input woocommerce-Input--text input-text" name="billing_address_1" id="reg_billing_address_1" value="<?php echo (!empty($_POST['billing_address_1'])) ? esc_attr(wp_unslash($_POST['billing_address_1'])) : ''; ?>" placeholder="Numéro et nom de rue" />
        </p>

        <p class="form-row form-row-first">
          <label for="reg_billing_city">Ville</label>
          <input type="text" class="woocommerce-Input woocommerce-Input--text input-text" name="billing_city" id="reg_billing_city" value="<?php echo (!empty($_POST['billing_city'])) ? esc_attr(wp_unslash($_POST['billing_city'])) : ''; ?>" />
        </p>

        <p class="form-row form-row-last">
          <label for="reg_billing_postcode">Code postal</label>
          <input type="text" class="woocommerce-Input woocommerce-Input--text input-text" name="billing_postcode" id="reg_billing_postcode" value="<?php echo (!empty($_POST['billing_postcode'])) ? esc_attr(wp_unslash($_POST['billing_postcode'])) : ''; ?>" />
        </p>

        <div class="clear"></div>

        <?php if ('no' === get_option('woocommerce_registration_generate_username')) : ?>
          <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
            <label for="reg_username">Identifiant <span class="required">*</span></label>
            <input type="text" class="woocommerce-Input woocommerce-Input--text input-text" name="username" id="reg_username" value="<?php echo (!empty($_POST['username'])) ? esc_attr(wp_unslash($_POST['username'])) : ''; ?>" required />
          </p>
        <?php endif; ?>

        <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
          <label for="reg_password">Mot de passe <span class="required">*</span></label>
          <input type="password" class="woocommerce-Input woocommerce-Input--text input-text" name="password" id="reg_password" autocomplete="new-password" required />
        </p>

        <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
          <label for="reg_password2">Confirmer le mot de passe <span class="required">*</span></label>
          <input type="password" class="woocommerce-Input woocommerce-Input--text input-text" name="password2" id="reg_password2" autocomplete="new-password" required />
        </p>

        <?php do_action('woocommerce_register_form'); ?>

        <p class="woocommerce-form-row form-row">
          <?php wp_nonce_field('woocommerce-register', 'woocommerce-register-nonce'); ?>
          <button type="submit" class="woocommerce-Button woocommerce-button button woocommerce-form-register__submit" name="register" value="Créer mon compte">Créer mon compte</button>
        </p>

        <?php do_action('woocommerce_register_form_end'); ?>
      </form>

      <p class="auth-switch">
        Déjà un compte ?
        <a href="<?php echo esc_url($login_url); ?>" class="auth-link">Connectez-vous ←</a>
      </p>

    <?php else : ?>

      <!-- ===== LOGIN ===== -->

      <?php if (!$show_register_section) : ?>
        <h2 class="auth-title">Connexion</h2>
      <?php endif; ?>

      <form class="woocommerce-form woocommerce-form-login login" method="post" action="<?php echo esc_url(wc_get_page_permalink('myaccount')); ?>" novalidate>

        <?php do_action('woocommerce_login_form_start'); ?>

        <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
          <label for="username">E-mail ou identifiant <span class="required" aria-hidden="true">*</span></label>
          <input type="text" class="woocommerce-Input woocommerce-Input--text input-text" name="username" id="username" autocomplete="username" value="<?php echo (!empty($_POST['username']) && is_string($_POST['username'])) ? esc_attr(wp_unslash($_POST['username'])) : ''; ?>" required aria-required="true" />
        </p>

        <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
          <label for="password">Mot de passe <span class="required" aria-hidden="true">*</span></label>
          <input class="woocommerce-Input woocommerce-Input--text input-text" type="password" name="password" id="password" autocomplete="current-password" required aria-required="true" />
        </p>

        <?php do_action('woocommerce_login_form'); ?>

        <p class="woocommerce-form-row form-row">
          <label class="woocommerce-form__label woocommerce-form__label-for-checkbox woocommerce-form-login__rememberme">
            <input class="woocommerce-form__input woocommerce-form__input-checkbox" name="rememberme" type="checkbox" id="rememberme" value="forever" />
            <span>Se souvenir de moi</span>
          </label>
        </p>

        <p class="woocommerce-form-row form-row">
          <?php wp_nonce_field('woocommerce-login', 'woocommerce-login-nonce'); ?>
          <button type="submit" class="woocommerce-button button woocommerce-form-login__submit<?php echo esc_attr(wc_wp_theme_get_element_class_name('button') ? ' ' . wc_wp_theme_get_element_class_name('button') : ''); ?>" name="login" value="Se connecter">Se connecter</button>
        </p>

        <p class="woocommerce-LostPassword lost_password">
          <a href="<?php echo esc_url(wp_lostpassword_url()); ?>">Mot de passe oublié ?</a>
        </p>

        <?php do_action('woocommerce_login_form_end'); ?>
      </form>

      <p class="auth-switch">
        Pas encore de compte ?
        <a href="<?php echo esc_url($register_url); ?>" class="auth-link">S'inscrire →</a>
      </p>

    <?php endif; ?>

  </div>
</div>

<?php do_action('woocommerce_after_customer_login_form'); ?>
