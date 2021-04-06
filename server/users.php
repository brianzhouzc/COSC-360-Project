<?php
require 'database.php';
require_once 'helper.php';

if (isset($_POST['action'])) {
    $action = $_POST['action'];
    $sql = '';
    switch ($action) {
        case "login":
            if (isset($_POST['username']) && isset($_POST['password'])) {
                $username = $_POST['username'];
                $password = $_POST['password'];
                login($connection, $username, $password);
            } else {
                exit(errorMsg(400, "fail", "Missing username/password"));
            }
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

                register($connection, $username, $email, $password, $avatar);
            } else {
                exit(errorMsg(400, "fail", "Missing username/email/password"));
            }
            break;

        case "forgot":
            if (isset($_POST['email'])) {
                $email = $_POST['email'];
                $token = $_POST['token'];
                $password = $_POST['password'];

                forgot($connection, $email, $token, $password);
            }
            break;
            
        default:
            exit(errorMsg(400, "fail", "Invalid action"));
    }
} else {
    $connection->close();
    exit(errorMsg(400, "fail", "Missing action"));
}

function login($connection, $username, $password)
{
    $user = getUser($connection, $username, null);
    if (isset($user)) {
        $password = md5($password);

        if (strcmp($user['password'], $password) == 0) {
            $session = generateRandomString(255);
            updateSession($connection, $username, $session);
            //LOGGED IN, PASS $session to front end, store $session in sessionStorage;
        } else {
            exit(errorMsg(400, "fail", "Invalid username/password"));
        }
    } else {
        exit(errorMsg(400, "fail", "User does not exsist"));
    }
}

function logout($connection, $username, $session)
{
    $user = getUser($connection, $username, null);
    if (isset($user)) {
        if (strcmp($user['session'], $session) == 0) {
            updateSession($connection, $username, NULL);

            // RETURN SUCCESS MESSAGE
        } else {
            exit(errorMsg(400, "fail", "Invalid user or session"));
        }
    } else {
        exit(errorMsg(400, "fail", "User does not exsist"));
    }
}

function register($connection, $username, $email, $password, $avatar)
{
    $user = getUser($connection, $username, $email);
    if (isset($user)) {
        exit(errorMsg(400, "fail", "Username/email already exsists"));
    } else {
        $sql = "INSERT INTO users (username, email, password, avatar) VALUES (?, ?, ?, ?);";
        $stmt = $connection->prepare($sql);
        $stmt->bind_param("sssb", $username, $email, $password, $avatar);
        $stmt->execute();
        // SUCCESS message
    }
}

function forgot($connection, $email, $token, $password)
{
    $user = getUser($connection, null, $email);
    if (isset($user)) {
        if (isset($token)) {
            if (isset($password)) {
                if (isset($user['reset_token']) && isset($user['reset_token_timestamp'])) {
                    if (strcmp($token, $user['reset_token']) == 0) {
                        //code sent less than an hour;
                        if (time() - $user['reset_token_timestamp'] < 3600) {
                            // Update password
                            $sql2 = "UPDATE users SET password = ? WHERE email = ?";
                            $stmt2 = $connection->prepare($sql2);
                            $stmt2->bind_param("ss", $password, $email);
                            $stmt2->execute();
                            /**** SEND CONFIRM RESPONSE ****/
                        } else {
                            // token expired. reset token and token_timestamp to null
                            $sql2 = "UPDATE users SET reset_token = NULL, reset_token_timestamp = NULL WHERE email = ?;";
                            $stmt2 = $connection->prepare($sql2);
                            $stmt2->bind_param("s", $email);
                            $stmt2->execute();
                            exit(errorMsg(400, "fail", "Password reset token expired"));
                        }
                    } else {
                        exit(errorMsg(400, "fail", "Invalid reset token"));
                    }
                } else {
                    exit(errorMsg(400, "fail", "No password reset request initiated"));
                }
            } else {
                exit(errorMsg(400, "fail", "Missing password"));
            }
        } else {
            $token = generateRandomString();
            $timestamp = time();
            $sql = "UPDATE users SET token = ?, token_timestamp = ? WHERE email = ?;";
            $stmt = $connection->prepare($sql);
            $stmt->bind_param("sis", $token, $timestamp, $email);
            $stmt->execute();
            // SEND RESET EMAIL SOMEHOW $token
            // SEND confirm message
        }
    } else {
        exit(errorMsg(400, "fail", "Email does not exsist"));
    }
}

function updateSession($connection, $username, $session)
{
    $sql = "UPDATE users SET session = ? WHERE username = ?;";
    $stmt = $connection->prepare($sql);
    $stmt->bind_param("ss", $session, $username);
    $stmt->execute();
}

function getUser($connection, $username, $email)
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
    $user = $result->fetch_assoc();

    return ($user);
}
