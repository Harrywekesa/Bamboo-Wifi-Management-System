<?php
require 'db.php';

// Log incoming callback data for debugging
file_put_contents('callback.log', json_encode($_POST) . PHP_EOL, FILE_APPEND);

// Parse callback data
$data = file_get_contents('php://input');
$callback = json_decode($data, true);

if (
    isset($callback['Body']['stkCallback']['ResultCode']) &&
    $callback['Body']['stkCallback']['ResultCode'] === 0
) {
    // Extract transaction details
    $checkout_request_id = $callback['Body']['stkCallback']['CheckoutRequestID'];
    $mpesa_receipt_number = $callback['Body']['stkCallback']['CallbackMetadata']['Item'][1]['Value'];
    $user_name = $callback['Body']['stkCallback']['CallbackMetadata']['Item'][0]['Value'];
    $mac_address = $_SERVER['HTTP_X_FORWARDED_FOR'] ?? $_SERVER['REMOTE_ADDR']; // Fetch client IP/MAC address

    // Update transaction in the database
    $stmt = $pdo->prepare("UPDATE transactions SET mpesa_code = ?, user_name = ?, mac_address = ? WHERE checkout_request_id = ?");
    $stmt->execute([$mpesa_receipt_number, $user_name, $mac_address, $checkout_request_id]);

    echo "Transaction verified successfully!";
} else {
    echo "Failed to verify transaction.";
}
?>