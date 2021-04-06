<?php
require 'database.php';
require_once 'helper.php';
require_once 'users.php';

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
        $stmt->bind_param("is", intval($enable), $username);
        $stmt->execute();

        return true;
    } else {
        return false;
    }
}
