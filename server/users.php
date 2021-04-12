<?php
require_once 'database.php';
require_once 'helper.php';
require_once 'users_functions.php';

if (isset($_POST['action'])) {
    $action = $_POST['action'];
    switch ($action) {
        case "login":
            if (isset_notempty($_POST['username']) && isset_notempty($_POST['password'])) {
                $username = $_POST['username'];
                $password = $_POST['password'];
                $response = login($connection, $username, $password);
                exitandclose($response, $connection);
            } else {
                exit(errorResponse(400, "Missing username/password"));
            }
            break;

        case "logout":
            if (isset_notempty($_POST['username']) && isset_notempty($_POST['session'])) {
                $username = $_POST['username'];
                $session = $_POST['session'];
                $response = logout($connection, $username, $session);
                exitandclose($response, $connection);
            } else {
                exit(errorResponse(400, "Missing username/session"));
            }
            break;

        case "register":
            if (isset_notempty($_POST['username']) && isset_notempty($_POST['email']) && isset_notempty($_POST['password'])) {
                $username = $_POST['username'];
                $email = $_POST['email'];
                $password = md5($_POST['password']);
                $avatar = isset($_FILES['avatar']) ? $_FILES['avatar'] : null;
                $response = register($connection, $username, $email, $password, $avatar);
                exitandclose($response, $connection);
            } else {
                exit(errorResponse(400, "Missing username/email/password"));
            }
            break;

        case "forgot":
            if (isset_notempty($_POST['email'])) {
                $email = $_POST['email'];
                $token = $_POST['token'];
                $password = $_POST['password'];
                $response = forgot($connection, $email, $token, $password);
                exitandclose($response, $connection);
            } else {
                exit(errorResponse(400, "Missing email"));
            }
            break;

        default:
            exit(errorResponse(400, "Invalid action"));
    }
} else {
    $connection->close();
    exit(errorResponse(400, "Missing action"));
}