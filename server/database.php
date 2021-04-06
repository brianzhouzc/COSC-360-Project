<?php
require_once 'helper.php';

$host = "localhost";
$database = "lab9";
$user = "webuser";
$password = "P@ssw0rd";

$connection = mysqli_connect($host, $user, $password, $database);

$error = mysqli_connect_error();
if ($error != null) {
	exit(errorMsg(500, "error", "MySQL error."));
}
