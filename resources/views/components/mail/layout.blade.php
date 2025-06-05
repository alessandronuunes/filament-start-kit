<!DOCTYPE html
    PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:v="urn:schemas-microsoft-com:vml"
    xmlns:o="urn:schemas-microsoft-com:office:office">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="Content-Type" content="text/html charset=UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="format-detection" content="telephone=no, date=no, address=no, email=no" />
    <meta name="x-apple-disable-message-reformatting" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="color-scheme" content="light dark" />
    <meta name="supported-color-schemes" content="light dark" />

    <title>{{ config('app.name') }}</title>

    <!--[if mso]>
        <style type="text/css">
            table {border-collapse: collapse; border-spacing: 0; margin: 0;}
            div, td {padding: 0;}
            div {margin: 0 !important;}
        </style>
        <noscript>
            <xml>
                <o:OfficeDocumentSettings>
                    <o:PixelsPerInch>96</o:PixelsPerInch>
                </o:OfficeDocumentSettings>
            </xml>
        </noscript>
        <![endif]-->

    <style>
        body {
            margin: 0 !important;
            padding: 0 !important;
            width: 100% !important;
            -webkit-text-size-adjust: 100% !important;
            -ms-text-size-adjust: 100% !important;
            background-color: #ffffff;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
        }

        #outlook a {
            padding: 0;
        }

        img {
            border: 0;
            height: auto;
            line-height: 100%;
            outline: none;
            text-decoration: none;
            -ms-interpolation-mode: bicubic;
        }

        table {
            border-collapse: collapse !important;
        }

        @media screen and (max-width: 600px) {
            .container {
                width: 100% !important;
                padding: 10px !important;
            }

            .content {
                padding: 20px 0 !important;
            }

            .header-title {
                font-size: 20px !important;
                line-height: 28px !important;
            }

            .text-content {
                font-size: 15px !important;
                line-height: 22px !important;
            }

            .footer-text {
                font-size: 13px !important;
                line-height: 19px !important;
            }

            .card-container {
                padding: 16px !important;
            }

            .card-title {
                font-size: 16px !important;
                line-height: 24px !important;
                margin-bottom: 12px !important;
            }

            td.card-content {
                font-size: 13px !important;
                line-height: 19px !important;
                padding: 10px 0 !important;
            }

            .button-mobile {
                display: block !important;
                width: 100% !important;
                min-width: 100% !important;
            }

            .footer-link {
                display: block !important;
                margin: 10px 0 !important;
                font-size: 13px !important;
                line-height: 19px !important;
            }
        }
    </style>
</head>

<body style="margin: 0; padding: 0; background-color: #ffffff; min-width: 100%;">
    <!-- Wrapper principal -->
    <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%"
        style="background-color: #ffffff;">
        <tr>
            <td align="center" style="padding: 0;">
                <!-- Container -->
                <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="600"
                    style="width: 100%; max-width: 600px; margin: 0 auto;">
                    <tr>
                        <td style="padding: 20px;">
                            <!-- Header -->
                            <x-mail.header />

                            <!-- Content -->
                            <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
                                <tr>
                                    <td style="padding: 32px 0;">
                                        {{ $slot }}
                                    </td>
                                </tr>
                            </table>

                            <!-- Footer -->
                            <x-mail.footer>
                                {{ $footer ?? '' }}
                            </x-mail.footer>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>

</html>
