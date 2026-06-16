<?php
/**
 * My Account page
 *
 * @see     https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.5.0
 */

defined( 'ABSPATH' ) || exit;
?>

<div class="myaccount-page">
  <header class="myaccount-page__header">
    <h1 class="myaccount-page__title"><?php esc_html_e( 'Mon compte', 'mypetpuzzle-child' ); ?></h1>
  </header>

  <?php do_action( 'woocommerce_account_navigation' ); ?>

  <div class="myaccount-page__content">
    <?php do_action( 'woocommerce_account_content' ); ?>
  </div>
</div>
