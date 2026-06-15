<?php
/**
 * Checkout terms and conditions area
 *
 * @package WooCommerce\Templates
 * @version 3.4.0
 */

defined( 'ABSPATH' ) || exit;

if ( apply_filters( 'woocommerce_checkout_show_terms', true ) && function_exists( 'wc_terms_and_conditions_checkbox_enabled' ) ) :
	do_action( 'woocommerce_checkout_before_terms_and_conditions' );
	?>
	<div class="woocommerce-terms-and-conditions-wrapper">
		<?php do_action( 'woocommerce_checkout_terms_and_conditions' ); ?>

		<?php if ( wc_terms_and_conditions_checkbox_enabled() ) : ?>
			<p class="form-row validate-required">
				<label class="woocommerce-form__label woocommerce-form__label-for-checkbox checkbox">
					<input type="checkbox" class="woocommerce-form__input woocommerce-form__input-checkbox input-checkbox" name="terms" <?php checked( apply_filters( 'woocommerce_terms_is_checked_default', isset( $_POST['terms'] ) ), true ); ?> id="terms" />
					<span class="woocommerce-terms-and-conditions-checkbox-text">J&#039;ai lu et j&#039;accepte les <a href="<?php echo esc_url( wc_get_page_permalink( 'terms' ) ); ?>" target="_blank">conditions générales de vente</a></span>&nbsp;<abbr class="required" title="obligatoire">*</abbr>
				</label>
				<input type="hidden" name="terms-field" value="1" />
			</p>
		<?php endif; ?>
	</div>
	<?php
	do_action( 'woocommerce_checkout_after_terms_and_conditions' );
endif;
