@props(['title' => null])

<table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%"
    style="background-color: #f5f5f5; border-radius: 8px; margin: 24px 0;">
    <tr>
        <td style="padding: 24px;">
            @if ($title)
                <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%"
                    style="margin-bottom: 16px;">
                    <tr>
                        <td style="color: #282a30; font-size: 18px; line-height: 28px; font-weight: 600;">
                            {{ $title }}
                        </td>
                    </tr>
                </table>
            @endif

            <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
                <tr>
                    <td style="color: #666666; font-size: 14px; line-height: 20px;">
                        {{ $slot }}
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>
