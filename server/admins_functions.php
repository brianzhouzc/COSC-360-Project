<?php
require 'database.php';
require_once 'helper.php';
require_once 'users_functions.php';

function authenticateAdmin($connection, $username, $session)
{
    if (authenticateUser($connection, $username, $session)) {
        $sql = "SELECT * FROM admins WHERE username = ?;";
        $stmt = $connection->prepare($sql);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $results = $stmt->get_result();

        return ($results->num_rows > 0);
    } else {
        return false;
    }
}

function enableUser($connection, $username)
{
    return setUserEnable($connection, $username, true);
}

function disableUser($connection, $username)
{
    return setUserEnable($connection, $username, false);
}

function setUserEnable($connection, $username, $enable)
{
    $user = getUserByName($connection, $username);
    if (isset($user)) {
        $sql = "UPDATE users SET enable = ? WHERE username = ?;";
        $stmt = $connection->prepare($sql);
        $enable = intval($enable);
        $stmt->bind_param("is", $enable, $username);
        $stmt->execute();
        return true;
    } else {
        return false;
    }
}

function adminEditPost($connection, $post_id, $title, $content)
{
    $sql = "UPDATE posts SET title = ?, content = ? WHERE id = ?;";
    $stmt = $connection->prepare($sql);
    $stmt->bind_param("ssi", $title, $content, $post_id);
    $stmt->execute();
}
