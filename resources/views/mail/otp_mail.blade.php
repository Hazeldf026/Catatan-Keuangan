<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Kode OTP Anda</title>
    <style>
        /* CSS ini hanya untuk fallback, gaya utama ada di inline-CSS */
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }
    </style>
</head>
<body style="margin: 0; padding: 0; background-color: #f4f4f4; font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;">

    <div style="max-width:600px; margin: 0 auto; background-color: #ffffff;">
        <table role="presentation" align="center" border="0" cellpadding="0" cellspacing="0" style="width:100%;">
            <tbody>
                <tr>
                    <td style="padding: 20px 0; text-align: center; background-color: #4A90E2;">
                        <img src="{{ asset('images/Logo.png') }}" alt="Credix Logo" width="120" style="width:120px; max-width:120px; height:auto; border:0; text-decoration:none; -ms-interpolation-mode:bicubic;">
                    </td>
                </tr>
                <tr>
                    <td style="padding: 40px 30px; text-align: left;">
                        <h1 style="font-size: 24px; font-weight: 700; color: #333333; margin: 0 0 20px 0;">
                            Verifikasi Kode OTP Anda
                        </h1>
                        <p style="font-size: 16px; line-height: 1.5; color: #555555; margin: 0 0 30px 0;">
                            Halo, silakan gunakan kode berikut untuk menyelesaikan proses verifikasi Anda. Kode ini hanya berlaku selama 10 menit.
                        </p>
                        
                        <div style="text-align: center; margin-bottom: 30px;">
                            <span style="display: inline-block; background-color: #eef3f8; padding: 15px 25px; border-radius: 8px; font-size: 32px; font-weight: 700; color: #0a2540; letter-spacing: 5px; border: 1px dashed #cccccc;">
                                {{ $otp }}
                            </span>
                        </div>

                        <p style="font-size: 16px; line-height: 1.5; color: #555555; margin: 0 0 20px 0;">
                            Jika Anda tidak merasa meminta kode ini, mohon abaikan email ini demi keamanan akun Anda.
                        </p>
                        <p style="font-size: 16px; line-height: 1.5; color: #555555; margin: 0;">
                            Terima kasih,<br>
                            Tim Credix
                        </p>
                    </td>
                </tr>
                <tr>
                    <td style="padding: 20px 30px; text-align: center; background-color: #f4f4f4; border-top: 1px solid #dddddd;">
                        <p style="font-size: 12px; color: #999999; margin: 0;">
                            &copy; 2025 Credix. Semua Hak Cipta Dilindungi.
                        </p>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

    </body>
</html>