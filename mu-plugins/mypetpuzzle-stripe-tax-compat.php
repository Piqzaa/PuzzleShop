<?php
/**
 * Stripe Tax compat
 *
 * Supprime une notice PHP inoffensive générée par l'extension
 * stripe-tax-for-woocommerce (propriété `$state` inexistante sur
 * certains objets). Ce bug est connu et a été signalé à l'éditeur
 * du plugin. Ce workaround est temporaire, à supprimer quand le
 * plugin tiers sera corrigé.
 */

defined('ABSPATH') || exit;

add_action('plugins_loaded', function () {
    if (!class_exists('Stripe\StripeTaxForWooCommerce\Stripe\TaxSettings')) {
        return;
    }

    set_error_handler(function ($severity, $message, $file) {
        if (
            ($severity === E_WARNING || $severity === E_NOTICE)
            && str_contains($file, 'stripe-tax-for-woocommerce')
            && str_contains($message, 'Undefined property')
            && str_contains($message, '::$state')
        ) {
            return true;
        }
        return false;
    });
});
