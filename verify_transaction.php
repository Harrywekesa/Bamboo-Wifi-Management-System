<?php
require 'db.php';
session_start();

// Safaricom credentials
$shortcode = 'N/A'; // Replace with your Lipa Na M-Pesa Online shortcode
$access_token = $_SESSION['mpesa_access_token'];

// Transaction details
$checkout_request_id = $_POST['checkout_request_id'] ?? '';

if (!$checkout_request_id) {
    echo json_encode(['success' => false, 'message' => 'Invalid CheckoutRequestID']);
    exit;
}

// Query transaction status
$url = 'https://sandbox.safaricom.co.ke/mpesa/stkpushquery/v1/query';
$data = [
    'BusinessShortCode' => $shortcode,
    'Password' => base64_encode($shortcode . 'YOUR_PASSKEY' . date('YmdHis')),
    'Timestamp' => date('YmdHis'),
    'CheckoutRequestID' => $checkout_request_id
];

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
    // Update transaction status in the database
    $mpesa_receipt_number = $result['Result']['ResultParameters']['ResultParameter'][1]['Value'];
    $user_name = $result['Result']['ResultParameters']['ResultParameter'][0]['Value'];
    $mac_address = $_SERVER['HTTP_X_FORWARDED_FOR'] ?? $_SERVER['REMOTE_ADDR'];

    $stmt = $pdo->prepare("UPDATE transactions SET mpesa_code = ?, user_name = ?, mac_address = ? WHERE checkout_request_id = ?");
    $stmt->execute([$mpesa_receipt_number, $user_name, $mac_address, $checkout_request_id]);

    echo json_encode(['success' => true, 'message' => 'Transaction verified successfully']);
} else {
    echo json_encode(['success' => false, 'message' => 'Failed to verify transaction']);
}
?>