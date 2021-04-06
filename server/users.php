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
            if (isset($_POST['email'])) {
                $email = $_POST['email'];

                if (exsist($connection, null, $email)) {
                    if (isset($_POST['token'])) {
                        $token = $_POST['token'];

                        if (isset($_POST['password'])) {
                            $password = md5($_POST['password']);
                            $sql = "SELECT token, token_timestamp FROM users WHERE $email = ?;";
                            $stmt = $connection->prepare($sql);
                            $stmt->bind_param("ss", $email, $token);
                            $stmt->execute();
                            $result = $stmt->get_result();
                            $user = $result->fetch_assoc();

                            if (isset($user['token']) && isset($user['token_timestamp'])) {
                                if (strcmp($token, $user['token']) == 0) {
                                    //code sent less than an hour;
                                    if (time() - $user['token_timestamp'] < 3600) {
                                        // Update password
                                        $sql2 = "UPDATE users SET password = ? WHERE email = ?";
                                        $stmt2 = $connection->prepare($sql2);
                                        $stmt2->bind_param("ss", $password, $email);
                                        $stmt2->execute();
                                        /**** SEND CONFIRM RESPONSE ****/
                                    } else {
                                        // token expired. reset token and token_timestamp to null
                                        $sql2 = "UPDATE users SET token = NULL, token_timestamp = NULL WHERE email = ?;";
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
            break;
        default:
            exit(errorMsg(400, "fail", "Invalid action"));
    }
} else {
    $connection->close();
    exit(errorMsg(400, "fail", "Missing action"));
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
