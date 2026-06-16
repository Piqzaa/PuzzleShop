<?php
/**
 * View Order
 *
 * @see     https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 10.6.0
 */

defined( 'ABSPATH' ) || exit;

$notes = $order->get_customer_order_notes();
?>
<div class="myaccount-order-view">
  <a href="<?php echo esc_url( wc_get_endpoint_url( 'orders' ) ); ?>" class="myaccount-order-view__back">
    &#x2190; <?php esc_html_e( 'Retour aux commandes', 'mypetpuzzle-child' ); ?>
  </a>

  <p class="myaccount-order-view__status">
    <?php
    echo wp_kses_post(
      apply_filters(
        'woocommerce_order_details_status',
        sprintf(
          esc_html__( 'Commande #%1$s passée le %2$s — statut : %3$s', 'mypetpuzzle-child' ),
          '<mark class="order-number">' . $order->get_order_number() . '</mark>',
          '<mark class="order-date">' . wc_format_datetime( $order->get_date_created() ) . '</mark>',
          '<mark class="order-status">' . wc_get_order_status_name( $order->get_status() ) . '</mark>'
        ),
        $order
      )
    );
    ?>
  </p>

  <?php if ( $notes ) : ?>
    <h2><?php esc_html_e( 'Suivi de commande', 'mypetpuzzle-child' ); ?></h2>
    <ol class="woocommerce-OrderUpdates commentlist notes">
      <?php foreach ( $notes as $note ) : ?>
      <li class="woocommerce-OrderUpdate comment note">
        <div class="woocommerce-OrderUpdate-inner comment_container">
          <div class="woocommerce-OrderUpdate-text comment-text">
            <p class="woocommerce-OrderUpdate-meta meta"><?php echo date_i18n( esc_html__( 'l jS \o\f F Y, h:ia', 'woocommerce' ), strtotime( $note->comment_date ) ); ?></p>
            <div class="woocommerce-OrderUpdate-description description">
              <?php echo wp_kses_post( wpautop( wptexturize( $note->comment_content ) ) ); ?>
            </div>
            <div class="clear"></div>
          </div>
          <div class="clear"></div>
        </div>
      </li>
      <?php endforeach; ?>
    </ol>
  <?php endif; ?>

  <?php do_action( 'woocommerce_view_order', $order_id ); ?>
</div>
