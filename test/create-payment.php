<?php
include('../helper/config.php');
include('../helper/func.php');

$method     = 'BRIVA';
$noref      = "A0011";
$amount     = 30000;
$customer   = "Arta";
$email      = "ar.pamuda@gmail.com";
$phone      = "083851378225";
$sku        = "AG011";
$product    = "Paket Ayam Geprek 2";

$detail = [
    'sku'         => $sku,
    'name'        => $product,
    'price'       => $amount,
    'quantity'    => 1,
];

$callback = $url . "test/callback.php";

echo pay($method, $noref, $amount, $customer, $email, $phone, $detail, $callback);
