<?php
add_action('plugins_loaded', function () {
    if (!class_exists('Stripe\StripeTaxForWooCommerce\Stripe\TaxSettings')) {
        return;
    }

    $prev = set_error_handler(function ($severity, $message, $file) use (&$prev) {
        if (($severity === E_WARNING || $severity === E_NOTICE)
            && str_contains($file, 'stripe-tax-for-woocommerce')
            && str_contains($message, 'Undefined property')
            && str_contains($message, '::$state')
        ) {
            return true;
        }
        return $prev ? $prev($severity, $message, $file) : false;
    });
});
