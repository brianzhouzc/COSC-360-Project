<?php
require_once 'helper.php';

$test = '1';
$test2 = '2';
$test3 = '1';

echo (intval(isset_notempty($test, $test2, $test3)));