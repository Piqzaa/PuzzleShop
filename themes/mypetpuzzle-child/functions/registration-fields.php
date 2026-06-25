<?php

defined('ABSPATH') || exit;

add_action('init', function () {
    if (isset($_GET['inscription']) || isset($_POST['register'])) {
        add_filter('pre_option_woocommerce_registration_generate_password', '__return_false');
    }
});

add_action('woocommerce_register_post', function ($username, $email, $validation_errors) {
    if (empty($_POST['billing_first_name'])) {
        $validation_errors->add('billing_first_name_error', __('Le prénom est requis.', 'mypetpuzzle-child'));
    }
    if (empty($_POST['billing_last_name'])) {
        $validation_errors->add('billing_last_name_error', __('Le nom est requis.', 'mypetpuzzle-child'));
    }
    if (!empty($_POST['password']) && ($_POST['password'] !== ($_POST['password2'] ?? ''))) {
        $validation_errors->add('password_mismatch', __('Les mots de passe ne correspondent pas.', 'mypetpuzzle-child'));
    }
}, 10, 3);

add_action('woocommerce_created_customer', function ($customer_id) {
    $fields = array(
        'billing_first_name',
        'billing_last_name',
        'billing_phone',
        'billing_address_1',
        'billing_city',
        'billing_postcode',
    );

    foreach ($fields as $field) {
        if (!empty($_POST[$field])) {
            update_user_meta($customer_id, $field, sanitize_text_field(wp_unslash($_POST[$field])));
        }
    }

    if (!empty($_POST['password'])) {
        wp_set_password($_POST['password'], $customer_id);
    }
});

add_action('init', function () {
    remove_action('woocommerce_register_form', 'wc_registration_privacy_policy_text', 20);
});
