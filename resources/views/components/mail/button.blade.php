@props(['url', 'color' => 'primary'])

<table role="presentation" cellpadding="0" cellspacing="0" border="0" width="100%" style="margin: 24px 0;">
    <tr>
        <td align="center" style="text-align: center;">
            <table role="presentation" cellpadding="0" cellspacing="0" border="0" style="margin: 0 auto;">
                <tr>
                    <td align="center"
                        style="border-radius: 8px;
                        {{ $color === 'secondary' ? 'background-color: #f5f5f5;' : 'background-color: #6366f1;' }}">
                        <!--[if mso]>
                        <v:roundrect xmlns:v="urn:schemas-microsoft-com:vml" xmlns:w="urn:schemas-microsoft-com:office:word"
                            style="height:44px;width:160px;v-text-anchor:middle;" arcsize="10%" stroke="f" fillcolor="{{ $color === 'secondary' ? '#f5f5f5' : '#6366f1' }}">
                        <w:anchorlock/>
                        <center>
                        <![endif]-->
                        <a href="{{ $url }}" target="_blank" rel="noopener"
                            style="display: inline-block;
                                      padding: 10px 24px;
                                      min-width: 160px;
                                      font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
                                      font-size: 15px;
                                      line-height: 24px;
                                      font-weight: 500;
                                      text-align: center;
                                      text-decoration: none;
                                      {{ $color === 'secondary' ? 'color: #282a30;' : 'color: #ffffff;' }}">
                            {{ $slot }}
                        </a>
                        <!--[if mso]>
                        </center>
                        </v:roundrect>
                        <![endif]-->
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>
