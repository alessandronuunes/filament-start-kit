<table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%"
    style="padding: 32px 0; border-top: 1px solid #dfe1e4;">
    <tr>
        <td align="center" style="text-align: center; padding-bottom: 16px;">
            <div class="text-content" style="color: #b4becc; font-size: 14px; line-height: 20px;">
                {{ config('app.name') }}
            </div>
        </td>
    </tr>

    <tr>
        <td align="center" style="text-align: center; padding-bottom: 16px;">
            <div class="text-content" style="color: #b4becc; font-size: 14px; line-height: 20px;">
                {{ $slot }}
            </div>
        </td>
    </tr>

    <tr>
        <td align="center" style="text-align: center;">
            <table role="presentation" cellspacing="0" cellpadding="0" border="0" style="margin: 0 auto;">
                <tr>
                    <td style="padding: 0 10px;">
                        <a href="{{ url('/privacy') }}" class="footer-link"
                            style="color: #b4becc; text-decoration: none; font-size: 14px;" target="_blank">
                            Privacidade
                        </a>
                    </td>
                    <td style="padding: 0 10px;">
                        <a href="{{ url('/terms') }}" class="footer-link"
                            style="color: #b4becc; text-decoration: none; font-size: 14px;" target="_blank">
                            Termos
                        </a>
                    </td>
                    <td style="padding: 0 10px;">
                        <a href="{{ url('/help') }}" class="footer-link"
                            style="color: #b4becc; text-decoration: none; font-size: 14px;" target="_blank">
                            Ajuda
                        </a>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>
