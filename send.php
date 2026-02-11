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
$subject = 'Zapytanie ze strony www - ' . $name;

$body  = "Imię i nazwisko: $name\n";
$body .= "E-mail: $email\n";
$body .= "Telefon: $phone\n\n";
$body .= "Wiadomość:\n$message\n";

$headers  = "From: noreply@gp-trans.pl\r\n";
$headers .= "Reply-To: $email\r\n";
$headers .= "Content-Type: text/plain; charset=UTF-8\r\n";

$sent = mail($to, $subject, $body, $headers);

echo json_encode(['ok' => $sent]);
