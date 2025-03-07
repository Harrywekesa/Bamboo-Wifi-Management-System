<?php
require 'db.php';

// Safaricom credentials
$consumer_key = 'coeGc6AxK4i2hTKjXulXxSSBFUQDEiWtM0SsgxkQJp01sRsJ'; // Replace with your API Key
$consumer_secret = '384urhcaNrYHLpIK54iI39tuZA9ckYke5IeJHzZizXeZNCAEGUY9QQKUoq7W8ib9'; // Replace with your API Secret

// Generate access token
function getAccessToken($consumer_key, $consumer_secret) {
    $url = 'https://sandbox.safaricom.co.ke/oauth/v1/generate?grant_type=client_credentials';
    $curl = curl_init($url);

    curl_setopt($curl, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_HEADER, false);
    curl_setopt($curl, CURLOPT_USERPWD, "$consumer_key:$consumer_secret");

    $result = curl_exec($curl);
    curl_close($curl);

    return json_decode($result, true)['access_token'] ?? '';
}

// Store access token in the session or database
$access_token = getAccessToken($consumer_key, $consumer_secret);
if ($access_token) {
    $_SESSION['mpesa_access_token'] = $access_token;
}
?>