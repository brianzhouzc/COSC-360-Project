<?php
require 'database.php';
require_once 'helper.php';
require_once 'users.php';

function createPost($connection, $username, $session, $content)
{
    if (authenticateUser($connection, $username, $session)) {
        //$user = getUserByName($connection, $username);
        $sql = "INSERT INTO posts (username, content) VALUES (?, ?);";
        $stmt = $connection->prepare($sql);
        $stmt->bind_param("ss", $username, $content);
        $stmt->execute();
    } else {
        // smth else
    }
}

function updatePost($connection, $post_id, $username, $session, $content)
{
    if (authenticateUser($connection, $username, $session)) {
        $post = getPostById($connection, $post_id);
        if (strcmp($post['username'], $username) == 0) {
            //$user = getUserByName($connection, $username);
            $sql = "UPDATE posts SET content = ? WHERE post";
            $stmt = $connection->prepare($sql);
            $stmt->bind_param("ss", $username, $content);
            $stmt->execute();
            // updated
        } else {
            // user does not own post
        }
    } else {
        // smth else
    }
}

function removePost($connection, $post_id, $username, $session)
{
    if (authenticateUser($connection, $username, $session)) {
        $post = getPostById($connection, $post_id);
        if (strcmp($post['username'], $username) == 0) {
            //$user = getUserByName($connection, $username);
            $sql = "DELETE FROM posts WHERE id = ?;";
            $stmt = $connection->prepare($sql);
            $stmt->bind_param("i", $post_id);
            $stmt->execute();
            // deleted
        } else {
            // user does not own post
        }
    } else {
        // smth else
    }
}

function getPostById($connection, $post_id)
{
    $sql = "SELECT * FROM posts WHERE id = ?;";
    $stmt = $connection->prepare($sql);
    $stmt->bind_param("i", $post_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $post = $result->fetch_assoc();

    return $post;
}

function getPostsByUsername($connection, $username)
{
    $sql = "SELECT * FROM posts WHERE username = ?;";
    $stmt = $connection->prepare($sql);
    $stmt->bind_param("i", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    return $result;
}
