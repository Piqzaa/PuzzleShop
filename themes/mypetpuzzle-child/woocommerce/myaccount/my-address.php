<?php
/**
 * My Addresses
 *
 * @see     https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 9.3.0
 */

defined( 'ABSPATH' ) || exit;

$customer_id = get_current_user_id();

if ( ! wc_ship_to_billing_address_only() && wc_shipping_enabled() ) {
	$get_addresses = apply_filters(
		'woocommerce_my_account_get_addresses',
		array(
			'billing'  => __( 'Adresse de facturation', 'mypetpuzzle-child' ),
			'shipping' => __( 'Adresse de livraison', 'mypetpuzzle-child' ),
		),
		$customer_id
	);
} else {
	$get_addresses = apply_filters(
		'woocommerce_my_account_get_addresses',
		array(
			'billing' => __( 'Adresse de facturation', 'mypetpuzzle-child' ),
		),
		$customer_id
	);
}
?>

<p class="myaccount-addresses__desc">
	<?php esc_html_e( 'Les adresses suivantes seront utilisées par défaut sur la page de paiement.', 'mypetpuzzle-child' ); ?>
</p>

<div class="myaccount-addresses">
	<?php foreach ( $get_addresses as $name => $address_title ) :
		$address = wc_get_account_formatted_address( $name );
	?>
		<div class="myaccount-address">
			<header class="myaccount-address__header">
				<h3 class="myaccount-address__title"><?php echo esc_html( $address_title ); ?></h3>
				<a href="<?php echo esc_url( wc_get_endpoint_url( 'edit-address', $name ) ); ?>" class="myaccount-address__edit">
					<?php echo $address ? esc_html__( 'Modifier', 'mypetpuzzle-child' ) : esc_html__( 'Ajouter', 'mypetpuzzle-child' ); ?>
				</a>
			</header>
			<address class="myaccount-address__content">
				<?php
				echo $address ? wp_kses_post( $address ) : esc_html__( 'Vous n\'avez pas encore défini cette adresse.', 'mypetpuzzle-child' );
				do_action( 'woocommerce_my_account_after_my_address', $name );
				?>
			</address>
		</div>
	<?php endforeach; ?>
</div>
