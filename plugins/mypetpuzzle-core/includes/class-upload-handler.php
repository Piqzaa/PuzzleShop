<?php
/**
 * Class_Upload_Handler
 *
 * Gère l'action AJAX cpz_upload_and_add_to_cart :
 *  1. Valide et stocke l'image temporairement
 *  2. Délègue l'ajout au panier à Cpz_Puzzle_Product
 *
 * @package MyPetPuzzle_Core
 */

defined( 'ABSPATH' ) || exit;

class Cpz_Upload_Handler {

	/**
	 * Dossier de stockage temporaire des images, relatif à wp-content/uploads/
	 */
	const UPLOAD_SUBDIR = 'mypetpuzzle-temp';

	/**
	 * Taille max autorisée en octets (20 Mo)
	 */
	const MAX_SIZE = 20971520;

	/**
	 * Types MIME acceptés
	 */
	const ALLOWED_MIME = [
		'image/jpeg',
		'image/png',
		'image/webp',
	];

	/**
	 * Durée de rétention des fichiers temp en secondes (48h)
	 */
	const RETENTION = 172800;

	// ─────────────────────────────────────────────────────────────

	public function __construct() {
		// Utilisateurs connectés et visiteurs (non connectés = guests WC)
		add_action( 'wp_ajax_cpz_upload_and_add_to_cart',        [ $this, 'handle' ] );
		add_action( 'wp_ajax_nopriv_cpz_upload_and_add_to_cart', [ $this, 'handle' ] );

		// Nettoyage quotidien des fichiers temporaires expirés
		add_action( 'cpz_cleanup_temp_uploads', [ $this, 'cleanup_temp_files' ] );
		if ( ! wp_next_scheduled( 'cpz_cleanup_temp_uploads' ) ) {
			wp_schedule_event( time(), 'daily', 'cpz_cleanup_temp_uploads' );
		}
	}

	// ═════════════════════════════════════════════════════════════
	//  HANDLER PRINCIPAL
	// ═════════════════════════════════════════════════════════════

	/**
	 * Point d'entrée de l'action AJAX
	 */
	public function handle(): void {
		// 1. Vérification nonce
		if ( ! check_ajax_referer( 'cpz_upload_nonce', 'nonce', false ) ) {
			wp_send_json_error( [ 'message' => 'Session expirée. Rechargez la page et réessayez.' ], 403 );
		}

		// 2. Vérification paramètres
		$product_id   = isset( $_POST['product_id'] )   ? absint( $_POST['product_id'] )   : 0;
		$variation_id = isset( $_POST['variation_id'] ) ? absint( $_POST['variation_id'] ) : 0;

		if ( ! $product_id || ! $variation_id ) {
			wp_send_json_error( [ 'message' => 'Paramètres manquants.' ], 400 );
		}

		// 3. Vérification du fichier uploadé
		if ( empty( $_FILES['puzzle_image'] ) || $_FILES['puzzle_image']['error'] !== UPLOAD_ERR_OK ) {
			$error = $this->file_upload_error_message( $_FILES['puzzle_image']['error'] ?? UPLOAD_ERR_NO_FILE );
			wp_send_json_error( [ 'message' => $error ], 400 );
		}

		$file = $_FILES['puzzle_image'];

		// 4. Validation MIME réelle (pas juste l'extension)
		$finfo     = finfo_open( FILEINFO_MIME_TYPE );
		$real_mime = finfo_file( $finfo, $file['tmp_name'] );
		finfo_close( $finfo );

		if ( ! in_array( $real_mime, self::ALLOWED_MIME, true ) ) {
			wp_send_json_error( [ 'message' => 'Format de fichier non autorisé. Utilisez JPG, PNG ou WebP.' ], 400 );
		}

		// 5. Validation taille
		if ( $file['size'] > self::MAX_SIZE ) {
			$mb = round( $file['size'] / 1048576, 1 );
			wp_send_json_error( [ 'message' => "Fichier trop volumineux ({$mb} Mo). Maximum : 20 Mo." ], 400 );
		}

		// 6. Stockage temporaire
		$stored_path = $this->store_temp_file( $file, $real_mime );
		if ( is_wp_error( $stored_path ) ) {
			wp_send_json_error( [ 'message' => $stored_path->get_error_message() ], 500 );
		}

		// 7. Ajout au panier via Cpz_Puzzle_Product
		$result = Cpz_Puzzle_Product::add_to_cart( $product_id, $variation_id, $stored_path );
		if ( is_wp_error( $result ) ) {
			// Nettoie le fichier temp si l'ajout panier échoue
			@unlink( $stored_path );
			wp_send_json_error( [ 'message' => $result->get_error_message() ], 500 );
		}

		wp_send_json_success( [
			'message'  => 'Puzzle ajouté au panier.',
			'cart_url' => wc_get_cart_url(),
		] );
	}

	// ═════════════════════════════════════════════════════════════
	//  STOCKAGE TEMPORAIRE
	// ═════════════════════════════════════════════════════════════

	/**
	 * Stocke le fichier uploadé dans le dossier temp sécurisé.
	 *
	 * @param array  $file      Entrée $_FILES
	 * @param string $real_mime MIME type vérifié
	 * @return string|WP_Error  Chemin absolu du fichier stocké
	 */
	private function store_temp_file( array $file, string $real_mime ) {
		$upload_dir  = wp_upload_dir();
		$target_dir  = trailingslashit( $upload_dir['basedir'] ) . self::UPLOAD_SUBDIR;

		// Crée le dossier s'il n'existe pas
		if ( ! wp_mkdir_p( $target_dir ) ) {
			return new WP_Error( 'dir_create_failed', 'Impossible de créer le dossier de stockage.' );
		}

		// Protège le dossier des accès directs HTTP
		$htaccess = $target_dir . '/.htaccess';
		if ( ! file_exists( $htaccess ) ) {
			$written = file_put_contents( $htaccess, "Options -Indexes\nDeny from all\n" );
			if ( false === $written ) {
				error_log( 'Cpz_Upload_Handler: impossible d\'écrire le .htaccess dans ' . $target_dir );
			}
		}

		// Génère un nom de fichier unique et sécurisé
		$ext       = $this->mime_to_ext( $real_mime );
		$filename  = 'cpz_' . bin2hex( random_bytes( 16 ) ) . '.' . $ext;
		$dest_path = $target_dir . '/' . $filename;

		if ( ! move_uploaded_file( $file['tmp_name'], $dest_path ) ) {
			return new WP_Error( 'move_failed', 'Erreur lors du stockage de l\'image. Réessayez.' );
		}

		return $dest_path;
	}

	// ═════════════════════════════════════════════════════════════
	//  NETTOYAGE AUTOMATIQUE
	// ═════════════════════════════════════════════════════════════

	/**
	 * Supprime les fichiers temporaires plus vieux que RETENTION secondes.
	 * Déclenché quotidiennement par le cron WP.
	 */
	public function cleanup_temp_files(): void {
		$upload_dir = wp_upload_dir();
		$base_dir   = trailingslashit( $upload_dir['basedir'] );

		// Nettoie les fichiers originaux
		$target_dir = $base_dir . self::UPLOAD_SUBDIR;
		if ( is_dir( $target_dir ) ) {
			$this->delete_old_files( $target_dir . '/cpz_*.{jpg,jpeg,png,webp}' );
		}

		// Nettoie les vignettes
		$thumb_dir = $base_dir . 'mypetpuzzle-thumbs';
		if ( is_dir( $thumb_dir ) ) {
			$this->delete_old_files( $thumb_dir . '/thumb_cpz_*.{jpg,jpeg,png,webp}' );
		}
	}

	/**
	 * Supprime les fichiers correspondant à un glob plus vieux que RETENTION.
	 */
	private function delete_old_files( string $glob_pattern ): void {
		$files = glob( $glob_pattern, GLOB_BRACE );
		if ( ! $files ) {
			return;
		}
		$threshold = time() - self::RETENTION;
		foreach ( $files as $file ) {
			if ( filemtime( $file ) < $threshold ) {
				@unlink( $file );
			}
		}
	}

	// ═════════════════════════════════════════════════════════════
	//  HELPERS
	// ═════════════════════════════════════════════════════════════

	/**
	 * Convertit un MIME type en extension de fichier.
	 */
	private function mime_to_ext( string $mime ): string {
		$map = [
			'image/jpeg' => 'jpg',
			'image/png'  => 'png',
			'image/webp' => 'webp',
		];
		return $map[ $mime ] ?? 'jpg';
	}

	/**
	 * Message d'erreur lisible pour les codes d'erreur PHP upload.
	 */
	private function file_upload_error_message( int $code ): string {
		$messages = [
			UPLOAD_ERR_INI_SIZE   => 'Fichier trop volumineux (limite serveur).',
			UPLOAD_ERR_FORM_SIZE  => 'Fichier trop volumineux (limite formulaire).',
			UPLOAD_ERR_PARTIAL    => 'Transfert incomplet. Réessayez.',
			UPLOAD_ERR_NO_FILE    => 'Aucune photo reçue.',
			UPLOAD_ERR_NO_TMP_DIR => 'Erreur serveur (dossier temp manquant).',
			UPLOAD_ERR_CANT_WRITE => 'Erreur serveur (écriture impossible).',
			UPLOAD_ERR_EXTENSION  => 'Upload bloqué par le serveur.',
		];
		return $messages[ $code ] ?? 'Erreur inconnue lors de l\'upload.';
	}
}