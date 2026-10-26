<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json; charset=UTF-8');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Max-Age: 3600');
header('Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With');

// Handle preflight OPTIONS request
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

// Only allow POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
    exit();
}

// Get JSON input from the frontend
$inputJSON = file_get_contents('php://input');
$input = json_decode($inputJSON, TRUE);

if (!$input) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid JSON input']);
    exit();
}

// MyFatoorah API Token (Securely stored here in the backend)
$token = "_y8UH1oIf-EGEsBq7O-NrV_V2l_oir4czL9LWIYp1lilGu9lApEeo5G8p-AD-8MqniuogHWfoO8ETteoIXzHTC88haAHx98msJcUjlhKY-zmnkJRgA1_1zydv6SdeDC9IE6cWowNtp2qkT9uvlQj4p8ZTEb3q6B7YRYlErk0Hj3M4d-tUZdL4dP7d7EYhZ6XX6i3a5TtJYaN2qsyqRIOKZDkv0qF8B6RbzJkqjWK31DefjL6KXG-qUVqHEmF84q9Q8veq984_lNZmlq45yQ_XVkdhd8Z8TTo9KDUfKHrqyXLupwGHWWPmMfbqEOEuwW3xdAf0hBJXttPceeNMBAoib__lMw64AOqv66_Y72Uu2a_drDVw87SjlrTx-rwQcex1q2yN8QcWpIcNSsHDswAdF665zRans9nHHyhLJCkLb1pOItmQDWPKsxjBPhFnv8YS8MkrYWb-mTPZsZ_b9raOb-6vFn4Jt8mD_CSdNx7OwnzZNhjd78jOaiFSblY_qCFRpA5rubBevcgjRU0brhJYlKWd-0AuFfzCVsRNiM3kvGq9xG84uVSAJIfbKGmC4hdO8awSLzw6FnpTVv0fptOv-59x7X40KiG-saLr8mxhgJCulTPoYL5_dwKjUNVK9IPV6q2PxAfwxAcfUN3JsgJBYLPEDdBdJEO_IKa2nbLmIM6RSULqrDWJF8P9zHQfomGHhs08g";

// Setup cURL request to MyFatoorah
$url = 'https://api.myfatoorah.com/v2/SendPayment';
$ch = curl_init($url);

curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $inputJSON);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json',
    'Authorization: Bearer ' . $token
]);

// Execute cURL request
$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

if (curl_errno($ch)) {
    http_response_code(500);
    echo json_encode(['error' => 'cURL Error: ' . curl_error($ch)]);
} else {
    http_response_code($httpCode);
    echo $response;
}

curl_close($ch);
?>
