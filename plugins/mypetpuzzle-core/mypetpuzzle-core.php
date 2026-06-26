<?php
/**
 * Plugin Name: MyPetPuzzle Core
 * Plugin URI:  https://mypetpuzzle.com
 * Description: Fonctionnalités principales MyPetPuzzle.
 * Version:     1.0.0
 * Author:      Your Name
 * Text Domain: mypetpuzzle-core
 * Requires PHP: 8.2
 * Requires Plugins: woocommerce
 */

defined( 'ABSPATH' ) || exit;

// ─── Constantes ───────────────────────────────────────────────
define( 'MYPETPUZZLE_VERSION',     '1.0.0' );
define( 'MYPETPUZZLE_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'MYPETPUZZLE_PLUGIN_URL', plugin_dir_url( __FILE__ ) );

defined( 'MYPETPUZZLE_PRINTIFY_SHOP_ID' )        || define( 'MYPETPUZZLE_PRINTIFY_SHOP_ID', 27917431 );
defined( 'MYPETPUZZLE_PRINTIFY_BLUEPRINT_ID' )   || define( 'MYPETPUZZLE_PRINTIFY_BLUEPRINT_ID', 616 );
defined( 'MYPETPUZZLE_PRINTIFY_PRINT_PROVIDER_ID' ) || define( 'MYPETPUZZLE_PRINTIFY_PRINT_PROVIDER_ID', 1 );

// ─── Chargement des classes ───────────────────────────────────
require_once MYPETPUZZLE_PLUGIN_DIR . 'includes/class-upload-handler.php';
require_once MYPETPUZZLE_PLUGIN_DIR . 'includes/class-puzzle-product.php';
require_once MYPETPUZZLE_PLUGIN_DIR . 'includes/class-order-meta.php';

// ─── Initialisation (après que WooCommerce soit chargé) ───────
add_action( 'plugins_loaded', function () {
    if ( ! class_exists( 'WooCommerce' ) ) {
        add_action( 'admin_notices', function () {
            echo '<div class="notice notice-error"><p>';
            echo '<strong>MyPetPuzzle Core</strong> nécessite WooCommerce. Activez-le d\'abord.';
            echo '</p></div>';
        } );
        return;
    }

    new Cpz_Upload_Handler();
    new Cpz_Puzzle_Product();
    new Cpz_Order_Meta();
} );