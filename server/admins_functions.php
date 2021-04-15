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

        return (isset($results->fetch_assoc()));
    } else {
        return false;
    }
}

function enableUser($connection, $admin, $session, $username)
{
    return setUserEnable($connection, $admin, $session, $username, true);
}

function disableUser($connection, $admin, $session, $username)
{
    return setUserEnable($connection, $admin, $session, $username, false);
}

function setUserEnable($connection, $admin, $session, $username, $enable)
{
    if (authenticateAdmin($connection, $admin, $session)) {
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
    } else {
        return false;
    }
}

