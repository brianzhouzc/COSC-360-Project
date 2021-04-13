<?php
require_once 'database.php';
require_once 'helper.php';
require_once 'users_functions.php';

if (isset($_POST['action'])) {
    $action = $_POST['action'];

    switch ($action) {
        case "login":
            $username = getValueFromKey($_POST, 'username');
            $password = getValueFromKey($_POST, 'password');
            if (isset_notempty($username, $password)) {
                $username = $_POST['username'];
                $password = $_POST['password'];
                $response = login($connection, $username, $password);
                exitandclose($response, $connection);
            } else {
                exit(errorResponse(400, "Missing username/password"));
            }
            break;

        case "logout":
            $username = getValueFromKey($_POST, 'username');
            $session = getValueFromKey($_POST, 'session');
            if (isset_notempty($username, $session)) {
                $username = $_POST['username'];
                $session = $_POST['session'];
                $response = logout($connection, $username, $session);
                exitandclose($response, $connection);
            } else {
                exit(errorResponse(400, "Missing username/session"));
            }
            break;

        case "register":
            $username = getValueFromKey($_POST, 'username');
            $email = getValueFromKey($_POST, 'email');
            $password = getValueFromKey($_POST, 'password');
            $avatar = isset_notempty($_FILES['avatar']) ? $_FILES['avatar'] : null;

            if (isset_notempty($username, $email, $password)) {
                $password = md5($password);
                $response = register($connection, $username, $email, $password, $avatar);
                exitandclose($response, $connection);
            } else {
                exit(errorResponse(400, "Missing username/email/password"));
            }
            break;

        case "forgot":
            $email = getValueFromKey($_POST, 'email');
            $token = getValueFromKey($_POST, 'token');
            $password = getValueFromKey($_POST, 'password');
            if (isset_notempty($email)) {
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
