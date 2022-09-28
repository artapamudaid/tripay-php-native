<?php

include('../helper/merchant_data.php');

// Ambil data JSON
$json = file_get_contents('php://input');

// Ambil callback signature
$callbackSignature = isset($_SERVER['HTTP_X_CALLBACK_SIGNATURE'])
    ? $_SERVER['HTTP_X_CALLBACK_SIGNATURE']
    : '';

// Generate signature untuk dicocokkan dengan X-Callback-Signature
$signature = hash_hmac('sha256', $json, $merchant_private);

// Validasi signature
if ($callbackSignature !== $signature) {
    exit(json_encode([
        'success' => false,
        'message' => 'Invalid signature',
    ]));
}

$data = json_decode($json);

if (JSON_ERROR_NONE !== json_last_error()) {
    exit(json_encode([
        'success' => false,
        'message' => 'Invalid data sent by payment gateway',
    ]));
}

// Hentikan proses jika callback event-nya bukan payent_status
if ('payment_status' !== $_SERVER['HTTP_X_CALLBACK_EVENT']) {
    exit(json_encode([
        'success' => false,
        'message' => 'Invalid callback event, no action was taken',
    ]));
}


$status = strtoupper((string) $data->status);

/*
|--------------------------------------------------------------------------
| Proses callback untuk closed payment
|--------------------------------------------------------------------------
*/

if (1 === (int) $data->is_closed_payment) {
    echo $status;
}
