<?php
require 'database.php';
require_once 'helper.php';

if (isset($_POST['action'])) {
    $action = $_POST['action'];
    switch ($action) {
        case "login":
            if (isset($_POST['username']) && isset($_POST['password'])) {
                $username = $_POST['username'];
                $password = $_POST['password'];
                login($connection, $username, $password);
            } else {
                exit(errorMsg(400, "Missing username/password"));
            }
            break;

        case "logout":
            if (isset($_POST['username']) && isset($_POST['session'])) {
                $username = $_POST['username'];
                $session = $_POST['session'];
                logout($connection, $username, $session);
            } else {
                exit(errorMsg(400, "Missing username/session"));
            }
            break;

        case "register":
            if (isset($_POST['username']) && isset($_POST['email']) && isset($_POST['password'])) {
                $username = $_POST['username'];
                $email = $_POST['email'];
                $password = md5($_POST['password']);
                $avatar = isset($_FILES['avatar']) ? $_FILES['avatar'] : null;

                register($connection, $username, $email, $password, $avatar);
            } else {
                exit(errorMsg(400, "Missing username/email/password"));
            }
            break;

        case "forgot":
            if (isset($_POST['email'])) {
                $email = $_POST['email'];
                $token = $_POST['token'];
                $password = $_POST['password'];

                forgot($connection, $email, $token, $password);
            } else {
                exit(errorMsg(400, "Missing email"));
            }
            break;

        default:
            exit(errorMsg(400, "Invalid action"));
    }
} else {
    $connection->close();
    exit(errorMsg(400, "Missing action"));
}

function login($connection, $username, $password)
{
    $user = getUserByName($connection, $username);
    if (isset($user)) {
        $password = md5($password);

        if (strcmp($user['password'], $password) == 0) {
            $session = generateRandomString(255);
            updateSession($connection, $username, $session);
            //LOGGED IN, PASS $session to front end, store $session in sessionStorage;
            echo (json_encode(array("session"=>$session)));
        } else {
            exit(errorMsg(400, "Invalid username/password"));
        }
    } else {
        exit(errorMsg(400, "User does not exsist"));
    }
}

function logout($connection, $username, $session)
{
    $user = getUserByName($connection, $username);
    if (isset($user)) {
        if (strcmp($user['session'], $session) == 0) {
            updateSession($connection, $username, NULL);

            // RETURN SUCCESS MESSAGE
            echo ('logged out');
        } else {
            exit(errorMsg(400, "Invalid user or session"));
        }
    } else {
        exit(errorMsg(400, "User does not exsist"));
    }
}

function register($connection, $username, $email, $password, $avatar)
{
    $user = getUserByNameOrEmail($connection, $username, $email);
    if (isset($user)) {
        exit(errorMsg(400, "Username/email already exsists"));
    } else {
        $sql = isset($avatar) ?
            "INSERT INTO users (username, email, password, avatar) VALUES (?, ?, ?, ?);" :
            "INSERT INTO users (username, email, password) VALUES (?, ?, ?);";

        $stmt = $connection->prepare($sql);
        if (isset($avatar))
            $stmt->bind_param("ssss", $username, $email, $password, $avatar);
        else
            $stmt->bind_param("sss", $username, $email, $password);

        $stmt->execute();
        // SUCCESS message
        echo ('registered');
    }
}

function forgot($connection, $email, $token, $password)
{
    $user = getUserByEmail($connection, $email);
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
                            echo ('Update pass');
                        } else {
                            // token expired. reset token and token_timestamp to null
                            $sql2 = "UPDATE users SET reset_token = NULL, reset_token_timestamp = NULL WHERE email = ?;";
                            $stmt2 = $connection->prepare($sql2);
                            $stmt2->bind_param("s", $email);
                            $stmt2->execute();
                            exit(errorMsg(400, "Password reset token expired"));
                        }
                    } else {
                        exit(errorMsg(400, "Invalid reset token"));
                    }
                } else {
                    exit(errorMsg(400, "No password reset request initiated"));
                }
            } else {
                exit(errorMsg(400, "Missing password"));
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
            echo ('token: ' . $token);
        }
    } else {
        exit(errorMsg(400, "Email does not exsist"));
    }
}

function updateSession($connection, $username, $session)
{
    $sql = "UPDATE users SET session = ? WHERE username = ?;";
    $stmt = $connection->prepare($sql);
    $stmt->bind_param("ss", $session, $username);
    $stmt->execute();
}

function authenticateUser($connection, $username, $session)
{
    $user = getUserByName($connection, $username);
    if (isset($user)) {
        if (strcmp($user['session'], $session) == 0)
            return true;
    }
    return false;
}
function getUserByNameOrEmail($connection, $username, $email)
{
    $sql = "SELECT * FROM users WHERE username = ? OR email = ?;";
    $stmt = $connection->prepare($sql);
    $stmt->bind_param("ss", $username, $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    return $user;
}

function getUserByName($connection, $username)
{
    $sql = "SELECT * FROM users WHERE username = ?;";
    $stmt = $connection->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    return $user;
}

function getUserByEmail($connection, $email)
{
    $sql = "SELECT * FROM users WHERE email = ?;";
    $stmt = $connection->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    return $user;
}

function getUserByPost()
{
}
