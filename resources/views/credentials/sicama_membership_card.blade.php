<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>SICAMA Membership Card — {{ $member->user->name }}</title>
    <style>
        @page { margin: 0; size: 242.64pt 152.52pt; }
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: DejaVu Sans, sans-serif; color: #111; font-size: 8px; }

        .card {
            width: 242.64pt;
            height: 152.52pt;
            page-break-after: always;
            border-collapse: collapse;
        }
        .card-back { page-break-after: auto; }

        .band { background: #156082; color: #fff; }
        .header-cell { height: 30pt; padding: 3pt 7pt; vertical-align: middle; }
        .footer-cell {
            height: 14pt;
            text-align: center;
            vertical-align: middle;
            font-size: 7.5pt;
            font-weight: bold;
            letter-spacing: 0.3pt;
        }
        .footer-thin { height: 7pt; }

        .body-cell {
            height: 108.52pt;
            vertical-align: top;
            background: #fff;
            position: relative;
            padding: 5pt 8pt 3pt;
            overflow: hidden;
        }
        .body-back { height: 115.52pt; }

        .wm {
            position: absolute;
            left: -18pt;
            top: 40pt;
            width: 300pt;
            font-size: 15pt;
            font-weight: bold;
            color: #e6e6e6;
            letter-spacing: 1pt;
            transform: rotate(-32deg);
            z-index: 0;
            white-space: nowrap;
        }

        .layer { position: relative; z-index: 1; }

        .hdr { width: 100%; border-collapse: collapse; }
        .hdr td { vertical-align: middle; color: #fff; }

        .logo {
            width: 22pt;
            height: 22pt;
            background: #fff;
            border-radius: 50%;
            text-align: center;
            overflow: hidden;
        }
        .logo img { width: 20pt; height: 20pt; margin-top: 1pt; }

        .org-full {
            font-size: 5.6pt;
            font-weight: bold;
            text-transform: uppercase;
            line-height: 1.1;
            letter-spacing: 0.1pt;
        }
        .org-short {
            font-size: 10.5pt;
            font-weight: bold;
            letter-spacing: 1pt;
            margin-top: 1pt;
        }

        .front-grid { width: 100%; border-collapse: collapse; }
        .front-grid td { vertical-align: middle; }

        .photo {
            width: 42pt;
            height: 42pt;
            border: 1.1pt solid #156082;
            background: #f3f4f6;
            text-align: center;
            overflow: hidden;
        }
        .photo img { width: 42pt; height: 42pt; }
        .photo-ph {
            font-size: 5.5pt;
            color: #64748b;
            font-weight: bold;
            line-height: 1.15;
            padding-top: 12pt;
        }

        .name {
            font-size: 10.5pt;
            font-weight: bold;
            text-align: center;
            line-height: 1.12;
        }
        .role {
            font-size: 9pt;
            font-weight: bold;
            text-align: center;
            margin-top: 2pt;
        }

        .meta { margin-top: 5pt; font-size: 7.2pt; line-height: 1.3; }
        .bold { font-weight: bold; }
        .meta-line { margin-top: 1pt; }

        .back-title {
            text-align: center;
            font-size: 9.5pt;
            font-weight: bold;
            letter-spacing: 0.5pt;
            text-decoration: underline;
            margin-top: 1pt;
        }
        .back-copy {
            margin-top: 3pt;
            font-size: 6pt;
            line-height: 1.25;
            text-align: center;
            padding: 0 8pt;
        }
        .back-h {
            margin-top: 3pt;
            text-align: center;
            font-size: 6.4pt;
            font-weight: bold;
        }
        .back-c {
            margin-top: 1.5pt;
            text-align: center;
            font-size: 6pt;
            line-height: 1.3;
        }
        .email { color: #156082; text-decoration: underline; }

        .sig-row { width: 100%; border-collapse: collapse; margin-top: 4pt; }
        .sig-row td { vertical-align: bottom; text-align: center; }
        .sig-img { height: 16pt; }
        .sig-line { border-top: 0.7pt solid #111; margin: 1pt 5pt 0; }
        .sig-label { font-size: 6.5pt; font-weight: bold; margin-top: 1pt; }
        .qr { width: 28pt; height: 28pt; }
        .qr-cap { font-size: 5.5pt; font-weight: bold; margin-top: 1pt; }
    </style>
</head>
<body>
    <table class="card">
        <tr>
            <td class="band header-cell">
                <table class="hdr">
                    <tr>
                        <td style="width:26pt;">
                            <div class="logo">
                                @if ($logoDataUri)<img src="{{ $logoDataUri }}" alt="SEC">@endif
                            </div>
                        </td>
                        <td style="padding-left:4pt;">
                            <div class="org-full">Scholars in Communication and Media Advancement Initiative</div>
                            <div class="org-short">SICAMA</div>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td class="body-cell">
                <div class="wm">SICAMA INITIATIVE</div>
                <div class="layer">
                    <table class="front-grid">
                        <tr>
                            <td style="width:50pt;">
                                <div class="photo">
                                    @if ($profileImageDataUri)
                                        <img src="{{ $profileImageDataUri }}" alt="{{ $member->user->name }}">
                                    @else
                                        <div class="photo-ph">Your Picture<br>Here</div>
                                    @endif
                                </div>
                            </td>
                            <td>
                                <div class="name">{{ $member->user->name }}</div>
                                <div class="role">{{ $cardRoleTitle }}</div>
                            </td>
                        </tr>
                    </table>
                    <div class="meta">
                        <div class="bold">Membership No:</div>
                        <div class="bold">{{ $member->membership_number }}</div>
                        <div class="meta-line">Valid Until {{ $validUntilLabel }}</div>
                        <div class="meta-line"><span class="bold">E-mail:</span> {{ $member->user->email }}</div>
                    </div>
                </div>
            </td>
        </tr>
        <tr>
            <td class="band footer-cell">OFFICIAL SICAMA MEMBERSHIP CARD</td>
        </tr>
    </table>

    <table class="card card-back">
        <tr>
            <td class="band header-cell">
                <table class="hdr">
                    <tr>
                        <td style="width:26pt;">
                            <div class="logo">
                                @if ($logoDataUri)<img src="{{ $logoDataUri }}" alt="SEC">@endif
                            </div>
                        </td>
                        <td style="padding-left:4pt;">
                            <div class="org-short">SICAMA</div>
                            <div class="org-full">Scholars in Communication and Media Advancement Initiative</div>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td class="body-cell body-back">
                <div class="wm">SICAMA INITIATIVE</div>
                <div class="layer">
                    <div class="back-title">MEMBERSHIP CARD</div>
                    <div class="back-copy">
                        This Card remains the property of <strong>SICAMA</strong>
                        and is valid only for the period stated on the
                        front. If found, please return it to the organization.
                    </div>
                    <div class="back-h">For Membership Verification &amp; Enquiries</div>
                    <div class="back-c">
                        E-Mail: <span class="email">merlaw2002@yahoo.com</span><br>
                        TEL: +2348035525785, +2348060051572
                    </div>
                    <table class="sig-row">
                        <tr>
                            <td style="width:34%;">
                                @if ($chairmanSignatureDataUri)
                                    <img class="sig-img" src="{{ $chairmanSignatureDataUri }}" alt="Chairman">
                                @endif
                                <div class="sig-line"></div>
                                <div class="sig-label">Chairman</div>
                            </td>
                            <td style="width:34%;">
                                @if ($presidentSignatureDataUri)
                                    <img class="sig-img" src="{{ $presidentSignatureDataUri }}" alt="President">
                                @endif
                                <div class="sig-line"></div>
                                <div class="sig-label">President</div>
                            </td>
                            <td style="width:32%;">
                                <img class="qr" src="data:{{ $qrMime }};base64,{{ $qrImage }}" alt="QR">
                                <div class="qr-cap">Scan me!</div>
                            </td>
                        </tr>
                    </table>
                </div>
            </td>
        </tr>
        <tr>
            <td class="band footer-cell footer-thin">&nbsp;</td>
        </tr>
    </table>
</body>
</html>
