<?php
include('../helper/func.php');

$payload = [];

echo tripay('merchant/payment-channel', $payload);
