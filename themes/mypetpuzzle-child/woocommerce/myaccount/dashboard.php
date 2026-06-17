<?php
/**
 * My Account Dashboard
 *
 * @see     https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 4.4.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$allowed_html = array(
	'a' => array(
		'href' => array(),
	),
);

$orders_url      = wc_get_endpoint_url( 'orders' );
$address_url     = wc_get_endpoint_url( 'edit-address' );
$account_url     = wc_get_endpoint_url( 'edit-account' );


$customer_orders = wc_get_orders( array(
	'customer_id' => get_current_user_id(),
	'limit'       => 3,
	'orderby'     => 'date',
	'order'       => 'DESC',
) );

$order_count = count( $customer_orders ) < 3
	? count( $customer_orders )
	: count( wc_get_orders( array(
		'customer_id' => get_current_user_id(),
		'limit'       => -1,
		'return'      => 'ids',
	) ) );
?>

<div class="myaccount-dashboard">
  <div class="myaccount-dashboard__welcome">
    <p class="myaccount-dashboard__greeting">
      <?php
      printf(
        wp_kses( __( 'Bon retour, <strong>%1$s</strong>', 'mypetpuzzle-child' ), array( 'strong' => array() ) ),
        esc_html( $current_user->display_name )
      );
      ?>
    </p>
    <p class="myaccount-dashboard__subtitle">
      <?php esc_html_e( 'Bienvenue dans votre tableau de bord', 'mypetpuzzle-child' ); ?>
    </p>
  </div>

  <div class="myaccount-dashboard__stats">
    <a href="<?php echo esc_url( $orders_url ); ?>" class="myaccount-stat">
      <span class="myaccount-stat__value"><?php echo esc_html( $order_count ); ?></span>
      <span class="myaccount-stat__label"><?php esc_html_e( 'Commandes', 'mypetpuzzle-child' ); ?></span>
    </a>
    <a href="<?php echo esc_url( $address_url ); ?>" class="myaccount-stat">
      <span class="myaccount-stat__icon">&#x1F4CD;</span>
      <span class="myaccount-stat__label"><?php esc_html_e( 'Adresses', 'mypetpuzzle-child' ); ?></span>
    </a>
    <a href="<?php echo esc_url( $account_url ); ?>" class="myaccount-stat">
      <span class="myaccount-stat__icon">&#x1F464;</span>
      <span class="myaccount-stat__label"><?php esc_html_e( 'Profil', 'mypetpuzzle-child' ); ?></span>
    </a>
  </div>

  <?php if ( ! empty( $customer_orders ) ) : ?>
    <section class="myaccount-dashboard__section">
      <h2 class="myaccount-dashboard__section-title">
        <?php esc_html_e( 'Dernières commandes', 'mypetpuzzle-child' ); ?>
        <a href="<?php echo esc_url( $orders_url ); ?>" class="myaccount-dashboard__section-link">
          <?php esc_html_e( 'Voir tout', 'mypetpuzzle-child' ); ?>
        </a>
      </h2>
      <div class="myaccount-dashboard__orders">
        <?php foreach ( $customer_orders as $order ) :
          $status = $order->get_status();
          $status_labels = array(
            'pending'    => 'En attente',
            'processing' => 'En cours',
            'on-hold'    => 'En attente',
            'completed'  => 'Livrée',
            'cancelled'  => 'Annulée',
            'refunded'   => 'Remboursée',
            'failed'     => 'Échouée',
          );
          $status_label = $status_labels[ $status ] ?? $status;
        ?>
          <a href="<?php echo esc_url( $order->get_view_order_url() ); ?>" class="myaccount-order">
            <div class="myaccount-order__info">
              <span class="myaccount-order__id">#<?php echo esc_html( $order->get_order_number() ); ?></span>
              <span class="myaccount-order__date"><?php echo esc_html( wc_format_datetime( $order->get_date_created() ) ); ?></span>
            </div>
            <span class="myaccount-order__total"><?php echo wp_kses_post( $order->get_formatted_order_total() ); ?></span>
            <span class="myaccount-order__status myaccount-order__status--<?php echo esc_attr( $status ); ?>">
              <?php echo esc_html( $status_label ); ?>
            </span>
          </a>
        <?php endforeach; ?>
      </div>
    </section>
  <?php endif; ?>

  <section class="myaccount-dashboard__section">
    <h2 class="myaccount-dashboard__section-title">
      <?php esc_html_e( 'Liens rapides', 'mypetpuzzle-child' ); ?>
    </h2>
    <div class="myaccount-dashboard__quick-links">
      <a href="<?php echo esc_url( $orders_url ); ?>" class="btn btn--secondary">
        &#x1F4E6; <?php esc_html_e( 'Mes commandes', 'mypetpuzzle-child' ); ?>
      </a>
      <a href="<?php echo esc_url( $address_url ); ?>" class="btn btn--secondary">
        &#x1F4CD; <?php esc_html_e( 'Mes adresses', 'mypetpuzzle-child' ); ?>
      </a>
      <a href="<?php echo esc_url( $account_url ); ?>" class="btn btn--secondary">
        &#x2699;&#xFE0F; <?php esc_html_e( 'Paramètres', 'mypetpuzzle-child' ); ?>
      </a>
      <a href="<?php echo esc_url( wc_get_page_permalink( 'shop' ) ); ?>" class="btn btn--primary">
        &#x1F50D; <?php esc_html_e( 'Boutique', 'mypetpuzzle-child' ); ?>
      </a>
    </div>
  </section>
</div>
