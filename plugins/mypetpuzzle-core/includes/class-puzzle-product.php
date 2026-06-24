<?php
/**
 * Cpz_Puzzle_Product
 *
 * Gère l'ajout au panier WooCommerce d'un puzzle personnalisé :
 *  - Ajoute la variation avec les meta custom (image path, nom original)
 *  - Affiche les meta dans le récap panier / commande admin
 *  - Nettoie le fichier temp après passage de commande
 *
 * @package MyPetPuzzle_Core
 */

defined( 'ABSPATH' ) || exit;

class Cpz_Puzzle_Product {

	const THUMBS_SUBDIR = 'mypetpuzzle-thumbs';
	const THUMB_SIZE    = 150;

	// ─────────────────────────────────────────────────────────────

	public function __construct() {
		// Affichage des meta dans le panier et le checkout
		add_filter( 'woocommerce_get_item_data',             [ $this, 'display_cart_item_meta' ], 10, 2 );

		// Image dans le panier → vignette dynamique
		add_filter( 'woocommerce_cart_item_thumbnail',       [ $this, 'cart_item_thumbnail' ], 10, 3 );

		// Sauvegarde des meta dans les order items après paiement
		add_action( 'woocommerce_checkout_create_order_line_item', [ $this, 'save_order_item_meta' ], 10, 4 );

		// Nettoyage du fichier temp quand la commande est créée
		add_action( 'woocommerce_checkout_order_created', [ $this, 'schedule_temp_cleanup' ] );
	}

	// ═════════════════════════════════════════════════════════════
	//  ADD TO CART (statique — appelé depuis Upload_Handler)
	// ═════════════════════════════════════════════════════════════

	/**
	 * Ajoute la variation puzzle au panier WooCommerce avec les meta custom.
	 *
	 * @param int    $product_id   ID produit parent
	 * @param int    $variation_id ID variation sélectionnée
	 * @param string $image_path   Chemin absolu de l'image stockée
	 * @return true|WP_Error
	 */
	public static function add_to_cart( int $product_id, int $variation_id, string $image_path ) {
		// Vérifie que le produit et la variation existent
		$product   = wc_get_product( $product_id );
		$variation = wc_get_product( $variation_id );

		if ( ! $product || ! $variation ) {
			return new WP_Error( 'invalid_product', 'Produit ou variation introuvable.' );
		}

		if ( ! $variation->is_in_stock() ) {
			return new WP_Error( 'out_of_stock', 'Ce format n\'est plus disponible.' );
		}

		// Récupère les attributs de la variation pour WC
		$variation_attrs = $variation->get_variation_attributes();

		// Génère une vignette 150×150 pour l'affichage dans le panier
		$thumb_url = self::generate_thumbnail( $image_path );

		// Meta custom attachées à l'item panier
		$cart_item_data = [
			'cpz_image_path'      => $image_path,
			'cpz_image_filename'  => basename( $image_path ),
			'cpz_image_thumb_url' => $thumb_url,
			'cpz_variation_label' => self::get_variation_label( $variation ),
		];

		$cart_item_key = WC()->cart->add_to_cart(
			$product_id,
			1,
			$variation_id,
			$variation_attrs,
			$cart_item_data
		);

		if ( false === $cart_item_key ) {
			return new WP_Error( 'add_to_cart_failed', 'Impossible d\'ajouter le puzzle au panier. Réessayez.' );
		}

		return true;
	}

	// ═════════════════════════════════════════════════════════════
	//  AFFICHAGE PANIER
	// ═════════════════════════════════════════════════════════════

	/**
	 * Affiche le nom du fichier image dans le récap panier / checkout.
	 * On n'affiche PAS le chemin serveur complet pour des raisons de sécurité.
	 *
	 * @param array $item_data  Données affichées dans le panier
	 * @param array $cart_item  Item panier WC
	 * @return array
	 */
	public function display_cart_item_meta( array $item_data, array $cart_item ): array {
		if ( ! empty( $cart_item['cpz_variation_label'] ) ) {
			$item_data[] = [
				'key'     => __( 'Format', 'mypetpuzzle-child' ),
				'value'   => esc_html( $cart_item['cpz_variation_label'] ),
				'display' => '',
			];
		}

		return $item_data;
	}

	// ═════════════════════════════════════════════════════════════
	//  VIGNETTE DYNAMIQUE PANIER
	// ═════════════════════════════════════════════════════════════

	/**
	 * Remplace l'image du produit dans le panier par la photo uploadée.
	 *
	 * @param string $thumbnail HTML de l'image
	 * @param array  $cart_item Item du panier
	 * @param string $cart_item_key
	 * @return string
	 */
	public function cart_item_thumbnail( string $thumbnail, array $cart_item, string $cart_item_key ): string {
		if ( empty( $cart_item['cpz_image_thumb_url'] ) ) {
			return $thumbnail;
		}
		return '<img src="' . esc_url( $cart_item['cpz_image_thumb_url'] )
			. '" alt="Photo puzzle personnalisé"'
			. ' class="attachment-woocommerce_thumbnail size-woocommerce_thumbnail">';
	}

	// ═════════════════════════════════════════════════════════════
	//  SAUVEGARDE META COMMANDE
	// ═════════════════════════════════════════════════════════════

	/**
	 * Copie les meta custom de l'item panier vers l'order item WC.
	 * Ces meta seront utilisées par Cpz_Order_Meta pour appeler Printify.
	 *
	 * @param WC_Order_Item_Product $item
	 * @param string                $cart_item_key
	 * @param array                 $values         Données item panier
	 * @param WC_Order              $order
	 */
	public function save_order_item_meta(
		WC_Order_Item_Product $item,
		string $cart_item_key,
		array $values,
		WC_Order $order
	): void {
		if ( ! empty( $values['cpz_image_path'] ) ) {
			$item->add_meta_data( '_cpz_image_path',     $values['cpz_image_path'],      true );
			$item->add_meta_data( '_cpz_image_filename', $values['cpz_image_filename'],   true );
			$item->add_meta_data( '_cpz_image_thumb_url', $values['cpz_image_thumb_url'], true );
		}

		if ( ! empty( $values['cpz_variation_label'] ) ) {
			$item->add_meta_data( '_cpz_variation_label', $values['cpz_variation_label'], true );
		}
	}

	// ═════════════════════════════════════════════════════════════
	//  NETTOYAGE TEMP APRÈS COMMANDE
	// ═════════════════════════════════════════════════════════════

	/**
	 * Planifie la suppression du fichier temp 48h après la commande.
	 * On ne supprime pas immédiatement pour gérer les éventuels retours/remboursements.
	 *
	 * @param WC_Order $order
	 */
	public function schedule_temp_cleanup( WC_Order $order ): void {
		foreach ( $order->get_items() as $item ) {
			$image_path = $item->get_meta( '_cpz_image_path' );
			if ( $image_path ) {
				// Stocke le chemin dans les meta de la commande pour le cron
				$order->add_meta_data( '_cpz_temp_image_' . $item->get_id(), $image_path, true );
			}
		}
		$order->save();
	}

	// ═════════════════════════════════════════════════════════════
	//  HELPERS
	// ═════════════════════════════════════════════════════════════

	/**
	 * Construit un label lisible pour la variation (ex: "20×16" — 500 pcs").
	 *
	 * @param WC_Product_Variation $variation
	 * @return string
	 */
	private static function get_variation_label( WC_Product_Variation $variation ): string {
		$attrs  = $variation->get_variation_attributes();
		$parts  = [];

		foreach ( $attrs as $tax => $slug ) {
			$taxonomy = str_replace( 'attribute_', '', $tax );
			$term     = get_term_by( 'slug', $slug, $taxonomy );
			if ( $term ) {
				$parts[] = $term->name;
			} else {
				$parts[] = $slug;
			}
		}

		return implode( ' — ', $parts );
	}

	// ═════════════════════════════════════════════════════════════
	//  GÉNÉRATION VIGNETTE
	// ═════════════════════════════════════════════════════════════

	/**
	 * Génère une vignette 150×150 de l'image uploadée et retourne son URL.
	 *
	 * @param string $image_path Chemin absolu de l'image source
	 * @return string URL de la vignette, ou chaîne vide si échec
	 */
	private static function generate_thumbnail( string $image_path ): string {
		if ( ! function_exists( 'wp_get_image_editor' ) ) {
			return '';
		}

		$editor = wp_get_image_editor( $image_path );
		if ( is_wp_error( $editor ) ) {
			return '';
		}

		$editor->resize( self::THUMB_SIZE, self::THUMB_SIZE, true );

		$upload_dir = wp_upload_dir();
		$thumb_dir  = trailingslashit( $upload_dir['basedir'] ) . self::THUMBS_SUBDIR;

		if ( ! wp_mkdir_p( $thumb_dir ) ) {
			return '';
		}

		$thumb_filename = 'thumb_' . basename( $image_path );
		$thumb_path     = $thumb_dir . '/' . $thumb_filename;

		$saved = $editor->save( $thumb_path );
		if ( is_wp_error( $saved ) ) {
			return '';
		}

		return trailingslashit( $upload_dir['baseurl'] ) . self::THUMBS_SUBDIR . '/' . $thumb_filename;
	}
}