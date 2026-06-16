<?php
/**
 * My Account navigation
 *
 * @see     https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 9.3.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

do_action( 'woocommerce_before_account_navigation' );

$menu_items = wc_get_account_menu_items();
unset( $menu_items['customer-logout'], $menu_items['downloads'] );

$icons = array(
	'dashboard'      => '&#x1F3E0;',
	'orders'         => '&#x1F4E6;',
	'edit-address'   => '&#x1F4CD;',
	'payment-methods' => '&#x1F4B3;',
	'edit-account'   => '&#x1F464;',
);

$bottom_endpoints = array( 'dashboard', 'orders', 'edit-address', 'payment-methods', 'edit-account' );
?>

<!-- Desktop navigation (tabs) -->
<nav class="myaccount-nav" aria-label="<?php esc_html_e( 'Account pages', 'woocommerce' ); ?>">
  <div class="myaccount-nav__inner">
    <ul class="myaccount-nav__list" role="list">
      <?php foreach ( $menu_items as $endpoint => $label ) : ?>
        <li class="myaccount-nav__item <?php echo wc_get_account_menu_item_classes( $endpoint ); ?>">
          <a href="<?php echo esc_url( wc_get_account_endpoint_url( $endpoint ) ); ?>"
             class="myaccount-nav__link"
             <?php echo wc_is_current_account_menu_item( $endpoint ) ? 'aria-current="page"' : ''; ?>>
            <span class="myaccount-nav__icon" aria-hidden="true"><?php echo $icons[ $endpoint ] ?? ''; ?></span>
            <span class="myaccount-nav__label"><?php echo esc_html( $label ); ?></span>
          </a>
        </li>
      <?php endforeach; ?>
    </ul>
  </div>
</nav>

<!-- Mobile bottom navigation -->
<nav class="myaccount-bottom-nav" aria-label="<?php esc_html_e( 'Account pages', 'woocommerce' ); ?>">
  <?php foreach ( $menu_items as $endpoint => $label ) :
    if ( ! in_array( $endpoint, $bottom_endpoints, true ) ) {
      continue;
    }
  ?>
    <a href="<?php echo esc_url( wc_get_account_endpoint_url( $endpoint ) ); ?>"
       class="myaccount-bottom-nav__link <?php echo wc_is_current_account_menu_item( $endpoint ) ? 'is-active' : ''; ?>">
      <span class="myaccount-bottom-nav__icon" aria-hidden="true"><?php echo $icons[ $endpoint ] ?? ''; ?></span>
      <span class="myaccount-bottom-nav__label"><?php echo esc_html( $label ); ?></span>
    </a>
  <?php endforeach; ?>
</nav>

<?php do_action( 'woocommerce_after_account_navigation' ); ?>
