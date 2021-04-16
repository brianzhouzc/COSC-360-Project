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
                $response = logout($connection, $username, $session);
                exitandclose($response, $connection);
            } else {
                exit(errorResponse(400, "Missing username/session"));
            }
            break;

        case "edit":
            $username = getValueFromKey($_POST, 'username');
            $session = getValueFromKey($_POST, 'session');
            $email = getValueFromKey($_POST, 'email');
            $password = getValueFromKey($_POST, 'password');

            if (isset_notempty($username, $session)) {
                if (authenticateUser($connection, $username, $session)) {
                    if ($_FILES["avatar"]["name"] !== '') {
                        $avatarProperties = getimagesize($_FILES["avatar"]["tmp_name"]);
                        if ($avatarProperties !== false) {
                            $avatar = imagecreatefromstring(file_get_contents($_FILES['avatar']['tmp_name']));
                            $avatar = imagescale($avatar, 100, 100);

                            ob_start();
                            imagepng($avatar);
                            $avatar = ob_get_contents(); // read from buffer
                            ob_end_clean(); // delete buffer

                            exit(edit($connection, $username, $email, $password, $avatar));
                        } else {
                            exit(errorResponse(400, "Invalid avatar - not an image"));
                        }
                    } else {
                        exit(edit($connection, $username, $email, $password, null));
                    }
                } else {
                    exit(errorResponse(400, "Unauthorize user"));
                }
            } else {
                exit(errorResponse(400, "Missing username/session"));
            }
            break;

        case "register":
            $username = getValueFromKey($_POST, 'username');
            $email = getValueFromKey($_POST, 'email');
            $password = getValueFromKey($_POST, 'password');
            if ($_FILES["avatar"]["name"] !== '') {
                $avatarProperties = getimagesize($_FILES["avatar"]["tmp_name"]);
                if ($avatarProperties !== false) {
                    $avatar = imagecreatefromstring(file_get_contents($_FILES['avatar']['tmp_name']));
                    $avatar = imagescale($avatar, 100, 100);

                    ob_start();
                    imagepng($avatar);
                    $avatar = ob_get_contents(); // read from buffer
                    ob_end_clean(); // delete buffer
                    if (isset_notempty($username, $email, $password)) {
                        $response = register($connection, $username, $email, md5($password), $avatar);
                        exitandclose($response, $connection);
                    } else {
                        exit(errorResponse(400, "Missing username/email/password"));
                    }
                } else {
                    exit(errorResponse(400, "Invalid avatar - not an image"));
                }
            } else {
                exit(errorResponse(400, "Missing avatar"));
                //exit(errorResponse(400, "Missing avatar"));
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

        case "info":
            $username = getValueFromKey($_POST, 'username');
            $session = getValueFromKey($_POST, 'session');
            if (isset_notempty($username, $session)) {
                if (authenticateUser($connection, $username, $session)) {
                    $user = getUserByName($connection, $username);
                    $data = array(
                        "user" =>
                        array(
                            "username" => $user['username'], "email" => $user['email']
                        )
                    );
                    exitandclose(dataResponse(200, "Success", $data), $connection);
                } else {
                    exit(errorResponse(400, "Unauthorize user"));
                }
            } else {
                exit(errorResponse(400, "Missing username/session"));
            }
            break;
        default:
            exit(errorResponse(400, "Invalid action"));
    }
} else {
    $connection->close();
    exit(errorResponse(400, "Missing action"));
}
