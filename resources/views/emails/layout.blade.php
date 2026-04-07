<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <title>{{ $subject ?? 'Email' }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet"
        type="text/css" />
    <style type="text/css">
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap');
    </style>
</head>

<body
    style="margin:0;padding:0;background-color:#f0f4f8;font-family:'Poppins',Arial,sans-serif;-webkit-font-smoothing:antialiased;">
    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0"
        style="width:100%;background-color:#f0f4f8;margin:0;padding:0;">
        <tr>
            <td align="center" style="padding:48px 16px;">
                <table role="presentation" cellspacing="0" cellpadding="0" border="0"
                    style="max-width:600px;width:100%;background-color:#ffffff;border-radius:20px;overflow:hidden;box-shadow:0 4px 24px rgba(0,0,0,0.08);">
                    <tr>
                        <td align="center" style="padding:32px 40px;background:#0F3869;">
                            <table role="presentation" cellspacing="0" cellpadding="0" border="0">
                                <tr>
                                    <td valign="middle">
                                        <img src="{{ asset('assets/images/email-logo.png') }}" width="100" height="45"
                                            alt="{{ config('app.name') }}"
                                            style="display:block;border:0;outline:none;" />
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:40px 40px 32px;font-family:'Poppins',Arial,sans-serif;">
                            {!! $body !!}
                        </td>
                    </tr>
                    <tr>
                        <td align="center"
                            style="padding:24px 40px;background-color:#f8fafc;border-top:1px solid #e2e8f0;">
                            <p
                                style="font-family:'Poppins',Arial,sans-serif;font-size:12px;color:#94a3b8;line-height:1.7;margin:0 0 6px 0;">
                                This email was sent by
                                <strong
                                    style="font-family:'Poppins',Arial,sans-serif;color:#64748b;font-weight:600;">{{ config('app.name') }}</strong>.
                                If you have questions, simply reply to this email.
                            </p>
                            <p
                                style="font-family:'Poppins',Arial,sans-serif;font-size:12px;color:#94a3b8;line-height:1.7;margin:0;">
                                &copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>

</html>
