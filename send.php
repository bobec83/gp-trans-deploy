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

$to = 'spedycja@gp-trans.pl';
$subject = '=?UTF-8?B?' . base64_encode('Zapytanie ze strony www — ' . $name) . '?=';

$messageHtml = nl2br(htmlspecialchars($message, ENT_QUOTES, 'UTF-8'));
$phoneRow = $phone !== '' ? '
                <tr>
                    <td style="padding:8px 16px;color:#8a9bae;font-size:13px;width:130px;vertical-align:top;">Telefon</td>
                    <td style="padding:8px 16px;color:#1a2a3a;font-size:15px;">' . htmlspecialchars($phone, ENT_QUOTES, 'UTF-8') . '</td>
                </tr>' : '';

$body = '<!DOCTYPE html>
<html lang="pl">
<head><meta charset="UTF-8"></head>
<body style="margin:0;padding:0;background-color:#f0f4f7;font-family:Arial,Helvetica,sans-serif;">
<table width="100%" cellpadding="0" cellspacing="0" style="background-color:#f0f4f7;padding:32px 0;">
<tr><td align="center">
<table width="600" cellpadding="0" cellspacing="0" style="background-color:#ffffff;border-radius:12px;overflow:hidden;box-shadow:0 2px 8px rgba(0,0,0,0.08);">

    <!-- Header -->
    <tr>
        <td style="background-color:#0b1c2c;padding:28px 32px;text-align:center;">
            <h1 style="margin:0;color:#3bbdc4;font-size:24px;font-weight:800;letter-spacing:1px;">GP-Trans</h1>
            <p style="margin:6px 0 0;color:rgba(255,255,255,0.6);font-size:12px;letter-spacing:2px;text-transform:uppercase;">Transport Międzynarodowy</p>
        </td>
    </tr>

    <!-- Title bar -->
    <tr>
        <td style="background-color:#3bbdc4;padding:14px 32px;">
            <h2 style="margin:0;color:#ffffff;font-size:16px;font-weight:600;">Nowe zapytanie ze strony www</h2>
        </td>
    </tr>

    <!-- Contact data -->
    <tr>
        <td style="padding:24px 16px 8px;">
            <table width="100%" cellpadding="0" cellspacing="0" style="border:1px solid #e8ecf0;border-radius:8px;overflow:hidden;">
                <tr style="background-color:#f7f9fb;">
                    <td style="padding:8px 16px;color:#8a9bae;font-size:13px;width:130px;">Imię i nazwisko</td>
                    <td style="padding:8px 16px;color:#1a2a3a;font-size:15px;font-weight:600;">' . htmlspecialchars($name, ENT_QUOTES, 'UTF-8') . '</td>
                </tr>
                <tr>
                    <td style="padding:8px 16px;color:#8a9bae;font-size:13px;border-top:1px solid #e8ecf0;">E-mail</td>
                    <td style="padding:8px 16px;border-top:1px solid #e8ecf0;">
                        <a href="mailto:' . htmlspecialchars($email, ENT_QUOTES, 'UTF-8') . '" style="color:#3bbdc4;font-size:15px;text-decoration:none;">' . htmlspecialchars($email, ENT_QUOTES, 'UTF-8') . '</a>
                    </td>
                </tr>' . $phoneRow . '
            </table>
        </td>
    </tr>

    <!-- Message -->
    <tr>
        <td style="padding:16px 16px 24px;">
            <div style="background-color:#f7f9fb;border-left:4px solid #3bbdc4;border-radius:0 8px 8px 0;padding:16px 20px;">
                <p style="margin:0 0 8px;color:#8a9bae;font-size:12px;text-transform:uppercase;letter-spacing:1px;">Treść wiadomości</p>
                <p style="margin:0;color:#1a2a3a;font-size:15px;line-height:1.6;">' . $messageHtml . '</p>
            </div>
        </td>
    </tr>

    <!-- Footer -->
    <tr>
        <td style="background-color:#0b1c2c;padding:20px 32px;text-align:center;">
            <p style="margin:0 0 4px;color:rgba(255,255,255,0.5);font-size:12px;">GP-Trans Golik i Piaskowski Sp. J.</p>
            <p style="margin:0 0 4px;color:rgba(255,255,255,0.4);font-size:11px;">ul. Krótka 8, 69-100 Słubice &bull; +48 95 755 88 08</p>
            <p style="margin:8px 0 0;color:rgba(255,255,255,0.3);font-size:10px;">Wiadomość wygenerowana automatycznie ze strony gp-trans.pl</p>
        </td>
    </tr>

</table>
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
