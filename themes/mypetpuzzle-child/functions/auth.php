<?php

defined('ABSPATH') || exit;

add_filter('woocommerce_email_enabled_customer_new_account', '__return_false');

add_action('woocommerce_created_customer', function ($customer_id) {
    $key = wp_hash($customer_id . time() . wp_rand());
    update_user_meta($customer_id, '_email_verification_key', $key);
    update_user_meta($customer_id, '_email_verified', 0);

    $user = get_userdata($customer_id);
    $verify_url = add_query_arg([
        'verify_email' => $key,
        'user'         => $customer_id,
    ], wc_get_page_permalink('myaccount'));

    $name = !empty($user->first_name) ? $user->first_name : $user->user_email;
    $subject = __('Confirmez votre adresse email', 'mypetpuzzle-child');

    ob_start();
    $verify_url = esc_url($verify_url);
    include get_stylesheet_directory() . '/template-parts/email-verification.php';
    $message = ob_get_clean();

    $headers = ['Content-Type: text/html; charset=UTF-8'];
    wp_mail($user->user_email, $subject, $message, $headers);
}, 20);

add_filter('query_vars', function ($vars) {
    $vars[] = 'verify_email';
    $vars[] = 'user';
    $vars[] = 'email_verified';
    $vars[] = 'registration';
    return $vars;
});

add_action('template_redirect', function () {
    $key = get_query_var('verify_email');
    $user_id = intval(get_query_var('user'));

    if (empty($key) || empty($user_id)) {
        return;
    }

    $stored_key = get_user_meta($user_id, '_email_verification_key', true);

    if ($stored_key !== $key) {
        $redirect = add_query_arg('email_verified', 'invalid', wc_get_page_permalink('myaccount'));
        wp_safe_redirect($redirect);
        exit;
    }

    update_user_meta($user_id, '_email_verified', 1);
    delete_user_meta($user_id, '_email_verification_key');

    $redirect = add_query_arg('email_verified', '1', wc_get_page_permalink('myaccount'));
    wp_safe_redirect($redirect);
    exit;
});

add_filter('wp_authenticate_user', function ($user, $password) {
    if (is_wp_error($user)) {
        return $user;
    }

    $verified = get_user_meta($user->ID, '_email_verified', true);

    if ('' === $verified) {
        return $user;
    }

    if ('1' !== $verified) {
        return new WP_Error(
            'email_not_verified',
            __('Veuillez confirmer votre adresse email avant de vous connecter.', 'mypetpuzzle-child')
        );
    }

    return $user;
}, 10, 2);

add_filter('woocommerce_login_redirect', function ($redirect, $user) {
    return home_url('/');
}, 10, 2);

add_action('template_redirect', function () {
    if (!is_page(wc_get_page_id('myaccount'))) {
        return;
    }

    $verified = get_query_var('email_verified', '');

    if ('1' === $verified) {
        wc_add_notice(__('Votre email a bien été confirmé. Vous pouvez maintenant vous connecter.', 'mypetpuzzle-child'), 'success');
    } elseif ('invalid' === $verified) {
        wc_add_notice(__('Le lien de confirmation est invalide ou a expiré.', 'mypetpuzzle-child'), 'error');
    }
});

