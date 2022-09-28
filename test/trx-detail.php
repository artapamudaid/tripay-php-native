<?php
include('../helper/func.php');

$ref     = "DEV-T981461590LZ6XH";

$payload =  [
    'reference' => $ref
];

echo tripay('transaction/detail', $payload);
