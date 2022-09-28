<?php
include('../helper/config.php');
include('../helper/merchant_data.php');

function signature($noref, $amount)
{

    global $merchant_code;
    global $merchant_private;

    $signature = hash_hmac('sha256', $merchant_code . $noref . $amount, $merchant_private);

    return $signature;
}

function tripay($uri, $payload = array())
{
    global $merchant_api;
    global $tripay_url;

    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_FRESH_CONNECT  => true,
        CURLOPT_URL            => $tripay_url . $uri . '?' . http_build_query($payload),
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HEADER         => false,
        CURLOPT_HTTPHEADER     => ['Authorization: Bearer ' . $merchant_api],
        CURLOPT_FAILONERROR    => false,
        CURLOPT_IPRESOLVE      => CURL_IPRESOLVE_V4
    ));

    $response = curl_exec($curl);
    $error = curl_error($curl);

    curl_close($curl);

    return empty($error) ? $response : $error;
}

function pay($method, $noref, $amount, $customer, $email, $phone, $detail = array(), $callback)
{
    global $merchant_api;
    global $tripay_url;

    $signature = signature($noref, $amount);

    $data = [
        'method'         => $method,
        'merchant_ref'   => $noref,
        'amount'         => $amount,
        'customer_name'  => $customer,
        'customer_email' => $email,
        'customer_phone' => $phone,
        'order_items'    => [
            $detail,
        ],
        'callback_url'   => $callback,
        'expired_time' => (time() + (24 * 60 * 60)),
        'signature'    => $signature
    ];

    $curl = curl_init();

    curl_setopt_array($curl, [
        CURLOPT_FRESH_CONNECT  => true,
        CURLOPT_URL            => $tripay_url . 'transaction/create',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HEADER         => false,
        CURLOPT_HTTPHEADER     => ['Authorization: Bearer ' . $merchant_api],
        CURLOPT_FAILONERROR    => false,
        CURLOPT_POST           => true,
        CURLOPT_POSTFIELDS     => http_build_query($data),
        CURLOPT_IPRESOLVE      => CURL_IPRESOLVE_V4
    ]);

    $response = curl_exec($curl);
    $error = curl_error($curl);

    curl_close($curl);

    echo empty($error) ? $response : $error;
}
