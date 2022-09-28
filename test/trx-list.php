<?php
include('../helper/func.php');

$payload = [
    'page' => 1,
    'per_page' => 50,
];

echo tripay('merchant/transactions', $payload);
