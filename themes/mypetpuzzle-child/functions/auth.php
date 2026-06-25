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

    $message = sprintf(
        '<!DOCTYPE html>
        <html>
        <head><meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1">
        <style>
            @media only screen and (max-width:600px){
                .email-container{width:100%%!important;padding:0!important}
                .email-inner{padding:32px 24px!important}
                .email-button{width:100%%!important;padding:14px 20px!important;font-size:15px!important;box-sizing:border-box!important}
                .email-title{font-size:22px!important}
                .email-body{font-size:15px!important}
            }
        </style>
        </head>
        <body style="margin:0;padding:0;background-color:#f5f0e8;font-family:\'DM Sans\',Helvetica,Arial,sans-serif;">
        <table role="presentation" width="100%%" cellpadding="0" cellspacing="0" style="background-color:#f5f0e8;">
        <tr><td align="center" style="padding:40px 16px;">
            <table class="email-container" role="presentation" width="560" cellpadding="0" cellspacing="0" style="max-width:560px;width:100%%;">
                <tr>
                    <td style="padding:0 0 8px 0;text-align:center;">
                        <span style="font-family:\'Playfair Display\',Georgia,serif;font-size:22px;color:#2c1810;letter-spacing:1px;">
                            <span style="color:#c9a84c;">My</span>Pet<span style="color:#c9a84c;">Puzzle</span>
                        </span>
                    </td>
                </tr>
                <tr>
                    <td class="email-inner" style="background-color:#ffffff;border-radius:12px;padding:48px 40px;box-shadow:0 4px 24px rgba(44,24,16,0.1);">
                        <h1 class="email-title" style="font-family:\'Playfair Display\',Georgia,serif;font-size:26px;color:#2c1810;margin:0 0 8px 0;text-align:center;">Bienvenue sur MyPetPuzzle</h1>
                        <p class="email-body" style="font-size:16px;color:#6b5d58;margin:0 0 24px 0;text-align:center;line-height:1.6;">
                            Merci de vous être inscrit, <strong style="color:#2c1810;">%s</strong>.
                        </p>
                        <p class="email-body" style="font-size:16px;color:#6b5d58;margin:0 0 32px 0;text-align:center;line-height:1.6;">
                            Pour activer votre compte et finaliser votre inscription, cliquez sur le bouton ci-dessous :
                        </p>
                        <table role="presentation" width="100%%" cellpadding="0" cellspacing="0">
                            <tr><td align="center" style="padding:0 0 32px 0;">
                                <a class="email-button" href="%s" style="display:inline-block;background-color:#c9a84c;color:#ffffff;text-decoration:none;padding:14px 40px;border-radius:8px;font-size:16px;font-weight:600;letter-spacing:0.5px;">
                                    Confirmer mon adresse email
                                </a>
                            </td></tr>
                        </table>
                        <p style="font-size:14px;color:#6b5d58;margin:0 0 16px 0;text-align:center;line-height:1.5;">
                            Si le bouton ne fonctionne pas, copiez le lien ci-dessous dans votre navigateur :
                        </p>
                        <p style="font-size:12px;color:#8b6f47;margin:0 0 0 0;text-align:center;word-break:break-all;line-height:1.5;">
                            <a href="%s" style="color:#8b6f47;">%s</a>
                        </p>
                    </td>
                </tr>
                <tr>
                    <td style="padding:24px 16px 0 16px;text-align:center;">
                        <p style="font-size:13px;color:#8b6f47;margin:0 0 4px 0;line-height:1.5;">
                            Vous recevez cet email car vous avez créé un compte sur MyPetPuzzle.
                        </p>
                        <p style="font-size:13px;color:#8b6f47;margin:0;line-height:1.5;">
                            MyPetPuzzle &mdash; L\'art du puzzle à l\'image de votre animal
                        </p>
                    </td>
                </tr>
            </table>
        </td></tr>
        </table>
        </body>
        </html>',
        esc_html($name),
        esc_url($verify_url),
        esc_url($verify_url),
        esc_url($verify_url)
    );

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

