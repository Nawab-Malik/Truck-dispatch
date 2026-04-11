<?php
declare(strict_types=1);

header('Content-Type: text/html; charset=UTF-8');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo '<div class="error">Invalid request method.</div>';
    exit;
}

function clean_input(string $value): string
{
    return trim(str_replace(["\r", "\n"], ' ', $value));
}

$to = 'am6242jan@gmail.com';
$siteName = 'Primelinklogistics.us';

$name = clean_input($_POST['name'] ?? '');
$email = filter_var(trim($_POST['email'] ?? ''), FILTER_VALIDATE_EMAIL);
$phone = clean_input($_POST['phone'] ?? '');
$service = clean_input($_POST['service'] ?? '');
$pickupState = clean_input($_POST['pickup_state'] ?? '');
$pickupCity = clean_input($_POST['pickup_city'] ?? '');
$deliveryState = clean_input($_POST['delivery_state'] ?? '');
$deliveryCity = clean_input($_POST['delivery_city'] ?? '');
$distanceMiles = clean_input($_POST['distance_miles'] ?? '');
$message = trim($_POST['message'] ?? '');
$subject = clean_input($_POST['subject'] ?? 'Rate Quote Request');

if ($name === '' || !$email || $message === '') {
    http_response_code(422);
    echo '<div class="error">Please complete all required fields.</div>';
    exit;
}

$emailSubject = $siteName . ' - ' . $subject;
$emailBody = "New form submission received from {$siteName}\n\n";
$emailBody .= "Name: {$name}\n";
$emailBody .= "Email: {$email}\n";
$emailBody .= "Phone: {$phone}\n";
$emailBody .= "Service Needed: {$service}\n";
$emailBody .= "Pickup: {$pickupCity}, {$pickupState}\n";
$emailBody .= "Delivery: {$deliveryCity}, {$deliveryState}\n";
$emailBody .= "Distance (Miles): {$distanceMiles}\n\n";
$emailBody .= "Load Details:\n{$message}\n";

$headers = [];
$headers[] = 'MIME-Version: 1.0';
$headers[] = 'Content-Type: text/plain; charset=UTF-8';
$headers[] = 'From: ' . $siteName . ' <no-reply@primelinklogistics.us>';
$headers[] = 'Reply-To: ' . $name . ' <' . $email . '>';
$headers[] = 'X-Mailer: PHP/' . phpversion();

$sent = mail($to, $emailSubject, $emailBody, implode("\r\n", $headers));

if (!$sent) {
    http_response_code(500);
    echo '<div class="error">Message sending failed. Please try again.</div>';
    exit;
}

echo '<div class="success">Thank you. Your request has been submitted successfully.</div>';

