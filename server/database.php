<?php
require_once 'helper.php';

$host = "localhost";
$database = "testdb";
$user = "webuser";
$password = "P@ssw0rd";

$connection = mysqli_connect($host, $user, $password, $database);

$error = mysqli_connect_error();
if ($error != null) {
	exit(errorResponse(500, "MySQL error."));
}
