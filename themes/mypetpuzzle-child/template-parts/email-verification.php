<?php
defined('ABSPATH') || exit;
?>
<!DOCTYPE html>
<html>
<head><meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<style>
    @media only screen and (max-width:600px){
        .email-container{width:100%!important;padding:0!important}
        .email-inner{padding:32px 24px!important}
        .email-button{width:100%!important;padding:14px 20px!important;font-size:15px!important;box-sizing:border-box!important}
        .email-title{font-size:22px!important}
        .email-body{font-size:15px!important}
    }
</style>
</head>
<body style="margin:0;padding:0;background-color:#f5f0e8;font-family:'DM Sans',Helvetica,Arial,sans-serif;">
<table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background-color:#f5f0e8;">
<tr><td align="center" style="padding:40px 16px;">
    <table class="email-container" role="presentation" width="560" cellpadding="0" cellspacing="0" style="max-width:560px;width:100%;">
        <tr>
            <td style="padding:0 0 8px 0;text-align:center;">
                <span style="font-family:'Playfair Display',Georgia,serif;font-size:22px;color:#2c1810;letter-spacing:1px;">
                    <span style="color:#c9a84c;">My</span>Pet<span style="color:#c9a84c;">Puzzle</span>
                </span>
            </td>
        </tr>
        <tr>
            <td class="email-inner" style="background-color:#ffffff;border-radius:12px;padding:48px 40px;box-shadow:0 4px 24px rgba(44,24,16,0.1);">
                <h1 class="email-title" style="font-family:'Playfair Display',Georgia,serif;font-size:26px;color:#2c1810;margin:0 0 8px 0;text-align:center;">Bienvenue sur MyPetPuzzle</h1>
                <p class="email-body" style="font-size:16px;color:#6b5d58;margin:0 0 24px 0;text-align:center;line-height:1.6;">
                    Merci de vous être inscrit, <strong style="color:#2c1810;"><?php echo esc_html($name); ?></strong>.
                </p>
                <p class="email-body" style="font-size:16px;color:#6b5d58;margin:0 0 32px 0;text-align:center;line-height:1.6;">
                    Pour activer votre compte et finaliser votre inscription, cliquez sur le bouton ci-dessous :
                </p>
                <table role="presentation" width="100%" cellpadding="0" cellspacing="0">
                    <tr><td align="center" style="padding:0 0 32px 0;">
                        <a class="email-button" href="<?php echo esc_url($verify_url); ?>" style="display:inline-block;background-color:#c9a84c;color:#ffffff;text-decoration:none;padding:14px 40px;border-radius:8px;font-size:16px;font-weight:600;letter-spacing:0.5px;">
                            Confirmer mon adresse email
                        </a>
                    </td></tr>
                </table>
                <p style="font-size:14px;color:#6b5d58;margin:0 0 16px 0;text-align:center;line-height:1.5;">
                    Si le bouton ne fonctionne pas, copiez le lien ci-dessous dans votre navigateur :
                </p>
                <p style="font-size:12px;color:#8b6f47;margin:0 0 0 0;text-align:center;word-break:break-all;line-height:1.5;">
                    <a href="<?php echo esc_url($verify_url); ?>" style="color:#8b6f47;"><?php echo esc_url($verify_url); ?></a>
                </p>
            </td>
        </tr>
        <tr>
            <td style="padding:24px 16px 0 16px;text-align:center;">
                <p style="font-size:13px;color:#8b6f47;margin:0 0 4px 0;line-height:1.5;">
                    Vous recevez cet email car vous avez créé un compte sur MyPetPuzzle.
                </p>
                <p style="font-size:13px;color:#8b6f47;margin:0;line-height:1.5;">
                    MyPetPuzzle &mdash; L'art du puzzle à l'image de votre animal
                </p>
            </td>
        </tr>
    </table>
</td></tr>
</table>
</body>
</html>
