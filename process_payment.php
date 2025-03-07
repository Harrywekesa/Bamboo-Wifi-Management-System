<?php
require 'db.php';
session_start();

// Safaricom credentials
$shortcode = '174379'; // Sandbox shortcode
$passkey = 'bfb279f9aa9bdbcf158e97dd71a467cd2e0c893059b10f78e6b72ada1ed2c919'; // Sandbox passkey
$callback_url = 'https://dynamic-reliably-hyena.ngrok-free.app/hotspot/callback.php'; // Update with your ngrok URL

// Generate access token if not already set
if (!isset($_SESSION['mpesa_access_token'])) {
    require 'generate_access_token.php';
}

$access_token = $_SESSION['mpesa_access_token'];
$phone_number = $_POST['phone_number'] ?? '';
$package_name = $_POST['package_name'] ?? '';
$amount = $_POST['amount'] ?? '';

if (!$phone_number || !$package_name || !$amount) {
    echo json_encode(['success' => false, 'message' => 'Invalid data']);
    exit;
}

// Get package details
$stmt = $pdo->prepare("SELECT * FROM packages WHERE name = ?");
$stmt->execute([$package_name]);
$package = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$package) {
    echo json_encode(['success' => false, 'message' => 'Package not found']);
    exit;
}

// Generate password for STK Push
$timestamp = date('YmdHis');
$password = base64_encode($shortcode . $passkey . $timestamp);

// STK Push payload
$data = [
    'BusinessShortCode' => $shortcode,
    'Password' => $password,
    'Timestamp' => $timestamp,
    'TransactionType' => 'CustomerPayBillOnline',
    'Amount' => $amount,
    'PartyA' => $phone_number,
    'PartyB' => $shortcode,
    'PhoneNumber' => $phone_number,
    'CallBackURL' => $callback_url,
    'AccountReference' => 'Bamboo Tech Hotspot',
    'TransactionDesc' => 'Internet Package Purchase'
];

// Send STK Push request
$url = 'https://sandbox.safaricom.co.ke/mpesa/stkpush/v1/processrequest';
$curl = curl_init($url);

curl_setopt($curl, CURLOPT_HTTPHEADER, [
    'Authorization: Bearer ' . $access_token,
    'Content-Type: application/json'
]);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
curl_setopt($curl, CURLOPT_POST, true);
curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data));

$response = curl_exec($curl);
curl_close($curl);

$result = json_decode($response, true);

if ($result['ResponseCode'] === '0') {
    // Log transaction with CheckoutRequestID
    log_transaction(
        $pdo,
        $phone_number,
        $package_name,
        $amount,
        $result['CheckoutRequestID'], // Temporary placeholder for MPESA Code
        null, // User Name will be updated later
        null  // MAC Address will be fetched automatically
    );

    echo json_encode(['success' => true, 'message' => 'Payment request sent successfully']);
} else {
    echo json_encode(['success' => false, 'message' => 'Failed to send payment request']);
}
?>