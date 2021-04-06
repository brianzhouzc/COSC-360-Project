<?php
require 'database.php';
require_once 'helper.php';

if (isset($_POST['action'])) {
    $action = $_POST['action'];
    $sql = '';
    switch ($action) {
        case "login":
            $sql = "SELECT * FROM users WHERE username = ?;";

            break;
        case "logout":
            $sql = "SELECT * FROM users WHERE username = ?;";

            break;
        case "register":
            if (isset($_POST['username']) && isset($_POST['email']) && isset($_POST['password'])) {
                $username = $_POST['username'];
                $email = $_POST['email'];
                $password = md5($_POST['password']);
                $avatar = $_FILES['avatar'];
                if (exsist($connection, $username, $email)) {
                    exit(errorMsg(400, "fail", "Username/email already exsists"));
                } else {
                    $sql = "INSERT INTO users (username, email, password, avatar) VALUES (?, ?, ?, ?);";
                    $stmt = $connection->prepare($sql);
                    $stmt->bind_param("sssb", $username, $email, $password, $_FILES['avatar']);
                    $stmt->execute();
                }
            } else {
                exit(errorMsg(400, "fail", "Missing username/email/password"));
            }
            break;
        case "forgot":
            $sql = "SELECT * FROM users WHERE username = ?;";

            break;
    }
} else {
    $connection->close();
    exit(errorMsg(400, "fail", "Missing action."));
}

function exsist($connection, $username, $email)
{
    $sql = '';
    $stmt = null;
    if (isset($username) && isset($email)) {
        $sql = "SELECT * FROM users WHERE username = ? OR email = ?;";
        $stmt = $connection->prepare($sql);
        $stmt->bind_param("ss", $username, $email);
    } else if (isset($username)) {
        $sql = "SELECT * FROM users WHERE username = ?;";
        $stmt = $connection->prepare($sql);
        $stmt->bind_param("s", $username);
    } else if (isset($email)) {
        $sql = "SELECT * FROM users WHERE email = ?;";
        $stmt = $connection->prepare($sql);
        $stmt->bind_param("s", $email);
    } else {
        return false;
    }

    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        return true;
    } else {
        return false;
    }
}
