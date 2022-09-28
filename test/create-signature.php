<?php
include('../helper/func.php');

$noref  = "A0001";
$amount = 10000;

echo signature($noref, $amount);
