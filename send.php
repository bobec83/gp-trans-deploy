<?php
header('Content-Type: application/json; charset=utf-8');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['ok' => false]);
    exit;
}

$input = json_decode(file_get_contents('php://input'), true);

$name    = isset($input['name'])    ? trim(strip_tags($input['name']))    : '';
$email   = isset($input['email'])   ? trim(strip_tags($input['email']))   : '';
$phone   = isset($input['phone'])   ? trim(strip_tags($input['phone']))   : '';
$message = isset($input['message']) ? trim(strip_tags($input['message'])) : '';

if ($name === '' || $email === '' || $message === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(['ok' => false]);
    exit;
}

$to = 'wpiaskowski@gmail.com';
$subject = '=?UTF-8?B?' . base64_encode('Zapytanie ze strony www — ' . $name) . '?=';

$messageHtml = nl2br(htmlspecialchars($message, ENT_QUOTES, 'UTF-8'));
$phoneRow = $phone !== '' ? '
                <tr>
                    <td style="padding:12px 20px;color:#6b7d8e;font-size:13px;width:140px;vertical-align:top;border-top:1px solid #e8ecf0;">Telefon</td>
                    <td style="padding:12px 20px;color:#0b1c2c;font-size:15px;border-top:1px solid #e8ecf0;">' . htmlspecialchars($phone, ENT_QUOTES, 'UTF-8') . '</td>
                </tr>' : '';

$date = date('d.m.Y, H:i');

$body = '<!DOCTYPE html>
<html lang="pl">
<head><meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1.0"></head>
<body style="margin:0;padding:0;background-color:#eef1f5;font-family:Arial,Helvetica,sans-serif;">

<!-- Outer wrapper -->
<table width="100%" cellpadding="0" cellspacing="0" style="background-color:#eef1f5;padding:40px 16px;">
<tr><td align="center">

<!-- Main card -->
<table width="600" cellpadding="0" cellspacing="0" style="background-color:#ffffff;border-radius:16px;overflow:hidden;box-shadow:0 4px 24px rgba(11,28,44,0.10);">

    <!-- ===== HEADER (dark navy like navbar) ===== -->
    <tr>
        <td style="background-color:#0b1c2c;padding:32px 40px;text-align:center;">
            <img src="https://www.gp-trans.pl/logo-email.png" alt="GP-Trans" width="180" style="display:block;margin:0 auto;" />
        </td>
    </tr>

    <!-- ===== RED ACCENT BAR (like CTA / TrustBar accent) ===== -->
    <tr>
        <td style="background-color:#df1d2b;padding:16px 40px;">
            <table width="100%" cellpadding="0" cellspacing="0">
                <tr>
                    <td style="color:#ffffff;font-size:16px;font-weight:700;letter-spacing:0.5px;">
                        &#9993; Nowe zapytanie ze strony www
                    </td>
                    <td align="right" style="color:rgba(255,255,255,0.7);font-size:12px;white-space:nowrap;">
                        ' . $date . '
                    </td>
                </tr>
            </table>
        </td>
    </tr>

    <!-- ===== CONTACT DATA ===== -->
    <tr>
        <td style="padding:28px 28px 12px;">
            <p style="margin:0 0 12px;color:#6b7d8e;font-size:11px;text-transform:uppercase;letter-spacing:2px;font-weight:600;">Dane kontaktowe</p>
            <table width="100%" cellpadding="0" cellspacing="0" style="border:1px solid #e8ecf0;border-radius:10px;overflow:hidden;">
                <tr style="background-color:#f7f9fb;">
                    <td style="padding:12px 20px;color:#6b7d8e;font-size:13px;width:140px;">Imię i nazwisko</td>
                    <td style="padding:12px 20px;color:#0b1c2c;font-size:15px;font-weight:700;">' . htmlspecialchars($name, ENT_QUOTES, 'UTF-8') . '</td>
                </tr>
                <tr>
                    <td style="padding:12px 20px;color:#6b7d8e;font-size:13px;border-top:1px solid #e8ecf0;">E-mail</td>
                    <td style="padding:12px 20px;border-top:1px solid #e8ecf0;">
                        <a href="mailto:' . htmlspecialchars($email, ENT_QUOTES, 'UTF-8') . '" style="color:#df1d2b;font-size:15px;text-decoration:none;font-weight:600;">' . htmlspecialchars($email, ENT_QUOTES, 'UTF-8') . '</a>
                    </td>
                </tr>' . $phoneRow . '
            </table>
        </td>
    </tr>

    <!-- ===== MESSAGE ===== -->
    <tr>
        <td style="padding:16px 28px 32px;">
            <p style="margin:0 0 12px;color:#6b7d8e;font-size:11px;text-transform:uppercase;letter-spacing:2px;font-weight:600;">Treść wiadomości</p>
            <div style="background-color:#f7f9fb;border-left:4px solid #df1d2b;border-radius:0 10px 10px 0;padding:20px 24px;">
                <p style="margin:0;color:#0b1c2c;font-size:15px;line-height:1.7;">' . $messageHtml . '</p>
            </div>
        </td>
    </tr>

    <!-- ===== QUICK ACTION ===== -->
    <tr>
        <td style="padding:0 28px 32px;" align="center">
            <table cellpadding="0" cellspacing="0">
                <tr>
                    <td style="background-color:#df1d2b;border-radius:8px;padding:14px 32px;">
                        <a href="mailto:' . htmlspecialchars($email, ENT_QUOTES, 'UTF-8') . '?subject=Re: Zapytanie ze strony gp-trans.pl" style="color:#ffffff;font-size:14px;font-weight:700;text-decoration:none;letter-spacing:0.5px;">Odpowiedz na wiadomość</a>
                    </td>
                </tr>
            </table>
        </td>
    </tr>

    <!-- ===== FOOTER (dark navy like website footer) ===== -->
    <tr>
        <td style="background-color:#0b1c2c;padding:28px 40px;">
            <table width="100%" cellpadding="0" cellspacing="0">
                <tr>
                    <td style="color:rgba(255,255,255,0.8);font-size:13px;font-weight:600;">
                        GP-Trans Golik i Piaskowski Sp. J.
                    </td>
                </tr>
                <tr>
                    <td style="padding-top:8px;color:rgba(255,255,255,0.45);font-size:12px;line-height:1.6;">
                        ul. Krótka 8, 69-100 Słubice<br>
                        tel. +48 95 755 88 08 &bull; fax +48 95 715 48 93<br>
                        spedycja@gp-trans.pl &bull; www.gp-trans.pl
                    </td>
                </tr>
                <tr>
                    <td style="padding-top:8px;color:rgba(255,255,255,0.3);font-size:11px;">
                        NIP: 5981633864 &bull; REGON: 361377446 &bull; KRS: 0000555975
                    </td>
                </tr>
            </table>
        </td>
    </tr>

    <!-- ===== BOTTOM NOTE ===== -->
    <tr>
        <td style="background-color:#091520;padding:12px 40px;text-align:center;">
            <p style="margin:0;color:rgba(255,255,255,0.25);font-size:10px;">
                Wiadomość wygenerowana automatycznie ze strony gp-trans.pl &bull; &copy; 2026 GP-Trans
            </p>
        </td>
    </tr>

</table>
<!-- End main card -->

</td></tr>
</table>
</body>
</html>';

$headers  = "From: noreply@gp-trans.pl\r\n";
$headers .= "Reply-To: $email\r\n";
$headers .= "MIME-Version: 1.0\r\n";
$headers .= "Content-Type: text/html; charset=UTF-8\r\n";

$sent = mail($to, $subject, $body, $headers);

echo json_encode(['ok' => $sent]);
