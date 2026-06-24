<?php
/**
 * Cpz_Order_Meta
 *
 * Envoie les commandes WooCommerce contenant des puzzles vers l'API Printify :
 *  1. Upload de la photo vers Printify
 *  2. Création de la commande Printify
 *  3. Stocke l'ID Printify en meta de commande
 *
 * @package MyPetPuzzle_Core
 */

defined( 'ABSPATH' ) || exit;

class Cpz_Order_Meta {

	const API_BASE      = 'https://api.printify.com/v1/';
	const SHOP_ID       = 27917431;
	const BLUEPRINT_ID  = 616;
	const PRINT_PROVIDER_ID = 1;

	const VARIANT_MAP = [
		153 => 72663,
		154 => 72664,
		155 => 72665,
	];

	const RATIO_MAP = [
		153 => 1.25,
		154 => 1.2727272727273,
		155 => 1.25,
	];

	private string $api_token = '';

	// ─────────────────────────────────────────────────────────────

	public function __construct() {
		$this->api_token = defined( 'MYPETPUZZLE_PRINTIFY_TOKEN' )
			? MYPETPUZZLE_PRINTIFY_TOKEN
			: '';

		add_action( 'init', [ $this, 'register_failed_status' ] );
		add_filter( 'wc_order_statuses', [ $this, 'add_printify_status_to_list' ] );
		add_action( 'woocommerce_order_status_processing', [ $this, 'send_to_printify' ] );
	}

	// ═════════════════════════════════════════════════════════════
	//  STATUT PERSONNALISÉ
	// ═════════════════════════════════════════════════════════════

	public function register_failed_status(): void {
		register_post_status( 'wc-printify-error', [
			'label'                     => 'Erreur Printify',
			'public'                    => true,
			'exclude_from_search'       => false,
			'show_in_admin_all_list'    => true,
			'show_in_admin_status_list' => true,
			'label_count'               => _n_noop(
				'Erreur Printify (%s)',
				'Erreurs Printify (%s)',
				'mypetpuzzle-core'
			),
		] );
	}

	public function add_printify_status_to_list( array $statuses ): array {
		$statuses['wc-printify-error'] = 'Erreur Printify';
		return $statuses;
	}

	// ═════════════════════════════════════════════════════════════
	//  HOOK PRINCIPAL
	// ═════════════════════════════════════════════════════════════

	public function send_to_printify( int $order_id ): void {
		if ( empty( $this->api_token ) ) {
			$this->log_error( 'Printify API token manquant. Définissez MYPETPUZZLE_PRINTIFY_TOKEN dans wp-config.php' );
			return;
		}

		$order = wc_get_order( $order_id );
		if ( ! $order ) {
			return;
		}

		$printify_items = [];

		foreach ( $order->get_items() as $item_id => $item ) {
			if ( ! $item instanceof WC_Order_Item_Product ) {
				continue;
			}

			$image_path   = wp_normalize_path( $item->get_meta( '_cpz_image_path' ) );
			$variation_id = $item->get_variation_id();

			if ( empty( $image_path ) || ! isset( self::VARIANT_MAP[ $variation_id ] ) ) {
				continue;
			}

			$this->log_info( "Upload image vers Printify pour item $item_id" );

			$uploaded_url = $this->upload_image_to_printify( $image_path, $variation_id );
			if ( ! $uploaded_url ) {
				$order->update_status( 'wc-printify-error', 'Upload image Printify échoué.' );
				$order->save();
				return;
			}

			$printify_items[] = [
				'blueprint_id'     => (string) self::BLUEPRINT_ID,
				'variant_id'       => self::VARIANT_MAP[ $variation_id ],
				'quantity'         => $item->get_quantity(),
				'print_provider_id' => self::PRINT_PROVIDER_ID,
				'sku'              => $item->get_product() ? $item->get_product()->get_sku() : 'var-' . $variation_id,
				'print_areas'      => [
					'front' => [
						[
							'src'   => $uploaded_url,
							'scale' => 1,
							'x'     => 0.5,
							'y'     => 0.5,
							'angle' => 0,
						],
					],
				],
			];
		}

		if ( empty( $printify_items ) ) {
			$this->log_info( "Commande $order_id : aucun item puzzle, ignoré." );
			return;
		}

		$this->log_info( "Création commande Printify pour la commande $order_id" );

		$result = $this->create_printify_order( $order, $printify_items );
		if ( ! $result ) {
			$order->update_status( 'wc-printify-error', 'Création commande Printify échouée.' );
			$order->save();
			return;
		}

		$order->add_meta_data( '_cpz_printify_order_id', $result['id'], true );
		$order->add_meta_data( '_cpz_printify_created_at', $result['created_at'] ?? current_time( 'mysql' ), true );
		$order->save();

		$this->log_info( "Commande Printify créée : {$result['id']}" );
	}

	// ═════════════════════════════════════════════════════════════
	//  UPLOAD IMAGE VERS PRINTIFY
	// ═════════════════════════════════════════════════════════════

	private function upload_image_to_printify( string $file_path, int $variation_id = 0 ): ?string {
		if ( ! file_exists( $file_path ) || ! is_readable( $file_path ) ) {
			$this->log_error( "Fichier introuvable : $file_path" );
			return null;
		}

		$upload_path = $file_path;

		if ( $variation_id && isset( self::RATIO_MAP[ $variation_id ] ) ) {
			$cropped = $this->crop_to_ratio( $file_path, self::RATIO_MAP[ $variation_id ] );
			if ( $cropped ) {
				$upload_path = $cropped;
			}
		}

		$url     = self::API_BASE . 'uploads/images.json';
		$content = file_get_contents( $upload_path );

		if ( false === $content ) {
			$this->log_error( "Impossible de lire le fichier : $upload_path" );
			if ( $upload_path !== $file_path ) @unlink( $upload_path );
			return null;
		}

		$response = wp_remote_post( $url, [
			'headers' => [
				'Authorization' => 'Bearer ' . $this->api_token,
				'Content-Type'  => 'application/json',
			],
			'body'    => wp_json_encode( [
				'file_name' => basename( $file_path ),
				'contents'  => base64_encode( $content ),
			] ),
			'timeout' => 60,
		] );

		if ( $upload_path !== $file_path ) @unlink( $upload_path );

		if ( is_wp_error( $response ) ) {
			$this->log_error( 'Upload Printify WP_Error : ' . $response->get_error_message() );
			return null;
		}

		$code         = wp_remote_retrieve_response_code( $response );
		$body_response = wp_remote_retrieve_body( $response );

		if ( $code < 200 || $code >= 300 ) {
			$this->log_error( "Upload Printify HTTP $code : $body_response" );
			return null;
		}

		$data = json_decode( $body_response, true );

		return $data['preview_url'] ?? $data['url'] ?? null;
	}

	private function crop_to_ratio( string $file_path, float $target_ratio ): ?string {
		if ( ! function_exists( 'wp_get_image_editor' ) ) {
			return null;
		}

		$editor = wp_get_image_editor( $file_path );
		if ( is_wp_error( $editor ) ) {
			$this->log_error( 'Échec chargement wp_get_image_editor : ' . $editor->get_error_message() );
			return null;
		}

		$size         = $editor->get_size();
		$current_ratio = $size['width'] / $size['height'];

		if ( abs( $current_ratio - $target_ratio ) < 0.01 ) {
			return null;
		}

		if ( $current_ratio > $target_ratio ) {
			$new_w = round( $size['height'] * $target_ratio );
			$x     = round( ( $size['width'] - $new_w ) / 2 );
			$editor->crop( $x, 0, $new_w, $size['height'] );
		} else {
			$new_h = round( $size['width'] / $target_ratio );
			$y     = round( ( $size['height'] - $new_h ) / 2 );
			$editor->crop( 0, $y, $size['width'], $new_h );
		}

		$upload_dir  = wp_upload_dir();
		$filename    = 'cropped_' . basename( $file_path );
		$dest        = trailingslashit( $upload_dir['basedir'] ) . 'mypetpuzzle-temp/' . $filename;

		$saved = $editor->save( $dest );
		if ( is_wp_error( $saved ) ) {
			$this->log_error( 'Échec sauvegarde crop : ' . $saved->get_error_message() );
			return null;
		}

		return $dest;
	}

	// ═════════════════════════════════════════════════════════════
	//  CRÉATION COMMANDE PRINTIFY
	// ═════════════════════════════════════════════════════════════

	private function create_printify_order( WC_Order $order, array $line_items ): ?array {
		$url     = self::API_BASE . 'shops/' . self::SHOP_ID . '/orders.json';
		$addr    = $order->get_address( 'shipping' );
		$billing = $order->get_address( 'billing' );

		$body_payload = [
			'external_id'              => 'wc_' . $order->get_id(),
			'label'                    => 'Commande WC #' . $order->get_id(),
			'shipping_method'          => apply_filters( 'cpz_printify_shipping_method', 1, $order ),
			'send_shipping_notification' => false,
			'address_to' => [
				'first_name' => $addr['first_name'] ?? $billing['first_name'],
				'last_name'  => $addr['last_name']  ?? $billing['last_name'],
				'address1'   => $addr['address_1']  ?? $billing['address_1'],
				'address2'   => $addr['address_2']  ?? '',
				'city'       => $addr['city']       ?? $billing['city'],
				'province'   => $addr['state']      ?? '',
				'country'    => $addr['country']    ?? $billing['country'],
				'zip'        => $addr['postcode']   ?? $billing['postcode'],
				'phone'      => $billing['phone']   ?? '',
				'email'      => $billing['email']   ?? '',
			],
			'line_items' => $line_items,
		];

		$response = wp_remote_post( $url, [
			'headers' => [
				'Authorization' => 'Bearer ' . $this->api_token,
				'Content-Type'  => 'application/json',
			],
			'body'    => wp_json_encode( $body_payload ),
			'timeout' => 30,
		] );

		if ( is_wp_error( $response ) ) {
			$this->log_error( 'Création commande Printify WP_Error : ' . $response->get_error_message() );
			return null;
		}

		$code = wp_remote_retrieve_response_code( $response );
		$body_response = wp_remote_retrieve_body( $response );

		if ( $code < 200 || $code >= 300 ) {
			$this->log_error( "Création commande Printify HTTP $code : $body_response" );
			return null;
		}

		return json_decode( $body_response, true );
	}

	// ═════════════════════════════════════════════════════════════
	//  LOGGER
	// ═════════════════════════════════════════════════════════════

	private function logger(): WC_Logger_Interface {
		return wc_get_logger();
	}

	private function log_info( string $message ): void {
		$this->logger()->info( $message, [ 'source' => 'cpz-printify' ] );
	}

	private function log_error( string $message ): void {
		$this->logger()->error( $message, [ 'source' => 'cpz-printify' ] );
	}
}
